<?php
// File: $Id: pnBlocks.php 16829 2005-09-28 12:37:08Z markwest $
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
// Original Author of file:  Patrick Kellum <webmaster@quahog-library.com>
// Purpose of file: Display the side blocks on the page
// ----------------------------------------------------------------------
// Advanced Blocks System
//
// Copyright (c) 2001 Patrick Kellum (webmaster@quahog-library.com)
// http://ctarl-ctarl.com/
//
// Based in part of the blocks system in PHP-Nuke
// Copyright (c) 2001 by Francisco Burzi (fbc@mandrakesoft.com)
// http://phpnuke.org/
// ----------------------------------------------------------------------
/**
 * @package PostNuke_Core
 * @subpackage PostNuke_pnAPI
 */

/**
 * change the function name so themes remain compatable
 * @param $side block position to render
 */
function blocks($side=null)
{
	if(empty($side))
		return null;
	
	static $blocks = array();

	if (empty($blocks)) {
		pnModDBInfoLoad('Blocks');
		$dbconn =& pnDBGetConn(true);
		$pntable =& pnDBGetTables();
		$currentlang = pnUserGetLang();
		$column = &$pntable['blocks_column'];

		if (pnConfigGetVar('multilingual') == 1) {
			$querylang = "AND ($column[blanguage]='$currentlang' OR $column[blanguage]='')";
		} else {
			$querylang = '';
		}
		$side = strtolower($side[0]);
		$sql = "SELECT $column[bid] as bid,
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
					   $column[blanguage] as blanguage,
					   $column[position] as postion
				FROM $pntable[blocks]
				WHERE $column[active] = 1
				$querylang
				ORDER BY $column[weight]";

		$dbconn->SetFetchMode(ADODB_FETCH_NUM);
		$result =& $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0) {
			return;
		}

		while (!$result->EOF) {
			$row = $result->GetRowAssoc(false);
			$row['unix_update'] = $result->UnixTimeStamp($row['unix_update']);
			$blocks[] = $row;
			$result->MoveNext();
		}
	}
	foreach ($blocks as $block) {
		if ($block['postion'] == $side[0]) {
			$modinfo = pnModGetInfo($block['mid']);
			if (!$modinfo) {
				// Assume core
				$modinfo['name'] = 'Core';
			}
			echo pnBlockShow($modinfo['name'], $block['bkey'], $block);
		}
	}
}

/**
 * show a block
 *
 * @param string $modname module name
 * @param string $block name of the block
 * @param array $blockinfo information parameters
 * @return mixed blockinfo array or null
 */
function pnBlockShow($modname=null, $block=null, $blockinfo = array())
{
	if (empty($modname) || empty($block)) {
		return null;
	}
	
    global $blocks_modules;

    pnBlockLoad($modname, $block);

    $displayfunc = "{$modname}_{$block}block_display";

    if (function_exists($displayfunc)) {
        // New-style blocks
        return $displayfunc($blockinfo);
    } else {
		$modid = pnModGetIDFromName($modname);
		if (isset($blocks_modules[0][$block]['func_display'])) {
            return $blocks_modules[0][$block]['func_display']($blockinfo);
		// support old style blocks within modules 
        } else if (isset($blocks_modules[$modid][$block]['func_display'])) {
            return $blocks_modules[$modid][$block]['func_display']($blockinfo);
            // Old-style blocks
        } else {
            if(pnSecAuthAction(0, '.*', '.*', ACCESS_ADMIN)) {
                $blockinfo['title'] = pnVarPrepForDisplay(_UNKNOWNBLOCKTYPE) . " $block!";
                $blockinfo['content'] = pnVarPrepForDisplay(_UNKNOWNBLOCKHINT);
                return themesideblock($blockinfo);
            }
        }
    }
}

/**
 * Display a block based on the current theme
 *
 */
