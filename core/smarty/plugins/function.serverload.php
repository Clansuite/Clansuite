<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * smarty_function_serverload
 */
function smarty_function_serverload($params, &$smarty)
{
   // check if exists, else define
   if( !function_exists('sys_getloadavg') )
   {
        function sys_getloadavg()
        {
            // get average server load in the last minute. Keep quiet cause virtual hosts can give perm denied
        	if (@is_readable('/proc/loadavg') && $load = file('/proc/loadavg')) 
        	{
        		list($server_load) = explode(' ', $load[0]);
        		return $server_load;
        	}
        }
    }
    
    // get
    $load = sys_getloadavg();
    print_r($load);
    
    /*
    // check for shut down in case 80 processes
	if ($load > 80) 
	{
       header('HTTP/1.1 503 Too busy, try again later.');
       die('Server too busy ('. $load[0] .'). Please try again later. ');
    } 
    */
}

?>