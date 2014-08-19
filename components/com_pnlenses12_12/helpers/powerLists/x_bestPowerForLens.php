<?php

include_once( JPATH_COMPONENT.DS.'powerLists/makePowerList.php' );

//takes a lensID and a refraction and, considering the parameters available for that lens, calculates the best lens power
function getBestPowerForLens ($params) {

	if(isset($params['lensID'])) $lensID 	  = $params['lensID']; 
	$refraction   = $params['refraction']; 
	
	//get an array of lens powers
	$lensPowers = getPowerArrayForLens ($lensID);
	$lensPowerArray = $lensPowers['body'];
	
	//get some information about the lens
	$lensData = getLensData($lensID); 
	$toric = $lensData['body'][0]['toric'];
	
	$resultArray = getBestCLPowerForParams ($refraction, $lensPowerArray, $toric)  ;
	

	return $resultArray;
}


//returns an array of lens powers - it gets an array of lens powers.  then it runs the array through the makepowerlist function to get a large multidimensional array of all the available powers for that lens
function getPowerArrayForLens ($lensID) {
	
	
	$sql = "
	SELECT *
	FROM pn_lenses_powers
	WHERE lensID = $lensID 
	";
		
	$results = doQuery ($sql); 
	
	//print_r($results);
	
	//$returnArray = array();
	
	 if ($results['head']['status'] == 1 ) {
	 		$powerLists = makeLists ($results['body']);
	 		
	 		//print_r($powerLists);
	 		
	 		if ($powerLists) {
	 			$returnArray = Array(
			'head' => Array(
				'status' 		=> '1',
				'error_number'	=> '',
				'error_message' => ''
				),
			'body' => $powerLists[$lensID]
		);
	 		
	 		} 
	 } 
	 
	 if (!isset($returnArray)) {
	 	$returnArray = Array(
			'head' => Array(
				'status' 		=> '0',
				'error_number'	=> '404',
				'error_message' => 'Data not found'
				),
			'body' => Array ()
		);
	 }
	 
	// print_r($returnArray);
	 
	 return $returnArray;
	
}

