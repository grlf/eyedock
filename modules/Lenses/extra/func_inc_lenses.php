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
    return pnRedirect(pnModURL('Lenses', 'admin', 'viewall_lenses'));
}

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
    return pnRedirect(pnModURL('Lenses', 'user', 'view', array('tid'=>$lens_data[tid])));
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
				   $lenses_field[bc_all]            	= '".pnVarPrepForStore($bc_all)."',
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
				   $lenses_field[sph_notes]            = '".pnVarPrepForStore($sph_notes)."',
           
                   $lenses_field[toric]                 = '".pnVarPrepForStore($toric)."',
                   $lenses_field[toric_type]            = '".pnVarPrepForStore($toric_type)."',
                   $lenses_field[toric_type_simple]     = '".pnVarPrepForStore($toric_type_simple)."',
                   $lenses_field[cyl_power]             = '".pnVarPrepForStore($cyl_power)."',
                   $lenses_field[max_cyl_power]         = '".pnVarPrepForStore($max_cyl_power)."',
                   $lenses_field[cyl_axis]              = '".pnVarPrepForStore($cyl_axis)."',
                   $lenses_field[cyl_axis_steps]        = '".pnVarPrepForStore($cyl_axis_steps)."',
                   $lenses_field[oblique]               = '".pnVarPrepForStore($oblique)."',
				   $lenses_field[cyl_notes]               = '".pnVarPrepForStore($cyl_notes)."',
                  
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



    // Assign $lenses to template.
    $pnRender->assign('lenses_data', $lenses_data);



    // Return templated output.
    return $pnRender->fetch('lenses_admin_viewall_lenses.htm');
}


?>
