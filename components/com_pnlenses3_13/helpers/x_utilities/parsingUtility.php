<?php

include_once 'sorter.php';


function parsePowerData ($dataString) {
	$dataArray = parseData ($dataString, .25, 2,1);
	return $dataArray;
}

function parseMMData ($dataString) {
	$dataArray = parseData ($dataString, .1,1,0);
	return $dataArray;
}

function parseIntegerData ($dataString) {
	$dataArray = parseData ($dataString, 1,0,0);
	return $dataArray;
}

//parseData
// parse the string data, which can include:
// a single value: 8.6
// a list of values: 8.6, 8.8, . . .
// a range: 1.00 to 5.00 in .25 steps 
//or some combination of the above fields.  
//First, separates values at the "," and removes whitespace (explodeArray function).  Then, if a range is given, parses that and creates an array to list the values in that range (eg -0.50, -0.75, -1.00 etc..)
//accepts $dataString (the string to parse), $defaultIncrement (the default steps for a range if the value is not given), $name (the name of the field for the HTML form), and $decimals (how to format a number in the output)

function parseData ($dataString, $defaultIncrement, $decimal = 2, $sign = 0){
	$paramArray = explodeString($dataString);
	
	//see if the values include a range - if it does it will include the word "to"
	$valueArray = array();  // the new string
	foreach ($paramArray as $string) {
		if (strpos($string, "to") ) {
			$valueArray = array_merge($valueArray , parseRange($string, $defaultIncrement, $decimal, $sign) ) ; 	
		} else {
			$valueArray = array_merge($valueArray , (array)$string); 
		}
	}
	//print_r($valueArray);
	//sort the values in the array and remove duplicates
	usort($valueArray, "sorter");
	$valueArray = array_unique($valueArray);
	
	return $valueArray;
	
}


//takes a string.  If it is a range it parses it, then returns an array of all the values in that range (using the makeRangeArray function)
//defaultIncrement is used for ranges where the increment is not provided 

function parseRange ($string, $increment, $decimal, $sign) {
	
	//this could contain a string like "1.00 to 5.00" or "1.00 to 5.00 in .25 steps"
	//use regex to find the location of the individual parts
	$toPos = stripos($string, "to"); //the location of "to"
	$inPos = stripos($string, "in"); //the location of "in", if it is present
	if ($inPos === false  ) $inPos = strlen($string);
	$stepsPos = stripos($string, "steps"); //the location of "steps", if it is present

	$num1 = (float) substr ($string, 0, $toPos - 1);
	$num2 = (float) substr ($string, $toPos + 2, $inPos - $toPos -2);
	
	if ($stepsPos) $increment = (float) substr ($string, $inPos + 2, $stepsPos - 4);
	
	//echo "<p>$string, toPos: $toPos, inPos: $inPos, stepsPos: $stepsPos</p>";
	//echo "<p>$num1, $num2, $increment</p>";
	
	$rangeArray = makerRangeArray ($num1, $num2, $increment, $decimal, $sign);
	return $rangeArray;
	
}

//make an array of values when given a start point, a stop point, and an increment
function makerRangeArray ($num1, $num2, $inc, $decimal = 2, $sign = 0) {
	$rangeArray = array();
	//val1 is larger value, val2 is smaller.  If we do this we can just loop through subtracting the increment as a positive number until it's less than the lower value
	$val1 = ($num1>$num2)?$num1:$num2;
	$val2 = ($num1<$num2)?$num1:$num2;
	$inc = abs($inc);
	if ($inc == 0) continue; //can't increment by 0 steps!
	//print "<p>val1: ". $val1.", val2: ".$val2. ", inc: ". $inc . "</p>";
	//the "-.001" is a fudge factor - the loop sometimes wasn't including the last value until I added this (rounding error?)
	for ($x = $val1; $x >= $val2-.001; $x -= $inc){
		//print "<br />". $x ;
		if ($x === "" || $x===null) continue; //don't show blank fields
		if (is_numeric($x) && $x !=0 ) {
			// format the number
			$plusSign = ($sign>0)?"+":"";
			$numFormat =  "%".$plusSign.".".$decimal."f";
			$x = sprintf($numFormat, $x);	
			
		}
		//echo $x . "<br/>";
		$rangeArray[] = $x;
	}
	//echo $rangeString . "<br/>";
	//print_r ($array);
	return $rangeArray;
	
}






//formats if the string is a number - takes a string, number of decimal places, and whether or not it should have a sign 
/*
function formatNum ($string, $decimal=2, $sign=0) {
	if (!is_numeric ($string) ) return $string;
	if ((int) $string == 0) return "0";
	$plusSign = ($sign>0)?"+":"";
	$numFormat =  "%".$plusSign.".".$decimal."f";
	$returnString = sprintf($numFormat, $string);
	return $returnString;
	
}
*/

function explodeString ($string) {
	//strip whitespace
	$string = preg_replace('/\s\s+/', ' ', $string);
	//separate string into chunks, using the comma as a delimiter
	$chunks = explode(",", $string);
	return $chunks;
	
}