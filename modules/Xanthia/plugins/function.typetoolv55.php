<?php
// $Id: function.typetoolv55.php 13213 2004-04-22 12:28:05Z drak $
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
 * @version      $Id: function.typetoolv55.php 13213 2004-04-22 12:28:05Z drak $
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
 * @see          function.typetoolv55.php::smarty_function_typetoolv55()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the requisite javascript
 */
function smarty_function_typetoolv55($params, &$smarty) 
{
    extract($params); 
	unset($params);

	// Enable Wysiwyg editor configuration at seeting Added by bharvey42 edited by Neo 
	$pnWysiwygEditor = pnConfigGetVar('WYSIWYGEditor'); 
	if (is_numeric($pnWysiwygEditor) && $pnWysiwygEditor == 1) {  
		$pnWSEditorPath = pnGetBaseURI();
		$script = "<!--Visual Editor Plug-in-->\n" 
		."<script type=\"text/javascript\">QBPATH='".$pnWSEditorPath."/javascript'; VISUAL=0; SECURE=1;</script>\n" 
		."<script type=\"text/javascript\" src='".$pnWSEditorPath."/javascript/quickbuild.js'></script>\n" 
		."<script type=\"text/javascript\" src='".$pnWSEditorPath."/javascript/tabedit.js'></script>\n"
		."<!--End Visual Editor Plug-in-->"; 
	} else { 
		$script = '';
	} 

    return $script;
}

?>