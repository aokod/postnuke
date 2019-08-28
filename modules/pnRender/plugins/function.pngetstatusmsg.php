<?php
// $Id: function.pngetstatusmsg.php 16492 2005-07-24 15:38:24Z jorg $
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
 * @version      $Id: function.pngetstatusmsg.php 16492 2005-07-24 15:38:24Z jorg $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to obtain status message
 * 
 * This function obtains the last status message posted for this session. 
 * The status message exists in one of two session variables: 'statusmsg' for a 
 * status message, or 'errormsg' for an error message. If both a status and an 
 * error message exists then the error message is returned.
 * 
 * This is is a destructive function - it deletes the two session variables 
 * 'statusmsg' and 'erorrmsg' during its operation.
 * 
 * Note that you must not cache the outputs from this function, as its results
 * change aech time it is called. The PostNuke developers are looking for ways to
 * automise this.
 * 
 * 
 * Available parameters:
 *   - assign:   If set, the status message is assigned to the corresponding variable instead of printed out
 *   - style, class: If set, the status message is being put in a div tag with the respective attributes
 *   - tag:      You can specify if you would like a span or a div tag
 * 
 * Example
 *   <!--[pngetstatusmsg|pnvarprephtmldisplay]-->
 *   <!--[pngetstatusmsg style="color:red;" |pnvarprephtmldisplay]-->
 *   <!--[pngetstatusmsg class="statusmessage" tag="span"|pnvarprephtmldisplay]-->
 * 
 * 
 * @author       Jörg Napp
 * @since        16. Sept. 2003
 * @todo         prevent this function from being cached (Smarty 2.6.0)
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the value of the last status message posted, or void if no status message exists
 */
function smarty_function_pngetstatusmsg($params, &$smarty)
{
    extract($params);
    unset($params);

    $statusmsg = pnGetStatusMsg();

    if (isset($assign)) {
        $smarty->assign($assign, $statusmsg);
        return;
    } 

    if ($statusmsg == '' || (!isset($class) && !isset($style) && !isset($tag))) {
        return $statusmsg;
    } 

    // some parameters have been set, so we build the complete tag
    if (!isset($tag) || $tag != 'span') {
        $tag = 'div';
    } 

    $result = "<$tag";

    if (isset($class)) {
        $result .= " class=\"$class\"";
    } 
    if (isset($style)) {
        $result .= " style=\"$style\"";
    } 
    $result .= ">$statusmsg</$tag>";

    return $result;
} 

?>