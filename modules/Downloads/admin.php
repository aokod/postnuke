<?php
// File: $Id: admin.php 19702 2006-08-16 20:27:49Z larsneo $
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

$ModName = $module;
modules_get_language();
modules_get_manual();

include_once ("modules/$ModName/dl-categories.php");
include_once ("modules/$ModName/dl-util.php");


/**
 * Downloads Modified Web Downloads
 */

function downloads() {
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    include ('header.php');
    GraphicAdmin();
    OpenTable();

    if (!pnSecAuthAction(0, 'Downloads::', '::', ACCESS_EDIT)) {
        echo _DOWNLOADSACCESSNOAUTH;
        include 'footer.php';
        return;
    }
    echo "<div style=\"text-align:center\"><h1>"._DLOADPAGETITLE.'</h1>';
    $result =& $dbconn->Execute("SELECT count(*) FROM $pntable[downloads_downloads]");
    list($numrows) = $result->fields;
    echo _THEREARE." <strong>".pnVarPrepForDisplay($numrows).'</strong> '._DOWNLOADSINDB.'</div>';
    CloseTable();

/* Temporarily 'homeless' downloads functions (to be revised in admin.php breakup ) */
    $result =& $dbconn->Execute("SELECT count(*)
                                FROM $pntable[downloads_modrequest]
                                WHERE {$pntable['downloads_modrequest_column']['brokendownload']}=1");

    list($totalbrokendownloads) = $result->fields;
    $result =& $dbconn->Execute("SELECT count(*)
                                FROM $pntable[downloads_modrequest]
                                WHERE {$pntable['downloads_modrequest_column']['brokendownload']}=0");

    list($totalmodrequests) = $result->fields;

/* List Downloads waiting for validation */

    $column = &$pntable['downloads_newdownload_column'];
    $result =& $dbconn->Execute("SELECT $column[lid],
                                       $column[cid],
                                       $column[sid],
                                       $column[title],
                                       $column[url],
                                       $column[description],
                                       $column[name],
                                       $column[email],
                                       $column[submitter],
                                       $column[filesize],
                                       $column[version],
                                       $column[homepage]
                                FROM $pntable[downloads_newdownload]
                                ORDER BY $column[lid]");
    if (!$result->EOF) {
        OpenTable();
        echo '<h2>'._DOWNLOADSWAITINGVAL.'</h2>';
        while(list($lid, $cid, $sid, $title, $url, $description, $name, $email, $submitter, $filesize, $version, $homepage) = $result->fields)	 {

            $result->MoveNext();
            if ($submitter == "") {
                $submitter = _NONE;
            }
            $homepage = ereg_replace("http://","",$homepage);
            echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";  // changed layout into a table
            echo " <tr><td colspan=\"4\"><form action=\"admin.php\" method=\"post\"><div>"
                .'<strong>'._DOWNLOADID.": $lid</strong></td></tr>"
                ."\n<tr><td colspan=\"4\">"._SUBMITTER.": <strong>".pnVarPrepForDisplay($submitter)."</strong></td></tr>"
                ."\n<tr><td colspan=\"4\">"._DOWNLOADNAME.": <input type=\"text\" name=\"title\" value=\"".pnVarPrepForDisplay($title)."\" size=\"50\" maxlength=\"100\" /></td></tr>"
                ."\n<tr><td colspan=\"4\">"._FILEURL.": <input type=\"text\" name=\"url\" value=\"".pnVarPrepForDisplay($url)."\" size=\"50\" maxlength=\"254\" />&nbsp;[ <a href=\"".pnVarPrepForDisplay($url)."\">"._CHECK."</a> ]</td></tr>"
                ."\n<tr><td colspan=\"4\">"._DESCRIPTION.": <br /><textarea name=\"description\" cols=\"80\" rows=\"10\">".pnVarPrepForDisplay($description)."</textarea></td></tr>"
                ."\n<tr><td colspan=\"4\">"._AUTHORNAME.": <input type=\"text\" name=\"name\" size=\"20\" maxlength=\"100\" value=\"".pnVarPrepForDisplay($name)."\" />&nbsp;&nbsp;"
                .""._AUTHOREMAIL.": <input type=\"text\" name=\"email\" size=\"20\" maxlength=\"100\" value=\"".pnVarPrepForDisplay($email)."\" /></td></tr>"
                ."\n<tr><td colspan=\"4\">"._FILESIZE.": <input type=\"text\" name=\"filesize\" size=\"12\" maxlength=\"11\" value=\"".pnVarPrepForDisplay($filesize)."\" /></td></tr>"
                ."\n<tr><td colspan=\"4\">"._VERSION.": <input type=\"text\" name=\"version\" size=\"11\" maxlength=\"10\" value=\"".pnVarPrepForDisplay($version)."\" /></td></tr>"
                ."\n<tr><td colspan=\"4\">"._HOMEPAGE.": <input type=\"text\" name=\"homepage\" size=\"30\" maxlength=\"200\" value=\"http://".pnVarPrepForDisplay($homepage)."\" /> [ <a =\"http://".pnVarPrepForDisplay($homepage)."\">"._VISIT."</a> ]</td></tr>";
            $column = &$pntable['downloads_categories_column'];
            $result2 =& $dbconn->Execute("SELECT $column[cid],
                                              $column[title]
                                       FROM $pntable[downloads_categories]
                                       ORDER BY $column[title]");
            echo "<tr><td valign=\"top\"><input type=\"hidden\" name=\"new\" value=\"1\">"
                ."\n<input type=\"hidden\" name=\"hits\" value=\"0\" />"
                ."\n<input type=\"hidden\" name=\"lid\" value=\"$lid\" />"
                ."\n<input type=\"hidden\" name=\"submitter\" value=\"$submitter\" />"
                ."<input type=\"hidden\" name=\"module\" value=\"".$GLOBALS['module']."\" />"
                ._CATEGORY.": <select name=\"cat\">";

            while(list($ccid, $ctitle) = $result2->fields) {
                $sel = "";
                if ($cid==$ccid AND $sid==0) {
                    $sel = "selected";
                }
                echo "<option value=\"$ccid\" $sel>".pnVarPrepForDisplay($ctitle)."</option>";
                $column = &$pntable['downloads_subcategories_column'];
                $result3 =& $dbconn->Execute("SELECT $column[sid],
                                                  $column[title]
                                           FROM $pntable[downloads_subcategories]
                                           WHERE $column[cid]='".pnVarPrepForStore($ccid)."'
                                           ORDER BY $column[title]");

                while(list($ssid, $stitle) = $result3->fields) {
                    $sel = "";
                    if ($sid == $ssid) {
                        $sel = "selected";
                    }
                    echo "<option value=\"$ccid-$ssid\" $sel>".pnVarPrepForDisplay($ctitle)." / ".pnVarPrepForDisplay($stitle)."</option>";
                    $result3->MoveNext();
                }
                $result2->MoveNext();
            }
            echo "</select></td><td><input type=\"hidden\" name=\"submitter\" value=\"$submitter\" />";
            echo "<input type=\"hidden\" name=\"op\" value=\"DownloadsAddDownload\" />"
                 .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
            ."\n<input type=\"submit\" value="._ADD." />&nbsp;</div></form></td><td>"
            ."\n<form action=\"admin.php\" method=\"post\"><div>"
            ."\n<input type=\"hidden\" name=\"op\" value=\"DownloadsDelNew\" />"
            ."\n<input type=\"hidden\" name=\"authid\" value=\"" . pnSecGenAuthKey() . "\" />"
            ."\n<input type=\"hidden\" name=\"lid\" value=\"$lid\" />"
            ."<input type=\"hidden\" name=\"module\" value=\"".$GLOBALS['module']."\" />"
            ."\n<input type=\"submit\" value=\""._DELETE."\"></div></form></td>"
            ."\n<td width=\"10%\">&nbsp;</td></tr></table><hr /><br />\n\n";

        }
        CloseTable();
    }

    OpenTable();
    echo "<div style=\"text-align:center\">[ <a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=DownloadsCleanVotes&amp;authid=" . pnSecGenAuthKey() . "\">"._CLEANDOWNLOADSDB."</a> | "
    ."<a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=DownloadsListBrokenDownloads\">"._BROKENDOWNLOADSREP." (".pnVarPrepForDisplay($totalbrokendownloads).")</a> | "
    ."<a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=DownloadsListModRequests\">"._DOWNLOADMODREQUEST." (".pnVarPrepForDisplay($totalmodrequests).")</a> | "
    ."<a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=DownloadsDownloadCheck\">"._VALIDATEDOWNLOADS."</a> ]</div>";
    CloseTable();

/* Add a New Main Category */
    DownloadsNewCat();

// Add a New Sub-Category
    DownloadsNewSubCat();

// Add a New Download to Database
/*
 * Hootbah: XXX FIXME XXX
 * This seems a little odd. Why do the same query again.
 */
    $column = &$pntable['downloads_categories_column'];
    $result =& $dbconn->Execute("SELECT $column[cid],
                                       $column[title]
                                FROM $pntable[downloads_categories]
                                ORDER BY $column[title]");
    if (!$result->EOF) {
    OpenTable();
    echo "<form method=\"post\" action=\"admin.php\"><div>"
        .'<h2>'._ADDNEWDOWNLOAD.'</h2>'
        .""._DOWNLOADNAME.": <input type=\"text\" name=\"title\" size=\"50\" maxlength=\"100\" /><br />"
        .""._FILEURL.": <input type=\"text\" name=\"url\" size=\"50\" maxlength=\"100\" value=\"http://\" /><br />";
    echo ""._CATEGORY.": <select name=\"cat\">";

    while(list($cid, $title) = $result->fields) {
        echo "<option value=\"$cid\">".pnVarPrepForDisplay($title)."</option>";
        $column = &$pntable['downloads_subcategories_column'];
        $result2 =& $dbconn->Execute("SELECT $column[sid],
                                          $column[title]
                                   FROM $pntable[downloads_subcategories]
                                   WHERE $column[cid]='".pnVarPrepForStore($cid)."'
                                   ORDER BY $column[title]");

        while(list($sid, $stitle) = $result2->fields) {
            echo "<option value=\"$cid-$sid\">".pnVarPrepForDisplay($title)." / ".pnVarPrepForDisplay($stitle)."</option>";
            $result2->MoveNext();
        }
        $result->MoveNext();
    }
    echo "</select><br />"
        .""._DESCRIPTION255."<br /><textarea name=\"description\" cols=\"80\" rows=\"10\"></textarea><br />"
        .""._AUTHORNAME.": <input type=\"text\" name=\"name\" size=\"30\" maxlength=\"60\" /><br />"
        .""._AUTHOREMAIL.": <input type=\"text\" name=\"email\" size=\"30\" maxlength=\"60\" /><br />"
        .""._FILESIZE.": <input type=\"text\" name=\"filesize\" size=\"12\" maxlength=\"11\" /> ("._INBYTES.")<br />"
        .""._VERSION.": <input type=\"text\" name=\"version\" size=\"11\" maxlength=\"10\" /><br />"
        .""._HOMEPAGE.": <input type=\"text\" name=\"homepage\" size=\"30\" maxlength=\"200\" value=\"http://\" /><br />"
        .""._HITS.": <input type=\"text\" name=\"hits\" size=\"12\" maxlength=\"11\" value=\"0\" /><br />"
        ."<input type=\"hidden\" name=\"module\" value=\"".$GLOBALS['module']."\" />"
        ."<input type=\"hidden\" name=\"op\" value=\"DownloadsAddDownload\" />"
            ."<input type=\"hidden\" name=\"new\" value=\"0\" />"
            .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
        ."<input type=\"hidden\" name=\"lid\" value=\"0\" />"
        ."<div style=\"text-align:center\"><input type=\"submit\" value=\""._ADDURL."\" /></div><br />"
        ."</div></form>";
    CloseTable();
    }

// Modify Category

    $column = &$pntable['downloads_categories_column'];
    $result =& $dbconn->Execute("SELECT $column[cid],
                                     $column[title]
                              FROM $pntable[downloads_categories]
                              ORDER BY $column[title]");
    if (!$result->EOF) {
        OpenTable();
        echo "<form method=\"post\" action=\"admin.php\"><div>"
            .'<h2>'._MODCATEGORY.'</h2>';
        echo ""._CATEGORY.": <select name=\"cat\">";

        while(list($cid, $title) = $result->fields) {
            echo "<option value=\"$cid\">".pnVarPrepForDisplay($title)."</option>";
            $column = &$pntable['downloads_subcategories_column'];
            $result2 =& $dbconn->Execute("SELECT $column[sid],
                                              $column[title]
                                       FROM $pntable[downloads_subcategories]
                                       WHERE $column[cid]='" . pnVarPrepForStore($cid). "'
                                       ORDER BY $column[title]");

            while(list($sid, $stitle) = $result2->fields) {
                echo "<option value=\"$cid-$sid\">".pnVarPrepForDisplay($title)." / ".pnVarPrepForDisplay($stitle)."</option>";
                $result2->MoveNext();
            }
            $result->MoveNext();
        }
        echo "</select>"
            ."<input type=\"hidden\" name=\"module\" value=\"".$GLOBALS['module']."\" />"
            ."<input type=\"hidden\" name=\"op\" value=\"DownloadsModCat\" />"
            .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
            ."<input type=\"submit\" value=\""._MODIFY."\" />"
            ."</div></form>";
        CloseTable();
    }

// Modify Downloads

    $result =& $dbconn->Execute("SELECT COUNT(1)
                                FROM $pntable[downloads_downloads]");
    list($numrows) = $result->fields;
    if ($numrows>0) {
        OpenTable();
        echo "<form method=\"post\" action=\"admin.php\"><div>"
            .'<h2>'._MODDOWNLOAD.'</h2>'
            .""._DOWNLOADID.": <input type=\"text\" name=\"lid\" size=\"12\" maxlength=\"11\" />&nbsp;&nbsp;"
            ."<input type=\"hidden\" name=\"module\" value=\"".$GLOBALS['module']."\" />"
            ."<input type=\"hidden\" name=\"op\" value=\"DownloadsModDownload\" />"
            .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
            ."<input type=\"submit\" value=\""._MODIFY."\" />"
            ."</div></form>";
        CloseTable();
    }

// Access Download Settings
    OpenTable();
    echo '<h2>'._DOWNLOADSCONF.'</h2>';
    echo "<div style=\"text-align:center\"><a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=getConfig\">"._DOWNLOADSCONF."</a></div>";
    CloseTable();
    include ('footer.php');
}

function DownloadsModDownload() {

    $lid = pnVarCleanFromInput('lid');

    /*if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }
    */
    if	(!isset($lid) || !is_numeric($lid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    include ('header.php');
    GraphicAdmin();

    $anonymous = pnConfigGetVar('anonymous');

    $column = &$pntable['downloads_downloads_column'];
    $result =& $dbconn->Execute("SELECT $column[cid],
                                       $column[sid],
                                       $column[title],
                                       $column[url],
                                       $column[description],
                                       $column[name],
                                       $column[email],
                                       $column[hits],
                                       $column[filesize],
                                       $column[version],
                                       $column[homepage]
                                FROM $pntable[downloads_downloads]
                                WHERE $column[lid]='" . (int)pnVarPrepForStore($lid)."'");
    OpenTable();
    echo '<h2>'._WEBDOWNLOADSADMIN.'</h2>';
    CloseTable();

    OpenTable();
    echo '<h2>'._MODDOWNLOAD.'</h2>';
    while(list($cid, $sid, $title, $url, $description, $name, $email, $hits, $filesize, $version, $homepage) = $result->fields) {

        $result->MoveNext();

        $homepage = ereg_replace("http://","",$homepage);
		echo "<form style=\"display:inline\" action=\"admin.php\" method=\"post\"><div style=\"display:inline\">";
        echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";  // changed layout into a table
        echo " <tr><td colspan=\"4\">"
        .'<strong>'._DOWNLOADID.": $lid</strong></td></tr>"
        ."\n<tr><td colspan=\"4\">"._DOWNLOADNAME.": <input type=\"text\" name=\"title\" value=\"".pnVarPrepForDisplay($title)."\" size=\"50\" maxlength=\"100\" /></td></tr>"
        ."\n<tr><td colspan=\"4\">"._FILEURL.": <input type=\"text\" name=\"url\" value=\"$url\" size=\"50\" maxlength=\"254\" />&nbsp;[ <a href=\"".pnVarPrepForDisplay($url)."\">"._CHECK."</a> ]</td></tr>"
        ."\n<tr><td colspan=\"4\">"._DESCRIPTION.": <br /><textarea name=\"description\" cols=\"80\" rows=\"10\">".pnVarPrepForDisplay($description)."</textarea></td></tr>"
        ."\n<tr><td colspan=\"4\">"._AUTHORNAME.": <input type=\"text\" name=\"name\" size=\"20\" maxlength=\"100\" value=\"".pnVarPrepForDisplay($name)."\" />&nbsp;&nbsp;"
        .""._AUTHOREMAIL.": <input type=\"text\" name=\"email\" size=\"20\" maxlength=\"100\" value=\"".pnVarPrepForDisplay($email)."\" /></td></tr>"
        ."\n<tr><td colspan=\"4\">"._FILESIZE.": <input type=\"text\" name=\"filesize\" size=\"12\" maxlength=\"11\" value=\"".pnVarPrepForDisplay($filesize)."\" /></td></tr>"
        ."\n<tr><td colspan=\"4\">"._VERSION.": <input type=\"text\" name=\"version\" size=\"11\" maxlength=\"10\" value=\"".pnVarPrepForDisplay($version)."\" /></td></tr>"
        ."\n<tr><td colspan=\"4\">"._HOMEPAGE.": <input type=\"text\" name=\"homepage\" size=\"50\" maxlength=\"200\" value=\"http://".pnVarPrepForDisplay($homepage)."\" /> [ <a href=\"http://".pnVarPrepForDisplay($homepage)."\">"._VISIT."</a> ]</td></tr>"
        ."\n<tr><td colspan=\"4\">"._HITS.": <input type=\"text\" name=\"hits\" value=\"".pnVarPrepForDisplay($hits)."\" size=\"12\" maxlength=\"11\" /></td></tr>";
        $column = &$pntable['downloads_categories_column'];
        $result2 =& $dbconn->Execute("SELECT $column[cid],
                                          $column[title]
                                   FROM $pntable[downloads_categories]
                                   ORDER BY $column[title]");
        echo "<tr><td valign=\"top\"><input type=\"hidden\" name=\"lid\" value=\"".(int)$lid."\" />"
        .""._CATEGORY.": <select name=\"cat\">";

        while(list($ccid, $ctitle) = $result2->fields) {
            $sel = '';
            if ($cid==$ccid AND $sid==0) {
                $sel = 'selected="selected"';
            }
            echo "<option value=\"$ccid\" $sel>".pnVarPrepForDisplay($ctitle)."</option>";
            $column = &$pntable['downloads_subcategories_column'];
            $result3 =& $dbconn->Execute("SELECT $column[sid],
                                              $column[title]
                                       FROM $pntable[downloads_subcategories]
                                       WHERE $column[cid]='" . pnVarPrepForStore($ccid) . "'
                                       ORDER BY $column[title]");

            while(list($ssid, $stitle) = $result3->fields) {
                $sel = '';
                if ($sid==$ssid) {
                    $sel = 'selected="selected"';
                }
                echo "<option value=\"$ccid-$ssid\" $sel>".pnVarPrepForDisplay($ctitle)." / ".pnVarPrepForDisplay($stitle)."</option>";
                $result3->MoveNext();
            }
            $result2->MoveNext();
        }
        echo "</select></td></tr></table>"
        ."<input type=\"hidden\" name=\"module\" value=\"".$GLOBALS['module']."\" />"
        ."<input type=\"hidden\" name=\"op\" value=\"DownloadsModDownloadS\" />"
        .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
        ."\n<input type=\"submit\" value=\""._MODIFY."\" />&nbsp;</div></form>"
        ."\n<form style=\"display:inline\" action=\"admin.php\" method=\"post\"><div style=\"display:inline\">"
        ."<input type=\"hidden\" name=\"module\" value=\"".$GLOBALS['module']."\" />"
        ."\n<input type=\"hidden\" name=\"op\" value=\"DownloadsDelDownload\" />"
        ."\n<input type=\"hidden\" name=\"authid\" value=\"" . pnSecGenAuthKey() . "\" />"
        ."\n<input type=\"hidden\" name=\"lid\" value=\"$lid\" />"
        ."\n<input type=\"submit\" value=\""._DELETE."\" /></div></form>";
    CloseTable();

    // Modify or Add Editorial

        $column = &$pntable['downloads_editorials_column'];
        $resulted2 =& $dbconn->Execute("SELECT $column[adminid],
                                              $column[editorialtimestamp],
                                              $column[editorialtext],
                                              $column[editorialtitle]
                                       FROM $pntable[downloads_editorials]
                                       WHERE $column[downloadid]='" . (int)pnVarPrepForStore($lid)."'");
        OpenTable();
    // if returns 'bad query' status 0 (add editorial)
        if ($resulted2->EOF) {
        	$editorialtitle = ''; // init for E_ALL
        	$editorialtext = ''; // init for E_ALL
            echo '<h2>'._ADDEDITORIAL.'</h2>'
            .'<form action="admin.php" method="post"><div>'
            ."<input type=\"hidden\" name=\"downloadid\" value=\"$lid\" />"
            .""._EDITORIALTITLE.":<br /><input type=\"text\" name=\"editorialtitle\" value=\"$editorialtitle\" size=\"50\" maxlength=\"100\" /><br />"
            .""._EDITORIALTEXT.":<br /><textarea name=\"editorialtext\" cols=\"80\" rows=\"10\">$editorialtext</textarea><br />"
            ."<input type=\"hidden\" name=\"module\" value=\"".$GLOBALS['module']."\" />"
            ."<input type=\"hidden\" name=\"op\" value=\"DownloadsAddEditorial\" />"
            .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
            ."<input type=\"submit\" value=\"Add\" />";
        } else {
    // if returns 'cool' then status 1 (modify editorial)

              while(list($adminid, $editorialtimestamp, $editorialtext, $editorialtitle) = $resulted2->fields) {
/* Better to use ADODB to do this stuff
 * cocomp 2002/07/13
                ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $editorialtimestamp, $editorialtime);
                $timestamp = mktime($editorialtime[4],$editorialtime[5],$editorialtime[6],$editorialtime[2],$editorialtime[3],$editorialtime[1]);
                $formatted_date = date("F d, Y", $timestamp);
*/
		$formatted_date = date("F d, Y", $dbconn->UnixTimestamp($editorialtimestamp));
                echo "<h2>Modify Editorial</h2>"
                    .'<form action="admin.php" method="post"><div>'
                    .""._AUTHOR.": ".pnVarPrepForDisplay($adminid).'<br />'
                    .""._DATEWRITTEN.": $formatted_date<br />"
                    ."<input type=\"hidden\" name=\"downloadid\" value=\"$lid\" />"
                    .""._EDITORIALTITLE.":<br /><input type=\"text\" name=\"editorialtitle\" value=\"$editorialtitle\" size=\"80\" maxlength=\"100\" /><br />"
                    .""._EDITORIALTEXT.":<br /><textarea name=\"editorialtext\" cols=\"80\" rows=\"10\">$editorialtext</textarea><br />"
                    ."</select><input type=\"hidden\" name=\"module\" value=\"".$GLOBALS['module']."\" />"
                    ."<input type=\"hidden\" name=\"op\" value=\"DownloadsModEditorial\" />"
                    .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
                    ."<input type=\"submit\" value=\""._MODIFY."\" /> [ <a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=DownloadsDelEditorial&amp;downloadid=$lid&amp;authid=" . pnSecGenAuthKey() . "\">"._DELETE."</a> ]";
                $resulted2->MoveNext();
            }
        }
	echo "</div></form>";
    CloseTable();

    OpenTable();
    /* Show Comments */
    $column = &$pntable['downloads_votedata_column'];
// Depricated use of != '' for text columns use NOT LIKE '' instead for cross db
// compatibility - cocomp 2002/07/13
    $result5 =& $dbconn->Execute("SELECT $column[ratingdbid],
                                      $column[ratinguser],
                                      $column[ratingcomments],
                                      $column[ratingtimestamp]
                               FROM $pntable[downloads_votedata]
                               WHERE $column[ratinglid] = '" . (int)pnVarPrepForStore($lid) . "'
                               AND $column[ratingcomments] NOT LIKE ''
                               ORDER BY $column[ratingtimestamp] DESC");
    $totalcomments = $result5->PO_RecordCount();
    echo "<table width=\"100%\">";
    echo "<tr><td colspan=\"7\"><strong>Download Comments (total comments: ".pnVarPrepForDisplay($totalcomments).")</strong></td></tr>";
    echo "<tr><td style=\"width:20px\" colspan=\"1\"><strong>User  </strong></td><td colspan=\"5\"><strong>Comment  </strong></td><td><strong>"._DELETE."</strong></td></tr>";
    if ($totalcomments == 0) echo "<tr><td colspan=\"7\"><div style=\"text-align:center\"><span style=\"color:cccccc\">No Comments<br /></span></div></td></tr>";
    $x=0;
    $colorswitch="dddddd";

    while(list($ratingdbid, $ratinguser, $ratingcomments, $ratingtimestamp)=$result5->fields) {
/* Better to use ADODB to do this stuff
 * cocomp 2002/07/13
        ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $ratingtimestamp, $ratingtime);
        $timestamp = mktime($ratingtime[4],$ratingtime[5],$ratingtime[6],$ratingtime[2],$ratingtime[3],$ratingtime[1]);
        $formatted_date = date("F d, Y", $timestamp);  // time format should be customizable -- besfred
*/
	$formatted_date = ml_ftime(_DATEBRIEF, $dbconn->UnixTimestamp($ratingtimestamp));
        echo "<tr><td valign=\"top\" bgcolor=\"$colorswitch\">".pnVarPrepForDisplay($ratinguser)."</td><td valign=\"top\" colspan=\"5\" bgcolor=\"$colorswitch\">" . pnVarPrepHTMLDisplay($ratingcomments) . "</td><td style=\"background-color:$colorswitch\"><div style=\"text-align:center\"><strong><a href=admin.php?module=".$GLOBALS['module']."&amp;op=DownloadsDelComment&amp;lid=$lid&amp;rid=$ratingdbid&amp;authid=" . pnSecGenAuthKey() . ">X</a></strong></div></td></tr>";
        $x++;
        if ($colorswitch=="dddddd") $colorswitch="ffffff";
            else $colorswitch="dddddd";

        $result5->MoveNext();
    }

    // Show Registered Users Votes
    $column = &$pntable['downloads_votedata_column'];
    $result5 =& $dbconn->Execute("SELECT $column[ratingdbid],
                                      $column[ratinguser],
                                      $column[rating],
                                      $column[ratinghostname],
                                      $column[ratingtimestamp]
                               FROM $pntable[downloads_votedata]
                               WHERE $column[ratinglid] = '" .(int) pnVarPrepForStore($lid) . "'
                               AND $column[ratinguser] != 'outside'
                               AND $column[ratinguser] != '" . pnVarPrepForStore($anonymous) . "'
                               ORDER BY $column[ratingtimestamp] DESC");
    $totalvotes = $result5->PO_RecordCount();
    echo "<tr><td colspan=\"7\"><br /><strong>Registered User Votes (total votes: $totalvotes)</strong></td></tr>";
    echo "<tr><td><strong>User  </strong></td><td><strong>IP Address  </strong></td><td><strong>Rating  </strong></td><td><strong>User AVG Rating  </strong></td><td><strong>Total Ratings  </strong></td><td><strong>Date  </strong></td><td><strong>"._DELETE."</strong></td></tr>";
    if ($totalvotes == 0) echo "<tr><td colspan=\"7\"><div style=\"text-align:center\"><span style=\"color:cccccc\">No Registered User Votes<br /></span></div></td></tr>";
    $x=0;
    $colorswitch="dddddd";

    while(list($ratingdbid, $ratinguser, $rating, $ratinghostname, $ratingtimestamp)=$result5->fields) {
/* Better to use ADODB to do this stuff
 * cocomp 2002/07/13
        ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $ratingtimestamp, $ratingtime);
        $timestamp = mktime($ratingtime[4],$ratingtime[5],$ratingtime[6],$ratingtime[2],$ratingtime[3],$ratingtime[1]);
        $formatted_date = date("F d, Y", $timestamp);
*/
	$formatted_date = ml_ftime(_DATEBRIEF, $dbconn->UnixTimestamp($ratingtimestamp));
        //Individual user information
        $column = &$pntable['downloads_votedata_column'];
        $result2 =& $dbconn->Execute("SELECT $column[rating]
                                   FROM $pntable[downloads_votedata]
                                   WHERE $column[ratinguser] = '" . pnVarPrepForStore($ratinguser) . "'");
            $usertotalcomments = $result2->PO_RecordCount();
            $useravgrating = 0;

        while(list($rating2)=$result2->fields) {
	        $useravgrating = $useravgrating + $rating2;
            $result2->MoveNext();
        }
        $useravgrating = $useravgrating / $usertotalcomments;
        $useravgrating = number_format($useravgrating, 1);
        echo "<tr><td style=\"background-color:$colorswitch\">$ratinguser</td><td style=\"background-color:$colorswitch\">$ratinghostname</td><td style=\"background-color:$colorswitch\">$rating</td><td style=\"background-color:$colorswitch\">$useravgrating</td><td style=\"background-color:$colorswitch\">$usertotalcomments</td><td style=\"background-color:$colorswitch\">$formatted_date  </span></strong></td><td style=\"background-color:$colorswitch\"><div style=\"text-align:center\"><strong><a href=admin.php?module=".$GLOBALS['module']."&amp;op=DownloadsDelVote&amp;lid=$lid&amp;rid=$ratingdbid&amp;authid=" . pnSecGenAuthKey() . ">X</a></strong></div></td></tr><br />";
        $x++;
        if ($colorswitch=="dddddd") $colorswitch="ffffff";
            else $colorswitch="dddddd";

        $result5->MoveNext();
    }

    // Show Unregistered Users Votes
    $column = &$pntable['downloads_votedata_column'];
    $result5 =& $dbconn->Execute("SELECT $column[ratingdbid],
                                      $column[rating],
                                      $column[ratinghostname],
                                      $column[ratingtimestamp]
                               FROM $pntable[downloads_votedata]
                               WHERE $column[ratinglid] = '" . (int)pnVarPrepForStore($lid) . "'
                               AND $column[ratinguser] = '" . pnVarPrepForStore($anonymous) . "'
                               ORDER BY $column[ratingtimestamp] DESC");
    $totalvotes = $result5->PO_RecordCount();
    echo "<tr><td colspan=\"7\"><strong><br />Unregistered User Votes (total votes: ".pnVarPrepForDisplay($totalvotes).")</strong></td></tr>";
    echo "<tr><td colspan=\"2\"><strong>IP Address  </strong></td><td colspan=\"3\"><strong>Rating  </strong></td><td><strong>Date  </strong></td><td><strong>"._DELETE."</strong></td></tr>";
    if ($totalvotes == 0) echo "<tr><td colspan=\"7\"><div style=\"text-align:center\"><span style=\"color:cccccc\">No Unregistered User Votes<br /></span></div></td></tr>";
    $x=0;
    $colorswitch="dddddd";

    while(list($ratingdbid, $rating, $ratinghostname, $ratingtimestamp)=$result5->fields) {
/* Better to use ADODB to do this stuff
 * cocomp 2002/07/13
        ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $ratingtimestamp, $ratingtime);
        $timestamp = mktime($ratingtime[4],$ratingtime[5],$ratingtime[6],$ratingtime[2],$ratingtime[3],$ratingtime[1]);
        $formatted_date = date("F d, Y", $timestamp);
*/
	$formatted_date = ml_ftime(_DATEBRIEF, $dbconn->UnixTimestamp($ratingtimestamp));
        echo "<td colspan=\"2\" bgcolor=\"$colorswitch\">".pnVarPrepForDisplay($ratinghostname)."</td><td colspan=\"3\" bgcolor=\"$colorswitch\">$rating</td><td style=\"background-color:$colorswitch\">".pnVarPrepForDisplay($formatted_date)."  </span></strong></td><td style=\"background-color:$colorswitch\"><div style=\"text-align:center\"><strong><a href=admin.php?module=".$GLOBALS['module']."&amp;op=DownloadsDelVote&amp;lid=$lid&amp;rid=$ratingdbid&amp;authid=" . pnSecGenAuthKey() . ">X</a></strong></div></td></tr><br />";
        $x++;
        if ($colorswitch=="dddddd") $colorswitch="ffffff";
            else $colorswitch="dddddd";

        $result5->MoveNext();
     }

    // Show Outside Users Votes
    $column = &$pntable['downloads_votedata_column'];
    $result5 =& $dbconn->Execute("SELECT $column[ratingdbid],
                                      $column[rating],
                                      $column[ratinghostname],
                                      $column[ratingtimestamp]
                               FROM $pntable[downloads_votedata]
                               WHERE $column[ratinglid] = '" . (int)pnVarPrepForStore($lid) . "'
                               AND $column[ratinguser] = 'outside'
                               ORDER BY $column[ratingtimestamp] DESC");
    $totalvotes = $result5->PO_RecordCount();
    echo "<tr><td colspan=\"7\"><strong><br />Outside User Votes (total votes: ".pnVarPrepForDisplay($totalvotes).")</strong></td></tr>";
    echo "<tr><td colspan=\"2\"><strong>IP Address  </strong></td><td colspan=\"3\"><strong>Rating  </strong></td><td><strong>Date  </strong></td><td><strong>"._DELETE."</strong></td></tr>";
    if ($totalvotes == 0) {
    $sitename = pnConfigGetVar('sitename');
    echo "<tr><td colspan=\"7\"><div style=\"text-align:center\"><span style=\"color:cccccc\">No Votes from Outside $sitename<br /></span></div></td></tr>";
    }
    $x=0;
    $colorswitch="dddddd";

    while(list($ratingdbid, $rating, $ratinghostname, $ratingtimestamp)=$result5->fields) {
/* Better to use ADODB to do this stuff
 * cocomp 2002/07/13
        ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $ratingtimestamp, $ratingtime);
        $timestamp = mktime($ratingtime[4],$ratingtime[5],$ratingtime[6],$ratingtime[2],$ratingtime[3],$ratingtime[1]);
        $formatted_date = date("F d, Y", $timestamp);
*/
	$formatted_date = ml_ftime(_DATEBRIEF, $dbconn->UnixTimestamp($ratingtimestamp));
        echo "<tr><td colspan=\"2\" bgcolor=\"$colorswitch\">".pnVarPrepForDisplay($ratinghostname)."</td><td colspan=\"3\" bgcolor=\"$colorswitch\">".pnVarPrepForDisplay($rating)."</td><td style=\"background-color:$colorswitch\">".pnVarPrepForDisplay($formatted_date)."  </span></strong></td><td style=\"background-color:$colorswitch\"><div style=\"text-align:center\"><strong><a href=admin.php?module=".$GLOBALS['module']."&amp;op=DownloadsDelVote&amp;lid=$lid&amp;rid=$ratingdbid&amp;authid=" . pnSecGenAuthKey() . ">X</a></strong></div></td></tr><br />";
        $x++;
        if ($colorswitch=="dddddd") {
	    $colorswitch="ffffff";
	} else {
	    $colorswitch="dddddd";
	}
        $result5->MoveNext();
    }

    echo "<tr><td colspan=\"6\"></td></tr>";
    echo "</table>";

    }

    CloseTable();

    include 'footer.php';
}

function DownloadsDelComment()
{

    list($lid,
         $rid) = pnVarCleanFromInput('lid',
                                     'rid');

    if (!isset($lid) || !is_numeric($lid) ||
        !isset($rid) || !is_numeric($rid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column = &$pntable['downloads_votedata_column'];
    $dbconn->Execute("UPDATE $pntable[downloads_votedata]
                    SET $column[ratingcomments]=''
                    WHERE $column[ratingdbid] = '" . (int)pnVarPrepForStore($rid)."'");
// cocomp 2002/07/13 changed the table so must also change the column!
	$column = &$pntable['downloads_downloads_column'];
    $dbconn->Execute("UPDATE $pntable[downloads_downloads]
                    SET $column[totalcomments] = ($column[totalcomments] - 1)
                    WHERE $column[lid] = '" . (int)pnVarPrepForStore($lid)."'");

    pnRedirect('admin.php?module='.$GLOBALS['module'].'&op=DownloadsModDownload&lid='.$lid);

}

function DownloadsDelVote()
{
    list($lid,
         $rid) = pnVarCleanFromInput('lid',
                                     'rid');

    if	(!isset($lid) || !is_numeric($lid) ||
         !isset($rid) || !is_numeric($rid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column = &$pntable['downloads_votedata_column'];
    $dbconn->Execute("DELETE FROM $pntable[downloads_votedata]
                    WHERE $column[ratingdbid]='".pnVarPrepForStore($rid)."'");
    $voteresult =& $dbconn->Execute("SELECT $column[rating],
                                           $column[ratinguser],
                                           $column[ratingcomments]
                                    FROM $pntable[downloads_votedata]
                                    WHERE $column[ratinglid] = '" . (int)pnVarPrepForStore($lid)."'");
    $totalvotesDB = $voteresult->PO_RecordCount();
    $finalrating = calculateVote($voteresult, $totalvotesDB);
    $column = &$pntable['downloads_downloads_column'];
    $dbconn->Execute("UPDATE $pntable[downloads_downloads]
                      SET $column[downloadratingsummary]=" . pnVarPrepForStore($finalrating) . ",
                          $column[totalvotes]=" . pnVarPrepForStore($totalvotesDB) . ",
                          $column[totalcomments]=" . pnVarPrepForStore($totalvotesDB) . "
                      WHERE $column[lid] = '" . (int)pnVarPrepForStore($lid)."'");

    pnRedirect('admin.php?module='.$GLOBALS['module'].'&op=DownloadsModDownload&lid='.$lid);
}

function DownloadsListBrokenDownloads()
{

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    include ('header.php');
    GraphicAdmin();
    OpenTable();
    echo '<h2>'._WEBDOWNLOADSADMIN.'</h2>';
    CloseTable();

    OpenTable();
    $column = &$pntable['downloads_modrequest_column'];
    $result =& $dbconn->Execute("SELECT $column[requestid],
                                       $column[lid],
                                       $column[modifysubmitter]
                                FROM $pntable[downloads_modrequest]
                                WHERE $column[brokendownload]=1
                                ORDER BY $column[requestid]");
    $totalbrokendownloads = $result->PO_RecordCount();
    echo '<h2>'._DUSERREPBROKEN." (".pnVarPrepForDisplay($totalbrokendownloads).")</h2><div style=\"text-align:center\">"
    .""._DIGNOREINFO.'<br />'
    .""._DDELETEINFO.'</div><br />';
    if ($totalbrokendownloads==0) {
        echo '<h2>'._DNOREPORTEDBROKEN.'</h2>';
    } else {
        $colorswitch = $GLOBALS['bgcolor2'];
        echo "<table width=\"100%\">"
            ."<tr>"
            ."<td><strong>"._DOWNLOAD."</strong></td>"
            ."<td><strong>"._SUBMITTER."</strong></td>"
            ."<td><strong>"._DOWNLOADOWNER."</strong></td>"
            ."<td><strong>"._IGNORE."</strong></td>"
            ."<td><strong>"._DELETE."</strong></td>"
            ."<td><strong>"._EDIT."</strong></td>"
            ."</tr>";
        while(list($requestid, $lid, $modifysubmitter)=$result->fields) {

            $result->MoveNext();
            $column = &$pntable['downloads_downloads_column'];
            $result2 =& $dbconn->Execute("SELECT $column[title],
                                                $column[url],
                                                $column[submitter]
                                         FROM $pntable[downloads_downloads]
                                         WHERE $column[lid]='" . pnVarPrepForStore($lid)."'");
            if ($modifysubmitter != '$anonymous') {
                $column = &$pntable['users_column'];
                $result3 =& $dbconn->Execute("SELECT $column[email]
                                             FROM $pntable[users]
                                             WHERE $column[uname]='" . pnVarPrepForStore($modifysubmitter) . "'");

                list($email)=$result3->fields;
            }

            list($title, $url, $owner)=$result2->fields;
            $column = &$pntable['users_column'];
            $result4 =& $dbconn->Execute("SELECT $column[email]
                                         FROM $pntable[users]
                                         WHERE $column[uname]='" . pnVarPrepForStore($owner) . "'");

            list($owneremail)=$result4->fields;
            echo "<tr>"
            ."<td style=\"background-color:$colorswitch\"><a href=\"$url\">".pnVarPrepForDisplay($title)."</a>"
            ."</td>";
            if ($email=='') {
                echo "<td style=\"background-color:$colorswitch\">".pnVarPrepForDisplay($modifysubmitter)."";
            } else {
                echo "<td style=\"background-color:$colorswitch\"><a href=\"mailto:$email\">".pnVarPrepForDisplay($modifysubmitter)."</a>";
            }
            echo "</td>";
            if ($owneremail=='') {
                echo "<td style=\"background-color:$colorswitch\">".pnVarPrepForDisplay($owner)."";
            } else {
                 echo "<td style=\"background-color:$colorswitch\"><a href=\"mailto:$owneremail\">".pnVarPrepForDisplay($owner)."</a>";
            }
            echo "</td>"
            ."<td style=\"background-color:$colorswitch\"><div style=\"text-align:center\"><a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=DownloadsIgnoreBrokenDownloads&amp;lid=$lid&amp;authid=" . pnSecGenAuthKey() . "\">X</a></div>"
            ."</td>"
            ."<td style=\"background-color:$colorswitch\"><div style=\"text-align:center\"><a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=DownloadsDelBrokenDownloads&amp;lid=$lid&amp;authid=" . pnSecGenAuthKey() . "\">X</a></div>"
            ."</td>"
            ."<td style=\"background-color:$colorswitch\"><div style=\"text-align:center\"><a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=DownloadsModDownload&amp;lid=$lid&amp;authid=" . pnSecGenAuthKey() . "\">X</a></div>"
            ."</td>"
            ."</tr>";
            if ($colorswitch == $GLOBALS['bgcolor2']) {
                $colorswitch = $GLOBALS['bgcolor1'];
            } else {
                $colorswitch = $GLOBALS['bgcolor2'];
            }
        }
        echo "</table>";
    }
    CloseTable();
    include 'footer.php';
}

function DownloadsDelBrokenDownloads() {

    $lid = pnVarCleanFromInput('lid');

    if	(!isset($lid) || !is_numeric($lid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $dbconn->Execute("DELETE FROM $pntable[downloads_modrequest]
                    WHERE {$pntable['downloads_modrequest_column']['lid']}='".(int)pnVarPrepForStore($lid)."'");
    $dbconn->Execute("DELETE FROM $pntable[downloads_downloads]
                    WHERE {$pntable['downloads_downloads_column']['lid']}='".(int)pnVarPrepForStore($lid)."'");

    pnRedirect('admin.php?module='.$GLOBALS['module'].'&op=DownloadsListBrokenDownloads');
}

function DownloadsIgnoreBrokenDownloads() {

    $lid = pnVarCleanFromInput('lid');

    if	(!isset($lid) || !is_numeric($lid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $dbconn->Execute("DELETE FROM $pntable[downloads_modrequest]
                    WHERE {$pntable['downloads_modrequest_column']['lid']}='".(int)pnVarPrepForStore($lid)."'
                      AND {$pntable['downloads_modrequest_column']['brokendownload']}=1");

    pnRedirect('admin.php?module='.$GLOBALS['module'].'&op=DownloadsListBrokenDownloads');
}

function DownloadsListModRequests() {
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    include ('header.php');
    GraphicAdmin();
    OpenTable();
    echo '<h2>'._WEBDOWNLOADSADMIN.'</h2>';
    CloseTable();

    OpenTable();
    $column = &$pntable['downloads_modrequest_column'];
    $result =& $dbconn->Execute("SELECT $column[requestid],
                                       $column[lid],
                                       $column[cid],
                                       $column[sid],
                                       $column[title],
                                       $column[url],
                                       $column[description],
                                       $column[modifysubmitter],
                                       $column[name],
                                       $column[email],
                                       $column[filesize],
                                       $column[version],
                                       $column[homepage]
                                FROM $pntable[downloads_modrequest]
                                WHERE $column[brokendownload]=0
                                ORDER BY $column[requestid]");
    $totalmodrequests = $result->PO_RecordCount();
    echo '<h2>'._DUSERMODREQUEST." (".pnVarPrepForDisplay($totalmodrequests).")</h2><br />";
    echo "<table width=\"95%\"><tr><td>";
    while(list($requestid, $lid, $cid, $sid, $title, $url, $description, $modifysubmitter, $name, $email, $filesize, $version, $homepage)=$result->fields) {

        $result->MoveNext();
        /*
         * Hootbah: XXX FIXME XXX
         * There is somthing odd here. Why not just use one or two queries with
         * some joins?
         */
        $column = &$pntable['downloads_downloads_column'];
        $result2 =& $dbconn->Execute("SELECT $column[cid],
                                            $column[sid],
                                            $column[title],
                                            $column[url],
                                            $column[description],
                                            $column[name],
                                            $column[email],
                                            $column[submitter],
                                            $column[filesize],
                                            $column[version],
                                            $column[homepage]
                                      FROM $pntable[downloads_downloads]
                                      WHERE $column[lid]='" . pnVarPrepForStore($lid)."'");

        list($origcid, $origsid, $origtitle, $origurl, $origdescription, $origname, $origemail, $owner, $origfilesize, $origversion, $orighomepage)=$result2->fields;
        $column = &$pntable['downloads_categories_column'];
        $result3 =& $dbconn->Execute("select $column[title] from $pntable[downloads_categories] where $column[cid]='".pnVarPrepForStore($cid)."'");
        $column = &$pntable['downloads_subcategories_column'];
        $result4 =& $dbconn->Execute("select $column[title] from $pntable[downloads_subcategories] where $column[cid]='".pnVarPrepForStore($cid)."' and $column[sid]='".pnVarPrepForStore($sid)."'");
        $column = &$pntable['downloads_categories_column'];
        $result5 =& $dbconn->Execute("select $column[title] from $pntable[downloads_categories] where $column[cid]='".pnVarPrepForStore($origcid)."'");
        $column = &$pntable['downloads_subcategories_column'];
        $result6 =& $dbconn->Execute("select $column[title] from $pntable[downloads_subcategories] where $column[cid]='".pnVarPrepForStore($origcid)."' and $column[sid]='".pnVarPrepForStore($origsid)."'");
        $column = &$pntable['users_column'];
        $result7 =& $dbconn->Execute("select $column[email] from $pntable[users] where $column[uname]='".pnVarPrepForStore($modifysubmitter)."'");
        $column = &$pntable['users_column'];
        $result8 =& $dbconn->Execute("select $column[email] from $pntable[users] where $column[uname]='".pnVarPrepForStore($owner)."'");

        list($cidtitle)=$result3->fields;
        list($sidtitle)=$result4->fields;
        list($origcidtitle)=$result5->fields;
        list($origsidtitle)=$result6->fields;
        list($modifysubmitteremail)=$result7->fields;
        list($owneremail)=$result8->fields;

        if ($owner=="") {
            $owner="administration";
        }
        if ($origsidtitle=="") {
            $origsidtitle= "-----";
        }
        if ($sidtitle=="") {
            $sidtitle= "-----";
        }
        echo "<table border=\"1\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\">"
            ."<tr>"
            ."<td>"
            ."<table width=\"100%\" style=\"background-color:".$GLOBALS['bgcolor2']."\">"
            ."<tr>"
            ."<td valign=\"top\" style=\"width:45%\"><strong>"._ORIGINAL."</strong></td>"
            ."<td rowspan=\"10\" valign=\"top\" align=\"left\"><span class=\"pn-sub\"><br />"._DESCRIPTION.":<br />".pnVarPrepForDisplay($origdescription)."</span></td>"
            ."</tr>"
            ."<tr><td valign=\"top\" style=\"width:45%\"><span class=\"pn-sub\">"._TITLE.": ".pnVarPrepForDisplay($origtitle)."</span></td></tr>"
            ."<tr><td valign=\"top\" style=\"width:45%\"><span class=\"pn-sub\">"._URL.": <a href=\"$origurl\">".pnVarPrepForDisplay($origurl)."</a></span></td></tr>"
            ."<tr><td valign=\"top\" style=\"width:45%\"><span class=\"pn-sub\">"._CATEGORY.": ".pnVarPrepForDisplay($origcidtitle)."</span></td></tr>"
            ."<tr><td valign=\"top\" style=\"width:45%\"><span class=\"pn-sub\">"._SUBCATEGORY.": ".pnVarPrepForDisplay($origsidtitle)."</span></td></tr>"
            ."<tr><td valign=\"top\" style=\"width:45%\"><span class=\"pn-sub\">"._AUTHORNAME.": ".pnVarPrepForDisplay($origname)."</span></td></tr>"
            ."<tr><td valign=\"top\" style=\"width:45%\"><span class=\"pn-sub\">"._AUTHOREMAIL.": ".pnVarPrepForDisplay($origemail)."</span></td></tr>"
            ."<tr><td valign=\"top\" style=\"width:45%\"><span class=\"pn-sub\">"._FILESIZE.": ".pnVarPrepForDisplay($origfilesize)."</span></td></tr>"
            ."<tr><td valign=\"top\" style=\"width:45%\"><span class=\"pn-sub\">"._VERSION.": ".pnVarPrepForDisplay($origversion)."</span></td></tr>"
            ."<tr><td valign=\"top\" style=\"width:45%\"><span class=\"pn-sub\">"._HOMEPAGE.": <a href=\"".pnVarPrepForDisplay($orighomepage)."\">".pnVarPrepForDisplay($orighomepage)."</a></span></td></tr>"
            ."</table>"
            ."</td>"
            ."</tr>"
            ."<tr>"
            ."<td>"
            ."<table width=\"100%\">"
            ."<tr>"
            ."<td valign=\"top\" style=\"width:45%\"><strong>"._PROPOSED."</strong></td>"
            ."<td rowspan=\"10\" valign=\"top\" align=\"left\"><span class=\"pn-sub\"><br />"._DESCRIPTION.":<br />".pnVarPrepForDisplay($description)."</span></td>"
            ."</tr>"
            ."<tr><td valign=\"top\" style=\"width:45%\"><span class=\"pn-sub\">"._TITLE.": ".pnVarPrepForDisplay($title)."</span></td></tr>"
            ."<tr><td valign=\"top\" style=\"width:45%\"><span class=\"pn-sub\">"._URL.": <a href=\"$url\">".pnVarPrepForDisplay($url)."</a></span></td></tr>"
            ."<tr><td valign=\"top\" style=\"width:45%\"><span class=\"pn-sub\">"._CATEGORY.": ".pnVarPrepForDisplay($cidtitle)."</span></td></tr>"
            ."<tr><td valign=\"top\" style=\"width:45%\"><span class=\"pn-sub\">"._SUBCATEGORY.": ".pnVarPrepForDisplay($sidtitle)."</span></td></tr>"
            ."<tr><td valign=\"top\" style=\"width:45%\"><span class=\"pn-sub\">"._AUTHORNAME.": ".pnVarPrepForDisplay($name)."</span></td></tr>"
            ."<tr><td valign=\"top\" style=\"width:45%\"><span class=\"pn-sub\">"._AUTHOREMAIL.": ".pnVarPrepForDisplay($email)."</span></td></tr>"
            ."<tr><td valign=\"top\" style=\"width:45%\"><span class=\"pn-sub\">"._FILESIZE.": ".pnVarPrepForDisplay($filesize)."</span></td></tr>"
            ."<tr><td valign=\"top\" style=\"width:45%\"><span class=\"pn-sub\">"._VERSION.": ".pnVarPrepForDisplay($version)."</span></td></tr>"
            ."<tr><td valign=\"top\" style=\"width:45%\"><span class=\"pn-sub\">"._HOMEPAGE.": <a href=\"".pnVarPrepForDisplay($homepage)."\">".pnVarPrepForDisplay($homepage)."</a></span></td></tr>"
            ."</table>"
            ."</td>"
            ."</tr>"
            ."</table>"
            ."<table width=\"100%\">"
            ."<tr>";
        if ($modifysubmitteremail=="") {
            echo "<td align=\"left\"><span class=\"pn-sub\">"._SUBMITTER.":  ".pnVarPrepForDisplay($modifysubmitter)."</span></td>";
        } else {
        echo "<td align=\"left\"><span class=\"pn-sub\">"._SUBMITTER.":  <a href=\"mailto:$modifysubmitteremail\">".pnVarPrepForDisplay($modifysubmitter)."</a></span></td>";
        }
        if ($owneremail=="") {
            echo "<td align=\"center\"><span class=\"pn-sub\">"._OWNER.":  ".pnVarPrepForDisplay($owner)."</span></td>";
        } else {
        echo "<td align=\"center\"><span class=\"pn-sub\">"._OWNER.": <a href=\"mailto:$owneremail\">".pnVarPrepForDisplay($owner)."</a></span></td>";
        }
        echo "<td align=\"right\"><span class=\"pn-sub\">( <a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=DownloadsChangeModRequests&amp;requestid=$requestid&amp;authid=" . pnSecGenAuthKey() . "\">"._ACCEPT."</a> / <a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=DownloadsChangeIgnoreRequests&amp;requestid=$requestid&amp;authid=" . pnSecGenAuthKey() . "\">"._IGNORE."</a> )</span></td></tr></table><br />";
    }
    if ($totalmodrequests == 0) {
        echo "<div style=\"text-align:center\">"._NOMODREQUESTS.'<br />'
        .""._GOBACK.'</div>';
    }
    echo "</td></tr></table>";
    CloseTable();
    include ('footer.php');
}

function DownloadsChangeModRequests() {

    $requestid = pnVarCleanFromInput('requestid');

    if	(!isset($requestid) || !is_numeric($requestid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column = &$pntable['downloads_modrequest_column'];
    $result =& $dbconn->Execute("SELECT $column[requestid],
                                       $column[lid],
                                       $column[cid],
                                       $column[sid],
                                       $column[title],
                                       $column[url],
                                       $column[description],
                                       $column[name],
                                       $column[email],
                                       $column[filesize],
                                       $column[version],
                                       $column[homepage]
                                FROM $pntable[downloads_modrequest]
                                WHERE $column[requestid]='" . (int)pnVarPrepForStore($requestid)."'");

    while(list($requestid, $lid, $cid, $sid, $title, $url, $description, $name, $email, $filesize, $version, $homepage)=$result->fields) {
        $column = &$pntable['downloads_downloads_column'];
        $dbconn->Execute("UPDATE $pntable[downloads_downloads]
                          SET $column[cid]=" . pnVarPrepForStore($cid) . ",
                              $column[sid]=" . pnVarPrepForStore($sid) . ",
                              $column[title]='" . pnVarPrepForStore($title) . "',
                              $column[url]='" . pnVarPrepForStore($url) . "',
                              $column[description]='" . pnVarPrepForStore($description) . "',
                              $column[name]='" . pnVarPrepForStore($name) . "',
                              $column[email]='" . pnVarPrepForStore($email) . "',
                              $column[filesize]='" . pnVarPrepForStore($filesize) . "',
                              $column[version]='" . pnVarPrepForStore($version) . "',
                              $column[homepage]='" . pnVarPrepForStore($homepage) . "'
                        WHERE $column[lid] = '" . (int)pnVarPrepForStore($lid)."'");

		$changerow = &$pntable['downloads_modrequest_column']['requestid'];
        $dbconn->Execute("DELETE FROM $pntable[downloads_modrequest]
						WHERE $changerow = '".pnVarPrepForStore($requestid)."'");
        $result->MoveNext();
    }

    pnRedirect('admin.php?module='.$GLOBALS['module'].'&op=DownloadsListModRequests');
}

function DownloadsChangeIgnoreRequests() {

    $requestid = pnVarCleanFromInput('requestid');

    if	(!isset($requestid) || !is_numeric($requestid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();


    $ignorerow = &$pntable['downloads_modrequest_column']['requestid'];
    $dbconn->Execute("DELETE FROM $pntable[downloads_modrequest]
					 WHERE $ignorerow = '".pnVarPrepForStore($requestid)."'");

    pnRedirect('admin.php?module='.$GLOBALS['module'].'&op=DownloadsListModRequests');
}

function DownloadsCleanVotes() {
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    $totalvoteresult =& $dbconn->Execute("SELECT DISTINCT {$pntable['downloads_votedata_column']['ratinglid']}
                                         FROM $pntable[downloads_votedata]");

    while(list($lid)=$totalvoteresult->fields) {
        $column = &$pntable['downloads_votedata_column'];
        $voteresult =& $dbconn->Execute("SELECT $column[rating],
                                               $column[ratinguser],
                                               $column[ratingcomments]
                                        FROM $pntable[downloads_votedata]
                                        WHERE $column[ratinglid] = '" . pnVarPrepForStore($lid)."'");
        $totalvotesDB = $voteresult->PO_RecordCount();
        $finalrating = calculateVote($voteresult, $totalvotesDB);
        $column = &$pntable['downloads_downloads_column'];
        $dbconn->Execute("UPDATE $pntable[downloads_downloads]
                          SET $column[downloadratingsummary]=" . pnVarPrepForStore($finalrating) . ",
                              $column[totalvotes]=" . pnVarPrepForStore($totalvotesDB) . ",
                              $column[totalcomments]=" . pnVarPrepForStore($totalvotesDB) . "
                          WHERE $column[lid] = '" . pnVarPrepForStore($lid)."'");
        $totalvoteresult->MoveNext();
    }

    pnRedirect('admin.php?module='.$GLOBALS['module'].'&op=downloads');
}

function DownloadsModDownloadS()
{
    list($lid,
         $title,
         $url,
         $description,
         $name,
         $email,
         $hits,
         $cat,
         $filesize,
         $version,
         $homepage) = pnVarCleanFromInput('lid',
                                          'title',
                                          'url',
                                          'description',
                                          'name',
                                          'email',
                                          'hits',
                                          'cat',
                                          'filesize',
                                          'version',
                                          'homepage');

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    if	(!isset($lid) || !is_numeric($lid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $cat = explode("-", $cat);
	if (empty($cat[0]) || !is_numeric($cat[0])) $cat[0] = 0;
	if (empty($cat[1]) || !is_numeric($cat[1])) $cat[1] = 0;
    $column = &$pntable['downloads_downloads_column'];
    $dbconn->Execute("UPDATE $pntable[downloads_downloads]
                      SET $column[cid]=" . (int)pnVarPrepForStore($cat[0]) . ",
                          $column[sid]=" . (int)pnVarPrepForStore($cat[1]) . ",
                          $column[title]='" . pnVarPrepForStore($title) . "',
                          $column[url]='" . pnVarPrepForStore($url) . "',
                          $column[description]='" . pnVarPrepForStore($description) . "',
                          $column[name]='" . pnVarPrepForStore($name) . "',
                          $column[email]='" . pnVarPrepForStore($email) . "',
                          $column[hits]='" . pnVarPrepForStore($hits) . "',
                          $column[filesize]='" . pnVarPrepForStore($filesize) . "',
                          $column[version]='" . pnVarPrepForStore($version) . "',
                          $column[homepage]='" . pnVarPrepForStore($homepage) . "'
                      WHERE $column[lid]='" . (int)pnVarPrepForStore($lid)."'");

    pnRedirect('admin.php?module='.$GLOBALS['module'].'&op=downloads');
}

function DownloadsDelDownload() {

    $lid = pnVarCleanFromInput('lid');

    if	(!isset($lid) || !is_numeric($lid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $dbconn->Execute("DELETE FROM $pntable[downloads_downloads]
                    WHERE {$pntable[downloads_downloads_column][lid]}='".(int)pnVarPrepForStore($lid)."'");

    // Let any hooks know that we have deleted an item
    pnModCallHooks('item', 'delete', $lid, '');

    pnRedirect('admin.php?module='.$GLOBALS['module'].'&op=downloads');
}

function DownloadsDelNew() {
    $lid = pnVarCleanFromInput('lid');

    if	(!isset($lid) || !is_numeric($lid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $dbconn->Execute("DELETE FROM $pntable[downloads_newdownload]
                    WHERE {$pntable['downloads_newdownload_column']['lid']}='".(int)pnVarPrepForStore($lid)."'");

    pnRedirect('admin.php?module='.$GLOBALS['module'].'&op=downloads');
}

function DownloadsAddEditorial()
{
    list($aid,
         $downloadid,
         $editorialtitle,
         $editorialtext) = pnVarCleanFromInput('aid',
                                               'downloadid',
                                               'editorialtitle',
                                               'editorialtext');

    if	(!isset($downloadid) || !is_numeric($downloadid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $column = &$pntable['downloads_editorials_column'];
// cocomp 2002/07/13 altered adminid to user pnUserGetVar('uid') as $aid was not
// supplied anyway.
// Also changed now() to $dbconn->DBTimestamp(time()) for cross db compatability
    $dbconn->Execute("INSERT INTO $pntable[downloads_editorials]
                        ($column[downloadid],
                         $column[adminid],
                         $column[editorialtimestamp],
                         $column[editorialtext],
                         $column[editorialtitle])
                      VALUES
                        (" . (int)pnVarPrepForStore($downloadid). ",
                         '" . pnVarPrepForStore(pnUserGetVar('uid')) . "',
						  " . $dbconn->DBTimestamp(time()) . ",
                         '" . pnVarPrepForStore($editorialtext) . "',
                         '" . pnVarPrepForStore($editorialtitle) . "')");
    include('header.php');
    GraphicAdmin();
    OpenTable();
    echo "<div style=\"text-align:center\"><br />"
    .""._EDITORIALADDED.'<br />'
    ."[ <a href=\"admin.php?module=".$GLOBALS['module']."&op=downloads\">"._WEBDOWNLOADSADMIN."</a> ]<br />";
    echo "<table border=\"0\"><tr><td align=\"left\">";
    echo '<strong>'._DOWNLOADID.":</strong> ".pnVarPrepForDisplay($downloadid).'<br />'
    .'<strong>'._EDITORIALTITLE.":</strong><br />".pnVarPrepForDisplay($editorialtitle).'<br />'
    .'<strong>'._EDITORIALTEXT.":</strong><br />".pnVarPrepHTMLDisplay($editorialtext).'<br />';
    echo "</td></tr></table>";
    CloseTable();
    include('footer.php');
}

function DownloadsModEditorial() {

    list($downloadid,
         $editorialtitle,
         $editorialtext) = pnVarCleanFromInput('downloadid',
                                               'editorialtitle',
                                               'editorialtext');

    if	(!isset($downloadid) || !is_numeric($downloadid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column = &$pntable['downloads_editorials_column'];
    $dbconn->Execute("UPDATE $pntable[downloads_editorials]
                      SET $column[editorialtext]='" . pnVarPrepForStore($editorialtext) . "',
                          $column[editorialtitle]='" . pnVarPrepForStore($editorialtitle) . "'
                      WHERE $column[downloadid]='" . (int)pnVarPrepForStore($downloadid)."'");
    include('header.php');
    GraphicAdmin();
    OpenTable();
    echo '<br /><div style="text-align:center">'
    ._EDITORIALMODIFIED.'<br />'
    ."[ <a href=\"admin.php?module=".$GLOBALS['module']."&op=downloads\">"._WEBDOWNLOADSADMIN."</a> ]<br />";
    CloseTable();
    include('footer.php');
}

function DownloadsDelEditorial()
{
    $downloadid = pnVarCleanFromInput('downloadid');

    if	(!isset($downloadid) || !is_numeric($downloadid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $dbconn->Execute("DELETE FROM $pntable[downloads_editorials]
                    WHERE {$pntable[downloads_editorials_column][downloadid]}='".(int)pnVarPrepForStore($downloadid)."'");
    include('header.php');
    GraphicAdmin();
    OpenTable();
    echo '<br /><div style="text-align:center">'
    ._EDITORIALREMOVED.'<br />'
    ."[ <a href=\"admin.php?module=".$GLOBALS['module']."&op=downloads\">"._WEBDOWNLOADSADMIN."</a> ]<br />";
    CloseTable();
    include('footer.php');
}

function DownloadsDownloadCheck() {
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    include ('header.php');
    GraphicAdmin();
    OpenTable();
    echo '<h2>'._WEBDOWNLOADSADMIN.'</h2>';
    CloseTable();

    OpenTable();
    echo '<h2>'._DOWNLOADVALIDATION.'</h2>'
        ."<table width=\"100%\"><tr><td colspan=\"2\" align=\"center\">"
        ."<a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=DownloadsValidate&amp;cid=0&amp;sid=0\">"._CHECKALLDOWNLOADS."</a></td></tr>"
        ."<tr><td valign=\"top\"><div style=\"text-align:center\"><strong>"._CHECKCATEGORIES.'</strong><br />'._INCLUDESUBCATEGORIES."<br /><span class=\"pn-sub\">";
    $column = &$pntable['downloads_categories_column'];
    $result =& $dbconn->Execute("SELECT $column[cid],
                                       $column[title]
                                FROM $pntable[downloads_categories]
                                ORDER BY $column[title]");

    while(list($cid, $title) = $result->fields) {
        $transfertitle = str_replace (" ", "_", $title);
        echo "<a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=DownloadsValidate&amp;cid=$cid&amp;sid=0&amp;ttitle=$transfertitle\">".pnVarPrepForDisplay($title)."</a><br />";
        $result->MoveNext();
    }
    echo "</span></div></td>";
    echo "<td valign=\"top\"><div style=\"text-align:center\"><strong>"._CHECKSUBCATEGORIES."</strong><br /><span class=\"pn-sub\">";
    $column = &$pntable['downloads_subcategories_column'];
    $result =& $dbconn->Execute("SELECT $column[sid],
                                       $column[cid],
                                       $column[title]
                                FROM $pntable[downloads_subcategories]
                                ORDER BY $column[title]");

    while(list($sid, $cid, $title) = $result->fields) {
        $transfertitle = str_replace (" ", "_", $title);
        $column = &$pntable['downloads_categories_column'];
        $result2 =& $dbconn->Execute("SELECT $column[title]
                                     FROM $pntable[downloads_categories]
                                     WHERE $column[cid] = '" . pnVarPrepForStore($cid)."'");

        while(list($ctitle) = $result2->fields) {
            echo "<a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=DownloadsValidate&amp;cid=0&amp;sid=$sid&amp;ttitle=$transfertitle\">".pnVarPrepForDisplay($ctitle)."</a>";
            $result2->MoveNext();
        }
        echo " / <a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=DownloadsValidate&amp;cid=0&amp;sid=$sid&amp;ttitle=$transfertitle\">".pnVarPrepForDisplay($title)."</a><br />";
        $result->MoveNext();
    }
    echo "</span></div></td></tr></table>";
    CloseTable();
    include ('footer.php');
}

function DownloadsValidate()
{
    list($cid,
         $aid,
         $sid,
         $ttitle) = pnVarCleanFromInput('cid',
                                        'aid',
                                        'sid',
                                        'ttitle');
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!isset($sid)) {
        $sid = 0;
    }
    if (!isset($cid) || !is_numeric($cid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }


    include ('header.php');
    GraphicAdmin();
    OpenTable();
    echo '<h2>'._WEBDOWNLOADSADMIN.'</h2>';
    CloseTable();

    OpenTable();
    $transfertitle = str_replace ("_", "", $ttitle);
    /* Check ALL Downloads */
    echo "<table width=\"100%\" border=\"0\">";
    if ($cid==0 && $sid==0) {
    echo "<tr><td colspan=\"3\"><div style=\"text-align:center\"><strong>"._CHECKALLDOWNLOADS.'</strong><br />'._BEPATIENT."</div></td></tr>";
    $column = &$pntable['downloads_downloads_column'];
    $result =& $dbconn->Execute("SELECT $column[lid],
                                       $column[title],
                                       $column[url],
                                       $column[name],
                                       $column[email],
                                       $column[submitter]
                                FROM $pntable[downloads_downloads]
                                ORDER BY $column[title]");
    }
    /* Check Categories & Subcategories */
    if ($cid!=0 && $sid==0) {
    echo "<tr><td colspan=\"3\"><div style=\"text-align:center\"><strong>"._VALIDATINGCAT.": $transfertitle</strong><br />"._BEPATIENT."</div></td></tr>";
    $column = &$pntable['downloads_downloads_column'];
    $result =& $dbconn->Execute("SELECT $column[lid],
                                       $column[title],
                                       $column[url],
                                       $column[name],
                                       $column[email],
                                       $column[submitter]
                                FROM $pntable[downloads_downloads]
                                WHERE $column[cid]='" . (int)pnVarPrepForStore($cid) . "'
                                ORDER BY $column[title]");
    }
    /* Check Only Subcategory */
    if ($cid==0 && $sid!=0) {
    echo "<tr><td colspan=\"3\"><div style=\"text-align:center\"><strong>"._VALIDATINGSUBCAT.": ".pnVarPrepForDisplay($transfertitle).'</strong><br />'._BEPATIENT."</div></td></tr>";
    $column = &$pntable['downloads_downloads_column'];
    $result =& $dbconn->Execute("SELECT $column[lid],
                                       $column[title],
                                       $column[url],
                                       $column[name],
                                       $column[email],
                                       $column[submitter]
                                FROM $pntable[downloads_downloads]
                                WHERE $column[sid]='" . (int)pnVarPrepForStore($sid) . "'
                                ORDER BY $column[title]");
    }
    echo "<tr><td style=\"background-color:".$GLOBALS['bgcolor2']."\" align=\"center\"><strong>"._STATUS."</strong></td><td style=\"background-color:".$GLOBALS['bgcolor2'].";width:100%\"><strong>"._DOWNLOADTITLE."</strong></td><td style=\"background-color:".$GLOBALS['bgcolor2']."\" align=\"center\"><strong>"._FUNCTIONS."</strong></td></tr>";

    while(list($lid, $title, $url, $name, $email, $submitter) = $result->fields) {
        $vurl = parse_url($url);
        $fp = fsockopen ($vurl['host'], 80, $errno, $errstr, 30);
        if (!$fp){
                echo "<tr><td align=\"center\"><strong>&nbsp;&nbsp;"._FAILED."&nbsp;(nc)&nbsp;</strong></td>"
                ."<td>&nbsp;&nbsp;<a href=\"$url\">".pnVarPrepForDisplay($title)."</a>&nbsp;&nbsp;</td>"
                ."<td align=\"center\">&nbsp;&nbsp;[ <a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=DownloadsModDownload&amp;lid=$lid\">"._EDIT."</a> | <a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=DownloadsDelDownload&amp;lid=$lid&amp;authid=" . pnSecGenAuthKey() . "\">"._DELETE."</a> ]&nbsp;&nbsp;"
                ."</td></tr>";
        } else {
            fputs ($fp, "HEAD ".$url." HTTP/1.0\r\n\r\n");
            $buffer = fgets($fp,256);
            if( (eregi("OK", $buffer)) || (eregi("302 Found", $buffer)) ) {
                echo "<tr><td align=\"center\">&nbsp;&nbsp;"._OK."&nbsp;&nbsp;</td>"
                ."<td>&nbsp;&nbsp;<a href=\"$url\">".pnVarPrepForDisplay($title)."</a>&nbsp;&nbsp;</td>"
                ."<td align=\"center\">&nbsp;&nbsp;"._NONE."&nbsp;&nbsp;>"
                ."</td></tr>";
            } else {
                echo "<tr><td align=\"center\"><strong>&nbsp;&nbsp;"._FAILED."</strong>&nbsp;&nbsp;<br />".str_replace("HTTP/1.0", "", $buffer)."</td>"
                ."<td>&nbsp;&nbsp;<a href=\"$url\">".pnVarPrepForDisplay($title)."</a>&nbsp;&nbsp;</td>"
                ."<td align=\"center\">&nbsp;&nbsp;[ <a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=DownloadsModDownload&amp;lid=$lid\">"._EDIT."</a> | <a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=DownloadsDelDownload&amp;lid=$lid&amp;authid=" . pnSecGenAuthKey() . "\">"._DELETE."</a> ]&nbsp;&nbsp;"
                ."</td></tr>";
            }
            fclose ($fp);
        }
        $result->MoveNext();
    }
    echo "</table>";
    CloseTable();
    include ('footer.php');
}

function DownloadsAddDownload()
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    list($new,
         $lid,
         $title,
         $url,
         $cat,
         $description,
         $name,
         $email,
         $submitter,
         $filesize,
         $version,
         $homepage,
         $hits) = pnVarCleanFromInput('new',
                                      'lid',
                                      'title',
                                      'url',
                                      'cat',
                                      'description',
                                      'name',
                                      'email',
                                      'submitter',
                                      'filesize',
                                      'version',
                                      'homepage',
                                      'hits');

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    /*
     * Hootbah: XXX FIXME XXX I don't think we need the following query.
     * It seems to be only used for numRows
     */
    $column = &$pntable['downloads_downloads_column'];
    $result =& $dbconn->Execute("SELECT $column[url]
                                FROM $pntable[downloads_downloads]
                                WHERE $column[url]='" . pnVarPrepForStore($url) . "'");
    $numrows = $result->PO_RecordCount();

    $error="";
    if ($description=="")        { $error = _ERRORNODESCRIPTION;}
    elseif ($title=="")          { $error = _ERRORNOTITLE;  }
    elseif ($numrows>0)          { $error = _ERRORURLEXIST; }
    elseif ($url=="")            { $error = _ERRORNOURL;    }
    if ($hits == "") {
        $hits = 0;
    }

    if ($error!="") {
		include('header.php');
		GraphicAdmin();
		OpenTable();
		echo '<h2>'._WEBDOWNLOADSADMIN.'</h2>';
		CloseTable();

		OpenTable();
		echo '<br /><div style="text-align:center">'
			."<strong>$error</strong><br />"
			._GOBACK.'<br />';
		CloseTable();
		include('footer.php');
    } else {

    $cat = explode("-", $cat);
    if (empty($cat[1])) $cat[1] = 0;
// cocomp 2002/07/13 Converted to use GenID and not use NULL for id insert
// removed now() replaced with DBTimestamp(time()) for cross db compatibility
	$downtable = $pntable['downloads_downloads'];
	$newid = $dbconn->GenID($downtable);
    $dbconn->Execute("INSERT INTO $downtable
                        ($column[lid],
                         $column[cid],
                         $column[sid],
                         $column[title],
                         $column[url],
                         $column[description],
                         $column[date],
                         $column[name],
                         $column[email],
                         $column[hits],
                         $column[submitter],
                         $column[downloadratingsummary],
                         $column[totalvotes],
                         $column[totalcomments],
                         $column[filesize],
                         $column[version],
                         $column[homepage])
                      VALUES
                        (" . (int)pnVarPrepForStore($newid) . ",
                         " . (int)pnVarPrepForStore($cat[0]) .",
                         " . (int)pnVarPrepForStore($cat[1]) .",
                         '" . pnVarPrepForStore($title) . "',
                         '" . pnVarPrepForStore($url) . "',
                         '" . pnVarPrepForStore($description) . "',
                          " . $dbconn->DBTimestamp(time()) . ",
                         '" . pnVarPrepForStore($name) . "',
                         '" . pnVarPrepForStore($email) . "',
                         '" . (int)pnVarPrepForStore($hits) . "',
                         '" . pnVarPrepForStore($submitter) . "',
                         0,
                         0,
                         0,
                         '" . pnVarPrepForStore($filesize) . "',
                         '" . pnVarPrepForStore($version) . "',
                         '" . pnVarPrepForStore($homepage) . "')");

    // Let any hooks know that we have created a new link
    pnModCallHooks('item', 'create', $newid, 'lid');

    include('header.php');
    GraphicAdmin();
    OpenTable();
    echo '<br /><div style="text-align:center">';
    echo _NEWDOWNLOADADDED.'<br />';
    echo "[ <a href=\"admin.php?module=".$GLOBALS['module']."&op=downloads\">"._WEBDOWNLOADSADMIN."</a> ]</div><br />";
    CloseTable();
    if ($new==1) {
	    $column = &$pntable['downloads_newdownload_column'];
	    $dbconn->Execute("DELETE FROM $pntable[downloads_newdownload] WHERE $column[lid]='".pnVarPrepForStore($lid)."'");
    }
    include('footer.php');
    }
}

function downloads_admin_getConfig() {

    include ('header.php');

    if (!pnSecAuthAction(0, "Downloads::", "::", ACCESS_ADMIN)) {
        echo 'Access denied';
        include('footer.php');
        return;
    }

    // prepare vars
    $sel_perpage['10'] = '';
    $sel_perpage['15'] = '';
    $sel_perpage['20'] = '';
    $sel_perpage['25'] = '';
    $sel_perpage['30'] = '';
    $sel_perpage['50'] = '';
    $sel_perpage[pnConfigGetVar('perpage')] = ' selected="selected"';
    $sel_popular['100'] = '';
    $sel_popular['250'] = '';
    $sel_popular['500'] = '';
    $sel_popular['1000'] = '';
    $sel_popular['1500'] = '';
    $sel_popular['2000'] = '';
	$sel_popular[pnConfigGetVar('popular')] = ' selected="selected"';
    $sel_newdownloads['10'] = '';
    $sel_newdownloads['15'] = '';
    $sel_newdownloads['20'] = '';
    $sel_newdownloads['25'] = '';
    $sel_newdownloads['30'] = '';
    $sel_newdownloads['50'] = '';
    $sel_newdownloads[pnConfigGetVar('newdownloads')] = ' selected="selected"';
    $sel_topdownloads['10'] = '';
    $sel_topdownloads['15'] = '';
    $sel_topdownloads['20'] = '';
    $sel_topdownloads['25'] = '';
    $sel_topdownloads['30'] = '';
    $sel_topdownloads['50'] = '';
    $sel_topdownloads[pnConfigGetVar('topdownloads')] = ' selected="selected"';
    $sel_downloadsresults['10'] = '';
    $sel_downloadsresults['15'] = '';
    $sel_downloadsresults['20'] = '';
    $sel_downloadsresults['25'] = '';
    $sel_downloadsresults['30'] = '';
    $sel_downloadsresults['50'] = '';
    $sel_downloadsresults[pnConfigGetVar('downloadsresults')] = ' selected="selected"';
    $sel_anonadddownloadlock['0'] = '';
    $sel_anonadddownloadlock['1'] = '';
    $sel_anonadddownloadlock[pnConfigGetVar('downloads_anonadddownloadlock')] = ' checked="checked"';
    $sel_useoutsidevoting['0'] = '';
    $sel_useoutsidevoting['1'] = '';
    $sel_useoutsidevoting[pnConfigGetVar('useoutsidevoting')] = ' checked="checked"';
    $sel_featurebox['0'] = '';
    $sel_featurebox['1'] = '';
    $sel_featurebox[pnConfigGetVar('featurebox')] = ' checked="checked"';
    $sel_blockunregmodify['0'] = '';
    $sel_blockunregmodify['1'] = '';
    $sel_blockunregmodify[pnConfigGetVar('blockunregmodify')] = ' checked="checked"';

    GraphicAdmin();
    OpenTable();
    print '<h2>	'._DOWNLOADSCONF.'</h2>'
        .'<form action="admin.php" method="post"><div>'
        .'<table border="0"><tr><td>'
        ._DOWNLOADSPAGE.':</td><td>'
        .'<select name="xperpage" size="1">'
        ."<option value=\"5\"".$sel_perpage['5'].">5</option>\n"
        ."<option value=\"10\"".$sel_perpage['10'].">10</option>\n"
        ."<option value=\"15\"".$sel_perpage['15'].">15</option>\n"
        ."<option value=\"20\"".$sel_perpage['20'].">20</option>\n"
        ."<option value=\"25\"".$sel_perpage['25'].">25</option>\n"
        ."<option value=\"30\"".$sel_perpage['30'].">30</option>\n"
        ."<option value=\"50\"".$sel_perpage['50'].">50</option>\n"
        .'</select>'
        .'</td></tr>'
        .'<tr><td>'
        ._ANONWAITDAYS."</td><td><input type=\"text\" name=\"xanonwaitdays\" value=\"".pnConfigGetVar('anonwaitdays')."\" size=\"4\" /> "._DAYS
        .'</td></tr>'
        .'<tr><td>'
        ._OUTSIDEWAITDAYS."</td><td><input type=\"text\" name=\"xoutsidewaitdays\" value=\"".pnConfigGetVar('outsidewaitdays')."\" size=\"4\" /> "._DAYS
       .'</td></tr>'
        .'<tr><td>'
        ._USEOUTSIDEVOTING.'</td><td>'
        ."<input type=\"radio\" name=\"xuseoutsidevoting\" value=\"1\" ".$sel_useoutsidevoting['1']." />"._YES.' &nbsp;'
        ."<input type=\"radio\" name=\"xuseoutsidevoting\" value=\"0\" ".$sel_useoutsidevoting['0']." />"._NO
       .'</td></tr>'
        .'<tr><td>'
        ._ANONWEIGHT."</td><td><input type=\"text\" name=\"xanonweight\" value=\"".pnConfigGetVar('anonweight')."\" size=\"4\" />"
       .'</td></tr>'
        .'<tr><td>'
        ._OUTSIDEWEIGHT."</td><td><input type=\"text\" name=\"xoutsideweight\" value=\"".pnConfigGetVar('outsideweight')."\" size=\"4\" />"
       .'</td></tr>'
        .'<tr><td>'
        ._DETAILVOTEDECIMAL."</td><td><input type=\"text\" name=\"xdetailvotedecimal\" value=\"".pnConfigGetVar('detailvotedecimal')."\" size=\"4\" />"
       .'</td></tr>'
        .'<tr><td>'
        ._MAINVOTEDECIMAL."</td><td><input type=\"text\" name=\"xmainvotedecimal\" value=\"".pnConfigGetVar('mainvotedecimal')."\" size=\"4\" />"
       .'</td></tr>'
        .'<tr><td>'
        ._TOPDOWNLOADSPERCENTRIGGER."</td><td><input type=\"text\" name=\"xtopdownloadspercentrigger\" value=\"".pnConfigGetVar('topdownloadspercentrigger')."\" size=\"4\" />"
       .'</td></tr>'
        .'<tr><td>'
        ._TOPDOWNLOADS."</td><td><input type=\"text\" name=\"xtopdownloads\" value=\"".pnConfigGetVar('topdownloads')."\" size=\"4\" />"
       .'</td></tr>'
        .'<tr><td>'
        ._MOSTPOPDOWNLOADSPERCENTRIGGER."</td><td><input type=\"text\" name=\"xmostpopdownloadspercentrigger\" value=\"".pnConfigGetVar('mostpopdownloadspercentrigger')."\" size=\"4\" />"
       .'</td></tr>'
        .'<tr><td>'
        ._MOSTPOPDOWNLOADS."</td><td><input type=\"text\" name=\"xmostpopdownloads\" value=\"".pnConfigGetVar('mostpopdownloads')."\" size=\"4\" />"
       .'</td></tr>'
        .'<tr><td>'
        ._FEATUREBOX.'</td><td>'
        ."<input type=\"radio\" name=\"xfeaturebox\" value=\"1\" ".$sel_featurebox['1']." />"._YES.' &nbsp;'
        ."<input type=\"radio\" name=\"xfeaturebox\" value=\"0\" ".$sel_featurebox['0']." />"._NO
       .'</td></tr>'
        .'<tr><td>'
        ._LINKVOTEMIN."</td><td><input type=\"text\" name=\"xlinkvotemin\" value=\"".pnConfigGetVar('linkvotemin')."\" size=\"4\" />"
       .'</td></tr>'
        .'<tr><td>'
        ._BLOCKUNREGMODIFY.'</td><td>'
        ."<input type=\"radio\" name=\"xblockunregmodify\" value=\"1\" ".$sel_blockunregmodify['1']." />"._YES.' &nbsp;'
        ."<input type=\"radio\" name=\"xblockunregmodify\" value=\"0\" ".$sel_blockunregmodify['0']." />"._NO
       .'</td></tr>'
         .'<tr><td>'
        ._TOBEPOPULAR.':</td><td>'
        .'<select name="xpopular" size="1">'
        ."<option value=\"100\"".$sel_popular['100'].">100</option>\n"
        ."<option value=\"250\"".$sel_popular['250'].">250</option>\n"
        ."<option value=\"500\"".$sel_popular['500'].">500</option>\n"
        ."<option value=\"1000\"".$sel_popular['1000'].">1000</option>\n"
        ."<option value=\"1500\"".$sel_popular['1500'].">1500</option>\n"
        ."<option value=\"2000\"".$sel_popular['2000'].">2000</option>\n"
        .'</select>'
        .'</td></tr><tr><td>'
        ._DOWNLOADSASNEW.':</td><td>'
        .'<select name="xnewdownloads" size="1">'
        ."<option value=\"10\"".$sel_newdownloads['10'].">10</option>\n"
        ."<option value=\"15\"".$sel_newdownloads['15'].">15</option>\n"
        ."<option value=\"20\"".$sel_newdownloads['20'].">20</option>\n"
        ."<option value=\"25\"".$sel_newdownloads['25'].">25</option>\n"
        ."<option value=\"30\"".$sel_newdownloads['30'].">30</option>\n"
        ."<option value=\"50\"".$sel_newdownloads['50'].">50</option>\n"
        .'</select>'
        .'</td></tr><tr><td>'
        ._DOWNLOADSASBEST.':</td><td>'
        .'<select name="xtopdownloads" size="1">'
        ."<option value=\"10\"".$sel_topdownloads['10'].">10</option>\n"
        ."<option value=\"15\"".$sel_topdownloads['15'].">15</option>\n"
        ."<option value=\"20\"".$sel_topdownloads['20'].">20</option>\n"
        ."<option value=\"25\"".$sel_topdownloads['25'].">25</option>\n"
        ."<option value=\"30\"".$sel_topdownloads['30'].">30</option>\n"
        ."<option value=\"50\"".$sel_topdownloads['50'].">50</option>\n"
        .'</select>'
        .'</td></tr><tr><td>'
        ._DOWNLOADSINRES.':</td><td>'
        .'<select name="xdownloadsresults">'
        ."<option value=\"10\"".$sel_downloadsresults['10'].">10</option>\n"
        ."<option value=\"15\"".$sel_downloadsresults['15'].">15</option>\n"
        ."<option value=\"20\"".$sel_downloadsresults['20'].">20</option>\n"
        ."<option value=\"25\"".$sel_downloadsresults['25'].">25</option>\n"
        ."<option value=\"30\"".$sel_downloadsresults['30'].">30</option>\n"
        ."<option value=\"50\"".$sel_downloadsresults['50'].">50</option>\n"
        .'</select>'
        .'</td></tr><tr><td>'
        ._ANONPOSTDOWNLOADS.'</td><td>'
        ."<input type=\"radio\" name=\"xdownloads_anonadddownloadlock\" value=\"1\" ".$sel_anonadddownloadlock['1']." />"._YES.' &nbsp;'
        ."<input type=\"radio\" name=\"xdownloads_anonadddownloadlock\" value=\"0\" ".$sel_anonadddownloadlock['0']." />"._NO
        .'</td></tr></table>'
        ."<input type=\"hidden\" name=\"module\" value=\"".$GLOBALS['module']."\" />"
        ."<input type=\"hidden\" name=\"op\" value=\"setConfig\" />"
        .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
        ."<input type=\"submit\" value=\""._SUBMIT."\" />"
        ."</div></form>";
    CloseTable();
    include ('footer.php');
}

function downloads_admin_setConfig($var) {

    if (!pnSecAuthAction(0, "Downloads::", "::", ACCESS_ADMIN)) {
        include('header.php');
        echo 'Access denied';
        include('footer.php');
        return;
    }

    // Escape some characters in these variables.
    // hehe, I like doing this, much cleaner :-)
    $fixvars = array();

    // todo: make FixConfigQuotes global / replace with other function
    foreach ($fixvars as $v) {
	//$var[$v] = FixConfigQuotes($var[$v]);
    }

    // Set any numerical variables that havn't been set, to 0. i.e. paranoia check :-)
    $fixvars = array('xdownloads_anonadddownloadlock');

    foreach ($fixvars as $v) {
        if (empty($var[$v])) {
            $var[$v] = 0;
        }
    }

    // all variables starting with x are the config vars.
    while (list ($key, $val) = each ($var)) {
        if (substr($key, 0, 1) == 'x') {
            pnConfigSetVar(substr($key, 1), $val);
        }
    }
    pnRedirect('admin.php?module='.$GLOBALS['module'].'&op=downloads');
}

function downloads_admin_main($var) {
    if (!pnSecAuthAction(0, "Downloads::", "::", ACCESS_ADMIN)) {
        include('header.php');
        echo 'Access denied';
        include('footer.php');
        return;
    }
    $op = pnVarCleanFromInput('op');
    extract($var);
    switch ($op)
    {
           case "downloads":
                downloads();
                break;

            case "DownloadsDelNew":
                 DownloadsDelNew();
                 break;

            case "DownloadsAddCat":
                 DownloadsAddCat();
                 break;

            case "DownloadsAddSubCat":
                 DownloadsAddSubCat();
                 break;

            case "DownloadsAddDownload":
                DownloadsAddDownload();
                 break;

            case "DownloadsAddEditorial":
                 DownloadsAddEditorial();
                 break;

            case "DownloadsModEditorial":
                 DownloadsModEditorial();
                 break;

            case "DownloadsDownloadCheck":
                 DownloadsDownloadCheck();
                 break;

            case "DownloadsValidate":
                 DownloadsValidate();
                 break;

            case "DownloadsDelEditorial":
                 DownloadsDelEditorial();
                 break;

            case "DownloadsCleanVotes":
                DownloadsCleanVotes();
                break;

            case "DownloadsListBrokenDownloads":
                DownloadsListBrokenDownloads();
                break;

            case "DownloadsDelBrokenDownloads":
                DownloadsDelBrokenDownloads();
                break;

            case "DownloadsIgnoreBrokenDownloads":
               DownloadsIgnoreBrokenDownloads();
               break;

            case "DownloadsListModRequests":
               DownloadsListModRequests();
               break;

            case "DownloadsChangeModRequests":
               DownloadsChangeModRequests();
               break;

            case "DownloadsChangeIgnoreRequests":
               DownloadsChangeIgnoreRequests();
               break;

            case "DownloadsDelCat":
                 DownloadsDelCat();
                 break;

            case "DownloadsModCat":
                 DownloadsModCat($cat);
                 break;

            case "DownloadsModCatS":
                 DownloadsModCatS();
                 break;

            case "DownloadsModDownload":
                 DownloadsModDownload();
                 break;

            case "DownloadsModDownloadS":
                 DownloadsModDownloadS();
                 break;

            case "DownloadsDelDownload":
                 DownloadsDelDownload();
                 break;

            case "DownloadsDelVote":
                 DownloadsDelVote();
                 break;

            case "DownloadsDelComment":
                 DownloadsDelComment();
                 break;

             case "getConfig":
                  downloads_admin_getConfig();
                  break;

            case "setConfig":
                 downloads_admin_setConfig($var);
                 break;

            default:
                    downloads();
                    break;
      }
}
?>