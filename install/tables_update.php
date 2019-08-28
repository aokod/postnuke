<?php 
// File: $Id: tables_update.php 15630 2005-02-04 06:35:42Z jorg $
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
// Purpose of file: Include the functions needed for the table validation
// ----------------------------------------------------------------------
/**
 * Function: CheckForField
 * Purpose: Returns true if field exists, false otherwise
 */

/**
 * first we check to see if the user table has a timezone_offset field
 */
if (CheckForField('timezone_offset', 'users')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_users table already contains a timezone_offset field ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add 'timezone_offset' field to $GLOBALS[prefix]_users ... ";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_users add timezone_offset float(3,1) not null default 12.0")) {
        echo "failed</font><br>\r\n";
    } else {
        echo "done!</font><br>\r\n";
    } 
} 

/**
 * Update for the Referers Mod
 */
if (CheckForField('frequency', 'referer')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_referer table already contains a frequency field ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add 'frequency' field to $GLOBALS[prefix]_referer ... ";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_referer ADD COLUMN frequency INT(15) AFTER url")) {
        echo "failed</font><br>\r\n";
    } else {
        echo "done!</font><br>\r\n";
    } 
} 

/**
 * Update for the Authors Permissions
 */
if (CheckForField('radminblocks', 'authors')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_authors table already contains a radminblocks field ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add 'radminblocks' field to $GLOBALS[prefix]_authors ... ";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_authors ADD COLUMN radminblocks tinyint(2) DEFAULT '0' NOT NULL AFTER radminreviews")) {
        echo "failed</font><br>\r\n";
    } else {
        echo "done!</font><br>\r\n";
    } 
} 

/**
 * Update for the AutoLink Mod
 */
if (CheckTableExists('autolinks')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_autolinks table already exists ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add $GLOBALS[prefix]_autolink ... ";
    if (!@mysql_query("CREATE TABLE $GLOBALS[prefix]_autolinks (lid int(11) NOT NULL auto_increment, keyword varchar(100) NOT NULL, title varchar(100) NOT NULL,	 url varchar(200) NOT NULL, comment varchar(200), PRIMARY KEY (lid),	UNIQUE keyword (keyword))")) {
        echo "failed</font><br>\r\n";
    } else {
        echo "done!</font><br>\r\n";
    } 
} 

/**
 * Update for the Stats Mod
 */
