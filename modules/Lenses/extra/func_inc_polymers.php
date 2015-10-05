<?php

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
function Lenses_adminapi_insert_polymer($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Extract any arguments.
    extract($args);

    // Extract $polymer for cleaner code below.
    extract($polymer);

    // Ensure valid values were passed in.
    if (empty($fda_grp) || !is_numeric($fda_grp) ||
        empty($h2o)     || !is_string($h2o) ||
        empty($poly_name)    || !is_string($poly_name) ||
        empty($poly_desc)    || !is_string($poly_desc)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // NOTE: No check for alt field as it can be empty.

    // Get a reference to the database object.
    $dbconn =& pnDBGetConn(true);

    // Get a reference to PostNuke's table info.
    $pntable =& pnDBGetTables();

    // Define table and column to work with.
    $polymers_table =& $pntable['lenses_polymers'];
    $polymers_field =& $pntable['lenses_polymers_column'];

    // Get the next $tid for the table before we insert.
    $next_tid = $dbconn->GenId($polymers_table);

	// Prep data for storage in the database.
    list($fda_grp,
         $h2o,
         $poly_name,
         $poly_desc) = pnVarPrepForStore($fda_grp,
                                    $h2o,
                                    $poly_name,
                                    $poly_desc);

    // Create sql to insert polymer.
    $sql = "INSERT INTO $polymers_table (
                        $polymers_field[poly_tid],
                        $polymers_field[fda_grp],
                        $polymers_field[h2o],
                        $polymers_field[poly_name],
                        $polymers_field[poly_desc])
                 VALUES (
                        '$next_tid',
                        '$fda_grp',
                        '$h2o',
                        '$poly_name',
                        '$poly_desc')";

    // Execute the SQL query.
    $result = $dbconn->Execute($sql);

    // Check for any database errors.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _CREATEFAILED);
        return false;
    }

    // Get the table id of the last insert, just to be sure.
    $poly_tid = $dbconn->PO_Insert_ID($polymers_table, $polymers_field['poly_tid']);

    // Return the polymer id.
    return $poly_tid;
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
function Lenses_adminapi_update_polymer($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Extract arguments.  In this case, $polymer.
    extract($args);

    // Extract polymer array.
    extract($polymer);

    // Ensure valid values were passed in.
    if (empty($poly_tid)     || !is_numeric($poly_tid) ||
        empty($fda_grp) || !is_numeric($fda_grp) ||
        empty($h2o)     || !is_string($h2o) ||
        empty($poly_name)    || !is_string($poly_name) ||
        empty($poly_desc)    || !is_string($poly_desc)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // NOTE: No check for alt field as it can be empty.

    // Check if polymer exists.
    if (!pnModAPIFunc('Lenses', 'user', 'get', array('item_id' => $poly_tid, 'item_type' => 'polymer'))) {
        pnSessionSetVar('errormsg', _NOSUCHITEM);
        return false;
    }

    // Get a reference to the database object.
    $dbconn =& pnDBGetConn(true);

    // Get a reference to PostNuke's table info.
    $pntable =& pnDBGetTables();

    // Define table and column to work with.
    $polymers_table =& $pntable['lenses_polymers'];
    $polymers_field =& $pntable['lenses_polymers_column'];

    // Prep data for storage in database.
    list($poly_tid,
         $fda_grp,
         $h2o,
         $name,
         $poly_desc) = pnVarPrepForStore($poly_tid,
                                    $fda_grp,
                                    $h2o,
                                    $name,
                                    $poly_desc);

    // Create SQL string to update the polymer record.
    $sql =  "UPDATE $polymers_table
                SET $polymers_field[fda_grp] 		= '$fda_grp',
                    $polymers_field[h2o]     		= '$h2o',
                    $polymers_field[poly_name]  	= '$poly_name',
                    $polymers_field[poly_desc]    	= '$poly_desc'
              WHERE $polymers_field[poly_tid]     	= '$poly_tid'";

    // Execute the SQL query.
    $result = $dbconn->Execute($sql);

    // Check for any database errors.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _UPDATEFAILED);
        return false;
    }

    // Start a new output object.
    // This function isn't an output function, but needs an output
    // object started before the cache can be cleared.
    $pnRender =& new pnRender('Lenses');

    // Clear the cache.
    $pnRender->clear_cache();

    // Return success.
    return true;
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

?>
