<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-2008
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
    * @copyright  Jens-Andre Koch (2005-2008)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

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
    /**
     * XDebug Helper Functions
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

        echo 'Memory Usage (before): ' . self::roundMB(xdebug_memory_usage()) . ' MB.<hr />';

        xdebug_start_trace(getcwd() . '/logs/clansuite_trace', XDEBUG_TRACE_HTML);
        xdebug_get_code_coverage();
        }
    }

    public static function end_xdebug()
    {
        # get page parsing time from xdebug
        echo '<hr>';
        echo 'Time to execute: '. round(xdebug_time_index(),4) . ' seconds.';
        echo '<br />Memory Usage by Clansuite ' . self::roundMB(xdebug_memory_usage()) . ' MB.';
        echo '<br />Memory Peak of ' . self::roundMB(xdebug_peak_memory_usage()) . ' MB. <br /><br />';
        # stop tracings and var_dump
        var_dump(xdebug_stop_trace());
        var_dump(xdebug_get_code_coverage());
    }

    public static function roundMB($value)
    {
        return round(($value/1048576),2);
    }
}
?>