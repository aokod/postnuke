<?php
// $Id: pnMod.php 17670 2006-01-22 14:33:56Z drak $
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
// Purpose of file: Module variable handling
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Core
 * @subpackage PostNuke_pnAPI
*/
/**
 * pnModVarExists - check to see if a module variable is set
 * @author Chris Miller
 * @param 'modname' the name of the module
 * @param 'name' the name of the variable
 * @return  true if the variable exists in the database, false if not
 */
function pnModVarExists($modname, $name)
{
	// define input, all numbers and booleans to strings
	$modname = isset($modname) ? ((string)$modname) : '';
	$name = isset($name) ? ((string)$name) : '';

	// make sure we have the necessary parameters
	if(!pnVarValidate($modname, 'mod') || !pnVarValidate($name, 'modvar')){
		return false;
	}
	// get all module vars for this module
	$modvars = pnModGetVar($modname);
	if (array_key_exists($name,$modvars)) {
		// if $name is set
		return true;
	}
	return false;
}

/**
 * pnModGetVar - get a module variable
 *
 * if the name parameter is included then function returns the
 * module variable value.
 * if the name parameter is ommitted then function returns a multi
 * dimentional array of the keys and values for the module vars.
 *
 * @author Jim McDonald <jim@mcdee.net>
 * @param 'modname' the name of the module
 * @param 'name' the name of the variable
 * @return  if the name parameter is included then function returns
 *          string - module variable value
 * @return  if the name parameter is ommitted then function returns
 *          array - multi dimentional array of the keys
 *                  and values for the module vars.
 */
function pnModGetVar($modname, $name='')
{
	// if we don't know the modname then lets assume it is the current
	// active module
	if (!isset($modname)) {
		$modname = pnModGetName();
    }

    global $pnmodvar;

	// if we haven't got vars for this module yet then lets get them
	if (!isset($pnmodvar[$modname])) {

		$dbconn =& pnDBGetConn(true);
		$pntable =& pnDBGetTables();

		$modulevarstable = $pntable['module_vars'];
		$modulevarscolumn = &$pntable['module_vars_column'];

		$query = "SELECT $modulevarscolumn[name],
			$modulevarscolumn[value]
				FROM $modulevarstable
				WHERE $modulevarscolumn[modname] = '" . pnVarPrepForStore($modname) . "'";

		$result =& $dbconn->Execute($query);

		if($dbconn->ErrorNo() != 0) {
			return array();
		}

		if ($result->EOF) {
			$pnmodvar[$modname] = array();
			return array();
		}

		for (; !$result->EOF; $result->MoveNext()) {
			list($aname, $avalue) = $result->fields;
			$pnmodvar[$modname][$aname] = $avalue;
		}

		$result->Close();
	}

	// if they didn't pass a variable name then return every variable
	// for the specified module as an associative array.
	// array('var1'=>value1, 'var2'=>value2)
	if (empty($name)) {
		return $pnmodvar[$modname];
	}

	// since they passed a variable name then only return the value for
	// that variable
	if (isset($pnmodvar[$modname][$name])) {
		return $pnmodvar[$modname][$name];
	}
	// we don't know the required module var but we established all known
	// module vars for this module so the requested one can't exist.
	return false;
}

/**
 * pnModSetVar - set a module variable
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 * @param 'modname' the name of the module
 * @param 'name' the name of the variable
 * @param 'value' the value of the variable
 * @return bool true if successful, false otherwise
 */
function pnModSetVar($modname, $name, $value='')
{
	// define input, all numbers and booleans to strings
	$modname = isset($modname) ? ((string)$modname) : '';

	// validate
    if (!pnVarValidate($modname, 'mod') || !isset($name)) {
    	return false;
	}

    global $pnmodvar;

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $modulevarstable = $pntable['module_vars'];
    $modulevarscolumn = &$pntable['module_vars_column'];

    if (pnModVarExists($modname,$name)) {
        $query = "UPDATE $modulevarstable
                  SET $modulevarscolumn[value] = '".pnVarPrepForStore($value)."'
                  WHERE $modulevarscolumn[modname] = '".pnVarPrepForStore($modname)."'
                  AND $modulevarscolumn[name] = '".pnVarPrepForStore($name)."'";
    } else {
        $query = "INSERT INTO $modulevarstable
                    ( $modulevarscolumn[modname], $modulevarscolumn[name], $modulevarscolumn[value])
                  VALUES
                    ('".pnVarPrepForStore($modname)."', '".pnVarPrepForStore($name)."', '".pnVarPrepForStore($value)."');";
    }
    $dbconn->Execute($query);
    if ($dbconn->ErrorNo()!=0) {
		return false;
	}

    $pnmodvar[$modname][$name] = $value;
    return true;
    // Modified by Segr function end
}

