<?php 
// $Id: function.additional_header.php 12575 2004-02-06 15:26:11Z jorg $
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
 * This file is a plugin for pnRender, the PostNuke implementation of Smarty
 * 
 * @package        Xanthia_Templating_Environment
 * @subpackage     Xanthia
 * @version        $Id: function.additional_header.php 12575 2004-02-06 15:26:11Z jorg $
 * @author         The PostNuke development team 
 * @link           http://www.postnuke.com The PostNuke Home Page
 * @copyright      Copyright (C) 2004 by the PostNuke Development Team
 * @license        http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

/**
 * Smarty function to get the site's charset
 * 
 * available parameters:
 *  - assign      if set, the language will be assigned to this variable
 * 
 * 
 * @author   Jörg Napp 
 * @since    03. Feb. 04
 * @param    array    $params     All attributes passed to this function from the template
 * @param    object   $smarty     Reference to the Smarty object
 * @return   string   the charset
 */
function smarty_function_additional_header($params, &$smarty)
{
    global $additional_header;
    if(isset($additional_header)) {
	    $return = @implode("\n", $additional_header);
    } else {
        $return = '';
	}

    if (isset($params['assign'])) {
        $smarty->assign($params['assign'], $return);
    } else {
        return $return;
    }
} 

?>