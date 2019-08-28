<?php
// File: $Id: admin.php 19379 2006-07-07 10:39:05Z markwest $
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

/**
 * banners_menu
 */
function banners_menu()
{
    GraphicAdmin();
    OpenTable();
    echo '<h2>'._BANNERSADMIN.'</h2>';
    echo "<div style=\"text-align: center;\">";
    echo "[ <a href=\"admin.php?module=".$GLOBALS['module']."\">" . pnVarPrepForDisplay(_BANNERSADMIN) . "</a> ] [ <a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=getConfig\">" . pnVarPrepForDisplay(_BANNERSCONF) . "</a> ]";
    echo '</div>';
    CloseTable();
}

/**
 * Banners Administration Functions
 */

function BannersAdmin()
{
    $bgcolor2 = $GLOBALS['bgcolor2'];
    list($clientname) = pnVarCleanFromInput('clientname');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

	$bannerTable = $pntable['banner'];
	$bannerColumn = &$pntable['banner_column'];

	$bannerclientTable = $pntable['bannerclient'];
	$bannerclientColumn = &$pntable['bannerclient_column'];

	$bannerfinishTable = $pntable['bannerfinish'];
	$bannerfinishColumn = &$pntable['bannerfinish_column'];

    include 'header.php';
    banners_menu();

    /* Check if Banners variable is active, if not then print a message */
    if (pnConfigGetVar('banners') == 0) {
        OpenTable();
        echo "<div style=\"text-align:center\"><br /><em><strong>"._IMPORTANTNOTE."</strong></em><br />"
            ._BANNERSNOTACTIVE.'<br />'._TOACTIVATE.'</div>';
        CloseTable();
    }

    // Banners List
    echo "<a id=\"top\"></a>";
    if (pnSecAuthAction(0, 'Banners::Banner', '::', ACCESS_READ)) {
        OpenTable();
        echo '<h2>'._ACTIVEBANNERS.'</h2>'
            ."<table width=\"100%\" border=\"0\">"
            ."<tr>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._BANTYPE."</strong></td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._IMPRESSIONS."</strong></td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._IMPLEFT."</strong></td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._CLICKS."</strong></td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._CLICKSPERCENT."</strong></td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._CLIENTNAME."</strong></td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._FUNCTIONS."</strong></td>"
            ."</tr>";

		$sql = "SELECT $bannerColumn[bid],
					   $bannerColumn[cid],
					   $bannerColumn[imptotal], 
					   $bannerColumn[impmade],
					   $bannerColumn[clicks],
					   $bannerColumn[date],
					   $bannerclientColumn[name],
					   $bannerColumn[type]
				FROM $bannerTable, $bannerclientTable
				WHERE $bannerColumn[cid] = $bannerclientColumn[cid] 
		ORDER BY $bannerColumn[bid]";
        $result =& $dbconn->Execute($sql);

        while(list($bid, $cid, $imptotal, $impmade, $clicks, $date, $name, $typ) = $result->fields) {

            $result->MoveNext();
            // jgm - Get and use $clientname
            if(!isset($clientname)) {
                $clientname = '';
            }
            if (pnSecAuthAction(0, 'Banners::Banner', "$clientname::$bid", ACCESS_READ)) {
                if($impmade==0) {
                    $percent = 0;
                } else {
                    $percent = substr(100 * $clicks / $impmade, 0, 5);
                }
                if($imptotal==0) {
                    $left = _UNLIMITED;
                } else {
                    $left = $imptotal-$impmade;
                }
                echo "<tr>"
                    ."<td style=\"background-color:$bgcolor2\" align=\"center\">" . pnVarPrepForDisplay($typ) . "</td>"
                    ."<td style=\"background-color:$bgcolor2\" align=\"center\">" . pnVarPrepForDisplay($impmade) . "</td>"
                    ."<td style=\"background-color:$bgcolor2\" align=\"center\">" . pnVarPrepForDisplay($left) . "</td>"
                    ."<td style=\"background-color:$bgcolor2\" align=\"center\">" . pnVarPrepForDisplay($clicks) . "</td>"
                    ."<td style=\"background-color:$bgcolor2\" align=\"center\">" . pnVarPrepForDisplay($percent) . "%</td>"
                    ."<td style=\"background-color:$bgcolor2\" align=\"center\">" . pnVarPrepForDisplay($name) . "</td>";
                if (pnSecAuthAction(0, 'Banners::Banner', "$clientname::$bid", ACCESS_EDIT)) {
                    echo "<td style=\"background-color:$bgcolor2\" align=\"center\"><a href=\"admin.php?module="
                    .$GLOBALS['module']."&amp;op=BannerEdit&amp;bid=$bid\">"._EDIT."</a>";
                    if (pnSecAuthAction(0, 'Banners::Banner', "$clientname::$bid", ACCESS_DELETE)) {
                        echo " | <a href=\"admin.php?module=".$GLOBALS['module']
                        ."&amp;op=BannerDelete&amp;bid=$bid&amp;ok=0\">"._DELETE."</a></td>";
                    } else {
                        echo "</td>";
                    }
                } else {
                    echo "<td style=\"background-color:$bgcolor2\">&nbsp;</td>";
                }
                echo "</tr>";
            }
        }
        echo "</table>";
        CloseTable();
    }

/* Finished Banners List */
    if (pnSecAuthAction(0, 'Banners::Banner', '::', ACCESS_READ)) {
        OpenTable();
        echo '<h2>'._FINISHEDBANNERS.'</h2>'
            ."<table width=\"100%\" border=\"0\"><tr>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._IMP."</strong></td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._CLICKS."</strong></td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._CLICKSPERCENT."</strong></td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._DATESTARTED."</strong></td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._DATEENDED."</strong></td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._CLIENTNAME."</strong></td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._FUNCTIONS."</strong></td></tr>";

		$sql = "SELECT 	$bannerfinishColumn[bid],
						$bannerfinishColumn[cid],
						$bannerfinishColumn[impressions],
						$bannerfinishColumn[clicks],
						$bannerfinishColumn[datestart],
						$bannerfinishColumn[dateend],
						$bannerclientColumn[name]
				FROM $pntable[bannerfinish],
						$pntable[bannerclient]
				WHERE $bannerfinishColumn[cid] = $bannerclientColumn[cid]
				ORDER BY $bannerfinishColumn[bid]";

         $result =& $dbconn->Execute($sql);

        while(list($bid, $cid, $impressions, $clicks, $datestart, $dateend, $name) = $result->fields) {

            $result->MoveNext();
            // jgm - get and use clientname
            if (pnSecAuthAction(0, 'Banners::Banner', "$clientname::$bid", ACCESS_READ)) {
                $percent = substr(100 * $clicks / $impressions, 0, 5);
                echo "<tr>"
                    ."<td style=\"background-color:$bgcolor2\" align=\"center\">" . pnVarPrepForDisplay($impressions) . "</td>"
                    ."<td style=\"background-color:$bgcolor2\" align=\"center\">" . pnVarPrepForDisplay($clicks) . "</td>"
                    ."<td style=\"background-color:$bgcolor2\" align=\"center\">" . pnVarPrepForDisplay($percent) . "%</td>"
                    ."<td style=\"background-color:$bgcolor2\" align=\"center\">" . pnVarPrepForDisplay($datestart) . "</td>"
                    ."<td style=\"background-color:$bgcolor2\" align=\"center\">" . pnVarPrepForDisplay($dateend) . "</td>"
                    ."<td style=\"background-color:$bgcolor2\" align=\"center\">" . pnVarPrepForDisplay($name) . "</td>";
                if (pnSecAuthAction(0, 'Banners::Banner', "$clientname::$bid", ACCESS_DELETE)) {
                    echo "<td style=\"background-color:$bgcolor2\" align=\"center\"><a href=\"admin.php?module="
                    .$GLOBALS['module']."&amp;op=BannerFinishDelete&amp;bid=$bid&amp;authid=" . pnSecGenAuthKey() . "\">"._DELETE."</a></td>";
                } else {
                    echo "<td style=\"background-color:$bgcolor2\">&nbsp;</td>";
                }
                echo "</tr>";
            }
        }
       echo "</table>";
       CloseTable();
    }

    /* Clients List */
    if (pnSecAuthAction(0, 'Banners::Client', '::', ACCESS_READ)) {
        OpenTable();
        echo '<h2>'._ADVERTISINGCLIENTS.'</h2>'
            ."<table width=\"100%\" border=\"0\"><tr>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._CLIENTNAME."</strong></td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._ACTIVEBANNERS2."</strong></td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._CONTACTNAME."</strong></td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._CONTACTEMAIL."</strong></td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._FUNCTIONS."</strong></td></tr>";

		$sql = "SELECT	$bannerclientColumn[cid],
						$bannerclientColumn[name],
						$bannerclientColumn[contact], 
						$bannerclientColumn[email] 
				FROM $bannerclientTable
				ORDER BY $bannerclientColumn[cid]";

        $result =& $dbconn->Execute($sql);

        while(list($cid, $name, $contact, $email) = $result->fields) {

			$sql = "SELECT COUNT(*) 
					FROM $bannerTable 
					WHERE $bannerColumn[cid]='".(int)pnVarPrepForStore($cid)."'";

            $result2 =& $dbconn->Execute($sql);

            list($numrows) = $result2->fields;
            echo "<tr>"
                ."<td style=\"background-color:$bgcolor2\" align=\"center\">" . pnVarPrepForDisplay($name) . "</td>"
                ."<td style=\"background-color:$bgcolor2\" align=\"center\">" . pnVarPrepForDisplay($numrows) . "</td>"
                ."<td style=\"background-color:$bgcolor2\" align=\"center\">" . pnVarPrepForDisplay($contact) . "</td>"
                ."<td style=\"background-color:$bgcolor2\" align=\"center\">" . pnVarPrepForDisplay($email) . "</td>";
            if (pnSecAuthAction(0, 'Banners::Client', "$name::$cid", ACCESS_EDIT)) {
                echo "<td style=\"background-color:$bgcolor2\" align=\"center\"><a href=\"admin.php?module="
                .$GLOBALS['module']."&amp;op=BannerClientEdit&amp;cid=$cid\">"._EDIT."</a>";
                if (pnSecAuthAction(0, 'Banners::Client', "$name::$cid", ACCESS_DELETE)) {
                    echo " | <a href=\"admin.php?module=".$GLOBALS['module']
                    ."&amp;op=BannerClientDelete&amp;cid=$cid\">"._DELETE."</a></td></tr>";
                } else {
                    echo "</td></tr>";
                }
            } else {
                echo "<td style=\"background-color:$bgcolor2\">&nbsp;</td></tr>";
            }
            $result->MoveNext();
        }
        echo "</table>";
        CloseTable();
    }

    /* Add Banner */
    if (pnSecAuthAction(0, 'Banners::Banner', '::', ACCESS_ADD)) {

		$sql = "SELECT $bannerclientColumn[cid], $bannerclientColumn[name] 
				FROM $bannerclientTable";

        $result =& $dbconn->Execute($sql);

        if(!$result->EOF) {
            OpenTable();
            echo '<h2>'._ADDNEWBANNER.'</h2>'
                .'<form action="admin.php" method="post"><div>'
                ._CLIENTNAME.":"
                ."<select name=\"cid\">";

            while(list($cid, $name) = $result->fields) {
                echo "<option value=\"$cid\">". pnVarPrepForDisplay($name) . "</option>";
                $result->MoveNext();
            }
            echo "</select><br />"
				._PURCHASEDIMPRESSIONS.": <input type=\"text\" name=\"imptotal\" size=\"12\" maxlength=\"11\" /> 0 = "._UNLIMITED.'<br />'
                ._BANTYPE.": <input type=\"text\" name=\"type\" size=\"2\" maxlength=\"2\" /><br />"
                ._IMAGEURL.": <input type=\"text\" name=\"imageurl\" size=\"50\" maxlength=\"250\" /><br />"
                ._CLICKURL.": <input type=\"text\" name=\"clickurl\" size=\"50\" maxlength=\"250\" /><br />"
                ."<input type=\"hidden\" name=\"module\" value=\"".$GLOBALS['module']."\" />"
                ."<input type=\"hidden\" name=\"op\" value=\"BannersAdd\" />"
                .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
                ."<input type=\"submit\" value=\""._ADDBANNER."\" />"
                ."</div></form>";
            CloseTable();
        }
    }

    /* Add Client */
    if (pnSecAuthAction(0, 'Banners::Client', '::', ACCESS_ADD)) {
        OpenTable();
        echo"<form action=\"admin.php\" method=\"post\"><div>
		     <h2>"._ADDCLIENT."</h2>
            "._CLIENTNAME.": <input type=\"text\" name=\"name\" size=\"30\" maxlength=\"60\" /><br />
            "._CONTACTNAME.": <input type=\"text\" name=\"contact\" size=\"30\" maxlength=\"60\" /><br />
            "._CONTACTEMAIL.": <input type=\"text\" name=\"email\" size=\"30\" maxlength=\"60\" /><br />
            "._CLIENTLOGIN.": <input type=\"text\" name=\"login\" size=\"12\" maxlength=\"10\" /><br />
            "._CLIENTPASSWD.": <input type=\"text\" name=\"passwd\" size=\"12\" maxlength=\"10\" /><br />
            "._EXTRAINFO.":<br /><textarea name=\"extrainfo\" cols=\"80\" rows=\"10\"></textarea><br />"
            ."<input type=\"hidden\" name=\"module\" value=\"".$GLOBALS['module']."\" />"
            ."<input type=\"hidden\" name=\"op\" value=\"BannerAddClient\" />"
            .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
            ."<input type=\"submit\" value=\""._ADDCLIENT2."\" />"
            ."</div></form>";
        CloseTable();
    }
/*
// Access Banner Settings
    OpenTable();
    echo '<h2>'._BANNERSCONF.'</h2>';
    echo "<a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=getConfig\">"._BANNERSCONF."</a>";
    CloseTable();
*/
    include ('footer.php');
}

