<?php
// $Id: function.typetoolv80.php 16722 2005-08-27 12:35:10Z  $
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
 * @version      $Id: function.typetoolv80.php 16722 2005-08-27 12:35:10Z  $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to display the necessary javascript for the vietdev typetool
 * editor shipped with PN .72x
 * 
 * Example
 * <!--[typetool55]-->
 * 
 * @author       Mark West
 * @since        16/02/04
 * @see          function.typetoolv80.php::smarty_function_typetoolv80()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the requisite javascript
 */
function smarty_function_typetoolv80($params, &$smarty) 
{
    extract($params); 
	unset($params);

	if (!pnModAvailable('typetool')) {
		return;
	}
	// include typetool 8.0 [larsneo]
	// (upload option disabled for security reasons) 
	$tt_enable = pnModGetVar('typetool', 'enable');
	$baseURL = pnGetBaseURL();
	$ModName = pnModGetName(); 
	$script = '';
	if (is_numeric($tt_enable) && $tt_enable == 1) {  
		if (pnSecAuthAction(0, 'typetool::', "$ModName::", ACCESS_COMMENT) && 
				$ModName != "Settings" && 
				$ModName != "Permissions" && 
				$ModName != "Censor") {
			// enable the wysiwyg editor when user has at least comment rights 
			// no WYSIWYG in Settings, Permissions and Censor anyway...
		    $script =  "\n<!--Visual Editor Plug-in-->\n";
    		$script .= "<script type=\"text/javascript\">VISUAL=0; FULLCTRL=1; SECURE=1; USETABLE=1; USEFORM=1; LANGUAGE='".pnModGetVar('typetool', 'language')."';</script>\n";
    		$script .= "<script type=\"text/javascript\" src='".$baseURL."modules/typetool/pnincludes/quickbuild.js'></script>\n";
    		$script .= "<!--End Visual Editor Plug-in-->\n";
		}
	}

    return $script;
}

?>