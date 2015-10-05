<?php 

// ini_set('display_errors', 1); 
// ini_set('log_errors', 1); 
// ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
// error_reporting(E_ALL);

	require_once "../phpClasses/RxHelper.php";
	require_once "../phpClasses/RxObject.php";

	$num1 = getPost('num1', 0);
	$num2 = getPost('num2', 0);
	$rxString = getPost('rxString', null);
	//$kString = $_REQUEST['kString'];
	$meridian1 = getPost('meridian1', 180);
	$meridian2 = getPost('meridian2', fixAxis($meridian1 + 90));
	$bgImage = getPost('bgImage', );
	$crossImage = getPost('crossImage',  "http://www.eyedock.com/images/crosses/cross");
	$totalSize = getPost('totalSize', 130);
	$crossSize = getPost('crossSize', $totalSize * .8);	
	$label = intval (getPost('label', 0) ; //0= no label, 1=label in one cyl type 2= plus/minus cyl labels
	$round = getPost('round', .12);
	//echo $label;
	
	$gutter = ($totalSize - $crossSize ) / 2;

	//get the numbers and meridians if an Rx string is passed
	if (isset($rxString) ){
		$rxObject = rxStringBreaker($rxString);
		$rxObject->round = $round;
		$num1 = $rxObject -> sphP();
		$num2 = $rxObject -> sphP() + $rxObject -> cylP();
		$meridian1 = $rxObject -> axisP();
		if ($label > 0) $niceString1 = $rxObject->prettyString();
		if ($rxObject->isPlusCyl() ) {
			if ($label > 1) $niceString2 = $rxObject->prettyStringMinus();
		} else {
			if ($label > 1) $niceString2 = $rxObject->prettyStringPlus();
		}
	}
	
	//an url to the optical cross images (it will be completed below - we need to calculate the angle to get the correct image)

	
	//find the closest 10 deg increment (for the image)
	$imageAngle = fixAxis($meridian1);
	$imageAngle = round($imageAngle / 10) * 10;
	if ($imageAngle > 90) $imageAngle -= 90;
	//echo $imageAngle;


	$radAngle1 = $meridian1 * (M_PI/180);
	$radAngle2 = $meridian2 * (M_PI/180);

	$x1 = (cos($radAngle1 ) * ($crossSize/2 + $gutter)) +  $crossSize/2;
	$y1 = -(sin($radAngle1 ) * ($crossSize/2 + $gutter/2)) + $crossSize/2 + ($gutter/2);
	$x2 = (cos($radAngle2 ) * ($crossSize/2 + $gutter)) +  $crossSize/2;
	$y2 = -(sin($radAngle2 ) * ($crossSize/2 + $gutter/2)) + $crossSize/2 + ($gutter/2);
	
	$canvasCSS = "position: relative; height:" . $totalSize . "px; width: " . $totalSize . " px;";
	$crossCSS = "width: " . $crossSize . "px; height: " . $crossSize . "px; margin:" . $gutter . " px";
	$labelCSS = "width: 40px; height: 25px; position: absolute; text-align: center;";
	
	$num1CSS = $labelCSS . "top: " . $y1 . " px; left: " .  $x1 . "px; " . alignText($x1); 
	$num2CSS = $labelCSS . "top: " . $y2 . " px; left: " .  $x2 . "px; " . alignText($x2); 
	$num1aCSS = $labelCSS . "bottom: " . ($y1 - $gutter/2) . " px; right: " .  $x1 . "px; " . alignText(-$x1); 
	$num2aCSS = $labelCSS . "bottom: " . ($y2 - $gutter/2) . " px; right: " .  $x2 . "px; " . alignText(-$x2); 
	
	
	$html = "<div style='$canvasCSS'>";
	$html .= "<img style='$crossCSS' src='" . $crossImage  . $imageAngle . ".png" . "' />";
	$html .= "<div style='$num1CSS' >" . numToDiopterString ($num1, 1) . "</div>";
	$html .= "<div style='$num2CSS' >" . numToDiopterString ($num2, 1)) . "</div>";
	$html .= "<div style='$num1aCSS' >" . numToAxisString($meridian1) . "</div>";
	$html .= "<div style='$num2aCSS' >" . numToAxisString($meridian2) . "</div>";
	
	//add the labels, if wanted
	if (isset($niceString1) ) {
		$css = "width: $totalSize px; text-align: center";
		$html .= "<div class='opticalCrossLabel' style='$css'>". $niceString1 . "</div>";
		if (isset($niceString2) )
			$html .= "<div class='opticalCrossLabel' style='$css'>". $niceString2 . "</div>";
		
	}
	$html .= "</div>";
	echo $html;
	
	function alignText ($val) {
	return;
		$midpoint = $crossSize / 2;
		echo (abs($val) - $midpoint);
		if ((abs($val) - $midpoint) <30 ) return "text-align: center";
	}
	
//a helper function
function getPost($key, $default) {
    if (isset($_POST[$key])
        return $_POST[$key];
    return $default;
}