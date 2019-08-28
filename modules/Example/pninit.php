<?php
// $Id: pninit.php 16621 2005-08-09 12:22:08Z markwest $
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
 * Example Module
 *
 * The Example module shows how to make a PostNuke module.
 * It can be copied over to get a basic file structure.
 *
 * Purpose of file:  Initialisation functions for Example
 *
 * @package      PostNuke_Miscellaneous_Modules
 * @subpackage   Example
 * @version      $Id: pninit.php 16621 2005-08-09 12:22:08Z markwest $
 * @author       Jim McDonald
 * @author       Joerg Napp <jnapp@users.sourceforge.net>
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */


/**
 * initialise the Example module
 *
 * This function is only ever called once during the lifetime of a particular
 * module instance.
 * This function MUST exist in the pninit file for a module
 *
 * @author       Jim McDonald
 * @return       bool       true on success, false otherwise
 */
function Example_init()
{
    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn()
    // we currently just want the first item, which is the official
    // database handle.  For pnDBGetTables() we want to keep the entire
    // tables array together for easy reference later on
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // It's good practice to name the table and column definitions you
    // are getting - $table and $column don't cut it in more complex
    // modules
    $Exampletable  = &$pntable['example'];
    $Examplecolumn = &$pntable['example_column'];

    // Create the table - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.
    // Create a new data dictionary object
    $dict = &NewDataDictionary($dbconn);

    // Define array containing specific table options
	// This variable only need populating once as the same table options will
	// apply for all tables to be created.
    $taboptarray =& pnDBGetTableOptions();

    // Define the fields in the form:
    // $fieldname $type $colsize $otheroptions
    $flds = "
        $Examplecolumn[tid]      I        AUTOINCREMENT PRIMARY,
        $Examplecolumn[itemname] C  (32)  NOTNULL DEFAULT '',
        $Examplecolumn[number]   I4       NOTNULL DEFAULT 0
    ";

    // Creating the table
    $sqlarray = $dict->CreateTableSQL($Exampletable, $flds, $taboptarray);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dict->ExecuteSQLArray($sqlarray) != 2) {
        pnSessionSetVar('errormsg', _EXAMPLECREATETABLEFAILED);
        return false;
    }

    // Define the name of the index
    $idxname = "ExampleIndex";

    // Define columns with index
    $idxflds = $Examplecolumn['number'];

    // ... and creating the index
    $sqlarray = $dict->CreateIndexSQL($idxname, $Exampletable, $idxflds);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dict->ExecuteSQLArray($sqlarray) != 2) {
        pnSessionSetVar('errormsg', _EXAMPLECREATEINDEXFAILED);
        return false;
    }

    // Set up an initial value for a module variable.  Note that all module
    // variables should be initialised with some value in this way rather
    // than just left blank, this helps the user-side code and means that
    // there doesn't need to be a check to see if the variable is set in
    // the rest of the code as it always will be

    // If the interactive installation has run, we hve some values now in sessionvars
    // that we must use
    $bold = pnSessionGetVar('example_bold');
    $itemsperpage = pnSessionGetVar('example_itemsperpage');
    pnModSetVar('Example', 'bold', (($bold<>false) ? $bold : 0));
    pnModSetVar('Example', 'itemsperpage', (($itemsperpage<>false) ? $itemsperpage : 10));

    // clean up
    pnSessionDelVar('example_bold');
    pnSessionDelVar('example_itemsperpage');

    // Initialisation successful
    return true;
}


/**
 * upgrade the Example module from an old version
 *
 * This function can be called multiple times
 * This function MUST exist in the pninit file for a module
 *
 * @author       Jim McDonald
 * @return       bool       true on success, false otherwise
 */
