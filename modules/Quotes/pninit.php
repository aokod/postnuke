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
// Original Author of file: 
// Purpose of file:  init for quotes module
// ----------------------------------------------------------------------

/**
 * @package PostNuke_ResourcePack_Modules
 * @subpackage Quotes
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * Init quotes module
 * @author Erik Slooff
 * @return true if init successful, false otherwise
 * @todo data dictionary seems only able to create long text fields
 */
function quotes_init()
{
    // Get database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Create a new data dictionary object
    $dict = NewDataDictionary($dbconn);

    // Define any table specific options
	$taboptarray =& pnDBGetTableOptions();

    // Create tables
    $quotestable = $pntable['quotes'];
    $quotescolumn = &$pntable['quotes_column'];

    // Create the table - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating out
    // the SQL statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "
            pn_qid I(10) NOTNULL AUTO PRIMARY,
            pn_quote X,
            pn_author C(150) NOTNULL DEFAULT ''
		   ";

    // create the data dictionaries SQL array
	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->CreateTableSQL($quotestable, $sql, $taboptarray);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result != 2) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED);
        return false;
    }

    // Set up module variables
	pnModSetVar('Quotes', 'itemsperpage', 25);

    // Initialisation successful
    return true;
}

/**
 * Upgrade quotes module
 * @author Erik Slooff
 * @return true if init successful, false otherwise
 */
function quotes_upgrade($oldversion)
{
    // Upgrade dependent on old version number
    switch($oldversion) {
        case 1.3:
            // version 1.3 was shipped with .72x/.75
			pnModSetVar('Quotes', 'itemsperpage', 25);
			// we don't need these variables anymore
			pnModDelVar('Quotes', 'detail');
			pnModDelVar('Quotes', 'table');
            break;
    }

    // upgrade success
    return true;
}

/**
 * Delete quotes module
 * @author Erik Slooff
 * @return true if init successful, false otherwise
 */
function quotes_delete()
{
    // Get database information
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
    $sqlarray = $dict->DropTableSQL($pntable['quotes']);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result != 2) {
        pnSessionSetVar('errormsg', _DELETETABLEFAILED);
        return false;
    }

    // Delete module variables
	pnModDelVar('Quotes', 'itemsperpage');

    // Deletion successful
    return true;
}

?>