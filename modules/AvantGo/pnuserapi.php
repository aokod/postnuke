<?php
// $Id: pnuserapi.php 16618 2005-08-09 07:57:45Z markwest $
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
// Purpose of file:  AvantGo user API
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Content_Modules
 * @subpackage AvantGo
 * @license http://www.gnu.org/copyleft/gpl.html
*/

/**
 * Get all articles from the news module
 * @author Mark West 
 * @link http://www.markwest.me.uk
 * @param $args['startnum'] the starting article numbner
 * @param $args['numitems'] the number of articles to return
 * @return array array of articles
 * @todo remove once news module is API compliant
 */
function AvantGo_userapi_getall($args)
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
    if (!pnSecAuthAction(0, 'AvantGo::', "::", ACCESS_READ)) {
        return $items;
    }

    // Check if the news module is available
    if (!pnModAvailable('News')) {
        return $items;
    }

    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn() we
    // currently just want the first item, which is the official database
    // handle.  For pnDBGetTables() we want to keep the entire tables array
    // together for easy reference later on
	pnModDBInfoLoad('News');
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // It's good practice to name the table and column definitions you are
    // getting - $table and $column don't cut it in more complex modules
    $storiestable = $pntable['stories'];
    $storiescolumn = &$pntable['stories_column'];

    // Get items - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the SelectLimit() command allows for simpler debug
    // operation if it is ever needed
    $sql = "SELECT $storiescolumn[aid],
                   $storiescolumn[sid],
                   $storiescolumn[title],
                   $storiescolumn[time],
                   $storiescolumn[catid],
                   $storiescolumn[topic]
            FROM $storiestable
            ORDER BY $storiescolumn[sid] DESC";
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
        list($aid, $sid, $title, $time, $catid, $topic) = $result->fields;
        if (pnSecAuthAction(0, 'Stories::Story', "$aid:$catid:$sid", ACCESS_READ)) {
            $items[] = array('aid' => $aid,
                             'sid' => $sid,
                             'title' => $title,
                             'time' => $time,
                             'catid' => $catid,
                             'topic' => $topic);
        }
    }

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Return the items
    return $items;
}

/**
 * Get category title
 * @author Mark West 
 * @link http://www.markwest.me.uk
 * @param 'catid' the category if of the article
 * @return string category name
 * @todo remove once news module is API compliant
 */
function AvantGo_userapi_getcattitle($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    if (!isset($catid)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $items = array();

    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'AvantGo::', "::", ACCESS_READ)) {
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
    $cattable = $pntable['stories_cat'];
    $catcolumn = &$pntable['stories_cat_column'];

    if ($catid == 0) {
	    // Default category
		$cattitle = ''._AVANTGOARTICLES.'';
	} else {
        // Get items - the formatting here is not mandatory, but it does make the
        // SQL statement relatively easy to read.  Also, separating out the sql
        // statement from the SelectLimit() command allows for simpler debug
        // operation if it is ever needed
        $sql = "SELECT $catcolumn[title]
                FROM $cattable
                WHERE $catcolumn[catid] = '" . (int)pnVarPrepForStore($catid) . "'";
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
		list($cattitle) = $result->fields;
        // All successful database queries produce a result set, and that result
        // set should be closed when it has been finished with
        $result->Close();

    }

    // add default category title for various pnSecAuthAction check
    // since _ARTICLES is a language define it's not that good idea, 
    // but almost all security checks are based on cattitle
    // 2002/11/17 larsneo
    if ($cattitle == '') { 
		$cattitle = _ARTICLES;
	}

    // Return the items
    return $cattitle;
}


/**
 * Get category title
 * @author Mark West 
 * @link http://www.markwest.me.uk
 * @param 'catid' the category if of the article
 * @return string category name
 * @todo remove once news module is API compliant
 */
function AvantGo_userapi_gettopictitle($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    if (!isset($topicid)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $items = array();

    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'AvantGo::', "::", ACCESS_READ)) {
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
    $table = $pntable['topics'];
	$column = &$pntable['topics_column'];

    // Get items - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the SelectLimit() command allows for simpler debug
    // operation if it is ever needed
    $sql = "SELECT $column[topicname]
            FROM $table
            WHERE $column[topicid] = '" . (int)pnVarPrepForStore($topicid) . "'";
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
	list($topictitle) = $result->fields;
    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Return the items
    return $topictitle;
}

?>