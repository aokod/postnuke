<?php 
// File: $Id: language_update.php 15630 2005-02-04 06:35:42Z jorg $
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
// Original Author of file:
// Purpose of file: Include the functions needed for the table validation
// ----------------------------------------------------------------------
/**
 * Function: CheckForField
 * Purpose: Returns true if field exists, false otherwise
 */
// New language system
// languages
$lang['arabic'] = 'ara';
$lang['chinese'] = 'zho';
$lang['czech'] = 'ces';
$lang['danish'] = 'dan';
$lang['dutch'] = 'nld';
$lang['english'] = 'eng';
$lang['esperanto'] = 'epo';
$lang['finnish'] = 'fin';
$lang['french'] = 'fra';
$lang['german'] = 'deu';
$lang['hungarian'] = 'hun';
$lang['icelandic'] = 'isl';
$lang['italian'] = 'ita';
$lang['japanese'] = 'jpn';
$lang['korean'] = 'kor';
$lang['malay'] = 'mas';
$lang['portuguese'] = 'por';
$lang['russian'] = 'rus';
$lang['russian_koi8r'] = 'x_rus_koi8r';
$lang['spanish'] = 'spa';
$lang['swedish'] = 'swe';
$lang['brazilian-portuguese'] = 'x_brazilian_portuguese'; 
// tables
$tab['autonews'] = 'alanguage';
$tab['blocks'] = 'blanguage';
$tab['ephem'] = 'elanguage';
$tab['faqcategories'] = 'flanguage';
$tab['message'] = 'mlanguage';
$tab['poll_desc'] = 'planguage';
$tab['queue'] = 'alanguage';
$tab['reviews'] = 'rlanguage';
$tab['reviews_add'] = 'rlanguage';
$tab['seccont'] = 'slanguage';
$tab['stories'] = 'alanguage';

$warn = false;
print '<hr><ul>';
foreach ($tab as $tk => $tv) {
    $flag = false;
    print "<li>Updating table: $tk... ";
    foreach ($lang as $k => $v) {
        if (!mysql_query("UPDATE {$GLOBALS['prefix']}_$tk SET $tv='$v' WHERE $tv='$k'")) {
            print mysql_error() . '<br>';
        } else {
            if (mysql_affected_rows()) {
                $flag = true;
            } 
        } 
    } 
    if ($flag) {
        $warn = true;
        print 'Done!</li>';
    } else {
        print 'Skipped!</li>';
    } 
} 
print '</ul>';
if ($warn) {
    print '<center class="pn-pagetitle">Admin: You Will Need To Re-Save Your Website Settings In The Admin Page ASAP!</center><br><center class="pn-title">(We Are Sorry For This Inconvience)</center>';
} 

?>