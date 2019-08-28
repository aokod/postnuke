<?php
// $Id: pnuserapi.php 16561 2005-07-29 14:41:28Z markwest $
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
// Original Author of file: Jim McDonald
// Purpose of file:  Ratings user API
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Utility_Modules
 * @subpackage Ratings
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * Get a rating for a specific item
 * @author Jim McDonald
 * @param $args['modname'] name of the module this rating is for
 * @param $args['objectid'] ID of the item this rating is for
 * @param $args['ratingtype'] type of rating (optional)
 * @return int rating the corresponding rating, or void if no rating exists
 */
function ratings_userapi_get($args)
{
    // Get arguments from argument array
    extract($args);

    // Argument check
    if ((!isset($modname)) ||
        (!isset($objectid))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    if (!isset($ratingtype) || $ratingtype = 'default') {
        $ratingtype = pnModGetVar('ratings', 'defaultstyle');
    }

    // Security check
    if (!pnSecAuthAction(0, 'Ratings::', "$modname:$ratingtype:$objectid", ACCESS_READ)) {
        return false;
    }

    // Database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $ratingstable = $pntable['ratings'];
    $ratingscolumn = &$pntable['ratings_column'];

    // Get items
    $sql = "SELECT $ratingscolumn[rating]
            FROM $ratingstable
            WHERE $ratingscolumn[module] = '" . pnVarPrepForStore($modname) . "'
              AND $ratingscolumn[itemid] = '" . pnVarPrepForStore($objectid) . "'
              AND $ratingscolumn[ratingtype] = '" . pnVarPrepForStore($ratingtype) . "'";
    $result =& $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', 'SQL Error');
        return false;
    }

    $rating = $result->fields[0];
    $result->close();

    return $rating;
}


/**
 * get all ratings for a given module
 * @author Mark West
 * @param $args['modname'] name of the module this rating is for
 * @param $args['ratingtype'] type of rating (optional)
 * @param $args['sortby'] column to sort by (optional)
 * @param $args['numitems'] number of items to return (optional)
 * @return mixed array of ratings or false
 */
function ratings_userapi_getall($args)
{
    // Get arguments from argument array
    extract($args);

    // Argument check
    if ((!isset($modname))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    if (!isset($ratingtype) || $ratingtype = 'default') {
        $ratingtype = pnModGetVar('ratings', 'defaultstyle');
    }
	
    $items = array();
	
    // Security check
    if (!pnSecAuthAction(0, 'Ratings::', "$modname:$ratingtype:", ACCESS_READ)) {
        return $items;
    }

    // Database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $ratingstable = $pntable['ratings'];
    $ratingscolumn = &$pntable['ratings_column'];

	if (isset($sortby)) {
	    $sortstring = " ORDER BY $ratingscolumn[$sortby]";
	} else {
	    $sortstring = '';
	}

    // Get items
    $sql = "SELECT $ratingscolumn[rating],
	               $ratingscolumn[itemid],
				   $ratingscolumn[numratings]
            FROM $ratingstable
            WHERE $ratingscolumn[module] = '" . pnVarPrepForStore($modname) . "'
              AND $ratingscolumn[ratingtype] = '" . pnVarPrepForStore($ratingtype) . "'" . $sortstring;

	if (!isset($numitems)) {
        $result =& $dbconn->Execute($sql);
	} else {
        $result = $dbconn->SelectLimit($sql, $numitems);
	}

    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', 'SQL Error');
        return false;
    }

    // Put items into result array.  Note that each item is checked
    // individually to ensure that the user is allowed access to it before it
    // is added to the results array
    for (; !$result->EOF; $result->MoveNext()) {
        list($rating, $itemid, $numratings) = $result->fields;
        if (pnSecAuthAction(0, "Ratings::", "$modname:$ratingtype:$itemid", ACCESS_READ)) {
            $items[] = array('rating' => $rating,
			                 'numratings' => $numratings,
                             'objectid' => $itemid);
        }
    }

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Return the items
    return $items;

}
/**
 * Rate an item
 * @author Jim McDonald
 * @param $args['modname'] module name of the item to rate
 * @param $args['id'] ID of the item to rate
 * @param $args['ratingtype'] type of rating (optional)
 * @param $args['rating'] actual rating
 * @return int the new rating for this item
 */
