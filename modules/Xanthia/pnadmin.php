<?php
// $Id: pnadmin.php 19417 2006-07-16 19:43:20Z markwest $
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
// ----------------------------------------------------------------------------
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
 * @package PostNuke_System_Modules
 * @subpackage Xanthia
 * @license http://www.gnu.org/copyleft/gpl.html
*/

/**
 * the main administration function
 * This function is the default function, and is called whenever the
 * module is initiated without defining arguments.  As such it can
 * be used for a number of things, but most commonly it either just
 * shows the module menu and returns or calls whatever the module
 * designer feels should be the default function (often this is the
 * view() function)
 * @author Larry E. Masters aka PhpNut <nut@phpnut.com>
 * @link http://www.larrymasters.com
 * @since         1.1
 * @version $Revision: 19417 $
 * @return string HTML string
 * @todo clean up code layout
 */
function xanthia_admin_main()
{
	/** Security check - important to do this as early as possible to avoid
	* potential security holes or just too much wasted processing.  For the
	* main function we want to check that the user has at least edit privilege
	* for some item within this component, or else they won't be able to do
	* anything and so we refuse access altogether.  The lowest level of access
	* for administration depends on the particular module, but it is generally
	* either 'edit' or 'delete'
	*/
    if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
    // Load user API
	if (!pnModAPILoad('Xanthia','user')) {	    
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
    // Return the Admin View Function
	//return xanthia_adminmenu();
	return xanthia_admin_view();
}

/**
 * Theme menu bar
 * @access        private
 * @author        Larry E. Masters aka PhpNut
 * @since         1.1
 * @param         none
 * @return        mixed    $output      the page output
 */
function xanthia_admin_thememenu($args)
{
	/** Security check - important to do this as early as possible to avoid
	* potential security holes or just too much wasted processing.  For the
	* main function we want to check that the user has at least edit privilege
	* for some item within this component, or else they won't be able to do
	* anything and so we refuse access altogether.  The lowest level of access
	* for administration depends on the particular module, but it is generally
	* either 'edit' or 'delete'
	*/
	if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
	}
    //extract($args);
    
    $skin = pnVarCleanFromInput('skin');

    // Load user API
	if (!pnModAPILoad('Xanthia','user')) {	    
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}  
    
	if (is_numeric($skin)) {
		// get the active skin name and ID
		//$skinName = pnVarCleanFromInput('skin');
		$skinName = pnModAPIFunc('Xanthia','user','getSkinFromID',
									array('id' => $skin));			
	} else {
	    $skinName = pnVarCleanFromInput('skin');
	}
	

	      
    // Create output object - this object will store all of our output so that
    // we can return it easily when required
	$pnRender =& new pnRender('Xanthia');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

    // assign the skin name to the template
	$pnRender->assign('skin', $skinName);
	
	$authid = pnSecGenAuthKey();
	$pnRender->assign('authid', $authid);
	
	$cachedthemes = pnModGetVar('Xanthia',''.$skinName.'themecache');
	$pnRender->assign('cachedthemes', $cachedthemes);
	
    $pnRender->assign('showhelp', pnModGetVar('Xanthia','help'));
    $menuoptions = array();
    $menuoptions[] = array('url' => pnVarPrepForDisplay(pnModURL('Xanthia', 'admin', 'BlockZones',
                                                                  array('skin' =>$skinName))), 
	                       'title' => pnVarPrepForDisplay(_XA_BCZONES),
	                       'helpurl' => pnVarPrepForDisplay(pnModURL('Xanthia', 'admin', 'help',
                                                                 array('topic' => 'BlockZones'))),
	                       'helptext' => _XA_BLOCKZONESHELP,
						   'cid' => 'BlockZones');
	                       
    $menuoptions[] = array('url' => pnVarPrepForDisplay(pnModURL('Xanthia', 'admin', 'editTheme',
                                                                  array('skin' =>$skinName))), 
	                       'title' => pnVarPrepForDisplay(_XA_THEMEZONES),
	                       'helpurl' => pnVarPrepForDisplay(pnModURL('Xanthia', 'admin', 'help',
                                                                 array('topic' => 'editTheme'))),
	                       'helptext' => _XA_EDITTHEMEHELP,
						   'cid' => 'editTheme');

    $menuoptions[] = array('url' => pnVarPrepForDisplay(pnModURL('Xanthia', 'admin', 'editTemplates',
                                                                array('skin' => $skinName))), 
	                       'title' => pnVarPrepForDisplay(_XA_EDITTEMPLATES),
	                       'helpurl' => pnVarPrepForDisplay(pnModURL('Xanthia', 'admin', 'help',
                                                                 array('topic' => 'editTemplates'))),
	                       'helptext' => _XA_EDITTEMPLATEHELP,
						   'cid' => 'editTemplates');
						   
/*   $menuoptions[] = array('url' => pnVarPrepForDisplay(pnModURL('Xanthia', 'admin', 'editModTemplates',
                                                                array('skin' => $skinName))), 
	                       'title' => pnVarPrepForDisplay(_XA_EDITMODTEMPLATES),
	                       'helpurl' => pnVarPrepForDisplay(pnModURL('Xanthia', 'admin', 'help',
                                                                 array('topic' => 'editModTemplates'))),
	                       'helptext' => _XA_EDITMODTEMPLATEHELP,
						   'cid' => 'editModTemplates');*/
                   
    $menuoptions[] = array('url' => pnVarPrepForDisplay(pnModURL('Xanthia', 'admin', 'editTheme',
                                                                 array('todo' => 'colors',
                                                                       'skin' =>$skinName))), 
	                       'title' => pnVarPrepForDisplay(_XA_THEMECOLORS),
	                       'helpurl' => pnVarPrepForDisplay(pnModURL('Xanthia', 'admin', 'help',
                                                                 array('topic' => 'colors'))),
	                       'helptext' => _XA_COLORSHELP,
						   'cid' => 'editThemecolors');
	                         
    $menuoptions[] = array('url' => pnVarPrepForDisplay(pnModURL('Xanthia', 'admin', 'editTheme',
                                                                 array('todo' => 'config',
                                                                       'skin' =>$skinName))), 
	                       'title' => pnVarPrepForDisplay(_XA_THEMECONFIGURE),
	                       'helpurl' => pnVarPrepForDisplay(pnModURL('Xanthia', 'admin', 'help',
                                                                 array('topic' => 'config'))),
	                       'helptext' => _XA_THEMECONTIGHELP,
						   'cid' => 'editThemeconfig');
	                       
    $menuoptions[] = array('url' => pnVarPrepForDisplay(pnModURL('Xanthia', 'admin', 'bcontrol',
                                                                 array('skin' => $skinName))),
	                       'title' => pnVarPrepForDisplay(_XA_BCCONTROL),
	                       'helpurl' => pnVarPrepForDisplay(pnModURL('Xanthia', 'admin', 'help',
                                                                 array('topic' => 'bcontrol'))),
	                       'helptext' => _XA_BCCONTROLHELP,
						   'cid' => 'bcontrol');
	                       
    $pnRender->assign('menuoptions', $menuoptions);
	// get the current active link
	$currentcat = ''.pnVarCleanFromInput('func') . pnVarCleanFromInput('todo').'' ;

	$pnRender->assign('currentcat', $currentcat);
    // Return the output that has been generated by this function
    return $pnRender->fetch('xanthiaadminmenunav.htm');
}

/**
 * Theme menu bar
 * @access        private
 * @author        Larry E. Masters aka PhpNut
 * @since         1.1
 * @param         none
 * @return        mixed    $output      the page output
 */
function xanthia_adminmenu()
{
	/** Security check - important to do this as early as possible to avoid
	 * potential security holes or just too much wasted processing.  For the
	 * main function we want to check that the user has at least edit privilege
	 * for some item within this component, or else they won't be able to do
	 * anything and so we refuse access altogether.  The lowest level of access
	 * for administration depends on the particular module, but it is generally
	 * either 'edit' or 'delete'
	 */
	if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
        
    // Create output object - this object will store all of our output so that
    // we can return it easily when required
	$pnRender =& new pnRender('Xanthia');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;
        
    // Return the output that has been generated by this function
    return $pnRender->fetch('xanthiaadminmenu.htm');
}

/**
 * View Xanthia
 * This is a standard function called to present the administrator with a list
 * of all items held by the module.
 * @author Larry E. Masters aka PhpNut <nut@phpnut.com>
 * @link http://www.larrymasters.com
 * @version $Revision: 19417 $
 * @return string HTML string
 * @todo clean up code layout
 */
function xanthia_admin_view()
{
	/** Security check - important to do this as early as possible to avoid
	 * potential security holes or just too much wasted processing.  For the
	 * main function we want to check that the user has at least edit privilege
	 * for some item within this component, or else they won't be able to do
	 * anything and so we refuse access altogether.  The lowest level of access
	 * for administration depends on the particular module, but it is generally
	 * either 'edit' or 'delete'
	 */
	if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
	}

	// Load user API
	if (!pnModAPILoad('Xanthia','user')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
   	}

	// Load admin API
	if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
   	}

	// Create output object - this object will store all of our output so that
	// we can return it easily when required
	$pnRender =& new pnRender('Xanthia');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

    $pnRender->assign('showhelp', pnModGetVar('Xanthia','help'));

	// Themes
	// Get a list of all themes in the themes dir
	$allthemes = pnModAPIFunc('Xanthia','user','getAllThemes');
	// Get a list of all themes in database
	$allskins = pnModAPIFunc('Xanthia','user','getAllSkins');

	$skins = array();
    if ($allskins) {
	    foreach($allskins as $allskin) {
	        $skins[] = $allskin['name'];
	    }
	}

    // Generate an Authorization ID
    $authid = pnSecGenAuthKey();
	$pnRender->assign('authid', $authid);

    if ($allthemes){
        //Start Foreach
        foreach($allthemes as $themes) {
            // Add applicable actions
            $actions = array();

    		switch ($themes) {
                //If theme is active in Xanthia then show the edit theme link
		        case in_array($themes, $skins):
                    $state = 1;
                    break;
                //If theme is not active in Xanthia then show the add theme link 
			    default:
                    $state = 0;
                    break;
			}
  
	        $theme[] = array('state' => $state,
						     'themename' => $themes);
            //End Foreach
        }
    }
	$pnRender->assign('theme', $theme);
    // Return the output that has been generated to the template
    return $pnRender->fetch('xanthiaadminviewmain.htm');
}

/**
 * Configure Xanthia Main Site Settings
 * This function is called to display the configuration settings
 * for Xanthia
 * @author Larry E. Masters aka PhpNut <nut@phpnut.com>
 * @link http://www.larrymasters.com
 * @since         1.1
 * @version $Revision: 19417 $
 * @return string HTML string
 * @todo clean up code layout
 */
function xanthia_admin_config()
{

	/** Security check - important to do this as early as possible to avoid
	 * potential security holes or just too much wasted processing.  For the
	 * main function we want to check that the user has at least edit privilege
	 * for some item within this component, or else they won't be able to do
	 * anything and so we refuse access altogether.  The lowest level of access
	 * for administration depends on the particular module, but it is generally
	 * either 'edit' or 'delete'
	 */
	if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
   	}

	// Load user API
	if (!pnModAPILoad('Xanthia','user')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

    // Load admin API
	if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	// Create output object - this object will store all of our output so that
	// we can return it easily when required
	$pnRender =& new pnRender('Xanthia');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

    // Visual Block Editor
    $pnRender->assign('vba', pnModGetVar('Xanthia', 'vba'));
    
    if (file_exists(".htaccess")){ 
	    $pnRender->assign('htaccess', 1); 
    } else {
    	$pnRender->assign('htaccess', 0); 
    }        

	// Short urls
    $pnRender->assign('shorturls', pnModGetVar('Xanthia', 'shorturls'));

	// Short urls
    $pnRender->assign('shorturlsextension', pnModGetVar('Xanthia', 'shorturlsextension'));

    // Enable Cache
    $pnRender->assign('enablecache', pnModGetVar('Xanthia', 'enablecache'));

	// modules not to cache for
	$pnRender->assign('modulesnocache', pnModGetVar('Xanthia', 'modulesnocache'));

    // Cache templates to Database
    $pnRender->assign('db_cache', pnModGetVar('Xanthia', 'db_cache'));
    
    // Compile Templates to Database (not active yet)
    $pnRender->assign('db_compile', pnModGetVar('Xanthia', 'db_compile'));

    // Check for new version of template
    $pnRender->assign('compile_check', pnModGetVar('Xanthia', 'compile_check'));

    // Force Check for new version of template
    $pnRender->assign('force_compile', pnModGetVar('Xanthia', 'force_compile'));
    
    // Cache Time
    $pnRender->assign('cache_lifetime', pnModGetVar('Xanthia', 'cache_lifetime'));

    // Base Caching on Database Updates
    $pnRender->assign('use_db', pnModGetVar('Xanthia', 'use_db'));

    // Use Database for Templates
    $pnRender->assign('db_templates', pnModGetVar('Xanthia', 'db_templates'));

    // use of whitespace output filter
	$pnRender->assign('trimwhitespace', pnModGetVar('Xanthia', 'trimwhitespace'));

    // Return the output that has been generated by this function
    if (ereg('0.8', _PN_VERSION_NUM)) {
        return $pnRender->fetch('xanthiaadminconfig.htm');
	} else {
        return $pnRender->fetch('xanthiaadmin726config.htm');
	}
}

/**
 * @access        private
 * @since         1.1
 * @param         none
 * @return        mixed    $output      the page output
 */
function xanthia_admin_modifyZones()
{
	/** Security check - important to do this as early as possible to avoid
	* potential security holes or just too much wasted processing.  For the
	* main function we want to check that the user has at least edit privilege
	* for some item within this component, or else they won't be able to do
	* anything and so we refuse access altogether.  The lowest level of access
	* for administration depends on the particular module, but it is generally
	* either 'edit' or 'delete'
	*/
    if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

	// grab our parameters in a secure manner
	list($task, $skin, $zone, $skintype) = pnVarCleanFromInput('task', 'skin', 'zone', 'skintype');

	// check our parameters
	if (empty($task) || empty($skin) || empty($zone)) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	// load user API
	if (!pnModAPILoad('Xanthia', 'user')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	// load admin API
	if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	// Figure out the task at hand
	switch ($task) {
		// TASK: configure templates
		case 'configure':
			$templates = pnModAPIFunc('Xanthia','admin','getThemeTplFile',
											array('skin' => $skin,
												  'zone' => $zone,
												  'type' => $skintype));
												  
			// @var array $tplinfo | holds the info to build a zone select list
			$tplinfo = array();
			if (is_array($templates)) {
				foreach ($templates as $template) {
				
					$tplinfo[] = array('id' => $template,
	 								   'name' => $template);
				}
			}

			$seltemplate = pnModAPIFunc('Xanthia','admin','listTpl',
											array('skin' => $skin,
												  'zone' => $zone));
	
			// Check results
			if (empty($seltemplate)) {
				$seltemplate = '';
			}
       		// Create output object - this object will store all of our output so that
			// we can return it easily when required
			$pnRender =& new pnRender('Xanthia');
		
			// As Admin output changes often, we do not want caching.
			$pnRender->caching = false;

            $pnRender->assign('menu', pnModFunc('Xanthia','admin','thememenu'));
            $pnRender->assign('skinid', $skin);
            $pnRender->assign('zone', $zone);
            $pnRender->assign('skintype', $skintype);
            $pnRender->assign('defaultzone', $seltemplate);
            $pnRender->assign('allzones', $tplinfo);
	        
	        return $pnRender->fetch('xanthiaadminmodzone.htm');
	        
			break;

		// TASK: deactivate zone
		case 'deactivate':
			// Update state
			if (pnModAPIFunc('Xanthia', 'admin', 'setstate',
									array('skin' => $skin,
										  'zone' => $zone,
										  'task' => 'deactivate'))) {
				// Successful deactivation
				pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_XA_ZONEDEACTIVATED));
				
				$skinName = pnModAPIFunc('Xanthia','user','getSkinFromID',
				array('id' => $skin));
				
				$cachedthemes = pnModGetVar('Xanthia',''.$skinName.'themecache');
				if (isset($cachedthemes)){
					pnModAPIFunc('Xanthia', 'admin', 'writethemetplcache', array('skinid' => $skin));
				}
										  }

			// Work completed, back to the menu
			pnRedirect(pnModURL('Xanthia', 'admin', 'editTheme',
									array('skin' => $skin)));
			return true;
			break;

		// TASK: activate zone
		case 'activate':
			// Update state
			if (pnModAPIFunc('Xanthia', 'admin', 'setstate',
									array('skin' => $skin,
										  'zone' => $zone,
										  'task' => 'activate'))) {
				// Successful activation
				pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_XA_ZONEACTIVATED));
				
				$skinName = pnModAPIFunc('Xanthia','user','getSkinFromID',
				array('id' => $skin));
				
				$cachedthemes = pnModGetVar('Xanthia',''.$skinName.'themecache');
				if (isset($cachedthemes)){
					pnModAPIFunc('Xanthia', 'admin', 'writethemetplcache', array('skinid' => $skin));
				}
			}

			// Work completed, back to the menu
			pnRedirect(pnModURL('Xanthia', 'admin', 'editTheme',
									array('skin' => $skin)));
			return true;
			break;
	}

}

