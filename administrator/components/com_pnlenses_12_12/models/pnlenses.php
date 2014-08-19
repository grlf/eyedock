<?php

defined('_JEXEC') or die();

// include '/Applications/MAMP/htdocs/eyedock.com/debug/ChromePhp.php';
// ChromePhp::log('hello world');
// ChromePhp::log($_SERVER); 

// ini_set('display_errors', 1); 
// ini_set('log_errors', 1); 
// ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
// error_reporting(E_ALL);
// ini_set('html_errors', 'On');

include_once (JPATH_SITE.DS. "api/dataGetters.php");
 
jimport( 'joomla.application.component.model' );

class PnlensesModelPnlenses extends JModel {
	
     const GPHOST = 'mysql.eyedock.com';
     const GPUSER = 'eyedockdatauser';
     const GPPASS = 'kvBS^VQR';
     const GPDB = 'eyedock_data';
	//also update database connections in updatePowerListsTable (below)
	
	
	const LENSIMGURL = '';
	const LENSPDFURL = '';

	function addNewLens($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['aliases']))
			$data['aliases'] = '';
		if(empty($data['company']))
			$data['company'] = 'NULL';
		if(empty($data['polymer']))
			$data['polymer'] = 'NULL';
		if(empty($data['visitint']))
			$data['visitint'] = '0';
		if(empty($data['ew']))
			$data['ew'] = '0';
		if(empty($data['centerthickness']))
			$data['centerthickness'] = 'NULL';
		if(empty($data['dk']))
			$data['dk'] = 'NULL';
		if(empty($data['opticzone']))
			$data['opticzone'] = '';
		if(empty($data['processtext']))
			$data['processtext'] = '';
		if(empty($data['processsimple']))
			$data['processsimple'] = '';
		if(empty($data['qty']))
			$data['qty'] = '';
		if(empty($data['replacesimple']))
			$data['replacesimple'] = 'NULL';
		if(empty($data['replacetext']))
			$data['replacetext'] = '';
		if(empty($data['wear']))
			$data['wear'] = '';
		if(empty($data['price']))
			$data['price'] = '';
		if(empty($data['markings']))
			$data['markings'] = '';
		if(empty($data['fittingguide']))
			$data['fittingguide'] = '';
		if(empty($data['website']))
			$data['website'] = '';
		if(empty($data['image']))
			$data['image'] = '';
		if(empty($data['otherinfo']))
			$data['otherinfo'] = '';
		if(empty($data['discontinued']))
			$data['discontinued'] = '0';
		if(empty($data['display']))
			$data['display'] = '1';
		if(empty($data['favorited']))
			$data['favorited'] = '0';
		if(empty($data['bcsimple']))
			$data['bcsimple'] = '';
		if(empty($data['bcall']))
			$data['bcall'] = '';
		if(empty($data['diamall']))
			$data['diamall'] = '';
		if(empty($data['maxplus']))
			$data['maxplus'] = 'NULL';
		if(empty($data['maxminus']))
			$data['maxminus'] = 'NULL';
		if(empty($data['maxdiam']))
			$data['maxdiam'] = 'NULL';
		if(empty($data['mindiam']))
			$data['mindiam'] = 'NULL';
		if(empty($data['diameter1']))
			$data['diameter1'] = '';
		if(empty($data['diameter2']))
			$data['diameter2'] = '';
		if(empty($data['diameter3']))
			$data['diameter3'] = '';
		if(empty($data['basecurves1']))
			$data['basecurves1'] = '';
		if(empty($data['basecurves2']))
			$data['basecurves2'] = '';
		if(empty($data['basecurves3']))
			$data['basecurves3'] = '';
		if(empty($data['powers1']))
			$data['powers1'] = '';
		if(empty($data['powers2']))
			$data['powers2'] = '';
		if(empty($data['powers3']))
			$data['powers3'] = '';
		if(empty($data['sphnotes']))
			$data['sphnotes'] = '';
		if(empty($data['toric']))
			$data['toric'] = '0';
		if(empty($data['torictype']))
			$data['torictype'] = '';
		if(empty($data['torictypesimple']))
			$data['torictypesimple'] = '';
		if(empty($data['cylpower']))
			$data['cylpower'] = '';
		if(empty($data['maxcylpower']))
			$data['maxcylpower'] = 'NULL';
		if(empty($data['cylaxis']))
			$data['cylaxis'] = '';
		if(empty($data['cylaxissteps']))
			$data['cylaxissteps'] = 'NULL';
		if(empty($data['oblique']))
			$data['oblique'] = 'NULL';
		if(empty($data['cylnotes']))
			$data['cylnotes'] = '';
		if(empty($data['bifocal']))
			$data['bifocal'] = '0';
		if(empty($data['bifocaltype']))
			$data['bifocaltype'] = '';
		if(empty($data['addtext']))
			$data['addtext'] = '';
		if(empty($data['maxadd']))
			$data['maxadd'] = '0';
		if(empty($data['cosmetic']))
			$data['cosmetic'] = '0';
		if(empty($data['enhnames']))
			$data['enhnames'] = '';
		if(empty($data['enhsimplenames']))
			$data['enhsimplenames'] = '';
		if(empty($data['opaquenames']))
			$data['opaquenames'] = '';
		if(empty($data['opaquesimplenames']))
			$data['opaquesimplenames'] = '';
		if(is_array($data['bcsimple']))
			$data['bcsimple'] = implode(',',$data['bcsimple']);
		if(is_array($data['enhsimplenames']))
			$data['enhsimplenames'] = implode(',',$data['enhsimplenames']);
		if(is_array($data['opaquesimplenames']))
			$data['opaquesimplenames'] = implode(',',$data['opaquesimplenames']);
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'INSERT INTO pn_lenses (pn_name, pn_aliases, pn_comp_id, pn_poly_id, pn_visitint, pn_ew, pn_ct, pn_dk, pn_oz, pn_process_text, pn_process_simple, pn_qty, pn_replace_simple, pn_replace_text, pn_wear, pn_price, pn_markings, pn_fitting_guide, pn_website, pn_image, pn_other_info, pn_discontinued, pn_display, pn_favorited, pn_bc_simple, pn_bc_all, pn_diam_all, pn_max_plus, pn_max_minus, pn_max_diam, pn_min_diam, pn_diam_1, pn_base_curves_1, pn_powers_1, pn_diam_2, pn_base_curves_2, pn_powers_2, pn_diam_3, pn_base_curves_3, pn_powers_3, pn_sph_notes, pn_toric, pn_toric_type, pn_toric_type_simple, pn_cyl_power, pn_max_cyl_power, pn_cyl_axis, pn_cyl_axis_steps, pn_oblique, pn_cyl_notes, pn_bifocal, pn_bifocal_type, pn_add_text, pn_max_add, pn_cosmetic, pn_enh_names, pn_enh_names_simple, pn_opaque_names, pn_opaque_names_simple, pn_updated) VALUES (
			"' . sanitize($data['name']) . '", 
			"' . sanitize($data['aliases']) . '", 
			' . $data['company'] . ', 
			' . $data['polymer'] . ', 
			' . $data['visitint'] . ', 
			' . $data['ew'] . ', 
			' . $data['centerthickness'] . ', 
			' . $data['dk'] . ', 
			"' . sanitize($data['opticzone']) . '", 
			"' . sanitize($data['processtext']) . '", 
			"' . sanitize($data['processsimple']) . '", 
			"' . sanitize($data['qty']) . '", 
			' . $data['replacesimple'] . ', 
			"' . sanitize($data['replacetext']) . '", 
			"' . sanitize($data['wear']) . '", 
			"' . sanitize($data['price']) . '", 
			"' . sanitize($data['markings']) . '", 
			"' . sanitize($data['fittingguide']) . '", 
			"' . sanitize($data['website']) . '", 
			"' . sanitize($data['image']) . '", 
			"' . sanitize($data['otherinfo']) . '", 
			' . $data['discontinued'] . ', 
			' . $data['display'] . ', 
			' . $data['favorited'] . ',
			"' . sanitize($data['bcsimple']) . '", 
			"' . sanitize($data['bcall']) . '", 
			"' . sanitize($data['diamall']) . '", 
			' . $data['maxplus'] . ', 
			' . $data['maxminus'] . ', 
			' . $data['maxdiam'] . ', 
			' . $data['mindiam'] . ', 
			"' . sanitize($data['diameter1']) . '", 
			"' . sanitize($data['basecurves1']) . '", 
			"' . sanitize($data['powers1']) . '", 
			"' . sanitize($data['diameter2']) . '", 
			"' . sanitize($data['basecurves2']) . '", 
			"' . sanitize($data['powers2']) . '", 
			"' . sanitize($data['diameter3']) . '", 
			"' . sanitize($data['basecurves3']) . '", 
			"' . sanitize($data['powers3']) . '", 
			"' . sanitize($data['sphnotes']) . '", 
			' . $data['toric'] . ', 
			"' . sanitize($data['torictype']) . '", 
			"' . sanitize($data['torictypesimple']) . '", 
			"' . sanitize($data['cylpower']) . '", 
			' . $data['maxcylpower'] . ', 
			"' . sanitize($data['cylaxis']) . '", 
			' . $data['cylaxissteps'] . ', 
			' . $data['oblique'] . ', 
			"' . sanitize($data['cylnotes']) . '", 
			' . $data['bifocal'] . ', 
			"' . sanitize($data['bifocaltype']) . '", 
			"' . sanitize($data['addtext']) . '", 
			' . $data['maxadd'] . ', 
			' . $data['cosmetic'] . ', 
			"' . sanitize($data['enhnames']) . '", 
			"' . sanitize($data['enhsimplenames']) . '", 
			"' . sanitize($data['opaquenames']) . '", 
			"' . sanitize($data['opaquesimplenames']) . '", 
			(SELECT NOW())     
			)';
			
		//echo $query;

	
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}
	
	}
	
	function updateLens($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['aliases']))
			$data['aliases'] = '';
		if(empty($data['company']))
			$data['company'] = 'NULL';
		if(empty($data['polymer']))
			$data['polymer'] = 'NULL';
		if(empty($data['visitint']))
			$data['visitint'] = '0';
		if(empty($data['ew']))
			$data['ew'] = '0';
		if(empty($data['centerthickness']))
			$data['centerthickness'] = 'NULL';
		if(empty($data['dk']))
			$data['dk'] = 'NULL';
		if(empty($data['opticzone']))
			$data['opticzone'] = '';
		if(empty($data['processtext']))
			$data['processtext'] = '';
		if(empty($data['processsimple']))
			$data['processsimple'] = '';
		if(empty($data['qty']))
			$data['qty'] = '';
		if(empty($data['replacesimple']))
			$data['replacesimple'] = 'NULL';
		if(empty($data['replacetext']))
			$data['replacetext'] = '';
		if(empty($data['wear']))
			$data['wear'] = '';
		if(empty($data['price']))
			$data['price'] = '';
		if(empty($data['markings']))
			$data['markings'] = '';
		if(empty($data['fittingguide']))
			$data['fittingguide'] = '';
		if(empty($data['website']))
			$data['website'] = '';
		if(empty($data['image']))
			$data['image'] = '';
		if(empty($data['otherinfo']))
			$data['otherinfo'] = '';
		if(empty($data['discontinued']))
			$data['discontinued'] = '0';
		if(empty($data['display']))
			$data['display'] = '1';
		if(empty($data['bcsimple']))
			$data['bcsimple'] = '';
		if(empty($data['bcall']))
			$data['bcall'] = '';
		if(empty($data['diamall']))
			$data['diamall'] = '';
		if(empty($data['maxplus']))
			$data['maxplus'] = 'NULL';
		if(empty($data['maxminus']))
			$data['maxminus'] = 'NULL';
		if(empty($data['maxdiam']))
			$data['maxdiam'] = 'NULL';
		if(empty($data['mindiam']))
			$data['mindiam'] = 'NULL';
		if(empty($data['diameter1']))
			$data['diameter1'] = '';
		if(empty($data['diameter2']))
			$data['diameter2'] = '';
		if(empty($data['diameter3']))
			$data['diameter3'] = '';
		if(empty($data['basecurves1']))
			$data['basecurves1'] = '';
		if(empty($data['basecurves2']))
			$data['basecurves2'] = '';
		if(empty($data['basecurves3']))
			$data['basecurves3'] = '';
		if(empty($data['powers1']))
			$data['powers1'] = '';
		if(empty($data['powers2']))
			$data['powers2'] = '';
		if(empty($data['powers3']))
			$data['powers3'] = '';
		if(empty($data['sphnotes']))
			$data['sphnotes'] = '';
		if(empty($data['toric']))
			$data['toric'] = '0';
		if(empty($data['torictype']))
			$data['torictype'] = '';
		if(empty($data['torictypesimple']))
			$data['torictypesimple'] = '';
		if(empty($data['cylpower']))
			$data['cylpower'] = '';
		if(empty($data['maxcylpower']))
			$data['maxcylpower'] = 'NULL';
		if(empty($data['cylaxis']))
			$data['cylaxis'] = '';
		if(empty($data['cylaxissteps']))
			$data['cylaxissteps'] = 'NULL';
		if(empty($data['oblique']))
			$data['oblique'] = 'NULL';
		if(empty($data['cylnotes']))
			$data['cylnotes'] = '';
		if(empty($data['bifocal']))
			$data['bifocal'] = '0';
		if(empty($data['bifocaltype']))
			$data['bifocaltype'] = '';
		if(empty($data['addtext']))
			$data['addtext'] = '';
		if(empty($data['maxadd']))
			$data['maxadd'] = '0';
		if(empty($data['cosmetic']))
			$data['cosmetic'] = '0';
		if(empty($data['enhnames']))
			$data['enhnames'] = '';
		if(empty($data['enhsimplenames']))
			$data['enhsimplenames'] = '';
		if(empty($data['opaquenames']))
			$data['opaquenames'] = '';
		if(empty($data['opaquesimplenames']))
			$data['opaquesimplenames'] = '';
		if(is_array($data['bcsimple']))
			$data['bcsimple'] = implode(',',$data['bcsimple']);
		if(is_array($data['enhsimplenames']))
			$data['enhsimplenames'] = implode(',',$data['enhsimplenames']);
		if(is_array($data['opaquesimplenames']))
			$data['opaquesimplenames'] = implode(',',$data['opaquesimplenames']);
			
			//echo $data['otherinfo'];
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'UPDATE pn_lenses SET pn_name = "' . sanitize($data['name']) . '", pn_aliases = "' . sanitize($data['aliases']) . '", pn_comp_id = ' . $data['company'] . ', pn_poly_id = ' . $data['polymer'] . ', pn_visitint = ' . $data['visitint'] . ', pn_ew = ' . $data['ew'] . ', pn_ct = ' . $data['centerthickness'] . ', pn_dk = "' . $data['dk'] . '", pn_oz = "' . sanitize($data['opticzone']) . '", pn_process_text = "' . sanitize($data['processtext']) . '", pn_process_simple = "' . sanitize($data['processsimple']) . '", pn_qty = "' . sanitize($data['qty']) . '", pn_replace_simple = ' . $data['replacesimple'] . ', pn_replace_text = "' . sanitize($data['replacetext']) . '", pn_wear = "' . sanitize($data['wear']) . '", pn_price = "' . sanitize($data['price']) . '", pn_markings = "' . sanitize($data['markings']) . '", pn_fitting_guide = "' . sanitize($data['fittingguide']) . '", pn_website = "' . sanitize($data['website']) . '", pn_image = "' . sanitize($data['image']) . '", pn_other_info = "' . sanitize($data['otherinfo']) . '", pn_discontinued = ' . $data['discontinued'] . ', pn_display = ' . $data['display'] . ', pn_bc_simple = "' . sanitize($data['bcsimple']) . '", pn_bc_all = "' . sanitize($data['bcall']) . '", pn_diam_all = "' . sanitize($data['diamall']) . '", pn_max_plus = ' . $data['maxplus'] . ', pn_max_minus = ' . $data['maxminus'] . ', pn_max_diam = ' . $data['maxdiam'] . ', pn_min_diam = ' . $data['mindiam'] . ', pn_diam_1 = "' . sanitize($data['diameter1']) . '", pn_base_curves_1 = "' . sanitize($data['basecurves1']) . '", pn_powers_1 = "' . sanitize($data['powers1']) . '", pn_diam_2 = "' . sanitize($data['diameter2']) . '", pn_base_curves_2 = "' . sanitize($data['basecurves2']) . '", pn_powers_2 = "' . sanitize($data['powers2']) . '", pn_diam_3 = "' . sanitize($data['diameter3']) . '", pn_base_curves_3 = "' . sanitize($data['basecurves3']) . '", pn_powers_3 = "' . sanitize($data['powers3']) . '", pn_sph_notes = "' . sanitize($data['sphnotes']) . '", pn_toric = ' . $data['toric'] . ', pn_toric_type = "' . sanitize($data['torictype']) . '", pn_toric_type_simple = "' . sanitize($data['torictypesimple']) . '", pn_cyl_power = "' . sanitize($data['cylpower']) . '", pn_max_cyl_power = ' . $data['maxcylpower'] . ', pn_cyl_axis = "' . sanitize($data['cylaxis']) . '", pn_cyl_axis_steps = ' . $data['cylaxissteps'] . ', pn_oblique = ' . $data['oblique'] . ', pn_cyl_notes = "' . sanitize($data['cylnotes']) . '", pn_bifocal = ' . $data['bifocal'] . ', pn_bifocal_type = "' . sanitize($data['bifocaltype']) . '", pn_add_text = "' . sanitize($data['addtext']) . '", pn_max_add = ' . $data['maxadd'] . ', pn_cosmetic = ' . $data['cosmetic'] . ', pn_enh_names = "' . sanitize($data['enhnames']) . '", pn_enh_names_simple = "' . sanitize($data['enhsimplenames']) . '", pn_opaque_names = "' . sanitize($data['opaquenames']) . '", pn_opaque_names_simple = "' . sanitize($data['opaquesimplenames']) . '", pn_updated = (SELECT NOW()) where pn_tid = ' . $data['id'];
			
	//echo $query;

		$resultarr[] = $mysqli->query($query);
	
	//make 3 arrays of sets of lens powers: 1) new sets of powers 2) edited powers 3) sets to delete
	$newArr = array();
	$editArr = array();
	$deleteArr = array();
	

	for($i=0;$i<count($data['lenspowers']);$i++) {
		
		$tempPwrSet = $data['lenspowers'][$i];
		
		if ($tempPwrSet['id'] =="new") {
		
			 $newArr[] = $tempPwrSet;
			 
		} elseif ($tempPwrSet['delete'] == "1") {
		
			$deleteArr[] = $tempPwrSet;
		
		} elseif (is_numeric($tempPwrSet['id'])  ) {
		
			$editArr[] =  $tempPwrSet;
		}
	}
	
	//the lens power rows that will be deleted
	for($i=0;$i<count($deleteArr);$i++) {
		
		if (is_numeric( $deleteArr[$i]['id']) ) {
			$query = 'DELETE from pn_lenses_powers where id = ' . $deleteArr[$i]['id'];
			$resultarr[] = $mysqli->query($query);
			//also delete the corresponding pn_lenses_power_lists table
			$query2 = 'DELETE from pn_lenses_power_lists where id = ' . $deleteArr[$i]['id'];
			$resultarr[] = $mysqli->query($query2);
			
		}
	
	}

	//the lens power rows that will be edited
		for($i=0;$i<count($editArr);$i++) {
				$baseCurve = trimAndDefault (  $editArr[$i]['baseCurve'] , "");
				$diameter = trimAndDefault ( 	 $editArr[$i]['diameter'] , "");
				$sphere = trimAndDefault ( 	 $editArr[$i]['sphere'] , "");
				$cylinder = trimAndDefault ( 	 $editArr[$i]['cylinder'] , "");
				$axis = trimAndDefault ( 		 $editArr[$i]['axis'] , "");
				$add = trimAndDefault ( 		 $editArr[$i]['add'] , "");
				$colors_enh = trimAndDefault ( $editArr[$i]['colors_enh'] , "");
				$colors_opq = trimAndDefault ( $editArr[$i]['colors_opq'] , "");
			

				$query = 'UPDATE pn_lenses_powers SET 
				baseCurve = "' . $baseCurve . '",
				diameter = "' . $diameter . '",
				sphere = "' . $sphere . '",
				cylinder = "' . $cylinder . '",
				axis = "' . $axis . '",
				addPwr = "' . $add . '",
				colors_enh = "' . $colors_enh . '",
				colors_opq = "' . $colors_opq . '" 
				WHERE id = ' . $editArr[$i]['id'];
			
				$resultarr[] = $mysqli->query($query);
				
				//echo 'QUERY: ' . $query . '<br /><br /><br />';
//ChromePhp::log($query);
		
	
			}
			
			//the lens power rows that will be added (new rows)
			for($i=0;$i<count($newArr);$i++) {
			
				$baseCurve = trimAndDefault (  $newArr[$i]['baseCurve'] , "");
				$diameter = trimAndDefault ( 	 $newArr[$i]['diameter'] , "");
				$sphere = trimAndDefault ( 	 $newArr[$i]['sphere'] , "");
				$cylinder = trimAndDefault ( 	 $newArr[$i]['cylinder'] , "");
				$axis = trimAndDefault ( 		 $newArr[$i]['axis'] , "");
				$add = trimAndDefault ( 		 $newArr[$i]['add'] , "");
				$colors_enh = trimAndDefault ( $newArr[$i]['colors_enh'] , "");
				$colors_opq = trimAndDefault ( $newArr[$i]['colors_opq'] , "");
			

				$query = 'INSERT INTO pn_lenses_powers (id, lensID, baseCurve, diameter, sphere, cylinder, axis, addPwr, colors_enh, colors_opq)  VALUES (NULL,
				' . $data['id'] . ',
				"' . $baseCurve . '",
				"' . $diameter . '",
				"' . $sphere . '",
				"' . $cylinder . '",
				"' . $axis . '",
				"' . $add . '",
				"' . $colors_enh . '",
				"' . $colors_opq .  
				'")';
			
				$resultarr[] = $mysqli->query($query);
		
			//	echo 'QUERY: ' . $query . '<br /><br /><br />';
//ChromePhp::log($query);
			}

		//update the power_lists table
		$powerListUpdateResult = updatePowerListsTable ($data['id']);
		if (in_array(false, $powerListUpdateResult)) return false;
		
		return (!in_array(false, $resultarr)) ? true : false;
		
	
	}

	

	
	function deleteLens($id) {
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'DELETE from pn_lenses where pn_tid = ' . $id;
			
		//echo $query;	
		
		//TMZ added: now we need to delete the rows in the pn_lenses_powers table assoc with this lens.
		$resultarr[] = $mysqli->query($query);
		$query = 'DELETE from pn_lenses_powers where lensID = ' . $id;
	
		//also delete the corresponding pn_lenses_power_lists table
		$query2 = 'DELETE from pn_lenses_power_lists where id = ' . $id;
		$resultarr[] = $mysqli->query($query2);
			
			
		//echo $query;	
			
		$resultarr[] = $mysqli->query($query);
		
		return (!in_array(false, $resultarr)) ? true : false;

	}
	
	
	
	

}

