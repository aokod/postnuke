<?php
// File: $Id: pnUser.php 17851 2006-02-07 15:04:15Z markwest $
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
// Purpose of file: User functions
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Core
 * @subpackage PostNuke_pnAPI
*/

/**
 * Defines
 */

/**
 * Data types for User Properties
 */
define('_UDCONST_MANDATORY', -1); // indicates a cord field that can't be removed'
define('_UDCONST_CORE', 0); // indicates a core field (HACK, to be removed?)
define('_UDCONST_STRING', 1);
define('_UDCONST_TEXT', 2);
define('_UDCONST_FLOAT', 3);
define('_UDCONST_INTEGER', 4);

/**
 * Log the user in
 *
 * @param uname $ the name of the user logging in
 * @param pass $ the password of the user logging in
 * @param whether $ or not to remember this login if not set false
 * @return bool true if the user successfully logged in, false otherwise
 */
function pnUserLogIn($uname, $pass, $rememberme=false)
{
	$uname = isset($uname) ? $uname : '';
	if (!pnVarValidate($uname, 'uname') || !isset($pass)) {
		return false;
	}
	
    if (!pnUserLoggedIn()) {
        // get the database connection
        $dbconn =& pnDBGetConn(true);
        $pntable =& pnDBGetTables();

        // Get user information
        $userscolumn = &$pntable['users_column'];
        $userstable = $pntable['users'];

        $query = "SELECT $userscolumn[uid],
                         $userscolumn[pass]
                  FROM $userstable
                  WHERE $userscolumn[uname] = '" . pnVarPrepForStore($uname) ."'";
        $result =& $dbconn->Execute($query);

        if ($result->EOF) {
            return false;
        }

        list($uid, $realpass) = $result->fields;
        $result->Close();

		// check if we need to create a session
		if (!session_id()) {
			// Start session
			if (!pnSessionSetup()) {
				die('Session setup failed');
			}
			if (!pnSessionInit()) {
				die('Session initialisation failed');
			}
		}

        // Confirm that passwords match
        if (!comparePasswords($pass, $realpass, $uname, substr($realpass, 0, 2))) {
        	return false;
        }

        // Set user session information (new table)
        $sessioninfocolumn = &$pntable['session_info_column'];
        $sessioninfotable = $pntable['session_info'];
        $query = "UPDATE $sessioninfotable
                  SET $sessioninfocolumn[uid] = " . pnVarPrepForStore($uid) . "
                  WHERE $sessioninfocolumn[sessid] = '" . pnVarPrepForStore(session_id()) . "'";
        $dbconn->Execute($query);

        // Set session variables
        pnSessionSetVar('uid', (int)$uid);

        if (!empty($rememberme)) {
            pnSessionSetVar('rememberme', 1);
        }

        // now we've logged in the permissions previously calculated are invalid
        $GLOBALS['authinfogathered'] = 0;
    }

    return true;
}

/**
 * Compare Passwords
 */
function comparePasswords($givenpass, $realpass, $username, $cryptSalt = '')
{
    if(empty($givenpass) || empty($realpass) || empty($username))
		return false;
	
	$compare2crypt = true;
    $compare2text = true;
    
    $system = pnConfigGetVar('system');

    $md5pass = md5($givenpass);
    if (strcmp($md5pass, $realpass) == 0) {
        return $md5pass;
    } elseif ($compare2crypt && $system != "1" ) {
        $crypted = false;
        if ($cryptSalt != '') {
            if (strcmp(crypt($givenpass, $cryptSalt), $realpass) == 0) {
                $crypted = true;
            }
        } else {
            if (strcmp(crypt($givenpass, $cryptSalt), $realpass) == 0) {
                $crypted = true;
      }
        }
        if ($crypted) {
            updateUserPass($username, $md5pass);
            return $md5pass;
        }
    } elseif ($compare2text && strcmp($givenpass, $realpass) == 0) {
        updateUserPass($username, $md5pass);
        return $md5pass;
    }

    return false;
}

/**
 * Log the user out
 *
 * @public
 * @return bool true if the user successfully logged out, false otherwise
 */
