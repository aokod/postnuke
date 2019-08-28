<?php 
// File: $Id: pn7db.php 15630 2005-02-04 06:35:42Z jorg $
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
$dbconn->Execute("ALTER TABLE $pntable[autolinks] CHANGE lid pn_lid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[autolinks] CHANGE keyword pn_keyword varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[autolinks] CHANGE title pn_title varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[autolinks] CHANGE url pn_url varchar(200) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[autolinks] CHANGE comment pn_comment varchar(200) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[autolinks] DROP INDEX UNIQUE KEY keyword (keyword)");
$dbconn->Execute("ALTER TABLE $pntable[autolinks] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_lid)");
$dbconn->Execute("ALTER TABLE $pntable[autolinks] ADD UNIQUE KEY keyword (pn_keyword)");

$dbconn->Execute("ALTER TABLE $pntable[autonews] CHANGE anid pn_anid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[autonews] CHANGE catid pn_catid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[autonews] CHANGE aid pn_aid varchar(30) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[autonews] CHANGE title pn_title varchar(80) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[autonews] CHANGE time pn_time varchar(19) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[autonews] CHANGE hometext pn_hometext text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[autonews] CHANGE bodytext pn_bodytext text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[autonews] CHANGE topic pn_topic tinyint(4) DEFAULT '1' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[autonews] CHANGE informant pn_informant varchar(20) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[autonews] CHANGE notes pn_notes text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[autonews] CHANGE ihome pn_ihome tinyint(1) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[autonews] CHANGE alanguage pn_language varchar(30) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[autonews] CHANGE withcomm pn_withcomm int(1) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[autonews] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_anid)");

$dbconn->Execute("ALTER TABLE $pntable[banner] CHANGE bid pn_bid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[banner] CHANGE cid pn_cid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[banner] CHANGE imptotal pn_imptotal int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[banner] CHANGE impmade pn_impmade int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[banner] CHANGE clicks pn_clicks int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[banner] CHANGE imageurl pn_imageurl varchar(255) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[banner] CHANGE clickurl pn_clickurl varchar(255) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[banner] CHANGE date pn_date datetime DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[banner] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_bid)");

$dbconn->Execute("ALTER TABLE $pntable[bannerclient] CHANGE cid pn_cid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[bannerclient] CHANGE name pn_name varchar(60) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[bannerclient] CHANGE contact pn_contact varchar(60) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[bannerclient] CHANGE email pn_email varchar(60) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[bannerclient] CHANGE login pn_login varchar(10) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[bannerclient] CHANGE passwd pn_passwd varchar(10) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[bannerclient] CHANGE extrainfo pn_extrainfo text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[bannerclient] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_cid)");

$dbconn->Execute("ALTER TABLE $pntable[bannerfinish] CHANGE bid pn_bid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[bannerfinish] CHANGE cid pn_cid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[bannerfinish] CHANGE impressions pn_impressions int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[bannerfinish] CHANGE clicks pn_clicks int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[bannerfinish] CHANGE datestart pn_datestart datetime DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[bannerfinish] CHANGE dateend pn_dateend datetime DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[bannerfinish] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_bid)");

$dbconn->Execute("ALTER TABLE $pntable[blocks] CHANGE bid pn_bid int(11) unsigned NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[blocks] CHANGE bkey pn_bkey varchar(255) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[blocks] CHANGE title pn_title varchar(255) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[blocks] CHANGE content pn_content text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[blocks] CHANGE url pn_url varchar(255) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[blocks] CHANGE position pn_position char(1) DEFAULT 'l' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[blocks] CHANGE weight pn_weight decimal(10,1) DEFAULT '0.0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[blocks] CHANGE active pn_active TINYINT(3) UNSIGNED DEFAULT '1' NOT NULL");
$dbconn->Execute("ALTER TABLE $pntable[blocks] CHANGE refresh pn_refresh int(11) unsigned DEFAULT '0' NOT NULL");
$dbconn->Execute("ALTER TABLE $pntable[blocks] CHANGE last_update pn_last_update timestamp(14) NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[blocks] CHANGE blanguage pn_language varchar(30) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[blocks] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_bid)");

