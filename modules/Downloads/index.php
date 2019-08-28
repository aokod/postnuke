<?php 
// $Id: index.php 15830 2005-02-22 13:30:40Z jorg $
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
// Purpose: Download tracker/organizer
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_MODULE'))
{
    die ("You can't access this file directly...");
}

$ModName = pnModGetName();

// cocomp 2002/07/13 change to false if you don't want the rating stars to
// appear
$download_show_star = true;

$modurl="index.php?name=$ModName"; //Shorten url text

modules_get_language();

include_once ("modules/$ModName/dl-util.php");
include_once ("modules/$ModName/dl-categories.php");
include_once ("modules/$ModName/dl-navigation.php");

/**
 * Switch on $req
 * load the appropriate module, and call the appropriate function.
 */
$req = pnVarCleanFromInput('req');

if(empty($req)) {
	$req = '';
}

switch($req) {

    case "menu":
      menu($maindownload);
    break;

    // Menu downloads
    case "AddDownload":
      include_once ("modules/$ModName/dl-adddownload.php");
      AddDownload();
    break;

    case "NewDownloads":
	  $newdownloadshowdays = pnVarCleanFromInput('newdownloadshowdays');
      include_once ("modules/$ModName/dl-newdownloads.php");

      // E_ALL complains if we pass an empty argument, lets settle on a week for a starter
      // (AV) think this $var should initially come from the config system
      if(!isset($newdownloadshowdays)) {
		$newdownloadshowdays = 7;
      }
      NewDownloads($newdownloadshowdays);
    break;

    case "NewDownloadsDate":
	  $selectdate = pnVarCleanFromInput('selectdate');
      include_once ("modules/$ModName/dl-newdownloads.php");
      NewDownloadsDate($selectdate);
    break;
    
    case "CoolSize":
      $size = pnVarCleanFromInput('size');
      if(!isset($size) || !is_numeric($size)) {
  	    $min = 0;
      }
	  CoolSize($size);
    break;
        
    case "TopRated":
	  list ($ratenum, $ratetype) = pnVarCleanFromInput('ratenum', 'ratetype');
      include_once ("modules/$ModName/dl-toprated.php");

      if(!isset($ratenum)) {
	    $ratenum = '';
      }

      if(!isset($ratetype)) {
	    $ratetype = '';
      }
      
      TopRated($ratenum, $ratetype);
    break;

    case "MostPopular":
	  list ($ratenum, $ratetype) = pnVarCleanFromInput('ratenum', 'ratetype');
      include_once ("modules/$ModName/dl-mostpopular.php");

      // E_ALL warnings here again...
      // initial $vars shouldn't be empty, but come from the module or system config
      if(!isset($ratenum)) {
	  $ratenum = '';
      }

      if(!isset($ratetype)) {
	  $ratetype = '';
      }
      MostPopular($ratenum, $ratetype);
    break;

    // currently not implemented. i just left this in. anyone plug it in?
    case "Randomdownload":
      include_once ("modules/$ModName/dl-randomdownload.php");
      Randomdownload();
    break;

    case "search":
	 list ($query, $min, $orderby, $show) = pnVarCleanFromInput('query', 'min', 'orderby', 'show');
     include_once ("modules/$ModName/dl-search.php");
	 if (!isset($min) || !is_numeric($min)) $min=0;
	 if (!isset($orderby)) $orderby="titleA";
	 if (!isset($show)) $show="";
	 $query = pnVarCleanFromInput('query');
     search($query, $min, $orderby, $show);
    break;

    //End of navigation Menu downloads
    //Display a download - called from index
    case "viewdownload":
	  list ($min, $orderby, $show, $cid) = pnVarCleanFromInput('min', 'orderby', 'show', 'cid');
      include_once ("modules/$ModName/dl-viewdownload.php");

      if(!isset($min) || !is_numeric($min)) {
	  $min = 0;
      }

	  if(!isset($orderby)) {
	  $orderby = 0;
      }

      if(!isset($show)) {
	  $show = '';
      }
      viewdownload($cid, $min, $orderby, $show);
    break;

    case "viewsdownload":
	  list ($min, $orderby, $show, $sid) = pnVarCleanFromInput('min', 'orderby', 'show', 'sid');
      include_once ("modules/$ModName/dl-viewdownload.php");

      if(!isset($min) || !is_numeric($min)) {
	  $min = 0;
      }

      if(!isset($show)) {
	  $show = '';
      }
      viewsdownload($sid, $min, $orderby, $show);
    break;

    case "brokendownload":
      $lid = pnVarCleanFromInput('lid');
      include_once ("modules/$ModName/dl-downloaddetails.php");
      brokendownload($lid);
    break;

    case "modifydownloadrequest":
      $lid = pnVarCleanFromInput('lid');
      include_once ("modules/$ModName/dl-downloaddetails.php");
      modifydownloadrequest($lid);
    break;

    case "modifydownloadrequestS":
	  list ($lid, $cat, $title, $url, $description, $modifysubmitter, $aname, $email, $filesize, $version, $homepage) = 
	  pnVarCleanFromInput ('lid', 'cat', 'title', 'url', 'description', 'modifysubmitter', 'aname', 'email', 'filesize', 'version', 'homepage');
      include_once ("modules/$ModName/dl-downloaddetails.php");
      modifydownloadrequestS($lid, $cat, $title, $url, $description, $modifysubmitter, $aname, $email, $filesize, $version, $homepage);
    break;

    case "brokendownloadS":
	  list ($lid, $modifiysubmiter) = pnVarCleanFromInput('lid', 'modifysubmitter');
      include_once ("modules/$ModName/dl-downloaddetails.php");
      brokendownloadS($lid, $modifysubmitter);
    break;

    case "getit":
	  $lid = pnVarCleanFromInput('lid');
      visit($lid);
    break;

    case "Add":
      include_once ("modules/$ModName/dl-adddownload.php");
      Add();
    break;

    case "rateinfo":
      $lid = pnVarCleanFromInput('lid');
      include_once ("modules/$ModName/dl-rating.php");
      rateinfo($lid);
    break;

    case "ratedownload":
      $lid = pnVarCleanFromInput('lid');
      include_once ("modules/$ModName/dl-rating.php");
      ratedownload($lid);
    break;

    case "addrating":
      list ($ratinglid, $ratinguser, $rating, $ratinghost_name, $ratingcomments) = 
	  pnVarCleanFromInput('ratinglid', 'ratinguser', 'rating', 'ratinghost_name', 'ratingcomments');
      include_once ("modules/$ModName/dl-rating.php");
      addrating($ratinglid, $ratinguser, $rating, $ratinghost_name, $ratingcomments);
    break;

    case "viewdownloadcomments":
      $lid = pnVarCleanFromInput('lid');
      include_once ("modules/$ModName/dl-downloaddetails.php");
      viewdownloadcomments($lid);
    break;

    case "outsidedownloadsetup":
	  $lid = pnVarCleanFromInput('lid');
      include_once ("modules/$ModName/dl-downloaddetails.php");
      outsidedownloadsetup($lid);
    break;

    case "viewdownloaddetails":
      $lid = pnVarCleanFromInput('lid');
      include_once ("modules/$ModName/dl-downloaddetails.php");
      viewdownloaddetails($lid);
    break;

    case "viewdownloadeditorial":
      $lid = pnVarCleanFromInput('lid');
      include_once ("modules/$ModName/dl-downloadeditorial.php");
      viewdownloadeditorial($lid);
    break;

    default:
      index();
}
?>