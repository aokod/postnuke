<?php 
// File: $Id: pn5.php 15630 2005-02-04 06:35:42Z jorg $
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
// Change those damn faq tables to lowercase if user loaded the nuke.sql from .5.1.2
$result = mysql_query("ALTER TABLE " . $prefix . "_faqAnswer RENAME " . $prefix . "_faqanswer");
if (!$result) {
    echo "Table faqaategories is already as it should be.<br>\n";
    echo "No need to rename it";
} else {
    echo "Table " . $prefix . "_faqAnswer renamed to " . $prefix . "_faqanswer<br>";
} 

$result = mysql_query("ALTER TABLE " . $prefix . "_faqCategories RENAME " . $prefix . "_faqcategories");
if (!$result) {
    echo "Table faqcategories is already as it should be.<br>\n";
    echo "No need to rename it";
} else {
    echo "Table " . $prefix . "_faqCategories renamed to " . $prefix_ . "faqcategories<br><br>";
} 
// Add the timezone field
$result = mysql_query("ALTER TABLE users ADD timezone_offset float(3,1) DEFAULT '0.0' NOT NULL");
// Add language field for admins , no need to set a default because blank means ALL languages
mysql_query("ALTER TABLE " . $prefix . "_authors ADD admlanguage VARCHAR (30) not null ");
// Add language field to automated news and fill it up with the default language
mysql_query("ALTER TABLE " . $prefix . "_autonews ADD alanguage VARCHAR (30) not null ");
// Add language field to blocks, no need to fill up because blank means visible to ALL
mysql_query("ALTER TABLE " . $prefix . "_blocks ADD blanguage VARCHAR (30) not null ");
// Add language field to ephemerids
mysql_query("ALTER TABLE " . $prefix . "_ephem ADD elanguage VARCHAR (30) not null ");
// Add language field to faq categories
mysql_query("ALTER TABLE " . $prefix . "_faqcategories ADD flanguage VARCHAR (30) not null ");
// Add language field to poll description
mysql_query("ALTER TABLE " . $prefix . "_poll_desc ADD planguage VARCHAR (30) not null ");
// Add language field to submitted news in queue
mysql_query("ALTER TABLE " . $prefix . "_queue ADD alanguage VARCHAR (30) not null ");
// Add language field to reviews
mysql_query("ALTER TABLE " . $prefix . "_reviews ADD rlanguage VARCHAR (30) not null ");
// Add language field to waiting reviews
mysql_query("ALTER TABLE " . $prefix . "_reviews_add ADD rlanguage VARCHAR (30) not null ");
// Add language field to articles in sections
mysql_query("ALTER TABLE " . $prefix . "_seccont ADD slanguage VARCHAR (30) not null ");
// Add themeoverride to stories
mysql_query("ALTER TABLE " . $prefix . "_stories ADD themeoverride VARCHAR (30) not null ");
// Add language field to the stories
mysql_query("ALTER TABLE " . $prefix . "_stories ADD alanguage VARCHAR (30) not null ");
// Add themeoverride to stories_cat
mysql_query("ALTER TABLE " . $prefix . "_stories_cat ADD themeoverride VARCHAR (30) not null ");
// Add author field to quotes
mysql_query("ALTER TABLE " . $prefix . "_quotes ADD author VARCHAR (150) not null ");
// New Message System : Add message id and language field , fill up with one example to explain
// WARNING : This will drop the existing message table first !!!
mysql_query("DROP TABLE " . $prefix . "_message ");
mysql_query("CREATE TABLE " . $prefix . "_message (
   mid int(11) NOT NULL auto_increment,
   title varchar(100) NOT NULL,
   content text NOT NULL,
   date varchar(14) NOT NULL,
   expire int(7) DEFAULT '0' NOT NULL,
   active int(1) DEFAULT '1' NOT NULL,
   view int(1) DEFAULT '1' NOT NULL,
   mlanguage varchar(30) NOT NULL,
   PRIMARY KEY (mid),
   UNIQUE mid (mid)
)");

mysql_query("INSERT INTO " . $prefix . "_message VALUES ( '1', 'Welcome to PostNuke, the =-Phoenix-= release (0.722)','<a target=\"_blank\" href=\"http://www.postnuke.com\">PostNuke</a> is a weblog/Content Management System (CMS). It is far more secure and stable than competing products, and able to work in high-volume environments with ease. 
<br>
<br>
Some of the highlights of PostNuke are
<ul>
<li> Customisation of all aspects of the website\'s appearance through themes, including CSS support 
<li> The ability to specify items as being suitable for either a single or all languages  
<li> The best guarantee of displaying your webpages on all browsers due to HTML 4.01 transitional compliance  
<li> A standard API and extensive documentation to allow for easy creation of extended functionality through modules and blocks  
</ul>
<br>
<br>
PostNuke has a very active developer and support community at www.postnuke.com.
<br>
<br>
We hope you will enjoy using PostNuke.
<br>
<br>
<i>Your PostNuke development team </i>', '993373194', '0', '1', '1', '')");

mysql_query("ALTER TABLE " . $prefix . "_blocks CHANGE bid bid INT UNSIGNED NOT NULL AUTO_INCREMENT");
mysql_query("ALTER TABLE " . $prefix . "_blocks CHANGE bkey bkey VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE " . $prefix . "_blocks CHANGE title title VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE " . $prefix . "_blocks CHANGE url url VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE " . $prefix . "_blocks CHANGE weight weight DECIMAL(10,1) NOT NULL DEFAULT 0");
mysql_query("ALTER TABLE " . $prefix . "_blocks CHANGE active active TINYINT UNSIGNED NOT NULL DEFAULT 0");
mysql_query("ALTER TABLE " . $prefix . "_blocks CHANGE refresh refresh INT UNSIGNED NOT NULL DEFAULT 0");
mysql_query("ALTER TABLE " . $prefix . "_blocks CHANGE time last_update TIMESTAMP");
mysql_query("UPDATE " . $prefix . "_blocks SET bkey='html' WHERE bkey=''");
mysql_query("ALTER TABLE " . $prefix . "_headlines CHANGE hid id INT UNSIGNED NOT NULL AUTO_INCREMENT");
mysql_query("ALTER TABLE " . $prefix . "_headlines CHANGE sitename sitename VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE " . $prefix . "_headlines CHANGE headlinesurl rssurl VARCHAR(255) NOT NULL DEFAULT ''");
mysql_query("ALTER TABLE " . $prefix . "_headlines ADD siteurl VARCHAR(255) NOT NULL DEFAULT ''");

?>