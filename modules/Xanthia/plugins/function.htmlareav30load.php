<?php
// $Id: function.htmlareav30load.php 13469 2004-05-11 08:13:53Z markwest $
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
 * This file is a plugin for Xanthia, the PostNuke implementation of Smarty
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   Xanthia
 * @version      $Id: function.htmlareav30load.php 13469 2004-05-11 08:13:53Z markwest $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to display the necessary javascript for the htmlarea
 * editor shipped with PN .8x.
 * 
 * This plugin must be combined with the htmlareav30script plugin
 *
 * Example
 * <!--[htmlareav30script]-->
 * <body <!--[htmlareav30load]-->>
 * 
 * @author       Mark West
 * @since        16/02/04
 * @see          function.htmlareav30body.php::smarty_function_htmlareav30body()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the requisite javascript
 */
function smarty_function_htmlareav30load($params, &$smarty) 
{
    extract($params); 
	unset($params);

	// include htmlarea v3.0
	$htmlarea_enable = pnModGetVar('htmlarea', 'enable');
	$baseURL = pnGetBaseURL();
	$ModName = pnModGetName(); 
	$script = '';
	if (is_numeric($htmlarea_enable) && $htmlarea_enable == 1) {    
		if (pnSecAuthAction(0, 'htmlarea::', "$ModName::", ACCESS_COMMENT) && 
				$ModName != "Settings" && 
				$ModName != "Permissions" && 
				$ModName != "Censor") {
			// enable the wysiwyg editor when user has at least comment rights 
			// no WYSIWYG in Settings, Permissions and Censor anyway...
			$script = ' onload="HTMLArea.replaceAll()"';
		}
	}

    return $script;
}

?>