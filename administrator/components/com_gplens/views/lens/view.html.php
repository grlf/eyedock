<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );

class GplensViewLens extends JView {

	const GPHOST = 'mysql.eyedock.com';
	const GPUSER = 'eyedockdatauser';
	const GPPASS = 'kvBS^VQR';
	const GPDB = 'eyedock_data';
	
	const LENSIMGURL = 'http://www.eyedock.com/test/administrator/components/com_gplens/models/images/';
	const LENSPDFURL = 'http://www.eyedock.com/test/administrator/components/com_gplens/models/pdfs/';

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
			$query = 'SELECT * from rgpLenses where tid = ' . $uid;
			$result = $mysqli->query($query);
			$lens = $result->fetch_object();
			
			$query = 'SELECT * from rgpLens_materials where lensID = ' . $uid;
			$result = $mysqli->query($query);	
			while($obj = $result->fetch_object()) {
				$lensmaterials[] = $obj;
			}
			
		}
		
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'SELECT tid, name from rgpLab ORDER BY name ASC';
		$result = $mysqli->query($query);
		while($obj = $result->fetch_object()) {
			$companies[] = $obj;
		}
		
		$query = 'SELECT tid, name from rgpDesignCategory';
		$result = $mysqli->query($query);
		while($obj = $result->fetch_object()) {
			$designcategories[] = $obj;
		}
		
		$query = 'SELECT * from rgpSubcategory';
		$result = $mysqli->query($query);
		while($obj = $result->fetch_object()) {
			$allsubcategories[] = $obj;
		}
		
		if($edit) {
			$query = 'SELECT * from rgpSubcategory where designCategoryID = ' . $lens->designCategoryID;
			$result = $mysqli->query($query);
			while($obj = $result->fetch_object()) {
				$startingsubcategories[] = $obj;
			}
		}
		
		if($edit) {
			$query = 'SELECT * from rgpMaterials';
			$result = $mysqli->query($query);
			while($obj = $result->fetch_object()) {
				$materials[] = $obj;
			}
		}
		
		if($edit) {
			$query = 'SELECT * from rgpMaterialCompany';
			$result = $mysqli->query($query);
			while($obj = $result->fetch_object()) {
				$materialcompany[] = $obj;
			}
		}
		
		$filepaths->img = self::LENSIMGURL;
		$filepaths->pdf = self::LENSPDFURL;
		
		$this->assignRef('companies', $companies);
		$this->assignRef('materials', $materials);
		$this->assignRef('materialcompany', $materialcompany);
		$this->assignRef('lensmaterials', $lensmaterials);	
		$this->assignRef('allsubcategories', $allsubcategories);	
		$this->assignRef('startingsubcategories', $startingsubcategories);				
		$this->assignRef('designcategories', $designcategories);		
		$this->assignRef('lens', $lens);
		$this->assignRef('filepaths', $filepaths);
		
		parent::display($tpl);
		
	}

}