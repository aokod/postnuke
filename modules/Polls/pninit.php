<?php
// $Id: pninit.php 15864 2005-02-26 13:46:33Z landseer $ $Name$
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
// Purpose of file:  Initialisation for Polls module
// ----------------------------------------------------------------------

/**
 * @package Postnuke_Resource_Pack_Modules
 * @subpackage Postnuke_Polls
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * init polls module
 * @author Xiaoyu Huang
 * @version $Revision: 15864 $
 * @return bool true if successful, false otherwise
 */
function Polls_init()
{
    // Get database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Create tables
    $poll_check_table = $pntable['poll_check'];
    $poll_check_column = &$pntable['poll_check_column'];
    $sql = "CREATE TABLE $poll_check_table (
            $poll_check_column[ip] varchar(20) NOT NULL default '',
            $poll_check_column[time] varchar(14) NOT NULL default '')";
    $dbconn->Execute($sql);

    $poll_data_table = $pntable['poll_data'];
    $poll_data_column = &$pntable['poll_data_column'];
    $sql = "CREATE TABLE $poll_data_table (
            $poll_data_column[pollid] int(11) NOT NULL default '0',
            $poll_data_column[optiontext] char(50) NOT NULL default '',
            $poll_data_column[optioncount] int(11) NOT NULL default '0',
            $poll_data_column[voteid] int(11) NOT NULL default '0')";
    $dbconn->Execute($sql);

    $poll_desc_table = $pntable['poll_desc'];
    $poll_desc_column = &$pntable['poll_desc_column'];
    $sql = "CREATE TABLE $poll_desc_table (
            $poll_desc_column[pollid] int(11) NOT NULL auto_increment,
            $poll_desc_column[polltitle] varchar(100) NOT NULL default '',
            $poll_desc_column[timestamp] int(11) NOT NULL default '0',
            $poll_desc_column[voters] mediumint(9) NOT NULL default '0',
            $poll_desc_column[language] varchar(30) NOT NULL default '',
            PRIMARY KEY  (pn_pollid))";
    $dbconn->Execute($sql);

    $pollcomments_table = $pntable['pollcomments'];
    $pollcomments_column = &$pntable['pollcomments_column'];
    $sql = "CREATE TABLE $pollcomments_table (
            $pollcomments_column[tid] int(11) NOT NULL auto_increment,
            $pollcomments_column[pid] int(11) default '0',
            $pollcomments_column[pollid] int(11) default '0',
            $pollcomments_column[date] datetime default NULL,
            $pollcomments_column[name] varchar(60) NOT NULL default '',
            $pollcomments_column[email] varchar(60) default NULL,
            $pollcomments_column[url] varchar(254) default NULL,
            $pollcomments_column[host_name] varchar(60) default NULL,
            $pollcomments_column[subject] varchar(60) NOT NULL default '',
            $pollcomments_column[comment] text NOT NULL,
            $pollcomments_column[score] tinyint(4) NOT NULL default '0',
            $pollcomments_column[reason] tinyint(4) NOT NULL default '0',
            PRIMARY KEY  (pn_tid))";
    $dbconn->Execute($sql);
    
    // Insert needed data
    $result = $dbconn->Execute("INSERT INTO $poll_data_table VALUES ( '2', '', '0', '12')");
    $result = $dbconn->Execute("INSERT INTO $poll_data_table VALUES ( '2', '', '0', '11')");
    $result = $dbconn->Execute("INSERT INTO $poll_data_table VALUES ( '2', '', '0', '10')");
    $result = $dbconn->Execute("INSERT INTO $poll_data_table VALUES ( '2', '', '0', '9')");
    $result = $dbconn->Execute("INSERT INTO $poll_data_table VALUES ( '2', '', '0', '8')");
    $result = $dbconn->Execute("INSERT INTO $poll_data_table VALUES ( '2', '', '0', '7')");
    $result = $dbconn->Execute("INSERT INTO $poll_data_table VALUES ( '2', '', '0', '6')");
    $result = $dbconn->Execute("INSERT INTO $poll_data_table VALUES ( '2', '', '0', '5')");
    $result = $dbconn->Execute("INSERT INTO $poll_data_table VALUES ( '2', '', '0', '4')");
    $result = $dbconn->Execute("INSERT INTO $poll_data_table VALUES ( '2', '"._POLLDATATEXT1."', '0', '3')");
    $result = $dbconn->Execute("INSERT INTO $poll_data_table VALUES ( '2', '"._POLLDATATEXT2."', '0', '2')");
    $result = $dbconn->Execute("INSERT INTO $poll_data_table VALUES ( '2', '"._POLLDATATEXT3."', '0', '1')");
	$result = $dbconn->Execute("INSERT INTO $poll_desc_table VALUES ( '2', '"._POLLDESCTEXT."', '995385085', '0', '')");

	// Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }
    
    // Set up module variables
    pnConfigSetVar('pollcomm',1);

    // Initialisation successful
    return true;
    
}

/**
 * upgrade
 * @author Xiaoyu Huang
 * @version $Revision: 15864 $
 * @return bool true if successful, false otherwise
 */
function Polls_upgrade($oldversion)
{
    // Upgrade successful
    return true;
}

/**
 * delete the polls module
 * @author Xiaoyu Huang
 * @version $Revision: 15864 $
 * @return bool true if successful, false otherwise
 */
function Polls_delete()
{
    // Get database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Delete tables
    $sql = "DROP TABLE $pntable[poll_check];";
    $dbconn->Execute($sql);

    $sql = "DROP TABLE $pntable[poll_data];";
    $dbconn->Execute($sql);

    $sql = "DROP TABLE $pntable[poll_desc];";
    $dbconn->Execute($sql);

    $sql = "DROP TABLE $pntable[pollcomments];";
    $dbconn->Execute($sql);

    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    // Delete module variables
    pnConfigDelVar('pollcomm');

    // Deletion successful
    return true;
}

?>