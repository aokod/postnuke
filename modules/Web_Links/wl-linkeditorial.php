<?php
// File: $Id: wl-linkeditorial.php 15953 2005-03-08 21:48:59Z larsneo $
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
function viewlinkeditorial($lid)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    include('header.php');
        if (!(pnSecAuthAction(0, 'Web Links::', '::', ACCESS_READ))) {
            echo _WEBLINKSNOAUTH;
            include 'footer.php';
            return;
        }

    menu(1);

    $column = &$pntable['links_editorials_column'];
    $result =& $dbconn->Execute("SELECT $column[adminid], $column[editorialtimestamp], $column[editorialtext], $column[editorialtitle] 
                            FROM $pntable[links_editorials] 
                            WHERE $column[linkid]=".(int)pnVarPrepForStore($lid)."");

    $displaytitle = displaytitle($lid);

    OpenTable();
    echo '<div style="text-align:center"><strong>'._LINKPROFILE.": ".pnVarPrepForDisplay($displaytitle).'</strong></div><br />';
    linkinfomenu($lid, displaytitle($lid));
    echo '<br />';
    if (!$result->EOF) {
    while(list($adminid, $editorialtimestamp, $editorialtext, $editorialtitle)=$result->fields) {

        $result->MoveNext();

        OpenTable2();
        $formatted_date = ml_ftime(_DATELONG, $dbconn->UnixTimestamp($editorialtimestamp));
		$uname = pnUserGetVar('uname', $adminid);

		//transform hooks
		list($editorialtext) = pnModCallHooks('item', 'transform', '', array($editorialtext));

        echo '<div style="text-align:center"><strong>'.pnVarPrepForDisplay($editorialtitle)."</strong></div>"
        .'<div style="text-align:center">'._EDITORIALBY." ".pnVarPrepForDisplay($uname)." - ".pnVarPrepForDisplay($formatted_date)."</div><br />"
        .pnVarPrepHTMLDisplay($editorialtext);
        CloseTable2();
     }
    } else {
        echo "<br /><div style=\"text-align:center\"><strong>"._NOEDITORIAL."</strong></div>";
    }
    echo '<br /><div style="text-align:center">';
    linkfooter($lid,displaytitle($lid));
    echo '</div>';
    CloseTable();
    include('footer.php');
}
?>