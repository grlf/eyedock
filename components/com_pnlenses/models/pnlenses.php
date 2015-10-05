<?php

// ini_set('display_errors', 1); 
// ini_set('log_errors', 1); 
// ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
// error_reporting(E_ALL);
// ini_set('html_errors', 'On');


defined('_JEXEC') or die();

jimport('joomla.application.component.model');
require_once( JPATH_ROOT.DS.'utilities/powerLists/formatNumberText.php' );
require_once( JPATH_ROOT.DS.'utilities/phpClasses/CLHelper.php' );
//require_once( JPATH_COMPONENT.DS.'helpers/sqlHelper/dbObject.php' );
require_once( JPATH_COMPONENT.DS.'helpers/sqlHelper/whereChunks.php' );
require_once( JPATH_ROOT.DS.'utilities/utilities/parametersFromRx.php' );
require_once( JPATH_ROOT.DS.'utilities/database.php' );
//require_once( JPATH_COMPONENT.DS.'helpers/powerLists/makePowerLists.php' );

//define('EYEDOCK_LENS_IMG_URL', 'http://www.eyedock.com/modules/Lenses/pnimages/lens_images');
//define('EYEDOCK_LENS_COMP_IMG_URL', 'http://www.eyedock.com/modules/Lenses/pnimages/comp_logos');


class PnlensesModelPnlenses extends JModelLegacy {
       
  function getLenses(){
       		
             $db = databaseObj();
             $db->setQuery('SELECT pn_name from pn_lenses WHERE pn_display=1');
             $items = $db->loadResultArray();
             
            if ($items === null) JError::raiseError(500, 'Error reading db');
                    
             return $items;
       }
       
    function getCompanies(){
       		
             $db = databaseObj();
             $db->setQuery('SELECT pn_comp_name as name, pn_comp_tid as id from pn_lenses_companies WHERE pn_hide = 0 ORDER BY name ASC');
             $items = $db->loadAssocList();
             
            if ($items === null) JError::raiseError(500, 'Error reading db');
                    
             return $items;
       }
       
    function getPolymers(){
       		
             $db = databaseObj();
             $db->setQuery('SELECT pn_poly_name as name, pn_poly_tid as id from pn_lenses_polymers ORDER BY name ASC');
             $items = $db->loadAssocList();
             
             if ($items === null) JError::raiseError(500, 'Error reading db');
                    
             return $items;
       }
       

     function getParamList(){
       		$items['name'] = "lens name";
       		return $items;
       }
       
    function getCompanyDetails($id) {
    	
		$id = abs(intval($id));
	
	
		$query = "SELECT
		 pn_comp_tid as comp_id,
		 pn_comp_name as company,
		 pn_logo as comp_logo,
		 pn_comp_desc as company_description,
		 pn_phone as company_phone,
		 pn_address as company_address,
		 pn_city as company_city,
		 pn_state as company_state,
		 pn_zip as company_zip,
		 pn_url as company_url,
		 pn_email as company_email 
		FROM pn_lenses_companies 
		WHERE pn_comp_tid = $id";
	
	//echo $query;
	
		$db = databaseObj();
        $db->setQuery($query);
        $company_details = $db->loadAssoc();
        
        return $company_details;
    }
       
    function suggestLensPowersForLens() {
    	    $lensID    = JRequest::getVar( 'lensID', null );
    	    $refraction    = JRequest::getVar( 'refraction', null );
    	    $isCL  = JRequest::getVar( 'isCL', 0 );
    	    
    	    if ($refraction == null  || $lensID == null) return null;
    	    
    	    
    	   $vertex = ( $isCL == 1)? 0:1;

    	    
    	    $db = databaseObj();
    	    $powerQuery = "SELECT l.pn_name as name, l.pn_toric as toric, lpl.sphere, lpl.cylinder, lpl.axis, lp.diameter, lp.baseCurve FROM pn_lenses l LEFT JOIN  pn_lenses_power_lists lpl ON l.pn_tid = lpl.lensID LEFT JOIN pn_lenses_powers lp ON lpl.id = lp.id WHERE lpl.lensID = " . $lensID;
    	    //echo $powerQuery;
    	    
			$db->setQuery($powerQuery);
        	$lensPowerArr = $db->loadAssocList();
        	
        	if(count($lensPowerArr) <1) return null;
        	
        	//print_r($lensPowerArr);
        	$returnLenses = array();
        	$resultArray = getBestCLPowerForParams ($refraction, $lensPowerArr, $lensPowerArr[0]['toric'], $vertex )  ;
				foreach ($resultArray as $oneVariation){
					$oneVariation['name'] = $lensPowerArr[0]['name'];
					array_push($returnLenses, $oneVariation);
				}
			usort($returnLenses, "vaSorter" );
		//print_r($returnLenses);
		//filter out the worst lenses
		$returnLenses = removeVAOutliers($returnLenses);
        //print_r($returnLenses);
        return $returnLenses;      
    }
       
