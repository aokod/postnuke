<?php
// File: $Id: newdata.php 17444 2006-01-02 12:20:51Z hammerhead $

// ml-stuff
// we check if specific defines are already set
// (from the corresponding /lang/xxx/global.php file)
// if not we use the english defaults
// this way we have some ml-installation ;)

$admin_description = (defined('_MODDESC_ADMIN')) ? ""._MODDESC_ADMIN."" : 'Administration';
$blocks_description = (defined('_MODDESC_BLOCKS')) ? ""._MODDESC_BLOCKS."" : 'Administration of side and centre blocks';
$groups_description = (defined('_MODDESC_GROUPS')) ? ""._MODDESC_GROUPS."" : 'Modify groups';
$modules_description = (defined('_MODDESC_MODULES')) ? ""._MODDESC_MODULES."" : 'Enable/disable modules, view install/docs/credits.';
$news_description = (defined('_MODDESC_NEWS')) ? ""._MODDESC_NEWS."" : 'News items'; 
$perms_description = (defined('_MODDESC_PERMISSIONS')) ? ""._MODDESC_PERMISSIONS."" : 'Configure permissions';
$user_description = (defined('_MODDESC_USER')) ? ""._MODDESC_USER."" : 'User Administration';

/* Modules names */
$admin_name = (defined('_MODNAME_ADMIN')) ? ""._MODNAME_ADMIN."" : 'Administration';
$blocks_name = (defined('_MODNAME_BLOCKS')) ? ""._MODNAME_BLOCKS."" : 'Blocks';
$groups_name = (defined('_MODNAME_GROUPS')) ? ""._MODNAME_GROUPS."" : 'Groups';
$modules_name = (defined('_MODNAME_MODULES')) ? ""._MODNAME_MODULES."" : 'Modules';
$news_name = (defined('_MODNAME_NEWS')) ? ""._MODNAME_NEWS."" : 'News';
$perms_name = (defined('_MODNAME_PERMISSIONS')) ? ""._MODNAME_PERMISSIONS."" : 'Permissions';
$user_name = (defined('_MODNAME_USER')) ? ""._MODNAME_USER."" : 'Users';

$modules = (defined('_MODULES')) ? "". _MODULES . "" : 'Modules';

// Groups
$users_group = (defined('_GROUPS_1_a')) ? ""._GROUPS_1_a."" : 'Users';
$admins_group = (defined('_GROUPS_2_a')) ? ""._GROUPS_2_a."" : 'Admins';

