<?php
// $Id: pnadmin.php 17524 2006-01-12 13:26:43Z markwest $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2002 by the PostNuke Development Team.
// http://www.postnuke.com/
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
// Original Author of file: Jim McDonald
// Purpose of file:  Autolinks administration display functions
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Utility_Modules
 * @subpackage Autolinks
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * Main Autolinks administration function
 * @author Jim McDonald
 * @return HTML String
 */
function autolinks_admin_main()
{
    // Security check
    if (!pnSecAuthAction(0, 'Autolinks::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
	$pnRender =& new pnRender('Autolinks');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

    // Return the output
    return $pnRender->fetch('autolinks_admin_main.htm');
}

/**
 * display form to create a new autolink
 * @author Jim McDonald
 * @return HTML string
 */
function autolinks_admin_new()
{
    // Security check
    if (!pnSecAuthAction(0, 'Autolinks::', '::', ACCESS_ADD)) {
        return pnVarPrepHTMLDisplay(pnVarPrepForDisplay(_MODULENOAUTH));
    }

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
	$pnRender =& new pnRender('Autolinks');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

    // Return the output
    return $pnRender->fetch('autolinks_admin_new.htm');
}

/**
 * This is a standard function that is called with the results of the
 * form supplied by autolinks_admin_new() to create a new item
 * @author Jim McDonald
 * @param 'keyword' the keyword of the link to be created
 * @param 'title' the title of the link to be created
 * @param 'url' the url of the link to be created
 * @param 'comment' the comment of the link to be created
 * @return true if autolink created successfully, false otherwise
 */
function autolinks_admin_create($args)
{
    // Get parameters from whatever input we need
    list($keyword,
         $title,
         $url,
         $comment) = pnVarCleanFromInput('keyword',
                                         'title',
                                         'url',
                                         'comment');

    extract($args);

    // Confirm authorisation code.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Autolinks', 'admin', 'view'));
        return true;
    }

    // Check arguments
    if (empty($keyword)) {
        return pnVarPrepHTMLDisplay(_AUTOLINKSKEYWORDEMPTY);
    }
    if (empty($title)) {
        return pnVarPrepHTMLDisplay(_AUTOLINKSTITLEEMPTY);
    }
    if (empty($url)) {
        return pnVarPrepHTMLDisplay(_AUTOLINKSURLEMPTY);
    }

    // The API function is called
    $lid = pnModAPIFunc('Autolinks',
                        'admin',
                        'create',
                        array('keyword' => $keyword,
                              'title' => $title,
                              'url' => $url,
                              'comment' => $comment));

    if ($lid != false) {
        // Success
        pnSessionSetVar('statusmsg', _AUTOLINKSCREATED);
    }

    pnRedirect(pnModURL('Autolinks', 'admin', 'view'));

    // Return
    return true;
}

/**
 * Display form to modify an autolink
 * @author Jim McDonald
 * @param 'lid' the id of the link to be modified
 * @param 'obid' optional generic object id - gets mapped to lid if present
 * @return HTML output string
 */
function autolinks_admin_modify($args)
{
    // Get parameters from whatever input we need
    list($lid,
         $obid)= pnVarCleanFromInput('lid',
                                     'obid');

    extract($args);

    if (!empty($obid)) {
        $lid = $obid;
    }                       

    $link = pnModAPIFunc('Autolinks',
                         'user',
                         'get',
                         array('lid' => $lid));

    if ($link == false) {
        return pnVarPrepHTMLDisplay(_AUTOLINKSNOSUCHLINK);
    }

    // Security check
    if (!pnSecAuthAction(0, 'Autolinks::Item', "$link[keyword]::$lid", ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
	$pnRender =& new pnRender('Autolinks');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

    // Assign the item
	$pnRender->assign('item', $link);

    // Return the output that has been generated by this function
    return $pnRender->fetch('autolinks_admin_modify.htm');
}


/**
 * This is a standard function that is called with the results of the
 * form supplied by autolinks_admin_modify() to update a current item
 * @author Jim McDonald
 * @param 'lid' the id of the link to be updated
 * @param 'objectid' generic object id - gets mapped to 'lid' if present
 * @param 'keyword' the keyword of the link to be updated
 * @param 'title' the title of the link to be updated
 * @param 'url' the url of the link to be updated
 * @param 'comment' the comment of the link to be updated
 * @return true if autolink updated succesfully, false otherwise
 */
function autolinks_admin_update($args)
{
    // Get parameters from whatever input we need
    list($lid,
         $obid,
         $keyword,
         $title,
         $url,
         $comment) = pnVarCleanFromInput('lid',
                                         'obid',
                                         'keyword',
                                         'title',
                                         'url',
                                         'comment');

    extract($args);
                            
    if (!empty($obid)) {
        $lid = $obid;
    }                       

    // Confirm authorisation code
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Autolinks', 'admin', 'view'));
        return true;
    }

    if(pnModAPIFunc('Autolinks',
                    'admin',
                    'update',
                    array('lid' => $lid,
                          'keyword' => $keyword,
                          'title' => $title,
                          'url' => $url,
                          'comment' => $comment))) {
        // Success
        pnSessionSetVar('statusmsg', _AUTOLINKSUPDATED);
    }

    pnRedirect(pnModURL('Autolinks', 'admin', 'view'));

    // Return
    return true;
}

/**
 * delete item
 * @author Jim McDonald
 * @param 'lid' the id of the item to be deleted
 * @param 'objectid' generic object id - gets mapped to 'lid' if present
 * @param 'confirmation' confirmation that this item can be deleted
 * @return HTML string if 'confirmation' is null or true if deleted succesfully, false otherwise
 */
function autolinks_admin_delete($args)
{
    // Get parameters from whatever input we need
    list($lid,
         $obid,
         $confirmation) = pnVarCleanFromInput('lid',
                                              'obid',
                                              'confirmation');
    extract($args);

     if (!empty($obid)) {
         $lid = $obid;
     }                     

    // The user API function is called
    $link = pnModAPIFunc('Autolinks',
                         'user',
                         'get',
                         array('lid' => $lid));

    if ($link == false) {
        return pnVarPrepHTMLDisplay(_AUTOLINKSNOSUCHITEM);
    }

    // Security check
    if (!pnSecAuthAction(0, 'Autolinks::Item', "$link[keyword]::$lid", ACCESS_DELETE)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Check for confirmation. 
    if (empty($confirmation)) {
        // No confirmation yet

        // Create output object - this object will store all of our output so that
        // we can return it easily when required
    	$pnRender =& new pnRender('Autolinks');

	    // As Admin output changes often, we do not want caching.
    	$pnRender->caching = false;

        // Add a hidden variable for the link id
        $pnRender->assign('lid', $lid);

        // Return the output that has been generated by this function
        return $pnRender->fetch('autolinks_admin_delete.htm');
    }

    // If we get here it means that the user has confirmed the action

    // Confirm authorisation code
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Autolinks', 'admin', 'view'));
        return true;
    }

    // The API function is called
    if (pnModAPIFunc('Autolinks',
                     'admin',
                     'delete',
                     array('lid' => $lid))) {
        // Success
        pnSessionSetVar('statusmsg', _AUTOLINKSDELETED);
    }

    pnRedirect(pnModURL('Autolinks', 'admin', 'view'));
    
    // Return
    return true;
}

/**
 * View list of all autolinks
 * @author Jim McDonald
 * @return HTML string
 */
function autolinks_admin_view()
{
    // Security check
    if (!pnSecAuthAction(0, 'Autolinks::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Get parameters from whatever input we need
    $startnum = pnVarCleanFromInput('startnum');

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
	$pnRender =& new pnRender('Autolinks');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

    // The user API function is called
    $links = pnModAPIFunc('Autolinks',
                          'user',
                          'getall',
                          array('startnum' => $startnum,
                                'numitems' => pnModGetVar('Autolinks',
                                                          'itemsperpage')));

    $autolinks = array();
    foreach ($links as $link) {

        if (pnSecAuthAction(0, 'Autolinks::', "$link[keyword]::$link[lid]", ACCESS_READ)) {
    
            // Options for the link
            $options = array();
            if (pnSecAuthAction(0, 'Autolinks::', "$link[keyword]::$link[lid]", ACCESS_EDIT)) {
                $options[] = array('url' => pnModURL('Autolinks', 'admin', 'modify', array('lid' => $link['lid'])),
                                   'title' => _EDIT);
                if (pnSecAuthAction(0, 'Autolinks::', "$link[keyword]::$link[lid]", ACCESS_DELETE)) {
                    $options[] = array('url'=> pnModURL('Autolinks', 'admin', 'delete', array('lid' => $link['lid'])),
                                       'title' => _DELETE);
                }
            }

			$link['options'] = $options;
            $autolinks[] = $link;
        }
    }
    $pnRender->assign('autolinks', $autolinks);

    // Display pager
	$pnRender->assign('pager', array('numitems' => pnModAPIFunc('Autolinks', 'user', 'countitems'),
	                                 'itemsperpage' => pnModGetVar('Autolinks', 'itemsperpage')));

    // Return the output that has been generated by this function
    return $pnRender->fetch('autolinks_admin_view.htm');
}

/**
 * Modify configuration of autolinks
 * @author Jim McDonald
 * @return HTML String
 */
function autolinks_admin_modifyconfig()
{
    // Security check
    if (!pnSecAuthAction(0, 'Autolinks::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
	$pnRender =& new pnRender('Autolinks');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

    // Link everything or just first occurrance
    $pnRender->assign('linkfirst', pnModGetVar('Autolinks', 'linkfirst'));

    // Remove text decoration for links
    $pnRender->assign('invisilinks', pnModGetVar('Autolinks', 'invisilinks'));

    // Open links in a new window
    $pnRender->assign('newwindow', pnModGetVar('Autolinks', 'newwindow'));

    // Items per page in admin view screen
	$pnRender->assign('itemsperpage', pnModGetVar('Autolinks', 'itemsperpage'));

    return $pnRender->fetch('autolinks_admin_modifyconfig.htm');
}

/**
 * Update autolinks configuration
 * @author Jim McDonald
 * @return true if configuration updated successfully, false otherwise
 */
function autolinks_admin_updateconfig()
{
    // Security check
    if (!pnSecAuthAction(0, 'Autolinks::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    list($linkfirst,
         $invisilinks,
		 $itemsperpage,
		 $newwindow)= pnVarCleanFromInput('linkfirst',
                                          'invisilinks',
					                      'itemsperpage',
										  'newwindow');

    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Autolinks', 'admin', 'view'));
        return true;
    }

    if (!isset($linkfirst)) {
        $linkfirst = 0;
    }
    pnModSetVar('Autolinks', 'linkfirst', $linkfirst);

    if (!isset($invisilinks)) {
        $invisilinks = 0;
    }
    pnModSetVar('Autolinks', 'invisilinks', $invisilinks);

    if (!isset($itemsperpage)) {
        $itemsperpage = 20;
    }
    pnModSetVar('Autolinks', 'itemsperpage', $itemsperpage);

    if (!isset($newwindow)) {
        $newwindow = 0;
    }
    pnModSetVar('Autolinks', 'newwindow', $newwindow);

    pnSessionSetVar('statusmsg', _AUTOLINKSUPDATEDCONFIG);

   // Let any other modules know that the modules configuration has been updated
    pnModCallHooks('module','updateconfig','Autolinks', array('module' => 'Autolinks'));

	// the module configuration has been updated successfuly
	pnSessionSetVar('statusmsg', _CONFIGUPDATED);

    pnRedirect(pnModURL('Autolinks', 'admin', 'view'));

    return true;
}

?>