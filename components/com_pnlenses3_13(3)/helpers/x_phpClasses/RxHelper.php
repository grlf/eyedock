<?php


//
//  Created by Todd Zarwell on 4/18/10.
//  Copyright 2010 EyeDock. All rights reserved.
//

include_once "RxObject.php";


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



function rxStringBreaker ($rxString) {

	
	$cleanRxString = cleanRxString ($rxString);
	
		
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

/*
function isNumeric ($aNum) {
	NSCharacterSet * charSet = [[NSCharacterSet characterSetWithCharactersInString:@".0123456789"]invertedSet];
	NSRange range= [aNum rangeOfCharacterFromSet:charSet];
	if(range.location == NS0tFound) return 1;  //only numbers in string
	return 0;  // string had letters or specail chars
	
}
*/

/*
+(KeratoObject *) kStringBreaker: (NSString *) kString {
	
	float kNum = [ [self removeAllButNumbers: kString] floatValue] ;
	BOOL mmOK = [self checkMMRange:kNum];
	BOOL dOK = [self checkKRange:kNum];
	
	KeratoObject * keratoObject = [ [KeratoObject alloc] init];
	
	if (mmOK || dOK) { 
		keratoObject.kValue = kNum;
		return keratoObject;
	} 
	// if the entered value isn't a number within the diopter or mm range...
		return nil;
}


+(FullKeratoObject *) fullKStringBreaker: (NSString *) fullKString {

	fullKString = [self removeAllButFullKeratoChars :fullKString];
	
	FullKeratoObject * returnKeratoObj =  [ [FullKeratoObject alloc] init];
	KeratoObject * keratoObject1 =  [ [KeratoObject alloc] init];
	KeratoObject * keratoObject2 =  [ [KeratoObject alloc] init];
	
	//check if one number is entered eg) 43.00 may be a spherical cornea.  also check for "/" and "@" signs
	if ( [self isNumeric:fullKString] ) {
		returnKeratoObj.k1 = [self kStringBreaker:fullKString];
		returnKeratoObj.k2 = [self kStringBreaker:fullKString];
		returnKeratoObj.k2meridian = 90;
		return returnKeratoObj;

	}
	

	//string should be in this format: 42.00/43.00@90 ->: split string at the "/"
	NSArray *kChunks = [fullKString componentsSeparatedByString:@"/"];
	
	//must have 2 parts
	if ([kChunks count] != 2) return nil;
	
	keratoObject1 = [self kStringBreaker: [kChunks objectAtIndex:0] ];
	returnKeratoObj.k1 = keratoObject1;
	
	//0w split the second half at the "@"
	NSArray *kChunks2 = [[kChunks objectAtIndex:1] componentsSeparatedByString:@"@"];
	
	//must have 2 parts
	if ([kChunks2 count] != 2) return nil;
	
	keratoObject2 = [self kStringBreaker: [kChunks2 objectAtIndex:0] ];
	returnKeratoObj.k2 = keratoObject2;
	returnKeratoObj.k2meridian = [[kChunks2 objectAtIndex:1]floatValue];
	
	
	BOOL checkK1 = [self checkKRange:keratoObject1.kValue];
	BOOL checkK2 = [self checkKRange:keratoObject2.kValue];
	BOOL checkAxis = [self checkAxisRange:returnKeratoObj.k2meridian];
	
	if (!checkK1 || !checkK2 || !checkAxis) return nil;
	
	
	
	return returnKeratoObj;
}

*/

?>