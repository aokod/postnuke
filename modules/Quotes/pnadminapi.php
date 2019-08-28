<?php
// File: $Id: pnadminapi.php 15630 2005-02-04 06:35:42Z jorg $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
// http://www.postnuke.com/
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
// Original Author of file:  Erik Slooff <erik@slooff.com> www.slooff.com
// Purpose of file:
// PHP-NUKE 5.0: Quote of the day Add-On
// Copyright (c) 2000 by Erik Slooff (erik@slooff.com)
// Quotes module API: adam_baum (Greg)
// ----------------------------------------------------------------------
// Changes for this admin module thanks to Heinz Hombergs
// (heinz@hhombergs.de), http://www.kodewulf.za.net
// ----------------------------------------------------------------------

/**
 * @package PostNuke_ResourcePack_Modules
 * @subpackage Quotes
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * Create Quote
 * @author Greg Allan
 * @author Erik Slooff
 * @param 'args['qquote']' quote text
 * @param 'args['qauthor']' quote author
 * @return id of quote if success, false otherwise
 */
function quotes_adminapi_create($args)
{

    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present,
    // if not then set an appropriate error message and return
    if ((!isset($qquote)) || (!isset($qauthor))) {
        pnSessionSetVar('errormsg', _QUOTESARGSERROR);
        return false;
    }

    // Security check - important to do this as early on as possible to 
    // avoid potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Quotes::', '::', ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

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
    $quotestable = $pntable['quotes'];
    $quotescolumn = &$pntable['quotes_column'];

    // Get next ID in table - this is required prior to any insert that
    // uses a unique ID, and ensures that the ID generation is carried
    // out in a database-portable fashion
    $nextId = $dbconn->GenId($quotestable);

    // Add item - the formatting here is not mandatory, but it does make
    // the SQL statement relatively easy to read.  Also, separating out
    // the sql statement from the Execute() command allows for simpler
    // debug operation if it is ever needed

    $qquote = pnVarPrepForStore($qquote);
    $qauthor = pnVarPrepForStore($qauthor);
    $query = "INSERT INTO $quotestable ($quotescolumn[qid], $quotescolumn[quote], $quotescolumn[author])
                                     VALUES ($nextId, '$qquote', '$qauthor')";
    $result =& $dbconn->Execute($query);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _CREATEFAILED);
        return false;
    }

    // Get the ID of the item that we inserted.  It is possible, although
    // very unlikely, that this is different from $nextId as obtained
    // above, but it is better to be safe than sorry in this situation
    $qid = $dbconn->PO_Insert_ID($quotestable, $quotescolumn['qid']);

    // Let any hooks know that we have created a new item.  As this is a
    // create hook we're passing 'tid' as the extra info, which is the
    // argument that all of the other functions use to reference this
    // item
    pnModCallHooks('item', 'create', $qid, array('module' => 'Quotes'));

    // Return the id of the newly created item to the calling process
    return $qid;

}

/**
 * Delete Quote
 * @author Greg Allan
 * @author Erik Slooff
 * @param 'args['qid']' quote id
 * @return true if success, false otherwise
 */
function quotes_adminapi_delete($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present,
    // if not then set an appropriate error message and return
    if (!isset($qid) || !is_numeric($qid)) {
        pnSessionSetVar('errormsg', _QUOTESARGSERROR);
        return false;
    }

    // The user API function is called.  This takes the item ID which
    // we obtained from the input and gets us the information on the
    // appropriate item.  If the item does not exist we post an appropriate
    // message and return
    $item = pnModAPIFunc('Quotes',
            'user',
            'get',
            array('qid' => $qid));

    if ($item == false) {
        pnSessionSetVar('errormsg', _QUOTESNOSUCHITEM);
        return false;
    }

    // Security check - important to do this as early on as possible to 
    // avoid potential security holes or just too much wasted processing.
    // However, in this case we had to wait until we could obtain the item
    // name to complete the instance information so this is the first
    // chance we get to do the check
    if (!pnSecAuthAction(0, 'Quotes::', "$item[author]::$qid", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

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
    $quotestable = $pntable['quotes'];
    $quotescolumn = &$pntable['quotes_column'];

    // Delete the item - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating
    // out the sql statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $query = "DELETE FROM $quotestable
              WHERE $quotescolumn[qid] = '" . (int)pnVarPrepForStore($qid) . "'";
    $dbconn->Execute($query);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _QUOTEAPIUPDATEFAILED);
        return false;
    }

    // Let any hooks know that we have deleted an item.  As this is a
    // delete hook we're not passing any extra info
    pnModCallHooks('item', 'delete', $qid, array('module' => 'Quotes'));

    // Let the calling process know that we have finished successfully
    return true;
}

