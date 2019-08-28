<?php
// $Id: pnuser.php 15601 2005-02-02 22:53:18Z markwest $
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
// Purpose of file:  AvantGo user display functions
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Content_Modules
 * @subpackage AvantGo
 * @license http://www.gnu.org/copyleft/gpl.html
*/

/**
 * the main user function
 * This function is the default function, and is called whenever the module is
 * initiated without defining arguments.  As such it can be used for a number
 * of things, but most commonly it either just shows the module menu and
 * returns or calls whatever the module designer feels should be the default
 * function (often this is the view() function)
 * @author Mark West 
 * @link http://www.markwest.me.uk
 * @param 'startnum' the starting article numbner
 * @return string HTML string
 * @todo change avantgo api to news api once news module is api compliant
 */
function AvantGo_user_main()
{
    // Get parameters from whatever input we need.  All arguments to this
    // function should be obtained from pnVarCleanFromInput(), getting them
    // from other places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    $startnum = pnVarCleanFromInput('startnum');

    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'AvantGo::', '::', ACCESS_OVERVIEW)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // The API function is called.  The arguments to the function are passed in
    // as their own arguments array
    $items = pnModAPIFunc('AvantGo',
                          'user',
                          'getall',
                          array('startnum' => $startnum,
                                'numitems' => pnModGetVar('AvantGo',
                                                          'itemsperpage')));

    // The return value of the function is checked here, and if the function
    // suceeded then an appropriate message is posted.  Note that if the
    // function did not succeed then the API function should have already
    // posted a failure message so no action is required
    if ($items == false) {
        return pnVarPrepHTMLDisplay(_AVANTGONOARTICLES);
    }

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
	$pnRender =& new pnRender('AvantGo');

    // Loop through each item and display it.  Note the use of pnVarCensor() to
    // remove any words from the name that the administrator has deemed
    // unsuitable for the site
	$storyitems = array();
    foreach ($items as $item) {
        // The API function is called.  The arguments to the function are passed in
        // as their own arguments array
        $cattitle = pnModAPIFunc('AvantGo', 'user', 'getcattitle', array('catid' => $item['catid']));
        $topictitle = pnModAPIFunc('AvantGo', 'user', 'gettopictitle', array('topicid' => $item['topic']));
        if (pnSecAuthAction(0, 'Stories::Story', "$item[aid]:$cattitle:$item[sid]", ACCESS_READ) 
		    && pnSecAuthAction(0, 'Topics::Topic', "$topictitle::$item[topic]", ACCESS_READ)) {	
			if (stristr(_PN_VERSION_NUM, '0.7')) {
			    $item['url'] = 'print.php?sid='.$item['sid'];
			} else {
			    $item['url'] = pnModURL('News', 'user', 'display', array('sid' => $item['sid'], 'theme' => 'Printer'));
			}
        }
		$storyitems[] = $item;
    }
	$pnRender->assign('storyitems', $storyitems);
   
	echo $pnRender->fetch('avantgo_user_main.htm');
	return true;
}

?>