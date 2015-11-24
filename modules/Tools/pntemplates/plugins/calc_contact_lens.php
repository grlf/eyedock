<?php

    require_once('func_inc_lenses.php');
    require_once('func_inc_companies.php');
    require_once('func_inc_polymers.php');

function Lenses_admin_main()
{
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $pnRender =& new pnRender('Lenses');

    return $pnRender->fetch('lenses_admin.htm');
}



// ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_admin_delete($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Clean $tid from input.
    list($tid, $item_type, $confirmation) = pnVarCleanFromInput('tid', 'item_type', 'confirmation');

    // Extract any extra arguments.
    extract($args);

    // Ensure valid values were passed in.
    if (empty($tid) || !is_numeric($tid) ||
        empty($item_type) || !is_string($item_type)) {
        return pnVarPrepHTMLDisplay('Invalid id or item type.');
    }

    // Check if confirmation is empty.  If so, start up a new
    // output object and show the confirmation template.
    if (empty($confirmation)) {
        $pnRender =& new pnRender('Lenses');
        $pnRender->assign('tid', $tid);
        $pnRender->assign('item_type', $item_type);
        return $pnRender->fetch('lenses_admin_delete.htm');
    }
    
    // Confirm $authid hidden field from form template.
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Lenses', 'admin', 'main'));
    }

    // Attempt to delete item.
    if (pnModAPIFunc('Lenses', 'admin', 'delete', array('tid' => $tid, 'item_type' => $item_type))) {
        pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_DELETESUCCEDED));
    }

    // No output.  Redirect user.
    return pnRedirect(pnModURL('Lenses', 'admin', 'main'));
}

// ------------------------------------------------------------
//
// ------------------------------------------------------------
function Lenses_adminapi_delete($args)
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Lenses::', '::', ACCESS_ADMIN)) {
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

    // Call API function to verify bifocal type exists.
    $item_exists = pnModAPIFunc('Lenses', 'user', 'get', array('tid' => $tid, 'item_type' => $item_type));

    // Verify sphere exists.
    if (!$item_exists) {
        pnSessionSetVar('errormsg', _NOSUCHITEM);
        return false;
    }

    // Get a reference to the database object.
    $dbconn =& pnDBGetConn(true);

    // Get a reference to PostNuke's table info.
    $pntable =& pnDBGetTables();

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

    // Create an sql query to delete the sphere.
    $sql = "DELETE FROM $table WHERE $field[tid] = '".(int)$tid."'";

    // Execute the SQL query.
    $result = $dbconn->Execute($sql);

    // Check for any database errors.
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _DELETEFAILED);
        return false;
    }

    // Start a new output object.
    // This function isn't an output function, but needs an output
    // object started before the cache can be cleared.
    $pnRender =& new pnRender('Lenses');

    // Clear the cache.
    $pnRender->clear_cache();

    // Return success.
    return true;
}











/**
 * form to add new item
 *
 * This is a standard function that is called whenever an administrator
 * wishes to create a new module item
 *
 * @author       The PostNuke Development Team
 * @return       output       The main module admin page.
 */
function Example_admin_new()
{
    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Example::', '::', ACCESS_ADD)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
    $pnRender =& new pnRender('Example');

    // We need the pnsecgenauthkey plugin, so we must not cache here.
    $pnRender->caching = false;

    // Return the output that has been generated by this function
    return $pnRender->fetch('example_admin_new.htm');
}

/**
 * Create an item
 *
 * This is a standard function that is called with the results of the
 * form supplied by Example_admin_new() to create a new item
 *
 * @author       The PostNuke Development Team
 * @param        name         the name of the item to be created
 * @param        number       the number of the item to be created
 */
