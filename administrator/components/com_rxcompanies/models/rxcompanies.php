<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );

class RxcompaniesModelRxcompanies extends JModel {
	
	const GPHOST = 'mysql.eyedock.com';
	const GPUSER = 'eyedockdatauser';
	const GPPASS = 'kvBS^VQR';
	const GPDB = 'eyedock_data';
	
	const LENSIMGURL = '';
	const LENSPDFURL = '';

	function addNewCompany($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['phone']))
			$data['phone'] = '';
		if(empty($data['street']))
			$data['street'] = '';
		if(empty($data['city']))
			$data['city'] = '';
		if(empty($data['state']))
			$data['state'] = '';
		if(empty($data['zip']))
			$data['zip'] = '';
		if(empty($data['email']))
			$data['email'] = '';
		if(empty($data['url']))
			$data['url'] = '';
		if(empty($data['comments']))
			$data['comments'] = '';
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'INSERT INTO pn_rx_company (pn_name, pn_phone, pn_street, pn_city, pn_state, pn_zip, pn_email, pn_url, pn_comments) VALUES (
			"' . addslashes($data['name']) . '", 
			"' . addslashes($data['phone']) . '", 
			"' . addslashes($data['street']) . '", 
			"' . addslashes($data['city']) . '", 
			"' . addslashes($data['state']) . '", 
			"' . addslashes($data['zip']) . '", 
			"' . addslashes($data['email']) . '",
			"' . addslashes($data['url']) . '",
			"' . addslashes($data['comments']) . '"
			)';

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
		if(empty($data['phone']))
			$data['phone'] = '';
		if(empty($data['street']))
			$data['street'] = '';
		if(empty($data['city']))
			$data['city'] = '';
		if(empty($data['state']))
			$data['state'] = '';
		if(empty($data['zip']))
			$data['zip'] = '';
		if(empty($data['email']))
			$data['email'] = '';
		if(empty($data['url']))
			$data['url'] = '';
		if(empty($data['comments']))
			$data['comments'] = '';

		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'UPDATE pn_rx_company SET pn_name = "' . addslashes($data['name']) . '", pn_phone = "' . addslashes($data['phone']) . '", pn_street = "' . addslashes($data['street']) . '", pn_city = "' . addslashes($data['city']) . '", pn_state = "' . addslashes($data['state']) . '", pn_zip = "' . addslashes($data['zip']) . '", pn_email = "' . addslashes($data['email']) . '", pn_url = "' . addslashes($data['url']) . '", pn_comments = "' . addslashes($data['comments']) . '" where pn_comp_id = ' . $data['id'];
	
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}
	
	}
	
	function deleteCompany($id) {
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'DELETE from pn_rx_company where pn_comp_id = ' . $id;
			
		echo $query;	
			
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}		
	
	}

}