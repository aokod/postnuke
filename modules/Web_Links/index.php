<?php 
// $Id: index.php 15630 2005-02-04 06:35:42Z jorg $
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
// Filename: modules/Web_Links/index.php
// Original Author of file: Francisco Burzi
// Purpose of file:
// ----------------------------------------------------------------------
// Changelog
//
// 10-07-2001:sluxford     - modified $description to $cdescription - SF BUG #468686
// 07-06-2001:niceguyeddie - Added category sub-graphics.  Credits to
//                           Michael Cortez <mcortez@fullcoll.edu>
// 07-06-2001:niceguyeddie - Added new header
// 07-07-2001:Wandrer      - changed mysql_num_rows to count(*) clause
// 07-07-2001:niceguyeddie - sfb 439379 carriage return in weblinks
// 07-07-2001:niceguyeddie - sfb 439399 missing CSS in viewlink function
// 07-09-2001:Greg         - changed from a core feature to module
// 08-22-2001:JM           - sfb 451070 452566 added global
//                           - viewlinkcomments(),viewlinkeditorial() date fix
//                         - web_links_show_star
//                         - A\D to A/D
// 08-24-2001:JM           - sfb 452097 Add() $name to $nnmae
// 09-25-2001:Karateka     - Added unlimited subcategories
// 11-14-2001:Eugenio      - Fixed SF 481675  Rating error
// 11-29-2001:ahumphr      - Major restructuring to modularise the monster
//                         - and reduce memory requirements.
// 10-15-2002:skooter      - Cross Site Scripting security fixes and also using 
//                           pnAPI for displaying data.
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_MODULE'))
{
    die ("You can't access this file directly...");
}

//removed show star flag,  need to rethink the config vars for this module.
// $web_links_show_star = true;
// $web_links_show_star = false;
// $web_links_show_url = true;
// $web_links_show_url = false;

$ModName = basename(dirname( __FILE__ ));

modules_get_language();

include_once ("modules/$ModName/wl-util.php");
include_once ("modules/$ModName/wl-categories.php");
include_once ("modules/$ModName/wl-navigation.php");

$modurl="index.php?name=$ModName"; //Shorten url text

// get all of our variables cleanly
list($perpage,
   $req,
   $min,
   $max,
   $orderby,
   $show,
   $mainlink,
   $ratenum,
   $ratetype,
   $newlinkshowdays,
   $lid,
   $cat,
   $title,
   $url,
   $description,
   $modifysubmitter,
   $user,
   $ratinglid,
   $ratinguser,
   $rating,
   $ratinghost_name,
   $ratingcomments,
   $ttitle,
   $selectdate,
   $cid) = pnVarCleanFromInput('perpage',
                              'req',
                              'min',
                              'max',
                              'orderby',
                              'show',
                              'mainlink',
                              'ratenum',
                              'ratetype',
                              'newlinkshowdays',
                              'lid',
                              'cat',
                              'title',
                              'url',
                              'description',
                              'modifysubmitter',
                              'user',
                              'ratinglid',
                              'ratinguser',
                              'rating',
                              'ratinghost_name',
                              'ratingcomments',
                              'ttitle',
                              'selectdate',
                              'cid');
/**
 * Switch on $req
 * load the appropriate module, and call the appropriate function.
 */
if(empty($req)) {
	$req = '';
}

switch($req) {

    case "menu":
      menu($mainlink);
    break;

    // Menu Links
    case "AddLink":
      include_once ("modules/$ModName/wl-addlink.php");
      AddLink();
    break;

    case "NewLinks":
      include_once("modules/$ModName/wl-newlinks.php");
		if (!isset($newlinkshowdays)) {
		    $newlinkshowdays = 7;
		}
      NewLinks($newlinkshowdays);
    break;

    case "MostPopular":
      include_once ("modules/$ModName/wl-mostpopular.php");
		if (!isset($ratenum)) $ratenum="";
		if (!isset($ratetype)) $ratetype="percent";  
      MostPopular($ratenum, $ratetype);
    break;

    case "TopRated":
		if (!isset($ratenum)) $ratenum="";
		if (!isset($ratetype)) $ratetype="percent"; 
      include_once ("modules/$ModName/wl-toprated.php");
      TopRated($ratenum, $ratetype);
      break;

    case "RandomLink":
       include_once ("modules/$ModName/wl-randomlink.php");
        RandomLink();
    break;

    case "search":
      include_once ("modules/$ModName/wl-search.php");
		if (!isset($min)) $min=0;
		if (!isset($orderby)) $orderby="titleA";
		if (!isset($show)) $show="";
		$query = pnVarCleanFromInput('query');
		  search($query, $min, $orderby, $show);
    break;

    //End of navigation Menu links

    //Display a link - called from index
    case "viewlink":
      include_once ("modules/$ModName/wl-viewlink.php");
	  /*
        if (!isset($perpage)||!is_numeric($perpage)) $perpage=pnConfigGetVar('perpage');
		if (!isset($min)||!is_numeric($min)) $min=0;
		if (!isset($max)||!is_numeric($max)) $max=$min+$perpage;
	
		if(isset($orderby)) {
			$orderby = convertorderbyin($orderby);
		} else {
			$orderby = convertorderbyin("titleA");
		}
		if (!isset($show)){
		   $show = $perpage;
		} else {
		   $perpage = $show;
		}
		*/
	// check the cid
	if (isset($cid) && !empty($cid) && !is_numeric($cid)){
		include 'header.php';
		OpenTable();
		echo _MODARGSERROR;
		CloseTable();
		include 'footer.php';
	}
		
      viewlink($cid, $min, $orderby, $show);
    break;

    case "NewLinksDate":
      include_once ("modules/$ModName/wl-newlinks.php");
      NewLinksDate($selectdate);
    break;

    case "brokenlink":
      include_once ("modules/$ModName/wl-linkdetails.php");
      brokenlink($lid);
    break;

    case "modifylinkrequest":
      include_once ("modules/$ModName/wl-linkdetails.php");
      modifylinkrequest($lid);
    break;

    case "modifylinkrequestS":
      include_once ("modules/$ModName/wl-linkdetails.php");
        modifylinkrequestS($lid, $cat, $title, $url, $description, $modifysubmitter);
    break;

    case "brokenlinkS":
      include_once ("modules/$ModName/wl-linkdetails.php");
        brokenlinkS($lid, $modifysubmitter);
    break;

    case "visit":
      include_once ("modules/$ModName/wl-util.php");
        visit($lid);
    break;

    case "Add":
      include_once ("modules/$ModName/wl-addlink.php");
        Add();
    break;

    case "rateinfo":
      include_once ("modules/$ModName/wl-rating.php");
        rateinfo($lid, $user, $title);
    break;

    case "ratelink":
      include_once ("modules/$ModName/wl-rating.php");
      ratelink($lid);
    break;

    case "addrating":
      include_once ("modules/$ModName/wl-rating.php");
        addrating($ratinglid, $ratinguser, $rating, $ratinghost_name, $ratingcomments, $user);
    break;

    case "viewlinkcomments":
      include_once ("modules/$ModName/wl-linkdetails.php");
      viewlinkcomments($lid);
    break;

    case "outsidelinksetup":
      include_once ("modules/$ModName/wl-linkdetails.php");
      outsidelinksetup($lid);
    break;

    case "viewlinkeditorial":
      include_once ("modules/$ModName/wl-linkeditorial.php");
      viewlinkeditorial($lid);
    break;

    case "viewlinkdetails":
      include_once ("modules/$ModName/wl-linkdetails.php");
      viewlinkdetails($lid);
    break;

    default:
      index();
}
?>