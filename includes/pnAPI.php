<?php
// $Id: pnAPI.php 20429 2006-11-07 19:53:57Z landseer $
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
// Purpose of file: The PostNuke API
// ----------------------------------------------------------------------
/**
* @package PostNuke_Core
* @subpackage PostNuke_pnAPI
*/

/**
* Defines
*/

/**
* Yes/no integer
*/
define('_PNYES', 1);
define('_PNNO', 0);

/**
* State of modules
*/
define('_PNMODULE_STATE_UNINITIALISED', 1);
define('_PNMODULE_STATE_INACTIVE', 2);
define('_PNMODULE_STATE_ACTIVE', 3);
define('_PNMODULE_STATE_MISSING', 4);
define('_PNMODULE_STATE_UPGRADED', 5);

/**
* 'All' and 'unregistered' for user and group permissions
*/
define('_PNPERMS_ALL', '-1');
define('_PNPERMS_UNREGISTERED', '0');

/**
* Core version informations - should be upgraded on each release for
* better control on config settings
*/
define('_PN_VERSION_NUM', '0.7.6.4');
define('_PN_VERSION_ID', 'PostNuke');
define('_PN_VERSION_SUB', 'Phoenix');

/**
* Fake module for config vars
*/
define('_PN_CONFIG_MODULE', '/PNConfig');

/**
* Functions
*/

/**
* get a configuration variable
*
* @param name $ the name of the variable
* @return mixed value of the variable, or false on failure
*/
function pnConfigGetVar($name)
{
	if (!isset($name)) {
		return null;
	}

    if (isset($GLOBALS['pnconfig'][$name])) {
        $result = $GLOBALS['pnconfig'][$name];
    } else {
    	$mod_var = pnModGetVar(_PN_CONFIG_MODULE, $name);
    	if (is_string($mod_var)) {
		$result = @unserialize($mod_var);
        // Some caching
        $GLOBALS['pnconfig'][$name] = $result;
    	}
    }
	if (!isset($result)) {
		return null;
	}
    return $result;
}

/**
* set a configuration variable
*
* @param name $ the name of the variable
* @param value $ the value of the variable
* @return bool true on success, false on failure
*/
function pnConfigSetVar($name, $value='')
{
	$name = isset($name) ? (string)$name : '';

	// The database parameter are not allowed to change
    if (empty($name) || ($name == 'dbtype') || ($name == 'dbhost') || ($name == 'dbuname') || ($name == 'dbpass')
            || ($name == 'dbname') || ($name == 'system') || ($name == 'prefix') || ($name == 'encoded')) {
        return false;
    }

    // set the variable
    if ( pnModSetVar(_PN_CONFIG_MODULE, $name, @serialize($value)))  {
   		 // Update my vars
		$GLOBALS['pnconfig'][$name] = $value;
 		return true;
    }
	return false;
}

/**
* delete a configuration variable
*
* @param name $ the name of the variable
* @return bool true on success, false on failure
*/
function pnConfigDelVar($name)
{
    if (!isset($name)) {
        return false;
    }

    // The database parameter are not allowed to be deleted
    if (empty($name) || ($name == 'dbtype') || ($name == 'dbhost') || ($name == 'dbuname') || ($name == 'dbpass')
            || ($name == 'dbname') || ($name == 'system') || ($name == 'prefix') || ($name == 'encoded')) {
        return false;
    }

    // set the variable
    pnModDelVar(_PN_CONFIG_MODULE, $name);

    // Update my vars
    unset($GLOBALS['pnconfig'][$name]);

    // success
    return true;
}

