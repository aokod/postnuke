<?php
// Generated: $d$ by $id$
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by The PostNuke Development Team.
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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Everyone
// Purpose of file: Translation files
// Translation team: Read credits in /docs/CREDITS.txt
// ----------------------------------------------------------------------
// taken from global definitions for compatibility with installer 
$sitename = pnConfigGetVar('sitename');
$anonwaitdays = pnConfigGetVar('anonwaitdays');
define('_IDREFER','in the HTML source is your site\'s ID number in '.$sitename.'\'s database. Please make sure that this number is present.');
define('_RATENOTE2ERROR','You have already voted for this link in the past '.$anonwaitdays.' day(s).');
define('_DOWNLOADSPAGE','Number of downloads per page');
define('_ANONWAITDAYS','Number of days unregistered users are required to wait to vote on a link');
define('_OUTSIDEWAITDAYS','Number of days outside users (voting from other sites) are required to wait before voting on a link');
define('_USEOUTSIDEVOTING', 'Allow webmasters to put vote links on their site');
define('_ANONWEIGHT', 'Number of unregistered-user votes equaling 1 registered-user vote');
define('_OUTSIDEWEIGHT', 'Number of outside-user votes equaling 1 registered-user vote');
define('_DETAILVOTEDECIMAL', 'Number of decimal places   allowed in detailed vote summary.');
define('_MAINVOTEDECIMAL', 'Number of decimal places allowed in main vote summary.');
define('_TOPDOWNLOADSPERCENTRIGGER', 'Enter 1 to show top downloads as a percentage (otherwise, the number of links is displayed)');
define('_TOPDOWNLOADS', 'Top downloads: either the number of links to display or the percentage to show  (enter a whole number, such as 25/100)');
define('_MOSTPOPDOWNLOADSPERCENTRIGGER', 'Enter 1 to show most-popular downloads as a percentage (otherwise, the number of links is displayed)');
define('_MOSTPOPDOWNLOADS', 'Most popular: either a number of links or a percentage to display (enter a whole number, such as 25/100)');
define('_FEATUREBOX', 'Show featured link box on links main page?');
define('_LINKVOTEMIN', 'Number votes required in order to be in the \'Top 10\' list');
define('_BLOCKUNREGMODIFY', 'Do not allow unregistered users to suggest links changes');
define('_TOBEPOPULAR', 'Hits required in order to be popular');
define('_DOWNLOADSASNEW', 'Number of times downloaded up to which still considered new');
define('_DOWNLOADSASBEST', 'Number of times downloaded after which to qualify as best');
define('_DOWNLOADSINRES', 'Number of downloads in search results');
//define('_DOWNLOADSINRES', 'Downloads in search results');

