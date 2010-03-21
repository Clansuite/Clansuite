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
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

//Security Handler
if (defined('IN_CS') == false) { die('Clansuite not loaded. Direct Access forbidden.'); }

/**
 * This Clansuite Core Class for Errorhandling
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Errorhandler
 */
class Clansuite_Errorhandler
{
    private $config; # holds configuration instance

    private static $errorstack = array(); # holds errorstack elements

    /**
     * Errorhandler Constructor
     *
     * Sets up the ErrorHandler
     *
     * Usage:
     * trigger_error('Errormessage', E_ERROR_TYPE);
     * E_ERROR_TYPE as string or int
     */
    function __construct(Clansuite_Config $config)
    {
        $this->config   = $config; # set instance of configuration

        # register own error handler
        set_error_handler(array(&$this,'clansuite_error_handler'));

        # DEBUG Test the errorhandler with the following function
        #trigger_error('Errorhandler Test - This should trigger a E_USER_NOTICE!', E_USER_NOTICE);

        # register own shutdown function
        #register_shutdown_function(array(&$this,'shutdown_and_exit'));
    }

    /**
     * Add an Error to the ErrorStack
     *
     * @param $errormessage
     * @param $errorcode
     */
    public static function addError($errormessage, $errorcode)
    {
        self::$errorstack[] = array('message' => $errormessage, 'code' => $errorcode);
    }

    /**
     * toString Magic Method to display the Errorstack
     */
    public static function toString()
    {
        $output = '';

        foreach(self::$errorstack as $error)
        {
            $output .= '<font color="#DF2F3D"> Error: '. $error['message'] .' Code: ' .$error['code'] . "</font><br /> \n\n";
        }
        return $output;
    }

    /**
     * Method to check if any errors were set
     *
     * @return boolean true, if errors were set
     */
    public static function hasErrors()
    {
        if(!empty (self::$errorstack))
        {
            return true;
        }
        return false;
    }

    /**
     * Get Method for fetching the whole Errorstack
     *
     * @return errorhandler:$errorstack
     */
    public static function getErrorStack()
    {
        return self::$errorstack;
    }

