<?php
// $Id: function.displaygreeting.php 16722 2005-08-27 12:35:10Z  $
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
 * Xanthia plugin
 * 
 * This file is a plugin for pnRender, the PostNuke implementation of Smarty
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   Xanthia
 * @version      $Id: function.displaygreeting.php 16722 2005-08-27 12:35:10Z  $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to display a greeting to the user with the number of private messages received.
 * 
 * This function displays a welcome message: welcome and the number of private 
 * messages for a registered user or welcome and a signup link for a guest.
 * 
 * Examples (with Admin having 5 messages total, 1 unread):
 * <!--[displaygreeting]-->  or
 * <!--[displaygreeting displayMsgs=true]-->
 * Returns
 * Welcome Admin!  You have 1 private message.
 * or 
 * Welcome Admin! You have no messages.
 *
 * <!--[displaygreeting class="welcome"  displayMsgs=false]-->
 * Returns
 * Welcome Admin!
 * styled with the class "welcome"
 *
 * <!--[displaygreeting multiline=true displayAllMsgs=true]-->
 * Returns
 * <span class="pn-normal">Welcome&nbsp;Admin! <br />
 * <span style="white-space: nowrap;">You have <a href="index.php?module=Messages"><strong>5&nbsp;private messages</strong></a>, 1 unread</span></span>
 * Welcome Admin! 
 * You have 5 private messages, 1 unread.
 *
 * <!--[displaygreeting displayAllMsgs=false class="messages"]-->
 * Returns
 * <span class="messages">Welcome&nbsp;Admin! &nbsp;&nbsp;<span style="white-space: nowrap;">You have <a href="index.php?module=Messages"><strong>1&nbsp;unread message</strong></a></span></span>
 * Welcome [username]! You have 1 unread message.
 * or 
 * Welcome [username]! You have no unread messages.
 *
 * If not logged in, returns
 * Unregistered? <a href="user.php">Register for a user account</a>.
 *
 * Language defines:
 * define('_WELCOME','Welcome');
 * define('_YOUHAVE','You have');
 * define('_PRIVATEMESSAGE','private message');
 * define('_PRIVATEMESSAGES','private messages');
 * define('_UNREADMSG','unread message');
 * define('_UNREADMSGS','unread messages');
 * define('_UNREAD','unread');
 * define('_NOUNREADMESSAGES','You have no unread messages.');
 * define('_NOMESSAGES','You have no messages.');
 * define('_CREATEACCOUNT','Unregistered? <a href="user.php">Register for a user account</a>.\n');
 * 
 * @author       Mark West, Martin Stær Andersen
 * @since        19/10/2003
 * @see          function.displaygreeting.php::smarty_function_displaygreeting()
 * @param        array       $params         All attributes passed to this function from the template
 * @param        object      &$smarty        Reference to the Smarty object
 * @param        string      class           CSS class for string
 * @param        string      displayMsgs     Set to false (or any value) to turn off display of Private Messages
 * @param        string      displayAllMsgs  Set to false (or any value) to only display unread Messages
 * @param        string      multiline       Set to true to show Welcome and Messages on two lines (with Break).
 * @return       string      the welcome message
 */
function smarty_function_displaygreeting($params, &$smarty) {
	extract($params);
	unset($params);

	// set some defaults
	if (!isset($class)) {
		$class = 'pn-normal';
	}

	// Turn on message display if not explicitly set or set true, or Display All is set
	$displayMsgs = ((!isset($displayMsgs) || $displayMsgs) or isset($displayAllMsgs) ? true : false);
	$displayAllMsgs = (isset($displayAllMsgs) && $displayAllMsgs ? true : false);
	$multiline = (isset($multiline) && $multiline ? true : false); 
	
	// Get the db connection
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();

	if (pnUserLoggedIn()) {
		$username = pnUserGetVar('uname');
		$uid = pnUserGetVar('uid');
		if (!defined('_WELCOME'))  define('_WELCOME','Welcome'); // _Welcome
		if (!defined('_YOUHAVE'))  define('_YOUHAVE','You have'); // _YouHave
		$greeting = "<span class=\"$class\">"._WELCOME."&nbsp;".$username."! ";
		if ($displayMsgs or $displayAllMsgs) {
			// Gather User's unread messages
			$column = &$pntable['priv_msgs_column'];
			// Get total number of messages from database, 
			// or just Unread if displayAllMsgs is off
       			$result =& $dbconn->Execute("SELECT count(*) FROM $pntable[priv_msgs] 
							WHERE $column[to_userid]=$uid"
							.($displayAllMsgs ? "" : 
							" AND $column[read_msg]='0'"));
			if ($dbconn->ErrorNo() != 0) {
				return false;
			}
        		list($messages) = $result->fields;
        		if ($messages > 0) {
        			$greeting .= ($multiline ? "<br />\n" : '&nbsp;&nbsp;').'<span style="white-space: nowrap;">'._YOUHAVE
        				.' <a href="index.php?name=Messages"><strong>'.(int)$messages.'&nbsp;';
        			if ($displayAllMsgs) {
					// Get number of unread messages
					$result2 =& $dbconn->Execute("SELECT count(*) FROM $pntable[priv_msgs] 
									WHERE $column[to_userid]=$uid 
									AND $column[read_msg]='0'");
					if ($dbconn->ErrorNo() != 0) {
						return false;
					}
					list($unread) = $result2->fields;
					if (!defined('_PRIVATEMESSAGE'))   define('_PRIVATEMESSAGE','private message'); // _PrivateMessage
					if (!defined('_PRIVATEMESSAGES'))  define('_PRIVATEMESSAGES','private messages'); // _PrivateMessages
					if (!defined('_UNREAD'))           define('_UNREAD','unread'); // _Unread
					$greeting .= ($messages==1 ? _PRIVATEMESSAGE : _PRIVATEMESSAGES)."</strong></a>, $unread "._UNREAD.'</span></span>';
				} else {
					if (!defined('_UNREADMSG'))   define('_UNREADMSG','unread message'); // _UnreadMsg
					if (!defined('_UNREADMSGS'))  define('_UNREADMSGS','unread messages'); // _UnreadMsgs
					$greeting .= ($messages==1 ? _UNREADMSG : _UNREADMSGS).'</strong></a></span></span>';
				}
			} else {
				if (!defined('_NOUNREADMESSAGES'))  define('_NOUNREADMESSAGES','You have no unread messages.'); // _NoUnreadMessages
				if (!defined('_NOMESSAGES'))        define('_NOMESSAGES','You have no messages.'); // _NoMessages
				$greeting .= ($multiline ? "<br />\n" : '').' &nbsp;<span style="white-space: nowrap;">'.($displayAllMsgs ? _NOMESSAGES : _NOUNREADMESSAGES)."</span></span>\n";
			}
       		} else {
       			$greeting .="</span>\n";
       		}
	} else {  // If not logged in, show login link, ask them to register
		if (!defined('_CREATEACCOUNT'))  define('_CREATEACCOUNT','Unregistered? <a href="user.php">Register for a user account</a>.'."\n"); // _CreateAccount
		$greeting = '<span class="'.$class.'" style="white-space: nowrap;">'._CREATEACCOUNT."</span>\n";   
	} // end login and private messages 

	return $greeting;
}
?>