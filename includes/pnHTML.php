<?php
// File: $Id: pnHTML.php 16657 2005-08-21 10:20:22Z landseer $
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
// Original Author of file: Jim McDonald
// Purpose of file: HTML helpers
// ----------------------------------------------------------------------

/**
 * @package PostNuke_Core
 * @subpackage PostNuke_pnHTML
 */
/**
 * Set object to keep generated HTML.
 *
 * After calling SetOutputMode() with this value, all future calls to
 * pnHTML methods will store their HTML in the objecr rather than
 * returning it to the calling process.
 *
 * $const _PNH_KEEPOUTPUT Keep the output from method calls
 */
define('_PNH_KEEPOUTPUT', 0);

/**
 * Set object to return generated HTML to caller.
 *
 * After calling SetOutputMode() with this value, all future calls to
 * pnHTML methods will return their HTML directly to the calling process
 * rather than storing it within the object.
 *
 * $const _PNH_RETURNOUTPUT Return the output from method calls
 */
define('_PNH_RETURNOUTPUT', 1);

/**
 * Set incoming text to be copied verbatim to the output buffer
 *
 * $const _PNH_VERBATIMINPUT Do not parse incoming text
 */
define('_PNH_VERBATIMINPUT', 0);

/**
 * Set incoming text to be parsed for display before putting in the output buffer
 *
 * $const _PNH_PARSEINPUT Parse incoming text
 */
define('_PNH_PARSEINPUT', 1);

/**
 * HTML creation and display functions
 *
 * This class is designed to make generating HTML output in PostNuke
 * very simple, and also allows for much greater control of output by
 * the site administrator.
 *
 *
 * <strong>Example</strong>
 * <pre>
 * // Information array
 * $colors = array(array('id' => 1,
 *                       'name' => 'Red',
 *                       'encoding' => 'ff0000'),
 *                 array('id' => 2,
 *                       'name' => 'Blue',
 *                       'encoding' => '00ff00'),
 *                 array('id' => 3,
 *                       'name' => 'Green',
 *                       'encoding' => '0000ff'));
 *
 * // Create the HTML object and start it
 * $myhtml = new pnHTML();
 * $myhtml->Start();
 *
 * // Add table showing encoding information
 * $myhtml->TableStart('Colors and Their Encodings', array('Color', 'Encoding'));
 * foreach ($colors as $color) {
 *     $info = array($color['name'], $color['encoding']);
 *     $myhtml->TableAddRow($info);
 * }
 * $myhtml->TableEnd();
 *
 * // Add form to select a color
 * $myhtml->Text('&lt;P&gt;&lt;P&gt;');
 * $myhtml->FormStart('colorchosen.php');
 * $myhtml->Text('Select a color: ');
 * $myhtml->FormList('chosen', $colorinfo);
 * $myhtml->FormSubmit('That\'s the color I want');
 * $myhtml->FormEnd();
 *
 *
 * // End the HTML object and print it
 * $myhtml->End();
 * $myhtml->PrintPage();
 * </pre>
 *
 * @package PostNuke_Core
 * @subpackage PostNuke_pnHTML
 * @author Jim McDonald
 * @author Patrick Kellum
 * @link http://www.postnuke.com/ The Official PostNuke website
 * @copyright (C) 2001, 2002 by the PostNuke Development Team
 * @version $Revision: 16657 $
 * @todo need to add text sanitizer
 */
class pnHTML {
    /**
     * ==============================================================================*
     * |                               Properties                                     |
     * ==============================================================================
     */

    /**
     * Specific headers which must be printed prior to the main body of HTML
     *
     * @access private
     * @var string $header
     */
    var $header;

    /**
     * The pending HTML output
     *
     * @access private
     * @var string $output
     */
    var $output;

    /**
     * Return output?
     *
     * @access private
     * @var integer $return
     */
    var $return;

    /**
     * Parse text for output?
     *
     * @access private
     * @var integer $parse
     */
    var $parse;