function updatePowerListsTable ($lensID) {

	//$mysqli = new mysqli('localhost', 'root', 'root', 'eyedock_data');
    $mysqli = new mysqli('mysql.eyedock.com', 'eyedockdatauser', 'kvBS^VQR', 'eyedock_data');

	//get the rows from lenses_powers table for this lens (these values are listed in ranges)
	$powerArray = getPowerArrayForLens($lensID);
	if ($powerArray['head']['status'] != 1) return;
	
	
	//print_r ($powerArray['body']);
	
	foreach ($powerArray['body'] as $row) {
		
		//create the data to insert into the power_lists table
		$id = $row['id'];
		$lensID = $row['lensID'];
		$variation = $row['variation'];
		$baseCurve = implode (",", $row['baseCurve']);
		$diameter = implode (",", $row['diameter']);
		$sphere = implode (",", $row['sphere']);
		//not every lens is going to have the following rows
		if ($row['cylinder'] != "") $cylinder = implode (",", $row['cylinder']);
		if ($row['axis'] != "") $axis = implode (",", $row['axis']);
		if ($row['addPwr'] != "") $addPwr = implode (",", $row['addPwr']);
		if ($row['colors_enh'] != "") $colors_enh = implode (",", $row['colors_enh']);
		if ($row['colors_opq'] != "") $colors_opq = implode (",", $row['colors_opq']);
		
		



		//check to see if the rows already exist in the power_lists table
		$sql = "SELECT 1 FROM pn_lenses_power_lists WHERE id =" . $row['id'];
		$result = $mysqli->query($sql);
		$count = $result->num_rows;
		
		//echo "<p>sql: " . $sql . "</p>";
		//echo "<p>count: " . $count . "</p>";
		
		if ($count <1) //if the row didn't already exist we'll insert it
		{
				$insertSQL = "INSERT INTO pn_lenses_power_lists (id, lensID, variation, baseCurve, diameter, sphere,cylinder, axis, addPwr, colors_enh, colors_opq)
VALUES (
		
			" . $id . " , 
			" . $lensID . ",
			'" . variation ."', 
			'" . $baseCurve ."', 
			'" . $diameter ."', 
			'" . $sphere ."', 
			'" . $cylinder ."', 
			'" . $axis ."', 
			'" . $addPwr ."', 
			'" . $colors_enh ."', 
			'" . $colors_opq . "'
		)";
		} else { //if the row did exist we'll update it
		
			$insertSQL = "UPDATE pn_lenses_power_lists SET 
			id = " . $id . ", 
			lensID = " . $lensID . ", 
			variation = '" . $variation ."', 
			baseCurve = '" . $baseCurve ."', 
			diameter = '" . $diameter ."', 
			sphere = '" . $sphere ."',
			cylinder = '" . $cylinder ."', 
			axis = '" . $axis ."', 
			addPwr = '" . $addPwr ."', 
			colors_enh = '" . $colors_enh ."', 
			colors_opq = '" . $colors_opq . "' 
			 WHERE id = " . $id ."
			";
		
		}
		
		//echo ($insertSQL);
		
		$resultarr[] = $mysqli->query($insertSQL);

	} // go to the next row . . .
	
	return $resultarr;
	
	
}

	function trimAndDefault($val, $default){
		if (empty($val)) return $default;
		$val = trim($val);
		if ($val == "") return $default;
		return sanitize($val);
	}

	function sanitize ($val) {
		$val  = str_replace(array('®', '™','˚','≤','≥' ), array('&reg;', '&#8482;', '&deg;', '&lt;=', '&gt;='), $val);

		$val = str_replace( array("\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x93", "\xe2\x80\x94", "\xe2\x80\xa6"), array("'", "'", '"', '"', '-', '--', '...'), $val);
 
		// Next, replace their Windows-1252 equivalents.
		 $val = str_replace(  array(chr(145), chr(146), chr(147), chr(148), chr(150), chr(151), chr(133)), array("'", "'", '"', '"', '-', '--', '...'), $val);
 
		$val = preg_replace('/[^(\x20-\x7F)]*/', '', $val);
		$val = addslashes($val);
		return $val;
	}
