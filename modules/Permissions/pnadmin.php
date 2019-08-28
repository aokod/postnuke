<?php
// File: $Id: pnadmin.php 17392 2005-12-29 17:02:43Z drak $
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
// Purpose of file:  Permissions administration
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Permissions
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * the main administration function
 * This function is the default function, and is called whenever the
 * module is initiated without defining arguments.  As such it can
 * be used for a number of things, but most commonly it either just
 * shows the module menu and returns or calls whatever the module
 * designer feels should be the default function (often this is the
 * view() function)
 * @author Jim McDonald
 * @version $Revision: 17392 $
 * @return mixed HTML string or true
 */
function permissions_admin_main()
{
    if (!pnSecAuthAction(0, 'Permissions::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    pnRedirect(pnModURL('Permissions','admin','view',array(
    												'permtype' => 'group')));
    return true;
}

/**
 * view permissions
 * @author Jim McDonald
 * @version $Revision: 17392 $
 * @param string 'permtype' permissions type
 * @return string HTML string
 */
function permissions_admin_view()
{
    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing.  For the
    // main function we want to check that the user has at least edit privilege
    // for some item within this component, or else they won't be able to do
    // anything and so we refuse access altogether.  The lowest level of access
    // for administration depends on the particular module, but it is generally
    // either 'edit' or 'delete'
    if (!pnSecAuthAction(0, 'Permissions::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
    $pnRender =& new pnRender('Permissions');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

    // Get parameters from whatever input we need.  All arguments to this
    // function should be obtained from pnVarCleanFromInput(), getting them
    // from other places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    // MMaes,2003-06-23: FilterView for single group
    list($permtype, $permgrp) = pnVarCleanFromInput('permtype', 'permgrp');

	// decide the default view
	$enableFilter = is_null(pnModGetVar('Permissions', 'filter')) ? 1 : pnModGetVar('Permissions', 'filter');
	$showSirenBar = is_null(pnModGetVar('Permissions', 'warnbar')) ? 1 : pnModGetVar('Permissions', 'warnbar');
	$rowview = is_null(pnModGetVar('Permissions', 'rowview')) ? '25' : pnModGetVar('Permissions', 'rowview');

    // Work out which tables to operate against, and
    // various other bits and pieces
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    if ($permtype == 'user') {
        $permtable = $pntable['user_perms'];
        $permcolumn = &$pntable['user_perms_column'];
        $idfield = $permcolumn['uid'];
        $mlpermtype = _USERPERMS;
        $viewperms = _VIEWUSERPERMS;
        $ids = permissions_getUsersInfo();
        // MMaes,2003-06-23: For single users we don't do this, set permgrp to default.
        $permgrp = _PNPERMS_ALL;
		$permwhere = '';
    } else {
        $permtable = $pntable['group_perms'];
        $permcolumn = &$pntable['group_perms_column'];
        $idfield = $permcolumn['gid'];
        $mlpermtype = _GROUPPERMS;
        $viewperms = _VIEWGROUPPERMS;
        $ids = permissions_getGroupsInfo();

        //if (isset($dbg)) $dbg->v($ids,'Group ids');
        //if (isset($dbg)) $dbg->v((int)$permgrp,'PermGrp');
        if (isset($permgrp) && ($permgrp != _PNPERMS_ALL)) {
        	$permwhere = "WHERE ($idfield='"._PNPERMS_ALL."' OR $idfield='".pnVarPrepForStore($permgrp)."')";
        	$showpartly = TRUE;
        } else {
        	$permgrp = _PNPERMS_ALL;
        	$permwhere = '';
        	$showpartly = FALSE;
        }
    }

    $query = "SELECT $permcolumn[pid],
                     $idfield,
                     $permcolumn[sequence],
                     $permcolumn[realm],
                     $permcolumn[component],
                     $permcolumn[instance],
                     $permcolumn[level],
                     $permcolumn[bond]
              FROM $permtable
        	  $permwhere
              ORDER BY $permcolumn[sequence]";
    if (isset($dbg)) $dbg->msg($query);
    $result  =& $dbconn->Execute($query);
    $numrows = $result->PO_RecordCount();

    // Title
	$pnRender->assign('title', $viewperms);

	// MMaes,2003-06-23: View single group
    $pnRender->assign('permtype', $permtype);
    if ($permtype != 'user' && $enableFilter) {
	    $pnRender->assign('formurl', pnModURL('Permissions', 'admin', 'view'));
		$pnRender->assign('filterlabel', _PERM_VWSHOWONLY);
		$pnRender->assign('permgrps', $ids);
		$pnRender->assign('permgrp', $permgrp);
	    $pnRender->assign('submit', _PERM_VWFILTER);
        // Show a clear warning-message that only partial Permission-table is shown.
	    if ($showpartly && $showSirenBar) {
		    $pnRender->assign('showsirenbar', 1);
			$pnRender->assign('sirenlabel', _PERM_PARTLY);
			$pnRender->assign('filteractivelabel', _PERM_WARN_FILTERACTIVE);
			$pnRender->assign('filerlabel', _PERM_SHOWING);
			if (isset($ids[$permgrp])) {
				$pnRender->assign('filtervalue', $ids[$permgrp]);
			}
	    }
	}

	$pnRender->assign('mlpermtype', $mlpermtype);

    // $realms = permissions_getRealmsInfo(); // not used yet (drak)
    $accesslevels = accesslevelnames();

    $permissions = array();
    if ($numrows>0) {

        $authid = pnSecGenAuthKey('Permissions');
        $rownum = 1;
        while(list($pid, $id, $sequence, $realm, $component, $instance, $level, $bond) = $result->fields) {
            $result->MoveNext();

            $up = array('url' => pnModURL('Permissions', 'admin', 'inc',
										  array('pid' => $pid,
												'permtype' => $permtype,
												'permgrp' => $permgrp,
												'authid' => $authid)),
                        'title' => _UP);
            $down = array('url' => pnModURL('Permissions', 'admin', 'dec',
											array('pid' => $pid,
												  'permtype' => $permtype,
												  'permgrp' => $permgrp,
												  'authid' => $authid)),
                          'title' => _DOWN);
            switch($rownum) {
                case 1:
                    $arrows = array('up' => 0, 'down' => 1);
                    break;
                case $numrows:
                    $arrows = array('up' => 1, 'down' => 0);
                    break;
                default:
                    $arrows = array('up' => 1, 'down' => 1);
                    break;
            }
            $rownum++;

    		// MMaes, 2003-06-20: Added authid to modify-url
	    	// MMaes, 2003-06-25: Changed URL to new modify-function
    		// MMaes, 2003-06-20: Direct Insert Capability
			$options = array();
            $options[] = array('url' => pnModURL('Permissions', 'admin', 'listedit',
												 array('permtype' => $permtype,
													   'permgrp' => $permgrp,
													   'action' => 'insert',
													   'insseq' => $sequence,
													   'authid' => $authid)),
                            'title' => _PERMINSBEFORE_ALTTXT,
							'imgfile' => 'insert.gif');
            $options[] = array('url' => pnModURL('Permissions', 'admin', 'listedit',
												 array('chgpid' => $pid,
													   'permtype' => $permtype,
													   'permgrp' => $permgrp,
													   'action' => 'modify',
													   'authid' => $authid)),
                          'title' => _EDIT,
                          'imgfile' => 'edit.gif');
            $options[] = array('url' => pnModURL('Permissions', 'admin', 'delete',
												 array('pid' => $pid,
													   'permtype' => $permtype,
													   'permgrp' => $permgrp,
													   'authid' => $authid)),
                            'title' => _DELETE,
							'imgfile' => 'delete.gif');

			$permissions[] = array('sequence' => $sequence,
			                       'arrows' => $arrows,
                                   // Realms not currently functional so hide the output - jgm
								   //'realms' => $realms[$realm],
								   'group' => $ids[$id],
								   'component' => $component,
								   'instance' => $instance,
								   'accesslevel' => $accesslevels[$level],
								   'options' => $options,
								   'up' => $up,
								   'down' => $down);
        }
    }
    $pnRender->assign('permissions', $permissions);

    return $pnRender->fetch('permissions_admin_view.htm');
}

/*
 * increment a permission
 * @author Jim McDonald
 * @version $Revision: 17392 $
 * @param string 'permtype' permissions type
 * @param int 'pid' permissions id
 * @return bool true
*/
function permissions_admin_inc()
{
    // MMaes,2003-06-23: Added sec.check
    if (!pnSecAuthAction(0, 'Permissions::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
    // Confirm authorisation code
    // MMaes,2003-06-23: Redirect to base if the AuthKey doesn't compute.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Permissions',
                            'admin',
                            'main'));
        return true;
    }

    // Get parameters
	// MMaes,2003-06-23: View permissions applying to single group; added permgrp
    list($permtype,
    	 $pid,
    	 $permgrp) = pnVarCleanFromInput(
    	 	'permtype',
    	 	'pid',
    	 	'permgrp');

	if (empty($permgrp)) {
		// For group-permissions, make sure we return something sensible.
		// Doesn't matter if we're looking at user-permissions...
		$permgrp = _PNPERMS_ALL;
	}

    // Load in API
    pnModAPILoad('Permissions', 'admin');

    // Pass to API
    if (pnModAPIFunc('Permissions',
                     'admin',
                     'inc',
                     array('type'		=> $permtype,
                           'pid'		=> $pid,
                           'permgrp'	=> $permgrp))) {
        // Success
        pnSessionSetVar('statusmsg', _PERM_INC);
    }

    // Redirect
    pnRedirect(pnModURL('Permissions',
                        'admin',
                        'view',
                        array('permtype'	=> $permtype,
                        	  'permgrp'		=> $permgrp)));

    return true;
}

/*
 * decrement a permission
 * @author Jim McDonald
 * @version $Revision: 17392 $
 * @param string 'permtype' permissions type
 * @param int 'pid' permissions id
 * @return bool true
*/
function permissions_admin_dec()
{
    // MMaes,2003-06-23: Added sec.check
    if (!pnSecAuthAction(0, 'Permissions::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
    // Confirm authorisation code
    // MMaes,2003-06-23: Redirect to base if the AuthKey doesn't compute.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Permissions',
                            'admin',
                            'main'));
        return true;
    }

    // Get parameters
	// MMaes,2003-06-23: View permissions applying to single group; added permgrp
    list($permtype,
    	 $pid,
    	 $permgrp) = pnVarCleanFromInput(
    	 	'permtype',
    	 	'pid',
    	 	'permgrp');

	if (!isset($permgrp) || $permgrp == '') {
		// For group-permissions, make sure we return something sensible.
		// This doesn't matter if we're looking at user-permissions...
		$permgrp = _PNPERMS_ALL;
	}

    // Load in API
    pnModAPILoad('Permissions', 'admin');

    // Pass to API
    if (pnModAPIFunc('Permissions',
                     'admin',
                     'dec',
                     array('type'		=> $permtype,
                           'pid'		=> $pid,
                           'permgrp'	=> $permgrp))) {
        // Success
        pnSessionSetVar('statusmsg', _PERM_DEC);
    }

    // Redirect
	// MMaes,2003-06-23: View permissions applying to single group; added permgrp
    pnRedirect(pnModURL('Permissions',
                        'admin',
                        'view',
                        array('permtype'	=> $permtype,
                        	  'permgrp'		=> $permgrp)));

    return true;
}


