<?php
// File: $Id: banners.php 15998 2005-03-20 08:23:40Z larsneo $
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

/**
 * Function to display banners in all pages
 */

if (!function_exists('pnInit')) {
    include 'includes/pnAPI.php';
    pnInit();
    // include 'includes/legacy.php';
    // eugenio themeover 20020413
    // pnThemeLoad();
}

/**
 * Load lang file
 */

if(file_exists("language/".pnVarPrepForOS(pnUserGetLang())."/banners.php")) {
    include "language/".pnVarPrepForOS(pnUserGetLang())."/banners.php";
} elseif (file_exists("language/eng/banners.php")) {
    include "language/eng/banners.php";
}

/**
 * Function to redirect the clicks to the
 * correct url and add 1 click
 */

function clickbanner()
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $bid = pnVarCleanFromInput('bid');

    $bannerTable = $pntable['banner'];
    $bannerColumn = &$pntable['banner_column'];

	$sql = "SELECT $bannerColumn[clickurl]
			FROM $bannerTable
			WHERE $bannerColumn[bid]='".(int)pnVarPrepForStore($bid)."'";

    $bresult =& $dbconn->Execute($sql);
	
    list($clickurl) = $bresult->fields;
    $bresult->Close();

	$sql = "UPDATE $bannerTable
			SET $bannerColumn[clicks]=$bannerColumn[clicks]+1
			WHERE $bannerColumn[bid]='".(int)pnVarPrepForStore($bid)."'";

    $dbconn->Execute($sql);
    Header('HTTP/1.1 301 Moved Permanently'); 
    Header("Location: $clickurl");
}

/* All of the crap below needs to be moved to a user module */

function clientlogin()
{
    include 'header.php';

    OpenTable();
    echo"<div style=\"text-align:center\">\n"
        ."<h2>"._BAN_ADVSTATS."</h2><br /><br />\n"
        ."<form action=\"banners.php\" method=\"post\">\n"
        ._BAN_LOGIN." <input type=\"text\" name=\"login\" size=\"12\" maxlength=\"10\" /><br />\n"
        ._BAN_PASSWORD." <input type=\"password\" name=\"pass\" size=\"12\" maxlength=\"10\" /><br />\n"
        ."<input type=\"hidden\" name=\"op\" value=\"Ok\" /><br />\n"
        ."<input type=\"submit\" value=\""._BAN_LOGIN."\" />\n"
        ."</form>\n</div>\n";
    CloseTable();

    include 'footer.php';
}

/**
 * Function to display the banners stats for
 * each client
 */

