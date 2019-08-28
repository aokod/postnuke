<?php
// $Id: pnadminapi.php 17501 2006-01-11 11:49:49Z markwest $
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
 * @access        private
 * @author        mh7
 * @since         0.911        02-04-2002
 */
function xanthia_adminapi_listZones($args)
{
	// Extract our parameters
	extract($args);
	// Argument check
	if (!isset($skin)) {
		// Load user API
		if (!pnModAPILoad('Xanthia', 'user')) {
            pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAIL));
			return false;
		}
		$skin = pnModAPIFunc('Xanthia','user','getSkinID');
	}
	// Setup db handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	// Define table & column
	$table = $pntable['theme_zones'];
	$column = &$pntable['theme_zones_column'];
	// Build the query
	$query = "SELECT $column[zone_id],
									$column[name],
									$column[label],
									$column[type],
									$column[is_active],
									$column[skin_type]
				FROM $table
				WHERE $column[skin_id]='".pnVarPrepForStore($skin)."'
				ORDER BY $column[type] ASC,
								$column[is_active] DESC,
								$column[label] ASC";
	// Execute the query
	$result =& $dbconn->Execute($query);
	// If no matches found return false
	if ($result->EOF) {
		return false;
	} else {
		// Organize our data
		$array = array();
		//modified for Oracle compatibility
		// while (list($zid, $name, $label, $type, $isactive) = $result->fields){
		while(!$result->EOF){
			list($zid, $name, $label, $type, $isactive, $skin_type) = $result->fields;
			// Move that ADOdb pointer !
			$result->MoveNext();
			// Assign to associative array
			$array[] = array('id'                => $zid,
							'name'                => $name,
							'label'                => $label,
							'type'                => $type,
							'active'        => $isactive,
							'skin_type' => $skin_type);
		}
		// Always close the result set
		$result->Close();
		// return the result
		return $array;
	}
}

/**
 * List colors for a given skin
 * @access                private
 * @author                mh7
 * @since                 0.925                24-08-2002
 * @param                 integer $skin current skin ID
 * @return                array $colors list of colors info
 */
function xanthia_adminapi_listColors($args)
{
	// Extract our parameters
	extract($args);
	// Load user API
	if (!pnModAPILoad('Xanthia', 'user')) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAIL));
		return false;
	}
	// Argument check
	if (!isset($skin)) {
		$skin = pnModAPIFunc('Xanthia','user','getSkinID');
	}
	// Setup db handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	// Get the current skin name
	$skinName = pnModAPIFunc('Xanthia','user','getSkinFromID', array('id' => $skin));

	// define table & column
	$table = $pntable['theme_palette'];
	$column = &$pntable['theme_palette_column'];
	// Build the DB query
	$query = "SELECT $column[palette_name],
					 $column[background],
					 $column[color1],
					 $column[color2],
					 $column[color3],
					 $column[color4],
					 $column[color5],
					 $column[color6],
					 $column[color7],
					 $column[color8],
					 $column[sepcolor],
					 $column[text1],
					 $column[text2],
					 $column[link],
					 $column[vlink],
					 $column[hover]        
					FROM $table
					WHERE $column[skin_id] = '" . (int)pnVarPrepForStore($skinid) . "' 
					AND $column[palette_id] = '" . (int)pnVarPrepForStore($paletteid) . "'";
	// Execute the query
	$result =& $dbconn->Execute($query);
	// If no match found report false
	if ($result->EOF) {
		return false;
	}
	// Orgnaize and build our data array
	//modified for Oracle compatibility
	/* while (list($bcolor1, $bcolor2, $bcolor3, $bcolor4, $bcolor5,
				$bcolor6, $scolor, $tcolor1, $tcolor2) = $result->fields){ */
	while(!$result->EOF){
		list($palette_name,
			 $background,
			 $color1,
			 $color2,
			 $color3,
			 $color4,
			 $color5,
			 $color6,
			 $color7,
			 $color8,
			 $sepcolor,
			 $text1,
			 $text2,
			 $link,
			 $vlink,
			 $hover) = $result->fields;
		// Move that ADOdb pointer !
		$result->MoveNext();
		// Build the array
		$array[] = array('palette_name'  => $palette_name,
						 'background'  => $background,
						 'color1'      => $color1,
						 'color2'      => $color2,
						 'color3'      => $color3,
						 'color4'      => $color4,
						 'color5'      => $color5,
						 'color6'      => $color6,
						 'color7'      => $color7,
						 'color8'      => $color8,
						 'sepcolor'    => $sepcolor,
						 'text1'       => $text1,
						 'text2'       => $text2,
						 'link'        => $link,
						 'vlink'       => $vlink,
						 'hover'       => $hover);
	}
	// Always close the result set
	$result->Close();
	// Return the results
	return $array;
}

/**
 * List colors on a per skin basis
 * @access                private
 * @author                mh7
 * @since                0.911                02-04-2002
 * @deprecated           0.925                24-08-2002
 * @see                   xanthia_adminapi_listColors()
 */
function xanthia_adminapi_skinColors()
{
	// pass thru, this function has been deprecated
	// code removed for clarity
}

/**
 * List configs for the given skin
 * @access                private
 * @author                mh7
 * @since                 0.911                02-04-2002
 * @param                 integer $skin current skin ID
 * @return                array $array list of config info
 */
function xanthia_adminapi_listConfig($args)
{
	// Extract our parameters
	extract($args);
	if (!isset($skin)) {
		// Load user API
		if (!pnModAPILoad('Xanthia','user')) {
            pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAIL));
			return false;
		}

		// Assign the current Active Skin
		$skin = pnModAPIFunc('Xanthia','user','getSkinID');
	}
	// Setup db handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	// Setup the table and column
	$table = $pntable['theme_config'];
	$column = &$pntable['theme_config_column'];
	// Build the query
	$query = "SELECT $column[name],
					$column[description],
					$column[setting]
			FROM $table
			WHERE $column[skin_id] = '" . (int)pnVarPrepForStore($skin) . "'";
        // Execute the query
	$result =& $dbconn->Execute($query);
	// If no matches return false
	if ($result->EOF) {
		return false;
	}
	// Organize and build our data array
	//modified for Oracle compatibility
	//while (list($name, $description, $setting) = $result->fields) {
	while(!$result->EOF){
		list($name, $description, $setting) = $result->fields;
		// Move that ADOdb pointer !
		$result->MoveNext();
		// Build the return array
		$array[] = array('name'                        => $name,
						'description'        => $description,
						'setting'                => $setting);
	}
	// Always close the result set
	$result->Close();
	// Return the results
	return $array;
}

/**
 * List template file for the given zone
 * @access                private
 * @author                mh7
 * @since                 0.911                02-04-2002
 * @param                 integer $skin current skin ID
 * @param                 string $zone label for the needed zone
 * @return                string $result->fields[0] the template file name
 */
function xanthia_adminapi_listTpl($args)
{
	// Extract our parameters
	extract($args);
	// Argument checks
	if (!isset($zone)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
		return false;
	}
	// Check parameters
	if (!isset($skin)) {
		// Load user API
		if (!pnModAPILoad('Xanthia','user')) {
            pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAIL));
			return false;
		}
		// Assign the current Active Skin
		$skin = pnModAPIFunc('Xanthia','user','getSkinID');
	}

	// Setup the DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();

	// Setup the table and column
	$table = $pntable['theme_layout'];
	$column = &$pntable['theme_layout_column'];
	$extraslect ='';
	if (isset($skintype)){
        $extraslect =" AND $column[skin_type] = '" . pnVarPrepForStore($skintype) . "'";
	}
	// echo $extraslect;
	// Build the query
	$query = "SELECT $column[tpl_file]
			FROM $table
			WHERE $column[skin_id] = '" . (int)pnVarPrepForStore($skin) . "'
			AND $column[zone_label] = '" . pnVarPrepForStore($zone) . "'
			$extraslect";
	// Execute the query
	$result =& $dbconn->Execute($query);

	// If no matches found report false
	if ($result->EOF) {
		return false;
	}
	// Always close the result set
	$result->Close();
	// Return the filename
	return $result->fields[0];
}

/*
 * Fetch the skin name from a supplied skin ID
 * @access                private
 * @author                mh7
 * @since                 0.911                02-04-2002
 * @param                 integer $is the supplied skin ID
 * @return                string $name the corresponding skin name
 */
//function xanthia_adminapi_getSkinFromID($args)
//{
	// Extract our parameters
//	extract($args);
	// Parameter check
//	if (!isset($id)) {
//		pnSessionSetVar('errormsg', pnVarPrepForDisplay(_XA_ARGSERROR));
//		return false;
//	}
	// Setup the DB handle
//	$dbconn =& pnDBGetConn(true);
//	$pntable =& pnDBGetTables();

	// Setup the table and column info
//	$table = $pntable['theme_skins'];
//	$column = &$pntable['theme_skins_column'];
   
    // Buld the query
	//modified for Oracle compatibility
	/* $query = "SELECT $column[name]
				FROM $table
				WHERE $column[skin_id]='$id'
				LIMIT 1";*/
//	$query = "SELECT $column[name] FROM $table WHERE $column[skin_id] = '" . (int)pnVarPrepForStore($id) . "'";
//	$result = $dbconn->SelectLimit($query,1);

	// Execute the query
//	$result =& $dbconn->Execute($query);
	// If no matches found return false
//	if ($result->EOF) {
//		return false;
//	}

	// Close the result set
//	$result->Close();
	// Return the result
//	return $result->fields[0];
//}

/**
 * Set the state of the current zone (active|inactive)
 * @access                private
 * @author                mh7
 * @since                0.911                02-04-2002
 * @param                integer $skin the current skin ID
 * @param                string $zone the zone label we are working with
 * @param                string $task the action we are to perform
 * @return                bool true upon successful update
 */
function xanthia_adminapi_setstate($args)
{
	// Extract our parameters
	extract($args);
	// Argument check
	if (!isset($zone) || !isset($task) || !isset($skin)) {
		pnSessionSetVar('errormsg', pnVarPrepForDisplay(_XA_ARGSERROR));
		return false;
	}

	// Permissions check
	if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
		pnSessionSetVar('errormsg', pnVarPrepForDisplay(_MODULENOAUTH));
		return false;
	}

	// Setup the DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	// Setup the table and column info
	$table = $pntable['theme_zones'];
	$column = &$pntable['theme_zones_column'];

	// determine the proper task
	switch ($task) {
		// Deactivate an active zone
		case 'deactivate':
			$sql = "UPDATE $table
					SET $column[is_active]='0'
					WHERE $column[label] = '" . pnVarPrepForStore($zone) . "'
					AND $column[skin_id] = '" . (int)pnVarPrepForStore($skin) . "'";
			break;

		// Activate an inactive zone
		case 'activate':
			$sql = "UPDATE $table
					SET $column[is_active]='1'
					WHERE $column[label] = '" . pnVarPrepForStore($zone) . "'
					AND $column[skin_id] = '" . (int)pnVarPrepForStore($skin) . "'";
			break;
	}

	// Execute the sql statement
	$result =& $dbconn->Execute($sql);
	// Always close the result set
	$result->Close();
	// Update successful, return true
	return true;
}

/**
 * Update items as requestd
 * @access                private
 * @author                mh7
 * @since                 0.911                02-04-2002
 * @deprecated            0.925                24-08-2002
 * @see                   xanthia_adminapi_update() | version 0.911
 */
function xanthia_adminapi_update()
{
	// pass thru, this function has been deprecated
	// code removed for clarity
}

/**
 * Update a zones template file
 * @access               private
 * @author               mh7
 * @since                0.925                24-08-2002
 * @param                integer $skin current skin ID
 * @param                string $zone the zone we are updating
 * @param                string $template tpl file that zone is to use
 * @return               bool true upon successful update
 * @todo                 fix permissions issues
 */
