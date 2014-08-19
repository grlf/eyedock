<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );
require_once( JPATH_COMPONENT.DS.'utilities/companyFunctions.php' );
require_once( JPATH_ROOT.DS.'utilities/connect.php' );
require_once( JPATH_ROOT.DS.'utilities/mysqliSingleton.php' );

class PncompaniesViewReport extends JView {


	function display($tpl = null) {

		global $mainframe;

		$id = JRequest::getVar( 'cid', array(0), '', 'array' );
		$boxchecked = JRequest::getVar( 'boxchecked' );
		//echo $boxchecked;
		$uid = (int) @$id[0];

		$data['company'] =  nl2br(getCompanyReport($uid) );
		//$date['company'] = str_replace("\n", "<br/>");
		$data['lenses'] =   nl2br(getCompanyLensesReport ($uid) );
		
		$mysqli = DBAccess::getConnection();
		$sql = "SELECT * from pn_lenses_companies WHERE pn_comp_tid = $uid";
		$result = $mysqli->selectQuery($sql);
		$row = $result->fetch_assoc();

	
		//massage the data for the email
		if ($row['pn_contact_email'] == "" ) $row['pn_contact_email'] = $row['pn_email'];
		if ($row['pn_comp_name_short'] == "" ) $row['pn_comp_name_short'] = $row['pn_comp_name'];
		
		//get the email intro content
		$row['content'] = makeEmailIntro ($row);
		//pass along the contact person
		$row['contact'] = $row['pn_contact_nameF'] . " " . $row['pn_contact_nameL'];
		if ($row['pn_contact_nameF'] == "") $row['contact']  = $row['pn_comp_name_short'];
		//echo $data['lenses'];
		
		//had to reset the database, otherwise joomla was looking for the component in the 'eyedock_data' database and causing an error (I assume the function above was causing this database switch)
		//$connect =mysql_connect('mysql.eyedock.com', 'eyedockdatauser', 'kvBS^VQR');
		//mysql_connect('localhost', 'root', 'root');
		$connect = getCONNECT(); 
		$database = mysql_select_db("eyedockjoomla", $connect);
		
		$this->assignRef('data', $data);
		$this->assignRef('companyData', $row);
		
		parent::display($tpl);
		
	}

}