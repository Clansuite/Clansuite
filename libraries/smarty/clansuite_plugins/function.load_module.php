<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * This smarty function is part of "Clansuite - just an eSports CMS"
 * @link http://www.clansuite.com
 *
 * @author Jens-André Koch <jakoch@web.de>
 * @copyright Copyright (C) 2009 Jens-André Koch
 * @license GNU Public License (GPL) v2 or any later version
 * @version SVN $Id: $
 *
 * Name:     	loadmodule
 * Type:     	function
 * Purpose: This TAG inserts the a certain module and its widget.
 *
 * Static Function to Call variable Methods from templates via
 * {load_module name= sub= params=}
 * Parameters: name, sub, action, params, items
 *
 * Example:
 * {load_module name="quotes" action="widget_quotes"}
 *
 * @param array $params as described above (emmail, size, rating, defaultimage)
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_load_module($params, &$smarty)
{
    # @todo use module and action mapping here
    
    # Init incomming Variables
    $mod    = isset( $params['name'] ) ? (string) $params['name'] : '';
    $sub    = isset( $params['sub'] )  ? (string) $params['sub']  : '';
    $action = (string) $params['action'];

    $params = isset( $params['params'] ) ? (string) $params['params'] : '';
    $items  = isset( $params['items'] )  ? (int)    $params['items']  : 5;

    # Construct the variable module_name
    if (isset($sub) && strlen($sub) > 0)
    {
        # like "module_admin_menueditor"
        $module_name = 'module_' . strtolower($mod) . '_'. strtolower($sub);
    }
    else
    {
        # like "module_admin"
        $module_name = 'module_' . strtolower($mod);
    }

    # Load class, if not already loaded
    if (!class_exists(ucfirst($module_name)))
    {
        clansuite_loader::loadModul($module_name);
    }

    # Check if class was loaded
    if (!class_exists(ucfirst($module_name)))
    {
        return '<br/>Module missing or misspelled: <strong>'. $module_name.'</strong>';
    }

    # Parameter Array

    if( empty($params['params']) )
    {
        $param_array = null;
    }
    else
    {
        $param_array = split('\|', $params['params']);
    }

    # Instantiate Class
    $controller = new $module_name;
    $controller->moduleName = $mod;
    $controller->methodName = $action;
    $controller->setView($smarty);

    /**
     * Get the Ouptut of the Object->Method Call
     */
    if( method_exists( $controller, $action ) )
    {
        # exceptional handling for adminmenu
        if ( $module_name == 'module_menu_admin' )
        {
            echo $controller->$action($param_array);
            return;
        }

        # slow call
        #call_user_func_array( array($controller, $action), $param_array );

        # fast call
        $controller->$action($items);
    }
    else
    {
        echo "Load failed:<br /> $module_name -> $action($items)";
    }
}
?>