/**
 * Edit / Create permissions in the mainview
 */
function permissions_admin_listedit()
{

    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing.  For the
    // main function we want to check that the user has at least edit privilege
    // for some item within this component, or else they won't be able to do
    // anything and so we refuse access altogether.  The lowest level of access
    // for administration depends on the particular module, but it is generally
    // either 'edit' or 'delete'
    if (!pnSecAuthAction(0, 'Permissions::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Get parameters from whatever input we need.  All arguments to this
    // function should be obtained from pnVarCleanFromInput(), getting them
    // from other places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    list($chgpid,
    	 $permtype,
    	 $permgrp,
    	 $action,
    	 $insseq) = pnVarCleanFromInput('chgpid',
                                        'permtype',
                                        'permgrp',
                                        'action',
                                        'insseq');


	// set some defaults
	if (empty($permgrp)) {
		$permgrp = '';
	}

	// decide default view
	$showSirenBar = is_null(pnModGetVar('Permissions', 'warnbar')) ? 1 : pnModGetVar('Permissions', 'warnbar');
	$rowview = is_null(pnModGetVar('Permissions', 'rowview')) ? '25' : pnModGetVar('Permissions', 'rowview');

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
    $pnRender =& new pnRender('Permissions');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

    // Assign the permission levels
	$pnRender->assign('permissionlevels', accesslevelnames());

    // Work out which tables to operate against, and
    // various other bits and pieces
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    if ($permtype == 'user') {
        $permtable = $pntable['user_perms'];
        $permcolumn = &$pntable['user_perms_column'];
        $idfield = $permcolumn['uid'];
        $mlpermtype = _USERPERMS;
        $viewperms = ($action == 'modify') ? _MODIFYUSERPERM : _NEWUSERPERM;
        $ids = permissions_getUsersInfo();
        foreach($ids as $k => $v) {
        	// Add all users to dropdown-list.
            $idinfo[] = array('id' => $k,
                              'name' => $v);
        }
        // MMaes,2003-06-23: For single users we don't do this, set permgrp to default.
        $permgrp = _PNPERMS_ALL;
      	$permwhere = '';
    } else {
        $permtable = $pntable['group_perms'];
        $permcolumn = &$pntable['group_perms_column'];
        $idfield = $permcolumn['gid'];
        $mlpermtype = _GROUPPERMS;
        $viewperms = ($action == 'modify') ? _MODIFYGROUPPERM : _NEWGROUPPERM;
        $ids = permissions_getGroupsInfo();

	    // MMaes,2003-06-23: View permissions applying to single group
        if (!empty($permgrp) && ($permgrp != _PNPERMS_ALL)) {
        	$permwhere = "WHERE ($idfield='"._PNPERMS_ALL."' OR $idfield='".pnVarPrepForStore($permgrp)."')";
        	$showpartly = TRUE;
        } else {
        	$permgrp = _PNPERMS_ALL;
        	$permwhere = '';
        	$showpartly = FALSE;
        }
    }

    $query = "SELECT $permcolumn[pid],
                     $idfield,
                     $permcolumn[sequence],
                     $permcolumn[realm],
                     $permcolumn[component],
                     $permcolumn[instance],
                     $permcolumn[level],
                     $permcolumn[bond]
              FROM $permtable
        	  $permwhere
              ORDER BY $permcolumn[sequence]";
    if (isset($dbg)) $dbg->msg($query);
    $result  =& $dbconn->Execute($query);
    if ($result->EOF && $action != 'add') {
        pnSessionSetVar('errormsg', _PERM_LISTNONEFOUND);
        pnRedirect(pnModURL('modules', 'admin', 'main'));
        return;
    }

    // Javascript
    // $output->Text(permissions_javascript());

	// MMaes,2003-06-23: View single group, show warning
    if ($permtype != 'user' && $showpartly && $showSirenBar) {
	    $pnRender->assign('showsirenbar', 1);
		$pnRender->assign('sirenlabel', _PERM_PARTLY);
		$pnRender->assign('filteractivelabel', _PERM_WARN_FILTERACTIVE);
		$pnRender->assign('filerlabel', _PERM_SHOWING);
		if (isset($ids[$permgrp])) {
			$pnRender->assign('filtervalue', $ids[$permgrp]);
		}
    }

    $pnRender->assign('title', $viewperms);
    $pnRender->assign('mlpermtype', $mlpermtype);

    //$realms = permissions_getRealmsInfo(); //not used yet - drak
    $accesslevels = accesslevelnames();
    $i=0;
    $numrows = $result->PO_RecordCount();

    $pnRender->assign('idvalues', $ids);

    if ($action == 'modify') {
	    // Form-start
		$pnRender->assign('formurl', pnModURL('Permissions', 'admin', 'update'));
        $pnRender->assign('permtype', $permtype);
        $pnRender->assign('permgrp', $permgrp);
		$pnRender->assign('chgpid', $chgpid);
        // Realms hard-code4d - jgm
        $pnRender->assign('realm', 0);
        $pnRender->assign('insseq', $chgpid);
        $pnRender->assign('submit', _MODIFYPERM);

	} else if ($action == 'insert') {
	    $pnRender->assign('formurl', pnModURL('Permissions', 'admin', 'create'));
	    $pnRender->assign('permtype', $permtype);
	    $pnRender->assign('permgrp', $permgrp);
	    $pnRender->assign('insseq', $insseq);
		// Realms hard-coded - jgm
	    $pnRender->assign('realm', 0);
		$pnRender->assign('submit', _NEWPERM);

	} else if ($action == 'add') {
		// Form-start
	    $pnRender->assign('formurl', pnModURL('Permissions', 'admin', 'create'));
	    $pnRender->assign('permtype', $permtype);
	    $pnRender->assign('permgrp', $permgrp);
	    $pnRender->assign('insseq', -1);
		// Realms hard-coded - jgm
	    $pnRender->assign('realm', 0);
		$pnRender->assign('submit', _NEWPERM);
	}

	$pnRender->assign('action', $action);

    $permissions = array();
    while(list($pid, $id, $sequence, $realm, $component, $instance, $level, $bond) = $result->fields) {
        $result->MoveNext();

		$permissions[] = array(// Realms not currently functional so hide the output - jgm
							   //'realms' => $realms[$realm],
							   'pid' => $pid,
							   'group' => $ids[$id],
							   'component' => $component,
							   'instance' => $instance,
							   'accesslevel' => $accesslevels[$level],
							   'level' => $level,
							   'sequence' => $sequence);
		if ($action == 'modify' && $pid == $chgpid) {
			$pnRender->assign('selectedid', $id);
		}

    }
    $pnRender->assign('permissions', $permissions);

    return $pnRender->fetch('permissions_admin_listedit.htm');
}

/* Build_InputRow
 * This function builds the input-row for modifying and creating permissions.
*/
function permission_Build_InputRow($args)
{
	extract($args);
    if (!isset($pid) || is_null($pid) || $pid == '') {
    	$op = 'NEW';
    	$realm = '';
    	$guid = '';
    	$component = '';
    	$instance = '';
    	$level = '';
    } else {
    	$op = 'MOD';
    }

    if ($permtype == 'user') {
        $ids = permissions_getUsersInfo();
        // MMaes,2003-06-23: For single users we don't do this, set permgrp to default.
        foreach($ids as $k => $v) {
        	// Add all users to dropdown-list.
            $idinfo[] = array('id' => $k,
                              'name' => $v);
        }
        $permgrp = _PNPERMS_ALL;
    } else {
        $ids = permissions_getGroupsInfo();
	    // MMaes,2003-06-23: View permissions applying to single group
	    if (!is_null($permgrp) && ($permgrp != _PNPERMS_ALL)) {
	    	$showpartly = TRUE;
	    } else {
	    	$showpartly = FALSE;
	    	$permgrp = _PNPERMS_ALL;
	    }
        foreach($ids as $k => $v) {
            if ($showpartly && $k == $permgrp) {
            	// Only add Filter-group to dropdown-list.
	            $idinfo[] = array('id' => $k,
	                              'name' => $v);
            } elseif (!$showpartly) {
            	// Add all groups to dropdown-list.
	            $idinfo[] = array('id' => $k,
	                              'name' => $v);
            }
        }
    }

	$rowedit = is_null(pnModGetVar('Permissions', 'rowedit')) ? '35' : pnModGetVar('Permissions', 'rowedit');

	// Realm (not in use)
	// If modifying, select the current Level
    // $realms = permissions_getRealmsInfo();
    // foreach($realms as $k => $v) {
    //    $realminfo[] = array('id' => $k, 'name' => $v);
    // }
	// $row[] = $output->FormSelectMultiple('realm', $realminfo, 0, 1, $realm);

	// Group / User
	// If modifying, select the current Group/User

    // Component-textbox
    $pnRender->assign('componentvalue', $component);

    // Instance-textbox
    $pnRender->assign('instancevalue', $instance);

	// Permission-level
	// If modifying, select the current Level
	$pnRender->assign('accesslevels', accesslevelnames());

    $pnRender->assign('submit', $btnText);

	return $pnRender->fetch('permissions_admin_buildinputrow.htm');
}

/*
 * @author Jim McDonald <jim@mcdee.net>
 * @link http://www.mcdee.net
 * @version $Revision: 17392 $
 * @param string 'permtype' permissions type
 * @param int 'pid' permissions id
 * @param int 'id' group or user id
 * @param int 'realm' realm to which the permission belongs
 * @param string 'component' component string
 * @param string 'instance' instance string
 * @param int 'level' permission level
 * @return bool true
*/
function permissions_admin_update()
{
    // MMaes,2003-06-23: Added sec.check
    if (!pnSecAuthAction(0, 'Permissions::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
    // Confirm authorisation code
    // MMaes,2003-06-23: Redirect to base if the AuthKey doesn't compute.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Permissions',
                            'admin',
                            'main'));
        return true;
    }

    // Get parameters
    list($permtype,
    	 $permgrp,
         $pid,
         $seq,
         $oldseq,
         $realm,
         $id,
         $component,
         $instance,
         $level) = pnVarCleanFromInput('permtype',
                                       'permgrp',
                                       'pid',
                                       'seq',
                                       'oldseq',
                                       'realm',
                                       'id',
                                       'component',
                                       'instance',
                                       'level');

	// Since we're using TextAreas, make sure no carriage-returns etc get through unnoticed.
	$warnmsg = '';
	if (ereg("[\n\r\t\x0B]",$component)) {
		$component = trim(ereg_replace("[\n\r\t\x0B]","",$component));
		$instance = trim(ereg_replace("[\n\r\t\x0B]","",$instance));
        $warnmsg .= _PERM_COMP_INPUTERR;
	}
	if (ereg("[\n\r\t\x0B]",$instance)) {
		$component = trim(ereg_replace("[\n\r\t\x0B]","",$component));
		$instance = trim(ereg_replace("[\n\r\t\x0B]","",$instance));
        $warnmsg .= _PERM_INST_INPUTERR;
	}

    // Load in API
    pnModAPILoad('Permissions', 'admin');

    // Pass to API
    if (pnModAPIFunc('Permissions',
                     'admin',
                     'update',
                     array('type'		=> $permtype,
                           'pid'		=> $pid,
                           'seq'		=> $seq,
                           'oldseq'		=> $oldseq,
                           'realm'		=> $realm,
                           'id'			=> $id,
                           'component'	=> $component,
                           'instance'	=> $instance,
                           'level'		=> $level))) {
        // Success
		if ($warnmsg == '') {
        	pnSessionSetVar('statusmsg', _PERM_UPD);
        } else {
        	pnSessionSetVar('errormsg', $warnmsg);
        }
    }

    pnRedirect(pnModURL('Permissions',
                        'admin',
                        'view',
                        array('permtype'	=> $permtype,
                        	  'permgrp'		=> $permgrp)));

    return true;
}


