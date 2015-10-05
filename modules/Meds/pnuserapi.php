<?php

    // Functions in this file interact directly with
    // the database in user capacities.  They are
    // used for 'get' operations.

/**
 *  Get a single item from the database.
 * 
 *  @param  $object STRING  required    Table to select from: med, moa, chem, company or preserve
 *  @param  $id     INT     required    ID of item to select.
 *  @return An array of data for the single item retrieved.
 */
function Meds_userapi_get($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_OVERVIEW)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Get argument.  $object is synonymous with "use this table"
    $object = $args['object'];

    // Ensure that $object was passed in.
    if (empty($object) || !is_string($object)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }
    
    // Since each table has a differently named id field,
    // assign the field's id-name based on $object.
    if ($object == 'med')       { $id_field = 'med_id'; }
    if ($object == 'moa')       { $id_field = 'moa_id'; }
    if ($object == 'company')   { $id_field = 'comp_id'; }
    if ($object == 'preserve')  { $id_field = 'pres_id'; }
    if ($object == 'chem')      { $id_field = 'chem_id'; }
    
    // Only now is the id cleaned from the args array (because only
    // now do we know what id field to use for this operation.
    $id = $args[$id_field];
    
    // Ensure that assigning the $id worked.
    if (empty($id) || !is_numeric($id)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }
    
    // Prepping input for further use.
    $object = (string)pnVarPrepForStore($object);
    $id     =    (int)pnVarPrepForStore($id);
    
    // Get database connection and tables references.
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Define which tables/columns to use, based on $object.
    switch($object) {
        case 'med':     $table =& $pntable['rx_meds'];
                        $field =& $pntable['rx_meds_column'];
                        break;
        case 'chem':    $table =& $pntable['rx_chem'];
                        $field =& $pntable['rx_chem_column'];
                        break;
        case 'moa':     $table =& $pntable['rx_moa'];
                        $field =& $pntable['rx_moa_column'];
                        break;
        case 'preserve':$table =& $pntable['rx_preserve'];
                        $field =& $pntable['rx_preserve_column'];
                        break;
        case 'company': $table =& $pntable['rx_company'];
                        $field =& $pntable['rx_company_column'];
                        break;
        default: break;
    }

    // Create SQL to select $object from $table based on $id_field.
    $sql = "SELECT * FROM $table WHERE $field[$id_field] = '$id'";
    
    // Execute query.
    $result = $dbconn->Execute($sql);

    // Check for database errors.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _GETFAILED);
        return false;
    }

    // Extract data from $result set, based on $object retrieved.
    switch($object) {
        case 'med':    
            list($med_id,$trade,$comp_id,$medType1,$medType2,$preg,$schedule,$generic,$image1,$image2,$dose,$peds,$ped_text,$nurse,$pres_id1,$pres_id2,$comments,$rxInfo,$med_url,$updated,$display,$conc1,$chem_id1,$moa_id1,$conc2,$chem_id2,$moa_id2,$conc3,$chem_id3,$moa_id3,$conc4,$chem_id4,$moa_id4,$form1,$size1,$cost1,$form2,$size2,$cost2,$form3,$size3,$cost3,$form4,$size4,$cost4) = $result->fields;
            $item = array('med_id'=>$med_id,'trade'=>$trade,'comp_id'=>$comp_id,'medType1'=>$medType1,'medType2'=>$medType2,'preg'=>$preg,'schedule'=>$schedule,'generic'=>$generic,'image1'=>$image1,'image2'=>$image2,'dose'=>$dose,'peds'=>$peds,'ped_text'=>$ped_text,'nurse'=>$nurse,'pres_id1'=>$pres_id1,'pres_id2'=>$pres_id2,'comments'=>$comments,'rxInfo'=>$rxInfo,'med_url'=>$med_url,'updated'=>$updated,'display'=>$display,'conc1'=>$conc1,'chem_id1'=>$chem_id1,'moa_id1'=>$moa_id1,'conc2'=>$conc2,'chem_id2'=>$chem_id2,'moa_id2'=>$moa_id2,'conc3'=>$conc3,'chem_id3'=>$chem_id3,'moa_id3'=>$moa_id3,'conc4'=>$conc4,'chem_id4'=>$chem_id4,'moa_id4'=>$moa_id4,'form1'=>$form1,'size1'=>$size1,'cost1'=>$cost1,'form2'=>$form2,'size2'=>$size2,'cost2'=>$cost2,'form3'=>$form3,'size3'=>$size3,'cost3'=>$cost3,'form4'=>$form4,'size4'=>$size4,'cost4'=>$cost4);
            break;
        case 'chem':    
            list($chem_id, $name, $moa_id) = $result->fields;
            $item = array('chem_id'=>$chem_id,'name'=>$name,'moa_id'=>$moa_id);
            break;
        case 'moa':     
            list($moa_id, $name, $comments) = $result->fields;
            $item = array('moa_id'=>$moa_id,'name'=>$name,'comments'=>$comments);
            break;
        case 'preserve':
            list($pres_id, $name, $comments) = $result->fields;
            $item = array('pres_id'=>$pres_id,'name'=>$name,'comments'=>$comments);
            break;
        case 'company': 
            list($comp_id, $name, $phone, $street, $city, $state, $zip, $email, $url, $comments) = $result->fields;
            $item = array('comp_id'=>$comp_id,'name'=>$name,'phone'=>$phone,'street'=>$street,'city'=>$city,'state'=>$state,'zip'=>$zip,'email'=>$email,'url'=>$url,'comments'=>$comments);
            break;
        default: break;
    }
    
    // Close $result set.
    $result->Close();

    // Return retrieved item.
    return $item;
}

