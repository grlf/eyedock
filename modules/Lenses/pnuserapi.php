<?php

// ------------------------------------------------------------
//  Get any item from a module table.
// ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_userapi_get($args)
{

    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_READ)) {
        return $items_array;
    }
	
	// Clean $tid from input.
    list($item_type, $item_id) = pnVarCleanFromInput('item_type','item_id');
	
    extract($args);

//echo($item_id."<br/> ");

    // Ensure valid values were passed in.
    if (empty($item_type) || !is_string($item_type) || empty($item_id) || !is_numeric($item_id) ) {
        //echo 'tid: $tid, item_type:$item_type<br />';
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }
	

    // Get a reference to the database object.
    $dbconn =& pnDBGetConn(true);

    // Get a reference to PostNuke's table info.
    $pntable =& pnDBGetTables();

    // A switch to choose the proper table.
    switch($item_type)
    {
        case 'lens':
                $table =& $pntable['lenses'];
                $field =& $pntable['lenses_column'];
				$sql = "SELECT *
                		FROM $table
               			WHERE $field[tid] = $item_id";
                break;
        case 'company':
                $table =& $pntable['lenses_companies'];
                $field =& $pntable['lenses_companies_column'];
				$sql = "SELECT *
                		FROM $table
               			WHERE $field[comp_tid] = $item_id";
                break;
        case 'polymer':
                $table =& $pntable['lenses_polymers'];
                $field =& $pntable['lenses_polymers_column'];
				$sql = "SELECT *
                		FROM $table
               			WHERE $field[poly_tid] = $item_id";
                break;
        default:break;
    }

// echo($sql."<br/>");
    

    // Execute the SQL query.
    $result = $dbconn->Execute($sql);

//print_r($result);

    // Check for any database errors.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _GETFAILED);
        return false;
    }

    // A switch to extract the data from a given result set.
    switch($item_type) {
        case 'lens':
		
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
				 
				 
			// get company data to switch company IDs for company names
         	$company_data = pnModAPIFunc('Lenses', 'user', 'get', array('item_type'=>'company','item_id'=>$comp_id));

         	// get polymer data to switch polymer IDs for polymer names
         	$polymer_data = pnModAPIFunc('Lenses', 'user', 'get', array('item_type'=>'polymer','item_id'=>$poly_id));
			
		$items_array =  array(	'tid'                   => $tid,
					'name'                  => $name,
					'aliases'               => $aliases,
					'comp_id'               => $comp_id,
					'comp_name'            	=> $company_data[comp_name],
					'logo'               	=> $company_data[logo],
					'comp_address'          => $company_data[address],
					'comp_city'		=> $company_data[city],
					'comp_state'		=> $company_data[state],
					'comp_zip'		=> $company_data[zip],
					'comp_phone'		=> $company_data[phone],
					'comp_url'		=> $company_data[url],
					'comp_email'		=> $company_data[email],
					'comp_desc'		=> $company_data[comp_desc],
                                        'poly_id'               => $poly_id,
					'poly_name'             => $polymer_data[poly_name],
					'fda_grp'		=> $polymer_data[fda_grp],
					'h2o'			=> $polymer_data[h2o],
					'poly_desc'		=> $polymer_data[poly_desc],
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
					'cyl_notes'           	=> $cyl_notes,
			
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
        break;

        case 'company':
            for (; !$result->EOF; $result->MoveNext()) {
                list($comp_tid, $comp_name, $logo, $phone, $address, $city, $state, $zip, $url, $email, $comp_desc) = $result->fields;
                $items_array =  array('comp_tid'      => $comp_tid,
                                            'comp_name'         => $comp_name,
                                            'logo'              => $logo,
                                            'phone'             => $phone,
                                            'address'           => $address,
                                            'city'              => $city,
                                            'state'             => $state,
                                            'zip'               => $zip,
                                            'url'               => $url,
                                            'email'             => $email,
                                            'comp_desc'        => $comp_desc,
                                            );
            }
        break;

        case 'polymer':
            for (; !$result->EOF; $result->MoveNext()) {
                list($poly_tid, $fda_grp, $h2o, $poly_name, $poly_desc) = $result->fields;
                $items_array = array('poly_tid'           => $poly_tid,
												'fda_grp'           => $fda_grp,
												'h2o'               => $h2o,
												'poly_name'         => $poly_name,
												'poly_desc'         => $poly_desc,
												);
            }
        break;

        default:
        break;
    }

    $result->Close();
 	//print_r($items_array."<br/>");
    return $items_array;
}

