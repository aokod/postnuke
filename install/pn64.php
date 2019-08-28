<?php 
// File: $Id: pn64.php 15630 2005-02-04 06:35:42Z jorg $
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
// Upgrade from PostNuke .64 to PostNuke .7

// Note that this is upgrade script uses PEAR instead
// of raw mysql
// Need this because we're inside a function
// global $dbconn, $pntable;
global $dbconn, $pntable;
include_once 'install/pntables64.php';

function fixanonyuser()
{
    global $dbconn, $pntable, $prefix; 
    // Everyone is a user
    // Wrote this with mysql because this overcomes a legacy problem with nukeaddon and the -1 uid.
    $result = mysql_query("DELETE from $pntable[users] WHERE uid='-1'");
    $result = mysql_query("INSERT INTO $pntable[users] VALUES ( '1', '', 'Anonymous', '', '', '', 'blank.gif', 'Nov 10, 2000', '', '', '', '', '', '0', '0', '', '', '', '', '10', '', '0', '0', '0', '', '0', '', '', '4096', '0', '12.0')");

    $result = mysql_query("CREATE TABLE temp_users (
    uid int(11) NOT NULL auto_increment,
    name varchar(60) NOT NULL,
    uname varchar(25) NOT NULL,
    email varchar(60) NOT NULL,
    femail varchar(60) NOT NULL,
    url varchar(100) NOT NULL,
    user_avatar varchar(30),
    user_regdate varchar(20) NOT NULL,
    user_icq varchar(15),
    user_occ varchar(100),
    user_from varchar(100),
    user_intrest varchar(150),
    user_sig varchar(255),
    user_viewemail tinyint(2),
    user_theme int(3),
    user_aim varchar(18),
    user_yim varchar(25),
    user_msnm varchar(25),
    pass varchar(40) NOT NULL,
    storynum tinyint(4) DEFAULT '10' NOT NULL,
    umode varchar(10) NOT NULL,
    uorder tinyint(1) DEFAULT '0' NOT NULL,
    thold tinyint(1) DEFAULT '0' NOT NULL,
    noscore tinyint(1) DEFAULT '0' NOT NULL,
    bio tinytext NOT NULL,
    ublockon tinyint(1) DEFAULT '0' NOT NULL,
    ublock tinytext NOT NULL,
    theme varchar(255) NOT NULL,
    commentmax int(11) DEFAULT '4096' NOT NULL,
    counter int(11) DEFAULT '0' NOT NULL,
    timezone_offset float(3,1) DEFAULT '0.0' NOT NULL,
    PRIMARY KEY (uid)
    )");
    $result = mysql_query("select  uid, name, uname, email, femail, url, user_avatar, user_regdate, user_icq, user_occ, user_from, user_intrest, user_sig, user_viewemail, user_theme, user_aim, user_yim, user_msnm, pass, storynum, umode, uorder, thold, noscore, bio,    ublockon, ublock, theme, commentmax, counter, timezone_offset FROM $pntable[users]");
    while (list($uid, $name, $uname, $email, $femail, $url, $user_avatar, $user_regdate, $user_icq, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $user_theme, $user_aim, $user_yim, $user_msnm, $pass, $storynum, $umode, $uorder, $thold, $noscore, $bio, $ublockon, $ublock, $theme, $commentmax, $counter, $timezone_offset) = mysql_fetch_row($result)) {
        $result2 = mysql_query("insert into temp_users values ('$uid', '$name', '$uname', '$email', '$femail', '$url', '$user_avatar', '$user_regdate', '$user_icq', '$user_occ', '$user_from', '$user_intrest', '$user_sig', '$user_viewemail', '$user_theme', '$user_aim', '$user_yim', '$user_msnm', '$pass', '$storynum', '$umode', '$uorder', '$thold', '$noscore', '$bio', '$ublockon', '$ublock', '$theme', '$commentmax', '$counter', '$timezone_offset')");
    } 
    $result = mysql_query("DROP TABLE $pntable[users]");
    $result = mysql_query("ALTER TABLE temp_users RENAME " . $prefix . "_users");
} 

