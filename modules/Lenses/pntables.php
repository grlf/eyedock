<?php

// ----------------------------------------------------------------------
// Lenses_pntables()
// ----------------------------------------------------------------------
//
// ----------------------------------------------------------------------
function Lenses_pntables()
{
    // Initialize return variable.
    $pntable = array();

    // Prefix for tables.  Or in the case of the main
    // lens table, the entire table name.
    $lenses_table = pnConfigGetVar('prefix') . '_lenses';

    // Define lenses table and columns.
    $pntable['lenses'] = $lenses_table;
    $pntable['lenses_column'] = array(   'tid'                  => 'pn_tid',
                                         'name'                 => 'pn_name',
                                         'aliases'              => 'pn_aliases',
                                         'comp_id'              => 'pn_comp_id',
                                         'poly_id'              => 'pn_poly_id',
                                         'visitint'             => 'pn_visitint',
                                         'ew'                   => 'pn_ew',
                                         'ct'                   => 'pn_ct',
                                         'dk'                   => 'pn_dk',
                                         'oz'                   => 'pn_oz',
                                         'process_text'         => 'pn_process_text',
                                         'process_simple'       => 'pn_process_simple',
                                         'qty'                  => 'pn_qty',
                                         'replace_simple'       => 'pn_replace_simple',
                                         'replace_text'         => 'pn_replace_text',
                                         'wear'                 => 'pn_wear',
                                         'price'                => 'pn_price',
                                         'markings'             => 'pn_markings',
                                         'fitting_guide'        => 'pn_fitting_guide',
                                         'website'              => 'pn_website',
                                         'image'                => 'pn_image',
                                         'other_info'           => 'pn_other_info',
                                         'discontinued'         => 'pn_discontinued',
                                         'display'              => 'pn_display',
                                         'redirect'             => 'pn_redirect',
                                         'bc_simple'            => 'pn_bc_simple',
										 'bc_all'               => 'pn_bc_all',
                                         'max_plus'             => 'pn_max_plus',
                                         'max_minus'            => 'pn_max_minus',
                                         'max_diam'             => 'pn_max_diam',
                                         'min_diam'             => 'pn_min_diam',
                                         'diam_1'               => 'pn_diam_1',
                                         'base_curves_1'        => 'pn_base_curves_1',
                                         'powers_1'             => 'pn_powers_1',
                                         'diam_2'               => 'pn_diam_2',
                                         'base_curves_2'        => 'pn_base_curves_2',
                                         'powers_2'             => 'pn_powers_2',
                                         'diam_3'               => 'pn_diam_3',
                                         'base_curves_3'        => 'pn_base_curves_3',
                                         'powers_3'             => 'pn_powers_3',
										 'sph_notes'            => 'pn_sph_notes',
                                   
                                         'toric'                => 'pn_toric',
                                         'toric_type'           => 'pn_toric_type',
                                         'toric_type_simple'    => 'pn_toric_type_simple',
                                         'cyl_power'            => 'pn_cyl_power',
                                         'max_cyl_power'        => 'pn_max_cyl_power',
                                         'cyl_axis'             => 'pn_cyl_axis',
                                         'cyl_axis_steps'       => 'pn_cyl_axis_steps',
                                         'oblique'              => 'pn_oblique',
										 'cyl_notes'            => 'pn_cyl_notes',
                                      
                                         'bifocal'              => 'pn_bifocal',
                                         'bifocal_type'         => 'pn_bifocal_type',
                                         'add_text'             => 'pn_add_text',
                                         'max_add'              => 'pn_max_add',
                                         'cosmetic'             => 'pn_cosmetic',
                                         'enh_names'            => 'pn_enh_names',
                                         'enh_names_simple'     => 'pn_enh_names_simple',
                                         'opaque_names'         => 'pn_opaque_names',
                                         'opaque_names_simple'  => 'pn_opaque_names_simple',
                                         'updated'              => 'pn_updated',
                                         );

    // Define companies table and columns.
    $pntable['lenses_companies'] = $lenses_table . '_companies';
    $pntable['lenses_companies_column'] = array(
                                        'comp_tid'              => 'pn_comp_tid',
                                        'comp_name'             => 'pn_comp_name',
										'logo'                  => 'pn_logo',
                                        'phone'                 => 'pn_phone',
                                        'address'               => 'pn_address',
                                        'city'                  => 'pn_city',
                                        'state'                 => 'pn_state',
                                        'zip'                   => 'pn_zip',
                                        'url'                   => 'pn_url',
                                        'email'                 => 'pn_email',
                                        'comp_desc'             => 'pn_comp_desc',
                                        );

    // Define polymers table and columns.
    $pntable['lenses_polymers'] = $lenses_table . '_polymers';
    $pntable['lenses_polymers_column'] = array(
                                        'poly_tid'              => 'pn_poly_tid',
                                        'fda_grp'               => 'pn_fda_grp',
                                        'h2o'                   => 'pn_h2o',
                                        'poly_name'             => 'pn_poly_name',
                                        'poly_desc'             => 'pn_poly_desc',
                                        );

    // Define stats table and columns
    $pntable['lenses_stats'] = $lenses_table . '_stats';
    $pntable['lenses_stats_column'] = array(
                                        'id'              		=> 'pn_id',
                                        'total'               	=> 'pn_total',
                                        'last_month'            => 'pn_last_month',
                                        'this_month'            => 'pn_this_month',
										'month'             	=> 'pn_month',
                                        );
										
	// Define zero results table and columns
    $pntable['lenses_zero'] = $lenses_table . '_zero';
    $pntable['lenses_zero_column'] = array(
                                        'id'              		=> 'pn_id',
										'phrase'              	=> 'pn_phrase',
                                        'total'               	=> 'pn_total',
                                        'last_month'            => 'pn_last_month',
                                        'this_month'            => 'pn_this_month',
										'month'             	=> 'pn_month',
                                        );

    // Return entire tables array.
    return $pntable;
}

?>
