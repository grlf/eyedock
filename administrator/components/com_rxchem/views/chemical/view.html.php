<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );

class RxchemViewChemical extends JView {

	const GPHOST = 'mysql.eyedock.com';
	const GPUSER = 'eyedockdatauser';
	const GPPASS = 'kvBS^VQR';
	const GPDB = 'eyedock_data';
	
	const LENSIMGURL = '';
	const LENSPDFURL = '';

	function display($tpl = null) {

		global $mainframe;

		$id = JRequest::getVar( 'cid', array(0), '', 'array' );
		$uid = (int) @$id[0];
		$edit = JRequest::getVar( 'edit', true );
		
		if($edit) {
			if($boxchecked != 0){
				$uid = $boxchecked;
			}
			$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
			$query = 'SELECT * from pn_rx_chem where pn_chem_id = ' . $uid;
			$result = $mysqli->query($query);
			$chemical = $result->fetch_object();
		}
		
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'SELECT * from pn_rx_moa';
		$result = $mysqli->query($query);
		while($obj = $result->fetch_object()) {
			$moas[] = $obj;
		}
		
		
		$this->assignRef('chemical', $chemical);
		$this->assignRef('moas', $moas);
		
		parent::display($tpl);
		
	}

}