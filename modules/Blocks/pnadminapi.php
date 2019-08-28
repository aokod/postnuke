<?php 
// $Id: pnadminapi.php 20250 2006-10-09 12:00:13Z markwest $
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

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Jim McDonald
// Purpose of file:  Blocks administration API
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Blocks
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * increment position of a block
 * <br>
 * This function moves a block such that it is higher in the
 * block display
 * @author Jim McDonald
 * @param int $args ['bid'] the ID of the block to increment
 * @return bool true on success, false on failure
 */
function blocks_adminapi_inc($args)
{ 
    // Get arguments from argument array
    extract($args); 
    // Argument check
    if (!isset($bid) || !is_numeric($bid)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    } 
    // Security check
    if (!pnSecAuthAction(0, 'Blocks::', "::$bid", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    } 

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $blockstable = $pntable['blocks'];
    $blockscolumn = &$pntable['blocks_column']; 
    // Get info on current position of block
    $sql = "SELECT $blockscolumn[weight],
                   $blockscolumn[position]
            FROM $blockstable
            WHERE $blockscolumn[bid]='" . (int)pnVarPrepForStore($bid) . "'";
    $result =& $dbconn->Execute($sql);

    if ($result->EOF) {
        pnSessionSetVar('errormsg', "No such block ID $bid");
        return false;
    } 
    list($seq, $position) = $result->fields;
    $result->Close(); 
    // Get info on displaced block
    $sql = "SELECT $blockscolumn[bid],
                   $blockscolumn[weight]
            FROM $blockstable
            WHERE $blockscolumn[weight]<'" . pnVarPrepForStore($seq) . "'
            AND   $blockscolumn[position]='" . pnVarPrepForStore($position) . "'
            ORDER BY $blockscolumn[weight] DESC";
    $result = $dbconn->SelectLimit($sql, 1);
    if ($result->EOF) {
        pnSessionSetVar('errormsg', "No block directly above that one");
        return false;
    } 
    list($altbid, $altseq) = $result->fields;
    $result->Close(); 
    // Swap sequence numbers
    $sql = "UPDATE $blockstable
            SET $blockscolumn[weight]=$seq
            WHERE $blockscolumn[bid]='".(int)pnVarPrepForStore($altbid)."'";
    $dbconn->Execute($sql);
    $sql = "UPDATE $blockstable
            SET $blockscolumn[weight]=$altseq
            WHERE $blockscolumn[bid]='".(int)pnVarPrepForStore($bid)."'";
    $dbconn->Execute($sql);

    return true;
} 

/**
 * decrement position of a block
 * <br>
 * This function moves a block such that it is lower in the
 * block display
 * @author Jim McDonald 
 * @param int $args ['bid'] the ID of the block to decrement
 * @return bool true on success, false on failure
 */
function blocks_adminapi_dec($args)
{ 
    // Get arguments from argument array
    extract($args); 
    // Argument check
    if (!isset($bid) || !is_numeric($bid)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    } 
    // Security check
    if (!pnSecAuthAction(0, 'Blocks::', "::$bid", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    } 

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $blockstable = $pntable['blocks'];
    $blockscolumn = &$pntable['blocks_column']; 
    // Get info on current position of block
    $sql = "SELECT $blockscolumn[weight],
                   $blockscolumn[position]
            FROM $blockstable
            WHERE $blockscolumn[bid]='" . (int)pnVarPrepForStore($bid)."'";
    $result =& $dbconn->Execute($sql);

    if ($result->EOF) {
        pnSessionSetVar('errormsg', "No such block ID $bid");
        return false;
    } 
    list($seq, $position) = $result->fields;
    $result->Close(); 
    // Get info on displaced block
    $sql = "SELECT $blockscolumn[bid],
                   $blockscolumn[weight]
            FROM $blockstable
            WHERE $blockscolumn[weight]>'" . pnVarPrepForStore($seq) . "'
            AND   $blockscolumn[position]='" . pnVarPrepForStore($position) . "'
            ORDER BY $blockscolumn[weight] ASC";
    $result = $dbconn->SelectLimit($sql, 1);

    if ($result->EOF) {
        pnSessionSetVar('errormsg', "No block directly below that one");
        return false;
    } 
    list($altbid, $altseq) = $result->fields;
    $result->Close(); 
    // Swap sequence numbers
    $sql = "UPDATE $blockstable
            SET $blockscolumn[weight]=$seq
            WHERE $blockscolumn[bid]='".(int)pnVarPrepForStore($altbid)."'";
    $dbconn->Execute($sql);
    $sql = "UPDATE $blockstable
            SET $blockscolumn[weight]=$altseq
            WHERE $blockscolumn[bid]='".(int)pnVarPrepForStore($bid)."'";
    $dbconn->Execute($sql);

    return true;
} 

/**
 * update attributes of a block
 * @author Jim McDonald
 * @param int $args ['bid'] the ID of the block to update
 * @param string $args ['title'] the new title of the block
 * @param string $args ['position'] the new position of the block
 * @param string $args ['url'] the new URL of the block
 * @param string $args ['language'] the new language of the block
 * @param string $args ['content'] the new content of the block
 * @return bool true on success, false on failure
 */
function blocks_adminapi_update($args)
{ 
    // Get arguments from argument array
    extract($args); 
    // Optional arguments
    if (!isset($url)) {
        $url = '';
    } 
    if (!isset($content)) {
        $content = '';
    } 
    // Argument check
    if ((!isset($bid) || !is_numeric($bid)) ||
            (!isset($content)) ||
            (!isset($title)) ||
            (!isset($url)) ||
            (!isset($language)) ||
            (!isset($collapsable)) ||
            (!isset($defaultstate)) ||
            (!isset($refresh)) ||
            (!isset($position))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Get details on current block
    $blockinfo = pnBlockGetInfo($bid);

    // Security check
    if (!pnSecAuthAction(0, 'Blocks::', "$blockinfo[bkey]:$blockinfo[title]:$bid", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    } 
    if (!pnSecAuthAction(0, 'Blocks::', "$blockinfo[bkey]:$title:$bid", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    } 

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $blockstable = $pntable['blocks'];
    $blockscolumn = &$pntable['blocks_column'];

    $sql = "UPDATE $blockstable
            SET $blockscolumn[content]='" . pnVarPrepForStore($content) . "',
                $blockscolumn[url]='" . pnVarPrepForStore($url) . "',
                $blockscolumn[title]='" . pnVarPrepForStore($title) . "',
                $blockscolumn[position]='" . pnVarPrepForStore($position) . "',
                $blockscolumn[collapsable]='" . pnVarPrepForStore($collapsable) . "',
                $blockscolumn[defaultstate]='" . pnVarPrepForStore($defaultstate) . "',
                $blockscolumn[refresh]='" . pnVarPrepForStore($refresh) . "',
                $blockscolumn[blanguage]='" . pnVarPrepForStore($language) . "'
            WHERE $blockscolumn[bid]='" . (int)pnVarPrepForStore($bid)."'";
    $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _UPDATEFAILED);
        return false;
    } 

    // New hook functions
    pnModCallHooks('item', 'update', $bid, array('module' => 'Blocks'));

    return true;
} 

/**
 * create a new block
 * @author Jim McDonald
 * @param string $args ['title'] the title of the block
 * @param string $args ['position'] the position of the block
 * @param int $args ['mid'] the module ID of the block
 * @param string $args ['language'] the language of the block
 * @param int $args ['bkey'] the key of the block
 * @return mixed block Id on success, false on failure
 */
function blocks_adminapi_create($args)
{ 
    // Get arguments from argument array
    extract($args); 
    // Argument check
    if ((!isset($title)) ||
            (!isset($position)) ||
            (!isset($mid)) ||
            (!isset($language)) ||
            (!isset($collapsable)) ||
            (!isset($defaultstate)) ||
            (!isset($bkey))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    } 
    // Security check
    if (!pnSecAuthAction(0, 'Blocks::', "$bkey:$title:", ACCESS_ADD)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    } 

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $blockstable = $pntable['blocks'];
    $blockscolumn = &$pntable['blocks_column'];

    $nextId = $dbconn->GenId($blockstable);

    $sql = "INSERT INTO $blockstable (
              $blockscolumn[bid],
              $blockscolumn[bkey],
              $blockscolumn[title],
              $blockscolumn[content],
              $blockscolumn[url],
              $blockscolumn[position],
              $blockscolumn[weight],
              $blockscolumn[active],
              $blockscolumn[collapsable],
              $blockscolumn[defaultstate],
              $blockscolumn[refresh],
              $blockscolumn[last_update],
              $blockscolumn[blanguage],
              $blockscolumn[mid])
            VALUES (
              '" . (int)pnVarPrepForStore($nextId) . "',
              '" . pnVarPrepForStore($bkey) . "',
              '" . pnVarPrepForStore($title) . "',
              '',
              '',
              '" . pnVarPrepForStore($position) . "',
              0.5,
              1,
              '" . pnVarPrepForStore($collapsable) . "',
              '" . pnVarPrepForStore($defaultstate) . "',
              3600,
              0,
              '" . pnVarPrepForStore($language) . "',
              '" . (int)pnVarPrepForStore($mid) . "')";
    $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _CREATEFAILED);
        return false;
    } 
    // Get bid to return
    $bid = $dbconn->PO_Insert_ID($blockstable, $blockscolumn['bid']); 
    // Resequence the blocks
    blocks_adminapi_resequence();

	// Let other modules know we have created an item
    pnModCallHooks('item', 'create', $bid, array('module' => 'Blocks'));

    return $bid;
} 

