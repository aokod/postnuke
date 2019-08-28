<?php
// $Id: modifier.examplebold.php 13624 2004-05-24 13:49:17Z markwest $
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
/**
 * Example Module
 * 
 * The Example module shows how to make a PostNuke module. 
 * It can be copied over to get a basic file structure.
 *
 * Purpose of file:  example smarty modifier
 *
 * @package      PostNuke_Miscellaneous_Modules
 * @subpackage   Example
 * @version      $Id: modifier.examplebold.php 13624 2004-05-24 13:49:17Z markwest $
 * @author       Joerg Napp 
 * @since        29. Sept. 2003
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty modifier to output something in bold
 * 
 * Please note that ththis  is just an example of how to use 
 * your own plugins.
 * Otherwise, using a plugin for such a simple function would be
 * overkill -- you would simply put the string in the appropriate
 * tags in the template.
 * 
 * @param       array    $string     the contents to transform
 * @return      string   the modified output
 */
function smarty_modifier_examplebold($string)
{
    return "<strong>$string</strong>";
}

?>