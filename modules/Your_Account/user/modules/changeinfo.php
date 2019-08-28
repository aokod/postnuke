<?php
// $Id: changeinfo.php 17323 2005-12-11 20:40:23Z larsneo $
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
// Original Author of this file:
// Purpose of this file: allows users to change account info
// ----------------------------------------------------------------------

if (eregi('changeinfo.php', $_SERVER['PHP_SELF'])) {
	die ("You can't access this file directly...");
}

$dbconn =& pnDBGetConn(true);

modules_get_language();

function edituser($htmltext)
{
    if (!pnUserLoggedIn()) {
        return;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    include('header.php');
    OpenTable();
    echo '<h2>'.pnVarPrepForDisplay($htmltext).'</h2>';
    echo '<h2>' . _PERSONALINFO . '</h2>';
    CloseTable();

    OpenTable();
    $propertytable = $pntable['user_property'];
    $propertycolumn = &$pntable['user_property_column'];

    $sql = "select " . $propertycolumn['prop_id'] . " AS prop_id, " . $propertycolumn['prop_label'] . " AS prop_label, " . $propertycolumn['prop_dtype'] . " AS prop_dtype, " . $propertycolumn['prop_length'] . " AS prop_length, " . $propertycolumn['prop_weight'] . " AS prop_weight, " . $propertycolumn['prop_validation'] . " AS prop_validation " . "FROM " . $propertytable . " " . "WHERE " . $propertycolumn['prop_weight'] . "!=0 ORDER BY " . $propertycolumn['prop_weight'];

    $result =& $dbconn->Execute($sql);

    $core_fields = array();
    echo '<form name="Register" action="user.php" method="post">'."\n";
    echo '<table cellpadding="8" border="0">';

    $prop_label_text = "";
    while (!$result->EOF) {
        list($prop_id, $prop_label, $prop_dtype, $prop_length, $prop_weight, $prop_validation) = $result->fields;
        $result->MoveNext();

        
		$prop_label_text = (defined($prop_label) ? constant($prop_label) : $prop_label);
      	        
        echo '<tr><td valign="top">' . $prop_label_text . '</td><td valign="top">';
       
	    switch ($prop_dtype) {
            case _UDCONST_MANDATORY;
            case _UDCONST_CORE;
                $core_fields[] = $prop_label;
                switch ($prop_label) {
                    case '_UREALNAME':
                        echo '<input type="text" name="realname" value="' . pnVarPrepForDisplay(pnUserGetVar('name')) . '" size="30" maxlength="60" />&nbsp;' . _OPTIONAL . '</td>';
                        break;
                    case '_UREALEMAIL':
                        echo '<input type="text" name="email" value="' . pnVarPrepForDisplay(pnUserGetVar('email')) . '" size="30" maxlength="60" />&nbsp;' . _REQUIRED . '&nbsp;' . _EMAILNOTPUBLIC . '</td>';
                        break;
                    case '_UFAKEMAIL':
                        echo '<input type="text" name="femail" value="' . pnVarPrepForDisplay(pnUserGetVar('femail')) . '" size="30" maxlength="60" />&nbsp;' . _OPTIONAL . '&nbsp;' . _EMAILPUBLIC . '</td>';
                        break;
                    case '_YOURHOMEPAGE':
                        echo '<input type="text" name="url" value="' . pnVarPrepForDisplay(pnUserGetVar('url')) . '" size="30" maxlength="100" />&nbsp;' . _OPTIONAL . '</td>';
                        break;
                    case '_TIMEZONEOFFSET':
                        $tzoffset = pnUserGetVar('timezone_offset');
                        global $tzinfo;
                        echo '<select name="timezoneoffset">';
                        foreach ($tzinfo as $tzindex => $tzdata) {
                            echo "\n".'<option value="'.pnVarPrepForDisplay($tzindex).'"';
                            if ($tzoffset == $tzindex) {
                                echo ' selected="selected"';
                            }
                            echo '>';
                            echo pnVarPrepForDisplay($tzdata);
                            echo '</option>';
                        }
                        echo '</select></td>';
                        break;
                    case '_YOURAVATAR':
                        $user_avatar = pnUserGetVar('user_avatar');
                        echo '<select name="user_avatar" onchange="showimage()">';
                        $handle = opendir('images/avatar');
                        while ($file = readdir($handle)) {
                            $filelist[] = $file;
                        }
                        asort($filelist);
                        while (list ($key, $file) = each ($filelist)) {
                            ereg('.gif|.jpg', $file);
                            if ($file != '.' && $file != '..' && $file != 'index.html' && $file != 'CVS') {
                                echo '<option value="'.pnVarPrepForDisplay($file).'"';
                                if ($file == $user_avatar) {
                                    echo ' selected="selected"';
                                }
                                echo '>'.pnVarPrepForDisplay($file).'</option>';
                            }
                        }
                        echo '</select>&nbsp;&nbsp;<img src="images/avatar/' . pnVarPrepForDisplay(pnUserGetVar('user_avatar')) . '" name="avatar" width="32" height="32" alt="" /></td>';
                        break;
                    case '_YICQ':
                        echo '<input type="text" name="user_icq" value="' . pnVarPrepForDisplay(pnUserGetVar('user_icq')) . '" size="30" maxlength="100" />&nbsp;' . _OPTIONAL . '</td>';
                        break;
                    case '_YAIM':
                        echo '<input type="text" name="user_aim" value="' . pnVarPrepForDisplay(pnUserGetVar('user_aim')) . '" size="30" maxlength="100" />&nbsp;' . _OPTIONAL . '</td>';
                        break;
                    case '_YYIM':
                        echo '<input type="text" name="user_yim" value="' . pnVarPrepForDisplay(pnUserGetVar('user_yim')) . '" size="30" maxlength="100" />&nbsp;' . _OPTIONAL . '</td>';
                        break;
                    case '_YMSNM':
                        echo '<input type="text" name="user_msnm" value="' . pnVarPrepForDisplay(pnUserGetVar('user_msnm')) . '" size="30" maxlength="100" />&nbsp;' . _OPTIONAL . '</td>';
                        break;
                    case '_YLOCATION':
                        echo '<input type="text" name="user_from" value="' . pnVarPrepForDisplay(pnUserGetVar('user_from')) . '" size="30" maxlength="100" />&nbsp;' . _OPTIONAL . '</td>';
                        break;
                    case '_YOCCUPATION':
                        echo '<input type="text" name="user_occ" value="' . pnVarPrepForDisplay(pnUserGetVar('user_occ')) . '" size="30" maxlength="100" />&nbsp;' . _OPTIONAL . '</td>';
                        break;
                    case '_YINTERESTS':
                        echo '<input type="text" name="user_intrest" value="' . pnVarPrepForDisplay(pnUserGetVar('user_intrest')) . '" size="30" maxlength="100" />&nbsp;' . _OPTIONAL . '</td>';
                        break;
                    case '_SIGNATURE':
                        echo '<textarea cols="65" rows="4" name="user_sig">' . pnVarPrepForDisplay(pnUserGetVar('user_sig')) . '</textarea><br />' . _OPTIONAL . '&nbsp;' . _255CHARMAX . '<br />' . _ALLOWEDHTML . '<br />';
                        $AllowableHTML = pnConfigGetVar('AllowableHTML');
                        while (list($key, $access,) = each($AllowableHTML)) {
                            if ($access > 0) echo ' &lt;' . $key . '&gt;';
                        }
                        echo '</td>';
                        break;
                    case '_EXTRAINFO':
                        echo '<textarea cols="65" rows="4" name="bio">' . pnVarPrepForDisplay(pnUserGetVar('bio')) . '</textarea><br />' . _OPTIONAL . '&nbsp;' . _CANKNOWABOUT . '</td>';
                        break;

                    case '_PASSWORD':
                        echo '<input type="password" name="pass" size="10" maxlength="20" />&nbsp;&nbsp;<input type="password" name="vpass" size="10" maxlength="20" />&nbsp;' . _TYPENEWPASSWORD . '</td>';
                        break;
                    default:
                        echo 'Undefined $prop_id, $prop_label, $prop_dtype, $prop_length, $prop_weight, $prop_validation </td>';
                }
                break;

            case _UDCONST_STRING:
                if (empty($prop_length)) $prop_length = 30;
                echo '<input type="text" name="dynadata['.pnVarPrepForDisplay($prop_label).']" value="' . pnVarPrepForDisplay(pnUserGetVar($prop_label)) . '" size="30" maxlength="'.pnVarPrepForDisplay($prop_length).'" />&nbsp;' . _OPTIONAL . '</td>';
                break;

            case _UDCONST_TEXT:
                echo '<textarea cols="80" rows="10" name="dynadata['.pnVarPrepForDisplay($prop_label).']">' . pnVarPrepForDisplay(pnUserGetVar($prop_label)) . '</textarea><br />' . _OPTIONAL;
                break;

            case _UDCONST_FLOAT:
            case _UDCONST_INTEGER:
                echo '<input type="text" name="dynadata['.pnVarPrepForDisplay($prop_label).']" value="' . pnVarPrepForDisplay(pnUserGetVar($prop_label)) . '" size="30" maxlength="100" />&nbsp;' . _OPTIONAL . '</td>';
                break;
        }
        echo '</tr>'."\n";
    }
    echo '<tr><td>&nbsp;</td><td>'
    	.'<input type="submit" value="' . _SAVECHANGES . '" />'."\n"
    	.'<input type="hidden" name="uname" value="' . pnVarPrepForDisplay(pnUserGetVar('uname')) . '" />'."\n"
		.'<input type="hidden" name="uid" value="' . pnVarPrepForDisplay(pnUserGetVar('uid')) . '" />'."\n"
		.'<input type="hidden" name="op" value="saveuser" />'."\n"
		.'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'."\n"
    	.'</td></tr>'."\n";
    // Use hidden variables to pass all core data not displayed
    if (!in_array('_UREALNAME', $core_fields)) {
        echo '<input type="hidden" name="realname" value="' . pnVarPrepForDisplay(pnUserGetVar('name')) . '" />';
    }
    if (!in_array('_UREALEMAIL', $core_fields)) {
        echo '<input type="hidden" name="email" value="' . pnVarPrepForDisplay(pnUserGetVar('email')) . '" />';
    }
    if (!in_array('_UFAKEMAIL', $core_fields)) {
        echo '<input type="hidden" name="femail" value="' . pnVarPrepForDisplay(pnUserGetVar('femail')) . '" />';
    }
    if (!in_array('_YOURHOMEPAGE', $core_fields)) {
        echo '<input type="hidden" name="url" value="' . pnVarPrepForDisplay(pnUserGetVar('url')) . '" />';
    }
    if (!in_array('_TIMEZONEOFFSET', $core_fields)) {
        echo '<input type="hidden" name="timezoneoffset" value="' . pnVarPrepForDisplay(pnUserGetVar('timezone_offset')) . '" />';
    }
    if (!in_array('_YOURAVATAR', $core_fields)) {
        echo '<input type="hidden" name="user_avatar" value="' . pnVarPrepForDisplay(pnUserGetVar('user_avatar')) . '" />';
    }
    if (!in_array('_YICQ', $core_fields)) {
        echo '<input type="hidden" name="user_icq" value="' . pnVarPrepForDisplay(pnUserGetVar('user_icq')) . '" />';
    }
    if (!in_array('_YAIM', $core_fields)) {
        echo '<input type="hidden" name="user_aim" value="' . pnVarPrepForDisplay(pnUserGetVar('user_aim')) . '" />';
    }
    if (!in_array('_YYIM', $core_fields)) {
        echo '<input type="hidden" name="user_yim" value="' . pnVarPrepForDisplay(pnUserGetVar('user_yim')) . '" />';
    }
    if (!in_array('_YMSNM', $core_fields)) {
        echo '<input type="hidden" name="user_msnm" value="' . pnVarPrepForDisplay(pnUserGetVar('user_msnm')) . '" />';
    }
    if (!in_array('_YLOCATION', $core_fields)) {
        echo '<input type="hidden" name="user_from" value="' . pnVarPrepForDisplay(pnUserGetVar('user_from')) . '" />';
    }
    if (!in_array("_YOCCUPATION", $core_fields)) {
        echo '<input type="hidden" name="user_occ" value="' . pnVarPrepForDisplay(pnUserGetVar('user_occ')) . '" />';
    }
    if (!in_array("_YINTERESTS", $core_fields)) {
        echo '<input type="hidden" name="user_intrest" value="' . pnVarPrepForDisplay(pnUserGetVar('user_intrest')) . '" />';
    }
    if (!in_array("_SIGNATURE", $core_fields)) {
        echo '<input type="hidden"  name="user_sig" value="' . pnVarPrepForDisplay(pnUserGetVar('user_sig')) . '" />';
    }
    if (!in_array("_EXTRAINFO", $core_fields)) {
        echo '<input type="hidden"  name="bio"  value="' . pnVarPrepForDisplay(pnUserGetVar('bio')) . '" />';
    }
    echo '</table></form>';
    echo '<br />';

    CloseTable();
    include('footer.php');
}

function saveuser()
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!pnSecConfirmAuthKey()) {
        die('Not allowed to directly alter user information');
        exit;
    }

    list($name,
        $email,
        $femail,
        $url,
        $pass,
        $vpass,
        $bio,
        $user_avatar,
        $user_icq,
        $user_occ,
        $user_from,
        $user_intrest,
        $user_sig,
        $user_aim,
        $user_yim,
        $user_msnm,
        $timezoneoffset,
        $dynadata) = pnVarCleanFromInput('realname',
                                         'email',
                                         'femail',
                                         'url',
                                         'pass',
                                         'vpass',
                                         'bio',
                                         'user_avatar',
                                         'user_icq',
                                         'user_occ',
                                         'user_from',
                                         'user_intrest',
                                         'user_sig',
                                         'user_aim',
                                         'user_yim',
                                         'user_msnm',
                                         'timezoneoffset',
                                         'dynadata');

    $minpass = pnConfigGetVar('minpass');

    if (pnUserLoggedIn()) {
        $uid = pnUserGetVar('uid');
        $uname = pnUserGetVar('uname');

        if ((isset($pass)) && ("$pass" != "$vpass")) {
            $htmltext = '' . _PASSDIFFERENT . '';
            edituser($htmltext);
        } elseif (($pass != '') && (strlen($pass) < $minpass)) {
            $htmltext = '' . _YOURPASSMUSTBE . " $minpass " . _CHARLONG . '';
            edituser($htmltext);
        } else {
            if ($bio) {
                $bio = pnVarCensor($bio);
            }
            if (!empty($femail)) {
                $femail = preg_replace('/[^a-zA-Z0-9_@.-]/', '', $femail);
            }
            if (!empty($url)) {
				if (!eregi('^http://[\-\.0-9a-z]+', $url)) {
					$url = 'http://' . $url;
				}
                $url = preg_replace('/[^a-zA-Z0-9_@.&#?;:\/-~-]/','',$url);
            }
            // bug #958 [landseer]
            if( !empty($email) ) {
                $email = (pnVarValidate($email,'email')==true) ? $email : pnUserGetVar('email');
            } else {
                $email = pnUserGetVar('email');
            }
            // end bug 958
            if ($pass != '') {
                $dbconn->Execute("LOCK TABLES $pntable[users] WRITE");
                $pass = md5($pass);
                $column = &$pntable['users_column'];
                $dbconn->Execute("UPDATE $pntable[users]
                                  SET $column[name]='" . pnVarPrepForStore($name) . "',
                                      $column[email]='" . pnVarPrepForStore($email) . "',
                                      $column[femail]='" . pnVarPrepForStore($femail) . "',
                                      $column[url]='" . pnVarPrepForStore($url) . "',
                                      $column[pass]='" . pnVarPrepForStore($pass) . "',
                                      $column[bio]='" . pnVarPrepForStore($bio) . "',
                                      $column[user_avatar]='" . pnVarPrepForStore($user_avatar) . "',
                                      $column[user_icq]='" . pnVarPrepForStore($user_icq) . "',
                                      $column[user_occ]='" . pnVarPrepForStore($user_occ) . "',
                                      $column[user_from]='" . pnVarPrepForStore($user_from) . "',
                                      $column[user_intrest]='" . pnVarPrepForStore($user_intrest) . "',
                                      $column[user_sig]='" . pnVarPrepForStore($user_sig) . "',
                                      $column[user_aim]='" . pnVarPrepForStore($user_aim) . "',
                                      $column[user_yim]='" . pnVarPrepForStore($user_yim) . "',
                                      $column[user_msnm]='" . pnVarPrepForStore($user_msnm) . "',
                                      $column[timezone_offset]='" . pnVarPrepForStore($timezoneoffset) . "'
                                  WHERE $column[uid]=" . pnVarPrepForStore($uid));
                $dbconn->Execute("UNLOCK TABLES");
            } else {
                $column = &$pntable['users_column'];
                $dbconn->Execute("UPDATE $pntable[users]
                                  SET $column[name]='" . pnVarPrepForStore($name) . "',
                                      $column[email]='" . pnVarPrepForStore($email) . "',
                                      $column[femail]='" . pnVarPrepForStore($femail) . "',
                                      $column[url]='" . pnVarPrepForStore($url) . "',
                                      $column[bio]='" . pnVarPrepForStore($bio) . "',
                                      $column[user_avatar]='" . pnVarPrepForStore($user_avatar) . "',
                                      $column[user_icq]='" . pnVarPrepForStore($user_icq) . "',
                                      $column[user_occ]='" . pnVarPrepForStore($user_occ) . "',
                                      $column[user_from]='" . pnVarPrepForStore($user_from) . "',
                                      $column[user_intrest]='" . pnVarPrepForStore($user_intrest) . "',
                                      $column[user_sig]='" . pnVarPrepForStore($user_sig) . "',
                                      $column[user_aim]='" . pnVarPrepForStore($user_aim) . "',
                                      $column[user_yim]='" . pnVarPrepForStore($user_yim) . "',
                                      $column[user_msnm]='" . pnVarPrepForStore($user_msnm) . "',
                                      $column[timezone_offset]='" . pnVarPrepForStore($timezoneoffset) . "'
                                  WHERE $column[uid]=" . pnVarPrepForStore($uid));
            }
            if (!empty($dynadata) && is_array($dynadata)) {
                while (list($key, $val) = each($dynadata)) {
                    pnUserSetVar($key, $val);
                }
            }
            redirect_user(); // we need to rip this out to mainfile and pass it a $page param
        }
    }
}

switch ($op) {
    case 'edituser':
    	if (!isset ($htmltext)) {
    		$htmltext = '';
    	}
	    edituser($htmltext);
        break;
    case 'saveuser':
        saveuser();
        break;
}

?>