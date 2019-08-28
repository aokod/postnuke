<?php
/**
 * File: $Id: error.php 20406 2006-11-05 15:27:29Z larsneo $
 *  ----------------------------------------------------------------------
 *  PostNuke Content Management System
 *  Copyright (C) 2001 by the PostNuke Development Team.
 *  http://www.postnuke.com/
 *  ----------------------------------------------------------------------
 *  Based on:
 *  PHP-NUKE Web Portal System - http://phpnuke.org/
 *  Thatware - http://thatware.org/
 *  ----------------------------------------------------------------------
 *  LICENSE
 * 
 *  This program is free software; you can redistribute it and/or
 *  modify it under the terms of the GNU General Public License (GPL)
 *  as published by the Free Software Foundation; either version 2
 *  of the License, or (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 * 
 *  To read the license please visit http://www.gnu.org/copyleft/gpl.html
 *  ----------------------------------------------------------------------
 *  Original Author of file: larsneo
 *  Purpose of file: Error Handling
 *  Usage: Set up the redirection in your .htaccess with
 *  ErrorDocument 404 http://www.yoursite.com/error.php
 *  Note: REDIRECT_URL won't be available but
 *  due to subfolders one can't use a relative path :-/
 *  ----------------------------------------------------------------------
 **/

include_once 'includes/pnAPI.php';
pnInit();
$currentlang = pnUserGetLang();
$currentlang = pnVarPrepForOS($currentlang);
if (file_exists("language/$currentlang/error.php")) {
    include "language/$currentlang/error.php";
} elseif (file_exists("language/eng/error.php")) {
    include "language/eng/error.php";
}

$reportlevel = pnConfigGetVar('reportlevel');
$funtext = pnConfigGetVar('funtext');

header('HTTP/1.1 404 Not Found');

include('header.php');
        
if ($funtext == 0) {
	echo "<h2>"._ERROR404_HEAD."</h2>\n"
		."<br /><br />\n"
		."<strong>"._ERROR404_TRY."</strong><br />\n"
		._ERROR404_TRY1."<br />\n"
		."<a href=\"index.php\">"._ERROR404_TRY2."</a><br />\n"
		._ERROR404_TRY3."<br />\n"
		._ERROR404_TRY4."\n";
	if (pnModAvailable('Search')) {
		echo '<br /><a href="' . pnVarPrepForDisplay(pnModURL('Search')) . '">' . _ERROR404_TRY5."</a>\n";
	}
} else {
	echo "<strong>"._ERROR404_MAILSUBJECT."</strong><br /><br />\n";
	echo _ERROR404_FUNTEXT;
}


function send_email()
{
    $adminmail = pnConfigGetVar('adminmail');
	$subject = ""._ERROR404_MAILSUBJECT."";
	$sitename = pnConfigGetVar('sitename');
	$remote_addr = pnServerGetVar('REMOTE_ADDR');
	$http_referer = pnServerGetVar('HTTP_REFERER');
	$redirect_url = pnServerGetVar('REDIRECT_URL');
	$server = pnServerGetVar('HTTP_HOST');
	$errordoc = "http://$server$redirect_url";
	$errortime = ml_ftime(_DATETIMEBRIEF, date(time()));
	
	$message = "$subject\n\n";
    $message .= "TIME: $errortime\n";
    $message .= "REMOTE_ADDR: $remote_addr\n";
    $message .= "ERRORDOC: ".pnVarPrepForDisplay($errordoc)."\n";
    $message .= "HTTP_REFERER: $http_referer\n";

    pnMail($adminmail, $subject, $message, "From: \"$sitename\" <$adminmail>\nX-Mailer: PHP/" . phpversion());

    echo "<br /><br /><strong>"._ERROR404_MAILED."</strong>\n";
}


if ($reportlevel != 0) {
    switch ($reportlevel) {
        case "1":
            if (eregi(pnServerGetVar('HTTP_HOST'), pnServerGetVar('HTTP_REFERER'))) {
                send_email();
            }
            break;
        case "2":
            send_email();
            break;
    }
}

include('footer.php');
?>