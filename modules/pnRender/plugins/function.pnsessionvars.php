<?php
// $Id: function.pnsessionvars.php 15542 2005-01-30 11:50:55Z markwest $
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
 * @version      $Id: function.pnsessionvars.php 15542 2005-01-30 11:50:55Z markwest $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 


/**
 * Smarty function to get all session variables
 * 
 * This function gets all session vars from the PostNuke system assigns the names and 
 * values to two array. This is being used in pndebug to show them.
 * 
 * Example
 *   <!--[pnsessionvars]-->
 * 
 * 
 * @author       Frank Schummertz
 * @since        23/08/2004
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       nothing
 */
function smarty_function_pnsessionvars($params, &$smarty)
{
    global $HTTP_SESSION_VARS;

    $allvars = $HTTP_SESSION_VARS;
    $smarty->assign( '_pnsession_keys', array_keys($allvars) );
    $smarty->assign( '_pnsession_vals', array_values($allvars) );

}

?>