// ------------------------------------------------------------
//  Get all of a given item from a module table.
// ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_userapi_getall($args)
{
    
	$items_array = array();

    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_OVERVIEW)) {
        return $items_array;
    }

    extract($args);
	



    // Ensure valid values were passed in.
    if (empty($item_type) || !is_string($item_type)) {
        //echo 'HERERE<br />';
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }
	
	

    // Get a reference to the database object.
    $dbconn =& pnDBGetConn(true);

    // Get a reference to PostNuke's table info.
    $pntable =& pnDBGetTables();

    // A switch to choose the proper table.
    switch($item_type)
    {
        case 'lenses':
                $table =& $pntable['lenses'];
                $field =& $pntable['lenses_column'];
				$sql = "SELECT *
                FROM $table
               WHERE $field[tid] > '0'
            ORDER BY $field[name]";
                break;
        case 'companies':
                $table =& $pntable['lenses_companies'];
                $field =& $pntable['lenses_companies_column'];
				$sql = "SELECT *
                FROM $table
               WHERE $field[comp_tid] > '0'
            ORDER BY $field[comp_name]";
                break;
        case 'polymers':
                $table =& $pntable['lenses_polymers'];
                $field =& $pntable['lenses_polymers_column'];
				$sql = "SELECT *
                FROM $table
               WHERE $field[poly_tid] > '0'
            ORDER BY $field[poly_name]";
                break;
        default:break;
    }

//print $sql; //die;
    // Execute the SQL query.
    $result = $dbconn->Execute($sql);



    // Check for any database errors.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _GETFAILED);
        return false;
    }

    // A switch to extract the data from a given result set.
    switch($item_type) {
        case 'lenses':
		
        	// get company data to switch company IDs for company names
         	$company_data = pnModAPIFunc('Lenses', 'user', 'getall', array('item_type'=>'companies'));

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
				 
//print "<br />".$tid." : ".$name." : ".$cyl_notes;

            $items_array[$tid] =  array('tid'                   => $tid,
                                        'name'                  => $name,
                                        'comp_name'             => $company_data[$comp_id][comp_name],
                                        'display'               => $display,
                                       
                                        );
										
										//print_r ($items_array[$tid]);
										//print "<br />";
            }
        break;

        case 'companies':
            for (; !$result->EOF; $result->MoveNext()) {
                list($comp_tid, $comp_name, $logo, $phone, $address, $city, $state, $zip, $url, $email, $comp_desc) = $result->fields;
                $items_array[$comp_tid] =  array('comp_tid'         => $comp_tid,
						'comp_name'         => $comp_name,
						'logo'        	    => $logo,
						'phone'             => $phone,
						'address'           => $address,
						'city'              => $city,
						'state'             => $state,
						'zip'               => $zip,
						'url'               => $url,
						'email'             => $email,
						'comp_desc'         => $comp_desc,
						);
            }
        break;

        case 'polymers':
            for (; !$result->EOF; $result->MoveNext()) {
                list($poly_tid, $fda_grp, $h2o, $poly_name, $poly_desc) = $result->fields;
                $items_array[$poly_tid] = array('poly_tid'                => $poly_tid,
						'fda_grp'          		 => $fda_grp,
						'h2o'               	 => $h2o,
						'poly_name'              => $poly_name,
						'poly_desc'              => $poly_desc,
						);
            }
        break;

        default:
        break;
    }

    $result->Close();
	
	//print "hi";
	//print_r ($items_array[38]);//die;
    //print_r ($result);
    return $items_array;
}


