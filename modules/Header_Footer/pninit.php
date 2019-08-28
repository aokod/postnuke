<?php
// $Id: pninit.php 13213 2004-04-22 12:28:05Z drak $
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
// Purpose of file:  Initialisation functions for Header and Footer Module
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Header_Footer
 * @license http://www.gnu.org/copyleft/gpl.html
*/

/**
 * initialise the Header module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 */
function Header_Footer_init()
{
    // Initialisation successful
    return true;
}

/**
 * upgrade the Header module from an old version
 * This function can be called multiple times
 */
function Header_Footer_upgrade($oldversion)
{
    // Update successful
    return true;
}

/**
 * delete the Header module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 */
function Header_Footer_delete()
{
    // Deletion successful
    return true;
}

?>