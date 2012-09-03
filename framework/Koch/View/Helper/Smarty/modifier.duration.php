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
 * Name:     duration<br>
 * Date:     Oct 07, 2008
 * Purpose:  show distanceOfTimeInWords from current timestamp to string timestamp
 * Input:
 * Example:  {$timestamp|duration}
 * @param string
 * @return string
 */
function Smarty_modifier_duration($toTimestamp)
{
    return \Koch\Functions\Functions::distanceOfTimeInWords(time(),$toTimestamp, false);
}

/* vim: set expandtab: */
