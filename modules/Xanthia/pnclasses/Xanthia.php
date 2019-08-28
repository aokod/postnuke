<?php
// $Id: Xanthia.php 17055 2005-11-16 21:42:45Z markwest $
// Xanthia Theme Engine FOR PostNuke Content Management System
// Copyright (C) 2003 By Larry E. Masters, Shorewood, Illinois.
// nut@phpnut.com
// http://www.larrymasters.com/
// http://www.phpnut.com/
// ----------------------------------------------------------------------
// Based on: Encompass Theme Engine - http://madhatt.info/
// Original Author: Brian K. Virgin (MADHATter7)
// Based on: NoMoreBlocks module - http://www.envolution.it
// Original Authors: ZXvision, TiMax, Cino
// ----------------------------------------------------------------------
// Based on:
// eNvolution Content Management System
// Copyright (C) 2002 by the eNvolution Development Team.
// http://www.envolution.com/
// ----------------------------------------------------------------------
// Based on:
// Postnuke Content Management System - www.postnuke.com
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

/**
 * @package Xanthia_Templating_Environment
 * @subpackage Xanthia
 * @license http://www.gnu.org/copyleft/gpl.html
*/

// check for direct call
if (strpos($_SERVER['PHP_SELF'], 'Xanthia.php')) {
    die ("You can't access this file directly...");
}

/**
 * @ignore
 */
    if (!defined('_XANTHIA_ROOT_PATH')) {
        $xanthiarootpath = pnModGetVar('Xanthia','rootpath');
        define('_XANTHIA_ROOT_PATH', $xanthiarootpath);
    }

// Load language file
    if (file_exists(_XANTHIA_ROOT_PATH . '/Xanthia/pnlang/' . pnVarPrepForOS(pnUserGetLang()). '/user.php')) {
        include_once _XANTHIA_ROOT_PATH . '/Xanthia/pnlang/' . pnVarPrepForOS(pnUserGetLang()). '/user.php';
    }

