<?php

    // Functions in this file interact directly with
    // the database in admin capacities.  They are
    // used for inserting, updating and deleting
    // items from the database.
    
    // The delete function is general enough to 
    // accept $object and $id parameters and
    // then make a proper deletion.  Therefore,
    // there is only one deletion function for
    // the entire module. Many of the functions 
    // could be re-written in this way.
    
// Insert chemical - adminapi.
function Meds_adminapi_insert_chem($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_ADD)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Get argument to this function.
    $chem = $args['chem'];

    if (empty($chem['name'])) {
        pnSessionSetVar('errormsg', _MEDS_ADM_TRY_AGAIN);
        return false;
    }
    
    $name   = (string)$chem['name'];
    $moa_id =    (int)$chem['moa_id'];
        
    // Get database connection and tables references.
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Assign table/columns used in this function.
    $table =& $pntable['rx_chem'];
    $field =& $pntable['rx_chem_column'];

    $next_id = $dbconn->GenId($table);

    list($next_id, $name, $moa_id) = pnVarPrepForStore($next_id, $name, $moa_id);

    // SQL to insert item.
    $sql = "INSERT INTO $table (
                        $field[chem_id],
                        $field[name],
                        $field[moa_id])
                VALUES (
                        '$next_id',
                        '$name',
                        '$moa_id')";
    // Execute query.
    $dbconn->Execute($sql);

    // Check for database error.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _CREATEFAILED);
        return false;
    }

    $chem_id = $dbconn->PO_Insert_ID($table, $field['chem_id']);

    pnModCallHooks('item', 'create', $chem_id, array('module'=>'Meds'));

    return $chem_id;
}
// Update chemical - adminapi.
function Meds_adminapi_update_chem($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Get argument to this function.
    $chem = $args['chem'];

    if (empty($chem['chem_id']) || empty($chem['name'])) {
        pnSessionSetVar('errormsg', _MEDS_ADM_TRY_AGAIN);
        return false;
    }
    
    $chem_id =    (int)$chem['chem_id'];
    $name    = (string)$chem['name'];
    $moa_id  = (string)$chem['moa_id'];
    
    // Ensure item exists.
    if (!pnModAPIFunc('Meds', 'user', 'get', array('object'=>'chem','chem_id'=>$chem_id))) {
        pnSessionSetVar('errormsg', _NOSUCHITEM);
        return false;
    }

    // Get database connection and tables references.
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Assign table/columns used in this function.
    $table =& $pntable['rx_chem'];
    $field =& $pntable['rx_chem_column'];

    list($chem_id, $name, $moa_id) = pnVarPrepForStore($chem_id, $name, $moa_id);

    $sql = "UPDATE $table
               SET $field[name]    = '$name',
                   $field[moa_id]  = '$moa_id'
             WHERE $field[chem_id] = '$chem_id'";

    // Execute query.
    $dbconn->Execute($sql);

    // Check for database error.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _UPDATEFAILED);
        return false;
    }
    
    $chem_id = $dbconn->PO_Insert_ID($table, $field['chem_id']);

    pnModCallHooks('item', 'update', $chem_id, array('module'=>'Meds'));

    // Start a new output object.
    $pnRender =& new pnRender('Meds');

    $pnRender->clear_cache(null, $chem_id);

    pnSessionSetVar('statusmsg', 'Update Accepted');

    return pnRedirect(pnModURL('Meds', 'admin', 'main'));
}