//-------------------------------------------------
// get a list of lenses based on the user's query


function Lenses_userapi_getlist($args)
{
    // Get arguments from argument array 
    extract($args);

	//set up the array that will be returned
	$items=array();
	
	// Security check -
	if (!pnSecAuthAction(0, 'Lenses::',"::", ACCESS_OVERVIEW)) {
       return false;
    }
    // Get datbase setup 
	list($dbconn) = pnDBGetConn();
    
	//get the table info
	$pntable =& pnDBGetTables();
        $table_lens =& $pntable['lenses'];
        $field_lens =& $pntable['lenses_column'];
	
        $table_company =& $pntable['lenses_companies'];
        $field_company =& $pntable['lenses_companies_column'];
	
        $table_polymer =& $pntable['lenses_polymers'];
        $field_polymer =& $pntable['lenses_polymers_column'];
	
	//make the individual sql statments - break the "select" and "where" arrays into 
	//stings.  
	$select_txt="SELECT ";
	$where_txt=" ";

	//print_r($select);

	//first, iterate throught the select array.  
	//For each item, add it to the select_txt string, separating each value by a comma.
	// need to check the table, polymer, and company table field arrays to make sure the item is associated with the proper table.
	$temp=0;
	foreach($select as $value){
		if ($temp!=0)$select_txt.=", "; $temp++;
		if (array_key_exists($value, $field_lens))$select_txt.=$field_lens[$value];
		if (array_key_exists($value, $field_polymer))$select_txt.=$field_polymer[$value];
		if (array_key_exists($value, $field_company))$select_txt.=$field_company[$value];
	}

//iterate throught the and_or array.  each element is an array that will be enclosed in parentheses.  Each array contains an array where the key is a field name and the value is a search value. These items will be separated by the word "OR"
	if (isset($and_or_array)){
		foreach($and_or_array as $and_option){
			$where_txt.=" AND "; $where_count++;
			$where_txt.=" ( ";
			$or_count=0;
			foreach($and_option as $field => $value){
				if ($or_count!=0)$where_txt.=" OR "; $or_count++;
				$where_txt.=$field_lens[$field]." ".$value;
			}
			$where_txt.=") ";
		}
	}
	
	//iterate throught the or array.  This array's keys are field names, and the value is composed of an array of values that will be separated by "OR".  Each element of the array will be enclosed in parantheses
	if (isset($or_array)){
		foreach($or_array as $field => $value_array){
			//figure out which table the field belongs to and give the field variable the proper name
			if (array_key_exists($field, $field_lens))$field_name=$field_lens[$field];
			if (array_key_exists($field, $field_polymer))$field_name=$field_polymer[$field];
			if (array_key_exists($field, $field_company))$field_name=$field_company[$field];
			$where_txt.=" AND "; $where_count++;
			$where_txt.=" ( ";
			$or_count=0;
			foreach($value_array as $value){
				if ($or_count!=0)$where_txt.=" OR "; $or_count++;
				$where_txt.=$field_name."  ".$value;
			}
			$where_txt.=") ";
		}
	}
	
	//iterate throught the "and" array.  This array's keys are field names, and the value is composed of an array of values that will be separated by "AND". 
	if (isset($and_array)){
		foreach($and_array as $field => $value_array){
			//figure out which table the field belongs to and give the field variable the proper name
			if (array_key_exists($field, $field_lens))$field_name=$field_lens[$field];
			if (array_key_exists($field, $field_polymer))$field_name=$field_polymer[$field];
			if (array_key_exists($field, $field_company))$field_name=$field_company[$field];
			foreach($value_array as $value){
				$where_txt.=" AND "; $where_count++;
				$where_txt.=$field_name."  ".$value;
			}
			$where_txt.=" ";
		}
	}
	
	//iterate throught the "select" array.  This array's keys are field names and values are search terms.
	if (isset($search)){
		foreach($search as $field => $value){
			//figure out which table the field belongs to and give the field variable the proper name
			if ($value!="0" && $value!=""){
				if (array_key_exists($field, $field_lens))$field_name=$field_lens[$field];
				if (array_key_exists($field, $field_polymer))$field_name=$field_polymer[$field];
				if (array_key_exists($field, $field_company))$field_name=$field_company[$field];
				$where_txt.=" AND "; $where_count++;
				$where_txt.=$field_name."  ".$value." ";
			}
		}
	}


    // create the sql statement.  If a phrase was passed to this function, then an sql statement will be created to do a full-text search.  This will be done by the user.getlist function when no items are found with a regular search.  The second sql statement is the default and will create a complicated statement from the arrays passed to this function.
	
	if (isset($phrase)) {
		$sql= "$select_txt 
, MATCH (`pn_name`,`pn_aliases`,`pn_process_text`,`pn_replace_text`,`pn_wear`, `pn_markings`, `pn_other_info`, `pn_toric_type`, `pn_bifocal_type`) AGAINST ('$phrase') AS score FROM pn_lenses LEFT JOIN pn_lenses_companies on pn_comp_tid=pn_comp_id LEFT JOIN pn_lenses_polymers on pn_poly_tid= pn_poly_id  WHERE MATCH (`pn_name`,`pn_aliases`,`pn_process_text`,`pn_replace_text`,`pn_wear`, `pn_markings`, `pn_other_info`, `pn_toric_type`, `pn_bifocal_type`) AGAINST ('$phrase') WHERE $table_lens.$field_lens[display]=1 order by score DESC";
	} else {
	
			$sql = "$select_txt
					FROM $table_lens LEFT JOIN $table_company on $table_company.$field_company[comp_tid]=$table_lens.$field_lens[comp_id] LEFT JOIN $table_polymer on $table_polymer.$field_polymer[poly_tid]=$table_lens.$field_lens[poly_id] WHERE $table_lens.$field_lens[display]=1 $where_txt order by $table_company.$field_company[comp_tid], $table_lens.$field_lens[name]";
		}

    if (isset($limit)) $sql.=" LIMIT ".$limit;


    //echo ("<br />".$sql);

    $result =$dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        return false;
    }


