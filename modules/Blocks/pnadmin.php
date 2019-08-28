<?php 
// $Id: pnadmin.php 20250 2006-10-09 12:00:13Z markwest $
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

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Jim McDonald
// Purpose of file: Block administration
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Blocks
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * the main administration function
 * This function is the default function, and is called whenever the
 * module is initiated without defining arguments.  As such it can
 * be used for a number of things, but most commonly it either just
 * shows the module menu and returns or calls whatever the module
 * designer feels should be the default function (often this is the
 * view() function)
 * @author Jim McDonald
 * @return string HTML output string
 */
function blocks_admin_main()
{
    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Blocks::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    } 

    pnRedirect(pnModURL('Blocks', 'admin', 'view'));
    return true;
} 

/**
 * View all blocks
 * @author Jim McDonald
 * @return string HTML output string
 */
function blocks_admin_view()
{
    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Blocks::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    } 

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
	$pnRender =& new pnRender('Blocks');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

	// generate an authorisation key for the links
    $authid = pnSecGenAuthKey();

	// set some default variables
    $rownum = 1;
    $lastpos = '';

    // The user API function is called.  This takes the number of items
    // required and the first number in the list of all items, which we
    // obtained from the input and gets us the information on the appropriate
    // items.
    $blocks = pnModAPIFunc('Blocks', 'user', 'getall');

	// we can easily count the number of blocks using count() rather than
	// calling the api function
	$numrows = count($blocks);

	// create an empty arrow to hold the processed items
	$blockitems = array();

	// loop round each item calculating the additional information
	foreach ($blocks as $key => $block) {

        // Sneaky lookahead
        if (!isset($blocks[$key+1]['position'])) {
            $nextpos = '';
        } else {
            $nextpos = $blocks[$key+1]['position'];
        }

        switch ($rownum) {
            case 1:
                if ($nextpos != $block['position']) {
                    $arrows['up'] = 0;
                    $arrows['down'] = 0;

                } else {
                    $arrows['up'] = 0;
                    $arrows['down'] = 1;
                } 
                break;
            case $numrows:
                if ($lastpos != $block['position']) {
                    $arrows['up'] = 0;
                    $arrows['down'] = 0;
                } else {
                    $arrows['up'] = 1;
                    $arrows['down'] = 0;

                } 
                break;
            default: 
                // Sneaky bit of lookahead here...
                if ($blocks[$key+1]['position'] != $block['position']) {
                    $arrows['up'] = 1;
                    $arrows['down'] = 0;
                } elseif ($block['position'] != $lastpos) {
                    $arrows['up'] = 0;
                    $arrows['down'] = 1;
                } else {
                    $arrows['up'] = 1;
                    $arrows['down'] = 1;
                } 
                break;
        } 
        $rownum++;
        $lastpos = $block['position'];

		// set the block position language define
        switch ($block['position']) {
            case 'l':
                $pos = _LEFT;
                break;
            case 'r':
                $pos = _RIGHT;
                break;
            case 'c':
                $pos = _CENTRE;
                break;
        } 

		// set the module that holds the block
        if ($block['mid'] == 0) {
            $modname = _CORE;
        } else {
            $modinfo = pnModGetInfo($block['mid']);
            $modname = $modinfo['name'];
        } 

		// set the blocks language
        if (empty($block['blanguage'])) {
		    $language = _ALL;
        } else {
            $language = $block['blanguage'];
        } 

		// calculate what options the user has over this block
        $options = array();
        if ($block['active']) {
            $state = _ACTIVE;
			$stateimage = 'green_dot.gif';
            $options[] = array('url' => pnModURL('Blocks', 'admin', 'deactivate',  array('bid' => $block['bid'], 'authid' => $authid)),
			                   'title' => _DEACTIVATE);
        } else {
            $state = _INACTIVE;
			$stateimage = 'red_dot.gif';
            $options[] = array ('url' => pnModURL('Blocks', 'admin', 'activate', array('bid' => $block['bid'], 'authid' => $authid)),
                                'title' => _ACTIVATE);
        } 

        $options[] = array('url' => pnModURL('Blocks', 'admin', 'modify', array('bid' => $block['bid'])),
		                   'title' => _EDIT);
        $options[] = array('url' => pnModURL('Blocks', 'admin', 'delete', array('bid' => $block['bid'])),
                           'title' => _DELETE);

        $blocksitems[] = array('state' => $state,
		                  'stateimage' => $stateimage,
						  'modname' => $modname,
						  'postion' => $pos,
						  'title' => $block['title'],
						  'bkey' => $block['bkey'],
						  'language' => $language,
						  'options' => $options,
						  'arrows' => $arrows,
						  'upurl' => pnModURL('Blocks', 'admin', 'inc', array('bid' => $block['bid'], 'authid' => $authid)),
						  'downurl' => pnModURL('Blocks', 'admin', 'dec', array('bid' => $block['bid'], 'authid' => $authid)));

    } 
	$pnRender->assign('blocks', $blocksitems);

    // Return the output that has been generated by this function
    return $pnRender->fetch('blocks_admin_view.htm');

} 

