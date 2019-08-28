<?php
// File: $Id: button.php 17347 2005-12-20 09:15:52Z markwest $
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
// Original Author of file: Patrick Kellum
// Purpose of file: Display a list of button links in random order
// ----------------------------------------------------------------------

if (strpos($_SERVER['PHP_SELF'], 'button.php')) {
	die ("You can't access this file directly...");
}

$blocks_modules['button'] = array (
    'func_display' => 'blocks_button_display',
    'func_update' => 'blocks_button_update',
    'func_edit' => 'blocks_button_edit',
    'text_type' => 'Button',
    'text_type_long' => 'Button Link Block',
    'allow_multiple' => true,
    'form_content' => false,
    'form_refresh' => false,
//  'support_xhtml' => true,
    'show_preview' => true
);

pnSecAddSchema('Buttonblock::', 'Block title:Target URL:Image URL');

function blocks_button_display($row)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!pnSecAuthAction(0, 'Buttonblock::', "$row[title]::", ACCESS_READ)) {
        return;
    }

    $buttons = array ();
    $column = &$pntable['blocks_buttons_column'];
    $result =& $dbconn->Execute("SELECT $column[title] as title, $column[url] as url, $column[images] as images
                              FROM $pntable[blocks_buttons]
                              WHERE $column[bid]=" . (int)pnVarPrepForStore($row['bid']));
    while(!$result->EOF) {
        $brow = $result->getRowAssoc(false);
        $result->MoveNext();
        $buttons[] = $brow;
    }
    srand(time());
    shuffle($buttons);
    shuffle($buttons);
    $row['content'] = '<span style="text-align:center">';
    $content = 0;
    foreach ($buttons as $v) {
        $img = explode('|', $v['images']);
        if (count($img) > 1) {
            $x = rand(0, count($img) - 1);
            $img = $img[$x];
        } else {
            $img = $img[0];
        }
        $v['title'] = pnVarPrepForDisplay($v['title']);
        if (!pnSecAuthAction(0, 'Buttonblock::', "$row[title]:$row[url]:$img", ACCESS_READ)) {
            continue;
        }
        $imgsize = @getimagesize($img);
        $row['content'] .= "<a href=\"$v[url]\" title=\"$v[title]\"><img src=\"$img\"
        alt=\"$v[title]\" $imgsize[3] /></a><br />\n";
        $content = 1;
    }
    $row['content'] .= '</span>';
    if ($content == 1) {
        return themesideblock($row);
    }
}

