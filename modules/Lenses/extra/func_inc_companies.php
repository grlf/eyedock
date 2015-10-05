<?php

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

// ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_adminapi_insert_company($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Extract any arguments.
    extract($args);

    // Extract $company for cleaner code below.
    extract($company);

    // Ensure valid values were passed in.
    if (empty($comp_name) || !is_string($comp_name)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }
    
    // NOTE: No check for other fields as they are not required.

    // Get a reference to the database object.
    $dbconn =& pnDBGetConn(true);

    // Get a reference to PostNuke's table info.
    $pntable =& pnDBGetTables();

    // Define table and column to work with.
    $companies_table =& $pntable['lenses_companies'];
    $companies_field =& $pntable['lenses_companies_column'];

    // Get the next $tid for the table before we insert.
    $next_tid = $dbconn->GenId($companies_table);

	// Prep data for storage in the database.
    list($comp_name,
         $phone,
		 $logo,
         $address,
         $city,
         $state,
         $zip,
         $url,
         $email,
         $comp_desc) = pnVarPrepForStore($comp_name,
                                    $phone,
									$logo,
                                    $address,
                                    $city,
                                    $state,
                                    $zip,
                                    $url,
                                    $email,
                                    $comp_desc);

    // Create sql to insert company.
    $sql = "INSERT INTO $companies_table (
                        $companies_field[comp_tid],
                        $companies_field[comp_name],
						$companies_field[logo],
                        $companies_field[phone],
                        $companies_field[address],
                        $companies_field[city],
                        $companies_field[state],
                        $companies_field[zip],
                        $companies_field[url],
                        $companies_field[email],
                        $companies_field[comp_desc])
                 VALUES (
                        '$next_tid',
                        '$comp_name',
						'$logo',
                        '$phone',
                        '$address',
                        '$city',
                        '$state',
                        '$zip',
                        '$url',
                        '$email',
                        '$comp_desc')";

    // Execute the SQL query.
    $result = $dbconn->Execute($sql);

    // Check for any database errors.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _CREATEFAILED);
        return false;
    }

    // Get the table id (tid) of the last insert, just to be sure.
    $comp_tid = $dbconn->PO_Insert_ID($companies_table, $companies_field['comp_tid']);

    // Return the company id.
    return $comp_tid;
}

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
function Lenses_adminapi_update_company($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Extract arguments.  In this case, $company.
    extract($args);

    // Extract company array.
    extract($company);


    // Ensure valid values were passed in.
    if (empty($comp_tid) ||  !is_numeric($comp_tid) ||
        empty($comp_name) || !is_string($comp_name)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // NOTE: No check for other fields as they are not required.

    // Check if company exists.
    if (!pnModAPIFunc('Lenses', 'user', 'get', array('item_id' => $comp_tid, 'item_type' => 'company'))) {
        pnSessionSetVar('errormsg', _NOSUCHITEM);
        return false;
    }

    // Get a reference to the database object.
    $dbconn =& pnDBGetConn(true);

    // Get a reference to PostNuke's table info.
    $pntable =& pnDBGetTables();

    // Define table and column to work with.
    $companies_table =& $pntable['lenses_companies'];
    $companies_field =& $pntable['lenses_companies_column'];

    // Prep data for storage in database.
    list($comp_tid,
         $comp_name,
		 $logo,
         $phone,
         $address,
         $city,
         $state,
         $zip,
         $url,
         $email,
         $comp_desc) = pnVarPrepForStore($comp_tid,
                                    $comp_name,
									$logo,
                                    $phone,
                                    $address,
                                    $city,
                                    $state,
                                    $zip,
                                    $url,
                                    $email,
                                    $comp_desc);

    // Create SQL string to update the company record.
    $sql =  "UPDATE $companies_table
                SET $companies_field[comp_name]     = '$comp_name',
					$companies_field[logo]     	    = '$logo',
                    $companies_field[phone]     	= '$phone',
                    $companies_field[address]  		= '$address',
                    $companies_field[city]      	= '$city',
                    $companies_field[state]     	= '$state',
                    $companies_field[zip]       	= '$zip',
                    $companies_field[url]       	= '$url',
                    $companies_field[email]     	= '$email',
                    $companies_field[comp_desc]     = '$comp_desc'
              WHERE $companies_field[comp_tid]      = '$comp_tid'";

//echo($sql);

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

?>
