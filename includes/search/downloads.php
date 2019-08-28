<?php // $Id: downloads.php 16113 2005-04-19 15:42:53Z larsneo $
// ----------------------------------------------------------------------
// PostNuke: Content Management System
// ====================================
// Module: Search/downloads plugin
//
// Copyright (c) 2001 by the Post Nuke development team
// http://www.postnuke.com
// ----------------------------------------------------------------------
// Search Module
// ===========================
//
// Copyright (c) 2001 by Patrick Kellum (webmaster@ctarl-ctarl.com)
// http://www.ctarl-ctarl.com
//
// This program is free software. You can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License.
// ----------------------------------------------------------------------
// Filename: includes/search/downloads.php
// Original Author: Patrick Kellum
// Purpose: Search downloads
// ----------------------------------------------------------------------
// Download plugin: adam_baum, based on Patrick Kellum's reviews plugin.
// ----------------------------------------------------------------------

$search_modules[] = array(
    'title' => 'Downloads',
    'func_search' => 'search_downloads',
    'func_opt' => 'search_downloads_opt'
);

function search_downloads_opt() {
    global
        $bgcolor2,
        $textcolor1;

	if (!pnModAvailable('Downloads')) {
		return;    
	}

    $output =& new pnHTML();
    $output->SetInputMode(_PNH_VERBATIMINPUT);

    if (pnSecAuthAction(0, 'Downloads::', '::', ACCESS_READ)) {
        $output->Text("<table border=\"0\" width=\"100%\"><tr style=\"background-color:$bgcolor2\"><td>
		<span style=\"text-color:$textcolor1\">
		<input type=\"checkbox\" name=\"active_downloads\" id=\"active_downloads\" value=\"1\" checked=\"checked\" tabindex=\"0\" />&nbsp;
		<label for=\"active_downloads\">"._SEARCH_DOWNLOADS."</label>
		</span></td></tr></table>");
    }

    return $output->GetOutput();
}

function search_downloads() {

    list($q,
         $active_downloads,
         $bool,
         $startnum,
         $total) = pnVarCleanFromInput('q',
                                       'active_downloads',
                                       'bool',
                                       'startnum',
                                       'total');

    if(empty($active_downloads)) {
        return;
    }

	if (!pnModAvailable('Downloads')) {
		return;    
	}

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $output =& new pnHTML();

    if (!isset($startnum) || !is_numeric($startnum)) {
        $startnum = 1;
    }
    if (isset($total) && !is_numeric($total)) {
    	unset($total);
    }

    $w = search_split_query($q);
    $flag = false;
    // fifers: have to explicitly name the columns so that if the underlying DB column names change, the code to access them doesn't.  We use the column names in assoc array later...
    $column = &$pntable['downloads_downloads_column'];
    $query = "SELECT $column[lid] as lid, $column[title] as title, $column[name] as name, $column[description] as description, $column[cid] as cid FROM $pntable[downloads_downloads] WHERE \n";

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
        // downloads
        $query .= "$column[description] LIKE '".pnVarPrepForStore($word)."' OR \n";
        $query .= "$column[title] LIKE '".pnVarPrepForStore($word)."' OR \n";
        $query .= "$column[submitter] LIKE '".pnVarPrepForStore($word)."' OR \n";
        $query .= "$column[name] LIKE '".pnVarPrepForStore($word)."' OR \n";
        $query .= "$column[homepage] LIKE '".pnVarPrepForStore($word)."' \n";
        $query .= ')';
        $flag = true;
    }
    $query .= " ORDER BY $column[lid]";

    if (empty($total)) {
		$total = 0;
        $countres =& $dbconn->Execute($query);
		// check for a db error
		if ($dbconn->ErrorNo() != 0) {
			return;
		}
		while(!$countres->EOF) {
			$row = $countres->GetRowAssoc(false);
			// we have a download id so get its category
        	$column2 = &$pntable['downloads_categories_column'];
            $result2 =& $dbconn->Execute("SELECT $column2[title] 
									FROM $pntable[downloads_categories] 
									WHERE $column2[cid]=$row[cid]");
	    	list($title) = $result2->fields;
			if (pnSecAuthAction(0, 'Downloads::Item', "$row[title]::$row[lid]", ACCESS_READ) && pnSecAuthAction(0, 'Downloads::Category', "$title::$row[cid]", ACCESS_READ)) {
				$total++;
			}
			$countres->MoveNext();
		}
    }

    $result = $dbconn->SelectLimit($query, 10, $startnum-1);
	// check for a db error
	if ($dbconn->ErrorNo() != 0) {
		return;
	}

    if(!$result->EOF) {
        $output->Text(_DOWNLOADS . ': ' . $total . ' ' . _SEARCHRESULTS);
        $output->SetInputMode(_PNH_VERBATIMINPUT);
        // Rebuild the search string from previous information
        $url = "index.php?name=Search&amp;action=search&amp;active_downloads=1&amp;bool=$bool&amp;q=$q";
        $output->Text("<dl>");
        while(!$result->EOF) {
          	$row = $result->GetRowAssoc(false);
			// we have a download id so get its category
        	$column2 = &$pntable['downloads_categories_column'];
            $result2 =& $dbconn->Execute("SELECT $column2[title] 
									FROM $pntable[downloads_categories] 
									WHERE $column2[cid]=$row[cid]");
	    	list($title) = $result2->fields;
			if (pnSecAuthAction(0, 'Downloads::Item', "$row[title]::$row[lid]", ACCESS_READ) && pnSecAuthAction(0, 'Downloads::Category', "$title::$row[cid]", ACCESS_READ)) {
            	$row['description']=strip_tags($row['description']);
	            if(strlen($row['description']) > 128) {
    		        	$row['description'] = substr($row['description'],0,125) . '...';
    		    	}
            	$output->Text("<dt><a href=\"index.php?name=Downloads&amp;req=viewdownloaddetails&amp;lid=$row[lid]\">".pnVarPrepForDisplay($row[title])."</a></dt>");
            	$output->Text("<dd>".pnVarPrepForDisplay($row[description])."</dd>");
			}
            $result->MoveNext();
        }
        $output->Text("</dl>");

        // Mung URL for template
        $urltemplate = $url . "&amp;startnum=%%&amp;total=$total";
        $output->Pager($startnum,
                $total,
                $urltemplate,
                10);
    } else {
        $output->SetInputMode(_PNH_VERBATIMINPUT);
        $output->Text(_SEARCH_NO_DOWNLOADS);
        $output->SetInputMode(_PNH_PARSEINPUT);
    }
    $output->Linebreak(3);

    return $output->GetOutput();
}
?>