function xanthia_adminapi_updateZones($args)
{
	// Check Permissions
	if (!pnSecAuthAction(0, 'Xanthia', '::', ACCESS_EDIT)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_MODULENOAUTH));
		return false;
	}

	// Extract our parameters
	extract($args);
	// Argument check
	if (!isset($skin) || !isset($zone) || !isset($tpl)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
		return false;
	}

	// Setup the DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	// Setup the table and columns info
	$table = $pntable['theme_layout'];
	$column = &$pntable['theme_layout_column'];

	// Build the query
	$query = "SELECT $column[tpl_file]
			FROM $table
			WHERE $column[skin_id] = '" . (int)pnVarPrepForStore($skin) . "'
			AND $column[zone_label] = '" . pnVarPrepForStore($zone) . "'";
	// Execute the query
	$result =& $dbconn->Execute($query);

	// Determine if this is a new template or an update
	if (!$result->EOF) {
		// Existing entry, update record
		$result->Close();
		$sql = "UPDATE $table
				SET $column[tpl_file] = '" . pnVarPrepForStore($tpl) . "'
				WHERE $column[skin_id] = '" . (int)pnVarPrepForStore($skin) . "'
				AND $column[zone_label] = '" . pnVarPrepForStore($zone) . "'";
	} else {
		// New entry, create record
		$sql = "INSERT INTO $table (
				$column[skin_id],
				$column[zone_label],
				$column[tpl_file],
				$column[skin_type]		
				) VALUES (
				'" . (int)pnVarPrepForStore($skin) . "',
				'" . pnVarPrepForStore($zone) . "',
				'" . pnVarPrepForStore($tpl) ."',
				'" . pnVarPrepForStore($skintype) ."')";
	}

	// Update the DB
	$result =& $dbconn->Execute($sql);

	// Always close the result set
	$result->Close();

	// Update successful, return true
	return true;
}

/**
 * Update a skins color set
 * @access               private
 * @author               mh7
 * @since                0.925                24-08-2002
 * @param                integer $skin ID for the skin
 * @param                string $bgcolor1 background color #1 (HEX)
 * @param                string $bgcolor2 background color #2 (HEX)
 * @param                string $bgcolor3 background color #3 (HEX)
 * @param                string $bgcolor4 background color #4 (HEX)
 * @param                string $bgcolor5 background color #5 (HEX)
 * @param                string $bcolor6 background color #6 (HEX)
 * @param                string $sepcolor  seperator color (HEX)
 * @param                string $tcolor1 text color #1 (HEX)
 * @param                string $tcolor2 text color #2 (HEX
 * @return               bool true upon successful update
 * @todo                 fix permissions issues
 */
function xanthia_adminapi_updateColors($args)
{
	// Check Permissions
	if (!pnSecAuthAction(0, 'Xanthia', '::', ACCESS_EDIT)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_MODULENOAUTH));
		return false;
	}

	// Extract our parameters
	extract($args);

	// check for our parameters
	if (empty($palname)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
		return false;
	}

	// Setup the DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	// Setup the table and column info
	$table = $pntable['theme_palette'];
	$column = &$pntable['theme_palette_column'];

	// Build the update statement
	if ($paletteid != ''){
        $sql = "UPDATE $table
				SET $column[palette_name] = '".pnVarPrepForStore(pnVarCleanFromInput('palname'))."',
					$column[background] = '#".pnVarPrepForStore(pnVarCleanFromInput('bgcolor'))."',
					$column[color1] = '#".pnVarPrepForStore(pnVarCleanFromInput('color1'))."',
					$column[color2] = '#".pnVarPrepForStore(pnVarCleanFromInput('color2'))."',
					$column[color3] = '#".pnVarPrepForStore(pnVarCleanFromInput('color3'))."',
					$column[color4] = '#".pnVarPrepForStore(pnVarCleanFromInput('color4'))."',
					$column[color5] = '#".pnVarPrepForStore(pnVarCleanFromInput('color5'))."',
					$column[color6] = '#".pnVarPrepForStore(pnVarCleanFromInput('color6'))."',
					$column[color7] = '#".pnVarPrepForStore(pnVarCleanFromInput('color7'))."',
					$column[color8] = '#".pnVarPrepForStore(pnVarCleanFromInput('color8'))."',
					$column[sepcolor] = '#".pnVarPrepForStore(pnVarCleanFromInput('sepcolor'))."',
					$column[text1] = '#".pnVarPrepForStore(pnVarCleanFromInput('text1'))."',
					$column[text2] = '#".pnVarPrepForStore(pnVarCleanFromInput('text2'))."',
					$column[link] = '#".pnVarPrepForStore(pnVarCleanFromInput('link'))."',
					$column[vlink] = '#".pnVarPrepForStore(pnVarCleanFromInput('vlink'))."',
					$column[hover] = '#".pnVarPrepForStore(pnVarCleanFromInput('hover'))."' 
				WHERE $column[skin_id]='" . (int)pnVarPrepForStore($skin) . "' 
				AND $column[palette_id] = '" . (int)pnVarPrepForStore($paletteid) . "'";
	} else {
		$sql = "INSERT INTO $table ($column[skin_id],
					$column[palette_name],
					$column[background],
					$column[color1],
					$column[color2],
					$column[color3],
					$column[color4],
					$column[color5],
					$column[color6],
					$column[color7],
					$column[color8],
					$column[sepcolor],
					$column[text1],
					$column[text2],
					$column[link],
					$column[vlink],
					$column[hover] 
				) VALUES (
					'".pnVarPrepForStore(pnVarCleanFromInput('skin'))."',                
					'".pnVarPrepForStore(pnVarCleanFromInput('palname'))."',
					'#".pnVarPrepForStore(pnVarCleanFromInput('bgcolor'))."',
					'#".pnVarPrepForStore(pnVarCleanFromInput('color1'))."',
					'#".pnVarPrepForStore(pnVarCleanFromInput('color2'))."',
					'#".pnVarPrepForStore(pnVarCleanFromInput('color3'))."',
					'#".pnVarPrepForStore(pnVarCleanFromInput('color4'))."',
					'#".pnVarPrepForStore(pnVarCleanFromInput('color5'))."',
					'#".pnVarPrepForStore(pnVarCleanFromInput('color6'))."',
					'#".pnVarPrepForStore(pnVarCleanFromInput('color7'))."',
					'#".pnVarPrepForStore(pnVarCleanFromInput('color8'))."',
					'#".pnVarPrepForStore(pnVarCleanFromInput('sepcolor'))."',
					'#".pnVarPrepForStore(pnVarCleanFromInput('text1'))."',
					'#".pnVarPrepForStore(pnVarCleanFromInput('text2'))."',
					'#".pnVarPrepForStore(pnVarCleanFromInput('link'))."',
					'#".pnVarPrepForStore(pnVarCleanFromInput('vlink'))."',
					'#".pnVarPrepForStore(pnVarCleanFromInput('hover'))."')";        
	}

	// Update the DB
	$result =& $dbconn->Execute($sql);

	// Always close the result set
	$result->Close();

	// Update successful, return true
	return true;
}

/**
 * Update a skins general configs
 * @access                private
 * @author                mh7
 * @since                 0.925                24-08-2002
 * @param                 integer $skin current skin ID
 * @param                 string $config anme of the config we are updating
 * @param                 string $setting the new value of the config
 * @return                bool true upon successful update
 * @todo                  fix permissions issues
 */
function xanthia_adminapi_updateConfig($args)
{
	// Check Permissions
	if (!pnSecAuthAction(0, 'Xanthia', '::', ACCESS_EDIT)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_MODULENOAUTH));
		return false;
	}

	// Extract our parameters
	extract($args);

	// Argument check
	if (!isset($skin) || !isset($config) || !isset($setting)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
		return false;
	}

	// Setup the DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	// Setup the table and column info
	$table = $pntable['theme_config'];
	$column = &$pntable['theme_config_column'];

	$lim=count($config);
	for($i=0; $i<$lim; $i++){
        // Buld the update statement
        $sql = "UPDATE $table
                        SET $column[setting] = '" . pnVarPrepForStore($setting[$i]) . "'
                        WHERE $column[name] = '" . pnVarPrepForStore($config[$i]) . "'
                        AND $column[skin_id] = '" . (int)pnVarPrepForStore($skin) . "'";
        // Update the DB
        $result =& $dbconn->Execute($sql);
	}

	// Always close the result set
	$result->Close();

	// Update successful, return true
	return true;
}

/**
 * Create items as requestd
 * @access                private
 * @author                mh7
 * @since                 0.911                02-04-2002
 * @deprecated            0.925                24-08-2002
 * @see                   xanthia_adminapi_create() | version 0.911
 */
function xanthia_adminapi_create()
{
	// pass thru, this function has been deprecated
	// code removed for clarity
}

/**
 * Create new zone
 * @access                private
 * @author                mh7
 * @since                0.925                24-08-2002
 * @param                integer $skin current skin ID
 * @param                string $zone the zone we are going to create
 * @param                string $label long title for the new zone
 * @return                bool true upon successful update
 * @todo                fix permissions issues
 */
function xanthia_adminapi_createZones($args)
{
	// Check Permissions
	if (!pnSecAuthAction(0, 'Xanthia', '::', ACCESS_EDIT)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_MODULENOAUTH));
		return false;
	}

	// Extract our parameters
	extract($args);

	// Argument check
	if (!isset($skin) || !isset($zone) || !isset($label) || !isset($type)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
		return false;
	}

	// Setup the DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	// Setup the table and column info
	$table = $pntable['theme_zones'];
	$column = &$pntable['theme_zones_column'];

	// Build the query
	$query = "SELECT $column[zone_id]
			FROM $table
			WHERE $column[label] = '" . pnVarPrepForStore($label) . "'
			AND $column[skin_id] = '" . (int)pnVarPrepForStore($skin) . "'";
	// Execute the query
	$result =& $dbconn->Execute($query);

	// Determine if the zone label already exists
	if ($result->EOF) {
		// No such label, build the update statement
		$sql = "INSERT INTO $table (
					$column[zone_id],
					$column[skin_id],
					$column[name],
					$column[label],
					$column[type],
					$column[is_active],
					$column[skin_type]
				) VALUES (
					'',
					'" . (int)pnVarPrepForStore($skin) . "',
					'" . pnVarPrepForStore($zone) . "',
					'" . pnVarPrepForStore($label) . "',
					1,
					0,
					'" . pnVarPrepForStore($type) . "')";
	} else {
		// Zone label exists, report error
		$result->Close();
		pnSessionSetVar('errormsg', _XA_ZONEEXISTS);
		return false;
	}

	// Update the DB
	$result =& $dbconn->Execute($sql);

	// Always close the result set
	$result->Close();

	// Update successful, return true
	return true;
}

/**
 * Create new config
 * @access                private
 * @author                mh7
 * @since                 0.911                02-04-2002
 * @deprecated            0.925                24-08-2002
 * @see                   xanthia_adminapi_create() | version 0.911
 * @todo                  never fully implemented, never will be
 */
function xanthia_adminapi_createConfig()
{
	// pass thru, this function has been deprecated
	// code removed for clarity
}

/*
 * Delete items as requestd
 * @access                private
 * @author                mh7
 * @since                0.911                02-04-2002
 * @deprecated        0.925                24-08-2002
 * @see                        xanthia_adminapi_delete() | version 0.911
function xanthia_adminapi_delete()
{
	// pass thru, this function has been deprecated
	// code removed for clarity
}

/**
 * Delete a zone
 * @access                private
 * @author                mh7
 * @since                0.925                24-08-2002
 * @param                integer $skin current skin ID
 * @param                string $zone the zone we are going to delete
 * @return                bool true upon successful removal
 * @todo                fix permissions issues
 */
function xanthia_adminapi_deleteZones($args)
{
	// Check Permissions
	if (!pnSecAuthAction(0, 'Xanthia', '::', ACCESS_EDIT)) {
		pnSessionSetVar('errormsg', pnVarPreHTMLDisplay(_MODULENOAUTH));
		return false;
	}

	// Extract our parameters
	extract($args);
	// Argument check
	if (!isset($skin) || !isset($zone)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
		return false;
	}

   // echo "$skin, $zone";
	// Setup the DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	// Setup the table and column info
	$table = $pntable['theme_zones'];
	$column = &$pntable['theme_zones_column'];

	$layouttable = $pntable['theme_layout'];
	$layoutcolumn = &$pntable['theme_layout_column'];
	
	// Build the query
	$query = "SELECT $column[type]
			FROM $table
			WHERE $column[label] = '" . pnVarPrepForStore($zone) . "'
			AND $column[skin_id] = '" . pnVarPrepForStore($skin) . "'";
	// Execute the query
	$result =& $dbconn->Execute($query);
	$result->Close();

	// Determine if this is a required or addon zone
	if ($result->fields[0] == 1) {
		// Addon Zone, proceed
		$sql = "DELETE FROM $table
				WHERE $column[label] = '" . pnVarPrepForStore($zone) . "'
				AND $column[skin_id] = '" . (int)pnVarPrepForStore($skin) . "'";
		
		$layoutsql = "DELETE FROM $layouttable
				WHERE $layoutcolumn[zone_label] = '" . pnVarPrepForStore($zone) . "'
				AND $layoutcolumn[skin_id] = '" . (int)pnVarPrepForStore($skin) . "'";
		
	} else {
		// Required Zone, report error
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
		return false;
	}
	// Update the DB
	$result =& $dbconn->Execute($sql);
	$result->Close();
	
	$result =& $dbconn->Execute($layoutsql);
	$result->Close();	

	// Update successful, return true
	return true;
}