define('_ADDADOWNLOAD','Add a new download');
define('_ADDDOWNLOAD','Add download');
define('_ADDMAINCATEGORY','Add main category');
define('_ADDNEWDOWNLOAD','Add a new download');
define('_ADDSUBCATEGORY','Add sub-category');
define('_ADDTHISFILE','Add this file');
define('_ANONPOSTDOWNLOADS','Let unregistered users post new downloads');
define('_AUTHOREMAIL','Author\'s e-mail address');
define('_AUTHORNAME','Author\'s name');
define('_BROKENDOWNLOADSREP','Broken download  reports');
define('_CHECK','Check');
define('_CHECKALLDOWNLOADS','Check ALL downloads');
define('_CHECKSUBCATEGORIES','Check sub-categories');
define('_CLEANDOWNLOADSDB','Clean download votes');
define('_DBESTRATED','Top-rated downloads - top');
define('_DCATLAST3DAYS','New downloads in this category added during the last 3 days');
define('_DCATNEWTODAY','New downloads in this category added today');
define('_DCATTHISWEEK','New downloads in this category added this week');
define('_DDATE1','Date (oldest downloads listed first)');
define('_DDATE2','Date (new downloads listed first)');
define('_DDELCATWARNING','WARNING: Are you sure you want to delete this category and ALL its downloads?');
define('_DDELETEINFO','Delete (deletes <strong><em>broken download</em></strong> and <strong><em>requests</em></strong> for a given download)');
define('_DIGNOREINFO','Ignore (deletes all <strong><em>requests</em></strong> for a given download)');
define('_DLALSOAVAILABLE','Downloads also available in');
define('_DLETSDECIDE','Input from users such as yourself will help other visitors better decide which downloads to click on.');
define('_DLOADPAGETITLE','Downloads');
define('_DNOREPORTEDBROKEN','No reported broken downloads.');
define('_DONLYREGUSERSMODIFY','Only registered users may modify a download');
define('_DOWNLOAD','Download');
define('_DOWNLOADALREADYEXT','ERROR: This URL is already listed in the database!');
define('_DOWNLOADCOMMENTS','Download comments');
define('_DOWNLOADID','Download ID');
define('_DOWNLOADMODREQUEST','Download modification requests');
define('_DOWNLOADNAME','Program name');
define('_DOWNLOADNODESC','ERROR: You need to type a description for your URL!');
define('_DOWNLOADNOTITLE','ERROR: You need to type a title for your URL!');
define('_DOWNLOADNOURL','ERROR: You need to type a URL for your download!');
define('_DOWNLOADNOW','Download this file now!');
define('_DOWNLOADOWNER','Download owner');
define('_DOWNLOADPROFILE','Download file name');
define('_DOWNLOADRATING','Downloads rating');
define('_DOWNLOADRATINGDET','Download rating details');
define('_DOWNLOADRECEIVED','Thank you! Your download submission has been received.');
define('_DOWNLOADSACCESSNOAUTH','Sorry! You do not have authorization to access downloads');
define('_DOWNLOADSADDNOAUTH','Sorry! You do not have authorization to add downloads');
define('_DOWNLOADSCATADDNOAUTH','Sorry! You do not have authorization to add a download category');
define('_DOWNLOADSCATDELNOAUTH','Sorry! You do not have authorization to delete that download category');
define('_DOWNLOADSCATEDITNOAUTH','Sorry! You do not have authorization to edit that download category');
define('_DOWNLOADSCONF','Downloads configuration');
define('_DOWNLOADSINDB','Downloads in the database');
define('_DOWNLOADSMAIN','Main download area');
define('_DOWNLOADSMAINCAT','Main download categories');
define('_DOWNLOADSNOCATS','No download categories available - downloads are currently disabled');
define('_DOWNLOADSNOTUSER1','Sorry! You are not a registered user or you have not logged in.');
define('_DOWNLOADSNOTUSER2','If you were registered you could add downloads on this web site.');
define('_DOWNLOADSNOTUSER3','Becoming a registered user is a quick and easy process.');
define('_DOWNLOADSNOTUSER4','Why is registration required for access to certain features?');
define('_DOWNLOADSNOTUSER5','So that you are offered only the highest-quality content,');
define('_DOWNLOADSNOTUSER6','Each submission is individually reviewed and approved.');
define('_DOWNLOADSNOTUSER7','Only worthwhile downloads are offered by this site.');
define('_DOWNLOADSNOTUSER8','<a href=\'user.php\'>You can register for an account here</a>');
define('_DOWNLOADSWAITINGVAL','Downloads awaiting validation');
define('_DOWNLOADTITLE','Download title');
define('_DOWNLOADVALIDATION','Download validation');
define('_DOWNLOADVOTE','Vote!');
define('_DPOSTPENDING','All downloads are posted pending verification.');
define('_DRATENOTE4','You can <a href=\'index.php?name=Downloads&amp;req=TopRated\'>view a list of the top-rated downloads here</a>.');
define('_DSUBMITONCE','Please submit each download only once.');
define('_DTOTALFORLAST','Total new downloads for last');
define('_DUSERMODREQUEST','User download modification requests');
define('_DUSERREPBROKEN','User reports of broken downloads');
define('_EDITTHISDOWNLOAD','Edit this download');
define('_ERRORTHESUBCATEGORY','ERROR: The sub-category');
define('_FILESIZE','File size');
define('_FILEURL','File link');
define('_HOMEPAGE','Home page');
define('_IN','in');
define('_INBYTES','in bytes');
define('_INCLUDESUBCATEGORIES','(include sub-categories)');
define('_LINEBREAKWARN','Important note: line breaks in the URL\'s and form actions shown above are for readability only.  The URL\'s and form actions you submit should be on a single line.');
define('_MODDOWNLOAD','Modify a download');
define('_NEWDOWNLOADADDED','New download added to the database');
define('_NEWDOWNLOADS','New downloads');
define('_RATERESOURCE','Rate resource');
define('_REQUESTDOWNLOADMOD','Request download modification');
define('_RESSORTED','Resources currently sorted by');
define('_SORTDOWNLOADSBY','Sort downloads by');
define('_SUBCATEGORY','Sub-category');
define('_TOTALNEWDOWNLOADS','Total new downloads');
define('_TRATEDDOWNLOADS','total rated downloads');
define('_UDOWNLOADS','Downloads');
define('_VALIDATEDOWNLOADS','Validate downloads');
define('_VALIDATINGCAT','Validating category (and all sub-categories)');
define('_VALIDATINGSUBCAT','Validating sub-category');
define('_VERSION','Version');
define('_WEBDOWNLOADSADMIN','Download administration');
?>