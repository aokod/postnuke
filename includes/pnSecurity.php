<?php 
// File: $Id: pnSecurity.php 16668 2005-08-21 15:59:03Z markwest $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// Based on:
// PHP-NUKE Web Portal System - http://phpnuke.org/
// Thatware - http://thatware.org/
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Jim McDonald
// Purpose of file: Provide a low-level security access mechanism
// ----------------------------------------------------------------------
/**
 * @package PostNuke_Core
 * @subpackage PostNuke_pnAPI
 */
/**
 * Notes on security system
 *
 * Special UID and GIDS:
 *  UID -1 corresponds to 'all users', includes unregistered users
 *  GID -1 corresponds to 'all groups', includes unregistered users
 *  UID 0 corresponds to unregistered users
 *  GID 0 corresponds to unregistered users
 */

/**
 * Defines for access levels
 */
define('ACCESS_INVALID', -1);
define('ACCESS_NONE', 0);
define('ACCESS_OVERVIEW', 100);
define('ACCESS_READ', 200);
define('ACCESS_COMMENT', 300);
define('ACCESS_MODERATE', 400);
define('ACCESS_EDIT', 500);
define('ACCESS_ADD', 600);
define('ACCESS_DELETE', 700);
define('ACCESS_ADMIN', 800);

/**
 * Translation functions - avoids globals in external code
 */
// Translate level -> name
function accesslevelname($level)
{
	if(!isset($level)) {
		return null;
	}
	
    $accessnames = accesslevelnames();
    return $accessnames[$level];
}

// Get all level -> name
function accesslevelnames()
{
    static $accessnames = array(  0 => _ACCESS_NONE,
                                100 => _ACCESS_OVERVIEW,
                                200 => _ACCESS_READ,
                                300 => _ACCESS_COMMENT,
                                400 => _ACCESS_MODERATE,
                                500 => _ACCESS_EDIT,
                                600 => _ACCESS_ADD,
                                700 => _ACCESS_DELETE,
                                800 => _ACCESS_ADMIN);

    return $accessnames;
}

/**
 * schemas - holds all component/instance schemas
 * Should wrap this in a static one day, but the information
 * isn't critical so we'll do it later
 */
global $schemas;
$schemas = array();

/**
 * addinstanceschemainfo - register an instance schema with the security
 *                         system
 *
 * Takes two parameters:
 * - a component
 * - an instance schema
 *
 * Will fail if an attempt is made to overwrite an existing schema
 */
function addinstanceschemainfo($component, $schema)
{
	if (!isset($component) || !isset($schema)) 
		return null;
		
    pnSecAddSchema($component, $schema);
}

function pnSecAddSchema($component, $schema)
{	
   if (empty($component) || empty($schema)) 
		return false;

    global $schemas;
    
    if (!empty($schemas[$component])) {
        return false;
    }

    $schemas[$component] = $schema;

    return true;
}

// Get list of schemas
function getinstanceschemainfo()
{
   global $schemas;
    static $gotschemas = 0;

    if ($gotschemas == 0) {
        // Get all module schemas
        getmodulesinstanceschemainfo();

        // Get all block schemas
        pnBlockLoadAll();

        $gotschemas = 1;
    }

    return $schemas;
}

// Get instance information from modules
function getmodulesinstanceschemainfo()
{
    $moddir = opendir('modules/');
    while ($modname = readdir($moddir)) {
        // Old-style version file
        $osfile = 'modules/' . pnVarPrepForOS($modname) . '/Version.php';
        @include $osfile;
        // New-style version file
        $osfile = 'modules/' . pnVarPrepForOS($modname) . '/pnversion.php';
        @include $osfile;
        if (!empty($modversion['securityschema'])) {
            foreach ($modversion['securityschema'] as $component => $instance) {
                pnSecAddSchema($component, $instance);
            }
        }
        $modversion['securityschema'] = '';
    }
    closedir($moddir);
}

