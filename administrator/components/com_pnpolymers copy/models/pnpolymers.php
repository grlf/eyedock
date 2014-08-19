<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );

class PnpolymersModelPnpolymers extends JModel {
	
	const GPHOST = 'mysql.eyedock.com';
	const GPUSER = 'eyedockdatauser';
	const GPPASS = 'kvBS^VQR';
	const GPDB = 'eyedock_data';
	
	const LENSIMGURL = '';
	const LENSPDFURL = '';

	function addNewPolymer($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['h2o']))
			$data['h2o'] = '';
		if(empty($data['fda']))
			$data['fda'] = '';
		if(empty($data['dk']))
			$data['dk'] = '';
		if(empty($data['modulus']))
			$data['modulus'] = '';
		if(empty($data['description']))
			$data['description'] = '';
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'INSERT INTO pn_lenses_polymers (pn_poly_name, pn_h2o, pn_fda_grp, pn_poly_dk, pn_poly_modulus, pn_poly_desc) VALUES (
			"' . addslashes($data['name']) . '", 
			"' . addslashes($data['h2o']) . '", 
			"' . addslashes($data['fda']) . '", 
			"' . addslashes($data['dk']) . '",
			"' . addslashes($data['modulus']) . '", 
			"' . addslashes($data['description']) . '"
			)';
			
	//	echo $query;

	
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}
	
	}
	
	function updatePolymer($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['h2o']))
			$data['h2o'] = '';
		if(empty($data['fda']))
			$data['fda'] = '';
		if(empty($data['dk']))
			$data['dk'] = '';
		if(empty($data['modulus']))
			$data['modulus'] = '';
		if(empty($data['description']))
			$data['description'] = '';
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'UPDATE pn_lenses_polymers SET pn_poly_name = "' . addslashes($data['name']) . '", pn_h2o = "' . addslashes($data['h2o']) . '", pn_fda_grp = "' . addslashes($data['fda']) .  '", pn_poly_dk = "' . addslashes($data['dk']) . '", pn_poly_modulus = "' . addslashes($data['modulus']) .'", pn_poly_desc = "' . addslashes($data['description']) . '" where pn_poly_tid = ' . $data['id'];
			
	//	echo $query;

	
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}
	
	}
	
	function deletePolymer($id) {
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'DELETE from pn_lenses_polymers where pn_poly_tid = ' . $id;
			
		echo $query;
			
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}		
	
	}

}