$dbconn->Execute("ALTER TABLE $pntable[blocks_buttons] CHANGE id pn_id int(11) unsigned NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[blocks_buttons] CHANGE bid pn_bid int(11) unsigned DEFAULT '0' NOT NULL");
$dbconn->Execute("ALTER TABLE $pntable[blocks_buttons] CHANGE title pn_title varchar(255) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[blocks_buttons] CHANGE url pn_url varchar(255) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[blocks_buttons] CHANGE images pn_images longtext NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[blocks_buttons] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_id)");

$dbconn->Execute("ALTER TABLE $pntable[comments] CHANGE tid pn_tid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[comments] CHANGE pid pn_pid int(11) DEFAULT '0'");
$dbconn->Execute("ALTER TABLE $pntable[comments] CHANGE sid pn_sid int(11) DEFAULT '0'");
$dbconn->Execute("ALTER TABLE $pntable[comments] CHANGE date pn_date datetime DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[comments] CHANGE name pn_name varchar(60) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[comments] CHANGE email pn_email varchar(60) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[comments] CHANGE url pn_url varchar(60) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[comments] CHANGE host_name pn_host_name varchar(60) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[comments] CHANGE subject pn_subject varchar(85) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[comments] CHANGE comment pn_comment text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[comments] CHANGE score pn_score tinyint(4) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[comments] CHANGE reason pn_reason tinyint(4) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[comments] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_tid)");

$dbconn->Execute("ALTER TABLE $pntable[counter] CHANGE type pn_type varchar(80) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[counter] CHANGE var pn_var varchar(80) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[counter] CHANGE count pn_count int(11)  unsigned NOT NULL DEFAULT '0'");

$dbconn->Execute("ALTER TABLE $pntable[downloads_categories] CHANGE cid pn_cid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[downloads_categories] CHANGE title pn_title varchar(50) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_categories] CHANGE cdescription pn_description text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_categories] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_cid)");

$dbconn->Execute("ALTER TABLE $pntable[downloads_downloads] CHANGE lid pn_lid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[downloads_downloads] CHANGE cid pn_cid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_downloads] CHANGE sid pn_sid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_downloads] CHANGE title pn_title varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_downloads] CHANGE url pn_url varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_downloads] CHANGE description pn_description text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_downloads] CHANGE date pn_date datetime DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[downloads_downloads] CHANGE name pn_name varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_downloads] CHANGE email pn_email varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_downloads] CHANGE hits pn_hits int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_downloads] CHANGE submitter pn_submitter varchar(60) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_downloads] CHANGE downloadratingsummary pn_ratingsummary double(6,4) DEFAULT '0.0000' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_downloads] CHANGE totalvotes pn_totalvotes int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_downloads] CHANGE totalcomments pn_totalcomments int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_downloads] CHANGE filesize pn_filesize int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_downloads] CHANGE version pn_version varchar(10) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_downloads] CHANGE homepage pn_homepage varchar(200) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_downloads] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_lid)");

$dbconn->Execute("ALTER TABLE $pntable[downloads_editorials] CHANGE downloadid pn_id int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_editorials] CHANGE adminid pn_adminid varchar(60) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_editorials] CHANGE editorialtimestamp pn_timestamp datetime DEFAULT '0000-00-00 00:00:00' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_editorials] CHANGE editorialtext pn_text text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_editorials] CHANGE editorialtitle pn_title varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_editorials] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_id)");