/**
 * @access        private
 * @author Larry E. Masters aka PhpNut <nut@phpnut.com>
 * @link http://www.larrymasters.com
 * @since       1.10
 * @param		int		$skin		ID for the skin
 * @param		int		$paletteid	ID for the palette
 * @return      mixed   the page output
 */
function xanthia_admin_newColor($args)
{
	/** Security check - important to do this as early as possible to avoid
	* potential security holes or just too much wasted processing.  For the
	* main function we want to check that the user has at least edit privilege
	* for some item within this component, or else they won't be able to do
	* anything and so we refuse access altogether.  The lowest level of access
	* for administration depends on the particular module, but it is generally
	* either 'edit' or 'delete'
	*/
    if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

	list($skin, $paletteid) = pnVarCleanFromInput('skin','paletteid'); 
	
    // Load user API
	if (!pnModAPILoad('Xanthia','user')) {	    
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	$skinName = pnModAPIFunc('Xanthia','user','getSkinFromID',
									array('id' => $skin));	 
	 
	// Create output object - this object will store all of our output so that
	// we can return it easily when required
	$pnRender =& new pnRender('Xanthia');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

    $pnRender->assign('menu', pnModFunc('Xanthia','admin','thememenu'));

	$rows[] = array('palname'     => '',
					'bgcolor'     => 'FFFFFF',
					'color1'      => 'FFFFFF',
					'color2'      => 'FFFFFF',
					'color3'      => 'FFFFFF',
					'color4'      => 'FFFFFF',
					'color5'      => 'FFFFFF',
					'color6'      => 'FFFFFF',
					'color7'      => 'FFFFFF',
					'color8'      => 'FFFFFF',
					'sepcolor'    => '000000',
					'text1'       => '000000',
					'text2'       => '000000',
					'link'        => '000000',
					'vlink'       => '000000',
					'hover'       => '000000');
	
	$pnRender->assign('authid', pnSecGenAuthKey('Xanthia'));
	$pnRender->assign('skin', $skin); 
	$pnRender->assign('paletteid', '');            
	$pnRender->assign('items', $rows);           
    return $pnRender->fetch('xanthiaadminmodifycolors.htm');
}

/**
 * @access        private
 * @author Larry E. Masters aka PhpNut <nut@phpnut.com>
 * @link http://www.larrymasters.com
 * @since       1.10
 * @param		int		$skin		ID for the skin
 * @param		int		$paletteid	ID for the palette
 * @return      mixed   the page output
 */
function xanthia_admin_modifyColors($args)
{
	/** Security check - important to do this as early as possible to avoid
	* potential security holes or just too much wasted processing.  For the
	* main function we want to check that the user has at least edit privilege
	* for some item within this component, or else they won't be able to do
	* anything and so we refuse access altogether.  The lowest level of access
	* for administration depends on the particular module, but it is generally
	* either 'edit' or 'delete'
	*/
    if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

	// grab our parameters in a secure manner
	list($skin, $paletteid) = pnVarCleanFromInput('skin','paletteid');

	// load admin API
	if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

    // Load user API
	if (!pnModAPILoad('Xanthia','user')) {	    
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	
	// get the name of the skin
	$skinName = pnModAPIFunc('Xanthia','user','getSkinFromID',
									array('id' => $skin));
									
 	$colors = pnModAPIFunc('Xanthia', 'admin', 'listColors',
								array('skinid' => $skin, 'paletteid' => $paletteid));
								
	foreach ($colors as $color)	{
		$rows[] = array('palname'     => $color['palette_name'],
						'bgcolor'     => str_replace('#','',$color['background']),
						'color1'      => str_replace('#','',$color['color1']),
						'color2'      => str_replace('#','',$color['color2']),
						'color3'      => str_replace('#','',$color['color3']),
						'color4'      => str_replace('#','',$color['color4']),
						'color5'      => str_replace('#','',$color['color5']),
						'color6'      => str_replace('#','',$color['color6']),
						'color7'      => str_replace('#','',$color['color7']),
						'color8'      => str_replace('#','',$color['color8']),
						'sepcolor'    => str_replace('#','',$color['sepcolor']),
						'text1'       => str_replace('#','',$color['text1']),
						'text2'       => str_replace('#','',$color['text2']),
						'link'        => str_replace('#','',$color['link']),
						'vlink'       => str_replace('#','',$color['vlink']),
						'hover'       => str_replace('#','',$color['hover']));
	}

	// Create output object - this object will store all of our output so that
	// we can return it easily when required
	$pnRender =& new pnRender('Xanthia');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

	$pnRender->assign('menu', pnModFunc('Xanthia','admin','thememenu'));
	$pnRender->assign('authid', pnSecGenAuthKey('Xanthia'));
	$pnRender->assign('skin', $skin); 
	$pnRender->assign('paletteid', $paletteid);            
	$pnRender->assign('items', $rows);           
	return $pnRender->fetch('xanthiaadminmodifycolors.htm');

}

/**
 * Update zone settings and configurations
 * @access		private
 * @author		mh7
 * @since		0.925
 * @param		string	$task		action to perform
 * @param		int		$skin		ID for the skin
 * @param		string	$zone		label of the zone we are looking at
 * @param		string	$template	the template file we are to use
 * @return		bool				true upon successful update
 *									redirect to xanthia_admin_view()
 *									ID of the updated skin
 */
function xanthia_admin_updateZones($args)
{
    //extract($args);
	// check the session auth key
	if (!pnSecConfirmAuthKey())	{
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
		pnRedirect(pnModURL('Xanthia', 'admin', 'main'));
		return true;
	}

	// grab our parameters in a secure manner
	list($skin, $zone, $tpl, $skintype) = pnVarCleanFromInput('skin', 'zone', 'tpl', 'skintype');

	// check for our parameters
	if (empty($skin) || empty($zone) || empty($tpl)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
		pnRedirect(pnModURL('Xanthia', 'admin', 'main'));
		return true;
	}

	// load admin API
	if (!pnModAPILoad('Xanthia', 'admin')) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
		pnRedirect(pnModURL('Xanthia', 'admin', 'main'));
		return true;
	}

	// Update template
	if (pnModAPIFunc('Xanthia', 'admin', 'updateZones',
							array('skin' => $skin,
								  'zone' => $zone,
								   'tpl' => $tpl,
								   'skintype' => $skintype)))	{
		// Success
		pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_XA_TPLUPDATED));
	}

    // Load user API
	if (!pnModAPILoad('Xanthia','user')) {	    
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	
	$skinName = pnModAPIFunc('Xanthia','user','getSkinFromID',
					   array('id' => $skin));
					   
	$cachedthemes = pnModGetVar('Xanthia',''.$skinName.'themecache');
	if (isset($cachedthemes)){
		pnModAPIFunc('Xanthia', 'admin', 'writethemetplcache', array('skinid' => $skin));
	}
	// Work completed, return to main
	pnRedirect(pnModURL('Xanthia', 'admin', 'editTheme',
							array('skin' => $skinName)));
	return true;
}

/**
 * Update skin color sets
 * @access		private
 * @author		mh7
 * @since		0.925
 * @author Larry E. Masters aka PhpNut <nut@phpnut.com>
 * @link http://www.larrymasters.com
 * @since		1.10
 * @param		int		$skin		ID for the skin
 * @param		int		$paletteid	ID for the palette
 * @param		string	$palname	name of color palette
 * @param		string	$bgcolor	background color (HEX)
 * @param		string	$color1 	color #1 (HEX)
 * @param		string	$color2		color #2 (HEX)
 * @param		string	$color3 	color #3 (HEX)
 * @param		string	$color4		color #4 (HEX)
 * @param		string	$color5 	color #5 (HEX)
 * @param		string	$color6		color #6 (HEX)
 * @param		string	$color7 	color #7 (HEX)
 * @param		string	$color8 	color #8 (HEX)
 * @param		string	$sepcolor	seperator color (HEX)
 * @param		string	$text1 		text color #1 (HEX)
 * @param		string	$text2		text color #2 (HEX)
 * @param		string	$link		link color (HEX)
 * @param		string	$vlink		visited link color (HEX)
 * @param		string	$hover 		hover color (HEX)
 * @return		bool				true upon successful update
 *									redirect to xanthia_admin_view()
 *									ID of the updated skin
 */
