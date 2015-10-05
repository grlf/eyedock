<?php
define('EYEDOCK_SEARCH_LIB_DIR', dirname(__FILE__) . '/lib');

require EYEDOCK_SEARCH_LIB_DIR . '/prepare.php';

header("Cache-Control: no-cache, must-revalidate");

if($_GET['mode'] == 'get_company_lenses'){

	$id = abs(intval($_GET['id']));

	$lenses = eyedock_get_company_lenses($id);

	ed_output_register('company_lenses', $lenses);

	eyedock_search_display('company_lenses');

}elseif($_POST['mode'] == 'parameters_search'){

	$criteria = $_POST;

	unset($criteria['mode']);

	$lenses = eyedock_get_parameters_lenses($criteria);

	ed_output_register('parameters_lenses', $lenses);

	eyedock_search_display('parameters_lenses');

}elseif($_POST['mode'] == 'advanced_search'){

	$criteria = $_POST;

	unset($criteria['mode']);

	$lenses = eyedock_get_parameters_lenses($criteria);

	ed_output_register('parameters_lenses', $lenses);

	eyedock_search_display('advanced_lenses');

}elseif($_GET['mode'] == 'get_advanced_search'){

	eyedock_search_display('advanced_search');

}elseif($_GET['mode'] == 'get_compare_lenses'){

	eyedock_search_display('compare_lenses');

}elseif($_POST['mode'] == 'get_lenses_data'){

	$productids = $_POST['productids'];

	$lenses = eyedock_get_lenses($productids);

	ed_output_register('lenses', $lenses);

	eyedock_search_display('compare_lenses_display');

}elseif($_POST['mode'] == 'search_lenses'){

	$str = trim(stripslashes($_POST['str']));

	$lenses = eyedock_search_lenses($str);

	ed_output_register('lenses', $lenses);

	eyedock_search_display('compare_lenses_display');

}elseif($_GET['mode'] == 'lens_detail'){

	$id = abs(intval($_GET['id']));

	$lens = eyedock_lens_detail($id);

	ed_output_register('lens', $lens);

	eyedock_search_display('lens_detail');

}else{

	$companies = eyedock_get_companies();

	ed_output_register('companies', $companies);

	eyedock_search_display('main');
}
?>
