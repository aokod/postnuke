<?php
// $Id: pnuser.php 17838 2006-02-03 12:21:53Z markwest $
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
// Original Author of file: Jim McDonald
// Purpose of file:  ratings user display functions
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Utility_Modules
 * @subpackage Ratings
 * @license http://www.gnu.org/copyleft/gpl.html
*/
/**
 * The main ratings user function
 * @author Jim McDonald
 * @return HTML String
 */
function ratings_user_main()
{
    // ratings module cannot be directly accessed
    return pnVarPrepHTMLDisplay(_MODULENODIRECTACCESS);
}

/**
 * display rating for a specific item, and request rating
 * @author Jim McDonald
 * @param $args['objectid'] ID of the item this rating is for
 * @param $args['extrainfo'] URL to return to if user chooses to rate
 * @param $args['style'] style to display this rating in (optional)
 * @return string output with rating information
 */
function ratings_user_display($args)
{
    extract($args);

    if (!isset($style)) {
        $style = pnModGetVar('Ratings', 'defaultstyle');
    }

	// work out the return url
	if (is_array($extrainfo)) {
		if (!isset($extrainfo['returnurl'])) {
			return false;
		}
		$returnurl = $extrainfo['returnurl'];
	} else {
		$returnurl = $extrainfo;
	}

	// work out the calling module
	if (is_array($extrainfo) && isset($extrainfo['module'])) {
		$args['modname'] = $extrainfo['module'];
	} else {
	    $args['modname'] = pnModGetName();
	}

	// security check
	// first check if the user is allowed to the any ratings for this module/style/objectid
	if (!pnSecAuthAction(0, 'Ratings::', "$args[modname]:$style:$objectid", ACCESS_READ)) {
		return;
	}
	// if we can we then need to check if the user can add thier own rating
	$permission = false;
	if (pnSecAuthAction(0, 'Ratings::', "$args[modname]:$style:$objectid", ACCESS_COMMENT)) {
		$permission = true;
	}

    // Run API function
    $rating = pnModAPIFunc('Ratings',
                           'user',
                           'get',
                           $args);

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
	$pnRender =& new pnRender('Ratings');

	// assign the rating style
	$pnRender->assign('style', $style);

    if (isset($rating)) {
        // Display current rating
        $pnRender->assign('currentratinglabel', _RATING);
		$pnRender->assign('showrating', 1);
        switch($style) {
            case 'percentage':
                $pnRender->assign('rating', $rating);
                break;
            case 'outoffive':
                $rating = (int)(($rating+10)/20);
                $pnRender->assign('rating', $rating);
                break;
            case 'outoffivestars':
                $rating = (int)($rating/2);
                $intrating = (int)($rating/10);
                $fracrating = $rating - (10*$intrating);
				$pnRender->assign('rating', $intrating);
				$pnRender->assign('fracrating', $fracrating);
                break;
            case 'outoften':
                $rating = (int)(($rating+5)/10);
                $pnRender->assign('rating', $rating);
                break;
            case 'outoftenstars':
                $intrating = (int)($rating/10);
                $fracrating = $rating - (10*$intrating);
				$pnRender->assign('rating', $intrating);
				$pnRender->assign('fracrating', $fracrating);
                break;
        }
    } 

    // Multiple rate check
    $seclevel = pnModGetVar('Ratings', 'seclevel');
	
    if ($seclevel == 'high') {
        // Database information
        $dbconn =& pnDBGetConn(true);
        $pntable =& pnDBGetTables();
        $ratingslogtable = $pntable['ratingslog'];
        $ratingslogcolumn = &$pntable['ratingslog_column'];

        // Check against table to see if user has already voted
        // we need to check against both ip and id
        $logid = pnUserGetVar('uid');
		// get the users ip
		$logip = pnServerGetVar('REMOTE_ADDR');

        $sql = "SELECT $ratingslogcolumn[id]
                FROM $ratingslogtable
                WHERE ( $ratingslogcolumn[id] = '" . pnVarPrepForStore($logid) . "'
                   OR $ratingslogcolumn[id] = '" . pnVarPrepForStore($logip) . "' )
                  AND $ratingslogcolumn[ratingid] = '" . $args['modname'] . $objectid . $style . "'";
        $result =& $dbconn->Execute($sql);
        if (!$result->EOF) {
            $result->Close();
            return $pnRender->fetch('ratings_user_display.htm');
        }
    } elseif ($seclevel == 'medium') {
        // Check against session to see if user has voted recently
        if (pnSessionGetVar("Rated$args[modname]$style$objectid")) {
            return $pnRender->fetch('ratings_user_display.htm');
        }
    } 

    // No check for low
    // This user hasn't rated this yet, ask them
    $pnRender->assign('showratingform', 1);
    $pnRender->assign('returnurl', $returnurl);
    $pnRender->assign('modname', $args['modname']);
    $pnRender->assign('objectid', $objectid);
    $pnRender->assign('ratingtype', $style);
	$pnRender->assign('permission', $permission);

    return $pnRender->fetch('ratings_user_display.htm');
}

/**
 * Process rating form
 *
 * Takes input from the rating form and passes this to the API
 * @author Jim McDonald
 * @param $args['modname'] Source module name for which we're rating an oject
 * @param $args['objectid'] ID of object in source module
 * @param $args['ratingtype'] specific type of rating for this item (optional)
 * @param $args['returnurl'] URL to return to if user chooses to rate
 * @param $args['rating'] rating user selected
 * @return bool true if rating sucess, false otherwise
 */
function ratings_user_rate($args)
{
    // Get parameters
    list($modname,
         $objectid,
         $ratingtype,
         $returnurl,
         $rating) = pnVarCleanFromInput('modname',
                                        'objectid',
                                        'ratingtype',
                                        'returnurl',
                                        'rating');
    
    // Confirm authorisation code
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect($returnurl);
        return true;
    }

    // Pass to API
    $newrating = pnModAPIFunc('Ratings',
                              'user',
                              'rate',
                              array('modname'    => $modname,
                                    'objectid'   => $objectid,
                                    'ratingtype' => $ratingtype,
                                    'rating'     => $rating));

    if ($newrating) {
        // Success
        pnSessionSetVar('statusmsg', _THANKYOUFORRATING);
    }

    pnRedirect($returnurl);

    return true;
}

?>