<?php
// File: $Id: index.php 17824 2006-02-02 10:12:40Z markwest $
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
// Purpose of file:
// ----------------------------------------------------------------------
// changelog: 2002/11/19 (larsneo)
// switched backed to pnVarPrepHTMLdisplay, credits for bringing
// a potential xss exploit to our attention goes to stephan ehlert
// uncommented the extrans option since we don't store the texttype in db
// should be restored for .725. set default to html for visual editor

if (!defined('LOADED_AS_MODULE')) {
    die ("You can't access this file directly...");
}

$ModName = basename(dirname( __FILE__ ));
modules_get_language();

include_once('modules/News/funcs.php');

function modtwo($tid, $score, $reason)
{
    $reasons = pnConfigGetVar('reasons');

    echo " | <select name=\"dkn$tid\">";
    for($i=0; $i<sizeof($reasons); $i++) {
        echo "<option value=\"$score:$i\">" . pnVarPrepForDisplay($reasons[$i]) . "</option>\n";
    }
    echo "</select>";
}

function modthree($sid, $mode, $order, $thold=0)
{
    echo "<input type=\"hidden\" name=\"sid\" value=\"$sid\" />\n"
        ."<input type=\"hidden\" name=\"mode\" value=\"$mode\" />\n"
        ."<input type=\"hidden\" name=\"order\" value=\"$order\" />\n"
        ."<input type=\"hidden\" name=\"thold\" value=\"$thold\" />\n"
        ."<input type=\"hidden\" name=\"req\" value=\"moderate\" />\n"
        ."<input type=\"submit\" value=\""._MODERATE."\" />\n"
        ."<input type=\"hidden\" name=\"name\" value=\"Comments\" />\n";
}

function navbar($info, $sid, $title, $thold, $mode, $order)
{
    $bgcolor1 = $GLOBALS['bgcolor1'];
    $bgcolor2 = $GLOBALS['bgcolor2'];
    $textcolor1 = $GLOBALS['textcolor1'];
    $textcolor2 = $GLOBALS['textcolor2'];
    $pid = pnVarCleanFromInput('pid');
  if (!isset($pid)) {
    $pid = 0;
  }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $anonpost = pnConfigGetVar('anonpost');

    $result =& $dbconn->Execute("SELECT count({$pntable['comments_column']['sid']})
                                                         FROM $pntable[comments]
                                                         WHERE {$pntable['comments_column']['sid']}='".(int)pnVarPrepForStore($sid)."'");
    list($count) = $result->fields;
    if(!isset($thold)) {
        $thold=0;
    }
  echo '<a id="comments"></a>';
    echo "\n\n<!-- COMMENTS NAVIGATION BAR START -->\n\n";
    echo "<table width=\"99%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    if (!empty($info['title'])) {
        echo "<tr><td style=\"background-color:$bgcolor2\" align=\"center\"><strong>" . pnVarPrepHTMLDisplay($info['title']) . "</strong> | ";
    } else {
    echo '<tr><td>';
  }
    if(pnUserLoggedIn()) {
        echo "<a href=\"user.php?op=editcomm\">"._CONFIGURE."</a>";
    } else {
        echo "<a href=\"user.php\">"._LOGINCREATE."</a>";
    }
    if(($count==1)) {
        echo " | ".pnVarPrepForDisplay($count)." "._COMMENT."</td></tr>\n";
    } else {
        echo " | ".pnVarPrepForDisplay($count)." "._COMMENTS."</td></tr>\n";
    }
    echo "<tr><td style=\"background-color:$bgcolor1;width:100%\" align=\"center\">\n"
        ."<table border=\"0\"><tr><td>\n"
        ."<form method=\"post\" action=\"index.php\"><div>\n";
    if (pnConfigGetVar('moderate')) {    // no point in selecting threshold if site has no moderation
        echo "<label for=\"thold\">"._THRESHOLD."</label> <select name=\"thold\" id=\"thold\" tabindex=\"0\">\n"
            ."<option value=\"-1\"";
        if ($thold == -1) {
            echo " selected=\"selected\"";
        }
        echo ">-1</option>\n"
             ."<option value=\"0\"";
        if ($thold == 0) {
            echo " selected=\"selected\"";
        }
        echo ">0</option>\n"
             ."<option value=\"1\"";
        if ($thold == 1) {
            echo " selected=\"selected\"";
        }
        echo ">1</option>\n"
             ."<option value=\"2\"";
        if ($thold == 2) {
            echo " selected=\"selected\"";
        }
        echo ">2</option>\n"
             ."<option value=\"3\"";
        if ($thold == 3) {
            echo " selected=\"selected\"";
        }
        echo ">3</option>\n"
             ."<option value=\"4\"";
        if ($thold == 4) {
            echo " selected=\"selected\"";
        }
        echo ">4</option>\n"
             ."<option value=\"5\"";
        if ($thold == 5) {
            echo " selected=\"selected\"";
        }
        echo ">5</option>\n"
         ."</select>";
    }   else {
        echo "<input type=\"hidden\" name=\"thold\" value=\"0\" />\n"; // I'm not sure it should be zero here but should be ok
    }
    echo "<label for=\"mode\">"._COM_DISPLAY."</label> <select name=\"mode\" id=\"mode\" tabindex=\"0\">"
         ."<option value=\"nocomments\"";
    if ($mode == 'nocomments') {
        echo " selected=\"selected\"";
    }
    echo ">"._NOCOMMENTS."</option>\n"
         ."<option value=\"nested\"";
    if ($mode == 'nested') {
        echo " selected=\"selected\"";
    }
    echo ">"._NESTED."</option>\n"
         ."<option value=\"flat\"";
    if ($mode == 'flat') {
        echo " selected=\"selected\"";
    }
    echo ">"._FLAT."</option>\n"
         ."<option value=\"thread\"";
    if (!isset($mode) || $mode=='thread' || $mode=="") {
        echo " selected=\"selected\"";
    }
    echo ">"._THREAD."</option>\n"
         ."</select> <label for=\"order\">"._COM_ORDER."</label> <select name=\"order\" id=\"order\" tabindex=\"0\">"
         ."<option value=\"0\"";
    if (!$order) {
        echo " selected=\"selected\"";
    }
    echo ">"._OLDEST."</option>\n"
         ."<option value=\"1\"";
    if ($order == 1) {
        echo " selected=\"selected\"";
    }
    echo ">"._NEWEST."</option>\n";
  if (pnConfigGetVar('moderate')) {
    echo "<option value=\"2\"";
    if ($order == 2) {
      echo " selected=\"selected\"";
    }
    echo ">"._HIGHEST."</option>\n";
  }
  echo ""
   ."</select>\n"
   ."<input type=\"hidden\" name=\"name\" value=\"News\" />\n"
   ."<input type=\"hidden\" name=\"file\" value=\"article\" />\n"
   ."<input type=\"hidden\" name=\"sid\" value=\"$sid\" />\n"
   ."<input type=\"submit\" value=\""._REFRESH."\" /></div></form>\n"
   ."</td></tr>";

    if (pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_COMMENT)) {
        echo "<tr><td style=\"background-color:$bgcolor1\" align=\"center\"><form action=\"index.php\" method=\"get\"><div>"
            ."<input type=\"hidden\" name=\"name\" value=\"Comments\" />"
            ."<input type=\"hidden\" name=\"pid\" value=\"$pid\" />"
            ."<input type=\"hidden\" name=\"sid\" value=\"$sid\" />"
            ."<input type=\"hidden\" name=\"req\" value=\"Reply\" />"
            ."&nbsp;&nbsp;<input type=\"submit\" value=\""._REPLYMAIN."\" />"
            ."</div></form>"
      ."</td></tr>";
    }
    echo "</table>\n"
        ."</td></tr>"
        ."<tr><td style=\"background-color:$bgcolor2\" align=\"center\"><span class=\"pn-sub\">"._COMMENTSWARNING."</span></td></tr>\n"
        ."</table>"
        ."\n\n<!-- COMMENTS NAVIGATION BAR END -->\n\n";
}

