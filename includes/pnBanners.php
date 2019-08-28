<?php
// File: $Id: pnBanners.php 20193 2006-10-04 07:19:59Z markwest $
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
// Purpose of file: Display banners
// ----------------------------------------------------------------------
/**
 * @package PostNuke_Core
 * @subpackage PostNuke_Banners
 */

/**
 * Function to display banners in all pages
 */

function pnBannerDisplay($type=0)
{
    // test on config settings
    if (pnConfigGetVar('banners') != 1) return '&nbsp;';

	// added check for numeric type - markwest
	if (!is_numeric($type)) return '&nbsp;';

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column = &$pntable['banner_column'];
    $bresult =& $dbconn->Execute("SELECT count(*) AS count FROM $pntable[banner]
								WHERE $column[type] = '".(int)pnVarPrepForStore($type)."'");
    list($numrows) = $bresult->fields;
    // we no longer need this, free the resources
    $bresult->Close();

    /* Get a random banner if exist any. */
    /* More efficient random stuff, thanks to Cristian Arroyo from http://www.planetalinux.com.ar */

    if ($numrows>1) {
        $numrows = $numrows-1;
        mt_srand((double)microtime()*1000000);
        $bannum = mt_rand(0, $numrows);
    } else {
        $bannum = 0;
    }

    $column = &$pntable['banner_column'];
    //$query = buildSimpleQuery ('banner', array ('bid', 'imageurl','clickurl'), "$column[type] = $type", '', 1, $bannum);
	$query = "SELECT $column[bid], $column[imageurl], $column[clickurl]
				FROM $pntable[banner]
				WHERE $column[type] = '".(int)pnVarPrepForStore($type)."'";
    $bresult2 =& $dbconn->SelectLimit($query,1,$bannum);
    list($bid, $imageurl, $clickurl) = $bresult2->fields;
    // we no longer need this, free the resources
    $bresult2->Close();

    $myIP = pnConfigGetVar('myIP');

    $myhost = pnServerGetVar("REMOTE_ADDR");

    if (!empty($myIP) && substr($myhost, 0, strlen($myIP)) == $myIP) {
        // itevo, MNA:  added temporary variable to check when inserting a finished banner (insert only when variable is not set)
		$ignore_bannerfinish = 1;
    } else {
        $dbconn->Execute("UPDATE $pntable[banner]
                        SET $column[impmade]=$column[impmade]+1
                        WHERE $column[bid]=".(int)pnVarPrepForStore($bid)."");
    }

    if ($numrows > 0) {
        $aborrar =& $dbconn->Execute("SELECT $column[cid],$column[imptotal],
                                          $column[impmade], $column[clicks],
                                          $column[date]
                                   FROM $pntable[banner]
                                   WHERE $column[bid]=".(int)pnVarPrepForStore($bid)."");
        list($cid, $imptotal, $impmade, $clicks, $date) = $aborrar->fields;
        $aborrar->Close();

        /* Check if this impression is the last one and print the banner */
		if ($imptotal == $impmade && !isset($ignore_bannerfinish)) {
            $column = &$pntable['bannerfinish_column'];
            $dbconn->Execute("INSERT INTO $pntable[bannerfinish]
                            ( $column[bid], $column[cid], $column[impressions], $column[clicks], $column[datestart], $column[dateend] )
                            VALUES (NULL, '".pnVarPrepForStore($cid)."', '".pnVarPrepForStore($impmade)."', '".pnVarPrepForStore($clicks)."', '".pnVarPrepForStore($date)."', now())");
            $dbconn->Execute("DELETE FROM $pntable[banner] WHERE $column[bid]=".(int)pnVarPrepForStore($bid)."");
        }
        list ($bid, $clickurl, $imageurl) = pnVarPrepForDisplay($bid, $clickurl, $imageurl);
		if ($type == 1 or $type == 2  or $type == 0) {
			echo "<a href=\"banners.php?op=click&amp;bid=$bid\" title=\"$clickurl\"><img src=\"$imageurl\" alt=\"$clickurl\" /></a>";
		} else {
			$content = "<a href=\"banners.php?op=click&amp;bid=$bid\" title=\"$clickurl\"><img src=\"$imageurl\" alt=\"$clickurl\" /></a>";
			return $content;
		}
    }
}

?>