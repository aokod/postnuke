<?php
// $Id: function.thememenujs.php 14250 2004-08-10 12:35:48Z markwest $
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
 * This file is a plugin for Xanthia, the PostNuke theme engine
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   Xanthia
 * @version      $Id: function.thememenujs.php 14250 2004-08-10 12:35:48Z markwest $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2004 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to provide easy access to an image
 * 
 * This function provides an easy way to include an image. The function will return the
 * full source path to the image. It will as well provite the width and height attributes 
 * if none are set.
 *
 * available parameters:
 *  - xhtml       if set, the xhtml format of the stylesheet tag will be used
 *  - modname     module name (if not set, the current module is assumed)
 *  - stylesheet  name of the style sheet. If not set, style.css is assumed
 *  - assign      if set, the tag and the style sheet are returned
 *
 * Example: <!--[modulestylesheet]-->
 * Output:  <link rel="stylesheet" href="templates/smarty/style.css" type="text/css">
 *
 * Example: <!--[modulestylesheet xhtml=true]-->
 * Output:  <link rel="stylesheet" href="templates/smarty/style.css" type="text/css" />
 * 
 * Example: <!--[modulestylesheet modname="foobar" stylesheet="mystyle.css" assign="style"]-->
 * Will output nothing; and the Smarty variable "style" will be set:
 * 
 * <!--[$style.tag]-->           -- the full tag:
 * <link rel="stylesheet" href="modules/foo/pnstyle/mystyle.css" type="text/css">
 * 
 * <!--[$style.stylesheet]-->    -- the path and the name of the stylesheet:
 * modules/foo/pnstyle/mystyle.css
 * 
 *
 * @author       Mark West
 * @author       J�rg Napp
 * @since        12. Feb. 2004
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      The tag
 */
function smarty_function_thememenujs($params, &$smarty)
{
    // get the parameters
    extract($params); 
	unset($params['xhtml']);

	// default for the module
    if (!isset($themename)) {
        $themename = pnUserGetTheme();
    } 
    
	$csssrc = '';      
	if (file_exists(''.pnConfigGetVar('temp').'/Xanthia_Config/'.$themename.'.menu.js')) {
		$csssrc =	"".pnConfigGetVar('temp')."/Xanthia_Config/$themename.menu.js";
	}
	// if no theme stylesheet is present then return no content
	if ($csssrc == '') {
        $tag='';
	} else {
    	// create xhtml specifier
	    if (isset($xhtml)) {
    		$xhtml = ' /';
	    } else {
    		$xhtml = '';
	    }
        $tag = '<script src="'.$csssrc.'" type="text/javascript"></script>'."\n";
    }

    if (isset($assign)) {
        $params['stylesheet'] = $csssrc;
        $params['tag'] = $tag;
        $smarty->assign($assign, $params);
    } else {
        return $tag;        
    }      
}
?>