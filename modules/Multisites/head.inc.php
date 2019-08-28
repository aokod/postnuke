<?php

if (defined('WHERE_IS_PERSO')) {
	global $themesarein;
	if (pnUserLoggedIn()) {
		$thistheme = pnUserGetTheme();
		if (isset($theme)) {
			$thistheme=$theme;
		}
	} else {
		$thistheme = pnConfigGetVar('Default_Theme');
		if (isset($theme)) {
			$thistheme=$theme;
		}
	}
	
	if (@file(WHERE_IS_PERSO."themes/".$thistheme."/theme.php")) { 
		$themesarein = WHERE_IS_PERSO;
	} else { 
		$themesarein = "";
	}			
} else {
    die ("You can't access this file directly...");
}

?>