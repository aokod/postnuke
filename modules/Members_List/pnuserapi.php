<?php
// File: $Id: pnuserapi.php 15630 2005-02-04 06:35:42Z jorg $
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
// Purpose of file:  Members List user API
// ----------------------------------------------------------------------

/**
 * @package PostNuke_ResourcePack_Modules
 * @subpackage Members_List
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * Get all users
 * This API fucntion returns all users ids. This function allows for
 * filtering and for paged selection
 * @author Mark West 
 * @param 'startnum' start number for recordset
 * @param 'numitems' number of items to return
 * @param 'letter' letter to filter by
 * @param 'sortby' attribute to sort by
 * @param 'sortorder' sort order ascending/descending
 * @return array matching user ids
 */
function Members_List_userapi_getall($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other places
    // such as the environment is not allowed, as that makes assumptions that
    // will not hold in future versions of PostNuke
    extract($args);

    // Optional arguments.
    if (!isset($startnum)) {
        $startnum = 1;
    }
    if (!isset($numitems)) {
        $numitems = -1;
    }
    if (!isset($letter)) {
        $letter = _ALL;
    }
    if (!isset($sortby)) {
        $sortby = 'uname';
    }
    if (!isset($sortorder) || empty($sortorder)) {
        $sortorder = 'ASC';
    }
    if (!isset($sorting) || empty($sorting)) {
        $sorting = 0;
    }
    if (!isset($searchby)) {
    	$searchby = '';
    }

    if ((!isset($startnum)) ||
        (!isset($numitems)) ||
        (!isset($sortby)) ||		
        (!isset($sortorder)) ||
        (!isset($letter))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // define the array to hold the result items
    $items = array();

    // Security check - important to do this as early on as possible to 
    // avoid potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Members_List::', '::', ACCESS_READ)) {
        return $items;
    }

    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn() we
    // currently just want the first item, which is the official database
    // handle.  For pnDBGetTables() we want to keep the entire tables array
    // together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

	// load the database information for the users module
	pnModDBInfoLoad('Users');

    // It's good practice to name the table and column definitions you are
    // getting - $table and $column don't cut it in more complex modules
    $userscolumn = &$pntable['users_column'];

    // Get items - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the SelectLimit() command allows for simpler debug
    // operation if it is ever needed
    $sql = "SELECT $userscolumn[uid] FROM $pntable[users] ";

    if (!$searchby){	
        if (($letter != _MEMBERSLISTOTHER) AND ($letter != _ALL)) {
            // are we listing all or "other" ?
            $where = "WHERE UPPER($userscolumn[uname]) LIKE UPPER('".pnVarPrepForStore($letter)."%') AND $userscolumn[uname] NOT LIKE 'Anonymous' ";
            // I guess we are not..
        } else if (($letter == _MEMBERSLISTOTHER) AND ($letter != _ALL)) {
            // But other is numbers ?
            $where = "WHERE ($userscolumn[uname] LIKE '0%'
                       OR $userscolumn[uname] LIKE '1%'
                       OR $userscolumn[uname] LIKE '2%'
                       OR $userscolumn[uname] LIKE '3%'
                       OR $userscolumn[uname] LIKE '4%'
                       OR $userscolumn[uname] LIKE '5%'
                       OR $userscolumn[uname] LIKE '6%'
                       OR $userscolumn[uname] LIKE '7%'
                       OR $userscolumn[uname] LIKE '8%'
                       OR $userscolumn[uname] LIKE '9%'
                       OR $userscolumn[uname] LIKE '-%'
                       OR $userscolumn[uname] LIKE '.%'
                       OR $userscolumn[uname] LIKE '@%'
                       OR $userscolumn[uname] LIKE '$%') ";
            // fifers: while this is not the most eloquent solution, it is
            // cross database compatible.  We could do an if dbtype is mysql
            // then do the regexp.  consider for performance enhancement.
            //
            // "WHERE $column[uname] REGEXP \"^\[1-9]\" "
            // REGEX :D, although i think its MySQL only
            // Will have to change this later.
            // if you know a better way to match only the first char
            // to be a number in uname, please change it and email
            // sweede@gallatinriver.net the correction
            // or go to post-nuke project page and post
            // your correction there. Thanks, Bjorn.
        } else { // or we are unknown or all..
            $where = "WHERE $userscolumn[uname] NOT LIKE 'Anonymous' "; // this is to get rid of anoying "undefinied variable" message
        }
    } else {
        $where = "WHERE $userscolumn[$searchby] LIKE '%".pnVarPrepForStore($letter)."%' ";
    }

	if (array_key_exists('activated', $userscolumn)) {
		if (!$sorting && pnModGetVar('Members_List', 'filterunverified')) {
			$where .= "AND $userscolumn[activated] != '1'";
		} else if ($sorting && pnModGetVar('Members_List', 'filterunverified')) {
			$where .= "AND $userscolumn[activated] = '0' ";
		}
	}

    $sort = "ORDER BY $userscolumn[$sortby] " . $sortorder; //sorty by .....

    $sql = $sql . $where . $sort;

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
        list($uid) = $result->fields;
        if (pnSecAuthAction(0, 'Members_List::', "::", ACCESS_READ)) {
            $items[] = array('uid' => $uid);
        }
    }

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Return the items
    return $items;
}

