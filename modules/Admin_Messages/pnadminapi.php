<?php
// $Id: pnadminapi.php 19345 2006-07-03 09:13:38Z markwest $
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
// Purpose of file:  Admin Messages administration API
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Admin_Messages
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * create a new Admin_Messages item
 * @author Mark West
 * @param string $args['title'] title of the admin message
 * @param string $args['content'] text of the admin message
 * @param string $args['language'] the language of the message
 * @param int $args['active'] activation status of the message
 * @param int $args['expire'] expiry date of the message
 * @param int $args['whocanview'] who can view the message
 * @return mixed Admin_Messages item ID on success, false on failure
 */
function Admin_Messages_adminapi_create($args)
{

    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present,
    // if not then set an appropriate error message and return
    if ((!isset($title)) ||
        (!isset($content)) ||
		(!isset($language)) ||
		(!isset($active)) ||
		(!isset($expire)) ||
		(!isset($whocanview))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Security check - important to do this as early on as possible to 
    // avoid potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Admin_Messages::', "::", ACCESS_ADD)) {
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
    // to do create init scripts - markwest	
    //$Admin_Messagestable = $pntable['Admin_Messages'];
    //$Admin_Messagescolumn = &$pntable['Admin_Messages_column'];
    $Admin_Messagescolumn = &$pntable['message_column'];


    // Get next ID in table - this is required prior to any insert that
    // uses a unique ID, and ensures that the ID generation is carried
    // out in a database-portable fashion
    //$nextId = $dbconn->GenId($Admin_Messagestable);
    $nextid = $dbconn->GenId($pntable['message']);

    // Add item - the formatting here is not mandatory, but it does make
    // the SQL statement relatively easy to read.  Also, separating out
    // the sql statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "INSERT INTO $pntable[message]
              ($Admin_Messagescolumn[mid],
               $Admin_Messagescolumn[title],
               $Admin_Messagescolumn[content],
               $Admin_Messagescolumn[date],
               $Admin_Messagescolumn[expire],
               $Admin_Messagescolumn[active],
               $Admin_Messagescolumn[view],
               $Admin_Messagescolumn[mlanguage])
            VALUES
              ('" . (int)pnVarPrepForStore($nextid) . "',
              '" . pnVarPrepForStore($title) . "',
              '" . pnVarPrepForStore($content) . "',
              '" . pnVarPrepForStore(time()) . "',
              '" . (int)pnVarPrepForStore($expire*86400) . "',
              '" . (int)pnVarPrepForStore($active) . "',
              '" . (int)pnVarPrepForStore($whocanview) . "',
              '" . pnVarPrepForStore($language) . "')";

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
    //$mid = $dbconn->PO_Insert_ID($Admin_Messagestable, $Admin_Messagescolumn['mid']);
    $mid = $dbconn->PO_Insert_ID($pntable['message'], $Admin_Messagescolumn['mid']);

    // Let any hooks know that we have created a new item.  As this is a
    // create hook we're passing $args and the mid as the extra info, 
    // which is the argument that all of the other functions use to reference
    // this item

    // New hook functions
    pnModCallHooks('item', 'create', $mid, array('module' => 'Admin_Messages'));

    // Return the id of the newly created item to the calling process
    return $mid;
}

/**
 * delete an Admin_Messages item
 * @author Mark West
 * @param int $args['mid'] ID of the admin message to delete
 * @return bool true on success, false on failure
 */
function Admin_Messages_adminapi_delete($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present,
    // if not then set an appropriate error message and return
    if (!isset($mid)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // The user API function is called.  This takes the item ID which
    // we obtained from the input and gets us the information on the
    // appropriate item.  If the item does not exist we post an appropriate
    // message and return
    $item = pnModAPIFunc('Admin_Messages',
            'user',
            'get',
            array('mid' => $mid));

    if ($item == false) {
        pnSessionSetVar('errormsg', _ADMINMESSAGESNOSUCHITEM);
        return false;
    }

    // Security check - important to do this as early on as possible to 
    // avoid potential security holes or just too much wasted processing.
    // However, in this case we had to wait until we could obtain the item
    // name to complete the instance information so this is the first
    // chance we get to do the check
    if (!pnSecAuthAction(0, 'Admin_Messages::', "$item[title]::$mid", ACCESS_DELETE)) {
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
    //$Admin_Messagestable = $pntable['Admin_Messages'];
    //$Admin_Messagescolumn = &$pntable['Admin_Messages_column'];
    $Admin_Messagescolumn = &$pntable['message_column'];

    // Delete the item - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating
    // out the sql statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "DELETE FROM $pntable[message]
            WHERE $Admin_Messagescolumn[mid] = '" . (int)pnVarPrepForStore($mid) . "'";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _DELETEFAILED);
        return false;
    }

    // Let any hooks know that we have deleted an item.  As this is a
    // delete hook we're not passing any extra info
    pnModCallHooks('item', 'delete', $mid, array('module' => 'Admin_Messages'));

	// The item has been modified, so we clear all cached pages of this item.
    // As this function might be called by other modules as well, we need
	// to pass the module name to pnRender.
    $pnRender =& new pnRender('Admin_Messages');
	// Please note that passing null as the first parameter to clear_cache,
	// all cached pages for the corresponding cache ID are cleared. 
	// As we are always using the item ID as the cache ID, all cached pages 
	// for this item ID are cleared.
    $pnRender->clear_cache(null, pnUserGetVar('uid'));

    // Let the calling process know that we have finished successfully
    return true;
}

/**
 * update a Admin_Messages item
 * @author Mark West
 * @param int $args['mid'] the ID of the item
 * @param sting $args['title'] title of the admin message
 * @param string $args['content'] text of the admin message
 * @param string $args['language'] the language of the message
 * @param int $args['active'] activation status of the message
 * @param int $args['expire'] expiry date of the message
 * @param int $args['whocanview'] who can view the message
 * @return bool true if successful, false otherwise
 */
function Admin_Messages_adminapi_update($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present,
    // if not then set an appropriate error message and return
    if ((!isset($mid)) ||
	    (!isset($title)) ||
        (!isset($content)) ||
		(!isset($language)) ||
		(!isset($active)) ||
		(!isset($expire)) ||
		(!isset($oldtime)) ||
		(!isset($changestartday)) ||
		(!isset($whocanview))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // The user API function is called.  This takes the item ID which
    // we obtained from the input and gets us the information on the
    // appropriate item.  If the item does not exist we post an appropriate
    // message and return
    $item = pnModAPIFunc('Admin_Messages',
            'user',
            'get',
            array('mid' => $mid));

    if ($item == false) {
        pnSessionSetVar('errormsg', _ADMINMESSAGESNOSUCHITEM);
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
    if (!pnSecAuthAction(0, 'Admin_Messages::', "$item[title]::$mid", ACCESS_EDIT)) {
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
    //$Admin_Messagestable = $pntable['Admin_Messages'];
    //$Admin_Messagescolumn = &$pntable['Admin_Messages_column'];
    $Admin_Messagescolumn = &$pntable['message_column'];	

    // check value of change start day to today and set time
	if ($changestartday == 1) {
	    $time = time();
	} else {
	    $time = $oldtime;
	}

    // check for an invalid expiry
    if ($expire < 0) {
        $expire = 0;
    }

    // Update the item - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating
    // out the sql statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "UPDATE $pntable[message]
            SET $Admin_Messagescolumn[title] = '" . pnVarPrepForStore($title) . "',
                $Admin_Messagescolumn[content] = '" . pnVarPrepForStore($content) . "',
                $Admin_Messagescolumn[date] = '" . pnVarPrepForStore($time) . "',
                $Admin_Messagescolumn[expire] = '" . (int)pnVarPrepForStore($expire*86400) . "',
                $Admin_Messagescolumn[active] = '" . (int)pnVarPrepForStore($active) . "',
                $Admin_Messagescolumn[view] = '" . (int)pnVarPrepForStore($whocanview) . "',
                $Admin_Messagescolumn[mlanguage] = '" . pnVarPrepForStore($language) . "'
            WHERE $Admin_Messagescolumn[mid] = '" . (int)pnVarPrepForStore($mid) . "'";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _UPDATEFAILED);
        return false;
    }

    // New hook functions
    pnModCallHooks('item', 'update', $mid, array('module' => 'Admin_Messages'));

	// The item has been modified, so we clear all cached pages of this item.
    // As this function might be called by other modules as well, we need
	// to pass the module name to pnRender.
    $pnRender =& new pnRender('Admin_Messages');
	// Please note that passing null as the first parameter to clear_cache,
	// all cached pages for the corresponding cache ID are cleared. 
	// As we are always using the item ID as the cache ID, all cached pages 
	// for this item ID are cleared.
    $pnRender->clear_cache(null, pnUserGetVar('uid'));

    // Let the calling process know that we have finished successfully
    return true;
}

?>