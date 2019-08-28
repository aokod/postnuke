<?php
// File: $Id: dl-search.php 20181 2006-10-03 12:46:45Z landseer $
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
 * search
 */
function search($query, $min, $orderby, $show) {

	list($query, $min, $orderby, $show) = pnVarCleanFromInput('query', 'min', 'orderby', 'show');

    pnModDBInfoLoad('Downloads');
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $perpage = pnConfigGetVar('perpage');
    $locale = pnConfigGetVar('locale');
    $downloadsresults = pnConfigGetVar('downloadsresults');

    if (!empty($min) && !is_numeric($min)) $min=0;
    if (!empty($max) && !is_numeric($max)) $max=$min+$downloadsresults;
    if (!empty($show) && !is_numeric($show)) $show='';
    if(!empty($orderby)) {
        $orderby = convertorderbyin($orderby);
    } else {
        $orderby = $pntable['downloads_downloads_column']['title'] . ' ASC';
    }
    if ($show!="") {
        $downloadsresults = $show;
    } else {
        $show=$downloadsresults;
    }
    //$query = stripslashes($query);
    $column = &$pntable['downloads_downloads_column'];
	$sql = "SELECT $column[lid], $column[cid], $column[sid],
                              $column[title], $column[url], $column[description],
                              $column[date], $column[hits], $column[downloadratingsummary],
                              $column[totalvotes], $column[totalcomments],
                              $column[filesize], $column[version], $column[homepage]
                              FROM $pntable[downloads_downloads]
                              WHERE $column[title] LIKE '%".pnVarPrepForStore($query)."%'
                                OR $column[description] LIKE '%".pnVarPrepForStore($query)."%'
                                ORDER BY $orderby";
    $result = $dbconn->SelectLimit($sql, (int)$downloadsresults, (int)$min);
    $nrows  = $result->PO_RecordCount();
    $fullcountresult =& $dbconn->Execute("SELECT count(*) from $pntable[downloads_downloads]
                                     WHERE $column[title] LIKE '%".pnVarPrepForStore($query)."%'
                                       OR $column[description] LIKE '%".pnVarPrepForStore($query)."%' ");
    list($totalselecteddownloads) = $fullcountresult->fields;
    
    $resultx =& $dbconn->Execute("SELECT * FROM ".$pntable['downloads_subcategories'].
                               " WHERE ".$pntable['downloads_subcategories_column']['title']." LIKE '%".pnVarPrepForStore($query).
                               "%' ORDER BY ".$pntable['downloads_subcategories_column']['title']." DESC");
    $nrowsx  = $resultx->PO_RecordCount();
    $x=0;
    include('header.php');
    menu(1);

    OpenTable();
    if ($query != "") {
        if ($nrows>0 OR $nrowsx>0) {
            echo '<strong>'._SEARCHRESULTS4.": ".pnVarPrepForDisplay($query).'</strong><br />'
	            .'<strong>'._USUBCATEGORIES.'</strong><br />';
            $column = &$pntable['downloads_subcategories_column'];
            $result2 =& $dbconn->Execute("SELECT $column[cid], $column[sid],
                                       $column[title] FROM $pntable[downloads_subcategories]
                                       WHERE $column[title] LIKE '%".pnVarPrepForStore($query)."%'
                                        ORDER BY $column[title] DESC");
            while(list($cid, $sid, $stitle) = $result2->fields) {
				if (downloads_authsubcat($cid, $sid, ACCESS_READ)) {
					$ctitle = downloads_CatNameFromCID($cid);
					$subnumfiles = downloads_SubCatNumItems($sid);
					//$ctitle = ereg_replace($query, "$query", pnVarPrepForDisplay($ctitle));
			        //$stitle = ereg_replace($query, "$query", pnVarPrepForDisplay($stitle));
					$ctitle = pnVarPrepForDisplay($ctitle);
					$stitle = pnVarPrepForDisplay($stitle);
			        echo "<strong><big>&middot;</big></strong>&nbsp;<a href=\"".$GLOBALS['modurl']."&amp;req=viewsdownload&amp;sid=$sid\">$ctitle / $stitle</a> ($subnumfiles)<br />";
			        $result2->MoveNext();
				}
			}

            echo "<br /><strong>"._UDOWNLOADS.'</strong><br />';
            $orderbyTrans = convertorderbytrans($orderby);
            echo ""._SORTDOWNLOADSBY.": "
            .""._TITLE." (<a href=\"".$GLOBALS['modurl']."&amp;req=search&amp;query=".pnVarPrepForDisplay($query)."&amp;orderby=titleA\">+</a> | <a href=\"".$GLOBALS['modurl']."&amp;req=search&amp;query=".pnVarPrepForDisplay($query)."&amp;orderby=titleD\">-</a>) "
            .""._DATE." (<a href=\"".$GLOBALS['modurl']."&amp;req=search&amp;query=".pnVarPrepForDisplay($query)."&amp;orderby=dateA\">+</a> | <a href=\"".$GLOBALS['modurl']."&amp;req=search&amp;query=".pnVarPrepForDisplay($query)."&amp;orderby=dateD\">-</a>) "
            .""._RATING." (<a href=\"".$GLOBALS['modurl']."&amp;req=search&amp;query=".pnVarPrepForDisplay($query)."&amp;orderby=ratingA\">+</a> | <a href=\"".$GLOBALS['modurl']."&amp;req=search&amp;query=".pnVarPrepForDisplay($query)."&amp;orderby=ratingD\">-</a>) "
            .""._POPULARITY." (<a href=\"".$GLOBALS['modurl']."&amp;req=search&amp;query=".pnVarPrepForDisplay($query)."&amp;orderby=hitsA\">+</a> | <a href=\"".$GLOBALS['modurl']."&amp;req=search&amp;query=".pnVarPrepForDisplay($query)."&amp;orderby=hitsD\">-</a>)"
            .'<br />'._RESSORTED.": $orderbyTrans<br /><br />";

            while(list($lid, $cid, $sid, $title, $url, $description, $time, $hits, $downloadratingsummary, $totalvotes, $totalcomments, $filesize, $version, $homepage) = $result->fields) {
                $result->MoveNext();
                //$title = ereg_replace($query, "$query", $title); // Skooter - no nead to use pnVarPrepForDisplay here it is done in downloads_outputitem
				downloads_outputitem ($lid, $url, $title, nl2br($description), $time, $hits, $downloadratingsummary, $totalvotes, $totalcomments, $filesize, $version, $homepage, $GLOBALS['modurl'], $GLOBALS['ModName']);

//                $catname = downloads_CatNameFromCID($cid);
//                 echo _CATEGORY.": $ctitle $slash $stitle<br />";

                $x++;
            }
        } else {
            echo '<h2 style="text-align: center;">' . pnVarPrepForDisplay(_NOMATCHES) . '</h2>';
        }
        //echo "</span>";
        $orderby = convertorderbyout($orderby);

		//downloads_outputpagelinks($query, $GLOBALS['modurl'], $orderby, $totalselecteddownloads, $perpage, $min, $max, $show, "search", "query");

        echo '<br /><div style="text-align:center">'
        .""._TRY2SEARCH." \"".pnVarPrepForDisplay($query)."\" "._INOTHERSENGINES.'<br />'
        ."<a href=\"http://www.google.com/search?q=".pnVarPrepForDisplay($query)."\">Google</a> - "
        ."<a href=\"http://search.yahoo.com/bin/search?p=".pnVarPrepForDisplay($query)."\">Yahoo</a>"
        .'</div>';

	} else {
        echo '<h2>'._NOMATCHES.'</h2>';
    }
    CloseTable();
    include('footer.php');
}
?>