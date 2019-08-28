<?php
// $Id: function.pnmodavailable.php 16842 2005-10-03 09:54:50Z markwest $
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
 * @version      $Id: function.pnmodavailable.php 16842 2005-10-03 09:54:50Z markwest $
 * @author       Michael Nagy
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to check for the availability of a module
 * 
 * This function calls pnModAvailable to determine if a PostNuke module is
 * is available. True is returned if the module is available, false otherwise.
 * The result can also be assigned to a template variable.
 * 
 * Available parameters:
 *   - modname:  The well-known name of a module to execute a function from (required)
 *   - assign:   The name of a variable to which the results are assigned
 * 
 * Examples
 *   <!--[pnmodavailable modname="News"]-->
 * 
 *   <!--[pnmodapifunc modname="foobar" assign="myfoo"]-->
 *   <!--[if $myfoo]-->.....<!--[/if]-->
 * 
 * 
 * @author       Michael Nagy
 * @since        20/1/2005
 * @see          function.pnmodavailable.php::smarty_function_pnmodavailable()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       bool        true if the module is available; false otherwise
 */
function smarty_function_pnmodavailable ($params, &$smarty) 
{
    extract($params); 
	unset($params);

	// minor backwards compatability fix
	if (isset($mod)) {
		$modname = $mod;
	}
	$result = pnModAvailable($modname);
    if(isset($assign))  {
		$smarty->assign($assign, $result);
	} else {
		return $result;
	}
}

?>