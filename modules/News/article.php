<?php
// File: $Id: article.php 17740 2006-01-27 19:07:05Z markwest $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// Based on PHP-NUKE Web Portal System
// Copyright (C) 2001 by Francisco Burzi (fbc@mandrakesoft.com)
// http://www.phpnuke.org/
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
// Original Author of this file: Francisco Burzi
// Purpose of this file:
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_MODULE')) {
     die ("You can't access this file directly...");
}

list ($save, $op, $mode, $order, $thold, $sid, $tid, $pageid) =
     pnVarCleanFromInput('save', 'op', 'mode', 'order', 'thold', 'sid', 'tid', 'pageid');

if ((empty($sid) && empty($tid)) ||
    (!is_numeric($sid) && !is_numeric($tid))) {
	include 'header.php';
	echo _MODARGSERROR;
	include 'footer.php';
	exit;
}

if (empty($pageid) && !is_numeric($pageid)) {
	$pageid = 1;
}

$ModName = basename(dirname( __FILE__ ));

modules_get_language();

$dbconn =& pnDBGetConn(true);
$pntable =& pnDBGetTables();

include_once("modules/$ModName/funcs.php");
$page = "modules/News/article.php";
// eugenio themeover 20020413
// pnThemeLoad();


if (isset($save) &&  (pnUserLoggedIn())) {
    $uid = pnUserGetVar('uid');
    $column = &$pntable['users_column'];
    $sql = "UPDATE $pntable[users] SET ";
    $sql .= "$column[umode]='" . pnVarPrepForStore($mode) . "', ";
    $sql .= "$column[uorder]='" . (int)pnVarPrepForStore($order) . "', ";
    $sql .= "$column[thold]='" . (int)pnVarPrepForStore($thold) . "'";
    $sql .= " WHERE $column[uid]='" . (int)pnVarPrepForStore($uid) . "'";
    $dbconn->Execute($sql);
}

if ($op == 'Reply') {
    pnRedirect('index.php&name=Comments&req=Reply&pid=0&sid='.$sid.'&mode='.$mode.'&order='.$order.'&thold='.$thold);
}

// Get the article we're looking at
$results = getArticles("{$pntable['stories_column']['sid']}='" . (int)pnVarPrepForStore($sid) . "'");
if (empty($results)) {
   include 'header.php';
   echo _NOTAUTHSTORY;
   include 'footer.php';
   exit;
}

$info = genArticleInfo($results[0]);

if (!pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_OVERVIEW) ||
    !pnSecAuthAction(0, 'Topics::Topic', "$info[topicname]::$info[tid]", ACCESS_READ)) {
   include 'header.php';
   echo _NOTAUTHSTORY;
   include 'footer.php';
   exit;
}

// store bodytext in an additional field
$info['fullbodytext'] = $info['bodytext'];

if (pnUserGetTheme() != 'Printer') {
    // Explode the article into an array of seperate pages
    $allpages = explode( "<!--pagebreak-->", $info['bodytext'] );

    // Set the item bodytext to be the required page
    // nb arrays start from zero pages from one
    $info['bodytext'] = $allpages[$pageid-1];

    // now remove the hometext if not on the first page
    if ($pageid != 1) {
	    $info['hometext'] = '';
    }
}

$links = genArticleLinks($info);
$preformat = genArticlePreformat($info, $links);

$column = &$pntable['stories_column'];
$dbconn->Execute("UPDATE $pntable[stories] SET $column[counter]=$column[counter]+1 WHERE $column[sid]='" . (int)pnVarPrepForStore($sid) . "'");

// set theme overrides prior to header
$themeOverrideCategory = $info['catthemeoverride'];
$themeOverrideStory = $info['themeoverride'];

$artpage = 1;
include ('header.php');
$artpage = 0;

// Backwards compatibility
//formatTimestamp(GetUserTime($info['time']));
$notes = $info['notes'];

if ($GLOBALS['postnuke_theme']) {
    themearticle($info['aid'], $info['informant'], $info['time'], $info['catandtitle'], $preformat['maintext'], $info['topic'], $info['topicname'], $info['topicimage'], $info['topictext'], $info, $links, $preformat);
} else {
    themearticle($info['aid'], $info['informant'], $info['time'], $info['catandtitle'], $preformat['maintext'], $info['topic'], $info['topicname'], $info['topicimage'], $info['topictext']);
}

// implement a pager if required
echo '<div style="text-align:center">';
$output =& new pnHTML();
$output->Pager($pageid, count($allpages), 'index.php?name=News&amp;sid='.$sid.'&amp;file=article&amp;pageid=%%',1);
echo $output->GetOutput();
echo '</div>';

// added display hook - bug #174 - ferenc veres

echo pnModCallHooks('item', 'display', $sid, "index.php?name=News&file=article&sid=$sid");
if ($info['withcomm'] == 0 && $GLOBALS['mode'] != "nocomments" &&  pnModAvailable('Comments')
    && pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_READ)) {
    $commentsmodinfo = pnModGetInfo(pnModGetIDFromName('Comments'));
	include('modules/'.pnVarPrepForOS($commentsmodinfo['directory']).'/index.php');
}

include ('footer.php');

?>