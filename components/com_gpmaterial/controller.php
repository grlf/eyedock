<?php

defined('_JEXEC') or die ('Restricted Access');

jimport('joomla.application.component.controller');

class GpMaterialController extends JController {

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
	
	function getMaterialsByCompanyId() {
	
		global $mainframe;
		
		$ids = json_decode(JRequest::getVar('ids'));
		$model = $this->getModel();
		$materials = $model->getMaterialsByCompanyId($ids);
		
		echo json_encode($materials);
	
		$mainframe->close();
	
	}
	
	function getMaterialsBySearchstring() {
	
		global $mainframe;
		
		$searchstring = JRequest::getVar('string');
		$model = $this->getModel();
		$materials = $model->getMaterialsBySearchstring($searchstring);
		
		echo json_encode($materials);
	
		$mainframe->close();
	
	}
	
	function getMaterialsBySliders() {
	
		global $mainframe;
		
		$data = json_decode(JRequest::getVar('data'));
		$model = $this->getModel();
		$materials = $model->getMaterialsBySliders($data);
		
		echo json_encode($materials);
	
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