/** 
 *  Get all of a given item from a given table.
 * 
 *  @param  $object STRING  required    Table to select from: med, moa, chem, company or preserve
 *  @param  $start  INT     optional    Number to start selecting at.
 *  @param  $limit  INT     optional    Limits results returned; default no limit. 
 *  @param  $count  STRING  optional    A flag to count a given item; default 0. 
 *  @return An array of all items retrieved.
 */
function Meds_userapi_getall($args)
{
    // Initialize the return variable early on.
    $items = array();

    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_OVERVIEW)) {
        return $items;
    }

    // Get arguments to this function.
    $object = (string)$args['object']; // Which table to get items from.
    $start  =    (int)$args['start'];  // What result to start selecting items from.
    $limit  =    (int)$args['limit'];  // How many items to select.
    $count  = (string)$args['count'];  // If !empty, do not limit results.
    
    // Optional: which type of objects to get; default to 'med'.
    if (empty($object) || !is_string($object)) {
        $object = 'med';
    }

    // Optional: Number to start selecting from; default to 1.
    if (empty($start) || !is_numeric($start)) {
        $start = 1;
    }

    // Optional: Limit items to get; default to -1 (no limit).
    if (empty($limit) || !is_numeric($limit)) {
        $limit  = -1;
    }
    
    // CHECK: If object is not a 'med', do not limit the results.
    if ($object != 'med') {
        $limit  = -1;
    }
    
    // CHECK: If $count exists, whether any/all/none of the other $args are set, 
    // any limit is over-ridden here; $count should be one of the following:
    // 'company', 'preserve', 'chem', 'moa', 'med' or else an error will be returned.
    if (!empty($count)) {
        $limit = -1;
        if (!in_array($count, array('company', 'preserve', 'chem', 'moa', 'med'))) {
            pnSessionSetVar('errormsg', _MODARGSERROR);
            return false;
        }
        $object = $count;
    }
        
    // Get database connection and tables references.
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Define which tables/columns to use, based on $object.
    switch($object) {
        case 'med':
            $table =& $pntable['rx_meds'];
            $field =& $pntable['rx_meds_column'];
            $order =  'trade';
            break;
        case 'chem':
            $table =& $pntable['rx_chem'];
            $field =& $pntable['rx_chem_column'];
            $order =  'name';
            break;
        case 'moa':
            $table =& $pntable['rx_moa'];
            $field =& $pntable['rx_moa_column'];
            $order =  'name';
            break;
        case 'preserve':
            $table =& $pntable['rx_preserve'];
            $field =& $pntable['rx_preserve_column'];
            $order =  'name';
            break;
        case 'company':
            $table =& $pntable['rx_company'];
            $field =& $pntable['rx_company_column'];
            $order =  'name';
            break;
        default: break;
    }
    
    // Create SQL to select all results from table.
    $sql = "SELECT * FROM $table ORDER BY $field[$order]";
    
    // Execute query.
    $result = $dbconn->SelectLimit($sql, $limit, $start-1);

    // Check for database error.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _GETFAILED);
        return false;
    }

    // Loop through $result set.
    for (; !$result->EOF; $result->MoveNext()) {
        // Extract data from $result set, based on $object retrieved.
        switch($object) {
            case 'med':    
                list($med_id,$trade,$comp_id,$medType1,$medType2,$preg,$schedule,$generic,$image1,$image2,$dose,$peds,$ped_text,$nurse,$pres_id1,$pres_id2,$comments,$rxInfo,$med_url,$updated,$display,$conc1,$chem_id1,$moa_id1,$conc2,$chem_id2,$moa_id2,$conc3,$chem_id3,$moa_id3,$conc4,$chem_id4,$moa_id4,$form1,$size1,$cost1,$form2,$size2,$cost2,$form3,$size3,$cost3,$form4,$size4,$cost4) = $result->fields;
                $items[] = array('med_id'=>$med_id,'trade'=>$trade,'comp_id'=>$comp_id,'medType1'=>$medType1,'medType2'=>$medType2,'preg'=>$preg,'schedule'=>$schedule,'generic'=>$generic,'image1'=>$image1,'image2'=>$image2,'dose'=>$dose,'peds'=>$peds,'ped_text'=>$ped_text,'nurse'=>$nurse,'pres_id1'=>$pres_id1,'pres_id2'=>$pres_id2,'comments'=>$comments,'rxInfo'=>$rxInfo,'med_url'=>$med_url,'updated'=>$updated,'display'=>$display,'conc1'=>$conc1,'chem_id1'=>$chem_id1,'moa_id1'=>$moa_id1,'conc2'=>$conc2,'chem_id2'=>$chem_id2,'moa_id2'=>$moa_id2,'conc3'=>$conc3,'chem_id3'=>$chem_id3,'moa_id3'=>$moa_id3,'conc4'=>$conc4,'chem_id4'=>$chem_id4,'moa_id4'=>$moa_id4,'form1'=>$form1,'size1'=>$size1,'cost1'=>$cost1,'form2'=>$form2,'size2'=>$size2,'cost2'=>$cost2,'form3'=>$form3,'size3'=>$size3,'cost3'=>$cost3,'form4'=>$form4,'size4'=>$size4,'cost4'=>$cost4);
                break;
            case 'chem':    
                list($chem_id, $name, $moa_id) = $result->fields;
                $items[] = array('chem_id'=>$chem_id,'name'=>$name,'moa_id'=>$moa_id);
                break;
            case 'moa':     
                list($moa_id, $name, $comments) = $result->fields;
                $items[] = array('moa_id'=>$moa_id,'name'=>$name,'comments'=>$comments);
                break;
            case 'preserve':
                list($pres_id, $name, $comments) = $result->fields;
                $items[] = array('pres_id'=>$pres_id,'name'=>$name,'comments'=>$comments);
                break;
            case 'company': 
                list($comp_id, $name, $phone, $street, $city, $state, $zip, $email, $url, $comments) = $result->fields;
                $items[] = array('comp_id'=>$comp_id,'name'=>$name,'phone'=>$phone,'street'=>$street,'city'=>$city,'state'=>$state,'zip'=>$zip,'email'=>$email,'url'=>$url,'comments'=>$comments);
                break;
            default: break;
        }
    }

    // Close result set.
    $result->Close();

    // Return retrieved items.
    return $items;
}






