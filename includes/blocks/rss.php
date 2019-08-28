<?php
// File: $Id: rss.php 15630 2005-02-04 06:35:42Z jorg $
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
// Original Author of file: Patrick Kellum
// Purpose of file: Retrive and display RSS feeds from other websites
// ----------------------------------------------------------------------

if (strpos($_SERVER['PHP_SELF'], 'rss.php')) {
	die ("You can't access this file directly...");
}

$blocks_modules['rss'] = array(
    'func_display' => 'blocks_rss_block',
    'func_update' => 'blocks_rss_update',
    'func_edit' => 'blocks_rss_select',
    'func_add' => 'blocks_rss_add',
    'text_type' => 'RSS',
    'text_type_long' => 'RSS Newsfeed',
    'allow_multiple' => true,
    'form_content' => false,
    'form_refresh' => true,
    'show_preview' => true
);

// Security
pnSecAddSchema('RSSblock::', 'Block title::');

function blocks_rss_block($row) {
    if (!pnSecAuthAction(0, 'RSSblock::', "$row[title]::", ACCESS_READ)) {
        return;
    }

    $row = blocks_rss_refresh($row);
    blocks_rss_display($row);
}

function blocks_rss_display($row)
{
    $args = pnBlockVarsFromContent($row['content']);
    if (!empty($args['headlines'])) {
        $row['content'] = $args['headlines'];
    } else {
        $row['content'] = '';
    }

    // Ugly ugly
    $row['content'] = preg_replace('/_RSSREADMORE/', _RSSREADMORE, $row['content']);
    return themesideblock($row);
}

function blocks_rss_add($row)
{
    $row = blocks_rss_refresh($row, 1);
    return $row;
}

function blocks_rss_update($row) {
    $dbconn =& pnDBGetConn(true);

    list($args['rssurl'],
         $args['maxitems'],
         $args['showimage'],
         $args['showsearch'],
         $args['showdescriptions'],
         $args['altstyle']) = pnVarCleanFromInput('rssurl',
                                                  'maxitems',
                                                  'showimage',
                                                  'showsearch',
                                                  'showdescriptions',
                                                  'altstyle');
    // Remove old URL if there
    unset($row['url']);

    // Defaults
    if (!isset($args['rssurl'])) {
        $args['rssurl'] = '';
    }
    if (!isset($args['maxitems'])) {
        $args['maxitems'] = 5;
    }
    if (!isset($args['showdescriptions'])) {
        $args['showdescriptions'] = 0;
    }
    if (!isset($args['altstyle'])) {
        $args['altstyle'] = 0;
    }
    if (!isset($args['showimage'])) {
        $args['showimage'] = 0;
    }
    if (!isset($args['showsearch'])) {
        $args['showsearch'] = 0;
    }
    $row['content'] = pnBlockVarsToContent($args);

    // Refresh data
    $row = blocks_rss_refresh($row, 1);

    return $row;
}

function blocks_rss_select($row)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $output = '';

    $args = pnBlockVarsFromContent($row['content']);

    // Migrate $row['rssurl'] to content if present
    if (!empty($row['url'])) {
        $args['rssurl'] = $row['url'];
        unset($row['url']);
    }

    // Defaults
    if (!isset($args['rssurl'])) {
        $args['rssurl'] = '';
    }
    if (!isset($args['showdescriptions'])) {
        $args['showdescriptions'] = 0;
    }
    if (!isset($args['altstyle'])) {
        $args['altstyle'] = 0;
    }
    if (!isset($args['maxitems'])) {
        $args['maxitems'] = 5;
    }
    if (!isset($args['showimage'])) {
        $args['showimage'] = 1;
    }
    if (!isset($args['showsearch'])) {
        $args['showsearch'] = 0;
    }

    // RSS URL
    $output .= '<tr><td>RSS File URL:</td><td>'
              ."<input type=\"text\" name=\"rssurl\" size=\"64\" maxlength=\"128\" value=\"$args[rssurl]\">";

    // List of RSS URLs
    $output .= '<a target="_blank" href="http://www.syndic8.com/">' . _BLOCKSRSSLISTS . '<a>';
    $output .= "</td></tr>";


    // Number of items
    $output .= '<tr><td>' . _RSSMAXITEMS . ':</td>';
    $output .= '<td><input type="text" name="maxitems" size="4" maxlength="4" value="' . $args['maxitems']. '"></td></tr>';

    // Show image
    $output .= '<tr><td>' . _RSSSHOWIMAGE . ':</td>';
    $output .= '<td><input type="checkbox" name="showimage" value="1"';
    if ($args['showimage'] == 1) {
        $output .= ' checked';
    }
    $output .= '></td></tr>';

    // Show search
    $output .= '<tr><td>' . _RSSSHOWSEARCH . ':</td>';
    $output .= '<td><input type="checkbox" name="showsearch" value="1"';
    if ($args['showsearch'] == 1) {
        $output .= ' checked';
    }
    $output .= '></td></tr>';

    // Show descriptions
    $output .= '<tr><td>' . _RSSSHOWDESCRIPTIONS . ':</td>';
    $output .= '<td><input type="checkbox" name="showdescriptions" value="1"';
    if ($args['showdescriptions'] == 1) {
        $output .= ' checked';
    }
    $output .= '></td></tr>';

    // Use old-style
    $output .= '<tr><td>' . _RSSALTSTYLE . ':</td>';
    $output .= '<td><input type="checkbox" name="altstyle" value="1"';
    if ($args['altstyle'] == 1) {
        $output .= ' checked';
    }
    $output .= '></td></tr>';

    return $output;
}

