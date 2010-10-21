<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_XDebug
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
    public static $xdebug_memory_before = '';

    protected static $initialized = false;

    protected static $initSettings = array(
        'var_display_max_children' => 128,
        'var_display_max_data' => 512,
        'var_display_max_depth' => 10,
        'overload_var_dump' => 'on',
        'collect_params' => 2,
        'collect_vars' => true,
        'dump_globals' => true,
        'dump.GET' => '*',
        'dump.POST' => '*',
        'dump.COOKIE' => '*',
        'dump.SESSION' => '*',
        'show_local_vars' => false,
        'show_mem_delta' => true,
        'show_exception_trace' => false,
        'auto_trace' => false,
    );

    public static function configure()
    {
        if(static::$initialized === false)
        {
            # loop over all settings and set them
            foreach(static::$initSettings as $key => $value)
            {
                ini_set("xdebug.{$key}", $value);
            }
        }

        # set initialized status flag
        static::$initialized = true;
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
            # do some xdebug configuration
            self::configure();

            # set some more values manually

            # tracing
            #ini_set('xdebug.auto_trace', 'On');
            #ini_set('xdebug.trace_output_dir', ROOT_LOGS);
            #ini_set('xdebug.trace_output_name', 'clansuite_trace%u');

            self::$xdebug_memory_before = self::roundMB(xdebug_memory_usage());

            #xdebug_start_trace(ROOT_LOGS . 'clansuite_trace', XDEBUG_TRACE_HTML);

            #xdebug_start_code_coverage(XDEBUG_CC_DEAD_CODE | XDEBUG_CC_UNUSED);
            #var_dump(xdebug_get_code_coverage());
            #var_dump(xdebug_get_function_count());
            #xdebug_get_code_coverage();

            # stop tracing and display infos
            register_shutdown_function('clansuite_xdebug::shutdown');
        }
    }

    /**
     * Rendering function on Shutdown of XDEBUG
     */
    public static function render()
    {
        # Start XDEBUG Tracing and Coverage
        if (self::is_xdebug_active())
        {
            echo '<!-- Disable XDebug Mode to remove this!-->
              <style type="text/css">
              /*<![CDATA[*/
                table.xdebug-console, table.xdebug-superglobals {
                    background: none repeat scroll 0 0 #FFFFCC;
                    border-width: 1px;
                    border-style: outset;
                    border-color: #BF0000;
                    border-collapse: collapse;
                    font-size: 11px;
                    color: #222;
                 }
                table.xdebug-console th, table.xdebug-superglobals th {
                    border:1px inset #BF0000;
                    padding: 3px;
                    padding-bottom: 3px;
                    font-weight: bold;
                    background: #E03937;
                }
                table.xdebug-console td {
                    border:1px inset grey;
                    padding: 2px;
                }
                table.xdebug-console tr:hover {
                    background: #ffff88;
                }
                fieldset.xdebug-console legend {
                    background:#fff;
                    border:1px solid #333;
                    font-weight:700;
                    padding:2px 15px;
                }
                /*]]>*/
                </style>';

            echo '<p>&nbsp;</p><fieldset class="xdebug-console"><legend>XDebug Console</legend>';
            echo '<br/>' . xdebug_dump_superglobals() . '</br>';
            echo '<table class="xdebug-console" width="95%">';
            echo '<tr><th>Name</th><th>Value</th></tr>';
            echo '<tr>';
            echo '<td style="text-align: center;">Time to execute</td>';
            echo '<td>' . round(xdebug_time_index(), 4) . ' seconds</td>';
            echo '</tr><tr>';
            echo '<td style="text-align: center;">Memory Usage (before)</td>';
            echo '<td>' . self::$xdebug_memory_before . ' MB</td>';
            echo '</tr><tr>';
            echo '<td style="text-align: center;">Memory Usage by Clansuite</td>';
            echo '<td>' . self::roundMB(xdebug_memory_usage()) . ' MB</td>';
            echo '</tr><tr>';
            echo '<td style="text-align: center;">Memory Peak</td>';
            echo '<td>' . self::roundMB(xdebug_peak_memory_usage()) . ' MB</td>';
            echo '</tr>';
            # stop tracings and var_dump
            #var_dump(xdebug_get_code_coverage());
            echo '</table>';

            echo '<br/>';

            echo '<table class="xdebug-console" width="95%">';
            echo '<tr><th>#</th><th>Headers</th></tr>';
            $headers = xdebug_get_headers();
            $i = 0;
            foreach($headers as $header)
            {
                echo '<tr><td style="text-align: center;">' . $i++ . '</td><td>' . $header . '</td></tr>';
            }
            echo '</table>';
            echo '</fieldset>';
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
     * @param mixed $variable The variable to debug display.
     */
    public static function xd_varDump($var)
    {
        echo xdebug_var_dump($var);
    }

    public static function xd_break()
    {
        echo xdebug_break();
    }

    public static function showCallStack()
    {
        echo 'CallStack - File: '       . xdebug_call_file();
        echo '<br />Class: '            . xdebug_call_class();
        echo '<br />Function: '         . xdebug_call_function();
        echo '<br />Line: '             . xdebug_call_line();
        echo '<br />Depth of Stacks: '  . xdebug_get_stack_depth();
        echo '<br />Content of Stack: ' . xdebug_var_dump(xdebug_get_function_stack());
    }

    /**
     * XDebug has the last word, if it was loaded.
     */
    public static function shutdown()
    {
        # Stop the tracing and show debugging infos.
        clansuite_xdebug::render();
    }
}
?>