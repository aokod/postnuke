<?php

if (defined('WHERE_IS_PERSO')) {
	$handle=opendir(WHERE_IS_PERSO.'themes');
	
	while ($file = readdir($handle)) {
		if ((!ereg("[.]",$file)) ) {
			if($file != "CVS") {				   
				$themelist .= "$file ";
			}
		}
	}
	closedir($handle);
} else {
    die ("You can't access this file directly...");
}

?>