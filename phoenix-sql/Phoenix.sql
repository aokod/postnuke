-- phpMyAdmin SQL Dump
-- version 2.9.0
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Sep 28, 2006 at 12:52 PM
-- Server version: 5.0.124
-- PHP Version: 4.4.4
-- 
-- PostNuke 0.7.6.4
-- 
-- 
-- Database: `pn764`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `pn_admin_category`
-- 

CREATE TABLE `pn_admin_category` (
  `pn_cid` int(10) NOT NULL,
  `pn_name` varchar(32) NOT NULL default '',
  `pn_description` varchar(254) NOT NULL default '',
  PRIMARY KEY  (`pn_cid`)
) TYPE=MyISAM AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `pn_admin_category`
-- 

INSERT INTO `pn_admin_category` VALUES (1, 'System', 'System Modules');
INSERT INTO `pn_admin_category` VALUES (2, 'Content', 'Content Modules');
INSERT INTO `pn_admin_category` VALUES (3, 'Resource Pack', 'Resource Pack Modules');
INSERT INTO `pn_admin_category` VALUES (4, 'Utility', 'Utility Modules');
INSERT INTO `pn_admin_category` VALUES (5, '3rd Party', 'Third Party Modules');

-- --------------------------------------------------------

-- 
-- Table structure for table `pn_admin_module`
-- 

CREATE TABLE `pn_admin_module` (
  `pn_mid` int(10) NOT NULL default '0',
  `pn_cid` int(10) NOT NULL default '0'
) TYPE=MyISAM;

-- 
-- Dumping data for table `pn_admin_module`
-- 

INSERT INTO `pn_admin_module` VALUES (1, 1);
INSERT INTO `pn_admin_module` VALUES (2, 1);
INSERT INTO `pn_admin_module` VALUES (3, 1);
INSERT INTO `pn_admin_module` VALUES (4, 1);
INSERT INTO `pn_admin_module` VALUES (5, 1);
INSERT INTO `pn_admin_module` VALUES (7, 1);
INSERT INTO `pn_admin_module` VALUES (41, 1);
INSERT INTO `pn_admin_module` VALUES (29, 1);
INSERT INTO `pn_admin_module` VALUES (50, 1);
INSERT INTO `pn_admin_module` VALUES (9, 1);
INSERT INTO `pn_admin_module` VALUES (48, 2);
INSERT INTO `pn_admin_module` VALUES (45, 2);
INSERT INTO `pn_admin_module` VALUES (40, 2);
INSERT INTO `pn_admin_module` VALUES (37, 2);
INSERT INTO `pn_admin_module` VALUES (16, 2);
INSERT INTO `pn_admin_module` VALUES (23, 1);
INSERT INTO `pn_admin_module` VALUES (19, 2);
INSERT INTO `pn_admin_module` VALUES (14, 2);
INSERT INTO `pn_admin_module` VALUES (32, 2);
INSERT INTO `pn_admin_module` VALUES (21, 2);
INSERT INTO `pn_admin_module` VALUES (36, 4);
INSERT INTO `pn_admin_module` VALUES (24, 4);
INSERT INTO `pn_admin_module` VALUES (8, 2);
INSERT INTO `pn_admin_module` VALUES (44, 2);

-- --------------------------------------------------------

-- 
-- Table structure for table `pn_autonews`
-- 

