<?php 
// File: $Id: check.php 20429 2006-11-07 19:53:57Z landseer $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2002 by the PostNuke Development Team.
// http://www.postnuke.com/
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
// Original Author of file: Gregor J. Rothfuss
// Purpose of file: Provide checks for the installer.
// ----------------------------------------------------------------------
/**
 * Checks various php settings
 * by Bob Herald
 */
function do_check_php()
{
    if (phpversion() < "4.1.0") {
        $phpver = phpversion();
        echo "<p class=\"pn-title\">" . _PHP_CHECK_1 . $phpver . "<br />
             " . _PHP_CHECK_2 . "</p>";
    } 

    if (get_magic_quotes_gpc() == 0) {
        echo "<p class=\"pn-title\">" . _PHP_CHECK_3 . "</p>";
    } 

    if (get_magic_quotes_runtime() == 1) {
        echo "<p class=\"pn-title\">" . _PHP_CHECK_4 . "</p>";
    }

    $rg = ini_get('register_globals');
    if (($rg === true) || ($rg=='on') || ($rg==1)) {
        echo "<p class=\"pn-title\"><img src='install/style/red_check.gif' alt='' border='0' align='middle'>" . _REGISTER_GLOBALS_ON . "</p>";
	    echo "<p class=\"pn-title\">" . _REGISTER_GLOBALS_ON_HINT . "</p><br />";
	}

} 

/**
 * Checks if config.php has the correct permissions set
 */
function do_check_chmod()
{
    $chmod = 0;
    global $currentlang;

    echo "<p class=\"pn-title\">" . _CHMOD_CHECK_1 . "</p><br />
         <p class=\"pn-normal\">" . _CHMOD_CHECK_2 . "</p>";
    $file = 'config.php';
    $sideblock = "chmod"; 
    // $mode = fileperms($file);
    // $mode &= 0x1ff; # Remove the bits we don't need
    // $chmod = sprintf("%o", $mode);
    // if ($chmod == '666'){
    if (is_writable($file)) {
        echo "<p  class=\"pn-title\"><img src='install/style/green_check.gif'  alt='' border='0' align='absmiddle'>" . _CHMOD_CHECK_3 . "<p>";
    } else {
        echo "<p  class=\"pn-title\"><img src='install/style/red_check.gif'  alt='' border='0' align='absmiddle'>" . _CHMOD_CHECK_4 . "<p>";
        $chmod = 1;
    } 

    $file = 'config-old.php'; 
    // $mode = fileperms($file);
    // $mode &= 0x1ff; # Remove the bits we don't need
    // $chmod = sprintf("%o", $mode);
    // if ($chmod == '666'){
    if (is_writable($file)) {
        echo "<p class=\"pn-title\"><img src='install/style/green_check.gif'  alt='' border='0' align='absmiddle'>" . _CHMOD_CHECK_5 . "</p>";
    } else {
        echo "<p class=\"pn-title\"><img src='install/style/red_check.gif'  alt='' border='0' align='middle'>" . _CHMOD_CHECK_6 . "</p>";
        $chmod = 1;
    } 

	$dir = "pnTemp/pnRender_compiled";
    if (is_writable($dir)) {
        echo "<p class=\"pn-title\"><img src='install/style/green_check.gif'  alt='' border='0' align='absmiddle'>$dir -- " . _PNTEMP_DIRWRITABLE . "</p>";
    } else {
        echo "<p class=\"pn-title\"><img src='install/style/red_check.gif'  alt='' border='0' align='middle'>$dir -- " . _PNTEMP_DIRNOTWRITABLE . "</p>";
        $chmod = 1;
	}

	$dir = "pnTemp/pnRender_cache";
    if (is_writable($dir)) {
        echo "<p class=\"pn-title\"><img src='install/style/green_check.gif'  alt='' border='0' align='absmiddle'>$dir -- " . _PNTEMP_DIRWRITABLE . "</p>";
    } else {
        echo "<p class=\"pn-title\"><img src='install/style/red_check.gif'  alt='' border='0' align='middle'>$dir -- " . _PNTEMP_DIRNOTWRITABLE . "</p>";
        $chmod = 1;
	}

	$dir = "pnTemp/Xanthia_compiled";
    if (is_writable($dir)) {
        echo "<p class=\"pn-title\"><img src='install/style/green_check.gif'  alt='' border='0' align='absmiddle'>$dir -- " . _PNTEMP_DIRWRITABLE . "</p>";
    } else {
        echo "<p class=\"pn-title\"><img src='install/style/red_check.gif'  alt='' border='0' align='middle'>$dir -- " . _PNTEMP_DIRNOTWRITABLE . "</p>";
        $chmod = 1;
	}

	$dir = "pnTemp/Xanthia_cache";
    if (is_writable($dir)) {
        echo "<p class=\"pn-title\"><img src='install/style/green_check.gif'  alt='' border='0' align='absmiddle'>$dir -- " . _PNTEMP_DIRWRITABLE . "</p>";
    } else {
        echo "<p class=\"pn-title\"><img src='install/style/red_check.gif'  alt='' border='0' align='middle'>$dir -- " . _PNTEMP_DIRNOTWRITABLE . "</p>";
        $chmod = 1;
	}

	$dir = "pnTemp/Xanthia_Config";
    if (is_writable($dir)) {
        echo "<p class=\"pn-title\"><img src='install/style/green_check.gif'  alt='' border='0' align='absmiddle'>$dir -- " . _PNTEMP_DIRWRITABLE . "</p>
             <p><form action=\"install.php\" method=\"post\">
             <input type=\"hidden\" name=\"currentlang\" value=\"$currentlang\">";
    } else {
        echo "<p class=\"pn-title\"><img src='install/style/red_check.gif'  alt='' border='0' align='middle'>$dir -- " . _PNTEMP_DIRNOTWRITABLE . "</p>
             <p><form action=\"install.php\" method=\"post\">
             <input type=\"hidden\" name=\"currentlang\" value=\"$currentlang\">";
        $chmod = 1;
	}

    $dirname = "modules/NS-Quotes";

    if (is_dir($dirname)) {
        echo "<font class=\"pn-title\" color=red><br /><b>" . _QUOTESCHECK_1 . "</b><br /><br />";
        echo "<img src='install/style/red_check.gif'  alt='' border='0' align='absmiddle'><font color=red><b>" . _QUOTESCHECK_2 . "</b><br /><br />";
        $dircheck = 1;
    } else {
        $dircheck = 0;
    } 
    if ($chmod == 1 or $dircheck == 1) {
        echo "<center><input type=\"hidden\" name=\"op\" value=\"Check\"><input type=\"submit\" value=\"" . _BTN_RECHECK . "\"></center></form></p>";
    } elseif ($chmod == 0 or $dircheck == 0) {
        echo "<center><input type=\"hidden\" name=\"op\" value=\"CHM_check\"><input type=\"submit\" value=\"" . _BTN_CONTINUE . "\"></center></form></p>";
    } 
} 
function progress($percent)
{
    echo "<table align=\"center\" width=\"400\" bgcolor=\"#000000\" cellspacing=\"1\" cellpadding=\"0\"><tr bgcolor=\"#cccccc\"><td><table cellspacing=\"0\" cellpadding=\"0\" width=\"$percent%\"><tr><td align=\"center\" bgcolor=\"#264CB7\"><font size=\"1\" color=\"white\">$percent%</font></td></tr></table></td></tr></table>";
} 

?>