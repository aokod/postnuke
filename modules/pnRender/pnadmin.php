<?php
// $Id: pnadmin.php 13918 2004-07-07 02:31:04Z larry $
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
 * pnRender
 * 
 * PostNuke wrapper class for Smarty
 * 
 * @author      PostNuke development team 
 * @version     .7/.8
 * @link        http://www.post-nuke.net              PostNuke home page
 * @link        http://smarty.php.net                 Smarty home page
 * @license     http://www.gnu.org/copyleft/gpl.html  GNU General Public License
 * @package     PostNuke_System_Modules
 * @subpackage  pnRender
 */


/**
 * Main administration function
 * 
 * This function provides the main administration interface 
 * 
 * @author       Joerg Napp
 * @version      $Revision: 13918 $
 * @return       output   the admin interface
 */
function pnRender_admin_main() {
	if (!pnSecAuthAction(0, 'pnRender::', '::', ACCESS_ADMIN)) {
		return _PNRENDER_NOAUTH;
	} 
	
	// get current settings
	// this can be done more elegant, but for now it works ;-)
	$compile_checked=array();
	if (pnModGetVar('pnrender', 'compile_check')) {
		$compile_checked[] = 'pnrender_compile_check';
	}
	if (pnModGetVar('pnrender', 'force_compile')) {
		$compile_checked[] = 'pnrender_force_compile';
	}

	$cache_checked=array();
	if (pnModGetVar('pnrender', 'cache')) {
		$cache_checked[] = 'pnrender_cache';
	}

	$expose_checked=array();
	if (pnModGetVar('pnrender', 'expose_template')) {
		$expose_checked[] = 'pnrender_expose';
	}

	// create pnRender object
	$pnRender =& new pnRender('pnRender');

    // As admin output changes often, we do not want caching.
    $pnRender->caching = false;
	
	// assign the variables for the checkboxes
	$pnRender->assign('compile_output', array(_PNRENDER_COMPILE_CHECK,
                                              _PNRENDER_FORCE_COMPILE));
	$pnRender->assign('compile_values', array('pnrender_compile_check',
                                              'pnrender_force_compile'));
	$pnRender->assign('compile_checked', $compile_checked);
	
	// debug
	$pnRender->assign('expose_output',  array(_PNRENDER_EXPOSE_TEMPLATE));
	$pnRender->assign('expose_values',  array('pnrender_expose'));
	$pnRender->assign('expose_checked', $expose_checked);
	 
	// assign the rest
	$pnRender->assign('cache_output', array(_PNRENDER_CACHE_ENABLE));
	$pnRender->assign('cache_values', array('pnrender_cache'));
	$pnRender->assign('cache_checked', $cache_checked);
	
	$pnRender->assign('lifetime', pnModGetVar('pnrender', 'lifetime'));

	// fetch and return the output
	return $pnRender->fetch('pnrender_admin.htm');
}


/**
 * Update the settings
 * 
 * This is the function that is called with the results of the
 * form supplied by pnRender_admin_main to alter the admin settings
 * 
 * @author       Joerg Napp
 * @version      $Revision: 13918 $
 * @param        settings   the options in an array
 * @param        lifetime   cache lifetime
 */
function pnRender_admin_updateconfig($args)
{
	if (!pnSecAuthAction(0, 'pnRender::', '::', ACCESS_ADMIN)) {
		return _PNRENDER_NOAUTH;
	} 
	
	if (!pnSecConfirmAuthKey()) {
		pnSessionSetVar('errormsg', _BADAUTHKEY);
		pnRedirect(pnModURL('pnRender', 'admin', 'main'));
		return true;
	} 

	list($settings, $lifetime) = pnVarCleanFromInput('settings', 'lifetime');

	extract($args);

	// set defaults
	if (!$settings) {
		$settings = array();
	}
	if (!(int)$lifetime) {
		$lifetime = 3600;
	}

	pnModSetVar('pnrender', 'compile_check',   in_array('pnrender_compile_check', $settings));
	pnModSetVar('pnrender', 'force_compile',   in_array('pnrender_force_compile', $settings));
	pnModSetVar('pnrender', 'cache',           in_array('pnrender_cache',         $settings));
	pnModSetVar('pnrender', 'expose_template', in_array('pnrender_expose',        $settings));

	pnModSetVar('pnrender', 'lifetime',      $lifetime);

	pnSessionSetVar('statusmsg', _PNRENDER_CONFIG_UPDATED);
	pnRedirect(pnModURL('pnRender', 'admin', 'main'));
	return true;
}


/**
 * Clear compiled templates
 * 
 * Using this function, the admin can clear all compiled templates for
 * the system.
 * 
 * @author       Joerg Napp
 * @version      $Revision: 13918 $
 */
function pnRender_admin_clear_compiled($args)
{
	if (!pnSecAuthAction(0, 'pnRender::', '::', ACCESS_ADMIN)) {
		return _PNRENDER_NOAUTH;
	} 
	
	
	if (!pnSecConfirmAuthKey()) {
		pnSessionSetVar('errormsg', _BADAUTHKEY);
		pnRedirect(pnModURL('pnRender', 'admin', 'main'));
		return true;
	}

   	$pnRender  =& new Smarty;
   	$pnRender->compile_dir = pnConfigGetVar('temp') . '/pnRender_compiled';
   	$pnRender->cache_dir = pnConfigGetVar('temp') . '/pnRender_cache';
   	$pnRender->use_sub_dirs = false;
   	$pnRender->clear_compiled_tpl();

	pnSessionSetVar('statusmsg', _PNRENDER_COMPILED_CLEARED);
	pnRedirect(pnModURL('pnRender', 'admin', 'main'));
	return true;
}


/**
 * Clear cached pages
 * 
 * Using this function, the admin can clear all cached templates for
 * the system.
 * 
 * @author       Joerg Napp
 * @version      $Revision: 13918 $
 */
function pnRender_admin_clear_cache($args)
{
	if (!pnSecAuthAction(0, 'pnRender::', '::', ACCESS_ADMIN)) {
		return _PNRENDER_NOAUTH;
	} 

	if (!pnSecConfirmAuthKey()) {
		pnSessionSetVar('errormsg', _BADAUTHKEY);
		pnRedirect(pnModURL('pnRender', 'admin', 'main'));
		return true;
	}

   $pnRender  =& new Smarty;
   $pnRender->compile_dir = pnConfigGetVar('temp') . '/pnRender_compiled';
   $pnRender->cache_dir = pnConfigGetVar('temp') . '/pnRender_cache';
   $pnRender->caching = true;
   $pnRender->use_sub_dirs = false;
   $pnRender->clear_all_cache(); 
	pnSessionSetVar('statusmsg', _PNRENDER_CACHE_CLEARED);
	pnRedirect(pnModURL('pnRender', 'admin', 'main'));
	return true;
}

?>