function themesideblock($row)
{
    if (!isset($row['bid'])) {
		$row['bid'] = '';
    }
    if (!isset($row['title'])) {
		$row['title'] = '';
    }
    // check for collapsable menus being enabled, and setup the collapsable menu image.
   if (file_exists('themes/'.pnVarPrepForOS(pnUserGetTheme()).'/images/upb.gif')) {
       $upb = '<img src="themes/'.pnVarPrepForOS(pnUserGetTheme()).'/images/upb.gif" alt="" />';
   } else {
       $upb = '<img src="images/global/upb.gif" alt="" />';
   }
   if (file_exists('themes/'.pnVarPrepForOS(pnUserGetTheme()).'/images/downb.gif')) {
       $downb = '<img src="themes/'.pnVarPrepForOS(pnUserGetTheme()).'/images/downb.gif" alt="" />';
   } else {
       $downb = '<img src="images/global/downb.gif" alt="" />';
   }
   if (pnUserLoggedIn() && pnModGetVar('Blocks', 'collapseable') == 1 && isset($row['collapsable']) && ($row['collapsable'] == '1')) {
   		if(pnCheckUserBlock($row) == '1') {
            if (!empty($row['title'])) {
                $row['minbox'] = '<a href="' . pnVarPrepForDisplay(pnModURL ('Blocks', 'user', 'changestatus', array ('bid' => $row['bid'], 'authid' => pnSecGenAuthKey()))) . '">' . $upb . '</a>';
            }
        } else {
            $row['content'] ='';
            if (!empty($row['title'])) {
                $row['minbox'] = '<a href="' . pnVarPrepForDisplay(pnModURL ('Blocks', 'user', 'changestatus', array ('bid' => $row['bid'], 'authid' => pnSecGenAuthKey()))) . '">' . $downb . '</a>';
            }
        }
    } else {
    	$row['minbox'] = '';
    }
    // end collapseable menu config

    return themesidebox($row);
}

/**
 * load a block
 *
 * @param string $modname module name
 * @param string $block name of the block
 * @return bool true on successful load, false otherwise
 */
function pnBlockLoad($modname, $block)
{
    static $loaded = array();

    if ((empty($modname)) || ($modname == 'Core') || ($modname == 'Blocks')) {
        $modname = 'Core';
        $moddir = 'includes/blocks';
        $langdir = 'includes/language/blocks';
    } else {
        $modinfo = pnModGetInfo(pnModGetIdFromName($modname));
        $moddir = 'modules/' . pnVarPrepForOS($modinfo['directory']) . '/pnblocks';
        $langdir = 'modules/' . pnVarPrepForOS($modinfo['directory']) . '/pnlang';
    }

    if (isset($loaded["$modname/$block"])) {
        return true;
    }

    // Load the block
    $incfile = $block . '.php';
    $filepath = $moddir . '/' . pnVarPrepForOS($incfile);
    if (!file_exists($filepath)) {
        return false;
    }
    include_once $filepath;
    $loaded["$modname/$block"] = 1;

    // Load the block language files
    $currentlangfile = $langdir . '/' . pnVarPrepForOS(pnUserGetLang()) . '/' . pnVarPrepForOS($incfile);
    $defaultlangfile = $langdir . '/' . pnVarPrepForOS(pnConfigGetVar('language')) . '/' . pnVarPrepForOS($incfile);
    if (file_exists($currentlangfile)) {
        include $currentlangfile;
    } elseif (file_exists($defaultlangfile)) {
        include $defaultlangfile;
    }

	// get the block info
	$infofunc = "{$modname}_{$block}block_info";
    if (function_exists($infofunc)) {
		$blocks_modules[$block] = $infofunc();
    }

	// set the module and keys for the new block
	$blocks_modules[$block]['bkey'] = $block;
	if (!isset($blocks_modules[$block]['module'])) {
		$blocks_modules[$block]['module'] = $modname;
	}
	$blocks_modules[$block]['mid'] = pnModGetIDFromName($blocks_modules[$block]['module']);

	// merge the blockinfo in the global list of blocks
	if (!isset($GLOBALS['blocks_modules'])) {
		$GLOBALS['blocks_modules'] = array();
	}
	$GLOBALS['blocks_modules'][$blocks_modules[$block]['mid']][$block] = $blocks_modules[$block];

    // Initialise block if required (new-style)
    $initfunc = "{$modname}_{$block}block_init";
    if (function_exists($initfunc)) {
        $initfunc();
    }

    return true;
}

