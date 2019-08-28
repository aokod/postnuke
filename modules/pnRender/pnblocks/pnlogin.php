<?php
// $Id: pnlogin.php 16582 2005-08-01 11:09:05Z landseer $
// ----------------------------------------------------------------------
// POST-NUKE Content Management System
// Copyright (C) 2001 by the Post-Nuke Development Team.
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

function pnRender_pnloginblock_info()
{
    return array(
    'module'         => 'pnRender',
    'text_type'      => 'pnLogin',
    'text_type_long' => 'pnRender Loginblock',
    'allow_multiple' => false,
    'form_content'   => false,
    'form_refresh'   => false,
    'show_preview'   => true
    );
}

function pnRender_pnloginblock_init()
{
    pnSecAddSchema('pnLoginblock::', 'Block title::');
}

function pnRender_pnloginblock_display($row)
{
	global $HTTP_SERVER_VARS;

    if (empty($row['title'])) {
        $row['title'] = 'Login';
    }
    if (!pnSecAuthAction(0, 'pnLoginblock::', "$row[title]::", ACCESS_READ)) {
        return;
    }

    if (!pnUserLoggedIn()) {
    	// get the current uri so we can return the user to the correct place
	    $path = pnGetCurrentURI();

        $pnr =& new pnRender('pnRender');
        $pnr->assign('path', $path);
        $pnr->assign('seclevel', pnConfigGetVar('seclevel'));
        $row['content'] = $pnr->fetch('pnlogin.html');
        return themesideblock($row);
    }
}
?>