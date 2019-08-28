<?php
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
// Purpose of file:  Admin Messages user API
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Admin_Messages
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * get all admin messages items
 * @author Mark West
 * @param int args['startnum'] starting number
 * @param int args['numitems'] number of items to get
 * @return mixed array of items, or false on failure
 */
function Admin_Messages_userapi_getall($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Optional arguments.
    if (!isset($startnum) || empty($startnum)) {
        $startnum = 1;
    }
    if (!isset($numitems) || empty($numitems)) {
        $numitems = -1;
    }

    if ((!is_numeric($startnum)) ||
        (!is_numeric($numitems))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $items = array();

    // Security check - important to do this as early on as possible to 
    // avoid potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Admin_Messages::', '::', ACCESS_READ)) {
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
    //$Admin_Messagestable = $pntable['Admin_Messages'];
    //$Admin_Messagescolumn = &$pntable['Admin_Messages_column'];
	$Admin_Messagescolumn = &$pntable['message_column'];

    // Get items - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the SelectLimit() command allows for simpler debug
    // operation if it is ever needed
    $sql = "SELECT $Admin_Messagescolumn[mid],
                   $Admin_Messagescolumn[title]
            FROM $pntable[message]
            ORDER BY $Admin_Messagescolumn[mid]";
    $result =& $dbconn->SelectLimit($sql, $numitems, $startnum-1);

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
        list($mid, $title) = $result->fields;
        if (pnSecAuthAction(0, 'Admin_Messages::', "$title::$mid", ACCESS_READ)) {
            $items[] = array('mid' => $mid,
                             'title' => $title);
        }
    }

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Return the items
    return $items;
}

/**
 * get a specific admin messages item
 * @author Mark West
 * @param int $args['mid'] id of message to get
 * @return mixed item array, or false on failure
 */
function Admin_Messages_userapi_get($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other places
    // such as the environment is not allowed, as that makes assumptions that
    // will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present, if
    // not then set an appropriate error message and return
    if ( (!isset($mid)) || (!is_numeric($mid)) ) {
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
    //$Admin_Messagestable = $pntable['Admin_Messages'];
    //$Admin_Messagescolumn = &$pntable['Admin_Messages_column'];
	$Admin_Messagescolumn = &$pntable['message_column'];

    // Get item - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the Execute() command allows for simpler debug operation
    // if it is ever needed
    $sql = "SELECT $Admin_Messagescolumn[mid],
                   $Admin_Messagescolumn[title],
                   $Admin_Messagescolumn[content],
                   $Admin_Messagescolumn[date],
                   $Admin_Messagescolumn[expire],
                   $Admin_Messagescolumn[active],
                   $Admin_Messagescolumn[view],
                   $Admin_Messagescolumn[mlanguage]
            FROM $pntable[message]
            WHERE $Admin_Messagescolumn[mid] = '" . (int)pnVarPrepForStore($mid) ."'";
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
    list($mid, $title, $content, $date, $expire, $active, $view, $mlanguage) = $result->fields;

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Security check - important to do this as early on as possible to avoid
    // potential security holes or just too much wasted processing.  Although
    // this one is a bit late in the function it is as early as we can do it as
    // this is the first time we have the relevant information
    if (!pnSecAuthAction(0, 'Admin_Messages::', "$title::$mid", ACCESS_READ)) {
        return false;
    }

    // Create the item array
    $item = array('mid' => $mid,
                  'title' => $title,
                  'content' => $content,
				  'date' => $date,
				  'expire' => $expire,
				  'active' => $active,
				  'whocanview' => $view,
				  'language' => $mlanguage);

    // Return the item array
    return $item;
}

/**
 * utility function to count the number of items held by this module
 * @author Mark West
 * @return int number of items held by this module
 */
function Admin_Messages_userapi_countitems()
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
    //$Admin_Messagestable = $pntable['Admin_Messages'];
    //$Admin_Messagescolumn = &$pntable['Admin_Messages_column'];
	$Admin_Messagescolumn = &$pntable['message_column'];	

    // Get item - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the Execute() command allows for simpler debug operation
    // if it is ever needed
    $sql = "SELECT COUNT(1)
            FROM $pntable[message]";
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
 * get all admin messages items
 * @author Mark West
 * @param int args['startnum'] starting number
 * @param int args['numitems'] number of items to get
 * @return mixed array of items, or false on failure
 */
function Admin_Messages_userapi_getactive($args)
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

    if ((!is_numeric($startnum)) ||
        (!is_numeric($numitems))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $items = array();

    // Security check - important to do this as early on as possible to 
    // avoid potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Admin_Messages::', '::', ACCESS_READ)) {
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
    $messagestable = $pntable['message'];
    $messagescolumn = &$pntable['message_column'];

	// Check if we're in a multilingual setup
    if (pnConfigGetVar('multilingual') == 1) {
        $currentlang = pnUserGetLang();
        $querylang = "AND ($messagescolumn[mlanguage]='$currentlang' OR $messagescolumn[mlanguage]='')";
    } else {
        $querylang = '';
    }

    // Get items - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the SelectLimit() command allows for simpler debug
    // operation if it is ever needed
    $sql = "SELECT $messagescolumn[mid],
                   $messagescolumn[title],
                   $messagescolumn[content],
                   $messagescolumn[date],
				   $messagescolumn[expire],
                   $messagescolumn[view]
            FROM $messagestable
            WHERE $messagescolumn[active] = 1 
            AND ($messagescolumn[date]+$messagescolumn[expire] > '".time()."'
            OR $messagescolumn[expire] = 0)
            $querylang
            ORDER by $messagescolumn[mid] DESC";

    $result =& $dbconn->Execute($sql);

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
        list($mid, $title, $content, $date, $expire, $view) = $result->fields;
        if (pnSecAuthAction(0, 'Admin_Messages::', "$title::$mid", ACCESS_READ)) {
            $items[] = array('mid' => $mid,
                             'title' => $title,
							 'content' => $content,
							 'date' => $date,
							 'expire' => $expire,
							 'view' => $view);
        }
    }

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Return the items
    return $items;
}

?>