function xanthia_admin_updateColors($args)
{
	extract($args);
	// check the session auth key
	if (!pnSecConfirmAuthKey())	{
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
		pnRedirect(pnModURL('Xanthia', 'admin', 'main'));
		return true;
	}

	// grab our parameters in a secure manner
	list($skin, $paletteid) = pnVarCleanFromInput('skin','paletteid');
	list($palname,
		 $bgcolor,
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
		 $hover) = pnVarCleanFromInput('palname',
									   'bgcolor',
									   'color1',
									   'color2',
									   'color3',
									   'color4',
									   'color5',
									   'color6',
									   'color7',
									   'color8',
									   'sepcolor',
									   'text1',
									   'text2',
									   'link',
									   'vlink',
									   'hover');

	// check for our parameters
	if (empty($palname)) {
		pnSessionSetVar('errormsg', pnVarPrepForDisplay(_XA_ARGSERROR));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
		return false;
	}

	// load admin API
	if (!pnModAPILoad('Xanthia', 'admin')){
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	// Update colors
	if (pnModAPIFunc('Xanthia', 'admin', 'updateColors',
						  array('skin'	        => $skin,
                                'paletteid'     => $paletteid,
                                'palname'       => $palname,
                                'bgcolor'       => $bgcolor,
                                'color1'        => $color1,
                                'color2'        => $color2,
                                'color3'        => $color3,
                                'color4'        => $color4,
                                'color5'        => $color5,
                                'color6'        => $color6,
                                'color7'        => $color7,
                                'color8'        => $color8,
                                'sepcolor'      => $sepcolor,
                                'text1'         => $text1,
                                'text2'         => $text2,
                                'link'          => $link,
                                'vlink'         => $vlink,
                                'hover'         => $hover))) {
		// Success
		pnSessionSetVar('statusmsg', pnVarPrepForDisplay(_XA_COLORSUPDATED));
		
    // Load user API
	if (!pnModAPILoad('Xanthia','user')) {	    
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	
	
	$skinName = pnModAPIFunc('Xanthia','user','getSkinFromID',
			array('id' => $skin));

			if ($paletteid == pnModGetVar('Xanthia',''.$skinName.'use')){
				
				$cachedthemes = pnModGetVar('Xanthia',''.$skinName.'themecache');
				
				if (isset($cachedthemes)){
					pnModAPIFunc('Xanthia', 'admin', 'writepalettescache', array('skinid' => $skin));
					pnModAPIFunc('Xanthia', 'admin', 'writestylesheet', array('skinid' => $skin,
							'paletteid' => $paletteid));
				}
			}
                                              }
	// Work completed, return to main
	pnRedirect(pnModURL('Xanthia', 'admin', 'editTheme',
							array('todo' => 'colors',
							      'skin' => $skinName)));
	return true;
}

/**
 * Update general configurations
 * @access		private
 * @author		mh7
 * @since		0.925
 * @param		int		$skin		ID for the skin
 * @param		str		$config		config name we are modifying
 * @param		string	$setting	config new value
 * @return		bool				true upon successful update
 *									redirect to xanthia_admin_view()
 *									ID of the updated skin
 */
function xanthia_admin_updateConfig()
{
	// check the session auth key
	if (!pnSecConfirmAuthKey())	{
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
		pnRedirect(pnModURL('Xanthia', 'admin', 'main'));
		return true;
	}

	// grab our parameters in a secure manner
	list($skin, $config,
		 $setting, $authid) = pnVarCleanFromInput( 'skin', 'config',
												'setting', 'authid');

	// check for our parameters
	// FIXED: <apathy>		Took out a call to a non existant variable
	if (empty($skin) || empty($config))	{
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
		return false;
	}

	// load admin API
	if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	if (!pnModAPILoad('Xanthia', 'user')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	// Update config
	if (pnModAPIFunc('Xanthia', 'admin', 'updateConfig',
						array('skin' => $skin,
							'config' => $config,
						   'setting' => $setting)))	{
		// Success
		pnSessionSetVar('statusmsg', _XA_CONFIGUPDATED);

	$skinName = pnModAPIFunc('Xanthia','user','getSkinFromID', array('id' => $skin));
	$cachedthemes = pnModGetVar('Xanthia',''.$skinName.'themecache');	
	
	if (isset($cachedthemes)){
		pnModAPIFunc('Xanthia', 'admin', 'writesettingscache', array('skinid' => $skin));
		
		$paletteid = pnModGetVar('Xanthia', ''.$skinName.'use');
		pnModAPIFunc('Xanthia', 'admin', 'writestylesheet', array('skinid' => $skin,
																  'paletteid' => $paletteid));
	}
						   }

	// Work completed, return to main
	pnRedirect(pnModURL('Xanthia', 'admin', 'editTheme',
							array('skin' => $skin,
							      'todo' => 'config')));
	return true;
}

/**
 * Gather info to create new zone
 * @access		private
 * @author		mh7
 * @since		0.925
 * @param		int		$skin		ID for the skin
 * @return		mixed	$output		the page output
 */
function xanthia_admin_newZones()
{
	/** Security check - important to do this as early as possible to avoid
	* potential security holes or just too much wasted processing.  For the
	* main function we want to check that the user has at least edit privilege
	* for some item within this component, or else they won't be able to do
	* anything and so we refuse access altogether.  The lowest level of access
	* for administration depends on the particular module, but it is generally
	* either 'edit' or 'delete'
	*/
    if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

	// grab our parameters in a secure manner
	$skin = pnVarCleanFromInput('skin');
	$authid = pnVarCleanFromInput('authid');
	// check for our parameters
	if (empty($skin)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
		return false;
	}

	// load admin API
	if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	// Create output object - this object will store all of our output so that
	// we can return it easily when required
	$pnRender =& new pnRender('Xanthia');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

    // Load user API
	if (!pnModAPILoad('Xanthia','user')) {	    
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	
    // Add menu to output - it helps if all of the module pages have a standard
    // menu at their head to aid in navigation
    $skinName = pnModAPIFunc('Xanthia','user','getSkinFromID', array('id' => $skin));
    $pnRender->assign('menu', pnModFunc('Xanthia','admin','thememenu'));

    // Title - putting a title ad the head of each page reminds the user what
    // they are doing
    $pnRender->assign('skin', $skin);
    
    if (!pnModAPILoad('Modules', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
    }

    // select mod for template
    $allmodules = array(array('id' => '*admin', 'name' => _XA_ADMINPAGES),
	                    array('id' => '*home', 'name'  => _XA_HOMEPAGE),
						array('id' => '*user', 'name'  => _XA_USERPAGES));
    $mods = pnModAPIFunc('Modules', 'admin', 'list');
    foreach($mods as $mod) {
		$allmodules[] = array('id'   => 'M-'.$mod['name'],
							  'name' => $mod['name']);
    }
    $pnRender->assign('defaultmodulevalue', 'none');
    $pnRender->assign('modules', $allmodules);
    unset($mod);
    //unset($elencomoduli);

    // select blocks for template
    $allblocks = array();
    pnModAPILoad('Xanthia', 'admin');
    $mods = pnModAPIFunc('Xanthia', 'admin', 'listblocks');
    foreach($mods as $mod) {
		$blocktitle = strtolower(strip_tags($mod['title']));
		$blocktitle = ereg_replace("[^0-9a-z_]","",$blocktitle);
	
		$allblocks[] = array('id'   => $blocktitle,
								'name' => $mod['title']);
    }
    $pnRender->assign('defaultblockvalue', 'none');
    $pnRender->assign('blocks', $allblocks);
    unset($mod);
    //unset($elencomoduli);

	// select all zones
	$zones = pnModGetVar('Xanthia', $skinName.'newzone');
	$zonesarray = split('[|]', $zones);
	foreach($zonesarray as $zone) {
		if (empty($zone)) { continue; }
		$ourzone = split(':', $zone);
		$allzones[] = array('id' => strtolower($ourzone[2]) .'sblock',
		                     'name' => $ourzone[1]);
	}
    $pnRender->assign('zones', $allzones);

    return $pnRender->fetch('xanthiaadminnewtzone.htm');
}

/**
 * Create new zones
 * @access		private
 * @author		mh7
 * @since		0.925
 * @param		int		$skin		ID for the skin
 * @param		string	$zone		zone name we are creating
 * @param		string	$label		long title of the zone
 * @return		bool				true upon successful update
 *									redirect to xanthia_admin_view()
 *									ID of the updated skin
 */
function xanthia_admin_createZones()
{
	// check the session auth key
	if (!pnSecConfirmAuthKey())	{
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
		pnRedirect(pnModURL('Xanthia', 'admin', 'main'));
		return true;
	}

	// grab our parameters in a secure manner
	list($skin, $zone, $label) = pnVarCleanFromInput('skin', 'zone', 'label');

	// check for our parameters
	if (empty($skin)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
		return false;
	}

	// load admin API
	if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	$settype = substr($label, 0,2);
	$settype2 = substr($label, 0,1);
	if ($settype == 'M-' || $settype2 == '*'){
		$type = 'module';
	}else{
		$type = 'block';
	}   

	// Create the new zone
	if (pnModAPIFunc('Xanthia', 'admin', 'createZones',
							array('skin' => $skin,
								  'zone' => $zone,
								  'label' => $label,
								  'type'  => $type))) {
		// Success
		pnSessionSetVar('statusmsg', _XA_ZONECREATED);
	}

	// Work completed, return to main
	pnRedirect(pnModURL('Xanthia', 'admin', 'editTheme',
	array('skin' => $skin)));
	return true;
}

/**
 * Delete zones
 * @access		private
 * @author		mh7
 * @since		0.925
 * @param		int		$skin		ID for the skin
 * @param		string	$zone		zone name we are creating
 * @param		string	$label		long title of the zone
 * @return		bool				true upon successful update
 *									redirect to xanthia_admin_view()
 *									ID of the updated skin
 */
function xanthia_admin_deleteZones()
{
	// check the session auth key
	if (!pnSecConfirmAuthKey())	{
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
		pnRedirect(pnModURL('Xanthia', 'admin', 'main'));
		return true;
	}

	// grab our parameters in a secure manner
	list($skin, $zone) = pnVarCleanFromInput('skin', 'zone');

	// check for our parameters
	if (empty($skin)) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_ARGSERROR));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
		return false;
	}

	// load admin API
	if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	// Delete the zone
	// FIXED: <apathy>		changed function to deleteZones so this would work
	if (pnModAPIFunc('Xanthia', 'admin', 'deleteZones',
							array('skin' => $skin,
								  'zone' => $zone))) {
		// Success
		pnSessionSetVar('statusmsg', _XA_ZONEDELETED);
		
		$skinName = pnModAPIFunc('Xanthia','user','getSkinFromID', array('id' => $skin));
		$cachedthemes = pnModGetVar('Xanthia',''.$skinName.'themecache');
		
		if (isset($cachedthemes)){
			pnModAPIFunc('Xanthia', 'admin', 'writethemetplcache', array('skinid' => $skin));
		}
		
	}

	// Work completed, return to main
	pnRedirect(pnModURL('Xanthia', 'admin', 'editTheme', array('skin' => $skin)));
	return true;
}

/**
 * @access		private
 */
function xanthia_admin_editTheme($args)
{
    
	/** Security check - important to do this as early as possible to avoid
	 * potential security holes or just too much wasted processing.  For the
	 * main function we want to check that the user has at least edit privilege
	 * for some item within this component, or else they won't be able to do
	 * anything and so we refuse access altogether.  The lowest level of access
	 * for administration depends on the particular module, but it is generally
	 * either 'edit' or 'delete'
	 */
	if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

	// Extract our parameters
	extract ($args);
    list($skin, $todo, $authid) = pnVarCleanFromInput('skin','todo','authid');
	// Load user API
	if (!pnModAPILoad('Xanthia','user')) {	    
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	// Load admin API
	if (!pnModAPILoad('Xanthia', 'admin'))	{
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	$authid = pnSecGenAuthKey();
    $options = array();
    
	if (is_numeric($skin)) {
	    $skinID = pnVarCleanFromInput('skin');			
	} else {
		$skinID = pnModAPIFunc('Xanthia','user','getSkinID',
							array('skin' => $skin));
	}
	$skin= pnModAPIFunc('Xanthia','user','getSkinFromID',
								array('id' => $skinID));

	switch ($todo) {
		case 'colors':

			$colorsid = pnModAPIFunc('Xanthia', 'admin', 'listPaletteID',
									array('skinid' => $skinID,
										   'blank' => 1));
            $rows = array();
            if ($colorsid) {
				foreach ($colorsid as $colors) {
					$paletteid = $colors['palette_id'];
					$selected = pnModGetVar('Xanthia',$skin.'use');
						
					$colors = pnModAPIFunc('Xanthia', 'admin', 'listColors',
							array('skinid' => $skinID,
							'paletteid' => $colors['palette_id']));
									
					foreach ($colors as $color) {
						if ($selected == $paletteid) {
							$display[] =  array('url' => '1',
												'label' => _XA_CURRENTPALETTE);
						} else {
							$display[] = array('url' => pnModURL('Xanthia','admin','setPalette',
													  array('skin' => $skinID,
															'paletteid' => $paletteid,
															'skinName' => $skin,
															'authid' => $authid)),
															'label' => _XA_USEPALETTE);
						}
	
						$actions[] = array('url' => pnModURL('Xanthia','admin','modifyColors',
													   array('skin' => $skinID,
															 'paletteid' => $paletteid)),
															 'label' => _XA_CONFIGURE);
	
						// Setup the array for display
						$rows[] = array('palname'     => $color['palette_name'],
									 'bgcolor'     => $color['background'],
									 'color1'      => $color['color1'],
									 'color2'      => $color['color2'],
									 'color3'      => $color['color3'],
									 'color4'      => $color['color4'],
									 'color5'      => $color['color5'],
									 'color6'      => $color['color6'],
									 'color7'      => $color['color7'],
									 'color8'      => $color['color8'],
									 'sepcolor'    => $color['sepcolor'],
									 'text1'       => $color['text1'],
									 'text2'       => $color['text2'],
									 'link'        => $color['link'],
									 'vlink'       => $color['vlink'],
									 'hover'       => $color['hover'],
									 'actions'     => $actions,
									 'display'     => $display);
						$actions ='';
						$display ='';
					}
				}
			}
										   
			// Create output object - this object will store all of our output so that
			// we can return it easily when required
			$pnRender =& new pnRender('Xanthia');
		
			// As Admin output changes often, we do not want caching.
			$pnRender->caching = false;

			$pnRender->assign('menu', pnModFunc('Xanthia','admin','thememenu'));
			$pnRender->assign('skin', $skin);
											   
			$pnRender->assign('skinid', $skinID);
			$pnRender->assign('authid', $authid);
			$pnRender->assign('items', $rows);
			return $pnRender->fetch('xanthiaadmincolors.htm');
			exit;  
			break;
		 
		case 'config':
			$configs = pnModAPIFunc('Xanthia', 'admin', 'listConfig',
									   array('skin' => $skinID));
									   
			foreach ($configs as $config){
				$options[] = array('name' => $config['name'],
								   'description' => $config['description'],
								   'setting' => $config['setting']);
			}
				
			// Create output object - this object will store all of our output so that
			// we can return it easily when required
			$pnRender =& new pnRender('Xanthia');
		
			// As Admin output changes often, we do not want caching.
			$pnRender->caching = false;

			$pnRender->assign('menu', pnModFunc('Xanthia','admin','thememenu'));
			$pnRender->assign('skinid', $skinID);
			$pnRender->assign('skin', $skin);
			$pnRender->assign('themeconfig', $options);
			return $pnRender->fetch('xanthiaadminthemeconfig.htm');
			exit;  
			break;

		default:
			$zones = pnModAPIFunc('Xanthia','admin','listZones',
									array('skin' => $skinID));
			if ($zones == false) {
				pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_COMPASSNOZONES));
				pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
				return true;
			}

			foreach ($zones as $zone) {
				// Set Zone type for display
				if ($zone['type'] == 0) {
					$type = _XA_REQUIRED;
				} else {
					$type = _XA_OPTIONAL;
				}

				// Set Zone state for display
				if ($zone['active'] == 0) {
					$active = _NO;
				} else {
					$active = _YES;
				}

				// @var array $template | find template file for the zone
				$template = pnModAPIFunc('Xanthia','admin','listTpl',
												array('skin' => $skinID,
													  'zone' => $zone['label']));

				// Check results
				if (empty($template)) {
					$template = _XA_TPLNOTSET;
				}

				// @var array actions | list of available configuration options
				$actions = array();

				// determine zone type (0=required; 1=addon)
				if (!$zone['type'] == 0) {

					// Determine Zone State
					switch ($zone['active']) {
						// Currently Inactive
						case 0:
							$actions[] = array('url' => pnModURL('Xanthia', 'admin', 'modifyZones',
											array('task' => 'activate',
												  'skin' => $skinID,
												  'zone' => $zone['label'],
												  'authid' => $authid)),
												  'label' => _XA_ACTIVATE);
							break;
		
						// Currently Active
						case 1:
							$actions[] = array('url' => pnModURL('Xanthia', 'admin', 'modifyZones',
											array('task' => 'deactivate',
												  'skin' => $skinID,
												  'zone' => $zone['label'],
												  'authid' => $authid)),
												  'label' => _XA_DEACTIVATE);
							break;
					}
		
					// Add a "delete" option for Addons
					$actions[] = array('url' => pnModURL('Xanthia', 'admin', 'deleteZones',
									array('task' => 'deleteZones',
										  'skin' => $skinID,
										  'zone' => $zone['label'],
										  'authid' => $authid)),
										  'label' => _XA_DELETE);
				}

				// Add a "configure' option for all Zones
				$actions[] = array('url' => pnModURL('Xanthia', 'admin', 'modifyZones',
								array('task' => 'configure',
									  'skin' => $skinID,
									  'zone' => $zone['label'],
									  'skintype' => $zone['skin_type'])),
									  'label' => _XA_CONFIGURE);
	
				//$actions = join(' | ', $actions[]);
					
				$rows[] = array('name'      => pnVarPrepForDisplay($zone['name']),
								'zonelabel' => pnVarPrepForDisplay($zone['label']), 
								'type' => $type,
								'active' => $active,
								'template' => $template,
								'actions' => $actions);
	
			}

			// Create output object - this object will store all of our output so that
			// we can return it easily when required
			$pnRender =& new pnRender('Xanthia');
		
			// As Admin output changes often, we do not want caching.
			$pnRender->caching = false;
	
			$pnRender->assign('menu', pnModFunc('Xanthia','admin','thememenu'));
			$pnRender->assign('skin', $skin);
            $pnRender->assign('skinid', $skinID);
			$pnRender->assign('items', $rows);
			return $pnRender->fetch('xanthiaadminthemezones.htm');
			exit;  
			break;
	}
}

/**
 * Add a new Theme
 * @access		private
 * @author		apathy
 * @author		mh7
 * @since		0.926
 * @return		mixed	$output		the page output
 */
function xanthia_admin_addTheme()
{

	// Check Permissions
	if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
	}

	// Load user API
	if (!pnModAPILoad('Xanthia','user')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	// Load admin API
	if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	$id = pnVarCleanFromInput('skin');
	$returninfo = pnModAPIFunc('Xanthia','admin','insertNewXanthiaTheme',
									array('id' => $id));

	if ($returninfo) {
		pnSessionSetVar('statusmsg', _XA_THEMEADDED);
		pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
		return true;
	} else {
		pnSessionSetVar('statusmsg', 'Theme Not Added');
		pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
		return true;
	}

}

/**
 * Removed Theme Function
 * @access		private
 * @author		apathy
 * @author		mh7
 * @since		0.926
 * @return		mixed	$output		the page output
 */
function xanthia_admin_removeTheme()
{

	// Check Permissions
	if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
	}

	// Load user API
	if (!pnModAPILoad('Xanthia','user')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	// Load admin API
	if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	$id = pnVarCleanFromInput('skin');
	$returninfo = pnModAPIFunc('Xanthia','admin','deleteXanthiaTheme',
	array('id' => $id));

    if ($returninfo == 'ERR:1') {
		pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_XA_THEMEREMOVEFAILURE));
		pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
		return true;
	} else {
		pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_XA_THEMEREMOVED));
		pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
		return true;
	}
}

/**
 * @access		private
 * @author		zx
 */
function xanthia_admin_nuovezone($args)
{

	// Check Permissions
	if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
	}

	extract($args);
	list($id, $desc, $tag, $skinID)=pnVarCleanFromInput('id','desc','tag','skinID');
	
//	if (!pnModAPILoad('Xanthia','admin')) {
//       pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
//        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
//        return true;
//	}

	// remove the standard delmiters from the tag (in case the user misentered the value)
	$tag = str_replace('<!--[', '', $tag);
	$tag = str_replace(']-->', '', $tag);

    // Load user API
	if (!pnModAPILoad('Xanthia','user')) {	    
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	
	$skinName = pnModAPIFunc('Xanthia','user','getSkinFromID',
							array('id' => $skinID));

	$stringa = '';								
    $stringa = pnModGetVar('Xanthia', $skinName.'newzone');

    if ($desc ==''){$desc='zone_'.$tag;}
    $tag=strtoupper(preg_replace("/[ -']/i",'_', $tag));

    if ($tag != '') {
		$stringa .= "|".$id.":".$desc.":".$tag;
		pnModSetVar('Xanthia', $skinName.'newzone', $stringa);
		pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_XA_NZOKADD));
    }
	//$output->Linebreak(2);
    //$output->URL(pnModURL('Xanthia','admin','view'), _XA_NZUPDATE);
	pnRedirect(pnModURL('Xanthia','admin','BlockZones',
											array('skin' => $skinName)));

}

/**
 * @access		private
 * @author		zx
 */
function xanthia_admin_rimuovinuovezone($args)
{

	// Check Permissions
	if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
	}

	extract($args);
	
	list($riga)=pnVarCleanFromInput('riga');
	
    $dati=split('[:]', $riga);
    
	$skinID=pnVarCleanFromInput('skinID');
	
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	$column = &$pntable['theme_blcontrol_column'];
	
	$sql="SELECT $column[module] as module, 
			   $column[block] as block, 
			   $column[position] as position 
					FROM  $pntable[theme_blcontrol]
					WHERE $column[position]='".pnVarPrepForStore($dati[0])."' 
					ORDER BY $column[module]";
	
	$result =& $dbconn->Execute($sql);
	if(!$result->EOF) {
		// Create output object - this object will store all of our output so that
		// we can return it easily when required
		$pnRender =& new pnRender('Xanthia');
	
		// As Admin output changes often, we do not want caching.
		$pnRender->caching = false;

		$pnRender->assign('menu', pnModFunc('Xanthia','admin','thememenu'));               
		$pnRender->assign('warn', _XA_NZWARNING);
		$pnRender->assign('columnheaders', array(pnVarPrepForDisplay(_XA_MODULE),
										  pnVarPrepForDisplay(_XA_BLOCK)));
		while(!$result->EOF) {
			$row = $result->GetRowAssoc(false);
			$inuse[] = array('module' => $row['module'],
						  'block' => $row['block']);
			$result->MoveNext();
		}
		$pnRender->assign('inuse', $inuse);
		return $pnRender->fetch('xanthiaadminzoneused.htm');
	}

	// Load admin API
