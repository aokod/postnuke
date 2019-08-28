<?php
// $Id: pnuserapi.php 19298 2006-06-26 13:34:51Z markwest $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
// http://www.postnuke.com/
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
// Original Author of file: Jim McDonald
// Purpose of file:  Wiki user API
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Value_Addons
 * @subpackage Wiki
 * @license http://www.gnu.org/copyleft/gpl.html
*/

if (!defined('WIKI_ZERO_DEPTH')) {
    define('WIKI_ZERO_DEPTH', 0);
    define('WIKI_SINGLE_DEPTH', 1);
    define('WIKI_ZERO_LEVEL', 0);
    define('WIKI_NESTED_LEVEL', 1);
}

/**
 * Parse wiki bracketed link
 * @author Jim McDonald <Jim@mcdee.net>
 * @link http://www.mcdee.net
 * @return array wiki link
 */
function PNParseAndLink($bracketlink)
{
    static $PNAllowedProtocols;
    if (!isset($PNAllowedProtocols)) {
        $PNAllowedProtocols = pnModGetVar('Wiki', 'AllowedProtocols');
    }
    static $PNInlineImages;
    if (!isset($PNInlineImages)) {
        $PNInlineImages = pnModGetVar('Wiki', 'InlineImages');
    }

    static $PNlExtlinkNewWindow;
    if (!isset($PNlExtlinkNewWindow)) {
        $PNlExtlinkNewWindow = pnModGetVar('Wiki', 'ExtlinkNewWindow');
    }
    static $PNlIntlinkNewWindow;
    if (!isset($PNlIntlinkNewWindow)) {
        $PNlIntlinkNewWindow = pnModGetVar('Wiki', 'IntlinkNewWindow');
    }


    // $bracketlink will start and end with brackets; in between
    // will be either a page name, a URL or both separated by a pipe.
    // ou bien du texte, si bbcode.

    // strip brackets and leading space
    preg_match("/(\[)\s*(.+?)\s*(\])/", $bracketlink, $match);

    if ( isset($match[3]) and ($match[3]==']') and ( ($match[2]=='b') or ($match[2]=='i') or ($match[2]=='/b') or ($match[2]=='/i') ) ) {
        $link[type] = "bbcode";
        $link[link] = "<".$match[2].">";
    } else {
        // match the contents
        preg_match("/([^|]+)(\|)?([^|]+)?/", $match[2], $matches);

        if (isset($matches[3])) {
            // named link of the form  "[some link name | http://blippy.com/]"
            $URL = trim($matches[3]);
            $linkname = trim($matches[1]);
            $linktype = 'named';
        } else {
            // unnamed link of the form "[http://blippy.com/] or [wiki page]"
            $URL = trim($matches[1]);
            $linkname = '';
            $linktype = 'simple';
        }
        if (preg_match("#^($PNAllowedProtocols):#", $URL)) {
            // if it's an image, embed it; otherwise, it's a regular link
            if (preg_match("/($PNInlineImages)$/i", $URL )) {
                $link['type'] = "image-$linktype";
                $link['link'] = PNLinkImage($URL, $linkname);
            } else {
                $link['type'] = "url-$linktype";
                $link['link'] = PNLinkURL($URL, $linkname, $PNlExtlinkNewWindow );
            }
        } elseif (preg_match("#^picture:(.*)#", $URL, $match)) {
            $link['type'] = "image-$linktype";
            $link['link'] = PNLinkImage("\"$match[1]\"",$linkname);
        } elseif (preg_match("#^photo:(.*)#", $URL, $match)) {
            $link['type'] = "image-$linktype";
            $link['link'] = PNLinkImage("\"$match[1]\"",$linkname);
        } elseif (preg_match("#^phpwiki:(.*)#", $URL, $match)) {
            $link['type'] = "url-wiki-$linktype";
            if(empty($linkname)) {
                $linkname = $URL;
            }
            $link['link'] = "<a href=\"$match[1]\">$linkname</a>";
        } elseif (preg_match("#^\d+$#", $URL)) {
            $link['type'] = "reference-$linktype";
            $link['link'] = $URL;
        } else {
            $link['type'] = "url-$linktype";
            $link['link'] = PNLinkURL($URL, $linkname, $PNlIntlinkNewWindow );
        }
    }
    return $link;
}

