<?php
/*
ini_set('display_errors', 1); 
ini_set('log_errors', 1); 
ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
error_reporting(E_ALL);
*/

//IF ANY CHANGES MADE HERE CONSIDER CHANGING THE [DUPLICATE] FILE IN THE API: 
// eyedock.com/api/phpClasses/CLHelper.php

include_once "RxObject.php";
include_once "RxHelper.php";
include_once "VAHelper.php";	


	
function getBestCLPowerForParams ($refraction, $lensPowerArray, $toric, $vertex=1){

	//print_r ($lensPowerArray);
	//echo "toric:  $toric <br/>";
	//echo "<br/>$refraction<br/>";	
	
	//get some info about the desired power
	$mr = rxStringBreaker($refraction); // break it down into an Rx object
	//echo $mr->prettyStringMinus();
	$bestCLrx = new RxObject();
	if ($vertex==1) {
		$bestCLrx = $mr->diffVertex(0); //vertex to the corneal plane
	} else {
		$bestCLrx = $mr;
			//echo $bestCLrx->prettyStringMinus();
	}
	

	$bestSph   = $bestCLrx -> sphM();
	$bestCyl   = $bestCLrx -> cylM();
	$bestAxis = $bestCLrx -> axisM();
	
	$targetSph = $bestSph;
	
	/*
	not needed - the lens should be toric or not
	if($toric == "") {
		if ($bestCyl <= -.75) $toric = 1;
		if ($bestCyl > -.75) $toric = 0;
	}
	*/

	
	//an array of lenses rxs that are the closest 
	$bestRxArray = array();
	
	//we'll only want to return the lens that provides the best vision.  For each lens we'll 
	//$bestVA = 10000;
	
	if ($toric == 0) {
		//if it's not a toric lens we'll need the spherical equivalent	
		$targetSph = $bestCLrx -> sphericalEquivalent();
		
		foreach ($lensPowerArray as $paramList){
			$rxArray = array();
			
			//if the parameters are not listed as an array (but are still in a comma-separated list), explode it into an array
			if (!is_array($paramList['sphere']) ) $paramList['sphere'] = explode(",", $paramList['sphere']);
			
			$rxArray['sphere'] = closestValue($paramList['sphere'], $targetSph);
			$rxArray['cylinder'] = null;
			$rxArray['axis'] = null;
			
			$rxArray['baseCurve'] =  $paramList['baseCurve'];
			$rxArray['diameter'] = $paramList['diameter'];
			
			//create an RxObject 
			$clRx = new RxObject($rxArray['sphere'] );			
			$rxArray['fullRx'] = $clRx->prettyStringMinus();				
			$rxArray['bestRx'] = $bestCLrx->prettyStringMinus();
			//the diff between this Rx and the ideal Rx
			$rxdiff = $bestCLrx->subtractRx($clRx ); 
			$rxArray['rxDiff'] = $rxdiff->prettyStringMinus();
			//echo $rxdiff  ->prettyStringMinus();
			$rxArray['va'] = (int) VAFromCorrection($rxdiff);
			$rxArray['chartVA'] = eyeChartVA ($rxArray['va']);
			$rxArray['variation'] = $paramList['variation'];
			
			array_push($bestRxArray, $rxArray);
			
		}
		
		
	} else if ($toric == 1) {
		//if it IS a toric lens...
		foreach ($lensPowerArray as $paramList){
			$rxArray = array();			
			
			//if the parameters are not listed as an array (but are still in a comma-separated list), explode it into an array
			if (!is_array($paramList['sphere']) ) $paramList['sphere'] = explode(",", $paramList['sphere']);
			if (!is_array($paramList['axis']) ) $paramList['axis'] = explode(",", $paramList['axis']);
			if (!is_array($paramList['cylinder']) ) $paramList['cylinder'] = explode(",", $paramList['cylinder']);
			

			//first, find the closest cyl power. 
			$cyl = closestValue($paramList['cylinder'], $bestCyl, 1);


			//$cyl = closestValue($paramList['cylinder'], $bestCyl, 1);
			//echo "<br/>".$cyl;
			// If the closest $cyl is less than the $bestCyl the $targetSph will need to be adjusted 
			//echo "<br/>targetSphere1: $targetSph";
			$adjSph = $targetSph + ($bestCyl - $cyl) / 2;
			//echo "<br/>adjSph: $adjSph";
			$rxArray['sphere'] =  closestValue($paramList['sphere'], $adjSph);

			//$rxArray['sphere'] = closestValue($paramList['sphere'], $adjSph, 1);
			$rxArray['cylinder'] = $cyl;
			$rxArray['axis'] =  closestValue($paramList['axis'], $bestAxis);

			//$rxArray['axis'] = closestValue($paramList['axis'], $bestAxis);
			$rxArray['baseCurve'] =  $paramList['baseCurve'];
			$rxArray['diameter'] = $paramList['diameter'];
			
			//create an RxObject 
			$clRx = new RxObject($rxArray['sphere'], $rxArray['cylinder'], $rxArray['axis'] );
			$rxArray['fullRx'] = $clRx->prettyStringMinus();					
			$rxArray['bestRx'] = $bestCLrx->prettyStringMinus();			
			
			//the diff between this and the ideal Rx
			$rxdiff = $bestCLrx->subtractRx($clRx ); 
			$rxArray['rxDiff'] = $rxdiff->prettyStringMinus();
			$rxArray['va'] = (int) VAFromCorrection($rxdiff);
			$rxArray['chartVA'] = eyeChartVA ($rxArray['va']);
			$rxArray['variation'] = $paramList['variation'];
			
// 			echo "<br/>best Rx: " . $bestCLrx->prettyStringMinus();
// 			echo "<br/>cl Rx " . $clRx->prettyStringMinus();
// 			echo "<br/>va " . (int) VAFromCorrection($rxdiff);
// 			echo "<p> --- </p>";		
// 						
						
			array_push($bestRxArray, $rxArray);
		}
	}
	
	//filter out the outliers
	//$bestRxArray = removeVAOutliers($bestRxArray);
	
	

	//print_r ( $bestRxArray);
	//echo "<p> </p>";
	return $bestRxArray;


}

//$search is the value we're looking for
//$roundDown used to make sure cyl is always undercorrected and not overcorrected

function closestValue ($arr, $search, $roundDown = 0) {

	//if the desired value is in the array, just return it..
	if (in_array($search, $arr) ) return $search;

   $closest = null;
   $smallestVal = null; //we'll keep track of the smallest value in case one isn't found
   //echo "<p>search: ".$search;
   
      //if searching for an axis, we need to take consider that axis 180 is the same as axis 0 (when we're calculating which is closest).
   if (in_array(180, $arr)) $arr[] = 0;

   foreach($arr as $item) {
   		$itemCount ++;
   		//echo "<br/>item: $item";
   		//echo "<br/>closest: $closest";
   		//echo "<br/>abs(search - closest): " . abs($search - $closest);
   		//echo "<br/>abs(item - search): " . abs($item - $search);
   	if ($smallestVal == null || abs($item) < abs($smallestVal) ) $smallestVal = $item;
   		
      if($closest == null || abs($search - $closest) > abs($item - $search)) {
          if ($roundDown == 1 && abs($item) > abs ($search)) continue;
          $closest = $item;
        // echo "*";
      }
     // echo "<br/>----";
   }
   //echo"</p>";
  	if ($closest == null) $closest = $smallestVal;
   if ($closest == "0" && in_array(180, $arr) ) $closest = 180;
   return $closest;
}

