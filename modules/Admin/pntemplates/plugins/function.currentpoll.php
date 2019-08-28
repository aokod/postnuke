<?php
// $Id: function.currentpoll.php 16636 2005-08-13 22:12:47Z chestnut $
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
 * @version      $Id: function.currentpoll.php 16636 2005-08-13 22:12:47Z chestnut $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */


/**
 * Smarty function to display the currently active poll
 *
 * <!--[currentpoll]-->
 *
 * @author       Mark West
 * @since        12/12/04
 * @see          function.currentpoll.php::smarty_function_currentpoll()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @todo         update to call polls user API (after .760)
 * @return       string      output of the current poll
 */
function smarty_function_currentpoll($params, &$smarty)
{
    extract($params);
  unset($params);

  if (!pnModAvailable('Polls')) {
    return;
  }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column = &$pntable['poll_desc_column'];
  $myquery = "SELECT $column[polltitle], $column[pollid]
        FROM $pntable[poll_desc]
        ORDER BY $column[pollid] DESC";
    $result =& $dbconn->SelectLimit($myquery,1);
    list($title, $pid) = $result->fields;
    if (pnSecAuthAction(0, 'Polls::', "$title::$pid", ACCESS_EDIT)) {
        OpenTable();
        echo '<div style="text-align:center">'._ADMINCURRENTPOLL.': '.pnVarPrepForDisplay($title).'</div>';
        CloseTable();
    }
    return null;
}

?>