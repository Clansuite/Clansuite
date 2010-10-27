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
 * @author Jens-André Koch <jakoch@web.de>
 * @copyright Copyright (C) 2009 Jens-André Koch
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

    # Check if class was loaded
    if(class_exists($module_classname, false) === false)
    {
        # include function Clansuite_Widget::loadModul()
        if(class_exists('Clansuite_Widget', false) === false)
        {
            require ROOT_CORE . 'viewhelper/widget.core.php';
        }

        # Load class, if not already loaded
        if(Clansuite_Widget::loadModul($module_classname) === false)
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
         * Output the template of a widget
         *
         * The template is fetched from the module or from the various theme folders!
         * You can also set an alternative widgettemplate inside the widget itself
         * via setTemplate() method.
         *
         * The order of template detection is determined by the $smarty->template_dir array.
         * @see $smarty->template_dir
         */
        # build template name
        $template = $action . '.tpl';

        # for a look at the detection order uncomment the next line
        #Clansuite_Debug::printR($smarty->template_dir);

        if ($smarty->templateExists('modules' . DS . $module . DS . $action . '.tpl'))
        {
            # $smarty->template_dir[s]..modules\news\widget_news.tpl
            return $smarty->fetch('modules' . DS . $module . DS . $action . '.tpl');
        }
        elseif ($smarty->templateExists('modules' . DS . $module . DS . 'view' . DS . 'smarty' . DS . $action . '.tpl'))
        {
            # $smarty->template_dir[s]..modules\news\view\widget_news.tpl
            return $smarty->fetch('modules' . DS . $module . DS . 'view' . DS . 'smarty' . DS . $action . '.tpl');
        }
        elseif ($smarty->templateExists($module . DS . 'view' . DS . 'smarty' . DS . $action . '.tpl'))
        {
            # $smarty->template_dir[s]..\news\view\smarty\widget_news.tpl
            return $smarty->fetch($module . DS . 'view' . DS . 'smarty' . DS . $action . '.tpl');
        }
        elseif($smarty->templateExists($template))
        {
            # $smarty->template_dir[s].. $template
            return $smarty->fetch($template);
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