//	if (!pnModAPILoad('Xanthia', 'admin')) {
//        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
//        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
//        return true;
//	}
	
    // Load user API
	if (!pnModAPILoad('Xanthia','user')) {	    
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	
	$skinName = pnModAPIFunc('Xanthia','user','getSkinFromID',
								array('id' => $skinID));
												
	$stringa = pnModGetVar('Xanthia', $skinName.'newzone');
	$stringasenza = preg_replace("/\|$riga:[a-z0-9A-Z _'\-]*:[a-z0-9A-Z _'\-]*/i",'', $stringa);
	
	pnModSetVar('Xanthia', $skinName.'newzone', $stringasenza);
	
	pnRedirect(pnModURL('Xanthia','admin','BlockZones',
						 array('skin' => $skinName)));
}

/**
 * @access		private
 */
function xanthia_admin_editTemplates($args)
{
	// Check Permissions
	if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
	}

	// Extract our parameters
	extract ($args);

	// Load user API
	if (!pnModAPILoad('Xanthia','user')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	// Load admin API
	if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	$skin = pnVarCleanFromInput('skin');
    $skinID = pnModAPIFunc('Xanthia','user','getSkinID',
							array('skin' => $skin));							
					
	$authid = pnSecGenAuthKey();

    $templates = pnModAPIFunc('Xanthia','admin','getTplTypeDB',
							  array('skin' => $skin,
									'zone' => 1));
	$themesrows = array();
	$modulesrows = array();
	$blocksrows = array();
	if (is_array($templates)) {
		foreach ($templates as $template) {
			if($template['type'] == 'theme') {
				$themetpl[] = array('url' => pnModURL('Xanthia','admin','editTemplate',
									  array('tpl' => $template['tpl_file'],
											'skin' => $skinID)),
									'label' => $template['tpl_file']);
									
				if(file_exists("themes/$skin/templates/".$template['tpl_file'])){
					$themereload[] = array('url' => pnModURL('Xanthia','admin','reloadTemplate',
										  array('tpl' => $template['tpl_file'],
												'skin' => $skinID,
												'authid' => $authid)));
/*				} else {
					$themereload[] = array('url' => pnModURL('Xanthia','admin','downloadTemplate',
										  array('tpl' => $template['tpl_file'],
												'skin' => $skinID,
												'authid' => $authid)),
												'DL' => 'DL');*/
				}
									
			} elseif($template['type'] == 'module') {
				$moduletpl[] = array('url' => pnModURL('Xanthia','admin','editTemplate',
									   array('tpl' => $template['tpl_file'],
											 'skin' => $skinID)),
											 'type' => $template['tpl_module'],
									 'label' => $template['tpl_file']);
									 
				if(file_exists("themes/$skin/templates/modules/".$template['tpl_file'])){
					$modulereload[] = array('url' => pnModURL('Xanthia','admin','reloadTemplate',
										  array('tpl' => $template['tpl_file'],
												'skin' => $skinID,
												'tpltype' => 'module',
												'authid' => $authid)));
/*				} else {
					$modulereload[] = array('url' => pnModURL('Xanthia','admin','downloadTemplate',
										  array('tpl' => $template['tpl_file'],
												'skin' => $skinID,
												'authid' => $authid)),
												'DL' => 'DL');*/
				} 
			} elseif ($template['type'] == 'block'){
				$blocktpl[]  = array('url' => pnModURL('Xanthia','admin','editTemplate',
									   array('tpl' => $template['tpl_file'],
											 'skin' => $skinID)),
											 'type' => $template['tpl_module'],
									 		 'label' => $template['tpl_file']);
									 
				if(file_exists("themes/$skin/templates/blocks/".$template['tpl_file'])){
					$blockreload[] = array('url' => pnModURL('Xanthia','admin','reloadTemplate',
										  array('tpl' => $template['tpl_file'],
												'skin' => $skinID,
												'tpltype' => 'block',
												'authid' => $authid)));
/*				} else {
					$blockreload[] = array('url' => pnModURL('Xanthia','admin','downloadTemplate',
										  array('tpl' => $template['tpl_file'],
												'skin' => $skinID,
												'authid' => $authid)),
												'DL' => 'DL');*/
				} 
			} else {}
	
			if(empty($themetpl)){
				$themetpl = array();
			}
			if(empty($moduletpl)){
				$moduletpl = array();
			}
			if(empty($blocktpl)){
				$blocktpl = array();
			}  
			if(empty($themereload)){
				$themereload = array();
			}  		
			if(empty($modulereload)){
				$modulereload = array();
			}  
			if(empty($blockreload)){
				$blockreload = array();
			}	
				
			$themesrows[] = array('themetpl'	       => $themetpl,
								  'themereload'	   => $themereload);
								  
			unset($themetpl);
			unset($themereload);
								  
			$modulesrows[] = array('moduletpl'	   => $moduletpl,
								   'modulereload'  => $modulereload);
								   
			unset($moduletpl);
			unset($modulereload);
								  
			$blocksrows[] = array('blocktpl'       => $blocktpl,
								  'blockreload'  => $blockreload);
								  
			unset($blocktpl);
			unset($blockreload);
		}		  
	} 
	
	$newtpl = array();

	// Create output object - this object will store all of our output so that
	// we can return it easily when required
	$pnRender =& new pnRender('Xanthia');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

	$pnRender->assign('menu', pnModFunc('Xanthia','admin','thememenu'));							
	$pnRender->assign('skin', $skin);
	$pnRender->assign('skinid', $skinID);
    $pnRender->assign('authid', $authid);
	$pnRender->assign('newtpl', $newtpl);                  
	$pnRender->assign('themes', $themesrows);
	$pnRender->assign('modules', $modulesrows);
	$pnRender->assign('blocks', $blocksrows);	           

    return $pnRender->fetch('xanthiaadmintemplates.htm');
}

/**
 * @access		private
 */
function xanthia_admin_editTemplate($args)
{
	// Check Permissions
	if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
	}

	// Extract our parameters
	//extract ($args);

	// Load user API
	if (!pnModAPILoad('Xanthia','user')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	// Load admin API
	if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	list($tpl,
	     $skin,
	     $authid) = pnVarCleanFromInput('tpl',
	                                    'skin',
	                                    'authid');
	                                    
	// Create output object - this object will store all of our output so that
	// we can return it easily when required
	$pnRender =& new pnRender('Xanthia');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

    $pnRender->assign('menu', pnModFunc('Xanthia','admin','thememenu'));

    $craker = pnConfigGetVar('pnAntiCracker');

 	$tpl_file = array();   
	$tpl_file = pnModAPIFunc('Xanthia','admin','getEditTemplate',
							  array('tpl' => $tpl,
									'skinid' => $skin));
    $content = $tpl_file['source'];							
    $formoptions[] = array('hiddentpl_id' => $tpl_file['id'],
                           'hiddenskin_id' => $tpl_file['skin_id'],
                           'hiddentpl_file' => $tpl_file['file'],
                           'hiddenosource' => base64_encode($content),
                           'source' => $content,
                           'filename' => $tpl_file['file'],
                           'filetext' => 'File Name',
                           'modifiedtext' => 'Last modified',
                           'modified' => $tpl_file['timestamp'],
                           'craker' => $craker);
                        
	$pnRender->assign('formcontent', $formoptions);                           
	return $pnRender->fetch('xanthiaadmintplform.htm');
}

/**
 * @access		private
 */
function xanthia_admin_newThemeTpl($args)
{
	// Check Permissions
    if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
	}

	// Extract our parameters
	extract ($args);

	// Load user API
	if (!pnModAPILoad('Xanthia','user')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	// Load admin API
//	if (!pnModAPILoad('Xanthia', 'admin')) {
//        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
//       pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
//       return true;
//	}

	list($tpl,
	     $skin,
	     $authid,
	     $ttype) = pnVarCleanFromInput('tpl',
	                                    'skin',
	                                    'authid',
	                                    'ttype');
	                                    
	$skinName = pnModAPIFunc('Xanthia','user','getSkinFromID',
										array('id' => $skin));
										                                    
	// Create output object - this object will store all of our output so that
	// we can return it easily when required
	$pnRender =& new pnRender('Xanthia');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

    $pnRender->assign('menu', pnModFunc('Xanthia','admin','thememenu'));
    $pnRender->assign('skin', $skinName);
						
    $formoptions[] = array('skinid' => $skin,
                           'tpl_module' => $skinName,
                           'themename' => $skinName,
                           'newtype' => $ttype);
                        
	$pnRender->assign('formcontent', $formoptions);                           
	return $pnRender->fetch('xanthiaadminnewtplform.htm');
}

/**
 * @access		private
 * @todo this function doesn't seem to be called from anywhere - markwest
 */
function xanthia_admin_newModBlockTpl($args) {


	// Check Permissions
	if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
	}

	// Extract our parameters
	extract ($args);

	// Load user API
	if (!pnModAPILoad('Xanthia','user')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	// Load admin API
	if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	list($tpl,
	     $skin,
	     $authid,
	     $ttype) = pnVarCleanFromInput('tpl',
	                                    'skin',
	                                    'authid',
	                                    'ttype');
	                                    
	$skinName = pnModAPIFunc('Xanthia','user','getSkinFromID',
										array('id' => $skin));
										                                    
	// Create output object - this object will store all of our output so that
	// we can return it easily when required
	$pnRender =& new pnRender('Xanthia');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

	if ($ttype == 'theme'){
		$tpl_module = $skinName;
		$allblocks ='';
	} elseif ($ttype == 'module') {
		$tpl_module = '';
		$allblocks = array();
		pnModAPILoad('Modules', 'admin');
		$mods = pnModAPIFunc('Modules', 'admin', 'list');
		foreach($mods as $mod) {
			//echo $mod['name'];
			$allblocks[] = array('id'   => $mod['name'],
								 'name' => $mod['name']);
		}
		//$pnRender->assign('allzones', $allblocks);
            
	} else {
		$tpl_module = '';
		$allblocks = array();
		$mods = pnModAPIFunc('Xanthia', 'admin', 'listblocks');
		foreach($mods as $mod) {
			$allblocks[] = array('id'   => $mod['title'],
								 'name' => $mod['title']);
		}
		//$pnRender->assign('allzones', $allblocks);
	}    			
                                    
    $pnRender->assign('menu', pnModFunc('Xanthia','admin','thememenu'));
    $pnRender->assign('skin', $skinName);
						
    $formoptions[] = array('skinid' => $skin,
                           'tpl_module' => $tpl_module,
                           'themename' => $skinName,
                           'newtype' => $ttype,
                           'allzones' => $allblocks                          
                           );
                        
	$pnRender->assign('formcontent', $formoptions);                           
	return $pnRender->fetch('xanthiaadminnewtplform.htm');
}

/**
 * function to copy selected theme to a new directory for modification from the browser
 * @access		private
 * @author      PhpNut nut@phpnut.com
 */
function xanthia_admin_createTheme()
{
	// Create output object - this object will store all of our output so that
	// we can return it easily when required
	$pnRender =& new pnRender('Xanthia');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

	// Check Permissions
	if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
	}

	// Load user API
	if (!pnModAPILoad('Xanthia','user')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	// Load admin API
	if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	// Render the output
	return $pnRender->fetch('xanthiaadmincreatetheme.htm');
}

/**
 * @access		private
 */
function xanthia_admin_updateMain($args)
{
	// Check Permissions
	if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
	}
	
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        pnRedirect(pnModURL('Xanthia', 'admin', ''));
        return true;
    } 
    
	extract($args);
	list($vba,
	     $shorturls,
		 $shorturlsextension, 
	     $enablecache,
		 $modulesnocache, 
	     $db_cache, 
	     $db_compile, 
	     $compile_check,
	     $cache_lifetime,
	     $use_db,
	     $db_templates,
		 $force_compile,
		 $trimwhitespace)=pnVarCleanFromInput('vba',
	                                        'shorturls',
											'shorturlsextension',
	                                        'enablecache',
											'modulesnocache',
	                                        'db_cache',
	                                        'db_compile',
	                                        'compile_check',
	                                        'cache_lifetime',
	                                        'use_db',
	                                        'db_templates',
											'force_compile',
											'trimwhitespace');


    pnModSetVar('Xanthia', 'vba', $vba);
    pnModSetVar('Xanthia', 'shorturls', $shorturls);
	pnModSetVar('Xanthia', 'shorturlsextension', $shorturlsextension);
	pnModSetVar('Xanthia', 'modulesnocache', $modulesnocache);
    pnModSetVar('Xanthia', 'enablecache', $enablecache);
    pnModSetVar('Xanthia', 'db_cache', $db_cache);
    pnModSetVar('Xanthia', 'db_compile', $db_compile);
    pnModSetVar('Xanthia', 'compile_check', $compile_check);
    pnModSetVar('Xanthia', 'cache_lifetime', $cache_lifetime);
    pnModSetVar('Xanthia', 'use_db', $use_db);
    pnModSetVar('Xanthia', 'db_templates', $db_templates);
	pnModSetVar('Xanthia', 'force_compile', $force_compile);
    pnModSetVar('Xanthia', 'trimwhitespace', $trimwhitespace);

    pnRedirect(pnModURL('Xanthia', 'admin', 'main'));
    return true;

}

/**
 * @access		private
 */
function xanthia_admin_setInner($args)
{ 
    // Get parameters
    $innerblock = pnVarCleanFromInput('innerblock'); 
    // Confirm authorisation code
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        pnRedirect(pnModURL('Xanthia', 'admin', ''));
        return true;
    } 
    // Load in API
    pnModAPILoad('Xanthia', 'admin'); 
    // Pass to API
    if (pnModAPIFunc('Xanthia', 'admin', 'updateInner', array('id' => $innerblock))) {
        // Success
        pnSessionSetVar('statusmsg', _XA_INNERUPDATED);
    } 
    // Redirect
    pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
    return true;
}

/**
 * @access		private
 * @author      ZXvision
 * @author      TiMax
 * @author      Cino             
 */
function xanthia_admin_blockControl($args)
{
    
	extract($args);
	
	list($zone,
		 $mod,
		 $chichiama) = pnVarCleanFromInput('zone','mod','chichiama');

	// Create output object - this object will store all of our output so that
	// we can return it easily when required
	$pnRender =& new pnRender('Xanthia');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;
     
    $pnRender->assign('zone', $zone);
    $pnRender->assign('chichiama', base64_decode($chichiama));
	$pnRender->assign('blocks', xanthia_admin_directoryblocks($mod,$zone,$chichiama));
    echo $pnRender->fetch('xanthiaadminblockcontrol.htm');

	exit;
}

/**
 * @access		private
 * @author      ZXvision
 * @author      TiMax
 * @author      Cino             
 */
function xanthia_admin_directoryblocks($mod,$lato,$chichiama)
{
     
	// Create output object - this object will store all of our output so that
	// we can return it easily when required
	$pnRender =& new pnRender('Xanthia');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

	// load the blocks tables
	pnModDBInfoLoad('Blocks');
     
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	
	$pnRender->assign('position', $lato);
	$pnRender->assign('bmodule', $mod);

	//  PER MOSTRARE I BLOCCHI
	$blockstable = $pntable['blocks'];
	$blockscolumn = &$pntable['blocks_column'];
	
	$where = "WHERE $blockscolumn[active] = 1";
	
	$sql = "SELECT $blockscolumn[bid],
	               $blockscolumn[title],
				 $blockscolumn[active],
				 $blockscolumn[position]
				 FROM $blockstable
				 ORDER BY $blockscolumn[title]";
	
	$result =& $dbconn->Execute($sql);
	
	$blocks = array();
	while(!$result->EOF) {
		list($bid, $title, $active, $position) = $result->fields;
		
		$blocks[] = array('title' => $bid,
		                  'title1' => "$title [$active] [$position]");
		$result->movenext();
	}
	$blocks[] = array('title' => '-1',
	                  'title1' => '==Block Control');
    $pnRender->assign('blocks', $blocks);                              
	
	return $pnRender->fetch('xanthiaadmindirectoryblocks.htm');
}

/**
 * @access		private
 * @author      ZXvision
 * @author      TiMax
 * @author      Cino             
 */