    /**
     * Clansuite Error callback.
     *
     * This is basically a switch defining the actions taken,
     * in case of serveral PHP error states
     *
     * @link http://www.usegroup.de/software/phptutorial/debugging.html
     * @link http://www.php.net/manual/de/function.set-error-handler.php
     * @link http://www.php.net/manual/de/errorfunc.constants.php
     *
     * @param integer $errornumber contains the error as integer
     * @param string $errorstring contains error string info
     * @param string $errorfile contains the filename with occuring error
     * @param string $errorline contains the line of error
     */
    public function clansuite_error_handler( $errornumber, $errorstring, $errorfile, $errorline, $errorcontext )
    {
        # do just return, if silenced (in case of @ operator) or DEBUG mode active
        if((error_reporting() == 0))
        {
            return;
        }

        /**
         * Assemble the error informations
         */
        # set the error time
        $errortime = date($this->config['date_format'].' '.$this->config['time_format']);

        /**
         * Definition of PHP Errortypes Array - with names for all the php error codes
         * @link http://php.oss.eznetsols.org/manual/de/errorfunc.constants.php
         * @todo return correct errorcodes for the actual php version (array is for 5.2.x)
         */
        $errorTypes = array (    1      => 'E_ERROR',               # fatal run-time errors, like php is failing memory allocation
                                 2      => 'E_WARNING',             # Run-time warnings (non-fatal errors)
                                 4      => 'E_PARSE',               # compile-time parse errors - generated by the parser
                                 8      => 'E_NOTICE',              # Run-time notices (could be an indicator for an error)
                                 16     => 'E_CORE_ERROR',          # PHP Core reports errors in PHP's initial startup
                                 32     => 'E_CORE_WARNING',        # PHP Core reports warning (non-fatal errors)
                                 64     => 'E_COMPILE_ERROR',       # Zend Script Engine reports fatal compile-time errors
                                 128    => 'E_COMPILE_WARNING',     # Zend Script Engine reports compile-time warnings (non-fatal errors)
                                 256    => 'E_USER_ERROR',          # trigger_error() / user_error() reports user-defined error
                                 512    => 'E_USER_WARNING',        # trigger_error() / user_error() reports user-defined warning
                                 1024   => 'E_USER_NOTICE',         # trigger_error() / user_error() reports user-defined notice
                                #2047   => 'E_ALL 2047 PHP <5.2.x', # all errors and warnings + old value of E_ALL of PHP Version below 5.2.x
                                 2048   => 'E_STRICT',              # PHP suggests codechanges to ensure interoperability / forwad compat
                                 4096   => 'E_RECOVERABLE_ERROR',   # catchable fatal error, if not catched it's an e_error (since PHP 5.2.0)
                                 6143   => 'E_ALL 6143 PHP5.2.x',   # all errors and warnings + old value of E_ALL of PHP Version 5.2.x
                                #8191   => 'E_ALL 8191'             # PHP 6 -> 8191
                                #8192   => 'E_DEPRECATED',          # notice marker for 'in future' deprecated php-functions (since PHP 5.3.0)
                                #16384  => 'E_USER_DEPRECATED',     # trigger_error() / user_error() reports user-defined deprecated functions
                                #30719  => 'E_ALL 30719 PHP5.3.x',  # all errors and warnings - E_ALL of PHP Version 5.3.x
                                #32767  => 'E_ALL 32767 PHP6'       # all errors and warnings - E_ALL of PHP Version 6
                                 );


        # check if the error number exists in the errortypes array
        if (array_key_exists($errornumber, $errorTypes))
        {
            # get the errorname from the array via $errornumber
            $errorname = $errorTypes[$errornumber];
        }

        # Handling the ErrorType via Switch
        switch ($errorname)
        {
            # What are the errortypes that can be handled by a user-defined errorhandler?
            case 'E_WARNING':                 $errorname .= ' [php warning]'; break;
            case 'E_NOTICE':                  $errorname .= ' [php notice]'; break;
            case 'E_USER_ERROR':              $errorname .= ' [Clansuite Internal Error]'; break;
            case 'E_USER_WARNING':            $errorname .= ' [Clansuite Internal Error]'; break;
            case 'E_USER_NOTICE':             $errorname .= ' [Clansuite Internal Error]'; break;
            #case 'E_ALL':
            case 'E_STRICT':                  $errorname .= ' [php strict]'; break;
            #case 'E_RECOVERABLE_ERROR':
            # when it's not in there, its an unknown errorcode
            default:                        $errorname .= ' Unknown Errorcode ['. $errornumber .']: ';
        }

        # if DEBUG is set, display the error
        if ( defined('DEBUG') and DEBUG == 1 )
        {
            # SMARTY ERRORS are thrown by trigger_error() - so they bubble up as E_USER_ERROR
            # so we need to detect if an E_USER_ERROR is incoming from SMARTY or from a template_c file (extension tpl.php)
            if( (strpos(strtolower($errorfile),'smarty') == true) or (strpos(strtolower($errorfile),'tpl.php') == true) )
            {
                # ok it's an Smarty Template Error - show the error via smarty_error_display inside the template
                echo $this->smarty_error_display( $errornumber, $errorname, $errorstring, $errorfile, $errorline, $errorcontext );
            }
            else # give normal Error Display
            {
                # All Error Informations (except backtraces)
                echo $this->ysod( $errornumber, $errorname, $errorstring, $errorfile, $errorline, $errorcontext );
            }
        }

        /*
        # if config setting log_errors is true, log the errormessage also to file
        if($this->config['errors']['log_to_file'] == true)
        {
            Clansuite_Logger::log();
        }
        */

        # Skip PHP internal error handler
        return true;
    }

    /**
     * Smarty Error Display
     *
     * This method defines the html-output when an Smarty Template Error occurs.
     * It's output is a shortened version of the normal error report, presenting
     * only errorname, filename and the line of the error.
     * The parameters used for the small report are $errorname, $errorfile, $errorline.
     * If you need a full errorreport, you can add more parameters from the methodsignature
     * to the $errormessage output.
     *
     * A Smarty Template Error is only displayed, when Clansuite is in DEBUG Mode.
     * @see clansuite_error_handler()
     *
     * The directlink to the templateeditor to edit the template file with the error is only available,
     * when Clansuite runs in DEVELOPMENT Mode.
     * @see addTemplateEditorLink()
     *
     * @param $errornumber
     * @param $errorname
     * @param $errorstring
     * @param $errorfile
     * @param $errorline
     * @param $errorcontext
     */
    private function smarty_error_display( $errornumber, $errorname, $errorstring, $errorfile, $errorline, $errorcontext )
    {
        # small errorreport
        $errormessage  =  '<h3><font color="#ff0000">&raquo; Smarty Template Error &laquo;</font></h3>';
        $errormessage .=  "<u>$errorname:</u><br/>";
        $errormessage .=  '<b>'. wordwrap($errorstring,50,"\n") .'</b><br/>';
        $errormessage .=  "File: $errorfile <br/>Line: $errorline ";
        $errormessage .= self::addTemplateEditorLink($errorfile, $errorline, $errorcontext);
        $errormessage .=  '<br/>';

        return $errormessage;
    }

