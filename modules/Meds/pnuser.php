<?php

/*
 * Main display page.
 * 
 * @param        integer      $med_id  ID of the med to display
 * @param        array        $search  Array containg search info; used as flag.
 * @return       output       Templated display of single medication.

 */


 function Meds_user_main()
{
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_OVERVIEW)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $pnRender =& new pnRender('Meds');
        
    return $pnRender->fetch('meds_user_main.htm');
}



function Meds_user_search($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_OVERVIEW)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
    
    // Clean any search data.
    list($search, $q, $active) = pnVarCleanFromInput('search','q','active');
    
    if (isset($q)){
       $search[trade] = $q;
       $search[type] = "phrase";
    }

    // Get any results from the database.
    $results = pnModAPIFunc('Meds', 'user', 'search_result', array('search'=>$search));

    $count = count($results);
    if (!is_array($results) ) $count=0;
    
    //a little written summary of what was searched for
    if ($active) $summary = pnModFunc('Meds', 'user', 'search_summary', array('search'=>$search, 'count' => $count));

    
    
    if ($count == 1 && is_array($results) && pnSecAuthAction(0, 'Meds::', '::', ACCESS_READ)  )pnRedirect(pnModURL('Meds', 'user', 'display', array('search' => $search, 'med_id' => $results[0]['med_id'])));


    // Start a new output object.
    $pnRender =& new pnRender('Meds');
    $pnRender->caching = false;
    $pnRender->assign('summary', $summary);
    $pnRender->assign('search', $search);
    $pnRender->assign('results', $results);
  
    $pnRender->assign('count', $count);
    $pnRender->assign(pnModAPIFunc('Meds', 'user', 'getall_selects'));

    // Return search form.
    return $pnRender->fetch('meds_user_search.htm');
}


//a utility function to make a written summary of what was searched for.
function Meds_user_search_summary($args){
    
    // Security check
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_OVERVIEW)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $count           = $args['count'];

    $trade           = $args['search']['trade'];
    $preg            = $args['search']['preg'];
    $generic         = $args['search']['generic'];
    $medType         = $args['search']['medType'];
    $comp_id         = $args['search']['comp_id'];
    $pres_id         = $args['search']['pres_id'];
    $moa_id          = $args['search']['moa_id'];
    
     //initiate a return variable
    $summary = "<p class='meds_highlight'>Your search for medications that ";
    
    //variable for punctuation.
    $comma_count=0;
    

    
    if ($trade){
       $summary .= "have a trade name or chemical name like '".$trade."'";
       $comma_count++;
    }
    if ($comp_id){
       $temp=pnModAPIFunc('Meds', 'user', 'get', array('object'=>'company','comp_id'=>$comp_id));
       $comp_name=$temp['name'];
       if ($comma_count>0) $summary .=", ";
       $summary .= "are manufactured by ".$comp_name;
       $comma_count++;
    }
    if ($pres_id){
       $temp=pnModAPIFunc('Meds', 'user', 'get', array('object'=>'preserve','pres_id'=>$pres_id));
       $pres_name=$temp['name'];
       if ($comma_count>0) $summary .=", ";
       $summary .= "preserved with ".$pres_name;
       $comma_count++;
    }
    if ($medType){
      if ($comma_count>0) $summary .=", ";
       $summary .= "fall into the ".$medType." category";
       $comma_count++;
    }
    if ($moa_id){
       $temp=pnModAPIFunc('Meds', 'user', 'get', array('object'=>'moa','moa_id'=>$moa_id));
       $moa_name=$temp['name'];
       if ($comma_count>0) $summary .=", ";
       $summary .= "have a method of action of: ".$moa_name;
       $comma_count++;
    }
    if ($preg){
      if ($comma_count>0) $summary .=", ";
       $summary .= "are in pregnancy class ".$preg;
       $comma_count++;
    }
    if ($generic){
      if ($comma_count>0) $summary .=", ";
       $summary .= "are avaialable as a generic medication";
    }
    
    if ($count < 1){
           $summary .=" <strong>did not yield any results.</strong>  Try to simplify your search criterion or search by a different method.";
    } else {
           $summary .=" yielded <strong>$count</strong> results. Click the medication's name to see more detailed information.";
    }
    $summary .="</p>";

    return $summary;
}


function Meds_user_display($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_READ)) {
        return pnVarPrepHTMLDisplay(_MODULENOTSUBSCRIBED);
    }

    // This is a flag to use in the template for 
    // the purpose of displaying a go-back link.
    // This flag is needed because the go back link
    // is not needed when the user dialed in a med
    // and displayed it directly (ie, non-search)
    $search = pnVarCleanFromInput('search');
    
    // Get the object type and start number.
    $med_id = pnVarCleanFromInput('med_id');

    // Get medication from database.
    $med = pnModAPIFunc('Meds', 'user', 'get', array('object'=>'med', 'med_id'=>$med_id));

    // Check if medication could not be obtained.
    if (!$med) {
        return pnVarPrepHTMLDisplay(_NOSUCHITEM);
    }
  
  	if (strpos($med['rxInfo'], "pdf/") === 0)  $med['rxInfo'] = "modules/Meds/pn".$med['rxInfo'];
