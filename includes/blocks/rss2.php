<?php
// File: $Id: rss2.php 15630 2005-02-04 06:35:42Z jorg $
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
// Based on the work of: Patrick Kellum (initial setup)
//
// Purpose of file: Retrive and display RSS feeds from other websites
// ----------------------------------------------------------------------
// Author: Pim van der Zwet (pim@zwet.com)

// This module uses code from PHP Classroom
// http://phpclassroom.nexen.net/php/code.php3?url=../rss/smartrdf.inc.php3

//
// RSS Status indication
// In the title of the rss2 block there are 3 characters that show the status
// of the rss file.
//
//     +    Cached        - You got a cached version of the headlines.
//    *    Fresh        - The rdf file was just renewed
//    !    Error        - An error occurred while retrieving the rdf file

if (strpos($_SERVER['PHP_SELF'], 'rss2.php')) {
	die ("You can't access this file directly...");
}

$blocks_modules['rss2'] = array(
    'func_display'     => 'blocks_rss2_block',
    'func_add'         => 'blocks_rss2_test',
    'func_update'     => 'blocks_rss2_update',
    'func_edit'     => 'blocks_rss2_select',
    'text_type'     => 'RSS2',
    'text_type_long'     => 'RSS Extra',
    'allow_multiple'     => true,
    'form_content'     => false,
    'form_refresh'     => true,
    'show_preview'     => true
);

// Uses same security schema as rss block

function blocks_rss2_block($row) {
    if (!pnSecAuthAction(0, 'RSSblock::', "$row[title]::", ACCESS_READ)) {
        return;
    }
    advheadlines2($row);
}

function blocks_rss2_update($row) {
    $pntable =& pnDBGetTables();
    if($row['rssurl']) {
        $row['url'] = $row['rssurl'];
        blocks_rss2_test($row);
        if(empty($row['title'])) {
            $column = &$pntable['headlines_column'];
            $site_result =& $dbconn->Execute("SELECT $column[sitename] FROM $pntable[headlines] WHERE $column[rssurl]='$row[url]'");
            $row2 = $site_result->fields;
            $row['title'] = $row2['sitename'];
        }
    } else {
        blocks_rss2_test($row);
        $vars = array('sitename'=>$row['title'],'rssurl'=>$row['url']);
        //HeadlinesAdd($vars);
    }
    return $row;
}

function blocks_rss2_test($row) {
    if(!ereg('http://' , $row['url'])) {
        $row['url'] = 'http://' . $row['url'];
    }
    if($row['url'] == 'http://') {
        $row['url'] = '';
    }
    return $row;
}

function drop_downlist($arr) {

}


function blocks_rss2_select($row)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $output =
    '<tr><td>RSS File URL:</td><td>'
    ."<input type=\"text\" name=\"url\" size=\"\" maxlength=\"\" value=\"$row[url]\">"
    .'&nbsp;&nbsp;<select name="rssurl" size="1">';

    if ($row['url']) {
        $output.= "<option value=\"\" selected=\"selected\">"._CURRENT."<--- Current</option>";
    } else {
        $output .= "<option value=\"\" selected=\"selected\">"._CUSTOM."</option>";
    }

    $column = &$pntable['headlines_column'];
    $res2 =& $dbconn->Execute("SELECT $column[rssurl] as rssurl, $column[sitename] as sitename FROM $pntable[headlines] ORDER BY $column[sitename],$column[id]");
    while(!$res2->EOF) {
        $row2 = $res2->GetRowAssoc(false);
        $res2->MoveNext();
        $output .= "<option value=\"$row2[rssurl]\">$row2[sitename]</option>\n";
    }
    $output .= "</select></td></tr>";

    return $output;
}

