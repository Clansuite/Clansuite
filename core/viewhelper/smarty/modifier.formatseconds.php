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
 * Purpose:  show format_seconds_to_shortstring from current timestamp in seconds
 * Input:
 *
 * Example:  {$seconds|formatseconds}

 * @version  1.0
 * @author   Jens-André Koch <jakoch@web.de>
 * @param string
 * @return string
 */
function smarty_modifier_formatseconds($seconds)
{
    return Clansuite_Functions::format_seconds_to_shortstring($seconds);
}
/* vim: set expandtab: */
?>