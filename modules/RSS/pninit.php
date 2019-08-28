<?php
// $Id: pninit.php 16659 2005-08-21 11:01:23Z markwest $
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
// Purpose of file:  Initialisation functions for RSS
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Content_Modules
 * @subpackage RSS
 * @license http://www.gnu.org/copyleft/gpl.html
*/

/**
 * initialise the RSS module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 */
function RSS_init()
{
    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn()
    // we currently just want the first item, which is the official
    // database handle.  For pnDBGetTables() we want to keep the entire
    // tables array together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Create a new data dictionary object
    $dict = NewDataDictionary($dbconn);

    // Define any table specific options
	$taboptarray =& pnDBGetTableOptions();

    // It's good practice to name the table and column definitions you
    // are getting - $table and $column don't cut it in more complex
    // modules
    $RSStable = $pntable['RSS'];
    $RSScolumn = &$pntable['RSS_column'];

    // Create the table - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating out
    // the SQL statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "
            pn_fid I(10) NOTNULL AUTOINCREMENT PRIMARY,
            pn_name C(32) NOTNULL DEFAULT '',
            pn_url C(255) NOTNULL DEFAULT ''
		   ";

    // create the data dictionaries SQL array
	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->CreateTableSQL($RSStable, $sql, $taboptarray);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result != 2) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED);
        return false;
    }

    // Set up an initial value for a module variable.  Note that all module
    // variables should be initialised with some value in this way rather
    // than just left blank, this helps the user-side code and means that
    // there doesn't need to be a check to see if the variable is set in
    // the rest of the code as it always will be
    pnModSetVar('RSS', 'bold', 0);
    pnModSetVar('RSS', 'openinnewwindow', 0);
    pnModSetVar('RSS', 'itemsperpage', 10);
	pnModSetVar('RSS', 'cachedirectory', 'rss');
    pnModSetVar('RSS', 'cacheinterval', 180);

    // Initialisation successful
    return true;
}

/**
 * upgrade the RSS module from an old version
 * This function can be called multiple times
 */
function RSS_upgrade($oldversion)
{
    // Update successful
    return true;
}

/**
 * delete the RSS module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 */
function RSS_delete()
{
    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn()
    // we currently just want the first item, which is the official
    // database handle.  For pnDBGetTables() we want to keep the entire
    // tables array together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Create a new data dictionary object
    $dict = NewDataDictionary($dbconn);

    // Drop the table - for such a simple command the advantages of separating
    // out the SQL statement from the Execute() command are minimal, but as
    // this has been done elsewhere it makes sense to stick to a single method
    // create the data dictionaries SQL array

	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->DropTableSQL($pntable['RSS']);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result != 2) {
        pnSessionSetVar('errormsg', _DELETETABLEFAILED);
        return false;
    }

    // Delete any module variables
	pnModDelVar('RSS', 'cachedirectory');
    pnModDelVar('RSS', 'cacheinterval');
    pnModDelVar('RSS', 'itemsperpage');
    pnModDelVar('RSS', 'openinnewwindow');
    pnModDelVar('RSS', 'bold');

    // Deletion successful
    return true;
}

?>