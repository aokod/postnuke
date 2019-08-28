<?php // $Id: changehome.php 14982 2004-11-27 21:18:42Z markwest $

if (eregi('changehome.php', $_SERVER['PHP_SELF'])) {
	die ("You can't access this file directly...");
}

modules_get_language();

function edithome() 
{
    $Default_Theme = pnConfigGetVar('Default_Theme');

    if (!pnUserLoggedIn()) {
        return;
    }

    include ('header.php');
    OpenTable();
    echo '<h1>'._HOMECONFIG.'</h1>';
    CloseTable();
    
    OpenTable();
    echo '<form action="user.php" method="post"><div>';
	
	if (pnModAvailable('News')) {
		echo _NEWSINHOME.' '._MAX127.' '
    		.'<input type="text" name="storynum" size="3" maxlength="3" value="' . pnVarPrepForDisplay(pnUserGetVar('storynum')) . '" />'
    		.'<br />';
	}
    if (pnUserGetVar('ublockon')==1) {
        $sel = ' checked="checked"';
    } else {
        $sel = '';
    }
    echo '<input type="checkbox" name="ublockon"'.$sel.' />'
    .' '._ACTIVATEPERSONAL
    .'<br />'._CHECKTHISOPTION
    .'<br />'._YOUCANUSEHTML.'<br />'
    .'<textarea cols="80" rows="10" name="ublock">' . pnVarPrepForDisplay(pnUserGetVar('ublock')) . '</textarea>'
    .'<br />'
    .'<input type="hidden" name="op" value="savehome" />'
    .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'
    .'<input type="submit" value="'._SAVECHANGES.'" />'
    .'</div></form>';
    CloseTable();
    include ('footer.php');
}

function savehome()
{
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', 'Attempt to directly update user information - denied');
        pnRedirect('user.php');
		return;
    }

    list($storynum,
         $ublockon,
         $ublock) = pnVarCleanFromInput('storynum',
                                        'ublockon',
                                        'ublock');

    if (pnUserLoggedIn()) {
        $uid = pnUserGetVar('uid');

        if (!empty($ublockon)) {
            $ublockon=1;
        } else {
            $ublockon=0;
        }
		$dbconn =& pnDBGetConn(true);
		$pntable =& pnDBGetTables();
        $column = &$pntable['users_column'];
        $dbconn->Execute("UPDATE $pntable[users]
                          SET $column[storynum]='" . (int)pnVarPrepForStore($storynum) . "',
                              $column[ublockon]='" . (int)pnVarPrepForStore($ublockon) . "',
                              $column[ublock]='" . pnVarPrepForStore($ublock) . "'
                          WHERE $column[uid]='" . (int)pnVarPrepForStore($uid)."'");
        pnRedirect('user.php');
    }
}

switch($op) 
{
    case 'edithome':
        edithome();
        break;
    case 'savehome':
        savehome();
        break;
}

?>