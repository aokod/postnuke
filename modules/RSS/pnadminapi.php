<?php
// $Id: pnadminapi.php 16659 2005-08-21 11:01:23Z markwest $
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
// Purpose of file:  RSS administration API
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Content_Modules
 * @subpackage RSS
 * @license http://www.gnu.org/copyleft/gpl.html
*/

/**
 * create a new RSS item
 * @param $args['feedname'] name of the item
 * @param $args['number'] number of the item
 * @return mixed RSS item ID on success, false on failure
 */
function RSS_adminapi_create($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present,
    // if not then set an appropriate error message and return
    if ((!isset($feedname)) ||
        (!isset($url))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'RSS::Item', "$feedname::", ACCESS_ADD)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // check for maximum length to avoid cutting off URLs
    if (strlen($url > 255)) {
        pnSessionSetVar('errormsg', _RSSURLTOOLONG);
        return false;
    }

    // Check for a protocol Magpie RSS (more exactly Snoopy) can handle.
    $url_parts = parse_url($url);
    if (!isset($url_parts['scheme']) || ($url_parts['scheme'] != 'http' && $url_parts['scheme'] != 'https')) {
        pnSessionSetVar('errormsg', _RSSINVALIDPROTOCOL);
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
    $RSStable = $pntable['RSS'];
    $RSScolumn = &$pntable['RSS_column'];

    // Get next ID in table - this is required prior to any insert that
    // uses a unique ID, and ensures that the ID generation is carried
    // out in a database-portable fashion
    $nexfid = $dbconn->GenId($RSStable);

    // Add item - the formatting here is not mandatory, but it does make
    // the SQL statement relatively easy to read.  Also, separating out
    // the sql statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "INSERT INTO $RSStable (
              $RSScolumn[fid],
              $RSScolumn[name],
              $RSScolumn[url])
            VALUES (
              $nexfid,
              '" . pnVarPrepForStore($feedname) . "',
              '" . pnvarPrepForStore($url) . "')";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _CREATEFAILED);
        return false;
    }

    // Get the ID of the item that we inserted.  It is possible, although
    // very unlikely, that this is different from $nexfid as obtained
    // above, but it is better to be safe than sorry in this situation
    $fid = $dbconn->PO_Insert_ID($RSStable, $RSScolumn['fid']);

    // Let any hooks know that we have created a new item.  As this is a
    // create hook we're passing 'fid' as the extra info, which is the
    // argument that all of the other functions use to reference this
    // item
    pnModCallHooks('item', 'create', $fid, array('module' => 'RSS'));

    // Return the id of the newly created item to the calling process
    return $fid;
}

/**
 * delete a RSS item
 * @param $args['fid'] ID of the item
 * @return bool true on success, false on failure
 */
function RSS_adminapi_delete($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present,
    // if not then set an appropriate error message and return
    if (!isset($fid)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // The user API function is called.  This takes the item ID which
    // we obtained from the input and gets us the information on the
    // appropriate item.  If the item does not exist we post an appropriate
    // message and return
    $item = pnModAPIFunc('RSS',
            'user',
            'get',
            array('fid' => $fid));

    if ($item == false) {
        pnSessionSetVar('errormsg', _RSSNOSUCHITEM);
        return false;
    }

    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing.
    // However, in this case we had to wait until we could obtain the item
    // name to complete the instance information so this is the first
    // chance we get to do the check
    if (!pnSecAuthAction(0, 'RSS::Item', "$item[name]::$fid", ACCESS_DELETE)) {
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
    $RSStable = $pntable['RSS'];
    $RSScolumn = &$pntable['RSS_column'];

    // Delete the item - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating
    // out the sql statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "DELETE FROM $RSStable
            WHERE $RSScolumn[fid] = '" . (int)pnVarPrepForStore($fid) . "'";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _DELETEFAILED);
        return false;
    }

    // Let any hooks know that we have deleted an item.  As this is a
    // delete hook we're not passing any extra info
    pnModCallHooks('item', 'delete', $fid, array('module' => 'RSS'));

    // Let the calling process know that we have finished successfully
    return true;
}

/**
 * update a RSS item
 * @param $args['fid'] the ID of the item
 * @param $args['feedname'] the new name of the item
 * @param $args['number'] the new number of the item
 */
function RSS_adminapi_update($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present,
    // if not then set an appropriate error message and return
    if ((!isset($fid)) ||
        (!isset($feedname)) ||
        (!isset($url))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // The user API function is called.  This takes the item ID which
    // we obtained from the input and gets us the information on the
    // appropriate item.  If the item does not exist we post an appropriate
    // message and return
    $item = pnModAPIFunc('RSS',
            'user',
            'get',
            array('fid' => $fid));

    if ($item == false) {
        pnSessionSetVar('errormsg', _RSSNOSUCHITEM);
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
    if (!pnSecAuthAction(0, 'RSS::Item', "$item[feedname]::$fid", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }
    if (!pnSecAuthAction(0, 'RSS::Item', "$feedname::$fid", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // check for maximum length to avoid cutting off URLs
    if (strlen($url > 200)) {
        pnSessionSetVar('errormsg', _RSSURLTOOLONG);
        return false;
    }

    // Check for a protocol Magpie RSS (more exactly Snoopy) can handle.
    $url_parts = parse_url($url);
    if ($url_parts['scheme'] != 'http' && $url_parts['scheme'] != 'https') {
        pnSessionSetVar('errormsg', _RSSINVALIDPROTOCOL);
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
    $RSStable = $pntable['RSS'];
    $RSScolumn = &$pntable['RSS_column'];

    // Update the item - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating
    // out the sql statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "UPDATE $RSStable
            SET $RSScolumn[name] = '" . pnVarPrepForStore($feedname) . "',
                $RSScolumn[url] = '" . pnVarPrepForStore($url) . "'
            WHERE $RSScolumn[fid] = '" . (int)pnVarPrepForStore($fid) . "'";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _DELETEFAILED);
        return false;
    }

    // New hook functions
    pnModCallHooks('item', 'update', $fid, array('module' => 'RSS'));

    // Let the calling process know that we have finished successfully
    return true;
}

?>