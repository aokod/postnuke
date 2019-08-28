<?php
// $Id: pninit.php 16760 2005-09-13 11:09:21Z markwest $
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
// Original Author of file: Simon Wudnerlin
 // Purpose of file:  Initialisation functions for Settings
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Settings
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * initialise the settings module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * @author Patrick Kellum
 * @author Simon Wunderlin
 * @version $Revision: 16760 $
 * @return bool true if successful, false otherwise
 */
function settings_init()
{
	// Initialisation successful
	return true;
}

/**
 * upgrade the settings module from an old version
 * This function can be called multiple times
 * @author Patrick Kellum
 * @author Simon Wunderlin
 * @version $Revision: 16760 $
 * @param int $oldversion version to upgrade from
 * @return bool true if successful, false otherwise
 */
function settings_upgrade($oldversion)
{
	// always ensure that the version infon is upgraded
	pnConfigSetVar('Version_Num', _PN_VERSION_NUM);
	pnConfigSetVar('Version_ID', _PN_VERSION_ID);
	pnConfigSetVar('Version_Sub', _PN_VERSION_SUB);

    // Upgrade dependent on old version number
    switch($oldversion) {
        case 1.2:
        	pnConfigSetVar('anonymoussessions', true);
        	pnConfigDelVar('locale');
            return settings_upgrade(1.3);
		case 1.3:
			return settings_upgrade(1.4);
    }

    // Update successful
    return true;
}

/**
 * delete the settings module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * @author Patrick Kellum
 * @author Simon Wunderlin
 * @version $Revision: 16760 $
 * @return bool true if successful, false otherwise
 */
function settings_delete()
{
	// Deletion successful
	return true;
}

?>