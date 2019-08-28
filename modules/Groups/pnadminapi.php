<?php
// $Id: pnadminapi.php 16371 2005-07-03 12:42:41Z chestnut $
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
// Purpose of file:  Groups administration API
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Groups
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * create a new group item
 * @author Mark West
 * @param string $args['name'] name of the group
 * @return mixed group ID on success, false on failure
 */
function Groups_adminapi_create($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present,
    // if not then set an appropriate error message and return
    if (!isset($name)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Groups::', "$name::", ACCESS_ADD)) {
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
    $grouptable = $pntable['groups'];
    $groupcolumn = &$pntable['groups_column'];

    // Get next ID in table - this is required prior to any insert that
    // uses a unique ID, and ensures that the ID generation is carried
    // out in a database-portable fashion
    $nextId = $dbconn->GenId($grouptable);

    // Add item - the formatting here is not mandatory, but it does make
    // the SQL statement relatively easy to read.  Also, separating out
    // the sql statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "INSERT INTO $grouptable (
              $groupcolumn[gid],
              $groupcolumn[name])
            VALUES (
              $nextId,
              '" . pnVarPrepForStore($name) . "')";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _CREATEFAILED);
        return false;
    }

    // Get the ID of the item that we inserted.  It is possible, although
    // very unlikely, that this is different from $nextId as obtained
    // above, but it is better to be safe than sorry in this situation
    $gid = $dbconn->PO_Insert_ID($grouptable, $groupcolumn['gid']);

    // Let any hooks know that we have created a new item.  As this is a
    // create hook we're passing 'tid' as the extra info, which is the
    // argument that all of the other functions use to reference this
    // item
    pnModCallHooks('item', 'create', $gid, array('module' => 'Groups'));

    // Return the id of the newly created item to the calling process
    return $gid;
}

/**
 * delete a group item
 * @author Mark West
 * @param int $args['gid'] ID of the item
 * @return bool true on success, false on failure
 * @todo call permissions API to remove group permissions associated with the group
 */
function Groups_adminapi_delete($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present,
    // if not then set an appropriate error message and return
    if (!isset($gid)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // The user API function is called.  This takes the item ID which
    // we obtained from the input and gets us the information on the
    // appropriate item.  If the item does not exist we post an appropriate
    // message and return
    $item = pnModAPIFunc('Groups',
            'user',
            'get',
            array('gid' => $gid));

    if ($item == false) {
        pnSessionSetVar('errormsg', _GROUPSNOSUCHITEM);
        return false;
    }

    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing.
    // However, in this case we had to wait until we could obtain the item
    // name to complete the instance information so this is the first
    // chance we get to do the check
    if (!pnSecAuthAction(0, 'Groups::', "$item[name]::$gid", ACCESS_DELETE)) {
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
    $grouptable = $pntable['groups'];
    $groupcolumn = &$pntable['groups_column'];
  $groupmembershiptable = $pntable['group_membership'];
  $groupmembershipcolumn = &$pntable['group_membership_column'];
  $grouppermstable = $pntable['group_perms'];
  $grouppermscolumn = &$pntable['group_perms_column'];

    // Delete the item - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating
    // out the sql statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "DELETE FROM $grouptable
            WHERE $groupcolumn[gid] = '" . (int)pnVarPrepForStore($gid) . "'";
    $dbconn->Execute($sql);

  // remove all memberships of this group
  $sql = "DELETE FROM $groupmembershiptable
      WHERE $groupmembershipcolumn[gid] = '".(int)pnVarPrepForStore($gid)."'";
  $dbconn->Execute($sql);

  // remove any group permissions for this group
  // TODO: Call the permissions API to do this job
  $sql = "DELETE FROM $grouppermstable
      WHERE $grouppermscolumn[gid] = '".(int)pnVarPrepForStore($gid)."'";
  $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _DELETEFAILED);
        return false;
    }

    // Let any hooks know that we have deleted an item.  As this is a
    // delete hook we're not passing any extra info
    pnModCallHooks('item', 'delete', $gid, array('module' => 'Groups'));

    // Let the calling process know that we have finished successfully
    return true;
}

/**
 * update a group item
 * @author Mark West
 * @param int $args['gid'] the ID of the item
 * @param string $args['name'] the new name of the item
 * @return bool true if successful, false otherwise
 * @todo add missing 'name' to modargs check
 */
