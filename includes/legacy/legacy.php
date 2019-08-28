<?php
// File: $Id: legacy.php 15668 2005-02-07 13:11:33Z markwest $
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
// Purpose of file: Legacy code still around for various old modules
// ----------------------------------------------------------------------

if (strpos($_SERVER['PHP_SELF'], 'legacy.php')) {
    die ("You can't access this file directly...");
}

// Recreate $pnconfig['nukeurl']
global $pnconfig;
$pnconfig['nukeurl'] = pnGetBaseURI();

global $mainfile;
$mainfile = 1;
function delQuotes($string){
    // No recursive function to add quote to an HTML tag if needed
    // and delete duplicate spaces between attribs.
    $tmp="";    # string buffer
    $result=""; # result string
    $i=0;
    $attrib=-1; # Are us in an HTML attrib ?   -1: no attrib   0: name of the attrib   1: value of the atrib
    $quote=0;   # Is a string quote delimited opened ? 0=no, 1=yes
    $len = strlen($string);
    while ($i<$len) {
    switch($string[$i]) { # What car is it in the buffer ?
        case "\"": #"       # a quote.
        if ($quote==0) {
            $quote=1;
        } else {
            $quote=0;
            if (($attrib>0) && ($tmp != "")) { $result .= "=\"$tmp\""; }
            $tmp="";
            $attrib=-1;
        }
        break;
        case "=":           # an equal - attrib delimiter
        if ($quote==0) {  # Is it found in a string ?
            $attrib=1;
            if ($tmp!="") $result.=" $tmp";
            $tmp="";
        } else $tmp .= '=';
        break;
        case " ":           # a blank ?
        if ($attrib>0) {  # add it to the string, if one opened.
            $tmp .= $string[$i];
        }
        break;
        default:            # Other
        if ($attrib<0)    # If we weren't in an attrib, set attrib to 0
        $attrib=0;
        $tmp .= $string[$i];
        break;
    }
    $i++;
    }
    if (($quote!=0) && ($tmp != "")) {
    if ($attrib==1) $result .= "=";
    /* If it is the value of an atrib, add the '=' */
    $result .= "\"$tmp\"";  /* Add quote if needed (the reason of the function ;-) */
    }
    return $result;
}

/**
 * Fixes quoting on a string
 *
 * This function replaces all single single quotes with double single quotes
 * (' becomes '') and all occurrences of \' with '.
 *
 * @param $what string The string to be fixed
 * @return string The fixed string
 * @author ?
 */

function FixQuotes ($what = "") {
    $what = ereg_replace("'","''",$what);
    while (eregi("\\\\'", $what)) {
        $what = ereg_replace("\\\\'","'",$what);
    }
    return $what;
}

function check_html ($str, $strip = '') {
    // The core of this code has been lifted from phpslash
    // which is licenced under the GPL.

    $AllowableHTML = pnConfigGetVar('AllowableHTML');
    
    if ($strip == "nohtml")
        $AllowableHTML=array('');
    $str = stripslashes($str);
    $str = eregi_replace("<[[:space:]]*([^>]*)[[:space:]]*>",
                         '<\\1>', $str);
// Delete all spaces from html tags .
    $str = eregi_replace("<a[^>]*href[[:space:]]*=[[:space:]]*\"?[[:space:]]*([^\" >]*)[[:space:]]*\"?[^>]*>",
                         '<a href="\\1">', $str); # "
// Delete all attribs from Anchor, except an href, double quoted.
    $tmp = "";
    while (ereg("<(/?[[:alpha:]]*)[[:space:]]*([^>]*)>",$str,$reg)) {
        $i = strpos($str,$reg[0]);
        $l = strlen($reg[0]);
        if ($reg[1][0] == "/") $tag = strtolower(substr($reg[1],1));
        else $tag = strtolower($reg[1]);
        if (isset($AllowableHTML[$tag])) {
            if ($a=$AllowableHTML[$tag])
            if ($reg[1][0] == "/") $tag = "</$tag>";
            elseif (($a == 1) || ($reg[2] == "")) $tag = "<$tag>";
            else {
              # Place here the double quote fix function.
              $attrb_list=delQuotes($reg[2]);
              $tag = "<$tag" . $attrb_list . ">";
            } # Attribs in tag allowed
        } else $tag = "";
        $tmp .= substr($str,0,$i) . $tag;
        $str = substr($str,$i+$l);
    }
    $str = $tmp . $str;
    return $str;
    exit;
    // Squash PHP tags unconditionally
    $str = ereg_replace("<\?","",$str);
    return $str;
}

