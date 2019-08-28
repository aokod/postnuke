<?php
// $Id: function.pm.php 20349 2006-10-20 10:50:27Z markwest $
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
 * @version      $Id: function.pm.php 20349 2006-10-20 10:50:27Z markwest $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to provide a link to private message a user
 * 
 * Example: <!--[pm uname="testuser" ]-->
 * Output:  <a href="<base_url>/index.php?module=Messages&func=compose&uname=Admin">Send a private message to testuser</a>
 *
 * @author       Mark West
 * @since        15 Aug. 2004
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      The img tag
 */
function smarty_function_pm($params, &$smarty)
{
    // get the parameters
    extract($params); 
	unset($params['uname']);

    if (!isset($uname)) {
        $smarty->trigger_error('pm: attribute uname required');
        return false;
    }

	if (!pnModAvailable('Messages')) {
		return;
	}

	// load the language file
	pnModLangLoad('Messages', 'user');

	if (pnUserLoggedIn() || pnSecAuthAction(0, 'Messages::', $uname . '::', ACCESS_COMMENT)) {
		$url = pnModURL('Messages', 'user', 'compose', array('uname' => $uname));
        $msg = _MESSAGESSENDTO;
	} else {
		if (_PN_VERSION_SUB == 'Phoenix') {
			$url = 'user.php?op=loginscreen&module=User';
		} else {
			$url = pnModURL('User', 'user', 'view', array('op' => 'loginscreen'));
		}
        $msg = _MESSAGESLOGINSENDTO;
	}
	$pmstring  = '<a href="' . pnVarPrepForDisplay($url) . '">';
	$pmstring .= '<img src="modules/Messages/pnimages/pm.gif" alt="' . pnVarPrepForDisplay(_MESSAGESSENDTO) . ' ' . pnVarPrepForDisplay($uname) . ' "';
	$pmstring .= ' title="' . pnVarPrepForDisplay($msg) . ' ' . pnVarPrepForDisplay($uname) . '" /></a>';

    if (isset($assign)) {
        $smarty->assign($assign, $pmstring);
    } else {
        return $pmstring;
    }      
}
?>