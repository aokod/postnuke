<?php

/**
 * $Id :  $
 *
 * Type: Function
 * Author: Thomas Pawlitzki
 *
 *@param params['assign'] The name of the variable, default smilies.
 *@param params['type'] if a type is set then only smilies of this type are returned.
 *@return void
 */
function smarty_function_pnbbsmile_smilie_list($params, &$smarty)
{
	extract($params);
	unset($params);

	// some initialization stuff
	$assign = (!empty($assign)) ? $assign : 'smilies';
	$all_smilies = array();
	$active_smilies = array();
	$smilies = array();

	// Gett all Smilies
	$all_smilies = unserialize(pnModGetVar('pn_bbsmile', 'smilie_array'));

	foreach ($all_smilies as $key => $smilie) {
		// Check if the typ od the smilie is the wanted type
		if ($smilie['active'] == 1) {
			$active_smilies[$key] = $smilie;
		}
	}
	// if there is no type then return all active smilies
	if (!isset($type) || $type == "") {
		$smilies = $active_smilies;
	}
	// Get only the smilies with the wanted type
	else {
		// map words to number
		if ($type == "standard") $type = 0;
		else if ($type == "auto") $type = 1;

		foreach ($active_smilies as $key => $smilie) {
			// Check if the typ od the smilie is the wanted type and if the smilie is active
			if ($smilie['type'] == $type && $smilie['active'] == 1) {
				$smilies[$key] = $smilie;
			}
		}
		$smarty->assign($assign, $smilies);
	}

	// Asign the smilies to smarty
	$smarty->assign($assign, $smilies);
	$smarty->assign($assign . '_count', count($smilies));
	return;
}

?>