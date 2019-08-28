<?php
// File: $Id: admin.php 16424 2005-07-19 21:28:37Z chestnut $
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
// Original Author of file:
// Modified By: bharvey42 CSS implimented 6/29/03
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_MODULE')) {
  die ('Access Denied');
}

$ModName = basename(dirname(__FILE__));

if (!(pnSecAuthAction(0, 'Referers::', '::', ACCESS_ADMIN))) {
  include 'header.php';
  echo _REFERERSNOAUTH;
  include 'footer.php';
}

modules_get_language();
modules_get_manual();

/*********************************************************/
/* Referer Functions to know who links to us             */
/*********************************************************/

/**
 * referer_menu()
 */
function referer_menu()
{
    GraphicAdmin();
    OpenTable();
    echo '<h2>'._HTTPREFERERS.'</h2>';
    echo "<div style=\"text-align: center;\">";
    echo "[ <a href=\"admin.php?module=Referers\">" . pnVarPrepForDisplay(_HTTPREFERERS) . "</a> ]";
    echo "[ <a href=\"admin.php?module=Referers&amp;op=getConfig\">" . pnVarPrepForDisplay(_REFERERSCONF) . "</a> ]";
    echo '</div>';
    CloseTable();
}

function referers_admin_main()
{
    include ('header.php');
    referer_menu();
    $bgcolor2 = $GLOBALS['bgcolor2'];
/*
    $statusmsg = pnGetStatusMsg();
    if ($statusmsg != '') {
      OpenTable();
      echo '<div class="pn-statusmsg">'.$statusmsg.'</div>';
      CloseTable();
    }

    GraphicAdmin();
    OpenTable();
    echo '<h1>'._HTTPREFERERS.'</h1>';
    CloseTable();
*/
    if (!(pnSecAuthAction(0, 'Referers::', '::', ACCESS_ADMIN))) {
        echo _REFERERSNOAUTH;
        include 'footer.php';
        return;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

  // Added by Leithya - Start
    list($sortby, $page) = pnVarCleanFromInput('sortby','page');
    if (!isset($page) || !is_numeric($page)) {
    $page = 1;
  }
    if ($sortby != 'pn_url') {
    $sortby = 'pn_frequency';
  }
    $column = &$pntable['referer_column'];
  if ($sortby == 'pn_url'){
    $sort = "ORDER BY $column[url] ";
  } else {
    $sort = "ORDER BY $column[frequency] DESC ";
  }
  $pagesize = '25';
  $min = $pagesize * ($page - 1);
  $max = $pagesize;
  // Added by Leithya - End

  // Edited by Leithya - Start
    OpenTable();
    echo '<h2>'._WHOLINKS.'</h2>'
      .'<table border="0" width="100%">'
    .'<tr><th><a href="admin.php?module=Referers&amp;op=main&amp;sortby=pn_frequency">'._FREQUENCY.'</a></th>'
    .'<th><a href="admin.php?module=Referers&amp;op=main&amp;sortby=pn_url">'._URL.'</a></th>'
    .'<th>'._PERCENT.'</th></tr>';

    /**
     * fifers: grab the total count of referers for percentage calculations
     */
    $hresult =& $dbconn->Execute("SELECT SUM($column[frequency]) FROM $pntable[referer]");
    list($totalfreq) = $hresult->fields;

    $hresult5 =& $dbconn->Execute("SELECT * FROM $pntable[referer]");
    list($totalurl) = $hresult5->fields;
  $totalurl = $hresult5->PO_RecordCount();

  $hresult =& $dbconn->Execute("SELECT $column[url], $column[frequency] FROM $pntable[referer] $sort LIMIT ".$min.",".$max." ");
    while(list($url, $freq) = $hresult->fields) {

        $urls = str_replace('&', ' &', $url);
        $urls = str_replace('/', '/ ', $urls);

    // Edited by Leithya - End
    /*
    $hresult =& $dbconn->Execute("SELECT $column[url], $column[frequency] FROM $pntable[referer] ORDER BY $column[frequency] DESC");
    while(list($url, $freq) = $hresult->fields) {
    */
        $hresult->MoveNext();

        echo '<tr>'."\n"
            .'<td style="background-color:$bgcolor2">' . pnVarPrepForDisplay($freq) . '</td>'."\n"
            .'<td style="background-color:$bgcolor2">'.
         (($url == "bookmark")?(''):('<a href="'.pnVarPrepForDisplay($url).'">')).
         pnVarPrepForDisplay($urls).(($url == "bookmark")?(''):('</a>')).'</td>'."\n"
            .'<td style="background-color:$bgcolor2">'.round(($freq / $totalfreq * 100), 2).' %</td>'."\n"
            .'</tr>'."\n";
    }
    echo '</table>'._TOTAL.' ' . pnVarPrepForDisplay($totalfreq) . ' <br />';

  // Added by Leithya - Start
  if ($totalurl > $pagesize) {
    $total_pages = ceil($totalurl/$pagesize)+ 0.99;
    $prev_page = $page - 1;
    $next_page = $page + 1;
    if ( $prev_page > 0 ) {
      echo "<a href='admin.php?module=Referers&amp;op=main&amp;sortby=$sortby&amp;page=$prev_page'><span class=\"pn-sub\"> <-- </span></a>";
    }
    for($n=1; $n < $total_pages; $n++) {
      if ($n == $page) {
        echo " <span class=\"pn-sub\">$n</span></a> ";
      } else {
        echo " <a href='admin.php?module=Referers&amp;op=main&amp;sortby=$sortby&amp;page=$n'><span class=\"pn-sub\">".pnVarPrepHTMLDisplay($n)."</span></a> ";
      }
    }
    if ( $next_page <= $total_pages ) {
      echo "<a href='admin.php?module=Referers&amp;op=main&amp;sortby=$sortby&amp;page=$next_page'><span class=\"pn-sub\"> --> </span></a>";
    }
  }
  // Added by Leithya - End

    echo '<form action="admin.php" method="post"><div>'
    .'<input type="hidden" name="module" value="Referers" />'
    .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
    .'<input type="hidden" name="op" value="delete" />'
    .'<div style="text-align:center"><input type="submit" value="'._DELETEREFERERS.'" /></div></div></form>';
    CloseTable();
/*
// Access Referer Settings
    OpenTable();
    echo '<h2>'._REFERERSCONF.'</h2>';
    echo '<div style="text-align:center"><a href="admin.php?module=Referers&amp;op=getConfig">'._REFERERSCONF.'</a></div>';
    CloseTable();
*/
    include ('footer.php');
}

function referers_admin_getConfig() {

    include ('header.php');

    // prepare vars
    $sel_httpref['0'] = '';
    $sel_httpref['1'] = '';
    $sel_httpref[pnConfigGetVar('httpref')] = ' checked="checked"';

//    GraphicAdmin();
    referer_menu();
    OpenTable();
    print '<h1>'._REFERERSCONF.'</h1>'
        .'<form action="admin.php" method="post"><div>'
        .'<table border="0"><tr><td>'
        ._ACTIVATEHTTPREF.'</td><td>'
        .'<input type="radio" name="xhttpref" value="1" '.$sel_httpref['1'].' />'._YES.' &nbsp;'
        .'<input type="radio" name="xhttpref" value="0" '.$sel_httpref['0'].' />'._NO
        .'</td></tr><tr><td>'
        ._HTTPREFEXCLUDED.'</td><td>'
        .'<textarea name="xhttprefexcluded" cols="40" rows="10">'.pnVarPrepForDisplay(pnConfigGetVar('httprefexcluded')).'</textarea>'
        .'</td></tr></table>'
        .'<input type="hidden" name="module" value="Referers" />'
        .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
        .'<input type="hidden" name="op" value="setConfig" />'
        .'<input type="submit" value="'._SUBMIT.'" />'
        .'</div></form>';
    CloseTable();
    include ('footer.php');
}

function referers_admin_setConfig($var)
{
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    // all variables starting with x are the config vars.
    while (list ($key, $val) = each ($var)) {
        if (substr($key, 0, 1) == 'x') {
            pnConfigSetVar(substr($key, 1), $val);
        }
    }
    pnRedirect('admin.php?module=Referers');
  return;
}

function referers_admin_delete($var)
{
    if (!(pnSecAuthAction(0, 'Referers::', '::', ACCESS_ADMIN))) {
    include 'header.php';
    echo _REFERERSDELNOAUTH;
    include 'footer.php';
    }
    if (!pnSecConfirmAuthKey()) {
        pnConfigSetVar('errormsg', _BADAUTHKEY);
    pnRedirect('admin.php?module=Referers');
    return;
    }
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $dbconn->Execute("DELETE FROM $pntable[referer]");
    pnRedirect('admin.php?module=Referers');
  return;
}

?>