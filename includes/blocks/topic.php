<?php
// File: $Id: topic.php 15630 2005-02-04 06:35:42Z jorg $
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
// Original Author of file: Francisco Burzi
// Purpose of file:
// ----------------------------------------------------------------------

if (strpos($_SERVER['PHP_SELF'], 'topic.php')) {
	die ("You can't access this file directly...");
}

$blocks_modules['topic'] = array(
		'func_display' => 'blocks_topic_block',
        'text_type' => 'Topics',
        'text_type_long' => 'Topics Menu',
        'allow_multiple' => false,
        'form_content' => false,
        'form_refresh' => false,
        'show_preview' => true
	);

pnSecAddSchema('Topicblock::', 'Block title::');

function blocks_topic_block($row)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $currentlang = pnUserGetLang();

    if (!pnSecAuthAction(0, 'Topicblock::', "$row[title]::", ACCESS_READ)) {
        return;
    }

	$language = pnConfigGetVar('language');
	$topic = '';
	$catid = '';
  
	if (pnConfigGetVar('multilingual') == 1) {
		$column = &$pntable['stories_column'];
		$querylang = "AND ($column[alanguage]='$currentlang' OR $column[alanguage]='')"; /* the OR is needed to display stories who are posted to ALL languages */
	} else {
		$querylang = '';
	}
	$column = &$pntable['topics_column'];
	$result =& $dbconn->Execute("SELECT $column[topicid] AS topicid, $column[topictext] as topictext, $column[topicname] as topicname FROM $pntable[topics] ORDER BY topictext");
	if ($result->EOF) {
		return;
	} else {
	    $boxstuff = '<ul>';
		if ($topic == "") {
			$boxstuff .= "<li><a href=\"index.php?name=Topics\">"._ALL_TOPICS."</a></li>";
    	} else {
			$boxstuff .= "<li><a href=\"index.php?name=News&amp;catid=$catid\">"._ALL_TOPICS."</a></li>";
		}

		while(!$result->EOF) {
    	    $srow = $result->GetRowAssoc(false);
        	$result->MoveNext();
        	if (pnSecAuthAction(0, 'Topics::Topic', "$srow[topicname]::$srow[topicid]", ACCESS_READ)) { 
				$column = &$pntable['stories_column'];
				$result2 =& $dbconn->Execute("SELECT $column[time] AS unixtime FROM $pntable[stories] WHERE $column[topic]=$srow[topicid] $querylang ORDER BY $column[time] DESC");

				if (!$result2->EOF) {
					$story = $result2->GetRowAssoc(false);
					$story['unixtime']=$result2->UnixTimeStamp($story['unixtime']);
					$sdate = ml_ftime(_DATEBRIEF, $story['unixtime']);
					if ($topic == $srow['topicid']) {
						$boxstuff .= "<li><h2>$srow[topictext]</h2><span class=\"pn-sub\">($sdate)</span></li>";
					} else {
						$boxstuff .= "<li><a href=\"index.php?name=News&amp;catid=$catid&amp;topic=$srow[topicid]\">$srow[topictext]</a> <span class=\"pn-sub\">($sdate)</span></li>";
					}
				}
			}
		}
	}
	$boxstuff .= '</ul>';
	if (empty($row['title'])) {
		$row['title'] = _TOPICS;
	}
	$row['content'] = $boxstuff;
	return themesideblock($row);
}

?>