function DisplayKids ($tid, $mode, $order=0, $thold=0, $level=0, $dummy=0, $tblwidth=99)
{
    $bgcolor1 = $GLOBALS['bgcolor1'];
    $bgcolor2 = $GLOBALS['bgcolor2'];
    //$info = pnVarCleanFromInput('info');

    $modoption= pnConfigGetVar('moderate');
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $reasons = pnConfigGetVar('reasons');
    $anonymous = pnConfigGetVar('anonymous');
    $anonpost = pnConfigGetVar('anonpost');
    $commentlimit = pnConfigGetVar('commentlimit');

    $comments = 0;

    $noshowscore = pnUserGetVar('noscore');
    $maxcomments = pnUserGetVar('commentmax');

    $column = &$pntable['comments_column'];

    $sql = "SELECT $column[tid], $column[pid], $column[sid],
                              $column[date],
                              $column[name], $column[email], $column[url],
                              $column[host_name], $column[subject], $column[comment],
                              $column[score], $column[reason]
                              FROM $pntable[comments]
                              WHERE $column[pid] = '".(int)pnVarPrepForStore($tid)."'
                              ORDER BY $column[tid]";
    // 2 most popular first
    // 1 newest first
    // 0 - oldest first

    if($order==1) {
      $sql .= ", $column[date] DESC";
    }
    if($order==0) {
      $sql .= ", $column[date] ASC";
    }
    if($order==2) {
      $sql .= ", $column[score] DESC";
    }
    $result =& $dbconn->Execute($sql);

    if ($mode == 'nested') {
        /* without the tblwidth variable, the tables run of the screen with netscape */
        /* in nested mode in long threads so the text can't be read. */
        while(list($r_tid, $r_pid, $r_sid, $r_date, $r_name, $r_email, $r_url, $r_host_name, $r_subject, $r_comment, $r_score, $r_reason) = $result->fields) {
            $r_date=$result->UnixTimeStamp($r_date);
            $result->MoveNext();
            if($r_score >= $thold) {
                if (!isset($level)) {
                } else {
                    if (!$comments) {
                        echo "<ul>";
                        $tblwidth -= 5;
                    }
                }
                $comments++;
        if ($r_name == "") {
          $r_name = $anonymous;
        }
        if ($r_subject == "") {
          $r_subject = "["._NOSUBJECT."]";
        }

                // HIJO enter hex color between first two appostrophe for second alt bgcolor
                //echo "dummy: $dummy<br />";
                $r_bgcolor = ($dummy%2)?"$bgcolor1":"$bgcolor2";
        echo '<li style="list-style:none">';
                echo "<a id=\"tid$r_tid\"></a>";
                echo "<table border=\"0\"><tr style=\"background-color:$r_bgcolor\"><td>";
                $datetime = ml_ftime(_DATETIMEBRIEF, GetUserTime($r_date));
                if ($r_email) {
                    echo '<h2>'.pnVarPrepForDisplay($r_subject).'</h2>';

                    if (!$noshowscore && $modoption ) {
                        echo "("._SCORE." $r_score";
                        if($r_reason>0) echo ", ".pnVarPrepForDisplay($reasons[$r_reason])."";
                        echo ")";
                    }
                    echo "<br /> "._BY." <a href=\"mailto:$r_email\">".pnVarPrepForDisplay($r_name)."</a>"
                        ." (".pnVarPrepForDisplay($r_email).")<span class=\"pn-sub\"> "._ON." ".pnVarPrepForDisplay($datetime)."</span>";
                } else {
                    echo '<h2>'.pnVarPrepForDisplay($r_subject).'</h2>';
                    if (!$noshowscore && $modoption ) {
                        echo "("._SCORE." ".pnVarPrepForDisplay($r_score)."";
                        if($r_reason>0) echo ", ".pnVarPrepForDisplay($reasons[$r_reason])."";
                        echo ")";
                    }
                    echo '<br />'._BY." ".pnVarPrepForDisplay($r_name)." "._ON." ".pnVarPrepForDisplay($datetime);
                }
                if ($r_name != $anonymous) {
                  echo '<br />(<a href="user.php?op=userinfo&amp;uname=' . pnVarPrepForDisplay($r_name) . '">' . _USERINFO . '</a>';
                    if (pnModAvailable('Messages')) {
                        echo ' | <a href="' . pnVarPrepForDisplay(pnModURL('Messages', 'user', 'compose', array('uname' => $r_name))).'">'._SENDAMSG.'</a>';
                    }
                }
                if (eregi("http://",$r_url)) {
                  echo "<a href=\"$r_url\">$r_url</a> ";
                }
                echo "</td></tr><tr><td>";
        //apply transform hooks - markwest
        list($r_comment) = pnModCallHooks('item', 'transform', '', array($r_comment, 'module' => 'Comments'));
                if(($maxcomments) && (strlen($r_comment) > $maxcomments)) {
                  echo substr("$r_comment", 0, $maxcomments)."<br /><a href=\"index.php?name=Comments&amp;sid=$r_sid&amp;tid=$r_tid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">"._READREST."</a>";
                } elseif(strlen($r_comment) > $commentlimit) {
                  echo substr("$r_comment", 0, $commentlimit)."<br /><strong><a href=\"index.php?name=Comments&amp;sid=$r_sid&amp;tid=$r_tid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">"._READREST."</a>";
                }
                else echo pnVarPrepHTMLDisplay($r_comment);
                echo "</td></tr></table><br /><div style=\"text-align:center\">";
                if ($anonpost==1 OR pnUserLoggedIn()) {
                    echo " [ <a href=\"index.php?name=Comments&amp;req=Reply&amp;pid=$r_tid&amp;sid=$r_sid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">"._REPLY."</a>";
                } else {
                    echo "[ "._NOANONCOMMENTS." ";
                }
                if (pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_MODERATE)) {
                    if ($modoption) modtwo($r_tid, $r_score, $r_reason);
                }
                if (pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_DELETE)) {
                    echo " | <a href=\"admin.php?module=Comments&amp;op=RemoveComment&amp;tid=$r_tid&amp;sid=$r_sid\">" ._DELETE."</a>";
                }
                echo " ]</div><br />\n";
                DisplayKids($r_tid, $mode, $order, $thold, $level+1, $dummy+1, $tblwidth);
        echo '</li>';
        if (!isset($level)) {
        } else {
          if ($comments) {
            echo "</ul>";
          }
        }
            }
        }
    } elseif ($mode == 'flat') {
      while(list($r_tid, $r_pid, $r_sid, $r_date, $r_name, $r_email, $r_url, $r_host_name, $r_subject, $r_comment, $r_score, $r_reason) = $result->fields) {
          $r_date=$result->UnixTimeStamp($r_date);
      $result->MoveNext();
      if($r_score >= $thold) {
        if ($r_name == "") {
          $r_name = $anonymous;
        }
        if (!$r_subject == "" ) {
          $r_subject = "["._NOSUBJECT."]";
        }
        echo "<a id=\"tid$r_tid\"></a>";
        echo "<hr /><table width=\"99%\" border=\"0\"><tr style=\"background-color:$bgcolor1\"><td>";
        $datetime = ml_ftime(_DATETIMEBRIEF, GetUserTime($r_date));
        if ($r_email) {
          echo '<h2>'.pnVarPrepForDisplay($r_subject).'</h2>';
          if (!$noshowscore && $modoption ) {
              echo "("._SCORE." ".pnVarPrepForDisplay($r_score)."";
              if($r_reason>0) echo ", ".pnVarPrepForDisplay($reasons[$r_reason])."";
              echo ")";
          }
          echo '<br />'._BY." <a href=\"mailto:$r_email\">".pnVarPrepForDisplay($r_name)."</a> (".pnVarPrepForDisplay($r_email).")<span class=\"pn-sub\"> "._ON." ".pnVarPrepForDisplay($datetime)."</span>";
        } else {
          echo '<h2>'.pnVarPrepForDisplay($r_subject).'</h2>';
          if (!$noshowscore && $modoption ) {
              echo "("._SCORE." ".pnVarPrepForDisplay($r_score)."";
              if($r_reason>0) echo ", ".pnVarPrepForDisplay($reasons[$r_reason])."";
              echo ")";
          }
          echo '<br />'._BY." ".pnVarPrepForDisplay($r_name)." "._ON." ".pnVarPrepForDisplay($datetime);
        }
        if ($r_name != $anonymous) {
                  echo '<br />(<a href="user.php?op=userinfo&amp;uname=' . pnVarPrepForDisplay($r_name) . '">' . _USERINFO . '</a>&nbsp;';
                    if (pnModAvailable('Messages')) {
                        echo ' | <a href="' . pnVarPrepForDisplay(pnModURL('Messages', 'user', 'compose', array('uname' => $r_name))).'">'._SENDAMSG.'</a>&nbsp;';
                    }
        }
        if (eregi("http://",$r_url)) {
          echo "<a href=\"$r_url\">".pnVarPrepForDisplay($r_url)."</a>)";
        }
        echo "</td></tr><tr><td>";
        //apply transform hooks - markwest
        list($r_comment) = pnModCallHooks('item', 'transform', '', array($r_comment, 'module' => 'Comments'));
        if(($maxcomments) && (strlen($r_comment) > $maxcomments)) {
          echo substr("$r_comment", 0, $maxcomments)."<br /><a href=\"index.php?name=Comments&amp;sid=$r_sid&amp;tid=$r_tid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">"._READREST."</a>";
        } elseif(strlen($r_comment) > $commentlimit) {
          echo substr("$r_comment", 0, $commentlimit)."<br /><strong><a href=\"index.php?name=Comments&amp;sid=$r_sid&amp;tid=$r_tid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">"._READREST."</a>";
        } else {
          echo $r_comment;
        }
        echo "</td></tr></table><br />";
        echo "<div style=\"text-align:center\">";
        if ($anonpost==1 OR pnUserLoggedIn()) {
          echo " [ <a href=\"index.php?name=Comments&amp;req=Reply&amp;pid=$r_tid&amp;sid=$r_sid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">"._REPLY."</a>";
        } else {
          echo "[ "._NOANONCOMMENTS." ";
        }
        if (pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_MODERATE)) {
          if ($modoption) modtwo($r_tid, $r_score, $r_reason);
        }
        if (pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_DELETE)) {
          echo " | <a href=\"admin.php?module=Comments&amp;op=RemoveComment&amp;tid=$r_tid&amp;sid=$r_sid\">"
          ._DELETE."</a>";
        }
        echo " ]</div><br />";
        DisplayKids($r_tid, $mode, $order, $thold);
      }
    }
    } else {
    while(list($r_tid, $r_pid, $r_sid, $r_date, $r_name, $r_email, $r_url, $r_host_name, $r_subject, $r_comment, $r_score, $r_reason) = $result->fields) {
      $r_date=$result->UnixTimeStamp($r_date);
      $result->MoveNext();
      if($r_score >= $thold) {
        if (!isset($level)) {
        } else {
          if (!$comments) {
            echo "<ul>";
          }
        }
        $comments++;
        if ($r_name == "") {
          $r_name = $anonymous;
        }
        if ($r_subject == "") {
          $r_subject = "["._NOSUBJECT."]";
        }
        $datetime = ml_ftime(_DATETIMEBRIEF, GetUserTime($r_date));
        echo "<li><a href=\"index.php?name=Comments&amp;req=showreply&amp;tid=$r_tid&amp;sid=$r_sid&amp;pid=$r_pid&amp;mode=$mode&amp;order=$order&amp;thold=$thold#tid$r_tid\">$r_subject</a><span class=\"pn-sub\"> "._BY." ".pnVarPrepForDisplay($r_name)." "._ON." ".pnVarPrepForDisplay($datetime)."</span>";
        DisplayKids($r_tid, $mode, $order, $thold, $level+1, $dummy+1);
        echo '</li>';
        if (!isset($level)) {
        } else {
          if ($comments) {
            echo "</ul>";
          }
        }
      }
    }
  }
}