// populate headlines table
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_headlines VALUES (1,'PostNuke',NULL,NULL,0,'http://postnuke.com/backend.php',10,'','')") or die ("<strong>"._NOTUPDATED.$prefix."_headlines</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_headlines VALUES (2,'LinuxCentral',NULL,NULL,0,'http://linuxcentral.com/backend/lcnew.rdf',10,'','')") or die ("<strong>"._NOTUPDATED.$prefix."_headlines</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_headlines VALUES (3,'Slashdot',NULL,NULL,0,'http://slashdot.org/slashdot.rdf',10,'','')") or die ("<strong>"._NOTUPDATED.$prefix."_headlines</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_headlines VALUES (4,'NewsForge',NULL,NULL,0,'http://www.newsforge.com/newsforge.rdf',10,'','')") or die ("<strong>"._NOTUPDATED.$prefix."_headlines</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_headlines VALUES (5,'PHPBuilder',NULL,NULL,0,'http://phpbuilder.com/rss_feed.php',10,'','')") or die ("<strong>"._NOTUPDATED.$prefix."_headlines</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_headlines VALUES (6,'Linux.com',NULL,NULL,0,'http://linux.com/mrn/front_page.rss',10,'','')") or die ("<strong>"._NOTUPDATED.$prefix."_headlines</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_headlines VALUES (7,'Freshmeat',NULL,NULL,0,'http://freshmeat.net/backend/fm.rdf',10,'','')") or die ("<strong>"._NOTUPDATED.$prefix."_headlines</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_headlines VALUES (9,'LinuxWeeklyNews',NULL,NULL,0,'http://lwn.net/headlines/rss',10,'','')") or die ("<strong>"._NOTUPDATED.$prefix."_headlines</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_headlines VALUES (11,'Segfault',NULL,NULL,0,'http://segfault.org/stories.xml',10,'','')") or die ("<strong>"._NOTUPDATED.$prefix."_headlines</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_headlines VALUES (13,'KDE',NULL,NULL,0,'http://www.kde.org/news/kdenews.rdf',10,'','')") or die ("<strong>"._NOTUPDATED.$prefix."_headlines</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_headlines VALUES (14,'Perl.com',NULL,NULL,0,'http://www.perl.com/pace/perlnews.rdf',10,'','')") or die ("<strong>"._NOTUPDATED.$prefix."_headlines</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_headlines VALUES (17,'MozillaNewsBot',NULL,NULL,0,'http://www.mozilla.org/newsbot/newsbot.rdf',10,'','')") or die ("<strong>"._NOTUPDATED.$prefix."_headlines</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_headlines VALUES (21,'SciFi-News',NULL,NULL,0,'http://www.technopagan.org/sf-news/rdf.php',10,'','')") or die ("<strong>"._NOTUPDATED.$prefix."_headlines</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_headlines VALUES (26,'DrDobbsTechNetCast',NULL,NULL,0,'http://www.technetcast.com/tnc_headlines.rdf',10,'','')") or die ("<strong>"._NOTUPDATED.$prefix."_headlines</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_headlines VALUES (27,'RivaExtreme',NULL,NULL,0,'http://rivaextreme.com/ssi/rivaextreme.rdf.cdf',10,'','')") or die ("<strong>"._NOTUPDATED.$prefix."_headlines</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_headlines VALUES (29,'PBSOnline',NULL,NULL,0,'http://cgi.pbs.org/cgi-registry/featuresrdf.pl',10,'','')") or die ("<strong>"._NOTUPDATED.$prefix."_headlines</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_headlines VALUES (30,'Listology',NULL,NULL,0,'http://listology.com/recent.rdf',10,'','')") or die ("<strong>"._NOTUPDATED.$prefix."_headlines</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_headlines VALUES (33,'exoScience',NULL,NULL,0,'http://www.exosci.com/exosci.rdf',10,'','')") or die ("<strong>"._NOTUPDATED.$prefix."_headlines</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_headlines VALUES (39,'DailyDaemonNews',NULL,NULL,0,'http://daily.daemonnews.org/ddn.rdf.php3',10,'','')") or die ("<strong>"._NOTUPDATED.$prefix."_headlines</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_headlines VALUES (40,'PerlMonks',NULL,NULL,0,'http://www.perlmonks.org/headlines.rdf',10,'','')") or die ("<strong>"._NOTUPDATED.$prefix."_headlines</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_headlines VALUES (42,'BSDToday',NULL,NULL,0,'http://www.bsdtoday.com/backend/bt.rdf',10,'','')") or die ("<strong>"._NOTUPDATED.$prefix."_headlines</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_headlines VALUES (45,'HotWired',NULL,NULL,0,'http://www.hotwired.com/webmonkey/meta/headlines.rdf',10,'','')") or die ("<strong>"._NOTUPDATED.$prefix."_headlines</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_headlines VALUES (52,'SolarisCentral',NULL,NULL,0,'http://www.SolarisCentral.org/news/SolarisCentral.rdf',10,'','')") or die ("<strong>"._NOTUPDATED.$prefix."_headlines</strong>");
echo "<br /><span class=\"pn-sub\">".$prefix."_headlines"._UPDATED."</span>";

