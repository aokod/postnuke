<?php
/**
 * $Id: pnRender.class.php 19261 2006-06-12 14:09:17Z markwest $
 *
 * * pnRender *
 *
 * PostNuke wrapper class for Smarty
 *
 * * License *
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License (GPL)
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @author      PostNuke development team
 * @version     .7/.8
 * @link        http://www.post-nuke.net              PostNuke home page
 * @link        http://smarty.php.net                 Smarty home page
 * @license     http://www.gnu.org/copyleft/gpl.html  GNU General Public License
 * @package     PostNuke_Core
 * @subpackage  PostNuke_pnAPI
 */

// keeping track if Smarty is loaded somewhere else!
if (!class_exists('Smarty')) {
	/**
	 * The directory of Smarty
	 */
    define('SMARTY_DIR', 'includes/classes/Smarty/');
    require_once (SMARTY_DIR . 'Smarty.class.php');
}

/**
 * Our class
 *
 * @package     PostNuke_Core
 * @subpackage  PostNuke_pnAPI
 */
class pnRender extends Smarty {
    /**
     * The module for wich the object is for
     */
    var $module;

    /**
     * The cache ID of the object
     */
    var $cache_id;

    /**
     * Set if Xanthia is an active module and templates stored in database
     */
    var $userdb;

    /**
     * true if admins wants to expose the template folder, needs admin rights for pnRender too
     */
    var $expose_template;

    /**
     * The class constructor.
     *
     * This function also tries to load a file called usemodules
     * which it expects in the calling modules pntemplates/config
     * folder.
     * This file contains lines like
     *
     * ---snip---
     * module1
     * module2
     * module3
     * ---snap---
     *
     * These modules plugins folders will be added to the plugins_dir
     * array to let a module use plugins from another module.
     *
     * If the usemodules file is not available we do not throw an
     * error message because this might be the usual case for
     * simple templates.
     *
     * @param   string   $module   The module for which this object is for
     */
    function pnRender($module = '')
    {
        // first, get a native Smarty object
        $this->Smarty();

        // Initialize the module property with the name of
        // the topmost module. Foor Hooks, Blocks, API Functions and others
        // you need to set this property to the name of the respective module!
        if (!$module) {
            $module = pnModGetName();
        }
        $this->module = $module;

        // begin holder tag (be nice to others)
        $this->left_delimiter = '<!--[';
        // end holder tag
        $this->right_delimiter = ']-->';

        //---- Plugins handling -----------------------------------------------
        // add the global PostNuke plugins directory
		if (is_dir('system/pnRender/plugins')) {
	        array_push($this->plugins_dir, 'system/pnRender/plugins');
		} else if (is_dir('modules/pnRender/plugins')) {
	        array_push($this->plugins_dir, 'modules/pnRender/plugins');
		}

        // add the global PostNuke plugins directory
        $modinfo = pnModGetInfo(pnModGetIDFromName('Xanthia'));
        $modpath = ($modinfo['type'] == 3) ? 'system' : 'modules';
        array_push($this->plugins_dir, "$modpath/$modinfo[directory]/plugins");

// uncomment for .8 until modtype=3 for system modules is implemented
        array_push($this->plugins_dir, "system/$modinfo[directory]/plugins");

        // add module specific plugins directories, if they exist
        $theme = pnUserGetTheme();
        $themepath = "themes/$theme/templates/modules/$module/plugins";
        if (file_exists($themepath)) {
            array_push($this->plugins_dir, $themepath);
        }

        $modinfo = pnModGetInfo(pnModGetIDFromName($module));
        $modpath = ($modinfo['type'] == 3) ? 'system' : 'modules';
        $mod_plugs = "$modpath/$modinfo[directory]/pntemplates/plugins";
        // build the path to the config file for usemodules needed later
        $usemod_conf = "$modpath/$modinfo[directory]/pntemplates/config/usemodules";
        if (file_exists($mod_plugs)) {
            array_push($this->plugins_dir, $mod_plugs);
        }

        // add theme specific plugins directories, if they exist
        $themepath = "themes/$theme/plugins";
        if (file_exists($themepath)) {
            array_push($this->plugins_dir, $themepath);
        }

// uncomment for .8 until modtype=3 for system modules is implemented
        $modpath = 'system';
        $mod_plugs = "$modpath/$modinfo[directory]/pntemplates/plugins";
        if (file_exists($mod_plugs)) {
            array_push($this->plugins_dir, $mod_plugs);
        }

        // load the config file
        if( file_exists($usemod_conf) && is_readable($usemod_conf)) {
            $additionalmodules = file($usemod_conf);
            if(is_array($additionalmodules)) {
                foreach($additionalmodules as $addmod) {
                    $this->_add_plugins_dir(trim($addmod));
                }
            }
        }

        // check if the recent 'type' parameter in the URL is admin and if yes,
        // include (modules|system)/Admin/pntemplates/plugins to the plugins_dir array
        $type = pnVarCleanFromInput('type');
        if(!empty($type) && $type=='admin') {
            array_push($this->plugins_dir, "modules/Admin/pntemplates/plugins");
            array_push($this->plugins_dir, "system/Admin/pntemplates/plugins");
        }

        //---- Cache handling -------------------------------------------------
        // use HTML cache system?
        $this->caching = pnModGetVar('pnrender', 'cache');
        $this->cache_lifetime = pnModGetVar('pnrender', 'lifetime');

        // HTML cache directory
        $this->cache_dir = pnConfigGetVar('temp') . '/pnRender_cache';

        //---- Compilation handling -------------------------------------------
        // check for updated templates?
        $this->compile_check = pnModGetVar('pnrender', 'compile_check');

        // force compile template always?
        $this->force_compile = pnModGetVar('pnrender', 'force_compile');

        // safe_mode?
        // This routine is taken from PostCalendar.
		// we don't need this code anymore due to change below
        //$safe_mode     = ini_get('safe_mode');
        //$safe_mode_gid = ini_get('safe_mode_gid');
        //$open_basedir  = ini_get('open_basedir');

        // don't use subdirectories when creating compiled/cached templates
		// this works better in a hosted environment
        $this->use_sub_dirs = false;
        //$this->use_sub_dirs = !((bool)$safe_mode ||
	    //                      (bool)$safe_mode_gid ||
        //                      !empty($open_basedir));

        // cache directory (compiled templates)
        $this->compile_dir = pnConfigGetVar('temp') . '/pnRender_compiled';

        // compile id
        $this->compile_id = $this -> module . '|' . $theme . '|' . pnUserGetLang();

        // initialize the cache ID
        $this->cache_id = '';

        // expose templates
        $this->expose_template = (pnModGetVar('pnrender', 'expose_template') == true) ? true : false;

        if (pnModAvailable('Xanthia')){
            $this->userdb = 'userdb';
        }

        $this->register_block('nocache', 'pnRender_block_nocache', false);
    }

