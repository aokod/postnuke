<?php
// $Id: index.php 16647 2005-08-19 08:23:03Z landseer $
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
// Filename: modules/Top_List/index.php
// Original Author of file: Francisco Burzi
// Purpose of file: Display top x listings on your site.
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_MODULE')) {
    die ("You can't access this file directly...");
}

$ModName = basename(dirname(__FILE__));

modules_get_language();

$dbconn =& pnDBGetConn(true);
$pntable =& pnDBGetTables();

$currentlang = pnUserGetLang();

if (pnConfigGetVar('multilingual') == 1) {
    $column = &$pntable['stories_column'];
    $queryalang = "WHERE ($column[alanguage]='$currentlang' OR $column[alanguage]='')"; /* top stories */
    $column = &$pntable['seccont_column'];
    $queryslang = "WHERE ($column[slanguage]='$currentlang' OR $column[slanguage]='')"; /* top section articles */
    $column = &$pntable['poll_desc_column'];
    $queryplang = "WHERE ($column[planguage]='$currentlang' OR $column[planguage]='')"; /* top polls */
    $column = &$pntable['reviews_column'];
    $queryrlang = "WHERE ($column[rlanguage]='$currentlang' OR $column[rlanguage]='')"; /* top reviews */
} else {
    $queryalang = '';
    $queryrlang = '';
    $queryplang = '';
    $queryslang = '';
}

include 'header.php';

if (!pnSecAuthAction(0, 'Top_List::', '::', ACCESS_OVERVIEW)) {
	echo _BADAUTHKEY;
	include('footer.php');
	return;
}

$top = pnVarPrepForDisplay(pnConfigGetVar('top'));
$sitename = pnVarPrepForDisplay(pnConfigGetVar('sitename'));

OpenTable();
echo '<h1>'._TOPWELCOME.' '.$sitename.'</h1>';
CloseTable();
echo "\n";
OpenTable();

if (pnModAvailable('News')) {
	/**
	 * Top 10 read stories
	 */
	$column = &$pntable['stories_column'];
	$myquery = "SELECT $column[sid], $column[title], $column[time], $column[counter]
				FROM $pntable[stories] ".$queryalang."
				ORDER BY $column[counter] DESC";
	$result =& $dbconn->SelectLimit($myquery,$top);

	if (!$result->EOF) {
		echo '<h2>'.$top.' '._READSTORIES.'</h2>'."\n";
		$lugar=1;
		while(list($sid, $title, $time, $counter) = $result->fields) {
			if($counter>0) {
				if (empty($title)) {
					$title = '- '._NOTITLE.' -';
				}
				echo "&nbsp;$lugar: <a href=\"index.php?name=News&amp;file=article&amp;sid=$sid\">" . pnVarPrepForDisplay(pnVarCensor($title)) . "</a> - ($counter "._READS.")<br />\n";
				$lugar++;
			}
			$result->MoveNext();
		}
		echo '<br />'."\n";
	}
	$result->Close();
}

if (pnModAvailable('Comments')) {
	/**
	 * Top 10 commented stories
	 */
	$column = &$pntable['stories_column'];
	$myquery = "SELECT $column[sid], $column[title], $column[comments]
				FROM $pntable[stories] ".$queryalang."
				ORDER BY $column[comments] DESC";
	$result =& $dbconn->SelectLimit($myquery,$top);

	if (!$result->EOF) {
		echo '<h2>'.$top.' '._COMMENTEDSTORIES.'</h2>'."\n";
		$lugar=1;
		while(list($sid, $title, $comments) = $result->fields) {
			if($comments>0) {
				if (empty($title)) {
				   $title = '- '._NOTITLE.' -';
				}
				echo "&nbsp;$lugar: <a href=\"index.php?name=News&amp;file=article&amp;sid=$sid\">" . pnVarPrepForDisplay(pnVarCensor($title)) . "</a> - (".pnVarPrepForDisplay($comments)." "._COMMENTS.")<br />\n";
				$lugar++;
			}
			$result->MoveNext();
		}
		echo '<br />'."\n";
	}
	$result->Close();
}