    /**
     * addTemplateEditorLink
     *
     * a) constructs a valid path to the errorous template file
     * b) provides the html-link to the templateeditor for this file
     *
     * @param $errorfile Template File with the Error.
     * @param $errorline Line Number of the Error.
     * @todo correct link to the templateeditor
     */
    private static function addTemplateEditorLink($errorfile, $errorline, $errorcontext)
    {
        # display the link to the templateeditor,
        # if we are in DEVELOPMENT MODE and if the error relates to a template file
        if(defined('DEVELOPMENT') and DEVELOPMENT === 1 and (strpos(strtolower($errorfile),'.tpl') == true))
        {
            #clansuite_xdebug::printR($errorcontext);

            $tpl_vars = $errorcontext['this']->getTemplateVars();

            if(isset($tpl_vars['templatename']))
            {
                $errorfile = $tpl_vars['templatename'];
            }
            else
            {
                $errorfile = $errorcontext['resource_name'];
            }

            #clansuite_xdebug::printR($errorfile);

            # construct the link to the tpl-editor
            $link_tpledit  = '<br/><a href="index.php?mod=templatemanager&amp;sub=admin&amp;action=editor';
            $link_tpledit .= '&amp;file='.$errorfile;
            $link_tpledit .= '&amp;line='.$errorline;
            $link_tpledit .= '">Edit the Template</a>';

            # return the link
            return $link_tpledit;
        }
    }