/**
 * Tokenise wiki string
 * @author Jim McDonald <Jim@mcdee.net>
 * @link http://www.mcdee.net
 * @param 'str' string to tokenise
 * @param 'pattern' patterns to match
 * @param 'orig' array of matches
 * @param 'ntokens' starting token number
 * @return string tokenised string
 */
function PNwikiTokenize($str, $pattern, &$orig, &$ntokens)
{
    static $PNFieldSeparator;
    if (!isset($PNFieldSeparator)) {
        $PNFieldSeparator = pnModGetVar('Wiki', 'FieldSeparator');
    }

    // Find any strings in $str that match $pattern and
    // store them in $orig, replacing them with tokens
    // starting at number $ntokens - returns tokenized string
    $new = '';
    while (preg_match("/^(.*?)($pattern)/", $str, $matches)) {
        $linktoken = $PNFieldSeparator . $PNFieldSeparator . ($ntokens++) . $PNFieldSeparator;
        $new .= $matches[1] . $linktoken;
        $orig[] = $matches[2];
        $str = substr($str, strlen($matches[0]));
    }
    $new .= $str;
    return $new;
}

/**
 * Tabulate string
 * @author Jim McDonald <Jim@mcdee.net>
 * @link http://www.mcdee.net
 * @param 'retour' string to tabulate
 * @return string HTML string
 */
function PNwikiInclude($retour)
{
    $retour = preg_replace("|(%%%%)(.*?)(%%%%)|", "\\2",  $retour);
//  not sure what this line does... there's no transform function and we're not an object either.... [markwest]
//    $retour = transform( $retour, $this->typeCoding );
    $retour = '<table border="0" cellpadding="8" cellspacing="1" width="100%"><tr><td align="left">' . $retour . "</td></tr></table>";
    return $retour;
}

/**
 * Transform text
 * @author Jim McDonald <Jim@mcdee.net>
 * @link http://www.mcdee.net
 * @param $args['objectid'] string or array of text items
 * @return mixed string or array of transformed text items
 */
function wiki_userapi_transform($args)
{
    // Get arguments from argument array
    extract($args);

    static $PNAllowedProtocols;
    if (!isset($PNAllowedProtocols)) {
        $PNAllowedProtocols = pnModGetVar('Wiki', 'AllowedProtocols');
    }

    // Argument check
    if (!isset($extrainfo)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return;
    }

    if (is_array($extrainfo)) {
        $transformed = array();
        foreach($extrainfo as $text) {
            if (preg_match("/('''|\t+\*|\t+1|\t+\s:|---|__|(\[[\w ]+\|))/", $text)) {
                $transformed[] = wiki_userapitransform($text);
            } else {
                $transformed[] = $text;
            }
        }
    } else {
        if (preg_match("/('''|\t+\*|\t+1|\t+\s:|---|__|(\[[\w ]+\|$PNAllowedProtocols))/", $extrainfo)) {
            $transformed = wiki_userapitransform($extrainfo);
        } else {
            $transformed = $extrainfo;
        }
    }

    return $transformed;
}

/**
 * Do the transform from Wiki to HTML
 * @author Jim McDonald <Jim@mcdee.net>
 * @link http://www.mcdee.net
 * @param $cContent Wiki content
 * @returns string transformed text
 */
