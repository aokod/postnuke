<?php
// $Id: function.pnsecgenauthkey.php 16722 2005-08-27 12:35:10Z  $
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
 * @version      $Id: function.pnsecgenauthkey.php 16722 2005-08-27 12:35:10Z  $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to generate a unique key to secure forms content as unique.
 * 
 * As the MGD states, all operations protected by pnSecAuthAction(). Ih the form, 
 * you generate a hidden field with the authid in your form, and check the authid 
 * in the submission. T.this way you can be sure that the data was given in your 
 * form (and not given by any POST/GET data from outside)
 * 
 * Note that you must not cache the outputs from this function, as its results
 * change aech time it is called. The PostNuke developers are looking for ways to
 * automise this.
 * 
 * 
 * Available parameters:
 *   - module:   The well-known name of a module to execute a function from (required)
 *   - assign:   If set, the results are assigned to the corresponding variable instead of printed out
 * 
 * Example
 *   <input type="hidden" name="authid" value="<!--[pnsecgenauthkey module="MyModule"]-->">
 *   
 * 
 * @author       Mark West
 * @since        08/08/2003
 * @todo         prevent this function from being cached (Smarty 2.6.0)
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the authentication key
 */
function smarty_function_pnsecgenauthkey ($params, &$smarty) 
{
    extract($params); 
	unset($params);

    $result = pnSecGenAuthKey($module);

    if (isset($assign)) {
        $smarty->assign($assign, $result);
    } else {
        return $result;        
    }        
}

?>