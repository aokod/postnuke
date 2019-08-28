<?php 
// File: $Id: mpn187.php 15630 2005-02-04 06:35:42Z jorg $
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
$result = mysql_query("ALTER TABLE contactbook RENAME mpn_contactbook") or die("<br>Unable to update contactbook");
echo "<br>Renamed contactbook to mpn_contactbook";
$result = mysql_query("ALTER TABLE mymsg2 RENAME mpn_mymsg2") or die("<br>Unable to update mymsg2");
echo "<br>Renamed mymsg2 to mpn_mymsg2";
$result = mysql_query("ALTER TABLE popsettings RENAME mpn_popsettings") or die("<br>Unable to update popsettings");
echo "<br>Renamed popsettings to mpn_popsettings";

$result = mysql_query("CREATE TABLE mpn_calendar (
   PostID int(11) NOT NULL auto_increment,
   Subj varchar(100) NOT NULL,
   Text text NOT NULL,
   Author int(11) DEFAULT '0' NOT NULL,
   Topic int(11) DEFAULT '0' NOT NULL,
   Date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   Taggedto int(11) DEFAULT '0' NOT NULL,
   Category int(11) DEFAULT '0' NOT NULL,
   EDate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   Status int(4) DEFAULT '0' NOT NULL,
   tmp int(11) DEFAULT '0' NOT NULL,
   Private int(11) DEFAULT '0' NOT NULL,
   TDate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   Gruppe int(11) DEFAULT '0' NOT NULL,
   PRIMARY KEY (PostID)
);") or die ("<b>Unable to create mpn_calendar</b>");

echo "<br>Created mpn_calendar";

$result = mysql_query("CREATE TABLE mpn_combo_country (
   id int(6) NOT NULL auto_increment,
   value varchar(255) NOT NULL,
   name varchar(255) NOT NULL,
   PRIMARY KEY (id)
);") or die("<br>Unable to create mpn_combo_country");

echo "<br>Created mpn_combo_country";

$result = mysql_query("CREATE TABLE mpn_combo_daynum (
   id int(6) NOT NULL auto_increment,
   value varchar(255) NOT NULL,
   name varchar(255) NOT NULL,
   PRIMARY KEY (id)
);") or die("<br>Unable to create mpn_combo_daynum");

echo "<br>Created mpn_combo_daynum";

$result = mysql_query("CREATE TABLE mpn_combo_industry (
   id int(6) NOT NULL auto_increment,
   value varchar(255) NOT NULL,
   name varchar(255) NOT NULL,
   PRIMARY KEY (id)
);") or die("<br>Unable to create mpn_combo_industry");

echo "<br>Created mpn_combo_industry";

$result = mysql_query("CREATE TABLE mpn_combo_monnum (
   id int(6) NOT NULL auto_increment,
   value varchar(255) NOT NULL,
   name varchar(255) NOT NULL,
   PRIMARY KEY (id)
);") or die("<br>Unable to create mpn_combo_monnum");

echo "<br>Created mpn_combo_monnum";

$result = mysql_query("CREATE TABLE mpn_combo_occ (
   id int(6) NOT NULL auto_increment,
   value varchar(255) NOT NULL,
   name varchar(255) NOT NULL,
   PRIMARY KEY (id)
);") or die("<br>Unable to create mpn_combo_occ");

echo "<br>Created mpn_combo_occ";

$result = mysql_query("CREATE TABLE mpn_combo_yearnum (
   id int(6) NOT NULL auto_increment,
   value varchar(255) NOT NULL,
   name varchar(255) NOT NULL,
   PRIMARY KEY (id)
);") or die("<br>Unable to create mpn_combo_yearnum");

echo "<br>Created mpn_combo_yearnum";

$result = mysql_query("CREATE TABLE mpn_everyonenet_settings (
   name varchar(24) NOT NULL,
   setting varchar(255),
   PRIMARY KEY (name),
   UNIQUE name (name)
);") or die ("<b>Unable to create mpn_everyonenet_settings</b>");

echo "<br>Created mpn_everyonenet_settings.";

$result = mysql_query("CREATE TABLE mpn_gallcats (
   gallid int(3) NOT NULL auto_increment,
   gallname varchar(30) NOT NULL,
   gallimg varchar(50) NOT NULL,
   galloc varchar(30) DEFAULT '0' NOT NULL,
   PRIMARY KEY (gallid),
   KEY gallid (gallid)
);") or die ("<b>Unable to create mpn_gallcats</b>");

echo "<br>Created mpn_gallcats made";

$result = mysql_query("CREATE TABLE mpn_gallcomments (
   cid int(11) NOT NULL auto_increment,
   pid int(4) DEFAULT '0' NOT NULL,
   uname varchar(25),
   comment varchar(255) NOT NULL,
   date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   PRIMARY KEY (cid)
);") or die ("<b>Unable to create mpn_gallcomments</b>");

echo "<br>Created mpn_gallcomments made";

