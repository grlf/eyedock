<?php

function smarty_function_headscript_contact_lens($params, &$smarty)
{
    extract($params);
	unset($params);
	global $additional_header;
	$additional_header[] = '<script type="text/javascript" src="modules/Tools/pnjavascript/specObj.js"></script>';
	$additional_header[] = '<script type="text/javascript" src="modules/Tools/pnjavascript/keratObj.js"></script>';
	$additional_header[] = '<script type="text/javascript" src="modules/Tools/pnjavascript/rx.js"></script>';
	$additional_header[] = '<script type="text/javascript" src="modules/Tools/pnjavascript/fullK.js"></script>';
	$additional_header[] = '<script type="text/javascript" src="modules/Tools/pnjavascript/mod_headscript_cl.js"></script>';
}

?>
