<?php
// $Id: pntables.php 16722 2005-08-27 12:35:10Z  $
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
 * This function is called internally by the core whenever the module is
 * loaded.  It adds in the information
 * @return array pntables array
 */
function xanthia_pntables() {

	// Init the array
	$pntable = array();

	// Get the DB prefix
	$prefix = pnConfigGetVar('prefix');

	// General config table and columns
	// mh7: Added 'skin_id' to permit Skin specific configs
	$pntable['theme_config'] = $prefix.'_theme_config';
	$pntable['theme_config_column'] = array(
		'name'			=> "$pntable[theme_config].name",
		'skin_id'		=> "$pntable[theme_config].skin_id",
		'description'	=> "$pntable[theme_config].description",
		'setting'		=> "$pntable[theme_config].setting",
		'data'			=> "$pntable[theme_config].data"
	);

	// Template to zone table and columns
	$pntable['theme_layout'] = $prefix.'_theme_layout';
	$pntable['theme_layout_column'] = array(
		'skin_id'		=> "$pntable[theme_layout].skin_id",
		'zone_label'	=> "$pntable[theme_layout].zone_label",
		'tpl_file'		=> "$pntable[theme_layout].tpl_file",
		'skin_type'		=> "$pntable[theme_layout].skin_type"
	);

	// Skins and color table and columns
	$pntable['theme_skins'] = $prefix.'_theme_skins';
	$pntable['theme_skins_column'] = array(
		'skin_id'		=> "$pntable[theme_skins].skin_id",
		'name'			=> "$pntable[theme_skins].name",
		'is_active'		=> "$pntable[theme_skins].is_active",
		'is_multicolor'	=> "$pntable[theme_skins].is_multicolor"
	);
	
	$pntable['theme_palette'] = $prefix.'_theme_palette';
	$pntable['theme_palette_column'] = array(
		'palette_id'      => "$pntable[theme_palette].palette_id",
		'palette_name'    => "$pntable[theme_palette].palette_name", 
		'skin_id'		  => "$pntable[theme_palette].skin_id",
	    'module'          => "$pntable[theme_palette].pn_module",
		'all'	          => "$pntable[theme_palette].all_themes",
        'background'      => "$pntable[theme_palette].background",
        'color1'	      => "$pntable[theme_palette].color1",
        'color2'	      => "$pntable[theme_palette].color2",
        'color3'	      => "$pntable[theme_palette].color3",
        'color4'	      => "$pntable[theme_palette].color4",
        'color5'	      => "$pntable[theme_palette].color5",
        'color6'	      => "$pntable[theme_palette].color6",
        'color7'	      => "$pntable[theme_palette].color7",
        'color8'	      => "$pntable[theme_palette].color8",
        'sepcolor'	      => "$pntable[theme_palette].sepcolor",
        'text1'		      => "$pntable[theme_palette].text1",
        'text2'		      => "$pntable[theme_palette].text2",
        'link'		      => "$pntable[theme_palette].link",
        'vlink'		      => "$pntable[theme_palette].vlink",
        'hover'		      => "$pntable[theme_palette].hover"
	);

	// Zones table and columns
	// mh7: Added 'skin_id' to permit Skin specific layouts
	$pntable['theme_zones'] = $prefix.'_theme_zones';
	$pntable['theme_zones_column'] = array(
		'zone_id'		=> "$pntable[theme_zones].zone_id",
		'skin_id'		=> "$pntable[theme_zones].skin_id",
		'name'			=> "$pntable[theme_zones].name",
		'label'			=> "$pntable[theme_zones].label",
		'type'			=> "$pntable[theme_zones].type",
		'is_active'		=> "$pntable[theme_zones].is_active",
		'skin_type'		=> "$pntable[theme_zones].skin_type"
	);

	$pntable['theme_cache'] = $prefix.'_theme_cache';
	$pntable['theme_cache_column'] = array(
		'cache_id'		=> "$pntable[theme_cache].cache_id",
		'cache_contents'	=> "$pntable[theme_cache].cache_contents"
	);	

	$pntable['theme_blcontrol'] = $prefix.'_theme_blcontrol';
	$pntable['theme_blcontrol_column'] = array(
	    'module'        => "$pntable[theme_blcontrol].pn_module",
        'block'         => "$pntable[theme_blcontrol].pn_block",
        'theme'         => "$pntable[theme_blcontrol].pn_theme",
        'identi'        => "$pntable[theme_blcontrol].pn_identi",
        'position'      => "$pntable[theme_blcontrol].pn_pos",
        'weight'        => "$pntable[theme_blcontrol].pn_weight",
        'blocktemplate' => "$pntable[theme_blcontrol].pn_template",
        'active'        => "$pntable[theme_blcontrol].pn_active",    
        'always'        => "$pntable[theme_blcontrol].pn_always"        
    );
                            
	$pntable['theme_addons'] = $prefix.'_theme_addons';
	$pntable['theme_addons_column'] = array(
	    'addon_id'        => "$pntable[theme_addons].pn_addon_id",
        'block_id'        => "$pntable[theme_addons].pn_block_id",
	    'addon'           => "$pntable[theme_addons].pn_addonname",
        'block_func'      => "$pntable[theme_addons].pn_block_function"        
    );
    
	$pntable['theme_tplfile'] = $prefix.'_theme_tplfile';
	$pntable['theme_tplfile_column'] = array(
	     'tpl_id'           => "$pntable[theme_tplfile].tpl_id",
         'tpl_skin_id'      => "$pntable[theme_tplfile].tpl_skin_id",
         'tpl_module'       => "$pntable[theme_tplfile].tpl_module",
         'tpl_skin_name'    => "$pntable[theme_tplfile].tpl_skin_name",
         'tpl_file'         => "$pntable[theme_tplfile].tpl_file",                                  
         'tpl_desc'         => "$pntable[theme_tplfile].tpl_desc",
         'tpl_lastmodified' => "$pntable[theme_tplfile].tpl_lastmodified",
         'tpl_lastimported' => "$pntable[theme_tplfile].tpl_lastimported",
         'tpl_type'         => "$pntable[theme_tplfile].tpl_type"       
    );
        
	$pntable['theme_tplsource'] = $prefix.'_theme_tplsource';
	$pntable['theme_tplsource_column'] = array(
		'tpl_id'		=> "$pntable[theme_tplsource].tpl_id",
        'tpl_skin_id'   => "$pntable[theme_tplsource].tpl_skin_id",
        'tpl_file_name' => "$pntable[theme_tplsource].tpl_file_name",
		'tpl_source'	=> "$pntable[theme_tplsource].tpl_source",
		'tpl_secure'	=> "$pntable[theme_tplsource].tpl_secure",
		'tpl_trusted'	=> "$pntable[theme_tplsource].tpl_trusted",
		'tpl_timestamp'	=> "$pntable[theme_tplsource].tpl_timestamp"		
	);

	return $pntable;

}

?>