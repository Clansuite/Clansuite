<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage Clansuite Plugins / Smarty View Helper
 */

/**
 * Smarty pagination
 * Displays help text of a module
 *
 * Examples:
 * <pre>
 * {help}
 * </pre>
 *
 * Type:     function<br>
 * Name:     help<br>
 * Purpose:  displays help.tpl for a module, if existing<br>
 *
 * @author   Jens-André Koch <vain@clansuite.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL 2 / any later version
 * @param array $params
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_help($params, $smarty)
{
    $modulename = $smarty->getTemplateVars('template_of_module');
    
    # check if file exists
    if( $smarty->templateExists( $modulename. '/view/help.tpl') )
    {
        # load the help template from modulepath ->  modulename/view/help.tpl
        return $smarty->fetch( $modulename. '/view/help.tpl');
    }
    elseif( DEBUG == true and DEVELOPMENT == true )
    {
        return $smarty->fetch( ROOT_THEMES . 'core/templates/help_not_found.tpl');
    }
    else
    {
        return 'Help Template not found.';
    }
}
?>