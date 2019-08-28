<?php 
// File: $Id: mpn188.php 15630 2005-02-04 06:35:42Z jorg $
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
$result = mysql_query("ALTER TABLE mpn_autonews RENAME " . $prefix . "_autonews") or die("<br>unable to update mpn_autonews<br>");
echo "Renamed mpn_autonews to " . $prefix . "_autonews<br>";

$result = mysql_query("ALTER TABLE mpn_banner RENAME " . $prefix . "_banner") or die("<br>unable to update mpn_banner<br>");
echo "Renamed mpn_banner to " . $prefix . "_banner<br>";

$result = mysql_query("ALTER TABLE mpn_bannerclient RENAME " . $prefix . "_bannerclient") or die("<br>unable to update mpn_bannerclient<br>");
echo "Renamed mpn_bannerclient to " . $prefix . "_bannerclient<br>";

$result = mysql_query("ALTER TABLE mpn_bannerfinish RENAME " . $prefix . "_bannerfinish") or die("<br>unable to update mpn_bannerfinish<br>");
echo "Renamed mpn_bannerfinish to " . $prefix . "_bannerfinish<br>";

$result = mysql_query("ALTER TABLE mpn_comments RENAME " . $prefix . "_comments") or die("<br>unable to update mpn_comments<br>");
echo "Renamed mpn_comments to " . $prefix . "_comments<br>";

$result = mysql_query("ALTER TABLE mpn_counter RENAME " . $prefix . "_counter") or die("<br>unable to update mpn_counter<br>");
echo "Renamed mpn_counter to " . $prefix . "_counter<br>";

$result = mysql_query("ALTER TABLE mpn_ephem RENAME " . $prefix . "_ephem") or die("<br>unable to update mpn_ephem<br>");
echo "Renamed mpn_ephem to " . $prefix . "_ephem<br>";

$result = mysql_query("ALTER TABLE mpn_downloads RENAME " . $prefix . "_downloads") or die("<br>unable to update mpn_downloads<br>");
echo "Renamed mpn_downloads to " . $prefix . "_downloads<br>";

$result = mysql_query("ALTER TABLE mpn_faqanswer RENAME " . $prefix . "_faqanswer") or die("<br>unable to update mpn_faqanswer<br>");
echo "Renamed mpn_faqanswer to " . $prefix . "_faqanswer<br>";

$result = mysql_query("ALTER TABLE mpn_faqcategories RENAME " . $prefix . "_faqcategories") or die("<br>unable to update mpn_faqcategories<br>");
echo "Renamed mpn_faqcategories to " . $prefix . "_faqcategories<br>";

$result = mysql_query("ALTER TABLE mpn_lastseen RENAME " . $prefix . "_lastseen") or die("<br>unable to update mpn_lastseen<br>");
echo "Renamed mpn_lastseen to " . $prefix . "_lastseen<br>";

$result = mysql_query("ALTER TABLE mpn_headlines RENAME " . $prefix . "_headlines") or die("<br>unable to update mpn_headlines<br>");
echo "Renamed mpn_headlines to " . $prefix . "_headlines<br>";

$result = mysql_query("ALTER TABLE mpn_links_categories RENAME " . $prefix . "_links_categories") or die("<br>unable to update mpn_links_categories<br>");
echo "Renamed mpn_links_categories to " . $prefix . "_links_categories<br>";

$result = mysql_query("ALTER TABLE mpn_links_editorials RENAME " . $prefix . "_links_editorials") or die("<br>unable to update mpn_links_editorials<br>");
echo "Renamed mpn_links_editorials to " . $prefix . "_links_editorials<br>";

$result = mysql_query("ALTER TABLE mpn_links_links RENAME " . $prefix . "_links_links") or die("<br>unable to update mpn_links_links<br>");
echo "Renamed mpn_links_links to " . $prefix . "_links_links<br>";

$result = mysql_query("ALTER TABLE mpn_links_modrequest RENAME " . $prefix . "_links_modrequest") or die("<br>unable to update mpn_links_modrequest<br>");
echo "Renamed mpn_links_modrequest to " . $prefix . "_links_modrequest<br>";

$result = mysql_query("ALTER TABLE mpn_links_newlink RENAME " . $prefix . "_links_newlink") or die("<br>unable to update mpn_links_newlink<br>");
echo "Renamed mpn_links_newlink to " . $prefix . "_links_newlink<br>";

$result = mysql_query("ALTER TABLE mpn_links_subcategories RENAME " . $prefix . "_links_subcategories") or die("<br>unable to update mpn_links_subcategories<br>");
echo "Renamed mpn_links_subcategories to " . $prefix . "_links_subcategories<br>";

$result = mysql_query("ALTER TABLE mpn_links_votedata RENAME " . $prefix . "_links_votedata") or die("<br>unable to update mpn_links_votedata<br>");
echo "Renamed mpn_links_votedata to " . $prefix . "_links_votedata<br>";

$result = mysql_query("ALTER TABLE mpn_poll_data RENAME " . $prefix . "_poll_data") or die("<br>unable to update mpn_poll_data<br>");
echo "Renamed mpn_poll_data to " . $prefix . "_poll_data<br>";

$result = mysql_query("ALTER TABLE mpn_poll_desc RENAME " . $prefix . "_poll_desc") or die("<br>unable to update mpn_poll_desc<br>");
echo "Renamed mpn_poll_desc to " . $prefix . "_poll_desc<br>";

$result = mysql_query("ALTER TABLE mpn_pollcomments RENAME " . $prefix . "_pollcomments") or die("<br>unable to update mpn_pollcomments<br>");
echo "Renamed mpn_pollcomments to " . $prefix . "_pollcomments<br>";

$result = mysql_query("ALTER TABLE mpn_priv_msgs RENAME " . $prefix . "_priv_msgs") or die("<br>unable to update mpn_priv_msgs<br>");
echo "Renamed mpn_priv_msgs to " . $prefix . "_priv_msgs<br>";

$result = mysql_query("ALTER TABLE mpn_queue RENAME " . $prefix . "_queue") or die("<br>unable to update mpn_queue<br>");
echo "Renamed mpn_queue to " . $prefix . "_queue<br>";