CREATE TABLE `pn_autonews` (
  `pn_anid` int(11) NOT NULL,
  `pn_catid` int(11) NOT NULL default '0',
  `pn_aid` varchar(30) NOT NULL default '',
  `pn_title` varchar(80) NOT NULL default '',
  `pn_time` varchar(19) NOT NULL default '',
  `pn_hometext` text NOT NULL,
  `pn_bodytext` text NOT NULL,
  `pn_topic` tinyint(4) NOT NULL default '1',
  `pn_informant` varchar(20) NOT NULL default '',
  `pn_notes` text NOT NULL,
  `pn_ihome` tinyint(1) NOT NULL default '0',
  `pn_language` varchar(30) NOT NULL default '',
  `pn_withcomm` int(1) NOT NULL default '0',
  PRIMARY KEY  (`pn_anid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_autonews`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_blocks`
-- 

CREATE TABLE `pn_blocks` (
  `pn_bid` int(11) unsigned NOT NULL,
  `pn_bkey` varchar(255) NOT NULL default '',
  `pn_title` varchar(255) NOT NULL default '',
  `pn_content` text NOT NULL,
  `pn_url` varchar(254) NOT NULL default '',
  `pn_mid` int(11) unsigned NOT NULL default '0',
  `pn_position` char(1) NOT NULL default 'l',
  `pn_weight` decimal(10,1) NOT NULL default '0.0',
  `pn_active` tinyint(3) unsigned NOT NULL default '1',
  `pn_refresh` int(11) unsigned NOT NULL default '0',
  `pn_last_update` timestamp NOT NULL,
  `pn_language` varchar(30) NOT NULL default '',
  `pn_collapsable` int(11) NOT NULL default '1',
  `pn_defaultstate` int(11) NOT NULL default '1',
  PRIMARY KEY  (`pn_bid`)
) TYPE=MyISAM AUTO_INCREMENT=16 ;

-- 
-- Dumping data for table `pn_blocks`
-- 

INSERT INTO `pn_blocks` VALUES (1, 'menu', 'Main Menu', 'style:=1\ndisplaymodules:=0\ndisplaywaiting:=0\ncontent:=index.php|Home|Back to the home page..LINESPLITuser.php|My Account|Administer your personal account..LINESPLITadmin.php|Administration|Administer your PostNuked site..LINESPLITuser.php?module=User&op=logout|Logout|Logout of your account..LINESPLIT||LINESPLIT[Downloads]|Downloads|Find downloads listed on this website..LINESPLIT[FAQ]|FAQ|Frequently Asked Questions.LINESPLIT[News]|News|Latest News on this site..LINESPLIT[Reviews]|Reviews|Reviews Section on this website..LINESPLIT[Search]|Search|Search our website..LINESPLIT[Sections]|Sections|Other content on this website..LINESPLIT[Submit_News]|Submit News|Submit an article..LINESPLIT[Topics]|Topics|Listing of news topics on this website..LINESPLIT[Web_Links]|Web Links|Links to other sites..', '', 0, 'l', '1.0', 1, 0, '2001-11-22 09:07:26', '', 1, 1);
INSERT INTO `pn_blocks` VALUES (2, 'menu', 'Incoming', 'style:=1\ndisplaymodules:=0\ndisplaywaiting:=1\ncontent:=', '', 0, 'l', '2.0', 1, 0, '0000-00-00 00:00:00', '', 1, 1);
INSERT INTO `pn_blocks` VALUES (3, 'online', 'Online', '', '', 0, 'l', '3.0', 1, 0, '0000-00-00 00:00:00', '', 1, 1);
INSERT INTO `pn_blocks` VALUES (5, 'user', 'Users Block', 'Put anything you want here', '', 0, 'l', '3.5', 1, 0, '0000-00-00 00:00:00', '', 1, 1);
INSERT INTO `pn_blocks` VALUES (6, 'search', 'Search Box', '', '', 0, 'l', '4.0', 0, 0, '0000-00-00 00:00:00', '', 1, 1);
INSERT INTO `pn_blocks` VALUES (8, 'thelang', 'Languages', '', '', 0, 'l', '6.0', 1, 0, '0000-00-00 00:00:00', '', 1, 1);
INSERT INTO `pn_blocks` VALUES (13, 'login', 'Login', '', '', 0, 'r', '5.0', 1, 0, '0000-00-00 00:00:00', '', 1, 1);
INSERT INTO `pn_blocks` VALUES (15, 'messages', 'Administration Messages', '', '', 9, 'c', '1.0', 1, 0, '0000-00-00 00:00:00', '', 1, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `pn_blocks_buttons`
-- 

CREATE TABLE `pn_blocks_buttons` (
  `pn_id` int(11) unsigned NOT NULL,
  `pn_bid` int(11) unsigned NOT NULL default '0',
  `pn_title` varchar(255) NOT NULL default '',
  `pn_url` varchar(254) NOT NULL default '',
  `pn_images` longtext NOT NULL,
  PRIMARY KEY  (`pn_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_blocks_buttons`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_comments`
-- 

CREATE TABLE `pn_comments` (
  `pn_tid` int(11) NOT NULL,
  `pn_pid` int(11) default '0',
  `pn_sid` int(11) default '0',
  `pn_date` datetime default NULL,
  `pn_name` varchar(60) NOT NULL default '',
  `pn_email` varchar(60) default NULL,
  `pn_url` varchar(254) default NULL,
  `pn_host_name` varchar(60) default NULL,
  `pn_subject` varchar(85) NOT NULL default '',
  `pn_comment` text NOT NULL,
  `pn_score` tinyint(4) NOT NULL default '0',
  `pn_reason` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`pn_tid`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_comments`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_counter`
-- 

CREATE TABLE `pn_counter` (
  `pn_type` varchar(80) NOT NULL default '',
  `pn_var` varchar(80) NOT NULL default '',
  `pn_count` int(11) unsigned NOT NULL default '0'
) TYPE=InnoDB;

-- 
-- Dumping data for table `pn_counter`
-- 

INSERT INTO `pn_counter` VALUES ('total', 'hits', 0);
INSERT INTO `pn_counter` VALUES ('browser', 'Lynx', 0);
INSERT INTO `pn_counter` VALUES ('browser', 'MSIE', 0);
INSERT INTO `pn_counter` VALUES ('browser', 'Opera', 0);
INSERT INTO `pn_counter` VALUES ('browser', 'Konqueror', 0);
INSERT INTO `pn_counter` VALUES ('browser', 'Netscape', 0);
INSERT INTO `pn_counter` VALUES ('browser', 'Bot', 0);
INSERT INTO `pn_counter` VALUES ('browser', 'Other', 0);
INSERT INTO `pn_counter` VALUES ('os', 'Windows', 0);
INSERT INTO `pn_counter` VALUES ('os', 'Linux', 0);
INSERT INTO `pn_counter` VALUES ('os', 'Mac', 0);
INSERT INTO `pn_counter` VALUES ('os', 'FreeBSD', 0);
INSERT INTO `pn_counter` VALUES ('os', 'SunOS', 0);
INSERT INTO `pn_counter` VALUES ('os', 'IRIX', 0);
INSERT INTO `pn_counter` VALUES ('os', 'BeOS', 0);
INSERT INTO `pn_counter` VALUES ('os', 'OS/2', 0);
INSERT INTO `pn_counter` VALUES ('os', 'AIX', 0);
INSERT INTO `pn_counter` VALUES ('os', 'Other', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `pn_downloads_categories`
-- 

CREATE TABLE `pn_downloads_categories` (
  `pn_cid` int(11) NOT NULL,
  `pn_title` varchar(50) NOT NULL default '',
  `pn_description` text NOT NULL,
  PRIMARY KEY  (`pn_cid`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_downloads_categories`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_downloads_downloads`
-- 

CREATE TABLE `pn_downloads_downloads` (
  `pn_lid` int(11) NOT NULL,
  `pn_cid` int(11) NOT NULL default '0',
  `pn_sid` int(11) NOT NULL default '0',
  `pn_title` varchar(100) NOT NULL default '',
  `pn_url` varchar(254) NOT NULL default '',
  `pn_description` text NOT NULL,
  `pn_date` datetime default NULL,
  `pn_name` varchar(100) NOT NULL default '',
  `pn_email` varchar(100) NOT NULL default '',
  `pn_hits` int(11) NOT NULL default '0',
  `pn_submitter` varchar(60) NOT NULL default '',
  `pn_ratingsummary` double(6,4) NOT NULL default '0.0000',
  `pn_totalvotes` int(11) NOT NULL default '0',
  `pn_totalcomments` int(11) NOT NULL default '0',
  `pn_filesize` int(11) NOT NULL default '0',
  `pn_version` varchar(10) NOT NULL default '',
  `pn_homepage` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`pn_lid`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_downloads_downloads`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_downloads_editorials`
-- 

CREATE TABLE `pn_downloads_editorials` (
  `pn_id` int(11) NOT NULL default '0',
  `pn_adminid` varchar(60) NOT NULL default '',
  `pn_timestamp` datetime NOT NULL default '0000-00-00 00:00:00',
  `pn_text` text NOT NULL,
  `pn_title` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`pn_id`)
) TYPE=InnoDB;

-- 
-- Dumping data for table `pn_downloads_editorials`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_downloads_modrequest`
-- 

CREATE TABLE `pn_downloads_modrequest` (
  `pn_requestid` int(11) NOT NULL,
  `pn_lid` int(11) NOT NULL default '0',
  `pn_cid` int(11) NOT NULL default '0',
  `pn_sid` int(11) NOT NULL default '0',
  `pn_title` varchar(100) NOT NULL default '',
  `pn_url` varchar(254) NOT NULL default '',
  `pn_description` text NOT NULL,
  `pn_modifysubmitter` varchar(60) NOT NULL default '',
  `pn_brokendownload` int(3) NOT NULL default '0',
  `pn_name` varchar(100) NOT NULL default '',
  `pn_email` varchar(100) NOT NULL default '',
  `pn_filesize` int(11) NOT NULL default '0',
  `pn_version` varchar(10) NOT NULL default '',
  `pn_homepage` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`pn_requestid`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_downloads_modrequest`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_downloads_newdownload`
-- 

CREATE TABLE `pn_downloads_newdownload` (
  `pn_lid` int(11) NOT NULL,
  `pn_cid` int(11) NOT NULL default '0',
  `pn_sid` int(11) NOT NULL default '0',
  `pn_title` varchar(100) NOT NULL default '',
  `pn_url` varchar(254) NOT NULL default '',
  `pn_description` text NOT NULL,
  `pn_name` varchar(100) NOT NULL default '',
  `pn_email` varchar(100) NOT NULL default '',
  `pn_submitter` varchar(60) NOT NULL default '',
  `pn_filesize` int(11) NOT NULL default '0',
  `pn_version` varchar(10) NOT NULL default '',
  `pn_homepage` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`pn_lid`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_downloads_newdownload`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_downloads_subcategories`
-- 

CREATE TABLE `pn_downloads_subcategories` (
  `pn_sid` int(11) NOT NULL,
  `pn_cid` int(11) NOT NULL default '0',
  `pn_title` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`pn_sid`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_downloads_subcategories`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_downloads_votedata`
-- 

CREATE TABLE `pn_downloads_votedata` (
  `pn_id` int(11) NOT NULL,
  `pn_lid` int(11) NOT NULL default '0',
  `pn_user` varchar(60) NOT NULL default '',
  `pn_rating` int(11) NOT NULL default '0',
  `pn_hostname` varchar(60) NOT NULL default '',
  `pn_comments` text NOT NULL,
  `pn_timestamp` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`pn_id`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_downloads_votedata`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_faqanswer`
-- 

CREATE TABLE `pn_faqanswer` (
  `pn_id` int(6) NOT NULL,
  `pn_id_cat` int(6) default NULL,
  `pn_question` text,
  `pn_answer` text,
  `pn_submittedby` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`pn_id`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_faqanswer`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_faqcategories`
-- 

CREATE TABLE `pn_faqcategories` (
  `pn_id_cat` int(6) NOT NULL,
  `pn_categories` varchar(255) default NULL,
  `pn_language` varchar(30) NOT NULL default '',
  `pn_parent_id` int(6) NOT NULL default '0',
  PRIMARY KEY  (`pn_id_cat`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_faqcategories`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_group_membership`
-- 

CREATE TABLE `pn_group_membership` (
  `pn_gid` int(11) NOT NULL default '0',
  `pn_uid` int(11) NOT NULL default '0'
) TYPE=MyISAM;

-- 
-- Dumping data for table `pn_group_membership`
-- 

INSERT INTO `pn_group_membership` VALUES (2, 2);

-- --------------------------------------------------------

-- 
-- Table structure for table `pn_group_perms`
-- 

CREATE TABLE `pn_group_perms` (
  `pn_pid` int(11) NOT NULL,
  `pn_gid` int(11) NOT NULL default '0',
  `pn_sequence` int(11) NOT NULL default '0',
  `pn_realm` smallint(4) NOT NULL default '0',
  `pn_component` varchar(255) NOT NULL default '',
  `pn_instance` varchar(255) NOT NULL default '',
  `pn_level` smallint(4) NOT NULL default '0',
  `pn_bond` int(2) NOT NULL default '0',
  PRIMARY KEY  (`pn_pid`)
) TYPE=MyISAM AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `pn_group_perms`
-- 

INSERT INTO `pn_group_perms` VALUES (1, 2, 1, 0, '.*', '.*', 800, 0);
INSERT INTO `pn_group_perms` VALUES (2, -1, 2, 0, 'Menublock::', 'Main Menu:Administration:', 0, 0);
INSERT INTO `pn_group_perms` VALUES (3, 1, 3, 0, '.*', '.*', 300, 0);
INSERT INTO `pn_group_perms` VALUES (4, 0, 4, 0, 'Menublock::', 'Main Menu:(My Account|Logout|Submit News):', 0, 0);
INSERT INTO `pn_group_perms` VALUES (5, 0, 5, 0, '.*', '.*', 200, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `pn_groups`
-- 

CREATE TABLE `pn_groups` (
  `pn_gid` int(11) NOT NULL,
  `pn_name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`pn_gid`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `pn_groups`
-- 

INSERT INTO `pn_groups` VALUES (1, 'Users');
INSERT INTO `pn_groups` VALUES (2, 'Admins');

-- --------------------------------------------------------

-- 
-- Table structure for table `pn_headlines`
-- 

CREATE TABLE `pn_headlines` (
  `pn_id` int(11) unsigned NOT NULL,
  `pn_sitename` varchar(255) NOT NULL default '',
  `pn_rssuser` varchar(10) default NULL,
  `pn_rsspasswd` varchar(10) default NULL,
  `pn_use_proxy` tinyint(3) NOT NULL default '0',
  `pn_rssurl` varchar(255) NOT NULL default '',
  `pn_maxrows` tinyint(3) NOT NULL default '10',
  `pn_siteurl` varchar(255) NOT NULL default '',
  `pn_options` varchar(20) default '',
  PRIMARY KEY  (`pn_id`)
) TYPE=MyISAM AUTO_INCREMENT=53 ;

-- 
-- Dumping data for table `pn_headlines`
-- 

INSERT INTO `pn_headlines` VALUES (1, 'PostNuke', NULL, NULL, 0, 'http://postnuke.com/backend.php', 10, '', '');
INSERT INTO `pn_headlines` VALUES (2, 'LinuxCentral', NULL, NULL, 0, 'http://linuxcentral.com/backend/lcnew.rdf', 10, '', '');
INSERT INTO `pn_headlines` VALUES (3, 'Slashdot', NULL, NULL, 0, 'http://slashdot.org/slashdot.rdf', 10, '', '');
INSERT INTO `pn_headlines` VALUES (4, 'NewsForge', NULL, NULL, 0, 'http://www.newsforge.com/newsforge.rdf', 10, '', '');
INSERT INTO `pn_headlines` VALUES (5, 'PHPBuilder', NULL, NULL, 0, 'http://phpbuilder.com/rss_feed.php', 10, '', '');
INSERT INTO `pn_headlines` VALUES (6, 'Linux.com', NULL, NULL, 0, 'http://linux.com/mrn/front_page.rss', 10, '', '');
INSERT INTO `pn_headlines` VALUES (7, 'Freshmeat', NULL, NULL, 0, 'http://freshmeat.net/backend/fm.rdf', 10, '', '');
INSERT INTO `pn_headlines` VALUES (9, 'LinuxWeeklyNews', NULL, NULL, 0, 'http://lwn.net/headlines/rss', 10, '', '');
INSERT INTO `pn_headlines` VALUES (11, 'Segfault', NULL, NULL, 0, 'http://segfault.org/stories.xml', 10, '', '');
INSERT INTO `pn_headlines` VALUES (13, 'KDE', NULL, NULL, 0, 'http://www.kde.org/news/kdenews.rdf', 10, '', '');
INSERT INTO `pn_headlines` VALUES (14, 'Perl.com', NULL, NULL, 0, 'http://www.perl.com/pace/perlnews.rdf', 10, '', '');
INSERT INTO `pn_headlines` VALUES (17, 'MozillaNewsBot', NULL, NULL, 0, 'http://www.mozilla.org/newsbot/newsbot.rdf', 10, '', '');
INSERT INTO `pn_headlines` VALUES (21, 'SciFi-News', NULL, NULL, 0, 'http://www.technopagan.org/sf-news/rdf.php', 10, '', '');
INSERT INTO `pn_headlines` VALUES (26, 'DrDobbsTechNetCast', NULL, NULL, 0, 'http://www.technetcast.com/tnc_headlines.rdf', 10, '', '');
INSERT INTO `pn_headlines` VALUES (27, 'RivaExtreme', NULL, NULL, 0, 'http://rivaextreme.com/ssi/rivaextreme.rdf.cdf', 10, '', '');
INSERT INTO `pn_headlines` VALUES (29, 'PBSOnline', NULL, NULL, 0, 'http://cgi.pbs.org/cgi-registry/featuresrdf.pl', 10, '', '');
INSERT INTO `pn_headlines` VALUES (30, 'Listology', NULL, NULL, 0, 'http://listology.com/recent.rdf', 10, '', '');
INSERT INTO `pn_headlines` VALUES (33, 'exoScience', NULL, NULL, 0, 'http://www.exosci.com/exosci.rdf', 10, '', '');
INSERT INTO `pn_headlines` VALUES (39, 'DailyDaemonNews', NULL, NULL, 0, 'http://daily.daemonnews.org/ddn.rdf.php3', 10, '', '');
INSERT INTO `pn_headlines` VALUES (40, 'PerlMonks', NULL, NULL, 0, 'http://www.perlmonks.org/headlines.rdf', 10, '', '');
INSERT INTO `pn_headlines` VALUES (42, 'BSDToday', NULL, NULL, 0, 'http://www.bsdtoday.com/backend/bt.rdf', 10, '', '');
INSERT INTO `pn_headlines` VALUES (45, 'HotWired', NULL, NULL, 0, 'http://www.hotwired.com/webmonkey/meta/headlines.rdf', 10, '', '');
INSERT INTO `pn_headlines` VALUES (52, 'SolarisCentral', NULL, NULL, 0, 'http://www.SolarisCentral.org/news/SolarisCentral.rdf', 10, '', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `pn_hooks`
-- 

CREATE TABLE `pn_hooks` (
  `pn_id` int(11) unsigned NOT NULL,
  `pn_object` varchar(64) NOT NULL,
  `pn_action` varchar(64) NOT NULL,
  `pn_smodule` varchar(64) default NULL,
  `pn_stype` varchar(64) default NULL,
  `pn_tarea` varchar(64) NOT NULL,
  `pn_tmodule` varchar(64) NOT NULL,
  `pn_ttype` varchar(64) NOT NULL,
  `pn_tfunc` varchar(64) NOT NULL,
  PRIMARY KEY  (`pn_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_hooks`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_links_categories`
-- 

CREATE TABLE `pn_links_categories` (
  `pn_cat_id` int(11) NOT NULL,
  `pn_parent_id` int(11) default NULL,
  `pn_title` varchar(50) NOT NULL default '',
  `pn_description` text NOT NULL,
  PRIMARY KEY  (`pn_cat_id`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_links_categories`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_links_editorials`
-- 

CREATE TABLE `pn_links_editorials` (
  `pn_linkid` int(11) NOT NULL default '0',
  `pn_adminid` varchar(60) NOT NULL default '',
  `pn_timestamp` datetime NOT NULL default '0000-00-00 00:00:00',
  `pn_text` text NOT NULL,
  `pn_title` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`pn_linkid`)
) TYPE=InnoDB;

-- 
-- Dumping data for table `pn_links_editorials`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_links_links`
-- 

CREATE TABLE `pn_links_links` (
  `pn_lid` int(11) NOT NULL,
  `pn_cat_id` int(11) NOT NULL default '0',
  `pn_title` varchar(100) NOT NULL default '',
  `pn_url` varchar(254) NOT NULL default '',
  `pn_description` text NOT NULL,
  `pn_date` datetime default NULL,
  `pn_name` varchar(100) NOT NULL default '',
  `pn_email` varchar(100) NOT NULL default '',
  `pn_hits` int(11) NOT NULL default '0',
  `pn_submitter` varchar(60) NOT NULL default '',
  `pn_ratingsummary` double(6,4) NOT NULL default '0.0000',
  `pn_totalvotes` int(11) NOT NULL default '0',
  `pn_totalcomments` int(11) NOT NULL default '0',
  PRIMARY KEY  (`pn_lid`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_links_links`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_links_modrequest`
-- 

CREATE TABLE `pn_links_modrequest` (
  `pn_requestid` int(11) NOT NULL,
  `pn_lid` int(11) NOT NULL default '0',
  `pn_cat_id` int(11) NOT NULL default '0',
  `pn_sid` int(11) NOT NULL default '0',
  `pn_title` varchar(100) NOT NULL default '',
  `pn_url` varchar(254) NOT NULL default '',
  `pn_description` text NOT NULL,
  `pn_modifysubmitter` varchar(60) NOT NULL default '',
  `pn_brokenlink` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`pn_requestid`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_links_modrequest`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_links_newlink`
-- 

CREATE TABLE `pn_links_newlink` (
  `pn_lid` int(11) NOT NULL,
  `pn_cat_id` int(11) NOT NULL default '0',
  `pn_title` varchar(100) NOT NULL default '',
  `pn_url` varchar(254) NOT NULL default '',
  `pn_description` text NOT NULL,
  `pn_name` varchar(100) NOT NULL default '',
  `pn_email` varchar(100) NOT NULL default '',
  `pn_submitter` varchar(60) NOT NULL default '',
  PRIMARY KEY  (`pn_lid`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_links_newlink`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_links_votedata`
-- 

CREATE TABLE `pn_links_votedata` (
  `pn_id` int(11) NOT NULL,
  `pn_lid` int(11) NOT NULL default '0',
  `pn_user` varchar(60) NOT NULL default '',
  `pn_rating` int(11) NOT NULL default '0',
  `pn_hostname` varchar(60) NOT NULL default '',
  `pn_comments` text NOT NULL,
  `pn_timestamp` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`pn_id`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_links_votedata`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_message`
-- 

CREATE TABLE `pn_message` (
  `pn_mid` int(11) NOT NULL,
  `pn_title` varchar(100) NOT NULL default '',
  `pn_content` longtext,
  `pn_date` varchar(14) NOT NULL default '',
  `pn_expire` int(7) NOT NULL default '0',
  `pn_active` int(4) NOT NULL default '1',
  `pn_view` int(1) NOT NULL default '1',
  `pn_language` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`pn_mid`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `pn_message`
-- 

INSERT INTO `pn_message` VALUES (1, 'Welcome to PostNuke, the =-Phoenix-= release (0.764)', '<p><a href="http://www.postnuke.com">PostNuke</a> is a weblog/content management system (CMS). It is far more secure and stable than competing products, and is able to work in high-volume environments with ease.<br /><br />\nSome of the key features of PostNuke are:</p>\n<ul>\n<li> customization of all aspects of the web site''s appearance through themes, with support for CSS stylesheets</li>\n<li> the ability to specify items as being suitable for either a single language or for all languages</li>\n<li> the best guarantee of properly displaying your web pages in all browsers, thanks to full compliance with W3C HTML standards</li>\n<li> a standard API (application programming interface) and extensive documentation to allow for easy extension of your web site''s functionality through modules and blocks.</li>\n</ul>\n<p>PostNuke has a very active developer and support community at <a href="http://www.postnuke.com">PostNuke.com</a>.</p>\n<p>We hope you will enjoy using PostNuke.<br /><br /><strong>The PostNuke development team </strong></p>\n<p><em>Note: you can edit or remove this message by going to the Administration page and clicking on the ''Administration messages'' entry </em></p>', '1159444262', 0, 1, 1, '');

-- --------------------------------------------------------

-- 
-- Table structure for table `pn_module_vars`
-- 

CREATE TABLE `pn_module_vars` (
  `pn_id` int(11) unsigned NOT NULL,
  `pn_modname` varchar(64) NOT NULL default '',
  `pn_name` varchar(64) NOT NULL default '',
  `pn_value` longtext,
  PRIMARY KEY  (`pn_id`),
  KEY `pn_modname` (`pn_modname`),
  KEY `pn_name` (`pn_name`)
) TYPE=MyISAM AUTO_INCREMENT=152 ;

-- 
-- Dumping data for table `pn_module_vars`
-- 

INSERT INTO `pn_module_vars` VALUES (1, 'Groups', 'defaultgroup', 'Users');
INSERT INTO `pn_module_vars` VALUES (2, 'Blocks', 'collapseable', '1');
INSERT INTO `pn_module_vars` VALUES (3, 'Admin', 'modulesperrow', '5');
INSERT INTO `pn_module_vars` VALUES (4, 'Admin', 'itemsperpage', '25');
INSERT INTO `pn_module_vars` VALUES (5, 'Admin', 'defaultcategory', '5');
INSERT INTO `pn_module_vars` VALUES (6, 'Admin', 'modulestylesheet', 'navtabs.css');
INSERT INTO `pn_module_vars` VALUES (7, 'Admin', 'admingraphic', '1');
INSERT INTO `pn_module_vars` VALUES (8, 'Admin', 'startcategory', '1');
INSERT INTO `pn_module_vars` VALUES (9, 'Admin', 'ignoreinstallercheck', '0');
INSERT INTO `pn_module_vars` VALUES (10, 'Modules', 'itemsperpage', '25');
INSERT INTO `pn_module_vars` VALUES (11, 'Groups', 'itemsperpage', '25');
INSERT INTO `pn_module_vars` VALUES (12, '/PNConfig', 'adminmail', 's:20:"postnuke@example.com";');
INSERT INTO `pn_module_vars` VALUES (13, '/PNConfig', 'debug', 'i:0;');
INSERT INTO `pn_module_vars` VALUES (14, '/PNConfig', 'sitename', 's:14:"Your Site Name";');
INSERT INTO `pn_module_vars` VALUES (15, '/PNConfig', 'site_logo', 's:8:"logo.gif";');
INSERT INTO `pn_module_vars` VALUES (16, '/PNConfig', 'slogan', 's:16:"Your slogan here";');
INSERT INTO `pn_module_vars` VALUES (17, '/PNConfig', 'metakeywords', 's:208:"nuke, postnuke, free, community, php, portal, opensource, open source, gpl, mysql, sql, database, web site, website, weblog, content management, contentmanagement, web content management, webcontentmanagement";');
INSERT INTO `pn_module_vars` VALUES (18, '/PNConfig', 'dyn_keywords', 'i:0;');
INSERT INTO `pn_module_vars` VALUES (19, '/PNConfig', 'startdate', 's:7:"09.2006";');
INSERT INTO `pn_module_vars` VALUES (20, '/PNConfig', 'Default_Theme', 's:9:"ExtraLite";');
INSERT INTO `pn_module_vars` VALUES (21, '/PNConfig', 'foot1', 's:776:"<a href="http://www.postnuke.com"><img src="images/powered/postnuke.butn.gif" alt="Web site powered by PostNuke" /></a> <a href="http://adodb.sourceforge.net"><img src="images/powered/adodb2.gif" alt="ADODB database library" /></a> <a href="http://www.php.net"><img src="images/powered/php4_powered.gif" alt="PHP Language" /></a><p>All logos and trademarks in this site are property of their respective owner. The comments are property of their posters, all the rest (c) 2006 by me<br />This web site was made with <a href="http://www.postnuke.com">PostNuke</a>, a web portal system written in PHP. PostNuke is Free Software released under the <a href="http://www.gnu.org">GNU/GPL license</a>.</p>You can syndicate our news using the file <a href="backend.php">backend.php</a>";');
INSERT INTO `pn_module_vars` VALUES (22, '/PNConfig', 'commentlimit', 'i:4096;');
INSERT INTO `pn_module_vars` VALUES (23, '/PNConfig', 'anonymous', 's:9:"Anonymous";');
INSERT INTO `pn_module_vars` VALUES (24, '/PNConfig', 'timezone_offset', 'i:12;');
INSERT INTO `pn_module_vars` VALUES (25, '/PNConfig', 'nobox', 'i:0;');
INSERT INTO `pn_module_vars` VALUES (26, '/PNConfig', 'funtext', 'i:0;');
INSERT INTO `pn_module_vars` VALUES (27, '/PNConfig', 'reportlevel', 'i:0;');
INSERT INTO `pn_module_vars` VALUES (28, '/PNConfig', 'startpage', 's:4:"News";');
INSERT INTO `pn_module_vars` VALUES (29, '/PNConfig', 'admingraphic', 'i:1;');
INSERT INTO `pn_module_vars` VALUES (30, '/PNConfig', 'admart', 'i:20;');
INSERT INTO `pn_module_vars` VALUES (31, '/PNConfig', 'backend_title', 's:21:"PostNuke Powered Site";');
INSERT INTO `pn_module_vars` VALUES (32, '/PNConfig', 'backend_language', 's:5:"en-us";');
INSERT INTO `pn_module_vars` VALUES (33, '/PNConfig', 'seclevel', 's:6:"Medium";');
INSERT INTO `pn_module_vars` VALUES (34, '/PNConfig', 'secmeddays', 'i:7;');
INSERT INTO `pn_module_vars` VALUES (35, '/PNConfig', 'secinactivemins', 'i:10;');
INSERT INTO `pn_module_vars` VALUES (36, '/PNConfig', 'Version_Num', 's:7:"0.7.6.4";');
INSERT INTO `pn_module_vars` VALUES (37, '/PNConfig', 'Version_ID', 's:8:"PostNuke";');
INSERT INTO `pn_module_vars` VALUES (38, '/PNConfig', 'Version_Sub', 's:7:"Phoenix";');
INSERT INTO `pn_module_vars` VALUES (39, '/PNConfig', 'debug_sql', 'i:0;');
INSERT INTO `pn_module_vars` VALUES (40, '/PNConfig', 'anonpost', 'i:1;');
INSERT INTO `pn_module_vars` VALUES (41, '/PNConfig', 'minpass', 'i:5;');
INSERT INTO `pn_module_vars` VALUES (42, '/PNConfig', 'pollcomm', 'i:1;');
INSERT INTO `pn_module_vars` VALUES (43, '/PNConfig', 'minage', 'i:13;');
INSERT INTO `pn_module_vars` VALUES (44, '/PNConfig', 'top', 'i:10;');
INSERT INTO `pn_module_vars` VALUES (45, '/PNConfig', 'storyhome', 'i:10;');
INSERT INTO `pn_module_vars` VALUES (46, '/PNConfig', 'banners', 'i:0;');
INSERT INTO `pn_module_vars` VALUES (47, '/PNConfig', 'myIP', 's:15:"192.168.123.254";');
INSERT INTO `pn_module_vars` VALUES (48, '/PNConfig', 'language', 's:3:"eng";');
INSERT INTO `pn_module_vars` VALUES (49, '/PNConfig', 'anonymoussessions', 's:1:"1";');
INSERT INTO `pn_module_vars` VALUES (50, '/PNConfig', 'multilingual', 'i:1;');
INSERT INTO `pn_module_vars` VALUES (51, '/PNConfig', 'useflags', 'i:0;');
INSERT INTO `pn_module_vars` VALUES (52, '/PNConfig', 'language_detect', 'i:1;');
INSERT INTO `pn_module_vars` VALUES (53, '/PNConfig', 'perpage', 'i:10;');
INSERT INTO `pn_module_vars` VALUES (54, '/PNConfig', 'popular', 'i:500;');
INSERT INTO `pn_module_vars` VALUES (55, '/PNConfig', 'newlinks', 'i:10;');
INSERT INTO `pn_module_vars` VALUES (56, '/PNConfig', 'toplinks', 'i:25;');
INSERT INTO `pn_module_vars` VALUES (57, '/PNConfig', 'linksresults', 'i:10;');
INSERT INTO `pn_module_vars` VALUES (58, '/PNConfig', 'links_anonaddlinklock', 'i:0;');
INSERT INTO `pn_module_vars` VALUES (59, '/PNConfig', 'anonwaitdays', 'i:1;');
INSERT INTO `pn_module_vars` VALUES (60, '/PNConfig', 'outsidewaitdays', 'i:1;');
INSERT INTO `pn_module_vars` VALUES (61, '/PNConfig', 'useoutsidevoting', 'i:1;');
INSERT INTO `pn_module_vars` VALUES (62, '/PNConfig', 'anonweight', 'i:10;');
INSERT INTO `pn_module_vars` VALUES (63, '/PNConfig', 'outsideweight', 'i:20;');
INSERT INTO `pn_module_vars` VALUES (64, '/PNConfig', 'detailvotedecimal', 'i:2;');
INSERT INTO `pn_module_vars` VALUES (65, '/PNConfig', 'mainvotedecimal', 'i:1;');
INSERT INTO `pn_module_vars` VALUES (66, '/PNConfig', 'toplinkspercentrigger', 'i:0;');
INSERT INTO `pn_module_vars` VALUES (67, '/PNConfig', 'mostpoplinkspercentrigger', 'i:0;');
INSERT INTO `pn_module_vars` VALUES (68, '/PNConfig', 'mostpoplinks', 'i:25;');
INSERT INTO `pn_module_vars` VALUES (69, '/PNConfig', 'featurebox', 'i:1;');
INSERT INTO `pn_module_vars` VALUES (70, '/PNConfig', 'linkvotemin', 'i:5;');
INSERT INTO `pn_module_vars` VALUES (71, '/PNConfig', 'blockunregmodify', 'i:0;');
INSERT INTO `pn_module_vars` VALUES (72, '/PNConfig', 'newdownloads', 'i:10;');
INSERT INTO `pn_module_vars` VALUES (73, '/PNConfig', 'topdownloads', 'i:25;');
INSERT INTO `pn_module_vars` VALUES (74, '/PNConfig', 'downloadsresults', 'i:10;');
INSERT INTO `pn_module_vars` VALUES (75, '/PNConfig', 'downloads_anonadddownloadlock', 'i:1;');
INSERT INTO `pn_module_vars` VALUES (76, '/PNConfig', 'topdownloadspercentrigger', 'i:0;');
INSERT INTO `pn_module_vars` VALUES (77, '/PNConfig', 'mostpopdownloadspercentrigger', 'i:0;');
INSERT INTO `pn_module_vars` VALUES (78, '/PNConfig', 'mostpopdownloads', 'i:25;');
INSERT INTO `pn_module_vars` VALUES (79, '/PNConfig', 'downloadvotemin', 'i:5;');
INSERT INTO `pn_module_vars` VALUES (80, '/PNConfig', 'notify', 'i:0;');
INSERT INTO `pn_module_vars` VALUES (81, '/PNConfig', 'notify_email', 's:15:"me@yoursite.com";');
INSERT INTO `pn_module_vars` VALUES (82, '/PNConfig', 'notify_subject', 's:16:"NEWS for my site";');
INSERT INTO `pn_module_vars` VALUES (83, '/PNConfig', 'notify_message', 's:44:"Hey! You got a new submission for your site.";');
INSERT INTO `pn_module_vars` VALUES (84, '/PNConfig', 'notify_from', 's:9:"webmaster";');
INSERT INTO `pn_module_vars` VALUES (85, '/PNConfig', 'moderate', 'i:1;');
INSERT INTO `pn_module_vars` VALUES (86, '/PNConfig', 'BarScale', 'i:1;');
INSERT INTO `pn_module_vars` VALUES (87, '/PNConfig', 'tipath', 's:14:"images/topics/";');
INSERT INTO `pn_module_vars` VALUES (88, '/PNConfig', 'userimg', 's:11:"images/menu";');
INSERT INTO `pn_module_vars` VALUES (89, '/PNConfig', 'usergraphic', 'i:1;');
INSERT INTO `pn_module_vars` VALUES (90, '/PNConfig', 'topicsinrow', 'i:5;');
INSERT INTO `pn_module_vars` VALUES (91, '/PNConfig', 'httpref', 'i:0;');
INSERT INTO `pn_module_vars` VALUES (92, '/PNConfig', 'httprefmax', 'i:1000;');
INSERT INTO `pn_module_vars` VALUES (93, '/PNConfig', 'reasons', 'a:11:{i:0;s:5:"As Is";i:1;s:8:"Offtopic";i:2;s:9:"Flamebait";i:3;s:5:"Troll";i:4;s:9:"Redundant";i:5;s:10:"Insightful";i:6;s:11:"Interesting";i:7;s:11:"Informative";i:8;s:5:"Funny";i:9;s:9:"Overrated";i:10;s:10:"Underrated";}');
INSERT INTO `pn_module_vars` VALUES (94, '/PNConfig', 'AllowableHTML', 'a:83:{s:3:"!--";i:2;s:1:"a";i:2;s:4:"abbr";i:0;s:7:"acronym";i:0;s:7:"address";i:0;s:6:"applet";i:0;s:4:"area";i:0;s:1:"b";i:1;s:4:"base";i:0;s:8:"basefont";i:0;s:3:"bdo";i:0;s:3:"big";i:0;s:10:"blockquote";i:0;s:2:"br";i:1;s:6:"button";i:0;s:7:"caption";i:0;s:6:"center";i:0;s:4:"cite";i:0;s:4:"code";i:0;s:3:"col";i:0;s:8:"colgroup";i:0;s:3:"del";i:0;s:3:"dfn";i:0;s:3:"dir";i:0;s:3:"div";i:0;s:2:"dl";i:0;s:2:"dd";i:0;s:2:"dt";i:0;s:2:"em";i:1;s:5:"embed";i:0;s:8:"fieldset";i:0;s:4:"font";i:0;s:4:"form";i:0;s:2:"h1";i:0;s:2:"h2";i:0;s:2:"h3";i:0;s:2:"h4";i:0;s:2:"h5";i:0;s:2:"h6";i:0;s:2:"hr";i:1;s:1:"i";i:1;s:6:"iframe";i:0;s:3:"img";i:0;s:5:"input";i:0;s:3:"ins";i:0;s:3:"kbd";i:0;s:5:"label";i:0;s:6:"legend";i:0;s:2:"li";i:1;s:3:"map";i:0;s:7:"marquee";i:0;s:4:"menu";i:0;s:4:"nobr";i:0;s:6:"object";i:0;s:2:"ol";i:1;s:8:"optgroup";i:0;s:6:"option";i:0;s:1:"p";i:1;s:5:"param";i:0;s:3:"pre";i:1;s:1:"q";i:0;s:1:"s";i:0;s:4:"samp";i:0;s:6:"script";i:0;s:6:"select";i:0;s:5:"small";i:0;s:4:"span";i:0;s:6:"strike";i:0;s:6:"strong";i:1;s:3:"sub";i:0;s:3:"sup";i:0;s:5:"table";i:2;s:5:"tbody";i:0;s:2:"td";i:2;s:8:"textarea";i:0;s:5:"tfoot";i:0;s:2:"th";i:2;s:5:"thead";i:0;s:2:"tr";i:2;s:2:"tt";i:1;s:1:"u";i:0;s:2:"ul";i:1;s:3:"var";i:0;}');
INSERT INTO `pn_module_vars` VALUES (95, '/PNConfig', 'CensorList', 'a:14:{i:0;s:4:"fuck";i:1;s:4:"cunt";i:2;s:6:"fucker";i:3;s:7:"fucking";i:4;s:5:"pussy";i:5;s:4:"cock";i:6;s:4:"c0ck";i:7;s:3:"cum";i:8;s:4:"twat";i:9;s:4:"clit";i:10;s:5:"bitch";i:11;s:3:"fuk";i:12;s:6:"fuking";i:13;s:12:"motherfucker";}');
INSERT INTO `pn_module_vars` VALUES (96, '/PNConfig', 'CensorMode', 'i:1;');
INSERT INTO `pn_module_vars` VALUES (97, '/PNConfig', 'CensorReplace', 's:5:"*****";');
INSERT INTO `pn_module_vars` VALUES (98, '/PNConfig', 'theme_change', 'i:0;');
INSERT INTO `pn_module_vars` VALUES (99, '/PNConfig', 'htmlentities', 's:1:"1";');
INSERT INTO `pn_module_vars` VALUES (100, '/PNConfig', 'UseCompression', 'i:0;');
INSERT INTO `pn_module_vars` VALUES (101, '/PNConfig', 'refereronprint', 'i:0;');
INSERT INTO `pn_module_vars` VALUES (102, '/PNConfig', 'storyorder', 's:1:"1";');
INSERT INTO `pn_module_vars` VALUES (103, '/PNConfig', 'pnAntiCracker', 's:1:"1";');
INSERT INTO `pn_module_vars` VALUES (104, '/PNConfig', 'safehtml', 's:1:"1";');
INSERT INTO `pn_module_vars` VALUES (105, '/PNConfig', 'idnnames', 'i:0;');
INSERT INTO `pn_module_vars` VALUES (106, '/PNConfig', 'reg_allowreg', 's:1:"1";');
INSERT INTO `pn_module_vars` VALUES (107, '/PNConfig', 'reg_verifyemail', 's:1:"1";');
INSERT INTO `pn_module_vars` VALUES (108, '/PNConfig', 'reg_Illegalusername', 's:87:"root adm linux webmaster admin god administrator administrador nobody anonymous anonimo";');
INSERT INTO `pn_module_vars` VALUES (109, '/PNConfig', 'reg_noregreasons', 's:45:"Sorry, registration is disabled at this time.";');
INSERT INTO `pn_module_vars` VALUES (110, '/PNConfig', 'loadlegacy', 'i:0;');
INSERT INTO `pn_module_vars` VALUES (111, '/PNConfig', 'newspager', 'i:0;');
INSERT INTO `pn_module_vars` VALUES (112, '/PNConfig', 'siteoff', 'i:0;');
INSERT INTO `pn_module_vars` VALUES (113, 'pnrender', 'compile_check', '1');
INSERT INTO `pn_module_vars` VALUES (114, 'pnrender', 'force_compile', '');
INSERT INTO `pn_module_vars` VALUES (115, 'pnrender', 'cache', '');
INSERT INTO `pn_module_vars` VALUES (116, 'pnrender', 'expose_template', '');
INSERT INTO `pn_module_vars` VALUES (117, 'pnrender', 'lifetime', '3600');
INSERT INTO `pn_module_vars` VALUES (118, 'Xanthia', 'rootpath', 'modules');
INSERT INTO `pn_module_vars` VALUES (119, 'Xanthia', 'vba', '0');
INSERT INTO `pn_module_vars` VALUES (120, 'Xanthia', 'enablecache', '0');
INSERT INTO `pn_module_vars` VALUES (121, 'Xanthia', 'modulesnocache', '');
INSERT INTO `pn_module_vars` VALUES (122, 'Xanthia', 'db_cache', '0');
INSERT INTO `pn_module_vars` VALUES (123, 'Xanthia', 'db_compile', '0');
INSERT INTO `pn_module_vars` VALUES (124, 'Xanthia', 'compile_check', '0');
INSERT INTO `pn_module_vars` VALUES (125, 'Xanthia', 'use_db', '0');
INSERT INTO `pn_module_vars` VALUES (126, 'Xanthia', 'cache_lifetime', '3600');
INSERT INTO `pn_module_vars` VALUES (127, 'Xanthia', 'db_templates', '0');
INSERT INTO `pn_module_vars` VALUES (128, 'Xanthia', 'block_control', '0');
INSERT INTO `pn_module_vars` VALUES (129, 'Xanthia', 'TopCenter', '0');
INSERT INTO `pn_module_vars` VALUES (130, 'Xanthia', 'BotCenter', '0');
INSERT INTO `pn_module_vars` VALUES (131, 'Xanthia', 'InnerBlock', '0');
INSERT INTO `pn_module_vars` VALUES (132, 'Xanthia', 'shorturls', '0');
INSERT INTO `pn_module_vars` VALUES (133, 'Xanthia', 'shorturlsextension', 'html');
INSERT INTO `pn_module_vars` VALUES (134, 'Xanthia', 'shorturlsok', '1');
INSERT INTO `pn_module_vars` VALUES (135, 'Admin_Messages', 'itemsperpage', '25');
INSERT INTO `pn_module_vars` VALUES (136, 'Mailer', 'mailertype', '1');
INSERT INTO `pn_module_vars` VALUES (137, 'Mailer', 'charset', 'iso-8859-1');
INSERT INTO `pn_module_vars` VALUES (138, 'Mailer', 'encoding', '8bit');
INSERT INTO `pn_module_vars` VALUES (139, 'Mailer', 'contenttype', 'text/plain');
INSERT INTO `pn_module_vars` VALUES (140, 'Mailer', 'wordwrap', '50');
INSERT INTO `pn_module_vars` VALUES (141, 'Mailer', 'msmailheaders', '');
INSERT INTO `pn_module_vars` VALUES (142, 'Mailer', 'sendmailpath', '/usr/sbin/sendmail');
INSERT INTO `pn_module_vars` VALUES (143, 'Mailer', 'smtpauth', '1');
INSERT INTO `pn_module_vars` VALUES (144, 'Mailer', 'smtpserver', 'localhost');
INSERT INTO `pn_module_vars` VALUES (145, 'Mailer', 'smtpport', '25');
INSERT INTO `pn_module_vars` VALUES (146, 'Mailer', 'smtptimeout', '10');
INSERT INTO `pn_module_vars` VALUES (147, 'Mailer', 'smtpusername', '');
INSERT INTO `pn_module_vars` VALUES (148, 'Mailer', 'smtppassword', '');
INSERT INTO `pn_module_vars` VALUES (149, 'legal', 'termsofuse', '1');
INSERT INTO `pn_module_vars` VALUES (150, 'legal', 'privacypolicy', '1');
INSERT INTO `pn_module_vars` VALUES (151, 'legal', 'accessibilitystatement', '1');

-- --------------------------------------------------------

-- 
-- Table structure for table `pn_modules`
-- 

CREATE TABLE `pn_modules` (
  `pn_id` int(11) unsigned NOT NULL,
  `pn_name` varchar(64) NOT NULL default '',
  `pn_type` int(6) NOT NULL,
  `pn_displayname` varchar(64) NOT NULL default '',
  `pn_description` varchar(255) NOT NULL default '',
  `pn_regid` int(11) unsigned NOT NULL default '0',
  `pn_directory` varchar(64) NOT NULL default '',
  `pn_version` varchar(10) NOT NULL default '0',
  `pn_admin_capable` tinyint(1) NOT NULL default '0',
  `pn_user_capable` tinyint(1) NOT NULL default '0',
  `pn_state` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`pn_id`)
) TYPE=MyISAM AUTO_INCREMENT=52 ;

-- 
-- Dumping data for table `pn_modules`
-- 

INSERT INTO `pn_modules` VALUES (1, 'Admin', 2, 'Administration', 'Postnuke Administration Module', 9, 'Admin', '1.1', 1, 0, 3);
INSERT INTO `pn_modules` VALUES (2, 'Blocks', 2, 'Blocks', 'Administration of side and centre blocks', 13, 'Blocks', '2.2', 1, 1, 3);
INSERT INTO `pn_modules` VALUES (3, 'Groups', 2, 'Groups', 'Modify groups', 16, 'Groups', '1.0', 1, 0, 3);
INSERT INTO `pn_modules` VALUES (4, 'Modules', 2, 'Modules', 'Enable/disable modules, view install/docs/credits.', 1, 'Modules', '2.5', 1, 0, 3);
INSERT INTO `pn_modules` VALUES (5, 'Permissions', 2, 'Permissions', 'Modify permissions security', 22, 'Permissions', '0.4', 1, 0, 3);
INSERT INTO `pn_modules` VALUES (6, 'News', 1, 'News', 'A module to display the news on your index page', 7, 'News', '1.3', 0, 1, 3);
INSERT INTO `pn_modules` VALUES (7, 'User', 1, 'User', 'PN-Core user module', 27, 'User', '0.3', 1, 0, 3);
INSERT INTO `pn_modules` VALUES (8, 'AddStory', 1, 'AddStory', 'Add a story', 8, 'AddStory', '1.0', 1, 0, 3);
INSERT INTO `pn_modules` VALUES (9, 'Admin_Messages', 2, 'Admin_Messages', 'Display automated/programmed messages.', 0, 'Admin_Messages', '1.5', 1, 0, 3);
INSERT INTO `pn_modules` VALUES (10, 'Autolinks', 2, 'Autolinks', 'Automatically link key words', 11, 'Autolinks', '1.0', 1, 0, 1);
INSERT INTO `pn_modules` VALUES (11, 'AvantGo', 2, 'AvantGo', 'AvantGo Mobile News Module', 2, 'AvantGo', '1.4', 1, 1, 1);
INSERT INTO `pn_modules` VALUES (12, 'Banners', 1, 'Banners Admin', 'Administer Banners on your site', 12, 'Banners', '1.0', 1, 0, 1);
INSERT INTO `pn_modules` VALUES (13, 'Censor', 2, 'Censor', 'Site Censorship Control', 0, 'Censor', '1.5', 1, 0, 1);
INSERT INTO `pn_modules` VALUES (14, 'Comments', 1, 'Comments', 'Comment on articles', 14, 'Comments', '1.1', 1, 1, 3);
INSERT INTO `pn_modules` VALUES (15, 'Credits', 2, 'Credits', 'Display Module credits, license, help and contact information', 0, 'Credits', '1.2', 0, 1, 3);
INSERT INTO `pn_modules` VALUES (16, 'Downloads', 1, 'Downloads', 'Files to download', 3, 'Downloads', '1.31', 1, 1, 3);
INSERT INTO `pn_modules` VALUES (17, 'Ephemerids', 2, 'Ephemerids', 'A ''This day in history'' module.', 15, 'Ephemerids', '1.5', 1, 0, 1);
INSERT INTO `pn_modules` VALUES (18, 'Example', 2, 'Example module', 'This is an example module for PostNuke CMS', 0, 'Example', '1.2', 1, 1, 1);
INSERT INTO `pn_modules` VALUES (19, 'FAQ', 1, 'FAQ', 'Frequently Asked Questions', 4, 'FAQ', '1.11', 1, 1, 3);
INSERT INTO `pn_modules` VALUES (20, 'Header_Footer', 2, 'Header_Footer', 'Postnuke Page Header and Footer', 0, 'Header_Footer', '1.0', 0, 1, 3);
INSERT INTO `pn_modules` VALUES (21, 'legal', 2, 'Legal', 'Generic Privacy Statement and Terms of Use', 0, 'legal', '1.2', 1, 1, 3);
INSERT INTO `pn_modules` VALUES (22, 'LostPassword', 1, 'LostPassword', 'Retrieve lost password of a user.', 18, 'LostPassword', '0.5', 0, 0, 3);
INSERT INTO `pn_modules` VALUES (23, 'Mailer', 2, 'Mailer', 'Postnuke Mailer', 0, 'Mailer', '1.0', 1, 0, 3);
INSERT INTO `pn_modules` VALUES (24, 'MailUsers', 1, 'MailUsers', 'Mail all/individual users on your site.', 19, 'MailUsers', '1.3', 1, 0, 3);
INSERT INTO `pn_modules` VALUES (25, 'Members_List', 2, 'Members_List', 'Members List', 0, 'Members_List', '1.5', 1, 1, 1);
INSERT INTO `pn_modules` VALUES (26, 'Messages', 2, 'Messages', 'Private messaging system for your site', 6, 'Messages', '1.0', 0, 1, 1);
INSERT INTO `pn_modules` VALUES (27, 'Multisites', 1, 'Multisites', 'Create multiple sites using the same PN installation files', 20, 'Multisites', '0.1', 0, 0, 1);
INSERT INTO `pn_modules` VALUES (28, 'NewUser', 1, 'NewUser', 'New User for postnuke.', 21, 'NewUser', '0.5', 0, 0, 3);
INSERT INTO `pn_modules` VALUES (29, 'pnRender', 2, 'pnRender', 'The Smarty implementation for PostNuke', 0, 'pnRender', '1.0', 1, 0, 3);
INSERT INTO `pn_modules` VALUES (30, 'pn_bbcode', 2, 'pn_bbcode', 'BBCode Hook', 164, 'pn_bbcode', '1.20', 1, 1, 1);
INSERT INTO `pn_modules` VALUES (31, 'pn_bbsmile', 2, 'pn_bbsmile', 'Smilie Hook (Autoincluded)', 163, 'pn_bbsmile', '1.17', 1, 1, 1);
INSERT INTO `pn_modules` VALUES (32, 'Polls', 1, 'Polls', 'Polls and surveys', 23, 'Polls', '1.1', 1, 1, 3);
INSERT INTO `pn_modules` VALUES (33, 'Quotes', 2, 'Random Quote', 'Random quotes', 24, 'Quotes', '1.5', 1, 0, 1);
INSERT INTO `pn_modules` VALUES (34, 'Ratings', 2, 'Ratings', 'Rate PostNuke items', 41, 'Ratings', '1.3', 1, 1, 1);
INSERT INTO `pn_modules` VALUES (35, 'Recommend_Us', 1, 'Recommend_Us', 'Recommend site/Send articles Module', 0, 'Recommend_Us', '1.0', 0, 1, 1);
INSERT INTO `pn_modules` VALUES (36, 'Referers', 1, 'Referers', 'Referers', 25, 'Referers', '1.3', 1, 0, 3);
INSERT INTO `pn_modules` VALUES (37, 'Reviews', 1, 'Reviews', 'Reviews', 31, 'Reviews', '1.0', 1, 1, 3);
INSERT INTO `pn_modules` VALUES (38, 'RSS', 2, 'RSS', 'RSS News Feed Reader', 0, 'RSS', '1.0', 1, 1, 1);
INSERT INTO `pn_modules` VALUES (39, 'Search', 1, 'Search', 'Search this site', 32, 'Search', '1.0', 0, 1, 3);
INSERT INTO `pn_modules` VALUES (40, 'Sections', 1, 'Sections', 'Sections', 33, 'Sections', '1.0', 1, 1, 3);
INSERT INTO `pn_modules` VALUES (41, 'Settings', 1, 'Settings', 'Settings', 26, 'Settings', '1.4', 1, 0, 3);
INSERT INTO `pn_modules` VALUES (42, 'Sniffer', 2, 'Sniffer', 'Browser detection and information', 0, 'Sniffer', '1.1', 1, 0, 1);
INSERT INTO `pn_modules` VALUES (43, 'Stats', 1, 'Stats', 'Site statistics', 34, 'Stats', '1.13', 0, 1, 3);
INSERT INTO `pn_modules` VALUES (44, 'Submit_News', 1, 'Submit_News', 'Contribute a story', 0, 'Submit_News', '1.13', 1, 1, 3);
INSERT INTO `pn_modules` VALUES (45, 'Topics', 1, 'Topics', 'Article topics', 37, 'Topics', '1.0', 1, 1, 3);
INSERT INTO `pn_modules` VALUES (46, 'Top_List', 1, 'Top_List', 'Display top x listings', 0, 'Top_List', '1.0', 1, 1, 1);
INSERT INTO `pn_modules` VALUES (47, 'typetool', 2, 'typetool', 'TypeTool Visual Editor Implementation', 0, 'typetool', '8.0', 1, 0, 1);
INSERT INTO `pn_modules` VALUES (48, 'Web_Links', 1, 'Web_Links', 'Links to other sites', 0, 'Web_Links', '1.0', 1, 1, 3);
INSERT INTO `pn_modules` VALUES (49, 'Wiki', 1, 'Wiki', 'Allow Wiki formatting in the news', 28, 'Wiki', '1.0', 0, 0, 1);
INSERT INTO `pn_modules` VALUES (50, 'Xanthia', 2, 'Xanthia', 'Xanthia Theme Engine', 0, 'Xanthia', '2.1', 1, 0, 3);
INSERT INTO `pn_modules` VALUES (51, 'Your_Account', 1, 'Your_Account', 'User options', 0, 'Your_Account', '0.8', 0, 0, 3);

-- --------------------------------------------------------

-- 
-- Table structure for table `pn_poll_check`
-- 

CREATE TABLE `pn_poll_check` (
  `pn_ip` varchar(20) NOT NULL default '',
  `pn_time` varchar(14) NOT NULL default ''
) TYPE=InnoDB;

-- 
-- Dumping data for table `pn_poll_check`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_poll_data`
-- 

CREATE TABLE `pn_poll_data` (
  `pn_pollid` int(11) NOT NULL default '0',
  `pn_optiontext` char(50) NOT NULL default '',
  `pn_optioncount` int(11) NOT NULL default '0',
  `pn_voteid` int(11) NOT NULL default '0'
) TYPE=InnoDB;

-- 
-- Dumping data for table `pn_poll_data`
-- 

INSERT INTO `pn_poll_data` VALUES (2, '', 0, 12);
INSERT INTO `pn_poll_data` VALUES (2, '', 0, 11);
INSERT INTO `pn_poll_data` VALUES (2, '', 0, 10);
INSERT INTO `pn_poll_data` VALUES (2, '', 0, 9);
INSERT INTO `pn_poll_data` VALUES (2, '', 0, 8);
INSERT INTO `pn_poll_data` VALUES (2, '', 0, 7);
INSERT INTO `pn_poll_data` VALUES (2, '', 0, 6);
INSERT INTO `pn_poll_data` VALUES (2, '', 0, 5);
INSERT INTO `pn_poll_data` VALUES (2, '', 0, 4);
INSERT INTO `pn_poll_data` VALUES (2, 'Think? I use it!', 0, 3);
INSERT INTO `pn_poll_data` VALUES (2, 'It is what was needed.', 0, 2);
INSERT INTO `pn_poll_data` VALUES (2, 'What is PostNuke ?', 0, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `pn_poll_desc`
-- 

CREATE TABLE `pn_poll_desc` (
  `pn_pollid` int(11) NOT NULL,
  `pn_title` varchar(100) NOT NULL default '',
  `pn_timestamp` int(11) NOT NULL default '0',
  `pn_voters` mediumint(9) NOT NULL default '0',
  `pn_language` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`pn_pollid`)
) TYPE=InnoDB AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `pn_poll_desc`
-- 

INSERT INTO `pn_poll_desc` VALUES (2, 'What do you think of PostNuke?', 995385085, 0, '');

-- --------------------------------------------------------

-- 
-- Table structure for table `pn_pollcomments`
-- 

CREATE TABLE `pn_pollcomments` (
  `pn_tid` int(11) NOT NULL,
  `pn_pid` int(11) default '0',
  `pn_pollid` int(11) default '0',
  `pn_date` datetime default NULL,
  `pn_name` varchar(60) NOT NULL default '',
  `pn_email` varchar(60) default NULL,
  `pn_url` varchar(254) default NULL,
  `pn_host_name` varchar(60) default NULL,
  `pn_subject` varchar(60) NOT NULL default '',
  `pn_comment` text NOT NULL,
  `pn_score` tinyint(4) NOT NULL default '0',
  `pn_reason` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`pn_tid`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_pollcomments`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_queue`
-- 

CREATE TABLE `pn_queue` (
  `pn_qid` smallint(5) unsigned NOT NULL,
  `pn_uid` mediumint(9) NOT NULL default '0',
  `pn_arcd` tinyint(1) NOT NULL default '0',
  `pn_uname` varchar(40) NOT NULL default '',
  `pn_subject` varchar(255) NOT NULL default '',
  `pn_story` text,
  `pn_timestamp` datetime NOT NULL default '0000-00-00 00:00:00',
  `pn_topic` varchar(20) NOT NULL default '',
  `pn_language` varchar(30) NOT NULL default '',
  `pn_bodytext` text,
  PRIMARY KEY  (`pn_qid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_queue`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_realms`
-- 

CREATE TABLE `pn_realms` (
  `pn_rid` int(11) NOT NULL,
  `pn_name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`pn_rid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_realms`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_referer`
-- 

CREATE TABLE `pn_referer` (
  `pn_rid` int(11) NOT NULL,
  `pn_url` varchar(254) NOT NULL default '',
  `pn_frequency` int(15) default NULL,
  PRIMARY KEY  (`pn_rid`),
  KEY `pn_url` (`pn_url`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_referer`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_related`
-- 

CREATE TABLE `pn_related` (
  `pn_rid` int(11) NOT NULL,
  `pn_tid` int(11) NOT NULL default '0',
  `pn_name` varchar(30) NOT NULL default '',
  `pn_url` varchar(254) NOT NULL default '',
  PRIMARY KEY  (`pn_rid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_related`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_reviews`
-- 

CREATE TABLE `pn_reviews` (
  `pn_id` int(11) NOT NULL,
  `pn_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `pn_title` varchar(150) NOT NULL default '',
  `pn_text` text NOT NULL,
  `pn_reviewer` varchar(20) default NULL,
  `pn_email` varchar(60) default NULL,
  `pn_score` int(11) NOT NULL default '0',
  `pn_cover` varchar(100) NOT NULL default '',
  `pn_url` varchar(254) NOT NULL default '',
  `pn_url_title` varchar(150) NOT NULL default '',
  `pn_hits` int(11) NOT NULL default '0',
  `pn_language` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`pn_id`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_reviews`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_reviews_add`
-- 

CREATE TABLE `pn_reviews_add` (
  `pn_id` int(11) NOT NULL,
  `pn_date` datetime default NULL,
  `pn_title` varchar(150) NOT NULL default '',
  `pn_text` text NOT NULL,
  `pn_reviewer` varchar(20) NOT NULL default '',
  `pn_email` varchar(60) default NULL,
  `pn_score` int(11) NOT NULL default '0',
  `pn_url` varchar(254) NOT NULL default '',
  `pn_url_title` varchar(150) NOT NULL default '',
  `pn_language` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`pn_id`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_reviews_add`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_reviews_comments`
-- 

CREATE TABLE `pn_reviews_comments` (
  `pn_cid` int(11) NOT NULL,
  `pn_rid` int(11) NOT NULL default '0',
  `pn_userid` varchar(25) NOT NULL default '',
  `pn_date` datetime default NULL,
  `pn_comments` text,
  `pn_score` int(11) NOT NULL default '0',
  PRIMARY KEY  (`pn_cid`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_reviews_comments`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_reviews_main`
-- 

CREATE TABLE `pn_reviews_main` (
  `pn_title` varchar(100) default NULL,
  `pn_description` text
) TYPE=InnoDB;

-- 
-- Dumping data for table `pn_reviews_main`
-- 

INSERT INTO `pn_reviews_main` VALUES ('Reviews Section Title', 'Reviews Section Long Description');

-- --------------------------------------------------------

-- 
-- Table structure for table `pn_seccont`
-- 

CREATE TABLE `pn_seccont` (
  `pn_artid` int(11) NOT NULL,
  `pn_secid` int(11) NOT NULL default '0',
  `pn_title` text NOT NULL,
  `pn_content` text NOT NULL,
  `pn_counter` int(11) NOT NULL default '0',
  `pn_language` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`pn_artid`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_seccont`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_sections`
-- 

CREATE TABLE `pn_sections` (
  `pn_secid` int(11) NOT NULL,
  `pn_secname` varchar(40) NOT NULL default '',
  `pn_image` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`pn_secid`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_sections`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_session_info`
-- 

CREATE TABLE `pn_session_info` (
  `pn_sessid` varchar(32) NOT NULL default '',
  `pn_ipaddr` varchar(20) NOT NULL default '',
  `pn_firstused` int(11) NOT NULL default '0',
  `pn_lastused` int(11) NOT NULL default '0',
  `pn_uid` int(11) NOT NULL default '0',
  `pn_vars` blob,
  PRIMARY KEY  (`pn_sessid`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `pn_session_info`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_stats_date`
-- 

CREATE TABLE `pn_stats_date` (
  `pn_date` varchar(80) NOT NULL default '',
  `pn_hits` int(11) unsigned NOT NULL default '0'
) TYPE=InnoDB;

-- 
-- Dumping data for table `pn_stats_date`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_stats_hour`
-- 

CREATE TABLE `pn_stats_hour` (
  `pn_hour` tinyint(2) unsigned NOT NULL default '0',
  `pn_hits` int(11) unsigned NOT NULL default '0'
) TYPE=InnoDB;

-- 
-- Dumping data for table `pn_stats_hour`
-- 

INSERT INTO `pn_stats_hour` VALUES (0, 0);
INSERT INTO `pn_stats_hour` VALUES (1, 0);
INSERT INTO `pn_stats_hour` VALUES (2, 0);
INSERT INTO `pn_stats_hour` VALUES (3, 0);
INSERT INTO `pn_stats_hour` VALUES (4, 0);
INSERT INTO `pn_stats_hour` VALUES (5, 0);
INSERT INTO `pn_stats_hour` VALUES (6, 0);
INSERT INTO `pn_stats_hour` VALUES (7, 0);
INSERT INTO `pn_stats_hour` VALUES (8, 0);
INSERT INTO `pn_stats_hour` VALUES (9, 0);
INSERT INTO `pn_stats_hour` VALUES (10, 0);
INSERT INTO `pn_stats_hour` VALUES (11, 0);
INSERT INTO `pn_stats_hour` VALUES (12, 0);
INSERT INTO `pn_stats_hour` VALUES (13, 0);
INSERT INTO `pn_stats_hour` VALUES (14, 0);
INSERT INTO `pn_stats_hour` VALUES (15, 0);
INSERT INTO `pn_stats_hour` VALUES (16, 0);
INSERT INTO `pn_stats_hour` VALUES (17, 0);
INSERT INTO `pn_stats_hour` VALUES (18, 0);
INSERT INTO `pn_stats_hour` VALUES (19, 0);
INSERT INTO `pn_stats_hour` VALUES (20, 0);
INSERT INTO `pn_stats_hour` VALUES (21, 0);
INSERT INTO `pn_stats_hour` VALUES (22, 0);
INSERT INTO `pn_stats_hour` VALUES (23, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `pn_stats_month`
-- 

CREATE TABLE `pn_stats_month` (
  `pn_month` tinyint(2) unsigned NOT NULL default '0',
  `pn_hits` int(11) unsigned NOT NULL default '0'
) TYPE=InnoDB;

-- 
-- Dumping data for table `pn_stats_month`
-- 

INSERT INTO `pn_stats_month` VALUES (1, 0);
INSERT INTO `pn_stats_month` VALUES (2, 0);
INSERT INTO `pn_stats_month` VALUES (3, 0);
INSERT INTO `pn_stats_month` VALUES (4, 0);
INSERT INTO `pn_stats_month` VALUES (5, 0);
INSERT INTO `pn_stats_month` VALUES (6, 0);
INSERT INTO `pn_stats_month` VALUES (7, 0);
INSERT INTO `pn_stats_month` VALUES (8, 0);
INSERT INTO `pn_stats_month` VALUES (9, 0);
INSERT INTO `pn_stats_month` VALUES (10, 0);
INSERT INTO `pn_stats_month` VALUES (11, 0);
INSERT INTO `pn_stats_month` VALUES (12, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `pn_stats_week`
-- 

CREATE TABLE `pn_stats_week` (
  `pn_weekday` tinyint(1) unsigned NOT NULL default '0',
  `pn_hits` int(11) unsigned NOT NULL default '0'
) TYPE=InnoDB;

-- 
-- Dumping data for table `pn_stats_week`
-- 

INSERT INTO `pn_stats_week` VALUES (0, 0);
INSERT INTO `pn_stats_week` VALUES (1, 0);
INSERT INTO `pn_stats_week` VALUES (2, 0);
INSERT INTO `pn_stats_week` VALUES (3, 0);
INSERT INTO `pn_stats_week` VALUES (4, 0);
INSERT INTO `pn_stats_week` VALUES (5, 0);
INSERT INTO `pn_stats_week` VALUES (6, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `pn_stories`
-- 

CREATE TABLE `pn_stories` (
  `pn_sid` int(11) NOT NULL,
  `pn_catid` int(11) NOT NULL default '0',
  `pn_aid` varchar(30) NOT NULL default '',
  `pn_title` varchar(255) default NULL,
  `pn_time` datetime default NULL,
  `pn_hometext` text,
  `pn_bodytext` text NOT NULL,
  `pn_comments` int(11) default '0',
  `pn_counter` mediumint(8) unsigned default NULL,
  `pn_topic` tinyint(4) NOT NULL default '1',
  `pn_informant` varchar(20) NOT NULL default '',
  `pn_notes` text NOT NULL,
  `pn_ihome` tinyint(1) NOT NULL default '0',
  `pn_themeoverride` varchar(30) NOT NULL default '',
  `pn_language` varchar(30) NOT NULL default '',
  `pn_withcomm` tinyint(1) NOT NULL default '0',
  `pn_format_type` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`pn_sid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_stories`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_stories_cat`
-- 

CREATE TABLE `pn_stories_cat` (
  `pn_catid` int(11) NOT NULL,
  `pn_title` varchar(40) NOT NULL default '',
  `pn_counter` int(11) NOT NULL default '0',
  `pn_themeoverride` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`pn_catid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_stories_cat`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_theme_addons`
-- 

CREATE TABLE `pn_theme_addons` (
  `pn_addon_id` int(11) unsigned NOT NULL,
  `pn_block_id` int(11) unsigned NOT NULL default '0',
  `pn_addonname` varchar(25) NOT NULL default '',
  `pn_block_function` varchar(200) NOT NULL default '',
  KEY `pn_addon_id` (`pn_addon_id`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_theme_addons`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_theme_blcontrol`
-- 

CREATE TABLE `pn_theme_blcontrol` (
  `pn_module` varchar(64) NOT NULL default '',
  `pn_block` varchar(32) NOT NULL default '',
  `pn_theme` varchar(32) NOT NULL default '',
  `pn_identi` varchar(32) NOT NULL default '',
  `pn_pos` varchar(4) NOT NULL default '',
  `pn_weight` decimal(10,1) NOT NULL default '1.0',
  `pn_template` varchar(50) NOT NULL default '',
  `pn_active` tinyint(1) NOT NULL default '1',
  `pn_always` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`pn_block`,`pn_module`,`pn_theme`)
) TYPE=InnoDB;

-- 
-- Dumping data for table `pn_theme_blcontrol`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_theme_cache`
-- 

CREATE TABLE `pn_theme_cache` (
  `cache_id` varchar(32) NOT NULL default '',
  `cache_contents` mediumtext NOT NULL,
  PRIMARY KEY  (`cache_id`)
) TYPE=InnoDB;

-- 
-- Dumping data for table `pn_theme_cache`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_theme_config`
-- 

CREATE TABLE `pn_theme_config` (
  `skin_id` int(11) NOT NULL default '1',
  `name` varchar(200) NOT NULL default '',
  `description` varchar(60) NOT NULL default '',
  `setting` text NOT NULL,
  `data` text NOT NULL
) TYPE=InnoDB;

-- 
-- Dumping data for table `pn_theme_config`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_theme_layout`
-- 

CREATE TABLE `pn_theme_layout` (
  `skin_id` int(11) NOT NULL default '0',
  `zone_label` varchar(255) NOT NULL default '',
  `tpl_file` varchar(200) NOT NULL default '',
  `skin_type` varchar(8) NOT NULL default 'theme',
  KEY `skin_id` (`skin_id`)
) TYPE=InnoDB;

-- 
-- Dumping data for table `pn_theme_layout`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_theme_palette`
-- 

CREATE TABLE `pn_theme_palette` (
  `palette_id` int(11) unsigned NOT NULL,
  `palette_name` varchar(32) NOT NULL default '',
  `skin_id` int(11) NOT NULL default '0',
  `pn_module` varchar(64) NOT NULL default '',
  `all_themes` tinyint(1) NOT NULL default '1',
  `background` varchar(12) NOT NULL default '#FFFFFF',
  `color1` varchar(12) NOT NULL default '#FFFFFF',
  `color2` varchar(12) NOT NULL default '#FFFFFF',
  `color3` varchar(12) NOT NULL default '#FFFFFF',
  `color4` varchar(12) NOT NULL default '#FFFFFF',
  `color5` varchar(12) NOT NULL default '#FFFFFF',
  `color6` varchar(12) NOT NULL default '#000000',
  `color7` varchar(12) NOT NULL default '#000000',
  `color8` varchar(12) NOT NULL default '#000000',
  `sepcolor` varchar(12) NOT NULL default '#000000',
  `text1` varchar(12) NOT NULL default '#000000',
  `text2` varchar(12) NOT NULL default '#000000',
  `link` varchar(12) NOT NULL default '#000000',
  `vlink` varchar(12) NOT NULL default '#000000',
  `hover` varchar(12) NOT NULL default '#000000',
  PRIMARY KEY  (`palette_id`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_theme_palette`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_theme_skins`
-- 

CREATE TABLE `pn_theme_skins` (
  `skin_id` int(11) unsigned NOT NULL,
  `name` varchar(200) NOT NULL default '',
  `is_active` int(1) NOT NULL default '0',
  `is_multicolor` int(1) NOT NULL default '0',
  PRIMARY KEY  (`skin_id`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_theme_skins`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_theme_tplfile`
-- 

CREATE TABLE `pn_theme_tplfile` (
  `tpl_id` mediumint(7) unsigned NOT NULL,
  `tpl_skin_id` smallint(5) unsigned NOT NULL default '0',
  `tpl_module` varchar(25) NOT NULL default '',
  `tpl_skin_name` varchar(50) NOT NULL default '',
  `tpl_file` varchar(200) NOT NULL default '',
  `tpl_desc` varchar(255) NOT NULL default '',
  `tpl_lastmodified` int(10) unsigned NOT NULL default '0',
  `tpl_lastimported` int(10) unsigned NOT NULL default '0',
  `tpl_type` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`tpl_id`),
  KEY `tpl_skin_id` (`tpl_skin_id`,`tpl_type`),
  KEY `tpl_skin_name` (`tpl_skin_name`,`tpl_file`(10))
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_theme_tplfile`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_theme_tplsource`
-- 

CREATE TABLE `pn_theme_tplsource` (
  `tpl_id` int(11) unsigned NOT NULL,
  `tpl_skin_id` int(11) unsigned NOT NULL default '0',
  `tpl_file_name` varchar(200) NOT NULL default '',
  `tpl_source` mediumtext NOT NULL,
  `tpl_secure` tinyint(1) NOT NULL default '1',
  `tpl_trusted` tinyint(1) NOT NULL default '1',
  `tpl_timestamp` timestamp NOT NULL,
  KEY `tpl_id` (`tpl_id`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_theme_tplsource`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_theme_zones`
-- 

CREATE TABLE `pn_theme_zones` (
  `zone_id` int(3) NOT NULL,
  `skin_id` int(3) NOT NULL default '1',
  `name` varchar(40) NOT NULL default 'No Name',
  `label` varchar(255) NOT NULL default 'addon',
  `type` int(1) NOT NULL default '1',
  `is_active` int(1) NOT NULL default '0',
  `skin_type` varchar(8) NOT NULL default 'theme',
  PRIMARY KEY  (`zone_id`),
  KEY `type` (`type`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_theme_zones`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_topics`
-- 

CREATE TABLE `pn_topics` (
  `pn_topicid` tinyint(4) NOT NULL,
  `pn_topicname` varchar(255) default NULL,
  `pn_topicimage` varchar(255) default NULL,
  `pn_topictext` varchar(255) default NULL,
  `pn_counter` int(11) NOT NULL default '0',
  PRIMARY KEY  (`pn_topicid`)
) TYPE=InnoDB AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `pn_topics`
-- 

INSERT INTO `pn_topics` VALUES (1, 'PostNuke', 'PostNuke.gif', 'PostNuke', 0);
INSERT INTO `pn_topics` VALUES (2, 'Linux', 'linux.gif', 'Linux', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `pn_user_data`
-- 

CREATE TABLE `pn_user_data` (
  `pn_uda_id` int(11) NOT NULL,
  `pn_uda_propid` int(11) NOT NULL default '0',
  `pn_uda_uid` int(11) NOT NULL default '0',
  `pn_uda_value` mediumblob NOT NULL,
  PRIMARY KEY  (`pn_uda_id`),
  UNIQUE KEY `index_id_propid` (`pn_uda_propid`,`pn_uda_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_user_data`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_user_perms`
-- 

CREATE TABLE `pn_user_perms` (
  `pn_pid` int(11) NOT NULL,
  `pn_uid` int(11) NOT NULL default '0',
  `pn_sequence` int(6) NOT NULL default '0',
  `pn_realm` int(4) NOT NULL default '0',
  `pn_component` varchar(255) NOT NULL default '',
  `pn_instance` varchar(255) NOT NULL default '',
  `pn_level` int(4) NOT NULL default '0',
  `pn_bond` int(2) NOT NULL default '0',
  PRIMARY KEY  (`pn_pid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pn_user_perms`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_user_property`
-- 

CREATE TABLE `pn_user_property` (
  `pn_prop_id` int(11) NOT NULL,
  `pn_prop_label` varchar(255) NOT NULL default '',
  `pn_prop_dtype` int(11) NOT NULL default '0',
  `pn_prop_length` int(11) NOT NULL default '255',
  `pn_prop_weight` int(11) NOT NULL default '0',
  `pn_prop_validation` varchar(255) default NULL,
  PRIMARY KEY  (`pn_prop_id`),
  UNIQUE KEY `pn_prop_label` (`pn_prop_label`)
) TYPE=MyISAM AUTO_INCREMENT=17 ;

-- 
-- Dumping data for table `pn_user_property`
-- 

INSERT INTO `pn_user_property` VALUES (1, '_UREALNAME', 0, 255, 1, NULL);
INSERT INTO `pn_user_property` VALUES (2, '_UREALEMAIL', -1, 255, 2, NULL);
INSERT INTO `pn_user_property` VALUES (3, '_UFAKEMAIL', 0, 255, 3, NULL);
INSERT INTO `pn_user_property` VALUES (4, '_YOURHOMEPAGE', 0, 255, 4, NULL);
INSERT INTO `pn_user_property` VALUES (5, '_TIMEZONEOFFSET', 0, 255, 5, NULL);
INSERT INTO `pn_user_property` VALUES (6, '_YOURAVATAR', 0, 255, 6, NULL);
INSERT INTO `pn_user_property` VALUES (7, '_YICQ', 0, 255, 7, NULL);
INSERT INTO `pn_user_property` VALUES (8, '_YAIM', 0, 255, 8, NULL);
INSERT INTO `pn_user_property` VALUES (9, '_YYIM', 0, 255, 9, NULL);
INSERT INTO `pn_user_property` VALUES (10, '_YMSNM', 0, 255, 10, NULL);
INSERT INTO `pn_user_property` VALUES (11, '_YLOCATION', 0, 255, 11, NULL);
INSERT INTO `pn_user_property` VALUES (12, '_YOCCUPATION', 0, 255, 12, NULL);
INSERT INTO `pn_user_property` VALUES (13, '_YINTERESTS', 0, 255, 13, NULL);
INSERT INTO `pn_user_property` VALUES (14, '_SIGNATURE', 0, 255, 14, NULL);
INSERT INTO `pn_user_property` VALUES (15, '_EXTRAINFO', 0, 255, 15, NULL);
INSERT INTO `pn_user_property` VALUES (16, '_PASSWORD', -1, 255, 16, NULL);

-- --------------------------------------------------------

-- 
-- Table structure for table `pn_userblocks`
-- 

CREATE TABLE `pn_userblocks` (
  `pn_uid` int(11) NOT NULL default '0',
  `pn_bid` int(11) NOT NULL default '0',
  `pn_active` tinyint(3) NOT NULL default '1',
  `pn_last_update` timestamp NOT NULL
) TYPE=MyISAM;

-- 
-- Dumping data for table `pn_userblocks`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pn_users`
-- 

CREATE TABLE `pn_users` (
  `pn_uid` int(11) NOT NULL,
  `pn_name` varchar(60) NOT NULL default '',
  `pn_uname` varchar(25) NOT NULL default '',
  `pn_email` varchar(60) NOT NULL default '',
  `pn_femail` varchar(60) NOT NULL default '',
  `pn_url` varchar(254) NOT NULL default '',
  `pn_user_avatar` varchar(30) default NULL,
  `pn_user_regdate` varchar(20) NOT NULL default '',
  `pn_user_icq` varchar(15) default NULL,
  `pn_user_occ` varchar(100) default NULL,
  `pn_user_from` varchar(100) default NULL,
  `pn_user_intrest` varchar(150) default NULL,
  `pn_user_sig` varchar(255) default NULL,
  `pn_user_viewemail` tinyint(2) default NULL,
  `pn_user_theme` tinyint(3) default NULL,
  `pn_user_aim` varchar(18) default NULL,
  `pn_user_yim` varchar(25) default NULL,
  `pn_user_msnm` varchar(25) default NULL,
  `pn_pass` varchar(40) NOT NULL default '',
  `pn_storynum` tinyint(4) NOT NULL default '10',
  `pn_umode` varchar(10) NOT NULL default '',
  `pn_uorder` tinyint(1) NOT NULL default '0',
  `pn_thold` tinyint(1) NOT NULL default '0',
  `pn_noscore` tinyint(1) NOT NULL default '0',
  `pn_bio` tinytext NOT NULL,
  `pn_ublockon` tinyint(1) NOT NULL default '0',
  `pn_ublock` text NOT NULL,
  `pn_theme` varchar(255) NOT NULL default '',
  `pn_commentmax` int(11) NOT NULL default '4096',
  `pn_counter` int(11) NOT NULL default '0',
  `pn_timezone_offset` float(3,1) NOT NULL default '0.0',
  PRIMARY KEY  (`pn_uid`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `pn_users`
-- 

INSERT INTO `pn_users` VALUES (1, '', 'Anonymous', '', '', '', 'blank.gif', '1159444259', '', '', '', '', '', 0, 0, '', '', '', '', 10, '', 0, 0, 0, '', 0, '', '', 4096, 0, 12.0);
INSERT INTO `pn_users` VALUES (2, 'Admin', 'Admin', 'postnuke@example.com', '', 'http://www.postnuke.com', 'blank.gif', '1159444259', '', '', '', '', '', 0, 0, '', '', '', 'dc647eb65e6711e155375218212b3964', 10, '', 0, 0, 0, '', 0, '', '', 4096, 0, 12.0);
