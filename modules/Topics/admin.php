<?php
// File: $Id: admin.php 17537 2006-01-12 13:58:00Z larsneo $
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
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_MODULE')) { die ('Access Denied'); }

$ModName = $module;
modules_get_language();
modules_get_manual();

/**
 * Topics Manager Functions
 */

function topicsmanager()
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $tipath = pnConfigGetVar('tipath');
    $topicsinrow = pnConfigGetVar('topicsinrow');

    include('header.php');
    GraphicAdmin();

    $statusmsg = pnGetStatusMsg();
    if ($statusmsg != '') {
    	OpenTable();
    	echo '<div class="pn-statusmsg">'.$statusmsg.'</div>';
    	CloseTable();
    }

    OpenTable();
    echo '<h1>'._TOPICSMANAGER.'</h1>';
    CloseTable();

    // List of current topics
    if (pnSecAuthAction(0, 'Topics::Topic', '::', ACCESS_READ)) {
        OpenTable();
        echo '<h2>'._CURRENTTOPICS.'</h2>'._CLICK2EDIT
            .'<table border="0" width="100%" cellpadding="2"><tr>';
        $count = 0;
        $column = &$pntable['topics_column'];
        $result =& $dbconn->Execute("SELECT $column[topicid], $column[topicname], $column[topicimage], $column[topictext] 
									FROM $pntable[topics]
									ORDER BY $column[topicname]");
        while(list($topicid, $topicname, $topicimage, $topictext) = $result->fields) {
            $result->MoveNext();
            echo '<td align="center" valign="top">';
            if (pnSecAuthAction(0, 'Topics::Topic', "$topicname::$topicid", ACCESS_EDIT)) {
				if (is_file($tipath.$topicimage)) {
                	echo '<a href="admin.php?module=Topics&amp;op=topicedit&amp;topicid='.pnVarPrepForDisplay($topicid)."\"><img src=\"".pnVarPrepForDisplay($tipath.$topicimage).'" alt="" /></a><br />';
				}
                echo '<a href="admin.php?module=Topics&amp;op=topicedit&amp;topicid='.pnVarPrepForDisplay($topicid).'"><strong>'.pnVarPrepForDisplay($topictext).'</strong></a></td>';
            } else {
				if (is_file($tipath.$topicimage)) {
	                echo '<img src="'.pnVarPrepForDisplay($tipath.$topicimage).'" alt="" /><br />';
				}
    			echo '<strong>'.pnVarPrepForDisplay($topictext).'</strong></a></td>';
            }
            $count++;
            if ($count == $topicsinrow) {    // changed hardcoded number of topics icons - rwwood
                echo '</tr><tr>';
                $count = 0;
            }
        }

        echo '</tr></table>';
        echo _ROWDEFINE;    // added for topics icon spacing - rwwood

        CloseTable();
    }

    // Add a topic
    if (pnSecAuthAction(0, 'Topics::Topic', '::', ACCESS_ADD)) {
        echo '<a id="Add"></a>';
        OpenTable();
        echo '<h2>'._ADDATOPIC.'</h2>'
            .'<form action="admin.php" method="post" name="topic_form"><div>'
            .'<strong>'._TOPICNAME.':</strong><br /><span class="pn-sub">'._TOPICNAME1.'<br />'
            ._TOPICNAME2.'</span><br />'
            .'<input type="text" name="topicname" size="20" maxlength="20" value="'.pnVarPrepForDisplay($topicname).'" /><br />'
            .'<strong>'._TOPICTEXT.':</strong><br /><span class="pn-sub">'._TOPICTEXT1.'<br />'
            ._TOPICTEXT2."</span><br />"
            .'<input type="text" name="topictext" size="40" maxlength="40" value="'.pnVarPrepForDisplay($topictext).'" /><br />'
            .'<strong>'._TOPICIMAGE.':</strong><br /><span class="pn-sub">('._TOPICIMAGE1.' '.pnVarPrepForDisplay($tipath).')<br />'
            ._TOPICIMAGE2.'</span><br />';
            
        //Based on the Hack by Gary Hammond to display the icon and to change dynamically
        echo '<select name="topicimage" onChange="document.images.topicimage.src=\'images/topics/\'+document.topic_form.topicimage.options[document.topic_form.topicimage.selectedIndex].value">';

        $filelist = array();
        $handle = opendir('images/topics');
        while ($file = readdir($handle)) {
            if ($file != '.' && $file != '..' && $file != 'index.html' && $file != 'CVS') {
                $filelist[] = $file;
            }
        } 
        asort($filelist);
        foreach ($filelist as $key=>$file) {
            echo '<option value="' . pnVarPrepForDisplay($file) . '"';
            if ($key == 0) {
                echo ' selected="selected"';
            }
            echo '>' . pnVarPrepForDisplay($file) . '</option>';
        } 
        echo '</select>&nbsp;&nbsp;';
    
        if (isset($filelist[0])) {
            $file = $filelist[0];
        } else {
            $file = '';
        }
        echo '<br /><br /><img src="images/topics/' . pnVarPrepForDisplay($file) . '" name="topicimage" alt="" /><br /><br />'
            .'<input type="hidden" name="op" value="topicmake" />'
            .'<input type="hidden" name="module" value="Topics" />'
	        .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
            .'<input type="submit" value="'._ADDTOPIC.'" />'
            .'</div></form>';
        CloseTable();
    }
	// Access Topics Settings
    OpenTable();
    echo '<h2>'._TOPICSCONF.'</h2>';
    echo '<div style="text-align:center"><a href="admin.php?module=Topics&amp;op=getConfig">'._TOPICSCONF.'</a></div>';
    CloseTable();
    include('footer.php');
}

