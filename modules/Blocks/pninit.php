<?php
// $Id: pninit.php 16573 2005-07-30 17:31:10Z markwest $
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
 * @subpackage Blocks
 * @license http://www.gnu.org/copyleft/gpl.html
*/

/**
 * initialise the blocks module
 * 
 * This function is only ever called once during the lifetime of a particular
 * module instance.
 * This function MUST exist in the pninit file for a module
 * 
 * @author       Mark West
 * @return       bool       true on success, false otherwise
 */
function blocks_init()
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
    // blocks
    $blockstable  = &$pntable['blocks'];
    $blockscolumn = &$pntable['blocks_column'];

    // Create the sql using adodb's data dictionary.
	// The formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.
    $sql = "
			pn_bid I(11) AUTO PRIMARY,
			pn_bkey C(255) NOTNULL DEFAULT '',
			pn_title C(255) NOTNULL DEFAULT '',
			pn_content X2 NOTNULL,
			pn_url C(254) NOTNULL DEFAULT '',
			pn_mid I(11) NOTNULL DEFAULT '0',
			pn_position C(1) NOTNULL DEFAULT 'l',
			pn_weight decimal(10.1) NOTNULL DEFAULT '0.0',
			pn_active I4 NOTNULL DEFAULT '1',
			pn_refresh I(11) NOTNULL DEFAULT '0',
			pn_last_update T DEFTIMESTAMP NOTNULL,
			pn_language C(30) NOTNULL DEFAULT '',
			pn_collapsable I4 NOTNULL DEFAULT '1',
			pn_defaultstate I4 NOTNULL DEFAULT '1'
    ";
	
    // create the data dictionaries SQL array
	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->CreateTableSQL($blockstable, $sql, $taboptarray);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result != 2) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED);
        return false;
    }

    // Let's create an index in the user id field
    // Create the index
	$sqlarray = $dict->CreateIndexSQL($blockstable . '_index', $blockstable, $blockscolumn['bid']);
	$result = $dict->ExecuteSQLArray($sqlarray);

    // It's good practice to name the table and column definitions you
    // are getting - $table and $column don't cut it in more complex
    // blocks
    $userblockstable  = &$pntable['userblocks'];
    $userblockscolumn = &$pntable['userblocks_column'];

    // Create the sql using adodb's data dictionary.
	// The formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.
    // Columns definition
    $sql = "
			pn_uid I(11) NOTNULL DEFAULT '0',
			pn_bid I(11) NOTNULL DEFAULT '0',
			pn_active I(3) NOTNULL DEFAULT '1',
			pn_last_update T DEFTIMESTAMP
    ";
	
    // create the data dictionaries SQL array
	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->CreateTableSQL($userblockstable, $sql, $taboptarray);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result != 2) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED);
        return false;
    }

    // create the default data for the modules module
	blocks_defaultdata();

	// Set a default value for a module variable
	pnModSetVar('Blocks', 'collapseable', 1);

    // Initialisation successful
    return true;
}


/**
 * upgrade the blocks module from an old version
 * 
 * This function can be called multiple times
 * This function MUST exist in the pninit file for a module
 * 
 * @author       Mark West
 * @return       bool       true on success, false otherwise
 */
function blocks_upgrade($oldversion)
{
	// When using adodb's data dictionary we don't need to consider what version 
	// we're upgrading from. The changetablesql method generate the necessary
	// ALTER, ADD statements. This method effectively applies a 'transform'
	// to the table

    // Get database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Create a new data dictionary object
    $dict = NewDataDictionary($dbconn);

    // It's good practice to name the table and column definitions you
    // are getting - $table and $column don't cut it in more complex
    // blocks
    $blockstable  = &$pntable['blocks'];
    $blockscolumn = &$pntable['blocks_column'];

    // Create the sql using adodb's data dictionary.
	// The formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.
    $sql = "
			pn_bid I(11) AUTO PRIMARY,
			pn_bkey C(255) NOTNULL DEFAULT '',
			pn_title C(255) NOTNULL DEFAULT '',
			pn_content X2 NOTNULL,
			pn_url C(254) NOTNULL DEFAULT '',
			pn_mid I(11) NOTNULL DEFAULT '0',
			pn_position C(1) NOTNULL DEFAULT 'l',
			pn_weight decimal(10.1) NOTNULL DEFAULT '0.0',
			pn_active I4 NOTNULL DEFAULT '1',
			pn_collapsable I4 NOTNULL DEFAULT '1',
			pn_defaultstate I4 NOTNULL DEFAULT '1',
			pn_refresh I(11) NOTNULL DEFAULT '0',
			pn_last_update T DEFTIMESTAMP NOTNULL,
			pn_language C(30) NOTNULL DEFAULT ''
    ";

    // create the data dictionaries SQL array
	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->ChangeTableSQL($blockstable, $sql);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result != 2) {
        pnSessionSetVar('errormsg', _UPDATETABLEFAILED);
        return false;
    }

    // Update successful
    return true;
}