//next, iterate through each lens...
  for($i=0; !$result->EOF; $result->MoveNext()) {
		//'row' is array of search results for 1 lens
       $row= list($results) = $result->fields;
//print_r($row);echo("<br/>");
        if (pnSecAuthAction(0, 'ContactLens::', "$name::$id", ACCESS_OVERVIEW)) {
    		//assign select fields as key, row results as value for each lens
			foreach( $select as $key=>$value){
				$items[$i][$value]=$row[$key];
			}
		
        } 
		$i++;
    }

    // Return the item array
    return $items;
}


//next function searches for lenses by company name, returns an array of company names and their IDs
function Lenses_userapi_getcompany($args){
     extract($args);

     // Ensure valid values were passed in.
    if (!isset($phrase_array) ) {
        return false;
    }
     //set up the array that will be returned
	$items=array();
	
      //get the table info
     list($dbconn) = pnDBGetConn();
    $pntable =& pnDBGetTables();
    $table =& $pntable['lenses_companies'];
    $field =& $pntable['lenses_companies_column'];

    //create the sql statement
    $sql = "SELECT $field[comp_tid],$field[comp_name]
                FROM $table WHERE $field[comp_tid] > '0' ";
               
    foreach($phrase_array as $item){
        $sql.=" AND $field[comp_name] LIKE '%$item%' ";
    }
               
    $sql.=" ORDER BY $field[comp_name]";

    $result =$dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        return false;
    }
    
     for (; !$result->EOF; $result->MoveNext()) {
         list($comp_tid, $comp_name) = $result->fields;
         $items_array[] =  array('comp_tid'      => $comp_tid,
                               'comp_name'     => $comp_name);
         }
    //print_r($items_array);
    return $items_array;
}