$dbconn->Execute("ALTER TABLE $pntable[downloads_modrequest] CHANGE requestid pn_requestid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[downloads_modrequest] CHANGE lid pn_lid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_modrequest] CHANGE cid pn_cid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_modrequest] CHANGE sid pn_sid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_modrequest] CHANGE title pn_title varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_modrequest] CHANGE url pn_url varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_modrequest] CHANGE description pn_description text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_modrequest] CHANGE modifysubmitter pn_modifysubmitter varchar(60) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_modrequest] CHANGE brokendownload pn_brokendownload int(3) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_modrequest] CHANGE name pn_name varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_modrequest] CHANGE email pn_email varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_modrequest] CHANGE filesize pn_filesize int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_modrequest] CHANGE version pn_version varchar(10) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_modrequest] CHANGE homepage pn_homepage varchar(200) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_modrequest] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_requestid)");

$dbconn->Execute("ALTER TABLE $pntable[downloads_newdownload] CHANGE lid pn_lid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[downloads_newdownload] CHANGE cid pn_cid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_newdownload] CHANGE sid pn_sid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_newdownload] CHANGE title pn_title varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_newdownload] CHANGE url pn_url varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_newdownload] CHANGE description pn_description text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_newdownload] CHANGE name pn_name varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_newdownload] CHANGE email pn_email varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_newdownload] CHANGE submitter pn_submitter varchar(60) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_newdownload] CHANGE filesize pn_filesize int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_newdownload] CHANGE version pn_version varchar(10) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_newdownload] CHANGE homepage pn_homepage varchar(200) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_newdownload] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_lid)");

$dbconn->Execute("ALTER TABLE $pntable[downloads_subcategories] CHANGE sid pn_sid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[downloads_subcategories] CHANGE cid pn_cid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_subcategories] CHANGE title pn_title varchar(50) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_subcategories] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_sid)");

$dbconn->Execute("ALTER TABLE $pntable[downloads_votedata] CHANGE ratingdbid pn_id int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[downloads_votedata] CHANGE ratinglid pn_lid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_votedata] CHANGE ratinguser pn_user varchar(60) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_votedata] CHANGE rating pn_rating int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_votedata] CHANGE ratinghostname pn_hostname varchar(60) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_votedata] CHANGE ratingcomments pn_comments text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_votedata] CHANGE ratingtimestamp pn_timestamp datetime DEFAULT '0000-00-00 00:00:00' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[downloads_votedata] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_id)");

$dbconn->Execute("ALTER TABLE $pntable[ephem] CHANGE eid pn_eid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[ephem] CHANGE did pn_did tinyint(2) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[ephem] CHANGE mid pn_mid tinyint(2) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[ephem] CHANGE yid pn_yid int(4) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[ephem] CHANGE content pn_content text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[ephem] CHANGE elanguage pn_language varchar(30) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[ephem] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_eid)");

$dbconn->Execute("ALTER TABLE $pntable[faqanswer] CHANGE id pn_id int(6) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[faqanswer] CHANGE id_cat pn_id_cat int(6) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[faqanswer] CHANGE question pn_question varchar(255) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[faqanswer] CHANGE answer pn_answer text");
$dbconn->Execute("ALTER TABLE $pntable[faqanswer] CHANGE submittedby pn_submittedby varchar(250) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[faqanswer] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_id)");

$dbconn->Execute("ALTER TABLE $pntable[faqcategories] CHANGE id_cat pn_id_cat int(6) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[faqcategories] CHANGE categories pn_categories varchar(255) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[faqcategories] CHANGE flanguage pn_language varchar(30) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[faqcategories] CHANGE parent_id pn_parent_id int(6) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[faqcategories] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_id_cat)");

$dbconn->Execute("ALTER TABLE $pntable[group_membership] CHANGE gid pn_gid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[group_membership] CHANGE uid pn_uid int(11) DEFAULT '0' NOT NULL ");

