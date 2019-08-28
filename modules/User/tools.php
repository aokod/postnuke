<?php
// File: $Id: tools.php 20458 2006-11-09 08:19:16Z larsneo $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2002 by the PostNuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// LICENSE

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------

if (eregi('tools.php', $_SERVER['PHP_SELF'])) {
  die ("You can't access this file directly...");
}

include_once 'modules/User/user/menu.php';
include_once 'modules/User/user/access.php';

function redirect_index($message, $url = "index.php")
{
    if (pnConfigGetVar('login_redirect') == 1) {
        if (empty($_COOKIE)) {
            include('header.php');
            echo '<div style="text-align:center">';
            // no session cookie available
            echo '<h1>'._USERALLOWCOOKIES.'</h1>';
            echo '<p><a href="'.pnVarPrepForDisplay($url).'">'._USERREDIRECT.'</a></p>';
            echo '</div>';
            include('footer.php');
        } else {
            // session started, use a straight redirect
            pnRedirect($url);
        }
    } else {
        $ThemeSel = pnUserGetTheme();
        echo "<html><head><META HTTP-EQUIV=Refresh CONTENT=\"2; URL=$url\">\n";
        if (defined("_CHARSET") && _CHARSET != "") {
            echo "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=" . _CHARSET . "\">\n";
        }
        echo "<LINK REL=\"StyleSheet\" href=\"".WHERE_IS_PERSO."themes/$ThemeSel/style/styleNN.css\" type=\"text/css\">\n";
        echo "<style type=\"text/css\">";
        echo "@import url(\"".WHERE_IS_PERSO."themes/$ThemeSel/style/style.css\"); ";
        echo "</style>\n";
        echo "</head><body bgcolor=\"$GLOBALS[bgcolor1]\" text=\"$GLOBALS[textcolor1]\">\n";
        echo "<div style=\"text-align:center\">$message</div></body></html>";
    }
}

function redirect_user()
{
    if (pnConfigGetVar('login_redirect') == 1) {
        include('header.php');
        echo '<div style="text-align:center"><h1>' . _INFOCHANGED . '</h1>';
        echo '<p><a href="user.php">'._USERPROFILELINK.'</a></p></div>';
        include('footer.php');
    } else {
        $ThemeSel = pnUserGetTheme();
        echo "<html><head><META HTTP-EQUIV=Refresh CONTENT=\"2; URL=user.php\">\n";
        if (defined("_CHARSET") && _CHARSET != "") {
            echo "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=" . _CHARSET . "\">\n";
        }
        echo " <LINK REL=\"StyleSheet\" href=\"".WHERE_IS_PERSO."themes/$ThemeSel/style/style.css\" type=\"text/css\">\n";
        echo "<style type=\"text/css\">";
        echo "@import url(\"".WHERE_IS_PERSO."themes/$ThemeSel/style/style.css\"); ";
        echo "</style>\n";
        echo "</head><body bgcolor=\"$GLOBALS[bgcolor1]\" text=\"$GLOBALS[textcolor1]\">\n";
        echo "<div style=\"text-align:center\">" . _INFOCHANGED . "</div></body></html>";
    }
}

// for compatibility : use user_menu_add_option($url,$title,$image)
function usermenu($url, $title, $image)
{
    if (!ereg('/', $image)) $image = pnConfigGetVar('userimg') . "/" . $image;
    user_menu_add_option($url, $title, $image);
}

function user_menu($help_file = '')
{
    $pntable =& pnDBGetTables();

    user_menu_title('user.php', _THISISYOURPAGE);
    user_menu_graphic(pnConfigGetVar('usergraphic'));
    if ($help_file != '') user_menu_help($help_file, _ONLINEMANUAL);
    // include 'modules/Modules/data.php';
    // foreach ($module_item as $k=>$item)
    // user_menu_add_option('user.php?module='.$item['module'].'&op=main',$item['text'],$item['image']);
    // modules, old way
  $usermods = pnModGetAllMods();
  foreach ($usermods as $usermod) {
    $modinfo = pnModGetInfo(pnModGetIDFromName($usermod['name']));
        if (@is_dir($dir = 'modules/' . pnVarPrepForOS($modinfo['directory']) . '/user/links/')) {
            $linksdir = opendir("modules/" . pnVarPrepForOS($modinfo['directory']) . "/user/links/");
            while ($func = readdir($linksdir)) {
                if (eregi('^links.', $func)) {
                    // modified by Chris van de Steeg to have $ModName available in the links file
                    // $menulist[$func] = "modules/$modulename/user/links";
                    $menulist[$func]["dir"] = 'modules/' . pnVarPrepForOS($modinfo['directory']) . '/user/links';
          // this should really be $usermod['name'] but this leaves of the ns-prefix - markwest
                    $menulist[$func]["modname"] = $usermod['directory'];
                    // end mofication by Chris van de Steeg
                }
            }
            closedir($linksdir);
        }
    }

    // display
    ksort($menulist);
    foreach ($menulist as $k => $v) {
        // modified by Chris van de Steeg to have $ModName available in the links file
        // $currMod = $GLOBALS["ModName"]; //moved a bit down by Andy Varganov
        $GLOBALS["ModName"] = $v["modname"];
        $currMod = $GLOBALS["ModName"];
        include $v['dir'] . '/' . pnVarPrepForOS($k);
        $GLOBALS["ModName"] = $currMod;
        // end mofication by Chris van de Steeg
    }
  $youraccountmodinfo = pnModGetInfo(pnModGetIDFromName('Your_Account'));
    user_menu_add_option('user.php?module=User&amp;op=logout', _LOGOUTEXIT, 'modules/'.pnVarPrepForOS($youraccountmodinfo['directory']).'/images/exit.gif');
}


?>