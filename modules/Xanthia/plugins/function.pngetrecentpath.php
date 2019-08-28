<?php
// $Id: function.pngetrecentpath.php 13806 2004-06-12 11:02:14Z landseer $
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
// but WIthOUT ANY WARRANTY; without even the implied warranty of
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
 * @version      $Id: function.pngetrecentpath.php 13806 2004-06-12 11:02:14Z landseer $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to get URI of the recent site
 * 
 * 
 * Available parameters:
 *   - assign:   If set, the results are assigned to the corresponding variable instead of printed out
 * 
 * Example
 *   <!--[pngetrecentpath assign=iamhere]-->!
 * 
 * 
 * @author       Frank Schummertz
 * @since        02/23/2004
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the value of the last status message posted, or void if no status message exists
 */
function smarty_function_pngetrecentpath ($params, &$smarty) 
{
    global $HTTP_SERVER_VARS;
    
    extract($params); 
	unset($params);

    // code taken pnGetBaseURI to fix issue with IIS not passing request_uri
    // markwest
    // Start of with REQUEST_URI
    if (isset($HTTP_SERVER_VARS['REQUEST_URI'])) {
        $path = $HTTP_SERVER_VARS['REQUEST_URI'];
    } else {
        $path = getenv('REQUEST_URI');
    }
    if ((empty($path)) ||
        (substr($path, -1, 1) == '/')) {
        // REQUEST_URI was empty or pointed to a path
        // Try looking at PATH_INFO
        $path = getenv('PATH_INFO');
        if (empty($path)) {
            // No luck there either
            // Try SCRIPT_NAME
            if (isset($HTTP_SERVER_VARS['SCRIPT_NAME'])) {
                $path = $HTTP_SERVER_VARS['SCRIPT_NAME'];
            } else {
                $path = getenv('SCRIPT_NAME');
            }
        }
    }

    if (isset($assign)) {
        $smarty->assign($assign, $path);
    } else {
        return $path;        
    }
}

?>