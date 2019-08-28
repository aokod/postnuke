<?php
// $Id: function.pndebug.php 16272 2005-05-26 11:20:31Z landseer $
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
 * @version      $Id: function.pndebug.php 16272 2005-05-26 11:20:31Z landseer $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

/**
 * Smarty function to display a PostNuke specific debug window
 *
 * This function shows a PostNuke debug window if the user has sufficient access rights
 *
 * You need to have:
 * modulename::debug     .*     ACCESS_ADMIN
 * permission to see this.
 *
 *
 * Example
 *   <!--[ pndebug ]-->
 *
 *
 * @author       Frank Schummertz
 * @since        23/08/2004
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @param        string      $output      if html, show debug in rendered page, otherwise open popup window
 * @param        string      $template    specify different debug template, default pndebug.html,
 *                                        must be stored in pnRender/pntemplates
 * @return       string      debug output
 *
 * This plugin is basing on the original debug plugin written by Monte Ohrt <monte@ispi.net>
 */
function smarty_function_pndebug($params, &$smarty)
{
    $out = '';
    $thismodule = pnModGetName();
    if(pnSecAuthAction(0, $thismodule.'::debug', '::', ACCESS_ADMIN)) {
        if($params['output']) {
            $smarty->assign('_smarty_debug_output',$params['output']);
        }
        $modinfo = pnModGetInfo(pnModGetIDFromName('pnRender'));
        $modpath = ($modinfo['type'] == 3) ? 'system' : 'modules';

        $_template_dir_orig = $smarty->template_dir;
        $smarty->template_dir = "$modpath/$modinfo[directory]/pntemplates";

        if($params['template']) {
            $debug_tpl = $smarty->template_dir . '/' . $params['template'];
            if(file_exists($debug_tpl) && is_readable($debug_tpl)) {
                $smarty->debug_tpl = $params['template'];
            }
        } else {
            $smarty->debug_tpl = "pndebug.html";
        }

        if($smarty->security && is_file($smarty->debug_tpl)) {
            $smarty->secure_dir[] = dirname(realpath($smarty->debug_tpl));
        }

        $_compile_id_orig = $smarty->_compile_id;
        $smarty->_compile_id = null;

        $_compile_path = $smarty->_get_compile_path($smarty->debug_tpl);
        if ($smarty->_compile_resource($smarty->debug_tpl, $_compile_path))
        {
            ob_start();
            $smarty->_include($_compile_path);
            $out = ob_get_contents();
            ob_end_clean();
        } else {
        }

        $smarty->_compile_id = $_compile_id_orig;
        $smarty->template_dir = $_template_dir_orig;
    }
    return $out;
}

?>