// Insert preservative - adminapi.
function Meds_adminapi_insert_preserve($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_ADD)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    $preserve = $args['preserve'];

    if (empty($preserve['name'])) {
        pnSessionSetVar('errormsg', _MEDS_ADM_TRY_AGAIN);
        return false;
    }
    
    $name     = (string)$preserve['name'];
    $comments = (string)$preserve['comments'];
        
    // Get database connection and tables references.
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Assign table/columns used in this function.
    $table =& $pntable['rx_preserve'];
    $field =& $pntable['rx_preserve_column'];

    $next_id = $dbconn->GenId($table);

    list($next_id, $name, $comments) = pnVarPrepForStore($next_id, $name, $comments);

    // SQL to insert item.
    $sql = "INSERT INTO $table (
                        $field[pres_id],
                        $field[name],
                        $field[comments])
                VALUES (
                        '$next_id',
                        '$name',
                        '$comments')";
    // Execute query.
    $dbconn->Execute($sql);

    // Check for database error.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _CREATEFAILED);
        return false;
    }

    $pres_id = $dbconn->PO_Insert_ID($table, $field['pres_id']);

    pnModCallHooks('item', 'create', $pres_id, array('module'=>'Meds'));

    return $pres_id;
}
// Update preservative - adminapi.
function Meds_adminapi_update_preserve($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Get argument to this function.
    $preserve = $args['preserve'];

    if (empty($preserve['pres_id']) || empty($preserve['name'])) {
        pnSessionSetVar('errormsg', _MEDS_ADM_TRY_AGAIN);
        return false;
    }
    
    $pres_id  =    (int)$preserve['pres_id'];
    $name     = (string)$preserve['name'];
    $comments = (string)$preserve['comments'];
    
    // Ensure item exists.
    if (!pnModAPIFunc('Meds', 'user', 'get', array('object'=>'preserve','pres_id'=>$pres_id))) {
        pnSessionSetVar('errormsg', _NOSUCHITEM);
        return false;
    }

    // Get database connection and tables references.
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Assign table/columns used in this function.
    $table =& $pntable['rx_preserve'];
    $field =& $pntable['rx_preserve_column'];

    list($pres_id, $name, $comments) = pnVarPrepForStore($pres_id, $name, $comments);

    $sql = "UPDATE $table
               SET $field[name]     = '$name',
                   $field[comments] = '$comments'
             WHERE $field[pres_id]  = '$pres_id'";

    // Execute query.
    $dbconn->Execute($sql);

    // Check for database error.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _UPDATEFAILED);
        return false;
    }
    
    $pres_id = $dbconn->PO_Insert_ID($table, $field['pres_id']);

    pnModCallHooks('item', 'update', $pres_id, array('module'=>'Meds'));

    // Start a new output object.
    $pnRender =& new pnRender('Meds');

    $pnRender->clear_cache(null, $pres_id);

    pnSessionSetVar('statusmsg', 'Update Accepted');

    return pnRedirect(pnModURL('Meds', 'admin', 'main'));
}

// Insert MOA - adminapi.
function Meds_adminapi_insert_moa($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_ADD)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    $moa = $args['moa'];

    if (empty($moa['name'])) {
        pnSessionSetVar('errormsg', _MEDS_ADM_TRY_AGAIN);
        return false;
    }
    
    $name     = (string)$moa['name'];
    $comments = (string)$moa['comments'];
        
    // Get database connection and tables references.
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Assign table/columns used in this function.
    $table =& $pntable['rx_moa'];
    $field =& $pntable['rx_moa_column'];

    $next_id = $dbconn->GenId($table);

    list($next_id, $name, $comments) = pnVarPrepForStore($next_id, $name, $comments);

    // SQL to insert item.
    $sql = "INSERT INTO $table (
                        $field[moa_id],
                        $field[name],
                        $field[comments])
                VALUES (
                        '$next_id',
                        '$name',
                        '$comments')";
    // Execute query.
    $dbconn->Execute($sql);

    // Check for database error.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _CREATEFAILED);
        return false;
    }

    $moa_id = $dbconn->PO_Insert_ID($table, $field['moa_id']);

    pnModCallHooks('item', 'create', $moa_id, array('module'=>'Meds'));

    return $moa_id;
}
// Update MOA - adminapi.
function Meds_adminapi_update_moa($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Get argument to this function.
    $moa = $args['moa'];

    if (empty($moa['moa_id']) || empty($moa['name'])) {
        pnSessionSetVar('errormsg', _MEDS_ADM_TRY_AGAIN);
        return false;
    }
    
    $moa_id   =    (int)$moa['moa_id'];
    $name     = (string)$moa['name'];
    $comments = (string)$moa['comments'];
    
    // Ensure item exists.
    if (!pnModAPIFunc('Meds', 'user', 'get', array('object'=>'moa','moa_id'=>$moa_id))) {
        pnSessionSetVar('errormsg', _NOSUCHITEM);
        return false;
    }

    // Get database connection and tables references.
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Assign table/columns used in this function.
    $table =& $pntable['rx_moa'];
    $field =& $pntable['rx_moa_column'];

    list($moa_id, $name, $comments) = pnVarPrepForStore($moa_id, $name, $comments);

    $sql = "UPDATE $table
               SET $field[name]     = '$name',
                   $field[comments] = '$comments'
             WHERE $field[moa_id]   = '$moa_id'";

    // Execute query.
    $dbconn->Execute($sql);

    // Check for database error.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _UPDATEFAILED);
        return false;
    }
    
    $moa_id = $dbconn->PO_Insert_ID($table, $field['moa_id']);

    pnModCallHooks('item', 'update', $moa_id, array('module'=>'Meds'));

    // Start a new output object.
    $pnRender =& new pnRender('Meds');

    $pnRender->clear_cache(null, $moa_id);

    pnSessionSetVar('statusmsg', 'Update Accepted');

    return pnRedirect(pnModURL('Meds', 'admin', 'main'));
}

