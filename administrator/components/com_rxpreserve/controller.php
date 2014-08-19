<?php

defined('_JEXEC') or die ('Restricted Access');

jimport('joomla.application.component.controller');

class RxpreserveController extends JController {

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
				JRequest::setVar( 'view', 'preservative'  );
				JRequest::setVar( 'edit', false  );

			} break;
			
			case 'edit': {
			
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'layout', 'form'  );
				JRequest::setVar( 'view', 'preservative'  );
				JRequest::setVar( 'edit', true  );
			
			} break;
		
		}

		//Set the default view, just in case
		$view = JRequest::getCmd('view');
		if(empty($view)) {
			JRequest::setVar('view', 'preservatives');
		};
	
		parent::display();
	
	}
	
	function save() {
	
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$post = JRequest::get( 'post' );
		
		$model = $this->getModel();
		
		if($post['id'] == 0) {
			if($model->addNewPreservative($post)) {
				$msg = JText::_( 'Preservative added' );
				$link = 'index.php?option=com_rxpreserve';
				$this->setRedirect($link, $msg);				
			}
			else {
				$msg = JText::_( 'There was an error - the preservative was not added to the database' );
				$link = 'index.php?option=com_rxpreserve';
				$this->setRedirect($link, $msg);			
			}
		}
		
		else {
			if($model->updatePreservative($post)) {
				$msg = JText::_( 'Preservative updated' );
				$link = 'index.php?option=com_rxpreserve';
				$this->setRedirect($link, $msg);				
			}
			else {
				$msg = JText::_( 'There was an error - the preservative was not updated' );
				$link = 'index.php?option=com_rxpreserve';
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
		
			$res = $model->deletePreservative($ids[$i]);
		
		}
		
		$msg = JText::_( 'Preservatives deleted' );
		$link = 'index.php?option=com_rxpreserve';
		$this->setRedirect($link, $msg);			
	
	}
	
}