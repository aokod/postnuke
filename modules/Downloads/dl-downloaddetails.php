<?php
// File: $Id: dl-downloaddetails.php 19177 2006-06-01 12:47:52Z markwest $
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
 * @usedby index
 */
function viewdownloadcomments($lid) {

    include('header.php');

    if (!isset($lid) || !is_numeric($lid)){
        echo _MODARGSERROR;
		include('footer.php');
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    menu(1);

    $itemname = downloads_ItemNameFromIID($lid);
    $catname = downloads_CatNameFromIID($lid);

    if (!(downloads_authitem((downloads_ItemCIDFromLID($lid)), (downloads_ItemSIDFromLID($lid)), $lid, ACCESS_READ))) {
        echo _DOWNLOADSACCESSNOAUTH;
        include 'footer.php';
        return;
    }

    $column = &$pntable['downloads_votedata_column'];
// cocomp 2002/07/13 changed to use NOT LIKE '' instead of != '' for text column
// better cross db compatibility
    $result =& $dbconn->Execute("SELECT $column[ratinguser], $column[rating],
                            $column[ratingcomments], $column[ratingtimestamp]
                            FROM $pntable[downloads_votedata]
                            WHERE $column[ratinglid] = '".(int)pnVarPrepForStore($lid)."'
                              AND $column[ratingcomments] NOT LIKE ''
                              ORDER BY $column[ratingtimestamp] DESC");
    $totalcomments = $result->PO_RecordCount();
    //$transfertitle = ereg_replace ("_", " ", $ttitle);
    //$displaytitle = $transfertitle;
    $displaytitle = displaytitle($lid);
    OpenTable();
    echo '<h2>'._DOWNLOADPROFILE.": ".pnVarPrepForDisplay($displaytitle)."</h2><br />";
    downloadinfomenu($lid, displaytitle($lid));
    echo '<br />'._TOTALOF." ".pnVarPrepForDisplay($totalcomments)." "._COMMENTS.'<br />'
    ."<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\" width=\"450\"><tr><td></td></tr>";
    $x=0;
    while(list($ratinguser, $rating, $ratingcomments, $ratingtimestamp)=$result->fields) {

        $result->MoveNext();
        $ratingcomments = stripslashes($ratingcomments);
/* cocomp 2002/07/13 let ADODB handle date stuff
        ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $ratingtimestamp, $ratingtime);
        $timestamp = mktime($ratingtime[4],$ratingtime[5],$ratingtime[6],$ratingtime[2],$ratingtime[3],$ratingtime[1]);
        $formatted_date = date("F d, Y", $timestamp);
*/
        $formatted_date = ml_ftime(_DATEBRIEF, $dbconn->UnixTimestamp($ratingtimestamp));
    /* Individual user information */
        $result2 =& $dbconn->Execute("SELECT ".$pntable['downloads_votedata_column']['rating'].
                                 " FROM ".$pntable['downloads_votedata'].
                                 " WHERE ".$pntable['downloads_votedata_column']['ratinguser']." = '".pnVarPrepForStore($ratinguser)."'");
        $usertotalcomments = $result2->PO_RecordCount();
        $useravgrating = 0;
        while(list($rating2)=$result2->fields) {

            $result2->MoveNext();
            $useravgrating = $useravgrating + $rating2;
        }
        $useravgrating = $useravgrating / $usertotalcomments;
        $useravgrating = number_format($useravgrating, 1);
        echo "<tr><td style=\"background-color:".$GLOBALS['bgcolor2']."\">"
        ." "._USER.": <a href=\"" . pnGetBaseURL() . "user.php?op=userinfo&amp;module=User&amp;uname=$ratinguser\">".pnVarPrepForDisplay($ratinguser)."</a>"
        ."</td>"
        ."<td style=\"background-color:".$GLOBALS['bgcolor2']."\">"
        ._RATING.": ".pnVarPrepForDisplay($rating)
        ."</td>"
        ."<td style=\"background-color=:".$GLOBALS['bgcolor2']."\" align=\"right\">"
        .pnVarPrepForDisplay($formatted_date)
        ."</td>"
        ."</tr>"
        ."<tr>"
        ."<td valign=\"top\">"
        ._USERAVGRATING.": ".pnVarPrepForDisplay($useravgrating)
        ."</td>"
        ."<td valign=\"top\" colspan=\"2\">"
        ._NUMRATINGS.": ".pnVarPrepForDisplay($usertotalcomments)
        ."</td>"
        ."</tr>"
        ."<tr>"
        ."<td colspan=\"3\">";
        if (downloads_authitem((downloads_ItemCIDFromLID($lid)), (downloads_ItemSIDFromLID($lid)), $lid, ACCESS_EDIT) ) {
            echo "<a href=\"admin.php?module=Downloads&amp;op=DownloadsModDownload&amp;lid=$lid\"><img src=\"modules/".$GLOBALS['ModName']."/images/editicon.gif\" alt=\""._EDITTHISDOWNLOAD."\" /></a>";
        }
		//transform hooks
		list($ratingcomments) = pnModCallHooks('item', 'transform', '', array($ratingcomments));
        echo " ".pnVarPrepHTMLDisplay($ratingcomments)
        ."</td></tr>";
        $x++;
    }
    echo "</table><br /><div style=\"text-align:center\">";
    downloadfooter($lid,displaytitle($lid));
    echo '</div>';
    CloseTable();
    include 'footer.php';
}


/**
 * @usedby index
 */
function outsidedownloadsetup($lid) {
    include('header.php');
    menu(1);

    if (!isset($lid) || !is_numeric($lid)){
        echo _MODARGSERROR;
		include('footer.php');
        return false;
    }

    $sitename = pnConfigGetVar('sitename');

    $itemname = downloads_ItemNameFromIID($lid);
    $catname = downloads_CatNameFromIID($lid);
    if (!(downloads_authitem((downloads_ItemCIDFromLID($lid)), (downloads_ItemSIDFromLID($lid)), $lid, ACCESS_COMMENT))) {
        echo _DOWNLOADSACCESSNOAUTH;
        include 'footer.php';
        return;
    }
   //    _THENUMBER." \"$lid\" "._IDREFER1." $sitename"._IDREFER2."<br />
    OpenTable();
    echo '<h2>'._PROMOTEYOURSITE."</h2>
    "._PROMOTE01."<br />
    1) "._TEXTLINK."<br />
    "._PROMOTE02."<br />
    <div style=\"text-align:center\"><a href=\"" . pnGetBaseURL().$GLOBALS['modurl']."&amp;req=ratedownload&amp;lid=$lid\">"._RATETHISSITE." @ $sitename</a></div><br />
    "._HTMLCODE1."<br />
    <pre>&lt;a href=\"" . pnGetBaseURL().$GLOBALS['modurl']."&amp;req=ratedownload&amp;lid=$lid\"&gt;"._RATETHISSITE."&lt;/a&gt;</pre>
    <br />
    "._THENUMBER." \"$lid\" "._IDREFER."<br />
    2) "._BUTTONLINK."<br />
    "._PROMOTE03."<br />
    <div style=\"text-align:center\">
    <form action=\"index.php\" method=\"post\"><div>\n
    <input type=\"hidden\" name=\"name\" value=\"".$GLOBALS['ModName']."\" /><br />\n
    <input type=\"hidden\" name=\"lid\" value=\"$lid\" />\n
    <input type=\"hidden\" name=\"req\" value=\"ratedownload\" />\n
    <input type=\"submit\" value=\""._RATEIT."\" />\n
    </div></form>\n
    </div>
    "._HTMLCODE2."<br />
    <pre>
&lt;form
  action=\"" . pnGetBaseURL().$GLOBALS['modurl']."\"method=\"post\"&gt;
  &lt;div&gt;
&lt;input type=\"hidden\" name=\"lid\" value=\"$lid\" /&gt;
&lt;input type=\"hidden\" name=\"req\" value=\"ratedownload\" /&gt;
&lt;input type=\"submit\" value=\""._RATEIT."\"&gt;
&lt;/div&gt;
&lt;/form&gt;\n
</pre>
    <br />
    3) "._REMOTEFORM."<br />
    "._PROMOTE04."
    <div style=\"text-align:center\">
    <form action=\"" . pnGetBaseURL() . "index.php\" method=\"post\">
	<div>
    <input type=\"hidden\" name=\"name\" value=\"".$GLOBALS['ModName']."\" />\n
    <table border=\"0\" width=\"175\" cellspacing=\"0\" cellpadding=\"0\">
    <tr><td align=\"center\">"._VOTE4THISSITE."</td></tr>
    <tr><td>
    <table border=\"0\" width=\"175\" cellspacing=\"0\" cellpadding=\"0\">
    <tr><td valign=\"top\">
        <select name=\"rating\">
        <option selected=\"selected\">--</option>";
        for ($i=10; $i>=1; $i--) {
            echo "<option value=\"$i\">$i</option>";
        }
    echo "</select>
    </td><td valign=\"top\">
    <input type=\"hidden\" name=\"ratinglid\" value=\"$lid\" />
	<input type=\"hidden\" name=\"ratinguser\" value=\"outside\" />
	<input type=\"hidden\" name=\"req\" value=\"addrating\" />
    <input type=\"submit\" value=\""._DOWNLOADVOTE."\" />
    </td></tr></table>
    </td></tr></table></div></form></div>
    <br />"._HTMLCODE3."<br />
    <pre>
