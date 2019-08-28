<?php 
// $Id: changetheme.php 15627 2005-02-04 06:00:05Z jorg $
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

if (eregi('changetheme.php', $_SERVER['PHP_SELF'])) {
	die ('You can\'t access this file directly...');
}

modules_get_language();

function chgtheme()
{
	if (!pnUserLoggedIn()) {
		return;
	}

    if (pnConfigGetVar('theme_change') == 1){
        return;
    }
    
    include ('header.php');
    OpenTable();
    echo '<h1>'._THEMESELECTION.'</h1>';
    CloseTable();

    OpenTable();

    $themelist = pnThemeGetAllThemes();
    $usertheme = pnUserGetTheme();
    echo '<div style="text-align:center">'
        .'<form action="user.php" method="post"><div>'
        .'<h2>'._SELECTTHEME.'</h2>'
        .'<select name="newtheme">';
    foreach($themelist as $theme) {
// all values should be set and not empty -- the API takes care of that [jn]
//        if(isset($theme) && $theme != '' && $theme != 'Printer') {
            echo '<option value="' . pnVarPrepForDisplay($theme) . '"';
            if ($theme == $usertheme) {
                echo ' selected="selected"';
            }
            echo '>' . pnVarPrepForDisplay($theme) . '</option>'."\n";
//        }
    }
    echo '</select>'
        .'<ul style="text-align:left"><li>'._THEMETEXT1.'</li>'
        .'<li>'._THEMETEXT2.'</li>'
        .'<li>'._THEMETEXT3.'</li></ul>'
//        .'<input type="hidden" name="op" value="savetheme" />'
//        .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
        .'<input type="submit" value="'._SAVECHANGES.'" />'
        .'</div></form></div>';
    CloseTable();
    include ('footer.php');
}

/*
function savetheme()
{
    if (!pnSecConfirmAuthKey()) {
		pnSessionSetVar('errormsg', 'Not allowed to directly update theme');
		pnRedirect('user.php');
		return;
    }

    $newtheme = pnVarCleanFromInput('newtheme');

    if (pnUserLoggedIn()) {
        $uid = pnUserGetVar('uid');
        $dbconn =& pnDBGetConn(true);
        $pntable =& pnDBGetTables();
        $column = &$pntable['users_column'];
        $dbconn->Execute("UPDATE $pntable[users]
                          SET $column[theme]='" . pnVarPrepForStore($newtheme) . "'
                          WHERE $column[uid]='" . pnVarPrepForStore($uid)."'");
        pnRedirect('user.php');
    }
}

*/

switch ($op) {
	case 'chgtheme':
		chgtheme();
		break;
//	case 'savetheme':
//		savetheme();
//		break;
}

?>