<?php
/*
*File used for control panel in Xanthia Color Modifications
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<HTML>
<HEAD>
	<TITLE></TITLE>
<STYLE  TYPE="text/css">

<!--
FONT		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
TD		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
BODY		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
P		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
DIV		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
INPUT		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
TEXTAREA	{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
FORM 		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
SELECT		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
.target { text : Navy;}

.util {text-decoration : none; color : black;}

.but1{
	color : #000000;
	background-color : #88BBEE;
}
.but3{
	color : #000000;
	background-color : #6699CC;
}
.but2{
	color : #000000;
	background-color : #8FBC8F;
}
   } 

-->

</style>

<SCRIPT LANGUAGE="JavaScript">

<!--
// Original Script by Kim Hjortholm, Denmark, 1997
// Homepage www.spiritnet.dk/newbeginnings/ 
//
//     Copyright © 1997 Kim Hjortholm, all rights reserved
//
//     Email : newbeginnings@x2y.dk   http://www.spiritnet.dk/newbeginnings/



function rgbhex(token)
{
document.buttonbar.hexcolor.value=" "+parent.rgbtohex(token);
}

function hexrgb(token)
{
document.buttonbar.rgbcolor.value="   "+parent.hextorgb(token);

}
// -->

</SCRIPT>
</HEAD>



<BODY LEFTMARGIN=0 TOPMARGIN=1 bgcolor="#C0C0C0">

<center>
	<TABLE BORDER=0 VALIGN="MIDDLE" width=100%>
	<FORM NAME=buttonbar>
		<tr>
			<td width=100% bgcolor=88BBEE ALIGN="CENTER">
				<table BORDER=0 width=100% ALIGN="CENTER"><tr><td ALIGN="CENTER">
				<INPUT TYPE="Button" class=but1 ONCLICK="parent.SetTarget('color1');" NAME="color1" VALUE="<? echo _XA_COLOR1C; ?>">
				<INPUT TYPE="Button" class=but1 ONCLICK="parent.SetTarget('color2');" NAME="color2" VALUE="<? echo _XA_COLOR2C; ?>">
				<INPUT TYPE="Button" class=but1 ONCLICK="parent.SetTarget('color3');" NAME="color3" VALUE="<? echo _XA_COLOR3C; ?>">
				<INPUT TYPE="Button" class=but1 ONCLICK="parent.SetTarget('color4');" NAME="color4" VALUE="<? echo _XA_COLOR4C; ?>">
				<INPUT TYPE="Button" class=but1 ONCLICK="parent.SetTarget('color5');" NAME="color5" VALUE="<? echo _XA_COLOR5C; ?>">
				<INPUT TYPE="Button" class=but1 ONCLICK="parent.SetTarget('color6');" NAME="color6" VALUE="<? echo _XA_COLOR6C; ?>">
				<INPUT TYPE="Button" class=but1 ONCLICK="parent.SetTarget('color7');" NAME="color7" VALUE="<? echo _XA_COLOR7C; ?>">
				<INPUT TYPE="Button" class=but1 ONCLICK="parent.SetTarget('color8');" NAME="color8" VALUE="<? echo _XA_COLOR8C; ?>">
				</td></tr>
				<tr><td ALIGN="CENTER">
				<INPUT TYPE="Button" class=but1 ONCLICK="parent.SetTarget('background');" NAME="Background" VALUE="<? echo _XA_BACKGROUNDC; ?>" class="target">
				<INPUT TYPE="Button" class=but1 ONCLICK="parent.SetTarget('sepcolor');" NAME="sepcolor" VALUE="<? echo _XA_SEPERATORC; ?>">
				<INPUT TYPE="Button" class=but1 ONCLICK="parent.SetTarget('text1');" NAME="Text1" VALUE="<? echo _XA_TEXT1C; ?>">
				<INPUT TYPE="Button" class=but1 ONCLICK="parent.SetTarget('text2');" NAME="Text2" VALUE="<? echo _XA_TEXT2C; ?>">
				<INPUT TYPE="Button" class=but1 ONCLICK="parent.SetTarget('link');" NAME="Link" VALUE="<? echo _XA_LINKC; ?>">
				<INPUT TYPE="Button" class=but1 ONCLICK="parent.SetTarget('vlink');" NAME="vLink" VALUE="<? echo _XA_VLINKC; ?>">
				<INPUT TYPE="Button" class=but1 ONCLICK="parent.SetTarget('hover');" NAME="hover" VALUE="<? echo _XA_HOVERC; ?>">
				</td></tr></table>
			</td>
		</tr>
		<tr>
			<td>
				<table ALIGN="CENTER">
					<tr ALIGN="CENTER">
						<td bgcolor=99CC99 >
							<INPUT TYPE="Button" class=but2 ONCLICK="parent.undo();" NAME="undo" VALUE="Undo">
							<INPUT TYPE="Button" class=but2 ONCLICK="parent.swap();" NAME="swap" VALUE="Swap">
						</td>
						<td bgcolor=#6699CC">
							<INPUT TYPE="Button" class=but3 VALUE="Brighter" ONCLICK="parent.brighter()">
							<INPUT TYPE="Button" class=but3 VALUE="Darker" ONCLICK="parent.darker()">
						</td>
						<TD BGCOLOR="#FF0000">
							<INPUT TYPE="checkbox" NAME="adjred" CHECKED><font color="ffffff"><B>R</B></font>
							<INPUT TYPE="text" NAME="chred" VALUE="CC" MAXLENGTH="3" SIZE="3">
						</TD>
						<TD BGCOLOR="#008000">
							<INPUT TYPE="checkbox" NAME="adjgreen" CHECKED><font color="ffffff"><B>G</B></font>
							<INPUT TYPE="text" NAME="chgreen" VALUE="CC" MAXLENGTH="3" SIZE="3">
						</TD>
						<TD BGCOLOR="#0000FF">
							<INPUT TYPE="checkbox" NAME="adjblue" CHECKED><font color="ffffff"><B>B</B></font>
							<INPUT TYPE="text" NAME="chblue" VALUE="99" MAXLENGTH="3" SIZE="3">
						</TD>
					</tr>
				</table>
			</td>

		</TR>
	</TABLE>
</FORM>

</BODY>

</HTML>

