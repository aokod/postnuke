<?php
// File: $Id: banners.php 16576 2005-07-31 16:05:48Z larsneo $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by The PostNuke Development Team.
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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Alexander Graef aka MagicX
// Purpose of file: simple side block calling the new banner API
//                  and allows to set up multiple custom banner side blocks
// ----------------------------------------------------------------------
define('_CUSTOM','Custom banner ID');
define('_DEFINEBANNER',' (0,1,2) are reserved for the default banners. Please use any number higher than that!');
define('_BANNERAPINOTACTIVATED','The Banner module is not activated');

?>