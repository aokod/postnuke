<?php
// $Id: user.php 16722 2005-08-27 12:35:10Z  $
// Xanthia Theme Engine FOR PostNuke Content Management System
// Copyright (C) 2003 by the CorpNuke.com Development Team.
// Copyright is claimed only on changes to original files
// Modifications by: Larry E. Masters aka. PhpNut 
// nut@phpnut.com
// http://www.coprnuke.com/
// ----------------------------------------------------------------------
// Based on: Encompass Theme Engine - http://madhatt.info/
// Original Author: Brian K. Virgin (MADHATter7)
// ----------------------------------------------------------------------
// Based on:
// eNvolution Content Management System
// Copyright (C) 2002 by the eNvolution Development Team.
// http://www.envolution.com/
// ----------------------------------------------------------------------
// Based on:
// PostNuke Content Management System - www.postnuke.com
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

/**
 * @package     Xanthia_Templating_Environment
 * @subpackage  Xanthia
 * @license http://www.gnu.org/copyleft/gpl.html
*/

// Global Defines

if (!defined('_XA_APILOADFAILED')) {
	define('_XA_APILOADFAILED','Sorry! Failed to open Xanthia API');
}
if (!defined('_XA_ANERROROCCURED')) {
	define('_XA_ANERROROCCURED','Sorry! Xanthia has encountered a fatal error');
}
if (!defined('_XA_NOMODINFO')) {
	define('_XA_NOMODINFO','Sorry! Xanthia module information not found.');
}
if (!defined('_XA_LOADADDONFAIL')) {
	define('_XA_LOADADDONFAIL','Sorry! An add-on for this skin failed to load.');
}

// Zone Related Defines
if (!defined('_XA_COMPASSNOZONES')) {
	define('_XA_COMPASSNOZONES','Sorry! No zones specified in API arguments.');
}
if (!defined('_XA_INZONE')) {
	define('_XA_INZONE','in zone');
}
if (!defined('_XA_MAINZONENOTPL')) {
	define('_XA_MAINZONENOTPL','Sorry! A required wrapper template was either not found or failed to load.');
}
if (!defined('_XA_NOZONEFOUND')) {
	define('_XA_NOZONEFOUND', 'Sorry! Failed to find wrapper :');
}
if (!defined('_XA_THEME')) {
    define('_XA_THEME', 'Theme');
}
if (!defined('_XA_BLOCCO')) {
	define('_XA_BLOCCO','Block');
}
if (!defined('_XA_RIGHT')) {
	define('_XA_RIGHT','Right');
}
if (!defined('_XA_LEFT')) {
	define('_XA_LEFT','Left');
}
if (!defined('_XA_CENTER')) {
	define('_XA_CENTER','Center');
}

if (!defined('_XA_POSITION_TAG')) {
	define('_XA_POSITION_TAG','Block Position: ');
}
if (!defined('_XA_EDITBLOCK')) {
	define('_XA_EDITBLOCK','Edit Block: ');
}
?>