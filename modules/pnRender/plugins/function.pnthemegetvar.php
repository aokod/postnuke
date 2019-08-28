<?php
// $Id: function.pnthemegetvar.php 16722 2005-08-27 12:35:10Z  $
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
 * @version      $Id: function.pnthemegetvar.php 16722 2005-08-27 12:35:10Z  $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

/**
 * Smarty function to get a colour definition from the theme
 * 
 * This function returns the corresponding color define from the theme
 * 
 * Available parameters:
 *   - name:    Name of the colour definition
 *   - assign:  If set, the results are assigned to the corresponding variable instead of printed out
 * 
 * Example
 * <!--[pnthemegetvar name="bgcolor2"]-->
 * 
 * 
 * @author       Jörg Napp
 * @since        16. Sept. 2003
 * @todo         check to work with Xanthia themes
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the colour definition
 */
function smarty_function_pnthemegetvar($params, &$smarty) 
{
    extract($params);      
    unset($params);
	
    if (!isset($name)) {
        $smarty->trigger_error("pnthemegetvar: variable name required");
        return false;
    }    

	pnThemeLoad(pnUserGetTheme());
    $result = pnThemeGetVar($name);

    if (isset($assign)) {
        $smarty->assign($assign, $result);
    } else {
        return $result;        
    }        
}

?>