if (CheckTableExists('stats_date')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_stats_date table already exists ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add $GLOBALS[prefix]_stats_date ... ";
    if (!@mysql_query("CREATE TABLE $GLOBALS[prefix]_stats_date (date varchar(80) NOT NULL default '', hits int(11) unsigned NOT NULL default '0')")) {
        echo "failed</font><br>\r\n";
    } else {
        echo "done!</font><br>\r\n";
    } 
} 
if (CheckTableExists('stats_hour')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_stats_hour table already exists ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add $GLOBALS[prefix]_stats_hour ... ";
    if (!@mysql_query("CREATE TABLE $GLOBALS[prefix]_stats_hour (hour tinyint(2) unsigned NOT NULL default '0', hits int(10) unsigned NOT NULL default '0')")) {
        echo "failed</font><br>\r\n";
    } else {
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '0', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '1', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '2', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '3', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '4', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '5', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '6', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '7', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '8', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '9', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '10', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '11', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '12', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '13', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '14', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '15', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '16', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '17', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '18', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '19', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '20', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '21', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '22', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_hour (hour, hits) VALUES ( '23', '0')");
        echo "done!</font><br>\r\n";
    } 
} 
if (CheckTableExists('stats_month')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_stats_month table already exists ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add $GLOBALS[prefix]_stats_month ... ";
    if (!@mysql_query("CREATE TABLE $GLOBALS[prefix]_stats_month (month tinyint(2) unsigned NOT NULL default '0', hits int(10) unsigned NOT NULL default '0')")) {
        echo "failed</font><br>\r\n";
    } else {
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_month (month, hits) VALUES ( '1', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_month (month, hits) VALUES ( '2', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_month (month, hits) VALUES ( '3', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_month (month, hits) VALUES ( '4', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_month (month, hits) VALUES ( '5', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_month (month, hits) VALUES ( '6', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_month (month, hits) VALUES ( '7', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_month (month, hits) VALUES ( '8', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_month (month, hits) VALUES ( '9', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_month (month, hits) VALUES ( '10', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_month (month, hits) VALUES ( '11', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_month (month, hits) VALUES ( '12', '0')");
        echo "done!</font><br>\r\n";
    } 
} 
if (CheckTableExists('stats_week')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_stats_week table already exists ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add $GLOBALS[prefix]_stats_week ... ";
    if (!@mysql_query("CREATE TABLE $GLOBALS[prefix]_stats_week (weekday tinyint(1) unsigned NOT NULL default '0', hits int(10) unsigned NOT NULL default '0')")) {
        echo "failed</font><br>\r\n";
    } else {
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_week (weekday, hits) VALUES ( '0', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_week (weekday, hits) VALUES ( '1', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_week (weekday, hits) VALUES ( '2', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_week (weekday, hits) VALUES ( '3', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_week (weekday, hits) VALUES ( '4', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_week (weekday, hits) VALUES ( '5', '0')");
        @mysql_query("INSERT INTO $GLOBALS[prefix]_stats_week (weekday, hits) VALUES ( '6', '0')");
        echo "done!</font><br>\r\n";
    } 
} 

/**
 * Update stories for the comments selection
 */

if (CheckForField('withcomm', 'stories')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_stories table already contains a withcomm field ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add 'withcomm' field to $GLOBALS[prefix]_stories ... ";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_stories add withcomm int(1) DEFAULT '0' NOT NULL AFTER alanguage")) {
        echo "failed</font><br>\r\n";
    } else {
        echo "done!</font><br>\r\n";
    } 
} 

if (CheckForField('withcomm', 'autonews')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_autonews table already contains a withcomm field ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add 'withcomm' field to $GLOBALS[prefix]_autonews ... ";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_autonews add withcomm int(1) DEFAULT '0' NOT NULL AFTER alanguage")) {
        echo "failed</font><br>\r\n";
    } else {
        echo "done!</font><br>\r\n";
    } 
} 

/**
 * Update queue for the archived submissions
 */

if (CheckForField('arcd', 'queue')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_queue table already contains a arcd field ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add 'arcd' field to $GLOBALS[prefix]_queue ... ";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_queue add arcd int(1) DEFAULT '0' NOT NULL AFTER uid")) {
        echo "failed</font><br>\r\n";
    } else {
        echo "done!</font><br>\r\n";
    } 
} 

/**
 * Update for Advance FAQ Hack
 */

if (CheckForField('parent_id', 'faqcategories')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_faqcategories table allread contains the parent_id field ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add 'parent_id' to $GLOBALS[prefix]_faqcategories ... ";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_faqcategories add parent_id TINYINT (3) DEFAULT '0' NOT NULL")) {
        echo "failed</font><br>\r\n";
    } else {
        echo "done!</font><br>\r\n";
    } 
} 

if (CheckForField('submittedby', 'faqanswer')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_faqanswer table allread contains the submittedby field ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add 'submittedby' to $GLOBALS[prefix]_faqanswer ... ";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_faqanswer ADD submittedby VARCHAR(250) DEFAULT '' NOT NULL")) {
        echo "failed</font><br>\r\n";
    } else {
        echo "done!</font><br>\r\n";
    } 
} 

/**
 * Update tables for Crocket's Multi-Language
 */

if (CheckForField('admlanguage', 'authors')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_authors table already contains a Multi-Language field ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add 'Multi-Language' to $GLOBALS[prefix]_authors ... ";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_authors add admlanguage varchar(30) NOT NULL")) {
        echo "failed</font><br>\r\n";
    } else {
        echo "done!</font><br>\r\n";
    } 
} 

if (CheckForField('alanguage', 'autonews')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_autonews table already contains a Multi-Language field ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add 'Multi-Language' to $GLOBALS[prefix]_autonews ... ";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_autonews add alanguage varchar(30) NOT NULL")) {
        echo "failed</font><br>\r\n";
    } else {
        (!@mysql_query("UPDATE $GLOBALS[prefix]_autonews SET alanguage='$language' '"));
        echo "done!</font><br>\r\n";
    } 
} 

if (CheckForField('blanguage', 'blocks')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_blocks table already contains a Multi-Language field ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add 'Multi-Language' to $GLOBALS[prefix]_blocks ... ";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_blocks add blanguage varchar(30) NOT NULL")) {
        echo "failed</font><br>\r\n";
    } else {
        echo "done!</font><br>\r\n";
    } 
} 