function ratings_userapi_rate($args)
{
    // Get arguments from argument array
    extract($args);

    // Argument check
    if ((!isset($modname)) ||
        (!isset($objectid)) ||
        (!isset($rating))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    if (!isset($ratingtype) || $ratingtype = 'default') {
        $ratingtype = pnModGetVar('ratings', 'defaultstyle');
    }

    // Security check
    if (!pnSecAuthAction(0, 'Ratings::', "$modname:$ratingtype:$objectid", ACCESS_READ)) {
        return false;
    }

    // Database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $ratingstable = $pntable['ratings'];
    $ratingscolumn = &$pntable['ratings_column'];
    $ratingslogtable = $pntable['ratingslog'];
    $ratingslogcolumn = &$pntable['ratingslog_column'];

    // Multiple rate check
    $seclevel = pnModGetVar('Ratings', 'seclevel');
    if ($seclevel == 'high') {
		// get the users user id
        $logid = pnUserGetVar('uid');
		// get the users ip        
        $logip = pnServerGetVar('REMOTE_ADDR');

        // Check against table to see if user has already voted
        $sql = "SELECT $ratingslogcolumn[id]
                FROM $ratingslogtable
                WHERE ( $ratingslogcolumn[id] = '" . pnVarPrepForStore($logid) . "'
                OR $ratingslogcolumn[id] = '" . pnVarPrepForStore($logip) . "' )
                AND $ratingslogcolumn[ratingid] = '" . $modname . $objectid . $ratingtype . "'";
        $result =& $dbconn->Execute($sql);
        if (!$result->EOF) {
            return false;
        }
        $result->Close();
    } elseif ($seclevel == 'medium') {
        // Check against session to see if user has voted recently
        if (pnSessionGetVar("Rated$modname$ratingtype$objectid")) {
            return false;
        }
    } // No check for low

	// check our input
	if ($rating < 0 || $rating > 100) {
		pnSessionSetVar('errormsg', _MODARGSERROR);
		return false;
	}
    // Get current information on rating
    $sql = "SELECT $ratingscolumn[rid],
                   $ratingscolumn[rating],
                   $ratingscolumn[numratings]
            FROM $ratingstable
            WHERE $ratingscolumn[module] = '" . pnVarPrepForStore($modname) . "'
              AND $ratingscolumn[itemid] = '" . pnVarPrepForStore($objectid) . "'
              AND $ratingscolumn[ratingtype] = '" . pnVarPrepForStore($ratingtype) . "'";
    $result =& $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', 'SQL Error');
        return false;
    }

    if (!$result->EOF) {
        // Update current rating
        list($rid, $currating, $numratings) = $result->fields;
        $result->close();

        // Calculate new rating
        $newnumratings = $numratings + 1;
        $newrating = (int)((($currating*$numratings) + $rating)/$newnumratings);

        // Insert new rating
        $sql = "UPDATE $ratingstable
                SET $ratingscolumn[rating] = " . pnVarPrepForStore($newrating) . ",
                    $ratingscolumn[numratings] = $newnumratings
                WHERE $ratingscolumn[rid] = $rid";
        $dbconn->Execute($sql);

        if ($dbconn->ErrorNo() != 0) {
            pnSessionSetVar('errormsg', 'SQL Error');
            return false;
        }
    } else {
        $result->close();

        // Get a new ratings ID
        $rid = $dbconn->GenId($ratingstable);
        // Create new rating
        $sql = "INSERT INTO $ratingstable($ratingscolumn[rid],
                                          $ratingscolumn[module],
                                          $ratingscolumn[itemid],
                                          $ratingscolumn[ratingtype],
                                          $ratingscolumn[rating],
                                          $ratingscolumn[numratings])
                VALUES ($rid,
                        '" . pnVarPrepForStore($modname) . "',
                        '" . pnVarPrepForStore($objectid) . "',
                        '" . pnVarPrepForStore($ratingtype) . "',
                        " . pnVarPrepForStore($rating) . ",
                        1)";

        $dbconn->Execute($sql);

        if ($dbconn->ErrorNo() != 0) {
            pnSessionSetVar('errormsg', 'SQL Error');
            return false;
        }

        $newrating = $rating;
    }

    // Set note that user has rated this item if required
    if ($seclevel == 'high') {
        $ratingslogtable = $pntable['ratingslog'];
        $ratingslogcolumn = &$pntable['ratingslog_column'];
        if (pnUserLoggedIn()) {
	        $logid = pnUserGetVar('uid');
        } else {
			$logid = pnServerGetVar('REMOTE_ADDR');
		}
		$sql = "INSERT INTO $ratingslogtable
				  ($ratingslogcolumn[id],
				   $ratingslogcolumn[ratingid],
				   $ratingslogcolumn[rating])
				VALUES ('" . pnVarPrepForStore($logid) . "',
						'" . $modname . $objectid . $ratingtype . "',
						'" . pnVarPrepForStore($rating) . "')";
		$dbconn->Execute($sql);
    } elseif ($seclevel == 'medium') {
        pnSessionSetVar("Rated$modname$ratingtype$objectid", true);
    }
    return $newrating;
}

?>