function bannerstats()
{
    list($login,
         $pass) = pnVarCleanFromInput('login',
				                      'pass');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $bannerTable = $pntable['banner'];
    $bannerColumn = &$pntable['banner_column'];
    $bannerclientTable = $pntable['bannerclient'];
    $bannerclientColumn = &$pntable['bannerclient_column'];

    $sitename = pnConfigGetVar('sitename');

	$sql = "SELECT	$bannerclientColumn[cid],
					$bannerclientColumn[name],
					$bannerclientColumn[passwd]
			FROM $bannerclientTable
			WHERE $bannerclientColumn[login]='".pnVarPrepForStore($login)."'";

    $result =& $dbconn->Execute($sql);

    list($cid, $name, $passwd) = $result->fields;
    $result->Close();

    if ($login == "" AND $pass == "" OR $pass == "") {
        include 'header.php';
        echo "<div style=\"text-align:center\"><br />"._BAN_LOGININCORR."<br /><br /><a href=\"javascript:history.go(-1)\">"._BAN_BACK."</a></div>";
        include 'footer.php';
    } else {
        if ($pass==$passwd) {
            include 'header.php';
            OpenTable();
             echo "<div style=\"text-align:center\">"
			    ."<h2>"
                ._BAN_CURRACTIVE." ".pnVarPrepForDisplay($name)."."
                ."<br />"
                ."</h2>"
                ."</div>"
                ."<table width=\"100%\" border=\"0\"><tr>"
                ."<td align=\"center\"><h2>"._BAN_ID."</h2></td>"
                ."<td align=\"center\"><h2>"._BAN_IMP_MADE."</h2></td>"
                ."<td align=\"center\"><h2>"._BAN_IMP_TOTAL."</h2></td>"
                ."<td align=\"center\"><h2>"._BAN_IMP_LEFT."</h2></td>"
                ."<td align=\"center\"><h2>"._BAN_CLICKS."</h2></td>"
                ."<td align=\"center\"><h2>"._BAN_PERCENTCLICKS."</h2></td>"
                ."<td align=\"center\"><h2>"._BAN_FUNCTIONS."</h2></td></tr>";

			$sql = "SELECT	$bannerColumn[bid],
							$bannerColumn[imptotal],
							$bannerColumn[impmade],
							$bannerColumn[clicks],
							$bannerColumn[date]
					FROM $bannerTable
					WHERE $bannerColumn[cid]='".(int)pnVarPrepForStore($cid)."'";

            $result =& $dbconn->Execute($sql);

            while(list($bid, $imptotal, $impmade, $clicks, $date) = $result->fields) {

                $result->MoveNext();
                if($impmade == 0) {
                    $percent = 0;
                } else {
                    $percent = substr(100 * $clicks / $impmade, 0, 5);
                }

                if($imptotal==0) {
                    $left = _BAN_UNLIMITED;
                } else {
                    $left = $imptotal-$impmade;
                }

                echo "<tr><td align=\"center\">".pnVarPrepForDisplay($bid)."</td>"
                    ."<td align=\"center\">".pnVarPrepForDisplay($impmade)."</td>"
                    ."<td align=\"center\">".pnVarPrepForDisplay($imptotal)."</td>"
                    ."<td align=\"center\">".pnVarPrepForDisplay($left)."</td>"
                    ."<td align=\"center\">".pnVarPrepForDisplay($clicks)."</td>"
                    ."<td align=\"center\">".pnVarPrepForDisplay($percent)."%</td>"
                    ."<td align=\"center\"><a href=\"banners.php?op=EmailStats&amp;login=$login&amp;cid=$cid&amp;bid=$bid&amp;pass=$pass\">"._BAN_EMAIL_STATS."</a></td></tr>";
            }

            echo "</table>";

            CloseTable();
            OpenTable();

            echo '<br /><br />'
                .''._BAN_ONYOURSITE.''
                .' '.pnVarPrepForDisplay($sitename).'<br /><br />';

			$sql = "SELECT	$bannerColumn[bid],
							$bannerColumn[imageurl],
							$bannerColumn[clickurl]
					FROM $bannerTable
					WHERE $bannerColumn[cid]='".(int)pnVarPrepForStore($cid)."'";

            $result =& $dbconn->Execute($sql);

            $foundrecs = !$result->EOF;

            while(list($bid, $imageurl, $clickurl) = $result->fields) {
                if ($foundrecs) {
                    echo "<hr /><br />";
                }

                echo "<img src=\"$imageurl\" alt=\"\" /><br />"
                    ."<div>"._BAN_ID.": ".pnVarPrepForDisplay($bid)."<br />"
                    ._BAN_SEND." <a href=\"banners.php?op=EmailStats&amp;login=$login&amp;cid=$cid&amp;bid=$bid\">"._BAN_EMAIL_STATS."</a> "._BAN_FORTHIS."<br />"
                    ." <a href=\"$clickurl\">"._BAN_THISURL."</a><br />"
                    ."<form action=\"banners.php\" method=\"post\"><div>"
                    ._BAN_CHANGEURL.": <input type=\"text\" name=\"url\" size=\"50\" maxlength=\"200\" value=\"$clickurl\" />"
                    ."<input type=\"hidden\" name=\"login\" value=\"$login\" />"
                    ."<input type=\"hidden\" name=\"bid\" value=\"$bid\" />"
                    ."<input type=\"hidden\" name=\"pass\" value=\"$pass\" />"
                    ."<input type=\"hidden\" name=\"cid\" value=\"$cid\" />"
                    ."<input type=\"submit\" name=\"op\" value=\"Change\" /></div></form></div>";
                $result->MoveNext();
            }

            CloseTable();

            include 'footer.php';

        } else {
            include 'header.php';
            echo "<div style=\"text-align:center\">"
                ."<br />"._BAN_LOGININCORR."<br /><br /><a href=\"javascript:history.go(-1)\">"._BAN_BACK."</a>"
                ."</div>";
            include 'footer.php';
        }
    }
}

/**
 * Let the client email his
 * banner statistics
 */

