<?php
// File: $Id: pnuserapi.php 15630 2005-02-04 06:35:42Z jorg $
// ----------------------------------------------------------------------
// POST-NUKE Content Management System
// Copyright (C) 2001 by the Post-Nuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// Based on:
// PHP-NUKE Web Portal System - http://phpnuke.org/
// Thatware - http://thatware.org/
// ----------------------------------------------------------------------
// LICENSE

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Mark West
// Purpose of file: Sniffer administration
// ----------------------------------------------------------------------

/**
 * Sniffer Module
 *
 * @package      PostNuke_Utility_Modules
 * @subpackage   Sniffer
 * @version      $Id: pnuserapi.php 15630 2005-02-04 06:35:42Z jorg $
 * @author       Mark West
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

/**
 * This function is called directly during installation
 * and is used in the event handler function below
 *
 * @access private
 * @return array of user agent id and client
 */
function sniffer_userapi_sniff($arg)
{
	// include the class
	// Note: When restoring a class from a session variable
	// the class needs to be defined to work properly so we
	// must include the class definition 
	if (file_exists('system/Sniffer/pnincludes/phpSniff.class.php')) {
		include_once('system/Sniffer/pnincludes/phpSniff.class.php');
	} else if (file_exists('modules/Sniffer/pnincludes/phpSniff.class.php')) {
		include_once('modules/Sniffer/pnincludes/phpSniff.class.php');
	} else {
		return false;
	}

	// check we've already worked out the browser info
	$browserinfo = pnSessionGetVar('browserinfo');
	if (is_string($browserinfo) && is_object(unserialize($browserinfo))) {
		return true;
	} else {
		// sniff process
		$client =& new phpSniff();
		pnSessionSetVar('browserinfo', serialize($client));
		return true;
	}
	return false;
}

/**
 * Get the full browser info object
 *
 * In general the individual API's should be called to determine the 
 * browsers functionality. This API is provided for convenience only
 *
 * @return object phpsniff object
 * @access private
 */
function sniffer_userapi_get()
{
	$result = false;
	$browserinfo = pnSessionGetVar('browserinfo');
	if (is_string($browserinfo) && is_object($info = unserialize($browserinfo))) {
		$result = $info;
	} else {
		$success = pnModAPIFunc('Sniffer', 'user', 'sniff');
		if ($success) {
			$browserinfo = pnSessionGetVar('browserinfo');
			if (is_string($browserinfo) && is_object($info = unserialize($browserinfo))) {
				$result = $info;
			}
		}
	}		
	return $result;
}

/**
 * Get the value of a defined property of the browser
 *
 * This api returns the value of a property of the phpsniff
 * object
 *
 * @access public
 * @param $args['property_name'] name of the property
 * @returns string property value
 */
function sniffer_userapi_property($args)
{
	extract($args);

	if (!isset($property_name)) {
		return;
	}

	$result = pnModAPIFunc('Sniffer', 'user', 'get');

	return $result->property($property_name);
}

/**
 * decide if the browser has a particular feature
 *
 * This api returns wether a browser has a particular
 * feature or not
 *
 * @access public
 * @param $args['feature'] name of the feature
 * @returns bool true if feature is available false otherwise
 */
function sniffer_userapi_has_feature($args)
{
	extract($args);

	if (!isset($feature)) {
		return;
	}

	$result = pnModAPIFunc('Sniffer', 'user', 'get');

	return $result->has_feature($feature);
}

/**
 * decide if the browser has a particular quirk
 *
 * This api returns wether a browser has a particular
 * quirk or not
 *
 * @access public
 * @param $args['quirk'] name of the quirk
 * @returns bool true if quirk is available false otherwise
 */
function sniffer_userapi_has_quirk($args)
{
	extract($args);

	if (!isset($quirk)) {
		return;
	}

	$result = pnModAPIFunc('Sniffer', 'user', 'get');

	return $result->has_quirk($quirk);
}

?>