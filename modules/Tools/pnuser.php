<?php

#=============================================================#
#        __   _   __      _____    __  __   ____   ____       #
#       /  | | | |  \    |  __ \  |  \/  | / __ \ |  _ \      #
#      /   | | | |   \   | |  \ | |      || /  \ || | \ \     #
#     / /| | | | | |\ \  | |__/ | | |\/| || \__/ || |_/ /     #
#    / /_| | | | | |_\ \ |  _  /  |_|  |_| \____/ |____/      #
#   / ___  | | | |  ___ \| | \ \                              #
#  / /   | | | | | |   \ | |  \ \    =================        #
# / /    | | | |_| |_   \  |   \ \   *   AUTHENTIC   *        #
# \/     |_| |_______|   \/     \/   =================        #
#                                                             #
#=============================================================#
#     Code By: John Alarcon                                   #
#     Mail To: mods@alarconcepts.com                          #
#     Site At: http://www.alarconcepts.com                    #
#                                                             #
# This module is the copyrighted (2006) work of John Alarcon. #
# If you use this module, you do so at your own risk. Period. #
#=============================================================#


// --------------------------------------------------
//  User main
// --------------------------------------------------
//
// --------------------------------------------------
function Tools_user_main()
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Tools::', '::', ACCESS_OVERVIEW)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Create a new output object.
    $pnRender =& new pnRender('Tools');

    // Return template.
    return $pnRender->fetch('tools_user.htm');
}

// --------------------------------------------------
//
// --------------------------------------------------
//
// --------------------------------------------------



// --------------------------------------------------
//
// --------------------------------------------------
//
// --------------------------------------------------



// --------------------------------------------------
//
// --------------------------------------------------
//
// --------------------------------------------------



// --------------------------------------------------
//
// --------------------------------------------------
//
// --------------------------------------------------



// --------------------------------------------------
//
// --------------------------------------------------
//
// --------------------------------------------------



// --------------------------------------------------
//
// --------------------------------------------------
//
// --------------------------------------------------



// --------------------------------------------------
//
// --------------------------------------------------
//
// --------------------------------------------------



// --------------------------------------------------
//
// --------------------------------------------------
//
// --------------------------------------------------
function Tools_user_display($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Tools::', '::', ACCESS_OVERVIEW)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
    
    // Clean tool from input.
    $tool = pnVarCleanFromInput('tool');
    
    // White-list approach; we only allow these values as $tool.
    $toolbox = array('contact_lens','conversions','crt','incentives','keratometer','oblique','parks','oblique_javascript');

    // Ensure proper value is passed in or default it.
    if (!in_array($tool, $toolbox)) {
        return pnVarPrepHTMLDisplay('Invalid Tool Specified');
    }

    // Create a new output object.
    $pnRender =& new pnRender('Tools');

    // Return template.
    return $pnRender->fetch('tools_user_display_'.(string)$tool.'.htm');
}


// --------------------------------------------------
//
// --------------------------------------------------
//
// --------------------------------------------------



// --------------------------------------------------
//  Explain things
// --------------------------------------------------
//  Simply displays whatever HTML is located in 
//  the 'explain' template for the given $tool.
// --------------------------------------------------
function Tools_user_explain($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Tools::', '::', ACCESS_OVERVIEW)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
    
    // Clean tool from input.
    $tool = pnVarCleanFromInput('tool');
    
    // White-list approach; we only allow these values as $tool.
    $explanations = array('contact_lens','conversions','crt','incentives','keratometer','oblique','parks');

    // Ensure proper value is passed in or default it.
    if (!in_array($tool, $explanations)) {
        $tool = $explanations[0];
        //return pnVarPrepHTMLDisplay('Invalid Tool Specified');
    }

    // Create a new output object.
    $pnRender =& new pnRender('Tools');

    // Return template.
    return $pnRender->fetch('tools_user_explain_'.(string)$tool.'.htm');
}

?>