/**
 * pnModDelVar
 *
 * Delete a module variables. If the optional name parameter is not supplied all variables
 * for the module 'modname' are deleted
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 * @param 'modname' the name of the module
 * @param 'name' the name of the variable (optional)
 * @return bool true if successful, false otherwise
 */
function pnModDelVar($modname, $name='')
{
	// define input, all numbers and booleans to strings
	$modname = isset($modname) ? ((string)$modname) : '';

	// validate
    if (!pnVarValidate($modname, 'modvar')) {
		return false;
	}

    global $pnmodvar;

	if (empty($name)) {
		if (isset($pnmodvar[$modname])) {
			unset($pnmodvar[$modname]);
		}
	} else {
		if (isset($pnmodvar[$modname][$name])) {
			unset($pnmodvar[$modname][$name]);
		}
	}

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $modulevarstable = $pntable['module_vars'];
    $modulevarscolumn = &$pntable['module_vars_column'];

	// check if we're deleting one module var or all module vars
	$specificvar = '';
	if (!empty($name)) {
		$specificvar = " AND $modulevarscolumn[name] = '".pnVarPrepForStore($name)."'";
	}
    $query = "DELETE FROM $modulevarstable
              WHERE $modulevarscolumn[modname] = '".pnVarPrepForStore($modname)."'
              $specificvar";
    $dbconn->Execute($query);

    if ($dbconn->ErrorNo()!=0) {
	    return false;
	}
    return true;
    // Modified by Segr function end
}

/**
 * pnModGetIDFromName - get module ID given its name
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 * @param 'module' the name of the module
 * @return int module ID
 */
function pnModGetIDFromName($module)
{
	// define input, all numbers and booleans to strings
	$module = isset($module) ? ((string)$module) : '';

	// validate
    if (!pnVarValidate($module, 'mod')) {
		return false;
	}

	if (substr($module,0,3) == 'NS-') {
	    $module = substr($module,3);
	}

    static $modid;

	if (!is_array($modid) || !isset($modid[strtolower($module)])) {
		$dbconn =& pnDBGetConn(true);
		$pntable =& pnDBGetTables();

		$modulestable = $pntable['modules'];
		$modulescolumn = &$pntable['modules_column'];
		$query = "SELECT $modulescolumn[name],
				 		 $modulescolumn[displayname],
						 $modulescolumn[id]
				  FROM $modulestable";
		$result =& $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			return;
		}

		for (; !$result->EOF; $result->MoveNext()) {
			list($modname, $displayname, $id) = $result->fields;
			$modid[strtolower($modname)] = $id;
			if(!empty($displayname)) {
			    $modid[strtolower($displayname)] = $id;
		    }
		}

		if (!isset($modid[strtolower($module)])) {
			$modid[strtolower($module)] = false;
			return false;
		}
		$result->Close();
	}

    if (isset($modid[strtolower($module)])) {
        return $modid[strtolower($module)];
    }

    return false;
}

/**
 * get information on module
 * return array of module information or false if core ( id = 0 )
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 * @param 'id' module ID
 * @return mixed module information array or false
 */
function pnModGetInfo($modid=0)
{
    // a $modid of 0 is associated with core ( pn_blocks.mid, ... ).
    if ($modid == 0 || !is_numeric($modid)) {
        return false;
    }

    static $modinfo;

    if (!is_array($modinfo) || !isset($modinfo[$modid])) {
		$dbconn =& pnDBGetConn(true);
		$pntable =& pnDBGetTables();

		$modulestable = $pntable['modules'];
		$modulescolumn = &$pntable['modules_column'];
		$query = "SELECT $modulescolumn[id],
						 $modulescolumn[name],
						 $modulescolumn[type],
						 $modulescolumn[directory],
						 $modulescolumn[regid],
						 $modulescolumn[displayname],
						 $modulescolumn[description],
						 $modulescolumn[state],
						 $modulescolumn[version]
				  FROM $modulestable";
		$result =& $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			return;
		}

		for (; !$result->EOF; $result->MoveNext()) {
			list($id,
				 $resarray['name'],
				 $resarray['type'],
				 $resarray['directory'],
				 $resarray['regid'],
				 $resarray['displayname'],
				 $resarray['description'],
				 $resarray['state'],
				 $resarray['version']) = $result->fields;
			$modinfo[$id] = $resarray;
		}

		if (!isset($modinfo[$modid])) {
			$modinfo[$modid] = false;
			return false;
		}
	    $result->Close();
	}

    return $modinfo[$modid];
}

