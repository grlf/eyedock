<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );

class RxmedsViewMeds extends JView {

	const GPHOST = 'mysql.eyedock.com';
	const GPUSER = 'eyedockdatauser';
	const GPPASS = 'kvBS^VQR';
	const GPDB = 'eyedock_data';
	
	// NEED TO ADD THESE URL'S
	const LENSIMGURL = '#';
	const LENSPDFURL = '#';


	function display($tpl = null) {

		global $mainframe, $option;
		
		$db	=& JFactory::getDBO();
		$search	= $mainframe->getUserStateFromRequest( "$option.search", 'search', '', 'string' );
		if (strpos($search, '"') !== false) {
			$search = str_replace(array('=', '<'), '', $search);
		}
		$search = JString::strtolower($search);
		
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );	
		
		$where = array();
	
		if ($search) {
			$where[] = 'LOWER(m.pn_trade) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}

		$where = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		
	//	if($limiter || $limit) {
			$limiter = ' LIMIT ' . $limitstart . ', ' . $limit;
	//	}
			
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'SELECT pn_med_id from pn_rx_meds';
		$result = $mysqli->query($query);
		$total = $result->num_rows;		
		
		jimport('joomla.html.pagination');
		$pagination = new JPagination( $total, $limitstart, $limit );		
		
		$query = 'SELECT m.*, comp.pn_name as companyname from pn_rx_meds m left join pn_rx_company comp on (m.pn_comp_id = comp.pn_comp_id)' . $where . ' ORDER BY m.pn_trade' . $limiter;
		$result = $mysqli->query($query);
		
		while($obj = $result->fetch_object()) {
			$meds[] = $obj;
		}
		
		$this->assignRef('meds', $meds);
		$this->assignRef('pagination', $pagination);
		
		parent::display($tpl);
	
	}	

}