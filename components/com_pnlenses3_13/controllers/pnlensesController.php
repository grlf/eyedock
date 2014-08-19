<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

define('EYEDOCK_SCL_IMG_URL', JPATH_ROOT.DS.'/components/com_pnlenses/images/');

#	ini_set('display_errors', 1); 
# ini_set('log_errors', 1); 
# ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
# error_reporting(E_ALL);
# ini_set('html_errors', 'On'); 

/**
 *  Component Controller
 */
class PnlensesController extends JController
{

	function display() {
		$document = & JFactory::getDocument();
   //This sets the default view (second argument)        
		 $viewName    = JRequest::getVar( 'view', 'pnlenses' ); 
		 $viewType = $document->getType();
		 //This sets the default layout/template for the view
		 $viewLayout  = JRequest::getVar( 'layout', 'default' );        
		 $view = &$this->getView($viewName, $viewType);

		 // Get/Create the model
		 if ($model = &$this->getModel('pnlenses')) {
				//Push the model into the view (as default)
				//Second parameter indicates that it is the default model for the view
				$view->setModel($model, true);
		 }
					   
		 $view->setLayout($viewLayout);
		 $view->display();
    } //end display
    
    function paramSearch() {
		$document = & JFactory::getDocument();
		$view = &$this->getView('paramlist', 'raw');	
		$params    = JRequest::getVar( 'params', null );	
		//print_r ($params);
		//if ($params === null) JError::raiseError(500, 'no data');
	
		 // Get/Create the model
		 if ($model = &$this->getModel('pnlenses')) $view->setModel($model, true);
		 $viewLayout  = JRequest::getVar( 'layout', 'default' );              	 
		 $view->setLayout($viewLayout);
		 $view->display($params);
    }
    
    function details() {
		$document = & JFactory::getDocument();
		$view = &$this->getView('details', 'raw');	
		$id    = JRequest::getVar( 'id', null );	
		if ($id === null) JError::raiseError(500, 'no id given');
	
		 // Get/Create the model
		 if ($model = &$this->getModel('pnlenses')) $view->setModel($model, true);
					   
		 $view->setLayout('default');
		 $view->display($id);
    }
    
    function dataList() {
		$document = & JFactory::getDocument();
		$view = &$this->getView('data', 'raw');	 
		$search    = JRequest::getVar( 'search', null );
	   // echo $search;
		if ($search === null) JError::raiseError(500, 'no task identified');       
		 // Get/Create the model
		 if ($model = &$this->getModel('pnlenses')) $view->setModel($model, true);
					   
		 $view->setLayout('default');
		 $view->display($search);
    }
    
    function userPrefs() {
   		$document = & JFactory::getDocument();
   		$prefs    = JRequest::getVar( 'prefs', null );
   		if ($prefs === null) JError::raiseError(500, 'no user preferences supplied');
   		$model =& $this->getModel('pnlenses');
   		$model->updateUserPrefs($prefs);
	}

	function bestPowerForLens() {
   		$document = & JFactory::getDocument();
		$view = &$this->getView('data', 'raw');	 
		$search    = JRequest::getVar( 'search', null );
	   // echo $search;
		if ($search === null) JError::raiseError(500, 'no task identified');       
		 // Get/Create the model
		 if ($model = &$this->getModel('pnlenses')) $view->setModel($model, true);
		 $view->setLayout('suggested_powers');
		 $view->display($search);
	}

}
?>