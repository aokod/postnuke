<?php
// $Id: pninit.php 15762 2005-02-19 19:55:07Z markwest $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
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
// Original Author of file: Xiaoyu Huang [class007]
// Purpose of file:  init for ephemerids module
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Content_Modules
 * @subpackage Ephemerids
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * Initialise ephemerids module
 * @author Xiaoyu Huang
 * @todo DDL creates longtext (how to to create smaller text field?)
 * @return bool true on success, false on failiure
 */
function Ephemerids_init()
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
    $ephem_table = $pntable['ephem'];
    $ephem_column = &$pntable['ephem_column'];

    // Create the table - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating out
    // the SQL statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "
            pn_eid I(11) NOTNULL AUTO PRIMARY,
            pn_did I1(2) NOTNULL DEFAULT '0',
            pn_mid I1(2) NOTNULL DEFAULT '0',
            pn_yid I(4) NOTNULL DEFAULT '0',
			pn_content X NOTNULL,
			pn_language C(30) NOTNULL DEFAULT ''
		   ";

    // create the data dictionaries SQL array
	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->CreateTableSQL($ephem_table, $sql, $taboptarray);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result != 2) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED);
        return false;
    }

    // Set up config variables
    pnModSetVar('Ephemerids', 'itemsperpage', 10);

    // Initialisation successful
    return true;
}

/**
 * upgrade ephemerids module
 * @author Xiaoyu Huang
 * @return bool true on success, false on failiure
 */
function Ephemerids_upgrade($oldversion)
{
    // Upgrade dependent on old version number
    switch($oldversion) {
        case 1.2:
			// version 1.2 shipped with postnuke .72x/.75
			pnModSetVar('Ephemerids', 'itemsperpage', 25);
            break;
    }
    
    // upgrade success
    return true;
}

/**
 * delete ephemerids module
 * @author Xiaoyu Huang
 * @return bool true on success, false on failiure
*/
function Ephemerids_delete()
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
    $sqlarray = $dict->DropTableSQL($pntable['ephem']);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result != 2) {
        pnSessionSetVar('errormsg', _DELETETABLEFAILED);
        return false;
    }

    // Delete module variables
    pnModDelVar('Ephemerids', 'itemsperpage');

    // Deletion successful
    return true;
}

?>