/**
 * show all blocks
 * @author Jim McDonald
 * @return string HTML output string
 */
function blocks_admin_showall()
{
    pnSessionSetVar('blocks_show_all', 1);
    pnRedirect(pnModURL('Blocks', 'admin', 'view'));
    return true;
} 

/**
 * show active blocks
 * @author Jim McDonald
 * @return string HTML output string
 */
function blocks_admin_showactive()
{
    pnSessionDelVar('blocks_show_all');
    pnRedirect(pnModURL('Blocks', 'admin', 'view'));
    return true;
} 

/**
 * increment position for a block
 * @author Jim McDonald 
 * @param int $bid block id
 * @return string HTML output string
 */
function blocks_admin_inc()
{ 
    // Get parameters
    $bid = pnVarCleanFromInput('bid'); 

    // Confirm authorisation code
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Blocks', 'admin', 'view'));
        return true;
    } 

    // Pass to API
    if (pnModAPIFunc('Blocks', 'admin', 'inc', array('bid' => $bid))) {
        // Success
        pnSessionSetVar('statusmsg', _BLOCKHIGHER);
    } 

    // Redirect
    pnRedirect(pnModURL('Blocks', 'admin', 'view'));
    return true;
} 

/**
 * deactivate a block
 * @author Jim McDonald
 * @param int $bid block id
 * @return string HTML output string
 */
function blocks_admin_deactivate()
{ 
    // Get parameters
    $bid = pnVarCleanFromInput('bid'); 

    // Confirm authorisation code
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Blocks', 'admin', 'view'));
        return true;
    } 

    // Pass to API
    if (pnModAPIFunc('Blocks', 'admin', 'deactivate', array('bid' => $bid))) {
        // Success
        pnSessionSetVar('statusmsg', _BLOCKDEACTIVATED);
    } 

    // Redirect
    pnRedirect(pnModURL('Blocks', 'admin', 'view'));
    return true;
} 

/**
 * activate a block
 * @author Jim McDonald
 * @param int $bid block id
 * @return string HTML output string
 */
function blocks_admin_activate()
{ 
    // Get parameters
    $bid = pnVarCleanFromInput('bid'); 

    // Confirm authorisation code
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Blocks', 'admin', 'view'));
        return true;
    } 

    // Pass to API
    if (pnModAPIFunc('Blocks', 'admin', 'activate', array('bid' => $bid))) {
        // Success
        pnSessionSetVar('statusmsg', _BLOCKACTIVATED);
    } 

    // Redirect
    pnRedirect(pnModURL('Blocks', 'admin', 'view'));
    return true;
} 

/**
 * decrement position for a block
 * @author Jim McDonald
 * @param int $bid block id
 * @return string HTML output string
 */
function blocks_admin_dec()
{ 
    // Get parameters
    $bid = pnVarCleanFromInput('bid'); 

    // Confirm authorisation code
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Blocks', 'admin', 'view'));
        return true;
    } 

    // Pass to API
    if (pnModAPIFunc('Blocks', 'admin', 'dec', array('bid' => $bid))) {
        // Success
        pnSessionSetVar('statusmsg', _BLOCKLOWER);
    } 

    // Redirect
    pnRedirect(pnModURL('Blocks', 'admin', 'view'));
    return true;
} 

/**
 * modify a block
 * @author Jim McDonald
 * @param int $bid block ind
 * @return string HTML output string
 */
