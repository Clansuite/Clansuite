<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005 - onwards
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  Jens-Andre Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * This Clansuite Core Class for Errorhandling
 *
 * @author     Jens-Andre Koch <vain@clansuite.com>
 * @copyright  Jens-Andre Koch (2005 - onwards)
 *
 * @package     clansuite
 * @category    core
 * @subpackage  errorhandler
 */
class errorhandler
{
    private $config; # holds configuration instance

    private static $errorstack = array(); # holds errorstack elements

    /**
     * Errorhandler Constructor
     *
     * Sets up the ErrorHandler and ExceptionHandler
     */
    function __construct(Clansuite_Config $config)
    {
        $this->config   = $config; # set instance of configuration

        # register own exception handler
        set_exception_handler(array(&$this, 'clansuite_exception_handler' ));

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
        Clansuite_Errorstack::$errorstack[] = array('message'   => $errormessage,
                                                    'code' => $errorcode);
    }

    /**
     * toString Magic Method to display the Errorstack
     */
    public static function toString()
    {
        $output = '';

        foreach(Clansuite_Errorstack::$errorstack as $error)
        {
		    $output .= 'Error: '. $error['message'] .' Code: ' .$error['code'] . "\n\n";
		}
		return $output;
    }

    /**
     * Get Method for fetching the whole Errorstack
     *
     * @return errorhandler:$errorstack
     */
    public static function getErrorStack()
    {
        return Clansuite_ErrorStack::$errorstack;
    }

    /**
     * Clansuite Error callback.
     *
     * This is basically a switch defining the actions taken,
     * in case of serveral PHP Error States
     *
     * @param integer $errornumber contains the error as integer
     * @param string $errorstring contains error string info
     * @param string $errorfile contains the filename with occuring error
     * @param string $errorline contains the line of error
     * @link http://www.usegroup.de/software/phptutorial/debugging.html
     * @link http://www.php.net/manual/de/function.set-error-handler.php
     */
    public function clansuite_error_handler( $errornumber, $errorstring, $errorfile, $errorline )
    {
        # do just return, if ErrorReporting is suppressed or silenced (in case of @ operator)
        if(($this->config['error']['suppress_errors'] == 1) OR (error_reporting() == 0))
        {
            return;
        }

        /**
               * Assemble the error informations
               */
        # set the error time
        $errortime = date($this->config['date_format'].' '.$this->config['time_format']);

        # define errorTypes array - with names for all the php error codes
        $errorTypes = array (    1      => 'E_ERROR',               # fatal run-time errors, like php is failing memory allocation
                                 2      => 'E_WARNING',             # Run-time warnings (non-fatal errors)
                                 4      => 'E_PARSE',               # compile-time parse errors - generated by the parser
                                 8      => 'E_NOTICE',              #
                                 16     => 'E_CORE_ERROR',          # PHP Core reports errors in PHP's initial startup
                                 32     => 'E_CORE_WARNING',        # PHP Core reports warning (non-fatal errors)
                                 64     => 'E_COMPILE_ERROR',       # Zend Script Engine reports fatal compile-time errors
                                 128    => 'E_COMPILE_WARNING',     # Zend Script Engine reports compile-time warnings (non-fatal errors)
                                 256    => 'E_USER_ERROR',          # trigger_error() / user_error() reports error
                                 512    => 'E_USER_WARNING',        # trigger_error() / user_error() reports warning
                                 1024   => 'E_USER_NOTICE',         # trigger_error() / user_error()  reports notice
                                #2047   => 'E_ALL',                 # old value of E_ALL
                                 2048   => 'E_STRICT',              # Run-time notices
                                 4096   => 'E_RECOVERABLE_ERROR',   # catchable fatal error, if not catched it's an e_error
                                 6143   => 'E_ALL 6143',            # all errors and warnings
                                #8191   => 'E_ALL 8191'             # PHP 6 -> 8191
                                 );


        # check if the error number exists in the errortypes array
        if (array_key_exists($errornumber, $errorTypes))
        {
            # get the errorname from the array via $errornumber
            $errorname = $errorTypes[$errornumber];
        }
        else
        {
            # when it's not in there, its an unknown errorcode
            $errorname = 'Unknown Errorcode';
        }

        # Handling the ErrorType via Switch
        switch ($errornumber)
        {
            # What are the errortypes that can be handled by a user-defined errorhandler?
            case E_WARNING:                 $errorname .= ' [php warning]'; break;
            case E_NOTICE:                  $errorname .= ' [php notice]'; break;
            case E_USER_ERROR:              $errorname .= ' [Clansuite Internal Error]'; break;
            case E_USER_WARNING:            $errorname .= ' [Clansuite Internal Error]'; break;
            case E_USER_NOTICE:             $errorname .= ' [Clansuite Internal Error]'; break;
            #case E_ALL:
            case E_STRICT:                  $errorname .= ' [php strict]'; break;
            #case E_RECOVERABLE_ERROR:
            default:                        $errorname .= 'Unknown ErrorCode ['. $errornumber .']: ';
        }

        # if DEBUG is set, display the error, else log the error
        if ( defined('DEBUG') && DEBUG == 1 )
        {
            # smarty errors are trigger_errors - so they bubble up as e_user_errors
            # so we need to detect if an e_user_errors is coming from smarty
            if(strpos(strtolower($errorfile),'smarty') !== false)
            {
                # Print shorter Version of ErrorReport
                echo "<h3><font color=red>&raquo; Smarty Template Error &laquo;</font></h3>";
                echo '<pre/>';
            }
            else
            {
                # All Error Informations (except backtraces)
                echo '<pre/>';

            }

            echo "<u>$errorname:</u><br/>";
            echo '<b>'. wordwrap($errorstring,50,"\n") .'</b><br/>';
            echo "File: $errorfile <br/>Line: $errorline ";
           /*
            $trace = array();
            if (function_exists('debug_backtrace')) {
                $trace = array_shift(debug_backtrace());
            }
            echo '<br> '.var_dump($trace); */

            echo '</pre><br/>';
        }

        # Skip PHP internal error handler
        return true;
    }

