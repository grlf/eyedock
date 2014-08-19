<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );

class GpmaterialViewMaterial extends JView {

	const GPHOST = 'mysql.eyedock.com';
	const GPUSER = 'eyedockdatauser';
	const GPPASS = 'kvBS^VQR';
	const GPDB = 'eyedock_data';
	
	const LENSIMGURL = 'http://www.eyedock.com/modules/Lenses/pnimages/lens_images/';
	const LENSPDFURL = 'http://www.eyedock.com/modules/Lenses/pnpdf/';

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
			$query = 'SELECT * from rgpMaterials where tid = ' . $uid;
			$result = $mysqli->query($query);
			$material = $result->fetch_object();	
		}
		
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'SELECT tid, name from rgpMaterialCompany';
		$result = $mysqli->query($query);
		while($obj = $result->fetch_object()) {
			$materialcompanies[] = $obj;
		}
		
		$query = 'SELECT tid, name from rgpMaterialType';
		$result = $mysqli->query($query);
		while($obj = $result->fetch_object()) {
			$materialtypes[] = $obj;
		}

		$this->assignRef('materialtypes', $materialtypes);				
		$this->assignRef('materialcompanies', $materialcompanies);		
		$this->assignRef('material', $material);
		
		parent::display($tpl);
		
	}

}