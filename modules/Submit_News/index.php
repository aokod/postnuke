<?php
// $Id: index.php 16879 2005-10-21 13:26:02Z landseer $
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
// Filename: modules/Submit_News/index.php
// Original Author of file: Francisco Burzi
// Purpose of file: Submit news to site
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_MODULE')) {
    die ("You can't access this file directly...");
}

$ModName = basename(dirname( __FILE__ ));

modules_get_language();

function defaultDisplay()
{
    $ModName = $GLOBALS['ModName'];

	$topic = pnVarCleanFromInput('topic');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $currentlang = pnUserGetLang();

    include ('header.php');
    if (!pnSecAuthAction(0, 'Submit news::', '::', ACCESS_COMMENT)) {
        echo _NOTALLOWED;
        include 'footer.php';
        exit;
    }

    OpenTable();
    echo '<h1>'._SUBMITNEWS.'</h1>'._SUBMITADVICE;
    CloseTable();

    OpenTable();

    echo '<form name="news" id="news" action="index.php?name=Submit_News" method="post"><div>'
	    .'<strong>'._YOURNAME.':</strong> ';
    if (pnUserLoggedIn()) {
        echo '<a href="user.php">' . pnUserGetVar('uname') . '</a>';
    } else {
        echo pnConfigGetVar('anonymous');
    }
    echo '<br />'
        .'<strong><label for="subtitle">'._SUBTITLE.'</label></strong> '
        .'('._BEDESCRIPTIVE.')<br />'
        .'<input type="text" name="subject" size="50" maxlength="80" id="subtitle" tabindex="0" /> '._REQUIRED.'<br />('._BADTITLES.')'
        .'<br />'
        .'<strong><label for="topic">'._TOPIC.'</label>:</strong> <select name="topic" id="topic">';
    $column = &$pntable['topics_column'];
    $toplist =& $dbconn->Execute("SELECT $column[topicid], $column[topictext], $column[topicname]
                               FROM $pntable[topics]
                               ORDER BY $column[topictext]");
    echo '<option value="">'._SELECTTOPIC."</option>\n";

    while(list($topicid, $topics, $topicname) = $toplist->fields) {
        if (pnSecAuthAction(0, 'Topics::Topic', "$topicname::$topicid", ACCESS_COMMENT)) {
            $sel = "";
            if ($topicid==$topic) {
                $sel = 'selected="selected"';
            }
            echo '<option '.$sel.' value="'.pnVarPrepForStore($topicid).'">'.pnVarPrepForDisplay($topics).'</option>'."\n";
        }
        $toplist->MoveNext();
    }
    echo '</select>';

	if (pnConfigGetVar('multilingual')) {
	    echo '<br /><strong><label for="language">'._LANGUAGE.'</label>: </strong>'; // ML added dropdown , currentlang is pre-selected
	    lang_dropdown();
	} else {
		echo '<input type="hidden" name="alanguage" value="" />';
	}

    $bbcode = array('', '');
    if(pnModIsHooked('pn_bbcode', 'Submit_News') && pnModIsHooked('pn_bbcode', 'AddStory') && pnModIsHooked('pn_bbcode', 'News')) {
        $bbcode[0] = pnModFunc('pn_bbcode', 'user', 'bbcodes', array('textfieldid' => 'articletext'));
        $bbcode[1] = pnModFunc('pn_bbcode', 'user', 'bbcodes', array('textfieldid' => 'extendedtext'));
    }
    $bbsmile = array('', '');
    if(pnModIsHooked('pn_bbsmile', 'Submit_News') && pnModIsHooked('pn_bbsmile', 'AddStory') && pnModIsHooked('pn_bbsmile', 'News')) {
        pnModAPILoad('pn_bbsmile', 'user');
        $bbsmile[0] = pnModFunc('pn_bbsmile', 'user', 'bbsmiles', array('textfieldid' => 'articletext'));
        $bbsmile[1] = pnModFunc('pn_bbsmile', 'user', 'bbsmiles', array('textfieldid' => 'extendedtext'));
    }
    echo '<br /><strong><label for="articletext">'._ARTICLETEXT.'</label></strong> '
        .'('._HTMLISFINE.')<br />'
        .'<textarea cols="80" rows="10" name="storytext" id="articletext"></textarea> '._REQUIRED.'<br />'
        . $bbcode[0]
        . $bbsmile[0]
        .'<br /><strong><label for="extendedtext">'._EXTENDEDTEXT.'</label></strong>'
        .'<br /><textarea cols="80" rows="10" name="bodytext" id="extendedtext"></textarea><br />'
        . $bbcode[1]
        . $bbsmile[1];

	// show pagebreak instructions
	echo _PAGEBREAK.'<br />';
    // Show allowable HTML
    echo _ALLOWEDHTML.'<br />';
    $AllowableHTML = pnConfigGetVar('AllowableHTML');
    while (list($key, $access, ) = each($AllowableHTML)) {
        if ($access > 0) echo " &lt;".$key."&gt;";
    }
    echo '<br />('._AREYOUSURE.')<br /><input type="submit" name="request_preview" value="'._PREVIEW.'" />';
    echo '</div></form>';
    CloseTable();
    include ('footer.php');
}

function PreviewStory()
{
    list($name,
         $address,
         $subject,
         $storytext,
         $topic,
         $alanguage,
         $bodytext) = pnVarCleanFromInput('name',
                                          'address',
                                          'subject',
                                          'storytext',
                                          'topic',
                                          'alanguage',
                                          'bodytext');

    $subject    = pnVarCensor($subject);
    $storytext  = pnVarCensor($storytext);
    $bodytext   = pnVarCensor($bodytext);

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $ModName = $GLOBALS['ModName'];


    include ('header.php');

    $tipath = pnConfigGetVar('tipath');
    $anonymous = pnConfigGetVar('anonymous');

    if (!pnSecAuthAction(0, 'Submit news::', '::', ACCESS_COMMENT)) {
        echo _NOTALLOWED;
        include 'footer.php';
        exit;
    }

    if($subject == '' || $storytext == '') {
        OpenTable2();
        echo '<strong>'._MPROBLEM.'</strong> '._NOSUBJECT.'<br />';
        echo '<div style="text-align:center">'._GOBACK.'</div><br />';
        CloseTable2();
        include('footer.php');
        exit;
    }

    OpenTable();
    echo '<h1>'._NEWSUBPREVIEW.'</h1>';
    CloseTable();

    OpenTable();
    echo '<div style="text-align:center"><em>'._STORYLOOK.'</em></div><br />';
    echo '<table width="70%" bgcolor="'.$GLOBALS['bgcolor2'].'" cellpadding="0" cellspacing="1" border="0" align="center"><tr><td>'
    .'<table width="100%" bgcolor="'.$GLOBALS['bgcolor1'].'" cellpadding="8" cellspacing="1" border="0"><tr><td>';
    if ($topic=='') {
        $topicimage='AllTopics.gif';
        $warning = '<br /><strong>'._SELECTTOPIC.'</strong><br />';
		$topictext='';
    } else {
        $warning = '';
        $column = &$pntable['topics_column'];
        $result =& $dbconn->Execute("SELECT $column[topicimage],
									       $column[topictext]
                                  FROM $pntable[topics]
                                  WHERE $column[topicid]='".pnVarPrepForStore($topic)."'");
        list($topicimage,$topictext) = $result->fields;
    }
    echo "<img src=\"$tipath$topicimage\" align=\"right\" alt=\"$topictext\" />";

    story_preview($subject, $storytext, $bodytext);

    echo '<div style="text-align:center">'
    .'<h2>'.pnVarPrepHTMLDisplay($warning).'</h2>'
    .'</div>'
    .'</td></tr></table></td></tr></table>'
    .'<br /><div style="text-align:center"><span class="pn-sub">'._CHECKSTORY.'</span></div>';
    CloseTable();

    OpenTable();
    echo '<form action="index.php?name=Submit_News" method="post"><div>'
	    .'<input type="hidden" name="authid" value="'.pnSecGenAuthKey().'" />'
	    .'<strong>'._YOURNAME.':</strong> ';
    if (pnUserLoggedIn()) {
        echo '<a href="user.php">' . pnUserGetVar('uname') . '</a> [ <a href="user.php?module=User&amp;op=logout">'._LOGOUT.'</a> ]';
    } else {
        echo pnVarPrepForDisplay($anonymous);
    }
    echo '<br /><strong>'._SUBTITLE.':</strong><br />'
    .'<input type="text" name="subject" size="50" maxlength="80" value="' . pnVarPrepForDisplay($subject) . '" /> '
    ._REQUIRED.'<br /><strong>'._TOPIC.': </strong><select name="topic">';
    $column = &$pntable['topics_column'];
    $toplist =& $dbconn->Execute("SELECT $column[topicid], $column[topictext], $column[topicname]
                               FROM $pntable[topics]
                               ORDER BY $column[topictext]");
    echo '<option value="">'._SELECTTOPIC."</option>\n";
    while(list($topicid, $topics, $topicname) = $toplist->fields) {
        if (pnSecAuthAction(0,'Topics::Topic',"$topicname::$topicid", ACCESS_COMMENT)) {
            if ($topicid == $topic) {
                $sel='selected="selected"';
                echo "<option value=\"$topicid\" $sel>".pnVarPrepForDisplay($topics)."</option>\n";
            } else {
                echo "<option value=\"$topicid\">".pnVarPrepForDisplay($topics)."</option>\n";
            }
            $sel='';
        }
        $toplist->MoveNext();
    }
    echo '</select>';
	if (pnConfigGetVar('multilingual')) {
	    echo '<br /><strong><label for="language">'._LANGUAGE.'</label>: </strong>'; // ML added dropdown , currentlang is pre-selected
	    lang_dropdown();
	} else {
		echo '<input type="hidden" name="language" value="" />';
	}

    $bbcode = array('', '');
    if(pnModIsHooked('pn_bbcode', 'Submit_News') && pnModIsHooked('pn_bbcode', 'AddStory') && pnModIsHooked('pn_bbcode', 'News')) {
        $bbcode[0] = pnModFunc('pn_bbcode', 'user', 'bbcodes', array('textfieldid' => 'articletext'));
        $bbcode[1] = pnModFunc('pn_bbcode', 'user', 'bbcodes', array('textfieldid' => 'extendedtext'));
    }
    $bbsmile = array('', '');
    if(pnModIsHooked('pn_bbsmile', 'Submit_News') && pnModIsHooked('pn_bbsmile', 'AddStory') && pnModIsHooked('pn_bbsmile', 'News')) {
        pnModAPILoad('pn_bbsmile', 'user');
        $bbsmile[0] = pnModFunc('pn_bbsmile', 'user', 'bbsmiles', array('textfieldid' => 'articletext'));
        $bbsmile[1] = pnModFunc('pn_bbsmile', 'user', 'bbsmiles', array('textfieldid' => 'extendedtext'));
    }
    echo"<br /><strong>"._ARTICLETEXT.'</strong> '
        .'('._HTMLISFINE.')<br />'
        .'<textarea cols="80" rows="10" name="storytext" id="articletext">' . pnVarPrepForDisplay($storytext) . '</textarea> '._REQUIRED.'<br />'
        . $bbcode[0]
        . $bbsmile[0]
        .'<br /><strong>'._EXTENDEDTEXT.'</strong>'
        .'<br /><textarea cols="80" rows="10" name="bodytext" id="extendedtext">' . pnVarPrepForDisplay($bodytext) . '</textarea>'
        . $bbcode[1]
        . $bbsmile[1]
        .'<br />('._AREYOUSURE.')<br />'
        .'<input type="submit" name="request_preview" value="'._PREVIEW.'"> <input type="submit" name="request_ok" value="'._OK.'" />'
        .'</div></form>';
    CloseTable();

    include 'footer.php';
}

function submitStory()
{
    list($name,
         $subject,
         $storytext,
         $topic,
         $alanguage,
         $bodytext) = pnVarCleanFromInput('name',
                                          'subject',
                                          'storytext',
                                          'topic',
                                          'alanguage',
                                          'bodytext');

    $ModName = $GLOBALS['ModName'];

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!pnSecAuthAction(0, 'Submit news::', '::', ACCESS_COMMENT)) {
        include ('header.php');
        echo _NOTALLOWED;
        include 'footer.php';
        exit;
    }

    if (empty($subject)) {
        include 'header.php';
        echo _STORYNEEDSTITLE;
        include 'footer.php';
        exit;
    }

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    if (pnUserLoggedIn()) {
        $uid = pnUserGetVar('uid');
        $name = pnUserGetVar('uname');
    } else {
        $uid = 1;
		$name = pnConfigGetVar('anonymous');
    }

    $column = &$pntable['queue_column'];
    $newid = $dbconn->GenId($pntable['queue']);
    $result =& $dbconn->Execute("INSERT INTO $pntable[queue] (
                                  $column[qid],
                                  $column[uid],
                                  $column[arcd],
                                  $column[uname],
                                  $column[subject],
                                  $column[story],
                                  $column[timestamp],
                                  $column[topic],
                                  $column[alanguage],
                                  $column[bodytext])
                                VALUES (" . pnVarPrepForStore($newid). ",
                                        '" . pnVarPrepForStore($uid) . "',
                                        '0',
                                        '" . pnVarPrepForStore($name) . "',
                                        '" . pnVarPrepForStore($subject) . "',
                                        '" . pnVarPrepForStore($storytext) . "',
                                        now(),
                                        '" . pnVarPrepForStore($topic) . "',
                                        '" . pnVarPrepForStore($alanguage) . "',
                                        '" . pnVarPrepForStore($bodytext) . "')");

    if($dbconn->ErrorNo()<>0) {
        echo $dbconn->ErrorNo(). ": ".$dbconn->ErrorMsg(). '<br />';
        exit();
    }
    if(pnConfigGetVar('notify')) {
		pnModAPIFunc('Mailer', 'user', 'sendmessage',
					 array('toaddress' => pnConfigGetVar('notify_email'),
					       'fromname' => pnConfigGetVar('notify_from'),
						   'subject' => str_replace('www.','',$_SERVER['SERVER_NAME']).' - '.pnConfigGetVar('notify_subject'),
						   'body' => pnConfigGetVar('notify_message').': http://'.$_SERVER['SERVER_NAME']));
    }
    include 'header.php';

    OpenTable();
    $column = &$pntable['queue_column'];
    $result =& $dbconn->Execute("SELECT count(*) FROM $pntable[queue] WHERE $column[arcd]='0'");
    list($waiting) = $result->fields;
    echo '<h2>'._SUBSENT.'</h2><br />'
    ._THANKSSUB.'<br />'
    ._SUBTEXT
    .'<br />'._WEHAVESUB." $waiting "._WAITING;
    CloseTable();
    include ('footer.php');
}

/**
 * Preview function for submitted stories.
 */
function story_preview($title, $hometext, $bodytext="", $notes="") {
    list($hometext,
         $bodytext) = pnModCallHooks('item',
                                  'transform',
                                  '',
                                  array($hometext,
                                        $bodytext));

    echo '<h2>' . pnVarPrepForDisplay($title) . '</h2>' . pnVarPrepHTMLDisplay(nl2br($hometext));
    if ($bodytext != '') {
        echo '<br />' . pnVarPrepHTMLDisplay(nl2br($bodytext));
    }
    if ($notes != '') {
        echo '<br /><strong>'._NOTE.'</strong> <em>' . pnVarPrepHTMLDisplay(nl2br($notes)) . '</em>';
    }
}

list ($request_preview, $request_ok) = pnVarCleanFromInput('request_preview', 'request_ok');

$req = '';
if ($request_preview) $req = 'PREVIEW';
elseif ($request_ok)  $req = 'OK';

switch($req) {

    case 'PREVIEW':
        PreviewStory();
        break;

    case 'OK':
        SubmitStory();
        break;

    default:
        defaultDisplay();
        break;
}
?>