$result = mysql_query("ALTER TABLE mpn_quotes RENAME " . $prefix . "_quotes") or die("<br>unable to update mpn_quotes<br>");
echo "Renamed mpn_quotes to " . $prefix . "_quotes<br>";

$result = mysql_query("ALTER TABLE mpn_referer RENAME " . $prefix . "_referer") or die("<br>unable to update mpn_referer<br>");
echo "Renamed mpn_referer to " . $prefix . "_referer<br>";

$result = mysql_query("ALTER TABLE mpn_related RENAME " . $prefix . "_related") or die("<br>unable to update mpn_related<br>");
echo "Renamed mpn_related to " . $prefix . "_related<br>";

$result = mysql_query("ALTER TABLE mpn_reviews RENAME " . $prefix . "_reviews") or die("<br>unable to update mpn_reviews<br>");
echo "Renamed mpn_reviews to " . $prefix . "_reviews<br>";

$result = mysql_query("ALTER TABLE mpn_reviews_add RENAME " . $prefix . "_reviews_add") or die("<br>unable to update mpn_reviews_add<br>");
echo "Renamed mpn_reviews_add to " . $prefix . "_reviews_add<br>";

$result = mysql_query("ALTER TABLE mpn_reviews_comments RENAME " . $prefix . "_reviews_comments") or die("<br>unable to update mpn_reviews_comments<br>");
echo "Renamed mpn_reviews_comments to " . $prefix . "_reviews_comments<br>";

$result = mysql_query("ALTER TABLE mpn_reviews_main RENAME " . $prefix . "_reviews_main") or die("<br>unable to update mpn_reviews_main<br>");
echo "Renamed mpn_reviews_main to " . $prefix . "_reviews_main<br>";

$result = mysql_query("ALTER TABLE mpn_seccont RENAME " . $prefix . "_seccont") or die("<br>unable to update mpn_seccont<br>");
echo "Renamed mpn_seccont to " . $prefix . "_seccont<br>";

$result = mysql_query("ALTER TABLE mpn_sections RENAME " . $prefix . "_sections") or die("<br>unable to update mpn_sections<br>");
echo "Renamed mpn_sections to " . $prefix . "_sections<br>";

$result = mysql_query("ALTER TABLE mpn_session RENAME " . $prefix . "_session") or die("<br>unable to update mpn_session<br>");
echo "Renamed mpn_session to " . $prefix . "_session<br>";

$result = mysql_query("ALTER TABLE mpn_stories RENAME " . $prefix . "_stories") or die("<br>unable to update mpn_stories<br>");
echo "Renamed mpn_stories to " . $prefix . "_stories<br>";

$result = mysql_query("ALTER TABLE mpn_stories_cat RENAME " . $prefix . "_stories_cat") or die("<br>unable to update mpn_stories_cat<br>");
echo "Renamed mpn_stories_cat to " . $prefix . "_stories_cat<br>";

$result = mysql_query("ALTER TABLE mpn_topics RENAME " . $prefix . "_topics") or die("<br>unable to update mpn_topics<br>");
echo "Renamed mpn_topics to " . $prefix . "_topics<br>";

$result = mysql_query("ALTER TABLE mpn_users RENAME " . $prefix . "_users") or die("<br>unable to update mpn_users<br>");
echo "Renamed mpn_users to " . $prefix . "_users<br>";

$result = mysql_query("CREATE TABLE " . $prefix . "_authors (
   aid varchar(30) NOT NULL,
   name varchar(50),
   url varchar(60),
   email varchar(60),
   pwd varchar(13),
   counter int(11) DEFAULT '0' NOT NULL,
   radminarticle tinyint(2) DEFAULT '0' NOT NULL,
   radmintopic tinyint(2) DEFAULT '0' NOT NULL,
   radminuser tinyint(2) DEFAULT '0' NOT NULL,
   radminsurvey tinyint(2) DEFAULT '0' NOT NULL,
   radminsection tinyint(2) DEFAULT '0' NOT NULL,
   radminlink tinyint(2) DEFAULT '0' NOT NULL,
   radminephem tinyint(2) DEFAULT '0' NOT NULL,
   radminfilem tinyint(2) DEFAULT '0' NOT NULL,
   radminfaq tinyint(2) DEFAULT '0' NOT NULL,
   radminforum tinyint(2) DEFAULT '0' NOT NULL,
   radmindownload tinyint(2) DEFAULT '0' NOT NULL,
   radminreviews tinyint(2) DEFAULT '0' NOT NULL,
   radminblocks tinyint(2) DEFAULT '0' NOT NULL,
   radminsuper tinyint(2) DEFAULT '1' NOT NULL,
   admlanguage varchar(30) NOT NULL,
   PRIMARY KEY (aid)
);") or die("<br>Unable to  create " . $prefix . "_authors");

$result = mysql_query("CREATE TABLE " . $prefix . "_autolinks (
   lid int(11) NOT NULL auto_increment,
   keyword varchar(100) NOT NULL,
   title varchar(100) NOT NULL,
   url varchar(200) NOT NULL,
   comment varchar(200),
   PRIMARY KEY (lid),
   UNIQUE keyword (keyword)
);") or die("<br>Unable to create " . $prefix . "_autolinks");

echo "<br>Created " . $prefix . "_autolinks";

$result = mysql_query("CREATE TABLE " . $prefix . "_blocks (
   bid int(10) unsigned NOT NULL auto_increment,
   bkey varchar(255) NOT NULL,
   title varchar(255) NOT NULL,
   content text NOT NULL,
   url varchar(255) NOT NULL,
   position char(1) DEFAULT 'l' NOT NULL,
   weight decimal(10,1) DEFAULT '0.0' NOT NULL,
   active tinyint(3) unsigned DEFAULT '1' NOT NULL,
   refresh int(10) unsigned DEFAULT '0' NOT NULL,
   last_update timestamp(14),
   blanguage varchar(30) NOT NULL,
   PRIMARY KEY (bid)
);") or die("<br>Unable to create " . $prefix . "_blocks");

