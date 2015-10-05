<?php

//does searches for the autocompletes. Works with jQuery autocomplete

// 
// ini_set('display_errors', 1); 
// ini_set('log_errors', 1); 
// ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
// error_reporting(E_ALL);
// ini_set('html_errors', 'On');

//examples


require_once("utilities/mysqliSingleton.php" );
require_once("utilities/phpClasses/RxObject.php" );
require_once("utilities/phpClasses/RxHelper.php" );
require_once("utilities/phpClasses/KvalObject.php" );
//require_once("utilities/is_subscriber.php" );

//see if they're a logged in eyedock subscriber
//$isUserSubscriber = isUserASubscriber();
//echo "hello" .  $isUserSubscriber;

  //$q = db_escape($_REQUEST["term"]);
$q = $_REQUEST["term"];
$q_encoded = urlencode($q);
//echo $q;
$demo = aRequest("demo", 0);
$scl = aRequest("scl", 0); //which database: scl, rgp, meds, icd9, mr, clRx, k, all
$scl_comp = aRequest("scl_comp", 0);
$rgp = aRequest("rgp", 0);
$button = aRequest("button", 0);
$meds = aRequest("meds", 0);
$epocrates = aRequest("epocrates", 0);
$icd9 = aRequest("icd9", 0);
$rx = aRequest("mr", 0); // generic refraction - used to return info
$mr = aRequest("mr", 0); // a refraction - can be used to search for cls
$clRx = aRequest("clRx", 0); // a clRx - can be used to search for cls
$k = aRequest("k", 0); //keratometry
$all = aRequest("all", 0); //all will include all things needed for the universal search bar
$subscriber = aRequest("subscriber", 0); //links will link to the appropriate component. only used for SCL power searches
$info = aRequest("info", 0); //returns formatted 
$noLabels = aRequest("noLabels", 0); //leaves off "(scl)", etc, when set to 1 

$opensearch = aRequest("opensearch", 0); //used for suggestions in chrome opensearch searches
if ($opensearch == 1) {
	$all = 1;
	$subscriber = 1;	
}
//if ($opensearch == 1) $scl = 1;

//echo "<p>all: $all</p>";

//break all the words into an array
$phrase_array=explode(" ", $q);

$return = array();

//--------------------------------------for refractions ----------------------------
//$isRx = preg_match("/\s*([+-]?[\d.]{1,5})\s*(?:[+-]{1}([\d.]{1,5})\s*[xX*]{1}\s*([\d]{1,3}))?\s*/u", $q, $rx_matches);
$tempStr = preg_replace( '/\s+/', '', $q );
$isNumber = ( preg_match('/^[+-.\d]/', $tempStr) );
$hasSign = ( preg_match('/^[+-]/', $tempStr) );
 
 //echo "<br>q! " . $q;
 //echo "<br># " . $isNumber;

if ( ($mr || $clRx || $all) && $isNumber ){  // && $isRx) {
  $rxObj = rxStringBreaker($q);

  if (!is_null($rxObj)) {

      $rxString = $rxObj->prettyString();

    if ($mr)  {
      $mrInfo['label'] =  $rxString . " (refraction)";
      $mrInfo['type'] = "refraction";
      $mrInfo['rx'] = $rxString;
      array_push($return, $mrInfo);
    }
    if ($clRx) {
      $pwrInfo['label'] =  $rxString . " (CL power)";
      $pwrInfo['type'] = "clRx";
      $pwrInfo['rx'] = $rxString;
      array_push($return, $pwrInfo);
    }

    if ( ($rx || $all) && $info) {
      $rxInfo['label'] =  $rxString . ": get info";
      $rxInfo['type'] = "refraction";
      $rxInfo['rx'] = $rxString;
      array_push($return, $rxInfo);
    }
  
  
    if ( ($mr || $all) && $subscriber == 1) {
      $mrRow['label'] = $rxString . " (refraction: find scls)";
      $mrRow['type'] = "mr";
      $mrRow['link'] = "http://www.eyedock.com/index.php?option=com_pnlenses#view=list&refraction%5B%5D=" . urlencode($rxString);
      array_push($return, $mrRow);
    }
    if ( ($clRx || $all) && $subscriber == 1) {
      $pwrRow['label'] =  $rxString . " (CL power - find scls)";
      $pwrRow['type'] = "power";
      $pwrRow['link'] = "http://www.eyedock.com/index.php?option=com_pnlenses#view=list&clRx%5B%5D=" . urlencode($rxString);
      array_push($return, $pwrRow);
    }
    
  } //end if $rxObj

}

