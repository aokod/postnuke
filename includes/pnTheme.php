<?php
// $Id: pnTheme.php 20413 2006-11-06 13:19:45Z larsneo $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
// http://www.postnuke.com/
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
// Original Author of file: Jim McDonald
// Purpose of file: Theme system API
// ----------------------------------------------------------------------
/**
 * @package PostNuke_Core
 * @subpackage PostNuke_pnAPI
*/

/**
 * Load a theme
 * <br />
 * include theme.php for the requested theme
 * 
 * @return bool true if successful, false otherwiese
 */
function pnThemeLoad($thistheme)
{
	$thistheme = isset($thistheme) ? $thistheme : '';
	if (!pnVarValidate($thistheme, 'theme')) {
		return false;
	}

    static $loaded = 0;

    if ($loaded) {
        return true;
    } 

    // Lots of nasty globals for back-compatability with older themes
    global $bgcolor1;
    global $bgcolor2;
    global $bgcolor3;
    global $bgcolor4;
    global $bgcolor5;
    global $sepcolor;
    global $textcolor1;
    global $textcolor2;
    global $postnuke_theme;
    global $thename; 
        
    $thistheme = pnVarPrepForOS($thistheme);
    if (file_exists($file=WHERE_IS_PERSO . "themes/$thistheme/theme.php")) {
        include_once $file;
    } elseif (file_exists($file="themes/$thistheme/theme.php")) {
        include_once $file;
    } else {
	    return false;
	}
	
	// now lets load the themes language file
	pnThemeLangLoad();

    // end of modification
    $loaded = 1;
    return true;
} 

/**
 * return a theme variable
 * 
 * @return mixed theme variable value
 */
function pnThemeGetVar($name)
{
	if (!isset($name)) {
		return null;
	}
	
    global $$name;
    if (isset($$name)) {
        return $$name;
    } 
} 

/**
 * pnThemeGetAllThemes()
 * 
 * list all available themes
 *
 * @param boolean $removeXanthia set to true to remove inactive Xanthia themes from the list
 * @param boolean $hideHiddenThemes set to true to remove hidden (special purpose) themes from the list
 * @return array of available themes
 **/
function pnThemeGetAllThemes($removeXanthia = true, $hideHiddenThemes = true)
{
    // all themes in the file system
    static $_allthemes = array();
    
    // all inactive Xanthia themes
    static $_inactive  = array();

    // all hidden themes
    static $_hidden    = array();
    

    $themelist = array();
    
    // get all themes from the file system
    if (empty($_allthemes)) {
        // get all theme names from the normal location
        $handle = opendir('themes');
        while ($f = readdir($handle)) {
            if (pnThemeInfo($f)) {
                $_allthemes["$f"] = $f;
            } 
        } 
        closedir($handle); 
        
        // get all theme names from the Multisites location
        if (strlen(WHERE_IS_PERSO) != 0) {
            $handle = opendir(WHERE_IS_PERSO . 'themes');
            while ($f = readdir($handle)) {
                if (pnThemeInfo($f)) {
                    $_allthemes["$f"] = $f;
                } 
            } 
            closedir($handle);
        } 
    }
    
    $themelist = $_allthemes;

    // Remove inactive Xanthia themes from the theme list
    if ($removeXanthia) {
        if (empty($_inactive)) {
            if (pnModAPILoad('Xanthia', 'user')) {
                // Get a list of all Xanthia themes
                $allthemes = pnModAPIFunc('Xanthia', 'user', 'getAllThemes');
                if ($allthemes) {
                    // Get a list of all active Xanthia themes
                    $allskins = pnModAPIFunc('Xanthia', 'user', 'getAllSkins');
                    $activethemes = array();
                    if ($allskins) {
                        foreach($allskins as $allskin) {
                            $activethemes[] = $allskin['name'];
                        } 
                    } 
                    // The difference are the inactive Xanthia themes, they need to be removed
                    $_inactive = array_diff($allthemes, $activethemes);
                } 
            } 
        }
        $themelist = array_diff($themelist, $_inactive);
    } 


    // Remove hidden themes from the theme list
    if ($hideHiddenThemes) {
        if (empty($_hidden)) {
            foreach ($_allthemes as $thistheme) {
                $themeinfo = pnThemeInfo($thistheme);
                if ($themeinfo['hidden']) {
                    $_hidden[] = $thistheme;
                }
            }
        }
        $themelist = array_diff($themelist, $_hidden);
    } 

    // sort array
    ksort($themelist);
    
    // return result set
    return $themelist;
} 

