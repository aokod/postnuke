<?php
// $Id: function.lang.php 20253 2006-10-09 12:08:06Z markwest $
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
 * @version        $Id: function.lang.php 20253 2006-10-09 12:08:06Z markwest $
 * @author         The PostNuke development team
 * @link           http://www.postnuke.com The PostNuke Home Page
 * @copyright      Copyright (C) 2004 by the PostNuke Development Team
 * @license        http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

/**
 * Smarty function to get the site's language
 *
 * available parameters:
 *  - assign      if set, the language will be assigned to this variable
 *
 * Example
 * <html lang="<!--[lang]-->">
 *
 * @author   Jörg Napp
 * @since    03. Feb. 04
 * @param    array    $params     All attributes passed to this function from the template
 * @param    object   $smarty     Reference to the Smarty object
 * @return   string   the language
 * @todo     Check if the language should be determined this way
 */
function smarty_function_lang($params, &$smarty)
{
    return pnVarPrepForDisplay(_LOCALE);
}

?>