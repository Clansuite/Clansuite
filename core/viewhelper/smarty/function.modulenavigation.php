<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage Clansuite Plugins / Smarty View Helper
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
    $modulename = Clansuite_HttpRequest::getRoute()->getModuleName();
    $modulenavigation_file = ROOT_MOD. $modulename . DS . $modulename . '.menu.php';

    if( is_file($modulenavigation_file) )
    {
        # this includes the file, which contains a php array name $modulenavigation
        include $modulenavigation_file;

        # convert URLs by callback
        $modulenavigation = array_map("convertURLs", $modulenavigation);

        $smarty->assign('modulenavigation', $modulenavigation);
        # The file is located in clansuite/themes/core/view/smarty/modulenavigation-generic.tpl
        return $smarty->fetch('modulenavigation-generic.tpl');
    }
    else
    {
        $smarty->assign('modulename', $modulename);
        $errormessage = $smarty->fetch('modulenavigation_not_found.tpl');
        trigger_error($errormessage);
    }
}

/**
 * array_map callback function to replace the values of the 'url' key
 * because these might be shorthands like "/index/show" etc.
 *
 * @param array $array
 */
function convertURLs($array)
{
    $array['url'] = Clansuite_Router::buildURL($array['url']);
    return $array;
}
?>