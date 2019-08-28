<?php 
// $Id: comments.php 16113 2005-04-19 15:42:53Z larsneo $
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
// Original Author: Patrick Kellum
// Purpose: Search comments
// ----------------------------------------------------------------------


$search_modules[] = array(
    'title' => 'Comments',
    'func_search' => 'search_comments',
    'func_opt' => 'search_comments_opt'
);

function search_comments_opt() {
    global
        $bgcolor2,
        $textcolor1;

	if (!pnModAvailable('Comments')) {
		return;    
	}
    $output =& new pnHTML();
    $output->SetInputMode(_PNH_VERBATIMINPUT);

    if (pnSecAuthAction(0, 'Stories::', "::", ACCESS_READ)) {
        $output->Text("<table border=\"0\" width=\"100%\"><tr style=\"background-color:$bgcolor2\"><td>
		<span style=\"text-color:$textcolor1\">
		<input type=\"checkbox\" name=\"active_comments\" id=\"active_comments\" value=\"1\" checked=\"checked\" tabindex=\"0\" />&nbsp;
		<label for=\"active_comments\">"._SEARCH_COMMENTS."</label>
		</span></td></tr></table>");
    }

    return $output->GetOutput();
}

function search_comments() {

    list($active_comments,
         $startnum,
         $total,
         $bool,
         $q) = pnVarCleanFromInput('active_comments',
                                   'startnum',
                                   'total',
                                   'bool',
                                   'q');

    if(empty($active_comments)) {
        return;
    }

	if (!pnModAvailable('Comments')) {
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
    $column = &$pntable['comments_column'];
    $query = "SELECT $column[subject] as subject, $column[tid] as tid, ";
    $query .= "$column[sid] as sid, $column[pid] as pid, $column[comment] as comment FROM $pntable[comments] WHERE ";
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
        $query .= "$column[subject] LIKE '".pnVarPrepForStore($word)."' OR ";
        $query .= "$column[comment] LIKE '".pnVarPrepForStore($word)."'";
        $query .= ')';
        $flag = true;
    }
    $query .= " ORDER BY $column[subject]";

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
        $output->Text(_COMMENTS . ': ' . $total . ' ' . _SEARCHRESULTS);
        $output->SetInputMode(_PNH_VERBATIMINPUT);
        // Rebuild the search string from previous information
        $url = "index.php?name=Search&amp;action=search&amp;active_comments=1&amp;bool=$bool&amp;q=$q";
        $output->Text('<dl>');
        while(!$result->EOF) {
            $row = $result->GetRowAssoc(false);
            $row['comment']=strip_tags($row['comment']);
            if(strlen($row['comment']) > 128) {
	        	    $row['comment'] = substr($row['comment'],0,125) . '...';
            }
            if ($row[subject] == "") { 
            	$row[subject]="No title";
            };
            if ($row[pid] != 0) {
            	// comment with parent posting
	            $output->Text("<dt><a href=\"index.php?name=Comments&amp;req=showreply&amp;tid=$row[tid]&amp;sid=$row[sid]&amp;pid=$row[pid]\">".pnVarPrepHTMLDisplay($row[subject])."</a></dt>");
            } else {
	            // comment without parent posting
	            $output->Text("<dt><a href=\"index.php?name=Comments&amp;tid=$row[tid]&amp;sid=$row[sid]#$row[tid]\">".pnVarPrepHTMLDisplay($row[subject])."</a></dt>");
            }
						$output->Text("<dd>".pnVarPrepForDisplay($row[comment])."</dd>");
            $result->MoveNext();
        }
        $output->Text('</dl>');

        // Munge URL for template
        $urltemplate = $url . "&amp;startnum=%%&amp;total=$total";
        $output->Pager($startnum,
                       $total,
                       $urltemplate,
                       10);
    } else {
        $output->SetInputMode(_PNH_VERBATIMINPUT);
        $output->Text(_SEARCH_NO_COMMENTS);
        $output->SetInputMode(_PNH_PARSEINPUT);
    }
    $output->Linebreak(3);

    return $output->GetOutput();
}
?>