/*
 * load the language file for a theme
 *
 * @author Patrick Kellum
 * @return void
 */
 function pnThemeLangLoad($script = 'global')
{
	$currentlang = pnSessionGetVar('lang');
	$language = pnConfigGetVar('language');
	$theme = pnUserGetTheme();

	if (file_exists($file=WHERE_IS_PERSO.'themes/' . pnVarPrepForOS($theme) . '/lang/' . pnVarPrepForOS($currentlang) . '/' . pnVarPrepForOS($script) . '.php')) {
		@include_once $file;
	} elseif (file_exists($file=WHERE_IS_PERSO.'themes/' . pnVarPrepForOS($theme) . '/lang/' . pnVarPrepForOS($language) . '/' . pnVarPrepForOS($script) . '.php')) {
		@include_once $file;
	} elseif (file_exists($file='themes/' . pnVarPrepForOS($theme) . '/lang/' . pnVarPrepForOS($currentlang) . '/' . pnVarPrepForOS($script) . '.php')) {
		@include_once $file;
	} elseif (file_exists($file='themes/' . pnVarPrepForOS($theme) . '/lang/' . pnVarPrepForOS($language) . '/' . pnVarPrepForOS($script) . '.php')) {
		@include_once $file;
	}

	return;
}

/**
 * pnThemeInfo()
 * <br />
 * This function returns information about a certain theme. 
 * For this purpose, it includes the xaninfo.php file (for 
 * Xanthia themes) or the themeinfo.php (for other themes).
 * <br />
 * If the name passed isn't a valid theme, false is returned. 
 * <br />
 * For a valid theme, at least these values are returned:
 * xanthia (boolean, true if this is a Xanthia theme)
 * hidden  (boolean, true if this is a hidden theme)
 * active  (boolean, true if the theme is active)
 * 
 * @author Joerg Napp
 * @since PostNuke .760
 * @param string $theme the name of the theme
 * @return array the theme information
 **/
function pnThemeInfo($theme)
{
	$theme = isset($theme) ? $theme: '';
    if (!pnVarValidate($theme, 'theme')) {
        return false;
    }
    $themepath = pnVarPrepForOS($theme);
    // determine if this is a valid theme
    if (!file_exists(WHERE_IS_PERSO . "themes/$themepath/theme.php") &&
        !file_exists("themes/$themepath/theme.php")) {
        return false;
    }

    $themeinfo = array();
    
    // Setting the defaults
    $themeinfo['name']    = $theme;         // might be useful
    $themeinfo['hidden']  = false;          // A theme isn't hidden unless explicitly marked as hidden
    $themeinfo['xanthia'] = false;          // assume the theme not to be a Xanthia theme unless xaninfo.php is present.
    $themeinfo['active']  = true;           // assume the theme to be active unless it isn't
    
    if (file_exists($file = WHERE_IS_PERSO . "themes/$themepath/themeinfo.php")) {
        include $file;
    } elseif (file_exists($file = "themes/$themepath/themeinfo.php")) {
        include $file;
    } elseif (file_exists($file = WHERE_IS_PERSO . "themes/$themepath/xaninfo.php")) {
        $themeinfo['xanthia'] = true;
        include $file;
    } elseif (file_exists($file = "themes/$themepath/xaninfo.php")) {
        $themeinfo['xanthia'] = true;
        include $file;
    }

    if ($themeinfo['xanthia']) {
        if (pnModAPILoad('Xanthia', 'user')) {
            // see if the skin is in the database (=active)
            $skinid = pnModAPIFunc('Xanthia', 'user', 'getSkinID', array('skin' => $theme));
            $themeinfo['active'] = (bool)$skinid;
        } else {
            // don't use Xanthia themes without Xanthia.
            // Maybe the function should return false (= not a valid theme) in this case?
            $themeinfo['active'] = false;
        }
    }
    return $themeinfo; 
} 

?>