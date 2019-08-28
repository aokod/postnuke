<?php 
// $Id: function.keywords.php 14076 2004-07-20 21:20:01Z markwest $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2002 by the PostNuke Development Team.
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

/**
 * Xanthia plugin
 * 
 * This file is a plugin for Xanthia, the PostNuke Theme engine
 * 
 * @package        Xanthia_Templating_Environment
 * @subpackage     Xanthia
 * @version        $Id: function.keywords.php 14076 2004-07-20 21:20:01Z markwest $
 * @author         The PostNuke development team 
 * @link           http://www.postnuke.com The PostNuke Home Page
 * @copyright      Copyright (C) 2004 by the PostNuke Development Team
 * @license        http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

/**
 * Xanthia function to get the meta keywords
 * 
 * This function will take the contents of the page and transfer it
 * into a keyword list. If stopwords are defined, they are filtered out. 
 * The keywords are sorted by cont.
 * As a default, the whole page contents are taken as a base for keyword
 * generation. If set, the contents of "contents" are taken. 
 * Beware that the function always returns the site keywords if "generate
 * meta keywords" is turned off. 
 * PLEASE NOTE: This function adds additional overhead when dynamic keyword
 * generation is turned on. You should use Xanthia page caching in this case.
 * 
 * available parameters:
 *  - contents    if set, this wil be taken as a base for the keywords
 *  - assign      if set, the keywords will be assigned to this variable
 * 
 * Example
 * <meta name="KEYWORDS" content="<!--[keywords]-->">
 * 
 * @author   Jörg Napp 
 * @since    03. Feb. 04
 * @param    array    $params     All attributes passed to this function from the template
 * @param    object   $smarty     Reference to the Smarty object
 * @return   string   the keywords
 */
function smarty_function_keywords($params, &$smarty)
{
    if (pnConfigGetVar('dyn_keywords') == 1) {
        if (isset($params['contents'])) {
            $pagecontent = $params['contents'];
        } else {
            $pagecontent = $smarty->_tpl_vars['maincontent'];
        }
    
        // get the contents of the page.
        $pagecontent = strtolower(strip_tags(html_entity_decode($pagecontent)));
        
        // strip the contents to an array at each non-letter 
        // this might be an issue for languages with other charsets.  
        $keywords = preg_split ('/\W/', $pagecontent);
        unset ($keywords['']);
        
        // sort the array by the most used keys first
        $keywords = array_count_values($keywords);
        arsort($keywords);
        
        // get back the keywords from the indexes
        $keywords = array_keys($keywords);
        
        // remove stopwords
        // not correct here: The _user language_ is not relevant, only
        // the language of the document. How to get this?
        $stopword_file = dirname(__FILE__) . '/stopwords/' . pnUserGetLang() . '.txt';
        if (file_exists($stopword_file)) {
            $stopwords = file($stopword_file);
            $stopwords = array_map('rtrim', $stopwords);
            $keywords = array_diff($keywords, $stopwords);
        } 
    
        // make it a comma-separated string
        $keywords = implode(',', $keywords);
    } else {
        $keywords = pnConfigGetVar('metakeywords');
    }
    
    if (isset($params['assign'])) {
        $smarty->assign($params['assign'], $keywords);
    } else {
        return $keywords;
    }
} 


if (!function_exists('html_entity_decode')) {
    /**
     * html_entity_decode()
     * 
     * Convert all HTML entities to their applicable characters
     * This function is a fallback if html_entity_decode isn't defined
     * in the PHP version used (i.e. PHP < 4.3.0). 
     * Please note that this function doesn't support all parameters
     * of the original html_entity_decode function. 
     * 
     * @param  string $string the this function converts all HTML entities to their applicable characters from string.
     * @return the converted string
     * @link http://php.net/html_entity_decode The documentation of html_entity_decode
     **/
    function html_entity_decode($string)
    {
        $trans_tbl = get_html_translation_table(HTML_ENTITIES);
        $trans_tbl = array_flip($trans_tbl);
        return (strtr($string, $trans_tbl));
    }     
}



?>