function Groups_adminapi_update($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present,
    // if not then set an appropriate error message and return
    if (!isset($gid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // The user API function is called.  This takes the item ID which
    // we obtained from the input and gets us the information on the
    // appropriate item.  If the item does not exist we post an appropriate
    // message and return
    $item = pnModAPIFunc('Groups',
            'user',
            'get',
            array('gid' => $gid));

    if ($item == false) {
        pnSessionSetVar('errormsg', _GROUPSNOSUCHITEM);
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
    if (!pnSecAuthAction(0, 'Groups::', "$item[name]::$gid", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }
    if (!pnSecAuthAction(0, 'Groups::', "$name::$gid", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Other check
    $checkname = pnModAPIFunc('Groups',
                              'admin',
                              'getgidbyname',
                              array('name'      => $name,
                                    'checkgid'  => $gid));
    if ($checkname != false) {
        pnSessionSetVar('errormsg', _GROUPSALREADYEXISTS);
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
    $grouptable = $pntable['groups'];
    $groupcolumn = &$pntable['groups_column'];

    // Update the item - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating
    // out the sql statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "UPDATE $grouptable
            SET $groupcolumn[name] = '" . pnVarPrepForStore($name) . "'
            WHERE $groupcolumn[gid] = '" . (int)pnVarPrepForStore($gid) . "'";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _UPDATEFAILED);
        return false;
    }

    // New hook functions
    pnModCallHooks('item', 'update', $gid, array('module' => 'Groups'));

    // Let the calling process know that we have finished successfully
    return true;
}

/**
 * add a user to a group item
 * @author Mark West
 * @param int $args['gid'] the ID of the item
 * @param int $args['uid'] the ID of the user
 * @return bool true if successful, false otherwise
 */
function Groups_adminapi_adduser($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present,
    // if not then set an appropriate error message and return
    if ((!isset($gid)) ||
     (!isset($uid))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // The user API function is called.  This takes the item ID which
    // we obtained from the input and gets us the information on the
    // appropriate item.  If the item does not exist we post an appropriate
    // message and return
    $item = pnModAPIFunc('Groups',
            'user',
            'get',
            array('gid' => $gid));

    if ($item == false) {
        pnSessionSetVar('errormsg', _GROUPSNOSUCHITEM);
        return false;
    }

    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Groups::', "$item[name]::$gid", ACCESS_ADD)) {
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
    $grouptable = $pntable['groups'];
    $groupcolumn = &$pntable['groups_column'];
    $groupmembershiptable = $pntable['group_membership'];
    $groupmembershipcolumn = &$pntable['group_membership_column'];

    // Add item - the formatting here is not mandatory, but it does make
    // the SQL statement relatively easy to read.  Also, separating out
    // the sql statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "INSERT INTO $groupmembershiptable (
              $groupmembershipcolumn[gid],
              $groupmembershipcolumn[uid])
            VALUES (
              '" . (int)pnVarPrepForStore($gid) . "',
              '" . (int)pnVarPrepForStore($uid) . "')";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _CREATEFAILED);
        return false;
    }

    // Let the calling process know that we have finished successfully
    return true;
}

/**
 * remove a user from a group item
 * @author Mark West
 * @param int $args['gid'] the ID of the item
 * @param int $args['uid'] the ID of the user
 * @return bool true if successful, false otherwise
 */
function Groups_adminapi_removeuser($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other
    // places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present,
    // if not then set an appropriate error message and return
    if ((!isset($gid)) ||
     (!isset($uid))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // The user API function is called.  This takes the item ID which
    // we obtained from the input and gets us the information on the
    // appropriate item.  If the item does not exist we post an appropriate
    // message and return
    $item = pnModAPIFunc('Groups',
            'user',
            'get',
            array('gid' => $gid));

    if ($item == false) {
        pnSessionSetVar('errormsg', _GROUPSNOSUCHITEM);
        return false;
    }

    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Groups::', "$item[name]::$gid", ACCESS_DELETE)) {
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
    $grouptable = $pntable['groups'];
    $groupcolumn = &$pntable['groups_column'];
    $groupmembershiptable = $pntable['group_membership'];
    $groupmembershipcolumn = &$pntable['group_membership_column'];

    // Add item - the formatting here is not mandatory, but it does make
    // the SQL statement relatively easy to read.  Also, separating out
    // the sql statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "DELETE FROM $groupmembershiptable
            WHERE $groupmembershipcolumn[gid] = '" . (int)pnVarPrepForStore($gid) . "'" .
      " AND $groupmembershipcolumn[uid] = '" . (int)pnVarPrepForStore($uid) . "'";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _CREATEFAILED);
        return false;
    }

    // Let the calling process know that we have finished successfully
    return true;
}

/**
 * get a specific group id from a group name
 * @author F. Chestnut
 * @param $args['name'] name of group item to get
 * @param $args['gid'] optional gid of the group
 * @return int item, or false on failure
 */
function Groups_adminapi_getgidbyname($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other places
    // such as the environment is not allowed, as that makes assumptions that
    // will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present, if
    // not then set an appropriate error message and return
    if (!isset($name)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn() we
    // currently just want the first item, which is the official database
    // handle.  For pnDBGetTables() we want to keep the entire tables array
    // together for easy reference later on
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // It's good practice to name the table and column definitions you are
    // getting - $table and $column don't cut it in more complex modules
    $grouptable = $pntable['groups'];
    $groupcolumn = &$pntable['groups_column'];

    // Get item - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the Execute() command allows for simpler debug operation
    // if it is ever needed
    $sql = "SELECT $groupcolumn[gid]
            FROM   $grouptable
            WHERE  $groupcolumn[name] = '" . pnVarPrepForStore($name) . "'";

    // Optional Where to use when modifying a group to check if there is
    // already another group by that name.
    if (isset($checkgid) && is_numeric($checkgid)) {
        $sql .= "AND $groupcolumn[gid] != '" . pnVarPrepForStore($checkgid) ."'";
    }

    $result =& $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    // Check for no rows found, and if so return
    if ($result->EOF) {
        return false;
    }

    // Obtain the item information from the result set
    list($gid) = $result->fields;

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Return the item array
    return $gid;
}

?>