/**
 * deactivate a block
 * @author Jim McDonald
 * @param int $args ['bid'] the ID of the block to deactivate
 * @return bool true on success, false on failure
 */
function blocks_adminapi_deactivate($args)
{ 
    // Get arguments from argument array
    extract($args); 
    // Argument check
    if (!isset($bid) || !is_numeric($bid)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    } 
    // Security check
    if (!pnSecAuthAction(0, 'Blocks::', "::$bid", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    } 

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $blockstable = $pntable['blocks'];
    $blockscolumn = &$pntable['blocks_column']; 
    // Deactivate
    $sql = "UPDATE $blockstable
            SET $blockscolumn[active] = 0
            WHERE $blockscolumn[bid] = '" . (int)pnVarPrepForStore($bid)."'";;
    $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _DEACTIVATEERROR);
        return false;
    } 

    return true;
} 

/**
 * activate a block
 * @author Jim McDonald
 * @param int $args ['bid'] the ID of the block to activate
 * @return bool true on success, false on failure
 */
function blocks_adminapi_activate($args)
{ 
    // Get arguments from argument array
    extract($args); 
    // Argument check
    if (!isset($bid) || !is_numeric($bid)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    } 
    // Security check
    if (!pnSecAuthAction(0, 'Blocks::', "::$bid", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    } 

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $blockstable = $pntable['blocks'];
    $blockscolumn = &$pntable['blocks_column']; 
    // Deactivate
    $sql = "UPDATE $blockstable
            SET $blockscolumn[active] = 1
            WHERE $blockscolumn[bid] = '" . (int)pnVarPrepForStore($bid)."'";
    $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _ACTIVATEERROR);
        return false;
    } 

    return true;
} 

