<?php
// $Id: pnuserapi.php 17741 2006-01-27 19:10:53Z markwest $
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
 * Initialze the Xanthia module
 * @access        private
 * @author        mh7
 * @since         0.911        02-04-2002
 * @param         string $module the module calling the DTS
 * @param         string $path the path to the templates
 * @return        object $DTS DuskyBlue Theme System object
 */
function xanthia_userapi_init()
{
	if (!defined('_XANTHIA_ROOT_PATH')) {
		$xanthiarootpath = pnModGetVar('Xanthia','rootpath');
		define('_XANTHIA_ROOT_PATH', $xanthiarootpath);
	}

    if (!class_exists('Xanthia')) {
        // Get Xanthia module info
        $xantinfo = pnModGetInfo(pnModGetIDFromName('Xanthia'));
        if (!empty($xantinfo)) {
            // Load the Xanthia Engine class
            include_once ''._XANTHIA_ROOT_PATH.'/'.pnVarPrepForOS($xantinfo['directory']).'/pnclasses/Xanthia.php';
        }
    }

    if (class_exists('Xanthia')) {
	    $engine =& new Xanthia;
	} else {
		return false;
	}

    // Return the Xanthia object
    return $engine;
}

/**
 * Initialze the DuskyBlue Theme System
 * @access        private
 * @author        mh7
 * @since        0.911        02-04-2002
 * @param        string $module the module calling the DTS
 * @param        string $path the path to the templates
 * @return        object $DTS DuskyBlue Theme System object
 */
function xanthia_userapi_dtsInit($args)
{
    // Extract our parameters
    extract($args);

    // Argument verification
    if (empty($module) || empty($path)) {
        // Arguement error
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
        return false;
    }

	// Get Xanthia module info
    $xantinfo = pnModGetInfo(pnModGetIDFromName('Xanthia'));
    
    // Loadup the DuskyBlue Theme System
    if (!class_exists('Smarty')) {
        pnUserGetLang();

        if (!empty($xantinfo)) {
            // Load the DTS (Smarty) Class
            include_once('includes/classes/Smarty/Smarty.class.php');
        } else {
            // No Xanthia module info
            pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        }
    }
    // Use the users Theme
    $theme = pnUserGetTheme();

    // Setup DTS Main Settings
    $DTS                       =& new Smarty;
    $DTS->compile_check        = pnModGetVar('Xanthia', 'compile_check');                    // check for updated templates?
    $DTS->force_compile        = pnModGetVar('Xanthia', 'force_compile');                    // force compile template always?
    $DTS->debugging            = false;                    // debugging on/off
    $DTS->left_delimiter       = '<!--[';                  // begin holder tag (be nice to others)
    $DTS->right_delimiter      = ']-->';                   // end holder tag
    $DTS->config_dir           = "$path";                  // default configs path (theme|module)
    $DTS->template_dir         = "$path/templates";        // default directory for templates
    $DTS->compile_dir          = pnConfigGetVar('temp') . '/Xanthia_compiled';// cache directory (compiled templates)
    $DTS->compile_id           = "$theme".pnUserGetLang().'';
    //$DTS->debugging = true;

	// don't use subdirectories when creating compiled/cached templates
	// this works better in a hosted environment
	$DTS->use_sub_dirs = false;

    /* This may be added in the future
    $DTS->register_resource("userdb", array("resource_userdb_source",
                                  "resource_userdb_timestamp",
                                  "resource_userdb_secure",
                                  "resource_userdb_trusted")); 

    $DTS->cache_handler_func = 'mysql_cache_handler';
    // This is till in development
    $lastdb_update = '';
    $DTS->cache_lifetime = time() - $lastdb_update;
    */

    // @todo        NOT FULLY IMPLEMENTED
    // number of seconds cached	content	will persist.
	// 0 = always regenerate cache,
	// -1 =	never expires.
    $DTS->cache_lifetime = pnModGetVar('Xanthia', 'cache_lifetime');
    // set caching to false initially since we only want to cache the 
	// master template
	$DTS->caching        = false;
	// HTML cache directory
    $DTS->cache_dir      = pnConfigGetVar('temp') . '/Xanthia_cache';

	//---- Plugins handling -----------------------------------------------
	
	// add the global PostNuke plugins directory
	$modinfo = pnModGetInfo(pnModGetIDFromName('pnRender'));
	$modpath = ($modinfo['type'] == 3) ? 'system' : 'modules';
	array_push($DTS->plugins_dir, "$modpath/$modinfo[directory]/plugins");

    // uncomment for .8 until modtype=3 for system modules is implemented
	array_push($DTS->plugins_dir, "system/$modinfo[directory]/plugins");

	// add the global PostNuke plugins directory
	$modinfo = pnModGetInfo(pnModGetIDFromName('Xanthia'));
	$modpath = ($modinfo['type'] == 3) ? 'system' : 'modules';
	array_push($DTS->plugins_dir, "$modpath/$modinfo[directory]/plugins");

    // uncomment for .8 until modtype=3 for system modules is implemented
	array_push($DTS->plugins_dir, "system/$modinfo[directory]/plugins");

	// add theme specific plugins directories, if they exist
	$cWhereIsPerso = WHERE_IS_PERSO;
	if ( (!(empty($cWhereIsPerso))) && is_dir(WHERE_IS_PERSO."themes/$theme/plugins") ) {
		$themepath = WHERE_IS_PERSO."themes/$theme/plugins";
        $usemod_conf = WHERE_IS_PERSO."themes/$theme/config/usemodules";
	} else {
		$themepath = "themes/$theme/plugins";
        $usemod_conf = "themes/$theme/config/usemodules";
	}

	if (file_exists($themepath)) {
		array_push($DTS->plugins_dir, $themepath);
	}

    // load the config file for the usemodules trick
    if( file_exists($usemod_conf) && is_readable($usemod_conf)) {
        $additionalmodules = file($usemod_conf);
        if(is_array($additionalmodules)) {
            foreach($additionalmodules as $addmod) {
                $addmodinfo = pnModGetInfo(pnModGetIDFromName(trim($addmod)));
                $addmodpath = ($addmodinfo['type'] == 3) ? 'system' : 'modules';
                $addmod_plugs = "$addmodpath/$addmodinfo[directory]/pntemplates/plugins";
                if (file_exists($addmod_plugs)) {
                    array_push($DTS->plugins_dir, $addmod_plugs);
                }
            }
        }
    }

	// register the function to handle non-cacheable blocks
	$DTS->register_block('nocache', 'pnRender_block_nocache', false);

    // Return the DTS object
    return $DTS;
}

