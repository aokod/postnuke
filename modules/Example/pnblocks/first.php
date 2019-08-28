<?php
// $Id: first.php 15323 2005-01-10 14:42:23Z markwest $
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
 * Example Module
 * 
 * The Example module shows how to make a PostNuke module. 
 * It can be copied over to get a basic file structure.
 *
 * Purpose of file:  administration display functions -- 
 *                   This file contains all administrative GUI functions 
 *                   for the module
 *
 * @package      PostNuke_Miscellaneous_Modules
 * @subpackage   Example
 * @version      $Id: first.php 15323 2005-01-10 14:42:23Z markwest $
 * @author       Jim McDonald
 * @author       The PostNuke Development Team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 


/**
 * initialise block
 * 
 * @author       The PostNuke Development Team
 */
function Example_firstblock_init()
{
    // Security
    pnSecAddSchema('Example:Firstblock:', 'Block title::');
}

/**
 * get information on block
 * 
 * @author       The PostNuke Development Team
 * @return       array       The block information
 */
function Example_firstblock_info()
{
    return array('text_type'      => 'First',
                 'module'         => 'Example',
                 'text_type_long' => 'Show first example items (alphabetical)',
                 'allow_multiple' => true,
                 'form_content'   => false,
                 'form_refresh'   => false,
                 'show_preview'   => true);
}

/**
 * display block
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the rendered bock
 */
function Example_firstblock_display($blockinfo)
{
    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing.  
	// Note that we have Example:Firstblock: as the component.
    if (!pnSecAuthAction(0,
                         'Example:Firstblock:',
                         "$blockinfo[title]::",
                         ACCESS_READ)) {
        return false;
    }

    // Get variables from content block
    $vars = pnBlockVarsFromContent($blockinfo['content']);

    // Defaults
    if (empty($vars['numitems'])) {
        $vars['numitems'] = 5;
    }

	// Check if the Example module is available. 
	if (!pnModAvailable('Example')) {
		return false;
	}

    // Call the modules API to get the items
    $items = pnModAPIFunc('Example', 
                          'user',  
                          'getall');	

	// Check for no items returned
	if (empty($items)) {
	    return;
	}

    // Call the modules API to get the numitems
    $countitems = pnModAPIFunc('Example', 
                          'user',  
                          'countitems');	
						  
    // Compare the numitems with the blocksetting
    if ($countitems <= $vars['numitems']) {
	    $vars['numitems'] = $countitems;
    }    
		
    // Create output object
	// Note that for a block the corresponding module must be passed.
	$pnRender =& new pnRender('Example');
	
    // Display each item, permissions permitting
	$shown_results = 0;
	$exampleitems = array();
	foreach ($items as $item) {

        if (pnSecAuthAction(0,
                            'Example::',
                            "$item[itemname]::$item[tid]",
                            ACCESS_OVERVIEW)) {
			$shown_results++;
			if ($shown_results <= $vars['numitems']) {
	            if (pnSecAuthAction(0,
    	                            'Example::',
        	                        "$item[itemname]::$item[tid]",
            	                    ACCESS_READ)) {
		        	$exampleitems[] = array('url'   => pnModURL('Example', 'user', 'display', array('tid' => $item['tid'])),
				    	                    'title' => $item['itemname']);
            	} else {
			    	$exampleitems[] = array('title' => $item['itemname']);
            	}
			}
        }
    }
    $pnRender->assign('items', $exampleitems);
	
    // Populate block info and pass to theme
    $blockinfo['content'] = $pnRender->fetch('example_block_first.htm');

    return themesideblock($blockinfo);
}


/**
 * modify block settings
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the bock form
 */
function Example_firstblock_modify($blockinfo)
{
    // Get current content
    $vars = pnBlockVarsFromContent($blockinfo['content']);

    // Defaults
    if (empty($vars['numitems'])) {
        $vars['numitems'] = 5;
    }

    // Create output object
	$pnRender =& new pnRender('Example');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

    // assign the approriate values
	$pnRender->assign('numitems', $vars['numitems']);

    // Return the output that has been generated by this function
	return $pnRender->fetch('example_block_first_modify.htm');
}


/**
 * update block settings
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       $blockinfo  the modified blockinfo structure
 */
function Example_firstblock_update($blockinfo)
{
    // Get current content
    $vars = pnBlockVarsFromContent($blockinfo['content']);
	
	// alter the corresponding variable
    $vars['numitems'] = pnVarCleanFromInput('numitems');
	
	// write back the new contents
    $blockinfo['content'] = pnBlockVarsToContent($vars);

	// clear the block cache
	$pnRender =& new pnRender('Example');
	$pnRender->clear_cache('example_block_first.htm');
	
    return $blockinfo;
}

?>