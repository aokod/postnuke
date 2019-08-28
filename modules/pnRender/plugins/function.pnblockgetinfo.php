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
 * @version      $Id: function.pnblockgetinfo.php 15997 2005-03-17 14:14:45Z markwest $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to obtain the a block variable
 * 
 * Note: If the name parameter is not set then the assign parameter must be set since there is an array of
 * block variables available.
 *
 * Available parameters:
 *   - bid: the block id
 *   - name: If set the name of the parameter to get otherwise the entire block array is assigned to the template
 *   - assign: If set, the results are assigned to the corresponding variable instead of printed out
 * 
 * 
 * @author       Mark West
 * @since        17.03.2005
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the variables content
 */
function smarty_function_pnblockgetinfo($params, &$smarty)
{
    extract($params);
    unset($params);

    if (!isset($bid)) {
        $smarty->trigger_error('pnblockgetinfo: attribute bid required');
    }

	// get the block info array
	$blockinfo = pnBlockGetInfo($bid);

	if (isset($name)) {
		if (isset($assign)) {
			$smarty->assign($assign, $blockinfo[$name]);
		} else {
			return $blockinfo[$name];
		}
	} else {
		// handle the full blockinfo array
		if (isset($assign)) {
			$smarty->assign($assign, $blockinfo);
		} else {
	        $smarty->trigger_error('pnblockgetinfo: attribute assign required');
		}
	}

    return;
} 

?>