$result = mysql_query("CREATE TABLE " . $prefix . "_blocks_buttons (
   id int(10) unsigned NOT NULL auto_increment,
   bid int(10) unsigned DEFAULT '0' NOT NULL,
   title varchar(255) NOT NULL,
   url varchar(255) NOT NULL,
   images longtext NOT NULL,
   PRIMARY KEY (id)
);") or die("<br>Unable to create " . $prefix . "_blocks_buttons");

echo "<br>Created " . $prefix . "_blocks_buttons";

$result = mysql_query("CREATE TABLE " . $prefix . "_downloads_categories (
   cid int(11) NOT NULL auto_increment,
   title varchar(50) NOT NULL,
   cdescription text NOT NULL,
   PRIMARY KEY (cid)
);") or die("<br>Unable to create " . $prefix . "_downloads_categories");

echo "<br>Created " . $prefix . "_downloads_categories";

$result = mysql_query("CREATE TABLE " . $prefix . "_downloads_downloads (
   lid int(11) NOT NULL auto_increment,
   cid int(11) DEFAULT '0' NOT NULL,
   sid int(11) DEFAULT '0' NOT NULL,
   title varchar(100) NOT NULL,
   url varchar(100) NOT NULL,
   description text NOT NULL,
   date datetime,
   name varchar(100) NOT NULL,
   email varchar(100) NOT NULL,
   hits int(11) DEFAULT '0' NOT NULL,
   submitter varchar(60) NOT NULL,
   downloadratingsummary double(6,4) DEFAULT '0.0000' NOT NULL,
   totalvotes int(11) DEFAULT '0' NOT NULL,
   totalcomments int(11) DEFAULT '0' NOT NULL,
   filesize int(11) DEFAULT '0' NOT NULL,
   version varchar(10) NOT NULL,
   homepage varchar(200) NOT NULL,
   PRIMARY KEY (lid)
);") or die("<br>Unable to create " . $prefix . "_downloads_downloads");

echo "<br>Created " . $prefix . "_downloads_downloads";

$result = mysql_query("CREATE TABLE " . $prefix . "_downloads_editorials (
   downloadid int(11) DEFAULT '0' NOT NULL,
   adminid varchar(60) NOT NULL,
   editorialtimestamp datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   editorialtext text NOT NULL,
   editorialtitle varchar(100) NOT NULL,
   PRIMARY KEY (downloadid)
);") or die("<br>Unable to create " . $prefix . "_downloads_editorials");

echo "<br>Created " . $prefix . "_downloads_editorials";

$result = mysql_query("CREATE TABLE " . $prefix . "_downloads_modrequest (
   requestid int(11) NOT NULL auto_increment,
   lid int(11) DEFAULT '0' NOT NULL,
   cid int(11) DEFAULT '0' NOT NULL,
   sid int(11) DEFAULT '0' NOT NULL,
   title varchar(100) NOT NULL,
   url varchar(100) NOT NULL,
   description text NOT NULL,
   modifysubmitter varchar(60) NOT NULL,
   brokendownload int(3) DEFAULT '0' NOT NULL,
   name varchar(100) NOT NULL,
   email varchar(100) NOT NULL,
   filesize int(11) DEFAULT '0' NOT NULL,
   version varchar(10) NOT NULL,
   homepage varchar(200) NOT NULL,
   PRIMARY KEY (requestid),
   UNIQUE requestid (requestid)
);") or die("<br>Unable to create " . $prefix . "_downloads_modrequest");

echo "<br>Created " . $prefix . "_downloads_modrequest";

$result = mysql_query("CREATE TABLE " . $prefix . "_downloads_newdownload (
   lid int(11) NOT NULL auto_increment,
   cid int(11) DEFAULT '0' NOT NULL,
   sid int(11) DEFAULT '0' NOT NULL,
   title varchar(100) NOT NULL,
   url varchar(100) NOT NULL,
   description text NOT NULL,
   name varchar(100) NOT NULL,
   email varchar(100) NOT NULL,
   submitter varchar(60) NOT NULL,
   filesize int(11) DEFAULT '0' NOT NULL,
   version varchar(10) NOT NULL,
   homepage varchar(200) NOT NULL,
   PRIMARY KEY (lid)
);") or die("<br>Unable to create " . $prefix . "_downloads_newdownload");

echo "<br>Created " . $prefix . "_downloads_newdownload";

$result = mysql_query("CREATE TABLE " . $prefix . "_downloads_subcategories (
   sid int(11) NOT NULL auto_increment,
   cid int(11) DEFAULT '0' NOT NULL,
   title varchar(50) NOT NULL,
   PRIMARY KEY (sid)
);") or die("<br>Unable to create " . $prefix . "_downloads_subcategories");

echo "<br>Created " . $prefix . "_downloads_subcategories";

$result = mysql_query("CREATE TABLE " . $prefix . "_downloads_votedata (
   ratingdbid int(11) NOT NULL auto_increment,
   ratinglid int(11) DEFAULT '0' NOT NULL,
   ratinguser varchar(60) NOT NULL,
   rating int(11) DEFAULT '0' NOT NULL,
   ratinghostname varchar(60) NOT NULL,
   ratingcomments text NOT NULL,
   ratingtimestamp datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   PRIMARY KEY (ratingdbid)
);") or die("<br>Unable to create " . $prefix . "_downloads_votedata");

echo "<br>Created " . $prefix . "_downloads_votedata";

$result = mysql_query("CREATE TABLE " . $prefix . "_message (
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
);") or die("<br>Unable to create " . $prefix . "_message");

echo "<br>Created " . $prefix . "_message";

$result = mysql_query("CREATE TABLE " . $prefix . "_poll_check (
   ip varchar(20) NOT NULL,
   time varchar(14) NOT NULL
);") or die("<br>Unable to create " . $prefix . "_poll_check");

echo "<br>Created " . $prefix . "_poll_check";

$result = mysql_query("CREATE TABLE " . $prefix . "_stats_date (
   date varchar(80) NOT NULL,
   hits int(11) unsigned DEFAULT '0' NOT NULL
);") or die("<br>Unable to create " . $prefix . "_stats_date");

