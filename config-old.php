<?php
// $Id: config-old.php 16107 2005-04-18 20:03:23Z lcabrera $
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
// Original Author of file: Everyone
// Purpose of file: Configure database
// ----------------------------------------------------------------------

// ----------------------------------------------------------------------
// Database & System Config
//
//      dbtype:      type of database, currently only mysql
//      dbtabletype: type of table in database, currently only 
//                   MyISAM or InnoDB
//      dbhost:      MySQL Database Hostname
//      dbuname:     MySQL Username
//      dbpass:      MySQL Password
//      dbname:      MySQL Database Name
//      system:      0 for Unix/Linux, 1 for Windows
//      encoded:     0 for MySQL information unenccoded
//                   1 for encoded
//      pconnect:    0 do not use pconnect (persistent connections)
//                   1 use pconnect [class007]
//      temp:        folder (with optional full path) for temp writable 
//                   temp directory
// ----------------------------------------------------------------------
//
$pnconfig['dbtype'] = 'mysql';
$pnconfig['dbtabletype'] = 'MyISAM';
$pnconfig['dbhost'] = 'localhost';
$pnconfig['dbuname'] = '';
$pnconfig['dbpass'] = '';
$pnconfig['dbname'] = 'Phoenix';
$pnconfig['system'] = '0';
$pnconfig['prefix'] = 'pn';
$pnconfig['encoded'] = '1';
$pnconfig['pconnect'] = '0';
$pnconfig['temp'] = 'pnTemp';


// ----------------------------------------------------------------------
// For debugging (Pablo Roca)
//
// $debug - debugger windows active
//          0 = No
//          1 = Yes
//
// $debug_sql - show SQL in lens debug
//          0 = No
//          1 = Yes
//
// $pagerendertime - display page render time in page footer
//          0 = No
//          1 = Yes
// ----------------------------------------------------------------------
global $pndebug;
$pndebug['debug']          = 0;
$pndebug['debug_sql']      = 0;
$pndebug['pagerendertime'] = 0;

// ----------------------------------------------------------------------
// You have finished configuring the database. Now you can start to
// change your site settings in the Administration Section.
//
// Thanks for choosing PostNuke.
// ----------------------------------------------------------------------

// ----------------------------------------------------------------------
// if there is a personal_config.php in the folder where is config.php
// we add it. (This HAS to be at the end, after all initialization.)
// ----------------------------------------------------------------------
if (@file_exists("personal_config.php"))
{ include("personal_config.php"); }
// ----------------------------------------------------------------------
// Make config file backwards compatible (deprecated)
// ----------------------------------------------------------------------
extract($pnconfig, EXTR_OVERWRITE);
?>
