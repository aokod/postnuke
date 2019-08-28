<?php
// File: $Id: addstory_functions.php 16853 2005-10-10 14:08:16Z landseer $
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
// Purpose of file: split very large admin.php into 2 manageable files
// ----------------------------------------------------------------------

// Security related changes and removed globals. - skooter

if (!defined('LOADED_AS_MODULE')) { die ('Access Denied'); }

function puthome($ihome)
{
    echo '<br /><strong>'._PUBLISHINHOME.'&nbsp;&nbsp;</strong>';
    if (($ihome == 0) OR ($ihome == '')) {
        $sel1 = ' checked="checked"';
        $sel2 = '';
    }
    if ($ihome == 1) {
        $sel1 = '';
        $sel2 = ' checked="checked"';
    }
    echo '<input type="radio" name="ihome" value="0"'.$sel1.' />'._YES.'&nbsp;'
        .'<input type="radio" name="ihome" value="1"'.$sel2.' />'._NO
        .'&nbsp;&nbsp;[ '._ONLYIFCATSELECTED.' ]<br />';
}

function withcomments($comm)
{
    echo '<br /><strong>'._ALLOWCOMMENTS.'</strong>&nbsp;&nbsp;';
    if (($comm == 0) OR ($comm == "")) {
    $csel1 = 'checked="checked"';
    $csel2 = '';
    }
    if ($comm == 1) {
    $csel1 = '';
    $csel2 = 'checked="checked"';
    }
    echo '<input type="radio" name="comm" value="0" '.$csel1.'/>'._YES.'&nbsp;'
      .'<input type="radio" name="comm" value="1" '.$csel2.'/>'._NO.'<br />';
}

