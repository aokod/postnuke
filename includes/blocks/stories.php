<?php
// File: $Id: stories.php 16427 2005-07-20 08:22:32Z larsneo $
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
// Purpose of file: Display titles of stories, with lots of options
// ----------------------------------------------------------------------

if (strpos($_SERVER['PHP_SELF'], 'stories.php')) {
    die ("You can't access this file directly...");
}

$blocks_modules['stories'] = array(
        'func_display' => 'blocks_stories_block',
        'func_edit' => 'blocks_stories_select',
        'func_update' => 'blocks_stories_update',
        'text_type' => 'Stories',
        'text_type_long' => 'Story Titles',
        'allow_multiple' => true,
        'form_content' => false,
        'form_refresh' => false,
        'show_preview' => true
        );

pnSecAddSchema('Storiesblock::', 'Block title::');

function blocks_stories_block($row)
{
    if (!pnSecAuthAction(0, 'Storiesblock::', "$row[title]::", ACCESS_READ)) {
        return;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $currentlang = pnUserGetLang();

    // Break out options from our content field
    $vars = pnBlockVarsFromContent($row['content']);

    // Defaults
    if (!isset($vars['storiestype'])) {
        $vars['storiestype'] = 2;
    }
    if (!isset($vars['topic'])) {
        $vars['topic'] = -1;
    }
    if (!isset($vars['category'])) {
        $vars['category'] = -1;
    }
    if (!isset($vars['limit'])) {
        $vars['limit'] = 10;
    }

    $row['content'] = '';
    $query_started = false;


    // Base query
    $storiescolumn = $pntable['stories_column'];
    $storiescatcolumn= $pntable['stories_cat_column'];
    $topicscolumn = $pntable['topics_column'];
    
    $query = "SELECT $storiescolumn[aid] AS \"aid\",
                    $storiescolumn[catid] AS \"cid\",
                    $storiescatcolumn[title] AS \"cattitle\",
                    $storiescolumn[sid] AS \"sid\",
                    $topicscolumn[topicid] AS \"tid\",
                    $storiescolumn[time] AS \"time\",
                    $storiescolumn[title] AS \"title\",
                    $topicscolumn[topicname] AS \"topicname\"
			  FROM  $pntable[stories]";

    // Assume mysql start
    $query .= " LEFT JOIN $pntable[stories_cat] ON $storiescolumn[catid] = $storiescatcolumn[catid]
                LEFT JOIN $pntable[topics] ON $storiescolumn[topic] = $topicscolumn[topicid]";
    // Assume mysql end
	
    $wherearray=array();

    // check language
    if (pnConfigGetVar('multilingual') == 1) {
		    $wherearray[] = " ($storiescolumn[alanguage]='" . pnUserGetLang() . "' OR $storiescolumn[alanguage]='')";
    }

    // Qualifier for front-page/not front-page news
    // storiestype = 3 - front-page news
    // storiestype = 1 - not front-page news
    // storiestype = 2 - all news
    if ($vars['storiestype']=='1') {
        $wherearray[] = " $storiescolumn[ihome]=1";
    }
    if ($vars['storiestype']=='3') {
        $wherearray[] = " $storiescolumn[ihome]=0";
    }
    
    // Qualifier for particular topic
    // topic = -1 - all topics?
    if ($vars['topic'] != -1) {
        $wherearray[] = " $storiescolumn[topic]=" . (int)pnVarPrepForStore($vars['topic']);
    }

    // Qualifier for particular category
    // category = -1 - all categories
    if ($vars['category'] != -1) {
        $wherearray[] = " $storiescolumn[cid]=" . (int)pnVarPrepForStore($vars['category']);
    }
	
		// build up WHERE query
    if ($wherearray) {
        $query .= " WHERE " . implode(" AND ", $wherearray);
    }

    // Qualifier for how many stories
    $query .= " ORDER BY $storiescolumn[time] DESC";	

    // use a limit query for performance reasons, permissions take effect later on...
    $result = $dbconn->SelectLimit($query, 999);
    //$result =& $dbconn->Execute($query);
	
    // Error checking -- jn
    if ($dbconn->ErrorNo() != 0) {
        return false;
    }
	
    $shown_results=0;
    $postmax=(int)$vars['limit'];
    $start = '<ul>';
    while((list($aid, $cid, $cattitle, $sid, $tid, $time, $title, $topicname) = $result->FetchRow()) && ($shown_results < $postmax) ){
        $time=$result->UnixTimeStamp($time);
        if(!isset($aid)) {
            $aid = '';
        }
		    if ($cid == 0) {
			    // Default category
			    $cattitle = ""._ARTICLES."";
		    }
        if (pnSecAuthAction(0, 'Stories::Story', "$aid:$cattitle:$sid", ACCESS_READ) && pnSecAuthAction(0, 'Topics::Topic', "$topicname::$tid", ACCESS_READ) ) {
            $row['content'] .=
                "<li><span class=\"pn-sub\"><a href=\"index.php?name=News&amp;file=article&amp;sid=" . pnVarPrepForDisplay($sid) . "\">" . pnVarPrepForDisplay(pnVarCensor($title)) . "</a>
                (".ml_ftime(_DATEBRIEF,$time).")</span></li>\n";
            $shown_results++;
        }
        // removed uncessary MoveNext; FetchRow() from above moves the record set pointer - markwest
        // Credit rembert http://forums.postnuke.com/index.php?name=PNphpBB2&file=viewtopic&t=14182
        // $result->MoveNext();
    }
    $end = '</ul>';

    if (!empty($row['content'])) {
        $row['content'] = $start . $row['content'] . $end;
        return themesideblock($row);
    }
}

function blocks_stories_select($row)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Break out options from our content field
    $vars = pnBlockVarsFromContent($row['content']);

    // Defaults
    if (empty($vars['storiestype'])) {
        $vars['storiestype'] = 2;
    }
    if (empty($vars['topic'])) {
        $vars['topic'] = -1;
    }
    if (empty($vars['category'])) {
        $vars['category'] = -1;
    }
    if (empty($vars['limit'])) {
        $vars['limit'] = 10;
    }

    $row['content'] = "";

    // Which stories to list
    $output = '<tr><td>' . _STORIESDISPLAYALL .
              ':</td><td><input type="radio" name="storiestype" value="2"';
    if ($vars['storiestype'] == 2) {
        $output .= ' checked';
    }
    $output .= '></td></tr><tr><td>' . _STORIESDISPLAYFRONTPAGE .
               ':</td><td><input type="radio" name="storiestype" value="3"';
    if ($vars['storiestype'] == 3) {
        $output .= ' checked';
    }
    $output .= '></td></tr><tr><td>' . _STORIESDISPLAYNONFRONTPAGE .
               ':</td><td><input type="radio" name="storiestype" value="1"';
    if ($vars['storiestype'] == 1) {
        $output .= ' checked';
    }
    $output .= '></td></tr>';

    // Which topic
    $output .= '<tr><td>' . _STORIESTOPIC . ':</td><td><select name="topic" size="1">';
    $output .= '<option value="-1\"';
    if ($vars['topic'] == -1) {
        $output .= " selected";
    }
    $output .= '>'._ALL.'</option>';

    $topicscolumn = &$pntable['topics_column'];
    $query = "SELECT $topicscolumn[tid],
                     $topicscolumn[topicname]
              FROM $pntable[topics]
              ORDER BY $topicscolumn[topicname]";
    $result =& $dbconn->Execute($query);
    while(!$result->EOF) {
        list($tid, $tname) = $result->fields;
        $result->MoveNext();
        $output .= "<option value=\"$tid\"";
        if ($vars['topic'] == $tid) {
            $output .= ' selected';
        }
        $output .= '>' . pnVarPrepForDisplay($tname) . '</option>';
    }
    $output .= '</select></td></tr>';

    // Which category
    $output .= '<tr><td>' . _STORIESCATEGORY . ':</td><td><select name="category" size="1">';
    $output .= "<option value=\"-1\"";
    if ($vars['category'] == -1) {
        $output .= ' selected';
    }
    $output .= '>'._ALL.'</option>';

    $storiescatcolumn = &$pntable['stories_cat_column'];
    $query = "SELECT $storiescatcolumn[catid],
                     $storiescatcolumn[title]
              FROM $pntable[stories_cat]
              ORDER BY $storiescatcolumn[title]";
    $result =& $dbconn->Execute($query);
    while(!$result->EOF) {
        list($catid, $cattitle) = $result->fields;
        $result->MoveNext();
        $output .= "<option value=\"$catid\"";
        if ($vars['category'] == $catid) {
            $output .= ' selected';
        }
        $output .= ">" . pnVarPrepForDisplay($cattitle) . "</option>";
    }
    $output .= "</select></td></tr>";

    // Number of stories
    $output .= '<tr><td>' . _STORIESMAXNUM .
               ':</td><td><input type="text" name="limit" size="2" value="' .
               pnVarPrepForDisplay($vars['limit']) . '"></td></tr>';

    return $output;
}

function blocks_stories_update($row)
{
    list($vars['storiestype'],
         $vars['topic'],
         $vars['category'],
         $vars['limit'])
      = pnVarCleanFromInput('storiestype',
                            'topic',
                            'category',
                            'limit');

    $row['content'] = pnBlockVarsToContent($vars);

    return($row);
}

?>