function EmailStats()
{
    list($login,
	     $cid,
	     $bid,
	     $pass) = pnVarCleanFromInput('login',
			                	      'cid',
			                	      'bid',
				                      'pass');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $bannerTable = $pntable['banner'];
    $bannerColumn = &$pntable['banner_column'];
    $bannerclientTable = $pntable['bannerclient'];
    $bannerclientColumn = &$pntable['bannerclient_column'];

	$sql = "SELECT $bannerclientColumn[name], $bannerclientColumn[email]
			FROM $bannerclientTable
			WHERE $bannerclientColumn[cid]='".(int)pnVarPrepForStore($cid)."'";

    $result2 =& $dbconn->Execute($sql);

    list($name, $email) = $result2->fields;

    if ($email == "") {
        include 'header.php';
        OpenTable();
        echo _BAN_STATSFORBAN;
        echo pnVarPrepForDisplay($bid);
        echo _BAN_CANTSEND." ".pnVarPrepForDisplay($name)."<br />"
            ._BAN_CONTACTADMIN."<br /><br />"
            ."<a href=\"javascript:history.go(-1)\">"._BAN_BACK."</a>";
        CloseTable();

        include 'footer.php';

    } else {
		$sql = "SELECT	$bannerColumn[bid],
						$bannerColumn[imptotal],
						$bannerColumn[impmade],
						$bannerColumn[clicks],
						$bannerColumn[imageurl],
						$bannerColumn[clickurl],
						$bannerColumn[date]
				FROM $bannerTable
				WHERE $bannerColumn[bid]='".(int)pnVarPrepForStore($bid)."'
				AND $bannerColumn[cid]='".(int)pnVarPrepForStore($cid)."'";

        $result =& $dbconn->Execute($sql);

        list($bid, $imptotal, $impmade, $clicks, $imageurl, $clickurl, $date) = $result->fields;

        if ($impmade == 0) {
            $percent = 0;
        } else {
            $percent = substr(100 * $clicks / $impmade, 0, 5);
        }

        if ($imptotal == 0) {
            $left =_BAN_UNLIMITED;
            $imptotal = _BAN_UNLIMITED;
        } else {
            $left = $imptotal-$impmade;
        }
        $sitename = pnConfigGetVar('sitename');
        $fecha = date("F jS Y, h:iA.");
        $subject = ""._BAN_YOURSTATS." $sitename";
        $message = ""._BAN_FORMAIL." $sitename:\n\n\n"._BAN_CLIENTNAME.": $name\n"._BAN_ID.": $bid\n"._BAN_IMAGE.": $imageurl\n"._BAN_URL.": $clickurl\n\n"._BAN_IMPPURCHASED.": $imptotal\n"._BAN_IMP_MADE.": $impmade\n"._BAN_IMP_LEFT.": $left\n"._BAN_CLICKS.": $clicks\n"._BAN_PERCENTCLICKS.": $percent%\n\n\n"._BAN_REPORTMADEON.": $fecha";
        $from = "$sitename";
        pnMail($email, $subject, $message, ""._BAN_FROM.": $from\nX-Mailer: PHP/" . phpversion());

        include 'header.php';
        OpenTable();
        echo _BAN_STATSFORBAN." ".pnVarPrepForDisplay($bid)." "._BAN_SENTTO."<br />"
            ."<em>".pnVarPrepForDisplay($email)."</em> for ".pnVarPrepForDisplay($name)."<br /><br />"
            ."<a href=\"javascript:history.go(-1)\">"._BAN_BACK."</a>";
        CloseTable();
        include 'footer.php';
    }
}

/**
 * Let the client to change the
 * url for his banner
 */

function change_banner_url_by_client()
{
    list($login,
    	 $pass,
    	 $cid,
    	 $bid,
    	 $url) = pnVarCleanFromInput('login',
			                	     'pass',
			                	     'cid',
			                	     'bid',
			                	     'url');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $bannerTable = $pntable['banner'];
    $bannerColumn = &$pntable['banner_column'];
    $bannerclientTable = $pntable['bannerclient'];
    $bannerclientColumn = &$pntable['bannerclient_column'];

	$sql = "SELECT $bannerclientColumn[passwd]
		FROM $bannerclientTable
		WHERE $bannerclientColumn[cid]='".(int)pnVarPrepForStore($cid)."'";

    $result =& $dbconn->Execute($sql);

    list($passwd) = $result->fields;
    $result->Close();

    if (!empty($pass) && $pass == $passwd) {
		$sql = "UPDATE $bannerTable
		SET $bannerColumn[clickurl]='".pnVarPrepForStore($url)."'
		WHERE $bannerColumn[bid]='".(int)pnVarPrepForStore($bid)."'";

        $dbconn->Execute($sql);

        include 'header.php';
        OpenTable();
        echo "<br />"._BAN_URLCHANGED."<br /><br /><a href=\"javascript:history.go(-1)\">"._BAN_BACK."</a>";
        CloseTable();
        include 'footer.php';
    } else {
        include 'header.php';
        OpenTable();
        echo "<br />"._BAN_BADLOGINPASS."<br /><br />"._BAN_PLEASE."<a href=\"banners.php?op=login\">"._BAN_LOGINAGAIN.".</a>";
        CloseTable();
        include 'footer.php';
    }
}

$op = pnVarCleanFromInput('op');

switch($op) {

    case "click":
        clickbanner();
        break;

    case "login":
        clientlogin();
        break;

    case "Ok":
        bannerstats();
        break;

    case "Change":
        change_banner_url_by_client();
        break;

    case "EmailStats":
        EmailStats();
        break;

    default:
        clientlogin();
        break;
}

?>