function Example_upgrade($oldversion)
{
    // Upgrade dependent on old version number
    switch($oldversion) {
        case 0.5:
            // Version 0.5 didn't have a 'number' field, it was added
            // in version 1.0

            // ** WARNING **
            // This is the old form to make an update.  It depends on the data
            // base that we are using.

            // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
            // return arrays but we handle them differently.  For pnDBGetConn()
            // we currently just want the first item, which is the official
            // database handle.  For pnDBGetTables() we want to keep the entire
            // tables array together for easy reference later on
            // This code could be moved outside of the switch statement if
            // multiple upgrades need it
            $dbconn  = &pnDBGetConn(true);
            $pntable = &pnDBGetTables();

            // It's good practice to name the table and column definitions you
            // are getting - $table and $column don't cut it in more complex
            // modules
            // This code could be moved outside of the switch statement if
            // multiple upgrades need it
            $Exampletable  = &$pntable['example'];
            $Examplecolumn = &$pntable['example_column'];

            // Add a column to the table - the formatting here is not
            // mandatory, but it does make the SQL statement relatively easy
            // to read.  Also, separating out the SQL statement from the
            // Execute() command allows for simpler debug operation if it is
            // ever needed
            $sql = "ALTER TABLE $Exampletable
                    ADD $Examplecolumn[number] int(5) NOT NULL default 0";
            $dbconn->Execute($sql);

            // Check for an error with the database code, and if so set an
            // appropriate error message and return
            if ($dbconn->ErrorNo() != 0) {
                pnSessionSetVar('errormsg', _EXAMPLEUPDATETABLETO1_0FAILED);
                return false;
            }

            // At the end of the successful completion of this function we
            // recurse the upgrade to handle any other upgrades that need
            // to be done.  This allows us to upgrade from any version to
            // the current version with ease
            return Example_upgrade(1.0);
        case 1.0:
            // Code to upgrade from version 1.0 goes here

            $dbconn  =& pnDBGetConn(true);
            $pntable =& pnDBGetTables();

            $Exampletable  = &$pntable['example'];
            $Examplecolumn = &$pntable['example_column'];

            $dict = &NewDataDictionary($dbconn);

            $flds = "
                $Examplecolumn[tid]      I        AUTOINCREMENT PRIMARY,
                $Examplecolumn[itemname] C  (32)  NOTNULL DEFAULT '',
                $Examplecolumn[number]   I4       NOTNULL DEFAULT 0
            ";

            // Alternative way is ... (add 1 or more fields)
            // $dict->AddColumnSQL($Exampletable,$flds);
            //
            // and ... (drop 1 or more fields)
            // $sqlarray = $dict->DropColumnSQL($Exampletable, $flds);
            //
            // or ... (alter 1 or more fields)
            $sqlarray = $dict->AlterColumnSQL($Exampletable,$flds);

            // Check for an error with the database code, and if so set an
            // appropriate error message and return
            if ($dict->ExecuteSQLArray($sqlarray) != 2) {
                pnSessionSetVar('errormsg', _EXAMPLEUPDATETABLETO1_1FAILED);
                return false;
            }

            return Example_upgrade(1.1);
            break;
        case 1.1:
            // Code to upgrade from version 1.1 goes here
            break;
        case 2.0:
            // Code to upgrade from version 2.0 goes here
            break;
    }

    // Update successful
    return true;
}


/**
 * delete the Example module
 *
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * This function MUST exist in the pninit file for a module
 *
 * @author       Jim McDonald
 * @return       bool       true on success, false otherwise
 */
function Example_delete()
{
    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn()
    // we currently just want the first item, which is the official
    // database handle.  For pnDBGetTables() we want to keep the entire
    // tables array together for easy reference later on
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $Exampletable  = &$pntable['example'];

    // New Object DataDictionary
    $dict = &NewDataDictionary($dbconn);

    $sqlarray = $dict->DropTableSQL($Exampletable);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dict->ExecuteSQLArray($sqlarray) != 2) {
        pnSessionSetVar('errormsg', _EXAMPLEDROPTABLEFAILED);
        // Report failed deletion attempt
        return false;
    }

    // Delete any module variables
    pnModDelVar('Example');

    // Deletion successful
    return true;
}

