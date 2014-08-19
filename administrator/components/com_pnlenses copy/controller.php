<?php

defined('_JEXEC') or die ('Restricted Access');

jimport('joomla.application.component.controller');

class PnlensesController extends JController {

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
				JRequest::setVar( 'view', 'lens'  );
				JRequest::setVar( 'edit', false  );

			} break;
			
			case 'edit': {
			
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'layout', 'form'  );
				JRequest::setVar( 'view', 'lens'  );
				JRequest::setVar( 'edit', true  );
			
			} break;
		
		}

		//Set the default view, just in case
		$view = JRequest::getCmd('view');
		if(empty($view)) {
			JRequest::setVar('view', 'lenses');
		};
	
		parent::display();
	
	}
	
	function save() {
	
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
                $post = JRequest::get( 'post', JREQUEST_ALLOWHTML );
            	//TMZ added 2012-08-21
				$post['otherinfo'] = JRequest::getVar( 'otherinfo', '', 'post', 'string', JREQUEST_ALLOWHTML );
				$post['sphnotes'] = JRequest::getVar( 'sphnotes', '', 'post', 'string', JREQUEST_ALLOWHTML );
				$post['cylnotes'] = JRequest::getVar( 'cylnotes', '', 'post', 'string', JREQUEST_ALLOWHTML );
		
		$model = $this->getModel();
	//	$model->updateLens($post);
		
		
		if($post['id'] == 0) {
			if($model->addNewLens($post)) {
				$msg = JText::_( 'Lens added' );
				$link = 'index.php?option=com_pnlenses';
				$this->setRedirect($link, $msg);				
			}
			else {
				$msg = JText::_( 'There was an error - the lens was not added to the database' );
				$link = 'index.php?option=com_pnlenses';
				$this->setRedirect($link, $msg);			
			}
		}
		
		else {
			if($model->updateLens($post)) {
				$msg = JText::_( 'Lens updated' );
				$link = 'index.php?option=com_pnlenses';
				$this->setRedirect($link, $msg);				
			}
			else {
				$msg = JText::_( 'There was an error - the lens was not updated' );
				$link = 'index.php?option=com_pnlenses';
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
		
			$res = $model->deleteLens($ids[$i]);
		
		}
		
		$msg = JText::_( 'Lenses deleted' );
		$link = 'index.php?option=com_pnlenses';
		$this->setRedirect($link, $msg);			
	
	}
	
}
