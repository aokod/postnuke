<?php
// $Id: pntables.php 13213 2004-04-22 12:28:05Z drak $
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
 * Permissions Module
 * 
 * Purpose of file:  Table information for Permissions module --
 *                   This file contains all information on database 
 *                   tables for the module
 *
 * @package      PostNuke_System_Modules
 * @subpackage   Permissions
 * @version      $Id: pntables.php 13213 2004-04-22 12:28:05Z drak $
 * @author       Mark West
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

 
/**
 * Populate pntables array for Permissions module
 * 
 * This function is called internally by the core whenever the module is
 * loaded. It delivers the table information to the core.
 * It can be loaded explicitly using the pnModDBInfoLoad() API function.
 * 
 * @author       Mark West
 * @version      $Revision: 13213 $
 * @return       array       The table information.
 */
function Permissions_pntables()
{
    // Initialise table array
    $pntable = array();

    // Get the name for the Permissions item table.  This is not necessary
    // but helps in the following statements and keeps them readable
    $group_perms = pnConfigGetVar('prefix') . '_group_perms';

    // Set the table name
	$pntable['group_perms'] = $group_perms;

    // Set the column names.  Note that the array has been formatted
    // on-screen to be very easy to read by a user.
	$pntable['group_perms_column'] = array ('pid'       => $group_perms . '.pn_pid',
											'gid'       => $group_perms . '.pn_gid',
											'sequence'  => $group_perms . '.pn_sequence',
											'realm'     => $group_perms . '.pn_realm',
											'component' => $group_perms . '.pn_component',
											'instance'  => $group_perms . '.pn_instance',
											'level'     => $group_perms . '.pn_level',
											'bond'      => $group_perms . '.pn_bond');

    // Get the name for the Permissions item table.  This is not necessary
    // but helps in the following statements and keeps them readable
	$realms = pnConfigGetVar('prefix') . '_realms';

    // Set the table name
	$pntable['realms'] = $realms;

    // Set the column names.  Note that the array has been formatted
    // on-screen to be very easy to read by a user.
	$pntable['realms_column'] = array ('rid'  => $realms . '.pn_rid',
									   'name' => $realms . '.pn_name');


    // Get the name for the Permissions item table.  This is not necessary
    // but helps in the following statements and keeps them readable
	$user_perms = pnConfigGetVar('prefix') . '_user_perms';

    // Set the table name
	$pntable['user_perms'] = $user_perms;

    // Set the column names.  Note that the array has been formatted
    // on-screen to be very easy to read by a user.
	$pntable['user_perms_column'] = array ('pid'       => $user_perms . '.pn_pid',
										   'uid'       => $user_perms . '.pn_uid',
										   'sequence'  => $user_perms . '.pn_sequence',
										   'realm'     => $user_perms . '.pn_realm',
										   'component' => $user_perms . '.pn_component',
										   'instance'  => $user_perms . '.pn_instance',
										   'level'     => $user_perms . '.pn_level',
										   'bond'      => $user_perms . '.pn_bond');

    // Return the table information
    return $pntable;
}

?>