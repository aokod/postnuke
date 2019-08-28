<?php
// $Id: function.pnml.php 16627 2005-08-10 08:02:39Z markwest $
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
 * @version      $Id: function.pnml.php 16627 2005-08-10 08:02:39Z markwest $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */


/**
 * Smarty function to read a PostNuke language constant.
 *
 * This function takes a identifier and returns the corresponding language constant.
 *
 * Available parameters:
 *   - name:     Name of the language constant to return
 *   - html:     Treat the language define as HTML
 *   - assign:   If set, the results are assigned to the corresponding variable instead of printed out
 *   - *         All remaining parameters are used as string replacements
 *
 * Example
 * _EXAMPLESTRING = 'Hello World'
 * <!--[pnml name="_EXAMPLESTRING"]--> returns Hello World
 *
 * _EXAMPLESTRING = 'There are %u% users online';
 *  $usersonline = 10
 * <!--[pnml name=_EXAMPLESTRING u=$usersonline]--> returns There are 10 users online
 *
 * @author       Mark West
 * @since        08/08/2003
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the language constant
 */
function smarty_function_pnml ($params, &$smarty)
{
    extract($params);
    unset($params['name']);
    unset($params['html']);
    unset($params['assign']);

    if (!isset($name)) {
        $smarty->trigger_error('pnml: attribute name required');
        return false;
    }

    // if the language files are missing, return the name of the constant instead.
    if (!defined($name)) {
        if (isset($assign)) {
            $smarty->assign($assign, $name);
        } else {
            return $name;
        }
    }

	// locate the constant
	$result = constant($name);

	// perform any string replacements
	if (!empty($params)) {
		foreach ($params as $var => $string) {
			$var = "%$var%";
			$result = str_replace($var, $string, $result);
		}
	}

    if (isset($html) && ($html > 0)) {
        $result = pnVarPrepHTMLDisplay($result);
    } else {
        $result = pnVarPrepForDisplay($result);
    }

    if (isset($assign)) {
        $smarty->assign($assign, $result);
    } else {
        return $result;
    }
}
?>