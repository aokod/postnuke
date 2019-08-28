<?php
// File: $Id: dl-toprated.php 15951 2005-03-08 21:07:03Z larsneo $
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
// Original Author of file: Francisco Burzi
// Purpose of file:
// ----------------------------------------------------------------------

/**
 * TopRated
 */
function TopRated($ratenum, $ratetype)
{
    include 'header.php';

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    menu(1);

    $topdownloadspercentrigger = pnConfigGetVar('topdownloadsprecentrigger');
    $topdownloads = pnConfigGetVar('topdownloads');
    $downloadvotemin = pnConfigGetVar('downloadvotemin');

    if (!pnSecAuthAction(0, 'Downloads::', '::', ACCESS_READ)) {
        echo _DOWNLOADSACCESSNOAUTH;
        include 'footer.php';
        return;
    }

    OpenTable();
    if ($ratenum != "" && $ratetype != "") {
        $topdownloads = $ratenum;
        if ($ratetype == "percent") {
            $topdownloadspercentrigger = 1;
        }
    }
    if ($topdownloadspercentrigger == 1) {
        $topdownloadspercent = $topdownloads;
        $column = &$pntable['downloads_downloads_column'];
        $result =& $dbconn->Execute("SELECT count(*) FROM $pntable[downloads_downloads]
        						WHERE $column[downloadratingsummary] != 0");
        list($totalrateddownloads) = $result->fields;
        $topdownloads = $topdownloads / 100;
        $topdownloads = $totalrateddownloads * $topdownloads;
        // skooter - Added if statement so it will return at least one row.
        // In cases where the total downloads is low, this may not return any
        // records and in my opinion it should always return one record.
        if ($topdownloads < .5){
        	$topdownloads = 1;
        }
        else{
        	$topdownloads = round($topdownloads);
        }
    }
    if ($topdownloadspercentrigger == 1) {
        echo "<div style=\"text-align:center\"><h2>"._DBESTRATED." ".pnVarPrepForDisplay($topdownloadspercent)."% ("._OF." ".pnVarPrepForDisplay($totalrateddownloads)." "._TRATEDDOWNLOADS.")</h2>";
    } else {
        echo "<div style=\"text-align:center\"><h2>"._DBESTRATED." ".pnVarPrepForDisplay($topdownloads).'</h2>';
    }
    echo _NOTE." ".pnVarPrepForDisplay($downloadvotemin)." "._TVOTESREQ.'<br />'
    .""._SHOWTOP.":  [ <a href=\"".$GLOBALS['modurl']."&amp;req=TopRated&amp;ratenum=10&amp;ratetype=num\">10</a> - "
    ."<a href=\"".$GLOBALS['modurl']."&amp;req=TopRated&amp;ratenum=25&amp;ratetype=num\">25</a> - "
    ."<a href=\"".$GLOBALS['modurl']."&amp;req=TopRated&amp;ratenum=50&amp;ratetype=num\">50</a> | "
    ."<a href=\"".$GLOBALS['modurl']."&amp;req=TopRated&amp;ratenum=1&amp;ratetype=percent\">1%</a> - "
    ."<a href=\"".$GLOBALS['modurl']."&amp;req=TopRated&amp;ratenum=5&amp;ratetype=percent\">5%</a> - "
    ."<a href=\"".$GLOBALS['modurl']."&amp;req=TopRated&amp;ratenum=10&amp;ratetype=percent\">10%</a> ]</div><br />";
    $column = &$pntable['downloads_downloads_column'];
    $sql = "SELECT $column[lid], $column[cid], $column[sid],
                             $column[title], $column[description], $column[date],
                             $column[hits], $column[downloadratingsummary],
                             $column[totalvotes], $column[totalcomments],
                             $column[filesize], $column[version], $column[homepage]
                            FROM $pntable[downloads_downloads]
                            WHERE $column[downloadratingsummary] != 0
                             AND $column[totalvotes] >= '".pnVarPrepForStore($downloadvotemin)."'
                             ORDER BY $column[downloadratingsummary] DESC";

    $result =& $dbconn->SelectLimit($sql,$topdownloads);

// optimization step 2 - move this to a function starting here ----------------------

    while(list($lid, $cid, $sid, $title, $description, $time, $hits, $downloadratingsummary, $totalvotes, $totalcomments, $filesize, $version, $homepage)=$result->fields) {

        $result->MoveNext();
        $downloadratingsummary = number_format($downloadratingsummary, pnConfigGetVar('mainvotedecimal'));
        $title = stripslashes($title);
        $description = stripslashes($description);
        $cattitle = downloads_CatNameFromCID($cid);
        $transfertitle = str_replace (" ", "_", $title);

       if (downloads_authitem($cid, $sid, $lid, ACCESS_READ)) { 
				echo "<h3>";
	if (downloads_authitem($cid, $sid, $lid, ACCESS_EDIT)) { 
            echo "<a href=\"admin.php?module=".$GLOBALS['ModName']."&amp;op=DownloadsModDownload&amp;lid=$lid\"><img src=\"modules/".$GLOBALS['ModName']."/images/lwin.gif\" alt=\""._EDIT."\" /></a>&nbsp;&nbsp;";
        } else {
            echo "<img src=\"modules/".$GLOBALS['ModName']."/images/lwin.gif\" alt=\"\" />&nbsp;&nbsp;";
        }
        echo "<a href=\"".$GLOBALS['modurl']."&amp;req=getit&amp;lid=$lid\">".pnVarPrepForDisplay($title)."</a>";
        newdownloadgraphic($datetime, $time);
        popgraphic($hits);
        detecteditorial($lid, $transfertitle, 1);
        echo '</h3>';
        echo _DESCRIPTION.": ".pnVarPrepHTMLDisplay(nl2br($description)).'<br />';
/* cocomp 2002/07/13 unnecessary date stuff
        ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime);
        $datetime = ml_ftime(""._LINKSDATESTRING."", mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
        $datetime = ucfirst($datetime);
*/
        echo _VERSION.": ".pnVarPrepForDisplay($version)." "._FILESIZE.": ".pnVarPrepForDisplay(CoolSize($filesize)).'<br />';
        echo _ADDEDON.": ".pnVarPrepForDisplay($datetime)." "._UDOWNLOADS.": ".pnVarPrepForDisplay($hits);
        /* voting & comments stats */
        if ($totalvotes == 1) {
            $votestring = _VOTE;
        } else {
            $votestring = _VOTES;
        }
        if ($downloadratingsummary!="0" || $downloadratingsummary!="0.0") {
            echo " "._RATING.": ".pnVarPrepForDisplay($downloadratingsummary)." (".pnVarPrepForDisplay($totalvotes)." ".pnVarPrepForDisplay($votestring).")";
        }
        if ($homepage == "") {
            echo '<br />';
        } else {
            echo "<br /><a href=\"$homepage\">"._HOMEPAGE."</a> | ";
        }
	if (downloads_authitem($cid, $sid, $lid, ACCESS_COMMENT)) { 
            echo "<a href=\"".$GLOBALS['modurl']."&amp;req=ratedownload&amp;lid=$lid\">"._RATERESOURCE."</a>";
            echo " | ";
        }
        echo "<a href=\"".$GLOBALS['modurl']."&amp;req=brokendownload&amp;lid=$lid\">"._REPORTBROKEN."</a>";
        echo " | <a href=\"".$GLOBALS['modurl']."&amp;req=viewdownloaddetails&amp;lid=$lid\">"._DETAILS."</a>";
        if ($totalcomments != 0) {
            echo " | <a href=\"".$GLOBALS['modurl']."&amp;req=viewdownloadcomments&amp;lid=$lid\">"._COMMENTS." (".pnVarPrepForDisplay($totalcomments).")</a>";
        }
        detecteditorial($lid, $transfertitle, 0);
        echo '<br />';
        $result2 =& $dbconn->Execute("SELECT {$pntable['downloads_categories_column']['title']}
                                 FROM $pntable[downloads_categories]
                                 WHERE {$pntable['downloads_categories_column']['cid']}='".(int)pnVarPrepForStore($cid)."'");
        list($ctitle) = $result2->fields;
        echo _CATEGORY.": ".pnVarPrepForDisplay($ctitle);
        $result3 =& $dbconn->Execute("
                SELECT {$pntable['downloads_subcategories_column']['title']}
                FROM $pntable[downloads_subcategories]
                WHERE {$pntable['downloads_subcategories_column']['sid']}='".(int)pnVarPrepForStore($sid)."'");
        while(list($stitle) = $result3->fields) {

            $result3->MoveNext();
            echo " / ".pnVarPrepForDisplay($stitle);
        }
        echo '<br /><br />';
       }
    }
// and ending here --------------------------  function called 'showdownload()'
    CloseTable();
    include 'footer.php';
}
?>