    /**
     * Checks whether requested template exists.
     *
     * @param string $template
     */
    function template_exists($template)
    {
        return (bool)$this->get_template_path($template);
    }

    /**
     * Checks which path to use for required template
     *
     * @param string $template
     */
    function get_template_path($template)
    {
		// the current module
        $pnmodgetname = pnModGetName();

        // get the module path to templates
        $module  = $this->module;
        $modinfo = pnModGetInfo(pnModGetIDFromName($module));

        // get the theme path to templates
        $theme = pnUserGetTheme();

        // prepare the values for OS
        $os_pnmodgetname = pnVarPrepForOS($pnmodgetname);
        $os_module       = pnVarPrepForOS($module);
        $os_modpath      = pnVarPrepForOS($modinfo['directory']);
        $os_theme        = pnVarPrepForOS($theme);

        // Define the locations in which we will look for templates
        // (in this order)
		// Note: Paths 1, 3, 5 - This allows for the hook or block functions
        // (such as ratings and comments) to use different templates depending
        // on the top level module. e.g. the comments dialog can be different
        // for news  and polls...
        // They are only evaluated when the calling module is not the current one.
        //
		// 1. The top level module directory in the requested module folder
		// in the theme directory.
		$themehookpath = "themes/$theme/templates/modules/$module/$pnmodgetname";
		// 2. The module directory in the current theme.
        $themepath = "themes/$theme/templates/modules/$module";
		// 3. The top level module directory in the requested module folder
		// in the modules sub folder.
		$modhookpath = "modules/$modinfo[directory]/pntemplates/$pnmodgetname";
		// 4. The module directory in the modules sub folder.
        $modpath = "modules/$modinfo[directory]/pntemplates";
		// 5. The top level module directory in the requested module folder
		// in the system sub folder.
        $syshookpath = "system/$modinfo[directory]/pntemplates/$pnmodgetname";
		// 6. The module directory in the system sub folder.
        $syspath = "system/$modinfo[directory]/pntemplates";

        $ostemplate = pnVarPrepForOS($template); //.'.htm';

        // check the module for which we're looking for a template is the
		// same as the top level mods. This limits the places to look for
		// templates.
		if ($module == $pnmodgetname) {
            $search_path = array($themepath,
                                 $modpath,
                                 $syspath);
		} else {
            $search_path = array($themehookpath,
                                 $themepath,
                                 $modhookpath,
                                 $modpath,
                                 $syshookpath,
                                 $syspath);
		}

	    foreach ($search_path as $path) {
            if (file_exists("$path/$ostemplate") && is_readable("$path/$ostemplate")) {
	    	    return $path;
    		}
        }

        // when we arrive here, no path was found
        return false;
    }

