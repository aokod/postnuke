<?php
// File: $Id: search.php 16485 2005-07-24 01:52:11Z markwest $
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
// Original Author of file: Francisco Burzi
// Purpose of file:
// ----------------------------------------------------------------------

if (strpos($_SERVER['PHP_SELF'], 'search.php')) {
	die ("You can't access this file directly...");
}

$blocks_modules['search'] = array(
    'func_display' => 'blocks_search_block',
    'func_update' => 'blocks_search_update',
    'func_edit' => 'blocks_search_edit',
    'text_type' => 'Search',
    'text_type_long' => 'Search Box',
    'allow_multiple' => false,
    'form_content' => false,
    'form_refresh' => false,
    'show_preview' => true
);

define('_SEARCH_DISPLAY_BTN','displaySearchBtn' );

// Security
pnSecAddSchema('Searchblock::', 'Block title::');

function blocks_search_block($row) {

    if (!pnSecAuthAction(0, 'Searchblock::', "$row[title]::", ACCESS_READ)) {
        return;
    }

    $vars = getVarsFrom_search_Content($row);

    $content = "<form method=\"post\" action=\"index.php\"><div>"
        ."<input type=\"hidden\" name=\"name\" value=\"Search\" />"
        ."<input type=\"hidden\" name=\"action\" value=\"search\" />"
        ."<input type=\"hidden\" name=\"overview\" value=\"1\" />";
        
    $content .= "<br /><div style=\"text-align:center\"><input type=\"text\" name=\"q\" size=\"14\" />";
    if(isset($vars[_SEARCH_DISPLAY_BTN])) {
       $content .= ' <input type="submit" value="'._SEARCH.'" />';
    }
       
    $content .= '</div>';

    // list of vars that don't need to be saved
    $avdsearch_reserved_vars = array(_SEARCH_DISPLAY_BTN,'authid','bid','title','position','language','refresh');
    
    foreach ($vars as $key => $value) {
    	if (in_array($key, $avdsearch_reserved_vars))
        	continue;

       	if (is_array($value)) {
          	foreach ($value as $val) {
            	$content .= "<input type=\"hidden\" name=\"$key\" value=\"$val\" />\n";
        	}
        } else {
        	$content .= "<input type=\"hidden\" name=\"$key\" value=\"$value\" />\n";
		}
    }

    
    $content .= "</div></form>";
    if (empty($row['title'])) {
        $row['title'] = _SEARCH;
    }
    $row['content'] = $content;
    return themesideblock($row);
}

function blocks_search_edit($row)
{
	// Break out options from our content field
	$vars = getVarsFrom_search_Content($row);
	    
	$search_modules = '';
	$d = opendir('includes/search/');
	while($f = readdir($d)) {
		if(substr($f, -3, 3) == 'php') {
		include 'includes/search/' . $f;
			}
	}
	closedir($d);

	// set some defaults
	if (!isset($vars[_SEARCH_DISPLAY_BTN])) {
		$vars[_SEARCH_DISPLAY_BTN] = 0;
	}
	    
	$output =& new pnHTML();
	//$output->TableStart();
	$output->TableRowStart();
        $output->TableColStart(1,'left','top');
        $output->Text(_SEARCH_SHOW_BTN);
        $output->TableColEnd();

        $output->TableColStart(1,'left','top');
        $output->FormCheckbox(_SEARCH_DISPLAY_BTN, $vars[_SEARCH_DISPLAY_BTN], '1', 'checkbox');
        $output->TableColEnd();
	$output->TableRowEnd();
	    
	$output->TableRowStart();
        $output->TableColStart(1,'left','top');
        $output->Text(_SEARCH_OPTIONS);
	$output->TableColEnd();
	$output->TableColStart(1,'left','top');
  	$output->setInputMode(_PNH_VERBATIMINPUT);
	    
        foreach($search_modules as $mods) {
              $output->Text($mods['func_opt']($vars));
        }

       	$output->TableColEnd();
	$output->TableRowEnd();
//	$output->TableEnd();
	    
	return $output->getOutput();
	    
}


function blocks_search_update($row)
{
	// list of vars that don't need to be saved
        $search_reserved_vars = array('authid','bid','title','position','language','refresh');
	    
	$vars = array();
	    
	foreach( $_POST as $key => $value )
	{
		if( in_array($key, $search_reserved_vars) ) {
	        	continue;
		}
		$vars[$key] = $value;
	}
	    
	$row['content'] = search_formatBlockParams($vars);
	return($row);
      
}


function getVarsFrom_search_Content($row){
	$links = explode("\n", $row['content']);
	$vars = array();
	foreach ($links as $link) {
   		$link = trim($link);
   		if ($link) 
   		{
     			$var = explode("=", $link);
     		   	$multivar = split("&",$var[1]);
     		   
     		   	if( sizeof($multivar) > 1 ) {
     	         		$vars = array_merge($vars, array($var[0] => $multivar) );
     	         	} else {
     	         		$vars = array_merge($vars, array($var[0] => $var[1]));
     	         	}
	   	}
	}
	    
    	return($vars);
}


function search_formatBlockParams($args)
{
	$result = "";
		
	foreach( $args as $name=>$val )
	{
		if( is_array($val) )
		{
			$val = implode($val,"&");
		}

  		$result .= "$name=$val\n";
	}
		
	return $result;
}

?>