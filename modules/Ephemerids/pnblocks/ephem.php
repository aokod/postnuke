<?php 
// File: $Id: ephem.php 15630 2005-02-04 06:35:42Z jorg $
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

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Mark West
// Purpose of file: Display emphemerid block
// ----------------------------------------------------------------------

/**
 * initialise block
 */
function Ephemerids_ephemblock_init()
{
    // Security
    pnSecAddSchema('Ephemeridsblock::', 'Block title::');
}

/**
 * get information on block
 */
function Ephemerids_ephemblock_info()
{
    // Values
    return array('text_type' => 'Ephemerids',
                 'module' => 'Ephemerids',
                 'text_type_long' => 'Ephemerids',
                 'allow_multiple' => true,
                 'form_content' => false,
                 'form_refresh' => false,
                 'show_preview' => true);
}

function Ephemerids_ephemblock_display($blockinfo)
{
    // Security check
    if (!pnSecAuthAction(0, 'Ephemeridsblock::', "$blockinfo[title]::", ACCESS_READ)) {
        return;
    }

    // Load modules db information
    pnModDBInfoLoad('Ephemerids');

    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn()
    // we currently just want the first item, which is the official
    // database handle.  For pnDBGetTables() we want to keep the entire
    // tables array together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // It's good practice to name the table and column definitions you
    // are getting - $table and $column don't cut it in more complex
    // modules
    $Ephemeridstable = $pntable['ephem'];
    $Ephemeridscolumn = &$pntable['ephem_column'];

    // Get current language
    $currentlang = pnUserGetLang();

    if (pnConfigGetVar('multilingual') == 1) {
        $querylang = "AND ($Ephemeridscolumn[language]='" . pnVarPrepForStore($currentlang) . "'
                      OR $Ephemeridscolumn[language]='')";
    } else {
        $querylang = "";
    } 
    
    // get todays date
    $today = getdate();
    $eday = $today['mday'];
    $emonth = $today['mon'];


    // Get items - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the SelectLimit() command allows for simpler debug
    // operation if it is ever needed
    $sql = "SELECT $Ephemeridscolumn[yid],
                   $Ephemeridscolumn[content]
            FROM $Ephemeridstable
            WHERE $Ephemeridscolumn[did]='" . pnVarPrepForStore($eday) . "'
            AND $Ephemeridscolumn[mid]='" . pnVarPrepForStore($emonth) . "' $querylang";
    $result =& $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _GETFAILED);
        return false;
    }

    // Check for no rows found, and if so return
    if ($result->EOF) {
        return false;
    }
    
    // Create output object - this object will store all of our output so that
    // we can return it easily when required
	$pnRender =& new pnRender('Ephemerids');
	
    $items = array();
    while (list($yid, $content) = $result->fields) {
        $result->MoveNext();
		$items[] = array('year' => $yid,
		                 'event' => nl2br($content));
    } 
    $pnRender->assign('items', $items);	

    if (empty($blockinfo['title'])) {
        $blockinfo['title'] = _EPHEMERIDS;
    } 

    // Populate block info and pass to theme
    $blockinfo['content'] = $pnRender->fetch('ephemerids_block_ephem.htm');
	
    return themesideblock($blockinfo);
}

?>