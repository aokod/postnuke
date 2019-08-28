<?php
// $Id: function.pnmodgetname.php 16722 2005-08-27 12:35:10Z  $
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
 * @version      $Id: function.pnmodgetname.php 16722 2005-08-27 12:35:10Z  $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to the topmost module name
 * 
 * This function currently returns the name of the current top-level 
 * module, false if not in a module.
 * 
 * 
 * Available parameters:
 *   - assign:   If set, the results are assigned to the corresponding 
 *               variable instead of printed out
 * 
 * Example
 *   <!--[pnmodgetname|pnvarprepfordisplay]-->
 * 
 * 
 * @author       Jörg Napp
 * @since        18. Jan. 2004
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      The module variable
 */
function smarty_function_pnmodgetname ($params, &$smarty) 
{
    extract($params); 
	unset($params);

    $result = pnModGetName();

    if (isset($assign)) {
        $smarty->assign($assign, $result);
    } else {
        return $result;        
    }      
}

?>