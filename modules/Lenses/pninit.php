<?php

    // more $datadict info is at http://phplens.com/lens/adodb/docs-datadict.htm

// ----------------------------------------------------------------------
// Lenses_init()
// ----------------------------------------------------------------------
//    This function initializes the Lenses module by defining and creating
//    tables, columns and indexes, as well as populating the module with
//    default contact lens records and important module variables.
//
//    Returns true on success; false on failure.
// ----------------------------------------------------------------------
function Lenses_init()
{
    // Security check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_ADMIN)) {
        pnSessionSetVar('errormsg', _MODULENOAUTH);
        return false;
    }

    // Get reference to database connection and PN tables.
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Create a new data object and define table options.
    $dict =& NewDataDictionary($dbconn);
    $taboptarray =& pnDBGetTableOptions();

    // Table/column references.
    $lenses_table    =& $pntable['lenses'];
    $lenses_field    =& $pntable['lenses_column'];
    $companies_table =& $pntable['lenses_companies'];
    $companies_field =& $pntable['lenses_companies_column'];
    $polymers_table  =& $pntable['lenses_polymers'];
    $polymers_field  =& $pntable['lenses_polymers_column'];
	$stats_table  =& $pntable['lenses_stats'];
    $stats_field  =& $pntable['lenses_stats_column'];
	$zero_table  =& $pntable['lenses_zero'];
    $zero_field  =& $pntable['lenses_zero_column'];

    // TIP: Another way to save on code is to setup all the table information
    //      inside an array first, and then loop through the array afterward,
    //      creating all the module tables with only a single block of table-
    //      creation code. Shaves many, many lines of code off this function.
    //      Though, note that now that the module only has 3 tables, the number
    //      of lines of code saved is far less than previously saved when there
    //      where many tables in this module.

    // Schema for companies table.
    $schema[$companies_table] = "
            $companies_field[comp_tid]          I       AUTO PRIMARY,
            $companies_field[comp_name]         C(50)   NOTNULL DEFAULT '',
            $companies_field[logo]              C(100)  DEFAULT NULL,
            $companies_field[phone]             C(15)   DEFAULT NULL,
            $companies_field[address]           C(100)  DEFAULT NULL,
            $companies_field[city]              C(50)   DEFAULT NULL,
            $companies_field[state]             C(50)   DEFAULT NULL,
            $companies_field[zip]               C(15)   DEFAULT NULL,
            $companies_field[url]               C(100)  DEFAULT NULL,
            $companies_field[email]             C(100)  DEFAULT NULL,
            $companies_field[comp_desc]         X2
            ";
    // Schema for polymers table.
    $schema[$polymers_table] = "
            $polymers_field[poly_tid]           I       AUTO PRIMARY,
            $polymers_field[fda_grp]            I(1)    DEFAULT NULL,
            $polymers_field[h2o]                N(3.1)  DEFAULT NULL,
            $polymers_field[poly_name]          C(50)   DEFAULT NULL,
            $polymers_field[poly_desc]          X2
            ";
			
			// Schema for stats table.
    $schema[$stats_table] = "
            $stats_field[id]           			I(4)    PRIMARY,
            $stats_field[total]            		I(7)  	NOTNULL DEFAULT '',
            $stats_field[last_month]         	I(5)  	NOTNULL DEFAULT '',
            $stats_field[this_month]         	I(5)    NOTNULL DEFAULT '',
			$stats_field[month]         		I(2)    NOTNULL DEFAULT '',
            ";
			
					// Schema for stats table.
    $schema[$zero_table] = "
            $zero_field[id]           			I(4)    AUTO PRIMARY,
			$zero_field[phrase]           		C(40)   AUTO PRIMARY,
            $zero_field[total]            		I(7)  	NOTNULL DEFAULT '',
            $zero_field[last_month]         	I(5)  	NOTNULL DEFAULT '',
            $zero_field[this_month]         	I(5)    NOTNULL DEFAULT '',
			$zero_field[month]         			I(2)    NOTNULL DEFAULT '',
            ";

    $schema[$lenses_table] = "
            $lenses_field[tid]                  I(10)   NOT NULL auto_increment,
            $lenses_field[name]                 C(100)  NOT NULL DEFAULT '',
            $lenses_field[aliases]              X2      NOT NULL,
            $lenses_field[comp_id]              I(4)    DEFAULT NULL,
            $lenses_field[poly_id]              I(4)    DEFAULT NULL,
            $lenses_field[visitint]             L       NOT NULL DEFAULT 1,
            $lenses_field[ew]                   L       NOT NULL DEFAULT 0,
            $lenses_field[ct]                   N(4.2)  DEFAULT NULL,
            $lenses_field[dk]                   N(3.1)  DEFAULT NULL,
            $lenses_field[oz]                   N(3.1)  DEFAULT NULL,
            $lenses_field[process_text]         C(64)   NOT NULL DEFAULT '',
            $lenses_field[process_simple]       C(64)   NOT NULL DEFAULT '',
            $lenses_field[qty]                  C(32)   DEFAULT NULL,
            $lenses_field[replace_simple]       I(3)    NOT NULL DEFAULT 365,
            $lenses_field[replace_text]         C(128)  NOT NULL DEFAULT 'conventional',
            $lenses_field[wear]                 C(64)   NOT NULL DEFAULT 'daily wear',
            $lenses_field[price]                X2      DEFAULT NULL,
            $lenses_field[markings]             C(64)   DEFAULT NULL,
            $lenses_field[fitting_guide]        C(255)  DEFAULT NULL,
            $lenses_field[website]              C(255)  DEFAULT NULL,
            $lenses_field[image]                C(255)  DEFAULT NULL,
            $lenses_field[other_info]           X2,
            $lenses_field[discontinued]         L       NOT NULL DEFAULT 0,
            $lenses_field[display]              L       NOT NULL DEFAULT 1,
            $lenses_field[special]              X2  NOT NULL,
            $lenses_field[bc_simple]            C(30)   DEFAULT NULL,
			$lenses_field[bc_all]               C(100)   DEFAULT NULL,
            $lenses_field[max_plus]             N(4.2)  NOT NULL DEFAULT '0.00',
            $lenses_field[max_minus]            N(4.2)  NOT NULL DEFAULT '0.00',
            $lenses_field[max_diam]             N(4.1)  NOT NULL DEFAULT '0.00',
            $lenses_field[min_diam]             N(4.1)  NOT NULL DEFAULT '0.00',
            $lenses_field[diam_1]               C(90)  NOT NULL DEFAULT '0.00',
            $lenses_field[base_curves_1]        C(90)   NOT NULL DEFAULT '',
            $lenses_field[powers_1]             C(160)  NOT NULL DEFAULT '',
            $lenses_field[diam_2]               C(90)  DEFAULT NULL,
            $lenses_field[base_curves_2]        C(90)   NOT NULL DEFAULT '',
            $lenses_field[powers_2]             C(160)  NOT NULL DEFAULT '',
            $lenses_field[diam_3]               C(90)  DEFAULT NULL,
            $lenses_field[base_curves_3]        C(90)   NOT NULL DEFAULT '',
            $lenses_field[powers_3]             C(160)  NOT NULL DEFAULT '',
			$lenses_field[sph_notes]            C(200)  NOT NULL DEFAULT '',

            $lenses_field[toric]                L       NOT NULL DEFAULT 0,
            $lenses_field[toric_type]           C(128)  NOT NULL DEFAULT '',
            $lenses_field[toric_type_simple]    C(80)   NOT NULL DEFAULT '',
            $lenses_field[cyl_power]            C(128)  NOT NULL DEFAULT '',
            $lenses_field[max_cyl_power]        N(5.2)  DEFAULT NULL,
            $lenses_field[cyl_axis]             C(128)  NOT NULL DEFAULT '',
            $lenses_field[cyl_axis_steps]       L       DEFAULT NULL,
            $lenses_field[oblique]              I(2)    DEFAULT NULL,
			$lenses_field[cyl_notes]            C(200)  NOT NULL DEFAULT '',

            $lenses_field[bifocal]              L       NOT NULL DEFAULT 0,
            $lenses_field[bifocal_type]         C(128)  NOT NULL DEFAULT '',
            $lenses_field[add_text]             C(64)   NOT NULL DEFAULT '',
            $lenses_field[max_add]              N(5.2)  DEFAULT NULL,
            $lenses_field[cosmetic]             L       NOT NULL DEFAULT 0,
            $lenses_field[enh_names]            C(255)  NOT NULL DEFAULT '',
            $lenses_field[enh_names_simple]     C(255)  NOT NULL DEFAULT '',
            $lenses_field[opaque_names]         C(255)  NOT NULL DEFAULT '',
            $lenses_field[opaque_names_simple]  C(255)  NOT NULL DEFAULT '',
            $lenses_field[updated]              D       NOT NULL DEFAULT '2003-01-03'
            ";

    // Now all tables are defined inside $schema, so
    // we let fly with the table-creation loop here.

    // Loop through $schema array.
    foreach ($schema as $table => $fields) {
        // Create SQL for creating a table.
        $sqlarray = $dict->CreateTableSQL($table, $fields, $taboptarray);
        // Run SQL query and check for database error.
        if ($dict->ExecuteSQLArray($sqlarray) != 2) {
            // Set a detailed error message.
            pnSessionSetVar('errormsg', '<strong>Table creation failure in ' . $table . '</strong> - ' . mysql_error());
            // Report failure.
            return false;
        }

        // If no error was encountered in the table creation loop, each table now
        // needs an index for better performance. Since all the tables don't have
        // an identical field to use as an index, a single assigment won't do for
        // all iterations of the loop.  To deal with this, a switch() is used and
        // the indexes defined on a case by case basis.
        switch($table) {
            case    $lenses_table: $index_alias = 'LensIndex';    $index_field = 'pn_name';      break;
            case $companies_table: $index_alias = 'CompanyIndex'; $index_field = 'pn_comp_name'; break;
            case  $polymers_table: $index_alias = 'PolymerIndex'; $index_field = 'pn_poly_name'; break;
            default: 
                pnSessionSetVar('errormsg', '<strong>Case failure in ' . $table . '</strong> - ' . mysql_error());
                return false;
                //break;
        }
        
        // Create the SQL to create a table index.
        $sqlarray = $dict->CreateIndexSQL($index_alias, $table, $index_field);

        // Run SQL query and check for database error.
        if ($dict->ExecuteSQLArray($sqlarray) != 2) {
            // Set a detailed error message.
            pnSessionSetVar('errormsg', '<strong>Error indexing table ' . $table . '</strong> - ' . mysql_error());
            // Report failure.
            return false;
        }
    }



    // Now all the tables are created and indexed.  This next block
    // is to pre-populate the module with the 432 initial records.

    // Define the file that holds the default records to insert.
    $inserts = (dirname(__FILE__)) . '/pnSQL/inserts.sql';

    // Check if such a file exists.
    if(@file_exists($inserts)) {
        // Get all INSERTs from file into an easily queryable array.
        $sqlarray = file($inserts);
        // Run SQL query and check for database error.
        if ($dict->ExecuteSQLArray($sqlarray) != 2) {
            // Set a detailed error message.
            pnSessionSetVar('errormsg', '<strong>Error Inserting Default Data</strong> - ' . mysql_error());
            // Report failure.
            return false;
        }
    }

    // Module installation complete.  Return success.
    return true;
}