$dbconn->Execute("ALTER TABLE $pntable[group_perms] CHANGE pid pn_pid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[group_perms] CHANGE gid pn_gid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[group_perms] CHANGE sequence pn_sequence int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[group_perms] CHANGE realm pn_realm smallint(4) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[group_perms] CHANGE component pn_component varchar(255) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[group_perms] CHANGE instance pn_instance varchar(255) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[group_perms] CHANGE level pn_level smallint(4) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[group_perms] CHANGE bond pn_bond int(2) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[group_perms] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_pid)");

$dbconn->Execute("ALTER TABLE $pntable[groups] CHANGE gid pn_gid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[groups] CHANGE name pn_name varchar(255) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[groups] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_gid)");

$dbconn->Execute("ALTER TABLE $pntable[headlines] CHANGE id pn_id int(11) unsigned NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[headlines] CHANGE sitename pn_sitename varchar(255) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[headlines] CHANGE rssuser pn_rssuser varchar(10) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[headlines] CHANGE rsspasswd pn_rsspasswd varchar(10) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[headlines] CHANGE use_proxy pn_use_proxy tinyint(3) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[headlines] CHANGE rssurl pn_rssurl varchar(255) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[headlines] CHANGE maxrows pn_maxrows tinyint(3) DEFAULT '10' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[headlines] CHANGE siteurl pn_siteurl varchar(255) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[headlines] CHANGE options pn_options varchar(20) DEFAULT ''");
$dbconn->Execute("ALTER TABLE $pntable[headlines] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_id)");

$dbconn->Execute("ALTER TABLE $pntable[languages_constant] CHANGE constant pn_constant varchar(32) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[languages_constant] CHANGE file pn_file varchar(64) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[languages_constant] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_constant)");

$dbconn->Execute("ALTER TABLE $pntable[languages_file] CHANGE target pn_target varchar(64) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[languages_file] CHANGE source pn_source varchar(64) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[languages_file] DROP INDEX UNIQUE KEY source (source)");
$dbconn->Execute("ALTER TABLE $pntable[languages_file] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_target,pn_source)");
$dbconn->Execute("ALTER TABLE $pntable[languages_file] ADD UNIQUE KEY source (pn_source)");

$dbconn->Execute("ALTER TABLE $pntable[languages_translation] CHANGE language pn_language varchar(32) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[languages_translation] CHANGE constant pn_constant varchar(32) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[languages_translation] CHANGE translation pn_translation longblob DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[languages_translation] CHANGE level pn_level tinyint(4) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[languages_translation] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_constant,pn_language)");

$dbconn->Execute("ALTER TABLE $pntable[links_categories] CHANGE cat_id pn_cat_id int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[links_categories] CHANGE parent_id pn_parent_id int(11) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[links_categories] CHANGE title pn_title varchar(50) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_categories] CHANGE cdescription pn_description text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_categories] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_cat_id)");

$dbconn->Execute("ALTER TABLE $pntable[links_editorials] CHANGE linkid pn_linkid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_editorials] CHANGE adminid pn_adminid varchar(60) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_editorials] CHANGE editorialtimestamp pn_timestamp datetime DEFAULT '0000-00-00 00:00:00' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_editorials] CHANGE editorialtext pn_text text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_editorials] CHANGE editorialtitle pn_title varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_editorials] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_linkid)");

$dbconn->Execute("ALTER TABLE $pntable[links_links] CHANGE lid pn_lid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[links_links] CHANGE cat_id pn_cat_id int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_links] CHANGE title pn_title varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_links] CHANGE url pn_url varchar(255) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_links] CHANGE description pn_description text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_links] CHANGE date pn_date datetime DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[links_links] CHANGE name pn_name varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_links] CHANGE email pn_email varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_links] CHANGE hits pn_hits int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_links] CHANGE submitter pn_submitter varchar(60) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_links] CHANGE linkratingsummary pn_ratingsummary double(6,4) DEFAULT '0.0000' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_links] CHANGE totalvotes pn_totalvotes int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_links] CHANGE totalcomments pn_totalcomments int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_links] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_lid)");