//utility function. Accepts phrase as $args, looks for chemical names that contain this phrase and returns an array of chem ids
function Meds_userapi_search_chem_name($args){

         $phrase = $args['search']['trade'];

         //initialize an array to return
         $ids = array();

         // Get database connection and pn tables.
         $dbconn  =& pnDBGetConn(true);
         $pntable =& pnDBGetTables();

     
         //chem table / column references
         $chem_table =& $pntable['rx_chem'];
         $chem_field =& $pntable['rx_chem_column'];

         $chem_sql = "SELECT $chem_field[chem_id] FROM $chem_table
                      WHERE $chem_field[name] LIKE '%$phrase%' ";
//echo "<!--$chem_sql-->";
         $chem_ids = $dbconn->Execute($chem_sql);

         

         // Loop through result set.
        for (; !$chem_ids->EOF; $chem_ids->MoveNext()) {
            // Extract the data from the result set.
            list($ids[]) = $chem_ids->fields;
        }
       return $ids;
       //die;
}


/** 
 *  Search for data and return a result.
 * 
 *  @param  $search ARRAY   required    Array of search data.
 *  @return An array of search results.
 */

function Meds_userapi_search_result($args)
{

    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_OVERVIEW)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Get the array of search (dropdown) inputs.
    $search = $args['search'];

    // Ensure default argument.
    if (!$search || empty($search) || !is_array($search)) {
        $search[trade] = $_POST["q"];
	$search[type]="phrase";

    }

    if (empty($search) || !is_array($search)) {
        return false;
    }

    //echo ($search);

    //set up the array to return
    $search_result=array();

    //Get arrays of all company and chemical information.  These will be matched with the search results later
    $all_comp = pnModAPIFunc('Meds', 'user', 'DBselect', array('from'=>'company'));
    $all_chem = pnModAPIFunc('Meds', 'user', 'DBselect', array('from'=>'chem'));

    
    // Get database connection and pn tables.
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // meds table and column references.
    $meds_table =& $pntable['rx_meds'];
    $meds_field =& $pntable['rx_meds_column'];


    //start creating the SQL statement
      $sql = "SELECT $meds_field[med_id], $meds_field[trade], $meds_field[medType1], $meds_field[medType2], $meds_field[comp_id], $meds_field[chem_id1], $meds_field[chem_id2], $meds_field[chem_id3], $meds_field[chem_id4]  FROM $meds_table WHERE $meds_field[display] = 'true' ";


    //if searching for a trade name, also search by chemical names.  Get an array of chemical name IDs that match the submitted phrase.
    if ($search[trade]){
          $sql.=" AND ($meds_field[trade] LIKE '%$search[trade]%' ";
      
          //if searching for a trade name, will also use this phrase to search for a chemical name
          $chem_ids = pnModAPIFunc('Meds', 'user', 'search_chem_name', array('search'=>$search));
          foreach($chem_ids as $value){
               $sql.=" OR $meds_field[chem_id1] = $value ";
               $sql.=" OR $meds_field[chem_id2] = $value ";
               $sql.=" OR $meds_field[chem_id3] = $value ";
               $sql.=" OR $meds_field[chem_id4] = $value ";
          }
          $sql.= " ) ";
    }

     // next, we'll cycle through the rest of the fields the user may be searching by and add to the SQL statement.


     //if searching for a pregnancy rating
     if ($search[preg]){
          $sql.=" AND $meds_field[preg] LIKE '%$search[preg]%'";
     }
     
     if ($search[generic]){
          $sql.=" AND $meds_field[generic] LIKE '%$search[generic]%'";
     }
     //if searching by company ID
     if ($search[comp_id]){
          $sql.=" AND $meds_field[comp_id] = $search[comp_id]";
     }
     //if searching by med type
     if ($search[medType]){
          $sql.=" AND ($meds_field[medType1] LIKE  '%$search[medType]%' OR $meds_field[medType2] LIKE '%$search[medType]%' )";
     }
     //if searching by preservative ID
     if ($search[pres_id]){
          $sql.=" AND ($meds_field[pres_id1] = $search[pres_id] OR $meds_field[pres_id2] = $search[pres_id] )";
     }
     //if searching by moa ID
     if ($search[moa_id]){
          $sql.=" AND ($meds_field[moa_id1] = $search[moa_id] OR $meds_field[moa_id2] = $search[moa_id] OR $meds_field[moa_id3] = $search[moa_id] OR $meds_field[moa_id4] = $search[moa_id])";
     }

        
        //finish off the SQL statement
        $sql.="  ORDER BY $meds_field[trade]";

  //echo("<p>".$sql."</p>");  die;