function Lenses_userapi_getall_options()
{
    $options = array();

    $options['sphere_powers']   = pnModAPIFunc('Lenses', 'user', 'opt_sphere_powers');
    $options['bifocal_powers']  = pnModAPIFunc('Lenses', 'user', 'opt_bifocal_powers');
    $options['cylinder_powers'] = pnModAPIFunc('Lenses', 'user', 'opt_cylinder_powers');
    $options['axes']            = pnModAPIFunc('Lenses', 'user', 'opt_axes');
    $options['axis_steps']      = pnModAPIFunc('Lenses', 'user', 'opt_axis_steps');
    $options['diameters']       = pnModAPIFunc('Lenses', 'user', 'opt_diameters');
    $options['processes']       = pnModAPIFunc('Lenses', 'user', 'opt_processes');
    $options['fda_groups']      = pnModAPIFunc('Lenses', 'user', 'opt_fda_groups');
    $options['styles']          = pnModAPIFunc('Lenses', 'user', 'opt_styles');
    $options['optic_zones']     = pnModAPIFunc('Lenses', 'user', 'opt_optic_zones');
    $options['dks']             = pnModAPIFunc('Lenses', 'user', 'opt_dks');
    $options['replacement']     = pnModAPIFunc('Lenses', 'user', 'opt_replacement_durations');
    $options['thicknesses']     = pnModAPIFunc('Lenses', 'user', 'opt_center_thicknesses');
    $options['basic_colors']    = pnModAPIFunc('Lenses', 'user', 'opt_basic_colors');

    return $options;
}

#################################################################
#                                                               #
#               All form input options are below.               #
#                                                               #
#################################################################

// ------------------------------------------------------------
//  Sphere power options
// ------------------------------------------------------------
function Lenses_userapi_opt_sphere_powers()
{
    $min = -20;
    $max = 20;

    for($i=$min; $i<=$max; $i++)
    {
        if ($i==0)
        {
            $range[0] = 'all';
            continue;
        }

        $range[$i] = $i;
    }

    return $range;
}

// ------------------------------------------------------------
//  Bifocal type options
// ------------------------------------------------------------
function Lenses_userapi_opt_bifocal_types()
{
    return $var = array('aspheric',
                        'aspheric back surface',
                        'aspheric front surface',
                        'concentric, distance center',
                        'concentric, near center',
                        'concentric zones',
                        'diffractive optics',
                        'monovision',
                        'progressive',
                        'translating',
                        'other',
                        'unkown',
                        );
}

// ------------------------------------------------------------
//  Bifocal power options
// ------------------------------------------------------------
function Lenses_userapi_opt_bifocal_powers()
{
    return $var = array('+1.00',
                        '+2.00',
                        '+3.00',
                        '+4.00',
                        );
}

// ------------------------------------------------------------
//  Toric cylinder power options
// ------------------------------------------------------------
function Lenses_userapi_opt_cylinder_powers()
{
    return $var = array('any',
                        -1.5,
                        -2,
                        -3,
                        -4,
                        -5,
                        -6,
                        );
}

// ------------------------------------------------------------
//  Toric axis step options
// ------------------------------------------------------------
function Lenses_userapi_opt_axis_steps()
{
    return $var = array('any',
                        '90/180 +/- 20',
                        '90/180 +/- 30',
                        'full circle',
                        );
}

// ------------------------------------------------------------
//  Toric oblique options
// ------------------------------------------------------------
function Lenses_userapi_opt_obliques()
{
    return $var = array('any',
                        1,
                        5,
                        10,
                        );
}

// ------------------------------------------------------------
//  Sphere diameter options
// ------------------------------------------------------------
function Lenses_userapi_opt_diameters()
{
    return $var = array('any',
                        13.4,
                        13.6,
                        13.8,
                        14.0,
                        14.2,
                        14.4,
                        14.5,
                        15.0,
                        );
}

