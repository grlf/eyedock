<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );

class RxcompaniesViewCompany extends JView {

	const GPHOST = 'mysql.eyedock.com';
	const GPUSER = 'eyedockdatauser';
	const GPPASS = 'kvBS^VQR';
	const GPDB = 'eyedock_data';
	
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
			$query = 'SELECT * from pn_rx_company where pn_comp_id = ' . $uid;
			$result = $mysqli->query($query);
			$company = $result->fetch_object();
		}
		
		$this->assignRef('company', $company);
		
		parent::display($tpl);
		
	}

}