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
function smarty_function_help($params, &$smarty)
{
    # check if file exists
    if( $smarty->template_exists( 'help.tpl') )
    {
        # load the help template from modulepath ->  modulename/templates/help.tpl
        return $smarty->fetch( $smarty->get_template_vars('template_of_module').'/templates/help.tpl');
    }
    else
    {
        echo 'Help Template not found.';
    }
}
?>