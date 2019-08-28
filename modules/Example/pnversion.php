<?php
// $Id: pnversion.php 15870 2005-02-27 11:01:02Z landseer $
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
 * Example Module
 * 
 * The Example module shows how to make a PostNuke module. 
 * It can be copied over to get a basic file structure.
 *
 * Purpose of file:  Provide version and credit information about the module
 *
 * @package      PostNuke_Miscellaneous_Modules
 * @subpackage   Example
 * @version      $Id: pnversion.php 15870 2005-02-27 11:01:02Z landseer $
 * @author       Jim McDonald
 * @author       Joerg Napp <jnapp@users.sourceforge.net>
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

// The following information is used by the Modules module 
// for display and upgrade purposes
$modversion['name']           = pnVarPrepForDisplay(_EXAMPLE_NAME); //'Example';
// the version string must not exceed 10 characters!
$modversion['version']        = '1.2';
$modversion['description']    = pnVarPrepForDisplay(_EXAMPLE_DESCRIPTION); //'Example for new modules';
$modversion['displayname']    = pnVarPrepForDisplay(_EXAMPLE_DISPLAYNAME); //'Example for new modules';

// The following in formation is used by the credits module
// to display the correct credits
$modversion['changelog']      = 'pndocs/changelog.txt';
$modversion['credits']        = 'pndocs/credits.txt';
$modversion['help']           = 'pndocs/help.txt';
$modversion['license']        = 'pndocs/license.txt';
$modversion['official']       = 1;
$modversion['author']         = 'The PostNuke development team';
$modversion['contact']        = 'http://www.postnuke.com/';

// The following information tells the PostNuke core that this
// module has an admin option.
$modversion['admin']          = 1;

// This one adds the info to the DB, so that users can click on the 
// headings in the permission module
$modversion['securityschema'] = array('Example::' => 'Example item name::Example item ID');

?>