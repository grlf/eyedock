<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class PnlensesViewParamlist extends JViewLegacy {
                                               
    function display($params){
        //echo "Greetings, everything is ok!";
        //global $mainframe;
        $model = $this->getModel();
        
        //print_r($params);
       
       //layout is set in advSearch.js  submitSearch ()
       $layout = JRequest::getVar( 'layout', 'default');   
       if ($layout == 'powers') {
       		$lensArr = $model->findCLsByPower($params);
       		if (!isset($params['tolerance']) ) $params['tolerance'] = 0;
       		if (!isset($params['lensCount']) ) $params['lensCount'] = 50;
       		//expand tolerances if too few lenses are found
       		while (count($lensArr) < 10 ) {
       			$params['tolerance'] += .5;
       			$lensArr = $model->findCLsByPower($params);
       			//print_r($lensArr); 
       			if ($params['tolerance'] > 3) break; //don't want an infinite loop!
       		}
       		$lenses = $model->getLensPowerListsFromArray($lensArr, $params['lensCount'] );
       } else {
       		if ($params['company']) {
       			$company_arr = $model->getCompanyDetails($params['company'][0]); 
       			//print_r($company_arr));
       			$this->assignRef( 'companyInfo', $company_arr);
       		}
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

