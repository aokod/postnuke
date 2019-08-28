<?php
// File: $Id: dl-adddownload.php 15951 2005-03-08 21:07:03Z larsneo $
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

function AddDownload() {
    include('header.php');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!(pnSecAuthAction(0, 'Downloads::Item', '::', ACCESS_COMMENT))) {
        echo _DOWNLOADSADDNOAUTH;
        include 'footer.php';
        return;
    }
    $maindownload = 1;
    menu(1);

    OpenTable();
    echo '<h2>'._ADDADOWNLOAD.'</h2>';
    if (pnUserLoggedIn() || pnConfigGetVar('downloads_anonadddownloadlock') != 1) {
        echo '<strong>'._INSTRUCTIONS.":</strong>"
        ."<ul><li>"._DSUBMITONCE."</li>"
        ."<li>"._DPOSTPENDING."</li>"
        ."<li>"._USERANDIP."</li></ul>"
        ."<form action=\"index.php\" method=\"post\"><div>"
        ."<input type=\"hidden\" name=\"name\" value=\"".$GLOBALS['ModName']."\" />\n"
        ._DOWNLOADNAME.": <input type=\"text\" name=\"title\" size=\"50\" maxlength=\"100\" /><br />"
        ._FILEURL.": <input type=\"text\" name=\"url\" size=\"50\" maxlength=\"250\" value=\"http://\" /><br />";
        $column = &$pntable['downloads_categories_column'];
        $result =& $dbconn->Execute("SELECT $column[cid], $column[title]
                                FROM $pntable[downloads_categories]
                                ORDER BY $column[title]");
        echo _CATEGORY.": <select name=\"cat\">";
        while(list($cid, $title) = $result->fields) {

            $result->MoveNext();
            if (pnSecAuthAction(0, 'Downloads::Category', "$title::$cid", ACCESS_COMMENT)) {
                echo "<option value=\"$cid\">".pnVarPrepForDisplay($title)."</option>";
                $column=&$pntable['downloads_subcategories_column'];
                $result2 =& $dbconn->Execute("SELECT $column[sid], $column[title]
                                           FROM $pntable[downloads_subcategories]
                                           WHERE $column[cid]='".pnVarPrepForStore($cid)."' ORDER BY $column[title]");
                while(list($sid, $stitle) = $result2->fields) {

                    $result2->MoveNext();
                    if (pnSecAuthAction(0, 'Downloads::Category', "$stitle::$sid", ACCESS_COMMENT)) {
                        echo "<option value=\"$cid-$sid\">".pnVarPrepForDisplay($title)." / ".pnVarPrepForDisplay($stitle)."</option>";
                    }
                }
            }
        }
        echo "</select><br />"
        ._LDESCRIPTION."<br /><textarea name=\"description\" cols=\"80\" rows=\"10\"></textarea><br />"
        ._AUTHORNAME.": <input type=\"text\" name=\"nname\" size=\"30\" maxlength=\"60\" /><br />"
        ._AUTHOREMAIL.": <input type=\"text\" name=\"email\" size=\"30\" maxlength=\"60\" /><br />"
        ._FILESIZE.": <input type=\"text\" name=\"filesize\" size=\"10\" maxlength=\"10\" /> ("._INBYTES.")<br />"
        ._VERSION.": <input type=\"text\" name=\"version\" size=\"10\" maxlength=\"10\" /><br />"
        ._HOMEPAGE.": <input type=\"text\" name=\"homepage\" size=\"50\" maxlength=\"200\" value=\"http://\" /><br />"
        ."<input type=\"hidden\" name=\"req\" value=\"Add\" />"
        ."<input type=\"submit\" value=\""._ADDTHISFILE."\" /> "._GOBACK.'<br />'
        ."</div></form>";
    } else {
        echo "<div style=\"text-align:center\">"._DOWNLOADSNOTUSER1.'<br />'
        ._DOWNLOADSNOTUSER2.'<br />'
        ._DOWNLOADSNOTUSER3.'<br />'
        ._DOWNLOADSNOTUSER4.'<br />'
        ._DOWNLOADSNOTUSER5.'<br />'
        ._DOWNLOADSNOTUSER6.'<br />'
        ._DOWNLOADSNOTUSER7.'<br />'
        ._DOWNLOADSNOTUSER8.'</div>';
    }
    CloseTable();
    include('footer.php');
}

function Add()
{
    list($title,
         $url,
         $nname,
         $cat,
         $description,
         $name,
         $email,
         $filesize,
         $version,
         $homepage) = pnVarCleanFromInput('title',
                                          'url',
                                          'nname',
                                          'cat',
                                          'description',
                                          'name',
                                          'email',
                                          'filesize',
                                          'version',
                                          'homepage');
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column=&$pntable['downloads_downloads_column'];
/* hootbah: I think that this is only getting the count(*) value back.
 *
 *  $result = $dbconn->query("SELECT $column[url]
 *                            FROM $pntable[downloads_downloads]
 *                            WHERE $column[url]='".pnVarPrepForStore($url)."'");
 *  $numrows = numRows($result);
 */
    include 'header.php';
    menu(1);

    if (!isset($cat)) {
        echo _DOWNLOADSNOCATS;
        include 'footer.php';
    }

    $catname = downloads_CatNameFromCID($cat);
    if (!(pnSecAuthAction(0, 'Downloads::Item', "$title:$catname:", ACCESS_COMMENT))) {
        echo _DOWNLOADSADDNOAUTH;
        include 'footer.php';
        return;
    }

    OpenTable();
    $result =& $dbconn->Execute("SELECT count(*)
                              FROM $pntable[downloads_downloads]
                              WHERE $column[url]='".pnVarPrepForStore($url)."'");
    list($numrows) = $result->fields;
    if ($numrows>0) {
        echo "<div style=\"text-align:center\">"._DOWNLOADALREADYEXT.'<br />'._GOBACK;
        CloseTable();
        include('footer.php');
    } else {
        if (pnUserLoggedIn()) {
            $submitter = pnUserGetVar('uname');
        }
// Check if Title exist
        if ($title=="") {
            echo "<div style=\"text-align:center\">"._DOWNLOADNOTITLE.'<br />'._GOBACK;
            CloseTable();
            include('footer.php');
            return;
        }
// Check if URL exist
        if ($url=="") {
            echo "<div style=\"text-align:center\">"._DOWNLOADNOURL.'<br />'._GOBACK;
            CloseTable();
            include('footer.php');
            return;
        }
// Check if Description exist
        if ($description=="") {
            echo "<div style=\"text-align:center\">"._DOWNLOADNODESC.'<br />'._GOBACK;
            CloseTable();
            include('footer.php');
            return;
        }
        $cat = explode("-", $cat);
		if	(!isset($cat[0]) || !is_numeric($cat[0])){
			$cat[0] = 0;
		}
		if	(empty($cat[1]) || !is_numeric($cat[1])){
			$cat[1] = 0;
		}

        $filesize = ereg_replace("\.","",$filesize);
        $filesize = ereg_replace("\,","",$filesize);
        $column = &$pntable['downloads_newdownload_column'];
// cocomp 2002/07/13 changed to use GenID instead of NULL for id insert
	$newtable = $pntable['downloads_newdownload'];
	$lid = $dbconn->GenID($newtable);
        $result =& $dbconn->Execute("INSERT INTO $newtable
                                ($column[lid], $column[cid], $column[sid],
                                 $column[title], $column[url], $column[description],
                                 $column[name], $column[email], $column[submitter],
                                 $column[filesize], $column[version],
                                 $column[homepage])
                                VALUES (".(int)pnVarPrepForStore($lid).", ".(int)$cat[0].", ".(int)$cat[1].", '".pnVarPrepForStore($title)."', '".pnVarPrepForStore($url)."',
                                 '".pnVarPrepForStore($description)."', '".pnVarPrepForStore($nname)."', '".pnVarPrepForStore($email)."', '".pnVarPrepForStore($submitter)."',
                                 '".pnVarPrepForStore($filesize)."', '".pnVarPrepForStore($version)."', '".pnVarPrepForStore($homepage)."')");
        OpenTable();
        echo "<div style=\"text-align:center\">"._DOWNLOADRECEIVED.'<br />';
        if ($email == "") {
            echo _CHECKFORIT;
        }
        echo '</div>';
        CloseTable();
    }
    CloseTable();
    include 'footer.php';
}
?>