$dbconn->Execute("ALTER TABLE $pntable[links_modrequest] CHANGE requestid pn_requestid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[links_modrequest] CHANGE lid pn_lid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_modrequest] CHANGE cat_id pn_cat_id int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_modrequest] CHANGE sid pn_sid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_modrequest] CHANGE title pn_title varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_modrequest] CHANGE url pn_url varchar(255) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_modrequest] CHANGE description pn_description text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_modrequest] CHANGE modifysubmitter pn_modifysubmitter varchar(60) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_modrequest] CHANGE brokenlink pn_brokenlink tinyint(3) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_modrequest] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_requestid)");

$dbconn->Execute("ALTER TABLE $pntable[links_newlink] CHANGE lid pn_lid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[links_newlink] CHANGE cat_id pn_cat_id int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_newlink] CHANGE title pn_title varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_newlink] CHANGE url pn_url varchar(255) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_newlink] CHANGE description pn_description text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_newlink] CHANGE name pn_name varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_newlink] CHANGE email pn_email varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_newlink] CHANGE submitter pn_submitter varchar(60) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_newlink] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_lid)");

$dbconn->Execute("ALTER TABLE $pntable[links_votedata] CHANGE ratingdbid pn_id int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[links_votedata] CHANGE ratinglid pn_lid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_votedata] CHANGE ratinguser pn_user varchar(60) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_votedata] CHANGE rating pn_rating int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_votedata] CHANGE ratinghostname pn_hostname varchar(60) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_votedata] CHANGE ratingcomments pn_comments text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_votedata] CHANGE ratingtimestamp pn_timestamp datetime DEFAULT '0000-00-00 00:00:00' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[links_votedata] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_id)");

$dbconn->Execute("ALTER TABLE $pntable[message] CHANGE mid pn_mid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[message] CHANGE title pn_title varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[message] CHANGE content pn_content text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[message] CHANGE date pn_date varchar(14) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[message] CHANGE expire pn_expire mediumint(7) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[message] CHANGE active pn_active tinyint(4) DEFAULT '1' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[message] CHANGE view pn_view tinyint(1) DEFAULT '1' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[message] CHANGE mlanguage pn_language varchar(30) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[message] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_mid)");

$dbconn->Execute("ALTER TABLE $pntable[poll_check] CHANGE ip pn_ip varchar(20) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[poll_check] CHANGE time pn_time varchar(14) DEFAULT '' NOT NULL ");

$dbconn->Execute("ALTER TABLE $pntable[poll_data] CHANGE pollid pn_pollid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[poll_data] CHANGE optiontext pn_optiontext char(50) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[poll_data] CHANGE optioncount pn_optioncount int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[poll_data] CHANGE voteid pn_voteid int(11) DEFAULT '0' NOT NULL ");

$dbconn->Execute("ALTER TABLE $pntable[poll_desc] CHANGE pollid pn_pollid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[poll_desc] CHANGE pollTitle pn_title varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[poll_desc] CHANGE timestamp pn_timestamp int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[poll_desc] CHANGE voters pn_voters mediumint(9) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[poll_desc] CHANGE planguage pn_language varchar(30) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[poll_desc] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_pollid)");

$dbconn->Execute("ALTER TABLE $pntable[pollcomments] CHANGE tid pn_tid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[pollcomments] CHANGE pid pn_pid int(11) DEFAULT '0'");
$dbconn->Execute("ALTER TABLE $pntable[pollcomments] CHANGE pollid pn_pollid int(11) DEFAULT '0'");
$dbconn->Execute("ALTER TABLE $pntable[pollcomments] CHANGE date pn_date datetime DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[pollcomments] CHANGE name pn_name varchar(60) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[pollcomments] CHANGE email pn_email varchar(60) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[pollcomments] CHANGE url pn_url varchar(60) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[pollcomments] CHANGE host_name pn_host_name varchar(60) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[pollcomments] CHANGE subject pn_subject varchar(60) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[pollcomments] CHANGE comment pn_comment text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[pollcomments] CHANGE score pn_score tinyint(4) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[pollcomments] CHANGE reason pn_reason tinyint(4) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[pollcomments] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_tid)");

