<?php

defined('_JEXEC') or die ('Restricted Access');

jimport('joomla.application.component.controller');

class GpmaterialcompanyController extends JController {

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
				JRequest::setVar( 'view', 'materialcompany'  );
				JRequest::setVar( 'edit', false  );

			} break;
			
			case 'edit': {
			
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'layout', 'form'  );
				JRequest::setVar( 'view', 'materialcompany'  );
				JRequest::setVar( 'edit', true  );
			
			} break;
		
		}

		//Set the default view, just in case
		$view = JRequest::getCmd('view');
		if(empty($view)) {
			JRequest::setVar('view', 'materialcompanies');
		};
	
		parent::display();
	
	}
	
	function save() {
	
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$post = JRequest::get( 'post' );
		
		$model = $this->getModel();
	//	$model->updateLab($post);
		
		if($post['id'] == 0) {
			if($model->addNewMaterialcompany($post)) {
				$msg = JText::_( 'Material company added' );
				$link = 'index.php?option=com_gpmaterialcompany';
				$this->setRedirect($link, $msg);				
			}
			else {
				$msg = JText::_( 'There was an error - the material company was not added to the database' );
				$link = 'index.php?option=com_gpmaterialcompany';
				$this->setRedirect($link, $msg);			
			}
		}
		
		else {
			if($model->updateMaterialcompany($post)) {
				$msg = JText::_( 'Material company updated' );
				$link = 'index.php?option=com_gpmaterialcompany';
				$this->setRedirect($link, $msg);				
			}
			else {
				$msg = JText::_( 'There was an error - the material company was not updated' );
				$link = 'index.php?option=com_gpmaterialcompany';
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
		
			$res = $model->deleteMaterialcompany($ids[$i]);
		
		}
		
		$msg = JText::_( 'Material companies deleted' );
		$link = 'index.php?option=com_gpmaterialcompany';
		$this->setRedirect($link, $msg);			
	
	}
	
}