function xanthia_admin_blockControlRule()
{
    if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_ADD)) {
		return _MODULENOAUTH;
	}
	pnModDBInfoLoad('Blocks');        
	// Setup the DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	list($module,
		 $bmodule,
		 $block,
		 $identi,    
		 $position,
		 $weight,
		 $refresh,
		 $skin,
		 $filtro,
		 $set,
		 $remove) = pnVarCleanFromInput('module',
										'bmodule',
										'block',
										'identi',
										'position',
										'weight',
										'refresh',
										'skin',
										'filtro',
										'set',
										'remove');
                                            
	if(empty($skin)){
	   $skin = pnUserGetTheme();
	}                                            
	
	// Load admin API
	if (!pnModAPILoad('Xanthia', 'admin')) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
		pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
		return true;
	}   
		                                         
	if (!pnModAPILoad('Xanthia', 'user')) {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAIL));
		return false;
	}	
											
	if(!pnModAPIFunc('Xanthia','admin','isBcSet',
		array('module' => $bmodule,
			  'skin' => $skin ))){
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay('Could not Set Block Control'));
		pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
		return true;
	}
											   
	//if(empty($position) && ){
	//	$position = 'l';
	//}
	if($bmodule == 'SetAll'){
		$mods = pnModGetAllMods();
		foreach($mods as $mod) {
			$lim=count($block);
			for($i=0; $i<$lim; $i++){
				// Setup the table and column nfo
				if ($block[$i] == '-1'){  //logic plus sale sempre su
					$position="0";
					$weight=0.0;
				} else {
					$blockscolumn = &$pntable['blocks_column'];
					$sql = "SELECT $blockscolumn[position],
								   $blockscolumn[weight]
						   FROM $pntable[blocks] WHERE $blockscolumn[bid] = '".pnVarPrepForStore($block[$i])."'";
					$result2 =& $dbconn->Execute($sql);
					if ($result2->EOF) {
						$position="l";
					}
	
					while(!$result2->EOF){
						list($position, $weight) = $result2->fields;
						$result2->MoveNext();
					}
					$result2->Close();				
				}
				$blocktitle = strtolower(strip_tags($block[$i]));
				$blocktitle = ereg_replace("[^0-9a-z_]","",$blocktitle);
		
				$table = $pntable['theme_blcontrol'];
				$column = &$pntable['theme_blcontrol_column'];
	
				if($remove == 'yes'){
					$sql= "DELETE FROM $table 
							WHERE $column[module] = '".pnVarPrepForStore($mod['name'])."' 
							AND $column[block] = '".pnVarPrepForStore($block[$i])."'
							AND $column[theme] = '".pnVarPrepForStore($skin)."'";
				} else {
					$sql = "INSERT INTO $table ($column[module],
												$column[block],
												$column[theme],
												$column[identi],
												$column[position],
												$column[weight]) 
							VALUES ('".pnVarPrepForStore($mod['name'])."',
									'".pnVarPrepForStore($block[$i])."',
									'".pnVarPrepForStore($skin)."',
									'".pnVarPrepForStore($blocktitle)."',
									'".pnVarPrepForStore($position)."',
									'".pnVarPrepForStore($weight)."')";
				}
				$result =& $dbconn->Execute($sql);  
			}
		}
		$skinid = pnModAPIFunc('Xanthia','user','getSkinID', array('skin' => $skin));
		if (!pnModAPIFunc('Xanthia', 'admin', 'writezonescache', array('skinid' => $skinid, 'modules' => $mods))) {
			//$return = false;
		}
	} else {
		$lim=count($block);
		for($i=0; $i<$lim; $i++){
			// Setup the table and column nfo
			if ($block[$i] == '-1'){  //logic plus sale sempre su
				$position="0";
				$weight=0.0;
			} else {
				if(!empty($set)){
					$blockscolumn = &$pntable['blocks_column'];
					$sql = "SELECT $blockscolumn[position],
								$blockscolumn[weight]
							FROM $pntable[blocks] WHERE $blockscolumn[bid] = '".pnVarPrepForStore($block[$i])."'";
					$result2 =& $dbconn->Execute($sql);
					if ($result2->EOF) {
						$position="l";
					}
					while(!$result2->EOF){
						list($position, $weight) = $result2->fields;
				        $result2->MoveNext();
				    }
				    $result2->Close();				
				}
			}
			$blocktitle = strtolower(strip_tags($block[$i]));
			$blocktitle = ereg_replace("[^0-9a-z_]","",$blocktitle);
	
			$table = $pntable['theme_blcontrol'];
			$column = &$pntable['theme_blcontrol_column'];
			if($remove == 'yes'){
		        $sql= "DELETE FROM $table 
                       WHERE $column[module] = '".pnVarPrepForStore($bmodule)."' 
                       AND $column[block] = '".pnVarPrepForStore($block[$i])."'
                       AND $column[theme] = '".pnVarPrepForStore($skin)."'";
			} else {									
				// No such label, build the update statement
				$sql = "INSERT INTO $table ($column[module],
											$column[block],
											$column[theme],
											$column[identi],
											$column[position],
											$column[weight]) 
				VALUES ('".pnVarPrepForStore($bmodule)."',
						'".pnVarPrepForStore($block[$i])."',
						'".pnVarPrepForStore($skin)."',
						'".pnVarPrepForStore($blocktitle)."',
						'".pnVarPrepForStore($position)."',
						'".pnVarPrepForStore($weight)."')";
			}
			$result =& $dbconn->Execute($sql);
			xanthia_admin_riordina(array('mod'  => $bmodule,
										 'bloc' => $block));
		}
		$skinid = pnModAPIFunc('Xanthia','user','getSkinID', array('skin' => $skin));
		if (!pnModAPIFunc('Xanthia', 'admin', 'writezonescache', array('skinid' => $skinid, 'modules' => pnVarPrepForStore($bmodule)))) {
			//$return = false;
		}
	}
								
	if ($refresh == "yes") {
		if(empty($filtro)){
			$filtro = substr($bmodule, 0,1);
		}  
		pnRedirect(pnModURL('Xanthia', 'admin', 'bcontrol',
					array('skin' =>$skin,
						  'filtro' => $filtro)));
	} else {
		sleep(1);
		pnRedirect(base64_decode($refresh));
		
		if (php_sapi_name() == "cgi") {
			header("Status: 204 No Content");
		}else{
			header("HTTP/1.0 204 No Content");
		}
    }
}

/**
 * @access		private
 * @author      ZXvision
 * @author      TiMax
 * @author      Cino             
 */
function xanthia_admin_riordina($args){
    
    if (pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)){
        extract($args);
        
        if(empty($skin)){
            $skin = pnUserGetTheme();
        }
                
        $dbconn =& pnDBGetConn(true);
        $pntable =& pnDBGetTables();
        
        $table = $pntable['theme_blcontrol'];
        $column = &$pntable['theme_blcontrol_column'];
        // Get the information
        
        $sql="SELECT $column[module],
                     $column[block],
                     $column[position],
                     $column[weight]
        FROM $table WHERE
                    $column[module] = '".pnVarPrepForStore(base64_decode($mod))."'
        AND $column[theme] = '".pnVarPrepForStore($skin)."' ORDER BY $column[position], $column[weight]";
        
        $result =& $dbconn->Execute($sql);
        
        $seq=1;
        $lastpos = '';
        
        while(!$result->EOF) {
            
            list($module, $block, $position, $curseq) = $result->fields;
            $result->MoveNext();
            
            if ($lastpos != $position) {
                $seq = 1;
            }
            
            $lastpos = $position;
            if ($curseq != $seq) {
                $query = "UPDATE $pntable[theme_blcontrol] SET 
                $column[weight]='" . pnVarPrepForStore($seq) . "' 
                WHERE $column[module] = '".pnVarPrepForStore($module)."' 
                AND $column[block] = '".pnVarPrepForStore($block)."' 
                AND $column[theme] = '".pnVarPrepForStore($skin)."'";
                
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
					pnSessionSetVar('errormsg', $dbconn->ErrorMsg());
					return false;
				}
            }
            $seq++;
        }
        $result->Close();
        
        return;
    }
}

/**
 * @access		private
 */
function xanthia_admin_setOnAll($args)
{

    list($skin,$refresh) = pnVarCleanFromInput('skin','refresh');
    
    if(empty($skin)){
        $skin = pnUserGetTheme();
    }
	// Check Permissions
	if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
	}

    // confirm auth key
	if (!pnSecConfirmAuthKey())	{
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
		return false;	
	}

    if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
    }    
  
    if (!pnModAPILoad('Xanthia', 'user')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
    }  
	
	//$skinid = pnModAPIFunc('Xanthia','user','getSkinID', array('skin' => $skin));

	if (!pnModAPIFunc('Xanthia', 'admin', 'setBcOnInstall', array('skin' => $skin))) {
		return false;
	} else {
	     pnRedirect(base64_decode($refresh));
	}
	
}

/**
 * @access		private
 * @author      ZXvision
 * @author      TiMax
 * @author      Cino             
 */
function xanthia_admin_blockControlAdmin($args){

	/** Security check - important to do this as early as possible to avoid
	* potential security holes or just too much wasted processing.  For the
	* main function we want to check that the user has at least edit privilege
	* for some item within this component, or else they won't be able to do
	* anything and so we refuse access altogether.  The lowest level of access
	* for administration depends on the particular module, but it is generally
	* either 'edit' or 'delete'
	*/
    if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    extract($args);
    
    list($zone,
         $bid,
         $mod,
         $chichiama) = pnVarCleanFromInput('zone','bid','mod','chichiama');
         

	$blocblocco=base64_encode($bid);


	$bloc = $blocblocco;
	$module = $mod;
	$mod=base64_encode($mod);
	
	if(empty($skin)){
	    $skin = pnUserGetTheme();
	} 
	
	// Create output object - this object will store all of our output so that
	// we can return it easily when required
	$pnRender =& new pnRender('Xanthia');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

	// Setup the DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	// Setup the table and column info
	$table = $pntable['theme_blcontrol'];
	$column = &$pntable['theme_blcontrol_column'];
	// Build the query
	$query = "SELECT $column[module],
	                 $column[position]
							FROM $table
							WHERE $column[block]='".pnVarPrepForStore($bid)."'
							AND $column[theme]='".pnVarPrepForStore($skin)."' AND $column[module] = '".pnVarPrepForStore($module)."'";
	// Execute the query
	$result =& $dbconn->Execute($query);
	// Determine if the zone label already exists
	if ($result->EOF) {
		$pnRender->assign('urlsetbc', pnModURL('Xanthia', 'admin', 'setOnAll', 
										 array('refresh' => $chichiama,
											   'skin' => $skin,
											   'authid' => pnSecGenAuthKey('Xanthia'))));
		echo $pnRender->fetch('xanthiaadminnoblockcontroladmin.htm');
		exit;
	}
	list($resarray['module'], $resarray['position']) = $result->fields;
	$result->Close(); 
    $pnRender->assign('urladm', pnModURL('Blocks', 'admin', 'modify', array('bid' => $bid)));
    $pnRender->assign('urlrem', pnModURL('Xanthia', 'admin', 'RemoveRule', 
						array('mod' => $mod, 'bloc' => $bloc, 'refresh' => $chichiama)));
	$pnRender->assign('uel', pnModURL('Xanthia', 'admin', 'IncRule', array('mod' => $mod, 'bloc' => $bloc, 'refresh' => $chichiama)));
	$pnRender->assign('uell', pnModURL('Xanthia', 'admin', 'DecRule', array('mod' => $mod, 'bloc' => $bloc, 'refresh' => $chichiama)));
	$pnRender->assign('bloc', base64_decode($bloc));
	$pnRender->assign('bid', $bid);
	$pnRender->assign('block', $row);
	$pnRender->assign('blockadminposition', xanthia_admin_position($mod,$bloc,$chichiama,$resarray['position']));
	$pnRender->assign('elencotemplate', templatebybloc($bloc,$mod,$chichiama));
	$pnRender->assign('chichiama', base64_decode($chichiama));

    echo $pnRender->fetch('xanthiaadminblockcontroladmin.htm');
    $result->Close();
	exit;
}


/**
 * @access		private
 * @author      ZXvision
 * @author      TiMax
 * @author      Cino             
 */
function xanthia_admin_position($mod,$bloc,$chichiama, $posizione){
    
    $skinName = pnUserGetTheme();
    $stringa="l:"._XA_LEFT.":|"."r:"._XA_RIGHT.":|"."c:"._XA_CENTER.":";
    $stringa .= pnModGetVar('Xanthia', $skinName.'newzone');
    $chunk=split('[|]', $stringa);
    
    $url=pngetbaseuri()."/index.php?module=Xanthia&amp;type=admin&amp;func=ChangePos&mod=".$mod.'&bloc='.$bloc.'&skin='.$skinName."&refresh=$chichiama&newpos=";
    $content  = '<select name="posizioni">';
    $lenarray=count($chunk);

    for($i=0; $i<$lenarray; $i++){
        $dati=split('[:]', $chunk[$i]);
        $k=$dati[0];
        $v=$dati[1];
        
        if ($posizione == $k) {$pos_cur=" selected"; }else{ $pos_cur="";}
        $content .= '<option value='.$url.$k.$pos_cur.'>'.$v."</option>\n";
    }
    $content .= "</select>\n";
    return $content;
}

/**
 * @access		private
 * @author      ZXvision
 * @author      TiMax
 * @author      Cino             
 */