/**
 * create a new permission
 * @author Jim McDonald
 * @version $Revision: 17392 $
 * @param string 'permtype' permissions type
 * @param int 'id' group or user id
 * @param int 'realm' realm to which the permission belongs
 * @param string 'component' component string
 * @param string 'instance' instance string
 * @param int 'level' permission level
 * @return bool true
 */
function permissions_admin_create()
{
    // MMaes,2003-06-23: Added sec.check
    if (!pnSecAuthAction(0, 'Permissions::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
    // Confirm authorisation code
    // MMaes,2003-06-23: Redirect to base if the AuthKey doesn't compute.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Permissions',
                            'admin',
                            'main'));
        return true;
    }

    // Get parameters
    list($permtype,
    	 $permgrp,
         $realm,
         $id,
         $component,
         $instance,
         $level,
         $insseq) = pnVarCleanFromInput('permtype',
                                        'permgrp',
                                        'realm',
                                        'id',
                                        'component',
                                        'instance',
                                        'level',
                                        'insseq');

	// Since we're using TextAreas, make sure no carriage-returns etc get through unnoticed.
	$warnmsg = '';
	if (ereg("[\n\r\t\x0B]",$component)) {
		$component = trim(ereg_replace("[\n\r\t\x0B]","",$component));
		$instance = trim(ereg_replace("[\n\r\t\x0B]","",$instance));
        $warnmsg .= _PERM_COMP_INPUTERR;
	}
	if (ereg("[\n\r\t\x0B]",$instance)) {
		$component = trim(ereg_replace("[\n\r\t\x0B]","",$component));
		$instance = trim(ereg_replace("[\n\r\t\x0B]","",$instance));
        $warnmsg .= _PERM_INST_INPUTERR;
	}

    // Load in API
    pnModAPILoad('Permissions', 'admin');

    // Pass to API
    if (pnModAPIFunc('Permissions',
                     'admin',
                     'create', array('type'	     => $permtype,
                                     'realm'     => $realm,
								     'id'        => $id,
								     'component' => $component,
								     'instance'	 => $instance,
								     'level'	 => $level,
								     'insseq'    => $insseq))) {
        // Success
		if ($warnmsg == '') {
        	pnSessionSetVar('statusmsg', _PERM_CREATED);
        } else {
        	pnSessionSetVar('errormsg', $warnmsg);
        }
    }

    pnRedirect(pnModURL('Permissions',
                        'admin',
                        'view',
                        array('permtype'    => $permtype,
                        	  'permgrp'	    => $permgrp)));

    return true;
}


