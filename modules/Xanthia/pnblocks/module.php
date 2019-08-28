<?php
// File: $Id: module.php 16396 2005-07-12 14:38:48Z markwest $ $Name$
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by Dmitry Beransky
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// Based on: search.php
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
// Original Author of file: Dmitry Beransky
// Updated by: Mark West
// Purpose of file: Show a modules output in a block 
// ----------------------------------------------------------------------
//
// VERSION 0.3

/**
 * Blocks Module
 * 
 * This block displays the output of an API compliant module function
 * in a block
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   Xanthia
 * @version      $Id: module.php 16396 2005-07-12 14:38:48Z markwest $
 * @author       Dmitry Beransky
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by Dmitry Beransky
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 


/**
 * initialise block
 * 
 * @author       Dmitry Beransky
 * @version      $Revision: 16396 $
 */
function xanthia_moduleblock_init()
{
	// Security
	pnSecAddSchema('Xanthia:Moduleblock:', 'Block title::');

}

/**
 * get information on block
 * 
 * @author       Dmitry Beransky
 * @version      $Revision: 16396 $
 * @return       array       The block information
 */
function xanthia_moduleblock_info()
{
	return array('text_type' 		=> 'module',
				 'module'			=> 'Xanthia',
				 'text_type_long' 	=> 'Display module output in a block',
				 'allow_multiple' 	=> true,
				 'form_content' 	=> false,
				 'form_refresh' 	=> false,
				 'show_preview' 	=> true);

}

/**
 * display block
 * 
 * @author       Dmitry Beransky
 * @version      $Revision: 16396 $
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the rendered bock
 */
function xanthia_moduleblock_display($blockinfo) 
{
    // check security
    if (!pnSecAuthAction(0, 'Xanthia:Moduleblock:', "$blockinfo[title]::", ACCESS_READ)) {
        return;
    }

    // Get variables from content block
    $vars = array();
    $vars = pnBlockVarsFromContent($blockinfo['content']);
    $modname = $vars['moduleid'];
    $modfunction = $vars['modulefunction'];
    $modparams = $vars['moduleparams'];
    parse_str($modparams, $params);

	// get the module info
    $modinfo = pnModGetInfo(pnModGetIdFromName($modname));

	// set a default block title
    if (empty($blockinfo['title'])) {
        $blockinfo['title'] = $modinfo['displayname'];
    }

	// get the output of the function
    $result = pnModFunc($modname, 'user', $modfunction, $params);

    // return the resultant output to the theme
    $blockinfo['content'] = $result;
    return themesideblock($blockinfo);
}

/**
 * modify block settings
 * 
 * @author       Dmitry Beransky
 * @version      $Revision: 16396 $
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the bock form
 */
function xanthia_moduleblock_modify($blockinfo)
{
    // Get current content
    $vars = pnBlockVarsFromContent($blockinfo['content']);

	// create the output object
	$pnRender =& new pnRender('Xanthia');

    // work out what modules we can actually display
	$userModules = pnModGetUserMods();
	$modules = array();
	foreach($userModules as $mod) {
		$modname = $mod['name'];
        $modid = pnModGetIDFromName($modname);
        $modinfo = pnModGetInfo($modid);
		if($modinfo['type'] == 2) {
			$modules[$mod['name']] = $mod['displayname'];
		}
	}

	// assign the content to the template
	$pnRender->assign('modules', $modules);
	$pnRender->assign('vars', $vars);

    // return our output
    return $pnRender->fetch('xanthiamoduleblockmodify.htm');

}

/**
 * update block settings
 * 
 * @author       Dmitry Beransky
 * @version      $Revision: 16396 $
 * @param        array       $blockinfo     a blockinfo structure
 * @return       $blockinfo  the modified blockinfo structure
 */
function xanthia_moduleblock_update($blockinfo)
{
	// get the variables passed from the form
	$vars['moduleid'] = pnVarCleanFromInput('moduleid');
	$vars['modulefunction'] = pnVarCleanFromInput('modulefunction');
	$vars['moduleparams'] = pnVarCleanFromInput('moduleparams');

    // store the variables
    $blockinfo['content'] = pnBlockVarsToContent($vars);
	
	return($blockinfo);
  
}

?>