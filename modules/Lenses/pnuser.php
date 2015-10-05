<?php

function Lenses_user_flash()
{
     if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_OVERVIEW)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
	    list( $tid, $q) = pnVarCleanFromInput( 'tid','q');
	
	$pnRender =& new pnRender('Lenses');
	
	if (isset ($tid) ) $pnRender->assign('tid', $tid);
    if (isset ($q) ) $pnRender->assign('q', $q);
	
	if (pnSecAuthAction(0, 'Lenses::', '::', ACCESS_EDIT)) {
            $pnRender->assign('edit_lens',"true");
        }
	//print "logged in: ".pnUserLoggedIn();
	return $pnRender->fetch('lenses_user_flash.htm');
}

function Lenses_user_main()
{
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_OVERVIEW)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

        //count recently searched lenses (saved as an array of IDs in a session variable)
        $saved_lens_count = count(pnSessionGetVar('saved_lens_array'));

	$pnRender =& new pnRender('Lenses');
	
	//get the company data for the drop-down boxes
	$opt_companies      = pnModAPIFunc('Lenses', 'user', 'getall', array('item_type'=>'companies'));
	$opt_polymers       = pnModAPIFunc('Lenses', 'user', 'getall', array('item_type'=>'polymers'));

    $pnRender->assign('opt_companies',$opt_companies);
    $pnRender->assign('opt_polymers',$opt_polymers);
	$pnRender->assign('saved_lens_count',$saved_lens_count);

    return $pnRender->fetch('lenses_user.htm');
}



function Lenses_user_search_wizard2($args)
{
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_OVERVIEW)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
        list($items,$material)= pnVarCleanFromInput('item','material');
	//get the selections from the main form.  This data will determine what will be displayed on the advanced search form.

	if ($material!=1) $items[material]="";
	extract($args);

	$pnRender =& new pnRender('Lenses');
	
	//get the polymer and company data for the drop-down boxes
	$opt_companies      = pnModAPIFunc('Lenses', 'user', 'getall', array('item_type'=>'companies'));
       $opt_polymers       = pnModAPIFunc('Lenses', 'user', 'getall', array('item_type'=>'polymers'));
	$pnRender->assign('opt_companies',$opt_companies);
        $pnRender->assign('opt_polymers',$opt_polymers);
	$pnRender->assign('terms',$items);

    return $pnRender->fetch('lenses_user_wizard_form2.htm');
}


//The advanced search wizard allows the user to select which parameters they'd like to search by and then creates a form to do that search.
function Lenses_user_search_wizard1(){
     
     if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_OVERVIEW)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
    
    $pnRender =& new pnRender('Lenses');
    return $pnRender->fetch('lenses_user_wizard_form1.htm');

}



// -----------------------------------------------
// function list takes the form data from the search.htm form.
// the $search array returned by this form uses database fields as keys and 
// the values are phrases that will be added to the SQL query 

