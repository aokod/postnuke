<?php
// File: $Id: index.php 17645 2006-01-17 19:41:14Z hammerhead $
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
// Original Author of this file: Francisco Burzi
// Purpose of this file: Directs to the start page as defined in config.php
// ----------------------------------------------------------------------

// include base api
include 'includes/pnAPI.php';

// start PN
pnInit();

// Get variables
// Note the op parameter is re-added here for gallery embedding
// this should be removed once gallery has been updated for better
// detection of postnuke - assuming this parameter exists is
// far from the best solution - markwest
list($module,
     $func,
     $name,
     $file,
     $type,
     $op) = pnVarCleanFromInput('module',
                                'func',
                                'name',
                                'file',
                                'type',
                                'op');

// check requested module and set to start module if not present
if (empty($name) && empty($module)) {
    $module = pnConfigGetVar('startpage');
    $type = pnConfigGetVar('starttype');
    $func = pnConfigGetVar('startfunc');
    $funcargs = explode(',', pnConfigGetVar('startargs'));
    $arguments = array();
    foreach ($funcargs as $funcarg) {
        if (!empty($funcarg)) {
            $argument=explode('=', $funcarg);
            $arguments[$argument[0]] = $argument[1];
        }
    }
} elseif (empty($module) && !empty($name)) {
    $module = $name;
}

// get module information
$modinfo = pnModGetInfo(pnModGetIDFromName($module));

if ($type<>'init' && !pnModAvailable($modinfo['name'])) {
    header('HTTP/1.0 404 Not Found');
    include ('header.php');
    echo 'Module <strong>' . pnVarPrepForDisplay($module) . '</strong> not available';
    include ('footer.php');
    exit;
}

if ($modinfo['type'] == 2 || $modinfo['type'] == 3)
{
    // New-new style of loading modules
    if (empty($type)) {
        $type = 'user';
    }
    if (empty($func)) {
        $func = 'main';
    }
    if (!isset($arguments)) {
        $arguments = array();
    }

    // temporary additional security check.....
    if ($type == 'admin' && $func == 'updateconfig' && !pnSecAuthAction(0, "$modinfo[name]::", '::', ACCESS_ADMIN)) {
        header('HTTP/1.0 403 Access Denied');
        include ('header.php');
        echo _MODULENOAUTH;
        include ('footer.php');
        exit;
    }

    // we need to force the mod load if we want to call a modules interactive init
    // function because the modules is not active right now
    $force_modload = ($type=='init') ? true : false;
    if (pnModLoad($modinfo['name'], $type, $force_modload)) {
        // Run the function
        $return = pnModFunc($modinfo['name'], $type, $func, $arguments);
    } else {
        $return = false;
    }
    // Sort out return of function.  Can be
    // true - finished
    // false - display error msg
    // text - return information
    if ($return !== true) {
        include_once('header.php');
        if ($return === false) {
            // Failed to load the module
            header('HTTP/1.0 404 Not Found');
            echo 'Failed to load module <strong>' . pnVarPrepForDisplay($module) .'</strong> (at function: "<strong>'. pnVarPrepForDisplay($func).'</strong>")';
        } elseif (is_string($return) && strlen($return) > 0) {
            // Text
            echo $return;
        } elseif (is_array($return)) {
            $pnRender =& new pnRender($modinfo['name']);
            $pnRender->assign($return);
            if (isset($return['template'])) {
                echo $pnRender->fetch($return['template']);
            } else {
                $modname = strtolower($modinfo['name']);
                $type = strtolower($type);
                $func = strtolower($func);
                echo $pnRender->fetch("{$modname}_{$type}_{$func}.htm");
            }
        } else {
            echo 'Function <em>' . pnVarPrepForDisplay($func) . '</em> in module <em>' . pnVarPrepForDisplay($module) .'</em> returned.';
        }
        include_once('footer.php');
    }
} else {
    // Old-old style of loading modules
    if (empty($file)) {
        $file = 'index';
    }
    define('LOADED_AS_MODULE', '1');
    if (file_exists('modules/' . pnVarPrepForOS($modinfo['directory']) . '/' . pnVarPrepForOS($file) . '.php')) {
        include 'modules/' . pnVarPrepForOS($modinfo['directory']) . '/' . pnVarPrepForOS($file) . '.php';
    } else {
        // Failed to load the module
        header('HTTP/1.0 404 Not Found');
        include ('header.php');
        echo 'Failed to load module <strong>' . pnVarPrepForDisplay($modinfo['name']) . '</strong>';
        include ('footer.php');
    }
}

?>