<?php

defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );
require_once( JPATH_ROOT.DS.'utilities/mysqliSingleton.php' );

class PncompaniesViewCompanies extends JViewLegacy {


	const LENSIMGURL = '#';
	const LENSPDFURL = '#';


	function display($tpl = null) {

		global $mainframe, $option;

		$mainframe = JFactory::getApplication();
		$db	= JFactory::getDBO();
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

		$mysqli = DBAccess::getConnection();
		$query = 'SELECT * from pn_lenses_companies';

		$result = $mysqli->selectQuery($query);
		$total = $result->num_rows;
	//	echo $total;

		jimport('joomla.html.pagination');
		$pagination = new JPagination( $total, $limitstart, $limit );

		//$mysqli = getSQLI();
		$query = 'SELECT * from pn_lenses_companies ' . $where . ' ORDER BY pn_comp_name' . $limiter;
		//echo $query;
		$result = $mysqli->selectQuery($query);
		if ($result) while($obj = $result->fetch_object()) {
			$companies[] = $obj;
		}
		$this->assignRef('search', $search);
		$this->assignRef('companies', $companies);
		$this->assignRef('pagination', $pagination);

		$this->addToolBar();

		parent::display($tpl);

	}

	public function addToolBar() {

		JHtmlSidebar::addEntry('Soft Lenses', 'index.php?option=com_pnlenses');
		JHtmlSidebar::addEntry('SoftLenses Polymers', 'index.php?option=com_pnpolymers');

		JToolBarHelper::title( JText::_( 'Soft Lens Companies Administration' ), 'generic.png' );
		JToolBarHelper::deleteList();
		JToolBarHelper::editList();
		JToolBarHelper::addNew('add');
		JToolBarHelper::custom( 'report', 'send.png', '', 'Report', false, false );
	}

}