<?php
// File: $Id: wl-search.php 20182 2006-10-03 13:10:10Z landseer $
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
 * search
 */
function search($query, $min, $orderby, $show)
{
    global $datetime;

	list($query, $min, $orderby, $show) = pnVarCleanFromInput('query', 'min', 'orderby', 'show');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $perpage = pnConfigGetVar('perpage');
    $locale = pnConfigGetVar('locale');
    $linksresults = pnConfigGetVar('linksresults');
    $mainvotedecimal = pnConfigGetVar('mainvotedecimal');

    if (!isset($min) || !is_numeric($min)) $min=0;
    if (!isset($max) || !is_numeric($max)) $max=$min+$linksresults;
    if(isset($orderby)) {
        $orderby = convertorderbyin($orderby);
    } else {
        $orderby = $pntable['links_links_column']['title'] . ' ASC';
    }
    if ($show != '') {
        $linksresults = $show;
    } else {
        $show = $linksresults;
    }

    $column = &$pntable['links_links_column'];
	$sql = "SELECT $column[lid],
						$column[cat_id],
						$column[title],
						$column[url],
						$column[description],
						$column[date],
						$column[hits],
						$column[linkratingsummary],
						$column[totalvotes],
						$column[totalcomments]
				FROM $pntable[links_links]
				WHERE $column[title] LIKE '%".pnVarPrepForStore($query)."%' 
				OR $column[description] LIKE '%".pnVarPrepForStore($query)."%'
				ORDER BY $orderby";

    $result = $dbconn->SelectLimit($sql, (int)$linksresults, (int)$min);
    $nrows  = $result->PO_RecordCount();

	$fullcountsql = "SELECT count(*) 
					FROM $pntable[links_links] 
					WHERE $column[title] LIKE '%".pnVarPrepForStore($query)."%' 
					OR $column[description] LIKE '%".pnVarPrepForStore($query)."%'";
    $fullcountresult =& $dbconn->Execute($fullcountsql);
    // fix for showing the pager, credits to Petzi-Juist
    //$totalselectedlinks = $fullcountresult->PO_RecordCount();
    list($totalselectedlinks) = $fullcountresult->fields;	
	
    $column = &$pntable['links_categories_column'];
    $resultx =& $dbconn->Execute("SELECT count(*) 
								FROM $pntable[links_categories] 
								WHERE $column[title] LIKE '%".pnVarPrepForStore($query)."%'");
    $nrowsx  = $resultx->PO_RecordCount();
 
    $x=0;
    include('header.php');
    menu(1);

    OpenTable();
    if ($query != "") {
        if ($nrows>0 OR $nrowsx>0) {
            echo _SEARCHRESULTS4.": <strong>".pnVarPrepForDisplay($query).'</strong><br />'
                .'<strong>'._USUBCATEGORIES.'</strong><br />';
            $column = &$pntable['links_categories_column'];
            $result2 =& $dbconn->Execute("SELECT $column[title], $column[cat_id] 
										FROM $pntable[links_categories] 
										WHERE $column[title] LIKE '%".pnVarPrepForStore($query)."%' 
										ORDER BY $column[title] DESC");
            while(list($title,$cid) = $result2->fields) {

                $result2->MoveNext();
				if (pnSecAuthAction(0, 'Web Links::Category', "$title::$cid", ACCESS_READ)) {
					//$stitle = ereg_replace($query, "<strong>$query</strong>", CatPath($cid,1,1,1));
					$stitle = CatPath($cid,1,1,1);
					echo "<strong><big>&middot;</big></strong>&nbsp;<a href=\"".$GLOBALS['modurl']."&amp;req=viewlink&amp;cid=".pnVarPrepForDisplay($cid)."\">$stitle</a><br />";
				}
            }
            echo "<br /><strong>"._LINKS.'</strong><br />';
            $orderbyTrans = convertorderbytrans($orderby);
            echo "<span class=\"pn-sub\">"._SORTLINKSBY.": "
                ." "._TITLE." (<a href=\"".$GLOBALS['modurl']."&amp;req=search&amp;query=".pnVarPrepForDisplay($query)."&amp;orderby=titleA\">+</a>/<a href=\"".$GLOBALS['modurl']."&amp;req=search&amp;query=".pnVarPrepForDisplay($query)."&amp;orderby=titleD\">-</a>)"
                ." "._DATE." (<a href=\"".$GLOBALS['modurl']."&amp;req=search&amp;query=".pnVarPrepForDisplay($query)."&amp;orderby=dateA\">+</a>/<a href=\"".$GLOBALS['modurl']."&amp;req=search&amp;query=".pnVarPrepForDisplay($query)."&amp;orderby=dateD\">-</a>)"
                ." "._RATING." (<a href=\"".$GLOBALS['modurl']."&amp;req=search&amp;query=".pnVarPrepForDisplay($query)."&amp;orderby=ratingA\">+</a>/<a href=\"".$GLOBALS['modurl']."&amp;req=search&amp;query=".pnVarPrepForDisplay($query)."&amp;orderby=ratingD\">-</a>)"
                ." "._POPULARITY." (<a href=\"".$GLOBALS['modurl']."&amp;req=search&amp;query=".pnVarPrepForDisplay($query)."&amp;orderby=hitsA\">+</a>/<a href=\"".$GLOBALS['modurl']."&amp;req=search&amp;query=".pnVarPrepForDisplay($query)."&amp;orderby=hitsD\">-</a>)"
                .'<br />'._SITESSORTED.": $orderbyTrans</span><br />";
            while(list($lid, $cid, $title, $url, $description, $time, $hits, $linkratingsummary, $totalvotes, $totalcomments) = $result->fields) {

                $result->MoveNext();
                    if (pnSecAuthAction(0, 'Web Links::Link', ":$title:$lid", ACCESS_READ) && pnSecAuthAction(0, 'Web Links::Category', "::$cid", ACCESS_READ)) {
                        $linkratingsummary = number_format($linkratingsummary, $mainvotedecimal);
                        //$title = stripslashes($title);
                        //$description = stripslashes(pnVarPrepHTMLDisplay($description));
                        //$transfertitle = str_replace (" ", "_", $title);
                        //$title = ereg_replace($query, "<strong>$query</strong>", pnVarPrepForDisplay($title));
                        echo "<br /><a href=\"".$GLOBALS['modurl']."&amp;req=visit&amp;lid=".(int)$lid."\">".pnVarPrepForDisplay($title)."</a>";
                        newlinkgraphic($datetime, $time);
                        popgraphic($hits);
                        echo '<br />';
                        echo ""._CATEGORY.": ".CatPath($cid,1,1,1).'<br />';
                        //$description = ereg_replace($query, "<strong>$query</strong>", $description);
                        //echo ""._WL_DESCRIPTION.": ".pnVarPrepHTMLDisplay($description).'<br />';
						//transform hooks
						list($description) = pnModCallHooks('item', 'transform', '', array($description));
		        		if (pnSecAuthAction(0, 'Web Links::', '::', ACCESS_ADMIN)) {
		            		echo "<a href=\"admin.php?module=Web_Links&amp;op=LinksModLink&amp;lid=$lid\"><img src=\"modules/Web_Links/images/editicon.gif\" border=\"0\" alt=\""._EDITTHISLINK."\"></a>";
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
                            echo " "._RATING.": ".pnVarPrepForDisplay($linkratingsummary)." (".pnVarPrepForDisplay($totalvotes)." ".pnVarPrepForDisplay($votestring).")";
                            //removed show star flag, need to make config var - skooter
                            //if ($web_links_show_star) {
                                echo '<br />'.web_links_rateMakeStar($linkratingsummary, 10);
                            //}
                        }
		                $transfertitle = str_replace (" ", "_", $title);
                        LinksBottomMenu($lid, $transfertitle, $totalvotes, $totalcomments);
                        detecteditorial($lid, $transfertitle);
                        echo '<br />';
                        $x++;
                }
            }
            echo "</span>";
            $orderby = convertorderbyout($orderby);
        } else {
            echo "<br /><div style=\"text-align:center\"><strong>"._NOMATCHES.'</strong><br />'._GOBACK.'</div>';
        }
        /* Calculates how many pages exist.  Which page one should be on, etc... */
        $linkpagesint = ($totalselectedlinks / $linksresults);
        $linkpageremainder = ($totalselectedlinks % $linksresults);
        if ($linkpageremainder != 0) {
            $linkpages = ceil($linkpagesint);
            if ($totalselectedlinks < $linksresults) {
                $linkpageremainder = 0;
            }
        } else {
            $linkpages = $linkpagesint;
        }
        /* Page Numbering */
        if ($linkpages!=1 && $linkpages!=0) {
            echo '<br />'
                ._SELECTPAGE.": ";
            $prev=$min-$linksresults;
            if ($prev>=0) {
                echo "&nbsp;&nbsp;<strong>[ <a href=\"".$GLOBALS['modurl']."&amp;req=search&amp;query=".pnVarPrepForDisplay($query)."&amp;min=".pnVarPrepForDisplay($prev)."&amp;orderby=".pnVarPrepForDisplay($orderby)."&amp;show=".pnVarPrepForDisplay($show)."\">"
                    ." &lt;&lt; "._PREVIOUS."</a> ]</strong> ";
            }
            $counter = 1;
            $currentpage = ($max / $linksresults);
            while ($counter<=$linkpages ) {
                $cpage = $counter;
                //$mintemp = ($perpage * $counter) - $linksresults;
                $mintemp = ($linksresults * $counter) - $linksresults;
                if ($counter == $currentpage) {
                    echo '<strong>'.pnVarPrepForDisplay($counter).'</strong> ';
                } else {
                    echo "<a href=\"".$GLOBALS['modurl']."&amp;req=search&amp;query=".pnVarPrepForDisplay($query)."&amp;min=".pnVarPrepForDisplay($mintemp)."&amp;orderby=".pnVarPrepForDisplay($orderby)."&amp;show=".pnVarPrepForDisplay($show)."\">".pnVarPrepForDisplay($counter)."</a> ";
                }
                $counter++;
            }
            $next=$min+$linksresults;
            if ($x>=$linksresults) {
            //if ($x>=$perpage) {
                echo "&nbsp;&nbsp;<strong>[ <a href=\"".$GLOBALS['modurl']."&amp;req=search&amp;query=".pnVarPrepForDisplay($query)."&amp;min=".pnVarPrepForDisplay($max)."&amp;orderby=".pnVarPrepForDisplay($orderby)."&amp;show=".pnVarPrepForDisplay($show)."\">"
                    ." "._NEXT." &gt;&gt;</a> ]</strong>";
            }
        }
        echo '<br /><div style="text-align:center">'
            ._TRY2SEARCH." \"".pnVarPrepForDisplay($query)."\" "._INOTHERSENGINES.'<br />'
            ."<a href=\"http://www.google.com/search?q=".pnVarPrepForDisplay($query)."\">Google</a> - "
            ."<a href=\"http://www.altavista.com/cgi-bin/query?pg=q&amp;sc=on&amp;hl=on&amp;act=2006&amp;par=0&amp;q=".pnVarPrepForDisplay($query)."&amp;kl=XX&amp;stype=stext\">Alta Vista</a> - "
            ."<a href=\"http://www.hotbot.com/?MT=".pnVarPrepForDisplay($query)."&amp;DU=days&amp;SW=web\">HotBot</a> - "
            ."<a href=\"http://sjc-search.sjc.lycos.com/?query=".pnVarPrepForDisplay($query)."\">Lycos</a> - "
            ."<a href=\"http://search.yahoo.com/bin/search?p=".pnVarPrepForDisplay($query)."\">Yahoo</a> - "
            ."<a href=\"http://web.ask.com/web?q=".pnVarPrepForDisplay($query)."&amp;o=0&amp;qsrc=0\">Ask Jeeves</a>"
            .'</div>';
    } else {
        echo '<div style="text-align:center"><strong>'._NOMATCHES.'</strong></div><br />';
    }
    CloseTable();
    include('footer.php');
}
?>