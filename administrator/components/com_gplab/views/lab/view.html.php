<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );

class GplabViewLab extends JView {

	const GPHOST = 'mysql.eyedock.com';
	const GPUSER = 'eyedockdatauser';
	const GPPASS = 'kvBS^VQR';
	const GPDB = 'eyedock_data';
	
	const LENSIMGURL = 'http://www.eyedock.com/test/administrator/components/com_gplab/models/images/';

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
			$query = 'SELECT * from rgpLab where tid = ' . $uid;
			$result = $mysqli->query($query);
			$lab = $result->fetch_object();
		}
		
		$filepaths->img = self::LENSIMGURL;
		
		$this->assignRef('lab', $lab);
		$this->assignRef('filepaths', $filepaths);
		
		parent::display($tpl);
		
	}

}