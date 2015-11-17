<?php



// ini_set('display_errors', 1); 
// ini_set('log_errors', 1); 
// ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
// error_reporting(E_ALL);
// ini_set('html_errors', 'On');


defined('_JEXEC') or die();

jimport('joomla.application.component.model');
require_once( JPATH_COMPONENT.DS.'helpers/powerLists/formatNumberText.php' );
require_once( JPATH_COMPONENT.DS.'helpers/phpClasses/CLHelper.php' );
//require_once( JPATH_COMPONENT.DS.'helpers/sqlHelper/dbObject.php' );
require_once( JPATH_COMPONENT.DS.'helpers/sqlHelper/whereChunks.php' );
require_once( JPATH_COMPONENT.DS.'helpers/utilities/parametersFromRx.php' );
//require_once( JPATH_COMPONENT.DS.'helpers/powerLists/makePowerLists.php' );
require_once(JPATH_ROOT.DS.'utilities/database.php');

//define('EYEDOCK_LENS_IMG_URL', 'http://www.eyedock.com/modules/Lenses/pnimages/lens_images');
//define('EYEDOCK_LENS_COMP_IMG_URL', 'http://www.eyedock.com/modules/Lenses/pnimages/comp_logos');


class PnlensesModelPnlenses extends JModel {
       
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
       
