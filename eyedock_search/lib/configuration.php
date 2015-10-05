<?php

define('EYEDOCK_SEARCH_SQL_HOST', 'mysql.eyedock.com');
//define('EYEDOCK_SEARCH_SQL_DB', 'eyedock_data_new');
define('EYEDOCK_SEARCH_SQL_DB', 'eyedock_data');
define('EYEDOCK_SEARCH_SQL_USER', 'eyedockdatauser');
define('EYEDOCK_SEARCH_SQL_PASS', 'kvBS^VQR');

define('EYEDOCK_SEARCH_TPL_DIR', dirname(__FILE__) . '/templates');
define('EYEDOCK_SEARCH_CSS_DIR', dirname(__FILE__) . '/css');
define('EYEDOCK_SEARCH_JS_DIR', dirname(__FILE__) . '/js');

define('EYEDOCK_LENS_IMG_URL', 'http://www.eyedock.com/modules/Lenses/pnimages/lens_images');

$detailed_popups = array(
	'power' => 'Power value ranges written in parentheses indicate half diopter steps. For example:
 (+12.00) +6.00 to -8.00 (-12.00)
  ...would indicate +6 to -8 in .25D increments, but up to +12 or -12 in .50D increments.',
	'dk' => 'Dk/t is calculated from listed dk and center thickness values. Actual Dk/t will vary.',
	'ct' => 'Center thickness varies by power.  For the majority of manufacturers the listed value indicates the CT at -3.00 D.'
);

ed_output_register('detailed_popups', $detailed_popups);
?>
