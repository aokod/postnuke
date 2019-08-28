<?php
// File: $Id: login.php 19381 2006-07-07 11:14:05Z markwest $
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

if (strpos($_SERVER['PHP_SELF'], 'login.php')) {
  die ("You can't access this file directly...");
}

$blocks_modules['login'] = array(
    'func_display' => 'blocks_login_block',
    'text_type' => 'Login',
    'text_type_long' => "User's Login",
    'allow_multiple' => false,
    'form_content' => false,
    'form_refresh' => false,
//  'support_xhtml' => true,
    'show_preview' => false
);

// Security
pnSecAddSchema('Loginblock::', 'Block title::');

function blocks_login_block($row)
{
    if (empty($row['title'])) {
        $row['title'] = 'Login';
    }
    if (!pnSecAuthAction(0, 'Loginblock::', "$row[title]::", ACCESS_READ)) {
        return;
    }

	// get the current uri so we can return the user to the correct place
	$path = pnGetCurrentURL();

    if (!pnUserLoggedIn()) {
    // prettified a little with a table for inputs and button to avoid bugs like #493456 (Andy Varganov)
        $boxstuff  = '<form action="user.php" method="post"><div>' . "\n";
        $boxstuff .= '<label for="uname">'._BLOCKNICKNAME.'</label><br />' . "\n";
        $boxstuff .= '<input type="text" name="uname" id="uname" size="14" maxlength="25" tabindex="0"
                    value="'._BLOCKNICKNAME.'"
                    onblur="if(this.value==\'\')this.value=\''._BLOCKNICKNAME.'\';"
                    onfocus="if(this.value==\''._BLOCKNICKNAME.'\')this.value=\'\';" /><br />';
	    $boxstuff .= '<label for="pass">'._BLOCKPASSWORD.'</label><br />' . "\n";
        $boxstuff .= '<input type="password" name="pass" id="pass" size="14" maxlength="20" tabindex="0" /><br />' . "\n";
        if (pnConfigGetVar('seclevel') != 'High') {
			$boxstuff .= '<input type="checkbox" value="1" name="rememberme" id="rememberme" tabindex="0" />' . "\n";
			$boxstuff .= '&nbsp;<label for="rememberme">'._REMEMBERME.'</label>' . "\n";
			$boxstuff .= '<br />' . "\n";
        }
        $boxstuff .= '<input type="hidden" name="module" value="User" />' . "\n";
        $boxstuff .= '<input type="hidden" name="op" value="login" />' . "\n";
        $boxstuff .= '<input type="hidden" name="url" value="' .pnVarPrepForDisplay($path) .'" />' . "\n";
        $boxstuff .= '<input title="' . _COOKIEHINTFORLOGIN . '" type="submit" value="'._LOGIN.'" /><br />' . "\n";
        $boxstuff .= '<br />'._ASREGISTERED.'</div></form>' . "\n";
        if (empty($row['title'])) {
            $row['title'] = _LOGIN;
        }
        $row['content'] = $boxstuff;
        return themesideblock($row);
    }
}

?>