/**
 * Delete a config
 * @access                private
 * @author                mh7
 * @since                0.911                02-04-2002
 * @deprecated        0.925                24-08-2002
 * @see                        xanthia_adminapi_delete() | version 0.911
 * @todo                never fully implemented, never will be
 */
function xanthia_adminapi_deleteConfig()
{
	// pass thru, this function has been deprecated
	// code removed for clarity
}

/**
 * Create a list of available template files
 * @access                private
 * @author                mh7
 * @since                0.911                02-04-2002
 * @param                integer $skin current skin ID
 * @param                string $zone the zone we are looking to assign a new template
 * @return                array $tpl list of available templates
 * @todo                not much protection here, use caution
 */
function xanthia_adminapi_getTplFiles($args)
{
	// Extract our parameters
	extract($args);
	// Argument check
	if (!isset($skin) || !isset($zone)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
		return false;
	}
	
    // Load user API
	if (!pnModAPILoad('Xanthia','user')) {	    
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	
	// Initialize the template array
	$tpl = array();
	// Get the current skins name
	$skinName = pnModAPIFunc('Xanthia','user','getSkinFromID', array('id' => $skin));

    if (is_dir(WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($skinName))) {
		// Open the template directory fo rthe skin
		$dir = opendir(WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($skinName).'/templates');
	} else {
		// Open the template directory fo rthe skin
		$dir = opendir('themes/'.pnVarPrepForOS($skinName).'/templates');
	}

	// Read through the directory
	while ($file = readdir($dir)) {
		// Validate the files are indeed templates
		// @todo        'prep-' templates are excluded from this list
		if ((substr($file, -3, 3) == 'tpl') && (substr($file, 0, 5) != 'prep-')) {
			// Add valid files to the list
			$tpl[] = $file;
		}

		if ((substr($file, -3, 3) == 'htm') && (substr($file, 0, 5) != 'prep-')) {
			// Add valid files to the list
			$tpl[] = $file;
		}
	}
	// Close the template directory
	closedir($dir);

	// Sort and return the template file names
	sort($tpl);
	return $tpl;
}

/**
 * Get a general conig info
 * @access                private
 * @author                mh7
 * @since                0.911                02-04-2002
 * @param                integer $skin current skin ID
 * @param                string $config current config name
 * @return                array $array list of config info
 */
function xanthia_adminapi_getConfigInfo($args)
{
	// Extract our parameters
	extract($args);
	// Argument check
	if (!isset($skin) || !isset($config)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
		return false;
	}

	// Setup DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	// Setup the table and column info
	$table = $pntable['theme_config'];
	$column = &$pntable['theme_config_column'];

	// Build the query
	$query = "SELECT $column[name],
					$column[description],
					$column[setting]
			FROM $table
			WHERE $column[name] = '" . pnVarPrepForStore($config) . "'
			AND $column[skin_id] = '" . (int)pnVarPrepForStore($skin) . "'";
	// Execute the query
	$result =& $dbconn->Execute($query);
	// If no match found return false
	if ($result->EOF) {
		return false;
	}

	// Organize our data
	list($name, $description, $setting) = $result->fields;
	// Close the result set
	// $result->Close();
	// Build the list of info
	$array = array('name'        => $name,
				   'description' => $description,
				   'setting'     => $setting);
	// return the results
	return $array;
}

/**
 * Insert New Theme info to the database
 * @access                private
 * @author                mh7
 * @since                0.926                29-08-2002
 * @param                none
 * @return                bool
 */
function xanthia_adminapi_insertNewXanthiaTheme($args)
{
	extract($args);
	if (!isset($id)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
		return false;
	}
	$langs = pnUserGetLang();

    if (is_dir(WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($id))) {
		$xaninitlang_path = WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($id).'/lang/'.pnVarPrepForOS($langs).'/xaninit.php';
		$xaninit_path = WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($id).'/xaninit.php';
	} else {
		$xaninitlang_path = 'themes/'.pnVarPrepForOS($id).'/lang/'.pnVarPrepForOS($langs).'/xaninit.php';
		$xaninit_path = 'themes/'.pnVarPrepForOS($id).'/xaninit.php';
	}
	if (file_exists($xaninitlang_path)) {
		include_once $xaninitlang_path;
	}
	
	include_once $xaninit_path;
	if (function_exists('xanthia_skins_install') && xanthia_skins_install(array('id' => $id))) {
		$cid = pnModAPIFunc('Xanthia','admin','insertThemeDB', array('id' => $id)); // sucessful install
		if ($cid != false) {
			//pnModAPIFunc('Xanthia','admin','setBcOnInstall', array('id' => $id));
			return true;
		} else {
            pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_TEMPLATERELOADFAILED));
			return false;
		}
	} else {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_THEMEINSTALLFAILED));
		return false;
    }
}

/**
 * Delete Theme from the database
 * @access                private
 * @author                mh7, ApathyBoy
 * @since                0.926                29-08-2002
 * @param                none
 * @return                bool
 */
function xanthia_adminapi_deleteXanthiaTheme($args)
{
	extract($args);
	if (!isset($id)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
		return false;
	}
	$defaultheme=pnConfigGetVar('Default_Theme');
	if ($id == $defaultheme){
		return 'ERR:1';
	}
	if (xanthia_adminapi_themedelete(array('id' => $id))) {
    } else {
		// failure
		return false;
	}
}

/**
 * This function will take an exsisting Theme and copy it to a new 
 * Directory, renaming the new theme to a name of the users choice
 * @access                private
 * @author                PhpNut nut@phpnut.com 
 */
function xanthia_adminapi_makeThemeDirStructue($args)
{
	extract($args);
         
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();

	// Define table & column
	$table = $pntable['theme_skins'];
	$column = &$pntable['theme_skins_column'];      
}

/**
 * This function will take the theme that the user chooses
 * Tar the theme and allow user to download for local modification or Distribution
 * @access                private
 * @author                PhpNut nut@phpnut.com 
 */
function xanthia_adminapi_ThemeExport($args)
{

	extract($args);
         
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();

	// Define table & column
	$table = $pntable['theme_skins'];
	$column = &$pntable['theme_skins_column'];
		
}

/**
 * @access                private
 */
function xanthia_adminapi_listblocks()
{
    // Security check
    if (!pnSecAuthAction(0, 'Blocks::', '::', ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_MODULENOAUTH));
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

	// load the block db information
	pnModDBInfoLoad('Blocks');

    //  PER MOSTRARE I BLOCCHI
    $pntable =& pnDBGetTables();
    $blockstable = $pntable['blocks'];
    $blockscolumn = &$pntable['blocks_column'];

	$sql = "SELECT $blockscolumn[bid],
				   $blockscolumn[bkey],
				   $blockscolumn[title],
				   $blockscolumn[active],
				   $blockscolumn[position]
		FROM $blockstable
		ORDER BY $blockscolumn[title]";
	$result =& $dbconn->Execute($sql);

    if ($result->EOF) {
        return false;
    }

    $resarray = array();
	//modified for Oracle compatibility
	//    while(list($bid, $bkey, $title, $active, $position) = $result->fields) {
	while(!$result->EOF){
		list($bid, $bkey, $title, $active, $position) = $result->fields;
		$result->MoveNext();

        $resarray[] = array('bid' => $bid,
                            'bkey' => $bkey,
                            'title' => $title,
                            'active' => $active,
                            'position' => $position);
    }
    $result->Close();

    return $resarray;

}

/**
 * @access                private
 */
function xanthia_adminapi_updateInner($args)
{
	// Extract our parameters
	extract($args);
	// Argument check
	if (!isset($id)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
		return false;
	}
	// Permissions check
	if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_MODULENOAUTH));
		return false;
	}
	// Setup the DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	// Setup the table and column info
	$table = $pntable['blocks'];
	$column = &$pntable['blocks_column'];
        
    $sql = "UPDATE $table
			SET $column[position]='l'
			WHERE $column[position]='i'";

	$result =& $dbconn->Execute($sql);
	$result->Close();
        
	$sql = "UPDATE $table
			SET $column[position]='i'
			WHERE $column[bid] = '" . (int)pnVarPrepForStore($id) . "'";

	$result =& $dbconn->Execute($sql);
	// Always close the result set
	$result->Close();
	// Update successful, return true
	return true;
}

/**
 * @access                private
 */
