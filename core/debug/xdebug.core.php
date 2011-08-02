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

    protected static $outputConstants     = true;

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

            self::$xdebug_memory_before = self::roundMB(xdebug_memory_usage());

            # set some more values manually

            # tracing
            #ini_set('xdebug.auto_trace', 'On');
            #ini_set('xdebug.trace_output_dir', ROOT_LOGS);
            #ini_set('xdebug.trace_output_name', 'clansuite_trace%u');
            #xdebug_start_trace(ROOT_LOGS . 'clansuite_trace', XDEBUG_TRACE_HTML);

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
            /**
             * This is the CSS for XDebug Fatal Error
             */
            echo '<!-- Disable XDebug Mode to remove this!-->
                <style type="text/css">
                /*<![CDATA[*/
                table.xdebug-error {
                     font-size: 12px;
                     width: 95%;
                     margin: 0 auto 10px auto;
                     border-color: #666;
                     border-collapse: collapse;
                     border-style: outset;
                     color: #222222;
                }
               .xdebug-error th {
                    background: none repeat scroll 0 0 #E03937;
                    border: 1px inset #BF0000;
                    font-weight: bold;
                    padding: 3px;
                }
                .xdebug-error td {
                    background: none repeat scroll 0 0 #FFFFCC;
                    border: 1px solid grey;
                    padding: 2px 2px 2px 5px;
                    vertical-align: top;
                }
                .xdebug-error tr:hover td {
                    background: #ffff88;
                }
                .xdebug-error span {
                    display: none;
                }
                /** Custom Styles not related to XDEBUG **/
                .toggle {
                    font-size: 12px;
                    color: white;
                    float: left;
                }
                /*]]>*/
                </style>';

            /**
             * Reset for hardcoded bgcolor attributes in the "xdebug-error" table.
             * This will select all <td> elements and reset the bgcolor attribute on each element.
             */
            echo "<script type=\"text/javascript\">
                  var xdebugErrorTable = document.getElementsByClassName('xdebug-error');
                  if(xdebugErrorTable.length > 0)
                  {
                    var xdebugErrorTableTds = document.getElementsByClassName('xdebug-error')[0].getElementsByTagName('td');
                    for (var i = 0; i < xdebugErrorTableTds.length; i++){xdebugErrorTableTds[i].setAttribute('bgcolor', '');}
                  }";

            /**
             * Visibility Toggle + Toggle Text Change
             */
            echo 'function toggleXDebugTable(a){document.getElementById(a).style.display=="none"
                  ?(document.getElementById(a).style.display="table",
                    document.getElementById("toggle-icon-"+a).innerHTML="&#9660;")
                  :(document.getElementById(a).style.display="none",
                    document.getElementById("toggle-icon-"+a).innerHTML="&#9658;")};';
            echo '</script>';

            /**
             * This is the CSS for XDebug Console via xdebug_dump_superglobals()
             */
            echo '<!-- Disable XDebug Mode to remove this!-->
              <style type="text/css">
              /*<![CDATA[*/
                /* center outer div */
                #x-debug {
                    width: 95%;
                    padding:20px 0px 10px 0px;
                    background: #EFEFEF;
                    border: 1px solid #333;
                    margin: 0 auto; /* centering */
                    margin-top: 15px;
                    margin-bottom: 15px;
                }
                table.xdebug-console, table.xdebug-superglobals {
                    width: 100%;
                    background: none repeat scroll 0 0 #FFFFCC;
                    border-width: 1px;
                    border-style: outset;
                    border-color: #BF0000;
                    border-collapse: collapse;
                    color: #222;
                    font: 12px tahoma,verdana,arial,sans-serif;
                    margin-top: 5px;
                    margin-bottom: 8px;
                }
                table.xdebug-console th, table.xdebug-superglobals th {
                    border: 1px inset #BF0000;
                    padding: 3px;
                    padding-bottom: 3px;
                    font-weight: bold;
                    background: #E03937;
                }
                .xdebug-superglobals td {
                    border: 1px solid grey;
                    padding: 2px;
                    padding-left: 5px;
                    vertical-align:top;
                }
                table.xdebug-console td {
                    border: 1px inset grey;
                    padding: 2px;
                    padding-left: 5px;
                }
                table.xdebug-console td.td1 {width: 30%; padding-left:20px; color:#FF0000; }
                table.xdebug-console td.td2 {width: 70%;}
                table.xdebug-console tr:hover td, table.xdebug-superglobals tr:hover td {
                    background: #ffff88;
                }
                fieldset.xdebug-console {
                    background: none repeat scroll 0 0 #ccc;
                    border: 1px solid #666666;
                    font: 12px tahoma,verdana,arial,sans-serif;
                    margin: 10px auto;
                    padding: 10px;
                    width: 95%;
                }
                fieldset.xdebug-console legend {
                    background: #fff;
                    border: 1px solid #333;
                    font-weight: bold;
                    padding: 5px 15px;
                    color: #222;
                    float: left;
                    margin-top: -23px;
                    margin-bottom: 10px;
                }
                fieldset.xdebug-console pre {
                    margin: 2px;
                    text-align: left;
                    width: 100%;
                }
                /*]]>*/
                </style>
                 <!--[if IE]>
                 <style type="text/css">
                    #x-debug { width: 95%; padding:30px 0px 10px 0px;}
                    fieldset.xdebug-console legend {
                        position:relative;
                        top: -0.2em;
                    }
                 </style>
                 <![endif]-->';

            echo '<div id="x-debug"><fieldset class="xdebug-console"><legend>XDebug Console</legend>';
            echo xdebug_dump_superglobals();
            echo '<table class="xdebug-console">';
            echo '<tr><th>Name</th><th>Value</th></tr>';
            echo '<tr>';
            echo '<td class="td1">Time to execute</td>';
            echo '<td class="td2">' . round(xdebug_time_index(), 4) . ' seconds</td>';
            echo '</tr><tr>';
            echo '<td class="td1">Memory Usage (before)</td>';
            echo '<td class="td2">' . self::$xdebug_memory_before . ' MB</td>';
            echo '</tr><tr>';
            echo '<td class="td1">Memory Usage by Clansuite</td>';
            echo '<td class="td2">' . self::roundMB(xdebug_memory_usage()) . ' MB</td>';
            echo '</tr><tr>';
            echo '<td class="td1">Memory Peak</td>';
            echo '<td class="td2">' . self::roundMB(xdebug_peak_memory_usage()) . ' MB</td>';
            echo '</tr>';
            # stop tracings and var_dump
            #var_dump(xdebug_get_code_coverage());
            echo '</table>';



            self::showConstants();

            self::showBrowserInfo();

            self::showHttpHeaders();

            echo '</table>';

            echo '</fieldset></div>';

            /**
             * Reset for hardcoded bgcolor attributes in the "xdebug-superglobals" table.
             * This will select all <td> elements and reset the bgcolor attribute on each element.
             */
            echo "<script type=\"text/javascript\">
                  var xdebugTds = document.getElementsByClassName('xdebug-superglobals')[0].getElementsByTagName('td');
                  for (var i = 0; i < xdebugTds.length; i++){xdebugTds[i].setAttribute('bgcolor', '');}
                  xdebugTds[0].setAttribute('width', '30%');
                  </script>";
        }
    }

    public static function showConstants()
    {
        # fetch all defined constants ordered by categories as key
        $aConsts = get_defined_constants(true);

        /**
         * This array defines
         * (a) the categories to show and
         * (b) the order of their appearance in the constants table.
         */
        $aCategoriesToShow = array(
            'user', # user category contains clansuite system defines - leave at first position!
            'apc',
            'mbstring',
            'xdebug',
        );

        echo self::getSectionHeadlineHTML('Konstanten');

        echo '<table class="xdebug-console" id="table-konstanten" style="display:none;">';

        foreach ($aCategoriesToShow as $category)
        {
            # display only the categories to show / whitelist
            if (isset($aConsts[$category]) === true)
            {
                # adjust headline
                if ($category == 'user')
                {
                    echo '<tr><th colspan="2">Clansuite Constants</th></tr>';
                }
                else
                {
                    echo '<tr><th colspan="2">Constants for [' . $category . ']</th></tr>';
                }

                # table row for the constant
                foreach ($aConsts[$category] as $name => $value)
                {
                    echo '<tr><td class="td1">' . $name . '</td>';
                    
                    # handle true and false
                    if(gettype($value) === 'boolean')
                    {                       
                        echo '<td class="td2">' . (int) $value . '</td></tr>'; 
                    }
                    else
                    {
                        echo '<td class="td2">' . self::formatter($value) . '</td></tr>';
                    }
                }
            }
        }
        echo '</table>';
    }

    public static function showBrowserInfo()
    {
        if(false === class_exists('Clansuite_Browserinfo', false))
        {
            include ROOT_CORE . 'tools/browserinfo.core.php';            
        }
        $browserinfo = new Clansuite_Browserinfo();
        $browser = $browserinfo->getBrowserInfo();

        echo self::getSectionHeadlineHTML('Browserinfo');
        echo '<table class="xdebug-console" id="table-browserinfo" style="display:none;">';
        echo '<tr><th>Name</th><th>Value</th></tr>';

        echo '<td class="td1"><b>Browser-Information</b></td>';
        echo '<td class="td2">';
        echo 'Browser: <b>' . $browser['name'] . "</b><br/>";
        echo 'Version:&nbsp;&nbsp;<b>' . $browser['version'] . "</b><br/>";
        echo 'Engine:&nbsp;&nbsp;&nbsp;' . $browser['engine'] . "<br/>";
        echo 'Browser ist Bot: ' . ($browserinfo->isBot() ? 'Ja' : 'Nein') . "<br/>";
        echo 'Betriebssystem: ' . $browser['os'] . "<br/>";
        echo '</td></tr>';
    }

    public static function showHttpHeaders()
    {
        echo self::getSectionHeadlineHTML('HttpHeaders');
        echo '<table class="xdebug-console" id="table-httpheaders" style="display:none;">';
        echo '<tr><th>Name</th><th>Value</th></tr>';

        $headers = xdebug_get_headers();
        foreach ($headers as $header)
        {
            /**
             * a header like "Expires: Thu, 19 Nov 1981 08:52:00 GMT" has several doublecolons (:)
             * we split the string by exploding it, at the first colon and returning a limited result set (2 items):
             * $headers['0'] = 'Expires';
             * $headers['1'] = 'Thu, 19 Nov 1981 08:52:00 GMT';
             */
            $headers = explode(':', $header, 2);

            echo '<tr><td class="td1">' . $headers[0] . '</td>';
            echo '<td class="td2">' . trim($headers[1]) . '</td></tr>';
        }
        echo '</table>';
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

    public static function formatter($buffer)
    {
        $buffer = str_replace("\r", "\\r", $buffer);
        $buffer = str_replace("\n", "\\n", $buffer);
        $buffer = str_replace("\t", "\\t", $buffer);
        $buffer = str_replace('<', '&lt;', $buffer);
        $buffer = str_replace('>', '&gt;', $buffer);
        return $buffer;
    }

    /**
     * Returns the HTML for a Section Headline of the XDEBUG Console
     *
     * Note:
     * If you want to use visibility toggle for a table, you have to
     * apply the attribute id="table-$name" to the table tag.
     * Where $name is the hardcoded(!) parameter of this function.
     *
     * @example
     * echo getSectionHeadlineHTML('System');
     * <table id="table-system">
     *
     * @param string $section_name Name of the Section.
     */
    public static function getSectionHeadlineHTML($name)
    {
        $html = '';
        $html .= '<table class="xdebug-console">';
        $html .= '<tr>';
        $html .= '<th style="background: #BF0000; color: #ffffff;">';
        $html .= '<a href="javascript:;" onclick="toggleXDebugTable(\'table-' . strtolower($name) . '\')"';
        $html .= 'title="Toggle (show/hide) the visibility." style="color: #fff;">';
        $html .= '<span class="toggle" id="toggle-icon-table-' . strtolower($name) . '">&#9658;</span></a>';
        $html .= ucfirst($name);
        $html .= '</th></tr></table>';

        return $html;

    }

}
?>