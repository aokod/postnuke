<?php
// File: $Id: pnadminapi.php 19235 2006-06-08 14:47:19Z markwest $
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

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Jim McDonald
// Purpose of file:  Modules administration API
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Modules
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * update module information
 * @author Jim McDonald
 * @param int $args ['mid'] the id number of the module to update
 * @param string $args ['displayname'] the new display name of the module
 * @param string $args ['description'] the new description of the module
 * @return bool true on success, false on failure
 */
function modules_adminapi_update($args)
{
    // Get arguments from argument array
    extract($args);

    // Argument check
    if ((!isset($mid) || !is_numeric($mid)) ||
            (!isset($displayname)) ||
            (!isset($description))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }
    // Security check
    if (!pnSecAuthAction(0, 'Modules::', "::$mid", ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // check for duplicate display names
    // get the module info for the module being updated
    $moduleinforeal = pnModGetInfo($mid);
    // attempt to get module info for the module whose display name is the one
    // entered into the form
    $moduleinfodisplay = pnModGetInfo(pnModGetIDFromName($displayname));
    // If the two real module name don't match then the new display name can't be used
    if ($moduleinfodisplay && $moduleinforeal['name'] != $moduleinfodisplay['name']) {
        pnSessionSetVar('errormsg', _MODULESDUPLICATEDEFINE);
        return false;
    }

    // Rename operation
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $modulestable = $pntable['modules'];
    $modulescolumn = &$pntable['modules_column'];
    $query = "UPDATE $modulestable
              SET $modulescolumn[displayname] = '" . pnVarPrepForStore($displayname) . "',
                  $modulescolumn[description] = '" . pnVarPrepForStore($description) . "'
              WHERE $modulescolumn[id] = '" . (int)pnVarPrepForStore($mid) . "'";
    $result =& $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _MODULESAPIUPDATEFAILED);
        return false;
    }
	$result->Close();
    return true;
}

/**
 * update module hook information
 * @author Jim McDonald
 * @param int $args ['mid'] the id number of the module to update
 * @return bool true on success, false on failure
 */
function modules_adminapi_updatehooks($args)
{
    // Get arguments from argument array
    extract($args);

    // Argument check
    if ((!isset($mid) || !is_numeric($mid))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }
    // Security check
    if (!pnSecAuthAction(0, 'Modules::', "::$mid", ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }
    // Rename operation
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $modulestable = $pntable['modules'];
    $modulescolumn = &$pntable['modules_column'];
    $hookstable = $pntable['hooks'];
    $hookscolumn = &$pntable['hooks_column'];

    // Hooks
    // Get module name
    $modinfo = pnModGetInfo($mid);

    // Delete hook regardless
    $sql = "DELETE FROM $hookstable
            WHERE $hookscolumn[smodule] = '" . pnVarPrepForStore($modinfo['name']) . "'
            AND $hookscolumn[tmodule] IS NOT NULL";
    $dbconn->Execute($sql);

    $sql = "SELECT DISTINCT $hookscolumn[id],
                            $hookscolumn[smodule],
                            $hookscolumn[stype],
                            $hookscolumn[object],
                            $hookscolumn[action],
                            $hookscolumn[tarea],
                            $hookscolumn[tmodule],
                            $hookscolumn[ttype],
                            $hookscolumn[tfunc]
            FROM $hookstable
            WHERE $hookscolumn[smodule] IS NULL
            ORDER BY $hookscolumn[tmodule],
                     $hookscolumn[smodule] DESC";
    $result =& $dbconn->Execute($sql);
    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    $displayed = array();
    for (; !$result->EOF; $result->MoveNext()) {
        list($hookid,
            $hooksmodname,
            $hookstype,
            $hookobject,
            $hookaction,
            $hooktarea,
            $hooktmodule,
            $hookttype,
            $hooktfunc,) = $result->fields;

        // Get selected value of hook
        $hookvalue = pnVarCleanFromInput("hooks_$hooktmodule");
        // See if this is checked and isn't in the database
        if ((isset($hookvalue)) && (empty($hooksmodname))) {

            // Get next ID in table - this is required prior to any insert that
            // uses a unique ID, and ensures that the ID generation is carried
            // out in a database-portable fashion
            $nextid = $dbconn->GenId($hookstable);

            // Insert hook if required
            $sql = "INSERT INTO $hookstable (
                      $hookscolumn[id],
                      $hookscolumn[object],
                      $hookscolumn[action],
                      $hookscolumn[smodule],
                      $hookscolumn[tarea],
                      $hookscolumn[tmodule],
                      $hookscolumn[ttype],
                      $hookscolumn[tfunc])
                    VALUES (
                      '" . (int)pnVarPrepForStore($nextid) . "',
                      '" . pnVarPrepForStore($hookobject) . "',
                      '" . pnVarPrepForStore($hookaction) . "',
                      '" . pnVarPrepForStore($modinfo['name']) . "',
                      '" . pnVarPrepForStore($hooktarea) . "',
                      '" . pnVarPrepForStore($hooktmodule) . "',
                      '" . pnVarPrepForStore($hookttype) . "',
                      '" . pnVarPrepForStore($hooktfunc) . "')";

            $hookresult = &$dbconn->Execute($sql);

            if ($dbconn->ErrorNo() != 0) {
                return false;
            }

            // Get the ID of the item that we inserted.  It is possible, although
            // very unlikely, that this is different from $nextId as obtained
            // above, but it is better to be safe than sorry in this situation
            $id = $dbconn->PO_Insert_ID($hookstable, $hookscolumn['id']);

        }
    }
    $result->Close();

    return true;
}

