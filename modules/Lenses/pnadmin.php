<?php

 // ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_admin_create_lens()
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Start a new output object.
    $pnRender =& new pnRender('Lenses');

    $opt_companies      = pnModAPIFunc('Lenses', 'user', 'getall', array('item_type'=>'companies'));
    $opt_polymers       = pnModAPIFunc('Lenses', 'user', 'getall', array('item_type'=>'polymers'));
    $opt_colors         = pnModAPIFunc('Lenses', 'user', 'opt_basic_colors');
    $opt_color_size     = count($opt_colors);
    $opt_axis_steps     = pnModAPIFunc('Lenses', 'user', 'opt_axis_steps');
    $opt_processes      = pnModAPIFunc('Lenses', 'user', 'opt_processes');
    $opt_torics         = pnModAPIFunc('Lenses', 'user', 'opt_toric_types');

    $pnRender->assign('opt_companies',      $opt_companies);
    $pnRender->assign('opt_polymers',       $opt_polymers);
    $pnRender->assign('opt_colors',         $opt_colors);
    $pnRender->assign('opt_color_size',     $opt_color_size);
    $pnRender->assign('opt_axis_steps',     $opt_axis_steps);
    $pnRender->assign('opt_processes',      $opt_processes);
    $pnRender->assign('opt_torics',         $opt_torics);

     	

    // Return templated output.
    return $pnRender->fetch('lenses_admin_create_lens.htm');
}

// ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_admin_insert_lens($args)
{
    // Clean input to this function.
    $lens_data = pnVarCleanFromInput('lens_data');
	$bc = pnVarCleanFromInput('bc');
	$enh_colors = pnVarCleanFromInput('enh_colors');
	$opaque_colors = pnVarCleanFromInput('opaque_colors');


    // Extract arguments.
    extract($args);

    // Confirm $authid hidden field from form template.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Lenses', 'admin', 'main'));
    }

	//take the arrays for the base curves and the simple opaque and enhancer colors
	//and create a string that's added to the appropriate parts of the $lens_data array
	$lens_data[bc_simple]=$bc[0]." ".$bc[1]." ".$bc[2];
	$lens_data[enh_names_simple]="";
	$lens_data[opaque_names_simple]="";
	
	foreach ($enh_colors as $value){
		$lens_data[enh_names_simple].=$value." ";
	}
	foreach ($opaque_colors as $value){
		$lens_data[opaque_names_simple].=$value." ";
	}


    // Run API function to insert lens in database.
    $tid = pnModAPIFunc('Lenses', 'admin', 'insert_lens', array('lens_data'=>$lens_data));

    // Check if we have a table id from the insert and set status message; success.
    if ($tid) {
        pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_CREATESUCCEDED));
        
        
    }

    // No output. Redirect user.
    //return pnRedirect(pnModURL('Lenses', 'admin', 'viewall_lenses'));
    return pnRedirect(pnModURL('Lenses', 'user', 'display', array('tid'=>$tid))) ;
}

 // ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_admin_modify_lens($args)
{
    // Clean $tid from input.
    $tid = pnVarCleanFromInput('tid');

    // Extract any extra arguments.
    extract($args);

    // Ensure valid values were passed in.
    if (empty($tid) || !is_numeric($tid)) {
        return pnVarPrepHTMLDisplay('Invalid lens id.');
    }

    // Run API function to get lens info.
    $lens_data = pnModAPIFunc('Lenses', 'user', 'get', array('item_id'=>$tid, 'item_type'=>'lens'));

    // Check if the lens exists.
    if (!is_array($lens_data)) {
        return pnVarPrepHTMLDisplay(_NOSUCHITEM);
    }

    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
	

    // Start a new output object.
    $pnRender =& new pnRender('Lenses');

    $opt_companies      = pnModAPIFunc('Lenses', 'user', 'getall', array('item_type'=>'companies'));
    $opt_polymers       = pnModAPIFunc('Lenses', 'user', 'getall', array('item_type'=>'polymers'));
    $opt_colors         = pnModAPIFunc('Lenses', 'user', 'opt_basic_colors');
   
    $opt_processes      = pnModAPIFunc('Lenses', 'user', 'opt_processes');
    $opt_torics         = pnModAPIFunc('Lenses', 'user', 'opt_toric_types');
	$opt_durations      = pnModAPIFunc('Lenses', 'user', 'opt_replacement_durations');
	$opt_quantities     = pnModAPIFunc('Lenses', 'user', 'opt_quantities');
	

	//break the bc_simple string into three elements to populate separate checkboxes in modify_lens.htm
	if (isset($lens_data[bc_simple]) && stristr($lens_data[bc_simple],"fla")) $bc[flat]=1;
	if (isset($lens_data[bc_simple]) && stristr($lens_data[bc_simple],"med")) $bc[med]=1;
	if (isset($lens_data[bc_simple]) && stristr($lens_data[bc_simple],"ste")) $bc[steep]=1;
	
	// break the enh_simple and opaque_simple colors string into arrays.  The colors in these arrays will be selected in the modify_lens.htm form
	$opaque_selected=explode(" ",trim($lens_data[opaque_names_simple]));
	$enh_selected=explode(" ",trim($lens_data[enh_names_simple]));

	//now, find out what colors should NOT be selected for the enh and opaque colors in the modify_lens.htm form by subtracting the above arrays from the possible colors
	$opaque_not_selected=array_diff($opt_colors, $opaque_selected);
	$enh_not_selected=array_diff($opt_colors, $enh_selected);

	
	$pnRender->assign('opaque_selected',    	$opaque_selected);
    $pnRender->assign('enh_selected',       	$enh_selected);
	$pnRender->assign('opaque_not_selected',    $opaque_not_selected);
    $pnRender->assign('enh_not_selected',       $enh_not_selected);
	
	$pnRender->assign('bc',      			$bc);
	
    $pnRender->assign('opt_companies',      $opt_companies);
    $pnRender->assign('opt_polymers',       $opt_polymers);
    $pnRender->assign('opt_processes',      $opt_processes);
	$pnRender->assign('opt_durations',      $opt_durations);
	$pnRender->assign('opt_quantities',     $opt_quantities);


    // Assign template variable.
    $pnRender->assign('tid', $tid);

    // Assign template variable.
    $pnRender->assign('lens_data', $lens_data);

    // Return templated output.
    return $pnRender->fetch('lenses_admin_modify_lens.htm');
}

// ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_admin_update_lens($args)
{
    // Clean input from the form.
    $lens_data = pnVarCleanFromInput('lens_data');
	$bc = pnVarCleanFromInput('bc');
	$enh_colors = pnVarCleanFromInput('enh_colors');
	$opaque_colors = pnVarCleanFromInput('opaque_colors');
	
    // Extract any extra arguments.
    extract($args);

    // Confirm $authid hidden field from form template.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Lenses', 'admin', 'main'));
    }
	
	//take the arrays for the base curves and the simple opaque and enhancer colors
	//and create a string that's added to the appropriate parts of the $lens_data array
	$lens_data[bc_simple]=$bc[0]." ".$bc[1]." ".$bc[2];
	$lens_data[enh_names_simple]="";
	$lens_data[opaque_names_simple]="";
	
	foreach ($enh_colors as $value){
		$lens_data[enh_names_simple].=$value." ";
	}
	foreach ($opaque_colors as $value){
		$lens_data[opaque_names_simple].=$value." ";
	}

    // Attempt to update lens.
    if(pnModAPIFunc('Lenses', 'admin', 'update_lens', array('lens_data' => $lens_data))) {
        pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_UPDATESUCCEDED));
    }

    // No output.  Redirect user.
    return pnRedirect(pnModURL('Lenses', 'user', 'display', array('tid'=>$lens_data[tid])));
}

// ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_admin_viewall_lenses()
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Start a new output object.
    $pnRender =& new pnRender('Lenses');



    // Call API function to get all lenses.
    $lenses_data = pnModAPIFunc('Lenses', 'user', 'getall', array('item_type'=>'lenses'));

//
    // Assign $lenses to template.
    $pnRender->assign('lenses_data', $lenses_data);
//print_r($lenses_data[419]);
//print "hi there";die;

    // Return templated output.
    return $pnRender->fetch('lenses_admin_viewall_lenses.htm');
}







// ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_admin_create_company()
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Start a new output object.
    $pnRender =& new pnRender('Lenses');

    // Return templated output.
    return $pnRender->fetch('lenses_admin_create_company.htm');
}

// ------------------------------------------------------------
//
// ------------------------------------------------------------

function Lenses_admin_insert_company($args)
{
    // Clean input to this function.
    $company = pnVarCleanFromInput('company');

    // Extract arguments.
    //extract($args);

    // Confirm $authid hidden field from form template.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Lenses', 'admin', 'main'));
    }

    // Run API function to insert company in database.
    $comp_tid = pnModAPIFunc('Lenses', 'admin', 'insert_company', array('company' => $company));

    // Check if we have a table id from the insert and set status message; success.
    if ($comp_tid) {
        pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_CREATESUCCEDED));
    }

    // No output. Redirect user.
    return pnRedirect(pnModURL('Lenses', 'admin', 'viewall_companies'));
}

function Lenses_admin_main()
{
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $pnRender =& new pnRender('Lenses');

    return $pnRender->fetch('lenses_admin.htm');
}



// ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_admin_delete($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Clean $tid from input.
    list($tid, $item_type, $confirmation) = pnVarCleanFromInput('tid', 'item_type', 'confirmation');

    // Extract any extra arguments.
    extract($args);

    // Ensure valid values were passed in.
    if (empty($tid) || !is_numeric($tid) ||
        empty($item_type) || !is_string($item_type)) {
        return pnVarPrepHTMLDisplay('Invalid id or item type.');
    }

    // Check if confirmation is empty.  If so, start up a new
    // output object and show the confirmation template.
    if (empty($confirmation)) {
        $pnRender =& new pnRender('Lenses');
        $pnRender->assign('tid', $tid);
        $pnRender->assign('item_type', $item_type);
        return $pnRender->fetch('lenses_admin_delete.htm');
    }
    
    // Confirm $authid hidden field from form template.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Lenses', 'admin', 'main'));
    }

    // Attempt to delete item.
    if (pnModAPIFunc('Lenses', 'admin', 'delete', array('tid' => $tid, 'item_type' => $item_type))) {
        pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_DELETESUCCEDED));
    }

    // No output.  Redirect user.
    return pnRedirect(pnModURL('Lenses', 'admin', 'main'));
}

// ------------------------------------------------------------
//
// ------------------------------------------------------------


  // ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_admin_modify_company($args)
{
    // Clean $tid from input.
    $comp_tid = pnVarCleanFromInput('comp_tid');

    // Extract any extra arguments.
    extract($args);

    // Ensure valid values were passed in.
    if (empty($comp_tid) || !is_numeric($comp_tid)) {
        return pnVarPrepHTMLDisplay('Invalid company id.');
    }

    // Run API function to get company info.
    $company = pnModAPIFunc('Lenses', 'user', 'get', array('item_id' => $comp_tid, 'item_type' => 'company'));

    // Check if the company exists.
    if (!is_array($company)) {
        return pnVarPrepHTMLDisplay(_NOSUCHITEM);
    }

    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Start a new output object.
    $pnRender =& new pnRender('Lenses');

    // Assign template variable.
    $pnRender->assign('comp_tid', $comp_tid);

    // Assign template variable.
    $pnRender->assign('company', $company);

    // Return templated output.
    return $pnRender->fetch('lenses_admin_modify_company.htm');
}

// ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_admin_update_company($args)
{
    // Clean input from the form.
    $company = pnVarCleanFromInput('company');

    // Extract any extra arguments.
    extract($args);

    // Confirm $authid hidden field from form template.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Lenses', 'admin', 'main'));
    }

    // Attempt to update company.
    if(pnModAPIFunc('Lenses', 'admin', 'update_company', array('company' => $company))) {
        pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_UPDATESUCCEDED));
    }

    // No output.  Redirect user.
    return pnRedirect(pnModURL('Lenses', 'admin', 'viewall_companies'));
}


// ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_admin_viewall_companies()
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Start a new output object.
    $pnRender =& new pnRender('Lenses');

    // Call API function to get all companies.
    $companies = pnModAPIFunc('Lenses', 'user', 'getall', array('item_type' => 'companies'));

    // Assign $companies to template.
    $pnRender->assign('companies', $companies);

    // Return templated output.
    return $pnRender->fetch('lenses_admin_viewall_companies.htm');
}

// ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_admin_create_polymer()
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Start a new output object.
    $pnRender =& new pnRender('Lenses');

    // Get FDA group options for dropdown.
    $opt_fda_grps = pnModAPIFunc('Lenses', 'user', 'opt_fda_groups');

    // Assign template variables.
    $pnRender->assign('opt_fda_grps', $opt_fda_grps);

    // Return templated output.
    return $pnRender->fetch('lenses_admin_create_polymer.htm');
}

// ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_admin_insert_polymer($args)
{
    // Clean input to this function.
    $polymer = pnVarCleanFromInput('polymer');

    // Extract arguments.
    extract($args);

    // Confirm $authid hidden field from form template.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Lenses', 'admin', 'main'));
    }

    // Run API function to insert polymer in database.
    $poly_tid = pnModAPIFunc('Lenses', 'admin', 'insert_polymer', array('polymer' => $polymer));

    // Check if we have a table id from the insert and set status message; success.
    if ($poly_tid) {
        pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_CREATESUCCEDED));
    }

    // No output. Redirect user.
    return pnRedirect(pnModURL('Lenses', 'admin', 'viewall_polymers'));
}

// ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_admin_modify_polymer($args)
{
    // Clean $tid from input.
    $poly_tid = pnVarCleanFromInput('poly_tid');

    // Extract any extra arguments.
    extract($args);

    // Ensure valid values were passed in.
    if (empty($poly_tid) || !is_numeric($poly_tid)) {
        return pnVarPrepHTMLDisplay('Invalid polymer id.');
    }

    // Run API function to get polymer info.
    $polymer = pnModAPIFunc('Lenses', 'user', 'get', array('item_id' => $poly_tid, 'item_type' => 'polymer'));

    // Check if the polymer exists.
    if (!is_array($polymer)) {
        return pnVarPrepHTMLDisplay(_NOSUCHITEM);
    }

    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Get FDA group options for dropdown.
    $opt_fda_grps = pnModAPIFunc('Lenses', 'user', 'opt_fda_groups');

    // Start a new output object.
    $pnRender =& new pnRender('Lenses');

    // Assign template variable.
    $pnRender->assign('opt_fda_grps', $opt_fda_grps);

    // Assign template variable.
    $pnRender->assign('poly_tid', $poly_tid);

    // Assign template variable.
    $pnRender->assign('polymer', $polymer);

    // Return templated output.
    return $pnRender->fetch('lenses_admin_modify_polymer.htm');
}

// ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_admin_update_polymer($args)
{
    // Clean input from the form.
    $polymer = pnVarCleanFromInput('polymer');

    // Extract any extra arguments.
    extract($args);

    // Confirm $authid hidden field from form template.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Lenses', 'admin', 'main'));
    }

    // Attempt to update polymer.
    if(pnModAPIFunc('Lenses', 'admin', 'update_polymer', array('polymer' => $polymer))) {
        pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_UPDATESUCCEDED));
    }

    // No output.  Redirect user.
    return pnRedirect(pnModURL('Lenses', 'admin', 'viewall_polymers'));
}


// ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_admin_viewall_polymers()
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Start a new output object.
    $pnRender =& new pnRender('Lenses');

    // Call API function to get all polymers.
    $polymers = pnModAPIFunc('Lenses', 'user', 'getall', array('item_type' => 'polymers'));

    // Assign $polymers to template.
    $pnRender->assign('polymers', $polymers);

    // Return templated output.
    return $pnRender->fetch('lenses_admin_viewall_polymers.htm');
}

// ------------------------------------------------------------
//
// ------------------------------------------------------------


function Lenses_admin_report_company($args){
         
    // Clean $tid from input.
    $comp_tid = pnVarCleanFromInput('comp_tid');

    // Extract any extra arguments.
    extract($args);

    

    // Ensure valid values were passed in.
    if (empty($comp_tid) || !is_numeric($comp_tid)) {
        return pnVarPrepHTMLDisplay('Invalid company id.');
    }

    // Run API function to get company info.
    $company = pnModAPIFunc('Lenses', 'user', 'get', array('item_id' => $comp_tid, 'item_type' => 'company'));
     

    // Check if the company exists.
    if (!is_array($company)) {
        return pnVarPrepHTMLDisplay(_NOSUCHITEM);
    }
     
     //print_r($company);

   //next, get all the information about all the lenses made by this company
   // Run API function to get lens info.
    $lens_data = pnModAPIFunc('Lenses', 'admin', 'report_company', array('comp_tid' => $comp_tid));

    // Start a new output object.
    $pnRender =& new pnRender('Lenses');

     // Assign $lenses to template.
    $pnRender->assign('lenses', $lens_data);

    $pnRender->assign('company', $company);

        // return templated output.
    return $pnRender->fetch('lenses_admin_report_company.htm');

}

function Lenses_admin_zero_report($args)
{

    // Clean input from the form.
    $time = pnVarCleanFromInput('time');

    // Extract any extra arguments.
    extract($args);

    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Start a new output object.
    $pnRender =& new pnRender('Lenses');



    // Call API function to get all lenses.
    $lenses_data = pnModAPIFunc('Lenses', 'user', 'zero_report', array('time' => $time));

//print $time;
//
    // Assign $lenses to template.
    $pnRender->assign('lenses_data', $lenses_data);
	 $pnRender->assign('time', $time);
//print_r($lenses_data);
//print "hi there";die;

    // Return templated output.
    return $pnRender->fetch('lenses_admin_zero_report.htm');
}

function Lenses_admin_search_report($args)
{

    // Clean input from the form.
    $time = pnVarCleanFromInput('time');

    // Extract any extra arguments.
    extract($args);

    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Start a new output object.
    $pnRender =& new pnRender('Lenses');



    // Call API function to get all lenses.
    $lenses_data = pnModAPIFunc('Lenses', 'user', 'search_report', array('time' => $time));

//
    // Assign $lenses to template.
    $pnRender->assign('lenses_data', $lenses_data);
	 $pnRender->assign('time', $time);


    // Return templated output.
    return $pnRender->fetch('lenses_admin_search_report.htm');
}
?>
