<?php
// File: $Id: wl-util.php 16525 2005-07-26 22:45:23Z markwest $
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
// Purpose of file: function lib, routines used by many other functions
// ----------------------------------------------------------------------
// 11-30-2001:ahumphr - created file as part of modularistation
// 10-15-2002:skooter      - Cross Site Scripting security fixes and also using 
//                           pnAPI for displaying data.

/**
 * @usedby nothing
 */
function SearchForm()
{
    echo "<form action=\"".$GLOBALS['modurl']."&amp;req=search&query=$query\" method=\"post\">"
    ."<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">"
    ."<tr><td><input type=\"text\" size=\"25\" name=\"query\"> <input type=\"submit\" value=\""._SEARCH."\"></td></tr>"
    ."</table>"
    ."</form>";
}

/**
 * @usedby viewlinkcomments, viewlinkdetails, viewlinkeditorial, completevotefooter
 */
function linkinfomenu($lid, $ttitle){
    //if (pnSecAuthAction(0, 'Web Links::Item', "$cattitle:$ttitle:$lid", ACCESS_COMMENT)) {
	if (commentslink($lid) != 0) {
        echo "<a href=\"".$GLOBALS['modurl']."&amp;req=viewlinkcomments&amp;lid=".pnVarPrepForDisplay($lid)."\">"._LINKCOMMENTS."</a> | ";
    }
    if (detailslink($lid) != 0) {
        echo "<a href=\"".$GLOBALS['modurl']."&amp;req=viewlinkdetails&amp;lid=".pnVarPrepForDisplay($lid)."\">"._ADDITIONALDET."</a> | ";
	}
	if (editoriallink($lid) != 0) {
        echo "<a href=\"".$GLOBALS['modurl']."&amp;req=viewlinkeditorial&amp;lid=".pnVarPrepForDisplay($lid)."\">"._EDITORREVIEW."</a> | ";
	}
	if (pnSecAuthAction(0, 'Web Links::Category', '::', ACCESS_COMMENT) || pnSecAuthAction(0, 'Web Links::Link', '::$lid', ACCESS_COMMENT)) {
	    echo "<a href=\"".$GLOBALS['modurl']."&amp;req=modifylinkrequest&amp;lid=".pnVarPrepForDisplay($lid)."\">"._MODIFY."</a> | ";
		echo "<a href=\"".$GLOBALS['modurl']."&amp;req=brokenlink&amp;lid=".pnVarPrepForDisplay($lid)."\">"._REPORTBROKEN."</a>";
	}
}

/**
 * @usedby linkinfomenu
 */
