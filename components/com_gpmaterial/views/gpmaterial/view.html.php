<?php

defined('_JEXEC') or die ('Restricted Access');

jimport('joomla.application.component.view');

class GpMaterialViewGpMaterial extends JView {

	function display($tpl = null) {
	
		$model = $this->getModel();
		
		parent::display($tpl);
	
	}

}