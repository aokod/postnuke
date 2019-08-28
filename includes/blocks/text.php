<?php
// $Id: text.php 16577 2005-07-31 16:11:48Z larsneo $
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
// Purpose of file: Display the text content of the block
// ----------------------------------------------------------------------

if (strpos($_SERVER['PHP_SELF'], 'text.php')) {
	die ("You can't access this file directly...");
}

$blocks_modules['text'] = array (
    'func_display' => 'blocks_text_block',
    'text_type' => 'Text',
    'text_type_long' => 'Plain Text',
    'allow_multiple' => true,
    'form_content' => true,
    'form_refresh' => false,
    'show_preview' => true
);

// Security
pnSecAddSchema('Textblock::', 'Block title::');

function blocks_text_block($row)
{
    if (!pnSecAuthAction(0, 'Textblock::', "$row[title]::", ACCESS_OVERVIEW)) {
        return;
    }

    $row['content'] = nl2br(pnVarPrepHTMLDisplay($row['content']));

    return themesideblock($row);
}

?>