/**
 * delete a permission
 * @author Jim McDonald
 * @version $Revision: 17392 $
 * @param string 'permtype' permissions type
 * @param int 'pid' permissions id
 * @return bool true
 */
function permissions_admin_delete()
{
    // MMaes,2003-06-23: Added sec.check
    if (!pnSecAuthAction(0, 'Permissions::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
    // Confirm authorisation code
    // MMaes,2003-06-23: Redirect to base if the AuthKey doesn't compute.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Permissions',
                            'admin',
                            'main'));
        return true;
    }

    // Get parameters
    list($permtype,
    	 $permgrp,
         $pid,
		 $confirmation) = pnVarCleanFromInput('permtype',
                                              'permgrp',
                                              'pid',
											  'confirmation');

    // Check for confirmation.
    if (empty($confirmation)) {
        // No confirmation yet - display a suitable form to obtain confirmation
        // of this action from the user

        // Create output object - this object will store all of our output so that
        // we can return it easily when required
        $pnRender =& new pnRender('Permissions');

        // As Admin output changes often, we do not want caching.
        $pnRender->caching = false;

        // Add a hidden field for the item ID to the output
        $pnRender->assign('pid', $pid);

		// assign the permission type and group
		$pnRender->assign('permgrp', $permgrp);
		$pnRender->assign('permtype', $permtype);

        // Return the output that has been generated by this function
        return $pnRender->fetch('permissions_admin_delete.htm');
    }

    // If we get here it means that the user has confirmed the action

    // Load in API
    pnModAPILoad('Permissions', 'admin');

    // Pass to API
    if (pnModAPIFunc('Permissions',
                     'admin',
                     'delete',
                     array('type'	=> $permtype,
                           'pid'	=> $pid))) {
        // Success
        pnSessionSetVar('statusmsg', _PERM_DEL);
    }

    pnRedirect(pnModURL('Permissions',
                        'admin',
                        'view',
                        array('permtype'	=> $permtype,
                        	  'permgrp'		=> $permgrp)));
    return true;
}