function DisplayBabies ($tid, $level=0, $dummy=0)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $anonymous = pnConfigGetVar('anonymous');

    $comments = 0;
    $column = &$pntable['comments_column'];
    $result =& $dbconn->Execute("SELECT $column[tid], $column[pid], $column[sid],
                              $column[date], $column[name],
                              $column[email], $column[url], $column[host_name],
                              $column[subject], $column[comment], $column[score],
                              $column[reason]
                              FROM $pntable[comments]
                              WHERE $column[pid] = '".(int)pnVarPrepForStore($tid)."'
                              ORDER BY $column[date], $column[tid]");
    while(list($r_tid, $r_pid, $r_sid, $r_date, $r_name, $r_email, $r_url, $r_host_name, $r_subject, $r_comment, $r_score, $r_reason) = $result->fields) {
        $r_date=$result->UnixTimeStamp($r_date);
        $result->MoveNext();
        if (!isset($level)) {
        } else {
            if (!$comments) {
                echo "<ul>";
            }
        }
        $comments++;
        if (!eregi("[a-z0-9]",$r_name)) { $r_name = $anonymous; }
        if (!eregi("[a-z0-9]",$r_subject)) { $r_subject = "["._NOSUBJECT."]"; }
        $datetime = ml_ftime(_DATETIMEBRIEF, GetUserTime($r_date));
        echo "<a href=\"index.php?name=Comments&amp;req=showreply&amp;tid=$r_tid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">$r_subject</a> "._BY." ".pnVarPrepForDisplay($r_name)." "._ON." ".pnVarPrepForDisplay($datetime).'<br />';
        DisplayBabies($r_tid, $level+1, $dummy+1);
    }
    if ($level && $comments) {
    echo "</ul>";
    }
}

