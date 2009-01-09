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

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * This Clansuite Core Class for Errorhandling
 *
 * @author     Jens-Andr� Koch <vain@clansuite.com>
 * @copyright  Jens-Andr� Koch (2005 - onwards)
 *
 * @package     clansuite
 * @category    core
 * @subpackage  errorhandler
 */
class Clansuite_Errorhandler
{
    private $config; # holds configuration instance

    private static $errorstack = array(); # holds errorstack elements

    /**
     * Errorhandler Constructor
     *
     * Sets up the ErrorHandler and ExceptionHandler
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
     * in case of serveral PHP Error States
     *
     * @param integer $errornumber contains the error as integer
     * @param string $errorstring contains error string info
     * @param string $errorfile contains the filename with occuring error
     * @param string $errorline contains the line of error
     * @link http://www.usegroup.de/software/phptutorial/debugging.html
     * @link http://www.php.net/manual/de/function.set-error-handler.php
     * @link http://www.php.net/manual/de/errorfunc.constants.php
     */
    public function clansuite_error_handler( $errornumber, $errorstring, $errorfile, $errorline, $errorcontext )
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

        /**
         * define errorTypes array - with names for all the php error codes
         * @link http://php.oss.eznetsols.org/manual/de/errorfunc.constants.php
         * @todo: return correct errorcodes for the actual php version
         */
        $errorTypes = array (    1      => 'E_ERROR',               # fatal run-time errors, like php is failing memory allocation
                                 2      => 'E_WARNING',             # Run-time warnings (non-fatal errors)
                                 4      => 'E_PARSE',               # compile-time parse errors - generated by the parser
                                 8      => 'E_NOTICE',              #
                                 16     => 'E_CORE_ERROR',          # PHP Core reports errors in PHP's initial startup
                                 32     => 'E_CORE_WARNING',        # PHP Core reports warning (non-fatal errors)
                                 64     => 'E_COMPILE_ERROR',       # Zend Script Engine reports fatal compile-time errors
                                 128    => 'E_COMPILE_WARNING',     # Zend Script Engine reports compile-time warnings (non-fatal errors)
                                 256    => 'E_USER_ERROR',          # trigger_error() / user_error() reports user-defined error
                                 512    => 'E_USER_WARNING',        # trigger_error() / user_error() reports user-defined warning
                                 1024   => 'E_USER_NOTICE',         # trigger_error() / user_error() reports user-defined notice
                                #2047   => 'E_ALL 2047 PHP <5.2.x', # all errors and warnings + old value of E_ALL of PHP Version below 5.2.x
                                 2048   => 'E_STRICT',              # Run-time notices (since PHP 5)
                                 4096   => 'E_RECOVERABLE_ERROR',   # catchable fatal error, if not catched it's an e_error (since PHP 5.2.0)
                                 6143   => 'E_ALL 6143 PHP5.2.x',   # all errors and warnings + old value of E_ALL of PHP Version 5.2.x
                                #8191   => 'E_ALL 8191'             # PHP 6 -> 8191
                                #8192   => 'E_DEPRECATED',          # notice marker for 'in future' deprecated php-functions (since PHP 5.3.0)
                                #16384  => 'E_USER_DEPRECATED',     # trigger_error() / user_error() reports user-defined deprecated functions
                                #30719  => 'E_ALL 30719 PHP5.3.x'   # all errors and warnings - E_ALL of PHP Version 5.3.x
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

        # if DEBUG is set, display the error, else log the error
        if ( defined('DEBUG') && DEBUG == 1 )
        {
            # smarty errors are trigger_errors - so they bubble up as e_user_errors
            # so we need to detect if an e_user_errors is coming from smarty
            if(strpos(strtolower($errorfile),'smarty') !== false)
            {
                # echo Smarty Template Error
                echo $this->smarty_error_display( $errornumber, $errorname, $errorstring, $errorfile, $errorline, $errorcontext );
            }
            else # give normal Error Display
            {
                # All Error Informations (except backtraces)
                echo $this->ysod( $errornumber, $errorname, $errorstring, $errorfile, $errorline, $errorcontext );
            }
        }

        # Skip PHP internal error handler
        return true;
    }

    /**
     * Smarty Error Display
     * prints a shorter Version of ErrorReport
     */
    private function smarty_error_display( $errornumber, $errorname, $errorstring, $errorfile, $errorline, $errorcontext )
    {
        $errormessage  = "<h3><font color=red>&raquo; Smarty Template Error &laquo;</font></h3>";
        $errormessage .=  '<pre/>';
        $errormessage .=  "<u>$errorname:</u><br/>";
        $errormessage .=  '<b>'. wordwrap($errorstring,50,"\n") .'</b><br/>';
        $errormessage .=  "File: $errorfile <br/>Line: $errorline ";
        $errormessage .=  '</pre><br/>';
        return $errormessage;
    }

    /**
     * Yellow Screen of Death (YSOD) is used to display errors
     *
     * @param string $ErrorObject contains ErrorObject
     * @param string $errorstring contains the Name of the Error
     * @param string $string contains errorstring
     * @param integer $errornumber contains errorlvl
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
        $errormessage   .= '<tr><td width=15%><strong>Errorcode :</strong></td><td>'.$errorname.' ('.$errornumber.')</td></tr>';
        $errormessage   .= '<tr><td><strong>Message :</strong></td><td>'.$errorstring.'</td></tr>';
        $errormessage   .= '<tr><td><strong>Pfad :</strong></td><td>'. dirname($errorfile).'</td></tr>';
        $errormessage   .= '<tr><td><strong>Datei :</strong></td><td>'. basename($errorfile).'</td></tr>';
        $errormessage   .= '<tr><td><strong>Zeile :</strong></td><td>'.$errorline.'</td></tr>';

        # HR Split
        $errormessage   .= '<tr><td colspan="2">&nbsp;</td></tr>';

        # Environmental Informations at Errortime ( $errorcontext is not displayed )
        $errormessage  .= '<tr><td colspan="2"><h3>Server Environment</h3></td></tr>';
        $errormessage   .= '<tr><td><strong>Date :</strong></td><td>'.date('r').'</td></tr>';
        $errormessage   .= '<tr><td><strong>Request :</strong></td><td>'.$_SERVER['QUERY_STRING'].'</td></tr>';
        $errormessage   .= '<tr><td><strong>Server :</strong></td><td>'.$_SERVER['SERVER_SOFTWARE'].'</td></tr>';
        $errormessage   .= '<tr><td><strong>Remote :</strong></td><td>'.$_SERVER['REMOTE_ADDR'].'</td></tr>';
        $errormessage   .= '<tr><td><strong>Agent :</strong></td><td>'.$_SERVER['HTTP_USER_AGENT'].'</td></tr>';
        $errormessage  .= '<tr><td><strong>Clansuite :</strong></td><td>'.CLANSUITE_VERSION.' '.CLANSUITE_VERSION_STATE.' ('.CLANSUITE_VERSION_NAME.') [Revision #'.CLANSUITE_REVISION.']</td></tr>';

        # Tracing
        /*if ( defined('DEBUG') && DEBUG == 1 )
        {
        $errormessage   .= '<tr><td>' . $this->getDebugBacktrace() . '</td></tr>';
        }*/

        # HR Split
        $errormessage   .= '<tr><td colspan="2">&nbsp;</td></tr>';

        # close all html elements: table, fieldset, body+page
        $errormessage   .= '</table>';
        $errormessage   .= '</fieldset><br /><br />';
        $errormessage   .= '</body></html>';

        # Output the errormessage
        return $errormessage;
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