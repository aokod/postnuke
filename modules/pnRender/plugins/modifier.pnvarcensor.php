<?php
// $Id: modifier.pnvarcensor.php 16722 2005-08-27 12:35:10Z  $
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
 * @version      $Id: modifier.pnvarcensor.php 16722 2005-08-27 12:35:10Z  $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

/**
 * Smarty modifier to remove censored words
 * 
 * This modifier examines the contents of the passed variable for words which 
 * are deemed offensive or otherwise not allowed to be displayed. These words 
 * are replaced with asterix marks to show that words have been removed.
 * 
 * This modifier tries to be intelligent in its attempt to remove censored 
 * words whilst not censoring words on the censor list that happen to be 
 * embedded in a larger word. 
 * 
 * This modifier uses the information provided in the configuration setting 
 * 'CensorList' as the basis of the words that it censors. It also looks for 
 * commonly derivations of the words used to try to avoid censoring. The system 
 * is also case-insensitive.
 * 
 * Care should be taken to consider the effect of censorship, and if it should 
 * be applied to all information that is passed in by the user or if it should 
 * only be used in specific cases. 
 * 
 * This modifier is to be removed in future versions, as pnVarCensor is being moved 
 * to be a transform hook. 
 * 
 * Example
 * 
 *   <!--[$MyVar|pnvarcensor]-->
 * 
 * 
 * @author       Joerg Napp
 * @since        16. Sept. 2003
 * @param        array    $string     the contents to transform
 * @return       string   the modified output
 */
function smarty_modifier_pnvarcensor($string)
{
    return pnVarCensor($string);
}

?>