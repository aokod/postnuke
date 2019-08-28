<?php

/**
 * pnRender plugin
 *
 * This file is a plugin for pnRender, the PostNuke implementation of Smarty
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   pnRender
 * @version      $Id: function.pagerabc.php 16655 2005-08-21 10:08:06Z drak $
 * @author      Peter Dudas <duda at bigfish dot hu>
 */

/**
* Smarty plugin
* -------------------------------------------------------------
* Type:     function
* Name:     pagerabc
* Purpose:  Displays alphabetical selection links
* Version:  1.1
* Date:     April 30, 2005
* Author:   Peter Dudas <duda at bigfish dot hu>
*           Martin Andersen; API links for ShortURL compliance
* -------------------------------------------------------------
*  Changes:   2002/09/25                 - created
*             2005/04/30   msandersen    - Added Span with Pager class, uses API for links when link to API module, various tweaks
*             2005/08/20   msandersen    - Changed forwardvars behaviour to be consistent with pager plugin:
*                                          If forwardvars is not set, ALL the URL vars are forwarded.
*                                          Fixed bug where if forwardvars weren't specifically set with "module,func" these core vars would not be used at all in the links
*                                          Added support for use on the Startpage, where the vars are taken from the config starttype, startfunc, and startargs vars.
*                                          Fixed the example below.
*
*  Examples:
*    code:
*    <!--[pagerabc posvar="abc" class_num="dl" class_numon="header" separator=" &nbsp;-&nbsp; " names="A,B;C,D;E,F;G,H;I,J;K,L;M,N,O;P,Q,R;S,T;U,V,W,X,Y,Z"]-->
*
*    result
* <span class="pager">
* <a class="header" href="index.php?module=Example&amp;abc=A,B">&nbspA,B
* </a> &nbsp;-&nbsp; <a class="dl" href="index.php?module=Example&amp;abc=C,D">&nbspC,D
* </a> &nbsp;-&nbsp; <a class="dl" href="index.php?module=Example&amp;abc=E,F">&nbspE,F
* </a> &nbsp;-&nbsp; <a class="dl" href="index.php?module=Example&amp;abc=G,H">&nbspG,H
* </a> &nbsp;-&nbsp; <a class="dl" href="index.php?module=Example&amp;abc=I,J">&nbspI,J
* </a> &nbsp;-&nbsp; <a class="dl" href="index.php?module=Example&amp;abc=K,L">&nbspK,L
* </a> &nbsp;-&nbsp; <a class="dl" href="index.php?module=Example&amp;abc=M,N,O">&nbspM,N,O
* </a> &nbsp;-&nbsp; <a class="dl" href="index.php?module=Example&amp;abc=P,Q,R">&nbspP,Q,R
* </a> &nbsp;-&nbsp; <a class="dl" href="index.php?module=Example&amp;abc=S,T">&nbspS,T
* </a> &nbsp;-&nbsp; <a class="dl" href="index.php?module=Example&amp;abc=U,V,W,X,Y,Z">&nbspU,V,W,X,Y,Z
* </a></span>
*
*
* Parameters
*     @param    string     $posvar           - name of the variable that contains the position data, eg "letter"
*     @param    cvs        $forwardvars      - comma- semicolon- or space-delimited list of POST and GET variables to forward in the pager links. If unset, all vars are forwarded.
*     @param    cvs        $additionalvars   - comma- semicolon- or space-delimited list of additional variable and value pairs to forward in the links. eg "foo=2,bar=4"
*     @param    string     $class_num        - class for the pager links (<a> tags)
*     @param    string     $class_numon      - class for the active page
*     @param    string     $separator        - string to put between the letters, eg "|" makes | A | B | C | D |
*     @param    string     $printempty       - print empty sel ('-')
*     @param    string     $lang             - language
*     @param    array      $names            - values to select from (array or csv)
*     @param    string     $skin             - use predefined values (hu - hungarian ABC)
*/
function smarty_function_pagerabc($params, &$smarty)
{
    foreach($params as $tmp=>$value)    {
        $tmp = strtolower($tmp);
        $$tmp = $value;
    }
    if (empty($posvar))    {
        die('unset variable "posvar" in pnRender plugin "pagerabc"');
    }
    $out = '<span class="pager">'."\n";

    if (!empty($names))    {
        if (!is_array($names))    {
            $names = explode(';', $names);
        }
        if (!empty($values))    {
            if (!is_array($values))    {
                $values = explode(';', $values);
            }
        } else    {
            $values = $names;
        }
    } else    {
        // predefined abc
        if (strtolower($skin) == 'hu') { // Hungarian
            $names  = $values = array('A','Á','B','C','D','E','É','F','G','H','I','Í','J','K','L','M','N','O','Ó','Ö','O','P','Q','R','S','T','U','Ú','Ü','U','V','W','X','Y','Z');
          //$names  = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U'    ,'V','W','X','Y','Z');
          //$values = array('A,Á','B','C','D','E,É','F','G','H','I,Í','J','K','L','M','N','O,Ó,Ö,O','P','Q','R','S','T','U,Ú,Ü,U','V','W','X','Y','Z');
        } else    {
            $names  = $values = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        }
    }

  //  $url = $_SERVER['PHP_SELF'].'?';
    $vars = array();
    $Request = array_merge($_POST, $_GET);

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

    // If $forwardvars set, add only listed vars to query string, else add all POST and GET vars
    if (isset($forwardvars)) {
    	if (!is_array($forwardvars))    {
    		$forwardvars = preg_split('/[,;\s]/', $forwardvars, -1, PREG_SPLIT_NO_EMPTY);
    	}
    	foreach ((array)$forwardvars as $key => $var)    {
            if (!empty($var) AND (!empty($Request[$var]))) {
		$vars[$var] = $Request[$var];
	//	if ($key !== 0) {
	//            $url .= '&amp;';
	//	}
		// $url .= $val.'='.$Request[$val];
	    }
    	}
    } else {
    	$vars = $Request;
    }

    if (isset($additionalvars)) {
	if (!is_array($additionalvars))    {
		$additionalvars = preg_split('/[,;\s]/', $additionalvars, -1, PREG_SPLIT_NO_EMPTY);
	}
	foreach ((array)$additionalvars as $var)    {
		$additionalvar = preg_split('/=/', $var);
		if (!empty($var) && !empty($additionalvar[1])) {
			$vars[$additionalvar[0]] = $additionalvar[1];
		//	$url .= '&amp;'.$additionalvar[0].'='.$additionalvar[1];
		}
	}
    }

    if (!empty($Request['module']) or !empty($vars['module'])) {
    	$module = (!empty($vars['module']) ? $vars['module'] : $Request['module']);
    	$type = (!empty($vars['type']) ? $vars['type']
    		: !empty($Request['type']) ? $Request['type']
    		: 'user');
        $func = (!empty($vars['func']) ? $vars['func']
        	: !empty($Request['func']) ? $Request['func']
        	: 'main');
    } else { // Must be Start module
    	$module = pnModGetName();
    	$type = pnConfigGetVar('starttype');
    	$func = pnConfigGetVar('startfunc');
    	$type = (!empty($vars['type']) ? $vars['type']
    		: !empty($type) ? $type
    		: 'user');
        $func = (!empty($vars['func']) ? $vars['func']
        	: !empty($func) ? $func
        	: 'main');
    }
    unset($vars['module']);
    unset($vars['func']);
    unset($vars['type']);
    unset($vars[$posvar]);

    // Get module information
    $modinfo = pnModGetInfo(pnModGetIDFromName($module));
    // If link to API module, not an Admin mod, and ShortURLs set, use ModURL API instead
    $modurl = (($modinfo['type'] == 2 || $modinfo['type'] == 3) && ($type == 'user') && pnConfigGetVar('ShortURLs'));
    if (!$modurl) { // Non-API URLs
        // Don't show default type and func for shorter URL
        $query = 'module='.$module.($func!='main' ? '&amp;func='.$func : '').($type!='user' ? '&amp;type='.$type : '');
        foreach($vars as $key=>$value)    {
            if (is_array($value))    {
                foreach($value as $var) {
                    $query .= '&amp;'.$key.'[]='.urlencode($var);
                }
            } elseif(!empty($value)) {
                $query .= '&amp;'.$key.'='.urlencode($value);
            }
        }
        $url = $_SERVER['PHP_SELF'].'?'.$query;
        // $link = '&amp;';
    }

    $tmp = '';
    if (isset($printempty) && $printempty == true)    {
        if (!empty($class_num))    {
            $tmp = ' class="'.$class_num.'"';
        }
        $vars[$posvar] = '';
        $urltemp = ($modurl ? pnModURL($module, $type, $func, $vars) : $url.'&amp;'.$posvar.'=');
        $out .= '<a'.$tmp.' href="'.$urltemp.'">&nbsp;-'."\n</a>".$separator;
    }


    $tmp = '';
    foreach($names as $i=>$name) {
        if (!empty($class_numon))    {
            if ($Request[$posvar] == $values[$i])  {
                $tmp = ' class="'.$class_numon.'"';
            } elseif (!empty($class_num))    {
                $tmp = ' class="'.$class_num.'"';
            } else {
		$tmp = '';
            }
        }
        $vars[$posvar] = $values[$i];
        $urltemp = ($modurl ? pnModURL($module, $type, $func, $vars) : $url.'&amp;'.$posvar.'='.$values[$i]);
        if ($i > 0)    {
            $out .= $separator;
        }
        $out .= '<a'.$tmp.' href="'.$urltemp.'">&nbsp;'.$name."\n</a>";
    }
    $out .= "</span>\n";
    print $out;
}

?>
