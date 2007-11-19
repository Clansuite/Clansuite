<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch © 2005-2007
    * http://www.clansuite.com/
    *
    * File:         errorhandling.class.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Core Class for Error Handling
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
    * @author     Jens-Andre Koch   <vain@clansuite.com>
    * @copyright  Jens-Andre Koch (2005-$LastChangedDate$)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * This Clansuite Core Class for Error Handling
 *
 * Version 0.1
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
 *
 * Version 0.2
 * File was rewritten for Release 0.2.
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$LastChangedDate$)
 *
 * @package     clansuite
 * @category    core
 * @subpackage  error
 */
class errorhandler
{
    # holds configuration instance
    private $config;

    /**
     * errorhandler Constructor
     *
     * - Sets up the ErrorHandler and ExceptionHandler
     * # loads the languagefile error.xml
     * # registers the {error} block which calls smarty_error function
     */
    function __construct(configuration $config)
    {
        # set instance of configuration to this class
        $this->config   = $config;

        # set error handling language to default language
        #$this->lang->load_lang('error', $this->config->language);

        #if(!DEBUG)
        #{
        if( function_exists('set_error_handler') )
        {
            set_error_handler(array($this, 'clansuite_error_handler'), error_reporting());
        }
        #trigger_error('bla');

        # register own exception handler
        if( function_exists('set_exception_handler') )
        {
            set_exception_handler(array($this, 'clansuite_exception_handler' ));
        }

        # register own shutdown function
        register_shutdown_function('shutdown_and_exit');
		#}

        # turn smarty error into php+html with defines
        #$tpl  = $this->injector->instantiate('view_smarty');
        #$this->tpl->smarty->register_block("error", array('error',"smarty_error"), false);
    }

