<?php
// File: $Id: fxp.php 16305 2005-06-06 20:30:10Z markwest $
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
// Modified by: Joan McGalliard
// Purpose of file: Display currency exchange rates
// ----------------------------------------------------------------------

if (strpos($_SERVER['PHP_SELF'], 'fxp.php')) {
	die ("You can't access this file directly...");
}

$blocks_modules['fxp'] = array (
    'func_display' => 'blocks_fxp_display',
    'func_add' => '',
    'func_update' => 'blocks_fxp_update',
    'func_edit' => 'blocks_fxp_edit',
    'text_type' => 'Currency',
    'text_type_long' => 'FXP Currency Exchange',
    'allow_multiple' => true,
    'form_content' => false,
    'form_refresh' => true,
    'show_preview' => true
);

pnSecAddSchema('fxpblock::', 'Block title::');

function blocks_fxp_display($row)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!pnSecAuthAction(0, 'fxpblock::', "$row[title]::", ACCESS_READ)) {
        return;
    }

    $blocktable = $pntable['blocks'];
    $blockscolumn = &$pntable['blocks_column'];
    $past = time() - $row['refresh'];
    if ($row['unix_update'] < $past) {
		$fx = new fx_rates();
        if (!$fx->allCurrencies() )  {
		    if(!isset($bid)) {
				$bid = '';
	    	}
            $content = addslashes(_FXPPROBLEM);
            $next_try = time() + 600;
            $result =& $dbconn->Execute("UPDATE $blocktable SET $blockscolumn[content]='$content',$blockscolumn[last_update]=FROM_UNIXTIME($next_try) WHERE $blockscolumn[bid]=".pnVarPrepForStore($bid)."");
            $row['title'] = "$row[title] *";
		    $row['content'] = "$row[content]\n\n\n<!--\n\n\n\n\n\n\n".ml_ftime(_DATESTRING,$row['unix_update'])."\n\n\n\n\n-->\n\n\n\n";
            return themesideblock($row);
        }
        // get quotes
		$row['content'] = "";
        $rates = explode("\n", trim($row['url']));
        foreach ($rates as $v) {
	       	$v=trim($v);
	       	if (!$v) {
		       	continue;
	       	}
	       	$temp = explode('|', $v);
			$rate=$fx->exchange($temp[0], $temp[1]);
	       	$row['content'] .= '<span class="pn-sub">&nbsp;&nbsp;&nbsp;1.00 '.$temp[0]." = $rate ".$temp[1]."</span><br />\n";
       	}
        //$sql_content = addslashes($row['content']);
        $sql = "UPDATE $blocktable SET $blockscolumn[content]='".pnVarPrepForStore($row['content'])."',$blockscolumn[last_update]=NOW() WHERE $blockscolumn[bid]=".pnVarPrepForStore($row['bid'])."";
		$dbconn->Execute($sql);
    }
    return themesideblock($row);
}

function blocks_fxp_update($vars)
{
    $vars['url'] = '';
    // edit quote
    $c = 0;

    if(count($vars['fxp_rname'])) {
        $delete = $vars[fxp_delete];
        $insert = $vars[fxp_insert];
        foreach ($vars[fxp_rname] as $v) {
            $c++;
            if (!$vars[fxp_delete][$c]) {
                $vars['url'] .= $vars[fxp_rname][$c].'|'.$vars[fxp_qname][$c]."\n";
            }
            // insert a blank link
            if ($vars[fxp_insert][$c]) {
                $vars['url'] .= "|\n";
            }
        }
    }
    // new quote
    if($vars['fxp_new_rname'] && $vars['fxp_new_qname']) {
        $vars['url'] .= "$vars[fxp_new_rname]|$vars[fxp_new_qname]\n";
    }
    // get quotes
    $vars['content'] = "";
    $fx = new fx_rates();
    $rates = explode("\n", trim($vars['url']));
    foreach ($rates as $v) {
	    $v=trim($v);
	    if (!$v) {
		    break;
	    }
	    $temp = explode('|', $v);
	    $rate=$fx->exchange($temp[0], $temp[1]);
	    $vars['content'] .= '<span class="pn-sub">&nbsp;&nbsp;&nbsp;1.0 '.$temp[0]." = $rate ".$temp[1]."</span><br />\n";
    }
    return $vars;
}

