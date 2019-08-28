<?php
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
// Purpose of file: Display admin messages
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Admin_Messages
 * @license http://www.gnu.org/copyleft/gpl.html
*/

/**
 * initialise block
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 */
function Admin_Messages_messagesblock_init()
{
    // Security
    pnSecAddSchema('Admin_Messages:messagesblock:', 'block title::');
}

/**
 * get information on block
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 * @return array blockinfo array
 */
function Admin_Messages_messagesblock_info()
{
    // Values
    return array('text_type' => 'Messages',
                 'module' => 'Admin_Messages',
                 'text_type_long' => 'Show Admin Messages',
                 'allow_multiple' => true,
                 'form_content' => false,
                 'form_refresh' => false,
                 'show_preview' => true);
}

/**
 * display block
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 * @param 'row' blockinfo array
 * @return string HTML output string
 */
function Admin_Messages_messagesblock_display($row)
{
	// set default values
    if (!empty($row['title'])) {
        $row['title'] = '';
    }

	//security check
    if (!pnSecAuthAction(0, 'Admin_Messages:messagesblock:', "$row[title]::", ACCESS_READ)) {
        return;
    }

	// Check the module is available
	if (!pnModAvailable('Admin_Messages')) {
		return;
	}

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
	$pnRender =& new pnRender('Admin_Messages');

	// For caching reasons you must pass a cache ID. This could be done as a 
	// separate parameter to every method that uses caching (like fetch, is_cached
	// etc.) or by assigning the ID to the cache_id property like it is done in
	// this case. 
	// The user id is used at the cache here since admin messages may be 
	// controlled by user permissions
	$pnRender->cache_id = pnUserGetVar('uid');
	
	// check out if the contents are cached.
	// If this is the case, we do not need to make DB queries.
	// Note that we print out "cached:" in front of a chached output --
	// of course, this is here to illustrate caching and needs to be removed!
	if ($pnRender->is_cached('messages.htm')) {
	    // Populate block info and pass to theme
    	$row['content'] = $pnRender->fetch('admin_messages_block_messages.htm');
		return themesideblock($row);
	}

	// call the api function
	$messages = pnModAPIFunc('Admin_Messages', 'user', 'getactive');

    $messagestodisplay = array();
	foreach ($messages as $message) {
        $show = 0;
		// Since the API has already done the permissions check
        // there's no need to duplicate the checks here.
        // We'll still the support admin panel 'permission' settings 
        // as these are still useful as a quick solution
        switch($message['view']) {
        case 1:
        	// Message for everyone
        	$show = 1;
        	break;
		case 2:
        	// Message for users
        	if (pnUserLoggedIn()) {
        		$show = 1;
        	}
        	break;
        case 3:
			// Messages for non-users
        	if (!pnUserLoggedIn()) {
        		$show = 1;
        	}
        	break;
        case 4:
        	 // Messages for administrators of any description
        	 if (pnSecAuthAction(0, '::', '::', ACCESS_ADMIN)) {
        		 $show = 1; 
        	 }
        	 break;
        }
        if ($show) {
			$messagestodisplay[] = $message;
        }
    }

	// check for an empty result set
	if (empty($messagestodisplay)) return;

	$pnRender->assign('messages', $messagestodisplay);
	
    // Populate block info and pass to theme
    $row['content'] = $pnRender->fetch('admin_messages_block_messages.htm');

    return themesideblock($row);

}

?>