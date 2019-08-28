<?php
// File: $Id: pntables.php 15630 2005-02-04 06:35:42Z jorg $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// Based on:
// Thatware - http://thatware.org/
// PHP-NUKE Web Portal System - http://phpnuke.org/
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
// Original Author of file: Xiaoyu Huang [class007]
// Purpose of file: tables define for ephemerids module
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Content_Modules
 * @subpackage Ephemerids
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * Populate pntables array for ephemerids module
 * @author Xiaoyu Huang
 * @return array pntable array
 */
function Ephemerids_pntables()
{
    // Initialise table array
    $pntable = array();

    $prefix = pnConfigGetVar('prefix');

    // Table name
    $ephem = $prefix . '_ephem';
    $pntable['ephem'] = $ephem;
    $pntable['ephem_column'] = array ('eid'       => $ephem . '.pn_eid',
                                      'did'       => $ephem . '.pn_did',
                                      'mid'       => $ephem . '.pn_mid',
                                      'yid'       => $ephem . '.pn_yid',
                                      'content'   => $ephem . '.pn_content',
                                      'language'  => $ephem . '.pn_language');
    
    return $pntable;
}

?>