&lt;form method=\"post\"
    action=\"" . pnGetBaseURL().$GLOBALS['modurl']."\"&gt;
&lt;div&gt;	
&lt;table border=\"0\" width=\"175\" cellspacing=\"0\" cellpadding=\"0\"&gt;
  &lt;tr&gt;
    &lt;td align=\"center\"&gt;
      &lt;b&gt;"._VOTE4THISSITE."&lt;/b&gt;
    &lt;/td&gt;
  &lt;/tr&gt;
  &lt;tr&gt;
    &lt;td&gt;
      &lt;table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"&gt;
        &lt;tr&gt;
          &lt;td valign=\"top\"&gt;
            &lt;select name=\"rating\"&gt;
              &lt;option selected&gt;--&lt;/option&gt;&lt;br&gt;\n";
    for ($i=10; $i>=1; $i--) {
        echo "              &lt;option value=\"$i\"&gt;$i&lt;/option&gt;&lt;br&gt;\n";
    }
    echo "            &lt;/select&gt;
          &lt;/td&gt;
          &lt;td valign=\"top\"&gt;
            &lt;input type=\"hidden\" name=\"ratinglid\" value=\"$lid\" /&gt;
            &lt;input type=\"hidden\" name=\"ratinguser\" value=\"outside\" /&gt;
            &lt;input type=\"hidden\" name=\"req\" value=\"addrating\" /&gt;
            &lt;input type=\"submit\" value=\""._DOWNLOADVOTE."\" /&gt;
          &lt;/td&gt;
        &lt;/tr&gt;
      &lt;/table&gt;
    &lt;/td&gt;
  &lt;/tr&gt;
