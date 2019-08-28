<?php
// File: $Id: admin.php 17588 2006-01-13 19:16:15Z jorg $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
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
// Original Author of file: Francisco Burzi
// Purpose of file:
// ----------------------------------------------------------------------

// include base api
include 'includes/pnAPI.php';

// start PN
pnInit();

// Get module
$module = pnVarCleanFromInput('module');

if (empty($module)) {
    // call for admin.php without module parameter
    pnRedirect(pnModURL('Admin', 'admin','adminpanel'));
    exit;
} else if (!pnModAvailable($module) || !pnSecAuthAction(0, "$module::", '::', ACCESS_EDIT)) {
    // call for an unavailable module - either not available or not authorized
    header('HTTP/1.0 403 Access Denied');
    include ('header.php');
    echo 'Module <strong>' . pnVarPrepForDisplay($module) . '</strong> not available';
    include ('footer.php');
    exit;
}

// get the module information
$modinfo = pnModGetInfo(pnModGetIDFromName($module));

if ($modinfo['type'] == 2 || $modinfo['type'] == 3) {
    // Redirect to new style admin panel
    pnRedirect(pnModURL($module, 'admin'));
    exit;
}


if (!file_exists($adminfile='modules/' . pnVarPrepForOS($modinfo['directory']) . '/admin.php')) {
    // Module claims to be old-style, but no admin.php present - quit here
    header('HTTP/1.0 404 Not Found');
    include ('header.php');
    echo 'Wrong call for Adminfunction in Module <strong>' . pnVarPrepForDisplay($module) . '</strong>';
    include ('footer.php');
    exit;
}


/**
 * old style module administration
 */

list($func,
     $op,
     $name,
     $file,
     $type) = pnVarCleanFromInput('func',
                                  'op',
                                  'name',
                                  'file',
                                  'type');

// load the legacy includes
include_once('modules/Admin/pnlegacy/tools.php'); 

// set a constant so we can check the correct entry point later
define('LOADED_AS_MODULE', '1');

$ModName = $module;
include $adminfile;
modules_get_manual();

if (substr($module, 0, 3) == 'NS-') {
    $function = substr($module, 3) . '_admin_';
} else {
    $function = $module . '_admin_';
} 
if (empty($op)) {
    $op = 'main';
} 
$function_op = $function . $op;
$function_main = $function . 'main';

if (function_exists($function_op)) {
    $function_op($_REQUEST);
} elseif (function_exists($function_main)) {
    $function_main($_REQUEST);
} else {
    // neither function_admin_op nor function_admin_main are available
    header('HTTP/1.0 404 Not Found');
    include ('header.php');
    echo 'Adminfunction <strong>' . pnVarPrepForDisplay($function_op) . '</strong> in Module <strong>' . pnVarPrepForDisplay($module) . '</strong> not available';
    include ('footer.php');
    exit;
} 
?>