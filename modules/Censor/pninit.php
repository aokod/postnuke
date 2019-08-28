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
// Original Author of file: Brian Bain
// Purpose of file:  Censor initialisation functions
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Censor
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * Initialise censor module
 * @author Brian Bain
 * @return bool true if succesful, false otherwise
 */
function Censor_init() 
{

    // Set up module hooks
    if (!pnModRegisterHook('item',
                           'transform',
                           'API',
                           'Censor',
                           'user',
                           'transform')) {
        pnSessionSetVar('errormsg', _CENSOR_COULDNOTREGISTER);
        return false;
    }

	pnModSetVar('/PNConfig', 'CensorList', _CENSORINITWORDS);
	pnConfigSetVar('CensorMode', _CENSORINITENABLED);
	pnConfigSetVar('CensorReplace', _CENSORINITREPLACE);

    return true;
}

/**
 * Upgrade censor module
 * @author Brian Bain
 * @param int $oldversion version to upgrade from
 * @return bool true if succesful, false otherwise
 */
function Censor_upgrade($oldversion)
{
    // Upgrade dependent on old version number
    switch($oldversion) {
        case 1.0:
            // Code to upgrade from version 1.0 goes here
			// v1.0 didn't have the transform hook
            // Set up module hooks
            if (!pnModRegisterHook('item',
                                   'transform',
                                   'API',
                                   'Censor',
                                   'user',
                                   'transform')) {
                pnSessionSetVar('errormsg', _REGISTERFAILED);
                return false;
            }
            break;
    }

    // Update successful
    return true;

}

/**
 * Delete censor module
 * @author Brian Bain
 * @return bool true if succesful, false otherwise
 */
function Censor_delete()
{
    // Remove module hooks
    if (!pnModUnregisterHook('item',
                             'transform',
                             'API',
                             'Censor',
                             'user',
                             'transform')) {
        pnSessionSetVar('errormsg', _UNREGISTERFAILED);
        return false;
    }

    return true;
}

?>