<?php
// $Id: pnuserapi.php 14603 2004-09-18 21:30:28Z markwest $
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
/**
 * Example Module
 * 
 * The Example module shows how to make a PostNuke module. 
 * It can be copied over to get a basic file structure.
 *
 * Purpose of file:  User API -- 
 *                   The file that contains all user operational 
 *                   functions for the module
 *
 * @package      PostNuke_Miscellaneous_Modules
 * @subpackage   Example
 * @version      $Id: pnuserapi.php 14603 2004-09-18 21:30:28Z markwest $
 * @author       Jim McDonald
 * @author       Joerg Napp <jnapp@users.sourceforge.net>
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

 
/**
 * get all example items
 * 
 * @param    int     $args['starnum']    (optional) first item to return
 * @param    int     $args['numitems']   (optional) number if items to return
 * @return   array   array of items, or false on failure
 */
function Example_userapi_getall($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Optional arguments.
    if (!isset($startnum) || !is_numeric($startnum)) {
        $startnum = 1;
    }
    if (!isset($numitems) || !is_numeric($numitems)) {
        $numitems = -1;
    }

    $items = array();

    // Security check - important to do this as early on as possible to 
    // avoid potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Example::', '::', ACCESS_OVERVIEW)) {
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
    $exampletable = $pntable['example'];
    $examplecolumn = &$pntable['example_column'];

    // Get items - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the SelectLimit() command allows for simpler debug
    // operation if it is ever needed
    $sql = "SELECT $examplecolumn[tid],
                   $examplecolumn[itemname],
                   $examplecolumn[number]
            FROM $exampletable
            ORDER BY $examplecolumn[itemname]";

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
        list($tid, $itemname, $number) = $result->fields;
        if (pnSecAuthAction(0, 'Example::', "$itemname::$tid", ACCESS_OVERVIEW)) {
            $items[] = array('tid' => $tid,
                             'itemname' => $itemname,
                             'number' => $number);
        }
    }

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Return the items
    return $items;
}

/**
 * get a specific item
 * 
 * @param    $args['tid']  id of example item to get
 * @return   array         item array, or false on failure
 */
function Example_userapi_get($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other places
    // such as the environment is not allowed, as that makes assumptions that
    // will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present, if
    // not then set an appropriate error message and return
    if (!isset($tid) || !is_numeric($tid)) {
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
    $exampletable = $pntable['example'];
    $examplecolumn = &$pntable['example_column'];

    // Get item - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the Execute() command allows for simpler debug operation
    // if it is ever needed
    $sql = "SELECT $examplecolumn[itemname],
                   $examplecolumn[number]
            FROM $exampletable
            WHERE $examplecolumn[tid] = '" . (int)pnVarPrepForStore($tid) ."'";
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
    list($itemname, $number) = $result->fields;

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Security check - important to do this as early on as possible to avoid
    // potential security holes or just too much wasted processing.  Although
    // this one is a bit late in the function it is as early as we can do it as
    // this is the first time we have the relevant information
    if (!pnSecAuthAction(0, 'Example::', "$itemname::$tid", ACCESS_READ)) {
       return false;
    }

    // Create the item array
    $item = array('tid'    => $tid,
                  'itemname'   => $itemname,
                  'number' => $number);

    // Return the item array
    return $item;
}

/**
 * utility function to count the number of items held by this module
 * 
 * @return   integer   number of items held by this module
 */
function Example_userapi_countitems()
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
    $exampletable = &$pntable['example'];
    $examplecolumn = &$pntable['example_column'];

    // Get items - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the Execute() command allows for simpler debug operation
    // if it is ever needed
    $sql = "SELECT $examplecolumn[tid],
				   $examplecolumn[itemname],
                   $examplecolumn[number]
            FROM $exampletable";

    $result =& $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    // Put items into result array.  Note that each item is checked
    // individually to ensure that the user is allowed access to it before it
    // is added to the results array
    $numitems = 0;
    for (; !$result->EOF; $result->MoveNext()) {
        list($tid, $itemname, $number) = $result->fields;
        if (pnSecAuthAction(0, 'Example::', "$itemname::$tid", ACCESS_OVERVIEW)) {
            $numitems++;
        }
    }

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Return the number of items
    return $numitems;
}

?>