// added by EB to reuse the code
function storyPreview($subject, $hometext, $bodytext="", $notes="", $topic, $format_type=0)
{
    if (empty($format_type)) {
        $format_type = 0;
    }

    // Get the format types. 'home' string is bits 0-1, 'body' is bits 2-3.
    $format_type_home = ($format_type%4);
    $format_type_body = (($format_type/4)%4);

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    list($subject,
         $hometext,
         $bodytext,
         $notes) = pnModCallHooks('item',
                                  'transform',
                                  '',
                                  array($subject,
                                        $hometext,
                                        $bodytext,
                                        $notes));

    $column = &$pntable['topics_column'];
    $result =& $dbconn->Execute("SELECT $column[topicimage]
                            FROM $pntable[topics]
                            WHERE $column[topicid]='" . pnVarPrepForStore($topic)."'");

    list($topicimage) = $result->fields;
    $tipath = pnConfigGetVar('tipath');
    OpenTable();
    echo '<img src="'.pnVarPrepForDisplay($tipath).pnVarPrepForDisplay($topicimage).'" alt="" />';
    echo '<h2>' . pnVarPrepHTMLDisplay($subject) . '</h2>';
    // Put br tags in only if format of hometext is 'text'.
    if ($format_type_home == 0) {
      $hometext = unnltobr($hometext);
        echo pnVarPrepHTMLDisplay(nl2br($hometext));
    } else {
        echo pnVarPrepHTMLDisplay($hometext);
    }

   //magicx : Removed instances of <br />
    if (!empty($bodytext)) {
        echo '<br />';
        // Put br tags in only if format of bodytext is 'text'
        if ($format_type_body == 0) {
          $bodytext = unnltobr($bodytext);
            echo pnVarPrepHTMLDisplay(nl2br($bodytext));
        } else {
            echo pnVarPrepHTMLDisplay($bodytext);
        }
    }
    if (!empty($notes)) {
        echo '<br /><strong>'._NOTE.'</strong> <em>' . pnVarPrepHTMLDisplay(nl2br($notes)) . '</em>';
    }
    CloseTable();
}

// added by EugenioBaldi to reuse the code
function storyEdit($subject, $hometext, $bodytext, $notes, $topic, $ihome, $catid, $alanguage, $comm, $aid, $informant, $format_type=0)
{
    if (empty($format_type)) {
        $format_type = 0;
    }

    // Get the format types. 'home' string is bits 0-1, 'body' is bits 2-3.
    $format_type_home = ($format_type%4);
    $format_type_body = (($format_type/4)%4);

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    echo '<strong>'._TITLE.'</strong><br />'
        .'<input type="text" name="subject" size="50" value="' . pnVarPrepForDisplay($subject) . '" /><br />';
  buildTopicsMenu($topic);
    SelectCategory($catid);
    echo '<br />';
    puthome($ihome);
    withcomments($comm);
  // new function !
  buildLanguageMenu(true,$alanguage);

    $bbcode = array('', '', '');
    if(pnModIsHooked('pn_bbcode', 'Submit_News') && pnModIsHooked('pn_bbcode', 'AddStory') && pnModIsHooked('pn_bbcode', 'News')) {
        $bbcode[0] = pnModFunc('pn_bbcode', 'user', 'bbcodes', array('textfieldid' => 'articletext'));
        $bbcode[1] = pnModFunc('pn_bbcode', 'user', 'bbcodes', array('textfieldid' => 'extendedtext'));
        $bbcode[2] = pnModFunc('pn_bbcode', 'user', 'bbcodes', array('textfieldid' => 'notestext'));
    }
    $bbsmile = array('', '', '');
    if(pnModIsHooked('pn_bbsmile', 'Submit_News') && pnModIsHooked('pn_bbsmile', 'AddStory') && pnModIsHooked('pn_bbsmile', 'News')) {
        pnModAPILoad('pn_bbsmile', 'user');
        $bbsmile[0] = pnModFunc('pn_bbsmile', 'user', 'bbsmiles', array('textfieldid' => 'articletext'));
        $bbsmile[1] = pnModFunc('pn_bbsmile', 'user', 'bbsmiles', array('textfieldid' => 'extendedtext'));
        $bbsmile[2] = pnModFunc('pn_bbsmile', 'user', 'bbsmiles', array('textfieldid' => 'notestext'));
    }

    echo '<br /><strong>'._STORYTEXT.'</strong><br />'
        .'<textarea cols="80" rows="10" name="hometext" id="articletext">';
    // Remove br tags only if format of hometext is 'text'.
    if ($format_type_home == 0)
    {
        echo pnVarPrepForDisplay(unnltobr($hometext));
    } else {
        echo pnVarPrepForDisplay($hometext);
    }
    echo '</textarea><br />'
        . $bbcode[0]
        . $bbsmile[0] ;

    // Choose format type for hometext.
    buildFormatTypeMenu("format_type_home", $format_type_home);

    echo '<br /><strong>'._EXTENDEDTEXT.'</strong><br />'
        .'<textarea cols="80" rows="10" name="bodytext" id="extendedtext">';
    // Remove br tags only if format of bodytext is 'text'
    // The bodytext flag is in bits 2 and 3.
    if ($format_type_body == 0) {
        echo pnVarPrepForDisplay(unnltobr($bodytext));
    } else {
        echo pnVarPrepForDisplay($bodytext);
    }
    echo '</textarea><br />'
       . $bbcode[1]
       . $bbsmile[1]
       . _PAGEBREAK.'<br />';
    // Choose format type for hometext.
    buildFormatTypeMenu("format_type_body", $format_type_body);

  echo '<br />'._AREYOUSURE.'<br />';
    if (pnSecAuthAction(0, 'Stories::', "$subject::", ACCESS_EDIT)) {
        echo '<strong>'._NOTES.'</strong><br />'
           . '<textarea cols="80" rows="5" name="notes" id="notestext">'
           . pnVarPrepForDisplay(unnltobr($notes)) . '</textarea>'
           . '<br />'
           . $bbcode[2]
           . $bbsmile[2];
    } else {
        echo '<input type="hidden" value="' . pnVarPrepForDisplay($notes). '" />';
    }
}

// judgej: get best-guess of the format types of the a content string.
// Returns: 0=text; 1=html
function estimateFormatType($content)
{
    // If the content string contains block-tags then is is likely to be HTML,
    // otherwise assume text.
    $block_elements = array(
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'div', 'center', 'form', 'hr', 'table',
        'p', 'pre', 'blockquote', 'address'
    );
    $pattern = '';
    $sep = '';

    // ereg pattern is <tag-name[ >]|<tag-name2[ >]|...
    foreach ($block_elements as $block_element)
    {
        $pattern = $pattern . $sep . '<' . $block_element . '[ >]';
        if ($sep == '') {$sep = '|';}
    }

    // If pattern matches then return 1 to indicate this probably contains HTML.
    if (eregi($pattern, $content)) {
        return 1;
    } else {
        return 0;
    }
}

// judgej: get default format types of the story components.
function defaultFormatType($hometext, $bodytext)
{
    $format_type_home = 0;
    $format_type_body = 0;

    $format_type_home = estimateFormatType($hometext);
    $format_type_body = estimateFormatType($bodytext);

    $format_type = ($format_type_body*4) + $format_type_home;

    return ($format_type);
}

// added by AV to reuse the code
function buildLanguageMenu($inheritselection, $alanguage)
{
  if (pnConfigGetVar('multilingual')) {
    if($inheritselection==true) { // && !empty($alanguage)){ Fix preventing alanguage staying chosen after preview.
      $lang = languagelist();
      if (!$alanguage) {
        $sel_lang[0] = ' selected="selected"';
      } else {
        $sel_lang[0] = '';
        $sel_lang[$alanguage] = ' selected="selected"';
      }
      print '<br /><strong>'._LANGUAGE.': </strong>' /* ML Dropdown with available languages to update */
        .'<select name="alanguage">'
        .'<option value="" '.$sel_lang[0].'>'._ALL.'</option>' ;
      $handle = opendir('language');
      while ($f = readdir($handle)) {
        if (is_dir("language/$f") && (!empty($lang[$f]))) {
          $langlist[$f] = $lang[$f];
        }
      }
      asort($langlist);
      //  a bit ugly, but it works in E_ALL conditions (Andy Varganov)
      foreach ($langlist as $k=>$v) {
       echo '<option value="'.$k.'"';
       if (isset($sel_lang[$k])) {
        echo ' selected="selected"';
       }
       echo '>'. $v . '</option>';
      }
      echo '</select>';
    } else {
      print '<br /><strong>'._LANGUAGE.': </strong>&nbsp;';
      lang_dropdown();
    }
  } else {
    echo '<input type="hidden" name="alanguage" value="" />';
  }

}

function buildFormatTypeMenu($field_name, $default_value)
{
  echo '<br /><strong>'. _FORMATTYPE .'</strong>&nbsp;'
    .'<select name="'.pnVarPrepForDisplay($field_name).'">';

    $format_types[] = Array('value' => 0, 'label' => _PLAINTEXT);
    $format_types[] = Array('value' => 1, 'label' => _HTMLFORMATED);

    foreach ($format_types as $format_type) {
        if ($format_type['value'] == $default_value) {
            $sel = ' selected="selected"';
        } else {
            $sel = '';
        }
    echo '<option value="' . $format_type['value'] . '"'.$sel.'>' . pnVarPrepForDisplay($format_type['label']) . '</option>'."\n";
    }
  echo '</select>';
}

// added by AV to reuse the code
function buildTopicsMenu($topic)
{

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

  $column = &$pntable['topics_column'];
  $toplist =& $dbconn->Execute("SELECT $column[topicid], $column[topictext], $column[topicname]
                 FROM $pntable[topics] ORDER BY $column[topictext]");
  echo '<strong>'._TOPIC.'</strong>&nbsp;'
    .'<select name="topic">';
  echo "<option value=\"\">"._SELECTTOPIC."</option>\n";

  while(list($topicid, $topics, $topicname) = $toplist->fields) {
    if (pnSecAuthAction(0, 'Topics::Topic', "$topicname::$topicid", ACCESS_COMMENT)) {
      $sel='';
      if ($topicid == $topic) {
          $sel='selected="selected"';
      }
        echo '<option value="'.pnVarPrepForDisplay($topicid).'" '.$sel.'>'.pnVarPrepForDisplay($topics).'</option>'."\n";
    }
    $toplist->MoveNext();
  }
  echo '</select><br />';
}

// added by AV to reuse the code
function buildCalendarMenu($automated, &$year, &$day, &$month, &$hour, &$min)
{
    $today = getdate();
    $tday = $today['mday'];
    if ($tday < 10){
        $tday = "0$tday";
    }
    $tmonth = $today['month'];
    $ttmon = $today['mon'];
    if ($ttmon < 10){
      $ttmon = "0$ttmon";
    }
    $tyear = $today['year'];
    $thour = $today['hours'];
    if ($thour < 10){
        $thour = "0$thour";
    }
    $tmin = $today['minutes'];
    if ($tmin < 10){
        $tmin = "0$tmin";
    }
    $tsec = $today['seconds'];
    if ($tsec < 10){
        $tsec = "0$tsec";
    }
    $date = "$tmonth $tday, $tyear @ $thour:$tmin:$tsec"; /* ML get the language from the queue table */
  $formatted_date = ml_ftime(_DATETIMELONG, mktime($today['hours'],$today['minutes'],$today['seconds'],$today['mon'],$today['mday'],$today['year']));
  if(!$automated){
      echo _NOWIS.': '.pnVarPrepForDisplay($formatted_date).'<br />';
    echo _HOUR.': <select name="hour">';
    $hour = 0;
    $cero = '0';
    while ($hour <= 23) {
      $dummy = $hour;
      if ($hour < 10) {
        $hour = "$cero$hour";
      }
      echo '<option>'.pnVarPrepForDisplay($hour).'</option>';
      $hour = $dummy;
      $hour++;
    }
    echo "</select>&nbsp;";
    echo ": <select name=\"min\">";
    $min = 0;
    while ($min <= 59) {
      if (($min == 0) OR ($min == 5)) {
        $min = "0$min";
      }
      echo "<option>".pnVarPrepForDisplay($min)."</option>";
      $min = $min + 5;
    }
    echo "</select>&nbsp;&nbsp;";
    $day = 1;
    echo _DAY.': <select name="day">';
    while ($day <= 31) {
      if ($tday==$day) {
        $sel = 'selected="selected"';
      } else {
        $sel = '';
      }
      echo '<option '.$sel.'>'.pnVarPrepForDisplay($day).'</option>';
      $day++;
    }
    echo '</select>&nbsp;&nbsp;';
    $month = 1;
    echo _MONTH.': <select name="month">';
    while ($month <= 12) {
      if ($ttmon==$month) {
        $sel = 'selected="selected"';
      } else {
        $sel = '';
      }
      echo '<option '.$sel.'>'.pnVarPrepForDisplay($month).'</option>';
      $month++;
    }
    echo '</select>&nbsp;&nbsp;';
    $date = getdate();
    $formatted_date = ml_ftime(_DATETIMELONG, mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']));
    $year = $date['year'];
    echo _YEAR.': <input type="text" name="year" value="'.pnVarPrepForDisplay($year).'" size="5" maxlength="4" /><br />';
  } else {
      echo _NOWIS.': '.pnVarPrepForDisplay($formatted_date).'<br />';
    echo _HOUR.': <select name="hour">';
    $xhour = 0;
    $cero = '0';
    while ($xhour <= 23) {
      $dummy = $xhour;
      if ($xhour < 10) {
        $xhour = "$cero$xhour";
      }
      if ($xhour == $hour) {
        $sel = 'selected="selected"';
      } else {
        $sel = '';
      }
      echo '<option '.$sel.'>'.pnVarPrepForDisplay($xhour).'</option>';
      $xhour = $dummy;
      $xhour++;
    }
    echo '</select>&nbsp;';
    echo ': <select name="min">';
    $xmin = 0;
    while ($xmin <= 59) {
      if (($xmin == 0) OR ($xmin == 5)) {
        $xmin = "0$xmin";
      }
      if ($xmin == $min) {
        $sel = 'selected="selected"';
      } else {
        $sel = '';
      }
      echo '<option '.$sel.'>'.pnVarPrepForDisplay($xmin).'</option>';
      $xmin = $xmin + 5;
    }
    echo '</select>&nbsp;';
    $xday = 1;
    echo _DAY.': <select name="day">';
    while ($xday <= 31) {
      if ($xday == $day) {
        $sel = 'selected="selected"';
      } else {
        $sel = '';
      }
      echo '<option '.$sel.'>'.pnVarPrepForDisplay($xday).'</option>';
      $xday++;
    }
    echo '</select>&nbsp;';
    $xmonth = 1;
    echo _MONTH.': <select name="month">';
    while ($xmonth <= 12) {
      if ($xmonth == $month) {
        $sel = 'selected="selected"';
      } else {
        $sel = '';
      }
      echo '<option '.$sel.'>'.pnVarPrepForDisplay($xmonth).'</option>';
      $xmonth++;
    }
    echo '</select>&nbsp;';
    echo _YEAR.': <input type="text" name="year" value="'.pnVarPrepForDisplay($year).'" size="5" maxlength="4" /><br />';
  }
}

function buildProgramStoryMenu($automated){
    if ($automated == 1) {
        $sel1 = 'checked="checked"';
        $sel2 = '';
    } else {
        $sel1 = '';
        $sel2 = 'checked="checked"';
    }
    echo '<strong>'._PROGRAMSTORY.'</strong>&nbsp;&nbsp;'
        .'<input type="radio" name="automated" value="1" '.$sel1.' />'._YES.' &nbsp;&nbsp;'
        .'<input type="radio" name="automated" value="0" '.$sel2.' />'._NO.'<br />';
}

?>