function xanthia_adminapi_insertThemeDB($args)
{
	extract($args);
	if (!isset($id)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
		return false;
	}
        
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();        
        
	$themetplsourcetable = $pntable['theme_tplsource'];
	$themetplsourcecolumn = &$pntable['theme_tplsource_column'];
        
	$skinID = pnModAPIFunc('Xanthia','user','getSkinID', array('skin' => $id));
						
	$themetemplates = pnModAPIFunc('Xanthia','admin','getTplFiles', array('skin' => $skinID, 'zone' => 1));
								       
	$themetplfileinsert = array();
		
	foreach ($themetemplates as $themetemplate){
    	if (is_dir(WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($id))) {
			$path = WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($id).'/templates/'.pnVarPrepForOS($themetemplate);
		} else {
			$path = 'themes/'.pnVarPrepForOS($id).'/templates/'.pnVarPrepForOS($themetemplate);
		}
		$file_content =  pnModAPIFunc('Xanthia', 'admin', 'getFileContent', array('file_name' => $path));
		                            
		if (!isset($file_content)){
            pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_TEMPLATENOCONTENT));
			return false;
		} 
		                            
		$now = time();
		
		$sql = "INSERT INTO $themetplsourcetable (
                            $themetplsourcecolumn[tpl_id],
                            $themetplsourcecolumn[tpl_skin_id],
                            $themetplsourcecolumn[tpl_file_name],
                            $themetplsourcecolumn[tpl_source],
                            $themetplsourcecolumn[tpl_secure],
                            $themetplsourcecolumn[tpl_trusted],
                            $themetplsourcecolumn[tpl_timestamp]) 
                VALUES ('',
				        '" . (int)pnVarPrepForStore($skinID) . "', 
						'" . pnVarPrepForStore($themetemplate) . "', 
						'".addslashes($file_content)."',
                        1, 
						1, 
						NOW( ))";
		
		$result =& $dbconn->Execute($sql);
		
		if ($dbconn->ErrorNo() != 0) {
			pnSessionSetVar('errormsg', $dbconn->ErrorMsg());
			return false;
		}
		
		$themetplfileinsert[] = array('tpl_id' => '',
                                      'tpl_skin_id' => $skinID,
                                      'tpl_module' => $id,
                                      'tpl_skin_name' =>  $id,
                                      'tpl_file' =>  $themetemplate,
                                      'tpl_desc' => '',
                                      'tpl_lastmodified' => $now,
                                      'tpl_lastimported' => $now,
                                      'tpl_type' => 'theme');
	}
		
	$moduletemplates = pnModAPIFunc('Xanthia','admin','getModBlockTplFiles',
																  array('skin' => $id,
																		'tpldir' => 'modules',
																		'tpltype' => 'modules'));
	if (!empty($moduletemplates)){
		foreach ($moduletemplates as $moduletemplate){
    		if (is_dir(WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($id))) {
				$path = WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($id).'/templates/modules/'.pnVarPrepForOS($moduletemplate);
			} else {
				$path = 'themes/'.pnVarPrepForOS($id).'/templates/modules/'.pnVarPrepForOS($moduletemplate);
			}
			$file_content =  pnModAPIFunc('Xanthia', 'admin', 'getFileContent', array('file_name' => $path));

			if (!isset($file_content)){
				pnSessionSerVar('errormsg', _XA_FILECONTENTNOTSET);
				return false;
			}
									   
			$now = time();
			$sql = "INSERT INTO $themetplsourcetable ($themetplsourcecolumn[tpl_id],
													$themetplsourcecolumn[tpl_skin_id],
													$themetplsourcecolumn[tpl_file_name],
													$themetplsourcecolumn[tpl_source],
													$themetplsourcecolumn[tpl_secure],
													$themetplsourcecolumn[tpl_trusted],
													$themetplsourcecolumn[tpl_timestamp]) 
				VALUES ('', 
				        '" . (int)pnVarPrepForStore($skinID) . "', 
						'" . pnVarPrepForStore($moduletemplate) . "',
						'".addslashes($file_content)."', 
						1, 
						1, 
						NOW( ))";
  
			$result =& $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0) {
				pnSessionSetVar('errormsg', $dbconn->ErrorMsg());
				return false;
			}

			$themetplfileinsert[] = array('tpl_id' => '',
										  'tpl_skin_id' => $skinID,
										  'tpl_module' => $id,
										  'tpl_skin_name' =>  $id,
										  'tpl_file' =>  $moduletemplate,
										  'tpl_desc' => '',
										  'tpl_lastmodified' => $now,
										  'tpl_lastimported' => $now,
										  'tpl_type' => 'module');
		}
	}
							   
	$blocktemplates = pnModAPIFunc('Xanthia','admin','getModBlockTplFiles',
							 array('skin' => $id,
								   'tpldir' => 'blocks',
								   'tpltype' => 'blocks'));
																	 
	if (!empty($blocktemplates)){
		foreach ($blocktemplates as $blocktemplate){
    		if (is_dir(WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($id))) {
				$path = WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($id).'/templates/blocks/'.pnVarPrepForOS($blocktemplate);
			} else {
				$path = 'themes/'.pnVarPrepForOS($id).'/templates/blocks/'.pnVarPrepForOS($blocktemplate);
			}
			$file_content =  pnModAPIFunc('Xanthia', 'admin', 'getFileContent',array('file_name' => $path));
  
			if (!isset($file_content)){
				pnSessionSerVar('errormsg', _XA_FILECONTENTNOTSET);
				return false;
			} 
			$now = time();
	
			$sql = "INSERT INTO $themetplsourcetable ($themetplsourcecolumn[tpl_id],
													  $themetplsourcecolumn[tpl_skin_id],
													  $themetplsourcecolumn[tpl_file_name],
													  $themetplsourcecolumn[tpl_source],
													  $themetplsourcecolumn[tpl_secure],
													  $themetplsourcecolumn[tpl_trusted],
													  $themetplsourcecolumn[tpl_timestamp])
					VALUES ('',
					        '" . (int)pnVarPrepForStore($skinID) . "', 
							'" . pnVarPrepForStore($blocktemplate) . "', 
							'".addslashes($file_content)."',
							1, 
							1, 
							NOW( ))";
			
			$result =& $dbconn->Execute($sql);
			
			if ($dbconn->ErrorNo() != 0) {
				pnSessionSetVar('errormsg', $dbconn->ErrorMsg());
				return false;
			}
	
			$themetplfileinsert[] = array('tpl_id' => '',
										  'tpl_skin_id' => $skinID,
										  'tpl_module' => $id,
										  'tpl_skin_name' =>  $id,
										  'tpl_file' =>  $blocktemplate,
										  'tpl_desc' => '',
										  'tpl_lastmodified' => $now,
										  'tpl_lastimported' => $now,
										  'tpl_type' => 'block');
		}
	}
	$contentmoduledirs = pnModAPIFunc('Xanthia','admin','getContentModuleDir',
						 array('skin' => $id,
							   'tpltype' => 'modules'));
							   
	if (!empty($contentmoduledirs)){
		foreach ($contentmoduledirs as $contentmoduledir){
									   
			$contentmoduletemplates = pnModAPIFunc('Xanthia','admin','getModuleTplFiles',
											 array('skin' => $id,
												   'tpldir' => $contentmoduledir,
												   'tpltype' => 'modules'));
																		
			if (!empty($contentmoduletemplates)){
				foreach ($contentmoduletemplates as $contentmoduletemplate){
		    		if (is_dir(WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($id))) {
						$path = WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($id).'/pntemplates/modules/'.pnVarPrepForOS($contentmoduledir).'/'.pnVarPrepForOS($contentmoduletemplate);
					} else {
						$path = 'themes/'.pnVarPrepForOS($id).'/pntemplates/modules/'.pnVarPrepForOS($contentmoduledir).'/'.pnVarPrepForOS($contentmoduletemplate);
					}
					$file_content =  pnModAPIFunc('Xanthia', 'admin', 'getFileContent', array('file_name' => $path));
									   
					if (!isset($file_content)){
						pnSessionSerVar('errormsg', _XA_FILECONTENTNOTSET);
						return false;
					}
									   
					$now = time();
					$sql = "INSERT INTO $themetplsourcetable ($themetplsourcecolumn[tpl_id],
															$themetplsourcecolumn[tpl_skin_id],
															$themetplsourcecolumn[tpl_file_name],
															$themetplsourcecolumn[tpl_source],
															$themetplsourcecolumn[tpl_secure],
															$themetplsourcecolumn[tpl_trusted],
															$themetplsourcecolumn[tpl_timestamp]) 
						  VALUES ('', 
						          '" . (int)pnVarPrepForStore($skinID) . "', 
								  '" . pnVarPrepForStore($contentmoduletemplate) . "',
								  '".addslashes($file_content)."', 
								  1, 
								  1, 
								  NOW( ))";
  
					$result =& $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0) {
						pnSessionSetVar('errormsg', $dbconn->ErrorMsg());
						return false;
					}

					$themetplfileinsert[] = array('tpl_id' => '',
												  'tpl_skin_id' => $skinID,
												  'tpl_module' => $contentmoduledir,
												  'tpl_skin_name' =>  $id,
												  'tpl_file' =>  $contentmoduletemplate,
												  'tpl_desc' => '',
												  'tpl_lastmodified' => $now,
												  'tpl_lastimported' => $now,
												  'tpl_type' => 'contentmodule');
				}
			}
		}
	}
							   
	$contentblockdirs = pnModAPIFunc('Xanthia','admin','getContentBlockDir',
						array('skin' => $id,
							  'tpltype' => 'blocks'));
							  
	if (!empty($contentblockdirs)){
		foreach ($contentblockdirs as $contentblockdir){
			$contentblocktemplates = pnModAPIFunc('Xanthia','admin','getBlockTplFiles',
								   array('skin' => $id,
										 'tpldir' => $contentblockdirs,
										 'tpltype' => 'blocks'));
																	 
			if (!empty($contentblocktemplates)){
				foreach ($contentblocktemplates as $contentblocktemplate) {
		    		if (is_dir(WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($id))) {
						$path = WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($id).'/pntemplates/blocks/'.pnVarPrepForOS($contentblockdir).'/'.pnVarPrepForOS($contentblocktemplate);
					}
					else {
						$path = 'themes/'.pnVarPrepForOS($id).'/pntemplates/blocks/'.pnVarPrepForOS($contentblockdir).'/'.pnVarPrepForOS($contentblocktemplate);
					}
					$file_content =  pnModAPIFunc('Xanthia', 'admin', 'getFileContent',array('file_name' => $path));
									  
					if (!isset($file_content)){
						pnSessionSerVar('errormsg', _XA_FILECONTENTNOTSET);
						return false;
					} 
					$now = time();
	
					$sql = "INSERT INTO $themetplsourcetable ($themetplsourcecolumn[tpl_id],
															  $themetplsourcecolumn[tpl_skin_id],
															  $themetplsourcecolumn[tpl_file_name],
															  $themetplsourcecolumn[tpl_source],
															  $themetplsourcecolumn[tpl_secure],
															  $themetplsourcecolumn[tpl_trusted],
															  $themetplsourcecolumn[tpl_timestamp])
							VALUES ('', 
							        '" . (int)pnVarPrepForStore($skinID) . "', 
									'" . pnVarPrepForStore($contentblocktemplate) . "', 
									'".addslashes($file_content)."',
									1, 
									1, 
									NOW( ))";
	
					$result =& $dbconn->Execute($sql);
	
					if ($dbconn->ErrorNo() != 0) {
						pnSessionSetVar('errormsg', $dbconn->ErrorMsg());
						return false;
					}
	
					$themetplfileinsert[] = array('tpl_id' => '',
												  'tpl_skin_id' => $skinID,
												  'tpl_module' => $contentblockdir,
												  'tpl_skin_name' =>  $id,
												  'tpl_file' =>  $contentblocktemplate,
												  'tpl_desc' => '',
												  'tpl_lastmodified' => $now,
												  'tpl_lastimported' => $now,
												  'tpl_type' => 'contentblock');
				}
				$result->Close();
			}
		}
	}
	if(pnModAPIFunc('Xanthia','admin','insertTplDB',array('themetplfiles' => $themetplfileinsert))){
		return true;
	} else {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_TEMPLATEDBINSERTFAILED));
		return false;
	}
}

/**
 * Get a list of install skins
 * @access                private
 * @author                mh7, ApathyBoy
 * @since                 0.926                29-08-2002
 * @param                 none
 * @return                array $array list of skins from themes dir
 */
function xanthia_adminapi_getContentModuleDir($args){
    
    // Extract our parameters
    extract($args);   
	if (is_dir(WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($skin))) {
		$file = WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($skin).'/pntemplates';
	} else {
		$file = 'themes/'.pnVarPrepForOS($skin).'/pntemplates';
	}
	
    if (file_exists ($file)){
        $handle = opendir($file);
        
        while ($f = readdir($handle)){
            if ($f != "." && $f != ".." && $f != "CVS" && $f != "blocks" && $f != "index.html" && !ereg("[.]",$f)){
                $dirlist[] = $f;
            }
        }
        closedir($handle);
        
        if(isset($dirlist)){
            return $dirlist;
        } else {
        }
    }
    return false;
}

/**
 * @access                private
 */
function xanthia_adminapi_getContentBlockDir($args)
{
    // Extract our parameters
    extract($args);
	if (is_dir(WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($skin))) {
		$file = WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($skin).'/pntemplates/blocks';
	} else {
		$file = 'themes/'.pnVarPrepForOS($skin).'/pntemplates/blocks';
	}
	
    if (file_exists ($file)){
        $handle = opendir($file);
        
        while ($f = readdir($handle)){
            if ($f != "." && $f != ".." && $f != "CVS" && $f != "index.html" && !ereg("[.]",$f)){
                $dirlist[] = $f;
            }
        }
        closedir($handle);
        
        if(isset($dirlist)){
            return $dirlist;
        }else {
        }
    }
    return false;
}

/**
 * @access                private
 */
function xanthia_adminapi_getModBlockTplFiles($args)
{
    extract($args);
    
    if (!isset($skin)){
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
        return false;
    }
	// Initialize the template array
	$tpl = '';
	$tpl = array();
	// Get the current skins name
	//$skinName = pnModAPIFunc('Xanthia','admin','getSkinFromID',
	//                                                        array('id' => $skin));
	// Open the template directory fo rthe skin
	
	if (is_dir(WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($skin))) {
		$filepath = WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($skin).'/templates/'.pnVarPrepForOS($tpltype);
	} else {
		$filepath = 'themes/'.pnVarPrepForOS($skin).'/templates/'.pnVarPrepForOS($tpltype);
	}
	
	if (file_exists ($filepath)){
		$dir = opendir($filepath);
		// Read through the directory
		while ($file = readdir($dir)) {
            if ((substr($file, -3, 3) == 'htm') && (substr($file, 0, 5) != 'prep-')){
                // Add valid files to the list
                $tpl[] = $file;
            }
        }
        // Close the template directory
        closedir($dir);
        // Sort and return the template file names
        sort($tpl);
        return $tpl;
	}
	return false;
}

/**
 * @access                private
 */