echo "<br>Created " . $prefix . "_stats_date";

$result = mysql_query("CREATE TABLE " . $prefix . "_stats_hour (
   hour tinyint(2) unsigned DEFAULT '0' NOT NULL,
   hits int(10) unsigned DEFAULT '0' NOT NULL
);") or die("<br>Unable to create " . $prefix . "_stats_hour");

echo "<br>Created " . $prefix . "_stats_hour";

$result = mysql_query("CREATE TABLE " . $prefix . "_stats_month (
   month tinyint(2) unsigned DEFAULT '0' NOT NULL,
   hits int(10) unsigned DEFAULT '0' NOT NULL
);") or die("<br>Unable to create " . $prefix . "_stats_month");

echo "<br>Created " . $prefix . "_stats_month";

$result = mysql_query("CREATE TABLE " . $prefix . "_stats_week (
   weekday tinyint(1) unsigned DEFAULT '0' NOT NULL,
   hits int(10) unsigned DEFAULT '0' NOT NULL
);") or die("<br>Unable to create " . $prefix . "_stats_week");

echo "<br>Created " . $prefix . "_stats_week";

echo "Altering " . $prefix . "_autonews table<br>";
mysql_query("ALTER TABLE " . $prefix . "_autonews ADD alanguage varchar(30) NOT NULL");
echo "alanguage added<br>";
mysql_query("ALTER TABLE " . $prefix . "_autonews ADD withcomm int(1) DEFAULT '0' NOT NULL");
echo "withcomm added <br><br>";
echo "Altering " . $prefix . "_ephem table<br>";
mysql_query("ALTER TABLE " . $prefix . "_ephem ADD elanguage varchar(30) NOT NULL");
echo "elanguage added<br><br>";
echo "Altering " . $prefix . "_faqanswer table<br>";
mysql_query("ALTER TABLE " . $prefix . "_faqanswer ADD submittedby varchar(250) NOT NULL");
echo "submittedby added<br><br>";
echo "Altering " . $prefix . "_faqcategories table<br>";
mysql_query("ALTER TABLE " . $prefix . "_faqcategories ADD flanguage varchar(30) NOT NULL");
echo "flanguage added<br>";
mysql_query("ALTER TABLE " . $prefix . "_faqcategories ADD parent_id tinyint(3) DEFAULT '0' NOT NULL");
echo "parent_id added<br><br>";
echo "Altering " . $prefix . "_headlines table<br>";
mysql_query("ALTER TABLE " . $prefix . "_headlines ADD rssuser varchar(10)");
echo "rssuser added<br>";
mysql_query("ALTER TABLE " . $prefix . "_headlines ADD rsspasswd varchar(10)");
echo "rsspasswd added<br>";
mysql_query("ALTER TABLE " . $prefix . "_headlines ADD use_proxy tinyint(3) DEFAULT '0' NOT NULL");
echo "use_proxy added<br>";
mysql_query("ALTER TABLE " . $prefix . "_headlines ADD maxrows tinyint(3) DEFAULT '10' NOT NULL");
echo "maxrows added<br>";
mysql_query("ALTER TABLE " . $prefix . "_headlines ADD options varchar(20)");
echo "options added<br>";
mysql_query("ALTER TABLE " . $prefix . "_headlines DROP status");
echo "status dropped<br>";
mysql_query("ALTER TABLE " . $prefix . "_headlines CHANGE url siteurl varchar(255) NOT NULL");
echo "url changed to siteurl<br>";
mysql_query("ALTER TABLE " . $prefix . "_headlines CHANGE headlinesurl rssurl varchar(255) NOT NULL");
echo "headlinesurl changed to rssurl<br><br>";
echo "Altering " . $prefix . "_poll_desc table<br>";
mysql_query("ALTER TABLE " . $prefix . "_poll_desc ADD planguage varchar(30) NOT NULL");
echo "planguage added<br><br>";
echo "Altering " . $prefix . "_queue table<br>";
mysql_query("ALTER TABLE " . $prefix . "_queue ADD arcd int(1) DEFAULT '0' NOT NULL");
echo "arcd added<br>";
mysql_query("ALTER TABLE " . $prefix . "_queue ADD alanguage varchar(30) NOT NULL");
echo "alanguage added<br><br>";
echo "Altering " . $prefix . "_referer table<br>";
mysql_query("ALTER TABLE " . $prefix . "_referer ADD frequency int(15)");
echo "frquency added<br><br>";
echo "Altering " . $prefix . "_reviews table<br>";
mysql_query("ALTER TABLE " . $prefix . "_reviews ADD rlanguage varchar(30) NOT NULL");
echo "rlanguage added<br><br>";
echo "Altering " . $prefix . "_reviews_add table<br>";
mysql_query("ALTER TABLE " . $prefix . "_reviews_add ADD rlanguage varchar(30) NOT NULL");
echo "rlanguage added<br><br>";
echo "Altering " . $prefix . "_seccont table<br>";
mysql_query("ALTER TABLE " . $prefix . "_seccont ADD slanguage varchar(30) NOT NULL");
echo "slanguage added<br>";
mysql_query("ALTER TABLE " . $prefix . "_seccont DROP byline");
echo "byline dropped";
mysql_query("ALTER TABLE " . $prefix . "_seccont DROP author");
echo "author dropped";
mysql_query("ALTER TABLE " . $prefix . "_seccont DROP authoremail");
echo "authoremail dropped";
mysql_query("ALTER TABLE " . $prefix . "_seccont DROP date");
echo "date dropped<br><br>";
echo "Altering " . $prefix . "_sections table<br>";
mysql_query("ALTER TABLE " . $prefix . "_section DROP secdesc");
echo "secdesc dropped<br><br>";
echo "Altering " . $prefix . "_session table<br>";
mysql_query("ALTER TABLE " . $prefix . "_session DROP forum_pass");
echo "forum_pass dropped<br><br>";
echo "Altering " . $prefix . "_stories table<br>";
mysql_query("ALTER TABLE " . $prefix . "_stories ADD themeoverride varchar(30) NOT NULL");
echo "themeoverride added<br>";
mysql_query("ALTER TABLE " . $prefix . "_stories ADD alanguage varchar(30) NOT NULL");
echo "alanguage added<br>";
mysql_query("ALTER TABLE " . $prefix . "_stories ADD withcomm int(1) DEFAULT '0' NOT NULL");
echo "withcomm added<br><br>";
echo "Altering " . $prefix . "_stories_cat table<br>";
mysql_query("ALTER TABLE " . $prefix . "_stories_cat ADD themeoverride varchar(30) NOT NULL");
echo "themeoverride added<br><br>";
echo "Altering " . $prefix . "_users table<br>";
mysql_query("ALTER TABLE " . $prefix . "_users ADD timezone_offset float(3,1) DEFAULT '0.0' NOT NULL");
echo "timezone_offset added<br>";
mysql_query("ALTER TABLE " . $prefix . "_users DROP user_from_flag");
echo "user_from_flag dropped<br>";
mysql_query("ALTER TABLE " . $prefix . "_users DROP allowemail");
echo "allowemail dropped<br>";
mysql_query("ALTER TABLE " . $prefix . "_users DROP emailpass");
echo "emailpass dropped<br><br>";

