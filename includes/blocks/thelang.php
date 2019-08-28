<?php
// File: $Id: thelang.php 17660 2006-01-20 10:11:40Z larsneo $
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
// eugeniobaldi 2002-02-16 Fixed error in useflag and cleaned Source

if (strpos($_SERVER['PHP_SELF'], 'thelang.php')) {
	die ("You can't access this file directly...");
}

$blocks_modules['thelang'] = array(
    'func_display' => 'blocks_thelang_block',
    'text_type' => 'Language',
    'text_type_long' => 'Languages',
    'allow_multiple' => false,
    'form_content' => false,
    'form_refresh' => false,
    'show_preview' => true
    );

// Security
pnSecAddSchema('Languageblock::', 'Block title::');

function blocks_thelang_block($row)
{
    $currentlang = pnUserGetLang();

    if (!pnSecAuthAction(0, 'Languageblock::', "$row[title]::", ACCESS_OVERVIEW)) {
        return;
    }

    if (!pnConfigGetVar('multilingual')) {
        return;
    }

	$currentURL = 'index.php?' . pnServerGetVar('QUERY_STRING');
	if ($currentURL === '') { $currentURL = 'index.php'; }
	$pattern = '/\?newlang=\w+/';
	$currentURL = preg_replace ($pattern, '', $currentURL); 
	$pattern = '/\&newlang=\w+/';
	$currentURL = pnVarPrepForDisplay(preg_replace ($pattern, '', $currentURL)); 
	$append = "&amp;"; 

	if (strpos($currentURL, '?') === false) { $append = '?'; }

    $lang = languagelist();
    $handle = opendir('language');
    while ($f = readdir($handle)) {
       if (is_dir("language/$f") && (!empty($lang[$f])))
       {
           $langlist[$f] = $lang[$f];
           $sel_lang[$f] = '';
       }
    }
    asort($langlist);
    $content = '<div>'._SELECTGUILANG.'<br /><br />';
	if (pnConfigGetVar('useflags'))
	// eugeniobaldi 2002-02-16 his if give error in useflag
	//    if (pnConfigGetVar('useflags') || isset($row['url']))
    {
        $i = 1;
        foreach ($langlist as $k=>$v) {
            if ($i > 3) {
                $content.= "<br />\n";
                $i = 1;
            }
            $imgsize = @getimagesize("images/flags/flag-$k.png");
            $content .= "<a href=\"$currentURL".$append."newlang=$k\"><img src=\"images/flags/flag-$k.png\" alt=\"$lang[$k]\" $imgsize[3] /></a> ";
            $i++;
        }
        $content .= '</div>';
    } else {
        $content .= '<form method="post" action="index.php"><div>'
                 .  '<select name="newlanguage" onchange="top.location.href=this.options[this.selectedIndex].value">';
        $sel_lang[$currentlang] = ' selected="selected"';
        foreach ($langlist as $k=>$v) {
            $content .= "<option value=\"$currentURL".$append."newlang=$k\"$sel_lang[$k]>$v</option>\n";
        }
        $content .= '</select></div></form></div>';
    }
    if (empty($row['title'])) {
	    $row['title'] = _SELECTLANGUAGE;
    }
    $row['content'] = $content;
    return themesideblock($row);
}

?>