<?PHP
// File: $Id: admin.php 17539 2006-01-12 14:18:37Z larsneo $
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

if (!defined('LOADED_AS_MODULE')) {
    die ('Access Denied');
}

modules_get_language();
modules_get_manual();

function submit_news_admin_getConfig() {

    include ('header.php');

    if (!pnSecAuthAction(0, 'Submit News::', '::', ACCESS_ADMIN)) {
        echo 'Access denied';
        include('footer.php');
        return;
    }

    // prepare vars
    $sel_notify['0'] = '';
    $sel_notify['1'] = '';
    $sel_notify[pnConfigGetVar('notify')] = ' checked="checked"';

    GraphicAdmin();
    OpenTable();
    echo '<h1>'._SUBMITCONF.'</h1>';
    CloseTable();

    $status = pnGetStatusMsg();
    if(!empty($status)) {
        OpenTable();
        echo "<div style=\"font-weight:bold;\">$status</div>";
        CloseTable();
    }
    
    OpenTable();
    print '<form action="admin.php" method="post"><div>' 
	    .'<table border="0"><tr><td>'
        ._NOTIFYSUBMISSION.'</td><td>'
	    .'<input type="radio" name="xnotify" value="1" '.$sel_notify['1'].' />'._YES.' &nbsp;'
        .'<input type="radio" name="xnotify" value="0" '.$sel_notify['0'].' />'._NO
        .'</td></tr><tr><td>'
        ._EMAIL2SENDMSG.':</td><td><input type="text" name="xnotify_email" value="'.pnConfigGetVar('notify_email').'" size="30" maxlength="100" />'
        .'</td></tr><tr><td>'
        ._EMAILSUBJECT.':</td><td><input type="text" name="xnotify_subject" value="'.pnConfigGetVar('notify_subject').'" size="50" maxlength="100" />'
        .'</td></tr><tr><td>'
        ._EMAILMSG.':</td><td><textarea name="xnotify_message" cols="80" rows="10">'.htmlspecialchars(pnConfigGetVar('notify_message')).'</textarea>'
        .'</td></tr><tr><td>'
        ._EMAILFROM.':</td><td><input type="text" name="xnotify_from" value="'.pnConfigGetVar('notify_from').'" size="15" maxlength="255" />'
        .'</td></tr></table>'
        .'<input type="hidden" name="module" value="'.$GLOBALS['module'].'" />'
        .'<input type="hidden" name="op" value="updateConfig" />'
	    .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
        .'<input type="submit" value="'._SUBMIT.'" />'
        .'</div></form>';
    CloseTable();

    include ('footer.php');

}

// new [landseer]
function submit_news_admin_updateConfig()
{
    if (!pnSecAuthAction(0, 'Submit News::', '::', ACCESS_ADMIN)) {
        include 'header.php';
        echo 'Access denied';
        include 'footer.php';
        return;
    } 
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }
    
    list( $xnotify,
          $xnotify_email, 
          $xnotify_subject, 
          $xnotify_message, 
          $xnotify_from ) = pnVarCleanFromInput( 'xnotify',
                                                 'xnotify_email',
                                                 'xnotify_subject',
                                                 'xnotify_message',
                                                 'xnotify_from' );
    // minimum checks
    pnConfigSetVar('notify_subject', $xnotify_subject );
    pnConfigSetVar('notify_message', $xnotify_message );
    $xnotify_from = (isset($xnotify_from)) ? $xnotify_from : pnConfigGetVar('sitename');
    pnConfigSetVar('notify_from', $xnotify_from );
    if(($xnotify==0) || ($xnotify==1)) {
        pnConfigSetVar('notify',$xnotify);
    }
    if(($xnotify==1) && (isset($xnotify_email) && (pnVarValidate($xnotify_email,'email')==false ))) {
        pnSessionSetVar('errormsg', _SUBMITVALIDEMAILADDRESSNEEDED );
    } else {
        pnConfigSetVar('notify_email', $xnotify_email);
    }
    pnRedirect('admin.php?module=Submit_News&op=main');
}

function submit_news_admin_main($var)
{
	$op = pnVarCleanFromInput('op');
	extract($var);

  if (!pnSecAuthAction(0, 'Submit News::', '::', ACCESS_ADMIN)) {
      include 'header.php';
      echo 'Access denied';
      include 'footer.php';
      return;
  } 

	switch ($op) {
		case 'getConfig':
			submit_news_admin_getConfig();
			break;
		case 'updateConfig':
			submit_news_admin_updateConfig($var);
			break;
		default:
			submit_news_admin_getConfig();
			break;
	}
}
?>