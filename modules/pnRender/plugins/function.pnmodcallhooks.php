<?php
// $Id: function.pnmodcallhooks.php 16380 2005-07-10 23:26:04Z landseer $
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
 * @version      $Id: function.pnmodcallhooks.php 16380 2005-07-10 23:26:04Z landseer $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */


/**
 * Smarty function call hooks
 *
 * This function calls a specific module function.  It returns whatever the return
 * value of the resultant function is if it succeeds.
 * Note that in contrast to the API function pnmodcallhooks you need not to load the
 * module with pnModLoad.
 *
 *
 * Available parameters:
 * - 'hookobject' the object the hook is called for - either 'item' or 'category'
 * - 'hookaction' the action the hook is called for - one of 'create', 'delete', 'transform', or 'display'
 * - 'hookid'     the id of the object the hook is called for (module-specific)
 * - 'assign'     If set, the results are assigned to the corresponding variable instead of printed out
 * - all remaining parameters are passed to the pnModCallHooks API via the extrainfo array
 *
 * Example
 * <!--[pnmodcallhooks hookobject="item" hookaction="modify" hookid=$tid $modname="Example" $objectid=$tid]-->
 *
 * @author       Mark West
 * @since        26/04/2004
 * @see          function.pnmodcallhooks.php::smarty_function_pnmodcallhooks()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the results of the module function
 */
function smarty_function_pnmodcallhooks($params, &$smarty)
{
    extract($params);

    unset($params['hookobject']);
    unset($params['hookaction']);
    unset($params['hookid']);
    unset($params['assign']);
	unset($params['implode']);

    if (!isset($hookobject)) {
        $smarty->trigger_error("pnmodcallhooks:  parameter 'hookobject' required");
        return false;
    }
    if (!isset($hookaction)) {
        $smarty->trigger_error("pnmodcallhooks:  parameter 'hookaction' required");
        return false;
    }
    if (!isset($hookid)) {
		$hookid = '';
    }
	if (!isset($implode)) {
		$implode = true;
	}

    // create returnurl if not supplied (= this page)
    if (!isset($params['returnurl']) || empty($params['returnurl'])) {
        $params['returnurl'] = str_replace('&amp;', '&', 'http://' . pnGetHost() . pnGetCurrentUri());
    }

	// if the implode flag is true then we must always assign the result to a template variable
	// outputing the erray is no use....
	if (!$implode) {
		$assign = 'hooks';
	}


	$result = pnModCallHooks($hookobject, $hookaction, $hookid, $params, $implode);

    if (isset($assign)) {
        $smarty->assign($assign, $result);
    } else {
        return $result;
    }
}

?>