       //deprecate
    function getLensSearchResults($phrase) {
    		$query = "select pnl.pn_tid, pnl.pn_name as name, pnl.pn_image as image, pnlc.pn_comp_name as company, 
	pnl.pn_max_plus, pnl.pn_max_minus, pnl.pn_max_diam, pnl.pn_min_diam, pnl.pn_diam_all, pnl.pn_bc_all, pnl.pn_max_cyl_power, pnl.pn_max_add, pnl.pn_cosmetic, pnl.pn_dk, pnl.pn_oz, pnl.pn_ct, pnlp.pn_h2o,
	pnl.pn_discontinued as discontinued 
	from 
	pn_lenses pnl 
	LEFT JOIN pn_lenses_companies pnlc ON (pnl.pn_comp_id = pnlc.pn_comp_tid)
	LEFT JOIN pn_lenses_polymers pnlp ON (pnl.pn_poly_id = pnlp.pn_poly_tid)
	WHERE pnl.pn_name LIKE '%$phrase%' 
	order by name asc
	
	";
	
	//echo $query;
	
		$db = databaseObj();
        $db->setQuery($query);
        $lenses = $db->loadAssocList();
        
        return $lenses;
    
    }
    
    function updateUserPrefs($prefs) {
    
    	$user =& JFactory::getUser(); //get the current user
    	$db = databaseObj();
    	$query = "SELECT * FROM pn_lenses_user_prefs WHERE user_id = $user->id";
    	$db->setQuery($query);
    	$checkId = $db->loadResult();
    	
    	$query2 = "";
    	
    	if ($checkId !== null) {
    		$query2 = "UPDATE pn_lenses_user_prefs SET prefs = '$prefs' WHERE user_id = $user->id";
       	} else {
       		$query2 = "INSERT INTO pn_lenses_user_prefs (user_id , prefs) VALUES ($user->id, '$prefs')";
       	}
    	$db ->setQuery($query2);
    	$result = $db->loadAssoc();
    	//echo $query2;
    }
       
    function getUserPrefs () {
    	$user =& JFactory::getUser(); //get the current user
    	$db = databaseObj();
    	
    	
    	$query = "SELECT prefs FROM pn_lenses_user_prefs WHERE user_id = $user->id LIMIT 1";
    	$db->setQuery($query);
    	//$db->query($query);
    	//$num_rows = $db->getNumRows();
    	//echo  "num ". $num_rows;
        try {
            $prefs = $db->loadResult();
        } catch (Exception $e) {
            error_reporting(0);
            return null;
        }
    	
    	//if ($num_rows <1 ) return null;
    	$params = array();
		parse_str($prefs, $params);
		if (count($params)<1) return null;
    	return $params;
    }
		   