function BannersAdd() {

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $bannerTable = $pntable['banner'];
    $bannerColumn = &$pntable['banner_column'];

    list($name,
         $cid,
         $type,
         $imptotal,
         $imageurl,
         $clickurl,
         $clientname) = pnVarCleanFromInput('name',
			 								'cid',
											'type',
											'imptotal',
											'imageurl',
											'clickurl',
											'clientname');
    // jgm - get and use clientname
    if(!isset($clientname)) {
        $clientname = '';
    }
    if (!(pnSecAuthAction(0, 'Banners::Banner', "$clientname::", ACCESS_ADD))) {
        include 'header.php';
        echo _BANNERSADDBANNERNOAUTH;
        include 'footer.php';
        exit;
    }
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

	$sql = "INSERT INTO $bannerTable SET
		$bannerColumn[cid] = '".(int)pnVarPrepForStore($cid)."',
		$bannerColumn[type] = '".(int)pnVarPrepForStore($type)."',
		$bannerColumn[imptotal] = '".(int)pnVarPrepForStore($imptotal)."',
		$bannerColumn[impmade] = '1',
		$bannerColumn[clicks] = '0', 
		$bannerColumn[imageurl] = '".pnVarPrepForStore($imageurl)."',
		$bannerColumn[clickurl] = '".pnVarPrepForStore($clickurl)."',
		$bannerColumn[date] = now()";

    $result =& $dbconn->Execute($sql);
    if($dbconn->ErrorNo()<>0) {
        error_log("Error: " . $dbconn->ErrorMsg());
    }
    pnRedirect('admin.php?module='.$GLOBALS['module'].'&op=BannersAdmin');
}