$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_module_vars VALUES ('1', 'Groups','defaultgroup','$users_group')") or die ("<strong>"._NOTUPDATED.$prefix."_module_vars</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_module_vars VALUES ('2', 'Blocks','collapseable','1')") or die ("<strong>"._NOTUPDATED.$prefix."_module_vars</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_module_vars VALUES ('3', 'Admin','modulesperrow','5')") or die ("<strong>"._NOTUPDATED.$prefix."_module_vars</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_module_vars VALUES ('4', 'Admin','itemsperpage','25')") or die ("<strong>"._NOTUPDATED.$prefix."_module_vars</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_module_vars VALUES ('5', 'Admin','defaultcategory','5')") or die ("<strong>"._NOTUPDATED.$prefix."_module_vars</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_module_vars VALUES ('6', 'Admin','modulestylesheet','navtabs.css')") or die ("<strong>"._NOTUPDATED.$prefix."_module_vars</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_module_vars VALUES ('7', 'Admin','admingraphic','1')") or die ("<strong>"._NOTUPDATED.$prefix."_module_vars</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_module_vars VALUES ('8', 'Admin','startcategory','1')") or die ("<strong>"._NOTUPDATED.$prefix."_module_vars</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_module_vars VALUES ('9', 'Admin','ignoreinstallercheck','0')") or die ("<strong>"._NOTUPDATED.$prefix."_module_vars</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_module_vars VALUES ('10', 'Modules','itemsperpage','25')") or die ("<strong>"._NOTUPDATED.$prefix."_module_vars</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_module_vars VALUES ('11', 'Groups','itemsperpage','25')") or die ("<strong>"._NOTUPDATED.$prefix."_module_vars</strong>");
echo "<br /><span class=\"pn-sub\">".$prefix."_module_vars"._UPDATED."</span>";

// populate users table
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_users VALUES ( '1', '', 'Anonymous', '', '', '', 'blank.gif', ".time().", '', '', '', '', '', '0', '0', '', '', '', '', '10', '', '0', '0', '0', '', '0', '', '', '4096', '0', '12.0')") or die ("<strong>"._NOTUPDATED.$prefix."_users</strong>");
echo "<br /><span class=\"pn-sub\">".$prefix."_users"._UPDATED."</span>";

// populate groups table
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_groups VALUES (0,'$users_group')") || die("<strong>"._NOTUPDATED.$prefix."_groups</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_groups VALUES (0,'$admins_group')") || die("<strong>"._NOTUPDATED.$prefix."_groups</strong>");
echo "<br /><span class=\"pn-sub\">".$prefix."_groups"._UPDATED."</span>";

// populate group_membership table
/*$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_group_membership VALUES (1, 1)") || die("<strong>"._NOTUPDATED.$prefix."_group_membership</strong>");
echo "<br /><span class=\"pn-sub\">".$prefix."_group_membership"._UPDATED."</span>";*/

// populate group_perms table with permisions for the admin - remaining permission needed will
// be added later
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_group_perms VALUES (0, 2, 1, 0, '.*', '.*', 800, 0)") || die("<strong>"._NOTUPDATED.$prefix."_group_perms</strong>");

// populate modules table
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (1,'Admin',2,         '$admin_name','$admin_description',9,'Admin','1.1',1,0,3)") || die("<strong>"._NOTUPDATED.$prefix."_modules</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (2,'Blocks',2,        '$blocks_name','$blocks_description',13,'Blocks','2.2',1,0,3)") || die("<strong>"._NOTUPDATED.$prefix."_modules</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (3,'Groups',2,        '$groups_name','$groups_description',16,'Groups','1.0',1,0,3)") || die("<strong>"._NOTUPDATED.$prefix."_modules</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (4,'Modules',2,       '$modules_name','$modules_description',1,'Modules','2.5',1,0,3)") || die("<strong>"._NOTUPDATED.$prefix."_modules</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (5,'Permissions',2,   '$perms_name','$perms_description',22,'Permissions','0.4',1,0,3)") || die("<strong>"._NOTUPDATED.$prefix."_modules</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (6,'News',1,          '$news_name','$news_description',7,'News','1.3',0,1,3)") || die("<strong>"._NOTUPDATED.$prefix."_modules</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_modules VALUES (7,'User',1,          '$user_name','$user_description',27,'User','0.3',1,1,3)") || die("<strong>"._NOTUPDATED.$prefix."_modules</strong>");
echo "<br /><span class=\"pn-sub\">".$prefix."_modules"._UPDATED."</span>";                               