function advheadlines2($row)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $past = time() - $row['refresh'];
    if($row['unix_update'] < $past && $row['url']) {
//    if(true) {        // remove comment for testing purposes

 	   // read proxy settings from database
        $column =&$pntable['headlines_column'];
		$result =& $dbconn->Execute("SELECT $column[options] as options, $column[maxrows] as maxrows FROM $pntable[headlines] WHERE $column[rssurl]='$row[url]'");
		$setting = $result->GetRowAssoc(false);
		$result->MoveNext();

		// Check if the 'P'roxy parameter is set in the options for this url.
		$use_proxy = (stristr($setting['options'],"P") != false);
	
		// connect to the rss host
		$fp = rss_connect($row, $use_proxy);
	    if($fp) {

    	    $rdf = "";

			// skip header improves regexp performance (TRICKY CODE!)
			while (!feof($fp) && (fgets($fp, 128) != "\r\n")) {; }

			// start reading after the header
			while (!feof($fp)) {
				// read fixed blocks of data
				$rdf[] .= fgets($fp, 128);
			}
			// nicely close the connection
			fputs($fp, "Connection: close\r\n\r\n");
			fclose($fp);

			// for timing purposes
			$starttime = getmicrotime();
	
			// 'parse' the rdf file
			$html = parse_rdf2html($rdf, $setting['maxrows'], $setting['options']);
	
			// build the contents of the display block
			$row['hdr_comment'] = "\n<!-- RSS Block start -->\n";
			$row['content'] = $html;
			$time = getmicrotime() - $starttime;
			$row['ftr_comment'] .= "<-- RSS2 Block end (age: $age of $row[refresh])[$stat]  -- runtime: $time -->\n\n";
	
			// update block in db
			$sql_content = addslashes($row['content']);
			$column =&$pntable['blocks_column'];
			$sql = "UPDATE $pntable[blocks] SET $column[content]='$sql_content',$column[last_update]=NOW() WHERE $column[bid]=$row[bid]";
            $result =& $dbconn->Execute($sql);
	        if($dbconn->ErrorNo()<>0) {
				$row['title'] .= ' *';
				$row['content'] .= "<!--\n\n\n".$dbconn->ErrorMsg()."\n\n\n$sql\n\n\n-->";
				exit(0);
	        }
    	} else {
			// no connection could be established!
	
			$content = addslashes(_RSSPROBLEM);
			$next_try = time() + 600;
				$column =&$pntable['blocks_column'];
			$result =& $dbconn->Execute("UPDATE $pntable[blocks] SET $column[content]='$content',$column[last_update]=FROM_UNIXTIME($next_try) WHERE $column[bid]=$bid");
			$row['title'] = "$row[title] !";
			$row['content'] = "$row[content]\n\n\n<!--\n\n\n\n\n\n\n".ml_ftime(_DATETIMELONG,$row['unix_update'])."\n\n\n\n\n-->\n\n\n\n";
		}

    } else { // end if
	    $row['title'] = $row['title']." +"; // chached version indicator
    }

    return themesideblock($row);

}

function getmicrotime() 
{
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}

function rss_connect(&$row, $use_proxy) 
{
    $pntable =& pnDBGetTables();
    $proxy = pnUserGetVar('proxy');

    // build HTTP request header
    $str  = "";
    $str .= "GET $row[url] HTTP/1.0 \r\n";

    // if we need to use a proxy, we must build the HTTP header
    if($proxy['host'] != "" && $proxy['port'] != "" && $use_proxy && $proxy['enable']) {

        // copy host information from proxy settings
        // echo "Connecting to proxyserver ";
        $host = $proxy['host'];
        $port = $proxy['port'];
        $conn_type = "proxy ";  // used for debugging and errors only

        // add some extra header lines  if PROXY authentication is required
        if($proxy['name'] != "" && $proxy['pass'] != "") {
            $str.= "Proxy-Authorization: Basic ";
            $str.= base64_encode($proxy['name'].":".$proxy['pass']) ."\r\n";
	        $row['conn_comment'] .= " \n<!-- via Authenticating Proxy $proxy[name]:$proxy[pass]@$proxy[host]:$proxy[port] -->";
        } else {
	        $row['conn_comment'] .= " \n<!-- via Proxy $proxy[host]:$proxy[port] -->";
    	}
    } else {
        $u = parse_url($row['url']);
        $u['port'] = ($u['port'])?$u['port']:80;

        // copy the remote host information in case of no proxy
        if($this->_debug) echo "Connecting to host ";
        $host = $u['host'];
        $port = $u['port'];
        $conn_type = "remote ";         // used for debugging and errors only

        // add some extra header info if SITE authentication is required
        if($uname != "" && $pass != "") {
            $str.= "Authorization: Basic ";
            $str.= base64_encode($set['uname'].":".$set['pass']) ."\r\n";
	        $row['conn_comment'] .= " \n<!-- using authentication $set[uname]:$set[pass] -->";
        }
    }

    // End with empty line according to protocol.
    $str.="\r\n";

    // open the connection to the remote host, or the proxy
    $fp = fsockopen($host, $port, $errno, $errstr, 2);
    if(!$fp) {
    	return false;
    } else {
    // write the request
    	fputs($fp, $str);
    	return $fp;
    }
}