    /**
     * Current tab index value
     *
     * @access private
     * @var integer $tabindex
     */
    var $tabindex;

    /**
     * File upload mode
     *
     * @access private
     * @since 1.13 - 2002/01/23
     * @var integer $fileupload
     */
    var $fileupload;

    /**
     * ==============================================================================*
     * |                             Methods: Base                                    |
     * ==============================================================================
     */

    /**
     * Constructor
     *
     * @access public
     * @return boolean Always returns true
     */
    function pnHTML()
    {
        $this->header = array ();
        $this->output = '';
        $this->return = _PNH_KEEPOUTPUT;
        $this->parse = _PNH_PARSEINPUT;
        $this->tabindex = 0;
        $this->fileupload = 0;
        return true;
    }

    /**
     * Return the current state of the output stream
     *
     * @access public
     * @since 1.13 - 2002/01/23
     * @return integer Current output state
     * @see SetOutputMode()
     */
    function GetOutputMode()
    {
        // The ONLY time this should be accessed directly
        return $this->return;
    }

    /**
     * Set state of the output stream
     *
     * @access public
     * @since 1.14 - 2002/01/29
     * @param int $st Output state to set to
     * @return integer Previous state
     * @see GetOutputMode()
     */
    function SetOutputMode($st)
    {
        $pre = $this->GetOutputMode();
        switch ($st) {
            default:
            case _PNH_KEEPOUTPUT: {
                // The ONLY time this should be accessed directly
                $this->return = _PNH_KEEPOUTPUT;
                break;
            }
            case _PNH_RETURNOUTPUT: {
                // The ONLY time this should be accessed directly
                $this->return = _PNH_RETURNOUTPUT;
                break;
            }
        }
        return $pre;
    }

    /**
     * Retrive the current input state
     *
     * @access public
     * @since 1.13 - 2002/01/23
     * @return integer Current input state
     * @see SetInputMode()
     */
    function GetInputMode()
    {
        // The ONLY time this should be accessed directly
        return $this->parse;
    }

    /**
     * Set state of the input stream
     *
     * @access public
     * @since 1.14 - 2002/01/29
     * @param int $st Input state to set to
     * @return integer Previous state
     * @see GetInputMode()
     */
    function SetInputMode($st)
    {
        $pre = $this->GetInputMode();
        switch ($st) {
            case _PNH_VERBATIMINPUT: {
                // The ONLY time this should be accessed directly
                $this->parse = _PNH_VERBATIMINPUT;
                break;
            }
            default:
            case _PNH_PARSEINPUT: {
                // The ONLY time this should be accessed directly
                $this->parse = _PNH_PARSEINPUT;
                break;
            }
        }
        return $pre;
    }

    /**
     * Set the form to allow file uploads to take place
     *
     * @access public
     * @since 1.13 - 2002/01/23
     * @return boolean Always returns true
     * @see FormStart(), FormFile()
     */
    function UploadMode()
    {
        $this->fileupload = 1;
        return true;
    }

    /**
     * ==============================================================================*
     * |                            Methods: Output                                   |
     * ==============================================================================
     */

    /**
     * Return the HTML output from the buffer.
     *
     * Note that this function does not clear out the object's buffer.
     *
     * @access public
     * @since 1.15 - 2002/01/30
     * @return string An HTML string
     */
    function GetOutput()
    {
        return implode($this->header, "\n") . "\n" . $this->output;
    }

    /**
     * Print the HTML currently held in the object.
     *
     * Note that this function does not clear out the object's buffer.
     *
     * @access public
     */
    function PrintPage()
    {
        // Headers set by the system
        foreach ($this->header as $headerline) {
            header($headerline);
        }

        // Other headers
		// Removed as per patch #264 bvdbos
        // header('Content-length: ' . strlen($this->output));
        print $this->output;
    }

    /**
     * ==============================================================================*
     * |                             Methods: Misc                                    |
     * ==============================================================================
     */