/**
 * obtain list of modules
 * @author Jim McDonald
 * @return array associative array of known modules
 */
function modules_adminapi_list($args)
{
    // Security check
    if (!pnSecAuthAction(0, 'Modules::', '::', ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Optional arguments.
    $startnum = (empty($startnum) || $startnum < 0)  ?  1 : (int)$startnum;
    $numitems = (empty($numitems) || $numitems < 0)  ? -1 : (int)$numitems;
	$state = 	(empty($state) || $state<0 || $state>5)  ? 0 : (int)$state;

    // Obtain information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $modulestable = $pntable['modules'];
    $modulescolumn = &$pntable['modules_column'];

	// filter my first letter of module
	if (isset($letter) && !empty($letter)) {
	    $where[] = "$modulescolumn[name] LIKE '" . pnVarPrepForStore($letter) . "%'";
		// why reset startnum here? This prevents moving to a second page within
		// a lettered filter - markwest
		//$startnum = 1;
	}

	// filter by module state
	switch ($state) {
		case _PNMODULE_STATE_UNINITIALISED:
		case _PNMODULE_STATE_INACTIVE:
		case _PNMODULE_STATE_ACTIVE:
		case _PNMODULE_STATE_MISSING:
		case _PNMODULE_STATE_UPGRADED:
			$where[] = "$modulescolumn[state] = '" . pnVarPrepForStore($state) . "'";
		    break;
	}

	// generate where clause
	$wheresql = '';
	if (isset($where) && is_array($where)) {
		$wheresql = 'WHERE ' . implode('AND', $where);
	}

    $query = "SELECT $modulescolumn[id],
                     $modulescolumn[name],
                     $modulescolumn[type],
                     $modulescolumn[displayname],
                     $modulescolumn[description],
                     $modulescolumn[directory],
                     $modulescolumn[state],
                     $modulescolumn[version],
                     $modulescolumn[admin_capable]
              FROM $modulestable " . $wheresql	 .
              " ORDER BY $modulescolumn[name]";
    $result = $dbconn->SelectLimit($query, (int)$numitems, (int)$startnum-1);
    if ($result->EOF) {
        return false;
    }

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _GETFAILED);
        return false;
    }

    $resarray = array();
    while (list($mid, $name, $modtype, $displayname, $description, $directory, $state, $version, $admin_capable) = $result->fields) {
        $result->MoveNext();

        $resarray[] = array('id' => $mid,
            'name' => $name,
            'displayname' => $displayname,
            'description' => $description,
            'directory' => $directory,
            'state' => $state,
            'version' => $version,
            'type' => $modtype,
            'admin_capable' => $admin_capable);
    }
    $result->Close();

    return $resarray;
}

/**
 * set the state of a module
 * @author Jim McDonald
 * @param int $args ['mid'] the module id
 * @param int $args ['state'] the state
 * @return bool true if successful, false otherwise
 */
