<?php
// $Id: pntables.php 13715 2004-06-02 15:08:51Z markwest $
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
// Original Author of file: Mark West
// Purpose of file:  Table information for Messages module
// ----------------------------------------------------------------------

/**
 * Messages Module
 * 
 * Purpose of file:  Initialisation functions for Messages 
 *
 * @package      PostNuke_Miscellaneous_Modules
 * @subpackage   Messages
 * @version      $Id: pntables.php 13715 2004-06-02 15:08:51Z markwest $
 * @author       Mark West
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

/**
 * This function is called internally by the core whenever the module is
 * loaded.  It adds in the information
 */
function Messages_pntables()
{
    // Initialise table array
    $pntable = array();

    // Get the name for the template item table.  This is not necessary
    // but helps in the following statements and keeps them readable
    $priv_msgs = pnConfigGetVar('prefix') . '_priv_msgs';

    // Set the table name
    $pntable['priv_msgs'] = $priv_msgs;

    // Set the column names.  Note that the array has been formatted
    // on-screen to be very easy to read by a user.
	$pntable['priv_msgs_column'] = array ('msg_id'      => $priv_msgs . '.pn_msg_id',
										  'msg_image'   => $priv_msgs . '.pn_msg_image',
										  'subject'     => $priv_msgs . '.pn_subject',
										  'from_userid' => $priv_msgs . '.pn_from_userid',
										  'to_userid'   => $priv_msgs . '.pn_to_userid',
										  'msg_time'    => $priv_msgs . '.pn_msg_time',
										  'msg_text'    => $priv_msgs . '.pn_msg_text',
										  'read_msg'    => $priv_msgs . '.pn_read_msg');

    // Return the table information
    return $pntable;
}

?>