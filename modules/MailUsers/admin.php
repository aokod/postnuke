<?PHP
// File: $Id: admin.php 19348 2006-07-03 10:09:23Z markwest $
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
// Original Author of file:  Christopher Thorjussen <joffer@online.no>
// Purpose of file:
// PHP-Nuke MailUsers Module for PHP-Nuke v5.0BETA
// Copyright (c) 2001 by Christopher Thorjussen (joffer@online.no)
// http://www.nukemodules.com
// Nuke MailUsers is a hack of the email_user script in
// PHP-Nuke AddOn v5.01
// Copyright (c)2001 Richard Tirtadji (rtirtadji@hotmail.com)
// URL: http://www.nukeaddon.com
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_MODULE')) {
    die ('Access Denied');
}

modules_get_language();
modules_get_manual();

function MailUser($var)
{
    pnModDBInfoLoad('Users');
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    include 'header.php';

    if (!(pnSecAuthAction(0, 'MailUsers::', '::', ACCESS_ADMIN))) {
        echo _MAILUSERSNOAUTH;
        include 'footer.php';
        return;
    }

    GraphicAdmin();

    $statusmsg = pnGetStatusMsg();
    if ($statusmsg != '') {
    	OpenTable();
    	echo '<div class="pn-statusmsg">'.$statusmsg.'</div>';
    	CloseTable();
    }

    OpenTable();

    echo '<h1>'._NM_MAILUSER.'</h1>'
         .'<form action="admin.php" method="post"><div>'
         .'<input type="hidden" name="module" value="MailUsers" />'
         .'<input type="hidden" name="op" value="send" />'
         .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
         .'<table border="0">'
         .'<tr><td><strong>'._NM_USERNAME.'</strong></td>'
         .'<td><select name="username">'."\n";
    $column = &$pntable['users_column'];
    $sql = "SELECT $column[uid], $column[uname]
            FROM $pntable[users]
            WHERE $column[email] IS NOT NULL
            ORDER BY $column[uname]";
    $result = $dbconn->Execute($sql);

    $tmp1 = 0;
    while(list($uid, $uname) = $result->fields) {
        $result->MoveNext();
      	if ($tmp1 == 0) {
        	echo '<option value="'.pnVarPrepForDisplay($uid).'">'._NM_CHOOSEUSER.'</option>';
      	}
      	$anonymous = pnConfigGetVar('anonymous');
      	if ($uname == $anonymous) {
			// echo "<option value=\"$uid\">"._NM_CHOOSEUSER."</option>";
	  		// $anonid = $uid;
      	} else {
        	echo '<option value="'.pnVarPrepForDisplay($uid).'">'.pnVarPrepForDisplay($uname).'</option>'."\n";
      	}
    	$tmp1 = $tmp1 + 1;
    }
    echo '</select></td></tr>
    <tr><td></td><td><input type="checkbox" name="all" value="1" />'._NM_MAILALLUSERS.'</td></tr>
    <tr><td><strong>'._NM_FROM.'</strong></td><td><input type="text" size="28" name="fromname" /></td></tr>
    <tr><td><strong>'._NM_REPLYTOADDRESS.'</strong></td><td><input type="text" size="28" name="from" /></td></tr>
    <tr><td><strong>'._NM_SUBJECT.'</strong></td><td><input type="text" size="28" name="subject" /></td></tr>
    <tr><td><strong>'._NM_MESSAGE.'</strong></td><td><textarea cols="80" rows="10" name="message"></textarea></td></tr>
    <tr><td>&nbsp;</td><td><input type="submit" value="'._NM_SEND_MAIL.'" />&nbsp;&nbsp; <strong>'._NM_REMEMBER.'</strong></td></tr>
    </table></div></form>';
    CloseTable();

    include 'footer.php';
}