$result = mysql_query("select message_id, title, text, time, display from mpn_mymsg2");
list($msgid, $title, $text, $time, $display) = mysql_fetch_row($result);
$result = mysql_query("insert into " . $prefix . "_message values ($msgid, '$title', '$text', '$time', '', '$display', '1', '')");

$result = mysql_query("INSERT INTO " . $prefix . "_authors VALUES ('', 'Admin', 'Admin', 'http://www.postnuke.com', 'my@home.com', 'Change', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1', '')") or die ("<b>Unable to update " . $prefix . "_authors</b>");

mysql_query("INSERT INTO " . $prefix . "_blocks VALUES ( '1', 'admin', 'Administration', 'Admins can have its own box, but just one. Who need more?<br>
Add the options you like. This box will appear only if you
has been logged like Admin. No others users can view this.<br>
<strong><big>·</big></strong> <a href=\"admin.php\">Administration</a><br>
<strong><big>·</big></strong> <a href=\"user.php?module=NS-User&op=logout\">Logout</a>', '', 'l', '0.5', '0', '0', '00000000000000', '')") or die ("<b>Unable to update " . $prefix . "_blocks</b>");
mysql_query("INSERT INTO " . $prefix . "_blocks VALUES ( '2', 'link', 'Main Menu', 'index.php|Home
user.php|Your Account
', '1', 'l', '1.0', '1', '0', '00000000000000', 'eng')") or die ("<b>Unable to update " . $prefix . "_blocks</b>");
mysql_query("INSERT INTO " . $prefix . "_blocks VALUES ( '3', 'online', 'Who\'s Online', '', '', 'l', '2.0', '1', '0', '00000000000000', 'eng')") or die ("<b>Unable to update " . $prefix . "_blocks</b>");
mysql_query("INSERT INTO " . $prefix . "_blocks VALUES ( '4', 'alink', 'Administration', '
admin.php|Administration
admin.php?op=logout|Logout', '1', 'l', '3.0', '1', '0', '00000000000000', 'eng')") or die ("<b>Unable to update " . $prefix . "_blocks</b>");
mysql_query("INSERT INTO " . $prefix . "_blocks VALUES ( '5', 'user', 'Users Block', 'Put anything you want here', '', 'l', '3.5', '1', '0', '00000000000000', '')") or die ("<b>Unable to update " . $prefix . "_blocks</b>");
mysql_query("INSERT INTO " . $prefix . "_blocks VALUES ( '6', 'search', 'Search Box', '', '', 'l', '4.0', '0', '0', '00000000000000', '')") or die ("<b>Unable to update " . $prefix . "_blocks</b>");
mysql_query("INSERT INTO " . $prefix . "_blocks VALUES ( '7', 'ephem', 'Ephemerids', '', '', 'l', '5.0', '0', '0', '00000000000000', '')") or die ("<b>Unable to update " . $prefix . "_blocks</b>");
mysql_query("INSERT INTO " . $prefix . "_blocks VALUES ( '8', 'thelang', 'Languages', '', '', 'l', '6.0', '1', '0', '00000000000000', '')") or die ("<b>Unable to update " . $prefix . "_blocks</b>");
mysql_query("INSERT INTO " . $prefix . "_blocks VALUES ( '9', 'category', 'Categories Menu', '', '', 'r', '1.0', '1', '0', '00000000000000', '')") or die ("<b>Unable to update " . $prefix . "_blocks</b>");
mysql_query("INSERT INTO " . $prefix . "_blocks VALUES ( '10', 'random', 'Random Headlines', '', '', 'r', '2.0', '0', '0', '00000000000000', '')") or die ("<b>Unable to update " . $prefix . "_blocks</b>");
mysql_query("INSERT INTO " . $prefix . "_blocks VALUES ( '11', 'poll', 'Survey', '', '', 'r', '3.0', '1', '0', '00000000000000', '')") or die ("<b>Unable to update " . $prefix . "_blocks</b>");
mysql_query("INSERT INTO " . $prefix . "_blocks VALUES ( '12', 'big', 'Today\'s Big Story', '', '', 'r', '4.0', '1', '0', '00000000000000', '')") or die ("<b>Unable to update " . $prefix . "_blocks</b>");
mysql_query("INSERT INTO " . $prefix . "_blocks VALUES ( '13', 'login', 'User\'s Login', '', '', 'r', '5.0', '1', '0', '00000000000000', '')") or die ("<b>Unable to update " . $prefix . "_blocks</b>");
mysql_query("INSERT INTO " . $prefix . "_blocks VALUES ( '14', 'past', 'Past Articles', '', '', 'r', '6.0', '1', '0', '00000000000000', '')") or die ("<b>Unable to update " . $prefix . "_blocks</b>");

echo "<br><font class=\"post-sub\">" . $prefix . "_blocks Updated.</font>";

mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '0', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '1', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '2', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '3', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '4', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '5', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '6', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '7', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '8', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '9', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '10', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '11', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '12', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '13', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '14', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '15', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '16', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '17', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '18', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '19', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '20', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '21', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '22', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '23', '0')");

mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '1', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '2', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '3', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '4', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '5', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '6', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '7', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '8', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '9', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '10', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '11', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '12', '0')");

