<?php
// $Id: pnSession.php 20489 2006-11-11 22:01:02Z larsneo $
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
// Purpose of file: Session handling
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Core
 * @subpackage PostNuke_pnAPI
*/
/**
 * Set up session handling
 *
 * Set all PHP options for PostNuke session handling
 */
function pnSessionSetup()
{
    $path = pnGetBaseURI();
    if (empty($path)) {
        $path = '/';
    }

    $host = pnServerGetVar('HTTP_HOST');

    if (strpos($host, ':') !== false) {
        $host = substr($host,0, strpos($host, ':'));
    }
    // PHP configuration variables
    // Stop adding SID to URLs
    ini_set('session.use_trans_sid', 0);
    @ini_set('url_rewriter.tags', '');
    // User-defined save handler
    ini_set('session.save_handler', 'user');
    // How to store data
    ini_set('session.serialize_handler', 'php');
    // Use cookie to store the session ID
    ini_set('session.use_cookies', 1);
    // Name of our cookie
    ini_set('session.name', 'POSTNUKESID');
    // Lifetime of our cookie
    $seclevel = pnConfigGetVar('seclevel');
    switch ($seclevel) {
        case 'High':
            // Session lasts duration of browser
            $lifetime = 0;
            // Referer check
            // ini_set('session.referer_check', "$host$path");
            ini_set('session.referer_check', "$host");
            break;
        case 'Medium':
            // Session lasts set number of days
            $lifetime = pnConfigGetVar('secmeddays') * 86400;
            break;
        case 'Low':
		    default :
            // Session lasts unlimited number of days (well, lots, anyway)
            // (Currently set to 25 years)
            $lifetime = 788940000;
            break;
    }
    ini_set('session.cookie_lifetime', $lifetime);

    // Cookie path
    ini_set('session.cookie_path', $path);
    // Cookie domain
    // only needed for multi-server multisites - adapt as needed
    // $domain = preg_replace('/^[^.]+/','',$host);
    // ini_set('session.cookie_domain', $domain);

    // Garbage collection
    ini_set('session.gc_probability', 1);
    // Inactivity timeout for user sessions
    ini_set('session.gc_maxlifetime', pnConfigGetVar('secinactivemins') * 60);
    // Auto-start session
    ini_set('session.auto_start', 1);
    // Session handlers
    // PHP 5.2 workaround
    if (version_compare(phpversion(), '5.2.0', '>=')) {
        register_shutdown_function('session_write_close');
    }    
    session_set_save_handler('pnSessionOpen',
                             'pnSessionClose',
                             'pnSessionRead',
                             'pnSessionWrite',
                             'pnSessionDestroy',
                             'pnSessionGC');
    return true;
}

/**
 * Session variables here are a bit 'different'.  Because they sit in the
 * global namespace we use a couple of helper functions to give them their
 * own prefix, and also to force users to set new values for them if they
 * require.  This avoids blatant or accidental over-writing of session
 * variables.
 */

/**
 * Get a session variable
 *
 * @param name $ name of the session variable to get
 */
function pnSessionGetVar($name)
{
    if(isset($name) && isset($_SESSION['PNSV' . $name])) {
        return $_SESSION['PNSV' . $name];
    }
    return false;
}

/**
 * Set a session variable
 *
 * @param name $ name of the session variable to set
 * @param value $ value to set the named session variable
 */
function pnSessionSetVar($name, $value='')
{
    if (!isset($name)) {
        return false;
    }

    $_SESSION['PNSV' . $name] = $value;

    return true;
}

/**
 * Delete a session variable
 *
 * @param name $ name of the session variable to delete
 * @return success or error
 */
function pnSessionDelVar($name)
{
    // if empty or not set in both globals, return
    if (empty($name) || !(isset($_SESSION['PNSV' . $name]) || !isset($GLOBALS['PNSV' . $name]))){
        return false;
    }

    // Unset the session var
    unset($_SESSION['PNSV' . $name]);

    // This unsets the variable if register globals are on
    unset($GLOBALS['PNSV' . $name]);

    return true;
}

/**
 * Initialise session
 */
function pnSessionInit()
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    // First thing we do is ensure that there is no attempted pollution
    // of the session namespace
    foreach($GLOBALS as $k => $v) {
        if (substr($k,0,4) == 'PNSV') {
            return false;
        }
    }
    // Kick it
    session_start();
    // Have to re-write the cache control header to remove no-save, this
    // allows downloading of files to disk for application handlers
    // adam_baum - no-cache was stopping modules (andromeda) from caching the playlists, et al.
    // any strange behaviour encountered, revert to commented out code.
    // Header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
    Header('Cache-Control: cache');

    $sessid = session_id();
    // Get (actual) client IP addr
    $ipaddr = pnServerGetVar('REMOTE_ADDR');
    if (empty($ipaddr)) {
        $ipaddr = pnServerGetVar('HTTP_CLIENT_IP');
    }
    $tmpipaddr = pnServerGetVar('HTTP_CLIENT_IP');
    if (!empty($tmpipaddr)) {
        $ipaddr = $tmpipaddr;
    }
    $fwdipaddr = pnServerGetVar('HTTP_X_FORWARDED_FOR');

    if (!empty($fwdipaddr) AND strpos($fwdipaddr, ',') !== false) {
        $fwdipaddr = substr($fwdipaddr,0, strpos($fwdipaddr, ','));
    }
    $tmpipaddr = $fwdipaddr;

    if (!empty($tmpipaddr) AND strpos($tmpipaddr, ',') !== false) {
        $ipaddr = substr($tmpipaddr,0, strpos($tmpipaddr, ','));
    }

    $sessioninfocolumn = &$pntable['session_info_column'];
    $sessioninfotable = $pntable['session_info'];

    $query = "SELECT $sessioninfocolumn[ipaddr]
              FROM $sessioninfotable
              WHERE $sessioninfocolumn[sessid] = '" . pnVarPrepForStore($sessid) . "'";

    $result =& $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    if (!$result->EOF) {
// jgm - this has been commented out so that the nice AOL people
//       can view PN pages, will examine full implications of this
//       later
//        list($dbipaddr) = $result->fields;
        $result->Close();
//        if ($ipaddr == $dbipaddr) {
            pnSessionCurrent($sessid);
//        } else {
//          // Mismatch - destroy the session
//          session_destroy();
//          pnRedirect('index.php');
//          return false;
//        }
    } else {
        pnSessionNew($sessid, $ipaddr);
        // Generate a random number, used for
        // some authentication
        srand((double)microtime() * 1000000);
        pnSessionSetVar('rand', rand());
    }

    return true;
}