function authorised($testrealm, $testcomponent, $testinstance, $testlevel)
{
	$testrealm = isset($testrealm) ? $testrealm : null;
	$testcomponent = isset($testcomponent) ? $testcomponent : null;
	$testinstance = isset($testinstance) ? $testinstance : null;
	$testlevel = isset($testlevel) ? $testlevel : 0;

	// Wrapper for new pnSecAuthAction() function
    return pnSecAuthAction($testrealm, $testcomponent, $testinstance, $testlevel);
}

/**
 * see if a user is authorised to carry out a particular task
 * @public
 * @param realm the realm under test
 * @param component the component under test
 * @param instance the instance under test
 * @param level the level of access required
 * @return bool true if authorised, false if not
 */
function pnSecAuthAction($testrealm, $testcomponent, $testinstance, $testlevel=0)
{

	$testrealm = isset($testrealm) ? $testrealm : 0;
	$testcomponent = isset($testcomponent) ? $testcomponent : null;
	$testinstance = isset($testinstance) ? $testinstance : null;

	if (strlen($testcomponent) == 0  || strlen($testrealm) == 0) {
		return false;
	}
	
	static $userperms, $groupperms;
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!isset($GLOBALS['authinfogathered']) || (int)$GLOBALS['authinfogathered'] == 0) {
        // First time here - get auth info
        list($userperms, $groupperms) = pnSecGetAuthInfo();

        if ((count($userperms) == 0) &&
            (count($groupperms) == 0)) {
                // No permissions
                return false;
        }
    }

    // Get user access level
    $userlevel = pnSecGetLevel($userperms, $testrealm, $testcomponent, $testinstance);
	
    // User access level is override, so return that if it exists
    if ($userlevel > ACCESS_INVALID) {
        // user has explicitly defined access level for this
        // realm/component/instance combination
		return $userlevel >= $testlevel;
    }

	return pnSecGetLevel($groupperms, $testrealm, $testcomponent, $testinstance) >= $testlevel;
}

/**
 * get authorisation information for this user
 * 
 * @public 
 * @return array two element array of user and group permissions
 */
function pnSecGetAuthInfo()
{
    // Load the groups db info
	pnModDBInfoLoad('Groups');
	pnModDBInfoLoad('Permissions');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Tables we use
    $userpermtable = $pntable['user_perms'];
    $userpermcolumn = &$pntable['user_perms_column'];

    $groupmembershiptable = $pntable['group_membership'];
    $groupmembershipcolumn = &$pntable['group_membership_column'];

    $grouppermtable = $pntable['group_perms'];
    $grouppermcolumn = &$pntable['group_perms_column'];

    $realmtable = $pntable['realms'];
    $realmcolumn = &$pntable['realms_column'];

    // Empty arrays
    $userperms = array();
    $groupperms = array();

    $uids[] = -1;
    // Get user ID
    if (!pnUserLoggedIn()) {
        // Unregistered UID
        $uids[] = 0;
        $vars['Active User'] = 'unregistered';
    } else {
        $uids[] = pnUserGetVar('uid');
        $vars['Active User'] = pnUserGetVar('uid');
    }
    $uids = implode(",", $uids);

    // Get user permissions
    $query = "SELECT $userpermcolumn[realm],
                     $userpermcolumn[component],
                     $userpermcolumn[instance],
                     $userpermcolumn[level]
              FROM $userpermtable
              WHERE $userpermcolumn[uid] IN (" . pnVarPrepForStore($uids) . ")
              ORDER by $userpermcolumn[sequence]";
    $result =& $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return array($userperms, $groupperms);
    }

	while (list($realm, $component, $instance, $level) = $result->fields) {
        $result->MoveNext();
		//itevo
		$component = fixsecuritystring($component);
		$instance = fixsecuritystring($instance);
        $userperms[] = array('realm'     => $realm,
                             'component' => $component,
                             'instance'  => $instance,
                             'level'     => $level);
    }

    // Get all groups that user is in
    $query = "SELECT $groupmembershipcolumn[gid]
              FROM $groupmembershiptable
              WHERE $groupmembershipcolumn[uid] IN (" . pnVarPrepForStore($uids) . ")";

    $result =& $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return array($userperms, $groupperms);
    }

    $usergroups[] = -1;
    if (!pnUserLoggedIn()) {
        // Unregistered GID
        $usergroups[] = 0;
    }
	while (list($gid) = $result->fields) {
        $result->MoveNext();
        $usergroups[] = $gid;
    }
    $usergroups = implode(",", $usergroups); 

    // Get all group permissions
    $query = "SELECT $grouppermcolumn[realm],
                     $grouppermcolumn[component],
                     $grouppermcolumn[instance],
                     $grouppermcolumn[level]
              FROM $grouppermtable
              WHERE $grouppermcolumn[gid] IN (" . pnVarPrepForStore($usergroups) . ")
              ORDER by $grouppermcolumn[sequence]";
    $result =& $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return array($userperms, $groupperms);
    }

    while(list($realm, $component, $instance, $level) = $result->fields) {
        $result->MoveNext();
		//itevo
		$component = fixsecuritystring($component);
		$instance = fixsecuritystring($instance);
        // Search/replace of special names
		preg_match_all("/<([^>]+)>/", $instance, $res);
		for($i = 0; $i < count($res[1]); $i++) {
			$instance = preg_replace("/<([^>]+)>/", $vars[$res[1][$i]], $instance, 1);
		}
        $groupperms[] = array('realm'     => $realm,
                              'component' => $component,
                              'instance'  => $instance,
                              'level'     => $level);
    }

	// we've now got the permissions info
	$GLOBALS['authinfogathered'] = 1;

    return array($userperms, $groupperms);
}