mysql_query("INSERT INTO " . $prefix . "_stats_week (weekday, hits) VALUES ( '0', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_week (weekday, hits) VALUES ( '1', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_week (weekday, hits) VALUES ( '2', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_week (weekday, hits) VALUES ( '3', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_week (weekday, hits) VALUES ( '4', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_week (weekday, hits) VALUES ( '5', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_week (weekday, hits) VALUES ( '6', '0')");

echo "Altering " . $prefix . "_users table<br>";
mysql_query("ALTER TABLE " . $prefix . "_users ADD posts int(10) DEFAULT '0'");
echo "posts added<br>";
mysql_query("ALTER TABLE " . $prefix . "_users ADD attachsig int(2) DEFAULT '0'");
echo "attachsig added <br>";
mysql_query("ALTER TABLE " . $prefix . "_users ADD rank int(10) DEFAULT '0'");
echo "rank added<br>";
mysql_query("ALTER TABLE " . $prefix . "_users ADD level int(10) DEFAULT '1'");
echo "level added<br>";

@$result = mysql_query("SELECT * FROM mpn_users_status");
while ($row = mysql_fetch_array($result)) {
    $row[uid] = $row[uid];
    $row[posts] = addslashes($row[posts]);
    $row[attachsig] = addslashes($row[attachsig]);
    $row[rank] = addslashes($row[rank]);
    $row[level] = addslashes($row[level]);
    mysql_query("UPDATE " . $prefix . "_users SET posts='$row[posts]', attachsig='$row[attachsig]', rank='$row[rank]', level='$row[level]' WHERE uid=$row[uid]");
} 

$result = mysql_query("ALTER TABLE mpn_categories RENAME " . $prefix . "_catagories") or die("<br>unable to update mpn_categories<br>");
echo "Renamed mpn_categories to " . $prefix . "_catagories<br>";

$result = mysql_query("ALTER TABLE mpn_forums RENAME " . $prefix . "_forums") or die("<br>unable to update mpn_forums<br>");
echo "Renamed mpn_forums to " . $prefix . "_forums<br>";

$result = mysql_query("ALTER TABLE mpn_forumtopics RENAME " . $prefix . "_forumtopics") or die("<br>unable to update mpn_forumtopics<br>");
echo "Renamed mpn_forumtopics to " . $prefix . "_forumtopics<br>";

$result = mysql_query("ALTER TABLE mpn_forumconfig RENAME " . $prefix . "_config") or die("<br>unable to update mpn_forumconfig<br>");
echo "Renamed mpn_forumconfig to " . $prefix . "_config<br>";

$result = mysql_query("ALTER TABLE mpn_posts RENAME " . $prefix . "_posts") or die("<br>unable to update mpn_posts<br>");
echo "Renamed mpn_posts to " . $prefix . "_posts<br>";

$result = mysql_query("ALTER TABLE mpn_ranks RENAME " . $prefix . "_ranks") or die("<br>unable to update mpn_ranks<br>");
echo "Renamed mpn_ranks to " . $prefix . "_ranks<br>";

$result = mysql_query("ALTER TABLE mpn_access RENAME " . $prefix . "_access") or die("<br>unable to update mpn_access<br>");
echo "Renamed mpn_access to " . $prefix . "_access<br>";

