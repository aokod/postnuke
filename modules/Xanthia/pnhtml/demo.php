<?php
if(!isset($palname)){
    $palname='';
}
$palname = @strip_tags($palname);
$paletteid = @strip_tags($paletteid);
$skin = @strip_tags($skin);
$authid = @strip_tags($authid);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">

<HTML>

<HEAD>

	<TITLE>Safecolor</TITLE>

<SCRIPT LANGUAGE="JavaScript">

<!--
function writecolors()

{

document.write("<b><font color="+parent.colortext1 +">text1="+parent.colortext1 +"<br><font color="+parent.colortext2 +">text2="+parent.colortext2 +"<br><a href=#><font color="+parent.colorlink +">link="+parent.colorlink+"</font></a>" + "<br><a href=#><font color="+parent.colorvlink +">vlink="+parent.colorvlink+"</font></a>" +"<br><font color="+parent.colorhover +">hover="+parent.colorhover+"</font>" +
"<br></b>");

}

function submitForm()
{
target="javascript:void(document.collect.submit());"
document.collect.target="_top";
document.location.href=target;
}
// -->

</SCRIPT>
 <STYLE type="text/css">
 <!--
FONT		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
TD		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
TD.text		{vertical-align:middle; text-align:center; padding: .4em;}
BODY		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
P		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
DIV		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
INPUT		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
TEXTAREA	{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
FORM 		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}
SELECT		{FONT-FAMILY: geneva,arial,Verdana,Helvetica; FONT-SIZE: 12px;}

-->
</STYLE>
</HEAD>



<SCRIPT LANGUAGE="JavaScript">

<!--

// Original Script by Kim Hjortholm, Denmark, 1997
// Homepage www.spiritnet.dk/newbeginnings/
//
//     Copyright © 1997 Kim Hjortholm, all rights reserved
//
//     Email : newbeginnings@x2y.dk
//     http://nb-tools.hypermart.net

document.writeln("<BODY bgColor="+parent.colorback+" text="+parent.colortext1+" vlink="+parent.colorvlink+" link="+parent.colorlink+">");

document.writeln('<STYLE  TYPE="text/css">');
document.writeln("A:hover {background :#"+parent.colorhover+";}");
document.writeln("</style>");


document.writeln("<center><form name=frm><table CELLPADDING=5 cellspacing=0 border=0 width=96%>");

document.writeln("<tr><td height=10 bgcolor="+parent.color1+"></td></tr>");
document.writeln("<tr><td height=10 bgcolor="+parent.color2+"></td></tr>");


document.writeln("<tr><td ><b>Quick Start:</b><ol>");

document.writeln("<li>Select which part of this document you want to change the color on by clicking the control-button at the top (Background, Text, Link, Color1, etc)<br>");

document.writeln("<li>Click on the color you want in the colorcube on right to select a color");

document.writeln("<li>When you have completed your color selction click the submit these colors at the bottom of the page to save your changes");

document.writeln("</ol>");

document.writeln('<HR></td></tr></TABLE>');

document.writeln("<b>background="+parent.colorback+"</b><br /><br />");

document.writeln("<table cellspacing=0 border=0><tr><td>");

document.writeln("<tr><td class=text bgcolor="+parent.color1+"><br><B>color1="+parent.color1+"</B><br><br>");

writecolors();

document.writeln("</td>");

document.writeln("<td bgColor="+parent.sepcolor+" width=5>");

document.writeln("</td>");


document.writeln("<td class=text bgcolor="+parent.color2+"><br><B>color2="+parent.color2+"</B><br><br>");

writecolors();

document.writeln("</td>");

document.writeln("<td bgColor="+parent.sepcolor+" width=5>");

document.writeln("</td>");

document.writeln("<td class=text bgcolor="+parent.color3+"><br><B>color3="+parent.color3+"</B><br><br>");

writecolors();

document.writeln("</td>");

document.writeln("</tr>");

document.writeln("<tr><td bgColor="+parent.sepcolor+" height=7>");

document.writeln("</td>");

document.writeln("<td bgColor="+parent.sepcolor+" width=5 height=7>");

document.writeln("</td>");

document.writeln("<td bgColor="+parent.sepcolor+" height=7>");

document.writeln("</td>");

document.writeln("<td bgColor="+parent.sepcolor+" width=5 height=7>");

