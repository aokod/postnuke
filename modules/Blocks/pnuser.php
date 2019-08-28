<?php 
// File: $Id: pnuser.php 15630 2005-02-04 06:35:42Z jorg $
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
// Original Author of file:  lophas <lophas@yahoo.com>
// Purpose of file: Collapse Menu Bocks
// ----------------------------------------------------------------------
// Modified to PostNuke by: Michael (acm3) <michael@acm3.com>

/**
 * @package PostNuke_System_Modules
 * @subpackage Blocks
 * @license http://www.gnu.org/copyleft/gpl.html
*/

/**
 * Change the status of a block
 * Invert the status of a given block id (collapsed/uncollapsed)
 * 
 * @author Michael (acm3)
 * @author lophas
 * @return void
 */
function blocks_user_changestatus()
{
    /* Throwing an error under come conditions - commented out temporarily.
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }
    */
    $bid = pnVarCleanFromInput('bid');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $uid = pnUserGetVar('uid');

    $ublockstable = $pntable['userblocks'];
    $column = &$pntable['userblocks_column'];

    $sql="SELECT $column[active] FROM $ublockstable WHERE $column[bid]='".pnVarPrepForStore($bid)."' AND $column[uid]='".pnVarPrepForStore($uid)."'";
    $result =& $dbconn->Execute($sql);
    list($active)=$result->fields;
    if($active) {
		$active=0;
    } else {
		$active=1;
    }
    $sql="UPDATE $ublockstable SET $column[active]='".pnVarPrepForStore($active)."' WHERE $column[uid]='".pnVarPrepForStore($uid)."' AND $column[bid]='".pnVarPrepForStore($bid)."'";
    $result =& $dbconn->Execute($sql);
    if ($result === false) {
        pnSessionSetVar('errormsg', "Error $sql");
        return false;
    }
	
	// now lets get back to where we came from
	pnRedirect(pnServerGetVar('HTTP_REFERER'));
}

?>