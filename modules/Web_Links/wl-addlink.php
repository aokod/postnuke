<?php
// File: $Id: wl-addlink.php 16832 2005-09-29 21:37:13Z hammerhead $
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
// 11-30-2001:ahumphr - created file as part of modularistation
// 10-15-2002:skooter      - Cross Site Scripting security fixes and also using 
//                           pnAPI for displaying data.
/**
 * AddLink
 */
function AddLink()
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    include('header.php');
    $mainlink = 1;
    menu(1);

    OpenTable();
    $yn = $ye = "";
    if (pnUserLoggedIn()) {
        $yn = pnUserGetVar('uname');
        $ye = pnUserGetVar('email');
    }

    echo '<h2>'._ADDALINK.'</h2>';
    if (pnSecAuthAction(0, 'Web Links::', "::", ACCESS_COMMENT) || pnConfigGetVar('links_anonaddlinklock')) {
        echo '<strong>'._INSTRUCTIONS.":</strong><br />"
        ."<strong><big>&middot;</big></strong> "._SUBMITONCE.'<br />'
        ."<strong><big>&middot;</big></strong> "._POSTPENDING.'<br />'
        ."<strong><big>&middot;</big></strong> "._USERANDIP.'<br />'
        ."<form method=\"post\" action=\"".$GLOBALS['modurl']."\"><div>"
        ._PAGETITLE.": <input type=\"text\" name=\"title\" size=\"50\" maxlength=\"100\" /><br />"
        ._PAGEURL.": <input type=\"text\" name=\"url\" size=\"75\" maxlength=\"254\" value=\"http://\" /><br />";
        echo ""._CATEGORY.": <select name=\"cat\">";
        echo CatList(0, 0);
        echo "</select><br />"
            ._LDESCRIPTION."<br /><textarea name=\"description\" cols=\"80\" rows=\"10\"></textarea><br />"
            ._YOURNAME.": <input type=\"text\" name=\"nname\" size=\"30\" maxlength=\"60\" value=\"$yn\" /><br />"
            ._YOUREMAIL.": <input type=\"text\" name=\"email\" size=\"30\" maxlength=\"60\" value=\"$ye\" /><br />"
            ."<input type=\"hidden\" name=\"req\" value=\"Add\" />"
            ."<input type=\"hidden\" name=\"authid\" value=\"".pnSecGenAuthKey()."\" />"
            ."<input type=\"submit\" value=\""._ADDURL."\" /> "._GOBACK.'<br />'
            ."</div></form>";
    }else {
        echo "<div style=\"text-align:center\">"._LINKSNOTUSER1.'<br />'
        ._LINKSNOTUSER2.'<br />'
            ._LINKSNOTUSER3.'<br />'
            ._LINKSNOTUSER4.'<br />'
            ._LINKSNOTUSER5.'<br />'
            ._LINKSNOTUSER6.'<br />'
            ._LINKSNOTUSER7.'<br />'
            ._LINKSNOTUSER8.'</div>';
    }
    CloseTable();
    include('footer.php');
}

/**
 * Add
 */
function Add()
{
    list($title,
         $url,
         $nname,
         $cat,
         $description,
         $email) = pnVarCleanFromInput('title',
                                       'url',
                                       'nname',
                                       'cat',
                                       'description',
                                       'email');

    if (!isset($cat) || !is_numeric($cat)){
        include 'header.php';
        echo _MODARGSERROR;
        include 'footer.php';
        return;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    if (!pnSecAuthAction(0, 'Web Links::', "::", ACCESS_COMMENT)) {
	    include 'header.php';
	    echo _WEBLINKSADDNOAUTH;
	    include 'footer.php';
	    return;
    }
    
    $column = &$pntable['links_links_column'];
    $existingurl =& $dbconn->Execute("SELECT $column[title] FROM $pntable[links_links] WHERE $column[url]='" . pnVarPrepForStore($url) . "'");
    //$result =& $dbconn->Execute("SELECT COUNT(*) FROM $pntable[links_links] WHERE $column[url]='".pnVarPrepForStore($url)."'");
    //$numrows = $result->PO_RecordCount(); 
	//list($numrows) = $result->fields;

    //if ($numrows>0) {
	if (!$existingurl->EOF) {
        include('header.php');
        menu(1);

        OpenTable();
        echo '<div style="text-align:center"><strong>'._LINKALREADYEXT.'</strong><br />'
            ._GOBACK.'</div>';
        CloseTable();
        include('footer.php');
    } else {
        if (pnUserLoggedIn()) {
            $submitter = pnUserGetVar('uname');
    }

// Check if Title exist
    if ($title=="") {
    include('header.php');
    menu(1);

    OpenTable();
    echo '<div style="text-align:center"><strong>'._LINKNOTITLE.'</strong><br />'
        ._GOBACK.'</div>';
    CloseTable();
    include('footer.php');
    exit;
    }

// Check if URL exist
	$valid = pnVarValidate($url, 'url');
    if ($valid == false) {
    include('header.php');
    menu(1);

    OpenTable();
    echo '<div style="text-align:center"><strong>'._LINKNOURL.'</strong><br />'
        ._GOBACK.'</div>';
    CloseTable();
    include('footer.php');
    exit;
    }

// Check if Category exists
    if ($cat=="") {
    include('header.php');
    menu(1);

    OpenTable();
    echo '<div style=\"text-align:center\"><strong>'._LINKNOCAT.'</strong><br />'
        ._GOBACK.'</div>';
    CloseTable();
    include('footer.php');
    exit;
}

// Check if Description exist
    if ($description=="") {
    include('header.php');
    menu(1);

    OpenTable();
    echo '<div style="text-align:center"><strong>'._LINKNODESC.'</strong><br />'
        ._GOBACK.'</div>';
    CloseTable();
    include('footer.php');
    exit;
    }

    $column = &$pntable['links_newlink_column'];
    $nextid = $dbconn->GenId($pntable['links_newlink']);
    $dbconn->Execute("INSERT INTO $pntable[links_newlink] ($column[lid], $column[cat_id], $column[title], $column[url], $column[description], $column[name], $column[email], $column[submitter]) VALUES ($nextid, ".(int)pnVarPrepForStore($cat).", '".pnVarPrepForStore($title)."', '".pnVarPrepForStore($url)."', '".pnVarPrepForStore($description)."', '".pnVarPrepForStore($nname)."', '".pnVarPrepForStore($email)."', '".pnVarPrepForStore($submitter)."')");
    include('header.php');
    menu(1);

    OpenTable();
    echo '<div style="text-align:center"><strong>'._LINKRECEIVED.'</strong><br />';
    if ($email != "") {
        echo _EMAILWHENADD;
    } else {
        echo _CHECKFORIT;
    }
	echo '</div>';
    CloseTable();
        include('footer.php');
    }
}

?>