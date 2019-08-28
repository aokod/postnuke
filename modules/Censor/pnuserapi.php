<?php
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
// Original Author of file: Mark West
// Purpose of file:  Censor user API functions
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Censor
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * Censor transform hook
 * @author Mark West
 * @return mixed 
 */
function Censor_userapi_transform($args) {

    extract($args);

    // Argument check
    if ((!isset($objectid)) ||
        (!isset($extrainfo))) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return;
    }

    if (is_array($extrainfo)) {
        foreach ($extrainfo as $text) {
            $result[] = Censor_transform($text);
        }
    } else {
        $result = Censor_transform($text);
    }

    return $result;

}

/**
 * Censor transform
 * @author Mark West
 * @return string 
 */
function Censor_transform($text) {

    // Argument check
    if (!isset($text)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return;
    }

    static $docensor;
    if (!isset($docensor)) {
        $docensor = pnConfigGetVar('CensorMode');
    } 

    static $search = array();
    if (empty($search)) {
        $repsearch = array('/o/i',
            '/e/i',
            '/a/i',
            '/i/i');
        $repreplace = array('0',
            '3',
            '@',
            '1');
        $censoredwords = pnConfigGetVar('CensorList');
        foreach ($censoredwords as $censoredword) {
            // Simple word
            $search[] = "/\b$censoredword\b/i"; 
            // Common replacements
            $mungedword = preg_replace($repsearch, $repreplace, $censoredword);
            if ($mungedword != $censoredword) {
                $search[] = "/\b$mungedword\b/";
            } 
        }
    } 

    $replace = pnConfigGetVar('CensorReplace');

    $message = preg_replace($search, $replace, $text);
	
	return $message;

}
?>