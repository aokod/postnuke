<?php
// $Id: function.pngetbaseurl.php 16483 2005-07-23 17:44:59Z markwest $
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
 * @version      $Id: function.pngetbaseurl.php 16483 2005-07-23 17:44:59Z markwest $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to obtain base URL for this site
 * 
 * This function obtains the base URL for the site. The base url is defined as the 
 * full URL for the site minus any file information  i.e. everything before the 
 * 'index.php' from your start page.
 * Unlike the API function pnGetBaseURL, the results of this function are already 
 * sanitized to display, so it should not be passed to the pnvarprepfordisplay modifier.
 * 
 * Available parameters:
 *   - assign:   If set, the results are assigned to the corresponding variable instead of printed out
 * 
 * Example
 *   <!--[pngetbaseurl]-->
 * 
 * 
 * @author       Mark West
 * @since        08/08/2003
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the base URL of the site
 */
function smarty_function_pngetbaseurl ($params, &$smarty) 
{
	extract($params);
	unset($params);

    $result = htmlspecialchars(pnGetBaseURL());

    if (isset($assign)) {
        $smarty->assign($assign, $result);
    } else {
        return $result;        
    }
}
?>