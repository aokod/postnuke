<?php
// File: $Id: wl-rating.php 15953 2005-03-08 21:48:59Z larsneo $
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
function rateinfo($lid)
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
    Header('Location: '.$url);
}

/**
 *@usedby index, navigation
 */
function addrating($ratinglid, $ratinguser, $rating, $ratinghost_name, $ratingcomments)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $passtest = "yes";
    include('header.php');
        if (!(pnSecAuthAction(0, 'Web Links::', '::', ACCESS_READ))) {
            echo _WEBLINKSNOAUTH;
            include 'footer.php';
            return;
        }
    include(WHERE_IS_PERSO."config.php");
    completevoteheader();
    if (pnUserLoggedIn()) {
        $ratinguser = pnUserGetVar('uname');
    } else if ($ratinguser=="outside") {
        $ratinguser = "outside";
    } else {
        $ratinguser = pnConfigGetVar("anonymous");
    }
    $column = &$pntable['links_links_column'];
    $results3 =& $dbconn->Execute("SELECT $column[title]
                                FROM $pntable[links_links]
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
    /* Check if Link POSTER is voting (UNLESS Anonymous users allowed to post) */
    if ($ratinguser != pnConfigGetVar("anonymous") && $ratinguser != "outside") {
        $column = &$pntable['links_links_column'];
        $result =& $dbconn->Execute("SELECT $column[submitter]
                                FROM $pntable[links_links]
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
    if ($ratinguser != pnConfigGetVar("anonymous") && $ratinguser != "outside") {
        $column = &$pntable['links_votedata_column'];
        $result =& $dbconn->Execute("SELECT $column[ratinguser] FROM $pntable[links_votedata] WHERE $column[ratinglid]='".(int)pnVarPrepForStore($ratinglid)."'");
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
    if ($ratinguser == pnConfigGetVar("anonymous")){
        $yesterdaytimestamp = (time()-(86400 * $anonwaitdays));
        $ytsDB = Date("Y-m-d H:i:s", $yesterdaytimestamp);
        $column = &$pntable['links_votedata_column'];
        $result =& $dbconn->Execute("SELECT count(*)
                                FROM $pntable[links_votedata]
                                WHERE $column[ratinglid]='".(int)pnVarPrepForStore($ratinglid)."'
                                AND $column[ratinguser]='".pnConfigGetVar("anonymous")."'
                                AND $column[ratinghostname]='".pnVarPrepForStore($ip)."'
                                AND TO_DAYS(NOW()) - TO_DAYS($column[ratingtimestamp]) < '".pnVarPrepForStore($anonwaitdays)."'");
        list($anonvotecount) = $result->fields;
        if ($anonvotecount >= 1) {
            $error = "anonflood";
            completevote($error);
            $passtest = "no";
        }
    }
    /* Check if OUTSIDE user is trying to vote more than once per day. */
    if ($ratinguser == "outside"){
        $yesterdaytimestamp = (time()-(86400 * $outsidewaitdays));
        $ytsDB = date("Y-m-d H:i:s", $yesterdaytimestamp);
        $column = &$pntable['links_votedata_column'];
        $result =& $dbconn->Execute("SELECT count(*) FROM $pntable[links_votedata]
                                WHERE $column[ratinglid]='".(int)pnVarPrepForStore($ratinglid)."'
                                AND $column[ratinguser]='outside'
                                AND $column[ratinghostname]='".pnVarPrepForStore($ip)."'
                                AND TO_DAYS(NOW()) - TO_DAYS($column[ratingtimestamp]) < '".pnVarPrepForStore($outsidewaitdays)."'");
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
        $nextid = $dbconn->GenId($pntable['links_votedata']);
        $column = &$pntable['links_votedata_column'];
        $dbconn->Execute("INSERT INTO $pntable[links_votedata]
                            ($column[ratingdbid], $column[ratinglid],
                             $column[ratinguser], $column[rating],
                             $column[ratinghostname], $column[ratingcomments],
                             $column[ratingtimestamp])
                             VALUES ($nextid,".(int)pnVarPrepForStore($ratinglid).", '".pnVarPrepForStore($ratinguser)."', '".pnVarPrepForStore($rating)."',
                             '".pnVarPrepForStore($ip)."', '".pnVarPrepForStore($ratingcomments)."', now())");
        /* All is well.  Calculate Score & Add to Summary (for quick retrieval & sorting) to DB. */
        /* NOTE: If weight is modified, ALL links need to be refreshed with new weight. */
        /*   Running a SQL statement with your modded calc for ALL links will accomplish this. */
        $voteresult =& $dbconn->Execute("SELECT $column[rating], $column[ratinguser],
                                        $column[ratingcomments]
                                        FROM $pntable[links_votedata]
                                        WHERE $column[ratinglid] = '".(int)pnVarPrepForStore($ratinglid)."'");
        $totalvotesDB = $voteresult->PO_RecordCount();
        $finalrating = calculateVote($voteresult, $totalvotesDB);
		$commresult =& $dbconn->Execute("SELECT $column[ratingcomments]
																		FROM $pntable[links_votedata]
																		WHERE $column[ratinglid] = '".pnVarPrepForStore($ratinglid)."'
																		AND $column[ratingcomments] != ''");
		$truecomments = $commresult->PO_RecordCount();
        $column = &$pntable['links_links_column'];
        $dbconn->Execute("UPDATE $pntable[links_links]
                        SET $column[linkratingsummary] = '".pnVarPrepForStore($finalrating)."',
							$column[totalvotes] = '".pnVarPrepForStore($totalvotesDB)."',
                            $column[totalcomments]= '".pnVarPrepForStore($truecomments)."'
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

/*
 * @usedby function addrating
 */
function completevoteheader(){
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

    $sitename = pnConfigGetVar('sitename');

    $column = &$pntable['links_links_column'];
    $result =& $dbconn->Execute("SELECT $column[url]
                    FROM $pntable[links_links]
                    WHERE $column[lid]='".(int)pnVarPrepForStore($lid)."'");
    list($url)=$result->fields;
    echo "<div style=\"text-align:center\">"._THANKSTOTAKETIME." $sitename<br />. "._LETSDECIDE.'</div><br />';
    if ($ratinguser=="outside") {
        echo "<div style=\"text-align:center\">".WEAPPREACIATE." ".pnVarPrepForDisplay($sitename)."!<br /><a href=\"".pnVarPrepForDisplay($url)."\">"._RETURNTO." ".pnVarPrepForDisplay($ttitle)."</a><div style=\"text-align:center\"><br />";
        $result =& $dbconn->Execute("SELECT $column[title] FROM $pntable[links_links]
                        WHERE $column[lid]='".(int)pnVarPrepForStore($lid)."'");
        list($title)=$result->fields;
        $ttitle = ereg_replace (" ", "_", $title);
    }
    echo "<div style=\"text-align:center\">";
    linkinfomenu($lid,displaytitle($lid));
    echo '</div>';
}

function completevote($error) {
    if ($error == "none")
    {
        echo '<div style="text-align:center"><strong>'._RATENOTE1ERROR."</strong></div>";
    }
    elseif ($error == "anonflood")
    {
        $anonwaitdays = pnConfigGetVar('anonwaitdays');
     	// echo '<div style="text-align:center"><strong>'._RATENOTE2ERROR1." $anonwaitdays "._RATENOTE2ERROR2.'</strong></div><br />';
        echo '<div style="text-align:center"><strong>'._RATENOTE2ERROR.'</strong></div><br />';
    }
    elseif ($error == "regflood")
    {
        echo '<div style="text-align:center"><strong>'._RATENOTE3ERROR.'</strong></div><br />';
    }
    elseif ($error == "postervote")
    {
        echo '<div style="text-align:center"><strong>'._RATENOTE4ERROR.'</strong></div><br />';
    }
    elseif ($error == "nullerror")
    {
        echo '<div style="text-align:center"><strong>'._RATENOTE5ERROR.'</strong></div><br />';
    }
    elseif ($error == "outsideflood")
    {
        $outsidewaitdays = pnConfigGetVar('outsidewaitdays');
        echo "<div style=\"text-align:center\"><strong>Only one vote per IP address allowed every $outsidewaitdays day(s).</strong></div><br />";
    }
}

/**
 * @usedby index
 */
function ratelink($lid) {
    include 'header.php';

	if (!(pnSecAuthAction(0, 'Web Links::', '::', ACCESS_COMMENT))) {
		echo _WEBLINKSNOAUTH;
		include 'footer.php';
        return false;
	}

    if ((!isset($lid) || !is_numeric($lid))){
        echo _MODARGSERROR;
		include('footer.php');
        return false;
    }

    menu(1);

    OpenTable();
    $displaytitle = displaytitle($lid);
    $ip = pnServerGetVar("REMOTE_HOST");
    if (empty($ip)) {
       $ip = pnServerGetVar("REMOTE_ADDR");
    }
    echo '<strong>'.pnVarPrepForDisplay($displaytitle).'</strong>'
    ."<ul>"
    ."<li>"._RATENOTE1."</li>"
    ."<li>"._RATENOTE2."</li>"
    ."<li>"._RATENOTE3."</li>"
    ."<li>"._RATENOTE4."</li>"
    ."<li>"._RATENOTE5."</li>";
    if (pnUserLoggedIn()) {
        $name = pnUserGetVar('uname');
        echo "<li>"._YOUAREREGGED."</li>"
            ."<li>"._FEELFREE2ADD."</li>";
    } else {
        echo "<li>"._YOUARENOTREGGED."</li>"
            ."<li>"._IFYOUWEREREG."</li>";
        $name = pnConfigGetVar("anonymous");
    }
    echo "</ul>"
        ."<form method=\"post\" action=\"".$GLOBALS['modurl']."&amp;req=addrating\"><div>"
        ."<input type=\"hidden\" name=\"ratinglid\" value=\"$lid\" />"
        ."<input type=\"hidden\" name=\"ratinguser\" value=\"$name\" />"
        ."<input type=\"hidden\" name=\"ratinghost_name\" value=\"$ip\" />"
        ._RATETHISSITE."&nbsp;&nbsp;"
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
        ."<input type=\"submit\" value=\""._RATETHISSITE."\" />"
        .'<br />';
    if (pnUserLoggedIn()) {
        echo '<strong>'._COMMENT." :</strong><br /><textarea cols=\"80\" rows=\"10\" name=\"ratingcomments\"></textarea>"
            .'<br />';
    } else {
        echo"<input type=\"hidden\" name=\"ratingcomments\" value=\"\" />";
    }
    echo "</div></form>";
    echo "<div style=\"text-align:center\">";
    linkfooterchild($lid);
    echo '</div>';
    CloseTable();
    include 'footer.php';
}
?>