<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch � 2005 - onwards
    * http://www.clansuite.com/
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  Jens-Andre Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
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
 * @package     clansuite
 * @category    core
 * @subpackage  xdebug
 */
class clansuite_xdebug
{

    public static $_xdebug_memory_before = '';

    /**
     * XDebug Helper Functions
     *
     * @static
     * @access public
     */
    public static function is_xdebug_active()
    {
        if (function_exists('xdebug_start_trace'))
        {
            #echo 'Xdebug ON';
            return true;
        }
        echo 'Xdebug OFF'; return false;
    }

    /**
     * Initializes XDEBUG
     *
     * @static
     * @access public
     */
    public static function start_xdebug()
    {
        # Start XDEBUG Tracing and Coverage
        if (self::is_xdebug_active())
        {
            #ini_set('xdebug.auto_trace', 'On');
            #ini_set('xdebug.trace_output_dir', getcwd() . '/logs/');
            #ini_set('xdebug.trace_output_name', 'clansuite_trace%u');
            ini_set('xdebug.show_mem_delta', 'On');
            ini_set('xdebug_start_code_coverage', 'XDEBUG_CC_UNUSED');
            ini_set('xdebug.xdebug.collect_return', 'On');
            ini_set('xdebug.var_display_max_children', 10 );

            self::$_xdebug_memory_before = 'Memory Usage (before): ' . self::roundMB(xdebug_memory_usage()) . ' MB.<hr />';

            xdebug_start_trace(getcwd() . '/logs/clansuite_trace', XDEBUG_TRACE_HTML);
            xdebug_get_code_coverage();
        }
    }

    /**
     * Shutdown XDEBUG and give some Debugging Reports
     *
     * @static
     * @access public
     */
    public static function end_xdebug()
    {
        # get page parsing time from xdebug
        self::$_xdebug_memory_before .= 'Time to execute: '. round(xdebug_time_index(),4) . ' seconds.';
        self::$_xdebug_memory_before .= '<br />Memory Usage by Clansuite ' . self::roundMB(xdebug_memory_usage()) . ' MB.';
        self::$_xdebug_memory_before .= '<br />Memory Peak of ' . self::roundMB(xdebug_peak_memory_usage()) . ' MB. <br /><br />';

        echo self::$_xdebug_memory_before;

        # stop tracings and var_dump
        var_dump(xdebug_stop_trace());
        var_dump(xdebug_get_code_coverage());
    }

    /**
     * Rounds a $value to MegaBytes
     *
     * @param integer $value The Value to round to megabytes.
     * @static
     * @access public
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
     * @static
     * @access  public
     *
     * @returns Returns a better structured display of an array/object as native print_r
     */
    public static function printR($var)
    {
        while (ob_get_level()) { ob_end_clean(); }
        if (func_num_args() > 1) $var = func_get_args();

        echo '<pre>';
        $trace = array_shift((debug_backtrace()));
        echo "<b>Debugging <font color=red>".basename($trace['file'])."</font> on line <font color=red>{$trace['line']}</font></b>:\r\n";
        $file = file($trace['file']);
        echo "<div style='background: #f5f5f5; padding: 0.2em 0em;'>".htmlspecialchars($file[$trace['line']-1])."</div>\r\n";
        echo '<b>Type</b>: '.gettype($var)."\r\n";
        if (is_string($var)) echo "<b>Length</b>: ".strlen($var)."\r\n";
        if (is_array($var)) echo "<b>Length</b>: ".count($var)."\r\n";
        echo '<b>Value</b>: ';
        if (is_string($var)) echo htmlspecialchars($var);
        else {
            $print_r = print_r($var, true);
            // str_contains < or >
            if ((strstr($print_r, '<') !== false) || (strstr($print_r, '>') !== false)) {
                $print_r = htmlspecialchars($print_r);
            }
            echo $print_r;
        }
        echo '</pre>';
        exit;
    }
}
?>