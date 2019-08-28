<?php
// File: $Id: pnuser.php 17330 2005-12-14 15:23:48Z markwest $
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
// Original Author of file: Rob Brandt
// Purpose of file: Credits administration
// ----------------------------------------------------------------------


/**
 * @package PostNuke_Miscellaneous_Modules
 * @subpackage Credits
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * Credits_user_list
 *
 * Lists Information and Credits for all User Modules.
 * @author Rob Brandt
 * @version $Revision: 17330 $
 * @return HTML output string
 * @todo add support for user mods in system directory
 */
function credits_user_main()
{
    // Security Check
    if (!pnSecAuthAction(0, 'Credits::', '::', ACCESS_READ)) {
        return pnVarPrepForDisplay(_MODULENOAUTH);
    }

    // If the user is site admin then get all modules including admin only modules
    if (pnSecAuthAction(0, '::', '::', ACCESS_ADMIN)) {
        $mods = pnModGetAllMods();
        $cacheid = 0;
    } else {
        $mods = pnModGetUserMods();
        $cacheid = 1;
    }

    // highly unlikely but check if we have no modules returned
    if ($mods == false) {
        return pnVarPrepHTMLDisplay(_CREDITSNOMODS);
    }

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
    $pnRender =& new pnRender('Credits');

    // For caching reasons you must pass a cache ID. This could be done as a
    // separate parameter to every method that uses caching (like fetch, is_cached
    // etc.) or by assigning the ID to the cache_id property like it is done in
    // this case.
    // Here the output only changes based on the security check to we use the
    // cacheid flag set earlier
    $pnRender->cache_id = $cacheid;

    // check out if the contents are cached.
    // If this is the case, we do not need to make DB queries.
    if ($pnRender->is_cached('credits_user_main.htm')) {
       return $pnRender->fetch('credits_user_main.htm');
    }

    $modules = array();
    foreach($mods as $mod) {
		$f = $mod['directory'];
		$fos = pnVarPrepForOS($mod['directory']);
		
		pnModLangLoad($fos, 'version');
		
		// Add applicable actions
		//$actions = array();
		$modversion = '';
		$modversion['displayname'] = $mod['displayname'];
		$modversion['filename']    = $f;
		$modversion['name']        = '';
		$modversion['version']     = '';
		$modversion['description'] = '';
		$modversion['credits']     = '';
		$modversion['help']        = '';
		$modversion['changelog']   = '';
		$modversion['license']     = '';
		$modversion['official']    = 0;
		$modversion['author']      = '';
		$modversion['contact']     = '';
		$modversion['admin']       = 0;
	    if (file_exists("modules/$fos/Version.php")) {
            include "modules/$fos/Version.php";
    	} else if (file_exists("modules/$fos/pnversion.php")) {
            include "modules/$fos/pnversion.php";
	    } else if (file_exists("system/$fos/Version.php")) {
            include "system/$fos/Version.php";
	    } else if (file_exists("system/$fos/pnversion.php")) {
            include "system/$fos/pnversion.php";
        } else {
            $modversion['name'] = $f;
            $modversion['version'] = $mod['version'];
            $modversion['description'] = $mod['description'];
        }

		// check for the existence of the files
		if (!file_exists('modules/'.$mod['directory'].'/'.$modversion['credits']) &&
			!file_exists('system/'.$mod['directory'].'/'.$modversion['credits'])) {
			$modversion['credits'] = '';
		}
		if (!file_exists('modules/'.$mod['directory'].'/'.$modversion['help']) &&
			!file_exists('system/'.$mod['directory'].'/'.$modversion['help'])) {
			$modversion['help'] = '';
		}
		if (!file_exists('modules/'.$mod['directory'].'/'.$modversion['changelog']) &&
			!file_exists('system/'.$mod['directory'].'/'.$modversion['changelog'])) {
			$modversion['changelog'] = '';
		}
		if (!file_exists('modules/'.$mod['directory'].'/'.$modversion['license']) &&
			!file_exists('system/'.$mod['directory'].'/'.$modversion['license'])) {
			$modversion['license'] = '';
		}
	
		// explode the contact and author arrays to allow for
		// multiple authors and contacts
		$modversion['author'] = explode(',', $modversion['author']);
		$modversion['contact'] = explode(',', $modversion['contact']);

		// check if an e-mail address is given as the contact
		foreach($modversion['contact'] as $key => $contact) {
			$contact = trim($contact);
			if (eregi ("^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,4}$", $contact)) {
				$modversion['contact'][$key] = 'mailto:' . $contact;
			} else {
                $modversion['contact'][$key] = $contact;
            }
		}
		$modules[] = $modversion;
    }

    // assign content to user
    $pnRender->assign('modules', $modules);

    return $pnRender->fetch('credits_user_main.htm');
}

