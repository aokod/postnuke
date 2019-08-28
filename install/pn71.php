<?php 
// File: $Id: pn71.php 15630 2005-02-04 06:35:42Z jorg $
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
// Purpose of file: Upgrade from PostNuke .71 to PostNuke .72
// ----------------------------------------------------------------------
// Note that this is upgrade script uses ADODB instead
// of raw mysql
global $dbconn, $pntable, $prefix;
include_once 'install/pntables71.php';
// Table structure changes first
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE pn_ublock pn_ublock text NOT NULL ") || die("<b>" . _NOTUPDATED . "$pntable[users]</b>");

$dbconn->Execute("ALTER TABLE $pntable[faqanswer] CHANGE pn_id pn_id int(6) NOT NULL auto_increment ") || die("<b>" . _NOTUPDATED . "$pntable[faqanswer]</b>");
$dbconn->Execute("ALTER TABLE $pntable[faqanswer] CHANGE pn_id_cat pn_id_cat int(6) DEFAULT NULL ") || die("<b>" . _NOTUPDATED . "$pntable[faqanswer]</b>");
$dbconn->Execute("ALTER TABLE $pntable[faqanswer] CHANGE pn_question pn_question text DEFAULT NULL ") || die("<b>" . _NOTUPDATED . "$pntable[faqanswer]</b>");

$dbconn->Execute("ALTER TABLE $pntable[faqcategories] CHANGE pn_id_cat pn_id_cat int(6) NOT NULL auto_increment ") || die("<b>" . _NOTUPDATED . "$pntable[faqcategories]</b>");
$dbconn->Execute("ALTER TABLE $pntable[faqcategories] CHANGE pn_parent_id pn_parent_id int(6) DEFAULT '0' NOT NULL ") || die("<b>" . _NOTUPDATED . "$pntable[faqcategories]</b>");
// Change most pn_url to 254 instead of 255 to later support vfp
$dbconn->Execute("ALTER TABLE $pntable[blocks] CHANGE pn_url pn_url VARCHAR(254) DEFAULT '' NOT NULL ") || die("<b>" . _NOTUPDATED . "$pntable[blocks]</b>");
$dbconn->Execute("ALTER TABLE $pntable[blocks_buttons] CHANGE pn_url pn_url VARCHAR(254) DEFAULT '' NOT NULL ") || die("<b>" . _NOTUPDATED . "$pntable[blocks_buttons]</b>");
$dbconn->Execute("ALTER TABLE $pntable[comments] CHANGE pn_url pn_url VARCHAR(254) DEFAULT '' NOT NULL ") || die("<b>" . _NOTUPDATED . "$pntable[comments]</b>");
$dbconn->Execute("ALTER TABLE $pntable[downloads_downloads] CHANGE pn_url pn_url VARCHAR(254) DEFAULT '' NOT NULL ") || die("<b>" . _NOTUPDATED . "$pntable[downloads_downloads]</b>");
$dbconn->Execute("ALTER TABLE $pntable[downloads_modrequest] CHANGE pn_url pn_url VARCHAR(254) DEFAULT '' NOT NULL ") || die("<b>" . _NOTUPDATED . "$pntable[downloads_modrequest]</b>");
$dbconn->Execute("ALTER TABLE $pntable[downloads_newdownload] CHANGE pn_url pn_url VARCHAR(254) DEFAULT '' NOT NULL ") || die("<b>" . _NOTUPDATED . "$pntable[downloads_newdownload]</b>");
$dbconn->Execute("ALTER TABLE $pntable[links_links] CHANGE pn_url pn_url VARCHAR(254) DEFAULT '' NOT NULL ") || die("<b>" . _NOTUPDATED . "$pntable[links_links]</b>");
$dbconn->Execute("ALTER TABLE $pntable[links_modrequest] CHANGE pn_url pn_url VARCHAR(254) DEFAULT '' NOT NULL ") || die("<b>" . _NOTUPDATED . "$pntable[links_modrequest]</b>");
$dbconn->Execute("ALTER TABLE $pntable[links_newlink] CHANGE pn_url pn_url VARCHAR(254) DEFAULT '' NOT NULL ") || die("<b>" . _NOTUPDATED . "$pntable[links_newlink]</b>");
$dbconn->Execute("ALTER TABLE $pntable[pollcomments] CHANGE pn_url pn_url VARCHAR(254) DEFAULT '' NOT NULL ") || die("<b>" . _NOTUPDATED . "$pntable[pollcomments]</b>");
$dbconn->Execute("ALTER TABLE $pntable[referer] CHANGE pn_url pn_url VARCHAR(254) DEFAULT '' NOT NULL ") || die("<b>" . _NOTUPDATED . "$pntable[referer]</b>");
$dbconn->Execute("ALTER TABLE $pntable[related] CHANGE pn_url pn_url VARCHAR(254) DEFAULT '' NOT NULL ") || die("<b>" . _NOTUPDATED . "$pntable[related]</b>");
$dbconn->Execute("ALTER TABLE $pntable[reviews] CHANGE pn_url pn_url VARCHAR(254) DEFAULT '' NOT NULL ") || die("<b>" . _NOTUPDATED . "$pntable[reviews]</b>");
$dbconn->Execute("ALTER TABLE $pntable[reviews_add] CHANGE pn_url pn_url VARCHAR(254) DEFAULT '' NOT NULL ") || die("<b>" . _NOTUPDATED . "$pntable[reviews_add]</b>");
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE pn_url pn_url VARCHAR(254) DEFAULT '' NOT NULL ") || die("<b>" . _NOTUPDATED . "$pntable[users]</b>");

