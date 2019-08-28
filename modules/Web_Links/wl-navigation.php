<?php
// File: $Id: wl-navigation.php 19178 2006-06-01 13:00:27Z markwest $
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
// Purpose of file:
// ----------------------------------------------------------------------
// 11-30-2001:ahumphr - created file as part of modularistation

/**
 * index
 * Display the main links categories
 */
function index()
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    include('header.php');
    $mainlink = 0;

    if (!pnSecAuthAction(0, 'Web Links::', '::', ACCESS_READ)) {
        echo _WEBLINKSNOAUTH;
        include 'footer.php';
        exit;
    }

    $column = &$pntable['links_categories_column'];
    $result =& $dbconn->Execute("select $column[cat_id], $column[title], $column[cdescription]
                              from $pntable[links_categories]
                              WHERE $column[parent_id]=0
                              ORDER BY $column[title]");
    $numcats = $result->PO_RecordCount();
    if ($numcats == 0) {
        echo _LINKSNOCATS;
        include 'footer.php';
    } else {
        $GLOBALS['info']['title'] = _LINKPAGETITLE;
        menu($mainlink);
        OpenTable();

        echo '<h2>'._LINKSMAINCAT.'</h2>';
        echo "<table border=\"0\" cellspacing=\"10\" cellpadding=\"10\" width=\"98%\" summary=\""._LINKSMAINCAT."\">";

        $count = 0;
        while(list($cat_id, $title, $cdescription) = $result->fields)
        {
            $result->MoveNext();
    		/* Hide this web link if have no access to it */
            if (!pnSecAuthAction(0, 'Web Links::Category', "$title::$cat_id", ACCESS_READ)) {
    			continue;
    		}
	        if ($count == 0) {
				// we need to start a row
        	    echo "<tr>";
        	}
            $count++;
            $cnumrows = CountSubLinks($cat_id);

            echo "<td valign=\"top\" style=\"width:50%\"><h3>"
				."<img src=\"modules/Web_Links/images/folder.gif\" height=\"13\" width=\"15\" alt=\"\" />&nbsp;&nbsp;"
				."<a href=\"".$GLOBALS['modurl']."&amp;req=viewlink&amp;cid=$cat_id\">".pnVarPrepForDisplay($title)."</a>"
				." ($cnumrows)";
            categorynewlinkgraphic($cat_id);
            echo '</h3>';

            if ($cdescription) {
                echo pnVarPrepHTMLDisplay($cdescription).'<br />';
            }

            $column = &$pntable['links_categories_column'];
            $result2 =& $dbconn->Execute("SELECT $column[cat_id], $column[title] FROM $pntable[links_categories] WHERE $column[parent_id]='".pnVarPrepForStore($cat_id)."' ORDER BY $column[title]");

            while(list($scat_id, $stitle) = $result2->fields) {

                $result2->MoveNext();
	            if (!pnSecAuthAction(0, 'Web Links::Category', "$stitle::$scat_id", ACCESS_READ)) {
    				continue;
    			}
                echo "&nbsp;&nbsp;&nbsp;"
					."<img src=\"modules/Web_Links/images/folder.gif\" height=\"13\" width=\"15\" alt=\"\" />&nbsp;&nbsp;"
					."<a href=\"".$GLOBALS['modurl']."&amp;req=viewlink&amp;cid=$scat_id\">".pnVarPrepForDisplay($stitle)."</a>";
                subcategorynewlinkgraphic($scat_id);
    	    	echo '<br />';
            }

            if ($count==1) {
				// next table cell
            	echo "</td>";
            }

            if ($count==2) {
				// two entries, start the next table row
                echo "</td></tr>";
                $count = 0;
            }
        } //While
		
        if ($count == 1) {
			// we need 
            echo "<td>&nbsp;</td></tr>";
        }
		
		echo "</table>";

         $result =& $dbconn->Execute("SELECT COUNT(*) FROM $pntable[links_links]");
        list($numrows) = $result->fields;

        $result =& $dbconn->Execute("SELECT COUNT(*) FROM $pntable[links_categories]");
        list($catnum) = $result->fields;

        echo "<br /><div style=\"text-align:center\">"._THEREARE." <strong>$numrows</strong> "._LINKS." "._AND." <strong>$catnum</strong> "._CATEGORIES." "._INDB."</div>";
        CloseTable();
        include('footer.php');
    }
}

/**
 * menu
 * builds the standard navigation menu
 * @param mainlink  integer switch. 1 means show _LINKSMAIN, 0 not.
 */
function menu($mainlink) {
    //$query = pnVarCleanFromInput('query');

    OpenTable();
	//patch [ #471 ] Web Module Fix Wil Schultz (xwil)
    echo '<div style="text-align:center"><h1>'._LINKPAGETITLE."</h1>";
    //echo "<div style=\"text-align:center\"><a  href=\"".$GLOBALS['modurl']."\">"._LINKPAGETITLE."</a><br />";
    echo "<form action=\"".$GLOBALS['modurl']."&amp;req=search\" method=\"post\" id=\"".str_replace(' ','_',_LINKS)."\"><div>"
		."<label for=\"query\">"._LINKS."</label>"
		." <input type=\"text\" size=\"25\" name=\"query\" id=\"query\" tabindex=\"0\" 
										value=\""._SEARCH_KEYWORDS."\" 
										onblur=\"if(this.value=='')this.value='"._SEARCH_KEYWORDS."';\"
										onfocus=\"if(this.value=='"._SEARCH_KEYWORDS."')this.value='';\" /> "
        ." <input type=\"submit\" value=\""._SEARCH."\" />"
		.'</div>'
		."</form>";
    echo "[ ";
    if ($mainlink>0) {
		echo "<a  href=\"".$GLOBALS['modurl']."\">"._LINKSMAIN."</a> | ";
    }
    if (pnSecAuthAction(0, 'Web Links::Category', '::', ACCESS_COMMENT) || pnConfigGetVar('links_anonaddlinklock')) {
		echo "<a href=\"".$GLOBALS['modurl']."&amp;req=AddLink\">"._ADDLINK."</a> | ";
    }
    echo " <a href=\"".$GLOBALS['modurl']."&amp;req=NewLinks\">"._NEW."</a>"
		." | <a href=\"".$GLOBALS['modurl']."&amp;req=MostPopular\">"._POPULAR."</a>"
    	." | <a href=\"".$GLOBALS['modurl']."&amp;req=TopRated\">"._TOPRATED."</a>"
    	." | <a href=\"".$GLOBALS['modurl']."&amp;req=RandomLink\">"._RANDOM."</a> ]"
    	.'</div>';
    CloseTable();
}

?>