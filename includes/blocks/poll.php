<?php
// File: $Id: poll.php 16980 2005-11-04 09:07:41Z markwest $
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

if (strpos($_SERVER['PHP_SELF'], 'poll.php')) {
    die ("You can't access this file directly...");
}


$blocks_modules['poll'] = array(
    'func_display' => 'blocks_poll_block',
    'func_edit' => 'blocks_poll_select',
    'func_update' => 'blocks_poll_update',
    'text_type' => 'Poll',
    'text_type_long' => 'Display poll',
    'allow_multiple' => true,
    'form_content' => false
);

// Security
pnSecAddSchema('Pollblock::', 'Block title::');

/**
 * poll functions
 */
function pollMain($pollID, $row)
{
    if (!pnSecAuthAction(0, 'Pollblock::', "$row[title]::", ACCESS_READ)) {
        return;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // if pollID isn't set use the latest pollID. Skooter
    if (!isset($pollID)     ||
         empty($pollID)     ||
        !is_numeric($pollID) ||
        $pollID == 0 ) {
      $pollID = pollLatest();
    }
    //could be a bug if pollID '1' is deleted. Skooter
    //if(!isset($pollID)) {
    //   $pollID = 1;
    //}

    if(!isset($url)) {
        $url = sprintf("index.php?name=Polls&amp;req=results&amp;pollID=%d", $pollID);
    }
    $boxContent = "<form action=\"index.php?name=Polls\" method=\"post\"><div>";
    $boxContent .= "<input type=\"hidden\" name=\"pollID\" value=\"".$pollID."\" />";
    $boxContent .= "<input type=\"hidden\" name=\"forwarder\" value=\"".pnVarPrepForDisplay($url)."\" />";
    $column = &$pntable['poll_desc_column'];
    $result =& $dbconn->Execute("SELECT $column[polltitle], $column[voters]
                              FROM $pntable[poll_desc]
                              WHERE $column[pollid]=".(int)pnVarPrepForStore($pollID));
    if ($result->EOF) {
        return;
    }

    list($pollTitle, $voters) = $result->fields;
    $result->Close();

    if (!pnSecAuthAction(0, 'Polls::', "$row[title]::$pollID", ACCESS_OVERVIEW)) {
        return;
    }

    $boxContent .= "<h2>".pnVarPrepForDisplay($pollTitle)."</h2>\n";

    $column = &$pntable['poll_data_column'];
    $result =& $dbconn->Execute("SELECT $column[voteid],
                                        $column[optiontext],
                                        $column[optioncount]
                                 FROM $pntable[poll_data]
                                 WHERE ($column[pollid]=".(int)pnVarPrepForStore($pollID)."
                                 AND $column[optiontext] NOT LIKE \"\") ORDER BY $column[voteid]");

    $sum = 0;
    if (pnSecAuthAction(0, 'Polls::', "$row[title]::$pollID",ACCESS_COMMENT)) {
        $boxContent .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    } else {
        $boxContent .= '<ul>';
    }
    while(list($voteid, $optionText, $optionCount) = $result->fields) {
        if (pnSecAuthAction(0, 'Polls::', "$row[title]::$pollID",ACCESS_COMMENT)) {
            $boxContent .= '<tr>';
            $boxContent .= '<td valign="baseline">';
            $boxContent .= "<input type=\"radio\" name=\"voteID\" value=\"".pnVarPrepForDisplay($voteid)."\" id=\"poll_".pnVarPrepForDisplay($voteid)."\" tabindex=\"0\" />";
            $boxContent .= '</td>';
            $boxContent .= '<td>';
            $boxContent .= "<label for=\"poll_".pnVarPrepForDisplay($voteid)."\">".pnVarPrepForDisplay($optionText)."</label>";
            $boxContent .= '</td>';
            $boxContent .= '</tr>';
        } else {
            $boxContent .= "<li>".pnVarPrepForDisplay($optionText)."</li>\n";
        }
        $sum = $sum + $optionCount;
        $result->MoveNext();
    }
    if (pnSecAuthAction(0, 'Polls::', "$row[title]::$pollID",ACCESS_COMMENT)) {
        $boxContent .= "</table>\n";
        $boxContent .= "<p style=\"text-align:center\">
                        <input type=\"submit\" name=\"submitpoll\" value=\""._VOTE."\" tabindex=\"0\" />
                        <input type=\"hidden\" name=\"authid\" value=\"".pnSecGenAuthKey()."\" /></p>\n";
    } else {
        $boxContent .= '</ul>';
    }

    $boxContent .= "</div></form>\n";

    $boxContent .=  '<p style="text-align:center">[ ';
    if (pnSecAuthAction(0, 'Polls::', "$row[title]::$pollID", ACCESS_READ)) {
        $boxContent .= "<a href=\"index.php?name=Polls&amp;req=results&amp;pollID=".(int)$pollID."\"><strong>"._RESULTS."</strong></a> | \n";
    }
    $boxContent .= '<a href="index.php?name=Polls&amp;file=index"><strong>'._POLLS.'</strong></a> ]</p>';
    if (pnConfigGetVar('pollcomm')) {
        $column = &$pntable['pollcomments_column'];
        $comres =& $dbconn->Execute("SELECT COUNT(*) FROM $pntable[pollcomments] WHERE $column[pollid]='".(int)pnVarPrepForStore($pollID)."'");
        list($numcom) = $comres->fields;
        $boxContent .= "\n<p style=\"text-align:center\">"._NUMVOTES.": <strong>".pnVarPrepForDisplay($sum)."</strong><br />"._PCOMMENTS." <strong>".pnVarPrepForDisplay($numcom)."</strong></p>\n\n";
    } else {
        $boxContent .= "\n<p style=\"text-align:center\">"._NUMVOTES.": <strong>".pnVarPrepForDisplay($sum)."</strong></p>\n\n";
    }

    if (empty($row['title'])) {
        $row['title'] = _POLL;
    }

    if (empty($row['position'])) {
        $row['position'] = "c";
    }

    $row['content'] = $boxContent;
    return themesideblock($row);
}

function pollLatest()
{
    static $pollID;

    if (!isset($pollID)) {
        $dbconn =& pnDBGetConn(true);
        $pntable =& pnDBGetTables();
        $currentlang = pnUserGetLang();

        if (pnConfigGetVar('multilingual') == 1) {
            $column = &$pntable['poll_desc_column'];
            $querylang = "WHERE ($column[planguage]='".pnVarPrepForStore($currentlang)."' OR $column[planguage]='')";
        } else {
            $querylang = '';
        }
        $column = &$pntable['poll_desc_column'];
        $sql = "SELECT $column[pollid] FROM $pntable[poll_desc] $querylang ORDER BY $column[pollid] DESC";
        $result = $dbconn->SelectLimit($sql,1);

        $pollID = $result->fields;
    }
    return($pollID[0]);
}

function pollNewest()
{
    $pollID = pollLatest();
    $row = array();
    $row['title'] = '';
    return pollMain($pollID,$row);
}

function pollCollector($pollID, $voteID, $forwarder)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column = &$pntable['poll_desc_column'];
    $result =& $dbconn->Execute("SELECT $column[polltitle], $column[voters]
                              FROM $pntable[poll_desc]
                              WHERE $column[pollid]=".(int)pnVarPrepForStore($pollID));
    if ($result->EOF) {
        $warn = pnVarPrepForDisplay(_POLL_NOVOTE);
    } else {

        list($pollTitle, $voters) = $result->fields;
        $result->Close();

        if (pnSecAuthAction(0, 'Polls::', "$pollTitle::$pollID", ACCESS_COMMENT)) {
            // Check that the user hasn't voted for this poll already
            if (pnSessionGetVar("poll_voted$pollID")) {
                $warn = pnVarPrepForDisplay(_POLL_VOTEDTODAY);
            } else {
                pnSessionSetVar("poll_voted$pollID", 1);
                $column = &$pntable['poll_data_column'];
                $dbconn->Execute("UPDATE $pntable[poll_data] SET $column[optioncount]=$column[optioncount]+1 WHERE ($column[pollid]=" . (int)pnVarPrepForStore($pollID) . ") AND ($column[voteid]=" . (int)pnVarPrepForStore($voteID) . ')');
                $column = &$pntable['poll_desc_column'];
                $dbconn->Execute("UPDATE $pntable[poll_desc] SET $column[voters]=$column[voters]+1 WHERE $column[pollid]=" . (int)pnVarPrepForStore($pollID));
            }
        } else {
            $warn = pnVarPrepForDisplay(_POLL_NOVOTE);
        }
    }
    if($warn) {
        include('header.php');
        echo pnVarPrepForDisplay($warn);
        include('footer.php');
        exit;
    }
    pnRedirect($forwarder);
}

function pollList()
{
    if (!pnSecAuthAction(0, 'Polls::', "::", ACCESS_OVERVIEW)) {
        return;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $currentlang = pnUserGetLang();

    if (pnConfigGetVar('multilingual') == 1) {
        $column = &$pntable['poll_desc_column'];
        $querylang =  "WHERE ($column[planguage]='".pnVarPrepForStore($currentlang)."' OR $column[planguage]='')";
    } else {
        $querylang = '';
    }
    $column = &$pntable['poll_desc_column'];
    $result =& $dbconn->Execute("SELECT $column[pollid], $column[polltitle], $column[timestamp], $column[voters] FROM $pntable[poll_desc] $querylang ORDER BY $column[timestamp]");
    OpenTable();
    OpenTable();
    echo '<h2>'._PASTSURVEYS.'</h2>';
    CloseTable();

    echo '<ul>';
    $counter = 0;
    $resultArray = array();
    while($thisresult = $result->fields) {

        $result->MoveNext();
        $resultArray[$counter] = $thisresult;
        $counter++;
    }
    for ($count = 0; $count < count($resultArray); $count++) {
        $id = $resultArray[$count][0];
        $pollTitle = $resultArray[$count][1];
        $voters = $resultArray[$count][3];
        $column = &$pntable['poll_data_column'];
        $result2 =& $dbconn->Execute("SELECT SUM($column[optioncount]) AS sum FROM $pntable[poll_data] WHERE $column[pollid]='".(int)pnVarPrepForStore($id)."'");
        list($sum) = $result2->fields;
        echo "<li><a href=\"index.php?name=Polls&amp;pollID=$id\">".pnVarPrepForDisplay($pollTitle).'</a> ';
        echo "(<a href=\"index.php?name=Polls&amp;req=results&amp;pollID=".(int)pnVarPrepForDisplay($id)."\">"._RESULTS."</a> - ".(int)pnVarPrepForDisplay($sum)." "._LVOTES.")</li>\n";
    }
    echo '</ul>';
    CloseTable();
}

function pollResults($pollID)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // if pollID isn't set use the latest pollID. Skooter
    //if(!isset($pollID)) $pollID = 1;  //could be a bug if pollID '1' is deleted. Skooter
    if(!isset($pollID) || empty($pollID) ||
        (is_numeric($pollID) && ($pollID == 0)) ) {
        $pollID = pollLatest();
    }
    $column = &$pntable['poll_desc_column'];
    $result =& $dbconn->Execute("SELECT $column[polltitle] FROM $pntable[poll_desc] WHERE $column[pollid]=".(int)pnVarPrepForStore($pollID));
    list($holdtitle) = $result->fields;
    echo "<br /><strong>".pnVarPrepForDisplay($holdtitle)."</strong><br /><br />";
    $result->Close();
    $column = &$pntable['poll_data_column'];
    $result =& $dbconn->Execute("SELECT SUM($column[optioncount]) AS sum FROM $pntable[poll_data] WHERE $column[pollid]=".(int)pnVarPrepForStore($pollID));
    list($sum) = $result->fields;
    $result->Close();
    echo "<table border=\"0\">";
    /* cycle through all options */
    $column = &$pntable['poll_data_column'];
    $result =& $dbconn->Execute("SELECT $column[optiontext], $column[optioncount] FROM $pntable[poll_data] WHERE ($column[pollid]=".(int)pnVarPrepForStore($pollID)." AND $column[optiontext] NOT LIKE \"\") ORDER BY $column[voteid]");
    while(list($optionText, $optionCount) = $result->fields) {

        $result->MoveNext();
        echo '<tr><td>';
        echo pnVarPrepForDisplay($optionText);
        echo '</td>';
        if($sum) {
            $percent = 100 * $optionCount / $sum;
        } else {
            $percent = 0;
        }
        echo "<td>";
        $percentInt = (int)$percent * 4 * pnConfigGetVar('BarScale');
        $percent2 = (int)$percent;

        $ThemeSel = pnUserGetTheme();

        if ($percent > 0) {
            echo "<img src=\"themes/$ThemeSel/images/leftbar.gif\" height=\"15\" width=\"7\" alt=\"$percent2 %\" />";
            echo "<img src=\"themes/$ThemeSel/images/mainbar.gif\" height=\"15\" width=\"$percentInt\" alt=\"$percent2 %\" />";
            echo "<img src=\"themes/$ThemeSel/images/rightbar.gif\" height=\"15\" width=\"7\" alt=\"$percent2 %\" />";
        } else {
            echo "<img src=\"themes/$ThemeSel/images/leftbar.gif\" height=\"15\" width=\"7\" alt=\"$percent2 %\" />";
            echo "<img src=\"themes/$ThemeSel/images/mainbar.gif\" height=\"15\" width=\"3\" alt=\"$percent2 %\" />";
            echo "<img src=\"themes/$ThemeSel/images/rightbar.gif\" height=\"15\" width=\"7\" alt=\"$percent2 %\" />";
        }
        printf(" %.2f %% (%d)", $percent, $optionCount);
        echo "</td></tr>";
    }
    echo "</table><br />";
    echo "<p style=\"text-align:center\">";
    echo "<strong>"._TOTALVOTES." $sum</strong><br />";
    echo "<span class=\"pn-sub\">"._ONEPERDAY."</span><br /><br />";
    $booth = $pollID;
    echo("[ <a href=\"index.php?name=Polls&amp;pollID=".(int)pnVarPrepForDisplay($booth)."\">"._VOTING."</a> | ");
    echo("<a href=\"index.php?name=Polls&amp;file=index\">"._OTHERPOLLS."</a> ]</p>");
    return(1);
}

function blocks_poll_block($row)
{
    if (!pnModAvailable('Polls')) {
        return;
    }
// for MSSQL that always have an space
    $row['content'] = trim($row['content']);
    if (!empty($row['content'])) {
        $pollID = $row['content'];
    } else {
        $pollID = pollLatest();
    }
    return pollMain($pollID, $row);
}

function blocks_poll_select($row)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $zerochecked = '';
    $onechecked = '';

    if (!empty($row['content'])) {
        $pollID = $row['content'];
        $showspecific = 1;
        $onechecked = 'checked="checked"';
    } else {
        $pollID = 0;
        $showspecific = 0;
        $zerochecked = 'checked="checked"';
    }

    $output = '<tr><td><h2>'._POLL_DISPLAY.':<h2></td></tr>';
    $output .= '<tr><td>'._POLL_LATEST."</td><td><input type=\"radio\" name=\"polltype\" value=\"0\" $zerochecked></td></tr>";
    $output .= '<tr><td>'._POLL_SPECIFIC."</td><td><input type=\"radio\" name=\"polltype\" value=\"1\" $onechecked>&nbsp;&nbsp;";

    $output .= '<select name="pollid">';

    // Get list of polls
    $polltable = $pntable['poll_desc'];
    $pollcolumn = $pntable['poll_desc_column'];
    $sql = "SELECT $pollcolumn[polltitle],
                   $pollcolumn[pollid]
            FROM $polltable
            ORDER BY $pollcolumn[polltitle]";
    $result =& $dbconn->Execute($sql);
    while(list($title, $id) = $result->fields) {
        $result->MoveNext();
        $output .= '<option value="'.pnVarPrepForDisplay($id).'"';
        if ($pollID == $id) {
           $output .= ' selected="selected"';
        }
        $output .= '>'.pnVarPrepForDisplay($title).'</option>';
    }
    $result->Close();

    $output .= '</select></td></tr>';

    return $output;
}

function blocks_poll_update($row)
{
    if (($row['polltype'] == 1) && (!empty($row['pollid']))) {
        $row['content'] = $row['pollid'];
    } else {
        $row['content'] = '';
    }

    return($row);
}

?>