//echo "<!--$sql-->";
        // Execute query.
        $result = $dbconn->Execute($sql);

        //***********************************

        // Check for database error.
        if ($dbconn->ErrorNo() != 0) {
            pnSessionSetVar('errormsg', _GETFAILED);
            return false;
        }

        
        // Loop through result set.
        for (; !$result->EOF; $result->MoveNext()) {
            // Extract the data from the result set.
            list($med_id, $trade, $medType1, $medType2, $comp_id, $chem_id1, $chem_id2, $chem_id3, $chem_id4) = $result->fields;
                // Assign results to the return array.
                $search_result[] = array('med_id'    => $med_id,
                                       'trade'     => $trade,
                                       'medType1'  => $medType1,                    
                                       'medType2'  => $medType2,
                                       'comp'   => $all_comp[$comp_id],
                                       'chem1'  => $all_chem[$chem_id1],
                                       'chem2'  => $all_chem[$chem_id2],
                                       'chem3'  => $all_chem[$chem_id3],
                                       'chem4'  => $all_chem[$chem_id4]
                                                           );                    
        }
    //print_r($search_result);

    // If there are no search results, return false.
    if (empty($search_result)) {
        return false;
    }
    
    // Return search result.
    return $search_result;
}


/**
 * ============================================================
 *  ALL FUNCTIONS BELOW ARE HELPERS FOR CREATING POPUP MESSAGES.
 * ============================================================
 */
 function Meds_userapi_preg_descriptions(){
    
    //create array to return
    $pregs = array();
    
    $pregs ['A']= " Class A: <i>Controlled studies show no risk</i>. Adequate, well-controlled studies in pregnant women have failed to demonstrate a risk to the fetus in any trimester of pregnancy.";
    
    $pregs['B'] = "Class B: <i>No evidence of risk in humans.</i>  Adequate, well-controlled studies in pregnant women have not shown increased risk of fetal abnormalities despite adverse findings in animals, or, in the absence of adequate human studies, animal studies show no fetal risk. The chance of fetal harm is remote, but remains a possibility.";

    $pregs['C']= "Class C: <i>Risk cannot be ruled out</i>.  Adequate, well-controlled human studies are lacking, and animal studies have shown a risk to the fetus or are lacking as well. There is a chance of fetal harm if the drug is administered during pregnancy; but the potential benefits may outweigh the potential risks.";

    $pregs['D'] = "Class D: <i>Positive evidence of risk</i>.  Studies in humans, or investigational or post-marketing data, have demonstrated fetal risk. Nevertheless, potential benefits from the use of the drug may outweigh the potential risk. For example, the drug may be acceptable if needed in a life-threatening situation or serious disease for which safer drugs cannot be used or are ineffective.";

    $pregs['X'] = "Class X: <i>Contraindicated in pregnancy.</i>  Studies in animals or humans, or investigational or post-marketing reports, have demonstrated positive evidence of fetal abnormalities or risks which clearly outweighs any possible benefit to the patient.";
    
    return $pregs;

}


