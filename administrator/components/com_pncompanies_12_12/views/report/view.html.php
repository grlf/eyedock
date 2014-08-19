<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );
include_once $_SERVER['DOCUMENT_ROOT']. "/eyedock.com/api_new/dataGetters.php";

class PncompaniesViewReport extends JView {


	function display($tpl = null) {

		global $mainframe;

		$id = JRequest::getVar( 'cid', array(0), '', 'array' );
		$boxchecked = JRequest::getVar( 'boxchecked' );
		//echo $boxchecked;
		$uid = (int) @$id[0];
		//$edit = JRequest::getVar( 'edit', true );
		//echo "uid: " . $uid;
		$data = getCompanyReport($uid);

		
		//had to reset the database, otherwise joomla was looking for the component in the 'eyedock_data' database and causing an error (I assume the function above was causing this database switch)
		$connect =mysql_connect('mysql.eyedock.com', 'eyedockdatauser', 'kvBS^VQR');
		$database = mysql_select_db("eyedockjoomla", $connect);
		
		$this->assignRef('companyInfo', $data);
		
		parent::display($tpl);
		
	}

}