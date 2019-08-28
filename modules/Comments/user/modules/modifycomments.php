<?php 
// File: $Id: modifycomments.php 15630 2005-02-04 06:35:42Z jorg $

if (eregi('modifycomments.php', $_SERVER['PHP_SELF'])) {
	die ("You can't access this file directly...");
}

modules_get_language();

function editcomm()
{
    if (!pnUserLoggedIn()) {
        return;
    }
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $mode = pnUserGetVar('umode');
    $order = pnUserGetVar('uorder');
    $thold = pnUserGetVar('thold');
    $noscore = pnUserGetVar('noscore');
    $commentmax = pnUserGetVar('commentmax');

    include 'header.php';
    OpenTable();
    echo '<h2>'._COMMENTSCONFIG.'</h2>';
    CloseTable();

    OpenTable();
    echo "<table cellpadding=\"8\" border=\"0\"><tr><td>"
        ."<form action=\"user.php\" method=\"post\"><div>"
        ._DISPLAYMODE.'<br />'
?>
	<select name="umode">
		<option value="nocomments" <?php if ($mode == 'nocomments') { echo 'selected="selected"'; } ?>><?php echo _NOCOMMENTS ?></option>
		<option value="nested" <?php if ($mode == 'nested') { echo 'selected="selected"'; } ?>><?php echo _NESTED ?></option>
		<option value="flat" <?php if ($mode == 'flat') { echo 'selected="selected"'; } ?>><?php echo _FLAT ?></option>
		<option value="thread" <?php if (!isset($mode) || ($mode=="") || $mode=='thread') { echo " selected"; } ?>><?php echo _THREAD ?></option>
    </select>
    <br />
    <?php echo _SORTORDER.'<br />' ?>
    <select name="uorder">
		<option value="0" <?php if (!$order) { echo 'selected="selected"'; } ?>><?php echo _OLDEST ?></option>
		<option value="1" <?php if ($order==1) { echo 'selected="selected"'; } ?>><?php echo _NEWEST ?></option>
		<option value="2" <?php if ($order==2) { echo 'selected="selected"'; } ?>><?php echo _HIGHEST ?></option>
    </select>
    <br />
    <?php echo _THRESHOLD ?>
    <?php echo _COMMENTSWILLIGNORED ?><br />
    <select name="thold">
		<option value="-1" <?php if ($thold==-1) { echo 'selected="selected"'; } ?>>-1: <?php echo _UNCUT ?></option>
		<option value="0" <?php if ($thold==0) { echo 'selected="selected"'; } ?>>0: <?php echo _EVERYTHING ?></option>
		<option value="1" <?php if ($thold==1) { echo 'selected="selected"'; } ?>>1: <?php echo _FILTERMOSTANON ?></option>
		<option value="2" <?php if ($thold==2) { echo 'selected="selected"'; } ?>>2: <?php echo _USCORE ?> +2</option>
		<option value="3" <?php if ($thold==3) { echo 'selected="selected"'; } ?>>3: <?php echo _USCORE ?> +3</option>
		<option value="4" <?php if ($thold==4) { echo 'selected="selected"'; } ?>>4: <?php echo _USCORE ?> +4</option>
		<option value="5" <?php if ($thold==5) { echo 'selected="selected"'; } ?>>5: <?php echo _USCORE ?> +5</option>
    </select><br />
    <?php echo _SCORENOTE ?>
    <br />
    <input type="checkbox" value="1" name="noscore" <?php if ($noscore==1) {  echo 'checked="checked"'; } ?> />
    <?php echo _NOSCORES ?> <?php echo _HIDDESCORES ?>
    <br />
    <?php echo _MAXCOMMENT ?> <?php echo _TRUNCATES ?><br />
    <input type="text" name="commentmax" value="<?php echo $commentmax ?>" size="11" maxlength="11" /> <?php echo _BYTESNOTE ?>
    <br />
    <input type="hidden" name="op" value="savecomm" />
    <input type="submit" value="<?php echo _SAVECHANGES ?>" />
    </div></form></td></tr></table>
<?php
    CloseTable();

    include 'footer.php';
}

function savecomm()
{
	list ($umode, $uorder, $thold, $noscore, $commentmax) = 
	     pnVarCleanFromInput('umode', 'uorder', 'thold', 'noscore', 'commentmax'); 

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (pnUserLoggedIn()) {
        $uid = pnUserGetVar('uid');
        if(isset($noscore) && ($noscore == 1)) {
            $noscore = '1';
        } else {
            $noscore = '0';
        }
        $column = &$pntable['users_column'];
        $query = "UPDATE $pntable[users]
                  SET $column[umode]='".pnVarPrepForStore($umode)."',
                      $column[uorder]='".pnVarPrepForStore($uorder)."',
                      $column[thold]='".pnVarPrepForStore($thold)."',
                      $column[noscore]='".pnVarPrepForStore($noscore)."',
                      $column[commentmax]='".pnVarPrepForStore($commentmax)."'
                  WHERE $column[uid]='".pnVarPrepForStore($uid)."'";
        $dbconn->Execute($query);
    }
    pnRedirect('user.php');
}

if (!isset($noscore)) {
    $noscore = '';
}

switch ($op)
{
    case "editcomm":
	editcomm();
        break;
    case "savecomm":
        savecomm($umode, $uorder, $thold, $noscore, $commentmax);
        break;
}
?>