function Meds_userapi_sched_descriptions(){
  
    //create array to return
    $sched = array();

      $sched['I'] = "Schedule I drugs have no acceptable medical use in the united states and may not be prescribed.";

      $sched['II'] = "Schedule II drugs have a high potential for abuse and cannot be refilled.  In addition, These drugs cannot be prescribed by telephone.";

      $sched['III'] = "Schedule III drugs have moderate potential for dependence.  They can be refilled, but there is a five-refill maximum and the prescription is invalid after 6 months.";

      $sched['IV'] = "Schedule IV drugs have low potential for dependence.  They can be refilled, but there is a five-refill maximum and the prescription is invalid after 6 months.";

      $sched['V'] = "Schedule V drugs have the lowest abuse potential.  They can be dispensed without a prescription if the patient is 18 years old, distribution is by a pharmacist, and only a limited quantity of drug is purchased.";

      return $sched;
}


/**
 * ============================================================
 *  ALL FUNCTIONS BELOW ARE HELPERS FOR POPULATING DROPDOWNS.
 * ============================================================
 */

/**
 * Get all dropdown options from both DB and API.
 * 
 * @return multidimensional array of all dropdown options.
 */
// Get all dropdown options; both from API and DB.  No arguments.
function Meds_userapi_getall_selects()
{
    $selects = array();
    $selects['preserves']   = pnModAPIFunc('Meds', 'user', 'DBselect', array('from'=>'preserve'));
    $selects['companies']   = pnModAPIFunc('Meds', 'user', 'DBselect', array('from'=>'company'));
    $selects['chemicals']   = pnModAPIFunc('Meds', 'user', 'DBselect', array('from'=>'chem'));
    $selects['methods']     = pnModAPIFunc('Meds', 'user', 'DBselect', array('from'=>'moa'));
    $selects['medtypes']    = pnModAPIFunc('Meds', 'user', 'select_medtypes');
    $selects['medforms']    = pnModAPIFunc('Meds', 'user', 'select_medforms');
    $selects['pregclasses'] = pnModAPIFunc('Meds', 'user', 'select_pregclasses');
    $selects['schedules']   = pnModAPIFunc('Meds', 'user', 'select_schedule');
    $selects['generics']    = pnModAPIFunc('Meds', 'user', 'select_generics');
    $selects['peds']        = pnModAPIFunc('Meds', 'user', 'select_peds');
    $selects['ped_texts']   = pnModAPIFunc('Meds', 'user', 'select_pedtexts');
    return $selects;    
}

