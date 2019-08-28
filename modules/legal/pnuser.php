<?php
// $Id: pnuser.php 15533 2005-01-28 16:16:17Z markwest $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2003 by the PostNuke Development Team.
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
// Original Author of file: Xiaoyu Huang
// Purpose of file:  legal display functions
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage legal
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * Legal Module main user function
 * @author Michael M. Wechsler
 * @author Xiaoyu Huang
 * @return string HTML output string
 */
function legal_user_main()
{
   
    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing.  For the
    // main function we want to check that the user has at least edit privilege
    // for some item within this component, or else they won't be able to do
    // anything and so we refuse access altogether.  The lowest level of access
    // for administration depends on the particular module, but it is generally
    // either 'edit' or 'delete'
    if (!pnSecAuthAction(0, 'legal::', '::', ACCESS_OVERVIEW)) {
        return pnVarPrepHTMLDisplay(_BADAUTHKEY);
    }    

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
	$pnRender =& new pnRender('legal');

    $pnRender->assign('main', pnModFunc('legal','user','termsofuse'));

    return $pnRender->fetch('legal_user_main.htm');
   
}

/**
 * Display Terms of Use
 * @author Michael M. Wechsler
 * @author Xiaoyu Huang
 * @return string HTML output string
 */
function legal_user_termsofuse()
{
    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing.  For the
    // main function we want to check that the user has at least edit privilege
    // for some item within this component, or else they won't be able to do
    // anything and so we refuse access altogether.  The lowest level of access
    // for administration depends on the particular module, but it is generally
    // either 'edit' or 'delete'
    if (!pnSecAuthAction(0, 'legal::', '::', ACCESS_OVERVIEW)) {
        return pnVarPrepHTMLDisplay(_BADAUTHKEY);
    }    

    // check the option is active
	if (!pnModGetVar('legal', 'termsofuse')) {
		return pnVarPrepForDisplay(_TOUNOTACTIVE);
	}

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
	$pnRender =& new pnRender('legal');

	// get the current users language
	$lang = pnUserGetLang();

	// work out the template path
	if ($pnRender->template_exists($lang.'/legal_user_termsofuse.htm')) {
		$template = $lang.'/legal_user_termsofuse.htm';
	} else {
		$template = 'eng/legal_user_termsofuse.htm';
	}

	// check out if the contents are cached.
	// If this is the case, we do not need to make DB queries.
	if ($pnRender->is_cached($template)) {
	   return $pnRender->fetch($template);
	}

    return $pnRender->fetch($template);
}

/**
 * Display Privacy Policy
 * @author Michael M. Wechsler
 * @author Xiaoyu Huang
 * @return string HTML output string
 */
function legal_user_privacy()
{
    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing.  For the
    // main function we want to check that the user has at least edit privilege
    // for some item within this component, or else they won't be able to do
    // anything and so we refuse access altogether.  The lowest level of access
    // for administration depends on the particular module, but it is generally
    // either 'edit' or 'delete'
    if (!pnSecAuthAction(0, 'legal::', '::', ACCESS_OVERVIEW)) {
        return pnVarPrepHTMLDisplay(_BADAUTHKEY);
    }    

    // check the option is active
	if (!pnModGetVar('legal', 'privacypolicy')) {
		return pnVarPrepForDisplay(_PPNOTACTIVE);
	}

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
	$pnRender =& new pnRender('legal');

	// get the current users language
	$lang = pnUserGetLang();

	// work out the template path
	if ($pnRender->template_exists($lang.'/legal_user_privacy.htm')) {
		$template = $lang.'/legal_user_privacy.htm';
	} else {
		$template = 'eng/legal_user_privacy.htm';
	}

	// check out if the contents are cached.
	// If this is the case, we do not need to make DB queries.
	if ($pnRender->is_cached($template)) {
	   return $pnRender->fetch($template);
	}

    return $pnRender->fetch($template);
}

/**
 * Display Accessibility statement
 * @author Mark West
 * @return string HTML output string
 */
function legal_user_accessibilitystatement()
{
    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing.  For the
    // main function we want to check that the user has at least edit privilege
    // for some item within this component, or else they won't be able to do
    // anything and so we refuse access altogether.  The lowest level of access
    // for administration depends on the particular module, but it is generally
    // either 'edit' or 'delete'
    if (!pnSecAuthAction(0, 'legal::', '::', ACCESS_OVERVIEW)) {
        return pnVarPrepHTMLDisplay(_BADAUTHKEY);
    }    

    // check the option is active
	if (!pnModGetVar('legal', 'accessibilitystatement')) {
		return pnVarPrepForDisplay(_ASNOTACTIVE);
	}

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
	$pnRender =& new pnRender('legal');

	// get the current users language
	$lang = pnUserGetLang();

	// work out the template path
	if ($pnRender->template_exists($lang.'/legal_user_accessibilitystatement.htm')) {
		$template = $lang.'/legal_user_accessibilitystatement.htm';
	} else {
		$template = 'eng/legal_user_accessibilitystatement.htm';
	}

	// check out if the contents are cached.
	// If this is the case, we do not need to make DB queries.
	if ($pnRender->is_cached($template)) {
	   return $pnRender->fetch($template);
	}

    return $pnRender->fetch($template);
}

?>