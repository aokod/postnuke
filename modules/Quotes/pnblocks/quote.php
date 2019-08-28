<?php
// File: $Id: quote.php 15630 2005-02-04 06:35:42Z jorg $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
// http://www.postnuke.com/
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
// Purpose of file: Display a random quote
//                  Uses the quote tables and other stuff from Erik Slooff
// ----------------------------------------------------------------------

/**
 * Init quotes block
 * @author Erik Slooff <erik@slooff.com>
 * @link http://www.slooff.com
 */
function quotes_quoteblock_init()
{
    // Security
    pnSecAddSchema('Quotes:Quoteblock:', 'Block title::');
}

/**
 * Return Quotes blockinfo array
 * @author Erik Slooff <erik@slooff.com>
 * @link http://www.slooff.com
 * @return array
 */
function quotes_quoteblock_info()
{
    return array('text_type' => 'Quote',
                 'module' => 'Quotes',
                 'text_type_long' => 'Random Quote',
                 'allow_multiple' => true,
                 'form_content' => false,
                 'form_refresh' => false,
                 'show_preview' => true);
}

/**
 * Display quotes block
 * @author Erik Slooff <erik@slooff.com>
 * @link http://www.slooff.com
 * @param 'blockinfo' blockinfo array
 * @return HTML String
 */
function quotes_quoteblock_display($blockinfo)
{
    if (!pnSecAuthAction(0, 'Quotes:Quoteblock:', "$blockinfo[title]::", ACCESS_READ)) {
        return;
    }

	if (!pnModAvailable('Quotes')) {
		return;
	}

    $total = pnModAPIFunc('Quotes', 'user', 'countitems');

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
	$pnRender =& new pnRender('Quotes');

    mt_srand((double)microtime()*1000000);

    if ($total < 2) {
	    $pnRender->assign('errormsg', _QUOTESERRORMSG);
    } else {
		$pnRender->assign('errormsg', '');
        $p = mt_rand(0,($total));
        $quotes = pnModAPIFunc('Quotes', 'user', 'getall', array('numitems' => 1, 'startnum' => $p));
        foreach ($quotes as $quote) {
		    $fullquote = pnModAPIFunc('Quotes', 'user' , 'get', array('qid' => $quote['qid']));
	        $pnRender->assign('quotetext', $fullquote['quote']);
      		$pnRender->assign('quoteauthor', $fullquote['author']);
	    }
    }
    $blockinfo['content'] = $pnRender->fetch('quotes_block_quote.htm');
	
    return themesideblock($blockinfo);
}

?>