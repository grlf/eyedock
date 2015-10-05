<?php

// 	ini_set('display_errors', 1); 
// ini_set('log_errors', 1); 
// ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
// error_reporting(E_ALL);

include_once "../phpClasses/RxHelper.php";
include_once "../phpClasses/RxObject.php";
include_once "opticalcrossfunc.php";

$rx = $_REQUEST['rx'];
$subscriber = $_REQUEST['subscriber'];

$rxObj = rxStringBreaker($rx);
$sphEquiv =  rxStringBreaker($rxObj->sphericalEquivalent () ) ;
$vertexed = $rxObj->diffVertex(0);
$vString = $vertexed ->prettyStringMinus();
$vSphEquiv = rxStringBreaker($vertexed->sphericalEquivalent () ) ;


$params['rxString'] = $rx;
$params['bgImage'] = "spherelens";
$vParams['rxString'] = $vString;
$vParams['bgImage'] = "lens";

echo "<table><tr><td style='padding: 20px'>";
	echo getCrossFromParams($params);

echo "</td>	<td>";
	echo "<table>";
	echo "<tr><th colspan=2><u>Your Rx</u></th></tr>";

if ($rxObj->isToric() ) {
	echo "<tr><td>Plus Cyl: </td><td> " . $rxObj->prettyStringPlus () . "</td></tr>";
	echo "<tr><td>Minus Cyl: </td><td> " . powerLink($rxObj->prettyStringMinus () ). "</td></tr>";
	echo "<tr><td>Sph equiv: </td><td> " . powerLink($sphEquiv->prettyString() ). "</td></tr>";
	echo "<tr><td>Toric Type: </td><td> " . $rxObj->toricType() . "</td></tr>";
} else {
	echo "<tr><td>Rx: </td><td> " . $rxObj->prettyString () . "</td></tr>";
	echo "<tr><td>Type: </td><td> Spherical</td></tr>";
}
echo "</table></td>";

echo "</td></tr>";

echo "<tr><td style='padding: 20px'>";
echo getCrossFromParams($vParams);
echo "</td>";

echo "<td><table>";
	echo "<tr><th colspan=2><u>Vertexed (12mm->cornea)</u></th></tr>";
	echo "<tr><td>Vertexed Rx: </td><td> " . powerLink($vString ). "</td></tr>";
	echo "<tr><td>Sph equiv: </td><td> " . powerLink($vSphEquiv->prettyString()) . "</td></tr>";

echo "</table></td></tr>";

echo "</table>";

if ($subscriber == 1) {
	echo "*click a link to search for soft lenses with that power.";
} else {
	echo "<span style='color:red'>Members can easily search for lenses with these powers. <br/>Consider supporting us by <a href='/amember/member.php?tab=add_renew'>subscribing</a></span> !"; 
	
}



function powerLink($val) {
	global $subscriber;
	$r = "";
	if ($subscriber == 1) $r .= "<a href='http://www.eyedock.com/index.php?option=com_pnlenses#view=list&clRx%5B%5D=" . urlencode($val) . "'>";
	$r .= $val;
	if ($subscriber == 1) $r .= "</a>";
	return $r;
}