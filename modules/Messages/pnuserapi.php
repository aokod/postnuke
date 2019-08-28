<?php
// $Id: pnuserapi.php 16155 2005-04-28 08:25:38Z markwest $
// ----------------------------------------------------------------------
// POST-NUKE Content Management System
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
 * Messages Module
 * 
 * Purpose of file:  User API -- 
 *                   The file that contains all user operational 
 *                   functions for the module
 *
 * @package      PostNuke_Miscellaneous_Modules
 * @subpackage   Messages
 * @version      $Id: pnuserapi.php 16155 2005-04-28 08:25:38Z markwest $
 * @author       Mark West
 * @author       Richard Tirtadji
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

 
/**
 * get all messages
 * 
 * @return   array         items array, or false on failure
 */
function Messages_userapi_getall($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other places
    // such as the environment is not allowed, as that makes assumptions that
    // will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present, if
    // not then set an appropriate error message and return
    if (!isset($uid) || !is_numeric($uid)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $items = array();

    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn() we
    // currently just want the first item, which is the official database
    // handle.  For pnDBGetTables() we want to keep the entire tables array
    // together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // It's good practice to name the table and column definitions you are
    // getting - $table and $column don't cut it in more complex modules
    $messagestable = $pntable['priv_msgs'];
    $messagescolumn = &$pntable['priv_msgs_column'];

    // Get item - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the Execute() command allows for simpler debug operation
    // if it is ever needed
    $sql = "SELECT $messagescolumn[msg_id],
	               $messagescolumn[msg_image],
                   $messagescolumn[subject],
				   $messagescolumn[from_userid],
             	   $messagescolumn[to_userid],
				   $messagescolumn[msg_time],
                   $messagescolumn[msg_text],
				   $messagescolumn[read_msg]
             FROM $pntable[priv_msgs] 
             WHERE $messagescolumn[to_userid]='".(int)pnVarPrepForStore($uid)."'
			 ORDER BY $messagescolumn[msg_time] DESC";
    $result = $dbconn->Execute($sql); 

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    // Check for no rows found, and if so return
    if ($result->EOF) {
        return false;
    }

    // Put items into result array.  Note that each item is checked
    // individually to ensure that the user is allowed access to it before it
    // is added to the results array
    for (; !$result->EOF; $result->MoveNext()) {
		// Obtain the item information from the result set
		list($msg_id, $msg_image, $subject, $from_userid, $to_userid, $msg_time, $msg_text, $read_msg) = $result->fields;
		// Create the item array
		$items[] = array('msg_id' => $msg_id,
						 'msg_image' => $msg_image,
						 'subject' => $subject,
						 'from_userid' => $from_userid,
						 'to_userid' => $to_userid,
						 'msg_time' => $msg_time,
						 'msg_text' => $msg_text,
						 'read_msg' => $read_msg);
	}
    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Return the item array
    return $items;
}

/**
 * get a specific message
 * 
 * @param    $args['msgid']  id of messageget
 * @param    $args['uid']  id of user
 * @return   array         item array, or false on failure
 */
function Messages_userapi_get($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other places
    // such as the environment is not allowed, as that makes assumptions that
    // will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present, if
    // not then set an appropriate error message and return
    if ((!isset($msgid) || !is_numeric($msgid)) ||
       (!isset($uid) || !is_numeric($uid))){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn() we
    // currently just want the first item, which is the official database
    // handle.  For pnDBGetTables() we want to keep the entire tables array
    // together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // It's good practice to name the table and column definitions you are
    // getting - $table and $column don't cut it in more complex modules
    $messagestable = $pntable['priv_msgs'];
    $messagescolumn = &$pntable['priv_msgs_column'];

    // Get item - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the Execute() command allows for simpler debug operation
    // if it is ever needed
	$sql = "SELECT $messagescolumn[msg_id],
				   $messagescolumn[msg_image],
				   $messagescolumn[subject] ,
				   $messagescolumn[from_userid],
				   $messagescolumn[to_userid],
				   $messagescolumn[msg_time],
				   $messagescolumn[msg_text],
				   $messagescolumn[read_msg]
		    FROM $messagestable 
			WHERE $messagescolumn[msg_id] = '" . (int)pnVarPrepForStore($msgid) . "' AND
				  $messagescolumn[to_userid] = '" . (int)pnVarPrepForStore($uid) . "'";
    $result =& $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    // Check for no rows found, and if so return
    if ($result->EOF) {
        return false;
    }

    // Obtain the item information from the result set
    list($msg_id, $msg_image, $subject, $from_userid, $to_userid, $msg_time, $msg_text, $read_msg) = $result->fields;

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Create the item array
	$item = array('msg_id' => $msg_id,
				  'msg_image' => $msg_image,
				  'subject' => $subject,
				  'from_userid' => $from_userid,
				  'to_userid' => $to_userid,
				  'msg_time' => $msg_time,
				  'msg_text' => $msg_text,
				  'read_msg' => $read_msg);

    // Return the item array
    return $item;
}

/**
 * utility function to count the number of items held by this module
 * 
 * @author	 Nathan Codding
 * @param 	 $args['userid'] userid to get private message count for
 * @param    $args['unread'] if set to true, return number of unread messages only
 * @return   integer   number of items held by this module
 */
function Messages_userapi_countitems($args)
{
    // Argument check - make sure that all required arguments are present, if
    // not then set an appropriate error message and return
    if (!isset($args['uid']) || !is_numeric($args['uid'])) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn() we
    // currently just want the first item, which is the official database
    // handle.  For pnDBGetTables() we want to keep the entire tables array
    // together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // It's good practice to name the table and column definitions you are
    // getting - $table and $column don't cut it in more complex modules
    $messagestable = $pntable['priv_msgs'];
    $messagescolumn = &$pntable['priv_msgs_column'];

    // Get items - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the Execute() command allows for simpler debug operation
    // if it is ever needed
    $sql = "SELECT count($messagescolumn[msg_id])
            FROM $messagestable
			WHERE $messagescolumn[to_userid] = '" . (int)pnVarPrepForStore($args['uid']) . "'";
    
    if (isset($args['unread']) && $args['unread']) {
        $sql .= "AND $messagescolumn[read_msg]='0'";
    }
            
    $result = $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

	// Get the result
    list($numitems) = $result->fields;

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Return the number of items
    return $numitems;
}

/**
 * utility function to count the number of items held by this module
 * 
 * @author	 Nathan Codding
 * @param 	 $args['userid'] userid to get private message count for
 * @return   integer   number of items held by this module
 */
function Messages_userapi_setreadstatus($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present, if
    // not then set an appropriate error message and return
    if (!isset($msgid) || !is_numeric($msgid)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Get datbase setup - note `that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn() we
    // currently just want the first item, which is the official database
    // handle.  For pnDBGetTables() we want to keep the entire tables array
    // together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // It's good practice to name the table and column definitions you are
    // getting - $table and $column don't cut it in more complex modules
    $messagestable = $pntable['priv_msgs'];
    $messagescolumn = &$pntable['priv_msgs_column'];

    // Get items - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the Execute() command allows for simpler debug operation
    // if it is ever needed
    $sql = "UPDATE $messagestable 
            SET $messagescolumn[read_msg]='1' 
            WHERE $messagescolumn[msg_id]= '" . (int)pnVarPrepForStore($msgid) . "'";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    // Return the number of items
    return true;
}

/**
 * delete a private message
 * 
 * @author	 Nathan Codding
 * @param 	 $args['msgid'] userid to get private message count for
 * @return   integer   number of items held by this module
 */
function Messages_userapi_delete($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present, if
    // not then set an appropriate error message and return
    if ((!isset($msgid) || !is_numeric($msgid)) ||
	   (!isset($uid) || !is_numeric($uid))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Get datbase setup - note `that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn() we
    // currently just want the first item, which is the official database
    // handle.  For pnDBGetTables() we want to keep the entire tables array
    // together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // It's good practice to name the table and column definitions you are
    // getting - $table and $column don't cut it in more complex modules
    $messagestable = $pntable['priv_msgs'];
    $messagescolumn = &$pntable['priv_msgs_column'];

    // Get items - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the Execute() command allows for simpler debug operation
    // if it is ever needed
	$sql = "DELETE FROM $messagestable 
			WHERE $messagescolumn[msg_id] = '" . (int)pnVarPrepForStore($msgid) ."'
			AND $messagescolumn[to_userid] = '" . (int)pnVarPrepForStore($uid) ."'";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    // Return the number of items
    return true;
}

/**
 * create a private message
 * 
 * @author	 Nathan Codding
 * @param 	 $args['to_userid'] id of the recipient
 * @param 	 $args['image'] image for the message
 * @param 	 $args['subject'] subject for the message
 * @param 	 $args['message'] text of the message
 * @return   integer   number of items held by this module
 */
function Messages_userapi_create($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present, if
    // not then set an appropriate error message and return
    if ((!isset($to_userid) || !is_numeric($to_userid)) ||
	    (!isset($subject)) ||
		(!isset($message))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Get datbase setup - note `that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn() we
    // currently just want the first item, which is the official database
    // handle.  For pnDBGetTables() we want to keep the entire tables array
    // together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // It's good practice to name the table and column definitions you are
    // getting - $table and $column don't cut it in more complex modules
    $messagestable = $pntable['priv_msgs'];
    $messagescolumn = &$pntable['priv_msgs_column'];

    // Get next ID in table - this is required prior to any insert that
    // uses a unique ID, and ensures that the ID generation is carried
    // out in a database-portable fashion
	$nextid = $dbconn->GenId($pntable['priv_msgs_column']);

	// set some defaults
	if(pnUserLoggedIn()) {
	    $from_uid = pnUserGetVar('uid');
	} else {
        $from_uid = 1; // anonymous
    }
	$time = date("Y-m-d H:i");
	if (!isset($image)) {
		$image = '';
	}

    // Get items - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the Execute() command allows for simpler debug operation
    // if it is ever needed
	$sql = "INSERT INTO $messagestable
			  ($messagescolumn[msg_id],
			   $messagescolumn[msg_image],
			   $messagescolumn[subject],
			   $messagescolumn[from_userid],
			   $messagescolumn[to_userid],
			   $messagescolumn[msg_time],
			   $messagescolumn[msg_text]) 
			VALUES
			  ('" . (int)pnVarPrepForStore($nextid). "',
			  '" . pnVarPrepForStore($image) . "',
			  '" . pnVarPrepForStore($subject) . "',
			  '" . (int)pnVarPrepForStore($from_uid) . "',
			  '" . (int)pnVarPrepForStore($to_userid) . "',
			  '" . pnVarPrepForStore($time) . "',
			  '" . pnVarPrepForStore($message) . "')";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    // Return the number of items
    return true;
}

/**
 * simple helper function to replace the [addsig] placeholder with the users signature
 * if it exists.
 * 
 * @author	 Frank Schummertz
 * @param 	 $args['signature'] the users signature, maybe empty
 * @param 	 $args['message'] the message text
 * @return   string   the new message text
 */
function Messages_userapi_replacesignature($args)
{
    extract($args);
    unset($args);
    
    if(!empty($signature)) {
        return eregi_replace("\[addsig]", "<br />-----------------<br />" . nl2br($signature), $message);
       } else {
        return eregi_replace("\[addsig]", "", $message);
       }
}

?>