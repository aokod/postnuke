<?php
// $Id: function.add_additional_header.php 16722 2005-08-27 12:35:10Z  $
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
 * @version      $Id: function.add_additional_header.php 16722 2005-08-27 12:35:10Z  $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to add additional information to the <head> </head>
 * section of a PostNuke document
 * 
 * Available parameters:
 *   - header:   If set, the value is assigned to the global
 *               $additional_header array.  The value can be a single
 *               string or an array of strings.
 * 
 * Example
 *   <!--[add_additional_header header='<title>This is the title</title>']-->
 *	OR
 *   <!--[add_additional_header header=$title]-->
 * 
 * @author       Chris Miller
 * @since        14 August 2004
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the value of the last status message posted, or void if no status message exists
 */
function smarty_function_add_additional_header($args, &$smarty)
{
	if (!isset($args['header'])) {
		return;
	}
	
	global $additional_header;

	if (is_array($args['header'])) {
		foreach($args['header'] as $header) {
			$additional_header[] = $header;
		}
	} else {
		$additional_header[] = $args['header'];
	}
	return;
}

?>
