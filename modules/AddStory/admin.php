<?php
// File: $Id: admin.php 19804 2006-08-23 13:34:19Z markwest $
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
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------

// Modifications by Andy Varganov - the file was almost unmanageable with
// more than 2500 lines of code, so I have splitted it in three parts:
// Part 1 - this file where only the functions included in the switch reside
// Part 2 - addstory_functions.php where all other functions live
// Part 3 - category functions that might be reused for other parts of pn
// I'll try to add description of those in addstory_functions.php later

// Security related changes and removed globals. - skooter

if (!defined('LOADED_AS_MODULE')) { die ('Access Denied'); }

$ModName = basename(dirname( __FILE__ ));
modules_get_language();
modules_get_manual();

include_once ("modules/$ModName/addstory_functions.php");
include_once ("modules/$ModName/addstory_categories.php");

function displayStory()
{
    // Confirm authorisation code
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    list($cat,
    	 $catid,
    	 $qid,
    	 $comm,
         $automated,
         $module) = pnVarCleanFromInput('cat',
         								   'catid',
         								   'qid',
         								   'comm',
                                           'automated',
                                           'module');

    if (!isset($automated)) {
        $automated = 0;
    }


    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $anonymous = pnConfigGetVar('anonymous');
    include ('header.php');
    GraphicAdmin();
    OpenTable();
    echo '<h1>'._SUBMISSIONSADMIN.'</h1>';
    CloseTable();

	$msg = pnGetStatusMsg();
	if ($msg != '') {
		OpenTable();
		echo $msg;
		CloseTable();
	}

    $column = &$pntable['queue_column'];
    $result =& $dbconn->Execute("SELECT $column[qid], $column[uid], $column[uname],
                                $column[subject], $column[story], $column[topic],
                                $column[alanguage], $column[bodytext]
                              FROM $pntable[queue] WHERE $column[qid]='".(int)pnVarPrepForStore($qid)."'");

    list($qid, $uid, $uname, $subject, $story, $topic, $alanguage, $bodytext) = $result->fields;
    $result->Close();
    OpenTable();
    echo '<form action="admin.php" method="post"><div>'
        .'<strong>'._NAME.'</strong><br />'
        .'<input type="text" name="author" size="25" value="'.pnVarPrepForDisplay($uname).'" />';
    if ($uname != $anonymous) {
        $column = &$pntable['users_column'];
        $res =& $dbconn->Execute("SELECT $column[email]
                               FROM $pntable[users]
                               WHERE $column[uname]='".pnVarPrepForStore($uname)."'");

        list($email) = $res->fields;
        echo '&nbsp;&nbsp;[ <a href="mailto:'.$email.'">'._EMAILUSER.'</a>';
        if (pnModAvailable('Messages')) {
            echo ' | <a href="' . pnVarPrepForDisplay(pnModURL('Messages', 'user', 'compose', array('uname' => $uname))).'">'._SENDPRIVMSG.'</a>';
        }
        echo ' ]';
    }
    if($topic=='') {
        $topic = 1;
    }

    // Guess the format type.
    $format_type = defaultFormatType($story, $bodytext);

    echo '<br />';
    // Pass in format_type=0 at this stage to assume format is text.
    storyPreview($subject, $story, $bodytext, $notes='', $topic, $format_type);
    storyEdit($subject, $story, $bodytext, $notes='', $topic, $ihome='', $catid, $alanguage, $comm, $aid="", $informant="", $format_type);
    buildProgramStoryMenu($automated);
    buildCalendarMenu(false, $year, $day, $month, $hour, $min);
    echo '<input type="hidden" name="module" value="AddStory" />'
        .'<input type="hidden" name="qid" size="50" value="' . pnVarPrepForDisplay($qid) . '" />'
        .'<input type="hidden" name="uid" size="50" value="' . pnVarPrepForDisplay($uid) . '" />'
        .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
        .'<br /><select name="op">'
        .'<option value="DeleteStory">'._DELETESTORY.'</option>'
        .'<option value="PreviewAgain" selected="selected">'._PREVIEWSTORY.'</option>'
        .'<option value="PostStory">'._POSTSTORY.'</option>'
        .'</select>&nbsp;&nbsp;'
        .'<input type="submit" value="'._OK.'" />'
        .'</div></form>';
    CloseTable();
    include ('footer.php');
}

function previewStory()
{
    // Confirm authorisation code
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    list($module,
    	   $automated,
         $year,
         $day,
         $month,
         $hour,
         $min,
         $qid,
         $uid,
         $author,
         $subject,
         $hometext,
         $bodytext,
         $topic,
         $notes,
         $catid,
         $ihome,
         $alanguage,
         $comm,
         $format_type,
         $format_type_home,
         $format_type_body) = pnVarCleanFromInput('module',
         							                'automated',
                                      'year',
                                      'day',
                                      'month',
                                      'hour',
                                      'min',
                                      'qid',
                                      'uid',
                                      'author',
                                      'subject',
                                      'hometext',
                                      'bodytext',
                                      'topic',
                                      'notes',
                                      'catid',
                                      'ihome',
                                      'alanguage',
                                      'comm',
                                      'format_type',
                                      'format_type_home',
                                      'format_type_body');

    if (!isset($format_type)) {
        $format_type = 0;
    }

    if (isset($format_type_home) && isset($format_type_body)) {
        $format_type = ($format_type_body%4)*4 + $format_type_home%4;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $anonymous = pnConfigGetVar('anonymous');

    if (!isset($automated)) {
        $automated = 0;
    }

    include ('header.php');
    GraphicAdmin();
    OpenTable();
    echo '<h1>'._ARTICLEADMIN.'</h1>';
    CloseTable();

	$msg = pnGetStatusMsg();
	if ($msg != '') {
		OpenTable();
		echo $msg;
		CloseTable();
	}

    OpenTable();
    echo '<form action="admin.php" method="post"><div>'
        .'<strong>'._NAME.'</strong><br />'
        .'<input type="text" name="author" size="25" value="'.pnVarPrepForDisplay($author).'" />';
    if ($author != $anonymous) {
        $column = &$pntable['users_column'];
        $res =& $dbconn->Execute("SELECT $column[email]
                               FROM $pntable[users]
                               WHERE $column[uname]='".pnVarPrepForStore($author)."'");

        list($email) = $res->fields;
        echo '&nbsp;&nbsp;[ <a href="mailto:'.$email.'">'._EMAILUSER.'</a>';
        if (pnModAvailable('Messages')) {
            echo ' | <a href="' . pnVarPrepForDisplay(pnModURL('Messages', 'user', 'compose', array('uname' => $author))).'">'._SENDPRIVMSG.'</a>';
        }
        echo ' ]';
    }
    echo '<br />';
    storyPreview($subject, $hometext, $bodytext, $notes, $topic, $format_type);
    storyEdit($subject, $hometext, $bodytext, $notes, $topic, $ihome, $catid, $alanguage, $comm, $uid, $author, $format_type);
    echo '<input type="hidden" name="qid" size="50" value="'.pnVarPrepForDisplay($qid).'" />'
        .'<input type="hidden" name="uid" size="50" value="'.pnVarPrepForDisplay($uid).'" />';
    buildProgramStoryMenu($automated);
    buildCalendarMenu(true, $year, $day, $month, $hour, $min);
    echo '<input type="hidden" name="module" value="AddStory" />'
        .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
        .'<br /><select name="op" />'
        .'<option value="DeleteStory">'._DELETESTORY.'</option>'
        .'<option value="PreviewAgain" selected"selected">'._PREVIEWSTORY.'</option>'
        .'<option value="PostStory">'._POSTSTORY.'</option>'
        .'</select>&nbsp;'
        .'<input type="submit" value="'._OK.'" />'
        .'</div></form>';
    CloseTable();
    include ('footer.php');
}

function postStory()
{
    list($module,
         $automated,
         $year,
         $day,
         $month,
         $hour,
         $min,
         $qid,
         $uid,
         $author,
         $subject,
         $hometext,
         $bodytext,
         $topic,
         $notes,
         $catid,
         $ihome,
         $alanguage,
         $comm,
         $format_type_home,
         $format_type_body) = pnVarCleanFromInput('module',
         							                'automated',
                                      'year',
                                      'day',
                                      'month',
                                      'hour',
                                      'min',
                                      'qid',
                                      'uid',
                                      'author',
                                      'subject',
                                      'hometext',
                                      'bodytext',
                                      'topic',
                                      'notes',
                                      'catid',
                                      'ihome',
                                      'alanguage',
                                      'comm',
                                      'format_type_home',
                                      'format_type_body');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    // check for valid topic
    if (empty($topic)) {
        echo "Error: No valid topic given. Repost.";
        exit;
    }

    if (empty($subject)) {
        echo _ADDSTORYNOSUBJECT;
        exit;
    }

    // Confirm authorisation code
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }


    if (!isset($automated)) {
        $automated = 0;
    }

    if (!isset($format_type_home)) {
        $format_type_home = 0;
    }

    if (!isset($format_type_body)) {
        $format_type_body = 0;
    }

    // Lowest two bits is the home format type, next two bits is the body format type.
    $format_type = (($format_type_body%4)*4) + ($format_type_home%4);

    // Get category from catID - needed for authorisation
    $column = &$pntable['stories_cat_column'];
    $result =& $dbconn->Execute("SELECT $column[title]
                              FROM $pntable[stories_cat]
                              WHERE $column[catid] = '".(int)pnVarPrepForStore($catid)."'");
	if ($dbconn->ErrorNo() !== 0) {
        $msg = $dbconn->ErrorMsg();
        include 'header.php';
        echo $msg;
        include 'footer.php';
        return;
	}
    if ($result->PO_RecordCount($pntable['stories_cat'], "$column[catid] = $catid")== 1) {
        list($cattitle) = $result->fields;
    } else {
        $cattitle = '';
    }

    if (!pnSecAuthAction(0, 'Stories::Story', ":$cattitle:", ACCESS_ADD)) {
        include 'header.php';
        echo _STORIESADDNOAUTH;
        include 'footer.php';
        return;
    }

    // Only add br tags if the format type is text.

    if ($format_type_home == 0) {
        $hometext = nl2br($hometext);
    }

    if ($format_type_body == 0) {
        $bodytext = nl2br($bodytext);
    }

    $notes = nl2br($notes);

    if ($automated == 1) {
        if ($day < 10) {
            $day = "0$day";
        }
        if ($month < 10) {
            $month = "0$month";
        }
        $sec = '00';
        $date = "$year-$month-$day $hour:$min:$sec";
        if ($hometext == $bodytext) $bodytext = "";
        $column = &$pntable['autonews_column'];
        $nextid = $dbconn->GenId($pntable['autonews']);
        $result =& $dbconn->Execute("INSERT INTO $pntable[autonews]
                                    ($column[anid], $column[catid], $column[aid],
                                    $column[title], $column[time], $column[hometext],
                                    $column[bodytext], $column[topic],
                                    $column[informant], $column[notes], $column[ihome],
                                    $column[alanguage], $column[withcomm])
                                  VALUES ('" . pnVarPrepForStore($nextid) . "',
                                          '" . pnVarPrepForStore($catid) . "',
                                          '" . pnVarPrepForStore($uid) . "',
                                          '" . pnVarPrepForStore($subject) . "',
                                          '" . pnVarPrepForStore($date) . "',
                                          '" . pnVarPrepForStore($hometext) . "',
                                          '" . pnVarPrepForStore($bodytext) . "',
                                          '" . pnVarPrepForStore($topic) . "',
                                          '" . pnVarPrepForStore($author) . "',
                                          '" . pnVarPrepForStore($notes) . "',
                                          '" . pnVarPrepForStore($ihome) . "',
                                          '" . pnVarPrepForStore($alanguage) . "',
                                          '" . pnVarPrepForStore($comm) . "')");

		if ($dbconn->ErrorNo() !== 0) {
			$msg = $dbconn->ErrorMsg();
			include 'header.php';
			echo $msg;
			include 'footer.php';
			return;
		}
        if (!empty($uid)) {
            $column = &$pntable['users_column'];
            $result =& $dbconn->Execute("UPDATE $pntable[users]
                                      SET $column[counter]=$column[counter]+1
                                      WHERE $column[uid]='".(int)pnVarPrepForStore($uid)."'");
			if ($dbconn->ErrorNo() !== 0) {
				$msg = $dbconn->ErrorMsg();
				include 'header.php';
				echo $msg;
				include 'footer.php';
				return;
			}
        }
        $queuetable = $pntable['queue'];
        $queuecolumn = &$pntable['queue_column'];
        $result =& $dbconn->Execute("DELETE FROM $queuetable
                                  WHERE $queuecolumn[qid]='".(int)pnVarPrepForStore($qid)."'");
		if ($dbconn->ErrorNo() !== 0) {
			$msg = $dbconn->ErrorMsg();
			include 'header.php';
			echo $msg;
			include 'footer.php';
			return;
		}
		pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_STORYPOSTED));
        pnRedirect('admin.php?module=AddStory&op=submissions');
        return true;
    } else {

        if ($hometext == $bodytext) $bodytext = '';
        $column = &$pntable['stories_column'];
        $nextid = $dbconn->GenId($pntable['stories']);
        $result =& $dbconn->Execute("INSERT INTO $pntable[stories] ($column[sid],
                               $column[catid], $column[aid], $column[title],
                               $column[time], $column[hometext], $column[bodytext],
                               $column[comments], $column[counter], $column[topic],
                               $column[informant], $column[notes], $column[ihome],
                               $column[themeoverride], $column[alanguage],
                               $column[withcomm], $column[format_type])
                             VALUES ('" . pnVarPrepForStore($nextid) . "',
                                     '" . pnVarPrepForStore($catid) . "',
                                     '" . pnVarPrepForStore($uid) . "',
                                     '" . pnVarPrepForStore($subject) . "',
                                     now(),
                                     '" . pnVarPrepForStore($hometext) . "',
                                     '" . pnVarPrepForStore($bodytext) . "',
                                     '" . pnVarPrepForStore(0) . "',
                                     '" . pnVarPrepForStore(0) . "',
                                     '" . pnVarPrepForStore($topic) . "',
                                     '" . pnVarPrepForStore($author) . "',
                                     '" . pnVarPrepForStore($notes) . "',
                                     '" . pnVarPrepForStore($ihome) . "',
                                     '',
                                     '" . pnVarPrepForStore($alanguage) . "',
                                     '" . pnVarPrepForStore($comm) . "',
                                     '" . pnVarPrepForStore($format_type) . "')");
		if ($dbconn->ErrorNo() !== 0) {
			$msg = $dbconn->ErrorMsg();
			include 'header.php';
			echo $msg;
			include 'footer.php';
			return;
		}
    	$sid = $dbconn->PO_Insert_ID($pntable['stories'], $column['sid']);
		// Let any hooks know that we have created a new item
		pnModCallHooks('item', 'create', $sid, array('module' => 'News'));

        if (!empty($uid)) {
            $column = &$pntable['users_column'];
            $result =& $dbconn->Execute("UPDATE {$pntable['users']}
                                      SET {$column['counter']}={$column['counter']}+1
                                      WHERE {$column['uid']}='".(int)pnVarPrepForStore($uid)."'");
			if ($dbconn->ErrorNo() !== 0) {
				$msg = $dbconn->ErrorMsg();
				include 'header.php';
				echo $msg;
				include 'footer.php';
				return;
			}
        }
		//delete story from queue
		$sql = "DELETE FROM {$pntable['queue']}
						WHERE {$pntable['queue_column']['qid']}='".(int)pnVarPrepForStore($qid)."'";
        $result =& $dbconn->Execute($sql);
    	if ($dbconn->ErrorNo() !== 0) {
    		$msg = $dbconn->ErrorMsg();
    		include 'header.php';
    		echo $msg;
    		include 'footer.php';
    		return;
    	}
		pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_STORYPOSTED));
        pnRedirect('admin.php?module=AddStory&op=submissions');
        return true;
    }
}

function editStory()
{
    $sid = pnVarCleanFromInput('sid');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column = &$pntable['stories_column'];
    $catcolumn = &$pntable['stories_cat_column'];
    $result =& $dbconn->Execute("SELECT {$column['catid']}, {$column['title']},
                                {$column['hometext']}, {$column['bodytext']},
                                {$column['topic']}, {$column['notes']}, {$column['ihome']},
                                {$column['alanguage']}, {$column['withcomm']}, {$column['aid']},
                                {$column['informant']}, {$column['format_type']}
                              FROM {$pntable['stories']}
                              WHERE {$column['sid']}='".(int)pnVarPrepForStore($sid)."'");

    if ($result->EOF) {
        include ('header.php');
        GraphicAdmin();
        OpenTable();
        echo '<h2>'._ARTICLEADMIN.'</h2>';
        CloseTable();
        OpenTable();
        echo '<div style="text-align:center"><strong>'._NOTSUCHARTICLE.'</strong><br />'._GOBACK.'</div>';
        CloseTable();
        include('footer.php');
        return;
    }

    list($catid, $subject, $hometext, $bodytext, $topic, $notes, $ihome, $alanguage, $comm, $aid, $informant, $format_type) = $result->fields;
    $result->Close();
    $result =& $dbconn->Execute("SELECT {$catcolumn['title']}
                              FROM {$pntable['stories_cat']}
                              WHERE {$catcolumn['catid']} = '".(int)pnVarPrepForStore($catid)."'");
    if ($result->PO_RecordCount($pntable['stories_cat'], "{$catcolumn['catid']} = $catid")== 1) {
        list($cattitle) = $result->fields;
    } else {
        $cattitle = '';
    }
    $result->Close();

    if (pnSecAuthAction(0, 'Stories::Story', "$aid:$cattitle:$sid", ACCESS_EDIT)) {
        include ('header.php');
        GraphicAdmin();
        OpenTable();
        echo '<h1>'._ARTICLEADMIN.'</h1>';
        CloseTable();
    	$msg = pnGetStatusMsg();
    	if ($msg != '') {
    		OpenTable();
    		echo $msg;
    		CloseTable();
    	}

        OpenTable();
        echo '<h2>'._EDITARTICLE.'</h2>';

        // judgej - Removed pre-processing of strings to preview.
        storyPreview($subject, $hometext, $bodytext, unnltobr($notes), $topic, $format_type);
        echo '<form action="admin.php" method="post"><div>';

        storyEdit($subject, $hometext, $bodytext, $notes, $topic, $ihome, $catid, $alanguage, $comm, $aid, $informant, $format_type);

		echo pnModCallHooks('item', 'modify', $sid, array('module' => 'News'));

        echo '<input type="hidden" name="sid" size="50" value="'.pnVarPrepForDisplay($sid).'" />'
            .'<input type="hidden" name="module" value="AddStory" />'
            .'<input type="hidden" name="op" value="ChangeStory" />'
            .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
            .'<input type="submit" value="'._SAVECHANGES.'" />'
            .'</div></form>';
        CloseTable();
        include ('footer.php');
    } else {
        include ('header.php');
        GraphicAdmin();
        OpenTable();
        echo '<h1>'._ARTICLEADMIN.'</h1>';
        CloseTable();

    	$msg = pnGetStatusMsg();
    	if ($msg != '') {
    		OpenTable();
    		echo $msg;
    		CloseTable();
    	}

        OpenTable();
        echo '<div style="text-align:center"><strong>'._NOTAUTHORIZED1.'</strong><br />'._GOBACK.'</div>';
        CloseTable();
        include('footer.php');
    }
}

function removeStory($sid, $ok=0)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column = &$pntable['stories_column'];
    $sql = "SELECT $column[aid],
                   $column[catid],
                   $column[title]
            FROM $pntable[stories]
            WHERE $column[sid] = '" . (int)pnVarPrepForStore($sid)."'";
    $result =& $dbconn->Execute($sql);
    list($aid, $catid, $stitle) = $result->fields;
    $result->Close();

    $column = &$pntable['stories_cat_column'];
    $sql = "SELECT $column[title]
            FROM $pntable[stories_cat]
            WHERE $column[catid] = '" . (int)pnVarPrepForStore($catid)."'";
    $result =& $dbconn->Execute($sql);
    list($cattitle) = $result->fields;
    $result->Close();

    if (pnSecAuthAction(0, 'Stories::Story', "$aid:$cattitle:$sid", ACCESS_DELETE)) {
        if($ok == 1) {

        if (!pnSecConfirmAuthKey()) {
            include 'header.php';
            echo _BADAUTHKEY;
            include 'footer.php';
            exit;
            }
            $column = &$pntable['stories_column'];
            $sql = "DELETE FROM $pntable[stories]
                    WHERE $column[sid] = '" . (int)pnVarPrepForStore($sid)."'";
            $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() !== 0) {
				$msg = $dbconn->ErrorMsg();
				include 'header.php';
				echo $msg;
				include 'footer.php';
				return;
			}
			// Let any hooks know that we have deleted an item
			pnModCallHooks('item', 'delete', $sid, array('module' => News));

			if (pnModAvailable('Comments')) {
				$column = &$pntable['comments_column'];
				$sql = "DELETE FROM $pntable[comments]
						WHERE $column[sid] = '" . (int)pnVarPrepForStore($sid)."'";
				$dbconn->Execute($sql);
				if ($dbconn->ErrorNo() !== 0) {
					$msg = $dbconn->ErrorMsg();
					include 'header.php';
					echo $msg;
					include 'footer.php';
					return;
				}
			}
            pnRedirect('admin.php');
        } else {
            include('header.php');
            GraphicAdmin();
            OpenTable();
            echo '<h1>'._ARTICLEADMIN.'</h1>';
            CloseTable();

        	$msg = pnGetStatusMsg();
        	if ($msg != '') {
        		OpenTable();
        		echo $msg;
        		CloseTable();
        	}

            OpenTable();
            echo '<div style="text-align:center">'._REMOVESTORY.'<strong> '.pnVarPrepForDisplay($sid).' - '.pnVarPrepForDisplay($stitle).'</strong> - '._ANDCOMMENTS;
            echo '<table><tr><td>'."\n";
            echo '<form action="admin.php" method="post"><div>';
            echo '<input type="submit" value="'.pnVarPrepForDisplay(_NO).'" />';
            echo '</div></form>'."\n";
            echo '</td><td>'."\n";
            echo '<form action="admin.php" method="post"><div>';
            echo '<input type="submit" value="'.pnVarPrepForDisplay(_YES).'" />';
            echo '<input type="hidden" name="ok" value="1" />';
            echo '<input type="hidden" name="module" value="AddStory" />';
            echo '<input type="hidden" name="op" value="RemoveStory" />';
            echo '<input type="hidden" name="sid" value="'.pnVarPrepForDisplay($sid).'" />';
            echo '<input type="hidden" name="authid" value="'.pnSecGenAuthKey().'" />';
            echo '</div></form>'."\n";
            echo '</td></tr></table>'."\n";
            echo '</div>'."\n";
            CloseTable();
            include('footer.php');
        }
    } else {
        include ('header.php');
        GraphicAdmin();
        OpenTable();
        echo '<h1>'._ARTICLEADMIN.'</h1>';
        CloseTable();

    	$msg = pnGetStatusMsg();
    	if ($msg != '') {
    		OpenTable();
    		echo $msg;
    		CloseTable();
    	}

        OpenTable();
        echo '<div style="text-align:center"><strong>'._NOTAUTHORIZED1.'</strong><br />'._GOBACK.'</div>';
        CloseTable();
        include('footer.php');
    }
}

function deleteStory($qid)
{

    $module = pnVarCleanFromInput('module');

    // Confirm authorisation code
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $result =& $dbconn->Execute("DELETE FROM {$pntable['queue']}
                              WHERE {$pntable['queue_column']['qid']}='".(int)pnVarPrepForStore($qid)."'");
	if ($dbconn->ErrorNo() !== 0) {
		$msg = $dbconn->ErrorMsg();
		include 'header.php';
		echo $msg;
		include 'footer.php';
		return;
	}

    pnRedirect('admin.php'.'?module=AddStory&op=submissions');
}

function changeStory()
{
    // Confirm authorisation code
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    list($sid,
         $subject,
         $hometext,
         $bodytext,
         $topic,
         $notes,
         $catid,
         $ihome,
         $alanguage,
         $comm,
         $format_type_home,
         $format_type_body) = pnVarCleanFromInput('sid',
                                      'subject',
                                      'hometext',
                                      'bodytext',
                                      'topic',
                                      'notes',
                                      'catid',
                                      'ihome',
                                      'alanguage',
                                      'comm',
                                      'format_type_home',
                                      'format_type_body');

    if (!isset($format_type_home)) {
        $format_type_home = 0;
    }

    if (!isset($format_type_body)) {
        $format_type_body = 0;
    }

    $format_type = (($format_type_body%4)*4) + ($format_type_home%4);

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // jgm - need to get instance information here
    $column = &$pntable['stories_cat_column'];
    $result =& $dbconn->Execute("SELECT $column[title]
                              FROM $pntable[stories_cat]
                              WHERE $column[catid] = '".(int)pnVarPrepForStore($catid)."'");
	if ($dbconn->ErrorNo() !== 0) {
		$msg = $dbconn->ErrorMsg();
		include 'header.php';
		echo $msg;
		include 'footer.php';
		return;
	}
    if ($result->PO_RecordCount($pntable['stories_cat'], "{$column['catid']} = $catid")== 1) {
        list($cattitle) = $result->fields;
    } else {
        $cattitle = '';
    }
    $result->Close();

    // TODO: handle these.
    if ($format_type_home == 0)
    {
        $hometext = nl2br($hometext);
    }
    if ($format_type_body == 0)
    {
        $bodytext = nl2br($bodytext);
    }
    $notes = nl2br($notes);

    $column = &$pntable['stories_column'];
    $result =& $dbconn->Execute("SELECT $column[aid]
                              FROM $pntable[stories]
                              WHERE $column[sid]='".(int)pnVarPrepForStore($sid)."'");
    list($aid) = $result->fields;
    $result->Close();

    if (pnSecAuthAction(0, 'Stories::Story', "$aid:$cattitle:$sid", ACCESS_EDIT)) {

        $column = &$pntable['stories_column'];
        $result =& $dbconn->Execute("UPDATE $pntable[stories]
                                    SET $column[catid]='" . pnVarPrepForStore($catid) . "',
                                        $column[title]='" . pnVarPrepForStore($subject) . "',
                                        $column[hometext]='" . pnVarPrepForStore($hometext) . "',
                                        $column[bodytext]='" . pnVarPrepForStore($bodytext) . "',
                                        $column[topic]='" . pnVarPrepForStore($topic) . "',
                                        $column[notes]='" . pnVarPrepForStore($notes) . "',
                                        $column[ihome]='" . pnVarPrepForStore($ihome) . "',
                                        $column[alanguage]='" . pnVarPrepForStore($alanguage) . "',
                                        $column[withcomm]='" . pnVarPrepForStore($comm) . "',
                                        $column[format_type]='" . pnVarPrepForStore($format_type) . "'
                                  WHERE $column[sid]='" . (int)pnVarPrepForStore($sid)."'");
		if ($dbconn->ErrorNo() !== 0) {
			$msg = $dbconn->ErrorMsg();
			include 'header.php';
			echo $msg;
			include 'footer.php';
			return;
		}
		pnModCallHooks('item', 'update', $sid, array('module' => 'News'));
        pnRedirect('admin.php?module=AddStory&op=main');
    }
}

function adminStory() {

    list($module, $automated) = pnVarCleanFromInput('module','automated');
    if (!isset($automated)) {
        $automated = 0;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    modules_get_manual();
    include ('header.php');
    GraphicAdmin();
    OpenTable();
    echo '<h1>'._ARTICLEADMIN.'</h1>';

	$msg = pnGetStatusMsg();
	if ($msg != '') {
		OpenTable();
		echo $msg;
		CloseTable();
	}

    echo '<h2><a href="admin.php?module=AddStory&amp;op=submissions">'._NEWSUBMISSIONS.'</a></h2>';
    CloseTable();
    OpenTable();
    echo '<h2>'._ADDARTICLE.'</h2>'
        .'<form action="admin.php" method="post"><div>';

    storyEdit($subject='', $hometext='', $bodytext='', $notes='', $topic='', $ihome=0, $catid='', $alanguage='', $comm=0, $aid='', $informant='', defaultFormatType('', ''));
    buildProgramStoryMenu($automated);
    buildCalendarMenu(false, $year, $day, $month, $hour, $min);
	echo pnModCallHooks('item', 'new', '', array('module' => 'News'));
    echo '<br /><input type="hidden" name="module" value="AddStory" />'
         .'<select name="op">'
         .'<option value="PreviewAdminStory" selected="selected">'._PREVIEWSTORY.'</option>'
         .'<option value="PostAdminStory">'._POSTSTORY.'</option>'
         .'</select>'
         .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
         .'<input type="submit" value="'._OK.'" />'
		 .'</div></form>';
    CloseTable();
    include ('footer.php');
}

function previewAdminStory()
{
    // Confirm authorisation code
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    list($module,
    	 $automated,
         $year,
         $day,
         $month,
         $hour,
         $min,
         $subject,
         $hometext,
         $bodytext,
         $topic,
         $catid,
         $ihome,
         $alanguage,
         $notes,
         $comm,
         $format_type_home,
         $format_type_body) = pnVarCleanFromInput('module',
         							  'automated',
                                      'year',
                                      'day',
                                      'month',
                                      'hour',
                                      'min',
                                      'subject',
                                      'hometext',
                                      'bodytext',
                                      'topic',
                                      'catid',
                                      'ihome',
                                      'alanguage',
                                      'notes',
                                      'comm',
                                      'format_type_home',
                                      'format_type_body');

    if (!isset($format_type_home)) {
        $format_type_home = 0;
    }

    if (!isset($format_type_body)) {
        $format_type_body = 0;
    }

    $format_type = (($format_type_body%4)*4) + ($format_type_home%4);

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!isset($automated)) {
        $automated = 0;
    }

    include ('header.php');
    if ($topic<1) {
        $topic = 1;
    }
    GraphicAdmin();
    OpenTable();
    echo '<h1>'._ARTICLEADMIN.'</h1>';
    CloseTable();

	$msg = pnGetStatusMsg();
	if ($msg != '') {
		OpenTable();
		echo $msg;
		CloseTable();
	}

    OpenTable();
    echo '<h2>'._PREVIEWSTORY.'</h2>'
    	.'<form action="admin.php" method="post"><div>';
    storyPreview($subject, $hometext, $bodytext, $notes, $topic, $format_type);
    storyEdit($subject, $hometext, $bodytext, $notes, $topic, $ihome, $catid, $alanguage, $comm, $aid="", $informant="", $format_type);
    buildProgramStoryMenu($automated);
    buildCalendarMenu(true, $year, $day, $month, $hour, $min);
    echo '<input type="hidden" name="module" value="AddStory" />'
         .'<select name="op">'
         .'<option value="PreviewAdminStory" selected="selected">'._PREVIEWSTORY.'</option>'
         .'<option value="PostAdminStory">'._POSTSTORY.'</option>'
         .'</select>'
         .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
         .'<input type="submit" value="'._OK.'" />'
         .'</div></form>';
    CloseTable();
    include ('footer.php');
}

function postAdminStory()
{
    // Confirm authorisation code
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    list($automated,
         $year,
         $day,
         $month,
         $hour,
         $min,
         $subject,
         $hometext,
         $bodytext,
         $topic,
         $catid,
         $ihome,
         $alanguage,
         $notes,
         $comm,
         $format_type_home,
         $format_type_body) = pnVarCleanFromInput('automated',
                                      'year',
                                      'day',
                                      'month',
                                      'hour',
                                      'min',
                                      'subject',
                                      'hometext',
                                      'bodytext',
                                      'topic',
                                      'catid',
                                      'ihome',
                                      'alanguage',
                                      'notes',
                                      'comm',
                                      'format_type_home',
                                      'format_type_body');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!isset($format_type_home)) {
        $format_type_home = 0;
    }

    if (!isset($format_type_body)) {
        $format_type_body = 0;
    }

    $format_type = (($format_type_body%4)*4) + ($format_type_home%4);

    if (empty($subject)) {
        include 'header.php';
        echo _ADDSTORYNOSUBJECT;
        include 'footer.php';
        exit;
    }

    if (!isset($automated)) {
        $automated = 0;
    }

    if (pnUserLoggedIn()) {
        $uid = pnUserGetVar('uid');
        $name = pnUserGetVar('uname');
    } else {
        $uid = 0;
        $name = 'anonymous';
    }

    // Special munging of pseudo-html to add in <br /> in place of \n
    // This will go away as soon as I write a dropdown to select the
    // appropriate translation (and some translation handlers)

    if ($format_type_home == 0) {
        $hometext = nl2br($hometext);
    }
    if ($format_type_body == 0) {
        $bodytext = nl2br($bodytext);
    }
    $notes = nl2br($notes);

    if ($automated == 1) {
        if ($day < 10) {
            $day = "0$day";
        }
        if ($month < 10) {
            $month = "0$month";
        }
        $sec = '00';
        $date = "$year-$month-$day $hour:$min:$sec";

        $column = &$pntable['autonews_column'];
        $nextid = $dbconn->GenId($pntable['autonews']);
        $result =& $dbconn->Execute("INSERT INTO $pntable[autonews] (
                                      $column[anid],
                                      $column[catid],
                                      $column[aid],
                                      $column[title],
                                      $column[time],
                                      $column[hometext],
                                      $column[bodytext],
                                      $column[topic],
                                      $column[informant],
                                      $column[notes],
                                      $column[ihome],
                                      $column[alanguage],
                                      $column[withcomm])
                                    VALUES (
                                      '" . pnVarPrepForStore($nextid) . "',
                                      '" . pnVarPrepForStore($catid) . "',
                                      '" . pnVarPrepForStore($uid) . "',
                                      '" . pnVarPrepForStore($subject) . "',
                                      '" . pnVarPrepForStore($date) . "',
                                      '" . pnVarPrepForStore($hometext) . "',
                                      '" . pnVarPrepForStore($bodytext) . "',
                                      '" . pnVarPrepForStore($topic) . "',
                                      '" . pnVarPrepForStore($name) . "',
                                      '" . pnVarPrepForStore($notes) . "',
                                      '" . pnVarPrepForStore($ihome) . "',
                                      '" . pnVarPrepForStore($alanguage) . "',
                                      '" . pnVarPrepForStore($comm) . "')");
		if ($dbconn->ErrorNo() !== 0) {
			$msg = $dbconn->ErrorMsg();
			include 'header.php';
			echo $msg;
			include 'footer.php';
			return;
		}
		pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_STORYPOSTED));
        pnRedirect('admin.php?module=AddStory&op=submissions');
        return true;
    } else {
        $column = &$pntable['stories_column'];
        $nextid = $dbconn->GenId($pntable['stories']);
        $result =& $dbconn->Execute("INSERT INTO $pntable[stories] (
                                      $column[sid],
                                      $column[catid],
                                      $column[aid],
                                      $column[title],
                                      $column[time],
                                      $column[hometext],
                                      $column[bodytext],
                                      $column[comments],
                                      $column[counter],
                                      $column[topic],
                                      $column[informant],
                                      $column[notes],
                                      $column[ihome],
                                      $column[themeoverride],
                                      $column[alanguage],
                                      $column[withcomm],
                                      $column[format_type])
                                    VALUES (
                                      '" . pnVarPrepForStore($nextid) . "',
                                      '" . pnVarPrepForStore($catid) . "',
                                      '" . pnVarPrepForStore($uid) . "',
                                      '" . pnVarPrepForStore($subject) . "',
                                      now(),
                                      '" . pnVarPrepForStore($hometext) . "',
                                      '" . pnVarPrepForStore($bodytext) . "',
                                      '0',
                                      '0',
                                      '" . pnVarPrepForStore($topic) . "',
                                      '" . pnVarPrepForStore($name) . "',
                                      '" . pnVarPrepForStore($notes) . "',
                                      '" . pnVarPrepForStore($ihome) . "',
                                      '',
                                      '" . pnVarPrepForStore($alanguage) . "',
                                      '" . pnVarPrepForStore($comm) . "',
                                      '" . pnVarPrepForStore($format_type) . "')");
		if ($dbconn->ErrorNo() !== 0) {
			$msg = $dbconn->ErrorMsg();
			include 'header.php';
			echo $msg;
			include 'footer.php';
			return;
		}
    	$sid = $dbconn->PO_Insert_ID($pntable['stories'], $column['sid']);
		pnModCallHooks('item', 'create', $sid, array('module' => 'News'));
		pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_STORYPOSTED));
        pnRedirect('admin.php?module=AddStory&op=submissions');
        return true;
    }
}

function autodelete($anid, $ok=0)
{
    $module = pnVarCleanFromInput('module');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column = &$pntable['autonews_column'];
    $result =& $dbconn->Execute("SELECT {$column['title']}, {$column['aid']}, {$column['catid']}
                               FROM {$pntable['autonews']}
                               WHERE {$column['anid']}='".(int)pnVarPrepForStore($anid)."'");
    list($titlean, $aid, $catid) = $result->fields;
    $result->Close();

    $column = &$pntable['stories_cat_column'];
    $result =& $dbconn->Execute("SELECT {$column['title']}
                              FROM {$pntable['stories_cat']}
                              WHERE {$column['catid']}='".(int)pnVarPrepForStore($catid)."'");
    list($cattitle) = $result->fields;
    $result->Close();

    if (pnSecAuthAction(0, 'Stories::Story', "$aid:$cattitle:$anid", ACCESS_DELETE)) {
        //if($ok && csssafe("postnuke")) { // csssafe always returns true?!
		if($ok==1) {
            $result =& $dbconn->Execute("DELETE FROM {$pntable['autonews']}
                                      WHERE {$pntable['autonews_column']['anid']}='".(int)pnVarPrepForStore($anid)."'");
			if ($dbconn->ErrorNo() !== 0) {
				$msg = $dbconn->ErrorMsg();
				include 'header.php';
				echo $msg;
				include 'footer.php';
				return;
			}
            $column = &$pntable['users_column'];
            $result =& $dbconn->Execute("UPDATE {$pntable['users']}
                                      SET {$column['counter']} = {$column['counter']} - 1
                                      WHERE {$column['uid']}='".(int)pnVarPrepForStore($aid)."'");
			if ($dbconn->ErrorNo() !== 0) {
				$msg = $dbconn->ErrorMsg();
				include 'header.php';
				echo $msg;
				include 'footer.php';
				return;
			}
            pnRedirect('admin.php');
        } else {
            include('header.php');
            GraphicAdmin();
            OpenTable();
            echo '<h1>'._ARTICLEADMIN.'</h1>';
            CloseTable();

        	$msg = pnGetStatusMsg();
        	if ($msg != '') {
        		OpenTable();
        		echo $msg;
        		CloseTable();
        	}

            OpenTable();
            echo '<div style="text-align:center">'._REMOVEAUTOSTORY.'<strong> '.pnVarPrepForDisplay($anid).' - '.pnVarPrepForDisplay($titlean).'</strong>';
            echo '<table><tr><td>'."\n";
            echo '<form action="admin.php" method="post"><div>';
            echo '<input type="submit" value="'.pnVarPrepForDisplay(_NO).'" />';
            echo '</div></form>'."\n";
            echo '</td><td>'."\n";
            echo '<form action="admin.php" method="post"><div>';
            echo '<input type="submit" value="'.pnVarPrepForDisplay(_YES).'" />';
            echo '<input type="hidden" name="module" value="AddStory" />';
            echo '<input type="hidden" name="op" value="autoDelete" />';
            echo '<input type="hidden" name="anid" value="'.pnVarPrepForDisplay($anid).'" />';
            echo '<input type="hidden" name="ok" value="1" />';
            echo '</div></form>'."\n";
            echo '</td></tr></table>'."\n";
            echo '</div>'."\n";
            CloseTable();
            include('footer.php');
        }
    } else {
        include ('header.php');
        GraphicAdmin();
        OpenTable();
        echo '<h1>'._ARTICLEADMIN.'</h1>';
        CloseTable();

    	$msg = pnGetStatusMsg();
    	if ($msg != '') {
    		OpenTable();
    		echo $msg;
    		CloseTable();
    	}

        OpenTable();
        echo '<div style="text-align:center"><strong>'._NOTAUTHORIZED1.'</strong><br />'._GOBACK.'</div>';
        CloseTable();
        include('footer.php');
    }
}

function autoEdit($anid)
{

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $module = pnVarCleanFromInput('module');
    $automated = pnVarCleanFromInput('automated');

    if (!isset($automated)) {
        $automated = 0;
    }

    $ancolumn = &$pntable['autonews_column'];
    $sccolumn = &$pntable['stories_cat_column'];
    $result =& $dbconn->Execute("SELECT $ancolumn[title], $sccolumn[title], $sccolumn[catid]
                               FROM $pntable[autonews], $pntable[stories_cat]
                               WHERE $ancolumn[anid]='".(int)pnVarPrepForStore($anid)."'
                               AND $ancolumn[catid]=$sccolumn[catid]");

    list($titleauto, $cattitle, $aid) = $result->fields;
    $result->Close();

    if (pnSecAuthAction(0, 'Stories::Story', "$aid:$cattitle:$anid", ACCESS_EDIT)) {

        include ('header.php');
        $column = &$pntable['autonews_column'];
        $result =& $dbconn->Execute("SELECT $column[catid], $column[aid], $column[title],
                                    $column[time], $column[hometext],
                                    $column[bodytext], $column[topic],
                                    $column[informant], $column[notes],
                                    $column[ihome], $column[alanguage],
                                    $column[withcomm]
                                  FROM $pntable[autonews]
                                  WHERE $column[anid]='".(int)pnVarPrepForStore($anid)."'");

        list($catid, $aid, $title, $time, $hometext, $bodytext, $topic, $informant, $notes, $ihome, $alanguage, $comm) = $result->fields;
        ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime);
        $day = $datetime[3];
        $month = $datetime[2];
        $year = $datetime[1];
        $hour = $datetime[4];
        $min = $datetime[5];
        $automated = 1;
        GraphicAdmin();
        OpenTable();
        echo '<h1>'._ARTICLEADMIN.'</h1>';
        CloseTable();

    	$msg = pnGetStatusMsg();
    	if ($msg != '') {
    		OpenTable();
    		echo $msg;
    		CloseTable();
    	}

        OpenTable();
        echo '<h2>'._AUTOSTORYEDIT.'</h2>';

        $format_type = defaultFormatType($hometext, $bodytext);

        storyPreview($title, $hometext, $bodytext, $notes, $topic, $format_type);

        echo '<form action="admin.php" method="post"><div>';
        storyEdit($title, $hometext, $bodytext, $notes, $topic, $ihome, $catid, $alanguage, $comm, $aid, $informant, $format_type);
		// echo '<br /><strong>'._CHNGPROGRAMSTORY.'</strong><br />';
        buildCalendarMenu(true, $year, $day, $month, $hour, $min);
        echo '<input type="hidden" name="anid" value="'.pnVarPrepForDisplay($anid).'" />'
            .'<input type="hidden" name="module" value="AddStory" />'
            .'<input type="hidden" name="op" value="autoSaveEdit" />'
            .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
            .'<input type="submit" value="'._SAVECHANGES.'" />'
            .'</div></form>';
        CloseTable();
        include ('footer.php');
    } else {
        include ('header.php');
        GraphicAdmin();
        OpenTable();
        echo '<h1>'._ARTICLEADMIN.'</h1>';
        CloseTable();

    	$msg = pnGetStatusMsg();
    	if ($msg != '') {
    		OpenTable();
    		echo $msg;
    		CloseTable();
    	}

        OpenTable();
        echo '<div style="text-align:center"><strong>'._NOTAUTHORIZED1.'</strong><br />'._GOBACK.'</div>';
        CloseTable();
        include('footer.php');
    }
}

function autoSaveEdit()
{
    list($anid,
         $year,
         $day,
         $month,
         $hour,
         $min,
         $subject,
         $hometext,
         $bodytext,
         $topic,
         $notes,
         $catid,
         $ihome,
         $alanguage,
         $comm,
         $format_type_home,
         $format_type_body) = pnVarCleanFromInput('anid',
                                      'year',
                                      'day',
                                      'month',
                                      'hour',
                                      'min',
                                      'subject',
                                      'hometext',
                                      'bodytext',
                                      'topic',
                                      'notes',
                                      'catid',
                                      'ihome',
                                      'alanguage',
                                      'comm',
                                      'format_type_home',
                                      'format_type_body');

    if (!isset($format_type_home)) {
        $format_type_home = 0;
    }

    if (!isset($format_type_body)) {
        $format_type_body = 0;
    }

    $format_type = (($format_type_body%4)*4) + ($format_type_home%4);

    if ($format_type_home == 0) {
        $hometext = nl2br($hometext);
    }
    if ($format_type_body == 0) {
        $bodytext = nl2br($bodytext);
    }
    $notes = nl2br($notes);

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column = &$pntable['autonews_column'];
    $result =& $dbconn->Execute("SELECT $column[aid]
                               FROM $pntable[autonews]
                               WHERE $column[anid]='".(int)pnVarPrepForStore($anid)."'");
    list($aid) = $result->fields;
    $result->Close();

    $column = &$pntable['stories_cat_column'];
    $result =& $dbconn->Execute("SELECT $column[title]
                              FROM $pntable[stories_cat]
                              WHERE $column[catid]='".(int)pnVarPrepForStore($catid)."'");
    list($cattitle) = $result->fields;
    $result->Close();

    if (pnSecAuthAction(0, 'Stories::Story', "$aid:$cattitle:$anid", ACCESS_EDIT)) {
        if ($day < 10) {
            $day = "0$day";
        }
        if ($month < 10) {
            $month = "0$month";
        }
        $sec = "00";
        $date = "$year-$month-$day $hour:$min:$sec";
        $title = $subject;
        $column = &$pntable['autonews_column'];
        $sql = "UPDATE $pntable[autonews]
                SET $column[catid]='" . pnVarPrepForStore($catid) . "',
                    $column[title]='" . pnVarPrepForStore($title) . "',
                    $column[time]='" . pnVarPrepForStore($date) . "',
                    $column[hometext]='" . pnVarPrepForStore($hometext) . "',
                    $column[bodytext]='" . pnVarPrepForStore($bodytext) . "',
                    $column[topic]='" . pnVarPrepForStore($topic) . "',
                    $column[notes]='" . pnVarPrepForStore($notes) . "',
                    $column[ihome]='" . pnVarPrepForStore($ihome) . "',
                    $column[alanguage]='" . pnVarPrepForStore($alanguage) . "',
                    $column[withcomm]='" . pnVarPrepForStore($comm) . "'
                WHERE $column[anid]='" . (int)pnVarPrepForStore($anid)."'";
        $result =& $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() !== 0) {
			$msg = $dbconn->ErrorMsg();
			include 'header.php';
			echo $msg;
			include 'footer.php';
			return;
		}
        pnRedirect('admin.php?module=AddStory&op=main');
    } else {
        include ('header.php');
        GraphicAdmin();
        OpenTable();
        echo '<h1>'._ARTICLEADMIN.'</h1>';
        CloseTable();

    	$msg = pnGetStatusMsg();
    	if ($msg != '') {
    		OpenTable();
    		echo $msg;
    		CloseTable();
    	}

        OpenTable();
        echo '<div style="text-align:center"><strong>'._NOTAUTHORIZED1.'</strong><br />'._GOBACK.'</div>';
        CloseTable();
        include('footer.php');
    }
}

function submissions()
{
    $module = pnVarCleanFromInput('module');
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    //$module = $GLOBALS['ModName'];
    $dummy = 0;
    include ('header.php');
    GraphicAdmin();
    OpenTable();
    echo '<h1>'._SUBMISSIONSADMIN.'</h1>';
    CloseTable();

	$msg = pnGetStatusMsg();
	if ($msg != '') {
		OpenTable();
		echo $msg;
		CloseTable();
	}

    OpenTable();
    $lang = languagelist();
    $column = &$pntable['queue_column'];
    $result =& $dbconn->Execute("SELECT $column[qid], $column[subject], $column[timestamp], $column[alanguage]
                             FROM $pntable[queue] WHERE $column[arcd]='0' ORDER BY $column[timestamp]");
    if($result->EOF) {
        echo '<table width="100%"><tr><td align="center"><strong>'
        ._NOSUBMISSIONS.'</strong> [ <a href="admin.php?module=AddStory&amp;op=ListArchive">'._ARCHIVESUBS."</a> ]</td></tr></table>\n";
    } else {
        echo '<div style="text-align:center"><strong>'._NEWSUBMISSIONS
        .'</strong> [ <a href="admin.php?module=AddStory&amp;op=ListArchive">'
        ._ARCHIVESUBS.'</a> ]</div><table width="100%" border="1">'."\n";

            while(list($qid, $subject, $timestamp, $alanguage) = $result->fields) {
                echo "<tr>\n";
                echo '<td align="center">';
                //echo '<table><tr><td><div style="text-align:center">'."\n";
                if (pnSecAuthAction(0, 'Stories::Story', '::', ACCESS_EDIT)) {
                    echo '<form action="admin.php" method="post"><div>'."\n"
                        .'<input type="hidden" name="module" value="AddStory" />'."\n"
                        .'<input type="hidden" name="qid" value="'.pnVarPrepForDisplay($qid).'" />'."\n"
                        .'<select name="op">'."\n"
                        .'<option value="DisplayStory" selected="selected">'._PREVIEWSTORY.'</option>'."\n";
// jgm - this option doesn't work, removeStory() deletes stories from the stories table, not the
//       queue.  Commented out until someone can get around to fixing it
// TODO - create a removeQueue() or whatever to remove the story directly from the queue
//                    if (pnSecAuthAction(0, 'Stories::Story', '::', ACCESS_DELETE)) {
//                        echo '<option value="RemoveStory">'._DELETE.'</option>'."\n";
//                    }
                    if (pnSecAuthAction(0, 'Stories::Story', '::', ACCESS_DELETE)) {
                        echo '<option value="ArchiveStory">'._ARCHIVE.'</option>'."\n";
                    }
                    echo '</select>&nbsp;'."\n"
                        .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
                        .'<input type="submit" value="'._GO.'" />'."\n"
                        .'</div></form>'."\n";
                        //.'</div>'."\n";
                }
                //echo '</td></tr></table>'."\n";
                echo '</td>'."\n";
                echo '<td>'."\n";

                if ($subject == '') {
                    echo '&nbsp; '._NOSUBJECT."\n";
                } else {
                    echo '&nbsp; '.pnVarPrepHTMLDisplay($subject) ."\n";
                }
                if ($alanguage=='') $lang[$alanguage]=_ALL;
                echo '</td><td align="center">'.pnVarPrepForDisplay($lang[$alanguage])."\n"; /* ML added column to display the language */
                //$timestamp = ereg_replace(" ", "@", $timestamp);
                $formatted_date = ml_ftime(_DATETIMELONG, $dbconn->UnixTimestamp($timestamp));

                echo '</td><td align="right">&nbsp;'.pnVarPrepForDisplay($formatted_date).'&nbsp;</td></tr>'."\n";
                $dummy++;
                $result->MoveNext();
            }
        if ($dummy < 1) {
            echo '<tr><td align="center"><strong>'._NOSUBMISSIONS.'</strong></form></td></tr></table>'."\n";
        } else {
            echo '</table>'."\n";
        }
    }
    CloseTable();
    include ('footer.php');
}

function ArchiveStory($qid)
{
    // Confirm authorisation code
/*    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }*/
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    include 'header.php';
    GraphicAdmin();

    OpenTable();
	echo '<h1>'._ARCHIVE.' '._FSTORY.'</h1>';
    CloseTable();

	$msg = pnGetStatusMsg();
	if ($msg != '') {
		OpenTable();
		echo $msg;
		CloseTable();
	}

    $column = &$pntable['queue_column'];
    $result =& $dbconn->Execute("SELECT {$column['subject']}
                              FROM {$pntable['queue']}
                              WHERE {$column['qid']}='".(int)pnVarPrepForStore($qid)."'");
	if ($dbconn->ErrorNo() !== 0) {
		$msg = $dbconn->ErrorMsg();
		include 'header.php';
		echo $msg;
		include 'footer.php';
		return;
	}

	OpenTable();
    while(list($subject) = $result->fields) {
        echo '<strong>'._ARCHIVECHOSE.'</strong> '.pnVarPrepForDisplay($subject);
        $result->MoveNext();
    }
    echo '<form action="admin.php" method="post"><div>'
            .'<p>'._LOOKSRIGHT.'<p>'
            .'<input type="submit" value="'._YES.'" />'
            .' '._GOBACK
            .'<input type="hidden" name="qid" value="'.pnVarPrepForDisplay($qid).'" />'
            .'<input type="hidden" name="module" value="AddStory" />'
            .'<input type="hidden" name="op" value="Archive" />'
            .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
            .'</div></form>';
	CloseTable();

    include('footer.php');
}

function Archive($qid)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    include 'header.php';
    GraphicAdmin();

    OpenTable();
	echo '<h1>'._ARCHIVING.'</h1>';
    CloseTable();

	$msg = pnGetStatusMsg();
	if ($msg != '') {
		OpenTable();
		echo $msg;
		CloseTable();
	}

    $column = &$pntable['queue_column'];
    $result =& $dbconn->Execute("UPDATE {$pntable['queue']}
                              SET {$column['arcd']}='1'
                              WHERE {$column['qid']}='".(int)pnVarPrepForStore($qid)."'");
	if ($dbconn->ErrorNo() !== 0) {
		$msg = $dbconn->ErrorMsg();
		include 'header.php';
		echo $msg;
		include 'footer.php';
		return;
	}

    echo '<p>'._ARCHIVESUCCESS.'</p>';
    echo '[ <a href="admin.php?module=AddStory&amp;op=submissions">'._SUBMISSIONS.'</a> ]';

    include('footer.php');
}

function ListArchive()
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    include('header.php');
    GraphicAdmin();

    OpenTable();
	echo '<h1>'._ARCHIVESUBS.'</h1>';
    CloseTable();

	$msg = pnGetStatusMsg();
	if ($msg != '') {
		OpenTable();
		echo $msg;
		CloseTable();
	}

    $column = &$pntable['queue_column'];
    $result =& $dbconn->Execute("SELECT {$column['qid']}, {$column['subject']},
                                {$column['timestamp']}, {$column['alanguage']}
                              FROM {$pntable['queue']}
                              WHERE {$column['arcd']}='1' ORDER BY {$column['timestamp']}");
	if ($dbconn->ErrorNo() !== 0) {
		$msg = $dbconn->ErrorMsg();
		include 'header.php';
		echo $msg;
		include 'footer.php';
		return;
	}

    while(list($qid,$subject,$timestamp,$alanguage) = $result->fields) {
    	$formatted_date = ml_ftime(_DATETIMELONG, $dbconn->UnixTimestamp($timestamp));

        echo '<table><tr><td valign="top">'."\n";
        echo '[ <a href="admin.php?module=AddStory&amp;op=DisplayStory&amp;qid='.pnVarPrepForDisplay($qid).'">'.pnVarPrepForDisplay($subject).'</a> ]['
		     .pnVarPrepForDisplay($alanguage).' ][ '.pnVarPrepForDisplay($formatted_date).' ] --';
        echo '</td><td>'."\n";
        echo '<form action="admin.php" method="post"><div>';
        echo '<input type="submit" value="'._DELETE.'" />';
        echo '<input type="hidden" name="module" value="AddStory" />';
        echo '<input type="hidden" name="op" value="DeleteStory" />';
        echo '<input type="hidden" name="qid" value="'.pnVarPrepForDisplay($qid).'" />';
        echo '<input type="hidden" name="authid" value="'.pnSecGenAuthKey().'" />';
        echo '</div></form>'."\n";
        echo '</td><td>'."\n";
        echo '<form action="admin.php" method="post"><div>';
        echo '<input type="submit" value="'._UNARCHIVE.'" />';
        echo '<input type="hidden" name="module" value="AddStory" />';
        echo '<input type="hidden" name="op" value="Unarchive" />';
        echo '<input type="hidden" name="qid" value="'.pnVarPrepForDisplay($qid).'" />';
        echo '</div></form>'."\n";
        echo '</td></tr></table>'."\n";
        $result->MoveNext();
    }

    include('footer.php');
}

function Unarchive($qid)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    include('header.php');
    GraphicAdmin();

    OpenTable();
	echo '<h2>'._ARCHIVESUBS.'</h2>';
    CloseTable();

    $column = &$pntable['queue_column'];
    $result =& $dbconn->Execute("UPDATE $pntable[queue]
                              SET $column[arcd]='0'
                              WHERE $column[qid]='".(int)pnVarPrepForStore($qid)."'");
	if ($dbconn->ErrorNo() !== 0) {
		$msg = $dbconn->ErrorMsg();
		include 'header.php';
		echo $msg;
		include 'footer.php';
		return;
	}

    echo '<p>'._UNARCHIVESUCCESS.'</p>';
    echo '<a href="admin.php?module=AddStory&amp;op=submissions">'._SUBMISSIONS.'</a>';

    include('footer.php');

}

function addstory_admin_main($var)
{
    //  changed to use pnVarCleanFromInput - skooter
    //  extract($var);
    list($op,
    	 $cat,
         $catid,
         $sid,
         $qid,
         $anid,
         $ok,
         $newcat,
         $title,
         $themeoverride
         ) = pnVarCleanFromInput('op',
         						 'cat',
         						 'catid',
    							 'sid',
         						 'qid',
         						 'anid',
    							 'ok',
    							 'newcat',
    							 'title',
    							 'themeoverride');

    if (!pnSecAuthAction(0, 'Stories::Story', '::', ACCESS_EDIT)) {
       include 'header.php';
       echo _STORIESADDNOAUTH;
       include 'footer.php';
    } else {
      switch($op) {

        case 'EditCategory':
            if(!isset($catid)) $catid='';
            EditCategory($catid);
            break;

        case 'DelCategory':
            if(!isset($cat)) $cat='';
            DelCategory($cat, $catid);
            break;

        case 'YesDelCategory':
            YesDelCategory($catid);
            break;

        case 'NoMoveCategory':
            if(!isset($newcat)) $newcat='';
            NoMoveCategory($catid, $newcat);
            break;

        case 'SaveEditCategory':
            SaveEditCategory($catid, $title, $themeoverride);
             break;

        case 'SelectCategory':
            if(!isset($cat)) $cat='';
            SelectCategory($cat);
            break;

        case 'AddCategory':
            AddCategory();
            break;

        case 'SaveCategory':
            SaveCategory($title, $themeoverride);
            break;

        case 'DisplayStory':
            displayStory();
            break;

        case 'PreviewAgain':
            previewStory();
            break;

        case 'PostStory':
            postStory();
            break;

        case 'EditStory':
            editStory();
            break;

        case 'RemoveStory':
            removeStory($sid, $ok);
            break;

        case 'DeleteStory':
            deleteStory($qid, $ok);
            break;

        case 'ChangeStory':
            changeStory();
            break;

        case 'ArchiveStory':
            ArchiveStory($qid);
            break;

        case 'Archive':
            Archive($qid);
            break;

        case 'ListArchive':
            ListArchive();
            break;

        case 'Unarchive':
            Unarchive($qid);
            break;

        case 'adminStory':
            adminStory();
            break;

        case 'PreviewAdminStory':
            previewAdminStory();
            break;

        case 'PostAdminStory':
            postAdminStory();
            break;

        case 'autoDelete':
            autodelete($anid, $ok);
            break;

        case 'autoEdit':
            autoEdit($anid);
            break;

        case 'autoSaveEdit':
            autoSaveEdit();
            break;

        case 'submissions':
            submissions();
            break;

        default:
            adminStory();
            break;
       }
   }
}

function unnltobr($text) {
    return (preg_replace('/(<br[ \/]*?>)/i', '', $text));
}

?>