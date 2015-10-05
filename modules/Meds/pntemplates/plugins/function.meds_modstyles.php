<?php

// ---------------------------------------------------------------------------
//
// ---------------------------------------------------------------------------
function smarty_function_meds_modstyles($params, &$smarty)
{
    // Extract arguments.
    extract($params);
    
    // Unset arguments container.
    unset($params);
    
    // Globalize. 
    global $additional_header;

    // Enable XHTML support in style tag.
    $close = (isset($xhtml)) ? ' /' : '';

    // Create a <link> tag to use the module's style sheet.
    $style = '<link rel="stylesheet" href="modules/Meds/pnstyle/style.css" type="text/css"'.$close.'">';

    // Include the style sheet in the head of the page.
    $additional_header[] = $style;
}

?>