// populate user_property table
$inst_dbconn->Execute("INSERT INTO ".$prefix."_user_property VALUES (1, '_UREALNAME', 0, 255, 1, NULL)") || die("<strong>"._NOTUPDATED.$prefix."_user_property</strong>");
$inst_dbconn->Execute("INSERT INTO ".$prefix."_user_property VALUES (2, '_UREALEMAIL', -1, 255, 2, NULL)") || die("<strong>"._NOTUPDATED.$prefix."_user_property</strong>");
$inst_dbconn->Execute("INSERT INTO ".$prefix."_user_property VALUES (3, '_UFAKEMAIL', 0, 255, 3, NULL)") || die("<strong>"._NOTUPDATED.$prefix."_user_property</strong>");
$inst_dbconn->Execute("INSERT INTO ".$prefix."_user_property VALUES (4, '_YOURHOMEPAGE', 0, 255, 4, NULL)") || die("<strong>"._NOTUPDATED.$prefix."_user_property</strong>");
$inst_dbconn->Execute("INSERT INTO ".$prefix."_user_property VALUES (5, '_TIMEZONEOFFSET', 0, 255, 5, NULL)") || die("<strong>"._NOTUPDATED.$prefix."_user_property</strong>");
$inst_dbconn->Execute("INSERT INTO ".$prefix."_user_property VALUES (6, '_YOURAVATAR', 0, 255, 6, NULL)") || die("<strong>"._NOTUPDATED.$prefix."_user_property</strong>");
$inst_dbconn->Execute("INSERT INTO ".$prefix."_user_property VALUES (7, '_YICQ', 0, 255, 7, NULL)") || die("<strong>"._NOTUPDATED.$prefix."_user_property</strong>");
$inst_dbconn->Execute("INSERT INTO ".$prefix."_user_property VALUES (8, '_YAIM', 0, 255, 8, NULL)") || die("<strong>"._NOTUPDATED.$prefix."_user_property</strong>");
$inst_dbconn->Execute("INSERT INTO ".$prefix."_user_property VALUES (9, '_YYIM', 0, 255, 9, NULL)") || die("<strong>"._NOTUPDATED.$prefix."_user_property</strong>");
$inst_dbconn->Execute("INSERT INTO ".$prefix."_user_property VALUES (10, '_YMSNM', 0, 255, 10, NULL)") || die("<strong>"._NOTUPDATED.$prefix."_user_property</strong>");
$inst_dbconn->Execute("INSERT INTO ".$prefix."_user_property VALUES (11, '_YLOCATION', 0, 255, 11, NULL)") || die("<strong>"._NOTUPDATED.$prefix."_user_property</strong>");
$inst_dbconn->Execute("INSERT INTO ".$prefix."_user_property VALUES (12, '_YOCCUPATION', 0, 255, 12, NULL)") || die("<strong>"._NOTUPDATED.$prefix."_user_property</strong>");
$inst_dbconn->Execute("INSERT INTO ".$prefix."_user_property VALUES (13, '_YINTERESTS', 0, 255, 13, NULL)") || die("<strong>"._NOTUPDATED.$prefix."_user_property</strong>");
$inst_dbconn->Execute("INSERT INTO ".$prefix."_user_property VALUES (14, '_SIGNATURE', 0, 255, 14, NULL)") || die("<strong>"._NOTUPDATED.$prefix."_user_property</strong>");
$inst_dbconn->Execute("INSERT INTO ".$prefix."_user_property VALUES (15, '_EXTRAINFO', 0, 255, 15, NULL)") || die("<strong>"._NOTUPDATED.$prefix."_user_property</strong>");
$inst_dbconn->Execute("INSERT INTO ".$prefix."_user_property VALUES (16, '_PASSWORD', -1, 255, 16, NULL)") || die("<strong>"._NOTUPDATED.$prefix."_user_property</strong>");
echo "<br /><span class=\"pn-sub\">".$prefix."_user_property"._UPDATED."</span>";