if (pnModAvailable('News')) {
	/**
	 * Top 10 categories
	 */
	$column = &$pntable['stories_cat_column'];
	$myquery = "SELECT $column[catid], $column[title], $column[counter]
				FROM $pntable[stories_cat]
				ORDER BY $column[counter] DESC";
	$result =& $dbconn->SelectLimit($myquery,$top);

	if (!$result->EOF) {
		echo '<h2>'.$top.' '._ACTIVECAT.'</h2>'."\n";
		$lugar=1;
		while(list($catid, $title, $counter) = $result->fields) {
			if($counter>0) {
				echo "&nbsp;$lugar: <a href=\"index.php?name=News&amp;catid=$catid\">" . pnVarPrepForDisplay($title) . "</a> - (".pnVarPrepForDisplay($counter)." "._HITS.")<br />\n";
				$lugar++;
			}
			$result->MoveNext();
		}
		echo '<br />'."\n";
	}
	$result->Close();
}

if (pnModAvailable('Sections')) {
	/**
	 * Top 10 articles in special sections
	 */
	$column = &$pntable['seccont_column'];
	$myquery = "SELECT $column[artid], $column[secid], $column[title], $column[content], $column[counter]
				FROM $pntable[seccont] ".$queryslang."
				ORDER BY $column[counter] DESC";
	$result =& $dbconn->SelectLimit($myquery,$top);

	if (!$result->EOF) {
		echo '<h2>'.$top.' '._READSECTION.'</h2>'."\n";
		$lugar=1;
		while(list($artid, $secid, $title, $content, $counter) = $result->fields) {
			echo "&nbsp;$lugar: <a href=\"index.php?name=Sections&amp;req=viewarticle&amp;artid=$artid\">" . pnVarPrepForDisplay($title) . "</a> - (".pnVarPrepForDisplay($counter)." "._READS.")<br />\n";
			$lugar++;
			$result->MoveNext();
		}
		echo "<br />\n";
	}
	$result->Close();
}

if (pnModAvailable('News')) {
	/**
	 * Top 10 users submitters
	 */
	$column = &$pntable['users_column'];
	$myquery = "SELECT $column[uname], $column[counter]
				FROM $pntable[users]
				WHERE $column[counter] > 0
				ORDER BY $column[counter] DESC";
	$result =& $dbconn->SelectLimit($myquery,$top);

	if (!$result->EOF) {
		echo '<h2>'.$top.' '._NEWSSUBMITTERS.'</h2>'."\n";
		$lugar=1;
		while(list($uname, $counter) = $result->fields) {
			if($counter>0) {
				echo "&nbsp;$lugar: <a href=\"user.php?op=userinfo&amp;uname=$uname\">$uname</a> - (".pnVarPrepForDisplay($counter)." "._NEWSSENT.")<br />\n";
				$lugar++;
			}
			$result->MoveNext();
		}
		echo '<br />'."\n";
	}
	$result->Close();
}

if (pnModAvailable('Polls')) {
	/**
	 * Top 10 Polls
	 */
	$column = &$pntable['poll_desc_column'];
	$myquery = "SELECT $column[pollid], $column[polltitle], $column[voters]
				FROM $pntable[poll_desc] ".$queryplang."
				ORDER BY $column[voters] DESC";
	$result =& $dbconn->SelectLimit($myquery,$top);

	if (!$result->EOF) {
		echo '<h2>'.$top.' '._VOTEDPOLLS.'</h2>'."\n";
		$lugar=1;

		while(list($pollID, $pollTitle, $voters) = $result->fields) {
			if(empty($pollTitle)) {
				$pollTitle = '';
			}
			$column = &$pntable['poll_data_column'];
			$result2 =& $dbconn->Execute("SELECT SUM($column[optioncount]) AS sum FROM $pntable[poll_data] WHERE $column[pollid]='".pnVarPrepForStore($pollID)."'");
			list($sum) = $result2->fields;
			if((int)$sum>0) {
				echo "&nbsp;$lugar: <a href=\"index.php?name=Polls&amp;pollID=$pollID\">" . pnVarPrepForDisplay($pollTitle) . "</a> - (".pnVarPrepForDisplay($voters)." "._TOPLISTVOTES.")<br />\n";
				$lugar++;
			}
			$result->MoveNext();
		}
		echo '<br />'."\n";
	}
	$result->Close();
}