$dbconn->Execute("ALTER TABLE $pntable[autonews] CHANGE pn_topic pn_topic tinyint(4) NOT NULL DEFAULT '1'") || die("<b>" . _NOTUPDATED . "$pntable[autonews]</b>");
$dbconn->Execute("ALTER TABLE $pntable[stories] CHANGE pn_topic pn_topic tinyint(4) NOT NULL DEFAULT '1'") || die("<b>" . _NOTUPDATED . "$pntable[stories]</b>");
$dbconn->Execute("ALTER TABLE $pntable[topics] CHANGE pn_topicid pn_topicid tinyint(4) NOT NULL auto_increment") || die("<b>" . _NOTUPDATED . "$pntable[topics]</b>");
// add-statements right at the end
$dbconn->Execute("ALTER TABLE $pntable[banner] ADD `pn_type` VARCHAR( 2 ) DEFAULT '0' NOT NULL AFTER `pn_cid`") || die ("<b>" . _NOTUPDATED . "$pntable[banner]</b>");
//$dbconn->Execute("ALTER TABLE $pntable[stories] ADD pn_format_type tinyint(1) unsigned NOT NULL DEFAULT '0'") || die("<b>" . _NOTUPDATED . "$pntable[stories]</b>");
// Now lets get the module_vars up to date.
$dbconn->Execute("INSERT INTO " . $prefix . "_module_vars
                    (pn_modname,
                     pn_name,
                     pn_value)
                   VALUES
                     ('/PNConfig',
                      'pnAntiCracker',
                      '" . serialize(0) . "')") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");

$dbconn->Execute("INSERT INTO " . $prefix . "_module_vars
                    (pn_modname,
                     pn_name,
                     pn_value)
                   VALUES
                     ('/PNConfig',
                      'WYSIWYGEditor',
                      '" . serialize(0) . "')") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");

$dbconn->Execute("INSERT INTO " . $prefix . "_module_vars
                    (pn_modname,
                     pn_name,
                     pn_value)
                   VALUES
                     ('/PNConfig',
                      'UseCompression',
                      '" . serialize(0) . "')") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");

$dbconn->Execute("INSERT INTO " . $prefix . "_module_vars
                    (pn_modname,
                     pn_name,
                     pn_value)
                   VALUES
                     ('/PNConfig',
                      'htmlentities',
                      '" . serialize(1) . "')") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");

$dbconn->Execute("INSERT INTO " . $prefix . "_module_vars
                    (pn_modname,
                     pn_name,
                     pn_value)
                   VALUES
                     ('Blocks',
                      'collapseable',
                      '" . serialize(1) . "')") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");