/**
* Initialise PostNuke
* <br />
* Carries out a number of initialisation tasks to get PostNuke up and
* running.
*
* @returns void
*/
function pnInit()
{
    // force register_globals=off 

    // force register_globals = off
    if(!defined('_PNINSTALLVER') && ini_get('register_globals')) {
        foreach($GLOBALS as $s_variable_name => $m_variable_value)
        {
            if (!in_array($s_variable_name, array('GLOBALS', 'argv', 'argc', '_FILES', '_COOKIE', '_POST', '_GET', '_SERVER', '_ENV', '_SESSION', '_REQUEST', 's_variable_name', 'm_variable_value')))
            {
                unset($GLOBALS[$s_variable_name]);
            }
        }
        unset($GLOBALS['s_variable_name']);
        unset($GLOBALS['m_variable_value']);
    }
    
    // proper error_repoting
    // E_ALL for development
    // error_reporting(E_ALL);
    // without warnings and notices for release
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

    // Hack for some weird PHP systems that should have the
    // LC_* constants defined, but don't
    if (!defined('LC_TIME')) {
        define('LC_TIME', 'LC_TIME');
    }

    // Initialise and load configuration
    $pnconfig = array();
    $pndebug = array();
    include 'config.php';
    $GLOBALS['pnconfig'] = $pnconfig;
    $GLOBALS['pndebug'] = $pndebug;

    // Initialize the (ugly) additional header array
	$GLOBALS['additional_header'] = array();

    // load ADODB
    pnADODBInit();

    // Connect to database
    if (!pnDBInit()) {
        die('Database initialisation failed');
    }

    // Set up multisites
    // added this @define for .71, ugly ?
    // i guess the E_ALL stuff.
    @define('WHERE_IS_PERSO', '');

    // Initialise and load pntables
    pnDBSetTables();

    // user and modules system includes
    include 'includes/pnUser.php';
    include 'includes/pnMod.php';

    // Set compression on if desired
    if (pnConfigGetVar('UseCompression') == 1) {
        ob_start("ob_gzhandler");
    }

    if (isset($_REQUEST['_SESSION'])) {
        die('Attempted pollution of SESSION space via GPC request');
    }

    // Other includes
    include 'includes/pnSession.php';
    if (pnConfigGetVar('anonymoussessions') || !empty($_REQUEST['POSTNUKESID'])) {
        // Start session
        if (!pnSessionSetup()) {
            die('Session setup failed');
        }
        if (!pnSessionInit()) {
            die('Session initialisation failed');
        }
    }

    // load security functions.
    include 'includes/pnSecurity.php';
    include 'includes/pnBlocks.php';

    // Load our language files
    include 'includes/pnLang.php';
    pnLangLoad();

    // inclusion of pnrender class -- jn
    include 'includes/pnRender.class.php';
    include 'includes/pnTheme.php';
    include 'includes/pnHTML.php';
    // Legacy includes
    if (pnConfigGetVar('loadlegacy') == '1') {
        include 'includes/legacy/legacy.php';
        include 'includes/legacy/queryutil.php';
        include 'includes/legacy/xhtml.php';
        include 'includes/legacy/oldfuncs.php';
    }

    // Check for site closed
    if (pnConfigGetVar('siteoff') && !pnSecAuthAction(0, 'Settings::', 'SiteOff::', ACCESS_ADMIN)) {
        include('includes/templates/siteoff.htm');
        die;
    }

    // Cross-Site Scripting attack defense - Sent by larsneo
    // some syntax checking against injected javascript
    if (pnConfigGetVar('pnAntiCracker') == '1') {
    	  include 'includes/pnAntiCracker.php';
        pnSecureInput();
    }

    // load safehtml class for xss filtering
    // the XML_HTMLSAX3 define is also needed inside the class so we
    // cannot use the path directly in the include.
    if (pnConfigGetVar('safehtml') == '1') {
        define('XML_HTMLSAX3', 'includes/classes/safehtml/');
        include XML_HTMLSAX3 . 'safehtml.php';
    }

    // Banner system
    // TODO - move to banners module
    if (pnModAvailable('Banners')) {
        include 'includes/pnBanners.php';
    }

    // Call Stats module counter code if installed
    if (pnModAvailable('Stats') && !pnSecAuthAction(0, '.*', '.*', ACCESS_ADMIN)) {
        include 'includes/legacy/counter.php';
    }

    // Handle referer
    if (pnModAvailable('Referers') && pnConfigGetVar('httpref') == 1) {
        include 'includes/legacy/referer.php';
        httpreferer();
    }

    // Load the theme
    pnThemeLoad(pnUserGetTheme());

    return true;
}

/**
* Initialise DB connection
* @return bool true if successful, false otherwise
*/
function pnDBInit()
{
    // Get database parameters
    $dbtype = $GLOBALS['pnconfig']['dbtype'];
    $dbhost = $GLOBALS['pnconfig']['dbhost'];
    $dbname = $GLOBALS['pnconfig']['dbname'];
    $dbuname = $GLOBALS['pnconfig']['dbuname'];
    $dbpass = $GLOBALS['pnconfig']['dbpass'];
    $pconnect = $GLOBALS['pnconfig']['pconnect'];

    // Start connection
    $GLOBALS['pndbconn'] =& ADONewConnection($dbtype);
    if ($pconnect) {
        $dbh = $GLOBALS['pndbconn']->PConnect($dbhost, $dbuname, $dbpass, $dbname);
    } else {
        // itevo: /Go; it's more safe to use NConnect instead of Connect because of the following:
        // If you create two connections, but both use the same userid and password, PHP will share the same connection.
        // This can cause problems if the connections are meant to different databases.
        // The solution is to always use different userid's for different databases, or use NConnect().
        // NConnect: Always force a new connection. In contrast, PHP sometimes reuses connections when you use Connect() or PConnect().
        // Currently works only on mysql (PHP 4.3.0 or later), postgresql and oci8-derived drivers.
        // For other drivers, NConnect() works like Connect().
        $dbh = $GLOBALS['pndbconn']->NConnect($dbhost, $dbuname, $dbpass, $dbname);
        // /itevo
    }
    if (!$dbh) {
        include('includes/templates/dbconnectionerror.htm');
        die;
    }
    $GLOBALS['pndbconn']->debug = (($GLOBALS['pndebug']['debug_sql'] == 1) ? true:false);// sql debugging
    global $ADODB_FETCH_MODE;
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

    // force oracle to a consistent date format for comparison methods later on
    if (strcmp($dbtype, 'oci8') == 0) {
        $GLOBALS['pndbconn']->Execute("alter session set NLS_DATE_FORMAT = 'YYYY-MM-DD HH24:MI:SS'");
    }

    return true;
}

