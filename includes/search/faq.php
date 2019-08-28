<?php 
// $Id: faq.php 16113 2005-04-19 15:42:53Z larsneo $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// Search Module
// ===========================
//
// Copyright (c) 2001 by Patrick Kellum (webmaster@ctarl-ctarl.com)
// http://www.ctarl-ctarl.com
//
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
// adam_baum: faq.php based on Patrick Kellum's reviews.php search plugin
//           purpose: search the faq database.

$search_modules[] = array(
    'title' => 'FAQS',
    'func_search' => 'search_faqs',
    'func_opt' => 'search_faqs_opt'
);

function search_faqs_opt() {
    global
        $bgcolor2,
        $textcolor1;

	if (!pnModAvailable('FAQ')) {
		return;    
	}

    $output =& new pnHTML();
    $output->SetInputMode(_PNH_VERBATIMINPUT);

    if (pnSecAuthAction(0, 'FAQ::', '::', ACCESS_READ)) {
        $output->Text("<table border=\"0\" width=\"100%\"><tr style=\"background-color:$bgcolor2\"><td>
		<span style=\"text-color:$textcolor1\">
		<input type=\"checkbox\" name=\"active_faqs\" id=\"active_faqs\" value=\"1\" checked=\"checked\" tabindex=\"0\" />
		&nbsp;<label for=\"active_faqs\">"._SEARCH_FAQS."</label>
		</span></td></tr></table>");
    }

    return $output->GetOutput();
}

function search_faqs() {

    list($q,
         $bool,
         $startnum,
         $total,
         $active_faqs) = pnVarCleanFromInput('q',
                                             'bool',
                                             'startnum',
                                             'total',
                                             'active_faqs');

    if (empty($active_faqs)) {
        return;
    }

	if (!pnModAvailable('FAQ')) {
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
    $column = &$pntable['faqanswer_column'];
    $faqcatcol = &$pntable['faqcategories_column'];
    $query = "SELECT $column[id_cat] as id_cat, 
    				$column[question] as question, 
    				$column[answer] as answer,
    				$faqcatcol[categories] as categories
              FROM $pntable[faqanswer] 
              LEFT JOIN $pntable[faqcategories] ON $column[id_cat]=$faqcatcol[id_cat]
              WHERE $column[answer] != \"\" AND \n";
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
        // faqs
        $query .= "$column[question] LIKE '".pnVarPrepForStore($word)."' OR \n";
        $query .= "$column[answer] LIKE '".pnVarPrepForStore($word)."'\n";
        $query .= ')';
        $flag = true;
    }
    if (pnConfigGetVar('multilingual') == 1) {
           $query .= " AND ($faqcatcol[flanguage]='" . pnVarPrepForStore(pnUserGetLang()) . "' OR $faqcatcol[flanguage]='')";
    }
    $query .= " ORDER BY $column[id]";

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
            if (pnSecAuthAction(0,"FAQ::","$row[categories]::$row[id_cat]",ACCESS_READ)) {
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

    if (!$result->EOF) {
        $output->Text(_FAQ . ': ' . $total . ' ' . _SEARCHRESULTS);
        $output->SetInputMode(_PNH_VERBATIMINPUT);
        // Rebuild the search string from previous information
        $url = "index.php?name=Search&amp;action=search&amp;active_faqs=1&amp;bool=$bool&amp;q=$q";
        $output->Text("<dl>");
        while(!$result->EOF) {
            $row = $result->GetRowAssoc(false);
            if (pnSecAuthAction(0,"FAQ::","$row[categories]::$row[id_cat]",ACCESS_READ)) {
            	$row['answer']=strip_tags($row['answer']);
	            if(strlen($row['answer']) > 128) {
    		        	$row['answer'] = substr($row['answer'],0,125) . '...';
    		    	}
	            $output->Text("<dt><a href=\"index.php?name=FAQ&amp;myfaq=yes&amp;id_cat=$row[id_cat]\">".pnVarPrepForDisplay($row[question])."</a></dt>");
	            $output->Text("<dd>".pnVarPrepForDisplay($row[answer])."</dd>");	
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
        $output->Text(_SEARCH_NO_FAQS);
        $output->SetInputMode(_PNH_PARSEINPUT);
    }
    $output->Linebreak(3);

    return $output->GetOutput();
}
?>