$result = mysql_query("CREATE TABLE mpn_gallery (
   pid int(4) NOT NULL auto_increment,
   gid int(3) DEFAULT '0' NOT NULL,
   galloc varchar(30) NOT NULL,
   thumb varchar(40) NOT NULL,
   img varchar(40) NOT NULL,
   counter int(4) DEFAULT '0' NOT NULL,
   submitter varchar(24) DEFAULT 'Webmaster' NOT NULL,
   date datetime,
   PRIMARY KEY (pid),
   KEY pid (pid)
);") or die ("<b>Unable to create mpn_gallery</b>");

echo "<br>Created mpn_gallery made";

$result = mysql_query("CREATE TABLE mpn_gallery_categories (
   gallid int(3) NOT NULL auto_increment,
   gallname varchar(30) NOT NULL,
   gallimg varchar(50) NOT NULL,
   galloc longtext,
   description text NOT NULL,
   parent int(3) DEFAULT '-1' NOT NULL,
   visible tinyint(3) unsigned DEFAULT '0' NOT NULL,
   template int(10) unsigned DEFAULT '2' NOT NULL,
   numcol tinyint(3) unsigned DEFAULT '3' NOT NULL,
   total int(10) unsigned DEFAULT '0' NOT NULL,
   lastadd date DEFAULT '0000-00-00' NOT NULL,
   PRIMARY KEY (gallid),
   KEY gallid (gallid)
);") or die ("<b>Unable to create mpn_gallery_categories</b>");

echo "<br>Created mpn_gallery_categories made";

$result = mysql_query("CREATE TABLE mpn_gallery_comments (
   cid int(10) unsigned NOT NULL auto_increment,
   pid int(10) unsigned DEFAULT '0' NOT NULL,
   comment varchar(255) NOT NULL,
   date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   name varchar(255),
   member tinyint(3) unsigned DEFAULT '0' NOT NULL,
   PRIMARY KEY (cid)
);") or die ("<b>Unable to create mpn_gallery_comments</b>");

echo "<br>Created mpn_gallery_comments made";

$result = mysql_query("CREATE TABLE mpn_gallery_media_class (
   id int(2) DEFAULT '0' NOT NULL,
   class varchar(10) NOT NULL,
   PRIMARY KEY (id),
   UNIQUE id (id)
);") or die ("<b>Unable to create mpn_gallery_media_class</b>");

echo "<br>Created mpn_gallery_media_class made";

$result = mysql_query("CREATE TABLE mpn_gallery_media_types (
   extension varchar(10) NOT NULL,
   description text NOT NULL,
   filetype varchar(20) NOT NULL,
   displaytag text NOT NULL,
   thumbnail varchar(255) NOT NULL,
   PRIMARY KEY (extension)
);") or die ("<b>Unable to create mpn_gallery_media_types</b>");

echo "<br>Created mpn_gallery_media_types made";

$result = mysql_query("CREATE TABLE mpn_gallery_pictures (
   pid int(10) unsigned NOT NULL auto_increment,
   gid int(3) DEFAULT '0' NOT NULL,
   img varchar(255) NOT NULL,
   counter int(10) unsigned DEFAULT '0' NOT NULL,
   submitter varchar(24) DEFAULT 'Webmaster' NOT NULL,
   date datetime,
   name varchar(255) NOT NULL,
   description text NOT NULL,
   votes int(10) unsigned DEFAULT '0' NOT NULL,
   rate float DEFAULT '0' NOT NULL,
   extension varchar(10) DEFAULT 'image' NOT NULL,
   width smallint(5) unsigned DEFAULT '0' NOT NULL,
   height smallint(5) unsigned DEFAULT '0' NOT NULL,
   PRIMARY KEY (pid),
   KEY pid (pid)
);") or die ("<b>Unable to create mpn_gallery_pictures</b>");

echo "<br>Created mpn_gallery_pictures made";

$result = mysql_query("CREATE TABLE mpn_gallery_pictures_newpicture (
   pid int(10) unsigned NOT NULL auto_increment,
   gid int(3) DEFAULT '0' NOT NULL,
   img varchar(255) NOT NULL,
   counter int(10) unsigned DEFAULT '0' NOT NULL,
   submitter varchar(24) DEFAULT 'Webmaster' NOT NULL,
   date datetime,
   name varchar(255) NOT NULL,
   description text NOT NULL,
   votes int(10) unsigned DEFAULT '0' NOT NULL,
   rate float DEFAULT '0' NOT NULL,
   extension varchar(10) DEFAULT 'image' NOT NULL,
   width smallint(5) unsigned DEFAULT '0' NOT NULL,
   height smallint(5) unsigned DEFAULT '0' NOT NULL,
   PRIMARY KEY (pid),
   KEY pid (pid)
);") or die ("<b>Unable to create mpn_gallery_pictures_newpicture</b>");

echo "<br>Created mpn_gallery_pictures_newpicture made";

$result = mysql_query("CREATE TABLE mpn_gallery_rate_check (
   ip varchar(20) NOT NULL,
   time varchar(14) NOT NULL,
   pid int(10) unsigned DEFAULT '0' NOT NULL
);") or die ("<b>Unable to make mpn_gallery_rate_check</b>");

