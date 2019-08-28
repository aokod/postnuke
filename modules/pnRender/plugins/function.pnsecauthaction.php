<?php
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
 * @version      $Id: function.pnsecauthaction.php 16722 2005-08-27 12:35:10Z  $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 *
 * 
 * Example:
 * <!--[pnsecauthaction realm="0" comp="Stories::" inst=".*" level="ACCESS_ADMIN" assign="auth"]-->
 * 
 * true/false will be returned. 
 * 
 * This file is a plugin for pnRender, the PostNuke implementation of Smarty
 * @author       Steffen Voss (ArschMitOhren), http://www.post-nuke.net
 * @since        05/14/2004
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       boolean     authorized?
 */
function smarty_function_pnsecauthaction ($params, &$smarty) 
{
    extract($params); 
	unset($params);
	
	if (!isset($realm)) {
        $smarty->trigger_error("pnSecAuthAction: attribute realm required (always 0)");
        return false;
    }    

	if (!isset($comp)) {
        $smarty->trigger_error("pnSecAuthAction: attribute comp required");
        return false;
    }    

	if (!isset($inst)) {
        $smarty->trigger_error("pnSecAuthAction: attribute inst required");
        return false;
    }    

	if (!isset($level)) {
        $smarty->trigger_error("pnSecAuthAction: attribute level required");
        return false;
    }    
	$realm = (int) $realm;
	
	$result = pnSecAuthAction($realm, $comp, $inst, constant($level));
    
    if (isset($assign)) {
        $smarty->assign($assign, $result);
    } else {
        return $result;        
    }
}

?>