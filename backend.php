<?php
// File: $Id: backend.php 20366 2006-10-23 08:11:40Z markwest $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2002 by the PostNuke Development Team.
// http://www.postnuke.com/
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
// 06.20.02 ColdRolledSteel	Fixed bug 482633 (Display only homepage stories)
//				Fixed bug 449959 (Added image tags for RSS)
//				Fixed bug where language not displayed
//				Added webMaster, managingEditor tags
//				Added $headline count so admin can control number of stories
//				Added show_content to feed non-HTML content
//				Renamed from backend.php to rss_feed.php
//				Modules capitalized for early 0.711 naming convention
// 03.21.05 Added time field to feeds as pubDate
//			time format in database is incorrect format for rss feeds
//			RFC-822 date format should be used.

include 'includes/pnAPI.php';
pnInit();

$dbconn =& pnDBGetConn(true);
$pntable =& pnDBGetTables();

header("Content-Type: text/xml; charset=ISO-8859-1");

// some default vars
static $mostrecentdate;
$headline_limit = 25; // Maximum value for Select
$shown_results = 0;
$body = '';
$mostrecentdate = 0;

// Get $charset (atamyrat@gmail.com)
if (defined('_CHARSET') && _CHARSET != '') {
	$charset = _CHARSET;
} else {
	$charset = 'ISO-8859-1';
}
// get the short urls extensions
$urlsok = pnModGetVar('Xanthia', 'shorturlsok');
$urlextension = pnModGetVar('Xanthia', 'shorturlsextension');
$baseurl = pnGetBaseURL();

// get the language
$newlang = pnVarCleanFromInput('newlang');
$backendlang = pnConfigGetVar('backend_language');
$backendlangs = cnvlanguagelist();
if ((!isset($newlang) || empty($newlang)) && isset($backendlangs[$backendlang])) {
    $lang = $backendlangs[$backendlang];
} else {
    $lang = $newlang;
}

// get any filters
$topicid = pnVarCleanFromInput('topicid');
$catid = pnVarCleanFromInput('catid');

// Base query
$storiescolumn = $pntable['stories_column'];
$storiescatcolumn= $pntable['stories_cat_column'];
$topicscolumn = $pntable['topics_column'];
   
$query = "SELECT $storiescolumn[aid] AS \"aid\",
                 $storiescolumn[catid] AS \"cid\",
                 $storiescatcolumn[title] AS \"cattitle\",
                 $storiescolumn[sid] AS \"sid\",
                 $topicscolumn[topicid] AS \"tid\",
                 $storiescolumn[title] AS \"title\",
                 $topicscolumn[topicname] AS \"topicname\",
                 $topicscolumn[topictext] AS \"topictext\",
				 $storiescolumn[hometext] AS \"hometext\",
				 $storiescolumn[time] AS \"time\"
          FROM 	 $pntable[stories]";
$query .= " LEFT JOIN $pntable[stories_cat] ON $storiescolumn[catid] = $storiescatcolumn[catid]
			LEFT JOIN $pntable[topics] ON $storiescolumn[topic] = $topicscolumn[topicid]";
$query .= " WHERE $storiescolumn[ihome] = 0 AND ($storiescolumn[language] = '".pnVarPrepForStore($lang)."' OR $storiescolumn[language] = '') ";
if (isset($topicid) && is_numeric($topicid)) {
    $query .= " AND $storiescolumn[topic] = '".pnVarPrepForStore($topicid)."'";
}
if (isset($catid) && is_numeric($catid)) {
    $query .= " AND $storiescolumn[catid] = '".pnVarPrepForStore($catid)."'";
}
$query .= " ORDER BY $storiescolumn[time] DESC";	

// with permissions in mind we can't use $headline_limit but restricting to 99 should be ok
$result =& $dbconn->SelectLimit($query,99);
//$result =& $dbconn->Execute($query);

// Error checking
if ($dbconn->ErrorNo() != 0) {
	return false;
}

// start capture of dynamic output
while((list($aid, $cid, $cattitle, $sid, $tid, $title, $topicname, $topictext, $hometext, $time) = $result->FetchRow()) 
	&& ($shown_results < $headline_limit) ){
	if (empty($mostrecentdate)) {
		$mostrecentdate = $time;
	}
	$title = pnVarPrepForDisplay($title);

    // store some vars for later
    $topictitle = $topictext;
    $categorytitle = $cattitle;

    // form the url
    if($urlsok == 1) {
       $link =  pnVarPrepForDisplay("{$baseurl}Article$sid.$urlextension");
    } else {
       $link =  pnVarPrepForDisplay("{$baseurl}index.php?name=News&file=article&sid=$sid");
    }

	$content = pnVarPrepForDisplay(strip_tags($hometext));

	// check author id
	if(!isset($aid)) {
		$aid = '';
	}
	
	// update category title
	if ($cid == 0) {
		// Default category
		$cattitle = ""._ARTICLES."";
	}

	// display with permission check
    if (pnSecAuthAction(0, 'Stories::Story', "$aid:$cattitle:$sid", ACCESS_READ) 
	 && pnSecAuthAction(0, 'Topics::Topic', "$topicname::$tid", ACCESS_READ) ) {

		$shown_results++;

		$body .= "<item>\n";
		$body .= "<title>$title</title>\n";
		$body .= "<link>$link</link>\n";
		$body .= "<description>$content</description>\n";
		$body .= "<pubDate>".date('r', strtotime($time))."</pubDate>\n";
		$body .= "</item>\n";
	}
}
// end of dynamic output

$sitename = pnConfigGetVar('sitename');
if (isset($topicid) && is_numeric($topicid)) {
    $title = pnVarPrepForDisplay($sitename) . ' :: ' . pnVarPrepForDisplay($topictitle);
} else if (isset($catid) && is_numeric($catid)) {
    $title = pnVarPrepForDisplay($sitename) . ' :: ' . pnVarPrepForDisplay($categorytitle);
} else {
    $title = pnVarPrepForDisplay($sitename);
}
$link = pnVarPrepForDisplay(pnGetBaseURL());
$description = pnVarPrepForDisplay(pnConfigGetVar('backend_title'));
$backend_language = pnVarPrepForDisplay($backendlang);
$webmaster = pnVarPrepForDisplay(pnConfigGetVar('adminmail'));
$image_url = $link.'images/'.pnVarPrepForDisplay(pnConfigGetVar('site_logo'));
$image_title = $title;  // RSS parsers usually use this for the ALT tag on the image
$image_link = $link;  // RSS parsers usually use this as the link when users click on the image

// start the RSS output	
echo "<?xml version=\"1.0\" encoding=\"$charset\"?>\n\n";
echo "<rss version=\"2.0\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\">";
echo "<channel>\n";
echo "<title>$title</title>\n";
echo "<pubDate>".date('r', strtotime($mostrecentdate))."</pubDate>\n";
echo "<link>$link</link>\n";
echo "<description>$description</description>\n";
echo "<language>$backend_language</language>\n";
echo "<image>\n";
echo " <title>$image_title</title>\n";
echo " <url>$image_url</url>\n";
echo " <link>$image_link</link>\n";
echo "</image>\n";
echo "<webMaster>$webmaster</webMaster>\n";
echo $body;
echo "</channel>\n";
echo "</rss>\n";

?>