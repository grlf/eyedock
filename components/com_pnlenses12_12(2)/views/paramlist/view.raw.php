<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class PnlensesViewParamlist extends JView {
                                               
    function display($params){
        //echo "Greetings, everything is ok!";
        //global $mainframe;
        $model = $this->getModel();
       
       //print_r($lensArr);
       $layout = JRequest::getVar( 'layout', 'default');   
       if ($layout == 'powers') {
       		$lensArr = $model->findCLsByPower($params);
       		$lenses = $model->getLensPowerListsFromArray($lensArr);
       } else {
       		$lensArr = $model->getAdvSearch($params);
       		$lenses = $model->getLensesFromArray($lensArr);
       }
       $this->assignRef( 'lenses', $lenses);
        parent::display();
    }
}
?>