function xanthia_adminapi_getModuleTplFiles($args)
{
    // Extract our parameters
    extract($args);

	if (!isset($skin)){
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
		return false;
	}
        
	$tpl = '';
	$tpl = array();
	// Get the current skins name
	//$skinName = pnModAPIFunc('Xanthia','admin','getSkinFromID',
	//                                                        array('id' => $skin));
	// Open the template directory fo rthe skin
	if (is_dir(WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($skin))) {
		$filepath = WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($skin).'/pntemplates/'.pnVarPrepForOS($tpldir);
	} else {
		$filepath = 'themes/'.pnVarPrepForOS($skin).'/pntemplates/'.pnVarPrepForOS($tpldir);
	}
	
	if (file_exists ($filepath)){ 
		$dir = opendir($filepath);
        // Read through the directory
        while ($file = readdir($dir)){
            if ((substr($file, -3, 3) == 'htm') && (substr($file, 0, 5) != 'prep-')){
                // Add valid files to the list
                $tpl[] = $file;
            }
        }
        // Close the template directory
        closedir($dir);
        // Sort and return the template file names
        sort($tpl);
        return $tpl;
	}
	return false;
}

/**
 * @access                private
 */
function xanthia_adminapi_getBlockTplFiles($args)
{
    // Extract our parameters
    extract($args);

	if (!isset($skin)){
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
		return false;
	}
        
	$tpl = '';
	$tpl = array();
	// Get the current skins name
	//$skinName = pnModAPIFunc('Xanthia','admin','getSkinFromID',
	//                                                        array('id' => $skin));
	// Open the template directory fo rthe skin
	
	if (is_dir(WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($skin))) {
		$filepath = WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($skin).'/pntemplates/blocks/'.pnVarPrepForOS($tpldir);
	} else {
		$filepath = 'themes/'.pnVarPrepForOS($skin).'/pntemplates/blocks/'.pnVarPrepForOS($tpldir);	
	}
	
	if (file_exists ($filepath)){ 
		$dir = opendir($filepath);
        // Read through the directory
        while ($file = readdir($dir)){
            if ((substr($file, -3, 3) == 'htm') && (substr($file, 0, 5) != 'prep-')){
                // Add valid files to the list
                $tpl[] = $file;
            }
        }
        // Close the template directory
        closedir($dir);
        // Sort and return the template file names
        sort($tpl);
        return $tpl;
	}
	return false;
}

/**
 * @access                private
 */
function xanthia_adminapi_getThemeTplFile($args)
{
	// Extract our parameters
	extract($args);
	// Argument check
	if (!isset($skin) || !isset($zone) || !isset($type)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
		return false;
	}
        
	// Initialize the template array
	$tpl = array();
        
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();        
        
	$themetplfiletable = $pntable['theme_tplfile'];
	$themetplfilecolumn = &$pntable['theme_tplfile_column'];       
        
	$query = "SELECT $themetplfilecolumn[tpl_file]
					FROM $themetplfiletable
					WHERE $themetplfilecolumn[tpl_skin_id] = '" . (int)pnVarPrepForStore($skin) . "'
					AND $themetplfilecolumn[tpl_type] = '" . pnVarPrepForStore($type) . "'";
	// Execute the query
	$result =& $dbconn->Execute($query);
	// If no matches found return false
	if ($result->EOF) {
		return false;
	} else {
		// Organize our data
		$tpl = array();
		//modified for Oracle compatibility
		// while (list($zid, $name, $label, $type, $isactive) = $result->fields){
		while(!$result->EOF){
			list($tpl_file) = $result->fields;
			// Move that ADOdb pointer !
			$result->MoveNext();
			// Assign to associative array
			$tpl[] = $tpl_file;
		}
		// Always close the result set
		$result->Close();
		// return the result
	}        
	sort($tpl);
	return $tpl;
}

/**
 * @access                private
 */
function xanthia_adminapi_getTplFilesDB($args)
{
	// Extract our parameters
	extract($args);
	// Argument check
	if (!isset($skin) || !isset($zone)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
		return false;
	}
        
	// Initialize the template array
	$tpl = array();
        
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();        
        
	$themetplsourcetable = $pntable['theme_tplsource'];
	$themetplsourceecolumn = &$pntable['theme_tplsource_column'];       
        
	$query = "SELECT $themetplsourceecolumn[tpl_file_name]
					FROM $themetplsourcetable
					WHERE $themetplsourceecolumn[tpl_skin_id] = '" . (int)pnVarPrepForStore($skin) . "'";
	// Execute the query
	$result =& $dbconn->Execute($query);
	// If no matches found return false
	if ($result->EOF) {
		return false;
	} else {
		// Organize our data
	   $tpl = array();
		//modified for Oracle compatibility
		// while (list($zid, $name, $label, $type, $isactive) = $result->fields){
		while(!$result->EOF){
			list($tpl_file) = $result->fields;
			// Move that ADOdb pointer !
			$result->MoveNext();
			// Assign to associative array
			$tpl[] = $tpl_file;
		}
		// Always close the result set
		$result->Close();
		// return the result
	}        
	sort($tpl);
	return $tpl;
}

/**
 * @access                private
 */
function xanthia_adminapi_getFileContent($args) {

	extract($args);
	if (!isset($file_name)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
		return false;
	} 
         
    if (file_exists($file_name)) {
        $size = filesize($file_name);
        $fp = @fopen($file_name, 'r');
        if ($fp) {
            $file_content = fread($fp, $size);
            fclose($fp);
            return $file_content;
        }    
    }
    return false;
}

/**
 * @access                private
 */
function xanthia_adminapi_listPaletteID($args)
{
    // Extract our parameters
    //$skinid = pnVarCleanFromInput('skinid');
    extract($args);

    // Setup the database object
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Verify the skins ID, assign default if null
    if (empty($skinid)) {
        $skinid = pnModAPIFunc('Xanthia','user','getSkinID');
    }
 
    // Setup the databse column pointers
    $column = &$pntable['theme_palette_column'];

    // Build the DB query
    $query = "SELECT $column[palette_id], $column[skin_id]
            FROM $pntable[theme_palette]
            WHERE $column[skin_id] = '" . (int)pnVarPrepForStore($skinid) . "'";

    // Execute the query
    $result =& $dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0) {
		pnSessionSetVar('errormsg', $dbconn->ErrorMsg());
		return false;
	}

    // If no matches found, return false to the API
    if ($result->EOF) {
        return false;
    }
	$array = array();
	while(!$result->EOF){
		list($palette_id,$skin_id) = $result->fields;
		// Move that ADOdb pointer !
		$result->MoveNext();
		// Assign to associative array
		$array[] = array('palette_id' => $palette_id,
						  'skin_id'     => $skin_id);
	}
	// Always close the result set
	$result->Close();
	// return the result
	return $array;

}

/**
 * @access                private
 */
function xanthia_adminapi_getEditTemplate($args)
{
    // Extract our parameters
    //$skinid = pnVarCleanFromInput('skinid');
    extract($args);
	//list($skinid,$tpl) = pnVarCleanFromInput('skinid','tpl');

    // Setup the database object
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Verify the skins ID, assign default if null
    if (empty($skinid)) {
        $skinid = pnModAPIFunc('Xanthia','user','getSkinID');
    }
 
    // Setup the databse column pointers
    $column = &$pntable['theme_tplsource_column'];

    // Build the DB query
    $query = "SELECT $column[tpl_id],
                     $column[tpl_skin_id],
                     $column[tpl_file_name],
                     $column[tpl_source],
                     $column[tpl_timestamp]
            FROM $pntable[theme_tplsource]
            WHERE $column[tpl_skin_id] = '" . (int)pnVarPrepForStore($skinid) . "' 
            AND $column[tpl_file_name] = '" . pnVarPrepForStore($tpl) . "'";

    // Execute the query
    $result =& $dbconn->Execute($query);

	if ($dbconn->ErrorNo() != 0) {
		pnSessionSetVar('errormsg', $dbconn->ErrorMsg());
		return false;
	}

    // If no matches found, return false to the API
    if ($result->EOF) {
        pnSessionSetVar('errormsg', "FALSE");
        return false;
    }
	$array = array();
	while(!$result->EOF){
		list($tpl_id,
			 $tpl_skin_id,
			 $tpl_file_name,
			 $tpl_source,
			 $tpl_timestamp) = $result->fields;
					         
		// Move that ADOdb pointer !
		$result->MoveNext();
		// Assign to associative array
		$array = array('id' => $tpl_id,
						 'skin_id'  => $tpl_skin_id,
						 'file'      => $tpl_file_name,
						 'source'    => $tpl_source,
						 'timestamp' => $tpl_timestamp, );
	}
	// Always close the result set
	$result->Close();
   
	return $array;

}

/**
 * @access                private
 */
function xanthia_adminapi_insertTplDB($args)
{
	// Check Permissions
	if (!pnSecAuthAction(0, 'Xanthia', '::', ACCESS_EDIT)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_MODULENOAUTH));
		return false;
	}
	// Extract our parameters
	extract($args);
    // Argument check
    // if (!isset($tpl_skin_id) || !isset($tpl_module) || !isset($tpl_type))
    // {
    //         pnSessionSetVar('errormsg', "error in function xanthia_adminapi_insertTplDB 1652");
    //         return false;
    // }

	// Setup the DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	// Setup the table and column info
	$themetplfiletable = $pntable['theme_tplfile'];
	$themetplfilecolumn = &$pntable['theme_tplfile_column'];

	// Build the query
	foreach ($themetplfiles as $themetplfile) {
        
        $query = "SELECT $themetplfilecolumn[tpl_id]
                                FROM $themetplfiletable
                                WHERE $themetplfilecolumn[tpl_skin_id]='".pnVarPrepForStore($themetplfile['tpl_skin_id'])."'
                                AND $themetplfilecolumn[tpl_module]='".pnVarPrepForStore($themetplfile['tpl_module'])."'
                                AND $themetplfilecolumn[tpl_file]='".pnVarPrepForStore($themetplfile['tpl_file'])."'";
        // Execute the query
        $result =& $dbconn->Execute($query);
        // Determine if the zone label already exists
        if ($result->EOF) {
			// No such label, build the update statement
			$sql = "INSERT INTO $themetplfiletable (
									$themetplfilecolumn[tpl_id],
									$themetplfilecolumn[tpl_skin_id],
									$themetplfilecolumn[tpl_module],
									$themetplfilecolumn[tpl_skin_name],
									$themetplfilecolumn[tpl_file],
									$themetplfilecolumn[tpl_desc],
									$themetplfilecolumn[tpl_lastmodified],
									$themetplfilecolumn[tpl_lastimported],
									$themetplfilecolumn[tpl_type]
							) VALUES (
									'".pnVarPrepForStore($themetplfile['tpl_id'])."',
									'".pnVarPrepForStore($themetplfile['tpl_skin_id'])."',
									'".pnVarPrepForStore($themetplfile['tpl_module'])."',
									'".pnVarPrepForStore($themetplfile['tpl_skin_name'])."',
									'".pnVarPrepForStore($themetplfile['tpl_file'])."',
									'".pnVarPrepForStore($themetplfile['tpl_desc'])."',
									'".pnVarPrepForStore($themetplfile['tpl_lastmodified'])."',
									'".pnVarPrepForStore($themetplfile['tpl_lastimported'])."',
									'".pnVarPrepForStore($themetplfile['tpl_type'])."')";
			// Update the DB
			$result =& $dbconn->Execute($sql);
			// Always close the result set
			$result->Close();
        } else {
			// Zone label exists, report error
			$result->Close();
			pnSessionSetVar('errormsg', $dbconn->ErrorMsg());
			return false;
        }
	}
	// Update successful, return true
	return true;
}

/**
 * @access                private
 */
function xanthia_adminapi_getTplTypeDB($args)
{
	// Extract our parameters
	extract($args);
	// Argument check
	if (!isset($skin) || !isset($zone)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
		return false;
	}
        
	// Initialize the template array
	$tpl = array();
	//echo $skin;
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();        
	$themetplfiletable = $pntable['theme_tplfile'];
	$themetplfilecolumn = &$pntable['theme_tplfile_column'];
	//$themetplsourcetable = $pntable['theme_tplsource'];
	//$themetplsourceecolumn = &$pntable['theme_tplsource_column'];       
        
	$sql = "SELECT $themetplfilecolumn[tpl_module],
	               $themetplfilecolumn[tpl_file],
                   $themetplfilecolumn[tpl_type]
            FROM $themetplfiletable
	        WHERE $themetplfilecolumn[tpl_skin_name]  = '" . pnVarPrepForStore($skin) . "'
	        ORDER BY $themetplfilecolumn[tpl_file]";
	// Execute the query
	$result =& $dbconn->Execute($sql);
	// If no matches found return false
	if ($result->EOF) {
		return false;
	} else {
		// Organize our data
        $tpl = array();
		//modified for Oracle compatibility
		/* while (list($zid, $name, $label, $type, $isactive) = $result->fields){
		while(!$result->EOF){
				list($tpl_file) = $result->fields;
				// Move that ADOdb pointer !
				$result->MoveNext();
				// Assign to associative array
				$tpl[] = $tpl_file;
		}
        */ // Always close the result set

		//modified for Oracle compatibility
		// while (list($zid, $name, $label, $type, $isactive) = $result->fields){
		while(!$result->EOF){
				list($tpl_module, $tpl_file, $type) = $result->fields;
				// Move that ADOdb pointer !
				$result->MoveNext();
				// Assign to associative array
				$tpl[] = array('tpl_module' => $tpl_module,
							   'tpl_file'   => $tpl_file,
							   'type'       => $type);
		}
		// Always close the result set

		$result->Close();
		// return the result
	}        
	//sort($tpl);
	return $tpl;
}

