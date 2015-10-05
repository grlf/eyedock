<?php

/**
 * Main admin function - shows only the module's admin menu.
 */
function Meds_admin_main()
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Start a new output object.
    $pnRender =& new pnRender('Meds');

    // Return templated output.
    return $pnRender->fetch('meds_admin_main.htm');
}


// --------------------------------------------------
// FUNCTIONS FOR CHEMICALS
// --------------------------------------------------
// Create chemical - template.
function Meds_admin_create_chem()
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_ADD)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Get options for MOA dropdown.
    $moas = pnModAPIFunc('Meds', 'user', 'DBselect', array('from'=>'moa'));
    
    // Start a new output object.
    $pnRender =& new pnRender('Meds');

    // Any data cleaned from input.
    $pnRender->assign('chem', pnVarCleanFromInput('chem'));
    
    // Extract/assign dropdown options.
    $pnRender->assign('moas', $moas);
    
    // Return templated output.
    return $pnRender->fetch('meds_admin_create_chem.htm');
}
// Insert chemical - utility.
function Meds_admin_insert_chem($args)
{
    // Clean new medication from input.
    $chem = pnVarCleanFromInput('chem');

    // Confirm authorizaton to carry out this function's action.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Meds', 'admin', 'main'));
    }

    // Call API function to insert.
    $chem_id = pnModAPIFunc('Meds', 'admin', 'insert_chem', array('chem'=>$chem));

    // Check if create succeeded.
    if ($chem_id) {
        pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_CREATESUCCEDED));
    } else { 
        // Re-populate the creation form to allow a retry.
        pnSessionSetVar('statusmsg', 'A problem was detected with your entry.  Please try again.');
        $moas = pnModAPIFunc('Meds', 'user', 'DBselect', array('from'=>'moa'));
        $pnRender =& new pnRender('Meds');
        $pnRender->assign('moas', $moas);
        $pnRender->assign('chem', $chem);
        return $pnRender->fetch('meds_admin_create_chem.htm');
    }
    
    // Success or failure, redirect user.
    return pnRedirect(pnModURL('Meds', 'admin', 'main'));
}
// Modify chemical - template.
function Meds_admin_modify_chem($args)
{
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $chem_id = pnVarCleanFromInput('chem_id');

    $chem = pnModAPIFunc('Meds', 'user', 'get', array('object'=>'chem','chem_id'=>$chem_id));

    if (!$chem) {
        return pnVarPrepHTMLDisplay(_NOSUCHITEM);
    }

    $moas = pnModAPIFunc('Meds', 'user', 'DBselect', array('from'=>'moa'));
    
    // Start a new output object.
    $pnRender =& new pnRender('Meds');

    // Manage caching for this function.
    $pnRender->caching = false;

    // Extract/assign dropdown options.
    $pnRender->assign('moas', $moas);
    
    // Assign template variable.
    $pnRender->assign('chem_id', $chem_id);

    // Assign template variable.
    $pnRender->assign('chem', $chem);

    // Return templated output.
    return $pnRender->fetch('meds_admin_modify_chem.htm');
}
// Update chemical - utility.
function Meds_admin_update_chem($args)
{
    // Clean new medication from input.
    $chem = pnVarCleanFromInput('chem');

    // Confirm authorizaton to carry out this function's action.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Meds', 'admin', 'main'));
    }

    // Call API function to insert medication.
    $updated = pnModAPIFunc('Meds', 'admin', 'update_chem', array('chem'=>$chem));

    // Check if create succeeded.
    if ($updated) {
        pnSessionSetVar('statusmsg', 'Update Successful');
    } else { 
        // Re-populate the modification form to allow a retry.
        pnSessionSetVar('statusmsg', 'A problem was detected with your entry.  Please try again.');
        $moas = pnModAPIFunc('Meds', 'user', 'DBselect', array('from'=>'moa'));
        $pnRender =& new pnRender('Meds');
        $pnRender->assign('moas', $moas);
        $pnRender->assign('chem', $chem);
        return $pnRender->fetch('meds_admin_modify_chem.htm');
    }

    // Success or failure, redirect user.
    return pnRedirect(pnModURL('Meds', 'admin', 'main'));
}


