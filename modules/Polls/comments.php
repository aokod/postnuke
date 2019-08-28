<?php
// $Id: comments.php 17591 2006-01-14 14:32:02Z larsneo $
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
// Filename: modules/Polls/index.php
// Original Author: Till Gerken (tig@skv.org)
// Purpose: Voting system
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_MODULE')) {
    die ("You can't access this file directly...");
}

$ModName = basename(dirname( __FILE__ ));
modules_get_language();

function modone()
{
    $moderate = pnConfigGetVar('moderate');

    if(($moderate == 1) || ($moderate==2))
    {
        echo "<form action=\"index.php?name=".pnVarPrepForDisplay($GLOBALS['name'])."&amp;file=comments\" method=\"post\"><div>";
    }
}
function modtwo($tid, $score, $reason)
{

    $moderate = pnConfigGetVar('moderate');
    $reasons = pnConfigGetVar('reasons');
    if((($moderate == 1) || ($moderate == 2)) && (pnUserLoggedIn())) {
        echo " | <select name=\"dkn$tid\">";
        for($i=0; $i<sizeof($reasons); $i++) {
            echo "<option value=\"$score:$i\">".pnVarPrepForDisplay($reasons[$i])."</option>\n";
        }
        echo "</select>";
    }
}

function modthree($pollID, $mode, $order, $thold=0)
{

    $moderate = pnConfigGetVar('moderate');
    $userimg = pnConfigGetVar('userimg');

    if((($moderate == 1) || ($moderate==2)) && (pnUserLoggedIn())) {
		list($pollID, $mode, $order, $thold) = pnVarPrepForDisplay($pollID, $mode, $order, $thold);
		echo "<div style=\"text-align:center\">
		<input type=\"hidden\" name=\"pollID\" value=\"$pollID\" />
		<input type=\"hidden\" name=\"mode\" value=\"$mode\" />
		<input type=\"hidden\" name=\"order\" value=\"$order\" />
		<input type=\"hidden\" name=\"thold\" value=\"$thold\" />
		<input type=\"hidden\" name=\"req\" value=\"moderate\" />
		<input type=\"submit\" value=\""._MODERATE."\" />
		</div></div></form>";
	} else {
		echo "</div></form>";
	}
}

