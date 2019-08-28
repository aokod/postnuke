<?php
// File: $Id: addstory_categories.php 16060 2005-04-03 19:21:54Z larsneo $
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
// Purpose of file: extract category functions to make them generic
// ----------------------------------------------------------------------

// Notes by AV
// OK, the idea is to make 'Categories' manager a bit more intuitive to work with:
// 1. Every EDIT and PREVIEW story inherits the 'Category' chosen during story ADD
// 2. Every 'Category' EDIT and DELETE inherits previously chosen one
// 3. Every 'Category' modification menu contains and/or ends up with further logical actions

// Security Changes and removed Globals - Skooter.

if (!defined('LOADED_AS_MODULE')) { die ('Access Denied'); }

function _admin_cat_theme_list($selectname, $defaulttheme)
{
    $r = '<select name="'.pnVarPrepForDisplay($selectname).'">';

    $r .= '<option value=""';
    if ($defaulttheme == '') {
        $r .= ' selected="selected"';
    }
    $r .= '>'._CATOVERRIDENONE.'</option>';

    $themelist = pnThemeGetAllThemes();
    foreach($themelist as $theme) {
        $r .= '<option value="' . pnVarPrepForDisplay($theme) . '"';
        if ($theme == $defaulttheme) {
            $r .= ' selected="selected"';
        }
         $r .= '>' . pnVarPrepForDisplay($theme) . '</option>'."\n";
    }

    $r .= '</select>';

    return ($r);
}

function AddCategory()
{
    $module = pnVarCleanFromInput('module');

    include ('header.php');
    GraphicAdmin();
    OpenTable();
    echo '<h1>'._CATEGORIESADMIN.'</h1>';
    CloseTable();

    if (!pnSecAuthAction(0, 'Stories::Category', '::', ACCESS_ADD)) {
        echo _STORIESADDCATNOAUTH;
        include 'footer.php';
        return;
    }

    OpenTable();
    echo '<h2>'._CATEGORYADD.'</h2>'
        .'<form action="admin.php" method="post"><div>'
        .'<table border="3" cellpadding="4"><tr><td>'
        .'<strong>'._CATNAME.':</strong></td><td>'
        .'<input type="text" name="title" size="22" maxlength="40" />'
        .'<input type="hidden" name="module" value="AddStory" />'
        .'<input type="hidden" name="op" value="SaveCategory" /></td></tr><tr><td>'
        .'<strong>'._CATOVERRIDE.':</strong></td><td> ' . _admin_cat_theme_list('themeoverride', '')
        .'</td></tr><tr><td>&nbsp;</td><td><input type="submit" value="'._SAVE.'" /></td></tr>'
        .'</table></div></form>';
    CloseTable();

    include ('footer.php');
}

