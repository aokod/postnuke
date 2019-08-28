<?php
// File: $Id: dl-newdownloads.php 15951 2005-03-08 21:07:03Z larsneo $
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

function NewDownloads($newdownloadshowdays)
{

    include 'header.php';

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    menu(1);

    if (!pnSecAuthAction(0, 'Downloads::', '::', ACCESS_READ)) {
        echo _DOWNLOADSACCESSNOAUTH;
        include 'footer.php';
        return;
    }

	// limit the number of days to 365
    if ($newdownloadshowdays < 0 || $newdownloadshowdays > 365) {
        echo _MODARGSERROR;
        include 'footer.php';
        return;
    }

    OpenTable();
    echo '<div style="text-align:center"><h2>'._NEWDOWNLOADS.'</h2></div>';
    $allweekdownloads = 0;
    $allmonthdownloads = 0;
    for ($counter = 0; $counter < 7; $counter++){
        $newdownloaddayRaw = (time()-(86400 * $counter));
        $newdownloadday = date("d-M-Y", $newdownloaddayRaw);
        //$newdownloadView = date("F d, Y", $newdownloaddayRaw);
        $newdownloadView = ml_ftime(_DATEBRIEF, $newdownloaddayRaw);
        $newdownloadDB = Date("Y-m-d", $newdownloaddayRaw);
/* cocomp 2002/07/13 cross db compatibility - can't compare dates using LIKE '%".$date."%'
        $result =& $dbconn->Execute("SELECT count(*) FROM ".$pntable['downloads_downloads'].
        " WHERE ".$pntable['downloads_downloads_column']['date']." LIKE '%".pnVarPrepForStore($newdownloadDB)."%'");
*/
	$newdownloadDB_upper = date("Y-m-d", $newdownloaddayRaw + 86400);
	$sql = "SELECT count(*) FROM ".$pntable['downloads_downloads'] .
		" WHERE " . $pntable['downloads_downloads_column']['date'] . " >= '" . pnVarPrepForStore($newdownloadDB) . "'" .
		" AND " . $pntable['downloads_downloads_column']['date'] . " < '" . pnVarPrepForStore($newdownloadDB_upper) . "'";
	$result =& $dbconn->Execute($sql);
        list($totaldownloads) = $result->fields;
        $allweekdownloads = $allweekdownloads + $totaldownloads;
    }
    for ($counter = 0; $counter < 30; $counter++){
        $newdownloaddayRaw = (time()-(86400 * $counter));
        $newdownloadDB = Date("Y-m-d", $newdownloaddayRaw);
/* cocomp 2002/07/13 cross db compatibility - can't compare dates using LIKE '%".$date."%'
        $result =& $dbconn->Execute("SELECT count(*) FROM ".$pntable['downloads_downloads'].
        " WHERE ".$pntable['downloads_downloads_column']['date']." LIKE '%".pnVarPrepForStore($newdownloadDB)."%'");
*/
	$newdownloadDB_upper = date("Y-m-d", $newdownloaddayRaw + 86400);
	$sql = "SELECT count(*) FROM ".$pntable['downloads_downloads'] .
		" WHERE " . $pntable['downloads_downloads_column']['date'] . " >= '" . pnVarPrepForStore($newdownloadDB) . "'" .
		" AND " . $pntable['downloads_downloads_column']['date'] . " < '" . pnVarPrepForStore($newdownloadDB_upper) . "'";
	$result =& $dbconn->Execute($sql);
        list($totaldownloads) = $result->fields;
        $allmonthdownloads = $allmonthdownloads + $totaldownloads;
    }
    echo '<div style="text-align:center">'._TOTALNEWDOWNLOADS.": "._LASTWEEK.": ".pnVarPrepForDisplay($allweekdownloads)." | "._LAST30DAYS.": ".pnVarPrepForDisplay($allmonthdownloads).'<br />'
    ._SHOW.": <a href=\"".$GLOBALS['modurl']."&amp;req=NewDownloads&amp;newdownloadshowdays=7\">"._1WEEK."</a> | <a href=\"".$GLOBALS['modurl']."&amp;req=NewDownloads&amp;newdownloadshowdays=14\">"._2WEEKS."</a> | <a href=\"".$GLOBALS['modurl']."&amp;req=NewDownloads&amp;newdownloadshowdays=30\">"._30DAYS."</a>";
    /* List Last VARIABLE Days of Downloads */
    if (!isset($newdownloadshowdays)) {
        $newdownloadshowdays = 7;
    }
    echo "<br /><br /><strong>"._DTOTALFORLAST." ".pnVarPrepForDisplay($newdownloadshowdays)." "._DAYS.":</strong><br />";
    $allweekdownloads = 0;
    for ($counter = 0; $counter < $newdownloadshowdays; $counter++) {
        $newdownloaddayRaw = (time()-(86400 * $counter));
        $newdownloadday = date("d-M-Y", $newdownloaddayRaw);
        //$newdownloadView = date("F d, Y", $newdownloaddayRaw);
        $newdownloadView = ml_ftime(_DATEBRIEF, $newdownloaddayRaw);
        $newdownloadDB = Date("Y-m-d", $newdownloaddayRaw);
/* cocomp 2002/07/13 cross db compatibility - can't compare dates using LIKE '%".$date."%'
        $result =& $dbconn->Execute("select count(*) FROM ".$pntable['downloads_downloads'].
        " WHERE ".$pntable['downloads_downloads_column']['date']." LIKE '%".pnVarPrepForStore($newdownloadDB)."%'");
*/
	$newdownloadDB_upper = date("Y-m-d", $newdownloaddayRaw + 86400);
	$sql = "SELECT count(*) FROM ".$pntable['downloads_downloads'] .
		" WHERE " . $pntable['downloads_downloads_column']['date'] . " >= '" . pnVarPrepForStore($newdownloadDB) . "'" .
		" AND " . $pntable['downloads_downloads_column']['date'] . " < '" . pnVarPrepForStore($newdownloadDB_upper) . "'";
	$result =& $dbconn->Execute($sql);
        list($totaldownloads) = $result->fields;
        $allweekdownloads = $allweekdownloads + $totaldownloads;
        echo "<strong><big>&middot;</big></strong> <a href=\"".$GLOBALS['modurl']."&amp;req=NewDownloadsDate&amp;selectdate=$newdownloaddayRaw\">".pnVarPrepForDisplay($newdownloadView)."</a>&nbsp;(".pnVarPrepForDisplay($totaldownloads).")<br />";
    }
    $counter = 0;
    $allmonthdownloads = 0;
    echo '</div>';
    CloseTable();
    include 'footer.php';
}

