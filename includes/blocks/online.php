<?php
// File: $Id: online.php 16522 2005-07-26 18:26:23Z larsneo $
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
// Original Author of file: Francisco Burzi
// Purpose of file: count number of guests/members online
// 20/09/2001 - modified sql to cope with there being 0 members online
// ----------------------------------------------------------------------

if (strpos($_SERVER['PHP_SELF'], 'online.php')) {
  die ("You can't access this file directly...");
}

$blocks_modules['online'] = array(
    'func_display' => 'blocks_online_block',
    'text_type' => 'Online',
    'text_type_long' => 'Online',
    'allow_multiple' => false,
    'form_content' => false,
    'form_refresh' => false,
//  'support_xhtml' => true,
    'show_preview' => true
);

// Security
pnSecAddSchema('Onlineblock::', 'Block title::');

function blocks_online_block($row)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!pnSecAuthAction(0, 'Onlineblock::', "$row[title]::", ACCESS_READ)) {
        return;
    }

    $sessioninfocolumn = &$pntable['session_info_column'];
    $sessioninfotable = $pntable['session_info'];

    $activetime = time() - (pnConfigGetVar('secinactivemins') * 60);

    $content = _CURRENTLY.' ';
    if (pnConfigGetVar('anonymoussessions')) {
        $query1 = "SELECT count( 1 )
                   FROM $sessioninfotable
                   WHERE $sessioninfocolumn[lastused] > $activetime AND $sessioninfocolumn[uid] = '0'
                   GROUP BY $sessioninfocolumn[ipaddr]";
        $result1 =& $dbconn->Execute($query1);
        $numguests = $result1->RecordCount();
        $result1->Close();

        // Pluralise, fix #1832: == instead of <=
        if ($numguests == 0) {
            $guests = _GUEST0;
        } elseif ($numguests == 1) {
            $guests = _GUEST;
        } else {
            $guests = _GUESTS;
        }
        $content .= pnVarPrepForDisplay($numguests).' '.pnVarPrepForDisplay($guests).' '._AND.' ';
    }

    $query2 = "SELECT count( 1 )
               FROM $sessioninfotable
               WHERE $sessioninfocolumn[lastused] > $activetime AND $sessioninfocolumn[uid] >0
               GROUP BY $sessioninfocolumn[uid]";
    $result2 =& $dbconn->Execute($query2);
    $numusers = $result2->RecordCount();
    $result2->Close();

    // Pluralise, fix #1832: == instead of <=
    if ($numusers == 0) {
        $users = _MEMBER0;
    } elseif ($numusers == 1) {
        $users = _MEMBER;
    } else {
        $users = _MEMBERS;
    }
    $content .= pnVarPrepForDisplay($numusers).' '.pnVarPrepForDisplay($users).' '._ONLINE.".<br />\n";

    if (pnUserLoggedIn()) {
        $content .= '<br />' . _YOUARELOGGED . ' <strong>' . pnVarPrepForDisplay(pnUserGetVar('uname')) . '</strong>.<br />';
        if (pnModAvailable('Messages') && pnModAPILoad('Messages')) {
            // display private messages only when module is active
            $uid = pnUserGetVar('uid');

            $numrow = pnModAPIFunc('Messages',
                                   'user',
                                   'countitems',
                                   array('uid' => $uid));
            $unreadrow = pnModAPIFunc('Messages',
                                      'user',
                                      'countitems',
                                      array('uid' => $uid,
                                            'unread' => true));

            if ($numrow != 0) {
                $link = pnVarPrepForDisplay(pnModURL('Messages'));
                $content .= '<br />' . _YOUHAVE . " (<a href=\"$link\" title=\"" . _PRIVATEMSGS . "\">" . pnVarPrepForDisplay($numrow) . "</a>|<a href=\"$link\" title=\"" . _PRIVATEMSGNEW . "\">" . pnVarPrepForDisplay($unreadrow) . "</a>) ";
                if ($numrow == 1) {
                    $content .= _PRIVATEMSG ;
                } elseif ($numrow > 1) {
                    $content .= _PRIVATEMSGS ;
                }
            }
            $content .= '<br />';
        }
    } else {
        $content .= '<br />'._YOUAREANON.'<br />';
    }
    if (empty($row['title'])) {
        $row['title'] = _WHOSONLINE;
    }
    $row['content'] = $content;
    return themesideblock($row);
}

?>