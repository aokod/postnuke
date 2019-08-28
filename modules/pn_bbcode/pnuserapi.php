<?php
// $Id: pnuserapi.php 108 2006-10-22 21:00:34Z landseer $
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
// Original Author of file: Hinrich Donner
// changed to pn_bbcode: larsneo
// ----------------------------------------------------------------------

if(!class_exists('GeSHi')) {
    include_once('modules/pn_bbcode/pnincludes/geshi.php');
}

/**
 * @package PostNuke_Utility_Modules
 * @subpackage pn_bbcode
 * @credits to Bravecobra for the phps function (webmaster@bravecobra.com)
 * @license http://www.gnu.org/copyleft/gpl.html
*/

/**
 * the hook function
*/
function pn_bbcode_userapi_transform($args)
{
    extract($args);

    // Argument check
    if ((!isset($objectid)) ||
        (!isset($extrainfo))) {
        pnSessionSetVar('errormsg', _PNBBCODE_ARGSERROR);
        return;
    }

    if (is_array($extrainfo)) {
        foreach ($extrainfo as $text) {
            $result[] = pn_bbcode_transform($text);
        }
    } else {
        $result = pn_bbcode_transform($extrainfo);
    }

    return $result;
}

/**
 * the wrapper for a string var (simple up to now)
 * bbdecode/bbencode functions:
 * Rewritten - Nathan Codding - Aug 24, 2000
 * quote, code, and list rewritten again in Jan. 2001.
 * All BBCode tags now implemented. Nesting and multiple occurances should be
 * handled fine for all of them. Using str_replace() instead of regexps often
 * for efficiency. quote, list, and code are not regular, so they are
 * implemented as PDAs - probably not all that efficient, but that's the way it is.
 *
 * Note: all BBCode tags are case-insensitive.
 *
 * some changes for PostNuke: larsneo - Jan, 12, 2003
 * different [img] tag conversion against XSS
*/
function pn_bbcode_transform($message)
{
    // check the user agent - if it is a bot, return immediately
    $robotslist = array ( "ia_archiver",
                          "googlebot",
                          "mediapartners-google",
                          "yahoo!",
                          "msnbot",
                          "jeeves",
                          "lycos");
    $useragent = pnServerGetVar('HTTP_USER_AGENT');
    for($cnt=0; $cnt < count($robotslist); $cnt++) {
        if(strpos(strtolower($useragent), $robotslist[$cnt]) !== false) {
            return $message;
        }
    }

    // pad it with a space so we can distinguish between FALSE and matching the 1st char (index 0).
    // This is important; bbencode_quote(), bbencode_list(), and bbencode_code() all depend on it.
    $message = " " . $message;

    // First: If there isn't a "[" and a "]" in the message, don't bother.
    if (! (strpos($message, "[") && strpos($message, "]")) ) {
        // Remove padding, return.
        $message = substr($message, 1);
        return $message;
    }

    // [CODE] and [/CODE] for posting code (HTML, PHP, C etc etc) in your posts.
    $message = pn_bbcode_encode_code($message);

    // move all links out of the text and replace them with placeholders
    $linkscount = preg_match_all('/<a(.*)>(.*)<\/a>/siU', $message, $links);
    for ($i = 0; $i < $linkscount; $i++) {
        $message = preg_replace('/(' . preg_quote($links[0][$i], '/') . ')/', " PNBBCODELINKREPLACEMENT{$i} ", $message, 1);
    }

    // Step 1 - remove all html tags, we do not want to change them!!
    $htmlcount = preg_match_all("/<(?:[^\"\']+?|.+?(?:\"|\').*?(?:\"|\')?.*?)*?>/i", $message, $html);
    for ($i=0; $i < $htmlcount; $i++) {
        $message = preg_replace('/(' . preg_quote($html[0][$i], '/') . ')/', " PNBBCODEHTMLREPLACEMENT{$i} ", $message, 1);
    }
    // [QUOTE] and [/QUOTE] for posting replies with quote, or just for quoting stuff.
    $message = pn_bbcode_encode_quote($message);

    // [list] and [list=x] for (un)ordered lists.
    $message = pn_bbcode_encode_list($message);

    // [b] and [/b] for bolding text.
    $message = preg_replace("/\[b\](.*?)\[\/b\]/si", "<strong>\\1</strong>", $message);

    // [i] and [/i] for italicizing text.
    $message = preg_replace("/\[i\](.*?)\[\/i\]/si", "<em>\\1</em>", $message);

    // [img]image_url_here[/img] code..
    $message = preg_replace("#\[img\](.*?)\[/img\]#si", "<img src=\"\\1\" alt=\"\\1\" />", $message);
    //$message = preg_replace("/\[img\](.*?)\[\/img\]/si", "<IMG SRC=\"\\1\" BORDER=\"0\">", $message);

    // three new bbcodes, thanks to Chris Miller (r3ap3r)
    // [u] and [/u] for underlining text.
    $message = preg_replace("/\[u\](.*?)\[\/u\]/si", "<span style=\"text-decoration:underline;\">\\1</span>", $message);

    // [color] and [/color] for coloring text.
    if(pnModGetVar('pn_bbcode', 'color_enabled')=="yes") {
        $message = preg_replace("#\[color=black\](.*?)\[/color\]#si", "<span style=\"color:black;\">\\1</span>", $message);
        $message = preg_replace("#\[color=darkred\](.*?)\[/color\]#si", "<span style=\"color:darkred;\">\\1</span>", $message);
        $message = preg_replace("#\[color=red\](.*?)\[/color\]#si", "<span style=\"color:red;\">\\1</span>", $message);
        $message = preg_replace("#\[color=orange\](.*?)\[/color\]#si", "<span style=\"color:orange;\">\\1</span>", $message);
        $message = preg_replace("#\[color=brown\](.*?)\[/color\]#si", "<span style=\"color:brown;\">\\1</span>", $message);
        $message = preg_replace("#\[color=yellow\](.*?)\[/color\]#si", "<span style=\"color:yellow;\">\\1</span>", $message);
        $message = preg_replace("#\[color=green\](.*?)\[/color\]#si", "<span style=\"color:green;\">\\1</span>", $message);
        $message = preg_replace("#\[color=olive\](.*?)\[/color\]#si", "<span style=\"color:olive;\">\\1</span>", $message);
        $message = preg_replace("#\[color=cyan\](.*?)\[/color\]#si", "<span style=\"color:cyan;\">\\1</span>", $message);
        $message = preg_replace("#\[color=blue\](.*?)\[/color\]#si", "<span style=\"color:blue;\">\\1</span>", $message);
        $message = preg_replace("#\[color=darkblue\](.*?)\[/color\]#si", "<span style=\"color:darkblue;\">\\1</span>", $message);
        $message = preg_replace("#\[color=indigo\](.*?)\[/color\]#si", "<span style=\"color:indigo;\">\\1</span>", $message);
        $message = preg_replace("#\[color=violet\](.*?)\[/color\]#si", "<span style=\"color:violet;\">\\1</span>", $message);
        $message = preg_replace("#\[color=white\](.*?)\[/color\]#si", "<span style=\"color:white;\">\\1</span>", $message);
        $message = preg_replace("#\[color=black\](.*?)\[/color\]#si", "<span style=\"color:black;\">\\1</span>", $message);
        // freestyle color
        if(pnModGetVar('pn_bbcode', 'allow_usercolor')=="yes") {
            $message = preg_replace("#\[color=(\#[0-9A-F]{6}|[a-z\-]+)\](.*?)\[/color\]#si", "<span style=\"color:\\1;\">\\2</span>", $message);
        } else {
            $message = preg_replace("#\[color=(\#[0-9A-F]{6}|[a-z\-]+)\](.*?)\[/color\]#si", "\\2", $message);
        }
    } else {
        $message = preg_replace("#\[color=(.*?)\](.*?)\[/color\]#si", "\\2", $message);
    }

    // [size] and [/size] for setting the size of text.
    if(pnModGetVar('pn_bbcode', 'size_enabled')=="yes") {
        $message = preg_replace("/\[size=tiny\](.*?)\[\/size\]/si", "<span style=\"font-size:".pnModGetVar('pn_bbcode', 'size_tiny').";\">\\1</span>", $message);
        $message = preg_replace("/\[size=small\](.*?)\[\/size\]/si", "<span style=\"font-size:".pnModGetVar('pn_bbcode', 'size_small').";\">\\1</span>", $message);
        $message = preg_replace("/\[size=normal\](.*?)\[\/size\]/si", "<span style=\"font-size:".pnModGetVar('pn_bbcode', 'size_normal').";\">\\1</span>", $message);
        $message = preg_replace("/\[size=large\](.*?)\[\/size\]/si", "<span style=\"font-size:".pnModGetVar('pn_bbcode', 'size_large').";\">\\1</span>", $message);
        $message = preg_replace("/\[size=huge\](.*?)\[\/size\]/si", "<span style=\"font-size:".pnModGetVar('pn_bbcode', 'size_huge').";\">\\1</span>", $message);
        // freestyle size
        if(pnModGetVar('pn_bbcode', 'allow_usersize')=="yes") {
            $message = preg_replace("/\[size=([0-9]+)\](.*?)\[\/size\]/si", "<span style=\"font-size:\\1px;\">\\2</span>", $message);
        } else {
            $message = preg_replace("/\[size=([0-9]+)\](.*?)\[\/size\]/si", "\\2", $message);
        }
    } else {
        $message = preg_replace("/\[size=(.*?)\](.*?)\[\/size\]/si", "\\2", $message);
    }

    // [url]xxxx://www.phpbb.com[/url] code..
    $message = preg_replace_callback(
                "#\[url\]([a-z]+?://){1}(.*?)\[/url\]#si",
                'linktest_callback_0',
                $message);

    // [url]www.phpbb.com[/url] code.. (no xxxx:// prefix).
    $message = preg_replace_callback(
                "#\[url\](.*?)\[/url\]#si",
                'linktest_callback_1',
                $message);

    // [url=xxxx://www.phpbb.com]phpBB[/url] code..
    $message = preg_replace_callback(
                "#\[url=([a-z]+?://){1}(.*?)\](.*?)\[/url\]#si",
                'linktest_callback_2',
                $message);

    // [url=www.phpbb.com]phpBB[/url] code.. (no xxxx:// prefix).
    $message = preg_replace_callback(
                "#\[url=(.*?)\](.*?)\[/url\]#si",
                'linktest_callback_3',
                $message);

    // [email]user@domain.tld[/email] code..
    $message = preg_replace_callback(
                "#\[email\](.*?)\[/email\]#si",
                'linktest_callback_4',
                $message);

    // replace the tags and links that we removed before
    for ($i = 0; $i < $htmlcount; $i++) {
        $message = preg_replace("/ PNBBCODEHTMLREPLACEMENT{$i} /", $html[0][$i], $message, 1);
    }
    for ($i = 0; $i < $linkscount; $i++) {
        $message = preg_replace("/ PNBBCODELINKREPLACEMENT{$i} /", $links[0][$i], $message, 1);
    }

    // Remove our padding from the string..
    $message = substr($message, 1);
    return $message;

} // pn_bbcode_encode()

