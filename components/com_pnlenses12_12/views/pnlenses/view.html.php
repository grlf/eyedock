<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class PnlensesViewPnlenses extends JView {
                                               
    function display(){
        // echo "Greetings, everything is ok!";
        $model = $this->getModel();
        //$lenses = $model->getLenses();
		//$companies = $model->getCompanies();
		//$polymers = $model->getPolymers();
        //$this->assignRef( 'lenses', $lenses );
        //$this->assignRef( 'companies', $companies );
        //$this->assignRef( 'polymers', $polymers );
       
        parent::display();
    }
}
?>