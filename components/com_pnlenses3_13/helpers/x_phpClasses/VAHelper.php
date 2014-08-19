<?php

include_once "RxObject.php";
include_once "RxHelper.php";	

//accepts an Rx, presumably an uncorrected or residual Rx
//returns a value that is the denominator to 20/20, 20/x etc.
function VAFromCorrection ($rx) {

	$bcva = 15;
	$sphEq = abs($rx->sphericalEquivalent() );
	$theCyl = $rx->cylP();
	$VA = 80;


	if ($sphEq <= 1.5) {
		$VA = ($theCyl*21+10)+((45-$theCyl*4)*$sphEq);
	} else {
		$VA = (($theCyl*21+10)+((42-$theCyl*4)*$sphEq))+(90*($sphEq-1.5));
	}


	$VA=$bcva*(($bcva+$VA)/($bcva+10));
	
	//another little adjustment. why?  why not?
	if ($sphEq > 2) $VA *= 1.3;

	return $VA;
}

function eyeChartVA ($VA) {

	$chartArray = array(15,20,25,30,40,50,60,80,100, 150, 200, 300, 400);
	
	//find the closest value
	$closest = null;
	foreach($chartArray as $item) {
      if($closest == null || abs($VA - $closest) > abs($item - $VA)) {
         $closest = $item;
      }
   }
   
   $return = "20/" . $closest;
   
   //a little tweaking by adding "+" or "-" where appropriate 
   if ($VA <= 35) {
    	$return .= (($VA - $closest) > 2)?"-":"";
    	$return .= (($VA - $closest) < 2)?"+":"";
   } else if ($VA<70) {
   		$return .= (($VA - $closest) > 4)?"-":"";
    	$return .= (($VA - $closest) < 4)?"+":"";
   } else if ($VA > 450) {
   		$return = "< 20/400";
   }
   
   
   return $return;

}

