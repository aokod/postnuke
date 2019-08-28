<?php
// $Id: function.articleadminlinks.php 16748 2005-09-05 09:48:00Z markwest $
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
 * @version      $Id: function.articleadminlinks.php 16748 2005-09-05 09:48:00Z markwest $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to display edit and delete links for a news article
 * 
 * Example
 * <!--[articleadminlinks sid="1" start="[" end="]" seperator="|" class="pn-sub"]-->
 * 
 * @author       Mark West
 * @since        20/10/03
 * @see          function.articleadminlinks.php::smarty_function_articleadminlinks()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @param        integer     $sid         article id
 * @param        string      $start       start string
 * @param        string      $end         end string
 * @param        string      $seperator   link seperator
 * @param        string      $class       CSS class
 * @return       string      the results of the module function
 */
function smarty_function_articleadminlinks($params, &$smarty) 
{
    extract($params); 
	unset($params);

	// get the info template var
	$info = $smarty->get_template_vars('info');

    if (!isset($sid)) {
		$sid = $info['sid'];
    }    
    
	// set some defaults
	if (!isset($start)) {
		$start = '[';
	}
	if (!isset($end)) {
		$end = ']';
	}
	if (!isset($seperator)) {
		$seperator = '|';
	}
    if (!isset($class)) {
	    $class = 'pn-sub';
	}

    $articlelinks = '';
    if (pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_EDIT)) {
		if (ereg('0.7', _PN_VERSION_NUM)) {
	        $articlelinks .= "<span class=\"$class\"> $start <a href=\"admin.php?module=AddStory&amp;op=EditStory&amp;sid=$sid\">" . _EDIT . "</a>";
		} else {
	        $articlelinks .= "<span class=\"$class\"> $start <a href=\"" . pnVarPrepHTMLDisplay(pnModURL('News', 'admin', 'modify', array('sid' => $sid))) . "\">" . _EDIT . "</a>";
		}
        if (pnSecAuthAction(0, 'Stories::Story', "$info[aid]:$info[cattitle]:$info[sid]", ACCESS_DELETE)) {
			if (ereg('0.7', _PN_VERSION_NUM)) {
	            $articlelinks .= " $seperator <a href=\"admin.php?module=AddStory&amp;op=RemoveStory&amp;sid=$sid\">" . _DELETE . "</a>";
			} else {
	            $articlelinks .= " $seperator <a href=\"" . pnVarPrepHTMLDisplay(pnModURL('News', 'admin', 'modify', array('sid' => $sid))) . "\">" . _DELETE . "</a>";
			}
        } 
        $articlelinks .= " $end</span>\n";
    } 

    return $articlelinks;
}

?>