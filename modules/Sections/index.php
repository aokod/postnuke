<?php 
// $Id: index.php 17448 2006-01-03 09:53:29Z markwest $ 
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
// Filename: modules/Sections/index.php
// Original Author: Francisco Burzi
// Purpose: displays the special sections on the site
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_MODULE')) {
    die ("You can't access this file directly...");
}

// this is only here for the get language call
$ModName = basename(dirname( __FILE__ ));

modules_get_language();

function listsections()
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    include ('header.php');

    if (!pnSecAuthAction(0, 'Sections::Section', '::', ACCESS_OVERVIEW)) {
        echo _SECTIONSNOAUTH;
        include 'footer.php';
        return;
    }

    $column = &$pntable['sections_column'];
    $result =& $dbconn->Execute("SELECT $column[secid], $column[secname], $column[image]
                              FROM $pntable[sections] ORDER BY $column[secname]");

    $sitename = pnConfigGetVar('sitename');

    OpenTable();
    echo '<h1>'._SECWELCOME." ".pnVarPrepForDisplay($sitename).'.</h1>'
        ._YOUCANFIND;

	if (!$result->EOF) {
		echo '<table border="0" width="100%"><tr>';
		$count = 0;
		while(list($secid, $secname, $image) = $result->fields) {
	
			$result->MoveNext();
			if (pnSecAuthAction(0, 'Sections::Section', "$secname::$secid", ACCESS_READ)) {
				if ($count == 2) {
					echo '</tr><tr>';
					$count = 0;
				}
				echo '<td align="center"><a href="index.php?name=Sections&amp;req=listarticles&amp;secid='.pnVarPrepForDisplay($secid).'">';
				if (($image == 'transparent.gif') or ($image == '') or ($image == 'none'))  {
					echo $secname;
				} else {
					echo '<img src="images/'.strtolower($GLOBALS['ModName']).'/'.pnVarPrepForDisplay($image).'" alt="'.pnVarPrepForDisplay($secname).'" />';
				}
				echo '</a></td>';
				$count++;
			}
		}
		$result->Close();
	    echo '</tr></table>';
	}
    CloseTable();
    include ('footer.php');
}