/**
 * load all blocks
 * @return array array of blocks
 */
function pnBlockLoadAll()
{
    // Load core and old-style blocks
    $dib = opendir('includes/blocks/');
    while($f = readdir($dib)) {
    	// itevo
        if (substr($f, -4) == ".php") {
    		// itevo
    		$block = substr($f,0,-4);
            pnBlockLoad('Core', $block);
        }
    }
    closedir($dib);

    // Load new-style blocks from system and modules tree
	$mods = pnModGetAllMods();

	//while (list($name, $directory, $mid) = $result->fields) {
	foreach ($mods as $mod) {
		$name = $mod['name'];
		$directory = $mod['directory'];

        $blockdir = 'modules/' . pnVarPrepForOS($directory) . '/pnblocks';
        if (!@is_dir($blockdir)) {
            continue;
        }

        $dib = opendir($blockdir);
		while ($f = readdir($dib)) {
    		// itevo
            if (substr($f, -4) == ".php") {
        		// itevo
        		$block = substr($f,0,-4);
                pnBlockLoad($name, $block);
			}
		}
    }
    // Return information gathered
    return $GLOBALS['blocks_modules'];
}

/**
 * extract an array of config variables out of the content field of a
 * block
 *
 * @param the $ content from the db
 */
function pnBlockVarsFromContent($content)
{
	// itevo
    if (substr($content, -2) == ';}' OR substr($content, -1) == ';') {
        // Serialised content
        return (unserialize($content));
    }
    // Unserialised content
    $links = explode("\n", $content);
    $vars = array();
    foreach ($links as $link) {
        $link = trim($link);
        if ($link) {
            $var = explode(':=', $link);
            if (isset($var[1])) {
                $vars[$var[0]] = $var[1];
            }
        }
    }
    return($vars);
}

/**
 * put an array of config variables in the content field of a block
 *
 * @param the $ config vars array, in key->value form
 */
function pnBlockVarsToContent($vars)
{
    return (serialize($vars));
}

/**
 * Checks if user controlled block state
 *
 * Checks if the user has a state set for a current block
 * Sets the default state for that block if not present
 *
 * @access private
 */
function pnCheckUserBlock($row)
{
    if (!isset($row['bid'])) {
        $row['bid'] = '';
    }
    if (pnUserLoggedIn()) {
        $uid = pnUserGetVar('uid');
		$dbconn =& pnDBGetConn(true);
		$pntable =& pnDBGetTables();
        $column = &$pntable['userblocks_column'];
        $sql="SELECT $column[active]
		      FROM $pntable[userblocks]
		      WHERE $column[bid] = '".pnVarPrepForStore($row['bid'])."'
			  AND $column[uid] = '".pnVarPrepForStore($uid)."'";
        $result =& $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0) {
			pnSessionSetVar('errormsg', 'Error: ' . $dbconn->ErrorNo() . ': ' . $dbconn->ErrorMsg());
			return true;
		}
        if ($result->EOF) {
            $uid = pnVarPrepForStore($uid);
            $row['bid'] = pnVarPrepForStore($row['bid']);
            $sql = "INSERT INTO $pntable[userblocks]
			        		   ($column[uid],
					 			$column[bid],
					 			$column[active])
					VALUES (" . pnVarPrepForStore($uid) . ",
					        '$row[bid]',
							". pnVarPrepForStore($row['defaultstate']) .")";
            $result =& $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0) {
				pnSessionSetVar('errormsg', 'Error: ' . $dbconn->ErrorNo() . ': ' . $dbconn->ErrorMsg());
				return true;
			}
            return true;
        } else {
            list($active) = $result->fields;
            return $active;
        }
    } else {
        return false;
    }
}

/**
 * get block information
 * @param bid the block id
 * @return array array of block information
 */
