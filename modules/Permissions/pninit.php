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

/**
 * @package PostNuke_System_Modules
 * @subpackage Permissions
 * @license http://www.gnu.org/copyleft/gpl.html
*/

/**
 * initialise the permissions module
 * 
 * This function is only ever called once during the lifetime of a particular
 * module instance.
 * This function MUST exist in the pninit file for a module
 * 
 * @author       Mark West
 * @version      $Revision: 15762 $
 * @return       bool       true on success, false otherwise
 */
function permissions_init()
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
    $grouppermstable  = &$pntable['group_perms'];
    $grouppermscolumn = &$pntable['group_perms_column'];

    // Create the sql using adodb's data dictionary.
	// The formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.
    $sql = "
			pn_pid I(11) AUTO PRIMARY,
			pn_gid I(11) NOTNULL DEFAULT '0',
			pn_sequence I(11) NOTNULL DEFAULT '0',
			pn_realm I(4) NOTNULL DEFAULT '0',
			pn_component C(255) NOTNULL DEFAULT '',
			pn_instance C(255) NOTNULL DEFAULT '',
			pn_level I(4) NOTNULL DEFAULT '0',
			pn_bond I(2) NOTNULL DEFAULT '0'
    ";
	
    // create the data dictionaries SQL array
	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->CreateTableSQL($grouppermstable, $sql, $taboptarray);

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
    $userpermstable  = &$pntable['user_perms'];
    $userpermscolumn = &$pntable['user_perms_column'];

    // Create the sql using adodb's data dictionary.
	// The formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.
    $sql = "
			pn_pid I(11) PRIMARY AUTO,
			pn_uid I(11) NOTNULL DEFAULT '0',
			pn_sequence I(6) NOTNULL DEFAULT '0',
			pn_realm I(4) NOTNULL DEFAULT '0',
			pn_component C(255) NOTNULL DEFAULT '',
			pn_instance C(255) NOTNULL DEFAULT '',
			pn_level I(4) NOTNULL DEFAULT '0',
			pn_bond I(2) NOTNULL DEFAULT '0'
    ";
	
    // create the data dictionaries SQL array
	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->CreateTableSQL($userpermstable, $sql, $taboptarray);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result != 2) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED);
        return false;
    }

    // Create any default for this module
	permissions_defaultdata();

    // Initialisation successful
    return true;
}


/**
 * upgrade the permissions module from an old version
 * 
 * This function can be called multiple times
 * This function MUST exist in the pninit file for a module
 * 
 * @author       Mark West
 * @version      $Revision: 15762 $
 * @return       bool       true on success, false otherwise
 */
function permissions_upgrade($oldversion)
{
    // Update successful
    return true;
}


/**
 * delete the permissions module
 * 
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * This function MUST exist in the pninit file for a module
 * 
 * Since the permissions module should never be deleted we'all always return false here
 * @author       Mark West
 * @version      $Revision: 15762 $
 * @return       bool       false
 */
function permissions_delete()
{
    // Deletion not allowed
    return false;
}

/**
 * create the default data for the permissions module
 * 
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * 
 * @author       Mark West
 * @version      $Revision: 15762 $
 * @return       bool       false
 */
