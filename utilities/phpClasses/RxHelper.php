<?php


//
//  Created by Todd Zarwell on 4/18/10.
//  Copyright 2010 EyeDock. All rights reserved.
//

//  ini_set('display_errors', 1); 
// ini_set('log_errors', 1); 
// ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
// error_reporting(E_ALL);


include_once "RxObject.php";
include_once "KeratoObject.php";
include_once "KvalObject.php";



function numToDiopterString ($aNum, $showSign = 0, $round = .25) {

	//echo "anum:" . $aNum;

	$round = ($round>0 && $round<=1)? $round : .25;

	$numberString =  "";
	
	$roundTo = 1/$round;
	
	$aNum = round($roundTo * $aNum)/$roundTo;

	if ($showSign) {
		$numberString = sprintf("%+.2f", $aNum);
	} else {
		$numberString = sprintf("%.2f", $aNum);

	}
	
	return $numberString;
}

function numToMMString ($x) {
	return sprintf("%.2f", $x);
}	


//make sure that this receives a positive value - it doesn't check

function numToAxisString ($aNum) {

	$numberString =  sprintf("%03d", $aNum);
	
	return $numberString;
	
}

function roundToAxis ($aNum, $round) {
	
	$roundTo = 1 / $round;
	$newAxis = fixAxis ($aNum);
	
	//round to the closest 90 or 180 degrees
	if ( ($newAxis<45 && $newAxis >0) || ($newAxis>90 && $newAxis <135) ){
		$newAxis = round($roundTo * $newAxis -.1)/$roundTo;
	} else {
		$newAxis = round($roundTo * $newAxis +.1)/$roundTo;

	}
	return $newAxis;
}



function checkSphereRange ($aNum) {
	if ($aNum < -30 || $aNum >+30) return 0;
	return 1;
}

function checkCylinderRange ($aNum) {
	if ($aNum < -12 || $aNum >+12) return 0;
	return 1;
}

function  checkAxisRange($aNum)  {
	if ($aNum < 0 || $aNum >180) return 0;
	return 1;
}

function checkMMRange ($aNum)  {
	if ($aNum >11.3 || $aNum < 4.8) return 0;
	return 1;
}

function checkKRange ($aNum) {
	if ($aNum >70 || $aNum <25) return 0;
	return 1;
}




function cleanRxString ($rxString) {
	
	//remove whitespace
	$rxString = str_replace(" ", "", $rxString);
		
	//replace words with numbers:
	$rxString = str_replace("plano", "0", $rxString);
	$rxString = str_replace("sph", "", $rxString);
	
	//make sure the first char is a + or - sign
	if ( substr($rxString,0,1) != "+" && substr($rxString,0,1) != "-" ) $rxString = "+" . $rxString;
														
	return $rxString;
	
}

function splitRxString ($rxString) {
	//put breakpoints (:) between the sphere, cyl, and axis
	$rxString = str_replace("+", ":+", $rxString);
	$rxString = str_replace("-", ":-", $rxString);
	$rxString = str_replace("x", ":x", $rxString);

	//remove the breakpoint from the front of the string
	$rxString = substr ($rxString, 1);  

	
	return $rxString;

}


function removeLetters ($numString) {
	
	//NSString *digits = [numString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:@"-.0123456789"] invertedSet]]; 
	//was missing decimal point - tmz added 6/4/10
	$digits = preg_replace( '/[^0-9-.\s]/', '', $numString );

	return $digits;
}

function removeAllButNumbers ($numString) {
	
	//NSString *digits = [numString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:@".0123456789"] invertedSet]]; 
	$digits = preg_replace( '/[^0-9.\s]/', '', $numString );
	return digits;
}

function removeAllButFullKeratoChars ($keratoString) {
	
	//NSString *keratoClean = [keratoString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:@".0123456789/@"] invertedSet]]; 
		$keratoClean = preg_replace( '/[^0-9.\/@\s]/', '', $keratoString );
	return $keratoClean;
}




function fixAxis ($axis) {
	while ($axis<=0) {
		$axis +=180;
	}
	while ($axis>180) {
		$axis -= 180;
	}
	return $axis;
}

function prettyRxString ($sph=0, $cyl=0, $axis=0, $round = .25) {

	//echo "<p>prettystring</p>";

	$axis = fixAxis ($axis);
	
	
	$sphString = numToDiopterString ($sph, 1, $round);
	$cylString = numToDiopterString ($cyl, 1, $round);
	$axisString = "x ". numToAxisString ($axis);
	
	if (abs($sph) < $round/2 ) $sphString = @"plano";
	if (abs($sph) < $round/2 && abs($cyl) < $round/2) return @"plano";
	if (abs($cyl) < $round/2) {
		$cylString = "sph";
		$axisString = "";
	}
	
	return $sphString . " " . $cylString . " " . $axisString;
}


function isVerticalAxis ($axis) {
	$axis = fixAxis($axis);
	if ($axis > 45 && $axis <= 135) return 1;
	return 0;
}


