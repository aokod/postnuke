<?php
// $Id: pntables.php 13213 2004-04-22 12:28:05Z drak $
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
// Purpose of file:  Table information for Admin Messages module
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Admin_Messages
 * @license http://www.gnu.org/copyleft/gpl.html
*/

/**
 * This function is called internally by the core whenever the module is
 * loaded.  It adds in the information
 */
function Admin_Messages_pntables()
{
    // Initialise table array
    $pntable = array();

    // Get the name for the template item table.  This is not necessary
    // but helps in the following statements and keeps them readable
    $message = pnConfigGetVar('prefix') . '_message';

    // Set the table name
    $pntable['message'] = $message;

    // Set the column names.  Note that the array has been formatted
    // on-screen to be very easy to read by a user.
    $pntable['message_column'] = array ('mid'         => $message . '.pn_mid',
                                        'title'       => $message . '.pn_title',
                                        'content'     => $message . '.pn_content',
                                        'date'        => $message . '.pn_date',
                                        'expire'      => $message . '.pn_expire',
                                        'active'      => $message . '.pn_active',
                                        'view'        => $message . '.pn_view',
                                        'mlanguage'   => $message . '.pn_language',
                                        'language'    => $message . '.pn_language');

    // Return the table information
    return $pntable;
}

?>