// Insert company - adminapi.
function Meds_adminapi_insert_company($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_ADD)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Get argument to this function.
    $company = $args['company'];

    if (empty($company['name'])) {
        pnSessionSetVar('errormsg', _MEDS_ADM_TRY_AGAIN);
        return false;
    }
    
    $name     = (string)$company['name'];
    $phone    = (string)$company['phone'];
    $street   = (string)$company['street'];
    $city     = (string)$company['city'];
    $state    = (string)$company['state'];
    $zip      = (string)$company['zip'];
    $email    = (string)$company['email'];
    $url      = (string)$company['url'];
    $comments = (string)$company['comments'];
    
    // Get database connection and tables references.
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Assign table/columns used in this function.
    $table =& $pntable['rx_company'];
    $field =& $pntable['rx_company_column'];

    $next_id = $dbconn->GenId($table);

    list($next_id,$name,$phone,$street,$city,$state,$zip,$email,$url,$comments) = pnVarPrepForStore($next_id,$name,$phone,$street,$city,$state,$zip,$email,$url,$comments);

    // SQL to insert item.
    $sql = "INSERT INTO $table (
                        $field[comp_id],
                        $field[name],
                        $field[phone],
                        $field[street],
                        $field[city],
                        $field[state],
                        $field[zip],
                        $field[email],
                        $field[url],
                        $field[comments])
                VALUES (
                        '$next_id',
                        '$name',
                        '$phone',
                        '$street',
                        '$city',
                        '$state',
                        '$zip',
                        '$email',
                        '$url',
                        '$comments')";
    // Execute query.
    $dbconn->Execute($sql);

    // Check for database error.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _CREATEFAILED);
        return false;
    }

    $comp_id = $dbconn->PO_Insert_ID($table, $field['comp_id']);

    pnModCallHooks('item', 'create', $comp_id, array('module'=>'Meds'));

    return $comp_id;
}
// Update company - adminapi.
function Meds_adminapi_update_company($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Get argument to this function.
    $company = $args['company'];

    if (empty($company['comp_id']) || empty($company['name'])) {
        pnSessionSetVar('errormsg', _MEDS_ADM_TRY_AGAIN);
        return false;
    }

    $comp_id  =    (int)$company['comp_id'];
    $name     = (string)$company['name'];
    $phone    = (string)$company['phone'];
    $street   = (string)$company['street'];
    $city     = (string)$company['city'];
    $state    = (string)$company['state'];
    $zip      = (string)$company['zip'];
    $email    = (string)$company['email'];
    $url      = (string)$company['url'];
    $comments = (string)$company['comments'];
    
    // Ensure item exists.
    if (!pnModAPIFunc('Meds', 'user', 'get', array('object'=>'company','comp_id'=>$comp_id))) {
        pnSessionSetVar('errormsg', _NOSUCHITEM);
        return false;
    }

    // Get database connection and tables references.
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Assign table/columns used in this function.
    $table =& $pntable['rx_company'];
    $field =& $pntable['rx_company_column'];

    list($comp_id,$name,$phone,$street,$city,$state,$zip,$email,$url,$comments) = pnVarPrepForStore($comp_id,$name,$phone,$street,$city,$state,$zip,$email,$url,$comments);

    $sql = "UPDATE $table
               SET $field[name]     = '$name',
                   $field[phone]    = '$phone',
                   $field[street]   = '$street',
                   $field[city]     = '$city',
                   $field[state]    = '$state',
                   $field[zip]      = '$zip',
                   $field[email]    = '$email',
                   $field[url]      = '$url',
                   $field[comments] = '$comments'
             WHERE $field[comp_id]  = '$comp_id'";

    // Execute query.
    $dbconn->Execute($sql);

    // Check for database error.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _UPDATEFAILED);
        return false;
    }
    
    $comp_id = $dbconn->PO_Insert_ID($table, $field['comp_id']);

    pnModCallHooks('item', 'update', $comp_id, array('module'=>'Meds'));

    // Start a new output object.
    $pnRender =& new pnRender('Meds');

    $pnRender->clear_cache(null, $comp_id);

    pnSessionSetVar('statusmsg', 'Update Accepted');

    return pnRedirect(pnModURL('Meds', 'admin', 'main'));
}