function modules_adminapi_setstate($args)
{
    // Get arguments from argument array
    extract($args);
    // Argument check
    if ((!isset($mid) || !is_numeric($mid)) ||
            (!isset($state))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }
    // Security check
    if (!pnSecAuthAction(0, 'Modules::', '::', ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }
    // Set state
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $modulestable = $pntable['modules'];
    $modulescolumn = &$pntable['modules_column'];
    $sql = "SELECT $modulescolumn[name],
                   $modulescolumn[directory],
                   $modulescolumn[state]
            FROM $modulestable
            WHERE $modulescolumn[id] = '" . (int)pnVarPrepForStore($mid) . "'";
    $result =& $dbconn->Execute($sql);
    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    if ($result->EOF) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    list($name, $directory, $oldstate) = $result->fields;
    $result->Close();
    // Check valid state transition
    switch ($state) {
        case _PNMODULE_STATE_UNINITIALISED:
            pnSessionSetVar('errormsg', _MODULESAPIINVALIDSTATETRANSITION);
            return false;
            break;
        case _PNMODULE_STATE_INACTIVE:
            break;
        case _PNMODULE_STATE_ACTIVE:
            if (($oldstate == _PNMODULE_STATE_UNINITIALISED) ||
                    ($oldstate == _PNMODULE_STATE_MISSING) ||
                    ($oldstate == _PNMODULE_STATE_UPGRADED)) {
                pnSessionSetVar('errormsg', _MODULESAPIINVALIDSTATETRANSITION);
                return false;
            }
            break;
        case _PNMODULE_STATE_MISSING:
            break;
        case _PNMODULE_STATE_UPGRADED:
            if ($oldstate == _PNMODULE_STATE_UNINITIALISED) {
                pnSessionSetVar('errormsg', _MODULESAPIINVALIDSTATETRANSITION);
                return false;
            }
            break;
    }

    $sql = "UPDATE $modulestable
            SET $modulescolumn[state] = '" . pnVarPrepForStore($state) . "'
            WHERE $modulescolumn[id] = '" . (int)pnVarPrepForStore($mid) . "'";
    $result =& $dbconn->Execute($sql);
    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    return true;
}

/**
 * remove a module
 * @author Jim McDonald
 * @param int $args ['mid'] the id of the module
 * @return bool true on success, false on failure
 */
function modules_adminapi_remove($args)
{
    // Get arguments from argument array
    extract($args);
    // Argument check
    if (!isset($mid) || !is_numeric($mid)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }
    // Security check
    if (!pnSecAuthAction(0, 'Modules::', '::', ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Get module information
    $modinfo = pnModGetInfo($mid);
    if (empty($modinfo)) {
        pnSessionSetVar('errormsg', _MODNOSUCHMOD);
        return false;
    }

	// call any module delete hooks
	pnModCallHooks('module', 'remove', $modinfo['name'], array('module' => $modinfo['name']));

    // Get module database info
    pnModDBInfoLoad($modinfo['name'], $modinfo['directory']);
    // Module deletion function
    $osdir = pnVarPrepForOS($modinfo['directory']);
    $modpath = ($modinfo['type'] == 3) ? 'system' : 'modules';
	if (file_exists($file = "$modpath/$osdir/pninit.php")) {
		if (!include_once($file)) {
			pnSessionSetVar('errormsg', _MODULESCOULDNOTINCLUDE.$file);
		}
	}
	if (file_exists($file="modules/$osdir/pnlang/" . pnVarPrepForOS(pnUserGetLang()) . "/init.php")) {
	    include_once($file);
	}
	if (file_exists($file="modules/$osdir/lang/" . pnVarPrepForOS(pnUserGetLang()) . "/init.php")) {
	    include_once($file);
	}
	if (file_exists($file="system/$osdir/pnlang/" . pnVarPrepForOS(pnUserGetLang()) . "/init.php")) {
    	include_once($file);
	}
	if (file_exists($file="system/$osdir/lang/" . pnVarPrepForOS(pnUserGetLang()) . "/init.php")) {
	    include_once($file);
	}

    $func = $modinfo['name'] . '_delete';
    $interactive_func = $modinfo['name'] . '_init_interactivedelete';
    if(($interactive_remove==false) && function_exists($interactive_func)) {
        pnSessionSetVar('interactive_remove', true);
        return $interactive_func();
    }

    if (function_exists($func)) {
        if ($func() != true) {
            return false;
        }
    }
    // Remove variables and module
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Delete any module variables that the module cleanup function might
    // have missed
    $modulevarstable = $pntable['module_vars'];
    $modulevarscolumn = &$pntable['module_vars_column'];
    $query = "DELETE FROM $modulevarstable
              WHERE $modulevarscolumn[modname] = '" . pnVarPrepForStore($modinfo['name']) . "'";
    $dbconn->Execute($query);

	// clean up any hooks activated for this module
    $hookstable = $pntable['hooks'];
    $hookscolumn = &$pntable['hooks_column'];
    $query = "DELETE FROM $hookstable
              WHERE $hookscolumn[smodule] = '" . pnVarPrepForStore($modinfo['name']) . "'";
    $dbconn->Execute($query);

	// remove the entry from the modules table
    $modulestable = $pntable['modules'];
    $modulescolumn = &$pntable['modules_column'];
    $query = "DELETE FROM $modulestable
              WHERE $modulescolumn[id] = '" . (int)pnVarPrepForStore($mid) . "'";
    $dbconn->Execute($query);

    return true;
}

/**
 * regenerate modules list
 * @author Jim McDonald
 * @return bool true on success, false on failure
 */
function modules_adminapi_regenerate()
{
    // Security check
    if (!pnSecAuthAction(0, 'Modules::', '::', ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }
    // Get all modules on filesystem
    $filemodules = array();

	// set the paths to search
    $rootdirs = array('system' => '3', 'modules' => '2');

    foreach ($rootdirs as $rootdir => $moduletype) {
    	if (is_dir($rootdir)) {
	    	$dh = opendir($rootdir);
		    while ($dir = readdir($dh)) {
		        unset($modtype);
		        if ((is_dir("$rootdir/$dir")) &&
		                ($dir != '.') &&
		                ($dir != '..') &&
		                ($dir != 'CVS')) {
		            // Found a directory
		            // Work out name from directory
		            $name = preg_replace('/^NS-/', '', $dir);

					// we'll use $modversion['name'] rather than the directory name here - markwesr
		            //$displayname = preg_replace('/_/', ' ', $name);

		            // Credit to Joerg Napp (jnapp) for SF Bug [ 562518 ]
		            unset($modtype);
		            // Work out if admin-capable
		            if (file_exists("$rootdir/$dir/pnadmin.php") || is_dir("$rootdir/$dir/pnadmin")) {
		                $adminCapable = _PNYES;
		                $modtype = $moduletype;
		            } elseif (file_exists("$rootdir/$dir/admin.php")) {
		                $adminCapable = _PNYES;
		                $modtype = 1;
		            } else {
		                $adminCapable = _PNNO;
		            }
		            // Work out if user-capable
		            if (file_exists("$rootdir/$dir/pnuser.php")  || is_dir("$rootdir/$dir/pnuser")) {
		                $userCapable = _PNYES;
		                if (!isset($modtype)) {
		                    $modtype = $moduletype;
		                }
		            } elseif (file_exists("$rootdir/$dir/index.php")) {
		                $userCapable = _PNYES;
		                if (!isset($modtype)) {
		                    $modtype = 1;
		                }
		            } else {
		                $userCapable = _PNNO;
		            }
		            if (empty($modtype)) {
		                $modtype = 1;
		            }

		            // include language file for ML displaynames and descriptions
                    $defaultlang = pnConfigGetVar('language');
                    if (empty($defaultlang)) {
                        $defaultlang = 'eng';
                    }
                    $currentlang = pnVarPrepForOS(pnUserGetLang());
                    $possiblelanguagefiles = array("$rootdir/$dir/pnlang/$currentlang/version.php",
                                                   "$rootdir/$dir/lang/$currentlang/version.php",
                                                   "$rootdir/$dir/pnlang/$defaultlang/version.php",
                                                   "$rootdir/$dir/lang/$defaultlang/version.php",
                                                   "$rootdir/$dir/pnlang/eng/version.php",
                                                   "$rootdir/$dir/lang/eng/version.php");
                    foreach($possiblelanguagefiles as $languagefile) {
                        if( file_exists($languagefile) && is_readable($languagefile)) {
                            include_once $languagefile;
                            break;
                        }
		            }

					if (file_exists($file ="$rootdir/$dir/Version.php")) {
						if (!include($file)) {
							pnSessionSetVar('errormsg', _MODULESCOULDNOTINCLUDE.$file);
						}
					}
					if (file_exists($file = "$rootdir/$dir/pnversion.php")) {
						if (!include($file)) {
							pnSessionSetVar('errormsg', _MODULESCOULDNOTINCLUDE.$file);
						}
					}

		            // Get the module version
		            $modversion['version'] = '0';
		            $modversion['description'] = '';
					$modversion['name'] = preg_replace('/_/', ' ', $name);
					if (file_exists($file ="$rootdir/$dir/Version.php")) {
						if (!include($file)) {
							pnSessionSetVar('errormsg', _MODULESCOULDNOTINCLUDE.$file);
						}
					}
					if (file_exists($file = "$rootdir/$dir/pnversion.php")) {
						if (!include($file)) {
							pnSessionSetVar('errormsg', _MODULESCOULDNOTINCLUDE.$file);
						}
					}
		            $version = $modversion['version'];
		            $description = $modversion['description'];
		            if(isset($modversion['displayname']) && !empty($modversion['displayname'])) {
					    $displayname = $modversion['displayname'];
					} else {
					    $displayname = $modversion['name'];
					}

					// get the correct regid
					if(isset($modversion['id']) && !empty($modversion['id'])) {
					    $regid = (int)$modversion['id'];
    	            } else {
    		            $regid = modules_adminapi_getreginfo(array('name' => $name));
	    	            $regid = ($regid==false) ? 0 : $regid;
					}

		            $filemodules[$name] = array('directory' => $dir,
								                'name' => $name,
								                'type' => $modtype,
								                'displayname' => $displayname,
								                'regid' => $regid,
								                'version' => $version,
								                'description' => $description,
								                'admincapable' => $adminCapable,
								                'usercapable' => $userCapable);
				    // important: unset modversion otherwise all following modules will have
				    // at least the same regid or other values not defined in
				    // the next pnversion.php files to be read
				    unset($modversion);
				}
		    }
		    closedir($dh);
    	}
    }
//pnfdebug('mod', $filemodules);

	// Get all modules in DB
    $dbmodules = array();

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $modulestable = $pntable['modules'];
    $modulescolumn = &$pntable['modules_column'];
    $query = "SELECT $modulescolumn[id],
                     $modulescolumn[name],
                     $modulescolumn[type],
                     $modulescolumn[displayname],
                     $modulescolumn[regid],
                     $modulescolumn[directory],
                     $modulescolumn[admin_capable],
                     $modulescolumn[user_capable],
                     $modulescolumn[version],
                     $modulescolumn[state]
              FROM $modulestable";
    $result =& $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _GETFAILED);
        return false;
    }
    while (list($mid, $name, $modtype, $displayname, $regid, $directory, $adminCapable, $userCapable, $version, $state) = $result->fields) {
        $result->MoveNext();
        $dbmodules[$name] = array('id' => $mid,
								  'name' => $name,
                                  'type' => $modtype,
								  'displayname' => $displayname,
								  'description' => $description,
								  'regid' => $regid,
                                  'directory' => $directory,
                                  'admincapable' => $adminCapable,
                                  'usercapable' => $userCapable,
                                  'version' => $version,
                                  'state' => $state);
    }
    $result->Close();
    // See if we have lost any modules since last generation
    foreach ($dbmodules as $name => $modinfo) {
        if (empty($filemodules[$name])) {
            // Old module
            // Get module ID
            $modulestable = $pntable['modules'];
            $modulescolumn = &$pntable['modules_column'];
            $query = "SELECT $modulescolumn[id]
                      FROM $modulestable
                      WHERE $modulescolumn[name] = '" . pnVarPrepForStore($name) . "'";
            $result =& $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                pnSessionSetVar('errormsg', _GETFAILED);
                return false;
            }

            // Ouch! jn
            if ($result->EOF) {
                die("Failed to get module ID");
            }

            list($mid) = $result->fields;
            $result->Close();
            // Set state of module to 'missing'
            modules_adminapi_setstate(array('mid' => $mid, 'state' => _PNMODULE_STATE_MISSING));
            unset($dbmodules[$name]);
        }
    }
    // See if we have gained any modules since last generation,
    // or if any current modules have been upgraded
    foreach ($filemodules as $name => $modinfo) {
        if (empty($dbmodules[$name])) {
            // New module
            $modid = $dbconn->GenId($pntable['modules']);
            $sql = "INSERT INTO $modulestable
                      ($modulescolumn[id],
                       $modulescolumn[name],
                       $modulescolumn[type],
                       $modulescolumn[regid],
                       $modulescolumn[displayname],
                       $modulescolumn[directory],
                       $modulescolumn[admin_capable],
                       $modulescolumn[user_capable],
                       $modulescolumn[state],
                       $modulescolumn[version],
                       $modulescolumn[description])
                    VALUES
                      ('" . (int)pnVarPrepForStore($modid) . "',
                       '" . pnVarPrepForStore($modinfo['name']) . "',
                       '" . pnVarPrepForStore($modinfo['type']) . "',
                       '" . (int)pnVarPrepForStore($modinfo['regid']) . "',
                       '" . pnVarPrepForStore($modinfo['displayname']) . "',
                       '" . pnVarPrepForStore($modinfo['directory']) . "',
                       '" . (int)pnVarPrepForStore($modinfo['admincapable']) . "',
                       '" . (int)pnVarPrepForStore($modinfo['usercapable']) . "',
                       '" . _PNMODULE_STATE_UNINITIALISED . "',
                       '" . pnVarPrepForStore($modinfo['version']) . "',
                       '" . pnVarPrepForStore($modinfo['description']) . "')";
            $dbconn->Execute($sql);
        } else {
            // module is in the db already
            if ($dbmodules[$name]['state'] == _PNMODULE_STATE_MISSING) {
                // module was lost, now it is here again
  	            modules_adminapi_setstate(array('mid'   => $dbmodules[$name]['id'],
  	                                            'state' => _PNMODULE_STATE_INACTIVE));
  	        }
            if ($dbmodules[$name]['version'] != $modinfo['version']) {
                if ($dbmodules[$name]['state'] != _PNMODULE_STATE_UNINITIALISED) {
                    // 20021127 : Roger Raymond
                    // Removed code that set new module version
/*
                    $sql = "UPDATE $modulestable
                            SET $modulescolumn[state] = '" . _PNMODULE_STATE_UPGRADED . "'
                            WHERE $modulescolumn[id] = '" . (int)pnVarPrepForStore($dbmodules[$name]['id']) . "'";
                    $dbconn->Execute($sql);
*/
                    modules_adminapi_setstate(array('mid'   => $dbmodules[$name]['id'],
                                                    'state' => _PNMODULE_STATE_UPGRADED));
                }
            }
        }
    }

    // see if any modules have changed
    // detects change in a) directory (e.g. removal of NS- prefix),
    // b) module type (e.g. non API compliant to API compliant or change from modules to system folder),
    // c) admin or user capabilities (e.g. addition of an admin interface to a module)
    // d) regid
    foreach ($filemodules as $name => $modinfo) {
    	if (isset($dbmodules[$name])) {
			if (($modinfo['directory']    != $dbmodules[$name]['directory']) ||
			    ($modinfo['type']         != $dbmodules[$name]['type']) ||
			    ($modinfo['admincapable'] != $dbmodules[$name]['admincapable']) ||
			    ($modinfo['usercapable']  != $dbmodules[$name]['usercapable']) ||
			    ($modinfo['description']  != $dbmodules[$name]['description']) ||
			    ($modinfo['regid']        != $dbmodules[$name]['regid'])) {
				$sql = "UPDATE $modulestable
	                    SET $modulescolumn[directory] = '" . pnVarPrepForStore($modinfo['directory']) . "',
				            $modulescolumn[type] = '" . pnVarPrepForStore($modinfo['type']) . "',
				            $modulescolumn[admin_capable] = '" . pnVarPrepForStore($modinfo['admincapable']) . "',
				            $modulescolumn[user_capable] = '" . pnVarPrepForStore($modinfo['usercapable']) . "',
				            $modulescolumn[description] = '" . pnVarPrepForStore($modinfo['description']) . "',
                            $modulescolumn[regid] = '" . (int)pnVarPrepForStore($modinfo['regid']) . "'
	                    WHERE $modulescolumn[id] = '" . pnVarPrepForStore($dbmodules[$name]['id']) . "'";
	            $dbconn->Execute($sql);
			}
    	}
	}

    return true;
}

/**
 * get registered modules info
 * @author Jim McDonald
 * @param string args['name'] name of module
 * @return mixed registered modid on success, false on failure
 */
function modules_adminapi_getreginfo($args)
{
    // Get arguments from argument array
    extract($args);
    // Argument check
    if (!isset($name)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    static $modreg;

    if (empty($modreg[1])) {
    	if (file_exists('system/Modules/modreg.php')) {
        	include 'system/Modules/modreg.php';
    	} else if (file_exists('modules/Modules/modreg.php')) {
        	include 'modules/Modules/modreg.php';
    	}
    }

    if (isset($modreg[$name])) {
        return $modreg[$name];
    } else {
        return false;
    }
}

/**
 * initialise a module
 * @author Jim McDonald, changed by Frank Schummertz for interactive init
 * @param int args['mid'] module ID
 * @param int args['interactive_mode'] boolean that tells us if we are in interactive mode or not
 * @return bool true on success, false on failure
 */
function modules_adminapi_initialise($args)
{
    // Get arguments from argument array
    extract($args);
    // Argument check
    if (!isset($mid) || !is_numeric($mid)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }
    // Get module information
    $modinfo = pnModGetInfo($mid);
    if (empty($modinfo)) {
        pnSessionSetVar('errormsg', _MODNOSUCHMOD);
        return false;
    }

    // Get module database info
    pnModDBInfoLoad($modinfo['name'], $modinfo['directory']);
    // Module initialisation function
    $osdir = pnVarPrepForOS($modinfo['directory']);

    //$modpath = ($modinfo['type'] == 3) ? 'system' : 'modules';
	//todo - reimplement once we have type 3 modules
	if (file_exists($file = "modules/$osdir/pninit.php")) {
		if (!include_once($file)) {
			pnSessionSetVar('errormsg', _MODULESCOULDNOTINCLUDE.$file);
		}
	} else if (file_exists($file = "system/$osdir/pninit.php")) {
		if (!include_once($file)) {
			pnSessionSetVar('errormsg', _MODULESCOULDNOTINCLUDE.$file);
		}
	}
	if (file_exists($file="modules/$osdir/pnlang/" . pnVarPrepForOS(pnUserGetLang()) . "/init.php")) {
	    include_once($file);
	}
	if (file_exists($file="modules/$osdir/lang/" . pnVarPrepForOS(pnUserGetLang()) . "/init.php")) {
	    include_once($file);
	}
	if (file_exists($file="system/$osdir/pnlang/" . pnVarPrepForOS(pnUserGetLang()) . "/init.php")) {
    	include_once($file);
	}
	if (file_exists($file="system/$osdir/lang/" . pnVarPrepForOS(pnUserGetLang()) . "/init.php")) {
	    include_once($file);
	}

    $func = $modinfo['name'] . '_init';
    $interactive_func = $modinfo['name'] . '_init_interactiveinit';

    if(isset($interactive_init) && ($interactive_init==false) && function_exists($interactive_func)) {
        pnSessionSetVar('interactive_init', true);
        return $interactive_func();
    }

    if (function_exists($func)) {
        if ($func() != true) {
            return false;
        }
    }
    // Update state of module
    if (!modules_adminapi_setstate(array('mid' => $mid,
                						 'state' => _PNMODULE_STATE_INACTIVE))) {
        pnSessionSetVar('errormsg', _MODCHANGESTATEFAILED);
        return false;
    }

	// call any module initialisation hooks
	pnModCallHooks('module', 'initialise', $modinfo['name'], array('module' => $modinfo['name']));

    // Success
    return true;
}

/**
 * upgrade a module
 * @author Jim McDonald
 * @param int args['mid'] module ID
 * @return bool true on success, false on failure
 */
function modules_adminapi_upgrade($args)
{
    // 20021216 fixed the fix : larsneo (thx to cmgrote and jojodee)
    // Get arguments from argument array
    extract($args);
    // Argument check
    if (!isset($mid) || !is_numeric($mid)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }
    // Get module information
    $modinfo = pnModGetInfo($mid);
    if (empty($modinfo)) {
        pnSessionSetVar('errormsg', _MODNOSUCHMOD);
        return false;
    }

    // Get module database info
    pnModDBInfoLoad($modinfo['name'], $modinfo['directory']);

    // Module upgrade function
    $osdir = pnVarPrepForOS($modinfo['directory']);
    $modpath = ($modinfo['type'] == 3) ? 'system' : 'modules';
	if (file_exists($file = "$modpath/$osdir/pninit.php")) {
		if (!include_once($file)) {
			pnSessionSetVar('errormsg', _MODULESCOULDNOTINCLUDE.$file);
		}
	}
	if (file_exists($file="modules/$osdir/pnlang/" . pnVarPrepForOS(pnUserGetLang()) . "/init.php")) {
	    include_once($file);
	}
	if (file_exists($file="modules/$osdir/lang/" . pnVarPrepForOS(pnUserGetLang()) . "/init.php")) {
	    include_once($file);
	}
	if (file_exists($file="system/$osdir/pnlang/" . pnVarPrepForOS(pnUserGetLang()) . "/init.php")) {
    	include_once($file);
	}
	if (file_exists($file="system/$osdir/lang/" . pnVarPrepForOS(pnUserGetLang()) . "/init.php")) {
	    include_once($file);
	}

    $func = $modinfo['name'] . '_upgrade';
    $interactive_func = $modinfo['name'] . '_init_interactiveupgrade';

    if(($interactive_upgrade==false) && function_exists($interactive_func)) {
        pnSessionSetVar('interactive_upgrade', true);
        return $interactive_func(array('oldversion' => $modinfo['version']));
    }

    if (function_exists($func)) {
        if ($func($modinfo['version']) != true) {
            return false;
        }
    }
    // Update state of module
    if (!modules_adminapi_setstate(array('mid' => $mid,
                'state' => _PNMODULE_STATE_INACTIVE))) {
        return false;
    }
    // BEGIN bugfix (561802) - cmgrote
    // Get the new version information...
    $modversion['version'] = '0';
	if (file_exists($file="$modpath/$modinfo[directory]/Version.php")) {
	    include_once($file);
	}
	if (file_exists($file="$modpath/$modinfo[directory]/pnversion.php")) {
	    include_once($file);
    }
	$version = $modversion['version'];

    // Note the changes in the database...

    // Get module database info
    pnModDBInfoLoad('Modules');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $modulestable = $pntable['modules'];
    $modulescolumn = &$pntable['modules_column'];

    $sql = "UPDATE $modulestable
			SET $modulescolumn[version] = '" . pnVarPrepForStore($modversion['version']) . "',
				$modulescolumn[admin_capable] = '" . pnVarPrepForStore($modversion['admin']) . "'
			WHERE $modulescolumn[id] = '" . (int)pnVarPrepForStore($mid) . "'";
    $dbconn->Execute($sql);
    // END bugfix (561802) - cmgrote
    // Message
    pnSessionSetVar('errormsg', _MODULESAPIUPGRADED);

	// call any module upgrade hooks
	pnModCallHooks('module', 'upgrade', $modinfo['name'], array('module' => $modinfo['name']));

    // Success
    return true;
}

/**
 * utility function to count the number of items held by this module
 * @author Mark West
 * @since 1.16
 * @returns integer number of items held by this module
 */
function modules_adminapi_countitems($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn() we
    // currently just want the first item, which is the official database
    // handle.  For pnDBGetTables() we want to keep the entire tables array
    // together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // It's good practice to name the table and column definitions you are
    // getting - $table and $column don't cut it in more complex modules
    $modulestable = $pntable['modules'];
    $modulescolumn = &$pntable['modules_column'];

	// filter my first letter of module
	if (isset($letter) && !empty($letter)) {
	    $where[] = "$modulescolumn[name] LIKE '" . pnVarPrepForStore($letter) . "%'";
		$startnum = 1;
	}

	// filter by module state
	switch ($state) {
		case _PNMODULE_STATE_UNINITIALISED:
		case _PNMODULE_STATE_INACTIVE:
		case _PNMODULE_STATE_ACTIVE:
		case _PNMODULE_STATE_MISSING:
		case _PNMODULE_STATE_UPGRADED:
			$where[] = "$modulescolumn[state] = '" . pnVarPrepForStore($state) . "'";
		    break;
	}

	// generate where clause
	$wheresql = '';
	if (isset($where) && is_array($where)) {
		$wheresql = 'WHERE ' . implode('AND', $where);
	}
    // Get item - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the Execute() command allows for simpler debug operation
    // if it is ever needed
    $sql = "SELECT COUNT(1)
            FROM $modulestable $wheresql";
    $result =& $dbconn->Execute($sql);
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _GETFAILED);
        return false;
    }


    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    // Obtain the number of items
    list($numitems) = $result->fields;

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Return the number of items
    return $numitems;
}