/**
 * @access                private
 */
function xanthia_adminapi_updateDbTemplate($args)
{
	// Check Permissions
	if (!pnSecAuthAction(0, 'Xanthia', '::', ACCESS_EDIT)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_MODULENOAUTH));
		return false;
	}
	// Extract our parameters
	extract($args);
	// Argument check
	if (!isset($tpl_id) || !isset($skin_id) || !isset($tpl_source) || !isset($tpl_file)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
		return false;
	}
	// Setup the DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();

	// Setup the table and columns info
	$themetplsourcetable = $pntable['theme_tplsource'];
	$themetplsourcecolumn = &$pntable['theme_tplsource_column'];
	$themetplfiletable = $pntable['theme_tplfile'];
	$themetplfilecolumn = &$pntable['theme_tplfile_column'];

	$query = "SELECT $themetplsourcecolumn[tpl_id]
							FROM $themetplsourcetable
							WHERE $themetplsourcecolumn[tpl_skin_id] = '" . (int)pnVarPrepForStore($skin_id) . "'
							AND $themetplsourcecolumn[tpl_file_name] = '" . pnVarPrepForStore($tpl_file) . "'";
	// Execute the query
	$result =& $dbconn->Execute($query);
	// Determine if this is a new template or an update
	if (!$result->EOF) {
		// Existing entry, update record
		$result->Close();
		$sql = "UPDATE $themetplsourcetable
						SET $themetplsourcecolumn[tpl_source]='".pnVarPrepForStore($tpl_source)."',
						$themetplsourcecolumn[tpl_timestamp] = NOW( )
						WHERE $themetplsourcecolumn[tpl_skin_id] = '" . (int)pnVarPrepForStore($skin_id) . "'
						AND $themetplsourcecolumn[tpl_file_name] = '" . pnVarPrepForStore($tpl_file) . "'";
	} else {
		// New entry, create record
		$sql = "INSERT INTO $table (
								$column[skin_id],
								$column[zone_label],
								$column[tpl_file]
						) VALUES (
								'" . (int)pnVarPrepForStore($skin) . "',
								'" . pnVarPrepForStore($zone) . "',
								'" . pnVarPrepForStore($tpl) . "')";
	}
	// Update the DB
	$result =& $dbconn->Execute($sql);
	// Always close the result set
	$result->Close();
	$now = time();
	$sql = "UPDATE $themetplfiletable
					SET $themetplfilecolumn[tpl_lastmodified] = '" . pnVarPrepForStore($now) . "'
					WHERE $themetplfilecolumn[tpl_skin_id] = '" . (int)pnVarPrepForStore($skin_id) . "'
					AND $themetplfilecolumn[tpl_file] = '" . pnVarPrepForStore($tpl_file) . "'";
		 
	$result =& $dbconn->Execute($sql);
	// Always close the result set
	$result->Close();
	// Update successful, return true
	return true;
}

/**
 * @access                private
 */
function xanthia_adminapi_addTplFile($args)
{
	extract($args);
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();        
	
	$themetplsourcetable = $pntable['theme_tplsource'];
	$themetplsourcecolumn = &$pntable['theme_tplsource_column'];  
                            
	$now = time();
	//$nextId = $dbconn->GenId($themetplsourcetable);

	//echo $themetplsourcecolumn['tpl_id'];

	$sql = "INSERT INTO $themetplsourcetable (
						$themetplsourcecolumn[tpl_id],
						$themetplsourcecolumn[tpl_skin_id],
						$themetplsourcecolumn[tpl_file_name],
						$themetplsourcecolumn[tpl_source],
						$themetplsourcecolumn[tpl_secure],
						$themetplsourcecolumn[tpl_trusted],
						$themetplsourcecolumn[tpl_timestamp]) 
			VALUES ('',
					'" . (int)pnVarPrepForStore($skinid) . "',
					'" . pnVarPrepForStore($sourceTpl) . "',
					'".addslashes($source)."',
					1,
					1,
					NOW( ))";
	
	$result =& $dbconn->Execute($sql);
          
	if ($dbconn->ErrorNo() != 0) {
		pnSessionSetVar('errormsg', $dbconn->ErrorMsg());
		return false;
	}
	$themetplfileinsert[] = array('tpl_id' => '',
								  'tpl_skin_id' => $skinid,
								  'tpl_module' => $tpl_module,
								  'tpl_skin_name' =>  $newtheme,
								  'tpl_file' =>  $sourceTpl,
								  'tpl_desc' => '',
								  'tpl_lastmodified' => $now,
								  'tpl_lastimported' => $now,
								  'tpl_type' => $newtype);
    
	$result->Close();

	if(pnModAPIFunc('Xanthia','admin','insertTplDB', array('themetplfiles' => $themetplfileinsert))){    
    	return true;
	} else{ 
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_TEMPLATEDBINSERTFAILED));
		return false;
	}
						      
}

/**
 * @access                private
 */
function xanthia_adminapi_isBcSet($args){
	// Check Permissions
	if (!pnSecAuthAction(0, 'Xanthia', '::', ACCESS_EDIT)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_MODULENOAUTH));
		return false;
	}
	// Extract our parameters
	extract($args);
	// Argument check
	if (!isset($skin) || !isset($module)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
		return false;
	}
	if($module == 'SetAll'){
		return true;
	}
	// Setup the DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	// Setup the table and column info
	$table = $pntable['theme_blcontrol'];
	$column = &$pntable['theme_blcontrol_column'];
	// Build the query
	$query = "SELECT $column[module]
							FROM $table
							WHERE $column[block]='-1'
							AND $column[theme]='".pnVarPrepForStore($skin)."' AND $column[module] = '".pnVarPrepForStore($module)."'";
	// Execute the query
	$result =& $dbconn->Execute($query);
	// Determine if the zone label already exists
	if ($result->EOF) {
		$block = '-1';
		$position="0";
		$weight= "0.0";
          
		// No such label, build the update statement
		$sql = "INSERT INTO $table ($column[module],
									$column[block],
									$column[theme],
									$column[identi],
									$column[position],
									$column[weight]) 
		VALUES ('".pnVarPrepForStore($module)."',
				'".pnVarPrepForStore($block)."',
				'".pnVarPrepForStore($skin)."',
				'".pnVarPrepForStore(@$identi)."',
				'".pnVarPrepForStore($position)."',
				'".pnVarPrepForStore($weight)."')";
	} else {
		// Zone label exists, report error
		$result->Close();
		return true;
	}
	// Update the DB
	$result =& $dbconn->Execute($sql);
	// Always close the result set
	$result->Close();
	// Update successful, return true
	return true;
}

/**
 * @access                private
 */
function xanthia_adminapi_setBcOnInstall($args)
{
	extract($args);
	
	if (!isset($skin)){
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
		return false;
	}
        
    if (!pnModAPILoad('Modules', 'admin')) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAIL));
		return false;
    }

	// load the blocks db info
	pnModDBInfoLoad('blocks');
    
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();

	// Define table & column
	$themeblcontroltable = $pntable['theme_blcontrol'];
	$themeblcontrolcolumn = &$pntable['theme_blcontrol_column']; 
        
	$blockstable = $pntable['blocks'];
	$blockscolumn = &$pntable['blocks_column'];

	$sql = "SELECT $blockscolumn[bid],
	               $blockscolumn[title],
				   $blockscolumn[position],
				   $blockscolumn[weight]
		FROM $blockstable WHERE $blockscolumn[active] = '1'
		ORDER BY $blockscolumn[title]";
	$result =& $dbconn->Execute($sql);

	if ($result->EOF) {
		return false;
    }

    $resarray = array();
	//modified for Oracle compatibility
	// while(list($bid, $bkey, $title, $active, $position) = $result->fields) {
	while(!$result->EOF){
		list($bid, $title, $position, $weight) = $result->fields;
        $result->MoveNext();

        $blocks[] = array('bid' => $bid,
		                  'title' => $title,
                          'position' => $position,
                          'weight' => $weight);
    }
    $result->Close();
           
    $mods = pnModAPIFunc('Modules', 'admin', 'list');
    foreach($mods as $mod) {
        
		if(!pnModAPIFunc('Xanthia','admin','isBcSet',
			array('module' => $mod['name'],
				  'skin' => $skin ))){
			pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_COULDNOTSETBLOCKCONTROL));
			return false;
		}
		foreach($blocks as $block){
			$blocktitle = strtolower(strip_tags($block['title']));
			$blocktitle = ereg_replace("[^0-9a-z_]","",$blocktitle);
		
			$sql = "INSERT INTO $themeblcontroltable ($themeblcontrolcolumn[module],
													  $themeblcontrolcolumn[block],
													  $themeblcontrolcolumn[theme],
													  $themeblcontrolcolumn[identi],
													  $themeblcontrolcolumn[position],
													  $themeblcontrolcolumn[weight]) 
												VALUES ('".pnVarPrepForStore($mod['name'])."',
														'".pnVarPrepForStore($block['bid'])."',
														'".pnVarPrepForStore($skin)."',
														'".pnVarPrepForStore($block['bid'])."',
														'".pnVarPrepForStore($block['position'])."',
														'".pnVarPrepForStore($block['weight'])."')";
	
			$result =& $dbconn->Execute($sql);  
        }
   }
   $result->Close();
   return true;     
}

/**
 * Create a palette in the db
 * @access private
 * @author Mark West
 */
function CreateTheme($skinname)
{
	// Setup DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
            
	// Setup the table and column info
	$themeskinstable = $pntable['theme_skins'];
	$themeskinscolumn = &$pntable['theme_skins_column'];

	// Get the next available skin id
	$id = $dbconn->GenId($themeskinstable);

	/*
	* Insert the theme into the database this code should not be edited!!!
	*/
	$dbconn->Execute("INSERT INTO $themeskinstable VALUES ($id, '".pnVarPrepForStore($skinname)."',1,0)");

	//Do not edit this one
	pnModSetVar('Xanthia', $skinname.'use','');

    // load the user api
	pnModAPILoad('Xanthia', 'user');

    // make sure we have the correct skin id by calling the api
    $skinid = pnModAPIFunc('Xanthia','user','getSkinID', array('skin' => $skinname));
        
	// return the skin ind
	return $skinid;

}
/**
 * Create a palette in the db
 * @access private
 * @author Mark West
 */
