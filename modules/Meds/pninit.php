<?php

/**
 * Module initialization.
 * 
 * @return  true on successful module initialization, else false.
 */
function Meds_init()
{
    // Get database connection and tables references.
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Assign table/columns used in this module.
    $company_table  =& $pntable['rx_company'];
    $company_field  =& $pntable['rx_company_column'];
    $preserve_table =& $pntable['rx_preserve'];
    $preserve_field =& $pntable['rx_preserve_column'];
    $chem_table     =& $pntable['rx_chem'];
    $chem_field     =& $pntable['rx_chem_column'];
    $moa_table      =& $pntable['rx_moa'];
    $moa_field      =& $pntable['rx_moa_column'];
    $meds_table     =& $pntable['rx_meds'];
    $meds_field     =& $pntable['rx_meds_column'];
    
    // Start a new data object.
    $dict =& NewDataDictionary($dbconn);

    // Get table creation options.
    $options =& pnDBGetTableOptions();

    // Initialize a variable to hold the modules' table/columns schema.
    $schema = array();

    // Adding another table/columns to $schema.
    $schema[$preserve_table] = "
            $preserve_field[pres_id]    I       AUTO PRIMARY,
            $preserve_field[name]       C(48)   NOT NULL DEFAULT '',
            $preserve_field[comments]   X2
            ";
    // Adding a first table/columns to $schema.
    $schema[$company_table] = "
            $company_field[comp_id]     I       AUTO PRIMARY,
            $company_field[name]        C(48)   NOT NULL DEFAULT '',
            $company_field[phone]       C(15)   NOT NULL DEFAULT '',
            $company_field[street]      C(96)   NOT NULL DEFAULT '',
            $company_field[city]        C(48)   NOT NULL DEFAULT '',
            $company_field[state]       C(2)    NOT NULL DEFAULT '',
            $company_field[zip]         C(12)   NOT NULL DEFAULT '',
            $company_field[email]       C(64)   NOT NULL DEFAULT '',
            $company_field[url]         C(64)   NOT NULL DEFAULT '',
            $company_field[comments]    X2
            ";
    // Adding another table/columns to $schema.
    $schema[$chem_table] = "
            $chem_field[chem_id]        I       AUTO PRIMARY,
            $chem_field[name]           C(48)   NOT NULL DEFAULT '',
            $chem_field[moa_id]         I(6)    DEFAULT NULL
            ";
    // Adding another table/columns to $schema.
    $schema[$moa_table] = "
            $moa_field[moa_id]          I       AUTO PRIMARY,
            $moa_field[name]            C(48)   NOT NULL DEFAULT '',
            $moa_field[comments]        X2
            ";
    // Loop through $schema array.
    foreach ($schema as $table => $fields) {
        
        // Assemble each SQL array to CREATE the populated, optioned table.
        $sqlarray = $dict->CreateTableSQL($table, $fields, $options);
        
        // Run SQL query, checking for errors along the way.
        if ($dict->ExecuteSQLArray($sqlarray) != 2) {
            pnSessionSetVar('errormsg', $table.' - '.mysql_error());
            return false;
        }

        // If no error was encountered in the table creation loop, each table now
        // needs an index for better performance. Since all the tables don't have
        // an identical field to use as an index, a single assigment won't do for
        // all iterations of the loop.  To deal with this, a switch() is used and
        // the indexes defined on a case by case basis.
        switch($table) {
            case $preserve_table:   $index_alias='PreserveIndex';   break;
            case $company_table:    $index_alias='CompanyIndex';    break;
            case $chem_table:       $index_alias='ChemIndex';       break;
            case $moa_table:        $index_alias='MOAIndex';        break;
            default:
                pnSessionSetVar('errormsg', $index_alias.' - '.mysql_error());
                $failure = true;
                break;
        }
        
        if ($failure) return;

        $index_field = 'pn_name';
        
        // Create an array of SQL to INDEX the table
        $sqlarray = $dict->CreateIndexSQL($index_alias, $table, $index_field);
        // Run each INDEX query, checking for errors along the way.
        if ($dict->ExecuteSQLArray($sqlarray) != 2) {
            pnSessionSetVar('errormsg', $table.' - '.mysql_error());
            return false;
        }
    }

     // This table requires an "enum" column type, which cannot be
     // handled with the data dictionary object.  This table will have
     // to be built the old way (a la the "Template" module.)
     $sql = "CREATE TABLE $meds_table (
             $meds_field[med_id]                int(10)         NOT NULL auto_increment,
             $meds_field[trade]                 varchar(48)     NOT NULL DEFAULT '',
             $meds_field[comp_id]               int(4)          DEFAULT NULL,
             $meds_field[medType1]              varchar(48)     DEFAULT NULL,
             $meds_field[medType2]              varchar(48)     DEFAULT NULL,
             $meds_field[preg]                  char(1)         NOT NULL DEFAULT '',
             $meds_field[schedule]              varchar(4)      NOT NULL DEFAULT '',
             $meds_field[generic]               enum('yes','no','unknown') DEFAULT NULL,
             $meds_field[image1]                varchar(48)     NOT NULL DEFAULT '',
             $meds_field[image2]                varchar(48)     NOT NULL DEFAULT '',
             $meds_field[dose]                  text,
             $meds_field[peds]                  varchar(32)     DEFAULT NULL,
             $meds_field[ped_text]              varchar(8)      DEFAULT NULL,
             $meds_field[nurse]                 varchar(96)     NOT NULL DEFAULT '',
             $meds_field[pres_id1]              int(4)          DEFAULT NULL,
             $meds_field[pres_id2]              int(4)          DEFAULT NULL,
             $meds_field[comments]              text,
             $meds_field[rxInfo]                varchar(200)    DEFAULT NULL,
             $meds_field[med_url]               varchar(200)    NOT NULL DEFAULT '',
             $meds_field[updated]               varchar(10)     NOT NULL DEFAULT '',
             $meds_field[display]               enum('true','false') DEFAULT 'true',
             $meds_field[conc1]                 varchar(20)     DEFAULT NULL,
             $meds_field[chem_id1]              int(4)          DEFAULT NULL,
             $meds_field[moa_id1]               int(4)          DEFAULT NULL,
             $meds_field[conc2]                 varchar(20)     DEFAULT NULL,
             $meds_field[chem_id2]              int(4)          DEFAULT NULL,
             $meds_field[moa_id2]               int(4)          DEFAULT NULL,
             $meds_field[conc3]                 varchar(20)     DEFAULT NULL,
             $meds_field[chem_id3]              int(4)          DEFAULT NULL,
             $meds_field[moa_id3]               int(4)          DEFAULT NULL,
             $meds_field[conc4]                 varchar(20)     DEFAULT NULL,
             $meds_field[chem_id4]              int(4)          DEFAULT NULL,
             $meds_field[moa_id4]               int(4)          DEFAULT NULL,
             $meds_field[form1]                 enum('capsule','gel','intravenous','ointment','solution','emulsion','suspension','syrup','tablet','unknown','other') DEFAULT NULL,
             $meds_field[size1]                 varchar(20)     NOT NULL DEFAULT '',
             $meds_field[cost1]                 varchar(20)     NOT NULL DEFAULT '',
             $meds_field[form2]                 enum('capsule','gel','intravenous','ointment','solution','emulsion','suspension','syrup','tablet','unknown','other') DEFAULT NULL,
             $meds_field[size2]                 varchar(20)     NOT NULL DEFAULT '',
             $meds_field[cost2]                 varchar(20)     NOT NULL DEFAULT '',
             $meds_field[form3]                 enum('capsule','gel','intravenous','ointment','solution','emulsion','suspension','syrup','tablet','unknown','other') DEFAULT NULL,
             $meds_field[size3]                 varchar(20)     NOT NULL DEFAULT '',
             $meds_field[cost3]                 varchar(20)     NOT NULL DEFAULT '',
             $meds_field[form4]                 enum('capsule','gel','intravenous','ointment','solution','emulsion','suspension','syrup','tablet','unknown','other') DEFAULT NULL,
             $meds_field[size4]                 varchar(20)     NOT NULL DEFAULT '',
             $meds_field[cost4]                 varchar(20)     NOT NULL DEFAULT '',
             PRIMARY KEY($meds_field[med_id]))";
 
     // Execute SQL.
     $dbconn->Execute($sql);
 
     // Check for any db errors.
     if ($dbconn->ErrorNo() != 0) {
         pnSessionSetVar('errormsg', '<strong>' . $meds_table . '</strong> - ' . mysql_error());
         return false;
     }
 
     // Create the SQL to create a table index.
     $sqlarray = $dict->CreateIndexSQL('MedsIndex', $meds_table, 'pn_trade');
 
     // Run SQL query and check for database error.
     if ($dict->ExecuteSQLArray($sqlarray) != 2) {
         // Set a detailed error message.
         pnSessionSetVar('errormsg', '<strong>' . $meds_table . '</strong> - ' . mysql_error());
         // Report failure.
         return false;
     }

    // Set a default number for per-page meds.
    pnModSetVar('Meds', 'per_page', (int)10);
    
    // --------------------------------------------------
    //  POPULATE TABLES WITH DATA
    // --------------------------------------------------
    // Define the path/file that holds the SQL inserts.
    $inserts = (dirname(__FILE__)) . '/pnSQL/inserts.sql';
    // Check if such a file exists and...
    if(@file_exists($inserts)) {
        // ...if so, get all INSERTs from file into an easily queryable array and then...
        $sqlarray = file($inserts);
        // ...run each INSERT query, checking for errors along the way.
        if ($dict->ExecuteSQLArray($sqlarray) != 2) {
            pnSessionSetVar('errormsg', 'Error Inserting Default Data - ' . mysql_error());
            return false;
        }
    }

    // Installation success.
    return true;
}

