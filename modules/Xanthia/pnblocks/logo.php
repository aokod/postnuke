<?php
// $Id: logo.php 15015 2004-12-03 12:04:19Z markwest $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
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
// Original Author of file: Jim McDonald
// Purpose of file: Show first items in template
// ----------------------------------------------------------------------

/**
 * Blocks Module
 * 
 * This block displays the output of an API compliant module function
 * in a block
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   Xanthia
 * @version      $Id: logo.php 15015 2004-12-03 12:04:19Z markwest $
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

/**
 * initialise block
 */
function xanthia_logoblock_init()
{
    // Security
    pnSecAddSchema('Xanthia:LogoBlock:', 'Block title::');
}

/**
 * get information on block
 */
function xanthia_logoblock_info()
{
    // Values
    return array('text_type' => 'Theme Logo',
                 'module' => 'Xanthia',
                 'text_type_long' => 'Logo Block for Xanthia Themes',
                 'allow_multiple' => true,
                 'form_content' => false,
                 'form_refresh' => false,
                 'show_preview' => true);
}

/**
 * display block
 */
function xanthia_logoblock_display($blockinfo)
{
    // Security check
    if (!pnSecAuthAction(0,
                         'Xanthia:LogoBlock:',
                         "$blockinfo[title]::",
                         ACCESS_READ)) {
        return;
    }

    $thename = pnUserGetTheme();
	$skinID = pnModAPIFunc('Xanthia',
	                       'user',
	                       'getSkinID', 
	                           array('skin' => $thename));
	 // Database information
	// itevo: /Go; no DB tables for Xanthia loaded; fixed
	pnModDBInfoLoad('Xanthia');
	// /itevo
    $dbconn =& pnDBGetConn(true);
    $pntable =pnDBGetTables();
 	$themeconfigtable = $pntable['theme_config'];
	$themeconfigcolumn = $pntable['theme_config_column'];


	$query = "SELECT $themeconfigcolumn[setting] FROM $themeconfigtable 
	           WHERE $themeconfigcolumn[name] = 'buttons' 
	           AND $themeconfigcolumn[skin_id] = '$skinID'";

	$result =& $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return;
    } else {
		if (!$result->EOF)	{
			$result->Close();
			list($newbuttons) = $result->fields;
			$channels_folder = "themes/$thename/templates/images/Channels/channel".$newbuttons;
			if (!file_exists($channels_folder))	{	
				$channels_folder = "themes/$thename/templates/images/Channels/channel1";
			}
		}
	}
	
	// if no channels folder then no output needed
	if (!isset($channels_folder)) {
		return;
	}

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
	$pnRender =& new pnRender('Xanthia');

	 // assign the channels folder to the template
	// itevo: /Go; used a wrong variable 'channelsfolder'; fixed
	// to 'channels_folder'
	$pnRender->assign('channels_folder', $channels_folder);
	// /itevo

    // get the image
    $image = pnConfigGetVar('site_logo');
	$pnRender->assign('image', $image);

    // check file exists
	if (!file_exists($channels_folder.'/'.$image)) {
	    return;
	}

	// determine the image size
    $imagehw = GetImageSize($channels_folder.'/'.$image);
	$pnRender->assign('width', $imagehw[0]);
	$pnRender->assign('height', $imagehw[1]);

    // we don't want a title on this block
    $blockinfo['title']='';

    // Populate block info and pass to theme
    $blockinfo['content'] = $pnRender->fetch('xanthialogo.htm');
    return themesideblock($blockinfo);
}

?>