function Example_admin_create($args)
{
    // Get parameters from whatever input we need.  All arguments to this
    // function should be obtained from pnVarCleanFromInput(), getting them
    // from other places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke

    // ** Please note pnVarCleanFromInput will always return a set variable, even
    // it's empty so isset() checking is not appropriate.

    list($itemname,
         $number) = pnVarCleanFromInput('itemname',
                                        'number');

    // Admin functions of this type can be called by other modules.  If this
    // happens then the calling module will be able to pass in arguments to
    // this function through the $args parameter.  Hence we extract these
    // arguments *after* we have obtained any form-based input through
    // pnVarCleanFromInput().
    extract($args);

    // Confirm authorisation code.  This checks that the form had a valid
    // authorisation code attached to it.  If it did not then the function will
    // proceed no further as it is possible that this is an attempt at sending
    // in false data to the system
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Example', 'admin', 'view'));
    }

    // Notable by its absence there is no security check here.  This is because
    // the security check is carried out within the API function and as such we
    // do not duplicate the work here

    // The API function is called.  Note that the name of the API function and
    // the name of this function are identical, this helps a lot when
    // programming more complex modules.  The arguments to the function are
    // passed in as their own arguments array
    $tid = pnModAPIFunc('Example',
                        'admin',
                        'create',
                        array('itemname' => $itemname,
                              'number' => $number));

    // The return value of the function is checked here, and if the function
    // suceeded then an appropriate message is posted.  Note that if the
    // function did not succeed then the API function should have already
    // posted a failure message so no action is required
    if ($tid) {
        // Success
        pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_CREATESUCCEDED));
    }

    // This function generated no output, and so now it is complete we redirect
    // the user to an appropriate page for them to carry on their work
    return pnRedirect(pnModURL('Example', 'admin', 'view'));
}


/**
 * modify an item
 *
 * This is a standard function that is called whenever an administrator
 * wishes to modify a current module item
 *
 * @author       The PostNuke Development Team
 * @param        tid          the id of the item to be modified
 * @return       output       the modification page
 */
function Example_admin_modify($args)
{
    // Get parameters from whatever input we need.  All arguments to this
    // function should be obtained from pnVarCleanFromInput(), getting them
    // from other places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    list($tid,
         $objectid)= pnVarCleanFromInput('tid',
                                         'objectid');

    // Admin functions of this type can be called by other modules.  If this
    // happens then the calling module will be able to pass in arguments to
    // this function through the $args parameter.  Hence we extract these
    // arguments *after* we have obtained any form-based input through
    // pnVarCleanFromInput().
    extract($args);

    // At this stage we check to see if we have been passed $objectid, the
    // generic item identifier.  This could have been passed in by a hook or
    // through some other function calling this as part of a larger module, but
    // if it exists it overrides $tid
    // Where ever possible all modules should support the passing of a generic
    // object id. Many hook functions will recieve the object id (via the extrainfo
    // array) but not the actual variable reference itself thus we need all API's
    // to take the generic object id specifier. If all modules used objectid
    // generally the interoperation of modules would be significantly
    // simplified.
    //
    // Note that this module couuld just use $objectid everywhere to avoid all
    // of this munging of variables, but then the resultant code is less
    // descriptive, especially where multiple objects are being used.  The
    // decision of which of these ways to go is up to the module developer
    if (!empty($objectid)) {
        $tid = $objectid;
    }

    // The user API function is called.  This takes the item ID which we
    // obtained from the input and gets us the information on the appropriate
    // item.  If the item does not exist we post an appropriate message and
    // return
    $item = pnModAPIFunc('Example',
                         'user',
                         'get',
                         array('tid' => $tid));

    if (!$item) {
        return pnVarPrepHTMLDisplay(_NOSUCHITEM);
    }

    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing.  However,
    // in this case we had to wait until we could obtain the item name to
    // complete the instance information so this is the first chance we get to
    // do the check
    if (!pnSecAuthAction(0, 'Example::', "$item[itemname]::$tid", ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
    $pnRender =& new pnRender('Example');

    // As Admin output changes often, we do not want caching.
    $pnRender->caching = false;

    // Add a hidden variable for the item id.  This needs to be passed on to
    // the update function so that it knows which item for which item to carry
    // out the update
    $pnRender->assign('tid', $tid);

    // For the assignment of name and number we can just assign the associative
    // array $item.
    $pnRender->assign($item);

    // Return the output that has been generated by this function
    return $pnRender->fetch('example_admin_modify.htm');
}


/**
 * Modify the item
 *
 * This is a standard function that is called with the results of the
 * form supplied by Example_admin_modify() to update a current item
 *
 * @author       The PostNuke Development Team
 * @param        tid          the id of the item to be modified
 * @param        name         the name of the item to be updated
 * @param        number       the number of the item to be updated
 */
function Example_admin_update($args)
{
    // Get parameters from whatever input we need.  All arguments to this
    // function should be obtained from pnVarCleanFromInput(), getting them
    // from other places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    list($tid,
         $objectid,
         $itemname,
         $number) = pnVarCleanFromInput('tid',
                                        'objectid',
                                        'itemname',
                                        'number');

    // User functions of this type can be called by other modules.  If this
    // happens then the calling module will be able to pass in arguments to
    // this function through the $args parameter.  Hence we extract these
    // arguments *after* we have obtained any form-based input through
    // pnVarCleanFromInput().
    extract($args);

    // At this stage we check to see if we have been passed $objectid, the
    // generic item identifier.  This could have been passed in by a hook or
    // through some other function calling this as part of a larger module, but
    // if it exists it overrides $tid
    // Where ever possible all modules should support the passing of a generic
    // object id. Many hook functions will recieve the object id (via the extrainfo
    // array) but not the actual variable reference itself thus we need all API's
    // to take the generic object id specifier. If all modules used objectid
    // generally the interoperation of modules would be significantly
    // simplified.
    //
    // Note that this module couuld just use $objectid everywhere to avoid all
    // of this munging of variables, but then the resultant code is less
    // descriptive, especially where multiple objects are being used.  The
    // decision of which of these ways to go is up to the module developer
    if (!empty($objectid)) {
        $tid = $objectid;
    }

    // Confirm authorisation code.  This checks that the form had a valid
    // authorisation code attached to it.  If it did not then the function will
    // proceed no further as it is possible that this is an attempt at sending
    // in false data to the system
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Example', 'admin', 'view'));
    }

    // Notable by its absence there is no security check here.  This is because
    // the security check is carried out within the API function and as such we
    // do not duplicate the work here

    // The API function is called.  Note that the name of the API function and
    // the name of this function are identical, this helps a lot when
    // programming more complex modules.  The arguments to the function are
    // passed in as their own arguments array.
    //
    // The return value of the function is checked here, and if the function
    // suceeded then an appropriate message is posted.  Note that if the
    // function did not succeed then the API function should have already
    // posted a failure message so no action is required
    if(pnModAPIFunc('Example',
                    'admin',
                    'update',
                    array('tid' => $tid,
                          'itemname' => $itemname,
                          'number' => $number))) {
        // Success
        pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_UPDATESUCCEDED));
    }

    // This function generated no output, and so now it is complete we redirect
    // the user to an appropriate page for them to carry on their work
    return pnRedirect(pnModURL('Example', 'admin', 'view'));
}

