<?php
// File: $Id: related.php 15630 2005-02-04 06:35:42Z jorg $
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
// Original Author of file: Patrick Kellum
// Purpose of file: Display releated stories.  Only displayed when reading articles.
// ----------------------------------------------------------------------

if (strpos($_SERVER['PHP_SELF'], 'related.php')) {
	die ("You can't access this file directly...");
}

$blocks_modules['related'] = array(
    'func_display' => 'blocks_related_block',
    'text_type' => 'Related',
    'text_type_long' => 'Story Related Links',
    'allow_multiple' => false,
    'form_content' => false,
    'form_refresh' => false,
    'show_preview' => false
);

// Security
pnSecAddSchema('Relatedblock::', 'Block title::');

function blocks_related_block($row)
{
	if (pnModGetName() != 'News') {
		return;
	}

    if (!pnSecAuthAction(0, 'Relatedblock::', "$row[title]::", ACCESS_READ)) {
        return;
    }

	// get the story id
	$sid = pnVarCleanFromInput('sid');

	// check the sid
	if (!is_numeric($sid)) {
		return;
	}

	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();

	// get the story
	$story = getArticles("{$pntable['stories_column']['sid']}='" . (int)pnVarPrepForStore($sid) . "'", "", "");
	$info = genArticleInfo($story[0]);

    if($info['tid']) {
        $boxstuff = '<ul>';

        $column = &$pntable['related_column'];
        //$myquery = buildSimpleQuery ('related', array ('name', 'url'), "$column[tid]=$info[tid]");
		$myquery = "SELECT $column[name], $column[url]
					FROM $pntable[related]
					WHERE $column[tid] = $info[tid]";
        $result =& $dbconn->Execute($myquery);
        while(list($name, $url) = $result->fields) {

            $result->MoveNext();
            $boxstuff .= "<li><a href=\"$url\">$name</a></li>\n";
        }

        $boxstuff .= "<li><a href=\"index.php?name=Search&amp;";
        $boxstuff .= "action=search&amp;overview=1&amp;active_stories=1&amp;stories_topics[0]=$info[tid]\">";
        $boxstuff .= _MOREABOUT." $info[topictext]</a></li>\n";

        $boxstuff .= "<li><a href=\"index.php?name=Search&amp;";
        $boxstuff .= "action=search&amp;overview=1&amp;active_stories=1&amp;stories_author=$info[informant]\">";
        $boxstuff .= _NEWSBY." $info[informant]</a></li>\n";

        // $boxlink is not defined and can't be found.  Not sure what this code is doing. Comment it out - Skooter.
        //$boxstuff .= $boxlink;

        $boxstuff .= "</ul><hr /><div style=\"text-align:center\">"._MOSTREAD." $info[topictext]:<br />\n";

        //Last story on this topic
        //$column = &$pntable['stories_column'];
        //$results = getArticles("$column[topic]=$info[tid]", "$column[counter] DESC", 1);

        // # solve on bug #524506
        //
        //$ta_row = $results[0];
        //$ta_info = genArticleInfo($ta_row);
        //$ta_links = genArticleLinks($ta_info);
        //$ta_preformat = genArticlePreformat($ta_info, $ta_links);
		
		// reduced the SQL-statements to get the most read story
        $column = &$pntable['stories_column'];
		$myquery = "SELECT $column[sid], $column[title]
					FROM $pntable[stories]
					WHERE $column[topic] = $info[tid]";
        if (pnConfigGetVar('multilingual') == 1) {
            $myquery .= " AND ($column[alanguage]='" . pnUserGetLang() . "' OR $column[alanguage]='')";
		}
		$myquery .= " ORDER BY $column[counter] DESC";
		
        $result =& $dbconn->SelectLimit($myquery,1);
	    list($most_read_sid, $most_read_title) = $result->fields;
        $boxstuff .= "<a href=\"index.php?name=News&amp;file=article&amp;sid=$most_read_sid\">$most_read_title</a></div>";

		$row['content'] = $boxstuff;
        return themesideblock($row);
    }
}
?>