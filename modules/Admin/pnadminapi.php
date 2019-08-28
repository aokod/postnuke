<?php
// $Id: pnadminapi.php 17446 2006-01-03 09:46:50Z markwest $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2002 by the PostNuke Development Team.
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
// Original Author of file: Mark West
// Purpose of file:  Admin administration API
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Admin
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * create a admin category
 * @author Mark West
 * @param string $args['catname'] name of the category
 * @param string $args['description'] description of the category
 * @return mixed admin category ID on success, false on failure
 */
function Admin_adminapi_create($args)
{

    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present,
    // if not then set an appropriate error message and return
    if (!isset($catname) ||
	    strlen($catname) == 0 || 
        !isset($description)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Admin::Category', "$catname::", ACCESS_ADD)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn()
    // we currently just want the first item, which is the official
    // database handle.  For pnDBGetTables() we want to keep the entire
    // tables array together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // It's good practice to name the table and column definitions you
    // are getting - $table and $column don't cut it in more complex
    // modules
    $admincategorytable = $pntable['admin_category'];
    $admincategorycolumn = &$pntable['admin_category_column'];

    // Get next ID in table - this is required prior to any insert that
    // uses a unique ID, and ensures that the ID generation is carried
    // out in a database-portable fashion
    $nextid = $dbconn->GenId($admincategorytable);

    // Add item - the formatting here is not mandatory, but it does make
    // the SQL statement relatively easy to read.  Also, separating out
    // the sql statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "INSERT INTO $admincategorytable (
              $admincategorycolumn[cid],
              $admincategorycolumn[catname],
              $admincategorycolumn[description])
            VALUES (
              $nextid,
              '" . pnVarPrepForStore($catname) . "',
              '" . pnvarPrepForStore($description) . "')";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _ADMINCREATEFAILED);
        return false;
    }

    // Get the ID of the item that we inserted.  It is possible, although
    // very unlikely, that this is different from $nextId as obtained
    // above, but it is better to be safe than sorry in this situation
    $cid = $dbconn->PO_Insert_ID($admincategorytable, $admincategorycolumn['cid']);

	// Let other modules know an item has been created
    pnModCallHooks('item', 'create', $cid, array('module' => 'Admin'));

    // Return the id of the newly created item to the calling process
    return $cid;
}

/**
 * delete a admin category
 * @author Mark West
 * @param int $args['cid'] ID of the category
 * @return bool true on success, false on failure
 */
function Admin_adminapi_delete($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present,
    // if not then set an appropriate error message and return
    if (!isset($cid)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // The user API function is called.  This takes the item ID which
    // we obtained from the input and gets us the information on the
    // appropriate item.  If the item does not exist we post an appropriate
    // message and return
    $item = pnModAPIFunc('Admin',
            'admin',
            'get',
            array('cid' => $cid));

    if ($item == false) {
        pnSessionSetVar('errormsg',_ADMINNOSUCHITEM);
        return false;
    }

    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing.
    // However, in this case we had to wait until we could obtain the item
    // name to complete the instance information so this is the first
    // chance we get to do the check
    if (!pnSecAuthAction(0, 'Admin::Category', "$item[catname]::$cid", ACCESS_DELETE)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Avoid deletion of the default category
    $defaultcategory = pnModGetVar('Admin', 'defaultcategory');
    if ($cid == $defaultcategory) {
        pnSessionSetVar('errormsg',_ADMINDELETEFAILEDDEFAULT);
        return false;
    }

    // Avoid deletion of the start category
    $startcategory = pnModGetVar('Admin', 'startcategory');
    if ($cid == $startcategory) {
        pnSessionSetVar('errormsg',_ADMINDELETEFAILEDSTART);
        return false;
    }

    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn()
    // we currently just want the first item, which is the official
    // database handle.  For pnDBGetTables() we want to keep the entire
    // tables array together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // move all modules from the category to be deleted into the
    // default category.
    $adminmoduletable = $pntable['admin_module'];
    $adminmodulecolumn = &$pntable['admin_module_column'];

    $sql = "UPDATE $adminmoduletable
            SET    $adminmodulecolumn[cid] = '" . (int)pnVarPrepForStore($defaultcategory) . "'
            WHERE  $adminmodulecolumn[cid] = '" . (int)pnVarPrepForStore($cid) . "'";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _ADMINDELETEFAILED);
        return false;
    }

    // Now actually delete the category

    // It's good practice to name the table and column definitions you
    // are getting - $table and $column don't cut it in more complex
    // modules
    $admincategorytable = $pntable['admin_category'];
    $admincategorycolumn = &$pntable['admin_category_column'];

    // Delete the item - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating
    // out the sql statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "DELETE FROM $admincategorytable
            WHERE $admincategorycolumn[cid] = '" . (int)pnVarPrepForStore($cid) . "'";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _ADMINDELETEFAILED);
        return false;
    }

    // Let any hooks know that we have deleted an item.  As this is a
    // delete hook we're not passing any extra info
    pnModCallHooks('item', 'delete', $cid, array('module' => 'Admin'));

    // Let the calling process know that we have finished successfully
    return true;
}

