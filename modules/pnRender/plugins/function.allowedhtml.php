<?php
// $Id: function.allowedhtml.php 16465 2005-07-23 15:02:33Z markwest $
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
 * pnRender plugin
 * 
 * This file is a plugin for pnRender, the PostNuke implementation of Smarty
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   pnRender
 * @version      $Id: function.allowedhtml.php 16465 2005-07-23 15:02:33Z markwest $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to display the list of allowed html tags
 * 
 * Available parameters:
 *   - assign:   If set, the results are assigned to the corresponding variable instead of printed out
 * 
 * Example
 *   <!--[allowedhtml]-->
 * 
 * @author       Mark West
 * @since        25 April 2004
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the value of the last status message posted, or void if no status message exists
 */
function smarty_function_allowedhtml($params, &$smarty) 
{
	extract($params);

	$AllowableHTML = pnConfigGetVar('AllowableHTML');
	$allowedhtml = '<strong>'._ALLOWEDHTML."</strong><br />\n";
	foreach($AllowableHTML as $key=>$access) {
		if ($access > 0) {
		   $allowedhtml .= '&lt;' . htmlspecialchars($key) . '&gt; ';
		}
	}

    if (isset($assign)) {
        $smarty->assign($assign, $allowedhtml);
    } else {
        return $allowedhtml;
    }      
}
?>