	function getLensDetails($id){
	
		$id = abs(intval($id));
	
	
		$query = "select pnl.pn_tid as id, 
		pnl.pn_name as name, 
		pnl.pn_image as image, 
		pnlc.pn_comp_tid as comp_id,
		pnlc.pn_comp_name as company,
		pnlc.pn_logo as comp_logo,
		pnlp.pn_poly_name as polymer,
		pnl.pn_dk as dk,
		IF(pnl.pn_dk > 0 and pnl.pn_ct > 0,(pnl.pn_dk / ( pnl.pn_ct * 10 ) ),'') as dkt, 
		pnlp.pn_fda_grp as fda_group,
		pnlp.pn_h2o as water,
		pnl.pn_ct as ct,
		pnl.pn_oz as oz,
		pnl.pn_process_text as man_process,
		pnl.pn_wear as wear,
		IF(pnl.pn_ew = 0,'no','yes') as ew,
		pnl.pn_qty as quantity,
		pnl.pn_replace_text as replacement,
		IF(pnl.pn_visitint > 0,'yes','no') as visitint,
		pnl.pn_other_info as comments,
		pnl.pn_toric as toric,
		pnl.pn_toric_type as toric_type,
		pnl.pn_bifocal as bifocal,
		pnl.pn_bifocal_type as bifocal_type,
		pnl.pn_cosmetic as cosmetic,
		pnl.pn_sph_notes as sph_notes,
		pnl.pn_cyl_notes as cyl_notes,
		pnl.pn_price as prices,
		pnl.pn_markings as appearance,
		pnl.pn_website as website,
		pnl.pn_fitting_guide as fitting_guide, 
		pnl.pn_discontinued as discontinued, 
		pnlc.pn_comp_desc as company_description,
		pnlc.pn_phone as company_phone,
		pnlc.pn_address as company_address,
		pnlc.pn_city as company_city,
		pnlc.pn_state as company_state,
		pnlc.pn_zip as company_zip,
		pnlc.pn_url as company_url,
		pnlc.pn_email as company_email, 
		pop.score
		from 
		pn_lenses pnl 
		LEFT JOIN pn_lenses_companies pnlc ON (pnl.pn_comp_id = pnlc.pn_comp_tid) 
		LEFT JOIN pn_lenses_polymers pnlp ON (pnl.pn_poly_id = pnlp.pn_poly_tid) 
		LEFT JOIN pn_lenses_popularity pop ON (pop.itemID = pnl.pn_tid)
		where 
		pnl.pn_tid = $id";
	
	//echo $query;
	
		$db = databaseObj();
        $db->setQuery($query);
        $lens = $db->loadAssoc();
        
        //see if this lens is a user favorite
        $user =& JFactory::getUser();
        $fav_query = "SELECT favorite FROM pn_lenses_user_favorites WHERE `user_id`= $user->id AND item_id = $id";
       // echo $fav_query;
        $db ->setQuery($fav_query);
        $fav = $db->loadAssoc();
        $lens['favorite'] = $fav['favorite'];
       
             
        if ($lens === null) JError::raiseError(500, 'Error reading db');
                    
	
		if(!empty($lens)){
			if(strstr($lens['image'], ',')){
				$images = explode(',', $lens['image']);
				$lens['image'] = trim($images[0]);
				if(count($images) > 1){
					$lens['images'] = array_map('trim', $images);
				}
			}
			if($lens['image'] != ''){
				$size = @getimagesize('http://www.eyedock.com/modules/Lenses/pnimages/lens_images/' . $lens['image']);
				if($size){
					$lens['image_width'] = $size[0];
				}
			}
			
			if($lens['comp_logo'] != ''){
				$sizeL = @getimagesize('http://www.eyedock.com/modules/Lenses/pnimages/comp_logos/' . $lens['comp_logo']);
				if($sizeL){
					$lens['logo_image_width'] = $sizeL[0];
				}
			}
			
			if($lens['website'] != '' && !preg_match('/^http[s]?:/i', $lens['website'])){
				$lens['website'] = 'http://www.eyedock.com/' . $lens['website'];
			}
			if($lens['fitting_guide'] != '' && !preg_match('/^http[s]?:/i', $lens['fitting_guide'])){
				$lens['fitting_guide'] = 'http://www.eyedock.com/' . $lens['fitting_guide'];
			}
			if($lens['dkt'] != ''){
				$lens['dkt'] = round($lens['dkt'], 1);
			}
			if($lens['comments'] != ''){
				if($lens['comments'] == strip_tags($lens['comments'])){
					$lens['comments'] = nl2br($lens['comments']);
				}
			}
		}
		
		
		$powerquery = "SELECT * FROM pn_lenses_powers WHERE lensID = $id";
		//echo $powerquery;
		//$db = databaseObj();
		$db->setQuery($powerquery);
        $powers = $db->loadAssocList();
        //print_r($powers);		
        if ($powers === null) JError::raiseError(500, 'Error reading db');
		        
		if(!empty($powers)) $lens['lensPowers'] = formatNumberText($powers);
		
		//print_r($lens);
	
		return $lens;
	} // end getLensDetails


