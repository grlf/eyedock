<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );

class RxchemViewChemicals extends JView {

	const GPHOST = 'mysql.eyedock.com';
	const GPUSER = 'eyedockdatauser';
	const GPPASS = 'kvBS^VQR';
	const GPDB = 'eyedock_data';
	
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
			$where[] = 'LOWER(pn_name) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}

		$where = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		
	//	if($limiter || $limit) {
			$limiter = ' LIMIT ' . $limitstart . ', ' . $limit;
	//	}
			
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'SELECT * from pn_rx_chem';
		$result = $mysqli->query($query);
		$total = $result->num_rows;
		
		jimport('joomla.html.pagination');
		$pagination = new JPagination( $total, $limitstart, $limit );		

		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'SELECT c.*, m.pn_name as moa_name from pn_rx_chem c left join pn_rx_moa m on (c.pn_moa_id = m.pn_moa_id) ' . $where . ' ORDER BY pn_name' . $limiter;
		$result = $mysqli->query($query);
		while($obj = $result->fetch_object()) {
			$chemicals[] = $obj;
		}
		
		$this->assignRef('chemicals', $chemicals);
		$this->assignRef('pagination', $pagination);
		
		parent::display($tpl);
	
	}	

}