/**
 * Credits user display
 *
 * Displays modules documentation
 * @author Rob Brandt
 * @version $Revision: 17330 $
 * @return HTML output string
 * @todo add support for user mods in system directory
 * @todo template output
 */
function credits_user_display($args) {

	if (!pnSecAuthAction(0, 'Credits::', '::', ACCESS_READ)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

	list($mod,
	     $filetype) = pnVarCleanFromInput('mod',
									  	  'filetype');
	  extract($args);

	// work out the version number, the display name for the module (or core)
	// and the directory(ies) to locate the files
	if ($mod == 'core') {
		$modinfo = array();
		$modinfo['name'] = 'core';
		$modinfo['version'] = _PN_VERSION_NUM;
		$modinfo['displayname'] = _PN_VERSION_ID;
		$moduledir1 = 'docs/';
		$moduledir2 = '';
	} else {
		$modinfo =& pnModGetInfo(pnModGetIDFromName($mod));
		if (!is_array($modinfo)) {
			return pnVarPrepHTMLDisplay(_CREDITSNOEXIST);
		}
		$moduledir1 = 'modules/';
		$moduledir2 = 'system/';
	}

    // locate the correct file
    // Create output object - this object will store all of our output so that
    // we can return it easily when required
    $pnRender =& new pnRender('Credits');

	// For caching reasons you must pass a cache ID. This could be done as a
	// separate parameter to every method that uses caching (like fetch, is_cached
	// etc.) or by assigning the ID to the cache_id property like it is done in
	// this case.
	// In this case we use hash of the path and filename
	$pnRender->cache_id = md5($modinfo['directory'].$filetype);

	// check out if the contents are cached.
	// If this is the case, we do not need to make DB queries.
	if ($pnRender->is_cached('credits_user_display.htm')) {
		return $pnRender->fetch('credits_user_display.htm');
	}

	// now lets locate the module version info
	if ($mod == 'core') {
		switch ($filetype) {
		case 'credits':
			$filename = 'docs/CREDITS.txt';
			break;
		case 'help':
			$filename = 'docs/manual.txt';
			break;
		case 'license':
			$filename = 'docs/COPYING.txt';
			break;
		}
	} else {
		$f = $modinfo['directory'];
		$fos = pnVarPrepForOS($modinfo['directory']);
		$modversion = '';
		$modversion['displayname'] = $modinfo['displayname'];
		$modversion['filename'] = $f;
		$modversion['name'] = '';
		$modversion['version'] = '';
		$modversion['description'] = '';
		$modversion['credits'] = '';
		$modversion['help'] = '';
		$modversion['changelog'] = '';
		$modversion['license'] = '';
		$modversion['official'] = 0;
		$modversion['author'] = '';
		$modversion['contact'] = '';
		$modversion['admin'] = 0;
		if (file_exists("modules/$fos/Version.php")) {
			include "modules/$fos/Version.php";
			if (empty($modversion[$filetype])) {
				return pnVarPrepHTMLDisplay(_CREDITSNOEXIST);
			}
			$filename = "modules/$fos/$modversion[$filetype]";
		} else if (file_exists("modules/$fos/pnversion.php")) {
			include "modules/$fos/pnversion.php";
			if (empty($modversion[$filetype])) {
				return pnVarPrepHTMLDisplay(_CREDITSNOEXIST);
			}
			$filename = "modules/$fos/$modversion[$filetype]";
		} else if (file_exists("system/$fos/Version.php")) {
			include "system/$fos/Version.php";
			if (empty($modversion[$filetype])) {
				return pnVarPrepHTMLDisplay(_CREDITSNOEXIST);
			}
			$filename = "system/$fos/$modversion[$filetype]";
		} else if (file_exists("system/$fos/pnversion.php")) {
			include "system/$fos/pnversion.php";
			if (empty($modversion[$filetype])) {
				return pnVarPrepHTMLDisplay(_CREDITSNOEXIST);
			}
			$filename = "system/$fos/$modversion[$filetype]";
		}
	}

	if (file_exists($filename)) {
		$thefile = implode('',file($filename));
		$thefile = nl2br(pnVarPrepForDisplay($thefile));
	} else {
		$thefile = pnVarPrepForDisplay(_CREDITSNOEXIST);
	}

    // assign the values to the template
    $pnRender->assign('heading', constant('_CREDITS' . strtoupper($filetype)));
    $pnRender->assign($modinfo);
    $pnRender->assign('thefile', $thefile);
    $pnRender->assign('filetype', $filetype);

    // create a fake numeric item id to allow display hooks work properly
    $pnRender->assign('itemid', $pnRender->cache_id);

    return $pnRender->fetch('credits_user_display.htm');
}

?>