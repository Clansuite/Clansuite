<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage Clansuite Plugins / Smarty View Helper
 */

/**
 * Smarty Currentmodule
 *
 * Displays the name of the current module
 *
 * Examples:
 * <pre>
 * {pagination}
 * </pre>
 *
 * Type:     function<br>
 * Name:     currentmodule<br>
 * Purpose:  display name of current module<br>
 * @author   Jens-André Koch <vain@clansuite.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL 2 / any later version
 * @return string
 */
function smarty_function_currentmodule()
{
    return Clansuite_TargetRoute::getModuleName();
}
?>