<?php
// $Id: pninit.php 15762 2005-02-19 19:55:07Z markwest $
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
// Purpose of file:  Initialisation functions for Admin Messages
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Admin_Messages
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * initialise the Admin module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * @author Mark West
 * @return bool true if initialisation succcesful, false otherwise
 */
function Admin_Messages_init()
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
    $adminmessagestable = $pntable['message'];
    $adminmessagescolumn = &$pntable['message_column'];

    // Create the table - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating out
    // the SQL statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "
			pn_mid I(11) PRIMARY AUTO,
			pn_title C(100) NOTNULL DEFAULT '',
			pn_content X2 NOTNULL,
			pn_date C(14) NOTNULL DEFAULT '',
			pn_expire I(7) NOTNULL DEFAULT '0',
			pn_active I(4) NOTNULL DEFAULT '1',
			pn_view I(1) NOTNULL DEFAULT '1',
			pn_language C(30) NOTNULL DEFAULT ''
           ";

    // create the data dictionaries SQL array
	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->CreateTableSQL($adminmessagestable, $sql, $taboptarray);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result != 2) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED);
        return false;
    }

	// Set a default value for a module variable
	pnModSetVar('Admin_Messages', 'itemsperpage', 25);

    // create the default data for the modules module
	Admin_Messages_defaultdata();

    // Initialisation successful
    return true;
}

/**
 * upgrade the Admin module from an old version
 * This function can be called multiple times
 * @author Mark West
 * @param int $oldversion previous to upgrade from
 * @return bool true if upgrade succcesful, false otherwise
 */
function Admin_Messages_upgrade($oldversion)
{
    // Upgrade dependent on old version number
    switch($oldversion) {
		// version 1.2 was shipped with PN .7x
        case 1.2:
			pnModSetVar('Admin_Messages', 'itemsperpage', 25);
			break;
	}

    // Update successful
    return true;
}

/**
 * delete the Admin module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * @author Mark West
 * @return bool true if deletetion succcesful, false otherwise
 */
function Admin_Messages_delete()
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
    $sqlarray = $dict->DropTableSQL($pntable['message']);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result != 2) {
        pnSessionSetVar('errormsg', _DELETETABLEFAILED);
        return false;
    }

	// Delete module variable
	pnModDelVar('Admin_Messages', 'itemsperpage');

    // Deletion successful
    return true;
}

/**
 * create the default data for the modules module
 * 
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * 
 * @author       Mark West
 * @return       bool       false
 */
function Admin_Messages_defaultdata()
{
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
    $adminmessagestable = $pntable['message'];
    $adminmessagescolumn = &$pntable['message_column'];

	$record = array();
	$record['pn_mid']      = $dbconn->GenID($adminmessagestable);
	$record['pn_title']    = ''._MESSAGE_00_a.'';
	$record['pn_content']  = ''._MESSAGE_00_b.'';
	$record['pn_date']     = time();
	$record['pn_expire']   = ''._MESSAGE_00_d.'';
	$record['pn_active']   = ''._MESSAGE_00_e.'';
	$record['pn_view']     = ''._MESSAGE_00_f.'';
	$record['pn_language'] = ''._MESSAGE_00_g.'';

	// Select an empty record from the database
	$sqltest = 'SELECT * FROM  '. $adminmessagestable . ' WHERE '.$adminmessagescolumn['mid'].' = -1';
	$rs =& $dbconn->Execute($sqltest);

	// Pass the empty recordset and the array containing the data to insert
	// into the GetInsertSQL function.
	$insertSQL = $dbconn->GetInsertSQL($rs, $record);

    // Create the new record;
	$dbconn->StartTrans();
	$dbconn->Execute($insertSQL);
	$dbconn->CompleteTrans();

}

?>