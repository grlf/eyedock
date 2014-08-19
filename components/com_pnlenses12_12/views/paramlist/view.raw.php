<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class PnlensesViewParamlist extends JView {
                                               
    function display($params){
        //echo "Greetings, everything is ok!";
        //global $mainframe;
        $model = $this->getModel();
       $lensArr = $model->getAdvSearch($params);
       //print_r($lensArr);
       $layout = JRequest::getVar( 'layout', 'default');   
       if ($layout == 'powers') {
       		$lenses = $model->getLensPowerListsFromArray($lensArr);
       } else {
       		$lenses = $model->getLensesFromArray($lensArr);
       }
       $this->assignRef( 'lenses', $lenses);
        parent::display();
    }
}
?>