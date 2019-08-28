<?php
// File: $Id: wl-randomlink.php 15630 2005-02-04 06:35:42Z jorg $
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
// 11-30-2001:ahumphr - created file as part of modularistation

/**
 * RandomLink
 */
function RandomLink()
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
	$column = &$pntable['links_links_column'];
    
    // $result =& $dbconn->Execute("SELECT COUNT(*) FROM $pntable[links_links]");
    // list($numrows) = $result->fields;
    
    $totallinks = 0;
    $result =& $dbconn->Execute("SELECT $column[cat_id], $column[title] FROM $pntable[links_links] WHERE $column[date] LIKE '%".pnVarPrepForStore($newlinkDB)."%'");
    while(list($cid, $title)=$result->fields) {
		$result->MoveNext();
		if (pnSecAuthAction(0, "Web Links::Category", "$title::$cid", ACCESS_READ)) {
			$totallinks++;
		}
    }
    $numrows = $totallinks;

    if ($numrows < 1 ) { // if no data
        include('header.php');
        menu(1);

        OpenTable();
        echo '<div style="text-align:center"><strong>'._LINKNODATA."</strong><br />\n";
        echo _GOBACK."</div>\n";
        CloseTable();
        include('footer.php');
        return;
    }
    if ($numrows == 1) {
        $random = 1;
    } else {
        srand((double)microtime()*1000000);
        $random = rand(1,$numrows);
    }
    
    // $column = &$pntable['links_links_column'];
    // $result =& $dbconn->Execute("SELECT $column[url] FROM $pntable[links_links] WHERE $column[lid]='".pnVarPrepForStore($random)."'");
    // list($url) = $result->fields;
    
    $totallinks = 0;
    $result =& $dbconn->Execute("SELECT $column[lid], $column[url], $column[cat_id], $column[title] FROM $pntable[links_links] WHERE $column[date] LIKE '%".pnVarPrepForStore($newlinkDB)."%'");
    while(list($lid, $linkurl, $cid, $title)=$result->fields) {
		$result->MoveNext();
		if (pnSecAuthAction(0, "Web Links::Category", "$title::$cid", ACCESS_READ)) {
			$totallinks++;
		}
		if ($totallinks == $random) {
			$url = $linkurl;
			$random = $lid;
			break;
		}
    }

    $dbconn->Execute("UPDATE $pntable[links_links] SET $column[hits]=$column[hits]+1 WHERE $column[lid]='".pnVarPrepForStore($random)."'");
    pnRedirect($url);
}
?>