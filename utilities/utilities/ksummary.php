<?php

	ini_set('display_errors', 1); 
ini_set('log_errors', 1); 
ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
error_reporting(E_ALL);


//pass a keratometry string in one of these formats:
// 42/43 @ 90
// 42@180 / 43 @ 90
// if needed, make sure to URL encode: "@" = %40, "/" = %2F

include_once "../phpClasses/RxHelper.php";
include_once "../phpClasses/KeratoObject.php";
include_once "opticalcrossfunc.php";

$kerato = $_REQUEST['k']; //escape slash with %2F, @ with %40
//echo $kerato;
//$subscriber = $_REQUEST['subscriber'];

$keratoObj = kStringBreaker($kerato);



$params['kString'] = $kerato;
$params['bgImage'] = "spherelens";
$params['label'] = "2";


echo "<table><tr><td style='padding: 20px'>";
	echo getCrossFromParams($params);

echo "</td>	<td>";
	echo "<table>";
	echo "<tr><th colspan=2><u>Keratometry</u></th></tr>";

//if ($rxObj->isToric() ) {
	echo "<tr><td>Flat K: </td><td> " . $keratoObj->flatK(1,1) . " @ " . $keratoObj->flatKMeridian();
	echo "</td></tr>";
	echo "<tr><td>Steep K: </td><td> "  . $keratoObj->steepK(1,1) . " @ " . $keratoObj->steepKMeridian();
	echo  "</td></tr>";
	echo "<tr><td>Avg K: </td><td> " . $keratoObj->midK(1,1);
	echo " (" .   $keratoObj->midK(1,1,1) . ")";

	echo "</td></tr>";
	echo "<tr><td>Cyl Type: </td><td> " . $keratoObj ->toricType() ."</td></tr>";
	echo "<tr><td>Corneal Cyl: </td><td> " . $keratoObj->cornealCylAndAxis();
	echo "<br>(" . $keratoObj->cornealCylAndAxis(1) . ")";
	echo "</td></tr>";


echo "</table></td>";

echo "</td></tr>";

echo "</table>";
