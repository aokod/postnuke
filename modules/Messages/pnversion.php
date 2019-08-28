<?php
// $Id: pnversion.php 15728 2005-02-18 10:48:44Z landseer $
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
 * Messages Module
 * 
 * Purpose of file:  Provide version and credit information about the module
 *
 * @package      PostNuke_Miscellaneous_Modules
 * @subpackage   Messages
 * @version      $Id: pnversion.php 15728 2005-02-18 10:48:44Z landseer $
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
$modversion['name'] = 'Messages';
$modversion['version'] = '1.0';
$modversion['description'] = 'Private messaging system for your site';
$modversion['official'] = 1;
$modversion['author'] = 'Richard Tirtadji';
$modversion['contact'] = 'rtirtadji@hotmail.com';
$modversion['admin'] = 0;
$modversion['securityschema'] = array('Messages::' => '.*');

?>