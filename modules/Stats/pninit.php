<?php
// $Id: pninit.php 15750 2005-02-19 17:14:03Z markwest $ $Name$
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
// but WIthOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Xiaoyu Huang [class007]
// Purpose of file:  init for stats module
// ----------------------------------------------------------------------

/**
 * init comments module
 */
function stats_init()
{
    // Get database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Create tables
    $counter_table = $pntable['counter'];
    $counter_column = &$pntable['counter_column'];
    $sql = "CREATE TABLE $counter_table (
            $counter_column[type] varchar(80) NOT NULL default '',
            $counter_column[var] varchar(80) NOT NULL default '',
            $counter_column[count] int(11) unsigned NOT NULL default '0')";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }

    $stats_date_table = $pntable['stats_date'];
    $stats_date_column = &$pntable['stats_date_column'];
    $sql = "CREATE TABLE $stats_date_table (
            $stats_date_column[date] varchar(80) NOT NULL default '',
            $stats_date_column[hits] int(11) unsigned NOT NULL default '0')";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }

    $stats_hour_table = $pntable['stats_hour'];
    $stats_hour_column = &$pntable['stats_hour_column'];
    $sql = "CREATE TABLE $stats_hour_table (
            $stats_hour_column[hour] tinyint(2) unsigned NOT NULL default '0',
            $stats_hour_column[hits] int(11) unsigned NOT NULL default '0')";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }

    $stats_month_table = $pntable['stats_month'];
    $stats_month_column = &$pntable['stats_month_column'];
    $sql = "CREATE TABLE $stats_month_table (
            $stats_month_column[month] tinyint(2) unsigned NOT NULL default '0',
            $stats_month_column[hits] int(11) unsigned NOT NULL default '0')";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }

    $stats_week_table = $pntable['stats_week'];
    $stats_week_column = &$pntable['stats_week_column'];
    $sql = "CREATE TABLE $stats_week_table (
            $stats_week_column[weekday] tinyint(1) unsigned NOT NULL default '0',
            $stats_week_column[hits] int(11) unsigned NOT NULL default '0')";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }

    // insert needed data
    $result = $dbconn->Execute("INSERT INTO $counter_table VALUES ('total','hits',0)");
    $result = $dbconn->Execute("INSERT INTO $counter_table VALUES ('browser','Lynx',0)");
    $result = $dbconn->Execute("INSERT INTO $counter_table VALUES ('browser','MSIE',0)");
    $result = $dbconn->Execute("INSERT INTO $counter_table VALUES ('browser','Opera',0)");
    $result = $dbconn->Execute("INSERT INTO $counter_table VALUES ('browser','Konqueror',0)");
    $result = $dbconn->Execute("INSERT INTO $counter_table VALUES ('browser','Netscape',0)");
    $result = $dbconn->Execute("INSERT INTO $counter_table VALUES ('browser','Bot',0)");
    $result = $dbconn->Execute("INSERT INTO $counter_table VALUES ('browser','Other',0)");
    $result = $dbconn->Execute("INSERT INTO $counter_table VALUES ('os','Windows',0)");
    $result = $dbconn->Execute("INSERT INTO $counter_table VALUES ('os','Linux',0)");
    $result = $dbconn->Execute("INSERT INTO $counter_table VALUES ('os','Mac',0)");
    $result = $dbconn->Execute("INSERT INTO $counter_table VALUES ('os','FreeBSD',0)");
    $result = $dbconn->Execute("INSERT INTO $counter_table VALUES ('os','SunOS',0)");
    $result = $dbconn->Execute("INSERT INTO $counter_table VALUES ('os','IRIX',0)");
    $result = $dbconn->Execute("INSERT INTO $counter_table VALUES ('os','BeOS',0)");
    $result = $dbconn->Execute("INSERT INTO $counter_table VALUES ('os','OS/2',0)");
    $result = $dbconn->Execute("INSERT INTO $counter_table VALUES ('os','AIX',0)");
    $result = $dbconn->Execute("INSERT INTO $counter_table VALUES ('os','Other',0)");

    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '0', '0')");
    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '1', '0')");
    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '2', '0')");
    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '3', '0')");
    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '4', '0')");
    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '5', '0')");
    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '6', '0')");
    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '7', '0')");
    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '8', '0')");
    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '9', '0')");
    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '10', '0')");
    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '11', '0')");
    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '12', '0')");
    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '13', '0')");
    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '14', '0')");
    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '15', '0')");
    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '16', '0')");
    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '17', '0')");
    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '18', '0')");
    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '19', '0')");
    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '20', '0')");
    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '21', '0')");
    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '22', '0')");
    $dbconn->Execute("INSERT INTO $stats_hour_table VALUES ( '23', '0')");
    
    $dbconn->Execute("INSERT INTO $stats_month_table VALUES ( '1', '0')");
    $dbconn->Execute("INSERT INTO $stats_month_table VALUES ( '2', '0')");
    $dbconn->Execute("INSERT INTO $stats_month_table VALUES ( '3', '0')");
    $dbconn->Execute("INSERT INTO $stats_month_table VALUES ( '4', '0')");
    $dbconn->Execute("INSERT INTO $stats_month_table VALUES ( '5', '0')");
    $dbconn->Execute("INSERT INTO $stats_month_table VALUES ( '6', '0')");
    $dbconn->Execute("INSERT INTO $stats_month_table VALUES ( '7', '0')");
    $dbconn->Execute("INSERT INTO $stats_month_table VALUES ( '8', '0')");
    $dbconn->Execute("INSERT INTO $stats_month_table VALUES ( '9', '0')");
    $dbconn->Execute("INSERT INTO $stats_month_table VALUES ( '10', '0')");
    $dbconn->Execute("INSERT INTO $stats_month_table VALUES ( '11', '0')");
    $dbconn->Execute("INSERT INTO $stats_month_table VALUES ( '12', '0')");
    
    $dbconn->Execute("INSERT INTO $stats_week_table VALUES ( '0', '0')");
    $dbconn->Execute("INSERT INTO $stats_week_table VALUES ( '1', '0')");
    $dbconn->Execute("INSERT INTO $stats_week_table VALUES ( '2', '0')");
    $dbconn->Execute("INSERT INTO $stats_week_table VALUES ( '3', '0')");
    $dbconn->Execute("INSERT INTO $stats_week_table VALUES ( '4', '0')");
    $dbconn->Execute("INSERT INTO $stats_week_table VALUES ( '5', '0')");
    $dbconn->Execute("INSERT INTO $stats_week_table VALUES ( '6', '0')");

    // Set up config variables
    
    // Initialisation successful
    return true;
}

/**
 * upgrade
 */
function stats_upgrade($oldversion)
{
    // Upgrade successful
    return true;
}

/**
 * delete the comments module
 */
function stats_delete()
{
    // Get database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Delete tables
    $sql = "DROP TABLE $pntable[counter]";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    $sql = "DROP TABLE $pntable[stats_date]";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    $sql = "DROP TABLE $pntable[stats_hour]";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    $sql = "DROP TABLE $pntable[stats_month]";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    $sql = "DROP TABLE $pntable[stats_week]";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    // Delete module variables

    // Deletion successful
    return true;
}

?>