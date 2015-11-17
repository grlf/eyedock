<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

//require_once( JPATH_COMPONENT.DS.'helpers/powerLists/formatNumberText.php' );

class PnlensesViewDetails extends JViewLegacy {
                                               
    function display($id){
        //echo "Greetings, everything is ok!";
        //global $mainframe;
        $model = $this->getModel();
        $lens = $model->getLensDetails($id);
        //print_r($lens);
        $this->assignRef( 'lens', $lens);
        parent::display();
    }
}
?>