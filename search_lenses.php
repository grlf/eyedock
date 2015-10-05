<?php

//ini_set('display_errors', 1); 
//ini_set('log_errors', 1); 
//ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
//error_reporting(E_ALL);
//ini_set('html_errors', 'On');

//defined('_JEXEC') or die();

//require_once( JPATH_ROOT.DS."utilities/database.php" );
require_once("utilities/mysqliSingleton.php" );
require_once("utilities/phpClasses/RxObject.php" );
require_once("utilities/phpClasses/RxHelper.php" );

//echo "wazzup.";

$q = strtolower($_REQUEST["term"]);
$which = $_REQUEST["which"];
$which = "scl";

// a flag to eliminate bifocals and hybrid lenses (for when doing autocomplete for power suggestions)
$noSpecial = $_REQUEST["noSpecial"]; 
if (!isset($noSpecial)) $noSpecial = 0;

//only looks for rx's - does not do database search
$onlyRx = $_REQUEST["onlyRx"]; 
if (!isset($onlyRx)) $onlyRx = 0;

//kind of the opposite - only looks for lenses - does not look for powers
$powers = $_REQUEST["powers"]; 
if (!isset($powers)) $powers = 0;


//$dataBase = databaseObj();



$return = array();


//check if the string is a number or a +/- sign
$tempStr = preg_replace( '/\s+/', '', $q );
$isNumber = ( preg_match('/^[+-.\d]/', $tempStr) );

if ($isNumber == 1 && $which == "scl" && $powers != 0) { 

	$rxObj = rxStringBreaker($q);
	$rxString = $rxObj->prettyString();
	$mrRow['id'] = $rxString ;
	$mrRow['label'] =  $rxString . " (refraction)";
	$mrRow['type'] = "mr";
	$mrRow['rx'] = $rxString;
	$pwrRow['id'] = $rxString ;
	$pwrRow['label'] =  $rxString . " (CL power)";
	$pwrRow['type'] = "power";
	$pwrRow['rx'] = $rxString;
	array_push($return, $mrRow);
	array_push($return, $pwrRow);
	
}


if ($which == "scl" && $onlyRx != 1) {
	
	//break all the words into a single array
	$phrase_array=explode(" ", $q);
	
	foreach ($phrase_array as $value){
		 $name[] = " pn_name LIKE '%$value%' ";
		 $alias[] = " pn_aliases LIKE '%$value%' ";
		 $company[] = " pn_comp_name LIKE '%$value%' ";
	}

	$nameString = implode(" AND ", $name);
	$aliasString = implode(" AND ", $alias);
	$companyString = implode(" AND ", $company);
 
	 $where[]= "   ( ($nameString) OR ($aliasString) OR ($companyString) ) ";
 
	 $where = implode(" AND ", $where);	

		  
	$sql = "SELECT pn_tid as id, pn_name as label, '$which' as type
		FROM pn_lenses 
		LEFT JOIN pn_lenses_companies ON pn_comp_tid = pn_comp_id
		WHERE
		$where 
		ORDER BY pn_name"; 
		
	//echo($sql);

	//$db = databaseObj();
	//$db->setQuery($sql);
	$mysqli = DBAccess::getConnection();
	$result = $mysqli->selectQuery($sql);
	 while($row = $result->fetch_assoc() ) {
	   array_push($return, $row);
 	}

}

	echo json_encode( $return);