// --------------------------------------------------
// FUNCTIONS FOR PRESERVATIVES
// --------------------------------------------------
// Create preservative - template.
function Meds_admin_create_preserve()
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_ADD)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Start a new output object.
    $pnRender =& new pnRender('Meds');

    // Any data cleaned from input.
    $pnRender->assign('preserve', pnVarCleanFromInput('preserve'));
    
    // Return templated output.
    return $pnRender->fetch('meds_admin_create_preserve.htm');
}
// Insert preservative - utility.
function Meds_admin_insert_preserve($args)
{
    // Clean new medication from input.
    $preserve = pnVarCleanFromInput('preserve');

    // Confirm authorizaton to carry out this function's action.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Meds', 'admin', 'main'));
    }

    // Call API function to insert.
    $pres_id = pnModAPIFunc('Meds', 'admin', 'insert_preserve', array('preserve'=>$preserve));

    // Check if create succeeded.
    if ($pres_id) {
        pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_CREATESUCCEDED));
    } else {
        // Re-populate the creation form to allow a retry.
        pnSessionSetVar('statusmsg', 'A problem was detected with your entry.  Please try again.');
        $pnRender =& new pnRender('Meds');
        $pnRender->assign('preserve', pnVarCleanFromInput('preserve'));
        return $pnRender->fetch('meds_admin_create_preserve.htm');
    }

    // Success or failure, redirect user.
    return pnRedirect(pnModURL('Meds', 'admin', 'main'));
}
// Modify preservative - template.
function Meds_admin_modify_preserve($args)
{
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $pres_id = pnVarCleanFromInput('pres_id');

    $preserve = pnModAPIFunc('Meds', 'user', 'get', array('object'=>'preserve','pres_id'=>$pres_id));

    if (!$preserve) {
        return pnVarPrepHTMLDisplay(_NOSUCHITEM);
    }

    // Start a new output object.
    $pnRender =& new pnRender('Meds');

    // Manage caching for this function.
    $pnRender->caching = false;

    // Assign template variable.
    $pnRender->assign('pres_id', $pres_id);

    // Assign template variable.
    $pnRender->assign('preserve', $preserve);

    // Return templated output.
    return $pnRender->fetch('meds_admin_modify_preserve.htm');
}
// Update preservative - utility.
function Meds_admin_update_preserve($args)
{
    // Clean new medication from input.
    $preserve = pnVarCleanFromInput('preserve');

    // Confirm authorizaton to carry out this function's action.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Meds', 'admin', 'main'));
    }

    // Call API function to insert medication.
    $updated = pnModAPIFunc('Meds', 'admin', 'update_preserve', array('preserve'=>$preserve));

    // Check if create succeeded.
    if ($updated) {
        pnSessionSetVar('statusmsg', 'Update Successful');
    } else {
        // Re-populate the modification form to allow a retry.
        pnSessionSetVar('statusmsg', 'A problem was detected with your entry.  Please try again.');
        $pnRender =& new pnRender('Meds');
        $pnRender->assign('preserve', $preserve);
        return $pnRender->fetch('meds_admin_modify_preserve.htm');
    }

    // Success or failure, redirect user.
    return pnRedirect(pnModURL('Meds', 'admin', 'main'));
}