if (CheckForField('elanguage', 'ephem')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_ephem table already contains a Multi-Language field ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add 'Multi-Language' to $GLOBALS[prefix]_ephem ... ";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_ephem add elanguage varchar(30) NOT NULL")) {
        echo "failed</font><br>\r\n";
    } else {
        (!@mysql_query("UPDATE $GLOBALS[prefix]_ephem SET elanguage='$language' '"));
        echo "done!</font><br>\r\n";
    } 
} 

if (CheckForField('flanguage', 'faqcategories')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_faqcategories table already contains a Multi-Language field ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add 'Multi-Language' to $GLOBALS[prefix]_faqcategories ... ";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_faqcategories add flanguage varchar(30) NOT NULL")) {
        echo "failed</font><br>\r\n";
    } else {
        (!@mysql_query("UPDATE $GLOBALS[prefix]_faqcategories SET flanguage='$language' '"));
        echo "done!</font><br>\r\n";
    } 
} 

if (CheckForField('planguage', 'poll_desc')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_poll_desc table already contains a Multi-Language field ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add 'Multi-Language' to $GLOBALS[prefix]_poll_desc ... ";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_poll_desc add planguage varchar(30) NOT NULL")) {
        echo "failed</font><br>\r\n";
    } else {
        (!@mysql_query("UPDATE $GLOBALS[prefix]_poll_desc SET planguage='$language' '"));
        echo "done!</font><br>\r\n";
    } 
} 

if (CheckForField('alanguage', 'queue')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_queue table already contains a Multi-Language field ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add 'Multi-Language' to $GLOBALS[prefix]_queue ... ";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_queue add alanguage varchar(30) NOT NULL")) {
        echo "failed</font><br>\r\n";
    } else {
        (!@mysql_query("UPDATE $GLOBALS[prefix]_queue SET alanguage='$language' '"));
        echo "done!</font><br>\r\n";
    } 
} 

if (CheckForField('rlanguage', 'reviews')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_reviews table already contains a Multi-Language field ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add 'Multi-Language' to $GLOBALS[prefix]_reviews ... ";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_reviews add rlanguage varchar(30) NOT NULL")) {
        echo "failed</font><br>\r\n";
    } else {
        (!@mysql_query("UPDATE $GLOBALS[prefix]_reviews SET rlanguage='$language' '"));
        echo "done!</font><br>\r\n";
    } 
} 

if (CheckForField('rlanguage', 'reviews_add')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_reviews table already contains a Multi-Language field ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add 'Multi-Language' to $GLOBALS[prefix]_reviews_add ... ";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_reviews_add add rlanguage varchar(30) NOT NULL")) {
        echo "failed</font><br>\r\n";
    } else {
        (!@mysql_query("UPDATE $GLOBALS[prefix]_reviews_add SET rlanguage='$language' '"));
        echo "done!</font><br>\r\n";
    } 
} 

if (CheckForField('slanguage', 'seccont')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_seccont table already contains a Multi-Language field ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add 'Multi-Language' to $GLOBALS[prefix]_seccont ... ";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_seccont add slanguage varchar(30) NOT NULL")) {
        echo "failed</font><br>\r\n";
    } else {
        (!@mysql_query("UPDATE $GLOBALS[prefix]_seccont SET slanguage='$language' '"));
        echo "done!</font><br>\r\n";
    } 
} 

