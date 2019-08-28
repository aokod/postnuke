<?php
// $Id: modifier.pndate_format.php 16344 2005-06-26 20:48:16Z landseer $
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
 * pnRender plugin
 *
 * This file is a plugin for pnRender, the PostNuke implementation of Smarty
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   pnRender
 * @version      $Id: modifier.pndate_format.php 16344 2005-06-26 20:48:16Z landseer $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

/**
 * Include the {@link shared.make_timestamp.php} plugin
 */
require_once $smarty->_get_plugin_filepath('shared','make_timestamp');

/**
 * Smarty modifier to format datestamps via strftime according to
 * locale setting in PostNuke
 *
 * @author   Frank Schummertz
 * @author   Steffen Voss
 * @since    15. Jan. 2004
 * @param    string   $string         input date string
 * @param    string   format          strftime format for output
 * @param    string   $default_date   default date if $string is empty
 * @return   string   the modified output
 * @uses     smarty_make_timestamp()
 */
function smarty_modifier_pndate_format($string, $format='datebrief', $default_date=null)
{
    if(empty($format)) {
        $format = 'datebrief';
    }
	switch(trim(strtolower($format))){
		case 'datelong':
			$format = _DATELONG;
			break;
		case 'datebrief':
			$format = _DATEBRIEF;
			break;
		case 'datestring':
			$format = _DATESTRING;
			break;
		case 'datestring2':
			$format = _DATESTRING2;
			break;
		case 'datetimebrief':
			$format = _DATETIMEBRIEF;
			break;
		case 'datetimelong':
			$format = _DATETIMELONG;
			break;
		case 'linksdatestring':
			$format = _LINKSDATESTRING;
			break;
		case 'timebrief':
			$format = _TIMEBRIEF;
			break;
		case 'timelong':
			$format = _TIMELONG;
			break;
		default:
	} // switch

	if($string != '') {
    	return strftime($format, smarty_make_timestamp($string));
	} elseif (isset($default_date) && $default_date != '') {
    	return strftime($format, smarty_make_timestamp($default_date));
	} else {
		return;
	}
}

?>