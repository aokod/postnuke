<?php
// $Id: pninit.php 16220 2005-05-10 15:10:07Z landseer $ $Name$
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
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
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Xiaoyu Huang [class007]
// Purpose of file:  init for comments module
// ----------------------------------------------------------------------

/**
 * init comments module
 */
function comments_init()
{
    // Get database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Create tables
    $comments_table = $pntable['comments'];
    $comments_column = &$pntable['comments_column'];
    $sql = "CREATE TABLE $comments_table (
            $comments_column[tid] int(11) NOT NULL auto_increment,
            $comments_column[pid] int(11) default '0',
            $comments_column[sid] int(11) default '0',
            $comments_column[date] datetime default NULL,
            $comments_column[name] varchar(60) NOT NULL default '',
            $comments_column[email] varchar(60) default NULL,
            $comments_column[url] varchar(254) default NULL,
            $comments_column[host_name] varchar(60) default NULL,
            $comments_column[subject] varchar(85) NOT NULL default '',
            $comments_column[comment] text NOT NULL,
            $comments_column[score] tinyint(4) NOT NULL default '0',
            $comments_column[reason] tinyint(4) NOT NULL default '0',
            PRIMARY KEY  (pn_tid))";
    $dbconn->Execute($sql);

    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }

    // Set up config variables
    pnConfigSetVar('anonpost', 1);
    pnConfigSetVar('commentlimit', 4096);
    pnConfigSetVar('moderate', 1);

    // Initialisation successful
    return true;
}

/**
 * upgrade
 */
function comments_upgrade($oldversion)
{
	// upgrade successful
	return true;
}

/**
 * delete the comments module
 */
function comments_delete()
{
    // Get database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Delete tables
    $sql = "DROP TABLE $pntable[comments]";
    $dbconn->Execute($sql);

    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    // Delete module variables
    pnConfigDelVar('anonpost');
    pnConfigDelVar('commentlimit');
    pnConfigDelVar('moderate');

    // Deletion successful
    return true;
}

?>