/**
 * calculate security level for a test item
 * 
 * @public 
 * @param perms $ array of permissions to test against
 * @param testrealm $ realm of item under test
 * @param testcomponent $ component of item under test
 * @param testinstanc $ instance of item under test
 * @return int matching security level
 */
function pnSecGetLevel($perms, $testrealm, $testcomponent='', $testinstance='')
{
    $level = ACCESS_INVALID;

    if (!isset($perms) || empty($perms)) {
    	return $level;
    }
    
    // If we get a test component or instance purely consisting of ':' signs
    // then it counts as blank
	//itevo
  	if ($testcomponent == str_repeat(':', strlen($testcomponent)) ) {
  	    $testcomponent = '';
  	}
  	if ($testinstance == str_repeat(':', strlen($testinstance)) ) {
  	    $testinstance = '';
  	}

    // Test for generic permission
    if ((empty($testcomponent)) &&
        (empty($testinstance))) {
        // Looking for best permission
        foreach ($perms as $perm) {
            // Confirm generic realm, or this particular realm
            if (($perm['realm'] != 0) && ($perm['realm'] != $testrealm)) {
                continue;
            }

            if ($perm['level'] > $level) {
                $level = $perm['level'];
            }
        }
        return $level;
    }

    // Test for generic instance
    // additional fixes by BMW [larsneo]
    // if the testinstance is empty, then we're looking for the per-module
    // permissions.
    if (empty($testinstance)) {
        // if $testinstance is empty, then there must be a component.
        // Looking for best permission
        foreach ($perms as $perm) {
            // Confirm generic realm, or this particular realm
            if (($perm['realm'] != 0) && ($perm['realm'] != $testrealm)) {
                continue;
            }
    
            // component check
            if (!preg_match("=^$perm[component]$=", $testcomponent)) {
                // component doestn't match.
                continue;
            }

            // check that the instance matches :: or '' (nothing)
            if (! (preg_match("=^$perm[instance]$=", '::') || 
                   preg_match("=^$perm[instance]$=",'')) ) {
                // instance does not match
                continue;
            }

            // We have a match - set the level and quit
            $level = $perm['level'];
            break;

        }
        return $level;
    }
    // Normal permissions check
    // there *is* a $testinstance at this point.
    foreach ($perms as $perm) {
        // Confirm generic realm, or this particular realm
        if (($perm['realm'] != 0) && ($perm['realm'] != $testrealm)) {
            continue;
        }

        // BMW: the ($testinstance != '') check is silly, it has to be
        // something or it would have been taken care of above.

        // if there is a component, check that it matches
        if ( ($testcomponent != '') &&
             (!preg_match("=^$perm[component]$=", $testcomponent)) ) {
            // component exists, and doestn't match.
            continue;
        }

        // Confirm that instance matches
        if (!preg_match("=^$perm[instance]$=", $testinstance)) {
            // instance does not match
            continue;
        }

        // We have a match - set the level and quit looking
        $level = $perm['level'];
        break;

    }
    return($level);
}

