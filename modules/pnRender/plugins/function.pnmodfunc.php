<?php
// $Id: function.pnmodfunc.php 16216 2005-05-09 18:10:00Z markwest $
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
 * @version      $Id: function.pnmodfunc.php 16216 2005-05-09 18:10:00Z markwest $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to to execute a module function
 * 
 * This function calls a specific module function.  It returns whatever the return 
 * value of the resultant function is if it succeeds. 
 * Note that in contrast to the API function pnModFunc you need not to load the
 * module with pnModLoad.
 * 
 * 
 * Available parameters:
 *   - modname:  The well-known name of a module to execute a function from (required)
 *   - type:     The type of function to execute; currently one of 'user' or 'admin' (default is 'user')
 *   - func:     The name of the module function to execute (default is 'main')
 *   - assign:   If set, the results are assigned to the corresponding variable instead of printed out
 *   - all remaining parameters are passed to the module function
 * 
 * Example
 * <!--[pnmodfunc modname="News" type="user" func="display"]-->
 * 
 * @author       Andreas Stratmann
 * @author       Jörg Napp
 * @since        03/05/23
 * @see          function.pnmodapifunc.php::smarty_function_pnmodapifunc()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the results of the module function
 */
function smarty_function_pnmodfunc($params, &$smarty) 
{
    extract($params); 

    unset($params['modname']);
    unset($params['type']);
    unset($params['func']);
    unset($params['assign']);
        
    if (!isset($modname)) {
        $smarty->trigger_error("pnmodfunc:  parameter 'modname' required");
        return false;
    }    

    if (!isset($type)) {
        $type='user';
    }
    if (!isset($func)) {
        $func='main';
    }

	$result = '';
	if (pnModAvailable($modname) && pnModLoad($modname, $type)) {
	    $result = pnModFunc($modname, $type, $func, $params);
		if (is_array($result)) {
        	$pnRender =& new pnRender($modname);
        	$pnRender->assign($result);
        	if (isset($return['template'])) {
        		echo $pnRender->fetch($return['template']);
        	} else {
        		$modname = strtolower($modname);
        		$type = strtolower($type);
        		$func = strtolower($func);
        		$result = $pnRender->fetch("{$modname}_{$type}_{$func}.htm");
        	}
		}
	} else {
        $smarty->trigger_error('pnmodfunc:  could not load module');
        return false;
	}

    if (isset($assign)) {
        $smarty->assign($assign, $result);
    } else {
        return $result;        
    }      
}

?>