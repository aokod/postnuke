<?php
// File: $Id: menu.php 20518 2006-11-13 10:10:40Z markwest $
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
// Original Author of file: Jim McDonald
// Purpose of file: Display menu, with lots of options
// ----------------------------------------------------------------------

if (strpos($_SERVER['PHP_SELF'], 'menu.php')) {
	die ("You can't access this file directly...");
}

$blocks_modules['menu'] = array(
    'func_display' => 'blocks_menu_block',
    'func_edit' => 'blocks_menu_select',
    'func_update' => 'blocks_menu_update',
    'text_type' => 'Menu',
    'text_type_long' => 'Generic menu',
    'allow_multiple' => true,
    'form_content' => false,
    'form_refresh' => false,
//  'support_xhtml' => true,
    'show_preview' => true
);

pnSecAddSchema('Menublock::', 'Block title:Link name:');

function blocks_menu_block($row)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Generic check
    if (!pnSecAuthAction(0, 'Menublock::', "$row[title]::", ACCESS_READ)) {
        return;
    }

    // Break out options from our content field
    $vars = pnBlockVarsFromContent($row['content']);

    // Display style
    // style = 1 - simple list
    // style = 2 - drop-down list

    // Title
    $block['title'] = $row['title'];

    // Styling
    if (empty($vars['style']) || !is_numeric($vars['style'])) {
        $vars['style'] = 1;
    }
    $block['content'] = startMenuStyle($vars['style']);

    $content = 0;
	$waiting = 0;

    // nkame: must start with some blank line, otherwise we're not able to
    // chose the first option in case of a drop-down menu.
    // a better solution would be to detect where we are, and adjust the selected
    // option in the list, and only add a blank line in case of no recognition.
    if($vars['style'] == 2)
        $block['content'] .= addMenuStyledUrl($vars['style'], '', '', '');

    // Content
    if (!empty($vars['content'])) {
        $contentlines = explode('LINESPLIT', $vars['content']);
        foreach ($contentlines as $contentline) {
            list($url, $title, $comment) = explode('|', $contentline);
            if (pnSecAuthAction(0, 'Menublock::', "$row[title]:$title:", ACCESS_READ)) {
                $block['content'] .= addMenuStyledUrl($vars['style'], $title, $url, $comment);
                $content = 1;
            }
        }
    }

    // Modules
    if($vars['displaymodules']==1) {
        $mods = pnModGetUserMods();

        // Separate from current content, if any
        if(is_array($mods) && count($mods)>0) {
            if ($content == 1) {
                $block['content'] .= addMenuStyledUrl($vars['style'], '', '', '');
            }

            foreach($mods as $mod) {
                if (pnSecAuthAction(0, "$mod[name]::", '::', ACCESS_OVERVIEW)) {
                    if (pnSecAuthAction(0, 'Menublock::', "$row[title]:$mod[name]:", ACCESS_READ)) {
                        switch($mod['type']) {
                            case 1:
                                $block['content'] .= addMenuStyledUrl($vars['style'],
                                                                      $mod['displayname'],
                                                                      'index.php?name='.$mod['directory'],
                                                                      $mod['description']);
                                $content = 1;
                                break;
                            case 2:
                                $block['content'] .= addMenuStyledUrl($vars['style'],
                                                                      $mod['displayname'],
                                                                      pnModURL($mod['name'], 'user', 'main'),
                                                                      $mod['description']);
                                $content = 1;
                                break;
		    			}
                    }
                }
            }
        }
    }

    // Waiting content
    if($vars['displaywaiting']==1) {
        $waiting = '';
        if (pnSecAuthAction(0, "Stories::Story", '::', ACCESS_ADD) && pnModAvailable('News')) {
            $result =& $dbconn->Execute("SELECT count(1) FROM $pntable[queue]
                                      WHERE {$pntable['queue_column']['arcd']}=0");
            if ($dbconn->ErrorNo() == 0) {
                list($qnum) = $result->fields;
                $result->Close();
                if ($qnum) {
                    $waiting .= addMenuStyledUrl($vars['style'], _SUBMISSIONS.": $qnum", 'admin.php?module=AddStory&op=submissions', '');
                }
            }
        }

        if (pnSecAuthAction(0, "Reviews::", '::', ACCESS_ADD) && pnModAvailable('Reviews')) {
            $result =& $dbconn->Execute("SELECT count(1) FROM $pntable[reviews_add]");
            if ($dbconn->ErrorNo() == 0) {
                list($rnum) = $result->fields;
                $result->Close();
                if ($rnum) {
                    $waiting .= addMenuStyledUrl($vars['style'], _WREVIEWS.": $rnum", 'admin.php?module=Reviews&op=main', '');
                }
            }
        }

        if (pnSecAuthAction(0, "Web Links::Link", '::', ACCESS_ADD) && pnModAvailable('Web_Links')) {
            $result =& $dbconn->Execute("SELECT count(1) FROM $pntable[links_newlink]");
            if ($dbconn->ErrorNo() == 0) {
                list($lnum) = $result->fields;
                $result->Close();
                if ($lnum) {
                    $waiting .= addMenuStyledUrl($vars['style'], _WLINKS.": $lnum", 'admin.php?module=Web_Links&op=main', '');
                }
            }
        }

        if (pnSecAuthAction(0, "Downloads::Item", '::', ACCESS_ADD) && pnModAvailable('Downloads')) {
            $result =& $dbconn->Execute("SELECT count(1) FROM $pntable[downloads_newdownload]");
            if ($dbconn->ErrorNo() == 0) {
                list($dnum) = $result->fields;
                $result->Close();
                if ($dnum) {
                    $waiting .= addMenuStyledUrl($vars['style'], _WDOWNLOADS.": $dnum", 'admin.php?module=Downloads&op=main', '');
                }
            }
        }

        if (pnSecAuthAction(0, "FAQ::", '::', ACCESS_ADD) && pnModAvailable('FAQ')) {
            $faqcolumn = &$pntable['faqanswer_column'];
            $result =& $dbconn->Execute("SELECT count(1) FROM $pntable[faqanswer] WHERE $faqcolumn[answer]=''");
            if ($dbconn->ErrorNo() == 0) {
                list($fnum) = $result->fields;
                $result->Close();
                if ($fnum) {
                    $waiting .= addMenuStyledUrl($vars['style'], _FQUESTIONS.": $fnum", 'admin.php?module=FAQ&op=FaqCatUnanswered', '');
                }
            }
        }

        if($waiting<>"") {
            $block['content'] .= addMenuStyledUrl($vars['style'], '<strong>' . _WAITINGCONT. '</strong>', '', '');
            $block['content'] .= $waiting;
        }
    }

    // Styling
    $block['content'] .= endMenuStyle($vars['style']);

    if ($content || $waiting) {
        $row['title'] = $block['title'];
        $row['content'] = $block['content'];
        return themesideblock($row);
    }
}


