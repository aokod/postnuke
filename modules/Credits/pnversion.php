<?php
// $Id: pnversion.php 16292 2005-06-03 11:04:01Z markwest $
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
// Original Author of file: Rob Brandt
// Purpose of file: Credits administration
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Miscellaneous_Modules
 * @subpackage Credits
 * @license http://www.gnu.org/copyleft/gpl.html
*/

$modversion['name'] = 'PostNuke Credits';
$modversion['version'] = '1.2';
$modversion['description'] = 'Display Module credits, license, help and contact information';
$modversion['credits'] = 'pndocs/credits.txt';
$modversion['help'] = 'pndocs/help.txt';
$modversion['changelog'] = 'pndocs/changelog.txt';
$modversion['license'] = 'pndocs/license.txt';
$modversion['official'] = 1;
$modversion['author'] = 'Rob Brandt';
$modversion['contact'] = 'http://bronto.csd-bes.net';
$modversion['admin'] = 0;
$modversion['securityschema'] = array('Credits::' => '::');

?>