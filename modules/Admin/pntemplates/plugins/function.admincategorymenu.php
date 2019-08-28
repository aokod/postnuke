<?php
// $Id: function.admincategorymenu.php 17382 2005-12-25 10:55:50Z landseer $
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
 * Admin Module
 *
 * @package      PostNuke_System_Modules
 * @subpackage   Admin
 * @version      $Id: function.admincategorymenu.php 17382 2005-12-25 10:55:50Z landseer $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */


/**
 * Smarty function to display the category menu for admin links. This also adds a link to
 * navtabs.css to the global additional_headers array.
 *
 * Admin
 * <!--[admincategorymenu]-->
 *
 * @author       Frank Schummertz
 * @since        16.01.2005
 * @see          function.admincategorymenu.php::smarty_function_admincategoreymenu()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @param        int         xhtml        if set, the link to the navtabs.css will be xhtml compliant
 * @return       string      the results of the module function
 */
function smarty_function_admincategorymenu($params, &$smarty)
{
    extract($params);
	unset($params);

	$stylesheet = pnModGetVar('Admin', 'modulestylesheet');
    // add an additional header to include the navtabs.css styleset
	global $additional_header;
    $xhtml = (isset($xhtml)) ? ' /' : '';
	if (file_exists("modules/Admin/pnstyle/$stylesheet")) {
		$additional_header[] = "<link rel=\"stylesheet\" href=\"modules/Admin/pnstyle/$stylesheet\" type=\"text/css\"" . $xhtml . ">";
	} else if (file_exists("system/Admin/pnstyle/$stylesheet")) {
		$additional_header[] = "<link rel=\"stylesheet\" href=\"system/Admin/pnstyle/$stylesheet\" type=\"text/css\"" . $xhtml . ">";
	}
    $cid = pnSessionGetVar('lastcid');
    if(empty($cid)) {
        $cid = pnModGetVar('Admin', 'startcategory');
    }

    // check version number
    // fix for bug #2121
    $modinfo = pnModGetInfo(pnModGetIDFromName('Admin'));
    if(version_compare($modinfo['version'], '1.0') == -1) {
        return;
    }
    return pnModFunc('Admin', 'admin', 'categorymenu', array('cid' => $cid));
}

?>