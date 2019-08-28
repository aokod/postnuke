<?php
// $Id: function.articles.php 17464 2006-01-04 22:30:25Z landseer $
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
 * @version      $Id: function.articles.php 17464 2006-01-04 22:30:25Z landseer $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to display the any programmed stories
 * 
 * <!--[articles]-->
 * 
 * @author       Mark West
 * @since        12/12/04
 * @see          function.articles.php::smarty_function_articles()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @todo         update to call news user API (after .760)
 * @return       string      output of the current poll
 */
function smarty_function_articles($params, &$smarty) 
{
    extract($params); 
	unset($params);

	if (!pnModAvailable('News')) {
		return;
	}

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $admart = pnConfigGetVar('admart');

    if (pnSecAuthAction(0, 'Stories::Story', '::', ACCESS_EDIT)) {
        OpenTable();
        echo  '<h2>'._LAST.' '.pnVarPrepForDisplay($admart).' '._ARTICLES.'</h2>'."\n"
             .'<table border="1" width="100%">';
        $storiescolumn = &$pntable['stories_column'];
        $topicscolumn = &$pntable['topics_column'];
        if (strcmp(pnConfigGetVar('dbtype'), 'oci8') == 0)   {
            $myquery = "SELECT $storiescolumn[sid],
                               $storiescolumn[cid],
                               $storiescolumn[aid],
                               $storiescolumn[title],
                               $storiescolumn[time],
                               $storiescolumn[topic],
                               $storiescolumn[informant],
                               $storiescolumn[alanguage],
                               $topicscolumn[topicname]
                        FROM $pntable[stories], $pntable[topics]
                        WHERE  $storiescolumn[topic]=$topicscolumn[topicid](+)
                        ORDER BY  $storiescolumn[time] DESC LIMIT $admart";
        } else {
            $myquery = "SELECT $storiescolumn[sid],
                               $storiescolumn[cid],
                               $storiescolumn[aid],
                               $storiescolumn[title],
                               $storiescolumn[time],
                               $storiescolumn[topic],
                               $storiescolumn[informant],
                               $storiescolumn[alanguage],
                               $topicscolumn[topicname]
                        FROM $pntable[stories]
                        LEFT JOIN $pntable[topics] ON $storiescolumn[topic]=$topicscolumn[topicid]
                        ORDER BY  $storiescolumn[time] DESC LIMIT $admart";
        }
        $result =& $dbconn->Execute($myquery);
        while(list($sid, $cid, $said, $title, $time, $topic, $informant, $alanguage,$topicname) = $result->fields) {
            if ($alanguage=='') {
                $alanguage = 'x_all';
            }
            if ($title == "") {
                $title = '- No title -';
            }
            echo  '<tr>'."\n"
                 .'<td align="right"><strong>'.pnVarPrepForDisplay($sid).'</strong></td>'
                 .'<td align="left" style="width:100%"><a href="modules.php?name=News&amp;file=article&amp;sid='.$sid.'">'.pnVarPrepForDisplay($title).'</a></td>'."\n"
                 .'<td align="center">'.language_name($alanguage).'</td>'."\n"
                 .'<td align="right">'.pnVarPrepForDisplay($topicname).'</td>'."\n";
            if ($cid == 0) {
                // Default category
                $cattitle = _ARTICLES;
            } else {
                $catcolumn = &$pntable['stories_cat_column'];
                //$catquery = buildSimpleQuery('stories_cat', array('title'), "$catcolumn[catid] = $cid");
				$catquery = "SELECT $catcolumn[title] 
							FROM $pntable[stories_cat]
							WHERE $catcolumn[catid] = '".(int)pnVarPrepForStore($cid)."'";
                $catresult =& $dbconn->Execute($catquery);
                list($cattitle) = $catresult->fields;
            }
            if (pnSecAuthAction(0, 'Stories::Story', "$said:$cattitle:", ACCESS_EDIT)) {
                echo '<td align="right">(<a href="admin.php?module=AddStory&amp;op=EditStory&amp;sid='.$sid.'">'._EDIT.'</a>';
                if (pnSecAuthAction(0, 'Stories::Story', "$said:$cattitle:", ACCESS_DELETE)) {
                    echo '-<a href="admin.php?module=AddStory&amp;op=RemoveStory&amp;sid='.$sid.'">'._DELETE.'</a>';
                }
                echo ')</td>';
            } else {
                echo '<td>&nbsp;</td>';
            }
            echo '</tr>'."\n";
            $result->MoveNext();
        }
        echo '</table>'."\n";
        if (pnSecAuthAction(0, 'Stories::Story', '::', ACCESS_EDIT)) {
            echo  '<div style="text-align:center">'."\n"
                 .'<form action="admin.php" method="post"><div>'."\n"
                 .'<input type="hidden" name="module" value="AddStory" />'."\n"
                 ._ADMINSTORYID.': <input type="text" name="sid" size="10" />'."\n"
                 .'<select name="op">'."\n"
                 .'<option value="EditStory" selected="selected">'._EDIT.'</option>'."\n";
            if (pnSecAuthAction(0, 'Stories::Story', '::', ACCESS_DELETE)) {
                echo '<option value="RemoveStory">'._DELETE.'</option>'."\n";
            }
            echo  '</select>'."\n"
                 .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
                 .'<input type="submit" value="'._GO.'" />'."\n"
				 .'</div>'."\n"
                 .'</form>'."\n"
                 .'</div>'."\n";
        }
        CloseTable();
    }
	return;
}

?>