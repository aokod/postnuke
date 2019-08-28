<?php
// $Id: sniffer.php 15680 2005-02-08 17:28:34Z markwest $
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
 * Sniffer Module
 *
 * @package      PostNuke_Utility_Modules
 * @subpackage   Sniffer
 * @version      $Id: sniffer.php 15680 2005-02-08 17:28:34Z markwest $
 * @author       Mark West
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

/**
 * initialise block
 * 
 * @author       The PostNuke Development Team
 */
function Sniffer_snifferblock_init()
{
    // Security
    pnSecAddSchema('Sniffer:Snifferblock:', 'Block title::');
}

/**
 * get information on block
 * 
 * @author       The PostNuke Development Team
 * @return       array       The block information
 */
function Sniffer_snifferblock_info()
{
    return array('text_type'      => 'Sniffer',
                 'module'         => 'Sniffer',
                 'text_type_long' => 'Show browser info',
                 'allow_multiple' => true,
                 'form_content'   => false,
                 'form_refresh'   => false,
                 'show_preview'   => true);
}

/**
 * display block
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the rendered bock
 */
function Sniffer_snifferblock_display($blockinfo)
{
    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing.  
	// Note that we have Sniffer:Snifferblock: as the component.
    if (!pnSecAuthAction(0, 'Sniffer:Snifferblock:', "$blockinfo[title]::", ACCESS_READ)) {
        return false;
    }

	// Check if the Sniffer module is available. 
	if (!pnModAvailable('Sniffer')) {
		return false;
	}

	// get the object
	// Note: we're calling a technically private API function but since we're
	// in the module that's defined the API then this seems ok (ish...).
	$browserinfo = pnModAPIFunc('Sniffer', 'user', 'get');

	// we also need the module language file for it's definitations
	pnModLangLoad('Sniffer', 'admin');

    // Create output object
	$pnRender =& new pnRender('Sniffer');

	// assign the object
	// Note: we assign the object by reference to avoid duplication in memory
	$pnRender->assign_by_ref('browserinfo', $browserinfo);

    // Populate block info and pass to theme
    $blockinfo['content'] = $pnRender->fetch('sniffer_block_sniffer.htm');

    return themesideblock($blockinfo);
}


/**
 * modify block settings
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the bock form
 */
function Sniffer_snifferblock_modify($blockinfo)
{
	return;
}


/**
 * update block settings
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       $blockinfo  the modified blockinfo structure
 */
function Sniffer_snifferblock_update($blockinfo)
{
    return $blockinfo;
}

?>