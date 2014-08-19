<?php
/*
ini_set('display_errors', 1); 
ini_set('log_errors', 1); 
ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
error_reporting(E_ALL);
*/


include_once "RxObject.php";
include_once "RxHelper.php";
include_once "VAHelper.php";	


	
function getBestCLPowerForParams ($refraction, $lensPowerArray, $toric){

	//print_r ($lensPowerArray);
	//echo "<br/>$refraction<br/>";	
	
	//get some info about the desired power
	$mr = rxStringBreaker($refraction); // break it down into an Rx object

	$bestCLrx = $mr->diffVertex(0); //vertex to the corneal plane
	
	$bestSph   = $bestCLrx -> sphM();
	$bestCyl   = $bestCLrx -> cylM();
	$bestAxis = $bestCLrx -> axisM();
	
	$targetSph = $bestSph;
	
	if($toric == "") {
		if ($bestCyl <= -.75) $toric = 1;
		if ($bestCyl > -.75) $toric = 0;
	}

	
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
			//echo $rxArray['sphere'] ;
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
		
		
	} else if ($toric ==1) {
		//if it IS a toric lens...
		foreach ($lensPowerArray as $paramList){
			$rxArray = array();
			
			//if the parameters are not listed as an array (but are still in a comma-separated list), explode it into an array
			if (!is_array($paramList['sphere']) ) $paramList['sphere'] = explode(",", $paramList['sphere']);
			if (!is_array($paramList['axis']) ) $paramList['axis'] = explode(",", $paramList['axis']);
			if (!is_array($paramList['cylinder']) ) $paramList['cylinder'] = explode(",", $paramList['cylinder']);
			

			//first, find the closest cyl power. 
			$cyl = closestValue($paramList['cylinder'], $bestCyl);
			//echo "<br/>".$cyl;
			// If the closest $cyl is less than the $bestCyl the $targetSph will need to be adjusted 
			//echo "<br/>targetSphere1: $targetSph";
			$adjSph = $targetSph + ($bestCyl - $cyl) / 2;
			//echo "<br/>adjSph: $adjSph";
			$rxArray['sphere'] = closestValue($paramList['sphere'], $adjSph);
			$rxArray['cylinder'] = $cyl;
			$rxArray['axis'] = closestValue($paramList['axis'], $bestAxis);
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
						
			array_push($bestRxArray, $rxArray);
		}
	}
	
	//filter out the outliers
	$bestRxArray = removeVAOutliers($bestRxArray);
	
	

	//print_r ( $bestRxArray);
	//echo "<p> </p>";
	return $bestRxArray;


}


function closestValue ($arr, $search)
{
   $closest = null;
   //echo "<p>search: ".$search;
   
      //if searching for an axis, we need to take consider that axis 180 is the same as axis 0 (when we're calculating which is closest).
   if (in_array(180, $arr)) $arr[] = 0;
   
   
   foreach($arr as $item) {
   		//echo "<br/>item: $item";
   		//echo "<br/>closest: $closest";
   		//echo "<br/>abs(search - closest): " . abs($search - $closest);
   		//echo "<br/>abs(item - search): " . abs($item - $search);
   		
      if($closest == null || abs($search - $closest) > abs($item - $search)) {
         $closest = $item;
        // echo "*";
      }
     // echo "<br/>----";
   }
   //echo"</p>";
   
   if ($closest == "0" && in_array(180, $arr) ) $closest = 180;
   return $closest;
}


//remove outliers (for example, we don't need to include the variation of a lens that only comes in plus powers if we want a minus lens - it will have much worse VA than the others. So, look at the VA for each lens and eliminate the ones that are way out there.

function removeVAOutliers($lensesArray) {

	//the array to return
	$returnArray = array();
	
	//find the best VA of the bunch
	$bestVA = null;
	foreach ($lensesArray as $lens){
		$va = $lens['va'];
		if ($bestVA == null || $va < $bestVA) $bestVA = $va;
	}
	//compare each lens's VA to the best VA. If it's less more than 12 points away, don't include it
	foreach ($lensesArray as $lens){
		$va = $lens['va'];
		if (abs($va - $bestVA) <12) array_push($returnArray, $lens);
	}
	
	return $returnArray;
}