$dbconn->Execute("ALTER TABLE $pntable[priv_msgs] CHANGE msg_id pn_msg_id int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[priv_msgs] CHANGE msg_image pn_msg_image varchar(100) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[priv_msgs] CHANGE subject pn_subject varchar(100) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[priv_msgs] CHANGE from_userid pn_from_userid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[priv_msgs] CHANGE to_userid pn_to_userid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[priv_msgs] CHANGE msg_time pn_msg_time varchar(20) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[priv_msgs] CHANGE msg_text pn_msg_text text");
$dbconn->Execute("ALTER TABLE $pntable[priv_msgs] CHANGE read_msg pn_read_msg tinyint(4) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[priv_msgs] DROP INDEX to_userid");
$dbconn->Execute("ALTER TABLE $pntable[priv_msgs] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_msg_id)");
$dbconn->Execute("ALTER TABLE $pntable[priv_msgs] ADD KEY pn_to_userid (pn_to_userid)");

$dbconn->Execute("ALTER TABLE $pntable[queue] CHANGE qid pn_qid smallint(5) unsigned NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[queue] CHANGE uid pn_uid mediumint(9) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[queue] CHANGE arcd pn_arcd tinyint(1) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[queue] CHANGE uname pn_uname varchar(40) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[queue] CHANGE subject pn_subject varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[queue] CHANGE story pn_story text");
$dbconn->Execute("ALTER TABLE $pntable[queue] CHANGE timestamp pn_timestamp datetime DEFAULT '0000-00-00 00:00:00' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[queue] CHANGE topic pn_topic varchar(20) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[queue] CHANGE alanguage pn_language varchar(30) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[queue] CHANGE bodytext pn_bodytext text");
$dbconn->Execute("ALTER TABLE $pntable[queue] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_qid)");

$dbconn->Execute("ALTER TABLE " . $prefix . "_quotes CHANGE qid pn_qid int(11) unsigned NOT NULL  auto_increment");
$dbconn->Execute("ALTER TABLE " . $prefix . "_quotes CHANGE quote pn_quote text");
$dbconn->Execute("ALTER TABLE " . $prefix . "_quotes CHANGE author pn_author varchar(150) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE " . $prefix . "_quotes DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_qid)");

$dbconn->Execute("ALTER TABLE $pntable[realms] CHANGE rid pn_rid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[realms] CHANGE name pn_name varchar(255) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[realms] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_rid)");

$dbconn->Execute("ALTER TABLE $pntable[referer] CHANGE rid pn_rid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[referer] CHANGE url pn_url varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[referer] CHANGE frequency pn_frequency int(15) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[referer] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_rid)");

$dbconn->Execute("ALTER TABLE $pntable[related] CHANGE rid pn_rid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[related] CHANGE tid pn_tid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[related] CHANGE name pn_name varchar(30) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[related] CHANGE url pn_url varchar(200) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[related] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_rid)");

$dbconn->Execute("ALTER TABLE $pntable[reviews] CHANGE id pn_id int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[reviews] CHANGE date pn_date date DEFAULT '0000-00-00' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[reviews] CHANGE title pn_title varchar(150) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[reviews] CHANGE text pn_text text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[reviews] CHANGE reviewer pn_reviewer varchar(20) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[reviews] CHANGE email pn_email varchar(60) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[reviews] CHANGE score pn_score int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[reviews] CHANGE cover pn_cover varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[reviews] CHANGE url pn_url varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[reviews] CHANGE url_title pn_url_title varchar(50) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[reviews] CHANGE hits pn_hits int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[reviews] CHANGE rlanguage pn_language varchar(30) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[reviews] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_id)");

