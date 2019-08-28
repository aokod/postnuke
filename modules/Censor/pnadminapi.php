<?php
// $Id: pnadminapi.php 16512 2005-07-25 17:36:22Z markwest $
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
// Purpose of file:  Censor administration API functions
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Censor
 * @license http://www.gnu.org/copyleft/gpl.html
*/

/**
 * Update Censor config
 * @author Brian Bain
 * @param int $args['censormode'] censor on or off
 * @param string $args['censorlist'] list of censored words
 * @param censorreplace $args['string'] to replace censored word with
 * @return bool true if succesful, false otherwise
 */
function Censor_adminapi_update($args)
{
    extract($args);
    if (!pnSecAuthAction(0, 'Censor::', "::", ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

	// check for empty elements in our censor list array
	$censoredwords = array();
	foreach ($censorlist as $censoritem) {
		if (!empty($censoritem)) {
			$censoredwords[] = $censoritem;
		}
	}

    if(!pnConfigSetVar('CensorMode', $censormode)){
        pnSessionSetVar('errormsg', _CENSORMODEFAIL);
        return false;
    }


    if(!pnConfigSetVar('CensorList', $censoredwords)){
        pnSessionSetVar('errormsg', _CENSORLISTFAIL);
        return false;
    }

    if(!pnConfigSetVar('CensorReplace', $censorreplace)){
        pnSessionSetVar('errormsg', _CENSORREPLACEFAIL);
        return false;
    }
    
    return true;
}

?>