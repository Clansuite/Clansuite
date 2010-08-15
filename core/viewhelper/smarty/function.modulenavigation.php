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
        include $modulenavigation_file;
        $smarty->assign('modulenavigation', $modulenavigation);
        # The file is located in clansuite/themes/core/view/modulenavigation-generic.tpl
        return $smarty->fetch('modulenavigation-generic.tpl');
    }
    else
    {
        $smarty->assign('modulename', $modulename);
        $errormessage = $smarty->fetch('modulenavigation_not_found.tpl');
        trigger_error($errormessage);
    }
}
?>