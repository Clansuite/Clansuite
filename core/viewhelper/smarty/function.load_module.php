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
 * @version SVN $Id$
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
    
    # debugdisplay for the incomming parameters of a specific load_module request
    /*if($params['name'] == 'news')
    {
        clansuite_xdebug::printR($params);
    }*/
    
    # Init incomming Variables
    $mod    = isset( $params['name'] ) ? (string) $params['name'] : '';
    $sub    = isset( $params['sub'] ) ? (string) $params['sub']  : '';
    $action = isset( $params['action'] ) ? (string) $params['action'] : '';    
    $items  = isset( $params['items'] )  ? (int) $params['items']  : null;
    
    # debugdisplay for a specific incomming value
    /*if($params['name'] == 'news')
    {
        clansuite_xdebug::printR($items);
    }*/

    # WATCH it, this resets the incomming parameters array
    #$params = isset( $params['params'] ) ? (string) $params['params'] : '';
 
    # Build a Parameter Array from Parameter String like: param|param|etc
    if( empty($params['params']) )
    {
        $parameter_array = null;
    }
    else
    {
        $parameter_array = split('\|', $params['params']);
    }

    # Construct the variable module_name
    if (isset($sub) and strlen($sub) > 0)
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
        # Check if class was loaded
        if( clansuite_loader::loadModul($module_name) == false)
        {
            return '<br/>Module missing or misspelled: <strong>'. $module_name.'</strong>';
        }
    }

    # Instantiate Class
    $controller = new $module_name;
    $controller->setView($smarty);
    $controller->initRecords($mod);

    /**
     * Get the Ouptut of the Object->Method Call
     */
    if( method_exists( $controller, $action ) )
    {
        # exceptional handling of parameters and output for adminmenu
        if ( $module_name == 'module_menu_admin' )
        {
            return $controller->$action($parameter_array);
        }

        # slow call
        #call_user_func_array( array($controller, $action), $parameter_array );

        # fast call
        $controller->$action($items);

        /**
         * Outputs the template of a widget
         *
         * The template is fetched from the module or from the various theme folders!
         * You can also set an alternative widgettemplate inside the widget itself.
         *
         * the templatepath checked is relative to the common smarty templatepaths array,
         * wich is defined by $smarty->template_dir.
         * the order of detection is also determined by that array.
         * @see $smarty->template_dir, clansuite_xdebug::printr($smarty->template_dir);
         */

        if($smarty->template_exists($mod.DS.$action.'.tpl'))
        {
            # $smarty->template_dir[s]..\news\widget_news.tpl
            return $smarty->fetch($mod.DS.$action.'.tpl');
        }
        elseif($smarty->template_exists($mod.DS.'templates'.DS.$action.'.tpl'))
        {
            # $smarty->template_dir[s]..\news\templates\widget_news.tpl
            return $smarty->fetch($mod.DS.'templates'.DS.$action.'.tpl');
        }
        else
        {
            return $smarty->trigger_error("Error! Failed to load Widget-Template for <br /> $module_name -> $action($items)");
        }
    }
    else
    {
        return $smarty->trigger_error("Error! Failed to load Widget: <br /> $module_name -> $action($items)");
    }
}
?>