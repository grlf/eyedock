<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );

class RxpreserveModelRxpreserve extends JModel {
	
	const GPHOST = 'mysql.eyedock.com';
	const GPUSER = 'eyedockdatauser';
	const GPPASS = 'kvBS^VQR';
	const GPDB = 'eyedock_data';
	
	const LENSIMGURL = '';
	const LENSPDFURL = '';

	function addNewPreservative($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['comments']))
			$data['comments'] = '';
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'INSERT INTO pn_rx_preserve (pn_name, pn_comments) VALUES (
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
	
	function updatePreservative($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['comments']))
			$data['comments'] = '';
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'UPDATE pn_rx_preserve SET pn_name = "' . addslashes($data['name']) . '", pn_comments = "' . addslashes($data['comments']) . '" where pn_pres_id = ' . $data['id'];
	
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}
	
	}
	
	function deletePreservative($id) {
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'DELETE from pn_rx_preserve where pn_pres_id = ' . $id;
			
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}		
	
	}

}