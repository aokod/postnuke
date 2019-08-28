<?php
// $Id: function.pncountnewmessages.php 14122 2004-07-25 19:48:33Z markwest $
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
 * @version      $Id: function.pncountnewmessages.php 14122 2004-07-25 19:48:33Z markwest $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to get the number of new messages for the user currently logged in
 * 
 * 
 * Available parameters:
 *   - assign:   If set, the results are assigned to the corresponding variable instead of printed out
 * 
 * Example
 *   <!--[pncountnewmessages assign=newmessages]-->!
 * 
 * 
 * @author       Frank Schummertz
 * @since        03/19/2004
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 */
function smarty_function_pncountnewmessages ($params, &$smarty) 
{
    extract($params); 
	unset($params);

    $newmessages = 0;
    if (pnUserLoggedIn()) {
		if (pnModAvailable('Messages'))	{
			$dbconn =& pnDBGetConn(true);
			$pntable =& pnDBGetTables();
        	$column = &$pntable['priv_msgs_column'];
        	// get unread messages
			$result =& $dbconn->Execute("SELECT count(*) FROM $pntable[priv_msgs] WHERE $column[to_userid]='" . pnVarPrepForStore(pnUserGetVar('uid')) . "' AND $column[read_msg]='0'");
        	list($newmessages) = $result->fields;  // new messages
        	$result->Close();
        }
    }

    if (isset($assign)) {
        $smarty->assign($assign, $newmessages);
    } else {
        return $newmessages;        
    }
}

?>