/**
 * Utility function to count the number of users
 * This function allows for filtering by letter
 * @author Mark West
 * @param 'letter' letter to filter by
 * @return intger count of matching users
 */
function Members_List_userapi_countitems($args)
{

    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other places
    // such as the environment is not allowed, as that makes assumptions that
    // will not hold in future versions of PostNuke
    extract($args);

    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn() we
    // currently just want the first item, which is the official database
    // handle.  For pnDBGetTables() we want to keep the entire tables array
    // together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

	// load the database information for the users module
	pnModDBInfoLoad('Users');

    // It's good practice to name the table and column definitions you are
    // getting - $table and $column don't cut it in more complex modules
    $userscolumn = &$pntable['users_column'];

    $letter = pnVarCleanFromInput('letter');
    if (empty($letter)) {
        $letter = _ALL;
    }

    // Get item - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the Execute() command allows for simpler debug operation
    // if it is ever needed
    $select = "SELECT COUNT(1) FROM $pntable[users] ";
    if (($letter != _MEMBERSLISTOTHER) AND ($letter != _ALL)) {
        $where = "WHERE (($userscolumn[uname] LIKE '" . pnVarPrepForStore($letter) . "%') AND ($userscolumn[uname] NOT LIKE 'Anonymous'))";
    } else if (($letter == _MEMBERSLISTOTHER) AND ($letter != _ALL)) {
        $where = "WHERE ($userscolumn[uname] LIKE '0%'
                  OR $userscolumn[uname] LIKE '1%'
                  OR $userscolumn[uname] LIKE '2%'
                  OR $userscolumn[uname] LIKE '3%'
                  OR $userscolumn[uname] LIKE '4%'
                  OR $userscolumn[uname] LIKE '5%'
                  OR $userscolumn[uname] LIKE '6%'
                  OR $userscolumn[uname] LIKE '7%'
                  OR $userscolumn[uname] LIKE '8%'
                  OR $userscolumn[uname] LIKE '9%'
                  OR $userscolumn[uname] LIKE '-%'
                  OR $userscolumn[uname] LIKE '.%'
                  OR $userscolumn[uname] LIKE '@%'
                  OR $userscolumn[uname] LIKE '$%') ";
    } else {
        $where = "WHERE $userscolumn[uname] NOT LIKE 'Anonymous'";
    }

	if (array_key_exists('activated', $userscolumn)) {
		if (pnModGetVar('Members_List', 'filterunverified')) {
			$where .= " AND $userscolumn[activated] != '1'";
		}
	}

    $sql = $select . $where;
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
 * Utility function to count the number of users online
 * @author Mark West
 * @return integer count of registered users online
 */
function Members_List_userapi_getregisteredonline($args) 
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other places
    // such as the environment is not allowed, as that makes assumptions that
    // will not hold in future versions of PostNuke
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
    $sessioninfocolumn = &$pntable['session_info_column'];
    $sessioninfotable = $pntable['session_info'];

    $activetime = time() - (pnConfigGetVar('secinactivemins') * 60);	

    // Get items - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the SelectLimit() command allows for simpler debug
    // operation if it is ever needed
    $sql = "SELECT DISTINCT $sessioninfocolumn[uid] FROM $sessioninfotable
            WHERE $sessioninfocolumn[uid] != 0 AND $sessioninfocolumn[lastused] > $activetime
            GROUP BY $sessioninfocolumn[uid]";
    $result =& $dbconn->Execute($sql);

     // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _GETFAILED);
        return false;
    }

    // Obtain the number of items
    $numusers = $result->RecordCount();
	
    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Return the number of items
    return $numusers;
}

