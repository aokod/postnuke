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
// Purpose of file:  Initialisation functions for Admin
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Admin
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * initialise the Admin module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * @author Mark West
 * @return bool true if initialisation succcesful, false otherwise
 */
function Admin_init()
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
    $admincategorytable = $pntable['admin_category'];
    $admincategorycolumn = &$pntable['admin_category_column'];

    // Create the table - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating out
    // the SQL statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "
            pn_cid I(10) NOTNULL AUTO PRIMARY,
            pn_name C(32) NOTNULL DEFAULT '',
            pn_description C(254) NOTNULL DEFAULT ''
		   ";

    // create the data dictionaries SQL array
	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->CreateTableSQL($admincategorytable, $sql, $taboptarray);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result != 2) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED);
        return false;
    }

    // It's good practice to name the table and column definitions you
    // are getting - $table and $column don't cut it in more complex
    // modules
    $adminmoduletable = $pntable['admin_module'];
    $adminmodulecolumn = &$pntable['admin_module_column'];

    // Create the table - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating out
    // the SQL statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "
            pn_mid I(10) NOTNULL DEFAULT '0',
            pn_cid I(10) NOTNULL DEFAULT '0'
			";

    // create the data dictionaries SQL array
	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->CreateTableSQL($adminmoduletable, $sql, $taboptarray);

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
    pnModSetVar('Admin', 'modulesperrow', 5);
    pnModSetVar('Admin', 'itemsperpage', 25);
	pnModSetVar('Admin', 'defaultcategory', 5);
	pnModSetVar('Admin', 'modulestylesheet', 'navtabs.css');
	pnModSetVar('Admin', 'admingraphic', 1);
	pnModSetVar('Admin', 'startcategory', 1);
    pnModSetVar('Admin', 'ignoreinstallercheck', 0);

    // create the default data for the modules module
	Admin_defaultdata();

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
function Admin_upgrade($oldversion)
{
    // Upgrade dependent on old version number
    switch($oldversion) {
	    // version 0.1 was supplied with all .7x versions of PN
        case 0.1:
			Admin_init();
            // At the end of the successful completion of this function we
            // recurse the upgrade to handle any other upgrades that need
            // to be done.  This allows us to upgrade from any version to
            // the current version with ease
            return Admin_upgrade(1.0);
		case 1.0:
			// move the admingraphic config var to a module var
			pnModSetVar('Admin', 'admingraphic', pnConfigGetVar('admingraphic'));
			pnConfigDelVar('admingraphic');
			// set the remaining new vars
			pnModSetVar('Admin', 'modulesperrow', 5);
			pnModSetVar('Admin', 'itemsperpage', 25);
			pnModSetVar('Admin', 'defaultcategory', 5);
			pnModSetVar('Admin', 'modulestylesheet', 'navtabs.css');
			pnModSetVar('Admin', 'startcategory', 1);
		    pnModSetVar('Admin', 'ignoreinstallercheck', 0);
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
function Admin_delete()
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
    $sqlarray = $dict->DropTableSQL($pntable['admin_module']);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result != 2) {
        pnSessionSetVar('errormsg', _DELETETABLEFAILED);
        return false;
    }

	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->DropTableSQL($pntable['admin_category']);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result != 2) {
        pnSessionSetVar('errormsg', _DELETETABLEFAILED);
        return false;
    }

    // Delete any module variables
    pnModDelVar('Admin', 'modulesperrow');
    pnModDelVar('Admin', 'itemsperpage');
	pnModDelVar('Admin', 'defaultcategory');
	pnModDelVar('Admin', 'modulestylesheet');
	pnModDelVar('Admin', 'admingraphic');
    pnModDelVar('Admin', 'ignoreinstallercheck');

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
function Admin_defaultdata()
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
    $admincategorytable = $pntable['admin_category'];
    $admincategorycolumn = &$pntable['admin_category_column'];

	$record = array();
	$record['pn_cid']         = $dbconn->GenID($admincategorytable);
	$record['pn_name']        = ''._ADMIN_CATEGORY_00_a.'';
	$record['pn_description'] = ''._ADMIN_CATEGORY_00_b.'';
	// Select an empty record from the database
	$sqltest = 'SELECT * FROM  '. $admincategorytable . ' WHERE '.$admincategorycolumn['cid'].' = -1';
	$rs =& $dbconn->Execute($sqltest);

	// Pass the empty recordset and the array containing the data to insert
	// into the GetInsertSQL function.
	$insertSQL = $dbconn->GetInsertSQL($rs, $record);

    // Create the new record;
	$dbconn->StartTrans();
	$dbconn->Execute($insertSQL);
	$dbconn->CompleteTrans();

	$record = array();
	$record['pn_cid']         = $dbconn->GenID($admincategorytable);
	$record['pn_name']        = ''._ADMIN_CATEGORY_01_a.'';
	$record['pn_description'] = ''._ADMIN_CATEGORY_01_b.'';
	// Select an empty record from the database
	$sqltest = 'SELECT * FROM  '. $admincategorytable . ' WHERE '.$admincategorycolumn['cid'].' = -1';
	$rs =& $dbconn->Execute($sqltest);

	// Pass the empty recordset and the array containing the data to insert
	// into the GetInsertSQL function.
	$insertSQL = $dbconn->GetInsertSQL($rs, $record);

    // Create the new record;
	$dbconn->StartTrans();
	$dbconn->Execute($insertSQL);
	$dbconn->CompleteTrans();

	$record = array();
	$record['pn_cid']         = $dbconn->GenID($admincategorytable);
	$record['pn_name']        = ''._ADMIN_CATEGORY_02_a.'';
	$record['pn_description'] = ''._ADMIN_CATEGORY_02_b.'';
	// Select an empty record from the database
	$sqltest = 'SELECT * FROM  '. $admincategorytable . ' WHERE '.$admincategorycolumn['cid'].' = -1';
	$rs =& $dbconn->Execute($sqltest);

	// Pass the empty recordset and the array containing the data to insert
	// into the GetInsertSQL function.
	$insertSQL = $dbconn->GetInsertSQL($rs, $record);

    // Create the new record;
	$dbconn->StartTrans();
	$dbconn->Execute($insertSQL);
	$dbconn->CompleteTrans();

	$record = array();
	$record['pn_cid']         = $dbconn->GenID($admincategorytable);
	$record['pn_name']        = ''._ADMIN_CATEGORY_03_a.'';
	$record['pn_description'] = ''._ADMIN_CATEGORY_03_b.'';
	// Select an empty record from the database
	$sqltest = 'SELECT * FROM  '. $admincategorytable . ' WHERE '.$admincategorycolumn['cid'].' = -1';
	$rs =& $dbconn->Execute($sqltest);

	// Pass the empty recordset and the array containing the data to insert
	// into the GetInsertSQL function.
	$insertSQL = $dbconn->GetInsertSQL($rs, $record);

    // Create the new record;
	$dbconn->StartTrans();
	$dbconn->Execute($insertSQL);
	$dbconn->CompleteTrans();

	$record = array();
	$record['pn_cid']         = $dbconn->GenID($admincategorytable);
	$record['pn_name']        = ''._ADMIN_CATEGORY_04_a.'';
	$record['pn_description'] = ''._ADMIN_CATEGORY_04_b.'';
	// Select an empty record from the database
	$sqltest = 'SELECT * FROM  '. $admincategorytable . ' WHERE '.$admincategorycolumn['cid'].' = -1';
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