function NewDownloadsDate($selectdate)
{

    $dateDB = (date("d-M-Y", $selectdate));
    //$dateView = (date("F d, Y", $selectdate));
    $dateView = ml_ftime(_DATELONG, $selectdate);
    include('header.php');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    menu(1);


    if (!pnSecAuthAction(0, 'Downloads::', '::', ACCESS_READ)) {
        echo _DOWNLOADSACCESSNOAUTH;
        include 'footer.php';
        return;
    }

    OpenTable();
    $newdownloadDB = Date("Y-m-d", $selectdate);
/* cocomp 2002/07/13 cross db compatibility - can't compare dates using LIKE '%".$date."%'
    $result =& $dbconn->Execute("SELECT count(*) FROM $pntable[downloads_downloads] WHERE {$pntable['downloads_downloads_column']['date']} LIKE '%".pnVarPrepForStore($newdownloadDB)."%'");
*/
	$newdownloadDB_upper = date("Y-m-d", $selectdate + 86400);
	$sql = "SELECT count(*) FROM ".$pntable['downloads_downloads'] .
		" WHERE " . $pntable['downloads_downloads_column']['date'] . " >= '" . pnVarPrepForStore($newdownloadDB) . "'" .
		" AND " . $pntable['downloads_downloads_column']['date'] . " < '" . pnVarPrepForStore($newdownloadDB_upper) . "'";
	$result =& $dbconn->Execute($sql);
    list($totaldownloads) = $result->fields;
    echo '<h2>'.pnVarPrepForDisplay($dateView)." - ".pnVarPrepForDisplay($totaldownloads)." "._NEWDOWNLOADS.'</h2>';
    $column = &$pntable['downloads_downloads_column'];
/* cocomp 2002/07/13 cross db compatibility - can't compare dates using LIKE '%".$date."%'
    $result =& $dbconn->Execute("SELECT $column[lid], $column[cid], $column[sid], $column[url],
                             $column[title], $column[description], $column[date],
                             $column[hits], $column[downloadratingsummary],
                             $column[totalvotes], $column[totalcomments],
                             $column[filesize], $column[version], $column[homepage]
                            FROM $pntable[downloads_downloads]
                            WHERE $column[date] LIKE '%".pnVarPrepForStore($newdownloadDB)."%'
                            ORDER BY $column[title] ASC");
*/
	$sql = "SELECT $column[lid], $column[cid], $column[sid], $column[url],
		$column[title], $column[description], $column[date],
		$column[hits], $column[downloadratingsummary],
		$column[totalvotes], $column[totalcomments],
		$column[filesize], $column[version], $column[homepage]
		FROM $pntable[downloads_downloads]
		WHERE $column[date] >= '" . pnVarPrepForStore($newdownloadDB) . "'
		AND $column[date] < '" . pnVarPrepForStore($newdownloadDB_upper) . "'
		ORDER BY $column[title] ASC";
	$result =& $dbconn->Execute($sql);
    while(list($lid, $cid, $sid, $url, $title, $description, $time, $hits, $downloadratingsummary, $totalvotes, $totalcomments, $filesize, $version, $homepage)=$result->fields) {
        $result->MoveNext();
		downloads_outputitem ($lid, $url, $title, nl2br($description), $time, $hits, $downloadratingsummary, $totalvotes, $totalcomments, $filesize, $version, $homepage, $GLOBALS['modurl'], $GLOBALS['ModName']);
    }
    CloseTable();
    include 'footer.php';
}
?>