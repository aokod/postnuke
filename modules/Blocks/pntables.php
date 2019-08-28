<?php
// $Id: pntables.php 15321 2005-01-10 14:28:40Z markwest $
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
 * Blocks Module
 * 
 * Purpose of file:  Table information for Blocks module --
 *                   This file contains all information on database 
 *                   tables for the module
 *
 * @package      PostNuke_System_Modules
 * @subpackage   Blocks
 * @version      $Id: pntables.php 15321 2005-01-10 14:28:40Z markwest $
 * @author       Mark West
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2004 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

 
/**
 * Populate pntables array for Blocks module
 * 
 * This function is called internally by the core whenever the module is
 * loaded. It delivers the table information to the core.
 * It can be loaded explicitly using the pnModDBInfoLoad() API function.
 * 
 * @author       Mark West
 * @return       array       The table information.
 */
function Blocks_pntables()
{
    // Initialise table array
    $pntable = array();

    // Get the name for the Blocks item table.  This is not necessary
    // but helps in the following statements and keeps them readable
    $blocks = pnConfigGetVar('prefix') . '_blocks';

    // Set the table name
	$pntable['blocks'] = $blocks;

    // Set the column names.  Note that the array has been formatted
    // on-screen to be very easy to read by a user.
	$pntable['blocks_column'] = array ('bid'         => $blocks . '.pn_bid',
									   'bkey'        => $blocks . '.pn_bkey',
									   'title'       => $blocks . '.pn_title',
									   'content'     => $blocks . '.pn_content',
									   'url'         => $blocks . '.pn_url',
									   'mid'         => $blocks . '.pn_mid',
									   'position'    => $blocks . '.pn_position',
									   'weight'      => $blocks . '.pn_weight',
									   'active'      => $blocks . '.pn_active',
									   'collapsable' => $blocks . '.pn_collapsable',
									   'defaultstate'=> $blocks . '.pn_defaultstate',
									   'refresh'     => $blocks . '.pn_refresh',
									   'last_update' => $blocks . '.pn_last_update',
									   'blanguage'   => $blocks . '.pn_language',
									   'language'    => $blocks . '.pn_language');

    // Get the name for the Blocks item table.  This is not necessary
    // but helps in the following statements and keeps them readable
    $userblocks = pnConfigGetVar('prefix') . '_userblocks';

    // Set the table name
	$pntable['userblocks'] = $userblocks;

    // Set the column names.  Note that the array has been formatted
    // on-screen to be very easy to read by a user.
	$pntable['userblocks_column'] = array ('uid'         => $userblocks . '.pn_uid',
										   'bid'         => $userblocks . '.pn_bid',
										   'active'      => $userblocks . '.pn_active',
										   'lastupdate'  => $userblocks . '.pn_lastupdate');

    // Return the table information
    return $pntable;
}

?>