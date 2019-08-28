<?php
// $Id: displayfeed.php 15327 2005-01-10 15:29:58Z markwest $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
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
// Purpose of file: Show RSS News Feed
// ----------------------------------------------------------------------

/**
 * initialise block
 */
function RSS_displayfeedblock_init()
{
    // Security
    pnSecAddSchema('RSS:NewsFeed:', 'Block title::');
}

/**
 * get information on block
 */
function RSS_displayfeedblock_info()
{
    // Values
    return array('text_type' => 'displayfeed',
                 'module' => 'RSS',
                 'text_type_long' => 'Show RSS News Feed',
                 'allow_multiple' => true,
                 'form_content' => false,
                 'form_refresh' => false,
                 'show_preview' => true);
}

/**
 * display block
 */
function RSS_displayfeedblock_display($blockinfo)
{
    // Security check
    if (!pnSecAuthAction(0,
                         'RSS:NewsFeed:',
                         "$blockinfo[title]::",
                         ACCESS_READ)) {
        return;
    }

    // Get variables from content block
    $vars = pnBlockVarsFromContent($blockinfo['content']);

    // Defaults
    if (empty($vars['feedid'])) {
        $vars['feedid'] = 1;
    }
    if (empty($vars['displayimage'])) {
        $vars['displayimage'] = 0;
    }

    // The API function is called.  The arguments to the function are passed in
    // as their own arguments array
    $item = pnModAPIFunc('RSS',
                         'user',
                         'get',
                         array('fid' => $vars['feedid']));

    // The return value of the function is checked here, and if the function
    // suceeded then an appropriate message is posted.  Note that if the
    // function did not succeed then the API function should have already
    // posted a failure message so no action is required
    if ($item == false) {
        return;
    }

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
	$pnRender =& new pnRender('RSS');

	//  Check if the block is cached
  	if ($pnRender->is_cached('rss_block_displayfeed.htm',$vars['feedid'])) {
    	$blockinfo['content'] = $pnRender->fetch('rss_block_displayfeed.htm',$vars['feedid']);
    	return themesideblock($blockinfo);
  	}

    // The API function is called.  The arguments to the function are passed in
    // as their own arguments array
    $fullfeed = pnModAPIFunc('RSS',
                             'user',
                             'getfeed',
                             array('fid' => $vars['feedid']));

    // Assign bold module var to RSS to we can use this to decide how to
	// display the item
    $pnRender->assign('bold', pnModGetVar('RSS', 'bold'));

	// Show links in a new browser?
	$pnRender->assign('openinnewwindow', pnModGetVar('RSS', 'openinnewwindow'));

    // Display details of the item.  Note the use of pnVarCensor() to remove
    // any words from the name that the administrator has deemed unsuitable for
    // the site.  Also note that a module variable is used here to determine
    // whether not not parts of the item information should be displayed in
    // bold type or not
    $pnRender->assign('namevalue', $item['feedname']);
    $pnRender->assign('numbervalue', $item['url']);
    $pnRender->assign('feed', $fullfeed);
	if (isset($vars['numitems'])) {
        $pnRender->assign('numitems', $vars['numitems']);
	}
	$pnRender->assign('displayimage', $vars['displayimage']);

   // Populate block info and pass to theme
    $blockinfo['content'] = $pnRender->fetch('rss_block_displayfeed.htm',$vars['feedid']);

    return themesideblock($blockinfo);
}

/**
 * modify block settings
 */
function RSS_displayfeedblock_modify($blockinfo)
{
    // Create output object - this object will store all of our output so that
    // we can return it easily when required
    $pnRender =& new pnRender('RSS');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

    // Get current content
    $vars = pnBlockVarsFromContent($blockinfo['content']);

    // Defaults
    if (empty($vars['feedid'])) {
        $vars['feedid'] = 1;
    }
    if (empty($vars['displayimage'])) {
        $vars['displayimage'] = 0;
    }
    if (empty($vars['numitems'])) {
        $vars['numitems'] = -1;
    }

    // The API function is called.  The arguments to the function are passed in
    // as their own arguments array
    $items = pnModAPIFunc('RSS',
                          'user',
                          'getall');

    // create an array for feednames and id's for the template
	$allfeeds = array();
    foreach($items as $item) {
	    $allfeeds[$item['fid']] = $item['feedname'];
	}
    $pnRender->assign('allfeeds', $allfeeds);

    // assign current feed id
    $pnRender->assign('feedid', $vars['feedid']);

    // assign number of items to display
    $pnRender->assign('numitems', $vars['numitems']);

    // assign display feed image flag
    $pnRender->assign('displayimage', $vars['displayimage']);

    // Return output
    return $pnRender->fetch('rss_block_displayfeed_modify.htm');
}

/**
 * update block settings
 */
function RSS_displayfeedblock_update($blockinfo)
{
    $vars['feedid'] = pnVarCleanFromInput('feedid');
    $vars['numitems'] = pnVarCleanFromInput('numitems');
    $vars['displayimage'] = pnVarCleanFromInput('displayimage');

    $blockinfo['content'] = pnBlockVarsToContent($vars);

    return $blockinfo;
}

?>