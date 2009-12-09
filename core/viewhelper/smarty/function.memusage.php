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
   /*
   if( !function_exists('memory_get_usage') )
   {
        function memory_get_usage()
       {
           //If its Windows
           //Tested on Win XP Pro SP2. Should work on Win 2003 Server too
           //Doesn't work for 2000
           //If you need it to work for 2000 look at http://us2.php.net/manual/en/function.memory-get-usage.php#54642
           if ( substr(PHP_OS,0,3) == 'WIN')
           {
                   if ( substr( PHP_OS, 0, 3 ) == 'WIN' )
                   {
                       $output = array();
                       exec( 'tasklist /FI "PID eq ' . getmypid() . '" /FO LIST', $output );

                       return preg_replace( '/[\D]/', '', $output[5] ) * 1024;
                   }
           }else
           {
               //We now assume the OS is UNIX
               //Tested on Mac OS X 10.4.6 and Linux Red Hat Enterprise 4
               //This should work on most UNIX systems
               $pid = getmypid();
               // Alternative:
               // exec("ps -o rss -p $pid", $output);
               exec("ps -eo%mem,rss,pid | grep $pid", $output);
               $output = explode("  ", $output[0]);
               //rss is given in 1024 byte units
               return $output[1] * 1024;
           }
       }
    }
    $memusage=memory_get_usage();
    */

    if (function_exists('memory_get_usage')) {
      $memusage = memory_get_usage();
    } else {
      return $memusage = 'n/a';
    }


    if ($memusage>0) {
      $memunit="B";
      if ($memusage>1024) {
        $memusage=$memusage/1024;
        $memunit="kB";
      }
      if ($memusage>1024) {
        $memusage=$memusage/1024;
        $memunit="MB";
      }
      if ($memusage>1024) {
        $memusage=$memusage/1024;
        $memunit="GB";
      }
      print(number_format($memusage,2).$memunit) . ' / ' . ini_get('memory_limit') .'B'; # append the missing B of MB :)
    }
}

/* vim: set expandtab: */

?>
