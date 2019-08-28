<?php
// $Id: pninit.php 15762 2005-02-19 19:55:07Z markwest $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2002 by the PostNuke Development Team.
// http://www.postnuke.com/
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
// Purpose of file:  Initialisation functions for autolinks
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Utility_Modules
 * @subpackage Autolinks
 * @license http://www.gnu.org/copyleft/gpl.html
*/

/**
 * Initialise the autolinks module
 * @author Jim McDonald
 * @return true if init sucess, false otherwise
 */
function autolinks_init()
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
    $autolinkstable = $pntable['autolinks'];
    $autolinkscolumn = $pntable['autolinks_column'];

    // Create the table - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating out
    // the SQL statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "
            pn_lid I(11) NOTNULL AUTO PRIMARY,
            pn_keyword C(100) NOTNULL DEFAULT '',
            pn_title C(100) NOTNULL DEFAULT '',
            pn_url C(200) NOTNULL DEFAULT '',
            pn_comment C(200) NOTNULL DEFAULT ''
		   ";

    // create the data dictionaries SQL array
	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->CreateTableSQL($autolinkstable, $sql, $taboptarray);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result != 2) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED);
        return false;
    }

    // Let's create an index on the keyword field
    // Create the index
	$sqlarray = $dict->CreateIndexSQL($autolinkstable . '_index', $autolinkstable, 'pn_keyword');
	$result = $dict->ExecuteSQLArray($sqlarray);

    // Set up module variables
    pnModSetVar('Autolinks', 'itemsperpage', 20);
    pnModSetVar('Autolinks', 'linkfirst', 1);
    pnModSetVar('Autolinks', 'invisilinks', 0);
    pnModSetVar('Autolinks', 'newwindow', 1);

    // Set up module hooks
    if (!pnModRegisterHook('item',
                           'transform',
                           'API',
                           'Autolinks',
                           'user',
                           'transform')) {
        pnSessionSetVar('errormsg', _REGISTERFAILED);
        return false;
    }

    // Initialisation successful
    return true;
}

/**
 * upgrade the autolinks module from an old version
 * @author Jim McDonald
 * @return true if upgrade success, false otherwise
 */
function autolinks_upgrade($oldversion)
{
    return true;
}

/**
 * delete the autolinks module
 * @author Jim McDonald
 * @return true if delete success, false otherwise
 */
function autolinks_delete()
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
    $sqlarray = $dict->DropTableSQL($pntable['autolinks']);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result != 2) {
        pnSessionSetVar('errormsg', _DELETETABLEFAILED);
        return false;
    }

    // Remove module hooks
    if (!pnModUnregisterHook('item',
                             'transform',
                             'API',
                             'Autolinks',
                             'user',
                             'transform')) {
        pnSessionSetVar('errormsg', _UNREGISTERFAILED);
        return false;
    }

    // Remove module variables
    pnModDelVar('Autolinks', 'invisilinks');
    pnModDelVar('Autolinks', 'linkfirst');
    pnModDelVar('Autolinks', 'itemsperpage');
    pnModDelVar('Autolinks', 'newwindow');

    // Deletion successful
    return true;
}

?>