// ------------------------------------------------------------
//  Polymer FDA groups
// ------------------------------------------------------------
function Lenses_userapi_opt_fda_groups()
{
    return $var = array('any',
                        1,
                        2,
                        3,
                        4,
                        );
}

// ------------------------------------------------------------
//  Process options
// ------------------------------------------------------------
function Lenses_userapi_opt_processes()
{
    return $var = array('any',
                        'molded',
                        'lathe-cut',
                        'spin-cast',
                        );
}

// ------------------------------------------------------------
//  Style options
// ------------------------------------------------------------
function Lenses_userapi_opt_styles()
{
    return $var = array('any',
                        'enhance',
                        'opaque',
                        );
}

// ------------------------------------------------------------
//  DK options
// ------------------------------------------------------------
function Lenses_userapi_opt_dks()
{
    return $var = array('any',
                        5,
                        10,
                        15,
                        20,
                        30,
                        50,
                        100,
                        );

}

// ------------------------------------------------------------
//  Optic zone options
// ------------------------------------------------------------
function Lenses_userapi_opt_optic_zones()
{
    return $var = array('any',
                        5,
                        6,
                        7,
                        8,
                        9,
                        10,
                        11,
                        12,
                        );
}

// ------------------------------------------------------------
//  Replacement duration options
// ------------------------------------------------------------
function Lenses_userapi_opt_replacement_durations()
{
    return $var = array('any',
                        1,
                        7,
                        14,
                        30,
                        60,
                        90,
                        180,
                        365,
                        );
}

// ------------------------------------------------------------
//  Center thickness options
// ------------------------------------------------------------
function Lenses_userapi_opt_center_thicknesses()
{
    return $var = array('any',
                        '0.04',
                        '0.05',
                        '0.06',
                        '0.07',
                        '0.08',
                        '0.09',
                        '0.10',
                        '0.11',
                        '0.12',
                        '0.13',
                        '0.14',
                        '0.15',
                        '0.16',
                        '0.17',
                        '0.18',
                        '0.19',
                        '0.20',
                        '0.21',
                        '0.22',
                        );
}


// ------------------------------------------------------------
//  Basic color options
// ------------------------------------------------------------
function Lenses_userapi_opt_basic_colors()
{
    return $var = array('aqua',
                        'amber',
                        'blue',
                        'brown',
                        'gray',
                        'green',
                        'hazel',
                        'honey',
                        'yellow',
                        'violet',
                        'novelty',
                        );
    return $var;
}

// ------------------------------------------------------------
//  Quick-search options
// ------------------------------------------------------------
function Lenses_userapi_opt_quick_searches()
{
    return $var = array('spherical disposables over -9',
                        'spherical disposables over +6',
                        'toric disposables with cyl over -2.25',
                        'toric disposables with sphere over -6',
                        'all toric multifocals',
                        );
}

// ------------------------------------------------------------
//  Toric type options
// ------------------------------------------------------------
function Lenses_userapi_opt_toric_types()
{
    return $var = array('thin zones',
                        'prism ballast',
                        );
}

// ------------------------------------------------------------
//  Quantity options
// ------------------------------------------------------------
function Lenses_userapi_opt_quantities()
{
    return $var = array('varies',
                        'single lens',
                        '2 pack',
                        '3 pack',
                        '4 pack',
                        '5 pack',
                        '6 pack',
                        'other',
                        );
}

// ------------------------------------------------------------
//  Marking options
// ------------------------------------------------------------
function Lenses_userapi_opt_markings()
{
    return $var = array('single Line',
                        'three lines around 6:00',
                        'lines at 3 and 9:00',
                        'lines at 3, 6, and 9:00',
                        'lines at 6 and 12:00',
                        'dot(s) at 6:00',
                        'dots at 3 and 9:00',
                        'letters at 6:00',
                        );
}


