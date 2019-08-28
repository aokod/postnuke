/************************/
function saveBefore()
{
  actualize()
  var arr= isNeedSave()
  if(!arr) return
  
  savetoClipboard(arr)
  alert('Your work is now in clipboard.\n You can recover it later with button "content recover".')
}


function isNeedSave()
{
   if(!document.forms || document.forms.length==0) return;

   var fidx, el;
   var oForm, strx1='', afield=null, FIDx ;

   for(fidx=0; fidx<document.forms.length; fidx++)
    {
	 oForm= document.forms[fidx]
     for(var i=0; i<oForm.elements.length; i++)
     {
      el= oForm.elements[i]
      if(el.type!='text' && el.type!='textarea' && el.type!='hidden') continue

      FIDx= fidx +'VDevID'+ el.name
      if(!afield && el.type=='hidden' && document.frames[FIDx]) afield= document.frames[FIDx]
	  var temp= el.value
	  temp= temp.replace(/\r/g,'');	temp= temp.replace(/\n/g,'&#13;');
      strx1 += temp + SYMBOLE ;
     }
	}
   strx1 += SYMBOLE ;

   if(!afield) return;

   var arr= new Array(afield,strx1)

   return arr;

}


function savetoClipboard(arr)
{
   var afield= arr[0];
   var strx1= arr[1];

   var strx2= afield.document.body.innerHTML
   afield.document.body.innerText= strx1 ;

   var rng= afield.document.body.createTextRange()
   rng.execCommand('SelectAll')
   rng.execCommand("Copy");

   afield.document.body.innerHTML=strx2;
}





function SmartcardData() // insert from clipboard
{
  if(!document.forms || document.forms.length==0) return ;

  var fidx, oForm, el , objF=null ;
  var FIDx, linex, lidx=0;
  for(fidx=0; fidx<document.forms.length; fidx++)
	{
	 oForm= document.forms[fidx]
	 for(var i=0; i<oForm.elements.length; i++)
	  {
       el= oForm.elements[i]
       if(el.type!='hidden') continue
        
       FIDx= fidx +'VDevID'+ el.name
	   if(document.frames[FIDx]){ objF=document.frames[FIDx]; break;}
     } // end for i
	} // end for fidx

  if(!objF) return;

  objF.document.body.innerText=''
  var s=objF.document.body.createTextRange()
  s.execCommand('Paste')
  var cbstr= objF.document.body.innerText
  objF.document.body.innerText=''

  var cbArr= cbstr.split(SYMBOLE);
  for(fidx=0; fidx<document.forms.length; fidx++)
	{
	 oForm= document.forms[fidx]
	 for(var i=0; i<oForm.elements.length; i++, linex='')
	  {
       el= oForm.elements[i]
       if(el.type!='text' && el.type!='textarea' && el.type!='hidden') continue
    
	   linex= cbArr[lidx++];
    
       FIDx= fidx +'VDevID'+ el.name
	   if(el.type=='hidden' && document.frames[FIDx] && linex) initDefaultOptions2(linex,FIDx)
	   else if(el.type!='hidden') el.value= linex.replace(/&#13;/g,"\n");
     } // end for i
	} // end for fidx
  
}




function initDefaultOptions2(linex,FIDx)
{
  var oFrame= document.frames[FIDx]
  var oStyle= oFrame.document.body.style

   // remove old Style
  var oSS= CSS[FIDx]
  if(oSS) for(var i=0; i<oSS.rules.length; i++) oSS.removeRule(i);
  CSS[FIDx]= null 

  var retArr= new Array();

  retArr= DefaultOptions(linex);

  oStyle.fontFamily=retArr[0]
  oStyle.fontSize=retArr[1]
  oStyle.color=retArr[3]
  oStyle.backgroundColor=retArr[2]
  oStyle.backgroundImage= "url("+retArr[4]+")"
  CSS[FIDx]= oFrame.document.createStyleSheet(retArr[5])
  FACE[FIDx]= retArr[0];
  SIZE[FIDx]= retArr[1];
  BCOLOR[FIDx]= retArr[2];
  COLOR[FIDx]= retArr[3];
  BIMAGE[FIDx]= retArr[4];
  var conts= retArr[6].replace(/&#13;/g,"\n")
  conts= conts.replace(/&#39;/g,"\'")
  oFrame.document.body.innerHTML= conts;
}