/**
 * getUsersInfo - get users information
 * Takes no parameters
 * @author Jim McDonald
 * @version $Revision: 17392 $
 * @return array users array
 * @todo remove calls to this function in favour of calls to the users module
 */
function permissions_getUsersInfo()
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $usertable = $pntable['users'];
    $usercolumn = &$pntable['users_column'];

    $query = "SELECT $usercolumn[uid],
                     $usercolumn[uname]
              FROM $usertable
              ORDER BY $usercolumn[uname]";
    $result =& $dbconn->Execute($query);
    $users[_PNPERMS_ALL] = _ALLUSERS;
    $users[_PNPERMS_UNREGISTERED] = _UNREGISTEREDUSER;
    while(list($id, $name) = $result->fields) {

        $result->MoveNext();
        $users[$id] = $name;
    }
    $result->Close();

    return($users);
}

/**
 * getGroupsInfo - get groups information
 * Takes no parameters
 * @author Jim McDonald
 * @version $Revision: 17392 $
 * @return array groups array
 * @todo remove calls to this function in favour of calls to the groups module
 */
function permissions_getGroupsInfo()
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $grouptable = $pntable['groups'];
    $groupcolumn = &$pntable['groups_column'];

    $query = "SELECT $groupcolumn[gid],
                     $groupcolumn[name]
              FROM $grouptable
              ORDER BY $groupcolumn[name]";
    $result =& $dbconn->Execute($query);
    $groups[_PNPERMS_ALL] = _ALLGROUPS;
    $groups[_PNPERMS_UNREGISTERED] = _UNREGISTEREDGROUP;
    while(list($gid, $name) = $result->fields) {
        $result->MoveNext();
        $groups[$gid] = $name;
    }
    $result->Close();

    return($groups);
}