$result = mysql_query("ALTER TABLE mpn_smiles RENAME " . $prefix . "_smiles") or die("<br>unable to update mpn_smiles<br>");
echo "Renamed mpn_smiles to " . $prefix . "_smiles<br>";
// jwallenhorst's default autolink data
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('autolink', 'AutoLink', ' http://www.nusite.de', 'AutoLinking for PostNuke')");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('postnuke', 'PostNuke', ' http://www.postnuke.com', 'PostNuke Portal System')");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('gpl', 'GPL', ' http://www.gnu.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('linux', 'Linux.com', ' http://www.linux.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('blender', 'Blender', ' http://www.blender.nl', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('gnu', 'GNU Project', ' http://www.gnu.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('gimp', 'The GIMP', ' http://www.gimp.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('cnn', 'CNN.com', ' http://www.cnn.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('news.com', 'News.com', ' http://www.news.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('ibm', 'IBM', ' http://www.ibm.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('php', 'PHP HomePage', ' http://www.php.net', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('mandrake', 'Mandrakesoft', ' http://www.mandrakesoft.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('redhat', 'Red Hat', ' http://www.redhat.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('red hat', 'Red Hat', ' http://www.redhat.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('Debian', 'Debian GNU/Linux', ' http://www.debian.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('slackware', 'Slackware', ' http://www.slackware.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('freebsd', 'FreeBsD', ' http://www.freebsd.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('artist', 'Linux Artist', ' http://www.linuxartist.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('games', 'Linux Games', ' http://www.linuxgames.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('sourceforge', 'SourceForge', ' http://www.sourceforge.net', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('source forge', 'SourceForge', ' http://www.sourceforge.net', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('palm pilot', 'Palm Pilot', ' http://www.palm.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('windows', 'Microsoft', ' http://www.microsoft.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('microsoft', 'Microsoft', ' http://www.microsoft.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('kernel', 'Linux Kernel Archives', ' http://www.kernel.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('opensource', 'Opensource', ' http://www.opensource.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('open source', 'Opensource', ' http://www.opensource.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('nuke', 'PostNuke', ' http://www.postnuke.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('compaq', 'Compaq', ' http://www.compaq.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('intel', 'Intel', ' http://www.intel.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('mysql', 'MysQL Database server', ' http://www.mysql.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('themes', 'Themes.org', ' http://www.themes.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('suse', 'SuSE', ' http://www.suse.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('script', 'Hotscripts', ' http://www.hotscripts.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('amd', 'AMD', ' http://www.amd.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('transmeta', 'Transmeta', ' http://www.transmeta.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('apple', 'Apple', ' http://www.apple.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('apache', 'Apache Web server', ' http://www.apache.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('nasa', 'NASA', ' http://www.nasa.gov', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('documentation', 'Linux Manuals', ' http://www.linuxdoc.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('manual', 'Linux Manuals', ' http://www.linuxdoc.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('howto', 'Linux Manuals', ' http://www.linuxdoc.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('rtfm', 'Linux Manuals', ' http://www.linuxdoc.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('dell', 'Dell', ' http://www.dell.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('google', 'Google search Engine', ' http://www.google.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('translate', 'Babelfish Translator', ' http://babelfish.altavista.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('w3', 'W3 Consortium', ' http://www.w3.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('cs', 'Cs standard', ' http://www.w3.org/style/Cs', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('html', 'HTML standard', ' http://www.w3.org/MarkUp', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('xhmtl', 'XHTML standard', ' http://www.w3.org/MarkUp', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('rpm', 'RPM', ' http://www.rpm.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('3com', '3Com', ' http://www.3com.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('sun microsystems', 'Sun Microsystems', ' http://www.sun.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('staroffice', 'Star Office', ' http://www.sun.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('star office', 'Star Office', ' http://www.sun.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('openoffice', 'Open Office', ' http://www.openoffice.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('open office', 'Open Office', ' http://www.openoffice.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('oracle', 'Oracle', ' http://www.oracle.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('informix', 'Informix', ' http://www.informix.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('postgre', 'PostgresQL', ' http://www.postgresql.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('mp3', 'MP3.com', ' http://www.mp3.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('gnome', 'GNOME', ' http://www.gnome.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('kde', 'KDE', ' http://www.kde.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('mozilla', 'Mozilla', ' http://www.mozilla.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('netscape', 'Netscape', ' http://www.netscape.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('corel', 'Corel', ' http://www.corel.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('hp', 'Hewlett Packard', ' http://www.hp.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('hewlett packard', 'Hewlett Packard', ' http://www.hp.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('caldera', 'Caldera systems', ' http://www.caldera.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('freshmeat', 'Freshmeat', ' http://www.freshmeat.net', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('slashdot', 'Slashdot', ' http://www.slashdot.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('spam', 'Spam Cop', ' http://www.spamcop.net', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('aol', 'America Online', ' http://www.aol.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('america online', 'America Online', ' http://www.aol.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('pov-ray', 'POV Ray', ' http://www.povray.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('povray', 'POV Ray', ' http://www.povray.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('pov ray', 'POV Ray', ' http://www.povray.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('seti', 'SETI Institute', ' http://www.seti.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('cnet', 'C|Net News', ' http://www.cnet.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('zdnet', 'ZDNet News', ' http://www.zdnet.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('napster', 'Napster', ' http://www.napster.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('sony', 'Sony HomePage', ' http://www.sony.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('xfree', 'X-Free86 Project', ' http://www.xfree.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('x-free', 'X-Free86 Project', ' http://www.xfree.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('beos', 'BeOS', ' http://www.beos.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('borland', 'Borland', ' http://www.borland.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('kylix', 'Kylix HomePage', ' http://www.borland.com/kylix', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('amazon', 'Amazon.com', ' http://www.amazon.com', NULL)");
// john's changes
mysql_query("ALTER TABLE " . $prefix . "_banner CHANGE imageurl imageurl VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE " . $prefix . "_banner CHANGE clickurl clickurl VARCHAR(255) NOT NULL");

$q = "
    CREATE TABLE " . $prefix . "_temp_links_categories
    (
        cat_id int(11) NOT NULL auto_increment,
        parent_id int(11),
        title varchar(50) NOT NULL,
        cdescription text,
        PRIMARY KEY (cat_id)
    )
    ";
$result = mysql_query($q);

$q = "
    CREATE TABLE " . $prefix . "_temp_links_links
    (
        lid int(11) NOT NULL auto_increment,
        cat_id int(11),
        title varchar(100) NOT NULL,
        url varchar(100) NOT NULL,
        description text NOT NULL,
        date datetime,
        name varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        hits int(11) NOT NULL,
        submitter varchar(60) NOT NULL,
        linkratingsummary double(6,4) DEFAULT '0.0000' NOT NULL,
        totalvotes int(11) NOT NULL,
        totalcomments int(11) NOT NULL,
        PRIMARY KEY (lid)
    )
    ";
$result = mysql_query($q);

$q = "ALTER TABLE " . $prefix . "_links_newlink CHANGE cid cat_id INT(11) NOT NULL";
$result = mysql_query($q);

$result = mysql_query("SELECT cid,title,cdescription FROM " . $prefix . "_links_categories");
while (list($cid, $title, $cdescription) = @mysql_fetch_row($result)) {
    if (!get_magic_quotes_runtime()) {
        $title = addslashes($title);
    } 
    mysql_query("INSERT INTO " . $prefix . "_temp_links_categories VALUES ('',0,'$title','$cdescription')");
    $newcid = mysql_insert_id();
    $result2 = mysql_query("SELECT title, url, description, date, name, email, hits, submitter, linkratingsummary, totalvotes, totalcomments FROM " . $prefix . "_links_links WHERE cid=$cid AND sid=0");
    while (list($ltitle, $url, $description, $date, $name, $email, $hits, $submitter, $linkratingsummary, $totalvotes, $totalcomments) = mysql_fetch_row($result2)) {
        if (!get_magic_quotes_runtime()) {
            $ltitle = addslashes($ltitle);
            $description = addslashes($description);
            $name = addslashes($name);
            $email = addslashes($email);
        } 
        mysql_query("INSERT INTO " . $prefix . "_temp_links_links VALUES ('','$newcid','$ltitle','$url','$description','$date','$name','$email','$hits','$submitter','$linkratingsummary','$totalvotes','$totalcomments')");
    } 
    $result3 = mysql_query("SELECT sid,title FROM " . $prefix . "_links_subcategories WHERE cid=$cid");
    while (list($sid, $stitle) = mysql_fetch_row($result3)) {
        if (!get_magic_quotes_runtime()) {
            $stitle = addslashes($stitle);
        } 
        mysql_query("INSERT INTO " . $prefix . "_temp_links_categories VALUES ('','$newcid','$stitle','')");
        $newscid = mysql_insert_id();
        $result4 = mysql_query("SELECT title, url, description, date, name, email, hits, submitter, linkratingsummary, totalvotes, totalcomments FROM " . $prefix . "_links_links where cid=$cid and sid=$sid");
        while (list($sltitle, $surl, $sdescription, $sdate, $sname, $semail, $shits, $ssubmitter, $slinkratingsummary, $stotalvotes, $stotalcomments) = mysql_fetch_row($result4)) {
            if (!get_magic_quotes_runtime()) {
                $sltitle = addslashes($sltitle);
                $sdescription = addslashes($sdescription);
                $sname = addslashes($sname);
                $semail = addslashes($semail);
            } 
            mysql_query("INSERT INTO " . $prefix . "_temp_links_links VALUES ('','$newscid','$sltitle','$surl','$sdescription','$sdate','$sname','$semail','$shits','$ssubmitter','$slinkratingsummary','$stotalvotes','$stotalcomments')");
        } 
    } 
} 
// Drop old tables
mysql_query("DROP TABLE " . $prefix . "_links_categories");
mysql_query("DROP TABLE " . $prefix . "_links_links");
mysql_query("DROP TABLE " . $prefix . "_links_subcategories"); 
// Rename temporary tables to new tables
mysql_query("ALTER TABLE " . $prefix . "_temp_links_categories RENAME " . $prefix . "_links_categories");
mysql_query("ALTER TABLE " . $prefix . "_temp_links_links RENAME " . $prefix . "_links_links"); 
// Conversion for downloads
mysql_query("ALTER TABLE " . $prefix . "_downloads ADD cid int(10)");

$result1 = mysql_query("SELECT dcategory, count(*) FROM " . $prefix . "_downloads group by dcategory");
while
(list($dcategory) = mysql_fetch_row($result1)) {
    mysql_query("INSERT INTO " . $prefix . "_downloads_categories VALUES ('', '$dcategory', '$dcategory')");
} 

$result = mysql_query("SELECT cid, title FROM " . $prefix . "_downloads_categories");
while
(list($cid, $title) = mysql_fetch_row($result)) {
    mysql_query("UPDATE " . $prefix . "_downloads SET cid='$cid' WHERE dcategory='$title'");
} 

$result2 = mysql_query("SELECT * FROM " . $prefix . "_downloads");
while ($row = mysql_fetch_array($result2)) {
    $row[did] = $row[did];
    $row[dcounter] = $row[dcounter];
    $row[durl] = addslashes($row[durl]);
    $row[dfilename] = addslashes($row[dfilename]);
    $row[dfilesize] = addslashes($row[dfilesize]);
    $row[ddate] = addslashes($row[ddate]);
    $row[dweb] = addslashes($row[dweb]);
    $row[duser] = addslashes($row[duser]);
    $row[dver] = addslashes($row[dver]);
    $row[dcategory] = $row[dcategory];
    $row[ddescription] = addslashes($row[ddescription]);
    $row[privs] = $row[privs];
    $row[cid] = $row[cid];
    mysql_query("INSERT INTO " . $prefix . "_downloads_downloads VALUES ('', '$row[cid]', '', '$row[dfilename]', '$row[durl]', '$row[ddescription]', '$row[ddate]', '$row[duser]', '', '$row[dcounter]', '', '', '', '', '$row[dfilesize]', '$row[dver]', '$row[dweb]')");
} 

mysql_query("DROP TABLE " . $prefix . "_downloads");

mysql_query("DROP TABLE mpn_blocks");
mysql_query("DROP TABLE eventcal");
mysql_query("DROP TABLE mpn_calendar");
mysql_query("DROP TABLE mpn_chatbox");
mysql_query("DROP TABLE mpn_click");
mysql_query("DROP TABLE mpn_combo_country");
mysql_query("DROP TABLE mpn_combo_daynum");
mysql_query("DROP TABLE mpn_combo_industry");
mysql_query("DROP TABLE mpn_combo_monnum");
mysql_query("DROP TABLE mpn_combo_occ");
mysql_query("DROP TABLE mpn_combo_yearnum");
mysql_query("DROP TABLE mpn_contactbook");
mysql_query("DROP TABLE mpn_everyonenet_settings");
mysql_query("DROP TABLE mpn_eventcal");
mysql_query("DROP TABLE mpn_flags");
mysql_query("DROP TABLE mpn_forummods");
mysql_query("DROP TABLE mpn_gallcats");
mysql_query("DROP TABLE mpn_gallcomments");
mysql_query("DROP TABLE mpn_gallery");
mysql_query("DROP TABLE mpn_gallery_categories");
mysql_query("DROP TABLE mpn_gallery_comments");
mysql_query("DROP TABLE mpn_gallery_media_types");
mysql_query("DROP TABLE mpn_gallery_media_class");
mysql_query("DROP TABLE mpn_gallery_pictures");
mysql_query("DROP TABLE mpn_gallery_pictures_newpicture");
mysql_query("DROP TABLE mpn_gallery_rate_check");
mysql_query("DROP TABLE mpn_gallery_template_types");
mysql_query("DROP TABLE mpn_guestbook");
mysql_query("DROP TABLE mpn_pager");
mysql_query("DROP TABLE mpn_popsettings");
mysql_query("DROP TABLE mpn_updatelog");
mysql_query("DROP TABLE mpn_users_status");
mysql_query("DROP TABLE mpn_mymsg2");
mysql_query("DROP TABLE mpn_yp_brokenlink");
mysql_query("DROP TABLE mpn_yp_business");
mysql_query("DROP TABLE mpn_yp_businesstxt");
mysql_query("DROP TABLE mpn_yp_categories");
mysql_query("DROP TABLE mpn_yp_modrequest");
mysql_query("DROP TABLE mpn_yp_votedata");
mysql_query("DROP TABLE mpn_authors");

?>