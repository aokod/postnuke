<?php 
// $Id: menu.php 15630 2005-02-04 06:35:42Z jorg $
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
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Francisco Burzi
// Purpose of file:
// ----------------------------------------------------------------------
// ChangeLog
// November 10th: Allow to place multiple links underneath an image
// (Commentented out for now, uncomment it after the
// feature-freeze is off).
// ----------------------------------------------------------------------
class user_menu_object {
    var $title_file;
    var $title_text;
    var $help_file;
    var $help_text;
    var $detail_menu = true;
    var $graphic_menu = true;
    var $nb_column = 6;
    var $options = array();

    function user_menu_object($url, $text)
    {
        $this->title_file = $url;
        $this->title_text = $text;
    } 

    function set_help($url, $text)
    {
        if (!ereg('/', $url)) $url = 'manual/' . $url;
        $hlpfile = $url;
        $this->help_file = $url;
        $this->help_text = $text;
    } 

    function set_detail($top)
    {
        $this->detail_menu = $top;
    } 

    function set_graphic($top)
    {
        $this->graphic_menu = $top;
    } 

    function add_option($url, $text, $image = '')
    {
        $current = count($this->options);
        $this->options[$current]['url'] = $url;
        $this->options[$current]['text'] = $text;
        $this->options[$current]['image'] = $image;
    } 

    function draw_option_cell($url, $text, $image = '')
    { 
        // modified by Chris van de Steeg to have multiple links under
        // an image
        echo '<td align="center" valign="top">';
        if ($url != '' && !is_array($url)) echo '<a href="' . $url . '">';
        if ($image == '') {
            if (is_array($url)) {
                for ($i = 0;$i < count($url);$i++)
                echo "<a href=\"" . $url[$i] . "\">" . $text[$i] . "</a><br />";
            } else
                echo $text;
        } else
            echo '<img src="' . $image . '" alt="' . $text . '" />';
        if ($url != '' && !is_array($url)) echo '</a>';
        echo '</td>' . "\n"; 
        // END modification
    } 

    function draw_options_graphic()
    {
        $nb_total = count($this->options);
        $i = 0;
        while ($i < $nb_total) {
            $j = 0;
            $t = array();
            echo '<tr>' . "\n";
            while (($j < $this->nb_column)and($i < $nb_total)) {
                $this->draw_option_cell($this->options[$i]['url'], $this->options[$i]['text'], $this->options[$i]['image']);
                $t[] = $this->options[$i];
                $i++;
                $j++;
            } 
            echo '</tr>' . "\n" . '<tr>' . "\n";
            for ($k = 0;$k < count($t);$k++) {
                $this->draw_option_cell($t[$k]['url'], $t[$k]['text']);
            } 
            echo '</tr>' . "\n";
        } 
    } 

    function draw_options()
    {
        $nb_total = count($this->options);
        $i = 0;
        while ($i < $nb_total) {
            echo '<tr>' . "\n";
            $j = 0;
            while (($j < $this->nb_column)and($i < $nb_total)) {
                $this->draw_option_cell($this->options[$i]['url'], $this->options[$i]['text']);
                $i++;
                $j++;
            } 
            echo '</tr>' . "\n";
        } 
    } 

    function draw_menu()
    {
        OpenTable(); 
        // echo  '<div style="text-align:center">'."\n";
        if ($this->title_file != '') echo '<h2><a href="' . $this->title_file . '">';
        echo $this->title_text;
        if ($this->title_file != '') echo '</a></h2>';
        echo "\n" . '<br />' . "\n";
        if (($this->detail_menu) or ($GLOBALS['module'] == 'oldway')) {
            if (isset($this->help_file)) {
                global $hlpfile;
                $hlpfile = $this->help_file;
                echo '[ <a href="javascript:openwindow(\'' . $hlpfile . '\')">' . $this->help_text . '</a> ]' . "\n" . '<br />' . "\n";
            } 
        } 
        if ($this->detail_menu) {
            echo '<br />' . "\n" . '<table border="0" width="100%" cellspacing="1">' . "\n";
            if ($this->graphic_menu) {
                $this->draw_options_graphic();
            } else {
                $this->draw_options();
            } 
            echo '</table>' . "\n";
        } 
        CloseTable();
    } 
} 

function user_menu_action($action, $parm1 = '', $parm2 = '', $parm3 = '')
{
    static $menu = array(),
    $current = -1; 
    // echo $action.'/'.$parm1.'/'.$parm2.'/'.$parm3.'<br />';
    if ($action == 'title') {
        if ($current >= 0) menu_detail(false);
        $current++;
        $menu[$current] = new user_menu_object($parm1, $parm2);
    } elseif ($action == 'help')
        $menu[$current]->set_help($parm1, $parm2);
    elseif ($action == 'graphic')
        $menu[$current]->set_graphic($parm1);
    elseif ($action == 'detail')
        $menu[$current]->set_detail($parm1);
    elseif ($action == 'option')
        $menu[$current]->add_option($parm1, $parm2, $parm3);
    elseif ($action == 'draw') {
        foreach ($menu as $i => $m)
        $m->draw_menu();
        $menu = array();
        $current = -1;
    } else
        die('fatal / admin/tools/menu.php');
} 

function user_menu_title($file, $text)
{
    user_menu_action('title', $file, $text);
} 
function user_menu_help($file, $text)
{
    user_menu_action('help', $file, $text);
} 
function user_menu_graphic($top)
{
    user_menu_action('graphic', $top);
} 
function user_menu_add_option($url, $text, $image)
{
    user_menu_action('option', $url, $text, $image);
} 
function user_menu_detail($top)
{
    user_menu_action('detail', $top);
} 
function user_menu_draw()
{
    user_menu_action('draw');
} 

?>