function Lenses_user_view($args)
{
    // Security check 
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_OVERVIEW)) {
        return pnVarPrepHTMLDisplay(_NOTSUBSCRIBED);
    }

    list( $phrase,
          $q,
          $display_phrase,
          $search,
          $or,
          $and,
          $markings,
          $markings2,
          $wizard)
    = pnVarCleanFromInput( 'phrase',
                           'q',
                           'display_phrase',
                           'search',
                           'or',
                           'and',
                           'markings',
                           'markings2',
                           'wizard');

    //print_r($or);
    //print_r($search);

    if (isset($q)) $phrase = $q;
    //echo ("here");
    //echo($q);
    //echo ($phrase);
	/*
	The following will require some explanation.  I want the search to be very dynamic, and I wanted to be able to take the user's input from the search form and create an SQL query from it.  I did this by creating several arrays that will ultimately be passed to the API function.  This includes the
	*/
	
        //the $or_array will have this structure: =>[field]=array (value, value...), where the key is a database field.  The API will eventually parse this to make part of the SQL statement: "...WHERE (key = 'value' OR key='value...)"
	$or_array=array();
	
	//the $and_array will have the same structure: =>[field]=array (value, value...), where the key is a database field.  The API will eventually parse this to make part of the SQL statement: "...WHERE (key = 'value' AND key='value...)". The only difference is the values will be separated by "AND".
	$and_array=array();
	
	//The and_or array.  Each array contains an array where the key is a field name and the value is a search value, like this: =>[0] => ([field1]=>value1, [field2]=>value2,..)...  This will ultimately be parsed to look like this: "...WHERE (field1 = value1 OR field2=value2...).  This is different from the or_array in that different fields can be used.
	$and_or_array=array();
	
	//the $select array is a list of database fields for which the user has specified an interest.
	//the following values will be defaults to be displayed
	$select=array('tid', 'name','comp_name', 'discontinued','max_minus','max_plus','toric','bifocal','cosmetic', 'max_cyl_power','max_add');

	//if the user is simply searching for a lens by name the $phrase variable will have been passed.  Since it may contain multiple words, it will be exploded and all terms will be searched in the names and aliases fields.
	if (isset($phrase) && $phrase!=""){
		$phrase_array=explode(" ",$phrase);
		//print_r($phrase_array);
		$i=0;
		foreach ($phrase_array as $value){
			$and_or_array[$i][name]=" LIKE '%$value%'";
			$and_or_array[$i][aliases]=" LIKE '%$value%'";
			$i++;
		}
		//this is just a term to display on the search results page
		$display_phrase="that contain the phrase '".$phrase."'";
	} 
		// if a phrase isn't passed to this fuction, assume the more "advanced" search is being requested
	   // Clean input to this function.
	   
	   //the search array will be passed with database fields as keys and search criterion as values.  The API will eventually parse this into the SQL statement.  The values from the $or and $and arrays will be added to the appropriate arrays that will be passed to the API.

		foreach ($or as $value){
			 $temp = pnVarCleanFromInput($value);
			if (is_array($temp)){
				$or_array[$value]=$temp;
				$select[]=$value;
			}
		}

		foreach ($and as $value){
			$temp = pnVarCleanFromInput($value);
			if (is_array($temp)){
				$and_array[$value] =$temp;
				$select[]=$value;
			}
		}
	
		foreach($search as $key=>$value){
			//echo ($key." : ".$value."<br/>");
			if ($value!='0') $select[]=$key;
		}
		
	//check to see if 'markings' was passed - indicates user is searching by toric markings...
	//there's gotta be a better way to do this....
          if ($markings!="")$and_array[markings][0]=$markings;
  	  if ($markings2!="") $and_array[markings][1]=$markings2;

	 //die;
    // Data called by other modules. 
    extract($args);

	// add these fields to the select array.  Some may have already been present, so the weed out the items that are not unique.  Then, the array will be reduced to the top 9 items so there aren't too many fields displayed in the search results table

	$select=array_values(array_unique($select));

    // The API function is called.  The arguments to the function are passed in
    // as their own arguments array
    $items = pnModAPIFunc('Lenses',
                          'user',
                          'getlist',
                          array('search' => $search,
                            'or_array'=>$or_array,
      			            'and_array'=>$and_array,
      			            'and_or_array'=> $and_or_array,
      			            'select'=>$select));

