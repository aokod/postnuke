<?php
// $Id: pnadminapi.php 15497 2005-01-26 10:12:09Z markwest $
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
//$examplecolumn[itemname]
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * Example Module
 * 
 * The Example module shows how to make a PostNuke module. 
 * It can be copied over to get a basic file structure.
 *
 * Purpose of file:  administration API -- 
 *                   The file that contains all administrative
 *                   operational functions for the module
 *
 * @package      PostNuke_Miscellaneous_Modules
 * @subpackage   Example
 * @version      $Id: pnadminapi.php 15497 2005-01-26 10:12:09Z markwest $
 * @author       Jim McDonald
 * @author       Joerg Napp <jnapp@users.sourceforge.net>
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
 
 
/**
 * create a new Example item
 * 
 * @param    $args['itemname']    name of the item
 * @param    $args['number']  number of the item
 * @return   int              Example item ID on success, false on failure
 */
function Example_adminapi_create($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present,
    // if not then set an appropriate error message and return
    if ((!isset($itemname)) ||
        (!isset($number)) ||
        (isset($number) && !is_numeric($number))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Security check - important to do this as early on as possible to 
    // avoid potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Example::', "$itemname::", ACCESS_ADD)) {
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
    $exampletable  = &$pntable['example'];
    $examplecolumn = &$pntable['example_column'];

    // Get next ID in table - this is required prior to any insert that
    // uses a unique ID, and ensures that the ID generation is carried
    // out in a database-portable fashion
    $nextId = $dbconn->GenId($exampletable);

	// All variables that come in to or go out of PostNuke should be handled
	// by the relevant pnVar*() functions to ensure that they are safe. 
	// Failure to do this could result in opening security wholes at either 
	// the web, filesystem, display, or database layers. 
	list($itemname, $number) = pnVarPrepForStore($itemname, $number);
	
    // Add item - the formatting here is not mandatory, but it does make
    // the SQL statement relatively easy to read.  Also, separating out
    // the sql statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "INSERT INTO $exampletable (
              $examplecolumn[tid],
              $examplecolumn[itemname],
              $examplecolumn[number])
            VALUES (
              '".(int)$nextId."',
              '".$itemname."',
              '".(int)$number."'
              )";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _CREATEFAILED);
        return false;
    }

    // Get the ID of the item that we inserted.  It is possible, although
    // very unlikely, that this is different from $nextId as obtained
    // above, but it is better to be safe than sorry in this situation
    $tid = $dbconn->PO_Insert_ID($exampletable, $examplecolumn['tid']);

    // Let any hooks know that we have created a new item.
    pnModCallHooks('item', 'create', $tid, array('module' => 'Example'));

    // Return the id of the newly created item to the calling process
    return $tid;
}


/**
 * delete an item
 * 
 * @param    $args['tid']   ID of the item
 * @return   bool           true on success, false on failure
 */
function Example_adminapi_delete($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present,
    // if not then set an appropriate error message and return
    if ((!isset($tid)) ||
        (isset($tid) && !is_numeric($tid))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // The user API function is called.  This takes the item ID which
    // we obtained from the input and gets us the information on the
    // appropriate item.  If the item does not exist we post an appropriate
    // message and return
    $item = pnModAPIFunc('Example',
                         'user',
                         'get',
                          array('tid' => $tid));

    if (!$item) {
        pnSessionSetVar('errormsg', _NOSUCHITEM);
        return false;
    }

    // Security check 
    // In this case we had to wait until we could obtain the item
    // name to complete the instance information so this is the first
    // chance we get to do the check
    if (!pnSecAuthAction(0, 'Example::', "$item[name]::$tid", ACCESS_DELETE)) {
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
    $exampletable  = &$pntable['example'];
    $examplecolumn = &$pntable['example_column'];

    // Delete the item - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating
    // out the sql statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "DELETE FROM $exampletable
            WHERE $examplecolumn[tid] = '" . (int)pnVarPrepForStore($tid) ."'";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _DELETEFAILED);
        return false;
    }

    // Let any hooks know that we have deleted an item.
    pnModCallHooks('item', 'delete', $tid, array('module' => 'Example'));

	// The item has been deleted, so we clear all cached pages of this item.
    // As this function might be called by other modules as well, we need
	// to pass the module name to pnRender.
    $pnRender =& new pnRender('Example');
	// Please note that passing null as the first parameter to clear_cache,
	// all cached pages for the corresponding cache ID are cleared. 
	// As we are always using the item ID as the cache ID, all cached pages 
	// for this item ID are cleared.
	$pnRender->clear_cache(null, $tid);

    // Let the calling process know that we have finished successfully
    return true;
}

/**
 * update an item
 * 
 * @param    $args['tid']     the ID of the item
 * @param    $args['itemname']    the new name of the item
 * @param    $args['number']  the new number of the item
 * @return   bool             true on success, false on failure
 */
function Example_adminapi_update($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present,
    // if not then set an appropriate error message and return
    if ((!isset($tid)) ||
        (!isset($itemname)) ||
        (!isset($number)) ||
        (isset($tid) && !is_numeric($tid)) ||
        (isset($number) && !is_numeric($number))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // The user API function is called.  This takes the item ID which
    // we obtained from the input and gets us the information on the
    // appropriate item.  If the item does not exist we post an appropriate
    // message and return
    $item = pnModAPIFunc('Example',
                         'user',
                         'get',
                         array('tid' => $tid));

    if (!$item) {
        pnSessionSetVar('errormsg', _NOSUCHITEM);
        return false;
    }

    // Security check.
    // In this case we had to wait until we could obtain the item
    // name to complete the instance information so this is the first
    // chance we get to do the check

    // Note that at this stage we have two sets of item information, the
    // pre-modification and the post-modification.  We need to check against
    // both of these to ensure that whoever is doing the modification has
    // suitable permissions to edit the item otherwise people can potentially
    // edit areas to which they do not have suitable access
    if (!pnSecAuthAction(0, 'Example::', "$item[name]::$tid", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }
    if (!pnSecAuthAction(0, 'Example::', "$itemname::$tid", ACCESS_EDIT)) {
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
    $exampletable = &$pntable['example'];
    $examplecolumn = &$pntable['example_column'];

	// All variables that come in to or go out of PostNuke should be handled
	// by the relevant pnVar*() functions to ensure that they are safe. 
	// Failure to do this could result in opening security wholes at either 
	// the web, filesystem, display, or database layers. 
    list($itemname, $number, $tid) = pnVarPrepForStore($itemname, $number, $tid);

    // Update the item - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating
    // out the sql statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "UPDATE $exampletable
            SET $examplecolumn[itemname] = '".$itemname."',
                $examplecolumn[number] = '".(int)$number."'
            WHERE $examplecolumn[tid] = '".(int)$tid."'";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _UPDATEFAILED);
        return false;
    }

    // Let any hooks know that we have updated an item.
    pnModCallHooks('item', 'update', $tid, array('module' => 'Example'));

	// The item has been modified, so we clear all cached pages of this item.
    // As this function might be called by other modules as well, we need
	// to pass the module name to pnRender.
    $pnRender =& new pnRender('Example');
	// Please note that passing null as the first parameter to clear_cache,
	// all cached pages for the corresponding cache ID are cleared. 
	// As we are always using the item ID as the cache ID, all cached pages 
	// for this item ID are cleared.
    $pnRender->clear_cache(null, $tid);
	
    // Let the calling process know that we have finished successfully
    return true;
}

?>