function listarticles()
{
    $secid = pnVarCleanFromInput('secid');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $currentlang = pnUserGetLang();

    if (pnConfigGetVar('multilingual') == 1) {
        $column = &$pntable['seccont_column'];
        $querylang = "AND ($column[slanguage]='$currentlang' OR $column[slanguage]='')"; /* the OR is needed to display stories who are posted to ALL languages */
    } else {
	    $querylang = '';
    }
    include ('header.php');
    OpenTable();

	//added by markwest check we have a valid section ID to prevent error closing db resource	
	if ((!isset($secid)) || (empty($secid))) {
        echo _SECTIONSARTICLENOAUTH;
        CloseTable();
        include 'footer.php';
        return;
    }	

    $column = &$pntable['sections_column'];
    $result =& $dbconn->Execute("SELECT $column[secname]
                              FROM $pntable[sections]
                              WHERE $column[secid]='".pnVarPrepForStore($secid)."'");
    if ($dbconn->ErrorNo() != 0) {
        echo _SECTIONSARTICLENOAUTH;
        CloseTable();
        include 'footer.php';
        return;
    }
    list($secname) = $result->fields;
    $result->Close();

    if (!pnSecAuthAction(0, 'Sections::Section', "$secname::$secid", ACCESS_READ)) {
        echo _SECTIONSARTICLENOAUTH;
        CloseTable();
        include 'footer.php';
        return;
    }

    $column = &$pntable['seccont_column'];
    $result =& $dbconn->Execute("SELECT $column[artid], $column[secid], $column[title],
                                $column[content], $column[counter]
                              FROM $pntable[seccont]
                              WHERE $column[secid]='".pnVarPrepForStore($secid)."' $querylang");
    $column = &$pntable['sections_column'];
    $result2 =& $dbconn->Execute("SELECT $column[image]
                               FROM $pntable[sections]
                               WHERE $column[secid]='".pnVarPrepForStore($secid)."'");
    list($image) = $result2->fields;

	// set a page title (the old fashioned way...)
	$GLOBALS['info']['title'] = $secname;

    if (($image == '') or ($image == 'none')) {
	    $image = 'transparent.gif';
    }

    echo '<div style="text-align:center"><img src="images/'.strtolower($GLOBALS['ModName']).'/'.pnVarPrepForDisplay($image).'" alt="" /></div>'
        .'<h1>'._THISISSEC.' '.pnVarPrepForDisplay($secname).'</h1>'._FOLLOWINGART
        .'<ul>';
    while(list($artid, $secid, $title, $content, $counter) = $result->fields) {

        $result->MoveNext();
        if (pnSecAuthAction(0, 'Sections::Article', "$title:$secname:$artid", ACCESS_READ)) {
            echo '<li><a href="index.php?name=Sections&amp;req=viewarticle&amp;artid='.pnVarPrepForDisplay($artid).'&amp;page=1">'.pnVarPrepForDisplay($title).'</a> ('.pnVarPrepForDisplay($counter).' '._READS.')'
                .'&nbsp;<a href="index.php?name=Sections&amp;req=viewarticle&amp;artid='.pnVarPrepForDisplay($artid).'&amp;allpages=1&amp;theme=Printer">'
				.'<img src="modules/'.pnVarPrepForOS($GLOBALS['name']).'/images/print.gif" alt="'._PRINTER.'" /></a>'
                .'</li>';
        }
    }
    echo '</ul>'
        .'<div style="text-align:center">'
        .'[ <a href="index.php?name=Sections">'._SECRETURN.'</a> ]</div>';
    CloseTable();
    $result->Close();
    include ('footer.php');
}

function viewarticle()
{
    list($artid,
         $page,
		 $allpages) = pnVarCleanFromInput('artid',
                                          'page',
										  'allpages');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    include('header.php');

    if (!isset($page)) {
        $page = 1;
    }
	if (!isset($allpages) || empty($allpages)) {
		$allpages = false;
	}
	//added by markwest check we have a valid article ID to prevent error closing db resource 
	if ((!isset($artid)) || (empty($artid))) {
	    OpenTable();
        echo _SECTIONSARTICLENOAUTH;
        CloseTable();
        include 'footer.php';
        return;
	}

    $column = &$pntable['seccont_column'];
    $result =& $dbconn->Execute("SELECT $column[artid], $column[secid], $column[title],
                             $column[content], $column[counter]
                           FROM $pntable[seccont]
                           WHERE $column[artid]='".pnVarPrepForStore($artid)."'");
    if ($dbconn->ErrorNo() != 0) {
	    OpenTable();
        echo _SECTIONSARTICLENOAUTH;
        CloseTable();
        include 'footer.php';
        return;
    }
    list($artid, $secid, $title, $content, $counter) = $result->fields;

    $column = &$pntable['sections_column'];
    $result2 =& $dbconn->Execute("SELECT $column[secid], $column[secname]
                               FROM $pntable[sections] WHERE $column[secid]='".pnVarPrepForStore($secid)."'");
    list($secid, $secname) = $result2->fields;

    if (!pnSecAuthAction(0, 'Sections::Article', "$title:$secname:$artid", ACCESS_READ) ||
    	!pnSecAuthAction(0, 'Sections::Section', "$secname::$secid", ACCESS_READ) ) {
	    OpenTable();
        echo _SECTIONSARTICLENOAUTH;
        CloseTable();
        include 'footer.php';
        return;
    }

	// set the page title (the old fashioned way....)
	$GLOBALS['info']['title'] = $title;

    if (($page == 1) || ($page == '')) {
        $column = &$pntable['seccont_column'];
        $result =& $dbconn->Execute("UPDATE $pntable[seccont]
                                  SET $column[counter]=$column[counter]+1
                                  WHERE $column[artid]='".pnVarPrepForStore($artid)."'");
    }

    $words = sizeof(explode(' ', $content));
    OpenTable();
	if (!$allpages) {
	    $contentpages = explode( "<!--pagebreak-->", $content );
	} else {
		$contentpages[] = str_replace('<!--pagebreak-->', '<br />', $content);
	}
    $pageno = count($contentpages);
    if ( $page=='' || $page < 1 )
        $page = 1;
    if ( $page > $pageno )
        $page = $pageno;
    $arrayelement = (int)$page;
    $arrayelement --;
    echo '<div style="text-align:center">';
    echo '<h1>'.pnVarPrepForDisplay($title).'</h1>';
    if ($pageno > 1) {
        echo _PAGE.': '.pnVarPrepForDisplay($page).'/'.pnVarPrepForDisplay($pageno).'<br />';
    }
    echo '('.pnVarPrepForDisplay($words).' '._TOTALWORDS.')<br />'
        .'('.pnVarPrepForDisplay($counter).' '._READS.')&nbsp;&nbsp;'
        .'<a href="index.php?name=Sections&amp;req=viewarticle&amp;artid='.pnVarPrepForDisplay($artid).'&amp;allpages=1&amp;theme=Printer"><img src="modules/'.pnVarPrepForOS($GLOBALS['name']).'/images/print.gif" alt="'._PRINTER.'" /></a>'
        .'<br />';
    echo '</div>';

	 // call transform hooks
	list($contentpages[$arrayelement]) = pnModCallHooks('item', 'transform', '', array($contentpages[$arrayelement]));
    echo pnVarPrepHTMLDisplay(pnVarCensor($contentpages[$arrayelement]));

    if($page >= $pageno) {
        $next_page = '';
    } else {
        $next_pagenumber = $page + 1;
        if ($page != 1) {
            $next_page = '<img src="modules/'.pnVarPrepForOS($GLOBALS['ModName']).'/images/blackpixel.gif" width="10" height="2" alt="" /> &nbsp;&nbsp; ';
        }
        $next_page  .= '<a href="index.php?name=Sections&amp;req=viewarticle&amp;artid='.pnVarPrepForDisplay($artid).'&amp;page='.pnVarPrepForDisplay($next_pagenumber).'">'._NEXT
		            .' ('.pnVarPrepForDisplay($next_pagenumber).'/'.pnVarPrepForDisplay($pageno).')</a> '
					.'<a href="index.php?name=Sections&amp;req=viewarticle&amp;artid='.pnVarPrepForDisplay($artid).'&amp;page='.pnVarPrepForDisplay($next_pagenumber).'">'
					.'<img src="modules/'.pnVarPrepForOS($GLOBALS['name']).'/images/right.gif" alt="'._NEXT.'"></a>';
    }
    if($page <= 1) {
        $previous_page = '';
    } else {
        $previous_pagenumber = $page - 1;
        $previous_page = '<a href="index.php?name=Sections&amp;req=viewarticle&amp;artid='.pnVarPrepForDisplay($artid).'&amp;page='.pnVarPrepForDisplay($previous_pagenumber).'">'
		                .'<img src="modules/'.pnVarPrepForOS($GLOBALS['name']).'/images/left.gif" alt="'._PREVIOUS.'" /></a> '
						.'<a href="index.php?name=Sections&amp;req=viewarticle&artid='.pnVarPrepForDisplay($artid).'&page='.pnVarPrepForDisplay($previous_pagenumber).'">'
						._PREVIOUS.' ('.pnVarprepForDisplay($previous_pagenumber).'/'.pnVarPrepForDisplay($pageno).')</a>';
    }
    echo '<div style="text-align:center;">'
        .$previous_page.' &nbsp;&nbsp; '.$next_page.'<br />'
        .'[ <a href="index.php?name=Sections&amp;req=listarticles&amp;secid='.pnVarPrepForDisplay($secid).'">'._BACKTO.' '.pnVarPrepForDisplay($secname).'</a> | '
        .'<a href="index.php?name=Sections">'._SECINDEX.'</a> ]'
        .'</div>';
    CloseTable();
    $result->Close();
    $result2->Close();
	// added display hook - bug #174 - ferenc veres
    echo pnModCallHooks('item', 'display', $artid, "index.php?name=Sections&req=viewarticle&artid=$artid&page=$page");
    include ('footer.php');
}

// clean the variables from input
list ($req, $secid, $artid, $page) = pnVarCleanFromInput('req', 'secid', 'artid', 'page');

if(empty($req)) {
	$req = '';
}

if ((isset($secid) && !is_numeric($secid)) ||
	(isset($artid) && !is_numeric($artid)) ||
	(isset($page) && !is_numeric($page))) {
	include 'header.php';
	echo _MODARGSERROR;
	include 'footer.php';
	return false;
}

switch($req) {

    case 'viewarticle':
        viewarticle();
        break;
    case 'listarticles':
        listarticles();
        break;
    default:
        listsections();
        break;
}

?>