    /**
     * executes & returns the template results
     *
	 * This returns the template output instead of displaying it.
	 * Supply a valid template name.
	 * As an optional second parameter, you can pass a cache id.
	 * As an optional third parameter, you can pass a compile id.
     *
     * @param   string   $template    the name of the template
     * @param   string   $cache_id    (optional) the cache ID
     * @param   string   $compile_id  (optional) the compile ID
	 * @return  string   the template output
     */
    function fetch($template, $cache_id=null, $compile_id=null)
    {
        $this->_setup_template($template);

		if (!is_null($cache_id)) {
			$cache_id = $this->module . '|' . $cache_id;
		} else {
			$cache_id = $this->module . '|' . $this->cache_id;
		}

        $output = parent::fetch($template, $cache_id, $compile_id);

        if($this->expose_template == true) {
            $output = "\n<!-- begin of ".$this->template_dir."/$template -->\n"
                    . $output
                    . "\n<!-- end of ".$this->template_dir."/$template -->\n";
        }

        return $output;
    }

    /**
     * executes & displays the template results
     *
	 * This displays the template.
	 * Supply a valid template name.
	 * As an optional second parameter, you can pass a cache id.
	 * As an optional third parameter, you can pass a compile id.
     *
     * @param   string   $template    the name of the template
     * @param   string   $cache_id    (optional) the cache ID
     * @param   string   $compile_id  (optional) the compile ID
	 * @return  void
     */
    function display($template, $cache_id=null, $compile_id=null)
    {
        echo $this->fetch($template, $cache_id, $compile_id);
    }

    /**
     * finds out if a template is already cached
	 *
     * This returns true if there is a valid cache for this template.
	 * Right now, we are just passing it to the original Smarty function.
	 * We might introduce a function to decide if the cache is in need
	 * to be refreshed...
	 *
     * @param   string   $template    the name of the template
     * @param   string   $cache_id    (optional) the cache ID
	 * @return  boolean
     */
    function is_cached($template, $cache_id=null)
    {
        // insert the condition to check the cache here!
        // if (functioncheckdb($this -> module)) {
        //        return parent :: clear_cache($template, $this -> cache_id);
        //}
	    $this->_setup_template($template);

		if ($cache_id) {
			$cache_id = $this->module . '|' . $cache_id;
		} else {
			$cache_id = $this->module . '|' . $this->cache_id;
		}

        return parent::is_cached($template, $cache_id);
    }

    /**
     * clears the cache for a specific template
	 *
     * This returns true if there is a valid cache for this template.
	 * Right now, we are just passing it to the original Smarty function.
	 * We might introduce a function to decide if the cache is in need
	 * to be refreshed...
	 *
     * @param   string   $template    the name of the template
     * @param   string   $cache_id    (optional) the cache ID
     * @param   string   $compile_id  (optional) the compile ID
     * @param   string   $expire      (optional) minimum age in sec. the cache file must be before it will get cleared.
	 * @return  boolean
     */
    function clear_cache($template, $cache_id=null, $compile_id=null, $expire=null)
    {
		if ($cache_id) {
			$cache_id = $this->module . '|' . $cache_id;
		} else {
			$cache_id = $this->module . '|' . $this->cache_id;
		}
        // the cache ID must not end on a |
        $cache_id = preg_replace('/\|$/', '', $cache_id);

        return parent::clear_cache($template, $cache_id, $compile_id, $expire);
    }