/**
 * get list of user modules
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 * @return array array of module information arrays
 */
function pnModGetUserMods()
{
	static $usermods = array();

	if (empty($usermods)) {
		$mods = pnModGetAllMods();
		foreach ($mods as $mod) {
			if ($mod['user_capable']) {
				array_push($usermods, $mod);
			}
		}
	}

    return $usermods;

}

/**
 * get list of administration modules
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 * @return array array of module information arrays
 */
function pnModGetAdminMods()
{
	static $adminmods = array();

	if (empty($adminmods)) {
		$mods = pnModGetAllMods();
		foreach ($mods as $mod) {
			if ($mod['admin_capable']) {
				array_push($adminmods, $mod);
			}
		}
	}

    return $adminmods;
}

/**
 * get list of all modules
 * @author Mark West <mark@markwest.me.uk>
 * @link http://www.markwest.me.uk
 * @return array array of module information arrays
 */
function pnModGetAllMods()
{
	static $modsarray = array();

	if (empty($modsarray)) {
		$dbconn =& pnDBGetConn(true);
		$pntable =& pnDBGetTables();

		$modulestable = $pntable['modules'];
		$modulescolumn = &$pntable['modules_column'];
		$query = "SELECT $modulescolumn[name],
						 $modulescolumn[id],
						 $modulescolumn[type],
						 $modulescolumn[directory],
						 $modulescolumn[regid],
						 $modulescolumn[displayname],
						 $modulescolumn[description],
						 $modulescolumn[admin_capable],
						 $modulescolumn[user_capable],
						 $modulescolumn[version]
				  FROM $modulestable
				  WHERE $modulescolumn[state] = " . _PNMODULE_STATE_ACTIVE . "
				  OR $modulescolumn[name] = 'Modules'
				  ORDER BY $modulescolumn[name]";

        $dbconn->SetFetchMode(ADODB_FETCH_NUM);
		$result =& $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			return false;
		}

		if ($result->EOF) {
			return false;
		}

		while (list($name,
				$id,
				$modtype,
				$directory,
				$regid,
				$displayname,
				$description,
				$admin_capable,
				$user_capable,
				$version) = $result->fields) {
			$result->MoveNext();

			$tmparray = array('name' => $name,
			  			    'id' => $id,
							'type' => $modtype,
							'directory' => $directory,
							'regid' => $regid,
							'displayname' => $displayname,
							'description' => $description,
							'admin_capable' => $admin_capable,
							'user_capable' => $user_capable,
							'version' => $version);

			array_push($modsarray, $tmparray);
		}
		$result->Close();
	}

    return $modsarray;
}

/**
 * load an API for a module
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 * @param 'modname' the name of the module
 * @param 'type' type of functions to load
 * @param 'force' determines to load API even if module isn't active
 * @return bool true on success, false on failure
 */
function pnModAPILoad($modname, $type = 'user', $force = false)
{
	// define input, all numbers and booleans to strings
	$modname = isset($modname) ? ((string)$modname) : '';

	// validate
    if (!pnVarValidate($modname, 'mod')) {
		return false;
	}

    static $loaded = array();

    if (!empty($loaded[strtolower("$modname{$type}api")])) {
        // Already loaded from somewhere else
        return true;
    }

	// get the module info
	$modinfo = pnModGetInfo(pnModGetIDFromName($modname));

	// check the modules state
	if (!$force && !pnModAvailable($modname) && pnModGetName() != 'Modules') {
		return false;
	}

	// create variables for the OS preped version of the directory
    list($osdirectory, $ostype) = pnVarPrepForOS($modinfo['directory'], $type);

    $mosfile = "modules/$osdirectory/pn{$ostype}api.php";
    $mosdir = "modules/$osdirectory/pn{$ostype}api";

    if (file_exists($mosfile)) {
	    // Load the file from modules
	    include $mosfile;
	} elseif (is_dir($mosdir)) {
	} else {
        // File does not exist
        return false;
    }
    $loaded[strtolower("$modname{$type}api")] = 1;

    // Load the module language files
	pnModLangLoad($modname, $type, true);

    // Load datbase info
    pnModDBInfoLoad($modname, $modinfo['directory']);

    return true;
}

