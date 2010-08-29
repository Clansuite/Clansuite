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

 * @author   Jens-André Koch <jakoch@web.de>
 * @param string = has to be a unix timestamp
 * @return string
 */
function smarty_modifier_dateformat($string)
{
    # it's a unix timestamp?
    if(mb_strlen($string) == 11) 
    {
       return date(DATE_FORMAT,$string); 
    }
}
?>
