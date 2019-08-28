<?php
// File: $Id: upgrade.php 16811 2005-09-26 20:42:35Z drak $
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
// Original Author of this file: Mark West
// Purpose of this file: Quick upgrade of all modules.
// ----------------------------------------------------------------------

/**
* PostNuke Automated Upgrade Script
* @author Mark West
* @version 1.01
* @copyright Copyright &copy; 2005, Mark West
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*/

/*
* changelog
* 1.0  -     initial version
* 1.01 -     added intro text,
*            set default theme to ExtraLite,
*            added note about additional config.php vars,
*            changed some output to lists,
*            fixed html validation issues
*/
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">'."\n";
echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-us">'."\n";
echo '<head>'."\n";
echo '<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />'."\n";
echo '<title>PostNuke Upgrade script (for versions .760+)</title>'."\n";
echo '<link rel="stylesheet" href="themes/ExtraLite/style/style.css" type="text/css" />';
echo '</head>'."\n";
echo '<body>'."\n";
echo '<h1>PostNuke Upgrade script (for versions .760+)</h1>'."\n";
echo '<p>This script will upgrade postnuke from versions 0.71+ to the most current release. Upgrades from prior releases are not supported by this script.</p>';

// load postnuke core
include 'includes/pnAPI.php';
pnInit();

// get our input
list ($task, $username, $password) = pnVarCleanFromInput('task', 'username', 'password');

// login to supplied admin credentials
if ($task === 'regenerate' || $task === 'upgrade') {
    if (!pnUserLogin($username, $password)) {
        die('Failed to login to your site');
    }
}

switch($task) {
    case 'regenerate':
        // ensure that the default theme is extralite
        pnConfigSetVar('Default_Theme', 'ExtraLite');
        // regenerate the modules list
        pnModAPIFunc('Modules', 'admin', 'regenerate');
        echo 'Modules list regenerated.<br />'."\n";
        // check some config vars in config.php
        echo 'Before proceeding please ensure that the following lines exist in config.php'."\n";
        echo '<ul>'."\n";
        echo '<li>$pnconfig[\'dbtabletype\'] = \'MyISAM\';</li>'."\n";
        echo '<li>$pnconfig[\'pconnect\'] = \'0\';</li>'."\n";
        echo '<li>$pnconfig[\'temp\'] = \'pnTemp\';</li>'."\n";
        echo '<li>$pndebug[\'pagerendertime\'] = 0;</li>'."\n";
        echo '</ul>'."\n";
        echo "<a href=\"upgrade.php?username=$username&amp;password=$password&amp;task=upgrade\">Upgrade all modules.</a>\n";
        break;
    case 'upgrade':
        // get a list of modules needing upgrading
        $newmods = pnModAPIFunc('Modules', 'admin', 'list', array('state' => _PNMODULE_STATE_UPGRADED));
        // upgrade and activate each module
        echo 'Starting upgrade.'."\n";
        echo '<ul>'."\n";
        foreach ($newmods as $newmod) {
            pnModAPIFunc('Modules', 'admin', 'upgrade', array('mid' => $newmod['id']));
            pnModAPIFunc('Modules', 'admin', 'setstate', array('mid' => $newmod['id'], 'state' => _PNMODULE_STATE_ACTIVE));
            echo "<li>$newmod[name] upgraded.</li>";
        }
        echo '</ul>'."\n";
        // regenerate the modules list to pick up any final changes
        pnModAPIFunc('Modules', 'admin', 'regenerate');
        echo 'Finished upgrade - '."\n";
        echo 'Go to <a href="index.php">'.pnVarPrepForDisplay(pnConfigGetVar('sitename')).'</a>.'."\n";
        break;
    default:
        echo '<p>Please provide your admin account credentials</p>'."\n";
        echo '<form action="upgrade.php?task=regenerate" method="post" enctype="application/x-www-form-urlencoded"><div>'."\n";
        echo '<div><label for="username">Username</label> : <input id="username" type="text" name="username" size="50" maxlength="255" /></div>'."\n";
        echo '<div><label for="password">Password</label> : <input id="password" type="password" name="password" size="50" maxlength="255" /></div>'."\n";
        echo '<input name="submit" type="submit" value="Submit" />'."\n";
        echo '</div></form>'."\n";
}
echo '</body>'."\n";
echo '</html>';

?>
