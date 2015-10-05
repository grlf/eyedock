<?php

/**
 * Module table references.
 */
function Meds_pntables()
{
    // Initialize the return variable.
    $pntable = array();

    // Get database table prefix.
    $prefix = pnConfigGetVar('prefix');

    // Define main module-table name.
    $meds = $prefix . '_rx';

    $pntable['rx_preserve'] = $meds.'_preserve';
    $pntable['rx_preserve_column'] = array(
                                        'pres_id'       => 'pn_pres_id',
                                        'name'          => 'pn_name',
                                        'comments'      => 'pn_comments');
    // Assign another table name.
    $pntable['rx_company'] = $meds.'_company';
    $pntable['rx_company_column'] = array(
                                        'comp_id'       => 'pn_comp_id',
                                        'name'          => 'pn_name',
                                        'phone'         => 'pn_phone',
                                        'street'        => 'pn_street',
                                        'city'          => 'pn_city',
                                        'state'         => 'pn_state',
                                        'zip'           => 'pn_zip',
                                        'email'         => 'pn_email',
                                        'url'           => 'pn_url',
                                        'comments'      => 'pn_comments');
    // Assign another table name.
    $pntable['rx_chem'] = $meds.'_chem';
    $pntable['rx_chem_column'] = array( 'chem_id'       => 'pn_chem_id',
                                        'name'          => 'pn_name',
                                        'moa_id'        => 'pn_moa_id');
    // Assign another table name.
    $pntable['rx_moa'] = $meds.'_moa';
    $pntable['rx_moa_column'] = array(  'moa_id'        => 'pn_moa_id',
                                        'name'          => 'pn_name',
                                        'comments'      => 'pn_comments');
    $pntable['rx_meds'] = $meds.'_meds';
    $pntable['rx_meds_column'] = array( 'med_id'        => 'pn_med_id',
                                        'trade'         => 'pn_trade',
                                        'comp_id'       => 'pn_comp_id',
                                        'medType1'      => 'pn_medType1',
                                        'medType2'      => 'pn_medType2',
                                        'preg'          => 'pn_preg',
                                        'schedule'      => 'pn_schedule',
                                        'generic'       => 'pn_generic',
                                        'image1'        => 'pn_image1',
                                        'image2'        => 'pn_image2',
                                        'dose'          => 'pn_dose',
                                        'peds'          => 'pn_peds',
                                        'ped_text'      => 'pn_ped_text',
                                        'nurse'         => 'pn_nurse',
                                        'pres_id1'      => 'pn_pres_id1',
                                        'pres_id2'      => 'pn_pres_id2',
                                        'comments'      => 'pn_comments',
                                        'rxInfo'        => 'pn_rxInfo',
                                        'med_url'       => 'pn_med_url',
                                        'updated'       => 'pn_updated',
                                        'display'       => 'pn_display',
                                        'conc1'         => 'pn_conc1',
                                        'chem_id1'      => 'pn_chem_id1',
                                        'moa_id1'       => 'pn_moa_id1',
                                        'conc2'         => 'pn_conc2',
                                        'chem_id2'      => 'pn_chem_id2',
                                        'moa_id2'       => 'pn_moa_id2',
                                        'conc3'         => 'pn_conc3',
                                        'chem_id3'      => 'pn_chem_id3',
                                        'moa_id3'       => 'pn_moa_id3',
                                        'conc4'         => 'pn_conc4',
                                        'chem_id4'      => 'pn_chem_id4',
                                        'moa_id4'       => 'pn_moa_id4',
                                        'form1'         => 'pn_form1',
                                        'size1'         => 'pn_size1',
                                        'cost1'         => 'pn_cost1',
                                        'form2'         => 'pn_form2',
                                        'size2'         => 'pn_size2',
                                        'cost2'         => 'pn_cost2',
                                        'form3'         => 'pn_form3',
                                        'size3'         => 'pn_size3',
                                        'cost3'         => 'pn_cost3',
                                        'form4'         => 'pn_form4',
                                        'size4'         => 'pn_size4',
                                        'cost4'         => 'pn_cost4');
    // Return tables array.
    return $pntable;
}

?>
