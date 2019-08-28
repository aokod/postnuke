<?php
// File: $Id: wl-viewlink.php 19178 2006-06-01 13:00:27Z markwest $
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
// 10-15-2002:skooter      - Cross Site Scripting security fixes and also using
//                           pnAPI for displaying data.

/**
 * sortLinksByMenu
 * generates the sort links by menu
 */
function sortLinksByMenu($cid, $orderbyTrans)
{
  echo '<div style="text-align:center">'._SORTLINKSBY.": "
		._TITLE." ( <a href=\"".$GLOBALS['modurl']."&amp;req=viewlink&amp;cid=".pnVarPrepForDisplay($cid)."&amp;orderby=titleA\" title=\""._TITLEAZ."\">+</a> | <a href=\"".$GLOBALS['modurl']."&amp;req=viewlink&amp;cid=".pnVarPrepForDisplay($cid)."&amp;orderby=titleD\" title=\""._TITLEZA."\">-</a> ) "
		._DATE." ( <a href=\"".$GLOBALS['modurl']."&amp;req=viewlink&amp;cid=".pnVarPrepForDisplay($cid)."&amp;orderby=dateA\" title=\""._DATE1."\">+</a> | <a href=\"".$GLOBALS['modurl']."&amp;req=viewlink&amp;cid=".pnVarPrepForDisplay($cid)."&amp;orderby=dateD\" title=\""._DATE2."\">-</a> ) "
		._RATING." ( <a href=\"".$GLOBALS['modurl']."&amp;req=viewlink&amp;cid=".pnVarPrepForDisplay($cid)."&amp;orderby=ratingA\" title=\""._RATING1."\">+</a> | <a href=\"".$GLOBALS['modurl']."&amp;req=viewlink&amp;cid=".pnVarPrepForDisplay($cid)."&amp;orderby=ratingD\" title=\""._RATING2."\">-</a> ) "
		._POPULARITY." ( <a href=\"".$GLOBALS['modurl']."&amp;req=viewlink&amp;cid=".pnVarPrepForDisplay($cid)."&amp;orderby=hitsA\" title=\""._POPULARITY1."\">+</a> | <a href=\"".$GLOBALS['modurl']."&amp;req=viewlink&amp;cid=".pnVarPrepForDisplay($cid)."&amp;orderby=hitsD\" title=\""._POPULARITY2."\">-</a> )"
		."<br /><strong>"._SITESSORTED.": ".pnVarPrepForDisplay($orderbyTrans)."</strong><br /></div>";
}

/**
 * viewlink
 */
