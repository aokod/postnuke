<?php
// File: $Id: wl-mostpopular.php 16525 2005-07-26 22:45:23Z markwest $
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
 * MostPopular
 */
function MostPopular($ratenum, $ratetype) {
    //global $datetime;
    
    include('header.php');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $locale = pnConfigGetVar('locale');
    $mostpoplinkspercentrigger = pnConfigGetVar('mostpoplinkspercentrigger');
    $mostpoplinks = pnConfigGetVar('mostpoplinks');
    $mainvotedecimal = pnConfigGetVar('mainvotedecimal');
    
    menu(1);

    OpenTable();
    if ($ratenum != "" && $ratetype != "") {
		if (!is_numeric($ratenum)) {
			$ratenum=5;
		}
		if ($ratetype != "percent") {
			$ratetype = "num";
		}
        $mostpoplinks = $ratenum;
        if ($ratetype == "percent") $mostpoplinkspercentrigger = 1;
    }
    if ($mostpoplinkspercentrigger == 1) {
        $toplinkspercent = $mostpoplinks;
        $result =& $dbconn->Execute("SELECT COUNT(*) FROM $pntable[links_links]");
        list($totalmostpoplinks) = $result->fields;
        $mostpoplinks = $mostpoplinks / 100;
        $mostpoplinks = $totalmostpoplinks * $mostpoplinks;
        $mostpoplinks = round($mostpoplinks);
        $mostpoplinks = max(1, $mostpoplinks); // ensure we show at least one result!!
        echo '<div style="text-align:center"><h2>'._MOSTPOPULAR." $toplinkspercent% ("._OFALL." $totalmostpoplinks "._LINKS.")</h2></div>";
    } else {
    echo '<div style="text-align:center"><h2>'._MOSTPOPULAR." $mostpoplinks</h2></div>";
    }
    echo "<div style=\"text-align:center\">"._SHOWTOP.": [ <a href=\"".$GLOBALS['modurl']."&amp;req=MostPopular&amp;ratenum=10&amp;ratetype=num\">10</a> - "
        ."<a href=\"".$GLOBALS['modurl']."&amp;req=MostPopular&amp;ratenum=25&amp;ratetype=num\">25</a> - "
        ."<a href=\"".$GLOBALS['modurl']."&amp;req=MostPopular&amp;ratenum=50&amp;ratetype=num\">50</a> | "
        ."<a href=\"".$GLOBALS['modurl']."&amp;req=MostPopular&amp;ratenum=1&amp;ratetype=percent\">1%</a> - "
        ."<a href=\"".$GLOBALS['modurl']."&amp;req=MostPopular&amp;ratenum=5&amp;ratetype=percent\">5%</a> - "
        ."<a href=\"".$GLOBALS['modurl']."&amp;req=MostPopular&amp;ratenum=10&amp;ratetype=percent\">10%</a> ]</div><br />";

    $column = &$pntable['links_links_column'];
    //$myquery = buildSimpleQuery ('links_links', array ('lid', 'cat_id', 'title', 'description', 'date', 'hits', 'linkratingsummary', 'totalvotes', 'totalcomments'), '', "$column[hits] DESC", $mostpoplinks);
		$myquery = "SELECT $column[lid],
						$column[cat_id],
						$column[title],
						$column[description],
						$column[date],
						$column[hits],
						$column[linkratingsummary],
						$column[totalvotes],
						$column[totalcomments]
				FROM $pntable[links_links]
				ORDER BY $column[hits] DESC
				LIMIT $mostpoplinks";

    $result =& $dbconn->Execute($myquery);
    while(list($lid, $cid, $title, $description, $time, $hits, $linkratingsummary, $totalvotes, $totalcomments)=$result->fields) {

        $result->MoveNext();
		
		// get category name
		$column = &$pntable['links_categories_column'];
		$sql = "SELECT $column[title] 
				FROM $pntable[links_categories]
				WHERE $column[cat_id]= '".(int)pnVarPrepForStore($cid)."'";
    	$res =& $dbconn->Execute($sql);
    	list($cattitle) = $res->fields;

        if (pnSecAuthAction(0, "Web Links::Category", "$cattitle::$cid", ACCESS_READ)) {
        	$linkratingsummary = number_format($linkratingsummary, $mainvotedecimal);
        	echo "<h3><a href=\"".$GLOBALS['modurl']."&amp;req=visit&amp;lid=".pnVarPrepForDisplay($lid)."\">".pnVarPrepForDisplay($title)."</a>";
        	newlinkgraphic($datetime, $time);
        	popgraphic($hits);
    		echo '</h3>';
	   		echo ""._CATEGORY.": ".CatPath($cid,1,1,1);
    		echo '<br />';
			//transform hooks
			list($description) = pnModCallHooks('item', 'transform', '', array($description));
	        if (pnSecAuthAction(0, 'Web Links::', '::', ACCESS_ADMIN)) {
	            echo "<a href=\"admin.php?module=Web_Links&amp;op=LinksModLink&amp;lid=$lid\"><img src=\"modules/Web_Links/images/editicon.gif\" border=\"0\" alt=\""._EDITTHISLINK."\"></a>";
       		}
    		echo "".pnVarPrepHTMLDisplay($description).'<br />';
        	ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime);
        	$datetime = ml_ftime(""._LINKSDATESTRING."", mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
        	$datetime = ucfirst($datetime);
        	echo ""._ADDEDON.": ".pnVarPrepForDisplay($datetime)." | "._HITS.": ".pnVarPrepForDisplay($hits);
        	/* voting & comments stats */
        	if ($totalvotes == 1) {
            	$votestring = _VOTE;
        	} else {
            	$votestring = _VOTES;
        	}
        	if ($linkratingsummary!="0" || $linkratingsummary!="0.0") {
            	echo " | "._RATING.": ".pnVarPrepForDisplay($linkratingsummary)." (".pnVarPrepForDisplay($totalvotes)." ".pnVarPrepForDisplay($votestring).")";
            	//removed show star flag always display star. need to make it a config var - skooter.
            	//if ($web_links_show_star) {
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