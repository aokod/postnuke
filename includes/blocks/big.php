<?php
// File: $Id: big.php 15630 2005-02-04 06:35:42Z jorg $
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

if (strpos($_SERVER['PHP_SELF'], 'big.php')) {
	die ("You can't access this file directly...");
}

$blocks_modules['big'] = array(
    'func_display' => 'blocks_big_block',
    'text_type' => 'Big',
    'text_type_long' => "Today's Big Story",
    'allow_multiple' => false,
    'form_content' => false,
    'form_refresh' => false,
//  'support_xhtml' => true,
    'show_preview' => true
);

// Get news helper functions
include_once('modules/News/funcs.php');

// Security
pnSecAddSchema('Bigblock::', 'Block title::');

function blocks_big_block($row)
{
    $pntable =& pnDBGetTables();

    if (!pnSecAuthAction(0, 'Bigblock::', "$row[title]::", ACCESS_READ)) {
        return;
    }
    $today = getdate();
    $day = $today['mday'];
    if ($day < 10) {
        $day = "0$day";
    }
    $month = $today['mon'];
    if ($month < 10) {
        $month = "0$month";
    }
    $year = $today['year'];
    $tdate = "$year-$month-$day";

    $column = &$pntable['stories_column'];
    $articles = getArticles("$column[time] LIKE '%$tdate%'
                             AND $column[ihome] = 0
                             AND $column[counter] > 0", "$column[counter] DESC", "1");
    if (empty($articles)) {
        return;
    } else {
        $info = genArticleInfo($articles[0]);
        if (pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_READ) &&
            pnSecAuthAction(0, 'Topics::Topic',"$info[topicname]::$info[tid]",ACCESS_READ)) {
            $links = genArticleLinks($info); // E_ALL fix (landseer, 22-01-05) ($articles[0]);
            $preformat = genArticlePreformat($info, $links);
            $content = _BIGSTORY.'<br /><br />';
            $content .= $preformat['title'];
        } else {
            return;
        }
    }

    if (empty($row['title'])) {
        $row['title'] = _TODAYBIG;
    }

    if (empty($content)) {
        return;
    }

    $row['content'] = $content;
    return themesideblock($row);
}

?>