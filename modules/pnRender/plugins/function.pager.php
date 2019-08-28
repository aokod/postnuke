<?php
// $Id: function.pager.php 19371 2006-07-04 12:05:56Z markwest $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2002 by the PostNuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// Based on:
// PHP-NUKE Web Portal System - http://phpnuke.org/
// Thatware - http://thatware.org/
// ----------------------------------------------------------------------
/**
 * pnRender plugin
 *
 * This file is a plugin for pnRender, the PostNuke implementation of Smarty
 *
 ***    @package      Xanthia_Templating_Environment
 ***    @subpackage   pnRender
 ***    @version      $Id: function.pager.php 19371 2006-07-04 12:05:56Z markwest $
 ***    @author       Peter Dudas <duda at bigfish dot hu>
 ***    @link         http://www.postnuke.com  The PostNuke Home Page
 ***    @link         http://www.smarty.hu/plugins/duda/function.pager.phps
 */

/**
* http://www.smarty.hu/plugins/duda/function.pager.phps
* Smarty plugin
* -------------------------------------------------------------
* Type:     function
* Name:     pager
* Purpose:  create a paging output to be able to browse long lists
* Version:  1.0
* Date:     September 29, 2002
* Last Modified:    Sep 29, 2003
* Install:  Drop into the plugin directory
* Author:   Peter Dudas <pager_mail at netrendorseg dot hu>
* -------------------------------------------------------------
*
* Example:
* <--[pager show="page" rowcount=$pager.numitems limit=$pager.itemsperpage posvar=startnum shift=1]-->
*
*    @param    mixed     $rowcount        - total number of items to page in between (if array=>numeer of lines)
*    @param    string    $show            - 'page' - to show page numbers, 'record' - to show record numbers (default records)
*    @param    int       $limit           - number of items on a page (if <0 unlimited)
*    @param    string    $posvar          - name of the variable that contains the position data, eg "offset"
*    @param    string    $forwardvars     - comma- semicolon- or space-separated list of POST and GET variables to forward in the pager links. If unset, all vars are forwarded.
*    @param    string    $txt_first       - on the first page, don't print out all pages, just this text; if set empty, prints all page numbers
*    @param    string    $img_first       - on the first page, don't print out all pages, just this image; if set empty, prints all page numbers
*    @param    boolean   $no_first        - print out all the pages, do not start with txt_firts, equals to txt_first set empty
*    @param    string    $txt_prev        - script to go to the prev page
*    @param    string    $img_prev        - button image to the prev page
*    @param    string    $txt_next        - script to go to the next page
*    @param    string    $img_next        - button image to go to the next page
*    @param    string    $txt_pos         - text position = 'top', 'bottom', 'middle/side'
*    @param    string    $class_num       - class for the pager links (<a> tags)
*    @param    string    $class_numon     - class for the active page
*    @param    string    $class_text      - class for the texts
*    @param    string    $separator       - string to put between the page numbers, eg "&nbsp;-&nbsp;" makes 1&nbsp;-&nbsp;2&nbsp;-&nbsp;3
*    @param    int       $firstpos        - record number of the first position
*    @param    int       $shift           - shift the record numbers with this value (useful if the position variable is printed, 0. page look bad, but 1. page!)
*
*    CHANGES:        2003-03-14:    positionable prev/next string. can use image instead of text
*                    2003-03-21:    Bugfixes
*                    2003-04-14:    Ability to show page number instead of row number, shift parameter
*                    2003-07-07:    prepared for negative limits (unlimited), bugfix
*                    2003-09-29:    fixed notices / warnings which are reported if all warnings and errors are reported (error_reporting(0))
*                    2005-07-03:    Overhauled with pnAPI URLs for ShortURL support;
*                                   Rewrote forwardvar section after Init and before link buildup sections. - msandersen
*                    2005-08-19:    Changed default behavior if forwardvars unset to forward all vars - msandersen
*/
function smarty_function_pager($params, &$smarty)
{
    // START INIT
    $show         = 'record';
    $posvar       = 'pos';
    $separator    = '&ndash;';
    $class_text   = 'nav';
    $class_num    = 'small';
    $class_numon  = 'big';
    $txt_pos      = 'middle';
    $txt_prev     = '&lt;';     // < previous
    $txt_next     = '&gt;';     // > next
    $txt_first    = '';         // archive, more articles
    $shift        = 0;
    $Request      = array_merge($_POST, $_GET);
    $pager        = '';

    foreach($params as $key=>$value)    {
        $tmps[strtolower($key)] = $value;
        $tmp = strtolower($key);
        if (!(${$tmp} = $value))    {
            ${$tmp} = '';
        }
    }
    settype($shift, 'integer');

    // If startmodule, check startargs for posvar
    if (empty($Request['module'])) {
        $funcargs = explode(',', pnConfigGetVar('startargs'));
        $arguments = array();
        foreach ($funcargs as $funcarg) {
            if (!empty($funcarg)) {
                $argument=explode('=', $funcarg);
                if ($argument[0]==$posvar)
                    $Request[$posvar] = $argument[1];
            }
        }
    }

    // START data check
    $minVars = array('limit');
    foreach($minVars as $tmp)  {
        if (empty($params[$tmp]))    {
            $smarty->trigger_error('plugin "pager": missing or empty parameter: "'.$tmp.'"');
        }
    }
    // END data check

    if ($txt_pos == 'middle')    {
        $txt_pos = 'side';
    }
    if (!in_array($txt_pos, array('side', 'top', 'bottom'))) {
        $smarty->trigger_error('plugin "pager": bad value for : "txt_pos"');
    }

    // If there is no need for paging at all
    if (is_array($rowcount))    {
        $rowcount = count($rowcount);
    } elseif (!is_int($rowcount))    {
        ceil($rowcount);
    }
    if ($rowcount <= $limit)    {
        return '';
    }
    if ($limit < 1)    {
        $limit = $rowcount + 1;
    }
    if (!empty($no_first))    {
        unset($txt_first);
    }

    // Determine the real position if the diplayed numbers were shifted (eg: showing 1 instead of 0)
    if ($shift > 0) {
        if (isset($Request[$posvar])) {
            $pos = $Request[$posvar] - $shift;
            if ($pos < 0)  $pos = 0;
        } else {
            $pos = 0;
        }
    } else {
        $pos = 0;   //29-sep-2003 markus weber   added line
        if ( isset($Request[$posvar]) ) { //29-sep-2003 markus weber   added if around existing code
            $pos = $Request[$posvar];
        }
    }
    // END INIT

    // If $forwardvars set, add only listed vars to query string, else add all POST and GET vars
    $vars = array();
    if (isset($forwardvars)) {
        if (!is_array($forwardvars))    {
            $forwardvars = preg_split('/[,;\s]/', $forwardvars, -1, PREG_SPLIT_NO_EMPTY);
        }
        foreach ((array)$forwardvars as $key => $var)    {
            if (!empty($var) and (!empty($Request[$var]))) {
                $vars[$var] = $Request[$var];
            }
        }
    } else {
        $vars = $Request;
    }
    unset($vars['module']);
    unset($vars['func']);
    unset($vars['type']);
    unset($vars['name']);
    unset($vars['file']);
    unset($vars[$posvar]);

    // Start building the links
    if (!empty($Request['module'])) {
        $module = $Request['module'];
        $type = !empty($Request['type']) ? $Request['type'] : '';
        $func = !empty($Request['func']) ? $Request['func'] : '';
    } else if (!empty($Request['name'])) {
        $module = $name = $Request['name'];
        $file = !empty($Request['file']) ? $Request['file'] : '';
    } else { // Must be Start module
        $module = pnConfigGetVar('startpage');
        $type = pnConfigGetVar('starttype');
        $func = pnConfigGetVar('startfunc');
    }

    // get module information
    $modinfo = pnModGetInfo(pnModGetIDFromName($module));
    // if ShortURLs set and User type, use ModURL API instead
    $modurl = ($modinfo['type'] == 2 || $modinfo['type'] == 3);
    if ($modurl) {
        unset($vars['module']); unset($vars['type']); unset($vars['func']);
        $query = pnModURL($module, $type, $func, $vars);
    } else { // Non-API URLs
        $query = 'name='.$module.
        $query .= (isset($file) && !empty($file)) ? '&file='.$file : '';
        foreach($vars as $key=>$value)    {
            if (is_array($value))    {
                foreach($value as $var) {
                    $query .= '&'.$key.'[]='.urlencode($var);
                }
            } elseif(!empty($value)) {
                $query .= '&'.$key.'='.urlencode($value);
            }
        }
        $url = pnGetBaseURL().'index.php?'.$query;
    }

    // if there is no position (or 0), prepare the link for the second page
    if ((empty($pos) OR ($pos < 1)) AND ($rowcount > $limit))    {
        if (!empty($firstpos)) {
            $vars[$posvar] = $firstpos;
            $short['first'] .= ($modurl ? pnModURL($module, $type, $func, $vars)
                    : $url.'&'.$posvar.'='.$firstpos); // $url.$link.$posvar.'='.$firstpos;
        } elseif ($pos == -1) {
            $vars[$posvar] = (1 + $shift);
            $short['first'] .= ($modurl ? pnModURL($module, $type, $func, $vars)
                    : $url.'&'.$posvar.'='.(1 + $shift)); // $url.$link.$posvar.'='.(1 + $shift);
        } else {
            $vars[$posvar] = ($limit+$shift);
            $short['first'] = ($modurl ? pnModURL($module, $type, $func, $vars)
                    : $url.'&'.$posvar.'='.($limit+$shift)); // $url.$link.$posvar.'='.($limit+$shift);
        }
    }

    // START create data to print
    if ($rowcount > $limit)  {
        if ($rowcount < ($limit * 30))    {
            for ($i=1; $i < $rowcount+1; $i+=$limit)    {
                if (($pos+1 >=$i) and ($pos+1 < ($i+$limit)) )        {
                    $short['now'] = $i;
                }
                $vars[$posvar] = ($i - 1 + $shift);
                $pages[$i] = ($modurl ? pnModURL($module, $type, $func, $vars)
                    : $url.'&'.$posvar.'='.$vars[$posvar]); // $url.$link.$posvar.'='.($i - 1 + $shift);
            }
        } else {
            // If there are a lot of records long before the actual position,
            // do big steps ($limit*10)
            for ($i=1; $i < ($pos-16*$limit); $i+=10*$limit)    {
                $vars[$posvar] = ($i - 1 + $shift);
                $pages[$i] = ($modurl ? pnModURL($module, $type, $func, $vars)
                    : $url.'&'.$posvar.'='.$vars[$posvar]); //$pages[$i] = $url.$link.$posvar.'='.($i - 1 + $shift);
            }
            // Around the actual position, do small steps ($limit)
            for ($tmp=1; ($i < $pos+16*$limit) AND ($i < $rowcount+1); $i += $limit)    {
                if (($pos+1 >= $i) and ($pos+1 < ($i+$limit)) )    {
                    $short['now'] = ($i);
                }
                $vars[$posvar] = ($i - 1 + $shift);
                $pages[$i] = ($modurl ? pnModURL($module, $type, $func, $vars)
                    : $url.'&'.$posvar.'='.$vars[$posvar]); // $url.$link.$posvar.'='.($i - 1 + $shift);
            }
            // Over $pos do big steps ($limit*10)
            for ($tmp=1;$i < $rowcount+1; $i += 10*$limit)    {
                $vars[$posvar] = ($i - 1 + $shift);
                $pages[$i] = ($modurl ? pnModURL($module, $type, $func, $vars)
                    : $url.'&'.$posvar.'='.$vars[$posvar]); // $url.$link.$posvar.'='.($i - 1 + $shift);
            }
        }
        // previous - next stepping
        if ($pos >= $limit)    {
            $vars[$posvar] = ($pos - $limit + $shift);
            $short['prev'] = ($modurl ? pnModURL($module, $type, $func, $vars)
                    : $url.'&'.$posvar.'='.$vars[$posvar]); // $url.$link.$posvar.'='.($pos - $limit + $shift);
        }

        if ( ($pos) < ($rowcount-$limit))    {
            $vars[$posvar] = ($pos + $limit + $shift);
            $short['next'] = ($modurl ? pnModURL($module, $type, $func, $vars)
                    : $url.'&'.$posvar.'='.$vars[$posvar]); // $url.$link.$posvar.'='.($pos + $limit + $shift);
        }
    }
    // END preparing the arrays to print


    // START DISPLAY ---------------------------------------------------------------------------
    // all neccesary data are in $pages, and in $short
    if (($pos == 0) AND ((!empty($txt_first)) OR !empty($img_first))){
        $pager .= '<p style="text-align:center;">';
        $pager .= '<a class="'.$class_text.'" href="'.$short['first'].'">';

        if (!empty($img_first))    {
            if (strpos($img_first, '<')===true) { // preg_match('/<img/i', $img_first)
                // image tag
                $pager .= $img_first;
            } else {
                // image url
                $pager .= '<img src="'.$img_first.'" />';
            }
        } else    {
            $pager .= $txt_first;
        }
        $pager .= '</a></p>'."\n";
    } else {
        // -----------------------------------------------------------------------
        // START prepare the prev and next string/image, make it a link...
        if ($pos >= $limit)    {
            $cache['prev'] = '<a class="'.$class_text.'" href="'.htmlspecialchars($short['prev']).'" style="text-decoration: none;">';
            if (!empty($img_prev))    {
                if (strpos($img_prev, '<')===true) { // preg_match('/\</', $img_prev)
                    // image tag
                    $cache['prev'] .= $img_prev;
                } else {
                    // image url
                    $cache['prev'] .= '<img src="'.$img_prev.'" />';
                }
            } else    {
                $cache['prev'] .= $txt_prev;
            }
            $cache['prev'] .= '</a>&nbsp;&nbsp;&nbsp;';
        } else    {
            $cache['prev'] = '&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        // next
        if ($pos < ($rowcount-$limit))    {
            $cache['next'] = '&nbsp;&nbsp;&nbsp;<a class="'.$class_text.'" href="'.htmlspecialchars($short['next']).'" style="text-decoration: none;">';
            if (!empty($img_next))    {
                if (preg_match('/\</', $img_next)) {
                    // image tag
                    $cache['next'] .= $img_next;
                } else {
                    // image url
                    $cache['next'] .= '<img src="'.$img_next.'" />';
                }
            } else    {
                $cache['next'] .= $txt_next;
            }
            $cache['next'] .= '</a>';
        } else {
            $cache['next'] = '&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        // END prepare the prev and next string/image, make it a link...
        // -----------------------------------------------------------------------
        // START PRINTOUT
        if ($txt_pos == 'top')    {
            $pager .= '<p style="text-align:center; text-decoration: none;">'.$cache['prev'].$cache['next'].'</p>'."\n";
        }
        $pager .= '<p style="text-align:center" class="pager">';
        if (($txt_pos == 'side') AND (!empty($cache['prev'])))    {
            $pager .= $cache['prev'];
        }
        foreach($pages as $num=>$url)    {
                if ($num > $limit)    {
                    $pager .= ' '.$separator.' ';
                }
                if ($show == 'record')    {        // show record number for paging
                    $tmp = $num;
                } else {                        // show page number for paging
                    // [jorg] Fix: Pager starts at page 2 if displaying 1 item per page. Credits to na-oma
                    // $tmp = floor($num/$limit) + 1;
                    $tmp = ceil($num/$limit);
               }
               if ($num != $short['now']) { // Don't have a link to the current page
                        $pager .= '<a class="'.$class_num.'" href="'.htmlspecialchars($url).'">'.$tmp.'</a>';
               } else { $pager .= '<span class="'.$class_numon.'">'.$tmp.'</span>';
               }
        }
        if (($txt_pos == 'side') AND (!empty($cache['next'])))    {
            $pager .= $cache['next'];
        }
        $pager .= '</p>'."\n";
        // END NUMBERS
        // START PREVIOUS, NEXT paging
        if ($txt_pos == 'bottom')    {
            $pager .= '<p style="text-align:center; text-decoration: none;">'.$cache['prev'].$cache['next'].'</p>'."\n";
        }
        // END PREVIOUS, NEXT paging
    }
    // END DISPLAY
    return $pager;
}
?>