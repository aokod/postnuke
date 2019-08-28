<?php
// File: $Id: pnversion.php 15630 2005-02-04 06:35:42Z jorg $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2003 by the PostNuke Development Team.
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
// Original Author of this file: Jim McDonald
// Purpose of this file: version define of the module
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Permissions
 * @license http://www.gnu.org/copyleft/gpl.html
*/

$modversion['name'] = 'Permissions Adminstration';
$modversion['version'] = '0.4';
$modversion['description'] = 'Modify permissions security';
$modversion['credits'] = 'pndocs/credits.txt';
$modversion['help'] = 'pndocs/install.txt';
$modversion['changelog'] = 'pndocs/changelog.txt';
$modversion['license'] = 'pndocs/license.txt';
$modversion['official'] = 1;
$modversion['author'] = 'Jim McDonald, M.Maes';
$modversion['contact'] = 'http://www.mcdee.net/, http://www.mmaes.com';
$modversion['admin'] = 1;
$modversion['securityschema'] = array('Permissions::' => '::');
?>