function pnUserLogOut()
{
    if (pnUserLoggedIn()) {
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

        // Reset user session information (new table)
        $sessioninfocolumn = &$pntable['session_info_column'];
        $sessioninfotable = $pntable['session_info'];
        $query = "UPDATE $sessioninfotable
                  SET $sessioninfocolumn[uid] = 0
                  WHERE $sessioninfocolumn[sessid] = '" . pnVarPrepForStore(session_id()) . "'";
        $dbconn->Execute($query);

        pnSessionDelVar('rememberme');
        pnSessionDelVar('uid');
    pnSessionDestroy(session_id());
    }
}

/**
 * is the user logged in?
 *
 * @public
 * @returns bool true if the user is logged in, false if they are not
 */
function pnUserLoggedIn()
{
    if (pnSessionGetVar('uid')) {
        return true;
    } else {
        return false;
    }
}

/**
 * get all user variables
 *
 * @access public
 * @author Gregor J. Rothfuss
 * @since 1.33 - 2002/02/07
 * @param uid $ the user id of the user
 * @return array an associative array with all variables for a user
 */
function pnUserGetVars($uid)
{
    if(!isset($uid) || !is_numeric($uid)) {
        return false;
    }

    // establish the database connection
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // create an empty array to hold the user vars
    $vars = array();

    // TODO: review this code for performance.

    $propertiestable = $pntable['user_property'];
    $userstable = $pntable['users'];
    $datatable = $pntable['user_data'];
    $userscolumn = &$pntable['users_column'];
    $datacolumn = &$pntable['user_data_column'];
    $propcolumn = &$pntable['user_property_column'];

    $query = "SELECT $propcolumn[prop_label] as label, $datacolumn[uda_value] as value
              FROM $datatable, $propertiestable
              WHERE $datacolumn[uda_uid] = '" . pnVarPrepForStore($uid) ."' "
              ."AND $datacolumn[uda_propid] = $propcolumn[prop_id]";

    $result =& $dbconn->Execute($query);

    while (!$result->EOF) {
        $uservars = $result->GetRowAssoc(false);
        $vars[$uservars['label']] = $uservars['value'];
        $result->MoveNext();
    }

    $result->Close();

    $query = "SELECT *
              FROM $userstable
              WHERE $userscolumn[uid] = " . pnVarPrepForStore($uid);
    $result =& $dbconn->Execute($query);

    if ($result->EOF) {
        return false;
    }

    $corevars = $result->GetRowAssoc(false);
    $result->Close();

    $vars = array_merge ($vars, $corevars);
    // Aliasing if required
    if (empty($vars['uid'])) {
        $vars['uid'] = $vars['pn_uid'];
        $vars['email'] = $vars['pn_email'];
        $vars['femail'] = $vars['pn_femail'];
        $vars['name'] = $vars['pn_name'];
        $vars['theme'] = $vars['pn_theme'];
        $vars['timezone_offset'] = $vars['pn_timezone_offset'];
        $vars['uname'] = $vars['pn_uname'];
        $vars['ublock'] = $vars['pn_ublock'];
        $vars['ublockon'] = $vars['pn_ublockon'];
        $vars['user_avatar'] = $vars['pn_user_avatar'];
        $vars['user_icq'] = $vars['pn_user_icq'];
        $vars['user_aim'] = $vars['pn_user_aim'];
        $vars['user_yim'] = $vars['pn_user_yim'];
        $vars['user_msnm'] = $vars['pn_user_msnm'];
        $vars['user_from'] = $vars['pn_user_from'];
        $vars['user_occ'] = $vars['pn_user_occ'];
        $vars['user_intrest'] = $vars['pn_user_intrest'];
        $vars['user_sig'] = $vars['pn_user_sig'];
        $vars['bio'] = $vars['pn_bio'];
        $vars['url'] = $vars['pn_url'];
        $vars['storynum'] = $vars['pn_storynum'];
        $vars['umode'] = $vars['pn_umode'];
        $vars['uorder'] = $vars['pn_uorder'];
        $vars['thold'] = $vars['pn_thold'];
        $vars['noscore'] = $vars['pn_noscore'];
        $vars['commentmax'] = $vars['pn_commentmax'];
        $vars['pass'] = $vars['pn_pass'];
    }
    return($vars);
}

/**
 * get a user variable
 *
 * @public
 * @author Jim McDonald
 * @param name $ the name of the variable
 * @param uid $ the user to get the variable for
 * @return string the value of the user variable if successful, false otherwise
 */