&lt;/table&gt;
&lt;div&gt;
&lt;/form&gt;
</pre>
    <br />
    <strong>"._LINEBREAKWARN."</strong><br />
    "._PROMOTE05."<br />
    - $sitename "._STAFF."
    <br />";
    CloseTable();
    include 'footer.php';
}

/**
 * @usedby index
 */
function viewdownloaddetails($lid)
{
    include('header.php');

    if (!isset($lid) || !is_numeric($lid)){
        echo _MODARGSERROR;
		include('footer.php');
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    menu(1);

        $detailvotedecimal = pnConfigGetVar('detailvotedecimal');
        $useoutsidevoting = pnConfigGetVar('useoutsidevoting');
        $outsideweight = pnConfigGetVar('outsideweight');
        $anonweight = pnConfigGetVar('anonweight');

    $itemname = downloads_ItemNameFromIID($lid);
    $catname = downloads_CatNameFromIID($lid);
    if (!(downloads_authitem((downloads_ItemCIDFromLID($lid)), (downloads_ItemSIDFromLID($lid)), $lid, ACCESS_READ))) {
        echo _DOWNLOADSACCESSNOAUTH;
        include 'footer.php';
        return;
    }

    $column = &$pntable['downloads_votedata_column'];
    $voteresult =& $dbconn->Execute("SELECT $column[rating], $column[ratinguser],
                                  $column[ratingcomments]
                                  FROM $pntable[downloads_votedata]
                                  WHERE $column[ratinglid] = '".(int)pnVarPrepForStore($lid)."'");
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
        if ($ratingcommentsDB=="") {
            $truecomments--;
        }
        if ($ratinguserDB == pnConfigGetVar('anonymous')) {
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
        if ($ratinguserDB == pnConfigGetVar('anonymous')) {
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
        $avgOU = 0;
        $avgAU = 0;
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
    //$transfertitle = ereg_replace ("_", " ", $ttitle);
    //$displaytitle = $transfertitle;
    $displaytitle = displaytitle($lid);
    $GLOBALS['info']['title'] = pnVarPrepForDisplay($displaytitle);
    $column = &$pntable['downloads_downloads_column'];
    $res =& $dbconn->Execute("SELECT $column[name], $column[email], $column[description],
                           $column[filesize], $column[version], $column[homepage]
                           FROM $pntable[downloads_downloads]
                           WHERE $column[lid]='".(int)pnVarPrepForStore($lid)."'");
    list($name, $email, $description, $filesize, $version, $homepage) = $res->fields;

    OpenTable();
    echo "<div><h2>"._DOWNLOADPROFILE.": ".pnVarPrepForDisplay($displaytitle)."</h2><br />";
	//transform hooks
	list($description) = pnModCallHooks('item', 'transform', '', array($description));

    echo pnVarPrepHTMLDisplay(nl2br($description)).'<br />';
    if ($name == "") {
        $name = _UNKNOWN;
    } else {
        if ($email == "") {
            $name = "$name";
        } else {
            $name = "$name ($email)";
        }
    }
    echo '<br />'._AUTHOR.": ".pnVarPrepForDisplay($name).'<br />'
    ._VERSION.": ".pnVarPrepForDisplay($version)." "._FILESIZE.": ".pnVarPrepForDisplay(CoolSize($filesize)).'<br />';
	if (($homepage != "") AND ($homepage != "http://")) {
        echo "[ <a href=\"$homepage\">"._HOMEPAGE."</a> ]";
    }
    //."[ <a href=\"".$GLOBALS['modurl']."&amp;req=getit&amp;lid=$lid\">"._DOWNLOADNOW."</a> ";
    //if (($homepage == "") OR ($homepage == "http://")) {
    //    echo "]<br />";
    //} else {
    downloadinfomenu($lid, displaytitle($lid));

    echo '<br />'._DOWNLOADRATINGDET.'<br />'
        ._TOTALVOTES." ".pnVarPrepForDisplay($totalvotesDB).'<br />'
        ._OVERALLRATING.": ".pnVarPrepForDisplay($finalrating).'<br />';
	
    //echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"2\" width=\"455\">";
// optimization start -- besfred
    $regdraw = array (
        'weight'    => FALSE,
        'users'     => _REGISTEREDUSERS,
        'votes'     => $regvotes,
        'novotes'   => _NOREGUSERSVOTES,
        'vv'        => $rvv,
        'vvpercent' => $rvvpercent,
        'vvcharth'  => $rvvchartheight,
        'avg'       => $avgRU,
        'top'       => $topreg,
        'low'       => $bottomreg,
        'comments'  => $truecomments
    );
    drawvotestats($regdraw);
    $unregdraw = array (
        'weight'    => TRUE,
        'weighnote' => _WEIGHNOTE,
        'weightext' => $anonweight,
        'users'     => _UNREGISTEREDUSERS,
        'votes'     => $anonvotes,
        'novotes'   => _NOUNREGUSERSVOTES,
        'vv'        => $avv,
        'vvpercent' => $avvpercent,
        'vvcharth'  => $avvchartheight,
        'avg'       => $avgAU,
        'top'       => $topanon,
        'low'       => $bottomanon
    );
    drawvotestats($unregdraw);

    if ($useoutsidevoting == 1) {
        $outsidedraw = array (
            'weight'    => TRUE,
            'weighnote' => _WEIGHOUTNOTE,
            'weightext' => $outsideweight,
            'users'     => _OUTSIDEVOTERS,
            'votes'     => $outsidevotes,
            'novotes'   => _NOOUTSIDEVOTES,
            'vv'        => $ovv,
            'vvpercent' => $ovvpercent,
            'vvcharth'  => $ovvchartheight,
            'avg'       => $avgOU,
            'top'       => $topoutside,
            'low'       => $bottomoutside
        );
        drawvotestats($outsidedraw);
    }
// optimization end -- besfred
    //echo "</table>";
    echo '</div>';
	echo '<br />';
	echo "<div style=\"text-align:center\">";
	downloadfooter($lid,displaytitle($lid));
	echo '</div>';
    CloseTable();

        // added hook call - markwest
    echo pnModCallHooks('item', 'display', $lid, "index.php?name=Downloads&req=viewdownloaddetails&lid=$lid");

    include 'footer.php';
}

/**
 * @usedby index
 */
function brokendownload($lid)
{

    include 'header.php';

    if (!isset($lid) || !is_numeric($lid)){
        echo _MODARGSERROR;
		include('footer.php');
        return false;
    }

    if (pnUserLoggedIn()) {
        $ratinguser = pnUserGetVar('uname');
    } else {
        $ratinguser = pnConfigGetVar('anonymous');
    }
    menu(1);

    $itemname = downloads_ItemNameFromIID($lid);
    $catname = downloads_CatNameFromIID($lid);
    if (!(downloads_authitem((downloads_ItemCIDFromLID($lid)), (downloads_ItemSIDFromLID($lid)), $lid, ACCESS_READ))) {
        echo _DOWNLOADSACCESSNOAUTH;
        include 'footer.php';
        return;
    }

    OpenTable();
    echo '<h2>'._REPORTBROKEN."</h2><br />";
    echo "<form action=\"index.php\" method=\"post\"><div>\n"
        ."<input type=\"hidden\" name=\"name\" value=\"".$GLOBALS['ModName']."\" />\n";
    echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\" />";
    echo "<input type=\"hidden\" name=\"modifysubmitter\" value=\"$ratinguser\" />";
    echo _THANKSBROKEN.'<br />'._SECURITYBROKEN.'<br />';
    echo "<input type=\"hidden\" name=\"req\" value=\"brokendownloadS\" /><input type=\"submit\" value=\""._REPORTBROKEN."\" /></div></form>";
    CloseTable();
    include 'footer.php';
}

function brokendownloadS($lid, $modifysubmitter)
{

    if (!isset($lid) || !is_numeric($lid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (pnUserLoggedIn()) {
        $ratinguser = pnUserGetVar('uname');
    } else {
        $ratinguser = pnConfigGetVar('anonymous');
    }
    include('header.php');
    menu(1);

    $itemname = downloads_ItemNameFromIID($lid);
    $catname = downloads_CatNameFromIID($lid);
    if (!(downloads_authitem((downloads_ItemCIDFromLID($lid)), (downloads_ItemSIDFromLID($lid)), $lid, ACCESS_READ))) {
        echo _DOWNLOADSACCESSNOAUTH;
        include 'footer.php';
        return;
    }

    OpenTable();

    $column = &$pntable['downloads_modrequest_column'];
// cocomp 2002/07/13 converted to GenID instead of NULL id insert
// also need to have entry for description column even if it's blank!
        $modtable = $pntable['downloads_modrequest'];
        $rid = $dbconn->GenID($modtable);
    $dbconn->Execute("INSERT INTO $modtable ($column[requestid],
                        $column[description],
                    $column[lid], $column[modifysubmitter], $column[brokendownload],
                    $column[name], $column[email], $column[filesize], $column[version],
                    $column[homepage])
                    VALUES ('".(int)pnVarPrepForStore($rid)."','',".(int)pnVarPrepForStore($lid).", '".pnVarPrepForStore($ratinguser)."', 1, '".pnVarPrepForStore($name)."', '".pnVarPrepForStore($email)."',
                            '".pnVarPrepForStore($filesize)."', '".pnVarPrepForStore($version)."', '".pnVarPrepForStore($homepage)."')");
    echo '<br /><div style="text-align:center">'._THANKSFORINFO.'<br />'._LOOKTOREQUEST.'</div><br />';
    CloseTable();
    include 'footer.php';
}

function modifydownloadrequest($lid)
{

    include 'header.php';

    if (!isset($lid) || !is_numeric($lid)){
        echo _MODARGSERROR;
		include('footer.php');
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

        $blockunregmodify = pnConfigGetVar('blockunregmodify');

    if (pnUserLoggedIn()) {
        $ratinguser = pnUserGetVar('uname');
    } else {
        $ratinguser = pnConfigGetVar('anonymous');
    }
    menu(1);

    $itemname = downloads_ItemNameFromIID($lid);
    $catname = downloads_CatNameFromIID($lid);
    if (!(downloads_authitem((downloads_ItemCIDFromLID($lid)), (downloads_ItemSIDFromLID($lid)), $lid, ACCESS_COMMENT))) {
        echo _DOWNLOADSACCESSNOAUTH;
        include 'footer.php';
        return;
    }

    OpenTable();
    $blocknow = 0;
    if ($blockunregmodify == 1 && $ratinguser == pnConfigGetVar('anonymous')) {
        echo '<br /><div style="text-align:center">'._DONLYREGUSERSMODIFY.'</div>';
        $blocknow = 1;
    }
    if ($blocknow != 1) {
        $column = &$pntable['downloads_downloads_column'];
        $result =& $dbconn->Execute("SELECT $column[cid], $column[sid], $column[title],
                                  $column[url], $column[description], $column[name],
                                  $column[email], $column[filesize], $column[version],
                                  $column[homepage]
                                  FROM $pntable[downloads_downloads]
                                  WHERE $column[lid]='".(int)pnVarPrepForStore($lid)."'");
        echo '<h2>'._REQUESTDOWNLOADMOD.'</h2>';
        while(list($cid, $sid, $title, $url, $description, $name, $email, $filesize, $version, $homepage) = $result->fields) {

            $result->MoveNext();
            $title = stripslashes($title);
            $description = stripslashes($description);
            echo "<form action=\"index.php\" method=\"post\">"
            ."<input type=\"hidden\" name=\"name\" value=\"".$GLOBALS['ModName']."\" />\n"
            ."<div style=\"text-align:center\">"._DOWNLOADID.": $lid</div><br />"
            ._DOWNLOADNAME.":<br /><input type=\"text\" name=\"title\" value=\"$title\" size=\"50\" maxlength=\"100\" /><br />"
            ._URL.":<br /><input type=\"text\" name=\"url\" value=\"$url\" size=\"50\" maxlength=\"254\" /><br />"
            ._DESCRIPTION.": <br /><textarea name=\"description\" cols=\"80\" rows=\"10\">$description</textarea><br />";
            $column = &$pntable['downloads_categories_column'];
            $result2 =& $dbconn->Execute("SELECT $column[cid], $column[title]
                                     FROM $pntable[downloads_categories] ORDER BY $column[title]");
            echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\" />"
            ."<input type=\"hidden\" name=\"modifysubmitter\" value=\"$ratinguser\" />"
            ._CATEGORY.": <select name=\"cat\" />";
            while(list($ccid, $ctitle) = $result2->fields) {

                $result2->MoveNext();
                $sel = "";
                if ($cid==$ccid AND $sid==0) {
                    $sel = "selected";
                }
                echo "<option value=\"$ccid\" $sel>".pnVarPrepForDisplay($ctitle)."</option>";
                $column = &$pntable['downloads_subcategories_column'];
                $result3 =& $dbconn->Execute("SELECT $column[sid], $column[title]
                                         FROM $pntable[downloads_subcategories]
                                         WHERE $column[cid]='".(int)pnVarPrepForStore($ccid)."' ORDER BY $column[title]");
                while(list($ssid, $stitle) = $result3->fields) {

                    $result3->MoveNext();
                    $sel = "";
                    if ($sid==$ssid) {
                        $sel = "selected";
                    }
                    echo "<option value=\"$ccid-$ssid\" $sel>".pnVarPrepForDisplay($ctitle)." / ".pnVarPrepForDisplay($stitle)."</option>";
                }
            }
            echo "</select><br />"
            ._AUTHORNAME.":<br /><input type=\"text\" name=\"aname\" value=\"$name\" size=\"30\" maxlength=\"80\" /><br />"
            ._AUTHOREMAIL.":<br /><input type=\"text\" name=\"email\" value=\"$email\" size=\"30\" maxlength=\"80\" /><br />"
            ._FILESIZE.": ("._INBYTES.")</span><br /><input type=\"text\" name=\"filesize\" value=\"$filesize\" size=\"12\" maxlength=\"11\" /><br />"
            ._VERSION.":<br /><input type=\"text\" name=\"version\" value=\"$version\" size=\"11\" maxlength=\"10\" /><br />"
            ._HOMEPAGE.":<br /><input type=\"text\" name=\"homepage\" value=\"$homepage\" size=\"50\" maxlength=\"200\" /><br />"
            ."<input type=\"hidden\" name=\"req\" value=\"modifydownloadrequestS\" />"
            ."<input type=\"submit\" value=\""._SENDREQUEST."\" /></form>";
        }
    }
    CloseTable();
    include 'footer.php';
}

function modifydownloadrequestS($lid, $cat, $title, $url, $description, $modifysubmitter, $aname, $email, $filesize, $version, $homepage) {

    if (!isset($lid) || !is_numeric($lid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (pnUserLoggedIn()) {
        $ratinguser = pnUserGetVar('uname');
    } else {
        $ratinguser = pnConfigGetVar('anonymous');
    }
        $blockunregmodify = pnConfigGetVar('blockunregmodify');

    $itemname = downloads_ItemNameFromIID($lid);
    $catname = downloads_CatNameFromIID($lid);
    if (!(downloads_authitem((downloads_ItemCIDFromLID($lid)), (downloads_ItemSIDFromLID($lid)), $lid, ACCESS_COMMENT))) {
        echo _DOWNLOADSACCESSNOAUTH;
        include 'footer.php';
        return;
    }

    $blocknow = 0;
    if ($blockunregmodify == 1 && $ratinguser == pnConfigGetVar('anonymous')) {
        include 'header.php';
        menu(1);

        OpenTable();
        echo "<div style=\"text-align:center\">"._DONLYREGUSERSMODIFY.'</div>';
        $blocknow = 1;
        CloseTable();
        include('footer.php');
    }
    if ($blocknow != 1) {
        $cat = explode("-", $cat);
        if (empty($cat[1])) $cat[1] = 0;
        $column = &$pntable['downloads_modrequest_column'];
		// cocomp 2002/07/13 altered to use GenID instead of NULL id insert
		$modtable = $pntable['downloads_modrequest'];
		$rid = $dbconn->GenID($modtable);
		$dbconn->Execute("INSERT INTO $modtable
							($column[requestid], $column[lid], $column[cid], $column[sid],
							$column[title], $column[url], $column[description],
							$column[modifysubmitter], $column[brokendownload],
							$column[name], $column[email], $column[filesize],
							$column[version], $column[homepage])
							VALUES
							(".(int)pnVarPrepForStore($rid).", ".(int)pnVarPrepForStore($lid).", ".(int)pnVarPrepForStore($cat['0']).", "
							.(int)pnVarPrepForStore($cat['1']).", '".pnVarPrepForStore($title)."', '".pnVarPrepForStore($url)."', '"
							.pnVarPrepForStore($description)."', '".pnVarPrepForStore($ratinguser)."', 0, '".pnVarPrepForStore($aname)."', '"
							.pnVarPrepForStore($email)."', '".pnVarPrepForStore($filesize)."', '".pnVarPrepForStore($version)."', '"
							.pnVarPrepForStore($homepage)."')");
        include 'header.php';
        menu(1);

        OpenTable();
        echo "<div style=\"text-align:center\">"._THANKSFORINFO." "._LOOKTOREQUEST.'</div>';
        CloseTable();
        include 'footer.php';
    }
}
?>