/**
 * Update Quote
 * @author Greg Allan
 * @author Erik Slooff
 * @param 'args['qid']' quote ID
 * @param 'args['qquote']' quote text
 * @param 'args['qauthor']' quote author
 * @return true if success, false otherwise
 */
function quotes_adminapi_update($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present,
    // if not then set an appropriate error message and return
    if ((!isset($qid)) || (!isset($qquote)) || (!isset($qauthor))) {
        pnSessionSetVar('errormsg', _QUOTESARGSERROR);
        return false;
    }

    // The user API function is called.  This takes the item ID which
    // we obtained from the input and gets us the information on the
    // appropriate item.  If the item does not exist we post an appropriate
    // message and return
    $item = pnModAPIFunc('Quotes',
            'user',
            'get',
            array('qid' => $qid));

    if ($item == false) {
        pnSessionSetVar('errormsg', _QUOTESNOSUCHITEM);
        return false;
    }

    // Security check - important to do this as early on as possible to 
    // avoid potential security holes or just too much wasted processing.
    // However, in this case we had to wait until we could obtain the item
    // name to complete the instance information so this is the first
    // chance we get to do the check

    // Note that at this stage we have two sets of item information, the
    // pre-modification and the post-modification.  We need to check against
    // both of these to ensure that whoever is doing the modification has
    // suitable permissions to edit the item otherwise people can potentially
    // edit areas to which they do not have suitable access
    if (!pnSecAuthAction(0, 'Quotes::', "$item[author]::$qid", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }
    if (!pnSecAuthAction(0, 'Quotes::', "$qauthor::$qid", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

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
    $quotestable = $pntable['quotes'];
    $quotescolumn = &$pntable['quotes_column'];

    // Update the item - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating
    // out the sql statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $query = "UPDATE $quotestable
              SET $quotescolumn[quote] = '" . pnVarPrepForStore($qquote) . "',
                  $quotescolumn[author] = '" . pnVarPrepForStore($qauthor) . "'
              WHERE $quotescolumn[qid] = '" . pnVarPrepForStore($qid) . "'";
    $dbconn->Execute($query);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _QUOTEAPIUPDATEFAILED);
        return false;
    }

	// New hook functions
    pnModCallHooks('item', 'update', $qid, array('module' => 'Quotes'));

    // Let the calling process know that we have finished successfully
    return true;
}

/**
 * Search for Quotes
 * @author Greg Allan
 * @author Erik Slooff
 * @param 'args['keyword']' keyword to search for
 * @return array
 */
function quotes_adminapi_search($args)
{
    extract($args);

    if (!isset($keyword)) {
        pnSessionSetVar('errormsg', _QUOTESARGSERROR);
        return false;
    }

    if (!pnSecAuthAction(0, 'Quotes::', "::", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $quotestable = $pntable['quotes'];
    $quotescolumn = &$pntable['quotes_column'];

    $query = "SELECT $quotescolumn[qid],
                     $quotescolumn[quote],
                     $quotescolumn[author] FROM $quotestable
                                          WHERE $quotescolumn[quote] LIKE '%".pnVarPrepForStore($keyword)."%'";

    $result =& $dbconn->Execute($query);

    if($result->EOF) {
        return false;
    }

    $resarray = array();

    while(list($qid, $quote, $author) = $result->fields) {
        $result->MoveNext();

        $resarray[] = array('qid' => $qid,
                            'quote' => $quote,
                            'author' => $author);
    }
    $result->Close();

    return $resarray;
}
?>