function permissions_defaultdata()
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
    $grouppermstable  = &$pntable['group_perms'];
    $grouppermscolumn = &$pntable['group_perms_column'];
	
	$record = array();
	$record['pn_pid']       = $dbconn->GenID($grouppermstable);
	$record['pn_gid']       = ''._GROUP_PERMS_1_a.'';
	$record['pn_sequence']  = ''._GROUP_PERMS_1_b.'';
	$record['pn_realm']     = ''._GROUP_PERMS_1_c.'';
	$record['pn_component'] = ''._GROUP_PERMS_1_d.'';
	$record['pn_instance']  = ''._GROUP_PERMS_1_e.'';
	$record['pn_level']     = ''._GROUP_PERMS_1_f.'';
	$record['pn_bond']      = ''._GROUP_PERMS_1_g.'';

	// Select an empty record from the database
	$sqltest = 'SELECT * FROM  '. $grouppermstable . ' WHERE '.$grouppermscolumn['pid'].' = -1';
	$rs =& $dbconn->Execute($sqltest);

	// Pass the empty recordset and the array containing the data to insert
	// into the GetInsertSQL function.
	$insertSQL = $dbconn->GetInsertSQL($rs, $record);

    // Create the new record;
	$dbconn->StartTrans();
	$dbconn->Execute($insertSQL);
	$dbconn->CompleteTrans();

	$record = array();
	$record['pn_pid']       = $dbconn->GenID($grouppermstable);
	$record['pn_gid']       = ''._GROUP_PERMS_2_a.'';
	$record['pn_sequence']  = ''._GROUP_PERMS_2_b.'';
	$record['pn_realm']     = ''._GROUP_PERMS_2_c.'';
	$record['pn_component'] = ''._GROUP_PERMS_2_d.'';
	$record['pn_instance']  = ''._GROUP_PERMS_2_e.'';
	$record['pn_level']     = ''._GROUP_PERMS_2_f.'';
	$record['pn_bond']      = ''._GROUP_PERMS_2_g.'';
	
	// Select an empty record from the database
	$sqltest = 'SELECT * FROM  '. $grouppermstable . ' WHERE '.$grouppermscolumn['pid'].' = -1';
	$rs =& $dbconn->Execute($sqltest);

	// Pass the empty recordset and the array containing the data to insert
	// into the GetInsertSQL function.
	$insertSQL = $dbconn->GetInsertSQL($rs, $record);

    // Create the new record;
	$dbconn->StartTrans();
	$dbconn->Execute($insertSQL);
	$dbconn->CompleteTrans();

	$record = array();
	$record['pn_pid']       = $dbconn->GenID($grouppermstable);
	$record['pn_gid']       = ''._GROUP_PERMS_3_a.'';
	$record['pn_sequence']  = ''._GROUP_PERMS_3_b.'';
	$record['pn_realm']     = ''._GROUP_PERMS_3_c.'';
	$record['pn_component'] = ''._GROUP_PERMS_3_d.'';
	$record['pn_instance']  = ''._GROUP_PERMS_3_e.'';
	$record['pn_level']     = ''._GROUP_PERMS_3_f.'';
	$record['pn_bond']      = ''._GROUP_PERMS_3_g.'';
	
	// Select an empty record from the database
	$sqltest = 'SELECT * FROM  '. $grouppermstable . ' WHERE '.$grouppermscolumn['pid'].' = -1';
	$rs =& $dbconn->Execute($sqltest);

	// Pass the empty recordset and the array containing the data to insert
	// into the GetInsertSQL function.
	$insertSQL = $dbconn->GetInsertSQL($rs, $record);

    // Create the new record;
	$dbconn->StartTrans();
	$dbconn->Execute($insertSQL);
	$dbconn->CompleteTrans();

	$record = array();
	$record['pn_pid']       = $dbconn->GenID($grouppermstable);
	$record['pn_gid']       = ''._GROUP_PERMS_4_a.'';
	$record['pn_sequence']  = ''._GROUP_PERMS_4_b.'';
	$record['pn_realm']     = ''._GROUP_PERMS_4_c.'';
	$record['pn_component'] = ''._GROUP_PERMS_4_d.'';
	$record['pn_instance']  = ''._GROUP_PERMS_4_e.'';
	$record['pn_level']     = ''._GROUP_PERMS_4_f.'';
	$record['pn_bond']      = ''._GROUP_PERMS_4_g.'';
	
	// Select an empty record from the database
	$sqltest = 'SELECT * FROM  '. $grouppermstable . ' WHERE '.$grouppermscolumn['pid'].' = -1';
	$rs =& $dbconn->Execute($sqltest);

	// Pass the empty recordset and the array containing the data to insert
	// into the GetInsertSQL function.
	$insertSQL = $dbconn->GetInsertSQL($rs, $record);

    // Create the new record;
	$dbconn->StartTrans();
	$dbconn->Execute($insertSQL);
	$dbconn->CompleteTrans();

	$record = array();
	$record['pn_pid']       = $dbconn->GenID($grouppermstable);
	$record['pn_gid']       = ''._GROUP_PERMS_5_a.'';
	$record['pn_sequence']  = ''._GROUP_PERMS_5_b.'';
	$record['pn_realm']     = ''._GROUP_PERMS_5_c.'';
	$record['pn_component'] = ''._GROUP_PERMS_5_d.'';
	$record['pn_instance']  = ''._GROUP_PERMS_5_e.'';
	$record['pn_level']     = ''._GROUP_PERMS_5_f.'';
	$record['pn_bond']      = ''._GROUP_PERMS_5_g.'';
	
	// Select an empty record from the database
	$sqltest = 'SELECT * FROM  '. $grouppermstable . ' WHERE '.$grouppermscolumn['pid'].' = -1';
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