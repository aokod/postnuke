<?php
// File: $Id: dl-navigation.php 19177 2006-06-01 12:47:52Z markwest $
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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Francisco Burzi
// Purpose of file:
// ----------------------------------------------------------------------

/**
* index
* Display the main links categories
*/
function index() {
	include('header.php');

	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();

	$maindownload = 0;

	if (!pnSecAuthAction(0, 'Downloads::', '::', ACCESS_READ)) {
		echo _DOWNLOADSACCESSNOAUTH;
		include 'footer.php';
		exit;
	}

	$column = &$pntable['downloads_categories_column'];
	$result =& $dbconn->Execute("SELECT $column[cid], $column[title], $column[cdescription]
								FROM $pntable[downloads_categories]
								ORDER BY $column[title]");
	$numcats = $result->PO_RecordCount();
	if ($numcats == 0) {
		echo _DOWNLOADSNOCATS;
		include 'footer.php';
	} else {
		menu($maindownload);
        $GLOBALS['info']['title'] = _DLOADPAGETITLE;
		OpenTable();
		// Main categories
		echo '<h2>'._DOWNLOADSMAINCAT.'</h2>';
		echo "<table border=\"0\" cellspacing=\"10\" cellpadding=\"10\" width=\"98%\" summary=\""._DOWNLOADSMAINCAT."\">";

		$count = 0;
		while(list($cid, $title, $cdescription) = $result->fields)
		{
			$result->MoveNext();
			/* Hide this download if have no access to it */
			if (!pnSecAuthAction(0, 'Downloads::Category', "$title::$cid", ACCESS_READ)) {
				continue;
			}
			if ($count == 0) {
				// we need to start a row
				echo "<tr>";
			}
			$count++;
			$cresult =& $dbconn->Execute("SELECT count(*) FROM " . $pntable['downloads_downloads'] . " WHERE " .
			$pntable['downloads_downloads_column']['cid'] . "='" . pnVarPrepForStore($cid) . "'");
			list($cnumrows) = $cresult->fields;

			echo "<td valign=\"top\" style=\"width:50%\">"
				."<h3><img src=\"modules/".$GLOBALS['ModName']."/images/icon_folder.gif\" height=\"13\" width=\"15\" alt=\"\" />&nbsp;&nbsp;"
				."<a href=\"".$GLOBALS['modurl']."&amp;req=viewdownload&amp;cid=$cid\">".pnVarPrepForDisplay($title)."</a>"
				."&nbsp;(" . pnVarPrepForDisplay($cnumrows) . ")";
			categorynewdownloadgraphic($cid);
			echo '</h3>';

			if ($cdescription) {
				echo pnVarPrepHTMLDisplay($cdescription).'<br />';
			}

			if ($count==1) {
				// next table cell
				echo "</td>";
			}

			if ($count==2) {
				// two entries, start the next table row
				echo "</td></tr>";
				$count = 0;
			}
		} //While

		if ($count == 1) {
			// we need
			echo "<td>&nbsp;</td></tr>";
		}

		echo "</table>";

		/*
		* SHOULD USE A UNION BUT USING MYSQL 3.x SO CAN'T RELY ON IT WORKING
		* JGIORDANO
		* hootbah: FIXME
		* This can be done in one database hit.
		*/
		$result =& $dbconn->Execute("SELECT COUNT(*) FROM $pntable[downloads_downloads]");
		list($numrows) = $result->fields;
		$result =& $dbconn->Execute("SELECT COUNT(*) FROM $pntable[downloads_categories]");
		list($catnum1) = $result->fields;
		$result =& $dbconn->Execute("SELECT COUNT(*) FROM $pntable[downloads_subcategories]");
		list($catnum2) = $result->fields;
		$catnum = $catnum1+$catnum2;

		echo "<br /><br /><div style=\"text-align:center\">"._THEREARE." <strong>" . pnVarPrepForDisplay($numrows) . '</strong> '._DOWNLOADS." "._AND." <strong>" . pnVarPrepForDisplay($catnum) . '</strong> '._CATEGORIES." "._INDB."</div>";

		CloseTable();
		include("footer.php");
	}
}


/**
* menu
* builds the standard navigation menu
* @param maindownload integer switch. 1 means show _DOWNLOADSMAIN, 0 not.
*/
function menu($maindownload) {
	
	if (!pnSecAuthAction(0, 'Downloads::', '::', ACCESS_READ)) {
		echo _DOWNLOADSACCESSNOAUTH;
		include 'footer.php';
		return;
	}

	$user_adddownload = pnConfigGetVar('user_adddownload');
	$query = pnVarCleanFromInput('query');
	$query = isset($query) ? $query : '';
	
	
	OpenTable();
	echo "<div style=\"text-align:center\"><h1>"._DLOADPAGETITLE."</h1>";
	echo "<form action=\"modules.php\" method=\"post\" id=\"".str_replace(' ','_',_UDOWNLOADS)."\"><div>"
		."\n<input type=\"hidden\" name=\"name\" value=\"".$GLOBALS['ModName']."\" />"
		."\n<input type=\"hidden\" name=\"req\" value=\"search\" />"
		."\n<input type=\"hidden\" name=\"query\" value=\"".pnVarPrepForDisplay($query)."\" />"
		."\n<label for=\"query\">"._UDOWNLOADS."</label>"
		."\n <input type=\"text\" size=\"25\" name=\"query\" id=\"query\" tabindex=\"0\" 
										value=\""._SEARCH_KEYWORDS."\" 
										onblur=\"if(this.value=='')this.value='"._SEARCH_KEYWORDS."';\"
										onfocus=\"if(this.value=='"._SEARCH_KEYWORDS."')this.value='';\" /> "
		."<input type=\"submit\" value=\""._SEARCH."\" />"
		."\n</div></form>";
	echo "[ ";
	if ($maindownload>0) {
		echo "<a href=\"".$GLOBALS['modurl']."\">"._DOWNLOADSMAIN."</a> | ";
	}
	if (pnSecAuthAction(0, 'Downloads::Item', '::', ACCESS_COMMENT)) {
		echo "<a href=\"".$GLOBALS['modurl']."&amp;req=AddDownload\">"._ADDDOWNLOAD."</a>"
			." | ";
	}
	echo "<a href=\"".$GLOBALS['modurl']."&amp;req=NewDownloads\">"._NEW."</a>"
		." | <a href=\"".$GLOBALS['modurl']."&amp;req=MostPopular\">"._POPULAR."</a>"
		." | <a href=\"".$GLOBALS['modurl']."&amp;req=TopRated\">"._TOPRATED."</a> ]"
		.'</div>';
	CloseTable();
}

?>