// ---------------scl searches by COMPANY----------------------------------------------------

if ($scl_comp || $all)  {
    
  foreach ($phrase_array as $value){
    $company[] = " pn_comp_name LIKE '%$value%' ";
  }

  $companyWHERE = implode(" AND ", $company);
 
   //$cl_comp_where[]= "   ($companyString) ) ";
 
   //$where = implode(" AND ", $cl_where);  

      
  $sql = "SELECT pn_comp_tid as id, concat(pn_comp_name, ' (scl company)' ) as label, concat('http://www.eyedock.com/index.php?option=com_pnlenses&comp_id=' ,  pn_comp_tid ) as link
    FROM pn_lenses_companies
    WHERE
    $companyWHERE 
    ORDER BY pn_comp_name"; 
    
  //echo($sql);

  $mysqli = DBAccess::getConnection();
  $result = $mysqli->selectQuery($sql);
   while($row = $result->fetch_assoc() ) {
     array_push($return, $row);
   }

}



//-----------------------------for scl searches (by name) ----------------------------

if ($scl || $all)  {
    
  foreach ($phrase_array as $value){
     $name[] = " pn_name LIKE '%$value%' ";
     $alias[] = " pn_aliases LIKE '%$value%' ";
    $company[] = " pn_comp_name LIKE '%$value%' ";
  }

  $nameString = implode(" AND ", $name);
  $aliasString = implode(" AND ", $alias);
  $companyString = implode(" AND ", $company);
 
   $cl_where[]= "   ( ($nameString) OR ($aliasString) OR ($companyString) ) ";
 
   $where = implode(" AND ", $cl_where);  
   
   if ($demo == 1) $where .= " and pn_name LIKE 'A%' ";

  //$label = ($noLabels == 1) ? "":" (scl)";
  $label = "";
  
  $sql = "SELECT pn_tid as id, concat(pn_name, '" . $label . "' ) as label, concat('http://www.eyedock.com/index.php?option=com_pnlenses&lens_id=' , pn_tid ) as link
    FROM pn_lenses 
    LEFT JOIN pn_lenses_companies ON pn_comp_tid = pn_comp_id
    WHERE
    $where 
    ORDER BY pn_name"; 
    
  //echo($sql);

  $mysqli = DBAccess::getConnection();
  $result = $mysqli->selectQuery($sql);
   while($row = $result->fetch_assoc() ) {
     array_push($return, $row);
   }

}


// ----------------------------- for keratometry --------------------------------------

if ( ($k || $all) && ! $hasSign){ 

  if ($isNumber) {
    $kValObj = new KvalObject($q);
    if ($kValObj->isValidK() ) {
      if ($kValObj->isMM() ) $kInfo['label'] = $kValObj->prettyStringMM() . "mm = " . $kValObj->prettyStringD() . "D";
      if ($kValObj->isD() )  $kInfo['label'] = $kValObj->prettyStringD() . "D = " . $kValObj->prettyStringMM() . "mm";
      $kInfo['type'] = "keratometry";
      $kInfo['k'] = $q;
      array_push($return, $kInfo);
    }
  } 
  
  $kObj = kStringBreaker($q);
  if ($kObj->isValidK() ) {
     $kInfo['label'] = $kObj->prettyString() . ": get info";
     $kInfo['type'] = "keratometry";
     $kInfo['k'] = $kObj->prettyString();
     array_push($return, $kInfo);
  }
  
}