$userscolumn = $pntable['users_column'];
$result = $dbconn->Execute("SELECT $userscolumn[uid] from $pntable[users] WHERE uname='Anonymous'");
// ADODBtag MoveNext while+list+row
list($uid) = $result->fields;
if ($uid == '-1') {
    fixanonyuser();
} 
// End the legacy fix
// Additions to pntable - because authors is no longer in PN but it used to be.
$pntable['authors'] = $prefix . '_authors';
$pntable['authors_column'] = array ('aid' => "$pntable[authors].aid",
    'name' => "$pntable[authors].name",
    'url' => "$pntable[authors].url",
    'email' => "$pntable[authors].email",
    'pwd' => "$pntable[authors].pwd",
    'counter' => "$pntable[authors].counter",
    'radminarticle' => "$pntable[authors].radminarticle",
    'radmintopic' => "$pntable[authors].radmintopic",
    'radminuser' => "$pntable[authors].radminuser",
    'radminsurvey' => "$pntable[authors].radminsurvey",
    'radminsection' => "$pntable[authors].radminsection",
    'radminlink' => "$pntable[authors].radminlink",
    'radminephem' => "$pntable[authors].radminephem",
    'radminfilem' => "$pntable[authors].radminfilem",
    'radminfaq' => "$pntable[authors].radminfaq",
    'radmindownload' => "$pntable[authors].radmindownload",
    'radminreviews' => "$pntable[authors].radminreviews",
    'radminblocks' => "$pntable[authors].radminblocks",
    'radminsuper' => "$pntable[authors].radminsuper",
    'admlanguage' => "$pntable[authors].admlanguage");
// Add a bodytext for user submitted news queue
$dbconn->Execute("ALTER TABLE " . $prefix . "_queue ADD bodytext text AFTER alanguage");