if (CheckForField('alanguage', 'stories')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_stories table already contains a Multi-Language field ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add 'Multi-Language' to $GLOBALS[prefix]_stories ... ";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_stories add alanguage varchar(30) NOT NULL")) {
        echo "failed</font><br>\r\n";
    } else {
        (!@mysql_query("UPDATE $GLOBALS[prefix]_stories SET alanguage='$language' '"));
        echo "done!</font><br>\r\n";
    } 
} 
// New Message System : Add message id and language field , fill up with one example to explain
// WARNING : This will drop the existing message table first !!!
if (CheckForField('mlanguage', 'message')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_message table already contains a Multi-Language field ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add 'Multi-Language' to $GLOBALS[prefix]_stories ... ";
    if (!@mysql_query("DROP TABLE $GLOBALS[prefix]_message")) {
        echo "failed</font><br>\r\n";
    } else {
        (!@mysql_query("CREATE TABLE $GLOBALS[prefix]_message (mid int(11) DEFAULT '0' NOT NULL auto_increment, title varchar(100) NOT NULL, content text NOT NULL, date varchar(14) NOT NULL, expire int(7) DEFAULT '0' NOT NULL, active int(1) DEFAULT '1' NOT NULL,   view int(1) DEFAULT '1' NOT NULL,  mlanguage varchar(30) NOT NULL, PRIMARY KEY (mid), UNIQUE mid (mid))"));
        (!@mysql_query("INSERT INTO TABLE $GLOBALS[prefix]_message VALUES  ( '1', 'New Message system', 'The new message system was written for Multilingual PHP-Nuke 5 available at <a href=\"http://www.webmasters.be\" target=\"_blank\">http://www.webmasters.be</a>. The original version in Nuke 5 allowed the admin to post only 1 message either to \'ALL visitors\' OR \'Anonymous users only\' OR \'Registered users only\' OR \'Admins only\'.<br>The new system allows admin(s) to post <b>multiple</b> messages in <b>multiple languages</b>, visible to different types of users. There is also an option to post a message to ALL languages at once...<br><br><br>Crocket', '993373194', '0', '1', '1', '')"));
        echo "done!</font><br>\r\n";
    } 
} 

/**
 * Update tables for use with Matthew R. Scotts theme over-ride code
 */
if (CheckForField('themeoverride', 'stories')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_stories table allread contains a themeoverride field ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add 'themeoverride' to $GLOBALS[prefix]_stories ... ";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_stories add themeoverride varchar(30) NOT NULL default ''")) {
        echo "failed</font><br>\r\n";
    } else {
        echo "done!</font><br>\r\n";
    } 
} 
if (CheckForField('themeoverride', 'stories_cat')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_stories_cat table already contains a themeoverride field ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add 'themeoverride' to $GLOBALS[prefix]_stories_cat ... ";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_stories_cat add themeoverride varchar(30) NOT NULL default ''")) {
        echo "failed</font><br>\r\n";
    } else {
        echo "done!</font><br>\r\n";
    } 
} 
$FieldType = GetFieldType('title', 'stories_cat');
if ($FieldType != false) {
    if ($FieldType != 'varchar(40)') {
        echo "<font class=\"pn-normal\">Attempting to change datatype of 'title' in $GLOBALS[prefix]_stories_cat from $FieldType to varchar(40) ... ";
        if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_stories_cat modify title varchar(40) NOT NULL DEFAULT ''")) {
            echo "failed</font><br>\r\n";
        } else {
            echo "done!</font><br>\r\n";
        } 
    } else {
        echo "<font class=\"pn-normal\">It appears 'title' in your $GLOBALS[prefix]_stories_cat table already has the correct definition ... skipping</font><br>\r\n";
    } 
} else {
    echo "<font class=\"pn-normal\">There was an error when trying to determine what datatype 'title' in your $GLOBALS[prefix]_stories_cat table is ... <b>FAILED</b></font><br>";
} 
$FieldType = GetFieldType('question', 'faqanswer');
if ($FieldType != false) {
    if ($FieldType != 'text') {
        echo "<font class=\"pn-normal\">Attempting to change datatype of 'question' in $GLOBALS[prefix]_faqanswer from $FieldType to text ... ";
        if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_faqanswer modify text DEFAULT NULL")) {
            echo "failed</font><br>\r\n";
        } else {
            echo "done!</font><br>\r\n";
        } 
    } else {
        echo "<font class=\"pn-normal\">It appears 'question' in your $GLOBALS[prefix]_faqanswer table already has the correct definition ... skipping</font><br>\r\n";
    } 
} else {
    echo "<font class=\"pn-normal\">There was an error when trying to determine what datatype 'question' in your $GLOBALS[prefix]_faqanswer table is ... <b>FAILED</b></font><br>";
} 

/**
 * Advanced Blocks table mods
 */