/**
 * Initialize Xanthia for Themes
 * @access        public
 * @author        mh7
 * @since         0.911        02-04-2002
 * @param         string $skin the skin name we are to use
 * @return        object $DTS the DuskyBlue Theme System object
 * @see           xanthia_userapi_dtsInit()
 */
function xanthia_userapi_themeInit($args)
{
    // Extract our parameters
    extract($args);

    // Verification
    if (empty($skin)) {
        // Determine which Theme to use
        $skin = pnModAPIFunc('Xanthia','user','getSkinName');
    }

	// Setup the Theme template path
	$cWhereIsPerso = WHERE_IS_PERSO;
	if ( (!(empty($cWhereIsPerso))) && is_dir(WHERE_IS_PERSO."themes/$skin") ) {
		$tplpath = WHERE_IS_PERSO."themes/$skin";
	} else {
		$tplpath = "themes/$skin";
	}

    // Return the DTS object with proper paths
    return pnModAPIFunc('Xanthia', 'user', 'dtsInit',
                            array('module'    => $skin,
                                'path'        => $tplpath));
}

/**
 * Get the active skin ID from the database
 * @access        private
 * @author        mh7
 * @since         0.911        02-04-2002
 * @param         string $skin the skin name
 * @return        integer $result->fields[0] the skin ID
 */