/**
 * Continue a current session
 *
 * @private
 * @param sessid $ the session ID
 */
function pnSessionCurrent($sessid)
{
    return true;
}

/**
 * Create a new session
 *
 * @private
 * @param sessid $ the session ID
 * @param ipaddr $ the IP address of the host with this session
 */
function pnSessionNew($sessid='', $ipaddr='')
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $sessioninfocolumn = &$pntable['session_info_column'];
    $sessioninfotable = $pntable['session_info'];

    $query = "INSERT INTO $sessioninfotable
                 ($sessioninfocolumn[sessid],
                  $sessioninfocolumn[ipaddr],
                  $sessioninfocolumn[uid],
                  $sessioninfocolumn[firstused],
                  $sessioninfocolumn[lastused])
              VALUES
                 ('" . pnVarPrepForStore($sessid) . "',
                  '" . pnVarPrepForStore($ipaddr) . "',
                  0,
                  " . time() . ",
                  " . time() . ")";

    $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    return true;
}

/**
 * PHP function to open the session
 *
 * @private
 */
function pnSessionOpen($path, $name)
{
    // Nothing to do - database opened elsewhere
    return true;
}

/**
 * PHP function to close the session
 *
 * @private
 */
function pnSessionClose()
{
    // Nothing to do - database closed elsewhere
    return true;
}

/**
 * PHP function to read a set of session variables
 *
 * @private
 */
function pnSessionRead($sessid)
{
	if (empty($sessid))
		return null;

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $sessioninfocolumn = &$pntable['session_info_column'];
    $sessioninfotable = $pntable['session_info'];

    $query = "SELECT $sessioninfocolumn[vars]
              FROM $sessioninfotable
              WHERE $sessioninfocolumn[sessid] = '" . pnVarPrepForStore($sessid) . "'";
    $result =& $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    if (!$result->EOF) {
        list($value) = $result->fields;
    } else {
        $value = '';
    }
    $result->Close();

    return($value);
}

/**
 * PHP function to write a set of session variables
 *
 * @private
 */
function pnSessionWrite($sessid, $vars)
{

    if (!isset($sessid) || !isset($vars)) {
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $sessioninfocolumn = &$pntable['session_info_column'];
    $sessioninfotable = $pntable['session_info'];

    $query = "UPDATE $sessioninfotable
              SET $sessioninfocolumn[vars] = '" . pnVarPrepForStore($vars) . "',
              	  $sessioninfocolumn[lastused] = " . time() . "
              WHERE $sessioninfocolumn[sessid] = '" . pnVarPrepForStore($sessid) . "'";
    $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    return true;
}

/**
 * PHP function to destroy a session
 *
 * @private
 */
function pnSessionDestroy($sessid)
{
	if (empty($sessid))
		return false;

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $sessioninfocolumn = &$pntable['session_info_column'];
    $sessioninfotable = $pntable['session_info'];

    $query = "DELETE FROM $sessioninfotable
              WHERE $sessioninfocolumn[sessid] = '" . pnVarPrepForStore($sessid) . "'";
    $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    if (!pnConfigGetVar('anonymoussessions')) {
        setcookie(session_name() ,'',0,ini_get('session.cookie_path'));
    }

    return true;
}

/**
 * PHP function to garbage collect session information
 *
 * @private
 */
function pnSessionGC($maxlifetime=3000)
{
    $now = time();
    $inactive = ($now - (int)(pnConfigGetVar('secinactivemins') * 60));
    $daysold  = ($now - (int)(pnConfigGetVar('secmeddays') * 86400));

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $sessioninfocolumn = &$pntable['session_info_column'];
    $sessioninfotable = $pntable['session_info'];

    switch (pnConfigGetVar('seclevel')) {
            case 'Low':
                // Low security - delete session info if user decided not to
                //                remember themself and inactivity timeout
                $where = "WHERE $sessioninfocolumn[vars] NOT LIKE '%PNSVrememberme|%'
                          AND $sessioninfocolumn[lastused] < $inactive";
                break;
            case 'Medium':
                // Medium security - delete session info if session cookie has
                // expired or user decided not to remember themself and inactivity timeout
                // OR max number of days have elapsed without logging back in
                $where = "WHERE ($sessioninfocolumn[vars] NOT LIKE '%PNSVrememberme|%'
                          AND $sessioninfocolumn[lastused] < $inactive)
                          OR ($sessioninfocolumn[lastused] < $daysold)
                          OR ($sessioninfocolumn[uid] = 0 AND $sessioninfocolumn[lastused] < $inactive)";
                break;
            case 'High':
            default:
                // High security - delete session info if user is inactive
                $where = "WHERE $sessioninfocolumn[lastused] < $inactive";
                break;
    }
    $query = "DELETE FROM $sessioninfotable $where";
    $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return false;
    }
    return true;
}

?>