function BannerAddClient()
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $bannerclientTable = $pntable['bannerclient'];
    $bannerclientColumn = &$pntable['bannerclient_column'];

    list($name,
         $contact,
         $email,
         $login,
         $passwd,
         $extrainfo) = pnVarCleanFromInput('name',
                                           'contact',
                                           'email',
                                           'login',
                                           'passwd',
                                           'extrainfo');

    if (!(pnSecAuthAction(0, 'Banners::Client', '::', ACCESS_ADD))) {
        include 'header.php';
        echo _BANNERSADDCLIENTNOAUTH;
        include 'footer.php';
        exit;
    }


    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

	$sql = "INSERT INTO $bannerclientTable SET
		$bannerclientColumn[name] = '".pnVarPrepForStore($name)."',
		$bannerclientColumn[contact] = '".pnVarPrepForStore($contact)."',
		$bannerclientColumn[email] = '".pnVarPrepForStore($email)."',
		$bannerclientColumn[login] = '".pnVarPrepForStore($login)."', 
		$bannerclientColumn[passwd] = '".pnVarPrepForStore($passwd)."',
		$bannerclientColumn[extrainfo] = '".pnVarPrepForStore($extrainfo)."'";

    $result =& $dbconn->Execute($sql);

    if($dbconn->ErrorNo()<>0) {
        error_log("Error: " . $dbconn->ErrorMsg());
    }
    pnRedirect('admin.php?module='.$GLOBALS['module'].'&op=BannersAdmin');
}

