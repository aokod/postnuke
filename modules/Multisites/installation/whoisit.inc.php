<?php
// $Id $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
// http://www.postnuke.com/
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

$serverName = $_SERVER['SERVER_NAME'];

// The following line strips www from servername
// comment if not needed.
$serverName = str_replace("www.","",$serverName);

// These lines will cause all .org/.net/.com sites to be treated the same
// so postnuke.com and postnuke.net would point to the same multisite config
$serverName = str_replace(".org","",$serverName);
$serverName = str_replace(".net","",$serverName);
$serverName = str_replace(".com","",$serverName);

// optional default for no match on $SERVER_NAME
// uncomment to use
/*
if (!file_exists($serverName)) {
    $serverName = "defaultsite";
}
*/
?>