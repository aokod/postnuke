<?php
// $Id: admin.php 17825 2006-02-02 10:16:15Z markwest $
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

/**
 * @package     PostNuke_System_Modules
 * @subpackage  PostNuke_Admin
 * @license http://www.gnu.org/copyleft/gpl.html
*/

define('_ADMIN_MAGIC_QUOTES','Notice: <a href="http://php.net/magic_quotes">magic_quotes_gpc</a> is off!');
define('_ADMIN_REGISTER_GLOBALS','Notice: <a href="http://php.net/register_globals">register_globals</a> is on!');
define('_ADMIN_CONFIG_PHP','Notice: config.php is writable (hint: chmod 644 or chmod 444)!');
define('_ADMIN_CONFIG_OLD_PHP','Notice: config-old.php is writable (hint: chmod 644 or chmod 444)!');
define('_ADMIN_PNTEMP_HTACCESS','Notice: /pnTemp-folder should be secured with .htaccess.');
define('_ADMINCONTINUE','Continue');
define('_ADMININSTALLWARNING','Warning! Please remove the file  install.php and the folder called install from the root of your PostNuke web site before proceeding');
define('_ADMINPSAKWARNING', 'Warning! Please remove the Swiss army knife tool from the root of your PostNuke web site before proceeding');
define('_ADMIN', 'PostNuke administration panel');
define('_ADMINSYSTEMMODULES', 'System modules');
define('_ADMINCONTENTMODULES', 'Content modules');
define('_ADMINUTILITYMODULES', 'Utility modules');
define('_ADMINRESOURCEPACKMODULES', 'Resource pack');
define('_ADMINTHIRDPARTYMODULES', '3rd-party modules');
define('_ADMINUNCATEGORISEDMODULES',' Uncategorized modules');
define('_ADMINMODULESPERROW', 'Modules per row');
define('_ADMINNEW', 'New category');
define('_ADMINADMINVIEW', 'View categories');
define('_ADMINADDCATEGORY', 'Add category');
define('_ADMINNAME', 'Category name');
define('_ADMINDESCRIPTION', 'Category description');
define('_ADMINCATEGORYCREATED', 'Category created');
define('_ADMINUPDATEFAILED', 'Error! Sorry! Failed to update administration category');
define('_ADMINNOSUCHITEM', 'No such administration category');
define('_ADMINDELETEFAILED', 'Error! Sorry! Failed to delete administration category');
define('_ADMINCREATEFAILED', 'Error! Sorry! Failed to create administration category');
define('_ADMINDELETEFAILEDDEFAULT', 'You cannot delete the default Admin category');
define('_ADMINDELETEFAILEDSTART', 'You cannot delete the start Admin category');
define('_ADMINVIEW', 'View administration categories');
define('_ADMINOPTIONS', 'Options');
define('_ADMINUPDATECATEGORY', 'Update category');
define('_ADMINCATEGORYUPDATED', 'Administration category updated');
define('_ADMINDELETECATEGORY', 'Delete administration category');
define('_ADMINCONFIRMCATEGORYDELETE', 'Confirm deletion of administration category');
define('_ADMINCANCELCATEGORYDELETE', 'Cancel deletion of administration category');
define('_ADMINDELETED', 'Administration category deleted');
define('_ADMINFAILEDADDMODTOCAT', 'Error! Sorry! Failed to add module to category');
define('_ADMINPANELCATEGORY', 'Administration panel');
define('_ADMINDISPLAYICONS', 'Display icons in administration panel');
define('_ADMINDEFAULTCATEGORY', 'Default category for newly-added modules');
define('_ADMINITEMSPERPAGE', 'Categories per page');
define('_ADMINSKIN', 'Stylesheet for rendering administration panel');
define('_ADMINSTARTCATEGORY', 'Start category');
define('_ADMINIGNOREINSTALLERCHECK', 'Ignore check for installer');
define('_ADMINIGNOREINSTALLERCHECKWARNING', 'WARNING: only check this box if on an isolated system otherwise your installation could be compromised.');
define('_ADMINAUTOMATEDARTICLES','Programmed articles');
define('_ADMINNOAUTOARTICLES','There are no programmed articles');
define('_ADMINSTORYID', 'Story ID');
define('_ADMINCURRENTPOLL', 'Current poll');
define('_ADMINMODSPERROWNUMERIC', 'The \'Modules per row\' setting must be numeric');
define('_ADMINCATPERPAGENUMERIC', 'The \'Categories per row\' setting must be numeric');
?>