<?php
// File: $Id: wl-linkdetails.php 19178 2006-06-01 13:00:27Z markwest $
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
 * @usedby index
 */
function viewlinkcomments($lid) {
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    include('header.php');
    menu(1);
    echo '<br />';
    $column = &$pntable['links_votedata_column'];
    $result =& $dbconn->Execute("SELECT $column[ratinguser], $column[rating], $column[ratingcomments], $column[ratingtimestamp] FROM $pntable[links_votedata] WHERE $column[ratinglid]='".(int)pnVarPrepForStore($lid)."' AND $column[ratingcomments] != '' ORDER BY $column[ratingtimestamp] DESC");
    $totalcomments = $result->PO_RecordCount();
    $displaytitle = displaytitle($lid);
    OpenTable();
    echo '<div style="text-align:center"><strong>'._LINKPROFILE.": ".pnVarPrepForDisplay($displaytitle).'</strong><br />';
    linkinfomenu($lid, displaytitle($lid));
    echo '<br />'._TOTALOF." ".pnVarPrepForDisplay($totalcomments)." "._COMMENTS.'</div><br />';
    $x=0;
    while(list($ratinguser, $rating, $ratingcomments, $ratingtimestamp)=$result->fields) {

        $result->MoveNext();
        /* Individual user information */
        $column = &$pntable['links_votedata_column'];
        $result2 =& $dbconn->Execute("SELECT SUM($column[rating]), COUNT(*) FROM $pntable[links_votedata] WHERE $column[ratinguser]='".pnVarPrepForStore($ratinguser)."'");
        list($useravgrating, $usertotalcomments)=$result2->fields;
        $useravgrating = $useravgrating / $usertotalcomments;
        $useravgrating = number_format($useravgrating, 1);
       echo "<strong> "._USER.": </strong>";
       if (!(pnSecAuthAction(0, 'Web Links::', '::', ACCESS_COMMENT))) {
       	echo pnVarPrepForDisplay($ratinguser);
       } else {
       	echo "<a href=\"" . pnGetBaseURL() . "user.php?op=userinfo&amp;uname=".pnVarPrepForDisplay($ratinguser)."\">".pnVarPrepForDisplay($ratinguser)."</a>";
       }
       echo "<br /><strong>"._RATING.": </strong>".pnVarPrepForDisplay($rating)
           ."&nbsp;/&nbsp;".pnVarPrepForDisplay($ratingtimestamp)
           .'<br />'
           ."<span class=\"pn-sub\">"._USERAVGRATING.": ".pnVarPrepForDisplay($useravgrating)
		   ."&nbsp;/&nbsp;"
           ._NUMRATINGS.": ".pnVarPrepForDisplay($usertotalcomments)."</span>"
		   .'<br />';
        if (pnSecAuthAction(0, 'Web Links::', '::', ACCESS_ADMIN)) {
            echo "<a href=\"admin.php?module=Web_Links&amp;op=LinksModLink&amp;lid=$lid\"><img src=\"modules/Web_Links/images/editicon.gif\" alt=\""._EDITTHISLINK."\" /></a>";
        }
		//transform hooks
		list($ratingcomments) = pnModCallHooks('item', 'transform', '', array($ratingcomments));
        echo " ".pnVarPrepHTMLDisplay($ratingcomments).'<br />';
        $x++;
    }
    echo '<br /><div style="text-align:center">';
    linkfooter($lid,displaytitle($lid));
    echo '</div>';
    CloseTable();
    include('footer.php');
}

/**
 * @usedby index
 */