function pnBlockGetInfo($bid)
{
	static $blocks;

	// load the db info if required
	pnModDBInfoLoad('Blocks');

	if (empty($blocks[$bid])) {
		$dbconn =& pnDBGetConn(true);
		$pntable =& pnDBGetTables();

		$blockstable = $pntable['blocks'];
		$blockscolumn = &$pntable['blocks_column'];
		$sql = "SELECT $blockscolumn[bid],
					   $blockscolumn[bkey],
					   $blockscolumn[title],
					   $blockscolumn[content],
					   $blockscolumn[url],
					   $blockscolumn[position],
					   $blockscolumn[weight],
					   $blockscolumn[active],
                       $blockscolumn[collapsable],
                       $blockscolumn[defaultstate],
					   $blockscolumn[refresh],
					   $blockscolumn[last_update],
					   $blockscolumn[blanguage],
					   $blockscolumn[mid]
				FROM $blockstable";
//				WHERE $blockscolumn[bid] = '" . (int)pnVarPrepForStore($bid) . "'";
		$result =& $dbconn->Execute($sql);

		if ($dbconn->ErrorNo() != 0) {
			return;
		}
		while(!$result->EOF){
			list($resbid,
				 $resarray['bkey'],
				 $resarray['title'],
				 $resarray['content'],
				 $resarray['url'],
				 $resarray['position'],
				 $resarray['weight'],
				 $resarray['active'],
		         $resarray['collapsable'],
		         $resarray['defaultstate'],
				 $resarray['refresh'],
				 $resarray['last_update'],
				 $resarray['language'],
				 $resarray['mid']) = $result->fields;
			// Move that ADOdb pointer !
			$result->MoveNext();
			$blocks[$resbid] = $resarray;
		}
		$result->Close();
	}

	if (isset($blocks[$bid])) {
		return $blocks[$bid];
	} else {
		return;
	}
}

/**
 * get block information
 * @param title the block title
 * @return array array of block information
 */
function pnBlockGetInfoByTitle($title=null)
{
	if (empty($title))
		return;
	
	static $blocks;

	// load the db info if required
	pnModDBInfoLoad('Blocks');

	if (empty($blocks[$title])) {

		$dbconn =& pnDBGetConn(true);
		$pntable =& pnDBGetTables();

		$blockstable = $pntable['blocks'];
		$blockscolumn = &$pntable['blocks_column'];
		$sql = "SELECT $blockscolumn[title],
					   $blockscolumn[bkey],
					   $blockscolumn[title],
					   $blockscolumn[content],
					   $blockscolumn[url],
					   $blockscolumn[position],
					   $blockscolumn[weight],
					   $blockscolumn[active],
 	                   $blockscolumn[collapsable],
 	                   $blockscolumn[defaultstate],
					   $blockscolumn[refresh],
					   $blockscolumn[last_update],
					   $blockscolumn[blanguage],
					   $blockscolumn[mid],
					   $blockscolumn[bid]
				FROM $blockstable";
//				WHERE $blockscolumn[title] = '" .pnVarPrepForStore($title)."' ";
		$result =& $dbconn->Execute($sql);

		if ($dbconn->ErrorNo() != 0) {
			return;
		}
		while(!$result->EOF){
			list($restitle,
				 $resarray['bkey'],
				 $resarray['title'],
				 $resarray['content'],
				 $resarray['url'],
				 $resarray['position'],
				 $resarray['weight'],
				 $resarray['active'],
				 $resarray['collapsable'],
				 $resarray['defaultstate'],
				 $resarray['refresh'],
				 $resarray['last_update'],
				 $resarray['language'],
				 $resarray['mid'],
				 $resarray['bid']) = $result->fields;
				 $resarray['unix_update']=$result->UnixTimeStamp($resarray['last_update']);
			// Move that ADOdb pointer !
			$result->MoveNext();
			$blocks[$restitle] = $resarray;
		}
		$result->Close();
	}

	if (isset($blocks[$title])) {
	    return $blocks[$title];
	} else {
		return;
	}
}

?>