/**
 * generate an authorisation key
 * <br />
 * The authorisation key is used to confirm that actions requested by a
 * particular user have followed the correct path.  Any stage that an
 * action could be made (e.g. a form or a 'delete' button) this function
 * must be called and the resultant string passed to the client as either
 * a GET or POST variable.  When the action then takes place it first calls
 * <code>pnSecConfirmAuthKey()</code> to ensure that the operation has
 * indeed been manually requested by the user and that the key is valid
 * 
 * @public 
 * @param modname $ the module this authorisation key is for (optional)
 * @return string an encrypted key for use in authorisation of operations
 */
function pnSecGenAuthKey($modname = '')
{
    // since we need sessions for authorisation keys we should check
    // if a session exists and if not create one
    if (!session_id()) {
        // Start session
        if (!pnSessionSetup()) {
            die('Session setup failed');
        }
        if (!pnSessionInit()) {
            die('Session initialisation failed');
        }
    }

    if (empty($modname)) {
        $modname = pnVarCleanFromInput('module');
    }

	// get the module info
	$modinfo = pnModGetInfo(pnModGetIDFromName($modname));

    // Date gives extra security but leave it out for now
    // $key = pnSessionGetVar('rand') . $modname . date ('YmdGi');
    $key = pnSessionGetVar('rand') . strtolower($modinfo['name']);

    // Encrypt key
    $authid = md5($key);

    // Return encrypted key
    return $authid;
}

/**
 * confirm an authorisation key is valid
 * <br />
 * See description of <code>pnSecGenAuthKey</code> for information on
 * this function
 * 
 * @public 
 * @return bool true if the key is valid, false if it is not
 */
function pnSecConfirmAuthKey()
{
    list($module, $authid) = pnVarCleanFromInput('module', 'authid');

	// get the module info
	$modinfo = pnModGetInfo(pnModGetIDFromName($module));

    // Regenerate static part of key
    $partkey = pnSessionGetVar('rand') . strtolower($modinfo['name']);

    // Not using time-sensitive keys for the moment
    // // Key life is 5 minutes, so search backwards and forwards 5
    // // minutes to see if there is a match anywhere
    // for ($i=-5; $i<=5; $i++) {
    // $testdate  = mktime(date('G'), date('i')+$i, 0, date('m') , date('d'), date('Y'));
    
    // $testauthid = md5($partkey . date('YmdGi', $testdate));
    // if ($testauthid == $authid) {
    // // Match
    
    // // We've used up the current random
    // // number, make up a new one
    // srand((double)microtime()*1000000);
    // pnSessionSetVar('rand', rand());
    
    // return true;
    // }
    // }

    if ((md5($partkey)) == $authid) {
        // Match - generate new random number for next key and leave happy
        srand((double)microtime() * 1000000);
        pnSessionSetVar('rand', rand());

        return true;
    }

    // Not found, assume invalid
    return false;
}


function fixsecuritystring($string) 
{
	if (empty($string)) {
	    $string = '.*';
	}
	if (strpos($string, ':') === 0) {
	    $string = '.*' . $string;
	}
	$string = str_replace('::', ':.*:', $string);
	if (strrpos($string, ':') === strlen($string) - 1) {
		$string = $string . '.*';
	}
	return $string;
}

?>