function navbar($pollID, $title, $thold, $mode, $order)
{
    list($pid, $sid) = pnVarCleanFromInput('pid','sid');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $pollcomm = pnConfigGetVar('pollcomm');
    $anonpost = pnConfigGetVar('anonpost');

    $query =& $dbconn->Execute("SELECT COUNT(*)
                             FROM ".$pntable['pollcomments'].
                             " WHERE ".$pntable['pollcomments_column']['pollid']."='".(int)pnVarPrepForStore($pollID)."'");
    list($count) = $query->fields;
    $column = &$pntable['poll_desc_column'];
    $result =& $dbconn->Execute("SELECT $column[polltitle]
                              FROM $pntable[poll_desc]
                              WHERE $column[pollid]='".(int)pnVarPrepForStore($pollID)."'");

    list($title) = $result->fields;

    if(!isset($thold)) {
        $thold=0;
    }

    echo "\n\n<!-- COMMENTS NAVIGATION BAR START -->\n\n";
    echo "<table width=\"99%\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\">\n";
    if($title) {
        echo "<tr><td style=\"background-color:".$GLOBALS['bgcolor2']."\" align=\"center\">\"".pnVarPrepForDisplay($title)."\" | ";
        if (pnUserLoggedIn()) {
            echo "<a href=\"user.php?op=editcomm\">"._CONFIGURE."</a>";
        } else {
            echo "<a href=\"user.php\">"._LOGINCREATE."</a>";
        }
        if(($count==1)) {
            echo " | $count "._COMMENT."</td></tr>\n";
        } else {
            echo " | $count "._COMMENTS."</td></tr>\n";
        }
    }
    echo "<tr><td style=\"background-color:".$GLOBALS['bgcolor1'].";width:100%\" align=\"center\">\n"
    ."<table border=\"0\"><tr><td>\n"
    ."<form method=\"post\" action=\"index.php?name=".pnVarPrepForDisplay($GLOBALS['name'])."&amp;req=results&amp;pollID=$pollID\"><div>\n";
    if (pnConfigGetVar('moderate')) {
        echo ""
            ._THRESHOLD." <select name=\"thold\">\n"
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
        echo ">5</option>\n";
    }   else {
        echo "<input type=\"hidden\" name=\"thold\" value=\"0\" />\n"; // I'm not sure it should be zero here but should be ok
    }
    echo ""
     ."</select> <select name=\"mode\">"
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
     ."</select> <select name=\"order\">"
     ."<option value=\"0\"";
    if (!$order) {
    echo " selected=\"selected\"";
    }
    echo ">"._OLDEST."</option>\n"
     ."<option value=\"1\"";
    if ($order==1) {
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
     ."<input type=\"hidden\" name=\"sid\" value=\"$sid\" />\n"
     ."<input type=\"submit\" value=\""._REFRESH."\" /></div></form>\n";
    if (($pollcomm) AND ($mode != "nocomments")) {
		if ($anonpost==1 OR pnUserLoggedIn()) {
			echo "</td><td style=\"background-color:".$GLOBALS['bgcolor1']."\"><form action=\"index.php?name=".pnVarPrepForDisplay($GLOBALS['name'])."&amp;file=comments\" method=\"post\"><div>"
			."<input type=\"hidden\" name=\"pid\" value=\"$pid\" />"
			."<input type=\"hidden\" name=\"pollID\" value=\"$pollID\" />"
			."<input type=\"hidden\" name=\"req\" value=\"Reply\" />"
			."&nbsp;&nbsp;<input type=\"submit\" value=\""._REPLYMAIN."\" />"
			."</div></form>";
		}
    }
    echo "</td></tr></table>\n"
    ."</td></tr>"
    ."<tr><td style=\"background-color:".$GLOBALS['bgcolor2']."\" align=\"center\"><span class=\"pn-sub\">"._COMMENTSWARNING."</span></td></tr>\n"
    ."</table>"
    ."\n\n<!-- COMMENTS NAVIGATION BAR END -->\n\n";
}

function DisplayKids ($tid, $mode, $order=0, $thold=0, $level=0, $dummy=0, $tblwidth=99)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $reasons = pnConfigGetVar('reasons');
    $anonymous = pnConfigGetVar('anonymous');
    $anonpost = pnConfigGetVar('anonpost');
    $commentlimit = pnConfigGetVar('commentlimit');

    $comments = 0;

    $noshowscore = pnUserGetVar('noscore');
    $commentsmax = pnUserGetVar('commentmax');

    $column = &$pntable['pollcomments_column'];
    $result =& $dbconn->Execute("SELECT $column[tid], $column[pid], $column[pollid],
                                $column[date], $column[name],
                                $column[email], $column[url], $column[host_name],
                                $column[subject], $column[comment], $column[score],
                                $column[reason]
                              FROM $pntable[pollcomments]
                              WHERE $column[pid] = '".(int)pnVarPrepForStore($tid)."'
                              ORDER BY $column[date], $column[tid]");
    if ($mode == 'nested') {
        /* without the tblwidth variable, the tables run of the screen with netscape
           in nested mode in long threads so the text can't be read. */

        while(list($r_tid, $r_pid, $r_pollID, $r_date, $r_name, $r_email, $r_url, $r_host_name, $r_subject, $r_comment, $r_score, $r_reason) = $result->fields) {
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
                if (!eregi("[a-z0-9]",$r_name)) $r_name = $anonymous;
                if (!eregi("[a-z0-9]",$r_subject)) $r_subject = "["._NOSUBJECT."]";
                // enter hex color between first two appostrophe for second alt bgcolor
                $r_bgcolor = ($dummy%2)?"":"".$GLOBALS['bgcolor1']."";
                echo '<li style="list-style:none">';
                echo "<table border=\"0\"><tr style=\"background-color:$r_bgcolor\"><td>";
                //formatTimestamp($r_date);
                $datetime = ml_ftime(_DATETIMEBRIEF, GetUserTime($r_date));
                if ($r_email) {
                    echo $r_subject;
                    if(!$noshowscore && $modoption ) {
                        echo "("._SCORE." $r_score";
                        if($r_reason>0) echo ", $reasons[$r_reason]";
                        echo ")";
                    }
                    echo '<br />'._BY." <a href=\"mailto:$r_email\">$r_name</a> <strong>($r_email)</strong> "._ON." $datetime";
                } else {
                    echo "<strong>$r_subject</strong> ";
                    if(!$noshowscore && $modoption ) {
                        echo "("._SCORE." $r_score";
                        if($r_reason>0) echo ", $reasons[$r_reason]";
                        echo ")";
                    }
                    echo '<br />'._BY." $r_name "._ON." $datetime";
                }
                if ($r_name != $anonymous) { 
                	echo '<br />(<a href="user.php?op=userinfo&amp;uname=' . pnVarPrepForDisplay($r_name) . '">' . _USERINFO . '</a>';
                    if (pnModAvailable('Messages')) {
                        echo ' | <a href="' . pnVarPrepForDisplay(pnModURL('Messages', 'user', 'compose', array('uname' => $r_name))).'">'._SENDAMSG.'</a>';
                    }
                }

                if (eregi("http://",$r_url)) { echo "<a href=\"$r_url\">$r_url</a> "; }
                echo "</td></tr><tr><td>";
                if(($commentsmax) && (strlen($r_comment) > $commentsmax)) echo substr("$r_comment", 0, $commentsmax)."<br /><strong><a href=\"index.php?name=".pnVarPrepForDisplay($GLOBALS['name'])."&amp;file=comments&amp;pollID=".pnVarPrepForDisplay($r_pollID)."&amp;tid=".pnVarPrepForDisplay($r_tid)."&amp;mode=".pnVarPrepForDisplay($mode)."&amp;order=".pnVarPrepForDisplay($order)."&amp;thold=".pnVarPrepForDisplay($thold)."\">"._READREST."</a></strong>";
                elseif(strlen($r_comment) > $commentlimit) echo substr("$r_comment", 0, $commentlimit)."<br /><strong><a href=\"index.php?name=".pnVarPrepForDisplay($GLOBALS['name'])."&amp;file=comments&amp;pollID=".pnVarPrepForDisplay($r_pollID)."&amp;tid=".pnVarPrepForDisplay($r_tid)."&amp;mode=".pnVarPrepForDisplay($mode)."&amp;order=".pnVarPrepForDisplay($order)."&amp;thold=".pnVarPrepForDisplay($thold)."\">"._READREST."</a></strong>";
                else echo $r_comment;
                echo "</td></tr></table><br />";
                if ($anonpost==1 OR pnUserLoggedIn()) {
                    echo " [ <a href=\"index.php?name=".pnVarPrepForDisplay($GLOBALS['name'])."&amp;file=comments&amp;req=Reply&amp;pid=".pnVarPrepForDisplay($r_tid)."&amp;pollID=".pnVarPrepForDisplay($r_pollID)."&amp;mode=".pnVarPrepForDisplay($mode)."&amp;order=".pnVarPrepForDisplay($order)."&amp;thold=".pnVarPrepForDisplay($thold)."\">"._REPLY."</a>";
                }
                modtwo($r_tid, $r_score, $r_reason);
                echo " ]";
                DisplayKids($r_tid, $mode, $order, $thold, $level+1, $dummy+1, $tblwidth);
                echo '</li>';
				if (!$level && $comments) {
					echo "</ul>";
				}
            }
        }
    } elseif ($mode == 'flat') {

        while(list($r_tid, $r_pid, $r_pollID, $r_date, $r_name, $r_email, $r_url, $r_host_name, $r_subject, $r_comment, $r_score, $r_reason) = $result->fields) {
            $r_date=$result->UnixTimeStamp($r_date);
            $result->MoveNext();
            if($r_score >= $thold) {
                if (!eregi("[a-z0-9]",$r_name)) $r_name = $anonymous;
                if (!eregi("[a-z0-9]",$r_subject)) $r_subject = "["._NOSUBJECT."]";
                echo "<hr /><table width=\"99%\" border=\"0\"><tr style=\"background-color:".$GLOBALS['bgcolor1']."\"><td>";
                $datetime = ml_ftime(_DATETIMEBRIEF, GetUserTime($r_date));
                if ($r_email) {
                    echo "$r_subject ";
                    if(!$noshowscore && $modoption ) {
                        echo "("._SCORE." $r_score";
                        if($r_reason>0) echo ", $reasons[$r_reason]";
                        echo ")";
                    }
                    echo '<br />'._BY." <a href=\"mailto:$r_email\">$r_name</a> <strong>($r_email)</strong> "._ON." $datetime";
                } else {
                    echo "$r_subject ";
                    if(!$noshowscore && $modoption ) {
                        echo "("._SCORE." $r_score";
                        if($r_reason>0) echo ", $reasons[$r_reason]";
                        echo ")";
                    }
                    echo '<br />'._BY." $r_name "._ON." $datetime";
                }
                if ($r_name != $anonymous) { 
                	echo '<br />(<a href="user.php?op=userinfo&amp;uname=' . pnVarPrepForDisplay($r_name) . '">' . _USERINFO . '</a>';
                    if (pnModAvailable('Messages')) {
                        echo ' | <a href="' . pnVarPrepForDisplay(pnModURL('Messages', 'user', 'compose', array('uname' => $r_name))).'">'._SENDAMSG.'</a>';
                    }
                }
                if (eregi("http://",$r_url)) { echo "<a href=\"$r_url\">$r_url</a> "; }
                echo "</td></tr><tr><td>";
                if(($commentsmax) && (strlen($r_comment) > $commentsmax)) echo substr("$r_comment", 0, $commentsmax)."<br /><strong><a href=\"index.php?name=".pnVarPrepForDisplay($GLOBALS['name'])."&amp;file=comments&amp;pollID=".pnVarPrepForDisplay($r_pollID)."&amp;tid=".pnVarPrepForDisplay($r_tid)."&amp;mode=".pnVarPrepForDisplay($mode)."&amp;order=".pnVarPrepForDisplay($order)."&amp;thold=".pnVarPrepForDisplay($thold)."\">"._READREST."</a></strong>";
                elseif(strlen($r_comment) > $commentlimit) echo substr("$r_comment", 0, $commentlimit)."<br /><strong><a href=\"index.php?name=".pnVarPrepForDisplay($GLOBALS['name'])."&amp;file=comments&amp;pollID=".pnVarPrepForDisplay($r_pollID)."&amp;tid=".pnVarPrepForDisplay($r_tid)."&amp;mode=".pnVarPrepForDisplay($mode)."&amp;order=".pnVarPrepForDisplay($order)."&amp;thold=".pnVarPrepForDisplay($thold)."\">"._READREST."</a></strong>";
                else echo $r_comment;                
                echo "</td></tr></table><br />"
                    ."<div style=\"text-align:center\">"
                    ." [ <a href=\"index.php?name=".pnVarPrepForDisplay($GLOBALS['name'])."&amp;file=comments&amp;req=Reply&amp;pid=".pnVarPrepForDisplay($r_tid)."&amp;pollID=".pnVarPrepForDisplay($r_pollID)."&amp;mode=".pnVarPrepForDisplay($mode)."&amp;order=".pnVarPrepForDisplay($order)."&amp;thold=".pnVarPrepForDisplay($thold)."\">"._REPLY."</a>";
                modtwo($r_tid, $r_score, $r_reason);
                echo " ]";
                echo '</div>';
                DisplayKids($r_tid, $mode, $order, $thold);
            }
        }
    } else {
        while(list($r_tid, $r_pid, $r_pollID, $r_date, $r_name, $r_email, $r_url, $r_host_name, $r_subject, $r_comment, $r_score, $r_reason) = $result->fields) {
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
                if (!eregi("[a-z0-9]",$r_name)) $r_name = $anonymous;
                if (!eregi("[a-z0-9]",$r_subject)) $r_subject = "["._NOSUBJECT."]";
                $datetime = ml_ftime(_DATETIMEBRIEF, GetUserTime($r_date));
                echo "<li><a href=\"index.php?name=".pnVarPrepForDisplay($GLOBALS['name'])."&amp;file=comments&amp;req=showreply&amp;tid=".pnVarPrepForDisplay($r_tid)."&amp;pollID=".pnVarPrepForDisplay($r_pollID)."&amp;pid=".pnVarPrepForDisplay($r_pid)."&amp;mode=".pnVarPrepForDisplay($mode)."&amp;order=".pnVarPrepForDisplay($order)."&amp;thold=".pnVarPrepForDisplay($thold)."#".pnVarPrepForDisplay($r_tid)."\">$r_subject</a> "._BY." $r_name "._ON." $datetime<br />";
                DisplayKids($r_tid, $mode, $order, $thold, $level+1, $dummy+1);
				echo '</li>';
				if (!$level && $comments) {
					echo "</ul>";
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
    $column = &$pntable['pollcomments_column'];
    $result =& $dbconn->Execute("SELECT $column[tid], $column[pid], $column[pollID],
                                $column[date], $column[name],
                                $column[email], $column[url], $column[host_name],
                                $column[subject], $column[comment], $column[score],
                                $column[reason]
                              FROM $pntable[pollcomments]
                              WHERE $column[pid] = '".(int)pnVarPrepForStore($tid)."'
                                ORDER BY $column[date], $column[tid]");

    while(list($r_tid, $r_pid, $r_pollID, $r_date, $r_name, $r_email, $r_url, $r_host_name, $r_subject, $r_comment, $r_score, $r_reason) = $result->fields) {
        $r_date=$result->UnixTimeStamp($r_date);
        $result->MoveNext();
        if (!isset($level)) {
        } else {
            if (!$comments) {
                echo "<ul>";
            }
        }
        $comments++;
        if(!eregi("[a-z0-9]",$r_name)) {
        $r_name = $anonymous;
    }
        if(!eregi("[a-z0-9]",$r_subject)) {
        $r_subject = "["._NOSUBJECT."]";
    }
        $datetime = ml_ftime(_DATETIMEBRIEF, GetUserTime($r_date));
			echo "<a href=\"index.php?name=".pnVarPrepForDisplay($GLOBALS['name'])."&amp;file=comments&amp;req=showreply&amp;tid=".pnVarPrepHTMLDisplay($r_tid)."&amp;mode=".pnVarPrepHTMLDisplay($mode)."&amp;order=".pnVarPrepHTMLDisplay($order)."&amp;thold=".pnVarPrepFOrDisplay($thold)."\"\">$r_subject</a> "._BY." $r_name "._ON." $datetime<br />";
        DisplayBabies($r_tid, $level+1, $dummy+1);
    }
    if ($level && $comments) {
        echo "</ul>";
    }
}

function DisplayTopic ($pollID, $pid=0, $tid=0, $mode="thread", $order=0, $thold=0, $level=0, $nokids=0)
{
    global $hr, $mainfile, $subject;

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $commentlimit = pnConfigGetVar('commentlimit');
    $anonymous = pnConfigGetVar('anonymous');
    $reasons = pnConfigGetVar('reasons');
    $anonpost = pnConfigGetVar('anonpost');

	global $title;
    if ($pid!=0) {
        include('header.php');
    }
    $count_times = 0;

    $noshowscore = pnUserGetVar('noscore');
    $commentsmax = pnUserGetVar('commentmax');

    $column = &$pntable['pollcomments_column'];
    $q = "SELECT $column[tid], $column[pid], $column[pollid],
            $column[date], $column[name], $column[email],
            $column[url], $column[host_name], $column[subject], $column[comment],
            $column[score], $column[reason]
          FROM $pntable[pollcomments]
          WHERE $column[pollid]='".(int)pnVarPrepForStore($pollID)."' AND $column[pid]='".(int)pnVarPrepForStore($pid)."'";
    if($thold != "") {
        $q .= " AND $column[score]>='".(int)$thold."'";
    } else {
        $q .= " AND $column[score]>=0";
    }
    if ($order==1) $q .= " ORDER BY $column[date] DESC";
    if ($order==2) $q .= " ORDER BY $column[score] DESC";
    $something =& $dbconn->Execute("$q");
	if($dbconn->ErrorNo() != 0) {
		include('footer.php');
		break;
	} else {
		$num_tid = $something->PO_RecordCount();
		navbar($pollID, $title, $thold, $mode, $order);
		//echo "<div align=\"left\">";
		modone();
		while ($count_times < $num_tid) {
			list($tid, $pid, $pollID, $date, $name, $email, $url, $host_name, $subject, $comment, $score, $reason) = $something->fields;
			$date=$something->UnixTimeStamp($date);
			$something->MoveNext();
			if ($name == "") { $name = $anonymous; }
			if ($subject == "") { $subject = "["._NOSUBJECT."]"; }
	
			echo "<table width=\"99%\" border=\"0\"><tr style=\"background-color:".$GLOBALS['bgcolor1']."\"><td style=\"width:500px\">";
			//formatTimestamp($date);
			$datetime = ml_ftime(_DATETIMEBRIEF, GetUserTime($date));
			if ($email) {
				echo pnVarPrepForDisplay($subject)." >";
				if(!$noshowscore && $modoption ) {
					echo "("._SCORE." ".pnVarPrepForDisplay($score);
					if($reason>0) echo ", ".pnVarPrepForDisplay($reasons[$reason]);
					echo ")";
				}
				echo '<br />'._BY." <a href=\"mailto:$email\">".pnVarPrepForDisplay($name)."</a> ($email) "._ON." $datetime";
			} else {
				echo pnVarPrepForDisplay($subject)." ";
				if(!$noshowscore && (isset($modoption)&&$modoption)) {
					echo "("._SCORE." ".pnVarPrepForDisplay($score);
					if($reason>0) echo ", ".pnVarPrepForDisplay($reasons[$reason]);
					echo ")";
				}
				echo '<br />'._BY." ".pnVarPrepForDisplay($name)." "._ON." $datetime";
			}
	
			// If you are admin you can see the Poster IP address (you have this right, no?)
			// with this you can see who is flaming you... ha-ha-ha
	
			if ($name != $anonymous) { 
            	echo '<br />(<a href="user.php?op=userinfo&amp;uname=' . pnVarPrepForDisplay($name) . '">' . _USERINFO . '</a>';
                if (pnModAvailable('Messages')) {
                    echo ' | <a href="' . pnVarPrepForDisplay(pnModURL('Messages', 'user', 'compose', array('uname' => $name))).'">'._SENDAMSG.'</a>';
                }
            }
			if (eregi("http://",$url)) { echo "<a href=\"$url\">$url</a> "; }
	
			if (pnSecAuthAction(0, 'Comments::', '::', ACCESS_ADMIN)) {
				$column = &$pntable['pollcomments_column'];
				$result=& $dbconn->Execute("SELECT $column[host_name]
										 FROM $pntable[pollcomments]
										 WHERE $column[tid]='".(int)pnVarPrepForStore($tid)."'");
				list($host_name) = $result->fields;
				echo "<br />(IP: $host_name)";
			}
	
			echo "</td></tr><tr><td>";
			if(($commentsmax) && (strlen($comment) > $commentsmax)) echo substr("$comment", 0, $commentsmax)."<br /><strong><a href=\"index.php?name=".pnVarPrepForDisplay($GLOBALS['name'])."&amp;file=comments&amp;pollID=".pnVarPrepForDisplay($pollID)."&amp;tid=".pnVarPrepForDisplay($tid)."&amp;mode=".pnVarPrepForDisplay($mode)."&amp;order=".pnVarPrepForDisplay($order)."&amp;thold=".pnVarPrepForDisplay($thold)."\">"._READREST."</a></strong>";
			elseif(strlen($comment) > $commentlimit) echo substr("$comment", 0, $commentlimit)."<br /><a href=\"index.php?name=".pnVarPrepForDisplay($GLOBALS['name'])."&amp;file=comments&amp;pollID=".pnVarPrepForDisplay($pollID)."&amp;tid=".pnVarPrepForDisplay($tid)."&amp;mode=".pnVarPrepForDisplay($mode)."&amp;order=".pnVarPrepForDisplay($order)."&amp;thold=".pnVarPrepForDisplay($thold)."\">"._READREST."</a>";
			else echo pnVarPrepHTMLDisplay($comment);
			echo "</td></tr></table><br />";
			echo "<div style=\"text-align:center\">";
			if ($anonpost==1 OR pnUserLoggedIn()) {
				echo " [ <a href=\"index.php?name=".pnVarPrepForDisplay($GLOBALS['name'])."&amp;file=comments&amp;req=Reply&amp;pid=".pnVarPrepForDisplay($tid)."&amp;pollID=".pnVarPrepForDisplay($pollID)."&amp;mode=".pnVarPrepForDisplay($mode)."&amp;order=".pnVarPrepForDisplay($order)."&amp;thold=".pnVarPrepForDisplay($thold)."\">"._REPLY."</a>";
			} else {
				echo "[ "._NOANONCOMMENTS." ";
			}
			if ($pid != 0) {
				$column = &$pntable['pollcomments_column'];
				$result =& $dbconn->Execute("SELECT $column[pid]
										  FROM $pntable[pollcomments]
										  WHERE $column[tid]='".(int)pnVarPrepForStore($pid)."'");
	
				list($erin) = $result->fields;
				echo "| <a href=\"index.php?name=".pnVarPrepForDisplay($GLOBALS['name'])."&amp;file=comments&amp;pollID=".pnVarPrepForDisplay($pollID)."&amp;pid=".pnVarPrepForDisplay($erin)."&amp;mode=".pnVarPrepForDisplay($mode)."&amp;order=".pnVarPrepForDisplay($order)."&amp;thold=".pnVarPrepForDisplay($thold)."\">"._PARENT."</a>";
			}
			modtwo($tid, $score, $reason);
	
			if(pnSecAuthAction(0, 'Comments::', '::', ACCESS_DELETE)) {
				echo " | <a href=\"admin.php?module=Comments&amp;op=RemovePollComment&amp;tid=".pnVarPrepForDisplay($tid)."&amp;pollID=".pnVarPrepForDisplay($pollID)."&amp;ok=\">"._DELETE."</a> ]";
			} else {
				echo " ]";
			}
			echo '</div>';
			DisplayKids($tid, $mode, $order, $thold, $level);
            if($hr) echo "<hr />";
			echo "";
			$count_times += 1;
		}
	}
	
    modthree($pollID, $mode, $order, $thold);
    if($pid==0) return array($pollID, $pid, $subject);
    else include('footer.php');
}

function singlecomment($tid, $pollID, $mode, $order, $thold)
{
    include('header.php');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $anonpost = pnConfigGetVar('anonpost');
    $anonymous = pnConfigGetVar('anonymous');

    $column = &$pntable['pollcomments_column'];
    $deekayen =& $dbconn->Execute("SELECT $column[date], $column[name],
                                  $column[email], $column[url], $column[subject],
                                  $column[comment], $column[score], $column[reason]
                                FROM $pntable[pollcomments]
                                WHERE $column[tid]='".pnVarPrepForStore($tid)."' AND $column[pollid]='".pnVarPrepForStore($pollID)."'");

    list($date, $name, $email, $url, $subject, $comment, $score, $reason) = $deekayen->fields;
    $date=$deekayen->UnixTimeStamp($date);
    $titlebar = pnVarPrepForDisplay($subject);
    if($name == "") $name = $anonymous;
    if($subject == "") $subject = "["._NOSUBJECT."]";
    modone();
    echo "<table width=\"99%\" border=\"0\"><tr style=\"background-color:".$GLOBALS['bgcolor1']."\"><td style=\"width:500px\">";
    $datetime = ml_ftime(_DATETIMEBRIEF, GetUserTime($date));
    if($email) echo pnVarPrepForDisplay($subject)." ("._SCORE." $score)<br />"._BY." <a href=\"mailto:$email\">".pnVarPrepForDisplay($name)."</a> (".pnVarPrepForDisplay($email)._ON." $datetime";
    else echo pnVarPrepForDisplay($subject)." ("._SCORE." $score)<br />"._BY." $name "._ON." $datetime";
    echo "</td></tr><tr><td>".pnVarPrepHTMLDisplay($comment)."</td></tr></table><br /> [ <a href=\"index.php?name=".pnVarPrepForDisplay($GLOBALS['name'])."&amp;file=comments&amp;req=Reply&amp;pid=".pnVarPrepForDisplay($tid)."&amp;pollID=".pnVarPrepForDisplay($pollID)."&amp;mode=".pnVarPrepForDisplay($mode)."&amp;order=".pnVarPrepForDisplay($order)."&amp;thold=".pnVarPrepForDisplay($thold)."\">"._REPLY."</a> | <a href=\"index.php?name=".pnVarPrepForDisplay($GLOBALS['name'])."&amp;pollID=$pollID\">"._ROOT."</a>";
    modtwo($tid, $score, $reason);
    echo " ]";
    modthree($pollID, $mode, $order, $thold);
    include('footer.php');
}

function reply($pid, $pollID, $mode, $order, $thold)
{
    include('header.php');
    list($comment) = pnVarCleanFromInput('comment');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $AllowableHTML = pnConfigGetVar('AllowableHTML');
    $anonymous = pnConfigGetVar('anonymous');

    if($pid!=0) {
        $column = &$pntable['pollcomments_column'];
        $result =& $dbconn->Execute("SELECT $column[date], $column[name], $column[email],
                                    $column[url], $column[subject], $column[comment],
                                    $column[score]
                                  FROM $pntable[pollcomments]
                                  WHERE $column[tid]='".(int)pnVarPrepForStore($pid)."'");

        list($date, $name, $email, $url, $subject, $comment, $score) = $result->fields;
    } else {
        $column = &$pntable['poll_desc_column'];
        $result =  $dbconn->Execute("SELECT $column[polltitle]
                                   FROM $pntable[poll_desc]
                                   WHERE $column[pollid]='".(int)pnVarPrepForStore($pollID)."'");

        list($subject) = $result->fields;
    }
    $titlebar = pnVarPrepForDisplay($subject);
    if($subject == "") $subject = "["._NOSUBJECT."]";
    //formatTimestamp($date);
    OpenTable();
    echo '<h2>'._POLLCOM.'</h2>';
    CloseTable();

    OpenTable();
    echo "<div style=\"text-align:center\">".pnVarPrepForDisplay($subject).'</div><br />';
    if ($comment == "") {
        echo "<div style=\"text-align:center\">"._DIRECTCOM.'</div><br />';
    } else {
        echo '<br />'.pnVarPrepHTMLDisplay($comment);
    }
    CloseTable();
    if(!isset($pid) || !isset($pollID)) { echo "Something is not right. This message is just to keep things from messing up down the road"; exit(); }

    OpenTable();
    echo "<form action=\"index.php?name=".pnVarPrepForDisplay($GLOBALS['name'])."&amp;file=comments\" method=\"post\"><div>";
    echo '<h2>'._YOURNAME.":</h2> ";
    if (pnUserLoggedIn()) {
        echo "<a href=\"user.php\">" . pnVarPrepForDisplay(pnUserGetVar('uname')) . "</a> [ <a href=\"user.php?module=User&amp;op=logout\">"._LOGOUT."</a> ]";
    } else {
        echo pnVarPrepForDisplay($anonymous);
        $xanonpost=1;
    }
    echo "<br /><h2>"._SUBJECT.":</h2><br />";
    if (!eregi("Re:",$subject)) $subject = "Re: ".substr($subject,0,81)."";
    echo "<input type=\"text\" name=\"subject\" size=\"50\" maxlength=\"85\" value=\"" . pnVarPrepForDisplay($subject)."\" /><br />";
    echo "<br /><h2>"._COMMENT.":</h2><br />"
        ."<textarea cols=\"80\" rows=\"10\" name=\"comment\"></textarea><br />"
		._ALLOWEDHTML.'<br />';
        while (list($key, $access, ) = each($AllowableHTML)) {
            if ($access > 0) echo " &lt;".$key."&gt;";
        }
        echo '<br />';
    if (pnUserLoggedIn()) {
        echo "<input type=\"checkbox\" name=\"xanonpost\" /> "._POSTANON.'<br />';
    }
	list ($pid, $pollID, $mode, $order, $thold) = pnVarPrepForDisplay($pid, $pollID, $mode, $order, $thold);
    echo "<input type=\"hidden\" name=\"pid\" value=\"$pid\" />"
        ."<input type=\"hidden\" name=\"pollID\" value=\"$pollID\" />"
        ."<input type=\"hidden\" name=\"mode\" value=\"$mode\" />"
        ."<input type=\"hidden\" name=\"order\" value=\"$order\" />"
        ."<input type=\"hidden\" name=\"thold\" value=\"$thold\" />"
        ."<input type=\"submit\" name=\"req\" value=\""._PREVIEW."\" />"
        ."<input type=\"submit\" name=\"req\" value=\""._OK."\" />"
        ."<select name=\"posttype\">"
        ."<option value=\"exttrans\">"._EXTRANS."</option>"
        ."<option value=\"html\" >"._HTMLFORMATED."</option>"
        ."<option value=\"plaintext\" selected=\"selected\">"._PLAINTEXT."</option>"
        ."</select>"
        ."</div></form>";
    CloseTable();
    include('footer.php');
}

function replyPreview ($pid, $pollID, $subject, $comment, $xanonpost, $mode, $order, $thold, $posttype)
{
    include 'header.php';

    if(!isset($xanonpost)) {
    $xanonpost = '';
    }
    $AllowableHTML = pnConfigGetVar('AllowableHTML');
    $anonymous = pnConfigGetVar('anonymous');

    list($subject, $comment) = pnVarCleanFromInput('subject','comment');
    //$subject = stripslashes($subject);
    //$comment = stripslashes($comment);

    if (!isset($pid) || !isset($pollID)) {
        echo _NOTRIGHT;
        exit();
    }
    OpenTable();
    echo '<h2>'._POLLCOMPRE.'</h2>';
    CloseTable();

    OpenTable();
    echo pnVarPrepForDisplay($subject).'<br />';
    echo _BY." ";
    if (pnUserLoggedIn()) {
        echo pnVarPrepForDisplay(pnUserGetVar('uname'));
    } else {
        echo "$anonymous ";
    }
    echo "&nbsp;"._ONN.'<br />';
    if ($posttype=="exttrans") {
        echo nl2br(htmlspecialchars($comment));
    } elseif ($posttype=="plaintext") {
        echo nl2br(pnVarPrepForDisplay($comment));
    } else {
        echo pnVarPrepHTMLDisplay($comment);
    }
    CloseTable();

    OpenTable();
    echo "<form action=\"index.php?name=".pnVarPrepForDisplay($GLOBALS['name'])."&amp;file=comments\" method=\"post\"><div>"
        .'<h2>'._YOURNAME.":</h2> ";
    if (pnUserLoggedIn()) {
        echo "<a href=\"user.php\">" . pnVarPrepForDisplay(pnUserGetVar('uname')) . "</a> [ <a href=\"user.php?op=logout\">"._LOGOUT."</a> ]";
    } else {
        echo $anonymous;
    }
    echo "<br /><h2>"._SUBJECT.":</h2><br />"
        ."<input type=\"text\" name=\"subject\" size=\"50\" maxlength=\"85\" value=\"".pnVarPrepForDisplay($subject)."\"><br />"
        .'<h2>'._COMMENT.":</h2><br />"
        ."<textarea cols=\"80\" rows=\"10\" name=\"comment\">".pnVarPrepForDisplay($comment)."</textarea><br />";
        echo _ALLOWEDHTML.'<br />';
        while (list($key, $access, ) = each($AllowableHTML)) {
            if ($access > 0) echo " &lt;".$key."&gt;";
        }
        echo '<br />';
    if ($xanonpost == 1) {
        echo "<input type=\"checkbox\" name=\"xanonpost\" checked=\"checked\" /> "._POSTANON.'<br />';
    } elseif (pnUserLoggedIn()) {
        echo "<input type=\"checkbox\" name=\"xanonpost\" /> "._POSTANON.'<br />';
    }
	list ($pid, $pollID, $mode, $order, $thold) = pnVarPrepForDisplay($pid, $pollID, $mode, $order, $thold);
    echo "<input type=\"hidden\" name=\"pid\" value=\"$pid\" />"
        ."<input type=\"hidden\" name=\"pollID\" value=\"$pollID\" /><input type=\"hidden\" name=\"mode\" value=\"$mode\" />"
        ."<input type=\"hidden\" name=\"order\" value=\"$order\" /><input type=\"hidden\" name=\"thold\" value=\"$thold\" />"
        ."<input type=\"submit\" name=\"req\" value=\""._PREVIEW."\" />"
        ."<input type=\"submit\" name=\"req\" value=\""._OK."\" /> <select name=\"posttype\"><option value=\"exttrans\"";
        if($posttype=="exttrans") echo" selected=\"selected\"";
        echo  ">"._EXTRANS."<option value=\"html\"";;
        if($posttype=="html") echo" selected=\"selected\"";
        echo ">"._HTMLFORMATED."<option value=\"plaintext\"";
        if(($posttype!="exttrans") && ($posttype!="html")) echo" selected=\"selected\"";
        echo ">"._PLAINTEXT."</select></div></form>";
    CloseTable();
    include('footer.php');
}

function CreateTopic ($xanonpost, $subject, $comment, $pid, $pollID, $host_name, $mode, $order, $thold, $posttype)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (pnUserLoggedIn() && (!isset($xanonpost))) {
        $name = pnUserGetVar('uname');
        $email = pnUserGetVar('femail');
        $url = pnUserGetVar('url');
        $score = 1;
    } else {
        $name = ""; $email = ""; $url = "";
        $score = 0;
    }

    $ip = pnServerGetVar("REMOTE_HOST");
    if (empty($ip)) {
        $ip = pnServerGetVar("REMOTE_ADDR");
    }

    // default $pid if it is not set
    if (!$pid) $pid=0;

//begin fake thread control
    $column = &$pntable['poll_desc_column'];
    $result =& $dbconn->Execute("SELECT COUNT(*)
                              FROM $pntable[poll_desc]
                              WHERE $column[pollid]='".(int)pnVarPrepForStore($pollID)."'");

    list($fake) = $result->fields;

//begin duplicate control
    $column = &$pntable['pollcomments_column'];
    $result =& $dbconn->Execute("SELECT COUNT(*)
                              FROM $pntable[pollcomments]
                              WHERE $column[pid]='".(int)pnVarPrepForStore($pid)."' AND $column[pollid]='".(int)pnVarPrepForStore($pollID)."'
                                AND $column[subject]='".pnVarPrepForStore($subject)."'
                                AND $column[comment]='".pnVarPrepForStore($comment)."'");
    //added following line to fix duplicate comment bug. - Skooter
    list($tia) = $result->fields;
//begin troll control
    if(pnUserLoggedIn()) {
        $column = &$pntable['pollcomments_column'];
        $result =& $dbconn->Execute("SELECT COUNT(*)
                               FROM $pntable[pollcomments]
                               WHERE ($column[score]=-1)
                                 AND ($column[name]='" . pnVarPrepForStore(pnUserGetVar('uname')) . "'
                                 AND (to_days(now()) - to_days($column[date]) < 3)");

        list($troll) = $result->fields;
    } elseif(!$score) {
        $column = &$pntable['pollcomments_column'];
        $result =& $dbconn->Execute("SELECT COUNT(*)
                                  FROM $pntable[pollcomments]
                                  WHERE ($column[score]=-1)
                                    AND ($column[host_name]='".pnVarPrepForStore($ip)."')
                                    AND (to_days(now()) - to_days($column[date]) < 3)");

        list($troll) = $result->fields;
    }
    if((!$tia) && ($fake == 1) && ($troll < 6)) {
        $column = &$pntable['pollcomments_column'];
        $nextid = $dbconn->GenId($pntable['pollcomments']);
        $result =& $dbconn->Execute("INSERT INTO $pntable[pollcomments] ($column[tid],
                                    $column[pid], $column[pollid], $column[date],
                                    $column[name], $column[email], $column[url],
                                    $column[host_name], $column[subject],
                                    $column[comment], $column[score], $column[reason])
                                    VALUES ($nextid, '".(int)pnVarPrepForStore($pid)."', '".(int)pnVarPrepForStore($pollID)."', now(), '".pnVarPrepForStore($name)."',
                                      '".pnVarPrepForStore($email)."', '".pnVarPrepForStore($url)."', '".pnVarPrepForStore($ip)."', '".pnVarPrepForStore($subject)."', '".pnVarPrepForStore($comment)."',
                                      '".pnVarPrepForStore($score)."', '0')");
        if($dbconn->ErrorNo()<>0)
        {
            error_log("Error: creating pollcomments, " . $dbconn->ErrorMsg);
        }
    } else {
        include('header.php');
        if($tia) echo _DUPLICATE."<br /><a href=\"index.php?name=".pnVarPrepForDisplay($GLOBALS['name'])."&amp;req=results&amp;pollID=".pnVarPrepForDisplay($pollID)."\">Back to Poll</a>";
        elseif($troll > 5) echo _TROLLMESSAGE."<br /><a href=\"index.php?name=".pnVarPrepForDisplay($GLOBALS['name'])."&amp;pollID=".pnVarPrepForDisplay($pollID)."\">Back to Poll</a>";
        elseif($fake == 0) echo _TOPICMISSING;
        include('footer.php');
        exit;
    }
    pnRedirect("index.php?name=".$GLOBALS['name']."&req=results&pollID=".$pollID);

}


list($req, $pid, $pollID, $mode, $order, $thold) = 
    pnVarCleanFromInput('req','pid','pollID','mode','order','thold');

if (empty($mode) || (empty($order) && $order != 0) || (empty($thold) && $thold != 0)) {
	$commentoptions = pnUserGetCommentOptions(false);
	extract($commentoptions);
}

if(!isset($req)) {
    $req = '';
}

switch($req) {

    case "Reply":
        reply($pid, $pollID, $mode, $order, $thold);
        break;

    case ""._PREVIEW."":
        list($xanonpost,$subject,$comment,$posttype) = 
            pnVarCleanFromInput('xanonpost','subject','comment','posttype');
		if(empty($xanonpost)) {
			$xanonpost = 0;
		}
		replyPreview ($pid, $pollID, $subject, $comment, $xanonpost, $mode, $order, $thold, $posttype);
		break;

    case ""._OK."":
        list($xanonpost, $subject, $comment, $host_name, $posttype) = 
            pnVarCleanFromInput('xanonpost','subject','comment','host_name','posttype');
        CreateTopic($xanonpost, $subject, $comment, $pid, $pollID, $host_name, $mode, $order, $thold, $posttype);
        break;

    case "moderate":
		if(!isset($moderate)) {
			$moderate = 2;
		}
        if($moderate == 2) {
            while(list($tdw, $emp) = each($_POST)) {
                if (eregi("dkn",$tdw)) {
                    $emp = explode(":", $emp);
                    if($emp[1] != 0) {
                        $tdw = ereg_replace("dkn", "", $tdw);
                        $column = &$pntable['pollcomments_column'];
                        $q = "UPDATE $pntable[pollcomments] SET";
                        if(($emp[1] == 9) && ($emp[0]>=0)) { # Overrated
                            $q .= " $column[score]=$column[score]-1 WHERE $column[tid]='".(int)pnVarPrepForStore($tdw)."'";
                        } elseif (($emp[1] == 10) && ($emp[0]<=4)) { # Underrated
                            $q .= " $column[score]=$column[score]+1 WHERE $column[tid]='".(int)pnVarPrepForStore($tdw)."'";
                        } elseif (($emp[1] > 4) && ($emp[0]<=4)) {
                            $q .= " $column[score]=$column[score]+1, $column[reason]=$emp[1] WHERE $column[tid]='".(int)pnVarPrepForStore($tdw)."'";
                        } elseif (($emp[1] < 5) && ($emp[0] > -1)) {
                            $q .= " $column[score]=$column[score]-1, $column[reason]=$emp[1] WHERE $column[tid]='".(int)pnVarPrepForStore($tdw)."'";
                        } elseif (($emp[0] == -1) || ($emp[0] == 5)) {
                            $q .= " $column[reason]=$emp[1] WHERE $column[tid]='".(int)pnVarPrepForStore($tdw)."'";
                        }
                        if(strlen($q) > 20) $dbconn->Execute($q);
                    }
                }
            }
        }
		pnRedirect("index.php?name=".$GLOBALS['name']."&req=results&pollID=$pollID");
        break;

    case "showreply":
        DisplayTopic($pollID, $pid, $tid, $mode, $order, $thold);
        break;

    default:
        list ($tid, $pid) = pnVarCleanFromInput('tid','pid');
        if ((!empty($tid)) && (empty($pid))) {
            singlecomment($tid, $pollID, $mode, $order, $thold);
/*        } elseif (($mainfile) xor (($pid==0) AND (!isset($pid)))) {
            pnRedirect("index.php?name=".$GLOBALS['name']."&req=results&pollID=$pollID&mode=$mode&order=$order&thold=$thold");*/
        } else {
            if(!isset($pid)) $pid=0;
            DisplayTopic($pollID, $pid, $tid, $mode, $order, $thold);
        }
        break;
}

function check_words_poll($Message)
{
    $CensorMode = pnConfigGetVar('CensorMode');
    $CensorList = pnConfigGetVar('CensorList');
    $CensorReplace = pnConfigGetVar('CensorReplace');

    $EditedMessage = $Message;
    if ($CensorMode != 0) {

        if (is_array($CensorList)) {
            $Replace = $CensorReplace;
            if ($CensorMode == 1) {
                for ($i = 0; $i < count($CensorList); $i++) {
                    $EditedMessage = eregi_replace("$CensorList[$i]([^a-zA-Z0-9])","$Replace\\1",$EditedMessage);
                }
            } elseif ($CensorMode == 2) {
                for ($i = 0; $i < count($CensorList); $i++) {
                    $EditedMessage = eregi_replace("(^|[^[:alnum:]])$CensorList[$i]","\\1$Replace",$EditedMessage);
                }
            } elseif ($CensorMode == 3) {
                for ($i = 0; $i < count($CensorList); $i++) {
                    $EditedMessage = eregi_replace("$CensorList[$i]","$Replace",$EditedMessage);
                }
            }
        }
    }
    return ($EditedMessage);
}

?>
