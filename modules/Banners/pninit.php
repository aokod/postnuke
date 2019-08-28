<?php
// $Id: pninit.php 15755 2005-02-19 18:16:21Z markwest $
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
// Original Author of file: Devin Hayes
// Purpose of file:  Initialisation functions for Banners
// ----------------------------------------------------------------------

/**
 * @package PostNuke_ResourcePack_Modules
 * @subpackage Banners
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * initialise the Banners module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * @author Devin Hayes
 * @version $Revision: 15755 $
 * @return bool true if successful, false otherwise
 */
function Banners_init()
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
    $banners_table = $pntable['banner'];
    $banners_column = &$pntable['banner_column'];

    // Create the table - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating out
    // the SQL statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "
			pn_bid I(11) AUTOINCREMENT PRIMARY,
			pn_cid I(11) NOTNULL DEFAULT '0',
			pn_type VARCHAR(2) NOTNULL DEFAULT '0',
			pn_imptotal I(11) NOTNULL DEFAULT '0',
			pn_impmade I(11) NOTNULL DEFAULT '0',
			pn_clicks I(11) NOTNULL DEFAULT '0',
			pn_imageurl VARCHAR(255) NOTNULL DEFAULT '',
			pn_clickurl VARCHAR(255) NOTNULL DEFAULT '',
			pn_date DATETIME DEFAULT NULL
		   ";

    // create the data dictionaries SQL array
	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->CreateTableSQL($banners_table, $sql, $taboptarray);

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
    $banners_table = $pntable['bannerclient'];
    $banners_column = &$pntable['bannerclient_column'];

    // Create the table - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating out
    // the SQL statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "
			pn_cid I(11) AUTOINCREMENT PRIMARY,
			pn_name VARCHAR(60) NOTNULL,
			pn_contact VARCHAR(60) NOTNULL,
			pn_email VARCHAR(60) NOTNULL,
			pn_login VARCHAR(10) NOTNULL,
			pn_passwd VARCHAR(10) NOTNULL,
			pn_extrainfo text NOTNULL
		   ";

    // create the data dictionaries SQL array
	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->CreateTableSQL($banners_table, $sql, $taboptarray);

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
    $banners_table = $pntable['bannerfinish'];
    $banners_column = &$pntable['bannerfinish_column'];

    // Create the table - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating out
    // the SQL statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "
			pn_bid I(11) AUTOINCREMENT PRIMARY,
			pn_cid I(11) NOTNULL DEFAULT '0',
			pn_impressions I(11) NOTNULL DEFAULT '0',
			pn_clicks I(11) NOTNULL DEFAULT '0',
			pn_datestart DATETIME DEFAULT NULL,
			pn_dateend DATETIME DEFAULT NULL
		   ";

    // create the data dictionaries SQL array
	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->CreateTableSQL($banners_table, $sql, $taboptarray);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result != 2) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED);
        return false;
    }

	// set config vars
	pnConfigSetVar('myIP', '192.168.123.254');
	pnConfigSetVar('banners', false);
    
    // Initialisation successful
    return true;
}

/**
 * upgrade the Banners module from an old version
 * This function can be called multiple times
 * @author Devin Hayes
 * @version $Revision: 15755 $
 * @return bool true if successful, false otherwise
 */
function Banners_upgrade($oldversion)
{
    // Update successful
    return true;
}
 
/**
 * delete the Banners module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * @author Devin Hayes
 * @version $Revision: 15755 $
 * @return bool true if successful, false otherwise
 */
function Banners_delete()
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
    $sqlarray = $dict->DropTableSQL($pntable['banner']);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result === false) {
        pnSessionSetVar('errormsg', _DELETETABLEFAILED);
        return false;
    }

	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->DropTableSQL($pntable['bannerclient']);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result !=2) {
        pnSessionSetVar('errormsg', _DELETETABLEFAILED);
        return false;
    }

	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->DropTableSQL($pntable['bannerfinish']);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result != 2) {
        pnSessionSetVar('errormsg', _DELETETABLEFAILED);
        return false;
    }

    // Delete module variables
    pnConfigDelVar('banners');
    pnConfigDelVar('myIP');

    // Delete successful
    return true;
}

?>