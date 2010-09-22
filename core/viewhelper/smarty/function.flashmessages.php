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
    # rener only a certain type-set of flashmessages
    if(isset($params['type']))
    {
        return Clansuite_Flashmessages::render($params['type']);
    }
    else # render all
    {
        return Clansuite_Flashmessages::render();
    }
}
?>