    /**
     * clear the entire contents of cache (all templates)
     *
     * Smarty's original clear_all_cache function calls the subclasse's
     * clear_cache function. As we always prepend the module name, this
     * doesn't work here...
     *
     * @param string $exp_time expire time
     * @return boolean results of {@link smarty_core_rm_auto()}
     */
    function clear_all_cache($exp_time = null)
    {
        return parent::clear_cache(null, null, null, $exp_time);
    }

    /**
     * set up paths for the template
     *
     * This function sets the template and the config path according
     * to where the template is found (Theme or Module directory)
     *
     * @param   string   $template   the template name
     * @access  private
     */
    function _setup_template($template)
    {
        // default directory for templates
        $this->template_dir = $this->get_template_path($template);
		//echo $this->template_dir . '<br>';
        $this->config_dir   = $this->template_dir . '/config';
    }

    /**
     * add a plugins dir to _plugin_dir array
     *
     * This function takes  module name and adds two path two the plugins_dir array
     * when existing
     *
     * @param   string   $module    well known module name
     * @access  private
     */
    function _add_plugins_dir( $module )
    {
        $modinfo = pnModGetInfo(pnModGetIDFromName($module));
        $modpath = ($modinfo['type'] == 3) ? 'system' : 'modules';
        $mod_plugs = "$modpath/$modinfo[directory]/pntemplates/plugins";
        if (file_exists($mod_plugs)) {
            array_push($this->plugins_dir, $mod_plugs);
        }

// uncomment for .8 until modtype=3 for system modules is implemented
        $modpath = 'system';
        $mod_plugs = "$modpath/$modinfo[directory]/pntemplates/plugins";
        if (file_exists($mod_plugs)) {
            array_push($this->plugins_dir, $mod_plugs);
        }
    }

    /**
     * add core data to the template
     *
     * This function adds some basic data to the template depending on the
     * current user and the PN settings.
     *
     * @param   list of module names. all mod vars of these modules will be included too
                The mod vars of the current module will always be included
     * @return  boolean true if ok, otherwise false
     * @access  public
     */
    function add_core_data()
    {
        $pncore = array();
        $pncore['version_num'] = _PN_VERSION_NUM;
        $pncore['version_id'] = _PN_VERSION_ID;
        $pncore['version_sub'] = _PN_VERSION_SUB;
        $pncore['logged_in'] = pnUserLoggedIn();
        $pncore['language'] = pnUserGetLang();
        $pncore['themeinfo'] = pnThemeInfo(pnUserGetTheme());

    	pnThemeLoad($pncore['themeinfo']['name']);
		$colors = array();
        $colors['bgcolor1'] = pnThemeGetVar('bgcolor1');
        $colors['bgcolor2'] = pnThemeGetVar('bgcolor2');
        $colors['bgcolor3'] = pnThemeGetVar('bgcolor3');
        $colors['bgcolor4'] = pnThemeGetVar('bgcolor4');
        $colors['bgcolor5'] = pnThemeGetVar('bgcolor5');
        $colors['sepcolor'] = pnThemeGetVar('sepcolor');
        $colors['textcolor1'] = pnThemeGetVar('textcolor1');
        $colors['textcolor2'] = pnThemeGetVar('textcolor2');

        // add userdata
        $pncore['user'] = pnUserGetVars(pnSessionGetVar('uid'));

        // add modvars of current module
        $pncore[$this->module] = pnModGetVar($this->module);

        // add mod vars of all modules supplied as parameter
    	foreach (func_get_args() as $modulename) {
	        // if the modulename is empty do nothing
	        if(!empty($modulename) && !is_array($modulename) && ($modulename<>$this->module)) {
                // check if user wants to have /PNConfig
                if($modulename==_PN_CONFIG_MODULE) {
                    $pnconfig = pnModGetVar(_PN_CONFIG_MODULE);
                    foreach($pnconfig as $key => $value) {
                        // unserialize all config vars
            		    $pncore['pnconfig'][$key] = @unserialize($value);
                    }
                } else {
                    $pncore[$modulename] = pnModGetVar($modulename);
                }
            }
        }

        $this->assign('pncore', $pncore);
		$this->assign($colors);
        return true;
    }

}

/**
 * Smarty block function to prevent template parts from being cached
 *
 * @param $param
 * @param $content
 * @param $smarty
 * @return string
 **/
function pnRender_block_nocache($param, $content, &$smarty) {
    return $content;
}

?>