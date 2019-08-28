<?php
// File: $Id: pninit.php 15752 2005-02-19 17:19:54Z markwest $
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

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Mark West
// Purpose of file: init script for Referers module
// ----------------------------------------------------------------------

function Referers_init()
{
	// Get database information
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();

    // Create tables
    $referer_table = $pntable['referer'];
    $referer_column = &$pntable['referer_column'];
    $sql = "CREATE TABLE $referer_table (
            $referer_column[rid] int(11) NOT NULL auto_increment,
            $referer_column[url] varchar(254) NOT NULL default '',
            $referer_column[frequency] int(15) default NULL,
            PRIMARY KEY  (pn_rid))";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }

    // add the index
	$sql = "ALTER TABLE $pntable[referer] ADD INDEX ( `pn_url` ) ";
	$result =& $dbconn->Execute($sql);
	// Check for an error with the database code, and if so set an appropriate
	// error message and return
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }

    // Set up config variables
    pnConfigSetVar('httpref', 0);
    pnConfigSetVar('httprefmax', 1000);
    
    // Initialisation successful
    return true;

}

function Referers_upgrade($oldversion)
{
    // Upgrade dependent on old version number
    switch($oldversion) {    	
    	case 1.2:
			$dbconn =& pnDBGetConn(true);
			$pntable =& pnDBGetTables();
			$sql = "ALTER TABLE $pntable[referer] ADD INDEX ( `pn_url` ) ";
			$result =& $dbconn->Execute($sql);
			// Check for an error with the database code, and if so set an appropriate
			// error message and return
			if ($dbconn->ErrorNo() != 0) {
				pnSessionSetVar('errormsg', $dbconn->ErrorMsg());
				return false;
			}

		break;
    }
    return true;
}

function Referers_delete()
{
    // Get database information
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();

    // Delete tables
    $sql = "DROP TABLE $pntable[referer]";
    $dbconn->Execute($sql);

    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    // Delete module variables
    pnConfigDelVar('httpref');
    pnConfigDelVar('httprefmax');

    // Deletion successful
    return true;

}

?>