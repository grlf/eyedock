<?php 
// 
// 	ini_set('display_errors', 1); 
// ini_set('log_errors', 1); 
// ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
// error_reporting(E_ALL);


	require_once("opticalcrossfunc.php");
	
	if (! isset($_REQUEST['rxString']) ) echo "Please supply a valid refraction";

	$lensCross['rxString'] = $_REQUEST['rxString'];
	$lensCross['label'] = !isset($_REQUEST['label']) ? 1 : $_REQUEST['label'];
	$lensCross['bgImage'] = !isset($_REQUEST['bgImage']) ? "lens" : $_REQUEST['bgImage'];
	$lensCross['doVertex'] = !isset($_REQUEST['doVertex']) ? null : $_REQUEST['doVertex'];
	$lensCross['header'] = !isset($_REQUEST['header']) ? "" : $_REQUEST['header'];
	//echo $lensCross['rxString'];
	echo getCrossFromParams ($lensCross);