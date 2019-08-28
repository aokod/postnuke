<?php 
// File: $Id: phpnuke52.php 15630 2005-02-04 06:35:42Z jorg $
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
// Stories table alteration
mysql_query("ALTER TABLE $prefix" . _stories . " DROP haspoll INT (1) DEFAULT '0' not null ");
mysql_query("ALTER TABLE $prefix" . _stories . " DROP pollID INT (10) DEFAULT '0' not null ");
// Queue Table Alteration
mysql_query("ALTER TABLE $prefix" . _queue . " DROP storyext TEXT not null AFTER story");
// Polls Table Alteration
mysql_query("ALTER TABLE $prefix" . _poll_desc . " DROP artid INT (10) DEFAULT '0' not null ");
mysql_query("ALTER TABLE $prefix" . _poll_check . " DROP pollID INT (10) DEFAULT '0' not null ");

/**
 * Update stories for the comments selection
 */
mysql_query("ALTER TABLE " . $prefix . "_stories ADD withcomm int(1) DEFAULT '0' NOT NULL AFTER alanguage");
mysql_query("ALTER TABLE " . $prefix . "_stories ADD pn_format_type int(1) UNSIGNED DEFAULT '0' NOT NULL AFTER withcomm");
mysql_query("ALTER TABLE " . $prefix . "_autonews ADD withcomm int(1) DEFAULT '0' NOT NULL AFTER alanguage");

mysql_query("ALTER TABLE " . $prefix . "_queue ADD arcd INT (1) DEFAULT '0' NOT NULL AFTER uid");
mysql_query("ALTER TABLE " . $prefix . "_users ADD timezone_offset float(3,1) DEFAULT '0.0' NOT NULL");

?>	