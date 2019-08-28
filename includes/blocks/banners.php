<?PHP
// File: $Id: banners.php 16576 2005-07-31 16:05:48Z larsneo $
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
// Alexander Graef aka MagicX
// Purpose of file: simple sideblock calling the new banner api
//                  and allows to set up multiple custom banner sideblocks
// ----------------------------------------------------------------------
if (strpos($_SERVER['PHP_SELF'], 'banners.php') && strpos($_SERVER['PHP_SELF'], 'blocks')) {
  die ("You can't access this file directly...");
}

$blocks_modules['banners'] = array(
    'func_display'    => 'blocks_banner_block',
    'func_add'      => 'blocks_banner_add',
    'func_update'     => 'blocks_banner_update',
    'func_edit'     => 'blocks_banner_edit',
    'text_type'     => 'banners',
    'text_type_long'  => 'Custom Banner Display',
    'allow_create'    => false,
     'allow_multiple' => true,
    'allow_delete'    => false,
    'form_content'    => false,
    'form_refresh'    => false,
    'show_preview'    => true
);

// Security
pnSecAddSchema("Bannersblock::", "Block title::");

function blocks_banner_block($row)
{
    if (!pnSecAuthAction(0, "Bannersblock::", "$row[title]::", ACCESS_READ)) {
        return;
    }

    $url = explode('|', $row['url']);

   // to have some start variables
  if (!$url[0]) {
    $url[0]="3";
    }

    if (function_exists('pnbannerdisplay')) {
    // get the banner through the new banner api and assign type
      $row['content'] = '<div style="text-align:center">'.pnBannerDisplay($url[0]).'</div>';
  } else {
    // get the banner through the new banner api and assign type
      $row['content'] = '<div style="text-align:center">'._BANNERAPINOTACTIVATED.'</div>';
  }
    return themesideblock($row);
}


function blocks_banner_add($row)
{
  $row['url'] = '3|0';
    return $row;
}

function blocks_banner_update($vars)
{
  $vars['url'] = "$vars[custom]|$vars[test]";
    return $vars;
}


function blocks_banner_edit($row)
{
  $url = explode('|', $row['url']);
  $custom = $url[0];


  $output = '<tr><td>'._CUSTOM.'</td>'
           .'<td><input type="text" name="custom" size="5" maxlength="255" value="'.pnVarPrepForDisplay($custom).'" /> '._DEFINEBANNER.'</td></tr>';
  return $output;
}

?>