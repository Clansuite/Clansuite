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
    if( isset($params['type']) and $params['type']== 'alphabet' and $smarty->template_exists('pagination-alphabet.tpl') )
    {
        # load the generic pagination template
        return $smarty->fetch('pagination-alphabet.tpl');
    }

    # check if file exists true and if it's necessary to paginate
    if( $smarty->template_exists('pagination-generic.tpl') == false )
    {
        echo 'Pagination Template not found.';
    }

    if( $smarty->get_template_vars('pager')->haveToPaginate() )
    {
        # load the generic pagination template
        return $smarty->fetch('pagination-generic.tpl');
    }
    else # there's no need to paginate
    {
        #return 'No need to paginate!';
    }
}
?>