function CreatePalette($skinname, $skinid, $default, $name, $background, $color1, $color2, $color3, $color4, 
                       $color5, $color6, $color7, $color8, $sepcolor, $text1, $text2, $link, $vlink, $hover)
{

	// Setup DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
            
	// Setup the table and column info
	$themepalettestable = $pntable['theme_palette'];
	$themepalettecolumn = &$pntable['theme_palette_column'];

    // execute sql statement to insert palette in db
	$dbconn->Execute("INSERT INTO $themepalettestable VALUES (''
															,'" . pnVarPrepForStore($name) . "'
															,'" . (int)pnVarPrepForStore($skinid) . "'
															,''
															,'1'
															,'" . pnVarPrepForStore($background) . "'
															,'" . pnVarPrepForStore($color1) . "'
															,'" . pnVarPrepForStore($color2) . "'
															,'" . pnVarPrepForStore($color3) . "'
															,'" . pnVarPrepForStore($color4) . "'
															,'" . pnVarPrepForStore($color5) . "'
															,'" . pnVarPrepForStore($color6) . "'
															,'" . pnVarPrepForStore($color7) . "'
															,'" . pnVarPrepForStore($color8) . "'
															,'" . pnVarPrepForStore($sepcolor) . "'
															,'" . pnVarPrepForStore($text1) . "'
															,'" . pnVarPrepForStore($text2) . "'
															,'" . pnVarPrepForStore($link) . "'
															,'" . pnVarPrepForStore($vlink) . "'
															,'" . pnVarPrepForStore($hover) . "')");
    if ($default == 1) {
/*		$query = "SELECT $themepalettecolumn[palette_id] as useid
			FROM $themepalettestable
			WHERE $themepalettecolumn[skin_id]='$skinid' LIMIT 1";
			$result =& $dbconn->Execute($query);
			list($useid) = $result->fields;
			$result->Close();*/
		// Get the ID of the item that we inserted.  It is possible, although
		// very unlikely, that this is different from $nextId as obtained
		// above, but it is better to be safe than sorry in this situation
		$useid = $dbconn->PO_Insert_ID($themepalettestable, $themepalettecolumn['palette_id']);
		pnModSetVar('Xanthia', $skinname.'use',$useid);
		pnModAPIFunc('Xanthia', 'admin', 'writestylesheet', array('skinid' => $skinid,
																   'paletteid' => $useid));
	
	}
	if ($dbconn->ErrorNo() != 0) {
		return false;
	}

}

/**
 * Create a theme configuration item
 * @access private
 * @author Mark West
 */