function blocks_admin_modify()
{
    // Get parameters
    $bid = pnVarCleanFromInput('bid');

    // Get details on current block
    $blockinfo = pnBlockGetInfo($bid);

    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Blocks::', "$blockinfo[bkey]:$blockinfo[title]:$bid", ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

	// check the blockinfo array
    if (empty($blockinfo)) {
        pnSessionSetVar('errormsg', _NOSUCHBLOCK);
        pnRedirect(pnModURL('Blocks', 'admin', 'view'));
        return true;
    } 

    // Load block
    $modinfo = pnModGetInfo($blockinfo['mid']);
    if (!pnBlockLoad($modinfo['name'], $blockinfo['bkey'])) {
        return pnVarPrepHTMLDisplay(_NOSUCHBLOCK);
    } 

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
	$pnRender =& new pnRender('Blocks');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

    // Title - putting a title ad the head of each page reminds the user what
    // they are doing
    if (!empty($modinfo['name'])) {
        $pnRender->assign('modtitle', "$modinfo[name]/$blockinfo[bkey]");
    } else {
        $pnRender->assign('modtitle', "Core/$blockinfo[bkey]");
    } 

    // Add hidden block id to form
    $pnRender->assign('bid', $bid);

    // Title
    $pnRender->assign('blocktitle', $blockinfo['title']);

    // Position
    $pnRender->assign('blockposition', $blockinfo['position']); //position
	$pnRender->assign('blockpositions', array('l' => pnVarPrepForDisplay(_LEFT),
                                        'r' => pnVarPrepForDisplay(_RIGHT),
										'c' => pnVarPrepForDisplay(_CENTRE)));

    // Collapsable
    $pnRender->assign('blockcollapsable', $blockinfo['collapsable']);

    // Collapsable
    $pnRender->assign('blockdefaultstate', $blockinfo['defaultstate']);

    // Language
    $pnRender->assign('blocklanguage', $blockinfo['language']);
	$pnRender->assign('languages', languagelist());

    // Block-specific

    // New way
    $usname = preg_replace('/ /', '_', $modinfo['name']);
    $modfunc = $usname . '_' . $blockinfo['bkey'] . 'block_modify';
	$blockoutput = '';
    if (function_exists($modfunc)) {
        $blockoutput = $modfunc($blockinfo);
    } else {
        // Old way
        $blocks_modules = $GLOBALS['blocks_modules'][$blockinfo['mid']];
        if (!empty($blocks_modules[$blockinfo['bkey']]) && !empty($blocks_modules[$blockinfo['bkey']]['func_edit'])) {
            if (function_exists($blocks_modules[$blockinfo['bkey']]['func_edit'])) {
                $blockoutput = $blocks_modules[$blockinfo['bkey']]['func_edit'](array_merge($_GET, $_POST, $blockinfo));
            } 
        } 
    } 
    $pnRender->assign('blockoutput', $blockoutput);

    // If no block-specific just allow them to edit content
    if (!empty($blocks_modules[$blockinfo['bkey']]) && ($blocks_modules[$blockinfo['bkey']]['form_content'] == true)) {
	    $pnRender->assign('blockcontentflag', 1);
        $pnRender->assign('blockcontent', $blockinfo['content']); // content
    } else {
	    $pnRender->assign('blockcontentflag', 0);
	}

    // Refresh
    $refreshtimes = array( 1800 => pnVarPrepForDisplay(_BLOCKSHALFHOUR),
                           3600 => pnVarPrepForDisplay(_BLOCKSHOUR),
                           7200 => pnVarPrepForDisplay(_BLOCKSTWOHOURS),
                          14400 => pnVarPrepForDisplay(_BLOCKSFOURHOURS),
                          43200 => pnVarPrepForDisplay(_BLOCKSTWELVEHOURS),
                          86400 => pnVarPrepForDisplay(_BLOCKSONEDAY));
    $pnRender->assign('blockrefreshtimes' , $refreshtimes);
    $pnRender->assign('blockrefreshtime', pnVarPrepForDisplay($blockinfo['refresh']));

    // Return the output that has been generated by this function
    return $pnRender->fetch('blocks_admin_modify.htm');

} 

/**
 * update a block
 * @author Jim McDonald
 * @see blocks_admin_modify()
 * @param int $bid block id to update
 * @param string $title the new title of the block
 * @param string $position the new position of the block
 * @param string $url the new URL of the block
 * @param string $language the new language of the block
 * @param string $content the new content of the block
 * @return bool true if succesful, false otherwise
 */
