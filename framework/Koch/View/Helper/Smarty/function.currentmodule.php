<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage Plugins
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

 * @return string
 */
function smarty_function_currentmodule()
{
    return Koch_TargetRoute::getModule();
}