/**
 * delete item
 *
 * This is a standard function that is called whenever an administrator
 * wishes to delete a current module item.  Note that this function is
 * the equivalent of both of the modify() and update() functions above as
 * it both creates a form and processes its output.  This is fine for
 * simpler functions, but for more complex operations such as creation and
 * modification it is generally easier to separate them into separate
 * functions.  There is no requirement in the PostNuke MDG to do one or the
 * other, so either or both can be used as seen appropriate by the module
 * developer
 *
 * @author       The PostNuke Development Team
 * @param        tid            the id of the item to be modified
 * @param        confirmation   confirmation that this item can be deleted
 */
function Example_admin_delete($args)
{
    // Get parameters from whatever input we need.  All arguments to this
    // function should be obtained from pnVarCleanFromInput(), getting them
    // from other places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    list($tid,
         $objectid,
         $confirmation) = pnVarCleanFromInput('tid',
                                              'objectid',
                                              'confirmation');


    // User functions of this type can be called by other modules.  If this
    // happens then the calling module will be able to pass in arguments to
    // this function through the $args parameter.  Hence we extract these
    // arguments *after* we have obtained any form-based input through
    // pnVarCleanFromInput().
    extract($args);

    // At this stage we check to see if we have been passed $objectid, the
    // generic item identifier.  This could have been passed in by a hook or
    // through some other function calling this as part of a larger module, but
    // if it exists it overrides $tid
    // Where ever possible all modules should support the passing of a generic
    // object id. Many hook functions will recieve the object id (via the extrainfo
    // array) but not the actual variable reference itself thus we need all API's
    // to take the generic object id specifier. If all modules used objectid
    // generally the interoperation of modules would be significantly
    // simplified.
    //
    // Note that this module couuld just use $objectid everywhere to avoid all
    // of this munging of variables, but then the resultant code is less
    // descriptive, especially where multiple objects are being used.  The
    // decision of which of these ways to go is up to the module developer
    if (!empty($objectid)) {
        $tid = $objectid;
    }

    // The user API function is called.  This takes the item ID which we
    // obtained from the input and gets us the information on the appropriate
    // item.  If the item does not exist we post an appropriate message and
    // return
    $item = pnModAPIFunc('Example',
                         'user',
                         'get',
                         array('tid' => $tid));

    if (!$item) {
        return pnVarPrepHTMLDisplay(_NOSUCHITEM);
    }

    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing.  However,
    // in this case we had to wait until we could obtain the item name to
    // complete the instance information so this is the first chance we get to
    // do the check
    if (!pnSecAuthAction(0, 'Example::', "$item[itemname]::$tid", ACCESS_DELETE)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Check for confirmation.
    if (empty($confirmation)) {
        // No confirmation yet - display a suitable form to obtain confirmation
        // of this action from the user

        // Create output object - this object will store all of our output so that
        // we can return it easily when required
        $pnRender =& new pnRender('Example');

        // As Admin output changes often, we do not want caching.
        $pnRender->caching = false;

        // Add a hidden field for the item ID to the output
        $pnRender->assign('tid', $tid);

        // Return the output that has been generated by this function
        return $pnRender->fetch('example_admin_delete.htm');
    }

    // If we get here it means that the user has confirmed the action

    // Confirm authorisation code.  This checks that the form had a valid
    // authorisation code attached to it.  If it did not then the function will
    // proceed no further as it is possible that this is an attempt at sending
    // in false data to the system
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Example', 'admin', 'view'));
    }

    // The API function is called.  Note that the name of the API function and
    // the name of this function are identical, this helps a lot when
    // programming more complex modules.  The arguments to the function are
    // passed in as their own arguments array.
    //
    // The return value of the function is checked here, and if the function
    // suceeded then an appropriate message is posted.  Note that if the
    // function did not succeed then the API function should have already
    // posted a failure message so no action is required
    if (pnModAPIFunc('Example',
                     'admin',
                     'delete',
                     array('tid' => $tid))) {
        // Success
        pnSessionSetVar('statusmsg', pnVarPrepHTMLDisplay(_DELETESUCCEDED));
    }

    // This function generated no output, and so now it is complete we redirect
    // the user to an appropriate page for them to carry on their work
    return pnRedirect(pnModURL('Example', 'admin', 'view'));
}