//a utility function that returns an array of descriptions of the different FDA classes
function Lenses_userapi_fda_descriptions(){

      //an array to return
      $fda_desc = array();

      $fda_desc[1] = "Low Water (<50 percent water content), Nonionic Polymers.<br />This group has the greatest resistance to protein deposition due to its lower water content and nonionic nature. Heat, chemical, and hydrogen peroxide care regimens can be used.";

      $fda_desc[2] =  "High Water (greater than 50 percent water content), Nonionic Polymers.<br />The higher water content of this group results in greater protein attraction than with group 1. However, the nonionic polymers reduce the potential for further attraction. Heat disinfection should be avoided because of the high water content. In addition, sorbic acid and potassium sorbate preservatives can discolor the lenses.";

      $fda_desc[3] =  "Low Water (less then 50 percent water content), Ionic Polymers.<br />The lower water content but ionic nature of these polymers results in intermediate protein resistance. Heat, chemical and hydrogen peroxide care systems may be used.";

      $fda_desc[4] =  "High Water (greater then 50 percent water content), Ionic Polymers.<br />Because of the high water content and ionic nature of these polymers they attract more proteins than any other group. It is best to avoid heat disinfection and sorbic acid preservatives.";


     return $fda_desc;
}





// ------------------------------------------------------------
//  Get a certain number of search terms that resulted in zero results
// ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_userapi_zero_report($args)
{

  $time = pnVarCleanFromInput('time');

// Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_OVERVIEW)) {
        return $items_array;
    }

         extract($args);	
		 
	$items_array = array();


    // Get a reference to the database object.
    $dbconn =& pnDBGetConn(true);

    // Get a reference to PostNuke's table info.
    $pntable =& pnDBGetTables();


                $table =& $pntable['lenses_zero'];
                $field =& $pntable['lenses_zero_column'];
				$sql = "SELECT *
                		FROM $table
            			ORDER BY $field[$time] DESC LIMIT 0,40";
              
    // Execute the SQL query.
    $result = $dbconn->Execute($sql);



    // Check for any database errors.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _GETFAILED);
        return false;
    }

    // A switch to extract the data from a given result set.

            for (; !$result->EOF; $result->MoveNext()) {
                list($id, $phrase, $total, $last_month, $this_month, $month) = $result->fields;
                $items_array[] =  array(
						'id'         		=> $id,
						'phrase'         	=> $phrase,
						'total'        	    => $total,
						'last_month'        => $last_month,
						'this_month'        => $this_month,
						'month'             => $month,
						);
            }
       
    

    $result->Close();
	
    return $items_array;
}

// ------------------------------------------------------------
//  Get a certain number of search terms that resulted in zero results
// ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_userapi_search_report($args)
{

  $time = pnVarCleanFromInput('time');

// Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_OVERVIEW)) {
        return $items_array;
    }

         extract($args);	
		 
	$items_array = array();


    // Get a reference to the database object.
    $dbconn =& pnDBGetConn(true);

    // Get a reference to PostNuke's table info.
    $pntable =& pnDBGetTables();


                $table =& $pntable['lenses_stats'];
				$field =& $pntable['lenses_stats_column'];
				$lens_table =& $pntable['lenses'];
                $lens_field =& $pntable['lenses_column'];
				
				$sql = "SELECT $field[id], $lens_field[name],  $field[this_month], $field[last_month], $field[total]
                		FROM $table, $lens_table
						WHERE $field[id] = $lens_field[tid] 
            			ORDER BY $field[$time] DESC LIMIT 0,40";
              
	//print ($sql);
			  
    // Execute the SQL query.
    $result = $dbconn->Execute($sql);



    // Check for any database errors.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _GETFAILED);
        return false;
    }

    // A switch to extract the data from a given result set.

            for (; !$result->EOF; $result->MoveNext()) {
                list($id, $name, $total, $last_month, $this_month  ) = $result->fields;
                $items_array[] =  array(
						'id'         		=> $id,
						'name'         		=> $name,
						'total'        	    => $total,
						'last_month'        => $last_month,
						'this_month'        => $this_month,
						);
            }
       
    

    $result->Close();
	
	//print_r($items_array);
	
    return $items_array;
}

?>
