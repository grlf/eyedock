<?php

// ini_set('display_errors', 1);
// ini_set('log_errors', 1);
// ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
// error_reporting(E_ALL);
// ini_set('html_errors', 'On');

defined('_JEXEC') or die ('Restricted Access');

jimport('joomla.application.component.controller');

class PncompaniesController extends JControllerLegacy {

	function __construct($default = array()) {

		parent::__construct($default);

		$this->registerTask( 'apply', 'save');
		$this->registerTask( 'edit', 'display');
		$this->registerTask( 'add' , 'display' );
		$this->registerTask( 'report' , 'display' );

	}

	function display($cachable = false, $urlparams = array()) {

		switch($this->getTask()) {

			case 'add': {

				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'layout', 'form'  );
				JRequest::setVar( 'view', 'company'  );
				JRequest::setVar( 'edit', false  );

			} break;

			case 'edit': {

				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'layout', 'form'  );
				JRequest::setVar( 'view', 'company'  );
				JRequest::setVar( 'edit', true  );

			} break;

			case 'report': {

				JRequest::setVar( 'hidemainmenu', 0 );
				JRequest::setVar( 'layout', 'form'  );
				JRequest::setVar( 'view', 'report'  );
				JRequest::setVar( 'edit', false  );

			} break;

		}

		//Set the default view, just in case
		$view = JRequest::getCmd('view');
		if(empty($view)) {
			JRequest::setVar('view', 'companies');
		};

		parent::display($cachable, $urlparams);

	}

	function save() {

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$post = JRequest::get( 'post' );

		$model = $this->getModel();

		if($post['id'] == 0) {
			if($model->addNewCompany($post)) {
				$msg = JText::_( 'Company added' );
				$link = 'index.php?option=com_pncompanies';
				$this->setRedirect($link, $msg);
			}
			else {
				$msg = JText::_( 'There was an error - the company was not added to the database' );
				$link = 'index.php?option=com_pncompanies';
				$this->setRedirect($link, $msg);
			}
		}

		else {
			if($model->updateCompany($post)) {
				$msg = JText::_( 'Company updated' );
				$link = 'index.php?option=com_pncompanies';
				$this->setRedirect($link, $msg);
			}
			else {
				$msg = JText::_( 'There was an error - the company was not updated' );
				$link = 'index.php?option=com_pncompanies';
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

			$res = $model->deleteCompany($ids[$i]);

		}

		$msg = JText::_( 'Companies deleted' );
		$link = 'index.php?option=com_pncompanies';
		$this->setRedirect($link, $msg);

	}

}