/**
 * Nathan Codding - Jan. 12, 2001.
 * Performs [quote][/quote] bbencoding on the given string, and returns the results.
 * Any unmatched "[quote]" or "[/quote]" token will just be left alone.
 * This works fine with both having more than one quote in a message, and with nested quotes.
 * Since that is not a regular language, this is actually a PDA and uses a stack. Great fun.
 *
 * Note: This function assumes the first character of $message is a space, which is added by
 * bbencode().
 */
function pn_bbcode_encode_quote($message)
{
    // First things first: If there aren't any "[quote=" or "[quote] strings in the message, we don't
    // need to process it at all.
    if (!strpos(strtolower($message), "[quote=") && !strpos(strtolower($message), "[quote]")) {
        return $message;
    }

    add_stylesheet_header();

    $quotebody = pnModGetVar('pn_bbcode', 'quote');

    $stack = Array();
    $curr_pos = 1;
    while ($curr_pos && ($curr_pos < strlen($message))) {
        $curr_pos = strpos($message, "[", $curr_pos);

        // If not found, $curr_pos will be 0, and the loop will end.
        if ($curr_pos) {
            // We found a [. It starts at $curr_pos.
            // check if it's a starting or ending quote tag.
            $possible_start = substr($message, $curr_pos, 6);
            $possible_end_pos = strpos($message, "]", $curr_pos);
            $possible_end = substr($message, $curr_pos, $possible_end_pos - $curr_pos + 1);
            if (strcasecmp("[quote", $possible_start) == 0)
            {
                // We have a starting quote tag.
                // Push its position on to the stack, and then keep going to the right.
                array_push($stack, $curr_pos);
                ++$curr_pos;
            } else if (strcasecmp("[/quote]", $possible_end) == 0) {
                // We have an ending quote tag.
                // Check if we've already found a matching starting tag.
                if (sizeof($stack) > 0) {
                    // There exists a starting tag.
                    // We need to do 2 replacements now.
                    $start_index = array_pop($stack);

                    // everything before the [quote=xxx] tag.
                    $before_start_tag = substr($message, 0, $start_index);

                    // find the end of the start tag
                    $start_tag_end = strpos($message, "]", $start_index);
                    $start_tag_len = $start_tag_end - $start_index + 1;
                    if($start_tag_len > 7) {
                        $username = substr($message, $start_index + 7, $start_tag_len - 8);
                    } else {
                        $username = pnVarPrepForDisplay(_PNBBCODE_QUOTE);;
                    }

                    // everything after the [quote=xxx] tag, but before the [/quote] tag.
                    $between_tags = substr($message, $start_index + $start_tag_len, $curr_pos - ($start_index + $start_tag_len));
                    // everything after the [/quote] tag.
                    $after_end_tag = substr($message, $curr_pos + 8);

                    $quotetext = str_replace('%u', $username, $quotebody);
                    $quotetext = str_replace('%t', $between_tags, $quotetext);

                    // rfe [ 1243208 ] credits to msandersen
                    // color handling
                    $colors['bgcolor1']   = pnThemeGetVar('bgcolor1');
                    $colors['bgcolor2']   = pnThemeGetVar('bgcolor2');
                    $colors['bgcolor3']   = pnThemeGetVar('bgcolor3');
                    $colors['bgcolor4']   = pnThemeGetVar('bgcolor4');
                    $colors['bgcolor5']   = pnThemeGetVar('bgcolor5');
                    $colors['sepcolor']   = pnThemeGetVar('sepcolor');
                    $colors['textcolor1'] = pnThemeGetVar('textcolor1');
                    $colors['textcolor2'] = pnThemeGetVar('textcolor2');
                    foreach($colors as $colorname => $colorvalue) {
                        $quotetext = str_replace('%' . $colorname, $colorvalue, $quotetext);
                    }

                    $message = $before_start_tag . $quotetext . $after_end_tag;

                    // Now.. we've screwed up the indices by changing the length of the string.
                    // So, if there's anything in the stack, we want to resume searching just after it.
                    // otherwise, we go back to the start.
                    if (sizeof($stack) > 0) {
                        $curr_pos = array_pop($stack);
                        array_push($stack, $curr_pos);
                        ++$curr_pos;
                    } else {
                        $curr_pos = 1;
                    }
                } else {
                    // No matching start tag found. Increment pos, keep going.
                    ++$curr_pos;
                }
            } else {
                // No starting tag or ending tag.. Increment pos, keep looping.,
                ++$curr_pos;
            }
        }
    } // while

    return $message;

} // pn_bbcode_encode_quote()

