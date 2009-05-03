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
 * Name:     dateformat<br>
 * Date:     Oct 07, 2008
 * Purpose:  format datestring 
 * Input:<br>
 *         - string = datestring
 *
 * Example:  {$timestamp|date}

 * @version  1.0
 * @author   Jens-André Koch <jakoch@web.de>
 * @param string
 * @return string
 */
function smarty_modifier_dateformat($string)
{
    if (!defined('DATE_FORMAT'))
    {
        define('DATE_FORMAT', "d.m.Y H:i");
    }   
    return date(DATE_FORMAT,$string);
}

/* vim: set expandtab: */

?>