/*
 * Parse RSS File (as array of lines)
 * A rather un-optimized function to parse an rss file (sent as an array)
 * I'll have to clean it up some later.
 *
 * If all goes well, the resulting array should be compatable with the results from
 * the built-in xml_parse_into_struct() function.  Except for some differences in
 * parsing of html entities.
 */

function rss_parse_array($f)
{
    $struct = '';
    foreach ($f as $line)
    {
// Fix for CDATA tag not removed when fetching RSS -- bharvey42 6/9/03
		$line = preg_replace('#(<\!\[CDATA\[)(.*)(\]\]>) #siU', '\2', $line);   
        $parse = '';
        // get our positions
        $sp = strpos($line,'>');
        $ep = strrpos($line,'<');
        $ep2 = strrpos($line,'>');
        // split into first tag, last tag, and content
        $first_tag = substr($line,1,($sp - 1));
        $last_tag = substr($line,($ep + 1),(($ep2 - $ep) - 1));
        $content = substr($line,($sp + 1),(($ep - 1) - $sp));
        if (!$line)
        { // blank line
            continue;
        }
        if ($first_tag == $last_tag)
        { // no content, single tag line
            if ($first_tag[0] == '/')
            {
                $parse['type'] = 'close';
                if ($temp_str = strstr($first_tag, ':'))
                {
                    $first_tag = $temp_str;
                }
                $parse['tag'] = strtolower(substr($first_tag,1,(strlen($first_tag) - 1)));
            } else {
                $parse['type'] = 'open';
                $first_tag = preg_replace('/^\S*:/', '', $first_tag);
                $first_tag = preg_replace('/\s.*/', '', $first_tag);
                $parse['tag'] = strtolower($first_tag);
            }
            $parse['value'] = '';
        } else { // complete
            $parse['type'] = 'complete';
            $parse['tag'] = strtolower($first_tag);
            if ($content) {
                // Content might have HTML entities, turn it into
                // normal text and then parse it through our own
                // system
                $trans = get_html_translation_table (HTML_ENTITIES);
                $trans = array_flip ($trans);

                // Need to do this twice as some systems pass us quotes like
                // &amp;quot; - ug
                $content = strtr($content, $trans);
                $content = strtr($content, $trans);
                $content = pnVarPrepHTMLDisplay($content);
            }
            $parse['value'] = $content;
        }
        $struct[] = $parse;
    }
    return $struct;
}


