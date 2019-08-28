<?php
// $Id: stories.php 19277 2006-06-24 14:34:17Z markwest $
// ----------------------------------------------------------------------
// PostNuke: Content Management System
// ====================================
// Module: Search/stories/topics plugin
//
// Copyright (c) 2001 by the PostNuke development team
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
    'title' => 'Stories',
    'func_search' => 'search_stories',
    'func_opt' => 'search_stories_opt'
);

function search_stories_opt()
{
    global $bgcolor2, $textcolor1, $info;

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!pnModAvailable('News')) {
        return;
    }

    $output =& new pnHTML();
    $output->SetInputMode(_PNH_VERBATIMINPUT);

    if (pnSecAuthAction(0, 'Stories::', "::", ACCESS_READ)) {
        $output->Text("<table border=\"0\" width=\"100%\"><tr style=\"background-color:$bgcolor2\"><td>
        <span style=\"text-color:$textcolor1\">
        <input type=\"checkbox\" name=\"active_stories\" id=\"active_stories\" value=\"1\" checked=\"checked\" tabindex=\"0\" />&nbsp;
        <label for=\"active_stories\">"._SEARCH_STORIES_TOPICS."</label>
        </span></td></tr></table>");
        $output->Text("<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">
        <tr><td align=\"right\" valign=\"top\">
        <label for=\"stories_topics\">"._TOPIC."</label>:</td>
        <td><select name=\"stories_topics[]\" id=\"stories_topics\" multiple=\"multiple\">
        <option value=\"\" selected=\"selected\">". _SRCHALLTOPICS."</option>");
        $column = &$pntable['topics_column'];
        $result =& $dbconn->Execute("SELECT $column[tid] as topicid, $column[topictext] as topictext, $column[topicname] as topicname
                                     FROM $pntable[topics]
                                     ORDER BY $column[topictext]");

        while (!$result->EOF) {
            $row = $result->GetRowAssoc(false);
            if (pnSecAuthAction(0, 'Topics::Topic', "$row[topicname]::$row[topicid]", ACCESS_READ)){
                if (strlen($row['topictext']) > 23) {
                    $row['topictext'] = substr($row['topictext'],0,20) . '...';
                }
                $output->Text("<option value=\"$row[topicid]\">$row[topictext]</option>");
            }
            $result->MoveNext();
        }
        $output->Text("</select></td></tr>");
        // categories
        $output->Text("<tr><td align=\"right\" valign=\"top\">
        <label for=\"stories_cat\">"._CATEGORY."</label>:</td>
        <td><select name=\"stories_cat[]\" id=\"stories_cat\" multiple=\"multiple\">
        <option value=\"\" selected=\"selected\">"._SRCHALLCATEGORIES."</option>");
        $column = &$pntable['stories_cat_column'];
        $result =& $dbconn->Execute("SELECT $column[catid] as catid, $column[title] as title
                                     FROM $pntable[stories_cat]
                                     ORDER BY $column[title]");

        while(!$result->EOF) {
            $row = $result->GetRowAssoc(false);
            if ($row['title'] != '') {
                if (pnSecAuthAction(0, 'Stories::Story', ":$row[title]:", ACCESS_READ)) {
                    if(strlen($row['title']) > 23) {
                        $row['title'] = substr($row['title'],0,20) . '...';
                    }
                    $output->Text("<option value=\"$row[catid]\">$row[title]</option>");
                }
            }
            $result->MoveNext();
        }
        $output->Text("</select></td></tr>");

        // author
        $output->Text("<tr><td align=\"right\" valign=\"top\">
        <label for=\"stories_author\">"._AUTHOR."</label>:</td>
        <td colspan=\"3\">
        <input type=\"text\" name=\"stories_author\" id=\"stories_author\" size=\"20\" maxlength=\"255\" tabindex=\"0\" />
        </td></tr></table>");
    }
    return $output->GetOutput();
}

function search_stories()
{
    list($startnum,
         $active_stories,
         $total,
         $stories_topics,
         $stories_cat,
         $stories_author,
         $q,
         $bool) = pnVarCleanFromInput('startnum',
                                      'active_stories',
                                      'total',
                                      'stories_topics',
                                      'stories_cat',
                                      'stories_author',
                                      'q',
                                      'bool');

    if(!isset($active_stories) || !$active_stories) {
        return;
    }

    if (!pnModAvailable('News')) {
        return;
    }

    $output =& new pnHTML();

    if (!isset($startnum) || !is_numeric($startnum)) {
        $startnum = 1;
    }
    if (isset($total) && !is_numeric($total)) {
        unset ($total);
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (empty($bool)) {
        $bool = 'OR';
    }

    $flag = false;

    $storcol = &$pntable['stories_column'];
    $stcatcol = &$pntable['stories_cat_column'];
    $topcol = &$pntable['topics_column'];
    $query = '';
    $query1 = "SELECT $storcol[sid] as sid,
                     $topcol[tid] as topicid,
                     $topcol[topicname] as topicname,
                     $topcol[topictext] as topictext,
                     $storcol[catid] as catid,
                     $storcol[time] AS fdate,
                     $storcol[title] AS story_title,
                     $storcol[aid] AS aid,
                     $stcatcol[title] AS cat_title
               FROM $pntable[stories]
               LEFT JOIN $pntable[stories_cat] ON ($storcol[catid]=$stcatcol[catid])
               LEFT JOIN $pntable[topics] ON ($storcol[topic]=$topcol[tid])
               WHERE ";

    // hack to get this to work, but much better than what we had before
    //$query .= " 1 = 1 ";
    // words
    $w = search_split_query($q);
    if (isset($w)) {
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
            $query .= "$storcol[title] LIKE '".pnVarPrepForStore($word)."' OR ";
            $query .= "$storcol[hometext] LIKE '".pnVarPrepForStore($word)."' OR ";
            $query .= "$storcol[bodytext] LIKE '".pnVarPrepForStore($word)."' OR ";
            //$query .= "$storcol[comments] LIKE '".pnVarPrepForStore($word)."' OR ";
            $query .= "$storcol[informant] LIKE '".pnVarPrepForStore($word)."' OR ";
            $query .= "$storcol[notes] LIKE '".pnVarPrepForStore($word)."'";
            $query .= ')';
            $flag = true;
            $no_flag = false;
        }
    } else {
        $no_flag = true;
    }
    // topics
    if (isset($stories_topics) && !empty($stories_topics)) {
        $flag = false;
        $start_flag = false;
        // dont set AND/OR if nothing is in front
        foreach ($stories_topics as $v) {
            if (empty($v)) continue;
            if ( (!$no_flag) and (!$start_flag) ) {
                $query .= ' AND (';
                $start_flag = true;
            }
            if ($flag) $query .= ' OR ';
            $query .= "$storcol[topic]='".pnVarPrepForStore($v)."'";
            $flag = true;
        }
        if ( (!$no_flag) and ($start_flag) ) {
            $query .= ') ';
            $no_flag = false;
        }
    }
    // categories
    if (!is_array($stories_cat)) $stories_cat[0] = '';
    if (isset($stories_cat[0]) &&(!empty($stories_cat[0]))) {
         if (!$no_flag) {
             $query .= ' AND (';
         }
         $flag = false;
         foreach($stories_cat as $v) {
             if($flag) {
                 $query .= ' OR ';
             }
             $query .= "$stcatcol[catid]='".pnVarPrepForStore($v)."'";
             $flag = true;
         }
         if (!$no_flag) {
             $query .= ') ';
             $no_flag = false;
         }
    }
    // authors
    if (isset($stories_author) && $stories_author != '') {
        if (!$no_flag) {
            $query .= ' AND (';
        }
        $query .= "$storcol[informant]='".pnVarPrepForStore($stories_author)."'";

        $result =& $dbconn->Execute("SELECT {$pntable['users_column']['uid']} as pn_uid FROM $pntable[users] WHERE {$pntable['users_column']['uname']} LIKE '%".pnVarPrepForStore($stories_author)."%' OR {$pntable['users_column']['name']} LIKE '%".pnVarPrepForStore($stories_author)."%'");
        while(!$result->EOF) {
            $row = $result->GetRowAssoc(false);
            $query .= " OR $storcol[aid]=$row[pn_uid]";
            $result->MoveNext();
        }
          if (!$no_flag) {
              $query .= ') ';
              $no_flag = false;
          }
    } else {
        $stories_author = '';
    }

    if (pnConfigGetVar('multilingual') == 1) {
        if (!empty($query)) $query .= ' AND';
        $query .= " ($storcol[alanguage]='" . pnVarPrepForStore(pnUserGetLang()) . "' OR $storcol[alanguage]='')";
    }
    if (empty($query)) $query = '1';
    $query .= " ORDER BY $storcol[time] DESC";
    $query = $query1.$query;

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
            if (pnSecAuthAction(0, 'Stories::Story', "$row[aid]:$row[cat_title]:$row[sid]", ACCESS_READ) && pnSecAuthAction(0, 'Topics::Topic', "$row[topicname]::$row[topicid]", ACCESS_READ)) {
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
        $output->Text(_STORIES_TOPICS . ': ' . $total . ' ' . _SEARCHRESULTS);
        $output->SetInputMode(_PNH_VERBATIMINPUT);
        // Rebuild the search string from previous information
        $url = 'index.php?name=Search&amp;action=search&amp;active_stories=1&amp;stories_author='.pnVarPrepForDisplay($stories_author);
        if (isset($stories_cat) && $stories_cat) {
            foreach($stories_cat as $v) {
                $url .= "&amp;stories_cat%5B%5D=$v";
            }
        }
        if (isset($stories_topics) && $stories_topics){
            foreach($stories_topics as $v) {
                $url .= ("&amp;stories_topics%5B%5D=$v");
            }
        }
        $url .= '&amp;bool='.pnVarPrepForDisplay($bool);
        if (isset($q)) {
            $url .= '&amp;q='.pnVarPrepForDisplay($q);
        }
        $output->Text('<dl>');
        while (!$result->EOF) {
            $row = $result->GetRowAssoc(false);
            if (pnSecAuthAction(0, 'Stories::Story', "$row[aid]:$row[cat_title]:$row[sid]", ACCESS_READ) && pnSecAuthAction(0, 'Topics::Topic', "$row[topicname]::$row[topicid]", ACCESS_READ)) {
                $row['fdate'] = ml_ftime(_DATELONG,$result->UnixTimeStamp($row['fdate']));
                $output->Text('<dt><a href="index.php?name=News&amp;file=article&amp;sid='.pnVarPrepForDisplay($row['sid']).'">'. pnVarPrepHTMLDisplay($row['story_title']) .'</a></dt>');
                $output->Text('<dd>');
                $output->Text(pnVarPrepForDisplay($row['fdate']).' (');
                if (!empty($row['topicid'])) {
                    $output->Text($row['topictext']);
                }
                if (!empty($row['catid'])) {
                    $output->Text(' - '.pnVarPrepHTMLDisplay($row['cat_title']));
                }
                $output->Text(')</dd>');
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
        $output->Text(_SEARCH_NO_STORIES_TOPICS);
        $output->SetInputMode(_PNH_PARSEINPUT);
    }
    $output->Linebreak(3);

    return $output->GetOutput();
}
?>