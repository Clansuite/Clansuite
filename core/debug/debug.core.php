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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
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
 * Clansuite_Debug
 *
 * This class initializes debugging helpers like xdebug, the doctrine profiler,
 * firebug and printR at system start-up and displays debug
 * and runtime-informations on demand or at application shutdown.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Debug
 */
class Clansuite_Debug
{
    /**
     * This is an replacement for the native php function print_r() with an upgraded display.
     *
     * @author  cagret@gosu.pl
     * @version created 2005-06-18 modified 2006-06-04
     * @param   mixed/array/object $var Array or Object as Variable to display
     *
     * @returns Returns a better structured display of an array/object as native print_r
     */
    public static function printR($var)
    {
        if (func_num_args() > 1)
        {
            $var = func_get_args();
        }

        $backtrace_array = array();
        $backtrace_array = debug_backtrace();
        $trace = array_shift($backtrace_array);
        $file = file($trace['file']);
        $trace_line = $file[$trace['line']-1];

        echo '<pre>';
        echo '<b>Debugging <font color=red>'.basename($trace['file']).'</font> on line <font color=red>'.$trace['line']."</font></b>:\r\n";
        echo "<div style='background: #f5f5f5; padding: 0.2em 0em;'>".htmlspecialchars($trace_line)."</div>\r\n";
        echo '<b>Type</b>: '.gettype($var)."\r\n"; # uhhh.. gettype is slow like hell

        if (is_string($var) === true)
        {
            echo '<b>Length</b>: ' . strlen($var)."\r\n";
        }

        if (is_array($var) === true)
        {
            echo '<b>Length</b>: '.count($var)."\r\n";
        }

        echo '<b>Value</b>: ';

        if($var === true)
        {
            echo '<font color=green><b>true</b></font>';
        }
        elseif($var === false)
        {
            echo '<font color=red><b>false</b></font>';
        }
        elseif($var === null)
        {
            echo '<font color=red><b>null</b></font>';
        }
        elseif($var === 0)
        {
            echo '0';
        }
        elseif(is_string($var) and strlen($var) == '0')
        {
            echo '<font color=green>*EMPTY STRING*</font>';
        }
        elseif(is_string($var))
        {
            echo htmlspecialchars($var);
        }
        else
        {
            $print_r = print_r($var, true);
            # str_contains < or >
            if ((strstr($print_r, '<') !== false) or (strstr($print_r, '>') !== false))
            {
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
     * Displays the content of a variable with var_dump.
     * The content gets escaping and pre tags are applied for better readability.
     *
     * @param mixed $var The variable to debug.
     * @param bool $stop Stop execution after dump? Default is true (stops).
     */
    public static function dump($var, $stop = true)
    {
        # var_dump the content into a buffer and store it to variable
        ob_start();
        var_dump($var);
        $var_dump = ob_get_clean();

        /**
         * if xdebug is on and overloaded the var_dump function,
         * then the output is already properly escaped and prepared for direct output.
         * if xdebug is off, we need to apply escaping ourself.
         * html pre tags are applied to structure the display a bit more.
         */
        if(false === extension_loaded('xdebug'))
        {
            $var_dump = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $var_dump);
            $var_dump = '<pre>' . htmlspecialchars($var_dump, ENT_QUOTES, 'UTF-8') . '</pre>';
        }

        # output the content of the buffer
        echo $var_dump;

        if($stop === true)
        {
            exit;
        }
    }

    /**
     * Debug logs the output of $var to the firebug console
     *
     * @param mixed $var The variable to debug.
     * @param $firebugmethod The firebug method to call (log,info,warn, error). Defaults to "log".
     *
     * @return Content of $var will be returned via Header and is displayed in the FireBugConsole.
     */
    public static function firebug($var, $firebugmethod = 'log')
    {
        # get firephp instance, if class not existant
        if( class_exists('FirePHP', false) === false )
        {
            include ROOT_LIBRARIES.'firephp/FirePHP.class.php';
        }

        $firephp = FirePHP::getInstance(true);

        /**
         * Adds an info message about the position of the firebug call (origin).
         * This is very helpful, if you spread Debug::firebug() calls all over your code.
         */
        $firephp->info(self::getWhereDebugWasCalled());

        # debug the var
        $firephp->{$firebugmethod}($var);
    }

    /**
     * Returns the position of a call.
     *
     * This is used in combination with Clansuite_Debug::firebug(),
     * to determine the origin of the call.
     *
     * @param type $level default 1.
     * @return string Message with origin of the debug call.
     */
    public static function getWhereDebugWasCalled($level = 1)
    {
        $trace  = array();
        $file = $line = $function = $class = $object = '';

        $trace  = debug_backtrace();
        $file     = $trace[$level]['file'];
        $line     = $trace[$level]['line'];
        $function = $trace[$level]['function'];
        $class    = $trace[$level]['class'];

        return sprintf(
            'You are debugging like fire in %s->%s() on line "%s" in file "%s".',
            $class, $function, $line, $file
        );
    }

    /**
     * Lists all currently included (or required) files.
     * Counts all included files and calculates total size of all inclusion.
     */
    public static function getIncludesFiles()
    {
        # init vars
        $includedFiles = $files = array();
        $includedFilesTotalSize = $includedFilesCount = 0;
        
        # fetch all included files
        $files = get_included_files();

        # loop over all included files and sum up filesize
        foreach ($files as $file)
        {
            $size = filesize($file);
            $includedFiles[] = array('name' => $file, 'size' => $size);
            $includedFilesTotalSize += $size;
        }

        # total number of included files
        $includedFilesCount = count($includedFiles);
        $includedFilesTotalSize = Clansuite_Functions::getsize($includedFilesTotalSize);

        self::printR(array('count' => $includedFilesCount, 'size' => $includedFilesTotalSize, 'files' => $includedFiles));
    }

    /**
     * Lists all user defined constants (Clansuite Constants).
     */
    public static function getClansuiteConstants()
    {
        $constants = get_defined_constants(true);
        self::printR($constants['user']);
    }

    /**
     * Displayes the debug backtrace.
     */
    public static function getBacktrace()
    {
        self::printR(debug_backtrace());
    }

    /**
     * Lists all declared interfaces.
     */
    public static function getInterfaces()
    {
        self::printR(get_declared_interfaces());
    }

    /**
     * Lists all declared classes.
     */
    public static function getClasses()
    {
        self::printR(get_declared_classes());
    }

    /**
     * Lists all declared functions.
     */
    public static function getFunctions()
    {
        self::printR(get_defined_functions());
    }

    /**
     * Lists all php extensions.
     */
    public static function getExtensions()
    {
        self::printR(get_loaded_extensions());
    }

    /**
     * Lists all php.ini settings.
     */
    public static function getPhpIni()
    {
        self::printR(parse_ini_file(get_cfg_var('cfg_file_path'), true));
    }

    /**
     * Lists all available wrappers
     */
    public static function getWrappers()
    {
        $w = stream_get_wrappers();
        echo 'openssl: '.  extension_loaded('openssl') ? 'yes':'no'. NL;
        echo 'http wrapper: '. in_array('http', $w) ? 'yes':'no'. NL;
        echo 'https wrapper: '. in_array('https', $w) ? 'yes':'no'. NL;
        echo 'wrappers: '. self::printR($w);
    }

    /**
     * Returns a list of all registered event listeners
     * @return array
     */
    public static function getRegisteredEventListeners()
    {
        # @todo
    }
}
?>