function blocks_rss_refresh($row, $forceupdate=0) {

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Get arguments
    $args = pnBlockVarsFromContent($row['content']);

    // Check for URL
    if (empty($args['rssurl'])) {
        return $row;
    }

    $past = time() - $row['refresh'];
    if ((!$forceupdate) && ($row['unix_update'] > $past)) {
        return $row;
    }

    $rss = parse_url($args['rssurl']);
    if (!isset($rss['port'])) {
        $rss['port'] = 80;
    }

    if (!isset($rss['query'])) {
        $rss['query'] = '';
    }

    // retrive the rss file
    $fp = @fsockopen($rss['host'], $rss['port'], $errno, $errstr, 10);
    if(!$fp) {
        $next_try = time() + 600;
        $column = &$pntable['blocks_column'];
        $sql = "UPDATE $pntable[blocks]
                SET $column[last_update]=". pnVarPrepForStore($next_try) . "
                WHERE $column[bid]=" . pnVarPrepForStore($row['bid']);

        $result =& $dbconn->Execute($sql);

        $row['title'] .= ' *';
        return $row;
    }

    fputs($fp, 'GET ' . $rss['path'] . '?' . $rss['query'] . " HTTP/1.0\r\n");
// cocomp 2002/07/13 Added Referer & User-Agent as some sites won't give RSS
// feed otherwise
	fputs($fp, 'HOST: ' . $rss['host'] . ":" . $rss['port'] . "\r\n"); 
    fputs($fp, 'Referer: ' . pnGetBaseURL() . "\r\n");
    fputs($fp, 'User-Agent: ' . pnConfigGetVar('Version_ID') . ': ' . pnConfigGetVar('Version_Sub') . ': ' .pnConfigGetVar('Version_Num') . "\r\n\r\n");
    $rss_file = '';
    $start_time = time();

    while(!feof($fp)) {
        $line = fgets($fp, 4096);
        if(empty($go)) {
            if($line[0] == '<') {
                $go = true;
			}
		    /*
			 * Fix on SF-bug #566489
			 */
   		    if (preg_match("/[\n]?[\r]{1}[^\n]{1}/", $line)) {
                $line = preg_replace("/([\n]?[\r]{1})([^\n]{1})/", "\n\\2", $line);
	    		$lines = explode("\n", $line);
				$first = true;
		    	foreach ($lines as $line) {
				    if ($first) {
					    $first = !$first;
				    } else {
			            $rss_file[] = trim($line);
				    }
			    }
            }
            /*
			 * End of fix
			 */
        } else {
		    /*
			 * Fix on SF-bug #566489
			 */
		    if (preg_match("/[\n]?[\r]{1}[^\n]{1}/", $line)) {
                $line = preg_replace("/([\n]?[\r]{1})([^\n]{1})/", "\n\\2", $line);
				$lines = explode("\r\n", $line);
				foreach ($lines as $line) {
				    $rss_file[] = trim($line);
				}
            } else {
                $rss_file[] = trim($line);
            }
            /*
			 * End of fix
			 */
        }
        if ((time() - $start_time) == 5) { // if the source server is too slow, we give up. 5 seconds is more then enough time
            fputs($fp, "Connection: close\r\n\r\n");
            fclose($fp);
            $column = &$pntable['blocks_column'];
            $sql = "UPDATE $pntable[blocks]
                    SET $column[last_update]=0
                    WHERE $column[bid]=" . pnVarPrepForStore($row['bid']);
            $result =& $dbconn->Execute($sql);

            return $row;
        }
    }
    fputs($fp, "Connection: close\r\n\r\n");
    fclose($fp);

    $struct = rss_parse_array($rss_file);

    // parse the file
    $channel_data = '';
    $image_data = '';
    $item_data = array();
    $search_data = '';
    $total_items = 0;
    $cur_block = '';
    foreach($struct as $v) {
        if(!is_array($v)) {
            continue;
        }
        if($v['type'] == 'open') {
            switch($v['tag']) {
                case 'channel' :
                    $cur_block = 'channel';
                    break;
                case 'image' :
                    $cur_block = 'image';
                    break;
                case 'item' :
                    $cur_block = 'item';
                    break;
                case 'textinput' :
                    $cur_block = 'textinput';
                    break;
            }
        } elseif($v['type'] == 'close') {
            switch($v['tag']) {
                case 'channel' :
                    $cur_block = '';
                    break;
                case 'image' :
                    $cur_block = '';
                    break;
                case 'item' :
                    $cur_block = '';
                    $total_items++;
                    break;
                case 'textinput' :
                    $cur_block = '';
                    break;
            }
        } elseif($v['type'] == 'complete') {
            $tag = $v['tag'];
            switch($cur_block) {
                case 'channel' :
                    $channel_data[$tag] = $v['value'];
                    break;
                case 'image' :
                    $image_data[$tag] = $v['value'];
                    break;
                case 'item' :
                    $item_data[$total_items][$tag] = $v['value'];
                    break;
                case 'textinput' :
                    $search_data[$tag] = $v['value'];
                    break;
            }
        }
    }

    // start generating content
    $content = '';
    // image & link
    if (!empty($args['showimage'])) {
// cocomp 2002/07/13 - prevent E_ALL errors check for $image_data
    if (isset($image_data) && is_array($image_data)) {
        if (isset($image_data['url']) && ($image_data['url'] != 'http://yoursite.com/images/logo.gif')) {
            if (!$image_data['link']) {
                $image_data['link'] = $channel_data['link'];
            }
            if (!$image_data['title']) {
                $image_data['title'] = $channel_data['title'];
            }
            if(!isset($image_data['description'])) {
                if($channel_data['description']) {
                    $image_data['description'] = $channel_data['description'];
                }
                else {
                    $image_data['description'] = 'No description provided...';
                }
            }
            if(!isset($image_data['width'])) {
                $image_data['width'] = 88;
            }
            if(!isset($image_data['height'])) {
                $image_data['height'] = 31;
            }
            if ($args['altstyle']) {
                $content .= "<a href=\"$image_data[link]\" target=\"_blank\" title=\"$image_data[description]\">\n"
                           ."<img src=\"$image_data[url]\" border=\"0\" alt=\"$image_data[title]\" width=\"$image_data[width]\" height=\"$image_data[height]\"></a>\n"
                           ."<br />\n";
            } else {
                $content .= "<div align=\"center\" style=\"text-align:center\"><a href=\"$image_data[link]\" target=\"_blank\" title=\"$image_data[description]\">\n"
                           ."<img src=\"$image_data[url]\" border=\"0\" alt=\"$image_data[title]\" width=\"$image_data[width]\" height=\"$image_data[height]\"></a>\n"
                           ."</div>";
            }
        }
    }
    }

    // pub date
    if(isset($channel_data['pubDate'])) {
        $content .= "<div align=\"center\" style=\"text-align:center\">\n"
            ."<strong>($channel_data[pubDate])</strong></div>";
    }

    // items
    if ($total_items > $args['maxitems']) { // we don't want a bunch of empty item spaces
        $total_items = $args['maxitems'];
    }

    for($i = 0; $i < $total_items; $i++) {
        if($i) {
            if ($args['altstyle']) {
                $content .= "\n";
            } else {
                $content .= "<hr />\n";
            }
        }

        if(empty($item_data[$i]['title'])) {
            $item_data[$i]['title'] = '<em>[no title]</em>';
        }
// cocomp 2002/07/13 prevent E_ALL errors check for $item_data[$i]['link']
    if (isset($item_data[$i]['link'])) {
        if ($args['altstyle']) {
            $content .= '<strong><big>&middot;</big></strong>&nbsp;<a href="' . $item_data[$i]['link'] . '" title="' . $item_data[$i]['title'] . '">' . $item_data[$i]['title'] . '</a><br />';
        } else {
            $content .= '<a href="' . $item_data[$i]['link'] . '" title="' . $item_data[$i]['title'] . '">' . $item_data[$i]['title'] . '</a><br />';
        }
    }
        if (!empty($args['showdescriptions']) && isset($item_data[$i]['description'])) {
            $content .= '<em>' . $item_data[$i]['description'] . '</em><br />';
        }
    }

    // search
    if (!empty($args['showsearch'])) {
        if((isset($search_data['link'])) && (isset($search_data['name'])) && (isset($search_data['title']))) {
            if ($args['altstyle']) {
                $content .= '<br />';
            } else {
                $content .= '<hr />';
            }
            $content .= "<div style=\"text-align:center\"><form method=\"get\" action=\"$search_data[link]\">\n";
            if ($search_data['description']) {
                $content .= htmlspecialchars($search_data['description']) . '<br />';
            }
            $content .= "<input type=\"text\" name=\"$search_data[name]\" size=\"15\" /><br /><input type=\"submit\" value=\"$search_data[title]\" /></div></form></div>\n";
        }
        // copyright
        if(isset($channel_data['copyright'])) {
            $content .= "$channel_data[copyright]\n";
        }
        // done with rdf file
        if ($args['altstyle']) {
            $content .= "<br /><a href=\"$channel_data[link]\"><strong>_RSSREADMORE</strong></a>\n";
        } else {
            $content .= "<div align=\"right\" style=\"text-align:right\"><a href=\"$channel_data[link]\"><strong>_RSSREADMORE</strong></a></div>\n";
        }
        $content = "$content\n";
    }

    $args['headlines'] = $content;
    $row['content'] = pnBlockVarsToContent($args);

    $column = &$pntable['blocks_column'];
// cocomp 2002/07/13 cross db compatibility - changed now() to DBTimestamp
    $sql = "UPDATE $pntable[blocks]
            SET $column[content]='" . pnVarPrepForStore($row['content']) . "',
                $column[last_update]=" . $dbconn->DBTimestamp(time()) . "
            WHERE $column[bid]=" . pnVarPrepForStore($row['bid']);
    $result =& $dbconn->Execute($sql);

    if($dbconn->ErrorNo() != 0) {
        $row['title'] .= ' *';
    }

    return $row;
}
?>