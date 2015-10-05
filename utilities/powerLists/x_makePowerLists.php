<?php 

//include_once "./sqlFunctions/doQuery.php";
//include_once $_SERVER['DOCUMENT_ROOT'] . "/eyedock.com/api_new/utilities/parsingUtility.php";
require_once( JPATH_COMPONENT.DS.'helpers/utilities/parsingUtility.php' );

//takes arrays sets of lens powers for one or many lenses and returns an array of lists

function makeLists ($dataArray) {


	// will return this array.  $lensArray[id][n], where id is the lens id and n is each row of power variations it comes in
	$lensArray = array();
	
	$currentN = 0; //this will count the rows for each lens id
	$currentLensID = 0;
	
	//powerRow will be one table row of lens powers
	foreach ($dataArray as $powerRow) {
		$lensID = $powerRow['lensID'];
		
		//echo "$lensID : $currentN <br/>";
		
		if ($lensID != $currentLensID){
			$currentN = 0;
			$currentLensID = $lensID;
		}

		
		if ($powerRow['baseCurve']){
			$powerRow['baseCurve'] = parseMMData ($powerRow['baseCurve']);
		}
		
		if ($powerRow['diameter']){
			$powerRow['diameter'] = parseMMData ($powerRow['diameter']);
		}
		
		if ($powerRow['sphere']){
			$powerRow['sphere'] = parsePowerData ($powerRow['sphere']);
		}
		
		if ($powerRow['cylinder']){
			$powerRow['cylinder'] = parsePowerData ($powerRow['cylinder']);
		}
		
		if ($powerRow['axis']){
			$powerRow['axis'] = parseIntegerData ($powerRow['axis']);
		}
		
		if ($powerRow['addPwr']){
			$powerRow['addPwr'] = parsePowerData ($powerRow['addPwr']);
		}
		
		if ($powerRow['colors_enh']){
			$powerRow['colors_enh'] = explode(",", $powerRow['colors_enh']); 
		}
		
		if ($powerRow['colors_opq']){
			$powerRow['colors_opq'] =  explode(",", $powerRow['colors_opq']);
		}
		
	
		$lensArray[$powerRow['lensID']][$currentN] = $powerRow;
		$currentN++;
	}
//echo "<p>&nbsp;</p>";
//print_r($lensArray);

return $lensArray;

}
