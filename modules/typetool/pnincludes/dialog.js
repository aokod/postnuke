function formatDialogF()
{
  var y = screen.height -parseInt('30em')*14 - 30 
  var feature = "font-family:Arial;font-size:10pt;dialogWidth:30em;dialogHeight:27em;dialogTop:"+y
      feature+= ";edge:sunken;help:no;status:no"

  var dialog= QBPATH+'/dialog.html'
  var arr= showModalDialog(dialog, "visual", feature);
  if(arr==null) return ; 

  doFormatF(arr)
  	  
}

function formatDialog()
{
  TXT.focus();
  var caret=TXT.document.selection.createRange()
  TXT.curword=caret.duplicate();
  
  var y = screen.height -parseInt('27em')*14 - 30 
  var feature = "font-family:Arial;font-size:10pt;dialogWidth:30em;dialogHeight:27em;dialogTop:"+y
      feature+= ";edge:sunken;help:no;status:no"

  var dialog= QBPATH+'/dialog.html'
  var arr= showModalDialog(dialog, "", feature);
  if(arr==null) return ;

  doFormat(arr,caret)

}
