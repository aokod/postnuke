<?php 
// File: $Id: pn4.php 15630 2005-02-04 06:35:42Z jorg $
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
// Poll Check table creation
$result = mysql_query("CREATE TABLE poll_check (
ip VARCHAR (20) NOT NULL,
time VARCHAR (14) NOT NULL
)");

$result = mysql_query("CREATE TABLE blocks (
bid INT (10) DEFAULT '0' not null AUTO_INCREMENT,
bkey VARCHAR (15) NOT NULL,
title VARCHAR (60) NOT NULL,
content TEXT NOT NULL,
url VARCHAR (200) NOT NULL,
position VARCHAR (1) NOT NULL,
weight INT (10) DEFAULT '1' NOT NULL,
active INT (1) DEFAULT '1' NOT NULL,
refresh INT (10) DEFAULT '0' NOT NULL,
time VARCHAR (14) DEFAULT '0' NOT NULL,
PRIMARY KEY (bid)
)");
// Main Block data migration
$result = mysql_query("SELECT title, content FROM mainblock");
list($title, $content) = mysql_fetch_row($result);
$result = mysql_query("INSERT INTO blocks VALUES (NULL, 'main', '$title', '$content', '', 'l', '1', '1', '', '')");
// Block data creation
$result = mysql_query("INSERT INTO blocks VALUES (NULL, 'online', 'Who\'s Online', '', '', 'l', '2', '1', '', '')");
// Admin Block data migration
$result = mysql_query("SELECT title, content FROM adminblock");
list($title, $content) = mysql_fetch_row($result);
$result = mysql_query("INSERT INTO blocks VALUES (NULL, 'admin', '$title', '$content', '', 'l', '3', '1', '', '')");
mysql_query("DROP TABLE adminblock");
// Blocks data creation
$result = mysql_query("INSERT INTO blocks VALUES (NULL, 'search', 'Search Box', '', '', 'l', '4', '0', '', '')");
$result = mysql_query("INSERT INTO blocks VALUES (NULL, 'ephem', 'Ephemerids', '', '', 'l', '5', '0', '', '')");
$result = mysql_query("INSERT INTO blocks VALUES (NULL, 'thelang', 'Languages', '', '', 'l', '6', '1', '', '')");
// Left Blocks data migration
$result = mysql_query("select title, content from lblocks");
$count = 7;
while (list($title, $content) = mysql_fetch_row($result)) {
    mysql_query("INSERT INTO blocks VALUES (NULL, 'html', '$title', '$content', '', 'l', '$count', '1', '', '')");
    $count++;
} 
// Blocks data creation
mysql_query("INSERT INTO blocks VALUES (NULL, 'user', 'User\'s Custom Box', '', '', 'r', '1', '1', '', '')");
mysql_query("INSERT INTO blocks VALUES (NULL, 'category', 'Categories Menu', '', '', 'r', '2', '1', '', '')");
mysql_query("INSERT INTO blocks VALUES (NULL, 'random', 'Random Headlines', '', '', 'r', '3', '0', '', '')");
mysql_query("INSERT INTO blocks VALUES (NULL, 'poll', 'Surveys', '', '', 'r', '4', '1', '', '')");
mysql_query("INSERT INTO blocks VALUES (NULL, 'big', 'Today\'s Big Story', '', '', 'r', '5', '1', '', '')");
mysql_query("INSERT INTO blocks VALUES (NULL, 'login', 'User\'s Login', '', '', 'r', '6', '1', '', '')");
mysql_query("INSERT INTO blocks VALUES (NULL, 'past', 'Past Articles', '', '', 'r', '7', '1', '', '')");
// Right Blocks data migration
$result = mysql_query("select title, content from rblocks");
$count = 8;
while (list($title, $content) = mysql_fetch_row($result)) {
    mysql_query("INSERT INTO blocks VALUES (NULL, 'html', '$title', '$content', '', 'r', '$count', '1', '', '')");
    $count++;
} 
// Authors table alteration
mysql_query("ALTER TABLE authors DROP radminleft");
mysql_query("ALTER TABLE authors DROP radminright");
mysql_query("ALTER TABLE authors DROP radminmain");
mysql_query("ALTER TABLE authors DROP radminhead");
mysql_query("ALTER TABLE authors DROP radminforum");
// Headlines table alteration
mysql_query("ALTER TABLE headlines DROP url");
mysql_query("ALTER TABLE headlines DROP status");
// Home Messages table creation
mysql_query("CREATE TABLE message (title VARCHAR (100) not null , content TEXT not null , date VARCHAR (14) not null , expire INT (7) not null , active INT (1) DEFAULT '1' not null , view INT (1) DEFAULT '1' not null )");
// Reviews table alteration
mysql_query("ALTER TABLE reviews CHANGE email email VARCHAR (60)");
mysql_query("ALTER TABLE reviews_add CHANGE email email VARCHAR (60)");
// Download table alteration and new tables creation
mysql_query("ALTER TABLE downloads DROP privs");
/**
 * Does this make any sense? nahhh
 */

