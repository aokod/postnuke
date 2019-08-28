<?php
// File: $Id: user.php 16763 2005-09-20 11:23:04Z jorg $
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

include 'includes/pnAPI.php';
pnInit();

// Get all parameters from input space
list($stop,
     $minage,
     $module,
     $op) = pnVarCleanFromInput('stop',
                                'minage',
                                'module',
                                'op');

// treat all user.php functions as a module for later checks - markwest
define('LOADED_AS_MODULE', '1');

// load languages
if (file_exists($currentlangfile = 'language/' . pnVarPrepForOS(pnUserGetLang()) . '/user.php')) {
    include $currentlangfile;
} elseif (file_exists($defaultlangfile = 'language/' . pnVarPrepForOS(pnConfigGetVar('language')) . '/user.php')) {
    include $defaultlangfile;
}

// set module and op respective to the different cases

if (!pnUserLoggedIn() && empty($op)) {
    $module = 'User';
    $op = 'getlogin';
}

if (isset($op) && ($op == 'userinfo')) {
    $module = 'User';
}

if (pnUserLoggedIn() and (empty($op) or ($op == 'adminMain'))) {
    $module = 'User';
    $op = 'main';
}

// Load tools -- they might be needed in the legacy user plugins
include_once 'modules/User/tools.php';
include_once 'modules/User/password.php';

if (file_exists($file = 'modules/' . pnVarPrepForOS($module) . '/user.php') ||
    file_exists($file = 'modules/' . pnVarPrepForOS(preg_replace('/^NS-/', '', $module)) . '/user.php')) {
    user_menu();
    include $file;
    if (substr($module, 0, 3) == 'NS-') {
        $function = substr($module, 3) . '_user_';
    } else {
        $function = $module . '_user_';
    }
    $function_op = $function . $op;
    $function_main = $function . 'main';
    if (function_exists($function_op)) {
        $function_op($_REQUEST);
        exit;
    } elseif (function_exists($function_main)) {
        $function_main($_REQUEST);
        exit;
    } else {
//        die("error : user_execute($file,$function_op)");
        pnRedirect('index.php');
        return true;
    }
}

// when we are here, the call is the result of an action
// requested by a (legacy) Your_Account plugin.

// Get all user modules...
$usermods = pnModGetUserMods();
// since the your account module does't have index.php/pnuser.php it
// won't be listed as user_capable hence we need to manually add it to
// our result set. [markwest]
$usermods[] = pnModGetInfo(pnModGetIDFromName('Your_Account'));
// ...and run the requested action (specified by $op)
foreach ($usermods as $usermod) {
    if (@is_dir($dir = 'modules/' . $usermod['directory'] . '/user/case/')) {
        $casedir = opendir($dir);
        while ($func = readdir($casedir)) {
            if (eregi('^case.', $func)) {
                $ModName = $usermod['name'];
                include $dir . pnVarPrepForOS($func);
            }
        }
        closedir($casedir);
    }
}
?>