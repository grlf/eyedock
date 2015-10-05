<?php

// ---------------------------------------------------------------------------
//
// ---------------------------------------------------------------------------
function smarty_function_lenses_modstyles($params, &$smarty)
{
    // Globalize.
    global $additional_header;

    // Enable XHTML support in style tag.
    $close = (isset($xhtml)) ? ' /' : '';

    // Create a <link> tag to use the module's style sheet.
    $css = '<link rel="stylesheet" href="modules/Lenses/pnstyle/style.css" type="text/css"'.$close.' />';

    // Include the style sheet in the head of the page.
    $additional_header[] = $css;
}

?>
