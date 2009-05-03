<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage Clansuite Plugins / Smarty View Helper
 */

/**
 * Smarty pagination
 * Displays a pagination Element
 *
 * Examples:
 * <pre>
 * {pagination}
 * </pre>
 *
 * Type:     function<br>
 * Name:     pagination<br>
 * Purpose:  display pagination if needed<br>
 * @author   Jens-André Koch <vain@clansuite.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL 2 / any later version
 * @param array $params
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_pagination($params, &$smarty)
{
    #clansuite_xdebug::printR($smarty->get_template_vars());

    # check if file exists true and if it's necessary to paginate
    if( $smarty->template_exists('pagination-generic.tpl') and $smarty->get_template_vars('pager')->haveToPaginate() )
    {
        # load the generic pagination template
        return $smarty->fetch('pagination-generic.tpl');
    }
    else # if no file was found - say so
    {
        echo 'Pagination Template not found.';
    }
}
?>