function viewlinkdetails($lid) {

    include('header.php');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $displaytitle = displaytitle($lid);
    $column = &$pntable['links_links_column'];
	$column2 = &$pntable['links_categories_column'];
	$sql = "SELECT $column[cat_id], $column[description], $column2[title]
                           FROM $pntable[links_links], $pntable[links_categories]
                           WHERE $column[lid]='".pnVarPrepForStore($lid)."'
						   AND $column[cat_id]=$column2[cat_id]";
    $res =& $dbconn->Execute($sql);
    list($cid, $description, $title) = $res->fields;

    if (!pnSecAuthAction(0, 'Web Links::Category', "$title::$cid" , ACCESS_READ)) {
		echo _BADAUTHKEY;
		include 'footer.php';
		return;
	}

    $useoutsidevoting = pnConfigGetVar('useoutsidevoting');
    $anonymous = pnConfigGetVar('anonymous');
    $detailvotedecimal = pnConfigGetVar('detailvotedecimal');
    $anonweight = pnConfigGetVar('anonweight');
    $outsideweight = pnConfigGetVar('anonweight');

    menu(1);
    $column = &$pntable['links_votedata_column'];
    $voteresult =& $dbconn->Execute("SELECT $column[rating], $column[ratinguser],
                                  $column[ratingcomments]
                                  FROM $pntable[links_votedata]
                                  WHERE $column[ratinglid]='".(int)pnVarPrepForStore($lid)."'");
    $totalvotesDB = $voteresult->PO_RecordCount();
    $anonvotes = 0;
    $anonvoteval = 0;
    $outsidevotes = 0;
    $outsidevoteeval = 0;
    $regvoteval = 0;
    $topanon = 0;
    $bottomanon = 11;
    $topreg = 0;
    $bottomreg = 11;
    $topoutside = 0;
    $bottomoutside = 11;
    $avv = array(0,0,0,0,0,0,0,0,0,0,0);
    $rvv = array(0,0,0,0,0,0,0,0,0,0,0);
    $ovv = array(0,0,0,0,0,0,0,0,0,0,0);
    $truecomments = $totalvotesDB;
    while(list($ratingDB, $ratinguserDB, $ratingcommentsDB) = $voteresult->fields) {

        $voteresult->MoveNext();
        if ($ratingcommentsDB==""){
            $truecomments--;
        }
        if ($ratinguserDB==pnConfigGetVar("anonymous")) {
            $anonvotes++;
            $anonvoteval += $ratingDB;
        }
        if (isset($useoutsidevoting) && $useoutsidevoting == 1) {
            if ($ratinguserDB=='outside') {
                $outsidevotes++;
                $outsidevoteval += $ratingDB;
            }
        } else {
            $outsidevotes = 0;
        }
        if ($ratinguserDB != pnConfigGetVar('anonymous') && $ratinguserDB!="outside") {
            $regvoteval += $ratingDB;
        }
        if ($ratinguserDB != pnConfigGetVar('anonymous') && $ratinguserDB!="outside") {
            if ($ratingDB > $topreg) {
                $topreg = $ratingDB;
            }
            if ($ratingDB < $bottomreg) {
                $bottomreg = $ratingDB;
            }
            for ($rcounter=1; $rcounter<11; $rcounter++) {
                if ($ratingDB==$rcounter) {
                    $rvv[$rcounter]++;
                }
            }
        }
        if ($ratinguserDB==pnConfigGetVar("anonymous")) {
            if ($ratingDB > $topanon) {
                $topanon = $ratingDB;
            }
            if ($ratingDB < $bottomanon) {
                $bottomanon = $ratingDB;
            }
            for ($rcounter=1; $rcounter<11; $rcounter++) {
                if ($ratingDB==$rcounter) {
                    $avv[$rcounter]++;
                }
            }
        }
        if ($ratinguserDB=="outside") {
            if ($ratingDB > $topoutside) {
                $topoutside = $ratingDB;
            }
            if ($ratingDB < $bottomoutside) {
                $bottomoutside = $ratingDB;
            }
            for ($rcounter=1; $rcounter<11; $rcounter++) {
                if ($ratingDB==$rcounter) {
                    $ovv[$rcounter]++;
                }
            }
        }
    }
    $regvotes = $totalvotesDB - $anonvotes - $outsidevotes;
    $avgRU = 0;
    $avgAU = 0;
    $avgOU = 0;
    if ($totalvotesDB == 0) {
        $finalrating = 0;
    } else if ($anonvotes == 0 && $regvotes == 0) {
        /* Figure Outside Only Vote */
        $finalrating = $outsidevoteval / $outsidevotes;
        $finalrating = number_format($finalrating, $detailvotedecimal);
        $avgOU = $outsidevoteval / $totalvotesDB;
        $avgOU = number_format($avgOU, $detailvotedecimal);
    } else if ($outsidevotes == 0 && $regvotes == 0) {
        /* Figure Anon Only Vote */
        $finalrating = $anonvoteval / $anonvotes;
        $finalrating = number_format($finalrating, $detailvotedecimal);
        $avgAU = $anonvoteval / $totalvotesDB;
        $avgAU = number_format($avgAU, $detailvotedecimal);
    } else if ($outsidevotes == 0 && $anonvotes == 0) {
        /* Figure Reg Only Vote */
        $finalrating = $regvoteval / $regvotes;
        $finalrating = number_format($finalrating, $detailvotedecimal);
        $avgRU = $regvoteval / $totalvotesDB;
        $avgRU = number_format($avgRU, $detailvotedecimal);
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
            $finalrating = number_format($finalrating, $detailvotedecimal);
        } else {
            /* Outside is 'standard weight' */
            $newimpact = $outsideweight / $anonweight;
            $impactOU = $outsidevotes;
            $impactAU = $anonvotes / $newimpact;
            $finalrating = ((($avgOU * $impactOU) + ($avgAU * $impactAU)) / ($impactAU + $impactOU));
            $finalrating = number_format($finalrating, $detailvotedecimal);
        }
    } else {
        /* REG User vs. Anonymous vs. Outside User Weight Calutions */
        $impact = $anonweight;
        $outsideimpact = $outsideweight;
        if ($regvotes == 0) {
            $avgRU = 0;
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
        $finalrating = number_format($finalrating, $detailvotedecimal);
    }
    if (!isset($avgOU) || $avgOU == 0 || $avgOU == "") {
        $avgOU = "";
    } else {
        $avgOU = number_format($avgOU, $detailvotedecimal);
    }
    if ($avgRU == 0 || $avgRU == "") {
        $avgRU = "";
    } else {
        $avgRU = number_format($avgRU, $detailvotedecimal);
    }
    if (!isset($avgAU) || $avgAU == 0 || $avgAU == "") {
        $avgAU = "";
    } else {
        $avgAU = number_format($avgAU, $detailvotedecimal);
    }
    if ($topanon == 0) $topanon = "";
    if ($bottomanon == 11) $bottomanon = "";
    if ($topreg == 0) $topreg = "";
    if ($bottomreg == 11) $bottomreg = "";
    if ($topoutside == 0) $topoutside = "";
    if ($bottomoutside == 11) $bottomoutside = "";
    $totalchartheight = 70;
    $chartunits = $totalchartheight / 10;
    $avvper     = array(0,0,0,0,0,0,0,0,0,0,0);
    $rvvper         = array(0,0,0,0,0,0,0,0,0,0,0);
    $ovvper         = array(0,0,0,0,0,0,0,0,0,0,0);
    $avvpercent     = array(0,0,0,0,0,0,0,0,0,0,0);
    $rvvpercent     = array(0,0,0,0,0,0,0,0,0,0,0);
    $ovvpercent     = array(0,0,0,0,0,0,0,0,0,0,0);
    $avvchartheight = array(0,0,0,0,0,0,0,0,0,0,0);
    $rvvchartheight = array(0,0,0,0,0,0,0,0,0,0,0);
    $ovvchartheight = array(0,0,0,0,0,0,0,0,0,0,0);
    $avvmultiplier = 0;
    $rvvmultiplier = 0;
    $ovvmultiplier = 0;
    for ($rcounter=1; $rcounter<11; $rcounter++) {
        if ($anonvotes != 0) $avvper[$rcounter] = $avv[$rcounter] / $anonvotes;
        if ($regvotes != 0) $rvvper[$rcounter] = $rvv[$rcounter] / $regvotes;
        if ($outsidevotes != 0) $ovvper[$rcounter] = $ovv[$rcounter] / $outsidevotes;
        $avvpercent[$rcounter] = number_format($avvper[$rcounter] * 100, 1);
        $rvvpercent[$rcounter] = number_format($rvvper[$rcounter] * 100, 1);
        $ovvpercent[$rcounter] = number_format($ovvper[$rcounter] * 100, 1);
        if ($avv[$rcounter] > $avvmultiplier) $avvmultiplier = $avv[$rcounter];
        if ($rvv[$rcounter] > $rvvmultiplier) $rvvmultiplier = $rvv[$rcounter];
        if ($ovv[$rcounter] > $ovvmultiplier) $ovvmultiplier = $ovv[$rcounter];
    }
    if ($avvmultiplier != 0) $avvmultiplier = 10 / $avvmultiplier;
    if ($rvvmultiplier != 0) $rvvmultiplier = 10 / $rvvmultiplier;
    if ($ovvmultiplier != 0) $ovvmultiplier = 10 / $ovvmultiplier;
    for ($rcounter=1; $rcounter<11; $rcounter++) {
        $avvchartheight[$rcounter] = ($avv[$rcounter] * $avvmultiplier) * $chartunits;
        $rvvchartheight[$rcounter] = ($rvv[$rcounter] * $rvvmultiplier) * $chartunits;
        $ovvchartheight[$rcounter] = ($ovv[$rcounter] * $ovvmultiplier) * $chartunits;
        if ($avvchartheight[$rcounter]==0) $avvchartheight[$rcounter]=1;
        if ($rvvchartheight[$rcounter]==0) $rvvchartheight[$rcounter]=1;
        if ($ovvchartheight[$rcounter]==0) $ovvchartheight[$rcounter]=1;
    }

	//transform hooks
	list($description) = pnModCallHooks('item', 'transform', '', array($description));
    $GLOBALS['info']['title'] = pnVarPrepForDisplay($displaytitle);

    OpenTable();
	echo '<h2>'._LINKPROFILE.": ".pnVarPrepForDisplay($displaytitle)."</h2><br />"
     	._CATEGORY.": ".CatPath($cid,1,1,1).'<br />';
    if (pnSecAuthAction(0, 'Web Links::', '::', ACCESS_ADMIN)) {
        echo "<a href=\"admin.php?module=Web_Links&amp;op=LinksModLink&amp;lid=$lid\"><img src=\"modules/Web_Links/images/editicon.gif\" alt=\""._EDITTHISLINK."\" /></a>";
    }
	echo pnVarPrepHTMLDisplay($description).'<br />';
    linkinfomenu($lid, displaytitle($lid));
    echo '<br />'._LINKRATINGDET.'<br />'
        ._TOTALVOTES." ".pnVarPrepForDisplay($totalvotesDB).'<br />'
        ._OVERALLRATING.": ".pnVarPrepForDisplay($finalrating).'<br />';
	if ($regvotes>0) {
    echo "<table><tr><td colspan=\"2\" style=\"background-color:".$GLOBALS['bgcolor2']."\">"
        .'<strong>'._REGISTEREDUSERS.'</strong>'
        ."</td></tr>"
        ."<tr>"
        ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\">"
        ._NUMBEROFRATINGS.": ".pnVarPrepForDisplay($regvotes)
        ."</td>"
        ."<td rowspan=\"5\" style=\"width:200px\">";
    if ($regvotes==0) {
        echo "<div style=\"text-align:center\">"._NOREGUSERSVOTES.'</div>';
    } else {
        echo "<table border=\"1\" width=\"200\">"
            ."<tr>"
            ."<td valign=\"top\" align=\"center\" colspan=\"10\" style=\"background-color:".$GLOBALS['bgcolor2']."\"><span class=\"pn-sub\">"._BREAKDOWNBYVAL."</span></td>"
            ."</tr>"
            ."<tr>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img alt=\"$rvv[1] "._LVOTES." ($rvvpercent[1]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$rvvchartheight[1]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img alt=\"$rvv[2] "._LVOTES." ($rvvpercent[2]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$rvvchartheight[2]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img alt=\"$rvv[3] "._LVOTES." ($rvvpercent[3]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$rvvchartheight[3]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img alt=\"$rvv[4] "._LVOTES." ($rvvpercent[4]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$rvvchartheight[4]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img alt=\"$rvv[5] "._LVOTES." ($rvvpercent[5]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$rvvchartheight[5]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img alt=\"$rvv[6] "._LVOTES." ($rvvpercent[6]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$rvvchartheight[6]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img alt=\"$rvv[7] "._LVOTES." ($rvvpercent[7]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$rvvchartheight[7]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img alt=\"$rvv[8] "._LVOTES." ($rvvpercent[8]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$rvvchartheight[8]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img alt=\"$rvv[9] "._LVOTES." ($rvvpercent[9]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$rvvchartheight[9]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img alt=\"$rvv[10] "._LVOTES." ($rvvpercent[10]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$rvvchartheight[10]\" /></td>"
            ."</tr>"
            ."<tr><td colspan=\"10\" style=\"background-color:".$GLOBALS['bgcolor2']."\">"
            ."<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"200\"><tr>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">1</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">2</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">3</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">4</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">5</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">6</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">7</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">8</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">9</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">10</span></td>"
            ."</tr></table>"
            ."</td></tr></table>";
    }
    echo "</td>"
        ."</tr>"
        ."<tr><td style=\"background-color:".$GLOBALS['bgcolor2']."\">"._LINKRATING.": $avgRU</td></tr>"
        ."<tr><td style=\"background-color:".$GLOBALS['bgcolor1']."\">"._HIGHRATING.": $topreg</td></tr>"
        ."<tr><td style=\"background-color:".$GLOBALS['bgcolor2']."\">"._LOWRATING.": $bottomreg</td></tr>"
        ."<tr><td style=\"background-color:".$GLOBALS['bgcolor1']."\">"._NUMOFCOMMENTS.": ".pnVarPrepForDisplay($truecomments)."</td></tr>"
        ."<tr><td></td></tr></table>";
	}
	if ($anonvotes>0) {
    echo "<table>"
        ."<tr><td colspan=\"2\" style=\"background-color:".$GLOBALS['bgcolor2']."\"><strong>"._UNREGISTEREDUSERS."</strong></td></tr>"
        ."<tr><td style=\"background-color:".$GLOBALS['bgcolor1']."\">"._NUMBEROFRATINGS.": $anonvotes</td>"
        ."<td rowspan=\"5\" style=\"width:200px\">";
    if ($anonvotes==0) {
        echo "<div style=\"text-align:center\">"._NOUNREGUSERSVOTES.'</div>';
    } else {
        echo "<table border=\"1\" width=\"200\">"
            ."<tr>"
            ."<td valign=\"top\" align=\"center\" colspan=\"10\" style=\"background-color:".$GLOBALS['bgcolor2']."\"><span class=\"pn-sub\">"._BREAKDOWNBYVAL."</span></td>"
            ."</tr>"
            ."<tr>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img alt=\"$avv[1] "._LVOTES." ($avvpercent[1]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$avvchartheight[1]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img border=\"0\" alt=\"$avv[2] "._LVOTES." ($avvpercent[2]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$avvchartheight[2]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img border=\"0\" alt=\"$avv[3] "._LVOTES." ($avvpercent[3]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$avvchartheight[3]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img border=\"0\" alt=\"$avv[4] "._LVOTES." ($avvpercent[4]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$avvchartheight[4]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img border=\"0\" alt=\"$avv[5] "._LVOTES." ($avvpercent[5]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$avvchartheight[5]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img border=\"0\" alt=\"$avv[6] "._LVOTES." ($avvpercent[6]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$avvchartheight[6]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img border=\"0\" alt=\"$avv[7] "._LVOTES." ($avvpercent[7]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$avvchartheight[7]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img border=\"0\" alt=\"$avv[8] "._LVOTES." ($avvpercent[8]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$avvchartheight[8]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img border=\"0\" alt=\"$avv[9] "._LVOTES." ($avvpercent[9]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$avvchartheight[9]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img border=\"0\" alt=\"$avv[10] "._LVOTES." ($avvpercent[10]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$avvchartheight[10]\" /></td>"
            ."</tr>"
            ."<tr><td colspan=\"10\" style=\"background-color:".$GLOBALS['bgcolor2']."\">"
            ."<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"200\"><tr>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">1</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">2</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">3</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">4</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">5</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">6</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">7</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">8</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">9</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">10</span></td>"
            ."</tr></table>"
            ."</td></tr></table>";
    }
    echo "</td>"
        ."</tr>"
        ."<tr><td style=\"background-color:".$GLOBALS['bgcolor2']."\">"._LINKRATING.": $avgAU</td></tr>"
        ."<tr><td style=\"background-color:".$GLOBALS['bgcolor1']."\">"._HIGHRATING.": $topanon</td></tr>"
        ."<tr><td style=\"background-color:".$GLOBALS['bgcolor2']."\">"._LOWRATING.": $bottomanon</td></tr>"
        ."<tr><td style=\"background-color:".$GLOBALS['bgcolor1']."\">&nbsp;</td></tr></table>"
		."<table><tr><td valign=\"top\"><span class=\"pn-sub\"><br />"._WEIGHNOTE." $anonweight "._TO." 1.</span></td></tr></table>";
	}
    if ($useoutsidevoting == 1) {
        echo "<table><tr><td valign=\"top\" colspan=\"2\"><span class=\"pn-sub\"><br />"._WEIGHOUTNOTE." $outsideweight "._TO." 1.</span></td></tr>"
            ."<tr><td colspan=\"2\" style=\"background-color:".$GLOBALS['bgcolor2']."\"><strong>"._OUTSIDEVOTERS."</strong></td></tr>"
            ."<tr><td style=\"background-color:".$GLOBALS['bgcolor1']."\">"._NUMBEROFRATINGS.": $outsidevotes</td>"
            ."<td rowspan=\"5\" style=\"width:200px\">";
        if ($outsidevotes==0) {
            echo '<div style="text-align:center"><span class="pn-sub">'._NOOUTSIDEVOTES."</span></div>";
        } else {
            echo "<table border=\"1\" width=\"200\">"
                ."<tr>"
            ."<td valign=\"top\" align=\"center\" colspan=\"10\" style=\"background-color:".$GLOBALS['bgcolor2']."\"><span class=\"pn-sub\">"._BREAKDOWNBYVAL."</span></td>"
            ."</tr>"
            ."<tr>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img alt=\"$ovv[1] "._LVOTES." ($ovvpercent[1]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$ovvchartheight[1]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img alt=\"$ovv[2] "._LVOTES." ($ovvpercent[2]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$ovvchartheight[2]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img alt=\"$ovv[3] "._LVOTES." ($ovvpercent[3]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$ovvchartheight[3]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img alt=\"$ovv[4] "._LVOTES." ($ovvpercent[4]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$ovvchartheight[4]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img alt=\"$ovv[5] "._LVOTES." ($ovvpercent[5]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$ovvchartheight[5]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img alt=\"$ovv[6] "._LVOTES." ($ovvpercent[6]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$ovvchartheight[6]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img alt=\"$ovv[7] "._LVOTES." ($ovvpercent[7]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$ovvchartheight[7]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img alt=\"$ovv[8] "._LVOTES." ($ovvpercent[8]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$ovvchartheight[8]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img alt=\"$ovv[9] "._LVOTES." ($ovvpercent[9]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$ovvchartheight[9]\" /></td>"
            ."<td style=\"background-color:".$GLOBALS['bgcolor1']."\" valign=\"bottom\"><img alt=\"$ovv[10] "._LVOTES." ($ovvpercent[10]% "._LTOTALVOTES.")\" src=\"modules/Web_Links/images/blackpixel.gif\" width=\"15\" height=\"$ovvchartheight[10]\" /></td>"
            ."</tr>"
            ."<tr><td colspan=\"10\" style=\"background-color:".$GLOBALS['bgcolor2']."\">"
            ."<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"200\"><tr>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">1</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">2</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">3</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">4</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">5</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">6</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">7</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">8</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">9</span></td>"
            ."<td width=\"10%\" valign=\"bottom\" align=\"center\"><span class=\"pn-sub\">10</span></td>"
            ."</tr></table>"
            ."</td></tr></table>";
        }
        echo "</td>"
            ."</tr>"
            ."<tr><td style=\"background-color:".$GLOBALS['bgcolor2']."\">"._LINKRATING.": $avgOU</td></tr>"
            ."<tr><td style=\"background-color:".$GLOBALS['bgcolor1']."\">"._HIGHRATING.": $topoutside</td></tr>"
            ."<tr><td style=\"background-color:".$GLOBALS['bgcolor2']."\">"._LOWRATING.": $bottomoutside</td></tr>"
            ."<tr><td style=\"background-color:".$GLOBALS['bgcolor1']."\">&nbsp;</td></tr></table>";
    }
    echo '<br /><div style="text-align:center">';
    linkfooter($lid,displaytitle($lid));
    echo '</div>';
    CloseTable();

    // added hook call - markwest
    echo pnModCallHooks('item', 'display', $lid, "index.php?name=$GLOBALS[ModName]&req=viewlinkdetails&lid=$lid");

    include('footer.php');
}

/**
 * @usedby index
 */
function outsidelinksetup($lid)
{
    $sitename = pnConfigGetVar('sitename');

    include('header.php');

    menu(1);

    OpenTable();
   // "._THENUMBER." \"$lid\" "._IDREFER1." $sitename"._IDREFER2."<br />
    echo '<div style="text-align:center"><strong>'._PROMOTEYOURSITE."</strong></div><br />

    "._PROMOTE01."<br />

    <strong>1) "._TEXTLINK."</strong><br />

    "._PROMOTE02."<br />
    <div style=\"text-align:center\"><a href=\"" . pnGetBaseURL() . "".$GLOBALS['modurl']."&amp;req=ratelink&amp;lid=$lid\">"._RATETHISSITE." @ $sitename</a></div><br />
    <div style=\"text-align:center\">"._HTMLCODE1."</div><br />
    <div style=\"text-align:center\"><em>&lt;a href=\"" . pnGetBaseURL() . "".$GLOBALS['modurl']."&amp;req=ratelink&amp;lid=$lid\"&gt;"._RATETHISSITE."&lt;/a&gt;</em></div>
    <br />
    "._THENUMBER." \"$lid\" "._IDREFER."<br />

    <strong>2) "._BUTTONLINK."</strong><br />

    "._PROMOTE03."<br />

    <div style=\"text-align:center\">
        <form action=\"" . pnGetBaseURL() . $GLOBALS['modurl']."\" method=\"post\"><div>
        <input type=\"hidden\" name=\"lid\" value=\"$lid\" />\n
    <input type=\"hidden\" name=\"req\" value=\"ratelink\" />\n
    <input type=\"submit\" value=\""._RATEIT."\" />\n
	</div>
    </form>\n
    </div>

    <div style=\"text-align:center\">"._HTMLCODE2."</div><br />

    <table border=\"0\"><tr><td align=\"left\"><em>
    &lt;form action=\"" . pnGetBaseURL() . "".$GLOBALS['modurl']."\" method=\"post\"&gt;<br />\n
	&lt;div&gt;
    &nbsp;&nbsp;&lt;input type=\"hidden\" name=\"lid\" value=\"$lid\" /&gt;<br />\n
    &nbsp;&nbsp;&lt;input type=\"hidden\" name=\"req\" value=\"ratelink\" /&gt;<br />\n
    &nbsp;&nbsp;&lt;input type=\"submit\" value=\""._RATEIT."\" /&gt;<br />\n
    &lt;/form&gt;\n
	&lt;/div&gt;
    </em></td></tr></table>

    <br />

    <strong>3) "._REMOTEFORM."</strong><br />

    "._PROMOTE04."

    <div style=\"text-align:center\">
    <form method=\"post\" action=\"" . pnGetBaseURL() . "".$GLOBALS['modurl']."\"><div>
    <table border=\"0\" width=\"175\" cellspacing=\"0\" cellpadding=\"0\">
    <tr><td align=\"center\"><strong>"._VOTE4THISSITE."</strong></td></tr>
    <tr><td>
    <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
    <tr><td valign=\"top\">
        <select name=\"rating\">
        <option selected=\"selected\">--</option>
    <option>10</option>
    <option>9</option>
    <option>8</option>
    <option>7</option>
    <option>6</option>
    <option>5</option>
    <option>4</option>
    <option>3</option>
    <option>2</option>
    <option>1</option>
    </select>
    </td><td valign=\"top\">
    <input type=\"hidden\" name=\"ratinglid\" value=\"$lid\" />
	<input type=\"hidden\" name=\"ratinguser\" value=\"outside\" />
	<input type=\"hidden\" name=\"req\" value=\"addrating\" />
    <input type=\"submit\" value=\""._LINKVOTE."\" />
    </td></tr></table>
    </td></tr></table></div></form>

    <br />"._HTMLCODE3."</div>
	<div>
    <blockquote><div><em>
    &lt;form method=\"post\" action=\"" . pnGetBaseURL() . "".$GLOBALS['modurl']."\"&gt;&lt;div&gt;<br />
    &lt;table align=\"center\" border=\"0\" width=\"175\" cellspacing=\"0\" cellpadding=\"0\"&gt;<br />
        &lt;tr&gt;&lt;td align=\"center\"&gt;&lt;b&gt;"._VOTE4THISSITE."&lt;/b&gt;&lt;/a&gt;&lt;/td&gt;&lt;/tr&gt;<br />
        &lt;tr&gt;&lt;td&gt;<br />
        &lt;table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\"&gt;<br />
        &lt;tr&gt;&lt;td valign=\"top\"&gt;<br />
		&lt;select name=\"rating\"&gt;<br />
		&lt;option selected&gt;--&lt;/option&gt;<br />
        &lt;option&gt;10&lt;/option&gt;<br />
        &lt;option&gt;9&lt;/option&gt;<br />
        &lt;option&gt;8&lt;/option&gt;<br />
        &lt;option&gt;7&lt;/option&gt;<br />
        &lt;option&gt;6&lt;/option&gt;<br />
        &lt;option&gt;5&lt;/option&gt;<br />
        &lt;option&gt;4&lt;/option&gt;<br />
        &lt;option&gt;3&lt;/option&gt;<br />
        &lt;option&gt;2&lt;/option&gt;<br />
        &lt;option&gt;1&lt;/option&gt;<br />
        &lt;/select&gt;<br />
        &lt;/td&gt;&lt;td valign=\"top\"&gt;<br />
        &lt;input type=\"hidden\" name=\"ratinglid\" value=\"$lid\" /&gt;<br />
		&lt;input type=\"hidden\" name=\"ratinguser\" value=\"outside\" /&gt;<br />
		&lt;input type=\"hidden\" name=\"req\" value=\"addrating\" /&gt;<br />
        &lt;input type=\"submit\" value=\""._LINKVOTE."\" /&gt;<br />
        &lt;/td&gt;&lt;/tr&gt;&lt;/table&gt;<br />
    &lt;/td&gt;&lt;/tr&gt;&lt;/table&gt;<br />
	&lt;/div&gt;
    &lt;/form&gt;<br />
    </em></div></blockquote></div>
    <br /><div style=\"text-align:center\">
    "._PROMOTE05."<br />
    - $sitename "._STAFF."
    </div>";
    CloseTable();
    include('footer.php');
}

