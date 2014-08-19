<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );
require_once( JPATH_ROOT.DS.'utilities/mysqliSingleton.php' );

class PnlensesViewLenses extends JView {

	
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
		
	//	echo $limitstart;
	//	echo $limit;
		
		$where = array();
	
		if ($search) {
			$where[] = 'LOWER(l.pn_name) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}

		$where = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		
	//	if($limiter || $limit) {
			$limiter = ' LIMIT ' . $limitstart . ', ' . $limit;
	//	}

		$mysqli = DBAccess::getConnection();	
		//$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'SELECT pn_tid from pn_lenses';
		$result = $mysqli->selectQuery($query);
		$total = $result->num_rows;		
	//	echo $total;
		
		jimport('joomla.html.pagination');
		$pagination = new JPagination( $total, $limitstart, $limit );		
		
		$query = 'SELECT l.*, comp.pn_comp_name as companyname, pn_updated as updated, pn_discontinued as discontinued, pn_display as display, pn_bifocal as bifocal, pn_toric as toric, pn_cosmetic as cosmetic, poly.pn_poly_name as polymername from pn_lenses l left join pn_lenses_companies comp on (l.pn_comp_id = comp.pn_comp_tid) left join pn_lenses_polymers poly on (l.pn_poly_id = poly.pn_poly_tid)' . $where . ' ORDER BY l.pn_name' . $limiter;
	//	echo $query;
		$result = $mysqli->selectQuery($query);
		
		while($obj = $result->fetch_object()) {
			$lenses[] = $obj;
		}
		
		$this->assignRef('lenses', $lenses);
		$this->assignRef('pagination', $pagination);
		
		parent::display($tpl);
	
	}	

}