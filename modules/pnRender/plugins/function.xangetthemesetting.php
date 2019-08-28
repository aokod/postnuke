<?php 
// $Id: function.xangetthemesetting.php 16722 2005-08-27 12:35:10Z  $
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
 * @version      $Id: function.xangetthemesetting.php 16722 2005-08-27 12:35:10Z  $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to get a setting from a Xanthia theme
 * 
 * This function will value of a setting in a Xanthia theme
 * 
 * available parameters:
 *  - assign      if set, the language will be assigned to this variable
 *  - name        the name of the theme setting to return
 * 
 * @author   Mark West
 * @since    18th Feb 2004
 * @param    array    $params     All attributes passed to this function from the template
 * @param    object   $smarty     Reference to the Smarty object
 * @param    string   $name       The name of the theme setting
 * @return   string   the version string
 */
function smarty_function_xangetthemesetting($params, &$smarty)
{
    // get the parameters
    extract($params); 
	unset($params['name']);

    global $engine;

	// check for a valid engine object
	// won't be set if we're not in a Xanthia theme
	if (!is_object($engine)) {
		return;
	}

	// check for the existance of the theme setting and 
	// return the value
	if (isset($engine->config[$name])) {
	    if (isset($params['assign'])) {
    	    $smarty->assign($params['assign'], $engine->config[$name]);
    	} else {
			return $engine->config[$name];
		}
	} else {
		return;
	}
} 

?>