/**
 * Module upgrade.
 * 
 * @return true in all cases at this time.
 */
function Meds_upgrade($oldversion)
{
    return true;
}

/**
 * Delete module.
 * 
 * @return true on successful module deletion, else false.
 */
function Meds_delete()
{
    // Get database connection and tables references.
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Create a new data object.
    $dict =& NewDataDictionary($dbconn);

    // The SQL to delete all module tables is setup inside $schema.
    // Notable is that table names are passed directly by reference
    // instead of pre-assigning the references to an intermediary
    // variable.  Setting up the tables under $schema allows for a loop
    // to delete all tables with only a single block of table-deletion
    // and error-checking code.
    $schema[] = $dict->DropTableSQL(&$pntable['rx_meds']);
    $schema[] = $dict->DropTableSQL(&$pntable['rx_preserve']);
    $schema[] = $dict->DropTableSQL(&$pntable['rx_company']);
    $schema[] = $dict->DropTableSQL(&$pntable['rx_chem']);
    $schema[] = $dict->DropTableSQL(&$pntable['rx_moa']);

    // Loop through $schema array, executing each and
    // checking for errors along the way.  Fails on error.
    foreach($schema as $sqlarray) {
        if ($dict->ExecuteSQLArray($sqlarray) != 2) {
            pnSessionSetVar('errormsg', _MEDS_INI_DROP_TABLE_FAILURE);
            return false;
        }
    }

    // Delete any lingering module variables.
    pnModDelVar('Meds');

    // Module deleted.
    return true;
}

?>
