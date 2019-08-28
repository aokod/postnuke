<?php
// File: $Id: dl-rating.php 15951 2005-03-08 21:07:03Z larsneo $
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
function rateinfo($lid) {

    if (!isset($lid) || !is_numeric($lid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $itemname = downloads_ItemNameFromIID($lid);
    $catname = downloads_CatNameFromIID($lid);
    if (!(pnSecAuthAction(0, 'Downloads::Item', "$itemname:$catname:$lid", ACCESS_COMMENT))) {
        echo _DOWNLOADSACCESSNOAUTH;
        include 'footer.php';
        return;
    }

    $column = &$pntable['downloads_downloads_column'];
    $dbconn->Execute("UPDATE $pntable[downloads_downloads]
                    SET $column[hits] =$column[hits]+1
                    WHERE $column[lid]='".(int)pnVarPrepForStore($lid)."'");
    $result =& $dbconn->Execute("SELECT $column[url]
                              FROM $pntable[downloads_downloads]
                              WHERE $column[lid]='".(int)pnVarPrepForStore($lid)."'");
    list($url) = $result->fields;
    Header("Location: ".$url);
}

/**
 *@usedby index, navigation
 */
function addrating($ratinglid, $ratinguser, $rating, $ratinghost_name, $ratingcomments)
{

    if (!isset($ratinglid) || !is_numeric($ratinglid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $anonymous = pnConfigGetVar('anonymous');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $passtest = "yes";
    include('header.php');
    include(WHERE_IS_PERSO."config.php");


    $itemname = downloads_ItemNameFromIID($ratinglid);
    $catname = downloads_CatNameFromIID($ratinglid);
    if(!isset($lid)) $lid = '';
    if (!(pnSecAuthAction(0, 'Downloads::Item', "$itemname:$catname:$lid", ACCESS_COMMENT))) {
        echo _DOWNLOADSACCESSNOAUTH;
        include 'footer.php';
        return;
    }

    completevoteheader();
    if (pnUserLoggedIn()) {
        $ratinguser = pnUserGetVar('uname');
    } else if ($ratinguser=="outside") {
        $ratinguser = "outside";
    } else {
        $ratinguser = pnConfigGetVar("anonymous");
    }
    $column = &$pntable['downloads_downloads_column'];
    $results3 =& $dbconn->Execute("SELECT $column[title]
                                FROM $pntable[downloads_downloads]
                                WHERE $column[lid]='".(int)pnVarPrepForStore($ratinglid)."'");
   while(list($title)=$results3->fields)   {
        $ttitle = $title;
        $results3->MoveNext();
    }
    /* Make sure only 1 anonymous from an IP in a single day. */
    $ip = pnServerGetVar("REMOTE_HOST");
    if (empty($ip)) {
       $ip = pnServerGetVar("REMOTE_ADDR");
    }
    /* Check if Rating is Null */
    if ($rating=="--") {
        $error = "nullerror";
        completevote($error);
        $passtest = "no";
    }
    /* Check if Download POSTER is voting (UNLESS Anonymous users allowed to post) */
    if ($ratinguser != $anonymous && $ratinguser != "outside") {
        $column = &$pntable['downloads_downloads_column'];
        $result =& $dbconn->Execute("SELECT $column[submitter]
                                FROM $pntable[downloads_downloads]
                                WHERE $column[lid]='".(int)pnVarPrepForStore($ratinglid)."'");
        while(list($ratinguserDB)=$result->fields) {

            $result->MoveNext();
            if ($ratinguserDB==$ratinguser) {
                $error = "postervote";
                completevote($error);
                $passtest = "no";
            }
        }
    }
    /* Check if REG user is trying to vote twice. */
    if ($ratinguser!=$anonymous && $ratinguser != "outside") {
        $column = &$pntable['downloads_votedata_column'];
        $result =& $dbconn->Execute("SELECT $column[ratinguser] FROM $pntable[downloads_votedata] WHERE $column[ratinglid]='".(int)pnVarPrepForStore($ratinglid)."'");
        while(list($ratinguserDB)=$result->fields) {

            $result->MoveNext();
            if ($ratinguserDB==$ratinguser) {
                $error = "regflood";
                completevote($error);
                $passtest = "no";
            }
        }
    }
    /* Check if ANONYMOUS user is trying to vote more than once per day. */
    if ($ratinguser==$anonymous){
        $yesterdaytimestamp = (time()-(86400 * $anonwaitdays));
        $ytsDB = Date("Y-m-d H:i:s", $yesterdaytimestamp);
        $column = &$pntable['downloads_votedata_column'];
/* cocomp 2002/07/13 removed mysql specific TO_DAYS(NOW() etc moved to cross db
 * compatible version
        $result =& $dbconn->Execute("SELECT count(*)
                                FROM $pntable[downloads_votedata]
                                WHERE $column[ratinglid]='".(int)pnVarPrepForStore($ratinglid)."'
                                   AND $column[ratinguser]='".pnVarPrepForStore($anonymous)."'
                                   AND $column[ratinghostname] = '".pnVarPrepForStore($ip)."'
                                   AND TO_DAYS(NOW()) - TO_DAYS(".pnVarPrepForStore($column['ratingtimestamp']).") < '".pnVarPrepForStore($anonwaitdays)."'");
*/
        $sql = "SELECT count(*)
                FROM $pntable[downloads_votedata]
                WHERE $column[ratinglid]='".pnVarPrepForStore($ratinglid)."'
                AND $column[ratinguser]='".pnVarPrepForStore($anonymous)."'
                AND $column[ratinghostname] = '".pnVarPrepForStore($ip)."'
                AND (".$dbconn->DBTimestamp(time() - $column['ratingtimestamp'])
                . " < '" . pnVarPrepForStore($anonwaitdays * 86400) . "')";
        $result =& $dbconn->Execute($sql);
        list($anonvotecount) = $result->fields;
        if ($anonvotecount >= 1) {
            $error = "anonflood";
            completevote($error);
            $passtest = "no";
        }
    }
    /* Check if OUTSIDE user is trying to vote more than once per day. */
    if ($ratinguser=="outside"){
        $yesterdaytimestamp = (time()-(86400 * $outsidewaitdays));
        $ytsDB = Date("Y-m-d H:i:s", $yesterdaytimestamp);
        $column = &$pntable['downloads_votedata_column'];
/* cocomp 2002/07/13 removed mysql specific TO_DAYS(NOW() etc moved to cross db
 * compatible version
        $result =& $dbconn->Execute("SELECT count(*) FROM $pntable[downloads_votedata]
                                WHERE $column[ratinglid]='".(int)pnVarPrepForStore($ratinglid)."'
                                   AND $column[ratinguser]='outside'
                                   AND $column[ratinghostname] = '".pnVarPrepForStore($ip)."'
                                   AND TO_DAYS(NOW()) - TO_DAYS(".pnVarPrepForStore($column['ratingtimestamp']).") < '".pnVarPrepForStore($outsidewaitdays)."'");
*/
        $sql = "SELECT count(*) FROM $pntable[downloads_votedata]
                WHERE $column[ratinglid]='".pnVarPrepForStore($ratinglid)."'
                AND $column[ratinguser] = 'outside'
                AND $column[ratinghostname] = '" . pnVarPrepForStore($ip) . "'
                AND (" . $dbconn->DBTimestamp(time() - $column['ratingtimestamp'])
                . " < '" . pnVarPrepForStore($anonwaitdays * 86400) . "')";
        $result =& $dbconn->Execute($sql);
        list($outsidevotecount) = $result->fields;
        if ($outsidevotecount >= 1) {
            $error = "outsideflood";
            completevote($error);
            $passtest = "no";
        }
    }
    /* Passed Tests */
    if ($passtest == "yes") {
        /* All is well.  Add to Line Item Rate to DB. */
         $column = &$pntable['downloads_votedata_column'];
// cocomp 2002/07/13 converted to use GenID instead of NULL id insert
// removed NOW() to cross db compatible DBTimestamp
        $votetable = $pntable['downloads_votedata'];
        $bid = $dbconn->GenID($votetable);
         $dbconn->Execute("INSERT INTO $votetable
                         ($column[ratingdbid], $column[ratinglid],
                         $column[ratinguser], $column[rating],
                         $column[ratinghostname], $column[ratingcomments],
                         $column[ratingtimestamp])
                         VALUES (" . (int)pnVarPrepForStore($bid) . ",
                                                                  ".(int)pnVarPrepForStore($ratinglid).", '".pnVarPrepForStore($ratinguser)."', '".pnVarPrepForStore($rating)."',
                                     '".pnVarPrepForStore($ip)."', '".pnVarPrepForStore($ratingcomments)."',
                                                                 " . $dbconn->DBTimestamp(time()) . ")");
        /* All is well.  Calculate Score & Add to Summary (for quick retrieval & sorting) to DB. */
        /* NOTE: If weight is modified, ALL downloads need to be refreshed with new weight. */
        /*   Running a SQL statement with your modded calc for ALL downloads will accomplish this. */
        $voteresult =& $dbconn->Execute("SELECT $column[rating], $column[ratinguser],
                                      $column[ratingcomments]
                                      FROM $pntable[downloads_votedata]
                                      WHERE $column[ratinglid] = '".(int)pnVarPrepForStore($ratinglid)."'");
        $totalvotesDB = $voteresult->PO_RecordCount();
        $finalrating = calculateVote($voteresult, $totalvotesDB);
		$commresult =& $dbconn->Execute("SELECT $column[ratingcomments]
																		FROM $pntable[downloads_votedata]
																		WHERE $column[ratinglid] = '".pnVarPrepForStore($ratinglid)."'
																		AND $column[ratingcomments] != ''");
		$truecomments = $commresult->PO_RecordCount();
        $column = &$pntable['downloads_downloads_column'];
        $dbconn->Execute("UPDATE $pntable[downloads_downloads]
                        SET $column[downloadratingsummary] = '".pnVarPrepForStore($finalrating)."',
                            $column[totalvotes] = '".pnVarPrepForStore($totalvotesDB)."',
                            $column[totalcomments] = '".pnVarPrepForStore($truecomments)."'
                        WHERE $column[lid] = '".(int)pnVarPrepForStore($ratinglid)."'");
        $error = "none";
        completevote($error);
    }
    if ($error == "none")
    {
    completevotefooter($ratinglid, $ttitle, $ratinguser);
    }
    CloseTable();
    include('footer.php');
}

function completevoteheader() {
    menu(1);

    OpenTable();
}

function completevotefooter($lid, $ttitle, $ratinguser)
{
    if (!isset($lid) || !is_numeric($lid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $itemname = downloads_ItemNameFromIID($lid);
    $catname = downloads_CatNameFromIID($lid);
    if (!(pnSecAuthAction(0, 'Downloads::Item', "$itemname:$catname:$lid", ACCESS_COMMENT))) {
        echo _DOWNLOADSACCESSNOAUTH;
        include 'footer.php';
        return;
    }

    $column = &$pntable['downloads_downloads_column'];
    $result =& $dbconn->Execute("SELECT $column[url]
                            FROM $pntable[downloads_downloads]
                            WHERE $column[lid]='".(int)pnVarPrepForStore($lid)."'");
    list($url)=$result->fields;
    echo "<div style=\"text-align:center\">"._THANKSTOTAKETIME.' '.pnConfigGetVar('sitename').'.<br />'._DLETSDECIDE.'</div><br />';
    if ($ratinguser=="outside") {
        echo "<div style=\"text-align:center\">".WEAPPREACIATE.' '.pnConfigGetVar('sitename')."!<br /><a href=\"$url\">"._RETURNTO." $ttitle</a></div><br />";
        $column = &$pntable['downloads_downloads_column'];
        $result =& $dbconn->Execute("SELECT $column[title]
                                FROM $pntable[downloads_downloads]
                                WHERE $column[lid]='".(int)pnVarPrepForStore($lid)."'");
        list($title)=$result->fields;
        $ttitle = ereg_replace (" ", "_", $title);
    }
    echo "<div style=\"text-align:center\">";
    downloadinfomenu($lid,displaytitle($lid));
    echo '</div>';
}

function completevote($error) 
{
    if ($error == "none") {
		echo '<div style="text-align:center"><strong>'._RATENOTE1ERROR."</strong></div>";
	} elseif ($error == "anonflood") {
		$anonwaitdays = pnConfigGetVar('anonwaitdays');
		// echo '<div style="text-align:center"><strong>'._RATENOTE2ERROR1." $anonwaitdays "._RATENOTE2ERROR2.'</strong></div><br />';
		echo '<div style="text-align:center"><strong>'._RATENOTE2ERROR.'</strong></div><br />';
	} elseif ($error == "regflood")	{
        echo '<div style="text-align:center"><strong>'._RATENOTE3ERROR.'</strong></div><br />';
    } elseif ($error == "postervote") {
        echo '<div style="text-align:center"><strong>'._RATENOTE4ERROR.'</strong></div><br />';
    } elseif ($error == "nullerror") {
        echo '<div style="text-align:center"><strong>'._RATENOTE5ERROR.'</strong></div><br />';
    } elseif ($error == "outsideflood") {
		$outsidewaitdays = pnConfigGetVar('outsidewaitdays');
		echo "<div style=\"text-align:center\"><strong>Only one vote per IP address allowed every $outsidewaitdays day(s).</strong></div><br />";
    }
}

/**
 * @usedby index
 */
function ratedownload($lid)
{
    include 'header.php';

	if (!is_numeric($lid)) {
        echo _MODARGSERROR;
        include 'footer.php';
        return;
	}

    menu(1);

    $itemname = downloads_ItemNameFromIID($lid);
    $catname = downloads_CatNameFromIID($lid);
    if (!(pnSecAuthAction(0, 'Downloads::Item', "$itemname:$catname:$lid", ACCESS_COMMENT))) {
        echo _DOWNLOADSACCESSNOAUTH;
        include 'footer.php';
        return;
    }

    OpenTable();
    //$transfertitle = ereg_replace ("_", " ", $ttitle);
    //$displaytitle = $transfertitle;
	$displaytitle = displaytitle($lid);
    $ip = pnServerGetVar("REMOTE_HOST");
    if (empty($ip)) {
       $ip = pnServerGetVar("REMOTE_ADDR");
    }
    echo "<h2>".pnVarPrepForDisplay($displaytitle)."</h2>"
    ."<ul>"
    ."<li>"._RATENOTE1."</li>"
    ."<li>"._RATENOTE2."</li>"
    ."<li>"._RATENOTE3."</li>"
    ."<li>"._DRATENOTE4."</li>"
    ."<li>"._RATENOTE5."</li>";
    if (pnUserLoggedIn()) {
        $name = pnUserGetVar('uname');
        echo "<li>"._YOUAREREGGED."</li>"
        ."<li>"._FEELFREE2ADD."</li>";
    } else {
        echo "<li>"._YOUARENOTREGGED."</li>"
        ."<li>"._IFYOUWEREREG."</li>";
        $name = pnConfigGetVar('anonymous');
    }
    echo "</ul>"
    ."<form action=\"".$GLOBALS['modurl']."&amp;req=addrating\" method=\"post\"><div>"
    ."<table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" width=\"100%\">"
    ."<tr><td style=\"width:25px\"></td></tr>"
    ."<tr><td style=\"width:25px\"></td><td style=\"width:550px\">"
    ."<input type=\"hidden\" name=\"ratinglid\" value=\"$lid\" />"
    ."<input type=\"hidden\" name=\"ratinguser\" value=\"$name\" />"
    ."<input type=\"hidden\" name=\"ratinghost_name\" value=\"$ip\" />"
    ._RATETHISSITE." "
    ."<select name=\"rating\">"
    ."<option>--</option>"
    ."<option>10</option>"
    ."<option>9</option>"
    ."<option>8</option>"
    ."<option>7</option>"
    ."<option>6</option>"
    ."<option>5</option>"
    ."<option>4</option>"
    ."<option>3</option>"
    ."<option>2</option>"
    ."<option>1</option>"
    ."</select>"
    ." <input type=\"submit\" value=\""._RATETHISSITE."\" />"
    .'<br />';
    if (pnUserLoggedIn()) {
        echo _COMMENTS.":<br /><textarea cols=\"80\" rows=\"10\" name=\"ratingcomments\"></textarea>"
        .'<br />';
    } else {
        echo"<input type=\"hidden\" name=\"ratingcomments\" value=\"\" />";
    }
    echo "</td></tr></table></div></form>";
    echo "<div style=\"text-align:center\">";

    downloadfooterchild($lid);

    echo '</div>';
    CloseTable();
    include 'footer.php';
}
?>