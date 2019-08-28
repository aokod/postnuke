<?php
// File: $Id: dl-viewdownload.php 19177 2006-06-01 12:47:52Z markwest $
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
 * viewdownload
 */
function viewdownload($cid, $min, $orderby, $show) {
	list($cid, $min, $orderby, $show) = pnVarCleanFromInput('cid', 'min', 'orderby', 'show');

    include('header.php');

    if ((empty($cid) || !is_numeric($cid))){
        echo _MODARGSERROR;
        include('footer.php');
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $perpage = pnConfigGetVar('perpage');

    $cattitle = downloads_CatNameFromCID($cid);
    if (!pnSecAuthAction(0, 'Downloads::Category', "$cattitle::$cid", ACCESS_READ)) {
        echo _DOWNLOADSACCESSNOAUTH;
        include 'footer.php';
        return;
    }

    if (empty($min) || !is_numeric($min)) $min=0;
    if (empty($show) || !is_numeric($show)) $show="";
    if (empty($max) || !is_numeric($max)) $max=$min+$perpage;
    if(!empty($orderby)) {
        $orderby = convertorderbyin($orderby);
    } else {
        $orderby = convertorderbyin("dateD");
    }
    if ($show!="") {
        $perpage = $show;
    } else {
        $show=$perpage;
    }
    menu(1);

    OpenTable();
    $result =& $dbconn->Execute("SELECT ".$pntable['downloads_categories_column']['title'].
                            " FROM ".$pntable['downloads_categories'].
                            " WHERE ".$pntable['downloads_categories_column']['cid']."='".(int)pnVarPrepForStore($cid)."'");

    list($title) = $result->fields;
    $GLOBALS['info']['title'] = pnVarPrepForDisplay($title);
    echo '<div style="text-align:center"><h2>'._CATEGORY.": ".pnVarPrepForDisplay($title).'</h2>';
    $carrytitle = $title;
    $column = &$pntable['downloads_subcategories_column'];
	$sorderby = $pntable['downloads_subcategories_column']['title']. ' ASC';
    $subresult =& $dbconn->Execute("SELECT $column[sid], $column[title]
                               FROM $pntable[downloads_subcategories]
                               WHERE $column[cid]='".(int)pnVarPrepForStore($cid).
					           "' ORDER BY ".$sorderby);
    if (!$subresult->EOF) {
        echo _DLALSOAVAILABLE." ".pnVarPrepForDisplay($title)." "._SUBCATEGORIES.':<br />';
        $scount = 0;

        $downloadscolumn = &$pntable['downloads_downloads_column'];
        $downloadstable = $pntable['downloads_downloads'];

        while(list($sid, $title) = $subresult->fields) {

        if (downloads_authsubcat($cid, $sid, ACCESS_READ)) {
                $result2 =& $dbconn->Execute("SELECT count(*)
                                             FROM $downloadstable
                                             WHERE $downloadscolumn[sid]='".(int)pnVarPrepForStore($sid)."'");

                list($numrows) = $result2->fields;
                echo "<img src=\"modules/".$GLOBALS['ModName']."/images/icon_folder.gif\" alt=\"\" width=\"15\" height=\"13\" />&nbsp;&nbsp;"
                . "<a href=\"".$GLOBALS['modurl']."&amp;req=viewsdownload&amp;sid=$sid\">"
                .pnVarPrepForDisplay($title)."</a> (".pnVarPrepForDisplay($numrows).")";
                subcategorynewlinkgraphic($sid);
                echo "&nbsp;&nbsp;&nbsp;";
                $scount++;
                if ($scount==3) {
                    echo '<br />';
                    $scount = 0;
                }
            }
            $subresult->MoveNext();
        }
        echo '<br />';
    }
    echo "</div>";
    $orderbyTrans = convertorderbytrans($orderby);
    echo '<div style="text-align:center">'._SORTDOWNLOADSBY.": "
        .""._TITLE." ( <a href=\"".$GLOBALS['modurl']."&amp;req=viewdownload&amp;cid=$cid&amp;orderby=titleA\" title=\""._TITLEAZ."\">+</a> | <a href=\"".$GLOBALS['modurl']."&amp;req=viewdownload&amp;cid=$cid&amp;orderby=titleD\" title=\""._TITLEZA."\">-</a> ) "
        .""._DATE." ( <a href=\"".$GLOBALS['modurl']."&amp;req=viewdownload&amp;cid=$cid&amp;orderby=dateA\" title=\""._DDATE1."\">+</a> | <a href=\"".$GLOBALS['modurl']."&amp;req=viewdownload&amp;cid=$cid&amp;orderby=dateD\" title=\""._DDATE2."\">-</a> ) "
        .""._RATING." ( <a href=\"".$GLOBALS['modurl']."&amp;req=viewdownload&amp;cid=$cid&amp;orderby=ratingA\" title=\""._RATING1."\">+</a> | <a href=\"".$GLOBALS['modurl']."&amp;req=viewdownload&amp;cid=$cid&amp;orderby=ratingD\" title=\""._RATING2."\">-</a> ) "
        .""._POPULARITY." ( <a href=\"".$GLOBALS['modurl']."&amp;req=viewdownload&amp;cid=$cid&amp;orderby=hitsA\" title=\""._POPULARITY1."\">+</a> | <a href=\"".$GLOBALS['modurl']."&amp;req=viewdownload&amp;cid=$cid&amp;orderby=hitsD\" title=\""._POPULARITY2."\">-</a> )"
        .'<br /><strong>'._RESSORTED.": $orderbyTrans</strong></div><br />";
    $column = &$pntable['downloads_downloads_column'];
    $sql = "SELECT $column[lid], $column[url], $column[title],
                   $column[description], $column[date], $column[hits],
                   $column[downloadratingsummary], $column[totalvotes],
                   $column[totalcomments], $column[filesize],
                   $column[version], $column[homepage]
            FROM $pntable[downloads_downloads]
            WHERE $column[cid]='".pnVarPrepForStore($cid)."'
            AND $column[sid]=0 ORDER BY $orderby";

    $result =& $dbconn->SelectLimit($sql,(int)$perpage,(int)$min);

    $fullcountresult =& $dbconn->Execute("SELECT $column[lid], $column[title],
                                              $column[description], $column[date],
                                              $column[hits], $column[downloadratingsummary],
                                              $column[totalvotes], $column[totalcomments]
                                       FROM $pntable[downloads_downloads]
                                       WHERE $column[cid]='".(int)pnVarPrepForStore($cid)."' AND $column[sid]=0");
    $totalselecteddownloads = $fullcountresult->PO_RecordCount();

    while(list($lid, $url, $title, $description, $time, $hits, $downloadratingsummary, $totalvotes, $totalcomments, $filesize, $version, $homepage)=$result->fields) {
        $result->MoveNext();
        # Fixes layout of the description in downloads annoying without this - Neo
        $description = nl2br($description);
        downloads_outputitem ($lid, $url, $title, $description, $time, $hits, $downloadratingsummary, $totalvotes, $totalcomments, $filesize, $version, $homepage, $GLOBALS['modurl'], $GLOBALS['ModName']);
    }

    $orderbyPager = convertorderbypager($orderby);
    downloads_outputpagelinks($cid, $GLOBALS['modurl'], $orderbyPager, $totalselecteddownloads, $perpage, $min, $max, $show, "viewdownload", "cid");

    CloseTable();
    include('footer.php');
}

function viewsdownload($sid, $min, $orderby, $show)
{
	list($sid, $min, $orderby, $show) = pnVarCleanFromInput('sid', 'min', 'orderby', 'show');

    include('header.php');

    if ( (empty($sid) || !is_numeric($sid)) ){
        echo _MODARGSERROR;
        include('footer.php');
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    menu(1);

    $perpage = pnConfigGetVar('perpage');

    $column = &$pntable['downloads_subcategories_column'];
    $result =& $dbconn->Execute("SELECT $column[cid], $column[title]
                              FROM $pntable[downloads_subcategories]
                              WHERE $column[sid]='".(int)pnVarPrepForStore($sid)."'");
    list($cid, $stitle) = $result->fields;

    $column = &$pntable['downloads_categories_column'];
    $result2 =& $dbconn->Execute("SELECT $column[cid], $column[title]
                               FROM $pntable[downloads_categories]
                               WHERE $column[cid]='".(int)pnVarPrepForStore($cid)."'");
    list($cid, $title) = $result2->fields;

    // DJD - Fix - Auth used to be called with Cat name instead of SubCat Name 04/06/2002
    if (downloads_authsubcat($cid, $sid, "ACCESS_READ")) {
        echo _DOWNLOADSACCESSNOAUTH;
        include 'footer.php';
        return;
    }

    if (empty($min) || !is_numeric($min)) $min=0;
    if (empty($show) || !is_numeric($show)) $show="";
    if (empty($max) || !is_numeric($max)) $max=$min+$perpage;
    if(!empty($orderby)) {
        $orderby = convertorderbyin($orderby);
    } else {
        $orderby = convertorderbyin("titleA");
    }
    if ($show!="") {
        $perpage = $show;
    } else {
        $show=$perpage;
    }

    OpenTable();
    $GLOBALS['info']['title'] = pnVarPrepForDisplay($stitle);
    echo "<div style=\"text-align:center\"><h2><a href=\"".$GLOBALS['modurl']."\">"._MAIN."</a> / <a href=\"".$GLOBALS['modurl']."&amp;req=viewdownload&amp;cid=$cid\">".pnVarPrepForDisplay($title)."</a> / ".pnVarPrepForDisplay($stitle)."</h2>";
    $orderbyTrans = convertorderbytrans($orderby);

    echo _SORTDOWNLOADSBY.": "
    .""._TITLE." ( <a href=\"".$GLOBALS['modurl']."&amp;req=viewsdownload&amp;sid=$sid&amp;orderby=titleA\">+</a> | <a href=\"".$GLOBALS['modurl']."&amp;req=viewsdownload&amp;sid=$sid&amp;orderby=titleD\">-</a> )"
    ." "._DATE." ( <a href=\"".$GLOBALS['modurl']."&amp;req=viewsdownload&amp;sid=$sid&amp;orderby=dateA\">+</a> | <a href=\"".$GLOBALS['modurl']."&amp;req=viewsdownload&amp;sid=$sid&amp;orderby=dateD\">-</a> )"
    ." "._RATING." ( <a href=\"".$GLOBALS['modurl']."&amp;req=viewsdownload&amp;sid=$sid&amp;orderby=ratingA\">+</a> | <a href=\"".$GLOBALS['modurl']."&amp;req=viewsdownload&amp;sid=$sid&amp;orderby=ratingD\">-</a> )"
    ." "._POPULARITY." ( <a href=\"".$GLOBALS['modurl']."&amp;req=viewsdownload&amp;sid=$sid&amp;orderby=hitsA\">+</a> | <a href=\"".$GLOBALS['modurl']."&amp;req=viewsdownload&amp;sid=$sid&amp;orderby=hitsD\">-</a> )"
    .'<br /><strong>'._RESSORTED.": $orderbyTrans</strong></div><br /><br />";

    $column = &$pntable['downloads_downloads_column'];
    $sql = "SELECT $column[lid], $column[url], $column[title],
                   $column[description], $column[date], $column[hits],
                   $column[downloadratingsummary], $column[totalvotes],
                   $column[totalcomments], $column[filesize],
                   $column[version], $column[homepage]
            FROM $pntable[downloads_downloads]
            WHERE $column[sid]='".(int)pnVarPrepForStore($sid)."'
            ORDER BY $orderby";

    $result =& $dbconn->SelectLimit($sql,(int)$perpage,(int)$min);
    //
    // Temporary Fixed       eugeniobaldi 01/07/11
    //                            ORDER BY  {$column[$orderby]} LIMIT $min,$perpage");
    $fullcountresult =& $dbconn->Execute("SELECT $column[lid], $column[title],
                                              $column[description], $column[date],
                                              $column[hits], $column[downloadratingsummary],
                                              $column[totalvotes], $column[totalcomments]
                                       FROM $pntable[downloads_downloads]
                                       WHERE $column[sid]='".(int)pnVarPrepForStore($sid)."'");

    $totalselecteddownloads = $fullcountresult->PO_RecordCount();

    while(list($lid, $url, $title, $description, $time, $hits, $downloadratingsummary, $totalvotes, $totalcomments, $filesize, $version, $homepage) = $result->fields) {
        $result->MoveNext();
        $description = nl2br($description);		
        downloads_outputitem ($lid, $url, $title, $description, $time, $hits, $downloadratingsummary, $totalvotes, $totalcomments, $filesize, $version, $homepage, $GLOBALS['modurl'], $GLOBALS['ModName']);
    }

    $orderbyPager = convertorderbypager($orderby);
    downloads_outputpagelinks($sid, $GLOBALS['modurl'], $orderbyPager, $totalselecteddownloads, $perpage, $min, $max, $show, "viewsdownload", "sid");

    CloseTable();
    include 'footer.php';
}
?>