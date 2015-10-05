<?php

include_once 'parsingUtility.php';
//include_once 'sorter.php';
include_once '../Array2xml.php';
include_once '../powerLists/formatNumberText.php';

//include_once '../phpClasses/RxHelper.php';

// ini_set('display_errors', 1); 
// ini_set('log_errors', 1); 
// ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
// error_reporting(E_ALL);
// ini_set('html_errors', 'On');


   $data   = $_REQUEST;   
   //$action = $_REQUEST['action']; 
   $format = $_REQUEST['format'];
 
  
    $arr = array();
    
    if ($data['bcData']) {
		$bcData = parseMMData ($data['bcData']);
		//print_r($bcData);
		$bcData = array_unique($bcData);
		$arr['numBCs'] = count($bcData);
		$arr['bcall'] = makeAllList ($data['bcData']);
		//$arr['bcall'] = shortestString(implode(", ", $bcData) , makeAllList($data['bcData']) );
    }
    
    if ($data['diamData']) {
		$diamData = parseMMData ($data['diamData']);
		//$diamArray = parseMMData ($diamData);
		$arr['maxdiam'] =  sprintf("%.1f", max($diamData) );
		$arr['mindiam']  = sprintf("%.1f", min($diamData) );
		$arr['diamall'] = makeAllList ($data['diamData']);
		//$arr['diamall'] = shortestString(implode(", ", $diamData), makeAllList($data['diamData']) );
	}
    
	
	if ($data['sphData']) {
		$sphArray = parsePowerData ($data['sphData']);
		$arr['maxplus'] = max($sphArray);
		$arr['maxminus']  = min($sphArray);
	}
	
	 if ($data['cylPwrData']) {
		$cylArray = parsePowerData ($data['cylPwrData']);
		//remember - the cyl values will be minus powers!
		$arr['maxcylpower'] = min($cylArray);
	}
	
	if ($data['cylAxisData'])  {
		$string = $data['cylAxisData'];
		$increment = 90;
		if (preg_match_all("/[0-9]*\s*steps/", $data['cylAxisData'], $matches)){
			foreach ($matches[0] as $steps) {
				$int = parseInt($steps);
				if ($int < $increment) $increment = $int;
			}
		}
		$arr['cylaxissteps'] = $increment;
		$dataArray = parseIntegerData ($data['cylAxisData']);
		$arr['oblique'] = mostObliqueAxis($dataArray );
	}
	
	if ($data['addData']) {
		$addArray = parsePowerData ($data['addData']);
		$arr['maxadd'] = max($addArray);
		//if (! is_numeric($arr['maxadd']) ) $arr['maxadd'] = null;
	}

  
	if (count($arr)>0) $response = makeArray($arr) ;




	
	if (! is_array($response) )
	$response = Array(
			'head' => Array(
			'status' 		=> '0',
			'error_number'	=> '404',
			'error_message' => 'No data found - Please check the format of your data request. '
		),
		'body' => Array ()
	);

	
	

	if ($format =='' ) $format = 'json'; //will return JSON if format not specified
	

		
	switch ($format) {
		case 'xml' :
			header ("content-type: text/xml"); 
    		$xml = new array2xml('root');
    		$xml->createNode( $response );
    		echo $xml;

			break;
			
		case 'json' :

			// Setting up JSON headers 
			header ("content-type: text/json charset=utf-8");

			//Printing the JSON Object 
			echo json_encode($response);
			break;
		
		case 'php' :	

			// Setting up PHP headers 
			header ("content-type: text/php charset=utf-8");

			// Printing the PHP serialized Object
			echo serialize($response);
				
		case 'html' :	

			echo ($response);
		
			break;	
	
	}


	//returns an array with a "400" error: bad request (usually when some piece of data is not provided)
	function badRequest(){
				$errorArray = Array(
				'head' => Array(
				'status' 		=> '0',
				'error_number'	=> '400',
				'error_message' => 'Bad request - There is a syntax error in your URL or some data (such as an ID or a search string) is missing. '
			),
			'body' => Array ()
		);
		return $errorArray;
	}	
	

//----------functions -------------------

//make an array if results OK
function makeArray ($arr) {

		$results = Array(
		'head' => Array (
			'status' => 1
			),
		'body' => $arr
	);
	
	return $results;
}



// -------- utility functions -------

function shortestString ($s1, $s2) {
	return (strlen($s1) > strlen($s2) )?$s2:$s1;
}

function formatNumbersInString ($string){
	preg_replace_callback(
            '/([0-9\.\+-]+)/',
          function($matches){
			return (sprintf("%.1f", $matches[1]) . $matches[2]);
},
            $string);
}

function rangeMaker ($arr) {
	$max = sprintf("%+.1f", max($arr) );
	$min = sprintf("%+.1f",min($arr) );
	$steps = stepFinder($arr);
	if ($steps == 0) {
		return implode(", ", $arr);
	} else {
		return $min . " to " . $max . " in " . $steps . "mm steps";
	}
}

function stepFinder ($arr) {
	$diff = 0;
	$diff1 = $arr[1] - $arr[0];
    for ($i = 1; $i < count($arr); $i++) {
        $diff0 = $arr[i] - $arr[i - 1];
        if ($diff0 != $diff1) break;
    }
	if ($diff0 != $diff1) return 0;
	return $diff0;
}

function mostObliqueAxis ($arr) {
	$mostOblique = 0;
	foreach ($arr as $axis){	
		//$axis = (int) $axis;	
		if ($axis > $mostOblique && $axis <= 45) $mostOblique = $axis;
	}
	return $mostOblique;
}

function parseInt($string) {
	if(preg_match('/[0-9]+/', $string, $array)) {
		return $array[0];
	} else {
		return 0;
	}
}

//for the 'all base curves' and 'all diameters fields'. Removes duplicates, sorts, and formats the numbers. also, checks to see if a list of numbers is shorter or (if included) lists of ranges is shorter
//eg) 14.0, 14.5, 15.0 vs.  14.0 to 15.0 in .50 steps
function makeAllList ($string) {
	$string = trim($string, ",");
	$string = preg_replace_callback ("/((\d+(\.\d*)?)|\.\d+)([0-9]*)/", 'callbackFunction' , $string );
	
	$numArray = parseMMData ($string); //an array of individual values
	$array = explode(",", $string); // an array of chunks which may include ranges
	$array =array_filter(array_map('trim', $array));
	$numArray = array_filter(array_map('trim', $numArray));
	$array = array_unique($array); //get rid of duplicates
	usort($numArray, "ascSorter");
	usort($array, "ascSorter");
	$string = shortestString(implode(", ", $array), implode(", ", $numArray) );
		
	return $string;
}

function callbackFunction ($matches) {
	return sprintf("%0.1f", $matches[0]);
	}



function ascSorter($a, $b){
		if ((float) $a == (float) $b) {
			return 0;
		}
		return ((float)$a > (float)$b) ? 1 : -1;
}