function filter_text($Message, $strip="") {
    global $EditedMessage;
    check_words($Message);
    $EditedMessage=check_html($EditedMessage, $strip);
    return ($EditedMessage);
}

/**
 * formatting stories
 */

function formatTimestamp($time) {
    global $datetime;
    
    // Below ereg commented out 07-08-2001:Alarion - less strict ereg thanks to "Joe"
    //ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime);
    ereg ("([0-9]+)-([0-9]+)-([0-9]+) ([0-9]+):([0-9]+):([0-9]+)", $time, $datetime);

    // 07-07-2001:Alarion - For the time being, I added an ereg_replace to strip out
    // the timezone until I get a function in to replace the server timezone with the users timezone
    $datetime = strftime("".ereg_replace("%Z", "",_DATESTRING)."", mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
    $datetime = ucfirst($datetime);
    return($datetime);
}


/**
 * include_once replacement
 *
 * Works basicly like include_once() (except not
 * include() aware, I'm not sure what array name
 * they use). Needed for older PHP4 installs.
 *
 * @param $f string The file/path to include
 * @return false if file was already included. true if first include
 * @author Patrick Kellum <webmaster@ctarl-ctarl.com>
 */
if (!function_exists('pninclude_once')) {
function pninclude_once($f)
{
//    static $postnuke_include_files;
//    if (!empty($postnuke_include_files[$f]))
//    {
//        return false;
//    }
//    include $f;
    include_once $f; // new
//    $postnuke_include_files[$f] = true;
    return true;
}
}

function check_words($Message) {
    global $EditedMessage;

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

/**
 * cross site scripting check
 */
function csssafe($checkArg = "op", $checkReferer = true)
{
    return true;
}

function myTextForm($url , $value , $useTable = false , $extraname="postnuke")
{
    $form = "";
    $form .= "<form action=\"$url\" method=\"post\"><div>";
    if ($useTable){
        $form .= "<table border=\"0\" width=\"100%\" align=\"center\"><tr><td>\n";
    }
    $form .= "<input type=\"submit\" value=\"$value\" style=\"text-align:center\" />";
    $form .= "<input type=\"hidden\" name=\"$extraname\" value=\"$extraname\" /></div></form>\n";
    if ($useTable){
        $form .= "</td></tr></table>\n";
    }
    return $form;
}

/**
 *  Error message due a ADODB SQL error and die
 */
function PN_DBMsgError($db='',$prg='',$line=0,$message='Error Accesing the Database')
{

    /*
    * simplied version of initial fix supplied by Neo
    * original fix by markwest 
    */
    $docroot = getcwd();
    $docroot = str_replace( 'includes', "", $docroot );

    $prg = str_replace('\\', '/', $prg);
    $prgoutput = str_replace($docroot, '[webroot]', $prg); 

    if(pnSecAuthAction(0, "::", '::', ACCESS_ADMIN)) {
        $lcmessage = $message . "<br />" .
                     "Program: " . $prgoutput . " - " . "Line N.: " . $line . "<br />" .
                     "Database: " . $db->database . "<br /> ";

        if($db->ErrorNo()<>0) {
            $lcmessage .= "Error (" . $db->ErrorNo() . ") : " . $db->ErrorMsg() . "<br />";
        }
    } else {
        $lcmessage = $message . "<br />" ."Program: " . $prgoutput . " - " . "Line N.: " . $line . "<br />";

        if($db->ErrorNo()<>0) {
            $lcmessage .= "Error (" . $db->ErrorNo() . ") : " . $db->ErrorMsg() . "<br />";
        }
    }
    die($lcmessage);
}

?>