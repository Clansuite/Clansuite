<?php
/**
 * Clansuite Smarty Viewhelper for rendering Flashmessages
 */
function smarty_function_flashmessages($params, $smarty)
{
    # render only a certain type of flashmessages
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