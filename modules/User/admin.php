<?php
// File: $Id: admin.php 19963 2006-09-03 21:38:05Z larsneo $
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
    die ('Access Denied');
}

modules_get_language();
modules_get_manual();

if (!pnSecAuthAction(0, 'Users::', '::', ACCESS_ADMIN)) {
    include 'header.php';
    echo _MODIFYUSERSNOAUTH;
    include 'footer.php';
    exit;
}

/**
 * User menu
 */
function show_menu()
{
    GraphicAdmin();
    OpenTable();
    echo '<h2>' . _USERADMIN . '</h2>';
    if (pnSecAuthAction(0, 'Users::', '::', ACCESS_ADMIN)) {
        echo "<div style=\"text-align: center;\">";
        echo "[ <a href=\"admin.php?module=" . $GLOBALS['module'] . "&amp;op=getRegConfig\">" . _REGCONF . "</a> ]";
        echo "[ <a href=\"admin.php?module=" . $GLOBALS['module'] . "&amp;op=getConfig\">" . _USERCONF . "</a> ]";
        echo "[ <a href=\"admin.php?module=" . $GLOBALS['module'] . "&amp;op=getDynamic\">" . _DYNAMICDATA . "</a> ]";
        echo '</div>';
    }
    CloseTable();
}
/**
 * Users Functions
 */

function displayUsers()
{
    include('header.php');
    show_menu();

    if (!pnSecAuthAction(0, 'Users::', '::', ACCESS_EDIT)) {
        echo _MODIFYUSERSNOAUTH;
        include 'footer.php';
        return;
    }
    // Edit and Delete current user
    OpenTable();
    echo '<h2>' . _EDITUSER . '</h2>'
       . "<form method=\"post\" action=\"admin.php\"><div>"
       . '<strong>' . _NICKNAME . ": </strong>"
       . "<input type=\"text\" name=\"chng_uname\" size=\"20\" />\n"
       . "<select name=\"op\">\n"
       . "<option value=\"modifyUser\">" . _MODIFY . "</option>\n"
       . "<option value=\"delUser\">" . _DELETE . "</option>\n"
       . "</select>\n"
       . "<input type=\"hidden\" name=\"module\" value=\"User\" />"
       . "<input type=\"hidden\" name=\"authid\" value=\"" . pnSecGenAuthKey(). "\" />"
       . "<input type=\"submit\" value=\"" . _OK . "\" /></div></form>";
    CloseTable();
    
    // Add new user
    if (pnSecAuthAction(0, 'Users::', '::', ACCESS_ADD)) {
        OpenTable();
        echo '<h2>' . _ADDUSER . '</h2>'
           . '<form action="admin.php" method="post"><div>'
           . "<table border=\"0\" width=\"100%\">"
           . "<tr><td style=\"width:100px\">" . _NICKNAME . "</td>"
           . "<td><input type=\"text\" name=\"add_uname\" size=\"30\" maxlength=\"25\" /> <span class=\"pn-sub\">" . _REQUIRED . "</span></td></tr>"
           . "<tr><td>" . _EMAIL . "</td>"
           . "<td><input type=\"text\" name=\"add_email\" size=\"30\" maxlength=\"60\" /> <span class=\"pn-sub\">" . _REQUIRED . "</span></td></tr>"
           . "<tr><td>" . _PASSWORD . "</td>"
           . "<td><input type=\"password\" name=\"add_pass\" size=\"12\" maxlength=\"12\" /> <span class=\"pn-sub\">" . _REQUIRED . "</span>"
           . "<input type=\"hidden\" name=\"add_avatar\" value=\"blank.gif\" />"
           . "<input type=\"hidden\" name=\"module\" value=\"User\" />"
           . "<input type=\"hidden\" name=\"op\" value=\"addUser\" />"
           . "<input type=\"hidden\" name=\"authid\" value=\"" . pnSecGenAuthKey() . "\" />"
           . "</td></tr><tr><td><input type=\"submit\" value=\"" . _ADDUSERBUT . "\" /></td></tr></table></div></form>";
        CloseTable();
    }
    include('footer.php');
}

