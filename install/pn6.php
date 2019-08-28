<?php 
// File: $Id: pn6.php 15630 2005-02-04 06:35:42Z jorg $
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
function UpdateLanguageSystem()
{
    global $prefix; 
    // New language system
    // languages
    $lang[arabic] = 'ara';
    $lang[chinese] = 'zho';
    $lang[czech] = 'ces';
    $lang[danish] = 'dan';
    $lang[dutch] = 'nld';
    $lang[english] = 'eng';
    $lang[esperanto] = 'epo';
    $lang[finnish] = 'fin';
    $lang[french] = 'fra';
    $lang[german] = 'deu';
    $lang[hungarian] = 'hun';
    $lang[icelandic] = 'isl';
    $lang[italian] = 'ita';
    $lang[japanese] = 'jpn';
    $lang[korean] = 'kor';
    $lang[malay] = 'mas';
    $lang[portuguese] = 'por';
    $lang[russian] = 'rus';
    $lang[russian_koi8r] = 'x_rus_koi8r';
    $lang[spanish] = 'spa';
    $lang[swedish] = 'swe';
    $lang['brazilian-portuguese'] = 'x_brazilian_portuguese'; 
    // tables
    $tab[autonews] = 'alanguage';
    $tab[blocks] = 'blanguage';
    $tab[ephem] = 'elanguage';
    $tab[faqcategories] = 'flanguage';
    $tab[message] = 'mlanguage';
    $tab[poll_desc] = 'planguage';
    $tab[queue] = 'alanguage';
    $tab[reviews] = 'rlanguage';
    $tab[reviews_add] = 'rlanguage';
    $tab[seccont] = 'slanguage';
    $tab[stories] = 'alanguage';

    $warn = false;
    print '<hr><ul>';
    foreach ($tab as $tk => $tv) {
        $flag = false;
        print "<li>" . _UPDATING . $tk . ".. ";
        foreach ($lang as $k => $v) {
            if (!mysql_query("UPDATE " . $prefix . "_$tk SET $tv='$v' WHERE $tv='$k'")) {
                print mysql_error() . '<br>';
            } else {
                if (mysql_affected_rows()) {
                    $flag = true;
                } 
            } 
        } 
        if ($flag) {
            $warn = true;
            print _DONE . "</li>";
        } else {
            print _SKIPPED . "</li>";
        } 
    } 
    print '</ul>';
    if ($warn) {
        print '<center class="pn-pagetitle">' . _PN6_1 . '</center><br><center class="pn-title">' . _PN6_2 . '</center>';
    } 
} 

/**
 * Cat Title increased
 */
mysql_query("ALTER TABLE " . $prefix . "_stories_cat MODIFY title varchar(40) NOT NULL DEFAULT ''");

/**
 * Referers Addition
 */
mysql_query("ALTER TABLE " . $prefix . "_referer ADD COLUMN frequency INT(15) AFTER url");

/**
 * Update for the Authors Permissions
 */
mysql_query("ALTER TABLE " . $prefix . "_authors ADD COLUMN radminblocks tinyint(2) DEFAULT '0' NOT NULL AFTER radminreviews");

/**
 * Update for the AutoLink Mod
 */
mysql_query("CREATE TABLE " . $prefix . "_autolinks (
lid int(11) NOT NULL auto_increment,
keyword varchar(100) NOT NULL,
title varchar(100) NOT NULL,
url varchar(200) NOT NULL,
comment varchar(200),
PRIMARY KEY (lid),
UNIQUE keyword (keyword)
)");

/**
 * Update stories for the comments selection
 */
mysql_query("ALTER TABLE " . $prefix . "_stories ADD withcomm int(1) DEFAULT '0' NOT NULL AFTER alanguage");
mysql_query("ALTER TABLE " . $prefix . "_autonews ADD withcomm int(1) DEFAULT '0' NOT NULL AFTER alanguage");

mysql_query("ALTER TABLE " . $prefix . "_queue ADD arcd INT (1) DEFAULT '0' not null AFTER uid");
mysql_query("ALTER TABLE " . $prefix . "_users ADD timezone_offset float(3,1) DEFAULT '0.0' NOT NULL");

mysql_query("CREATE TABLE " . $prefix . "_blocks_buttons (
id int(10) unsigned NOT NULL auto_increment,
bid int(10) unsigned DEFAULT '0' NOT NULL,
title varchar(255) NOT NULL,
url varchar(255) NOT NULL,
images longtext NOT NULL,
PRIMARY KEY (id)
)") or die ("<b>" . _NOTMADE . $prefix . "_blocks_buttons</b>");

print "<font class=\"pn-normal\">Converting old-style button blocks...<ul>";
$result = mysql_query("SELECT bid, title, url FROM " . $prefix . "_blocks WHERE bkey='button' ORDER BY title");
while ($row = mysql_fetch_array($result)) {
    if (mysql_num_rows(mysql_query("SELECT id FROM " . $prefix . "_blocks_buttons WHERE bid=$row[bid]"))) { // already converted...
        continue;
    } 
    print "<li>$row[title]... ";
    if (!($row[url] && file_exists("data/$row[url]"))) {
        echo _PN6_3 . "data/" . $row[url] . "</li>\n";
        continue;
    } 
    require "data/$row[url]";
    foreach($buttons as $v) {
        $image = '';
        $flag = false;
        if (is_array($v[img])) {
            foreach ($v[img] as $v2) {
                if ($flag) {
                    $image .= '|';
                } 
                $image .= $v2;
                $flag = true;
            } 
        } else {
            $image = $v[img];
        } 
        $v[title] = addslashes($v[title]);
        mysql_query("INSERT INTO " . $prefix . "_blocks_buttons (id, bid, title, url, images) VALUES (NULL, $row[bid], '$v[title]', '$v[url]', '$image')");
    } 
    print _DONE . "</li>\n";
} 
print '</ul>' . _PN6_4;

mysql_query("ALTER TABLE " . $prefix . "_faqcategories ADD parent_id TINYINT (3) DEFAULT '0' NOT NULL");
mysql_query("ALTER TABLE " . $prefix . "_faqanswer ADD submittedby VARCHAR(250) DEFAULT '' NOT NULL");

UpdateLanguageSystem();

?>