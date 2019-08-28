<?php
// $Id: modifier.pnmodcallhooks.php 16722 2005-08-27 12:35:10Z  $
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
 * @version      $Id: modifier.pnmodcallhooks.php 16722 2005-08-27 12:35:10Z  $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty modifier to apply transform hooks
 * 
 * This modifier will run the transform hooks that are enabled for the
 * corresponding module (like Autolinks, bbclick and others).
 * 
 * Available parameters:
 *   - modname:  The well-known name of the calling module; passed to the hook function
 *               in the extrainfo array
 * Example
 * 
 *   <!--[$MyVar|pnmodcallhooks]-->
 * 
 * 
 * @author       Joerg Napp
 * @author       The pnCommerce team
 * @since        16. Sept. 2003
 * @param        array    $string     the contents to transform
 * @return       string   the modified output
 */
function smarty_modifier_pnmodcallhooks($string, $modname = '')
{
	$extrainfo = array($string);
	if (!empty($modname)) {
		$extrainfo['module'] = $modname;
	}

	list($string) = pnModCallHooks('item', 'transform', '', $extrainfo);
    return $string;
}

?>