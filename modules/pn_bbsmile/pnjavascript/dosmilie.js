// $Id: dosmilie.js 59 2006-09-09 17:48:08Z landseer $

// new function
function AddSmilie(textfieldname, SmilieCode) {
    var SmilieCode;
    var revisedMessage;
    var textfield = document.getElementById(textfieldname);

    if(textfield==null) {
        alert("internal error: unknown textfieldname '" + textfieldname + "'supplied");
        return;
    }

    //
    // for Internet Explorer
    //
    if(typeof document.selection != 'undefined') {
        textfield.focus();
        var range = document.selection.createRange();
        //var insText = range.text;

        range.text = SmilieCode; //aTag + insText + eTag;
        range = document.selection.createRange();
        range.move('character', -SmilieCode.length);
    }
    //
    // for Gecko based browsers
    //
    else if(typeof textfield.selectionStart != 'undefined')
    {

        var start = textfield.selectionStart;
        var end = textfield.selectionEnd;
        var insText = textfield.value.substring(start, end);

        // insert Smilie
        textfield.value = textfield.value.substr(0, start) + SmilieCode + textfield.value.substr(end);

        // adjust cursorposition
        textfield.selectionStart = start + SmilieCode.length;
        textfield.selectionEnd = start + SmilieCode.length;
    }
    //
    // for all other browsers
    //
    else
    {
        // insert at end
        textfield.value = textfield.value + SmilieCode;
    }
    textfield.focus();

}

// old function wrapper
function DoSmilie(SmilieCode) {
    return AddSmilie('post', SmilieCode);
}

// new ShowHide, taken from pnUpper
function getFormObject(name, form) {
    var myobj = null;
    if (document.getElementById) myobj = document.getElementById(name);
    else if (document.all) myobj = document.all[name];
    else myobj = document.forms[form][name];
    if (!myobj) alert('Internal error with field ' + name + '!!');
    else return myobj;
}


function ShowHide(id) {
    var myobj = getFormObject(id, '');
    if (myobj.style) {
        if (myobj.style.display == "none") { myobj.style.display = ""; }
        else { myobj.style.display = "none"; }
    }
    else {
        myobj.visibility = "show";
    }
}


