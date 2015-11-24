<?php

// ------------------------------------------------------------
//  Get any item from a module table.
// ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_userapi_get($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_OVERVIEW)) {
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
                break;
        case 'company':
                $table =& $pntable['lenses_companies'];
                $field =& $pntable['lenses_companies_column'];
                break;
        case 'polymer':
                $table =& $pntable['lenses_polymers'];
                $field =& $pntable['lenses_polymers_column'];
                break;
        default:break;
    }
    
    // SQL string to select the proper sphere.
    $sql = "SELECT *
                FROM $table
               WHERE $field[tid] = '".(int)$tid."'";

    // Execute the SQL query.
    $result = $dbconn->Execute($sql);

    // Check for any database errors.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _GETFAILED);
        return false;
    }

    // A switch to extract the data from a given result set.
    switch($item_type)
    {
        case 'lens':
            list($tid, $name, $aliases, $comp_id, $poly_id, $visitint, $ew, $ct, $dk, $oz, $qty, $price, $wear, $markings, $process_simple, $process_text, $replace_simple, $replace_text, $website, $fitting_guide, $image, $discontinued, $display, $redirect, $keywords, $other_info, $bc_flat, $bc_med, $bc_steep, $max_plus, $max_minus, $max_diam, $min_diam, $diam_1, $powers_1, $base_curves_1, $diam_2, $powers_2, $base_curves_2, $diam_3, $powers_3, $base_curves_3, $sphere_alt, $bifocal, $bifocal_type, $bifocal_type_simple, $add_text, $max_add, $toric, $toric_type, $toric_type_simple, $cyl_power, $max_cyl_power, $cyl_axis, $cyl_axis_steps, $oblique, $cyl_alt, $cosmetic, $enh_names, $opaque_names, $e_aqua, $e_amber, $e_blue, $e_brown, $e_gray, $e_green, $e_hazel, $e_honey, $e_violet, $e_sports, $e_novelty, $o_aqua, $o_amber, $o_blue, $o_brown, $o_gray, $o_green, $o_hazel, $o_honey, $o_violet, $o_sports, $o_novelty, $color_images, $updated) = $result->fields;

            $item_array = array('tid'                   => $tid,
                                'name'                  => $name,
                                'aliases'               => $aliases,
                                'comp_id'               => $comp_id,
                                'poly_id'               => $poly_id,
                                'visitint'              => $visitint,
                                'ew'                    => $ew,
                                'ct'                    => $ct,
                                'dk'                    => $dk,
                                'oz'                    => $oz,
                                'qty'                   => $qty,
                                'price'                 => $price,
                                'wear'                  => $wear,
                                'markings'              => $markings,
                                'process_simple'        => $process_simple,
                                'process_text'          => $process_text,
                                'replace_simple'        => $replace_simple,
                                'replace_text'          => $replace_text,
                                'website'               => $website,
                                'fitting_guide'         => $fitting_guide,
                                'image'                 => $image,
                                'discontinued'          => $discontinued,
                                'display'               => $display,
                                'redirect'              => $redirect,
                                'keywords'              => $keywords,
                                'other_info'            => $other_info,
                                'bc_flat'               => $bc_flat,
                                'bc_med'                => $bc_med,
                                'bc_steep'              => $bc_steep,
                                'max_plus'              => $max_plus,
                                'max_minus'             => $max_minus,
                                'max_diam'              => $max_diam,
                                'min_diam'              => $min_diam,
                                'diam_1'                => $diam_1,
                                'powers_1'              => $powers_1,
                                'base_curves_1'         => $base_curves_1,
                                'diam_2'                => $diam_2,
                                'powers_2'              => $powers_2,
                                'base_curves_2'         => $base_curves_2,
                                'diam_3'                => $diam_3,
                                'powers_3'              => $powers_3,
                                'base_curves_3'         => $base_curves_3,
                                'sphere_alt'            => $sphere_alt,
                                'bifocal'               => $bifocal,
                                'bifocal_type'          => $bifocal_type,
                                'bifocal_type_simple'   => $bifocal_type_simple,
                                'add_text'              => $add_text,
                                'max_add'               => $max_add,
                                'toric'                 => $toric,
                                'toric_type'            => $toric_type,
                                'toric_type_simple'     => $toric_type_simple,
                                'cyl_power'             => $cyl_power,
                                'max_cyl_power'         => $max_cyl_power,
                                'cyl_axis'              => $cyl_axis,
                                'cyl_axis_steps'        => $cyl_axis_steps,
                                'oblique'               => $oblique,
                                'cyl_alt'               => $cyl_alt,
                                'cosmetic'              => $cosmetic,
                                'enh_names'             => $enh_names,
                                'opaque_names'          => $opaque_names,
                                'e_aqua'                => $e_aqua,
                                'e_amber'               => $e_amber,
                                'e_blue'                => $e_blue,
                                'e_brown'               => $e_brown,
                                'e_gray'                => $e_gray,
                                'e_green'               => $e_green,
                                'e_hazel'               => $e_hazel,
                                'e_honey'               => $e_honey,
                                'e_violet'              => $e_violet,
                                'e_sports'              => $e_sports,
                                'e_novelty'             => $e_novelty,
                                'o_aqua'                => $o_aqua,
                                'o_amber'               => $o_amber,
                                'o_blue'                => $o_blue,
                                'o_brown'               => $o_brown,
                                'o_gray'                => $o_gray,
                                'o_green'               => $o_green,
                                'o_hazel'               => $o_hazel,
                                'o_honey'               => $o_honey,
                                'o_violet'              => $o_violet,
                                'o_sports'              => $o_sports,
                                'o_novelty'             => $o_novelty,
                                'color_images'          => $color_images,
                                'updated'               => $updated,
                                );break;

        case 'company':
                list($tid, $name, $address, $city, $state, $zip, $phone, $email, $url, $desc) = $result->fields;
                $item_array = array('tid'               => $tid,
                                    'name'              => $name,
                                    'address'           => $address,
                                    'city'              => $city,
                                    'state'             => $state,
                                    'zip'               => $zip,
                                    'phone'             => $phone,
                                    'email'             => $email,
                                    'url'               => $url,
                                    'desc'              => $desc,
                                    );break;
        case 'polymer':
                list($tid, $fda_grp, $h2o, $name, $desc) = $result->fields;
                $item_array = array('tid'               => $tid,
                                    'fda_grp'           => $fda_grp,
                                    'h2o'               => $h2o,
                                    'name'              => $name,
                                    'desc'              => $desc,
                                    );break;
        default:break;
    }

    // Extract data from result set.

    // Assign data to a neat array for return.

    // Close the result set.
    $result->Close();

    // Return an array of info on the sphere.
    return $item_array;
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
        echo 'HERERE<br />';
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
                break;
        case 'companies':
                $table =& $pntable['lenses_companies'];
                $field =& $pntable['lenses_companies_column'];
                break;
        case 'polymers':
                $table =& $pntable['lenses_polymers'];
                $field =& $pntable['lenses_polymers_column'];
                break;
        default:break;
    }

    // SQL string to select the proper sphere.
    $sql = "SELECT *
                FROM $table
               WHERE $field[tid] > '0'
            ORDER BY $field[tid]";

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
            for (; !$result->EOF; $result->MoveNext()) {
            list($tid, $name, $aliases, $comp_id, $poly_id, $visitint, $ew, $ct, $dk, $oz, $qty, $price, $wear, $markings, $process_simple, $process_text, $replace_simple, $replace_text, $website, $fitting_guide, $image, $discontinued, $display, $redirect, $keywords, $other_info, $bc_flat, $bc_med, $bc_steep, $max_plus, $max_minus, $max_diam, $min_diam, $diam_1, $powers_1, $base_curves_1, $diam_2, $powers_2, $base_curves_2, $diam_3, $powers_3, $base_curves_3, $sphere_alt, $bifocal, $bifocal_type, $add_text, $max_add, $toric, $toric_type, $toric_type_simple, $cyl_power, $max_cyl_power, $cyl_axis, $cyl_axis_steps, $oblique, $cyl_alt, $cosmetic, $enh_names, $opaque_names, $e_aqua, $e_amber, $e_blue, $e_brown, $e_gray, $e_green, $e_hazel, $e_honey, $e_violet, $e_sports, $e_novelty, $o_aqua, $o_amber, $o_blue, $o_brown, $o_gray, $o_green, $o_hazel, $o_honey, $o_violet, $o_sports, $o_novelty, $color_images, $updated) = $result->fields;
            $items_array[$tid] = array( 'tid'                   => $tid,
                                        'name'                  => $name,
                                        'aliases'               => $aliases,
                                        'comp_id'               => $comp_id,
                                        'poly_id'               => $poly_id,
                                        'visitint'              => $visitint,
                                        'ew'                    => $ew,
                                        'ct'                    => $ct,
                                        'dk'                    => $dk,
                                        'oz'                    => $oz,
                                        'qty'                   => $qty,
                                        'price'                 => $price,
                                        'wear'                  => $wear,
                                        'markings'              => $markings,
                                        'process_simple'        => $process_simple,
                                        'process_text'          => $process_text,
                                        'replace_simple'        => $replace_simple,
                                        'replace_text'          => $replace_text,
                                        'website'               => $website,
                                        'fitting_guide'         => $fitting_guide,
                                        'image'                 => $image,
                                        'discontinued'          => $discontinued,
                                        'display'               => $display,
                                        'redirect'              => $redirect,
                                        'keywords'              => $keywords,
                                        'other_info'            => $other_info,
                                        'bc_flat'               => $bc_flat,
                                        'bc_med'                => $bc_med,
                                        'bc_steep'              => $bc_steep,
                                        'max_plus'              => $max_plus,
                                        'max_minus'             => $max_minus,
                                        'max_diam'              => $max_diam,
                                        'min_diam'              => $min_diam,
                                        'diam_1'                => $diam_1,
                                        'powers_1'              => $powers_1,
                                        'base_curves_1'         => $base_curves_1,
                                        'diam_2'                => $diam_2,
                                        'powers_2'              => $powers_2,
                                        'base_curves_2'         => $base_curves_2,
                                        'diam_3'                => $diam_3,
                                        'powers_3'              => $powers_3,
                                        'base_curves_3'         => $base_curves_3,
                                        'sphere_alt'            => $sphere_alt,
                                        'bifocal'               => $bifocal,
                                        'bifocal_type'          => $bifocal_type,
                                        'bifocal_type_simple'   => $bifocal_type_simple,
                                        'add_text'              => $add_text,
                                        'max_add'               => $max_add,
                                        'toric'                 => $toric,
                                        'toric_type'            => $toric_type,
                                        'toric_type_simple'     => $toric_type_simple,
                                        'cyl_power'             => $cyl_power,
                                        'max_cyl_power'         => $max_cyl_power,
                                        'cyl_axis'              => $cyl_axis,
                                        'cyl_axis_steps'        => $cyl_axis_steps,
                                        'oblique'               => $oblique,
                                        'cyl_alt'               => $cyl_alt,
                                        'cosmetic'              => $cosmetic,
                                        'enh_names'             => $enh_names,
                                        'opaque_names'          => $opaque_names,
                                        'e_aqua'                => $e_aqua,
                                        'e_amber'               => $e_amber,
                                        'e_blue'                => $e_blue,
                                        'e_brown'               => $e_brown,
                                        'e_gray'                => $e_gray,
                                        'e_green'               => $e_green,
                                        'e_hazel'               => $e_hazel,
                                        'e_honey'               => $e_honey,
                                        'e_violet'              => $e_violet,
                                        'e_sports'              => $e_sports,
                                        'e_novelty'             => $e_novelty,
                                        'o_aqua'                => $o_aqua,
                                        'o_amber'               => $o_amber,
                                        'o_blue'                => $o_blue,
                                        'o_brown'               => $o_brown,
                                        'o_gray'                => $o_gray,
                                        'o_green'               => $o_green,
                                        'o_hazel'               => $o_hazel,
                                        'o_honey'               => $o_honey,
                                        'o_violet'              => $o_violet,
                                        'o_sports'              => $o_sports,
                                        'o_novelty'             => $o_novelty,
                                        'color_images'          => $color_images,
                                        'updated'               => $updated,
                                        );
            }
        break;

        case 'companies':
            for (; !$result->EOF; $result->MoveNext()) {
                list($tid, $name, $address, $city, $state, $zip, $phone, $email, $url, $desc) = $result->fields;
                $items_array[$tid] =  array('tid'               => $tid,
                                            'name'              => $name,
                                            'address'           => $address,
                                            'city'              => $city,
                                            'state'             => $state,
                                            'zip'               => $zip,
                                            'phone'             => $phone,
                                            'email'             => $email,
                                            'url'               => $url,
                                            'desc'              => $desc,
                                            );
            }
        break;
            
        case 'polymers':
            for (; !$result->EOF; $result->MoveNext()) {
                list($tid, $fda_grp, $h2o, $name, $desc) = $result->fields;
                $items_array[$tid] = array('tid'                => $tid,
                                            'fda_grp'           => $fda_grp,
                                            'h2o'               => $h2o,
                                            'name'              => $name,
                                            'desc'              => $desc,
                                            );
            }
        break;

        default:
        break;
    }
    
    $result->Close();

    return $items_array;
}

// ------------------------------------------------------------
//  Get all options to populate dropdowns in user/admin forms.
// ------------------------------------------------------------
//
// ------------------------------------------------------------
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
                        '0.06',
                        '0.08',
                        '0.1',
                        '0.12',
                        '0.14',
                        '0.16',
                        '0.18',
                        '0.2',
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

?>
