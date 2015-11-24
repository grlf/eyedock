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

function Tools_admin_main()
{
    // Permission check.
    if (!pnSecAuthAction(0, 'Tools::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODNOAUTH);
    }

    // Create a new output object.
    $pnRender =& new pnRender('Tools');

    // Return template.
    return $pnRender->fetch('tools_admin.htm');
}

?>