$dbconn->Execute("INSERT INTO " . $prefix . "_module_vars
                    (pn_modname,
                     pn_name,
                     pn_value)
                   VALUES
                     ('/PNConfig',
                      'storyorder',
                      '" . serialize(0) . "')") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
// Verfy existance prior to alter Neo
$pnValConfigVar = $dbconn->Execute("SELECT pn_format_type FROM " . $prefix . "_stories
                              WHERE pn_sid >0 LIMIT 1");
if (!$pnValConfigVar) {
    $dbconn->Execute("ALTER TABLE $pntable[stories] ADD pn_format_type tinyint(1) unsigned NOT NULL DEFAULT '0'") || die("<b>" . _NOTUPDATED . "$pntable[stories]</b>");
} 
// Now for the module case issue.
// First change the start page.
// Check for current startpage
$startpage = $dbconn->Execute("SELECT pn_value FROM " . $prefix . "_module_vars
                              WHERE pn_name='startpage' AND pn_modname='/PNConfig'");

list($startpage) = $startpage->fields;
$startpage = unserialize($startpage);

switch ($startpage) {
    case 'AvantGo':
    case 'Downloads':
    case 'FAQ':
    case 'Members_List':
    case 'News':
    case 'Sections':
    case 'Reviews':
    case 'Top_List':
    case 'Web_Links':
        $startpage = 'News';
} 

$startpage = serialize($startpage);
$dbconn->Execute("UPDATE " . $prefix . "_module_vars
                      SET pn_value = '" . $startpage . "'
                      WHERE pn_name = 'startpage'");
// Next empty the modules table -- Sucks for installed modules need to regenerate afterwards.
/**
 * $dbconn->Execute("DELETE FROM $pntable[modules]");
 * 
 * // Drop the new valuses back in.
 * 
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (1,'AvantGo',1,'AvantGo','News for your PDA',2,'avantgo','1.3',0,1,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (2,'Downloads',1,'Downloads','Files to download',3,'downloads','1.3',1,1,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (3,'faq',1,'FAQ','Frequently Asked Questions',4,'faq','1.11',1,1,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (4,'members_list',1,'Members List','Information on users of this site',5,'members_list','1.0',0,1,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (5,'messages',1,'Messages','Private messages to users of this site',6,'messages','1.0',0,1,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (6,'addstory',1,'AddStory','Add a story',8,'ns-addstory','1.0',1,0,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (7,'admin',1,'Admin','Administration',9,'ns-admin','0.1',1,0,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (8,'admin_messages',1,'Admin Messages','Banner messages',10,'ns-admin_messages','1.2',1,0,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (9,'autolinks',1,'Autolinks','Automatically add links to text',11,'autolinks','1.0',1,0,1)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (10,'banners',1,'Banners','Banners',12,'ns-banners','1.0',1,0,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (11,'blocks',2,'Blocks','Side blocks',13,'blocks','2.0',1,0,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (12,'comments',1,'Comments','Comment on articles',14,'ns-comments','1.1',1,1,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (13,'ephemerids',1,'Ephemerids','Daily events',15,'ns-ephemerids','1.2',1,0,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (14,'groups',1,'Groups','Set up administrative groups',16,'ns-groups','0.1',1,0,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (15,'languages',1,'Languages','Multi-language functions',17,'ns-languages','1.2',1,0,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (16,'mailusers',1,'MailUsers','Mail your users',19,'ns-mailusers','1.3',1,0,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (17,'modules',2,'Modules','Module configuration',1,'modules','2.0',1,0,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (18,'permissions',2,'Permissions','Configure permissions',22,'permissions','0.1',1,0,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (19,'polls',1,'Polls','Polls and surveys',23,'ns-polls','1.1',1,1,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (20,'quotes',2,'Quotes','Quotes and sayings',24,'quotes','1.3',1,0,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (21,'referers',1,'Referers','Referers',25,'ns-referers','1.2',1,0,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (22,'settings',1,'Settings','Settings',26,'ns-settings','1.2',1,0,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (23,'news',1,'News','News items',7,'news','1.3',0,1,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (24,'recommend_us',1,'Recommend Us','Recommend us to a friend',30,'recommend_us','1.0',0,1,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (25,'reviews',1,'Reviews','Reviews',31,'reviews','1.0',1,1,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (26,'search',1,'Search','Search this site',32,'search','1.0',0,1,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (27,'sections',1,'Sections','Sections',33,'sections','1.0',1,1,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (28,'stats',1,'Stats','Site statistics',34,'stats','1.12',0,1,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (29,'submit_news',1,'Submit News','Contribute a story',35,'submit_news','1.13',1,1,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (30,'top_list',1,'Top List','Top 10 listings',38,'top_list','1.0',1,1,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (31,'topics',1,'Topics','Article topics',37,'topics','1.0',1,1,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (32,'user',1,'Users','User Administration',27,'ns-user','0.1',1,1,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (33,'web_links',1,'Web Links','Links to other sites',39,'web_links','1.0',1,1,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (34,'ratings',2,'Ratings','Ratings utility',41,'ratings','1.1',0,0,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (35,'wiki',2,'Wiki','Wiki encoding',28,'wiki','1.0',0,0,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (36,'xmlrpc',2,'xmlrpc','XML-RPC utility module',42,'xmlrpc','1.0',0,0,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 * $result = $dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (37,'legal',1,'Legal Documents','Generic Privacy Statement and Terms of Use',43,'legal','1.0',0,1,3)") || die("<b>"._NOTUPDATED.$prefix."_modules</b>");
 */
// Modules will need to be regenerated, for the modules that are added, but other than that, blee, blee, blee, thats all folks.
// Change permissions for 'Mail users' to 'MailUsers' in user permissions
$upcolumn = $pntable['user_perms_column'];
$result = $dbconn->Execute("SELECT $upcolumn[pid],
                                   $upcolumn[component]
                            FROM $pntable[user_perms]");
$foundrecords = !$result->EOF;
while (list($pid, $component) = $result->fields) {
    $result->MoveNext();

    if (preg_match('/Mail users/', $component)) {
        $component = preg_replace('/Mail users/', 'MailUsers', $component);
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
?>