/**
* get a list of database connections
*
* @author Eric Barr
* @copyright Copyright (c) 2003 Envolution; Eric Barr. All rights reserved.
* @author Roger Raymond
* @return array array of database connections
*/
function &pnDBGetConn($pass_by_reference = null)
{
    // If the function was called with pass_by_reference set to true
    // return a reference to the dbconn object
    if ($pass_by_reference == true) {
        return $GLOBALS['pndbconn'];
    } else {
        return array($GLOBALS['pndbconn']);
    }
}

/**
* get a list of database tables
*
* @author Eric Barr
* @copyright Copyright (c) 2003 Envolution; Eric Barr. All rights reserved.
* @author Roger Raymond
* @return array array of database tables
*/
function &pnDBGetTables()
{
    return $GLOBALS['pntables'];
}

/*
* get a list of dbms specific table options
*
* For use by ADODB's data dictionary
*
* @author Mark West
* @since v1.93
*/
function &pnDBGetTableOptions()
{
    // For mysql we should get the table type defined in config.php
    // Additional database specific settings can be defined here
    // See ADODB's data dictionary docs for full details
    // Since we'll use the same options for all tables we'll define this once
    $tableoptions = array('mysql' => 'engine = ' . pnConfigGetVar('dbtabletype'));
    return $tableoptions;
}

/**
* Set Database Table Listing
*
* @desc        Creates the database table listing if it hasn't been created yet
*              and merges new table listings into the master list.
* @author Eric Barr
* @copyright Copyright (c) 2003 Envolution; Eric Barr. All rights reserved.
* @access      public
* @param       array $newtables
* @return      void
*/
function pnDBSetTables()
{
    // Create a static var to hold the database table listing
    static $pntables;

    // If the table listing doesn't exist create it with the input array
    if(!is_array($pntables)) {
        // if a multisite has its own pntables.
        if (defined('WHERE_IS_PERSO') && file_exists(WHERE_IS_PERSO . 'pntables.php')) {
            include WHERE_IS_PERSO . 'pntables.php';
        } else {
            include 'pntables.php';
        }
    }

    // Set the pntables in the global listing for pnDBGetTables to have access to
    $GLOBALS['pntables'] = $pntable;
    return;
}

/**
* clean user input
* <br />
* Gets a global variable, cleaning it up to try to ensure that
* hack attacks don't work
*
* @param var $ name of variable to get
* @param  $ ...
* @return mixed prepared variable if only one variable passed
* in, otherwise an array of prepared variables
*/
function pnVarCleanFromInput()
{
    // Create an array of bad objects to clean out of input variables
    $search = array('|</?\s*SCRIPT.*?>|si',
                    '|</?\s*FRAME.*?>|si',
                    '|</?\s*OBJECT.*?>|si',
                    '|</?\s*META.*?>|si',
                    '|</?\s*APPLET.*?>|si',
                    '|</?\s*LINK.*?>|si',
                    '|</?\s*IFRAME.*?>|si',
                    '|STYLE\s*=\s*"[^"]*"|si');

    // Create an empty array that will be used to replace any malacious code
    $replace = array('');

    // Create an array to store cleaned variables
    $resarray = array();

    // Loop through the function arguments
    // these arguments are input variables to be cleaned
    foreach (func_get_args() as $var) {

        // If the var is empty return void
        if (empty($var)) {
            return;
        }

        // Identify the correct place to get our variable from
        // and if we should attempt to cleanse the variable
        // content from the $_FILES array is left untouched
        $cleanse = false;
        switch (true) {
            case (isset($_REQUEST[$var]) && !isset($_FILES[$var])):
                // Set $ourvar from the $_REQUEST superglobal
                // but only if it's not also present in the $_FILES array
                // since php < 4.30 includes $_FILES in $_REQUEST
                $ourvar = $_REQUEST[$var];
                $cleanse = true;
                break;
            case isset($_GET[$var]):
                // Set $ourvar from the $_GET superglobal
                $ourvar = $_GET[$var];
                $cleanse = true;
                break;
            case isset($_POST[$var]):
                // Set $ourvar from the $_POST superglobal
                $ourvar = $_POST[$var];
                $cleanse = true;
                break;
            case isset($_COOKIE[$var]):
                // Set $ourvar from the $_COOKIE superglobal
                $ourvar = $_COOKIE[$var];
                $cleanse = true;
                break;
            case isset($_FILES[$var]):
                // Set $ourvar from the $_FILES superglobal
                $ourvar = $_FILES[$var];
                break;
            default:
                $ourvar = null;
                break;
        }

        $alwaysclean = array('name', 'module', 'type', 'file', 'authid');
        if (in_array($var, $alwaysclean)) {
            $cleanse = true;
        }

        if ($cleanse) {
            // If magic_quotes_gpc is on strip out the slashes
            if (get_magic_quotes_gpc()) {
                pnStripslashes($ourvar);
            }

            // If at least ADMIN access level is not set clean the variable
            // @note: Since no security parameters have been passed to this
            // the variables will always be cleaned.
            // @note: some vars will always be cleaned so as not to trigger
            // a security check (requires 3 sql queries to build permissions
            // map).
            if (!pnSecAuthAction(0, '.*', '.*', ACCESS_ADMIN)) {
                $ourvar = preg_replace($search, $replace, $ourvar);
            }
        }

        // Add the cleaned var to the return array
        array_push($resarray, $ourvar);
    }

    // If there was only one parameter passed return a variable
    if (func_num_args() == 1) {
        return $resarray[0];
    // Else return an array
    } else {
        return $resarray;
    }
}