    /**
     * Yellow Screen of Death (YSOD) is used to display a Clansuite Error
     *
     * @param int $errornumber
     * @param string $errorname
     * @param string $errorstring
     * @param string $errorfile
     * @param int $errorline
     * @param string $errorcontext
     */
    private function ysod( $errornumber, $errorname, $errorstring, $errorfile, $errorline, $errorcontext )
    {
        # Header
        $errormessage    = '<html><head>';
        $errormessage   .= '<title>Clansuite Error : [ '. wordwrap($errorstring,40,"\n") .' | Code: '. $errornumber .' ] </title>';
        $errormessage   .= '<body>';
        $errormessage   .= '<link rel="stylesheet" href="'. WWW_ROOT_THEMES_CORE .'/css/error.css" type="text/css" />';
        $errormessage   .= '</head>';
        # Body
        $errormessage   .= '<body>';

        # Fieldset with colours (error_red, error_orange, error_beige)
        if ($errornumber == 256)        { echo '<fieldset class="error_red">';    }
        elseif ($errornumber == 512)    { echo '<fieldset class="error_orange">'; }
        elseif ($errornumber == 1024)   { echo '<fieldset class="error_beige">';  }
        elseif ($errornumber > 0)       { echo '<fieldset class="error_beige">';  }

        # Errorlogo
        $errormessage   .= '<div style="float: left; margin: 5px; margin-right: 25px; border:1px inset #bf0000; padding: 20px;">';
        $errormessage   .= '<img src="'. WWW_ROOT_THEMES_CORE .'/images/Clansuite-Toolbar-Icon-64-error.png" style="border: 2px groove #000000;"/></div>';
        # Fieldset Legend
        $errormessage   .= '<legend>Clansuite Error : [ '. wordwrap($errorstring,50,"\n") .' ] </legend>';

        # Error Messages
        $errormessage   .= '<table>';
        $errormessage   .= '<tr><td colspan="2"><h3>'. wordwrap($errorstring,50,"\n") .'</h3></td></tr>';
        $errormessage   .= '<tr><td width=15%><strong>Errorcode: </strong></td><td>'.$errorname.' ('.$errornumber.')</td></tr>';
        $errormessage   .= '<tr><td><strong>Message: </strong></td><td>'.$errorstring.'</td></tr>';
        $errormessage   .= '<tr><td><strong>Path: </strong></td><td>'. dirname($errorfile).'</td></tr>';
        $errormessage   .= '<tr><td><strong>File: </strong></td><td>'. basename($errorfile).'</td></tr>';
        $errormessage   .= '<tr><td><strong>Line: </strong></td><td>'.$errorline.'</td></tr>';

        # HR Split
        $errormessage   .= '<tr><td colspan="2">&nbsp;</td></tr>';

        # Error Context
        $errormessage  .= '<tr><td colspan="2"><h3>Context</h3></td></tr>';
        $errormessage  .= '<tr><td colspan="2">'.self::getErrorContext($errorfile, $errorline, 8).'</td></tr>';

        # HR Split
        $errormessage   .= '<tr><td colspan="2">&nbsp;</td></tr>';

        # Environmental Informations at Errortime ( $errorcontext is not displayed )
        $errormessage  .= '<tr><td colspan="2"><h3>Server Environment</h3></td></tr>';
        $errormessage   .= '<tr><td><strong>Date: </strong></td><td>'.date('r').'</td></tr>';
        $errormessage   .= '<tr><td><strong>Request: </strong></td><td>'.htmlentities($_SERVER['QUERY_STRING'], ENT_QUOTES).'</td></tr>';
        $errormessage   .= '<tr><td><strong>Server: </strong></td><td>'.$_SERVER['SERVER_SOFTWARE'].'</td></tr>';
        $errormessage   .= '<tr><td><strong>Remote: </strong></td><td>'.$_SERVER['REMOTE_ADDR'].'</td></tr>';
        $errormessage   .= '<tr><td><strong>Agent: </strong></td><td>'.$_SERVER['HTTP_USER_AGENT'].'</td></tr>';
        $errormessage  .= '<tr><td><strong>Clansuite: </strong></td><td>'.CLANSUITE_VERSION.' '.CLANSUITE_VERSION_STATE.' ('.CLANSUITE_VERSION_NAME.') [Revision #'.CLANSUITE_REVISION.']</td></tr>';

        # HR Split
        $errormessage   .= '<tr><td colspan="2">&nbsp;</td></tr>';

        # Add Debug Backtracing
        $errormessage   .= '<tr><td>' . self::getDebugBacktrace() . '</td></tr>';

        # HR Split
        $errormessage   .= '<tr><td colspan="2">&nbsp;</td></tr>';

        # close all html elements: table, fieldset, body+page
        $errormessage   .= '</table>';

        # Footer with Support-Backlinks
        $errormessage  .= '<div style="float:right;">';
        $errormessage  .= '<strong><!-- Live Support JavaScript -->
                           <script type="text/javascript"
                              src="http://www.clansuite.com/livezilla/image.php?v=PGEgaHJlZj1cImphdmFzY3JpcHQ6dm9pZCh3aW5kb3cub3BlbignaHR0cDovL3d3dy5jbGFuc3VpdGUuY29tL2xpdmV6aWxsYS9saXZlemlsbGEucGhwP2NvZGU9UlhoalpYQjBhVzl1TDBWeWNtOXkmYW1wO3Jlc2V0PXRydWUnLCcnLCd3aWR0aD02MDAsaGVpZ2h0PTYwMCxsZWZ0PTAsdG9wPTAscmVzaXphYmxlPXllcyxtZW51YmFyPW5vLGxvY2F0aW9uPXllcyxzdGF0dXM9eWVzLHNjcm9sbGJhcnM9eWVzJykpXCIgPCEtLWNsYXNzLS0-PjwhLS10ZXh0LS0-PC9hPjwhPkxpdmUgSGVscCAoQ2hhdCBzdGFydGVuKTwhPkxpdmUgSGVscCAoTmFjaHJpY2h0IGhpbnRlcmxhc3Nlbik8IT4_">
                           </script>
                           <noscript>
                              <div><a href="http://www.clansuite.com/livezilla/livezilla.php?code=RXhjZXB0aW9uL0Vycm9y&amp;reset=true" target="_blank">Contact Support (Start Chat)</a></div>
                           </noscript>
                           <!-- Live Support JavaScript --></strong> | ';
        $errormessage  .= '<strong><a href="http://trac.clansuite.com/newticket/">Bug-Report</a></strong> |
                           <strong><a href="http://forum.clansuite.com/">Support-Forum</a></strong> |
                           <strong><a href="http://docs.clansuite.com/">Manuals</a></strong> |
                           <strong><a href="http://www.clansuite.com/">visit clansuite.com</a></strong>
                           </div>';

        # close all html elements: fieldset, body+page
        $errormessage   .= '</fieldset><br /><br />';
        $errormessage   .= '</body></html>';

        # save session
        session_write_close();

        # Output the errormessage
        return $errormessage;
    }