/**
 * Nathan Codding - Jan. 12, 2001.
 * Frank Schummertz - Sept. 2004ff
 * Performs [code][/code] bbencoding on the given string, and returns the results.
 * Any unmatched "[code]" or "[/code]" token will just be left alone.
 * This works fine with both having more than one code block in a message, and with nested code blocks.
 * Since that is not a regular language, this is actually a PDA and uses a stack. Great fun.
 *
 * Note: This function assumes the first character of $message is a space, which is added by
 * bbencode().
 */
function pn_bbcode_encode_code($message)
{
    $count = preg_match_all("#(\[code=*(.*?)\])(.*?)(\[\/code\])#si", $message, $bbcode);
    // the array $bbode now contains e.g.
    // [0] [code=php,start=25]php code();[/code]
    // [1] [code=php,start=25]
    // [2] php,start=25
    // [3] php code();
    // [4] [/code]

    if($count>0 && is_array($bbcode)) {
        add_stylesheet_header();
        // this is only needed once and will not change
        $hilite  = (pnModGetVar('pn_bbcode', 'syntaxhilite')=='yes') ? true : false;
        $codebody = "<!--code-->" . pnModGetVar('pn_bbcode', 'code') . "<!--/code-->";
        for($i=0; $i < $count; $i++) {
            // the code in between incl. code tags
            $str_to_match = "/" . preg_quote($bbcode[0][$i], "/") . "/";

            // no parameters, set some defaults
            $numbers = (pnModGetVar('pn_bbcode', 'linenumbers')=='yes') ? true : false;
            $language = 'php';
            $startline = 1;
            // analyze parameters in [2]
            if(strlen($bbcode[2][$i])>0) {
                $parameters = explode(',', $bbcode[2][$i]);
                if(is_array($parameters) && count($parameters)>0) {
                    // $parameters[0] is the language
                    // everything else must be parsed
                    $language = $parameters[0];
                    // remove it, its no longer used
                    array_shift($parameters);
                    foreach($parameters as $parameter) {
                        $singleparam = explode('=', $parameter);
                        switch(strtolower($singleparam[0])) {
                            case 'start':
                                $startline = $singleparam[1];
                                break;
                            case 'nolines':
                                $numbers = false;
                                break;
                            case 'user':
                                // special for htmlmail, we show the user name instead
                                // of _PNBBCODE_CODE
                                $username = $singleparam[1];
                                break;
                            default:
                        }
                    }
                }
            } // parameters analyzed
            $after_replace = "";
            // pagesetter workaround:
            if(pnModGetName()=='pagesetter') {
                $bbcode[3][$i] = html_entity_decode($bbcode[3][$i]);
            }
            if(!empty($bbcode[3][$i])) {
                $lines = explode("\n", trim($bbcode[3][$i]));
                $linecount = count($lines);
                if(is_array($lines) && count($lines)>0) {
                    // remove empty lines on top of the code
                    while(count($lines)>0 && ($lines[0] == '' || $lines[0] == ' ' || $lines[0] == "\r") ) {
                        array_shift($lines);
                    }
                    if(count($lines)>0){
                        // remove empty lines at the end of the code
                        while(count($lines)>0 && ($lines[count($lines)-1] == '' || $lines[count($lines)-1] == ' ' || $lines[count($lines)-1] == "\r") ) {
                            array_pop($lines);
                        }
                        if(count($lines)>0){
                            for($lcnt=0;$lcnt<count($lines); $lcnt++) {
                                if($numbers==true && $hilite==false) {
                                    $after_replace .= sprintf("%03d", $startline+$lcnt) . ": " . $lines[$lcnt] . "\n";
                                } else {
                                    $after_replace .= $lines[$lcnt] . "\n";
                                }
                            }
                        }
                    }
                }
            }
            $after_replace = chop($after_replace);

            // finally decide which language to use
            $language = ($hilite==true) ? $language : '';

            $geshi =& new GeSHi($after_replace, $language, 'modules/pn_bbcode/pnincludes/geshi/');
            $geshi->set_tab_width(4);
            $geshi->set_case_keywords(GESHI_CAPS_LOWER);
            $geshi->set_header_type(GESHI_HEADER_DIV);
            $geshi->set_link_styles(GESHI_LINK,    'padding-left: 0px; background-image: none;');
            $geshi->set_link_styles(GESHI_HOVER,   'padding-left: 0px; background-image: none;');
            $geshi->set_link_styles(GESHI_ACTIVE,  'padding-left: 0px; background-image: none;');
            $geshi->set_link_styles(GESHI_VISITED, 'padding-left: 0px; background-image: none;');
            if($numbers == true) {
                $geshi->set_line_style('color: blue; font-weight: bold;', 'color: green;');
                $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
                $geshi->start_line_numbers_at($startline);
            } else {
                $geshi->enable_line_numbers(GESHI_NO_LINE_NUMBERS);
            }
            $after_replace = $geshi->parse_code();
            if($hilite==true) {
                // remove \n for single linespacing
                $after_replace = str_replace("\n", '', $after_replace);
            }

            // replace %h with _PNBBCODE_CODE
            if($language=='htmlmail') {
                $codetext = str_replace("%h", pnVarPrepForDisplay(_PNBBCODE_ORIGINALSENDER) . ': ' . pnVarPrepForDisplay($username), $codebody);
            } else {
                $codetext = str_replace("%h", pnVarPrepForDisplay(_PNBBCODE_CODE), $codebody);
            }
            // replace %c with code
            $codetext = str_replace("%c",  $after_replace, $codetext);
            // replace %e with urlencoded code (prepared for javascript)
            $codetext = str_replace("%e", urlencode(nl2br($after_replace)), $codetext);
            // my fault, admin panel says %j, so we do both :-)
            $codetext = str_replace("%j", urlencode(nl2br($after_replace)), $codetext);

            // rfe [ 1243208 ] credits to msandersen
            // color handling
            $colors['bgcolor1']   = pnThemeGetVar('bgcolor1');
            $colors['bgcolor2']   = pnThemeGetVar('bgcolor2');
            $colors['bgcolor3']   = pnThemeGetVar('bgcolor3');
            $colors['bgcolor4']   = pnThemeGetVar('bgcolor4');
            $colors['bgcolor5']   = pnThemeGetVar('bgcolor5');
            $colors['sepcolor']   = pnThemeGetVar('sepcolor');
            $colors['textcolor1'] = pnThemeGetVar('textcolor1');
            $colors['textcolor2'] = pnThemeGetVar('textcolor2');
            foreach($colors as $colorname => $colorvalue) {
                $codetext = str_replace("%{$colorname}", $colorvalue, $codetext);
            }

            $message = preg_replace($str_to_match, $codetext, $message);
        }
        //$message = str_replace("\n\n","\n",$message);
    }
    return $message;

} // pn_bbcode_encode_code()