function pnUserGetVar($name, $uid = -1)
{
    static $vars = array();

    if (empty($name)) {
        return;
    }

    // bug fix #1311 [landseer]
    if(isset($uid) && !is_numeric($uid) ) {
        return;
    }

    if ($uid == -1) {
        $uid = pnSessionGetVar('uid');
    }
    if (empty($uid)) {
        return;
    }

    // Get this user's variables if not already obtained
    if (!isset($vars[$uid])) {
        $vars[$uid] = pnUserGetVars($uid);
    }

    // Return the variable
    if (isset($vars[$uid][$name])) {
        return $vars[$uid][$name];
    } else {
        return;
    }
}

/**
 * set a user variable
 *
 * @access public
 * @author Gregor J. Rothfuss
 * @since 1.23 - 2002/02/01
 * @param name $ the name of the variable
 * @param value $ the value of the variable
 * @return bool true if the set was successful, false otherwise
 */
function pnUserSetVar($name, $value)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (empty($name)) {
        return false;
    }

    $uid = pnSessionGetVar('uid');
    if (empty($uid)) {
        return false;
    }

    if (pnUserFieldAlias($name)) {
        // this value comes from the users table
        $usertbl =  $pntable['users'];
        $usercol = &$pntable['users_column'];

        $sql = "UPDATE $usertbl
                SET    $usercol[$name] = '".pnVarPrepForStore($value)."'
                WHERE  $usercol[uid]   = '".pnVarPrepForStore($uid)."'";

        $dbconn->Execute($sql);

        if ($dbconn->ErrorNo() != 0) {
            return false;
        }

    } else {

        $propertiestable = $pntable['user_property'];
        $datatable = $pntable['user_data'];
        $propcolumns = &$pntable['user_property_column'];
        $datacolumns = &$pntable['user_data_column'];

        // Confirm that this is a known value
        $query = "SELECT $propcolumns[prop_id],
                         $propcolumns[prop_dtype]
                  FROM   $propertiestable
                  WHERE  $propcolumns[prop_label] = '" . pnVarPrepForStore($name) . "'";
        $result =& $dbconn->Execute($query);

        if ($result->EOF) {
            return false;
        }

        list ($id, $type) = $result->fields;
        // check for existence of the variable in user data
        $query = "SELECT $datacolumns[uda_id]
                  FROM   $datatable
                  WHERE  $datacolumns[uda_propid] = '" . pnVarPrepForStore($id) . "'
                  AND    $datacolumns[uda_uid] = '" . pnVarPrepForStore($uid) . "'";
        $result =& $dbconn->Execute($query);

        // jgm - this won't work in databases that care about typing
        // but this should get fixed when we move to the dynamic user
        // variables setup
        // TODO: do some checking with $type to maybe do conditional sql
        if ($result->EOF) {
            // record does not exist
            $query = "INSERT INTO $datatable
                                 ($datacolumns[uda_propid],
                                  $datacolumns[uda_uid],
                                  $datacolumns[uda_value])
                      VALUES     ('" . pnVarPrepForStore($id) . "',
                                  '" . pnVarPrepForStore($uid) . "',
                                  '" . pnVarPrepForStore($value) . "')";

            $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
                return false;
            }

        } else {
            // existing record
            $query = "UPDATE $datatable
                      SET    $datacolumns[uda_value]  = '" . pnVarPrepForStore($value) . "'
                      WHERE  $datacolumns[uda_propid] = '" . pnVarPrepForStore($id) . "'
                      AND    $datacolumns[uda_uid]    = '" . pnVarPrepForStore($uid) . "'";
            $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
                return false;
            }
        }

    }

    return true;
}

/**
 * delete the contents of a user variable
 *
 * @access public
 * @author Gregor J. Rothfuss
 * @since 1.23 - 2002/02/01
 * @param name $ the name of the variable
 * @return string true on success, false on failure
 */
function pnUserDelVar($name)
{
    // Prevent deletion of core fields (duh)
    if (empty($name) || ($name == 'uid') || ($name == 'email') ||
       ($name == 'password') || ($name == 'uname')) {
        return false;
    }
	
	$dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $propertiestable = $pntable['user_property'];
    $datatable = $pntable['user_data'];
    $propcolumns = &$pntable['user_property_column'];
    $datacolumns = &$pntable['user_data_column'];

    $uid = pnSessionGetVar('uid');
    if (empty($uid)) {
        return false;
    }

    // get property id for cascading delete later
    $query = "SELECT $propcolumns[prop_id] from $propertiestable
              WHERE $propcolumns[prop_label] = '" . pnVarPrepForStore($name) . "'";
    $result =& $dbconn->Execute($query);

    if ($result->EOF) {
        return false;
    }

    list ($id) = $result->fields;

    $query = "DELETE from $propertiestable
              WHERE $propcolumns[prop_id] = '" . pnVarPrepForStore($id) . "'";
    $result =& $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    // delete variable from user data for all users
    $query = "DELETE from $datatable
              WHERE $datacolumns[uda_propid] = '" . pnVarPrepForStore($id) . "'";
    $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    return true;
}