function wiki_userapitransform($cContent)
{
    static $PNFieldSeparator;
    if (!isset($PNFieldSeparator)) {
        $PNFieldSeparator = pnModGetVar('Wiki', 'FieldSeparator');
    }
    static $PNlWithHtml;
    if (!isset($PNlWithHtml)) {
        $PNlWithHtml = pnModGetVar('Wiki', 'WithHTML');
    }
    static $PNAllowedProtocols;
    if (!isset($PNAllowedProtocols)) {
        $PNAllowedProtocols = pnModGetVar('Wiki', 'AllowedProtocols');
    }

    $html = "";

    if (strlen($cContent)==0) {
        return($cContent);
    }

    $aContent  = explode("\n", $cContent);

    $aContent = PNCookSpaces($aContent);

    // Loop over all lines of the page and apply transformation rules
    $numlines = count($aContent);
    for ($index = 0; $index < $numlines; $index++) {

        unset($tokens);
        unset($replacements);
        $ntokens = 0;
        $replacements = array();

        //      $tmpline = stripslashes($aContent[$index]);
        $tmpline = $aContent[$index];

        if (!strlen($tmpline) || $tmpline == "\r") {
            // this is a blank line, send <p>
            $html .= PNSetHTMLOutputMode("p", WIKI_ZERO_DEPTH, 0);
            continue;
        } elseif ($PNlWithHtml and preg_match("/(^\|)(.*)/", $tmpline, $matches)) {
            // HTML mode
            $html .= PNSetHTMLOutputMode("", WIKI_ZERO_LEVEL, 0);
            $html .= $matches[2];
            continue;
        }

        //////////////////////////////////////////////////////////
        // New linking scheme: links are in brackets. This will
        // emulate typical HTML linking as well as Wiki linking.

        // First need to protect [[.
        $oldn = $ntokens;
        $tmpline = PNwikiTokenize($tmpline, '\[\[', $replacements, $ntokens);
        while ($oldn < $ntokens)
            $replacements[$oldn++] = '[';

        // Now process the [\d+] links which are numeric references
        $oldn = $ntokens;
        $tmpline = PNwikiTokenize($tmpline, '\[\s*\d+\s*\]', $replacements, $ntokens);
        while ($oldn < $ntokens) {
            $num = (int) substr($replacements[$oldn], 1);
            if (! empty($embedded[$num]))
                $replacements[$oldn] = $embedded[$num];
            $oldn++;
        }

        // match anything else between brackets
        $oldn = $ntokens;
        $tmpline = PNwikiTokenize($tmpline, '\[.+?\]', $replacements, $ntokens);
        while ($oldn < $ntokens) {
            $link = PNParseAndLink($replacements[$oldn]);
            $replacements[$oldn] = $link['link'];
            $oldn++;
        }
        //////////////////////////////////////////////////////////
        // replace all URL's with tokens, so we don't confuse them
        // with Wiki words later. Wiki words in URL's break things.
        // URLs preceeded by a '!' are not linked

        $tmpline = PNwikiTokenize($tmpline, "!\b($PNAllowedProtocols):[^\s<>\[\]\"'()]*[^\s<>\[\]\"'(),.?]", $replacements, $ntokens);

        while ($oldn < $ntokens) {
            if($replacements[$oldn][0] == '!')
                $replacements[$oldn] = substr($replacements[$oldn], 1);
            else
                $replacements[$oldn] = PNLinkURL($replacements[$oldn]);
            $oldn++;
        }

        //////////////////////////////////////////////////////////
        // escape HTML metachars
//        $tmpline = str_replace('&', '&amp;', $tmpline);
//        $tmpline = str_replace('>', '&gt;', $tmpline);
//        $tmpline = str_replace('<', '&lt;', $tmpline);

        // four or more dashes to <hr>
        $tmpline = ereg_replace("^-{4,}",
                                '<hr noshade>',
                                $tmpline);


        // %%%% are image blocks
        if ( preg_match("|(%%%%)(.*?)(%%%%)|", $tmpline, $aContenu ))
        {
            $retour = PNwikiInclude($aContenu[0]);
            $retour = preg_replace("|(%%%%)(.*?)(%%%%)|", "\\2",  $retour);
            $retour = wiki_userapitransform($retour);
            $retour = '<table border="0" cellpadding="8" cellspacing="1" width="100%"><tr><td align="left">' . $retour . '</td></tr></table>';
            $tmpline = $retour.preg_replace("|(%%%%)(.*?)(%%%%)|",
                                            "",
                                            $tmpline);
        }

        // %%% are linebreaks
        if (strstr($tmpline,'%%%')) {
            // i dont want ' %%%' or '%%% '
            str_replace("%%% ","%%%",$tmpline);
            str_replace(" %%%","%%%",$tmpline);
            // i want to check i dont have '%%%<br />'
            str_replace("%%%<br />","%%%",$tmpline);
            $tmpline = str_replace('%%%',
                                '<br />',
                                 $tmpline);
            }

        // bold italics (old way)
        $tmpline = preg_replace("|(''''')(.*?)(''''')|",
                                "<strong><em>\\2</em></strong>",
                                $tmpline);

        // bold (old way)
        $tmpline = preg_replace("|(''')(.*?)(''')|",
                                "<strong>\\2</strong>",
                                $tmpline);

        // italics (old ways)
        $tmpline = preg_replace("|('')(.*?)('')|",
                                "<em>\\2</em>",
                                $tmpline);

        // bold
        $tmpline = preg_replace("|(___)(.*?)(___)|",
                                "<strong>\\2</strong>",
                                $tmpline);

        // italics
        $tmpline = preg_replace("|(__)(.*?)(__)|",
                                "<em>\\2</em>",
                                $tmpline);

        // bold italics
        $tmpline = preg_replace("|(_____)(.*?)(_____)|",
                                "<strong><em>\\2</em></strong>",
                                $tmpline);

        // center
        $tmpline = preg_replace("|(---)(.*?)(---)|",
                                "<center>\\2</center>",
                                $tmpline);
        // tag <PUB>
        //      $tmpline = str_replace("<PUB>",  impHtml() , $tmpline );

        //////////////////////////////////////////////////////////
        // unordered, ordered, and dictionary list  (using TAB)

        if (preg_match("/(^\t+)(.*?)(:\t)(.*$)/", $tmpline, $matches)) {
            // this is a dictionary list (<dl>) item
            $numtabs = strlen($matches[1]);
            $html .= PNSetHTMLOutputMode('dl', WIKI_NESTED_LEVEL, $numtabs);
            $tmpline = '';
            if(trim($matches[2]))
                $tmpline = '<dt>' . $matches[2];
            $tmpline .= '<dd>' . $matches[4];

        } elseif (preg_match("/(^\t+)(\*|\d+|#)/", $tmpline, $matches)) {
            // this is part of a list (<ul>, <ol>)
            $numtabs = strlen($matches[1]);
            if ($matches[2] == '*') {
                $listtag = 'ul';
            } else {
                $listtag = 'ol'; // a rather tacit assumption. oh well.
            }
            $tmpline = preg_replace("/^(\t+)(\*|\d+|#)/", "", $tmpline);
            $html .= PNSetHTMLOutputMode($listtag, WIKI_NESTED_LEVEL, $numtabs);
            $html .= '<li>';


            //////////////////////////////////////////////////////////
            // tabless markup for unordered, ordered, and dictionary lists
            // ul/ol list types can be mixed, so we only look at the last
            // character. Changes e.g. from "**#*" to "###*" go unnoticed.
            // and wouldn't make a difference to the HTML layout anyway.

            // unordered lists <UL>: "*"
        } elseif (preg_match("/^([#*]*\*)[^#]/", $tmpline, $matches)) {
            // this is part of an unordered list
            $numtabs = strlen($matches[1]);
            $tmpline = preg_replace("/^([#*]*\*)/", '', $tmpline);
            $html .= PNSetHTMLOutputMode('ul', WIKI_NESTED_LEVEL, $numtabs);
            $html .= '<li>';

            // ordered lists <OL>: "#"
        } elseif (preg_match("/^([#*]*\#)/", $tmpline, $matches)) {
            // this is part of an ordered list
            $numtabs = strlen($matches[1]);
            $tmpline = preg_replace("/^([#*]*\#)/", "", $tmpline);
            $html .= PNSetHTMLOutputMode('ol', WIKI_NESTED_LEVEL, $numtabs);
            $html .= '<li>';

            // definition lists <DL>: ";text:text"
        } elseif (preg_match("/(^;+)(.*?):(.*$)/", $tmpline, $matches)) {
            // this is a dictionary list item
            $numtabs = strlen($matches[1]);
            $html .= PNSetHTMLOutputMode('dl', WIKI_NESTED_LEVEL, $numtabs);
            $tmpline = '';
            if(trim($matches[2]))
                $tmpline = '<dt>' . $matches[2];
            $tmpline .= '<dd>' . $matches[3];


            //////////////////////////////////////////////////////////
            // remaining modes: preformatted text, headings, normal text
            // preformated mode was a pb. So ...
            //        } elseif (preg_match("/^\s+/", $tmpline)) {
            // this is preformatted text, i.e. <pre>
            //$html .= "????";
            //           $html .= PNSetHTMLOutputMode('pre', WIKI_ZERO_LEVEL, 0);

        } elseif (preg_match("/^(!{1,3})[^!]/", $tmpline, $whichheading)) {
            // lines starting with !,!!,!!! are headings
            if($whichheading[1] == '!') $heading = 'h3';
            elseif($whichheading[1] == '!!') $heading = 'h2';
            elseif($whichheading[1] == '!!!') $heading = 'h1';
            $tmpline = preg_replace("/^!+/", '', $tmpline);
            $html .= PNSetHTMLOutputMode($heading, WIKI_ZERO_LEVEL, 0);

        } else {
            // it's ordinary output if nothing else
            $html .= PNSetHTMLOutputMode('', WIKI_ZERO_LEVEL, 0);
        }

        ///////////////////////////////////////////////////////
        // Replace tokens
        for ($i = 0; $i < $ntokens; $i++)
            $tmpline = str_replace($PNFieldSeparator.$PNFieldSeparator.$i.$PNFieldSeparator, $replacements[$i], $tmpline);

        $html .= $tmpline . "\n";
    }
    $html .= PNSetHTMLOutputMode('', WIKI_ZERO_LEVEL, 0);

    return $html;
}