    /**
     * Put the appropriate HTML tags in place to create a valid start to HTML output.
     *
     * @access public
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     * @see EndPage()
     */
    function StartPage()
    {
        ob_start();
        include 'header.php';
        //        print '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
        // Fixes bug 16 Neo submitted by keops 14/09/2002
		// Remove extra table on output for XTE for .726
        // print '<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">';

        $output = ob_get_contents();
        @ob_end_clean();

        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT)
        {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     * Put the appropriate HTML tags in place to create a valid end to HTML output.
     *
     * @access public
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     * @see StartPage()
     */
    function EndPage()
    {
        global $index;
        if (pnVarCleanFromInput('module')) {
            $index = 0;
        } else {
            $index = 1;
        }
        ob_start();
		//if (!class_exists('Xanthia')) {
            // Do we really need an extra table here? - markwest
			// Not just with Xanthia themes but rendered modules
			// This prevents this like RSS theme overrrides etc.
			//print '</td></tr></table><!-- testing tables footer -->';
		//}
        include 'footer.php';
        $output = ob_get_contents();
        @ob_end_clean();

        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     *
     * @access public
     * @author Greg 'Adam Baum'
     * @since 1.13 - 2002/01/23
     * @param integer $startnum start iteam
     * @param integer $total total number of items present
     * @param string $urltemplate template for url, will replace '%%' with item number
     * @param integer $perpage number of links to display (default=10)
     */
    function Pager($startnum, $total, $urltemplate, $perpage = 10)
    {
        // Quick check to ensure that we have work to do
        if ($total <= $perpage) {
            return;
        }
        $compoutput = new pnHTML();

        if (empty($startnum)) {
            $startnum = 1;
        }

        if (empty($perpage)) {
            $perpage = 10;
        }
        // Make << and >> do paging properly
        // Display subset of pages if large number

        // Check that we are needed
        if ($total <= $perpage) {
            return;
        }

        // Show Introtext for WCAG
        $compoutput->Text(_PAGE.' ');

        // Show startnum link
        if ($startnum != 1) {
            $url = preg_replace('/%%/', 1, $urltemplate);
            $compoutput->URL($url, '<<');
        } else {
            $compoutput->Text('<<');
        }
        $compoutput->Text(' ');

		// Show following items
        $pagenum = 1;

		$compoutput->Text(' | ');
        for ($curnum = 1; $curnum <= $total; $curnum += $perpage) {
            if (($startnum < $curnum) || ($startnum > ($curnum + $perpage - 1))) {
                //mod by marsu - use sliding window for pagelinks
                if ((($pagenum%10)==0) // link if page is multiple of 10
                        || ($pagenum==1) // link first page
                        || (($curnum >($startnum-4*$perpage)) //link -3 and +3 pages
                        &&($curnum <($startnum+4*$perpage)))
                ) {
                // Not on this page - show link
                $url = preg_replace('/%%/', $curnum, $urltemplate);
                $compoutput->URL($url, $pagenum);
                $compoutput->Text(' | ');
                }
                //end mod by marsu
            } else {
                // On this page - show text
                $compoutput->Text($pagenum.' ');
                $compoutput->Text(' | ');
            }
            $pagenum++;
        }
        if (($curnum >= $perpage + 1) && ($startnum < $curnum - $perpage)) {
            $url = preg_replace('/%%/', $curnum - $perpage, $urltemplate);
            $compoutput->URL($url, '>>');
        } else {
            $compoutput->Text('>>');
        }

        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            $compoutput->SetOutputMode(_PNH_RETURNOUTPUT);
            return $compoutput->PrintPage();
        } else {
            $this->output .= $compoutput->GetOutput();
        }
    }

    /**
     * Redirect the user to another page
     *
     * This function is broken, do not use it!
     *
     * @access public
     * @param string $url URL to redirect to
     * @param integer $waittime Seconds to wait before redirecting
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     * @todo This function is broken, do not use it!
     */
    function Redirect($url, $waittime = 3)
    {
        $server = pnServerGetVar('HTTP_HOST');
        $self = pnServerGetVar('PHP_SELF');

        // Removing leading slashes from path
        $path = preg_replace('!^/*!', '', dirname($self));

        // Removing leading slashes from redirect url
        $url = preg_replace('!^/*!', '', $url);

        // Make redirect line
        if (empty ($path)) {
            $output = "Location: http://$server/$url";
        } else {
            $output = "Location: http://$server/$path/$url";
        }

        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            return $output;
        } else {
            $this->header[] = $output;
        }
    }