/**
 * @usedby index
 */
function brokenlink($lid) {
    include('header.php');

    if (pnUserLoggedIn()) {
        $ratinguser = pnUserGetVar('uname');
    } else {
        $ratinguser = pnConfigGetVar("anonymous");
    }
    menu(1);

    OpenTable();
    echo '<div style="text-align:center"><strong>'._REPORTBROKEN.'</strong><br />';
    echo "<form action=\"".$GLOBALS['modurl']."\" method=\"post\"><div>";
    echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\" />";
    echo "<input type=\"hidden\" name=\"modifysubmitter\" value=\"$ratinguser\" />";
    echo "<input type=\"hidden\" name=\"authid\" value=\"".pnSecGenAuthKey()."\" />";
    echo ""._THANKSBROKEN.'<br />'._SECURITYBROKEN.'<br />';
    echo "<input type=\"hidden\" name=\"req\" value=\"brokenlinkS\" />
	<input type=\"submit\" value=\""._REPORTBROKEN."\" /></div></form></div>";
    CloseTable();
    include('footer.php');
}

/**
 * @usedby index
 */
function brokenlinkS($lid, $modifysubmitter)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        menu(1);
        OpenTable();
        echo _BADAUTHKEY;
        CloseTable();
        include 'footer.php';
        return;
    }

    if (pnUserLoggedIn()) {
        $ratinguser = pnUserGetVar('uname');
    } else {
        $ratinguser = pnConfigGetVar('anonymous');
    }

    $nextid = $dbconn->GenId($pntable['links_modrequest']);
    $column = &$pntable['links_modrequest_column'];
    $dbconn->Execute("INSERT INTO $pntable[links_modrequest] ($column[requestid], $column[lid], $column[modifysubmitter], $column[brokenlink]) VALUES ($nextid, ".(int)pnVarPrepForStore($lid).", '".pnVarPrepForStore($ratinguser)."', 1)");
    include('header.php');
    menu(1);

    OpenTable();
    echo '<br /><div style="text-align:center">'._THANKSFORINFO.'<br />'._LOOKTOREQUEST.'</div><br />';
    CloseTable();
    include('footer.php');
}