    /**
     * Exception Handler Callback
     *
     * Uncaught Exception will bubble up and trigger an ERROR?
     * This is used to trigger the output via errorhandler::show,
     * when suppress_errors is not enabled.
     *
     * @param $exception
     *
     * @see error::show()
     * @todo
     */
    public function clansuite_exception_handler( Exception $exception )
    {
       if ($this->config['suppress_errors'] == 0 )
       {
            $error_head = 'Clansuite Exception';
            $this->ysod($exception, $error_head, 'Exception:', 1000);
       }
    }

    /**
     * Yellow Screen of Death (YSOD) is used to display errors
     *
     * @param string $ErrorObject contains ErrorObject
     * @param string $error_head contains the Name of the Error
     * @param string $string contains errorstring
     * @param integer $error_level contains errorlvl
     */
    public function ysod( $ErrorObject, $error_head = 'Clansuite Error', $string = '', $error_level = 3 )
    {
        # Header
        $errormessage    = '<html><head>';
        $errormessage   .= '<title>'. $error_head .' | Code: '. $error_level .'</title>';
        $errormessage   .= '<body>';
        $errormessage   .= '<link rel="stylesheet" href="'. WWW_ROOT_THEMES_CORE .'/css/error.css" type="text/css" />';
        $errormessage   .= '</head>';
        # Body
        $errormessage   .= '<body>';
        # Fieldset with colours (error_red, error_orange, error_beige)
        if ($error_level == 1)     { echo '<fieldset class="error_red">'; }
        elseif ($error_level == 2) { echo '<fieldset class="error_orange">'; }
        elseif ($error_level == 3) { echo '<fieldset class="error_beige">'; }
        # Errorlogo
        $errormessage   .= '<div style="float: left; margin: 5px; margin-right: 25px; border:1px inset #bf0000; padding: 20px;">';
        $errormessage   .= '<img src="'. WWW_ROOT_THEMES_CORE .'/images/Clansuite-Toolbar-Icon-64-error.png" style="border: 2px groove #000000;"/></div>';
        # Fieldset Legend
        $errormessage   .= '<legend>'. $error_head .'</legend>';
        # Error String (passed Error Description)
        #$errormessage   .= '<p><strong>'.$ErrorObject->message.'</strong>';
        # Error Messages from the ErrorObject
        #$errormessage   .= '<hr style="width=80%">';
        $errormessage   .= '<table>';
        $errormessage  .= '<tr><td><h3>'. $error_head .'</h3></td></tr>';
        $errormessage   .= '<tr><td><strong>Errorcode :</strong></td><td>'.$ErrorObject->getCode().'</td></tr>';
        $errormessage   .= '<tr><td><strong>Message :</strong></td><td>'.$ErrorObject->getMessage().'</td></tr>';
        $errormessage   .= '<tr><td><strong>Pfad :</strong></td><td>'. dirname($ErrorObject->getFile()).'</td></tr>';
        $errormessage   .= '<tr><td><strong>Datei :</strong></td><td>'. basename($ErrorObject->getFile()).'</td></tr>';
        $errormessage   .= '<tr><td><strong>Zeile :</strong></td><td>'.$ErrorObject->getLine().'</td></tr>';
        # HR Split
        $errormessage   .= '<tr><td colspan="2"><hr style="width=80%"></td></tr>';
        # Environmental Informations at Errortime
        $errormessage  .= '<tr><td><h3>Server Environment</h3></td></tr>';
        $errormessage   .= '<tr><td><strong>Date :</strong></td><td>'.date('r').'</td></tr>';
        $errormessage   .= '<tr><td><strong>Request :</strong></td><td>'.$_SERVER['QUERY_STRING'].'</td></tr>';
        $errormessage   .= '<tr><td><strong>Server :</strong></td><td>'.$_SERVER['SERVER_SOFTWARE'].'</td></tr>';
        $errormessage   .= '<tr><td><strong>Remote :</strong></td><td>'.$_SERVER['REMOTE_ADDR'].'</td></tr>';
        $errormessage   .= '<tr><td><strong>Agent :</strong></td><td>'.$_SERVER['HTTP_USER_AGENT'].'</td></tr>';
        # HR Split
        $errormessage   .= '<tr><td colspan="2"><hr style="width=80%"></td></tr>';
        # Tracing
        if ( defined('DEBUG') && DEBUG == 1 )
        {
            $errormessage   .= '<tr><td>' . $this->getDebugBacktrace() . '</td></tr>';
        }
        # close all html elements: table, fieldset, body+page
        $errormessage   .= '</table>';
        $errormessage   .= '</fieldset>';
        $errormessage   .= '</body></html>';
        # Output the errormessage
        echo $errormessage;

    }