function templatebybloc($blocco,$mod,$chichiama){
    
    $mod=base64_decode($mod);
    $blocco=base64_decode($blocco);
    
    $infoblock=pnBlockGetInfoByTitle($blocco);
	// Load admin API
	if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	if (!pnModAPILoad('Xanthia', 'user')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	$skinID = pnModAPIFunc('Xanthia','user','getSkinID',
								array('skin' => pnUserGetTheme()));
    $templates = pnModAPIFunc('Xanthia','admin','getThemeTplFile',
								array('skin' => $skinID,
									  'zone' => 1,
									  'type' => 'block'));
    
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    $column = &$pntable['theme_blcontrol_column'];
        
    $result =& $dbconn->Execute("SELECT $column[module] as module, 
                                       $column[block] as block,
                                       $column[position] as position, 
                                       $column[weight] as weight,
                                       $column[blocktemplate] as blocktpl 
                                                FROM $pntable[theme_blcontrol]
                                                WHERE $column[module]='".pnVarPrepForStore($mod)."'
                                                AND $column[theme]='".pnUserGetTheme()."'
                                                AND $column[block]='".pnVarPrepForStore($blocco)."'
                                                ORDER BY $column[position],
                                                $column[weight] ");
    $blocktpl= '';
    
    while(!$result->EOF) {
        $row = $result->GetRowAssoc(false);
        $modulo= $row['module'];
        $blocco= $row['block'];
        $posizione= $row['position'];
        $peso= $row['weight'];
        $blocktpl = preg_replace('/.htm/','',$row['blocktpl']);
        
        $result->MoveNext();
    }
    $result->Close();
    
    $content  = '<select name="template">';
    $url=pngetbaseuri()."/index.php?module=Xanthia&amp;type=admin&amp;func=blockControlTemplate&mod=".base64_encode($mod).'&bloc='.base64_encode($blocco)."&refresh=".($chichiama)."&template=".base64_encode('false');
	$content .= '<option value=\''.$url.'\'>' . _XA_ZONEDEFAULT . '</option>';
    foreach ($templates as $rowtemplate){
        
        $rowtemplate= preg_replace('/.htm/','',$rowtemplate);
        $selected='';
        
        if ($rowtemplate == $blocktpl) {$selected="SELECTED";}
        
        $url=pngetbaseuri()."/index.php?module=Xanthia&amp;type=admin&amp;func=blockControlTemplate&mod=".base64_encode($mod).'&bloc='.base64_encode($blocco)."&refresh=".($chichiama)."&template=".base64_encode($rowtemplate);
        $content .= '<option value='.$url." ".$selected.'>'.$rowtemplate.'</option>\n';
    }
    
    $content.='</select>';
    
    if ($rowtemplate != ''){
        return $content;
    }else{
        return '';
    }
}

/**
 * @access		private
 */
function getTplFiles_block($args){
    
    $tpl = array();
    
    // Open the template directory fo rthe skin
    $dir = @opendir('themes/'.pnUserGetTheme().'/templates/blockskins/');
    
    // Read through the directory
    
    while ($file = @readdir($dir)){
        
        // Validate the files are indeed templates
        // @todo        'prep-' templates are excluded from this list
        
        if ((substr($file, -3, 3) == 'htm') && (substr($file, 0, 5) != 'prep-')) {
            // Add valid files to the list
            $tpl[] = $file;
        }
    }
    
    // Close the template directory
    @closedir($dir);
    
    // Sort and return the template file names
    $tpl[]='';
    sort($tpl);
    return $tpl;
}

/**
 * @access		private
 * @author      ZXvision
 * @author      TiMax
 * @author      Cino             
 */
function xanthia_admin_IncRule($args){
    
    if (pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)){
        
        extract($args);
        // Setup the DB handle
        
        list($skin,$mod,$bloc,$refresh) = pnVarCleanFromInput('skin','mod','bloc','refresh');
        
        if(empty($skin)){
            $skin = pnUserGetTheme();
        }
        
        $dbconn =& pnDBGetConn(true);
        $pntable =& pnDBGetTables();
        $table = $pntable['theme_blcontrol'];
        $column = &$pntable['theme_blcontrol_column'];

        $sql= "UPDATE $table SET 
                      $column[weight]=$column[weight]-'1.5'
                       WHERE $column[module] = '".pnVarPrepForStore(base64_decode($mod))."' 
                       AND $column[block] = '".pnVarPrepForStore(base64_decode($bloc))."'
                       AND $column[theme] = '".pnVarPrepForStore($skin)."'";
        
        $result =& $dbconn->Execute($sql);
        $result->Close();
        
        xanthia_admin_riordina(array('mod'  => $mod, 'bloc' => $bloc, 'skin' => $skin));

	if (!pnModAPILoad('Xanthia', 'user')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	$skinid = pnModAPIFunc('Xanthia','user','getSkinID', array('skin' => $skin));
	if (!pnModAPIFunc('Xanthia', 'admin', 'writezonescache', array('skinid' => $skinid,'modules' => pnVarPrepForStore(base64_decode($mod))))) {
		//$return = false;
	}
        
        if ($refresh == "yes"){
            if(empty($filtro)){
                $filtro = substr(base64_decode($mod), 0,1);
            }  
            
            pnRedirect(pnModURL('Xanthia', 'admin', 'bcontrol',
                        array('skin' =>$skin,
                             'filtro' => $filtro)));
        }else{
            pnRedirect(base64_decode($refresh));
        }
    }
}

/**
 * @access		private
 * @author      ZXvision
 * @author      TiMax
 * @author      Cino             
 */
function xanthia_admin_DecRule($args){
    
    if (pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)){
        
        extract($args);
        list($skin,$mod,$bloc,$refresh) = pnVarCleanFromInput('skin','mod','bloc','refresh');
        
        if(empty($skin)){
            $skin = pnUserGetTheme();
        }  
                
        // Setup the DB handle
        $dbconn =& pnDBGetConn(true);
        $pntable =& pnDBGetTables();
        
        // Setup the table and column info
        $table = $pntable['theme_blcontrol'];
        $column = &$pntable['theme_blcontrol_column'];

        $sql= "UPDATE $table SET 
                      $column[weight]=$column[weight]+'1.5'
                       WHERE $column[module] = '".pnVarPrepForStore(base64_decode($mod))."' 
                        AND $column[block] = '".pnVarPrepForStore(base64_decode($bloc))."'
                        AND $column[theme] = '".pnVarPrepForStore($skin)."'";
        
        $result =& $dbconn->Execute($sql);
        $result->Close();
        
        xanthia_admin_riordina(array('mod'  => $mod,
                                     'bloc' => $bloc,
                                     'skin' => $skin));

	if (!pnModAPILoad('Xanthia', 'user')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	$skinid = pnModAPIFunc('Xanthia','user','getSkinID', array('skin' => $skin));
	if (!pnModAPIFunc('Xanthia', 'admin', 'writezonescache', array('skinid' => $skinid, 'modules' => pnVarPrepForStore(base64_decode($mod))))) {
		//$return = false;
	} 
                                     
       if ($refresh == "yes") {
           if(empty($filtro)){
               $filtro = substr(base64_decode($mod), 0,1);
           }  
           pnRedirect(pnModURL('Xanthia', 'admin', 'bcontrol',
                        array('skin' =>$skin,
                              'filtro' => $filtro)));
                }else{
                    pnRedirect(base64_decode($refresh));
                }
    }
}

/**
 * @access		private
 * @author      ZXvision
 * @author      TiMax
 * @author      Cino             
 */
function xanthia_admin_RemoveRule($args){
    
    if (pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)){
        extract($args);
        
        list($skin,$mod,$bloc,$refresh) = pnVarCleanFromInput('skin','mod','bloc','refresh');
        
        if(empty($skin)){
            $skin = pnUserGetTheme();
        } 
                
        $dbconn =& pnDBGetConn(true);
        $pntable =& pnDBGetTables();
        $table = $pntable['theme_blcontrol'];
        $column = &$pntable['theme_blcontrol_column'];

        $sql= "DELETE FROM $table 
                        WHERE $column[module] = '".pnVarPrepForStore(base64_decode($mod))."' 
                        AND $column[block] = '".pnVarPrepForStore(base64_decode($bloc))."'
                        AND $column[theme] = '".pnVarPrepForStore($skin)."'";
        
        $result =& $dbconn->Execute($sql);
        $result->Close();
        
	if (!pnModAPILoad('Xanthia', 'user')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	$skinid = pnModAPIFunc('Xanthia','user','getSkinID', array('skin' => $skin));
	if (!pnModAPIFunc('Xanthia', 'admin', 'writezonescache', array('skinid' => $skinid, 'modules' => pnVarPrepForStore(base64_decode($mod))))) {
		//$return = false;
	}
        if ($refresh == "yes") {
            if(empty($filtro)){
                $filtro = substr(base64_decode($mod), 0,1);
            }
            pnRedirect(pnModURL('Xanthia', 'admin', 'bcontrol',
                             array('skin' =>$skin,
                                   'filtro' => $filtro)));
        }else{
            pnRedirect(base64_decode($refresh));
        }
    }
}

/**
 * @access		private
 * @author      ZXvision
 * @author      TiMax
 * @author      Cino             
 */
function xanthia_admin_blockControlTemplate($args){
    
    if (pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)){
        extract($args);
        
        list($skin, $mod,$bloc,$refresh,$template) = pnVarCleanFromInput('skin','mod','bloc','refresh','template');
        
        if(empty($skin)){
            $skin = pnUserGetTheme();
        }
        
        $skinid = pnModAPIFunc('Xanthia','user','getSkinID', array('skin' => pnVarPrepForStore($skin)));
        
        $blocktitle = pnBlockGetInfo(base64_decode($bloc));
		$label = strtolower(strip_tags($blocktitle['title']));
		$label = ereg_replace("[^0-9a-z_]","",$label);
        
        $template=pnVarPrepForStore(base64_decode($template));
        if($template == 'false'){
            $templateset = '';}
            else{
                $template ="$template.htm";
                $templateset= $template;
            }
            
            
        $dbconn =& pnDBGetConn(true);
        $pntable =& pnDBGetTables();
        
        $blcontroltable = $pntable['theme_blcontrol'];
        $blcontrolcolumn = &$pntable['theme_blcontrol_column'];
        
	    $layouttable = $pntable['theme_layout'];
	    $layoutcolumn = &$pntable['theme_layout_column'];
	    
	    $zonestable = $pntable['theme_zones'];
	    $zonescolumn = &$pntable['theme_zones_column'];
	    
	    
        $zonesquery = "SELECT $zonescolumn[zone_id]
			           FROM $zonestable
			           WHERE $zonescolumn[label] = '" . pnVarPrepForStore($label) . "'
			           AND $zonescolumn[skin_id] = '" . (int)pnVarPrepForStore($skinid) . "'";
        
        $result =& $dbconn->Execute($zonesquery);
            if ($result->EOF) {
				
                $zonessql = "INSERT INTO $zonestable (
					                     $zonescolumn[zone_id],
					                     $zonescolumn[skin_id],
					                     $zonescolumn[name],
					                     $zonescolumn[label],
					                     $zonescolumn[type],
					                     $zonescolumn[is_active],
					                     $zonescolumn[skin_type]) 
                VALUES ('',
					    '" . (int)pnVarPrepForStore($skinid) . " ',
					    '" . pnVarPrepForStore($blocktitle['title']) . " Block',
					    '" . pnVarPrepForStore($label) . "',
					    1,
					    1,
					    'block')";
                $result =& $dbconn->Execute($zonessql);
                $result->Close();
            }
            
        $layoutquery = "SELECT $layoutcolumn[tpl_file]
			              FROM $layouttable
			             WHERE $layoutcolumn[skin_id] = '" . (int)pnVarPrepForStore($skinid) . "'
			               AND $layoutcolumn[zone_label] = '" . pnVarPrepForStore($label) . "'";
        
        $result =& $dbconn->Execute($layoutquery);
            if ($result->EOF) {
                $result->Close();
		$position=$blocktitle['position'];
		if($position == 'l'){
		    $blocktpl = 'lsblock.htm';
		} elseif ($position == 'r'){
		    $blocktpl = 'rsblock.htm';
		} elseif ($position == 'c'){
		    $blocktpl = 'ccblock.htm';
		} else {
		    $blocktpl = $templateset;
		}
		    
                $layoutsql = "INSERT INTO $layouttable (
				                          $layoutcolumn[skin_id],
				                          $layoutcolumn[zone_label],
				                          $layoutcolumn[tpl_file],
				                          $layoutcolumn[skin_type])
                VALUES ('" . (int)pnVarPrepForStore($skinid) . "',
				        '" . pnVarPrepForStore($label) . "',
				        '" . pnVarPrepForStore($blocktpl) ."',
				        'block')";
                
                $result =& $dbconn->Execute($layoutsql);
                $result->Close();
            }
	
                $blcontrolsql= "UPDATE $blcontroltable SET $blcontrolcolumn[blocktemplate]='".pnVarPrepForStore($templateset)."' 
                                WHERE $blcontrolcolumn[module] = '".pnVarPrepForStore(base64_decode($mod))."' 
                                AND $blcontrolcolumn[block] = '".pnVarPrepForStore(base64_decode($bloc))."'
                                AND $blcontrolcolumn[theme] = '".pnVarPrepForStore($skin)."'";

                $result =& $dbconn->Execute($blcontrolsql);
                $result->Close();
        
	if (!pnModAPILoad('Xanthia', 'user')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	if (!pnModAPIFunc('Xanthia', 'admin', 'writezonescache', array('skinid' => $skinid,'modules' => pnVarPrepForStore(base64_decode($mod))))) {
	}
	if (!pnModAPIFunc('Xanthia', 'admin', 'writethemetplcache', array('skinid' => $skinid))) {
	}
        if ($refresh == "yes") {
            if(empty($filtro)){
                $filtro = substr(base64_decode($mod), 0,1);
            }
            pnRedirect(pnModURL('Xanthia', 'admin', 'bcontrol',
                         array('skin' =>$skin,
                               'filtro' => $filtro)));
        }else{
            pnRedirect(base64_decode($refresh));
        }
    }
}

/**
 * @access		private
 * @author      ZXvision
 * @author      TiMax
 * @author      Cino             
 */
function xanthia_admin_ChangePos($args)
{
    if (pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)){
        extract($args);

        list($skin,$mod,$bloc,$refresh,$newpos) = pnVarCleanFromInput('skin','mod','bloc','refresh','newpos');    

        if(empty($skin)){
            $skin = pnUserGetTheme();
        }
       
        // Setup the DB handle
        $dbconn =& pnDBGetConn(true);
        $pntable =& pnDBGetTables();

        // Setup the table and column info
        $table = $pntable['theme_blcontrol'];
        $column = &$pntable['theme_blcontrol_column'];

        $sql= "UPDATE $table SET $column[position]='$newpos'
                            WHERE $column[module] = '".pnVarPrepForStore(base64_decode($mod))."'
                            AND $column[block] = '".pnVarPrepForStore(base64_decode($bloc))."'
                            AND $column[theme] = '".pnVarPrepForStore($skin)."'";
        $result =& $dbconn->Execute($sql);
        $result->Close();
        
        xanthia_admin_riordina(array('mod'  => $mod, 'bloc' => $bloc));
        
	if (!pnModAPILoad('Xanthia', 'user')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	$skinid = pnModAPIFunc('Xanthia','user','getSkinID', array('skin' => $skin));
	if (!pnModAPIFunc('Xanthia', 'admin', 'writezonescache', array('skinid' => $skinid,'modules' => pnVarPrepForStore(base64_decode($mod))))) {

		//$return = false;
	}
        
        if ($refresh == "yes") {
            if(empty($filtro)){
                $filtro = substr(base64_decode($mod), 0,1);
            }
            pnRedirect(pnModURL('Xanthia', 'admin', 'bcontrol',
                         array('skin' =>$skin,
                               'filtro' => $filtro)));
        } else {
			pnRedirect(base64_decode($refresh));
        }
    }
}

/**
 * @access		private
 */
