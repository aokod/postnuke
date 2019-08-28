<?PHP
// File: $Id: admin.php 19344 2006-07-03 08:16:27Z markwest $
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
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_MODULE')) {
    die ('Access Denied');
}

modules_get_language();
modules_get_manual();

/**
 * Comments Delete Function
 */

// Thanks to Oleg [Dark Pastor] Martos from http://www.rolemancer.ru
// to code the comments childs deletion function!

function removeSubComments($tid)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $count = 0;
    $column = &$pntable['comments_column'];
    $result =& $dbconn->Execute("SELECT $column[tid]
                                FROM $pntable[comments] WHERE $column[pid]='".pnVarPrepForStore($tid)."'");

    while (list($stid) = $result->fields) {
        $result->MoveNext();
        $count += removeSubComments($stid);
    }

    $dbconn->Execute("DELETE FROM $pntable[comments]
                      WHERE {$pntable['comments_column']['tid']}='".pnVarPrepForStore($tid)."'");

    // Let any hooks know that we have deleted an item
    pnModCallHooks('item', 'delete', $tid, '');

    return $count + 1;
}

function removeComment ($tid, $sid, $ok = 0)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if ($ok) {
        // Call recursive delete function to delete the comment and all its childs.
        // Returns total number of comments deleted.
        $num_deleted = removeSubComments($tid);
        // Update the number of comments in stories table
        $column = &$pntable['stories_column'];
        $dbconn->Execute("UPDATE $pntable[stories]
                          SET $column[comments]=$column[comments]-'$num_deleted'
                          WHERE $column[sid]='".pnVarPrepForStore($sid)."'");
        pnRedirect('index.php?name=News&file=article&sid='.$sid);
    } else {
        include('header.php');
        GraphicAdmin();
        OpenTable();
        echo '<h1>'._REMOVECOMMENTS.'</h1>';
        CloseTable();

        OpenTable();
        echo '<div style="text-align:center">'._SURETODELCOMMENTS;
        echo '<br />'._GOBACK.' | <a href="admin.php?module=Comments&amp;op=RemoveComment&amp;tid='.pnVarPrepForDisplay($tid).'&amp;sid='.pnVarPrepForDisplay($sid).'&amp;ok=1">'
        ._YES.'</a> ]</div>';
        CloseTable();
        include('footer.php');
    }
}

function removePollSubComments($tid)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column = &$pntable['pollcomments_column'];
    $result =& $dbconn->Execute("SELECT $column[tid]
                                FROM $pntable[pollcomments]
                                WHERE $column[pid]='".pnVarPrepForStore($tid)."'");

    while (list($stid) = $result->fields) {
		removePollSubComments($stid);
		$result->MoveNext();
    }
    $dbconn->Execute("DELETE FROM $pntable[pollcomments]
                      WHERE {$pntable['pollcomments_column']['tid']}='".pnVarPrepForStore($tid)."'");
}

function RemovePollComment ($tid, $pollID, $ok)
{
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();

	if ($ok == 1) {
		// Call recursive delete function to delete the comment and all its childs.
		// Returns total number of comments deleted.
		$num_deleted = removePollSubComments($tid);
		pnRedirect('index.php?name=Polls&req=results&pollID='.$pollID);
	} else {

        include('header.php');

        GraphicAdmin();
        OpenTable();

        echo '<h1>'._REMOVECOMMENTS.'</h1>';

        CloseTable();
        OpenTable();

        echo '<div style="text-align:center">'._SURETODELCOMMENTS;
        echo '<br />';
        echo '<table><tr><td>'."\n";
        echo _GOBACK;
        echo '</td><td valign="middle">'."\n";
        echo '<form action="admin.php?module=Comments&amp;op=RemovePollComment&tid='.pnVarPrepForDisplay($tid).'&pollID='.pnVarPrepForDisplay($pollID).'&ok=1" method="post"><div>';
        echo '<input type="submit" value="'.pnVarPrepForDisplay(_YES).'" />';
        echo '<input type="hidden" name="postnuke" value="postnuke" /></div></form>'."\n";
        echo '</td></tr></table></div>'."\n";

        CloseTable();
        include('footer.php');
	}
}

function comments_admin_getConfig()
{
    include 'header.php';

    if (!pnSecAuthAction(0, 'Comments::', '::', ACCESS_ADMIN)) {
        echo 'Access denied';
        include('footer.php');
        return;
    }
    
    // prepare vars
    $sel_moderate['0'] = '';
    $sel_moderate['1'] = '';
    $sel_moderate['2'] = '';
    $sel_moderate[pnConfigGetVar('moderate')] = ' selected="selected"';
    $sel_anonpost['0'] = '';
    $sel_anonpost['1'] = '';
    $sel_anonpost[pnConfigGetVar('anonpost')] = ' checked="checked"';

    GraphicAdmin();
    OpenTable();
    echo '<h1>'._COMMENTSCONFIG.'</h1>';
    CloseTable();

    print '<form action="admin.php" method="post"><div>';
    OpenTable();
    print '<h2>'._COMMENTSMOD.'</h2>'
        .'<table border="0"><tr><td>'
        ._MODTYPE.':</td><td>'
        .'<select name="xmoderate" size="1">'
        .'<option value="1"'.$sel_moderate['1'].'>'._MODADMIN.'</option>'
        .'<option value="2"'.$sel_moderate['2'].'>'._MODUSERS.'</option>'
        .'<option value="0"'.$sel_moderate['0'].'>'._NOMOD.'</option>'
        .'</select>'
        .'</td></tr></table>';
    CloseTable();

    OpenTable();
    print '<h2>'._COMMENTSOPT.'</h2>'
        .'<table border="0"><tr><td>'
         ._ALLOWANONPOST.' </td><td>'
        .'<input type="radio" name="xanonpost" value="1"'.$sel_anonpost['1'].' />'._YES.' &nbsp;'
        .'<input type="radio" name="xanonpost" value="0"'.$sel_anonpost['0'].' />'._NO
        .'</td></tr><tr><td>'
        ._COMMENTSLIMIT.":</td><td><input type=\"text\" name=\"xcommentlimit\" value=\"".pnVarPrepForDisplay(pnConfigGetVar('commentlimit'))."\" size=\"11\" maxlength=\"10\" />"
        .'</td></tr></table>'
        .'<input type="hidden" name="module" value="Comments" />'
        .'<input type="hidden" name="op" value="setConfig" />'
		.'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
        .'<input type="submit" value="'._SUBMIT.'" />';
    CloseTable();
    print "</div></form>";
    include ('footer.php');

}

function comments_admin_setConfig()
{
    if (!pnSecAuthAction(0, 'Comments::', '::', ACCESS_ADMIN)) {
        include('header.php');
        echo 'Access denied';
        include('footer.php');
        return;
    }
    	
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }
    // Get configuration variables from input
    list($var['anonpost'],
         $var['commentlimit'],
         $var['moderate']) = pnVarCleanFromInput('xanonpost',
						 						 'xcommentlimit',
						 						 'xmoderate');

    // Set any numerical variables that havn't been set, to 0. i.e. paranoia check :-)
    $fixvars = array ('anonpost', 'commentlimit','moderate');
    foreach ($fixvars as $v) {
        if (!isset($var[$v]) || !is_numeric($var[$v])) {
            if ($v == 'commentlimit') {
                $var[$v] = 4096;
            } else {
                $var[$v] = 0;
            }
        }
    }

    // Set configuration variables
    while (list ($key, $val) = each ($var)) {
		pnConfigSetVar($key, $val);
    }
    pnSessionSetVar('statusmsg', _CONFIGUPDATED);
    pnRedirect('admin.php');
}

function comments_admin_main($var)
{
    if (!pnSecAuthAction(0, 'Comments::', '::', ACCESS_ADMIN)) {
        include('header.php');
        echo 'Access denied';
        include('footer.php');
        return;
    }
    	
   list($op,
   	$ok,
   	$tid,
   	$sid,
   	$pollID) = pnVarCleanFromInput ('op',
   					'ok',
   					'tid',
   					'sid',
   					'pollID');

   switch ($op) {

    case 'RemoveComment':
		removeComment ($tid, $sid, $ok);
        break;

    case 'removeSubComments':
		removeSubComments($tid);
        break;

    case 'removePollSubComments':
		removePollSubComments($tid);
        break;

    case 'RemovePollComment':
        RemovePollComment($tid, $pollID, $ok);
        break;

    case 'getConfig':
        comments_admin_getConfig();
        break;

    case 'setConfig':
        comments_admin_setConfig();
        break;

    default:
        comments_admin_getConfig();
        break;
   }

}
?>