function EditCategory($catid)
{
    $module = pnVarCleanFromInput('module');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $catid += 0;
    $column = &$pntable['stories_cat_column'];
    $result =& $dbconn->Execute( "SELECT $column[title], $column[themeoverride]
                               FROM $pntable[stories_cat]
                               WHERE $column[catid] = '".pnVarPrepForStore($catid)."'");

    list($title, $themeoverride) = $result->fields;

    include ('header.php');
    GraphicAdmin();
    OpenTable();
    echo '<h1>'._CATEGORIESADMIN.'</h1>';
    CloseTable();

    if (!pnSecAuthAction(0, 'Stories::Category', "$title::$catid", ACCESS_EDIT)) {
        echo _STORIESEDITCATNOAUTH;
        include 'footer.php';
        return;
    }

    OpenTable();

    echo '<h2>'._EDITCATEGORY.'</h2>';
    if (!$catid) {
        $column = &$pntable['stories_cat_column'];
        $selcat =& $dbconn->Execute("SELECT $column[catid], $column[title]
                                  FROM $pntable[stories_cat]");
        echo '<form action="admin.php" method="post"><div>';
        echo '<table border="3" cellpadding="4"><tr><td>';
        echo '<strong>'._ASELECTCATEGORY.'</strong></td><td>';
        echo '<select name="catid">';
		// echo '<option name="catid" value="0" '.$selcat.'>Articles</option>';

        while(list($catid, $title) = $selcat->fields) {
		    //removed $select cat from option control - seems unneeded and $selcat is an object anyway
            echo '<option value="'.pnVarPrepForDisplay($catid).'">'.pnVarPrepForDisplay($title).'</option>';
            $selcat->MoveNext();
        }
        echo '</select>'
             .'</td></tr><tr><td>&nbsp;</td><td>'
             .'<input type="hidden" name="module" value="AddStory" />'
             .'<input type="hidden" name="op" value="EditCategory" />'
             .'<input type="submit" value="'._EDIT.'" />'
             .'</td></tr></table><br />'
             ._NOARTCATEDIT
             .'</div></form>';
	} else {
        echo '<form action="admin.php" method="post"><div>'
             .'<table border="3" cellpadding="4"><tr><td>'
             .'<strong>'._CATEGORYNAME.':</strong></td><td>'
             .'<input type="text" name="title" size="22" maxlength="40" value="'.pnVarPrepForDisplay($title).'" />'
             .'</td></tr><tr><td><strong>'._CATOVERRIDE.':</strong></td><td>'
             ._admin_cat_theme_list('themeoverride', $themeoverride)
             .'<input type="hidden" name="catid" value="'.pnVarPrepForDisplay($catid).'" />'
             .'<input type="hidden" name="module" value="AddStory" />'
             .'<input type="hidden" name="op" value="SaveEditCategory" />'
             .'</td></tr><tr><td>&nbsp;</td><td>'
             .'<input type="submit" value="'._SAVECHANGES.'" />'
             .'</td></tr></table><br />'
             ._NOARTCATEDIT
             .'</div></form>';
    }

    CloseTable();
    include('footer.php');
}

// we need $catid param to be able to carry on from previous op
function DelCategory($cat, $catid)
{
    $module = pnVarCleanFromInput('module');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    include ('header.php');
    GraphicAdmin();
    OpenTable();
    echo '<h1>'._CATEGORIESADMIN.'</h1>';
    CloseTable();
    echo '<br />';

    $column = &$pntable['stories_cat_column'];
    $result =& $dbconn->Execute("SELECT $column[title]
                              FROM $pntable[stories_cat]
                              WHERE $column[catid]='".pnVarPrepForStore($cat)."'");

	list($title) = $result->fields;

    if (!pnSecAuthAction(0, 'Stories::Category', "$title::$cat", ACCESS_DELETE)) {
        echo _STORIESDELCATNOAUTH;
        include 'footer.php';
        return;
    }

    OpenTable();
    echo '<h2>'._DELETECATEGORY.'</h2>';

    if (empty($catid)) {

		$column = &$pntable['stories_cat_column'];
		$thiscat =& $dbconn->Execute( "SELECT $column[catid]
								   FROM $pntable[stories_cat]
								   WHERE $column[catid] = '".pnVarPrepForStore($cat)."'");
		list($thiscat) = $thiscat->fields;

		$column = &$pntable['stories_cat_column'];
		$selcat =& $dbconn->Execute("SELECT $column[catid], $column[title]
								  FROM $pntable[stories_cat]");

		echo '<form action="admin.php" method="post"><div>'
			.'<table border="3" cellpadding="4"><tr><td>'
			.'<strong>'._SELECTCATDEL.': </strong>'
			.'</td><td>'
			.'<select name="catid">';

		while(list($catid, $title) = $selcat->fields) {

			if ($catid == $thiscat) {
				$sel = 'selected="selected"';
			} else {
				$sel = '';
			}
				echo '<option value="'.pnVarPrepForDisplay($catid).'" '.$sel.'>'.pnVarPrepForDisplay($title).'</option>';

			$selcat->MoveNext();
		}
		echo '</select>'
			.'</td></tr><tr><td>&nbsp;</td><td>'
			.'<input type="hidden" name="module" value="AddStory" />'
			.'<input type="hidden" name="op" value="DelCategory" />'
			.'<input type="submit" value="Delete" />'
			.'</td></tr></table><br />'
			.'</div></form>';
    } else {

        /* Get a quick count of the rows - Wandrer */
        $column = &$pntable['stories_column'];
        $result2 =& $dbconn->Execute("SELECT COUNT(*) FROM $pntable[stories]
                                   WHERE $column[catid]='".pnVarPrepForStore($catid)."'");

        list($numrows) = $result2->fields;

        if ($numrows == 0) {
            $temp= &$pntable['stories_cat_column']['catid']; // TRICKY BIT - may need to be changed
            $result =& $dbconn->Execute("DELETE FROM $pntable[stories_cat]
                                      WHERE ${temp}='".pnVarPrepForStore($catid)."'");
			if ($dbconn->ErrorNo() !== 0) {
				$msg = $dbconn->ErrorMsg();
				include 'header.php';
				echo $msg;
				include 'footer.php';
				return;
			}
            echo '<br />'._CATDELETED.'<br />[ <a href="admin.php">'._GOTOADMIN.'</a> ]';
        } else {
            echo '<br /><strong>'._WARNING.':</strong> '._THECATEGORY.' <strong>'.pnVarPrepForDisplay($title).'</strong> '
                ._HAS.' <strong>'.pnVarPrepForDisplay($numrows).'</strong> '._STORIESINSIDE.'<br />'
            	._DELCATWARNING1.'<br />'
            	._DELCATWARNING2.'<br />'
            	._DELCATWARNING3.'<br />'
            	.'<strong>[ <a href="admin.php?module=AddStory&amp;op=YesDelCategory&amp;catid='.pnVarPrepForDisplay($catid).'">'._YESDEL.'</a> | '
            	.'<a href="admin.php?module=AddStory&amp;op=NoMoveCategory&amp;catid='.pnVarPrepForDisplay($catid).'">'._NOMOVE.'</a> ]</strong>'
            	.'<br />'._GOBACK;
        }
    }
    //echo '</div>';
    CloseTable();
    include('footer.php');
}

function YesDelCategory($catid)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column = &$pntable['stories_cat_column'];
    $result =& $dbconn->Execute("SELECT $column[title]
                              FROM $pntable[stories_cat]
                              WHERE $column[catid]='".pnVarPrepForStore($catid)."'");

    list($title) = $result->fields;

    if (!pnSecAuthAction(0, 'Stories::Category', "$title::$cat", ACCESS_DELETE)) {
        echo _STORIESDELCATNOAUTH;
        include 'footer.php';
        return;
    }
	$temp = &$pntable['stories_cat_column']['catid'];
    $result =& $dbconn->Execute("DELETE FROM $pntable[stories_cat]
                              WHERE ${temp}='".pnVarPrepForStore($catid)."'");
	if ($dbconn->ErrorNo() !== 0) {
		$msg = $dbconn->ErrorMsg();
		include 'header.php';
		echo $msg;
		include 'footer.php';
		return;
	}

    $column = &$pntable['stories_column'];
    $result =& $dbconn->Execute("SELECT $column[sid]
                              FROM $pntable[stories]
                              WHERE $column[catid]='".pnVarPrepForStore($catid)."'");

    while(list($sid) = $result->fields) {
        $results =& $dbconn->Execute("DELETE FROM $pntable[stories]
                                  WHERE $column[catid]='".pnVarPrepForStore($catid)."'");
		if ($dbconn->ErrorNo() !== 0) {
			$msg = $dbconn->ErrorMsg();
			include 'header.php';
			echo $msg;
			include 'footer.php';
			return;
		}
		if (pnModAvailable('Comments')) {
			$temp2 = &$pntable['comments_column']['sid'];
			$resultc =& $dbconn->Execute("DELETE FROM $pntable[comments]
									  WHERE ${temp2}='".pnVarPrepForStore($sid)."'");
			if ($dbconn->ErrorNo() !== 0) {
				$msg = $dbconn->ErrorMsg();
				include 'header.php';
				echo $msg;
				include 'footer.php';
				return;
			}
		}
        $result->MoveNext();
    }
    include ('header.php');
    GraphicAdmin();
    OpenTable();
    echo '<h1>'._CATEGORIESADMIN.'</h1>';
    CloseTable();
    echo '<br />';
	OpenTable();
	echo '<h2>'._DELETECATEGORY.'</h2>';
	echo '<br />'._CATDELETED.'<br />';
	add_edit_del_category($cat);
	echo '<br />[ <a href="admin.php">'._GOTOADMIN.'</a> ]';
	echo '</div>';
	CloseTable();
	include('footer.php');
}

function NoMoveCategory($catid, $newcat)
{
    $module = pnVarCleanFromInput('module');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column = &$pntable['stories_cat_column'];
    $result =& $dbconn->Execute("SELECT $column[title]
                              FROM $pntable[stories_cat]
                              WHERE $column[catid]='".pnVarPrepForStore($catid)."'");

    list($title) = $result->fields;
    include ('header.php');
    GraphicAdmin();
    OpenTable();
    echo '<h1>'._CATEGORIESADMIN.'</h1>';
    CloseTable();

    if (!pnSecAuthAction(0, 'Stories::Category', "$title::$catid", ACCESS_DELETE)) {
        echo _STORIESMOVECATNOAUTH;
        include 'footer.php';
        return;
    }

    OpenTable();
    echo '<h2>'._MOVESTORIES.'</h2>';
    if (!$newcat) {
        echo ""._ALLSTORIES." <strong>".pnVarPrepForDisplay($title).'</strong> '._WILLBEMOVED.'<br />';
        $column = &$pntable['stories_cat_column'];
        $selcat =& $dbconn->Execute("SELECT $column[catid], $column[title]
                                  FROM $pntable[stories_cat]");
        echo '<form action="admin.php" method="post"><div>';
        echo '<strong>'._SELECTNEWCAT.':</strong> ';
        echo '<select name="newcat">';
		// echo '<option name="newcat" value="0">'._ARTICLES.'</option>';

        while(list($newcat, $title) = $selcat->fields) {
            if (pnSecAuthAction(0, 'Stories::Story', ":$title:", ACCESS_ADD))
            	// lets skip the cat which is going to be deleted
            	if($newcat != $catid) echo '<option value="'.pnVarPrepForDisplay($newcat).'">'.pnVarPrepForDisplay($title).'</option>';
            $selcat->MoveNext();
        }
        echo '</select>'
			.'<input type="hidden" name="module" value="AddStory" />';
        echo '<input type="hidden" name="catid" value="'.pnVarPrepForDisplay($catid).'" />';
        echo '<input type="hidden" name="op" value="NoMoveCategory" />';
        echo '<input type="submit" value="'._OK.'" />';
        echo '</div></form>';
    } else {
        $column = &$pntable['stories_column'];
        $resultm =& $dbconn->Execute("SELECT $column[sid]
                                   FROM $pntable[stories]
                                   WHERE $column[catid]='".pnVarPrepForStore($catid)."'");

        while(list($sid) = $resultm->fields) {
            $column = &$pntable['stories_column'];
            $result =& $dbconn->Execute("UPDATE $pntable[stories]
                                      SET $column[catid]='".pnVarPrepForStore($newcat)."'
                                      WHERE $column[sid]='".pnVarPrepForStore($sid)."'");
			if ($dbconn->ErrorNo() !== 0) {
				$msg = $dbconn->ErrorMsg();
				include 'header.php';
				echo $msg;
				include 'footer.php';
				return;
			}
            $resultm->MoveNext();
        }
        $temp = &$pntable['stories_cat_column']['catid'];
        $result =& $dbconn->Execute("DELETE FROM $pntable[stories_cat]
                                  WHERE ${temp}='".pnVarPrepForStore($catid)."'");
		if ($dbconn->ErrorNo() !== 0) {
			$msg = $dbconn->ErrorMsg();
			include 'header.php';
			echo $msg;
			include 'footer.php';
			return;
		}
        echo _MOVEDONE;
        echo '<br />';
        add_edit_del_category($cat);
        echo '<br />[ <a href="admin.php">'._GOTOADMIN.'</a> ]';
    }
    CloseTable();
    include('footer.php');
}

function SaveEditCategory($catid, $title, $themeoverride)
{
    list($title,
          $themeoverride) = pnVarCleanFromInput('title',
                                      'themeoverride');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!pnSecAuthAction(0, 'Stories::Category', "$title::$catid", ACCESS_EDIT)) {
        include 'header.php';
        echo _STORIESEDITCATNOAUTH;
        include 'footer.php';
        return;
    }

    $catid += 0;

    $column = &$pntable['stories_cat_column'];
    $check =& $dbconn->Execute("SELECT $column[catid]
                             FROM $pntable[stories_cat]
                             WHERE $column[title] = '".pnVarPrepForStore($title)."'
                               AND $column[themeoverride] = '".pnVarPrepForStore($themeoverride)."'");
    if (!$check->EOF) {
        $what1 = _CATEXISTS;
        $what2 = _GOBACK;
    } else {
        $what1 = _CATSAVED;
        $what2 = '[ <a href="admin.php">'._GOTOADMIN.'</a> ]';
        $column = &$pntable['stories_cat_column'];
        $result =& $dbconn->Execute("UPDATE $pntable[stories_cat]
                                     SET $column[title] = '".pnVarPrepForStore($title)."',
                                     $column[themeoverride] = '".pnVarPrepForStore($themeoverride)."'
                                     WHERE $column[catid]='".pnVarPrepForStore($catid)."'");
		if ($dbconn->ErrorNo() !== 0) {
			$msg = $dbconn->ErrorMsg();
			include 'header.php';
			echo $msg;
			include 'footer.php';
			return;
		}
    }

    include ('header.php');
    GraphicAdmin();
    OpenTable();
    echo '<h1>'._CATEGORIESADMIN.'</h1>';
    CloseTable();
    OpenTable();
    echo '<div style="text-align:center"><strong>'.pnVarPrepHTMLDisplay($what1).'</strong><br />';
    echo pnVarPrepHTMLDisplay($what2).'</div>';
    CloseTable();
    include ('footer.php');
}

function SaveCategory($title, $themeoverride)
{
    list($title,$themeoverride) = pnVarCleanFromInput('title','themeoverride');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!pnSecAuthAction(0, 'Stories::Category', "$title::", ACCESS_ADD)) {
        include 'header.php';
        echo _STORIESADDCATNOAUTH;
        include 'footer.php';
        return;
    }

    $column = &$pntable['stories_cat_column'];
    $check =& $dbconn->Execute("SELECT $column[catid]
                             FROM $pntable[stories_cat]
                             WHERE $column[title]='".pnVarPrepForStore($title)."'");
    if (!$check->EOF) {
        $what1 = _CATEXISTS;
        $what2 = _GOBACK;
    } else {
        $what1 = _CATADDED;
        $what2 = '[ <a href="admin.php">'._GOTOADMIN.'</a> ]';
        $column = &$pntable['stories_cat_column'];
        $nextid = $dbconn->GenId($pntable['stories_cat']);
        $result =& $dbconn->Execute("INSERT INTO $pntable[stories_cat]
                                    ($column[catid], $column[title], $column[counter],
                                     $column[themeoverride])
                                  VALUES ($nextid, '".pnVarPrepForStore($title)."', '0', '${themeoverride}')");
		if ($dbconn->ErrorNo() !== 0) {
			$msg = $dbconn->ErrorMsg();
			include 'header.php';
			echo $msg;
			include 'footer.php';
			return;
		}
    }

    include ('header.php');
    GraphicAdmin();
    OpenTable();
    echo '<h1>'._CATEGORIESADMIN.'</h1>';
    CloseTable();
    OpenTable();
    echo '<div style="text-align:center"><strong>'.pnVarPrepHTMLDisplay($what1).'</strong><br />';
    if(!isset($cat)) {
		$cat = '';
    }
    add_edit_del_category($cat);
    echo '<br />'.pnVarPrepHTMLDisplay($what2).'</div>';
    CloseTable();
    include ('footer.php');
}

function SelectCategory($cat)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column = &$pntable['stories_cat_column'];
	//[ #469 ] Sort categories in AddStory - Rolf Rejek (homestarter)
    $selcat =& $dbconn->Execute("SELECT $column[catid], $column[title]
                              FROM $pntable[stories_cat] ORDER BY $column[title] ASC");
    echo '<strong>'._CATEGORY.'</strong> ';

    echo '<select name="catid">';
    if ($cat == 0) {
        $sel = 'selected="selected"';
    } else {
        $sel = '';
    }
    if (pnSecAuthAction(0, 'Stories::Story', ':' . _ARTICLES . ':', ACCESS_EDIT)) {
        echo '<option value="0" '.$sel.'>'._ARTICLES.'</option>';
    }

    while(list($catid, $title) = $selcat->fields) {
        if ($catid == $cat) {
            $sel = 'selected="selected"';
        } else {
            $sel = '';
        }
        if (pnSecAuthAction(0, 'Stories::Story', ":$title:", ACCESS_EDIT)) {
            echo '<option value="'.pnVarPrepForDisplay($catid).'" '.$sel.'>'.pnVarPrepForDisplay($title).'</option>';
        }
        $selcat->MoveNext();
    }
    echo '</select>';

    add_edit_del_category($cat);

}

// below are helper API function for this module
// probably they will be made redundant in later releases (AV)

function add_edit_del_category($cat)
{

    $module = pnVarCleanFromInput('module');
	$cat = pnVarPrepForDisplay($cat);

    if (pnSecAuthAction(0, 'Stories::Category', '::', ACCESS_DELETE)) {
        echo ' [ <a href="admin.php?module=AddStory&amp;op=AddCategory">'._ADD.'</a>';
        echo ' | <a href="admin.php?module=AddStory&amp;op=EditCategory&amp;catid='.$cat.'">'._EDIT.'</a>';
        echo ' | <a href="admin.php?module=AddStory&amp;op=DelCategory&amp;catid='.$cat.'">'._DELETE.'</a> ]';
    } elseif (pnSecAuthAction(0, 'Stories::Category', '::', ACCESS_ADD)) {
        echo ' [ <a href="admin.php?module=AddStory&amp;op=AddCategory">'._ADD.'</a>';
        echo ' | <a href="admin.php?module=AddStory&amp;op=EditCategory&amp;catid='.$cat.'">'._EDIT.'</a> ]';
    } elseif (pnSecAuthAction(0, 'Stories::Category', '::', ACCESS_EDIT)) {
        echo ' [ <a href="admin.php?module=AddStory&amp;op=EditCategory&amp;catid='.$cat.'">'._EDIT.'</a> ]';
	}
}

?>