/**
 * Our class
 * 
 * @package     Xanthia_Templating_Environment
 * @subpackage  Xanthia
 */
    class Xanthia {

///////////////////////////////////////////////////////////////////////////////
// NO NEED TO EDIT BELOW THIS LINE                                           //
///////////////////////////////////////////////////////////////////////////////

// Internal (private) Variables
        var $DTS;            // DuskyBlue Template System object
        var $DTS_attrib;     // DTS global settings and configs
        var $colorset   = 1; // holds the custom colorset (DO NOT USE)
        var $thename;        // Basename of the skin directory
        var $themepath;      // Base directory for the skin
        var $skinspath;      // Base directory for the Active Skin
        var $imagepath;      // Base directory for the Active Skin images
        var $numArticles;    // News-index (dual column) config variable
        var $tmp1;           // News-index (dual column) config variable
        var $tmp2;           // News-index (dual column) config variable

        var $zone       = array();    // holds the Layout Zones which are Active

// holds general Engine Configuration info
// DO NOT EDIT THIS --YOU HAVE BEEN WARNED
        var $config     = array('mainpage'  => 'News');

        var $skins      = array();        // holds the Skin Configuration info

        var $cmod;
        var $componentid;
		var $pageid;
        var $qstring;
        var $permlevel;
        var $pnalng;
        var $isloggedin;

    //////
    // Initialize Xanthia (class constructor)
	// @access                private
	// @author                mh7
	// @since                 0.911                02-04-2002
	// @param                 none
	// @return                none
	// @see                   Xanthia::init_the_engine()
    function Xanthia()
    {
        // Begin all of the processes
		$this->init_the_engine();
    }

	//////
	// Trap legacy OpenTable and OpenTable2 calls in the output buffer
	// @access                private
	// @author                sentinel
	// @author                mh7
	// @since                0.911                02-04-2002
	// @param                string $begin the action we are to perform (start|stop)
	// @param                integer $tablenum controls the opentable or opentable2 calls
	// @return                object page output
	function do_themetable($begin, $tablenum='1')
	{
		// global $tablecount;        	
		list ($name, $module, $file, $authid) = pnVarCleanFromInput('name','module','file','authid');

		// Setup the proper zone we are going to display (table1|table2)
		$zone = 'table'.$tablenum;
                
		// Check to see which control command was sent
		if ($begin == 'start') {
			// Start the output buffer
			ob_start();
		} else {
			// Control command was end, assign buffer contents and close it
			$tablecontent = ob_get_contents();
			ob_end_clean();

			// Make sure we have a zone setup
			if (!empty($this->skins[$zone])) {
				// Assign the content to a DTS holder
				$tablecount = md5($tablecontent);
				$this->componentid = md5($this->qstring.$this->pnalng.'table'.$tablecount);

				if(!$this->DTS->is_cached($this->skins[$zone],$this->componentid.$this->isloggedin)) {
					$this->DTS->assign_by_ref('tablecontent', $tablecontent);
				}

				// Render the output
				$tplfile = $this->skins[$zone];
				$this->DTS->display("$this->tplresourcepath$tplfile",$this->componentid.$this->isloggedin);
			} else {
				// No template provided for a table so let's use a default wrapper
				// this allows legacy table templtes not to be provided by a theme
				if ($tablenum == 1) {
					echo '<div class="box1">'.$tablecontent.'</div>';
				} else {
					echo '<div class="box2">'.$tablecontent.'</div>';
				}
			}
		}
	}

	////
	// Displays the top portion of your Theme
	// @access                private
	// @author                mh7
	// @since                 0.911                02-04-2002
	// @param                 integer $index determines if the right blocks are dispalyed
	// @param                 string $name the name of the module we are displaying
	// @param                 string $file the modules file we are viewing
	// @param                 string $module --deprecated 0.925        25-08-2002        ~mh7
	// @return                none
	// @todo                  this function does prep and engages the output buffer
	//                        to trap all further output
	// @todo                  legacy theme function, includes logo, banners, site messages
	//                        blocks['left'] and topcenter blocks
	function do_themeheader($index)
	{
		// Grab our parameters in a secure (API compliant) manner
		list ($name, $module, $file, $authid) = pnVarCleanFromInput('name','module','file','authid');

		// set some empty defaults
		$leftblocks = '';
		$rightblocks = '';
		$centerblocks = '';

		// do we have permission to control block placements
		$chichiama=base64_encode(pnServerGetVar('REQUEST_URI'));
		$permesso=false;
		if (pnModGetVar('Xanthia', 'vba') == 1) {
			if (pnSecAuthAction(0, 'Blocks::', '::', ACCESS_ADMIN)) { 
			    $permesso=true;
			}
		}

		// Start the output buffer
		ob_start();
		// Call the right blocks
		xanthia_userapi_doBlocks('right');
		// Assign the contents
		$rightblocks = ob_get_contents();
		// Close the output buffer
		ob_end_clean();

		// Start the output buffer
		ob_start();
		// Call the right blocks
		xanthia_userapi_doBlocks('left');
		// Assign the contents
		$leftblocks = ob_get_contents();
		// Close the output buffer
		ob_end_clean();

		ob_start();
		xanthia_userapi_doBlocks('centre');
		$centerblocks = ob_get_contents();
		ob_end_clean();

        if ($permesso == true) {
			$chicco="<div style=\"text-align:right\"><a title=\""._XA_POSITION_TAG . _XA_LEFT."\" onMouseOver='window.status=\""._XA_POSITION_TAG . _XA_LEFT."\"; return true' onClick=\"win=window.open('index.php?module=Xanthia&amp;type=admin&amp;func=blockControl&amp;mod=$this->cmod&amp;zone=l&amp;chichiama=$chichiama', 'popup', 'width=300, height=280, resizable=0,toolbar=0, status=0');if (win.focus) win.focus();return false;\" href=\"index.php?module=Xanthia&amp;type=admin&amp;func=blockControl&amp;mod=$this->cmod&amp;zone=c&amp;chichiama=$chichiama\"target=\"popup\"><img src="._XANTHIA_ROOT_PATH."/Xanthia/pnimages/adminzn.jpg alt=\""._XA_POSITION_TAG . _XA_LEFT."\" /></a></div>";
            $chicco1="<div style=\"text-align:right\"><a title=\""._XA_POSITION_TAG . _XA_RIGHT."\" onMouseOver='window.status=\""._XA_POSITION_TAG . _XA_RIGHT."\"; return true' onClick=\"win=window.open('index.php?module=Xanthia&amp;type=admin&amp;func=blockControl&amp;mod=$this->cmod&amp;zone=r&amp;chichiama=$chichiama', 'popup', 'width=300, height=280, resizable=0,toolbar=0, status=0');if (win.focus) win.focus();return false;\" href=\"index.php?module=Xanthia&amp;type=admin&amp;func=blockControl&amp;mod=$this->cmod&amp;zone=c&amp;chichiama=$chichiama\"target=\"popup\"><img src="._XANTHIA_ROOT_PATH."/Xanthia/pnimages/adminzn.jpg alt=\""._XA_POSITION_TAG . _XA_RIGHT."\" /></a></div>";
            $chicco2="<div style=\"text-align:right\"><a title=\""._XA_POSITION_TAG . _XA_CENTER."\" onMouseOver='window.status=\""._XA_POSITION_TAG . _XA_CENTER."\"; return true' onClick=\"win=window.open('index.php?module=Xanthia&amp;type=admin&amp;func=blockControl&amp;mod=$this->cmod&amp;zone=c&amp;chichiama=$chichiama', 'popup', 'width=300, height=280, resizable=0,toolbar=0, status=0');if (win.focus) win.focus();return false;\" href=\"index.php?module=Xanthia&amp;type=admin&amp;func=blockControl&amp;mod=$this->cmod&amp;zone=c&amp;chichiama=$chichiama\"target=\"popup\"><img src="._XANTHIA_ROOT_PATH."/Xanthia/pnimages/adminzn.jpg alt=\""._XA_POSITION_TAG . _XA_CENTER."\" /></a></div>";					 
            $leftblocks = $chicco.$leftblocks;
			$rightblocks = $chicco1.$rightblocks;
			$centerblocks = $chicco2.$centerblocks;
        }

		//-zx
        $this->DTS->assign(array('leftblocks'    => $leftblocks,
            					 'rightblocks'   => $rightblocks,
								 'centerblocks'  => $centerblocks));


		$stringa = pnmodgetvar('Xanthia', $this->thename.'newzone');
		if ($stringa == '') {
			$lenarray = -1;
		} else {
			$chunk=split('[|]', $stringa);
			$lenarray=count($chunk);
		}

		for($i=0; $i<$lenarray; $i++) {
			$dati=split('[:]', $chunk[$i]);
			$k=$dati[0];
			// $v=@$dati[2];
			$v = isset($dati[2]) ? $dati[2] : null;
            if ($index != 3) {
				// Start the output buffer
				ob_start();
				// Call the xxx blocks
				xanthia_userapi_doBlocks("$k");
				// Assign the contents
				$unoblock = ob_get_contents();

				// Close the output buffer
				ob_end_clean();
	            if ($permesso == true){
					 $stringacmd="<div style=\"text-align:right\"><a title=\""._XA_POSITION_TAG . @$dati[2] . "\" onMouseOver='window.status=\""._XA_POSITION_TAG . @$dati[2] . "\"; return true' onClick=\"win=window.open('index.php?module=Xanthia&amp;type=admin&amp;func=blockControl&amp;mod=$this->cmod&amp;zone=$k&amp;chichiama=$chichiama','popup','width=300,height=280,resizable=0,toolbar=0, status=0');if (win.focus) win.focus();return false;\" href=\"index.php?module=Xanthia&amp;type=admin&amp;func=blockControl&amp;mod=$this->cmod&amp;zone=$k&amp;chichiama=$chichiama\" target=\"popup\"><img src="._XANTHIA_ROOT_PATH."/Xanthia/pnimages/adminzn.jpg alt=\""._XA_POSITION_TAG . @$dati[2] . "\" /></a></div>";
					 $unoblock = $stringacmd.$unoblock;                         
				}
				$this->DTS->assign($v, $unoblock);
			}
		}

		// Start the output buffering to capture module output
		ob_start();

	}

	////
	// Displays the bottom portion of your Theme
	// @access                private
	// @author                mh7
	// @since                 0.911                02-04-2002
	// @param                 integer $index controls if the right blocks are displayed
	// @param                 string $name name of the module we are to render
	// @param                 string $file name of the modules file we are rendering from
	// @param                 string $module --deprecated 0.925        25-08-2002        ~mh7
	// @return                object the entire page to be rendered
	// @todo                  this function completes the output buffering and defines
	//                        all holders for the DTS
	// @todo                  legacy theme function, includes footmsg, bottomlinks,
	//                        blocks['right'] and innerblock
	function do_themefooter($index)
	{
		// Gather our parameters in a secure [API compliant] manner
		list ($name, $module, $file, $authid, $type) = pnVarCleanFromInput('name','module','file','authid','type');

		// define the zone to be used		
		$zone1 = 'master';

		// register trim whitespace output filter if requried
		if (pnModGetVar('Xanthia', 'trimwhitespace')) {
			$this->DTS->load_filter('output','trimwhitespace');
		}
	
		// register short urls output filter if requried
		if (pnModGetVar('Xanthia', 'shorturls')) {
			$this->DTS->load_filter('output','shorturls');
		}

		// Define the master template
		$zone1 = 'master';

		// end output buffering and get module output
		$maincontent = ob_get_contents();
		ob_end_clean();

		// Assign the main content area to the template engine
		$this->DTS->assign_by_ref('maincontent', $maincontent);

		// assign the module information
		$this->DTS->assign('module', $this->cmod);

		// check if we should consider caching the page
		if ((!stristr($_SERVER['PHP_SELF'], 'admin.php')) && 
		    ($type != 'admin') && 
			(!in_array($this->cmod, $this->modulesnocache)) && 
			empty($_POST) && empty($authid)) {
	    	// use HTML cache system? 
			$this->DTS->caching = pnModGetVar('Xanthia', 'enablecache');
		}

		// deleted any expired cached pages
		pnModAPIFunc('Xanthia', 'user', 'clearcache');

		// Determine the correct Master template to call
		if (!empty($this->skins[$zone1])) {
			// Render the page using master template
			if (stristr($_SERVER['PHP_SELF'], 'user') && !empty($this->skins['*user'])) {
				$tplfile = $this->skins['*user'];
				$this->DTS->display("$this->moduletplresourcepath$tplfile",$this->pageid);
			} else if ((stristr($_SERVER['PHP_SELF'], 'admin') || strtolower($type) == 'admin') && !empty($this->skins['*admin'])) {
				$tplfile = $this->skins['*admin'];
				$this->DTS->display("$this->moduletplresourcepath$tplfile",$this->pageid);
			} else if (empty($name) && empty($module) && !empty($this->skins['*home']) && !stristr($_SERVER['PHP_SELF'], 'user') && !stristr($_SERVER['PHP_SELF'], 'admin')) {
				$tplfile = $this->skins['*home'];
				$this->DTS->display("$this->moduletplresourcepath$tplfile",$this->pageid);
			} else if (!empty($this->skins['M-'.$this->cmod])) {
				$tplfile = $this->skins['M-'.$this->cmod];
				$this->DTS->display("$this->moduletplresourcepath$tplfile",$this->pageid);
			} else {
				$tplfile = $this->skins[$zone1];
				$this->DTS->display("$this->tplresourcepath$tplfile",$this->pageid);
			}
		} else {
			// No template for a main zone, trigger error
			// @todo                need to implement better error processing
			$this->tpl_config_error(_XA_MAINZONENOTPL, $zone1);
		}
	}

	////
	// Display the stories on the main news page
	// @access                private
	// @author                mh7
	// @author                sentinel
	// @since                 0.911                02-04-2002
	// @param                 array $info holds the info array built by the news module
	// @param                 array $links holds the links array built by the news module
	// @param                 array $preformat holds the preformatted array built by the news module
	// @param                 integer $index controls if the right blocks are displayed
	// @param                 string $name the name of the module to render
	// @param                 string $file the name of the modules file
	// @return                object the page output
	// @todo                  seperate the dual news column code into an addon in order to
	//                        cleanup this section and remove that code if not needed
	function do_themeindex($info, $links, $preformat, $index)
	{
		// Define the zone (News-index)
		$zone = 'News-index';

		// set a default for indexcol
		if (!isset($this->config['indexcol'])) {
			$this->config['indexcol'] = 1;
		}

		//rickup
		if (isset($info['skins']) == '') {
			//rickup added (@ )
			$this->skins[$zone] = (@$this->skins['backup']);
		} else {
			if (!file_exists($this->skinspath.'/templates/news/'.$info['skins'])) {
				$info['skins'] = $this->skins['backup'];
				$this->skins[$zone] = $info['skins'];
			} else {
				$this->skins[$zone] = 'news/'.$info['skins'];
			}
		}
		// Setup the News Arrays
		$this->set_pn_attrib($info, $links, $preformat);

		// Dual Column News Page
		if (@$this->config['indexcol'] == '2') {
			// Increase the number of articles counter
			$this->numArticles++;
			if($this->numArticles > 2) {
				// More than 2 articles
				// If the number of articles/2 has no remainder
				if(($this->numArticles % 2) == 0) {
					// Fetch the output of the template and assign it to tmp2
					// Render the page
					$tplfile = $this->skins[$zone];
					$this->tmp2 = $this->DTS->fetch("$this->tplresourcepath$tplfile",$this->componentid.$this->isloggedin);

					// Assign the holder tags for the two column news page
					$this->DTS->assign_by_ref('column1', $this->tmp1);
					$this->DTS->assign_by_ref('column2', $this->tmp2);

					// Verify a template has been assigned to this zone
					if (!empty($this->skins[$zone.'2'])) {
						// Render the output
						$tplfile = $this->skins[$zone.'2'];                           
						$this->DTS->display("$this->tplresourcepath$tplfile",$this->componentid.$this->isloggedin); //dualindex
					}
				} else {
					// Fetch the output of the template and assign it to tmp1
					// Render the page
					$tplfile = $this->skins[$zone]; 
					$this->tmp1 = $this->DTS->fetch("$this->tplresourcepath$tplfile",$this->componentid.$this->isloggedin);
				}
			} else {
				// Less than two articles
				// Check to see if the zone is active
				if (!empty($this->skins[$zone])) {
					// Render the page
					$tplfile = $this->skins[$zone]; 
					$this->DTS->display("$this->tplresourcepath$tplfile",$this->componentid.$this->isloggedin);
				} else {
					// No template for a main zone, trigger error
					// @todo                need to implement better error processing
					$this->tpl_config_error(_XA_MAINZONENOTPL, $zone);
				}
			}
		}

		// Single Column News Page
		//rickup
		if (@$this->config['indexcol'] == '1') {
			// Check to see if this zone is active
			if (!empty($this->skins[$zone])) {
				// Render the page
				$tplfile = $this->skins[$zone];
				$this->DTS->display("$this->tplresourcepath$tplfile",$this->componentid.$this->isloggedin);
			} else {
				// No template for a main zone, trigger error
				// @todo                need to implement better error processing
				$this->tpl_config_error(_XA_MAINZONENOTPL, $zone);
			}
		}
	}

	////
	// Display the entire article on its own page
	// @access                private
	// @author                mh7
	// @since                 0.911                02-04-2002
	// @param                 array $info holds the info array built by the news module
	// @param                 array $links holds the links array built by the news module
	// @param                 array $preformat holds the preformatted array built by the news module
	// @param                 string $name the name of the module to render
	// @param                 string $file the name of the modules file
	// @return                object the page output
    function do_themearticle($info, $links, $preformat)
	{
		// Gather our parameters in a secure [API compliant] manner
		list ($file, $authid) = pnVarCleanFromInput('file','authid');

		//if (!isset($name)) { $name = 'News'; }
		$name = pnModGetName();

		// Assign the module name and file to a zone
		$zone = "$name-$file";

		// Send info, links and preformat arrays to be assigned
		$this->set_pn_attrib($info, $links, $preformat);

		// Verify the zone is active
		if (!empty($this->skins[$zone])) {
			// Render the page
			$tplfile = $this->skins[$zone];
			$this->DTS->display("$this->tplresourcepath$tplfile",$this->componentid.$this->isloggedin);
		} else {
			// No template for a main zone, trigger error
			// @todo                need to implement better error processing
			$this->tpl_config_error(_XA_MAINZONENOTPL, $zone);
		}
	}

	////
	// Display the sideboxes using templates
	// @access                private
	// @author                mh7
	// @since                 0.911                02-04-2002
	// @param                 array $block sideblock info
	// @param                 integer $index --deprecated 0.925        25-08-2002        ~mh7
	// @return                object the themed sideblock output
	// @todo                  find a faster/better way to format block titles,
	//                        currently uses: ereg_replace("[^0-9a-z_]","",$blocktitle);
    function do_themesidebox($block, $index='0')
	{
		list ($name, $module, $file, $authid) = pnVarCleanFromInput('name','module','file','authid');

		// If there is no position info, this controls the look of the block
		// (l=left; r=right; d=default; c=center etc.)
		if(empty($block['position'])) {
		    $block['position'] = 'd';
		}

		if (is_numeric($block['position'])) {
			$block['position'] = 'd';
		}
		// New [0.710] method:        Yes, I know it's ugly (PN Centre Block is BAD)
        $rowvisual = '';
		if($block['position'] == 'c') {
			if (isset($this->zone['ccblock'])) {
            } else {
				if (pnModGetVar('Xanthia', 'vba') == 1) {
					if (pnSecAuthAction(0, 'Blocks::', '::', ACCESS_ADMIN)) {
						$chichiama=base64_encode('http://'.pnServerGetVar('HTTP_HOST').pnServerGetVar('REQUEST_URI'));  //che fare con la funzione di pn
						$blockbid = $block['bid'];
						$blockname = strip_tags($block['title']);
						$rowvisual = "<div style=\"text-align:center\"><a title=\""._XA_EDITBLOCK . $blockname ."\" onMouseOver='window.status=\""._XA_EDITBLOCK . $blockname ."\"; return true' onClick=\"win=window.open('index.php?module=Xanthia&amp;type=admin&amp;func=blockControlAdmin&amp;bid=$blockbid&amp;mod=$this->cmod&amp;chichiama=$chichiama', 'popup', 'width=300, height=300, resizable=0,toolbar=0, status=0');  if (win.focus) win.focus(); return false;\"  href=\" \" target=\"popup\"><img src=\""._XANTHIA_ROOT_PATH."/Xanthia/pnimages/adminbl.jpg\" alt=\""._XA_EDITBLOCK . $blockname ."\" /></a></div>";
					}
				}
				echo "$rowvisual<div class=\"pn-normal\">$block[content]</div>";
			}
		}

		// Determine the postion and template for the block
		//rickup
		//Notice: Use of undefined constant position - assumed 'position' 
		if ($block['position'] == 'c' || $block['position'] == 't' || $block['position'] == 'b') {
			// Assign different templates to top and bottom centerblocks
			$zone = $block['position'].'cblock';
		} else {
			// Normal left, right or inner sideblock templates
			$zone = $block['position'].'sblock';
		}

		//     phpnut
		if (pnModGetVar('Xanthia', 'vba') == 1) {
			if (pnSecAuthAction(0, 'Blocks::', '::', ACCESS_ADMIN)) {
				$chichiama=base64_encode('http://'.pnServerGetVar('HTTP_HOST').pnServerGetVar('REQUEST_URI'));  //che fare con la funzione di pn
				$blockbid=$block['bid'];
				$blockname = strip_tags($block['title']);
				$rowvisual = "<div style=\"text-align:center\"><a title=\""._XA_EDITBLOCK . $blockname ."\" onMouseOver='window.status=\""._XA_EDITBLOCK . $blockname ."\"; return true' onClick=\"win=window.open('index.php?module=Xanthia&amp;type=admin&amp;func=blockControlAdmin&amp;bid=$blockbid&amp;mod=$this->cmod&amp;chichiama=$chichiama', 'popup', 'width=300, height=300, resizable=0,toolbar=0, status=0');  if (win.focus) win.focus(); return false;\"  href=\" \" target=\"popup\"><img src=\""._XANTHIA_ROOT_PATH."/Xanthia/pnimages/adminbl.jpg\" alt=\""._XA_EDITBLOCK . $blockname ."\" /></a></div>";
				$block['content'] = $rowvisual.$block['content'];
			}
		}
		/*$this->DTS->assign(array('title'   => $block['title'],
								 'content' => $block['content'],
								 'bid'     => $block['bid']));*/
		$this->DTS->assign($block);

        // Convert block[title] to lowercase / remove all non-alpha characters
        $blocktitle = strtolower(strip_tags($block['title']));
        $blocktitle = ereg_replace('[^0-9a-z_]','',$blocktitle);

       if (isset($this->zone[$blocktitle])) {
			$tablecount = md5($block['content']);
			$this->componentid = md5($this->qstring.$this->pnalng.$blocktitle.$tablecount);
            // Yes, display the "highlighted" block template
            $tplfile = $this->skins[$blocktitle];
            $this->DTS->display("$this->blocktplresourcepath$tplfile",$this->componentid.$this->isloggedin);
        } else {
            // No, display the default template for each side
			if (!empty($this->skins[$zone])) {
                 $tablecount = md5($block['content']);
                 $this->componentid = md5($this->qstring.$this->pnalng.$blocktitle.$tablecount);
                 $tplfile = $this->skins[$zone];
                 $this->DTS->display("$this->blocktplresourcepath$tplfile",$this->componentid.$this->isloggedin);
			}
        }
    }

	///////////////////////////////////////////////////////////////////////////////
	// BEGIN ENGINE CONFIG METHODS                                               //
	///////////////////////////////////////////////////////////////////////////////

	////
	// Assigns the PN info, links and preformat arrays to the template engine
	// @access                private
	// @author                sentinel
	// @author                mh7
	// @since                 0.911                02-04-2002
	// @param                 array $info article info array
	// @param                 array $links article links array
	// @param                 array $preformat article preformatted info array
	// @return                none
	function set_pn_attrib($info, $links, $preformat)
	{
		$this->DTS_attrib = array('info'  		=> $info,
								  'links' 		=> $links,
								  'preformat'	=> $preformat
		);
		$this->DTS->assign($this->DTS_attrib);
	}

	////
	// Begin the initilization process for Xanthia
	// @access                private
	// @author                mh7
	// @since                 0.911                02-04-2002
	// @param                 none
	// @return                none
	// @todo                  creates the DTS object for the class assigns values to internal variables
	// @todo                  MULTI-COLOR TEMPLATES NOT IMPLEMENTED
	function init_the_engine()
	{
		// Load user API
		if (!pnModAPILoad('Xanthia', 'user')) {
			pnSessionSetVar('errormsg', _XA_APILOADFAILED);
		}

		// get variables from input
		list ($name, $module, $file, $authid, $type) = pnVarCleanFromInput('name','module','file','authid', 'type');

		// set some basic class variables from the PN environemnt
		$this->pnalng = pnUserGetLang();
		$this->isloggedin = pnUserLoggedIn();

		// Assign the query string
		$this->qstring = @$_SERVER['QUERY_STRING'];
		// Assign the current script
		$this->phpself = $_SERVER['PHP_SELF'];

		// Assign the user id
		$this->uid = pnUserGetVar('uid');

		// Assign the skins name and ID
		$this->skins['name']  = pnModAPIFunc('Xanthia','user','getSkinName');
		$this->skins['id']    = pnModAPIFunc('Xanthia','user','getSkinID', array('skin' => $this->skins['name']));

		// set the module name
		$this->cmod = pnModGetName();

		// Assign the cache id
		if ($this->isloggedin) {
		    $this->pageid = $this->cmod.'|'.$this->skins['name'].'|'.$this->pnalng.'|'.$this->uid.'|'.$this->phpself.''.$this->qstring;
			//$this->pageid = md5($this->skins['name'].''.$this->phpself.''.$this->qstring.''.$this->pnalng.''.$this->uid);
		} else {
		    $this->pageid = $this->cmod.'|'.$this->skins['name'].'|'.$this->pnalng.'|'.$this->phpself.''.$this->qstring;
			//$this->pageid = md5($this->skins['name'].''.$this->phpself.''.$this->qstring.''.$this->pnalng.'');
		}

		// Assign some basic paths for the engine and skins
		$this->thename      = $this->skins['name'];
		if (is_dir(WHERE_IS_PERSO.'themes/'.pnVarPrepForOS($this->thename))) {
			$this->themepath    = WHERE_IS_PERSO.'themes/'.$this->thename;
		} else {
			$this->themepath    = 'themes/'.$this->thename;
		}

		// Backwards compatability with previous Xanthia themes
		$this->skinspath    = $this->themepath;
		$this->imagepath    = $this->skinspath.'/images';

		// modules not to cache
		$this->modulesnocache = explode(',', pnModGetVar('Xanthia', 'modulesnocache'));

		// Initialize the DuskyBlue Theme System (templates)
		$this->DTS = pnModAPIFunc('Xanthia','user','themeInit',array('skin' => $this->skins['name']));
		if (pnModGetVar('Xanthia', 'db_templates')) {
			$this->tplresourcepath = "userdb:$this->thename/";
			$this->blocktplresourcepath = $this->tplresourcepath;
			$this->moduletplresourcepath = $this->tplresourcepath;
		} else {
			$this->tplresourcepath = '';
			$this->blocktplresourcepath = 'blocks/';
			$this->moduletplresourcepath = 'modules/';
		}

		// Intialize the layout & templates
		$this->init_layout($this->skins['id'], $this->thename, $this->cmod);

		// if we've some post values then clear the cache
		if (!empty($_POST) || !empty($authid)) {
			$this->DTS->clear_cache(null, $this->cmod);
			// now clear the cache for any hooked module
			$hooks = pnModAPIFunc('Modules', 'admin', 'gethookedmodules', array('hookmodname' => $this->cmod));
			foreach($hooks as $modname => $hook) {
				$this->DTS->clear_cache(null, $modname);
			}
			// quick .7x hack - if the module is comments then clear the news cache too
			if ($this->cmod == 'Comments' || $this->cmod == 'AddStory') {
				$this->DTS->clear_cache(null, 'News');
			}
		}

		// check if we should consider caching the page
		if ((!stristr($_SERVER['PHP_SELF'], 'admin.php')) && ($type != 'admin') && (!in_array($this->cmod, $this->modulesnocache)) && ($this->cmod != 'Admin') 
		    && empty($POST) && empty($authid)){
			// we now have enough information to determine if the page is cached
			$zone = 'master';
			// we now set caching on (if the admin has requested it) so we can check
			// for a cache hit
			$this->DTS->caching = pnModGetVar('Xanthia', 'enablecache');
			// Determine the correct Master template to call
			if (!empty($this->skins[$zone])) {
				// Render the page using master template
				if (stristr($_SERVER['PHP_SELF'], 'user') && !empty($this->skins['*user'])) {
					$tplfile = $this->skins['*user'];
					if ($this->DTS->is_cached("$this->moduletplresourcepath$tplfile",$this->pageid)) {
						$this->DTS->display("$this->moduletplresourcepath$tplfile",$this->pageid);
						exit;
					}
				} else if (empty($name) && empty($module) && !empty($this->skins['*home'])) {
					$tplfile = $this->skins['*home'];
					if ($this->DTS->is_cached("$this->moduletplresourcepath$tplfile",$this->pageid)) {
						$this->DTS->display("$this->moduletplresourcepath$tplfile",$this->pageid);
						exit;
					}
				} else if (!empty($this->skins['M-'.$this->cmod])) {
					$tplfile = $this->skins['M-'.$this->cmod];
					if ($this->DTS->is_cached("$this->moduletplresourcepath$tplfile",$this->pageid)) {
						$this->DTS->display("$this->moduletplresourcepath$tplfile",$this->pageid);
						exit;
					}
				} else {
					$tplfile = $this->skins[$zone];
					if ($this->DTS->is_cached("$this->tplresourcepath$tplfile",$this->pageid)) {
						$this->DTS->display("$this->tplresourcepath$tplfile",$this->pageid);
						exit;
					}
				}
			} else {
				// No template for a main zone, trigger error
				// @todo                need to implement better error processing
				$this->tpl_config_error(_XA_MAINZONENOTPL, $zone1);
			}
			// since we don't want to cache subtemplates (to save disk space)
			// we now turn caching off
			$this->DTS->caching = false;
		}

		// Assign the skins general configs
		$this->init_general_config();

		// Assign custom configs for the DTS
		$this->init_theme_system();

		// Turn off deprecated colors option
		$this->config['multicolor'] = 0;

		// Load the skins colors
		$selected = pnModGetVar('Xanthia',$this->skins['name'].'use');
		$colors = pnModAPIFunc('Xanthia','user','getSkinColors', array('skinid' => $this->skins['id'],
                                                                       'paletteid' => $selected));
		// Assign the skins colors to the template system
		$this->assign_global_colors($colors);

		// Assign some other variables (dual news column support)
		$this->pasthead = 0;
		$this->numArticles = 0;
		global $nomedeltema;

		$nomedeltema = $this->thename;

	}

	////
	// Load and assign the skins general configs
	// @access                private
	// @author                mh7
	// @since                 02-04-2002
	// @param                 none
	// @return                none
	function init_general_config()
	{
		if (file_exists(pnConfigGetVar('temp') . '/Xanthia_Config/'. pnVarPrepForOS($this->thename) . '.settings.php')) {
			include(pnConfigGetVar('temp') . '/Xanthia_Config/'. pnVarPrepForOS($this->thename) . '.settings.php');
		} else {
			// Setup the DB handle
			$dbconn =& pnDBGetConn(true);
			$pntable =& pnDBGetTables();
	
			// Define the table and columns info
			$table = $pntable['theme_config'];
			$column = &$pntable['theme_config_column'];
	
			// Build the query
			$query = "SELECT $column[name],
							 $column[setting]
					  FROM $table
					  WHERE $column[skin_id]='".pnVarPrepForStore($this->skins['id'])."'";
	
			// Execute the query
			$result =& $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				// Report the DB error
				$this->db_error((__FILE__), (__LINE__), 'init_general_config',
						$query, $dbconn->ErrorNo(), $dbconn->ErrorMsg());
			} else {
				if (!$result->EOF) {
					// Iterate through the results, assign to config
					// modified for Oracle compatibility
					while(!$result->EOF){
						list($name, $setting) = $result->fields;
						// Move the pointer !
						$result->MoveNext();
						$this->config[$name] = $setting;
					}
	
					// Close the result set
					$result->Close();
				}
			}
		}
	}

	////
	// Load the active zone info
	// @access                private
	// @author                mh7
	// @since                 0.911                02-04-2002
	// @param                 none
	// @return                none
	function init_layout($skinID='', $theme='', $mod='' )
	{
		static $themezones;
		if (file_exists(pnConfigGetVar('temp') . '/Xanthia_Config/'. pnVarPrepForOS($theme) . '.' . pnVarPrepForOS($mod) . '.tplconfig.php')&& 
		    !isset($themezones)) {
			include(pnConfigGetVar('temp') . '/Xanthia_Config/'. pnVarPrepForOS($theme) . '.' . pnVarPrepForOS($mod) . '.tplconfig.php');
		}
		if (!isset($themezones)) {
			// Assign the DB handle
			$dbconn =& pnDBGetConn(true);
			$pntable =& pnDBGetTables();
	
			// Define the table columns info
			$column = &$pntable['theme_zones_column'];
	
			// Build the query
			$query = "SELECT $column[label] as label ,
							 $column[is_active] as active,
							 $column[skin_type] as type
					  FROM   $pntable[theme_zones]
					  WHERE  $column[is_active]='1'
					  AND    $column[skin_id]='".pnVarPrepForStore($skinID)."'";
	
			// Execute the query
			$result =& $dbconn->Execute($query);
	
			if ($dbconn->ErrorNo() != 0) {
				// Report the DB error
				$this->db_error((__FILE__), (__LINE__), 'init_layout',
						$query, $dbconn->ErrorNo(), $dbconn->ErrorMsg());
			} else {
				if (!$result->EOF) {
					// Iterate through the results, assign to zones
					//modified for Oracle compatibility
					$themezones = array();
					while(!$result->EOF){
						$row = $result->GetRowAssoc(false);
						$themezones[]  = $row;
						$result->MoveNext();
					}
					// Close the result set
					$result->Close();
					if (isset($this->skins['News-index'])) {
						$this->skins['backup'] = $this->skins['News-index'];
					}
				}
			}
		}

		if (!empty($themezones)) {
			foreach ($themezones as $themezone) {
				$this->zone[$themezone['label']] = $themezone['active'];
				$this->init_template($skinID, $themezone['label'], $theme, $mod, $themezone['type']);
				
				if (isset($this->skins['News-index'])) {
					$this->skins['backup'] = $this->skins['News-index'];
				}
			}
		}
	}

	////
	// Assign templates for active zones
	// @access                private
	// @author                mh7
	// @since                 v0.911                02-04-2002
	// @param                 integer $skinID the active skins ID
	// @param                 string $zone the zone to which we are assigning a template
	// @return                none
	// @todo                  error handling improvements
	function init_template($skinID='', $zone, $theme, $mod, $type)
	{
		static $blocktemplates, $zonetemplates;

		if (file_exists(pnConfigGetVar('temp') . '/Xanthia_Config/'. pnVarPrepForOS($theme) . '.' . pnVarPrepForOS($mod) . '.tplconfig.php') && 
		    !isset($blocktemplates) && !isset($zonetemplates)) {
			include(pnConfigGetVar('temp') . '/Xanthia_Config/'. pnVarPrepForOS($theme) . '.' . pnVarPrepForOS($mod) . '.tplconfig.php');
		}
		
		if($type == 'block'){
			if (!isset($blocktemplates)) {		
				// Setup the DB handle
				$dbconn =& pnDBGetConn(true);
				$pntable =& pnDBGetTables();
	
				// Checdk our parameters
				if (empty($skinID)) {
					// Assign the active skin
					// @see                xanthia_userapi_getSkinName()
					$skinID = $this->skins['id'];
				}
		
				// Setup the table columns info
				$blcontrolcolumn = &$pntable['theme_blcontrol_column'];
				// Build the query
				$sql = "SELECT $blcontrolcolumn[blocktemplate] as blocktemplate,
								$blcontrolcolumn[identi] as identi
								FROM $pntable[theme_blcontrol]
								WHERE $blcontrolcolumn[theme]='".pnVarPrepForStore($theme)."'
								AND $blcontrolcolumn[module]='".pnVarPrepForStore($mod)."'
								AND $blcontrolcolumn[blocktemplate] !=''";
	
				// Execute the query
				$result =& $dbconn->Execute($sql);
	
				$blocktemplates = array();
				while(!$result->EOF) {
					$row = $result->GetRowAssoc(false);
					$blocktemplates[] = $row;
					$result->MoveNext();
				}
			}
		}

        if (!empty($blocktemplates) && is_array($blocktemplates) && $type == 'block') {
			foreach ($blocktemplates as $blocktemplate) {
				if (is_numeric($blocktemplate['identi'])){
					$btitle = pnBlockGetInfo($blocktemplate['identi']);
					$blocktitle = strtolower(strip_tags($btitle['title']));
					$blocktitle = ereg_replace("[^0-9a-z_]","",$blocktitle);
				}else{
					$blocktitle = $blocktemplate['identi'];
					
				}
				if ($blocktitle == $zone) {
					$foundtemplate = $blocktemplate['blocktemplate'];
					
				}
			}
		}

		//echo "$skinID $zone $theme $mod";
		if (!empty($foundtemplate)) {
		    // template found, assign it, close result set
			$template = $foundtemplate;

			// Make sure this zone is active
			if (!empty($this->zone[$zone])) {
				// Verify a template file was found
				if (!empty($template)) {
					// Assign the template to this zone
					$this->skins[$zone] = "$template";
				}
			}   
        } else {              
		    if (!isset($zonetemplates)) {
			
				// Setup the DB handle
				$dbconn =& pnDBGetConn(true);
				$pntable =& pnDBGetTables();
   
				// Setup the table columns info
				$column = &$pntable['theme_layout_column'];

				// Build the query
				$query = "SELECT $column[tpl_file] as template,
  								$column[zone_label] as zone
						  FROM $pntable[theme_layout]
						  WHERE $column[skin_id]='".pnVarPrepForStore($skinID)."'";
		
				// Execute the query
				$result =& $dbconn->Execute($query);
				$zonetemplates = array();
				while(!$result->EOF) {
					$row = $result->GetRowAssoc(false);
					$zonetemplates[$row['zone']] = $row['template'];
					$result->MoveNext();
				}
				$result->Close();
			}
			if (isset($zonetemplates[$zone])) {
				// template found, assign it, close result set
				$template = $zonetemplates[$zone];
				
				// Make sure this zone is active
				if (!empty($this->zone[$zone])) {
					// Verify a template file was found
					if (!empty($template)) {
						// Assign the template to this zone
						$this->skins[$zone] = "$template";
					}
				}
			} else {
				// No template specified in the dB, try the default filename
				if (file_exists($this->skinspath."/templates/$zone.tpl")) {
					// Template found, use it by default
					$this->skins[$zone] = "$zone.tpl";
				} else {
					if (file_exists($this->skinspath."/templates/$zone.htm")) {
						//FIXME: tpl sempre prima di htm
						$this->skins[$zone] = "$zone.htm";
					} else {
						// No Template found, turn off the Zone
						pnSessionSetVar('errormsg', _XA_NOZONEFOUND.": $zone");
						$this->zone[$zone] = '';
						$this->skins[$zone] = '';
					}
				}
			}
		}
	}

	////
	// Assign the skins colors to the template system
	// @access                private
	// @author                mh7
	// @since                 0.911                02-04-2002
	// @param                 array $colors the skins colors to be assigned
	// @return                none
	// @todo                  MULTICOLOR SKINS NOT IMPLEMENTED        --deprecated 0.925
	function assign_global_colors($colors)
	{
		static $colors;

		// Verify our parameters
		if (empty($colors)) {
			// Load the active skins colors
			// @see xanthia_userapi_getSkinColors()
			$skinName = pnModAPIFunc('Xanthia','user','getSkinName');
			$selected = pnModGetVar('Xanthia',$this->skins['name'].'use');
			$colors = pnModAPIFunc('Xanthia','user','getSkinColors',array('skinid' => $this->skins['id'],
                                                                          'paletteid' => $selected));
		}

		// See if we are dealing with a multicolor skin
		if ($this->config['multicolor'] == 0) {
			// No, define the colors for the template engine
			$this->DTS_attrib = array('palette_name'=> $colors['palette_name'],
			                          'bgcolor'     => $colors['background'],
									  'color1'      => $colors['color1'],
									  'color2'      => $colors['color2'],
									  'color3'      => $colors['color3'],
									  'color4'      => $colors['color4'],
									  'color5'      => $colors['color5'],
									  'color6'      => $colors['color6'],
									  'color7'      => $colors['color7'],
									  'color8'      => $colors['color8'],
									  'sepcolor'    => $colors['sepcolor'],
									  'text1color'  => $colors['text1'],
									  'text2color'  => $colors['text2'],
									  'linkcolor'   => $colors['link'],
									  'vlinkcolor'  => $colors['vlink'],
									  'hovercolor'  => $colors['hover']);

			// Assign them for global template use
			$this->DTS->assign($this->DTS_attrib);
		}
	}

	///////////////////////////////////////////////////////////////////////////////
	// BEGIN ENGINE TEMPLATE SYSTEM INIT                                         //
	///////////////////////////////////////////////////////////////////////////////

	////
	// Assign template system attributes and configs
	// @access                private
	// @author                mh7
	// @since                 0.911                02-04-2002
	// @param                 none
	// @return                none
	// @todo                  DTS cache (HTML) not properly implemented
	function init_theme_system()
	{
		// Define some helpful paths and holder tags
		$this->DTS_attrib = array('themepath'     => $this->skinspath,     // the skins path (themes/$skinName)
								  'imagepath'     => $this->imagepath);    // image path ($themepath/images)

		// Assign to the template engine for global template use
		$this->DTS->assign($this->DTS_attrib);
		
		// auto assign any theme settings
		$this->DTS->assign($this->config);
	}

	///////////////////////////////////////////////////////////////////////////////
	// BEGIN ENGINE ERROR HANDLING METHODS                                       //
	///////////////////////////////////////////////////////////////////////////////

    //////
    // function:    db_error
    //
    // purpose:     generic db error routine
	function db_error($file='', $line='', $function='', $query='', $ErrorNo, $ErrorMsg)
	{
		echo "<br />"
				."<table width=\"100%\" cellpadding=\"1\" "
				."cellspacing=\"0\" border=\"0\">"
				."<tr><td colspan=\"2\" align=\"center\">"
				."<span class=\"pn-title\"><em>DB Error $ErrorNo</em>"
				."</span></td>"
				."</tr><tr>"
				."<td><span class=\"pn-normal\"><strong>File:</strong>"
				."</span></td><td><span class=\"pn-sub\">$file</span></td>"
				."</tr><tr>"
				."<td><span class=\"pn-normal\"><strong>Function:</strong>"
				."</span></td><td><span class=\"pn-sub\">$function</span></td>"
				."</tr><tr>"
				."<td><span class=\"pn-normal\"><strong>Line No:</strong>"
				."</span></td><td><span class=\"pn-sub\">$line</span></td>"
				."</tr><tr>"
				."<td><span class=\"pn-normal\"><strong>Query:</strong>"
				."</span></td><td><span class=\"pn-sub\">$query</span></td>"
				."</tr><tr>"
				."<td><span class=\"pn-normal\"><strong>Message:</strong>"
				."</span></td><td><span class=\"pn-sub\">$ErrorMsg</sapn></td>"
				."</tr></table>";
		exit;
	}

	//////
	// function:        tpl_config_error()
	//
	// purpose:         displays template config errors
	function tpl_config_error($message='', $area='')
	{
		echo _XA_ANERROROCCURED." "._XA_INZONE.": $area<br />";
		echo "<strong>$message</strong><br />";
		exit;
	}

} // END Class Xanthia

?>