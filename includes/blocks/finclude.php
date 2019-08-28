<?php
// File: $Id: finclude.php 16572 2005-07-30 14:23:41Z chestnut $
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
// Purpose of file: Include a file
// ----------------------------------------------------------------------

if (strpos($_SERVER['PHP_SELF'], 'finclude.php')) {
  die ("You can't access this file directly...");
}

$blocks_modules['finclude'] = array(
    'func_display' =>'blocks_finclude_block',
    'func_add' =>    'blocks_finclude_add',
    'func_edit' =>   'blocks_finclude_edit',
  'func_update' => 'blocks_finclude_update',
    'text_type' => 'Include',
    'text_type_long' => 'Simple File Include',
    'allow_multiple' => true,
    'form_content' => false,
    'form_refresh' => false,
//  'support_xhtml' => true,
    'show_preview' => true
);

pnSecAddSchema('fincludeblock::', 'Block title::');

function blocks_finclude_block($row)
{
    if (!pnSecAuthAction(0, "fincludeblock::", "$row[title]::", ACCESS_READ)) {
        return;
    }

    $url  = explode('|', $row['url']);

    if (isset($row['url'])) {
      $file = (isset($url[0]) ? $url[0] : null);
      $type = (isset($url[1]) ? $url[1] : null);

      if (!file_exists($file)) {
      $row['content'] = _BLOCKTEXTFILENOTFOUND;
          return themesideblock($row);
      }
    $lines = file ($file);

      foreach ($lines as $line_num => $line) {
      if ($type == 1) {
            $row['content'] .= $line;
      }
      if ($type == 0) {
        $row['content'] .= $line."<br/>";
          }
    }
    }
    return themesideblock($row);
}

function blocks_finclude_add($row)
{
    $row['url'] = '/path/to/file.txt|0';
    return $row;
}

function blocks_finclude_update($vars)
{
    $vars['url'] = $vars['filo'] ."|". $vars['typo'];
    return $vars;
}

function blocks_finclude_edit($row)
{
    if (!empty($row['url'])) {
        $url  = explode('|', $row['url']);
        $file   = $url[0];
        $type   = $url[1];
  }

  if (!isset($file)){
    $file = '';
  }

    $output = "<tr><td>"._FILE." </td><td><input type=\"text\" name=\"filo\" size=\"30\" maxlength=\"255\" value=\"$file\" /></td></tr>";
    $output .= "<tr><td></td><td><select name=\"typo\"><option value=\"0\"";
  if (isset($type) && $type == 0) {
    $output .= ' selected="selected"';
  }
    $output .= ">"._HTML."</option><option value=\"1\"";
  if (isset($type) && $type == 1) {
    $output .= ' selected="selected"';
  }
  $output .= ">"._TXT."</option></select></td></tr>";
  return $output;
}

?>