<?php
// File: $Id: user.php 16146 2005-04-25 09:59:26Z chestnut $
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
// Original Author of this file:
// Purpose of this file: mail a new password if forgotten
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_MODULE')) {
  die ('You can\'t access this file directly...');
}

$ModName = basename(dirname( __FILE__ ));

modules_get_language();

function lostpassword_user_lostpassscreen()
{

    if (pnUserLoggedIn()) {
        pnRedirect('user.php');
    }

    include 'header.php';

    $statusmsg = pnGetStatusMsg();
    if ($statusmsg != '') {
      OpenTable();
      echo '<div class="pn-statusmsg">'.$statusmsg.'</div>';
      CloseTable();
    }

    OpenTable();
    echo '<h1>'._PASSWORDLOST.'</h1>'
        .'<p>'._NOPROBLEM.'</p>'."\n"
        .'<form action="user.php" method="post"><div>'."\n"
        .'<table border="0" cellspacing="0" cellpadding="5">'."\n"
        .'<tr>'."\n"
        .'<td><label for="uname_lost_password">'._NICKNAME.'</label>: </td>'."\n"
    .'<td><input type="text" name="uname" id="uname_lost_password" size="26" maxlength="25" tabindex="0" /></td>'."\n"
    .'</tr>'."\n"
        .'<tr>'."\n"
    .'<td><label for="email_lost_password">'._EMAIL.'</label>: </td>'."\n"
    .'<td><input type="text" name="email" id="email_lost_password" size="60" maxlength="60" tabindex="0" /></td>'."\n"
        .'</tr>'."\n"
        .'<tr><td><label for="code_lost_password">'._CONFIRMATIONCODE.'</label>: </td>'."\n"
    .'<td><input type="text" name="code" id="code_lost_password" size="5" maxlength="6" tabindex="0" /></td>'."\n"
    .'</tr>'."\n"
    .'</table>'."\n"
        .'<input type="hidden" name="op" value="mailpasswd" />'."\n"
        .'<input type="hidden" name="module" value="LostPassword" />'."\n"
        .'<input type="submit" value="'._SENDPASSWORD.'" />'."\n"
        .'</div></form>'."\n";
    CloseTable();

    include 'footer.php';
}

function lostpassword_user_mailpasswd()
{
    list($uname,
         $email,
         $code)= pnVarCleanFromInput('uname',
                                     'email',
                                     'code');
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $sitename = pnConfigGetVar('sitename');
    $system = pnConfigGetVar('system');
    $adminmail = pnConfigGetVar('adminmail');

    $column = &$pntable['users_column'];
    $wheres = array();
    if (!empty($email)) {
        $wheres[] = "$column[email] = '".pnVarPrepForStore($email)."'";
        $who = $email;
    }
    if (!empty($uname)) {
        $wheres[] = "$column[uname] = '".pnVarPrepForStore($uname)."'";
        $who = $uname;
    }
    $where = join('AND ', $wheres);
    $result =& $dbconn->Execute("SELECT $column[uname],
                                       $column[email],
                                       $column[pass]
                                FROM $pntable[users]
                                WHERE $where");
    if (($dbconn->ErrorNo() != 0) || ($result->numRows() == 0)) {
    pnSessionSetVar('errormsg', _SORRYNOUSERINFO);
    pnRedirect('user.php?op=lostpassscreen&module=LostPassword');
    return;
    } else {
        $host_name = pnServerGetVar("REMOTE_ADDR");
        list($uname, $email, $pass) = $result->fields;
        $areyou = substr($pass, 0, 5);
        if ($areyou == $code) {
            $newpass=makepass();
            $message = _USERACCOUNT." $uname "._AT." $sitename "._HASTHISEMAIL."  "._AWEBUSERFROM." $host_name "._HASREQUESTED."\n\n"._YOURNEWPASSWORD." $newpass\n\n "._YOUCANCHANGE . ' ' . pnGetBaseURL() . "user.php\n\n"._IFYOUDIDNOTASK."";
            $subject = _USERPASSWORD4." $uname";
      // send the e-mail
      pnModAPIFunc('Mailer', 'user', 'sendmessage', array('toaddress' => $email, 'subject' => $subject, 'body' => $message));
          // Next step: add the new password to the database
            $cryptpass = md5($newpass);

            $column = &$pntable['users_column'];
            $query = "UPDATE $pntable[users] SET $column[pass]='".pnVarPrepForStore($cryptpass)."' WHERE $column[uname]='".pnVarPrepForStore($uname)."'";
            $result =& $dbconn->Execute($query);

            if($dbconn->ErrorNo()<>0) {
        pnSessionSetVar('errormsg', _UPDATEFAILED);
        pnRedirect('user.php?op=lostpassscreen&module=LostPassword');
        return true;
            }
      pnSessionSetVar('statusmsg', _PASSWORD4.' '.$uname.' '._MAILED);
      pnRedirect('user.php?op=lostpassscreen&module=LostPassword');
      return;
        // If no Code, send it
        } else {
            $host_name = pnServerGetVar("REMOTE_ADDR");
            $areyou = substr($pass, 0, 5);
            $message = _USERACCOUNT.' '.$uname.' '._AT.' '.$sitename.' '._HASTHISEMAIL.' '._AWEBUSERFROM.' '.$host_name.
                ' '._CODEREQUESTED."\n\n"._YOURCODEIS.' '.$areyou."\n\n"._WITHTHISCODE. ' ' .pnGetBaseURL() .
            'user.php?op=lostpassscreen&module=LostPassword'."\n"._IFYOUDIDNOTASK2;
            $subject = _CODEFOR." $uname";
      // send the e-mail
      pnModAPIFunc('Mailer', 'user', 'sendmessage', array('toaddress' => $email, 'subject' => $subject, 'body' => $message));

      pnSessionSetVar('statusmsg', _CODEFOR.' '.$who.' '._MAILED);
      pnRedirect('user.php?op=lostpassscreen&module=LostPassword');
      return;
        }
    }
  pnRedirect('user.php?op=lostpassscreen&module=LostPassword');
  return true;

}
?>