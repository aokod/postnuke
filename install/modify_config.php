<?php
// File: $Id: modify_config.php 20321 2006-10-17 11:56:35Z larsneo $
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
// Original Author of file: Scott Kirkwood (scott_kirkwood@bigfoot.com)
// Purpose of file: Routines to modify the config.php file.
// General routine modify_file() is useful in it's own right.
// ----------------------------------------------------------------------
// This is the last update to this script before the new version is finished.
// mod_file is general, give it a source file a destination.
// an array of search patterns (Perl style) and replacement patterns
// Returns a string which starts with "Err" if there's an error
function modify_file($src, $dest, $reg_src, $reg_rep)
{
    $in = @fopen($src, "r");
    if (! $in) {
        return _MODIFY_FILE_1 . " $src";
    }
    $i = 0;
    while (!feof($in)) {
        $file_buff1[$i++] = fgets($in, 4096);
    }
    fclose($in);

    $lines = 0; // Keep track of the number of lines changed

    while (list ($bline_num, $buffer) = each ($file_buff1)) {
        $new = preg_replace($reg_src, $reg_rep, $buffer);
        if ($new != $buffer) {
            $lines++;
        }
        $file_buff2[$bline_num] = $new;
    }

    if ($lines == 0) {
        // Skip the rest - no lines changed
        return _MODIFY_FILE_3;
    }

    reset($file_buff1);
    $out_backup = @fopen($dest, "w");

    if (! $out_backup) {
        return _MODIFY_FILE_2 . " $dest";
    } while (list ($bline_num, $buffer) = each ($file_buff1)) {
        fputs($out_backup, $buffer);
    }

    fclose($out_backup);

    reset($file_buff2);
    $out_original = fopen($src, "w");
    if (! $out_original) {
        return _MODIFY_FILE_2 . " $src";
    } while (list ($bline_num, $buffer) = each ($file_buff2)) {
        fputs($out_original, $buffer);
    }

    fclose($out_original);
    // Success!
    return "$src updated with $lines lines of changes, backup is called $dest";
}
// Two global arrays
$reg_src = array();
$reg_rep = array();
// Setup various searches and replaces
// Scott Kirkwood
function add_src_rep($key, $rep)
{
    global $reg_src, $reg_rep;
    // Note: /x is to permit spaces in regular expressions
    // Great for making the reg expressions easier to read
    // Ex: $pnconfig['sitename'] = stripslashes("Your Site Name");
    $reg_src[] = "/ \['$key'\] \s* = \s* stripslashes\( (\' | \") (.*) \\1 \); /x";
    $reg_rep[] = "['$key'] = stripslashes(\\1$rep\\1);";
    // Ex. $pnconfig['site_logo'] = "logo.gif";
    $reg_src[] = "/ \['$key'\] \s* = \s* (\' | \") (.*) \\1 ; /x";
    $reg_rep[] = "['$key'] = '$rep';";
    // Ex. $pnconfig['pollcomm'] = 1;
    $reg_src[] = "/ \['$key'\] \s* = \s* (\d*\.?\d*) ; /x";
    $reg_rep[] = "['$key'] = $rep;";
}

function show_error_info()
{
    global $dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype, $dbtabletype;

    echo "<br/><br/><b>" . _SHOW_ERROR_INFO_1 . "<br/>";
  echo <<< EOT
        <tt>
        \$pnconfig['dbtype'] = '$dbtype';<br/>
        \$pnconfig['dbtabletype'] = '$dbtabletype';<br/>
        \$pnconfig['dbhost']  = '$dbhost';<br/>
        \$pnconfig['dbuname'] = '$dbuname';<br/>
        \$pnconfig['dbpass'] = '$dbpass';<br/>
        \$pnconfig['dbname'] = '$dbname';<br/>
        \$pnconfig['prefix'] = '$prefix';<br/>
        </tt>
EOT;
}
// Update the config.php file with the database information.
function update_config_php($db_prefs = false)
{
    global $reg_src, $reg_rep;
    global $dbhost, $dbuname, $dbpass, $dbname, $prefix, $dbtype, $dbtabletype;
    global $email, $url, $HTTP_ENV_VARS;

    add_src_rep("dbhost", $dbhost);
    add_src_rep("dbuname", base64_encode($dbuname));
    add_src_rep("dbpass", base64_encode($dbpass));
    add_src_rep("dbname", $dbname);
    add_src_rep("prefix", $prefix);
    add_src_rep("dbtype", $dbtype);
    add_src_rep("dbtabletype", $dbtabletype);
    if (@strstr($HTTP_ENV_VARS["OS"], "Win")) {
        add_src_rep("system" , '1');
    } else {
        add_src_rep("system", '0');
    }
    add_src_rep("encoded", '1');

    if ($email) {
        add_src_rep("adminmail", $email);
    }

    $ret = modify_file("config.php", "config-old.php", $reg_src, $reg_rep);

    if (preg_match("/Error/", $ret)) {
        show_error_info();
    }
}