    /**
     * composite function for generic confirmation of action
     *
     * @param string $confirm_text Confirmation message to display
     * @param string $confirm_url URL to go to if confirm button is clicked
     * @param string $cancel_text Link text cor the cancel message
     * @param string $cancel_url URL to go to is action is canceled
     * @param array $arg An array of args to create hidden fields for
	 *
     * @access public
     */
    function ConfirmAction($confirm_text, $confirm_url, $cancel_text, $cancel_url, $arg = array ())
    {
        $compoutput = new pnHTML();
        $compoutput->FormStart($confirm_url);

        $compoutput->Text($confirm_text);
        $compoutput->Linebreak(2);
        $arg['confirm'] = 1;
        $arg['authid'] = pnSecGenAuthKey();
        $arg['confirmation'] = 1;
        $compoutput->FormHidden($arg);
        $compoutput->FormSubmit(_CONFIRM);
        $compoutput->Linebreak(2);
        $compoutput->URL($cancel_url, $cancel_text);
        $compoutput->FormEnd();
        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            $compoutput->SetOutputMode(_PNH_RETURNOUTPUT);
            return $compoutput->PrintPage();
        } else {
            $compoutput->SetOutputMode(_PNH_RETURNOUTPUT);
            $this->output .= $compoutput->GetOutput();
        }
    }

    /**
     * ==============================================================================*
     * |                             Methods: Text                                    |
     * ==============================================================================
     */

    /**
     * Add free-form text to the object's buffer
     *
     * @access public
     * @param string $text The text string to add
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function Text($text)
    {
        if ($this->GetInputMode() == _PNH_PARSEINPUT) {
            $text = pnVarPrepForDisplay($text);
        }

        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            return $text;
        } else {
            $this->output .= $text;
        }
    }

    /**
     * Add free-form text to the object's buffer as a title
     *
     * @access public
     * @param string $text the text string to add
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function PageTitle($text)
    {
        $output = '<h1>';

        if ($this->GetInputMode() == _PNH_PARSEINPUT) {
            $output .= pnVarPrepForDisplay($text);
        } else {
            $output .= $text;
        }
        $output .= '</h1>';

        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     * Add free-form text to the object's buffer as a title
     *
     * @access public
     * @param string $text the text string to add
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function Title($text)
    {
        $output = '<h2>';

        if ($this->GetInputMode() == _PNH_PARSEINPUT) {
            $output .= pnVarPrepForDisplay($text);
        } else {
            $output .= $text;
        }
        $output .= '</h2>';

        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     * add bold text to the object's buffer
     *
     * @access public
     * @param string $text the text string to add
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function BoldText($text)
    {
        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            return '<strong>' . pnVarPrepForDisplay($text) . '</strong>';
        } else {
            $this->output .= '<strong>' . pnVarPrepForDisplay($text) . '</strong>';
        }
    }

    /**
     * Add line break
     *
     * @access public
     * @param integer $numbreaks number of linebreaks to add
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function Linebreak($numbreaks = 1)
    {
        $out = '';
        for ($i = 0; $i < $numbreaks; $i++) {
            $out .= '<br />';
        }
        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            return $out;
        } else {
            $this->output .= $out;
        }
    }

    /**
     * Add HTML tags for a hotlink.
     *
     * @access public
     * @since 1.13 - 2002/01/23
     * @param string $url the URL of the link
     * @param string $text the text that the URL is anchored to
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function URL($url='', $text='')
    {
        if (empty ($url)) {
            return;
        }

        $output = '<a href="' . $url . '" ';
        if (!empty($text)) {
            if ($this->GetInputMode() == _PNH_PARSEINPUT) {
                $text = pnVarPrepForDisplay($text);
            }
            $title = strip_tags($text);
            if (empty($title)) {
                $output .= '>'.$text;
            } else {
                $output .= 'title="' . $title . '">'.$text;
            }
        } else {
        	$output .= '>' . $url;
        }
        $output .= '</a>';

        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     * ==============================================================================*
     * |                           Methods: Tables                                    |
     * ==============================================================================
     */

    /**
     * Add HTML tags for the start of a table.
     *
     * @access public
     * @param string $title the title of the table
     * @param array $headers an array of column headings
     * @param integer $border size of table borders
     * @param string $width the width of the table.  can be null if no width needs
     * to be specified
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function TableStart($title = '', $headers = array(), $border = 0, $width = '100%', $cellpadding = 0, $cellspacing = 0)
    {
        // Wrap the user table in our own invisible table to make the title sit properly
        $output = '<table border="' . $border . '"' . ((empty ($width)) ? '' : ' width="' . $width . '"') . ' cellpadding="' . $cellpadding . '" cellspacing="' . $cellspacing . "\">\n";
        if (!empty ($title)) {
            if ($this->GetInputMode() == _PNH_PARSEINPUT) {
                $title = pnVarPrepForDisplay($title);
            }
            $output .= '<tr><th align="center">' . $title . '</th></tr>' . "\n";
        }
        $output .= "<tr><td>\n";

        if ($this->GetInputMode() == _PNH_PARSEINPUT) {
            $border = pnVarPrepForDisplay($border);
        }
        $output .= '<table border="' . $border . '" width="100%">';
        // Add column headers
        if (!empty ($headers)) {
            $output .= '<tr>';
            foreach ($headers as $head) {
                if (empty ($head)) {
                    $head = '&nbsp;';
                }
                if ($this->GetInputMode() == _PNH_PARSEINPUT) {
                    $head = pnVarPrepForDisplay($head);
                }
                $output .= '<th>' . $head . '</th>';
            }
            $output .= '</tr>';
        }
        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     * Add HTML tags for the start of a table row.
     *
     * @access public
     * @param string $align Default horizantal alignment for all columns on this row
     * @param string $valign Default vertical alignment for all columns on this row
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function TableRowStart($align = 'center', $valign = 'middle')
    {
        $output = '<tr align="' . $align . '" valign="' . $valign . '">';
        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     * Add HTML tags for the start of a table column.
     *
     * @access public
     * @param integer $colspan number of columns that this column spans
     * @param string $align Horizantal alignment of the column
     * @param string $valign Vertical alignment of this column
     * @param integer $rowspan Total rows this column uses
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function TableColStart($colspan = 1, $align = 'center', $valign = 'middle', $rowspan = 1)
    {
        $output = '<td colspan="' . $colspan . '" rowspan="' . $rowspan . '" align="' . $align . '" valign="' . $valign . '">';
        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     * Add HTML tags for the end of a table column.
     *
     * @access public
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function TableColEnd()
    {
        $output = '</td>';
        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     * Add HTML tags for the end of a table row.
     *
     * @access public
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function TableRowEnd()
    {
        $output = '</tr>';
        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     * Add HTML tags for the end of a table.
     *
     * @access public
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function TableEnd()
    {
        $output = '</table></td></tr></table>';
        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     * Add HTML tags for a row of a table.
     *
     * @access public
     * @param array $row an array of row entries
     * @param string $align (optional) the alignment of the row, which can be
     * one of <code>'left'</code>, <code>'center'</code> or <code>'right'</code>
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function TableAddRow($row, $align = 'center', $valign = 'middle')
	{
        if (empty ($row)) {
            return;
        }
        $output = '<tr align="' . $align . '" valign="' . $valign.'">';
        // test to see if we are using the latest array style
        if (is_array($row[0])) {
            // new style
            foreach ($row as $rowitem) {
                if (!isset($rowitem['content'])) {
                    $rowitem['content'] = '&nbsp;';
                }
                if ($this->GetInputMode()) {
                    $rowitem['content'] = pnVarPrepForDisplay($rowitem['content']);
                }
                $output .= '<td'
                    .((empty ($rowitem['align'])) ? '' : ' align="'.$rowitem['align'].'"')
                    .((empty ($rowitem['valign'])) ? '' : ' valign="'.$rowitem['valign'].'"')
					.((empty ($rowitem['color'])) ? '' : ' bgcolor="'.$rowitem['color'].'"')
                    .'>'
					.((empty ($rowitem['class'])) ? $rowitem['content'] : $rowitem['content'])
					.'</td>';
            }
        } else {
            // old style
            foreach ($row as $rowitem) {
                if (!isset($rowitem)) {
                    $rowitem = '&nbsp;';
                }
                if ($this->GetInputMode() == _PNH_PARSEINPUT) {
                    $rowitem = pnVarPrepForDisplay($rowitem);
                }
                $output .= '<td>' . $rowitem . '</td>';
            }
        }
        $output .= '</tr>';
        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     * ==============================================================================*
     * |                             Methods: Forms                                   |
     * ==============================================================================
     */

    /**
     * Add HTML tags to start a form.
     *
     * @access public
     * @param string $action the URL that this form should go to on submission
	 * @param string $id OPTIONAL - the unique ID of this form
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function FormStart($action, $id='')
    {
        $output = '<form'
            .' action="'.pnVarPrepForDisplay($action).'"'
            .' method="post"'
            .' enctype="'.((empty ($this->fileupload)) ? 'application/x-www-form-urlencoded' : 'multipart/form-data').'"';
        if ($id != '') {
          $output .= ' id="'.pnVarPrepForDisplay($id).'"';
        }
        $output .= '><div>';

        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     * Add HTML tags to end a form.
     *
     * @access public
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function FormEnd()
    {
        $output = '</div></form>';

        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     * Add HTML tags for a submission button as part of a form.
     *
     * @access public
     * @param string $label (optional) the name of the submission button.  This
     * defaults to <code>'Submit'</code>
     * @param string $accesskey (optional) accesskey to active this button
	 * @param string $name (optional) name for this button
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function FormSubmit($label = 'Submit', $accesskey = '', $name = '')
    {
        $this->tabindex++;
        $output = '<input'
            .' type="submit"'
            .' value="'.pnVarPrepForDisplay($label).'"'
			.((empty ($name)) ? '' : ' name="'.pnVarPrepForDisplay($name).'"')
            //.' align="middle"'
            .((empty ($accesskey)) ? '' : ' accesskey="'.pnVarPrepForDisplay($accesskey).'"')
            .' tabindex="'.$this->tabindex.'"'
            .' />'
        ;
        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT)
        {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     * Add HTML tags for a text field as part of a form.
     *
     * @access public
     * @param string $fieldname the name of the text field
     * @param string $contents (optional) the inital value of the text field
     * @param integer $size (optional) the size of the text field on the page
     * in number of characters
     * @param integer $maxlength (optional) the maximum number of characters the
     * text field can hold
     * @param boolean $password (optional) field acts as a password field
     * @param string $accesskey (optional) accesskey to active this item
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function FormText($fieldname, $contents = '', $size = 16, $maxlength = 64, $password = false, $accesskey = '')
    {
        if (empty ($fieldname)) {
            return;
        }
        $this->tabindex++;
        $output = '<input'
            .' type="'.(($password) ? 'password' : 'text').'"'
            .' name="'.pnVarPrepForDisplay($fieldname).'"'
            .' id="'.pnVarPrepForDisplay($fieldname).'"'
            .' value="'.pnVarPrepForDisplay($contents).'"'
            .' size="'.pnVarPrepForDisplay($size).'"'
            .' maxlength="'.pnVarPrepForDisplay($maxlength).'"'
            .((empty ($accesskey)) ? '' : ' accesskey="'.pnVarPrepForDisplay($accesskey).'"')
            .' tabindex="'.$this->tabindex.'"'
            .' />'
        ;
        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     * Add HTML tags for a text area as part of a form
     *
     * @access public
     * @param string $fieldname the name of the text area filed
     * @param string $contents the initial value of the text area field
     * @param integer $rows the number of rows that the text area
     * |        should cover
     * @param integer $cols the number of columns that the text area
     * |        should cover
     * @param string $wrap (optional) wordwrap mode to use, either <code>'soft'</code> or <code>'hard'</code>
     * @param string $accesskey (optional) accesskey to active this item
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function FormTextArea($fieldname, $contents = '', $rows = 6, $cols = 40, $wrap = 'soft', $accesskey = '')
    {
        if (empty ($fieldname)) {
            return;
        }
        $this->tabindex++;
        $output = '<textarea'
            .' name="'.pnVarPrepForDisplay($fieldname).'"'
            .' id="'.pnVarPrepForDisplay($fieldname).'"'
            //.' wrap="'.(($wrap = 'soft') ? 'soft' : 'hard').'"' // not proper HTML, but too useful to abandon yet
            .' rows="'.pnVarPrepForDisplay($rows).'"'
            .' cols="'.pnVarPrepForDisplay($cols).'"'
            .((empty ($accesskey)) ? '' : ' accesskey="'.pnVarPrepForDisplay($accesskey).'"')
            .' tabindex="'.$this->tabindex.'"'
            .'>'
            .pnVarPrepForDisplay($contents)
            .'</textarea>';
        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT)  {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     * Add HTML tags for a hidden field as part of a form.
     *
     * @access public
     * @param mixed $fieldname the name of the hidden field.  can also be an array.
     * @param string $value the value of the hidden field
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function FormHidden($fieldname, $value = '')
    {
        if (empty ($fieldname)) {
            return;
        }
        if (is_array($fieldname)) {
            $output = '';
            foreach ($fieldname as $n=>$v) {
                $output .= '<input'
                    .' type="hidden"'
                    .' name="'.pnVarPrepForDisplay($n).'"'
                    //.' id="'.pnVarPrepForDisplay($n).'"'
                    .' value="'.pnVarPrepForDisplay($v).'"'
                    .'/>';
            }
        } else {
            $output = '<input'
                .' type="hidden"'
                .' name="'.pnVarPrepForDisplay($fieldname).'"'
                //.' id="'.pnVarPrepForDisplay($fieldname).'"'
                .' value="'.pnVarPrepForDisplay($value).'"'
                .' />';
        }
        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     * Add HTML tags for a select field as part of a form.
     *
     * @access public
     * @since 1.13 - 2002/01/23
     * @param string $fieldname the name of the select field
     * @param array $data an array containing the data for the list.  Each array
     * entry is itself an array, containing the values for <code>'id'</code>
     * (the value returned if the entry is selected), <code>'name'</code>
     * (the string displayed for this entry) and <code>'selected'</code>
     * (optional, <code>1</code> if this option is selected)
     * @param integer $multiple (optional) <code>1</code> if the user is allowed to
     * make multiple selections
     * @param integer $size (optional) the number of entries that are visible in the
     * select at any one time.  Note that if the number
     * of actual items is less than this value then the select box will
     * shrink automatically to the correct size
     * @param string $selected (optional) selected value of <code>id</code>
     * @param string $accesskey (optional) accesskey to active this item
     * @param string $events: javascript events
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function FormSelectMultiple($fieldname, $data, $multiple=0, $size=1, $selected = '', $accesskey='', $events='')
    {
        if (empty ($fieldname)) {
            return;
        }
        $this->tabindex++;

        // Set up selected if required
        if (!empty($selected)) {
            for ($i=0; !empty($data[$i]); $i++) {
                if ($data[$i]['id'] == $selected) {
                    $data[$i]['selected'] = 1;
                }
            }
        }

        $c = count($data);
        if ($c < $size) {
            $size = $c;
        }
        $output = '<select'
            .' name="'.pnVarPrepForDisplay($fieldname).'"'
            .' id="'.pnVarPrepForDisplay($fieldname).'"'
            .' size="'.pnVarPrepForDisplay($size).'"'
            .(($multiple == 1) ? ' multiple="multiple"' : '')
            .((empty ($accesskey)) ? '' : ' accesskey="'.pnVarPrepForDisplay($accesskey).'"')
            .' tabindex="'.$this->tabindex.'"';

        if ($events != '') {
        	$output .= ' '.$events;
        }

        $output .= '>';

        foreach ($data as $datum) {
            $output .= '<option'
                .' value="'.pnVarPrepForDisplay($datum['id']).'"'
                .((empty ($datum['selected'])) ? '' : ' selected="selected"')
                .'>'
                .pnVarPrepForDisplay($datum['name'])
                .'</option>';
        }
        $output .= '</select>';
        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     * Add HTML tags for a checkbox or radio button field as part of a form.
     *
     * @access public
     * @since 1.13 - 2002/01/23
     * @param string $fieldname the name of the checkbox field
     * @param string $value (optional) the value of the checkbox field
     * @param boolean $checked (optional) the field is checked
     * @param string $type (optional) the type of field this is, either
     * <code>'checkbox'</code> or <code>'radio'</code>
     * @param string $accesskey (optional) accesskey to active this item
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function FormCheckbox($fieldname, $checked = false, $value = '1', $type = 'checkbox', $accesskey = '')
    {
        if (empty ($fieldname)) {
            return;
        }
        $this->tabindex++;
        $output = '<input'
            .' type="'.(($type == 'checkbox') ? 'checkbox' : 'radio').'"'
            .' name="'.pnVarPrepForDisplay($fieldname).'"'
            .' id="'.pnVarPrepForDisplay($fieldname).'"'
            .' value="'.pnVarPrepForDisplay($value).'"'
            .(($checked) ? ' checked="checked"' : '')
            .((empty ($accesskey)) ? '' : ' accesskey="'.pnVarPrepForDisplay($accesskey).'"')
            .' tabindex="'.$this->tabindex.'"'
            .' />'
        ;
        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     * Add HTML tags for a file upload field as part of a form.
     *
     * @access public
     * @since 1.13 - 2002/01/23
     * @param string $fieldname the name of the field
     * @param integer $size (optional) the size of the field on the page in number
     * of characters
     * @param integer $maxsize (optional) the maximum file size allowed (in bytes)
     * @param string $accesskey (optional) accesskey to active this item
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function FormFile($fieldname, $size = 32, $maxsize = 1000000, $accesskey = '')
    {
        if (empty ($fieldname)) {
            return;
        }
        $this->tabindex++;
        $output = '<input type="hidden" name="MAX_FILE_SIZE" value="'.pnVarPrepForDisplay($maxsize).'" />';
        $output .= '<input'
            .' type="file"'
            .' name="'.pnVarPrepForDisplay($fieldname).'"'
            .' id="'.pnVarPrepForDisplay($fieldname).'"'
            .' size="'.pnVarPrepForDisplay($size).'"'
            .((empty ($accesskey)) ? '' : ' accesskey="'.pnVarPrepForDisplay($accesskey).'"')
            .' tabindex="'.$this->tabindex.'"'
            .' />'
        ;
        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT) {
            return $output;
        } else {
            $this->output .= $output;
        }
    }
}

?>