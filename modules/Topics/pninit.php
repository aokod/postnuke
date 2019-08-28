<?php
// $Id: pninit.php 18046 2006-03-04 17:54:00Z drak $ $Name$
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2003 by the PostNuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WIthOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Xiaoyu Huang [class007]
// Purpose of file:  init for topics module
// ----------------------------------------------------------------------

/**
 * init topics module
 */
function Topics_init()
{
    // Get database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Create tables
    $topics_table = $pntable['topics'];
    $topics_column = &$pntable['topics_column'];
    $sql = "CREATE TABLE $topics_table  (
            $topics_column[topicid] tinyint(4) NOT NULL auto_increment,
            $topics_column[topicname] varchar(255) default NULL,
            $topics_column[topicimage] varchar(255) default NULL,
            $topics_column[topictext] varchar(255) default NULL,
            $topics_column[counter] int(11) NOT NULL default '0',
            PRIMARY KEY  (pn_topicid))";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED);
        return false;
    }

    // create the default values
    $sql = "INSERT INTO $topics_table VALUES (2, 'Linux', 'linux.gif', 'Linux', 0);";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED);
        return false;
    }
	$sql = "INSERT INTO $topics_table VALUES (1, 'PostNuke', 'PostNuke.gif', 'PostNuke', 0)";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED);
        return false;
    }

    // Set up config variables
    pnConfigSetVar('topicsinrow', 5);

    // Initialisation successful
    return true;
}

/**
 * upgrade
 */
function Topics_upgrade($oldversion)
{
	return true;
}

/**
 * delete the topics module
 */
function Topics_delete()
{
    // Get database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Delete tables
    $sql = "DROP TABLE $pntable[topics]";
    $dbconn->Execute($sql);

    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _DELETETABLEFAILED);
        return false;
    }

    // Delete module variables
    pnConfigDelVar('topicsinrow');

    // Deletion successful
    return true;
}

?>