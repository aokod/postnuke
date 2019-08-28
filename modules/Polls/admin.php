<?php
// File: $Id: admin.php 17540 2006-01-12 14:29:40Z larsneo $
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

if (!defined('LOADED_AS_MODULE')) { die ('Access Denied'); }

modules_get_language();
modules_get_manual();

/*********************************************************/
/* Poll/Surveys Functions                                */
/*********************************************************/

function polls_menu()
{
    GraphicAdmin();
    $statusmsg = pnGetStatusMsg();
    if ($statusmsg != '') {
    	OpenTable();
    	echo '<div class="pn-statusmsg">'.$statusmsg.'</div>';
    	CloseTable();
    }
    OpenTable();
    echo '<h1>'._POLLSADMIN.'</h1>';
    CloseTable();

    if (!pnSecAuthAction(0, 'Polls::', '::', ACCESS_EDIT)) {
        echo _POLLSEDITNOAUTH;
        CloseTable();
        include 'footer.php';
        return;
    }
    echo '<div style="text-align:center">[
	      <a href="admin.php?module=Polls&amp;op=modify">'._MODIFYPOLLS.'</a> |
	      <a href="admin.php?module=Polls">'._CREATEPOLLBUT.'</a> |
		  <a href="admin.php?module=Polls&amp;op=getConfig">'._POLLSCONF.'</a>
		  ]</div><br />';
}

function polls_createPoll()
{
    $currentlang = pnUserGetLang();

    $language = pnConfigGetVar('language');

    include ('header.php');

	polls_menu();

	OpenTable();
    if (pnSecAuthAction(0, 'Polls::', '::', ACCESS_ADD)) {
        echo '<h2>'._CREATEPOLL.'</h2>'
        .'<form action="admin.php" method="post"><div>'
        .'<input type="hidden" name="module" value="Polls" />'
		.'<input type="hidden" name="op" value="createPosted" />'
        ._POLLTITLE
		.': <input type="text" name="pollTitle" size="50" maxlength="100" /><br />'
        .'<br />'
		._LANGUAGE.': ';
        lang_dropdown();

        echo '<br />'._POLLEACHFIELD.'<br />'
            .'<table border="0">';
        for($i = 1; $i <= 12; $i++)        {
            echo '<tr>'
                .'<td>'._OPTION.' '.$i.':</td>'
				.'<td><input type="text" name="optionText['.$i.']" size="50" maxlength="50" /></td>'
                .'</tr>';
        }
        echo '</table><br />'
	        .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
            .'<input type="submit" value="'._CREATEPOLLBUT.'" />'
            .'</div></form>';
    }
    CloseTable();

    include ('footer.php');
}

