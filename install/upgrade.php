<?php 
// File: $Id: upgrade.php 15630 2005-02-04 06:35:42Z jorg $
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

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file:  Gregor J. Rothfuss
// Purpose of file: Provide upgrade functions for installer.
// ----------------------------------------------------------------------
/**
 * This function calls the upgrade from mpn 1.8.7
 */
function do_upgrade187 ($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype)
{
    global $dbconn;
    mysql_connect($dbhost, $dbuname, $dbpass);
    mysql_select_db("$dbname") or die ("<br><font class=\"pn-failed\">Unable to select database.</font>");
    include("install/mpn187.php");
    include("install/mpn188.php");
} 

/**
 * This function calls the upgrade from mpn 1.8.8b2
 */
function do_upgrade188 ($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype)
{
    global $dbconn;
    mysql_connect($dbhost, $dbuname, $dbpass);
    mysql_select_db("$dbname") or die ("<br><font class=\"pn-failed\">Unable to select database.</font>");
    include("install/mpn188.php");
} 

/**
 * This function calls the upgrade from PHP-Nuke 4.4
 */
function do_upgrade4 ($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype)
{
    global $dbconn;
    mysql_connect($dbhost, $dbuname, $dbpass);
    mysql_select_db("$dbname") or die ("<br><font class=\"pn-failed\">Unable to select database.</font>");
    include("install/pn4.php");
} 

/**
 * This function calls the upgrade from PHP-Nuke 5.2
 */
function do_upgrade52 ($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype)
{
    global $dbconn;
    mysql_connect($dbhost, $dbuname, $dbpass);
    mysql_select_db("$dbname") or die ("<br><font class=\"pn-failed\">Unable to select database.</font>");
    include("install/phpnuke52.php");
} 

/**
 * This function calls the upgrade from PHP-Nuke 5.3
 */
function do_upgrade53 ($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype)
{
    global $dbconn;
    mysql_connect($dbhost, $dbuname, $dbpass);
    mysql_select_db("$dbname") or die ("<br><font class=\"pn-failed\">Unable to select database.</font>");
    include("install/phpnuke53.php");
} 

/**
 * This function calls the upgrade from PHP-Nuke 5.3.1
 */
function do_upgrade531 ($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype)
{
    global $dbconn;
    mysql_connect($dbhost, $dbuname, $dbpass);
    mysql_select_db("$dbname") or die ("<br><font class=\"pn-failed\">Unable to select database.</font>");
    include("install/phpnuke531.php");
} 

/**
 * This function calls the upgrade from PHP-Nuke 5.4
 */
function do_upgrade54 ($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype)
{
    global $dbconn;
    mysql_connect($dbhost, $dbuname, $dbpass);
    mysql_select_db("$dbname") or die ("<br><font class=\"pn-failed\">Unable to select database.</font>");
    include("install/phpnuke54.php");
} 

/**
 * This function calls the upgrade from PHP-Nuke 5 / PostNuke .5
 */
function do_upgrade5 ($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype)
{
    global $dbconn;
    mysql_connect($dbhost, $dbuname, $dbpass);
    mysql_select_db("$dbname") or die ("<br><font class=\"pn-failed\">Unable to select database.</font>");
    include("install/pn5.php");
} 

/**
 * This function calls the upgrade from PostNuke .60 / .61
 */
function do_upgrade6 ($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype)
{
    global $dbconn;
    mysql_connect($dbhost, $dbuname, $dbpass);
    mysql_select_db("$dbname") or die ("<br><font class=\"pn-failed\">Unable to select database.</font>");
    include("install/pn6.php");
} 

/**
 * This function calls the upgrade from PostNuke .62
 */
function do_upgrade62 ($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype)
{
    global $dbconn;
    mysql_connect($dbhost, $dbuname, $dbpass);
    mysql_select_db("$dbname") or die ("<br><font class=\"pn-failed\">Unable to select database.</font>");
    include("install/pn62.php");
} 

/**
 * This function calls the upgrade from PostNuke .63
 */
function do_upgrade63 ($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype)
{
    global $dbconn;
    mysql_connect($dbhost, $dbuname, $dbpass);
    mysql_select_db("$dbname") or die ("<br><font class=\"pn-failed\">Unable to select database.</font>");
    include("install/pn63.php");
} 

/**
 * This function calls the upgrade from PostNuke .64
 */
function do_upgrade64 ($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype)
{
    global $dbconn;
    mysql_connect($dbhost, $dbuname, $dbpass);
    mysql_select_db("$dbname") or die ("<br><font class=\"pn-failed\">Unable to select database.</font>");
    include("install/pn64.php");
    update_config_php(true); // Scott - added
} 

/**
 * This function calls the upgrade from PostNuke .7
 */
function do_upgrade7 ($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype)
{
    global $dbconn;
    mysql_connect($dbhost, $dbuname, $dbpass);
    mysql_select_db("$dbname") or die ("<br><font class=\"pn-failed\">Unable to select database.</font>");
    include("install/pn7.php");
} 

/**
 * This function calls the upgrade from PostNuke .71
 */
function do_upgrade71 ($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype)
{
    global $dbconn;
    mysql_connect($dbhost, $dbuname, $dbpass);
    mysql_select_db("$dbname") or die ("<br><font class=\"pn-failed\">Unable to select database.</font>");
    include("install/pn71.php");
} 

/**
 * This function calls the upgrade from PostNuke .722
 */
function do_upgrade72 ($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype)
{
    global $dbconn;
    mysql_connect($dbhost, $dbuname, $dbpass);
    mysql_select_db("$dbname") or die ("<br><font class=\"pn-failed\">Unable to select database.</font>");
    include("install/pn72.php");
} 

/**
 * Removed for Release.  Needs to be updated.
 * 
 * function tables_update($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype) {
 * global $dbconn;
 * mysql_connect($dbhost, $dbuname, $dbpass);
 * mysql_select_db("$dbname") or die ("<br><font class=\"pn-failed\">Unable to select database.</font>");
 * include 'install/update_functions.php';
 * include 'install/tables_update.php';
 * }
 * 
 * function sequence_update($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype) {
 * global $dbconn;
 * mysql_connect($dbhost, $dbuname, $dbpass);
 * mysql_select_db("$dbname") or die ("<br><font class=\"pn-failed\">Unable to select database.</font>");
 * include 'install/update_functions.php';
 * include 'install/sequence_update.php';
 * }
 * 
 * function language_update($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype) {
 * global $dbconn;
 * mysql_connect($dbhost, $dbuname, $dbpass);
 * mysql_select_db("$dbname") or die ("<br><font class=\"pn-failed\">Unable to select database.</font>");
 * include 'install/update_functions.php';
 * include 'install/language_update.php';
 * }
 */

?>