    function getAdvSearch ($params) {
/* echo "<p>params:<br/> ";
print_r($params);
echo "</p>"; */
	if (!isset($params) ) return null;
		extract ($params);
		
		//echo $phrase[0];
		$vertex = 0;
		
	
		if (isset($refraction[0])) {
			$clRx[0] = $refraction[0];
			$vertex = 1;
		}
		
		if (isset($clRx[0])) {
			//echo "clRx: " . $clRx[0];
			$lensRxVals = getParametersFromRx ($clRx[0], $toric, $vertex) ;  
			
			//echo "<p>rxVals: " . print_r($lensRxVals) . "</p>";
			$sph[] = $lensRxVals['sph'];
			$cylinder[] = $lensRxVals['cylinder'];
			$oblique[] = $lensRxVals['oblique'];
			
			if(!isset($toric) ) $toric = (abs($cylinder[0]) > .7)?1:0;
			
		};
	
	
		 $tolerance = (isset($tolerance) ) ? $tolerance : 0;
		 
		 //when true will find lenses where replace <100 (1,14,30, and 90 day lenses)
		 if ($disposable[0] == "1") $replaceMax = 100;
		 

	
	$where[] = " WHERE pn_display = 1 ";    
	
	//if searching for a phrase, explode the phrase into it's component parts and search for each one 
	if (isset($phrase) ){

	
			// put all the elements of the array into a single string
			$phrase = implode(" ", $phrase);
			//break all the words into a single array
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


	if (isset($company) )  $where[] = makeWhereChunk ("pn_comp_id", $company, "OR", "=");
	if (isset($polymer) )  $where[] = makeWhereChunk ("pn_poly_id", $polymer, "OR", "=");
	if (isset($dk) )  $where[] = makeWhereChunk ("pn_dk", $dk, "AND", "<>");
	if (isset($diam) )  $where[] = makeWhereChunk ("pn_max_diam", $diam, "AND", "<>", "pn_min_diam");
	
	if (isset($h2o) )  $where[] = makeWhereChunk ("pn_h2o", $h2o, "AND", "<>");
	if (isset($oz) )  $where[] = makeWhereChunk ("pn_oz", $oz, "AND", "<>");
	if (isset($ct) )  $where[] = makeWhereChunk ("pn_ct", $ct, "AND", "<>");
	if (isset($visi) )  $where[] = makeWhereChunk ("pn_visitint", $visi, "OR", "=");
	
	if (isset($sph) && abs($sph[0]) > .25)  $where[]= makeWhereChunk ("pn_max_plus", $sph, "AND", "<>", "pn_max_minus", $tolerance);

	if (isset($bc) )  $where[] = makeWhereChunk ("pn_bc_simple", $bc, "OR", "LIKE");

	if (isset($replace) )  {
		foreach ($replace as $item) {
			$itemLo = intval($item) -3;
			$itemHi = intval($item) +3;
			$replaceChunks[] = " (pn_replace_simple >= " . $itemLo . " AND pn_replace_simple <= " . $itemHi . ")";
		}
		$where[] = "(" . implode(" OR ", $replaceChunks) . ")";
	} 
	

	if (isset($bifocal) && $bifocal == 0) $where[] = "pn_bifocal = 0 ";
	if (isset($cosmetic) && $cosmetic == 0) $where[] = "pn_cosmetic = 0 ";
	
	if ($toric == 1) $where[] = "pn_toric = 1 ";
	if ($bifocal == 1) $where[] = "pn_bifocal = 1 ";
	if ($cosmetic == 1) $where[] = "pn_cosmetic = 1 ";
	
	if ($bifocal == 1 || ( (isset($max_add) || isset($bifocal_type))) ) {
		$bifocal = 1;
		$where[] = " pn_bifocal = 1 ";
		if (isset($max_add) )  $where[] = makeWhereChunk ("pn_max_add", $max_add, "AND", ">=");
		if (isset($bifocal_type) )  $where[] = makeWhereChunk ("pn_bifocal_type", $bifocal_type, "OR", "LIKE");
	} 
	
	if ( isset($toric) && $toric == 0) {
		$where[] = "pn_toric = 0 ";
	} else if ($toric == 1 || ( (isset($axisSteps) || isset($oblique) || isset($cylinder)))  ) {
		$toric = 1;
		$where[]= "   pn_toric = 1 " ;
		if (isset($axis_steps) )  $where[] = makeWhereChunk ("pn_cyl_axis_steps ", $axis_steps, "AND", "<=");
		if ($oblique == 1 )  $where[]= "   pn_oblique >30 " ;	
		if (isset($cylinder) )  $where[]  = makeWhereChunk ("pn_max_cyl_power", $cylinder , "AND", "<=",null , $tolerance);
	} 

	
	if ($cosmetic == 1 || ( (isset($colors_enh) || isset($colors_opq) || isset($novelty))) ) {
		$cosmetic = 1;
		$where[] = " pn_cosmetic = 1 ";
		if (isset($colors_enh) )  $where[] = makeWhereChunk ("pn_enh_names_simple", $colors_enh, "OR", "LIKE");
		if (isset($colors_opq) )  $where[] = makeWhereChunk ("pn_opaque_names_simple", $colors_opq, "OR", "LIKE");
		if ($novelty == 1 )  $where[]= " pn_opaque_names_simple LIKE %novelty% " ;
	} 
	

	
	if ($onlyAvailableLenses == 1 )  $where[]= "  pn_discontinued = 0 " ;

	$where = implode(" AND ", $where);
	//print ($where);		

		  
	$sql = "SELECT pn_tid as id
		FROM pn_lenses 
		LEFT JOIN pn_lenses_companies ON pn_comp_tid = pn_comp_id
		LEFT JOIN pn_lenses_polymers ON pn_poly_tid = pn_poly_id 
		$where 
		ORDER BY pn_name"; 
		
		//echo($sql);
	
		$db = databaseObj();
        $db->setQuery($sql);
        $lenses = $db->loadResultArray();
        
        //print_r($lenses);
        
        return $lenses;
	

	
    } // end getAdvSearch
    
    function getLensesFromArray($arr) {
    	
    	if (!isset($arr) ) return null;
    	
    	$user =& JFactory::getUser();

$query = "(SELECT d.pn_tid, d.pn_name AS name, d.pn_image AS image, pnlc.pn_comp_name AS company, d.pn_max_plus, d.pn_max_minus, d.pn_max_diam, d.pn_min_diam, d.pn_diam_all, d.pn_bc_all, d.pn_max_cyl_power, d.pn_max_add, d.pn_cosmetic, d.pn_dk, d.pn_oz, d.pn_ct, pnlp.pn_h2o, d.pn_discontinued AS discontinued, pop.score, 1 AS favorite 
FROM pn_lenses d
LEFT JOIN pn_lenses_companies pnlc ON ( d.pn_comp_id = pnlc.pn_comp_tid ) 
LEFT JOIN pn_lenses_polymers pnlp ON ( d.pn_poly_id = pnlp.pn_poly_tid ) 
LEFT JOIN pn_lenses_popularity pop ON ( pop.itemID = d.pn_tid ) 
WHERE d.pn_tid IN (" . implode(',',$arr) . ") 
AND d.pn_tid IN (SELECT `item_id`  FROM pn_lenses_user_favorites WHERE `user_id`= $user->id AND `favorite` = 1)
) UNION (
SELECT d.pn_tid, d.pn_name AS name, d.pn_image AS image, pnlc.pn_comp_name AS company, d.pn_max_plus, d.pn_max_minus, d.pn_max_diam, d.pn_min_diam, d.pn_diam_all, d.pn_bc_all, d.pn_max_cyl_power, d.pn_max_add, d.pn_cosmetic, d.pn_dk, d.pn_oz, d.pn_ct, pnlp.pn_h2o, d.pn_discontinued AS discontinued, pop.score, 0 AS favorite 
FROM pn_lenses d
LEFT JOIN pn_lenses_companies pnlc ON ( d.pn_comp_id = pnlc.pn_comp_tid ) 
LEFT JOIN pn_lenses_polymers pnlp ON ( d.pn_poly_id = pnlp.pn_poly_tid ) 
LEFT JOIN pn_lenses_popularity pop ON ( pop.itemID = d.pn_tid ) 
WHERE d.pn_tid IN (" . implode(',',$arr) . ") 
AND d.pn_tid NOT IN (SELECT `item_id`  FROM pn_lenses_user_favorites WHERE `user_id`= $user->id  AND `favorite` = 1)
) ORDER BY favorite DESC , score DESC , name ASC";

	//echo "<p>" .$query . "</p>";
	
		$db = databaseObj();
        $db->setQuery($query);
        $lenses = $db->loadAssocList();
        
        if(!empty($lenses)){
			foreach($lenses as $k => $v){
				if(strstr($v['image'], ',')){
					$images = explode(',', $v['image']);
					$lenses[$k]['image'] = trim($images[0]);
				}
			}
		}
        
       
        return $lenses;
	
    	
    } // end getLensesFromArray
    
    
    
        
    //called by views/paramlist/tmpl/view.raw.php
    function findCLsByPower ($params) {
    	//print_r($params);
//     	echo "findCLsByPower";
    	$toric = $params['toric'];
    	$bifocal = $params['bifocal'];
    	$refraction = $params['refraction'][0];
    	$clRx = $params['clRx'][0];
    	
    	//echo "findCLsByPower";
    	
    	$error_msg = array() ;
    	
    	if (!isset($refraction) || !isset($clRx) ) $error_msg[] = "A refraction is needed!";
    	if ($bifocal == 1) $error_msg[] = "Sorry - Bifocal lenses cannot be searched by refraction or power. Only single vision lenses were returned.";
    	$params['bifocal'] = 0;
    	
    	//get some info about the desired power
		if (isset($refraction) ) {
			$mr = rxStringBreaker($refraction); // break it down into an Rx object
			$bestCLrx = $mr->diffVertex(0);
		} else {
			$mr = rxStringBreaker($clRx); 
			$bestCLrx = $mr;
		}
	
		$sphEqiv = $bestCLrx->sphericalEquivalent();
		$bestSphericalRx = rxStringBreaker ($sphEqiv);
		
		//remove the refraction from the $params (we'll be sending the vertexed data instead)
		unset($params['refraction'] );
		
		$sphCylRatio = 0;
		
		if ($bestCLrx->sphP() != 0) {
			$sphCylRatio = $bestCLrx->cylP()  /  abs($bestCLrx->sphP() ) ;
		} 
		
		$lenses = array();
		
		if( !isset($toric) ) {  //if not specified if toric or spherical
			//find the best spherical lenses first, if necessary
			if ($bestCLrx->cylP() < .62 || $sphCylRatio > .15 ) {
				$params['toric'] = 0;
				$params['clRx'][0] = $bestSphericalRx->prettyString();
				$lensesSph = $this->getAdvSearch ($params);
			}
			//get the toric lenses if there's enough cyl 
			if ($bestCLrx->cylP() > .62) {
				$params['toric'] = 1;
				$params['clRx'][0] = $bestCLrx->prettyString();
				$lensesCyl = $this->getAdvSearch ($params);
				
			}			
			if (isset($lensesSph) && isset($lensesCyl) ) {
				$lenses = array_merge( (array) $lenses , (array) $lensesCyl);
			} else {
				if (isset($lensesSph) ) $lenses = $lensesSph;
				if (isset($lensesCyl) ) $lenses = $lensesCyl;
			}
			
		} else { // toric IS specified
			$params['clRx'][0] = $bestCLrx->prettyString();
			$lenses = $this->getAdvSearch ($params);
		}
		//$lenses = $this->getAdvSearch ($params);
		//print_r ($lenses);
    	
    	// $data = array();
//     	
//     	$data['msg'] = $error_msg;
//         $data['lenses'] = $lenses;

        return $lenses;
    	
    }
    
    
    