       //deprecate
    function getLensSearchResults($phrase) {
    		$query = "select pnl.pn_tid, pnl.pn_name as name, pnl.pn_image as image, pnlc.pn_comp_name as company, 
	pnl.pn_max_plus, pnl.pn_max_minus, pnl.pn_max_diam, pnl.pn_min_diam, pnl.pn_diam_all, pnl.pn_bc_all, pnl.pn_max_cyl_power, pnl.pn_max_add, pnl.pn_cosmetic, pnl.pn_dk, pnl.pn_oz, pnl.pn_ct, pnlp.pn_h2o,
	pnl.pn_discontinued as discontinued 
	from 
	pn_lenses pnl 
	left join pn_lenses_companies pnlc on (pnl.pn_comp_id = pnlc.pn_comp_tid)
	left join pn_lenses_polymers pnlp on (pnl.pn_poly_id = pnlp.pn_poly_tid)
	WHERE pnl.pn_name LIKE '%$phrase%' 
	order by name asc
	
	";
	
	//echo $query;
	
		$db = databaseObj();
        $db->setQuery($query);
        $lenses = $db->loadAssocList();
        
        return $lenses;
    
    }
       
		   
	function getLensDetails($id){
	
		$id = abs(intval($id));
	
	
		$query = "select pnl.pn_tid as id, 
		pnl.pn_name as name,
		pnl.pn_image as image, 
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
		ufav.favorite, 
		pop.score
		from 
		pn_lenses pnl 
		left join pn_lenses_companies pnlc on (pnl.pn_comp_id = pnlc.pn_comp_tid) 
		left join pn_lenses_polymers pnlp on (pnl.pn_poly_id = pnlp.pn_poly_tid) 
		left join pn_lenses_user_favorites ufav on (ufav.item_id = pnl.pn_tid)
		left join pn_lenses_popularity pop on (pop.itemID = pnl.pn_tid)
		where 
		pnl.pn_tid = $id";
	
	//echo $query;
	
		$db = databaseObj();
        $db->setQuery($query);
        $lens = $db->loadAssoc();
        
        
             
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

	//print_r($params);
		
		extract ($params);
		
		//echo $phrase[0];
		$vertex = 0;
		
	
		if (isset($refraction[0])) {
			$clRx[0] = $refraction[0];
			$vertex = 1;
		}
		
		if (isset($clRx[0])) {
			$lensRxVals = getParametersFromRx ($clRx[0], $toric, $vertex) ;  
			//print_r($lensRxVals);
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
	
	if (isset($sph) )  $where[]= makeWhereChunk ("pn_max_plus", $sph, "AND", "<>", "pn_max_minus", $tolerance);

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
        
        return $lenses;
	

	
    } // end getAdvSearch
    
    function getLensesFromArray($arr) {
s$query = "SELECT d.pn_tid, d.pn_name as name, d.pn_image as image, pnlc.pn_comp_name as company,s	d.pn_max_plus, d.pn_max_minus, d.pn_max_diam, d.pn_min_diam, d.pn_diam_all, d.pn_bc_all, d.pn_max_cyl_power, d.pn_max_add, d.pn_cosmetic, d.pn_dk, d.pn_oz, d.pn_ct,pnlp.pn_h2o,sd.pn_discontinued as discontinued, pop.score,sCOALESCE(c.`favorite`, 0) FavoritesFROMs(sSELECT a.pn_tid, a.pn_comp_id, a.pn_poly_id, a.pn_name, a.pn_image, b.user_id,sa.pn_max_plus, a.pn_max_minus, a.pn_max_diam, a.pn_min_diam, a.pn_diam_all, a.pn_bc_all, a.pn_max_cyl_power, a.pn_max_add, a.pn_cosmetic, a.pn_dk, a.pn_oz, a.pn_ct,s	a.pn_discontinuedsFROM pn_lenses a CROSS JOINs	(sSELECT DISTINCT user_idsFROM pn_lenses_user_favoritess) bs) d LEFT JOIN pn_lenses_user_favorites csON c.item_id = d.pn_tid ANDsc.user_id = d.user_idsleft join pn_lenses_companies pnlc on (d.pn_comp_id = pnlc.pn_comp_tid)sleft join pn_lenses_polymers pnlp on (d.pn_poly_id = pnlp.pn_poly_tid)sleft join pn_lenses_popularity pop on (pop.itemID = d.pn_tid)sWHERE d.user_id = 77 AND d.pn_tid INs(" . implode(',',$arr) . ")sORDER BY favorite DESC, score DESC, name ASC";		
		
		//echo $query;
	
		$db = dbObject();
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
    
    
    

    
    
    function getLensPowerListsFromArray($arr){
    	
    	$params    = JRequest::getVar( 'params', null );
    	// $toric = $params['toric'];
    	$refraction = $params['refraction'][0]; 
    	if ($refraction == "") return null;
    
    	$query = "SELECT pnl.pn_tid, pnl.pn_name as name, pnl.pn_image as image, pnlc.pn_comp_name as company, pnl.pn_toric as toric,
		pnl.pn_cosmetic, pnl.pn_dk, ufav.favorite, pop.score,
		pnl.pn_discontinued as discontinued 
		FROM 
		pn_lenses pnl 
		left join pn_lenses_companies pnlc on (pnl.pn_comp_id = pnlc.pn_comp_tid)
		left join pn_lenses_polymers pnlp on (pnl.pn_poly_id = pnlp.pn_poly_tid)
		left join pn_lenses_user_favorites ufav on (ufav.item_id = pnl.pn_tid)
		left join pn_lenses_popularity pop on (pop.itemID = pnl.pn_tid)
		WHERE pnl.pn_tid IN (" . implode(',',$arr) . ")
		AND pnl.pn_name NOT LIKE '%synergeye%' AND pnl.pn_name NOT LIKE '%softperm%' AND pn_bifocal = 0
		order by favorite DESC, score DESC, name ASC";
		
		$db = databaseObj();
        $db->setQuery($query);
        $lenses = $db->loadAssocList();
        
       $returnLenses = array();
        
        if(!empty($lenses)){
			foreach($lenses as $k => $v){
				//comvert image string to an array
				if(strstr($v['image'], ',')){
					$images = explode(',', $v['image']);
					$v['image'] = trim($images[count($images) - 1]);
				}
				//get an array for this lens's powers
				$powerQuery = "SELECT lpl.sphere, lpl.cylinder, lpl.axis, lp.diameter, lp.baseCurve FROM pn_lenses_power_lists lpl LEFT JOIN pn_lenses_powers lp ON lpl.id=lp.id WHERE lpl.lensID = " . $v['pn_tid'];
				$db->setQuery($powerQuery);
        		$lensPowerArr = $db->loadAssocList();
        		
        		//print_r($lensPowerArr[0]);
				//$lensPowers = $this->getPowerArrayForLens ($v['pn_tid']);
				$resultArray = getBestCLPowerForParams ($refraction, $lensPowerArr, $v['toric'])  ;
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
        
        return $returnLenses;        
		
		
    } // end getLensPowerListsFromArray
   
 	
       
} // class ends

function vaSorter($a, $b) {
	return $a['va'] - $b['va'];
}