function DisplayTopic ($info, $sid, $pid=0, $tid=0, $mode="thread", $order=0, $thold=0, $level=0, $nokids=0)
{
    $bgcolor1 = $GLOBALS['bgcolor1'];
    $bgcolor2 = $GLOBALS['bgcolor2'];
    $bgcolor3 = $GLOBALS['bgcolor3'];

    list($hr,
       $datetime,
       $mainfile,
       $foot1,
       $subject,
       $title) = pnVarCleanFromInput('hr',
               'datetime',
               'mainfile',
               'foot1',
               'subject',
               'title');

    $modoption= pnConfigGetVar('moderate');
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $reasons = pnConfigGetVar('reasons');
    $anonymous = pnConfigGetVar('anonymous');
    $anonpost = pnConfigGetVar('anonpost');
    $commentlimit = pnConfigGetVar('commentlimit');

    if($mainfile) {
        include 'header.php';
    }
    if($pid != 0) {
        include 'header.php';
    }

    $noshowscore = pnUserGetVar('noscore');
    $maxcomments = pnUserGetVar('commentmax');

    $column = &$pntable['comments_column'];
    /*
  $selectcolumns = array ('tid' => 0,
                            'pid' => 0,
                            'sid' => 0,
                            'name' => 0,
                            'email' => 0,
                            'url' => 0,
                            'host_name' => 0,
                            'subject' => 0,
                            'comment' => 0,
                            'score' => 0,
                            'reason' => 0 );
    $q = "SELECT ";
    $q .= getColumnsViaHashKeys('comments', $selectcolumns);
    $q .= ", $column[date]" ;
    $q .= " FROM $pntable[comments] WHERE $column[sid]='".(int)pnVarPrepForStore($sid)."' AND $column[pid]='".(int)pnVarPrepForStore($pid)."'";
  */
  $q = "SELECT $column[tid],
        $column[pid],
        $column[sid],
        $column[name],
        $column[email],
        $column[url],
        $column[host_name],
        $column[subject],
        $column[comment],
        $column[score],
        $column[reason],
        $column[date]
      FROM $pntable[comments]
      WHERE $column[sid]='".(int)pnVarPrepForStore($sid)."'
      AND $column[pid]='".(int)pnVarPrepForStore($pid)."'";
    if($thold != "") {
        $q .= " AND $column[score] >= '".(int)pnVarPrepForStore($thold)."'";
    } else {
        $q .= " AND $column[score] >= 0";
    }

    // 2 most popular first
    // 1 newest first
    // 0 - oldest first

    if ($order==1) {
      $q .= " ORDER BY $column[date] DESC";
    }
    if ($order==0) {
      $q .= " ORDER BY $column[date] ASC";
    }
    if ($order==2) {
      $q .= " ORDER BY $column[score] DESC";
    }

  // I've set $bruce to $sid because $sid was getting corrupted later.
  // I can't figure out where. - skribe

    $bruce = $sid;

    $something =& $dbconn->Execute($q);

    navbar($info, $sid, $title, $thold, $mode, $order);
    echo "<div style=\"text-align:left\">";
    if (pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_COMMENT)) {
//        echo "<form action=\"index.php\" method=\"post\"><div>";
    }
  if (pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_MODERATE)) {
    echo "<form style=\"display:inline\" method=\"post\" action=\"index.php\"><div style=\"display:inline\" >\n";
  }
    while(list($tid, $spid, $sid, $name, $email, $url, $host_name, $subject, $comment, $score, $reason, $date) = $something->fields) {
        $date = $something->UnixTimeStamp($date);
        $something->MoveNext();
        if ($name == "") {
          $name = $anonymous;
      }
      if ($subject == "") {
          $subject = "["._NOSUBJECT."]";
      }
        echo "<a id=\"tid$tid\"></a>\n";
        echo "<table width=\"99%\" border=\"0\">\n<tr style=\"background-color:$bgcolor1\">\n<td>\n";
        $datetime = ml_ftime(_DATETIMEBRIEF, GetUserTime($date));
        if ($email) {
            echo "<h2>$subject </h2>\n";
            if(!$noshowscore && $modoption ) {
                echo "("._SCORE." $score";
                if($reason>0) echo ", ".pnVarPrepForDisplay($reasons[$reason])."";
                echo ")\n";
            }
            echo '<br />'._BY." <a href=\"mailto:$email\">".pnVarPrepForDisplay($name)."</a> (".pnVarPrepForDisplay($email).")\n<span class=\"pn-sub\"> "._ON." ".pnVarPrepForDisplay($datetime)."</span>";
        } else {
            echo '<h2>'.pnVarPrepForDisplay($subject)."</h2>\n";
            if(!$noshowscore && $modoption ) {
                echo "("._SCORE." $score";
                if($reason>0) echo ", ".pnVarPrepForDisplay($reasons[$reason])."";
                echo ")\n";
            }
            echo "<br />\n"._BY." ".pnVarPrepForDisplay($name)." "._ON." ".pnVarPrepForDisplay($datetime);
        }

        /* If you are admin you can see the Poster IP address (you have this right, no?) */
        /* with this you can see who is flaming you... ha-ha-ha */

        if($name != $anonymous) {
          echo '<br />(<a href="user.php?op=userinfo&amp;uname=' . pnVarPrepForDisplay($name) . '">' . _USERINFO . '</a>&nbsp;';
            if (pnModAvailable('Messages')) {
                echo ' | <a href="' . pnVarPrepForDisplay(pnModURL('Messages', 'user', 'compose', array('uname' => $name))).'">'._SENDAMSG.'</a>&nbsp;';
            }
      }
        if(eregi("http://",$url)) {
          echo "<a href=\"$url\">".pnVarPrepForDisplay($url)."</a>)\n";
      }
        if (pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_ADMIN)) {
            $column = &$pntable['comments_column'];
            $result=& $dbconn->Execute("SELECT $column[host_name]
                                     FROM $pntable[comments]
                                     WHERE $column[tid]='".(int)pnVarPrepForStore($tid)."'");
            list($host_name) = $result->fields;
            echo "<br />\n(IP: ".pnVarPrepForDisplay($host_name).")\n";
        }

        echo "\n</td>\n</tr>\n<tr>\n<td>\n";
    // transform hooks - markwest
    list($comment) = pnModCallHooks('item', 'transform', '', array($comment, 'module' => 'Comments'));
        if(($maxcomments) && (strlen($comment) > $maxcomments)) echo substr("".pnVarPrepHTMLDisplay($comment)."", 0, $maxcomments)."<br />\n<strong><a href=\"index.php?name=Comments&amp;sid=$sid&tid=$tid&mode=$mode&order=$order&thold=$thold\">"._READREST."</a></strong>\n";
        elseif(strlen($comment) > $commentlimit) echo substr("".pnVarPrepHTMLDisplay($comment)."", 0, $commentlimit)."<br />\n<strong><a href=\"index.php?name=Comments&amp;sid=$sid&tid=$tid&mode=$mode&order=$order&thold=$thold\">"._READREST."</a></strong>\n";
        else echo pnVarPrepHTMLDisplay($comment)."\n";
        echo "</td>\n</tr>\n</table><br />\n";
    echo "<div style=\"text-align:center\">";
        if (pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_COMMENT)) {
            echo " [ <a href=\"index.php?name=Comments&amp;req=Reply&amp;pid=$tid&amp;sid=$sid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">"._REPLY."</a>\n";

            if ($spid != 0) {
                $column = &$pntable['comments_column'];
                $pidResult =& $dbconn->Execute("SELECT $column[pid]
                                             FROM $pntable[comments]
                                             WHERE $column[tid]='".(int)pnVarPrepForStore($spid)."'");
                $showrepl = "";
                list($erin) = $pidResult->fields;

                if ($erin != 0) $showrepl = "req=showreply&amp;tid=$spid&amp;";
                echo " | <a href=\"index.php?name=Comments&amp;".$showrepl."sid=$sid&amp;pid=$erin&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">"._PARENT."</a>";
            }
      if (pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_MODERATE)) {
                if ($modoption) modtwo($tid, $score, $reason);
        $column = &$pntable['comments_column'];
        $result2 =& $dbconn->Execute("SELECT count(*) FROM $pntable[comments] WHERE $column[sid]='".(int)pnVarPrepForStore($bruce)."'");
        list($numrow) = $result2->fields;
        if ($numrow == 0) {
          echo '';
        } else {
          if ($modoption) modthree($bruce, $mode, $order, $thold);
        }
      }

            if (pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_DELETE)) {
                echo " | <a href=\"admin.php?module=Comments&amp;op=RemoveComment&amp;tid=$tid&amp;sid=$sid\">"
                ._DELETE."</a> ]<br />\n\n";
            } else {
                echo " ]<br />\n\n";
            }
        }
        echo '</div>';
        DisplayKids($tid, $mode, $order, $thold, $level);
        if($hr) echo "<hr />";
  // $sid changes value between here
  }
  // and here - skribe
  /**
   * I've changed $sid to $bruce below (until the end of the function) so
   * so that moderation will work.
   * - skribe
   */

  if (pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_MODERATE)) {
    echo "</div></form>";
  }
  echo '</div>';
  if($pid==0) {
        return array($bruce, $pid, $subject);
    } else {
        include 'footer.php';
    }