        //called by views/paramlist/tmpl/view.raw.php, which passes the results of the findCLsByPower function
    function getLensPowerListsFromArray($arr, $lensCount = 25){
    	
    	$params    = JRequest::getVar( 'params', null );
    	//print_r($params);
    	// $toric = $params['toric'];
    	$refraction = $params['refraction'][0]; 
    	$clRx = $params['clRx'][0];
    	$vertex = 1;
    	
    	if (isset($params['clRx'][0]) ) {
    		$vertex = 0;
    		$refraction = $clRx;
    	}
    	
    	if (!isset($refraction ) ) return null;
    	
    	//print_r($refraction);
    	
    	$user =& JFactory::getUser();
    /*
    	$query = "
(SELECT pnl.pn_tid, pnl.pn_name as name, pnl.pn_image as image, pnlc.pn_comp_name as company, pnl.pn_cosmetic,  pnl.pn_toric as toric, pnl.pn_dk, pop.score, pnl.pn_discontinued as discontinued,  1 AS favorite
FROM pn_lenses pnl 
LEFT JOIN pn_lenses_companies pnlc ON (pnl.pn_comp_id = pnlc.pn_comp_tid) 
LEFT JOIN pn_lenses_polymers pnlp ON (pnl.pn_poly_id = pnlp.pn_poly_tid) 
LEFT JOIN pn_lenses_user_favorites c ON c.item_id = pnl.pn_tid 
LEFT JOIN pn_lenses_popularity pop ON (pop.itemID = pnl.pn_tid) 
WHERE c.user_id = $user->id AND c. favorite = 1
AND pnl.pn_tid IN (" . implode(',',$arr) . ") 
AND pnl.pn_name NOT LIKE '%synergeye%' AND pnl.pn_name NOT LIKE '%softperm%' 
AND pn_bifocal = 0)
UNION DISTINCT
(SELECT pnl.pn_tid, pnl.pn_name as name, pnl.pn_image as image, pnlc.pn_comp_name as company, pnl.pn_cosmetic, pnl.pn_toric as toric,  pnl.pn_dk, pop.score, pnl.pn_discontinued as discontinued, 0 as favorite
FROM pn_lenses pnl 
LEFT JOIN pn_lenses_companies pnlc ON (pnl.pn_comp_id = pnlc.pn_comp_tid) 
LEFT JOIN pn_lenses_polymers pnlp ON (pnl.pn_poly_id = pnlp.pn_poly_tid) 
LEFT JOIN pn_lenses_popularity pop ON (pop.itemID = pnl.pn_tid) 
WHERE NOT EXISTS (SELECT *
                   FROM   pn_lenses_user_favorites f
                   WHERE  f.item_id = pnl.pn_tid
AND f.user_id = $user->id AND f.favorite=1
)
AND pnl.pn_tid IN (" . implode(',',$arr) . ") 
AND pnl.pn_name NOT LIKE '%synergeye%' AND pnl.pn_name NOT LIKE '%softperm%' 
AND pn_bifocal = 0)

 order by favorite DESC, score DESC, name ASC";
 */
 
 $query = "(SELECT d.pn_tid, d.pn_name as name, d.pn_image as image, d.pn_replace_simple as replace_s, pnlc.pn_comp_name as company, d.pn_cosmetic,  d.pn_toric as toric, d.pn_dk, pop.score, d.pn_discontinued as discontinued,  1 AS favorite
FROM pn_lenses d
LEFT JOIN pn_lenses_companies pnlc ON ( d.pn_comp_id = pnlc.pn_comp_tid ) 
LEFT JOIN pn_lenses_polymers pnlp ON ( d.pn_poly_id = pnlp.pn_poly_tid ) 
LEFT JOIN pn_lenses_popularity pop ON ( pop.itemID = d.pn_tid ) 
WHERE d.pn_tid IN (" . implode(',',$arr) . ") 
AND d.pn_tid IN (SELECT `item_id`  FROM pn_lenses_user_favorites WHERE `user_id`= $user->id AND `favorite` = 1)
AND d.pn_name NOT LIKE '%synergeye%' AND d.pn_name NOT LIKE '%softperm%' 
AND pn_bifocal = 0
) UNION (
SELECT d.pn_tid, d.pn_name as name, d.pn_image as image, d.pn_replace_simple as replace_s, pnlc.pn_comp_name as company, d.pn_cosmetic,  d.pn_toric as toric, d.pn_dk, pop.score, d.pn_discontinued as discontinued,  0 AS favorite
FROM pn_lenses d
LEFT JOIN pn_lenses_companies pnlc ON ( d.pn_comp_id = pnlc.pn_comp_tid ) 
LEFT JOIN pn_lenses_polymers pnlp ON ( d.pn_poly_id = pnlp.pn_poly_tid ) 
LEFT JOIN pn_lenses_popularity pop ON ( pop.itemID = d.pn_tid ) 
WHERE d.pn_tid IN (" . implode(',',$arr) . ") 
AND d.pn_tid NOT IN (SELECT `item_id`  FROM pn_lenses_user_favorites WHERE `user_id`= $user->id  AND `favorite` = 1)
AND d.pn_name NOT LIKE '%synergeye%' AND d.pn_name NOT LIKE '%softperm%' 
AND pn_bifocal = 0
) ORDER BY  favorite DESC , score DESC , replace_s ASC, discontinued ASC  LIMIT " . $lensCount; 
    
		
		$db = databaseObj();
        $db->setQuery($query);
        $lenses = $db->loadAssocList();
        
