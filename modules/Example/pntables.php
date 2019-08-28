<?php
// $Id: pntables.php 14662 2004-09-29 13:19:15Z markwest $
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
 * Example Module
 *
 * The Example module shows how to make a PostNuke module.
 * It can be copied over to get a basic file structure.
 *
 * Purpose of file:  Table information for Example module --
 *                   This file contains all information on database
 *                   tables for the module
 *
 * @package      PostNuke_Miscellaneous_Modules
 * @subpackage   Example
 * @version      $Id: pntables.php 14662 2004-09-29 13:19:15Z markwest $
 * @author       Jim McDonald
 * @author       Joerg Napp <jnapp@users.sourceforge.net>
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */


/**
 * Populate pntables array for Example module
 *
 * This function is called internally by the core whenever the module is
 * loaded. It delivers the table information to the core.
 * It can be loaded explicitly using the pnModDBInfoLoad() API function.
 *
 * @author       Jim McDonald
 * @version      $Revision: 14662 $
 * @return       array       The table information.
 */
function Example_pntables()
{
    // Initialise table array
    $pntable = array();

    // Get the name for the Example item table.  This is not necessary
    // but helps in the following statements and keeps them readable
    $Example = pnConfigGetVar('prefix') . '_example';

    // Set the table name
    $pntable['example'] = $Example;

    // Set the column names.  Note that the array has been formatted
    // on-screen to be very easy to read by a user.
    $pntable['example_column'] = array('tid'      => 'pn_tid',
                                       'itemname' => 'pn_name',
                                       'number'   => 'pn_number');

    // Return the table information
    return $pntable;
}

?>