<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class PnlensesViewParamlist extends JView {
                                               
    function display($params){
        //echo "Greetings, everything is ok!";
        //global $mainframe;
        $model = $this->getModel();
       
       //layout is set in advSearch.js  submitSearch ()
       $layout = JRequest::getVar( 'layout', 'default');   
       if ($layout == 'powers') {
       		$lensArr = $model->findCLsByPower($params);
       		if (!isset($params['tolerance']) ) $params['tolerance'] = 0;
       		//expand tolerances if too few lenses are found
       		while (count($lensArr) < 10 ) {
       			$params['tolerance'] += .5;
       			$lensArr = $model->findCLsByPower($params);
       			if ($params['tolerance'] > 3) break; //don't want an infinite loop!
       		}
       		$lenses = $model->getLensPowerListsFromArray($lensArr);
       } else {
       		$lensArr = $model->getAdvSearch($params);
       		$lenses = $model->getLensesFromArray($lensArr);
       }
      // echo "<p>results:   ";
       //print_r($lenses);    
       //echo "</p>";
       $this->assignRef( 'lenses', $lenses);
        parent::display();
    }
}
?>

