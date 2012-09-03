<?php
/**
 * Koch Framework Smarty Viewhelper
 *
 * @category Koch
 * @package Smarty
 * @subpackage Viewhelper
 */

/**
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
function Smarty_function_load_module($params, $smarty)
{
    // debug display for the incomming parameters of a specific load_module request
    if ($params['name'] == 'news') {
        \Koch\Debug\Debug::firebug($params);
    }

    // Init incomming Variables
    $module = isset($params['name']) ? (string) mb_strtolower($params['name']) : '';
    $controller = isset($params['ctrl']) ? (string) mb_strtolower($params['ctrl']) : '';
    $action = isset($params['action']) ? (string) $params['action'] : '';
    $items = isset($params['items']) ? (int) $params['items'] : null;

    // Load Module/Controller in order to get access to the widget method
    $module_path = \Koch\Mvc\Mapper::getModulePath($module);
    #echo $module_path . '<br>';

    $classname = \Koch\Mvc\Mapper::mapControllerToClassname($module, $controller);
    #echo $classname . '<br>';

    if (class_exists($classname) === false) {
        return '<br/>Widget Loading Error. Module missing or misspelled? <strong>' . $module .' ' . $controller . '</strong>';
    }

    // Instantiate Class
    $controller = new $classname(
                \Clansuite\Application::getInjector()->instantiate('Koch\Http\HttpRequest'),
                \Clansuite\Application::getInjector()->instantiate('Koch\Http\HttpResponse')
    );
    $controller->setView($smarty);
    #$controller->setModel($module);

    /**
     * Get the Ouptut of the Object->Method Call
     */
    if (method_exists($controller, $action)) {
        // exceptional handling of parameters and output for adminmenu
        if ($classname == 'clansuite_module_menu_admin') {
            $parameters = array();

            // Build a Parameter Array from Parameter String like: param|param|etc
            if (empty($params['params'])) {
                $parameters = null;
            } else {
                $parameters = explode('\|', $params['params']);
            }

            return $controller->$action($parameters);
        }

        // call
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
        // build template name
        $template = $action . '.tpl';

        // for a look at the detection order uncomment the next line
        #\Koch\Debug\Debug::printR($smarty->template_dir);

        if ($smarty->templateExists('modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $action . '.tpl')) {
            // $smarty->template_dir[s]..modules\news\widget_news.tpl
            return $smarty->fetch('modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $action . '.tpl');
        } elseif ($smarty->templateExists('modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'smarty' . DIRECTORY_SEPARATOR . $action . '.tpl')) {
            // $smarty->template_dir[s]..modules\news\view\widget_news.tpl
            return $smarty->fetch('modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'smarty' . DIRECTORY_SEPARATOR . $action . '.tpl');
        } elseif ($smarty->templateExists($module . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'smarty' . DIRECTORY_SEPARATOR . $action . '.tpl')) {
            // $smarty->template_dir[s]..\news\view\smarty\widget_news.tpl
            return $smarty->fetch($module . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'smarty' . DIRECTORY_SEPARATOR . $action . '.tpl');
        } elseif ($smarty->templateExists($template)) {
            // $smarty->template_dir[s].. $template
            return $smarty->fetch($template);
        } else {
            return trigger_error('Error! Failed to load Widget-Template for <br /> ' . $classname . ' -> ' . $action . '(' . $items . ')');
        }
    } else {
        return trigger_error('Error! Failed to load Widget: <br /> ' . $classname . ' -> ' . $action . '(' . $items . ')');
    }
}