/**
 * load datbase definition for a module
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 * @param 'name' the name of the module to load database definition for
 * @param 'directory' directory that module is in (if known)
 * @param 'force' force table information to be reloaded
 * @return bool true if successful, false otherwise
 */
function &pnModDBInfoLoad($modname, $directory = '', $force=false)
{
	// define input, all numbers and booleans to strings
	$modname = isset($modname) ? ((string)$modname) : '';

	// validate
    if (!pnVarValidate($modname, 'mod')) {
		$result = false;
		return $result;
	}

    static $loaded = array();
    // Check to ensure we aren't doing this twice
    if (isset($loaded[strtolower($modname)]) && !$force) {
		$result = true;
		return $result;
    }

	// Get the directory if we don't already have it
    if (empty($directory)) {
		// get the module info
		$modinfo = pnModGetInfo(pnModGetIDFromName($modname));
		$directory = $modinfo['directory'];
	}

    // Load the database definition if required
    $mospntablefile = 'modules/' . pnVarPrepForOS($directory) . '/pntables.php';
    // Ignore errors for this, if it fails we'll find out and handle
    // it when we look for the function itself
	if (file_exists($mospntablefile)) {
		include_once $mospntablefile;
	}
    $tablefunc = $modname . '_' . 'pntables';
    if (function_exists($tablefunc)) {
		$data = $tablefunc();
		// V4B RNG: added casts to ensure proper behaviour under PHP5
        $GLOBALS['pntables'] = array_merge((array)$GLOBALS['pntables'], (array)$data);
    }
    $loaded[strtolower($modname)] = true;

    // V4B RNG: return data so we know which tables were loaded by this module
    //return true;
    return $data;
}

/**
 * load a module
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 * @param 'name' the name of the module
 * @param 'type' the type of functions to load
 * @param 'force' determines to load Module even if module isn't active
 * @return string name of module loaded, or false on failure
 */
function pnModLoad($modname, $type = 'user', $force = false)
{
	// define input, all numbers and booleans to strings
	$modname = isset($modname) ? ((string)$modname) : '';

	// validate
    if (!pnVarValidate($modname, 'mod')) {
		return false;
	}

	if (strtolower(substr($type, -3)) == 'api') {
        return false;
    }

	static $loaded = array();

    if (!empty($loaded[strtolower("$modname$type")])) {
        // Already loaded from somewhere else
        return $modname;
    }

	// get the module info
	$modinfo = pnModGetInfo(pnModGetIDFromName($modname));

	// check the modules state
	if (!$force && !pnModAvailable($modname) && pnModGetName() != 'Modules') {
		return false;
	}

    // Load the module and module language files
    list($osdirectory, $ostype) = pnVarPrepForOS($modinfo['directory'], $type);
    $mosfile = "modules/$osdirectory/pn$ostype.php";
    $mosdir = "modules/$osdirectory/pn$ostype";
    if (file_exists($mosfile)) {
	    // Load the file from modules
	    include $mosfile;
	} elseif (is_dir($mosdir)) {
	} else {
		// File does not exist
        return false;
    }
    $loaded[strtolower("$modname$type")] = 1;

	// load the module language file
	pnModLangLoad($modname, $type);

    // Load datbase info
    pnModDBInfoLoad($modname, $modinfo['directory']);
    // Return the module name
    return $modname;
}

/**
 * run a module API function
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 * @param 'modname' the name of the module
 * @param 'type' the type of function to run
 * @param 'func' the specific function to run
 * @param 'args' the arguments to pass to the function
 * @returns mixed
 */
function pnModAPIFunc($modname, $type = 'user', $func = 'main', $args = array())
{
	// define input, all numbers and booleans to strings
	$modname = isset($modname) ? ((string)$modname) : '';

	// validate
    if (!pnVarValidate($modname, 'mod')) {
		return null;
	}

    if (empty($type)) {
        $type = 'user';
    } elseif(!pnVarValidate($type, 'api')) {
    	return null;
    }

    if (empty($func)) {
        $func = 'main';
    }

	list ($osmodname, $ostype, $osfunc) = pnVarPrepForOS($modname, $type, $func);

    // Build function name and call function
    $modapifunc = "{$modname}_{$type}api_{$func}";
	if (pnModAPILoad($modname, $type)) {
        if (function_exists($modapifunc)) {
            return $modapifunc($args);
        } elseif (file_exists("modules/$osmodname/pn{$ostype}api/$osfunc.php")) {
    		require_once("modules/$osmodname/pn{$ostype}api/$osfunc.php");
            if (function_exists($modapifunc)) {
    	        return $modapifunc($args);
    	    }
    	}
	}

	// if we get here, the function does not exist - show an error and die()
    // to-do: get execptions working for better handling of such errors
    include_once 'header.php';
    echo pnVarPrepHTMLDisplay(_UNKNOWNFUNC) . " " . pnVarPrepForDisplay($modapifunc) . "()<br />\n";
    if(pnSecAuthAction(0, $modname . '.*', '.*', ACCESS_ADMIN)) {
        foreach($args as $key => $value) {
            echo pnVarPrepForDisplay($key) . " => " . pnVarPrepForDisplay($value) . "<br />\n";
        }
    }
    include_once 'footer.php';
    exit;
}

