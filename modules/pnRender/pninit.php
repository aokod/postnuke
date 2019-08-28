<?php 
// $Id: pninit.php 13853 2004-06-30 14:27:27Z markwest $
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
 * pnRender
 * 
 * PostNuke wrapper class for Smarty
 * 
 * @author      PostNuke development team 
 * @version     .7/.8
 * @link        http://www.post-nuke.net              PostNuke home page
 * @link        http://smarty.php.net                 Smarty home page
 * @license     http://www.gnu.org/copyleft/gpl.html  GNU General Public License
 * @package     PostNuke_System_Modules
 * @subpackage  pnRender
 */

 
/**
 * initialise the pnRender module
 * 
 * This function initializes pnRender settings.
 * 
 * @author       Joerg Napp
 * @version      $Revision: 13853 $
 * @return       boolean true on success, false otherwise.
 */
function pnRender_init()
{ 
	// global PostNuke temp directory
	// this will be deleted in future versions
	// as it will be done by the PostNuke install
	/*include_once 'install/pntemp.php';
	if (!pntemp_install('pnTemp')) {
	    return false;
	}*/

	// now the main initialisation
	pnModSetVar('pnrender', 'compile_check',  true);
	pnModSetVar('pnrender', 'force_compile',  false);
	pnModSetVar('pnrender', 'cache',          false);
	pnModSetVar('pnrender', 'expose_template',false);
	pnModSetVar('pnrender', 'lifetime',       3600);
	
	// Initialisation successful
	return true;
} 


/**
 * upgrade the pnRender module from an old version
 * 
 * This function upgrades the module to be used. It updates tables,
 * registers hooks,...
 * 
 * @author       Joerg Napp
 * @version      $Revision: 13853 $
 * @param        oldversion  the old version
 * @return       boolean true on success, false otherwise.
 */
function pnRender_upgrade($oldversion)
{ 
	return true;
} 


/**
 * delete the pnRender module
 * 
 * This function deletes pnRender settings.
 * (Not sure if that should ever happen!)
 * 
 * @author       Joerg Napp
 * @version      $Revision: 13853 $
 * @return       boolean true on success, false otherwise.
 */
function pnRender_delete()
{
	pnModDelVar('pnrender', 'compile_check');
	pnModDelVar('pnrender', 'force_compile');
	pnModDelVar('pnrender', 'cache');
	pnModDelVar('pnrender', 'expose_template');
	pnModDelVar('pnrender', 'lifetime');
	// Deletion successful
	return true;
} 

?>