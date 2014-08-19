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
			/*if($boxchecked != 0){
				$uid = $boxchecked;
			}*/
			$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
			$query = 'SELECT * from pn_lenses where pn_tid = ' . $uid;
			$result = $mysqli->query($query);
			$lens = $result->fetch_object();	
			
			$query = 'SELECT * from pn_lenses_powers where lensID = ' . $uid;
			$result = $mysqli->query($query);	
			while($obj = $result->fetch_object()) {
				$lenspowers[] = $obj;
			}
		}
		
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'SELECT pn_comp_tid, pn_comp_name from pn_lenses_companies ORDER BY  pn_comp_name ASC ';
		$result = $mysqli->query($query);
		while($obj = $result->fetch_object()) {
			$companies[] = $obj;
		}
		
		$query = 'SELECT pn_poly_tid, pn_poly_name from pn_lenses_polymers ORDER BY pn_poly_name ASC';
		$result = $mysqli->query($query);
		while($obj = $result->fetch_object()) {
			$polymers[] = $obj;
		}
		
		if ($edit) {
			$bcsimples = explode(',', $lens->pn_bc_simple);
			$bcsimples = array_filter(array_map('trim', $bcsimples)); //remove whitespace
			$enhsimples = explode(',', $lens->pn_enh_names_simple);
			$enhsimples = array_filter(array_map('trim', $enhsimples)); //remove whitespace
			$opqsimples = explode(',', $lens->pn_opaque_names_simple);
			$opqsimples = array_filter(array_map('trim', $opqsimples));  //remove whitespace
		} else {
			$bcsimples = array();
			$enhsimples = array();
			$opqsimples = array();
		}
		
		$this->assignRef('polymers', $polymers);				
		$this->assignRef('companies', $companies);		
		$this->assignRef('lens', $lens);	
		$this->assignRef('bcsimples', $bcsimples);
		$this->assignRef('enhsimples', $enhsimples);
		$this->assignRef('opaquesimples', $opqsimples);
		if ($edit) $this->assignRef('lenspowers', $lenspowers);
		
		parent::display($tpl);
		
	}

}