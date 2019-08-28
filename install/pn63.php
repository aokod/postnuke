<?php 
// File: $Id: pn63.php 15630 2005-02-04 06:35:42Z jorg $
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
// proca check for existence of tables
function mysql_table_exists($table, $db)
{
    $tables = mysql_list_tables($db);
    while (list($temp) = mysql_fetch_array($tables)) {
        if ($temp == $table) return 1;
    } 
    return 0;
} 

if (mysql_table_exists($prefix . "_links_subcategories", $dbname) == 1) {
    echo "existe";
} 
// bam-bam's headline modifications
mysql_query("ALTER TABLE " . $prefix . "_headlines ADD rssuser VARCHAR(10) AFTER sitename");
mysql_query("ALTER TABLE " . $prefix . "_headlines ADD rsspasswd VARCHAR(10) AFTER rssuser");
mysql_query("ALTER TABLE " . $prefix . "_headlines ADD use_proxy TINYINT(3) DEFAULT '0' NOT NULL AFTER rsspasswd");
mysql_query("ALTER TABLE " . $prefix . "_headlines ADD maxrows TINYINT(3) DEFAULT '10' NOT NULL AFTER rssurl");
mysql_query("ALTER TABLE " . $prefix . "_headlines ADD options VARCHAR(20) NOT NULL AFTER siteurl");
// karateka's advanced stats
mysql_query("CREATE TABLE " . $prefix . "_stats_date (
date varchar(80) NOT NULL default '',
hits int(11) unsigned NOT NULL default '0'
)");

mysql_query("CREATE TABLE " . $prefix . "_stats_hour (
hour tinyint(2) unsigned NOT NULL default '0',
hits int(10) unsigned NOT NULL default '0'
)");

mysql_query("CREATE TABLE " . $prefix . "_stats_month (
month tinyint(2) unsigned NOT NULL default '0',
hits int(10) unsigned NOT NULL default '0'
)");

mysql_query("CREATE TABLE " . $prefix . "_stats_week (
weekday tinyint(1) unsigned NOT NULL default '0',
hits int(10) unsigned NOT NULL default '0'
)");

mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '0', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '1', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '2', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '3', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '4', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '5', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '6', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '7', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '8', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '9', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '10', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '11', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '12', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '13', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '14', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '15', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '16', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '17', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '18', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '19', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '20', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '21', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '22', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_hour (hour, hits) VALUES ( '23', '0')");

mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '1', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '2', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '3', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '4', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '5', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '6', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '7', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '8', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '9', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '10', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '11', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_month (month, hits) VALUES ( '12', '0')");

mysql_query("INSERT INTO " . $prefix . "_stats_week (weekday, hits) VALUES ( '0', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_week (weekday, hits) VALUES ( '1', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_week (weekday, hits) VALUES ( '2', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_week (weekday, hits) VALUES ( '3', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_week (weekday, hits) VALUES ( '4', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_week (weekday, hits) VALUES ( '5', '0')");
mysql_query("INSERT INTO " . $prefix . "_stats_week (weekday, hits) VALUES ( '6', '0')");

mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('autolink', 'AutoLink', ' http://www.nusite.de', 'AutoLinking for PostNuke')");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('postnuke', 'PostNuke', ' http://www.postnuke.com', 'PostNuke Portal System')");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('gpl', 'GPL', ' http://www.gnu.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('linux', 'Linux.com', ' http://www.linux.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('gnu', 'GNU Project', ' http://www.gnu.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('cnn', 'CNN.com', ' http://www.cnn.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('news.com', 'News.com', ' http://www.news.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('ibm', 'IBM', ' http://www.ibm.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('php', 'PHP HomePage', ' http://www.php.net', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('redhat', 'Red Hat', ' http://www.redhat.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('sourceforge', 'SourceForge', ' http://www.sourceforge.net', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('palm pilot', 'Palm Pilot', ' http://www.palm.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('microsoft', 'Microsoft', ' http://www.microsoft.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('kernel', 'Linux Kernel Archives', ' http://www.kernel.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('open source', 'Opensource', ' http://www.opensource.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('nuke', 'PostNuke', ' http://www.postnuke.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('compaq', 'Compaq', ' http://www.compaq.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('intel', 'Intel', ' http://www.intel.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('mysql', 'MysQL Database server', ' http://www.mysql.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('apple', 'Apple', ' http://www.apple.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('apache', 'Apache Web server', ' http://www.apache.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('google', 'Google search Engine', ' http://www.google.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('translate', 'Babelfish Translator', ' http://babelfish.altavista.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('w3', 'W3 Consortium', ' http://www.w3.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('html', 'HTML standard', ' http://www.w3.org/MarkUp', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('xhmtl', 'XHTML standard', ' http://www.w3.org/MarkUp', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('sun microsystems', 'Sun Microsystems', ' http://www.sun.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('oracle', 'Oracle', ' http://www.oracle.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('postgre', 'PostgresQL', ' http://www.postgresql.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('mp3', 'MP3.com', ' http://www.mp3.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('mozilla', 'Mozilla', ' http://www.mozilla.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('netscape', 'Netscape', ' http://www.netscape.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('slashdot', 'Slashdot', ' http://www.slashdot.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('spam', 'Spam Cop', ' http://www.spamcop.net', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('aol', 'America Online', ' http://www.aol.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('sony', 'Sony HomePage', ' http://www.sony.com', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('x-free', 'X-Free86 Project', ' http://www.xfree.org', NULL)");
mysql_query("INSERT INTO " . $prefix . "_autolinks (keyword, title, url, comment) VALUES ('amazon', 'Amazon.com', ' http://www.amazon.com', NULL)");
// john's changes
mysql_query("ALTER TABLE " . $prefix . "_banner CHANGE imageurl imageurl VARCHAR(255) NOT NULL");
mysql_query("ALTER TABLE " . $prefix . "_banner CHANGE clickurl clickurl VARCHAR(255) NOT NULL");

$q = "
    CREATE TABLE " . $prefix . "_temp_links_categories
    (
        cat_id int(11) NOT NULL auto_increment,
        parent_id int(11),
        title varchar(50) NOT NULL,
        cdescription text,
        PRIMARY KEY (cat_id)
    )
    ";
$result = mysql_query($q);

$q = "
    CREATE TABLE " . $prefix . "_temp_links_links
    (
        lid int(11) NOT NULL auto_increment,
        cat_id int(11),
        title varchar(100) NOT NULL,
        url varchar(100) NOT NULL,
        description text NOT NULL,
        date datetime,
        name varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        hits int(11) NOT NULL,
        submitter varchar(60) NOT NULL,
        linkratingsummary double(6,4) DEFAULT '0.0000' NOT NULL,
        totalvotes int(11) NOT NULL,
        totalcomments int(11) NOT NULL,
        PRIMARY KEY (lid)
    )
    ";
$result = mysql_query($q);

$q = "ALTER TABLE " . $prefix . "_links_newlink CHANGE cid cat_id INT(11) NOT NULL";
$result = mysql_query($q);

$result = mysql_query("SELECT cid,title,cdescription FROM " . $prefix . "_links_categories");
while (list($cid, $title, $cdescription) = @mysql_fetch_row($result)) {
    if (!get_magic_quotes_runtime()) {
        $title = addslashes($title);
    } 
    mysql_query("INSERT INTO " . $prefix . "_temp_links_categories VALUES ('',0,'$title','$cdescription')");
    $newcid = mysql_insert_id();
    $result2 = mysql_query("SELECT title, url, description, date, name, email, hits, submitter, linkratingsummary, totalvotes, totalcomments FROM " . $prefix . "_links_links WHERE cid=$cid AND sid=0");
    while (list($ltitle, $url, $description, $date, $name, $email, $hits, $submitter, $linkratingsummary, $totalvotes, $totalcomments) = mysql_fetch_row($result2)) {
        if (!get_magic_quotes_runtime()) {
            $ltitle = addslashes($ltitle);
            $description = addslashes($description);
            $name = addslashes($name);
            $email = addslashes($email);
        } 
        mysql_query("INSERT INTO " . $prefix . "_temp_links_links VALUES ('','$newcid','$ltitle','$url','$description','$date','$name','$email','$hits','$submitter','$linkratingsummary','$totalvotes','$totalcomments')");
    } 
    // if links_subcategories exists dump the data (proca).
    if (mysql_table_exists($prefix . "_links_subcategories", $dbname) == 1) {
        $result3 = mysql_query("SELECT sid,title FROM " . $prefix . "_links_subcategories WHERE cid=$cid");
        while (list($sid, $stitle) = mysql_fetch_row($result3)) {
            if (!get_magic_quotes_runtime()) {
                $stitle = addslashes($stitle);
            } 
            mysql_query("INSERT INTO " . $prefix . "_temp_links_categories VALUES ('','$newcid','$stitle','')");
            $newscid = mysql_insert_id();
            $result4 = mysql_query("SELECT lid, title, url, description, date, name, email, hits, submitter, linkratingsummary, totalvotes, totalcomments FROM " . $prefix . "_links_links where cid=$cid and sid=$sid");

            while (list($slid, $sltitle, $surl, $sdescription, $sdate, $sname, $semail, $shits, $ssubmitter, $slinkratingsummary, $stotalvotes, $stotalcomments) = mysql_fetch_row($result4)) {
                if (!get_magic_quotes_runtime()) {
                    $sltitle = addslashes($sltitle);
                    $sdescription = addslashes($sdescription);
                    $sname = addslashes($sname);
                    $semail = addslashes($semail);
                } 
                mysql_query("INSERT INTO " . $prefix . "_temp_links_links VALUES ('$slid','$newscid','$sltitle','$surl','$sdescription','$sdate','$sname','$semail','$shits','$ssubmitter','$slinkratingsummary','$stotalvotes','$stotalcomments')");
            } 
        } 
    } 
} 
// Drop old tables
mysql_query("DROP TABLE " . $prefix . "_links_categories");
mysql_query("DROP TABLE " . $prefix . "_links_links");
mysql_query("DROP TABLE " . $prefix . "_links_subcategories"); 
// Rename temporary tables to new tables
mysql_query("ALTER TABLE " . $prefix . "_temp_links_categories RENAME " . $prefix . "_links_categories");
mysql_query("ALTER TABLE " . $prefix . "_temp_links_links RENAME " . $prefix . "_links_links");

?>