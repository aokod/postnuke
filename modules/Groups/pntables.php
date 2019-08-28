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
// Original Author of file: Mark West
// Purpose of file:  Table information for Groups module
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Groups
 * @license http://www.gnu.org/copyleft/gpl.html
*/

/**
 * This function is called internally by the core whenever the module is
 * loaded.  It adds in the information
 */
function Groups_pntables()
{
    // Initialise table array
    $pntable = array();

    // Set the table prefix
	$prefix = pnConfigGetVar('prefix');

    // Get the name for the template item table.  This is not necessary
    // but helps in the following statements and keeps them readable
    $group_membership = $prefix  . '_group_membership';

    // Set the table name
    $pntable['group_membership'] = $group_membership;

    // Set the column names.  Note that the array has been formatted
    // on-screen to be very easy to read by a user.
    $pntable['group_membership_column'] = array ('gid' => $group_membership . '.pn_gid',
                                                 'uid' => $group_membership . '.pn_uid');

    // Get the name for the template item table.  This is not necessary
    // but helps in the following statements and keeps them readable
    $groups = $prefix . '_groups';

    // Set the table name
    $pntable['groups'] = $groups;

    // Set the column names.  Note that the array has been formatted
    // on-screen to be very easy to read by a user.
    $pntable['groups_column'] = array ('gid'  => $groups . '.pn_gid',
                                       'name' => $groups . '.pn_name');
    // Return the table information
    return $pntable;
}

?>