function start_postnuke($name,$pwd)
{
    include_once 'includes/pnAPI.php';
    pnInit();
    // login
    return pnUserLogIn($name, $pwd, false);
}

function close_postnuke()
{
    // logout
    pnUserLogOut();
}

// init modules list
function install_modules()
{
    // regenerate modules list
    pnModAPIFunc('Modules', 'admin', 'regenerate');

    // install some modules
    $modulestoinstall = array(
                            array('name' => 'Settings', 'displayname' => _MODNAME_SETTINGS, 'description' => _MODDESC_SETTINGS, 'category' => 1),
                            array('name' => 'pnRender', 'displayname' => _MODNAME_PNRENDER, 'description' => _MODDESC_PNRENDER, 'category' => 1),
                            array('name' => 'Xanthia', 'displayname' => _MODNAME_XANTHIA, 'description' => _MODDESC_XANTHIA, 'category' => 1),
                            array('name' => 'Admin_Messages', 'displayname' => _MODNAME_ADMMESSAGES, 'description' => _MODDESC_ADMMESSAGES, 'category' => 1),
                            array('name' => 'Web_Links', 'displayname' => _MODNAME_WEBLINKS, 'description' => _MODDESC_WEBLINKS, 'category' => 2),
                            array('name' => 'Topics', 'displayname' => _MODNAME_TOPICS, 'description' => _MODDESC_TOPICS, 'category' => 2),
                            array('name' => 'Sections', 'displayname' => _MODNAME_SECTIONS, 'description' => _MODDESC_SECTIONS, 'category' => 2),
                            array('name' => 'Reviews', 'displayname' => _MODNAME_REVIEWS, 'description' => _MODDESC_REVIEWS, 'category' => 2),
                            array('name' => 'Downloads', 'displayname' => _MODNAME_DOWNLOADS, 'description' => _MODDESC_DOWNLOADS, 'category' => 2),
                            array('name' => 'Mailer', 'displayname' => _MODNAME_MAILER, 'description' => _MODDESC_MAILER, 'category' => 1),
                            array('name' => 'FAQ', 'displayname' => _MODNAME_FAQ, 'description' => _MODDESC_FAQ, 'category' => 2),
                            array('name' => 'Comments', 'displayname' => _MODNAME_COMMENTS, 'description' => _MODDESC_COMMENTS, 'category' => 2),
                            array('name' => 'Header_Footer', 'displayname' => _MODNAME_HEADFOOT, 'description' => _MODDESC_HEADFOOT, 'category' => -1),
                            array('name' => 'Stats', 'displayname' => _MODNAME_STATS, 'description' => _MODDESC_STATS, 'category' => -1),
//                            array('name' => 'Banners', 'displayname' => _MODNAME_BANNERS, 'description' => _MODDESC_BANNERS, 'category' => 4),
                            array('name' => 'Polls', 'displayname' => _MODNAME_POLLS, 'description' => _MODDESC_POLLS, 'category' => 2),
                            array('name' => 'legal', 'displayname' => _MODNAME_LEGAL, 'description' => _MODDESC_LEGAL, 'category' => 2),
                            array('name' => 'Credits', 'displayname' => _MODNAME_CREDITS, 'description' => _MODDESC_CREDITS, 'category' => -1),
                            array('name' => 'Referers', 'displayname' => _MODNAME_REFERERS, 'description' => _MODDESC_REFERERS, 'category' => 4),
                            array('name' => 'MailUsers', 'displayname' => _MODNAME_MAILUSERS, 'description' => _MODDESC_MAILUSERS, 'category' => 4),
                            array('name' => 'AddStory', 'displayname' => _MODNAME_ADDSTORY, 'description' => _MODDESC_ADDSTORY, 'category' => 2),
                            array('name' => 'Submit_News', 'displayname' => _MODNAME_SUBMITNEWS, 'description' => _MODDESC_SUBMITNEWS, 'category' => 2),
                            array('name' => 'LostPassword', 'displayname' => _MODNAME_LOSTPASS, 'description' => _MODDESC_LOSTPASS, 'category' => -1),
                            array('name' => 'Search', 'displayname' => _MODNAME_SEARCH, 'description' => _MODDESC_SEARCH, 'category' => -1),
//                            array('name' => 'xmlrpc', 'displayname' => _MODNAME_XMLRPC, 'description' => _MODDESC_XMLRPC, 'category' => -1),
                            array('name' => 'NewUser', 'displayname' => _MODNAME_NEWUSER, 'description' => _MODDESC_NEWUSER, 'category' => -1),
                            array('name' => 'Your_Account', 'displayname' => _MODNAME_YOURACCOUNT, 'description' => _MODDESC_YOURACCOUNT, 'category' => -1),
                            );

    foreach($modulestoinstall as $moduletoinstall) {
        $mid = pnModGetIDFromName($moduletoinstall['name']);
        // init it
        if(pnModAPIFunc('Modules', 'admin', 'initialise', array('mid' => $mid))==true) {
            // activate it
            if(pnModAPIFunc('Modules', 'admin', 'setState', array('mid' => $mid, 'state' => _PNMODULE_STATE_ACTIVE))==true) {
                // localize the module name and description if available
                if( (substr($moduletoinstall['displayname'],0,8)<>'_MODNAME_') && (substr($moduletoinstall['description'],0,8)<>'_MODDESC_') ) {
                    // localized strings available
                    if(pnModAPIFunc('Modules', 'admin', 'update', array('mid' => $mid,'displayname' => $moduletoinstall['displayname'], 'description' => $moduletoinstall['description']))==false) {
                        // error when localising
                        echo "<br>" . _ERROR . " " . $moduletoinstall[name] . " " . _NOTLOCALISED;
                        exit;
                    }
                }
                // set the admin category
                if(($moduletoinstall['category'] <> -1) && (pnModAPIFunc('Admin', 'admin', 'addmodtocategory', array('module' => $moduletoinstall['name'], 'category' => $moduletoinstall['category']))==false)) {
                    // error when setting category
                    echo "<br>" . _ERROR . " " . $moduletoinstall[name] . " " . _NOTCATEGORISED;
                    exit;
                } else {
                    echo "<br /><span class=\"pn-sub\">".$moduletoinstall['name']. " " . _INSTALLED."</span>";
                }


            } else {
                // error when activating
                echo "<br>" . _ERROR . " " . $moduletoinstall[name] . " " . _NOTACTIVATED;
                exit;
            }
        } else {
            // error on init
            echo "<br>" . _ERROR . " " . $moduletoinstall[name] . " " . _NOTINIT;
            exit;
        }
    }
    return;
}

