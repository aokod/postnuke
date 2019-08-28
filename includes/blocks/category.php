<?php
// File: $Id: category.php 16638 2005-08-15 10:22:38Z landseer $
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

if (strpos($_SERVER['PHP_SELF'], 'category.php')) {
	die ("You can't access this file directly...");
}

$blocks_modules['category'] = array(
    'func_display' => 'blocks_category_block',
    'text_type' => 'Category',
    'text_type_long' => 'Categories Menu',
    'allow_multiple' => false,
    'form_content' => false,
    'form_refresh' => false,
//  'support_xhtml' => true,
    'show_preview' => true
);

// Security
pnSecAddSchema('Categoryblock::', 'Block title::');

function blocks_category_block($row)
{
    global $topic, $catid;

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!pnSecAuthAction(0, 'Categoryblock::', "$row[title]::", ACCESS_READ)) {
        return;
    }

    if (pnConfigGetVar('multilingual') == 1) {
        $column = &$pntable['stories_column'];
        $querylang = "AND ($column[alanguage]='" . pnVarPrepForStore(pnUserGetLang()) . "' OR $column[alanguage]='')"; /* the OR is needed to display stories who are posted to ALL languages */
    } else {
        $querylang = '';
    }
    $column = &$pntable['stories_cat_column'];
    $result =& $dbconn->Execute("SELECT $column[catid] as catid, $column[title] as title FROM $pntable[stories_cat] ORDER BY $column[title]");
    if ($result->EOF) {
        return;
    } else {
        $boxstuff = '<ul>';
        if ($catid == '') {
            // $boxstuff .= '<strong><big>&middot;</big></strong>&nbsp;<strong>'._ALL_CATEGORIES.'</strong><br />';
			$boxstuff .= '';
        } else {
        $boxstuff .= "<li><a href=\"index.php?name=News&amp;topic=$topic\">"._ALL_CATEGORIES."</a></li>";
        }
        for(;!$result->EOF;$result->MoveNext() ) {
            $srow = $result->GetRowAssoc(false);
            if (pnSecAuthAction(0, 'Stories::Category', "$srow[title]::$srow[catid]", ACCESS_READ)) {
                $column = &$pntable['stories_column'];
                $result2 =& $dbconn->Execute("SELECT $column[time] AS unixtime
                                           FROM $pntable[stories]
                                           WHERE $column[catid]=".pnVarPrepForStore($srow['catid'])." $querylang
                                           ORDER BY $column[time] DESC");
                if (!$result2->EOF) {
                    $story = $result2->GetRowAssoc(false);
                    $story['unixtime']=$result2->UnixTimeStamp($story['unixtime']);
                    $sdate = ml_ftime(_DATEBRIEF, $story['unixtime']);
                    if ($catid == $srow['catid']) {
                        $boxstuff .= "<li><h2>".pnVarPrepForDisplay($srow['title'])."</h2> <span class=\"pn-sub\">(".pnVarPrepForDisplay($sdate).")</span></li>";
                    } else {
                      	$boxstuff .= "<li><a href=\"index.php?name=News&amp;catid=$srow[catid]&amp;topic=$topic\">".pnVarPrepForDisplay($srow['title'])."</a> <span class=\"pn-sub\">(".pnVarPrepForDisplay($sdate).")</span></li>";
                    }
                }
            }
        }
        $boxstuff .= '</ul>';
    }
    if (empty($row['title'])) {
        $row['title'] = _CATEGORIES;
    }
    $row['content'] = $boxstuff;
    return themesideblock($row);
}

?>