function modifyUser($chng_uname)
{
	  $chng_uname=pnVarCleanFromInput('chng_uname');
	  
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    include('header.php');
    GraphicAdmin();

    OpenTable();
    echo '<h2>' . _USERADMIN . '</h2>';
    CloseTable();

    $column = &$pntable['users_column'];
    $result =& $dbconn->Execute("SELECT $column[uid],          $column[uname],          $column[name],
                                        $column[url],          $column[email],          $column[femail],
                                        $column[user_icq],     $column[user_aim],       $column[user_yim],
                                        $column[user_msnm],    $column[user_from],      $column[user_occ],
                                        $column[user_intrest], $column[user_viewemail], $column[user_avatar],
                                        $column[user_sig],     $column[bio],            $column[pass],
                                        $column[user_regdate]
                                 FROM   $pntable[users]
                                 WHERE  $column[uname] = '".pnVarPrepForStore($chng_uname)."'");

    if (!$result->EOF) {
        list($chng_uid,          $chng_uname,          $chng_name,
             $chng_url,          $chng_email,          $chng_femail,
             $chng_user_icq,     $chng_user_aim,       $chng_user_yim,
             $chng_user_msnm,    $chng_user_from,      $chng_user_occ,
             $chng_user_intrest, $chng_user_viewemail, $chng_avatar,
             $chng_user_sig,     $chng_bio,            $chng_pass,
             $chng_regdate) = $result->fields;
        if (!pnSecAuthAction(0, 'Users::', "$chng_uname::$chng_uid", ACCESS_EDIT)) {
            echo _MODIFYUSERSEDITNOAUTH;
            include 'footer.php';
            return;
        }
        $permissions_array=array();
        $access_types_array=array();
        $usergroups = pnModAPIFunc('Groups', 'user', 'getusergroups', array('uid' => $chng_uid));
        foreach ($usergroups as $usergroup) {
            $permissions_array[] = (int)$usergroup['gid'];
        }
        $allgroups = pnModAPIFunc('Groups', 'user', 'getall');
        foreach ($allgroups as $group) {
            $access_types_array[$group['gid']]=$group['name'];
        }
        OpenTable();
        echo '<h2>' . _USERUPDATE . ': <em>' . pnVarPrepForDisplay($chng_uname) . '</em></h2>' .
             '<form name="Register" action="admin.php" method="post"><div>' .
             '<table border="0">' .
             '<tr><td>'._USERID.'</td><td><strong>'.pnVarPrepForDisplay($chng_uid).'</strong></td></tr>' .
             '<tr><td>'._REGDATE.'</td><td>'.pnVarPrepForDisplay(ml_ftime(_DATETIMEBRIEF, $chng_regdate)).'</td></tr>' .
             '<tr><td>'._NICKNAME.'</td><td><input type="text" name="chng_uname" value="'.pnVarPrepForDisplay($chng_uname).'" size="30" maxlength="25" /> '.
             '<span class="pn-sub">' . _REQUIRED . '</span></td></tr>' .
             '<tr><td>'._NAME.'</td><td><input type="text" name="chng_name" value="'.pnVarPrepForDisplay($chng_name).'" size="30" maxlength="60" />'.
             '<tr><td>'._EMAIL.'</td><td><input type="text" name="chng_email" value="'.pnVarPrepHTMLDisplay($chng_email).'" size="30" maxlength="60" /> <span class="pn-sub">'._REQUIRED . '</span></td></tr>' .
             '<tr><td>'._UFAKEMAIL.'</td><td><input type="text" name="chng_femail" value="'.pnVarPrepForDisplay($chng_femail).'" size="30" maxlength="60" />' .
             '<tr><td>'._URL.'</td><td><input type="text" name="chng_url" value="'.pnVarPrepForDisplay($chng_url).'" size="30" maxlength="255" />';
        echo '<tr><td>'._AVATAR.'</td><td><select name="user_avatar" onchange="showimage()">';
        $handle = opendir('images/avatar');
        while ($file = readdir($handle)) {
            $filelist[] = $file;
        }
        asort($filelist);
        while (list ($key, $file) = each ($filelist)) {
            ereg('.gif|.jpg', $file);
            if ($file != '.' && $file != '..' && $file != 'index.html' && $file != 'CVS') {
                echo '<option value="'.pnVarPrepForDisplay($file).'"';
                if ($file == $chng_avatar) {
                    echo ' selected="selected"';
                }
                echo '>'.pnVarPrepForDisplay($file).'</option>';
            }
        }
        echo '</select>&nbsp;&nbsp;<img src="images/avatar/' . pnVarPrepForDisplay($chng_avatar) . '" name="avatar" width="32" height="32" alt="" /></td>'.
             '<tr><td>'._ICQ.'</td><td><input type="text" name="chng_user_icq" value="'.pnVarPrepForDisplay($chng_user_icq).'" size="30" maxlength="255" />&nbsp;'._OPTIONAL.
             '<tr><td>'._AIM.'</td><td><input type="text" name="chng_user_aim" value="'.pnVarPrepForDisplay($chng_user_aim).'" size="30" maxlength="255" />&nbsp;'._OPTIONAL.
             '<tr><td>'._YIM.'</td><td><input type="text" name="chng_user_yim" value="'.pnVarPrepForDisplay($chng_user_yim).'" size="30" maxlength="255" />&nbsp;'._OPTIONAL.
             '<tr><td>'._MSNM.'</td><td><input type="text" name="chng_user_msnm" value="'.pnVarPrepForDisplay($chng_user_msnm).'" size="30" maxlength="255" />&nbsp;'._OPTIONAL.
             '<tr><td>'._LOCATION.'</td><td><input type="text" name="chng_user_from" value="'.pnVarPrepForDisplay($chng_user_from).'" size="30" maxlength="255" />&nbsp;'._OPTIONAL.
             '<tr><td>'._OCCUPATION.'</td><td><input type="text" name="chng_user_occ" value="'.pnVarPrepForDisplay($chng_user_occ).'" size="30" maxlength="255" />&nbsp;'._OPTIONAL.
             '<tr><td>'._INTERESTS.'</td><td><input type="text" name="chng_user_intrest" value="'.pnVarPrepForDisplay($chng_user_intrest).'" size="30" maxlength="255" />&nbsp;'._OPTIONAL.
             '<tr><td>'._SIGNATURE.'</td><td><textarea cols="65" rows="4" name="chng_user_sig">'.pnVarPrepForDisplay($chng_user_sig).'</textarea>' .
             '<tr><td>'._BIO.'</td><td><textarea cols="65" rows="4" name="chng_bio">' . pnVarPrepForDisplay($chng_bio). '</textarea></td></tr>' .
             '<tr><td>'._OPTION.'</td>';
        if ($chng_user_viewemail == 1) {
            echo "<td><input type=\"checkbox\" name=\"chng_user_viewemail\" value=\"1\" checked=\"checked\" /> " . _ALLOWUSERS . "</td></tr>";
        } else {
            echo "<td><input type=\"checkbox\" name=\"chng_user_viewemail\" value=\"1\" /> " . _ALLOWUSERS . "</td></tr>";
        }
        echo "<tr><td>" . _PASSWORD . "</td>" .
             "<td><input type=\"password\" name=\"chng_pass\" size=\"12\" maxlength=\"12\" /></td></tr>" .
             "<tr><td>" . _RETYPEPASSWD . "</td>" .
             "<td><input type=\"password\" name=\"chng_pass2\" size=\"12\" maxlength=\"12\" /> <span class=\"pn-sub\">" .
             _FORCHANGES . "</span></td></tr>" .
             "<tr><td>" . _GROUPMEMBERSHIP . "</td><td>";
        echo "<table border=\"1\">\n";
        echo "<tr><th>" . _GROUP . "</th><th>" . _MEMBEROF . "</th></tr>";
        while(list($access_id, $access_title) = each($access_types_array)) {
            if ((int)$access_id>=0) {
                echo "<tr><td>".pnVarPrepForDisplay($access_title)."</td>";
                echo "<td><input type=\"checkbox\" ";
                foreach($permissions_array as $member) {
                    if ($access_id==$member) {
                        echo "checked=\"checked\"";
                        break;
                    }
                }
                echo " name=\"access_permissions[]\" value=\"".pnVarPrepForDisplay($access_id)."\" /></td>";
                echo "</tr>\n";
            }
        }
        echo "</table>\n</td>\n</tr>\n" .
             "<tr><td><input type=\"submit\" value=\"" . _SAVECHANGES . "\" /></td></tr></table>" .
             "<input type=\"hidden\" name=\"chng_uid\" value=\"".(int)pnVarPrepForDisplay($chng_uid)."\" />" .
             "<input type=\"hidden\" name=\"module\" value=\"User\" />" .
             "<input type=\"hidden\" name=\"op\" value=\"updateUser\" />" .
             "<input type=\"hidden\" name=\"authid\" value=\"" . pnSecGenAuthKey() . "\" />" .
             "</div></form>";
        CloseTable();
    } else {
        OpenTable();
        echo '<div style="text-align:center"><strong>' . _USERNOEXIST . ' ('.pnVarPrepForDisplay($chng_uname).')</strong><br />' . _GOBACK . '</div>';
        CloseTable();
    }
    include('footer.php');
}

function updateUser()
{
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    list($chng_uid,
        $chng_uname,
        $chng_name,
        $chng_url,
        $chng_pass,
        $chng_pass2,
        $chng_email,
        $chng_femail,
        $chng_user_icq,
        $chng_user_aim,
        $chng_user_yim,
        $chng_user_msnm,
        $chng_user_from,
        $chng_user_occ,
        $chng_user_intrest,
        $chng_user_viewemail,
        $chng_user_sig,
        $chng_avatar,
        $chng_bio,
        $access_permissions) = pnVarCleanFromInput('chng_uid',
        'chng_uname',
        'chng_name',
        'chng_url',
        'chng_pass',
        'chng_pass2',
        'chng_email',
        'chng_femail',
        'chng_user_icq',
        'chng_user_aim',
        'chng_user_yim',
        'chng_user_msnm',
        'chng_user_from',
        'chng_user_occ',
        'chng_user_intrest',
        'chng_user_viewemail',
        'chng_user_sig',
        'user_avatar',
        'chng_bio',
        'access_permissions');

    if (empty($chng_user_viewemail)) {
        $chng_user_viewemail = 0;
    }

    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column = &$pntable['users_column'];
    $result =& $dbconn->Execute("SELECT $column[uname]
                                 FROM   $pntable[users]
                                 WHERE  $column[uid] = '" . (int)pnVarPrepForStore($chng_uid) . "'");

    if (!$result->EOF) {
        list($old_uname) = $result->fields;
    } else {
        include 'header.php';
        echo _USERNOEXIST;
        include 'footer.php';
        return;
    }
    if (!pnSecAuthAction(0, 'Users::', $old_uname . "::" . $chng_uid, ACCESS_EDIT)) {
        include 'header.php';
        echo _MODIFYUSERSEDITNOAUTH;
        include 'footer.php';
        return;
    }
    if (!$chng_uname || stristr($chng_uname,'&') || preg_match("/[[:space:]]/", $chng_uname) || strip_tags($chng_uname) != $chng_uname) {
        include('header.php');
        GraphicAdmin();
        OpenTable();
        echo '<h2>' . _USERADMIN . '</h2>';
        CloseTable();

        OpenTable();
        echo "<div style=\"text-align:center\">" . _ERRORINVNICK . '<br />' . _GOBACK . '</div>';
        CloseTable();
        include('footer.php');
        exit;
    }

    // Fix for wrong URL adress
    if (!pnVarValidate($chng_url, 'url')) {
        $chng_url="";
    }
    
    // validate uname
    if (!pnVarValidate($chng_uname, 'uname')) {
    	  $chng_uname=$old_uname;
    }

    $tmp = 0;
    if ($chng_pass2 != "") {
        if ($chng_pass != $chng_pass2) {
            include 'header.php';
            GraphicAdmin();
            OpenTable();
            echo '<h2>' . _USERADMIN . '</h2>';
            CloseTable();

            OpenTable();
            echo "<div style=\"text-align:center\">" . _PASSWDNOMATCH . '<br />' . _GOBACK . '</h2>';
            CloseTable();
            include('footer.php');
            exit;
        }
        $tmp = 1;
    }

    if ($tmp == 0) {
        $column = &$pntable['users_column'];
        $result =& $dbconn->Execute("UPDATE $pntable[users]
                                     SET    $column[uname]='"           . pnVarPrepForStore($chng_uname)          . "',
                                            $column[name]='"            . pnVarPrepForStore($chng_name)           . "',
                                            $column[email]='"           . pnVarPrepForStore($chng_email)          . "',
                                            $column[femail]='"          . pnVarPrepForStore($chng_femail)         . "',
                                            $column[url]='"             . pnVarPrepForStore($chng_url)            . "',
                                            $column[user_icq]='"        . pnVarPrepForStore($chng_user_icq)       . "',
                                            $column[user_aim]='"        . pnVarPrepForStore($chng_user_aim)       . "',
                                            $column[user_yim]='"        . pnVarPrepForStore($chng_user_yim)       . "',
                                            $column[user_msnm]='"       . pnVarPrepForStore($chng_user_msnm)      . "',
                                            $column[user_from]='"       . pnVarPrepForStore($chng_user_from)      . "',
                                            $column[user_occ]='"        . pnVarPrepForStore($chng_user_occ)       . "',
                                            $column[user_intrest]='"    . pnVarPrepForStore($chng_user_intrest)   . "',
                                            $column[user_viewemail]='"  . pnVarPrepForStore($chng_user_viewemail) . "',
                                            $column[user_avatar]='"     . pnVarPrepForStore($chng_avatar)         . "',
                                            $column[user_sig]='"        . pnVarPrepForStore($chng_user_sig)       . "',
                                            $column[bio]='"             . pnVarPrepForStore($chng_bio)            . "'
                                     WHERE  $column[uid]='"             . (int)pnVarPrepForStore($chng_uid)       . "'");
        if ($dbconn->ErrorNo() <> 0) {
            error_log("DB Error: " . $dbconn->ErrorMsg());
        }
    }
    if ($tmp == 1) {
        $cpass = md5($chng_pass);
        $column = &$pntable['users_column'];
        $result =& $dbconn->Execute("UPDATE $pntable[users]
                                     SET    $column[uname]='"           . pnVarPrepForStore($chng_uname)          . "',
                                            $column[name]='"            . pnVarPrepForStore($chng_name)           . "',
                                            $column[email]='"           . pnVarPrepForStore($chng_email)          . "',
                                            $column[femail]='"          . pnVarPrepForStore($chng_femail)         . "',
                                            $column[url]='"             . pnVarPrepForStore($chng_url)            . "',
                                            $column[user_icq]='"        . pnVarPrepForStore($chng_user_icq)       . "',
                                            $column[user_aim]='"        . pnVarPrepForStore($chng_user_aim)       . "',
                                            $column[user_yim]='"        . pnVarPrepForStore($chng_user_yim)       . "',
                                            $column[user_msnm]='"       . pnVarPrepForStore($chng_user_msnm)      . "',
                                            $column[user_from]='"       . pnVarPrepForStore($chng_user_from)      . "',
                                            $column[user_occ]='"        . pnVarPrepForStore($chng_user_occ)       . "',
                                            $column[user_intrest]='"    . pnVarPrepForStore($chng_user_intrest)   . "',
                                            $column[user_viewemail]='"  . pnVarPrepForStore($chng_user_viewemail) . "',
                                            $column[user_avatar]='"     . pnVarPrepForStore($chng_avatar)         . "',
                                            $column[user_sig]='"        . pnVarPrepForStore($chng_user_sig)       . "',
                                            $column[bio]='"             . pnVarPrepForStore($chng_bio)            . "',
                                            $column[pass]='"            . pnVarPrepForStore($cpass)               . "'
                                     WHERE  $column[uid]='"             . (int)pnVarPrepForStore($chng_uid)       . "'");
        if ($dbconn->ErrorNo() <> 0) {
            error_log("DB Error: " . $dbconn->ErrorMsg());
        }
    }

    pnModCallHooks('item', 'update', $chng_uid, '');

    // update group membership
    $group_membership_table  =  $pntable['group_membership'];
    $group_membership_column = &$pntable['group_membership_column'];
    $result =& $dbconn->Execute("DELETE FROM $group_membership_table
                                 WHERE       $group_membership_column[uid]='" . (int)pnVarPrepForStore($chng_uid) . "'");
    if ($dbconn->ErrorNo() == 0) {
        foreach($access_permissions as $permission) {
            $result =& $dbconn->Execute("INSERT INTO $group_membership_table
                                         SET         $group_membership_column[gid]='" . (int)pnVarPrepForStore($permission) . "',
                                                     $group_membership_column[uid]='" . (int)pnVarPrepForStore($chng_uid)   . "'");
            if ($dbconn->ErrorNo() <> 0) {
                error_log("DB Error: " . $dbconn->ErrorMsg());
            }
        }
    }
    pnRedirect("admin.php?module=User&op=main");
}

function deleteUser($chng_uname)
{
	  $chng_uname = pnVarCleanFromInput('chng_uname');
	  
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $authid = pnSecGenAuthKey();

    include('header.php');
    GraphicAdmin();
    OpenTable();
    echo '<h2>' . _USERADMIN . '</h2>';
    CloseTable();

    OpenTable();
    echo '<h2>' . _DELETEUSER . '</h2>';

    $column = &$pntable['users_column'];
    $result =& $dbconn->Execute("SELECT $column[uid]
                                 FROM   $pntable[users]
                                 WHERE $column[uname] = '".pnVarPrepForStore($chng_uname)."'");

    if (!$result->EOF) {
        list($uid) = $result->fields;
    } else {
        echo _USERNOEXIST." (".pnVarPrepForDisplay($chng_uname).")";
        CloseTable();
        include 'footer.php';
        exit;
    }
    if (!pnSecAuthAction(0, 'Users::', "$chng_uname::$uid", ACCESS_DELETE)) {
        echo _MODIFYUSERSDELNOAUTH;
        CloseTable();
        include 'footer.php';
        exit;
    }
    echo '<h2>' . _SURE2DELETE . " " . pnVarPrepForDisplay($chng_uname) ." (".pnVarPrepForDisplay($uid) . ")?<br />" . "[ <a href=\"admin.php?module=User&amp;op=delUserConf&amp;del_uid=".(int)pnVarPrepForDisplay($uid)."&amp;authid=".pnVarPrepForDisplay($authid)."\">" . _YES . "</a> | <a href=\"admin.php?module=User&amp;op=mod_users\">" . _NO . "</a> ]</h2>";
    CloseTable();
    include('footer.php');
}

function deleteUserConfirm($del_uid)
{
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }
    
    $del_uid = (int)pnVarCleanFromInput('del_uid');
    
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column = &$pntable['users_column'];

    $result =& $dbconn->Execute("SELECT $column[uname]
                                 FROM   $pntable[users]
                                 WHERE  $column[uid] = '".(int)pnVarPrepForStore($del_uid)."'");

    if (!$result->EOF) {
        list($uname) = $result->fields;
    } else {
        include 'header.php';
        echo _USERNOEXIST." (".pnVarPrepForDisplay($uname)."/".pnVarPrepForDisplay($del_uid).")";
        include 'footer.php';
        exit;
    }
    if (!pnSecAuthAction(0, 'Users::', "$uname::$del_uid", ACCESS_DELETE)) {
        include 'header.php';
        echo _MODIFYUSERSDELNOAUTH;
        include 'footer.php';
        exit;
    }
    $column = &$pntable['user_perms_column'];
    $dbconn->Execute("DELETE FROM $pntable[user_perms]
                      WHERE       $column[uid] = '".(int)pnVarPrepForStore($del_uid)."'");
    if ($dbconn->ErrorNo() <> 0) {
        echo $dbconn->ErrorMsg();
        error_log("DB Error: " . $dbconn->ErrorMsg());
    }
    $column = &$pntable['group_membership_column'];
    $dbconn->Execute("DELETE FROM $pntable[group_membership]
                      WHERE       $column[uid] = '".(int)pnVarPrepForStore($del_uid)."'");
    if ($dbconn->ErrorNo() <> 0) {
        echo $dbconn->ErrorMsg();
        error_log("DB Error: " . $dbconn->ErrorMsg());
    }
    $column = &$pntable['users_column'];
    $dbconn->Execute("DELETE FROM $pntable[users]
                      WHERE       $column[uid] = '".(int)pnVarPrepForStore($del_uid)."'");
    if ($dbconn->ErrorNo() <> 0) {
        echo $dbconn->ErrorMsg();
        error_log("DB Error: " . $dbconn->ErrorMsg());
    }
    // remove dud - markwest bug \616 - patch from mrkshrt
    $column = &$pntable['user_data_column'];
    $dbconn->Execute("DELETE FROM $pntable[user_data]
                      WHERE       $column[uda_uid] = '".(int)pnVarPrepForStore($del_uid)."'");
    if ($dbconn->ErrorNo() <> 0) {
      echo $dbconn->ErrorMsg();
      error_log("DB Error: " . $dbconn->ErrorMsg());
    }

    // Let any hooks know that we have deleted an item
    pnModCallHooks('item', 'delete', $del_uid, '');

    pnRedirect("admin.php?module=User&op=main");
}

function addUser($add_uname, $add_email, $add_pass)
{
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }
    
    list($add_uname, $add_email, $add_pass) = pnVarCleanFromInput('add_uname','add_email','add_pass');
    
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!pnSecAuthAction(0, 'Users::', $add_uname . "::", ACCESS_ADD)) {
        include 'header.php';
        echo _MODIFYUSERSADDNOAUTH;
        include 'footer.php';
        exit;
    }

    if (empty($add_uname) || empty($add_email) || empty($add_pass)) {
        include('header.php');
        GraphicAdmin();
        OpenTable();
        echo '<h2>' . _USERADMIN . '</h2>';
        CloseTable();

        OpenTable();
        echo '<strong>' . _NEEDTOCOMPLETE . '</strong><br />' . _GOBACK;
        CloseTable();
        include('footer.php');
        return;
    }

    $stop = userCheck($add_uname, $add_email);
    if (empty($stop)) {
        $Default_Theme  = pnConfigGetVar('Default_Theme');
        $commentlimit   = pnConfigGetVar('commentlimit');
        $storynum       = pnConfigGetVar('storyhome');
        $timezoneoffset = pnConfigGetVar('timezone_offset');
        $user_regdate   = time();
        $column         = &$pntable['users_column'];
        $existinguser =& $dbconn->Execute("SELECT $column[uname]
                                           FROM   $pntable[users]
                                           WHERE  $column[uname] = '" . pnVarPrepForStore(add_uname) . "'");
        if (!$existinguser->EOF) {
            include 'header.php';
            echo '<h2>' . _USEREXIST . " <a href=\"admin.php?module=User&op=modifyUser&chng_uid=".pnVarPrepForDisplay($add_uname)." \">(" . pnVarPrepForDisplay($add_uname) . ") " . "</a></h2>";
            echo "<a href=\"admin.php?module=User&op=main\">" . _ADDUSER . "</a>";
            include 'footer.php';
        } else {
            $add_pass = md5($add_pass);
            $uid = $dbconn->GenId($pntable['users']);
            $sql = "INSERT INTO $pntable[users] ($column[uid],          $column[name],         $column[uname],
                                                 $column[email],        $column[femail],       $column[url],
                                                 $column[user_regdate], $column[user_icq],     $column[user_aim],
                                                 $column[user_yim],     $column[user_msnm],    $column[user_from],
                                                 $column[user_occ],     $column[user_intrest], $column[user_viewemail],
                                                 $column[user_avatar],  $column[user_sig],     $column[pass],
                                                 $column[timezone_offset])
                    VALUES                      (" . (int)pnVarPrepForStore($uid) . ",'','" . pnVarPrepForStore($add_uname) . "',
                                                '" . pnVarPrepForStore($add_email) . "', '', '',
                                                '" . pnVarPrepForStore($user_regdate) . "', '', '',
                                                '', '', '',
                                                '', '', '0',
                                                'blank.gif', '', '" . pnVarPrepForStore($add_pass) . "',
                                                '" . pnVarPrepForStore($timezoneoffset) . "')";
            $result =& $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() <> 0) {
                echo $dbconn->ErrorNo() . ": " . $dbconn->ErrorMsg() . '<br />';
                error_log("DB Error: " . $dbconn->ErrorMsg());
                return;
            }

            // get the generated id
            $uid = $dbconn->PO_Insert_ID($pntable['users'], $column['uid']);

            // Let any hooks know that we have created a new link
            pnModCallHooks('item', 'create', $uid, 'uid');

            // Add user to group
            $column = &$pntable['groups_column'];
            $result =& $dbconn->Execute("SELECT $column[gid]
                                         FROM   $pntable[groups]
                                         WHERE  $column[name] = '" . pnVarPrepForStore(pnModGetVar('Groups', 'defaultgroup')) . "'");

            if ($dbconn->ErrorNo() <> 0) {
                echo $dbconn->ErrorNo() . "Get default group: " . $dbconn->ErrorMsg() . '<br />';
                error_log ($dbconn->ErrorNo() . "Get default group: " . $dbconn->ErrorMsg() . '<br />');
                return;
            }
            if (!$result->EOF) {
                list($gid) = $result->fields;
                $result->Close();
                $column = &$pntable['group_membership_column'];
                $result =& $dbconn->Execute("INSERT INTO $pntable[group_membership] ($column[gid],
                                                                                     $column[uid])
                                             VALUES                                 (" . (int)pnVarPrepForStore($gid) . ",
                                                                                     " . (int)pnVarPrepForStore($uid) . ")");

                if ($dbconn->ErrorNo() <> 0) {
                    echo $dbconn->ErrorNo() . "Add to default group: " . $dbconn->ErrorMsg() . '<br />';
                    error_log ($dbconn->ErrorNo() . "Add to default group: " . $dbconn->ErrorMsg() . '<br />');
                    return;
                }
            }
            // fix for #1566 [landseer]
            pnSessionSetVar('statusmsg', pnVarPrepForDisplay(_ADDED) . ' ' . pnVarPrepForDisplay($add_uname));
            pnRedirect("admin.php?module=User&op=main");
            return true;
        }
    } else {
        include 'header.php';
        echo "<h2>".pnVarPrepForDisplay($stop)."</h2>";
        include 'footer.php';
    }
}

// new [landseer]
function user_admin_updateConfig()
{
    if (!pnSecAuthAction(0, 'Users::', '::', ACCESS_ADMIN)) {
        include 'header.php';
        echo _MODIFYUSERSNOAUTH;
        include 'footer.php';
    }
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    list( $usergraphic,
          $userimg,
          $anonymous ) = pnVarCleanFromInput('xusergraphic','xuserimg', 'xanonymous');
    $userimg = pnVarPrepForStore($userimg);
    if(!empty($userimg) && file_exists($userimg)) {
        pnConfigSetVar('userimg', $userimg);
    }

    pnConfigSetVar('anonymous', $anonymous);

    if(($usergraphic==0) || ($usergraphic==1)) {
        pnConfigSetVar('usergraphic',$usergraphic);
    }
    pnRedirect("admin.php?module=User&op=main");
}

function user_admin_getConfig()
{
    include ('header.php');
    // prepare vars
    $sel_usergraphic['0'] = '';
    $sel_usergraphic['1'] = '';
    $sel_usergraphic[pnConfigGetVar('usergraphic')] = ' checked="checked"';
    $sel_minpass['3'] = '';
    $sel_minpass['5'] = '';
    $sel_minpass['8'] = '';
    $sel_minpass['10'] = '';
    $sel_minpass[pnConfigGetVar('minpass')] = ' selected="selected"';

    show_menu();
    OpenTable();
    print '<h2>' . _USERCONF . '</h2>' .
          '<form action="admin.php" method="post"><div>' .
          '<table border="0"><tr><td>' . _USERPATH .
          "</td><td><input type=\"text\" name=\"xuserimg\" value=\"" . pnConfigGetVar('userimg') . "\" size=\"50\" />" .
          '</td></tr><tr><td>' . _USERGRAPHIC . '</td><td>' .
          "<input type=\"radio\" name=\"xusergraphic\" value=\"1\"" . $sel_usergraphic['1'] . " />" . _YES . ' &nbsp;' .
          "<input type=\"radio\" name=\"xusergraphic\" value=\"0\"" . $sel_usergraphic['0'] . " />" . _NO . '</td></tr>' .
          '<tr><td>' . pnVarPrepForDisplay(_ANONYMOUSNAME) . ':</td><td><input type="text" name="xanonymous" value="' . pnVarPrepForDisplay(pnConfigGetVar('anonymous')).'" size="15" />' .
          '</td></tr></table>' .
          "<input type=\"hidden\" name=\"module\" value=\"" . $GLOBALS['module'] . "\" />" .
          "<input type=\"hidden\" name=\"op\" value=\"updateConfig\" />" .
          "<input type=\"hidden\" name=\"authid\" value=\"" . pnSecGenAuthKey() . "\" />" .
          "<input type=\"submit\" value=\"" . _SUBMIT . "\" />" . "</div></form>";
    CloseTable();
    include ('footer.php');
}

function user_dynamic_data()
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $currentlangfile = 'language/' . pnVarPrepForOS(pnUserGetLang()) . '/user.php';
    $defaultlangfile = 'language/' . pnVarPrepForOS(pnConfigGetVar('language')) . '/user.php';
    if (file_exists($currentlangfile)) {
        include $currentlangfile;
    } elseif (file_exists($defaultlangfile)) {
        include $defaultlangfile;
    }

    include ('header.php');
    show_menu();

    // This section displays the dynamic fields
    // and the order in which they are displayed
    OpenTable();
    print '<h2>' . _DYNAMICDATA . '</h2>'
        . '<table border="1" width="100%">' . '<tr>' . '<th>' . _FIELDACTIVE . '</th>'
        . '<th colspan="2">' . _FIELDLABEL . '</th>' . '<th>' . _FIELDWEIGHT . '</th>' . '<th>' . _FIELDTYPE . '</th>'
        . '<th>' . _FIELDLENGTH . '</th>' . '<th>' . _DELETE . '</th>'
        // .'<th>'._FIELDVALIDATION.'</th>'
        . '</tr>';

    $column = &$pntable['user_property_column'];
    $result =& $dbconn->Execute("SELECT   $column[prop_id],     $column[prop_label],  $column[prop_dtype],
                                          $column[prop_length], $column[prop_weight], $column[prop_validation]
                                 FROM     $pntable[user_property]
                                 ORDER BY $column[prop_weight]");
    if ($dbconn->ErrorNo() <> 0) {
        echo $dbconn->ErrorNo() . "List User Properties: " . $dbconn->ErrorMsg() . '<br />';
        error_log ($dbconn->ErrorNo() . "List User Properties: " . $dbconn->ErrorMsg() . '<br />');
        return;
    }
    $active_count = 0;
    $true_count = 0;
    $total_count = $result->PO_RecordCount();
    $prop_weight = 0;
    while (list($prop_id, $prop_label, $prop_dtype, $prop_length, $prop_weight, $prop_validation) = $result->fields) {
        $result->MoveNext();

        $true_count++;
        if ($prop_weight <> 0) {
            $active_count++;
            $next_prop_weight = $active_count + 1;
        }
        
       $prop_label_text = (defined($prop_label) ? constant($prop_label) : $prop_label);
      	   
        switch (true) {
            // Mandatory Images can't be disabled
            case ($prop_dtype == _UDCONST_MANDATORY):
                $img_cmd = '<img src="images/global/green_dot.gif" alt="' . _FIELD_REQUIRED . '" />';
                break;
            case ($prop_weight <> 0):
                $img_cmd = "<a href=\"admin.php?module=" . $GLOBALS['module'] . "&amp;op=deactivate_property&amp;property=$prop_id&amp;weight=$prop_weight\">"
                         . '<img src="images/global/green_dot.gif" alt="' . _FIELD_DEACTIVATE . '" />' . '</a>';
                break;
            default:
                $img_cmd = "<a href=\"admin.php?module=" . $GLOBALS['module'] . "&amp;op=activate_property&amp;property=$prop_id&amp;weight=$prop_weight\">"
                         . '<img src="images/global/red_dot.gif" alt="' . _FIELD_ACTIVATE . '" />' . '</a>';
        }

        switch ($prop_dtype) {
            case _UDCONST_MANDATORY:
                $data_type_text   = _UDT_MANDATORY;
                $data_length_text = _FIELD_NA;
                break;
            case _UDCONST_CORE:
                $data_type_text   = _UDT_CORE;
                $data_length_text = _FIELD_NA;
                break;
            case _UDCONST_STRING:
                $data_type_text   = _UDT_STRING;
                $data_length_text = $prop_length;
                break;
            case _UDCONST_TEXT:
                $data_type_text   = _UDT_TEXT;
                $data_length_text = _FIELD_NA;
                break;
            case _UDCONST_FLOAT:
                $data_type_text   = _UDT_FLOAT;
                $data_length_text = _FIELD_NA;
                break;
            case _UDCONST_INTEGER:
                $data_type_text   = _UDT_INTEGER;
                $data_length_text = _FIELD_NA;
                break;
            default:
                $data_length_text = "";
                $data_type_text   = "";
        }

        switch (true) {
            case ($active_count == 0):
                $arrows = "&nbsp;";
                break;
            case ($active_count == 1):
                $arrows = "<a href=\"admin.php?module=" . $GLOBALS['module'] . "&amp;op=increase_weight&amp;property=$prop_id&amp;weight=$prop_weight\">"
                        . '<img src="images/global/down.gif" alt="' . _DOWN . '" />' . '</a>';
                break;
            case ($true_count == $total_count):
                $arrows = "<a href=\"admin.php?module=" . $GLOBALS['module'] . "&amp;op=decrease_weight&amp;property=$prop_id&amp;weight=$prop_weight\">"
                        . '<img src="images/global/up.gif" alt="' . _UP . '" />' . '</a>';
                break;
            default:
                $arrows = '<img src="images/global/up.gif" alt="' . _UP . '" />&nbsp;<img src="images/global/down.gif" alt="' . _DOWN . '" />';
                $arrows = "<a href=\"admin.php?module=" . $GLOBALS['module'] . "&amp;op=decrease_weight&amp;property=$prop_id&amp;weight=$prop_weight\">"
                        . '<img src="images/global/up.gif" alt="' . _UP . '" />' . '</a>&nbsp;'
                        . "<a href=\"admin.php?module=" . $GLOBALS['module'] . "&amp;op=increase_weight&amp;property=$prop_id&amp;weight=$prop_weight\">"
                        . '<img src="images/global/down.gif" alt="' . _DOWN . '" />' . '</a>';
        }

        if (($prop_dtype == _UDCONST_MANDATORY) || ($prop_dtype == _UDCONST_CORE)) {
            $del_text = _FIELD_NA;
        } else {
            $del_text = "<a href=\"admin.php?module=" . $GLOBALS['module'] . "&amp;op=delete_property&amp;property=$prop_id\">" . _DELETE . '</a>';
        }
        print '<tr><td style="width:5%" align="center">' . "$img_cmd" . '</td>'
            . '<td style="width:12%">' . $prop_label . '</td>'
            . '<td style="width:12%">' . $prop_label_text . '</td>'
            . '<td style="width:10%" align="center">' . $arrows . '</td>'
            . '<td style="width:15%" align="center">' . $data_type_text . '</td>'
            . '<td style="width:10%" align="center">' . $data_length_text . '</td>'
            . '<td style="width:10%" align="center">' . $del_text . '</td>'
            // .'<td style="width:15%" align="center">'._FIELD_NA.'</td>'
            . '</tr>';
    }
    print '</table>';
    CloseTable();

    print '<br />';

    OpenTable();

    print '<h2>' . _ADDFIELD . '</h2>' .
          '<form action="admin.php" method="post"><div>'
        . '<table><tr><th align="left">' . _FIELDLABEL . ':</th>'
        . '<td><input type="text" name="label" value="" size="20" maxlength="20" />&nbsp;' . _ADDINSTRUCTIONS . '</td></tr>'
        . '<tr><th align="left">' . _FIELDTYPE . ':</th><td>'
        . '<select name="dtype">'
        . '<option value="' . _UDCONST_STRING . '">' . _UDT_STRING . '</option>' . "\n"
        . '<option value="' . _UDCONST_TEXT . '">' . _UDT_TEXT . '</option>' . "\n"
        . '<option value="' . _UDCONST_FLOAT . '">' . _UDT_FLOAT . '</option>' . "\n"
        . '<option value="' . _UDCONST_INTEGER . '">' . _UDT_INTEGER . '</option>' . "\n"
        . '</select></td></tr>'
        . '<tr><th align="left">' . _FIELDLENGTH . ':</th><td>'
        . '<input type="text" name="prop_len" value="" size="3" maxlength="3" />'
        . '&nbsp;' . _STRING_INSTRUCTIONS . '</td></tr>'
        . '<tr><td></td><td>'
        . "<input type=\"hidden\" name=\"module\" value=\"" . $GLOBALS['module'] . "\" />"
        . "<input type=\"submit\" value=\"" . _SUBMIT . "\" />"
        . '</td></tr></table>'
        . '<input type="hidden" name="prop_weight" value="' . $next_prop_weight . '" />'
        . '<input type="hidden" name="validation" value="" />'
        . "<input type=\"hidden\" name=\"authid\" value=\"" . pnSecGenAuthKey() . "\" />"
        . '<input type="hidden" name="op" value="add_property" />'
        . '</div></form>';
    CloseTable();
    include ('footer.php');
}

function add_property()
{
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }
    list ($label, $dtype, $prop_weight, $validation, $prop_len) =
    pnVarCleanFromInput('label', 'dtype', 'prop_weight', 'validation', 'prop_len');

    addVar($label, $dtype, $prop_weight, $validation, $prop_len);
    pnRedirect("admin.php?module=User&op=getDynamic");
}

function delete_property_confirm($var)
{
    // print_r($var);
    removeVar($var['label']);
    // pnRedirect("admin.php?module=User&op=getDynamic");
}

function delete_property($var)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    include('header.php');

    GraphicAdmin();
    OpenTable();
    echo '<h2>' . _USERADMIN . '</h2>';
    CloseTable();

    OpenTable();
    echo '<h2>' . _DELETEFIELD . '</h2>';

    $column = &$pntable['user_property_column'];

    $result =& $dbconn->Execute("SELECT $column[prop_id], $column[prop_label], $column[prop_weight]
                                 FROM   $pntable[user_property]
                                 WHERE  $column[prop_id] = '".pnVarPrepForStore($var['property'])."'");

    if (!$result->EOF) {
        list($pid, $plabel, $pweight) = $result->fields;
    } else {
        echo _FIELD_NOEXIST;
        CloseTable();
        include 'footer.php';
        exit;
    }
    if ($pweight != 0) {
        echo _FIELD_DEACTIVATE;
    }
    if (!pnSecAuthAction(0, 'Users::', '::', ACCESS_ADMIN)) {
        echo _MODIFYUSERSDELNOAUTH;
        CloseTable();
        include 'footer.php';
        exit;
    }
    echo _FIELD_DEL_SURE . " " . pnVarPrepForDisplay($plabel) . "?<br />" . "[ <a href=\"admin.php?module=" . $GLOBALS['module'] . "&amp;op=delPropConf&amp;label=" . $plabel . "\">" . _YES . "</a> | <a href=\"admin.php?module=" . $GLOBALS['module'] . "&amp;op=getDynamic\">" . _NO . "</a> ]</div>";
    CloseTable();
    include('footer.php');
}

/**
 * add a user variable to the database
 *
 * @access public
 * @author Gregor J. Rothfuss
 * @since 1.22 - 2002/02/01
 * @param name $ the name of the variable
 * @param type $ the type of the variable
 * @param weight $ the weight of the variable for display
 * @param validation $ the name of the validation function to apply
 * @param length $ the length of the variable for text fields
 * @returns bool
 * @return true on success, false on failure
 */
function addVar($name, $type, $weight, $validation, $length = 0)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $propertiestable = $pntable['user_property'];
    $columns = &$pntable['user_property_column'];
    // Prevent bogus entries
    if (empty($name) || (trim($name) == '') || ($name == 'uid') || ($name == 'email') ||
       ($name == 'password') || ($name == 'uname')) {
       	return false;
    }
    // Don't want duplicates either
    $query = "SELECT $columns[prop_label]
              FROM   $propertiestable
              WHERE  $columns[prop_label] = '" . pnVarPrepForStore($name) . "'";
    $result =& $dbconn->Execute($query);

    if ($result->PO_RecordCount() != 0) {
        return false;
    }
    // datatype checks
    if (($type != _UDCONST_STRING) && ($type != _UDCONST_TEXT) && ($type != _UDCONST_FLOAT) && ($type != _UDCONST_INTEGER)) {
    	return false;
    }
    // further checks
    if (($type == _UDCONST_STRING) && (!is_numeric($length) || ($length <= 0))) {
    	return false;
    }

    if (!is_numeric($weight)) {
    	return false;
    }

    $query = "INSERT INTO $propertiestable
                         ($columns[prop_label],
                          $columns[prop_dtype],
                          $columns[prop_length],
                          $columns[prop_weight],
                          $columns[prop_validation])
              VALUES     ('" . pnVarPrepForStore($name)       . "',
                          '" . pnVarPrepForStore($type)       . "',
                          '" . pnVarPrepForStore($length)     . "',
                          '" . pnVarPrepForStore($weight)     . "',
                          '" . pnVarPrepForStore($validation) . "')";
    $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    return true;
}

/**
 * remove a user variable from the database
 *
 * @access public
 * @author Gregor J. Rothfuss
 * @since 1.22 - 2002/02/01
 * @param name $ the name of the variable
 * @returns bool
 * @return true on success, false on failure
 */
function removeVar($name)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $propertiestable = $pntable['user_property'];
    $datatable = $pntable['user_data'];
    $propcolumns = &$pntable['user_property_column'];
    $datacolumns = &$pntable['userdata_column'];
    // Prevent deletion of core fields (duh)
    if (empty($name) || ($name == 'uid') || ($name == 'email') ||
       ($name == 'password') || ($name == 'uname')) {
        return false;
    }
    // get property id for cascading delete later
    $query = "SELECT $propcolumns[prop_id]
              FROM   $propertiestable
              WHERE  $propcolumns[prop_label] = '" . pnVarPrepForStore($name) . "'";
    $result =& $dbconn->Execute($query);

    if ($result->PO_RecordCount() == 0) {
        return false;
    }

    list ($id) = $result->fields;
    // Remove variable from properties
    $query = "DELETE FROM $propertiestable
              WHERE       $propcolumns[prop_label] = '" . pnVarPrepForStore($name) . "'";
    $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return false;
    }
    // Remove variable from user data
    $query = "DELETE FROM $datatable
              WHERE       $datacolumns[uda_propid] = '" . pnVarPrepForStore($id) . "'";
    $dbconn->Execute($query);
    // Temp Fix for deleting a label with no data.  Will fix after release.
    // if($dbconn->ErrorNo() != 0) {
    // return false;
    // }
    pnRedirect("admin.php?module=User&op=getDynamic");
}

function increase_weight($var)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!empty($var['property']) && !empty($var['weight'])) {
        $new_weight = $var['weight'] + 1;
        $column = &$pntable['user_property_column'];
        $result =& $dbconn->Execute("UPDATE $pntable[user_property]
                                     SET    $column[prop_weight] = '" . pnVarPrepForStore($new_weight) . "'
                                     WHERE  $column[prop_id]     = '" . pnVarPrepForStore($var['property']) . "'
                                     AND    $column[prop_weight] = '" . pnVarPrepForStore($var['weight']) . "'");
        if ($dbconn->ErrorNo() <> 0) {
            echo $dbconn->ErrorNo() . "Increase Weight 1" . $dbconn->ErrorMsg() . '<br />';
            error_log ($dbconn->ErrorNo() . "Increase Weight 1: " . $dbconn->ErrorMsg() . '<br />');
            return;
        }

        $result =& $dbconn->Execute("UPDATE $pntable[user_property]
                                     SET    $column[prop_weight] =  '" . pnVarPrepForStore($var['weight'])   . "'
                                     WHERE  $column[prop_id]     <> '" . pnVarPrepForStore($var['property']) . "'
                                     AND    $column[prop_weight] =  '" . pnVarPrepForStore($new_weight)      . "'");
        if ($dbconn->ErrorNo() <> 0) {
            echo $dbconn->ErrorNo() . "Increase Weight 2" . $dbconn->ErrorMsg() . '<br />';
            error_log ($dbconn->ErrorNo() . "Increase Weight 2: " . $dbconn->ErrorMsg() . '<br />');
            return;
        }
    }
    pnRedirect("admin.php?module=User&op=getDynamic");
}

function decrease_weight($var)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!empty($var['property']) && !empty($var['weight'])) {
        $new_weight = $var['weight'] - 1;
        $column = &$pntable['user_property_column'];
        $result =& $dbconn->Execute("UPDATE $pntable[user_property]
                                     SET    $column[prop_weight] = '" . pnVarPrepForStore($new_weight)      . "'
                                     WHERE  $column[prop_id]     = '" . pnVarPrepForStore($var['property']) . "'
                                     AND    $column[prop_weight] = '" . pnVarPrepForStore($var['weight'])   . "'");
        if ($dbconn->ErrorNo() <> 0) {
            echo $dbconn->ErrorNo() . "Decrease Weight 1" . $dbconn->ErrorMsg() . '<br />';
            error_log ($dbconn->ErrorNo() . "Decrease Weight 1: " . $dbconn->ErrorMsg() . '<br />');
            return;
        }

        $result =& $dbconn->Execute("UPDATE $pntable[user_property]
                                     SET    $column[prop_weight] =  '" . pnVarPrepForStore($var['weight'])  . "'
                                     WHERE  $column[prop_id]     <> '" . pnVarPrepForStore($var['property']) . "'
                                     AND    $column[prop_weight] =  '" . pnVarPrepForStore($new_weight)      . "'");
        if ($dbconn->ErrorNo() <> 0) {
            echo $dbconn->ErrorNo() . "Decrease Weight 2" . $dbconn->ErrorMsg() . '<br />';
            error_log ($dbconn->ErrorNo() . "Decrease Weight 2: " . $dbconn->ErrorMsg() . '<br />');
            return;
        }
    }
    pnRedirect("admin.php?module=User&op=getDynamic");
}

function activate_property ($var)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!empty($var['property'])) {
        $max_weight = 0;
        $column = &$pntable['user_property_column'];
        $result =& $dbconn->Execute("SELECT MAX($column[prop_weight]) max_weight FROM $pntable[user_property]");

        if (!$result->EOF) {
            list($max_weight) = $result->fields;
        }
        $max_weight++;
        $result =& $dbconn->Execute("UPDATE $pntable[user_property]
                                     SET    $column[prop_weight] = '" . pnVarPrepForStore($max_weight) . "'
                                     WHERE  $column[prop_id]     = '" . pnVarPrepForStore($var['property']) . "'");
        if ($dbconn->ErrorNo() <> 0) {
            echo $dbconn->ErrorNo() . "Activate User Property 1" . $dbconn->ErrorMsg() . '<br />';
            error_log ($dbconn->ErrorNo() . "Activate User Property 1: " . $dbconn->ErrorMsg() . '<br />');
            return;
        }
    }
    pnRedirect("admin.php?module=User&op=getDynamic");
}
// deactive a user property
function deactivate_property($var)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!empty($var['property'])) {
        $column = &$pntable['user_property_column'];

        $result =& $dbconn->Execute("UPDATE $pntable[user_property]
                                     SET    $column[prop_weight] = 0
                                     WHERE  $column[prop_id]     = '" . pnVarPrepForStore($var['property']) . "'");
        if ($dbconn->ErrorNo() <> 0) {
            echo $dbconn->ErrorNo() . "Deactivate User Property 1" . $dbconn->ErrorMsg() . '<br />';
            error_log ($dbconn->ErrorNo() . "Deactivate User Property 1: " . $dbconn->ErrorMsg() . '<br />');
            return;
        }
        $result =& $dbconn->Execute("UPDATE $pntable[user_property]
                                     SET    $column[prop_weight] = $column[prop_weight]-1
                                     WHERE  $column[prop_weight] > '" . pnVarPrepForStore($var['weight']) . "'");

        if ($dbconn->ErrorNo() <> 0) {
            echo $dbconn->ErrorNo() . "Deactivate User Property 2: " . $dbconn->ErrorMsg() . '<br />';
            error_log ($dbconn->ErrorNo() . "Deactivate User Property 2: " . $dbconn->ErrorMsg() . '<br />');
            return;
        }
    }
    pnRedirect("admin.php?module=User&op=getDynamic");
}

function userCheck($add_uname, $add_email)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $column = &$pntable['users_column'];

    $uname = pnVarCleanFromInput('add_uname');
    $email = pnVarCleanFromInput('add_email');
    $stop = '';

    // check for valid email
    $res1 = pnVarValidate($email, 'email');
    if ($res1 == false) {
        $stop = _ERRORINVEMAIL;
    }

    // check for valid uname
    $res2 = pnVarValidate($uname, 'uname');
    if ($res2 == false) {
        $stop = _NICK2LONG;
    }

    // check for some e-mail domains.
    list($foo, $maildomain) = split('@', $email);
    $maildomain = strtolower($maildomain);
    // get the list of banned domains
    $domains = pnConfigGetVar('reg_Illegaldomains');
    // fix any text formatting and convert to an array
    $domains = str_replace(', ', ',', $domains);
    $checkdomains = explode(',', $domains);
    // check if our main domain is amonsgt the banned list
    if (in_array($maildomain, $checkdomains)) {
       $stop = _EMAILINVALIDDOMAIN;
    }

    // check for valid username
    if (!$uname || stristr($uname,'&') || preg_match("/[[:space:]]/", $uname) || strip_tags($uname) != $uname) {
        $stop = _ERRORINVNICK;
    }

    // check for forbidden names
    $reg_illegalusername = trim(pnConfigGetVar('reg_Illegalusername'));
    if (!empty($reg_illegalusername)) {
        $usernames = explode(" ", $reg_illegalusername);
        $count = count($usernames);
        $pregcondition = "/((";
        for ($i = 0;$i < $count;$i++) {
            if ($i != $count-1) {
                $pregcondition .= $usernames[$i] . ")|(";
            } else {
                $pregcondition .= $usernames[$i] . "))/iAD";
            }
        }
        if (preg_match($pregcondition, $uname)) {
            $stop = _NAMERESERVED;
        }
    }

    // check if user already exists
    $existinguser =& $dbconn->Execute("SELECT $column[uname] FROM $pntable[users] WHERE $column[uname]='" . pnVarPrepForStore($uname) . "'");
    if (!$existinguser->EOF) {
        $stop = _NICKTAKEN;
    }
    $existinguser->Close();

    // check if email is unique (if wanted)
    if (pnConfigGetVar('reg_uniemail')) {
        $existingemail =& $dbconn->Execute("SELECT $column[email] FROM $pntable[users] WHERE $column[email]='" . pnVarPrepForStore($email) . "'");
        if (!$existingemail->EOF) {
            $stop = _EMAILREGISTERED;
        }
        $existingemail->Close();
    }
    
    return($stop);
}

function user_admin_updateRegConfig()
{
    if (!pnSecAuthAction(0, 'Users::', '::', ACCESS_ADMIN)) {
        include 'header.php';
        echo _MODIFYUSERSNOAUTH;
        include 'footer.php';
    }
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    list( $reg_uniemail,
          $reg_optitems,
          $reg_allowreg,
          $reg_noregreasons,
          $reg_verifyemail,
          $reg_notifyemail,
          $reg_Illegalusername,
          $reg_Illegaldomains,
          $reg_Illegaluseragents,
          $minage,
          $minpass,
          $idnnames,
          $login_redirect,
          $reg_question,
          $reg_answer ) = pnVarCleanFromInput('xreg_uniemail',
                                            'xreg_optitems',
                                            'xreg_allowreg',
                                            'xreg_noregreasons',
                                            'xreg_verifyemail',
                                            'xreg_notifyemail',
                                            'xreg_Illegalusername',
                                            'xreg_Illegaldomains',
                                            'xreg_Illegaluseragents',
                                            'xminage',
                                            'xminpass',
                                            'xidnnames',
                                            'xlogin_redirect',
                                            'xreg_question',
                                            'xreg_answer');

    // check input for a minimum of validity
    if(!empty($reg_notifyemail)) {
        $reg_notifyemail = (pnVarValidate($reg_notifyemail,'email')==true) ? $reg_notifyemail : '';
    }
    pnConfigSetVar('reg_notifyemail', $reg_notifyemail);

    if(($reg_uniemail==0) || ($reg_uniemail==1)) {
        pnConfigSetVar('reg_uniemail',$reg_uniemail);
    }
    if(($reg_optitems==0) || ($reg_optitems==1)) {
        pnConfigSetVar('reg_optitems',$reg_optitems);
    }
    if(($reg_allowreg==0) || ($reg_allowreg==1)) {
        pnConfigSetVar('reg_allowreg',$reg_allowreg);
    }
    if(($reg_verifyemail==0) || ($reg_verifyemail==1)) {
        pnConfigSetVar('reg_verifyemail',$reg_verifyemail);
    }
    pnConfigSetVar('reg_Illegalusername', trim($reg_Illegalusername));
    pnConfigSetVar('reg_noregreasons', $reg_noregreasons);
    pnConfigSetVar('reg_Illegaldomains', $reg_Illegaldomains);
    pnConfigSetVar('reg_Illegaluseragents', $reg_Illegaluseragents);
    if(!empty($minage)) {
        $minage = (is_numeric($minage)) ? $minage : 18;
    }
    pnConfigSetVar('minage', $minage);
    $minpass = (is_numeric($minpass)) ? $minpass : 5;
    pnConfigSetVar('minpass', $minpass);
    if(($idnnames==0) || ($idnnames==1)) {
        pnConfigSetVar('idnnames',$idnnames);
    }
    if(($login_redirect==0) || ($login_redirect==1)) {
        pnConfigSetVar('login_redirect',$login_redirect);
    }
    pnConfigSetVar('reg_question', $reg_question);
    pnConfigSetVar('reg_answer', $reg_answer);
    pnRedirect("admin.php?module=User&op=main");
}

function user_admin_getRegConfig()
{
    include ('header.php');
    // prepare vars
    $login_redirect['0'] = '';
    $login_redirect['1'] = '';
    $login_redirect[pnConfigGetVar('login_redirect')] = ' checked="checked"';
    $sel_idnnames['0'] = '';
    $sel_idnnames['1'] = '';
    $sel_idnnames[pnConfigGetVar('idnnames')] = ' checked="checked"';
    $sel_reg_uniemail['0'] = '';
    $sel_reg_uniemail['1'] = '';
    $sel_reg_uniemail[pnConfigGetVar('reg_uniemail')] = ' checked="checked"';
    $sel_reg_optitems['0'] = '';
    $sel_reg_optitems['1'] = '';
    $sel_reg_optitems[pnConfigGetVar('reg_optitems')] = ' checked="checked"';
    $sel_reg_allowreg['0'] = '';
    $sel_reg_allowreg['1'] = '';
    $sel_reg_allowreg[pnConfigGetVar('reg_allowreg')] = ' checked="checked"';
    $sel_reg_verifyemail['0'] = '';
    $sel_reg_verifyemail['1'] = '';
    $sel_reg_verifyemail[pnConfigGetVar('reg_verifyemail')] = ' checked="checked"';
    $sel_minpass['3'] = '';
    $sel_minpass['5'] = '';
    $sel_minpass['8'] = '';
    $sel_minpass['10'] = '';
    $sel_minpass[pnConfigGetVar('minpass')] = ' selected="selected"';

    show_menu();
    OpenTable();
    print '<h2>' . _REGCONF . '</h2>'
        . '<form action="admin.php" method="post"><div>'
        . '<table border="0"><tr><td>' . _UNIEMAIL . '</td><td>'
        . "<input type=\"radio\" name=\"xreg_uniemail\" value=\"1\"" . $sel_reg_uniemail['1'] . " />" . _YES . ' &nbsp;'
        . "<input type=\"radio\" name=\"xreg_uniemail\" value=\"0\"" . $sel_reg_uniemail['0'] . " />" . _NO . " &nbsp;" . _UNIEMAILDESC . "\n"
        . '</td></tr><tr><td>' . _OPTITEMS . '</td><td>'
        . "<input type=\"radio\" name=\"xreg_optitems\" value=\"1\"" . $sel_reg_optitems['1'] . " />" . _YES . ' &nbsp;'
        . "<input type=\"radio\" name=\"xreg_optitems\" value=\"0\"" . $sel_reg_optitems['0'] . " />" . _NO . " &nbsp;" . _OPTITEMSDESC . "\n"
        . '</td></tr><tr><td valign="top">' . _ALLOWREG . ' </td><td>'
        . "<input type=\"radio\" name=\"xreg_allowreg\" value=\"1\"" . $sel_reg_allowreg['1'] . " />" . _YES . ' &nbsp;'
        . "<input type=\"radio\" name=\"xreg_allowreg\" value=\"0\"" . $sel_reg_allowreg['0'] . " />" . _NO . " &nbsp;<br />\n" . _IFNO
        . "<br />&nbsp;&nbsp;&nbsp;<textarea name=\"xreg_noregreasons\" cols=\"80\" rows=\"10\">" . pnVarPrepForDisplay(pnConfigGetVar('reg_noregreasons')) . "</textarea>"
        . '</td></tr><tr><td>' . _VERIFYEMAIL . '</td><td>'
        . "<input type=\"radio\" name=\"xreg_verifyemail\" value=\"1\"" . $sel_reg_verifyemail['1'] . " />" . _YES . ' &nbsp;'
        . "<input type=\"radio\" name=\"xreg_verifyemail\" value=\"0\"" . $sel_reg_verifyemail['0'] . " />" . _NO . " &nbsp;" . _PASSBYMAIL . "\n"
        . "</td></tr><tr><td>" . _NOTIFYEMAIL . "</td><td>"
        . "<input type=\"text\" name=\"xreg_notifyemail\" value=\"" . pnVarPrepForDisplay(pnConfigGetVar('reg_notifyemail')) . "\" size=\"20\" maxlength=\"200\" />" . _NOTIFYEMAILDESC . "\n"
        . "</td></tr><tr><td>" . _ILLEGALUNAME . "</td><td>"
        . "<input type=\"text\" name=\"xreg_Illegalusername\" value=\"" . pnVarPrepForDisplay(pnConfigGetVar('reg_Illegalusername')) . "\" size=\"20\" />" . _ILLEGALUNAMEDESC . "\n"
        . '</td></tr><tr><td>' . _REG_QUESTION . "</td><td>"
        . "<input type=\"text\" name=\"xreg_question\" value=\"" . pnVarPrepForDisplay(pnConfigGetVar('reg_question')) . "\" size=\"50\" /> " . _REG_QUESTIONDESC . "\n"
        . '</td></tr><tr><td>' . _REG_ANSWER . "</td><td>"
        . "<input type=\"text\" name=\"xreg_answer\" value=\"" . pnVarPrepForDisplay(pnConfigGetVar('reg_answer')) . "\" size=\"50\" /> " . _REG_ANSWERDESC . "\n"
        . '</td></tr><tr><td>' . _ILLEGALMAILDOMAINS . "</td><td>"
        . "<textarea name=\"xreg_Illegaldomains\" rows=\"5\" cols=\"50\">" . pnVarPrepForDisplay(pnConfigGetVar('reg_Illegaldomains')) . "</textarea><br />" . _ILLEGALDOMAINDESC . "\n"
        . '</td></tr><tr><td>' . _ILLEGALUSERAGENTS . "</td><td>"
        . "<textarea name=\"xreg_Illegaluseragents\" rows=\"5\" cols=\"50\">" . pnVarPrepForDisplay(pnConfigGetVar('reg_Illegaluseragents')) . "</textarea><br />" . _ILLEGALUSERAGENTDESC . "\n"
        . '</td></tr><tr><td>' . _MINAGE . "</td><td><input type=\"text\" name=\"xminage\" value=\""
        . pnConfigGetVar('minage') . "\" size=\"2\" maxlength=\"2\" /> " . _MINAGEDESCR . "\n"
        . '</td></tr><tr><td>' . _PASSWDLEN . '</td><td>' . '<select name="xminpass" size="1">'
        . "<option value=\"3\"" . $sel_minpass['3'] . ">3</option>\n" . "<option value=\"5\"" . $sel_minpass['5'] . ">5</option>\n"
        . "<option value=\"8\"" . $sel_minpass['8'] . ">8</option>\n" . "<option value=\"10\"" . $sel_minpass['10'] . ">10</option>\n"
        . '</select>' . '</td></tr><tr><td>' . _IDNNAMES . '</td><td>'
        . "<input type=\"radio\" name=\"xidnnames\" value=\"1\"" . $sel_idnnames['1'] . " />" . _YES . ' &nbsp;'
        . "<input type=\"radio\" name=\"xidnnames\" value=\"0\"" . $sel_idnnames['0'] . " />" . _NO . " &nbsp;" . _IDNNAMESDESC . "\n"
        . '</td></tr><tr><td>' . _LOGIN_REDIRECT_WCAG . '</td><td>'
        . "<input type=\"radio\" name=\"xlogin_redirect\" value=\"1\"" . $login_redirect['1'] . " />" . _YES . ' &nbsp;'
        . "<input type=\"radio\" name=\"xlogin_redirect\" value=\"0\"" . $login_redirect['0'] . " />" . _NO . " &nbsp;" . _LOGIN_REDIRECT_DESC . "\n"
        . '</td></tr>'
        . '</table>'
        . "<input type=\"hidden\" name=\"module\" value=\"" . $GLOBALS['module'] . "\" />"
        . "<input type=\"hidden\" name=\"op\" value=\"updateRegConfig\" />" . "<input type=\"hidden\" name=\"authid\" value=\"" . pnSecGenAuthKey() . "\" />"
        . "<input type=\"submit\" value=\"" . _SUBMIT . "\" />" . "</div></form>";
    CloseTable();
    include ('footer.php');
}

function user_admin_main($var)
{
    if (!isset($var['op'])) {
        $var['op'] = '';
    }
    switch ($var['op']) {
        case "modifyUser":
            $chng_uname=pnVarCleanFromInput('chng_uname');
            modifyUser($chng_uname);
            break;

        case "updateUser":
            updateUser($var);
            break;

        case "delUser":
            $chng_uname=pnVarCleanFromInput('chng_uname');
            deleteUser($chng_uname);
            break;

        case "delUserConf":
            $del_uid=(int)pnVarCleanFromInput('del_uid');
            deleteUserConfirm($del_uid);
            break;

        case "addUser":
            list($add_uname, $add_email, $add_pass) = pnVarCleanFromInput('add_uname','add_email','add_pass');
            addUser($add_uname, $add_email, $add_pass);
            break;

        case "getConfig":
            user_admin_getConfig();
            break;

        case "getRegConfig":
            user_admin_getRegConfig();
            break;

        case "getDynamic":
            user_dynamic_data();
            break;

        case "add_property":
            add_property();
            break;

        case "delete_property":
            delete_property($var);
            break;

        case "delPropConf":
            delete_property_confirm($var);
            break;

        case "deactivate_property":
            deactivate_property ($var);
            break;

        case "activate_property":
            activate_property ($var);
            break;

        case "increase_weight":
            increase_weight ($var);
            break;

        case "decrease_weight":
            decrease_weight ($var);
            break;

        default:
            displayUsers();
            break;
    }
}

?>