function blocks_menu_select($row)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Break out options from our content field
    $vars = pnBlockVarsFromContent($row['content']);
    $row['content'] = '';

    // Defaults
    if (empty($vars['style']) || !is_numeric($vars['style'])) {
        $vars['style'] = 1;
    }

    // What style of menu
    $output = '<tr><td>'._MENU_FORMAT.'</td><td></td></tr>';

    $output .= '<tr><td><label for="menu_list">'._MENU_AS_LIST.'</label>:</td><td><input type="radio" id="menu_list" name="style" value="1"';
    if ($vars['style'] == 1) {
        $output .= ' checked="checked"';
    }
    $output .= ' /></td></tr><tr><td><label for="menu_dropdown">'._MENU_AS_DROPDOWN.'</label>:</td><td><input type="radio" id="menu_dropdown" name="style" value="2"';
    if ($vars['style'] == 2) {
        $output .= ' checked="checked"';
    }
    $output .= ' /></td></tr>';

    // What to display
    $output .= '<tr><td><h2>'._DISPLAY.'</h2></td><td></td></tr>';

    $output .= '<tr><td><label for="menu_modules">'._MENU_MODULES.'</label>:</td><td><input type="checkbox" id="menu_modules" value="1" name="displaymodules"';
    if (!empty($vars['displaymodules'])) {
        $output .= ' checked="checked"';
    }

    $output .= ' /></td></tr><tr><td><label for="waiting">'._WAITINGCONT.'</label>:</td><td><input type="checkbox" id="waiting" value="1" name="displaywaiting"';
    if (!empty($vars['displaywaiting'])) {
        $output .= ' checked="checked"';
    }
    $output .= ' /></td></tr>';

    // Content
    $c=1;
    $output .= '</table><table>';
    $output .= '<tr><td valign="top"><h2>'._MENU_CONTENT.'</h2></td><td><table border="1"><tr>
	<td align="center"><strong>'._TITLE.'</strong></td>
	<td align="center"><strong>'._URL.'</strong></td>
	<td align="center"><strong>'._MENU_DESCRIPTION.'&nbsp;</strong><span class="pn-sub"><strong>('._OPTIONAL.')</strong></span></td>
	<td align="center"><strong>'._DELETE.'</strong></td><td align="center"><strong>'._INSERT_BLANK_AFTER.'</strong></td>
	</tr>';
    if (!empty($vars['content'])) {
        $contentlines = explode("LINESPLIT", $vars['content']);
        foreach ($contentlines as $contentline) {
            $link = explode('|', $contentline);
            $output .= "<tr><td valign=\"top\"><input type=\"text\" name=\"linkname[$c]\" size=\"30\" maxlength=\"255\" value=\"" . pnVarPrepHTMLDisplay($link[1]) . "\" /></td>
                            <td valign=\"top\"><input type=\"text\" name=\"linkurl[$c]\" size=\"30\" maxlength=\"255\" value=\"" . pnVarPrepHTMLDisplay($link[0]) . "\" /></td>
                            <td valign=\"top\"><input type=\"text\" name=\"linkdesc[$c]\" size=\"30\" maxlength=\"255\" value=\"" . pnVarPrepHTMLDisplay($link[2]) . "\" /></td>
                            <td valign=\"top\"><input type=\"checkbox\" name=\"linkdelete[$c]\" value=\"1\" /></td>
                            <td valign=\"top\"><input type=\"checkbox\" name=\"linkinsert[$c]\" value=\"1\" /></td></tr>\n";
            $c++;
        }
    }

    $output .= "<tr><td><input type=\"text\" name=\"new_linkname\" size=\"30\" maxlength=\"255\" /></td>
                    <td><input type=\"text\" name=\"new_linkurl\" size=\"30\" maxlength=\"255\" /></td>
                    <td><input type=\"text\" name=\"new_linkdesc\" size=\"30\" maxlength=\"255\" /></td>
                    <td>"._NEWONE."</td><td><input type=\"checkbox\" name=\"new_linkinsert\" value=\"1\" /></td></tr>\n";
    $output .= '</table></td></tr>';

    return $output;

}

