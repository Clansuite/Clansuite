<?php
/**
 * Clansuite Smarty Viewhelper
 *
 * @category Clansuite
 * @package Smarty
 * @subpackage Viewhelper
 */

/**
 * Smarty Array function plugin
 * Defines an array from template side
 *
 * Examples:
 * <pre>
 * {array name="arrayname" values="1,2,3,4,5" delimiter="," explode="true"}
 * </pre>
 *
 * Type:     function<br>
 * Name:     array<br>
 * Purpose:  Defines an array from template side<br>
 * @author   Jens-André Koch <jakoch@web.de>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL v2 or any later license
 * @param array $params icq and title parameters required
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_array($params, $smarty)
{
    // be sure array has a name
    if( empty($params['name']) or is_string($params['name']) == false)
    {
        $smarty->trigger_error('array: name as parameter');
        return;
    }

    // be sure values parameter is present
    if(empty($params['values']))
    {
        $smarty->trigger_error('array: missing values as parameter');
        return;
    }

    // be sure explode parameter is present
    if( empty($params['explode']))
    {
        $smarty->trigger_error('array: missing explode (true, false) as parameter');
        return;
    }
    else
    {
        (bool) $params['explode'];
    }

    // be sure delimiter parameter is present
    if( empty($params['delimiter']))
    {
        $smarty->trigger_error('array: missing delimiter definition as parameter');
        return;
    }

    # set up temporary array
    $temporary_array = array();
    # explode values at delimiter into the array
    $temporary_array = explode($params['delimiter'], $params['values']);

    # ok, check the assigned template vars and see if a variable name exists and if it's an array
    if (is_array($smarty->getTemplateVars($params['name'])))
    {
        # if yes, we append our array to the existing one
        $smarty->append($params['name'], $temporary_array, true);
    }
    else
    {
        # we assign the array with the given name
        $smarty->assign($params['name'], $temporary_array);
    }
}
?>