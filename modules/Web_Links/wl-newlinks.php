<?php
// File: $Id: wl-newlinks.php 16525 2005-07-26 22:45:23Z markwest $
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
 * NewLinks
 * @usedby index
 */
function NewLinks($newlinkshowdays) {

	if ( ($newlinkshowdays != "7" && $newlinkshowdays != "14" && $newlinkshowdays != "30") ||
		(!is_numeric($newlinkshowdays)) || (!isset($newlinkshowdays)) ) {
		$newlinkshowdays = "7";    
	}
    include('header.php');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $column = &$pntable['links_links_column'];
	$column2 = &$pntable['links_categories_column'];

    menu(1);

    OpenTable();
    echo '<div style="text-align:center"><h2>'._NEWLINKS.'</h2></div>';
    $counter = 0;
    $allweeklinks = 0;
    while ($counter <= 7-1){
    	$newlinkdayRaw = (time()-(86400 * $counter));
    	$newlinkday = date("d-M-Y", $newlinkdayRaw);
    	$newlinkView = date("F d, Y", $newlinkdayRaw);
    	$newlinkDB = Date("Y-m-d", $newlinkdayRaw);
    	$totallinks = 0;
    
    	$result =& $dbconn->Execute("SELECT $column[cat_id], $column2[title] 
									FROM $pntable[links_links], $pntable[links_categories] 
									WHERE $column[date] LIKE '%$newlinkDB%'
									AND $column[cat_id]=$column2[cat_id]");
      	while(list($cid, $title)=$result->fields) {
        	$result->MoveNext();
        	if (pnSecAuthAction(0, "Web Links::Category", "$title::$cid", ACCESS_READ)) {
           		$totallinks++;
        	}
      	}
    	$counter++;
    	$allweeklinks = $allweeklinks + $totallinks;
    }
    $counter = 0;
    if (!isset($allmonthlinks)) {
    $allmonthlinks = 0;
    }
    while ($counter < 30){
        $newlinkdayRaw = (time()-(86400 * $counter));
        $newlinkDB = Date("Y-m-d", $newlinkdayRaw);
        $totallinks = 0;
              
    	$result =& $dbconn->Execute("SELECT $column[cat_id], $column2[title] 
									FROM $pntable[links_links], $pntable[links_categories] 
									WHERE $column[date] LIKE '%$newlinkDB%'
									AND $column[cat_id]=$column2[cat_id]");
		while(list($cid, $title)=$result->fields) {
			$result->MoveNext();
        	if (pnSecAuthAction(0, "Web Links::Category", "$title::$cid", ACCESS_READ)) {
           		$totallinks++;
        	}
		}
        $allmonthlinks = $allmonthlinks + $totallinks;
        $counter++;
    }
    echo '<div style="text-align:center">'._TOTALNEWLINKS.": "._LASTWEEK.": "
    .pnVarPrepForDisplay($allweeklinks)." | "._LAST30DAYS.": ".pnVarPrepForDisplay($allmonthlinks).'<br />'
    ._SHOW.": <a href=\"".$GLOBALS['modurl']."&amp;req=NewLinks&amp;newlinkshowdays=7\">"
    ._1WEEK."</a> | <a href=\"".$GLOBALS['modurl']."&amp;req=NewLinks&amp;newlinkshowdays=14\">"
    ._2WEEKS."</a> | <a href=\"".$GLOBALS['modurl']."&amp;req=NewLinks&amp;newlinkshowdays=30\">"
    ._30DAYS."</a>"
    .'<br />';
    echo "<br /><strong>"._TOTALFORLAST
    ." ".pnVarPrepForDisplay($newlinkshowdays)." "._DAYS." :</strong><br />";
    $counter = 0;
    $allweeklinks = 0;
    while ($counter <= $newlinkshowdays-1) {
        $newlinkdayRaw = (time()-(86400 * $counter));
        $newlinkday = date("d-M-Y", $newlinkdayRaw);
        $newlinkView = ml_ftime(_DATEBRIEF, $newlinkdayRaw);
        $newlinkDB = Date("Y-m-d", $newlinkdayRaw);
		$totallinks = 0;

    	$result =& $dbconn->Execute("SELECT $column[cat_id], $column2[title] 
									FROM $pntable[links_links], $pntable[links_categories] 
									WHERE $column[date] LIKE '%$newlinkDB%'
									AND $column[cat_id]=$column2[cat_id]");
		while(list($cid, $title)=$result->fields) {
        	$result->MoveNext();
        	if (pnSecAuthAction(0, "Web Links::Category", "$title::$cid", ACCESS_READ)) {
           		$totallinks++;
        	}
      	}
        $counter++;
        $allweeklinks = $allweeklinks + $totallinks;
        echo "<strong><big>&middot;</big></strong> <a href=\"".$GLOBALS['modurl']."&amp;req=NewLinksDate&amp;selectdate="
        .pnVarPrepForDisplay($newlinkdayRaw)."\">".pnVarPrepForDisplay($newlinkView)."</a>&nbsp;("
        .pnVarPrepForDisplay($totallinks).")<br />";
    }
    $counter = 0;
    $allmonthlinks = 0;
    echo '</div>';
    CloseTable();
    include('footer.php');
}

/**
 * @usedby index, newlinks,
 */
function NewLinksDate($selectdate) {
    global $datetime;

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $mainvotedecimal = pnConfigGetVar('mainvotedecimal');
    $locale = pnConfigGetVar('locale');

    $dateDB = (date("d-M-Y", $selectdate));
    $dateView = (ml_ftime(_DATELONG, $selectdate));
    include('header.php');
    menu(1);

    OpenTable();
    $newlinkDB = date("Y-m-d", $selectdate);
    $column = &$pntable['links_links_column'];
	$column2 = &$pntable['links_categories_column'];
    //$result =& $dbconn->Execute("SELECT COUNT(*) FROM $pntable[links_links] WHERE $column[date] LIKE '%".pnVarPrepForStore($newlinkDB)."%'");
   	$totallinks=0;
	$result =& $dbconn->Execute("SELECT $column[cat_id], $column2[title] 
							FROM $pntable[links_links], $pntable[links_categories] 
							WHERE $column[date] LIKE '%$newlinkDB%'
							AND $column[cat_id]=$column2[cat_id]");
	while(list($cid, $title)=$result->fields) {
       	$result->MoveNext();
       	if (pnSecAuthAction(0, "Web Links::Category", "$title::$cid", ACCESS_READ)) {
       		$totallinks++;
       	}
	}
    echo '<h2>'.pnVarPrepForDisplay($dateView)." - ".pnVarPrepForDisplay($totallinks)." "._NEWLINKS.'</h2><br />';
    $result =& $dbconn->Execute("SELECT $column[lid], $column[cat_id], $column[title], $column[description], $column[date], $column[hits], $column[linkratingsummary], $column[totalvotes], $column[totalcomments] FROM $pntable[links_links] WHERE $column[date] LIKE '%".pnVarPrepForStore($newlinkDB)."%' ORDER BY $column[title] ASC");
    while(list($lid, $cid, $title, $description, $time, $hits, $linkratingsummary, $totalvotes, $totalcomments)=$result->fields) {
    	$result->MoveNext();
		// get category name
		$column = &$pntable['links_categories_column'];
		$sql = "SELECT $column[title] 
				FROM $pntable[links_categories]
				WHERE $column[cat_id]='".(int)pnVarPrepForStore($cid)."'";
    	$res =& $dbconn->Execute($sql);
    	list($cattitle) = $res->fields;

    	if (pnSecAuthAction(0, "Web Links::Category", "$cattitle::$cid", ACCESS_READ)) {
    		$linkratingsummary = number_format($linkratingsummary, $mainvotedecimal);
    		echo "<h3><a href=\"".$GLOBALS['modurl']."&amp;req=visit&amp;lid=".pnVarPrepForDisplay($lid)."\">".pnVarPrepForDisplay($title)."</a>";
    		newlinkgraphic($datetime, $time);
    		popgraphic($hits);
    		echo '</h3>';
	   		echo ""._CATEGORY." : ".CatPath($cid,1,1,1);
    		echo '<br />';
			//transform hooks
			list($description) = pnModCallHooks('item', 'transform', '', array($description));
	        if (pnSecAuthAction(0, 'Web Links::', '::', ACCESS_ADMIN)) {
	            echo "<a href=\"admin.php?module=Web_Links&amp;op=LinksModLink&amp;lid=$lid\"><img src=\"modules/Web_Links/images/editicon.gif\" alt=\""._EDITTHISLINK."\" /></a>";
       		}
    		echo pnVarPrepHTMLDisplay($description).'<br />';
    		ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime);
    		$datetime = ml_ftime(""._LINKSDATESTRING."", mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
    		$datetime = ucfirst($datetime);
    		echo ""._ADDEDON.": ".pnVarPrepForDisplay($datetime)." | "._HITS.": ".pnVarPrepForDisplay($hits);
        	$transfertitle = str_replace (" ", "_", $title);
        	/* voting & comments stats */
        	if ($totalvotes == 1) {
        		$votestring = _VOTE;
        	} else {
        		$votestring = _VOTES;
    		}
        	if ($linkratingsummary!="0" || $linkratingsummary!="0.0") {
        		echo " | "._RATING.": ".pnVarPrepForDisplay($linkratingsummary)." (".pnVarPrepForDisplay($totalvotes)." ".pnVarPrepForDisplay($votestring).")";
        		//removed show star flag - need to make it config var. - Skooter
        		//if ($web_links_show_star){
            		echo '<br />'.web_links_rateMakeStar($linkratingsummary, 10);
        		//}
    		}
        	$transfertitle = str_replace (" ", "_", $title);
    		LinksBottomMenu($lid, $transfertitle, $totalvotes, $totalcomments);
    		detecteditorial($lid, $transfertitle);
    		echo '<br /><br />';
    	}
    }
    CloseTable();
    include('footer.php');
}
?>