<?php
// $Id: pninit.php 15762 2005-02-19 19:55:07Z markwest $
// ----------------------------------------------------------------------
// POST-NUKE Content Management System
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
 * Messages Module
 * 
 * The Messages module shows how to make a PostNuke module. 
 * It can be copied over to get a basic file structure.
 *
 * Purpose of file:  Initialisation functions for Messages 
 *
 * @package      PostNuke_Miscellaneous_Modules
 * @subpackage   Messages
 * @version      $Id: pninit.php 15762 2005-02-19 19:55:07Z markwest $
 * @author       Mark West
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * initialise the Messages module
 * 
 * This function is only ever called once during the lifetime of a particular
 * module instance.
 * This function MUST exist in the pninit file for a module
 * 
 * @author       Jim McDonald
 * @return       bool       true on success, false otherwise
 */
function Messages_init()
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
    $messagestable  = &$pntable['priv_msgs'];
    $messagescolumn = &$pntable['priv_msgs_column'];

    // Create the table - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating out
    // the SQL statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "
            pn_msg_id int(11) NOTNULL AUTO PRIMARY,
            pn_msg_image C(100) NOTNULL DEFAULT '',
            pn_subject C(100) NOT NULL DEFAULT '',
            pn_from_userid int(11) NOTNULL DEFAULT '0',
            pn_to_userid int(11) NOTNULL DEFAULT '0',
            pn_msg_time C(20) DEFAULT '',
            pn_msg_text X DEFAULT '',
            pn_read_msg int(4) NOTNULL
           ";

    // create the data dictionaries SQL array
	// This array contains all the ncessary information to execute some sql 
	// on any of the supported dbms platforms
    $sqlarray = $dict->CreateTableSQL($messagestable, $sql, $taboptarray);

    // Execute the sql that has been created
    $result = $dict->ExecuteSQLArray($sqlarray, false);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
	if ($result == 0) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED);
        return false;
    }

	// activate the bbsmile hook for this module if the module is present
	if (pnModAvailable('pn_bbsmile')) {
		pnModAPIFunc('Modules', 'admin', 'enablehooks', 
		             array('callermodname' => 'Messages', 
				           'hookmodname' => 'pn_bbsmile'));
	}
	if (pnModAvailable('pn_bbcode')) {
		pnModAPIFunc('Modules', 'admin', 'enablehooks', 
		             array('callermodname' => 'Messages', 
				           'hookmodname' => 'pn_bbcode'));
	}
    // Initialisation successful
    return true;
}


/**
 * upgrade the Messages module from an old version
 * 
 * This function can be called multiple times
 * This function MUST exist in the pninit file for a module
 * 
 * @author       Jim McDonald
 * @return       bool       true on success, false otherwise
 */
function Messages_upgrade($oldversion)
{
    // Update successful
    return true;
}


/**
 * delete the Messages module
 * 
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * This function MUST exist in the pninit file for a module
 * 
 * @author       Jim McDonald
 * @return       bool       true on success, false otherwise
 */
function Messages_delete()
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
    $sqlarray = $dict->DropTableSQL($pntable['priv_msgs']);

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