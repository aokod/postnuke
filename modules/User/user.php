<?php
// File: $Id: user.php 19273 2006-06-22 19:05:48Z markwest $
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

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_MODULE')) {
  die ('You can\'t access this file directly...');
}

$ModName = basename(dirname(__FILE__));
modules_get_language();

function user_user_userinfo()
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $uname = pnVarCleanFromInput('uname');
    include 'header.php';

    // some input checking
	  if (!pnSecAuthAction(0, 'UserInfo::', '::', ACCESS_READ)) {
        echo _PERMISSIONSNOAUTH;
        include 'footer.php';
        exit;
  	}

    if ( empty($uname) || stristr($uname,'&') || preg_match("/[[:space:]]/", $uname) || strip_tags($uname) != $uname) {
        echo _MODARGSERROR;
        include 'footer.php';
        exit;
    }
    // End of check

    $column = &$pntable['users_column'];
    $sql = "SELECT $column[femail] AS femail,
          $column[url] AS url,
          $column[bio] AS bio,
          $column[user_avatar] AS user_avatar,
          $column[user_icq] AS user_icq,
          $column[user_aim] AS user_aim,
          $column[user_yim] AS user_yim,
          $column[user_msnm] AS user_msnm,
          $column[user_from] AS user_from,
          $column[user_occ] AS user_occ,
          $column[user_intrest] AS user_intrest,
          $column[user_sig] AS user_sig,
          $column[uid] AS pn_uid,
          $column[pass] AS pass FROM $pntable[users] WHERE $column[uname]='" . pnVarPrepForStore($uname) . "'";
    $result =& $dbconn->Execute($sql);
    $userinfo = $result->GetRowAssoc(false);

    OpenTable();

    echo '<h2>' . pnVarPrepForDisplay($uname) . '</h2>';
    if ((!$result->EOF) && ($userinfo['url'] || $userinfo['femail'] || $userinfo['bio'] || $userinfo['user_avatar'] || $userinfo['user_icq'] || $userinfo['user_aim'] || $userinfo['user_yim'] || $userinfo['user_msnm'] || $userinfo['user_from'] || $userinfo['user_occ'] || $userinfo['user_intrest'] || $userinfo['user_sig'] || $userinfo['pn_uid'])) {
        echo '<div style="text-align:center">';
        $userinfo['user_sig'] = nl2br($userinfo['user_sig']);
        if ($userinfo['user_avatar']) {
            echo '<img src="images/avatar/'.pnVarPrepForDisplay($userinfo['user_avatar']).'" alt="" /><br />'."\n";
        }
    echo _REGISTEREDUSER." ".pnVarPrepForDisplay($userinfo['pn_uid']).'<br />'."\n";
        if ($userinfo['url']) {
            echo _MYHOMEPAGE . ' <a href="'.pnVarPrepForDisplay($userinfo['url']).'">' . pnVarPrepForDisplay($userinfo['url']) . '</a><br />'."\n";
        }
        if ($userinfo['femail']) {
            echo _MYEMAIL . ' <a href="mailto:'.pnVarPrepForDisplay($userinfo['femail']).'">' . pnVarPrepForDisplay($userinfo['femail']) . '</a><br />'."\n";
        }
        if ($userinfo['user_icq']) {
            echo _ICQ . ': ' . pnVarPrepForDisplay($userinfo['user_icq']) . '<br />'."\n";
        }
        if ($userinfo['user_aim']) {
            echo _AIM . ': ' . pnVarPrepForDisplay($userinfo['user_aim']) . '<br />'."\n";
        }
        if ($userinfo['user_yim']) {
            echo _YIM . ': ' . pnVarPrepForDisplay($userinfo['user_yim']) . '<br />'."\n";
        }
        if ($userinfo['user_msnm']) {
            echo _MSNM . ': ' . pnVarPrepForDisplay($userinfo['user_msnm']) . '<br />'."\n";
        }
        if ($userinfo['user_from']) {
            echo _LOCATION . ': ' . pnVarPrepForDisplay($userinfo['user_from']) . '<br />'."\n";
        }
        if ($userinfo['user_occ']) {
            echo _OCCUPATION . ': ' . pnVarPrepForDisplay($userinfo['user_occ']) . '<br />'."\n";
        }
        if ($userinfo['user_intrest']) {
            echo _INTERESTS . ': ' . pnVarPrepForDisplay($userinfo['user_intrest']) . '<br />'."\n";
        }
        if ($userinfo['user_sig']) {
            echo '<br />' . _SIGNATURE . ":<br />" . pnVarPrepHTMLDisplay($userinfo['user_sig']) . '<br />'."\n";
        }
        if ($userinfo['bio']) {
            echo '<br />' . _EXTRAINFO . ":<br />" . pnVarPrepForDisplay($userinfo['bio']) . '<br />'."\n";
        }

    $activetime = time() - (pnConfigGetVar('secinactivemins') * 60);
    $userhack = "SELECT pn_uid
          FROM ".$pntable['session_info']."
          WHERE pn_uid = '$userinfo[pn_uid]'
          AND pn_lastused > '".pnVarPrepForStore($activetime)."'";
    $userresult =& $dbconn->Execute($userhack);
    $online_state = $userresult->GetRowAssoc(false);
    if (isset($online_state['pn_uid'])) {
      $online = _ONLINE;
    } else {
      $online = _OFFLINE;
    }
    echo '<br />'._USERSTATUS.': '.pnVarPrepForDisplay($online).'<br />'."\n";

        if (pnModAvailable('Messages')) {
            echo "<br />[ <a href=\"".pnModURL('Messages', 'user', 'compose', array('uname' => $uname)) ."\">" . _USENDPRIVATEMSG . " " . pnVarPrepForDisplay($uname) . "</a> ]<br />\n";
        }
    if (pnModAvailable('Comments')) {
      echo '<br />';
        user_main_last10com($uname);
        echo '<br />';
    }
    if (pnModAvailable('News')) {
      echo '<br />';
        user_main_last10submit($uname);
    }
        echo '</div>';
    } else {
        echo '<div style="text-align:center">' . _NOINFOFOR . ' ' . pnVarPrepForDisplay($uname) . '</div>';
    }
    CloseTable();

    include('footer.php');
}

