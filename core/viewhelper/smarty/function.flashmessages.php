<?php
/**
 * Clansuite Smarty Viewhelper
 *
 * @category Clansuite
 * @package Smarty
 * @subpackage Viewhelper
 */

function smarty_function_flashmessages($params, $smarty)
{    
    if(isset($params['type']))
    {       
        return Clansuite_Flashmessages::render($params['type']);
    }
    else
    { 
        return Clansuite_Flashmessages::render();
    }
}
?>