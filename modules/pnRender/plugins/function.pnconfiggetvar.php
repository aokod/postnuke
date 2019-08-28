<?php
// $Id: function.pnconfiggetvar.php 16611 2005-08-08 14:42:32Z markwest $
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
 * @version      $Id: function.pnconfiggetvar.php 16611 2005-08-08 14:42:32Z markwest $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to get configuration variable
 * 
 * This function obtains a configuration variable from the PostNuke system.
 * 
 * Note that the results should be handled by the pnvarprepfordisplay or the 
 * pnvarprephtmldisplay modifier before being displayed.
 * 
 * 
 * Available parameters:
 *   - name:     The name of the config variable to obtain 
 *   - assign:   If set, the results are assigned to the corresponding variable instead of printed out
 * 
 * Example
 *   Welcome to <!--[pnconfiggetvar name="sitename"]-->!
 * 
 * 
 * @author       Andreas Stratmann
 * @since        03/05/19
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @param        bool        $html        (optional) If true then result will be treated as html content
 * @param        string      $assign      (optional) If set then result will be assigned to this template variable
 * @return       string      the value of the last status message posted, or void if no status message exists
 */
function smarty_function_pnconfiggetvar($params, &$smarty) 
{
    extract($params); 
	unset($params);

    if (!isset($name)) {
        $smarty->trigger_error("pnconfiggetvar: attribute name required");
        return false;
    }    

    $result = pnConfigGetVar($name);

    if (isset($assign)) {
        $smarty->assign($assign, $result);
    } else {
		if (isset($html) && is_bool($html) && $html) {
			return pnVarPrepHTMLDisplay($result);
		} else {
	        return pnVarPrepForDisplay($result);
		}
    }
}

?>