/**
 * view items
 *
 * This function shows all items and lists the administration
 * options.
 *
 * @author       The PostNuke Development Team
 * @param        startnum     The number of the first item to show
 * @return       output       The main module admin page
 */
function Example_admin_view()
{
    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Example::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Get parameters from whatever input we need.  All arguments to this
    // function should be obtained from pnVarCleanFromInput(), getting them
    // from other places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    $startnum = pnVarCleanFromInput('startnum');

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
    $pnRender =& new pnRender('Example');

    // As Admin output changes often, we do not want caching.
    $pnRender->caching = false;

    // we need this value multiple times, so we keep it
    $itemsperpage = pnModGetVar('Example', 'itemsperpage');

    // The user API function is called.  This takes the number of items
    // required and the first number in the list of all items, which we
    // obtained from the input and gets us the information on the appropriate
    // items.
    $items = pnModAPIFunc('Example',
                          'user',
                          'getall',
                          array('startnum' => $startnum,
                                'numitems' => $itemsperpage));

    // Loop through each returned item adding in the options that the user has over
    // each item based on the permissions the user has.
    // The key is used to add the options array back into the original array as the
    // forach structure operates on a copy of the original data.
    foreach ($items as $key => $item) {

        if (pnSecAuthAction(0, 'Example::', "$item[itemname]::$item[tid]", ACCESS_READ)) {

            // Options for the item.  Note that each item has the appropriate
            // levels of authentication checked to ensure that it is suitable
            // for display. Note unlike other module urls and language defines
            // we define these in the code rather than the Example so permissions
            // can be checked
            $options = array();
            if (pnSecAuthAction(0, 'Example::', "$item[itemname]::$item[tid]", ACCESS_EDIT)) {
                $options[] = array('url'   => pnModURL('Example', 'admin', 'modify', array('tid' => $item['tid'])),
                                   'title' => _EDIT);
                if (pnSecAuthAction(0, 'Example::', "$item[itemname]::$item[tid]", ACCESS_DELETE)) {
                    $options[] = array('url'   => pnModURL('Example', 'admin', 'delete', array('tid' => $item['tid'])),
                                       'title' => _DELETE);
                }
            }

            // Add the calculated menu options to the item array
            $items[$key]['options'] = $options;
        }
    }

    // Assign the items to the template
    $pnRender->assign('exampleitems', $items);

    // assign the values for the smarty plugin to produce a pager in case of there
    // being many items to display.
    //
    // Note that this function includes another user API function.  The
    // function returns a simple count of the total number of items in the item
    // table so that the pager function can do its job properly
    $pnRender->assign('pager', array('numitems'     => pnModAPIFunc('Example',
                                                                    'user',
                                                                    'countitems'),
                                     'itemsperpage' => $itemsperpage));

    // Return the output that has been generated by this function
    return $pnRender->fetch('example_admin_view.htm');
}


