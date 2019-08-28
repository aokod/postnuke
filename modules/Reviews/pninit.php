<?php
// $Id: pninit.php 16321 2005-06-08 09:32:49Z chestnut $ $Name$
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
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Xiaoyu Huang [class007]
// Purpose of file:  init for reviews module
// ----------------------------------------------------------------------

/**
 * init reviews module
 */
function Reviews_init()
{
    // Get database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Create tables
    $reviews_table = $pntable['reviews'];
    $reviews_column = &$pntable['reviews_column'];
    $sql = "CREATE TABLE $reviews_table (
            $reviews_column[id] int(11) NOT NULL auto_increment,
            $reviews_column[date] datetime NOT NULL default '0000-00-00 00:00:00',
            $reviews_column[title] varchar(150) NOT NULL default '',
            $reviews_column[text] text NOT NULL,
            $reviews_column[reviewer] varchar(20) default NULL,
            $reviews_column[email] varchar(60) default NULL,
            $reviews_column[score] int(11) NOT NULL default '0',
            $reviews_column[cover] varchar(100) NOT NULL default '',
            $reviews_column[url] varchar(254) NOT NULL default '',
            $reviews_column[url_title] varchar(150) NOT NULL default '',
            $reviews_column[hits] int(11) NOT NULL default '0',
            $reviews_column[rlanguage] varchar(30) NOT NULL default '',
            PRIMARY KEY  (pn_id))";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }

    $reviews_add_table = $pntable['reviews_add'];
    $reviews_add_column = &$pntable['reviews_add_column'];
    $sql = "CREATE TABLE $reviews_add_table (
            $reviews_add_column[id] int(11) NOT NULL auto_increment,
            $reviews_add_column[date] datetime default NULL,
            $reviews_add_column[title] varchar(150) NOT NULL default '',
            $reviews_add_column[text] text NOT NULL,
            $reviews_add_column[reviewer] varchar(20) NOT NULL default '',
            $reviews_add_column[email] varchar(60) default NULL,
            $reviews_add_column[score] int(11) NOT NULL default '0',
            $reviews_add_column[url] varchar(254) NOT NULL default '',
            $reviews_add_column[url_title] varchar(150) NOT NULL default '',
            $reviews_add_column[rlanguage] varchar(30) NOT NULL default '',
            PRIMARY KEY  (pn_id))";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }

    $reviews_comments_table = $pntable['reviews_comments'];
    $reviews_comments_column = &$pntable['reviews_comments_column'];
    $sql = "CREATE TABLE $reviews_comments_table (
            $reviews_comments_column[cid] int(11) NOT NULL auto_increment,
            $reviews_comments_column[rid] int(11) NOT NULL default '0',
            $reviews_comments_column[userid] varchar(25) NOT NULL default '',
            $reviews_comments_column[date] datetime default NULL,
            $reviews_comments_column[comments] text,
            $reviews_comments_column[score] int(11) NOT NULL default '0',
            PRIMARY KEY  (pn_cid))";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }

    $reviews_main_table = $pntable['reviews_main'];
    $reviews_main_column = &$pntable['reviews_main_column'];
    $sql = "CREATE TABLE $reviews_main_table  (
            $reviews_main_column[title] varchar(100) default NULL,
            $reviews_main_column[description] text)";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }

    // Insert needed data
    $result =& $dbconn->Execute("INSERT INTO $reviews_main_table VALUES ( '"._REVIEWSMAINTITLE."', '"._REVIEWSMAINDESC."')");
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }

    // Set up config variables

    // Initialisation successful
    return true;
}

/**
 * upgrade
 */
function Reviews_upgrade($oldversion)
{
  return true;
}

/**
 * delete the reviews module
 */
function Reviews_delete()
{
    // Get database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Delete tables
    $sql = "DROP TABLE $pntable[reviews]";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    $sql = "DROP TABLE $pntable[reviews_add]";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    $sql = "DROP TABLE $pntable[reviews_comments]";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    $sql = "DROP TABLE $pntable[reviews_main]";
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