function blocks_admin_update()
{
    // Get parameters
    list($bid,
        $title,
        $language,
        $collapsable,
        $defaultstate,
        $content,
        $refresh,
        $position) = pnVarCleanFromInput('bid',
        'title',
        'language',
        'collapsable',
		'defaultstate',
        'content',
        'refresh',
        'position'); 

    // Fix for null language
    if (!isset($language)) {
        $language = '';
    } 

    // Confirm authorisation code
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Blocks', 'admin', 'view'));
        return true;
    } 

    // Get and update block info
    $blockinfo = pnBlockGetInfo($bid);
    $blockinfo['title'] = $title;
    $blockinfo['bid'] = $bid;
    $blockinfo['language'] = $language;
    $blockinfo['collapsable'] = $collapsable;
    $blockinfo['defaultstate'] = $defaultstate;
    $blockinfo['content'] = $content;
    $blockinfo['refresh'] = $refresh;
    if ($blockinfo['position'] != $position) {
        // Moved position - try to keep weight (not that it means much)
        $blockinfo['weight'] += 0.5;
        $resequence = 1;
    } 
    $blockinfo['position'] = $position; 

    // Load block
    $modinfo = pnModGetInfo($blockinfo['mid']);
    if (!pnBlockLoad($modinfo['name'], $blockinfo['bkey'])) {
		pnSessionSetVar('errormsg', _NOSUCHBLOCK);
		pnRedirect(pnModURL('Blocks', 'admin', 'view'));
		return true;
    } 

    // Do block-specific update
    $usname = preg_replace('/ /', '_', $modinfo['name']);
    $updatefunc = $usname . '_' . $blockinfo['bkey'] . 'block_update';
    if (function_exists($updatefunc)) {
        $blockinfo = $updatefunc($blockinfo);
		if (!$blockinfo) {
			pnRedirect(pnModURL('Blocks', 'admin', 'modify', array('bid' => $bid)));
			return false;
		}
    } else {
        // Old way
        $blocks_modules = $GLOBALS['blocks_modules'][$blockinfo['mid']];
        if (!empty($blocks_modules[$blockinfo['bkey']]) && !empty($blocks_modules[$blockinfo['bkey']]['func_update'])) {
            if (function_exists($blocks_modules[$blockinfo['bkey']]['func_update'])) {
                $blockinfo = $blocks_modules[$blockinfo['bkey']]['func_update'](array_merge($_POST, $blockinfo));
            } 
        } 
    } 

    // Pass to API
    if (pnModAPIFunc('Blocks',
            'admin',
            'update',
            $blockinfo)) {
        // Success
        pnSessionSetVar('statusmsg', _UPDATEDBLOCK);

        if (!empty($resequence)) {
            // Also need to resequence
            pnModAPIFunc('Blocks', 'admin', 'resequence');
        } 
    } 

    pnRedirect(pnModURL('Blocks', 'admin', 'view'));

    return true;
} 

/**
 * display form for a new block
 * @author Jim McDonald
 * @return string HTML output string
 */
function blocks_admin_new()
{
    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Blocks::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
	$pnRender =& new pnRender('Blocks');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

    // Block
    // Load all blocks (trickier than it sounds)
    $blocks = pnBlockLoadAll();
    if (!$blocks) {
        return pnVarPrepHTMLDisplay(_BLOCKLOADFAILED);
    } 

    $blockinfo = array();
    foreach ($blocks as $moduleblocks) {
		foreach ($moduleblocks as $block) {
	        $blockinfo[$block['mid'] . ':' . $block['bkey']] =  $block['module'] . '/' . $block['text_type_long'];
		}
    } 
    $pnRender->assign('blockids', $blockinfo);

    // Language
    $pnRender->assign('blocklanguages', languagelist());

    // Return the output that has been generated by this function
    return $pnRender->fetch('blocks_admin_new.htm');

} 

/**
 * create a new block
 * @author Jim McDonald
 * @see blocks_admin_new()
 * @param string $title the new title of the block
 * @param int $blockid block id to create
 * @param string $language the language to assign to the block
 * @param string $position the position of the block
 * @return bool true if successful, false otherwise
 */