/**
 * Create a new link
 * @author Jim McDonald <Jim@mcdee.net>
 * @link http://www.mcdee.net
 * @param 'url' url for link
 * @param 'linktext' text for link
 * @param 'autreFenetre' breakout link into new window
 * @return mixed HTML string url if successful, error if false
 */
function PNLinkURL($url, $linktext='', $autreFenetre=false )
{
    if(ereg("[<>\"]", $url)) {
        return "<strong><em>BAD URL -- remove all of &lt;, &gt;, &quot;</em></strong>";
    }
    if(empty($linktext))
        $linktext = $url;
    if ($autreFenetre) {
        $target = " target=\"sb\"";
    } else {
        $target = "";
    }
    return "<a href=\"$url\"". $target.">$linktext</a>";
}

/**
 * Create an image link
 * @author Jim McDonald <Jim@mcdee.net>
 * @link http://www.mcdee.net
 * @param 'url' url for link
 * @param 'alt' ALT text
 * @return string HTML string
 */
function PNLinkImage($url, $alt='' )
{
    static $PNlExtlinkNewWindow;
    if (!isset($PNlExtlinkNewWindow)) {
        $PNlExtlinkNewWindow = pnModGetVar('Wiki', 'ExtlinkNewWindow');
    }
    static $PNlIntlinkNewWindow;
    if (!isset($PNlIntlinkNewWindow)) {
        $PNlIntlinkNewWindow = pnModGetVar('Wiki', 'IntlinkNewWindow');
    }

    if(ereg('[<>]', $url)) {
        return "<strong><em>BAD URL -- remove all of &lt;, &gt;, &quot;</em></strong>";
    }
    $link = '';

    $chaine = substr ($alt, 0, strpos($alt, '+'));
    if (!(empty($chaine))) {
        $link = substr ($alt, strpos($alt, '+') + 1);
        $alt =  substr ($alt, 0, strpos($alt, '+'));
    }


    $cRetour  = "\n";
    $cRetour .= "<!-- inclusion de la photo de l'article. -->\n";
    $cRetour .= "<table cellspacing=\"0\" cellpadding=\"5\" border=\"0\" align=\"left\">\n";
    $cRetour .= "<tr><td valign=\"top\">\n";

    if (!(empty($link))) {
        $cRetour .= "<a href=".$link ;
        if (strstr($link,"http://") and $PNlExtlinkNewWindow ) {
            $cRetour .= " target=\"_blank\" ";
        } elseif ( ($PNlIntlinkNewWindow) ) {
            $cRetour .= " target=\"_blank\" ";
        }
        $cRetour .= ">" ;
    }
    $cRetour .= "<img src=\"$url\" alt=\"$alt\">\n";
    if (!(empty($link))) {
        $cRetour .= "</a>" ;
    }
    $cRetour .= "</td></tr></table>\n";
    $cRetour .= "<!-- fin de l'inclusion de la photo de l'article. -->\n";
    return $cRetour;
}

