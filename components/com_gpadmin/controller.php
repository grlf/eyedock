<?php

defined('_JEXEC') or die ('Restricted Access');

jimport('joomla.application.component.controller');

class GpLensController extends JController {

	function display() {
		parent::display();
	}
	
	function getCompanyList() {
	
		global $mainframe;
		
		$model = $this->getModel();
		$companies = $model->getCompanies();
		
		echo json_encode($companies);
		
		$mainframe->close();
	
	}
	
	function getParameters() {
	
		global $mainframe;
		
		$model = $this->getModel();
		$parameters = $model->getParameters();
		
		echo json_encode($parameters);
		
		$mainframe->close();		
	
	}
	
	function getLensesByCompanyId() {
	
		global $mainframe;
		
		$ids = json_decode(JRequest::getVar('ids'));
		$model = $this->getModel();
		$lenses = $model->getLensesByCompanyId($ids);
		
		echo json_encode($lenses);
	
		$mainframe->close();
	
	}
	
	function getLensesBySearchstring() {
	
		global $mainframe;
		
		$searchstring = JRequest::getVar('string');
		$model = $this->getModel();
		$lenses = $model->getLensesBySearchstring($searchstring);
		
		echo json_encode($lenses);
	
		$mainframe->close();
	
	}
	
	function getLensesByParameters() {
	
		global $mainframe;
		
		$params = json_decode(JRequest::getVar('params'));
		$model = $this->getModel();
		$lenses = $model->getLensesByParameters($params);
		
		echo json_encode($lenses);
	
		$mainframe->close();
	
	}
	
	function getLensDetails() {
	
		global $mainframe;
		
		$id = JRequest::getVar('id');
		$model = $this->getModel();
		$details = $model->getLensDetails($id);
		
		$mainframe->close();
	
	}
	
	function getMaterialDetails() {
	
		global $mainframe;
		
		$id = JRequest::getVar('id');
		$model = $this->getModel();
		$details = $model->getMaterialDetails($id);
		
		$mainframe->close();
	
	}

}