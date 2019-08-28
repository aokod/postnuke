<?php
// File: $Id: global.php 20429 2006-11-07 19:53:57Z landseer $
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
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Everyone
// Purpose of file: Translation files
// Translation team: Read credits in /docs/CREDITS.txt
// ----------------------------------------------------------------------

define('_REGISTER_GLOBALS_ON', 'register_global is on - you should turn this off for enhanced security, for more information click <a href="http://php.net/manual/en/security.globals.php" title="more about register_globals">here</a>');
define('_REGISTER_GLOBALS_ON_HINT', 'Important note: PostNuke itself does not need register_globals=on, but some old modules might need this in order to work. You might consider to not use such modules.');

define('_ADMIN_EMAIL','Admin Email');
define('_ADMIN_LOGIN','Admin Login');
define('_ADMIN_NAME','Admin Name');
define('_ADMIN_PASS','Admin Password');
define('_ADMIN_REPEATPASS','Admin Password (verify)');
define('_ADMIN_URL','Admin URL');
define('_BTN_CHANGEINFO','Edit information');
define('_BTN_CONTINUE','Continue');
define('_BTN_FINISH','Finish');
define('_BTN_NEXT','Next');
define('_BTN_NEWINSTALL','New installation');
define('_BTN_RECHECK','Re-check');
define('_BTN_SET_LANGUAGE','Set Language');
define('_BTN_SET_LOGIN','Set Login');
define('_BTN_START','Start');
define('_BTN_SUBMIT','Submit');
define('_BTN_UPGRADE','Upgrade');
define('_CHANGE_INFO_0','Change Info');
define('_CHANGE_INFO_1','Please correct your database information.');
define('_CHMOD_CHECK_1','CHMOD Check');
define('_CHMOD_CHECK_2','We will first check to see that your file permissions are correct in order for the script to write to the file. If your settings are not correct, this script will not be able to encrypt your data in your config file. Encrypting the SQL data is added security, and is set by this script. You will also not be able to update your preferences from your admin once your site is up and running.');
define('_CHMOD_CHECK_3','File permissions for config.php are 666 -- correct, this script can write to the file');
define('_CHMOD_CHECK_4','Please change permissions on config.php to 666 so this script can write and encrypt the DB data (HINT: use "chmod")');
define('_CHMOD_CHECK_5','File permissions for config-old.php are 666 -- correct, this script can write to the file');
define('_CHMOD_CHECK_6','Please change permissions on config-old.php to 666 so this script can write and encrypt the DB data (HINT: use "chmod")');
define('_CHM_CHECK_1','Please enter your DB info. If you do not have root access to your DB (virtual hosting, etc), you will need to make your database before you proceed. A good rule of thumb, if you cannot create databases through phpMyAdmin because of virtual hosting, or security on mySQL, then this script will not be able to create the db for you. This script will still be able to fill the database, and will still need to be run.<br><br>If you do not know the values for the database host, username or password, leave them as their current defaults.  <br><br><b>PLEASE NOTE: Some hosts use 127.0.0.1 as the database host.  If you get an error "unable to connect to MySQL socket", try changing to 127.0.0.1 </b><br><br>If problems persist please contact your ISP who should be able to provide the information for you.');
define('_CONTINUE_1','Setting Your DB Preferences');
define('_CONTINUE_2','You can now set up your administrative account. If you pass on this set up, your login for the administrative account will be Admin / Password (case sensitive).  It is advisable to set it up now, and not wait until later.');
define('_DBHOST','Database Host');
define('_DBINFO','Database Information');
define('_DBNAME','Database Name');
define('_DBPASS','Database Password');
define('_DBPREFIX','Table Prefix (for Table Sharing)');
define('_DBTYPE','Database Type');
define('_DBTABLETYPE', 'Database Table Type');
define('_DBUNAME','Database Username');
define('_DEFAULT_1','This script will install the PostNuke database and help you set up the variables that you need to start. You will be taken through a variety of pages. Each page sets a different portion of the script. We estimate that this entire process will take about ten minutes. At any time that you get stuck, please visit our support forums for help.');
define('_DEFAULT_2','Our License');
define('_DEFAULT_3','Please read through the GNU General Public License. PostNuke is developed as free software, but there are certain requirements for distributing and editing.');
define('_DONE','Done.');
define('_FINISH_1','The Credits');
define('_FINISH_2','These are the scripts and people that make PostNuke go. Take some time and let these people know how much you appreciate their work. If you would like to be listed here, contact us about being a part of the developement team. We are always looking for some help.');
define('_FINISH_3','You are now done with the PostNuke installation. If you run into any problems, let us know.  Make sure that you delete this script. You will not need it again.');
define('_FINISH_4','Go to your PostNuke site');
define('_FOOTER_1','Thank you for trying PostNuke and welcome to our community.');
define('_FORUM_INFO_1','Your forum tables are untouched.<br><br>FYI, those tables are:');
define('_FORUM_INFO_2','So, you can delete those tables if you don\'t want to use forums.<br> phpBB should be available as a module from http://mods.postnuke.com');
define('_INPUT_DATA_1','Uploaded Data');
define('_INSTALLATION','PostNuke Installation');
define('_MADE',' made.');
define('_MAKE_DB_1','Unable to make database');
define('_MAKE_DB_2','has been created.');
define('_MAKE_DB_3','No database made.');
define('_MODIFY_FILE_1','Error: unable to open for read:');
define('_MODIFY_FILE_2','Error: unable to open for write:');
define('_MODIFY_FILE_3','0 lines changed, did nothing');
define('_MYPHPNUKE_1','Upgrading from MyPHPNuke 1.8.7?');
define('_MYPHPNUKE_2','Just press the <b>MyPHPNuke 1.8.7</b> button');
define('_MYPHPNUKE_3','Upgrading from MyPHPNuke 1.8.8b2?');
define('_MYPHPNUKE_4','Just press the <b>MyPHPNuke 1.8.8</b> button');
define('_NEWINSTALL','New Install');
define('_NEW_INSTALL_1','You have choosen to do a new install. Below is the information that you have entered.');
define('_NEW_INSTALL_2','There are two steps to getting a working PostNuke database. First an empty database is created, then it is populated.<br><br>If you have root access, check the <b>create the database</b> box and this script will create the empty database for you. Otherwise, just click on start.<br>If you do not have root access you need to create the empty database manually first.<br>Either way this script will then create the tables and populate your database for you.');
define('_NEW_INSTALL_3','Create the Database');
define('_NOTMADE','Unable to make ');
define('_NOTSELECT','Unable to select database.');
define('_NOTUPDATED','Unable to update ');
define('_PHPNUKE_1','Upgrading from PHP-Nuke 4.4?');
define('_PHPNUKE_10','Just press the <b>PHP-Nuke 5.3.1</b> button');
define('_PHPNUKE_11','Upgrading from PHP-Nuke 5.4?');
define('_PHPNUKE_12','Just press the <b>PHP-Nuke 5.4</b> button');
define('_PHPNUKE_2','Please read the following note, and press the <b>PHP-Nuke 4.4</b> button when ready.<br><br> This script will leave intact your forums DB but this version will not manage the data.<i> There is an upgrade script for this forum data that is being tested. It is currently held in the pn-modules CVS</i><br><br> We do not have PHPBB included into the release, but the upgrade script is the same. It will not destroy any of your data.');
define('_PHPNUKE_3','Upgrading from PHP-Nuke 5?');
define('_PHPNUKE_4','Just press the <b>PHP-Nuke 5</b> button');
define('_PHPNUKE_5','Upgrading from PHP-Nuke 5.2?');
define('_PHPNUKE_6','Just press the <b>PHP-Nuke 5.2</b> button');
define('_PHPNUKE_7','Upgrading from PHP-Nuke 5.3?');
define('_PHPNUKE_8','Just press the <b>PHP-Nuke 5.3</b> button');
define('_PHPNUKE_9','Upgrading from PHP-Nuke 5.3.1?');
define('_PHP_CHECK_1','Your PHP version is ');
define('_PHP_CHECK_2','You need to upgrade PHP to at least 4.1.0 - <a href=\'http://www.php.net\'>http://www.php.net</a>');
define('_PHP_CHECK_3','Not Good! magic_quotes_gpc is Off.<br>This can often be fixed using a .htaccess file with the following line:<br>php_flag magic_quotes_gpc On');
define('_PHP_CHECK_4','Not Good! magic_quotes_runtime is On.<br>This can often be fixed using a .htaccess file with the following line:<br>php_flag magic_quotes_runtime Off');
define('_PN6_1','Admin: You Will Need To Re-Save Your Website Settings In The Admin Page ASAP!');
define('_PN6_2','(We Are Sorry For This Inconvience)');
define('_PN6_3','ERROR: File not found: ');
define('_PN6_4','Done converting old-style button blocks.');
define('_PNTEMP_DIRNOTWRITABLE', 'Please change permissions on this directory to 777 so this script can write to this directory (HINT: use "chmod")');
define('_PNTEMP_DIRWRITABLE', 'correct, the script can write to this directory');
define('_POSTNUKE_1','Upgrading from PostNuke .5x?');
define('_POSTNUKE_10','Just press the <b>PostNuke .64</b> button');
define('_POSTNUKE_11','Upgrading from PostNuke .7?');
define('_POSTNUKE_12','Just press the <b>PostNuke 7</b> button');
define('_POSTNUKE_13','Upgrading from PostNuke .71?');
define('_POSTNUKE_14','Just press the <b>PostNuke 71</b> button');
define('_POSTNUKE_15','To confirm your system language?');
define('_POSTNUKE_16','Just press the <b>Validate</b> button');
define('_POSTNUKE_17','Validate your table structure?');
define('_POSTNUKE_18','Just press the <b>Validate</b> button');
# added for 0.7.2.2 Neo
define('_POSTNUKE_19','Upgrading from PostNuke .72?');
define('_POSTNUKE_20','Just press the <b>PostNuke 72</b> button');
define('_POSTNUKE_2','Just press the <b>PostNuke .5</b> button');
define('_POSTNUKE_3','Upgrading from PostNuke .6 / .61?');
define('_POSTNUKE_4','Just press the <b>PostNuke .6</b> button');
define('_POSTNUKE_5','Upgrading from PostNuke .62?');
define('_POSTNUKE_6','Just press the <b>PostNuke .62</b> button');
define('_POSTNUKE_7','Upgrading from PostNuke .63?');
define('_POSTNUKE_8','Just press the <b>PostNuke .63</b> button<br>');
define('_POSTNUKE_9','Upgrading from PostNuke .64?');
define('_PWBADMATCH','The passwords supplied do not match.  Please go back and re-type the passwords to ensure they are the same.');
define('_QUOTESCHECK_1','NS-Quotes Check');
define('_QUOTESCHECK_2','The Former NS-Quotes module has been deprecated in favor of the new Quotes module.<br> Please remove the modules/NS-Quotes directory.');
define('_SELECT_LANGUAGE_1','Select your language.');
define('_SELECT_LANGUAGE_2','Language: ');
define('_SHOW_ERROR_INFO_1','Write error</b> unable to update your \'config.php\' file<br/> You will have to modify this file yourself using a text editor.<br/> Here are the changes required:');
define('_SKIPPED','Skipped.');
define('_SUBMIT_1','Please, look over the information and make sure that it is correct.');
define('_SUBMIT_2','You have entered the following information:');
define('_SUBMIT_3','Select <b>New Install</b> or <b>Upgrade</b> to continue.');
define('_SUCCESS_1','Finished');
define('_SUCCESS_2','Your upgrade to the latest version of PostNuke is finished.<br> Remember to change your config.php settings before using for the first time.');
define('_UPDATED',' updated.');
define('_UPDATING','Updating table: ');
define('_UPGRADETAKESALONGTIME','Carrying out a PostNuke upgrade can take a long time, maybe a matter of minutes.  When selecting an upgrade option please select the option only once, and wait for the next screen to appear.  Clicking on upgrade options multiple times can cause the upgrade process to fail');
define('_UPGRADE_1','Upgrades');
define('_UPGRADE_2','Here is where you can select which CMS your are upgrading from.<br><br><center> Select <b>PHP-Nuke</b> to upgrade an existing PHP-Nuke install.<br> Select <b>PostNuke</b> to upgrade an existing PostNuke install.<br> Select <b>MyPHPNuke</b> to upgrade an exisitng MyPHPNuke install.');
define('_VERSION_WARNING','NOTE: Official PostNuke distributions are ONLY available from <a href="http://download.postnuke.com/" target="_blank">PostNuke.com</a>.<br>For certitude of quality, please ensure you are installing an official distribution.');