       //echo $query;
       //print_r($lenses);
       $returnLenses = array();
        
        if(!empty($lenses)){
			foreach($lenses as $k => $v){
				//comvert image string to an array
				if(strstr($v['image'], ',')){
					$images = explode(',', $v['image']);
					$v['image'] = trim($images[count($images) - 1]);
				}
				//get an array for this lens's powers
				$powerQuery = "SELECT lpl.sphere,  lpl.cylinder, lpl.axis, lp.diameter, lp.baseCurve FROM pn_lenses_power_lists lpl LEFT JOIN pn_lenses_powers lp ON lpl.id=lp.id WHERE lpl.lensID = " . $v['pn_tid'];
				$db->setQuery($powerQuery);
        		$lensPowerArr = $db->loadAssocList();
        		
        		//print_r($lensPowerArr);
				//$lensPowers = $this->getPowerArrayForLens ($v['pn_tid']);
				$resultArray = getBestCLPowerForParams ($refraction, $lensPowerArr, $v['toric'], $vertex)  ;
				foreach ($resultArray as $oneVariation){
// 					echo "<br/>vari:<br/>";
// 					print_r ($oneVariation);
// 					echo "<br/>lens:<br/>";
// 					print_r ($v);
					$row = array_merge($v, $oneVariation);
					array_push($returnLenses, $row);
				}
				//print_r($resultArray);
				//
				//print_r($returnLenses);
			}
		}
		usort($returnLenses, "vaSorter" );
		//print_r($returnLenses);
		/*array_multisort($returnLenses['va'], SORT_ASC, SORT_NUMERIC,
                $returnLenses['favorite'], SORT_NUMERIC, SORT_DESC);*/
		