/**
 * Nathan Codding - Jan. 12, 2001.
 * Frank Schummertz - Sept. 2004ff
 * Performs [list][/list] and [list=?][/list] bbencoding on the given string, and returns the results.
 * Any unmatched "[list]" or "[/list]" token will just be left alone.
 * This works fine with both having more than one list in a message, and with nested lists.
 * Since that is not a regular language, this is actually a PDA and uses a stack. Great fun.
 *
 * Note: This function assumes the first character of $message is a space, which is added by
 * bbencode().
 */
function pn_bbcode_encode_list($message)
{
    $start_length = array('ordered' => 8,
                          'unordered' => 6);

    // First things first: If there aren't any "[list" strings in the message, we don't
    // need to process it at all.
    if (!strpos(strtolower($message), "[list")) {
        return $message;
    }

    add_stylesheet_header();

    $stack = array();
    $curr_pos = 1;
    while ($curr_pos && ($curr_pos < strlen($message))) {
        $curr_pos = strpos($message, "[", $curr_pos);

        // If not found, $curr_pos will be 0, and the loop will end.
        if ($curr_pos) {
            // We found a [. It starts at $curr_pos.
            // check if it's a starting or ending list tag.
            $possible_ordered_start = substr($message, $curr_pos, $start_length['ordered']);
            $possible_unordered_start = substr($message, $curr_pos, $start_length['unordered']);
            $possible_end = substr($message, $curr_pos, 7);
            if (strcasecmp("[list]", $possible_unordered_start) == 0) {
                // We have a starting unordered list tag.
                // Push its position on to the stack, and then keep going to the right.
                array_push($stack, array($curr_pos, ""));
                ++$curr_pos;
            } else if (preg_match("/\[list=([a1])\]/si", $possible_ordered_start, $matches)) {
                // We have a starting ordered list tag.
                // Push its position on to the stack, and the starting char onto the start
                // char stack, the keep going to the right.
                array_push($stack, array($curr_pos, $matches[1]));
                ++$curr_pos;
            } else if (strcasecmp("[/list]", $possible_end) == 0) {
                // We have an ending list tag.
                // Check if we've already found a matching starting tag.
                $new_between_tags = '';
                if (sizeof($stack) > 0) {
                    // There exists a starting tag.
                    // We need to do 2 replacements now.
                    $start = array_pop($stack);
                    $start_index = $start[0];
                    $start_char = $start[1];
                    $is_ordered = ($start_char != "");

                    // configure list style
                    // to-do: think about definition lists...
                    if($is_ordered) {
                        // ordered list
                        $listitemclass    = 'bbcode_orderedlist_item';
                        $start_tag_length = $start_length['ordered'];
                        $start_tag        = '<ol type="' . $start_char . '" class="bbcode_orderedlist">';
                        $end_tag          = '</ol>';
                    } else {
                        // list
                        $listitemclass    = 'bbcode_list_item';
                        $start_tag_length = $start_length['unordered'];
                        $start_tag        = '<ul class="bbcode_list">';
                        $end_tag          = '</ul>';
                    }

                    // everything before the [list] tag.
                    $before_start_tag = substr($message, 0, $start_index);

                    // everything between [list] and [/list] tags.
                    $between_tags = substr($message, $start_index + $start_tag_length, $curr_pos - $start_index - $start_tag_length);

                    // everything after the [/list] tag.
                    $after_end_tag = substr($message, $curr_pos + 7);

                    // replace [*]... with <li>...</li>
                    // new: adding css classes for better styling of bbcode lists
                    $listitems = explode('[*]', $between_tags);
                    // listitems may be false, empty or containing [*] if between_tags was empty
                    if(is_array($listitems) && count($listitems)>0 && $listitems[0]<>'[*]') {
                        for($cnt=1; $cnt<count($listitems); $cnt++) {
                            if(!empty($listitems[$cnt])) {
                                $new_between_tags .= '<li class="' . $listitemclass . '">' . $listitems[$cnt] . '</li>';
                            }
                        }
                    }

                    $message = $before_start_tag
                             . $start_tag
                             . $new_between_tags
                             . $end_tag
                             . $after_end_tag;

                    // Now.. we've screwed up the indices by changing the length of the string.
                    // So, if there's anything in the stack, we want to resume searching just after it.
                    // otherwise, we go back to the start.
                    if (sizeof($stack) > 0) {
                        $a = array_pop($stack);
                        $curr_pos = $a[0];
                        array_push($stack, $a);
                        ++$curr_pos;
                    } else {
                        $curr_pos = 1;
                    }
                } else {
                    // No matching start tag found. Increment pos, keep going.
                    ++$curr_pos;
                }
            } else {
                // No starting tag or ending tag.. Increment pos, keep looping.,
                ++$curr_pos;
            }
        }
    } // while

    return $message;

} // pn_bbcode_encode_list()