define('_INSTALLGUIDEREF1', 'Please refer to the');
define('_INSTALLGUIDEREF2', 'installation guide');
define('_INSTALLGUIDEREF3', 'during this process');

/* pn0.76 */
define('_ERROR', 'Error:');
define('_INSTALLED', 'successfully installed');
define('_NOTINIT', 'not initialised');
define('_NOTACTIVATED', 'not activated');
define('_NOTLOCALIZED', 'not localised');
define('_NOTCATEGORISED', 'not categorised');
define('_INSTALL_ANONYMOUS','Anonymous');
define('_INSTALL_BACKENDLANG','en-us');
define('_INSTALL_CENSORLIST','fuck,cunt,fucker,fucking,pussy,cock,c0ck,cum,twat,clit,bitch,fuk,fuking,motherfucker');
define('_INSTALL_ILLEGALNAMES','root adm linux webmaster admin god administrator administrador nobody anonymous anonimo');
define('_INSTALL_METAKEYWORDS','nuke, postnuke, free, community, php, portal, opensource, open source, gpl, mysql, sql, database, web site, website, weblog, content management, contentmanagement, web content management, webcontentmanagement');
define('_INSTALL_NOTIFYFRM','webmaster');
define('_INSTALL_NOTIFYMAIL','me@yoursite.com');
define('_INSTALL_NOTIFYMSG','Hey! You got a new submission for your site.');
define('_INSTALL_NOTIFYSBJ','NEWS for my site');
define('_INSTALL_PNPOWERED','PostNuke Powered Site');
define('_INSTALL_REASONS','As Is,Offtopic,Flamebait,Troll,Redundant,Insightful,Interesting,Informative,Funny,Overrated,Underrated');
define('_INSTALL_REGDISABLED','Sorry, registration is disabled at this time.');
define('_INSTALL_YOURSITENAME','Your Site Name');
define('_INSTALL_YOURSLOGAN','Your slogan here');
define('_FOOTMSGTEXT','<a href="http://www.postnuke.com"><img src="images/powered/postnuke.butn.gif" alt="Web site powered by PostNuke" /></a> <a href="http://adodb.sourceforge.net"><img src="images/powered/adodb2.gif" alt="ADODB database library" /></a> <a href="http://www.php.net"><img src="images/powered/php4_powered.gif" alt="PHP Language" /></a><p>All logos and trademarks in this site are property of their respective owner. The comments are property of their posters, all the rest (c) '.date("Y").' by me<br />This web site was made with <a href="http://www.postnuke.com">PostNuke</a>, a web portal system written in PHP. PostNuke is Free Software released under the <a href="http://www.gnu.org">GNU/GPL license</a>.</p>You can syndicate our news using the file <a href="backend.php">backend.php</a>');

