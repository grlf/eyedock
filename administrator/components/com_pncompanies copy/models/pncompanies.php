<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );

class PncompaniesModelPncompanies extends JModel {
	
	const GPHOST = 'mysql.eyedock.com';
	const GPUSER = 'eyedockdatauser';
	const GPPASS = 'kvBS^VQR';
	const GPDB = 'eyedock_data';
	
	const LENSIMGURL = '';
	const LENSPDFURL = '';

	function addNewCompany($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['logo']))
			$data['logo'] = '';
		if(empty($data['phone']))
			$data['phone'] = '';
		if(empty($data['address']))
			$data['address'] = '';
		if(empty($data['city']))
			$data['city'] = '';
		if(empty($data['state']))
			$data['state'] = '';
		if(empty($data['zip']))
			$data['zip'] = '';
		if(empty($data['website']))
			$data['website'] = '';
		if(empty($data['email']))
			$data['email'] = '';
		if(empty($data['description']))
			$data['description'] = '';
		if(empty($data['hide']))
			$data['hide'] = '';
				
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'INSERT INTO pn_lenses_companies (pn_comp_name, pn_logo, pn_phone, pn_address, pn_city, pn_state, pn_zip, pn_url, pn_email, pn_comp_desc, pn_hide) VALUES (
			"' . addslashes($data['name']) . '", 
			"' . addslashes($data['logo']) . '", 
			"' . addslashes($data['phone']) . '", 
			"' . addslashes($data['address']) . '", 
			"' . addslashes($data['city']) . '", 
			"' . addslashes($data['state']) . '", 
			"' . addslashes($data['zip']) . '", 
			"' . addslashes($data['website']) . '",
			"' . addslashes($data['email']) . '",
			"' . addslashes($data['description']) . '"
			"' . addslashes($data['hide']) . '"
			)';
			
	//	echo $query;

	
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}
	
	}
	
	function updateCompany($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['logo']))
			$data['logo'] = '';
		if(empty($data['phone']))
			$data['phone'] = '';
		if(empty($data['address']))
			$data['address'] = '';
		if(empty($data['city']))
			$data['city'] = '';
		if(empty($data['state']))
			$data['state'] = '';
		if(empty($data['zip']))
			$data['zip'] = '';
		if(empty($data['website']))
			$data['website'] = '';
		if(empty($data['email']))
			$data['email'] = '';
		if(empty($data['description']))
			$data['description'] = '';
		if(empty($data['hide']))
			$data['hide'] = '';

	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'UPDATE pn_lenses_companies SET pn_comp_name = "' . addslashes($data['name']) . '", pn_logo = "' . addslashes($data['logo']) . '", pn_phone = "' . addslashes($data['phone']) . '", pn_address = "' . addslashes($data['address']) . '", pn_city = "' . addslashes($data['city']) . '", pn_state = "' . addslashes($data['state']) . '", pn_zip = "' . addslashes($data['zip']) . '", pn_url = "' . addslashes($data['website']) . '", pn_email = "' . addslashes($data['email']) . '", pn_comp_desc = "' . addslashes($data['description']). '", pn_hide = "' . addslashes($data['hide']) . '" where pn_comp_tid = ' . $data['id'];
			
	//	echo $query;

	
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}
	
	}
	
	function deleteCompany($id) {
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'DELETE from pn_lenses_companies where pn_comp_tid = ' . $id;
			
		echo $query;	
			
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}		
	
	}

}