function polls_createPosted($alanguage, $optionText, $pollTitle)
{
    if (!pnSecAuthAction(0, 'Polls::', "$pollTitle::", ACCESS_ADD)) {
		pnSessionSetVar('errormsg', _POLLSADDNOAUTH);
	    pnRedirect('admin.php?module=Polls&op=main');
		return true;
    }
	
	//Poll title should contain a value.
	if (empty($pollTitle)){
		pnSessionSetVar('errormsg', _POLLTITLEBLANK);
	    pnRedirect('admin.php?module=Polls&op=main');
		return true;
	}	
	
	//moved auth check to after audits to prevent auth error when an audit error occurs - skooter
    if (!pnSecConfirmAuthKey()) {
		pnSessionSetVar('errormsg', _BADAUTHKEY);
	    pnRedirect('admin.php?module=Polls&op=main');
		return true;
    }

    //don't get the db connection until needed. - skooter
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $timeStamp = time();

    $column = &$pntable['poll_desc_column'];
    $nextId = $dbconn->GenId($pntable['poll_desc']);
    $result =& $dbconn->Execute("INSERT INTO {$pntable['poll_desc']} ({$column['pollid']},
                                {$column['polltitle']}, {$column['timestamp']},
                                {$column['voters']}, {$column['planguage']})
                              	VALUES ($nextId, '".pnVarPrepForStore($pollTitle)."', '".pnVarPrepForStore($timeStamp)."', 0,
                                '".pnVarPrepForStore($alanguage)."')");
    if($dbconn->ErrorNo() != 0) {
		pnSessionSetVar('errormsg', $dbconn->ErrorNo() . ': ' . $dbconn->ErrorMsg());
	    pnRedirect('admin.php?module=Polls&op=main');
		return true;
    }
    $column = &$pntable['poll_desc_column'];
    $result =& $dbconn->Execute("SELECT {$column['pollid']}
                              FROM {$pntable['poll_desc']}
                              WHERE {$column['polltitle']}='".pnVarPrepForStore($pollTitle)."'");
    list($id) = $result->fields;
    for($i = 1; $i <= sizeof($optionText); $i++) {
		$column =&$pntable['poll_data_column'];
		$result =& $dbconn->Execute("INSERT INTO {$pntable['poll_data']} ({$column['pollid']},
									{$column['optiontext']}, {$column['optioncount']},
									{$column['voteid']})
								  	VALUES (".(int)pnVarPrepForStore($id).", '".pnVarPrepForStore($optionText[$i])."', 0, $i)");
		if($dbconn->ErrorNo() != 0) {
			pnSessionSetVar('errormsg', $dbconn->ErrorNo() . ': ' . $dbconn->ErrorMsg());
			pnRedirect('admin.php?module=Polls&op=main');
			return true;
		}
    }

    // Let any hooks know that we have created a new link
    pnModCallHooks('item', 'create', $id, 'pollID');
	
    pnRedirect('admin.php?module=Polls&op=main');
}

function polls_ModList()
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    include ('header.php');

	polls_menu();
    if (!pnSecAuthAction(0, 'Polls::', '::', ACCESS_EDIT)) {
        echo _POLLSEDITNOAUTH;
        include 'footer.php';
        return;
    }

    OpenTable();
    echo '<h2>'._EDITEXISTING.'</h2>'._CHOOSEPOLL.'<br />';
    $column =&$pntable['poll_desc_column'];
    $result =& $dbconn->Execute("SELECT {$column['pollid']}, {$column['polltitle']},
                                {$column['timestamp']}, {$column['planguage']}
                              FROM {$pntable['poll_desc']} ORDER BY {$column['timestamp']}");

    if($dbconn->ErrorNo() != 0) {
		pnSessionSetVar('errormsg', $dbconn->ErrorNo() . ': ' . $dbconn->ErrorMsg());
		pnRedirect('admin.php?module=Polls&op=main');
		return true;
    }
	echo '<ul>';
    while(list($pollID, $pollTitle, $timeStamp, $planguage) = $result->fields) {
        $result->MoveNext();

        if ($planguage == '') {
            $planguage = _ALL ;
        }
        if (pnSecAuthAction(0, 'Polls::', "$pollTitle::$pollID", ACCESS_EDIT)) {
            echo '<li>'.pnVarPrepForDisplay($pollTitle).' - ('.pnVarPrepForDisplay($planguage)
			    .') [ <a href="admin.php?module=Polls&amp;op=editPoll&amp;id='.$pollID.'">'._EDIT.'</a> ';
            if (pnSecAuthAction(0, 'Polls::', "$pollTitle::$pollID", ACCESS_DELETE)) {
                echo '| <a href="admin.php?module=Polls&amp;op=removePosted&amp;id='.$pollID.'">'._DELETE.'</a> ]';
            } else {
                echo ']';
            }
			echo '</li>';
        }
    }
	echo '</ul>';
    CloseTable();
    include ('footer.php');
}

function polls_EditPoll ($id)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    include ('header.php');
	polls_menu();
    OpenTable();
    $column =&$pntable['poll_desc_column'];
    $result_title =& $dbconn->Execute("SELECT {$column['polltitle']}, {$column['planguage']}
                                    FROM {$pntable['poll_desc']}
                                    WHERE {$column['pollid']}='".(int)pnVarPrepForStore($id)."'");
    list($pollTitle, $planguage) = $result_title->fields;

    if (!pnSecAuthAction(0, 'Polls::', "$pollTitle::$id", ACCESS_EDIT)) {
        echo _POLLSEDITNOAUTH;
        CloseTable();
        include 'footer.php';
        return;
    }

    $column =&$pntable['poll_data_column'];
    $result_data =& $dbconn->Execute("SELECT {$column['optiontext']}, {$column['optioncount']}
                                   FROM {$pntable['poll_data']}
                                   WHERE {$column['pollid']}='".(int)pnVarPrepForStore($id)."' ORDER BY {$column['voteid']}");
    echo '<form action="admin.php" method="post"><div>'
        .'<input type="hidden" name="module" value="Polls" />'
		.'<input type="hidden" name="op" value="modifyPosted" />'
        .'<input type="hidden" name="id" value="'.pnVarPrepForDisplay($id).'" />'
    	.'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
        ._POLLTITLE
		.': <input type="text" name="pollTitle" size="50" maxlength="100" value="'.pnVarPrepForDisplay($pollTitle).'" /><br />';
    $lang = languagelist();
    if (!$planguage) {
        $sel_lang[0] = ' selected="selected"';
    } else {
		$sel_lang[0] = '';
        $sel_lang[$planguage] = ' selected="selected"';
    }
    print '<br />'._LANGUAGE.': ' /* ML Dropdown with available languages to update */
        .'<select name="planguage">'
        .'<option value="" '.$sel_lang[0].'>'._ALL.'</option>'
    ;
    $handle = opendir('language');
    while ($f = readdir($handle)) {
        if (is_dir("language/$f") && (!empty($lang[$f]))) {
            $langlist[$f] = $lang[$f];
        }
    }
    asort($langlist);
    //  a bit ugly, but it works in E_ALL conditions (Andy Varganov)
    foreach ($langlist as $k=>$v){
		echo '<option value="'.$k.'"';
		if (isset($sel_lang[$k])) echo ' selected="selected"';
		echo '>'. pnVarPrepForDisplay($v) . "</option>\n";
    }
    echo '</select>';
    echo '<br />'._POLLEACHFIELD.'<br />'
    	.'<table border="0">';
    $i = 0;
    while(list($optionText, $optionCount) = $result_data->fields) {
        $i++;
        $result_data->MoveNext();
        echo '<tr><td>'._OPTION.' '.$i.'</td><td>';
        echo '<input type="text" name="optionText['.$i.']" size="50" maxlength="50" value="'.pnVarPrepForDisplay($optionText).'" />';
		echo '</td></tr>';
    }
    //??? Not necessary since we always store 12 option values for every poll - Skooter
    if ($i < 12) {
       for ($i = $i + 1; $i <=12; $i++) {
           echo '<tr><td>'._OPTION.' '.$i.'</td>'
		       .'<td><input type="text" name="optionText['.$i.']" size="50" maxlength="50" value="" /></td></tr>';
        }
    }
    echo '</table><br /><input type="submit" value="'._MODIFY.'" /></div></form>';
    CloseTable();
    include ('footer.php');
}

function polls_modifyPosted($pollTitle, $planguage, $optionText, $id)
{
    if (!pnSecAuthAction(0, 'Polls::', "$pollTitle::$id", ACCESS_EDIT)) {
        include 'header.php';
        echo _POLLSEDITNOAUTH;
        include 'footer.php';
        return;
    }
	
	//Poll title should contain a value.
	if (empty($pollTitle)){
		pnSessionSetVar('errormsg', _POLLTITLEBLANK);
	    pnRedirect('admin.php?module=Polls&op=main');
		return;
	}	

	//moved auth check to after audits to prevent auth error when an audit error occurs - skooter
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column =&$pntable['poll_desc_column'];
    $result =& $dbconn->Execute("UPDATE {$pntable['poll_desc']}
                             SET {$column['polltitle']}='".pnVarPrepForStore($pollTitle)."',
                               {$column['planguage']}='".pnVarPrepForStore($planguage)."'
                             WHERE {$column['pollid']}='".(int)pnVarPrepForStore($id)."'");
    if($dbconn->ErrorNo() != 0)
    {
        error_log("Error: polls_admin_modifyPosted" . $dbconn->ErrorMsg());
    }

    $column = &$pntable['poll_data_column'];
    for ($i = 1; $i <= sizeof($optionText); $i++) {
        $sql = "UPDATE {$pntable['poll_data']} SET {$column['optiontext']}='" . pnVarPrepForStore($optionText[$i]) . "' WHERE {$column['pollid']}='".(int)pnVarPrepForStore($id)."' AND {$column['voteid']} = '".(int)pnVarPrepForStore($i)."'";
        $result =& $dbconn->Execute($sql);
        if($dbconn->ErrorNo() != 0)
        {
            error_log("DB Error: polls_admin_modifyPosted: can not modify poll_data: " . $dbconn->ErrorMsg());
        }
    }

    pnRedirect('admin.php?module=Polls&op=modify');
}

function polls_removePosted() {
    
    list($pollTitle, $id) = pnVarCleanFromInput('pollTitle','id');
    
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!pnSecAuthAction(0, 'Polls::', "$pollTitle::$id", ACCESS_DELETE)) {
        include 'header.php';
        echo _POLLSDELNOAUTH;
        include 'footer.php';
        return;
    }
    $result =& $dbconn->Execute("DELETE FROM {$pntable['poll_desc']}
                              WHERE {$pntable['poll_desc_column']['pollid']}='".(int)pnVarPrepForStore($id)."'");
    $result =& $dbconn->Execute("DELETE FROM {$pntable['poll_data']}
                              WHERE {$pntable['poll_data_column']['pollid']}='".(int)pnVarPrepForStore($id)."'");

    // Let any hooks know that we have deleted an item
    pnModCallHooks('item', 'delete', $id, '');

    pnRedirect('admin.php?module=Polls&op=main');
}

function polls_admin_getConfig() {

    include ('header.php');

    if (!pnSecAuthAction(0, 'Polls::', '::', ACCESS_ADMIN)) {
		    echo _POLLSNOAUTH;
		    include 'footer.php';
		    return;
    }
   
    // prepare vars
    $sel_pollcomm['0'] = '';
    $sel_pollcomm['1'] = '';
    $sel_pollcomm[pnConfigGetVar('pollcomm')] = ' checked="checked"';

	polls_menu();
    OpenTable();
    print '<h2>'._POLLSCONF.'</h2>'
         .'<form action="admin.php" method="post"><div>'
         .'<table border="0"><tr><td>'
         ._SCALEBAR.":</td><td><input type=\"text\" name=\"xBarScale\" value=\"".pnConfigGetVar('BarScale')."\" size=\"4\" maxlength=\"3\" />"
         .'</td></tr><tr><td>'
         ._COMMENTSPOLLS.'</td><td>'
         ."<input type=\"radio\" name=\"xpollcomm\" value=\"1\" ".$sel_pollcomm['1']." />"._YES.' &nbsp;'
         ."<input type=\"radio\" name=\"xpollcomm\" value=\"0\" ".$sel_pollcomm['0']." />"._NO
         .'</td></tr></table>'
         ."<input type=\"hidden\" name=\"module\" value=\"".$GLOBALS['module']."\" />"
         ."<input type=\"hidden\" name=\"op\" value=\"setConfig\" />"
     	 .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
         ."<input type=\"submit\" value=\""._SUBMIT."\" />"
         ."</div></form>";
    CloseTable();
    include ('footer.php');
}

function polls_admin_setConfig($var)
{
    if (!pnSecAuthAction(0, 'Polls::', '::', ACCESS_ADMIN)) {
		    include 'header.php';
		    echo _POLLSNOAUTH;
		    include 'footer.php';
		    return;
    }
   
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }
    // Escape some characters in these variables.
    // hehe, I like doing this, much cleaner :-)
    $fixvars = array ();

    // Set any numerical variables that havn't been set, to 0. i.e. paranoia check :-)
    $fixvars = array ('xpolls_anonadddownloadlock');
    foreach ($fixvars as $v) {
        if (empty($var[$v])) {
            $var[$v] = 0;
        }
    }

    // all variables starting with x are the config vars.
    while (list ($key, $val) = each ($var)) {
        if (substr($key, 0, 1) == 'x') {
            pnConfigSetVar(substr($key, 1), $val);
        }
    }
    pnRedirect('admin.php?module=Polls&op=main');
}

function polls_admin_main($var)
{
    if (!pnSecAuthAction(0, 'Polls::', '::', ACCESS_ADMIN)) {
		    include 'header.php';
		    echo _POLLSNOAUTH;
		    include 'footer.php';
    } else {
    	$op = pnVarCleanFromInput('op');
        switch($op) {
			case 'createPosted':
			   list($alanguage, $optionText, $pollTitle) = pnVarCleanFromInput('alanguage', 'optionText', 'pollTitle');
			   polls_createPosted($alanguage, $optionText, $pollTitle);
			   break;
	
			case 'modify':
			   polls_ModList();
			   break;
	
			case 'editPoll':
			   $id = pnVarCleanFromInput('id');
			   polls_editPoll($id);
			   break;
	
			case 'modifyPosted':
			   list($planguage, $id, $pollTitle, $optionText) = 
			   pnVarCleanFromInput('planguage', 'id', 'pollTitle', 'optionText');
			   polls_ModifyPosted($pollTitle, $planguage, $optionText, $id);
			   break;
	
			case 'removePosted':
			   polls_removePosted();
			   break;
	
			case 'getConfig':
			   polls_admin_getConfig();
			   break;
	
			case 'setConfig':
			   polls_admin_setConfig($var);
			   break;
	
			default:
			   polls_createPoll();
			   break;
        }
    }
}

?>