/* Modules Descriptions */
define('_MODULE', 'Module');
define('_MODDESC_ADDSTORY','Add a story');
define('_MODDESC_ADMIN','Administration');
define('_MODDESC_ADMMESSAGES','Display automated/programmed messages.');
define('_MODDESC_AUTOLINKS','Automatically link keywords');
define('_MODDESC_AVANTGO','News for your PDA');
define('_MODDESC_BANNERS','Banners');
define('_MODDESC_BLOCKS','Administration of side and centre blocks');
define('_MODDESC_CENSOR','Site Censorship Control');
define('_MODDESC_COMMENTS','Comment on articles');
define('_MODDESC_CREDITS','Display Module credits, license, help and contact information');
define('_MODDESC_DOWNLOADS','Files to download');
define('_MODDESC_EPHEM','Daily events');
define('_MODDESC_FAQ','Frequently Asked Questions');
define('_MODDESC_GROUPS','Modify groups');
define('_MODDESC_HEADFOOT','Postnuke Page Header and Footer');
define('_MODDESC_LANGUAGES','List languages by their ISO Key and name');
define('_MODDESC_LEGAL','Generic Privacy Statement and Terms of Use');
define('_MODDESC_LOSTPASS','Retrieve lost password of a user.');
define('_MODDESC_MAILER','Postnuke Mailer');
define('_MODDESC_MAILUSERS','Mail all/individual users on your site.');
define('_MODDESC_MBLIST','Information on users of this site');
define('_MODDESC_MESSAGES','Private messages to users of this site');
define('_MODDESC_MODULES','Enable/disable modules, view install/docs/credits.');
define('_MODDESC_MULTISITES','Create multiple sites using the same PN installation files');
define('_MODDESC_NEWS','News items');
define('_MODDESC_NEWUSER','New User for postnuke.');
define('_MODDESC_PERMISSIONS','Configure permissions');
define('_MODDESC_PNRENDER','The Smarty implementation for PostNuke');
define('_MODDESC_POLLS','Polls and surveys');
define('_MODDESC_QUOTES','Quotes and sayings');
define('_MODDESC_RATINGS','Ratings utility');
define('_MODDESC_RECOMMENDUS','Recommend us to a friend');
define('_MODDESC_REFERERS','Referers');
define('_MODDESC_REVIEWS','Reviews');
define('_MODDESC_SEARCH','Search this site');
define('_MODDESC_SECTIONS','Sections');
define('_MODDESC_SETTINGS','Settings');
define('_MODDESC_STATS','Site statistics');
define('_MODDESC_SUBMITNEWS','Contribute a story');
define('_MODDESC_TOPICS','Article topics');
define('_MODDESC_TOPLIST','Top 10 listings');
define('_MODDESC_TYPETOOL','TypeTool Visual Editor Implementation');
define('_MODDESC_USER','User Administration');
define('_MODDESC_WEBLINKS','Links to other sites');
define('_MODDESC_WIKI','Wiki encoding');
define('_MODDESC_XMLRPC','XML-RPC utility module');
define('_MODDESC_YOURACCOUNT','User options');
define('_MODDESC_XANTHIA','Xanthia Theme Engine');
define('_MODDESC_PNBBSMILE','Smilie Hook');
define('_MODDESC_RSS','Syndicate other sites content as RSS-News');