    function getDebugBacktrace()
    {
        $backtrace_string = '';
        $dbg_backtrace = debug_backtrace();

        $backtrace_string  .= '<tr><td><h3>Backtrace</h3>(Recent function calls last)</td></tr>';

        for($i = 0; $i <= count($dbg_backtrace) - 1; $i++)
        {
            if(!isset($dbg_backtrace[$i]['file']))
            {
                $backtrace_string .= '<tr><td><strong>[PHP core called function]</strong></td>';
            }
            else
            {
                $backtrace_string .= '<tr><td><strong>Datei :</strong></td><td>' . $dbg_backtrace[$i]['file'] . '</td>';
            }

            if(isset($dbg_backtrace[$i]['line']))
            {
                $backtrace_string .= '</tr><tr><td><strong>Zeile</strong></td><td>' . $dbg_backtrace[$i]['line'] . '</td></tr>';
                $backtrace_string .= '<tr><td><strong>Function called :</strong></td><td>' . $dbg_backtrace[$i]['function'] . '</td></tr>';
            }

            if($dbg_backtrace[$i]['args'] && !is_object($dbg_backtrace[$i]['args']))
            {
                $backtrace_string .= '<tr><td>Arguments: ';
                for($j = 0; $j <= count($dbg_backtrace[$i]['args']) - 1; $j++)
                {
                    # if array, print_r
                    if(is_array($dbg_backtrace[$i]['args'][$j]))
                    {
                        #$backtrace_string .= print_r($dbg_backtrace[$i]['args'][$j]);
                    }
                    # if object, convert via toString
                    elseif (is_object($dbg_backtrace[$i]['args'][$j]) && method_exists($dbg_backtrace[$i]['args'][$j], 'tostring'))
                    {
                         # @todo: this is buggy!
        			     $backtrace_string .= new $dbg_backtrace[$i]['args'][$j]->toString();
        			}
        			# if object, without toString method return NULL
        			elseif (is_object($dbg_backtrace[$i]['args'][$j]))
        			{
        			     $backtrace_string .= 'Object';
        			}
        			# when string, simple add it
                    else
                    {
                        $backtrace_string .= $dbg_backtrace[$i]['args'][$j];
                    }

                    if($j != count($dbg_backtrace[$i]['args']) - 1)
                    {
                        # split
                        $backtrace_string .= ', ';
                    }
                }
            }
            # spacer
            $backtrace_string .= '</td></tr>';
        }

        # Returns the Backtrace String
        return $backtrace_string;
    }

    /**
     * register_shutdown_function
     * @todo: needed?
     */
    public function shutdown_and_exit()
    {
        echo '<p><b>Clansuite execution stopped.</b></p>';
    }
}
?>