// --------------------------------------------------
// FUNCTIONS FOR METHODS OF ACTION (MOAs)
// --------------------------------------------------
// Create MOA - template.
function Meds_admin_create_moa()
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_ADD)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Start a new output object.
    $pnRender =& new pnRender('Meds');

    // Any data cleaned from input.
    $pnRender->assign('moa', pnVarCleanFromInput('moa'));
    
    // Return templated output.
    return $pnRender->fetch('meds_admin_create_moa.htm');
}
// Insert MOA - utility.
function Meds_admin_insert_moa($args)
{
    // Clean new medication from input.
    $moa = pnVarCleanFromInput('moa');

    // Confirm authorizaton to carry out this function's action.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Meds', 'admin', 'main'));
    }

    // Call API function to insert.
    $moa_id = pnModAPIFunc('Meds', 'admin', 'insert_moa', array('moa'=>$moa));

    // Check if create succeeded.
    if ($moa_id) {
        pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_CREATESUCCEDED));
    } else {
        // Re-populate the creation form to allow a retry.
        pnSessionSetVar('statusmsg', 'A problem was detected with your entry.  Please try again.');
        $pnRender =& new pnRender('Meds');
        $pnRender->assign('moa', pnVarCleanFromInput('moa'));
        return $pnRender->fetch('meds_admin_create_moa.htm');
    }

    // Success or failure, redirect user.
    return pnRedirect(pnModURL('Meds', 'admin', 'main'));
}
// Modify MOA - template.
function Meds_admin_modify_moa($args)
{
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $moa_id = pnVarCleanFromInput('moa_id');

    $moa = pnModAPIFunc('Meds', 'user', 'get', array('object'=>'moa','moa_id'=>$moa_id));

    if (!$moa) {
        return pnVarPrepHTMLDisplay(_NOSUCHITEM);
    }

    // Start a new output object.
    $pnRender =& new pnRender('Meds');

    // Manage caching for this function.
    $pnRender->caching = false;

    // Assign template variable.
    $pnRender->assign('moa_id', $moa_id);

    // Assign template variable.
    $pnRender->assign('moa', $moa);

    // Return templated output.
    return $pnRender->fetch('meds_admin_modify_moa.htm');
}
// Updated MOA - utility.
function Meds_admin_update_moa($args)
{
    // Clean new medication from input.
    $moa = pnVarCleanFromInput('moa');

    // Confirm authorizaton to carry out this function's action.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Meds', 'admin', 'main'));
    }

    // Call API function to insert medication.
    $updated = pnModAPIFunc('Meds', 'admin', 'update_moa', array('moa'=>$moa));

    // Check if create succeeded.
    if ($updated) {
        pnSessionSetVar('statusmsg', 'Update Successful');
    } else {
        // Re-populate the modification form to allow a retry.
        pnSessionSetVar('statusmsg', 'A problem was detected with your entry.  Please try again.');
        $pnRender =& new pnRender('Meds');
        $pnRender->assign('moa', $moa);
        return $pnRender->fetch('meds_admin_modify_moa.htm');
    }

    // Success or failure, redirect user.
    return pnRedirect(pnModURL('Meds', 'admin', 'main'));
}


// --------------------------------------------------
// FUNCTIONS FOR COMPANIES
// --------------------------------------------------
// Create company - template.
function Meds_admin_create_company()
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_ADD)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Start a new output object.
    $pnRender =& new pnRender('Meds');

    // Any data cleaned from input.
    $pnRender->assign('company', pnVarCleanFromInput('company'));
    
    // Return templated output.
    return $pnRender->fetch('meds_admin_create_company.htm');
}
// Insert company - utility.
function Meds_admin_insert_company($args)
{
    // Clean new medication from input.
    $company = pnVarCleanFromInput('company');

    // Confirm authorizaton to carry out this function's action.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Meds', 'admin', 'main'));
    }

    // Call API function to insert.
    $comp_id = pnModAPIFunc('Meds', 'admin', 'insert_company', array('company'=>$company));

    // Check if create succeeded.
    if ($comp_id) {
        pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_CREATESUCCEDED));
    } else {
        // Re-populate the creation form to allow a retry.
        pnSessionSetVar('statusmsg', 'A problem was detected with your entry.  Please try again.');
        $pnRender =& new pnRender('Meds');
        $pnRender->assign('company', pnVarCleanFromInput('company'));
        return $pnRender->fetch('meds_admin_create_company.htm');
    }

    // Success or failure, redirect user.
    return pnRedirect(pnModURL('Meds', 'admin', 'main'));
}
// Modify company - template.
function Meds_admin_modify_company($args)
{
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $comp_id = pnVarCleanFromInput('comp_id');

    $company = pnModAPIFunc('Meds', 'user', 'get', array('object'=>'company','comp_id'=>$comp_id));

    if (!$company) {
        return pnVarPrepHTMLDisplay(_NOSUCHITEM);
    }

    // Start a new output object.
    $pnRender =& new pnRender('Meds');

    // Manage caching for this function.
    $pnRender->caching = false;

    // Assign template variable.
    $pnRender->assign('comp_id', $comp_id);

    // Assign template variable.
    $pnRender->assign('company', $company);

    // Return templated output.
    return $pnRender->fetch('meds_admin_modify_company.htm');
}
// Update company - utility.
function Meds_admin_update_company($args)
{
    // Clean new medication from input.
    $company = pnVarCleanFromInput('company');

    // Confirm authorizaton to carry out this function's action.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Meds', 'admin', 'main'));
    }

    // Call API function to insert medication.
    $updated = pnModAPIFunc('Meds', 'admin', 'update_company', array('company'=>$company));

    // Check if create succeeded.
    if ($updated) {
        pnSessionSetVar('statusmsg', 'Update Successful');
    } else {
        // Re-populate the modification form to allow a retry.
        pnSessionSetVar('statusmsg', 'A problem was detected with your entry.  Please try again.');
        $pnRender =& new pnRender('Meds');
        $pnRender->assign('company', $company);
        return $pnRender->fetch('meds_admin_modify_company.htm');
    }

    // Success or failure, redirect user.
    return pnRedirect(pnModURL('Meds', 'admin', 'main'));
}