/**
 * delete a block
 * @author Jim McDonald
 * @param int $args ['bid'] the ID of the block to delete
 * @return bool true on success, false on failure
 */
function blocks_adminapi_delete($args)
{ 
    // Get arguments from argument array
    extract($args); 
    // Argument check
    if (!isset($bid) || !is_numeric($bid)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    } 
    // Security check
    if (!pnSecAuthAction(0, 'Blocks::', "::$bid", ACCESS_DELETE)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    } 
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $blockstable = $pntable['blocks'];
    $blockscolumn = &$pntable['blocks_column'];

    $sql = "DELETE FROM $blockstable
            WHERE $blockscolumn[bid]='" . (int)pnVarPrepForStore($bid)."'";
    $dbconn->Execute($sql);

    blocks_adminapi_resequence(array());

    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _DELETEERROR);
        return false;
    } 

    // Let any hooks know that we have deleted an item.  As this is a
    // delete hook we're not passing any extra info
    pnModCallHooks('item', 'delete', $bid, array('module' => 'Blocks'));

    return true;
} 

/**
 * resequence a blocks table
 * @author Jim McDonald
 * @returns void
 */
function blocks_adminapi_resequence()
{
    // Security check
    if (!pnSecAuthAction(0, 'Blocks::', '::', ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    } 

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $blockstable = $pntable['blocks'];
    $blockscolumn = &$pntable['blocks_column']; 
    // Get the information
    $query = "SELECT $blockscolumn[bid],
                     $blockscolumn[position],
                     $blockscolumn[weight]
              FROM $blockstable
              ORDER BY $blockscolumn[position],
                       $blockscolumn[weight],
                       $blockscolumn[active] DESC";
    $result =& $dbconn->Execute($query); 
    // Fix sequence numbers
    $seq = 1;
    $lastpos = '';
    while (list($bid, $position, $curseq) = $result->fields) {
        $result->MoveNext(); 
        // Reset sequence number if we've changed block position
        if ($lastpos != $position) {
            $seq = 1;
        } 
        $lastpos = $position;

        if ($curseq != $seq) {
            $query = "UPDATE $blockstable
                      SET $blockscolumn[weight]=" . pnVarPrepForStore($seq) . "
                      WHERE $blockscolumn[bid]='" . (int)pnVarPrepForStore($bid)."'";
            $dbconn->Execute($query);
        } 
        $seq++;
    } 
    $result->Close();

    return;
} 

?>