/**
 * getRealmsInfo - get realms information
 * Takes no parameters
 * @author Jim McDonald
 * @version $Revision: 17392 $
 * @todo Move to admin API
 * @return array realms array
 */
function permissions_getRealmsInfo()
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $realmtable = $pntable['realms'];
    $realmcolumn = &$pntable['realms_column'];

	// start realms array
    $realms[0] = _ALLREALMS;

    $query = "SELECT $realmcolumn[rid],
                     $realmcolumn[name]
              FROM $realmtable";
    $result =& $dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0) {
		return $realms;
	}

    while(list($rid, $rname) = $result->fields) {
        $result->MoveNext();
        $realms[$rid] = $rname;
    }
    $result->Close();

    return($realms);
}

/**
 * showInstanceInformation  - Show instance information gathered
 *                             from blocks and modules
 * Takes no parameters
 * @author Jim McDonald
 * @version $Revision: 17392 $
 * @return string HTML string
 */
function permissions_admin_viewinstanceinfo()
{
    // MMaes,2003-06-23: This function generates output -> added sec.check
    if (!pnSecAuthAction(0, 'Permissions::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
    $pnRender =& new pnRender('Permissions');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

    // Get all permissions schemas, sort and assign to the template
    $schemas = getinstanceschemainfo();
    ksort($schemas);
    $pnRender->assign('schemas', $schemas);

    echo $pnRender->fetch('permissions_admin_viewinstanceinfo.htm');
	return true;
}


/**
 * Set configuration parameters of the module
 */
function permissions_admin_modifyconfig()
{
    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing.  For the
    // main function we want to check that the user has at least edit privilege
    // for some item within this component, or else they won't be able to do
    // anything and so we refuse access altogether.  The lowest level of access
    // for administration depends on the particular module, but it is generally
    // either 'edit' or 'delete'
    if (!pnSecAuthAction(0, 'Permissions::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
    $pnRender =& new pnRender('Permissions');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

	// assign the module vars
    $pnRender->assign(pnModGetVar('Permissions'));

    return $pnRender->fetch('permissions_admin_modifyconfig.htm');
}

/*
 * Save new settings
 */
function permissions_admin_updateconfig()
{
    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing.  For the
    // main function we want to check that the user has at least edit privilege
    // for some item within this component, or else they won't be able to do
    // anything and so we refuse access altogether.  The lowest level of access
    // for administration depends on the particular module, but it is generally
    // either 'edit' or 'delete'
    if (!pnSecAuthAction(0, 'Permissions::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Confirm authorisation code
    // MMaes,2003-06-23: Redirect to base if the AuthKey doesn't compute.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Permissions',
                            'admin',
                            'main'));
        return true;
    }

    list($filter,
    	 $warnbar,
    	 $rowview,
    	 $rowedit) = pnVarCleanFromInput('filter',
                                         'warnbar',
                                         'rowview',
                                         'rowedit');

    if (!isset($filter)) {
        $filter = 0; // FALSE
    } else {
    	$filter = 1; // TRUE
    }
    pnModSetVar('Permissions', 'filter', $filter);

    if (!isset($warnbar)) {
        $warnbar = 0; // FALSE
    } else {
        $warnbar = 1; // TRUE
    }
    pnModSetVar('Permissions', 'warnbar', $warnbar);

    if (!isset($rowview)) {
        $rowview = '25';
    }
    pnModSetVar('Permissions', 'rowview', $rowview);

    if (!isset($rowedit)) {
        $rowedit = '35';
    }
    pnModSetVar('Permissions', 'rowedit', $rowedit);

	// the module configuration has been updated successfuly
	pnSessionSetVar('statusmsg', _CONFIGUPDATED);

    pnRedirect(pnModURL('Permissions', 'admin', 'main'));
    return true;
}

?>