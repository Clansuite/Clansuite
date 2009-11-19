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
 * Clansuite_Exception
 *
 * Developer Notice:
 * The "Fatal error: Exception thrown without a stack frame in Unknown on line 0"
 * is of PHP dying when an exception is thrown when running INSIDE an error or exception handler.
 * Avoid stacking Exceptions, e.g. try/catch Exception($e) and then throwing a Clansuite_Exception().
 *
 * @see http://php.net/manual/de/class.exception.php
 *
 * @author     Jens-Andr� Koch <vain@clansuite.com>
 * @copyright  Jens-Andr� Koch (2005 - onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Errorhandler
 */
class Clansuite_Exception extends Exception implements Clansuite_Exception_Interface
{
    /**
     * Variables of a PHP Exception
     * They are used to store the content of incomming uncatched Exceptions.
     */

    # exception message
    protected $message = 'Unknown exception';

    # debug backtrace string
    private   $string;

    # user-defined exception code
    protected $code    = 0;

    # source filename of exception
    protected $file;

    # source line of exception
    protected $line;

    # trace
    private   $trace;

    /**
     * Variables for the content of exception templates
     */

    private static $exception_template_content = '';
    private static $exception_development_template_content = '';

    # redeclare exception, so that it is not optional
    public function __construct($message = null, $code = 0)
    {
        # assign to parent
        parent::__construct($message, $code);

        # fetch exceptionTemplates, but not for $code = 0
        if( $code > 0 )
        {
            self::fetchExceptionTemplates($code);

        }

        # debug display of exception object
        #clansuite_xdebug::printR($this);
    }

    /**
     * Fetches the normal and rapid development templates for exceptions and sets them to class.
     * Callable via self::getExceptionTemplate() and self::getExceptionDevelopmentTemplate($placeholders).
     *
     * @param $code exception
     */
    private static function fetchExceptionTemplates($code)
    {
        self::fetchExceptionTemplate($code);

        if( defined('DEBUG') and DEBUG == 1 and defined('DEVELOPMENT') and DEVELOPMENT == 1)
        {
            self::fetchExceptionDevelopmentTemplate($code);
        }
    }

    /**
     * Fetches an ErrorTemplate from File and sets it to the object
     * Filename has to be "exception-ID.html", where ID is the exception id.
     * Example with ID 20: throw new Clansuite_Exception('My Exception Message: ', 20);
     *
     * @param $code exception
     */
    private static function fetchExceptionTemplate($code)
    {
        # construct filename with code
        $exception_template_file = ROOT . 'themes/core/exceptions/exception-'.$code.'.html';

        # ensure file is there, load it and set it to classvariable
        if(is_file($exception_template_file))
        {
            $content = file_get_contents($exception_template_file);
            self::setExceptionTemplate($content);
        }
    }

    /**
     * Setter Method for the Content of the ErrorTemplate
     *
     * @param $content
     */
    private static function setExceptionTemplate($content)
    {
        self::$exception_template_content = $content;
    }

    /**
     * Getter Method for the exception_template_content
     *
     * @return Content of $exception_template_content
     */
    private static function getExceptionTemplate()
    {
        return self::$exception_template_content;
    }

    /**
     * Fetches an ErrorTemplate for rapid development purposes from file and sets it to the object
     * Place filename with exception id into the folder: /themes/core/exceptions/
     * Filename has to be "exception-dev-ID.html", where ID is the exception id.
     * Example with ID 20: throw new Clansuite_Exception('My Exception Message: ', 20);
     *
     * @param $code exception
     */
    private static function fetchExceptionDevelopmentTemplate($code)
    {
        # construct filename with code
        $exception_template_file = ROOT . 'themes/core/exceptions/exception-dev-'.$code.'.html';

        if(is_file($exception_template_file))
        {
            $content = file_get_contents($exception_template_file);
            self::setExceptionDevelopmentTemplate($content);
        }
    }

    /**
     * Setter Method for the Content of the ExceptionDevelopmentTemplate
     *
     * @param $content
     */
    private static function setExceptionDevelopmentTemplate($content)
    {
        self::$exception_development_template_content = $content;
    }

    /**
     * Getter Method for the exception_development_template_content
     *
     * @return Content of $exception_development_template_content
     */
    private static function getExceptionDevelopmentTemplate($placeholders)
    {
        $original_file_content = self::$exception_development_template_content;

        if(isset($placeholders['modulename']))
        {
            $replaced_content = str_replace('{$modulename}', $placeholders['modulename'], $original_file_content);
        }

        if(isset($placeholders['classname']))
        {
            $replaced_content = str_replace('{$classname}', $placeholders['classname'], $replaced_content);
        }

        if(isset($placeholders['actionname']))
        {
            $replaced_content = str_replace('{$actionname}', $placeholders['actionname'], $replaced_content);
        }

        return $replaced_content;
    }

    /**
     * Exception Handler Callback
     * Rethrows uncatched Exceptions in our presentation style.
     *
     * @param $exception A valid Exception Object is valid (Type Hint).
     */
    public function clansuite_exception_handler( Exception $exception )
    {
       # display exceptions if errors are not suppressed
       #if ($this->config['suppress_errors'] == 0 )
       #{
            # Simple structured output
            #echo '<pre>'.$exception.'</pre';

            # Assigning variables from an uncatched exception to this exception object
            $this->message = $exception->getMessage();
            $this->string  = $exception->getTraceAsString();
            $this->code    = $exception->getCode();
            $this->file    = $exception->getFile();
            $this->line    = $exception->getLine();
            $this->trace   = $exception->getTrace();

            # if no errorcode is set, say that it's an rethrow
            if($this->code == '0')
            {
                $this->code = '0 (This exception is uncatched and rethrown.)';
            }

            # stringifies this object via __toString by calling yellowScreenOfDeath()
            echo $this;
       #}
    }

    /**
     * Method for Conditional Usage
     *
     * Usage: someFunction() OR throwException();
     */
    public function throwException($message = null, $code = null)
    {
        throw new Clansuite_Exception($message, $code);
    }

    /**
     * Exception Presentation:
     * Yellow Screen of Death (YSOD)
     */
    public function yellowScreenOfDeath()
    {
        # Header
        $errormessage    = '<html><head>';
        $errormessage   .= '<title>Clansuite Exception : [ '.get_class($this).'][ '. $this->getMessage() .' | Exceptioncode: '. self::getCode() .' ] </title>';
        $errormessage   .= '<body>';
        $errormessage   .= '<link rel="stylesheet" href="'. WWW_ROOT_THEMES_CORE .'/css/error.css" type="text/css" />';
        $errormessage   .= '</head>';

        # Body
        $errormessage   .= '<body>';

        # Fieldset with colours (error_red, error_orange, error_beige)
        $errormessage   .= '<fieldset class="error_beige">';

        # Errorlogo
        $errormessage   .= '<div style="float: left; margin: 5px; margin-right: 25px; border:1px inset #bf0000; padding: 20px;">';
        $errormessage   .= '<img src="'. WWW_ROOT_THEMES_CORE .'/images/Clansuite-Toolbar-Icon-64-exception.png" style="border: 2px groove #000000;"/></div>';

        # Fieldset Legend
        $errormessage   .= '<legend>Clansuite Exception : [ '. self::getMessage() .' ]</legend>';

        # Error Messages from Object (table)
        # HEADING <Exception Object>
        $errormessage   .= '<table>';
        $errormessage   .= '<tr><td colspan="2"><h3>Exception</h3></td></tr>';
        $errormessage   .= '<tr><td width=15%><strong>Code: </strong></td><td>'.$this->code.'</td></tr>';
        $errormessage   .= '<tr><td><strong>Message: </strong></td><td>'.$this->message.'</td></tr>';
        $errormessage   .= '<tr><td><strong>Pfad: </strong></td><td>'.dirname($this->file).'</td></tr>';
        $errormessage   .= '<tr><td><strong>Datei: </strong></td><td>'.basename($this->file).'</td></tr>';
        $errormessage   .= '<tr><td><strong>Zeile: </strong></td><td>'.$this->line.'</td></tr>';

        # Split
        $errormessage   .= '<tr><td colspan="2">&nbsp;</td></tr>';

        if ( defined('DEBUG') and DEBUG == 1 )
        {
            $errormessage   .= '<tr><td><strong>Debugtrace (Callstack): </strong></td><td colspan=2 width=80%>'. self::formatGetTraceString($this->string) . '</td></tr>';

            # Split
            $errormessage   .= '<tr><td colspan="2">&nbsp;</td></tr>';
        }

        # Environmental Informations at Errortime
        if ( defined('DEBUG') and DEBUG == 1 )
        {
            # HEADING <Server Environment>
            $errormessage  .= '<tr><td colspan="2"><h3>Server Environment</h3></td></tr>';
            $errormessage   .= '<tr><td><strong>Date: </strong></td><td>'.date('r').'</td></tr>';
            $errormessage   .= '<tr><td><strong>Request: </strong></td><td>index.php?'.htmlentities($_SERVER['QUERY_STRING']).'</td></tr>';
            $errormessage   .= '<tr><td><strong>Remote: </strong></td><td>'.$_SERVER['REMOTE_ADDR'].'</td></tr>';
            $errormessage   .= '<tr><td><strong>Server: </strong></td><td>'.$_SERVER['SERVER_SOFTWARE'].'</td></tr>';
            $errormessage   .= '<tr><td><strong>Agent: </strong></td><td>'.$_SERVER['HTTP_USER_AGENT'].'</td></tr>';
            $errormessage   .= '<tr><td><strong>Clansuite: </strong></td><td>'.CLANSUITE_VERSION.' '.CLANSUITE_VERSION_STATE.' ('.CLANSUITE_VERSION_NAME.') [Revision #'.CLANSUITE_REVISION.']</td></tr>';

            # Split
            $errormessage   .= '<tr><td colspan="2">&nbsp;</td></tr>';
        }

        # HEADING <Additional Information>
        if(self::getExceptionTemplate() != '')
        {
            $errormessage  .= '<tr><td colspan="2"><h3>Additional Information & Solution Suggestion</h3></td></tr>';
            $errormessage  .= '<tr><td colspan="2">'.self::getExceptionTemplate().'</td></tr>';

            # Split
            $errormessage  .= '<tr><td colspan="2">&nbsp;</td></tr>';
        }

        # HEADING <Rapid Development>

        # assign placeholders for replacements in the html
        if(strpos(self::getMessage(),"action_"))
        {
            $placeholders['actionname']  = substr($this->message,strpos($this->message,"action_"));
        }
        elseif(strpos(self::getMessage(),"module_"))
        {
            $placeholders['classname']  = substr($this->message,strpos($this->message,"module_"));
        }

        if(empty($_GET['mod']) == false)
        {
            $placeholders['modulename'] = (string) stripslashes($_GET['mod']);
        }
        else
        {
            $placeholders['modulename'] = '';
        }

        if(self::getExceptionDevelopmentTemplate($placeholders) != '')
        {
            $errormessage  .= '<tr><td colspan="2"><h3>Rapid Development</h3></td></tr>';
            $errormessage  .= '<tr><td colspan="2">'.self::getExceptionDevelopmentTemplate($placeholders).'</td></tr>';

            # Split
            $errormessage  .= '<tr><td colspan="2">&nbsp;</td></tr>';
        }

        # Split
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
                              <a href="http://www.clansuite.com/livezilla/livezilla.php?code=RXhjZXB0aW9uL0Vycm9y&amp;reset=true" target="_blank">Live Help (Start Chat)</a>
                           </noscript>
                           <!-- Live Support JavaScript --></strong> | ';
        $errormessage  .= '<strong><a href="http://trac.clansuite.com/">Bug-Report</a></strong> |
                           <strong><a href="http://forum.clansuite.com/">Support-Forum</a></strong> |
                           <strong><a href="http://docs.clansuite.com/">Manuals</a></strong> |
                           <strong><a href="http://www.clansuite.com/">visit clansuite.com</a></strong>
                           </div>';

        # close all html elements: fieldset, body+page
        $errormessage   .= '</fieldset>';
        $errormessage   .= '</body></html>';

        # Output the errormessage
        return $errormessage;
    }

    /**
     * formats the debugtrace by applying linebreaks
     */
    public static function formatGetTraceString($string)
    {
        $string = str_replace('#','<br/><br/>Call #', $string);
        #$string = str_replace('):','):<br/><br/>', $string);
        return $string;
    }

    /**
     * Overwriteable Method of Class Exception
     * This is the String representation of the exception.
     * It is a pass-through to our presentation format (ysod);
     *
     * @see yellowScreenOfDeath()
     */
    public function __toString()
    {
        return $this->yellowScreenOfDeath();
    }
}

/**
 * Clansuite_Exception has to implement the following methods.
 */
interface Clansuite_Exception_Interface
{
    /* Protected methods inherited from Exception class */
    public function getMessage();                 // Exception message
    public function getCode();                    // User-defined Exception code
    public function getFile();                    // Source filename
    public function getLine();                    // Source line
    public function getTrace();                   // An array of the backtrace()
    public function getTraceAsString();           // Formated string of trace

    /* Overrideable methods inherited from Exception class */
    public function __toString();                 // formated string for display
    public function __construct($message = null, $code = 0);
}
?>