$dbconn->Execute("ALTER TABLE $pntable[reviews_add] CHANGE id pn_id int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[reviews_add] CHANGE date pn_date date DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[reviews_add] CHANGE title pn_title varchar(150) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[reviews_add] CHANGE text pn_text text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[reviews_add] CHANGE reviewer pn_reviewer varchar(20) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[reviews_add] CHANGE email pn_email varchar(60) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[reviews_add] CHANGE score pn_score int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[reviews_add] CHANGE url pn_url varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[reviews_add] CHANGE url_title pn_url_title varchar(50) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[reviews_add] CHANGE rlanguage pn_language varchar(30) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[reviews_add] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_id)");

$dbconn->Execute("ALTER TABLE $pntable[reviews_comments] CHANGE cid pn_cid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[reviews_comments] CHANGE rid pn_rid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[reviews_comments] CHANGE userid pn_userid varchar(25) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[reviews_comments] CHANGE date pn_date datetime DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[reviews_comments] CHANGE comments pn_comments text");
$dbconn->Execute("ALTER TABLE $pntable[reviews_comments] CHANGE score pn_score int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[reviews_comments] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_cid)");

$dbconn->Execute("ALTER TABLE $pntable[reviews_main] CHANGE title pn_title varchar(100) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[reviews_main] CHANGE description pn_description text");

$dbconn->Execute("ALTER TABLE $pntable[seccont] CHANGE artid pn_artid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[seccont] CHANGE secid pn_secid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[seccont] CHANGE title pn_title text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[seccont] CHANGE content pn_content text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[seccont] CHANGE counter pn_counter int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[seccont] CHANGE slanguage pn_language varchar(30) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[seccont] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_artid)");

$dbconn->Execute("ALTER TABLE $pntable[sections] CHANGE secid pn_secid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[sections] CHANGE secname pn_secname varchar(40) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[sections] CHANGE image pn_image varchar(50) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[sections] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_secid)");

$dbconn->Execute("ALTER TABLE $pntable[stats_date] CHANGE date pn_date varchar(80) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[stats_date] CHANGE hits pn_hits int(11) unsigned NOT NULL DEFAULT '0'");

$dbconn->Execute("ALTER TABLE $pntable[stats_hour] CHANGE hour pn_hour tinyint(2) unsigned DEFAULT '0' NOT NULL");
$dbconn->Execute("ALTER TABLE $pntable[stats_hour] CHANGE hits pn_hits int(11) unsigned NOT NULL DEFAULT '0' ");

$dbconn->Execute("ALTER TABLE $pntable[stats_month] CHANGE month pn_month tinyint(2) unsigned NOT NULL DEFAULT '0'");
$dbconn->Execute("ALTER TABLE $pntable[stats_month] CHANGE hits pn_hits int(11) unsigned NOT NULL DEFAULT '0'");

$dbconn->Execute("ALTER TABLE $pntable[stats_week] CHANGE weekday pn_weekday tinyint(1) unsigned NOT NULL DEFAULT '0'");
$dbconn->Execute("ALTER TABLE $pntable[stats_week] CHANGE hits pn_hits int(11) unsigned NOT NULL DEFAULT '0'");

$dbconn->Execute("ALTER TABLE $pntable[stories] CHANGE sid pn_sid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[stories] CHANGE catid pn_catid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[stories] CHANGE aid pn_aid varchar(30) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[stories] CHANGE title pn_title varchar(255) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[stories] CHANGE time pn_time datetime DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[stories] CHANGE hometext pn_hometext text");
$dbconn->Execute("ALTER TABLE $pntable[stories] CHANGE bodytext pn_bodytext text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[stories] CHANGE comments pn_comments int(11) DEFAULT '0'");
$dbconn->Execute("ALTER TABLE $pntable[stories] CHANGE counter pn_counter mediumint(8) unsigned NULL");
$dbconn->Execute("ALTER TABLE $pntable[stories] CHANGE topic pn_topic tinyint(4) DEFAULT '1' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[stories] CHANGE informant pn_informant varchar(20) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[stories] CHANGE notes pn_notes text NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[stories] CHANGE ihome pn_ihome tinyint(1) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[stories] CHANGE themeoverride pn_themeoverride varchar(30) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[stories] CHANGE alanguage pn_language varchar(30) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[stories] CHANGE withcomm pn_withcomm tinyint(1) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[stories] CHANGE format_type pn_format_type tinyint(1) unsigned DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[stories] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_sid)");

