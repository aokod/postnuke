<?php
// $Id: pnversion.php 13853 2004-06-30 14:27:27Z markwest $
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
 * pnRender
 * 
 * PostNuke wrapper class for Smarty
 * 
 * @author      PostNuke development team 
 * @version     .7/.8
 * @link        http://www.post-nuke.net              PostNuke home page
 * @link        http://smarty.php.net                 Smarty home page
 * @license     http://www.gnu.org/copyleft/gpl.html  GNU General Public License
 * @package     PostNuke_System_Modules
 * @subpackage  pnRender
 */

// Information for the modules admin
$modversion['name']        = 'pnRender';
$modversion['version']     = '1.0';
$modversion['description'] = 'The Smarty implementation for PostNuke';

// Tell the core if the module is admin- or user-capable
$modversion['admin']       = 1;
$modversion['user']        = 0;

// Information for the credits module
$modversion['changelog']   = 'pndocs/changelog.txt';
$modversion['credits']     = 'pndocs/credits.txt';
$modversion['help']        = 'pndocs/help.txt';
$modversion['license']     = 'pndocs/license.txt';
$modversion['official']    = 1;
$modversion['author']      = 'The PostNuke development team';
$modversion['contact']     = 'http://www.postnuke.com/';

// This one adds the info to the DB, so that users can click on the 
// headings in the permission module
$modversion['securityschema'] = array('pnRender::' => '::');
?>