<?php
// File: $Id: oldfuncs.php 16633 2005-08-11 07:59:33Z larsneo $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
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
// Original Author of file: Jim McDonald
// Purpose of file: Back-compatibility functions
// ----------------------------------------------------------------------

if (strpos($_SERVER['PHP_SELF'], 'oldfuncs.php')) {
	die ("You can't access this file directly...");
}

// Replacement functions for old modules
function is_admin()
{
    return pnSecAuthAction(0, '.*', '.*', ACCESS_ADMIN);
}

function is_user()
{
    return pnUserLoggedIn();
}

function cookiedecode()
{
    if (!pnUserLoggedIn()) {
        return;
    }

    global $cookie;
    $cookie = array(pnUserGetVar('uid'),
                    pnUserGetVar('uname'),
                    pnUserGetVar('pass'),
                    pnUserGetVar('storynum'),
                    pnUserGetVar('umode'),
                    pnUserGetVar('uorder'),
                    pnUserGetVar('thold'),
                    pnUserGetVar('noscore'),
                    pnUserGetVar('ublockon'),
                    pnUserGetVar('theme'),
                    pnUserGetVar('commentmax'));

    return $cookie;
}

// Needed for some old modules
global $user;
if (pnUserLoggedIn()) {
    $user = pnUserGetVar('uid');
} else {
    $user = "";
}
    
// More needed for some old modules
global $prefix;
global $pnconfig;
$prefix = $pnconfig['prefix'];

// Yet another one needed for older modules
include('includes/legacy/textsanitizer.php');

function getusrinfo($user) {
    global $userinfo;
    
    if (empty($user)) {
        return;
    }

    if (isset($userinfo['uid'])) {
        return $userinfo;
    }

    $user3 = cookiedecode();

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $column = $pntable['users_column'];

    $sql = "SELECT $column[uid] AS uid,
                   $column[name] AS name,
                   $column[uname] AS uname,
                   $column[email] AS email,
                   $column[femail] AS femail,
                   $column[url] AS url,
                   $column[user_avatar] AS user_avatar,
                   $column[user_icq] AS user_icq,
                   $column[user_occ] AS user_occ,
                   $column[user_from] AS user_from,
                   $column[user_intrest] AS user_intrest,
                   $column[user_sig] AS user_sig,
                   $column[user_viewemail] AS user_viewemail,
                   $column[user_theme] AS user_theme,
                   $column[user_aim] AS user_aim,
                   $column[user_yim] AS user_yim,
                   $column[user_msnm] AS user_msnm,
                   $column[pass] AS pass,
                   $column[storynum] AS storynum,
                   $column[umode] AS umode,
                   $column[uorder] AS uorder,
                   $column[thold] AS thold,
                   $column[noscore] AS noscore,
                   $column[bio] AS bio,
                   $column[ublockon] AS ublockon,
                   $column[ublock] AS ublock,
                   $column[theme] AS theme,
                   $column[commentmax] AS commentmax,
                   $column[timezone_offset] AS timezone_offset
            FROM $pntable[users]
            WHERE $column[uname] = '" . pnVarPrepForStore($user3[1]). "'";

    $result =& $dbconn->Execute($sql);
    if ($result->PO_RecordCount() == 1) {
        $userinfo = $result->GetRowAssoc(false);
    } else {
        echo "Problem obtaining user information<br />";
    }
    return $userinfo;
}

?>