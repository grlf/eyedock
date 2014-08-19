<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );

class PnlensesViewLens extends JView {

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
			$query = 'SELECT * from pn_lenses where pn_tid = ' . $uid;
			$result = $mysqli->query($query);
			$lens = $result->fetch_object();	
		}
		
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'SELECT pn_comp_tid, pn_comp_name from pn_lenses_companies';
		$result = $mysqli->query($query);
		while($obj = $result->fetch_object()) {
			$companies[] = $obj;
		}
		
		$query = 'SELECT pn_poly_tid, pn_poly_name from pn_lenses_polymers';
		$result = $mysqli->query($query);
		while($obj = $result->fetch_object()) {
			$polymers[] = $obj;
		}
		
		$bcsimples = explode(',', $lens->pn_bc_simple);
		//tmz added 05/09/2012
		$bcsimples = array_filter(array_map('trim', $bcsimples)); //remove whitespace


		$this->assignRef('polymers', $polymers);				
		$this->assignRef('companies', $companies);		
		$this->assignRef('lens', $lens);	
		$this->assignRef('bcsimples', $bcsimples);
		
		parent::display($tpl);
		
	}

}
