<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );

class RxmedsViewMed extends JView {

	const GPHOST = 'mysql.eyedock.com';
	const GPUSER = 'eyedockdatauser';
	const GPPASS = 'kvBS^VQR';
	const GPDB = 'eyedock_data';
	
	// NEED TO ADD THESE URL'S
	const LENSIMGURL = '';
	const LENSPDFURL = '';

	function display($tpl = null) {

		global $mainframe;

		$id = JRequest::getVar( 'cid', array(0), '', 'array' );
	//	$boxchecked = JRequest::getVar( 'boxchecked' );
	//	echo $boxchecked;
		$uid = (int) @$id[0];
		$edit = JRequest::getVar( 'edit', true );
		
		if($edit) {
			if($boxchecked != 0){
				$uid = $boxchecked;
			}
			$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
			$query = 'SELECT * from pn_rx_meds where pn_med_id = ' . $uid;
			$result = $mysqli->query($query);
			$med = $result->fetch_object();	
		}
		
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'SELECT pn_comp_id, pn_name from pn_rx_company';
		$result = $mysqli->query($query);
		while($obj = $result->fetch_object()) {
			$companies[] = $obj;
		}
		
		$query = 'SELECT pn_pres_id, pn_name from pn_rx_preserve';
		$result = $mysqli->query($query);
		while($obj = $result->fetch_object()) {
			$preserves[] = $obj;
		}
		
		$query = 'SELECT pn_chem_id, pn_name from pn_rx_chem';
		$result = $mysqli->query($query);
		while($obj = $result->fetch_object()) {
			$chemicals[] = $obj;
		}
		
		$query = 'SELECT pn_moa_id, pn_name from pn_rx_moa';
		$result = $mysqli->query($query);
		while($obj = $result->fetch_object()) {
			$moas[] = $obj;
		}
	
		$this->assignRef('med', $med);	
		$this->assignRef('companies', $companies);
		$this->assignRef('preserves', $preserves);
		$this->assignRef('chemicals', $chemicals);
		$this->assignRef('moas', $moas);
		
		parent::display($tpl);
		
	}

}