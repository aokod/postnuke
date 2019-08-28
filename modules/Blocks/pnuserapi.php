<?php
// $Id: pnuserapi.php 16725 2005-08-28 13:13:29Z markwest $
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
 * @package PostNuke_System_Modules
 * @subpackage Blocks
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * Get all blocks
 * <br>
 * This function gets all block entries from the database
 * @author Mark West
 * @return   array   array of items, or false on failure
 */
function Blocks_userapi_getall($args)
{
	// extract our args
	extract($args);

	// create an empty items array
    $items = array();

    // Security check - important to do this as early on as possible to 
    // avoid potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Blocks::', '::', ACCESS_OVERVIEW)) {
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
    $blockstable = $pntable['blocks'];
    $blockscolumn = &$pntable['blocks_column']; 

	// initialise the where arguments array
	$whereargs = array();

    // Work out if we're showing all blocks or just active ones
    if (!pnSessionGetVar('blocks_show_all')) {
        $whereargs[] = "$blockscolumn[active] = 1";
    } 

	// check for a filter by module id
	if (isset($modid) && is_numeric($modid)) {
		$whereargs[] = "$blockscolumn[mid] = '".pnVarPrepForOS($modid)."'";
	}

	// construct the where clause	
	$where = '';
	if (!empty($whereargs)) {
		$where = 'WHERE ' . implode(' AND ', $whereargs);
	}

    // Get items - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the SelectLimit() command allows for simpler debug
    // operation if it is ever needed
    $sql = "SELECT $blockscolumn[bid],
                   $blockscolumn[bkey],
                   $blockscolumn[title],
                   $blockscolumn[url],
                   $blockscolumn[position],
                   $blockscolumn[weight],
                   $blockscolumn[mid],
                   $blockscolumn[active],
                   $blockscolumn[collapsable],
                   $blockscolumn[blanguage]
            FROM $blockstable
            $where
            ORDER BY $blockscolumn[position],
                     $blockscolumn[weight]";
    $result =& $dbconn->Execute($sql);
    $numrows = $result->PO_RecordCount(); 

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
        list($bid, $bkey, $title, $url, $position, $weight, $mid, $active, $collapsable, $blanguage) = $result->fields;
        if (pnSecAuthAction(0, 'Blocks::', "$bkey:$title:$bid", ACCESS_OVERVIEW)) {
            $items[] = array('bid' => $bid,
                             'bkey' => $bkey,
                             'title' => $title,
                             'url' => $url,
                             'position' => $position,
                             'weight' => $weight,
                             'mid' => $mid,
                             'active' => $active,
							 'collapsable' => $collapsable,
                             'blanguage' => $blanguage);
        }
    }

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Return the items
    return $items;
}

/**
 * get a specific block
 * 
 * @param    $args['bid']  id of block to get
 * @return   array         item array, or false on failure
 */
function Blocks_userapi_get($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other places
    // such as the environment is not allowed, as that makes assumptions that
    // will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present, if
    // not then set an appropriate error message and return
    if (!isset($bid) || !is_numeric($bid)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Return the item array
    return pnBlockGetInfo($bid);
}

/**
 * utility function to count the number of items held by this module
 * 
 * @return   integer   number of items held by this module
 */
function Blocks_userapi_countitems()
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
    $blockstable = $pntable['blocks'];
    $blockscolumn = &$pntable['blocks_column']; 

    // Get items - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the Execute() command allows for simpler debug operation
    // if it is ever needed
    $sql = "SELECT $blockscolumn[bid],
                   $blockscolumn[bkey],
                   $blockscolumn[title]
            FROM $blockstable";

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
        list($bid, $bkey, $title) = $result->fields;
        if (pnSecAuthAction(0, 'Blocks::', "$bkey:$title:$bid", ACCESS_OVERVIEW)) {
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