/**
 * Get a list of modules calling a particular hook module
 *
 * @copyright (C) 2003 by the Xaraya Development Team.
 * @author Xaraya Team
 * @link http://www.xaraya.com
 * @param $args['hookmodname'] hook module we're looking for
 * @param $args['hookobject'] the object of the hook (item, module, ...) (optional)
 * @param $args['hookaction'] the action on that object (transform, display, ...) (optional)
 * @param $args['hookarea'] the area we're dealing with (GUI, API) (optional)
 * @returns array
 * @return array of modules calling this hook module
 * @raise BAD_PARAM
 */
function modules_adminapi_gethookedmodules($args)
{
    // Get arguments from argument array
    extract($args);

    // Argument check
    if (empty($hookmodname)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $bindvars = array();
    $query = "SELECT DISTINCT pn_smodule, pn_stype
              FROM $pntable[hooks]
              WHERE pn_tmodule= ?";
    $bindvars[] = $hookmodname;
    if (!empty($hookobject)) {
        $query .= " AND pn_object = ?";
        $bindvars[] = $hookobject;
    }
    if (!empty($hookaction)) {
        $query .= " AND pn_action = ?";
        $bindvars[] = $hookaction;
    }
    if (!empty($hookarea)) {
        $query .= " AND pn_tarea = ?";
        $bindvars[] = $hookarea;
    }

    $result =& $dbconn->Execute($query,$bindvars);

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    // modlist will hold the hooked modules
    $modlist = array();
    for (; !$result->EOF; $result->MoveNext()) {
        list($callermodname,$calleritemtype) = $result->fields;
        if (empty($callermodname)) continue;
        if (empty($calleritemtype)) {
            $calleritemtype = 0;
        }
        $modlist[$callermodname][$calleritemtype] = 1;
    }
    $result->Close();

    return $modlist;
}

/**
 * Enable hooks between a caller module and a hook module
 *
 * @param $args['callermodname'] caller module
 * @param $args['hookmodname'] hook module
 * @returns bool
 * @return true if successfull
 * @raise BAD_PARAM
 *
 * @author Xaraya Team
 * @copyright (C) 2003 by the Xaraya Development Team.
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link http://www.xaraya.com
 */
function modules_adminapi_enablehooks($args)
{
    // Get arguments from argument array
    extract($args);

    // Argument check
    if (empty($callermodname) || empty($hookmodname)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Rename operation
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Delete hooks regardless
    $sql = "DELETE FROM $pntable[hooks]
            WHERE pn_smodule = ? AND pn_tmodule = ?";
    $bindvars = array($callermodname, $hookmodname);

    $result =& $dbconn->Execute($sql,$bindvars);
    if (!$result) return;

    $sql = "SELECT DISTINCT pn_id, pn_smodule, pn_object, pn_action,
							pn_tarea, pn_tmodule, pn_ttype, pn_tfunc
            FROM $pntable[hooks]
            WHERE pn_smodule IS NULL AND pn_tmodule = ?";

    $result =& $dbconn->Execute($sql,array($hookmodname));
    if (!$result) return;
    for (; !$result->EOF; $result->MoveNext()) {
        list($hookid,
             $hooksmodname,
             $hookobject,
             $hookaction,
             $hooktarea,
             $hooktmodule,
             $hookttype,
             $hooktfunc) = $result->fields;

        $sql = "INSERT INTO $pntable[hooks] (
                pn_id, pn_object, pn_action, pn_smodule,
                pn_tarea, pn_tmodule, pn_ttype, pn_tfunc)
                VALUES (?,?,?,?,?,?,?,?)";
        $bindvars = array($dbconn->GenId($pntable['hooks']),
                          $hookobject, $hookaction, $callermodname,
                          $hooktarea, $hooktmodule,
                          $hookttype, $hooktfunc);
        $subresult =& $dbconn->Execute($sql,$bindvars);
        if (!$subresult) return;
    }
    $result->Close();

    return true;
}

/**
 * Disable hooks between a caller module and a hook module
 *
 * @param $args['callermodname'] caller module
 * @param $args['hookmodname'] hook module
 * @returns bool
 * @return true if successfull
 * @raise BAD_PARAM
 *
 * @copyright (C) 2003 by the Xaraya Development Team.
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link http://www.xaraya.com
 * @author Xaraya Team
 */
function modules_adminapi_disablehooks($args)
{
    // Get arguments from argument array
    extract($args);

    // Argument check
    if (empty($callermodname) || empty($hookmodname)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }
    if (empty($calleritemtype)) {
        $calleritemtype = '';
    }

    // Rename operation
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Delete hooks regardless
    $sql = "DELETE FROM $pntable[hooks]
            WHERE pn_smodule = ?
              AND pn_stype = ?
              AND pn_tmodule = ?";
    $bindvars = array($callermodname,$calleritemtype,$hookmodname);

    $result =& $dbconn->Execute($sql,$bindvars);
    if (!$result) return;

    return true;
}

?>