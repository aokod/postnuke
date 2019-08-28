<?php
// $Id: pninit.php 15749 2005-02-19 17:10:42Z markwest $ $Name$
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
// Purpose of file:  init for faq module
// ----------------------------------------------------------------------

/**
 * init faq module
 */
function FAQ_init()
{
    // Get database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Create tables
    $faqanswer_table = $pntable['faqanswer'];
    $faqanswer_column = &$pntable['faqanswer_column'];
    $sql = "CREATE TABLE $faqanswer_table (
            $faqanswer_column[id] int(6) NOT NULL auto_increment,
            $faqanswer_column[id_cat] int(6) default NULL,
            $faqanswer_column[question] text default NULL,
            $faqanswer_column[answer] text,
            $faqanswer_column[submittedby] varchar(250) NOT NULL default '',
            PRIMARY KEY  (pn_id))";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }

    $faqcategories_table = $pntable['faqcategories'];
    $faqcategories_column = &$pntable['faqcategories_column'];
    $sql = "CREATE TABLE $faqcategories_table (
            $faqcategories_column[id_cat] int(6) NOT NULL auto_increment,
            $faqcategories_column[categories] varchar(255) default NULL,
            $faqcategories_column[language] varchar(30) NOT NULL default '',
            $faqcategories_column[parent_id] int(6) NOT NULL default '0',
            PRIMARY KEY  (pn_id_cat))";
    $dbconn->Execute($sql);
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
function FAQ_upgrade($oldversion)
{
	// upgrade successful
	return true;
}

/**
 * delete the faq module
 */
function FAQ_delete()
{
    // Get database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Delete tables
    $sql = "DROP TABLE $pntable[faqanswer]";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    $sql = "DROP TABLE $pntable[faqcategories]";
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