function BannerFinishDelete()
{    
    $bid = pnVarCleanFromInput('bid');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $bannerTable = $pntable['banner'];
    $bannerColumn = &$pntable['banner_column'];
    $bannerclientTable =  $pntable['bannerclient'];
    $bannerclientColumn =  &$pntable['bannerclient_column'];
    $bannerfinishTable =  $pntable['bannerfinish'];
    $bannerfinishColumn =  &$pntable['bannerfinish_column'];

	$sql = "SELECT $bannerclientColumn[name]
			FROM $bannerTable, $bannerclientTable
			WHERE $bannerColumn[bid] = '" . (int)pnVarPrepForStore($bid) . "'
			AND $bannerColumn[cid] = $bannerclientColumn[cid]";

    $result =& $dbconn->Execute($sql);

    list($clientname) = $result->fields;
    $result->Close();

    if (!pnSecAuthAction(0, 'Banners::Banner', "$clientname::$bid", ACCESS_DELETE)) {
        include 'header.php';
        echo _BANNERSDELBANNERNOAUTH;
        include 'footer.php';
        return;
    }

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

	$sql = "DELETE FROM $bannerfinishTable 
			WHERE $bannerfinishColumn[bid]='".(int)pnVarPrepForStore($bid)."'";

    $result =& $dbconn->Execute($sql);

    if($dbconn->ErrorNo()<>0) {
        error_log("Error: " . $dbconn->ErrorMsg());
    }

    pnRedirect('admin.php?module='.$GLOBALS['module'].'&op=BannersAdmin');
}