/**
 * get_geshi_languages
 * read the languages supported by GeSHi for display in a dropdown list
 *
 */
function pn_bbcode_userapi_get_geshi_languages()
{
    $langs = array();
    if(pnModGetVar('pn_bbcode', 'syntaxhilite')=='yes') {
        $dir = opendir('modules/pn_bbcode/pnincludes/geshi');
        while($lang = readdir($dir)) {
            if(preg_match("/\.php$/si", $lang)) {
                // remove trailing .php
                $langs[] = preg_replace("/\.php$/si", "", $lang);
            }
        }
    }
    asort($langs);
    return $langs;
}

/**
 * Nathan Codding - Oct. 30, 2000
 *
 * Escapes the "/" character with "\/". This is useful when you need
 * to stick a runtime string into a PREG regexp that is being delimited
 * with slashes.
 */
function pn_escape_slashes($input)
{
    $output = str_replace('/', '\/', $input);
    return $output;
}

/**
 * larsneo - Jan. 11, 2003
 *
 * removes instances of <br /> since sometimes they are stored in DB :(
 */
function pn_bbcode_br2nl($str)
{
    return preg_replace("=<br(>|([\s/][^>]*)>)\r?\n?=i", "\n", $str);
}


/**
 * add_stylesheet_header
 *
 */
