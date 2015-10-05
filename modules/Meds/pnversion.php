<?php

/**
 * Module properties.
 */
 
    // ---------------------------------------------------
    // USED BY "Modules" MODULE
    // ---------------------------------------------------
    // Module name, version, description and display name.
    // Module version string may not exceed 10 characters.
    $modversion['name']        = 'Meds';
    $modversion['version']     = '1.0';
    $modversion['description'] = 'Medications';
    $modversion['displayname'] = 'Meds';

    // ---------------------------------------------------
    // USED BY "Credits" MODULE
    // ---------------------------------------------------
    // Changes, credits, help, license, status, author and
    // contact info.  The latter 2 can be comma-separated.
    $modversion['changelog'] = 'pndocs/index.html';
    $modversion['credits']   = 'pndocs/credits.txt';
    $modversion['help']      = 'pndocs/index.html';
    $modversion['license']   = 'pndocs/index.html';
    $modversion['official']  = 0;
    $modversion['author']    = 'John Alarcon';
    $modversion['contact']   = 'http://www.alarconcepts.com/';

    // ---------------------------------------------------
    // USED BY "PostNuke Core"
    // ---------------------------------------------------
    // Officially registered modules should use a value of
    // 1 here; all others should use a value of 0 instead.
    $modversion['admin'] = 1;

    // ---------------------------------------------------
    // USED BY "Permissions" MODULE
    // ---------------------------------------------------
    // Enables this module's permission schema to be shown
    // in Permissions module's popup schema-syntax window.
    $modversion['securityschema'] = array('Meds::' => '::');
    
    // ---------------------------------------------------
    // USED BY "Modules" MODULE
    // ---------------------------------------------------
    // Enforces dependencies.  If this module depends on 
    // (an)other module(s) in any way, that can be defined here.
    //$modversion['dependencies'] = array(
    //                                array('modname'    => '', 
    //                                      'minversion' => '', 
    //                                      'maxversion' => '', 
    //                                      'status'     => ''
    //                                      )
    //                                   );

?>