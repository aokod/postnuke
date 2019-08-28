<?php
// $Id: pnversion.php 14934 2004-11-21 20:04:34Z markwest $
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
// Original Author of file: Xiaoyu Huang
// Purpose of file:  version of legal module
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage legal
 * @license http://www.gnu.org/copyleft/gpl.html
*/

$modversion['name'] = 'legal';
$modversion['version'] = '1.2';
$modversion['description'] = 'Generic Privacy Statement and Terms of Use';
$modversion['credits'] = 'pndocs/credits.txt';
$modversion['help'] = 'pndocs/install.txt';
$modversion['changelog'] = 'pndocs/changelog.txt';
$modversion['license'] = 'pndocs/license.txt';
$modversion['official'] = 1;
$modversion['author'] = 'Michael M. Wechsler';
$modversion['contact'] = 'michael@thelaw.com';
$modversion['admin'] = 0;
$modversion['securityschema'] = array('legal::' => '::');
?>