/**
 * Converts spaces to tabs
 * @author Jim McDonald <Jim@mcdee.net>
 * @link http://www.mcdee.net
 * @param 'url' url for link
 * @param 'alt' ALT text
 * @return string HTML string
 */
function PNCookSpaces($pagearray)
{
    return preg_replace("/ {3,8}/", "\t", $pagearray);
}

/**
 * Define PNStack Class
 * @author Jim McDonald <Jim@mcdee.net>
 * @link http://www.mcdee.net
 */
class PNStack {
    var $items = array();
    var $size = 0;

    function push($item) {
        $this->items[$this->size] = $item;
        $this->size++;
        return true;
    }

    function pop() {
        if ($this->size == 0) {
            return false; // stack is empty
        }
        $this->size--;
        return $this->items[$this->size];
    }

    function cnt() {
        return $this->size;
    }

    function top() {
        if($this->size)
            return $this->items[$this->size - 1];
        else
            return '';
    }

}
// end class definition

// globalize it here cause pninclude_once.
$GLOBALS['PNstack'] =& new PNStack;

/*
   Wiki HTML output can, at any given time, be in only one mode.
   It will be something like Unordered List, Preformatted Text,
   plain text etc. When we change modes we have to issue close tags
   for one mode and start tags for another.

   $tag ... HTML tag to insert
   $tagtype ... WIKI_ZERO_LEVEL - close all open tags before inserting $tag
   WIKI_NESTED_LEVEL - close tags until depths match
   $level ... nesting level (depth) of $tag
   nesting is arbitrary limited to 10 levels
 */

