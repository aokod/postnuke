<?php
// $Id: admin.php 19418 2006-07-16 19:54:19Z markwest $
// Xanthia Theme Engine FOR PostNuke Content Management System
// Copyright (C) 2003 by the CorpNuke.com Development Team.
// Copyright is claimed only on changes to original files
// Modifications by: Larry E. Masters aka. PhpNut 
// nut@phpnut.com
// http://www.coprnuke.com/
// ----------------------------------------------------------------------
// Based on: Encompass Theme Engine - http://madhatt.info/
// Original Author: Brian K. Virgin (MADHATter7)
// ----------------------------------------------------------------------
// Based on:
// eNvolution Content Management System
// Copyright (C) 2002 by the eNvolution Development Team.
// http://www.envolution.com/
// ----------------------------------------------------------------------
// Based on:
// PostNuke Content Management System - www.postnuke.com
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

/**
 * @package     Xanthia_Templating_Environment
 * @subpackage  Xanthia
 * @license http://www.gnu.org/copyleft/gpl.html
*/

// Global Defines
define('_XA_ADMINMENU','Administration menu');
define('_XA_ADDCOLOR','Add new theme color palette');
if (!defined('_XA_ARGSERROR')) {
	define('_XA_ARGSERROR','Sorry! Xanthia argument error');
}
if (!defined('_XA_APILOADFAILED')) {
	define('_XA_APILOADFAILED','Sorry! Failed to open  Xanthia API');
}
if (!defined('_XA_ANERROROCCURED')) {
	define('_XA_ANERROROCCURED','Sorry! Xanthia has encountered a fatal error');
}
define('_XA_ACTIVE','Active');
define('_XA_EDITMODTEMPLATES','Module Templates');
define('_XA_BLOCK','Block');
define('_XA_ALLMODULES','All Modules');
define('_XA_BCDEACTIVATEALL','Deactivate block control in modules');
define('_XA_BCCONTROL','Block control');
define('_XA_BCZONES','Block Position Tags');
define('_XA_BCSTART','Choose a module to configure from the list below ');
define('_XA_BCQUESTIONBC','If you want to activate block control for all current modules click on <strong>Set block control for all modules</strong><br />
If you activate block control for all modules, you can then add and remove individual blocks using Visual Block Editor (VBE) or the Block Control admin panel in Xanthia Admin');
define('_XA_BCACTIVATEALL','Set block control for all modules');
define('_XA_BCCHOOSEBLOCKS','Choose blocks to add to this module from the list below <strong>You can select multiple blocks by holding the <CTRL> key and clicking on the desired blocks');
define('_XA_BCTURNOFFBC','If you want to deactivate block control for all currently-active modules. click on <strong>Deactivate block control for modules</strong><br />
If you deactivate block control for all modules, you will have to set block control for each module individually');
define('_XA_BLOCKMANAGE','Configure block control for&nbsp;');
define('_XA_CONFIGURE_SHORTURL','Use short URLS'); 
define('_XA_CONFIGURE_TRIMWHITESPACE', 'Use trimwhitespace output filter');
define('_XA_COMPASSEDIT','Xanthia theme engine');
define('_XA_COLORSCONFIG','Color settings');
define('_XA_COMMIT','Commit');
define('_XA_CHANGE','Change');
define('_XA_COMMASEPERATED', 'Comma-separated');
define('_XA_CACHETHEMESETTINGS', 'Generate configuration cache');
define('_XA_DELETE','Delete');
define('_XA_GENERALCONFIG','general settings');
define('_XA_INACTIVE','Inactive');
define('_XA_MAIN','Main menu');
define('_XA_MODULES','Modules');
define('_XA_MODULE','Module');
define('_XA_MODULES_NOCACHE', 'Modules to exclude from caching');
define('_XA_NEW', 'New');	
define('_XA_NEWTHEME','Create new theme');
define('_XA_NEWMASTERMODULES', 'New master for module');
define('_XA_NEWZONES', 'New skin for zone');
define('_XA_DEFAULTLABEL', 'Use the controls below to define the default label for modules, blocks and block zones');
if (!defined('_XA_NOMODINFO')) {
	define('_XA_NOMODINFO','Xanthia module information not found.');
}
define('_XA_NOTHEMESAVAILABLE', 'You have no Xanthia themes available');
if (!defined('_XA_NOZONEFOUND')) {
	define('_XA_NOZONEFOUND','No wrapper found');
}

define('_XA_POSITION','Position');

define('_XA_REMOVE','Remove');

define('_XA_SHORTURL_EXTENSION', 'Extension to use for short URLs (excluding .)');
define('_XA_SHOWACTIVETHEMES','Show active themes');
define('_XA_SHOWALLTHEMES','Show all themes');

define('_XA_THEME_TEMPLATE', 'Theme template');
define('_XA_TEMPLATE_NAME', 'Template name');
define('_XA_TEMPLATE_NAME_INSTRUCTIONS', 'You must set the name for the template!');
define('_XA_TEMPLATE_NAME_VALIDATION', 'Sorry! The template name is missing. \n Please enter a template name.');
define('_XA_TEMPLATE_SOURCE_VALIDATION', 'Sorry!  The template source is missing. \n Please specify a template source.');
define('_XA_THEMEZONES','Content Wrappers');
define('_XA_THEMECOLORS','Colors');
define('_XA_THEMECONFIGURE','Settings');
define('_XA_THEMECACHEGENERATED', 'Configuration cache generated');
define('_XA_THEMECACHENOTGENERATED', 'Sorry! Failed to generate configuration cache');

define('_XA_VIEWTHEMES','View themes');

define('_XA_ZONECONFIG','wrapper settings');


define('_XA_THEMEREMOVEFAILURE', 'Sorry! This theme can\'t be removed because it is your default theme');
define('_XA_BLOCKS', 'New skin for block');
define('_XA_BACK', 'Back');


// Block control defines
define('_XA_BLOCCO1','<strong>Available blocks</strong>');
define('_XA_ADMINBLOCK','Modify blocks');
define('_XA_NOMORECONFIG','<strong>Configure</strong>');
define('_XA_NOMOREAUTO','<strong>Logic Plus</strong> active by default in every new module');
define('_XA_NOMOREHELP','Select one or more blocks from the list<br /> and press <strong>Submit</strong><br />&nbsp;');
define('_XA_NOMOREHELP1','(<I>[0]</I> active block <I>[1]</I> inactive block)<br />');
define('_XA_RELOAD','Refresh page');
define('_XA_CHIUDI','Close');
if (!defined('_XA_BLOCCO')) define('_XA_BLOCCO','Block');
if (!defined('_XA_RIGHT')) define('_XA_RIGHT','Right');
if (!defined('_XA_LEFT')) define('_XA_LEFT','Left');
if (!defined('_XA_CENTER')) define('_XA_CENTER','Center');
define('_XA_INNER','Inner');
define('_XA_REMOVEBLOCK','Remove block');
define('_XA_NEWTHEMETEMPLATE', 'New theme template');
define('_XA_NEWBLOCKTEMPLATE', 'New block template');
define('_XA_NEWMODULETEMPLATE', 'New module template');
define('_XA_TEMPLATERELOADSUCESSFUL', 'Templates reloaded from file system');
define('_XA_TEMPLATERELOADFAILED', 'Sorry! Failed to reload templates from file system');
define('_XA_THEMEINSTALLFAILED', 'Sorry! Theme installation failed');
define('_XA_TEMPLATENOCONTENT', 'Sorry! Template content not set');
define('_XA_TEMPLATEDBINSERTFAILED', 'Sorry! Error inserting theme template files in database');
define('_XA_COULDNOTSETBLOCKCONTROL', 'Sorry! Could not set block control');
define('_XA_ZONEDEFAULT', 'Default template');

define('_XA_MASTER','Master wrapper');
define('_XA_OPENTABLE1','OpenTable1');
define('_XA_OPENTABLE2','OpenTable2');
define('_XA_LEFTSIDEB','Left side box');
define('_XA_RIGHTSIDEB','Right side box');
define('_XA_NEWSINDEX','News index (standard)');
define('_XA_NEWSART','News article');
define('_XA_NEWSINDEX2','News index (2 columns)');
define('_XA_TOPCENTERBLOCK','Top center block');
define('_XA_TOPCENTERBOX','Top center box');
define('_XA_BOTCENTERBLOCK','Bottom center block');
define('_XA_BOTCENTERBOX','Bottom center box');
define('_XA_DEFAULTSIDEB','Inner block side box');
define('_XA_MAINMENUB','Main menu block');
define('_XA_DEFAULTSIDE','Default side box');
define('_XA_CENTERBLOCK','Center block');

// Theme Related Defines
define('_XA_THEMECONFIG','Theme configuration');
define('_XA_AVAILABLETHEMES','All Xanthia themes');
define('_XA_RELOADTEMPLATES', 'Reload templates');
define('_XA_RELOADTEMPLATE', 'Reload template');
define('_XA_REMOVETHEME','Deactivate theme');
define('_XA_THEMECREDITS', 'Credits');
define('_XA_THEMENAME','Theme name');
define('_XA_NOTHEMECREDITS', 'No credits available for this theme');
//define('_XA_CREATETHEME','Create template from this theme');
define('_XA_EDITTEMPLATES','Templates');
define('_XA_EDITTEMPLATEFILE', 'Edit template form');
define('_XA_VIEWTHEME','View theme');
define('_XA_ADDTHEME','Activate theme');
define('_XA_EDITTHEME','Edit theme');
define('_XA_THEMEREMOVED','The theme was successfully deactivated. Please remember to remove the theme from the themes directory.');
define('_XA_THEMEADDED','The theme was successfully activated.');

// Zone Related Defines
define('_XA_ZONENAME','Name');
define('_XA_ZONELABEL','Label');
define('_XA_ZONETYPE','Type');
define('_XA_ISACTIVE','Active?');
define('_XA_ACTIONS','Actions');
define('_XA_REQUIRED','Required');
define('_XA_OPTIONAL','Add-on');
define('_XA_ACTIVATE','Activate');
define('_XA_DEACTIVATE','Deactivate');
define('_XA_CONFIGURE','Configure');
define('_XA_ADDZONE','Add new wrapper');
define('_XA_CREATEZONE','Add new wrapper');
define('_XA_ZONECREATED','Wrapper created');
define('_XA_ZONEDELETED','Wapper deleted');
define('_XA_ZONEDEACTIVATED','Wrapper deactivated');
define('_XA_ZONEACTIVATED','Wrapper activated');
define('_XA_ZONEEXISTS','Specified wrapper label already exists.');
if (!defined('_XA_COMPASSNOZONES')) {
	define('_XA_COMPASSNOZONES','No zones specified in API arguments.');
}
define('_XA_ZONENODELETE','Cannot remove required wrapper.');
if (!defined('_XA_INZONE')) {
	define('_XA_INZONE','in wrapper');
}
if (!defined('_XA_MAINZONENOTPL')) {
	define('_XA_MAINZONENOTPL','Sorry! A required wrapper template was either not found or failed to load.');
}
define('_XA_ADMINPAGES', 'Admin pages');
define('_XA_HOMEPAGE', 'Home page');
define('_XA_USERPAGES', 'User pages');
// Template Related Defines
define('_XA_TEMPLATE','Template');
define('_XA_TPLNOTSET','No template assigned');
define('_XA_CONFIGTEMPLATES','Configure templates');
define('_XA_TPLUPDATED','Template updated');
define('_XA_TPLINFO','Simply select the template you want to assign to this wrapper and click Commit. The changes will take effect immediately. Remember that although
you can assign any template to any wrapper, most templates are not compatible with each other. (i.e. News index templates will not work for the top center block,
etc.) One exception to this rule are the block templates (e.g. rsblock, lsblock): most of them are interchangeable in most
themes.');

// Colors Related Defines
define('_XA_ADDCOLORS','Add new color set');
define('_XA_DELCOLORS','Delete color set');
define('_XA_CONFIGCOLORS','Configure colors');
define('_XA_BGCOLOR1','Color 1');
define('_XA_BGCOLOR2','Color 2');
define('_XA_BGCOLOR3','Color 3');
define('_XA_BGCOLOR4','Color 4');
define('_XA_BGCOLOR5','Color 5');
define('_XA_BGCOLOR6','Color 6');
define('_XA_SEPCOLOR','Separator');
define('_XA_TEXTCOLOR1','Text 1');
define('_XA_TEXTCOLOR2','Text 2');
define('_XA_CURRENTPALETTE', 'Current palette');
define('_XA_USEPALETTE', 'Use Palette');

define('_XA_BACKGROUNDC','Background');
define('_XA_SEPERATORC','Separator');
define('_XA_TEXT1C','Text1');
define('_XA_TEXT2C','Text2');
define('_XA_LINKC','Link');
define('_XA_VLINKC','Vlink');
define('_XA_HOVERC','Hover');
define('_XA_COLOR1C','Color 1');
define('_XA_COLOR2C','Color 2');
define('_XA_COLOR3C','Color 3');
define('_XA_COLOR4C','Color 4');
define('_XA_COLOR5C','Color 5');
define('_XA_COLOR6C','Color 6');
define('_XA_COLOR7C','Color 7');
define('_XA_COLOR8C','Color 8');

define('_XA_COLORSMAINMENU','Colors menu');
define('_XA_COLORSUPDATED','Colors updated');
define('_XA_COLORSINFO','Theme colors should be specified in HEX format, including the number symbol. For instance, for black, specify #000000; for white, specify 
#FFFFFF. There are six different background colors, one separator color and two text colors available to you. Simply change any or all of
the values above and click Commit: the changes will take effect immediately.');

// Skins Related Defines
define('_XA_SKINNAME','Theme name');
define('_XA_SKININSTALL','Install new theme');
define('_XA_SKINDELETE','Uninstall a theme');

// Config Related Defines
define('_XA_ADDCONFIG','Add new configuration');
define('_XA_DELCONFIG','Delete configuration');
define('_XA_CONFIGNAME','Name');
define('_XA_CONFIGCONFIGS','Configure general configurations');
define('_XA_CONFIGDESCRIPT','Description');
define('_XA_CONFIGSETTING','Setting');
define('_XA_CONFIGMAINMENU','Configurations menu');
define('_XA_CREATECONFIG','Add new general configuration');
define('_XA_CONFIGCREATED','Configuration created');
define('_XA_CONFIGDELETED','Configuration deleted');
define('_XA_CONFIGUPDATED','Setting updated');
define('_XA_CONFIGEXISTS','Sorry! The specified configuration name already exists.');
define('_XA_CONFIGINFO','Simply enter the new value in the text area and click Commit, the changes will take effect immediately. Unfortunately, the ability to create and
delete general configurations has been removed, as it was never fully implemented and now never will be.');
define('_XA_CONFIGNEWINFO','Here are some helpful hints and pointers to proper documentation for creating general configurations.');
define('_XA_FILE', 'File');
define('_XA_ACTION', 'Action');

// DTS Related Defines
//define('_XA_FLUSHCACHE','Flush cache files');
//define('_XA_DTSMAINMENU','DTS menu');

//NEWZONE
define('_XA_NZID','Tag ID');
define('_XA_NZDESC','Tag description');
define('_XA_NZTAG','Tag to use in theme');
define('_XA_NZACTION','Action');
define('_XA_NZREMOVE','Remove');
define('_XA_NZUPDATE','Refresh');
define('_XA_NZOKADD','Zone added');
define('_XA_NZTITLE','Add new block position tag');
define('_XA_NZWARNING','Warning! This zone has the following blocks assigned to it. Zone not removed');

//Main Configuration
define('_XA_BLOCKTEMPLATES','Block templates');
define('_XA_CHOOSEUPLOAD','<strong>Choose a theme package to upload</strong><br /><span style="color:#ff0000;">It must be a tar.gz/.tar file with a valid Xanthia theme set structure</span>');
define('_XA_CONFIGUREBLOCKCONTROL','Configure block control');
define('_XA_CONFIGURENOHTACCESS', 'Note: shortURLs cannot be activated while the .htaccess file is not in your webroot.  If you wish to use shortURLs, consult the documentation for more information.  Otherwise, ignore this message.');
define('_XA_CONFIGURE_TITLE','Main configuration settings');
define('_XA_CONFIGURE_VBA','Use visual block editor');
define('_XA_CONFIGURE_XANTHIA','Configure Xanthia');
define('_XA_CONFIGURE_USECACHE','Enable caching');
define('_XA_CONFIGURE_CACHETYPE','Store cache data in database');
define('_XA_CONFIGURE_DBCOMPILE','Store compiled templates in database (not yet working)');
define('_XA_CONFIGURE_TEMPLATECHECK','Check for updated version of templates');
define('_XA_CONFIGURE_FORCETEMPLATECHECK', 'Force templates recompilation');
define('_XA_CONFIGURE_CACHETIME','Length of time to cache pages');
define('_XA_CONFIGURE_USEDB','Base caching on updated database (requires pnCache)');
define('_XA_CONFIGURE_DBTEMPLATES','Store templates in database');
define('_XA_COPYTEMPLATE','Make copy');
define('_XA_CREATETEMPLATE','Create new template');
define('_XA_CHOOSEINNER','Select a block to use for your inner block');
define('_XA_DATETCREATED','Templates created');
define('_XA_DOWNTEMPLATE','Download template');
define('_XA_DOWNTHEME','Download theme for distribution');
define('_XA_EDITTEMPLATE','EDIT');
define('_XA_MODULETEMPLATES','Module templates');
define('_XA_THEMETEMPLATES','theme templates');
define('_XA_TEMPLATES','templates');
define('_XA_UPLOADTHEME','Upload a new theme package');
define('_XA_UPLOADNAME','<strong>New theme name</strong><br />Enter a theme name for this package. Leave it blank if you want the name to be automatically detected.');

/** Xanthia Help Menu Defines
 *  These are used for the help icons displayed within Xanthia
 *  Administration Menu
 */
define('_XA_BCCONTROLHELP','Block control help');
define('_XA_COLORSHELP','Colors settings help');
define('_XA_CREATETHEMEHELP', 'Creating a theme help file');
define('_XA_EDITTHEMEHELP','Editing a theme help file');
define('_XA_EDITTEMPLATEHELP','Editing templates help');
define('_XA_EDITMODTEMPLATEHELP','Editing module templates help');
define('_XA_FILECONTENTNOTSET', 'Sorry! File_content not set');
define('_XA_HELP','Xanthia help');
define('_XA_THEMECONTIGHELP','Theme configuration help');
define('_XA_THEMEZONESHELP','Theme zones help');
define('_XA_BLOCKZONESHELP','Block Control zones help');
// defines for theme credits function - add additional ones to themes/themename/lang/code/xaninfo.php
define('_XA_API', 'API');
define('_XA_AUTHOR', 'Author');
define('_XA_URL', 'Web site');
define('_XA_NAME', 'Name');
define('_XA_XHTMLSUPPORT', 'XHTML support');
define('_XA_DOWNLOAD', 'Download');
?>