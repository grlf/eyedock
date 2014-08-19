<?php
/*
ini_set('display_errors', 1); 
ini_set('log_errors', 1); 
ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
error_reporting(E_ALL);
ini_set('html_errors', 'On');
*/

//include_once $_SERVER['DOCUMENT_ROOT'] . "/api_new/phpClasses/RxHelper.php";
require_once($_SERVER['DOCUMENT_ROOT'] . '/components/com_pnlenses/helpers/phpClasses/RxHelper.php' );

//accepts an array of lens powers and reformats the text

function formatNumberText ($powerArray) {
	$i = 0;
	foreach ($powerArray as $lens) {
		//$returnArray[$i]['id'] = $lens['id'];
		//$returnArray[$i]['lensID'] = $lens['lensID'];
		$returnArray[$i]['variation'] = $lens['variation'];
		$returnArray[$i]['sphere'] = sphereFixer($lens['sphere'] );
		$returnArray[$i]['cylinder'] = sphereFixer($lens['cylinder'] );
		$returnArray[$i]['baseCurve'] = mmFixer($lens['baseCurve'] );
		$returnArray[$i]['diameter'] = mmFixer($lens['diameter'] );
		$returnArray[$i]['axis'] = axisFixer($lens['axis'] );
		$returnArray[$i]['addPwr'] = sphereFixer($lens['addPwr'] );
		$returnArray[$i]['colors_enh'] = listFixer($lens['colors_enh'] );
		$returnArray[$i]['colors_opq'] = listFixer($lens['colors_opq'] );
		$i++;
	 }
	 
	 //print_r ($returnArray);

	return $returnArray;
}

//makes use of the RxHelper utility functions
/*function formatDiopterStringText ($matches) {
	numToDiopterString ($matches[0], 1, .1);
}*/



function sphereFixer ($string){
	$string = preg_replace_callback ("/[+-]?((\d+(\.\d*)?)|\.\d+)([+-]?[0-9]*)/", "powerStrings", $string );
	$string = rangeFixer ($string, "0.25");
	$string = preg_replace_callback ("/[+-]?((\d+(\.\d*)?)|\.\d+)([+-]?[0-9]*)\s+steps/", "powerSteps", $string);
	
	//break rows with ranges into separate lines
	$string = str_replace("steps,","steps<br/>",$string);
	return $string;
}

function mmFixer ($string){
	$string = preg_replace_callback ("/((\d+(\.\d*)?)|\.\d+)([0-9]*)/", "mmStrings", $string );
	$string = rangeFixer ($string, "0.1");
	$string = preg_replace_callback ("/((\d+(\.\d*)?)|\.\d+)([0-9]*)\s+steps/", "mmSteps", $string);
	return $string;	
}

function axisFixer ($string){
	$string = preg_replace_callback ("/\d+[\d\.]*/", "axisStrings", $string );
	$string = rangeFixer ($string, "1");
	$string = preg_replace_callback ("/\d+[\d\.]*\s+steps/", "axisSteps", $string);
	return $string;	
}

//makes lists look prettier (strips whitespace, evenly spaces commas, etc.)
function listFixer ($string){
	$string = trim($string, ",");
	$chunks = explode(",", $string);
	foreach ($chunks as $chunk) {
		$chunk = trim($chunk); //strip WHITESPACE
		$returnArray[] = $chunk;
	}
	$returnString = implode(", ", $returnArray);
	
	return $returnString;	
}

function powerStrings($matches) {
	if ($matches[0] == 0 ) return "plano";
	return numToDiopterString ($matches[0], 1, .01);
}

function powerSteps($matches) {
	return numToDiopterString (abs($matches[0]), 0, .01) . " D steps";
}

function mmStrings($matches) {
	return sprintf("%0.1f", $matches[0]);
}

function mmSteps($matches) {
	return sprintf("%.1f", $matches[0]) . " mm steps";
}

function axisStrings($matches) {
	return intval($matches[0]). "&deg";
}

function axisSteps($matches) {
	return intval( $matches[0]) . "&deg; steps";
}

//checks the for ranges, and if the steps aren't provided, fills in the default steps.  For example, if a range of "-0.50 to -6.00" is given it will change it to "-0.50 to -6.00 in .25 steps"
function rangeFixer ($string, $defaultSteps) {
	$returnArray = "";
	$chunks = explode(",", $string);
	foreach ($chunks as $chunk) {
		$toPos = stripos($chunk, "to"); //the location of "to"
		$inPos = stripos($chunk, "in"); //the location of "in", if it is present
		if ($toPos > 0 && $inPos === false  ) { //if it's a range but steps are not provided
			$chunk .= " in " . $defaultSteps . " steps";
		} 
		$returnArray[] = $chunk;
	}
	$returnString = implode(", ", $returnArray);
	return $returnString;
}