function blocks_menu_update($row)
{
    list($vars['displaymodules'],
         $vars['displaywaiting'],
         $vars['style'])
      = pnVarCleanFromInput('displaymodules',
                            'displaywaiting',
                            'style');

    // Defaults
    if (empty($vars['displaymodules'])) {
        $vars['displaymodules'] = 0;
    }
    if (empty($vars['displaywaiting'])) {
        $vars['displaywaiting'] = 0;
    }
    if (empty($vars['style']) || !is_numeric($vars['style'])) {
        $vars['style'] = 1;
    }

    // User links
    $content = array();
    $c = 1;
    if (isset($row['linkname'])) {
        list($linkurl, $linkname, $linkdesc) = pnVarCleanFromInput('linkurl', 'linkname', 'linkdesc');
        foreach ($row['linkname'] as $v) {
            if (!isset($row['linkdelete'][$c])) {
                $content[] = "$linkurl[$c]|$linkname[$c]|$linkdesc[$c]";
            }
            if (isset($row['linkinsert'][$c])) {
                $content[] = "||";
            }
            $c++;
        }
    }
    if ($row['new_linkname']) {
       $content[] = pnVarCleanFromInput('new_linkurl').'|'.pnVarCleanFromInput('new_linkname').'|'.pnVarCleanFromInput('new_linkdesc');
    }
    $vars['content'] = implode('LINESPLIT', $content);

    $row['content']=pnBlockVarsToContent($vars);

    return($row);
}

