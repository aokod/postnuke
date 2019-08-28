<?php
// $Id: function.userlogin.php 17000 2005-11-11 08:06:27Z landseer $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2002 by the PostNuke Development Team.
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
/**
 * Xanthia plugin
 *
 * This file is a plugin for pnRender, the PostNuke implementation of Smarty
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   Xanthia
 * @version      $Id: function.userlogin.php 17000 2005-11-11 08:06:27Z landseer $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */


/**
 * Smarty function to display the login box
 *
 * Example
 * <!--[userlogin size=14 maxlength=25 maxlengthpass=20]-->
 *
 * @author       Mark West
 * @since        23/10/03
 * @see          function.userlogin.php::smarty_function_userlogin()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @param        integer     $size        Size of text boxes (default=14)
 * @param        integer     $maxlength   Maximum length of text box for unamees (default=25)
 * @param        integer     $maxlengthpass   Maximum length of text box for password (default=20)
 * @param        string      $class       Name of class (default value is "pn-normal")
 * @return       string      the welcome message
 */
function smarty_function_userlogin($params, &$smarty)
{
    extract($params);
	unset($params);

	// set some defaults
	if (!isset($size)) {
		$size = 14;
	}
	if (!isset($maxlength)) {
		$maxlength = 25;
	}
	if (!isset($maxlengthpass)) {
		$maxlengthpass = 20;
	}
	if (isset($class)) {
		$class = ' class="'.$class.'"';
	} else {
		$class = '';
	}

    // determine the current url so we can return the user to the correct place after login
    // Start of with REQUEST_URI
    if (isset($_SERVER['REQUEST_URI'])) {
        $path = $_SERVER['REQUEST_URI'];
    } else {
        $path = pnServerGetVar('REQUEST_URI');
    }
    if ((empty($path)) ||
        (substr($path, -1, 1) == '/')) {
        // REQUEST_URI was empty or pointed to a path
        // Try looking at PATH_INFO
        $path = pnServerGetVar('PATH_INFO');
        if (empty($path)) {
            // No luck there either
            // Try SCRIPT_NAME
            if (isset($_SERVER['SCRIPT_NAME'])) {
                $path = $_SERVER['SCRIPT_NAME'];
            } else {
                $path = pnServerGetVar('SCRIPT_NAME');
            }
        }
    }

	if (!pnUserLoggedIn()) {
		$loginbox =  '<form style="display:inline" action="user.php" method="post"><div>'
  				.'<span'.$class.'>&nbsp;<label for="uname_xte_plugin">' . pnVarPrepForDisplay(_NICKNAME) . '</label></span>'
				.'<input type="text" name="uname" id="uname_xte_plugin" size="'.$size.'" maxlength="'.$maxlength.'" />'
  				.'<span'.$class.'>&nbsp;<label for="pass_xte_plugin">' . pnVarPrepForDisplay(_PASSWORD) . '</label></span>'
				.'<input type="password" name="pass" id="pass_xte_plugin" size="'.$size.'" maxlength="'.$maxlengthpass.'" />';

        if (pnConfigGetVar('seclevel') <> 'high') {
			$loginbox .= '<input type="checkbox" value="1" name="rememberme" id="rememberme_xte_plugin" />'
					.'  <span'.$class.'>&nbsp;<label for="rememberme_xte_plugin">' . pnVarPrepForDisplay(_REMEMBERME) . '</label></span>';
		}

		$loginbox .= '<input type="hidden" name="module" value="User" />'
				.'<input type="hidden" name="op" value="login" />'
				.'<input type="hidden" name="url" value="' . pnVarPrepForDisplay($path) .'" />'
				.'<input class="pn-button" type="submit" value="' . pnVarPrepForDisplay(_LOGIN) . '" />'
				.'</div></form>';
 	} else {
		$loginbox = '&nbsp;';
	}

    return $loginbox;
}
?>