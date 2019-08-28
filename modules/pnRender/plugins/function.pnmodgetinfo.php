<?php
// $Id: function.pnmodgetinfo.php 15479 2005-01-25 15:52:28Z markwest $
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
 * @version      $Id: function.pnmodgetinfo.php 15479 2005-01-25 15:52:28Z markwest $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to retrieve module information
 * 
 * This function retrieves module information from the database and returns them 
 * or assigns them to a variable for later use
 * 
 * 
 * Available parameters:
 *   - info        the information you want to retrieve from the modules info,
 *                 "all" results in assigning all information, see $assign 
 *   - assign      (optional or mandatory :-)) if set, assign the result instead of returning it
 *                 if $info is "all", a $assign is mandatory and the default is modinfo
 *   - modname     (optional) module name, if not set, the recent module is used
 * 
 * Example
 *   <!--[pnmodgetinfo info=displayname]-->
 *   <!--[pnmodgetinfo info=all assign=gimmeeverything]-->
 *   <!--[pnmodgetinfo modname=mymodname info=all assign=gimmeeverything]-->
 * 
 * 
 * @author       Frank Schummertz
 * @since        06. Sept. 2004
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      The module variable
 */
function smarty_function_pnmodgetinfo ($params, &$smarty) 
{
    extract($params); 
	unset($params);

    $modname = (!empty($modname)) ? $modname: pnModGetName();
    if( !pnModAvailable($modname)) {
        return false;
    }
    
	$modinfo = pnModGetInfo(pnModGetIDFromName($modname));

    $info = strtolower($info);
    if( ($info <> 'all') && !array_key_exists($info, $modinfo) ) {
        $smarty->trigger_error("pnmodgetinfo: invalid value '$info' for parameter info");
        return false;
    }

    if( $info == 'all' ) {
        $assign = (!empty($assign)) ? $assign: 'modinfo';
        $smarty->assign($assign, $modinfo);
    } else {
        if (isset($assign)) {
            $smarty->assign($assign, $modinfo[$info]);
        } else {
            return $modinfo[$info];        
        }      
    }
}

?>