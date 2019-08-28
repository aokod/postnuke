<?php
// File: $Id: install.php 20429 2006-11-07 19:53:57Z landseer $
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
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------

/**
 * PostNuke Install Script.
 *
 * This script will set the database up, and do the basic configurations of the script.
 * Once this script has run, please delete this file from your root directory.
 * There is a security risk if you keep this file around.
 *
 * This module of the PostNuke project was inspired by the myPHPNuke project.
 *
 * The PostNuke project is free software released under the GNU License.
 * Please read the credits file for more information on who has made this project possible.
 */

/** initialize vars, and include all necessary files **/


/*  Allows Postnuke to work with register_globals set to off
 *  Patch for php 4.2.x or greater
 */

if (phpversion() >= "4.2.0") {
    if ( ini_get('register_globals') != 1 ) {
        $supers = array('_REQUEST',
                        '_ENV',
                        '_SERVER',
                        '_POST',
                        '_GET',
                        '_COOKIE',
                        '_SESSION',
                        '_FILES',
                        '_GLOBALS' );

        foreach( $supers as $__s) {
            if ( ( isset( $$__s ) ) && ( is_array( $$__s ) == true )) {
                extract( $$__s, EXTR_OVERWRITE );
            }
        }
        unset($supers);
    }
} else {
    if ( ini_get('register_globals') != 1 ) {
        $supers = array('HTTP_POST_VARS',
                        'HTTP_GET_VARS',
                        'HTTP_COOKIE_VARS',
                        'GLOBALS',
                        'HTTP_SESSION_VARS',
                        'HTTP_REQUEST_VARS',
                        'HTTP_SERVER_VARS',
                        'HTTP_ENV_VARS' );

        foreach( $supers as $__s) {
            if ( is_array( $$__s ) == true ) {
                extract( $$__s, EXTR_OVERWRITE );
            }
        }
        unset($supers);
    }
}


@set_time_limit(0);

define('ADODB_DIR', 'includes/classes/adodb');
require_once ("includes/classes/adodb/adodb.inc.php");
define('_PNINSTALLVER', '0.7.6.4');

//ini_set('register_globals', 'On');

if (isset($alanguage)) {
    $currentlang = $alanguage;
}


if(!isset($prefix)) {
    include_once 'config.php';
    $prefix = $pnconfig['prefix'];
    $dbtype = $pnconfig['dbtype'];
    $dbtabletype = $pnconfig['dbtabletype'];
    $dbhost = $pnconfig['dbhost'];
    $dbuname = $pnconfig['dbuname'];
    $dbpass = $pnconfig['dbpass'];
    $dbname = $pnconfig['dbname'];
    $system = $pnconfig['system'];
    $encoded = $pnconfig['encoded'];
}

if (!empty($encoded)) {
    // Decode username and password
    $dbuname = base64_decode($dbuname);
    $dbpass = base64_decode($dbpass);
}

$pnconfig['prefix'] = $prefix;

include_once 'pntables.php';
include_once 'install/language.php'; // functions for multilanguage support

$currentlang = installer_get_language(@$currentlang);

include_once 'install/modify_config.php'; // functions to modify config.php
include_once 'install/upgrade.php';  // functions for upgrades
include_once 'install/newinstall.php'; // functions for new installs
include_once 'install/gui.php'; // functions for rendering the gui
include_once 'install/db.php'; // functions for accessing the db
include_once 'install/check.php'; // functions for various checks

/** print the page header, include style sheets **/
print_header();

