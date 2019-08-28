<?php
// $Id: function.avantgoadminlinks.php 14025 2004-07-16 19:07:04Z markwest $
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
 * Avantgo Module
 * 
 * The Avantgo module shows how to make a PostNuke module. 
 * It can be copied over to get a basic file structure.
 *
 * @package      PostNuke_Content_Modules
 * @subpackage   AvantGo
 * @version      $Id: function.avantgoadminlinks.php 14025 2004-07-16 19:07:04Z markwest $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to display admin links for the example module
 * based on the user's permissions
 * 
 * Avantgo
 * <!--[avantgoadminlinks start="[" end="]" seperator="|" class="pn-menuitem-title"]-->
 * 
 * @author       Mark West
 * @since        29/02/04
 * @see          function.avantgoadminlinks.php::smarty_function_avantgoadminlinks()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @param        string      $start       start string
 * @param        string      $end         end string
 * @param        string      $seperator   link seperator
 * @param        string      $class       CSS class
 * @return       string      the results of the module function
 */
function smarty_function_avantgoadminlinks($params, &$smarty) 
{
    extract($params); 
	unset($params);
    
	// set some defaults
	if (!isset($start)) {
		$start = '[';
	}
	if (!isset($end)) {
		$end = ']';
	}
	if (!isset($seperator)) {
		$seperator = '|';
	}
    if (!isset($class)) {
	    $class = 'pn-menuitem-title';
	}

    $adminlinks = "<span class=\"$class\">$start ";
	
    if (pnSecAuthAction(0, 'AvantGo::', "::", ACCESS_READ)) {
		$adminlinks .= "<a href=\"" . pnVarPrepHTMLDisplay(pnModURL('Avantgo', 'admin', 'view')) . "\">" . _AVANTGOVIEW . "</a> ";
    }
    if (pnSecAuthAction(0, 'AvantGo::', "::", ACCESS_ADMIN)) {
		$adminlinks .= "$seperator <a href=\"" . pnVarPrepHTMLDisplay(pnModURL('Avantgo', 'admin', 'modifyconfig')) . "\">" . _MODIFYCONFIG . "</a> ";
    }

	$adminlinks .= "$end</span>\n";

    return $adminlinks;
}

?>