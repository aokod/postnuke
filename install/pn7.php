<?php 
// File: $Id: pn7.php 15724 2005-02-17 13:21:21Z jorg $
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
// Purpose of file: Upgrade from PostNuke .7 to PostNuke .71
// ----------------------------------------------------------------------
// Note that this is upgrade script uses ADODB instead
// of raw mysql
global $dbconn, $pntable, $prefix, $pnconfig; 
include_once 'install/pntables71.php';
// Change all of the field names in the database to the new prefix
// Note - this should stay first, it makes the stuff below easier to handle
// as it can use the $pntable array
include('install/pn7db.php');
// Standard Fields included in PostNuke
// New table for session information
$sicolumn = $pntable['session_info_column'];
$dbconn->Execute("CREATE TABLE " . $prefix . "_session_info (
    $sicolumn[sessid] varchar(32) NOT NULL,
    $sicolumn[ipaddr] varchar(20) NOT NULL,
    $sicolumn[firstused] int(11) NOT NULL,
    $sicolumn[lastused] int(11) NOT NULL,
    $sicolumn[uid] int(11) NOT NULL DEFAULT 0,
    $sicolumn[vars] blob,
    PRIMARY KEY (pn_sessid)
    ) TYPE=MyISAM");
// New table for hooks
$hcolumn = $pntable['hooks_column'];
$dbconn->Execute(" CREATE TABLE " . $prefix . "_hooks (
    $hcolumn[id] int(11) unsigned NOT NULL auto_increment,
    $hcolumn[object] varchar(64) NOT NULL,
    $hcolumn[action] varchar(64) NOT NULL,
    $hcolumn[smodule] varchar(64),
    $hcolumn[stype] varchar(64),
    $hcolumn[tarea] varchar(64) NOT NULL,
    $hcolumn[tmodule] varchar(64) NOT NULL,
    $hcolumn[ttype] varchar(64) NOT NULL,
    $hcolumn[tfunc] varchar(64) NOT NULL,
    PRIMARY KEY  (pn_id)
    )");