function user_user_login()
{
    list($uname,
        $pass,
        $url,
        $rememberme) = pnVarCleanFromInput('uname',
        'pass',
        'url',
        'rememberme');
    if (!isset($rememberme)) {
        $rememberme = '';
    }
    access_user_login($uname, $pass, $url, $rememberme);
}

function user_user_getlogin()
{
    // Check if stop var is numeric
    if ((isset($GLOBALS['stop']) && !empty($GLOBALS['stop']) && !is_numeric($GLOBALS['stop']))) {
        include 'header.php';
        echo _MODARGSERROR;
        unset($GLOBALS['stop']);
        include 'footer.php';
        exit;
    }
    // End of check
    if ($GLOBALS['stop']) {
    user_user_loginscreen(_LOGININCOR);
    } else {
      include 'header.php';
      OpenTable();
      echo '<h1>' . _USERREGLOGIN . '</h1>';
      CloseTable();

    OpenTable();
        echo '<h2>'._SELECTOPTION.'</h2>';
        echo '<ul>';
        echo '<li><a href="user.php?op=loginscreen&amp;module=User">'._LOGINSITE . '</a></li>';
        // if admin do not allow register
        if (pnConfigGetVar('reg_allowreg')) {
            // age will not be checked, if $pnconfig['minage'] is set to 0 in config.php
            if (pnConfigGetVar('minage') == 0) {
                echo '<li><a href="user.php?op=register&amp;module=NewUser">'._REGISTER.'</a></li>';
            } else {
                echo '<li><a href="user.php?op=check_age&amp;module=NewUser">'._REGISTER.'</a></li>';
            }
        } else {
            echo '<li><strong>'._NOTALLOWREG.'</strong><br />'._REASONS.'<br />&nbsp;&nbsp;&nbsp;&nbsp;' . pnVarPrepForDisplay(pnConfigGetVar('reg_noregreasons')) . '</li>';
        }

        echo '<li><a href="user.php?op=lostpassscreen&amp;module=LostPassword">'._RETRIEVEPASS.'</a></li>';
        echo '</ul>';
      CloseTable();
    include ('footer.php');
    }

}

function user_main_last10com($uname)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $column1 = &$pntable['comments_column'];
    $column2 = &$pntable['stories_column'];

    /**
     * Fetch active laguage
     */
    if (pnConfigGetVar('multilingual') == 1) {
        $querylang = "AND (" . $column2['alanguage'] . "='" . pnVarPrepForStore(pnUserGetLang()) . "' OR " . $column2['alanguage'] . "='') ";
    } else {
        $querylang = '';
    }

    /**
     * Build up SQL
     */
    $query = "SELECT " . $column1['tid'] . ", " . $column1['sid'] . ", " . $column1['subject'] . " " . "FROM " . $pntable['comments'] . ", " . $pntable['stories'] . " " . "WHERE (" . $column1['name'] . "='" . pnVarPrepForStore($uname) . "' AND " . $column1['sid'] . "=" . $column2['sid'] . ") " . $querylang . "ORDER BY " . $column1['sid'] . " DESC";

    /**
     * Make limited select
     */
    $result = $dbconn->SelectLimit($query, 10, 0);

    /**
     * Do output
     */
    OpenTable();
    echo '<h2>' . _LAST10COMMENTS . ' ' . pnVarPrepForDisplay($uname) . ':</h2>';
    echo '<ul>';
    while (list($tid, $sid, $subject) = $result->fields) {
        $result->MoveNext();
        echo '<li><a href="index.php?name=News&amp;file=article&amp;thold=-1&amp;mode=flat&amp;order=0&amp;sid='
             .pnVarPrepForDisplay($sid).'#'.pnVarPrepForDisplay($tid).'">' . pnVarPrepForDisplay($subject) . '</a></li>';
    }
    echo '</ul>';
    CloseTable();
}

