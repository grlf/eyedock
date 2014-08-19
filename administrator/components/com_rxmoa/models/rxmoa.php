<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );

class RxmoaModelRxmoa extends JModel {
	
	const GPHOST = 'mysql.eyedock.com';
	const GPUSER = 'eyedockdatauser';
	const GPPASS = 'kvBS^VQR';
	const GPDB = 'eyedock_data';
	
	const LENSIMGURL = '';
	const LENSPDFURL = '';

	function addNewMoa($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['comments']))
			$data['comments'] = '';
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'INSERT INTO pn_rx_moa (pn_name, pn_comments) VALUES (
			"' . addslashes($data['name']) . '", 
			"' . addslashes($data['comments']) . '"
			)';

	
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}
	
	}
	
	function updateMoa($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['comments']))
			$data['comments'] = '';
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'UPDATE pn_rx_moa SET pn_name = "' . addslashes($data['name']) . '", pn_comments = "' . addslashes($data['comments']) . '" where pn_moa_id = ' . $data['id'];
	
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}
	
	}
	
	function deleteMoa($id) {
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'DELETE from pn_rx_moa where pn_moa_id = ' . $id;
			
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}		
	
	}

}