<?php
// $Id: pninit.php 15915 2005-03-04 15:42:33Z markwest $
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

/**
 * @package PostNuke_System_Modules
 * @subpackage Modules
 * @license http://www.gnu.org/copyleft/gpl.html
*/

/**
 * initialise the Modules module
 * 
 * This function is only ever called once during the lifetime of a particular
 * module instance.
 * This function MUST exist in the pninit file for a module
 * 
 * @author       Mark West
 * @return       bool       true on success, false otherwise
 */
function modules_init()
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
    $modulestable  = &$pntable['modules'];
    $modulescolumn = &$pntable['modules_column'];

    // Create the sql using adodb's data dictionary.
	// The formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.
    $sql = "
			pn_id I(11) PRIMARY AUTO,
			pn_name C(64) NOTNULL DEFAULT '',
			pn_type I(6) NOTNULL,
			pn_displayname C(64) NOTNULL DEFAULT '',
			pn_description C(255) NOTNULL DEFAULT '',
			pn_regid I(11) NOTNULL DEFAULT '0',
			pn_directory C(64) NOTNULL DEFAULT '',
			pn_version C(10) NOTNULL DEFAULT '0',
			pn_admin_capable I(1) NOTNULL DEFAULT '0',
			pn_user_capable I(1) NOTNULL DEFAULT '0',
			pn_state I(1) NOTNULL DEFAULT '0'
    ";
	
    // create the data dictionaries SQL array
	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->CreateTableSQL($modulestable, $sql, $taboptarray);

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
    $modulevarstable  = &$pntable['module_vars'];
    $modulevarscolumn = &$pntable['module_vars_column'];

    // Create the sql using adodb's data dictionary.
	// The formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.
    $sql = "
        pn_id I(11)         PRIMARY    AUTO,
        pn_modname          C(64)     PRIMARY    DEFAULT '',
        pn_name             C(64)    PRIMARY    DEFAULT '',
        pn_value            X2
    ";
	
    // create the data dictionaries SQL array
	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->CreateTableSQL($modulevarstable, $sql, $taboptarray);

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
    $hookstable  = &$pntable['hooks'];
    $hookscolumn = &$pntable['hooks_column'];

    // Create the sql using adodb's data dictionary.
	// The formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.
    // Columns definition
    $sql = "
			pn_id I(11) AUTO PRIMARY,
			pn_object C(64) NOTNULL,
			pn_action C(64) NOTNULL,
			pn_smodule C(64),
			pn_stype C(64),
			pn_tarea C(64) NOTNULL,
			pn_tmodule C(64) NOTNULL,
			pn_ttype C(64) NOTNULL,
			pn_tfunc C(64) NOTNULL
    ";
	
    // create the data dictionaries SQL array
	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->CreateTableSQL($hookstable, $sql, $taboptarray);

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
    pnModSetVar('Modules', 'itemsperpage', 25);
	pnConfigSetVar('loadlegacy', 0);

    // create the default data for the modules module
	modules_defaultdata();

    // Initialisation successful
    return true;
}


/**
 * upgrade the modules module from an old version
 * 
 * This function can be called multiple times
 * This function MUST exist in the pninit file for a module
 * 
 * @author       Mark West
 * @return       bool       true on success, false otherwise
 */
function modules_upgrade($oldversion)
{
    // Upgrade dependent on old version number
    switch($oldversion) {
	    // version 2.0 was supplied with PN .7x
        case 2.0:
			pnModSetVar('Modules', 'itemsperpage', 25);
			// unlike a new install we'll assume that we need legacy support on
			pnConfigSetVar('loadlegacy', 1);
			break;
	}

    // Update successful
    return true;
}


/**
 * delete the modules module
 * 
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * This function MUST exist in the pninit file for a module
 * 
 * Since the modules module should never be deleted we'all always return false here
 * @author       Mark West
 * @return       bool       false
 */
function modules_delete()
{
    // Deletion not allowed
    return false;
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
function modules_defaultdata()
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
    $modulestable  = &$pntable['modules'];
    $modulescolumn = &$pntable['modules_column'];
	
	// modules module
	$record = array();
	$record['pn_id']            = $dbconn->GenID($modulestable);
	$record['pn_name']          = ''._MODULES_1_a.'';
	$record['pn_type']          = ''._MODULES_1_b.'';
	$record['pn_displayname']   = ''._MODULES_1_c.'';
	$record['pn_description']   = ''._MODULES_1_d.'';
	$record['pn_regid']         = ''._MODULES_1_e.'';
	$record['pn_directory']     = ''._MODULES_1_f.'';
	$record['pn_version']       = ''._MODULES_1_g.'';
	$record['pn_admin_capable'] = ''._MODULES_1_h.'';
	$record['pn_user_capable']  = ''._MODULES_1_i.'';
	$record['pn_state']         = ''._MODULES_1_j.'';

	// Select an empty record from the database
	$sqltest = 'SELECT * FROM  '. $modulestable . ' WHERE '.$modulescolumn['id'].' = -1';
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