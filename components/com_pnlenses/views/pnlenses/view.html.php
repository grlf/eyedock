<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class PnlensesViewPnlenses extends JViewLegacy {
                                               
    function display(){
        // echo "Greetings, everything is ok!";
        $model = $this->getModel();
        //$lenses = $model->getLenses();
		//$companies = $model->getCompanies();
		//$polymers = $model->getPolymers();
        //$this->assignRef( 'lenses', $lenses );
        //$this->assignRef( 'companies', $companies );
        //$this->assignRef( 'polymers', $polymers );
        $userPrefs = $model->getUserPrefs();
        //print_r($userPrefs);
        $this->assignRef( 'userPrefs', $userPrefs);
        parent::display();
    }
}
?>