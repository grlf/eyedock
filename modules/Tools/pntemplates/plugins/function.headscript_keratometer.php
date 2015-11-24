<?php

function smarty_function_headscript_keratometer($params, &$smarty)
{
    extract($params);
	unset($params);
	global $additional_header;
	$additional_header[] = '<script type="text/javascript" src="modules/Tools/pnjavascript/kerato.js"></script>';
	$additional_header[] = '<script type="text/javascript" src="modules/Tools/pnjavascript/mod_headscript_keratometer.js"></script>';
}

?>
