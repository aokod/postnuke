<?php
// $Id: pninit.php 19296 2006-06-26 12:50:51Z markwest $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
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
// Purpose of file:  Initialisation functions for Wiki encoding
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Value_Addons
 * @subpackage Wiki
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * Initialise the Wiki module
 * @author Jim McDonald <Jim@mcdee.net>
 * @link http://www.mcdee.net
 * @return true if init success, false otherwise
 */
function wiki_init()
{
    // Set up module variables
    // these are the authorised links.
    pnModSetVar('Wiki', 'AllowedProtocols', 'http|https|mailto|ftp|news|gopher');
    // an image may be one of these.
    pnModSetVar('Wiki', 'InlineImages', 'png|jpg|gif');
    // if a link is http://something, it can be directed in a new window, or in the same one.
    pnModSetVar('Wiki', 'ExtlinkNewWindow', true);
    pnModSetVar('Wiki', 'IntlinkNewWindow', false);
    // todo: check what this one actually does...
    pnModSetVar('Wiki', 'WithHTML', true);
    // don't touch this one.
    pnModSetVar('Wiki', 'FieldSeparator', "\263");

    // Set up module hooks
    if (!pnModRegisterHook('item',
                           'transform',
                           'API',
                           'Wiki',
                           'user',
                           'transform')) {
        pnSessionSetVar('errormsg', _WIKICOULDNOTREGISTER);
        return false;
    }

    // Initialisation successful
    return true;
}

/**
 * Upgrade the wiki module from an old version
 * @author Jim McDonald <Jim@mcdee.net>
 * @link http://www.mcdee.net
 * @return true if upgrade success, false otherwise
 */
function wiki_upgrade($oldversion)
{
    return true;
}

/**
 * Delete the wiki_ module
 * @author Jim McDonald <Jim@mcdee.net>
 * @link http://www.mcdee.net
 * @return true if delete success, false otherwise
 */
function wiki_delete()
{
    // Remove module hooks
    if (!pnModUnregisterHook('item',
                             'transform',
                             'API',
                             'Wiki',
                             'user',
                             'transform')) {
        pnSessionSetVar('errormsg', _WIKICOULDNOTUNREGISTER);
        return false;
    }

    // Remove module variables
    pnModDelVar('Wiki');

    // Deletion successful
    return true;
}

?>