define('_MODNAME_ADDSTORY','AddStory');
define('_MODNAME_ADMIN','Administration');
define('_MODNAME_ADMMESSAGES','Admin_Messages');
define('_MODNAME_AUTOLINKS','Autolinks');
define('_MODNAME_AVANTGO','AvantGo');
define('_MODNAME_BANNERS','Banners');
define('_MODNAME_BLOCKS','Blocks');
define('_MODNAME_CENSOR','Censor');
define('_MODNAME_COMMENTS','Comments');
define('_MODNAME_CREDITS','Credits');
define('_MODNAME_DOWNLOADS','Downloads');
define('_MODNAME_EPHEM','Ephemerids');
define('_MODNAME_FAQ','FAQ');
define('_MODNAME_GROUPS','Groups');
define('_MODNAME_HEADFOOT','Header_Footer');
define('_MODNAME_LANGUAGES','Languages');
define('_MODNAME_LEGAL','Legal');
define('_MODNAME_LOSTPASS','LostPassword');
define('_MODNAME_MAILER','Mailer');
define('_MODNAME_MAILUSERS','MailUsers');
define('_MODNAME_MBLIST','Members_List');
define('_MODNAME_MESSAGES','Messages');
define('_MODNAME_MODULES','Modules');
define('_MODNAME_MULTISITES','Multisites');
define('_MODNAME_NEWS','News');
define('_MODNAME_NEWUSER','NewUser');
define('_MODNAME_PERMISSIONS','Permissions');
define('_MODNAME_PNRENDER','pnRender');
define('_MODNAME_POLLS','Polls');
define('_MODNAME_QUOTES','Quotes');
define('_MODNAME_RATINGS','Ratings');
define('_MODNAME_RECOMMENDUS','RecommendUs');
define('_MODNAME_REFERERS','Referers');
define('_MODNAME_REVIEWS','Reviews');
define('_MODNAME_SEARCH','Search');
define('_MODNAME_SECTIONS','Sections');
define('_MODNAME_SETTINGS','Settings');
define('_MODNAME_STATS','Stats');
define('_MODNAME_SUBMITNEWS','Submit_News');
define('_MODNAME_TOPICS','Topics');
define('_MODNAME_TOPLIST','Top_List');
define('_MODNAME_TYPETOOL','TypeTool');
define('_MODNAME_USER','User');
define('_MODNAME_WEBLINKS','Web_Links');
define('_MODNAME_WIKI','Wiki');
define('_MODNAME_XMLRPC','xmlrpc');
define('_MODNAME_YOURACCOUNT','Your_Account');
define('_MODNAME_XANTHIA','Xanthia');
define('_MODNAME_PNBBSMILE','pn_bbsmile');
define('_MODNAME_RSS','RSS');

/* admin module default categories */
define('_ADMIN_CATEGORY_00_a',  'System');
define('_ADMIN_CATEGORY_00_b',  'System Modules');
define('_ADMIN_CATEGORY_01_a',  'Content');
define('_ADMIN_CATEGORY_01_b',  'Content Modules');
define('_ADMIN_CATEGORY_02_a',  'Resource Pack');
define('_ADMIN_CATEGORY_02_b',  'Resource Pack Modules');
define('_ADMIN_CATEGORY_03_a',  'Utility');
define('_ADMIN_CATEGORY_03_b',  'Utility Modules');
define('_ADMIN_CATEGORY_04_a',  '3rd Party');
define('_ADMIN_CATEGORY_04_b',  'Third Party Modules');

/* init - Reviews */
define('_REVIEWSMAINDESC','Reviews Section Long Description');
define('_REVIEWSMAINTITLE','Reviews Section Title');

?>