function xanthia_userapi_getSkinID($args)
{
	static $skinids;

    // Extract our parameters
    extract($args);

    // Argument verification
    if (empty($skin)) {
        $skin = pnModAPIFunc('Xanthia','user','getSkinName');
    }

	if (!isset($skinids[$skin])) {

		// Grab the Skin's ID from the db
		$dbconn =& pnDBGetConn(true);
		$pntable =& pnDBGetTables();

		// Define the table & column
		$table = &$pntable['theme_skins'];
		$column = &$pntable['theme_skins_column'];
	
		// the query
		$query = "SELECT $column[skin_id] FROM $table WHERE $column[name]='" . pnVarPrepForStore($skin) . "' AND $column[is_active]='1'";
		// Execute the query
		$result = $dbconn->SelectLimit($query,1);
	
		// Check for an error with the database code, and if so set an
		// appropriate error message and return
		if ($dbconn->ErrorNo() != 0) {
			return false;
		}
	
		// If no matches found, return false to API
		if ($result->EOF) {
			return false;
		}

		// close the result set
	    $result->Close();
		
		$skinids[$skin] = $result->fields[0];
	}

    // Return the result
    return $skinids[$skin];
}

/**
 * Determine which skin to use
 * @access        private
 * @author        mh7
 * @since        0.911        02-04-2002
 * @param        none
 * @return        string $skin name of the active skin
 */
function xanthia_userapi_getSkinName($args)
{
    // the pnUserGetTheme API handles the not logged in user
	// this code broke the &theme=<themename> for not logged
	// in users. - markwest
    // Determine which Theme to use
    // Determine which Theme to use
    
    // Extract our parameters
    extract($args);

	// get the theme name    
	$skin = pnUserGetTheme();

	// override the theme per category or story
	// precedence is story over category override
	// .7x category overrides
	global $themeOverrideCategory, $themeOverrideStory;
	if (($themeOverrideCategory != '') && (file_exists("themes/$themeOverrideCategory"))) {
		$skin = $themeOverrideCategory;
	}
	if (($themeOverrideStory != '') && (file_exists("themes/$themeOverrideStory"))) {
		$skin = $themeOverrideStory;
	}

    // If no matches found, return false to API
    if (empty($skin)) {
        return false;
    }

    // Return the results
    return $skin;
}

/**
 * Fetch BG and Text Colors from the dB for the skin [legacy support]
 * @access        private
 * @author        mh7
 * @since         0.911        02-04-2002
 * @param         integer $skinid the ID of the skin to fetch colors for
 * @param         integer $paletteid the ID of the palette to fetch colors for
 * @return        array $colors the list of active colors for the skin
 */