function commentslink($lid) {
    if ($GLOBALS['req'] == 'viewlinkcomments') {
        return 0;
    }
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $column = &$pntable['links_votedata_column'];

    $result =& $dbconn->Execute("SELECT count($column[ratingcomments])
                                FROM   $pntable[links_votedata]
                                WHERE  $column[ratinglid]='".(int)pnVarPrepForStore($lid)."'");
    list($count) = $result->fields;
    return $count;
}

/**
 * @usedby linkinfomenu
 */
function detailslink($lid) {
    if ($GLOBALS['req'] == 'viewlinkdetails') {
        return 0;
    }
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $column = &$pntable['links_links_column'];

    $result =& $dbconn->Execute("SELECT $column[totalvotes]
                                FROM   $pntable[links_links]
                                WHERE  $column[lid]='".(int)pnVarPrepForStore($lid)."'");
    list($votes) = $result->fields;
    return $votes;
}

/**
 * @usedby linkinfomenu
 */
function editoriallink($lid) {
    if ($GLOBALS['req'] == 'viewlinkeditorial') {
        return 0;
    }
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $column = &$pntable['links_editorials_column'];

    $result =& $dbconn->Execute("SELECT count(*)
                                FROM $pntable[links_editorials]
                                WHERE $column[linkid]='".(int)pnVarPrepForStore($lid)."'");
    list($count) = $result->fields;
    return $count;
}

/**
 * @usedby viewlinkcomments, viewlinkdetails, viewlinkeditorial, ratelink
 */
function displaytitle($lid) {
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $column = &$pntable['links_links_column'];

    $result =& $dbconn->Execute("SELECT $column[title]
                                FROM   $pntable[links_links]
                                WHERE  $column[lid]='".(int)pnVarPrepForStore($lid)."'");
    list($title) = $result->fields;
    return $title;
}

/**
 * @usedby mostpopular, search, toprated, viewlink
 */
function detecteditorial($lid, $ttitle)
{

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $column = &$pntable['links_editorials_column'];
    
    $result =& $dbconn->Execute("SELECT count(*) FROM $pntable[links_editorials] WHERE $column[linkid]='".(int)pnVarPrepForStore($lid)."'");
    list($recordexist) = $result->fields;
    if ($recordexist != 0) {
        echo " | <a href=\"".$GLOBALS['modurl']."&amp;req=viewlinkeditorial&amp;lid=".pnVarPrepForDisplay($lid)."\">"._EDITORIAL."</a>";
    }
}

/**
 * @usedby mostpopular, search, toprated, viewlink
 */
function popgraphic($hits) {

    if ($hits>=pnConfigGetVar('popular')) {
        echo "&nbsp;<img src=\"modules/Web_Links/images/pop.gif\" alt=\""._POPULAR."\" />";
    }
}

/**
 * @usedby search, viewlink
 */
function convertorderbyin($orderby)
{
    $pntable =& pnDBGetTables();
    $column = &$pntable['links_links_column'];

    if ($orderby == "titleA") {
	$orderby = "$column[title] ASC";
    } else
    if ($orderby == "dateA") {
	$orderby = "$column[date] ASC";
    } else
    if ($orderby == "hitsA") {
	$orderby = "$column[hits] ASC";
    } else
    if ($orderby == "ratingA") {
	$orderby = "$column[linkratingsummary] ASC";
    } else
    if ($orderby == "titleD") {
	$orderby = "$column[title] DESC";
    } else
    if ($orderby == "dateD") {
	$orderby = "$column[date] DESC";
    } else
    if ($orderby == "hitsD") {
	$orderby = "$column[hits] DESC";
    } else
    if ($orderby == "ratingD") {
	$orderby = "$column[linkratingsummary] DESC";
    } else {
	$orderby = "$column[title] ASC";
	}
    return $orderby;
}

/**
 *
 * @usedby search, viewlink
 */
function convertorderbytrans($orderby)
{
    $pntable =& pnDBGetTables();
    $column = &$pntable['links_links_column'];
    $orderbyTrans = "";
	
    if ($orderby == "$column[hits] ASC") {
	$orderbyTrans = ""._POPULARITY1."";
    }
    if ($orderby == "$column[hits] DESC") {
	$orderbyTrans = ""._POPULARITY2."";
    }
    if ($orderby == "$column[title] ASC") {
	$orderbyTrans = ""._TITLEAZ."";
    }
    if ($orderby == "$column[title] DESC") {
	$orderbyTrans = ""._TITLEZA."";
    }
    if ($orderby == "$column[date] ASC") {
	$orderbyTrans = ""._DATE1."";
    }
    if ($orderby == "$column[date] DESC") {
	$orderbyTrans = ""._DATE2."";
    }
    if ($orderby == "$column[linkratingsummary] ASC") {
	$orderbyTrans = ""._RATING1."";
    }
    if ($orderby == "$column[linkratingsummary] DESC") {
	$orderbyTrans = ""._RATING2."";
    }
    return $orderbyTrans;
}

/**
 * @usedby viewlink, search,
 */
function convertorderbyout($orderby)
{
    $pntable =& pnDBGetTables();

    $column = &$pntable['links_links_column'];
    if ($orderby == "$column[title] ASC") {
	$orderby = "titleA";
    }
    if ($orderby == "$column[date] ASC") {
	$orderby = "dateA";
    }
    if ($orderby == "$column[hits] ASC") {
	$orderby = "hitsA";
    }
    if ($orderby == "$column[linkratingsummary] ASC") {
	$orderby = "ratingA";
    }
    if ($orderby == "$column[title] DESC") {
	$orderby = "titleD";
    }
    if ($orderby == "$column[date] DESC") {
	$orderby = "dateD";
    }
    if ($orderby == "$column[hits] DESC") {
	$orderby = "hitsD";
    }
    if ($orderby == "$column[linkratingsummary] DESC") {
	$orderby = "ratingD";
    }
    return $orderby;
}

/**
 * @usedby index, mostpopular, serach, toprated, viewlink
 */
function visit($lid)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $column = &$pntable['links_links_column'];
    
    $dbconn->Execute("UPDATE $pntable[links_links] 
                    SET $column[hits]=$column[hits]+1 
                    WHERE $column[lid]='".(int)pnVarPrepForStore($lid)."'");
    $result =& $dbconn->Execute("SELECT $column[url] 
                    FROM $pntable[links_links] 
                    WHERE $column[lid]='".(int)pnVarPrepForStore($lid)."'");
    list($url) = $result->fields;
    Header('HTTP/1.1 301 Moved Permanently'); 
    Header('Location: '.$url);
}

/**
 * @usedby index, search, viewlink
 */
function CountSubLinks($cid)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $column = &$pntable['links_links_column'];

    $ct=0;
    $result =& $dbconn->Execute("SELECT COUNT(*) FROM $pntable[links_links] 
                    WHERE $column[cat_id]='".(int)pnVarPrepForStore($cid)."'");
    list($ct) = $result->fields;

    // Now get all child nodes
    $column = &$pntable['links_categories_column'];
    $result =& $dbconn->Execute("SELECT $column[cat_id] FROM $pntable[links_categories] 
                    WHERE $column[parent_id]='".(int)pnVarPrepForStore($cid)."'");
    while(list($sid)=$result->fields) {

        $result->MoveNext();
        $ct+=CountSubLinks($sid);
    }
  return $ct;
}

/* Link Graphics */

/**
 * categorynewlinkgraphic
 * @usedby index, viewlink
 */
function categorynewlinkgraphic($cat)
{

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $column = &$pntable['links_links_column'];

    $locale = pnConfigGetVar('locale');

    //$query = buildSimpleQuery ('links_links', array ('date'), "$column[cat_id]=$cat", "$column[date] DESC", 1);
    //$newresult =& $dbconn->Execute($query);
	$query = "SELECT $column[date]
				FROM $pntable[links_links]
				WHERE $column[cat_id]= '".(int)pnVarPrepForStore($cat)."'
				ORDER BY $column[date] DESC";
	$newresult = $dbconn->SelectLimit($query, 1);
    list($time)=$newresult->fields;
    if (!$time) return;
    echo "&nbsp;";
    ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime);
    $datetime = ml_ftime(""._LINKSDATESTRING."", mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
    $datetime = ucfirst($datetime);
    $startdate = time();
    $count = 0;

    while ($count <= 7)
    {
        $daysold = ml_ftime(""._LINKSDATESTRING."", $startdate);
        if ("$daysold" == "$datetime") {
            if ($count<=1) {
              echo "<img src=\"modules/Web_Links/images/newred.gif\" width=\"34\" height=\"15\" alt=\""._CATNEWTODAY."\" />";
            }
            if ($count<=3 && $count>1) {
              echo "<img src=\"modules/Web_Links/images/newgreen.gif\" width=\"34\" height=\"15\" alt=\""._CATLAST3DAYS."\" />";
            }
            if ($count<=7 && $count>3) {
              echo "<img src=\"modules/Web_Links/images/newblue.gif\" width=\"34\" height=\"15\" alt=\""._CATTHISWEEK."\" />";
            }
        }
        $count++;
        $startdate = (time()-(86400 * $count));
    }
}

/**
 * newlinkgraphic
 * @usedby mostpopular, search
 */
function newlinkgraphic($datetime, $time) {

    $locale = pnConfigGetVar('locale');

    echo "&nbsp;";
    ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime);
    $datetime = ml_ftime(""._LINKSDATESTRING."", mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
    $datetime = ucfirst($datetime);
    $startdate = time();
    $count = 0;
    while ($count <= 7)
    {
        $daysold = ml_ftime(""._LINKSDATESTRING."", $startdate);
        if ("$daysold" == "$datetime") {
            if ($count<=1) {
                echo "<img src=\"modules/Web_Links/images/newred.gif\" width=\"34\" height=\"15\" alt=\""._NEWTODAY."\" />&nbsp;";
            }
            if ($count<=3 && $count>1) {
                echo "<img src=\"modules/Web_Links/images/newgreen.gif\" width=\"34\" height=\"15\" alt=\""._NEWLAST3DAYS."\" />&nbsp;";
            }
            if ($count<=7 && $count>3) {
                echo "<img src=\"modules/Web_Links/images/newblue.gif\" width=\"34\" height=\"15\" alt=\""._NEWTHISWEEK."\" />&nbsp;";
            }
        }
        $count++;
        $startdate = (time()-(86400 * $count));
    }
}

/**
 * subcategorynewlinkgraphic
 *
 * PostNuke mod --  Create a the new function for generating the 'new' graphics for
 * sub-categoires, based on the post-nuke categorynewlinkgraphic($cid)
 * @usedby index, viewlink
 */

function subcategorynewlinkgraphic($sid) 
{

    $locale = pnConfigGetVar('locale');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $column = &$pntable['links_links_column'];
    
    //$query = buildSimpleQuery ('links_links', array ('date'), "$column[cat_id]=".(int)pnVarPrepForStore($sid)."", "$column[date] DESC");
	$query = "SELECT $column[date]
				FROM $pntable[links_links]
				WHERE $column[cat_id]= '".(int)pnVarPrepForStore($sid)."'
				ORDER BY $column[date] DESC";
	$newresult = $dbconn->SelectLimit($query, 1);
    list($time)=$newresult->fields;

    if (!$time) return;
    echo "&nbsp;";
    ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime);
    $datetime = ml_ftime(""._LINKSDATESTRING."", mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
    $datetime = ucfirst($datetime);
    $startdate = time();
    $count = 0;

    while ($count <= 7) {
        $daysold = ml_ftime(""._LINKSDATESTRING."", $startdate);
        if ("$daysold" == "$datetime") {
        if ($count<=1) {
        echo "<img src=\"modules/Web_Links/images/newred.gif\" width=\"34\" height=\"15\" alt=\""._CATNEWTODAY."\" />&nbsp;";
    }
            if ($count<=3 && $count>1) {
        echo "<img src=\"modules/Web_Links/images/newgreen.gif\" width=\"34\" height=\"15\" alt=\""._CATLAST3DAYS."\" />&nbsp;";
    }
            if ($count<=7 && $count>3) {
        echo "<img src=\"modules/Web_Links/images/newblue.gif\" width=\"34\" height=\"15\" alt=\""._CATTHISWEEK."\" />&nbsp;";
    }
}
        $count++;
        $startdate = (time()-(86400 * $count));
    }
}

/**
 * @usedby mostpopular, search, toprated, viewlink
 */
function LinksBottomMenu($lid, $transfertitle, $totalvotes, $totalcomments){
	echo '<br />';
	if (pnSecAuthAction(0, 'Web Links::Category', '::', ACCESS_COMMENT) || pnSecAuthAction(0, 'Web Links::Link', '::$lid', ACCESS_COMMENT)) {
        echo "<a href=\"".$GLOBALS['modurl']."&amp;req=ratelink&amp;lid=".pnVarPrepForDisplay($lid)."\">"._RATESITE."</a>";
        echo " | <a href=\"".$GLOBALS['modurl']."&amp;req=modifylinkrequest&amp;lid=".pnVarPrepForDisplay($lid)."\">"._MODIFY."</a> | ";
    }
    echo "<a href=\"".$GLOBALS['modurl']."&amp;req=brokenlink&amp;lid=".pnVarPrepForDisplay($lid)."\">"._REPORTBROKEN."</a>";
    if (pnSecAuthAction(0, 'Web Links::Category', '::', ACCESS_READ) || pnSecAuthAction(0, 'Web Links::Link', '::$lid', ACCESS_READ)) {
        // the votes check should not be here as the details link needs to be accessable for hook calls
		// even when there are no votes present.
		// Weblinks works slightly differently to downloads which has this link in the category listing
		// in dl-categories.php not the equivalent of this function. The equivalent weblinks function
		// is never called - markwest
     	//if ($totalvotes != 0) {
			echo " | <a href=\"".$GLOBALS['modurl']."&amp;req=viewlinkdetails&amp;lid=".pnVarPrepForDisplay($lid)."\">"._DETAILS."</a>";
        //}
    }
    if (pnSecAuthAction(0, 'Web Links::Category', '::', ACCESS_READ) || pnSecAuthAction(0, 'Web Links::Link', '::$lid', ACCESS_READ)) {
        if ($totalcomments != 0) {
           echo " | <a href=\"".$GLOBALS['modurl']."&amp;req=viewlinkcomments&amp;lid=".pnVarPrepForDisplay($lid)."\">"._COMMENTS." (".pnVarPrepForDisplay($totalcomments).")</a>";
        }
    }
}

function web_links_rateMakeStar($score, $max_score)
{
    // this code is take from harpia project http://sourceforge.net/projects/harpia

    $score /= 2;    $max_score /=2; //      5 stars. comment for 10 stars
    $basedir="modules/Web_Links/images/" ;   // for $basedir/image/xxx.gif
    $rateImgFull = $basedir.'rate_full.gif';
    $rateImgHalf = $basedir.'rate_half.gif';
    $rateImgNone = $basedir.'rate_none.gif';

    // Break up score
    if (strpos($score,".")==0){
        $full_stars=$score;
    }else{
        $full_stars=substr($score,0,strpos($score,"."));
    }

    // *** Is there half star
    if (substr($score,strpos($score,".")+1)==0){
        $half_stars=0;
    }else{
        $half_stars=1;
    }

    // *** Build Star Line
    $blank_stars=$max_score-($full_stars+$half_stars);
    $star_line="";
    for ($i=1;$i<=$max_score;$i++){
        if ($i<=$full_stars){
            $star_line.='<img src="'.$rateImgFull.'" alt="" />';
        }elseif ($i<=($half_stars+$full_stars)){
            $star_line.='<img src="'.$rateImgHalf.'" alt="" />';
        }elseif ($i<=$max_score){
            $star_line.='<img src="'.$rateImgNone.'" alt="" />';
        }
    }
    return $star_line;
}

/**
 * @usedby viewlinkcomments, viewlinkdetails, viewlinkeditorial
 */
function linkfooter($lid,$ttitle) {
    echo "[ <a href=\"".$GLOBALS['modurl']."&amp;req=visit&amp;lid=".pnVarPrepForDisplay($lid)."\">"._VISITTHISSITE."</a>";
	if (pnSecAuthAction(0, 'Web Links::Category', '::', ACCESS_COMMENT) || pnSecAuthAction(0, 'Web Links::Link', '::$lid', ACCESS_COMMENT)) {
		echo " | <a href=\"".$GLOBALS['modurl']."&amp;req=ratelink&amp;lid=".pnVarPrepForDisplay($lid)."\">"._RATETHISSITE."</a>";
	}
 	echo "]<br />";
    linkfooterchild($lid);
}

/**
 * @usedby linkfooter, ratelink
 */
function linkfooterchild($lid) {

    if(pnConfigGetVar('useoutsidevoting') == 1) {
        echo '<br />'._ISTHISYOURSITE." <a href=\"".$GLOBALS['modurl']."&amp;req=outsidelinksetup&amp;lid=".pnVarPrepForDisplay($lid)."\">"._ALLOWTORATE."</a>";
    }
}

function calculateVote($voteresult, $totalvotesDB)
{
	$anonvotes = 0;
	$anonvoteval = 0;
	$outsidevotes = 0;
	$outsidevoteval = 0;
	$regvoteval = 0;
	$truecomments = $totalvotesDB;
	
	$anonweight = pnConfigGetVar('anonweight');
	$anonymous = pnConfigGetVar('anonymous');
	$outsideweight = pnConfigGetVar('outsideweight');
	$useoutsidevoting = pnConfigGetVar('useoutsidevoting');
	
	
	while(list($ratingDB, $ratinguserDB, $ratingcommentsDB) = $voteresult->fields) {
		$voteresult->MoveNext();
		if ($ratingcommentsDB == "") {
			--$truecomments;
		}
		if ($ratinguserDB == $anonymous) {
			$anonvotes++;
			$anonvoteval += $ratingDB;
		}
		if ($useoutsidevoting == 1) {
			if ($ratinguserDB == 'outside') {
				++$outsidevotes;
				$outsidevoteval += $ratingDB;
			}
		} else {
			$outsidevotes = 0;
		}
		if ($ratinguserDB != $anonymous && $ratinguserDB != "outside") {
			$regvoteval += $ratingDB;
		}
	}
	
	$regvotes = $totalvotesDB - $anonvotes - $outsidevotes;
	
	if ($totalvotesDB == 0) {
		$finalrating = 0;
	} else if ($anonvotes == 0 && $regvotes == 0) {
		/* Figure Outside Only Vote */
		$finalrating = $outsidevoteval / $outsidevotes;
		$finalrating = number_format($finalrating, 4);
	} else if ($outsidevotes == 0 && $regvotes == 0) {
		/* Figure Anon Only Vote */
		$finalrating = $anonvoteval / $anonvotes;
		$finalrating = number_format($finalrating, 4);
	} else if ($outsidevotes == 0 && $anonvotes == 0) {
		/* Figure Reg Only Vote */
		$finalrating = $regvoteval / $regvotes;
		$finalrating = number_format($finalrating, 4);
	} else if ($regvotes == 0 && $useoutsidevoting == 1 && $outsidevotes != 0 && $anonvotes != 0 ) {
		/* Figure Reg and Anon Mix */
		$avgAU = $anonvoteval / $anonvotes;
		$avgOU = $outsidevoteval / $outsidevotes;
		if ($anonweight > $outsideweight ) {
			/* Anon is 'standard weight' */
			$newimpact = $anonweight / $outsideweight;
			$impactAU = $anonvotes;
			$impactOU = $outsidevotes / $newimpact;
			$finalrating = ((($avgOU * $impactOU) + ($avgAU * $impactAU)) / ($impactAU + $impactOU));
			$finalrating = number_format($finalrating, 4);
		} else {
			/* Outside is 'standard weight' */
			$newimpact = $outsideweight / $anonweight;
			$impactOU = $outsidevotes;
			$impactAU = $anonvotes / $newimpact;
			$finalrating = ((($avgOU * $impactOU) + ($avgAU * $impactAU)) / ($impactAU + $impactOU));
			$finalrating = number_format($finalrating, 4);
		}
	} else {
		/* Registered User vs. Anonymous vs. Outside User Weight Calutions */
		$impact = $anonweight;
		$outsideimpact = $outsideweight;
		if ($regvotes == 0) {
			$regvotes = 0;
		} else {
			$avgRU = $regvoteval / $regvotes;
		}
		if ($anonvotes == 0) {
			$avgAU = 0;
		} else {
			$avgAU = $anonvoteval / $anonvotes;
		}
		if ($outsidevotes == 0 ) {
			$avgOU = 0;
		} else {
			$avgOU = $outsidevoteval / $outsidevotes;
		}
	
		$impactRU = $regvotes;
		$impactAU = $anonvotes / $impact;
		$impactOU = $outsidevotes / $outsideimpact;
		$finalrating = (($avgRU * $impactRU) + ($avgAU * $impactAU) + ($avgOU * $impactOU)) / ($impactRU + $impactAU + $impactOU);
		$finalrating = number_format($finalrating, 4);
	}
    return $finalrating;
}
?>