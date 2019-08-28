<?php
// $Id: pninit.php 15748 2005-02-19 17:07:40Z markwest $ $Name$
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
// Original Author of file: Xiaoyu Huang
// Purpose of file:  init for downloads module
// ----------------------------------------------------------------------

/**
 * init downloads module
 */
function Downloads_init()
{
    // Get database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Create tables
    $downloads_categories_table = $pntable['downloads_categories'];
    $downloads_categories_column = &$pntable['downloads_categories_column'];
    $sql = "CREATE TABLE $downloads_categories_table (
            $downloads_categories_column[cid] int(11) NOT NULL auto_increment,
            $downloads_categories_column[title] varchar(50) NOT NULL default '',
            $downloads_categories_column[cdescription] text NOT NULL,
            PRIMARY KEY  (pn_cid))";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }

    $downloads_downloads_table = $pntable['downloads_downloads'];
    $downloads_downloads_column = &$pntable['downloads_downloads_column'];
    $sql = "CREATE TABLE $downloads_downloads_table (
            $downloads_downloads_column[lid] int(11) NOT NULL auto_increment,
            $downloads_downloads_column[cid] int(11) NOT NULL default '0',
            $downloads_downloads_column[sid] int(11) NOT NULL default '0',
            $downloads_downloads_column[title] varchar(100) NOT NULL default '',
            $downloads_downloads_column[url] varchar(254) NOT NULL default '',
            $downloads_downloads_column[description] text NOT NULL,
            $downloads_downloads_column[date] datetime default NULL,
            $downloads_downloads_column[name] varchar(100) NOT NULL default '',
            $downloads_downloads_column[email] varchar(100) NOT NULL default '',
            $downloads_downloads_column[hits] int(11) NOT NULL default '0',
            $downloads_downloads_column[submitter] varchar(60) NOT NULL default '',
            $downloads_downloads_column[downloadratingsummary] double(6,4) NOT NULL default '0.0000',
            $downloads_downloads_column[totalvotes] int(11) NOT NULL default '0',
            $downloads_downloads_column[totalcomments] int(11) NOT NULL default '0',
            $downloads_downloads_column[filesize] int(11) NOT NULL default '0',
            $downloads_downloads_column[version] varchar(10) NOT NULL default '',
            $downloads_downloads_column[homepage] varchar(200) NOT NULL default '',
            PRIMARY KEY  (pn_lid))";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }
    
    $downloads_editorials_table = $pntable['downloads_editorials'];
    $downloads_editorials_column = &$pntable['downloads_editorials_column'];
    $sql = "CREATE TABLE $downloads_editorials_table (
            $downloads_editorials_column[downloadid] int(11) NOT NULL default '0',
            $downloads_editorials_column[adminid] varchar(60) NOT NULL default '',
            $downloads_editorials_column[editorialtimestamp] datetime NOT NULL default '0000-00-00 00:00:00',
            $downloads_editorials_column[editorialtext] text NOT NULL,
            $downloads_editorials_column[editorialtitle] varchar(100) NOT NULL default '',
            PRIMARY KEY  (pn_id))";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }
   
    $downloads_modrequest_table = $pntable['downloads_modrequest'];
    $downloads_modrequest_column = &$pntable['downloads_modrequest_column'];
    $sql = "CREATE TABLE $downloads_modrequest_table (
            $downloads_modrequest_column[requestid] int(11) NOT NULL auto_increment,
            $downloads_modrequest_column[lid] int(11) NOT NULL default '0',
            $downloads_modrequest_column[cid] int(11) NOT NULL default '0',
            $downloads_modrequest_column[sid] int(11) NOT NULL default '0',
            $downloads_modrequest_column[title] varchar(100) NOT NULL default '',
            $downloads_modrequest_column[url] varchar(254) NOT NULL default '',
            $downloads_modrequest_column[description] text NOT NULL,
            $downloads_modrequest_column[modifysubmitter] varchar(60) NOT NULL default '',
            $downloads_modrequest_column[brokendownload] int(3) NOT NULL default '0',
            $downloads_modrequest_column[name] varchar(100) NOT NULL default '',
            $downloads_modrequest_column[email] varchar(100) NOT NULL default '',
            $downloads_modrequest_column[filesize] int(11) NOT NULL default '0',
            $downloads_modrequest_column[version] varchar(10) NOT NULL default '',
            $downloads_modrequest_column[homepage] varchar(200) NOT NULL default '',
            PRIMARY KEY  (pn_requestid))";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }

    $downloads_newdownload_table = $pntable['downloads_newdownload'];
    $downloads_newdownload_column = &$pntable['downloads_newdownload_column'];
    $sql = "CREATE TABLE $downloads_newdownload_table (
            $downloads_newdownload_column[lid] int(11) NOT NULL auto_increment,
            $downloads_newdownload_column[cid] int(11) NOT NULL default '0',
            $downloads_newdownload_column[sid] int(11) NOT NULL default '0',
            $downloads_newdownload_column[title] varchar(100) NOT NULL default '',
            $downloads_newdownload_column[url] varchar(254) NOT NULL default '',
            $downloads_newdownload_column[description] text NOT NULL,
            $downloads_newdownload_column[name] varchar(100) NOT NULL default '',
            $downloads_newdownload_column[email] varchar(100) NOT NULL default '',
            $downloads_newdownload_column[submitter] varchar(60) NOT NULL default '',
            $downloads_newdownload_column[filesize] int(11) NOT NULL default '0',
            $downloads_newdownload_column[version] varchar(10) NOT NULL default '',
            $downloads_newdownload_column[homepage] varchar(200) NOT NULL default '',
            PRIMARY KEY  (pn_lid))";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }

    $downloads_subcategories_table = $pntable['downloads_subcategories'];
    $downloads_subcategories_column = &$pntable['downloads_subcategories_column'];
    $sql = "CREATE TABLE $downloads_subcategories_table (
            $downloads_subcategories_column[sid] int(11) NOT NULL auto_increment,
            $downloads_subcategories_column[cid] int(11) NOT NULL default '0',
            $downloads_subcategories_column[title] varchar(50) NOT NULL default '',
            PRIMARY KEY  (pn_sid))";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }

    $downloads_votedata_table = $pntable['downloads_votedata'];
    $downloads_votedata_column = &$pntable['downloads_votedata_column'];
    $sql = "CREATE TABLE $downloads_votedata_table (
            $downloads_votedata_column[ratingdbid] int(11) NOT NULL auto_increment,
            $downloads_votedata_column[ratinglid] int(11) NOT NULL default '0',
            $downloads_votedata_column[ratinguser] varchar(60) NOT NULL default '',
            $downloads_votedata_column[rating] int(11) NOT NULL default '0',
            $downloads_votedata_column[ratinghostname] varchar(60) NOT NULL default '',
            $downloads_votedata_column[ratingcomments] text NOT NULL,
            $downloads_votedata_column[ratingtimestamp] datetime NOT NULL default '0000-00-00 00:00:00',
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
function Downloads_upgrade($oldversion)
{
	// upgrade successful
	return true;
}

/**
 * delete the downloads module
 */
function Downloads_delete()
{
    // Get database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Delete tables
    $sql = "DROP TABLE $pntable[downloads_categories];";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    $sql = "DROP TABLE $pntable[downloads_downloads];";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    $sql = "DROP TABLE $pntable[downloads_editorials];";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    $sql = "DROP TABLE $pntable[downloads_modrequest];";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    $sql = "DROP TABLE $pntable[downloads_newdownload];";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    $sql = "DROP TABLE $pntable[downloads_subcategories];";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    $sql = "DROP TABLE $pntable[downloads_votedata];";
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