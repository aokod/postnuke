<?php 
// $Id: users.php 16488 2005-07-24 12:37:25Z dabase $
// ----------------------------------------------------------------------
// PostNuke: Content Management System
// ====================================
// Module: Search/stories/topics plugin
//
// Copyright (c) 2001 by the Post Nuke development team
// http://www.postnuke.com
// -----------------------------------------------------------------------
// Modified version of:
//
// Search Module
// ===========================
//
// Copyright (c) 2001 by Patrick Kellum (webmaster@ctarl-ctarl.com)
// http://www.ctarl-ctarl.com
//
// This program is free software. You can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License.
//
//
// Filename: modules/Search/stories.php
// Original Author: Patrick Kellum
// Purpose: Search reviews/users/stories/topics
// -----------------------------------------------------------------------

$search_modules[] = array(
    'title' => 'Users',
    'func_search' => 'search_users',
    'func_opt' => 'search_users_opt'
);
function search_users_opt() {
    global
        $bgcolor2,
        $textcolor1;

    $output =& new pnHTML();
    $output->SetInputMode(_PNH_VERBATIMINPUT);

    if (pnSecAuthAction(0, 'Users::', '::', ACCESS_READ)) {
        $output->Text("<table border=\"0\" width=\"100%\"><tr style=\"background-color:$bgcolor2\"><td>
		<span style=\"text-color:$textcolor1\">
		<input type=\"checkbox\" name=\"active_users\" id=\"active_users\" value=\"1\" checked=\"checked\" tabindex=\"0\" />&nbsp;
		<label for=\"active_users\">"._SEARCH_MEMBERS."</label>
		</span></td></tr></table>");
    }

    return $output->GetOutput();
}
function search_users() {

    list($active_users,
         $startnum,
         $total,
         $bool,
         $q) = pnVarCleanFromInput('active_users',
                                   'startnum',
                                   'total',
                                   'bool',
                                   'q');

    if(empty($active_users)) {
        return;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $output =& new pnHTML();
    $output->SetInputMode(_PNH_VERBATIMINPUT);

    if (!isset($startnum) || !is_numeric($startnum)) {
        $startnum = 1;
    }
    if (isset($total) && !is_numeric($total)) {
    	unset($total);
    }

    $w = search_split_query($q);
    $flag = false;
    $column = &$pntable['users_column'];
    $query = "SELECT $column[name] as name, $column[uname] as uname, $column[uid] as uid FROM $pntable[users] WHERE ";
    foreach($w as $word) {
        if($flag) {
            switch($bool) {
                case 'AND' :
                    $query .= ' AND ';
                    break;
                case 'OR' :
                default :
                    $query .= ' OR ';
                    break;
            }
        }
        $query .= '(';
        $query .= "$column[uname] LIKE '".pnVarPrepForStore($word)."' OR ";
        $query .= "$column[name] LIKE '".pnVarPrepForStore($word)."'";
        $query .= ')';
        $flag = true;
    }
    $query .= " ORDER BY $column[uname]";

    if (empty($total)) {
        $countres =& $dbconn->Execute($query);
		// check for a db error
		if ($dbconn->ErrorNo() != 0) {
			return;
		}
        $total = $countres->PO_RecordCount();
        $countres->Close();
    }
    $result = $dbconn->SelectLimit($query, 10, $startnum-1);
	// check for a db error
	if ($dbconn->ErrorNo() != 0) {
		return;
	}

    if(!$result->EOF) {
        $output->Text(_SMEMBERS . ': ' . $total . ' ' . _SEARCHRESULTS);

        $url = "index.php?name=Search&amp;action=search&amp;active_users=1&amp;bool=$bool&amp;q=$q";

        $output->Text("<dl>");
        while(!$result->EOF) {
            $row = $result->GetRowAssoc(false);
            // some basic authcheck - might result in a wrong count...
            if (pnSecAuthAction(0,"Users::","$row[uname]::$row[uid]",ACCESS_READ)) {
            	$output->Text("<dt><a href=\"user.php?op=userinfo&amp;uname=".pnVarPrepForDisplay($row['uname'])."\">".pnVarPrepForDisplay($row['uname'])."</a></dt><dd>".pnVarPrepForDisplay($row['name'])."</dd>");
			}
            $result->MoveNext();
        }
        $output->Text("</dl>");

        // Munge URL for template
        $urltemplate = $url . "&amp;startnum=%%&amp;total=$total";
        $output->Pager($startnum,
                       $total,
                       $urltemplate,
                       10);

    }
    else {
        $output->SetInputMode(_PNH_VERBATIMINPUT);
        $output->Text(_SEARCH_NO_MEMBERS);
        $output->SetInputMode(_PNH_PARSEINPUT);
    }
    $output->Linebreak(3);

    return $output->GetOutput();
}
?>