/**
 * @usedby index
 */
function modifylinkrequest($lid) {
    include('header.php');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $anonymous = pnConfigGetVar('anonymous');
	$blockunregmodify = pnConfigGetVar('blockunregmodify');

    if (pnUserLoggedIn()) {
        $ratinguser = pnUserGetVar('uname');
    } else {
        $ratinguser = $anonymous;
    }
    menu(1);

    OpenTable();
    $blocknow = 0;
    if ($blockunregmodify == 1 && $ratinguser == $anonymous) {
    //if ($ratinguser == $anonymous) {
        echo '<br /><div style="text-align:center">'._ONLYREGUSERSMODIFY.'</div>';
        $blocknow = 1;
    }
    if ($blocknow != 1) {
        echo '<div style="text-align:center"><strong>'._REQUESTLINKMOD."</strong></div>";
        $column = &$pntable['links_links_column'];
		$sql = "SELECT $column[cat_id],
                       $column[title], 
					   $column[url],
                       $column[description]
                FROM $pntable[links_links]
                WHERE $column[lid]='".(int)pnVarPrepForStore($lid)."'";
        $result =& $dbconn->Execute($sql);
	    list($cid, $title, $url, $description) = $result->fields;
        //while(list($cid, $title, $url, $description) = $result->fields) {
            //$result->MoveNext();
            echo "<form action=\"".$GLOBALS['modurl']."\" method=\"post\">"
                .'<div><strong>'._LINKID.": ".pnVarPrepForDisplay($lid).'</strong><br />'
                ._LINKTITLE.":<br /><input type=\"text\" name=\"title\" value=\"".pnVarPrepForDisplay($title)."\" size=\"50\" maxlength=\"100\" /><br />"
                ._URL.":<br /><input type=\"text\" name=\"url\" value=\"".pnVarPrepForDisplay($url)."\" size=\"75\" maxlength=\"254\" /><br />"
                ._DESCRIPTION255.": <br /><textarea name=\"description\" cols=\"80\" rows=\"10\">".pnVarPrepHTMLDisplay($description)."</textarea><br />";
            echo "<input type=\"hidden\" name=\"lid\" value=\"".pnVarPrepForDisplay($lid)."\" />"
                ."<input type=\"hidden\" name=\"modifysubmitter\" value=\"".pnVarPrepForDisplay($ratinguser)."\" />"
                ._CATEGORY.": <select name=\"cat\">";
            echo CatList(0, $cid)."</select><br />"
                ."<input type=\"hidden\" name=\"req\" value=\"modifylinkrequestS\" />"
                ."<input type=\"hidden\" name=\"authid\" value=\"".pnSecGenAuthKey()."\" />"
                ."<input type=\"submit\" value=\""._SENDREQUEST."\" />"
				."</div></form>";
        //}
    }
    CloseTable();
    include('footer.php');
}