// $sid to $bruce ends here - skribe
}

function singlecomment($info, $tid, $sid, $mode, $order, $thold)
{
    //global $bgcolor1, $bgcolor2, $bgcolor3, $bgcolor4;
    $bgcolor1 = $GLOBALS['bgcolor1'];
    $bgcolor2 = $GLOBALS['bgcolor2'];
    $bgcolor3 = $GLOBALS['bgcolor3'];
    $bgcolor4 = $GLOBALS['bgcolor4'];

    //$datetime = pnVarCleanFromInput('datetime');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $anonpost = pnConfigGetVar('anonpost');

    include'header.php';

    $column = &$pntable['comments_column'];
    $result =& $dbconn->Execute("SELECT $column[date],
                              $column[name], $column[email], $column[url],
                              $column[subject], $column[comment], $column[score],
                              $column[reason]
                              FROM $pntable[comments]
                              WHERE $column[tid]='".(int)pnVarPrepForStore($tid)."' AND $column[sid]='".(int)pnVarPrepForStore($sid)."'");
    list($date, $name, $email, $url, $subject, $comment, $score, $reason) = $result->fields;
    $date=$result->UnixTimeStamp($date);
    $titlebar = '<h2>'.pnVarPrepForDisplay($subject).'</h2>';
    if(empty($name)) {
      $name = $anonymous;
    }
    if(empty($subject)) {
      $subject = "["._NOSUBJECT."]";
    }
    if (pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_COMMENT)) {
        echo "<form action=\"index.php\" method=\"post\"><div>";
    }
    echo "<table width=\"99%\" border=\"0\"><tr style=\"background-color:$bgcolor1\"><td width=\"500\">";
    $datetime = ml_ftime(_DATETIMEBRIEF, GetUserTime($date));
    if($email) {
        echo '<h2>'.pnVarPrepForDisplay($subject)."</h2>("._SCORE." ".pnVarPrepForDisplay($score).")<br />"._BY." <a href=\"mailto:$email\">".pnVarPrepForDisplay($name)."</a>(".pnVarPrepForDisplay($email).")<span class=\"pn-sub\"> "._ON." ".pnVarPrepForDisplay($datetime)."</span>";
    } else {
        echo '<h2>'.pnVarPrepForDisplay($subject)."</h2>("._SCORE." ".pnVarPrepForDisplay($score).")<br />"._BY." ".pnVarPrepForDisplay($name)."<span class=\"pn-sub\"> "._ON." ".pnVarPrepForDisplay($datetime)."</span>";
    }
    echo "</td></tr><tr><td>".pnVarPrepHTMLDisplay($comment)."</td></tr></table><br />";
    if (pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_COMMENT)) {
        echo " [ <a href=\"index.php?name=Comments&amp;req=Reply&amp;pid=$tid&amp;sid=$sid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">"._REPLY."</a> | <a href=\"index.php?name=News&amp;file=article&amp;sid=$sid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">"._ROOT."</a>";
    }
    if (pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_MODERATE)) {
       if ($modoption) modtwo($tid, $score, $reason);
       echo " ]";
       if ($modoption) modthree($sid, $mode, $order, $thold);
    }
    include 'footer.php';
}

