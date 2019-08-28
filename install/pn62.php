<?php 
// File: $Id: pn62.php 15630 2005-02-04 06:35:42Z jorg $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2002 by the PostNuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// LICENSE

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------
/**
 * Cat Title increased
 */
mysql_query("ALTER TABLE " . $prefix . "_stories_cat MODIFY title varchar(40) NOT NULL DEFAULT ''");

/**
 * Referers Addition
 */
mysql_query("ALTER TABLE " . $prefix . "_referer ADD COLUMN frequency INT(15) AFTER url");

/**
 * Update for the Authors Permissions
 */
mysql_query("ALTER TABLE " . $prefix . "_authors ADD COLUMN radminblocks tinyint(2) DEFAULT '0' NOT NULL AFTER radminreviews");

/**
 * Update for the AutoLink Mod
 */
mysql_query("CREATE TABLE " . $prefix . "_autolinks (
lid int(11) NOT NULL auto_increment,
keyword varchar(100) NOT NULL,
title varchar(100) NOT NULL,
url varchar(200) NOT NULL,
comment varchar(200),
PRIMARY KEY (lid),
UNIQUE keyword (keyword)
)");

/**
 * Update stories for the comments selection
 */
mysql_query("ALTER TABLE " . $prefix . "_stories ADD withcomm int(1) DEFAULT '0' NOT NULL AFTER alanguage");
mysql_query("ALTER TABLE " . $prefix . "_autonews ADD withcomm int(1) DEFAULT '0' NOT NULL AFTER alanguage");

mysql_query("ALTER TABLE " . $prefix . "_queue ADD arcd INT (1) DEFAULT '0' NOT NULL AFTER uid");
mysql_query("ALTER TABLE " . $prefix . "_users ADD timezone_offset float(3,1) DEFAULT '0.0' NOT NULL");

?>