function xanthia_userapi_getSkinColors($args) {

    // Extract our parameters
    extract($args);
    	
    if (!isset($paletteid)) {
    	pnSessionSetVar('errormsg', pnVarPrepForDisplay(_XA_ARGSERROR));
		return false;
    }
    
	static $skincolors;
    // Verify the skins ID, assign default if null
    if (empty($skinid)) {
    	$skinid = pnModAPIFunc('Xanthia','user','getSkinID');
    }
	// check if we've already got the colors from the db
	// this screws up the stylesheet when changing palettes
	//if (is_array($skincolors[$skinid])) {
	//	return $skincolors[$skinid];
	//}

	// get the theme name
	$skin = pnModAPIFunc('Xanthia','user','getSkinName',
								array('id' => $skinid));
								
	if (file_exists(pnConfigGetVar('temp') . '/Xanthia_Config/'. pnVarPrepForOS($skin) . '.palettes.php')) {
		include pnConfigGetVar('temp') . '/Xanthia_Config/'. pnVarPrepForOS($skin) . '.palettes.php';
	} else {
		// Setup the database object
		$dbconn =& pnDBGetConn(true);
		$pntable =& pnDBGetTables();
	
		// Setup the databse column pointers
		$table = $pntable['theme_palette'];
		$column = &$pntable['theme_palette_column'];
		// Build the DB query
		$query = "SELECT $column[background],
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
						 $column[hover],
						 $column[palette_name]
					FROM $table
					WHERE $column[skin_id] = '" . (int)pnVarPrepForStore($skinid) . "' 
					AND $column[palette_id] = '" . (int)pnVarPrepForStore($paletteid) . "'";
		// Execute the query
		$result =& $dbconn->Execute($query);

		// Check for an error with the database code, and if so set an
		// appropriate error message and return
		if ($dbconn->ErrorNo() != 0) {
			return false;
		}
		// If no match found report false
		if ($result->EOF) { 
			//echo 'No Colors';
			return false;
		}
		// Orgnaize and build our data array
		//modified for Oracle compatibility
		/*        while (list($bcolor1, $bcolor2, $bcolor3, $bcolor4, $bcolor5,
					$bcolor6, $scolor, $tcolor1, $tcolor2) = $result->fields){ */
		while(!$result->EOF){
			list($background,
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
				 $hover,
				 $palette_name) = $result->fields;
	 
			// Move that ADOdb pointer !
			$result->MoveNext();
			// Build the array
			$colors = array('palette_name'  => str_replace(' ', '', $palette_name),
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
	}

	// remember colors for future function calls	
	$skincolors[$skinid] = $colors;
	// Return the results
	return $colors;
}

/**
 * @access        private
 * @author        mh7
 * @since         0.911        02-04-2002
 */
function xanthia_userapi_doBlocks($side)
{
    global $blocks_modules, $blocks_side, $allblocks;
	static $includi, $nonmostra, $moduleblockzones;

    $pippo=preg_replace('/^NS-/', '', pnModGetName());
    $temptheme = pnUserGetTheme();
    $currentlang = pnUserGetLang();

	// work out the current zone
	if (is_numeric($side)) {
		$side = pnVarPrepForStore($side);
	} elseif(isset($side[0])) {
		$side = pnVarPrepForStore(strtolower($side[0]));
	}

// Check: Added to check for theme config files    
    if (file_exists(pnConfigGetVar('temp') . '/Xanthia_Config/'. pnVarPrepForOS($temptheme) . '.' . pnVarPrepForOS($pippo) . '.config.php')) {
        include_once(pnConfigGetVar('temp') . '/Xanthia_Config/'. pnVarPrepForOS($temptheme) . '.' . pnVarPrepForOS($pippo) . '.config.php');
    }
	if  (!empty($moduleblockzones)) {
		if (isset($moduleblockzones[$side])) {
	        $chunk=split('[:]', $moduleblockzones[$side]);
		} else {
			$chunk = array();
		}
        $chunkcount=count($chunk);
        
        for($i=0; $i<$chunkcount; $i++) {
            $row1 = pnBlockGetInfo($chunk[$i]);
            if (!empty($row1)) {
				// update block postion with xanthia position
				$row1['position'] = $side;
                $row1['bid'] = $chunk[$i];
                if (($row1['active'] == 1) && (($row1['language'] == $currentlang) || ($row1['language'] == ''))) {
                    $modinfo = pnModGetInfo($row1['mid']);
                    if (!$modinfo) {
                        $modinfo['name'] = 'Core';
                    }
                    pnBlockShow($modinfo['name'], $row1['bkey'], $row1);
                }
            }
            $modinfo = '';
        }
    } else {
//End of Check
		pnModDBInfoLoad('Blocks');
		$dbconn =& pnDBGetConn(true);
		$pntable =& pnDBGetTables();
		//$currentlang = pnUserGetLang();
	
		if (pnConfigGetVar('multilingual') == 1) {
			$column = &$pntable['blocks_column'];
			$querylang = "AND ($column[blanguage] = '" . pnVarPrepForStore($currentlang) . "' 
						  OR $column[blanguage] = '' 
						  OR $column[blanguage] IS NULL)";
		} else {
			$querylang = '';
		}
		//$temptheme = pnUserGetTheme();
		if (!isset($includi) || !isset($nonmostra)) {
			//$pippo=preg_replace('/^NS-/', '', pnModGetName());
			$column = &$pntable['theme_blcontrol_column'];
			$result =& $dbconn->Execute("SELECT $column[module] as module, 
											   $column[block] as block FROM 
											   $pntable[theme_blcontrol] WHERE 
											   $column[module]='".pnVarPrepForStore($pippo)."'
											   AND $column[theme]='".pnVarPrepForStore($temptheme)."'");
			$nonmostra ='';
			while(!$result->EOF) {
				$row = $result->GetRowAssoc(false);
				$nonmostra .= ':'.$row['block'];
				$result->MoveNext();
			}
			$nonmostra .= ':';

			$pippo='-1';
			//$diplayzone="alwa

			if (preg_match("/:$pippo:/", $nonmostra)){
				$includi = 'true';
			} else {
				$includi = 'false';
			}
		}

		if ($includi == 'false') {
			if (!isset($allblocks)) {
				$block_side = $side;
				$column = &$pntable['blocks_column'];
				$result =& $dbconn->Execute("SELECT $column[bid] as bid, 
											$column[bkey] as bkey, 
											$column[mid] as mid, 
											$column[title] as title, 
											$column[content] as content, 
											$column[url] as url, 
											$column[position] as position, 
											$column[weight] as weight, 
											$column[active] as active, 
                        				    $column[collapsable] as collapsable, 
                        				    $column[defaultstate] as defaultstate, 
											$column[refresh] as refresh, 
											$column[last_update] AS unix_update, 
											$column[blanguage] as blanguage FROM 
											$pntable[blocks] WHERE $column[active]=1 $querylang ORDER BY $column[weight]");
				while(!$result->EOF) {
					$row = $result->GetRowAssoc(false);
					$row['unix_update']=$result->UnixTimeStamp($row['unix_update']);
					$allblocks[] = $row;
					$result->MoveNext();
				}
			}
			foreach ($allblocks as $row) {		
				$modinfo = pnModGetInfo($row['mid']);
				if (!$modinfo) {
					$modinfo['name'] = 'Core';
				}

				$pippo=preg_quote(pnVarPrepForStore($row['title']));
				if ($includi == 'true') {
					if (preg_match("/:$pippo:/i", $nonmostra))  {
						echo pnBlockShow($modinfo['name'], $row['bkey'], $row);
					}
				}

				if ($includi == 'false') {
					$pippo = strip_tags($pippo);
					if (isset($side[0]) && !preg_match("/:$pippo:/i", $nonmostra) && $row['position'] == $side[0])  {
						echo pnBlockShow($modinfo['name'], $row['bkey'], $row);
					}
				}
			}
		} else {
			//---Block Control ------------------------------------------------------------------------------------------------------------
			$column = &$pntable['theme_blcontrol_column'];
			$pippo=preg_replace('/^NS-/', '', pnModGetName());
			$block_side = $side;

			$pippo=pnVarPrepForStore($pippo);

			if (!isset($allblocks)) {
				$sql="SELECT $column[module] as module, 
							$column[block] as block, 
							$column[position] as position, 
							$column[weight] as weight, 
							$column[blocktemplate] as blocktpl,
							$column[position] as side 
							FROM $pntable[theme_blcontrol] 
							WHERE $column[module]= '$pippo'
							AND  $column[theme]= '" . pnVarPrepForStore($temptheme) . "' ORDER BY $column[weight]";
				$result =& $dbconn->Execute($sql);

				$allblocks = array();
				while(!$result->EOF) {
					$row = $result->GetRowAssoc(false);
					$allblocks[]  = $row;
					$result->MoveNext();
				}
				$result->close();
			}		
			foreach ($allblocks as $row) {
				if ($row['block'] != '-1' && $row['side'] == $side) {
					$row1 = pnBlockGetInfo($row['block']);
					// update block postion with xanthia position
					$row1['position'] = $side;
					if (!empty($row1)) {
						$row1['bid'] = $row['block'];
						if (($row1['active'] == 1) && (($row1['language'] == $currentlang) || ($row1['language'] == ''))) {
							$modinfo = pnModGetInfo($row1['mid']);
							if (!$modinfo) {
								$modinfo['name'] = 'Core';
							}
							$row1['blocktpl']= $row['blocktpl'];
							pnBlockShow($modinfo['name'], $row1['bkey'], $row1);
						}
					}
				}
			}
			//---------------------------------------------------------------------------------------------------------------
		}
	}
}

/**
 * Get a list of install skins
 * @access                private
 * @author                mh7, ApathyBoy
 * @since                0.926                29-08-2002
 * @param                none
 * @return                array $array list of skins from themes dir
 */
function xanthia_userapi_getAllThemes()
{
	// get all themes from the main themes directory
    $handle = opendir('themes');
    while ($f = readdir($handle)) {
		if ($f != "." && $f != ".." && $f != "CVS" && $f != "index.html" && !ereg("[.]",$f)) {
			$newHandle = opendir("themes/$f/");
			while ($a = readdir($newHandle)) {
				if ($a == 'xaninit.php'){
					$themelist[] = $f;
				} else {
					continue;
				}
			}
        }
    }
	closedir($handle);
	// get all themes from a multisite setup
	$cWhereIsPerso = WHERE_IS_PERSO;
	if (!(empty($cWhereIsPerso))) {
    if (is_dir(WHERE_IS_PERSO.'themes')) {
		$handle = opendir(WHERE_IS_PERSO.'themes');
		while ($f = readdir($handle)) {
			if ($f != "." && $f != ".." && $f != "CVS" && $f != "index.html" && !ereg("[.]",$f)) {
				$newHandle = opendir(WHERE_IS_PERSO."themes/$f/");
				while ($a = readdir($newHandle)) {
					if ($a == 'xaninit.php'){
						$themelist[] = $f;
					} else {
						continue;
					}
				}
			}
		}
    }
	}
	
    if(isset($themelist)){
		return $themelist;
    }
}

/**
 * Get a list of install skins
 * @access                private
 * @author                mh7
 * @since                0.926                29-08-2002
 * @param                none
 * @return                array $array list of installed skins info
 */
function xanthia_userapi_getAllSkins()
{
	// Setup DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	// Setup the table and column info
	$table = $pntable['theme_skins'];
	$column = $pntable['theme_skins_column'];
	// build the query
	$query = "SELECT $column[skin_id],
					$column[name]
					FROM $table
					WHERE $column[skin_id]>0";
	
	$result =& $dbconn->Execute($query);
	// If no match found return false
	if ($result->EOF) {
		return false;
	}
	
	// Organize and build our data array
	while (list($id, $name) = $result->fields) {
		// Move that ADOdb pointer !
		$result->MoveNext();
		// Build the return array
		$array[] = array('id'        => $id,
						'name'        => $name);
	}
	// Always close the result set
	$result->Close();
	// Return the results
	return $array;
}

/*
 * Fetch the skin name from a supplied skin ID
 * @access                private
 * @author                mh7
 * @since                 0.911                02-04-2002
 * @param                 integer $is the supplied skin ID
 * @return                string $name the corresponding skin name
 */
function xanthia_userapi_getSkinFromID($args)
{
	// Extract our parameters
	extract($args);
	// Parameter check
	if (!isset($id)) {
		pnSessionSetVar('errormsg', pnVarPrepForDisplay(_XA_ARGSERROR));
		return false;
	}
	// Setup the DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();

	// Setup the table and column info
	$table = $pntable['theme_skins'];
	$column = &$pntable['theme_skins_column'];
   
    // Buld the query
	//modified for Oracle compatibility
	/* $query = "SELECT $column[name]
				FROM $table
				WHERE $column[skin_id]='$id'
				LIMIT 1";*/
	$query = "SELECT $column[name] FROM $table WHERE $column[skin_id] = '" . (int)pnVarPrepForStore($id) . "'";
	$result = $dbconn->SelectLimit($query,1);

	// Execute the query
	$result =& $dbconn->Execute($query);
	// If no matches found return false
	if ($result->EOF) {
		return false;
	}

	// Close the result set
	$result->Close();
	// Return the result
	return $result->fields[0];
}

/**
 * clear the xanthia cache
 *
 * @author Mark West
 */
function xanthia_userapi_clearcache()
{
	// get the engine object
	$engine = pnModAPIFunc('Xanthia','user','init');

	// clear the cache
	$engine->DTS->clear_all_cache(pnModGetVar('Xanthia', 'cache_lifetime'));

}

?>