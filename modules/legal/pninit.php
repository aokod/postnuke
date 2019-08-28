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
// Purpose of file:  Initialisation functions for Legal
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage legal
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * initialise the template module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * @author Mark West
 * @return bool true if successful, false otherwise
 */
function Legal_init()
{
	pnModSetVar('legal', 'termsofuse', true);
	pnModSetVar('legal', 'privacypolicy', true);
	pnModSetVar('legal', 'accessibilitystatement', true);

	// Initialisation successful
    return true;
}

/**
 * upgrade the template module from an old version
 * This function can be called multiple times
 * @author Mark West
 * @param int $oldversion version to upgrade from
 * @return bool true if successful, false otherwise
 */
function Legal_upgrade($oldversion)
{
    // Upgrade dependent on old version number
    switch($oldversion) {
        case 1.1:
			pnModSetVar('legal', 'termsofuse', true);
			pnModSetVar('legal', 'privacypolicy', true);
			pnModSetVar('legal', 'accessibilitystatement', true);
        	return Legal_upgrade(1.2);
            break;
    }

    // Update successful
    return true;
}

/**
 * delete the Legal module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * @author Mark West
 * @return bool true if successful, false otherwise
 */
function Legal_delete()
{
	pnModDelVar('legal', 'termsofuse');
	pnModDelVar('legal', 'privacypolicy');
	pnModDelVar('legal', 'accessibilitystatement');

    // Deletion successful
    return true;
}

?>