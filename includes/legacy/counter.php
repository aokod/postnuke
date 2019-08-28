<?php
// File: $Id: counter.php 15630 2005-02-04 06:35:42Z jorg $
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

if (strpos($_SERVER['PHP_SELF'], 'counter.php')) {
	die ("You can't access this file directly...");
}

// NO and NO
// That's wrong, pnAPI yet include the right pntables.php
// MC

// why ?
// modification multisites .71 mouzaia
//  include(WHERE_IS_PERSO.'config.php');
// this one is necessary, it admitted the idea a site may have its own pntables.php
//    if (file_exists(WHERE_IS_PERSO."pntables.php"))
//        { include(WHERE_IS_PERSO."pntables.php"); }
//    else
//        { include("pntables.php"); }

// END MC

/* Get the Browser data */

if((ereg("Nav", pnServerGetVar("HTTP_USER_AGENT"))) || (ereg("Gold", pnServerGetVar("HTTP_USER_AGENT"))) || (ereg("X11", pnServerGetVar("HTTP_USER_AGENT"))) || (ereg("Mozilla", pnServerGetVar("HTTP_USER_AGENT"))) || (ereg("Netscape", pnServerGetVar("HTTP_USER_AGENT"))) AND (!ereg("MSIE", pnServerGetVar("HTTP_USER_AGENT"))) AND (!ereg("Konqueror", pnServerGetVar("HTTP_USER_AGENT")))) $browser = "Netscape";
// Opera needs to be above MSIE as it pretends to be an MSIE clone
elseif(ereg("Opera", pnServerGetVar("HTTP_USER_AGENT"))) $browser = "Opera";
elseif(ereg("MSIE", pnServerGetVar("HTTP_USER_AGENT"))) $browser = "MSIE";
elseif(ereg("Lynx", pnServerGetVar("HTTP_USER_AGENT"))) $browser = "Lynx";
elseif(ereg("WebTV", pnServerGetVar("HTTP_USER_AGENT"))) $browser = "WebTV";
elseif(ereg("Konqueror", pnServerGetVar("HTTP_USER_AGENT"))) $browser = "Konqueror";
elseif((eregi("bot", pnServerGetVar("HTTP_USER_AGENT"))) || (ereg("Google", pnServerGetVar("HTTP_USER_AGENT"))) || (ereg("Slurp", pnServerGetVar("HTTP_USER_AGENT"))) || (ereg("Scooter", pnServerGetVar("HTTP_USER_AGENT"))) || (eregi("Spider", pnServerGetVar("HTTP_USER_AGENT"))) || (eregi("Infoseek", pnServerGetVar("HTTP_USER_AGENT")))) $browser = "Bot";
else $browser = "Other";

/* Get the Operating System data */

if(ereg("Win", pnServerGetVar("HTTP_USER_AGENT"))) $os = "Windows";
elseif((ereg("Mac", pnServerGetVar("HTTP_USER_AGENT"))) || (ereg("PPC", pnServerGetVar("HTTP_USER_AGENT")))) $os = "Mac";
elseif(ereg("Linux", pnServerGetVar("HTTP_USER_AGENT"))) $os = "Linux";
elseif(ereg("FreeBSD", pnServerGetVar("HTTP_USER_AGENT"))) $os = "FreeBSD";
elseif(ereg("SunOS", pnServerGetVar("HTTP_USER_AGENT"))) $os = "SunOS";
elseif(ereg("IRIX", pnServerGetVar("HTTP_USER_AGENT"))) $os = "IRIX";
elseif(ereg("BeOS", pnServerGetVar("HTTP_USER_AGENT"))) $os = "BeOS";
elseif(ereg("OS/2", pnServerGetVar("HTTP_USER_AGENT"))) $os = "OS/2";
elseif(ereg("AIX", pnServerGetVar("HTTP_USER_AGENT"))) $os = "AIX";
else $os = "Other";

/* Save on the databases the obtained values */
//global $pntable, $dbconn;
$dbconn =& pnDBGetConn(true);
$pntable =& pnDBGetTables();

$column = &$pntable['counter_column'];
$dbconn->Execute("UPDATE $pntable[counter]
                SET $column[count]=$column[count]+1
                WHERE ($column[type]='total' AND $column[var]='hits')
                   OR ($column[var]='".pnVarPrepForStore($browser)."' AND $column[type]='browser')
                   OR ($column[var]='".pnVarPrepForStore($os)."' AND $column[type]='os')");

/* Per-Day-Counter */
$xydate=date("dmY");
$column = &$pntable['stats_date_column'];
$xyval =& $dbconn->Execute("SELECT $column[hits] as hits
                       FROM $pntable[stats_date]
                       WHERE $column[date]='".pnVarPrepForStore($xydate)."'");

if ($dbconn->ErrorNo() != 0) {
    echo "Error accessing stats information<P>";
}
$ttemp=$xyval->GetRowAssoc(false);
$xyval->MoveNext();
$happend=$ttemp['hits'];
if ($happend==""||$happend==false||!$happend)
{
    $column = &$pntable['stats_date_column'];
    $dbconn->Execute("INSERT INTO $pntable[stats_date]
                    ($column[date], $column[hits]) VALUES ('".pnVarPrepForStore($xydate)."','1')");
}
else
{
    $column = &$pntable['stats_date_column'];
    $dbconn->Execute("UPDATE $pntable[stats_date]
                    SET $column[hits]=$column[hits]+1
                    WHERE $column[date]='".pnVarPrepForStore($xydate)."'");
}

/* Per-Hour-Counter */
$xyhour=date("G");
$column = &$pntable['stats_hour_column'];
$dbconn->Execute("UPDATE $pntable[stats_hour]
                SET $column[hits]=$column[hits]+1
                WHERE $column[hour]='".pnVarPrepForStore($xyhour)."'");

/* Weekday-Counter */
$xyweekday=date("w");
$column = &$pntable['stats_week_column'];
$dbconn->Execute("UPDATE $pntable[stats_week]
                SET $column[hits]=$column[hits]+1
                WHERE $column[weekday]='".pnVarPrepForStore($xyweekday)."'");

/* Month-Counter */
$xymonth=date("m");
$column = &$pntable['stats_month_column'];
$dbconn->Execute("UPDATE $pntable[stats_month]
                SET $column[hits]=$column[hits]+1
                WHERE $column[month]='".pnVarPrepForStore($xymonth)."'");

?>