// Insert med - adminapi.
function Meds_adminapi_insert_med($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_ADD)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Get argument to this function.
    $med = $args['med'];

    // Ensure required argument.
    if (empty($med['trade'])) {
        pnSessionSetVar('errormsg', _MEDS_ADM_TRY_AGAIN);
        return false;
    }
    
    // Only needed for update function.
    // $med_id     =    (int)$med['med_id'];
    $trade      = (string)$med['trade'];
    $comp_id    =    (int)$med['comp_id'];
    $medType1   = (string)$med['medType1'];
    $medType2   = (string)$med['medType2'];
    $preg       = (string)$med['preg'];
    $schedule   = (string)$med['schedule'];
    $generic    = (string)$med['generic'];
    $image1     = (string)$med['image1'];
    $image2     = (string)$med['image2'];
    $dose       = (string)$med['dose'];
    $peds       = (string)$med['peds'];
    $ped_text   = (string)$med['ped_text'];
    $nurse      = (string)$med['nurse'];
    $pres_id1   =    (int)$med['pres_id1'];
    $pres_id2   =    (int)$med['pres_id2'];
    $comments   = (string)$med['comments'];
    $rxInfo     = (string)$med['rxInfo'];
    $med_url    = (string)$med['med_url'];    
    $updated    = (string)$med['updated'];
    $display    =    (int)$med['display'];
    $conc1      = (string)$med['conc1'];
    $chem_id1   =    (int)$med['chem_id1'];
    $moa_id1    =    (int)$med['moa_id1'];
    $conc2      = (string)$med['conc2'];
    $chem_id2   =    (int)$med['chem_id2'];
    $moa_id2    =    (int)$med['moa_id2'];
    $conc3      = (string)$med['conc3'];
    $chem_id3   =    (int)$med['chem_id3'];
    $moa_id3    =    (int)$med['moa_id3'];
    $conc4      = (string)$med['conc4'];    
    $chem_id4   =    (int)$med['chem_id4'];    
    $moa_id4    =    (int)$med['moa_id4'];    
    $form1      = (string)$med['form1'];
    $size1      = (string)$med['size1'];
    $cost1      = (string)$med['cost1'];
    $form2      = (string)$med['form2'];
    $size2      = (string)$med['size2'];
    $cost2      = (string)$med['cost2'];
    $form3      = (string)$med['form3'];
    $size3      = (string)$med['size3'];
    $cost3      = (string)$med['cost3'];
    $form4      = (string)$med['form4'];    
    $size4      = (string)$med['size4'];    
    $cost4      = (string)$med['cost4'];    
    
    // Get database connection and tables references.
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Assign table/columns used in this function.
    $table =& $pntable['rx_meds'];
    $field =& $pntable['rx_meds_column'];

    $next_id = $dbconn->GenId($table);

    // SQL to insert item.
    $sql = "INSERT INTO $table (
                        $field[med_id],
                        $field[trade],
                        $field[comp_id],
                        $field[medType1],
                        $field[medType2],
                        $field[preg],
                        $field[schedule],
                        $field[generic],
                        $field[image1],
                        $field[image2],
                        $field[dose],
                        $field[peds],
                        $field[ped_text],
                        $field[nurse],
                        $field[pres_id1],
                        $field[pres_id2],
                        $field[comments],
                        $field[rxInfo],
                        $field[med_url],    
                        $field[updated],
                        $field[display],
                        $field[conc1],
                        $field[chem_id1],
                        $field[moa_id1],
                        $field[conc2],
                        $field[chem_id2],
                        $field[moa_id2],
                        $field[conc3],
                        $field[chem_id3],
                        $field[moa_id3],
                        $field[conc4],    
                        $field[chem_id4],    
                        $field[moa_id4],
                        $field[form1],
                        $field[size1],
                        $field[cost1],
                        $field[form2],
                        $field[size2],
                        $field[cost2],
                        $field[form3],
                        $field[size3],
                        $field[cost3],
                        $field[form4],    
                        $field[size4],    
                        $field[cost4])
                VALUES (
                        '".pnVarPrepForStore($next_id)."',
                        '".pnVarPrepForStore($trade)."',
                        '".pnVarPrepForStore($comp_id)."',
                        '".pnVarPrepForStore($medType1)."',
                        '".pnVarPrepForStore($medType2)."',
                        '".pnVarPrepForStore($preg)."',
                        '".pnVarPrepForStore($schedule)."',
                        '".pnVarPrepForStore($generic)."',
                        '".pnVarPrepForStore($image1)."',
                        '".pnVarPrepForStore($image2)."',
                        '".pnVarPrepForStore($dose)."',
                        '".pnVarPrepForStore($peds)."',
                        '".pnVarPrepForStore($ped_text)."',
                        '".pnVarPrepForStore($nurse)."',
                        '".pnVarPrepForStore($pres_id1)."',
                        '".pnVarPrepForStore($pres_id2)."',
                        '".pnVarPrepForStore($comments)."',
                        '".pnVarPrepForStore($rxInfo)."',
                        '".pnVarPrepForStore($med_url)."',    
                        '".pnVarPrepForStore($updated)."',
                        '".pnVarPrepForStore($display)."',                        
                        '".pnVarPrepForStore($conc1)."',
                        '".pnVarPrepForStore($chem_id1)."',
                        '".pnVarPrepForStore($moa_id1)."',
                        '".pnVarPrepForStore($conc2)."',
                        '".pnVarPrepForStore($chem_id2)."',
                        '".pnVarPrepForStore($moa_id2)."',
                        '".pnVarPrepForStore($conc3)."',
                        '".pnVarPrepForStore($chem_id3)."',
                        '".pnVarPrepForStore($moa_id3)."',
                        '".pnVarPrepForStore($conc4)."',    
                        '".pnVarPrepForStore($chem_id4)."',    
                        '".pnVarPrepForStore($moa_id4)."',
                        '".pnVarPrepForStore($form1)."',
                        '".pnVarPrepForStore($size1)."',
                        '".pnVarPrepForStore($cost1)."',
                        '".pnVarPrepForStore($form2)."',
                        '".pnVarPrepForStore($size2)."',
                        '".pnVarPrepForStore($cost2)."',
                        '".pnVarPrepForStore($form3)."',
                        '".pnVarPrepForStore($size3)."',
                        '".pnVarPrepForStore($cost3)."',
                        '".pnVarPrepForStore($form4)."',    
                        '".pnVarPrepForStore($size4)."',    
                        '".pnVarPrepForStore($cost4)."')";

    // Execute query.
    $dbconn->Execute($sql);

    // Check for database error.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _CREATEFAILED);
        return false;
    }

    // Get just-inserted record's id.
    $med_id = $dbconn->PO_Insert_ID($table, $field['med_id']);

    // Let hooks know something happened.
    pnModCallHooks('item', 'create', $med_id, array('module'=>'Meds'));

    // Success.  Return the med_id.
    return $med_id;
}
// Update med - adminapi.
function Meds_adminapi_update_med($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Get argument to this function.
    $med = $args['med'];

    if (empty($med['med_id']) || empty($med['trade'])) {
        pnSessionSetVar('errormsg', _MEDS_ADM_TRY_AGAIN);
        return false;
    }
    
    $med_id     =    (int)$med['med_id'];
    $trade      = (string)$med['trade'];
    $comp_id    =    (int)$med['comp_id'];
    $medType1   = (string)$med['medType1'];
    $medType2   = (string)$med['medType2'];
    $preg       = (string)$med['preg'];
    $schedule   = (string)$med['schedule'];
    $generic    = (string)$med['generic'];
    $image1     = (string)$med['image1'];
    $image2     = (string)$med['image2'];
    $dose       = (string)$med['dose'];
    $peds       = (string)$med['peds'];
    $ped_text   = (string)$med['ped_text'];
    $nurse      = (string)$med['nurse'];
    $pres_id1   =    (int)$med['pres_id1'];
    $pres_id2   =    (int)$med['pres_id2'];
    $comments   = (string)$med['comments'];
    $rxInfo     = (string)$med['rxInfo'];
    $med_url    = (string)$med['med_url'];    
    $updated    = (string)$med['updated'];
    $display    =    (int)$med['display'];
    $conc1      = (string)$med['conc1'];
    $chem_id1   =    (int)$med['chem_id1'];
    $moa_id1    =    (int)$med['moa_id1'];
    $conc2      = (string)$med['conc2'];
    $chem_id2   =    (int)$med['chem_id2'];
    $moa_id2    =    (int)$med['moa_id2'];
    $conc3      = (string)$med['conc3'];
    $chem_id3   =    (int)$med['chem_id3'];
    $moa_id3    =    (int)$med['moa_id3'];
    $conc4      = (string)$med['conc4'];    
    $chem_id4   =    (int)$med['chem_id4'];    
    $moa_id4    =    (int)$med['moa_id4'];
    $form1      = (string)$med['form1'];
    $size1      = (string)$med['size1'];
    $cost1      = (string)$med['cost1'];
    $form2      = (string)$med['form2'];
    $size2      = (string)$med['size2'];
    $cost2      = (string)$med['cost2'];
    $form3      = (string)$med['form3'];
    $size3      = (string)$med['size3'];
    $cost3      = (string)$med['cost3'];
    $form4      = (string)$med['form4'];    
    $size4      = (string)$med['size4'];    
    $cost4      = (string)$med['cost4'];    
    
    // Ensure item exists.
    if (!pnModAPIFunc('Meds', 'user', 'get', array('object'=>'med','med_id'=>$med_id))) {
        pnSessionSetVar('errormsg', _NOSUCHITEM);
        return false;
    }

    // Get database connection and tables references.
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Assign table/columns used in this function.
    $table =& $pntable['rx_meds'];
    $field =& $pntable['rx_meds_column'];

    $sql = "UPDATE $table
               SET $field[trade]    = '".pnVarPrepForStore($trade)."',
                   $field[comp_id]  = '".pnVarPrepForStore($comp_id)."',
                   $field[medType1] = '".pnVarPrepForStore($medType1)."',
                   $field[medType2] = '".pnVarPrepForStore($medType2)."',
                   $field[preg]     = '".pnVarPrepForStore($preg)."',
                   $field[schedule] = '".pnVarPrepForStore($schedule)."',
                   $field[generic]  = '".pnVarPrepForStore($generic)."',
                   $field[image1]   = '".pnVarPrepForStore($image1)."',
                   $field[image2]   = '".pnVarPrepForStore($image2)."',
                   $field[dose]     = '".pnVarPrepForStore($dose)."',
                   $field[peds]     = '".pnVarPrepForStore($peds)."',
                   $field[ped_text] = '".pnVarPrepForStore($ped_text)."',
                   $field[nurse]    = '".pnVarPrepForStore($nurse)."',
                   $field[pres_id1] = '".pnVarPrepForStore($pres_id1)."',
                   $field[pres_id2] = '".pnVarPrepForStore($pres_id2)."',
                   $field[comments] = '".pnVarPrepForStore($comments)."',
                   $field[rxInfo]   = '".pnVarPrepForStore($rxInfo)."',
                   $field[med_url]  = '".pnVarPrepForStore($med_url)."',    
                   $field[updated]  = '".pnVarPrepForStore($updated)."',
                   $field[display]  = '".pnVarPrepForStore($display)."',
                   $field[conc1]    = '".pnVarPrepForStore($conc1)."',
                   $field[chem_id1] = '".pnVarPrepForStore($chem_id1)."',
                   $field[moa_id1]  = '".pnVarPrepForStore($moa_id1)."',
                   $field[conc2]    = '".pnVarPrepForStore($conc2)."',
                   $field[chem_id2] = '".pnVarPrepForStore($chem_id2)."',
                   $field[moa_id2]  = '".pnVarPrepForStore($moa_id2)."',
                   $field[conc3]    = '".pnVarPrepForStore($conc3)."',
                   $field[chem_id3] = '".pnVarPrepForStore($chem_id3)."',
                   $field[moa_id3]  = '".pnVarPrepForStore($moa_id3)."',
                   $field[conc4]    = '".pnVarPrepForStore($conc4)."',    
                   $field[chem_id4] = '".pnVarPrepForStore($chem_id4)."',    
                   $field[moa_id4]  = '".pnVarPrepForStore($moa_id4)."',
                   $field[form1]    = '".pnVarPrepForStore($form1)."',
                   $field[size1]    = '".pnVarPrepForStore($size1)."',
                   $field[cost1]    = '".pnVarPrepForStore($cost1)."',
                   $field[form2]    = '".pnVarPrepForStore($form2)."',
                   $field[size2]    = '".pnVarPrepForStore($size2)."',
                   $field[cost2]    = '".pnVarPrepForStore($cost2)."',
                   $field[form3]    = '".pnVarPrepForStore($form3)."',
                   $field[size3]    = '".pnVarPrepForStore($size3)."',
                   $field[cost3]    = '".pnVarPrepForStore($cost3)."',
                   $field[form4]    = '".pnVarPrepForStore($form4)."',    
                   $field[size4]    = '".pnVarPrepForStore($size4)."',    
                   $field[cost4]    = '".pnVarPrepForStore($cost4)."'
             WHERE $field[med_id]   = '".pnVarPrepForStore($med_id)."'";

    // Execute query.
    $dbconn->Execute($sql);

    // Check for database error.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _UPDATEFAILED);
        return false;
    }
    
    $med_id = $dbconn->PO_Insert_ID($table, $field['med_id']);

    pnModCallHooks('item', 'update', $med_id, array('module'=>'Meds'));

    // Start a new output object.
    $pnRender =& new pnRender('Meds');

    $pnRender->clear_cache(null, $med_id);

    pnSessionSetVar('statusmsg', 'Update Accepted');

    return pnRedirect(pnModURL('Meds', 'admin', 'main'));
}

