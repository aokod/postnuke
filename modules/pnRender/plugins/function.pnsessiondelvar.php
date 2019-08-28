<?php
// $Id: function.pnsessiondelvar.php 15291 2005-01-07 15:55:38Z markwest $
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
 * @version      $Id: function.pnsessiondelvar.php 15291 2005-01-07 15:55:38Z markwest $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to delete a session variable
 * 
 * This function deletes a session-specific variable from the PostNuke system.
 * 
 * 
 * Available parameters:
 *   - name:    The name of the session variable to delete 
 *   - assign:  If set, the result is assigned to the corresponding variable instead of printed out
 * 
 * Example
 *   <!--[pnsessiondelvar name="foobar"]-->
 * 
 * 
 * @author       Michael Nagy
 * @since        23/12/2004
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @param        string      $name        The name of the session variable to delete
 * @return       string      The session variable
 */
function smarty_function_pnsessiondelvar ($params, &$smarty) 
{
    extract($params); 
	unset($params);

    if (!isset($name)) {
        $smarty->trigger_error("pnsessiondelvar: attribute name required");
        return false;
    }    

    $result = pnSessionDelVar($name); 

    if (isset($assign)) {
        $smarty->assign($assign, $result);
    } else {
        return $result;        
    }        
}

?>