/**
 * Modify configuration
 *
 * This is a standard function to modify the configuration parameters of the
 * module
 *
 * @author       The PostNuke Development Team
 * @return       output       The configuration page
 */
function Example_admin_modifyconfig()
{
    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Example::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Create output object
    $pnRender =& new pnRender('Example');

    // As admin output changes often, we do not want caching.
    $pnRender->caching = false;

    // Assign all the module variables to the template
    // If no variable name is passed to pnModGetVar then an array containing all module
    // variables is returned
    // If no template variable name is supplied to the pnRender assign method then each
    // element of the array is assigned to the template using the array key as the 
    // template variable name
    // In this case we have two module variables; itemsperpage and vbold.
    // The array returned from pnModGetVar is array('bold' => ...., 'itemsperpage' => ....)
    // Hence the template variable names will be the same as the module variable name
    $pnRender->assign(pnModGetVar('Example'));

    // Return the output that has been generated by this function
    return $pnRender->fetch('example_admin_modifyconfig.htm');
}


/**
 * Update the configuration
 *
 * This is a standard function to update the configuration parameters of the
 * module given the information passed back by the modification form
 * Modify configuration
 *
 * @author       Jim McDonald
 * @param        bold           print items in bold
 * @param        itemsperpage   number of items per page
 */
function Example_admin_updateconfig()
{
    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'Example::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // Get parameters from whatever input we need.  All arguments to this
    // function should be obtained from pnVarCleanFromInput(), getting them
    // from other places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    list($bold,
         $itemsperpage) = pnVarCleanFromInput('bold',
                                              'itemsperpage');

    // Confirm authorisation code.  This checks that the form had a valid
    // authorisation code attached to it.  If it did not then the function will
    // proceed no further as it is possible that this is an attempt at sending
    // in false data to the system
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', pnVarPrepHTMLDisplay(_BADAUTHKEY));
        return pnRedirect(pnModURL('Example', 'admin', 'view'));
    }

    // Update module variables.  Note that depending on the HTML structure used
    // to obtain the information from the user it is possible that the values
    // might be empty, so it is important to check them all and assign them
    // default values if required.

    // ** Please note pnVarCleanFromInput will always return a set variable, even
    // it's empty so isset() checking is not appropriate.
    if (empty($bold)) {
        $bold = false;
    }
    pnModSetVar('Example', 'bold', (bool)$bold);

    if (empty($itemsperpage)) {
        $itemsperpage = 10;
    }
    // make sure $itemsperpage is a positive integer
    if (!is_integer($itemsperpage) || $itemsperpage < 1) {
        pnSessionSetVar('errormsg', pnVarPrepForDisplay(_EXAMPLEITEMSPERPAGE));
        $itemsperpage = (int)$itemsperpage;
        if ($itemsperpage < 1) {
            $itemsperpage = 25;
        }
    }
    pnModSetVar('Example', 'itemsperpage', $itemsperpage);

    // The configuration has been changed, so we clear all caches for
    // this module.
    $pnRender =& new pnRender('Example');
    // Please note that by using clear_cache without any parameter,
    // we clear all cached pages for this module.
    $pnRender->clear_cache();

    // the module configuration has been updated successfuly
    pnSessionSetVar('statusmsg', _CONFIGUPDATED);

    // Let any other modules know that the modules configuration has been updated
    pnModCallHooks('module','updateconfig', 'Example', array('module' => 'Example'));

    // This function generated no output, and so now it is complete we redirect
    // the user to an appropriate page for them to carry on their work
    return pnRedirect(pnModURL('Example', 'admin', 'view'));
}

?>