function rxStringBreaker ($rxString) {

	//echo $rxString . "<br>";
	$cleanRxString = cleanRxString ($rxString);
	//echo $cleanRxString;
		
	$splitRxString = splitRxString ($cleanRxString);
	
		
	$rxChunks = explode (":", $splitRxString);
	
	//print_r($rxChunks);
	
	$returnRx = new RxObject();
	
	if ( count($rxChunks) >0) {
			$returnRx->sph = removeLetters($rxChunks[0]);	
		} else {
			$returnRx->sph = 0;
	}
	
	if ( count($rxChunks)  >1) {
		$returnRx->cyl = removeLetters($rxChunks[1] );
	} else {
		$returnRx->cyl = 0;
	}
	
	if (count($rxChunks) >2) {
		$returnRx->axis = removeLetters ($rxChunks[2]);
	} else {
		$returnRx->axis = 0;
	}
	
	 $checkSph = checkSphereRange ($returnRx->sph);
	 $checkCyl = checkSphereRange ($returnRx->cyl);
	 $checkAxis = checkAxisRange ($returnRx->axis);

	if (!$checkSph || !$checkCyl || !$checkAxis) return null;
		
	return $returnRx; 
}



function kStringBreaker ($str) {
	//echo ($str);
	$str = str_replace(" ", "", $str); //remove whitespace

	$returnK = new KeratoObject();
	
	//if just a single number is provided
	if (is_numeric($str) ) {
		$returnK -> num1 = $str ;
		$returnK -> num2 = $str ;
		$returnK -> meridian1 = 90 ;
		return $returnK;
	} 
	
	$kParts = explode ("/", $str);
	
	if (count($kParts) <2) {
		$k1 = $str;
		$k2 = $str;
	} else {
		$k1 = $kParts[0]; //should be 42@90 ..or 42
		$k2 = $kParts[1]; //should be 42@90 ..or 42
	}
	
	
	if (is_numeric( $k1) ){ //if the first half is just a number eg)43
		$num1 = $k1;
	} else {
		$k1parts = explode ("@", $k1);
		if (count($k1parts)>1) {
			$num1 = $k1parts [0];
			$meridian1 = $k1parts [1];
		} else { //didn't contain "@"
			$num1 = intval ($k1);
		}
	}
	
	if (is_numeric($k2 ) ) { //if the second half is just a number eg)43
		$num2 = $k2;
	} else {
		$k2parts = explode ("@", $k2);
		if (count($k2parts)>1) {
			$num2 = $k2parts [0];
			$meridian2 = $k2parts [1];
		} else { //didn't contain "@"
			$num2 = intval ($k2);
		}
	}
	if (!isset($meridian2) ) {
		if (isset($meridian1) ) {
			$meridian2 = fixAxis($meridian1 + 90);
		} else {
			$meridian2 = 90;
		}
	}

	if (!isset($meridian1) ) {
		if (isset($meridian2) ) {
			$meridian1 = fixAxis($meridian2 + 90);
		} else {
			$meridian1 = 180;
		}
	}

	if (!$num2) $num2 = $num1;

	$returnK -> num1 = $num1 ;
	$returnK -> num2 = $num2 ;
	$returnK -> meridian1 = $meridian1 ;
	$returnK -> meridian2 = $meridian2 ;
	return $returnK;
	
}



//commented out because the clunkier function above works better with partial strings ("42", "42/", "42@90/44") ..better for parsing autocompletes
// 
// function kStringBreaker2 ($str) {
// 	//echo "str: " . $str;
// 	$returnK = new KeratoObject();
// 	
// 	// both meridians: 42 @ 90 / 43 @ 180
// 	$regEx1 = "/\s*([.\d]{1,5})\s*(?:@\s*(\d{1,3}))\s*\/\s*([.\d]{1,5})\s*@\s*(\d{1,3})\s*/u";
// 	// one meridian: 42 / 42 @ 180
// 	$regEx2 = "/\s*([.\d]{1,5})\s*\/\s*([.\d]{1,5})\s*@\s*(\d{1,3})\s*/u";
// 	
// 	if (is_numeric($str) ) {
// 		$returnK -> num1 = $str ;
// 		$returnK -> num2 = $str ;
// 		$returnK -> meridian1 = 90 ;
// 	} else 	if (preg_match($regEx1, $str, $matches) ) {
// 		//print_r($matches);
// 		$returnK -> num1 =  $matches[1] ;
// 		$returnK -> num2 =  $matches[3] ;
// 		$returnK -> meridian2 = $matches[4] ;
// 		
// 	} else if (preg_match($regEx2, $str, $matches) ) {
// 		$returnK -> num1 =  $matches[1];
// 		$returnK -> num2 = $matches[2];
// 		$returnK -> meridian2 = $matches[3];
// 	} else {
// 		return null;
// 	}
// 	
// 	return $returnK;
// }