function parse_rdf2html($rdf, $linemax=10, $options="") 
{
    // options are 'S' search, 'D' description, 'I' image
    $a = $rdf;

    if(!is_array($a))  return;
    if(count($a) == 0) return;

    reset ($a); //
	//    if ("%" == $a[0][0]) {
	//      return $this->backend2h($a);
	//    }
    // channel stuff
    $channel_beg = "<channel>";
    $channel_end1 = "</channel>|<image>|<item>";
    $channel_tit = "<title>";
    $channel_url = "<link>";

    // image stuff
    $image_beg = "<image>";
    $image_end = "</image>";
    $image_src = "<url>";
    $image_url = "<link>";
    $image_tit = "<title>";

    // element stuff
    // ces elements repondent a 4 structures differentes d'elements
    $element_beg = "<item>|<story>|<text>";  // <story> for slashdot
    $element_end = "</item>|</story>|</text>";
    $channel_end = "</rss>|</rdf:RDF>|</backslash>";
    $element_tit = "<title>";
    $element_dat = "<time>";
    $element_des = "<(/)*description>";
    $element_url = "<link>|<url>";

    // text input stuff
    $text_beg = "<textinput>";
    $text_end = "</textinput>";
    $text_sub = "<title>";       // le submit
    $text_lib = "<description>"; // le libelle
    $text_inp = "<name>";        // le nom de la zone de saisie
    $text_url = "<link>";

    // search for start channel section
    reset ($a); //
    while (list (, $line) = each($a)) { // ligne 1
    if (ereg($channel_end1,  $line)) break;     // it's the end
    if (!ereg($channel_beg, $line)) continue;

    while (list (, $line) = each($a)) {
        $line = trim(chop($line));
        if ($line == "") continue;
        if ($line[0] != "<") continue;
        if (ereg($channel_end1, $line)) break;
        switch (true) {
			case ereg($channel_url, $line) :
				$channelurl = trim(strip_tags($line));
				break;
			case ereg($channel_tit, $line) :
				$channeltit = trim(strip_tags($line));
				break;
			}
		}
    } //=======end of channel========


    // search for start image section
    reset ($a); //
    while (list (, $line) = each($a)) { // ligne 1
		$line = trim(chop($line));
		if (ereg($image_end,  $line)) break;     // it's the end
		if (!ereg($image_beg, $line)) continue;

		while (list (, $line) = each($a)) {
			$line = trim(chop($line));
			if ($line == "") continue;
			if ($line[0] != "<") continue;
			if (ereg($image_end, $line)) break;
			switch (true) {
				case ereg($image_url, $line) :
					$imgurl = trim(strip_tags($line));
					break;
				case ereg($image_src, $line) :
					$imgsrc = trim(strip_tags($line));
					break;
				case ereg($image_tit, $line) :
					$imgalt = trim(strip_tags($line));
					break;
			}
		}
    } //=======end of image========

    if (!$imgsrc) {
		// on pose un titre quelconque pour commencer
		// $titles = array("News","Comment");
		$titles = "News";
		$h = $titles;
    }

    // recherche du debut d'un item
    reset ($a);
	$h = '<ul>';
    while (list (, $line) = each($a)) { // ligne 1
		if (ereg($channel_end,  $line)) break;     // it's the end
		if (ereg($element_end,  $line)) continue;
		if (!ereg($element_beg, $line)) continue;
	
		if ($end) break;     // it's the end
	
		// reculer d'une ligne pour des formats RSS 0.90 comme chez mozilla
		//<item><title>MathML International Conference 2000</title>
		prev($a);
		while (list (, $line) = each($a)) {

			$line = trim(chop($line));
			if ($line == "") continue;
			if ($line[0] != "<") continue;
			//if (ereg($element_end, $line)) break;

	        switch (true) {
				case ereg($element_url, $line) :
					$url = trim(strip_tags($line));
					break;
				case ereg($element_tit, $line) :
					$title = trim(strip_tags($line));
					// 23/01/01 devshed utilise des attributs <em> dans la partie titres
					$title = ereg_replace("&lt;", "<", $title);
					$title = ereg_replace("&gt;", ">", $title);
					break;
				case ereg($element_des, $line) :
					// chez o'reilly, le tag description contient des <a...
					// mais passes en htmlentities()
					// il faut donc les reveiller
					//$des = trim(strip_tags($line));
					if (trim(strip_tags($line))) {
					$des = trim(strip_tags($line));
					$des = ereg_replace($element_des, "", $des);
					$des = ereg_replace("&lt;a", "<strong><a", $des);
					$des = ereg_replace("a&gt;", "a></strong>", $des);
					$des = ereg_replace("&lt;", "<", $des);
					$des = ereg_replace("&gt;", ">", $des);
					}
					break;
				case ereg($element_dat, $line) :
					$dat = trim(strip_tags($line));
					break;
			}

			// il faut tester a la fin a cause de mozilla
			// <link>http://home.netscape.com/browsers/6/index.html</link></item>
			if (ereg($element_end, $line)) break;
			// on a tous les elements pour une ligne
		}

	    $ligne = " <li><a href=\"" . $url . "\">" . $title . "</a></li>\n";

    	$linenum++;
	    if ($linenum > $linemax) {
			$end = true;
			break;
		}

	    switch (true) {
			case $dat:
				//$h[] = array($dat, $ligne);
				$h .= $dat.$ligne;
				if (!$imgsrc) $titles = array("Date","News");
				break;
			case $des:
				//$h[] = array($ligne, $des);
				$h .= $ligne;
				if (!$imgsrc) $titles = array("News","Comment");
				if(stristr($options,"D")) {
				$h .= "<br />$des";
				}
				break;
			case !$des:
				//$h[] = array($ligne);
				$h .= $ligne;
				if (!$imgsrc) $titles = array("News");
					break;
				}
		}

		// recherche du debut de la section textinput
	
		// probleme avec
		// <link>http://www.phpinfo.net/?p=chercher</link>
		reset ($a); //
		while (list (, $line) = each($a)) { // ligne 1
		$line = trim(chop($line));
		if (ereg($text_end,  $line)) break;     // it's the end
		if (!ereg($text_beg, $line)) continue;

		while (list (, $line) = each($a)) {
			$line = trim(chop($line));
			if ($line == "") continue;
			if ($line[0] != "<") continue;
			if (ereg($text_end, $line)) break;
			switch (true) {
				case ereg($text_inp, $line) :
					$input = trim(strip_tags($line));
					break;
				case ereg($text_url, $line) :
					$cgi = trim(strip_tags($line));
					break;
				case ereg($text_lib, $line) :
					$lib = trim(strip_tags($line));
					break;
				case ereg($text_sub, $line) :
					$sub = trim(strip_tags($line));
					break;
			}
		}
    } //=======end of text input========
	$h .= '</ul>';

    if ($imgsrc && (stristr($options,"I"))) {
		$h['titles'] = '';
		$header = "<div style=\"text-align:center\">" . '<a href="' . $imgurl . '">' .
			  '<img src="' . $imgsrc . '" alt="' . $imgalt . ' />' . '</a></div>';
    }
    if ($cgi && (stristr($options,"S"))) {
		// voir si url de la forme
		// http://www.phpinfo.net/?p=chercher
		$c = parse_url($cgi);
	    if ($c['query']) {
			// on recupere p=chercher
			$c1 = explode("&", $c['query']);
			//'<input type="hidden" name="id" value="' . $k . '">'
			$hidden = "";
	        while (list (, $arg) = each($c1)) {
    		    $hidden .= ereg_replace("(.*)=(.*)", "<input type=\"hidden\" name=\"\\1\" value=\"\\2\" />", $arg);
	        }
    	}
	    $footer = "<form  method=\"get\" action=\"" . $cgi ."\"  target=\"other\">" . $lib .
				'&nbsp;<input type="text" name="' . $input . '" size="10" maxlength="10" /><br />' .
				'<input type="submit"  value="' . $sub . '">' .
				$hidden .
				"</form>";
			    /*name="Submit"*/
    }
    return $header.$h.$footer;
}

?>