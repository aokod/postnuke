<?php
// $Id: modifier.pnvarprephtmldisplay.php 16722 2005-08-27 12:35:10Z  $
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
 * @version      $Id: modifier.pnvarprephtmldisplay.php 16722 2005-08-27 12:35:10Z  $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

/**
 * Smarty modifier to prepare variable for display, preserving some HTML tags
 * 
 * This modifier carries out suitable escaping of characters such that when output 
 * as part of an HTML page the exact string is displayed, except for a number of 
 * admin-defined HTML tags which are left as-is for display purposes.
 * 
 * This modifier should be used with great care, as it does allow certain 
 * HTML tags to be displayed.
 * 
 * The HTML tags that will be displayed are those defined in the configuration 
 * variable AllowableHTML , which is set on a per-instance basis by the site administrator. 
 * 
 * Running this modifier multiple times is cumulative and is not reversible.
 * It recommended that variables that have been returned from this modifier
 * are only used to display the results, and then discarded.
 * 
 * Example
 * 
 *   <!--[$MyVar|pnvarprephtmldisplay]-->
 * 
 * 
 * @author       The pnCommerce team
 * @since        16. Sept. 2003
 * @see          modifier.pnvarprepfordisplay.php::smarty_modifier_pnvarprepfordisplay()
 * @param        array    $string     the contents to transform
 * @return       string   the modified output
 */
function smarty_modifier_pnvarprephtmldisplay($string)
{
    return pnVarPrepHTMLDisplay($string);
}

?>