// Hooks
$dbconn->Execute("INSERT INTO $pntable[hooks]
                  VALUES (1,
                          'item',
                          'display',
                          NULL,
                          NULL,
                          'GUI',
                          'Ratings',
                          'user',
                          'display')") || die("<b>" . _NOTUPDATED . $prefix . "_hooks</b>");
$dbconn->Execute("INSERT INTO " . $prefix . "_hooks
                  VALUES (2,
                          'item',
                          'transform',
                          NULL,
                          NULL,
                          'API',
                          'Wiki',
                          'user',
                          'transform')") || die("<b>" . _NOTUPDATED . $prefix . "_hooks</b>");
// Some copies of PN seem to have an 'id' field in user_perms rather than a 'pid' field - fix this
$dbconn->Execute("ALTER TABLE $pntable[user_perms] CHANGE id pn_pid INT (11) NOT NULL AUTO_INCREMENT");
$dbconn->Execute("ALTER TABLE $pntable[user_perms] CHANGE pn_id pn_pid INT (11) NOT NULL AUTO_INCREMENT");
// Remove typocoding from messages
$mcolumn = $pntable['message_column'];
$dbconn->Execute("UPDATE $pntable[message]
                  SET $mcolumn[title] = substring($mcolumn[title], 4) where substring($mcolumn[title],1,2) = '_T'");
$dbconn->Execute("UPDATE $pntable[message]
                  SET $mcolumn[content] = substring($mcolumn[content], 4) where substring($mcolumn[content],1,2) = '_T'");
// Update news
// 1 - update typocoding
// 2 - translate \n -> <br />
$scolumn = $pntable['stories_column'];
$result = $dbconn->Execute("SELECT $scolumn[sid],
                                   $scolumn[title],
                                   $scolumn[hometext],
                                   $scolumn[bodytext],
                                   $scolumn[notes]
                            FROM $pntable[stories]");
$foundrecords = !$result->EOF;
if ($foundrecords) {
    while (!$result->EOF) {
        list($sid, $title, $hometext, $bodytext, $notes) = $result->fields;
        if (preg_match('/^_T/', $title)) {
            $title = substr($title, 3);
            $hometext = substr($hometext, 3);
            $bodytext = substr($bodytext, 3);
            $notes = substr($notes, 3);
        } 

        $hometext = preg_replace('!<br( /)?>$!m', '', $hometext);
        $hometext = nl2br($hometext);

        $bodytext = preg_replace('!<br( /)?>$!m', '', $bodytext);
        $bodytext = nl2br($bodytext);

        $notes = preg_replace('!<br( /)?>$!m', '', $notes);
        $notes = nl2br($notes);

        $dbconn->Execute("UPDATE $pntable[stories]
                          SET $scolumn[title] = '" . addslashes($title) . "',
                              $scolumn[hometext] = '" . addslashes($hometext) . "',
                              $scolumn[bodytext] = '" . addslashes($bodytext) . "',
                              $scolumn[notes] = '" . addslashes($notes) . "'
                          WHERE $scolumn[sid] = $sid");
        $result->MoveNext();
    } 
    $result->Close();
} 
// Verfy existance prior to alter Neo
$pnValConfigVar = $dbconn->Execute("SELECT pn_format_type FROM " . $prefix . "_stories
                              WHERE pn_sid >0 LIMIT 1");
if (!$pnValConfigVar) {
    $dbconn->Execute("ALTER TABLE $pntable[stories] ADD pn_format_type tinyint(1) unsigned NOT NULL DEFAULT '0'") || die("<b>" . _NOTUPDATED . "$pntable[stories]</b>");
} 
// Change the anonymous users regdate to timestamp format
$userscolumn = $pntable['users_column'];
$dbconn->Execute("UPDATE $pntable[users] SET $userscolumn[user_regdate] = " . time() . " WHERE $userscolumn[uname] = 'Anonymous'");
// New table for modules and module variables
$mcolumn = $pntable['modules_column'];
$dbconn->Execute("CREATE TABLE " . $prefix . "_modules (
                         $mcolumn[id] int(11) unsigned NOT NULL auto_increment,
                         $mcolumn[name] varchar(64) NOT NULL,
                         $mcolumn[type] int(6) NOT NULL,
                         $mcolumn[displayname] varchar(64) NOT NULL,
                         $mcolumn[description] varchar(255) NOT NULL,
                         $mcolumn[regid] int(11) unsigned NOT NULL DEFAULT 0,
                         $mcolumn[directory] varchar(64) NOT NULL,
                         $mcolumn[version] varchar(10) NOT NULL DEFAULT 0,
                         $mcolumn[admin_capable] tinyint(1) NOT NULL DEFAULT 0,
                         $mcolumn[user_capable] tinyint(1) NOT NULL DEFAULT 0,
                         $mcolumn[state] tinyint(1) NOT NULL DEFAULT 0,
                  PRIMARY KEY(pn_id)
                  ) TYPE=MyISAM");

$mvcolumn = $pntable['module_vars_column'];
$dbconn->Execute("CREATE TABLE " . $prefix . "_module_vars (
                         $mvcolumn[id] int(11) unsigned NOT NULL auto_increment,
                         $mvcolumn[modname] varchar(64) NOT NULL,
                         $mvcolumn[name] varchar(64) NOT NULL,
                         $mvcolumn[value] longtext,
                         PRIMARY KEY(pn_id),
                         KEY pn_modname (pn_modname),
                         KEY pn_name (pn_name)
                   ) TYPE=MyISAM");
// Modules supplied with system
// TODO - populate module table with current modules
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (1,'AvantGo',1,'AvantGo','News for your PDA',2,'AvantGo','1.3',0,1,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (2,'Downloads',1,'Downloads','Files to download',3,'Downloads','1.3',1,1,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (3,'FAQ',1,'FAQ','Frequently Asked Questions',4,'FAQ','1.11',1,1,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (4,'Members_List',1,'Members List','Information on users of this site',5,'Members_List','1.0',0,1,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (5,'Messages',1,'Messages','Private messages to users of this site',6,'Messages','1.0',0,1,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (6,'AddStory',1,'AddStory','Add a story',8,'NS-AddStory','1.0',1,0,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (7,'Admin',1,'Admin','Administration',9,'NS-Admin','0.1',1,0,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (8,'Admin_Messages',1,'Admin Messages','Banner messages',10,'NS-Admin_Messages','1.2',1,0,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (9,'Autolinks',1,'Autolinks','Automatically add links to text',11,'Autolinks','1.0',1,0,1)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (10,'Banners',1,'Banners','Banners',12,'NS-Banners','1.0',1,0,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (11,'Blocks',2,'Blocks','Side blocks',13,'Blocks','2.0',1,0,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (12,'Comments',1,'Comments','Comment on articles',14,'NS-Comments','1.1',1,0,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (13,'Ephemerids',1,'Ephemerids','Daily events',15,'NS-Ephemerids','1.2',1,0,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (14,'Groups',1,'Groups','Set up administrative groups',16,'NS-Groups','0.1',1,0,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (15,'Languages',1,'Languages','Multi-language functions',17,'NS-Languages','1.2',1,0,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (16,'MailUsers',1,'MailUsers','Mail your users',19,'NS-MailUsers','1.3',1,0,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (17,'Modules',2,'Modules','Module configuration',1,'Modules','2.0',1,0,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (18,'Permissions',2,'Permissions','Configure permissions',22,'Permissions','0.1',1,0,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (19,'Polls',1,'Polls','Polls and surveys',23,'NS-Polls','1.1',1,1,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (20,'Quotes',2,'Quotes','Quotes and sayings',24,'Quotes','1.3',1,0,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (21,'Referers',1,'Referers','Referers',25,'NS-Referers','1.2',1,0,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (22,'Settings',1,'Settings','Settings',26,'NS-Settings','1.2',1,0,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (23,'News',1,'News','News items',7,'News','1.3',0,1,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (24,'Recommend_Us',1,'Recommend Us','Recommend us to a friend',30,'Recommend_Us','1.0',0,1,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (25,'Reviews',1,'Reviews','Reviews',31,'Reviews','1.0',1,1,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (26,'Search',1,'Search','Search this site',32,'Search','1.0',0,1,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (27,'Sections',1,'Sections','Sections',33,'Sections','1.0',1,1,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (28,'Stats',1,'Stats','Site statistics',34,'Stats','1.12',0,1,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (29,'Submit_News',1,'Submit News','Contribute a story',35,'Submit_News','1.13',1,1,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (30,'Top_List',1,'Top List','Top 10 listings',38,'Top_List','1.0',1,1,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (31,'Topics',1,'Topics','Article topics',37,'Topics','1.0',1,1,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (32,'User',1,'Users','User Aministration',27,'NS-User','0.1',1,1,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (33,'Web_Links',1,'Web Links','Links to other sites',39,'Web_Links','1.0',1,1,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (34,'Ratings',2,'Ratings','Ratings utility',41,'Ratings','1.1',0,0,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (35,'Wiki',2,'Wiki','Wiki encoding',28,'Wiki','1.0',0,0,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_modules VALUES (36,'xmlrpc',2,'xmlrpc','XML-RPC utility module',42,'xmlrpc','1.0',0,0,3)") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
// Dynamic User Profiule
$upcolumn = $pntable['user_property_column'];
$dbconn->Execute("CREATE TABLE " . $prefix . "_user_property (
  $upcolumn[prop_id] int(11) NOT NULL auto_increment,
  $upcolumn[prop_label] varchar(255) NOT NULL default '',
  $upcolumn[prop_dtype] int(11) NOT NULL default '0',
  $upcolumn[prop_length] int(11) NOT NULL default '255',
  $upcolumn[prop_weight] int(11) NOT NULL default '0',
  $upcolumn[prop_validation] varchar(255) default NULL,
  PRIMARY KEY  (pn_prop_id),
  UNIQUE KEY pn_prop_label (pn_prop_label)
) TYPE=MyISAM;");
// Standard Fields included in PostNuke
$dbconn->Execute("INSERT INTO " . $prefix . "_user_property VALUES (1, '_UREALNAME', 0, 255, 1, NULL)") || die("<b>" . _NOTUPDATED . $prefix . "_user_property</b>");
$dbconn->Execute("INSERT INTO " . $prefix . "_user_property VALUES (2, '_UREALEMAIL', -1, 255, 2, NULL)") || die("<b>" . _NOTUPDATED . $prefix . "_user_property</b>");
$dbconn->Execute("INSERT INTO " . $prefix . "_user_property VALUES (3, '_UFAKEMAIL', 0, 255, 3, NULL)") || die("<b>" . _NOTUPDATED . $prefix . "_user_property</b>");
$dbconn->Execute("INSERT INTO " . $prefix . "_user_property VALUES (4, '_YOURHOMEPAGE', 0, 255, 4, NULL)") || die("<b>" . _NOTUPDATED . $prefix . "_user_property</b>");
$dbconn->Execute("INSERT INTO " . $prefix . "_user_property VALUES (5, '_TIMEZONEOFFSET', 0, 255, 5, NULL)") || die("<b>" . _NOTUPDATED . $prefix . "_user_property</b>");
$dbconn->Execute("INSERT INTO " . $prefix . "_user_property VALUES (6, '_YOURAVATAR', 0, 255, 6, NULL)") || die("<b>" . _NOTUPDATED . $prefix . "_user_property</b>");
$dbconn->Execute("INSERT INTO " . $prefix . "_user_property VALUES (7, '_YICQ', 0, 255, 7, NULL)") || die("<b>" . _NOTUPDATED . $prefix . "_user_property</b>");
$dbconn->Execute("INSERT INTO " . $prefix . "_user_property VALUES (8, '_YAIM', 0, 255, 8, NULL)") || die("<b>" . _NOTUPDATED . $prefix . "_user_property</b>");
$dbconn->Execute("INSERT INTO " . $prefix . "_user_property VALUES (9, '_YYIM', 0, 255, 9, NULL)") || die("<b>" . _NOTUPDATED . $prefix . "_user_property</b>");
$dbconn->Execute("INSERT INTO " . $prefix . "_user_property VALUES (10, '_YMSNM', 0, 255, 10, NULL)") || die("<b>" . _NOTUPDATED . $prefix . "_user_property</b>");
$dbconn->Execute("INSERT INTO " . $prefix . "_user_property VALUES (11, '_YLOCATION', 0, 255, 11, NULL)") || die("<b>" . _NOTUPDATED . $prefix . "_user_property</b>");
$dbconn->Execute("INSERT INTO " . $prefix . "_user_property VALUES (12, '_YOCCUPATION', 0, 255, 12, NULL)") || die("<b>" . _NOTUPDATED . $prefix . "_user_property</b>");
$dbconn->Execute("INSERT INTO " . $prefix . "_user_property VALUES (13, '_YINTERESTS', 0, 255, 13, NULL)") || die("<b>" . _NOTUPDATED . $prefix . "_user_property</b>");
$dbconn->Execute("INSERT INTO " . $prefix . "_user_property VALUES (14, '_SIGNATURE', 0, 255, 14, NULL)") || die("<b>" . _NOTUPDATED . $prefix . "_user_property</b>");
$dbconn->Execute("INSERT INTO " . $prefix . "_user_property VALUES (15, '_EXTRAINFO', 0, 255, 15, NULL)") || die("<b>" . _NOTUPDATED . $prefix . "_user_property</b>");
$dbconn->Execute("INSERT INTO " . $prefix . "_user_property VALUES (16, '_PASSWORD', -1, 255, 16, NULL)") || die("<b>" . _NOTUPDATED . $prefix . "_user_property</b>");
// Supplemantal User Data Table
$udcolumn = $pntable['user_data_column'];
$dbconn->Execute("CREATE TABLE " . $prefix . "_user_data (
    $udcolumn[uda_id] int(11) NOT NULL auto_increment,
    $udcolumn[uda_propid] int(11) NOT NULL default 0,
    $udcolumn[uda_uid] int(11) NOT NULL default 0,
    $udcolumn[uda_value] mediumblob NOT NULL,
    PRIMARY KEY  (pn_uda_pid)
    ) TYPE=MyISAM");
// Add module ID field for blocks
$bcolumn = $pntable['blocks_column'];
$dbconn->Execute("ALTER TABLE $pntable[blocks] ADD  $bcolumn[mid] INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER pn_url");
// Quotes block has migrated to its own module
$dbconn->Execute("UPDATE $pntable[blocks]
                  SET $bcolumn[mid] = 20
                  WHERE $bcolumn[bkey] = 'quote'");

// footermessage
if (!defined('_FOOTMSGTEXT')) {
	define ('_FOOTMSGTEXT','<a href="http://www.postnuke.com"><img src="images/powered/postnuke.butn.gif" alt="Web site powered by PostNuke" /></a> <a href="http://adodb.sourceforge.net"><img src="images/powered/adodb2.gif" alt="ADODB database library" /></a> <a href="http://www.php.net"><img src="images/powered/php4_powered.gif" alt="PHP Language" /></a><p>All logos and trademarks in this site are property of their respective owner. The comments are property of their posters, all the rest (c) 2004 by me<br />This web site was made with <a href="http://www.postnuke.com">PostNuke</a>, a web portal system written in PHP. PostNuke is Free Software released under the <a href="http://www.gnu.org">GNU/GPL license</a>.</p>You can syndicate our news using the file <a href="backend.php">backend.php</a>');
}
$footmsg = ""._FOOTMSGTEXT."";

// Make base config
// TODO - fix names
// TODO - make it work
// TODO - write something in the release notes or readme.1st to let people
// know about this change, and where to put their config.php
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('1', '/PNConfig','debug','i:0;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('2', '/PNConfig','sitename','s:14:\"Your Site Name\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('3', '/PNConfig','site_logo','s:8:\"logo.gif\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('4', '/PNConfig','slogan','s:16:\"Your slogan here\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('5', '/PNConfig','metakeywords','s:218:\"nuke, postnuke, postnuke, free, community, php, portal, opensource, open source, gpl, mysql, sql, database, web site, website, weblog, content management, contentmanagement, web content management, webcontentmanagement\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('6', '/PNConfig','dyn_keywords','i:0;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('7', '/PNConfig','startdate','s:9:\"June 2001\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('8', '/PNConfig','adminmail','s:22:\"postnuke@example.com\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('9', '/PNConfig','Default_Theme','s:9:\"ExtraLite\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO ".$prefix."_module_vars VALUES ('10', '/PNConfig','foot1','". serialize($footmsg) ."')") or die ("<strong>"._NOTUPDATED.$prefix."_module_vars</strong>");
//$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('10', '/PNConfig','foot1','s:1116:\"<br><a href=\"http://www.postnuke.com\" target=\"_blank\"><img src=\"images/powered/postnuke.butn.gif\" border=\"0\" alt=\"Web site powered by PostNuke\" hspace=\"10\"></a> <a href=\"http://php.weblogs.com/ADODB\" target=\"_blank\"><img src=\"images/powered/adodb2.gif\" alt=\"ADODB database library\" border=\"0\" hspace=\"10\"></a><a href=\"http://www.phplivesupport.com/\" target=\"_blank\"><img src=\"images/powered/phplive.gif\" alt=\"PHP Live!, brought to you by LivePeople.info\" border=\"0\" hspace=\"10\"></a><a href=\"http://www.php.net\" target=\"_blank\"><img src=\"images/powered/php2.gif\" alt=\"PHP Scripting Language\" border=\"0\" hspace=\"10\"></a><br><br>All logos and trademarks in this site are property of their respective owner. The comments are property of their posters, all the rest © 2002 by me<br>This web site was made with <a href=\"http://www.postnuke.com\" target=\"_blank\">PostNuke</a>, a web portal system written in PHP. PostNuke is Free Software released under the <a href=\"http://www.gnu.org\" target=\"_blank\">GNU/GPL license</a>.<br>You can syndicate our news using the file <a href=\"backend.php\">backend.php</a>\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('11', '/PNConfig','commentlimit','i:4096;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('12', '/PNConfig','anonymous','s:9:\"Anonymous\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('13', '/PNConfig','defaultgroup','s:5:\"Users\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('14', '/PNConfig','timezone_offset','i:12;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('15', '/PNConfig','nobox','i:0;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('16', '/PNConfig','funtext','i:1;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('17', '/PNConfig','reportlevel','i:0;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('18', '/PNConfig','startpage','s:4:\"News\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('19', '/PNConfig','admingraphic','i:0;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('20', '/PNConfig','admart','i:20;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('21', '/PNConfig','backend_title','s:21:\"PostNuke Powered Site\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('22', '/PNConfig','backend_language','s:5:\"en-us\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('23', '/PNConfig','seclevel','s:6:\"Medium\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('24', '/PNConfig','secmeddays','i:7;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('25', '/PNConfig','secinactivemins','i:90;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('26', '/PNConfig','Version_Num','s:5:\"0.7.1\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('27', '/PNConfig','Version_ID','s:8:\"PostNuke\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('28', '/PNConfig','Version_Sub','s:5:\"Rogue\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('29', '/PNConfig','debug_sql','i:0;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('30', '/PNConfig','anonpost','i:1;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('31', '/PNConfig','minpass','i:5;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('32', '/PNConfig','pollcomm','i:1;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('33', '/PNConfig','minage','i:13;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('34', '/PNConfig','top','i:10;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('35', '/PNConfig','storyhome','i:10;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('36', '/PNConfig','banners','i:0;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('37', '/PNConfig','myIP','s:12:\"150.10.10.10\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('38', '/PNConfig','language','s:3:\"eng\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('39', '/PNConfig','locale','s:5:\"en_US\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('40', '/PNConfig','multilingual','i:1;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('41', '/PNConfig','useflags','i:0;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('42', '/PNConfig','perpage','i:10;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('43', '/PNConfig','popular','i:500;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('44', '/PNConfig','newlinks','i:10;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('45', '/PNConfig','toplinks','i:25;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('46', '/PNConfig','linksresults','i:10;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('47', '/PNConfig','links_anonaddlinklock','i:1;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('48', '/PNConfig','anonwaitdays','i:1;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('49', '/PNConfig','outsidewaitdays','i:1;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('50', '/PNConfig','useoutsidevoting','i:1;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('51', '/PNConfig','anonweight','i:10;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('52', '/PNConfig','outsideweight','i:20;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('53', '/PNConfig','detailvotedecimal','i:2;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('54', '/PNConfig','mainvotedecimal','i:1;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('55', '/PNConfig','toplinkspercentrigger','i:0;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('56', '/PNConfig','mostpoplinkspercentrigger','i:0;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('57', '/PNConfig','mostpoplinks','i:25;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('58', '/PNConfig','featurebox','i:1;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('59', '/PNConfig','linkvotemin','i:5;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('60', '/PNConfig','blockunregmodify','i:0;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('61', '/PNConfig','newdownloads','i:10;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('62', '/PNConfig','topdownloads','i:25;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('63', '/PNConfig','downloadsresults','i:10;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('64', '/PNConfig','downloads_anonadddownloadlock','i:0;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('65', '/PNConfig','topdownloadspercentrigger','i:0;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('66', '/PNConfig','mostpopdownloadspercentrigger','i:0;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('67', '/PNConfig','mostpopdownloads','i:25;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('68', '/PNConfig','downloadvotemin','i:5;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('69', '/PNConfig','notify','i:0;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('70', '/PNConfig','notify_email','s:15:\"me@yoursite.com\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('71', '/PNConfig','notify_subject','s:16:\"NEWS for my site\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('72', '/PNConfig','notify_message','s:44:\"Hey! You got a new submission for your site.\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('73', '/PNConfig','notify_from','s:9:\"webmaster\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('74', '/PNConfig','moderate','i:1;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('75', '/PNConfig','BarScale','i:1;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('76', '/PNConfig','tipath','s:14:\"images/topics/\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('77', '/PNConfig','userimg','s:11:\"images/menu\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('78', '/PNConfig','usergraphic','i:1;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('79', '/PNConfig','topicsinrow','i:5;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('80', '/PNConfig','httpref','i:1;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('81', '/PNConfig','httprefmax','i:1000;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('83', '/PNConfig','reasons','a:11:{i:0;s:5:\"As Is\";i:1;s:8:\"Offtopic\";i:2;s:9:\"Flamebait\";i:3;s:5:\"Troll\";i:4;s:9:\"Redundant\";i:5;s:10:\"Insightful\";i:6;s:11:\"Interesting\";i:7;s:11:\"Informative\";i:8;s:5:\"Funny\";i:9;s:9:\"Overrated\";i:10;s:10:\"Underrated\";}')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('84', '/PNConfig','AllowableHTML','a:25:{s:3:\"!--\";s:1:\"2\";s:1:\"a\";s:1:\"2\";s:1:\"b\";s:1:\"2\";s:10:\"blockquote\";s:1:\"2\";s:2:\"br\";s:1:\"2\";s:6:\"center\";s:1:\"2\";s:3:\"div\";s:1:\"2\";s:2:\"em\";s:1:\"2\";s:4:\"font\";i:0;s:2:\"hr\";s:1:\"2\";s:1:\"i\";s:1:\"2\";s:3:\"img\";i:0;s:2:\"li\";s:1:\"2\";s:7:\"marquee\";i:0;s:2:\"ol\";s:1:\"2\";s:1:\"p\";s:1:\"2\";s:3:\"pre\";s:1:\"2\";s:4:\"span\";i:0;s:6:\"strong\";s:1:\"2\";s:2:\"tt\";s:1:\"2\";s:2:\"ul\";s:1:\"2\";s:5:\"table\";s:1:\"2\";s:2:\"td\";s:1:\"2\";s:2:\"th\";s:1:\"2\";s:2:\"tr\";s:1:\"2\";}')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('85', '/PNConfig','CensorList','a:14:{i:0;s:4:\"fuck\";i:1;s:4:\"cunt\";i:2;s:6:\"fucker\";i:3;s:7:\"fucking\";i:4;s:5:\"pussy\";i:5;s:4:\"cock\";i:6;s:4:\"c0ck\";i:7;s:3:\"cum\";i:8;s:4:\"twat\";i:9;s:4:\"clit\";i:10;s:5:\"bitch\";i:11;s:3:\"fuk\";i:12;s:6:\"fuking\";i:13;s:12:\"motherfucker\";}')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('86', '/PNConfig','CensorMode','i:1;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES ('87', '/PNConfig','CensorReplace','s:5:\"*****\";')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES (88, 'Ratings','defaultstyle','outoffivestars')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES (89, 'Ratings','seclevel','medium')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES (90, '/PNConfig','theme_change','i:0;')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES (92, 'Wiki','AllowedProtocols','http|https|mailto|ftp|news|gopher')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES (93, 'Wiki','ExtlinkNewWindow', '0')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES (94, 'Wiki','IntlinkNewWindow', '0')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES (95, 'Wiki','FieldSeparator','\263')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES (96, 'Wiki','InlineImages','png|jpg|gif')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars VALUES (97, 'Blocks','collapseable','1')") or die ("<b>" . _NOTUPDATED . $prefix . "_module_vars</b>");
// this don't sets properly the module_vars - commented out
/**
 * include 'config.php';
 * 
 * @include 'pn7config.php';
 * foreach($pnconfig as $k => $v) {
 * if (($k != 'dbtype') && ($k != 'dbhost') && ($k != 'dbuname') && ($k != 'dbpass')
 * && ($k != 'dbname') && ($k != 'system') && ($k != 'prefix') && ($k != 'encoded')) {
 * $v = serialize($v);
 * $dbconn->Execute("INSERT INTO ".$prefix."_module_vars
 * (pn_modname,
 * pn_name,
 * pn_value)
 * VALUES
 * ('/PNConfig',
 * '" . addslashes($k) . "',
 * '" . addslashes($v) . "')") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * }
 * }
 */
// Add new configuration variables
$dbconn->Execute("INSERT INTO " . $prefix . "_module_vars
                    (pn_modname,
                     pn_name,
                     pn_value)
                   VALUES
                     ('/PNConfig',
                      'funtext',
                      '" . serialize(0) . "')") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$dbconn->Execute("INSERT INTO " . $prefix . "_module_vars
                    (pn_modname,
                     pn_name,
                     pn_value)
                   VALUES
                     ('/PNConfig',
                      'reportlevel',
                      '" . serialize(1) . "')") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$dbconn->Execute("INSERT INTO " . $prefix . "_module_vars
                    (pn_modname,
                     pn_name,
                     pn_value)
                   VALUES
                     ('/PNConfig',
                      'seclevel',
                      '" . serialize('Medium') . "')") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$dbconn->Execute("INSERT INTO " . $prefix . "_module_vars
                    (pn_modname,
                     pn_name,
                     pn_value)
                   VALUES
                     ('/PNConfig',
                      'secmeddays',
                      '" . serialize(7) . "')") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$dbconn->Execute("INSERT INTO " . $prefix . "_module_vars
                    (pn_modname,
                     pn_name,
                     pn_value)
                   VALUES
                     ('/PNConfig',
                      'secinactivemins',
                      '" . serialize(90) . "')") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$dbconn->Execute("INSERT INTO " . $prefix . "_module_vars
                    (pn_modname,
                     pn_name,
                     pn_value)
                   VALUES
                     ('/PNConfig',
                      'funtext',
                      '" . serialize(1) . "')") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$dbconn->Execute("INSERT INTO " . $prefix . "_module_vars
                    (pn_modname,
                     pn_name,
                     pn_value)
                   VALUES
                     ('/PNConfig',
                      'mostpopdownloadspercentrigger',
                      '" . serialize(0) . "')") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
$dbconn->Execute("INSERT INTO " . $prefix . "_module_vars
                    (pn_modname,
                     pn_name,
                     pn_value)
                   VALUES
                     ('/PNConfig',
                      'user_adddownload',
                      '" . serialize(30) . "')") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
// Add messages block
$bcolumn = $pntable['blocks_column'];
$dbconn->Execute("INSERT INTO $pntable[blocks]
                    ($bcolumn[bid],
                     $bcolumn[bkey],
                     $bcolumn[title],
                     $bcolumn[content],
                     $bcolumn[url],
                     $bcolumn[mid],
                     $bcolumn[position],
                     $bcolumn[weight],
                     $bcolumn[active],
                     $bcolumn[refresh],
                     $bcolumn[last_update],
                     $bcolumn[language])
                  VALUES
                    (0,
                     'messages',
                     'Messages',
                     '',
                     '',
                     8,
                     'c',
                     1.0,
                     1,
                     0,
                     0,
                     '')");
// Migrate = to := in blocks' content
$bcolumn = $pntable['blocks_column'];
$result = $dbconn->Execute("SELECT $bcolumn[bid],
                                   $bcolumn[content]
                            FROM $pntable[blocks]");
$foundrecords = !$result->EOF;
while (list($bid, $content) = $result->fields) {
    $result->MoveNext();

    if (preg_match('/:=/', $content)) {
        continue;
    } 
    if (preg_match('/=/', $content)) {
        $content = preg_replace('/^([^ *])=/', "\\1:=", $content);
        $dbconn->Execute("UPDATE $pntable[blocks]
                          SET $bcolumn[content] = '" . addslashes($content) . "'
                          WHERE $bcolumn[bid] = $bid");
    } 
} 
if ($foundrecords) {
    $result->Close();
} 
// Delete nuke_session table
$dbconn->Execute("DROP TABLE " . $prefix . "_session");
// Change permissions for 'Web links' to 'Web Links' in group permissions
$gpcolumn = $pntable['group_perms_column'];
$result = $dbconn->Execute("SELECT $gpcolumn[pid],
                                   $gpcolumn[component]
                            FROM $pntable[group_perms]");

$foundrecords = !$result->EOF;
while (list($pid, $component) = $result->fields) {
    $result->MoveNext();

    if (preg_match('/Web links/', $component)) {
        $component = preg_replace('/Web links/', 'Web Links', $component);
        $dbconn->Execute("UPDATE $pntable[group_perms]
                          SET $gpcolumn[component] = '" . addslashes($component) . "'
                          WHERE $gpcolumn[pid] = $pid");
    } 
} 
if ($foundrecords) {
    $result->Close();
} 
// Change permissions for 'Web links' to 'Web Links' in user permissions
$upcolumn = $pntable['user_perms_column'];
$result = $dbconn->Execute("SELECT $upcolumn[pid],
                                   $upcolumn[component]
                            FROM $pntable[user_perms]");
$foundrecords = !$result->EOF;
while (list($pid, $component) = $result->fields) {
    $result->MoveNext();

    if (preg_match('/Web links/', $component)) {
        $component = preg_replace('/Web links/', 'Web Links', $component);
        $dbconn->Execute("UPDATE $pntable[user_perms]
                          SET $upcolumn[component] = '" . addslashes($component) . "'
                          WHERE $upcolumn[pid] = $pid");
    } 
} 
if ($foundrecords) {
    $result->Close();
} 
// Add in reminder to remove the install.php file
/*
$bcolumn = $pntable['blocks_column'];
$dbconn->Execute("INSERT INTO $pntable[blocks]
                    ($bcolumn[bid],
                     $bcolumn[bkey],
                     $bcolumn[title],
                     $bcolumn[content],
                     $bcolumn[url],
                     $bcolumn[mid],
                     $bcolumn[position],
                     $bcolumn[weight],
                     $bcolumn[active],
                     $bcolumn[refresh],
                     $bcolumn[last_update],
                     $bcolumn[language])
                  VALUES
                    (0,
                     'html',
                     'Reminder',
                     'Please remember to remove the following files from your PostNuke directory
                     <p>
                     &middot;<b>install.php</b> file
                     <p>
                     &middot;<b>install</b> directory
                     <p>
                     If you do not remove these files then users can obtain the password to your database!',
                     '',
                     0,
                     'l',
                     0.5,
                     1,
                     0,
                     0,
                     '')");
*/
// Update yid
$dbconn->Execute("ALTER TABLE $prefix._ephem CHANGE pn_yid pn_yid INT(4) DEFAULT 0 NOT NULL");
// EugenioBaldi fixed error in revievs date
$revcolumn = $pntable['reviews_column'];
$dbconn->Execute("ALTER TABLE $pntable[reviews] CHANGE $revcolumn[date] $revcolumn[date] DATETIME DEFAULT '0000-00-00' not null");

$revcolumn = $pntable['reviews_add_column'];
$dbconn->Execute("ALTER TABLE $pntable[reviews_add] CHANGE $revcolumn[date] $revcolumn[date] DATETIME DEFAULT '0000-00-00' not null");
// EugenioBaldi  I Have this field tinyint(4) and max value is 127 setted from a previous install
$ephemcolumn = $pntable['ephem_column'];
$dbconn->Execute("ALTER TABLE $pntable[ephem] CHANGE $ephemcolumn[yid] $ephemcolumn[yid] INT(4) DEFAULT '0' not null");

include 'pntables.php';

?>