/**
 * delete the blocks module
 * 
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * This function MUST exist in the pninit file for a module
 * 
 * Since the blocks module should never be deleted we'all always return false here
 * @author       Mark West
 * @return       bool       false
 */
function blocks_delete()
{
    // Deletion not allowed
    return false;
}

/**
 * create the default data for the blocks module
 * 
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * 
 * @author       Mark West
 * @return       bool       false
 */
function blocks_defaultdata()
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
    $blockstable = $pntable['blocks'];
    $blockscolumn = &$pntable['blocks_column'];

	// Menu
	$record = array();
	$record['pn_bid']         = $dbconn->GenID($blockstable);
	$record['pn_bkey']        = ''._BLOCKS_00_a.'';
	$record['pn_title']       = ''._BLOCKS_00_b.'';
	$record['pn_content']     = ''._BLOCKS_00_c.'';
	$record['pn_url']         = ''._BLOCKS_00_d.'';
	$record['pn_mid']         = pnModGetIDFromName('Blocks');
	$record['pn_position']    = ''._BLOCKS_00_e.'';
	$record['pn_weight']      = ''._BLOCKS_00_f.'';
	$record['pn_active']      = ''._BLOCKS_00_g.'';
	$record['pn_refresh']     = ''._BLOCKS_00_h.'';
	$record['pn_last_update'] = time();
	$record['pn_language']    = ''._BLOCKS_00_i.'';

	// Select an empty record from the database
	$sqltest = 'SELECT * FROM  '. $blockstable . ' WHERE '.$blockscolumn['bid'].' = -1';
	$rs =& $dbconn->Execute($sqltest);

	// Pass the empty recordset and the array containing the data to insert
	// into the GetInsertSQL function.
	$insertSQL = $dbconn->GetInsertSQL($rs, $record);

    // Create the new record;
	$dbconn->StartTrans();
	$dbconn->Execute($insertSQL);
	$dbconn->CompleteTrans();

	// Languages block
	$record = array();
	$record['pn_bid']         = $dbconn->GenID($blockstable);
	$record['pn_bkey']        = ''._BLOCKS_01_a.'';
	$record['pn_title']       = ''._BLOCKS_01_b.'';
	$record['pn_content']     = ''._BLOCKS_01_c.'';
	$record['pn_url']         = ''._BLOCKS_01_d.'';
	$record['pn_mid']         = pnModGetIDFromName('Blocks');
	$record['pn_position']    = ''._BLOCKS_01_e.'';
	$record['pn_weight']      = ''._BLOCKS_01_f.'';
	$record['pn_active']      = ''._BLOCKS_01_g.'';
	$record['pn_refresh']     = ''._BLOCKS_01_h.'';
	$record['pn_last_update'] = time();
	$record['pn_language']    = ''._BLOCKS_01_i.'';

	// Select an empty record from the database
	$sqltest = 'SELECT * FROM  '. $blockstable . ' WHERE '.$blockscolumn['bid'].' = -1';
	$rs =& $dbconn->Execute($sqltest);

	// Pass the empty recordset and the array containing the data to insert
	// into the GetInsertSQL function.
	$insertSQL = $dbconn->GetInsertSQL($rs, $record);

    // Create the new record;
	$dbconn->StartTrans();
	$dbconn->Execute($insertSQL);
	$dbconn->CompleteTrans();

	// Admin messages block
	$record = array();
	$record['pn_bid']         = $dbconn->GenID($blockstable);
	$record['pn_bkey']        = ''._BLOCKS_02_a.'';
	$record['pn_title']       = ''._BLOCKS_02_b.'';
	$record['pn_content']     = ''._BLOCKS_02_c.'';
	$record['pn_url']         = ''._BLOCKS_02_d.'';
	$record['pn_mid']         = pnModGetIDFromName('Admin_Messages');
	$record['pn_position']    = ''._BLOCKS_02_e.'';
	$record['pn_weight']      = ''._BLOCKS_02_f.'';
	$record['pn_active']      = ''._BLOCKS_02_g.'';
	$record['pn_refresh']     = ''._BLOCKS_02_h.'';
	$record['pn_last_update'] = time();
	$record['pn_language']    = ''._BLOCKS_02_i.'';

	// Select an empty record from the database
	$sqltest = 'SELECT * FROM  '. $blockstable . ' WHERE '.$blockscolumn['bid'].' = -1';
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