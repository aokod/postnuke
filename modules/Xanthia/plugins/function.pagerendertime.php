<?php 
// $Id: function.pagerendertime.php 16722 2005-08-27 12:35:10Z  $
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
 * @package        Xanthia_Templating_Environment
 * @subpackage     Xanthia
 * @version        $Id: function.pagerendertime.php 16722 2005-08-27 12:35:10Z  $
 * @author         The PostNuke development team 
 * @link           http://www.postnuke.com The PostNuke Home Page
 * @copyright      Copyright (C) 2004 by the PostNuke Development Team
 * @license        http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

/**
 * Smarty function to get the site's page render time
 * 
 * available parameters:
 *  - assign      if set, the message will be assigned to this variable
 * 
 * Example
 * <!--[pagerendertime]--> outputs 'Page created in 0.18122792243958 seconds.'
 * 
 * <!--[pagerendertime round=2]--> outputs 'Page created in 0.18 seconds.'
 *
 * @author   Mark West
 * @since    08/02/04
 * @param    array    $params     All attributes passed to this function from the template
 * @param    object   $smarty     Reference to the Smarty object
 * @param    string   $round      format to apply to the number (based on the round php function)
 * @return   string   the page render time in seconds
 * @todo change to ML as language is hardcoded (as per .726).
 */
function smarty_function_pagerendertime($params, &$smarty)
{
    // get the parameters
    extract($params); 
	unset($params['round']);

	// show time to render
    if ($GLOBALS['pndebug']['pagerendertime']){

		// calcultate time to render
		$mtime = explode(" ",microtime());
		$dbg_endtime = $mtime[1] + $mtime[0];
		$dbg_totaltime = ($dbg_endtime - $GLOBALS['dbg_starttime']);
	
		// log time to render
		if ($GLOBALS['pndebug']['debug']){
			$GLOBALS['dbg']->v($dbg_totaltime,"Page created in (seconds)");
		}

		if (isset($round)) {
			$dbg_totaltime = round($dbg_totaltime, $round);
		}

	    if (isset($params['assign'])) {
			$smarty->assign('rendertime', $dbg_totaltime);
		} else {
			$message = '<div class="pn-sub" style="text-align:center;">Page created in  ' . $dbg_totaltime . ' seconds.</div>';
			return $message;
		}			
	}		

} 

?>