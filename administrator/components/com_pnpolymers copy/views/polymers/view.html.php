<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );

class PnpolymersViewPolymers extends JView {

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
			$where[] = 'LOWER(pn_poly_name) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}

		$where = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		
	//	if($limiter || $limit) {
			$limiter = ' LIMIT ' . $limitstart . ', ' . $limit;
	//	}
			
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'SELECT * from pn_lenses_polymers';
		$result = $mysqli->query($query);
		$total = $result->num_rows;		
	//	echo $total;
		
		jimport('joomla.html.pagination');
		$pagination = new JPagination( $total, $limitstart, $limit );		

		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'SELECT * from pn_lenses_polymers ' . $where . ' ORDER BY pn_poly_name' . $limiter;
		$result = $mysqli->query($query);
		while($obj = $result->fetch_object()) {
			$polymers[] = $obj;
		}
		
		foreach($polymers as $poly) {
	
			switch($poly->pn_fda_grp) {
			
				case 1:
					$poly->pn_fda_grp = 'Low Water (<50 percent water content), Nonionic Polymers.<br />This group has the greatest resistance to protein deposition due 
to its lower water content and nonionic nature. Heat, chemical, and hydrogen peroxide care regimens can be used.';
				break;
		
				case 2:
					$poly->pn_fda_grp = 'High Water (greater than 50 percent water content), Nonionic Polymers.<br />The higher water content of this group results in g
reater protein attraction than with group 1. However, the nonionic polymers reduce the potential for further attraction. Heat disinfection should be a
voided because of the high water content. In addition, sorbic acid and potassium sorbate preservatives can discolor the lenses.';						
				break;
				
				case 3:
					$poly->pn_fda_grp = 'Low Water (less then 50 percent water content), Ionic Polymers.<br />The lower water content but ionic nature of these polymers
 results in intermediate protein resistance. Heat, chemical and hydrogen peroxide care systems may be used.';					
				break;
				
				case 4:
					$poly->pn_fda_grp = 'High Water (greater then 50 percent water content), Ionic Polymers.<br />Because of the high water content and ionic nature of 
these polymers they attract more proteins than any other group. It is best to avoid heat disinfection and sorbic acid preservatives.';				
				break;
			
			}
	
		}
		
		$this->assignRef('polymers', $polymers);
		$this->assignRef('pagination', $pagination);
		
		parent::display($tpl);
	
	}	

}