if (CheckTableExists('advblocks')) {
    print '<br><br>';
    print "<font class=\"pn-normal\">Converting the Advanced Blocks Table ($GLOBALS[prefix]_advblocks) to $GLOBALS[prefix]_blocks.</font><br>";
    if (CheckTableExists('blocks')) {
        print "<font class=\"pn-normal\">Dropping old $GLOBALS[prefix]_blocks table...";
        if (!@mysql_query("DROP TABLE $GLOBALS[prefix]_blocks")) {
            print 'failed!</font><br>';
        } else {
            print 'done!</font><br>';
        } 
    } 
    print "<font class=\"pn-normal\">Renaming $GLOBALS[prefix]_advblocks to $GLOBALS[prefix]_blocks...";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_advblocks RENAME TO $GLOBALS[prefix]_blocks")) {
        print 'failed!</font><br>';
    } else {
        print 'done!</font><br>';
    } 
} else { // advblocks not previously installed, just clean up the current blocks table.
    print '<br><br>';
    print "<font class=\"pn-normal\">Converting the Blocks Table ($GLOBALS[prefix]_blocks) to support the advanced blocks system.</font><br>";
    $fields = GetFields("blocks"); 
    // bid
    if (!$fields[bid][unsigned]) {
        print '<font class="pn-normal">Updating column bid...';
        if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_blocks CHANGE bid bid INT UNSIGNED NOT NULL AUTO_INCREMENT")) {
            print 'failed!</font><br>';
        } else {
            print 'done!</font><br>';
        } 
    } 
    // bkey
    if ($fields[bkey][size] < 255) {
        print '<font class="pn-normal">Updating column bkey...';
        if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_blocks CHANGE bkey bkey VARCHAR(255) NOT NULL")) {
            print 'failed!</font><br>';
        } else {
            print 'done!</font><br>';
        } 
    } 
    // title
    if ($fields[title][size] < 255) {
        print '<font class="pn-normal">Updating column title...';
        if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_blocks CHANGE title title VARCHAR(255) NOT NULL")) {
            print 'failed!</font><br>';
        } else {
            print 'done!</font><br>';
        } 
    } 
    // url
    if ($fields[url][size] < 255) {
        print '<font class="pn-normal">Updating column url...';
        if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_blocks CHANGE url url VARCHAR(255) NOT NULL")) {
            print 'failed!</font><br>';
        } else {
            print 'done!</font><br>';
        } 
    } 
    // weight
    if ($fields[weight][type] != 'decimal' || $fields[weight][size] < 10 || $fields[weight][fraction] < 1) {
        print '<font class="pn-normal">Updating column weight...';
        if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_blocks CHANGE weight weight DECIMAL(10,1) NOT NULL DEFAULT 0")) {
            print 'failed!</font><br>';
        } else {
            print 'done!</font><br>';
        } 
    } 
    // active
    if ($fields[active][type] != 'tinyint' || !$fields[active][unsigned]) {
        print '<font class="pn-normal">Updating column active...';
        if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_blocks CHANGE active active TINYINT UNSIGNED NOT NULL DEFAULT 0")) {
            print 'failed!</font><br>';
        } else {
            print 'done!</font><br>';
        } 
    } 
    // refresh
    if (!$fields[refresh][unsigned]) {
        print '<font class="pn-normal">Updating column refresh...';
        if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_blocks CHANGE refresh refresh INT UNSIGNED NOT NULL DEFAULT 0")) {
            print 'failed!</font><br>';
        } else {
            print 'done!</font><br>';
        } 
    } 
    // last_update/time
    if (!$fields[last_update][type]) {
        print '<font class="pn-normal">Changing column time to last_update...';
        if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_blocks CHANGE time last_update TIMESTAMP")) {
            print 'failed!</font><br>';
        } else {
            print 'done!</font><br>';
        } 
    } 
    // bkey - convert blank bkeys
    @mysql_query("UPDATE $GLOBALS[prefix]_blocks SET bkey='text' WHERE bkey=''");
} 
// headlines table
if (CheckTableExists('advheadlines')) {
    print '<br><br>';
    print "<font class=\"pn-normal\">Converting the Headlines Table ($GLOBALS[prefix]_advheadlines) to $GLOBALS[prefix]_headlines.</font><br>";
    if (CheckTableExists('headlines')) {
        print "<font class=\"pn-normal\">Dropping old $GLOBALS[prefix]_headlines table...";
        if (!@mysql_query("DROP TABLE $GLOBALS[prefix]_headlines")) {
            print 'failed!</font><br>';
        } else {
            print 'done!</font><br>';
        } 
    } 
    print "<font class=\"pn-normal\">Renaming $GLOBALS[prefix]_advheadlines to $GLOBALS[prefix]_headlines...";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_advheadlines RENAME TO $GLOBALS[prefix]_headlines")) {
        print 'failed!</font><br>';
    } else {
        print 'done!</font><br>';
    } 
} else { // advheadlines not previously installed, just clean up the current headlines table.
    print '<br><br>';
    print "<font class=\"pn-normal\">Converting the Headlines Table ($GLOBALS[prefix]_headlines) to support the advanced blocks system.</font><br>";
    $fields = GetFields("headlines"); 
    // id/hid
    if (!$fields[hid][unsigned] && !$fields[id][type]) {
        print '<font class="pn-normal">Updating column hid and renaming to id...';
        if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_headlines CHANGE hid id INT UNSIGNED NOT NULL AUTO_INCREMENT")) {
            print 'failed!</font><br>';
        } else {
            print 'done!</font><br>';
        } 
    } 
    // sitename
    if ($fields[sitename][size] < 255) {
        print '<font class="pn-normal">Updating column sitename...';
        if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_headlines CHANGE sitename sitename VARCHAR(255) NOT NULL DEFAULT ''")) {
            print 'failed!</font><br>';
        } else {
            print 'done!</font><br>';
        } 
    } 
    // rssurl/headlinesurl
    if ($fields[headlinesurl][size] < 255 && !$fields[rssurl][type]) {
        print '<font class="pn-normal">Updating column headlinesurl and renaming to rssurl...';
        if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_headlines CHANGE headlinesurl rssurl VARCHAR(255) NOT NULL DEFAULT ''")) {
            print 'failed!</font><br>';
        } else {
            print 'done!</font><br>';
        } 
    } 
    // siteurl
    if (!$fields[siteurl][type]) {
        print '<font class="pn-normal">Adding column siteurl...';
        if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_headlines ADD siteurl VARCHAR(255) NOT NULL DEFAULT ''")) {
            print 'failed!</font><br>';
        } else {
            print 'done!</font><br>';
        } 
    } 
} 
// blocks_buttons table
if (CheckTableExists('blocks_buttons')) { // for future updates
} else { // create blocks_buttons table
    print '<br><br>';
    print "<font class=\"pn-normal\">Creating the Button Block Table ($GLOBALS[prefix]_blocks_buttons).</font><br>";
    if (!@mysql_query("CREATE TABLE $GLOBALS[prefix]_blocks_buttons (id int(10) unsigned NOT NULL auto_increment, bid int(10) unsigned DEFAULT '0' NOT NULL, title varchar(255) NOT NULL, url varchar(255) NOT NULL, images longtext NOT NULL, PRIMARY KEY (id))")) {
        print 'failed!</font><br>' . mysql_error();
    } else {
        print 'done!</font><br>';
    } 
} 
// convert exisiting button blocks
if (CheckTableExists('blocks_buttons')) {
    print '<br><br>';
    print "<font class=\"pn-normal\">Converting old-style button blocks...<ul>";
    $result = mysql_query("SELECT bid, title, url FROM $GLOBALS[prefix]_blocks WHERE bkey='button' ORDER BY title");
    while ($row = mysql_fetch_array($result)) {
        if (mysql_num_rows(mysql_query("SELECT id FROM $GLOBALS[prefix]_blocks_buttons WHERE bid=$row[bid]"))) { // already converted...
            continue;
        } 
        print "<li>$row[title]... ";
        if (!($row[url] && file_exists("data/$row[url]"))) {
            print "ERROR: File data/$row[url] not found.</li>\n";
            continue;
        } 
        require "data/$row[url]";
        foreach($buttons as $v) {
            $image = '';
            $flag = false;
            if (is_array($v[img])) {
                foreach ($v[img] as $v2) {
                    if ($flag) {
                        $image .= '|';
                    } 
                    $image .= $v2;
                    $flag = true;
                } 
            } else {
                $image = $v[img];
            } 
            $v[title] = addslashes($v[title]);
            mysql_query("INSERT INTO $GLOBALS[prefix]_blocks_buttons (id, bid, title, url, images) VALUES (NULL, $row[bid], '$v[title]', '$v[url]', '$image')");
        } 
        print "Done</li>\n";
    } 
    print '</ul>Done converting old-style button blocks!';
} 
// Add a column to the queue of news for the bodytext
if (CheckForField('bodytext', 'queue')) {
    echo "<font class=\"pn-normal\">It appears your $GLOBALS[prefix]_queue table already contains a bodytext field ... skipping</font><br>\r\n";
} else {
    echo "<font class=\"pn-normal\">Attempting to add 'bodytext' field to $GLOBALS[prefix]_queue... ";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_queue add bodytext text")) {
        echo "failed</font><br>\r\n";
    } else {
        echo "done!</font><br>\r\n";
    } 
} 
// Alter length of url field in links_links table
if (CheckTableExists('links_links')) { // alter table
    print '<br><br>';
    print "<font class=\"pn-normal\">Updating Links Table ($GLOBALS[prefix]_links_links).</font><br>";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_links_links CHANGE pn_url VARCHAR(255) NOT NULL DEFAULT ''")) {
        print 'failed!</font><br>';
    } else {
        print 'done!</font><br>';
    } 
} else { // skip
} 
// Alter length of url field in links_modrequest table
if (CheckTableExists('links_modrequest')) { // alter table
    print '<br><br>';
    print "<font class=\"pn-normal\">Updating Links Table ($GLOBALS[prefix]_links_modrequest).</font><br>";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_links_modrequest CHANGE pn_url VARCHAR(255) NOT NULL DEFAULT ''")) {
        print 'failed!</font><br>';
    } else {
        print 'done!</font><br>';
    } 
} else { // skip
} 
// Alter length of url field in links_newlink table
if (CheckTableExists('links_newlink')) { // alter table
    print '<br><br>';
    print "<font class=\"pn-normal\">Updating Links Table ($GLOBALS[prefix]_links_newlink).</font><br>";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_links_newlink CHANGE pn_url VARCHAR(255) NOT NULL DEFAULT ''")) {
        print 'failed!</font><br>';
    } else {
        print 'done!</font><br>';
    } 
} else { // skip
} 
// Alter length of id field in faqanswer table
if (CheckTableExists('faqanswer')) { // alter table
    print '<br><br>';
    print "<font class=\"pn-normal\">Updating FAQ Table ($GLOBALS[prefix]_faqanswer).</font><br>";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_faqanswer CHANGE pn_id INT(6) NOT NULL auto_increment") && !@mysql_query("ALTER TABLE $GLOBALS[prefix]_faqanswer CHANGE pn_id_cat INT(6) DEFAULT NULL")) {
        print 'failed!</font><br>';
    } else {
        print 'done!</font><br>';
    } 
} else { // skip
} 
// Alter length of id field in faqcategories table
if (CheckTableExists('faqcategories')) { // alter table
    print '<br><br>';
    print "<font class=\"pn-normal\">Updating FAQ Table ($GLOBALS[prefix]_faqcategories).</font><br>";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_faqcategories CHANGE pn_id_cat INT(6) NOT NULL auto_increment") && !@mysql_query("ALTER TABLE $GLOBALS[prefix]_faqcategories CHANGE pn_parent_id INT(6) DEFAULT '0' NOT NULL")) {
        print 'failed!</font><br>';
    } else {
        print 'done!</font><br>';
    } 
} else { // skip
} 
// Alter length of id field in topics table
if (CheckTableExists('topics')) { // alter table
    print '<br><br>';
    print "<font class=\"pn-normal\">Updating Topics Table ($GLOBALS[prefix]_topics).</font><br>";
    if (!@mysql_query("ALTER TABLE $GLOBALS[prefix]_topics CHANGE pn_itopicid TINYINT(4) NOT NULL auto_increment") && !@mysql_query("ALTER TABLE $GLOBALS[prefix]_autonews CHANGE pn_topic TINYINT(4) DEFAULT '1' NOT NULL") && !@mysql_query("ALTER TABLE $GLOBALS[prefix]_stories CHANGE pn_topic TINYINT(4) DEFAULT '1' NOT NULL")) {
        print 'failed!</font><br>';
    } else {
        print 'done!</font><br>';
    } 
} else { // skip
} 

?>