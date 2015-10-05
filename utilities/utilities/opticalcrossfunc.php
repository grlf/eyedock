<?php 

//  ini_set('display_errors', 1); 
// ini_set('log_errors', 1); 
// ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
// error_reporting(E_ALL);

require_once($_SERVER['DOCUMENT_ROOT'] . "/utilities/phpClasses/RxHelper.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/utilities/phpClasses/RxObject.php");

function getCrossFromParams($params){
	extract ($params);	
	
	//if (!isset($round) ) $round = .12;
	if (!isset($totalSize) ) $totalSize = 140;
	if (!isset($crossSize) ) $crossSize = $totalSize * .8;
	$halfCross = $crossSize / 2;
	$gutter = ($totalSize - $crossSize ) / 2;
	
	// label: 0= no label, 1=label in given cyl type; 2= both +/- cyl;  
	//3= if minus show minus, if plus show minus and plus
	if (!isset($label) ) $label = 0; 
	if(!isset($doVertex) ) $doVertex = null;
	if(!isset($doSphEquiv) ) $doSphEquiv = null;
	
	
	if (!isset($rxString) && !isset($kString) ) return null;

	//get the numbers and meridians if an Rx string is passed
	if (isset($rxString) ) {
	
		$rxObject = rxStringBreaker($rxString);
	
		if ($doVertex == 1) $rxObject = $rxObject->diffVertex(0);

		if ($doSphEquiv == 1)  {
			$rxObject = rxStringBreaker($rxObject->sphericalEquivalent());
		}
		$num1 = numToDiopterString ( $rxObject -> sphP(), 1);
		$num2 = numToDiopterString ( $rxObject -> sphP() + $rxObject -> cylP(), 1);
		$meridian1 = $rxObject -> axisP();
	
	}
	
	if (isset ($kString) ){
		//	echo "string: " . $kString;

		$kObject = kStringBreaker($kString);
		$num1 = numToDiopterString ($kObject ->pwr1D(), 0) . "<br>(" . numToMMString($kObject ->pwr1mm() ) . ")";
		$num2 = numToDiopterString ($kObject ->pwr2D(), 0) . "<br>(" . numToMMString($kObject ->pwr2mm() ). ")";;
		$meridian1 = $kObject -> meridian1;
		$meridian2 = $kObject -> meridian2;
		
		
		if ($label > 0) $niceString1 = $kObject -> prettyStringD();
		if ($label == 2) $niceString2 = $kObject -> prettyStringMM();
	}

	
	if ($label > 0 && isset($rxString)  ) {
		if ($label == 1) {
			$niceString1 = ($doVertex == 1)?$rxObject->prettyStringMinus(): $rxObject->prettyString();
		}
		if ($label == 2) {
			$niceString1 = $rxObject->prettyStringPlus();
			$niceString2 = $rxObject->prettyStringMinus();
		}
		if ($label == 3) {
			$plusCyl = $rxObject->isPlusCyl(); //the cyl type the original Rx was given in
			$niceString1 = $rxObject->prettyStringMinus();
			if ($plusCyl == 1) $niceString2 = "(" . $rxObject->prettyStringPlus() . ")";
		}
	}
	
	

	
	if (isset($bgImage) ) {
		switch ($bgImage) { 
			case "lens":
				if (isset($rxObject) && $rxObject ->isToric() == 1){
					$image = "/images/crosses/toric_lens.png"; 
				} else {
					$image = "/images/crosses/sphere_lens.png"; 
				}
				break; 
			case "spherelens": 
				$image = "/images/crosses/sphere_lens.png"; 

				break; 
			case "toriclens":
				$image = "/images/crosses/toric_lens.png"; 
				break; 
			default: 
				$image = $bgImage; 
		}
	}
	
	//start the html and CSS we'll be returning
	$html = "";
	
	if (isset($header) ) $html .= "<div class='opticalCrossLabel' style='font-weight:bold; text-align: center; margin: .5em;'>$header</div>";
	$canvasCSS = "position: relative; height:" . $totalSize . "px; width: " . $totalSize . "px;";
	
	if (isset($image) ) $canvasCSS .= "background-image: url(" . $image . "); background-repeat:no-repeat;background-position:center; ";

	$html .= "<div style='$canvasCSS'>";
	$labelCSS = "width: 40px; height: 25px; position: absolute; text-align: center;";	
	
	//echo("toric " . $rxObject ->isToric()  );
	
	//don't need to worry about optical crosses if the lens is spherical
	if (isset ($rxObject) && $rxObject ->isToric() == 0) {
		// a spacer div
		 $html .= "<div style='margin: 0 " . $gutter . "px " . ($gutter *2) ."px; width: " . $crossSize . "px; height: " . $crossSize . "px'>&nbsp;</div>";
		 $html .= "<div style=' " . $labelCSS . " ; top: " . ($halfCross + 4) . "px; left: " . ($halfCross - 8) . "px; '>";
		 $html .= numToDiopterString ($num1, 1) ;
		 $html .= "</div>";
		 
	} else {
	
		//toric lenses

		//an url to the optical cross images (it will be completed below - we need to calculate the angle to get the correct image)
		if (!isset($crossImage) ) $crossImage = "http://www.eyedock.com/images/crosses/cross";


		if (!isset($meridian2) ) $meridian2 = fixAxis($meridian1 + 90);

		//find the closest 10 deg increment (for the image)
		$imageAngle = fixAxis($meridian1);
		$imageAngle = round($imageAngle / 10) * 10;
		if ($imageAngle > 90) $imageAngle -= 90;
		//echo $imageAngle;


		$radAngle1 = $meridian1 * (M_PI/180);
		$radAngle2 = $meridian2 * (M_PI/180);

		$x1 = intval ( (cos($radAngle1 ) * ($halfCross + $gutter)) +  $halfCross ) -6;
		$y1 = intval ( -(sin($radAngle1 ) * ($halfCross + $gutter)) + $halfCross + ($gutter/2) );
		$x2 = intval (  (cos($radAngle2 ) * ($halfCross + $gutter)) +  $halfCross ) - 6;
		$y2 = intval (  -(sin($radAngle2 ) * ($halfCross + $gutter)) + $halfCross + ($gutter/2) );


		$crossCSS = "width: " . $crossSize . "px; height: " . $crossSize . "px; margin:" . $gutter . "px";


		$num1CSS = $labelCSS . "top: " . $y1 . "px; left: " .  $x1 . "px; "; 
		$num2CSS = $labelCSS . "top: " . $y2 . "px; left: " .  $x2 . "px; "; 
		$num1aCSS = $labelCSS . "bottom: " . ($y1 - $gutter/2) . "px; right: " .  $x1 . "px; "; 
		$num2aCSS = $labelCSS . "bottom: " . ($y2 - $gutter/2) . "px; right: " .  $x2 . "px; "; 


		
		$html .= "<img style='$crossCSS' src='" . $crossImage  . $imageAngle . ".png" . "' />";
		$html .= "<div class='cross_num1' style='$num1CSS' >" . $num1 . "</div>";
		$html .= "<div class='cross_num2' style='$num2CSS' >" . $num2 . "</div>";
		$html .= "<div class='cross_meridian1' style='$num1aCSS' >" . numToAxisString($meridian1) . "</div>";
		$html .= "<div class='cross_meridian2' style='$num2aCSS' >" . numToAxisString($meridian2) . "</div>";

	
	} //  end toric lenses / crosses
	
	//add the labels, if wanted
	if (isset($niceString1) ) {
		$css = "width: $totalSize px; text-align: center";
		$html .= "<div class='opticalCrossLabel' style='$css'>". $niceString1 . "</div>";
		if (isset($niceString2) )
			$html .= "<div class='opticalCrossLabel' style='$css'>". $niceString2 . "</div>";

	}
	
	if ($doVertex == 1) $html .= "<div class='opticalCrossLabel' style='$css ;font-size:smaller;opticalCrossLabel margin: .5em;'>(vertexed) </div>";
	
	$html .= "</div>";
	
	
	return $html;
	
	
}

