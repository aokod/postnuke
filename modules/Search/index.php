<?php
// $Id: index.php 16754 2005-09-13 08:32:10Z jorg $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// Copyright (c) 2001 by Patrick Kellum (webmaster@ctarl-ctarl.com)
// http://www.ctarl-ctarl.com
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
// Filename: modules/Search/index.php
// Original Author of file: Patrick Kellum
// Purpose of file: Search reviews/users/stories/topics/faqs
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_MODULE')) {
	die ("You can't access this file directly...");
}

/*
Credits to Edgar Miller -- http://www.bosna.de/ from his post on PHP-Nuke 
( http://phpnuke.org/article.php?sid=2010&mode=nested&order=0&thold=0 )
Further Credits go to Djordjevic Nebojsa (nesh) for the fix for the fix */

$ModName = basename(dirname(__FILE__));

modules_get_language();

/*
 * load all available search modules
 */
$search_modules = array();
$d = opendir('includes/search/');
while($f = readdir($d)) {
    if(substr($f, -3, 3) == 'php') {
        include 'includes/search/' . $f;
    }
}
closedir($d);
/*
 * splits the query string into words suitable for a mysql query
 */
function search_split_query($q) {
    if (!isset($q)) {
        return;
    }
    $w = array();
    $stripped = pnVarPrepForStore($q);
    $qwords = preg_split('/ /', $stripped, -1, PREG_SPLIT_NO_EMPTY);
    foreach($qwords as $word) {
        $w[] = '%' . $word . '%';
    }
    return $w;
}
function search_form($vars) {

	$search_modules = &$GLOBALS['search_modules'];
	$bgcolor1 = &$GLOBALS['bgcolor1'];
	$bgcolor2 = &$GLOBALS['bgcolor2'];
	$bgcolor3 = &$GLOBALS['bgcolor3'];
	$textcolor1 = &$GLOBALS['textcolor1'];
	$textcolor2 = &$GLOBALS['textcolor2'];

    if(!isset($vars['bool']) || $vars['bool'] == '') {
        $vars['bool'] = 'AND';
    }

    $bool_select = array('AND' => '', 'OR' => '');
    $bool_select[$vars['bool']] = ' selected="selected"';
    echo '<form method="post" action="index.php"><div>'
        .'<input type="hidden" name="name" value="Search" />'
        .'<input type="hidden" name="action" value="search" />'
        .'<input type="hidden" name="overview" value="1" />';
    echo '<table border="0" cellpadding="2" cellspacing="0">'
        .'<tr>'
        .'<td><label for="search1">'._SEARCH.'</label>&nbsp;'._FOR.':</td>'
        .'<td colspan="2">'
        .'<input type="text" name="q" id="search1" size="20" maxlength="255" value="' . (isset($vars['q']) ? htmlspecialchars($vars['q']) : '') . '" tabindex="0" /> '
        .'<input type="submit" value="'._SEARCH.'" />'
        .'</td>'
        .'</tr>'
        .'<tr>'
        .'<td><label for="search2">'._SEARCH_BOOL.'</label>&nbsp;:</td>'
        .'<td colspan="2">'
        .'<select name="bool" size="1" id="search2">'
        .'<option value="AND"'.$bool_select['AND'].'>'._ALLWORDS.'</option>'
        .'<option value="OR"'.$bool_select['OR'].'>'._ANYWORDS.'</option>'
        .'</select>'
        .'</td>'
        .'</tr>'
        .'</table>';
    foreach($search_modules as $mods) {
        echo $mods['func_opt']($vars);
    }
    echo '</div></form>';
}

$vars = array_merge($_POST,$_GET);
if(!isset($vars['action'])) {
	$vars['action'] = 'form';
}

include 'header.php';
OpenTable();
echo '<h1>' . _SEARCH . '</h1>';
CloseTable();
switch($vars['action']) {
    default:
    case 'form':
        OpenTable();
        search_form($vars);
        CloseTable();
        break;
    case 'search':
        OpenTable();
        foreach($search_modules as $mods) {
            echo '<div>';
            echo $mods['func_search']($vars);
			echo '</div>';
        }
        CloseTable();
        break;
}
include 'footer.php';

?>