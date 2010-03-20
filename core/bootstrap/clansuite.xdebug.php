<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Clansuite - just an eSports CMS".
    *
    * LICENSE:
    *
    *    This program is free software; you can redistribute it and/or modify
    *    it under the terms of the GNU General Public License as published by
    *    the Free Software Foundation; either version 2 of the License, or
    *    (at your option) any later version.
    *
    *    This program is distributed in the hope that it will be useful,
    *    but WITHOUT ANY WARRANTY; without even the implied warranty of
    *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    *    GNU General Public License for more details.
    *
    *    You should have received a copy of the GNU General Public License
    *    along with this program; if not, write to the Free Software
    *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    *
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    *
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite_XDEBUG
 *
 * This class initializes xdebug at system start-up and displays debug
 * and runtime-informations at application shutdown.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  XDebug
 */
class Clansuite_Xdebug
{
    public static $_xdebug_memory_before = '';

    public static function enable()
    {
        xdebug_enable();
    }

    public static function disable()
    {
        xdebug_disable();
    }

    /**
     * XDebug Helper Functions
     */
    public static function is_xdebug_active()
    {
        if (extension_loaded('xdebug') and xdebug_is_enabled())
        {
            return true;
        }
        return false;
    }

    /**
     * Initializes XDEBUG
     */
    public static function start_xdebug()
    {
        # Start XDEBUG Tracing and Coverage
        if (self::is_xdebug_active())
        {
            ini_set('xdebug.auto_trace', 'On');
            ini_set('xdebug.trace_output_dir', getcwd() . '/logs/');
            ini_set('xdebug.trace_output_name', 'clansuite_trace%u');
            ini_set('xdebug.show_mem_delta', 'On');
            ini_set('xdebug_start_code_coverage', 'XDEBUG_CC_UNUSED');
            ini_set('xdebug.xdebug.collect_return', 'On');
            ini_set('xdebug.var_display_max_children', 100 );
            ini_set('xdebug.var_display_max_depth', 10 );
            ini_set('xdebug.dump.GET', '*' );
            ini_set('xdebug.dump.POST', '*' );
            ini_set('xdebug.dump.COOKIE', '*' );
            ini_set('xdebug.dump.SESSION', '*' );
            ini_set('xdebug.profiler_enable', 1);
            ini_set('xdebug.profiler_output_name', 'cachegrind.out.tmp');

            self::$_xdebug_memory_before = 'Memory Usage (before): ' . self::roundMB(xdebug_memory_usage()) . ' MB.<hr />';

            #xdebug_start_trace(getcwd() . '/logs/clansuite_trace', XDEBUG_TRACE_HTML);

            xdebug_start_code_coverage(XDEBUG_CC_DEAD_CODE | XDEBUG_CC_UNUSED);
            #var_dump(xdebug_get_code_coverage());
            #var_dump(xdebug_get_function_count());
            xdebug_get_code_coverage();
        }

        # stop tracing and display infos
        register_shutdown_function('clansuite_xdebug::shutdown');
    }


    /**
     * Shutdown XDEBUG and give some Debugging Reports
     */
    public static function end_xdebug()
    {
        # Start XDEBUG Tracing and Coverage
        if (self::is_xdebug_active())
        {
            # get page parsing time from xdebug
            echo "<div id='xdebug'><center>";

            self::$_xdebug_memory_before .= 'Time to execute: '. round(xdebug_time_index(),4) . ' seconds';
            self::$_xdebug_memory_before .= '<br />Memory Usage by Clansuite ' . self::roundMB(xdebug_memory_usage()) . ' MB';
            self::$_xdebug_memory_before .= '<br />Memory Peak of ' . self::roundMB(xdebug_peak_memory_usage()) . ' MB';
            self::$_xdebug_memory_before .= '<br />Debug Trace is saved: '. @xdebug_stop_trace();
            echo self::$_xdebug_memory_before;

            # stop tracings and var_dump
            xdebug_dump_superglobals();
            echo "</center></div>";
            # var_dump(xdebug_get_code_coverage());
        }
    }

    /**
     * Rounds a $value to MegaBytes
     *
     * @param integer $value The Value to round to megabytes.
     * @return Returns the $value rounded to megabytes, like: 1,44MB.
     */
    public static function roundMB($value)
    {
        return round(($value/1048576),2);
    }

