<?php
// $Id: pnversion.php 13719 2004-06-02 16:21:28Z markwest $
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
// Purpose of file:  RSS version information
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Content_Modules
 * @subpackage RSS
 * @license http://www.gnu.org/copyleft/gpl.html
*/

$modversion['name'] = 'RSS';
$modversion['version'] = '1.0';
$modversion['description'] = 'RSS News Feed Reader';
$modversion['credits'] = 'pndocs/credits.txt';
$modversion['help'] = 'pndocs/help.txt';
$modversion['changelog'] = 'pndocs/changelog.txt';
$modversion['license'] = 'pndocs/license.txt';
$modversion['official'] = 1;
$modversion['author'] = 'Mark West';
$modversion['contact'] = 'http://www.markwest.me.uk/';
$modversion['admin'] = 1;
$modversion['securityschema'] = array('RSS::Item' => 'RSS item name::RSS item ID');

?>