/**
 * get the user's theme
 * <br />
 * This function will return the current theme for the user.
 * Order of theme priority:
 *  - page-specific
 *  - category
 *  - user
 *  - system
 *
 * @public
 * @return string the name of the user's theme
 **/
function pnUserGetTheme()
{
  static $theme;
  if (isset($theme)) {
    return $theme;
  }

    // Page-specific theme
    $pagetheme = pnVarCleanFromInput('theme');
    if (!empty($pagetheme)) {
        $themeinfo = pnThemeInfo($pagetheme);
        if ($themeinfo && $themeinfo['active']) {
      $theme = $pagetheme;
            return $pagetheme;
        }
    }

    // set a new theme for the user
    $pagetheme = pnVarCleanFromInput('newtheme');
    if (!empty($pagetheme) && !pnConfigGetVar('theme_change')) {
        $themeinfo = pnThemeInfo($pagetheme);
        if ($themeinfo && $themeinfo['active']) {
            if (pnUserLoggedIn()) {
                $uid = pnUserGetVar('uid');
                $dbconn =& pnDBGetConn(true);
                $pntable =& pnDBGetTables();
                $column = &$pntable['users_column'];
                $sql = "UPDATE $pntable[users]
                        SET $column[theme]='" . pnVarPrepForStore($pagetheme) . "'
                        WHERE $column[uid]='" . pnVarPrepForStore($uid)."'";
                $dbconn->Execute($sql);
            } else {
                pnSessionSetVar('theme', $pagetheme);
            }
            $theme = $pagetheme;
            return $pagetheme;
        }
    }

  // eugenio themeover 20020413
  // override the theme per category or story
  // precedence is story over category override
  list($sid, $file) = pnVarCleanFromInput('sid', 'file');
  if (pnModGetName() == 'News' && (!empty($sid) || strtolower($file) == 'article')) {
    $modinfo = pnModGetInfo(pnModGetIDFromName('News'));
    include_once('modules/'.$modinfo['directory'].'/funcs.php');
    $pntable =& pnDBGetTables();
    $results = getArticles("{$pntable['stories_column']['sid']}='" . (int)pnVarPrepForStore($sid) . "'", "", "");
        if (is_array($results) && count($results) > 0) {
            $info = genArticleInfo($results[0]);
            $themeinfo = pnThemeInfo($info['catthemeoverride']);
            if ($themeinfo && $themeinfo['active']) {
                $theme = $info['catthemeoverride'];
                return $theme;
            }
            $themeinfo = pnThemeInfo($info['themeoverride']);
            if ($themeinfo && $themeinfo['active']) {
                $theme = $info['themeoverride'];
                return $theme;
            }
        }
  }

    // User theme
    if (!pnConfigGetVar('theme_change')) {
        if ((pnUserLoggedIn())) {
            $usertheme = pnUserGetVar('theme');
        } else {
            $usertheme = pnSessionGetVar('theme');
        }
        $themeinfo = pnThemeInfo($usertheme);
        if ($themeinfo && $themeinfo['active']) {
            $theme = $usertheme;
            return $usertheme;
        }
    }

    // default site theme
    $defaulttheme = pnConfigGetVar('Default_Theme');
    $themeinfo = pnThemeInfo($defaulttheme);
    if ($themeinfo && $themeinfo['active']) {
    $theme = $defaulttheme;
        return $theme;
    }
    return false;
}

/**
 * get the user's language
 *
 * @public <br>
 * jgm - the language parameter should be a user variable, not a
 *        session variable
 * @return string the name of the user's language
 */
function pnUserGetLang()
{
    $lang = pnSessionGetVar('lang');
    if (!empty($lang)) {
        return $lang;
    } else {
        return pnConfigGetVar('language');
    }
}

