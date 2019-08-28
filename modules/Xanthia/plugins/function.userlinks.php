<?php
// $Id: function.userlinks.php 16722 2005-08-27 12:35:10Z  $
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
 * @version      $Id: function.userlinks.php 16722 2005-08-27 12:35:10Z  $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to display some user links
 * 
 * Example
 * <!--[userlinks start="[" end="]" seperator="|"]-->
 * 
 * Two additional defines need adding to a xanthia theme for this plugin
 * _CREATEACCOUNT and _YOURACCOUNT
 *
 * @author       Mark West
 * @since        21/10/03
 * @see          function.userlinks.php::smarty_function_userlinks()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @param        string      $start       start delimiter
 * @param        string      $end         end delimiter
 * @param        string      $seperator   seperator
 * @return       string      user links
 */
function smarty_function_userlinks($params, &$smarty) 
{
    extract($params); 
	unset($params);

    if (!isset($start)) {
	    $start = '[';
	}
    if (!isset($end)) {
	    $end = ']';
	}
    if (!isset($seperator)) {
	    $seperator = '|';
	}

	if (pnUserLoggedIn()) {
		if (ereg('0.7', _PN_VERSION_NUM)) {
			$links = "$start <a href=\"user.php\">" . _YOURACCOUNT . "</a> $seperator "
					."<a href=\"user.php?module=User&amp;op=logout\">". _LOGOUT . "</a> $end";
		} else {
			$links = "$start <a href=\"user.php\">" . _YOURACCOUNT . "</a> $seperator "
					."<a href=\"" . pnVarPrepHTMLDisplay(pnModURL('Users', 'user', 'logout')) . "\">"  . _LOGOUT . "</a> $end";
		}
	} else {
		if (ereg('0.7', _PN_VERSION_NUM)) {
			$links = "$start <a href=\"user.php\">" . _CREATEACCOUNT . "</a> $seperator "
					."<a href=\"user.php?op=loginscreen&amp;module=User\">". _LOGIN . "</a> $end";
		} else {
			$links = "$start <a href=\"user.php\">" . _CREATEACCOUNT . "</a> $seperator "
					."<a href=\"" . pnVarPrepHTMLDisplay(pnModURL('Users', 'user', 'view')) . "\">" . _LOGIN . "</a> $end";
		}
	}

    return pnVarPrepHTMLDisplay($links);
}

?>