// --------------------------------------------------
// FUNCTIONS FOR MEDS
// --------------------------------------------------
// Create med - template.
function Meds_admin_create_med()
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_ADD)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Get options for all dropdowns.
    $selects = pnModAPIFunc('Meds', 'user', 'getall_selects');
    
    // Start a new output object.
    $pnRender =& new pnRender('Meds');

    // Any data cleaned from input.
    $pnRender->assign('med', pnVarCleanFromInput('med'));
    
    
    
    // Extract/assign dropdown options.
    $pnRender->assign($selects);
    
    // Return templated output.
    return $pnRender->fetch('meds_admin_create_med.htm');
}
// Insert med - utility.
function Meds_admin_insert_med($args)
{
    // Clean new medication from input.
    $med = pnVarCleanFromInput('med');

    // Confirm authorizaton to carry out this function's action.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Meds', 'admin', 'main'));
    }

    // Call API function to insert medication.
    $med_id = pnModAPIFunc('Meds', 'admin', 'insert_med', array('med'=>$med));

    // Check if create succeeded.
    if ($med_id) {
        pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_CREATESUCCEDED));
    } else { 
        // Re-populate the creation form to allow a retry.
        pnSessionSetVar('statusmsg', 'A problem was detected with your entry.  Please try again.');
        $selects = pnModAPIFunc('Meds', 'user', 'getall_selects');
        $pnRender =& new pnRender('Meds');
        $pnRender->assign('med', $med);
        $pnRender->assign($selects);
        return $pnRender->fetch('meds_admin_create_med.htm');
    }

    // Success or failure, redirect user.
    return pnRedirect(pnModURL('Meds', 'admin', 'main'));
}
// Modify med.
function Meds_admin_modify_med($args)
{
    // Clean arguments from URL.
    $med_id = pnVarCleanFromInput('med_id');

    $med = pnModAPIFunc('Meds', 'user', 'get', array('object'=>'med','med_id'=>$med_id));

    if (!$med) {
        return pnVarPrepHTMLDisplay(_NOSUCHITEM);
    }

    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Get options for all dropdowns.
    $selects = pnModAPIFunc('Meds', 'user', 'getall_selects');
    
    // Start a new output object.
    $pnRender =& new pnRender('Meds');

    // Manage caching for this function.
    $pnRender->caching = false;

    // Extract/assign dropdown options.
    $pnRender->assign($selects);
    
    // Assign template variable.
    $pnRender->assign('med_id', $med_id);

    // Assign template variable.
    $pnRender->assign('med', $med);

    // Return templated output.
    return $pnRender->fetch('meds_admin_modify_med.htm');
}
// Update med - utility.
function Meds_admin_update_med($args)
{
    // Clean new medication from input.
    $med = pnVarCleanFromInput('med');

    // Confirm authorizaton to carry out this function's action.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Meds', 'admin', 'main'));
    }

    // Call API function to insert medication.
    $updated = pnModAPIFunc('Meds', 'admin', 'update_med', array('med'=>$med));

    // Check if modification succeeded.
    if (!$updated) {
        // Re-populate the modification form to allow a retry.
        $selects = pnModAPIFunc('Meds', 'user', 'getall_selects');
        $pnRender =& new pnRender('Meds');
        $pnRender->assign('med_id', $med['med_id']);
        $pnRender->assign('med', $med);
        $pnRender->assign($selects);
        pnRedirect(pnModURL('Meds', 'admin', 'modify_med', array('med_id'=>$med['med_id'])));
        return $pnRender->fetch('meds_admin_modify_med.htm');
    }

    // Success or failure, redirect user.
    return pnRedirect(pnModURL('Meds', 'admin', 'main'));
}


/** 
 * View all of any given $object.
 * 
 * @param   $object STRING  required    table to select from
 * @param   $start  INT     optional    where to start selecting
 * 
 * @return  templated output overviewing the given items retrieved.
 * 
 */
