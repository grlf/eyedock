<?php

defined('_JEXEC') or die ('Restricted Access');

jimport('joomla.application.component.controller');

class RxchemController extends JController {

	function __construct($default = array()) {
	
		parent::__construct($default);
		
		$this->registerTask( 'apply', 'save');
		$this->registerTask( 'edit', 'display');
		$this->registerTask( 'add' , 'display' );
	
	}
	
	function display() {
	
		switch($this->getTask()) {
		
			case 'add': {
			
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'layout', 'form'  );
				JRequest::setVar( 'view', 'chemical'  );
				JRequest::setVar( 'edit', false  );

			} break;
			
			case 'edit': {
			
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'layout', 'form'  );
				JRequest::setVar( 'view', 'chemical'  );
				JRequest::setVar( 'edit', true  );
			
			} break;
		
		}

		//Set the default view, just in case
		$view = JRequest::getCmd('view');
		if(empty($view)) {
			JRequest::setVar('view', 'chemicals');
		};
	
		parent::display();
	
	}
	
	function save() {
	
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$post = JRequest::get( 'post' );
		
		$model = $this->getModel();
		
		if($post['id'] == 0) {
			if($model->addNewChemical($post)) {
				$msg = JText::_( 'Chemical added' );
				$link = 'index.php?option=com_rxchem';
				$this->setRedirect($link, $msg);				
			}
			else {
				$msg = JText::_( 'There was an error - the chemical was not added to the database' );
				$link = 'index.php?option=com_rxchem';
				$this->setRedirect($link, $msg);			
			}
		}
		
		else {
			if($model->updateChemical($post)) {
				$msg = JText::_( 'Chemical updated' );
				$link = 'index.php?option=com_rxchem';
				$this->setRedirect($link, $msg);				
			}
			else {
				$msg = JText::_( 'There was an error - the chemical was not updated' );
				$link = 'index.php?option=com_rxchem';
				$this->setRedirect($link, $msg);			
			}
		}

	}
	
	function remove() {
	
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$model = $this->getModel();
		$ids = JRequest::getVar( 'cid', array(0), '', 'array' );
		
	//	echo $ids[0];
		
		for ($i=0, $n=count($ids); $i < $n; $i++) {
		
			$res = $model->deleteChemical($ids[$i]);
		
		}
		
		$msg = JText::_( 'Chemicals deleted' );
		$link = 'index.php?option=com_rxchem';
		$this->setRedirect($link, $msg);			
	
	}
	
}