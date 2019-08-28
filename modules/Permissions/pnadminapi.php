<?php
// File: $Id: pnadminapi.php 15082 2004-12-09 11:16:21Z markwest $
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
// Purpose of file:  Permissions administration API
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Permissions
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * increment sequence number of a permission
 * <br>
 * This function raises a permission higher up in the overall
 * permissions sequence, thus making it more likely to be acted
 * against
 * @author Jim McDonald
 * @version $Revision: 15082 $
 * @param string $args ['type'] the type of the permission to
 *         increment (user or group)
 * @param int $args ['pid'] the ID of the permission to increment
 * @return bool true on success, false on failure
 */
function permissions_adminapi_inc($args)
{
    // Get arguments from argument array
    extract($args);

    // Security check (if not ADMIN, we wouldn't be here.)
	// security check moved until we have the required variables
    if (!pnSecAuthAction(0, 'Permissions::', "$type::$pid", ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Argument check
    if ((!isset($type)) ||
        (!isset($pid))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Work out which tables to operate against, and
    // various other bits and pieces
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    if ($type == 'user') {
        $permtable = $pntable['user_perms'];
        $permcolumn = &$pntable['user_perms_column'];
        $permwhere = '';
    } else {
        $permtable = $pntable['group_perms'];
        $permcolumn = &$pntable['group_perms_column'];
       	// MMaes, 2003-06-23; Filter-view
        if (!is_null($permgrp) && ($permgrp != _PNPERMS_ALL)) {
        	$permwhere = " AND ($permcolumn[gid]="._PNPERMS_ALL." OR $permcolumn[gid]='".pnVarPrepForStore($permgrp)."')";
        	$showpartly = true;
        } else {
        	$permwhere = '';
        	$showpartly = false;
        }
    }

    // Get info on current perm
    $query = "SELECT $permcolumn[sequence]
              FROM $permtable
              WHERE $permcolumn[pid] = '" . (int)pnVarPrepForStore($pid) . "'";
    $result =& $dbconn->Execute($query);
    if ($result->EOF) {
        pnSessionSetVar('errormsg', _PERM_DECINCERR_NOID.$pid);
        return false;
    }
    list($seq) = $result->fields;
    $result->Close();

    if ($seq != 1) {
        $altseq = $seq-1;
        // Get info on displaced perm
        // MMaes, 2003-06-23; Filter-view: added extra check to select
        $query = "SELECT $permcolumn[pid]
                  FROM $permtable
                  WHERE $permcolumn[sequence] = '" . (int)pnVarPrepForStore($altseq) . "' $permwhere";
        if (isset($dbg)) $dbg->msg($query);
        $result =& $dbconn->Execute($query);
        if ($result->EOF) {
        	if ($showpartly) {
	        	// MMaes, 2003-06-23; Filter-view
        		// Changing the sequence by moving while in partial view may only be done if there
        		// are no invisible permissions inbetween that might be affected by the move.
	            pnSessionSetVar('errormsg', _PERM_DECINCERR_NOSWAPPART);
        	} else {
	            pnSessionSetVar('errormsg', _PERM_INCERR_NOSWAP);
	        }
            return false;
        }
        list($altpid) = $result->fields;
        $result->Close();

        // Swap sequence numbers
        $query = "UPDATE $permtable
                  SET $permcolumn[sequence] = '" . (int)pnVarPrepForStore($seq) . "'
                  WHERE $permcolumn[pid] = '" . (int)pnVarPrepForStore($altpid) . "'";
        $dbconn->Execute($query);
        $query = "UPDATE $permtable
                  SET $permcolumn[sequence] = '" . (int)pnVarPrepForStore($altseq) . "'
                  WHERE $permcolumn[pid] = '" . (int)pnVarPrepForStore($pid) . "'";
        $dbconn->Execute($query);
    }

    return true;
}

/**
 * decrement sequence number of a permission
 * @author Jim McDonald
 * @version $Revision: 15082 $
 * @param string $args ['type'] the type of the permission to
 *         decrement (user or group)
 * @param int $args ['pid'] the ID of the permission to decrement
 * @return bool true on success, false on failure
 */
function permissions_adminapi_dec($args)
{
    // Get arguments from argument array
    extract($args);

    // Security check (if not ADMIN, we wouldn't be here.)
	// security check moved until we have the required variables
    if (!pnSecAuthAction(0, 'Permissions::', "$type::$pid", ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Argument check
    if ((!isset($type)) ||
        (!isset($pid))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Work out which tables to operate against
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    if ($type == "user") {
        $permtable = $pntable['user_perms'];
        $permcolumn = &$pntable['user_perms_column'];
        $permwhere = "";
    } else {
        $permtable = $pntable['group_perms'];
        $permcolumn = &$pntable['group_perms_column'];
        // MMaes, 2003-06-23; Filter-view
        if (!is_null($permgrp) && ($permgrp != _PNPERMS_ALL)) {
        	$permwhere = " AND ($permcolumn[gid]="._PNPERMS_ALL." OR  $permcolumn[gid]='".(int)pnVarPrepForStore($permgrp)."')";
        	$showpartly = TRUE;
        } else {
        	$permwhere = "";
        	$showpartly = FALSE;
        }
    }

    // Get info on current perm
    $query = "SELECT $permcolumn[sequence]
              FROM $permtable
              WHERE $permcolumn[pid] = '" . (int)pnVarPrepForStore($pid) . "'";
    $result =& $dbconn->Execute($query);
    if ($result->EOF) {
        pnSessionSetVar('errormsg', _PERM_DECINCERR_NOID.$pid);
        return false;
    }
    list($seq) = $result->fields;
    $result->Close();

    $maxseq = permissions_adminapi_maxsequence(array('table' => $permtable,
                                                     'column' => $permcolumn['sequence']));
    if ($seq != $maxseq) {
        $altseq = $seq+1;
        // Get info on displaced perm
        // MMaes, 2003-06-23; Filter-view: added extra check to select-query
        $query = "SELECT $permcolumn[pid]
                  FROM $permtable
                  WHERE $permcolumn[sequence] = '" . (int)pnVarPrepForStore($altseq) . "' $permwhere";
        $result =& $dbconn->Execute($query);
        if ($result->EOF) {
        	if ($showpartly) {
	        	// MMaes, 2003-06-23; Filter-view
        		// Changing the sequence by moving while in partial view may only be done if there
        		// are no invisible permissions inbetween that might be affected by the move.
	            pnSessionSetVar('errormsg', _PERM_DECINCERR_NOSWAPPART);
        	} else {
	            pnSessionSetVar('errormsg', _PERM_DECERR_NOSWAP);
	        }
            return false;
        }
        list($altpid) = $result->fields;
        $result->Close();

        // Swap sequence numbers
        $query = "UPDATE $permtable
                  SET $permcolumn[sequence] = '" . (int)pnVarPrepForStore($seq) . "'
                  WHERE $permcolumn[pid] = '" . (int)pnVarPrepForStore($altpid) . "'";
        $dbconn->Execute($query);
        $query = "UPDATE $permtable
                  SET $permcolumn[sequence] = '" . (int)pnVarPrepForStore($altseq) . "'
                  WHERE $permcolumn[pid] = '" . pnVarPrepForStore($pid) . "'";
        $dbconn->Execute($query);
    }

    return true;
}

/**
 * update attributes of a permission
 * @author Jim McDonald
 * @version $Revision: 15082 $
 * @param string $args ['type'] the type of the permission to update (user or group)
 * @param int $args ['pid'] the ID of the permission to update
 * @param string $args ['realm'] the new realm of the permission
 * @param int $args ['id'] the new group/user id of the permission
 * @param string $args ['component'] the new component of the permission
 * @param string $args ['instance'] the new instance of the permission
 * @param int $args ['level'] the new level of the permission
 * @return bool true on success, false on failure
 */
function permissions_adminapi_update($args)
{
    // Get arguments from argument array
    extract($args);

    // Security check (if not ADMIN, we wouldn't be here.)
	// security check moved until we have the required variables
    if (!pnSecAuthAction(0, 'Permissions::', "$type::$pid", ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Argument check
    if ((!isset($type)) ||
        (!isset($pid)) ||
        (!isset($seq)) ||
        (!isset($oldseq)) ||
        (!isset($realm)) ||
        (!isset($id)) ||
        (!isset($component)) ||
        (!isset($instance)) ||
        (!isset($level))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Work out which tables to operate against
    if ($type == "user") {
        $permtable = $pntable['user_perms'];
        $permcolumn = &$pntable['user_perms_column'];
        $idfield = $permcolumn['uid'];
    } else {
        $permtable = $pntable['group_perms'];
        $permcolumn = &$pntable['group_perms_column'];
        $idfield = $permcolumn['gid'];
    }

    $query = "UPDATE $permtable
              SET $permcolumn[realm] = '" . (int)pnVarPrepForStore($realm) . "',
                  $idfield = '" . (int)pnVarPrepForStore($id) . "',
                  $permcolumn[component] = '" . pnVarPrepForStore($component) . "',
                  $permcolumn[instance] = '" . pnVarPrepForStore($instance) . "',
                  $permcolumn[level] = '" . (int)pnVarPrepForStore($level) . "'
              WHERE $permcolumn[pid] = '" . (int)pnVarPrepForStore($pid) . "'";
    $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', "Error updating $type permission $pid");
        return false;
    }

	if($seq != $oldseq){
		permissions_adminapi_full_resequence(array('type' => $type, 'newseq'=>$seq, 'oldseq'=>$oldseq));
	}

    return true;
}

/**
 * create a new perm
 * @author Jim McDonald
 * @version $Revision: 15082 $
 * @param string $args ['type'] the type of the permission to update (user or group)
 * @param string $args ['realm'] the new realm of the permission
 * @param int $args ['id'] the new group/user id of the permission
 * @param string $args ['component'] the new component of the permission
 * @param string $args ['instance'] the new instance of the permission
 * @param int $args ['level'] the new level of the permission
 * @return bool true on success, false on failure
 */
function permissions_adminapi_create($args)
{
    // Get arguments from argument array
    extract($args);

    // Security check (if not ADMIN, we wouldn't be here.)
	// security check moved until we have the required variables
    // $pid changes to $id as this is the form item name
    if (!pnSecAuthAction(0, 'Permissions::', "$type::$id", ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Argument check
	// MMaes, 2003-06-20: Insert Capability: added $insseq
    if ((!isset($type)) ||
        (!isset($realm)) ||
        (!isset($id)) ||
        (!isset($component)) ||
        (!isset($instance)) ||
        (!isset($level)) ||
        (!isset($insseq))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Work out which tables to operate against
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    if ($type == "user") {
        $permtable = $pntable['user_perms'];
        $permcolumn = &$pntable['user_perms_column'];
        $idfield = $permcolumn['uid'];
        $view = "secviewuserperms";
    } else {
        $permtable = $pntable['group_perms'];
        $permcolumn = &$pntable['group_perms_column'];
        $idfield = $permcolumn['gid'];
        $view = "secviewgroupperms";
    }

	// MMaes, 2003-06-20: Insert Capability
	if ($insseq == -1) {
	    $maxseq = permissions_adminapi_maxsequence(array('table' => $permtable,
	                                                     'column' => $permcolumn['sequence']));
	    $newseq = $maxseq + 1;
	} else {
	    // Increase sequence numbers
	    $query = "UPDATE $permtable
	              SET $permcolumn[sequence] = $permcolumn[sequence] + 1
	              WHERE $permcolumn[sequence] >= '" . (int)pnVarPrepForStore($insseq) . "'";
	    $dbconn->Execute($query);
	    if ($dbconn->ErrorNo() != 0) {
	        pnSessionSetVar('errormsg', _PERM_INSERR);
	        return false;
	    }
		$newseq = $insseq;
	}

    $nextId = $dbconn->GenId($permtable);

    $query = "INSERT INTO $permtable
               ($permcolumn[pid],
                $permcolumn[realm],
                $idfield,
                $permcolumn[sequence],
                $permcolumn[component],
                $permcolumn[instance],
                $permcolumn[level],
                $permcolumn[bond])
             VALUES
               ('" . (int)pnVarPrepForStore($nextId) . "',
                '" . (int)pnVarPrepForStore($realm) . "',
                '" . (int)pnVarPrepForStore($id) . "',
                '" . (int)pnVarPrepForStore($newseq) . "',
                '" . pnVarPrepForStore($component) . "',
                '" . pnVarPrepForStore($instance) . "',
                '" . (int)pnVarPrepForStore($level) . "',
                0)";
    $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', "Error adding $type permission");
        return false;
    }

	// MMaes, 2003-06-20: Clean-up
    permissions_adminapi_resequence(array('type' => $type));

    return true;
}

/**
 * delete a perm
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 * @version $Revision: 15082 $
 * @param string $args ['type'] the type of the permission to update (user or group)
 * @param int $args ['pid'] the ID of the permission to delete
 * @return bool true on success, false on failure
 */
function permissions_adminapi_delete($args)
{
    // Get arguments from argument array
    extract($args);

    // Security check
	// security check moved until we have the required variables
    if (!pnSecAuthAction(0, 'Permissions::', "$type::$pid", ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Argument check
    if ((!isset($type)) ||
        (!isset($pid))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Work out which tables to operate against
    if ($type == "user") {
        $permtable = $pntable['user_perms'];
        $permcolumn = &$pntable['user_perms_column'];
        $idfield = $permcolumn['uid'];
        $view = "secviewuserperms";
    } else {
        $permtable = $pntable['group_perms'];
        $permcolumn = &$pntable['group_perms_column'];
        $idfield = $permcolumn['gid'];
        $view = "secviewgroupperms";
    }

    $query = "DELETE FROM $permtable
              WHERE $permcolumn[pid] = '" . (int)pnVarPrepForStore($pid) . "'";
    $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', "Error deleting $type permission $pid");
        return false;
    }

    permissions_adminapi_resequence(array('type' => $type));

    return true;
}

/**
 * get the maximum sequence number currently in a given table
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 * @version $Revision: 15082 $
 * @param string $args ['table'] the table name
 * @param string $args ['column'] the sequence column name
 * @return int the maximum sequence number
 */
function permissions_adminapi_maxsequence($args)
{
    // Get arguments from argument array
    extract($args);

    // Called via the API -> security check
	// $type and $pid removed as not relevant here
    if (!pnSecAuthAction(0, 'Permissions::', "::", ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Argument check
    if ((!isset($table)) ||
        (!isset($column))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $dbconn =& pnDBGetConn(true);

    $query = "SELECT MAX($column)
              FROM $table";
    $result =& $dbconn->Execute($query);
    list($maxseq) = $result->fields;
    $result->Close();

    return($maxseq);
}

/**
 * resequence a permissions table
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 * @version $Revision: 15082 $
 * @param string $args ['type'] the type of permissions to resequence
 * @return void
 */
function permissions_adminapi_resequence($args)
{

    // Get arguments from argument array
    extract($args);

    // Called via the API -> security check
	// security check moved until we have the required variables
	// $pid removed as all permissions of a type are affected
	// this permission check seems more approapriate.
    if (!pnSecAuthAction(0, 'Permissions::', "$type::", ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Argument check
    if (!isset($type)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Work out which tables to operate against
    if ($type == "user") {
        $permtable = $pntable['user_perms'];
        $permcolumn = &$pntable['user_perms_column'];
    } else {
        $permtable = $pntable['group_perms'];
        $permcolumn = &$pntable['group_perms_column'];
    }

    // Get the information
    $query = "SELECT $permcolumn[pid],
                     $permcolumn[sequence]
              FROM $permtable
              ORDER BY $permcolumn[sequence]";
    $result =& $dbconn->Execute($query);

    // Fix sequence numbers
    $seq=1;
    while(list($pid, $curseq) = $result->fields) {

        $result->MoveNext();
        if ($curseq != $seq) {
            $query = "UPDATE $permtable
                      SET $permcolumn[sequence] = '" . (int)pnVarPrepForStore($seq) . "'
                      WHERE $permcolumn[pid] = '" . (int)pnVarPrepForStore($pid) . "'";
            $dbconn->Execute($query);
        }
        $seq++;
    }
    $result->Close();

    return;
}

/**
 * resequence permissions
 * called when a permission is assigned the same sequence number
 * as an existing permission
 * @author Chris Miller 
 * @version $Revision: 15082 $
 * @param string $args ['type'] the type of permissions to resequence
 * @param string $args ['seq'] the desired sequence
 * @param string $args ['oldseq'] the original sequence number
 * @return void
 */
function permissions_adminapi_full_resequence($args)
{

    // Get arguments from argument array
    extract($args);

    // Called via the API -> security check
	// security check moved until we have the required variables
	// $pid removed as all permissions of a type are affected
	// this permission check seems more approapriate.
    if (!pnSecAuthAction(0, 'Permissions::', "$type::", ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Argument check
    if (!isset($type)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

	if(!isset($newseq) || !isset($oldseq)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
	}

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Work out which tables to operate against
    if ($type == "user") {
        $permtable = $pntable['user_perms'];
        $permcolumn = &$pntable['user_perms_column'];
    } else {
        $permtable = $pntable['group_perms'];
        $permcolumn = &$pntable['group_perms_column'];
    }
	
	//find out the maximum sequence number
    $maxseq = permissions_adminapi_maxsequence(array('table' => $permtable,
                                                     'column' => $permcolumn['sequence']));

	if ((int)$oldseq > (int)$newseq) {
		if ($newseq < 1) {
			$newseq = 1;
		}
		// The new sequence is higher in the list
		// Get the information
		$query = "SELECT $permcolumn[pid],
			$permcolumn[sequence]
				FROM $permtable
				WHERE $permcolumn[sequence] >= '" . (int)$newseq . "'
				AND $permcolumn[sequence] <= '" . (int)$oldseq . "'
				ORDER BY $permcolumn[sequence] DESC";
		$result =& $dbconn->Execute($query);
		while(list($pid, $curseq) = $result->fields) {
			if ($curseq == $oldseq) {
				// we are dealing with the old value so make it the new value
				$curseq = $newseq;
			} else {
			$curseq++;
			}
			$result->MoveNext();
			$query = "UPDATE $permtable
				SET $permcolumn[sequence] = '" . (int)pnVarPrepForStore($curseq) . "'
				WHERE $permcolumn[pid] = '" . (int)pnVarPrepForStore($pid) . "'";
			$dbconn->Execute($query);
		}
	} else {
		// The new sequence is lower in the list
		//if the new requested sequence is bigger than
		//the maximum sequence number then set it to
		//the maximum number.  We don't want any spaces
		//in the sequence.
		if ($newseq > $maxseq) {
			$newseq = (int)$maxseq;
		}
		$query = "SELECT $permcolumn[pid],
			$permcolumn[sequence]
				FROM $permtable
				WHERE $permcolumn[sequence] >= '" . (int)$oldseq . "'
				AND $permcolumn[sequence] <= '" . (int)$newseq . "'
				ORDER BY $permcolumn[sequence] ASC";
		$result =& $dbconn->Execute($query);
		while(list($pid, $curseq) = $result->fields) {
			if ($curseq == $oldseq) {
				// we are dealing with the old value so make it the new value
				$curseq = $newseq;
			} else {
			$curseq--;
			}
			$result->MoveNext();
			$query = "UPDATE $permtable
				SET $permcolumn[sequence] = '" . (int)pnVarPrepForStore($curseq) . "'
				WHERE $permcolumn[pid] = '" . (int)pnVarPrepForStore($pid) . "'";
			$dbconn->Execute($query);
		}
	}

    $result->Close();

    return;
}

?>