//-----------------------------for meds searchs (by name, chem, or company) ----------------------------

if ( ($meds || $all) && ! $hasSign) {

  foreach ($phrase_array as $value){
     $chem[] = " chem.pn_name LIKE '%$value%' ";
     $rx_comp[] = " comp.pn_name  LIKE '%$value%' ";
     $trade[] = " pn_trade LIKE '%$value%' ";
  }


  $chemString = implode(" AND ", $chem);
  $tradeString = implode(" AND ", $trade);
  $rxCompString = implode(" AND ", $rx_comp);
  
   $med_where[] = "   ( ($chemString) OR ($tradeString) OR ($rxCompString) ) ";

   $where = implode(" AND ", $med_where);  

      
  $sql = "SELECT pn_med_id as id, concat(pn_trade, ' (meds)' ) as label, concat('http://www.eyedock.com/index.php?option=com_content&view=article&id=70&Itemid=62&s_id=' , pn_med_id) as link 
    FROM pn_rx_meds med 
    LEFT JOIN pn_rx_company comp ON comp.pn_comp_id = med.pn_comp_id
    LEFT JOIN pn_rx_chem chem ON (pn_chem_id = `pn_chem_id1` || pn_chem_id = `pn_chem_id2` || pn_chem_id = `pn_chem_id3`) 
    WHERE
    $where 
    ORDER BY pn_trade"; 
    
  //echo($sql);

  $mysqli = DBAccess::getConnection();
  $result = $mysqli->selectQuery($sql);
   while($row = $result->fetch_assoc() ) {
     array_push($return, $row);
   }

}




/*

//this worked, but dx codes return too much unneeded info. We'll jst provide a link to the component

if ($icd9 || $all) {
  //echo "is code";

  $sql = "SELECT concat (LEFT(pn_diagnosis, 20), '..[', pn_code, ']', ' (icd9)' ) as label, pn_diagnosis as dx, pn_code as code  FROM pn_icd9
    LEFT JOIN pn_icd9_section
      ON ( pn_icd9_section.pn_high >= pn_code
      AND pn_icd9_section.pn_low <= pn_code
      AND pn_icd9.pn_letter = pn_icd9_section.pn_letter )
    LEFT JOIN pn_icd9_category
      ON (pn_icd9_category .pn_high >= pn_code
      AND   pn_icd9_category .pn_low <= pn_code
      AND pn_icd9.pn_letter = pn_icd9_category.pn_letter)
    WHERE ( (lower(pn_icd9.pn_diagnosis) LIKE '" . db_escape("%$q%") . "'
      or lower(pn_icd9.pn_comment) LIKE '" . db_escape("%$q%") . "'";
    if (is_numeric($q) ) $sql .= " or pn_icd9.pn_code = " . db_escape("$q");
    $sql .=   ")  ) order by pn_icd9.pn_letter, pn_icd9.pn_code LIMIT 50";
    

  $mysqli = DBAccess::getConnection();
  $result = $mysqli->selectQuery($sql);
   while($row = $result->fetch_assoc() ) {
     array_push($return, $row);
   }

}
*/

//echo "<p> done codes</p>";
//-----------------------------for gp searchs (by name or lab) ----------------------------

if (($rgp || $all) && ! $hasSign) {
  foreach ($phrase_array as $value){
     $gp_name[] = " rgp.name LIKE '%$value%' ";
     $gp_alias[] = " rgp.aliases LIKE '%$value%' ";
     $gp_lab[] = " lab.name LIKE '%$value%' ";
  }

  $nameString = implode(" AND ", $gp_name);
  $aliasString = implode(" AND ", $gp_alias);
  $companyString = implode(" AND ", $gp_lab);
 
   $gp_where[]= "   ( ($nameString) OR ($aliasString) OR ($companyString) ) ";
 
   $where = implode(" AND ", $gp_where);  

//would rather pass a lens ID than the name in an URL (can't urlencode in mysql?)
      
  $sql = "SELECT rgp.tid as id, concat(rgp.name, ' (gp)' ) as label, concat('http://www.eyedock.com/index.php?option=com_content&view=article&id=126&Itemid=71&q=' , rgp.name ) as link 
    FROM rgpLenses rgp
    LEFT JOIN  rgpMaterialCompany lab ON lab.tid = rgpCompanyID
    WHERE
    $where 
    ORDER BY rgp.name"; 
    
  //echo($sql);

  $mysqli = DBAccess::getConnection();
  $result = $mysqli->selectQuery($sql);
   while($row = $result->fetch_assoc() ) {
     array_push($return, $row);
   }

}

