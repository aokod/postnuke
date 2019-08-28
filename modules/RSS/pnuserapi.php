<?php
// $Id: pnuserapi.php 16928 2005-10-24 15:37:20Z markwest $
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
// Purpose of file:  RSS user API
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Content_Modules
 * @subpackage RSS
 * @license http://www.gnu.org/copyleft/gpl.html
*/

/**
 * get all rss feeds
 * @return mixed array of items, or false on failure
 */
function RSS_userapi_getall($args)
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

    if (pnModAvailable('Categories') && pnModIsHooked('Categories', 'RSS') && (isset($cid)))  {
        // The user API function is called.  This takes the item ID which
        // we obtained from the input and gets us the information on the
        // appropriate item.  If the item does not exist we post an appropriate
        // message and return
        $join = pnModAPIFunc('Categories',
                'user',
                'leftjoin',
                array('modid' => pnModGetIDFromName('RSS')));
	}

    $items = array();

    // Security check - important to do this as early on as possible to 
    // avoid potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'RSS::', '::', ACCESS_READ)) {
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
    $RSStable = $pntable['RSS'];
    $RSScolumn = &$pntable['RSS_column'];

	// construct the join for categories
    if ((pnModAvailable('Categories')) && pnModIsHooked('Categories', 'RSS') && (isset($cid))) {
		$joinsql = "LEFT JOIN $join[table] on ($join[iid] = $RSScolumn[fid])
         			WHERE $join[where] AND $join[cid] =  '" . (int)$cid . "'";
    } else {
	    $joinsql = '';
	}	

    // Get items - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the SelectLimit() command allows for simpler debug
    // operation if it is ever needed
    $sql = "SELECT $RSScolumn[fid],
                   $RSScolumn[name],
                   $RSScolumn[url]
            FROM $RSStable
			$joinsql
            ORDER BY $RSScolumn[name]";
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
        list($fid, $feedname, $url) = $result->fields;
        if (pnSecAuthAction(0, 'RSS::Item', "$feedname::$fid", ACCESS_READ)) {
            $items[] = array('fid' => $fid,
                             'feedname' => $feedname,
                             'url' => $url);
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
 * @param $args['fid'] id of example item to get
 * @return mixed item array, or false on failure
 */
function RSS_userapi_get($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other places
    // such as the environment is not allowed, as that makes assumptions that
    // will not hold in future versions of PostNuke
    extract($args);

	// optional arguments
	if (isset($objectid)) {
	   $fid = $objectid;
	}

    // Argument check - make sure that all required arguments are present, if
    // not then set an appropriate error message and return
    if (!isset($fid)) {
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
    $RSStable = $pntable['RSS'];
    $RSScolumn = &$pntable['RSS_column'];

    // Get item - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the Execute() command allows for simpler debug operation
    // if it is ever needed
    $sql = "SELECT $RSScolumn[name],
                   $RSScolumn[url]
            FROM $RSStable
            WHERE $RSScolumn[fid] = '" . (int)pnVarPrepForStore($fid) . "'";
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
    list($feedname, $url) = $result->fields;

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Security check - important to do this as early on as possible to avoid
    // potential security holes or just too much wasted processing.  Although
    // this one is a bit late in the function it is as early as we can do it as
    // this is the first time we have the relevant information
    if (!pnSecAuthAction(0, 'RSS::Item', "$feedname::$fid", ACCESS_READ)) {
        return false;
    }

    // Create the item array
    $item = array('fid' => $fid,
                  'feedname' => $feedname,
                  'url' => $url);

    // Return the item array
    return $item;
}

/**
 * utility function to count the url of items held by this module
 * @returns integer
 * @return integer count of items held by this module
 */
function RSS_userapi_countitems()
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
    $RSStable = $pntable['RSS'];
    $RSScolumn = &$pntable['RSS_column'];

    // Get item - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the Execute() command allows for simpler debug operation
    // if it is ever needed
    $sql = "SELECT COUNT(1)
            FROM $RSStable";
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
 * utility function to count the url of items held by this module
 *
 * @param integer fid feed id (not required if feed url is present)
 * @param string  furl feed url (not requred if feed id is present)
 * @return object object containing entire feed
 */
function RSS_userapi_getfeed($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other places
    // such as the environment is not allowed, as that makes assumptions that
    // will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present, if
    // not then set an appropriate error message and return
    if ((!isset($fid) || !is_numeric($fid)) 
	   && (!isset($furl) || !is_string($furl))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

	// check if the feed id is set, grab the feed from the db
	if (isset($fid)) {
		// Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
		// return arrays but we handle them differently.  For pnDBGetConn() we
		// currently just want the first item, which is the official database
		// handle.  For pnDBGetTables() we want to keep the entire tables array
		// together for easy reference later on
		$dbconn =& pnDBGetConn(true);
		$pntable =& pnDBGetTables();
	
		// It's good practice to name the table and column definitions you are
		// getting - $table and $column don't cut it in more complex modules
		$RSStable = $pntable['RSS'];
		$RSScolumn = &$pntable['RSS_column'];
	
		// Get item - the formatting here is not mandatory, but it does make the
		// SQL statement relatively easy to read.  Also, separating out the sql
		// statement from the Execute() command allows for simpler debug operation
		// if it is ever needed
		$sql = "SELECT $RSScolumn[url]
				FROM $RSStable
				WHERE $RSScolumn[fid] = '" . (int)pnVarPrepForStore($fid) . "'";
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
	
		// Obtain the url of item
		list($url) = $result->fields;
	} else {
		$url =& $furl;
	}

	// create array to hold feed
	$rssfeed = array();

	// define the cache directory
	if (!defined('MAGPIE_CACHE_DIR')) {
		define ('MAGPIE_CACHE_DIR', pnConfigGetVar('temp').'/'.pnVarPrepForOS(pnModGetVar('RSS', 'cachedirectory')).'/');
	}

	// include the Magpie RSS class
    require_once 'modules/RSS/pnincludes/rss_fetch.inc';

	// fetch the feed the feed
	$rss = fetch_rss($url);

	// return the results
	return $rss;
	
}

?>