    /**
     * getDebugBacktrace
     *
     * Transforms the output of php's debug_backtrace() to a more readable html format.
     *
     * @return string $backtrace_string contains the backtrace
     * @todo: translations
     */
    private static function getDebugBacktrace()
    {
        # provide backtrace only when we are in Clansuite DEBUG Mode
        # if we are not in debug mode, just return
        if ( defined('DEBUG') == false xor DEBUG == 0 )
        {
            return;
        }

        # get php's backtrace output
        $debug_backtrace = debug_backtrace();

        # get rid of several last calls in the backtrace stack
        # a) call to getDebugBacktrace()
        array_shift($debug_backtrace);
        # b) call to ysod()
        array_shift($debug_backtrace);
        # c) call to trigger_error() [php core function call]
        array_shift($debug_backtrace);

        # prepare a new backtrace_string
        $backtrace_string = '';
        $backtrace_string .= '<tr><td><h3>Backtrace</h3></td></tr>';
        $backtrace_string .= '<tr><td><strong>Callstack</strong></td><td>(Recent function calls last)</td>';

        # restructure the debug_backtrace
        $backtrace_counter_i = count($debug_backtrace) - 1;
        for($i = 0; $i <= $backtrace_counter_i; $i++)
        {
            $backtrace_string .= '<tr><td><br />Call #'.($backtrace_counter_i-$i+1).'</td></tr>';

            if(!isset($debug_backtrace[$i]['file']))
            {
                $backtrace_string .= '<tr><td><strong>[PHP Core Function called]</strong></td>';
            }
            else
            {
                $backtrace_string .= '<tr><td><strong>File: </strong></td><td>' . $debug_backtrace[$i]['file'] . '</td>';
            }

            if(isset($debug_backtrace[$i]['line']))
            {
                $backtrace_string .= '</tr>';
                $backtrace_string .= '<tr><td><strong>Line: </strong></td><td>' . $debug_backtrace[$i]['line'] . '</td></tr>';
                $backtrace_string .= '<tr><td><strong>Function: </strong></td><td>' . $debug_backtrace[$i]['function'] . '</td></tr>';
            }

            if(isset($debug_backtrace[$i]['args']))
            {
                $backtrace_string .= '<tr><td><strong>Arguments: </strong></td><td>';

                $backtrace_counter_j = count($debug_backtrace[$i]['args']) - 1;
                for($j = 0; $j <= $backtrace_counter_j; $j++)
                {
                    $backtrace_string .= self::formatBacktraceArgument($debug_backtrace[$i]['args'][$j]);

                    # if we have several arguments to loop over
                    if($j != $backtrace_counter_j)
                    {
                        # we split them by comma
                        $backtrace_string .= ', ';
                    }
                }
            }

            # spacer
            $backtrace_string .= '</td></tr>';
        }

        # returns the Backtrace String
        return $backtrace_string;
    }

    /**
     * formatBacktraceArgument
     *
     * performs a type check on an backtrace argument and formats it nicely
     *
     * This formater is based on comments for debug-backtrace in the php manual
     * @link http://de2.php.net/manual/en/function.debug-backtrace.php#30296
     * @link http://de2.php.net/manual/en/function.debug-backtrace.php#47644
     *
     * @param backtraceArgument mixed The argument to identify the type upon and perform a string formatting on.
     *
     * @return string
     */
    public static function formatBacktraceArgument($backtraceArgument)
    {
        $args = '';

        switch (gettype($backtraceArgument))
        {
            case 'boolean':
                $args .= '<span>bool</span> ';
                $args .= $backtraceArgument ? 'TRUE' : 'FALSE';
                break;
            case 'integer':
                $args .= '<span>int</span> ';
                $args .= $backtraceArgument;
                break;
            case 'float':
                $args .= '<span>float</span> ';
                $args .= $backtraceArgument;
                break;
            case 'double':
                $args .= '<span>double</span> ';
                $args .= $backtraceArgument;
                break;
            case 'string':
                $args .= '<span>string</span> ';
                $backtraceArgument = htmlspecialchars(substr($backtraceArgument, 0, 64)).((strlen($backtraceArgument) > 64) ? '...' : '');
                $args .= "\"$backtraceArgument\"";
                break;
            case 'array':
                $args .= '<span>array</span> ('.count($backtraceArgument).')';
                break;
            case 'object':
                $args .= '<span>object</span> ('.get_class($backtraceArgument).')';
                break;
            case 'resource':
                $args .= '<span>resource</span> ('.strstr($backtraceArgument, '#').' - '. get_resource_type($backtraceArgument) .')';
                break;
            case 'NULL':
                $args .= '<span>null</span> ';
                break;
            default:
                $args .= 'Unknown';
        }
        return $args;
    }

