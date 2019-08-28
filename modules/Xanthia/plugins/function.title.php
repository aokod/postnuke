<?php
// $Id: function.title.php 16722 2005-08-27 12:35:10Z  $
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
 * This file is a plugin for Xanthia, the PostNuke implementation of Smarty
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   Xanthia
 * @version      $Id: function.title.php 16722 2005-08-27 12:35:10Z  $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to generate the title for the page
 * 
 * Example
 * <!--[title]-->
 * 
 * @author       Mark West
 * @since        29/03/04
 * @see          function.title.php::smarty_function_title()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the title
 */
function smarty_function_title($params, &$smarty) 
{
    extract($params); 
	unset($params);

	if (isset($GLOBALS['info']) && is_array($GLOBALS['info'])) {
		// article page output
		$title = strip_tags($GLOBALS['info']['title']).' :: '.pnConfigGetVar('sitename').' :: '.pnConfigGetVar('slogan');
	} else {
		// all other page output
		$title =  pnConfigGetVar('sitename').' :: '.pnConfigGetVar('slogan');
	}

    if (isset($params['assign'])) {
        $smarty->assign($params['assign'], $title);
    } else {
        return $title;
    }

}

?>