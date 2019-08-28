<?php
// $Id: pnversion.php 14663 2004-09-29 13:49:42Z markwest $
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
/**
 * Sniffer Module
 * 
 * Purpose of file:  Provide browser information to module developers
 *
 * @package      PostNuke_Utility_Modules
 * @subpackage   Sniffer
 * @version      $Id: pnversion.php 14663 2004-09-29 13:49:42Z markwest $
 * @author       Mark West
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

// The following information is used by the Modules module 
// for display and upgrade purposes
$modversion['name']           = 'Sniffer';
// the version string must not exceed 10 characters!
$modversion['version']        = '1.1';
$modversion['description']    = 'Browser detection and information';

// The following in formation is used by the credits module
// to display the correct credits
$modversion['changelog']      = 'pndocs/CHANGES';
$modversion['credits']        = 'pndocs/credits.txt';
$modversion['help']           = '';
$modversion['license']        = 'pndocs/LICENSE';
$modversion['official']       = 1;
$modversion['author']         = 'Mark West';
$modversion['contact']        = 'http://www.postnuke.com/';

// The following information tells the PostNuke core that this
// module has an admin option.
$modversion['admin']          = 1;

// This one adds the info to the DB, so that users can click on the 
// headings in the permission module
$modversion['securityschema'] = array('Sniffer::' => '::');

?>