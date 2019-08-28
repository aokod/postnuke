<?php
// $Id: pninit.php 17835 2006-02-03 09:28:10Z markwest $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
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
// Original Author of file: Jim McDonald
// Purpose of file:  Initialisation functions for ratings
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Utility_Modules
 * @subpackage Ratings
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * Initialise the ratings module
 * @author Jim McDonald
 * @return true if init success, false otherwise
 */
function ratings_init()
{
    // Get database information
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Create a new data dictionary object
    $dict = NewDataDictionary($dbconn);

    // Define any table specific options
	$taboptarray =& pnDBGetTableOptions();

    // Create tables
    $ratingstable = $pntable['ratings'];
    $ratingscolumn = &$pntable['ratings_column'];

    // Create the table - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating out
    // the SQL statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "
            pn_rid I(10) NOTNULL AUTO PRIMARY,
            pn_module C(32)  NOTNULL DEFAULT '',
            pn_itemid C(64)  NOTNULL DEFAULT '',
            pn_ratingtype C(64)  NOTNULL DEFAULT '',
            pn_rating F(8.5)  NOTNULL DEFAULT '0',
            pn_numratings I(5) NOTNULL DEFAULT '1'
		   ";

    // create the data dictionaries SQL array
	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->CreateTableSQL($ratingstable, $sql, $taboptarray);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result != 2) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED);
        return false;
    }

    $ratingslogtable = $pntable['ratingslog'];
    $ratingslogcolumn = &$pntable['ratingslog_column'];

    // Create the table - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating out
    // the SQL statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "
            pn_id C(32) NOTNULL DEFAULT '',
            pn_ratingid C(64) NOTNULL DEFAULT '',
            pn_rating I(3) NOTNULL DEFAULT '0'
		   ";

    // create the data dictionaries SQL array
	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->CreateTableSQL($ratingslogtable, $sql, $taboptarray);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result != 2) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED);
        return false;
    }


    // Set up module variables
    pnModSetVar('Ratings', 'defaultstyle', 'outoffivestars');
    pnModSetVar('Ratings', 'seclevel', 'medium');

    // Set up module hooks
    if (!pnModRegisterHook('item', 'display', 'GUI', 'Ratings', 'user', 'display')) {
        return false;
    }
	if (!pnModRegisterHook('item', 'delete', 'API', 'Ratings', 'admin', 'deletehook')) {
		return false;
	}
    if (!pnModRegisterHook('module', 'remove', 'API', 'Ratings', 'admin', 'removehook')) {
        return false;
    }

    // Initialisation successful
    return true;
}

/**
 * Upgrade the ratings module from an old version
 * @author Jim McDonald
 * @return true if upgrade success, false otherwise
 */
function ratings_upgrade($oldversion)
{
    // Upgrade dependent on old version number
    switch($oldversion) {
        case 1.0:
            // Code to upgrade from version 1.0 goes here

            // Get database information
            $dbconn =& pnDBGetConn(true);
            $pntable =& pnDBGetTables();

            // rating went from int to double
            $ratingstable = $pntable['ratings'];
            $ratingscolumn = &$pntable['ratings_column'];
            $sql = "ALTER TABLE $ratingstable
                    CHANGE $ratingscolumn[rating] $ratingscolumn[rating] double(8,5) NOT NULL default 0";
            $dbconn->Execute($sql);

            // Check database result
            if ($dbconn->ErrorNo() != 0) {
                // Report failed upgrade
                return false;
            }

            // Carry out other upgrades
            return ratings_upgrade(1.1);
            break;
        case 1.1:
			if (!pnModRegisterHook('module', 'remove', 'API', 'Ratings', 'admin', 'removehook')) {
				return false;
			}
            return ratings_upgrade(1.2);
            break;
		case 1.2:
			// get all modules hooked to ezcomments
			$hookedmodules = pnModAPIFunc('Modules', 'admin', 'gethookedmodules', array('hookmodname'=> 'Ratings'));
		    if (!pnModRegisterHook('item', 'delete', 'API', 'Ratings', 'admin', 'deletehook')) {
				return false;
			}
			foreach ($hookedmodules as $modname => $hooktype) {
				// disable the hooks for this module
				pnModAPIFunc('Modules', 'admin', 'disablehooks', array('callermodname' => $modname, 'hookmodname' => 'Ratings'));
				// re-enable the hooks for this module
				pnModAPIFunc('Modules', 'admin', 'enablehooks', array('callermodname' => $modname, 'hookmodname' => 'Ratings'));
			}

			break;
    }

    return true;
}

/**
 * delete the ratings module
 * @author Jim McDonald
 * @return true if delete success, false otherwise
 */
function ratings_delete()
{
    // Remove module hooks
    if (!pnModUnregisterHook('item', 'display', 'GUI', 'Ratings', 'user', 'display')) {
        pnSessionSetVar('errormsg', _RATINGSCOULDNOTUNREGISTER);
		// return false;
    }

    if (!pnModUnregisterHook('module', 'remove', 'API', 'Ratings', 'admin', 'removehook')) {
        pnSessionSetVar('errormsg', _RATINGSCOULDNOTUNREGISTER);
		// return false;
    }

    // Delete module variables
    pnModDelVar('Ratings', 'defaultstyle');
    pnModDelVar('Ratings', 'seclevel');

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
    $sqlarray = $dict->DropTableSQL($pntable['ratings']);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result != 2) {
        pnSessionSetVar('errormsg', _DELETETABLEFAILED);
        return false;
    }

    // Drop the table - for such a simple command the advantages of separating
    // out the SQL statement from the Execute() command are minimal, but as
    // this has been done elsewhere it makes sense to stick to a single method
    // create the data dictionaries SQL array

	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->DropTableSQL($pntable['ratingslog']);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($result != 2) {
        pnSessionSetVar('errormsg', _DELETETABLEFAILED);
        return false;
    }

    // Deletion successful
    return true;
}

?>