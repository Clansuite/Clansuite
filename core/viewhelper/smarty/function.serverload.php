<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * smarty_function_serverload
 */
function smarty_function_serverload($params)
{
    if(mb_strtoupper(mb_substr(PHP_OS, 0, 3)) === 'WIN')
    {
        $wmi = new COM("Winmgmts://");
        $cpus = $wmi->execquery("SELECT * FROM Win32_Processor");
        $cpuload = 0;
        $nr_cpus = 0;
        $loadcpu = '';
        
        foreach($cpus as $cpu)
        {
            $cpuload += $cpu->loadpercentage;

            if($nr_cpus > 0)
            {
                $loadcpu.= ' | ' . $cpuload . '%';
            }
            else # first one
            {
                $loadcpu.= $cpuload . '%';
            }

            $nr_cpus++;
        }

        $cpuload = $cpuload / $nr_cpus;

        if($nr_cpus == 1)
        {
            echo '[ ' . $loadcpu . ' ]';
        }
        else
        {
            # list all processor loads and total load
            echo '[ ' . $loadcpu . ' ] [ ' . $cpuload . ' ]';
        }
        
        #if($cpuload >= $ServerLoadAllowed)
        #{
            # throw new Clansuite_Exception("Server load too high, come back later." );
            # $server_load_high = true;
        #}
    }
    else
    {
        # check if exists, else define
        if( false === function_exists('sys_getloadavg') )
        {
            function sys_getloadavg()
            {
                # get average server load in the last minute. Keep quiet cause virtual hosts can give perm denied
                if (is_readable('/proc/loadavg') and $load = file('/proc/loadavg'))
                {
                    $serverload = array();
                    list($serverload) = explode(' ', $load[0]);
                    return $serverload;
                }
            }
        }

        # get
        $load = sys_getloadavg();
        if(empty($load))
        {
            $load = array(0, 0, 0);
        }
        echo '1[' .$load[0]. '] 5[' .$load[1]. '] 15[' .$load[2]. ']';

        /*
        // check for shut down in case 80 processes
        if ($load > 80)
        {
           header('HTTP/1.1 503 Too busy, try again later.');
           die('Server too busy ('. $load[0] .'). Please try again later. ');
        }
        */
    }
}
?>