function viewlink($cid, $min, $orderby, $show)
{

	list($cid, $min, $orderby, $show) = pnVarCleanFromInput('cid', 'min', 'orderby', 'show');
	
    //global $datetime;
    include('header.php');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $column = &$pntable['links_categories_column'];

    //if (empty($perpage)||!is_numeric($perpage)) $perpage=pnConfigGetVar('perpage');
    $perpage = pnConfigGetVar('perpage');
    $locale = pnConfigGetVar('locale');
    $linkresults = pnConfigGetVar('linksresults');

    // check if this or parent category is accessible to user
    $result =& $dbconn->Execute("SELECT $column[parent_id], $column[title] 
								FROM $pntable[links_categories] 
								WHERE $column[cat_id]='".(int)pnVarPrepForStore($cid)."'");
    list($parent_id, $title) = $result->fields;
    $result_par =& $dbconn->Execute("SELECT $column[title] 
									FROM $pntable[links_categories] 
									WHERE $column[cat_id]='".(int)pnVarPrepForStore($parent_id)."'");
    list($parent_title) = $result_par->fields;
    if (!pnSecAuthAction(0, 'Web Links::Category', "$title::$cid" , ACCESS_READ) 
		|| !pnSecAuthAction(0, 'Web Links::Category', "$parent_title::$parent_id" , ACCESS_READ)) {
        echo _BADAUTHKEY;
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
    menu(1);

    OpenTable();
    $sql = "SELECT $column[title] , $column[cdescription]
			FROM $pntable[links_categories]
			WHERE $column[cat_id]='".(int)pnVarPrepForStore($cid)."'";
    $result =& $dbconn->Execute($sql);
    list($title, $description) = $result->fields;
    $GLOBALS['info']['title'] = CatPath($cid,0,1,0);
    echo '<div style="text-align:center"><h2>'._CATEGORY.": ".CatPath($cid,1,1,0).'</h2>';
    if ($description <> "") {
    	echo "".pnVarPrepHTMLDisplay($description).'<br />';
    }
    echo '</div>';

    //$carrytitle = $title;

    $column = &$pntable['links_categories_column'];
    $subsql = "SELECT $column[cat_id], $column[title]
				FROM $pntable[links_categories] 
				WHERE $column[parent_id]='".(int)pnVarPrepForStore($cid)."'
				ORDER BY $column[title]";
    $subresult =& $dbconn->Execute($subsql);
    $numrows = $subresult->PO_RecordCount();
    if ($numrows != 0) {
        echo '<div style="text-align:center">'._LALSOAVAILABLE." ".pnVarPrepForDisplay($title)." "._SUBCATEGORIES.':<br />';
		$count = 0;
        while(list($sid, $title) = $subresult->fields) {
            $subresult->MoveNext();
		    if (pnSecAuthAction(0, 'Web Links::Category', "$title::$sid" , ACCESS_READ)) {
            	echo "&nbsp;<img src=\"modules/Web_Links/images/folder.gif\" height=\"13\" width=\"15\" alt=\"\" /> <a href=\"".$GLOBALS['modurl']."&amp;req=viewlink&amp;cid=".(int)pnVarPrepForStore($sid)."\">"
            		.pnVarPrepForDisplay($title)."</a> (".CountSubLinks($sid).")&nbsp;";
            	subcategorynewlinkgraphic($sid);
            	echo "&nbsp;";
			}

            $scount++;
            if ($scount==3) {
                echo '<br />';
                $scount = 0;
            }
        }
		echo '</div><br />';
    }
    $orderbyTrans = convertorderbytrans($orderby);

    sortLinksByMenu($cid, $orderbyTrans);
		echo "<br />";
    $column = &$pntable['links_links_column'];
    $fullcountresult =& $dbconn->Execute("SELECT count(*) 
										FROM $pntable[links_links] 
										WHERE $column[cat_id]='".(int)pnVarPrepForStore($cid)."'");
    list($totalselectedlinks) = $fullcountresult->fields;

    $query = "SELECT $column[lid],
						$column[title],
						$column[description],
						$column[date],
						$column[hits],
						$column[linkratingsummary],
						$column[totalvotes],
						$column[totalcomments]
				FROM $pntable[links_links]
				WHERE $column[cat_id]='".(int)pnVarPrepForStore($cid)."' 
				ORDER BY $orderby";
    $result = $dbconn->SelectLimit($query, $perpage, (int)$min);
	
    while(list($lid, $title, $description, $time, $hits, $linkratingsummary, $totalvotes, $totalcomments)=$result->fields) {

        $result->MoveNext();
        if (pnSecAuthAction(0, 'Web Links::Link', ":$title:$lid", ACCESS_READ)) {
                $linkratingsummary = number_format($linkratingsummary, $mainvotedecimal);
                echo "<h3><a href=\"".$GLOBALS['modurl']."&amp;req=visit&amp;lid=".(int)$lid."\">".pnVarPrepForDisplay($title)."</a>";
                newlinkgraphic($datetime, $time);
                popgraphic($hits);
                echo '</h3>';
				//transform hooks
				list($description) = pnModCallHooks('item', 'transform', '', array($description));
		        if (pnSecAuthAction(0, 'Web Links::', '::', ACCESS_ADMIN)) {
		            echo "<a href=\"admin.php?module=Web_Links&amp;op=LinksModLink&amp;lid=$lid\"><img src=\"modules/Web_Links/images/editicon.gif\" alt=\""._EDITTHISLINK."\" /></a>";
        		}
                echo "".pnVarPrepHTMLDisplay($description).'<br />';
                ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime);
                $datetime = ml_ftime(""._LINKSDATESTRING."", mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
                $datetime = ucfirst($datetime);
                echo ""._ADDEDON.": ".pnVarPrepForDisplay($datetime)." | "._HITS.": ".(int)$hits;
                /* voting & comments stats */
                if ($totalvotes == 1) {
                    $votestring = _VOTE;
                } else {
                    $votestring = _VOTES;
                }
                if ($linkratingsummary!="0" || $linkratingsummary!="0.0") {
                    echo " | "._RATING.": ".pnVarPrepForDisplay($linkratingsummary)." (".(int)$totalvotes." ".pnVarPrepForDisplay($votestring).")";
                    //removed show star flag need to replace with config var - skooter
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
    $orderby = convertorderbyout($orderby);
    /* Calculates how many pages exist. Which page one should be on, etc... */
    $linkpagesint = ($totalselectedlinks / $perpage);
    $linkpageremainder = ($totalselectedlinks % $perpage);
    if ($linkpageremainder != 0) {
        $linkpages = ceil($linkpagesint);
        if ($totalselectedlinks < $perpage) {
            $linkpageremainder = 0;
        }
    } else {
        $linkpages = $linkpagesint;
    }
    /* Page Numbering */
    if ($linkpages > 1) {
        echo '<br />';
        echo _SELECTPAGE.": ";
        $prev=$min-$perpage;
        if ($prev>=0) {
            echo "&nbsp;&nbsp;<a href=\"".$GLOBALS['modurl']."&amp;req=viewlink&amp;cid=".(int)$cid."&amp;min=".(int)$prev."&amp;orderby=".pnVarPrepForDisplay($orderby)."&amp;show=".pnVarPrepForDisplay($show)."\">";
            echo " &lt;&lt; "._PREVIOUS."</a>&nbsp;&nbsp;&nbsp;";
        }
        $counter = 1;
        $currentpage = ($max / $perpage);
        while ($counter<=$linkpages ) {
            $cpage = $counter;
            $mintemp = ($perpage * $counter) - $perpage;
            if ($counter == $currentpage) {
                echo "".(int)$counter."&nbsp;";
            } else {
                echo "<a href=\"".$GLOBALS['modurl']."&amp;req=viewlink&amp;cid=".(int)$cid."&amp;min=".(int)$mintemp."&amp;orderby=".pnVarPrepForDisplay($orderby)."&amp;show=".pnVarPrepForDisplay($show)."\">".(int)$counter."</a> ";
            }
            $counter++;
        }
        $next=$min+$perpage;
        if ($currentpage < $linkpages) {
            echo "&nbsp;&nbsp;&nbsp;<a href=\"".$GLOBALS['modurl']."&amp;req=viewlink&amp;cid=".(int)$cid."&amp;min=".(int)$max."&amp;orderby=".pnVarPrepForDisplay($orderby)."&amp;show=".pnVarPrepForDisplay($show)."\">";
            echo " "._NEXT." &gt;&gt;</a>";
        }
    }
    CloseTable();
    include('footer.php');
}
?>