//print (strpos($med['rxInfo'], "pdf/"));

    //information used for popup windows. I'm sure there's a better way to do this but...
    $pregnancy = pnModAPIFunc('Meds', 'user', 'preg_descriptions');
    $schedules = pnModAPIFunc('Meds', 'user', 'sched_descriptions');
    if ($med['pres_id1'])$pres_info1 = pnModAPIFunc('Meds', 'user', 'get', array('object'=>'preserve','pres_id'=>$med['pres_id1']));
    if ($med['pres_id2']) $pres_info2 = pnModAPIFunc('Meds', 'user', 'get', array('object'=>'preserve','pres_id'=>$med['pres_id2']));
    $comp_info = pnModAPIFunc('Meds', 'user', 'get', array('object'=>'company','comp_id'=>$med['comp_id']));
    $comp_text = pnModFunc('Meds', 'user', 'company_popup', array('comp_info'=>$comp_info));
    if ($med['moa_id1']) $moa_info1 = pnModAPIFunc('Meds', 'user', 'get', array('object'=>'moa','moa_id'=>$med['moa_id1']));
    if ($med['moa_id2']) $moa_info2 = pnModAPIFunc('Meds', 'user', 'get', array('object'=>'moa','moa_id'=>$med['moa_id2']));
    if ($med['moa_id3']) $moa_info3 = pnModAPIFunc('Meds', 'user', 'get', array('object'=>'moa','moa_id'=>$med['moa_id3']));
    if ($med['moa_id4']) $moa_info4 = pnModAPIFunc('Meds', 'user', 'get', array('object'=>'moa','moa_id'=>$med['moa_id4']));

    // Start a new output object.
    $pnRender =& new pnRender('Meds');

    // Assign medication's data to template.
    $pnRender->assign('med', $med);

    //assign popup info to templates
    $pnRender->assign('preg', $pregnancy[$med['preg']]);
    $pnRender->assign('sched', $schedules[$med['schedule']]);
    $pnRender->assign('comp_text', $comp_text);
    $pnRender->assign('preserve_info1', $pres_info1['comments']);
    $pnRender->assign('preserve_info2', $pres_info2['comments']);
    $pnRender->assign('moa_info1', $moa_info1['comments']);
    $pnRender->assign('moa_info2', $moa_info2['comments']);
    $pnRender->assign('moa_info3', $moa_info3['comments']);
    $pnRender->assign('moa_info4', $moa_info4['comments']);

    // Assign flag to template; for search back-links.
    if (!empty($search)) {
        $pnRender->assign('search', $search);
    }

    // Assign flag for admin permission capacity.
    $pnRender->assign('is_admin', pnSecAuthAction(0, 'Meds::', '::', ACCESS_ADMIN));
    
     // Let any hooks know that we are displaying an item.  As this is a display
    // hook we're passing a URL as the extra info, which is the URL that any
    // hooks will show after they have finished their own work.  It is normal
    // for that URL to bring the user back to this function
    $pnRender->assign('hooks' ,pnModCallHooks('item',
                                              'display',
                                              $med_id,
                                              pnModURL('Meds',
                                                       'user',
                                                       'display',
                                                       array('med_id' =>$med_id))));

    // Get options for all dropdowns.  These are not used
    // for dropdowns here, but rather are used to help convert
    // the med's various ids back into meaning texts.
    $pnRender->assign(pnModAPIFunc('Meds', 'user', 'getall_selects'));

    // Return templated output.
    return $pnRender->fetch('meds_user_display.htm');
}

function Meds_user_company_popup($args){

      $comp = $args['comp_info'];
      //a string variable to return
      $company_info = "";
      
      if ($comp['phone']) $company_info .= $comp['phone']."<br />";
      if ($comp['street']) $company_info .= $comp['street']."<br />";
      if ($comp['city']) $company_info .= $comp['city'].", ";
      if ($comp['state']) $company_info .= $comp['state']." ";
      if ($comp['zip']) $company_info .= $comp['zip']."<br />";
      if ($comp['email']) $company_info .= "<a href='mailto:".$comp['email']."' target='_blank' >Their email</a><br />";
      if ($comp['url']) $company_info .= "<a href='".$comp['url']."' target='_blank'>Their website</a><br />";
      if ($comp['comments']) $company_info .= "Comments: ".$comp['comments'];
      
      return $company_info;
}

?>