/**
 * get the options for commenting
 * <br>
 * This function is deprecated, use <code>pnUserGetcommentArray()</code> in
 * conjunction with <code>pnModURL()</code> to produce relevant URLs
 *
 * @deprecated
 * @public
 * @return string the comment options string
 */
function pnUserGetCommentOptions($implode=true)
{
    if (pnUserLoggedIn()) {
        $mode = pnUserGetVar('umode');
        $order = pnUserGetVar('uorder');
        $thold = pnUserGetVar('thold');
    }

    if (empty($mode)) {
        $mode = 'thread';
    }

    if (empty($order)) {
        $order = 0;
    }

    if (empty($thold)) {
        $thold = 0;
    }

  if ($implode) {
      return("mode=$mode&amp;order=$order&amp;thold=$thold");
  } else {
    return(array('mode' => $mode, 'order' => $order, 'thold' => $thold));
  }
}

/**
 * get the options for commenting
 *
 * @public
 * @return array the comment options array
 */
function pnUserGetCommentOptionsArray()
{
    if (pnUserLoggedIn()) {
        $mode = pnUserGetVar('umode');
        $order = pnUserGetVar('uorder');
        $thold = pnUserGetVar('thold');
    }

    if (empty($mode)) {
        $mode = 'thread';
    }

    if (empty($order)) {
    $order = 0;
    }

    if (empty($thold)) {
        $thold = 0;
    }

    return array('mode' => $mode,
                 'order' => $order,
                 'thold' => $thold);
}

/**
 * get a list of user information
 *
 * @public
 * @return array array of user arrays
 */
function pnUserGetAll()
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    pnModDBInfoLoad('Users');

    $userstable = $pntable['users'];
    $userscolumn = &$pntable['users_column'];
    $sql = "SELECT $userscolumn[uname],
                   $userscolumn[uid],
                   $userscolumn[name],
                   $userscolumn[email],
                   $userscolumn[url],
                   $userscolumn[user_avatar]
            FROM $userstable";
    $result =& $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0) {
        return;
    }

    if ($result->EOF) {
        return false;
    }

    $resarray = array();
    while (!$result->EOF) {
        list($uname, $uid, $name, $email, $url, $user_avatar) = $result->fields;
        $result->MoveNext();
        $resarray[$uid] = array('uname' => $uname,
                                'uid' => $uid,
                                'name' => $name,
                                'email' => $email,
                                'url' => $url,
                                'avatar' => $user_avatar);
    }
    $result->Close();

    return $resarray;
}

/**
 * Get the uid of a user from the username
 *
 * @access public
 * @author Michael Halbrook
 * @since 1.9 - 19/04/2004
 * @param uname $ the username
 * @return mixed userid if found, false if not, void upon error
 */
function pnUserGetIDFromName($uname)
{
    $uname = isset($uname) ? $uname : '';

	if (!pnVarValidate($uname, 'uname')) {
        return false;
    }
    
    static $uid = array();
    if (isset($uid[$uname])) {
        return $uid[$uname];
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $userstable = $pntable['users'];
    $userscolumn = &$pntable['users_column'];
    $query = "SELECT $userscolumn[uid]
              FROM $userstable
              WHERE $userscolumn[uname] = '" . pnVarPrepForStore($uname) . "'";

    $result =& $dbconn->Execute($query);

    if($dbconn->ErrorNo() != 0) {
        return;
    }

    if ($result->EOF) {
        $uid[$uname] = false;
        return false;
    }

    list($uid[$uname]) = $result->fields;
    $result->Close();

    return $uid[$uname];
}

/**
 * Checks the alias and returns if we save the data in the
 * user_data table or the users table.
 * This should be removed if we ever go fully dynamic
 *
 * @access private
 * @author F. Chestnut
 * @since 1.26 - 19/04/2004
 * @param label $ the alias of the field to check
 * @return true if found, false if not, void upon error
 */
function pnUserFieldAlias($label)
{
    if (empty($label)) {
        return false;
    }

    $vars = array();

    $vars = array('name',
                  'email',
                  'femail',
                  'url',
                  'user_avatar',
                  'user_icq',
                  'user_occ',
                  'user_from',
                  'user_intrest',
                  'user_sig',
                  'user_theme',
                  'user_aim',
                  'user_yim',
                  'user_msnm',
                  'bio',
                  'theme',
                  'timezone_offset');

    if (in_array($label, $vars)) {
        return true;
    } else {
        return false;
    }

}

?>