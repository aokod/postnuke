<?php
// $Id: pninit.php 16722 2005-08-27 12:35:10Z  $
// Xanthia Theme Engine FOR PostNuke Content Management System
// Copyright (C) 2003 By Larry E. Masters, Shorewood, Illinois.
// nut@phpnut.com
// http://www.larrymasters.com/
// http://www.phpnut.com/
// ----------------------------------------------------------------------
// Based on: Encompass Theme Engine - http://madhatt.info/
// Original Author: Brian K. Virgin (MADHATter7)
// Based on: NoMoreBlocks module - http://www.envolution.it
// Original Authors: ZXvision, TiMax, Cino
// ----------------------------------------------------------------------
// Based on:
// eNvolution Content Management System
// Copyright (C) 2002 by the eNvolution Development Team.
// http://www.envolution.com/
// ----------------------------------------------------------------------
// Based on:
// Postnuke Content Management System - www.postnuke.com
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

/**
 * @package     PostNuke_System_Modules
 * @subpackage  Xanthia
 * @license http://www.gnu.org/copyleft/gpl.html
*/

/**
 * initialise the Xanthia module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * @return bool true if initialisation succcesful, false otherwise
 */
function xanthia_init()
{
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();

	// Theme Configuration

	$table = $pntable['theme_config'];
	$column = &$pntable['theme_config_column'];

	$create ="CREATE TABLE $table (
		$column[skin_id] int(11) NOT NULL default '1',
		$column[name] varchar(200) NOT NULL default '',
		$column[description] varchar(60) NOT NULL default '',
		$column[setting] TEXT NOT NULL default '',
		$column[data] TEXT NOT NULL default '')";

	$dbconn->Execute($create);

	if ($dbconn->ErrorNo() != 0) {
		pnSessionSetVar('errormsg', _XA_CREATETABLEFAIL." on $table...");
		return false;
	}
	// Theme Layout
	$table = $pntable['theme_layout'];
	$column = &$pntable['theme_layout_column'];

	$create = "CREATE TABLE $table (
		$column[skin_id] int(11) NOT NULL default '0',
		$column[zone_label] varchar(255) NOT NULL default '',
		$column[tpl_file] varchar(200) NOT NULL default '',
		$column[skin_type] varchar(8) NOT NULL default 'theme',
			KEY $column[skin_id](skin_id))";

	$dbconn->Execute($create);

	if ($dbconn->ErrorNo() != 0) {
		pnSessionSetVar('errormsg', _XA_CREATETABLEFAIL." on $table...");
		return false;
	}

	// Theme Skins
	$table = $pntable['theme_skins'];
	$column = &$pntable['theme_skins_column'];
		
	$create = "CREATE TABLE $table (
		$column[skin_id] int(11) unsigned NOT NULL auto_increment,
		$column[name] varchar(200) NOT NULL default '',
		$column[is_active] int(1) NOT NULL default '0',
		$column[is_multicolor] int(1) NOT NULL default '0',
			PRIMARY KEY(skin_id))";

	$dbconn->Execute($create);

	if ($dbconn->ErrorNo() != 0) {
		pnSessionSetVar('errormsg', _XA_CREATETABLEFAIL." on $table...");
		return false;
	}

	// Theme Color Palette
	$table = $pntable['theme_palette'];
	$column = &$pntable['theme_palette_column'];

	//        $create = "CREATE TABLE $table (
	//            $column[palette_id] int(11) unsigned NOT NULL auto_increment,
	//            $column[skin_id]    int(11) NOT NULL default '0',
	//        	$column[module]     varchar(32) NOT NULL default '',
	//            $column[all]        tinyint(1) NOT NULL default '1',
	//            $column[bgcolor1]   varchar(12) NOT NULL default '#FFFFFF',
	//            $column[bgcolor2]   varchar(12) NOT NULL default '#FFFFFF',
	//            $column[bgcolor3]   varchar(12) NOT NULL default '#FFFFFF',
	//            $column[bgcolor4]   varchar(12) NOT NULL default '#FFFFFF',
	//            $column[bgcolor5]   varchar(12) NOT NULL default '#FFFFFF',
	//            $column[bgcolor6]   varchar(12) NOT NULL default '#FFFFFF',
	//            $column[sepcolor]   varchar(12) NOT NULL default '#000000',
	//            $column[textcolor1] varchar(12) NOT NULL default '#000000',
	//            $column[textcolor2] varchar(12) NOT NULL default '#000000',
	//            	PRIMARY KEY(palette_id))";
        
	$create = "CREATE TABLE $table (
		$column[palette_id]    	int(11) unsigned NOT NULL auto_increment,
		$column[palette_name]  	varchar(32) NOT NULL default '',
		$column[skin_id]       	int(11) NOT NULL default '0',
		$column[module]        	varchar(64) NOT NULL default '',
		$column[all]           	tinyint(1) NOT NULL default '1',
		$column[background]     varchar(12) NOT NULL default '#FFFFFF',
		$column[color1]      	varchar(12) NOT NULL default '#FFFFFF',
		$column[color2]      	varchar(12) NOT NULL default '#FFFFFF',
		$column[color3]      	varchar(12) NOT NULL default '#FFFFFF',
		$column[color4]      	varchar(12) NOT NULL default '#FFFFFF',
		$column[color5]      	varchar(12) NOT NULL default '#FFFFFF',
		$column[color6]      	varchar(12) NOT NULL default '#000000',
		$column[color7]    		varchar(12) NOT NULL default '#000000',
		$column[color8]    		varchar(12) NOT NULL default '#000000',
		$column[sepcolor]    	varchar(12) NOT NULL default '#000000',
		$column[text1]    		varchar(12) NOT NULL default '#000000',        
		$column[text2]    		varchar(12) NOT NULL default '#000000',        
		$column[link]    		varchar(12) NOT NULL default '#000000',       
		$column[vlink]    		varchar(12) NOT NULL default '#000000',        
		$column[hover]    		varchar(12) NOT NULL default '#000000',        
			PRIMARY KEY(palette_id))";               
               
	$dbconn->Execute($create);

	if ($dbconn->ErrorNo() != 0) {
		pnSessionSetVar('errormsg', _XA_CREATETABLEFAIL." on $table...");
		return false;
	} 

	// Theme Zones
	$table = $pntable['theme_zones'];
	$column = &$pntable['theme_zones_column'];
	
	$create = "CREATE TABLE $table (
		$column[zone_id] int(3) NOT NULL auto_increment,
		$column[skin_id] int(3) NOT NULL default '1',
		$column[name] varchar(40) NOT NULL default 'No Name',
		$column[label] varchar(255) NOT NULL default 'addon',
		$column[type] int(1) NOT NULL default '1',
		$column[is_active] int(1) NOT NULL default '0',
		$column[skin_type] varchar(8) NOT NULL default 'theme',
			PRIMARY KEY(zone_id),
			KEY $column[type](type))";

	$dbconn->Execute($create);

	if ($dbconn->ErrorNo() != 0) {
		pnSessionSetVar('errormsg', _XA_CREATETABLEFAIL." on $table...");
		return false;
	}

	// Theme Cache
	$table = $pntable['theme_cache'];
	$column = &$pntable['theme_cache_column'];
	
	$create = "CREATE TABLE $table (
		$column[cache_id] varchar(32) NOT NULL default '',
		$column[cache_contents] MEDIUMTEXT NOT NULL default '',
			PRIMARY KEY(cache_id))";

	$dbconn->Execute($create);

	if ($dbconn->ErrorNo() != 0) {
		pnSessionSetVar('errormsg', _XA_CREATETABLEFAIL." on $table...");
		return false;
	}

	 // Compiled Templates
	 // Not implemented 
	 // 	$table = $pntable['theme_compiled'];
	 // 	$column = &$pntable['theme_blcontrol_column'];
	 // 	
	 // 	$create = 
	 // 		"CREATE TABLE $table ($column[compile_id] varchar(32) NOT NULL default '',
	 // 							  $column[tpl_id] varchar(32) NOT NULL default '',
	 // 							  $column[skin_id] varchar(32) NOT NULL default '',
	 // 							  $column[compiled_content] varchar(4) NOT NULL default '',
	 // 							  $column[compiled_time] decimal(10,1) NOT NULL default '1.0',
	 // 	                          PRIMARY KEY (compile_id))";	
	 // 	$dbconn->Execute($create);
	 // 	// Check for an error with the database code
	 // 	 if ($dbconn->ErrorNo() != 0)
	 // 	{
	 // 	pnSessionSetVar('errormsg', _XA_CREATETABLEFAIL." on $table...");
	 // 	return false;
	 // 	}	
	 //

	// Block Control
	$table = $pntable['theme_blcontrol'];
	$column = &$pntable['theme_blcontrol_column'];
		
	$create = "CREATE TABLE $table (
		$column[module] 		varchar(64) NOT NULL default '',
		$column[block] 			varchar(32) NOT NULL default '',
		$column[theme] 			varchar(32) NOT NULL default '',        
		$column[identi] 		varchar(32) NOT NULL default '',
		$column[position] 		varchar(4) NOT NULL default '',
		$column[weight] 		decimal(10,1) NOT NULL default '1.0',
		$column[blocktemplate] 	varchar(50) NOT NULL default '',
		$column[active] 		tinyint(1) NOT NULL default '1', 
		$column[always] 		tinyint(1) NOT NULL default '0',
			PRIMARY KEY  (pn_block,pn_module,pn_theme))";

	$dbconn->Execute($create);

	if ($dbconn->ErrorNo() != 0) {
		pnSessionSetVar('errormsg', _XA_CREATETABLEFAIL." on $table...");
		return false;
	}		

	// Theme Template File
	$table = $pntable['theme_tplfile'];
	$column = &$pntable['theme_tplfile_column'];
        
	$create = "CREATE TABLE $table (
		$column[tpl_id] 			mediumint(7) unsigned NOT NULL auto_increment,
		$column[tpl_skin_id] 		smallint(5) unsigned NOT NULL default '0',
		$column[tpl_module] 		varchar(25) NOT NULL default '',
		$column[tpl_skin_name] 		varchar(50) NOT NULL default '',
		$column[tpl_file] 			varchar(200) NOT NULL default '',
		$column[tpl_desc] 			varchar(255) NOT NULL default '',
		$column[tpl_lastmodified] 	int(10) unsigned NOT NULL default '0',
		$column[tpl_lastimported] 	int(10) unsigned NOT NULL default '0',
		$column[tpl_type] 			varchar(20) NOT NULL default '',
			PRIMARY KEY  (tpl_id),
			KEY tpl_skin_id (tpl_skin_id,tpl_type),
			KEY tpl_skin_name (tpl_skin_name,tpl_file(10)))";
             
	$dbconn->Execute($create);
        
	if ($dbconn->ErrorNo() != 0) {
		pnSessionSetVar('errormsg', _XA_CREATETABLEFAIL." on $table...");
		return false;
	}
        
	//Template Source Files   
	$table = $pntable['theme_tplsource'];
	$column = &$pntable['theme_tplsource_column'];
        
	$create = "CREATE TABLE $table (
		$column[tpl_id] 		int(11) unsigned NOT NULL auto_increment,
		$column[tpl_skin_id] 	int(11) unsigned NOT NULL default '0',
		$column[tpl_file_name] 	varchar(200) NOT NULL default '',        
		$column[tpl_source] 	mediumtext NOT NULL,
		$column[tpl_secure] 	tinyint(1) NOT NULL default '1',
		$column[tpl_trusted] 	tinyint(1) NOT NULL default '1',
		$column[tpl_timestamp] 	timestamp(14) NOT NULL,       
			KEY tpl_id (tpl_id))";
	
	$dbconn->Execute($create);
        
	if ($dbconn->ErrorNo() != 0) {
		pnSessionSetVar('errormsg', _XA_CREATETABLEFAIL." on $table...");
		return false;
	} 
                
	//Themes Addons   
	$table = $pntable['theme_addons'];
	$column = &$pntable['theme_addons_column'];
	
	$create = "CREATE TABLE $table (
		$column[addon_id] 	int(11) unsigned NOT NULL auto_increment,
		$column[block_id] 	int(11) unsigned NOT NULL default '0',
		$column[addon] 		varchar(25) NOT NULL default '',       
		$column[block_func] varchar(200) NOT NULL default '',      
			KEY pn_addon_id (pn_addon_id))";
        
	$dbconn->Execute($create);
        
	if ($dbconn->ErrorNo() != 0) {
		pnSessionSetVar('errormsg', _XA_CREATETABLEFAIL." on $table...");
		return false;
	}                 
                    
    if (file_exists('modules/Xanthia')){
        pnModSetVar('Xanthia', 'rootpath', 'modules');
    } else {
        pnModSetVar('Xanthia', 'rootpath', 'system');
    }

	// Set Default Vars for Xanthia                
    pnModSetVar('Xanthia', 'vba', 0);
    pnModSetVar('Xanthia', 'enablecache', 0); 
	pnModSetVar('Xanthia', 'modulesnocache', '');  
    pnModSetVar('Xanthia', 'db_cache', 0);
    pnModSetVar('Xanthia', 'db_compile', 0);    
    pnModSetVar('Xanthia', 'compile_check', 0);  
    pnModSetVar('Xanthia', 'use_db', 0);
    pnModSetVar('Xanthia', 'cache_lifetime', 3600);
    pnModSetVar('Xanthia', 'db_templates', 0);
    pnModSetVar('Xanthia', 'block_control', 0);  	
    pnModSetVar('Xanthia', 'TopCenter',0);
    pnModSetVar('Xanthia', 'BotCenter',0);
    pnModSetVar('Xanthia', 'InnerBlock',0);
    pnModSetVar('Xanthia', 'shorturls',0);
    pnModSetVar('Xanthia', 'shorturlsextension','html');
    
    ob_start();
    phpinfo();
    $contents = ob_get_contents();
    ob_end_clean();
    $needle = "mod_rewrite";
    $pos = strpos($contents, $needle); 
    
    if ($pos !== false) { 
        pnModSetVar('Xanthia', 'shorturlsok','1');
    } else {
        pnModSetVar('Xanthia', 'shorturlsok','0');
    }
    
	return true;
}