function reply()
{
    $bgcolor1 = $GLOBALS['bgcolor1'];
    $bgcolor2 = $GLOBALS['bgcolor2'];
    $bgcolor3 = $GLOBALS['bgcolor3'];

    list($pid,
         $sid,
         $mode,
         $order,
         $thold,
         $email,
         $comment,
         $temp_comment,
         $datetime) = pnVarCleanFromInput('pid',
                                       'sid',
                                       'mode',
                                       'order',
                                       'thold',
                                       'email',
                                       'comment',
                                       'temp_comment',
                                       'datetime');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

  if (!isset($pid)) {
    $pid = 0;
  }

    include 'header.php';

  $sid = (int)pnVarPrepForStore($sid);
    $row = getArticles("pn_sid=$sid", "", "");
    $info = genArticleInfo($row[0]);

    if($pid !=0) {
    $column = &$pntable['comments_column'];
    $result =& $dbconn->Execute("SELECT $column[date], $column[name], $column[email], $column[url], $column[subject], $column[comment],
                     $column[score] FROM $pntable[comments] WHERE $column[tid]='".(int)pnVarPrepForStore($pid)."'");
    // modification mouzaia
    // if not there, a warnin on line 654.
    $comment2 = "";
    list($date, $name, $email, $url, $subject, $comment, $score) = $result->fields;
    ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $date, $datetime);
    $date = ml_ftime(""._DATETIMEBRIEF."", mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));

    } else {
        $date = $info['briefdatetime'];
        $subject = $info['title'];
        $temp_comment = $info['hometext'];
        $comment2 = $info['bodytext'];
        $name = $info['informant'];
        $notes = $info['notes'];
    }
    if(empty($comment)) {
        $comment = "$temp_comment<br />$comment2";
    }
  OpenTable();
  echo '<h2>'._COMMENTREPLY.'</h2>';
    CloseTable();

    OpenTable();
    if ((!pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_COMMENT)) ||
         !pnSecAuthAction(0, 'Topics::Topic', "$info[topicname]::$info[tid]", ACCESS_READ)) {
    echo _NOTAUTHORIZEDCOMMENT;
      CloseTable();
        include 'footer.php';
        exit;
    }
    $anonymous = pnConfigGetVar('anonymous');
    if(empty($name)) {
      $name = $anonymous;
    }
    if($subject == "") {
      $subject = "["._NOSUBJECT."]";
    }
    //formatTimestamp($date);
    echo '<h2>' . pnVarPrepHTMLDisplay($subject) . '</h2>';
    if(!isset($temp_comment)) {
      echo " ("._SCORE." ".pnVarPrepForDisplay($score).")";
    }
    if($email) {
        echo '<br />'._BY." <a href=\"mailto:$email\">".pnVarPrepForDisplay($name)."</a> (".pnVarPrepForDisplay($email).")<span class=\"pn-sub\"> "._ON." ".pnVarPrepForDisplay($date)."</span>";
    } else {
        echo "<br /><span class=\"pn-sub\">"._BY." ".pnVarPrepForDisplay($name)." "._ON." ".pnVarPrepForDisplay($date)."</span>";
    }
    echo '<br />' . pnVarPrepHTMLDisplay($comment) . '<br />';
    if ($pid == 0) {
        if (!empty($notes)) {
            echo '<strong>'._NOTE.'</strong> '.pnVarPrepHTMLDisplay($notes).'<br />';
        } else {
            echo "";
      }
    }

    if(!isset($pid) || !isset($sid)) { echo _NOTRIGHT; exit(); }
  if($pid == 0) {
    $column = &$pntable['stories_column'];
    $result =& $dbconn->Execute("SELECT $column[title]
                  FROM $pntable[stories]
                  WHERE $column[sid]='".(int)pnVarPrepForStore($sid)."'");
    list($subject) = $result->fields;
  } else {
    $column = &$pntable['comments_column'];
    $result =& $dbconn->Execute("SELECT $column[subject]
                  FROM $pntable[comments]
                  WHERE $column[tid]='".(int)pnVarPrepForStore($pid)."'");
    list($subject) = $result->fields;
    }
    CloseTable();

    OpenTable();
    echo "<form action=\"index.php\" method=\"post\"><div>";
    echo '<h2>'._YOURNAME.":</h2> ";
    if (pnUserLoggedIn()) {
        echo "<a href=\"user.php\">" . pnUserGetVar('uname') . "</a> [ <a href=\"user.php?module=User&amp;op=logout\">"._LOGOUT."</a> ]<br />";
    } else {
            echo pnVarPrepForStore($anonymous);
        echo " [ <a href=\"user.php\">"._NEWUSER."</a> ]<br />";
    }
    echo '<h2>'._SUBJECT.':</h2>';

    if (!stristr($subject, 'Re:')) {
        $subject = 'Re: '.$subject;
    }

    echo "<input type=\"text\" name=\"subject\" size=\"50\" maxlength=\"85\" value=\"" . pnVarPrepForDisplay(pnVarCensor($subject)) . "\" /><br />";
    echo '<h2>'._COMMENT.':</h2>'
         ."<textarea wrap=\"virtual\" cols=\"80\" rows=\"10\" name=\"comment\"></textarea><br />"
         ."<span class=\"pn-sub\">"._ALLOWEDHTML."&nbsp;";

    $AllowableHTML = pnConfigGetVar('AllowableHTML');
    while (list($key, $access, ) = each($AllowableHTML)) {
        if ($access > 0) echo " &lt;".$key."&gt;";
    }

    echo "</span><br />";
    if (pnConfigGetVar('anonpost')) {
        if (pnUserLoggedIn()) {
            echo "<input type=\"checkbox\" name=\"xanonpost\">"._POSTANON.'<br />';
        }
    }
    echo "<input type=\"hidden\" name=\"name\" value=\"Comments\" />\n"
        ."<input type=\"hidden\" name=\"pid\" value=\"$pid\" />\n"
        ."<input type=\"hidden\" name=\"sid\" value=\"$sid\" />\n"
        ."<input type=\"hidden\" name=\"mode\" value=\"$mode\" />\n"
        ."<input type=\"hidden\" name=\"order\" value=\"$order\" />\n"
        ."<input type=\"hidden\" name=\"thold\" value=\"$thold\" />\n"
        ."<input type=\"submit\" name=\"req\" value=\""._PREVIEW."\" />\n"
        ."<input type=\"submit\" name=\"req\" value=\""._OK."\" />\n"
        ."<select name=\"posttype\">\n";
    // extrans not stored in DB - should be fixed for .725
    //."<option value=\"exttrans\">"._EXTRANS."</option>\n"
    echo "<option value=\"plaintext\" selected=\"selected\">"._PLAINTEXT."</option>\n"
        ."<option value=\"html\">"._HTMLFORMATED."</option>\n"
        ."</select></div></form>\n";
    CloseTable();
    include 'footer.php';
}

function replyPreview()
{
    list($pid,
         $sid,
         $subject,
         $comment,
         $xanonpost,
         $mode,
         $order,
         $thold,
         $posttype) = pnVarCleanFromInput('pid',
                                          'sid',
                                          'subject',
                                          'comment',
                                          'xanonpost',
                                          'mode',
                                          'order',
                                          'thold',
                                          'posttype');

    include 'header.php';

    // fix permissions and config checks -InvalidResponse
    $sid = (int)pnVarPrepForStore($sid);
    $row = getArticles("pn_sid=$sid", "", "");
    $info = genArticleInfo($row[0]);

    if ((!pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_COMMENT)) ||
         !pnSecAuthAction(0, 'Topics::Topic', "$info[topicname]::$info[tid]", ACCESS_READ) ||
         (!pnUserLoggedIn() && !pnConfigGetVar('anonpost')) ||
         $info['withcomm'] == 1) {

         OpenTable2();
         echo _NOTAUTHORIZEDCOMMENT;
         CloseTable();
         include 'footer.php';
         exit;
    }

    $AllowableHTML = pnConfigGetVar('AllowableHTML');
    $anonymous = pnConfigGetVar('anonymous');
    OpenTable();
    echo '<h2>'._COMREPLYPRE.'</h2>';
    CloseTable();

    OpenTable();
    if (!isset($pid) || !isset($sid)) {
        echo ""._NOTRIGHT."";
        exit();
    }
    if($subject == '' or $comment == '') {
        OpenTable2();
        echo '<strong>'._MPROBLEM.'</strong> '._NOSUBJECTORCOMMENT.'<br />';
        echo "<div style=\"text-align:center\">"._GOBACK.'</div><br />';
        CloseTable2();
        include('footer.php');
        exit;
    }
    $subject = pnVarCensor($subject);
    echo '<h2>'.pnVarPrepForDisplay($subject).'</h2>';
    echo '<br />'._BY." ";
    if (pnUserLoggedIn() && !$xanonpost) {
        echo pnUserGetVar('uname');
    } else {
        echo "".pnVarPrepForDisplay($anonymous)."";
    }
    echo " "._ONN.'<br />';

    $postcomment = pnVarCensor($comment);
    if ($posttype == 'plaintext') {
        $postcomment = nl2br($postcomment);
      // some quick'n dirty replacement - no htmlspecialchars possible
      // since this is in pnVarPrepHTMLDisplay already / larsneo 2002/11/19
        // extrans not stored in DB - should be fixed for .725
      // } elseif ($posttype == 'exttrans') {
      // $postcomment = nl2br($postcomment);
      // } elseif ($posttype == 'html') {
      // HTML is preformatted so nothing is needed
        // $postcomment = $postcomment;
    }
    echo pnVarPrepHTMLDisplay($postcomment);

    CloseTable();

    OpenTable();
    echo "<form action=\"index.php\" method=\"post\"><div><h2>"._YOURNAME.":</h2> ";
    if (pnUserLoggedIn()) {
        echo "<a href=\"user.php\">" . pnUserGetVar('uname') . "</a> [ <a href=\"user.php?module=User&amp;op=logout\">"._LOGOUT."</a> ]<br />";
    } else {
        echo pnVarPrepForStore($anonymous).'<br />';
    }
    echo '<h2>'._SUBJECT.':</h2>'
        ."<input type=\"text\" name=\"subject\" size=\"50\" maxlength=\"85\" value=\"" . pnVarPrepForDisplay($subject) . "\" /><br />"
        .'<h2>'._COMMENT.':</h2>'
        ."<textarea wrap=\"virtual\" cols=\"80\" rows=\"10\" name=\"comment\">" . pnVarPrepForDisplay($comment) . "</textarea><br />"
        ."<span class=\"pn-sub\">"._ALLOWEDHTML.'<br />';
    while (list($key, $access, ) = each($AllowableHTML)) {
        if ($access > 0) echo " &lt;".$key."&gt;";
    }
    echo "</span><br />";
    if (pnConfigGetVar('anonpost')) {
        if ($xanonpost) {
            echo "<input type=\"checkbox\" name=\"xanonpost\" checked> "._POSTANON.'<br />';
        } elseif (pnUserLoggedIn()) {
            echo "<input type=\"checkbox\" name=\"xanonpost\"> "._POSTANON.'<br />';
        }
    }
    echo "<input type=\"hidden\" name=\"pid\" value=\"$pid\" />"
        ."<input type=\"hidden\" name=\"sid\" value=\"$sid\" />"
        ."<input type=\"hidden\" name=\"mode\" value=\"$mode\" />"
        ."<input type=\"hidden\" name=\"order\" value=\"$order\" />"
        ."<input type=\"hidden\" name=\"thold\" value=\"$thold\" />"
        ."<input type=\"submit\" name=\"req\" value=\""._PREVIEW."\" />"
        ."<input type=\"submit\" name=\"req\" value=\""._OK."\" />\n"
        ."<input type=\"hidden\" name=\"name\" value=\"Comments\" />\n"
        ."<select name=\"posttype\">\n";
        // extrans not stored in DB - should be fixed for .725
      //."<option value=\"exttrans\"";
    //if ($posttype=="exttrans") {
    //    echo " selected=\"selected\"";
    //}
    //echo ">"._EXTRANS."</option>\n";

    echo "<option value=\"plaintext\"";
    if ($posttype == "plaintext") {
        echo " selected=\"selected\"";
    }
    echo ">"._PLAINTEXT."</option>";

  echo "<option value=\"html\"";
    if ($posttype == "html") {
        echo " selected=\"selected\"";
    }
    echo ">"._HTMLFORMATED."</option>\n";

    echo "</select></span></div></form>";
    CloseTable();
    include 'footer.php';
}

