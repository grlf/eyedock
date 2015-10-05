<?php

defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );
require_once( JPATH_ROOT.DS.'utilities/mysqliSingleton.php' );

class PnpolymersViewPolymer extends JViewLegacy {

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

			//$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
			$mysqli = DBAccess::getConnection();
			$query = 'SELECT * from pn_lenses_polymers where pn_poly_tid = ' . $uid;
			$result = $mysqli->selectQuery($query);
			$polymer = $result->fetch_object();
		}

		$this->assignRef('polymer', $polymer);

		parent::display($tpl);

	}

}