$dbconn->Execute("ALTER TABLE $pntable[stories_cat] CHANGE catid pn_catid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[stories_cat] CHANGE title pn_title varchar(40) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[stories_cat] CHANGE counter pn_counter int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[stories_cat] CHANGE themeoverride pn_themeoverride varchar(30) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[stories_cat] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_catid)");

$dbconn->Execute("ALTER TABLE $pntable[topics] CHANGE topicid pn_topicid tinyint(4) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[topics] CHANGE topicname pn_topicname varchar(255) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[topics] CHANGE topicimage pn_topicimage varchar(255) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[topics] CHANGE topictext pn_topictext varchar(255) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[topics] CHANGE counter pn_counter int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[topics] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_topicid)");

$dbconn->Execute("ALTER TABLE $pntable[user_perms] CHANGE pid pn_id int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[user_perms] CHANGE uid pn_uid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[user_perms] CHANGE sequence pn_sequence int(6) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[user_perms] CHANGE realm pn_realm int(4) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[user_perms] CHANGE component pn_component varchar(255) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[user_perms] CHANGE instance pn_instance varchar(255) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[user_perms] CHANGE level pn_level int(4) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[user_perms] CHANGE bond pn_bond int(2) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[user_perms] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_id)");

$dbconn->Execute("ALTER TABLE $pntable[userblocks] CHANGE uid pn_uid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[userblocks] CHANGE bid pn_bid int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[userblocks] CHANGE active pn_active tinyint(3) DEFAULT '1' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[userblocks] CHANGE last_update pn_last_update timestamp(14) NOT NULL ");

$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE uid pn_uid int(11) NOT NULL auto_increment");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE name pn_name varchar(60) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE uname pn_uname varchar(25) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE email pn_email varchar(60) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE femail pn_femail varchar(60) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE url pn_url varchar(100) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE user_avatar pn_user_avatar varchar(30) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE user_regdate pn_user_regdate varchar(20) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE user_icq pn_user_icq varchar(15) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE user_occ pn_user_occ varchar(100) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE user_from pn_user_from varchar(100) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE user_intrest pn_user_intrest varchar(150) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE user_sig pn_user_sig varchar(255) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE user_viewemail pn_user_viewemail tinyint(2) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE user_theme pn_user_theme tinyint(3) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE user_aim pn_user_aim varchar(18) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE user_yim pn_user_yim varchar(25) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE user_msnm pn_user_msnm varchar(25) DEFAULT NULL");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE pass pn_pass varchar(40) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE storynum pn_storynum tinyint(4) DEFAULT '10' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE umode pn_umode varchar(10) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE uorder pn_uorder tinyint(1) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE thold pn_thold tinyint(1) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE noscore pn_noscore tinyint(1) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE bio pn_bio tinytext NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE ublockon pn_ublockon tinyint(1) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE ublock pn_ublock tinytext NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE theme pn_theme varchar(255) DEFAULT '' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE commentmax pn_commentmax int(11) DEFAULT '4096' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE counter pn_counter int(11) DEFAULT '0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE timezone_offset pn_timezone_offset float(3,1) DEFAULT '0.0' NOT NULL ");
$dbconn->Execute("ALTER TABLE $pntable[users] DROP PRIMARY KEY, ADD PRIMARY KEY  (pn_uid)");

?>