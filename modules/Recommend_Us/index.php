<?php 
// $Id: index.php 16001 2005-03-22 11:05:16Z markwest $
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
// Filename: modules/Recommed_Us/index.php
// Original Author: Francisco Burzi
// Purpose: Recommend site/send articles to 'friends'
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_MODULE')) {
     die ("You can't access this file directly...");
}

$ModName = basename(dirname(__FILE__));

modules_get_language();

/* functions for recommending a story module */
function FriendSend($sid)
{
    include ('header.php');

    if(empty($sid) || !is_numeric($sid)) {
        echo _MODARGSERROR;
        include 'footer.php';
        exit;
    }

    if (!pnSecAuthAction(0, 'Recommend us::', '::', ACCESS_READ)) {
        echo _RECOMMENDUSNOAUTH;
        include 'footer.php';
        return;
    }

    if(pnUserLoggedIn()) {
		$uid = pnUserGetVar('uid');
		$uname = pnUserGetVar('uname');
    } else {
    	$uid = 1;
        $uname = pnConfigGetVar('anonymous');
    }

    $statusmsg = pnGetStatusMsg();
    if ($statusmsg != '') {
    	OpenTable();
    	echo '<div class="pn-statusmsg">'.$statusmsg.'</div>';
    	CloseTable();
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // grab the actual story from the database
    $column = &$pntable['stories_column'];
    $result =& $dbconn->Execute("SELECT $column[title],
                                     $column[topic],
                                     $column[cid],
                                     $column[aid]
                              FROM $pntable[stories] where $column[sid] = '".(int)pnVarPrepForStore($sid)."'");
    list($title, $topic, $cid, $aid) = $result->fields;
	
	// find out the cattitle
	if ($cid == 0) {
		// Default category
		$cattitle = _ARTICLES;
	} else {
		$catcolumn = &$pntable['stories_cat_column'];
		//$catquery = buildSimpleQuery('stories_cat', array('title'), "$catcolumn[catid] = $cid");
		$catquery = "SELECT $catcolumn[title]
					FROM $pntable[stories_cat]
					WHERE $catcolumn[catid] = '".(int)pnVarPrepForStore($cid)."'";
		$catresult =& $dbconn->Execute($catquery);
		list($cattitle) = $catresult->fields;
	}

	// find out the topictext
	$topicscolumn = &$pntable['topics_column'];
	//$topicquery = buildSimpleQuery('topics', array('topictext', 'topicname'), "$topicscolumn[topicid] = $topic");
	$topicquery = "SELECT $topicscolumn[topictext],
						$topicscolumn[topicname]
					FROM $pntable[topics]
					WHERE $topicscolumn[topicid] = '".(int)pnVarPrepForStore($topic)."'";
	$topicresult =& $dbconn->Execute($topicquery);
	list($topictext, $topicname) = $topicresult->fields;
	
    if (!pnSecAuthAction(0, 'Recommend us::', '::', ACCESS_READ) || 
        !pnSecAuthAction(0, 'Stories::Story', "$aid:$cattitle:$sid", ACCESS_READ) ||
        !pnSecAuthAction(0, 'Topics::Topic', "$topicname::$topic", ACCESS_READ) ) {
        echo _RECOMMENDUSNOAUTH;
        include 'footer.php';
        return;
    }

    OpenTable();
    echo '<h1>'._FRIEND.'</h1>'
		. '<p>' ._YOUSENDSTORY
		.' <strong>' . pnVarPrepForDisplay($title) 
		. ' ('.pnVarPrepHTMLDisplay($cattitle).'/'.pnVarPrepHTMLDisplay($topictext).')</strong> '
		._TOAFRIEND . '</p>'
		.'<form action="index.php" method="post"><div>'
		.'<input type="hidden" name="name" value="Recommend_Us" />'
		.'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
		.'<input type="hidden" name="sid" value="'.pnVarPrepForDisplay($sid).'" />';

	$yn = pnSessionGetVar('recommendus_yname');
	$ye = pnSessionGetVar('recommendus_ymail');
	if (empty($yn) && empty($ye)) {
		$yn = $ye = '';
		if (pnUserLoggedIn()) {
			$yn = pnUserGetVar('name');
			if ($yn == '') {
				$yn = pnUserGetVar('uname');    
			}
			$ye = pnUserGetVar('email');
		}
	}
	$fn = pnSessionGetVar('recommendus_fname');
	$fe = pnSessionGetVar('recommendus_fmail');
    echo '<label for="YOURNAME">'._FYOURNAME.'</label> '
	.'<input type="text" name="yname" value="'.pnVarPrepForDisplay($yn).'" id="YOURNAME" tabindex="0" /><br />'."\n"
    .'<label for="YOUREMAIL">'._FYOUREMAIL.'</label> '
	.'<input type="text" name="ymail" value="'.pnVarPrepForDisplay($ye).'" id="YOUREMAIL" tabindex="0" /><br />'."\n"
    .'<label for="FRIENDNAME">'._FFRIENDNAME.'</label> '
	.'<input type="text" name="fname" value="'.pnVarPrepForDisplay($fn).'" id="FRIENDNAME" tabindex="0" /><br />'."\n"
    .'<label for="FRIENDEMAIL">'._FFRIENDEMAIL.'</label> '
	.'<input type="text" name="fmail" value="'.pnVarPrepForDisplay($fe).'" id="FRIENDEMAIL" tabindex="0" /><br />'."\n"
    .'<input type="hidden" name="req" value="SendStory" />'."\n"
    .'<input type="submit" value="'._SEND.'" />'."\n"
    .'</div></form>'."\n";
    CloseTable();
    include ('footer.php');
}

function SendStory($sid, $yname, $ymail, $fname, $fmail)
{
    if (!pnSecAuthAction(0, 'Recommend us::', '::', ACCESS_READ)) {
		include 'header.php';
		echo _RECOMMENDUSNOAUTH;
        include 'footer.php';
        return;
    }

	pnSessionSetVar('recommendus_yname', $yname);
	pnSessionSetVar('recommendus_ymail', $ymail);
	pnSessionSetVar('recommendus_fname', $fname);
	pnSessionSetVar('recommendus_fmail', $fmail);

    // Security checks
    // 1) the name isn't too long
    if (strlen($fname)>25 || strlen($yname)>25) {
		pnSessionSetVar('errormsg', _NAMETOOLONG);
	    pnRedirect('index.php?name=Recommend_Us&req=FriendSend&sid='.$sid);
        return;
    }

    // 2) the fmail is valid
    $valid = pnVarValidate($fmail, 'email');
    if ($valid == false) {
		pnSessionSetVar('errormsg', _EMAILWRONG);
	    pnRedirect('index.php?name=Recommend_Us&req=FriendSend&sid='.$sid);
        return;
    }

    // 3) the ymail is valid
    $valid = pnVarValidate($ymail, 'email');
    if ($valid == false) {
		pnSessionSetVar('errormsg', _EMAILWRONG);
	    pnRedirect('index.php?name=Recommend_Us&req=FriendSend&sid='.$sid);
        return;
    }

    if (!pnSecConfirmAuthKey()) {
		pnSessionSetVar('errormsg', _BADAUTHKEY);
	    pnRedirect('index.php?name=Recommend_Us&req=FriendSend&sid='.$sid);
        return;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // grab the actual story from the database
    $column = &$pntable['stories_column'];
    $result =& $dbconn->Execute("SELECT $column[title],
    								 $column[time],
                                     $column[topic],
                                     $column[cid],
                                     $column[aid]
                              FROM $pntable[stories] 
							  WHERE $column[sid] = '".(int)pnVarPrepForStore($sid)."'");
    list($title, $time, $topic, $cid, $aid) = $result->fields;
	pnSessionSetVar('recommendus_title', $title);

	// find out the cattitle
	if ($cid == 0) {
		// Default category
		$cattitle = _ARTICLES;
	} else {
		$catcolumn = &$pntable['stories_cat_column'];
		//$catquery = buildSimpleQuery('stories_cat', array('title'), "$catcolumn[catid] = $cid");
		$catquery = "SELECT $catcolumn[title]
					FROM $pntable[stories_cat]
					WHERE $catcolumn[catid] = '".(int)pnVarPrepForStore($cid)."'";
		$catresult =& $dbconn->Execute($catquery);
		list($cattitle) = $catresult->fields;
	}

	// find out the topictext
	$topicscolumn = &$pntable['topics_column'];
	//$topicquery = buildSimpleQuery('topics', array('topictext', 'topicname'), "$topicscolumn[topicid] = $topic");
	$topicquery = "SELECT $topicscolumn[topictext],
						$topicscolumn[topicname]
					FROM $pntable[topics]
					WHERE $topicscolumn[topicid] = '".(int)pnVarPrepForStore($topic)."'";
	$topicresult =& $dbconn->Execute($topicquery);
	list($topictext, $topicname) = $topicresult->fields;
	
    if (!pnSecAuthAction(0, 'Recommend us::', '::', ACCESS_READ) || 
        !pnSecAuthAction(0, 'Stories::Story', "$aid:$cattitle:$sid", ACCESS_READ) ||
        !pnSecAuthAction(0, 'Topics::Topic', "$topicname::$topic", ACCESS_READ) ) {
        echo _RECOMMENDUSNOAUTH;
        include 'footer.php';
        return;
    }

    // convert time
	$formatted_time = ml_ftime(_DATETIMELONG, $dbconn->UnixTimestamp($time));

    $newlang = pnSessionGetVar('lang');
    $sitename = pnConfigGetVar('sitename');
    $subject = _INTERESTING." $sitename";
    $message = _HELLO." $fname\r\n"
    		   ._YOURFRIEND." $yname "._CONSIDERED."\r\n\n$title\n"
               ."($cattitle / $topictext)\r\n"
			   ._FDATE." $formatted_time\n\n"
               ._URL.": " . pnGetBaseURL()
               . "index.php?name=News&file=article&sid=$sid&newlang=$newlang\r\n\n"
               ._YOUCANREAD." $sitename\n" . pnGetBaseURL();
	// send the e-mail
	pnModAPIFunc('Mailer', 'user', 'sendmessage', 
	             array('toaddress' => $fmail, 'toname' => $fname,  'fromaddress' => $ymail, 
				       'fromname' => $yname, 'subject' => $subject, 'body' => $message));
    pnRedirect('index.php?name=Recommend_Us&req=StorySent');
	return;
}

function StorySent()
{
	$fname = pnSessionGetVar('recommendus_fname');
	$title = pnSessionGetVar('recommendus_title');
	pnSessionDelVar('recommendus_yname');
	pnSessionDelVar('recommendus_ymail');
	pnSessionDelVar('recommendus_fname');
	pnSessionDelVar('recommendus_fmail');

    include ('header.php');
    $title = urldecode($title);
    $fname = urldecode($fname);
    OpenTable();
    echo '<div style="text-align:center">'._FSTORY.' <strong>'.pnVarPrepForDisplay($title).'</strong> '
	     ._HASSENT.' '.pnVarPrepForDisplay($fname).'... '._THANKS.'</div>';
    CloseTable();
    include ('footer.php');
}

/* functions for main recommend us module */
function RecommendSite()
{
    include ('header.php');

    if (!pnSecAuthAction(0, 'Recommend us::', '::', ACCESS_READ)) {
	    OpenTable();
        echo _RECOMMENDUSNOAUTH;
        CloseTable();
        include 'footer.php';
        return;
    }
    $statusmsg = pnGetStatusMsg();
    if ($statusmsg != '') {
    	OpenTable();
    	echo '<div class="pn-statusmsg">'.$statusmsg.'</div>';
    	CloseTable();
    }

    OpenTable();
	echo '<h1>'._RECOMMEND.'</h1>';

    CloseTable();

    OpenTable();
    echo'<form action="index.php" method="post"><div>'
		.'<input type="hidden" name="name" value="Recommend_Us" />'
		.'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
		.'<input type="hidden" name="req" value="SendSite" />';

	$yn = pnSessionGetVar('recommendus_yname');
	$ye = pnSessionGetVar('recommendus_ymail');
	if (empty($yn) && empty($ye)) {
		$yn = $ye = '';
		if (pnUserLoggedIn()) {
			$yn = pnUserGetVar('name');
			if ($yn == '') {
				$yn = pnUserGetVar('uname');    
			}
			$ye = pnUserGetVar('email');
		}
	}
	$fn = pnSessionGetVar('recommendus_fname');
	$fe = pnSessionGetVar('recommendus_fmail');

    echo '
    <table>
      <tr>
		<td><label for="YOURNAME">'._FYOURNAME.'</label></td>
		<td><input type="text" name="yname" value="'.pnVarPrepForDisplay($yn).'" size="25" maxlength="25" id="YOURNAME" tabindex="0" /></td>
      </tr>
      <tr>
		<td><label for="YOUREMAIL">'._FYOUREMAIL.'</label></td>
		<td><input type="text" name="ymail" value="'.pnVarPrepForDisplay($ye).'" size="25" id="YOUREMAIL" tabindex="0" /></td>
      </tr>
      <tr>
		<td><label for="FRIENDNAME">'._FFRIENDNAME.'</label></td>
		<td><input type="text" name="fname" value="'.pnVarPrepForDisplay($fn).'" size="25" maxlength="25" id="FRIENDNAME" tabindex="0" /></td>
      </tr>
      <tr>
		<td><label for="FRIENDEMAIL">'._FFRIENDEMAIL.'</label></td>
		<td><input type="text" name="fmail" value="'.pnVarPrepForDisplay($fe).'" size="25" id="FRIENDEMAIL" tabindex="0" /></td>
      </tr>
      <tr>
		<td colspan="2"><input type="submit" value="'._SEND.'" /></td>
      </tr>
    </table>
	</div>
    </form>';
    CloseTable();
    include ('footer.php');
}

function SendSite($yname, $ymail, $fname, $fmail)
{
    if (!pnSecAuthAction(0, 'Recommend us::', '::', ACCESS_READ)) {
        include 'header.php';
        echo _RECOMMENDUSNOAUTH;
        include 'footer.php';
        return;
    }

	pnSessionSetVar('recommendus_yname', $yname);
	pnSessionSetVar('recommendus_ymail', $ymail);
	pnSessionSetVar('recommendus_fname', $fname);
	pnSessionSetVar('recommendus_fmail', $fmail);

	// Security checks
    // 1) the name isn't too long
    if (strlen($fname)>25 || strlen($yname)>25) {
		pnSessionSetVar('errormsg', _NAMETOOLONG);
	    pnRedirect('index.php?name=Recommend_Us');
        return;
    }

    // 2) the fmail is valid
    if (!pnVarValidate($fmail, 'email')) {
		pnSessionSetVar('errormsg', _EMAILWRONG);
	    pnRedirect('index.php?name=Recommend_Us');
        return;
    }

    // 3) the ymail is valid
    if (!pnVarValidate($ymail, 'email')) {
		pnSessionSetVar('errormsg', _EMAILWRONG);
	    pnRedirect('index.php?name=Recommend_Us');
        return;
    }

    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
	    pnRedirect('index.php?name=Recommend_Us');
        return;
    }

    $sitename = pnConfigGetVar('sitename');
    $slogan = pnConfigGetVar('slogan');
    $subject = _INTSITE.' '.$sitename;
    $message = _HELLO." $fname:\r\n"._YOURFRIEND." $yname "._OURSITE." $sitename "._INTSENT."\r\n\n"._FSITENAME." $sitename\n$slogan\n"._FSITEURL. pnGetBaseURL() . "\n";
	pnModAPIFunc('Mailer', 'user', 'sendmessage', 
	             array('toaddress' => $fmail, 'toname' => $fname,  'fromaddress' => $ymail, 
				       'fromname' => $yname, 'subject' => $subject, 'body' => $message));
    pnRedirect('index.php?name=Recommend_Us&req=SiteSent');
	return;
}

function SiteSent()
{
	$fname = pnSessionGetVar('recommendus_fname');
	pnSessionDelVar('recommendus_yname');
	pnSessionDelVar('recommendus_ymail');
	pnSessionDelVar('recommendus_fname');
	pnSessionDelVar('recommendus_fmail');

    include ('header.php');
    OpenTable();
    echo '<div style="text-align:center">'._FREFERENCE.' '.pnVarPrepForDisplay($fname).'...<br />'._THANKSREC.'</div>';
    CloseTable();
    include ('footer.php');
}

$req = pnVarCleanFromInput('req');

if(empty($req)) {
    $req = '';
}

switch($req) {

    case 'SendStory':
        list($sid, $yname, $ymail, $fname, $fmail) = pnVarCleanFromInput('sid', 'yname', 'ymail', 'fname', 'fmail');
        SendStory($sid, $yname, $ymail, $fname, $fmail);
        break;

    case 'StorySent':
        StorySent();
        break;

    case 'SendSite':
        list($yname, $ymail, $fname, $fmail) = pnVarCleanFromInput('yname', 'ymail', 'fname', 'fmail');
        SendSite($yname, $ymail, $fname, $fmail);
        break;

    case 'SiteSent':
        SiteSent();
        break;

    case 'FriendSend':
        $sid = pnVarCleanFromInput('sid');
        FriendSend($sid);
        break;

    default:
        RecommendSite();
        break;

}

?>