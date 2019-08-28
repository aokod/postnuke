<?php
// $Id: function.search.php 16722 2005-08-27 12:35:10Z  $
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
 * This file is a plugin for pnRender, the PostNuke implementation of Smarty
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   Xanthia
 * @version      $Id: function.search.php 16722 2005-08-27 12:35:10Z  $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to display a search form
 * 
 * Available parameters:
 *  - active            a comma-separated list of modules to be searched.
 *  - bool              the boolean operation to be performed (AND or OR)
 *  - label             the label to show before the search box
 *  - button            the text to be displayed on the search button
 *  - size              the size of the input box
 *  - value             the default value of the input box
 *  - search_tabindex   the tabindex of the Search box (defaults to 0)
 *  - button_tabindex   the tabindex of the Search button (defaults to 0)
 *  - search_accesskey  the access key of the Search box
 *  - button_accesskey  the access key of the Search button
 *  - class             the CSS class to assign to the form
 * 
 * Example
 * <!--[search]-->
 * 
 * <!--[pnml name="_SEARCH" assign="search_label"]-->
 * <!--[search active="faqs, stories" label=$search_label class="pnsearchform"]-->
 * 
 * Note
 * IE (incorrectly) treats a form as a block element rather than an inline 
 * element. This, if you want the search box to display as expected on IE, 
 * you should use a custom CSS class to style the search box (use the class
 * parameter of this plugin), and add 
 *   display: inline;
 * to this class in your style sheet. 
 * 
 * @author       Mark West
 * @author       Jörg Napp
 * @since        23/10/03
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the search box
 */
function smarty_function_search($params, &$smarty) 
{
    extract($params);
	unset($params);

    // assign the class, if set
    if (isset($class)) {
        $class = 'class="' . $class .'"';
    } else {
		$class = '';
	}

    // Staring the search box    
    $searchbox  = "<form $class action=\"index.php\" method=\"post\">\n";
    $searchbox .= " <div>\n";
    $searchbox .= "  <input type=\"hidden\" name=\"name\" value=\"Search\" />\n";
    $searchbox .= "  <input type=\"hidden\" name=\"action\" value=\"search\" />\n";
    $searchbox .= "  <input type=\"hidden\" name=\"overview\" value=\"1\" />\n";

    if (!isset($bool) || $bool != 'OR') {
        $bool = 'AND';
    }
    $searchbox .= "  <input type=\"hidden\" name=\"bool\" value=\"$bool\" />\n";
    
    
    // default value for active modules (this is cloned from the "old" standard)				
    if (!isset($active)) {
        $active = 'comments, downloads, faqs, reviews, sections, stories, users, weblinks';
    }
    // Loop through the active modules and assign them
    $active_modules = split(",", $active);
    foreach($active_modules as $active_module) {
        $active_module = trim($active_module);
        $searchbox .= "  <input type=\"hidden\" name=\"active_$active_module\" value=\"1\" />\n";
    }


    if (isset($label)) {
		$searchbox .= "  <label for=\"search_xte_plugin\">$label</label>\n";
    }       
    if (!isset($size)) {
        $size = '12';
    }
    if (!isset($search_tabindex)) {
        $search_tabindex = '0';
    } else {
        $search_tabindex = (int)$search_tabindex;
    }
    if (isset($search_accesskey)) {
        $search_accesskey = "accesskey=\"$search_accesskey\" ";
    }
	if (!isset($value)) {
		$value = '';
	}
	if (!isset($search_accesskey)) {
		$search_accesskey = '';
	}

	$searchbox .= "  <input id=\"search_xte_plugin\" type=\"text\" name=\"q\" value=\"$value\" size=\"$size\" tabindex=\"$search_tabindex\" $search_accesskey />\n";

    if (isset($button)) {
        if (!isset($button_tabindex)) {
            $button_tabindex = '0';
        } else {
            $button_tabindex = (int)$button_tabindex;
        }
        if (isset($button_accesskey)) {
            $button_accesskey = "accesskey=\"$button_accesskey\" ";
        }
		$searchbox .="  <input type=\"submit\" value=\"$button\" tabindex=\"$button_tabindex\" $button_accesskey />\n";
	}

    $searchbox .= " </div>\n";
    $searchbox .= "</form>\n";

    return $searchbox;
}
?>