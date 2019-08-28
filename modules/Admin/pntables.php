<?php
// $Id: pntables.php 15318 2005-01-10 13:21:34Z markwest $
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
// Purpose of file:  Table information for admin module
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Admin
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * This function is called internally by the core whenever the module is
 * loaded.  It adds in the information
 * @author Mark West
 * @return array pntables array
 */
function Admin_pntables()
{
    // Initialise table array
    $pntable = array();

    // Get the name for the admin category table.  This is not necessary
    // but helps in the following statements and keeps them readable
    $prefix = pnConfigGetVar('prefix');

    // Set the column names.  Note that the array has been formatted
    // on-screen to be very easy to read by a user.
	$admin_category = $prefix . '_admin_category';
    $pntable['admin_category'] = $admin_category;
    $pntable['admin_category_column'] = array('cid'    => $admin_category . '.pn_cid',
                                              'catname'   => $admin_category . '.pn_name',
                                              'description' => $admin_category . '.pn_description');

    // Set the column names.  Note that the array has been formatted
    // on-screen to be very easy to read by a user.
	$admin_category = $prefix . '_admin_module';
    $pntable['admin_module'] = $admin_category;
    $pntable['admin_module_column'] = array('mid'    => $admin_category . '.pn_mid',
                                            'cid'   => $admin_category . '.pn_cid');

    // Return the table information
    return $pntable;
}

?>