/**
 * run a module function
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 * @param 'modname' the name of the module
 * @param 'type' the type of function to run
 * @param 'func' the specific function to run
 * @param 'args' the arguments to pass to the function
 * @returns mixed
 */
function pnModFunc($modname, $type = 'user', $func = 'main', $args = array())
{
	// define input, all numbers and booleans to strings
	$modname = isset($modname) ? ((string)$modname) : '';

	// validate
    if (!pnVarValidate($modname, 'mod')) {
		return null;
	}

	list ($osmodname, $ostype, $osfunc) = pnVarPrepForOS($modname, $type, $func);

    // Build function name and call function
    $modfunc = "{$modname}_{$type}_{$func}";
    if (pnModLoad($modname, $type)) {
        if (function_exists($modfunc)) {
            return $modfunc($args);
        } else {
            if (file_exists("modules/$osmodname/pn$ostype/$osfunc.php")) {
                require_once("modules/$osmodname/pn$ostype/$osfunc.php");
                if (function_exists($modfunc)) {
                    return $modfunc($args);
                }
            }
        }
    }

	// if we get here, the function does not exist - show an error and die()
    // to-do: get execptions working for better handling of such errors
    include_once 'header.php';
    echo pnVarPrepHTMLDisplay(_UNKNOWNFUNC) . " " . pnVarPrepForDisplay($modfunc) . "()<br />\n";
    if(pnSecAuthAction(0, $modname . '.*', '.*', ACCESS_ADMIN)) {
        foreach($args as $key => $value) {
            echo pnVarPrepForDisplay($key) . " => " . pnVarPrepForDisplay($value) . "<br />\n";
        }
    }
    include_once 'footer.php';
    exit;
}

/**
 * generate a module function URL
 *
 * if the module is non-API compliant (type 1) then
 * a) $func is ignored.
 * b) $type=admin will generate admin.php?module=... and $type=user will generate index.php?name=...
 *
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 * @param 'modname' the name of the module
 * @param 'type' the type of function to run
 * @param 'func' the specific function to run
 * @param 'args' the array of arguments to put on the URL
 * @param 'ssl'  set to constant null,true,false $ssl = true not $ssl = 'true'  null - leave the current status untouched, true - create a ssl url, false - create a non-ssl url
 * @return sting absolute URL for call
 */
function pnModURL($modname, $type = 'user', $func = 'main', $args = array(), $ssl=null)
{
	// define input, all numbers and booleans to strings
	$modname = isset($modname) ? ((string)$modname) : '';

	// validate
    if (!pnVarValidate($modname, 'mod')) {
		return null;
	}

	//get the module info
	$modinfo = pnModGetInfo(pnModGetIDFromName($modname));

	// set the module name to the display name if this is present
	if (isset($modinfo['displayname']) && !empty($modinfo['displayname'])) {
		$modname = rawurlencode($modinfo['displayname']);
	}

	// check the type of module
	$root = 'index.php';
	if ($modinfo['type'] == 1) {
		if ($type == 'admin') {
			$urlargs[] = "name=$modname";
			$root = 'admin.php';
		} else {
			$urlargs[] = "name=$modname";
		}
	} else {
		// The arguments
		$urlargs[] = "module=$modname";
		if ((!empty($type)) && ($type != 'user')) {
			$urlargs[] = "type=$type";
		}
		if ((!empty($func)) && ($func != 'main')) {
			$urlargs[] = "func=$func";
		}
	}
    $urlargs = join('&', $urlargs);
    $url = "$root?$urlargs";
    // <rabbitt> added array check on args
	// April 11, 2003
	if (!is_array($args)) {
		return false;
	} else {
		foreach ($args as $k => $v) {
        	if (is_array($v)) {
        	    foreach($v as $l => $w) {
        	        $url .= "&$k" . "[$l]=$w";
        	    }
        	} else {
        	    $url .= "&$k=$v";
        	}
		}
    }

    // Changes by pnCommerce team + the additional parameter SSL
    // The URL
    $url = pnGetBaseURL() . $url;

    // pnc addon - if ssl is set to true, change protocol to https
    // otherwise make sure http is used
    if ($ssl===true) {
		// itevo
        $url = str_replace ( "http://","https://", $url );
    } else if ($ssl===false) {
		// itevo
        $url = str_replace ( "https://","http://", $url );
    }

    return $url;
    //End Changes by pnCommerce team
}

