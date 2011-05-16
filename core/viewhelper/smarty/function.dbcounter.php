<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * smarty_function_dbexectime
 */
function smarty_function_dbcounter($params, $smarty)
{
    if(DEBUG == 1)
    {
        /** 
         * The call to this viehelper "dbcounter" is performed inside the view.
         * So the Query for closing the session is missing, because it's 
         * performed on shutdown of the application.
         * We simply add one Query..
         */
        echo Clansuite_Doctrine2::getNumberOfQueries() + 1;
    }
    else
    {
        echo 'Disabled';
    }
}

/* vim: set expandtab: */

?>
