<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );

class GpmaterialcompanyModelGpmaterialcompany extends JModel {
	
	const GPHOST = 'mysql.eyedock.com';
	const GPUSER = 'eyedockdatauser';
	const GPPASS = 'kvBS^VQR';
	const GPDB = 'eyedock_data';
	
	const LENSIMGURL = 'http://www.eyedock.com/modules/Lenses/pnimages/lens_images/';
	const LENSPDFURL = 'http://www.eyedock.com/modules/Lenses/pnpdf/';

	function addNewMaterialcompany($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['phone']))
			$data['phone'] = '';
		if(empty($data['url']))
			$data['url'] = '';
		if(empty($data['otherinfo']))
			$data['otherinfo'] = '';
		
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'INSERT INTO rgpMaterialCompany (name, phone, url, otherInfo) VALUES (
			"' . addslashes($data['name']) . '", 
			"' . addslashes($data['phone']) . '", 
			"' . addslashes($data['url']) . '", 
			"' . addslashes($data['otherinfo']) . '"
			)';
			
	//	echo $query;

	
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}
	
	}
	
	function updateMaterialcompany($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['phone']))
			$data['phone'] = '';
		if(empty($data['url']))
			$data['url'] = '';
		if(empty($data['otherinfo']))
			$data['otherinfo'] = '';
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'UPDATE rgpMaterialCompany SET name = "' . addslashes($data['name']) . '", phone = "' . addslashes($data['phone']) . '", url = "' . addslashes($data['url']) . '", otherInfo = "' . addslashes($data['otherinfo']) . '" where tid = ' . $data['id'];
			
	//	echo $query;


		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}

	}
	
	function deleteMaterialcompany($id) {
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'DELETE from rgpMaterialCompany where tid = ' . $id;
			
		echo $query;
			
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}		
	
	}

}