//print_r ($items);//die;


	// if no items were found and the user was searching for a phrase, will do a full-text search for this phrase and hopefully find something 
    if (!$items && isset($phrase)){
			$display_phrase=" whose content includes: '$phrase'";
			$items= pnModAPIFunc('Lenses',
                          'user',
                          'getlist',
                          array('search' => $search,
	                  'phrase' => $phrase,
			  'select'=>$select));
		}
		
  //count the items.  
  $count = count($items);

  //If there is only one result (and the user has permission), go directly to that lens.
  if ($count == 1 && is_array($items) && pnSecAuthAction(0, 'Lenses::', '::', ACCESS_READ))pnRedirect(pnModURL('Lenses', 'user', 'display', array('tid' => $items[0]['tid'])));

  //just to be thorough, if the user is searching for a lens by a phrase, check to see if there's any company names that match that phrase.  This will be displayed with a statement saying something like "were you looking for lenses made by..."

           $company_options=pnModAPIFunc('Lenses',
                                         'user',
                                         'getcompany',
                                         array('phrase_array'=>$phrase_array));

   // The return value of the function is checked here, and if the function
    // suceeded then an appropriate message is posted.  
	//if (!$items && !$company_options) return pnVarPrepHTMLDisplay(_LENSESITEMFAILED);


	//if the user was searching by company, find the company name by looking at the first lens returned and capturing it's company name (this should work because ALL lenses returned should be manufactured by this company...
	if ($display_phrase=="company") $display_phrase=" that are manufactured by ".$items[0][comp_name];
    
    // Create output object
    $pnRender =& new pnRender('Lenses');

	//get the company data for the drop-down boxes for the search forms
	    $opt_companies = pnModAPIFunc('Lenses', 'user', 'getall', array('item_type'=>'companies'));
        $opt_polymers       = pnModAPIFunc('Lenses', 'user', 'getall', array('item_type'=>'polymers'));
        $pnRender->assign('opt_companies',$opt_companies);
        $pnRender->assign('opt_polymers',$opt_polymers);

        //count recently searched lenses (saved as an array of IDs in a session variable)
        $saved_lens_count = count(pnSessionGetVar('saved_lens_array'));
        $pnRender->assign('saved_lens_count',$saved_lens_count);
        
        $pnRender->assign('count',$count);
        $pnRender->assign('display_phrase',$display_phrase);
    	$pnRender->assign('search',$search);
    	$pnRender->assign('phrase',$phrase);
    	$pnRender->assign('wizard',$wizard);
    	$pnRender->assign('select',array_flip($select));
        $pnRender->assign('lenses',$items);
        $pnRender->assign('company_options',$company_options);

        


	
    // Return the output that has been generated by this function
    return $pnRender->fetch('lenses_user_view.htm');

}
function Lenses_user_display($args)
{
    //Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_READ)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

	// Clean $tid from input.
    $tid = pnVarCleanFromInput('tid');
	
	extract($args);


    // Ensure valid values were passed in.
    if (empty($tid) || !is_numeric($tid) ) {
        //echo 'TID: $tid<br />';
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
	}


    // Start a new output object.
    $pnRender =& new pnRender('Lenses');

    // Call API function to get all lens data.
    $lens_data = pnModAPIFunc('Lenses', 'user', 'get', array('item_type'=>'lens','item_id'=>$tid));

	//the image field will be a comma-separated string.  Explode it.  The first element will be placed into the "image1" field and the rest will be kept in the images field
	$lens_data[images] = explode(",",$lens_data[image]);

        //record lens ID as a session variable so it can be used to provide an option to compare recently searched lenses
    $saved_lens_array=array();
    $saved_lens_array = pnSessionGetVar('saved_lens_array');
    $saved_lens_array[$lens_data[name]]=$tid;
    pnSessionSetVar('saved_lens_array',array_unique($saved_lens_array));
    
    //count how many recently searched lenses are now saved as a session variable.
    $saved_lens_count = count($saved_lens_array);

	//create text for company popups:
	$lens_data['comp_info'] = pnModFunc('Lenses', 'user', 'company_popup', array('comp_id'=> $lens_data['comp_id']));

        //create popup text for FDA groups:
        $fda_desc = pnModAPIFunc('Lenses', 'user', 'fda_descriptions');
        $lens_data['fda_grp_desc'] = $fda_desc[$lens_data['fda_grp']];
        
        //if possible, create dk/t value
        if ($lens_data['dk']>0 && $lens_data['ct']>0) $lens_data['dkt']= $lens_data['dk'] / $lens_data['ct'] /10;

    // Let any hooks know that we are displaying an item.  As this is a display
    // hook we're passing a URL as the extra info, which is the URL that any
    // hooks will show after they have finished their own work.  It is normal
    // for that URL to bring the user back to this function
    $pnRender->assign('hooks' ,pnModCallHooks('item',
                                              'display',
                                              $tid,
                                              pnModURL('Lenses',
                                                       'user',
                                                       'display',
                                                       array('tid' => $tid))));
         
         
         
         //if user is allowed to edit, allow them to go to the edit page for the lens they're veiwing
	 if (pnSecAuthAction(0, 'Lenses::', '::', ACCESS_EDIT)) {
            $pnRender->assign('edit_lens',true);
        }

        //only enable those with comment access (users) to see wholesale prices
       if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_COMMENT)) {
          $lens_data['price']="";
    }

    // Assign $lenses to template.
    $pnRender->assign('lens_data', $lens_data);

    $pnRender->assign('saved_lens_count', $saved_lens_count);

        // return templated output.
    return $pnRender->fetch('lenses_user_display.htm');
}
   

function Lenses_user_compare_form()
{
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_OVERVIEW)) {
        return pnVarPrepHTMLDisplay(_NOTSUBSCRIBED);
    }

	$pnRender =& new pnRender('Lenses');
	
	//get the saved lens session variable
	$saved_lens_array=pnSessionGetVar('saved_lens_array');

	if(!isset($saved_lens_array)){
              $pnRender->assign('no_lenses', true);
          } else {
               $pnRender->assign('lenses', $saved_lens_array);
          }

    return $pnRender->fetch('lenses_user_compare_form.htm');
}

function Lenses_user_compare_view($args)
{
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_OVERVIEW)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

     $lens= pnVarCleanFromInput('lens');
     extract($args);

     //an array object that will be returned.  It will be two dimensional ... $array[field][tid]
     $field_array=array();

     $pnRender =& new pnRender('Lenses');

     foreach($lens as $tid=>$value){
          $temp_array = pnModAPIFunc('Lenses', 'user', 'get', array('item_type'=>'lens','item_id'=>$tid));
          foreach($temp_array as $field=>$property){
             $field_array[$field][$tid]=$property;
          }
     }
     $pnRender->assign('field_array', $field_array);
      //print_r($field_array);
      
    return $pnRender->fetch('lenses_user_compare_view.htm');
}


function Lenses_user_company_popup($args){
         
         $comp_id= $args['comp_id'];
         
         $comp_info = pnModAPIFunc('Lenses', 'user', 'get', array('item_type' => 'company','item_id' => $comp_id));

         //a return variable...
         $info="";

        if ($comp_info['logo']) $info.= "<img src=modules/Lenses/pnimages/comp_logos/".$comp_info['logo']."/><br />";
	if ($comp_info['address']) $info.=$comp_info['address']."<br/>".$comp_info['city'].", ".$comp_info['state'].", ".$comp_info['zip']."<br/>";
	if ($comp_info['phone'])$info.=$comp_info['phone']."<br/>";
	if ($comp_info['url'])$info.="Their <a href=".$comp_info['url']." target=_blank>website</a><br/>";
	if ($comp_info['desc'])$info.=$comp_info['desc'];
	return $info;

}




?>