<?php
/**
 * Clansuite Smarty Viewhelper
 *
 * @category Clansuite
 * @package Smarty
 * @subpackage Viewhelper
 */

/**
 * This smarty function is part of "Clansuite - just an eSports CMS"
 * @link http://www.clansuite.com
 *
 * @author Jens-Andr� Koch <jakoch@web.de>
 * @copyright Copyright (C) 2009 Jens-Andr� Koch
 * @license GNU Public License (GPL) v2 or any later version
 * @version SVN $Id$
 *
 * Name:         loadmodule
 * Type:         function
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
function smarty_function_load_module($params, $smarty)
{
    # debugdisplay for the incomming parameters of a specific load_module request
    /*if($params['name'] == 'news')
    {
        Clansuite_Debug::firebug($params);
    }*/

    # Init incomming Variables
    $module = isset($params['name']) ? (string) mb_strtolower($params['name']) : '';
    $submodule = isset($params['sub']) ? (string) mb_strtolower($params['sub']) : '';
    $action = isset($params['action']) ? (string) $params['action'] : '';
    $items = isset($params['items']) ? (int) $params['items'] : null;

    # WATCH it, this resets the incomming parameters array
    #$params = isset( $params['params'] ) ? (string) $params['params'] : '';

    $module_classname = 'clansuite_module_';
    # Construct the variable module_name
    if(isset($submodule) and mb_strlen($submodule) > 0)
    {
        # like "clansuite_module_admin_menu"
        $module_classname .= $module . '_' . $submodule;
    }
    else
    {
        # like "clansuite_module_admin"
        $module_classname .= $module;
    }

    #Clansuite_Debug::firebug($module_classname);

    # Load class, if not already loaded
    if(class_exists($module_classname, false) == false)
    {
        # Check if class was loaded
        if(Clansuite_Loader::loadModul($module_classname) == false)
        {
            return '<br/>Module missing or misspelled: <strong>' . $module_classname . '</strong>';
        }
    }

    # Instantiate Class
    $controller = new $module_classname(
                Clansuite_CMS::getInjector()->instantiate('Clansuite_HttpRequest'),
                Clansuite_CMS::getInjector()->instantiate('Clansuite_HttpResponse')
    );
    $controller->setView($smarty);
    $controller->initModel($module);

    /**
     * Get the Ouptut of the Object->Method Call
     */
    if(method_exists($controller, $action))
    {
        # exceptional handling of parameters and output for adminmenu
        if($module_classname == 'clansuite_module_menu_admin')
        {
            $parameters = array();

            # Build a Parameter Array from Parameter String like: param|param|etc
            if(empty($params['params']))
            {
                $parameters = null;
            }
            else
            {
                $parameters = explode('\|', $params['params']);
            }

            return $controller->$action($parameters);
        }

        # call
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
         * @see $smarty->template_dir, Clansuite_Debug::printr($smarty->template_dir);
         */
        if($smarty->templateExists($module . DS . $action . '.tpl'))
        {
            # $smarty->template_dir[s]..\news\widget_news.tpl
            return $smarty->fetch($module . DS . $action . '.tpl');
        }
        elseif($smarty->templateExists($module . DS . 'view' . DS . $action . '.tpl'))
        {
            # $smarty->template_dir[s]..\news\view\widget_news.tpl
            return $smarty->fetch($module . DS . 'view' . DS . $action . '.tpl');
        }
        else
        {
            return $smarty->trigger_error('Error! Failed to load Widget-Template for <br /> ' . $module_classname . ' -> ' . $action . '(' . $items . ')');
        }
    }
    else
    {
        return $smarty->trigger_error('Error! Failed to load Widget: <br /> ' . $module_classname . ' -> ' . $action . '(' . $items . ')');
    }
}
?>