//-----------------------------for gp materials buttons (by name or manufacturer) ----------------------------

if (($button || $all) && ! $hasSign) {

  foreach ($phrase_array as $value){
     $button_name[] = " mat.name LIKE '%$value%' ";
     $button_manuf[] = " comp.name LIKE '%$value%' ";
  }

  $nameString = implode(" AND ", $button_name);
  $companyString = implode(" AND ", $button_manuf);
 
   $btn_where[]= "   ( ($nameString) OR ($companyString) ) ";
 
   $where = implode(" AND ", $btn_where);  

//would rather pass a lens ID than the name in an URL (can't urlencode in mysql?)
      
  $sql = "SELECT mat.tid as id, concat(mat.name, ' (gp btn)' ) as label, concat('http://www.eyedock.com/index.php?option=com_content&view=article&id=127&Itemid=72&q=' , mat.name ) as link 
    FROM rgpMaterials mat 
    LEFT JOIN  rgpMaterialCompany comp ON comp.tid = rgpMaterialCompanyID
    WHERE
    $where 
    ORDER BY mat.name"; 
    
  //echo($sql);

  $mysqli = DBAccess::getConnection();
  $result = $mysqli->selectQuery($sql);
   while($row = $result->fetch_assoc() ) {
     array_push($return, $row);
   }

}


//-----------------------------for epocrates ----------------------------

if ( ($all) && ! $hasSign) {
  $codeInfo['label'] =  $q . " (epocrates)";
  $codeInfo['type'] = "epocrates";
  $codeInfo['link'] = "http://online.epocrates.com/public/portkey/?monograph=" . substr($q_encoded, 0, 6);
  array_push($return, $codeInfo);
}



//-----------------------------for icd9 searchs (by name or code) ----------------------------

if ( ($icd9 || $all) && ! $hasSign) {
  $codeInfo['label'] =  $q . " (ICD-9)";
  $codeInfo['type'] = "icd9";
  $codeInfo['link'] = "http://www.eyedock.com/index.php?option=com_content&view=article&id=71&Itemid=63&q=" . $q_encoded;
  array_push($return, $codeInfo);
}


//sort the array by the label
//aasort($return,"label");

//$sorted = usort($return, 'sortByOrder');

//if returning data for opensearch suggestions needs to be in proper format
//see http://www.opensearch.org/Specifications/OpenSearch/Extensions/Suggestions

if ($opensearch == 1) {
	$osearch = array();
	$osearch[0] = $q;
	foreach ($return as $item) {
		$osearch[1][] = $item['label'];
		$osearch[2][] = "1 result";
		$osearch[3][] = encodeGPs($item['link']);
	}
	$return = array_values($osearch);
}

echo json_encode( $return);

//a helper function
function aRequest($key, $default) {
    if (isset($_REQUEST[$key])) return $_REQUEST[$key];
    return $default;
}


function aasort (&$array, $key) {
    $sorter = array();
    $ret = array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii] = $va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[]=$array[$ii];  //was $ret[$ii]=$array[$ii];
    }
    $array = $ret;
}

//url encode the q=xxx parts of the links (needed for GP searches)
function encodeGPs ($link) {
	$parts = explode("q=", $link);
	$parts[1] = ($parts[1]) ? "q=".urlencode($parts[1]) : "";
	return $parts[0] . $parts[1];
}