		//filter out the worst lenses
		$returnLenses = removeVAOutliers($returnLenses);
        //echo "<p>----</p>";
       // print_r($returnLenses);
        
        return $returnLenses;        
		
		
    } // end getLensPowerListsFromArray
   
 	
       
} // class ends

function vaSorter($a, $b) {
	//return $a['va'] - $b['va'];
	
	//always return a favorite higher...
	if ($a['favorite'] != $b['favorite'] ) return $b['favorite'] - $a['favorite'];
	
	if ($a['va'] == $b['va']) {
        if ($a['favorite'] == $b['favorite']){
        	return $b['score'] - $a['score'];
        }
        return $b['favorite'] - $a['favorite'];
    }

    return $a['va'] - $b['va'];
}

function removeVAOutliers($lensesArray) {
	//if changes made here consider altering the function in the api: /api/phpClasses/CLHelper.php

	$threshold = 20;  //how big of a difference in VA will be acceptable (eg 20/40 vs 20/20 = 20)

	//the array to return
	$returnArray = array();
	
	//find the best VA of the bunch
	$bestVA = null;
		
	foreach ($lensesArray as $lens){
		$va = $lens['va'];
		//echo "<p>$va</p>";
		if ($bestVA == null || $va < $bestVA) $bestVA = $va;
	}
	
	
	//echo "<p>Best VA: $bestVA</p>";
	//compare each lens's VA to the best VA. If it's less more than [threshold] points away, don't include it
	foreach ($lensesArray as $lens){
		$va = $lens['va'];
		//echo "<p>$va</p>";
		if (abs($va - $bestVA) < $threshold ) array_push($returnArray, $lens);
	}
	
	return $returnArray;
}