if (pnModAvailable('News')) {
	/**
	 * Top 10 authors
	 */
	$column = &$pntable['stories_column'];
	$column1 = &$pntable['users_column'];
	$myquery ="SELECT $column1[uname], count(*) AS num
									FROM $pntable[stories], $pntable[users]
									WHERE  $column[aid] = '".pnVarPrepForStore($column1['uid'])."'
									GROUP BY $column1[uname]
									ORDER BY num DESC";

	$result = $dbconn->SelectLimit($myquery,$top);

	if (!$result->EOF) {
		echo '<h2>'.$top.' '._MOSTACTIVEAUTHORS.'</h2>'."\n";
		$lugar=1;
		while(list($aid, $counter) = $result->fields) {
			if($counter>0) {
				echo "&nbsp;$lugar:"
				 ."<a href=\"index.php?name=Search&amp;action=search&amp;overview=1&amp;active_stories=1&amp;stories_author=$aid\">$aid</a>"
				 .' - ('.pnVarPrepForDisplay($counter).' '._NEWSPUBLISHED.')<br />'."\n";
				$lugar++;
			}
		$result->MoveNext();
		}
		echo '<br />'."\n";
	}
	$result->Close();
}

if (pnModAvailable('Reviews')) {
	/**
	 * Top 10 reviews
	 */
	$column = &$pntable['reviews_column'];
	$myquery = "SELECT $column[id], $column[title], $column[hits]
				FROM $pntable[reviews] ".$queryrlang."
				ORDER BY $column[hits] DESC";
	$result =& $dbconn->SelectLimit($myquery,$top);

	if (!$result->EOF) {
		echo '<h2>'.$top.' '._READREVIEWS.'</h2>'."\n";
		$lugar=1;
		while(list($id, $title, $hits) = $result->fields) {
			if($hits>0) {
			   if(empty($title)) {
					  $title = '- '._NOTITLE.' -';
			   }
			   echo "&nbsp;$lugar: <a href=\"index.php?name=Reviews&amp;req=showcontent&amp;id=$id\">" . pnVarPrepForDisplay(pnVarCensor($title)) . "</a> - (".pnVarPrepForDisplay($hits)." "._READS.")<br />\n";
			   $lugar++;
		   }
			$result->MoveNext();
		}
		echo '<br />'."\n";
	}
	$result->Close();
}

if (pnModAvailable('Downloads')) {
	/**
	 * Top 10 downloads
	 */
	$column = &$pntable['downloads_downloads_column'];
	$myquery = "SELECT $column[lid], $column[cid], $column[title], $column[hits]
				FROM $pntable[downloads_downloads]
				ORDER BY $column[hits] DESC";
	$result =& $dbconn->SelectLimit($myquery,$top);
	
	if (!$result->EOF) {
		echo '<h2>'.$top.' '._DOWNLOADEDFILES.'</h2>'."\n";
		$lugar=1;
		while(list($lid, $cid, $title, $hits) = $result->fields) {
			if($hits>0) {
				$column = &$pntable['downloads_categories_column'];
				$res =& $dbconn->Execute("SELECT $column[title]
									   FROM $pntable[downloads_categories]
									   WHERE $column[cid]='".pnVarPrepForStore($cid)."'");
				list($ctitle) = $res->fields;
				$res->Close();
				$utitle = ereg_replace(' ', '_', $title);

				if(empty($title)) {
					$title = '- '._NOTITLE.' -';
				}
				echo "&nbsp;$lugar: <a href=\"index.php?name=Downloads&amp;req=viewdownloaddetails&amp;lid=$lid\">" . pnVarPrepForDisplay($title) . "</a> ("._CATEGORY.": ".pnVarPrepForDisplay($ctitle).") - (".pnVarPrepForDisplay($hits)." "._DOWNLOADS.")<br />\n";
				$lugar++;
			}
			$result->MoveNext();
		}
		echo "\n\n";
	}
	$result->Close();
}

CloseTable();

include 'footer.php';

?>