<?php
// $Id: global.php 19963 2006-09-03 21:38:05Z larsneo $
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
define('_REG_QUESTION', 'Spamprotect Question');
define('_REG_ANSWER', 'Spamprotect Answer');
define('_REG_QUESTIONDESC', '(set an individual question to protect against spam-registrations)');
define('_REG_ANSWERDESC', '(answer for the spamprotect question during user registration)');
define('_ILLEGALUSERAGENTS', 'Banned Useragents');
define('_ILLEGALUSERAGENTDESC', 'Comma seperated list of useragents from which registration will not be allowed');
define('_LOGIN_REDIRECT_WCAG', 'WCAG-compliant Log-in/Log-off');
define('_LOGIN_REDIRECT_DESC', 'use of meta-refresh');
define('_COOKIEHINTFORLOGIN', 'Cookies must be enabled past this point.');
define('_USERALLOWCOOKIES', 'Please allow Cookies for this site!');
define('_USERREDIRECT', 'Continue');
define('_IDNNAMES','IDN-Domains:');
define('_IDNNAMESDESC', 'Allow special characters in email addresses and URLs');
define('_ADDFIELD','Add fields');
define('_ADDINSTRUCTIONS','Example: _MYINT -- You must create a define for this variable in language/eng/global.php (or the global.php file in the language directory for the appropriate language, such as language/fra/global.php, etc.)');
define('_ADDUSER','Add a new user');
define('_ADDUSERBUT','Add user');
define('_ALLOWREG','Allow new user registrations:');
define('_ALLOWUSERS','Allow other users to view your e-mail address');
define('_ANONYMOUSNAME','Default name of unregistered user');
define('_BIO','Extra information');
define('_DELETEFIELD','Delete field and data');
define('_DELETEUSER','Delete user');
define('_DYNAMICDATA','Dynamic user data');
define('_EDITUSER','Edit user');
define('_ERRORINVURL','Sorry! Error in URL! This could be because there is a space in the URL');
define('_FIELDACTIVE','Active');
define('_FIELDLABEL','Field label');
define('_FIELDLENGTH','Length');
define('_FIELDTYPE','Data type');
define('_FIELDVALIDATION','Validation');
define('_FIELDWEIGHT','Weight');
define('_FIELD_ACTIVATE','Activate');
define('_FIELD_DEACTIVATE','Deactivate');
define('_FIELD_DEL_SURE','Are you sure you want to delete all data for this field?');
define('_FIELD_NA','N/A');
define('_FIELD_NOEXIST','Sorry! Field does not exist');
define('_FIELD_REQUIRED','Required');
define('_FORCHANGES','(For changes only)');
define('_GROUP','Group');
define('_GROUPMEMBERSHIP','Group Membership');
define('_IFNO','(If you specify \'No\', please state reasons below)');
define('_ILLEGALMAILDOMAINS', 'Banned mail domains');
define('_ILLEGALDOMAINDESC', 'Comma seperated list of e-mail domains from which registration will not be allowed');
define('_ILLEGALUNAME','Reserved user names: ');
define('_ILLEGALUNAMEDESC',' (Enter names here that new users should not be able to register. Separate names with spaces)');
define('_INTERESTS','Interests');
define('_LAST10COMMENTS','Last 10 comments by');
define('_LAST10SUBMISSIONS','Last 10 news submissions  by');
define('_LOCATION','Location');
define('_LOGGINGYOU','Logging you in -- please wait!');
define('_LOGININCOR','Wrong user name or password. Please try again...');
define('_LOGINSITE','Log-in.');
define('_MEMBEROF','Member');
define('_MINAGE','Minimum age:');
define('_MINAGEDESCR','(Sets the required minimum age for registration; 0 = no age check)');
define('_MODIFYUSERSADDNOAUTH','Sorry! You do not have authorization to add a user');
define('_MODIFYUSERSDELNOAUTH','Sorry! You do not have authorization to delete a user');
define('_MODIFYUSERSEDITNOAUTH','Sorry! You do not have authorization to edit a user');
define('_MODIFYUSERSNOAUTH','Sorry! You do not have authorization to modify users');
define('_MYEMAIL','Your e-mail address:');
define('_MYHOMEPAGE','Your web site:');
define('_NEEDTOCOMPLETE','Sorry! Required information is missing. Please fill-in all the required fields');
define('_NOINFOFOR','There is no available information for');
define('_NOTALLOWREG','Sorry! New account registration is currently disabled');
define('_NOTIFYEMAIL','Receive notification of new user registrations: ');
define('_NOTIFYEMAILDESC',' (When a new user registers, an e-mail message will be sent to this address before the account is enabled. Leave blank for no e-mail notification)');
define('_OCCUPATION','Occupation');
define('_OFFLINE','Off-line.');
define('_OPTITEMS','Show optional fields');
define('_OPTITEMSDESC','(Show dynamic user data during registration)');
define('_PASSBYMAIL','(Send users their password by mail after registration)');
define('_PASSWDLEN','Minimum length for user passwords:');
define('_PASSWDNOMATCH','Sorry! The passwords you entered do not match. Please go back and enter the same password twice (this is required for verification)');
define('_REASONS','Reasons:');
define('_REGCONF','User registration configuration');
define('_REGDATE', 'Registration date');
define('_REGISTER','Register.');
define('_REGISTEREDUSER','Registered user #');
define('_RETRIEVEPASS','Retrieve lost password.');
define('_RETYPEPASSWD','Re-type password (for verification)');
define('_SELECTOPTION','Please select an option from the menu below:');
define('_STRING_INSTRUCTIONS','STRINGS ONLY: data length range (1,254)');
define('_SURE2DELETE','Are you sure you want to delete this user?');
define('_UDT_CORE','Core');
define('_UDT_FLOAT','Float');
define('_UDT_INTEGER','Integer');
define('_UDT_MANDATORY','Core-required');
define('_UDT_STRING','String');
define('_UDT_TEXT','Text');
define('_UNIEMAIL','Require that each e-mail address can only be registered once:');
define('_UNIEMAILDESC','(Require that each e-mail address can only be registered once for any registered user)');
define('_USERADMIN','User administration');
define('_USERCONF','User configuration');
define('_USEREXIST','Sorry! This user name has already been registered');
define('_USERGRAPHIC','Enable graphics in the user home page');
define('_USERID','User ID');
define('_USERLOGIN','Login');
define('_USERNOEXIST','Sorry! This user does not exist!');
define('_USERPATH','Path to user menu images');
define('_USERREGLOGIN','User log-in and registration');
define('_USERSTATUS','User\'s current status');
define('_USERUPDATE','Update user');
define('_VERIFYEMAIL','Verify e-mail address during registration: ');
define('_YOUARELOGGEDOUT','You are now logged-out!');

?>