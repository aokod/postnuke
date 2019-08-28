<?php 
// File: $Id: access.php 15630 2005-02-04 06:35:42Z jorg $
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

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file: verify username/password against form input, upgrade
// user passwords to md5 if not already done.
// ----------------------------------------------------------------------
function updateUserPass($username, $md5pass)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column = &$pntable['users_column'];
    $result =& $dbconn->Execute("UPDATE $pntable[users]
                              SET $column[pass] = '" . pnVarPrepForStore($md5pass) . "'
                              WHERE $column[uname]='" . pnVarPrepForStore($username) . "'");
} 

function access_user_login($uname, $pass, $url, $rememberme)
{
    if (pnUserLogIn($uname, $pass, $rememberme)) {
        redirect_index(_LOGGINGYOU, $url);
    } else {
    	pnSessionSetVar('errormsg', _LOGININCOR);
        pnRedirect('user.php?stop=1');
    } 
} 

?>