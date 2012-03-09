<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage Plugins
 */

/**
 * Smarty ModuleNavigation
 * Displays a Module Navigation Element
 * depends on module configuration file.
 *
 * Examples:
 * <pre>
 * {modulnavigation}
 * </pre>
 *
 * Type:     function<br>
 * Name:     modulenavigation<br>
 * Purpose:  display modulenavigation<br>
 * @author   Jens-André Koch <vain@clansuite.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL 2 / any later version
 * @param array $params
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_modulenavigation($params, $smarty)
{
    $module = Koch_HttpRequest::getRoute()->getModuleName();

    $file = ROOT_MOD. $module . DS . $module . '.menu.php';

    if( is_file($file) )
    {
        # this includes the file, which contains a php array name $modulenavigation
        include $file;

        # push the $modulenavigation array to a callback function
        # for further processing of the menu items
        $modulenavigation = array_map("applyCallbacks", $modulenavigation);

        $smarty->assign('modulenavigation', $modulenavigation);

        # The file is located in clansuite/themes/core/view/smarty/modulenavigation-generic.tpl
        return $smarty->fetch('modulenavigation-generic.tpl');
    }
    else # the module menu navigation file is missing
    {
        $smarty->assign('modulename', $module);
        $errormessage = $smarty->fetch('modulenavigation_not_found.tpl');
        trigger_error($errormessage);
    }
}

/**
 * array_map callback function
 *
 * 1) convert short urls
 * 2) execute callback conditions of menu items
 * 3) use name as title, if title is not defined
 *
 * @param array $modulenavigation
 */
function applyCallbacks(array $modulenavigation)
{
    /**
     * 1) Convert Short Urls
     *
     * This replaces the values of the 'url' key (array['url']),
     * because these might be shorthands, like "/index/show".
     */
    $modulenavigation['url'] = Koch_Router::buildURL($modulenavigation['url']);

    /**
     * 2) Conditions of menu items
     *
     * If the condition of the menu item is not met,
     * then condition is set to false, otherwise true.
     */
    if(isset($modulenavigation['condition']) === true)
    {
        /**
         * the if statement evaluates the content of the key condition
         * and compares it to false, then reassigns the boolean value as
         * the condition value.
         *
         * for now you might define 'condition' => extension_loaded('apc')
         *
         * @todo check usage of closures
         */
        if($modulenavigation['condition'] === false)
        {
            $modulenavigation['condition'] = false;
        }
        else
        {
            $modulenavigation['condition'] = true;
        }
    }

    /**
     * 3) use name as title, if title is not defined
     */
    if($modulenavigation['title'] == '')
    {
        $modulenavigation['title'] = $modulenavigation['name'];
    }

    return $modulenavigation;
}
?>