/**
 * see if a module is available
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 * @param 'modname' the name of the module
 * @return bool true if the module is available, false if not
 */
function pnModAvailable($modname = null)
{
	// define input, all numbers and booleans to strings
	$modname = isset($modname) ? ((string)$modname) : '';

	// validate
    if (!pnVarValidate($modname, 'mod')) {
		return false;
	}

    static $modstate = array();

    if (!isset($modstate[strtolower($modname)]))  {
		$modinfo = pnModGetInfo(pnModGetIDFromName($modname));
		$modstate[strtolower($modname)] = $modinfo['state'];
	}

	if ((isset($modstate[strtolower($modname)]) && $modstate[strtolower($modname)] == _PNMODULE_STATE_ACTIVE)
	   	|| $modname == 'Modules') {
		return true;
	} else {
		return false;
	}

}

/**
 * get name of current top-level module
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 * @return string the name of the current top-level module, false if not in a module
 */
function pnModGetName()
{
    $modname = pnVarCleanFromInput('module');
    if (empty($modname)) {
        $name = pnVarCleanFromInput('name');
        if (empty($name)) {
			// anything from user.php is the user module
			// not really - of course but it'll do..... [markwest]
			if (stristr($_SERVER['PHP_SELF'], 'user.php')) {
				$ourmod = 'User';
			} else {
				$modname = pnConfigGetVar('startpage');
				$ourmod = $modname;
			}
        } else {
			$ourmod = $name;
		}
    } else {
        $ourmod = $modname;
    }

	// itevo
	if (substr($ourmod,0,3) == 'NS-') {
	    $ourmod = substr($ourmod,3);
	}

	// the parameters may provide the module alias so lets get
	// the real name from the db
	$modinfo = pnModGetInfo(pnModGetIDFromName($ourmod));

	return $modinfo['name'];
}

/**
 * register a hook function
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 * @param 'hookobject' the hook object
 * @param 'hookaction' the hook action
 * @param 'hookarea' the area of the hook (either 'GUI' or 'API')
 * @param 'hookmodule' name of the hook module
 * @param 'hooktype' name of the hook type
 * @param 'hookfunc' name of the hook function
 * @return bool true if successful, false otherwise
 */
function pnModRegisterHook($hookobject,
    $hookaction,
    $hookarea,
    $hookmodule,
    $hooktype,
    $hookfunc)
{
	// define input, all numbers and booleans to strings
	$hookmodule = isset($hookmodule) ? ((string)$hookmodule) : '';

	// validate
    if (!pnVarValidate($hookmodule, 'mod')) {
		return false;
	}

    // Get database info
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $hookstable = $pntable['hooks'];
    $hookscolumn = &$pntable['hooks_column'];
    // Insert hook
    $sql = "INSERT INTO $hookstable (
              $hookscolumn[id],
              $hookscolumn[object],
              $hookscolumn[action],
              $hookscolumn[tarea],
              $hookscolumn[tmodule],
              $hookscolumn[ttype],
              $hookscolumn[tfunc])
            VALUES (
              " . pnVarPrepForStore($dbconn->GenId($hookstable)) . ",
              '" . pnVarPrepForStore($hookobject) . "',
              '" . pnVarPrepForStore($hookaction) . "',
              '" . pnVarPrepForStore($hookarea) . "',
              '" . pnVarPrepForStore($hookmodule) . "',
              '" . pnVarPrepForStore($hooktype) . "',
              '" . pnVarPrepForStore($hookfunc) . "')";
    $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    return true;
}

/**
 * unregister a hook function
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 * @param 'hookobject' the hook object
 * @param 'hookaction' the hook action
 * @param 'hookarea' the area of the hook (either 'GUI' or 'API')
 * @param 'hookmodule' name of the hook module
 * @param 'hooktype' name of the hook type
 * @param 'hookfunc' name of the hook function
 * @return bool true if successful, false otherwise
 */
