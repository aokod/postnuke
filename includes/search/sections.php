<?php 
// $Id: sections.php 16113 2005-04-19 15:42:53Z larsneo $
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
// Purpose: Search sections
// ----------------------------------------------------------------------

$search_modules[] = array(
    'title' => 'Sections',
    'func_search' => 'search_sections',
    'func_opt' => 'search_sections_opt'
);

function search_sections_opt() {
    global
        $bgcolor2,
        $textcolor1,
        $secname,
        $secid;

	if (!pnModAvailable('Sections')) {
		return;    
	}

    $output =& new pnHTML();
    $output->SetInputMode(_PNH_VERBATIMINPUT);

    if (pnSecAuthAction(0, 'Sections::', "::", ACCESS_READ)) {
        $output->Text("<table border=\"0\" width=\"100%\"><tr style=\"background-color:$bgcolor2\"><td>
		<span style=\"text-color:$textcolor1\">
		<input type=\"checkbox\" name=\"active_sections\" id=\"active_sections\" value=\"1\" checked=\"checked\" tabindex=\"0\" />&nbsp;
		<label for=\"active_sections\">"._SEARCH_SECTIONS."</label>
		</span></td></tr></table>");
    }

    return $output->GetOutput();
}
function search_sections() {

    list($active_sections,
         $startnum,
         $total,
         $bool,
         $q) = pnVarCleanFromInput('active_sections',
                                   'startnum',
                                   'total',
                                   'bool',
                                   'q');

    if(empty($active_sections)) {
        return;
    }

	if (!pnModAvailable('Sections')) {
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

    $seccol = &$pntable['seccont_column'];
    $query = "SELECT $seccol[artid] as id, $seccol[title] as title, $seccol[secid] as secid, $seccol[content] as content
              FROM $pntable[seccont]
              WHERE \n";
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
        $query .= "$seccol[title] LIKE '".pnVarPrepForStore($word)."' OR \n";
        $query .= "$seccol[content] LIKE '".pnVarPrepForStore($word)."')\n";
        $flag = true;
    }
    if (pnConfigGetVar('multilingual') == 1) {
           $query .= " AND ($seccol[slanguage]='" . pnVarPrepForStore(pnUserGetLang()) . "' OR $seccol[slanguage]='')";
    }
    $query .= " ORDER BY $seccol[artid]";

	// get the total count with permissions!
    if (empty($total)) {
		$total = 0;
		$countres =& $dbconn->Execute($query);
		// check for a db error
		if ($dbconn->ErrorNo() != 0) {
			return;
		}
		while(!$countres->EOF) {
			$row = $countres->GetRowAssoc(false);
            // we know about the section id so let's get the section name
    		$column2 = &$pntable['sections_column'];
    		$result2 =& $dbconn->Execute("SELECT $column2[secname] FROM $pntable[sections] WHERE $column2[secid]=$row[secid]");
    		list($secname) = $result2->fields;
			if (pnSecAuthAction(0,"Sections::Section","$secname::$row[secid]",ACCESS_READ) && pnSecAuthAction(0,"Sections::Article","$row[title]:$secname:$row[id]",ACCESS_READ)) {
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
        $output->Text(_SECTIONS . ': ' . $total . ' ' . _SEARCHRESULTS);
        $output->SetInputMode(_PNH_VERBATIMINPUT);
        // Rebuild the search string from previous information
        $url = "index.php?name=Search&amp;action=search&amp;active_sections=1&amp;bool=$bool&amp;q=$q";
        $output->Text('<dl>');
        while(!$result->EOF) {
            $row = $result->GetRowAssoc(false);
            // we know about the section id so let's get the section name
    		$column2 = &$pntable['sections_column'];
    		$result2 =& $dbconn->Execute("SELECT $column2[secname] FROM $pntable[sections] WHERE $column2[secid]=$row[secid]");
    		list($secname) = $result2->fields;
            if (pnSecAuthAction(0,"Sections::Section","$secname::$row[secid]",ACCESS_READ) && pnSecAuthAction(0,"Sections::Article","$row[title]:$secname:$row[id]",ACCESS_READ)) {
	            $row['content']=strip_tags($row['content']);
	            if(strlen($row['content']) > 128) {
    		        	$row['content'] = substr($row['content'],0,125) . '...';
    		    	}
	            $output->Text("<dt><a href=\"index.php?name=Sections&amp;req=viewarticle&amp;artid=$row[id]\">".pnVarPrepForDisplay($row[title])."</a></dt>");
	            $output->Text("<dd>".pnVarPrepForDisplay($row[content])."</dd>");
	        }
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
        $output->Text(_SEARCH_NO_SECTIONS);
        $output->SetInputMode(_PNH_PARSEINPUT);
    }
    $output->Linebreak(3);

    return $output->GetOutput();
}
?>