function blocks_fxp_edit($row)
{
    $fxp_port = 5011;
    global $pntheme;
    $fx= new fx_rates();
    if (!$fx->allCurrencies() )
    {
        return '<tr><td>'._FXP_ERROR.':</td><td>'
            .'Error Contacting FXP Server!'
            ."</td></tr>\n";
    }
    // currency code list
    $output = '<tr><td valign="top">'._CURRENCYCODES.':</td><td>'
        .'<table border="1"><tr>'
        ."<td align=\"center\">"._CODE."</td><td align=\"center\">"._RATE."</td>"
        ."<td align=\"center\">"._CODE."</td><td align=\"center\">"._RATE."</td>"
        ."<td align=\"center\">"._CODE."</td><td align=\"center\">"._RATE."</td>"
        ."<td align=\"center\">"._CODE."</td><td align=\"center\">"._RATE."</td>"
        .'</tr>'
        .'<tr>'
    ;
    $c = 1;
    foreach ($fx->allCurrencies() as $k=>$v)
    {
        if ($c > 4)
        {
            $output .= "</tr>\n<tr>";
            $c = 1;
        }
        $output .= "<td align=\"center\">$k</td><td align=\"center\">$v</td>\n";
        $c++;
    }
    $output .= '</tr></table></td></tr>';
    // build form
    $output .= '<tr><td valign="top">'._CURRENCYRATES.':</td><td>'
        	."<table border=\"1\"><tr><td align=\"center\">"._QUOTE
			."</td><td align=\"center\">"._BASE
			."</td><td align=\"center\">"._INSERT
			."</td><td align=\"center\">"._DELETE."</td></tr>";
    $c = 0;
    $rates = explode("\n", trim($row['url']));
    if ($rates[0]) {
        foreach ($rates as $v) {
            $c++;
            $temp = explode('|', $v);
            $output .= '<tr>';
            $output .= "<td valign=\"top\"><input type=\"text\" name=\"fxp_qname[$c]\" size=\"30\" value=\"$temp[1]\" /></td>";
            $output .= "<td valign=\"top\"><input type=\"text\" name=\"fxp_rname[$c]\" size=\"30\" value=\"$temp[0]\" /></td>";
            $output .= "<td valign=\"top\"><input type=\"checkbox\" name=\"fxp_insert[$c]\" value=\"1\" /></td>";
            $output .= "<td valign=\"top\"><input type=\"checkbox\" name=\"fxp_delete[$c]\" value=\"1\" /></td>";
            $output .= "</tr>\n";
        }
    }
    $output .= '<tr>';
    $output .= "<td valign=\"top\"><input type=\"text\" name=\"fxp_new_qname\" size=\"30\" /></td>";
    $output .= "<td valign=\"top\"><input type=\"text\" name=\"fxp_new_rname\" size=\"30\" /></td>";
    $output .= "<td valign=\"top\" colspan=\"2\">"._NEW."</td></tr>\n";
    $output .= '</table></td></tr>';
    return $output;
}

class fx_rates 
{
	var $source = 'http://www.ecb.int/stats/eurofxref/eurofxref-daily.xml';
	var $currencies = array();
   	var $date=null;
	function fx_rates() {
	       	$this->currencies['EUR']='1.0';
	       	$xml_parser = xml_parser_create();
			xml_set_object($xml_parser, $this);
	       	xml_set_element_handler($xml_parser, "startElement", "endElement");
	       	if (!($fp = fopen($this->source, "r"))) {
		       	$currencies=null;
		       	return;
	       	}

			while ($data = fread($fp, 4096)) {
		       	if (!xml_parse($xml_parser, $data, feof($fp))) {
					$currencies=null;
					return;
		       	}
	       	}
	       	xml_parser_free($xml_parser);
       	}

	function startElement($parser, $name, $attrs) {
		if ($name != 'CUBE') {
			return;
		}
		if (!empty($attrs['CURRENCY']) && !empty($attrs['RATE'])) { 
			$this->currencies[$attrs['CURRENCY']] = $attrs['RATE'];
			return;
		}
		if (!empty($attrs['TIME'])) {
			$date=strtotime($attrs['TIME']);
			return;
		}
	}

	function endElement($parser, $name) {
   	}

	// 1.0 of curr1 = ? of curr2?
	function exchange($curr1, $curr2, $amount=1.0) {
		if (empty($this->currencies[$curr1]) || empty($this->currencies[$curr2])) {
			return 0;
		}
		return number_format(($this->currencies[$curr2]*$amount)/ $this->currencies[$curr1], 2);
	}

	function allCurrencies() {
		return $this->currencies;
	}

}

?>