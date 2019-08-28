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

// check for direct call
if (strpos($_SERVER['PHP_SELF'], 'config.php')) {
    die ("You can't access this file directly...");
}

include('parameters/whoisit.inc.php'); 
if (!empty($serverName)) { 
	include('parameters/'.pnVarPrepForOS($serverName).'/config.php'); 
}

/* this next defined is coming before the one I put in mainfile2.php. 
So there are 2 possibilities, to destroy the one I put in mainfile2.php, or to let it, 
as it is coming after the one below, it wont affect WHERE_IS_PERSO. */

define('WHERE_IS_PERSO', 'parameters/'.pnVarPrepForOS($serverName).'/'); 
?>