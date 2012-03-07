<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * smarty_function_dbexectime
 */
function smarty_function_dbexectime($params, $smarty)
{
    if(DEBUG == 1)
    {
        echo Clansuite_Doctrine2::getExecTime();
    }
    else
    {
        echo 'Disabled';
    }
}
?>