<?php
// $Id: function.modstyleslist.php 16499 2005-07-24 22:58:29Z markwest $
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
 * @version      $Id: function.modstyleslist.php 16499 2005-07-24 22:58:29Z markwest $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */


/**
 * Smarty function to display a drop down list of module stylesheets
 *
 * Available parameters:
 *   - modname   The module name to show the styles for
 *   - assign:   If set, the results are assigned to the corresponding variable instead of printed out
 *   - name:     Name for the control
 *   - selected: Selected value
 *
 * Example
 *   <!--[modstyleslist name=modulestylesheet selected=navtabs.css]-->
 *
 *
 * @author       Mark West
 * @since        24 July 2005
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the value of the last status message posted, or void if no status message exists
 */
function smarty_function_modstyleslist($params, &$smarty)
{
    extract($params);

    unset($params['name']);
    unset($params['selected']);
    unset($params['modname']);

    if (!isset($name)) {
        $smarty->trigger_error("modstyleslist:  parameter 'name' required");
        return false;
    }
    if (!isset($modname)) {
        $smarty->trigger_error("modstyleslist:  parameter 'modname' required");
        return false;
    }

    $modstyleslist = pnModAPIFunc('Admin', 'admin', 'getmodstyles', array('modname' => $modname));
    $stylesdropdown = '<select name="'.pnVarPrepForDisplay($name)."\">\n";
    foreach ($modstyleslist as $style) {
        if (isset($selected) && $style == $selected) {
            $selectedtext = ' selected="selected"';
        } else {
            $selectedtext = '';
        }
		$stylesdropdown .= '<option value="'.pnVarPrepForDisplay($style)."\"$selectedtext>".pnVarPrepForDisplay($style)."</option>\n";
    }
    $stylesdropdown .= '</select>';

    if (isset($assign)) {
        $smarty->assign($assign, $stylesdropdown);
    } else {
        return $stylesdropdown;
    }
}
?>