function user_main_last10submit($uname)
{
    $pntable =& pnDBGetTables();
    $dbconn =& pnDBGetConn(true);
    $column = &$pntable['stories_column'];

    /**
     * Fetch active laguage
     */
    if (pnConfigGetVar('multilingual') == 1) {
        $querylang = "AND (" . $column['alanguage'] . "='" . pnVarPrepForStore(pnUserGetLang()) . "' OR " . $column['alanguage'] . "='') ";
    } else {
        $querylang = '';
    }

    /**
     * Build up SQL
     */
    $query = "SELECT " . $column['sid'] . ", " . $column['title'] . " " . "FROM " . $pntable['stories'] . " " . "WHERE " . $column['informant'] . "='" . pnVarPrepForStore($uname) . "' " . $querylang . "ORDER BY " . $column['sid'] . " DESC";

    /**
     * Make limited select
     */
    $result = $dbconn->SelectLimit($query, 10, 0);

    /**
     * Do output
     */
    OpenTable();
    echo '<h2>' . _LAST10SUBMISSIONS . ' ' . pnVarPrepForDisplay($uname) . ':</h2>';
    echo '<ul>';
    while (list($sid, $title) = $result->fields) {
        $result->MoveNext();
        If (!$title) {
            $title = '- no Title -' ;
        }
        echo '<li><a href="index.php?name=News&amp;file=article&amp;sid='.pnVarPrepForDisplay($sid).'">' . pnVarPrepForDisplay(pnVarCensor($title)) . '</a></li>';
    }
    echo '</ul>';
    CloseTable();
}

/**
 * View main user page
 *
 **/
function user_user_main()
{
    include 'header.php';
    if (pnUserLoggedIn()) {
        user_menu_draw();
        $uname = pnUserGetVar('uname');
        if (pnModAvailable('Comments')) {
            user_main_last10com($uname);
        }
        if (pnModAvailable('News')) {
            user_main_last10submit($uname);
        }
    } else {
        echo _PERMISSIONSNOAUTH;
    }
    include 'footer.php';
}

function user_user_loginscreen()
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
    echo '<h1>' . _USERLOGIN . '</h1>'."\n";
    echo '<h2 style="color: red;">' . _COOKIEHINTFORLOGIN . '</h2>';
    CloseTable();
    OpenTable();
    echo '<form action="user.php" method="post"><div>'."\n"
     . '<table border="0">'."\n"
     . '<tr>'."\n"
     . '<td><label for="uname_mod_user">' . _NICKNAME . '</label>: </td>'."\n"
     . '<td><input type="text" name="uname" id="uname_mod_user" size="26" maxlength="25" tabindex="0" /></td>'
     . '</tr>'."\n"
     . '<tr>'."\n"
     . '<td><label for="pass_mod_user">' . _PASSWORD . "</label>: </td>\n"
     . '<td><input type="password" name="pass" id="pass_mod_user" size="21" maxlength="20" tabindex="0" /></td>'."\n"
     . '</tr>'."\n";
    if (pnConfigGetVar('seclevel') != 'High') {
        echo '<tr>'."\n"
      .'<td><label for="rememberme_mod_user">' . _REMEMBERME . '</label>: </td>'."\n"
      .'<td><input type="checkbox" name="rememberme" id="rememberme_mod_user" tabindex="0" /></td>'."\n"
      .'</tr>'."\n";
    }
    echo '</table>'."\n"
     . '<input type="hidden" name="url" value="' . pnVarPrepForDisplay(pnServerGetVar('HTTP_REFERER')) . '" />'."\n";
    echo '<input type="hidden" name="module" value="User" />' . "\n"
     . '<input type="hidden" name="op" value="Login" />' . "\n"
     . '<input type="submit" value="' . _LOGIN . '" />' . "\n";
    echo '</div></form>'."\n";
    CloseTable();

    include 'footer.php';
}

function user_user_logout($var)
{
    pnUserLogOut();

    redirect_index(_YOUARELOGGEDOUT);
}

?>