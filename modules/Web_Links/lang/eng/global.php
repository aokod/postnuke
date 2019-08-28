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

define('_ADDALINK','Add a new link');
define('_ADDLINK','Add link');
define('_ADDNEWLINK','Add a new link');
define('_ANONPOSTLINKS','Enable unregistered users to post new links?');
define('_ANONWAITDAYS','Number of days unregistered users need to wait to vote on a link');
define('_ANONWEIGHT','Number of unregistered-user votes equivalent to 1 registered-user vote');
define('_BESTRATED','Top-rated links - top');
define('_BLOCKUNREGMODIFY','Prevent unregistered users from suggesting links changes');
define('_BROKENLINKSREP','Broken link reports');
define('_CHECKALLLINKS','Check ALL links');
define('_CLEANLINKSDB','Clean link votes');
define('_DATE1','Date (oldest links listed first)');
define('_DATE2','Date (newest links listed first)');
define('_DELCATWARNING','Confirmation prompt: are you sure you want to delete this category and ALL its links?');
define('_DELETEINFO','Delete (deletes <strong><em>broken link</em></strong> and <strong><em>requests</em></strong> for a given link)');
define('_DETAILVOTEDECIMAL','Number of decimal places to allow in detailed vote summary.');
define('_EDITTHISLINK','Edit this link');
define('_EMAILWHENADD','Thank you! You\'ll receive an e-mail message when it\'s approved.');
define('_FEATUREBOX','Show featured link box on web links main page?');
define('_IGNOREINFO','Ignore (deletes all <strong><em>requests</em></strong> for a given link)');
define('_INCAT','in category');
define('_LALSOAVAILABLE','Links also available in');
define('_LETSDECIDE','Input from users such as yourself will help other user better decide which links to visit.');
define('_LINK','Link');
define('_LINKALREADYEXT','Sorry! This URL is already listed in the database!');
define('_LINKCOMMENTS','Link comments');
define('_LINKID','Link ID');
define('_LINKMODREQUEST','Link modification requests');
define('_LINKNOCAT','Sorry! Error! No category');
define('_LINKSNOCATS','No web link categories available -- web links are currently disabled');
define('_LINKNODATA','Sorry! Error! No data');
define('_LINKNODESC','Sorry! Error! You must type a description for the URL!');
define('_LINKNOTITLE','Sorry! Error! You must type a title for the URL!');
define('_LINKNOURL','Sorry! Error! You must type a URL for the web link!');
define('_LINKOWNER','Link owner');
define('_LINKPAGETITLE','Web links');
define('_LINKPROFILE','Link profile');
define('_LINKRATING','Link rating');
define('_LINKRATINGDET','Link rating details');
define('_LINKRECEIVED','Thank you! Your link submission. has been received');
define('_LINKS','Links');
define('_LINKSASBEST','Number of links required to qualify as best');
define('_LINKSASNEW','Number of links as new');
define('_LINKSINDB','Links in the database');
define('_LINKSINRES','Links in search results');
define('_LINKSMAIN','Links main');
define('_LINKSMAINCAT','Main link categories');
define('_LINKSNOTUSER1','Sorry! You are not a registered user or you have not logged-in.');
define('_LINKSNOTUSER2','If you were registered, you could contribute links to this web site.');
define('_LINKSNOTUSER3','Becoming a registered user is a quick and easy process.');
define('_LINKSNOTUSER4','Why is registration required for access to certain features?');
define('_LINKSNOTUSER5','So that you are offered only the highest-quality content,');
define('_LINKSNOTUSER6','Each item is individually reviewed and approved.');
define('_LINKSNOTUSER7','The site editor aims to offer useful information only.');
define('_LINKSNOTUSER8','<a href="user.php">Register for a new user account</a>');
define('_LINKSPAGE','Links per page');
define('_LINKSWAITINGVAL','Links awaiting validation');
define('_LINKVALIDATION','Link validation');
define('_LINKVOTE','Vote!');
define('_LINKVOTEMIN','Number of votes required to qualify for the \'top 10\' list');
define('_MAINVOTEDECIMAL','Number of decimal places to allow in main vote summary');
define('_MODLINK','Modify a link');
define('_MOSTPOPLINKS','Most-popular links: either a number of links or the percentage to display (percentage as whole number, such as 25/100)');
define('_MOSTPOPLINKSPERCENTRIGGER','Enter 1 to show most-popular links as a percentage (otherwise, enter the number of links to show)');
define('_NEWLINKADDED','New link added to the database');
define('_NEWLINKS','New links');
define('_NOREPORTEDBROKEN','No reported broken links.');
define('_NOSUCHLINK','Sorry! There is no such link');
define('_ONLYREGUSERSMODIFY','Sorry! Only registered users can suggest link modifications. Please <a href="user.php">register for an account, or log-in</a>.');
define('_OUTSIDEWAITDAYS','Number of days outside users (voting from other sites) are required to wait before voting on a link');
define('_OUTSIDEWEIGHT','Number of outside-user votes equivalent to 1 registered-user vote?');
define('_PAGETITLE','Page title');
define('_PAGEURL','Page URL');
define('_POSTPENDING','All links are reviewed before being posted on-line.');
define('_RANDOM','Random');
define('_RATENOTE4','You can view a list of <a href="index.php?name='.$GLOBALS["ModName"].'&amp;req=TopRated">top-rated resources</a>.');
define('_REQUESTLINKMOD','Request link modification');
define('_SITESSORTED','Sites currently sorted by');
define('_SORTLINKSBY','Sort links by');
define('_SUBMITONCE','Please submit each link only once.');
define('_TEAM','Team.');
define('_THANKS4YOURSUBMISSION','Thank you for your submission!');
define('_TOBEPOPULAR','Number of hits required to qualify as popular');
define('_TOPLINKS','Leading links: either number of links or the  percentage of links to show (percentage as whole number, such as 25/100)');
define('_TOPLINKSPERCENTRIGGER','Enter 1 to display leading links as a percentage (otherwise, a number of links will be displayed)');
define('_TOTALFORLAST','Total new links over the last');
define('_TOTALNEWLINKS','Total new links');
define('_TRATEDLINKS','total number of rated links');
define('_USEOUTSIDEVOTING','Allow webmasters to put vote links on their site');
define('_USERMODREQUEST','User link modification requests');
define('_USERREPBROKEN','User-reported broken links');
define('_VALIDATELINKS','Validate links');
define('_VISITTHISSITE','Visit this web site');
define('_WEAPPROVED','Your link submission has been approved for the site\'s search engine.');
define('_WEBLINKSADDNOAUTH','Sorry! You do not have authorization to add web links');
define('_WEBLINKSADMIN','Web links administration');
define('_WEBLINKSCATADDNOAUTH','Sorry! You do not have authorization to add a web link category');
define('_WEBLINKSCATDELNOAUTH','Sorry! You do not have authorization to delete a web link category');
define('_WEBLINKSCATEDITNOAUTH','Sorry! You do not have authorization to edit a web link category');
define('_WEBLINKSCONF','Web links configuration');
define('_WEBLINKSDELNOAUTH','Sorry! You do not have authorization to delete a web link');
define('_WEBLINKSEDITNOAUTH','Sorry! You do not have authorization to edit a web link');
define('_WEBLINKSMODERATENOAUTH','Sorry! You do not have authorization to moderate this web link');
define('_WEBLINKSNOAUTH','Sorry! You do not have authorization to access web links');
define('_WL_DESCRIPTION','Description');
define('_YOUCANBROWSEUS','The site\'s search engine is available at:');
define('_YOUREMAIL','Your e-mail address');
define('_YOURLINKAT','Your link at');
?>