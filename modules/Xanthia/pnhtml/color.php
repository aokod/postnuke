<?php
?>
<!DOCTYPE html public "-//W3C//DTD HTML 3.2 Final//EN">
<HTML>
<HEAD>
<TITLE>free colorpicker, websafe colors, named colorpalette, HEXRGB conversion</TITLE>
<STYLE type="text/css"><!--
FONT		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
TD		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
BODY		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
P		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
DIV		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
INPUT		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
TEXTAREA	{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
FORM 		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
SELECT		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
.dark {text-decoration : none; color : white;}

.light {text-decoration : none; color : black;}

A {text-decoration : none; color : black;}

.Anormal{text-decoration: underline; color : navy;}

.hover {cursor:pointer; height:15; width:15;}
-->
</STYLE>

<SCRIPT LANGUAGE="JavaScript">
<!--
var color="";
function linkClicky(color)
{
target="javascript: parent.SetColor('"+color+"')";

parent.location.href=target;
}

-->
</SCRIPT>

</HEAD>
<BODY link="#000080" vlink="#000080" bgcolor="#C0C0C0">
<CENTER>
	<TABLE border="0" cellpadding="2" cellspacing="0" width="185">	<!-- Copyright 2000 VisiBone -->
	<!-- Arrangement by Bob Stein -->
		<TR>
			<TD class="hover" style="background-color:#FFFFFF;" onclick="linkClicky('FFFFFF')"></TD>
			<TD class="hover" style="background-color:#CCCCCC;" onclick="linkClicky('CCCCCC')"></TD>
			<TD class="hover" style="background-color:#999999;" onclick="linkClicky('999999')"></TD>
			<TD class="hover" style="background-color:#666666;" onclick="linkClicky('666666')"></TD>
			<TD class="hover" style="background-color:#333333;" onclick="linkClicky('333333')"></TD>
			<TD class="hover" style="background-color:#000000;" onclick="linkClicky('000000')"></TD>
			<TD class="hover" style="background-color:#FFCC00;" onclick="linkClicky('FFCC00')"></TD>
			<TD class="hover" style="background-color:#FF9900;" onclick="linkClicky('FF9900')"></TD>
			<TD class="hover" style="background-color:#FF6600;" onclick="linkClicky('FF6600')"></TD>
			<TD class="hover" style="background-color:#FF3300;" onclick="linkClicky('FF3300')"></TD>
			<TD colspan="6"></TD>
		</TR>
		<TR>
			<TD class="hover" style="background-color:#99CC00;" onclick="linkClicky('99CC00')"></TD>
			<TD colspan="4"></TD>
			<TD class="hover" style="background-color:#CC9900;" onclick="linkClicky('CC9900')"></TD>
			<TD class="hover" style="background-color:#FFCC33;" onclick="linkClicky('FFCC33')"></TD>
			<TD class="hover" style="background-color:#FFCC66;" onclick="linkClicky('FFCC66')"></TD>
			<TD class="hover" style="background-color:#FF9966;" onclick="linkClicky('FF9966')"></TD>
			<TD class="hover" style="background-color:#FF6633;" onclick="linkClicky('FF6633')"></TD>
			<TD class="hover" style="background-color:#CC3300;" onclick="linkClicky('CC3300')"></TD>
			<TD colspan="4"></TD>
			<TD class="hover" style="background-color:#CC0033;" onclick="linkClicky('CC0033')"></TD>
		</TR>
		<TR>
			<TD class="hover" style="background-color:#CCFF00;" onclick="linkClicky('CCFF00')"></TD>
			<TD class="hover" style="background-color:#CCFF33;" onclick="linkClicky('CCFF33')"></TD>
			<TD class="hover" style="background-color:#333300;" onclick="linkClicky('333300')"></TD>
			<TD class="hover" style="background-color:#666600;" onclick="linkClicky('666600')"></TD>
			<TD class="hover" style="background-color:#999900;" onclick="linkClicky('999900')"></TD>
			<TD class="hover" style="background-color:#CCCC00;" onclick="linkClicky('CCCC00')"></TD>
			<TD class="hover" style="background-color:#FFFF00;" onclick="linkClicky('FFFF00')"></TD>
			<TD class="hover" style="background-color:#CC9933;" onclick="linkClicky('CC9933')"></TD>
			<TD class="hover" style="background-color:#CC6633;" onclick="linkClicky('330000')"></TD>
			<TD class="hover" style="background-color:#330000;" onclick="linkClicky('330000')"></TD>
			<TD class="hover" style="background-color:#660000;" onclick="linkClicky('660000')"></TD>
			<TD class="hover" style="background-color:#990000;" onclick="linkClicky('990000')"></TD>
			<TD class="hover" style="background-color:#CC0000;" onclick="linkClicky('CC0000')"></TD>
			<TD class="hover" style="background-color:#FF0000;" onclick="linkClicky('FF0000')"></TD>
			<TD class="hover" style="background-color:#FF3366;" onclick="linkClicky('FF3366')"></TD>
			<TD class="hover" style="background-color:#FF0033;" onclick="linkClicky('FF0033')"></TD>
		</TR>
		<TR>
			<TD class="hover" style="background-color:#99FF00;" onclick="linkClicky('99FF00')"></TD>
			<TD class="hover" style="background-color:#CCFF66;" onclick="linkClicky('CCFF66')"></TD>
			<TD class="hover" style="background-color:#99CC33;" onclick="linkClicky('99CC33')"></TD>
			<TD class="hover" style="background-color:#666633;" onclick="linkClicky('666633')"></TD>
			<TD class="hover" style="background-color:#999933;" onclick="linkClicky('999933')"></TD>
			<TD class="hover" style="background-color:#CCCC33;" onclick="linkClicky('CCCC33')"></TD>
			<TD class="hover" style="background-color:#FFFF33;" onclick="linkClicky('FFFF33')"></TD>
			<TD class="hover" style="background-color:#996600;" onclick="linkClicky('996600')"></TD>
			<TD class="hover" style="background-color:#993300;" onclick="linkClicky('993300')"></TD>
			<TD class="hover" style="background-color:#663333;" onclick="linkClicky('663333')"></TD>
			<TD class="hover" style="background-color:#993333;" onclick="linkClicky('993333')"></TD>
			<TD class="hover" style="background-color:#CC3333;" onclick="linkClicky('CC3333')"></TD>
			<TD class="hover" style="background-color:#FF3333;" onclick="linkClicky('FF3333')"></TD>
			<TD class="hover" style="background-color:#CC3366;" onclick="linkClicky('CC3366')"></TD>
			<TD class="hover" style="background-color:#FF6699;" onclick="linkClicky('FF6699')"></TD>
			<TD class="hover" style="background-color:#FF0066;" onclick="linkClicky('FF0066')"></TD>
		</TR>
		<TR>
			<TD class="hover" style="background-color:#66FF00;" onclick="linkClicky('66FF00')"></TD>
			<TD class="hover" style="background-color:#99FF66;" onclick="linkClicky('99FF66')"></TD>
			<TD class="hover" style="background-color:#66CC33;" onclick="linkClicky('66CC33')"></TD>
			<TD class="hover" style="background-color:#669900;" onclick="linkClicky('669900')"></TD>
			<TD class="hover" style="background-color:#999966;" onclick="linkClicky('999966')"></TD>
			<TD class="hover" style="background-color:#CCCC66;" onclick="linkClicky('CCCC66')"></TD>
			<TD class="hover" style="background-color:#FFFF66;" onclick="linkClicky('FFFF66')"></TD>
			<TD class="hover" style="background-color:#996633;" onclick="linkClicky('996633')"></TD>
			<TD class="hover" style="background-color:#663300;" onclick="linkClicky('663300')"></TD>
			<TD class="hover" style="background-color:#996666;" onclick="linkClicky('996666')"></TD>
			<TD class="hover" style="background-color:#CC6666;" onclick="linkClicky('CC6666')"></TD>
			<TD class="hover" style="background-color:#FF6666;" onclick="linkClicky('FF6666')"></TD>
			<TD class="hover" style="background-color:#990033;" onclick="linkClicky('990033')"></TD>
			<TD class="hover" style="background-color:#CC3399;" onclick="linkClicky('CC3399')"></TD>
			<TD class="hover" style="background-color:#FF66CC;" onclick="linkClicky('FF66CC')"></TD>
			<TD class="hover" style="background-color:#FF0099;" onclick="linkClicky('FF0099')"></TD>
		</TR>
		<TR>
			<TD class="hover" style="background-color:#33FF00;" onclick="linkClicky('33FF00')"></TD>
			<TD class="hover" style="background-color:#66FF33;" onclick="linkClicky('66FF33')"></TD>
			<TD class="hover" style="background-color:#339900;" onclick="linkClicky('339900')"></TD>
			<TD class="hover" style="background-color:#66CC00;" onclick="linkClicky('66CC00')"></TD>
			<TD class="hover" style="background-color:#99FF33;" onclick="linkClicky('99FF33')"></TD>
			<TD class="hover" style="background-color:#CCCC99;" onclick="linkClicky('CCCC99')"></TD>
			<TD class="hover" style="background-color:#FFFF99;" onclick="linkClicky('FFFF99')"></TD>
			<TD class="hover" style="background-color:#CC9966;" onclick="linkClicky('CC9966')"></TD>
			<TD class="hover" style="background-color:#CC6600;" onclick="linkClicky('CC6600')"></TD>
			<TD class="hover" style="background-color:#CC9999;" onclick="linkClicky('CC9999')"></TD>
			<TD class="hover" style="background-color:#FF9999;" onclick="linkClicky('FF9999')"></TD>
			<TD class="hover" style="background-color:#FF3399;" onclick="linkClicky('FF3399')"></TD>
			<TD class="hover" style="background-color:#CC0066;" onclick="linkClicky('CC0066')"></TD>
			<TD class="hover" style="background-color:#990066;" onclick="linkClicky('990066')"></TD>
			<TD class="hover" style="background-color:#FF33CC;" onclick="linkClicky('FF33CC')"></TD>
			<TD class="hover" style="background-color:#FF00CC;" onclick="linkClicky('FF00CC')"></TD>
		</TR>
		<TR>
			<TD class="hover" style="background-color:#00CC00;" onclick="linkClicky('00CC00')"></TD>
			<TD class="hover" style="background-color:#33CC00;" onclick="linkClicky('33CC00')"></TD>
			<TD class="hover" style="background-color:#336600;" onclick="linkClicky('336600')"></TD>
			<TD class="hover" style="background-color:#669933;" onclick="linkClicky('669933')"></TD>
			<TD class="hover" style="background-color:#99CC66;" onclick="linkClicky('99CC66')"></TD>
			<TD class="hover" style="background-color:#CCFF99;" onclick="linkClicky('CCFF99')"></TD>
			<TD class="hover" style="background-color:#FFFFCC;" onclick="linkClicky('FFFFCC')"></TD>
			<TD class="hover" style="background-color:#FFCC99;" onclick="linkClicky('FFCC99')"></TD>
			<TD class="hover" style="background-color:#FF9933;" onclick="linkClicky('FF9933')"></TD>
			<TD class="hover" style="background-color:#FFCCCC;" onclick="linkClicky('FFCCCC')"></TD>
			<TD class="hover" style="background-color:#FF99CC;" onclick="linkClicky('FF99CC')"></TD>
			<TD class="hover" style="background-color:#CC6699;" onclick="linkClicky('CC6699')"></TD>
			<TD class="hover" style="background-color:#993366;" onclick="linkClicky('993366')"></TD>
			<TD class="hover" style="background-color:#660033;" onclick="linkClicky('660033')"></TD>
			<TD class="hover" style="background-color:#CC0099;" onclick="linkClicky('CC0099')"></TD>
			<TD class="hover" style="background-color:#330033;" onclick="linkClicky('330033')"></TD>
		</TR>
		<TR>
			<TD class="hover" style="background-color:#33CC33;" onclick="linkClicky('33CC33')"></TD>
			<TD class="hover" style="background-color:#66CC66;" onclick="linkClicky('66CC66')"></TD>
			<TD class="hover" style="background-color:#00FF00;" onclick="linkClicky('00FF00')"></TD>
			<TD class="hover" style="background-color:#33FF33;" onclick="linkClicky('33FF33')"></TD>
			<TD class="hover" style="background-color:#66FF66;" onclick="linkClicky('66FF66')"></TD>
			<TD class="hover" style="background-color:#99FF99;" onclick="linkClicky('99FF99')"></TD>
			<TD class="hover" style="background-color:#CCFFCC;" onclick="linkClicky('CCFFCC')"></TD>
			<TD colspan="3"></TD>
			<TD class="hover" style="background-color:#CC99CC;" onclick="linkClicky('CC99CC')"></TD>
			<TD class="hover" style="background-color:#996699;" onclick="linkClicky('996699')"></TD>
			<TD class="hover" style="background-color:#993399;" onclick="linkClicky('993399')"></TD>
			<TD class="hover" style="background-color:#990099;" onclick="linkClicky('990099')"></TD>
			<TD class="hover" style="background-color:#663366;" onclick="linkClicky('663366')"></TD>
			<TD class="hover" style="background-color:#660066;" onclick="linkClicky('660066')"></TD>
		</TR>
		<TR>
			<TD class="hover" style="background-color:#006600;" onclick="linkClicky('006600')"></TD>
			<TD class="hover" style="background-color:#336633;" onclick="linkClicky('336633')"></TD>
			<TD class="hover" style="background-color:#009900;" onclick="linkClicky('009900')"></TD>
			<TD class="hover" style="background-color:#339933;" onclick="linkClicky('339933')"></TD>
			<TD class="hover" style="background-color:#669966;" onclick="linkClicky('669966')"></TD>
			<TD class="hover" style="background-color:#99CC99;" onclick="linkClicky('99CC99')"></TD>
			<TD colspan="3"></TD>
			<TD class="hover" style="background-color:#FFCCFF;" onclick="linkClicky('FFCCFF')"></TD>
			<TD class="hover" style="background-color:#FF99FF;" onclick="linkClicky('FF99FF')"></TD>
			<TD class="hover" style="background-color:#FF66FF;" onclick="linkClicky('FF66FF')"></TD>
			<TD class="hover" style="background-color:#FF33FF;" onclick="linkClicky('FF33FF')"></TD>
			<TD class="hover" style="background-color:#FF00FF;" onclick="linkClicky('FF00FF')"></TD>
			<TD class="hover" style="background-color:#CC66CC;" onclick="linkClicky('CC66CC')"></TD>
			<TD class="hover" style="background-color:#CC33CC;" onclick="linkClicky('CC33CC')"></TD>
		</TR>
		<TR>
			<TD class="hover" style="background-color:#003300;" onclick="linkClicky('003300')"></TD>
			<TD class="hover" style="background-color:#00CC33;" onclick="linkClicky('00CC33')"></TD>
			<TD class="hover" style="background-color:#006633;" onclick="linkClicky('006633')"></TD>
			<TD class="hover" style="background-color:#339966;" onclick="linkClicky('339966')"></TD>
			<TD class="hover" style="background-color:#66CC99;" onclick="linkClicky('66CC99')"></TD>
			<TD class="hover" style="background-color:#99FFCC;" onclick="linkClicky('99FFCC')"></TD>
			<TD class="hover" style="background-color:#CCFFFF;" onclick="linkClicky('CCFFFF')"></TD>
			<TD class="hover" style="background-color:#3399FF;" onclick="linkClicky('3399FF')"></TD>
			<TD class="hover" style="background-color:#99CCFF;" onclick="linkClicky('99CCFF')"></TD>
			<TD class="hover" style="background-color:#CCCCFF;" onclick="linkClicky('CCCCFF')"></TD>
			<TD class="hover" style="background-color:#CC99FF;" onclick="linkClicky('CC99FF')"></TD>
			<TD class="hover" style="background-color:#9966CC;" onclick="linkClicky('9966CC')"></TD>
			<TD class="hover" style="background-color:#663399;" onclick="linkClicky('663399')"></TD>
			<TD class="hover" style="background-color:#330066;" onclick="linkClicky('330066')"></TD>
			<TD class="hover" style="background-color:#9900CC;" onclick="linkClicky('9900CC')"></TD>
			<TD class="hover" style="background-color:#CC00CC;" onclick="linkClicky('CC00CC')"></TR>
		</TR>
		<TR>
			<TD class="hover" style="background-color:#00FF33;" onclick="linkClicky('00FF33')"></TD>
			<TD class="hover" style="background-color:#33FF66;" onclick="linkClicky('33FF66')"></TD>
			<TD class="hover" style="background-color:#009933;" onclick="linkClicky('009933')"></TD>
			<TD class="hover" style="background-color:#00CC66;" onclick="linkClicky('00CC66')"></TD>
			<TD class="hover" style="background-color:#33FF99;" onclick="linkClicky('33FF99')"></TD>
			<TD class="hover" style="background-color:#99FFFF;" onclick="linkClicky('99FFFF')"></TD>
			<TD class="hover" style="background-color:#99CCCC;" onclick="linkClicky('99CCCC')"></TD>
			<TD class="hover" style="background-color:#0066CC;" onclick="linkClicky('0066CC')"></TD>
			<TD class="hover" style="background-color:#6699CC;" onclick="linkClicky('6699CC')"></TD>
			<TD class="hover" style="background-color:#9999FF;" onclick="linkClicky('9999FF')"></TD>
			<TD class="hover" style="background-color:#9999CC;" onclick="linkClicky('9999CC')"></TD>
			<TD class="hover" style="background-color:#9933FF;" onclick="linkClicky('9933FF')"></TD>
			<TD class="hover" style="background-color:#6600CC;" onclick="linkClicky('6600CC')"></TD>
			<TD class="hover" style="background-color:#660099;" onclick="linkClicky('660099')"></TD>
			<TD class="hover" style="background-color:#CC33FF;" onclick="linkClicky('CC33FF')"></TD>
			<TD class="hover" style="background-color:#CC00FF;" onclick="linkClicky('CC00FF')"></TD>
		</TR>
		<TR>
			<TD class="hover" style="background-color:#00FF66;" onclick="linkClicky('00FF66')"></TD>
			<TD class="hover" style="background-color:#66FF99;" onclick="linkClicky('66FF99')"></TD>
			<TD class="hover" style="background-color:#33CC66;" onclick="linkClicky('33CC66')"></TD>
			<TD class="hover" style="background-color:#009966;" onclick="linkClicky('009966')"></TD>
			<TD class="hover" style="background-color:#66FFFF;" onclick="linkClicky('66FFFF')"></TD>
			<TD class="hover" style="background-color:#66CCCC;" onclick="linkClicky('66CCCC')"></TD>
			<TD class="hover" style="background-color:#669999;" onclick="linkClicky('669999')"></TD>
			<TD class="hover" style="background-color:#003366;" onclick="linkClicky('003366')"></TD>
			<TD class="hover" style="background-color:#336699;" onclick="linkClicky('336699')"></TD>
			<TD class="hover" style="background-color:#6666FF;" onclick="linkClicky('6666FF')"></TD>
			<TD class="hover" style="background-color:#6666CC;" onclick="linkClicky('6666CC')"></TD>
			<TD class="hover" style="background-color:#666699;" onclick="linkClicky('666699')"></TD>
			<TD class="hover" style="background-color:#330099;" onclick="linkClicky('330099')"></TD>
			<TD class="hover" style="background-color:#9933CC;" onclick="linkClicky('9933CC')"></TD>
			<TD class="hover" style="background-color:#CC66FF;" onclick="linkClicky('CC66FF')"></TD>
			<TD class="hover" style="background-color:#9900FF;" onclick="linkClicky('9900FF')"></TD>
		</TR>
		<TR>
			<TD class="hover" style="background-color:#00FF99;" onclick="linkClicky('00FF99')"></TD>
			<TD class="hover" style="background-color:#66FFCC;" onclick="linkClicky('66FFCC')"></TD>
			<TD class="hover" style="background-color:#33CC99;" onclick="linkClicky('33CC99')"></TD>
			<TD class="hover" style="background-color:#33FFFF;" onclick="linkClicky('33FFFF')"></TD>
			<TD class="hover" style="background-color:#33CCCC;" onclick="linkClicky('33CCCC')"></TD>
			<TD class="hover" style="background-color:#339999;" onclick="linkClicky('339999')"></TD>
			<TD class="hover" style="background-color:#336666;" onclick="linkClicky('336666')"></TD>
			<TD class="hover" style="background-color:#006699;" onclick="linkClicky('006699')"></TD>
			<TD class="hover" style="background-color:#003399;" onclick="linkClicky('003399')"></TD>
			<TD class="hover" style="background-color:#3333FF;" onclick="linkClicky('3333FF')"></TD>
			<TD class="hover" style="background-color:#3333CC;" onclick="linkClicky('3333CC')"></TD>
			<TD class="hover" style="background-color:#333399;" onclick="linkClicky('333399')"></TD>
			<TD class="hover" style="background-color:#333366;" onclick="linkClicky('333366')"></TD>
			<TD class="hover" style="background-color:#6633CC;" onclick="linkClicky('6633CC')"></TD>
			<TD class="hover" style="background-color:#9966FF;" onclick="linkClicky('9966FF')"></TD>
			<TD class="hover" style="background-color:#6600FF;" onclick="linkClicky('6600FF')"></TD>
		</TR>
		<TR>
			<TD class="hover" style="background-color:#00FFCC;" onclick="linkClicky('00FFCC')"></TD>
			<TD class="hover" style="background-color:#33FFCC;" onclick="linkClicky('33FFCC')"></TD>
			<TD class="hover" style="background-color:#00FFFF;" onclick="linkClicky('00FFFF')"></TD>
			<TD class="hover" style="background-color:#00CCCC;" onclick="linkClicky('00CCCC')"></TD>
			<TD class="hover" style="background-color:#009999;" onclick="linkClicky('009999')"></TD>
			<TD class="hover" style="background-color:#006666;" onclick="linkClicky('006666')"></TD>
			<TD class="hover" style="background-color:#003333;" onclick="linkClicky('003333')"></TD>
			<TD class="hover" style="background-color:#3399CC;" onclick="linkClicky('3399CC')"></TD>
			<TD class="hover" style="background-color:#3366CC;" onclick="linkClicky('3366CC')"></TD>
			<TD class="hover" style="background-color:#0000FF;" onclick="linkClicky('0000FF')"></TD>
			<TD class="hover" style="background-color:#0000CC;" onclick="linkClicky('0000CC')"></TD>
			<TD class="hover" style="background-color:#000099;" onclick="linkClicky('000099')"></TD>
			<TD class="hover" style="background-color:#000066;" onclick="linkClicky('000066')"></TD>
			<TD class="hover" style="background-color:#000033;" onclick="linkClicky('000033')"></TD>
			<TD class="hover" style="background-color:#6633FF;" onclick="linkClicky('6633FF')"></TD>
			<TD class="hover" style="background-color:#3300FF;" onclick="linkClicky('3300FF')"></TD>
		</TR>
		<TR>
			<TD class="hover" style="background-color:#00CC99;" onclick="linkClicky('00CC99')"></TD>
			<TD colspan="4" valign="middle" align="center"></TD>
			<TD class="hover" style="background-color:#0099CC;" onclick="linkClicky('0099CC')"></TD>
			<TD class="hover" style="background-color:#33CCFF;" onclick="linkClicky('33CCFF')"></TD>
			<TD class="hover" style="background-color:#66CCFF;" onclick="linkClicky('66CCFF')"></TD>
			<TD class="hover" style="background-color:#6699FF;" onclick="linkClicky('6699FF')"></TD>
			<TD class="hover" style="background-color:#3366FF;" onclick="linkClicky('3366FF')"></TD>
			<TD class="hover" style="background-color:#0033CC;" onclick="linkClicky('0033CC')"></TD>
			<TD colspan="4"></TD>
			<TD class="hover" style="background-color:#3300CC;" onclick="linkClicky('3300CC')"></TD>
		</TR>
		<TR>
			<TD colspan="6"></TD>
			<TD class="hover" style="background-color:#00CCFF;" onclick="linkClicky('00CCFF')"></TD>
			<TD class="hover" style="background-color:#0099FF;" onclick="linkClicky('0099FF')"></TD>
			<TD class="hover" style="background-color:#0066FF;" onclick="linkClicky('0066FF')"></TD>
			<TD class="hover" style="background-color:#0033FF;" onclick="linkClicky('0033FF')"></TD>
			<TD colspan="6"></TD>
		</TR>
	</TABLE><BR>© 2000
    &nbsp;&nbsp;Tablelayout reprinted with permission
</CENTER>
</BODY>
</HTML>