function migratelinkmenu()
{
    global $dbconn, $pntable; 
    // Shorthand for columns
    $blockscolumn = $pntable['blocks_column']; 
    // Get link blocks
    $result = $dbconn->Execute("SELECT $blockscolumn[bid], $blockscolumn[content], $blockscolumn[url]
                              FROM $pntable[blocks]
                              WHERE $blockscolumn[bkey] = 'link'");
    if ($result->EOF) {
        // No link menus
        $result->Close();
        return;
    } 
    // Could be multiple blocks
    // ADODBtag MoveNext while+list+row
    while (list($bid, $oldcontent, $url) = $result->fields) {
        $result->MoveNext(); 
        // Munge old content
        $oldcontent = explode("\n", trim($oldcontent));
        $newcontent = array();
        foreach ($oldcontent as $oc) {
            preg_match('!^.*<A\s+HREF="?([^>]*)>\s*([^<]*)!si', $oc, $matches); 
            // Title might have trailing '"'
            $matches[1] = preg_replace('!"$!', '', $matches[1]);
            $newcontent[] = "$matches[1]|$matches[2]|";
        } 
        $newcontent = join('LINESPLIT', $newcontent);

        if (eregi("^1", $url)) {
            $displaymodules = 1;
        } else {
            $displaymodules = 0;
        } 
        $content = "style:=1\ndisplaymodules:=$displaymodules\ndisplaywaiting:=0\ncontent:=$newcontent";
        $dbconn->Execute("UPDATE $pntable[blocks]
                    SET $blockscolumn[bkey] = 'menu',
                $blockscolumn[url] = '',
                $blockscolumn[content] = '$content'
            WHERE $blockscolumn[bid] = $bid");
    } 
    // $result->Close(); 
    // Done
} 
migratelinkmenu();

function migratelinklist()
{
    global $dbconn, $pntable; 
    // Shorthand for columns
    $blockscolumn = $pntable['blocks_column']; 
    // Get linkmenu blocks
    $result = $dbconn->Execute("SELECT $blockscolumn[bid], $blockscolumn[content], $blockscolumn[url]
                              FROM $pntable[blocks]
                              WHERE $blockscolumn[bkey] = 'linkmenu'");
    if ($result->EOF) {
        // No link lists
        $result->Close();
        return;
    } 
    // Could be multiple blocks
    // ADODBtag MoveNext while+list+row
    while (list($bid, $oldcontent, $url) = $result->fields) {
        $result->MoveNext(); 
        // Munge old content
        $oldcontent = explode("\n", trim($oldcontent));
        $newcontent = array();
        foreach ($oldcontent as $oc) {
            preg_match('!^.*<A\s+HREF="?([^>]*)>\s*([^<]*)!si', $oc, $matches); 
            // Title might have trailing '"'
            $matches[1] = preg_replace('!"$!', '', $matches[1]);
            $newcontent[] = "$matches[1]|$matches[2]|";
        } 
        $newcontent = join('LINESPLIT', $newcontent);

        if (eregi("^1", $url)) {
            $displaywaiting = 1;
        } else {
            $displaywaiting = 0;
        } 
        $content = "style:=2\ndisplaymodules:=1\ndisplaywaiting:=$displaywaiting\ncontent:=$newcontent";
        $dbconn->Execute("UPDATE $pntable[blocks]
                    SET $blockscolumn[bkey] = 'menu',
                $blockscolumn[url] = '',
                $blockscolumn[content] = '$content'
            WHERE $blockscolumn[bid] = $bid");
    } 
    // $result->Close(); 
    // Done
} 
migratelinklist();

function migratealinkmenu()
{
    global $dbconn, $pntable; 
    // Shorthand for columns
    $blockscolumn = $pntable['blocks_column']; 
    // Get alink blocks
    $result = $dbconn->Execute("SELECT $blockscolumn[bid], $blockscolumn[content], $blockscolumn[url]
                              FROM $pntable[blocks]
                              WHERE $blockscolumn[bkey] = 'alink'");
    if ($result->EOF) {
        // No admin link menus
        $result->Close();
        return;
    } 
    // Could be multiple blocks
    // ADODBtag MoveNext while+list+row
    while (list($bid, $oldcontent, $url) = $result->fields) {
        $result->MoveNext(); 
        // Munge old content
        $oldcontent = explode("\n", trim($oldcontent));
        $newcontent = array();
        foreach ($oldcontent as $oc) {
            preg_match('!^.*<A\s+HREF="?([^>]*)>\s*([^<]*)!si', $oc, $matches); 
            // Title might have trailing '"'
            $matches[1] = preg_replace('!"$!', '', $matches[1]);
            $newcontent[] = "$matches[1]|$matches[2]|";
        } 
        $newcontent = join('LINESPLIT', $newcontent);

        if (eregi("^1", $url)) {
            $displaywaiting = 1;
        } else {
            $displaywaiting = 0;
        } 
        $content = "style:=1\ndisplaymodules:=0\ndisplaywaiting:=$displaywaiting\ncontent:=$newcontent";
        $dbconn->Execute("UPDATE $pntable[blocks]
                    SET $blockscolumn[bkey] = 'menu',
                $blockscolumn[url] = '',
                $blockscolumn[content] = '$content'
            WHERE $blockscolumn[bid] = $bid");
    } 
    // $result->Close(); 
    // Done
} 
migratealinkmenu();

function migratealinklist()
{
    global $dbconn, $pntable; 
    // Shorthand for columns
    $blockscolumn = $pntable['blocks_column']; 
    // Get alinkmenu blocks
    $result = $dbconn->Execute("SELECT $blockscolumn[bid], $blockscolumn[content], $blockscolumn[url]
                              FROM $pntable[blocks]
                              WHERE $blockscolumn[bkey] = 'alinkmenu'");
    if ($result->EOF) {
        // No admin link menus
        $result->Close();
        return;
    } 
    // Could be multiple blocks
    // ADODBtag MoveNext while+list+row
    while (list($bid, $oldcontent, $url) = $result->fields) {
        $result->MoveNext(); 
        // Munge old content
        $oldcontent = explode("\n", trim($oldcontent));
        $newcontent = array();
        foreach ($oldcontent as $oc) {
            preg_match('!^.*<A\s+HREF="?([^>]*)>\s*([^<]*)!si', $oc, $matches); 
            // Title might have trailing '"'
            $matches[1] = preg_replace('!"$!', '', $matches[1]);
            $newcontent[] = "$matches[1]|$matches[2]|";
        } 
        $newcontent = join('LINESPLIT', $newcontent);

        if (eregi("^1", $url)) {
            $displaywaiting = 1;
        } else {
            $displaywaiting = 0;
        } 
        $content = "style:=1\ndisplaymodules:=0\ndisplaywaiting:=$displaywaiting\ncontent:=$newcontent";
        $dbconn->Execute("UPDATE $pntable[blocks]
                    SET $blockscolumn[bkey] = 'menu',
                $blockscolumn[url] = '',
                $blockscolumn[content] = '$content'
            WHERE $blockscolumn[bid] = $bid");
    } 
    // $result->Close(); 
    // Done
} 
migratealinklist();

function migratemainmenu()
{
    global $dbconn, $pntable; 
    // Shorthand for columns
    $blockscolumn = $pntable['blocks_column']; 
    // Get menu blocks
    $result = $dbconn->Execute("SELECT $blockscolumn[bid], $blockscolumn[content], $blockscolumn[url]
                              FROM $pntable[blocks]
                              WHERE $blockscolumn[bkey] = 'main'");
    if ($result->EOF) {
        // No main menus
        $result->Close();
        return;
    } 
    // Could be multiple blocks
    // ADODBtag MoveNext while+list+row
    while (list($bid, $oldcontent, $url) = $result->fields) {
        $result->MoveNext(); 
        // Munge old content
        $oldcontent = explode("\n", trim($oldcontent));
        foreach ($oldcontent as $oc) {
            preg_match('!^.*<A\s+HREF="?([^>]*)>\s*([^<]*)!si', $oc, $matches); 
            // Title might have trailing '"'
            $matches[1] = preg_replace('!"$!', '', $matches[1]);
            $newcontent[] = "$matches[1]|$matches[2]|";
        } 
        $newcontent = join('LINESPLIT', $newcontent);

        if (eregi("^1", $url)) {
            $displaywaiting = 1;
        } else {
            $displaywaiting = 0;
        } 
        $content = "style:=1\ndisplaymodules:=1\ndisplaywaiting:=$displaywaiting\ncontent:=$newcontent";
        $dbconn->Execute("UPDATE $pntable[blocks]
                          SET $blockscolumn[bkey] = 'menu',
                              $blockscolumn[url] = '',
                              $blockscolumn[content] = '" . addslashes($content) . "'
                          WHERE $blockscolumn[bid] = $bid");
    } 
    // $result->Close();
    // Done
} 
migratemainmenu();

function migrateadminmenu()
{
    global $dbconn, $pntable; 
    // Shorthand for columns
    $blockscolumn = $pntable['blocks_column']; 
    // Get menu blocks
    $result = $dbconn->Execute("SELECT $blockscolumn[bid], $blockscolumn[content], $blockscolumn[url]
                              FROM $pntable[blocks]
                              WHERE $blockscolumn[bkey] = 'admin'");
    if ($result->EOF) {
        // No admin menus
        $result->Close();
        return;
    } 
    // Could be multiple blocks
    // ADODBtag MoveNext while+list+row
    while (list($bid, $oldcontent, $url) = $result->fields) {
        $result->MoveNext(); 
        // Munge old content
        $oldcontent = explode("\n", trim($oldcontent));
        $newcontent = array();
        foreach ($oldcontent as $oc) {
            preg_match('!^.*<A\s+HREF="?([^>]*)>\s*([^<]*)!si', $oc, $matches); 
            // Title might have trailing '"'
            $matches[1] = preg_replace('!"$!', '', $matches[1]);
            $newcontent[] = "$matches[1]|$matches[2]|";
        } 
        $newcontent = join('LINESPLIT', $newcontent);
        $content = "style:=1\ndisplaymodules:=0\ndisplaywaiting:=0\ncontent:=$newcontent";
        $dbconn->Execute("UPDATE $pntable[blocks]
                         SET $blockscolumn[bkey] = 'menu',
                             $blockscolumn[url] = '',
                             $blockscolumn[content] = '$content'
                         WHERE $blockscolumn[bid] = $bid");
    } 
    // $result->Close(); 
    // Done
} 
migrateadminmenu();

function migratelast10()
{ 
    // Migrate last10 to stories
    global $dbconn, $pntable; 
    // Shorthand for columns
    $blockscolumn = $pntable['blocks_column']; 
    // Get last10 blocks
    $result = $dbconn->Execute("SELECT $blockscolumn[bid], $blockscolumn[url]
                              FROM $pntable[blocks]
                              WHERE $blockscolumn[bkey] = 'last10'");
    if ($result->EOF) {
        // No last10 blocks
        $result->Close();
        return;
    } else {
        // Could be multiple blocks
        while (!$result->EOF) {
            list($bid, $url) = $result->fields;
            if (!empty($url)) {
                $num = $url;
            } else {
                $num = 10;
            } 
            $comment = "type=2\ntopic=-1\ncategory=-1\nlimit=$num\n";
            $dbconn->Execute("UPDATE $pntable[blocks]
                        SET $blockscolumn[bkey] = 'stories',
                            $blockscolumn[title] = 'Stories',
                    $blockscolumn[url] = '',
                    $blockscolumn[content] = '$comment'
                WHERE $blockscolumn[bid] = $bid");
            $result->MoveNext();
        } 
    } 
    // Done
} 
migratelast10();

function validateweblinks()
{ 
    // Validate current weblinks
    global $dbconn, $pntable; 
    // Shorthand for columns
    $linkscolumn = $pntable['links_links_column'];
    $catcolumn = $pntable['links_categories_column'];
    $newlinkcolumn = $pntable['links_newlink_column'];
    $editorialcolumn = $pntable['links_editorials_column'];
    $votecolumn = $pntable['links_votedata_column'];
    $modcolumn = $pntable['links_modrequest_column']; 
    // Get invalid categories
    // Invalid categories are categories that do not have a valid parent
    // Note that this is iterative, as removing one cateogry can invalidate others
    while (1) {
        // Get list of all cids
        $result = $dbconn->Execute("SELECT $catcolumn[cat_id] FROM $pntable[links_categories]");
        if ($result->EOF) {
            // No rows at all
            $result->Close();
            break;
        } 
        $allcid = array();
        // ADODBtag MoveNext while+list+row
        while (!$result->EOF) {
            list($cid) = $result->fields;
            $allcid[$cid] = $cid;
            $result->MoveNext();
        } 
        $result->Close();
        $allcids = implode(",", $allcid); 
        // Get list of cids whose parents aren't here
        $result = $dbconn->Execute("SELECT $catcolumn[cat_id] FROM $pntable[links_categories] where parent_id not in (0,$allcids)");
        if ($result->EOF) {
            // No invalid rows - finished
            $result->Close();
            break;
        } 
        $invalidcid = array();
        // ADODBtag MoveNext while+list+row
        while (list($cid) = $result->fields) {
            $result->MoveNext();
            $invalidcid[$cid] = $cid;
        } 
        $result->Close();
        $invalidcids = implode(",", $invalidcid); 
        // Remove these categories
        $dbconn->Execute("DELETE FROM $pntable[links_newlink] where $newlinkcolumn[cat_id] IN ($invalidcids)");
        $dbconn->Execute("DELETE FROM $pntable[links_categories] where $catcolumn[cat_id] IN ($invalidcids)");
    } 
    // At this stage categories should be clean, so get a list of all valid categories
    $result = $dbconn->Execute("SELECT $catcolumn[cat_id] FROM $pntable[links_categories]");
    $validcid = array();
    // ADODBtag MoveNext while+list+row
    while (list($cid) = $result->fields) {
        $result->MoveNext();
        $validcid[$cid] = $cid;
    } 
    $result->Close();
    $validcids = implode(",", $validcid); 
    // Ensure that there are some categories
    if (empty($validcids)) {
        return;
    } 
    // Now find invalid links
    $result = $dbconn->Execute("SELECT $linkscolumn[lid] FROM $pntable[links_links] WHERE $linkscolumn[cat_id] NOT IN (0,$validcids)");
    $invalidlid = array();
    // ADODBtag MoveNext while+list+row
    while (list($lid) = $result->fields) {
        $result->MoveNext();
        $invalidlid[$lid] = $lid;
    } 
    $result->Close();
    $invalidlids = implode(",", $invalidlid); 
    // Remove invalid links
    if (!empty($invalidlids)) {
        $dbconn->Execute("DELETE FROM $pntable[links_editorials] WHERE $editorialcolumn[linkid] IN ($invalidlids)");
        $dbconn->Execute("DELETE FROM $pntable[links_votedata] WHERE $votecolumn[ratinglid] IN ($invalidlids)");
        $dbconn->Execute("DELETE FROM $pntable[links_modrequest] WHERE $modcolumn[lid] IN ($invalidlids)");
        $dbconn->Execute("DELETE FROM $pntable[links_links] WHERE $linkscolumn[lid] IN ($invalidlids)");
    } 
} 
validateweblinks();
// Add new tables for permissions
$dbconn->Execute("CREATE TABLE " . $prefix . "_group_membership (gid int(6) NOT NULL, uid int(11) NOT NULL)");

$dbconn->Execute("CREATE TABLE " . $prefix . "_group_perms (pid int(11) NOT NULL auto_increment, gid int(6) NOT NULL, sequence int(6) NOT NULL, realm int(4) NOT NULL, component varchar(255) NOT NULL, instance varchar(255) NOT NULL, level int(4) NOT NULL, bond int(2) NOT NULL, PRIMARY KEY (pid))");

$dbconn->Execute("CREATE TABLE " . $prefix . "_user_perms (pid int(11) NOT NULL auto_increment, uid int(11) NOT NULL, sequence int(6) NOT NULL, realm int(4) NOT NULL, component varchar(255) NOT NULL, instance varchar(255) NOT NULL, level int(4) NOT NULL, bond int(2) NOT NULL, PRIMARY KEY (pid))");

$dbconn->Execute("CREATE TABLE " . $prefix . "_groups (gid int(6) NOT NULL auto_increment, name varchar(255) NOT NULL, PRIMARY KEY (gid))");

$dbconn->Execute("CREATE TABLE " . $prefix . "_realms (rid int(4) NOT NULL auto_increment, name varchar(255) NOT NULL, PRIMARY KEY (rid))");
// Add basic permissions information
// Simple groups
$dbconn->Execute("INSERT INTO " . $prefix . "_groups (name) VALUES ('Users')");
$dbconn->Execute("INSERT INTO " . $prefix . "_groups (name) VALUES ('Admins')");
// Simple permissions structure
$dbconn->Execute("INSERT INTO " . $prefix . "_group_perms (gid, sequence, realm, component, instance, level) VALUES (2, 1, 0, '.*', '.*', 800)");
$dbconn->Execute("INSERT INTO " . $prefix . "_group_perms (gid, sequence, realm, component, instance, level) VALUES (-1, 2, 0, 'Menublock::', 'Main Menu:Administration:', 0)");
$dbconn->Execute("INSERT INTO " . $prefix . "_group_perms (gid, sequence, realm, component, instance, level) VALUES (1, 3, 0, '.*', '.*', 300)");
$dbconn->Execute("INSERT INTO " . $prefix . "_group_perms (gid, sequence, realm, component, instance, level) VALUES (0, 4, 0, 'Menublock::', 'Main Menu:(My Account|Logout|Submit News):', 0)");
$dbconn->Execute("INSERT INTO " . $prefix . "_group_perms (gid, sequence, realm, component, instance, level) VALUES (0, 5, 0, '.*', '.*', 200)");
// Make all users members of the users group
function addUsersToUsersGroup()
{
    global $dbconn, $pntable; 
    // Shorthand for columns
    $userscolumn = $pntable['users_column']; 
    // Get list of all UIDs
    $query = "SELECT $userscolumn[uid]
              FROM $pntable[users]";

    $result = $dbconn->Execute($query);
    // ADODBtag MoveNext while+list+row
    while (list($uid) = $result->fields) {
        $result->MoveNext();
        addUIDToGroup($uid, "Users");
    } 
    $result->Close();
} 
addUserstoUsersGroup();
// Migrate administrators to users
// First, create admin groups as required
function createGroups()
{ 
    // Create groups for specific admin purposes
    global $dbconn, $pntable; 
    // Shorthand for columns
    $authorscolumn = $pntable['authors_column'];

    $query = "SELECT MAX($authorscolumn[radminarticle]),
                     MAX($authorscolumn[radmintopic]),
                     MAX($authorscolumn[radminuser]),
                     MAX($authorscolumn[radminsurvey]),
                     MAX($authorscolumn[radminsection]),
                     MAX($authorscolumn[radminlink]),
                     MAX($authorscolumn[radminephem]),
                     MAX($authorscolumn[radminfilem]),
                     MAX($authorscolumn[radminfaq]),
                     MAX($authorscolumn[radmindownload]),
                     MAX($authorscolumn[radminreviews]),
                     MAX($authorscolumn[radminblocks])
              FROM $pntable[authors]";

    $result = $dbconn->Execute($query);
    // ADODBtag list+row
    list($article, $topic, $user, $survey, $section, $link, $ephem, $filem, $faq, $download, $reviews, $blocks) = $result->fields;

    if ($article == 1) {
        createAdminGroup("Article admins", "Stories::");
    } 
    if ($topic == 1) {
        createAdminGroup("Topic admins", "Topics::");
    } 
    if ($user == 1) {
        createAdminGroup("User admins", "Users::");
    } 
    if ($survey == 1) {
        createAdminGroup("Poll admins", "Polls::");
    } 
    if ($section == 1) {
        createAdminGroup("Section admins", "Sections::");
    } 
    if ($link == 1) {
        // Link is over-used
        createAdminGroup("Autolinks admins", "Autolinks::");
        createAdminGroup("Banners admins", "Banners::");
        createAdminGroup("Weblinks admins", "Web Links::");
    } 
    if ($ephem == 1) {
        // so is ephem
        createAdminGroup("Ephemerids admins", "Ephemerids::");
        createAdminGroup("Quotes admins", "Quotes::");
    } 
    // filem isn't used for anything
    if ($faq == 1) {
        createAdminGroup("FAQ admins", "FAQ::");
    } 
    if ($download == 1) {
        createAdminGroup("Download admins", "Downloads::");
    } 
    if ($reviews == 1) {
        createAdminGroup("Review admins", "Reviews::");
    } 
    if ($blocks == 1) {
        createAdminGroup("Block admins", "Blocks::");
    } 

    resequenceGroupPerms();
} 
createGroups();

function resequenceGroupPerms()
{
    global $dbconn, $pntable;

    $permtable = $pntable['group_perms'];
    $permcolumn = $pntable['group_perms_column']; 
    // Get the information
    $query = "SELECT $permcolumn[pid],
                     $permcolumn[sequence]
              FROM $permtable
              ORDER BY $permcolumn[sequence]";
    $result = $dbconn->Execute($query); 
    // Fix sequence numbers
    $seq = 1;
    // ADODBtag MoveNext while+list+row
    while (list($pid, $curseq) = $result->fields) {
        $result->MoveNext();
        if ($curseq != $seq) {
            $query = "UPDATE $permtable
                      SET $permcolumn[sequence]=$seq
                      WHERE $permcolumn[pid]=$pid";
            $dbconn->Execute($query);
        } 
        $seq++;
    } 
    $result->Close();
} 

function createAdminGroup($groupname, $admincomponent)
{ 
    // Create an admin group and set a relevant permission
    global $dbconn, $pntable; 
    // Shorthand for columns
    $groupscolumn = $pntable['groups_column'];
    $grouppermscolumn = $pntable['group_perms_column'];

    $gid = $dbconn->GenId($pntable['groups']);
    $query = "INSERT INTO $pntable[groups]
              ($groupscolumn[gid], $groupscolumn[name])
              VALUES($gid, \"$groupname\")";
    $dbconn->Execute($query);
    $gid = $dbconn->PO_Insert_ID($pntable[groups], $groupscolumn[gid]);

    $pid = $dbconn->GenId($pntable['group_perms']);
    $query = "INSERT INTO $pntable[group_perms]
              ($grouppermscolumn[pid],
               $grouppermscolumn[gid],
               $grouppermscolumn[sequence],
               $grouppermscolumn[realm],
               $grouppermscolumn[component],
               $grouppermscolumn[instance],
               $grouppermscolumn[level],
               $grouppermscolumn[bond])
              VALUES ($pid,
                      $gid,
                      1,
                      0,
                      '$admincomponent',
                      '::',
                      800,
                      0)";
    $dbconn->Execute($query);
} 
// Second, migrate administrators to users
function migrateAdminUsers()
{ 
    // Migrate admins to normal users
    global $dbconn, $pntable; 
    // Shorthand for columns
    $authorscolumn = $pntable['authors_column'];
    $userscolumn = $pntable['users_column'];

    $query = "SELECT $authorscolumn[aid],
                     $authorscolumn[name],
                     $authorscolumn[url],
                     $authorscolumn[email],
                     $authorscolumn[pwd],
                     $authorscolumn[counter],
                     $authorscolumn[admlanguage],
                     $authorscolumn[radminsuper],
                     $authorscolumn[radminarticle],
                     $authorscolumn[radmintopic],
                     $authorscolumn[radminuser],
                     $authorscolumn[radminsurvey],
                     $authorscolumn[radminsection],
                     $authorscolumn[radminlink],
                     $authorscolumn[radminephem],
                     $authorscolumn[radminfilem],
                     $authorscolumn[radminfaq],
                     $authorscolumn[radmindownload],
                     $authorscolumn[radminreviews],
                     $authorscolumn[radminblocks]
              FROM $pntable[authors]";
    $result = $dbconn->Execute($query);

    $newusers = array();
    $migratedusers = array();
    // ADODBtag MoveNext while+list+row
    while (list($aid, $name, $url, $email, $pwd, $counter, $admlanguage, $radminsuper, $radminarticle, $radmintopic, $radminuser, $radminsurvey, $radminsection, $radminlink, $radminephem, $radminfilem, $radminfaq, $radmindownload, $radminreviews, $radminblocks) = $result->fields) {
        $result->MoveNext(); 
        // See if this admin exists as a user
        $userquery = "SELECT $userscolumn[uid]
                      FROM $pntable[users]
                      WHERE $userscolumn[uname] = '$aid'";
        $userresult = $dbconn->Execute($userquery);

        if (!$userresult->EOF) {
            // This admin exists as a user
            // ADODBtag list+row
            list($uid) = $userresult->fields;
            $migratedusers[] = "$aid($name)";
        } else {
            // This admin does not exist as a user - create
            $uid = $dbconn->GenId($pntable['users']); 
            // $encpass = crypt($pwd, '..');
            // upgrades moved to md5 encryption - works on all windows *nix variants.
            $encpass = md5($pwd);

            $userquery = "INSERT INTO $pntable[users]
                         ($userscolumn[uid],
                          $userscolumn[name],
                          $userscolumn[uname],
                          $userscolumn[email],
                          $userscolumn[femail],
                          $userscolumn[url],
                          $userscolumn[user_avatar],
                          $userscolumn[user_regdate],
                          $userscolumn[user_icq],
                          $userscolumn[user_occ],
                          $userscolumn[user_from],
                          $userscolumn[user_intrest],
                          $userscolumn[user_sig],
                          $userscolumn[user_viewemail],
                          $userscolumn[user_theme],
                          $userscolumn[user_aim],
                          $userscolumn[user_yim],
                          $userscolumn[user_msnm],
                          $userscolumn[pass],
                          $userscolumn[storynum],
                          $userscolumn[umode],
                          $userscolumn[uorder],
                          $userscolumn[thold],
                          $userscolumn[noscore],
                          $userscolumn[bio],
                          $userscolumn[ublockon],
                          $userscolumn[ublock],
                          $userscolumn[theme],
                          $userscolumn[commentmax],
                          $userscolumn[counter],
                          $userscolumn[timezone_offset])
                         VALUES ($uid,
                                 '$name',
                                 '$aid',
                                 '$email',
                                 '',
                                 '$url',
                                 'blank.gif',
                                 '',
                                 '',
                                 '',
                                 '',
                                 '',
                                 '',
                                 '',
                                 '',
                                 '',
                                 '',
                                 '',
                                 '$encpass',
                                 10,
                                 '',
                                 0,
                                 0,
                                 0,
                                 '',
                                 0,
                                 '',
                                 '',
                                 4096,
                                 0,
                                 0)";
            $dbconn->Execute($userquery);
            $uid = $dbconn->PO_Insert_ID($pntable[users], $userscolumn[uid]);
            $newusers[] = "$aid($name)";
        } 

        if ($radminsuper == 1) {
            // Superuser
            addUIDToGroup($uid, "Admins");
        } else {
            if ($radminarticle == 1) {
                addUIDToGroup($uid, "Article admins");
            } 
            if ($radmintopic == 1) {
                addUIDToGroup($uid, "Topic admins");
            } 
            if ($radminuser == 1) {
                addUIDToGroup($uid, "User admins");
            } 
            if ($radminsurvey == 1) {
                addUIDToGroup($uid, "Poll admins");
            } 
            if ($radminsection == 1) {
                addUIDToGroup($uid, "Section admins");
            } 
            if ($radminlink == 1) {
                addUIDToGroup($uid, "Autolinks admins");
                addUIDToGroup($uid, "Banners admins");
                addUIDToGroup($uid, "Weblinks admins");
            } 
            if ($radminephem == 1) {
                addUIDToGroup($uid, "Ephemerids admins");
                addUIDToGroup($uid, "Quotes admins");
            } 
            if ($radminfaq == 1) {
                addUIDToGroup($uid, "FAQ admins");
            } 
            if ($radmindownload == 1) {
                addUIDToGroup($uid, "Download admins");
            } 
            if ($radminreview == 1) {
                addUIDToGroup($uid, "Review admins");
            } 
            if ($radminblocks == 1) {
                addUIDToGroup($uid, "Block admins");
            } 
        } 
    } 
    // Update aid in stories and in autonews with uid
    $column = $pntable['users_column'];
    $query = "SELECT $column[uid], $column[uname] FROM $pntable[users]";
    $result = $dbconn->Execute($query);
    while (list($uid, $uname) = $result->fields) {
        $colsto = $pntable['stories_column'];
        $dbconn->Execute("UPDATE $pntable[stories]
                               SET $colsto[aid]= '$uid'
                             where $colsto[aid]= '$uname'");
        $colauto = $pntable['autonews_column'];
        $dbconn->Execute("UPDATE $pntable[autonews]
                               SET $colauto[aid]= '$uid'
                             where $colauto[aid]= '$uname'");
        $result->MoveNext();
    } 
    echo "Stories Authors Updated"; 
    // Tell them what happened - important, especially for migrations
    echo '<H1>Migrated Admins to User Table</H1>';

    if (!empty($newusers)) {
        echo 'The following users have been created in the users table: ';
        echo implode(", ", $newusers);
        echo '.  These users should log in as themselves and set their user preferences accordingly.
              <P>';
    } 

    if (!empty($migratedusers)) {
        echo 'The following admins already had user accounts: ';
        echo implode(", ", $migratedusers);
        echo '.  It should be confirmed that these user accounts belong to the relevant admin.
              <B>
              THESE USERS HAVE ADMINISTRATOR LEVEL ACCESS TO THIS SYSTEM
              </B><P>';
    } 
} 
// Utility function for above
function addUIDToGroup($uid, $gname)
{ 
    // Add a uid to a group
    global $dbconn, $pntable; 
    // Shorthand for columns
    $groupscolumn = $pntable['groups_column'];
    $groupmembershipcolumn = $pntable['group_membership_column']; 
    // Get the group ID
    $query = "SELECT $groupscolumn[gid]
              FROM $pntable[groups]
              WHERE $groupscolumn[name] = '$gname'";
    $result = $dbconn->Execute($query);
    // ADODBtag list+row
    list($gid) = $result->fields; 
    // Add the UID
    $query = "INSERT INTO $pntable[group_membership]
              ($groupmembershipcolumn[gid],
               $groupmembershipcolumn[uid])
              VALUES ($gid, $uid)";
    $dbconn->Execute($query);
} 

migrateAdminUsers();
// Ditch authors table
$dbconn->Execute("DROP TABLE $pntable[authors]");
// Alter users_msnm lenght because is too short (bug #461425)
$dbconn->Execute("ALTER TABLE $pntable[users] CHANGE user_msnm user_msnm varchar(255)");
// Alter Topics image legnth because is too short #469023
$dbconn->Execute("ALTER TABLE $pntable[topics] CHANGE topicname topicname varchar(255), topicimage topicimage varchar(255), topictext topictext varchar(255)");
// Blocks Table
$dbconn->Execute("CREATE TABLE " . $prefix . "_userblocks (uid int(11) NOT NULL, bid int(10) NOT NULL, active tinyint(3) DEFAULT '1' NOT NULL, last_update timestamp(14))");
// Languag Tables
$dbconn->Execute("CREATE TABLE " . $prefix . "_languages_translation (
  language varchar(32) NOT NULL default '',
  constant varchar(32) NOT NULL default '',
  translation varchar(255) NOT NULL default '',
  level tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (constant,language))");

$dbconn->Execute("CREATE TABLE " . $prefix . "_languages_file (
  target varchar(64) NOT NULL default '',
  source varchar(64) NOT NULL default '',
  PRIMARY KEY  (target,source),
  UNIQUE KEY source (source))");

$dbconn->Execute("CREATE TABLE " . $prefix . "_languages_constant (
  constant varchar(32) NOT NULL default '',
  file varchar(64) NOT NULL default '',
  PRIMARY KEY  (constant))");
// Alter sessions table to fix #472187
// $dbcon->Execute("ALTER TABLE ".$prefix."_session ADD PRIMARY KEY(host_addr)");
// Survey has been renamed to Poll
$dbconn->Execute("UPDATE $pntable[blocks] SET title=\"Poll\" where title=\"Survey\")");

?>