function set_config_vars($currentlang)
{
    // reasons
    $reasons = explode(',', _INSTALL_REASONS);

    // censorlist
    $wordlist = explode(',', _INSTALL_CENSORLIST);

    // footermessage
    $footmsg = (defined('_FOOTMSGTEXT')) ? ""._FOOTMSGTEXT."" : '<a href="http://www.postnuke.com"><img src="images/powered/postnuke.butn.gif" alt="Web site powered by PostNuke" /></a> <a href="http://adodb.sourceforge.net"><img src="images/powered/adodb2.gif" alt="ADODB database library" /></a> <a href="http://www.php.net"><img src="images/powered/php4_powered.gif" alt="PHP Language" /></a><p>All logos and trademarks in this site are property of their respective owner. The comments are property of their posters, all the rest (c) 2004 by me<br />This web site was made with <a href="http://www.postnuke.com">PostNuke</a>, a web portal system written in PHP. PostNuke is Free Software released under the <a href="http://www.gnu.org">GNU/GPL license</a>.</p>You can syndicate our news using the file <a href="backend.php">backend.php</a>';

    $allowablehtml = array('!--' => 2,
                           'a' => 2,
                           'abbr' => 0,
                           'acronym' => 0,
                           'address' => 0,
                           'applet' => 0,
                           'area' => 0,
                           'b' => 1,
                           'base' => 0,
                           'basefont' => 0,
                           'bdo' => 0,
                           'big' => 0,
                           'blockquote' => 0,
                           'br' => 1,
                           'button' => 0,
                           'caption' => 0,
                           'center' => 0,
                           'cite' => 0,
                           'code' => 0,
                           'col' => 0,
                           'colgroup' => 0,
                           'del' => 0,
                           'dfn' => 0,
                           'dir' => 0,
                           'div' => 0,
                           'dl' => 0,
                           'dd' => 0,
                           'dt' => 0,
                           'em' => 1,
                           'embed' => 0,
                           'fieldset' => 0,
                           'font' => 0,
                           'form' => 0,
                           'h1' => 0,
                           'h2' => 0,
                           'h3' => 0,
                           'h4' => 0,
                           'h5' => 0,
                           'h6' => 0,
                           'hr' => 1,
                           'i' => 1,
                           'iframe' => 0  ,
                           'img' => 0,
                           'input' => 0,
                           'ins' => 0,
                           'kbd' => 0,
                           'label' => 0,
                           'legend' => 0,
                           'li' => 1,
                           'map' => 0,
                           'marquee' => 0,
                           'menu' => 0,
                           'nobr' => 0,
                           'object' => 0,
                           'ol' => 1,
                           'optgroup' => 0,
                           'option' => 0 ,
                           'p' => 1,
                           'param' => 0,
                           'pre' => 1,
                           'q' => 0,
                           's' => 0,
                           'samp' => 0  ,
                           'script' => 0,
                           'select' => 0,
                           'small' => 0,
                           'span' => 0,
                           'strike' => 0,
                           'strong' => 1,
                           'sub' => 0,
                           'sup' => 0,
                           'table' => 2,
                           'tbody' => 0,
                           'td' => 2,
                           'textarea' => 0,
                           'tfoot' => 0,
                           'th' => 2,
                           'thead' => 0,
                           'tr' => 2,
                           'tt' => 1,
                           'u' => 0,
                           'ul' => 1,
                           'var' => 0);
    global $email;
    if ($email) {
        pnConfigSetVar('adminmail',$email);
    } else {
        pnConfigSetVar('adminmail','postnuke@example.com');
    }
    pnConfigSetVar('debug', 0);
    pnConfigSetVar('sitename', _INSTALL_YOURSITENAME);
    pnConfigSetVar('site_logo','logo.gif');
    pnConfigSetVar('slogan', _INSTALL_YOURSLOGAN);
    pnConfigSetVar('metakeywords', _INSTALL_METAKEYWORDS);
    pnConfigSetVar('dyn_keywords', 0);
    pnConfigSetVar('startdate', date("m.Y", time()));
    pnConfigSetVar('Default_Theme','ExtraLite');
    pnConfigSetVar('foot1', _FOOTMSGTEXT);
    pnConfigSetVar('commentlimit', 4096);
    pnConfigSetVar('anonymous', _INSTALL_ANONYMOUS);
    pnConfigSetVar('timezone_offset', 12);
    pnConfigSetVar('nobox', 0);
    pnConfigSetVar('funtext', 0);
    pnConfigSetVar('reportlevel', 0);
    pnConfigSetVar('startpage', 'News');
    pnConfigSetVar('admingraphic', 1);
    pnConfigSetVar('admart', 20);
    pnConfigSetVar('backend_title', _INSTALL_PNPOWERED);
    pnConfigSetVar('backend_language', _INSTALL_BACKENDLANG);
    pnConfigSetVar('seclevel','Medium');
    pnConfigSetVar('secmeddays', 7);
    pnConfigSetVar('secinactivemins', 10);
    pnConfigSetVar('Version_Num', '0.7.6.2');
    pnConfigSetVar('Version_ID', 'PostNuke');
    pnConfigSetVar('Version_Sub','Phoenix');
    pnConfigSetVar('debug_sql', 0);
    pnConfigSetVar('anonpost', 0);
    pnConfigSetVar('minpass', 5);
    pnConfigSetVar('pollcomm', 1);
    pnConfigSetVar('minage', 13);
    pnConfigSetVar('top', 10);
    pnConfigSetVar('storyhome', 10);
    pnConfigSetVar('banners', 0);
    pnConfigSetVar('myIP','192.168.123.254');
    pnConfigSetVar('language', $currentlang);
    pnConfigSetVar('anonymoussessions', '1');
    pnConfigSetVar('multilingual', 1);
    pnConfigSetVar('useflags', 0);
    pnConfigSetVar('language_detect', 1);
    pnConfigSetVar('perpage', 10);
    pnConfigSetVar('popular', 500);
    pnConfigSetVar('newlinks', 10);
    pnConfigSetVar('toplinks', 25);
    pnConfigSetVar('linksresults', 10);
    pnConfigSetVar('links_anonaddlinklock', 0);
    pnConfigSetVar('anonwaitdays', 1);
    pnConfigSetVar('outsidewaitdays', 1);
    pnConfigSetVar('useoutsidevoting', 1);
    pnConfigSetVar('anonweight', 10);
    pnConfigSetVar('outsideweight', 20);
    pnConfigSetVar('detailvotedecimal', 2);
    pnConfigSetVar('mainvotedecimal', 1);
    pnConfigSetVar('toplinkspercentrigger', 0);
    pnConfigSetVar('mostpoplinkspercentrigger', 0);
    pnConfigSetVar('mostpoplinks', 25);
    pnConfigSetVar('featurebox', 1);
    pnConfigSetVar('linkvotemin', 5);
    pnConfigSetVar('blockunregmodify', 0);
    pnConfigSetVar('newdownloads', 10);
    pnConfigSetVar('topdownloads', 25);
    pnConfigSetVar('downloadsresults', 10);
    pnConfigSetVar('downloads_anonadddownloadlock', 1);
    pnConfigSetVar('topdownloadspercentrigger', 0);
    pnConfigSetVar('mostpopdownloadspercentrigger', 0);
    pnConfigSetVar('mostpopdownloads', 25);
    pnConfigSetVar('downloadvotemin', 5);
    pnConfigSetVar('notify', 0);
    pnConfigSetVar('notify_email', _INSTALL_NOTIFYMAIL);
    pnConfigSetVar('notify_subject', _INSTALL_NOTIFYSBJ);
    pnConfigSetVar('notify_message', _INSTALL_NOTIFYMSG);
    pnConfigSetVar('notify_from', _INSTALL_NOTIFYFRM);
    pnConfigSetVar('moderate', 1);
    pnConfigSetVar('BarScale', 1);
    pnConfigSetVar('tipath', 'images/topics/');
    pnConfigSetVar('userimg', 'images/menu');
    pnConfigSetVar('usergraphic', 1);
    pnConfigSetVar('topicsinrow', 5);
    pnConfigSetVar('httpref', 1);
    pnConfigSetVar('httprefmax', 1000);
    pnConfigSetVar('reasons', $reasons);
    pnConfigSetVar('AllowableHTML', $allowablehtml);
    pnConfigSetVar('CensorList', $wordlist);
    pnConfigSetVar('CensorMode', 1);
    pnConfigSetVar('CensorReplace', '*****');
    pnConfigSetVar('theme_change', 0);
    pnConfigSetVar('htmlentities', '1');
    pnConfigSetVar('UseCompression', 0);
    pnConfigSetVar('refereronprint', 0);
    pnConfigSetVar('storyorder', '1');
    pnConfigSetVar('pnAntiCracker', '1');
    pnConfigSetVar('safehtml', '1');
    pnConfigSetVar('idnnames', 0);
    pnConfigSetVar('reg_allowreg', '1');
    pnConfigSetVar('reg_verifyemail', '1');
    pnConfigSetVar('reg_Illegalusername', _INSTALL_ILLEGALNAMES);
    pnConfigSetVar('reg_noregreasons', _INSTALL_REGDISABLED);
    pnConfigGetVar('reg_uniemail', '1');
    pnConfigSetVar('loadlegacy', 0);
    pnConfigSetVar('newspager', 0);
    pnConfigSetVar('siteoff', 0);
}

