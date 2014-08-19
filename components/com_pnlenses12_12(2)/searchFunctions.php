<?php



//does everything - takes a ton of parameters and does searches. returns SQL statement to return a list of lens IDs that meets the given criterion 
function ed-lenses-advSearch ($params) {

	//print_r($params);
		
		extract ($params);
		
		if (isset($refraction)) extract(getParametersFromRx ($refraction, $toric) );
	
		 $tolerance = ($tolerance > 0) ? $tolerance : 0;
		 
		 //when true will find lenses where replace <100 (1,14,30, and 90 day lenses)
		 if ($disposable == "true") $replaceMax = 100;
		 

	
	$where[] = " WHERE pn_display = 1 ";    
	
	//if searching for a phrase, explode the phrase into it's component parts and search for each one 
	if (isset($phrase) ){
			$phrase_array=explode(" ",$phrase);
			//$orCount = 0;

			foreach ($phrase_array as $value){
				//$and = "";
				//if ($orCount > 0) $and = " AND ";
				 $name[] = " pn_name LIKE '%$value%' ";
				 $alias[] = " pn_aliases LIKE '%$value%' ";
				 //$orCount++;
			}
			 $nameString = implode(" AND ", $name);
			 $aliasString = implode(" AND ", $alias);
			 $where[]= "   ( ($nameString) OR ($aliasString) ) ";
	}
	//print ($where);die;
	
	if (isset($company) )  $where[]= "   pn_comp_id = $company ";
	
	if ($dk > 0 )  $where[]= "   pn_dk > $dk ";
	if ($dk < 0 )  $where[]= "   pn_dk < ". abs ($dk) ;
	if ($sph > 0 )  $where[]= "   pn_max_plus >= " . ($sph - abs($tolerance) );
	if ($sph < 0 )  $where[]= "   (ABS(pn_max_minus) = 99 OR pn_max_minus <=" . ($sph + abs($tolerance) ) ." )";
	if ($diam > 0 )  $where[]= "   pn_max_diam >= $diam";
	if ($diam < 0 )  $where[]= "   (pn_min_diam = 99 OR pn_min_diam <= ".abs($diam) . ")" ;
	
	if ($flat == "true" || $med=="true" || $steep == "true") {
		$orCount = 0;
		 $where[]= "   ( " ;
		if ($flat =="true"){
			 $where[]= "pn_bc_simple LIKE '%flat%'";
			$orCount ++;
		}
		if ($med =="true"){
			if ($orCount >0)  $where[]= " OR ";
			 $where[]= "pn_bc_simple LIKE '%med%'";
			$orCount ++;
		}
		if ($steep =="true"){
			if ($orCount >0)  $where[]= " OR ";
			 $where[]= "pn_bc_simple LIKE '%st%'";
			$orCount ++;
		}
			 $where[]=" ) ";
	} 
	
	if ($h2o > 0 )  $where[]= "   pn_h2o >= $h2o ";
	if ($h2o < 0 )  $where[]= "   pn_h2o <= ".abs ($h2o)  ;
	if ($oz > 0 )  $where[]= "   pn_oz >= $oz " ;
	if ($replaceMin > 0 )  $where[]= "   pn_replace_simple >= " . ($replaceMin - 2 )  ;
	if ($replaceMax > 0 )  $where[]= "   pn_replace_simple <= " . ($replaceMax + 3 ) ;
	if ($ct > 0 )  $where[]= "   pn_ct >= $ct ";
	if ($ct < 0 )  $where[]= "   pn_ct < ". abs ($ct) ;
	if ($visi == "true" )  $where[]= "   pn_visitint = 1 " ;
	if ($bifocal == "true" ) {
		 $where[]= "   pn_bifocal = 1 " ;
		if ($add > 0 )  $where[]= "   pn_max_add >= $add ";
	} else {
		 $where[]= "   pn_bifocal = 0 " ;
	}
	//if ($bifocal == "false" )  $where[]= "   pn_bifocal = 0 " ;
	
	if ($ew == "true" )  $where[]= "   pn_ew = 1 " ;
	 $where[]= ($cosmetic == "true" ) ? "   pn_cosmetic = 1 " : "   pn_cosmetic = 0 "  ;
	//if ($cosmetic == "false" )	 $where[]= "   pn_cosmetic = 0 " ;
		
	
	if ($toric == "false" || !isset($toric) ) {
	
		 $where[]= "   pn_toric = 0 " ;
		
	} else if ($toric == "true" || abs($cyl) >= .75 || $oblique = "true") {
	
		 $where[]= "   pn_toric = 1 " ;
		if ($axisSteps >0 )  $where[]= "   pn_cyl_axis_steps <= $axisSteps " ;
		if ($oblique == "true" )  $where[]= "   pn_oblique >30 " ;	
		if (abs($cyl) > 0 )  $where[]= "   ABS(pn_max_cyl_power) >=" . ( abs($cyl  + abs($tolerance) )) ;
		
	}   else {
		 $where[]= "   pn_toric = 0 " ;
	}
	

	if ($onlyAvailableLenses == "true" )  $where[]= "  pn_discontinued = 0 " ;
	
	//print_r($where);
	
	$where = implode(" AND ", $where);
	
	//echo $phrase; die;
		  
				$sql = "SELECT pn_tid as id
					FROM pn_lenses 
					LEFT JOIN pn_lenses_companies ON pn_comp_tid = pn_comp_id
					LEFT JOIN pn_lenses_polymers ON pn_poly_tid = pn_poly_id 
					$where 
					ORDER BY pn_name"; 
	return $sql;
}					




//a helper function for the clSearchSQL function
// takes a refraction and a toric value and returns a sph, cyl, & oblique params
//if toric is set to "false" it is assumed the user wanted a spherical lens and one is the sph equiv is calculated

function getParametersFromRx ($refraction, $toric="true"){
	
	//$rx = new RxObject();
	$rx = rxStringBreaker($refraction);
	
	if ($rx->isValidRx() !=1 ) return null;
	
	$params[sph]  = $rx->sphM();
	$params[cyl] = $rx->cylM();
	
	//echo "<p>params[sph] " . $params[sph] . "</p>";
	//echo "<p>toric " . $toric . "</p>";
	
	if ($toric != "false") { 
		if ($rx->isOblique() == 1) $params[oblique] = "true";
		$params[toric] = "true";
	} else {
		$params[sph] = $params[sph] + (.5 * $params[cylinder] );
		$params[toric] = "false";
	}
	
	//print_r($params);
	
	return $params;
	
	
}


?>