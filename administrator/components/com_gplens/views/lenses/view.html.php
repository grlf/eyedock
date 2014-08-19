<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );

class GplensViewLenses extends JView {

	const GPHOST = 'mysql.eyedock.com';
	const GPUSER = 'eyedockdatauser';
	const GPPASS = 'kvBS^VQR';
	const GPDB = 'eyedock_data';
	
	const LENSIMGURL = 'http://www.eyedock.com/modules/Lenses/pnimages/lens_images/';
	const LENSPDFURL = 'http://www.eyedock.com/modules/Lenses/pnpdf/';


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
			$where[] = 'LOWER(l.name) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}

		$where = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		
	//	if($limiter || $limit) {
			$limiter = ' LIMIT ' . $limitstart . ', ' . $limit;
	//	}
			
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'SELECT * from rgpLenses ORDER BY name';
		$result = $mysqli->query($query);
		$total = $result->num_rows;		
	//	echo $total;
		
		jimport('joomla.html.pagination');
		$pagination = new JPagination( $total, $limitstart, $limit );		
		
		$query = 'SELECT l.*, c.name as companyname, dc.name as designcategory, sc.name as subcategory from rgpLenses l left join rgpLab c on (l.rgpCompanyID = c.tid) left join rgpDesignCategory dc on (l.designCategoryID = dc.tid) left join rgpSubcategory sc on (l.subcategoryID = sc.tid) ' . $where . ' ORDER BY l.name' . $limiter;
	//	echo $query;
		$result = $mysqli->query($query);
		
		while($obj = $result->fetch_object()) {
			$lenses[] = $obj;
		}
		
		$this->assignRef('lenses', $lenses);
		$this->assignRef('pagination', $pagination);
		
		parent::display($tpl);
	
	}	

}