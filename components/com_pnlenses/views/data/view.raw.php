<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class PnlensesViewData extends JViewLegacy {
                                               
    function display($search){
        //echo "Greetings, everything is ok! (" . $search . ")<br>";
        //global $mainframe;
        //$phrase  = JRequest::getVar( 'task', null );
        $model = $this->getModel();
        if ($search == "company") $dataList = $model->getCompanies();
        if ($search == "polymer") $dataList = $model->getPolymers();
        if ($search == "lensPowers") {
        	//$dataList = $model->getPolymers();
        	$dataList = $model->suggestLensPowersForLens();
        	//if (count($dataList) );
        	//print_r( $dataList);
        }
        //print_r($dataList[0]);
        //$this->assignRef( 'type', $type);
       $this->assignRef( 'dataList', $dataList);
       parent::display();
    }
}
?>
