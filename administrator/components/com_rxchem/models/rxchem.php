<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );

class RxchemModelRxchem extends JModel {
	
	const GPHOST = 'mysql.eyedock.com';
	const GPUSER = 'eyedockdatauser';
	const GPPASS = 'kvBS^VQR';
	const GPDB = 'eyedock_data';
	
	const LENSIMGURL = '';
	const LENSPDFURL = '';

	function addNewChemical($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['moa']))
			$data['moa'] = 'NULL';
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'INSERT INTO pn_rx_chem (pn_name, pn_moa_id) VALUES (
			"' . addslashes($data['name']) . '", 
			' . $data['moa'] . '
			)';

	
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}
	
	}
	
	function updateChemical($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['moa']))
			$data['moa'] = '';
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'UPDATE pn_rx_chem SET pn_name = "' . addslashes($data['name']) . '", pn_moa_id = ' . $data['moa'] . ' where pn_chem_id = ' . $data['id'];
	
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}
	
	}
	
	function deleteChemical($id) {
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'DELETE from pn_rx_chem where pn_chem_id = ' . $id;
			
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}		
	
	}

}