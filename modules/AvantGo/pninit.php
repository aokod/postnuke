<?php
// $Id: pninit.php 15321 2005-01-10 14:28:40Z markwest $
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
// Purpose of file:  Initialisation functions for AvantGo
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Content_Modules
 * @subpackage AvantGo
 * @license http://www.gnu.org/copyleft/gpl.html
*/

/**
 * initialise the AvantGo module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * @author Mark West
 * @link http://www.markwest.me.uk
 * @return bool true if initalisation successful, false otherwise
 */
function AvantGo_init()
{

    // Set up an initial value for a module variable.  Note that all module
    // variables should be initialised with some value in this way rather
    // than just left blank, this helps the user-side code and means that
    // there doesn't need to be a check to see if the variable is set in
    // the rest of the code as it always will be
    pnModSetVar('AvantGo', 'itemsperpage', 10);

    // Initialisation successful
    return true;
}

/**
 * upgrade the AvantGo module from an old version
 * This function can be called multiple times
 * @author Mark West
 * @link http://www.markwest.me.uk
 * @param 'oldversion' the previous version number of the module
 * @return bool true if upgrade successful, false otherwise
 */
function AvantGo_upgrade($oldversion)
{
    // Upgrade dependent on old version number
    switch($oldversion) {
        case 1.3:
            // Code to upgrade from version 1.3 goes here
            pnModSetVar('AvantGo', 'itemsperpage', 10);
            break;
    }

    // Update successful
    return true;
}

/**
 * Delete the AvantGo module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * @author Mark West
 * @link http://www.markwest.me.uk
 * @return bool true if deletion successful, false otherwise
 */
function AvantGo_delete()
{
    // Delete any module variables
    pnModDelVar('AvantGo', 'itemsperpage');

    // Deletion successful
    return true;
}

?>