<?php
// $Id: pnuser.php 19281 2006-06-25 14:11:06Z markwest $
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
// Original Author of file: Mark West
// Purpose of file:  Header user display functions
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Header_Footer
 * @license http://www.gnu.org/copyleft/gpl.html
*/

/**
 * The main header_footer user function
 * @author Mark West
 * @return HTML String
 */
function header_footer_user_main()
{
    // ratings module cannot be directly accessed
    return pnVarPrepHTMLDisplay(_MODULENODIRECTACCESS);
}

/**
 * The render header_footer user function
 * @author Mark West
 * @return true is OK
 */
function Header_Footer_user_render()
{
    global $themesarein;

    // check for the multisites module
    if (pnModAvailable('Multisites')) {
        require_once 'modules/Multisites/head.inc.php';
    }

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
    $pnRender =& new pnRender('Header_Footer');

    // get the current theme
    $thistheme = pnUserGetTheme();

	// since the module output has already completed there's no point caching 
	// things here.
    $pnRender->caching = false;

    // allow plugins to be used from the xanthia module if available
    if (!ereg('0.8', _PN_VERSION_NUM)) {
        $osdir = 'modules/Xanthia/plugins';
    } else {
        $osdir = 'system/Xanthia/plugins';
    }

    if (file_exists($osdir)) {
        array_push($pnRender->plugins_dir, $osdir);
    }

    // register trim whitespace output filter if requried
    if (pnModGetVar('Xanthia', 'trimwhitespace')) {
        $pnRender->load_filter('output','trimwhitespace');
    }

    // register short urls output filter if requried
    if (pnModGetVar('Xanthia', 'shorturls')) {
        $pnRender->load_filter('output','shorturls');
    }

    // get the output from the module (and themeheader/footer)
    $maincontent = ob_get_contents();
    ob_end_clean();
    //strip the main content of the closing head and opening
    // body tag - this allows the main template to be a properly
    // formed html file (a little hacky but hey..it's for
    // legacy themes.....
    $maincontent = str_replace('</head>', '', $maincontent);
    $maincontent = str_replace('<body>', '', $maincontent);
    $pnRender->assign_by_ref('maincontent', $maincontent);

    // assign the theme paths
    $pnRender->assign('themepath', "{$themesarein}themes/$thistheme");
    $pnRender->assign('imagepath', "{$themesarein}themes/$thistheme/images");

    // display the output
    if ($pnRender->template_exists($template = 'header_footer_page_'.pnModGetName().'.htm')) {
        $pnRender->display($template);
    } else {
        $pnRender->display('header_footer_page.htm');
    }
    return true;
}

?>