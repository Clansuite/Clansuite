<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * smarty_function_memusage
 */
function smarty_function_memusage($params, $smarty)
{
    if (function_exists('memory_get_usage'))
    {
        $memusage = memory_get_usage();
    }
    else
    {
        return $memusage = 'n/a';
    }


    if ($memusage>0)
    {
        $memunit='B';
        if ($memusage>1024)
        {
            $memusage=$memusage/1024;
            $memunit='kB';
        }
        if ($memusage>1024)
        {
            $memusage=$memusage/1024;
            $memunit='MB';
        }
        if ($memusage>1024)
        {
            $memusage=$memusage/1024;
            $memunit='GB';
        }

        # append the missing B of MB :)
        echo (number_format($memusage,2).$memunit) . ' / ' . ini_get('memory_limit') .'B';
    }
}
?>