function pnModUnregisterHook($hookobject,
    $hookaction,
    $hookarea,
    $hookmodule,
    $hooktype,
    $hookfunc)
{
	// define input, all numbers and booleans to strings
	$hookmodule = isset($hookmodule) ? ((string)$hookmodule) : '';

	// validate
    if (!pnVarValidate($hookmodule, 'mod')) {
		return false;
	}

    // Get database info
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $hookstable = $pntable['hooks'];
    $hookscolumn = &$pntable['hooks_column'];
    // Remove hook
    $sql = "DELETE FROM $hookstable
            WHERE $hookscolumn[object] = '" . pnVarPrepForStore($hookobject) . "'
             AND $hookscolumn[action] = '" . pnVarPrepForStore($hookaction) . "'
             AND $hookscolumn[tarea] = '" . pnVarPrepForStore($hookarea) . "'
             AND $hookscolumn[tmodule] = '" . pnVarPrepForStore($hookmodule) . "'
             AND $hookscolumn[ttype] = '" . pnVarPrepForStore($hooktype) . "'
             AND $hookscolumn[tfunc] = '" . pnVarPrepForStore($hookfunc) . "'";
    $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    return true;
}

/**
 * carry out hook operations for module
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 * @param 'hookobject' the object the hook is called for - one of 'item', 'category' or 'module'
 * @param 'hookaction' the action the hook is called for - one of 'new', 'create', 'modify', 'update', 'delete', 'transform', 'display', 'modifyconfig', 'updateconfig'
 * @param 'hookid' the id of the object the hook is called for (module-specific)
 * @param 'extrainfo' extra information for the hook, dependent on hookaction
 * @param 'implode' implode collapses all display hooks into a single string - default to true for compatability with .7x
 * @return mixed string output from GUI hooks, extrainfo array for API hooks
 */
function pnModCallHooks($hookobject, $hookaction, $hookid, $extrainfo=array(), $implode = true)
{
	static $modulehooks;

	if (!isset($hookaction)) {
		return null;
	}

	if (isset($extrainfo['module']) &&
	    (pnModAvailable($extrainfo['module']) || strtolower($hookobject) == 'module')) {
		$modname = $extrainfo['module'];
	} else {
		$modname = pnModGetName();
	}

	if (!isset($modulehooks[strtolower($modname)])) {
		// Get database info
		$dbconn =& pnDBGetConn(true);
		$pntable =& pnDBGetTables();
		$hookstable = $pntable['hooks'];
		$hookscolumn = &$pntable['hooks_column'];
		// Get applicable hooks
		$sql = "SELECT $hookscolumn[tarea],
					   $hookscolumn[tmodule],
					   $hookscolumn[ttype],
					   $hookscolumn[tfunc],
					   $hookscolumn[action],
					   $hookscolumn[object]
				FROM $hookstable
				WHERE $hookscolumn[smodule] = '" . pnVarPrepForStore($modname) . "'";
		$result =& $dbconn->Execute($sql);

		if ($dbconn->ErrorNo() != 0) {
			return null;
		}
		$hooks = array();
	    for (; !$result->EOF; $result->MoveNext()) {
			list($area, $module, $type, $func, $action, $object) = $result->fields;
			$hooks[] = array('area' => $area,
							 'module' => $module,
							 'type' => $type,
							 'func' => $func,
							 'action' => $action,
							 'object' => $object);
		}
		$modulehooks[strtolower($modname)] = $hooks;
	}
    $gui = false;
    $output = array();

    // Call each hook
	foreach($modulehooks[strtolower($modname)] as $modulehook) {
		if (($modulehook['action'] == $hookaction) && ($modulehook['object'] == $hookobject)) {
			if ($modulehook['area'] == 'GUI') {
				$gui = true;
				if (pnModAvailable($modulehook['module'], $modulehook['type']) && pnModLoad($modulehook['module'], $modulehook['type'])) {
					$output[$modulehook['module']] = pnModFunc($modulehook['module'],
										  $modulehook['type'],
										  $modulehook['func'],
										  array('objectid' => $hookid,
											    'extrainfo' => $extrainfo));
				}
			} else {
				if (pnModAvailable($modulehook['module'], $modulehook['type']) && pnModAPILoad($modulehook['module'], $modulehook['type'])) {
					$extrainfo = pnModAPIFunc($modulehook['module'],
											  $modulehook['type'],
											  $modulehook['func'],
											  array('objectid' => $hookid,
												    'extrainfo' => $extrainfo));
				}
			}
		}
    }

	// check what type of information we need to return
	// credit to the xaraya team for the eregi check
	// itevo
    if ($gui ||
		strtolower($hookaction) == 'display' ||
		strtolower($hookaction) == 'new' ||
		strtolower($hookaction) == 'modify' ||
		strtolower($hookaction) == 'modifyconfig') {
		if ($implode || empty($output)) {
			$output = implode("\n", $output);
		}
        return $output;
    } else {
        return $extrainfo;
    }
}