/**
 * interactive installation procedure
 * This function starts the interactive module installation procedure. We can have
 * as many steps here as necessary and go forwards or backwards as needed.
 *
 * This function may exist in the pninit file for a module
 *
 * @author       Frank Schummertz
 * @return       pnRender output
 */
function Example_init_interactiveinit()
{
    // We start the interactive installation process now.
    // This function is called automatically if present.
    // In this case we simply show a welcome screen.

    // Check permissions
    if (!pnSecAuthAction(0, '::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // prepare the output
    $pnr =& new pnRender('Example');
    $pnr->caching = false;
    return $pnr->fetch('example_init_interactive.htm');
}

/**
 * step 2 of the interactive installation procedure
 *
 * @author       Frank Schummertz
 * @return       pnRender output
 */
function Example_init_step2()
{
    // This is part two of the interactive installation procedure. We will ask the user
    // for some basic data now. After collecting the data, we store them session vars.

    // Check permissions
    if (!pnSecAuthAction(0, '::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // submit is set if the users sends his data. We can use the same function here for
    // presenting our form and handle the users input.
    $submit = pnVarCleanFromInput('submit');
    if(!$submit) {
        // submit is not set, show the form now
        $pnr =& new pnRender('Example');
        $pnr->caching = false;
        return $pnr->fetch('example_init_step2.htm');
    } else {
        // submit is set, read the data and store them.
        if (!pnSecConfirmAuthKey()) {
            pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
            return pnRedirect(pnModURL('Modules', 'admin', 'view'));
        }
        // submit is set, read the data now
        list($bold, $itemsperpage, $activate) = pnVarCleanFromInput('bold', 'itemsperpage', 'activate');
        // We do not store the values directly in the mod vars but put them in to a session
        // var first. This will be read in the _init function. So we keep backwards compatible
        // with .750 or earlier
        pnSessionSetVar('example_bold', $bold);
        pnSessionSetVar('example_itemsperpage', $itemsperpage);
        $activate = (!empty($activate)) ? true : false;
    }
    // we are ready now and redirect to the function that is responsible for installing a module
    return pnRedirect(pnModURL('Example', 'init', 'step3', array('activate' => $activate)));
}

/**
 * step3 - the last step
 * We just say "Thank you" and continue
 *
 * @author       Frank Schummertz
 * @return       pnRender output
 */
function Example_init_step3()
{
    // Check permissions
    if (!pnSecAuthAction(0, '::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
    $activate = (bool)pnVarCleanFromInput('activate');

    $pnr =& new pnRender('Example');
    $pnr->caching = false;
    $pnr->assign('authid', pnSecGenAuthKey('Modules'));
    $pnr->assign('activate', $activate);
    return $pnr->fetch('example_init_step3.htm');
}

/**
 * interactive delete
 * We just say "Thank you" and continue
 *
 * @author       Frank Schummertz
 * @return       pnRender output
 */
function Example_init_interactivedelete()
{
    // Check permissions
    if (!pnSecAuthAction(0, '::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $pnr =& new pnRender('Example');
    $pnr->caching = false;
    $pnr->assign('authid', pnSecGenAuthKey('Modules'));
    return $pnr->fetch('example_init_delete.htm');
}

/**
 * interactive upgrade
 * We inform the user that we are going to upgrade now
 *
 * @author       Frank Schummertz
 * @params       $args[oldversion] the old version of the module before upgrading
 * @return       pnRender output
 */
function Example_init_interactiveupgrade($args)
{
    extract($args);
    unset($args);

    // Check permissions
    if (!pnSecAuthAction(0, '::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $pnr =& new pnRender('Example');
    $pnr->caching = false;
    $pnr->assign('authid', pnSecGenAuthKey('Modules'));

    switch($oldversion) {
        case '0.1':
            // use template for upgrade from 0.1
        default:
            $templatefile = 'example_init_upgrade.htm';
    }
    return $pnr->fetch($templatefile);
}

?>