function add_stylesheet_header()
{
    // add the style sheet file to the additional_header array
    $stylesheet = "modules/pn_bbcode/pnstyle/style.css";
    $stylesheetlink = "<link rel=\"stylesheet\" href=\"$stylesheet\" type=\"text/css\" />";
    global $additional_header;
    if(is_array($additional_header)) {
        $values = array_flip($additional_header);
        if(!array_key_exists($stylesheetlink, $values) && file_exists($stylesheet) && is_readable($stylesheet)) {
            $additional_header[] = $stylesheetlink;
        }
    } else {
        if(file_exists($stylesheet) && is_readable($stylesheet)) {
            $additional_header[] = $stylesheetlink;
        }
    }
    return;
}

/**
 * linktest_callback_0
 *
 */
function linktest_callback_0($matches)
{
    static $is_allowed;
    static $modname;
    static $our_url;

    if(!isset($is_allowed)) {
        $modname = pnModGetName();
        $our_url = pnGetBaseURL();
        $is_allowed = pnSecAuthAction(0, 'pn_bbcode:' . $modname . ':Links' , '::', ACCESS_READ);
    }
    if( ($is_allowed==false) && (strpos($matches[1] . $matches[2], $our_url)===false) ) {
        // not allowed to see links and link is not on our site
        if(pnUserLoggedIn()) {
            return  pnVarPrepForDisplay(_PNBBCODE_NOTALLOWEDTOSEEEXTERNALLINKS);
        } else {
            return '<a href="user.php" title="' . pnVarPrepForDisplay(_PNBBCODE_NOTALLOWEDTOSEEEXTERNALLINKS) . '">' . pnVarPrepForDisplay(_PNBBCODE_NOTALLOWEDTOSEEEXTERNALLINKS) . '</a>';
        }
    } else {
        $displayurl = pn_bbcode_minimize_displayurl($matches[1] . $matches[2]);
        return '<a href="' . $matches[1] . $matches[2] . '">' . $displayurl . '</a>';
    }
}

