<?php
// $Id: function.groupsadminlinks.php 14022 2004-07-16 15:10:15Z markwest $
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
 * Groups Module
 * 
 * @package      PostNuke_System_Modules
 * @subpackage   Groups
 * @version      $Id: function.groupsadminlinks.php 14022 2004-07-16 15:10:15Z markwest $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to display admin links for the groups module
 * based on the user's permissions
 * 
 * Groups
 * <!--[groupsadminlinks start="[" end="]" seperator="|" class="pn-menuitem-title"]-->
 * 
 * @author       Mark West
 * @since        24/01/04
 * @see          function.groupsadminlinks.php::smarty_function_groupsadminlinks()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @param        string      $start       start string
 * @param        string      $end         end string
 * @param        string      $seperator   link seperator
 * @param        string      $class       CSS class
 * @return       string      the results of the module function
 */
function smarty_function_groupsadminlinks($params, &$smarty) 
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
	
    if (pnSecAuthAction(0, 'Groups::', "::", ACCESS_READ)) {
		$adminlinks .= "<a href=\"" . pnVarPrepHTMLDisplay(pnModURL('Groups', 'admin', 'view')) . "\">" . _GROUPSADMINVIEW . "</a> ";
    }
    if (pnSecAuthAction(0, 'Groups::', "::", ACCESS_ADD)) {
		$adminlinks .= "$seperator <a href=\"" . pnVarPrepHTMLDisplay(pnModURL('Groups', 'admin', 'new')) . "\">" . _GROUPSNEW . "</a> ";
    }
    if (pnSecAuthAction(0, 'Groups::', "::", ACCESS_ADMIN)) {
		$adminlinks .= "$seperator <a href=\"" . pnVarPrepHTMLDisplay(pnModURL('Groups', 'admin', 'modifyconfig')) . "\">" . _GROUPSMODIFYCONFIG . "</a> ";
    }

	$adminlinks .= "$end</span>\n";

    return $adminlinks;
}

?>