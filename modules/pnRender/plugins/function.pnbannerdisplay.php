<?php
// $Id: function.pnbannerdisplay.php 16722 2005-08-27 12:35:10Z  $
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
 * @version      $Id: function.pnbannerdisplay.php 16722 2005-08-27 12:35:10Z  $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to display . 
 * 
 * This function takes a identifier and returns a banner from the banners module
 * 
 * Available parameters:
 *   - id:       id of the banner group as defined in the banners module
 *   - assign:   If set, the results are assigned to the corresponding variable instead of printed out
 * 
 * Example
 * <!--[pnbannerdisplay id=0]-->
 * 
 * 
 * @author       Mark West
 * @since        20/10/2003
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @param        sting
 * @return       string      the banner
 */
function smarty_function_pnbannerdisplay ($params, &$smarty) 
{
    extract($params); 
	unset($params);

    // set some defaults
    if (!isset($id)) {
        $id = 0;
    }    

    if(pnModAvailable('Banners'))  {
        $result = pnBannerDisplay($id);
    
        if (isset($assign)) {
            $smarty->assign($assign, $result);
        } else {
            return $result;        
        }   
	} else {
        return '&nbsp;';
	}
}

?>