/**
 * linktest_callback_1
 *
 */
function linktest_callback_1($matches)
{
    static $is_allowed;
    static $modname;
    static $our_url;

    if(!isset($is_allowed)) {
        $modname = pnModGetName();
        $our_url = pnGetBaseURL();
        $is_allowed = pnSecAuthAction(0, 'pn_bbcode:' . $modname . ':Links' , '::', ACCESS_READ);
    }
    if( ($is_allowed==false) && (strpos('http://' . $matches[1], $our_url)===false) ) {
        // not allowed to see links and link is not on our site
        if(pnUserLoggedIn()) {
            return  pnVarPrepForDisplay(_PNBBCODE_NOTALLOWEDTOSEEEXTERNALLINKS);
        } else {
            return '<a href="user.php" title="' . pnVarPrepForDisplay(_PNBBCODE_NOTALLOWEDTOSEEEXTERNALLINKS) . '">' . pnVarPrepForDisplay(_PNBBCODE_NOTALLOWEDTOSEEEXTERNALLINKS) . '</a>';
        }
    } else {
        $displayurl = pn_bbcode_minimize_displayurl($matches[1]);
        return '<a href="http://' . $matches[1] . '">' . $displayurl . '</a>';
    }
}

/**
 * linktest_callback_2
 *
 */
