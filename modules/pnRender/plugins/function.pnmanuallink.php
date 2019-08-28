<?php
// $Id: function.pnmanuallink.php 16722 2005-08-27 12:35:10Z  $
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
// but WIthOUT ANY WARRANTY; without even the implied warranty of
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
 * @version      $Id: function.pnmanuallink.php 16722 2005-08-27 12:35:10Z  $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

/**
 * Smarty function to create  manual link. 
 * 
 * This function creates a manual link from some parameters.
 * 
 * Available parameters:
 *   - manual:    name of manual file, manual.html if not set
 *   - chapter:   an anchor in the manual file to jump to
 *   - newwindow: opens the manual in a new window using javascript
 *   - width:     width of the window if newwindow is set, default 600
 *   - height:    height of the window if newwindow is set, default 400
 *   - title:     name of the new window if newwindow is set, default is modulename
 *   - class:     class for use in the <a> tag
 *   - assign:    if set, the results ( array('url', 'link') are assigned to the corresponding variable instead of printed out
 * 
 * Example
 * <!--[pnmanuallink newwindow=1 width=400 height=300 title=rtfm ]-->
 * 
 * 
 * @author       Frank Schummertz
 * @since        04/26/2004
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the link or assign an array( 'url', 'link' ) if assign is set
 */

function smarty_function_pnmanuallink($params, &$smarty)
{
    extract($params);
    unset($params);
    
    $userlang= pnUserGetLang();
    $stdlang = pnConfigGetVar( 'language' );
    
    $title   = (isset($title)) ? $title : "Manual";
    $manual  = (isset($manual)) ? $manual : "manual.html";
    $chapter = (isset($chapter)) ? "#".$chapter : "";
    $class   = (isset($class)) ? "class='$class'" : "";
    $width   = (isset($width)) ? $width : 600;
    $height  = (isset($height)) ? $height : 400;
    $modname = pnModGetName();

    $possibleplaces = array( "modules/$modname/pndocs/lang/$userlang/manual/$manual",
                             "modules/$modname/pndocs/lang/$stdlang/manual/$manual",
                             "modules/$modname/pndocs/lang/eng/manual/$manual",
                             "modules/$modname/pndocs/lang/$userlang/$manual",
                             "modules/$modname/pndocs/lang/$stdlang/$manual",
                             "modules/$modname/pndocs/lang/eng/$manual" );
    foreach( $possibleplaces as $possibleplace ) {
        if(file_exists($possibleplace)) {
            $url = $possibleplace.$chapter;
            break;
        }
    }
    if(isset($newwindow)) {
        $link = "<a $class href='#' onclick=\"window.open( '" . pnVarPrepForDisplay($url) . "' , '" . pnVarPrepForDisplay($modname) . "', 'status=yes,scrollbars=yes,resizable=yes,width=$width,height=$height'); picwin.focus();\">" . pnVarPrepHTMLDisplay($title) . "</a>";
    } else {
        $link = "<a $class href=\"" . pnVarPrepForDisplay($url) . "\">" . pnVarPrepHTMLDisplay($title) . "</a>";
    }

    if(isset($assign)) {
        $ret = array( 'url' => $url,
                      'link' => $link );
        $smarty->assign( $assign, $ret );
        return;
    } else {
        return $link;
    }
    
}

?>