/**
 * Sets HTML Output mode for Wiki
 * @author Jim McDonald <Jim@mcdee.net>
 * @link http://www.mcdee.net
 * @param 'tag' tag
 * @param 'tagtype' Wiki tag type
 * @param 'level' stack level
 * @return string
 */
function PNSetHTMLOutputMode($tag, $tagtype, $level)
{
    $retvar = '';

    if ($tagtype == WIKI_ZERO_LEVEL) {
        // empty the stack until $level == 0;
        if ($tag == $GLOBALS['PNstack']->top()) {
            return; // same tag? -> nothing to do
        }
        while ($GLOBALS['PNstack']->cnt() > 0) {
            $closetag = $GLOBALS['PNstack']->pop();
            $retvar .= "</$closetag>\n";
        }

        if ($tag) {
            $retvar .= "<$tag>\n";
            $GLOBALS['PNstack']->push($tag);
        }


    } elseif ($tagtype == WIKI_NESTED_LEVEL) {
        if ($level < $GLOBALS['PNstack']->cnt()) {
            // $tag has fewer nestings (old: tabs) than stack,
            // reduce stack to that tab count
            while ($GLOBALS['PNstack']->cnt() > $level) {
                $closetag = $GLOBALS['PNstack']->pop();
                if ($closetag == false) {
                    break;
                }
                $retvar .= "</$closetag>\n";
            }

            // if list type isn't the same,
            // back up one more and push new tag
            if ($tag != $GLOBALS['PNstack']->top()) {
                $closetag = $GLOBALS['PNstack']->pop();
                $retvar .= "</$closetag><$tag>\n";
                $GLOBALS['PNstack']->push($tag);
            }

        } elseif ($level > $GLOBALS['PNstack']->cnt()) {
            // we add the diff to the stack
            // stack might be zero
            while ($GLOBALS['PNstack']->cnt() < $level) {
                $retvar .= "<$tag>\n";
                $GLOBALS['PNstack']->push($tag);
                if ($GLOBALS['PNstack']->cnt() > 10) {
                    // arbitrarily limit tag nesting
                    pnSessionSetVar('errormsg', 'Stack bounds exceeded in SetHTMLOutputMode');
                }
            }

        } else { // $level == $PNstack->cnt()
            if ($tag == $GLOBALS['PNstack']->top()) {
                return; // same tag? -> nothing to do
            } else {
                // different tag - close old one, add new one
                $closetag = $GLOBALS['PNstack']->pop();
                $retvar .= "</$closetag>\n";
                $retvar .= "<$tag>\n";
                $GLOBALS['PNstack']->push($tag);
            }
        }


    } else { // unknown $tagtype
        pnSessionSetVar('errormsg', 'Passed bad tag type value in SetHTMLOutputMode');
    }
    return $retvar;
}

?>