function send_email_to_user($var)
{
    if (!(pnSecAuthAction(0, 'MailUsers::', '::', ACCESS_ADMIN))) {
        include 'header.php';
        echo _MAILUSERSNOAUTH;
        include 'footer.php';
        return;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

	//use pnVarCleanFromInput which is more reliable with scripting security issues. - Skooter
	list($username,
		 $all,
		 $fromname,
		 $from,
		 $subject,
		 $message) = pnVarCleanFromInput('username',
		 								 'all',
		 								 'fromname',
		 								 'from',
		 								 'subject',
		 								 'message');

    //$message = stripslashes($message);
    //$subject = stripslashes($subject);

    if (($username== 0) and (!$all)) {
    	pnSessionSetVar('errormsg', _NM_ERROR1);
		pnRedirect('admin.php?module=MailUsers&op=main');
    } elseif ($fromname == '') {
    	pnSessionSetVar('errormsg', _NM_ERROR2);
		pnRedirect('admin.php?module=MailUsers&op=main');
    } elseif ($from == '') {
    	pnSessionSetVar('errormsg', _NM_ERROR3);
		pnRedirect('admin.php?module=MailUsers&op=main');
    } elseif ($subject == '') {
    	pnSessionSetVar('errormsg', _NM_ERROR4);
		pnRedirect('admin.php?module=MailUsers&op=main');
    } elseif ($message == '') {
    	pnSessionSetVar('errormsg', _NM_ERROR5);
		pnRedirect('admin.php?module=MailUsers&op=main');
    }

	//  moved security key check to after audits since back button from error page gave auth error. - Skooter
	if (!pnSecConfirmAuthKey()) {
		include 'header.php';
		echo _BADAUTHKEY;
		include 'footer.php';
		exit;
	}

	if($all) {
		$column = &$pntable['users_column'];
		$result =& $dbconn->Execute("SELECT DISTINCT $column[email], $column[uname] FROM $pntable[users] WHERE $column[email] IS NOT NULL");
	} else {
		$column = &$pntable['users_column'];
		$result =& $dbconn->Execute("SELECT $column[email], $column[uname]
							  FROM $pntable[users]
							  WHERE $column[uid]='".pnVarPrepForStore($username)."'");
	}

	if($dbconn->ErrorNo()<>0) {
    	pnSessionSetVar('errormsg', $dbconn->ErrorNo(). ': '.$dbconn->ErrorMsg());
		pnRedirect('admin.php?module=MailUsers&op=main');
	}

	//added logic to email in batches of 100 users to prevent problems with large headers - Skooter
	$email = array();
	$usercount = 0;

	// load the mailer module API
	if (!pnModAPILoad('Mailer', 'user')) {
		include 'header.php';
		echo _BADAUTHKEY;
		include 'footer.php';
		exit;
	}

	if($all) {
		while(list($useremail, $username) = $result->fields) {
			$result->MoveNext();
			if (!empty($useremail)) {
				$email[] = array('address' => $useremail, 'name' => $username);
				$usercount = $usercount + 1;
			}
			//If we've processed more than 100 users send email and reset counters - Skooter
			if ($usercount > 100){
				$status = pnModAPIFunc('Mailer', 'user', 'sendmessage',
				          array('toaddress' => $from, 'toname' => $fromname, 'fromaddress' => $from, 'fromname' => $fromname,
				               'bcc' => $email, 'subject' => $subject, 'body' => $message));
				if (!$status) {
					pnSessionSetVar('errormsg', $status);
				}
				$email = array();
				$usercount = 0;
			}
		}
		//Leaving this pnmail call here to catch the last set of users if counter is > 0 - Skooter
		// An email to all goes out To: the user sending the email,
		// and Bcc: all of the actual recipients
		if ($usercount >0){
			$status = pnModAPIFunc('Mailer', 'user', 'sendmessage',
			          array('toaddress' => $from, 'toname' => $fromname, 'fromaddress' => $from, 'fromname' => $fromname,
			                'bcc' => $email, 'subject' => $subject, 'body' => $message));
			if (!$status !== true) {
				pnSessionSetVar('errormsg', $status);
			}
		}
	} else {
		list($useremail, $username) = $result->fields;
		$status = pnModAPIFunc('Mailer', 'user', 'sendmessage',
		          array('toaddress' => $useremail, 'toname' => $username, 'fromaddress' => $from, 'fromname' => $fromname,
		                'subject' => $subject, 'body' => $message));
		if (!$status !== true) {
			pnSessionSetVar('errormsg', $status);
		}
	}

	pnSessionSetVar('statusmsg', _NM_MAILSENT);
	pnRedirect('admin.php?module=MailUsers&op=main');
	return;
}

function mailusers_admin_main($var)
{
   $op = pnVarCleanFromInput('op');
   extract($var);

   if (!(pnSecAuthAction(0, 'MailUsers::', '::', ACCESS_ADMIN))) {
       include 'header.php';
       echo _MAILUSERSNOAUTH;
       include 'footer.php';
   } else {
        switch ($op) {
	        case 'send':
	             send_email_to_user($var);
	             break;
	        default:
	            MailUser($var);
	            break;
       }
   }
}

?>