/**
 * update a admin category
 * @author Mark West
 * @param int $args['cid'] the ID of the category
 * @param string $args['catname'] the new name of the category
 * @param string $args['description'] the new description of the category
 * @return bool true on success, false on failure
 */
function Admin_adminapi_update($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present,
    // if not then set an appropriate error message and return
    if (!isset($cid) ||
		!is_numeric($cid) ||
        !isset($catname) ||
		strlen($catname) == 0 ||
        !isset($description)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // The user API function is called.  This takes the item ID which
    // we obtained from the input and gets us the information on the
    // appropriate item.  If the item does not exist we post an appropriate
    // message and return
    $item = pnModAPIFunc('Admin',
            'admin',
            'get',
            array('cid' => $cid));

    if ($item == false) {
        pnSessionSetVar('errormsg', _ADMINNOSUCHITEM);
        return false;
    }

    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing.
    // However, in this case we had to wait until we could obtain the item
    // name to complete the instance information so this is the first
    // chance we get to do the check

    // Note that at this stage we have two sets of item information, the
    // pre-modification and the post-modification.  We need to check against
    // both of these to ensure that whoever is doing the modification has
    // suitable permissions to edit the item otherwise people can potentially
    // edit areas to which they do not have suitable access
    if (!pnSecAuthAction(0, 'Admin::Category', "$item[catname]::$cid", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }
    if (!pnSecAuthAction(0, 'Admin::Category', "$catname::$cid", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn()
    // we currently just want the first item, which is the official
    // database handle.  For pnDBGetTables() we want to keep the entire
    // tables array together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // It's good practice to name the table and column definitions you
    // are getting - $table and $column don't cut it in more complex
    // modules
    $admincategorytable = $pntable['admin_category'];
    $admincategorycolumn = &$pntable['admin_category_column'];

    // Update the item - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating
    // out the sql statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "UPDATE $admincategorytable
            SET $admincategorycolumn[catname] = '" . pnVarPrepForStore($catname) . "',
                $admincategorycolumn[description] = '" . pnVarPrepForStore($description) . "'
            WHERE $admincategorycolumn[cid] = '" . (int)pnVarPrepForStore($cid) . "'";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _ADMINUPDATEFAILED);
        return false;
    }

    // New hook functions
    pnModCallHooks('item', 'update', $cid, array('module' => 'Admin'));

    // Let the calling process know that we have finished successfully
    return true;
}

/**
 * get all admin categories
 * @author Mark West
 * @param int $args['startnum'] starting record number
 * @param int $args['numitems'] number of items to get
 * @return mixed array of items, or false on failure
 */
function Admin_adminapi_getall($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Optional arguments.
    if (!isset($startnum)) {
        $startnum = 1;
    }
    if (!isset($numitems)) {
        $numitems = -1;
    }

    if ((!isset($startnum)) ||
        (!isset($numitems))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $items = array();

    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Admin::', '::', ACCESS_READ)) {
        return $items;
    }

    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn() we
    // currently just want the first item, which is the official database
    // handle.  For pnDBGetTables() we want to keep the entire tables array
    // together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // It's good practice to name the table and column definitions you are
    // getting - $table and $column don't cut it in more complex modules
    $admincategorytable = $pntable['admin_category'];
    $admincategorycolumn = &$pntable['admin_category_column'];

    // Get items - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the SelectLimit() command allows for simpler debug
    // operation if it is ever needed
    $sql = "SELECT $admincategorycolumn[cid],
                   $admincategorycolumn[catname],
                   $admincategorycolumn[description]
            FROM $admincategorytable
            ORDER BY $admincategorycolumn[catname]";
    $result = $dbconn->SelectLimit($sql, $numitems, $startnum-1);

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _GETFAILED);
        return false;
    }

    // Put items into result array.  Note that each item is checked
    // individually to ensure that the user is allowed access to it before it
    // is added to the results array
    for (; !$result->EOF; $result->MoveNext()) {
        list($cid, $catname, $description) = $result->fields;
        if (pnSecAuthAction(0, 'Admin::', "$catname::$cid", ACCESS_READ)) {
            $items[] = array('cid' => $cid,
                             'catname' => $catname,
                             'description' => $description);
        }
    }

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Return the items
    return $items;
}

/**
 * get a specific category
 * @author Mark West
 * @param int $args['cid'] id of example item to get
 * @return mixed item array, or false on failure
 */
function Admin_adminapi_get($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other places
    // such as the environment is not allowed, as that makes assumptions that
    // will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present, if
    // not then set an appropriate error message and return
    if (!isset($cid)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn() we
    // currently just want the first item, which is the official database
    // handle.  For pnDBGetTables() we want to keep the entire tables array
    // together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // It's good practice to name the table and column definitions you are
    // getting - $table and $column don't cut it in more complex modules
    $admincategorytable = $pntable['admin_category'];
    $admincategorycolumn = &$pntable['admin_category_column'];

    // Get item - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the Execute() command allows for simpler debug operation
    // if it is ever needed
    $sql = "SELECT $admincategorycolumn[catname],
                   $admincategorycolumn[description]
            FROM $admincategorytable
            WHERE $admincategorycolumn[cid] = '" . (int)pnVarPrepForStore($cid) . "'";
    $result =& $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    // Check for no rows found, and if so return
    if ($result->EOF) {
        return false;
    }

    // Obtain the item information from the result set
    list($catname, $description) = $result->fields;

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Security check - important to do this as early on as possible to avoid
    // potential security holes or just too much wasted processing.  Although
    // this one is a bit late in the function it is as early as we can do it as
    // this is the first time we have the relevant information
    if (!pnSecAuthAction(0, 'Admin::', "$catname::$cid", ACCESS_READ)) {
        return false;
    }

    // Create the item array
    $item = array('cid' => $cid,
                  'catname' => $catname,
                  'description' => $description);

    // Return the item array
    return $item;
}

/**
 * utility function to count the number of items held by this module
 * @author Mark West
 * @return int number of items held by this module
 */
function Admin_adminapi_countitems()
{
    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn() we
    // currently just want the first item, which is the official database
    // handle.  For pnDBGetTables() we want to keep the entire tables array
    // together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // It's good practice to name the table and column definitions you are
    // getting - $table and $column don't cut it in more complex modules
    $admincategorytable = $pntable['admin_category'];
    $admincategorycolumn = &$pntable['admin_category_column'];

    // Get item - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the Execute() command allows for simpler debug operation
    // if it is ever needed
    $sql = "SELECT COUNT(1)
            FROM $admincategorytable";
    $result =& $dbconn->Execute($sql);

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
 * add a module to a category
 * @author Mark West
 * @param string $args['module'] name of the module
 * @param int $args['category'] number of the category
 * @return mixed admin category ID on success, false on failure
 */
function Admin_adminapi_addmodtocategory($args)
{

    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present,
    // if not then set an appropriate error message and return
    if ((!isset($module)) ||
        (!isset($category))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Admin::Category', "::", ACCESS_ADD)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }


	// get module id
	$mid = pnModGetIDFromName($module);

    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn()
    // we currently just want the first item, which is the official
    // database handle.  For pnDBGetTables() we want to keep the entire
    // tables array together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // It's good practice to name the table and column definitions you
    // are getting - $table and $column don't cut it in more complex
    // modules
    $adminmoduletable = $pntable['admin_module'];
    $adminmodulecolumn = &$pntable['admin_module_column'];

    // empty the item from the table first.
    $sql = "DELETE FROM $adminmoduletable
            WHERE $adminmodulecolumn[mid] = '" . (int)pnvarPrepForStore($mid) . "'";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _ADMINCREATEFAILED);
        return false;
    }

    // Add item - the formatting here is not mandatory, but it does make
    // the SQL statement relatively easy to read.  Also, separating out
    // the sql statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "INSERT INTO $adminmoduletable (
              $adminmodulecolumn[cid],
              $adminmodulecolumn[mid])
            VALUES (
              '" . (int)pnVarPrepForStore($category) . "',
              '" . (int)pnvarPrepForStore($mid) . "')";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _ADMINCREATEFAILED);
        return false;
    }

    // Return success
    return true;
}

/**
 * Get the category a module belongs to
 * @author Mark West
 * @param int $args['mid'] id of the module
 * @return mixed category id, or false on failure
 */
function Admin_adminapi_getmodcategory($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other places
    // such as the environment is not allowed, as that makes assumptions that
    // will not hold in future versions of PostNuke
    extract($args);

	// create a static result set to prevent multiple sql queries
	static $catitems = array();

    // Argument check - make sure that all required arguments are present, if
    // not then set an appropriate error message and return
    if (!isset($mid)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

	// check if we've already worked this query out
	if (isset($catitems[$mid])) {
		return $catitems[$mid];
	}

    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn() we
    // currently just want the first item, which is the official database
    // handle.  For pnDBGetTables() we want to keep the entire tables array
    // together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // It's good practice to name the table and column definitions you are
    // getting - $table and $column don't cut it in more complex modules
    $adminmoduletable = $pntable['admin_module'];
    $adminmodulecolumn = &$pntable['admin_module_column'];

    // Get item - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the Execute() command allows for simpler debug operation
    // if it is ever needed
    $sql = "SELECT $adminmodulecolumn[cid],
				   $adminmodulecolumn[mid]
            FROM $adminmoduletable";
    $result =& $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    // Check for no rows found, and if so return
    if ($result->EOF) {
        return false;
    }

    // Put items into result array.  Note that each item is checked
    // individually to ensure that the user is allowed access to it before it
    // is added to the results array
    for (; !$result->EOF; $result->MoveNext()) {
        list($cid, $cmid) = $result->fields;
            $catitems[$cmid] = $cid;
    }

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Return the category id
	if (isset($catitems[$mid])) {
		return $catitems[$mid];
	} else {
		return false;
	}
}

/**
 * Get the category a module belongs to
 * @author Mark West
 * @param int $args['mid'] id of the module
 * @return mixed array of styles if successful, or false on failure
 */
function Admin_adminapi_getmodstyles($args)
{
	// exact our args
	extract($args);

	// check our input and get the module information
	if (!isset($modname) || !is_string($modname) || !is_array($modinfo = pnModGetInfo(pnModGetIDFromName($modname)))) {
		pnSessionSetVar(_MODARGSERROR);
		return false;
	}

	// create an empty result set
	$styles = array();

	$osmoddir = pnVarPrepForOS($modinfo['directory']);
	if (is_dir($dir = "modules/$osmoddir/pnstyle")) {
		$handle = opendir($dir);
		while ($file = readdir($handle)) {
			if (stristr($file, '.css')) {
				$styles[] = $file;
			}
		}
	} else if (is_dir($dir = "system/$osmoddir/pnstyle")) {
		$handle = opendir($dir);
		while ($file = readdir($handle)) {
			if (stristr($file, '.css')) {
				$styles[] = $file;
			}
		}
	}

	// return our results
	return $styles;
}
?>