/**
 * Utility function get the latest registered user
 * @author Mark West
 * @return integer latest registered user id
 */
function Members_List_userapi_getlatestuser($args) 
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other places
    // such as the environment is not allowed, as that makes assumptions that
    // will not hold in future versions of PostNuke
    extract($args);

    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn() we
    // currently just want the first item, which is the official database
    // handle.  For pnDBGetTables() we want to keep the entire tables array
    // together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

	// load the database information for the users module
	pnModDBInfoLoad('Users');

    // It's good practice to name the table and column definitions you are
    // getting - $table and $column don't cut it in more complex modules
    $userscolumn = &$pntable['users_column'];

	// filter out unverified users
	$where = '';
	if (array_key_exists('activated', $userscolumn)) {
		if (pnModGetVar('Members_List', 'filterunverified')) {
			$where = " AND $userscolumn[activated] != '1'";
		}
	}

    // Get items - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the SelectLimit() command allows for simpler debug
    // operation if it is ever needed
	$sql = "SELECT $userscolumn[uid] 
            FROM $pntable[users] 
            WHERE $userscolumn[uname] NOT LIKE 'Anonymous' $where 
			ORDER BY $userscolumn[uid] DESC";
    $result =& $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _GETFAILED);
        return false;
    }

    // Obtain the number of items
    list($lastuser) = $result->fields;

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Return the number of items
    return $lastuser;

}

/**
 * Utility function to decide if a user is online
 * @author Mark West
 * @return bool true if online, false otherwise
 */
function Members_List_userapi_isonline($args) 
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other places
    // such as the environment is not allowed, as that makes assumptions that
    // will not hold in future versions of PostNuke
    extract($args);

    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn() we
    // currently just want the first item, which is the official database
    // handle.  For pnDBGetTables() we want to keep the entire tables array
    // together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // get active time based on PN security settings
    $activetime = time() - (pnConfigGetVar('secinactivemins') * 60);

    // It's good practice to name the table and column definitions you are
    // getting - $table and $column don't cut it in more complex modules
    $sessioninfocolumn = &$pntable['session_info_column'];
    $sessioninfotable = $pntable['session_info'];

    // Get items - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the SelectLimit() command allows for simpler debug
    // operation if it is ever needed
    $sql = "SELECT DISTINCT $sessioninfocolumn[uid] 
	        FROM $sessioninfotable 
			WHERE $sessioninfocolumn[uid] = $userid and $sessioninfocolumn[lastused] > $activetime";
    $result =& $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _GETFAILED);
        return false;
    }

    // Obtain the item
    list($online) = $result->fields;

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Return if the user is online
    if ($online > 0) {
        return true;
    } else {
        return false;
    }

}

/**
 * Utility function return registered users online
 * @author Mark West
 * @return array registered users
 */
function Members_List_userapi_whosonline($args) 
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other places
    // such as the environment is not allowed, as that makes assumptions that
    // will not hold in future versions of PostNuke
    extract($args);

    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn() we
    // currently just want the first item, which is the official database
    // handle.  For pnDBGetTables() we want to keep the entire tables array
    // together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // define the array to hold the resultant items
    $items = array();
    // It's good practice to name the table and column definitions you are
    // getting - $table and $column don't cut it in more complex modules
    $sessioninfocolumn = &$pntable['session_info_column'];
    $sessioninfotable = $pntable['session_info'];

    // get active time based on PN security settings
    $activetime = time() - (pnConfigGetVar('secinactivemins') * 60);

    // Get items - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the SelectLimit() command allows for simpler debug
    // operation if it is ever needed
    $sql = "SELECT DISTINCT $sessioninfocolumn[uid]
            FROM $sessioninfotable
            WHERE $sessioninfocolumn[uid] != 0
			AND $sessioninfocolumn[lastused] > $activetime
            GROUP BY $sessioninfocolumn[uid]";
    $result =& $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _GETFAILED);
        return false;
    }

    // Obtain the number of items
    list($numitems) = $result->fields;

    // Put items into result array. 
    for (; !$result->EOF; $result->MoveNext()) {
        list($uid) = $result->fields;
        $items[] = array('uid' => $uid);
    }
    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Return the items
    return $items;

}

?>