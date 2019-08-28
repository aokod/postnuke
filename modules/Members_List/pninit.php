<?php
// $Id: pninit.php 15324 2005-01-10 15:11:12Z markwest $
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
// Purpose of file:  Initialisation functions for Members_List
// ----------------------------------------------------------------------

/**
 * @package PostNuke_ResourcePack_Modules
 * @subpackage Members_List
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * initialise the Members_List module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * @author Mark West
 * @return bool true if successful, false otherwise
 */
function Members_List_init()
{
    // Set up an initial value for a module variable.  Note that all module
    // variables should be initialised with some value in this way rather
    // than just left blank, this helps the user-side code and means that
    // there doesn't need to be a check to see if the variable is set in
    // the rest of the code as it always will be
    pnModSetVar('Members_List', 'memberslistitemsperpage', 20);
    pnModSetVar('Members_List', 'onlinemembersitemsperpage', 20);

    // Initialisation successful
    return true;
}

/**
 * upgrade the Members_List module from an old version
 * This function can be called multiple times
 * @author Mark West
 * @return bool true if successful, false otherwise
 */
function Members_List_upgrade($oldversion)
{
    // Upgrade dependent on old version number
    switch($oldversion) {
        case 1.1:
			pnModSetVar('Members_List', 'memberslistitemsperpage', 20);
			pnModSetVar('Members_List', 'onlinemembersitemsperpage', 20);
			pnModSetVar('Members_List', 'filterunverified', 1);
            // Version 1.1 didn't the module variables
			break;

    }

    // Update successful
    return true;
}

/**
 * delete the Members_List module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * @author Mark West
 * @return bool true if successful, false otherwise
 */
function Members_List_delete()
{
    // Delete any module variables
    pnModDelVar('Members_List', 'memberslistitemsperpage');
    pnModDelVar('Members_List', 'onlinemembersitemsperpage');

    // Deletion successful
    return true;
}

?>