/**
 * upgrade the Xanthia module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * @return bool true if upgrade succcesful, false otherwise
 */
function xanthia_upgrade($oldversion) {

	// Upgrade dependent on old version number
	switch($oldversion) {
		case 1.0:
			// Version 1.1 Of Xanthia 
			// This version has new table structure in the Database
			// so we must delete current tables then create new tables
			xanthia_delete();
			xanthia_init();
			break;
		case 1.1:
			// Version 2.0 of Xanthia has the same DB structure as v1.1
			// version 1.1 was internal and never released
			break;
		case 2.0:
			// change the lengths of some fields
            // Get datbase setup 
            $dbconn  = &pnDBGetConn(true);
            $pntable = &pnDBGetTables();
			$table = $pntable['theme_config'];
			$column = &$pntable['theme_config_column'];
            $sql = "ALTER TABLE $table
                    CHANGE $column[setting] $column[setting] TEXT";
            $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                pnSessionSetVar('errormsg', _UPGRADEFAILED);
                return false;
            }
			$table = $pntable['theme_layout'];
			$column = &$pntable['theme_layout_column'];
            $sql = "ALTER TABLE $table
                    CHANGE $column[zone_label] $column[zone_label] VARCHAR(255)";
            $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                pnSessionSetVar('errormsg', _UPGRADEFAILED);
                return false;
            }
            $sql = "ALTER TABLE $table
                    CHANGE $column[tpl_file] $column[tpl_file] VARCHAR(200)";
            $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                pnSessionSetVar('errormsg', _UPGRADEFAILED);
                return false;
            }
			$table = $pntable['theme_tplfile'];
			$column = &$pntable['theme_tplfile_column'];
            $sql = "ALTER TABLE $table
                    CHANGE $column[tpl_file] $column[tpl_file] VARCHAR(200)";
            $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                pnSessionSetVar('errormsg', _UPGRADEFAILED);
                return false;
            }
			$table = $pntable['theme_zones'];
			$column = &$pntable['theme_zones_column'];
            $sql = "ALTER TABLE $table
                    CHANGE $column[label] $column[label] VARCHAR(255)";
            $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                pnSessionSetVar('errormsg', _UPGRADEFAILED);
                return false;
            }
			break;
    }
    // Update successful
    return true;
}

