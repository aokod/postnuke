<?php
// File: $Id: past.php 15768 2005-02-20 14:37:56Z larsneo $
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

if (strpos($_SERVER['PHP_SELF'], 'past.php')) {
    die ("You can't access this file directly...");
}

$blocks_modules['past'] = array(
    'func_display' => 'blocks_past_block',
    'func_edit' => 'blocks_past_select',
    'func_update' => 'blocks_past_update',
    'text_type' => 'Past',
    'text_type_long' => 'Past Articles',
    'allow_multiple' => false,
    'form_content' => false,
    'form_refresh' => false,
//  'support_xhtml' => true,
    'show_preview' => true
    );

// Security
pnSecAddSchema('Pastblock::', 'Block title::');

function blocks_past_block($row)
{
    if(!pnModAvailable('News')) {
        return;
    }

    // Story functions
    include_once('modules/News/funcs.php');

    $catid = pnVarCleanFromInput('catid');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $oldnum = pnConfigGetVar('perpage');

    if (!pnSecAuthAction(0, 'Pastblock::', "$row[title]::", ACCESS_READ)) {
        return;
    }
    if (pnUserLoggedIn()) {
        $storyhome = pnUserGetVar('storynum');
    } else {
        $storyhome = pnConfigGetVar('storyhome');
    }

    // Break out options from our content field
    $vars = pnBlockVarsFromContent($row['content']);

    // Defaults
    if (empty($storynum)) $storynum = 10;
    if (empty($vars['limit'])) {
        $vars['limit'] = 10;
    }
    $storynum = $vars['limit'];

    $column = &$pntable['stories_column'];
    
    if (pnModGetName() == 'News' && is_numeric($catid)) {
        $articles = getArticles("$column[catid]=" . (int)pnVarPrepForStore($catid), "$column[time] DESC", $storynum, $storyhome);
    } else {
        $articles = getArticles("$column[ihome]=0", "$column[time] DESC", $storynum,$storyhome);
    }

    $time2 = '';

//    $boxstuff = "<table width=\"100%\" cellpadding=\"1\" cellspacing=\"0\" border=\"0\">\n";
    $boxstuff = '<ul>';
    $vari = 0;
    $see = 0;
    $morelink = '';
    foreach ($articles as $article) {

        $info = genArticleInfo($article);
        $links = genArticleLinks($info);
        $preformat = genArticlePreformat($info, $links);

        if (!pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_READ) ||
            !pnSecAuthAction(0, 'Topics::Topic',"$info[topicname]::$info[tid]",ACCESS_READ)) {
            continue;
        }
        $see = 1;
        ereg ('([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})', $info['time'], $datetime2);
        $datetime2 = ml_ftime(_DATESTRING2, mktime($datetime2[4],$datetime2[5],$datetime2[6],$datetime2[2],$datetime2[3],$datetime2[1]));
        $datetime2 = ucfirst($datetime2);
		$commentstring = '';
		if (pnModAvailable('Comments')) {
			$commentstring = "&nbsp;($info[comments])";
		}
        if($time2==$datetime2) {
            $boxstuff .= '<li>' . $preformat['title'] . "$commentstring</li>\n";
        } else {
            $boxstuff .= "<li style=\"list-style:none\">$datetime2</li>\n"
                        ."<li>$preformat[title]$commentstring</li>\n";
            $time2 = $datetime2;
        }
        $vari++;
        if ($vari == $vars['limit']) {
            $usernum = pnUserGetVar('storynum');
            if (!empty($usernum)) {
                $storynum = $usernum;
            } else {
                $storynum = pnConfigGetVar('storyhome');
            }
            $min = $oldnum + $storynum;
            if (!isset($catid)) {
                $morelink .= "<a href=\"index.php?name=Search&amp;action=search&amp;overview=1&amp;active_stories=1\"><strong>"._OLDERARTICLES."</strong></a>\n";
            } else {
                $morelink .= "<a href=\"index.php?name=Search&amp;action=search&amp;overview=1&amp;active_stories=1&amp;stories_cat[0]=$catid\"><strong>"._OLDERARTICLES."</strong></a>\n";;     
            }
        }
    }
    $boxstuff .= '</ul>';
    $boxstuff .= $morelink.'<br />';

    if ($see == 1) {
        if (empty($row['title'])) {
            $row['title'] = _PASTARTICLES;
        }
        $row['content'] = $boxstuff;
        return themesideblock($row);
    }
}

function blocks_past_select($row)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Break out options from our content field
    $vars = pnBlockVarsFromContent($row['content']);

    // Defaults
    if (empty($vars['limit'])) {
        $vars['limit'] = 10;
    }

    $row['content'] = '';

    // Number of stories
    $output = '<tr><td>Maximum number of stories to display:</td>
               <td><input type="text" name="limit" size="2" value="' . pnVarPrepForDisplay($vars['limit']) . 
               '"></td></tr>';

    return $output;
}

function blocks_past_update($row)
{
    $vars['limit'] = pnVarCleanFromInput('limit');

    $row['content'] = pnBlockVarsToContent($vars);

    return($row);
}

?>