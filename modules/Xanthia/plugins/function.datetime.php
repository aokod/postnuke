<?php
// $Id: function.datetime.php 16722 2005-08-27 12:35:10Z  $
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
 * Xanthia plugin
 * 
 * This file is a plugin for pnRender, the PostNuke implementation of Smarty
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   Xanthia
 * @version      $Id: function.datetime.php 16722 2005-08-27 12:35:10Z  $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to display the current date and time
 * 
 * Example
 * <!--[datetime]-->
 *
 * <!--[datetime format="_DATEBRIEF"]-->
 *
 * <!--[datetime format='%b %d, %Y - %I:%M %p']-->
 * 
 *  Format:
 * _DATEBRIEF       '%b %d, %Y'
 * _DATELONG        '%A, %B %d, %Y'
 * _DATESTRING      '%A, %B %d @ %H:%M:%S'
 * _DATETIMEBRIEF   '%b %d, %Y - %I:%M %p'
 * _DATETIMELONG    '%A, %B %d, %Y - %I:%M %p'
 * 
 * Key:
 * %a - abbreviated weekday name according to the current locale
 * %A = full weekday name according to the current locale
 * %b = abbreviated month name according to the current locale
 * %B = full month name according to the current locale
 * %d = day of the month as a decimal number (range 01 to 31)
 * %D = same as %m/%d/%y
 * %y = year as a decimal number including the century
 * %Y = year as a decimal number without a century (range 00 to 99)
 * %H = hour as a decimal number using a 24-hour clock (range 00 to 23)
 * %I = hour as a decimal number using a 12-hour clock (range 01 to 12)
 * %M = minute as a decimal number
 * %S = second as a decimal number
 * %p = either 'am' or 'pm' according to the given time value, or the corresponding strings for the current locale
 
 * ml_ftime function is defined in modules/NS-languages/api.php in PN 0.7x
 * and in includes/pnLang.php line 450 in PN 0.8
 * http://www.php.net/manual/en/function.strftime.php 

 * @author       Mark West & Martin Andersen
 * @since        19/10/2003
 * @see          function.datetime.php::smarty_function_datetime()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @param        string      format       Date and time format
 * @return       string      current date and time
 */
function smarty_function_datetime($params, &$smarty) 
{
	extract($params); 
	unset($params);
    
	// set some defaults
	if (!isset($format)) {
		$format = '_DATETIMEBRIEF';
	}
	if (strpos($format, '%') !== false) { // allow the use of conversion specifiers
		return ml_ftime($format, (GetUserTime(time())));
	} 
	return ml_ftime(constant($format), (GetUserTime(time())));
}

?>