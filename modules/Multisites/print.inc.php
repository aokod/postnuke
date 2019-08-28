<?php

if (defined('WHERE_IS_PERSO')) {
	global $themesarein;
	
	$Default_Theme = pnConfigGetVar('Default_Theme');
	
	if (pnUserLoggedIn()) { 
		$thistheme = pnUserGetTheme();
		if (isset ($theme))	{
			$thistheme=$theme;
		}
	
		if (@file(WHERE_IS_PERSO."themes/".$thistheme."/theme.php")) { 
			$themesarein = WHERE_IS_PERSO;
		} else { 
			$themesarein = "";
		}			
	
		if(!$file=@opendir($themesarein."themes/$thistheme")) { 
			$ThemeSel = $Default_Theme; 
		} else { 
			$ThemeSel = $thistheme; 
		}
	} else {
		$ThemeSel = $Default_Theme;
		if (@file(WHERE_IS_PERSO."themes/".$ThemeSel."/theme.php")) { 
			$themesarein = WHERE_IS_PERSO;
		} else { 
			$themesarein = "";
		}			
	}
} else {
    die ("You can't access this file directly...");
}

?>