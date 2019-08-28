<?php
// File: $Id: print.php 17567 2006-01-13 08:47:46Z larsneo $
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
// Original Author of file: Francisco Burzi
// Purpose of file: Displays a printer friendly (story) page
// ----------------------------------------------------------------------

include 'includes/pnAPI.php';
pnInit();

// get story id from input
$sid = pnVarCleanFromInput('sid');

if(empty($sid)  || !is_numeric($sid) || !pnModAvailable('News')) {
    header('HTTP/1.0 404 Not Found');
    include 'header.php';
    echo _MODARGSERROR;
    include 'footer.php';
    exit;
}

if (!pnLocalReferer() && pnConfigGetVar('refereronprint')) {
    Header('HTTP/1.1 301 Moved Permanently'); 
    pnRedirect("index.php?name=News&file=article&sid=$sid");
    exit;
} else  {
    pnRedirect('index.php?name=News&file=article&sid='.$sid.'&theme=Printer');
}
?>