function linktest_callback_2($matches)
{
    static $is_allowed;
    static $modname;
    static $our_url;

    if(!isset($is_allowed)) {
        $modname = pnModGetName();
        $our_url = pnGetBaseURL();
        $is_allowed = pnSecAuthAction(0, 'pn_bbcode:' . $modname . ':Links' , '::', ACCESS_READ);
    }

    if( (pnVarValidate($matches[3], 'url')==true) && ($is_allowed==false) && (strpos($matches[3], $our_url)===false) ) {
        $displayurl = pnVarPrepForDisplay(_PNBBCODE_NOTALLOWEDTOSEEEXTERNALLINKS);
    } else {
        $displayurl = $matches[3];
        $title = strip_tags($displayurl);
    }
    if( ($is_allowed==false) && (strpos($matches[1] . $matches[2], $our_url)===false) ) {
        // not allowed to see links and link is not on our site
        if(pnUserLoggedIn()) {
            return  '<span title="' . pnVarPrepForDisplay(_PNBBCODE_NOTALLOWEDTOSEEEXTERNALLINKS) . '">' . $displayurl . '</span>';
        } else {
            return '<a href="user.php" title="' . pnVarPrepForDisplay(_PNBBCODE_NOTALLOWEDTOSEEEXTERNALLINKS) . '">' . $displayurl . '</a>';
        }
    } else {
        return '<a href="' . $matches[1] . $matches[2] . '" title="' . $title . '">' . $displayurl . '</a>';
    }
}

/**
 * linktest_callback_3
 *
 */
function linktest_callback_3($matches)
{
    static $is_allowed;
    static $modname;
    static $our_url;

    if(!isset($is_allowed)) {
        $modname = pnModGetName();
        $our_url = pnGetBaseURL();
        $is_allowed = pnSecAuthAction(0, 'pn_bbcode:' . $modname . ':Links' , '::', ACCESS_READ);
    }

    if(pnVarValidate($matches[2], 'url')==true) {
        $displayurl = pnVarPrepForDisplay(_PNBBCODE_NOTALLOWEDTOSEEEXTERNALLINKS);
    } else {
        $displayurl = $matches[2];
        $title = strip_tags($displayurl);
    }
    if( ($is_allowed==false) && (strpos('http://' . $matches[1], $our_url)===false) ) {
        // not allowed to see links and link is not on our site
        if(pnUserLoggedIn()) {
            return '<span title="' . pnVarPrepForDisplay(_PNBBCODE_NOTALLOWEDTOSEEEXTERNALLINKS) . '"><strong>' . $displayurl . '</strong></span>';
        } else {
            return '<a href="user.php" title="' . pnVarPrepForDisplay(_PNBBCODE_NOTALLOWEDTOSEEEXTERNALLINKS) . '">' . $displayurl . '</a>';
        }
    } else {
        return '<a href="http://' . $matches[1] . '" title="' . $title . '">' . $displayurl . '</a>';
    }
}

/**
 * linktest_callback_4
 *
 */
function linktest_callback_4($matches)
{
    static $is_allowed;
    static $modname;

    if(!isset($is_allowed)) {
        $modname = pnModGetName();
        $is_allowed = pnSecAuthAction(0, 'pn_bbcode:' . $modname . ':Emails' , '::', ACCESS_READ);
    }
    if($is_allowed==false) {
        // not allowed to see emails
        if(pnUserLoggedIn()) {
            return  pnVarPrepForDisplay(_PNBBCODE_NOTALLOWEDTOSEEEMAILS);
        } else {
            return '<a href="user.php" title="' . pnVarPrepForDisplay(_PNBBCODE_NOTALLOWEDTOSEEEMAILS) . '">' . pnVarPrepForDisplay(_PNBBCODE_NOTALLOWEDTOSEEEMAILS) . '</a>';
        }
    } else {
        return '<a href="mailto:' . $matches[1] . '" title="' . $matches[1] . '">' . $matches[1] . '</a>';
    }
}

/** pn_bbcode_minimize_displayurl
 *  helper function to cut down the displayed url to a maximum length
 *
 *
 */
function pn_bbcode_minimize_displayurl($displayurl)
{
    // get the maximum size of the urls to show
    // todo: make this configurable
    $maxsize = 30;
    $before = round($maxsize / 2);
    $after  = $maxsize - 3 - $before;

    if(strlen($displayurl) > $maxsize) {
        $displayurl = substr($displayurl, 0, $before) . "..." . substr($displayurl, strlen($displayurl) - $after, $after);
    }
    return $displayurl;
}

?>