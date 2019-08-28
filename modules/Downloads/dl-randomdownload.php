<?php
// File: $Id: dl-randomdownload.php 15630 2005-02-04 06:35:42Z jorg $
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
 * RandomDownload
 */
function RandomDownload()
{
    include 'header.php';

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    menu(1);

    $result =& $dbconn->Execute("SELECT COUNT(*) FROM $pntable[downloads_downloads]");
    list($numrows) = $result->fields;
    if ($numrows < 1 ) { // if no data
        OpenTable();
        echo '<div style="text-align:center"><strong>'._downloadNODATA."</strong><br />\n";
	    echo _GOBACK."\n";
    	CloseTable();
    	include 'footer.php';
    	return;
    }

    if ($numrows == 1) {
        $random = 1;
    } else {
        srand((double)microtime()*1000000);
        $random = rand(1,$numrows);
    }

    do {
        $column = &$pntable['downloads_downloads_column'];
        $result =& $dbconn->Execute("SELECT $column[url] FROM $pntable[downloads_downloads] WHERE $column[lid]='".pnVarPrepForStore($random)."'");
        list($url) = $result->fields;
        $itemname = downloads_ItemNameFromIID($random);
        $catname = downloads_CatNameFromIID($random);

    } while (!pnSecAuthAction(0, 'Downloads::Item', "$itemname:$catname:$random"));

    $dbconn->Execute("UPDATE $pntable[downloads_downloads] SET $column[hits]=".pnVarPrepForStore($column['hits'])."+1 WHERE $column[lid]='".pnVarPrepForStore($random)."'");
    Header('Location: '.$url);
}

?>