// ----------------------------------------------------------------------
// Lenses_delete()
// ----------------------------------------------------------------------
//    This function deletes the Lenses module including all
//    its tables, data, and modula variables.
//
//    Returns true on success; false on failure.
// ----------------------------------------------------------------------
function Lenses_delete()
{
    // Get a reference to the database connection and PN tables.
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Create a new data object.
    $dict =& NewDataDictionary($dbconn);

    // The SQL to delete all module tables is setup inside $schema.
    // Notable is that table names are passed directly by reference
    // instead of pre-assigning the references to an intermediary
    // variable.  Setting up the tables as $schema allows for a loop
    // to delete all tables with only a single block of table-deletion
    // and error-checking code.
    $schema[] = $dict->DropTableSQL(&$pntable['lenses']);
    $schema[] = $dict->DropTableSQL(&$pntable['lenses_companies']);
    $schema[] = $dict->DropTableSQL(&$pntable['lenses_polymers']);

    // Loop through $schema array.
    foreach($schema as $sqlarray) {
        // Run SQL query and check for database error.
        if ($dict->ExecuteSQLArray($sqlarray) != 2) {
            // Set an error message.
            pnSessionSetVar('errormsg', _LENSES_DROP_TABLE_FAILURE);
            // Report failure.
            return false;
        }
    }

    // Delete any lingering module variables.
    pnModDelVar('Lenses');

    // Module deletion successful.  Report success.
    return true;
}

// ----------------------------------------------------------------------
// Lenses_upgrade()
// ----------------------------------------------------------------------
//    This function is used to upgrade modules to successive versions.
//    Not used at this time, but must be present to proper operation.
//
//    Returns true on success; false on failure.
// ----------------------------------------------------------------------
function Lenses_upgrade($oldversion)
{
    // Return success.
    return true;
}

?>

