<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * smarty_function_gzipcheck
 */
function smarty_function_gzipcheck($params, &$smarty)
{
    if (ini_get('zlib.output_compression') == 1)
    {
		echo 'Enabled (zlib)';
    }
    else
    {
		echo 'Disabled';
    }
}

/* vim: set expandtab: */

?>