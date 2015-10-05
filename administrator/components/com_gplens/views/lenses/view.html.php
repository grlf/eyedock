<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );

class GplensViewLenses extends JViewLegacy {

	const GPHOST = 'mysql.eyedock.com';
	const GPUSER = 'eyedockdatauser';
	const GPPASS = 'kvBS^VQR';
	const GPDB = 'eyedock_data';
	
	const LENSIMGURL = 'http://www.eyedock.com/modules/Lenses/pnimages/lens_images/';
	const LENSPDFURL = 'http://www.eyedock.com/modules/Lenses/pnpdf/';
	
	protected $pagination;
	
	protected $state;

	function display($tpl = null) {

		global $option;
		
		$mainframe = JFactory::getApplication();
		
		$this->state = $this->get("State");
		
		$db	= JFactory::getDBO();
		$search = $this->state->get('filter.search');
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
		
		// Include the component HTML helpers.
		JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
		
		$this->sidebar = JHtmlSidebar::render();
		
		$this->pagination    = $this->get('Pagination');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		
		parent::display($tpl);
	
	}

}