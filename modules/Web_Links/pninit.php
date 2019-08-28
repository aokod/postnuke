<?php
// $Id: pninit.php 15756 2005-02-19 18:27:31Z markwest $ $Name$
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
// Original Author of file: Xiaoyu Huang
// Purpose of file:  init for web links module
// ----------------------------------------------------------------------

/**
 * init web_links module
 */
function web_links_init()
{
    // Get database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Create tables
    $links_categories_table = $pntable['links_categories'];
    $links_categories_column = &$pntable['links_categories_column'];
    $sql = "CREATE TABLE $links_categories_table (
            $links_categories_column[cat_id] int(11) NOT NULL auto_increment,
            $links_categories_column[parent_id] int(11) default NULL,
            $links_categories_column[title] varchar(50) NOT NULL default '',
            $links_categories_column[cdescription] text NOT NULL,
            PRIMARY KEY  (pn_cat_id))";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }

    $links_editorials_table = $pntable['links_editorials'];
    $links_editorials_column = &$pntable['links_editorials_column'];
    $sql = "CREATE TABLE $links_editorials_table (
            $links_editorials_column[linkid] int(11) NOT NULL default '0',
            $links_editorials_column[adminid] varchar(60) NOT NULL default '',
            $links_editorials_column[editorialtimestamp] datetime NOT NULL default '0000-00-00 00:00:00',
            $links_editorials_column[editorialtext] text NOT NULL,
            $links_editorials_column[editorialtitle] varchar(100) NOT NULL default '',
            PRIMARY KEY  (pn_linkid))";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }

    $links_links_table = $pntable['links_links'];
    $links_links_column = &$pntable['links_links_column'];
    $sql = "CREATE TABLE $links_links_table (
            $links_links_column[lid] int(11) NOT NULL auto_increment,
            $links_links_column[cat_id] int(11) NOT NULL default '0',
            $links_links_column[title] varchar(100) NOT NULL default '',
            $links_links_column[url] varchar(254) NOT NULL default '',
            $links_links_column[description] text NOT NULL,
            $links_links_column[date] datetime default NULL,
            $links_links_column[name] varchar(100) NOT NULL default '',
            $links_links_column[email] varchar(100) NOT NULL default '',
            $links_links_column[hits] int(11) NOT NULL default '0',
            $links_links_column[submitter] varchar(60) NOT NULL default '',
            $links_links_column[linkratingsummary] double(6,4) NOT NULL default '0.0000',
            $links_links_column[totalvotes] int(11) NOT NULL default '0',
            $links_links_column[totalcomments] int(11) NOT NULL default '0',
            PRIMARY KEY  (pn_lid))";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }

    $links_modrequest_table = $pntable['links_modrequest'];
    $links_modrequest_column = &$pntable['links_modrequest_column'];
    $sql = "CREATE TABLE $links_modrequest_table (
            $links_modrequest_column[requestid] int(11) NOT NULL auto_increment,
            $links_modrequest_column[lid] int(11) NOT NULL default '0',
            $links_modrequest_column[cat_id] int(11) NOT NULL default '0',
            $links_modrequest_column[sid] int(11) NOT NULL default '0',
            $links_modrequest_column[title] varchar(100) NOT NULL default '',
            $links_modrequest_column[url] varchar(254) NOT NULL default '',
            $links_modrequest_column[description] text NOT NULL,
            $links_modrequest_column[modifysubmitter] varchar(60) NOT NULL default '',
            $links_modrequest_column[brokenlink] tinyint(3) NOT NULL default '0',
            PRIMARY KEY  (pn_requestid))";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }

    $links_newlink_table = $pntable['links_newlink'];
    $links_newlink_column = &$pntable['links_newlink_column'];
    $sql = "CREATE TABLE $links_newlink_table (
            $links_newlink_column[lid] int(11) NOT NULL auto_increment,
            $links_newlink_column[cat_id] int(11) NOT NULL default '0',
            $links_newlink_column[title] varchar(100) NOT NULL default '',
            $links_newlink_column[url] varchar(254) NOT NULL default '',
            $links_newlink_column[description] text NOT NULL,
            $links_newlink_column[name] varchar(100) NOT NULL default '',
            $links_newlink_column[email] varchar(100) NOT NULL default '',
            $links_newlink_column[submitter] varchar(60) NOT NULL default '',
            PRIMARY KEY  (pn_lid))";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }

    $links_votedata_table = $pntable['links_votedata'];
    $links_votedata_column = &$pntable['links_votedata_column'];
    $sql = "CREATE TABLE $links_votedata_table (
            $links_votedata_column[ratingdbid] int(11) NOT NULL auto_increment,
            $links_votedata_column[ratinglid] int(11) NOT NULL default '0',
            $links_votedata_column[ratinguser] varchar(60) NOT NULL default '',
            $links_votedata_column[rating] int(11) NOT NULL default '0',
            $links_votedata_column[ratinghostname] varchar(60) NOT NULL default '',
            $links_votedata_column[ratingcomments] text NOT NULL,
            $links_votedata_column[ratingtimestamp] datetime NOT NULL default '0000-00-00 00:00:00',
            PRIMARY KEY  (pn_id))";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }
    
    // Initialisation successful
    return true;
    
}

/**
 * upgrade
 */
function web_links_upgrade($oldversion)
{
    // Upgrade successful
    return true;
}

/**
 * delete the web_links module
 */
function web_links_delete()
{
    // Get database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Delete tables
    $sql = "DROP TABLE $pntable[links_categories];";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    $sql = "DROP TABLE $pntable[links_editorials];";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    $sql = "DROP TABLE $pntable[links_links];";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    $sql = "DROP TABLE $pntable[links_modrequest];";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    $sql = "DROP TABLE $pntable[links_newlink];";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    $sql = "DROP TABLE $pntable[links_votedata];";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }
  
    // Deletion successful
    return true;
}


?>