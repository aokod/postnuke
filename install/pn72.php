<?php 
// File: $Id: pn72.php 15630 2005-02-04 06:35:42Z jorg $
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
// Purpose of file: Upgrade from PostNuke .72 to PostNuke .722 [Neo]
// ----------------------------------------------------------------------
// 
// Temp fixes for validation and correct update of 0.7.2.2

// Note that this is upgrade script uses ADODB instead
// of raw mysql

global $dbconn, $pntable, $prefix;
include_once 'install/pntables72.php';
include_once 'install/update_functions.php';
// Table structure changes first
// Change most pn_url to 254 instead of 255 to later support vfp
// add-statements right at the end
// Fixes for 0.7.2.2 installation prevents duplication and errors [Neo]
if (!CheckForField('pn_type', 'banner')) {
    $dbconn->Execute("ALTER TABLE $pntable[banner] ADD `pn_type` VARCHAR( 2 ) DEFAULT '0' NOT NULL AFTER `pn_cid`");
} 
// || die  ("<b>"._NOTUPDATED."$pntable[banner]</b>");
// Verfy existance prior to alter Neo
$pnValConfigVar = $dbconn->Execute("SELECT pn_format_type FROM " . $prefix . "_stories
                              WHERE pn_sid >0 LIMIT 1");
if (!$pnValConfigVar) {
    $dbconn->Execute("ALTER TABLE $pntable[stories] ADD pn_format_type tinyint(1) unsigned NOT NULL DEFAULT '0'") || die("<b>" . _NOTUPDATED . "$pntable[stories]</b>");
} 
// Now lets get the module_vars up to date.
$pnValConfigVar = $dbconn->Execute("SELECT pn_value FROM " . $prefix . "_module_vars
                              WHERE pn_name='pnAntiCracker' AND pn_modname='/PNConfig'");

if (!$pnValConfigVar) {
    $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars
                    (pn_modname,
                     pn_name,
                     pn_value)
                   VALUES
                     ('/PNConfig',
                      'pnAntiCracker',
                      '" . serialize(0) . "')") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
} 

$pnValConfigVar = $dbconn->Execute("SELECT pn_value FROM " . $prefix . "_module_vars
                              WHERE pn_name='WYSIWYGEditor' AND pn_modname='/PNConfig'");
if (!$pnValConfigVar) {
    $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars
                    (pn_modname,
                     pn_name,
                     pn_value)
                   VALUES
                     ('/PNConfig',
                      'WYSIWYGEditor',
                      '" . serialize(0) . "')") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
} 

$pnValConfigVar = $dbconn->Execute("SELECT pn_value FROM " . $prefix . "_module_vars
                              WHERE pn_name='UseCompression' AND pn_modname='/PNConfig'");
if (!$pnValConfigVar) {
    $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars
                    (pn_modname,
                     pn_name,
                     pn_value)
                   VALUES
                     ('/PNConfig',
                      'UseCompression',
                      '" . serialize(0) . "')") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
} 

$pnValConfigVar = $dbconn->Execute("SELECT pn_value FROM " . $prefix . "_module_vars
                              WHERE pn_name='htmlentities' AND pn_modname='/PNConfig'");
if (!$pnValConfigVar) {
    $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars
                    (pn_modname,
                     pn_name,
                     pn_value)
                   VALUES
                     ('/PNConfig',
                      'htmlentities',
                      '" . serialize(1) . "')") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
} 

$pnValConfigVar = $dbconn->Execute("SELECT pn_value FROM " . $prefix . "_module_vars
                              WHERE pn_name='collapseable' AND pn_modname='Blocks'");
if (!$pnValConfigVar) {
    $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars
                    (pn_modname,
                     pn_name,
                     pn_value)
                   VALUES
                     ('Blocks',
                      'collapseable',
                      '" . serialize(1) . "')") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
} 

$pnValConfigVar = $dbconn->Execute("SELECT pn_value FROM " . $prefix . "_module_vars
                              WHERE pn_name='storyorder' AND pn_modname='/PNConfig'");
if (!$pnValConfigVar) {
    $dbconn->Execute("INSERT INTO " . $prefix . "_module_vars
                    (pn_modname,
                     pn_name,
                     pn_value)
                   VALUES
                     ('/PNConfig',
                      'storyorder',
                      '" . serialize(0) . "')") || die("<b>" . _NOTUPDATED . $prefix . "_modules</b>");
} 
// magicx: check if the quotes table is present and add it if not
$pnValConfigVar = $dbconn->Execute("SELECT * FROM " . $prefix . "_quotes");
if (!$pnValConfigVar) {
    $dbconn->Execute("CREATE TABLE " . $prefix . "_quotes (
  pn_qid int(10) unsigned NOT NULL auto_increment,
  pn_quote text,
  pn_author varchar(150) NOT NULL default '',
  PRIMARY KEY  (pn_qid)");
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
$pnValConfigVar = $dbconn->Execute("SELECT * FROM " . $prefix . "_blocks
                              WHERE pn_title='Reminder' AND pn_bkey='html'");
if (!$pnValConfigVar) {
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
} 
*/

?>