/**
 * Determine if a module is hooked by another module
 * @author Mark West (mark@markwest.me.uk)
 * @link http://www.markwest.me.uk
 * @param 'tmodule' the target module
 * @param 'smodule' the source module - default the current top most module
 * @return bool true if the current module is hooked by the target module, false otherwise
 */
function pnModIsHooked($tmodule, $smodule)
{
    // define input, all numbers and booleans to strings
	$tmodule = isset($tmodule) ? ((string)$tmodule) : '';
	$smodule = isset($smodule) ? ((string)$smodule) : '';

	// validate
    if (!pnVarValidate($tmodule, 'mod') || !pnVarValidate($smodule, 'mod') ) {
		return false;
	}

    // Get database info
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $hookstable = $pntable['hooks'];
    $hookscolumn = &$pntable['hooks_column'];

    // Get applicable hooks
    $sql = "SELECT COUNT(1)
            FROM $hookstable
            WHERE $hookscolumn[smodule] = '" . pnVarPrepForStore($smodule) . "'
            AND $hookscolumn[tmodule] = '" . pnVarPrepForStore($tmodule) . "'";
    $result =& $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0) {
        return null;
    }

    // Obtain the number of items
    list($numitems) = $result->fields;

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Return the number of items
	if ($numitems > 0) {
	    return true;
	} else {
	    return false;
    }

}

/**
 * pnModLangLoad
 * loads the language files for a module
 *
 * @author Mark West
 * @link http://www.markwest.me.uk
 * @param modname - name of the module
 * @param type - type of the language file to load e.g. user, admin
 * @param api - load api lang file or gui lang file
 */
function pnModLangLoad($modname, $type = 'user', $api = false)
{
	// define input, all numbers and booleans to strings
	$modname = isset($modname) ? ((string)$modname) : '';

	// validate
    if (!pnVarValidate($modname, 'mod')) {
		return false;
	}

	// get the module info
	$modinfo = isset($modname) ? pnModGetInfo(pnModGetIDFromName($modname)) : false;

	if(!$modinfo){
		return false;
	}

	// create variables for the OS preped version of the directory
    list($osdirectory, $ostype) = pnVarPrepForOS($modinfo['directory'], $type);

    $defaultlang = pnConfigGetVar('language');
    if (empty($defaultlang)) {
        $defaultlang = 'eng';
    }

	$osapi = '';
	if ($api) {
		$osapi = 'api';
	}

    $currentlang = pnUserGetLang();
    if (file_exists("modules/$osdirectory/pnlang/$currentlang/{$ostype}{$osapi}.php")) {
        include_once "modules/$osdirectory/pnlang/" . pnVarPrepForOS($currentlang) . "/{$ostype}{$osapi}.php";
    } elseif (file_exists("modules/$osdirectory/pnlang/$defaultlang/{$ostype}{$osapi}.php")) {
        include_once "modules/$osdirectory/pnlang/" . pnVarPrepForOS($defaultlang) . "/{$ostype}{$osapi}.php";
    }

	return;
}

/**
 * Get the base directory for a module
 *
 * Example: If the webroot is located at
 * /var/www/html
 * and the module name is Template and is found
 * in the modules directory then this function
 * would return /var/www/html/modules/Template
 *
 * If the Template module was located in the system
 * directory then this function would return
 * /var/www/html/system/Template
 *
 * This allows you to say:
 * include(pnModGetBaseDir() . '/includes/private_functions.php');
 *
 * @author Chris Miller
 * @param   $modname - name of module to that you want the
 *                     base directory of.
 * @return  string - the path from the root directory to the
 *                   specified module.
 */
function pnModGetBaseDir($modname='')
{
	if(empty($modname)){
		$modname = pnModGetName();
	}
    $path = pnGetBaseURI();
	$directory = 'modules/'.$modname;
	if($path!=''){
		$path.='/';
	}
	$url=$path.$directory;
    return $url;
}

?>