/**
 * uninstall the Xanthia module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * @return bool true if uninstall succcesful, false otherwise
 */
function xanthia_delete() {

    // Setup db variables
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

	$destroy = "DROP TABLE IF EXISTS $pntable[theme_config]";
	$dbconn->Execute($destroy);

    if ($dbconn->ErrorNo() != 0) {
		return false;
	}
    
	$destroy = "DROP TABLE IF EXISTS  $pntable[theme_layout]";
	$dbconn->Execute($destroy);

    if ($dbconn->ErrorNo() != 0) {
		return false;
	}
    
	$destroy = "DROP TABLE IF EXISTS $pntable[theme_skins]";
	$dbconn->Execute($destroy);

    if ($dbconn->ErrorNo() != 0) {
		return false;
	}
        
	$destroy = "DROP TABLE IF EXISTS $pntable[theme_palette]";
	$dbconn->Execute($destroy);

    if ($dbconn->ErrorNo() != 0) {
		return false;
	}        
        
	$destroy = "DROP TABLE IF EXISTS $pntable[theme_zones]";
	$dbconn->Execute($destroy);

    if ($dbconn->ErrorNo() != 0) {
		return false;
	}
    
	$destroy = "DROP TABLE IF EXISTS $pntable[theme_cache]";
	$dbconn->Execute($destroy);

    if ($dbconn->ErrorNo() != 0) {
		return false;
	}
        
	// Compiled Templates
	// Not implemented     
	// $destroy = "DROP TABLE $pntable[theme_compiled]";
	// $dbconn->Execute($destroy);
	// 
	//     if ($dbconn->ErrorNo() != 0) {
	//             return false;
	//         }
     
	$destroy = "DROP TABLE IF EXISTS $pntable[theme_blcontrol]";
	$dbconn->Execute($destroy);

    if ($dbconn->ErrorNo() != 0) {
		return false;
	}
    
	$destroy = "DROP TABLE IF EXISTS $pntable[theme_tplfile]";
	$dbconn->Execute($destroy);

    if ($dbconn->ErrorNo() != 0) {
		return false;
	}
    
    
	$destroy = "DROP TABLE IF EXISTS $pntable[theme_tplsource]";
	$dbconn->Execute($destroy);

    if ($dbconn->ErrorNo() != 0) {
		return false;
	}

	$destroy = "DROP TABLE IF EXISTS $pntable[theme_addons]";
	$dbconn->Execute($destroy);

    if ($dbconn->ErrorNo() != 0) {
		return false;
	}

	if(pnModGetVar('Xanthia','rootpath')){
		$modulecolumn = &$pntable['module_vars_column'];
		$destroy = "DELETE FROM $pntable[module_vars] WHERE $modulecolumn[modname] = 'Xanthia'";
		$dbconn->Execute($destroy);

		if ($dbconn->ErrorNo() != 0) {
            return false;
        } 
	}       
	// Deletion successful
	return true;
}

?>