// populate the admin module
$system_category_name =               (defined('_ADMIN_CATEGORY_00_a')) ? ""._ADMIN_CATEGORY_00_a."" : 'System';
$system_category_description =        (defined('_ADMIN_CATEGORY_00_b')) ? ""._ADMIN_CATEGORY_00_b."" : 'System Modules';
$content_category_name =              (defined('_ADMIN_CATEGORY_01_a')) ? ""._ADMIN_CATEGORY_01_a."" : 'Content';
$content_category_description =       (defined('_ADMIN_CATEGORY_01_b')) ? ""._ADMIN_CATEGORY_01_b."" : 'Content Modules';
$resource_pack_category_name =        (defined('_ADMIN_CATEGORY_02_a')) ? ""._ADMIN_CATEGORY_02_a."" : 'Resource Pack';
$resource_pack_category_description = (defined('_ADMIN_CATEGORY_02_b')) ? ""._ADMIN_CATEGORY_02_b."" : 'Resource Pack Modules';
$utility_category_name =              (defined('_ADMIN_CATEGORY_03_a')) ? ""._ADMIN_CATEGORY_03_a."" : 'Utility';
$utility_category_description =       (defined('_ADMIN_CATEGORY_03_b')) ? ""._ADMIN_CATEGORY_03_b."" : 'Utility Modules';
$thirdparty_category_name =           (defined('_ADMIN_CATEGORY_04_a')) ? ""._ADMIN_CATEGORY_04_a."" : '3rd Party';
$thirdparty_category_description =    (defined('_ADMIN_CATEGORY_04_b')) ? ""._ADMIN_CATEGORY_04_b."" : 'Third Party Modules';

$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_admin_category VALUES ( '1', '$system_category_name', '$system_category_description')") or die ("<strong>"._NOTUPDATED.$prefix."_admin_category</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_admin_category VALUES ( '2', '$content_category_name', '$content_category_description')") or die ("<strong>"._NOTUPDATED.$prefix."_admin_category</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_admin_category VALUES ( '3', '$resource_pack_category_name', '$resource_pack_category_description')") or die ("<strong>"._NOTUPDATED.$prefix."_admin_category</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_admin_category VALUES ( '4', '$utility_category_name', '$utility_category_description')") or die ("<strong>"._NOTUPDATED.$prefix."_admin_category</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_admin_category VALUES ( '5', '$thirdparty_category_name', '$thirdparty_category_description')") or die ("<strong>"._NOTUPDATED.$prefix."_admin_category</strong>");
echo "<br /><span class=\"pn-sub\">".$prefix."_admin_category"._UPDATED."</span>";

$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_admin_module VALUES ( '1','1')") or die ("<strong>"._NOTUPDATED.$prefix."_admin_module</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_admin_module VALUES ( '2','1')") or die ("<strong>"._NOTUPDATED.$prefix."_admin_module</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_admin_module VALUES ( '3','1')") or die ("<strong>"._NOTUPDATED.$prefix."_admin_module</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_admin_module VALUES ( '4','1')") or die ("<strong>"._NOTUPDATED.$prefix."_admin_module</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_admin_module VALUES ( '5','1')") or die ("<strong>"._NOTUPDATED.$prefix."_admin_module</strong>");
$result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_admin_module VALUES ( '7','1')") or die ("<strong>"._NOTUPDATED.$prefix."_admin_module</strong>");
echo "<br /><span class=\"pn-sub\">".$prefix."_admin_modules"._UPDATED."</span>";

?>