/*  This starts the switch statement that filters through the form options.

* the @ is in front of $op to suppress error messages if $op is unset and E_ALL

* is on

*/
switch(@$op) {

    case "CHM_check":
         print_CHM_check();
         break;

    case "Submit":
         print_submit();
         break;

    case _BTN_CHANGEINFO:
         print_change_info();
         break;

    case _BTN_NEWINSTALL:
         print_new_install();
         break;

    case "Start":
         if(!isset($dbmake)) {
            $dbmake = false;
         }
         make_db($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype, $dbmake, $dbtabletype);
         print_start();
         break;

    case "Continue":
         print_continue();
         break;

    case "Set Login":
         $inst_dbconn = dbconnect($dbhost, $dbuname, $dbpass, $dbname, $dbtype);
         update_config_php(true); // Scott - added
         input_data($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype, $aid, $name, $pwd, $repeatpwd, $email, $url);
         if(start_postnuke($aid, $pwd)) {
            set_config_vars($currentlang);
            install_modules();
            insert_basic_data($prefix);
            close_postnuke();
         } else {
            echo "unable to start PostNuke!!";
            exit;
         }
         print_set_login();
         break;

    case "Select Language":
         print_select_language();
         break;

    case "Set Language":
         $currentlang = $alanguage;
         print_default();
         break;

    case "Finish":
         //$currentlang = $alanguage;
         print_finish();
         break;

    case _BTN_UPGRADE:
         print_upgrade();
         break;

    case "PHP-Nuke":
         print_select_phpnuke();
         break;

    case "PostNuke":
         print_select_postnuke();
         break;

    case "MyPHPNuke":
         print_select_myphpnuke();
         break;

/* Removed for release.  Needs to be updated

    Case "Validate Language":
         $dbconn = dbconnect($dbhost, $dbuname, $dbpass, $dbname, $dbtype);
         language_update($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         break;

    Case "Validate Tables":
         $dbconn = dbconnect($dbhost, $dbuname, $dbpass, $dbname, $dbtype);
         tables_update($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         break;

    Case "Validate Sequence Tables":
         $dbconn = dbconnect($dbhost, $dbuname, $dbpass, $dbname, $dbtype);
         sequence_update($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         break; */

    case "MyPHPNuke 1.8.7":
         $dbconn = dbconnect($dbhost, $dbuname, $dbpass, $dbname, $dbtype);
         do_upgrade187($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade64($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade7($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade71($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         print_success();
         break;

    case "MyPHPNuke 1.8.8":
         $dbconn = dbconnect($dbhost, $dbuname, $dbpass, $dbname, $dbtype);
         do_upgrade188($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade64($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade7($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade71($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         print_success();
         break;

    case "PHP-Nuke 4.4":
         $dbconn = dbconnect($dbhost, $dbuname, $dbpass, $dbname, $dbtype);
         do_upgrade4($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade5($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade6($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade62($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade63($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade64($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade7($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade71($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         print_forum_info();
         print_success();
         break;

    case "PHP-Nuke 5":
         $dbconn = dbconnect($dbhost, $dbuname, $dbpass, $dbname, $dbtype);
         do_upgrade5($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade6($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade62($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade63($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade64($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade7($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade71($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         print_success();
         break;

    case "PHP-Nuke 5.2":
         $dbconn = dbconnect($dbhost, $dbuname, $dbpass, $dbname, $dbtype);
         do_upgrade52($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade5($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade6($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade62($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade63($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade64($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade7($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade71($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         print_success();
         break;

    case "PHP-Nuke 5.3":
         $dbconn = dbconnect($dbhost, $dbuname, $dbpass, $dbname, $dbtype);
         do_upgrade53($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade52($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade5($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade6($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade62($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade63($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade64($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade7($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade71($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         print_success();
         break;

    case "PHP-Nuke 5.3.1":
         $dbconn = dbconnect($dbhost, $dbuname, $dbpass, $dbname, $dbtype);
         do_upgrade531($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade53($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade52($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade5($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade6($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade62($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade63($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade64($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade7($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade71($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         print_success();
         break;

    case "PHP-Nuke 5.4":
         $dbconn = dbconnect($dbhost, $dbuname, $dbpass, $dbname, $dbtype);
         do_upgrade54($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade531($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade53($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade52($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade5($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade6($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade62($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade63($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade64($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade7($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade71($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         print_success();
         break;

    case "PostNuke .5":
         $dbconn = dbconnect($dbhost, $dbuname, $dbpass, $dbname, $dbtype);
         do_upgrade5($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade6($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade62($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade63($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade64($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade7($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade71($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         print_success();
         break;

    case "PostNuke .6":
         $dbconn = dbconnect($dbhost, $dbuname, $dbpass, $dbname, $dbtype);
         do_upgrade6($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade62($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade63($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade64($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade7($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade71($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         print_success();
         break;

    case "PostNuke .62":
         $dbconn = dbconnect($dbhost, $dbuname, $dbpass, $dbname, $dbtype);
         do_upgrade62($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade63($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade64($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade7($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade71($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         print_success();
         break;

    case "PostNuke .63":
         $dbconn = dbconnect($dbhost, $dbuname, $dbpass, $dbname, $dbtype);
         do_upgrade63($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade64($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade7($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade71($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         print_success();
         break;

    case "PostNuke .64":
         $dbconn = dbconnect($dbhost, $dbuname, $dbpass, $dbname, $dbtype);
         do_upgrade64($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade7($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade71($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         print_success();
         break;

    case "PostNuke .7":
         $dbconn = dbconnect($dbhost, $dbuname, $dbpass, $dbname, $dbtype);
         do_upgrade7($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         do_upgrade71($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         print_success();
         break;

    case "PostNuke .71":
         $dbconn = dbconnect($dbhost, $dbuname, $dbpass, $dbname, $dbtype);
         do_upgrade71($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         print_success();
         break;

    case "PostNuke .72":
         $dbconn = dbconnect($dbhost, $dbuname, $dbpass, $dbname, $dbtype);
         do_upgrade72($dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype);
         print_success();
         break;

    case "Check":
        do_check_php();
        do_check_chmod();
        break;

    default:
         print_select_language($currentlang);
         break;
}

/** print the footer, and closing tags **/
print_footer();

?>