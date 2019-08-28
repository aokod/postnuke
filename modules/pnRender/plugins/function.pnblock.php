<?php
// $Id: function.pnblock.php 20519 2006-11-13 20:39:48Z landseer $
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
 * @version      $Id: function.pnblock.php 20519 2006-11-13 20:39:48Z landseer $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to display an existing PostNuke block.
 * 
 * The block is choosen by its id.
 * 
 * The block state is ignored, so even deactivated blocks
 * can be shown.
 *
 * The block specific parameters can be overwritten,
 * considering they are known.
 *
 * Available parameters:
 *   - id        id of block to be displayed
 *   - name      title of block to be displayed
 *   - title     Overwrites block title
 *   - position  Overwrites position (l,c,r)
 *   - assign:   If set, the results are assigned to the corresponding variable instead of printed out
 *
 * Example
 *   TBD
 * 
 * 
 * @author   Andreas Stratmann
 * @author   Jörg Napp
 * @since    03/05/23
 * @param    array    $params      All attributes passed to this function from the template
 * @param    object   &$smarty     Reference to the Smarty object
 * @return   string   the block
 */
function smarty_function_pnblock ($params, &$smarty) 
{
    extract($params);      

    // unset the variables for the function, leaving the ones for the block
    unset($params['bid']);
    unset($params['name']);
    unset($params['title']);
    unset($params['position']);
    unset($params['assign']);
        
    if (!isset($bid)) {
        $smarty->trigger_error("pnblock: block id (bid) required");
        return false;
    }    

    //  render the block
    $blockinfo=pnBlockGetInfo($bid);

    // overwrite block title
    if (isset($title)) {
        $blockinfo['title']=$title;    
    }

    if (isset($position)) {
        $blockinfo['position']=$position;    
    }

    $blockinfo['bid']=$bid; // bid is not return by BlockGetInfo.
    
    // Overwrite block specific config vars.
    // Only the new style is supported.
    if (count($params) > 0) {
        $_vars = pnBlockVarsFromContent($blockinfo['content']);
        $_vars = array_merge($_vars, $params);
        $blockinfo['content'] = pnBlockVarsToContent($_vars);
    }    

    // We need the module name. 
    $modinfo = pnModGetInfo($blockinfo['mid']);
    if (!is_array($modinfo) || !isset($modinfo['name'])) {
        $modinfo = array('name' => 'core');
    }

    // The theme must be loaded before showing a block
    pnThemeLoad(pnUserGetTheme());

    // show the block and capture its contents
    ob_start();
    pnBlockShow($modinfo['name'], $blockinfo['bkey'], $blockinfo);
    $content = ob_get_contents();
    ob_end_clean();

    if (isset($assign)) {
        $smarty->assign($assign, $content);
    } else {
        return $content;        
    }
}

?>