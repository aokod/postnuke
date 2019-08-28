<?php
// $Id: pninit.php 15328 2005-01-10 15:54:09Z markwest $
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
 * @version      $Id: pninit.php 15328 2005-01-10 15:54:09Z markwest $
 * @author       Mark West
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */


/**
 * initialise the Sniffer module
 *
 * This function is only ever called once during the lifetime of a particular
 * module instance.
 * This function MUST exist in the pninit file for a module
 *
 * @author       Jim McDonald
 * @return       bool       true on success, false otherwise
 */
function Sniffer_init()
{
    // Initialisation successful
    return true;
}

/**
 * upgrade the Example module from an old version
 *
 * This function can be called multiple times
 * This function MUST exist in the pninit file for a module
 *
 * @author       Jim McDonald
 * @return       bool       true on success, false otherwise
 */
function Sniffer_upgrade($oldversion)
{
    // Update successful
    return true;
}


/**
 * delete the Example module
 *
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * This function MUST exist in the pninit file for a module
 *
 * @author       Jim McDonald
 * @return       bool       true on success, false otherwise
 */
function Sniffer_delete()
{
    // Deletion successful
    return true;
}

?>