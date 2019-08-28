<?php
// File: $Id: weblinks.php 15630 2005-02-04 06:35:42Z jorg $
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
// Original Author of file: Patrick Kellum
// Purpose of file: Display the recent links added to the WEb Links module
// ----------------------------------------------------------------------

if (strpos($_SERVER['PHP_SELF'], 'weblinks.php')) {
	die ("You can't access this file directly...");
}

$blocks_modules['weblinks'] = array (
    'func_display' => 'blocks_weblinks_display',
    'func_add' => 'blocks_weblinks_add',
    'func_update' => 'blocks_weblinks_update',
    'func_edit' => 'blocks_weblinks_edit',
    'text_type' => 'Weblinks',
    'text_type_long' => 'Latest Web Links',
    'allow_multiple' => true,
    'form_content' => false,
    'form_refresh' => false,
    'show_preview' => true
);

pnSecAddSchema('Weblinksblock::', 'Block title::');

function blocks_weblinks_display($row)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();


    if (!pnSecAuthAction(0, 'Weblinksblock::', "$row[title]::", ACCESS_READ)) {
        return;
    }

    $url = explode('|', $row['url']);
    if (!$url[0])
    {
        $row['content'] = 'You forgot to set the module name!';
        return themesideblock($row);
    }
    if (!$url[1])
    {
        $url[1] = 10;
    }

	$links_col = &$pntable['links_links_column'];
	$linksok = 0;
	$linkcount = 0;
	$result =& $dbconn->Execute("SELECT $links_col[cat_id], $links_col[title] FROM $pntable[links_links] ORDER BY $links_col[date] DESC");
	while(list($cid, $title)=$result->fields) {
		$result->MoveNext();
        $linkcount++;
        if (pnSecAuthAction(0, "Web Links::Category", "$title::$cid", ACCESS_READ)) {
			$linksok++;
        }
        if ($linksok == $url[1]) {
			break;
        }
      }
	$oldurl=$url[1];
    $url[1] = $linkcount;

    $row['content'] = '<ul>';
    $links_col = &$pntable['links_links_column'];
    $cats_col = &$pntable['links_categories_column'];
	$sql = "SELECT $links_col[lid] as lid, $links_col[cat_id] as catid, $links_col[title] as title, $links_col[description] as description, $links_col[hits] as hits, IF($links_col[cat_id], CONCAT('/', $cats_col[title]), $cats_col[title]) AS cattitle
               FROM $pntable[links_links]
               LEFT JOIN $pntable[links_categories]
               ON $cats_col[cat_id]=$links_col[cat_id]
               ORDER BY $links_col[date] DESC";
			   
    $result = $dbconn->SelectLimit($sql,$url[1]);

    while(!$result->EOF) {
        $lrow = $result->GetRowAssoc(false);
      	if (pnSecAuthAction(0, "Web Links::Category", "$lrow[cattitle]::$lrow[catid]", ACCESS_READ)) {
	        $lrow['title'] = pnVarPrepForDisplay($lrow['title']);
    	    $lrow['description'] = pnVarPrepHTMLDisplay($lrow['description']);
    	    $lrow['cattitle'] = pnVarPrepForDisplay($lrow['cattitle']);
    	    $row['content'] .= "<li><a href=\"index.php?name=$url[0]&amp;req=visit&amp;lid=$lrow[lid]\" title=\"$lrow[cattitle]:\n$lrow[description]\">$lrow[title]</a></li>\n";
    	    $result->MoveNext();
   		}
    }
    //$row['content'] .= "<div align=\"right\"><span class=\"pn-sub\"><a href=\"index.php?op=modload&name=Web_Links&file=index&req=NewLinks&newlinkshowdays=10\">"._READMORE."</a></span></div>";
    $row['content'] .= '</ul>';
    return themesideblock($row);
}
function blocks_weblinks_add($row)
{
    $row['url'] = 'Web_Links|10';
    return $row;
}
function blocks_weblinks_update($vars)
{
    $vars['url'] = "$vars[weblinks_modname]|$vars[weblinks_total]";
    return $vars;
}
function blocks_weblinks_edit($row)
{
    if (!empty($row['url'])) {
        $url = explode('|', $row['url']);
        $wlmodname = $url[0];
        $wltotal = $url[1];
    } else {
        $wlmodname = 'Web_Links';
        $wltotal = 10;
    }
    $output = '<tr><td valign="top">Module Name:</td><td>'
        ."<input type=\"text\" name=\"weblinks_modname\" size=\"30\" maxlength=\"255\" value=\"$wlmodname\" />"
        ."</td></tr>\n"
    ;
    $output .= '<tr><td valign="top">Total Links:</td><td>'
        ."<input type=\"text\" name=\"weblinks_total\" size=\"30\" maxlength=\"255\" value=\"$wltotal\" />"
        ."</td></tr>\n"
    ;
    return $output;
}

?>