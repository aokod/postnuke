<?php
// File: $Id: pntables.php 15630 2005-02-04 06:35:42Z jorg $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// Based on:
// Thatware - http://thatware.org/
// PHP-NUKE Web Portal System - http://phpnuke.org/
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
// Original Author of file: Frank Schummertz
// Purpose of file: Table information for the modules module
// ----------------------------------------------------------------------

/**
 * @package PostNuke_System_Modules
 * @subpackage Modules
 * @license http://www.gnu.org/copyleft/gpl.html
*/


/**
 * Populate pntables array for modules module
 * 
 * This function is called internally by the core whenever the module is
 * loaded. It delivers the table information to the core.
 * It can be loaded explicitly using the pnModDBInfoLoad() API function.
 * 
 * @author       Frank Schummertz
 * @return       array       The table information.
 */
function Modules_pntables()
{
    // Initialise table array
    $pntable = array();
    $prefix = pnConfigGetVar('prefix');

    // modules module
    $hooks = $prefix . '_hooks';
    $pntable['hooks'] = $hooks;
    $pntable['hooks_column'] = array ('id'        => $hooks . '.pn_id',
                                      'object'    => $hooks . '.pn_object',
                                      'action'    => $hooks . '.pn_action',
                                      'smodule'   => $hooks . '.pn_smodule',
                                      'stype'     => $hooks . '.pn_stype',
                                      'tarea'     => $hooks . '.pn_tarea',
                                      'tmodule'   => $hooks . '.pn_tmodule',
                                      'ttype'     => $hooks . '.pn_ttype',
                                      'tfunc'     => $hooks . '.pn_tfunc');
    
    $modules = $prefix . '_modules';
    $pntable['modules'] = $modules;
    $pntable['modules_column'] = array ('id'            => $modules . '.pn_id',
                                        'name'          => $modules . '.pn_name',
                                        'type'          => $modules . '.pn_type',
                                        'displayname'   => $modules . '.pn_displayname',
                                        'description'   => $modules . '.pn_description',
                                        'regid'         => $modules . '.pn_regid',
                                        'directory'     => $modules . '.pn_directory',
                                        'version'       => $modules . '.pn_version',
                                        'admin_capable' => $modules . '.pn_admin_capable',
                                        'user_capable'  => $modules . '.pn_user_capable',
                                        'state'         => $modules . '.pn_state');
    
    $module_vars = $prefix . '_module_vars';
    $pntable['module_vars'] = $module_vars;
    $pntable['module_vars_column'] = array ('id'      => $module_vars . '.pn_id',
                                            'modname' => $module_vars . '.pn_modname',
                                            'name'    => $module_vars . '.pn_name',
                                            'value'   => $module_vars . '.pn_value');
    
    return $pntable;
}

?>