function xanthia_admin_modTemplate($args){
    
    extract($args);
    
    list($skin_id,$tpl_file,$newsource,$originalsource) = pnVarCleanFromInput('skin_id','tpl_file','source','osource');
    
    if (!pnModAPILoad('Xanthia','admin')){
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
    }
    
    // Load user API
	if (!pnModAPILoad('Xanthia','user')) {	    
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	if (!defined('_XANTHIA_ROOT_PATH')) {
		$xanthiarootpath = pnModGetVar('Xanthia','rootpath');
		define('_XANTHIA_ROOT_PATH', $xanthiarootpath);
	}

    $skinName = pnModAPIFunc('Xanthia','user','getSkinFromID',
									array('id' => $skin_id));
									
	$newsource  = str_replace('</head>', '', $newsource);
	$newsource  = preg_replace('/<body[^>]*?>.*?>/i', '', $newsource); 	
	$newsource  = preg_replace('/<body[^>]*>/i', '', $newsource);
	
	$originalsource  = str_replace('</head>', '', base64_decode($originalsource));
	$originalsource  = preg_replace('/<body[^>]*?>.*?>/i', '', $originalsource);
	$originalsource  = preg_replace('/<body[^>]*>/i', '', $originalsource);
	
	if (!class_exists('Smarty')) {
	    
	    include_once 'includes/classes/Smarty/Smarty.class.php';
	    
	    $vardisplay = new Smarty;
	    $vardisplay->compile_check	= false;                      // check for updated templates?
        $vardisplay->force_compile	= false;                     // force compile template always?
        $vardisplay->debugging		= false;                     // debugging on/off
        $vardisplay->left_delimiter	= "<!--[";                      // begin holder tag (be nice to others)
        $vardisplay->right_delimiter	= "]-->";                      // end holder tag
        $vardisplay->compile_dir		= pnConfigGetVar('temp') . '/Xanthia_compiled';    // cache directory (compiled templates)
        
        $paletteid = pnModGetVar('Xanthia',$skinName.'use');
        

	
		$colors = pnModAPIFunc('Xanthia','user','getSkinColors',
							array('skinid' => $skin_id,
								  'paletteid' => $paletteid));
	
                              
	/*
	 * Hack setup recoding to use variables set by theme
	 *
	*/
    $vardisplay->register_function("FOOTMSG","nofootmsg");                        // footer message
    $vardisplay->register_function("BANNERS","nothemebanners");
    $vardisplay->assign(array("BGCOLOR"      => $colors['background'],
                              "COLOR2"      => $colors['color2'],
                              "COLOR3"      => $colors['color3'],
                              "COLOR4"      => $colors['color4'],
                              "COLOR5"      => $colors['color5'],
                              "COLOR6"      => $colors['color6'],
                              "SEPCOLOR"      => $colors['sepcolor'],
                              "TEXTCOLOR1"    => $colors['text1'],
                              "TEXTCOLOR2"    => $colors['text2'],
                              "CONTENT"       => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/cntblk.png border=\"2\">",
                              "MAINCONTENT"   => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/mcntblk.png border=\"2\">",
                              "TITLE"         => "Title",
                              "ZUPPERTOP"     => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/uptblk.png border=\"2\">",
                              "ZLOGO"         => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/logblk.png border=\"2\">",
                              "ZBANNERA"      => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanablk.png border=\"2\">",
                              "ZCHANNEL"      => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zchanblk.png border=\"2\">",
                              "ZBANNERB"      => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">",
                              "ZSCHANNELTOP"  => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">",
                              "ZBANNERC"      => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">",
                              "ZSCHANNELBOT"  => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">",
                              "ZCOL3SLEFT"    => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">",
                              "ZCOL3SCENTER"  => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">",
                              "ZCOL3SRIGHT"   => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">",
                              "ZINCOLLEFT"    => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">",
                              "ZINCOLRIGHT"   => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">",
                              "ZCOL4SLEFT"    => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">",
                              "ZCOL4SCENTER"  => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">",
                              "ZCOL4SRIGHT"   => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">",
                              "ZCOL5SLEFT"    => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">",
                              "ZCOL5SCENTER"  => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">", 
                              "ZCOL5SRIGHT"   => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">",
                              "ZCOL6SLEFT"    => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">",
                              "ZCOL6SCENTER"  => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">", 
                              "ZCOL6SRIGHT"   => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">",
                              "ZONE1"         => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">",
                              "ZONE2"         => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">",
                              "ZONE3"         => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">", 
                              "ZONE4"         => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">", 
                              "ZONE5"         => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">",
                              "ZONE6"         => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">",
                              "ZONE8"         => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">", 
                              "ZONE7"         => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">",
                              "ZONE9"         => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/zbanbblk.png border=\"2\">",
                              "RIGHTBLOCKS"   => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/rblks.png border=\"1\">",
                              "LEFTBLOCKS"    => "<img src="._XANTHIA_ROOT_PATH."/Xanthia/pnlang/eng/pnimages/lblks.png border=\"1\">",
                              "DATETIME"      => "Date and Time",
                              "MINBOX"        => "<img src=images/global/downb.gif border=\"1\">"));
                              
                              
        define('_XA_TBGCOLOR',''.$colors['background'].'');
        define('_XA_TCOLOR1',$colors['color1']);
        define('_XA_TCOLOR2',$colors['color2']);
        define('_XA_TCOLOR3',$colors['color3']);
        define('_XA_TCOLOR4',$colors['color4']);
        define('_XA_TCOLOR5',$colors['color5']);
        define('_XA_TCOLOR6',$colors['color6']);
        define('_XA_TCOLOR7',$colors['color7']);
        define('_XA_TCOLOR8',$colors['color8']);
        define('_XA_TSEPCOLOR',$colors['sepcolor']);
        define('_XA_TTEXT1COLOR',$colors['text1']);
        define('_XA_TTEXT2COLOR',$colors['text2']);
        define('_XA_TLINKCOLOR',$colors['link']);
        define('_XA_TVLINKCOLOR',$colors['vlink']);
        define('_XA_THOVERCOLOR',$colors['hover']);
        
        ob_start();
        
        echo "<html><head>";
        echo "<TITLE>$skinName : $tpl_file</TITLE>";

		$GLOBALS['bgcolor1']   = $colors['background'];
		$GLOBALS['bgcolor2']   = $colors['color2'];
		$GLOBALS['bgcolor3']   = $colors['color3'];
		$GLOBALS['bgcolor4']   = $colors['color4'];
		$GLOBALS['bgcolor5']   = $colors['color5'];
		$GLOBALS['bgcolor6']  = $colors['color6'];
		$GLOBALS['sepcolor']  = $colors['sepcolor'];
		$GLOBALS['textcolor1'] = $colors['text1'];
		$GLOBALS['textcolor2'] = $colors['text2'];
		$GLOBALS['skinName'] = $skinName;
		
		include_once 'themes/'.pnVarPrepForOS($skinName).'/style/style.php';
		echo "</head>";
		echo "<BODY>";
		echo "<table cellspacing=\"2\" cellpadding=\"2\" border=\"1\">";
		echo "<tr>";
		echo "    <td>Original</td>";
		echo "    <td>Submited</td>";
		echo "</tr>";
		echo "<tr>";
		echo "    <td>$originalsource</td>";
		echo "    <td>$newsource</td>";
		echo "</tr>";
		echo "</table>";
		echo '<p align="right"><input type="button" value="'._XA_CHIUDI.'" onclick="javascript:window.close();"></p>';
		echo '</body></html>';

		$source = ob_get_contents();
	
		ob_end_clean();
	
		$GLOBALS['vartpl'] = $source; 
		$vardisplay->display('var:vartpl');
		$vardisplay->clear_compiled_tpl('var:vartpl');
	}
	exit;
}

/**
 * @access		private
 */
function nofootmsg(){
    echo "Footer Message";
    return  true;
}

/**
 * @access		private
 */
function nothemebanners(){
	echo "Banner Message";
    return true;
}

/**
 * @access		private
 */
function xanthia_admin_doModTemplate($args){
    
    extract($args);
    

    list($skin_id,$tpl_id,$source,$tpl_file) = pnVarCleanFromInput('skin_id','tpl_id','source','tpl_file');

    // Load user API
	if (!pnModAPILoad('Xanthia','user')) {	    
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	} 
    
    if (!pnModAPILoad('Xanthia','admin')){
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
    }
	
	if(pnModAPIFunc('Xanthia','admin','updateDbTemplate',
									 array('tpl_id' => $tpl_id,
										   'skin_id' => $skin_id,
										   'tpl_file' => $tpl_file,
										   'tpl_source' => $source))){
                                           
        pnSessionSetVar('statusmsg', 'Template Updated');
        
		if (!class_exists('Smarty')){
			include_once 'includes/classes/Smarty/Smarty.class.php';
		}
		
		$skinName = pnModAPIFunc('Xanthia','user','getSkinFromID',
								 array('id' => $skin_id));
		$thisfile = "$skinName/$tpl_file";						
		$DelteDTS =& new Smarty;
		$DelteDTS->compile_id           = "$skinName".pnUserGetLang().'';
		$DelteDTS->compile_dir		= pnConfigGetVar('temp') . '/Xanthia_compiled';    // cache directory (compiled templates)
	    // don't use subdirectories when creating compiled/cached templates
	    // this works better in a hosted environment
	    $DelteDTS->use_sub_dirs = false;
		$DelteDTS->clear_compiled_tpl("userdb:$thisfile");
    
		pnRedirect(pnModURL('Xanthia','admin','editTemplate',
		array('tpl' => $tpl_file,
			  'skin' => $skin_id,
			  'authid' => pnSecGenAuthKey('Xanthia'))));
                                       
	}else{
		pnSessionSetVar('errormsg', 'Template Not Updated');
		pnRedirect(pnModURL('Xanthia','admin','editTemplate',
					array('tpl' => $tpl_file,
						  'skin' => $skin_id,
						  'authid' => pnSecGenAuthKey('Xanthia'))));
	}													  	
}

/**
 * @access		private
 */
function xanthia_admin_addTplFile($args){
    
	extract($args);
	
	list($authid, $skinid, $tpl_module, $themename, $source, $newtype, $sourceTpl) = pnVarCleanFromInput('authid','skinid','tpl_module','themename','source', 'newtype', 'sourceTpl');

	if (!pnModAPILoad('Xanthia','admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}
	
	if(pnModAPIFunc('Xanthia','admin','addTplFile',
                                 array('skinid' => $skinid,
                                       'tpl_module' => $tpl_module,
                                       'newtheme' => $themename,
                                       'sourceTpl' => $sourceTpl,
                                       'source' => $source,
                                       'newtype' => $newtype
                                 ))){
		pnSessionSetVar('statusmsg', 'Template Updated');
		
		if (!class_exists('Smarty')) {
			include_once 'includes/classes/Smarty/Smarty.class.php';
		}
    
		$thisfile = "$themename/$sourceTpl";								
		$DelteDTS = new Smarty;
		$DelteDTS->compile_id = "$themename".pnUserGetLang()."";
		$DelteDTS->compile_dir = pnConfigGetVar('temp') . '/Xanthia_compiled';    // cache directory (compiled templates)
		$DelteDTS->clear_compiled_tpl("userdb:$thisfile");	
		pnRedirect(pnModURL('Xanthia','admin','editTemplate',
												array('tpl' => $sourceTpl,
												      'skin' => $skinid,
													  'authid' => pnSecGenAuthKey('Xanthia'))));
	}else{
		pnSessionSetVar('errormsg', 'Template Not Updated');
		pnRedirect(pnModURL('Xanthia','admin','editTemplate',
														array('tpl' => $sourceTpl,
															  'skin' => $skinid,
															  'authid' => pnSecGenAuthKey('Xanthia'))));  
	}													  	
}

/**
 * @access		private
 */
function xanthia_admin_BlockZones($args){
    
	/** Security check - important to do this as early as possible to avoid
	 * potential security holes or just too much wasted processing.  For the
	 * main function we want to check that the user has at least edit privilege
	 * for some item within this component, or else they won't be able to do
	 * anything and so we refuse access altogether.  The lowest level of access
	 * for administration depends on the particular module, but it is generally
	 * either 'edit' or 'delete'
	 */
	if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
	}

	// Extract our parameters
	extract ($args);
	//$skinID = pnVarCleanFromInput('skinID');
	// Load user API
	if (!pnModAPILoad('Xanthia','user')) {	    
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	// Load admin API
	if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
	}

	// Check $skin parameter
	if (!isset($skinID)) {
		// get the active skin name and ID
		$skinName = pnVarCleanFromInput('skin');
		//$skinName = pnModAPIFunc('Xanthia','user','getSkinName');
		$skinID = pnModAPIFunc('Xanthia','user','getSkinID',
									array('skin' => $skinName));
	} else {
		// get the skin name for supplied ID
		$skinName = pnModAPIFunc('Xanthia','user','getSkinFromID',
									array('id' => $skinID));
	}

	// Create output object - this object will store all of our output so that
	// we can return it easily when required
	$pnRender =& new pnRender('Xanthia');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

    $pnRender->assign('menu', pnModFunc('Xanthia','admin','thememenu'));
    $pnRender->assign('title', $skinName.'&nbsp;'. _XA_BCZONES);
    $pnRender->assign('skinID', $skinID);
    $pnRender->assign('authid', pnSecGenAuthKey());
    $pnRender->assign('columnheaders', array(_XA_NZID,
                                       _XA_NZDESC,
                                       _XA_NZTAG,
                                       _XA_NZACTION));	
	// New Main configure options for Xanthia

    $stringa = pnModGetVar('Xanthia', $skinName.'newzone');
    $chunk=split('[|]', $stringa);
    $lenarray=count($chunk);
    $options = array();
    for($i=1; $i<$lenarray; $i++) {
		$dati=split('[:]', $chunk[$i]);
		$k=strtolower($dati[0]);
		$d=$dati[1];
		$t=$dati[2];

		$options[] = array('url' => pnModURL('Xanthia', 'admin', 'rimuovinuovezone', array('skinID' => $skinID, 
																						 'riga' => $dati,
																						 'skin' => $skinName)),
							'label' => pnVarPrepForDisplay(_XA_NZREMOVE),
							'zid' => $k,
							'zdesc' => $d,
							'ztag' => '<!--[$'.$t.']-->');     
    }
	if (!isset($k)) {
		$k = 0;
	}
    $i = ($k+1);
	$pnRender->assign('formurl', pnModURL('Xanthia', 'admin', 'nuovezone'));
	$pnRender->assign('addzone', pnVarPrepForDisplay(_XA_NZTITLE));
	$pnRender->assign('nextid', $i);
	$pnRender->assign('blockzones', $options);
	$pnRender->assign('submit', pnVarPrepForDisplay(_SUBMIT));
	return $pnRender->fetch('xanthiaadminblockzones.htm');  
}

/**
 * @access		private
 * @author      ZXvision
 * @author      TiMax
 * @author      Cino             
 */
function xanthia_admin_bcontrol($args)
{
    //extract($args);
    list($skin, $filtro) = pnVarCleanFromInput('skin','filtro'); 
    
    if (!isset($filtro)) {
        $filtro='A';
    } else {
        $filtro = strtolower(pnVarCleanFromInput('filtro'));
    }

    if (!pnModAPILoad('Modules', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
    }    
    
    
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if(empty($type)){
        $type = _XA_MODULES;
    }

	// Create output object - this object will store all of our output so that
	// we can return it easily when required
	$pnRender =& new pnRender('Xanthia');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

    $pnRender->assign('menu', pnModFunc('Xanthia','admin','thememenu'));
    
    $pnRender->assign('moduleheaders', array(_XA_MODULES,
                                             _XA_ACTIONS,
                                             _ACTIVE,
                                             _UP,
                                             _DOWN,
                                             _XA_POSITION,
                                             _XA_TEMPLATE));
    
    $questionshow = pnModGetVar('Xanthia','allmods');
    
    if($questionshow != 1){
        $pnRender->assign('question', _XA_BCQUESTIONBC);
        $pnRender->assign('allmodslink',array('text' => pnVarPrepForDisplay(_XA_BCACTIVATEALL),
                                        'location' => pnModURL('Xanthia', 'admin', 'setBcOnAll',
                                           array('authid' => pnSecGenAuthKey('Xanthia')))));
    }else{
        $pnRender->assign('question', _XA_BCTURNOFFBC);
        $pnRender->assign('allmodslink',array('text' => pnVarPrepForDisplay(_XA_BCDEACTIVATEALL),
                                        'location' => pnModURL('Xanthia', 'admin', 'setBcOffAll',
                                           array('authid' => pnSecGenAuthKey('Xanthia')))));
    }        
    
   if(@$this){
       $pnRender->assign('formurl', pnModURL('Xanthia', 'admin', 'blockControlRule'));
       $pnRender->assign('title1', _XA_BCSTART);
   }else{
       $pnRender->assign('formurl', pnModURL('Xanthia', 'admin', 'blockControlRule'));
       $pnRender->assign('title1', _XA_BCCHOOSEBLOCKS);
	   $pnRender->assign('skin', $skin);
	   $pnRender->assign('authid', pnSecGenAuthKey('Xanthia'));       
   }
                
    $pnRender->assign('title', _XA_BLOCKMANAGE.$type);
    
	$allmodules = array();
	// Let's show only the active modules
	//$mods = pnModAPIFunc('Modules', 'admin', 'list');
	$mods = pnModGetAllMods();
	foreach($mods as $mod) {
		$allmodules[] = array('id'   => $mod['name'],
							  'name' => $mod['name']);
	}
        
    $pnRender->assign('modules', $allmodules);

    $allblocks = array();
	$allblocks[] = array('id'   => -1,
						 'name' => '==Block Control');
    pnModAPILoad('Xanthia', 'admin');
    $blocks = pnModAPIFunc('Xanthia', 'admin', 'listblocks');
    foreach($blocks as $block) {
        $allblocks[] = array('id'   => $block['bid'],
                             'name' => $block['title']);
    }
    $pnRender->assign('blocks', $allblocks);

    //$mods = pnModAPIFunc('Modules', 'admin', 'list');
    $alphabet = array ('*', 'A','B','C','D','E','F','G','H','I','J','K','L','M',
                       'N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
                       
	$num = count($alphabet) - 1;
	$counter = 0;
	$menuoptions[] = array();
	while(list(, $ltr) = each($alphabet)) {
		$menuoptions[] = array('url' => pnVarPrepForDisplay(pnModURL('Xanthia', 'admin', 'bcontrol',
						 array('skin' =>$skin,
							   'filtro' => $ltr))), 
							   'title' => pnVarPrepForDisplay($ltr));
	}
    $pnRender->assign('menuoptions', $menuoptions);

	$modulecontrol[] = array();

    // We've aready got a list of modules from above....
	// Let's show only the active modules
	////$mods = pnModAPIFunc('Modules', 'admin', 'list');
	//$umods = pnModGetUserMods();
	//$amods = pnModGetAdminMods();
	//$mods = array_merge($umods, $amods);
   
	foreach($mods as $mod) {
        $pippo=$mod['name'];
        $rows[] = array();
        if (($filtro == '*' ) || preg_match("/^".$filtro."/i", $pippo)) {
            $themeblcontroltable = $pntable['theme_blcontrol'];
            $themeblcontrolcolumn = &$pntable['theme_blcontrol_column'];
            $result =& $dbconn->Execute("SELECT $themeblcontrolcolumn[module] as module,
                                                $themeblcontrolcolumn[block] as block,
                                                $themeblcontrolcolumn[position] as position,
                                                $themeblcontrolcolumn[weight] as weight, 
                                                $themeblcontrolcolumn[blocktemplate] as blocktpl 
                                         FROM $themeblcontroltable
                                         WHERE $themeblcontrolcolumn[module]='".pnVarPrepForStore($pippo)."'
                                         AND  $themeblcontrolcolumn[theme]='".pnVarPrepForStore($skin)."'
                                         ORDER BY $themeblcontrolcolumn[weight], $themeblcontrolcolumn[position]");
            $BlockControlActive='no';
            while(!$result->EOF) {
                $row = $result->GetRowAssoc(false);
                $modulo= $row['module'];
				if ($row['block'] == '-1') {
				    $title = 'Block Control';
				} else {
    				$blockinfo = pnBlockGetInfo($row['block']);
                    $title = $blockinfo['title'];
					if (empty($title)) {
						$title = $blockinfo[bkey];
					}
				}
                $blocco= $row['block'];
                $posizione= $row['position'];
                $peso= $row['weight'];
                $blocktpl= $row['blocktpl'];

                if ($blocco =='-1') {
                    $BlockControlActive = 'yes';
                }
                
                // Check if block is active
                //$blocstatus = pnBlockGetInfoByTitle($blocco);
				$blocstatus = pnBlockGetInfo($blocco);
                if (($blocstatus['active'] ==1)||($blocco == '-1')) {
                    $imag = 'green_dot.gif';
                } else{
                    $imag = 'red_dot.gif';
                }

                $blockname = pnVarPrepHTMLDisplay($title);
                $removeurl = pnVarPrepForDisplay(pnModURL('Xanthia', 'admin', 'RemoveRule',
              				array('skin' =>$skin,
                                  'mod' => base64_encode($modulo),
                                  'bloc' => base64_encode($blocco),
                                  'refresh' => 'yes',
                                  'filtro' => $filtro))); 
                $removelabel = pnVarPrepForDisplay(_XA_REMOVE);
                $stateimg = pnVarPrepForDisplay($imag);
                if (($BlockControlActive == 'yes') && ($blocco != '-1')){

					$blockup = pnVarPrepForDisplay(pnModURL('Xanthia', 'admin', 'IncRule',
							 array('skin' =>$skin,
								   'mod' => base64_encode($modulo),
								   'bloc' => base64_encode($blocco),
								   'refresh' => 'yes',
								   'filtro' => $filtro)));
					$uptext = pnVarPrepForDisplay(_UP);                 
					   
					$blockdown = pnVarPrepForDisplay(pnModURL('Xanthia', 'admin', 'DecRule',
							   array('skin' =>$skin,
									 'mod' => base64_encode($modulo),
									 'bloc' => base64_encode($blocco),
									 'refresh' => 'yes', 
									 'filtro' => $filtro)));
					$downtext = pnVarPrepForDisplay(_DOWN);                   

					$url = pnVarPrepForDisplay(pnModURL('Xanthia', 'admin', 'changepos',
													   array('skin' =>$skin,
															  'mod' => base64_encode($modulo),
															  'bloc' => base64_encode($blocco),
															  'refresh' => 'yes',
															  'filtro' => $filtro,
															  'newpos' => ''))); 
                 
					$stringa="l:"._XA_LEFT.":|"."r:"._XA_RIGHT.":|"."c:"._XA_CENTER.":|"."i:"._XA_INNER.":";
					$stringa .= pnModGetVar('Xanthia', $skin.'newzone');
					$chunk=split('[|]', $stringa);
					$lenarray=count($chunk);
					$dropdown[] = array();
					for($i=0; $i<$lenarray; $i++) {
						$dati=split('[:]', $chunk[$i]);
						$k=strtolower($dati[0]);
						$v=$dati[1];
						if (strtolower($posizione) == $k) {$pos_cur=" SELECTED"; }else{ $pos_cur="";}
                     
						$dropdown[] = array('onchange' => $url.$k.$pos_cur,
											  'value' => $v);
					}
					
                    $skinid = pnModAPIFunc('Xanthia','user','getSkinID', array('skin' => $skin));
					$templates = pnModAPIFunc('Xanthia','admin','getThemeTplFile',
					                          array('skin' => $skinid,
					                                'zone' => 1,
					                                'type' => 'block'));
					                                
					$url1 = pnVarPrepForDisplay(pnModURL('Xanthia', 'admin', 'blockControlTemplate',
													    array('skin' =>$skin,
															  'mod' => base64_encode($modulo),
															  'bloc' => base64_encode($blocco),
															  'refresh' => 'yes',
															  'filtro' => $filtro,
															  'template' => '')));
															  
						$tdropdown[] = array('onchange' => $url1.base64_encode('false').$sel,
											  'value' => _XA_ZONEDEFAULT);
											  
				    foreach ($templates as $rowtemplate) {
				        $thistemplate= preg_replace('/.htm/','',$rowtemplate);
				        if ($rowtemplate == $blocktpl)
				        {
				            $sel=" SELECTED"; 
				        }else{ 
				            $sel="";
				        }
 	    		    

						$tdropdown[] = array('onchange' => $url1.base64_encode($thistemplate).$sel,
											  'value' => $thistemplate);
				    }

						

				}
				
				$rows[] = array('modulename'	 => $mod['name'],
							   'blockname'	 => $blockname,
							   'removeurl'   => $removeurl, 
							   'removelabel' => $removelabel,
							   'stateimg' 	 => $stateimg,
							   'up' 		 => @$blockup,
							   'uptext' 	 => @$uptext,                 
							   'down' 		 => @$blockdown,
							   'downtext' 	 => @$downtext,
							   'dropdown' 	 => @$dropdown,
							   'tdropdown' 	 => @$tdropdown);
                unset($dropdown);
                unset($tdropdown);
				$result->MoveNext();
			}
			$modulecontrol[]  = array('modulename'	 => $mod['name'],
									  'actions'	 => $rows);
			unset($rows);
		}
	}
        
	$pnRender->assign('modulecontrol', $modulecontrol);
	//$pnRender->assign('item',$rows);

	return $pnRender->fetch('xanthiaadminbcontrol.htm');
}

/**
 * @access		private
 */
function xanthia_admin_help($args){

	extract ($args);
	$helpwith = pnVarCleanFromInput('topic');

	if (!defined('_XANTHIA_ROOT_PATH')) {
		$xanthiarootpath = pnModGetVar('Xanthia','rootpath');
		define('_XANTHIA_ROOT_PATH', $xanthiarootpath);
	}

	include ""._XANTHIA_ROOT_PATH.'/Xanthia/docs/help/'.pnVarPrepFoOS($helpwith).'.php';
	exit;
}

/**
 * @access		private
 */
function xanthia_admin_getIncludes($args){

	extract ($args);
	list($include,$skin,$paletteid,$palname) = pnVarCleanFromInput('link','skin','paletteid','palname');

	if (!defined('_XANTHIA_ROOT_PATH')) {
		$xanthiarootpath = pnModGetVar('Xanthia','rootpath');
		define('_XANTHIA_ROOT_PATH', $xanthiarootpath);
	}

	$authid = pnSecGenAuthKey('Xanthia');
    include ""._XANTHIA_ROOT_PATH.'/Xanthia/pnhtml/'.pnVarPrepForOS($include).'.php';
	exit;
}

/**
 * @access		private
 */
function xanthia_admin_setPalette($args)
{

	// Check Permissions
	if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
	}

	extract($args);
	list($skin, $paletteid, $skinName) = pnVarCleanFromInput('skin','paletteid','skinName');
	
	pnModSetVar('Xanthia',$skinName.'use',$paletteid);
	
	// Load admin API
    if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
    }
    
	if (!pnModAPIFunc('Xanthia', 'admin', 'writepalettescache', array('skinid' => $skin))) {
		$return = false;
	}
    	
	if (!pnModAPIFunc('Xanthia', 'admin', 'writestylesheet', array('skinid' => $skin,
																   'paletteid' => $paletteid))) {
		$return = false;
	}	
	
	$authid = pnSecGenAuthKey();

	// Render the output
	pnRedirect(pnModURL('Xanthia','admin','editTheme',
                               array('skin' => $skinName,
                                     'todo' => 'colors',
                                     'authid' => $authid  )));



}

/**
 * @access		private
 */
function xanthia_admin_credits($args)
{
	// Check Permissions
	if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
	}

	$skinName = pnVarCleanFromInput('skin');

    if (file_exists("themes/$skinName/xaninfo.php")) {
	
	    // get the theme info file
	    include 'themes/'.pnVarPrepForOS($skinName).'/xaninfo.php';

        // get the current language
		$currentlang = pnUserGetLang();
		
		// include the custim languagefile if present
		if (file_exists('themes/'.pnVarPrepForOS($skinName).'/lang/'.pnVarPrepForOS($currentlang).'/xaninfo.php')) {
            include 'themes/'.pnVarPrepForOS($skinName).'/lang/'.pnVarPrepForOS($currentlang).'/xaninfo.php';
		}

    	// Create output object - this object will store all of our output so that
		// we can return it easily when required
		$pnRender =& new pnRender('Xanthia');
	
		// As Admin output changes often, we do not want caching.
		$pnRender->caching = false;

		// Main menu
		$pnRender->assign('menu', xanthia_adminmenu());

       	// assign the theme info array
		$pnRender->assign('themeinfo', $themeinfo);

		// Render the output
		return $pnRender->fetch('xanthiaadmincredits.htm');
	} else {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_NOTHEMECREDITS));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
    }

}

