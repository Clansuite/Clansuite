<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty plugin
 *
 * Type:     modifier<br>
 * Name:     megabytes<br>
 * Date:     Oct 07, 2008
 * Purpose:  convert a number to megabytes , kilobytes, bytes
 * Input:<br>
 *         - string = bytesize to convert to mb, kb, b
 * Example:  {$bytesize|megabytes}
 * @param string
 * @return string
 */
function smarty_modifier_megabytes($string)
{
    return \Koch\Functions\Functions::getsize($string);
}

/* vim: set expandtab: */
