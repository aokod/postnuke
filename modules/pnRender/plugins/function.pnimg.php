<?php
// $Id: function.pnimg.php 16594 2005-08-04 11:55:10Z markwest $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2002 by the PostNuke Development Team.
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
/**
 * pnRender plugin
 * 
 * This file is a plugin for pnRender, the PostNuke implementation of Smarty
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   pnRender
 * @version      $Id: function.pnimg.php 16594 2005-08-04 11:55:10Z markwest $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to provide easy access to an image
 * 
 * This function provides an easy way to include an image. The function will return the
 * full source path to the image. It will as well provite the width and height attributes 
 * if none are set.
 * 
 * Available parameters:
 *   - src:           The file name of the image
 *   - modname:       The well-known name of a module (default: the current module)
 *   - width, height: If set, they will be passed. If none is set, they are obtained from the image 
 *   - alt:           If not set, an empty string is being assigned
 *   - altml:         If true then alt string is assumed to be a ML constant
 *   - title:         If not set, an empty string is being assigned
 *   - titleml:       If true then title string is assumed to be a ML constant
 *   - assign:        If set, the results are assigned to the corresponding variable instead of printed out
 *   - optional       If set then the plugin will not return an error if an image is not found
 *   - default        If set then a default image is used should the requested image not be found (Note: full path required)
 *   - all remaining parameters are passed to the image tag
 * 
 * Example: <!--[pnimg src="heading.gif" ]-->
 * Output:  <img src="modules/Example/pnimages/eng/heading.gif" alt="" width="261" height="69"  />
 * 
 * Example: <!--[pnimg src="heading.gif" width="100" border="1" alt="foobar" ]-->
 * Output:  <img src="modules/Example/pnimages/eng/heading.gif" width="100" border="1" alt="foobar"  />
 *
 * If the parameter assign is set, the results are assigned as an array. The components of
 * this array are the same as the attributes of the img tag; additionally an entry 'imgtag' is
 * set to the complete image tag.
 * 
 * Example: 
 * <!--[pnimg src="heading.gif" assign="myvar"]-->
 * <!--[$myvar.src]-->
 * <!--[$myvar.width]-->
 * <!--[$myvar.imgtag]-->
 * 
 * Output:
 * modules/Example/pnimages/eng/heading.gif
 * 261   
 * <img src="modules/Example/pnimages/eng/heading.gif" alt="" width="261" height="69"  />
 *   
 * 
 * @author       Joerg Napp
 * @since        05. Nov. 2003
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      The img tag
 */
function smarty_function_pnimg($params, &$smarty)
{
    // get the parameters
    extract($params); 
	unset($params['src']);
	unset($params['modname']);
	unset($params['assign']);
	unset($params['altml']);
	unset($params['titleml']);
	unset($params['optional']);
	unset($params['default']);

    if (!isset($src)) {
        $smarty->trigger_error('pnimg: attribute src required');
        return false;
    }

    // default for the module
    if (!isset($modname)) {
        $modname = $smarty->module;
    }    

    // default for the optional flag
    if (!isset($optional)) {
		$optional = true;
    }

    // always provide an alt attribute.
    // if none is set, assign an empty one.
    if (!isset($alt)) {
        $params['alt'] = '';
    }    
    if (!isset($title)) {
        $params['title'] = '';
    }  
    
    // check if the alt string is an ml constant
    if (isset($altml) && is_bool($altml) && $altml) {
		$params['alt'] = constant($params['alt']);
    }

    // check if the alt string is an ml constant
    if (isset($titleml) && is_bool($titleml) && $titleml) {
		$params['title'] = constant($params['title']);
    }

    // language 
    $lang =  pnVarPrepForOS(pnUserGetLang());

    // theme directory
    $theme         = pnVarPrepForOS(pnUserGetTheme());
    $osmodname     = pnVarPrepForOS($modname);
    $cWhereIsPerso = WHERE_IS_PERSO;
    if (!(empty($cWhereIsPerso))) {
    	$themelangpath = $cWhereIsPerso . "themes/$theme/templates/modules/$osmodname/images/$lang";
    	$themepath     = $cWhereIsPerso . "themes/$theme/templates/modules/$osmodname/images";
    	$corethemepath = $cWhereIsPerso . "themes/$theme/images";
    } else {
        $themelangpath = "themes/$theme/templates/modules/$osmodname/images/$lang";
    	$themepath     = "themes/$theme/templates/modules/$osmodname/images";
    	$corethemepath = "themes/$theme/images";
    }
    // module directory
    $modinfo       = pnModGetInfo(pnModGetIDFromName($modname));
    $osmoddir      = pnVarPrepForOS($modinfo['directory']);
    if ($modinfo['type'] != 1) {
		$modlangpath   = "modules/$osmoddir/pnimages/$lang";
		$modpath       = "modules/$osmoddir/pnimages";
		$syslangpath   = "system/$osmoddir/pnimages/$lang";
		$syspath       = "system/$osmoddir/pnimages";
    } else {
		$modlangpath   = "modules/$osmoddir/images/$lang";
		$modpath       = "modules/$osmoddir/images";
		$syslangpath   = "system/$osmoddir/images/$lang";
		$syspath       = "system/$osmoddir/images";
    }
    $ossrc = pnVarPrepForOS($src);

    // search for the image
    $imgsrc = '';
    foreach (array($themelangpath,
				   $themepath,
				   $corethemepath,
				   $modlangpath,
				   $modpath,
				   $syslangpath,
				   $syspath) as $path) {
     	if (file_exists("$path/$ossrc") && is_readable("$path/$ossrc")) {
    		$imgsrc = "$path/$ossrc";
		break;
    	}
    }

    if ($imgsrc == '' && isset($default)) {
	$imgsrc = $default;
    }

    if ($imgsrc == '') {
	if ($optional) {
		$smarty->trigger_error("pnimg: image $src not found");
	}
        return;
    }

    // If neither width nor height is set, get these parameters.
    // If one of them is set, we do NOT obtain the real dimensions.
    // This way it is easy to scale the image to a certain dimension.
    if(!isset($params['width']) && !isset($params['height'])) {
        if(!$_image_data = @getimagesize($imgsrc)) {
            $smarty->trigger_error("pnimg: image $src is not a valid image file");
            return false;
        }
        $params['width']  = $_image_data[0];
        $params['height'] = $_image_data[1];
    }

    $imgtag = '<img src="'.pnGetBaseURI().'/'.$imgsrc.'" ';
	foreach ($params as $key => $value) {
        $imgtag .= $key . '="' .$value  . '" ';
    }
	$imgtag .= ' />';
	
    if (isset($assign)) {
        $params['src'] = $imgsrc;
        $params['imgtag'] = $imgtag;
        $smarty->assign($assign, $params);
    } else {
        return $imgtag;        
    }      
}
?>