    /**
     * This is an replacement for the native php function print_r() with an upgraded display
     *
     * @author  Cagret @ pl.gosu.php/debug/printR.php
     * @version created 2005-06-18 modified 2006-06-04
     * @param   mixed/array/object $var Array or Object as Variable to display
     *
     * @returns Returns a better structured display of an array/object as native print_r
     */
    public static function printR($var)
    {
        while (ob_get_level())
        {
            ob_end_clean();
        }
        if (func_num_args() > 1)
        {
            $var = func_get_args();
        }
        echo '<pre>';
        $trace = array_shift((debug_backtrace()));
        echo "<b>Debugging <font color=red>".basename($trace['file'])."</font> on line <font color=red>{$trace['line']}</font></b>:\r\n";
        $file = file($trace['file']);
        echo "<div style='background: #f5f5f5; padding: 0.2em 0em;'>".htmlspecialchars($file[$trace['line']-1])."</div>\r\n";
        echo '<b>Type</b>: '.gettype($var)."\r\n";
        if (is_string($var)) { echo '<b>Length</b>: '.strlen($var)."\r\n"; }
        if (is_array($var)) { echo '<b>Length</b>: '.count($var)."\r\n"; }
        echo '<b>Value</b>: ';
        if($var === true)        { echo '<font color=green><b>true</b></font>'; }
        elseif($var === false)   { echo '<font color=red><b>false</b></font>'; }
        elseif($var === null)    { echo '<font color=red><b>null</b></font>'; }
        elseif($var === 0)       { echo "0"; }
        elseif(is_string($var) and strlen($var) == '0') { echo '<font color=green>*EMPTY STRING*</font>'; }
        elseif(is_string($var))  { echo htmlspecialchars($var); }
        else {
            $print_r = print_r($var, true);
            // str_contains < or >
            if ((strstr($print_r, '<') !== false) || (strstr($print_r, '>') !== false)) {
                $print_r = htmlspecialchars($print_r);
            }
            echo $print_r;
        }
        echo '</pre>';
        # save session before exit
        session_write_close();
        exit;
    }

    /**
     * Debug logs the output of $var to the firebug console
     *
     * @param mixed $var
     * @param $firebugmethod log,info,warn, error
     * @return Content of $var will be returned via Header and is displayed in the FireBugConsole.
     */
    public static function firebug($var, $firebugmethod = 'log' )
    {
        # get firephp instance, if class not existant
        if( class_exists('FirePHP', false) === false )
        {
            require ROOT_LIBRARIES.'firephp/FirePHP.class.php';
        }
        $firephp = FirePHP::getInstance(true);

        # get callstack and log the origin of the call to clansuite_xdebug::firebug()
        $backtrace_array = debug_backtrace();

        $firephp->info('You are debugging like fire in '.$backtrace_array[1]['class'].':'.$backtrace_array[1]['function'].'()
                        in file "'.$backtrace_array[0]['file'].'" line "'.$backtrace_array[0]['line'].'"');

        unset($backtrace_array);

        # debug the var
        $firephp->{$firebugmethod}($var);
    }

    public static function xd_varDump($var = null)
    {
        if(is_null($var))
        {
            die('clansuite_xdebug::xd_varDump() expects the variable you want to display as parameter.');
        }

        echo xdebug_var_dump($var);
    }

    public static function xd_break()
    {
        echo xdebug_break();
    }

    public static function showCallStack()
    {
        echo "CallStack - File: ".xdebug_call_file();
        echo "<br />Class: ".xdebug_call_class();
        echo "<br />Function: ".xdebug_call_function();
        echo "<br />Line: ".xdebug_call_line();
        echo "<br />Depth of Stacks: ".xdebug_get_stack_depth();
        echo "<br />Content of Stack:"; xdebug_var_dump(xdebug_get_function_stack());
    }

    /**
     * XDebug has the last word, if it was loaded.
     */
    public static function shutdown()
    {
        if(defined('SHUTDOWN_FUNCTION_SUPPRESSION') and SHUTDOWN_FUNCTION_SUPPRESSION == false)
        {
            # Stop the tracing and show debugging infos.
            clansuite_xdebug::end_xdebug();
        }
    }
}
?>