/** 
 * Selects all of a given item from database.
 * 
 * @param  $from   STRING  required    table name to select items from.
 * @return array of options for dropdowns. 
 */
function Meds_userapi_DBselect($args)
{
    // Initialize the return variable early on.
    $select = array();
    
    // Permission check.
    if (!pnSecAuthAction(0, 'Meds::', '::', ACCESS_OVERVIEW)) {
        return $select;
    }
    
    // Define table to select from. (comparable to $object in other functions)
    $from = (string)$args['from'];
    
    // Define tables that can be selected from for dropdowns.
    $tables = array('chem', 'company', 'moa', 'preserve');
    
    // Ensure a valid table name was passed.
    if (!in_array($from, $tables)) {
        pnSessionSetVar('errormsg', 'Error selecting table from database.');
        return false;
    }
        
    // Get database connection and tables references.
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    
    // Dynamically create the table/field references based on $from.
    $table =& $pntable['rx_'.$from];
    $field =& $pntable['rx_'.$from.'_column'];
    
    // Dynamically create the $id_field to select by.
    $id_field = substr($from, 0, 4) . '_id';
    
    // Create SQL to select the id and name of the item.
    $sql = "SELECT $field[$id_field],
                   $field[name]
              FROM $table
          ORDER BY $field[name]";

    // Execute query.
    $result = $dbconn->Execute($sql);
    
    // Check for database errors.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _GETFAILED);
        return false;
    }
    
    // Loop through $result set.
    for (; !$result->EOF; $result->MoveNext()) {
        // Extract data from result set.
        list($id, $name) = $result->fields;
        // Assign the data to the select array.
        $select[$id] = array($id_field => $id, 'name' => $name);
    }
    
    // Close $result set.
    $result->Close();
    
    // Return.
    return $select;
}

/**
 * Get options for medType dropdown.
 *  
 * @return array of options for dropdowns.
 */
// Get dropdown options for medication types.  No arguments.
function Meds_userapi_select_medtypes()
{
  $select = array(1 => 'allergy',
                         'analgesics',
                         'anti-infective',
						 'antibiotic',
						 'antifungal',
						 'antiviral',
                         'combo steroid / antibiotic',
                         'corticosteroids',
                         'diagnostic',
                         'dry eye',
                         'glaucoma',
                         'NSAID',
                         'vitamin or mineral supplement',
                    0 => 'other');
    return $select;
}



/**
 * Get options for pregnancy classes dropdown.
 *  
 * @return array of options for dropdowns.
 */
// Get dropdown options for pregnancy classes.  No arguments.
function Meds_userapi_select_pregclasses()
{
    return $select = array('A', 'B', 'C', 'D', 'X');
}

/**
 * Get options for FDA schedule dropdown.
 *  
 * @return array of options for dropdowns.
 */
// Get dropdown options for FDA schedule.  No arguments.
function Meds_userapi_select_schedule()
{
    return $select = array('OTC', 'I', 'II', 'III', 'IV', 'V');
}

/**
 * Get options for generics dropdown.
 *  
 * @return array of options for dropdowns.
 */
// Get dropdown options for generics.  No arguments.
function Meds_userapi_select_generics()
{
    return $select = array('yes', 'no', 'unknown');
}

/**
 * Get options for peds dropdown.
 *  
 * @return array of options for dropdowns.
 */
// Get dropdown options for peds value.  No arguments.
function Meds_userapi_select_peds()
{
    $letter = array('Not Established', 'Neonate');
    $number = range(1, 18);
    return $select = array_merge($letter, $number);
}

/**
 * Get options for ped_text dropdown.
 *  
 * @return array of options for dropdowns.
 */
// Get dropdown options for ped text.  No arguments.
function Meds_userapi_select_pedtexts()
{
    return $select = array('years', 'months', 'days');
}

/**
 * Get options for medforms dropdown.
 *  
 * @return array of options for dropdowns.
 */
// Get dropdown options for medication forms.  No arguments.
function Meds_userapi_select_medforms()
{
    return $select = array('capsule', 'gel', 'intravenous', 'ointment', 'solution', 'emulsion','suspension', 'syrup', 'tablet', 'unknown', 'other');
}

?>