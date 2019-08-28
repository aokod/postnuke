<?php
// File: $Id: php.php 15630 2005-02-04 06:35:42Z jorg $
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
// Purpose of file: Execute very simple PHP code
// ----------------------------------------------------------------------

if (strpos($_SERVER['PHP_SELF'], 'php.php')) {
	die ("You can't access this file directly...");
}

$blocks_modules['php'] = array(
    'func_display' => 'blocks_php_block',
    'text_type' => 'PHP',
    'text_type_long' => 'PHP Script',
    'allow_multiple' => true,
    'form_content' => true,
    'form_refresh' => false,
//  'xhtml_support' => false,
    'show_preview' => true
);

// Security
pnSecAddSchema('PHPblock::', 'Block title::');

function blocks_php_block($row) {

    if (!pnSecAuthAction(0, 'PHPblock::', "$row[title]::", ACCESS_READ)) {
        return;
    }

    ob_start();
    print eval($row['content']);
    $row['content'] = ob_get_contents();
    ob_end_clean();
    return themesideblock($row);
}
?>