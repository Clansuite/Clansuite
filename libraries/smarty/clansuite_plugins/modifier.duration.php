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
 * Input:<br>
 *         - contents = contents to replace
 *         - preceed_test = if true, includes preceeding break tags
 *           in replacement
 * Example:  {$timestamp|duration}

 * @version  1.0
 * @author   Jens-Andre Koch <jakoch@web.de>
 * @param string
 * @return string
 */
function smarty_modifier_duration($toTimestamp)
{
    return functions::distanceOfTimeInWords(time(),$toTimestamp, false);
}

/* vim: set expandtab: */

?>