<?php
// File: $Id: dl-downloadeditorial.php 15630 2005-02-04 06:35:42Z jorg $
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
function viewdownloadeditorial($lid)
{
    include 'header.php';

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
    if (!(pnSecAuthAction(0, 'Downloads::Item', "$itemname:$catname:$lid", ACCESS_READ))) {
        echo _DOWNLOADSACCESSNOAUTH;
        include 'footer.php';
        return;
    }

    $column = &$pntable['downloads_editorials_column'];
    $result =& $dbconn->Execute("SELECT $column[adminid], $column[editorialtimestamp],
                            $column[editorialtext], $column[editorialtitle]
                            FROM $pntable[downloads_editorials]
                            WHERE $column[downloadid] = '".(int)pnVarPrepForStore($lid)."'");

    //$transfertitle = ereg_replace ("_", " ", $ttitle);
    //$displaytitle = $transfertitle;
	$displaytitle = displaytitle($lid);
	
    OpenTable();
    echo '<h2>'._DOWNLOADPROFILE.": ".pnVarPrepForDisplay($displaytitle)."</h2><br />";
    downloadinfomenu($lid, displaytitle($lid));
    if (!$result->EOF) {
        while(list($adminid, $editorialtimestamp, $editorialtext, $editorialtitle)=$result->fields) {
            $editorialtitle = stripslashes($editorialtitle); 
			$editorialtext = stripslashes($editorialtext);
			//transform hooks
			list($editorialtext) = pnModCallHooks('item', 'transform', '', array($editorialtext));

/* cocomp 2002/07/13 Let ADODB handle date stuff
            ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $editorialtimestamp, $editorialtime);
            $timestamp = mktime($editorialtime[4],$editorialtime[5],$editorialtime[6],$editorialtime[2],$editorialtime[3],$editorialtime[1]);
            $formatted_date = date("F d, Y", $timestamp);
*/
			$formatted_date = ml_ftime(_DATELONG, $dbconn->UnixTimestamp($editorialtimestamp));
			$uname = pnUserGetVar('uname', $adminid);

            OpenTable2();
            echo '<h2>'.pnVarPrepForDisplay($editorialtitle).'</h2>'
            ."<div style=\"text-align:center\">"._EDITORIALBY." ".pnVarPrepForDisplay($uname)." - ".pnVarPrepForDisplay($formatted_date).'</div><br />'
            .pnVarPrepHTMLDisplay($editorialtext);
            CloseTable2();
            $result->MoveNext();
         }
    } else {
        echo "<br /><h2>"._NOEDITORIAL.'</h2>';
    }
    echo '<br />';
    downloadfooter($lid,displaytitle($lid));
    CloseTable();
    include 'footer.php';
}

?>