/**
 * @access		private
 */
function xanthia_admin_reloadTemplates()
{
	// Check Permissions
	if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
	}

    // confirm auth key
	if (!pnSecConfirmAuthKey())	{
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
		return false;	
	}

    $skin = pnVarCleanFromInput('skin');

    if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
    }    
    if (!pnModAPILoad('Xanthia', 'user')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
    }    

    // make sure we have the correct skin id by calling the api
    $skinid = pnModAPIFunc('Xanthia','user','getSkinID', array('skin' => $skin));

	// Setup DB handle
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();        
	$themetplsourcetable = $pntable['theme_tplsource'];
	$themetplsourcecolumn = &$pntable['theme_tplsource_column'];
	$themetplfiletable = $pntable['theme_tplfile'];
	$themetplfilecolumn = &$pntable['theme_tplfile_column'];
	
	// Delete info from _theme_tplfile
	$dbconn->Execute("DELETE FROM $themetplfiletable WHERE $themetplfilecolumn[tpl_skin_id]='".pnVarPrepForStore($skinid)."'");

	// remove templates from db first so we can re-insert them
	$dbconn->Execute("DELETE FROM $themetplsourcetable WHERE $themetplsourcecolumn[tpl_skin_id]='".pnVarPrepForStore($skinid)."'");

	$cid = pnModAPIFunc('Xanthia','admin','insertThemeDB', array('id' => $skin)); // sucessful install
	if ($cid != false) {
		if (!class_exists('Smarty')){
			include_once 'includes/classes/Smarty/Smarty.class.php';
		}
							
		$DelteDTS =& new Smarty;
		$DelteDTS->compile_id           = "$skin".pnUserGetLang().'';
		$DelteDTS->compile_dir		= pnConfigGetVar('temp') . '/Xanthia_compiled';    // cache directory (compiled templates)
	    // don't use subdirectories when creating compiled/cached templates
	    // this works better in a hosted environment
	    $DelteDTS->use_sub_dirs = false;
		$DelteDTS->clear_compiled_tpl();
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_TEMPLATERELOADSUCESSFUL));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
		return true;
	} else {
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_TEMPLATERELOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
		return false;
	}

}

/**
 * @access		private
 */
function xanthia_admin_reloadTemplate($args)
{
	// Check Permissions
	if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
	}

    // confirm auth key
	if (!pnSecConfirmAuthKey())	{
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
		return false;	
	}


    if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
    }    
    if (!pnModAPILoad('Xanthia', 'user')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
    }    
    //extract($args);
    list($skin_id,$tpl_file,$tpltype)= pnVarCleanFromInput('skin','tpl','tpltype');

	if ($tpltype != 'block' && $tpltype != 'module') {
		$tpltype = '';
	} else {
		$tpltype .= 's/';
	}

	if (!is_numeric($skin_id)) {
		// get the active skin name and ID
		$skinName = $skin_id;
		$skin_id = pnModAPIFunc('Xanthia','user','getSkinID',
									array('id' => $skin_id));			
	} else {
			$skinName = pnModAPIFunc('Xanthia','user','getSkinFromID',
									array('id' => $skin_id));
	}
 	$tpl_fileid = array();   
	$tpl_fileid = pnModAPIFunc('Xanthia','admin','getEditTemplate',
							  array('tpl' => $tpl_file,
									'skinid' => $skin_id));
	$tpl_id = $tpl_fileid['id'];

	
	$path = 'themes/'.$skinName.'/templates/'.$tpltype.$tpl_file;
	$source =  pnModAPIFunc('Xanthia', 'admin', 'getFileContent', array('file_name' => $path));		

	if(pnModAPIFunc('Xanthia','admin','updateDbTemplate',
									 array('tpl_id' => $tpl_id,
										   'skin_id' => $skin_id,
										   'tpl_file' => $tpl_file,
										   'tpl_source' => $source))){
                                           
       		
        
		if (!class_exists('Smarty')){
			include_once 'includes/classes/Smarty/Smarty.class.php';
		}
		
		$thisfile = "$skinName/$tpl_file";						
		$DelteDTS =& new Smarty;
		$DelteDTS->compile_id           = "$skinName".pnUserGetLang().'';
		$DelteDTS->compile_dir		= pnConfigGetVar('temp') . '/Xanthia_compiled';    // cache directory (compiled templates)
	    // don't use subdirectories when creating compiled/cached templates
	    // this works better in a hosted environment
	    $DelteDTS->use_sub_dirs = false;
		$DelteDTS->clear_compiled_tpl("userdb:$thisfile");
		
		pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_XA_TEMPLATERELOADSUCESSFUL));    
		pnRedirect(pnModURL('Xanthia','admin','editTemplates',
		array('skin' => $skinName,
			  'authid' => pnSecGenAuthKey('Xanthia'))));
                                       
	}else{
		pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_XA_TEMPLATERELOADFAILED));
		pnRedirect(pnModURL('Xanthia','admin','editTemplates',
					array('skin' => $skinName,
						  'authid' => pnSecGenAuthKey('Xanthia'))));
	}

}

/**
 * @access		private
 */
function xanthia_admin_generatethemecache($args)
{

	// Check Permissions
	if (!pnSecAuthAction(0, 'Xanthia::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
	}

    // confirm auth key
	if (!pnSecConfirmAuthKey())	{
		pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
		return false;	
	}

    if (!pnModAPILoad('Xanthia', 'admin')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
    }    

    if (!pnModAPILoad('Xanthia', 'user')) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_APILOADFAILED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'view'));
        return true;
    }    

	// get the active skin name and ID
    $skin= pnVarCleanFromInput('skin');
	$skinid = pnModAPIFunc('Xanthia','user','getSkinID', array('skin' => $skin));

	$return = true;
	if (!pnModAPIFunc('Xanthia', 'admin', 'writesettingscache', array('skinid' => $skinid))) {
		$return = false;
	}
	if (!pnModAPIFunc('Xanthia', 'admin', 'writezonescache', array('skinid' => $skinid))) {
		$return = false;
	}
	if (!pnModAPIFunc('Xanthia', 'admin', 'writepalettescache', array('skinid' => $skinid))) {
		$return = false;
	}
	if (!pnModAPIFunc('Xanthia', 'admin', 'writethemetplcache', array('skinid' => $skinid))) {
		$return = false;
	}
	if ($return) {
        pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_XA_THEMECACHEGENERATED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'thememenu',
							 array('skin' => $skin)));
        pnModSetVar('Xanthia', $skin.'themecache', '1');
		return true;
	} else {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_XA_THEMECACHENOTGENERATED));
        pnRedirect(pnModURL('Xanthia', 'admin', 'thememenu',
							 array('skin' => $skin)));
		return false;
	}
}

?>