function topicedit()
{
    $topicid = pnVarCleanFromInput('topicid');

    $authid = pnSecGenAuthKey();

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $tipath = pnConfigGetVar('tipath');

    include('header.php');
    GraphicAdmin();
    OpenTable();
    echo '<h1>'._TOPICSMANAGER.'</h1>';
    CloseTable();

    $column = &$pntable['topics_column'];
    $result =& $dbconn->Execute("SELECT $column[topicid], $column[topicname], $column[topicimage], $column[topictext] 
								FROM $pntable[topics] 
								WHERE $column[topicid]='".(int)pnVarPrepForStore($topicid)."'");
    list($topicid, $topicname, $topicimage, $topictext) = $result->fields;

    if (!(pnSecAuthAction(0, 'Topics::Topic', "$topicname::$topicid", ACCESS_EDIT))) {
        echo _TOPICSEDITNOAUTH;
        include 'footer.php';
        return;
    }
    echo '<form action="admin.php" method="post" name="topic_form">';
    OpenTable();
    echo '<img src="'.pnVarPrepForDisplay($tipath).pnVarPrepForDisplay($topicimage).'" alt="'.pnVarPrepForDisplay($topictext).'" /><br />'
        .'<h2>'._EDITTOPIC.': '.pnVarPrepForDisplay($topictext).'</h2>'
        .'<br />'
        .'<strong>'._TOPICNAME.':</strong><br /><span class="pn-sub">'._TOPICNAME1.'<br />'
        ._TOPICNAME2.'</span><br />'
        .'<input type="text" name="topicname" size="20" maxlength="20" value="'.pnVarPrepForDisplay($topicname).'" /><br />'
        .'<strong>'._TOPICTEXT.':</strong><br /><span class="pn-sub">'._TOPICTEXT1.'<br />'
        ._TOPICTEXT2.'</span><br />'
        .'<input type="text" name="topictext" size="40" maxlength="40" value="'.pnVarPrepForDisplay($topictext).'" /><br />'
        .'<strong>'._TOPICIMAGE.':</strong><br /><span class="pn-sub">('._TOPICIMAGE1.' '.pnVarPrepForDisplay($tipath).'<br />'
        ._TOPICIMAGE2.'</span><br />';
        
        //Based on the Hack by Gary Hammond to display the icon and to change dynamically
        echo '<select name="topicimage" onChange="document.images.topicimage.src=\'images/topics/\'+document.topic_form.topicimage.options[document.topic_form.topicimage.selectedIndex].value">';

        $filelist = array();
        $handle = opendir('images/topics');
        while ($file = readdir($handle)) {
            if ($file != '.' && $file != '..' && $file != 'index.html' && $file != 'CVS') {
                $filelist[] = $file;
            }
        } 
        asort($filelist);
        foreach ($filelist as $file) {
            echo '<option value="' . pnVarPrepForDisplay($file) . '"';
            if ($file == $topicimage) {
                echo ' selected="selected"';
            } 
            echo '>' . pnVarPrepForDisplay($file) . '</option>';
        } 
        echo '</select>&nbsp;&nbsp;';
        echo '<br /><br /><img src="images/topics/' . pnVarPrepForDisplay($topicimage) . '" name="topicimage" alt="" /><br /><br />';

    if (pnSecAuthAction(0, 'Topics::Related', "$topicname::", ACCESS_ADD)) {
        echo '<strong>'._ADDRELATED.':</strong><br />'
             ._SITENAME.': <input type="text" name="name" size="30" maxlength="30" /><br />'
             ._URL.': <input type="text" name="url" value="http://" size="50" maxlength="200" /><br />';
    }
    CloseTable();
	
    if (pnSecAuthAction(0, 'Topics::Related', "$topicname::", ACCESS_EDIT)) {
        OpenTable2();
        echo '<h2>'._ACTIVERELATEDLINKS.':</h2>';
        $column = &$pntable['related_column'];
        $res =& $dbconn->Execute("SELECT $column[rid], $column[name], $column[url] 
									FROM $pntable[related] 
									WHERE $column[tid]='".(int)pnVarPrepForStore($topicid)."'");
        if ($res->EOF) {
            echo '<span class="pn-sub">'._NORELATED.'</span>';
        } else {
        	echo '<ul>';
	
	        while(list($rid, $name, $url) = $res->fields) {

            $res->MoveNext();
            echo '<li><a href="'.pnVarPrepForDisplay($url).'">'.pnVarPrepForDisplay($name).'</a> '.
                 '<a href="'.pnVarPrepForDisplay($url).'">'.pnVarPrepForDisplay($url).'</a> ';
            if (pnSecAuthAction(0, 'Topics::Related', "$topicname::", ACCESS_EDIT)) {
                echo '[ <a href="admin.php?module=Topics&amp;op=relatededit&amp;tid='.pnVarPrepForDisplay($topicid).
                      '&amp;rid='.pnVarPrepForDisplay($rid).'&amp;authid='.pnVarPrepForDisplay($authid).'">'._EDIT.'</a>';
                if (pnSecAuthAction(0, 'Topics::Related', "$topicname::", ACCESS_DELETE)) {
                    echo ' | <a href="admin.php?module=Topics&amp;op=relateddelete&amp;tid='.pnVarPrepForDisplay($topicid).
                         '&amp;rid='.pnVarPrepForDisplay($rid).'&amp;authid='.pnVarPrepForDisplay($authid).'">'._DELETE.'</a> ]';
                } else {
                    echo ' ]';
                }
            }
            echo '</li>';
        	}
        	echo '</ul>';
        }

        CloseTable2();
        echo '<br />';
    }
    echo '<input type="hidden" name="topicid" value="'.pnVarPrepForDisplay($topicid).'" />'
        .'<input type="hidden" name="op" value="topicchange" />'
        .'<input type="hidden" name="module" value="Topics" />'
	    .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
        .'<input type="submit" value="'._SAVECHANGES.'" /> '
        .'[ <a href="admin.php?module=Topics&amp;op=topicdelete&amp;topicid='.pnVarPrepForDisplay($topicid).
         '&amp;ok=0&amp;authid='.pnVarPrepForDisplay($authid).'">'._DELETE.'</a> ]'
        .'</form>';
    include('footer.php');
}

function relatededit()
{
    list($tid,
	 $rid) = pnVarCleanFromInput('tid',
				     'rid');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $tipath = pnConfigGetVar('tipath');

    include('header.php');
    GraphicAdmin();
    OpenTable();
    echo '<h1>'._TOPICSMANAGER.'</h1>';
    CloseTable();

    // grab an entry from the related table

    $column = &$pntable['related_column'];
    //$sql = buildQuery(array('related'), array($column['name'], $column['url']), "$column[rid]=$rid", '');
	$sql = "SELECT $column[name], $column[url]
			FROM $pntable[related]
			WHERE $column[rid]='".(int)pnVarPrepForStore($rid)."'";
    $result = $dbconn->SelectLimit($sql,1);
    list($name, $url) = $result->fields;
    $result->Close();

    // grab the topic and description
    $column = &$pntable['topics_column'];
    //$sql = buildQuery(array('topics'), array($column['topictext'], $column['topicimage'],$column['topictext']), "$column[topicid]='".pnVarPrepForStore($tid)."'", '');
	$sql = "SELECT $column[topictext], $column[topicimage], $column[topictext]
			FROM $pntable[topics]
			WHERE $column[topicid]='".(int)pnVarPrepForStore($tid)."'";
    $result = $dbconn->SelectLimit($sql,1);
    list($topictext, $topicimage, $topicname) = $result->fields;

    if (!(pnSecAuthAction(0, 'Topics::Related', "$name:$topicname:$tid", ACCESS_EDIT))) {
        echo _TOPICSEDITNOAUTH;
        include 'footer.php';
        return;
    }
    OpenTable();
    echo '<div style="text-align:center">'
        .'<img src="'.pnVarPrepForDisplay($tipath).pnVarPrepForDisplay($topicimage).'" alt="'.pnVarPrepForDisplay($topictext).'" />'
        .'<h2>'._EDITRELATED.'</h2>'
        .'<strong>'._TOPIC.':</strong> '.pnVarPrepForDisplay($topictext).'</div>'
        .'<form action="admin.php" method="post"><div>'
        ._SITENAME.': <input type="text" name="name" value="'.pnVarPrepForDisplay($name).'" size="30" maxlength="30" /><br />'
        ._URL.': <input type="text" name="url" value="'.pnVarPrepForDisplay($url).'" size="60" maxlength="200" /><br />'
        .'<input type="hidden" name="op" value="relatedsave" />'
        .'<input type="hidden" name="module" value="Topics" />'
        .'<input type="hidden" name="tid" value="'.pnVarPrepForDisplay($tid).'" />'
        .'<input type="hidden" name="rid" value="'.pnVarPrepForDisplay($rid).'" />'
	    .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
        .'<input type="submit" value="'._SAVECHANGES.'" /> '._GOBACK
        .'</div></form>';
    CloseTable();
    include('footer.php');
}

function relatedsave()
{
    list($tid,
	 $rid,
	 $name,
	 $url) = pnVarCleanFromInput('tid',
				     'rid',
				     'name',
				     'url');
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column = &$pntable['topics_column'];
	$sql = "SELECT $column[topicname]
               FROM $pntable[topics]
               WHERE $column[topicid]='".(int)pnVarPrepForStore($tid)."' 
			   ORDER BY $column[topicid]";

    $result =& $dbconn->SelectLimit($sql,1);

    list($topicname) = $result->fields;
    $result->Close();
    if (!(pnSecAuthAction(0, 'Topics::Related', "$name:$topicname:$tid", ACCESS_EDIT))) {
        include 'header.php';
        echo _TOPICSEDITNOAUTH;
        include 'footer.php';
        return;
    }

    $column = &$pntable['related_column'];
    $dbconn->Execute("UPDATE $pntable[related] 
					SET $column[name]='".pnVarPrepForStore($name)."', 
						$column[url]='".pnVarPrepForStore($url)."' 
					WHERE $column[rid]='".(int)pnVarPrepForStore($rid)."'");
    pnRedirect('admin.php?module=Topics&op=topicedit&topicid='.$tid);
}

function relateddelete()
{
    list($tid,
	  $rid) = pnVarCleanFromInput('tid',
				      'rid');

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column = &$pntable['topics_column'];
	$sql = "SELECT $column[topicname]
              FROM $pntable[topics]
              WHERE $column[topicid]='".(int)pnVarPrepForStore($tid)."' 
			  ORDER BY $column[topicid]";

    $result =& $dbconn->SelectLimit($sql,1);

    list($topicname) = $result->fields;
    $result->Close();
    if (!(pnSecAuthAction(0, 'Topics::Related', "$name:$topicname:$tid", ACCESS_DELETE))) {
        include 'header.php';
        echo _TOPICSDELNOAUTH;
        include 'footer.php';
        return;
    }

    $column = &$pntable['related_column'];
    $dbconn->Execute("DELETE FROM $pntable[related] 
						WHERE $column[rid]='".(int)pnVarPrepForStore($rid)."'");
    pnRedirect('admin.php?module=Topics&op=topicedit&topicid='.$tid);
}

function topicmake()
{
    list($topicname,
	 $topicimage,
	 $topictext) = pnVarCleanFromInput('topicname',
					   'topicimage',
					   'topictext');
					   
		if (empty($topicname)) {
				include 'header.php';
				echo _TOPICNAMEEMPTY;
				include 'footer.php';
		}
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    $topicimage = pnVarPrepForOS($topicimage);
    $tipath = pnConfigGetVar('tipath');
    if(file_exists("$tipath$topicimage")) {
        $dbconn =& pnDBGetConn(true);
        $pntable =& pnDBGetTables();
        
        if (!(pnSecAuthAction(0, 'Topics::Topic', "$topicname::", ACCESS_ADD))) {
            include 'header.php';
            echo _TOPICSADDNOAUTH;
            include 'footer.php';
            return;
        }
        
        $column = &$pntable['topics_column'];
        $nextid = $dbconn->GenId($pntable['topics']);
        $dbconn->Execute("INSERT INTO $pntable[topics] ($column[topicid], $column[topicname], $column[topicimage], $column[topictext], $column[counter]) VALUES ($nextid,'".pnVarPrepForStore($topicname)."','".pnVarPrepForStore($topicimage)."','".pnVarPrepForStore($topictext)."',0)");
    } else {
        pnSessionSetVar( 'errormsg', _TOPICIMAGENOTFOUND );
    }
    pnRedirect('admin.php?module=Topics&op=topicsmanager');
}

function topicchange()
{
    list($topicid,
	 $topicname,
	 $topicimage,
	 $topictext,
	 $name,
	 $url) = pnVarCleanFromInput('topicid',
				     'topicname',
				     'topicimage',
				     'topictext',
				     'name',
				     'url');
		if (empty($topicname)) {
				include 'header.php';
				echo _TOPICNAMEEMPTY;
				include 'footer.php';
		}
    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $topicimage = pnVarPrepForOS($topicimage);
    $tipath = pnConfigGetVar('tipath');
    if(file_exists("$tipath$topicimage")) {
        // Must use old topicname for authorisation check
        $column = &$pntable['topics_column'];
	    $sql = "SELECT $column[topicname]
                  FROM $pntable[topics]
                  WHERE $column[topicid]='".(int)pnVarPrepForStore($topicid)."' 
				  ORDER BY $column[topicid]";
        
        $result =& $dbconn->SelectLimit($sql,1);
        
        list($oldtopicname) = $result->fields;
        $result->Close();
        if (!(pnSecAuthAction(0, 'Topics::Topic', "$oldtopicname::$topicid", ACCESS_EDIT))) {
            include 'header.php';
            echo _TOPICSEDITNOAUTH;
            include 'footer.php';
            return;
        }
        
        $column = &$pntable['topics_column'];
        $dbconn->Execute("UPDATE $pntable[topics] 
						SET $column[topicname]='".pnvarPrepForStore($topicname)."', $column[topicimage]='".pnvarPrepForStore($topicimage)."', $column[topictext]='".pnvarPrepForStore($topictext)."' 
						WHERE $column[topicid]='".(int)pnvarPrepForStore($topicid)."'");
        if (!$name) {
        } else {
            $nextid = $dbconn->GenId($pntable['related']);
            $column = &$pntable['related_column'];
            $dbconn->Execute("INSERT INTO $pntable[related] ($column[rid], $column[tid], $column[name], $column[url]) 
							VALUES ($nextid, '".pnvarPrepForStore($topicid)."','".pnvarPrepForStore($name)."','".pnvarPrepForStore($url)."')");
        }
    } else {
        pnSessionSetVar('errormsg', _TOPICIMAGENOTFOUND);
    }
    
    pnRedirect('admin.php?module=Topics&op=topicsmanager');
}

function topicdelete()
{
    list($topicid,
	 $ok) = pnVarCleanFromInput('topicid',
				    'ok');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $column = &$pntable['topics_column'];
	$sql = "SELECT $column[topicname]
               FROM $pntable[topics]
               WHERE $column[topicid]='".(int)pnvarPrepForStore($topicid)."' 
			   ORDER BY $column[topicid]";

    $result =& $dbconn->SelectLimit($sql,1);

    list($oldtopicname) = $result->fields;
    $result->Close();
    if (!(pnSecAuthAction(0, 'Topics::Topic', "$oldtopicname::$topicid", ACCESS_DELETE))) {
        include 'header.php';
        echo _TOPICSDELNOAUTH;
        include 'footer.php';
        return;
    }
    if ($ok==1) {

    	if (!pnSecConfirmAuthKey()) {
            include 'header.php';
            echo _BADAUTHKEY;
            include 'footer.php';
            exit;
		}
        $column = &$pntable['stories_column'];
        $result =& $dbconn->Execute("SELECT $column[sid] FROM $pntable[stories] WHERE $column[topic]='".(int)pnVarPrepForStore($topicid)."'");
        list($sid) = $result->fields;
        $dbconn->Execute("DELETE FROM $pntable[stories] WHERE {$pntable['stories_column']['topic']}='".(int)pnVarPrepForStore($topicid)."'");
        $dbconn->Execute("DELETE FROM $pntable[topics] WHERE {$pntable['topics_column']['topicid']}='".(int)pnVarPrepForStore($topicid)."'");
        $dbconn->Execute("DELETE FROM $pntable[related] WHERE {$pntable['related_column']['tid']}='".(int)pnVarPrepForStore($topicid)."'");
		if (pnModAvailable('Comments')) {
			$column = &$pntable['comments_column'];
			$result =& $dbconn->Execute("SELECT $column[sid] FROM $pntable[comments] WHERE $column[sid]='".(int)pnVarPrepForStore($sid)."'");
			list($sid) = $result->fields;
			$result->Close();
			$dbconn->Execute("DELETE FROM $pntable[comments] WHERE {$pntable['comments_column']['sid']}='".(int)pnVarPrepForStore($sid)."'");
		}
        pnRedirect('admin.php?module=Topics&op=topicsmanager');
    } else {
        global $topicimage;

        $tipath = pnConfigGetVar('tipath');
	$authid = pnSecGenAuthKey();

        include('header.php');
        GraphicAdmin();
        OpenTable();
        echo '<h1>'._TOPICSMANAGER.'</h1>';
        CloseTable();

	$column = &$pntable['topics_column'];
        $result =& $dbconn->Execute("SELECT $column[topicimage], $column[topictext] 
									FROM $pntable[topics] 
									WHERE $column[topicid]='".(int)pnVarPrepForStore($topicid)."'");
        list($topicimage, $topictext) = $result->fields;
        OpenTable();
        echo '<div style="text-align:center"><img src="'.pnVarPrepForDisplay($tipath).pnVarPrepForDisplay($topicimage).'" alt="'.pnVarPrepForDisplay($topictext).'" /><br />'
            .'<strong><h2>'._DELETETOPIC.' '.pnVarPrepForDisplay($topictext).'</h2></strong><br />'
            ._TOPICDELSURE.' <em>'.pnVarPrepForDisplay($topictext).'</em>?<br />'
            ._TOPICDELSURE1.'<br />'
            .'[ <a href="admin.php?module=Topics&amp;op=topicsmanager">'._NO
        	.'</a> | <a href="admin.php?module=Topics&amp;op=topicdelete&amp;topicid='.pnVarPrepForDisplay($topicid).'&amp;ok=1&amp;authid='.pnVarPrepForDisplay($authid).'">'._YES.
            '</a> ]</div>';
        CloseTable();
        include('footer.php');
    }
}

function topics_admin_getConfig() {

    include ('header.php');

    if (!pnSecAuthAction(0, 'Topics::', '::', ACCESS_ADMIN)) {
        echo _TOPICSNOAUTH;
        include 'footer.php';
        return;
    }

    // prepare vars
    $sel_topicsinrow['1'] = '';
    $sel_topicsinrow['2'] = '';
    $sel_topicsinrow['3'] = '';
    $sel_topicsinrow['4'] = '';
    $sel_topicsinrow['5'] = '';
    $sel_topicsinrow[pnConfigGetVar('topicsinrow')] = ' selected="selected"';

    GraphicAdmin();
    OpenTable();
    print '<h2>'._TOPICSCONF.'</h2>'
        .'<form action="admin.php" method="post"><div>'
        .'<table border="0"><tr><td>'
        ._TOPICSPATH.'</td><td><input type="text" name="xtipath" value="'.pnConfigGetVar('tipath').'" size="50" />'
        .'</td></tr>'
        .'<tr><td>'
        ._TOPICSINROW.'</td><td>'
        .'<select name="xtopicsinrow" size="1">';
        $topicsinrows = array('1', '2', '3', '4', '5');
        foreach ( $topicsinrows as $topicsinrow){
        	echo '<option value="'.pnVarPrepForDisplay($topicsinrow).'" '.$sel_topicsinrow[$topicsinrow].'>'.pnVarPrepForDisplay($topicsinrow).'</option>'."\n";
        }
        echo "</select>\n"
        .'</td></tr>'
        .'</table>'
        .'<input type="hidden" name="module" value="Topics" />'
        .'<input type="hidden" name="op" value="setConfig" />'
		.'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
        .'<input type="submit" value="'._SUBMIT.'" />'
        .'</div></form>';
    CloseTable();
    include ('footer.php');
}

function topics_admin_setConfig($var)
{
    if (!pnSecAuthAction(0, 'Topics::', '::', ACCESS_ADMIN)) {
        include 'header.php';
        echo _TOPICSNOAUTH;
        include 'footer.php';
        return;
    }

    if (!pnSecConfirmAuthKey()) {
        include 'header.php';
        echo _BADAUTHKEY;
        include 'footer.php';
        exit;
    }

    // Escape some characters in these variables.
    // hehe, I like doing this, much cleaner :-)
    $fixvars = array();

    // todo: make FixConfigQuotes global / replace with other function
    foreach ($fixvars as $v) {
	//$var[$v] = FixConfigQuotes($var[$v]);
    }

    // Set any numerical variables that havn't been set, to 0. i.e. paranoia check :-)
    $fixvars = array();
    foreach ($fixvars as $v) {
        if (empty($var[$v])) {
            $var[$v] = 0;
        }
    }

    // all variables starting with x are the config vars.
    while (list ($key, $val) = each ($var)) {
        if (substr($key, 0, 1) == 'x') {
            pnConfigSetVar(substr($key, 1), $val);
        }
    }
    pnRedirect('admin.php?module=Topics&op=main');
}

function topics_admin_main($var)
{
   $op = pnVarCleanFromInput('op');
   extract($var);

   if (!pnSecAuthAction(0, 'Topics::', '::', ACCESS_EDIT)) {
       include 'header.php';
       echo _TOPICSNOAUTH;
       include 'footer.php';
       return;
   } else {
       switch ($op) {

        case 'topicsmanager':
            topicsmanager();
            break;

        case 'topicedit':
            topicedit();
            break;

        case 'topicmake':
            topicmake();
            break;

        case 'topicdelete':
            topicdelete();
            break;

        case 'topicchange':
            topicchange();
            break;

        case 'relatedsave':
            relatedsave();
            break;

        case 'relatededit':
            relatededit();
            break;

        case 'relateddelete':
            relateddelete();
            break;

       	case 'getConfig':
            topics_admin_getConfig();
            break;

     	case 'setConfig':
           topics_admin_setConfig($var);
           break;

        default:
            topicsmanager();
            break;
       }
   }
}

?>