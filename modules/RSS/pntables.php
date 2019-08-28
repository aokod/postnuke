<?php
// $Id: pntables.php 13719 2004-06-02 16:21:28Z markwest $
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
// Purpose of file:  Table information for RSS module
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Content_Modules
 * @subpackage RSS
 * @license http://www.gnu.org/copyleft/gpl.html
*/

/**
 * This function is called internally by the core whenever the module is
 * loaded.  It adds in the information
 */
function RSS_pntables()
{
    // Initialise table array
    $pntable = array();

    // Get the name for the RSS item table.  This is not necessary
    // but helps in the following statements and keeps them readable
    $RSS = pnConfigGetVar('prefix') . '_RSS';

    // Set the table name
    $pntable['RSS'] = $RSS;

    // Set the column names.  Note that the array has been formatted
    // on-screen to be very easy to read by a user.
    $pntable['RSS_column'] = array('fid'    => $RSS . '.pn_fid',
                                   'name'   => $RSS . '.pn_name',
                                   'url'    => $RSS . '.pn_url');

    // Return the table information
    return $pntable;
}

?>