/**
* strip slashes
*
* stripslashes on multidimensional arrays.
* Used in conjunction with pnVarCleanFromInput
*
* @access private
* @param any $ variables or arrays to be stripslashed
*/
function pnStripslashes (&$value)
{
	if(empty($value))
		return;

    if (!is_array($value)) {
        $value = stripslashes($value);
    } else {
        array_walk($value, 'pnStripslashes');
    }
}

/**
* ready user output
* <br />
* Gets a variable, cleaning it up such that the text is
* shown exactly as expected
*
* @param var $ variable to prepare
* @param  $ ...
* @return mixed prepared variable if only one variable passed
* in, otherwise an array of prepared variables
*/
function pnVarPrepForDisplay()
{
    // This search and replace finds the text 'x@y' and replaces
    // it with HTML entities, this provides protection against
    // email harvesters
    static $search = array('/(.)@(.)/se');

    static $replace = array('"&#" .
                            sprintf("%03d", ord("\\1")) .
                            ";&#064;&#" .
                            sprintf("%03d", ord("\\2")) . ";";');

    $resarray = array();
    foreach (func_get_args() as $ourvar) {
        // Prepare var
        $ourvar = htmlspecialchars($ourvar);
        $ourvar = preg_replace($search, $replace, $ourvar);
        // Add to array
        array_push($resarray, $ourvar);
    }
    // Return vars
    if (func_num_args() == 1) {
        return $resarray[0];
    } else {
        return $resarray;
    }
}

/**
* ready HTML output
* <br />
* Gets a variable, cleaning it up such that the text is
* shown exactly as expected, except for allowed HTML tags which
* are allowed through
* @author Xaraya development team
* @param var variable to prepare
* @param ...
* @return string/array prepared variable if only one variable passed
* in, otherwise an array of prepared variables
*/
function pnVarPrepHTMLDisplay()
{
    // This search and replace finds the text 'x@y' and replaces
    // it with HTML entities, this provides protection against
    // email harvesters
    //
    // Note that the use of \024 and \022 are needed to ensure that
    // this does not break HTML tags that might be around either
    // the username or the domain name
    static $search = array('/([^\024])@([^\022])/se');

    static $replace = array('"&#" .
                            sprintf("%03d", ord("\\1")) .
                            ";&#064;&#" .
                            sprintf("%03d", ord("\\2")) . ";";');

    static $allowedtags = NULL;

    if (!isset($allowedtags)) {
        $allowedhtml = array();
        foreach(pnConfigGetVar('AllowableHTML') as $k=>$v) {
            if ($k == '!--') {
                if ($v <> 0) {
                    $allowedhtml[] = "$k.*?--";
                }
            } else {
                switch($v) {
                    case 0:
                        break;
                    case 1:
                        $allowedhtml[] = "/?$k\s*/?";
                        break;
                    case 2:
                        // intelligent regex to deal with > in parameters, bug #1782
                        // credits to jln
                        $allowedhtml[] = "/?\s*$k" . "(\s+[\w:]+\s*=\s*(\"[^\"]*\"|'[^']*'))*" . '\s*/?';
                       // original version
                        // $allowedhtml[] = "/?$k(\s+[^>]*)?/?";
                        break;
                }
            }
        }
        if (count($allowedhtml) > 0) {
            // 2nd part of bugfix #1782
            $allowedtags = '~<\s*(' . join('|',$allowedhtml) . ')\s*>~is';
        } else {
            $allowedtags = '';
        }
    }

    $usesh = pnConfigGetVar('safehtml');
    if ($usesh == '1') {
        // prepare safehtml class
        static $safehtml;
        if(!isset($safehtml)) {
            $safehtml = new SafeHTML();
            $safehtml->attributes = array('dynsrc'); // removes id and name from the attributes
        }
    }

    $resarray = array();
    foreach (func_get_args() as $var) {
        if ($usesh == '1') {
            static $parsed = array();
            $shakey = sha1($var);
            if (isset($parsed[$shakey])) {
                $var = $parsed[$shakey];
            } else {
                $safehtml->clear();
                $var = $safehtml->parse($var);
                $parsed[$shakey] = $var;
            }
        }
        // Preparse var to mark the HTML that we want
        if (!empty($allowedtags))
            $var = preg_replace($allowedtags, "\022\\1\024", $var);

        // Prepare var
        $var = htmlspecialchars($var);
        
        // scramble mailadress
        $var = preg_replace($search, $replace, $var);

        // Fix the HTML that we want
        $var = preg_replace_callback('/\022([^\024]*)\024/',
                                     'pnVarPrepHTMLDisplay__callback',
                                     $var);

        // Fix entities if required
        if (pnConfigGetVar('htmlentities')) {
            $var = preg_replace('/&amp;([a-z#0-9]+);/i', "&\\1;", $var);
        }

        // Add to array
        array_push($resarray, $var);
    }

    // Return vars
    if (func_num_args() == 1) {
        return $resarray[0];
    } else {
        return $resarray;
    }
}

/**
* Callback function for pnVarPrepHTMLDisplay
*
* @author Xaraya development team
* @access private
*/
function pnVarPrepHTMLDisplay__callback($matches)
{
	if(empty($matches))
		return;

    return '<' . strtr($matches[1],
                       array('&gt;' => '>',
                             '&lt;' => '<',
                             '&quot;' => '"'/*,
                             '&amp;' => '&'*/))
           . '>';
}

/**
* ready database output
* <br />
* Gets a variable, cleaning it up such that the text is
* stored in a database exactly as expected
*
* @param var $ variable to prepare
* @param  $ ...
* @return mixed prepared variable if only one variable passed
* in, otherwise an array of prepared variables
*/
function pnVarPrepForStore()
{
    $resarray = array();
    foreach (func_get_args() as $ourvar) {
        if (!get_magic_quotes_runtime() && !is_array($ourvar)) {
            $ourvar = addslashes($ourvar);
        }
        // Add to array
        array_push($resarray, $ourvar);
    }
    // Return vars
    if (func_num_args() == 1) {
        return $resarray[0];
    } else {
        return $resarray;
    }
}

/**
* ready operating system output
* <br />
* Gets a variable, cleaning it up such that any attempts
* to access files outside of the scope of the PostNuke
* system is not allowed.
*
* @param var $ variable to prepare
* @param  $ ...
* @return mixed prepared variable if only one variable passed
* in, otherwise an array of prepared variables
**/
function pnVarPrepForOS()
{
    $resarray = array();

    foreach (func_get_args() as $ourvar) {
        // this array will keep the cleaned path
        $clean_array = array();

        // Split the path at possible path delimiters.
        // Setting PREG_SPLIT_NOEMPTY eliminates double delimiters on the fly.
        $dirty_array = preg_split('#[:/\\\\]#', $ourvar, -1, PREG_SPLIT_NO_EMPTY);

        // now walk the path and do the relevant things
        foreach ($dirty_array as $current) {
            if ($current == '.') {
                // current path element is a dot, so we don't do anything
            } elseif ($current == '..') {
                // current path element is .., so we remove the last path
                array_pop($clean_array);
            } else {
                // current path element is valid, so we add it to the path
                array_push($clean_array, $current);
            }
        }
        // build the path
        // should we use DIRECTORY_SEPARATOR here?
        $ourvar = implode('/', $clean_array);

        // Prepare var
        if (!get_magic_quotes_runtime()) {
            $ourvar = addslashes($ourvar);
        }

        // Add to array
        array_push($resarray, $ourvar);
    }
    // Return vars
    if (func_num_args() == 1) {
        return $resarray[0];
    } else {
        return $resarray;
    }
}

/**
* remove censored words
*/
function pnVarCensor()
{
    static $docensor;
    if (!isset($docensor)) {
        $docensor = pnConfigGetVar('CensorMode');
    }

    static $search = array();
    if (empty($search)) {
        $repsearch = array('/o/i',
                           '/e/i',
                           '/a/i',
                           '/i/i');
        $repreplace = array('0',
                            '3',
                            '@',
                            '1');
        $censoredwords = pnConfigGetVar('CensorList');
        foreach ($censoredwords as $censoredword) {
            // Simple word
            $search[] = "/\b$censoredword\b/i";
            // Common replacements
            $mungedword = preg_replace($repsearch, $repreplace, $censoredword);
            if ($mungedword != $censoredword) {
                $search[] = "/\b$mungedword\b/";
            }
        }
    }

    $replace = pnConfigGetVar('CensorReplace');

    $resarray = array();
    foreach (func_get_args() as $ourvar) {
        if ($docensor) {
            // Parse out nasty words
            $ourvar = preg_replace($search, $replace, $ourvar);
        }
        // Add to array
        array_push($resarray, $ourvar);
    }
    // Return vars
    if (func_num_args() == 1) {
        return $resarray[0];
    } else {
        return $resarray;
    }
}

/**
 * validate a postnuke variable
 *
 * @access public
 * @author Damien Bonvillain
 * @author Gregor J. Rothfuss
 * @author Jörg Napp
 * @since 1.23 - 2002/02/01
 * @param $var   the variable to validate
 * @param $type  the type of the validation to perform (email, url etc.)
 * @param $args  optional array with validation-specific settings (never used...)
 * @return bool true if the validation was successful, false otherwise
 */
function pnVarValidate($var, $type, $args = 0)
{
    if (!isset($var) || !isset($type)) {
        return false;
    }

    // typecasting (might be useless in this function)
    $var = (string)$var;
    $type = (string)$type;

    static $maxlength = array('modvar' => 64,
                              'func'   => 512,
                              'api'    => 187,
                              'theme'  => 200,
                              'uname'  => 25,
                              'config' => 64);

    static $minlength = array('mod'    => 1,
                              'modvar' => 1,
                              'uname'  => 1,
                              'config' => 1);

    // commented out some regexps until some useful and working ones are found
    static $regexp    = array( // 'mod'    => '/^[^\\\/\?\*\"\'\>\<\:\|]*$/',
                               // 'func'   => '/[^0-9a-zA-Z_]/',
                               // 'api'    => '/[^0-9a-zA-Z_]/',
                               // 'theme'  => '/^[^\\\/\?\*\"\'\>\<\:\|]*$/',
                              'email'  => '/^(?:[^\s\000-\037\177\(\)<>@,;:\\"\[\]]\.?)+@(?:[^\s\000-\037\177\(\)<>@,;:\\\"\[\]]\.?)+\.[a-z]{2,6}$/Ui',
                              'url'    => '/^([!#\$\046-\073=\077-\132_\141-\172~]|(?:%[a-f0-9]{2}))+$/i');


    // special cases
    if ($type == 'mod' && $var == '/PNConfig') {
        return true;
    }

    if ($type == 'config' && ($var == 'dbtype') ||
                             ($var == 'dbhost') ||
                             ($var == 'dbuname') ||
                             ($var == 'dbpass') ||
                             ($var == 'dbname') ||
                             ($var == 'system') ||
                             ($var == 'prefix') ||
                             ($var == 'encoded')) {
        // The database parameter are not allowed to change
        return false;
    }

    if ($type == 'email' || $type == 'url') {
        // CSRF protection for email and url
        $var = str_replace(array('\r', '\n', '%0d', '%0a'), '', $var);

        if (pnConfigGetVar('idnnames') == 1) {
            // transfer between the encoded (Punycode) notation and the decoded (8bit) notation.
            include_once 'includes/classes/idna/idna_convert.class.php';
            $IDN = new idna_convert();
            $var = $IDN->encode($var);
        }
        // all characters must be 7 bit ascii
        $length = strlen($var);
        $idx = 0;
        while ($length--) {
            $c = $var[$idx++];
            if (ord($c) > 127) {
                return false;
            }
        }
    }

    if ($type == 'url') {
        // check for url
        $url_array = @parse_url($var);
        if (!empty($url_array) && empty($url_array['scheme'])) {
            return false;
        }
    }

    if ($type == 'uname') {
        // check for invalid characters
        if ( strstr($var, chr(160)) || strstr($var, chr(173)) ) {
            return false;
        }
    }

    // variable passed special checks. We now to generic checkings.

    // check for maximal length
    if (isset($maxlength[$type]) && strlen($var) > $maxlength[$type]) {
        return false;
    }

    // check for minimal length
    if (isset($minlength[$type]) && strlen($var) < $minlength[$type]) {
        return false;
    }

    // check for regular expression
    if (isset($regexp[$type]) && !preg_match($regexp[$type], $var)) {
        return false;
    }

    // all tests for illegal entries failed, so we assume the var is ok ;-)
    return true;
}

/**
* get status message from previous operation
* <br />
* Obtains any status message, and also destroys
* it from the session to prevent duplication
*
* @return string the status message
*/
function pnGetStatusMsg()
{
    $msg = pnSessionGetVar('statusmsg');
    pnSessionDelVar('statusmsg');
    $errmsg = pnSessionGetVar('errormsg');
    pnSessionDelVar('errormsg');
    // Error message overrides status message
    if (!empty($errmsg)) {
        return $errmsg;
    }
    return $msg;
}

/**
* get base URI for PostNuke
*
* @return string base URI for PostNuke
*/
function pnGetBaseURI()
{
    // Get the name of this URI
    // Start of with REQUEST_URI
    $path = pnServerGetVar('REQUEST_URI');

    if ((empty($path)) || (substr($path, -1, 1) == '/')) {
        // REQUEST_URI was empty or pointed to a path
        // Try looking at PATH_INFO
        $path = pnServerGetVar('PATH_INFO');
        if (empty($path)) {
            $path = pnServerGetVar('SCRIPT_NAME');
        }
    }

    $path = preg_replace('/[#\?].*/', '', $path);
    $path = dirname($path);

    if (preg_match('!^[/\\\]*$!', $path)) {
        $path = '';
    }

    return $path;
}

/**
* get base URL for PostNuke
*
* @return string base URL for PostNuke
*/
function pnGetBaseURL()
{
    $server = pnServerGetVar('HTTP_HOST');

    // IIS sets HTTPS=off
    $https = pnServerGetVar('HTTPS');
    if (isset($https) && $https != 'off') {
        $proto = 'https://';
    } else {
        $proto = 'http://';
    }

    $path = pnGetBaseURI();

    return "$proto$server$path/";
}

/**
* Carry out a redirect
*
* @param the $ URL to redirect to
* @returns bool true if redirect successful, false otherwise
*/
function pnRedirect($redirecturl)
{
    // very basic input validation against HTTP response splitting
    $redirecturl = str_replace(array('\r', '\n', '%0d', '%0a'), '', $redirecturl);

    // check if the headers have already been sent
    if (headers_sent()) {
        return false;
    }

    // Always close session before redirect
    if (function_exists('session_write_close')) {
        session_write_close();
    }

    if (preg_match('!^http!', $redirecturl)) {
        // Absolute URL - simple redirect
        header("Location: $redirecturl");
        return true;
    }
    // Removing leading slashes from redirect url
    $redirecturl = preg_replace('!^/*!', '', $redirecturl);
    // Get base URL
    $baseurl = pnGetBaseURL();
    header("Location: $baseurl$redirecturl");
    return true;
}

/**
* check to see if this is a local referral
*
* @return bool true if locally referred, false if not
*/
function pnLocalReferer()
{
    $server = pnServerGetVar('HTTP_HOST');
    $referer = pnServerGetVar('HTTP_REFERER');

    if (empty($referer) || preg_match("!^http://$server/!", $referer)) {
        return true;
    } else {
        return false;
    }
}

// Hack - we need this for themes, but will get rid of it soon
if (!function_exists('GetUserTime')) {
	/**
	 * get a Time String in the right format
	 *
	 *
	 * @param time $ - prefix string
	 * @return mixed string if successfull, false if not
	 */
	function GetUserTime($time)
    {
		if(empty($time)) {
			return;
		}

        if (pnUserLoggedIn()) {
            $time += (pnUserGetVar('timezone_offset') - pnConfigGetVar('timezone_offset')) * 3600;
        } else {
			$time += (12 - pnConfigGetVar('timezone_offset')) * 3600;
		}
        return($time);
    }
}

/**
* send an email
*
* e-mail messages should now be send with a pnModAPIFunc call to the mailer module
*
* @deprecated
* @param to $ - recipient of the email
* @param subject $ - title of the email
* @param message $ - body of the email
* @param headers $ - extra headers for the email
* @param html $ - message is html formatted
* @param debug $ - if 1, echo mail content
* @return bool true if the email was sent, false if not
*/
function pnMail($to, $subject, $message='', $headers = '', $html = 0, $debug = 0)
{
    if(empty($to) || !isset($subject)) {
    	return false;
    }

    // set initial return value until we know we have a valid return
    $return = false;

    // check if the mailer module is availble and if so call the API
    if((pnModAvailable('Mailer')) && (pnModAPILoad('Mailer', 'user'))) {
        $return = pnModAPIFunc('Mailer', 'user', 'sendmessage', array('toaddress' => $to,
                                                                      'subject' => $subject,
                                                                      'headers' => $headers,
                                                                      'body' => $message,
                                                                      'headers' => $headers,
                                                                      'html' => $html));
   	}

    return $return;
}

/**
* Function that compares the current php version on the
* system with the target one
*
* Deprecate function reverting to php detecion function
*
* @deprecated
*/
function pnPhpVersionCheck($vercheck='')
{
    $minver = str_replace(".", "", $vercheck);
    $curver = str_replace(".", "", phpversion());

    if ($curver >= $minver) {
        return true;
    } else {
        return false;
    }
}

/**
* initialise ADODB
*
* @return void
*/
function pnADODBInit()
{
    // ADODB configuration
    global $ADODB_CACHE_DIR;
    $ADODB_CACHE_DIR = realpath($GLOBALS['pnconfig']['temp'] . '/adodb');
    if (!defined('ADODB_DIR')) {
        define('ADODB_DIR', 'includes/classes/adodb');
    }
    include 'includes/classes/adodb/adodb.inc.php';

    // ADODB Error handle
    if ($GLOBALS['pndebug']['debug_sql']) {
        include 'includes/classes/adodb/adodb-errorhandler.inc.php';
    }

    // Decode encoded DB parameters
    if ($GLOBALS['pnconfig']['encoded']) {
        $GLOBALS['pnconfig']['dbuname'] = base64_decode($GLOBALS['pnconfig']['dbuname']);
        $GLOBALS['pnconfig']['dbpass'] = base64_decode($GLOBALS['pnconfig']['dbpass']);
        $GLOBALS['pnconfig']['encoded'] = 0;
    }

    // debugger if required
    if ($GLOBALS['pndebug']['debug']) {
        include_once 'includes/classes/lensdebug/lensdebug.class.php';
        $GLOBALS['dbg'] =& new LensDebug();
        $GLOBALS['debug_sqlcalls'] = 0;
    }

    // initialise time to render
    if ($GLOBALS['pndebug']['pagerendertime']) {
        $mtime = explode(" ", microtime());
        $GLOBALS['dbg_starttime'] = $mtime[1] + $mtime[0];
    }
}

/**
* Gets a server variable
*
* Returns the value of $name from $_SERVER array.
* Accepted values for $name are exactly the ones described by the
* {@link http://www.php.net/manual/en/reserved.variables.html#reserved.variables.server PHP manual}.
* If the server variable doesn't exist void is returned.
*
* @author Marco Canini <marco@xaraya.com>, Michel Dalle
* @access public
* @param name string the name of the variable
* @return mixed value of the variable
*/
function pnServerGetVar($name)
{
    // Check the relevant superglobals
    if (!empty($name) && isset($_SERVER[$name])) {
        return $_SERVER[$name];
    }
    return null; // we found nothing here
}

/**
* Gets the host name
*
* Returns the server host name fetched from HTTP headers when possible.
* The host name is in the canonical form (host + : + port) when the port is different than 80.
*
* @author Marco Canini <marco@xaraya.com>
* @access public
* @return string HTTP host name
*/
function pnGetHost()
{
    $server = pnServerGetVar('HTTP_HOST');
    if (empty($server)) {
        // HTTP_HOST is reliable only for HTTP 1.1
        $server = pnServerGetVar('SERVER_NAME');
        $port = pnServerGetVar('SERVER_PORT');
        if ($port != '80') $server .= ":$port";
    }
    return $server;
}

/**
* Get current URI (and optionally add/replace some parameters)
*
* @access public
* @param args array additional parameters to be added to/replaced in the URI (e.g. theme, ...)
* @return string current URI
*/
function pnGetCurrentURI($args = array())
{
    // get current URI
    $request = pnServerGetVar('REQUEST_URI');

    if (empty($request)) {
        // adapted patch from Chris van de Steeg for IIS
        // TODO: please test this :)
        $scriptname = pnServerGetVar('SCRIPT_NAME');
        $pathinfo = pnServerGetVar('PATH_INFO');
        if ($pathinfo == $scriptname) {
            $pathinfo = '';
        }
        if (!empty($scriptname)) {
            $request = $scriptname . $pathinfo;
            $querystring = pnServerGetVar('QUERY_STRING');
            if (!empty($querystring)) $request .= '?'.$querystring;
        } else {
            $request = '/';
        }
    }

    // add optional parameters
    if (count($args) > 0) {
        if (strpos($request,'?') === false) $request .= '?';
        else $request .= '&';

        foreach ($args as $k=>$v) {
            if (is_array($v)) {
                foreach($v as $l=>$w) {
                // TODO: replace in-line here too ?
                    if (!empty($w)) $request .= $k . "[$l]=$w&";
                }
            } else {
                // if this parameter is already in the query string...
                if (preg_match("/(&|\?)($k=[^&]*)/",$request,$matches)) {
                    $find = $matches[2];
                    // ... replace it in-line if it's not empty
                    if (!empty($v)) {
                        $request = preg_replace("/(&|\?)$find/","$1$k=$v",$request);

                    // ... or remove it otherwise
                    } elseif ($matches[1] == '?') {
                        $request = preg_replace("/\?$find(&|)/",'?',$request);
                    } else {
                        $request = preg_replace("/&$find/",'',$request);
                    }
                } elseif (!empty($v)) {
                    $request .= "$k=$v&";
                }
            }
        }

        $request = substr($request, 0, -1);
    }

    return $request;
}

/**
* Gets the host name
*
* Returns the server host name fetched from HTTP headers when possible.
* The host name is in the canonical form (host + : + port) when the port is different than 80.
*
* @author Marco Canini <marco@xaraya.com>
* @access public
* @return string HTTP host name
*/
function pnServerGetHost()
{
    $server = pnServerGetVar('HTTP_HOST');
    if (empty($server)) {
        // HTTP_HOST is reliable only for HTTP 1.1
        $server = pnServerGetVar('SERVER_NAME');
        $port = pnServerGetVar('SERVER_PORT');
        if ($port != '80') $server .= ":$port";
    }
    return $server;
}

/**
* Gets the current protocol
*
* Returns the HTTP protocol used by current connection, it could be 'http' or 'https'.
*
* @author Marco Canini <marco@xaraya.com>
* @access public
* @return string current HTTP protocol
*/
function pnServerGetProtocol()
{
    if (preg_match('/^http:/', $_SERVER['REQUEST_URI'])) {
        return 'http';
    }
    $HTTPS = pnServerGetVar('HTTPS');
    // IIS seems to set HTTPS = off for some reason
    return (!empty($HTTPS) && $HTTPS != 'off') ? 'https' : 'http';
}

/**
* Get current URL
*
* @access public
* @param args array additional parameters to be added to/replaced in the URL (e.g. theme, ...)
* @return string current URL
* @todo cfr. BaseURI() for other possible ways, or try PHP_SELF
*/
function pnGetCurrentURL()
{
    $server = pnServerGetHost();
    $protocol = pnServerGetProtocol();
    $baseurl = "$protocol://$server";

    // get current URI
    $request = pnServerGetVar('REQUEST_URI');

    if (empty($request)) {
        // adapted patch from Chris van de Steeg for IIS
        // TODO: please test this :)
        $scriptname = pnServerGetVar('SCRIPT_NAME');
        $pathinfo = pnServerGetVar('PATH_INFO');
        if ($pathinfo == $scriptname) {
            $pathinfo = '';
        }
        if (!empty($scriptname)) {
            $request = $scriptname . $pathinfo;
            $querystring = pnServerGetVar('QUERY_STRING');
            if (!empty($querystring)) $request .= '?'.$querystring;
        } else {
            $request = '/';
        }
    }

    return $baseurl . $request;
}

?>