function BannerDelete()
{
    list($bid,
         $ok) = pnVarCleanFromInput('bid',
                                    'ok');
    if (!isset($ok)) {
        $ok = 0;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $bannerTable = $pntable['banner'];
    $bannerColumn = &$pntable['banner_column'];
    $bannerclientTable =  $pntable['bannerclient'];
    $bannerclientColumn =  &$pntable['bannerclient_column'];

	$sql = "SELECT $bannerclientColumn[name]
			FROM $bannerTable, $bannerclientTable
			WHERE $bannerColumn[bid] = '" . (int)pnVarPrepForStore($bid) . "'
			AND $bannerColumn[cid] = $bannerclientColumn[cid]";

    $result =& $dbconn->Execute($sql);

    list($clientname) = $result->fields;

    $result->Close();

    if (!pnSecAuthAction(0, 'Banners::Banner', "$clientname::$bid", ACCESS_DELETE)) {
        include 'header.php';
        echo _BANNERSDELBANNERNOAUTH;
        include 'footer.php';
        return;
    }

    if($ok == 1) {
        if (!pnSecConfirmAuthKey()) {
            include 'header.php';
            echo _BADAUTHKEY;
            include 'footer.php';
            exit;
        }

		$sql = "DELETE FROM $bannerTable 
				WHERE $bannerColumn[bid]='".(int)pnVarPrepForStore($bid)."'";

        $result =& $dbconn->Execute($sql);

        if($dbconn->ErrorNo()<>0) {
            error_log("Error: " . $dbconn->ErrorMsg());
        } 

        pnRedirect('admin.php?module='.$GLOBALS['module'].'&op=BannersAdmin');
    } else {
        include('header.php');
    	$bgcolor2 = $GLOBALS['bgcolor2'];
        banners_menu();
		$sql = "SELECT	$bannerColumn[cid],
						$bannerColumn[imptotal], 
						$bannerColumn[impmade], $bannerColumn[clicks], 
						$bannerColumn[imageurl], $bannerColumn[clickurl],
						$bannerclientColumn[name]
				FROM $pntable[banner], $pntable[bannerclient] 
				WHERE $bannerColumn[bid]='".(int)pnVarPrepForStore($bid)."' 
				AND $bannerColumn[cid] = $bannerclientColumn[cid]";

        $result =& $dbconn->Execute($sql);

        list($cid, $imptotal, $impmade, $clicks, $imageurl, $clickurl, $name) = $result->fields;
        OpenTable();
        echo '<div style="text-align:center"><strong>'._DELETEBANNER.'</strong><br />'
            ."<a href=\"$clickurl\"><img src=\"$imageurl\" alt=\"\" /></a><br />"
            ."<a href=\"$clickurl\">$clickurl</a><br />"
            ."<table width=\"100%\" border=\"0\"><tr>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._ID."</strong></td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._IMPRESSIONS."</strong></td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._IMPLEFT."</strong></td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._CLICKS."</strong></td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._CLICKSPERCENT."</strong></td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\"><strong>"._CLIENTNAME."</strong></td></tr>";
        $percent = substr(100 * $clicks / $impmade, 0, 5);
        if($imptotal==0) {
            $left = _UNLIMITED;
        } else {
            $left = $imptotal-$impmade;
        }
        echo "<tr><td style=\"background-color:$bgcolor2\" align=\"center\">" . pnVarPrepForDisplay($bid) . "</td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\">" . pnVarPrepForDisplay($impmade) . "</td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\">" . pnVarPrepForDisplay($left) . "</td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\">" . pnVarPrepForDisplay($clicks) . "</td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\">" . pnVarPrepForDisplay($percent) . "%</td>"
            ."<td style=\"background-color:$bgcolor2\" align=\"center\">" . pnVarPrepForDisplay($name) . "</td></tr>";
        echo "</table><br />"
        ._SURETODELBANNER.'<br />'
        ."[ <a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=BannersAdmin\">"
        ._NO."</a> | <a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=BannerDelete&amp;bid=$bid&amp;ok=1&amp;authid=" . pnSecGenAuthKey() . "\">"
        ._YES."</a> ]</div><br />";
        CloseTable();
        include('footer.php');
    }
}

function BannerEdit($bid) {
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $bannerTable = $pntable['banner'];
    $bannerColumn = &$pntable['banner_column'];
    $bannerclientTable =  $pntable['bannerclient'];
    $bannerclientColumn =  &$pntable['bannerclient_column'];

    include('header.php');
    banners_menu();
    $column = $pntable['banner_column'];
    $column2 = $pntable['bannerclient_column'];

	$sql = "SELECT	$bannerColumn[cid],
					$bannerColumn[type],
					$bannerColumn[imptotal], 
					$bannerColumn[impmade],
					$bannerColumn[clicks],
					$bannerColumn[imageurl],
					$bannerColumn[clickurl],
					$bannerclientColumn[name]
			FROM $pntable[banner], $pntable[bannerclient]
			WHERE $bannerColumn[bid]='".(int)pnVarPrepForStore($bid)."'
			AND $bannerColumn[cid] = $bannerclientColumn[cid]";

    $result =& $dbconn->Execute($sql);

    list($cid, $type, $imptotal, $impmade, $clicks, $imageurl, $clickurl, $name) = $result->fields;


    if (!pnSecAuthAction(0, 'Banners::Banner', "$name::$bid", ACCESS_EDIT)) {
        echo _BANNERSEDITBANNERNOAUTH;
        include 'footer.php';
        return;
    }
    OpenTable();
    echo'<h2>'._EDITBANNER.'</h2>'
        ."<div style=\"text-align:center\"><img src=\"$imageurl\" alt=\"\" /></div><br />"
        .'<form action="admin.php" method="post"><div>'
        ._CLIENTNAME.": "
        ."<select name=\"cid\">";
    echo "<option value=\"$cid\" selected=\"selected\">".pnVarPrepForDisplay($name)."</option>";

	$sql = "SELECT	$bannerclientColumn[cid],
					$bannerclientColumn[name]
			FROM $bannerclientTable";

    $result =& $dbconn->Execute($sql);

    while(list($ccid, $name) = $result->fields) {

        $result->MoveNext();
        if($cid!=$ccid) {
            echo "<option value=\"$ccid\">" . pnVarPrepForDisplay($name) . "</option>";
        }
    }
    echo "</select><br />";
    if($imptotal==0) {
        $impressions = _UNLIMITED;
    } else {
        $impressions = $imptotal;
    }
    echo '<br />'._ADDIMPRESSIONS.": <input type=\"text\" name=\"impadded\" size=\"12\" maxlength=\"11\" /> "._PURCHASED.": <strong>" 
	    . pnVarPrepForDisplay($impressions) . '</strong> '._MADE.": <strong>" . pnVarPrepForDisplay($impmade) . '</strong><br />'
        ._BANTYPE.":<input type=\"text\" name=\"type\" size=\"2\" maxlength=\"2\" value=\"$type\" /><br />"
        ._IMAGEURL.":<input type=\"text\" name=\"imageurl\" size=\"50\" maxlength=\"255\" value=\"$imageurl\" /><br />"
        ._CLICKURL.":<input type=\"text\" name=\"clickurl\" size=\"50\" maxlength=\"255\" value=\"$clickurl\" /><br />"
        ."<input type=\"hidden\" name=\"bid\" value=\"$bid\" />"
        ."<input type=\"hidden\" name=\"imptotal\" value=\"$imptotal\" />"
        ."<input type=\"hidden\" name=\"module\" value=\"".$GLOBALS['module']."\" />"
        ."<input type=\"hidden\" name=\"op\" value=\"BannerChange\" />"
        .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
        ."<input type=\"submit\" value=\""._SAVECHANGES."\" />"
        ."</div></form>";
    CloseTable();
    include('footer.php');
}

function BannerChange() {

    list($bid,
         $cid,
         $type,
         $imptotal,
         $impadded,
         $imageurl,
         $clickurl) = pnVarCleanFromInput('bid',
                                          'cid',
                                          'type',
                                          'imptotal',
                                          'impadded',
                                          'imageurl',
                                          'clickurl');
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $bannerTable = $pntable['banner'];
    $bannerColumn = &$pntable['banner_column'];
    $bannerclientTable =  $pntable['bannerclient'];
    $bannerclientColumn =  &$pntable['bannerclient_column'];

    $imp = $imptotal+$impadded;

    $bannercolumn = &$pntable['banner_column'];
    $bannerclientColumn =  &$pntable['bannerclient_column'];

	$sql = "SELECT $bannerclientColumn[name]
			FROM $bannerTable, $bannerclientTable
			WHERE $bannercolumn[bid] = '" . (int)pnVarPrepForStore($bid) . "'
			AND $bannercolumn[cid] = $bannerclientColumn[cid]";
								
    $result =& $dbconn->Execute($sql);

    list($clientname) = $result->fields;
    $result->Close();

    if (!pnSecAuthAction(0, 'Banners::Banner', "$clientname::$bid", ACCESS_EDIT)) {
        include 'header.php';
        echo _BANNERSEDITBANNERNOAUTH;
        include 'footer.php';
        return;
    }

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

	$sql = "UPDATE $bannerTable 
			SET	$bannercolumn[cid]='".(int)pnVarPrepForStore($cid)."',
				$bannercolumn[type]='".(int)pnVarPrepForStore($type)."',
				$bannercolumn[imptotal]='".(int)pnVarPrepForStore($imp)."', 
				$bannercolumn[imageurl]='".pnVarPrepForStore($imageurl)."', 
				$bannercolumn[clickurl]='".pnVarPrepForStore($clickurl)."' 
			WHERE $bannercolumn[bid]='".(int)pnVarPrepForStore($bid)."'";

    $result =& $dbconn->Execute($sql);

    if($dbconn->ErrorNo()<>0) {
        error_log("Error: " . $dbconn->ErrorMsg());
    } 

    pnRedirect('admin.php?module='.$GLOBALS['module'].'&op=BannersAdmin');
}

function BannerClientDelete()
{
    list($cid,
         $ok) = pnVarCleanFromInput('cid',
                                    'ok');
    if (!isset($ok)) {
        $ok = 0;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $bannerTable = $pntable['banner'];
    $bannerColumn = &$pntable['banner_column'];
    $bannerclientTable =  $pntable['bannerclient'];
    $bannerclientColumn =  &$pntable['bannerclient_column'];

	$sql = "SELECT $bannerclientColumn[name]
			FROM $bannerclientTable
			WHERE $bannerclientColumn[cid] = '".(int)pnVarPrepForStore($cid)."'";

    $result =& $dbconn->Execute($sql);

    list($clientname) = $result->fields;
    $result->Close();

    if (!pnSecAuthAction(0, 'Banners::Client', "$clientname::$cid", ACCESS_DELETE)) {
        include 'header.php';
        echo _BANNERSDELCLIENTNOAUTH;
        include 'footer.php';
        return;
    }

    if ($ok==1) {
        if (!pnSecConfirmAuthKey()) {
            include 'header.php';
            echo _BADAUTHKEY;
            include 'footer.php';
            exit;
        }

		$sql = "DELETE FROM $bannerTable 
				WHERE $bannerColumn[cid]='".(int)pnVarPrepForStore($cid)."'";

        $result =& $dbconn->Execute($sql);

        if($dbconn->ErrorNo()<>0) {
            error_log("Error: " . $dbconn->ErrorMsg());
        }

		$sql = "DELETE FROM $bannerclientTable 
				WHERE $bannerclientColumn[cid]='".(int)pnVarPrepForStore($cid)."'";

        $result =& $dbconn->Execute($sql);

        if($dbconn->ErrorNo()<>0) {
            error_log("Error: " . $dbconn->ErrorMsg());
        }

        pnRedirect('admin.php?module='.$GLOBALS['module'].'&op=BannersAdmin');
    } else {
        include('header.php');
        banners_menu();
		$sql = "SELECT $bannerclientColumn[cid], $bannerclientColumn[name] 
				FROM $bannerclientTable 
				WHERE $bannerclientColumn[cid]='".(int)pnVarPrepForStore($cid)."'";

        $result =& $dbconn->Execute($sql);

        list($cid, $name) = $result->fields;
        OpenTable();
        echo '<div style="text-align:center"><strong>'._DELETECLIENT.": $name</strong><br />
            "._SURETODELCLIENT.'<br />';

		$sql = "SELECT $bannerColumn[imageurl], $bannerColumn[clickurl] 
				FROM $bannerTable 
				WHERE $bannerColumn[cid]='".pnVarPrepForStore($cid)."'";

        $result =& $dbconn->Execute($sql);

        if($result->EOF) {
            echo _CLIENTWITHOUTBANNERS.'<br />';
        } else {
            echo '<strong>'._WARNING."!!!</strong><br />
                "._DELCLIENTHASBANNERS.":<br />";
        }

        while(list($imageurl, $clickurl) = $result->fields) {
            echo "<a href=\"$clickurl\"><img src=\"$imageurl\" alt=\"\" /></a><br />
                <a href=\"$clickurl\">$clickurl</a><br />";
            $result->MoveNext();
        }
        echo _SURETODELCLIENT."<br />
        [ <a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=BannersAdmin\">"
        ._NO."</a> | <a href=\"admin.php?module=".$GLOBALS['module']."&amp;op=BannerClientDelete&amp;cid=$cid&amp;ok=1&amp;authid=" . pnSecGenAuthKey() . "\">"
        ._YES."</a> ]</div>";
        CloseTable();
        include('footer.php');
    }
}

function BannerClientEdit()
{
    list($cid,
    	 $clientname) = pnVarCleanFromInput('cid',
    	 				    'clientname');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $bannerclientTable =  $pntable['bannerclient'];
    $bannerclientColumn =  &$pntable['bannerclient_column'];

    include('header.php');
    banners_menu();
	$sql = "SELECT	$bannerclientColumn[name],
					$bannerclientColumn[contact],
					$bannerclientColumn[email],
					$bannerclientColumn[login],
					$bannerclientColumn[passwd],
					$bannerclientColumn[extrainfo] 
			FROM $bannerclientTable 
			WHERE $bannerclientColumn[cid]='".(int)pnVarPrepForStore($cid)."'";

    $result =& $dbconn->Execute($sql);

    list($name, $contact, $email, $login, $passwd, $extrainfo) = $result->fields;

    if(!isset($clientname)) {
        $clientname = '';
    }
    if (!pnSecAuthAction(0, 'Banners::Client', "$clientname::$cid", ACCESS_EDIT)) {
        echo _BANNERSEDITCLIENTNOAUTH;
        include 'footer.php';
        return;
    }

    OpenTable();
    echo '<h2>'._EDITCLIENT.'</h2>'
        .'<form action="admin.php" method="post"><div>'
        ._CLIENTNAME.": <input type=\"text\" name=\"name\" value=\"$name\" size=\"30\" maxlength=\"60\" /><br />"
        ._CONTACTNAME.": <input type=\"text\" name=\"contact\" value=\"$contact\" size=\"30\" maxlength=\"60\" /><br />"
        ._CONTACTEMAIL.": <input type=\"text\" name=\"email\" size=\"30\" maxlength=\"60\" value=\"$email\" /><br />"
        ._CLIENTLOGIN.": <input type=\"text\" name=\"login\" size=\"12\" maxlength=\"10\" value=\"$login\" /><br />"
        ._CLIENTPASSWD.": <input type=\"text\" name=\"passwd\" size=\"12\" maxlength=\"10\" value=\"$passwd\" /><br />"
        ._EXTRAINFO."<br /><textarea name=\"extrainfo\" cols=\"80\" rows=\"10\">$extrainfo</textarea><br />"
        ."<input type=\"hidden\" name=\"cid\" value=\"$cid\" />"
        ."<input type=\"hidden\" name=\"module\" value=\"".$GLOBALS['module']."\" />"
        ."<input type=\"hidden\" name=\"op\" value=\"BannerClientChange\" />"
        .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
        ."<input type=\"submit\" value=\""._SAVECHANGES."\" />"
        ."</div></form>";
    CloseTable();

    include 'footer.php';
}

function BannerClientChange()
{
    list($cid,
         $name,
         $contact,
         $email,
         $extrainfo,
         $login,
         $passwd) = pnVarCleanFromInput('cid',
                                        'name',
                                        'contact',
                                        'email',
                                        'extrainfo',
                                        'login',
                                        'passwd');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $bannerclientTable =  $pntable['bannerclient'];
    $bannerclientColumn =  &$pntable['bannerclient_column'];

    // NB - authorisation is against *OLD* client name

	$sql = "SELECT $bannerclientColumn[name]
			FROM $bannerclientTable
			WHERE $bannerclientColumn[cid] = '".(int)pnVarPrepForStore($cid)."'";

    $result =& $dbconn->Execute($sql);

    list($clientname) = $result->fields;
    $result->Close();

    if (!pnSecAuthAction(0, 'Banners::Client', "$clientname::$cid", ACCESS_EDIT)) {
        include 'header.php';
        echo _BANNERSEDITCLIENTNOAUTH;
        include 'footer.php';
        return;
    }

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

	$sql = "UPDATE $bannerclientTable 
			SET	$bannerclientColumn[name]='".pnVarPrepForStore($name)."',
				$bannerclientColumn[contact]='".pnVarPrepForStore($contact)."',
				$bannerclientColumn[email]='".pnVarPrepForStore($email)."',
				$bannerclientColumn[extrainfo]='".pnVarPrepForStore($extrainfo)."', 
				$bannerclientColumn[login]='".pnVarPrepForStore($login)."',
				$bannerclientColumn[passwd]='".pnVarPrepForStore($passwd)."'
			WHERE $bannerclientColumn[cid]='".(int)pnVarPrepForStore($cid)."'";

    $result =& $dbconn->Execute($sql);

    if($dbconn->ErrorNo()<>0) {
        error_log("Error: " . $dbconn->ErrorMsg());
    }

    pnRedirect('admin.php?module='.$GLOBALS['module'].'&op=BannersAdmin');
}

function banners_admin_getConfig()
{
    include 'header.php';
    
    if (!(pnSecAuthAction(0, 'Banners::Banner', '::', ACCESS_ADMIN))) {
        echo _BANNERSNOAUTH;
        include 'footer.php';
    }
    
    $bgcolor2 = $GLOBALS["bgcolor2"];

    // prepare vars
    $sel_banners['0'] = '';
    $sel_banners['1'] = '';
    $sel_banners[pnConfigGetVar('banners')] = ' checked="checked"';

    banners_menu();
    OpenTable();
    print '<h2>'._BANNERSCONF.'</h2>'
          .'<form action="admin.php" method="post"><div>'
        .'<table border="0"><tr><td>'
        ._ACTBANNERS.'</td><td>'
        ."<input type=\"radio\" name=\"xbanners\" value=\"1\" ".$sel_banners['1']." />"._YES.' &nbsp;'
        ."<input type=\"radio\" name=\"xbanners\" value=\"0\" ".$sel_banners['0']." />"._NO
        .'</td></tr><tr><td>'
        ._YOURIP.':</td><td>'
        ."<input type=\"text\" name=\"xmyIP\" value=\"".pnConfigGetVar('myIP')."\" size=\"30\" />"
        .'</td></tr></table>'
        ."<input type=\"hidden\" name=\"module\" value=\"".$GLOBALS['module']."\" />"
        ."<input type=\"hidden\" name=\"op\" value=\"setConfig\" />"
        .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
        ."<input type=\"submit\" value=\""._SUBMIT."\" />"
        ."</div></form>";
    CloseTable();

    include 'footer.php';
}

function banners_admin_setConfig($var)
{
    if (!(pnSecAuthAction(0, 'Banners::Banner', '::', ACCESS_ADMIN))) {
        include 'header.php';
        echo _BANNERSNOAUTH;
        include 'footer.php';
    }
    
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    // Escape some characters in these variables.
    // hehe, I like doing this, much cleaner :-)
    $fixvars = array();

    // todo: make FixConfigQuotes global / replace with other function
    foreach ($fixvars as $v) {
	//$var[$v] = FixConfigQuotes($var[$v]);
    }

    // Set any numerical variables that havn't been set, to 0. i.e. paranoia check :-)
    $fixvars = array();

    foreach ($fixvars as $v) {
        if (empty($var[$v])) {
            $var[$v] = 0;
        }
    }

    // all variables starting with x are the config vars.
    while(list ($key, $val) = each ($var)) {
        if (substr($key, 0, 1) == 'x') {
            pnConfigSetVar(substr($key, 1), $val);
        }
    }
    pnRedirect('admin.php?module='.$GLOBALS['module'].'&op=BannersAdmin');
}

function banners_admin_main($var)
{
    list($op,
    	 $bid) = pnVarCleanFromInput('op',
    	 			     'bid');

   extract($var);

    if (!(pnSecAuthAction(0, 'Banners::Banner', '::', ACCESS_ADMIN))) {
        include 'header.php';
        echo _BANNERSNOAUTH;
        include 'footer.php';
    } else {
        switch($op) {

            case "BannersAdmin":
                BannersAdmin();
                break;

            case "BannersAdd":
                BannersAdd();
                break;

            case "BannerAddClient":
                BannerAddClient();
                break;

            case "BannerFinishDelete":
                BannerFinishDelete();
                break;

            case "BannerDelete":
                BannerDelete();
                break;

            case "BannerEdit":
                BannerEdit($bid);
                break;

            case "BannerChange":
                BannerChange();
                break;

            case "BannerClientDelete":
                BannerClientDelete();
                break;

            case "BannerClientEdit":
                BannerClientEdit();
                break;
    
            case "BannerClientChange":
                BannerClientChange();
                break;
            
            case "getConfig":
                 banners_admin_getConfig();
                 break;

            case "setConfig":
                 banners_admin_setConfig($var);
                 break;

            default:
               BannersAdmin();
               break;
       }
   }
}

?>