    /**
     * This registeres the Smarty Block {error level="1" title="Error"}
     *
     * @param array $params mixed array
     * @param string $string contains string data
     * @param array $smarty smarty array
     * @access static
     */
    public function smarty_error($params, $string, &$smarty)
    {
        global $error, $lang;

        /**
         * Init Vars
         * - level
         * - title
         */
        $params['level']   = !isset( $params['level'] ) ? 3 : $params['level'];
        $params['title']   = !isset( $params['level'] ) ? 'Unkown Error' : $params['title'];

        if ( !empty($string) )
        {
            $this->ysod( $params['title'], $string, $params['level'] );
        }
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
     * @global $tpl
     * @global $config
     * @link http://www.usegroup.de/software/phptutorial/debugging.html
     * @link http://www.php.net/manual/de/function.set-error-handler.php
     */
    public function clansuite_error_handler( $errornumber, $errorstring, $errorfile, $errorline )
    {
        # just return, if ErrorReporting is suppressed or silenced (in case of @ operator)
        if(($this->config->suppress_errors == 0) OR (error_reporting() == 0))
        {
            return;
        }

        # set the error time
        $errortime=date($CONF[date_format].' '.$CONF[time_format]);

        # define errorTypes array - with names for all the php error codes
        $errorTypes = array (    1      => 'E_ERROR',
                                 2      => 'E_WARNING',
                                 4      => 'E_PARSE',
                                 8      => 'E_NOTICE',
                                 16     => 'E_CORE_ERROR',
                                 32     => 'E_CORE_WARNING',
                                 64     => 'E_COMPILE_ERROR',
                                 128    => 'E_COMPILE_WARNING',
                                 256    => 'E_USER_ERROR',
                                 512    => 'E_USER_WARNING',
                                 1024   => 'E_USER_NOTICE',
                                 2047   => 'E_ALL',
                                 2048   => 'E_STRICT',
                                 4096   => 'E_RECOVERABLE_ERROR',
                                 6143   => 'E_ALL'); # PHP 6 -> 8191


        # check if the error number exists in the errortypes array
        if (array_key_exists($errornumber, $errorTypes))
        {
            # get the errorname from the array via $errornumber
            $errorname = $errorTypes[$errornumber];
        }
        else
        {
            # when it's not in there, it has to be an exception
            $errorname = 'CAUGHT EXCEPTION';
        }

        # set error details to class
		echo 'Fehlernummer: '. $errornumber;

        # Handling the ErrorType via Switch
        switch ($errornumber)
        {
            # the first few errortypes simply for clearification
            # not could not be handled by a user-defined errorhandler
            case E_ERROR:               # fatal run-time errors, like php is failing memory allocation
            case E_PARSE:               # compile-time parse errors - generated by the parser
            case E_CORE_ERROR:          # PHP Core reports errors in PHP's initial startup
            case E_CORE_WARNING:        # PHP Core reports warning (non-fatal errors)
            case E_COMPILE_ERROR:       # Zend Script Engine reports fatal compile-time errors
            case E_COMPILE_WARNING:     # Zend Script Engine reports compile-time warnings (non-fatal errors)
                            
                                        $error = 'Errors not handled by user-defined errorhandler';
			                            break;

            case E_STRICT:              # Run-time notices
                                        echo 'Hello to the Strict Error';
            case E_USER_ERROR:          # trigger_error() reports error
            case E_USER_WARNING:        # trigger_error() reports warning
            case E_USER_NOTICE:         # trigger_error() reports notice            
            case E_RECOVERABLE_ERROR:   # catchable fatal error, if not catched it's an e_error            
            case E_WARNING:             # Run-time warnings (non-fatal errors)            
            case E_NOTICE:              # Run-time notices
            #case E_ALL:                # ALL 
            default:
                # if DEBUG is set, display the error, else log the error
                if ( defined('DEBUG') && DEBUG===1 )
                {
                    echo "<b>$errorname:</b> $errornumber: $errorstring | File: $errorfile | Line: $errorline";
                }
                else
                {

                    # error logging: log the error with the logging_type according to config file
                    switch ($config['logging_type'])
                    {
                        default:
                        case 'php':
                                            $this->error_log['unknown'][] = "<b>$errorname:</b> $errornumber: $errorstring | File: $errorfile | Line: $errorline";
                                            break;
                        case 'file':
                                            $logfile = WWW_ROOT . $config['logging_folder'] . $config['log_filename'];
                                            $file = fopen($logfile,"a");
                                            fwrite ($file,"\r\n--[ ".$errortime." ] => ".$errorstring .' - '. $errornumber."\r\n");
                                            fwrite ($file,"$errorname: $errornumber: $errorstring | File: $errorfile | Line: $errorline \r\n");
                                            fwrite ($file,"~~~~\r\n");
                                            fclose($file);

                                            break;
                        case 'database':
                                            break;
                    }

                    # mail error
                    # @todo mail via swiftmailer 
                    if ($config['mail_on_errors'])
                    {
                        mail($config['mail_on_errors'],"Clansuite Error report","\r\n--[ ".$errortime." ] => ".$errorstring .' - '. $errornumber."\r\n $errorname: $errornumber: $errorstring | File: $errorfile | Line: $errorline \r\n ~~~~\r\n");
                    }
                }
                break;
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
        # @todo: Logger
        /*
        if()
        {

        }
        */

        # @todo: Email Errors
        /*
        if($email_on_error == 1)
        {

        }
        */
        
        # timestamp of the error
        $timestamp = date("d-m-Y H:i:s");

        if ($this->config->suppress_errors == 0 )
        {
            $this->ysod($exception, 'Uncaught exception : ' . $e->getCode(), $e->getFile() . ' | Line: ' . $e->getLine() . '<br />' . $e->getMessage() .'<br /><b>Last DB-Query:&nbsp;</b>' . $this->db->last_sql, 1 );
        }
    }

    /**
     * Yellow Screen of Death (YSOD) is used to display errors
     *
     * @param string $error_head contains the Name of the Error
     * @param string $string contains errorstring
     * @param integer $level contains errorlvl
     * @param string $redirect contains redirect url
     * @global $tpl
     */
    public function ysod( $ErrorObject, $error_head = 'Unknown Error', $string = '', $error_level = 3, $redirect = '' )
    {
        # Header
        echo '<html><head>';
        echo '<title>Clansuite Error Page -'. $error_head .' Errortype '. $error_level .'</title>';
        echo '<body>';
        echo '<link rel="stylesheet" href="'. WWW_ROOT .'/templates/core/css/error.css" type="text/css" />';
        echo '</head>';
        # Body
        echo '<body>';
        # Fieldset with colours
        if ($error_level == 1)     { echo '<fieldset class="error_red">'; }
        elseif ($error_level == 2) { echo '<fieldset class="error_orange">'; }
        elseif ($error_level == 3) { echo '<fieldset class="error_beige">'; }
        # Fieldset Legend
        echo "<legend>$error_head</legend>";
        # Error String (passed Error Description)
        echo '<strong>'.$string.'</strong>';
        # Error Messages from the ErrorObject
        echo "<div style='border-style: solid; border-width:1px; border-color: red; margin-top:10px;'>\n";
        echo '<p><table>';
        echo '<tr><td><strong>ErrorCode:</strong></td><td>'.$ErrorObject -> getCode().'</td></tr>';
        echo '<tr><td><strong>Message:</strong></td><td>'.$ErrorObject -> getMessage().'</td></tr>';
        echo '<tr><td><strong>Pfad :</strong></td><td>'. dirname($ErrorObject -> getFile()).'</td></tr>';
        echo '<tr><td><strong>Datei :</strong></td><td>'. basename($ErrorObject -> getFile()).'</td></tr>';
        echo '<tr><td><strong>Zeile :</strong></td><td>'.$ErrorObject -> getLine().'</td></tr>';
        echo '</table>';
        echo "</div>\n";
    	# If Debug is enabled, display extended Error Information (tracing etc.)
    	if(DEBUG===1)
    	{
    	    /*
            $trace = array();
            if (function_exists('debug_backtrace')) {
                $trace = array_shift(debug_backtrace());
            }
            echo '<br> '.var_dump($trace);
            */
    	    echo '<br>';
            #echo $ErrorObject->getTraceAsString();
        }
        echo '</fieldset>';
        echo '</body></html>';
    }

    function shutdown_and_exit()
    {
        echo '<p><b>Clansuite execution stopped.</b></p>';
    }
}
?>