<?php

// ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_adminapi_insert_lens($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Extract any arguments.
    extract($args);

    // Extract $lens for cleaner code below.
    extract($lens_data);

    // NOTE: Even though there are many, many fields in the lens
    //       creation form, only the 'name' field is checked due
    //       to the fact that every lens may or may not need any
    //       given field. To this end, only a 'name' is required
    //       to create (or later modify) a contact lens entry.

    // Ensure valid name was passed in.
    if (empty($name) || !is_string($name)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Get a reference to the database object.
    $dbconn =& pnDBGetConn(true);

    // Get a reference to PostNuke's table info.
    $pntable =& pnDBGetTables();

    // Define table and column to work with.
    $lenses_table =& $pntable['lenses'];
    $lenses_field =& $pntable['lenses_column'];

    // NOTE: We need to take care of a few preliminaries
    //       before passing the data off to the database
    //       for storage.  Specifically:
    //       1) Get the next table ID   - $tid
    //       2) Get today's date        - $birthday


    // Next table ID.
    $next_tid = $dbconn->GenId($lenses_table);

    // Today's date.
    $created = date('Y-m-d');



    // NOTE: There would typically be a list() of all variables here
    //       which would be prepped for db storage before being used
    //       in the $sql query below.  This is not the case when the
    //       new lens is being inserted as this effectively adds apx
    //       165 lines of code between here and the $sql query.  The
    //       data is instead cleaned, still via pnVarPrepForStore(),
    //       as it would have been done here in a list(); the only
    //       difference here is that the data is cleaned AS the $sql
    //       query string is created, instead of BEFOREHAND.

    // Create sql query to insert lens.
    $sql = "INSERT INTO $lenses_table (
                        $lenses_field[tid],
                        $lenses_field[name],
                        $lenses_field[aliases],
                        $lenses_field[comp_id],
                        $lenses_field[poly_id],
                        $lenses_field[visitint],
                        $lenses_field[ew],
                        $lenses_field[ct],
                        $lenses_field[dk],
                        $lenses_field[oz],
                        $lenses_field[process_text],
                        $lenses_field[process_simple],
                        $lenses_field[qty],
                        $lenses_field[replace_simple],
                        $lenses_field[replace_text],
                        $lenses_field[wear],
                        $lenses_field[price],
                        $lenses_field[markings],
                        $lenses_field[fitting_guide],
                        $lenses_field[website],
                        $lenses_field[image],
                        $lenses_field[other_info],
                        $lenses_field[discontinued],
                        $lenses_field[display],
                        $lenses_field[redirect],
                        $lenses_field[bc_simple],
						$lenses_field[bc_all],
                        $lenses_field[max_plus],
                        $lenses_field[max_minus],
                        $lenses_field[max_diam],
                        $lenses_field[min_diam],
                        $lenses_field[diam_1],
                        $lenses_field[base_curves_1],
                        $lenses_field[powers_1],
                        $lenses_field[diam_2],
                        $lenses_field[base_curves_2],
                        $lenses_field[powers_2],
                        $lenses_field[diam_3],
                        $lenses_field[base_curves_3],
                        $lenses_field[powers_3],
						$lenses_field[sph_notes],
                   
                        $lenses_field[toric],
                        $lenses_field[toric_type],
                        $lenses_field[toric_type_simple],
                        $lenses_field[cyl_power],
                        $lenses_field[max_cyl_power],
                        $lenses_field[cyl_axis],
                        $lenses_field[cyl_axis_steps],
                        $lenses_field[oblique],
						$lenses_field[cyl_notes],
              
                        $lenses_field[bifocal],
                        $lenses_field[bifocal_type],
                        $lenses_field[add_text],
                        $lenses_field[max_add],
                        $lenses_field[cosmetic],
                        $lenses_field[enh_names],
                        $lenses_field[enh_names_simple],
                        $lenses_field[opaque_names],
                        $lenses_field[opaque_names_simple],
                        $lenses_field[updated])
                VALUES (
                        '".pnVarPrepForStore($next_tid)."',
                        '".pnVarPrepForStore($name)."',
                        '".pnVarPrepForStore($aliases)."',
                        '".pnVarPrepForStore($comp_id)."',
                        '".pnVarPrepForStore($poly_id)."',
                        '".pnVarPrepForStore($visitint)."',
                        '".pnVarPrepForStore($ew)."',
                        '".pnVarPrepForStore($ct)."',
                        '".pnVarPrepForStore($dk)."',
                        '".pnVarPrepForStore($oz)."',
                        '".pnVarPrepForStore($process_text)."',
                        '".pnVarPrepForStore($process_simple)."',
                        '".pnVarPrepForStore($qty)."',
                        '".pnVarPrepForStore($replace_simple)."',
                        '".pnVarPrepForStore($replace_text)."',
                        '".pnVarPrepForStore($wear)."',
                        '".pnVarPrepForStore($price)."',
                        '".pnVarPrepForStore($markings)."',
                        '".pnVarPrepForStore($fitting_guide)."',
                        '".pnVarPrepForStore($website)."',
                        '".pnVarPrepForStore($image)."',
                        '".pnVarPrepForStore($other_info)."',
                        '".pnVarPrepForStore($discontinued)."',
                        '".pnVarPrepForStore($display)."',
                        '".pnVarPrepForStore($redirect)."',
                        '".pnVarPrepForStore($bc_simple)."',
						'".pnVarPrepForStore($bc_all)."',
                        '".pnVarPrepForStore($max_plus)."',
                        '".pnVarPrepForStore($max_minus)."',
                        '".pnVarPrepForStore($max_diam)."',
                        '".pnVarPrepForStore($min_diam)."',
                        '".pnVarPrepForStore($diam_1)."',
                        '".pnVarPrepForStore($base_curves_1)."',
                        '".pnVarPrepForStore($powers_1)."',
                        '".pnVarPrepForStore($diam_2)."',
                        '".pnVarPrepForStore($base_curves_2)."',
                        '".pnVarPrepForStore($powers_2)."',
                        '".pnVarPrepForStore($diam_3)."',
                        '".pnVarPrepForStore($base_curves_3)."',
                        '".pnVarPrepForStore($powers_3)."',
						'".pnVarPrepForStore($sph_notes)."',
                       
                        '".pnVarPrepForStore($toric)."',
                        '".pnVarPrepForStore($toric_type)."',
                        '".pnVarPrepForStore($toric_type_simple)."',
                        '".pnVarPrepForStore($cyl_power)."',
                        '".pnVarPrepForStore($max_cyl_power)."',
                        '".pnVarPrepForStore($cyl_axis)."',
                        '".pnVarPrepForStore($cyl_axis_steps)."',
                        '".pnVarPrepForStore($oblique)."',
						'".pnVarPrepForStore($cyl_notes)."',
                    
                        '".pnVarPrepForStore($bifocal)."',
                        '".pnVarPrepForStore($bifocal_type)."',
                        '".pnVarPrepForStore($add_text)."',
                        '".pnVarPrepForStore($max_add)."',
                        '".pnVarPrepForStore($cosmetic)."',
                        '".pnVarPrepForStore($enh_names)."',
                        '".pnVarPrepForStore($enh_names_simple)."',
                        '".pnVarPrepForStore($opaque_names)."',
                        '".pnVarPrepForStore($opaque_names_simple)."',
                        '".pnVarPrepForStore($created)."'
                        )";


    // Execute the  query.
    $result = $dbconn->Execute($sql);

    // Check for any database errors.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _CREATEFAILED);
        return false;
    }

    // Get the table id (tid) of the last insert, just to be sure.
    $tid = $dbconn->PO_Insert_ID($lenses_table, $lenses_field['tid']);
    
    // Let any hooks know that we have created a new item.  As this is a
	// create hook we're passing 'tid' as the extra info, which is the
	// argument that all of the other functions use to reference this
	// item
	pnModCallHooks('item', 'create', $tid, 'tid');
	

    // Return the lens id.
    return $tid;
}

// ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_adminapi_update_lens($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Extract arguments.  In this case, $lens.
    extract($args);

    // Extract lens array.
    extract($lens_data);

    // Ensure valid values were passed in.
    if (empty($tid) || !is_numeric($tid) ||
        empty($name) || !is_string($name)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Check if lens exists.
    if (!pnModAPIFunc('Lenses', 'user', 'get', array('item_id'=>$tid, 'item_type'=>'lens'))) {
        pnSessionSetVar('errormsg', _NOSUCHITEM);
        return false;
    }

    // Get a reference to the database object.
    $dbconn =& pnDBGetConn(true);

    // Get a reference to PostNuke's table info.
    $pntable =& pnDBGetTables();

    // Define table and column to work with.
    $lenses_table =& $pntable['lenses'];
    $lenses_field =& $pntable['lenses_column'];

    // NOTE: We need to take care of a few preliminaries
    //       before passing the data off to the database
    //       for storage.  Specifically:
    //       1) Get today's date        - $updated


    // Today's date.
    $updated = date('Y-m-d');


    // NOTE: There would typically be a list() of all variables here
    //       which would be prepped for db storage before being used
    //       in the $sql query below.  This is not the case when the
    //       new lens is being inserted as this effectively adds apx
    //       165 lines of code between here and the $sql query.  The
    //       data is instead cleaned, still via pnVarPrepForStore(),
    //       as it would have been done here in a list(); the only
    //       difference here is that the data is cleaned AS the $sql
    //       query string is created, instead of BEFOREHAND.

    // Create sql to insert lens.
    $sql = "UPDATE $lenses_table
               SET $lenses_field[name]                  = '".pnVarPrepForStore($name)."',
                   $lenses_field[aliases]               = '".pnVarPrepForStore($aliases)."',
                   $lenses_field[comp_id]               = '".pnVarPrepForStore($comp_id)."',
                   $lenses_field[poly_id]               = '".pnVarPrepForStore($poly_id)."',
                   $lenses_field[visitint]              = '".pnVarPrepForStore($visitint)."',
                   $lenses_field[ew]                    = '".pnVarPrepForStore($ew)."',
                   $lenses_field[ct]                    = '".pnVarPrepForStore($ct)."',
                   $lenses_field[dk]                    = '".pnVarPrepForStore($dk)."',
                   $lenses_field[oz]                    = '".pnVarPrepForStore($oz)."',
                   $lenses_field[process_text]          = '".pnVarPrepForStore($process_text)."',
                   $lenses_field[process_simple]        = '".pnVarPrepForStore($process_simple)."',
                   $lenses_field[qty]                   = '".pnVarPrepForStore($qty)."',
                   $lenses_field[replace_simple]        = '".pnVarPrepForStore($replace_simple)."',
                   $lenses_field[replace_text]          = '".pnVarPrepForStore($replace_text)."',
                   $lenses_field[wear]                  = '".pnVarPrepForStore($wear)."',
                   $lenses_field[price]                 = '".pnVarPrepForStore($price)."',
                   $lenses_field[markings]              = '".pnVarPrepForStore($markings)."',
                   $lenses_field[fitting_guide]         = '".pnVarPrepForStore($fitting_guide)."',
                   $lenses_field[website]               = '".pnVarPrepForStore($website)."',
                   $lenses_field[image]                 = '".pnVarPrepForStore($image)."',
                   $lenses_field[other_info]            = '".pnVarPrepForStore($other_info)."',
                   $lenses_field[discontinued]          = '".pnVarPrepForStore($discontinued)."',
                   $lenses_field[display]               = '".pnVarPrepForStore($display)."',
                   $lenses_field[redirect]              = '".pnVarPrepForStore($redirect)."',
                   $lenses_field[bc_simple]             = '".pnVarPrepForStore($bc_simple)."',
				   $lenses_field[bc_all]                = '".pnVarPrepForStore($bc_all)."',
                   $lenses_field[max_plus]              = '".pnVarPrepForStore($max_plus)."',
                   $lenses_field[max_minus]             = '".pnVarPrepForStore($max_minus)."',
                   $lenses_field[max_diam]              = '".pnVarPrepForStore($max_diam)."',
                   $lenses_field[min_diam]              = '".pnVarPrepForStore($min_diam)."',
                   $lenses_field[diam_1]                = '".pnVarPrepForStore($diam_1)."',
                   $lenses_field[base_curves_1]         = '".pnVarPrepForStore($base_curves_1)."',
                   $lenses_field[powers_1]              = '".pnVarPrepForStore($powers_1)."',
                   $lenses_field[diam_2]                = '".pnVarPrepForStore($diam_2)."',
                   $lenses_field[base_curves_2]         = '".pnVarPrepForStore($base_curves_2)."',
                   $lenses_field[powers_2]              = '".pnVarPrepForStore($powers_2)."',
                   $lenses_field[diam_3]                = '".pnVarPrepForStore($diam_3)."',
                   $lenses_field[base_curves_3]         = '".pnVarPrepForStore($base_curves_3)."',
                   $lenses_field[powers_3]              = '".pnVarPrepForStore($powers_3)."',
				   $lenses_field[sph_notes]             = '".pnVarPrepForStore($sph_notes)."',
                   
                   $lenses_field[toric]                 = '".pnVarPrepForStore($toric)."',
                   $lenses_field[toric_type]            = '".pnVarPrepForStore($toric_type)."',
                   $lenses_field[toric_type_simple]     = '".pnVarPrepForStore($toric_type_simple)."',
                   $lenses_field[cyl_power]             = '".pnVarPrepForStore($cyl_power)."',
                   $lenses_field[max_cyl_power]         = '".pnVarPrepForStore($max_cyl_power)."',
                   $lenses_field[cyl_axis]              = '".pnVarPrepForStore($cyl_axis)."',
                   $lenses_field[cyl_axis_steps]        = '".pnVarPrepForStore($cyl_axis_steps)."',
                   $lenses_field[oblique]               = '".pnVarPrepForStore($oblique)."',
				   $lenses_field[cyl_notes]             = '".pnVarPrepForStore($cyl_notes)."',

                   $lenses_field[bifocal]               = '".pnVarPrepForStore($bifocal)."',
                   $lenses_field[bifocal_type]          = '".pnVarPrepForStore($bifocal_type)."',
                   $lenses_field[add_text]              = '".pnVarPrepForStore($add_text)."',
                   $lenses_field[max_add]               = '".pnVarPrepForStore($max_add)."',
                   $lenses_field[cosmetic]              = '".pnVarPrepForStore($cosmetic)."',
                   $lenses_field[enh_names]             = '".pnVarPrepForStore($enh_names)."',
                   $lenses_field[enh_names_simple]      = '".pnVarPrepForStore($enh_names_simple)."',
                   $lenses_field[opaque_names]          = '".pnVarPrepForStore($opaque_names)."',
                   $lenses_field[opaque_names_simple]   = '".pnVarPrepForStore($opaque_names_simple)."',
                   $lenses_field[updated]               = '".date('Y-m-d')."'
             WHERE $lenses_field[tid]                   = '".(int)pnVarPrepForStore($tid)."'
             ";

    // Execute the SQL query.
    $result = $dbconn->Execute($sql);

    // Check for any database errors.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _UPDATEFAILED . '<br />' . mysql_error());
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

function Lenses_adminapi_delete($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Extract arguments.
    extract($args);

    // Ensure valid values were passed in.
    if (empty($tid) || !is_numeric($tid) ||
        empty($item_type) || !is_string($item_type)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Call API function to verify bifocal type exists.
    $item_exists = pnModAPIFunc('Lenses', 'user', 'get', array('item_id' => $tid, 'item_type' => $item_type));

    // Verify sphere exists.
    if (!$item_exists) {
        pnSessionSetVar('errormsg', _NOSUCHITEM);
        return false;
    }

    // Get a reference to the database object.
    $dbconn =& pnDBGetConn(true);

    // Get a reference to PostNuke's table info.
    $pntable =& pnDBGetTables();

    switch($item_type)
    {
        case 'lens':
                $table =& $pntable['lenses'];
                $field =& $pntable['lenses_column'];
				$sql = "DELETE FROM $table WHERE $field[tid] = '".(int)$tid."'";
                break;
        case 'company':
                $table =& $pntable['lenses_companies'];
                $field =& $pntable['lenses_companies_column'];
				$sql = "DELETE FROM $table WHERE $field[comp_tid] = '".(int)$tid."'";
                break;
        case 'polymer':
                $table =& $pntable['lenses_polymers'];
                $field =& $pntable['lenses_polymers_column'];
				$sql = "DELETE FROM $table WHERE $field[poly_tid] = '".(int)$tid."'";
                break;
        default:break;
    }


    

    // Execute the SQL query.
    $result = $dbconn->Execute($sql);

    // Check for any database errors.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _DELETEFAILED);
        return false;
    }

    // Let any hooks know that we have deleted an item.  As this is a
	// delete hook we're not passing any extra info
	pnModCallHooks('item', 'delete', $tid, '');

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
        empty($poly_name)    || !is_string($poly_name)
        ) {
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
        empty($poly_name)    || !is_string($poly_name) 
        ) {
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

//-----------------------------------------------------------

//------------------------------------------------------------
//a function to report on all the lenses manufactured ba a particular company

function Lenses_adminapi_report_company($args)
{
    
    // Clean $tid from input.
    $comp_tid = pnVarCleanFromInput('comp_tid');

    // Get arguments from argument array
    extract($args);
    

    // Ensure valid values were passed in.
    if (empty($comp_tid) || !is_numeric($comp_tid)) {
        return pnVarPrepHTMLDisplay('Invalid company id.');
    }

	//set up the array that will be returned
	$items=array();
	

    // Get datbase setup 
	list($dbconn) = pnDBGetConn();
    
	//get the table info
	$pntable =& pnDBGetTables();
        $table_lens =& $pntable['lenses'];
        $field_lens =& $pntable['lenses_column'];

        $table_polymer =& $pntable['lenses_polymers'];
        $field_polymer =& $pntable['lenses_polymers_column'];
	
    $sql = "SELECT * FROM $table_lens 
               LEFT JOIN $table_polymer ON $field_polymer[poly_tid] = $field_lens[poly_id]
               WHERE $field_lens[comp_id] = $comp_tid AND $field_lens[display] = 1;";

    //echo ("<p>".$sql."</p>");

    $result =$dbconn->Execute($sql);
    
    //print_r ($result);die;

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

     // get polymer data to switch polymer IDs for polymer names
     $polymer_data = pnModAPIFunc('Lenses', 'user', 'getall', array('item_type'=>'polymers'));

            for (; !$result->EOF; $result->MoveNext()) {
            list($tid,
                 $name,
                 $aliases,
                 $comp_id,
                 $poly_id,
                 $visitint,
                 $ew,
                 $ct,
                 $dk,
                 $oz,
                 $process_text,
                 $process_simple,
                 $qty,
                 $replace_simple,
                 $replace_text,
                 $wear,
                 $price,
                 $markings,
                 $fitting_guide,
                 $website,
                 $image,
                 $other_info,
                 $discontinued,
                 $display,
                 $redirect,
                 $bc_simple,
				 $bc_all,
                 $max_plus,
                 $max_minus,
                 $max_diam,
                 $min_diam,
                 $diam_1,
                 $base_curves_1,
                 $powers_1,
                 $diam_2,
                 $base_curves_2,
                 $powers_2,
                 $diam_3,
                 $base_curves_3,
                 $powers_3,
				 $sph_notes,
        
                 $toric,
                 $toric_type,
                 $toric_type_simple,
                 $cyl_power,
                 $max_cyl_power,
                 $cyl_axis,
                 $cyl_axis_steps,
                 $oblique,
				 $cyl_notes,
       
                 $bifocal,
                 $bifocal_type,
                 $add_text,
                 $max_add,
                 $cosmetic,
                 $enh_names,
                 $enh_names_simple,
                 $opaque_names,
                 $opaque_names_simple,
                 $updated) = $result->fields;

            $items_array[$tid] =  array('tid'                   => $tid,
                                        'name'                  => $name,
                                        'aliases'               => $aliases,
					                    'comp_id'               => $comp_id,
                                        'comp_name'      => $company_data[$comp_id][comp_name],
					                    'poly_id'               => $poly_id,
                                        'poly_name'     => $polymer_data[$poly_id][poly_name],
                                        'fda_grp'		=> $polymer_data[$poly_id][fda_grp],
                                        'h2o'			=> $polymer_data[$poly_id][h2o],
                                        'visitint'              => $visitint,
                                        'ew'                    => $ew,
                                        'ct'                    => $ct,
                                        'dk'                    => $dk,
                                        'oz'                    => $oz,
                                        'process_text'          => $process_text,
                                        'process_simple'        => $process_simple,
                                        'qty'                   => $qty,
                                        'replace_simple'        => $replace_simple,
                                        'replace_text'          => $replace_text,
                                        'wear'                  => $wear,
                                        'price'                 => $price,
                                        'markings'              => $markings,
                                        'fitting_guide'         => $fitting_guide,
                                        'website'               => $website,
                                        'image'                 => $image,
                                        'other_info'            => $other_info,
                                        'discontinued'          => $discontinued,
                                        'display'               => $display,
                                        'redirect'              => $redirect,
                                        'bc_simple'             => $bc_simple,
										'bc_all'                => $bc_all,
                                        'max_plus'              => $max_plus,
                                        'max_minus'             => $max_minus,
                                        'max_diam'              => $max_diam,
                                        'min_diam'              => $min_diam,
                                        'diam_1'                => $diam_1,
                                        'base_curves_1'         => $base_curves_1,
                                        'powers_1'              => $powers_1,
                                        'diam_2'                => $diam_2,
                                        'base_curves_2'         => $base_curves_2,
                                        'powers_2'              => $powers_2,
                                        'diam_3'                => $diam_3,
                                        'base_curves_3'         => $base_curves_3,
                                        'powers_3'              => $powers_3,
										'sph_notes'             => $sph_notes,
                                   
                                        'toric'                 => $toric,
                                        'toric_type'            => $toric_type,
                                        'toric_type_simple'     => $toric_type_simple,
                                        'cyl_power'             => $cyl_power,
                                        'max_cyl_power'         => $max_cyl_power,
                                        'cyl_axis'              => $cyl_axis,
                                        'cyl_axis_steps'        => $cyl_axis_steps,
                                        'oblique'               => $oblique,
										'cyl_notes'             => $cyl_notes,
                                       
                                        'bifocal'               => $bifocal,
                                        'bifocal_type'          => $bifocal_type,
                                        'add_text'              => $add_text,
                                        'max_add'               => $max_add,
                                        'cosmetic'              => $cosmetic,
                                        'enh_names'             => $enh_names,
                                        'enh_names_simple'      => $enh_names_simple,
                                        'opaque_names'          => $opaque_names,
                                        'opaque_names_simple'   => $opaque_names_simple,
                                        'updated'               => $updated,
                                        );
            }

    //print_r ($items_array);die;

    // Return the item array
    return $items_array;
}

?>
