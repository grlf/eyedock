<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );

class PncompaniesModelPncompanies extends JModel {
	
//      const GPHOST = 'localhost';
//      const GPUSER = 'root';
//      const GPPASS = 'root';
//      const GPDB = 'eyedock_data';
     
     const GPHOST = 'mysql.eyedock.com';
     const GPUSER = 'eyedockdatauser';
     const GPPASS = 'kvBS^VQR';
     const GPDB = 'eyedock_data';


	
	const LENSIMGURL = '';
	const LENSPDFURL = '';

	function addNewCompany($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['name_short']))
			$data['name_short'] = '';
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
		if(empty($data['contactNameF']))
			$data['contactNameF'] = '';		
		if(empty($data['contactNameL']))
			$data['contactNameL'] = '';		
		if(empty($data['contactEmail']))
			$data['contactEmail'] = '';
		if(empty($data['lastEmail']))
			$data['lastEmail'] = date("Y/m/d");
		if(empty($data['emailInterval']))
			$data['emailInterval'] = '90';
		if(empty($data['hide']))
			$data['hide'] = '0';
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'INSERT INTO pn_lenses_companies (pn_comp_name, pn_comp_name_short, pn_logo, pn_phone, pn_address, pn_city, pn_state, pn_zip, pn_url, pn_email, pn_comp_desc, pn_contact_nameF, pn_contact_nameL, pn_contact_email, pn_hide, pn_last_email, pn_email_interval) VALUES (
			"' . addslashes($data['name']) . '", 
			"' . addslashes($data['name_short']) . '", 
			"' . addslashes($data['logo']) . '", 
			"' . addslashes($data['phone']) . '", 
			"' . addslashes($data['address']) . '", 
			"' . addslashes($data['city']) . '", 
			"' . addslashes($data['state']) . '", 
			"' . addslashes($data['zip']) . '", 
			"' . addslashes($data['website']) . '",
			"' . addslashes($data['email']) . '",
			"' . addslashes($data['description']) . '",
			"' . addslashes($data['contactNameF']) . '",
			"' . addslashes($data['contactNameL']) . '",
			"' . addslashes($data['contactEmail']) . '",
			"' . addslashes($data['hide']) . '",
			"' . addslashes($data['lastEmail']) . '",
			"' . addslashes($data['emailInterval']) . '"
			)';
			
		//echo $query;

	
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
		if(empty($data['name_short']))
			$data['name_short'] = '';
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
		if(empty($data['contactNameF']))
			$data['contactNameF'] = '';		
		if(empty($data['contactNameL']))
			$data['contactNameL'] = '';		
		if(empty($data['contactEmail']))
			$data['contactEmail'] = '';
		if(empty($data['lastEmail']))
			$data['lastEmail'] = date("Y/m/d");
		if(empty($data['emailInterval']))
			$data['emailInterval'] = '90';
		if(empty($data['hide']))
			$data['hide'] = '0';
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'UPDATE pn_lenses_companies SET 
		pn_comp_name = "' . addslashes($data['name']) . '", 
		pn_comp_name_short = "' . addslashes($data['name_short']) . '", 
		pn_logo = "' . addslashes($data['logo']) . '", 
		pn_phone = "' . addslashes($data['phone']) . '", 
		pn_address = "' . addslashes($data['address']) . '", 
		pn_city = "' . addslashes($data['city']) . '", 
		pn_state = "' . addslashes($data['state']) . '", 
		pn_zip = "' . addslashes($data['zip']) . '", 
		pn_url = "' . addslashes($data['website']) . '", 
		pn_email = "' . addslashes($data['email']) . '", 
		pn_comp_desc = "' . addslashes($data['description']). '",		
		pn_contact_nameF = "' . addslashes($data['contactNameF']). '",
		pn_contact_nameL = "' . addslashes($data['contactNameL']). '",
		pn_contact_email = "' . addslashes($data['contactEmail']). '", 
		pn_hide = "' . addslashes($data['hide']) . '",
		pn_last_email = "' . addslashes($data['lastEmail']). '",
		pn_email_interval = "' . addslashes($data['emailInterval']). '"'. 
		'where pn_comp_tid = ' . $data['id'];
			
	//echo $query;

	
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
			
		//echo $query;	
			
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}		
	
	}

}