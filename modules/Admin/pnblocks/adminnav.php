<?php
// $Id: adminnav.php 15905 2005-03-04 13:00:28Z markwest $
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
// Original Author of file: Mark West
// Purpose of file: Display Admin Categories and Modules
// ----------------------------------------------------------------------
/**
 * @author       Mark West
 * @version      $Revision: 15905 $
 * @package      PostNuke_System_Modules
 * @subpackage   Admin
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
 
/**
 * initialise block
 */
function Admin_adminnavblock_init()
{
    // Security
    pnSecAddSchema('Admin:adminnavblock:', 'Block title::');
}

/**
 * get information on block
 */
function Admin_adminnavblock_info()
{
    // Values
    return array('text_type'      => 'Admin',
                 'module'         => 'Admin',
                 'text_type_long' => 'Show admin categories and modules',
                 'allow_multiple' => false,
                 'form_content'   => false,
                 'form_refresh'   => false,
                 'show_preview'   => true);
}

/**
 * display block
 */
function Admin_adminnavblock_display($blockinfo)
{
    // Security check
    if (!pnSecAuthAction(0,
                         'Admin:adminnavblock',
                         "$blockinfo[title]::",
                         ACCESS_ADMIN)) {
        return;
    }

    // Get variables from content block
    $vars = pnBlockVarsFromContent($blockinfo['content']);

    // Call the modules API to get the items
    if (pnModAvailable('Admin')) {
        $items = pnModAPIFunc('Admin', 'admin', 'getall');	
    } else {
	    return;
	}

	// Check for no items returned
	if (empty($items)) {
	    return;
	}

    // Create output object
	// Note that for a block the corresponding module must be passed.
	$pnRender = new pnRender('Admin');

	// get admin capable modules
	$adminmodules = pnModGetAdminMods();
	$adminmodulescount = count($adminmodules);

    // Display each item, permissions permitting
	$admincategories = array();
	foreach ($items as $item) {
		if (pnSecAuthAction(0, 'Admin::', "$item[catname]::$item[cid]", ACCESS_READ)) {
			$adminlinks = array();
			foreach ($adminmodules as $adminmodule) {
				// The user API function is called.  This takes the number of items
				// required and the first number in the list of all items, which we
				// obtained from the input and gets us the information on the appropriate
				// items.
				$catid = pnModAPIFunc('Admin',
									'admin',
									'getmodcategory',
									array('mid' => pnModGetIDFromName($adminmodule['name'])));

				if (($catid == $item['cid']) || (($catid == false) && ($item['cid'] == pnModGetVar('Admin', 'defaultcategory')))) {
					$modinfo = pnModGetInfo(pnModGetIDFromName($adminmodule['name']));
					if ($modinfo['type'] == 2) {
						$menutexturl = pnModURL($modinfo['name'], 'admin'); 
						$menutexttitle = $modinfo['name'];
					} else {
						$menutexturl = 'admin.php?module=' . pnVarPrepForDisplay($modinfo['name']);
						$menutexttitle =  $modinfo['name'];
					}
					$adminlinks[] = array('menutexturl' => $menutexturl,
										  'menutexttitle' => $menutexttitle);
				}
			}
			$admincategories[] = array('url' => pnModURL('Admin', 'admin', 'adminpanel', array('cid' => $item['cid'])),
									   'title' => pnVarPrepForDisplay($item['catname']),
									   'modules' => $adminlinks);
		}
    }

    $pnRender->assign('admincategories', $admincategories);
	
    // Populate block info and pass to theme
    $blockinfo['content'] = $pnRender->Fetch('admin_block_adminnav.htm');

    return themesideblock($blockinfo);
}

?>