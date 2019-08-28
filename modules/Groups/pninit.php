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
// Purpose of file:  Initialisation functions for Groups Module
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Groups
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * initialise the groups module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * @author Mark West
 * @return bool true if initialisation succesful, false otherwise
 */
function Groups_init()
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
    $groupstable  = &$pntable['groups'];
    $groupscolumn = &$pntable['groups_column'];

    // Create the sql using adodb's data dictionary.
	// The formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.
    $sql = "
			pn_gid I(11) AUTO PRIMARY,
			pn_name C(255) NOTNULL DEFAULT ''
    ";
	
    // create the data dictionaries SQL array
	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->CreateTableSQL($groupstable, $sql, $taboptarray);

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
    $groupmembershiptable  = &$pntable['group_membership'];
    $groupmembershipcolumn = &$pntable['group_membership_column'];

    // Create the sql using adodb's data dictionary.
	// The formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.
    $sql = "
			pn_gid I(11) NOTNULL DEFAULT '0',
			pn_uid I(11) NOTNULL DEFAULT '0'
		";
	
    // create the data dictionaries SQL array
	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->CreateTableSQL($groupmembershiptable, $sql, $taboptarray);

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
    pnModSetVar('Groups', 'itemsperpage', 25);
	pnModSetVar('Groups', 'defaultgroup', _GROUPS_1_a);

    // create the default data for the modules module
	groups_defaultdata();

    // Initialisation successful
    return true;
}

/**
 * upgrade the groups module from an old version
 * This function can be called multiple times
 * @author Mark West
 * @param int $oldversion version to upgrade from
 * @return bool true if upgrade succesful, false otherwise */
function Groups_upgrade($oldversion)
{
    // Upgrade dependent on old version number
    switch($oldversion) {
		// version 0.1 shipped with PN .7x
        case 0.1:
			pnModSetVar('Groups', 'defaultgroup', pnConfigGetVar('defaultgroup'));
			pnConfigDelVar('defaultgroup');
		    pnModSetVar('Groups', 'itemsperpage', 25);
			break;
	}
    // Update successful
    return true;
}

/**
 * delete the groups module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * @author Mark West
 * @return bool true if delete succesful, false otherwise */
function Groups_delete()
{
    // Deletion not allowed
    return false;
}

/**
 * create the default data for the groups module
 * 
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * 
 * @author       Mark West
 * @return       bool       false
 */
function groups_defaultdata()
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
    // groups
    $groupstable  = &$pntable['groups'];
    $groupscolumn = &$pntable['groups_column'];
	
	$record = array();
	$record['pn_gid']       = $dbconn->GenID($groupstable);
	$record['pn_name'] 		= ''._GROUPS_1_a.'';

	// Select an empty record from the database
	$sqltest = 'SELECT * FROM  '. $groupstable . ' WHERE '.$groupscolumn['gid'].' = -1';
	$rs =& $dbconn->Execute($sqltest);

	// Pass the empty recordset and the array containing the data to insert
	// into the GetInsertSQL function.
	$insertSQL = $dbconn->GetInsertSQL($rs, $record);

    // Create the new record;
	$dbconn->StartTrans();
	$dbconn->Execute($insertSQL);
	$dbconn->CompleteTrans();

	$record = array();
	$record['pn_gid']       = $dbconn->GenID($groupstable);
	$record['pn_name'] 		= ''._GROUPS_2_a.'';

	// Select an empty record from the database
	$sqltest = 'SELECT * FROM  '. $groupstable . ' WHERE '.$groupscolumn['gid'].' = -1';
	$rs =& $dbconn->Execute($sqltest);

	// Pass the empty recordset and the array containing the data to insert
	// into the GetInsertSQL function.
	$insertSQL = $dbconn->GetInsertSQL($rs, $record);

    // Create the new record;
	$dbconn->StartTrans();
	$dbconn->Execute($insertSQL);
	$dbconn->CompleteTrans();

    // It's good practice to name the table and column definitions you
    // are getting - $table and $column don't cut it in more complex
    // modules
    $groupmembershiptable  = &$pntable['group_membership'];
    $groupmembershipcolumn = &$pntable['group_membership_column'];

	// Anonymous
	$record = array();
	$record['pn_gid']     = ''._GROUP_MEMBERSHIP_1_a.'';
	$record['pn_uid']      = ''._GROUP_MEMBERSHIP_1_b.'';

	// Select an empty record from the database
	$sqltest = 'SELECT * FROM  '. $groupmembershiptable . ' WHERE '.$groupmembershipcolumn['gid'].' = -1';
	$rs = $dbconn->Execute($sqltest);

	// Pass the empty recordset and the array containing the data to insert
	// into the GetInsertSQL function.
	$insertSQL = $dbconn->GetInsertSQL($rs, $record);

    // Create the new record;
	$dbconn->StartTrans();
	$dbconn->Execute($insertSQL);
	$dbconn->CompleteTrans();

	// Anonymous
	$record = array();
	$record['pn_gid']     = ''._GROUP_MEMBERSHIP_2_a.'';
	$record['pn_uid']      = ''._GROUP_MEMBERSHIP_2_b.'';

	// Select an empty record from the database
	$sqltest = 'SELECT * FROM  '. $groupmembershiptable . ' WHERE '.$groupmembershipcolumn['gid'].' = -1';
	$rs = $dbconn->Execute($sqltest);

	// Pass the empty recordset and the array containing the data to insert
	// into the GetInsertSQL function.
	$insertSQL = $dbconn->GetInsertSQL($rs, $record);

    // Create the new record;
	$dbconn->StartTrans();
	$dbconn->Execute($insertSQL);
	$dbconn->CompleteTrans();

}

?>