echo "<br>Created mpn_gallery_rate_check made";

$result = mysql_query("CREATE TABLE mpn_gallery_template_types (
   id int(10) unsigned NOT NULL auto_increment,
   title varchar(255) NOT NULL,
   type tinyint(3) unsigned DEFAULT '2' NOT NULL,
   templateCategory longtext NOT NULL,
   templatePictures longtext NOT NULL,
   templateCSS longtext,
   PRIMARY KEY (id)
);") or die ("<b>Unable to create mpn_gallery_template_types</b>");

echo "<br>Created mpn_gallery_template_types made";

$result = mysql_query("CREATE TABLE mpn_yp_brokenlink (
   reportid int(11) NOT NULL auto_increment,
   bid int(11) DEFAULT '0' NOT NULL,
   sender varchar(25) NOT NULL,
   ip varchar(20) NOT NULL,
   PRIMARY KEY (reportid)
);") or die ("<b>Unable to create mpn_yp_broken_link</b>");

echo "<br>Created mpn_yp_brokenlink made";

$result = mysql_query("CREATE TABLE mpn_yp_business (
   bid int(11) unsigned NOT NULL auto_increment,
   cid int(11) unsigned DEFAULT '0' NOT NULL,
   title varchar(100) NOT NULL,
   url varchar(100) NOT NULL,
   email varchar(60) NOT NULL,
   phone varchar(20) NOT NULL,
   fax varchar(20) NOT NULL,
   summary varchar(150) NOT NULL,
   address varchar(100) NOT NULL,
   city varchar(50) NOT NULL,
   state varchar(25) NOT NULL,
   zipcode varchar(10) NOT NULL,
   country char(3) NOT NULL,
   logourl varchar(150) NOT NULL,
   submitter varchar(25) NOT NULL,
   status tinyint(2) unsigned DEFAULT '0' NOT NULL,
   date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   hits int(11) unsigned DEFAULT '0' NOT NULL,
   rating double(6,4) DEFAULT '0.0000' NOT NULL,
   votes int(11) unsigned DEFAULT '0' NOT NULL,
   comments int(11) unsigned DEFAULT '0' NOT NULL,
   PRIMARY KEY (bid)
);") or die ("<b>Unable to create mpn_yp_business</b>");

echo "<br>Created mpn_yp_business made.";

$result = mysql_query("CREATE TABLE mpn_yp_businesstxt (
   txtid int(11) unsigned NOT NULL auto_increment,
   bid int(11) unsigned DEFAULT '0' NOT NULL,
   description text NOT NULL,
   PRIMARY KEY (txtid)
);") or die ("<b>Unable to create mpn_businesstxt</b>");

echo "<br>Created mpn_yp_businesstxt made";

$result = mysql_query("CREATE TABLE mpn_yp_categories (
   cid int(11) unsigned NOT NULL auto_increment,
   pid int(11) unsigned DEFAULT '0' NOT NULL,
   title varchar(50) NOT NULL,
   imgurl varchar(150) NOT NULL,
   PRIMARY KEY (cid)
);") or die ("<b>Unable to create mpn_yp_categories</b>");

echo "<br>Created mpn_yp_categories made";

$result = mysql_query("CREATE TABLE mpn_yp_modrequest (
   requestid int(11) unsigned NOT NULL auto_increment,
   bid int(11) unsigned DEFAULT '0' NOT NULL,
   cid int(11) unsigned DEFAULT '0' NOT NULL,
   title varchar(100) NOT NULL,
   url varchar(100) NOT NULL,
   email varchar(60) NOT NULL,
   phone varchar(20) NOT NULL,
   fax varchar(20) NOT NULL,
   summary varchar(150) NOT NULL,
   address varchar(100) NOT NULL,
   city varchar(50) NOT NULL,
   state varchar(25) NOT NULL,
   zipcode varchar(10) NOT NULL,
   country char(3) NOT NULL,
   logourl varchar(150) NOT NULL,
   description text NOT NULL,
   modifysubmitter varchar(25) NOT NULL,
   PRIMARY KEY (requestid)
);") or die ("<b>Unable to create mpn_yp_modrequest</b>");

echo "<br>Created mpn_yp_modrequest made";

$result = mysql_query("CREATE TABLE mpn_yp_votedata (
   ratingid int(11) unsigned NOT NULL auto_increment,
   bid int(11) unsigned DEFAULT '0' NOT NULL,
   ratinguser varchar(25) NOT NULL,
   rating tinyint(3) unsigned DEFAULT '0' NOT NULL,
   ratinghostname varchar(60) NOT NULL,
   ratingtimestamp datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   PRIMARY KEY (ratingid)
);") or die ("<b>Unable to create mpn_yp_votedata</b>");

echo "<br>Created mpn_yp_votedata made" . "<br><br>Upgrade to MyPHPNuke 1.8.8b2 Sucessfull" . "<br><br>Starting Upgrade to Post Nuke .64<br><br>"

?>