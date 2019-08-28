<?php
// $Id: pninit.php 15757 2005-02-19 18:32:20Z markwest $ $Name$
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
// Purpose of file:  init for sections module
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Content_Modules
 * @subpackage Sections
 * @license http://www.gnu.org/copyleft/gpl.html
*/

/**
 * init Sections module
 */
function Sections_init()
{
    // Get database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Create tables
    $seccont_table = $pntable['seccont'];
    $seccont_column = &$pntable['seccont_column'];
    $sql = "CREATE TABLE $seccont_table (
            $seccont_column[artid] int(11) NOT NULL auto_increment,
            $seccont_column[secid] int(11) NOT NULL default '0',
            $seccont_column[title] text NOT NULL,
            $seccont_column[content] text NOT NULL,
            $seccont_column[counter] int(11) NOT NULL default '0',
            $seccont_column[language] varchar(30) NOT NULL default '',
            PRIMARY KEY  (pn_artid))";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed initialisation attempt
        return false;
    }
 
    $sections_table = $pntable['sections'];
    $sections_column = &$pntable['sections_column'];
    $sql = "CREATE TABLE $sections_table (
            $sections_column[secid] int(11) NOT NULL auto_increment,
            $sections_column[secname] varchar(40) NOT NULL default '',
            $sections_column[image] varchar(50) NOT NULL default '',
            PRIMARY KEY  (pn_secid))";
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
function Sections_upgrade($oldversion)
{
	return true;
}

/**
 * delete the sections module
 */
function Sections_delete()
{
    // Get database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Delete tables
    $sql = "DROP TABLE $pntable[seccont]";
    $dbconn->Execute($sql);
    // Check database result
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    $sql = "DROP TABLE $pntable[sections]";
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