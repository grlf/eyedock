<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );

class PncompaniesViewCompanies extends JView {

//      const GPHOST = 'localhost';
//      const GPUSER = 'root';
//      const GPPASS = 'root';
//      const GPDB = 'eyedock_data';

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
		
	//	echo $limitstart;
	//	echo $limit;
		
		$where = array();
	
		if ($search) {
			//echo $search;
			$where[] = 'LOWER(name) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}

		$where = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		
	//	if($limiter || $limit) {
			$limiter = ' LIMIT ' . $limitstart . ', ' . $limit;
	//	}
			
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'SELECT * from pn_lenses_companies';
		$result = $mysqli->query($query);
		$total = $result->num_rows;		
	//	echo $total;
		
		jimport('joomla.html.pagination');
		$pagination = new JPagination( $total, $limitstart, $limit );		

		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'SELECT * from pn_lenses_companies ' . $where . ' ORDER BY pn_comp_name' . $limiter;
		//echo $query;
		$result = $mysqli->query($query);
		if ($result) while($obj = $result->fetch_object()) {
			$companies[] = $obj;
		}
		
		$this->assignRef('companies', $companies);
		$this->assignRef('pagination', $pagination);
		
		parent::display($tpl);
	
	}	

}