$result = mysql_query("CREATE TABLE " . $prefix . "_downloads_categories (
  cid int(11) NOT NULL auto_increment,
  title varchar(50) NOT NULL default '',
  cdescription text NOT NULL,
  PRIMARY KEY  (cid)
)");

$result = mysql_query("CREATE TABLE " . $prefix . "_downloads_editorials (
  downloadid int(11) NOT NULL default '0',
  adminid varchar(60) NOT NULL default '',
  editorialtimestamp datetime NOT NULL default '0000-00-00 00:00:00',
  editorialtext text NOT NULL,
  editorialtitle varchar(100) NOT NULL default '',
  PRIMARY KEY  (downloadid)
)");

$result = mysql_query("CREATE TABLE " . $prefix . "_downloads_downloads (
  lid int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  sid int(11) NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  description text NOT NULL,
  date datetime default NULL,
  name varchar(100) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  hits int(11) NOT NULL default '0',
  submitter varchar(60) NOT NULL default '',
  downloadratingsummary double(6,4) NOT NULL default '0.0000',
  totalvotes int(11) NOT NULL default '0',
  totalcomments int(11) NOT NULL default '0',
  filesize int(11) NOT NULL default '0',
  version varchar(10) NOT NULL default '0',
  homepage varchar(200) NOT NULL default '',
  PRIMARY KEY  (lid)
)");

$result = mysql_query("CREATE TABLE " . $prefix . "_downloads_modrequest (
  requestid int(11) NOT NULL auto_increment,
  lid int(11) NOT NULL default '0',
  cid int(11) NOT NULL default '0',
  sid int(11) NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  description text NOT NULL,
  modifysubmitter varchar(60) NOT NULL default '',
  brokendownload int(3) NOT NULL default '0',
  name varchar(100) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  filesize int(11) NOT NULL default '0',
  version varchar(10) NOT NULL default '0',
  homepage varchar(200) NOT NULL default '',
  PRIMARY KEY  (requestid),
  UNIQUE KEY requestid (requestid)
)");

$result = mysql_query("CREATE TABLE " . $prefix . "_downloads_newdownload (
  lid int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  sid int(11) NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  description text NOT NULL,
  name varchar(100) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  submitter varchar(60) NOT NULL default '',
  filesize int(11) NOT NULL default '0',
  version varchar(10) NOT NULL default '0',
  homepage varchar(200) NOT NULL default '',
  PRIMARY KEY  (lid)
)");

$result = mysql_query("CREATE TABLE " . $prefix . "_downloads_subcategories (
  sid int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  title varchar(50) NOT NULL default '',
  PRIMARY KEY  (sid)
)");

