<?php
// File: $Id: index.php 19406 2006-07-12 08:29:28Z markwest $
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
// Original Author of this file: Francisco Burzi
// Purpose of this file:
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_MODULE')) {
    die ("You can't access this file directly...");
}

if (strtolower($name) == 'news') {
    $index = 0;
} else {
    $index = 1;
}

$ModName = basename(dirname( __FILE__ ));

modules_get_language();

include_once("modules/$ModName/funcs.php");

automatednews();

/**
 * Prints out the index
 * Prints out the index screen.
 * @return none
 * @author FB
 */
function theindex()
{
    global $themeOverrideCategory;

    list ($catid, $topic, $name, $startrow) = pnVarCleanFromInput('catid', 'topic', 'name', 'startrow');
    if (!isset($startrow) || !is_numeric($startrow)) {
        $startrow = 1;
    }

    // Check if the entered topic, catid and allstories vars are numeric
    if ((isset($topic) && !empty($topic) && !is_numeric($topic)) ||
        (isset($catid) && !empty($catid) && !is_numeric($catid)) ||
        (isset($allstories) && !empty($allstories) && !is_numeric($allstories))) {
        include 'header.php';
        echo _MODARGSERROR;
        include 'footer.php';
        return;
    }
    //End of check

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $currentlang = pnUserGetLang();

    if (pnUserLoggedIn()) {
        $storynum = pnUserGetVar('storynum');
    }
    if (empty($storynum)) {
        $storynum = pnConfigGetVar('storyhome');
    }

    if (pnConfigGetVar('multilingual') == 1) {
        $column = &$pntable['stories_column'];
        $querylang = "AND ($column[alanguage]='$currentlang' OR $column[alanguage]='')"; /* the OR is needed to display stories who are posted to ALL languages */
    } else {
        $querylang = '';
    }

    // use a theme override if we're displaying a category
    if ((!empty($catid)) && ($catid > 0)) {
        $column = &$pntable['stories_cat_column'];
        $result =& $dbconn->Execute("SELECT $column[themeoverride]
                                  FROM $pntable[stories_cat]
                                  WHERE $column[catid]='".(int)pnVarPrepForStore($catid)."'");
        //list($themeOverrideCategory) = $result->fields;
        if ($result) $themeOverrideCategory = $result->fields[0];
    }

    include 'header.php';

    echo '<div style="text-align:center;margin-bottom:10px;">';
    if (isset($topic) && !empty($topic) && is_numeric($topic)) {
        $column = &$pntable['topics_column'];
        $result =& $dbconn->Execute("SELECT $column[topicid], $column[topicname], " .
                "$column[topicimage], $column[topictext] FROM " .
                "$pntable[topics] WHERE $column[topicid]='".(int)pnVarPrepForStore($topic)."'");
        list($topicid, $topicname, $topicimage, $topictext) = $result->fields;
        if (!empty($topicimage)) {
            echo '<img style="float:right" src="'.pnConfigGetVar('tipath').$topicimage.'" alt="" />';
        }
        echo '<h1>' . _THISISTOPIC . pnVarPrepForDisplay($topictext) . '</h1>' . _FOLLOWINGNEWS;
        echo '<div style="clear:both"></div>';
        $GLOBALS['info']['title'] = $topictext;
    } else if (isset($catid) && $catid >= 0 && is_numeric($catid)) {
        $column = &$pntable['stories_cat_column'];
        $result =& $dbconn->Execute("SELECT $column[title] FROM $pntable[stories_cat]
                  WHERE $column[catid]=".(int)pnVarPrepForStore($catid)."");
        list($title) = $result->fields;
        echo '<h1>'._THISISCATEGORY.pnVarPrepForDisplay($title).'</h1>'._FOLLOWINGNEWSCATEGORY;
        $GLOBALS['info']['title'] = $title;
    }

    $storcol = &$pntable['stories_column'];
    $storcatcol = &$pntable['stories_cat_column'];
    $topiccol = &$pntable['topics_column'];
    if (!empty($catid) && !empty($topic)) { // show only one category and one topic
        $result =& $dbconn->Execute("UPDATE $pntable[topics] SET $topiccol[counter]=$topiccol[counter]+1 WHERE $topiccol[topicid]='".(int)pnVarPrepForStore($topic)."'");
        if($dbconn->ErrorNo() != 0) {
            error_log("DB Error updating $pntable[topics]: ". $dbconn->ErrorNo() . ": ". $dbconn->ErrorMsg());
        }

        $dbconn->Execute("UPDATE $pntable[stories_cat] SET $storcatcol[counter]=$storcatcol[counter]+1 WHERE $storcatcol[catid]='".(int)pnVarPrepForStore($catid)."'");
        if($dbconn->ErrorNo()<>0) {
            error_log("DB Error updating $pntable[stories_cat]: ". $dbconn->ErrorNo() . ": ". $dbconn->ErrorMsg());
        }

        $whereclause = "$topiccol[topicid]='".(int)pnVarPrepForStore($topic)."' AND $storcol[catid]='".(int)pnVarPrepForStore($catid)."' ";
    } else if (!empty($catid)) { // show only one category
        $dbconn->Execute("UPDATE $pntable[stories_cat] SET $storcatcol[counter]=$storcatcol[counter]+1 WHERE $storcatcol[catid]='".(int)pnVarPrepForStore($catid)."'");
        if($dbconn->ErrorNo() != 0) {
            error_log("DB Error updating $pntable[stories_cat]: ". $dbconn->ErrorNo() . ": ". $dbconn->ErrorMsg());
        }
        $whereclause = "$storcol[catid]='".(int)pnVarPrepForStore($catid)."' ";
    } else if (!empty($topic)) { // show only one category
        $dbconn->Execute("UPDATE $pntable[topics] SET $topiccol[counter]=$topiccol[counter]+1 WHERE $topiccol[topicid]='".(int)pnVarPrepForStore($topic)."'");
        if($dbconn->ErrorNo() != 0) {
            error_log("DB Error updating $pntable[topics]: ". $dbconn->ErrorNo() . ": ". $dbconn->ErrorMsg());
        }
        // eugeniobaldi 2002-02-18 fixed sf patch # 511223 All Cat. doesn't work whenTopic=#
        // $whereclause = "($topiccol[topicid]='".pnVarPrepForStore($topic)."' OR $topiccol[topicid]=0) AND ($storcol[ihome]=0 OR $storcol[catid]=0) ";
        $whereclause = "($topiccol[topicid]='".(int)pnVarPrepForStore($topic)."' OR $topiccol[topicid]=0) ";
    } else {
        $whereclause = "$storcol[ihome]=0";
    }

    switch (pnConfigGetVar('storyorder')) {
        case '1':
            $storyorder = "$storcol[time] DESC";
            break;
        default:
            $storyorder = "$storcol[sid] DESC";
            break;
    }

    $articles = getArticles($whereclause, $storyorder, $storynum, $startrow-1);

    // count of stories only if newspager is enabled
    if ((pnConfigGetVar('newspager') == 1) || isset($topic) || isset($catid)) {
        $storycount = count(getArticles($whereclause, $storyorder, ''));
        $pnRender = new pnRender('News');
        require_once $pnRender->_get_plugin_filepath('function','pager');
        $params =  array ('show' => 'page', 'shift' => 1, 'posvar' => 'startrow', 'rowcount' => $storycount, 'limit' => $storynum);
        $pagerstring = smarty_function_pager($params, $pnRender);
    }

    // slightly different logic to the pager below
    if (isset($topic) || isset($catid)) {
        echo '<div style="text-align:center">';
        // implement a pager if required
        if (isset($catid) && !empty($catid) && is_numeric($catid)) {
            $catstring = '&amp;catid='. $catid;
        } else {
            $catstring = '';
        }
        if (isset($topic) && !empty($topic) && is_numeric($topic)) {
            $topicstring = '&amp;topic='. $topic;
        } else {
            $topicstring = '';
        }
        echo $pagerstring;
        echo '</div>';
    }

    echo '</div>';

    if (!$articles && $GLOBALS['index'] == 0) {
        echo '<h2>'._NOARTICLESYET1;
        if ((!empty($catid)) or (!empty($topic))) {
            echo _NOARTICLESYET2;
        }
        echo '</h2>';
    } else {

        foreach ($articles as $row) {

            // $info is array holding raw information.
            // Used below and also passed to the theme - jgm
            $info = genArticleInfo($row);

            // Need to at least have overview permissions on this story
            // February 19, 2002 -- Rabbitt (aka Carl P. Corliss) -- Added Topics permission check
            if (pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_OVERVIEW) &&
                pnSecAuthAction(0, 'Topics::Topic',"$info[topicname]::$info[tid]",ACCESS_OVERVIEW)) {

                // $links is an array holding pure URLs to
                // specific functions for this article.
                // Used below and also passed to the theme - jgm
                $links = genArticleLinks($info);

                // $preformat is an array holding chunks of
                // preformatted text for this article.
                // Used below and also passed to the theme - jgm
                $preformat = genArticlePreformat($info, $links);

                if ($GLOBALS['postnuke_theme']) {
                    themeindex($info['aid'], $info['informant'], $info['longdatetime'], $info['catandtitle'], $info['counter'], $info['topic'], $preformat['hometext'], $info['notes'], $preformat['more'], $info['topicname'], $info['topicimage'], $info['topictext'], $info, $links, $preformat);
                } else {
                    themeindex($info['aid'], $info['informant'], $info['longdatetime'], $info['catandtitle'], $info['counter'], $info['topic'], $preformat['hometext'], $info['notes'], $preformat['more'], $info['topicname'], $info['topicimage'], $info['topictext']);
                }
            }
        }

        if ((pnConfigGetVar('newspager') == 1) || isset($topic) || isset($catid)) {
            echo '<div style="text-align:center">';
            // implement a pager if required
            if (isset($catid) && !empty($catid) && is_numeric($catid)) {
                $catstring = '&amp;catid='. $catid;
            } else {
                $catstring = '';
            }
            if (isset($topic) && !empty($topic) && is_numeric($topic)) {
                $topicstring = '&amp;topic='. $topic;
            } else {
                $topicstring = '';
            }
            echo $pagerstring;
            echo '</div>';
        }
    }

    include 'footer.php';
}

theindex();

?>