<?php
// $Id: function.modulejavascript.php 13808 2004-06-13 14:02:04Z landseer $
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
 * Xanthia plugin
 * 
 * This file is a plugin for Xanthia, the PostNuke theme engine
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   Xanthia
 * @version      $Id: function.modulejavascript.php 13808 2004-06-13 14:02:04Z landseer $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2004 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 
 
/**
 * Smarty function to include module specific javascripts
 * 
 * available parameters:
 *  - modname     module name (if not set, the current module is assumed)
 *                if modname="" than we will look into the main javascript folder
 *  - script      name of the external javascript file (mandatory)
 *  - modonly     javascript will only be included when the the current module is $modname
 *  - onload      function to be called with onLoad handler in body tag, makes sense with assign set only, see example #2
 *  - assign      if set, the tag and the script filename are returned
 *
 * Example: <!--[modulejavascript modname=foobar script=openwindow.js modonly=1 ]-->
 * Output:  <script type="text/javascript" src="modules/foobar/pnjavascript/openwindow.js">
 *
 * Example: <!--[modulejavascript modname=foobar script=openwindow.js modonly=1 onload="dosomething()" assign=myjs ]-->
 * Output: nothing, but assigns a variable containing several values:
 *      $myjs.scriptfile = "modules/foobar/pnjavascript/openwindow.js"
 *      $myjs.tag = "<script type=\"text/javascript\" src=\"modules/foobar/pnjavascript/openwindow.js\"></script>"
 *      $myjs.onload = "onLoad=\"dosomething()\"";
 *      Possible code in master.htm would be:
 *
 *      ...
 *      <!--[ $myjs.tag ]-->
 *      </head>
 *      <body <!--[ $myjs.onload ]--> >
 *      ...
 *
 *      which results in
 *
 *      ...
 *      <script type="text/javascript" src="modules/foobar/pnjavascript/openwindow.js"></script>
 *      </head>
 *      <body onLoad="dosomething()" >
 *      ...
 *
 *      if foobar is the current module. 
 *
 * @author       Frank Schummertz
 * @since        13. June 2004
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      The tag
 */
function smarty_function_modulejavascript($params, &$smarty)
{
    // get the parameters
    extract($params); 
	unset($params);
	
	// check if script is set (mandatory)
    if (!isset($script)) {
        $smarty->trigger_error("modulejavascript: missing parameter 'script'", E_ERROR );
        return false;
    }   

    // check if modname is set and if not, if $modonly is set
    if (!isset($modname)) {
        if(isset($modonly)) {
            // error - we want $modonly only with $modname
            $smarty->trigger_error( "modulejavascript: modonly parameter only supported together with modname set", E_ERROR );
            return;
        }
        // we use the current module name
        $modname = pnModGetName();
    }
    if(isset($modonly) && ($modname<>pnModGetName()) ) {
        // current module is not $modname - do nothing and return silently
        return;
    }
    
    // if modname is empty, we will search the main javascript folder
    if($modname=="") {
        $searchpaths = array( "javascript" );
    } else {
	    // theme directory
        $theme         = pnVarPrepForOS(pnUserGetTheme());
        $osmodname     = pnVarPrepForOS($modname);
        $themepath     = "themes/$theme/javascript/$osmodname";
        
	    // module directory
        $modinfo       = pnModGetInfo(pnModGetIDFromName($modname));
	    $osmoddir      = pnVarPrepForOS($modinfo['directory']);
        $modpath       = "modules/$osmoddir/pnjavascript";
        $syspath       = "system/$osmoddir/pnjavascript";

        $searchpaths = array( $themepath, $modpath, $syspath );
    }
    $osscript = pnVarPrepForOS($script);
	
	// search for the javascript
    $scriptsrc = "";
	foreach( $searchpaths as $path) {
        if (file_exists("$path/$osscript") && is_readable("$path/$osscript")) {
		    $scriptsrc = "$path/$osscript";
			break;
		}
    }
    
	// if no module javascript has been found then return no content
	$tag = (empty($scriptsrc)) ? "" : "<script type=\"text/javascript\" src=\"$scriptsrc\"></script>";
    
    // onLoad event handler used?
    $onload = (isset($onload)) ? "onLoad=\"$onload\"" : ""; 
    
    if (isset($assign)) {
        $params['scriptfile'] = $scriptsrc;
        $params['tag']        = $tag;
        $params['onload']     = $onload;
        $smarty->assign($assign, $params);
    } else {
        return $tag;        
    }      
}
?>