$result = mysql_query("CREATE TABLE " . $prefix . "_downloads_votedata (
  ratingdbid int(11) NOT NULL auto_increment,
  ratinglid int(11) NOT NULL default '0',
  ratinguser varchar(60) NOT NULL default '',
  rating int(11) NOT NULL default '0',
  ratinghostname varchar(60) NOT NULL default '',
  ratingcomments text NOT NULL,
  ratingtimestamp datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (ratingdbid)
)");
// All tables renaming to ".$prefix."_
mysql_query("ALTER TABLE authors RENAME " . $prefix . "_authors");
mysql_query("ALTER TABLE autonews RENAME " . $prefix . "_autonews");
mysql_query("ALTER TABLE banner RENAME " . $prefix . "_banner");
mysql_query("ALTER TABLE bannerclient RENAME " . $prefix . "_bannerclient");
mysql_query("ALTER TABLE bannerfinish RENAME " . $prefix . "_bannerfinish");
mysql_query("ALTER TABLE comments RENAME " . $prefix . "_comments");
mysql_query("ALTER TABLE counter RENAME " . $prefix . "_counter");
mysql_query("ALTER TABLE ephem RENAME " . $prefix . "_ephem");
mysql_query("ALTER TABLE faqAnswer RENAME " . $prefix . "_faqanswer");
mysql_query("ALTER TABLE faqCategories RENAME " . $prefix . "_faqcategories");
mysql_query("ALTER TABLE headlines RENAME " . $prefix . "_headlines");
mysql_query("ALTER TABLE links_categories RENAME " . $prefix . "_links_categories");
mysql_query("ALTER TABLE links_editorials RENAME " . $prefix . "_links_editorials");
mysql_query("ALTER TABLE links_links RENAME " . $prefix . "_links_links");
mysql_query("ALTER TABLE links_modrequest RENAME " . $prefix . "_links_modrequest");
mysql_query("ALTER TABLE links_newlink RENAME " . $prefix . "_links_newlink");
mysql_query("ALTER TABLE links_votedata RENAME " . $prefix . "_links_votedata");
mysql_query("ALTER TABLE message RENAME " . $prefix . "_message");
mysql_query("ALTER TABLE blocks RENAME " . $prefix . "_blocks");
mysql_query("ALTER TABLE poll_check RENAME " . $prefix . "_poll_check");
mysql_query("ALTER TABLE poll_data RENAME " . $prefix . "_poll_data");
mysql_query("ALTER TABLE poll_desc RENAME " . $prefix . "_poll_desc");
mysql_query("ALTER TABLE pollcomments RENAME " . $prefix . "_pollcomments");
mysql_query("ALTER TABLE priv_msgs RENAME " . $prefix . "_priv_msgs");
mysql_query("ALTER TABLE queue RENAME " . $prefix . "_queue");
mysql_query("ALTER TABLE quotes RENAME " . $prefix . "_quotes");
mysql_query("ALTER TABLE referer RENAME " . $prefix . "_referer");
mysql_query("ALTER TABLE related RENAME " . $prefix . "_related");
mysql_query("ALTER TABLE reviews RENAME " . $prefix . "_reviews");
mysql_query("ALTER TABLE reviews_add RENAME " . $prefix . "_reviews_add");
mysql_query("ALTER TABLE reviews_comments RENAME " . $prefix . "_reviews_comments");
mysql_query("ALTER TABLE reviews_main RENAME " . $prefix . "_reviews_main");
mysql_query("ALTER TABLE seccont RENAME " . $prefix . "_seccont");
mysql_query("ALTER TABLE sections RENAME " . $prefix . "_sections");
mysql_query("ALTER TABLE session RENAME " . $prefix . "_session");
mysql_query("ALTER TABLE stories RENAME " . $prefix . "_stories");
mysql_query("ALTER TABLE stories_cat RENAME " . $prefix . "_stories_cat");
mysql_query("ALTER TABLE topics RENAME " . $prefix . "_topics");
mysql_query("ALTER TABLE users RENAME " . $prefix . "_users");
mysql_query("DROP TABLE mainblock");
mysql_query("DROP TABLE lblocks");
mysql_query("DROP TABLE rblocks");
// Links table alteration
mysql_query("ALTER TABLE " . $prefix . "_links_links CHANGE email email VARCHAR (100) not null");
mysql_query("ALTER TABLE " . $prefix . "_links_links CHANGE name name VARCHAR (100) not null");
mysql_query("ALTER TABLE " . $prefix . "_links_newlink CHANGE email email VARCHAR (100) not null");
mysql_query("ALTER TABLE " . $prefix . "_links_newlink CHANGE name name VARCHAR (100) not null");
// Reviews table alteration
mysql_query("ALTER TABLE " . $prefix . "_reviews CHANGE reviewer reviewer VARCHAR (40)");
// Stats table alteration
mysql_query("DELETE FROM " . $prefix . "_counter WHERE type = 'browser' AND var = 'WebTV'");

?>