/** 
 * Delete item from the database - adminapi.
 * 
 * @param   $object STRING  required    table to delete from
 * @param   $id     INT     required    id to delete
 * @param $confirmation INT required    confirms deletion action
 * 
 * @return  true on successful deletion, else false.
 * 
 */
function Meds_adminapi_delete($args)
{
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_DELETE)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    $object = (string)pnVarPrepForStore($args['object']);

    if ($object == 'med')       { $id_field = 'med_id'; }
    if ($object == 'moa')       { $id_field = 'moa_id'; }
    if ($object == 'company')   { $id_field = 'comp_id'; }
    if ($object == 'preserve')  { $id_field = 'pres_id'; }
    if ($object == 'chem')      { $id_field = 'chem_id'; }
    
    $id = (int)pnVarPrepForStore($args[$id_field]);
    
    $exists = pnModAPIFunc('Meds', 'user', 'get', array('object'=>$object, $id_field=>$id));

    if (!$exists) {
        pnSessionSetVar('errormsg', _NOSUCHITEM);
        return false;
    }

    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Set proper tables/columns.
    switch($object) {
        case 'med':
            $table =& $pntable['rx_meds'];
            $field =& $pntable['rx_meds_column'];
            break;
        case 'chem':
            $table =& $pntable['rx_chem'];
            $field =& $pntable['rx_chem_column'];
            break;
        case 'moa':
            $table =& $pntable['rx_moa'];
            $field =& $pntable['rx_moa_column'];
            break;
        case 'preserve':
            $table =& $pntable['rx_preserve'];
            $field =& $pntable['rx_preserve_column'];
            break;
        case 'company':
            $table =& $pntable['rx_company'];
            $field =& $pntable['rx_company_column'];
            break;
        default: break;
    }
    
    $sql = "DELETE FROM $table WHERE $field[$id_field] = '$id'";
    
    $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _DELETEFAILED);
        return false;
    }

    pnModCallHooks('item', 'delete', $id, array('module'=>'Meds'));

    $pnRender =& new pnRender('Meds');

    $pnRender->clear_cache(null, $id);

    return true;
}

?>