function insert_basic_data($prefix)
{
    global $inst_dbconn;

    // title of blocks
    $incoming_block_title = (defined('_BLOCKTITLE_INCOMING')) ? ""._BLOCKTITLE_INCOMING."" : 'Incoming';
    $whoisonline_block_title = (defined('_BLOCKTITLE_WHOISONLINE')) ? ""._BLOCKTITLE_WHOISONLINE."" : 'Online';
    $otherstories_block_title = (defined('_BLOCKTITLE_OTHERSTORIES')) ? ""._BLOCKTITLE_OTHERSTORIES."" : 'Other Stories';
    $usersblock_block_title = (defined('_BLOCKTITLE_USERSBLOCK')) ? ""._BLOCKTITLE_USERSBLOCK."" : 'Users Block';
    $searchbox_block_title = (defined('_BLOCKTITLE_SEARCHBOX')) ? ""._BLOCKTITLE_SEARCHBOX."" : 'Search Box';
    $languages_block_title = (defined('_BLOCKTITLE_LANGUAGES')) ? ""._BLOCKTITLE_LANGUAGES."" : 'Languages';
    $catmenu_block_title = (defined('_BLOCKTITLE_CATMENU')) ? ""._BLOCKTITLE_CATMENU."" : 'Categories Menu';
    $ranhead_block_title = (defined('_BLOCKTITLE_RANHEAD')) ? ""._BLOCKTITLE_RANHEAD."" : 'Random Headlines';
    $poll_block_title = (defined('_BLOCKTITLE_POLL')) ? ""._BLOCKTITLE_POLL."" : 'Poll';
    $bigstory_block_title = (defined('_BLOCKTITLE_BIGSTORY')) ? ""._BLOCKTITLE_BIGSTORY."" : 'Todays Big Story';
    $userslogin_block_title = (defined('_BLOCKTITLE_USERSLOGIN')) ? ""._BLOCKTITLE_USERSLOGIN."" : 'Login';
    $pastart_block_title = (defined('_BLOCKTITLE_PASTART')) ? ""._BLOCKTITLE_PASTART."" : 'Past Articles';
    $adminmess_block_title = (defined('_BLOCKTITLE_ADMINMESS')) ? ""._BLOCKTITLE_ADMINMESS."" : 'Administration Messages';
    $usersblocktexte_block_title = (defined('_BLOCKTITLE_USERSBLOCK_TEXTE')) ? ""._BLOCKTITLE_USERSBLOCK_TEXTE."" : 'Put anything you want here';

    // main menu
    // note: this is also used for setting up the permission table!
    $mainblock_block_title = (defined('_BLOCKTITLE_MAINMENU')) ? ""._BLOCKTITLE_MAINMENU."" : 'Main Menu';
    $mainblock_block_home = (defined('_BLOCKTITLE_MAINMENU_HOME')) ? ""._BLOCKTITLE_MAINMENU_HOME."" : 'Home';
    $mainblock_block_homealt = (defined('_BLOCKTITLE_MAINMENU_HOMEALT')) ? ""._BLOCKTITLE_MAINMENU_HOMEALT."" : 'Back to the home page.';
    $mainblock_block_user = (defined('_BLOCKTITLE_MAINMENU_USER')) ? ""._BLOCKTITLE_MAINMENU_USER."" : 'My Account';
    $mainblock_block_useralt = (defined('_BLOCKTITLE_MAINMENU_USERALT')) ? ""._BLOCKTITLE_MAINMENU_USERALT."" : 'Administer your personal account.';
    $mainblock_block_admin = (defined('_BLOCKTITLE_MAINMENU_ADMIN')) ? ""._BLOCKTITLE_MAINMENU_ADMIN."" : 'Administration';
    $mainblock_block_adminalt = (defined('_BLOCKTITLE_MAINMENU_ADMINALT')) ? ""._BLOCKTITLE_MAINMENU_ADMINALT."" : 'Administer your PostNuked site.';
    $mainblock_block_userexit = (defined('_BLOCKTITLE_MAINMENU_USEREXIT')) ? ""._BLOCKTITLE_MAINMENU_USEREXIT."" : 'Logout';
    $mainblock_block_userexitalt = (defined('_BLOCKTITLE_MAINMENU_USEREXITALT')) ? ""._BLOCKTITLE_MAINMENU_USEREXITALT."" : 'Logout of your account.';
    $mainblock_block_dl = (defined('_BLOCKTITLE_MAINMENU_DL')) ? ""._BLOCKTITLE_MAINMENU_DL."" : 'Downloads';
    $mainblock_block_dlalt = (defined('_BLOCKTITLE_MAINMENU_DLALT')) ? ""._BLOCKTITLE_MAINMENU_DLALT."" : 'Find downloads listed on this website.';
    $mainblock_block_faq = (defined('_BLOCKTITLE_MAINMENU_FAQ')) ? ""._BLOCKTITLE_MAINMENU_FAQ."" : 'FAQ';
    $mainblock_block_faqalt = (defined('_BLOCKTITLE_MAINMENU_FAQALT')) ? ""._BLOCKTITLE_MAINMENU_FAQALT."" : 'Frequently Asked Questions';
    $mainblock_block_news = (defined('_BLOCKTITLE_MAINMENU_NEWS')) ? ""._BLOCKTITLE_MAINMENU_NEWS."" : 'News';
    $mainblock_block_newsalt = (defined('_BLOCKTITLE_MAINMENU_NEWSALT')) ? ""._BLOCKTITLE_MAINMENU_NEWSALT."" : 'Latest News on this site.';
    $mainblock_block_rws = (defined('_BLOCKTITLE_MAINMENU_RWS')) ? ""._BLOCKTITLE_MAINMENU_RWS."" : 'Reviews';
    $mainblock_block_rwsalt = (defined('_BLOCKTITLE_MAINMENU_RWSALT')) ? ""._BLOCKTITLE_MAINMENU_RWSALT."" : 'Reviews Section on this website.';
    $mainblock_block_search = (defined('_BLOCKTITLE_MAINMENU_SEARCH')) ? ""._BLOCKTITLE_MAINMENU_SEARCH."" : 'Search';
    $mainblock_block_searchalt = (defined('_BLOCKTITLE_MAINMENU_SEARCHALT')) ? ""._BLOCKTITLE_MAINMENU_SEARCHALT."" : 'Search our website.';
    $mainblock_block_sections = (defined('_BLOCKTITLE_MAINMENU_SECTIONS')) ? ""._BLOCKTITLE_MAINMENU_SECTIONS."" : 'Sections';
    $mainblock_block_sectionsalt = (defined('_BLOCKTITLE_MAINMENU_SECTIONSALT')) ? ""._BLOCKTITLE_MAINMENU_SECTIONSALT."" : 'Other content on this website.';
    $mainblock_block_snews = (defined('_BLOCKTITLE_MAINMENU_SNEWS')) ? ""._BLOCKTITLE_MAINMENU_SNEWS."" : 'Submit News';
    $mainblock_block_snewsalt = (defined('_BLOCKTITLE_MAINMENU_SNEWSALT')) ? ""._BLOCKTITLE_MAINMENU_SNEWSALT."" : 'Submit an article.';
    $mainblock_block_topics = (defined('_BLOCKTITLE_MAINMENU_TOPICS')) ? ""._BLOCKTITLE_MAINMENU_TOPICS."" : 'Topics';
    $mainblock_block_topicsalt = (defined('_BLOCKTITLE_MAINMENU_TOPICSALT')) ? ""._BLOCKTITLE_MAINMENU_TOPICSALT."" : 'Listing of news topics on this website.';
    $mainblock_block_wlinks = (defined('_BLOCKTITLE_MAINMENU_WLINKS')) ? ""._BLOCKTITLE_MAINMENU_WLINKS."" : 'Web Links';
    $mainblock_block_wlinksalt = (defined('_BLOCKTITLE_MAINMENU_WLINKSALT')) ? ""._BLOCKTITLE_MAINMENU_WLINKSALT."" : 'Links to other sites.';

    // popuplate blocks table
    $result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_blocks VALUES (1,'menu','$mainblock_block_title','style:=1\ndisplaymodules:=0\ndisplaywaiting:=0\ncontent:=index.php|$mainblock_block_home|$mainblock_block_homealt.LINESPLITuser.php|$mainblock_block_user|$mainblock_block_useralt.LINESPLITadmin.php|$mainblock_block_admin|$mainblock_block_adminalt.LINESPLITuser.php?module=User&op=logout|$mainblock_block_userexit|$mainblock_block_userexitalt.LINESPLIT|$modules|LINESPLIT[Downloads]|$mainblock_block_dl|$mainblock_block_dlalt.LINESPLIT[FAQ]|$mainblock_block_faq|$mainblock_block_faqalt.LINESPLIT[News]|$mainblock_block_news|$mainblock_block_newsalt.LINESPLIT[Reviews]|$mainblock_block_rws|$mainblock_block_rwsalt.LINESPLIT[Search]|$mainblock_block_search|$mainblock_block_searchalt.LINESPLIT[Sections]|$mainblock_block_sections|$mainblock_block_sectionsalt.LINESPLIT[Submit_News]|$mainblock_block_snews|$mainblock_block_snewsalt.LINESPLIT[Topics]|$mainblock_block_topics|$mainblock_block_topicsalt.LINESPLIT[Web_Links]|$mainblock_block_wlinks|$mainblock_block_wlinksalt.','',0, 'l','1.0',1,0,20011122090726,'', 1 , 1)") or die ("<strong>"._NOTUPDATED. $prefix."_blocks ($mainblock_block_title)</span>");
    $result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_blocks VALUES ( '2', 'menu', '$incoming_block_title', 'style:=1\ndisplaymodules:=0\ndisplaywaiting:=1\ncontent:=', '', 0, 'l', '2.0', '1', '0', '00000000000000', '', 1 , 1)") or die ("<strong>"._NOTUPDATED.$prefix."_blocks ($incoming_block_title)</strong>");
    $result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_blocks VALUES ( '3', 'online', '$whoisonline_block_title', '', '', 0, 'l', '3.0', '1', '0', '00000000000000', '', 1 , 1)") or die ("<strong>"._NOTUPDATED. $prefix."_blocks ($whoisonline_block_title)</strong>");
//    $result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_blocks VALUES ( '4', 'stories', '$otherstories_block_title', 'type:=1 topic:=-1  category:=-1 limit:=10', '', 0, 'r', '1.0', '1', '0', '00000000000000', '', 1 , 1)") or die ("<strong>"._NOTUPDATED. $prefix."_blocks ($otherstories_block_title)</strong>");
    $result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_blocks VALUES ( '5', 'user', '$usersblock_block_title', '$usersblocktexte_block_title', '', 0, 'l', '3.5', '1', '0', '00000000000000', '', 1 , 1)") or die ("<strong>"._NOTUPDATED.$prefix."_blocks ($usersblock_block_title)</strong>");
    $result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_blocks VALUES ( '6', 'search', '$searchbox_block_title', '', '', 0, 'l', '4.0', '0', '0', '00000000000000', '', 1 , 1)") or die ("<strong>"._NOTUPDATED.$prefix."_blocks ($searchbox_block_title)</strong>");
    $result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_blocks VALUES ( '8', 'thelang', '$languages_block_title', '', '', 0, 'l', '6.0', '1', '0', '00000000000000', '', 1 , 1)") or die ("<strong>"._NOTUPDATED.$prefix."_blocks ($languages_block_title)</strong>");
//    $result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_blocks VALUES ( '9', 'category', '$catmenu_block_title', '', '', 0, 'r', '1.5', '1', '0', '00000000000000', '', 1 , 1)") or die ("<strong>"._NOTUPDATED.$prefix."_blocks ($catmenu_block_title)</strong>");
//    $result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_blocks VALUES ( '10', 'random', '$ranhead_block_title', '', '', 0, 'r', '2.0', '0', '0', '00000000000000', '', 1 , 1)") or die ("<strong>"._NOTUPDATED.$prefix."_blocks ($ranhead_block_title)</strong>");
//    $result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_blocks VALUES ( '11', 'poll', '$poll_block_title', '', '', 0, 'r', '3.0', '1', '0', '00000000000000', '', 1 , 1)") or die ("<strong>"._NOTUPDATED.$prefix."_blocks ($poll_block_title)</strong>");
//    $result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_blocks VALUES ( '12', 'big', '$bigstory_block_title', '', '', 0, 'r', '4.0', '1', '0', '00000000000000', '', 1 , 1)") or die ("<strong>"._NOTUPDATED.$prefix."_blocks ($bigstory_block_title)</strong>");
    $result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_blocks VALUES ( '13', 'login', '$userslogin_block_title', '', '', 0, 'r', '5.0', '1', '0', '00000000000000', '', 1 , 1)") or die ("<strong>"._NOTUPDATED.$prefix."_blocks ($userslogin_block_title)</strong>");
//    $result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_blocks VALUES ( '14', 'past', '$pastart_block_title', '', '', 0, 'r', '6.0', '1', '0', '00000000000000', '', 1 , 1)") or die ("<strong>"._NOTUPDATED.$prefix."_blocks ($pastart_block_title)</strong>");
    $result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_blocks VALUES ( '15', 'messages', '$adminmess_block_title', '', '', '".pnModGetIDFromName('Admin_Messages')."', 'c', '1.0', '1', '0', '00000000000000', '', 1 , 1)") or die ("<strong>"._NOTUPDATED.$prefix."_blocks ($adminmess_block_title)</strong>");
    echo "<br /><span class=\"pn-sub\">".$prefix."_blocks "._UPDATED."</span>";

    // populate group_perms table
//    $result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_group_perms VALUES (0, 2, 1, 0, '.*', '.*', 800, 0)") || die("<strong>"._NOTUPDATED.$prefix."_group_perms</strong>");
    $result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_group_perms VALUES (0, -1, 2, 0, 'Menublock::', '$mainblock_block_title:$mainblock_block_admin:', 0, 0)") || die("<strong>"._NOTUPDATED.$prefix."_group_perms</strong>");
    $result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_group_perms VALUES (0, 1, 3, 0, '.*', '.*', 300, 0)") || die("<strong>"._NOTUPDATED.$prefix."_group_perms</strong>");
    $result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_group_perms VALUES (0, 0, 4, 0, 'Menublock::', '$mainblock_block_title:($mainblock_block_user|$mainblock_block_userexit|$mainblock_block_snews):', 0, 0)") || die("<strong>"._NOTUPDATED.$prefix."_group_perms</strong>");
    $result = $inst_dbconn->Execute("INSERT INTO ".$prefix."_group_perms VALUES (0, 0, 5, 0, '.*', '.*', 200, 0)") || die("<strong>"._NOTUPDATED.$prefix."_group_perms</strong>");
    echo "<br /><span class=\"pn-sub\">".$prefix."_group_perms"._UPDATED."</span>";

}

?>