<?php
// $Id: pnadminapi.php 15985 2005-03-15 09:39:04Z markwest $
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
// Original Author of file: Mark West
// Purpose of file:  Ratings admin API
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Utility_Modules
 * @subpackage Ratings
 * @license http://www.gnu.org/copyleft/gpl.html
*/
 
 
/**
 * clean up ratings for a removed module
 * 
 * @param    $args['extrainfo']   array extrainfo array
 * @return   array extrainfo array
 */
function Ratings_adminapi_removehook($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // optional arguments
    if (!isset($extrainfo)) {
        $extrainfo = array();
    }

    // When called via hooks, the module name may be empty, so we get it from
    // the current module
    if (empty($extrainfo['module'])) {
        $modname = pnModGetName();
    } else {
        $modname = $extrainfo['module'];
    }

    // Database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $ratingstable = $pntable['ratings'];
    $ratingscolumn = &$pntable['ratings_column'];

    // Get items
    $sql = "DELETE FROM $ratingstable
            WHERE $ratingscolumn[module] = '" . pnVarPrepForStore($modname) . "'";
    $result =& $dbconn->Execute($sql);

	return $extrainfo;
}

/**
 * clean up ratings for a removed item
 * 
 * @param    $args['extrainfo']   array extrainfo array
 * @return   array extrainfo array
 */
function Ratings_adminapi_deletehook($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // optional arguments
    if (!isset($extrainfo)) {
        $extrainfo = array();
    }

	// set the object id
    $objectid = $args['objectid'];

    // When called via hooks, the module name may be empty, so we get it from
    // the current module
    if (empty($extrainfo['module'])) {
        $modname = pnModGetName();
    } else {
        $modname = $extrainfo['module'];
    }

    // Database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $ratingstable = $pntable['ratings'];
    $ratingscolumn = &$pntable['ratings_column'];

	// prepare the item data
	list ($modname, $objectid) = pnVarPrepForStore($modname, $objectid);

    // Get items
    $sql = "DELETE FROM $ratingstable
			WHERE $ratingscolumn[module] = '$modname' AND $ratingscolumn[itemid] = '$objectid'";
    $result =& $dbconn->Execute($sql);

	return $extrainfo;
} 

?>