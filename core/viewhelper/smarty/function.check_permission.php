<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage Clansuite Plugins / Smarty View Helper
 */

/**
 * Smarty Viewhelper for checking permissions
 *
 * Examples:
 * <pre>
 * {check_permission name="module.action"}
 * </pre>
 *
 * Type:    function<br>
 * Name:    check_permission<br>
 * Purpose: checks if a user has a certain permission<br>
 *
 * @author  Jens-André Koch <vain@clansuite.com>
 * @license http://www.gnu.org/copyleft/gpl.html GPL 2 / any later version
 * @param   array $params
 * @param   Smarty $smarty
 * @return  boolean True if user has permission, false otherwise.
 */
function smarty_function_check_permission($params, $smarty)
{
    # ensure we got parameter name
    if( empty($params['name']) or is_string($params['name']) == false)
    {
        $smarty->trigger_error('Parameter "name" is not a string or empty.
                                Please provide a name in the format "module.action".');
        return;
    }

    # ensure parameter name contains a dot
    if (false === strpos($params['name'], '.'))
    {
        $smarty->trigger_error('Parameter "name" is not in the correct format.
                                Please provide a name in the format "module.action".');
        return;
    }

    # perform the permission check
    if( false !== Clansuite_ACL::checkPermission( $params['name'] ) )
    {
        return true;
    }
    else
    {
        return false;
    }
}
?>