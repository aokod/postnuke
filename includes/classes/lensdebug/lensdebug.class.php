<?php 
// File: $Id: lensdebug.class.php 15630 2005-02-04 06:35:42Z jorg $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2002 by the PostNuke Development Team.
// http://www.postnuke.com/
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
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------
/**
 * Debug Variables - show debugging results in another window using javascript.
 * 
 * The original saved into a file then displayed it in a window 
 * and was written by Alter Gr <alt-gr@gmx.de>.
 * 
 * The new version by John Lim <jlim@natsoft.com.my>
 * 1. writes directly to the debug window without using an intermediate file
 * 2. supports all types, including objects, floats and booleans
 * 3. cleans up strings with < correctly using htmlspecialchars()
 * 4. added msg()
 * 5. added recursion check in v2()
 * 
 * Tested with IE 5.5 and Netscape 4.77 and 6.
 * 
 * usage:    
 * $D = new LensDebug();
 * $D->v($var1,"var1"); // display a variable and its type
 * $D->msg('Show a text message');
 * 
 * second parameter is optional 
 * 
 * Original (c) by DATABAY AG 2001 - ay@databay.de
 * Other portions (c) 2001 by John Lim
 * 
 * This is free software. Use at your own risk.
 */

class LensDebug {
    var $show = false;
    var $depth = 0; 
    // destination of the results
    // file - to a file named debug.html
    // window - to the lens debug window
    var $destination = "window"; 
    // ------------------------------------------
    // Format variable recursively based on type
    function v2($V, $Name = "", $class = false)
    {
        global $depth;

        if ($this->depth > 32) {
            return '<p>Recursive Depth Exceeded</p>';
        } 
        $depth += 1;
        $TYPE_COLOR = "RED";
        $NAME_COLOR = "BLUE";
        $VALUE_COLOR = "BLACK";

        $D = "";

        $Name = htmlspecialchars($Name);

        $type = gettype($V);
        if (is_string($V)) {
            $V = htmlspecialchars($V);
            $D = "<FONT COLOR=$TYPE_COLOR><B>$type: </B></FONT>";
            if ($Name != "") {
                $D .= "<FONT COLOR=$NAME_COLOR>$Name</FONT> = ";
            } 
            $D .= "<FONT COLOR=$VALUE_COLOR>&quot;$V&quot;</FONT>";
        } else if (is_object($V)) {
            $D .= $this->v2(get_object_vars($V), $Name, get_class($V));
            $D = substr($D, 0, strlen($D)-4); // get rid of last BR
        } else if (is_array($V)) {
            if ($class) {
                $t = "Class $class";
            } else {
                $t = 'Array';
            } 

            $D = "<FONT COLOR=$TYPE_COLOR><B>$t: </B></FONT>";

            if ($Name != "") {
                $D .= " (<FONT COLOR=$NAME_COLOR>$Name</FONT>) ";
            } 
            $D .= "<FONT COLOR=$VALUE_COLOR><UL>";

            foreach($V as $key => $val) {
                $D .= $this->v2($val, $key);
            } 
            // $D = substr($D,0,strlen($D)-4); // get rid of last BR
            $D .= "</UL></FONT>";
        } else {
            if ($V === null) {
                $V = 'null';
            } else if ($V === false) {
                $V = 'false';
            } else if ($V === true) {
                $V = 'true';
            } 

            $D = "<FONT COLOR=$TYPE_COLOR><B>$type: </B></FONT>";
            if ($Name != "") {
                $D .= "<FONT COLOR=$NAME_COLOR>$Name</FONT> = ";
            } 
            $D .= "<FONT COLOR=$VALUE_COLOR>$V</FONT>";
        } 

        $D .= "<BR>";
        $this->depth -= 1;
        return($D);
    } 

    function _show()
    {
        if (!$this->show) {
            global $PHP_SELF;

            $file = $PHP_SELF;
            $D = "<TABLE SIZE=100% CELLSPACING=0 CELLPADDING=0 BORDER=0><TR><TD><HR SIZE=1></TD><TD WIDTH=1%><FONT FACE='Verdana,arial' SIZE=1>" . date("d.m.Y") . "&nbsp;" . date("H:i:s") . "</FONT></TD></TR></TABLE>";

            if ($this->destination == "window") {

                ?>
   <SCRIPT>
   lensdebugw=window.open('',"DEBUGVAR","WIDTH=450,HEIGHT=500,scrollbars=yes,resizable=yes");
   if (lensdebugw) {
       lensdebugw.focus();
       lensdebugw.document.write("<?php echo $D;

                ?>"+'<strong>'+window.location.href+'</strong></p>');
   }
   </SCRIPT>
<?php
            } else {
                $fh = fopen('debug.htm', 'a');
                fwrite($fh, $D);
                fclose($fh);
            } 
            $this->show = true;
        } 
    } 
    // ---------------------------------
    // display message in debug window
    function msg($D, $encode = true)
    {
        $this->_show();
        if ($encode) {
            $D = htmlspecialchars($D) . '<p>';
        } 
        $D = str_replace('\\', '\\\\', $D);
        $D = str_replace('"', '\"', $D);
        $D = str_replace("\r", ' ', $D);
        $D = str_replace("\n", ' ', $D);

        if ($this->destination == "window") {

            ?>
<SCRIPT>
if (lensdebugw) {
    lensdebugw.document.write("<?php echo $D;

            ?>");
    lensdebugw.scrollBy(0,100000);
}
</SCRIPT>
<?php
        } else {
            $fh = fopen('debug.html', 'a');
            fwrite($fh, $D);
            fclose($fh);
        } 
    } 
    // ---------------------------------
    // display variable in debug window
    function v($V, $Name = "")
    {
        $this->_show();

        $D = $this->v2($V, $Name);
        if (!is_object($V) and !is_array($V)) {
            $D .= '<br />';
        } 
        $this->msg($D, false);
    } 
} // LensDebug class

?>