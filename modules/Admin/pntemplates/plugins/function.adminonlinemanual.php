<?php
// $Id: function.adminonlinemanual.php 15397 2005-01-17 16:31:02Z landseer $
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
 * Admin Module
 * 
 * @package      PostNuke_System_Modules
 * @subpackage   Admin
 * @version      $Id: function.adminonlinemanual.php 15397 2005-01-17 16:31:02Z landseer $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to display the category menu for admin links. This also adds a link to
 * navtabs.css to the global additional_headers array. 
 * 
 * Admin
 * <!--[adminonlinemanual]-->
 * 
 * @author       Frank Schummertz
 * @since        16.01.2005
 * @see          function.admincategorymenu.php::smarty_function_admincategoreymenu()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @param        int         xhtml        if set, the link to the navtabs.css will be xhtml compliant
 * @return       string      the results of the module function
 */
function smarty_function_adminonlinemanual($params, &$smarty) 
{
    extract($params); 
	unset($params);

	$lang = pnVarPrepForOS(pnUserGetLang());
	$modinfo = pnModGetInfo(pnModGetIDFromName(pnModGetName()));
    $modpath = ($modinfo['type'] == 3) ? 'system' : 'modules';
	$file = "$modpath/$modinfo[directory]/lang/$lang/manual.html";
	$man_link = "";
	if(file_exists($file) && is_readable($file)) {
		$man_link = '<div style="margin-top: 20px; text-align:center">[ <a href="javascript:openwindow(\'' . $file . '\')">'._ONLINEMANUAL.'</a> ]</div>'."\n";
	}
    return $man_link;    
}

?>