<?php
// $Id: pnRender.php 16227 2005-05-11 17:22:18Z landseer $
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
/**
 * pnRender - PostNuke wrapper class for Smarty
 *
 * Display a pnRender block
 *
 * @author      PostNuke development team
 * @version     .7/.8
 * @link        http://www.post-nuke.net              PostNuke home page
 * @link        http://smarty.php.net                 Smarty home page
 * @license     http://www.gnu.org/copyleft/gpl.html  GNU General Public License
 * @package     Xanthia_Templating_Environment
 * @subpackage  pnRender
 */


/**
 * initialise block
 *
 * @author    Frank Schummertz
 * @author    Jörg Napp
 * @version   $Revision: 16227 $
 */
function pnRender_pnRenderblock_init()
{
    // Security
    pnSecAddSchema('pnRender:pnRenderblock:', 'Block title::');
}


/**
 * Get information on block
 *
 * @author    Frank Schummertz
 * @author    Jörg Napp
 * @version   $Revision: 16227 $
 * @return    array    blockinfo array
 */
function pnRender_pnRenderblock_info()
{
    return array('text_type'      => 'pnRender',
                 'module'         => 'pnRender',
                 'text_type_long' => 'custom pnRender block',
                 'allow_multiple' => true,
                 'form_content'   => false,
                 'form_refresh'   => false,
                 'show_preview'   => true);
}


/**
 * Display the block
 *
 * @author    Frank Schummertz
 * @version   $Revision: 16227 $
 * @param     $row     blockinfo array
 * @return    string   HTML output string
 */
function pnRender_pnRenderblock_display($row)
{
    if (!pnSecAuthAction(0, 'pnRender:pnRenderblock:', "$row[title]::", ACCESS_OVERVIEW)) {
        return;
    }

    // Break out options from our content field
    $vars = pnBlockVarsFromContent($row['content']);

    // Parameter check
    if (!isset($vars['template']) || !isset($vars['module'])) {
        $row['content'] = pnVarPrepHTMLDisplay(_PNRENDERBLOCK_NOBLOCK) ;
        return themesideblock($row);
    }

    // If the module is available we load the user api to ensure that the language
    // defines are present for use inside of the block. If we do not do this, the user
    // will see _THEDEFINES only
    // If the module is not available we show an error messages.
    if( (!pnModAvailable($vars['module'])) || (!pnModAPILoad($vars['module'], 'user')) ) {
        $row['content'] = pnVarPrepHTMLDisplay(_PNRENDERBLOCK_NOMODULE) . $vars['module'];
        return themesideblock($row);
    }

    $pnRender =& new pnRender($vars['module']);
    $pnRender->caching = false;

    // Get the additional parameters and assign them
    $params = split( ";", trim($vars['parameters']));
    if(is_array($params) && count($params) > 0 ) {
        foreach($params as $param) {
            if(!empty($param)) {
                $assign = split('=', trim($param));
                if(is_array($assign) && count($assign)>0 ) {
                    $pnRender->assign(trim($assign[0]), trim($assign[1]));
                }
            }
        }
    }

    $row['content'] = $pnRender->fetch($vars['template']);

    return themesideblock($row);
}


/**
 * Update the block
 *
 * @author    Frank Schummertz
 * @author    Jörg Napp
 * @version   $Revision: 16227 $
 * @param     $row     old blockinfo array
 * @return    array    new blockinfo array
 */
function pnRender_pnRenderblock_update($row)
{
    if (!pnSecAuthAction(0, 'pnRender:pnRenderblock:', "$row[title]::", ACCESS_ADMIN)) {
        return false;
    }
    list($module,
         $template,
         $parameters) = pnVarCleanFromInput('pnrmodule',
                                            'pnrtemplate',
                                            'pnrparameters');

    $row['content'] = pnBlockVarsToContent(compact('module', 'template', 'parameters' ));
    return($row);
}


/**
 * Modify the block
 *
 * @author    Frank Schummertz
 * @author    Jörg Napp
 * @version   $Revision: 16227 $
 * @param     $row     blockinfo array
 * @return    string   HTML output string
 */
function pnRender_pnRenderblock_modify($row)
{
    if (!pnSecAuthAction(0, 'pnRender:pnRenderblock:', "$row[title]::", ACCESS_ADMIN)) {
        return false;
    }
    // Break out options from our content field
    $vars = pnBlockVarsFromContent($row['content']);

    $pnRender = new pnRender('pnRender');
    $pnRender->caching = false;
    $pnRender->assign($vars);
    return $pnRender->fetch('pnrenderblock.htm');
}

?>