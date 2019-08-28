<?php
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
 * @package PostNuke_Utility_Modules
 * @subpackage typetool
 * @license http://www.gnu.org/copyleft/gpl.html
*/

 
/**
 * initialise the TypeTool module
 * 
 * This function is only ever called once during the lifetime of a particular
 * module instance.
 * This function MUST exist in the pninit file for a module
 * 
 * @author       Andreas Krapohl
 * @version      
 * @return       bool       true on success, false otherwise
 */
function typetool_init()
{
    // Set up an initial value for a module variable.  Note that all module
    // variables should be initialised with some value in this way rather
    // than just left blank, this helps the user-side code and means that
    // there doesn't need to be a check to see if the variable is set in
    // the rest of the code as it always will be
    pnModSetVar('typetool', 'enable', 0);
    pnModSetVar('typetool', 'language', 'language.js');

    // Initialisation successful
    return true;
}


/**
 * upgrade the TypeTool module from an old version
 * 
 * This function can be called multiple times
 * This function MUST exist in the pninit file for a module
 * 
 * @author       Andreas Krapohl
 * @version      
 * @return       bool       true on success, false otherwise
 */
function typetool_upgrade($oldversion)
{
    // Update successful
    return true;
}


/**
 * delete the TypeTool module
 * 
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * This function MUST exist in the pninit file for a module
 * 
 * @author       Andreas Krapohl
 * @version      
 * @return       bool       true on success, false otherwise
 */
function typetool_delete()
{
    // Delete any module variables
    pnModDelVar('typetool', 'enable');
    pnModDelVar('typetool', 'language');

    // Deletion successful
    return true;
}

?>