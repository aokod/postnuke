<?php
// $Id: pnadminapi.php 15320 2005-01-10 14:10:14Z markwest $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2002 by the PostNuke Development Team.
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
// Original Author of file: Jim McDonald
// Purpose of file:  Autolinks administration API
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Utility_Modules
 * @subpackage Autolinks
 * @license http://www.gnu.org/copyleft/gpl.html
*/

/**
 * Create a new autolink
 * @author Jim McDonald
 * @param $args['keyword'] keyword of the item
 * @param $args['title'] title of the item
 * @param $args['url'] url of the item
 * @param $args['comment'] comment of the item
 * @return mixed autolink ID on success, false on failure
 */
function autolinks_adminapi_create($args)
{

    // Get arguments from argument array
    extract($args);

    // Optional arguments
    if (!isset($comment)) {
        $comment = '';
    }

    // Argument check - make sure that all required arguments are present,
    // if not then set an appropriate error message and return
    if ((!isset($keyword)) ||
        (!isset($title)) ||
        (!isset($url))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Security check
    if (!pnSecAuthAction(0, 'Autolinks::', "$keyword::", ACCESS_ADD)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Get datbase setup
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $autolinkstable = $pntable['autolinks'];
    $autolinkscolumn = &$pntable['autolinks_column'];

    // Get next ID in table
    $nextId = $dbconn->GenId($autolinkstable);

    // Add item
    $sql = "INSERT INTO $autolinkstable (
              $autolinkscolumn[lid],
              $autolinkscolumn[keyword],
              $autolinkscolumn[title],
              $autolinkscolumn[url],
              $autolinkscolumn[comment])
            VALUES (
              $nextId,
              '" . pnVarPrepForStore($keyword) . "',
              '" . pnVarPrepForStore($title) . "',
              '" . pnVarPrepForStore($url) . "',
              '" . pnVarPrepForStore($comment) . "')";
    $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _AUTOLINKSLINKCREATEFAILED);
        return false;
    }

    // Get the ID of the item that we inserted
    $lid = $dbconn->PO_Insert_ID($autolinkstable, $autolinkscolumn['lid']);

    // New hook functions
    pnModCallHooks('item', 'create', $lid, array('module' => 'Autolinks'));

    // Return the id of the newly created link to the calling process
    return $lid;
}

/**
 * Delete an autolink
 * @author Jim McDonald
 * @param $args['lid'] ID of the link
 * @return bool true on success, false on failure
 */
function autolinks_adminapi_delete($args)
{
    // Get arguments from argument array
    extract($args);

    // Argument check
    if (!isset($lid)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // The user API function is called
    $link = pnModAPIFunc('Autolinks',
                         'user',
                         'get',
                         array('lid' => $lid));

    if ($link == false) {
        pnSessionSetVar('errormsg', _AUTOLINKSNOSUCHLINK);
        return false;
    }

    // Security check
    if (!pnSecAuthAction(0, 'Autolinks::', "$link[keyword]::$lid", ACCESS_DELETE)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Get datbase setup
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $autolinkstable = $pntable['autolinks'];
    $autolinkscolumn = &$pntable['autolinks_column'];

    // Delete the item
    $sql = "DELETE FROM $autolinkstable
            WHERE $autolinkscolumn[lid] = '" . (int)pnVarPrepForStore($lid) . "'";
    $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _AUTOLINKSDELETEFAILED);
        return false;
    }

    // Let any hooks know that we have deleted a link
    pnModCallHooks('item', 'delete', $lid, array('module' => 'Autolinks'));

    // Let the calling process know that we have finished successfully
    return true;
}

/**
 * Update an autolink
 * @author Jim McDonald
 * @param $args['lid'] the ID of the link
 * @param $args['keyword'] the new keyword of the link
 * @param $args['title'] the new title of the link
 * @param $args['url'] the new url of the link
 * @param $args['comment'] the new comment of the link
 */
function autolinks_adminapi_update($args)
{
    // Get arguments from argument array
    extract($args);

    if (!isset($comment)) {
        $comment = '';
    }
    // Argument check
    if ((!isset($lid)) ||
        (!isset($keyword)) ||
        (!isset($title)) ||
        (!isset($url))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // The user API function is called
    $link = pnModAPIFunc('Autolinks',
                         'user',
                         'get',
                         array('lid' => $lid));

    if ($link == false) {
        pnSessionSetVar('errormsg', _AUTOLINKSNOSUCHLINK);
        return false;
    }

    if (!pnSecAuthAction(0, 'Autolinks::', "$link[keyword]::$lid", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }
    if (!pnSecAuthAction(0, 'Autolinks::', "$keyword::$lid", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Get datbase setup
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $autolinkstable = $pntable['autolinks'];
    $autolinkscolumn = &$pntable['autolinks_column'];

    // Update the link
    $sql = "UPDATE $autolinkstable
            SET $autolinkscolumn[keyword] = '" . pnVarPrepForStore($keyword) . "',
                $autolinkscolumn[title] = '" . pnVarPrepForStore($title) . "',
                $autolinkscolumn[url] = '" . pnVarPrepForStore($url) . "',
                $autolinkscolumn[comment] = '" . pnVarPrepForStore($comment) . "'
            WHERE $autolinkscolumn[lid] = '" . (int)pnVarPrepForStore($lid) . "'";
    $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _AUTOLINKSUPDATEFAILED);
        return false;
    }

    // New hook functions
    pnModCallHooks('item', 'update', $lid, array('module' => 'Autolinks'));

    // Let the calling process know that we have finished successfully
    return true;
}

?>