    /**
     * getErrorContext displayes some additional lines of sourcecode around the line with error.
     *
     * This is based on a code-snippet posted on the php manual website by
     * @author dynamicflurry [at] gmail dot com
     * @link http://us3.php.net/manual/en/function.highlight-file.php#92697
     *
     * @param int $scope the context scope (defining how many lines surrounding the error are displayed)
     * @param int $line the line with the error in it
     * @param string $file file with the error in it
     */
    public static function getErrorContext($file, $line, $scope)
    {
        # ensure error context is only shown, when in debug mode
        if(defined('DEVELOPMENT') and DEVELOPMENT == 1  and defined('DEBUG') and DEBUG == 1)
        {
            # ensure that sourcefile is readable
            if (is_readable($file))
            {
                # Scope Calculations
                $surrounding_lines = round($scope/2);
                $errorcontext_starting_line = $line - $surrounding_lines;
                $errorcontext_ending_line = $line + $surrounding_lines;

                # get linenumbers array
                $lines_array = range($errorcontext_starting_line+1, $errorcontext_ending_line);

                # now colourize the errorous linenumber
                $lines_array[$scope-$surrounding_lines-1]  = '<span style="color: white; background-color:#BF0000;">'. $lines_array[$scope-$surrounding_lines-1]  .'</span>';

                # transform linenumbers array to string for later display
                $lines  = implode($lines_array, '<br />');

                # get ALL LINES syntax highlighted source-code of the file and explode it into an array
                $array_content = explode('<br />', highlight_file($file, true));

                # get the ERROR SURROUNDING LINES from ALL LINES
                $array_content_sliced = array_slice($array_content, $errorcontext_starting_line, $scope, true);

                /**
                 * reindexig the array,
                 * we need the first element’s index being [1], because linenumbers don't start with [0]
                 * think of this problem moved from [0] to the [$errorcontext_starting_line] (because of slicing)
                 * this is not working on the whole array, but reindexing only the sliced segment
                 */
                $result = array();
                foreach ( $array_content_sliced as $key => $val )
                {
                    $result[ $key+1 ] = $val;
                }

                # now colourize the background of the errorous line with RED
                # $result[$line] = '<span style="background-color:#BF0000;">'. $result[$line] .'</span>';

                /**
                 * transform the array into a string again
                 * (1) we have to re-add <code> , bevause it got lost somewhere... hmm? @todo figure out why!
                 * (2) implode array with linebreaks
                 */
                $errorcontext_lines  = '<code>'.implode($result, '<br />');

                #clansuite_xdebug::printr($errorcontext_lines);

                # attach some hardcoded style
                $style_string = '<style type="text/css">
                        .num {
                        float: left;
                        color: gray;
                        font-size: 13px;
                        font-family: monospace;
                        text-align: right;
                        margin-right: 6pt;
                        padding-right: 6pt;
                        border-right: 1px solid gray;}

                        body {margin: 0px; margin-left: 5px;}
                        td {vertical-align: top;}
                        code {white-space: nowrap;}
                    </style>';

                # display LINES and ERRORCONTEXT_LINES in a table (prefixed with the hardcoded style)
                return "$style_string <table><tr><td class=\"num\">\n$lines\n</td><td>\n$errorcontext_lines\n</td></tr></table>";
            }
        }
    }

    /**
     * shutdown_and_exit() is the callback function for register_shutdown_function()
     */
    public function shutdown_and_exit()
    {
        echo '<p><b>Clansuite Error. Execution stopped.</b></p>';
    }
}
?>