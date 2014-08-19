<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );
require_once( JPATH_ROOT.DS.'utilities/sqli.php' );

class PncompaniesViewCompany extends JView {

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
			$mysqli = getSQLI();
			$query = 'SELECT * from pn_lenses_companies where pn_comp_tid = ' . $uid;
			$result = $mysqli->query($query);
			$company = $result->fetch_object();
		}
		
		$this->assignRef('company', $company);
		
		parent::display($tpl);
		
	}

}