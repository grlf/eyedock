<?php

/*
makes the where clauses in an sql query
- $field is the database field
- $valArr is the value that will be searched for. it'll be an array, like this:
[phrase] => Array ( [0] => freq [1] => tori )
most will just have one item but some may have more
- $and is how multiple items will be joined. most will be AND (meaning a lens has to meet every criterion to be returned) but some might be OR.
- $operator can be "=", "like", "><"
	- if "><" will return ≤ for negative numbers and ≥ for positive 
- $negField is for when a different field will be searched for positive or negative numbers.  For example, a diameter of 14 will search pn_max_diam, a diameter of -14 will search pn_min_diam
$tolerance only used for powers (it adjusts the search to find more values).  if a tolerance is given we know we're dealing with a power
*/


function makeWhereChunk ($field, $valArr, $join="AND", $operator = "=", $negField = null, $tolerance = null) {
	$returnArray = "";
	$returnString = "";
	$like = ($operator == "LIKE")?"%":"";
	foreach ($valArr as $val) {
		$tempOperator = $operator ;
		$tempField = $field;
		if ($operator =="<>" || $operator == "=" || $operator == "<=" || $operator == ">=") $val = mwParseFloat($val); 
		if ($negField != null && $val<0) $tempField = $negField;
		
		if (isset($tolerance) && $tolerance != null ) {
			$tempOperator = ($val >0)?">=":"<=";
			if ($val>0) $val -= abs($tolerance);
			if ($val<0) $val += abs($tolerance);
		} else if ($operator == "<>" ) {
			$tempOperator = ($val >0)?">=":"<=";
			if ($negField == null) $val = abs($val);
		}


		$returnArray[] = $tempField . " " . $tempOperator . " '" . $like . $val . $like . "' ";
	}
	$returnString = implode (" ".$join." ", $returnArray);
	return "(" . $returnString . ")";
}

function mwParseFloat($val) {
	return preg_replace( '/[^\d\.\+\-]/', '', $val ); 
}