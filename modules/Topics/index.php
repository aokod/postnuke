<?php
// $Id: index.php 17332 2005-12-14 16:01:34Z markwest $
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
// Filename: modules/Topics/index.php
// Original Author of file: Francisco Burzi
// Purpose of file: display topics on your site
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_MODULE')) {
	die ("You can't access this file directly...");
}

$ModName = basename(dirname( __FILE__ ));

modules_get_language();

$dbconn =& pnDBGetConn(true);
$pntable =& pnDBGetTables();

$topicsinrow = pnConfigGetVar('topicsinrow');
$tipath = pnConfigGetVar('tipath');

$count = 0;
$column = &$pntable['topics_column'];
$result =& $dbconn->Execute("SELECT $column[topicid], $column[topicname], $column[topicimage], $column[topictext] FROM $pntable[topics] ORDER BY $column[topicname]");
if ($result->EOF) {
    include 'header.php';
    if (!pnSecAuthAction(0, 'Topics::', '::', ACCESS_OVERVIEW)) {
        echo _TOPICSNOAUTH;
        include 'footer.php';
        return;
    }
    echo _NOACTIVETOPICS;
    include 'footer.php';
} else {
    include 'header.php';
    if (!pnSecAuthAction(0, 'Topics::', '::', ACCESS_OVERVIEW)) {
        echo _TOPICSNOAUTH;
        include 'footer.php';
        return;
    }
    OpenTable();
    echo '<h1>'._ACTIVETOPICS.'</h1>'."\n";
	CloseTable();
    OpenTable();
	echo  _CLICK2LIST."\n";
    echo '<table border="0" width="100%" cellpadding="2" summary=""><tr>' ."\n";
    while(list($topicid, $topicname, $topicimage, $topictext) = $result->fields) {

        $result->MoveNext();
        // someone forgot to add permissions check for Topics::Topic Topicname::TopicId
        // -- Rabbitt (aka Carl P. Corliss)
        if (pnSecAuthAction(0, 'Topics::Topic', "$topicname::$topicid", ACCESS_READ)){
            if ($count == $topicsinrow) {    // changed hardcoded number of topics icons - rwwood
                echo '<tr>'."\n";
                $count = 0;
            }
	
            echo '<td align="center" valign="top">'."\n";
			if (is_file($tipath.$topicimage)) {
				echo '<a href="index.php?name=News&amp;catid=&amp;topic='. pnVarPrepForDIsplay($topicid).'">';
				echo '<img src="'. pnVarPrepForDIsplay($tipath.$topicimage).'" alt="'.pnVarPrepForDIsplay($topictext).'" title="'.pnVarPrepForDIsplay($topictext).'" /></a><br />'. "\n";
			}
            echo '[&nbsp;<a href="index.php?name=News&amp;catid=&amp;topic='. pnVarPrepForDIsplay($topicid).'">'.pnVarPrepForDIsplay($topictext).'</a>&nbsp;]'."\n";
            echo '</td>'."\n";

            /* Thanks to John Hoffmann from softlinux.org for the next 5 lines ;) */
            $count++;

            if ($count == $topicsinrow) {    // changed hardcoded number of topics icons - rwwood
                echo '</tr>'."\n";
            }
        }
    }
    if ($count == $topicsinrow) {    // changed hardcoded number of topics icons - rwwood
        echo '</table>'."\n";
    } else {
        echo '</tr></table>'."\n";
    }

    CloseTable();
    include 'footer.php';
}
$result->Close();

?>