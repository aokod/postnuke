<?php
// $Id: admin.php 15197 2004-12-16 15:46:33Z markwest $
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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Everyone
// Purpose of file: Translation files
// Translation team: Read credits in /docs/CREDITS.txt
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage  PostNuke_Permissions
 * @license http://www.gnu.org/copyleft/gpl.html
*/

if (!defined('_DELETE')) {
    define('_DELETE','Delete');
}
if (!defined('_EDIT')) {
    define('_EDIT','Edit');
}
if (!defined('_PERMISSIONS')) {
    define('_PERMISSIONS','Permissions');
}
define('_ALLGROUPS','All groups');
define('_ALLREALMS','All realms'); // Realms defines until they get their own home
define('_ALLUSERS','All users');
define('_REALM','Realm');
define('_INSTANCE','Instance');
define('_COMPONENT','Component');
if (!defined('_DOWN')) {
    define('_DOWN','Down');
}
define('_USERPERMS','User');
define('_GROUPPERMS','Group');
define('_VIEWGROUPPERMS','View group permissions');
define('_VIEWUSERPERMS','View user permissions');
define('_MODIFYPERM','Modify');
define('_MODIFYGROUPPERM','Modify group permissions');
define('_MODIFYUSERPERM','Modify user permissions');
define('_NEWPERM',' Add ');
define('_NEWGROUPPERM','New group permission');
define('_NEWUSERPERM','New user permission');
define('_PERMLEVEL','Permissions level');
define('_PERMOPS','Operations');
define('_SEQUENCE','Seq.');
define('_UNREGISTEREDGROUP','Unregistered');
define('_UNREGISTEREDUSER','Unregistered');
if (!defined('_UP')) {
    define('_UP','Up');
}
define('_PERMISSIONINFO','Permissions information');
define('_REGISTEREDCOMP','Registered component');
define('_INSTANCETEMP','Instance template');

// MMaes: Removed some hard-coded text
define('_PERM_INC','Incremented permission rule');
define('_PERM_DEC','Decremented permission rule');
define('_PERM_UPD','Updated permission rule');
define('_PERM_CREATED','Created permission rule');
define('_PERM_DEL','Removed permission rule');
define('_PERM_DECINCERR_NOID','Error! Sorry! No such permissions ID: ');
define('_PERM_DECERR_NOSWAP','No permission directly below that one');
define('_PERM_INCERR_NOSWAP','No permission directly above that one');
// MMaes: Direct Insert capability
// define('_PERM_THINS','Ins.');
define('_PERMINSBEFORE_ALTTXT','Insert permission rule  before');
define('_PERM_INSERR','Error! Sorry! Could not update permission sequences');
define('_PERM_INSNOTIFY','Inserted permission rule at position ');
// MMaes: Only show permissions applying to a group
define('_SEQ_ADJUST','Shift');
define('_PERM_VWSHOWONLY','Only show permissions applying to: ');
define('_PERM_VWFILTER','Filter');
define('_PERM_WARN_FILTERACTIVE','<strong>- PARTIAL VIEW -</strong>');
define('_PERM_PARTLY','Partial view of permissions list');
define('_PERM_SHOWING','Group: ');
define('_PERM_DECINCERR_NOSWAPPART','Error! Sorry! Permission-swapping in partial view can only be done if both affected permissions are visible. Please use full view');
// MMaes: ListEdit-function, editing in the mainview
define('_PERM_LISTNONEFOUND','Error! Sorry! No permissions of this kind were found. Please add some first');
define('_PERM_COMP_INPUTERR',' [Illegal input in component!] ');
define('_PERM_INST_INPUTERR',' [Illegal input in instance!] ');
// MMaes: Module-settings
define('_PERM_ENABLEFILTER','Enable filtering of group permissions');
define('_PERM_DISPLAYWARNING','Show warning bar when in filter list');
define('_PERM_ROWHEIGHTVIEW','Minimum viewing row height (in pixels)');
define('_PERM_ROWHEIGHTEDIT','Minimum editing row height (in pixels)');
define('_PERM_UPDATESETTINGS','Save settings');
define('_PERMISSIONSDELETE', 'Delete permission rule');
define('_PERMISSIONSCONFIRMDELETE', 'Confirm deletion of rule');
define('_PERMISSIONSCANCELDELETE', 'Cancel deletion of rule');

?>