<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );

class GpmaterialModelGpmaterial extends JModel {
	
	const GPHOST = 'mysql.eyedock.com';
	const GPUSER = 'eyedockdatauser';
	const GPPASS = 'kvBS^VQR';
	const GPDB = 'eyedock_data';
	
	const LENSIMGURL = 'http://www.eyedock.com/modules/Lenses/pnimages/lens_images/';
	const LENSPDFURL = 'http://www.eyedock.com/modules/Lenses/pnpdf/';

	function addNewMaterial($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['materialtype']))
			$data['materialtype'] = 'NULL';
		if(empty($data['materialcompany']))
			$data['materialcompany'] = 'NULL';
		if(empty($data['dk']))
			$data['dk'] = 'NULL';
		if(empty($data['wettingangle']))
			$data['wettingangle'] = 'NULL';
		if(empty($data['refractiveindex']))
			$data['refractiveindex'] = 'NULL';
		if(empty($data['specificgravity']))
			$data['specificgravity'] = 'NULL';
		if(empty($data['colors']))
			$data['colors'] = '';
		if(empty($data['uvcolors']))
			$data['uvcolors'] = '';
		if(empty($data['url']))
			$data['url'] = '';
		if(empty($data['otherinfo']))
			$data['otherinfo'] = '';
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'INSERT INTO rgpMaterials (name, materialTypeID, rgpMaterialCompanyID, dk, wetAngle, refractiveIndex, specificGravity, colors, colorsUV, url, otherInfo) VALUES (
			"' . addslashes($data['name']) . '", 
			' . $data['materialtype'] . ', 
			' . $data['materialcompany'] . ', 
			' . $data['dk'] . ', 
			' . $data['wettingangle'] . ', 
			' . $data['refractiveindex'] . ', 
			' . $data['specificgravity'] . ', 
			"' . addslashes($data['colors']) . '", 
			"' . addslashes($data['uvcolors']) . '", 
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
	
	function updateMaterial($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['materialtype']))
			$data['materialtype'] = 'NULL';
		if(empty($data['materialcompany']))
			$data['materialcompany'] = 'NULL';
		if(empty($data['dk']))
			$data['dk'] = 'NULL';
		if(empty($data['wettingangle']))
			$data['wettingangle'] = 'NULL';
		if(empty($data['refractiveindex']))
			$data['refractiveindex'] = 'NULL';
		if(empty($data['specificgravity']))
			$data['specificgravity'] = 'NULL';
		if(empty($data['colors']))
			$data['colors'] = '';
		if(empty($data['uvcolors']))
			$data['uvcolors'] = '';
		if(empty($data['url']))
			$data['url'] = '';
		if(empty($data['otherinfo']))
			$data['otherinfo'] = '';
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'UPDATE rgpMaterials SET name = "' . addslashes($data['name']) . '", materialTypeID = ' . $data['materialtype'] . ', rgpMaterialCompanyID = ' . $data['materialcompany'] . ', dk = ' . $data['dk'] . ', wetAngle = ' . $data['wettingangle'] . ', refractiveIndex = ' . $data['refractiveindex'] . ', specificGravity = ' . $data['specificgravity'] . ', colors = "' . addslashes($data['colors']) . '", colorsuv = "' . addslashes($data['uvcolors']) . '", url = "' . addslashes($data['url']) . '", otherInfo = "' . addslashes($data['otherinfo']) . '" where tid = ' . $data['id'];
			
	//	echo $query;

	
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}
	
	}
	
	function deleteMaterial($id) {
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'DELETE from rgpMaterials where tid = ' . $id;
			
		echo $query;	
			
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}		
	
	}

}