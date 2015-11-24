<?php

function smarty_function_headscript_incentives($params, &$smarty)
{
    extract($params);
	unset($params);
	global $additional_header;
	$additional_header[] = '<script type="text/javascript" src="modules/Tools/pnjavascript/mod_headscript_incentives.js"></script>';
}

?>
