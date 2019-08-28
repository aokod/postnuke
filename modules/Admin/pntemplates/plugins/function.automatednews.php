<?php
// $Id: function.automatednews.php 17446 2006-01-03 09:46:50Z markwest $
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
 * @version      $Id: function.automatednews.php 17446 2006-01-03 09:46:50Z markwest $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to display the any programmed stories
 * 
 * <!--[automatednews]-->
 * 
 * @author       Mark West
 * @since        12/12/04
 * @see          function.automatednews.php::smarty_function_automatednews()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @todo         update to call news user API (after .760)
 * @return       string      output of the current poll
 */
function smarty_function_automatednews($params, &$smarty) 
{
    extract($params); 
	unset($params);

	if (!pnModAvailable('News')) {
		return;
	}

	$dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (pnSecAuthAction(0, 'Stories::Story', '::', ACCESS_EDIT)) {
        OpenTable();
        echo  '<h2>'._ADMINAUTOMATEDARTICLES.'</h2>'."\n";
        $count = 0;
        $column = &$pntable['autonews_column'];
		$query = "SELECT $column[anid], $column[catid], $column[aid], $column[title], $column[time], $column[alanguage]
					FROM $pntable[autonews]
					ORDER BY $column[time] ASC";
		$result =& $dbconn->Execute($query);
        if ($result->EOF) {
            echo '<em>'._ADMINNOAUTOARTICLES.'</em>'."\n";
        } else {
            echo '<table border="1" width="100%">'."\n";
            while(list($anid,$catid,$said,$title,$time,$alanguage) = $result->fields) {
                echo '<tr>'."\n";
                if ($alanguage == '') $alanguage = 'x_all';
                if ($count == 0) $count = 1;
                $time = ereg_replace(' ', '@', $time);
                if ($catid == 0) {
                    // Default category
                    $cattitle = _ARTICLES;
                } else {
                    $catcolumn = &$pntable['stories_cat_column'];
					$catquery = "SELECT $catcolumn[title] 
								FROM $pntable[stories_cat]
								WHERE $catcolumn[catid] = '".(int)pnVarPrepForStore($catid)."'";
                    $catresult =& $dbconn->Execute($catquery);
                    list($cattitle) = $catresult->fields;
                }
                if (pnSecAuthAction(0, 'Stories::Story', "$said:$cattitle:", ACCESS_EDIT)) {
                    echo '<td align="right">(<a href="admin.php?module=AddStory&op=autoEdit&amp;anid='.$anid.'">'._EDIT.'</a>';
                    if (pnSecAuthAction(0, 'Stories::Story', "$said:$cattitle:", ACCESS_DELETE)) {
	                    echo '-<a href="admin.php?module=AddStory&op=autoDelete&amp;anid='.$anid.'">'._DELETE.'</a>'."\n";
                    }
                    echo ')</td>';
                }
                echo  '<td style="width:100%">&nbsp;'.pnVarPrepForDisplay($title).'&nbsp;('.pnVarPrepForDisplay($cattitle).')&nbsp;</td>'."\n"
                     .'<td align="center">&nbsp;'.language_name($alanguage).'&nbsp;</td>'."\n"
                     .'<td>&nbsp;'.$time.'&nbsp;</td>'."\n"
                     .'</tr>'."\n";
                $result->MoveNext();
            }
            echo '</table>'."\n";
        }
        CloseTable();
    }
	return;
}

?>