function blocks_button_update($vars)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if ((!empty($vars['button_name'])) &&
        (!empty($vars['button_url'])) &&
        (!empty($vars['button_image']))) {
        // add link
        //$nextid = pnVarPrepForStore($nextid);
        $vars['bid'] = pnVarPrepForStore($vars['bid']);
        $vars['button_name'] = pnVarPrepForStore($vars['button_name']);
        $vars['url'] = pnVarPrepForStore($vars['url']);
        $vars['button_image'] = pnVarPrepForStore($vars['button_image']);
        $column = &$pntable['blocks_buttons_column'];
        $nextid = $dbconn->GenId($pntable['blocks_buttons']);
        $dbconn->Execute("INSERT INTO $pntable[blocks_buttons] ($column[id], $column[bid],
        $column[title], $column[url], $column[images]) VALUES ($nextid, $vars[bid],
        '$vars[button_name]', '$vars[button_url]', '$vars[button_image]')");
    }
    // update link name
    if ((isset($vars['bname'])) && (count($vars['bname']))) {
        foreach ($vars['bname'] as $k=>$v) {
            if (!empty($v)) {
                $column = &$pntable['blocks_buttons_column'];
                $dbconn->Execute("UPDATE $pntable[blocks_buttons]
                                  SET $column[title]='" . pnVarPrepForStore($v) . "'
                                  WHERE $column[id]=" . pnVarPrepForStore($k));
            }
        }
    }
    // update link url
    if ((isset($vars['burl'])) && (count($vars['burl']))) {
        foreach ($vars['burl'] as $k=>$v) {
            if (!empty($v)) {
                $column = &$pntable['blocks_buttons_column'];
                $dbconn->Execute("UPDATE $pntable[blocks_buttons]
                                  SET $column[url]='" . pnVarPrepForStore($v) . "'
                                  WHERE $column[id]=" . pnVarPrepForStore($k));
            }
        }
    }
    // remove link
    if ((isset($vars['delete_link'])) && (count($vars['delete_link']))) {
        foreach ($vars['delete_link'] as $v) {
            if (!empty($v)) {
                $column = &$pntable['blocks_buttons_column'];
                $dbconn->Execute("DELETE FROM $pntable[blocks_buttons]
                                  WHERE $column[id]=" . pnVarPrepForStore($v));
            }
        }
    }
    // add image
    if ((isset($vars['images'])) && (count($vars['images']))) {
        foreach ($vars['images'] as $k=>$v) {
            if (!empty($v)) {
                $column = &$pntable['blocks_buttons_column'];
                $result =& $dbconn->Execute("SELECT $column[images] as images
                                           FROM $pntable[blocks_buttons]
                                           WHERE $column[id]=" . pnVarPrepForStore($k));
                $brow = $result->GetRowAssoc(false);
                $result->MoveNext();
                if (!empty($brow['images'])) {
                    $images = "$brow[images]|$v";
                } else {
                    $images = $v;
                }
                $column = &$pntable['blocks_buttons_column'];
                $dbconn->Execute("UPDATE $pntable[blocks_buttons]
                                SET $column[images]='" . pnVarPrepForStore($images) . "'
                                WHERE $column[id]=" . pnVarPrepForStore($k));
            }
        }
    }
    // remove image
    if ((isset($vars['bdel_img'])) && (count($vars['bdel_img']))) {
        foreach ($vars['bdel_img'] as $k=>$v) {
            $images = '';
            $flag = false;
            if (count($v)) {
                $column = &$pntable['blocks_buttons_column'];
                $result =& $dbconn->Execute("SELECT $column[images] as images
                                          FROM $pntable[blocks_buttons]
                                          WHERE $column[id]=" . pnVarPrepForStore($k));
                $brow = $result->GetRowAssoc(false);
                $result->MoveNext();
                $c = 1;
                $img = explode('|', $brow['images']);
                foreach ($img as $v2)
                {
                    if (!in_array($c, $v))
                    {
                        if ($flag)
                        {
                            $images .= '|';
                        }
                        $images .= $v2;
                        $flag = true;
                    }
                    $c++;
                }
                $column = &$pntable['blocks_buttons_column'];
                $dbconn->Execute("UPDATE $pntable[blocks_buttons]
                                  SET $column[images]='" . pnVarPrepForStore($images) . "'
                                  WHERE $column[id]=" . pnVarPrepForStore($k));
            }
        }
    }
    return $vars;
}

function blocks_button_edit($row)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $output = '<tr><td valign="top">Buttons:</td><td>'
        .'<table border="1" width=\"100%\"><tr>
		<td align="center">Title</td>
		<td align="center">URL</td>
		<td align="center">Images</td>
		<td align="center">Delete</td></tr>';
    $column = &$pntable['blocks_buttons_column'];
    $result =& $dbconn->Execute("SELECT $column[id] as id, $column[bid] as bid,
    $column[title] as title, $column[url] as url, $column[images] as images
                              FROM $pntable[blocks_buttons]
                              WHERE $column[bid]=$row[bid]
                              ORDER BY $column[title]");
    while(!$result->EOF) {
        $brow = $result->GetRowAssoc(false);
        $result->MoveNext();

        $c = 1;
        $img = '';
        $buttons = explode('|', $brow['images']);
        foreach ($buttons as $v) {
            if ($v) {
                $imgsize = @getimagesize($v);
                $imgsize_alt = str_replace('"', '', $imgsize[3]);
                $img .= "<img src=\"$v\" $imgsize[3] alt=\"$v - $imgsize_alt\" />&nbsp;Delete:&nbsp;
                <input type=\"checkbox\" name=\"bdel_img[$brow[id]][]\" value=\"$c\" /><br />\n";
                $c++;
            }
        }
        $brow['title'] = pnVarPrepForDisplay($brow['title']);
        $output .= "<tr>
		<td valign=\"top\"><input type=\"text\" name=\"bname[$brow[id]]\" size=\"30\" maxlength=\"255\" value=\"$brow[title]\" /></td>
		<td valign=\"top\"><input type=\"text\" name=\"burl[$brow[id]]\" size=\"30\" maxlength=\"255\" value=\"$brow[url]\" /></td>
		<td valign=\"top\">$img<input type=\"text\" name=\"images[$brow[id]]\" size=\"10\" maxlength=\"255\" /></td>
		<td valign=\"top\"><input type=\"checkbox\" name=\"delete_link[]\" value=\"$brow[id]\" /></td></tr>\n";
    }
    $output .= "<tr>
	<td><input type=\"text\" name=\"button_name\" size=\"30\" maxlength=\"255\" /></td>
	<td><input type=\"text\" name=\"button_url\" size=\"30\" maxlength=\"255\" /></td>
	<td colspan=\"2\"><input type=\"text\" name=\"button_image\" size=\"20\" maxlength=\"255\" /></td></tr>\n";
    $output .= '</table></td></tr>';
    return $output;
}

?>