function blocks_admin_create()
{ 
    // Get parameters
    list($title,
        $blockid,
        $language,
        $collapsable,
        $defaultstate,
        $position) = pnVarCleanFromInput('title',
        'blockid',
        'language',
        'collapsable',
        'defaultstate',
        'position');

    list($mid, $bkey) = split(':', $blockid); 

    // Fix for null language
    if (!isset($language)) {
        $language = '';
    } 

    // Confirm authorisation code
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Blocks', 'admin', 'view'));
        return true;
    } 

    // Pass to API
    $bid = pnModAPIFunc('Blocks',
        'admin',
        'create', array('bkey' => $bkey,
            'title' => $title,
            'mid' => $mid,
            'language' => $language,
            'collapsable' => $collapsable,
            'defaultstate' => $defaultstate,
            'position' => $position));
    if ($bid != false) {
        // Success
        pnSessionSetVar('statusmsg', _BLOCKCREATED); 

        // Send to modify page to update block specifics
        pnRedirect(pnModURL('Blocks', 'admin', 'modify', array('bid' => $bid)));

        return true;
    } 

    pnRedirect(pnModURL('Blocks', 'admin', 'view'));

    return true;
} 

/**
 * delete a block
 * @author Jim McDonald
 * @param int bid the block id
 * @param bool confirm to delete block
 * @return string HTML output string
 */
function blocks_admin_delete()
{ 
    // Get parameters
    list($bid, $confirmation) = pnVarCleanFromInput('bid', 'confirmation'); 

	// Get details on current block
	$blockinfo = pnBlockGetInfo($bid);

	// Security check - important to do this as early as possible to avoid
	// potential security holes or just too much wasted processing
	if (!pnSecAuthAction(0, 'Blocks::', "$blockinfo[bkey]:$blockinfo[title]:$bid", ACCESS_DELETE)) {
		return pnVarPrepHTMLDisplay(_MODULENOAUTH);
	}

    if ($blockinfo == false) {
        return pnVarPrepHTMLDisplay(_NOSUCHBLOCK);
    }

    // Check for confirmation
    if (empty($confirmation)) {
	
        // No confirmation yet - get one

        // Create output object - this object will store all of our output so that
        // we can return it easily when required
    	$pnRender =& new pnRender('Blocks');

	    // As Admin output changes often, we do not want caching.
    	$pnRender->caching = false;

		// get the module info
        $modinfo = pnModGetInfo($blockinfo['mid']);

        if (!empty($modinfo['name'])) {
            $pnRender->assign('blockname', "$modinfo[name]/$blockinfo[bkey]");
        } else {
            $pnRender->assign('blockname', "Core/$blockinfo[bkey]");
        } 

		// add the block id
        $pnRender->assign('bid', $bid);

        // Return the output that has been generated by this function
        return $pnRender->fetch('blocks_admin_delete.htm');
    } 

    // Confirm authorisation code
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Blocks', 'admin', 'view'));
        return true;
    } 

    // Pass to API
    if (pnModAPIFunc('Blocks',
            'admin',
            'delete', array('bid' => $bid))) {
        // Success
        pnSessionSetVar('statusmsg', _BLOCKDELETED);

    } 

    pnRedirect(pnModURL('Blocks', 'admin', 'view'));

    return true;
} 

/**
 * Any config options would likely go here in the future
 * @author Jim McDonald
 * @return string HTML output string
 */
function blocks_admin_modifyconfig()
{
    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Blocks::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
	$pnRender =& new pnRender('Blocks');

	// As Admin output changes often, we do not want caching.
	$pnRender->caching = false;

    // Collapsable blocks
    $pnRender->assign(pnModGetVar('Blocks'));

    // Return the output that has been generated by this function
    return $pnRender->fetch('blocks_admin_modifyconfig.htm');

} 
/**
 * Set config variable(s)
 * @author Jim McDonald
 * @return string bool true if successful, false otherwise
 */
function blocks_admin_updateconfig()
{
    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Blocks::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    } 

    $collapseable = pnVarCleanFromInput('collapseable');

    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errmsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Blocks', 'admin', 'main'));
        return true;
    } 

	if (!isset($collapseable) || !is_numeric($collapseable)) {
		$collapseable = 0;
	}
	pnModSetVar('Blocks', 'collapseable', $collapseable);

    // Let any other modules know that the modules configuration has been updated
    pnModCallHooks('module','updateconfig','Blocks', array('module' => 'Blocks'));

	// the module configuration has been updated successfuly
	pnSessionSetVar('statusmsg', _CONFIGUPDATED);

    pnRedirect(pnModURL('Blocks', 'admin', 'main'));

    return true;
} 

?>