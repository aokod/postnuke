<?php
// File: $Id: dl-categories.php 17336 2005-12-15 21:22:00Z larsneo $
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
// Purpose of file: function lib, routines used by many other functions
// ----------------------------------------------------------------------

/**
 * CatList
 * Recursivly creates option tags for each sub category of $scat, selects category $sel
 * @usedby search, popular, addlink
 */
function CatList($scat, $sel)
{
  if	(!isset($scat) || !is_numeric($scat)){
      pnSessionSetVar('errormsg', _MODARGSERROR);
      return false;
  }

  $dbconn =& pnDBGetConn(true);
  $pntable =& pnDBGetTables();

  $s="";
  $column = &$pntable['links_categories_column'];
  $result =& $dbconn->Execute("SELECT $column[cat_id]
                            FROM $pntable[links_categories]
                            WHERE $column[parent_id]='" . (int)pnVarPrepForStore($scat)."'");
  while(list($cid)=$result->fields) {

      $result->MoveNext();
    if ($sel==$cid) {
      $selstr=" selected";
    } else {
      $selstr='';
    }
    $s.="<option value=\"$cid\"$selstr>".CatPath($cid,0,0,0)."</option>";
    $s.=CatList($cid, $sel);
  }
  return $s;
}

/**
 * Catpath
 * Creates the full path for a category title
 * New function by toph, 20/8/2001
 * @usedby search, popular, addlink
 */
function CatPath($cid, $start, $links, $linkmyself) {

    if	(!isset($cid) || !is_numeric($cid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column = &$pntable['links_categories_column'];
    $result =& $dbconn->Execute("SELECT $column[parent_id],
                                     $column[title]
                              FROM $pntable[links_categories]
                              WHERE $column[cat_id]='" . (int)pnVarPrepForStore($cid)."'");
    list($pid, $title)=$result->fields;
    if ($linkmyself) {
        $cpath = "<a href=\"${modurl}&req=viewlink&amp;cid=$cid\">".pnVarPrepForDisplay($title)."</a>";
    } else {
        $cpath = pnVarPrepForDisplay($title);
    }
    while ($pid!=0) {
        $column = &$pntable['links_categories_column'];
        $result =& $dbconn->Execute("SELECT $column[cat_id],
                                         $column[parent_id],
                                         $column[title]
                                  FROM $pntable[links_categories]
                                  WHERE $column[cat_id]='" . pnVarPrepForStore($pid)."'");
        list($cid, $pid, $title)=$result->fields;
        if ($links) {
            $cpath = "<a href=\"${modurl}&amp;req=viewlink&amp;cid=$cid\">".pnVarPrepForDisplay($title).'</a> / '.pnVarPrepForDisplay($cpath);
        } else {
            $cpath = pnVarPrepForDisplay($title).' / '.$cpath;
        }
    }
    if ($start) {
      $cpath="<a href=\"${modurl}\">"._START."</a> / ".pnVarPrepForDisplay($cpath);
    }
    return $cpath;
}


function downloads_ItemCIDFromLID($lid) {

    if	(!isset($lid) || !is_numeric($lid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $dlcattable = $pntable['downloads_downloads'];
    $dlcatcolumn = &$pntable['downloads_downloads_column'];

    $query = "SELECT $dlcatcolumn[cid]
              FROM $dlcattable
              WHERE $dlcatcolumn[lid] = '" . (int)pnVarPrepForStore($lid)."'";
    $result =& $dbconn->Execute($query);
    list($cid) = $result->fields;
    $result->Close();

    return $cid;
}

function downloads_ItemSIDFromLID($lid) {

    if	(!isset($lid) || !is_numeric($lid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $dlcattable = $pntable['downloads_downloads'];
    $dlcatcolumn = &$pntable['downloads_downloads_column'];

    $query = "SELECT $dlcatcolumn[sid]
              FROM $dlcattable
              WHERE $dlcatcolumn[lid] = '" . (int)pnVarPrepForStore($lid)."'";
    $result =& $dbconn->Execute($query);
    list($sid) = $result->fields;
    $result->Close();

    return $sid;
}

function downloads_SubCatNumItems($sid)
{

    if	(!isset($sid) || !is_numeric($sid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $dlcattable = $pntable['downloads_downloads'];
    $dlcatcolumn = &$pntable['downloads_downloads_column'];

    $query = "SELECT $dlcatcolumn[sid]
              FROM $dlcattable
              WHERE $dlcatcolumn[sid] = '" . (int)pnVarPrepForStore($sid)."'";
    $result =& $dbconn->Execute($query);
    $catnumitems = $result->PO_RecordCount();
    $result->Close();

    return $catnumitems;
}


/*
 * Get the sub category name given its SID
 */
function downloads_SubCatNameFromSID($sid)
{
    if	(!isset($sid) || !is_numeric($sid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $dlcattable = $pntable['downloads_subcategories'];
    $dlcatcolumn = &$pntable['downloads_subcategories_column'];

    $query = "SELECT $dlcatcolumn[title]
              FROM $dlcattable
              WHERE $dlcatcolumn[sid] = '" . (int)pnVarPrepForStore($sid)."'";
    $result =& $dbconn->Execute($query);
    list($catname) = $result->fields;
    $result->Close();

    return $catname;
}

/*
 * Get the category name given its CID
 */
function downloads_CatNameFromCID($cid)
{
    if	(!isset($cid) || !is_numeric($cid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $dlcattable = $pntable['downloads_categories'];
    $dlcatcolumn = &$pntable['downloads_categories_column'];

    $query = "SELECT $dlcatcolumn[title]
              FROM $dlcattable
              WHERE $dlcatcolumn[cid] = '" . (int)pnVarPrepForStore($cid)."'";
    $result =& $dbconn->Execute($query);
    list($catname) = $result->fields;
    $result->Close();

    return $catname;
}

function downloads_ItemNameFromLID($lid)
{
    if	(!isset($lid) || !is_numeric($lid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $dlcattable = $pntable['downloads_downloads'];
    $dlcatcolumn = &$pntable['downloads_downloads_column'];

    $query = "SELECT $dlcatcolumn[title]
              FROM $dlcattable
              WHERE $dlcatcolumn[lid] = '" . (int)pnVarPrepForStore($lid)."'";
    $result =& $dbconn->Execute($query);
    list($catname) = $result->fields;
    $result->Close();

    return $catname;


}

function downloads_ItemSubmitterFromLID($lid)
{
    if	(!isset($lid) || !is_numeric($lid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $dlcattable = $pntable['downloads_downloads'];
    $dlcatcolumn = &$pntable['downloads_downloads_column'];

    $query = "SELECT $dlcatcolumn[submitter]
              FROM $dlcattable
              WHERE $dlcatcolumn[lid] = '" . (int)pnVarPrepForStore($lid)."'";
    $result =& $dbconn->Execute($query);
    list($catname) = $result->fields;
    $result->Close();

    return $catname;

}


/*
 * Get the item category given its IID
 */
function downloads_CatNameFromIID($iid)
{
    if	(!isset($iid) || !is_numeric($iid)){
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $dlitemtable = $pntable['downloads_downloads'];
    $dlitemcolumn = &$pntable['downloads_downloads_column'];

    $query = "SELECT $dlitemcolumn[cid]
              FROM $dlitemtable
              WHERE $dlitemcolumn[lid] = '" . (int)pnVarPrepForStore($iid)."'";
    $result =& $dbconn->Execute($query);
    list($cid) = $result->fields;
    $result->Close();

    $dlcattable = $pntable['downloads_categories'];
    $dlcatcolumn = &$pntable['downloads_categories_column'];

    $query = "SELECT $dlcatcolumn[title]
              FROM $dlcattable
              WHERE $dlcatcolumn[cid] = '" . pnVarPrepForStore($cid)."'";
    $result =& $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
        return false;
    }
    list($catname) = $result->fields;
    $result->Close();

    return $catname;
}

function downloads_authsubcat($cid, $sid, $actype)
{
	$ctitle = downloads_CatNameFromCID($cid);
	$stitle = downloads_SubCatNameFromSID($sid);

	if ((pnSecAuthAction(0, 'Downloads::Category', "$stitle::$sid", $actype)) &&
	    (pnSecAuthAction(0, 'Downloads::Category', "$ctitle::$cid", $actype))) {
		return true;
	} else {
		return false;
	}
}

function downloads_authitem($cid, $sid, $lid, $actype)
{
	$ititle = downloads_ItemNameFromLID($lid);

	if ($actype == ACCESS_EDIT) {
		if (pnSecAuthAction(0, 'Downloads::Item', "$ititle::$lid", $actype)) {
			return true;
		} else {
			return false;
		}
	} else {
		$ctitle = downloads_CatNameFromCID($cid);

		if ($sid == 0) {
			$subcatauth = true;
		} else {
			$stitle = downloads_SubCatNameFromSID($sid);
			$subcatauth = (pnSecAuthAction(0, 'Downloads::Category', "$stitle::$sid", $actype));
		}

		if ((pnSecAuthAction(0, 'Downloads::Category', "$ctitle::$cid", $actype)) &&
		    ($subcatauth) &&
	    	(pnSecAuthAction(0, 'Downloads::Item', "$ititle::$lid", $actype)) ) {
			return true;
		} else {
			return false;
		}
	}
}

function DownloadsNewCat() {

    if (pnSecAuthAction(0, 'Downloads::', '::', ACCESS_ADD)) {
       OpenTable();
       echo "<form method=\"post\" action=\"admin.php\"><div>"
       .'<h2>'._ADDMAINCATEGORY.'</h2>'
       .""._NAME.": <input type=\"text\" name=\"title\" size=\"30\" maxlength=\"100\" /><br />"
       .""._DESCRIPTION.":<br /><textarea name=\"cdescription\" cols=\"80\" rows=\"10\"></textarea><br />"
       ."<input type=\"hidden\" name=\"module\" value=\"".$GLOBALS['module']."\" />"
       ."<input type=\"hidden\" name=\"op\" value=\"DownloadsAddCat\" />"
       .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
       ."<input type=\"submit\" value=\""._ADD."\" /><br />"
       ."</div></form>";
       CloseTable();
    }
}

function DownloadsAddCat()
{
    list($title,
         $cdescription) = pnVarCleanFromInput('title',
                                              'cdescription');

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!pnSecAuthAction(0, 'Downloads::Category', "$title::", ACCESS_ADD)) {
        echo _DOWNLOADSCATADDNOAUTH;
        CloseTable();
        include 'footer.php';
        return;
    }

    $column = &$pntable['downloads_categories_column'];
    $result =& $dbconn->Execute("SELECT $column[cid]
                                FROM $pntable[downloads_categories]
                                WHERE $column[title]='" . pnVarPrepForStore($title) . "'");
    if (!$result->EOF) {
        include('header.php');
        GraphicAdmin();
        OpenTable();
        echo '<br /><div style="text-align:center">'
            .'<strong>'._ERRORTHECATEGORY." ".pnVarPrepForDisplay($title)." "._ALREADYEXIST.'</strong><br />'
            .""._GOBACK.'<br />';
        CloseTable();
        include('footer.php');
    } else {
// cocomp 2002/07/13 converted to use GenID instead of NULL id insert
	$cattable = $pntable['downloads_categories'];
	$cid = $dbconn->GenID($cattable,1,$column['cid']);
        $dbconn->Execute("INSERT INTO $cattable
                            ($column[cid],
                             $column[title],
                             $column[cdescription])
                          VALUES
                            ('" . pnVarPrepForStore($cid) . "',
                             '" . pnVarPrepForStore($title) . "',
                             '" . pnVarPrepForStore($cdescription) . "')");

        pnRedirect('admin.php?module='.$GLOBALS['module'].'&op=downloads');
    }
}

function DownloadsNewSubCat()
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (pnSecAuthAction(0, 'Downloads::', '::', ACCESS_ADD)) {
       $column = &$pntable['downloads_categories_column'];
       $result =& $dbconn->Execute("SELECT $column[cid],
                                        $column[title]
                                 FROM $pntable[downloads_categories]
                                 ORDER BY $column[title]");
        if (!$result->EOF) {
           OpenTable();
           echo "<form method=\"post\" action=\"admin.php\"><div>"
            .'<h2>'._ADDSUBCATEGORY.'</h2>'
            .""._NAME.": <input type=\"text\" name=\"title\" size=\"30\" maxlength=\"100\" />&nbsp;"._IN."&nbsp;"
            ."<select name=\"cid\">";

            while(list($ccid, $ctitle) = $result->fields) {
               echo "<option value=\"$ccid\">".pnVarPrepForDisplay($ctitle)."</option>";
               $result->MoveNext();
            }
            echo "</select>"
            ."<input type=\"hidden\" name=\"module\" value=\"".$GLOBALS['module']."\" />"
            ."<input type=\"hidden\" name=\"op\" value=\"DownloadsAddSubCat\" />"
            .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
            ."<input type=\"submit\" value=\""._ADD."\" /><br />"
            ."</div></form>";
            CloseTable();
         }
    }
}

function DownloadsAddSubCat()
{
    list($cid,
         $title) = pnVarCleanFromInput('cid',
                                       'title');

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!pnSecAuthAction(0, 'Downloads::Category', "$title::", ACCESS_ADD)) {
        echo _DOWNLOADSCATADDNOAUTH;
        CloseTable();
        include 'footer.php';
        return;
    }

    $column = &$pntable['downloads_subcategories_column'];
    $result =& $dbconn->Execute("SELECT $column[cid]
                                FROM $pntable[downloads_subcategories]
                                WHERE $column[title]='" . pnVarPrepForStore($title) . "'
                                AND $column[cid]='" . (int)pnVarPrepForStore($cid) . "'");
    if (!$result->EOF) {
        include('header.php');
        GraphicAdmin();
        OpenTable();
        echo '<br /><div style="text-align:center">';
        echo '<strong>'._ERRORTHESUBCATEGORY." ".pnVarPrepForDisplay($title)." "._ALREADYEXIST.'</strong><br />'
            .""._GOBACK.'<br />';
        include('footer.php');
    } else {
// cocomp 2002/07/13 converted to use GenID instead of NULL id insert
	$subtable = $pntable['downloads_subcategories'];
	$sid = $dbconn->GenID($subtable);
        $dbconn->Execute("INSERT INTO $subtable
                            ($column[sid],
                             $column[cid],
                             $column[title])
                           VALUES
                             (" .(int)pnVarPrepForStore($sid) . ",
                              " .(int)pnVarPrepForStore($cid) . ",
                              '" . pnVarPrepForStore($title) . "')");

        pnRedirect('admin.php?module='.$GLOBALS['module'].'&op=downloads');
    }
}

function DownloadsModCat($cat)
{
	$dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (pnSecAuthAction(0, 'Downloads::Category', '::', ACCESS_EDIT)) {
       include ('header.php');
       GraphicAdmin();
       OpenTable();
       echo '<h2>'._WEBDOWNLOADSADMIN.'</h2>';
       CloseTable();

       $cat = explode("-", $cat);
       if (!isset($cat[0]) || !is_numeric($cat[0])) {
          $cat[0] = 0;
       }
       if (!isset($cat[1]) || !is_numeric($cat[1])) {
          $cat[1] = 0;
       }
	   OpenTable();
	   echo '<h2>'._MODCATEGORY.'</h2>';
	   if ($cat[1]==0) {
             $column = &$pntable['downloads_categories_column'];
             $result =& $dbconn->Execute("SELECT $column[title],
                                              $column[cdescription]
                                       FROM $pntable[downloads_categories]
                                       WHERE $column[cid]='".(int)pnVarPrepForStore($cat[0])."'");

             list($title,$cdescription) = $result->fields;
             $cdescription = stripslashes($cdescription);
             echo "<form style=\"display:inline\" action=\"admin.php\" method=\"get\"><div style=\"display:inline\">"
            .""._NAME.": <input type=\"text\" name=\"title\" value=\"".pnVarPrepHTMLDisplay($title)."\" size=\"51\" maxlength=\"50\" /><br />"
            .""._DESCRIPTION.":<br /><textarea name=\"cdescription\" cols=\"80\" rows=\"10\">".pnVarPrepHTMLDisplay($cdescription)."</textarea><br />"
            ."<input type=\"hidden\" name=\"sub\" value=\"0\" />"
            ."<input type=\"hidden\" name=\"cid\" value=\"$cat[0]\" />"
            ."<input type=\"hidden\" name=\"module\" value=\"".$GLOBALS['module']."\" />"
            ."<input type=\"hidden\" name=\"op\" value=\"DownloadsModCatS\" />"
            .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
            ."<input type=\"submit\" value=\""._SAVECHANGES."\" /></div></form>&nbsp;"
            ."<form style=\"display:inline\" action=\"admin.php\" method=\"get\"><div style=\"display:inline\">"
            ."<input type=\"hidden\" name=\"sub\" value=\"0\" />"
            ."<input type=\"hidden\" name=\"cid\" value=\"$cat[0]\" />"
            ."<input type=\"hidden\" name=\"module\" value=\"".$GLOBALS['module']."\" />"
            ."<input type=\"hidden\" name=\"op\" value=\"DownloadsDelCat\" />"
            ."<input type=\"submit\" value=\""._DELETE."\" /></div></form>";
         } else {
           $column = &$pntable['downloads_categories_column'];
           $result =& $dbconn->Execute("SELECT $column[title]
                                     FROM $pntable[downloads_categories]
                                     WHERE $column[cid]='" . (int)pnVarPrepForStore($cat[0])."'");

           list($ctitle) = $result->fields;
           $column = &$pntable['downloads_subcategories_column'];
           $result2 =& $dbconn->Execute("SELECT $column[title]
                                      FROM $pntable[downloads_subcategories]
                                      WHERE $column[sid]='" . (int)pnVarPrepForStore($cat[1])."'");

           list($stitle) = $result2->fields;
           echo "<form style=\"display:inline\" action=\"admin.php\" method=\"get\"><div style=\"display:inline\">"
            .""._CATEGORY.": ".pnVarPrepHTMLDisplay($ctitle).'<br />'
            .""._SUBCATEGORY.": <input type=\"text\" name=\"title\" value=\"".pnVarPrepHTMLDisplay($stitle)."\" size=\"51\" maxlength=\"50\" /><br />"
            ."<input type=\"hidden\" name=\"sub\" value=\"1\" />"
            ."<input type=\"hidden\" name=\"cid\" value=\"$cat[0]\" />"
            ."<input type=\"hidden\" name=\"sid\" value=\"$cat[1]\" />"
            ."<input type=\"hidden\" name=\"module\" value=\"".$GLOBALS['module']."\" />"
            ."<input type=\"hidden\" name=\"op\" value=\"DownloadsModCatS\" />"
            .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
            ."<input type=\"submit\" value=\""._SAVECHANGES."\"></div></form>&nbsp;"
            ."<form style=\"display:inline\" action=\"admin.php\" method=\"get\"><div style=\"display:inline\">"
            ."<input type=\"hidden\" name=\"sub\" value=\"1\" />"
            ."<input type=\"hidden\" name=\"cid\" value=\"$cat[0]\" />"
            ."<input type=\"hidden\" name=\"sid\" value=\"$cat[1]\" />"
            ."<input type=\"hidden\" name=\"module\" value=\"".$GLOBALS['module']."\">"
            ."<input type=\"hidden\" name=\"op\" value=\"DownloadsDelCat\" />"
            ."<input type=\"submit\" value=\""._DELETE."\" /></div></form>";
           }
           CloseTable();
           include('footer.php');
     }
}

function DownloadsModCatS()
{
    list($cid,
         $sid,
         $sub,
         $title,
         $cdescription) = pnVarCleanFromInput('cid',
                                              'sid',
                                              'sub',
                                              'title',
                                              'cdescription');

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    if (!isset($cdescription)) {
        $cdescription = '';
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $catcolumn = &$pntable['downloads_categories_column'];
    $cattable = $pntable['downloads_categories'];
    $result =& $dbconn->Execute("SELECT $catcolumn[title]
                                FROM $cattable
                                WHERE $catcolumn[cid] = '" . (int)pnVarPrepForStore($cid)."'");

    list($oldtitle) = $result->fields;
    $result->Close();
    if (!pnSecAuthAction(0, 'Downloads::Category', "$oldtitle::$cid", ACCESS_EDIT)) {
        echo _DOWNLOADSCATEDITNOAUTH;
        CloseTable();
        include 'footer.php';
        return;
    }
    if ($sub==0) {
        $column = &$pntable['downloads_categories_column'];
        $dbconn->Execute("UPDATE $pntable[downloads_categories]
                          SET $column[title]='" . pnVarPrepForStore($title) . "',
                              $column[cdescription]='" . pnVarPrepForStore($cdescription) . "'
                          WHERE $column[cid]='" . (int)pnVarPrepForStore($cid)."'");
    } else {
        $column = &$pntable['downloads_subcategories_column'];
        $dbconn->Execute("UPDATE $pntable[downloads_subcategories]
                          SET $column[title]='" . pnVarPrepForStore($title) . "'
                          WHERE $column[sid]='" . (int)pnVarPrepForStore($sid)."'");
    }

    pnRedirect('admin.php?module='.$GLOBALS['module'].'&op=downloads');
}

function DownloadsDelCat()
{
    list($cid,
         $sid,
         $sub,
         $ok) = pnVarCleanFromInput('cid',
                                    'sid',
                                    'sub',
                                    'ok');

    if (!isset($ok)) {
        $ok = 0;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $catcolumn = &$pntable['downloads_categories_column'];
    $cattable = $pntable['downloads_categories'];
    $result =& $dbconn->Execute("SELECT $catcolumn[title]
                                FROM $cattable
                                WHERE $catcolumn[cid] = '" . (int)pnVarPrepForStore($cid)."'");

    list($oldtitle) = $result->fields;
    $result->Close();
    if (!pnSecAuthAction(0, 'Downloads::Category', "$oldtitle::$cid", ACCESS_DELETE)) {
        echo _DOWNLOADSCATDELNOAUTH;
        CloseTable();
        include 'footer.php';
        return;
    }

    if($ok==1) {
        if ($sub>0) {
            $dbconn->Execute("DELETE FROM $pntable[downloads_subcategories]
                              WHERE {$pntable['downloads_subcategories_column']['sid']}='" . (int)pnVarPrepForStore($sid)."'");
            $dbconn->Execute("DELETE FROM $pntable[downloads_downloads]
                              WHERE {$pntable['downloads_downloads_column']['sid']}='" . (int)pnVarPrepForStore($sid)."'");
        } else {
            $dbconn->Execute("DELETE FROM $pntable[downloads_categories]
                              WHERE {$pntable['downloads_categories_column']['cid']}='" . (int)pnVarPrepForStore($cid)."'");
            $dbconn->Execute("DELETE FROM $pntable[downloads_subcategories]
                              WHERE {$pntable['downloads_subcategories_column']['cid']}='" . (int)pnVarPrepForStore($cid)."'");
            $dbconn->Execute("DELETE FROM $pntable[downloads_downloads]
                              WHERE {$pntable['downloads_downloads_column']['cid']}='" . (int)pnVarPrepForStore($cid) . "'
                              AND {$pntable['downloads_downloads_column']['sid']}=0");
        }

        pnRedirect('admin.php?module='.$GLOBALS['module'].'&op=downloads');
    } else {
        include('header.php');
        GraphicAdmin();
        OpenTable();
        echo '<br /><div style="text-align:center">';
        echo '<strong>'._DDELCATWARNING.'</strong><br />';
    }
    echo "\n<form action=\"admin.php\" method=\"post\"><div>"
         ."<input type=\"hidden\" name=\"module\" value=\"".$GLOBALS['module']."\" />"
        ."\n<input type=\"hidden\" name=\"op\" value=\"DownloadsDelCat\" />"
        .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
        ."\n<input type=\"hidden\" name=\"cid\" value=\"$cid\" />"
        ."\n<input type=\"hidden\" name=\"sid\" value=\"$sid\" />"
        ."\n<input type=\"hidden\" name=\"sub\" value=\"$sub\" />"
        ."\n<input type=\"hidden\" name=\"ok\" value=\"1\" />"
        ."\n<input type=\"submit\" value=\""._YES."\" />"
        ."\n<input type=\"reset\" value=\""._NO."\" onClick=\"window.location.href = 'admin.php?module=".$GLOBALS['module']."&amp;op=main'\" />"
        ."\n</div></form><br /></div>";
    CloseTable();
    include 'footer.php';
}


function downloads_outputpagelinks($catid, $modurl, $orderby, $totalselecteddownloads, $perpage, $min, $max, $show, $dllinktext, $dlidtext)
{

    if (!isset($modurl)){
    	$modurl = $GLOBALS['modurl'];
    }
    $orderby = convertorderbyout($orderby);
    /* Calculates how many pages exist.  Which page one should be on, etc... */

    $downloadpagesint = ($totalselecteddownloads / $perpage);
    $downloadpageremainder = ($totalselecteddownloads % $perpage);
    if ($downloadpageremainder != 0) {
        $downloadpages = ceil($downloadpagesint);
        if ($totalselecteddownloads < $perpage) {
            $downloadpageremainder = 0;
        }
    } else {
        $downloadpages = $downloadpagesint;
    }


    /* Page Numbering */
    if ($downloadpages > 1) {
        echo '<br />'._SELECTPAGE.": ";
        $prev=$min-$perpage;
        if ($prev>=0) {
            echo "&nbsp;&nbsp;[ <a href=\"$modurl&amp;req=$dllinktext&amp;$dlidtext=$catid&amp;min=$prev&amp;orderby=$orderby&amp;show=$show\">"
            ." &lt;&lt; "._PREVIOUS."</a> ] ";
        }
        $counter = 1;
        $currentpage = ($max / $perpage);
        while ($counter<=$downloadpages ) {
            $cpage = $counter;
            $mintemp = ($perpage * $counter) - $perpage;
            if ($counter == $currentpage) {
                echo (int)$counter."&nbsp";
            } else {
                echo "<a href=\"$modurl&amp;req=$dllinktext&amp;$dlidtext=$catid&amp;min=$mintemp&amp;orderby=$orderby&amp;show=$show\">".(int)$counter."</a> ";
            }
            $counter++;
        }
        $next=$min+$perpage;
        if ($currentpage < $downloadpages) {
            echo "&nbsp;&nbsp;[ <a href=\"$modurl&amp;req=$dllinktext&amp;$dlidtext=$catid&amp;min=$max&amp;orderby=$orderby&amp;show=$show\">"
            ." "._NEXT." &gt;&gt;</a> ] ";
        }
    }
}

function downloads_outputitem ($lid, $url, $title, $description, $time, $hits, $downloadratingsummary, $totalvotes, $totalcomments, $filesize, $version, $homepage, $modurl, $ModName)
{
	if (!isset($modurl)){
		$modurl = $GLOBALS['modurl'];
	}
	if (!isset($ModName)){
		$ModName = $GLOBALS['ModName'];
	}
	if (downloads_authitem((downloads_ItemCIDFromLID($lid)), (downloads_ItemSIDFromLID($lid)), $lid, ACCESS_READ) ) {
		$downloadratingsummary = number_format($downloadratingsummary, pnConfigGetVar('mainvotedecimal'));
		$title = pnVarPrepForDisplay($title); 
		$description = pnVarPrepHTMLDisplay($description);
		$transfertitle = str_replace (" ", "_", $title);

		//if (eregi(".pdf", $url)) {
			$dlicon = "editicon.gif";
		//} else {
		//	$dlicon = "lwin.gif";
		//}

		if (downloads_authitem((downloads_ItemCIDFromLID($lid)), (downloads_ItemSIDFromLID($lid)), $lid, ACCESS_EDIT) ) {
			echo "<a href=\"admin.php?module=$ModName&amp;op=DownloadsModDownload&amp;lid=$lid\"><img src=\"modules/$ModName/images/$dlicon\" alt=\""._EDIT."\" height=\"19\" width=\"17\" /></a>&nbsp;";
		}

		echo "<h3 style=\"display:inline\"><a href=\"$modurl&amp;req=getit&amp;lid=$lid\">$title</a></h3>";
		newdownloadgraphic($datetime, $time);
		popgraphic($hits);
		/* code for *editor review* insert here */
		detecteditorial($lid, $transfertitle, 1);

		//transform hooks
		list($description) = pnModCallHooks('item', 'transform', '', array($description));

		echo '<br />'._DESCRIPTION.": $description<br />";
		/* cocomp 2002/07/13 unnecessary date stuff
		ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime);
		$datetime = ml_ftime(""._LINKSDATESTRING."", mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
		$datetime = ucfirst($datetime);
		*/
		echo _VERSION.": ".pnVarPrepForDisplay($version)." | "._FILESIZE.": ".CoolSize($filesize).'<br />';
		echo _ADDEDON.": ".pnVarPrepForDisplay($datetime)." | "._UDOWNLOADS.": ".(int)$hits;
		$transfertitle = str_replace (" ", "_", $title);

		/* voting & comments stats */
		if ($totalvotes == 1) {
			$votestring = _VOTE;
		} else {
			$votestring = _VOTES;
		}
		if ($downloadratingsummary!="0" || $downloadratingsummary!="0.0") {
			echo " | "._RATING.": ".pnVarPrepForDisplay($downloadratingsummary)." (".(int)$totalvotes." ".pnVarPrepForDisplay($votestring).")";
			// cocomp 2002/07/13 added ratings (I know there's a global in there too!)
			// skooter 2002/10/24 - removed global replaced with $GLOBALS[]
			//global $download_show_star;
			if ($GLOBALS['download_show_star']) {
				echo '<br />' . downloads_rateMakeStar($downloadratingsummary, 10);
			}
		}
		if ($homepage == "") {
			echo '<br />';
		} else {
			echo "<br /><a href=\"".pnVarPrepForDisplay($homepage)."\">"._HOMEPAGE."</a> | ";
		}

		if (downloads_authitem((downloads_ItemCIDFromLID($lid)), (downloads_ItemSIDFromLID($lid)), $lid, ACCESS_COMMENT) ) {
			echo "<a href=\"$modurl&amp;req=ratedownload&amp;lid=$lid\">"._RATERESOURCE."</a>";
			echo " | ";
		}
		echo "<a href=\"$modurl&amp;req=viewdownloaddetails&amp;lid=$lid\">"._DETAILS."</a>";
		if ($totalcomments != 0) {
			echo " | <a href=\"$modurl&amp;req=viewdownloadcomments&amp;lid=$lid\">"._COMMENTS." (".(int)$totalcomments.")</a>";
		}
		detecteditorial($lid, $transfertitle, 0);
		echo "<br /><br />";
	}
}

?>