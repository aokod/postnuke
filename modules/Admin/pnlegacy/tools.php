<?php 
// $Id: tools.php 15465 2005-01-24 21:00:05Z markwest $
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
// Purpose of file:  Provide support for admin calls from non API 
// compliant modules
// ----------------------------------------------------------------------

/**
 * display the header for the admin panel of non API compliant modules 
 *
 * A lot simple that the object orientated menu system used before....
 *
 * @author Mark West
 * @deprecated
 */
function GraphicAdmin()
{
    global $additional_header;
    $stylesheet = pnModGetVar('admin', 'modulestylesheet');
	$additional_header[] = "<link rel=\"stylesheet\" href=\"modules/Admin/pnstyle/$stylesheet\" type=\"text/css\">";
    echo pnModFunc('Admin', 'admin', 'categorymenu');
}

?>