function CreateTopic ()
{
    list($xanonpost,
         $subject,
         $comment,
         $pid,
         $sid,
         $host_name,
         $mode,
         $order,
         $thold,
         $posttype,
         $req) = pnVarCleanFromInput('xanonpost',
                                          'subject',
                                          'comment',
                                          'pid',
                                          'sid',
                                          'host_name',
                                          'mode',
                                          'order',
                                          'thold',
                                          'posttype',
                                          'req');

    if (!isset($pid) || !is_numeric($pid)) {
        $pid = 0;
    }

    // fix permissions and config checks -InvalidResponse
    $sid = (int)pnVarPrepForStore($sid);
    $row = getArticles("pn_sid=$sid", "", "");
    $info = genArticleInfo($row[0]);

    if ((!pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_COMMENT)) ||
         !pnSecAuthAction(0, 'Topics::Topic', "$info[topicname]::$info[tid]", ACCESS_READ) ||
         (!pnUserLoggedIn() && !pnConfigGetVar('anonpost')) ||
         $info['withcomm'] == 1) {

         include('header.php');
         OpenTable2();
         echo _NOTAUTHORIZEDCOMMENT;
         CloseTable();
         include 'footer.php';
         exit;
    }

    global $EditedMessage, $options;

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $AllowableHTML = pnConfigGetVar('AllowableHTML');

    if($subject == '' or $comment == '') {
        include('header.php');
        OpenTable2();
        echo '<strong>'._MPROBLEM.'</strong> '._NOSUBJECTORCOMMENT.'<br />';
        echo "<div style=\"text-align:center\">"._GOBACK.'</div><br />';
        CloseTable2();
        include 'footer.php';
        exit;
    }

    $subject = pnVarCensor($subject);
    $comment = pnVarCensor($comment);

    if ($posttype == 'plaintext') {
        $comment = nl2br($comment);
      // some quick'n dirty replacement - no htmlspecialchars possible
      // since this is in pnVarPrepHTMLDisplay already / larsneo 2002/11/19
        // extrans not stored in DB - should be fixed for .725
      // } elseif ($posttype == 'exttrans') {
      // $postcomment = nl2br($postcomment);
      // } elseif ($posttype == 'html') {
      // HTML is preformatted so nothing is needed
        // $comment = nl2br($comment);
    }

    if (pnUserLoggedIn() && (!$xanonpost)) {
        $uname = pnUserGetVar('uname');
        $email = pnUserGetVar('femail');
        $url   = pnUserGetVar('url');
        $score = 1;
    } else {
        $uname = "";
    $email = "";
    $url   = "";
        $score = 0;
    }
    $ip = pnServerGetVar("REMOTE_ADDR");

  /* begin fake thread control */

    $result =& $dbconn->Execute("SELECT count(*)
                                FROM $pntable[stories]
                                WHERE {$pntable['stories_column']['sid']}='".(int)pnVarPrepForStore($sid)."'");
    list($fake) = $result->fields;

  /* begin duplicate control */

  /*
   * hootbah:
   * Is this needed? If the table is set up correctly with indexes then this test
   * would be obsolete.
   */
    $column = &$pntable['comments_column'];
    if (strcmp(pnConfigGetVar('dbtype'), 'oci8') == 0) {
    $sql = "SELECT count(*) FROM $pntable[comments]
            WHERE $column[pid]='".(int)pnVarPrepForStore($pid)."'
              AND $column[sid]='".(int)pnVarPrepForStore($sid)."'
              AND $column[subject]='".pnVarPrepForStore($subject)."'
              AND DBMS_LOB.INSTR($column[comment], '$comment', 1, 1) > 0";
    } else {
        $sql = "SELECT count(*) FROM $pntable[comments]
                WHERE $column[pid]='".(int)pnVarPrepForStore($pid)."'
                  AND $column[sid]='".(int)pnVarPrepForStore($sid)."'
                  AND $column[subject]='".pnVarPrepForStore($subject)."'
                  AND $column[comment]='".pnVarPrepForStore($comment)."'";
    }
    $result =& $dbconn->Execute($sql);
    list($tia) = $result->fields;

  /* begin troll control */

    if(pnUserLoggedIn()) {
        $column = &$pntable['comments_column'];
        $result =& $dbconn->Execute("SELECT count(*) FROM $pntable[comments]
                                    WHERE ($column[score]=-1)
                                    AND ($column[name]='".pnVarPrepForStore($uname)."')
                                    AND (to_days(now())
                                    - to_days($column[date]) < 3)");
        list($troll) = $result->fields;
    } elseif(!$score) {
        $column = &$pntable['comments_column'];
        $result =& $dbconn->Execute("SELECT count(*) FROM $pntable[comments]
                                    WHERE ($column[score]=-1)
                                    AND ($column[host_name]='".pnVarPrepForStore($ip)."')
                                    AND (to_days(now())
                                    - to_days($column[date]) < 3)");
        list($troll) = $result->fields;
    }

    //fix for [#2074] HTTP injection allows anonymous comments when they're turned off in settings
    if(!pnUserLoggedIn() && !pnConfigGetVar('anonpost')) {
       $troll = 8;
    }

    if((!$tia) && ($fake == 1) && ($troll < 6)) {
        $column = &$pntable['comments_column'];
        $nextid = $dbconn->GenId($pntable['comments']);
    $sql = "INSERT INTO $pntable[comments] ($column[tid], $column[pid],
                          $column[sid], $column[date], $column[name], $column[email],
              $column[url], $column[host_name], $column[subject],
              $column[comment], $column[score], $column[reason] )
                        VALUES ($nextid, '".(int)pnVarPrepForStore($pid)."', '".(int)pnVarPrepForStore($sid).
            "', now(), '".pnVarPrepForStore($uname)."', '".pnVarPrepForStore($email)."',
              '".pnVarPrepForStore($url)."', '".pnVarPrepForStore($ip)."', '"
            .pnVarPrepForStore($subject)."', '".pnVarPrepForStore($comment)."', '"
            .pnVarPrepForStore($score)."', 0)";
        $result =& $dbconn->Execute($sql);
        if($dbconn->ErrorNo()<>0) {
            error_log("DB Error: Can not add comment: " . $dbconn->ErrorMsg());
        }
    // Let any hooks know that we have created a new item
    pnModCallHooks('item', 'create', $nextid, 'tid');

    } else {
        include 'header.php';
        if ($tia) {
            echo _DUPLICATE."<br /><a href=\"index.php?name=News&amp;file=article&amp;sid=$sid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">"._COMMENTSBACK."</a>";
        } elseif($troll > 5) {
            echo '_TROLL';
        } elseif($fake == 0) {
            echo '_FAKETOPIC';
        }
        include 'footer.php';
        exit;
    }
    $column = &$pntable['stories_column'];
    $result =& $dbconn->Execute("UPDATE $pntable[stories] SET $column[comments]=$column[comments]+1 WHERE $column[sid]='".(int)pnVarPrepForStore($sid)."'");

    $options .= pnUserGetCommentOptionsArray();

    $redURL = 'index.php?name=News&file=article&sid='.$sid.'&thold=';
    if (empty($thold)) {
      $redURL .= "0&mode=";
    } else {
      $redURL .= "$thold&mode=";
    }
    if (empty($mode)) {
      $redURL .= "0&order=";
    } else {
      $redURL .= "$mode&order=";
    }
    if (empty($order)) {
      $redURL .= "0";
    } else {
      $redURL .= "$order";
    }

    pnRedirect($redURL);
}

$dbconn = &pnDBGetConn(true);

$req = pnVarCleanFromInput('req');

if(empty($req)) {
    $req = '';
}
list($sid,
   $pid,
   $tid,
   $mode,
   $order,
   $thold,
   $moderate) = pnVarCleanFromInput('sid',
                   'pid',
                   'tid',
                   'mode',
                   'order',
                   'thold',
                   'moderate');

if (empty($mode) || (empty($order) && $order != 0) || (empty($thold) && $thold != 0)) {
  $commentoptions = pnUserGetCommentOptions(false);
  extract($commentoptions);
}

switch($req) {

    case "Reply":
        reply($pid, $sid, $mode, $order, $thold);
        break;

    case ""._PREVIEW."":
        replyPreview ();
        break;

    case ""._OK."":
        CreateTopic();
        break;

    case "moderate":
    if(!isset($moderate)) {
      $moderate = 2;
    }
    // what the heck is the echo good for??? creating a xss problem??
    //echo $moderate;
        if($moderate == 2) {
      $dbconn =& pnDBGetConn(true);
      $pntable =& pnDBGetTables();
      foreach ($_POST as $tdw => $emp) {
                if (eregi("dkn",$tdw)) {
                    $emp = explode(":", $emp);
                    if($emp[1] != 0) {
                        $tdw = ereg_replace("dkn", "", $tdw);
            $column = &$pntable['comments_column'];
                        $q = "UPDATE $pntable[comments] SET";
                        if(($emp[1] == 9) && ($emp[0]>=0)) { # Overrated
                            $q .= " $column[score]=$column[score]-1 WHERE $column[tid]='".pnVarPrepForStore($tdw)."'";
                        } elseif (($emp[1] == 10) && ($emp[0]<=4)) { # Underrated
                            $q .= " $column[score]=$column[score]+1 WHERE $column[tid]='".pnVarPrepForStore($tdw)."'";
                        } elseif (($emp[1] > 4) && ($emp[0]<=4)) {
                            $q .= " $column[score]=$column[score]+1, $column[reason]=$emp[1] WHERE $column[tid]='".pnVarPrepForStore($tdw)."'";
                        } elseif (($emp[1] < 5) && ($emp[0] > -1)) {
                            $q .= " $column[score]=$column[score]-1, $column[reason]=$emp[1] WHERE $column[tid]='".pnVarPrepForStore($tdw)."'";
                        } elseif (($emp[0] == -1) || ($emp[0] == 5)) {
                            $q .= " $column[reason]=$emp[1] WHERE $column[tid]='".pnVarPrepForStore($tdw)."'";
                        }
                        if(strlen($q) > 20) {
                            $result = $dbconn->Execute($q);
                        }
                    }
                }
            }
        }
        pnRedirect('index.php?name=News&file=article&sid='.$sid.'&mode='.$mode.'&order='.$order.'&thold='.$thold);
        exit;
        break;

    case "showreply":
        global $info;
    $info = pnVarCleanFromInput('info');
        DisplayTopic($info, $sid, $pid, $tid, $mode, $order, $thold);
        break;

    default:
        if ((!empty($tid)) && (empty($pid))) {
            singlecomment($info, $tid, $sid, $mode, $order, $thold);
/*        } elseif (($mainfile) xor (($pid==0) AND (empty($pid)))) {
            pnRedirect('index.php?name=News&file=article&sid='.$sid.'&mode='.$mode.'&order='.$order.'&thold='.$thold);*/
        } else {
            if(!isset($pid)) {
        $pid=0;
        }
            if(!empty($info)) {
                DisplayTopic($info, $sid, $pid, $tid, $mode, $order, $thold);
            } else {
        // no info present, check if we can build one...
        if(!empty($sid)) {
          $sid = (int)pnVarPrepForStore($sid);
          $row = getArticles("pn_sid=$sid", "", "");
            $info = genArticleInfo($row[0]);
                  include 'header.php';
                  DisplayTopic($info, $sid, $pid, $tid, $mode, $order, $thold);
                  include 'footer.php';
        } else {
          // really no info array, shouldn't be here...
                  include 'header.php';
                  echo _COMMENTSNODIRECTACCESS;
                  include 'footer.php';
        }
            }
        }
        break;
}
?>