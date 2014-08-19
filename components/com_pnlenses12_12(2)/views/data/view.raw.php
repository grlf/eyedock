<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

//require_once( JPATH_COMPONENT.DS.'helpers/powerLists/formatNumberText.php' );

class PnlensesViewData extends JView {
                                               
    function display($search){
        //echo "Greetings, everything is ok!" . $search;
        //global $mainframe;
        //$phrase  = JRequest::getVar( 'task', null );
        $model = $this->getModel();
        if ($search == "company") $dataList = $model->getCompanies();
        if ($search == "polymer") $dataList = $model->getPolymers();
        //print_r($dataList);
        $this->assignRef( 'type', $type);
        $this->assignRef( 'dataList', $dataList);
        parent::display();
    }
    
    
    
}
?>