function Meds_admin_viewall($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_READ)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Get the object type and start number.
    list($object, $start) = pnVarCleanFromInput('object', 'start');

    // In case no object type was passed in.
    if (empty($object)) {
        $object = 'med';
    }
    
    // Get default result limit.
    $limit = pnModGetVar('Meds', 'per_page');
    
    // Get all items, based on specs and limits passed.
    $items = pnModAPIFunc('Meds', 'user', 'getall', array('object' => $object,
                                                          'start'  => $start,
                                                          'limit'  => $limit,
                                                          ));
    // Total $object items.
    $total = count(pnModAPIFunc('Meds', 'user', 'getall', array('count'=>$object)));
     
    // Start a new output object.
    $pnRender =& new pnRender('Meds');
    
    // Manage caching for this function.
    $pnRender->caching = false;

    // Total items in table.
    $selects = pnModAPIFunc('Meds', 'user', 'getall_selects');
     
    // Assign template variable.
    $pnRender->assign($selects);
    
    // Assign template variable.
    $pnRender->assign('items', $items);
    
    // Assign pager variable.
    $pnRender->assign('pager', array('total'=>$total, 'limit'=>$limit));

    // Assign flag for admin permission capacity.
    $pnRender->assign('is_admin', pnSecAuthAction(0, 'Meds::', '::', ACCESS_ADMIN));
    
    // Can merely add an "s" for the template name, except with "companIEs".
    $template = ($object != 'company') ? $object.'s' : 'companies';
    
    // Return templated output.
    return $pnRender->fetch('meds_admin_viewall_'.$template.'.htm');
}

/** 
 * Initiate a delete action on an item in the database - template/utilty in one.
 * 
 * @param   $object STRING  required    table to delete from
 * @param   $id     INT     required    id to delete
 * @param $confirmation INT required    confirms deletion action
 * 
 * @return  confirmation screen for deleting items.
 * 
 */
function Meds_admin_delete($args)
{
    list($id,
         $object,
         $confirmation) = pnVarCleanFromInput('id',
                                              'object',
                                              'confirmation');

    extract($args);
    
    if ($object == 'med')       { $id_field = 'med_id'; }
    if ($object == 'moa')       { $id_field = 'moa_id'; }
    if ($object == 'company')   { $id_field = 'comp_id'; }
    if ($object == 'preserve')  { $id_field = 'pres_id'; }
    if ($object == 'chem')      { $id_field = 'chem_id'; }
    
//    echo '<em>'.$object . '<br />';
//    echo $id . '<br />';
//    echo $id_field . '<br /></em>';
    
    $exists = pnModAPIFunc('Meds', 'user', 'get', array('object'=>$object, $id_field=>$id));

    if (!$exists) {
        return pnVarPrepHTMLDisplay(_NOSUCHITEM);
    }

    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_DELETE)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    if (empty($confirmation)) {
        $pnRender =& new pnRender('Meds');
        $pnRender->caching = false;
        $pnRender->assign('object', $object);
        $pnRender->assign('id', $id);
        return $pnRender->fetch('meds_admin_delete.htm');
    }

    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Meds', 'admin', 'main'));
    }

    $deleted = pnModAPIFunc('Meds', 'admin', 'delete', array('object'=>$object, $id_field=>$id));
    
    if ($deleted) {
        pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_DELETESUCCEDED));
    }

    return pnRedirect(pnModURL('Meds', 'admin', 'main'));
}

/**
 * Modify module config - currently only "max meds per page"
 */
function Meds_admin_modify_config()
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Start a new output object.
    $pnRender =& new pnRender('Meds');
    
    // Manage caching for this function.
    $pnRender->caching = false;

    // Assign template variable.
    $pnRender->assign('per_page', pnModGetVar('Meds', 'per_page'));

    // Return templated output.
    return $pnRender->fetch('meds_admin_modify_config.htm');
}

/**
 * Update module config.
 */
function Meds_admin_update_config()
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Clean arguments from URL.
    $per_page = pnVarCleanFromInput('per_page');

    // Confirm authorizaton to carry out this function's action.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Meds', 'admin', 'main'));
    }

    // Ensure a default.
    if (empty($per_page) || !is_numeric($per_page) || $per_page < 1) {
        $per_page = 10;
    }

    // Set the module variable.
    pnModSetVar('Meds', 'per_page', (int)$per_page);

    // Start a new output object.
    $pnRender =& new pnRender('Meds');

    // Dump module cache.
    $pnRender->clear_cache();

    // Set a status message.
    pnSessionSetVar('statusmsg', _CONFIGUPDATED);

    // Let any hooks know that something occurred.
    pnModCallHooks('module','updateconfig', 'Meds', array('module' => 'Meds'));

    // Redirect user.
    return pnRedirect(pnModURL('Meds', 'admin', 'main'));
}

?>
