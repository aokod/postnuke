<?php
// $Id: function.pnmodishooked.php 15484 2005-01-25 16:14:01Z markwest $
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
 * @version      $Id: function.pnmodishooked.php 15484 2005-01-25 16:14:01Z markwest $
 * @author       Mark West
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to check for the availability of a module
 * 
 * This function calls pnModIsHooked to determine if two PostNuke modules are
 * hooked together. True is returned if the modules are hooked, false otherwise.
 * The result can also be assigned to a template variable.
 * 
 * Available parameters:
 *   - tmodname:  The well-known name of the hook module
 *   - smodname:  The well-known name of the calling module
 *   - assign:    The name of a variable to which the results are assigned
 * 
 * Examples
 *   <!--[pnmodishooked tmodname="Ratings" smodname="News"]-->
 * 
 *   <!--[pnmodishooked tmodname="bar" smodname="foo" assign=barishookedtofoo]-->
 *   <!--[if $barishookedtofoo]-->.....<!--[/if]-->
 * 
 * 
 * @author       Mark West
 * @since        25/1/2005
 * @see          function.pnmodishooked.php::smarty_function_pnmodishooked()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       bool        true if the module is available; false otherwise
 */
function smarty_function_pnmodishooked ($params, &$smarty) 
{
    extract($params); 
	unset($params);

	$result = pnModIsHooked($tmodname, $smodname);

    if(isset($assign)) {
		$smarty->assign($assign, $result);
	} else {
		return $result;
	}
}

?>