function CreateThemeVar($skinid, $name, $description, $value)
{
	// Setup DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();

	//  Setup the table and column
	$themeconfigtable = $pntable['theme_config'];
	$themeconfigcolumn = &$pntable['theme_config_column'];

	// Insert Data into _themes_config
	$dbconn->Execute("INSERT INTO $themeconfigtable
						VALUES ('" . (int)pnVarPrepForStore($skinid) . "',
								'" . pnVarPrepForStore($name) . "',
								'" . pnVarPrepForStore($description) . "',
								'" . pnVarPrepForStore($value) . "',
								'')");
	if ($dbconn->ErrorNo() != 0) {
		return false;
	}

}

/**
 * Create a theme configuration item
 * @access private
 * @author Mark West
 */
function CreateThemeTemplate($skinid, $label, $file, $type)
{
	// Setup DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();

	// Setup the table and column info
	$themelayouttable = $pntable['theme_layout'];
	$themelayoutcolumn = &$pntable['theme_layout_column'];
			
	// Insert Data into _themes_layout
	$dbconn->Execute("INSERT INTO $themelayouttable 
						VALUES ('" . (int)pnVarPrepForStore($skinid) . "',
								'" . pnVarPrepForStore($label) . "',
								'" . pnVarPrepForStore($file) . "',
								'" . pnVarPrepForStore($type) . "')");
	if ($dbconn->ErrorNo() != 0) {
		return false;
	}

}
/**
 * Create a theme configuration item
 * @access private
 * @author Mark West
 */
function CreateThemeZone($skinid, $zonename, $zonelabel, $type, $active, $skintype)
{
	// Setup DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();

	// Setup the table and column info
	$themezonestable = $pntable['theme_zones'];
	$themezonescolumn = &$pntable['theme_zones_column'];

	// Get the next available zone id
	$id = $dbconn->GenId($themezonestable);
        
	// Insert Data into _theme_zones
	$dbconn->Execute("INSERT INTO $themezonestable
						VALUES ('" . pnVarPrepForStore($id) . "',
								'" . pnVarPrepForStore($skinid) . "',
								'" . pnVarPrepForStore($zonename) . "',
								'" . pnVarPrepForStore($zonelabel) . "',
								'" . pnVarPrepForStore($type) . "',
								'" . pnVarPrepForStore($active) . "',
								'" . pnVarPrepForStore($skintype) . "')");
	if ($dbconn->ErrorNo() != 0) {
		return false;
	}

}

function xanthia_adminapi_themedelete($args)
{
    // Check if the user has permission to perform this action
	if (!pnSecAuthAction(0, 'Xanthia', '::', ACCESS_EDIT)) {
		return false;
	}

    // extract all arguments passed to this function
	extract($args);
    
	// set the skin name from the id passed this function
	if(isset($id)) {
    	$skinName = $id;
	} else {
		return false;
	}
                
    // load the user api
	pnModAPILoad('Xanthia', 'user');

	// call the api function to get the skin id  
	$skinID = pnModAPIFunc('Xanthia',
							'user',
							'getSkinID',
		      				array('skin' => $skinName));
    
	// delete from the db here
	// Setup DB handle
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

	// Setup the table and column info
	$themeblockcontroltable = $pntable['theme_blcontrol'];
	$themeblockcontrolcolumn = &$pntable['theme_blcontrol_column'];
		
	// Delete info from _theme_blcontrol
	$dbconn->Execute("DELETE FROM $themeblockcontroltable WHERE $themeblockcontrolcolumn[theme] = '" . pnVarPrepForStore($skinName) . "'");
 
	// Setup the table and column info
	$themeskinstable = $pntable['theme_skins'];
	$themeskinscolumn = &$pntable['theme_skins_column'];
		
	// Delete info from _theme_skins
	$dbconn->Execute("DELETE FROM $themeskinstable WHERE $themeskinscolumn[skin_id] = '" . pnVarPrepForStore($skinID) . "'");

	$themepalettestable = $pntable['theme_palette'];
	$themepalettecolumn = &$pntable['theme_palette_column'];
    
	// Delete info from _theme_skins
	$dbconn->Execute("DELETE FROM $themepalettestable WHERE $themepalettecolumn[skin_id] = '" . pnVarPrepForStore($skinID) . "'");

	// Setup the table and column info
	$themeconfigtable = $pntable['theme_config'];
	$themeconfigcolumn = &$pntable['theme_config_column'];
    
	// Delete info from _themes_config
	$dbconn->Execute("DELETE FROM $themeconfigtable WHERE $themeconfigcolumn[skin_id] = '" . pnVarPrepForStore($skinID) . "'");
    
	// Setup the table and column info
	$themelayouttable = $pntable['theme_layout'];
	$themelayoutcolumn = &$pntable['theme_layout_column'];
    
	// Delete info from _theme_layout
	$dbconn->Execute("DELETE FROM $themelayouttable WHERE $themelayoutcolumn[skin_id] = '" . pnVarPrepForStore($skinID) . "'");
    
	// Setup the table and column info
	$themezonestable = $pntable['theme_zones'];
	$themezonescolumn = &$pntable['theme_zones_column'];
    
	// Delete info from _theme_zones
	$dbconn->Execute("DELETE FROM $themezonestable WHERE $themezonescolumn[skin_id] = '" . pnVarPrepForStore($skinID) . "'");
	
	$themetplfiletable = $pntable['theme_tplfile'];
	$themetplfilecolumn = &$pntable['theme_tplfile_column'];
	
	// Delete info from _theme_tplfile
	$dbconn->Execute("DELETE FROM $themetplfiletable WHERE $themetplfilecolumn[tpl_skin_id] = '" . pnVarPrepForStore($skinID) . "'");
	
	$themetplsourcetable = $pntable['theme_tplsource'];
	$themetplsourcecolumn = &$pntable['theme_tplsource_column'];
	
	// Delete info from _theme_tplset
	$dbconn->Execute("DELETE FROM $themetplsourcetable WHERE $themetplsourcecolumn[tpl_skin_id] = '" . pnVarPrepForStore($skinID) . "'");
	
    // remove theme module vars	
	pnModDelVar('Xanthia', $skinName.'palette');
	pnModDelVar('Xanthia', $skinName.'use');
	pnModDelVar('Xanthia', $skinName.'themecache');
	
	if (!class_exists('Smarty')){
		include_once 'includes/classes/Smarty/Smarty.class.php';
	}
	
	$DelteDTS =& new Smarty;
	$DelteDTS->compile_id = "$skinName".pnUserGetLang().'';
	$DelteDTS->compile_dir = pnConfigGetVar('temp') . '/Xanthia_compiled';
	$DelteDTS->cache_dir = pnConfigGetVar('temp') . '/Xanthia_cache';
    $DelteDTS->caching = true;
	$DelteDTS->use_sub_dirs = false;
	$DelteDTS->clear_compiled_tpl();
    $DelteDTS->clear_all_cache();

    $modules = pnModGetAllMods();
    
    	$pnRender =& new Smarty();
    	$pnRender->compile_dir = pnConfigGetVar('temp') . '/pnRender_compiled';
		$pnRender->cache_dir = pnConfigGetVar('temp') . '/pnRender_cache';
		$pnRender->use_sub_dirs = false;
		$pnRender->caching = true;
		
		foreach ($modules as $module) {
			
			$pnRender->compile_id = $module['name'] . '|' . $skinName . '|' . pnUserGetLang();
			$cache_id = ''.$module['name'].'' . '||' .''.$module['name'].''  . '|' .''.$skinName .''. '|' . ''.pnUserGetLang().'';
			$pnRender->clear_compiled_tpl();
			$pnRender->clear_cache(null,$cache_id);
			
		}

	return true;
}

function xanthia_adminapi_writesettingscache($args)
{
    // extract all arguments passed to this function
	extract($args);

	if (!isset($skinid)) {
		return false;
	}

	// Setup the DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();

	// Define the table and columns info
	$table = $pntable['theme_config'];
	$column = &$pntable['theme_config_column'];

	// Build the query
	$query = "SELECT $column[name],
					 $column[setting]
			  FROM $table
			  WHERE $column[skin_id]='".(int)pnVarPrepForStore($skinid)."'";

	// Execute the query
	$result =& $dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0) {
		// Report the DB error
		$this->db_error((__FILE__), (__LINE__), 'cache_general_config',
				$query, $dbconn->ErrorNo(), $dbconn->ErrorMsg());
	} else {
		if (!$result->EOF) {
			// Iterate through the results, assign to config
			// modified for Oracle compatibility
			$settingscode = "<?php\n";
			while(!$result->EOF){
				list($name, $setting) = $result->fields;
				// Move the pointer !
				$result->MoveNext();
				$settingscode .= '$this->config' . "['{$name}'] = '$setting';\n";
			}
			$settingscode .= "\n?>";
			// Close the result set
			$result->Close();
		}
	}
	
    // Load user API
	if (!pnModAPILoad('Xanthia','user')) {	    
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	
	// get the theme name
	$skin = pnModAPIFunc('Xanthia','user','getSkinFromID', array('id' => $skinid));

	// write the array to the file
	$return = true;
	if (!$file = fopen(pnConfigGetVar('temp') . '/Xanthia_Config/'. $skin . '.settings.php', 'w+')) {
		$return = false;
	}
	if (!fwrite($file, $settingscode)) {
		$return = false;
	}
	fclose($file);

	return $return;
}

function xanthia_adminapi_writezonescache($args)
{
    // extract all arguments passed to this function
	extract($args);

	if (!isset($skinid)) {
		return false;
	}

	if (!isset($modules)) {
		$modules = pnModGetAllMods();
	}else{
		if(!is_array($modules)){
			$modename = array();
			$modename['name'] = $modules;
			$modules = array();
			$modules[] = $modename;
		}
	}
	// Setup the DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();

	$column = &$pntable['theme_blcontrol_column'];
	
    // Load user API
	if (!pnModAPILoad('Xanthia','user')) {	    
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	// get the theme name
	$skin = pnModAPIFunc('Xanthia','user','getSkinFromID', array('id' => $skinid));

	$return = true;
	foreach ($modules as $module) {
		
		$sql="SELECT $column[block] as block
					FROM $pntable[theme_blcontrol] 
					WHERE $column[module] = '" . pnVarPrepForStore($module['name']) . "'
					AND $column[block] = '-1'
					AND $column[theme] = '" . pnVarPrepForStore($skin) ."'";
		$result2 =& $dbconn->Execute($sql);
		while(!$result2->EOF) {

		$sql="SELECT $column[block] as block, 
					$column[position] as position, 
					$column[weight] as weight
					FROM $pntable[theme_blcontrol] 
					WHERE $column[module] = '" . pnVarPrepForStore($module['name']) . "'
					AND $column[block] != '-1'
					AND $column[theme] = '" . pnVarPrepForStore($skin) ."' 
					ORDER BY $column[position], $column[weight]";
		$result =& $dbconn->Execute($sql);
		$moduleblocks = array();
		while(!$result->EOF) {
			$row = $result->GetRowAssoc(false);
			$moduleblocks[]  = $row;
			$result->MoveNext();
		}
		$result->close();
		$result2->MoveNext();
		}
		$result2->close();
		unset($blockcode);
		if (!empty($moduleblocks)) {
			$blockcode = '<?php $moduleblockzones = array (';
			foreach ($moduleblocks as $moduleblock) {
				if (isset($tempzone) && $tempzone != $moduleblock['position']) {
					$blockcode .= "',";
					unset($tempzone);
				}
				if (isset($tempzone)) {
					$blockcode .= ":$moduleblock[block]";
				} else {
					$blockcode .= "\n'$moduleblock[position]' => '$moduleblock[block]";
					$tempzone = $moduleblock['position'];
				}
			}
			unset($tempzone);
			$blockcode .= "');?>";
		} else {
			$blockcode = '<?php ?>';
		}
		if (!$file = fopen(pnConfigGetVar('temp') . '/Xanthia_Config/'. $skin . '.' . $module['name'] . '.config.php', 'w+')) {
			$return = false;
		}
		if (!fwrite($file, $blockcode)) {
			$return = false;
		}
		fclose($file);
		unset($moduleblocks);
	}
	
	//Remove after testing
	//if (!pnModAPIFunc('Xanthia', 'admin', 'writethemetplcache', array('skinid' => $skinid, 'modules' => $modename['name']))) {
		//$return = false;
	//}
	return $return;
}

function xanthia_adminapi_writepalettescache($args)
{
    // extract all arguments passed to this function
	extract($args);

	if (!isset($skinid)) {
		return false;
	}

	// Setup the DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();

    // Setup the databse column pointers
	$table = $pntable['theme_palette'];
	$column = &$pntable['theme_palette_column'];

			$palettescode = "<?php\n";

    // Load user API
	if (!pnModAPILoad('Xanthia','user')) {	    
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
			$skin = pnModAPIFunc('Xanthia','user','getSkinFromID', array('id' => $skinid));
			$paletteid = pnModGetVar('Xanthia',$skin.'use');
				$query = "SELECT $column[palette_name],
				                 $column[background],
								 $column[color1],
								 $column[color2],
								 $column[color3],
								 $column[color4],
								 $column[color5],
								 $column[color6],
								 $column[color7],
								 $column[color8],
								 $column[sepcolor],
								 $column[text1],
								 $column[text2],
								 $column[link],
								 $column[vlink],
								 $column[hover]        
							FROM $table
							WHERE $column[skin_id] = '" . pnVarPrepForStore($skinid) . "' 
							AND $column[palette_id] = '" . pnVarPrepForStore($paletteid) . "'";
				// Execute the query
				$result2 =& $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					// Report the DB error
					$this->db_error((__FILE__), (__LINE__), 'cache_general_config',
							$query, $dbconn->ErrorNo(), $dbconn->ErrorMsg());
				} else {
					//if (!$result->EOF) {
						list($palette_name,
						     $background,
							 $color1,
							 $color2,
							 $color3,
							 $color4,
							 $color5,
							 $color6,
							 $color7,
							 $color8,
							 $sepcolor,
							 $text1,
							 $text2,
							 $link,
							 $vlink,
							 $hover) = $result2->fields;
						$palettescode .=  '$colors = array(\'palette_name\' => '. " '$palette_name',\n" .
						                  '\'background\'  => ' . " '$background',\n" .
										  '\'color1\'      => ' . " '$color1',\n" .
										  '\'color2\'      => ' . " '$color2',\n" .
										  '\'color3\'      => ' . " '$color3',\n" .
										  '\'color4\'      => ' . " '$color4',\n" .
										  '\'color5\'      => ' . " '$color5',\n" .
										  '\'color6\'      => ' . " '$color6',\n" .
										  '\'color7\'      => ' . " '$color7',\n" .
										  '\'color8\'      => ' . " '$color8',\n" .
										  '\'sepcolor\'    => ' . " '$sepcolor',\n" .
										  '\'text1\'       => ' . " '$text1',\n" .
										  '\'text2\'       => ' . " '$text2',\n" .
										  '\'link\'        => ' . " '$link',\n" .
										  '\'vlink\'       => ' . " '$vlink',\n" .
										  '\'hover\'       => ' . " '$hover');\n\n" .

						$result2->MoveNext();
					}

			$palettescode .= "\n?>";

			$result2->Close();

	// get the theme name
	$skin = pnModAPIFunc('Xanthia','user','getSkinFromID', array('id' => $skinid));

	// write the array to the file
	$return = true;
	if (!$file = fopen(pnConfigGetVar('temp') . '/Xanthia_Config/'. $skin . '.palettes.php', 'w+')) {
		$return = false;
	}
	if (!fwrite($file, $palettescode)) {
		$return = false;
	}
	fclose($file);

	return $return;

}

function xanthia_adminapi_writestylesheet($args)
{
    // extract all arguments passed to this function
	extract($args);

	if (!isset($skinid)) {
		return false;
	}

    // Load user API
	if (!pnModAPILoad('Xanthia','user')) {	    
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	// get the theme name
	$skin = pnModAPIFunc('Xanthia','user','getSkinFromID', array('id' => $skinid));

	if (!file_exists('themes/'.$skin.'/style/style.php')) {
		// Return true since this theme does not have a dynamic style sheet
		return true;
	}

	// get the color scheme
	$colors = pnModAPIFunc('Xanthia','user','getSkinColors',
		array('skinid' => pnVarPrepForStore($skinid),
		'paletteid' => pnVarPrepForStore($paletteid)));

	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();	

	$table = $pntable['theme_config'];
	$column = $pntable['theme_config_column'];

	// Build the query
	$query = "SELECT $column[name],
					 $column[setting]
			  FROM $table
			  WHERE $column[skin_id]='".pnVarPrepForStore($skinid)."'";
	// Execute the query
	$result =& $dbconn->Execute($query);
	
	if ($dbconn->ErrorNo() != 0) {
		return false;
	} else {
		if (!$result->EOF) {
			// Iterate through the results, assign to config
			// modified for Oracle compatibility
			while(!$result->EOF){
				list($name, $setting) = $result->fields;
				// Move the pointer !
				$result->MoveNext();
				$$name = $setting;
			}
			// Close the result set
			$result->Close();
		}
	}

	include_once 'themes/'.pnVarPrepForOS($skin).'/style/style.php';

	// write the array to the file
	$return = true;
	if (!$file = fopen(pnConfigGetVar('temp') . '/Xanthia_Config/'. pnVarPrepForOS($skin) . '.style.css', 'w+')) {
		$return = false;
	}
	if (!fwrite($file, $stylesheet)) {
		$return = false;
	}
	fclose($file);

	if (file_exists('themes/'.pnVarPrepForOS($skin).'/scripts/menu.js.php')) {
		include_once('themes/'.pnVarPrepForOS($skin).'/scripts/menu.js.php');
		if (!$file2 = fopen(pnConfigGetVar('temp') . '/Xanthia_Config/'. pnVarPrepForOS($skin) . '.menu.js', 'w+')) {
			//$return = false;
		}
		if (!fwrite($file2, $javascript)) {
			//$return = false;
		}
		fclose($file2);
	}
	return $return;
}

function xanthia_adminapi_writethemetplcache($args)
{
    // extract all arguments passed to this function
	extract($args);

	if (!isset($skinid)) {
		return false;
	}

	if (!isset($modules)) {
		$modules = pnModGetAllMods();
	}else{
		if(!is_array($modules)){
			$modename = array();
			$modename['name'] = $modules;
			$modules = array();
			$modules[] = $modename;
		}
	}
	// Setup the DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();

	$blockcolumn = &$pntable['theme_blcontrol_column'];
	$themecolumn = &$pntable['theme_layout_column'];
	$zonecolumn = &$pntable['theme_zones_column'];
    // Load user API
	if (!pnModAPILoad('Xanthia','user')) {	    
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	// get the theme name
	$skin = pnModAPIFunc('Xanthia','user','getSkinFromID', array('id' => $skinid));

	$return = true;
	foreach ($modules as $module) {
		
		$sql = "SELECT $zonecolumn[label] as label ,
						 $zonecolumn[is_active] as active,
						 $zonecolumn[skin_type] as type
						FROM $pntable[theme_zones]
						WHERE $zonecolumn[is_active]='1'
						AND $zonecolumn[skin_id]='" . pnVarPrepForStore($skinid) ."'";

		$result =& $dbconn->Execute($sql);
		$themezones = array();
		while(!$result->EOF) {
			$row = $result->GetRowAssoc(false);
			$themezones[]  = $row;
			$result->MoveNext();
		}
		$result->close();
		
		unset($tplscode);
		if (!empty($themezones)) {
			$tplscode =  '<?php' . "\n" .  '$themezones = array (';
			$count = '0';
			foreach ($themezones as $themezone) {
				$tplscode .= "\n'$count' => array ( \n";
				$tplscode .= "'label' => '".$themezone['label']."', \n";
				$tplscode .= "'active' => '".$themezone['active']."', \n";
				$tplscode .= "'type' => '".$themezone['type']."'),\n";
				$temptpl = $themezone['label'];
				$count++;	
			}
            $tplscode = rtrim($tplscode,",\n");
			$tplscode .= "\n);\n\n";
		} 
		
		$sql = "SELECT $themecolumn[tpl_file] as template,
						$themecolumn[zone_label] as zone,
						$themecolumn[skin_id] as skin
						FROM $pntable[theme_layout]
						WHERE $themecolumn[skin_id] = '" . pnVarPrepForStore($skinid) ."'";
		
		$result =& $dbconn->Execute($sql);
		$themetpls = array();
		while(!$result->EOF) {
			$row = $result->GetRowAssoc(false);
			$themetpls[]  = $row;
			$result->MoveNext();
		}

		$result->close();
		if (empty($tplscode)) {
			$tplscode = "<?php";
		}
		if (!empty($themetpls)) {
			$tplscode .= "\n" .  '$zonetemplates = array (';
			foreach ($themetpls as $themetpl) {
				$tplscode .= "\n'$themetpl[zone]' => '$themetpl[template]',";
				$temptpl = $themetpl['zone'];
					
			}
            $tplscode = rtrim($tplscode,"',");
			$tplscode .= "');\n\n";
			
		}

			$sql = "SELECT $blockcolumn[blocktemplate] as blocktemplate,
							$blockcolumn[identi] as identi
							FROM $pntable[theme_blcontrol]
							WHERE $blockcolumn[theme]='".pnVarPrepForStore($skin)."'
							AND $blockcolumn[module]='".pnVarPrepForStore($module['name'])."'
							AND $blockcolumn[blocktemplate] !=''";

		$result =& $dbconn->Execute($sql);
		$blockttpls = array();
		while(!$result->EOF) {
			$row = $result->GetRowAssoc(false);
			$blockttpls[]  = $row;
			$result->MoveNext();
		}
		$result->close();
		
		if (empty($tplscode)) {
			$tplscode = "<?php";
		}
		
		if (!empty($blockttpls)) {
			$tplscode .=  "\n" .  '$blocktemplates = array (';
			$count = '0';
			foreach ($blockttpls as $blockttpl) {
					$btitle = pnBlockGetInfo($blockttpl['identi']);
					$blocktitle = strtolower(strip_tags($btitle['title']));
					$blocktitle = ereg_replace("[^0-9a-z_]","",$blocktitle);
				
				$tplscode .= "\n'$count' => array ( \n";
				$tplscode .= "'blocktemplate' => '".$blockttpl['blocktemplate']."', \n";
				$tplscode .= "'identi' => '$blocktitle'),\n";
				$temptpl = $blockttpl['identi'];
				$count++;	
			}
            $tplscode = rtrim($tplscode,",\n");
			$tplscode .= "\n);\n\n";
		}else{
			$tplscode .=  "\n" .  '$blocktemplates = " "; '."\n";
		} 
			
		if (empty($themezones) && empty($themetpls) && empty($blockttpls)){
			$tplscode = '<?php ?>';
		} else{
			$tplscode .= "?>";
		}		
		
		if (!$file = fopen(pnConfigGetVar('temp') . '/Xanthia_Config/'. $skin . '.' . $module['name'] . '.tplconfig.php', 'w+')) {
			$return = false;
		}
		if (!fwrite($file, $tplscode)) {
			$return = false;
		}
		fclose($file);
	}

	return $return;
}

?>