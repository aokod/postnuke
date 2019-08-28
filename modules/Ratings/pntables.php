<?php
// $Id: pntables.php 15326 2005-01-10 15:25:04Z markwest $
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
// Purpose of file:  Table information for ratings module
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Utility_Modules
 * @subpackage Ratings
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * Get ratings pntable array
 * @author Jim McDonald
 * @return array
 */
function ratings_pntables()
{
    // Initialise table array
    $pntable = array();

    // Name for ratings database entities
    $ratings = pnConfigGetVar('prefix') . '_ratings';

    // Table name
    $pntable['ratings'] = $ratings;

    // Column names
    $pntable['ratings_column'] = array('rid' => $ratings . '.pn_rid',
                                       'module' => $ratings . '.pn_module',
                                       'itemid' => $ratings . '.pn_itemid',
                                       'ratingtype' => $ratings . '.pn_ratingtype',
                                       'rating' => $ratings . '.pn_rating',
                                       'numratings' => $ratings . '.pn_numratings');

    // Name for ratings database log
    $ratingslog = pnConfigGetVar('prefix') . '_ratingslog';

    // Table name
    $pntable['ratingslog'] = $ratingslog;

    // Column names
    $pntable['ratingslog_column'] = array('id' => $ratingslog . '.pn_id',
                                          'ratingid' => $ratingslog . '.pn_ratingid',
                                          'rating' => $ratingslog . '.pn_rating');

    // Return table information
    return $pntable;
}

?>