document.writeln("</td>");

document.writeln("<td bgColor="+parent.sepcolor+" height=7>");

document.writeln("</td></tr>");

document.writeln("<tr><td class=text bgcolor="+parent.color4+"><br><B>color4="+parent.color4+"</B><br><br>");

writecolors();

document.writeln("</td>");

document.writeln("<td bgColor="+parent.sepcolor+" width=5>");

document.writeln("</td>");

document.writeln("<td class=text bgColor="+parent.colorback+">");

writecolors();

document.writeln("</td>");

document.writeln("<td bgColor="+parent.sepcolor+" width=5>");

document.writeln("</td>");

document.writeln("<td class=text bgcolor="+parent.color5+"><br><B>color5="+parent.color5+"</B><br><br>");

writecolors();

document.writeln("</td>");

document.writeln("</tr>");

document.writeln("<tr><td bgColor="+parent.sepcolor+" height=7>");

document.writeln("</td>");

document.writeln("<td bgColor="+parent.sepcolor+" width=5 height=7>");

document.writeln("</td>");

document.writeln("<td bgColor="+parent.sepcolor+" height=7>");

document.writeln("</td>");

document.writeln("<td bgColor="+parent.sepcolor+" width=5 height=7>");

document.writeln("</td>");

document.writeln("<td bgColor="+parent.sepcolor+" height=7>");

document.writeln("</td></tr>");

document.writeln("<tr><td class=text bgcolor="+parent.color6+"><br><B>color6="+parent.color6+"</B><br><br>");

writecolors();

document.writeln("</td>");

document.writeln("<td bgColor="+parent.sepcolor+" width=5>");

document.writeln("</td>");

document.writeln("<td class=text bgcolor="+parent.color7+"><br><B>color7="+parent.color7+"</B><br><br>");

writecolors();

document.writeln("</td>");

document.writeln("<td bgColor="+parent.sepcolor+" width=5>");

document.writeln("</td>");

document.writeln("<td class=text bgcolor="+parent.color8+"><br><B>color8="+parent.color8+"</B><br><br>");

writecolors();

document.writeln("</td>");

document.writeln("</table>");
document.writeln("</td></tr></table></form></center>");

document.writeln("<br /><br /><center><form method=post name=collect action=index.php?module=Xanthia&amp;type=admin&amp;func=updateColors><table CELLPADDING=5 cellspacing=0 border=0 width=96%>");
document.writeln("<tr align=center><td>");
document.writeln("<input type=hidden name=bgcolor value="+parent.colorback+">");
document.writeln("<input type=hidden name=color1 value="+parent.color1+">");
document.writeln("<input type=hidden name=color2 value="+parent.color2+">");
document.writeln("<input type=hidden name=color3 value="+parent.color3+">");
document.writeln("<input type=hidden name=color4 value="+parent.color4+">");
document.writeln("<input type=hidden name=color5 value="+parent.color5+">");
document.writeln("<input type=hidden name=color6 value="+parent.color6+">");
document.writeln("<input type=hidden name=color7 value="+parent.color7+">");
document.writeln("<input type=hidden name=color8 value="+parent.color8+">");
document.writeln("<input type=hidden name=sepcolor value="+parent.sepcolor+">");
document.writeln("<input type=hidden name=text1 value="+parent.colortext1+">");
document.writeln("<input type=hidden name=text2 value="+parent.colortext2+">");
document.writeln("<input type=hidden name=link value="+parent.colorlink+">");
document.writeln("<input type=hidden name=vlink value="+parent.colorvlink+">");
document.writeln("<input type=hidden name=hover value="+parent.colorhover+">");
document.writeln("<input type=hidden name=skin value=<?php echo $skin;?>>");
document.writeln("<input type=hidden name=paletteid value=<?php echo $paletteid;?>>");
document.writeln("<input type=hidden name=authid value=<?php echo $authid?>>");
document.writeln("Palette Name:&nbsp;&nbsp;<input type='text' name='palname' id='palname' value='<?php echo $palname?>' size='20' maxlength='20'>");
document.writeln("<br /><br />");
document.writeln("<a href='javascript: submitForm()'>Submit these colors</a>");
document.writeln("</tr></td></table></form></center>");
// -->

</SCRIPT>
</BODY>

</HTML>