function startMenuStyle($style)
{
    // Nothing to do for style == 1 (bullet list)
    $content = '';
    if ($style == 2) {
        $content = "<br /><div style=\"text-align:center\"><form method=\"post\" action=\"index.php\"><div><select class=\"pn-text\" name=\"newlanguage\" onchange=\"top.location.href=this.options[this.selectedIndex].value\">\n";
    } else {
    	$content = "<ul>\n";
    }

    return $content;
}

function endMenuStyle($style)
{
    // Nothing to do for style == 1 (bullet list)
    $content = '';
    if ($style == 2) {
        $content = "</select></div></form></div>\n";
    } else {
    	$content = "</ul>\n";
    }

    return $content;
}

function addMenuStyledUrl($style, $name, $url, $comment)
{
	$url = trim($url);
    if ($style == 1) {
        // Bullet list
        if (empty($url)) {
            // Separator
            if (empty($name)) {
                $content = "<li style=\"list-style:none\"><br /></li>\n";
            } else {
                $content = "<li style=\"list-style:none\"><br />$name<br /></li>\n";
            }
        } else {
        switch ($url[0]) // Used to allow support for linking to modules with the use of bracket
        {
            case '[': // old style module link
            {
                $url = explode(':', substr($url, 1,  - 1));
                $url = 'index.php?name='.$url[0].(isset($url[1]) ? '&amp;file='.$url[1]:'');
                break;
            }
            case '{': // new module link
            {
                $url = explode(':', substr($url, 1,  - 1));
                $url = pnModURL($url[0], 'user', isset($url[1]) ? $url[1]:'');
                //$url = 'index.php?module='.$url[0].(isset($url[1]) ? '&amp;func='.$url[1]:'');
                break;
            }
        }  // End Bracket Linking
            $content = '<li><a href="'.pnVarPrepForDisplay($url).'" title="'.pnVarPrepHTMLDisplay($comment).'">'.pnVarPrepHTMLDisplay($name).'</a></li>'."\n";
        }
    } else if ($style == 2) {
        // Drop-down lilst
        if (empty($url)) {
            // Separator
            $content = "<option>-----</option>\n";
            if (!empty($name)) {
                $content .= "<option>$name</option>\n";
                $content .= "<option>-----</option>\n";
            }
        } else {

        switch ($url[0])  // Used to allow support for linking to modules with the use of bracket
        {
            case '[': // module link
            {
                $url = explode(':', substr($url, 1,  - 1));
                $url = 'index.php?name='.$url[0].(isset($url[1]) ? '&amp;file='.$url[1]:'');
                break;
            }
            case '{': // new module link
            {
                $url = explode(':', substr($url, 1,  - 1));
                $url = pnModURL($url[0], 'user', isset($url[1]) ? $url[1]:'');
                //$url = 'index.php?module='.$url[0].(isset($url[1]) ? '&amp;func='.$url[1]:'');
                break;
            }
        } // End bracket linking.
            $content = '<option value="'.pnVarPrepForDisplay($url).'">'.pnVarPrepHTMLDisplay($name).'</option>'."\n";
        }
    }

    return $content;
}

?>