/*
 * @usedby index
 */
function modifylinkrequestS($lid, $cat, $title, $url, $description, $modifysubmitter)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        OpenTable();
        echo _BADAUTHKEY;
        CloseTable();
        include 'footer.php';
        return;
    }

    $anonymous = pnConfigGetVar('anonymous');
	$blockunregmodify = pnConfigGetVar('blockunregmodify');

    if (pnUserLoggedIn()) {
        $ratinguser = pnUserGetVar('uname');
    } else {
        $ratinguser = $anonymous;
    }
    $blocknow = 0;
    if ($blockunregmodify == 1 && $ratinguser == $anonymous) {
    //if ($ratinguser == $anonymous) {
        include('header.php');
        menu(1);

        OpenTable();
        echo "<div style=\"text-align:center\">"._ONLYREGUSERSMODIFY.'</div>';
        $blocknow = 1;
        CloseTable();
        include('footer.php');
    }
    if ($blocknow != 1) {
        $nextid = $dbconn->GenId($pntable['links_modrequest']);
        $column = &$pntable['links_modrequest_column'];
        $dbconn->Execute("INSERT INTO $pntable[links_modrequest] ($column[requestid], $column[lid], $column[cat_id], $column[title], $column[url], $column[description], $column[modifysubmitter], $column[brokenlink])
                        VALUES ($nextid, ".(int)pnVarPrepForStore($lid).", ".pnVarPrepForStore($cat).", '".pnVarPrepForStore($title)."', '".pnVarPrepForStore($url)."', '".pnVarPrepForStore($description)."', '".pnVarPrepForStore($ratinguser)."', 0)");
        include('header.php');
        menu(1);

        OpenTable();
        echo "<div style=\"text-align:center\">"._THANKSFORINFO." "._LOOKTOREQUEST.'</div>';
        CloseTable();
        include('footer.php');
    }
}
?>