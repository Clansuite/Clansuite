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
 * Clansuite_Exception
 *
 * Sets up a custom Exceptionhandler.
 * @see Clansuite_CMS::initialize_Errorhandling()
 *
 * Developer Notice:
 * The "Fatal error: Exception thrown without a stack frame in Unknown on line 0"
 * is of PHP dying when an exception is thrown when running INSIDE an error or exception handler.
 * Avoid stacking Exceptions, e.g. try/catch Exception($e) and then throwing a Clansuite_Exception().
 *
 * @see http://php.net/manual/de/class.exception.php
 * @see http://php.net/manual/de/function.set-exception-handler.php
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Exceptionhandler
 */
class Clansuite_Exception extends Exception implements Clansuite_Exception_Interface
{
    /**
     * Variables of a PHP Exception
     * They are used to store the content of incomming uncatched Exceptions.
     */

    /**
     * @var string exception message
     */
    protected $message = 'Unknown exception';

    /**
     * @var string debug backtrace string
     */
    private $string;

    /**
     * @var int user-defined exception code
     */
    protected $code = 0;

    /**
     * @var string source filename of exception
     */
    protected $file;

    /**
     * @var int source line of exception
     */
    protected $line;

    /**
     * @var string trace
     */
    private $trace;

    /**
     * Variables for the content of exception templates
     */

    /**
     * @var string HTML Representation of the Exception Template
     */
    private static $exception_template_content = '';

    /**
     * @var string HTML Representation of the Exception Development (RAD) Template
     */
    private static $exception_development_template_content = '';

    /**
     * Exception Handler Callback
     * Rethrows uncatched Exceptions in our presentation style.
     *
     * @see http://php.net/manual/de/function.set-exception-handler.php
     * @param $exception PHP Exception Objects are valid (Type Hint).
     */
    public function __construct(Exception $exception)
    {
        # re/assign variables from an uncatched exception to this exception object
        $this->message = $exception->getMessage();
        $this->string = $exception->getTraceAsString();
        $this->code = $exception->getCode();
        $this->file = $exception->getFile();
        $this->line = $exception->getLine();
        $this->trace = $exception->getTrace();

        # if no errorcode is set, say that it's an rethrow
        if($this->code === '0')
        {
            $this->code = '0 (This exception is uncatched and rethrown.)';
        }

        # fetch exceptionTemplates, but not for $code = 0
        if( $this->code > 0 )
        {
            self::fetchExceptionTemplates($this->code);
        }

        echo $this->yellowScreenOfDeath();
    }

    /**
     * Fetches the normal and rapid development templates for exceptions and sets them to class.
     * Callable via self::getExceptionTemplate() and self::getExceptionDevelopmentTemplate($placeholders).
     *
     * @param int $code Exception Code
     */
    private static function fetchExceptionTemplates($code)
    {
        # normal exception template
        self::fetchExceptionTemplate($code);

        # development template
        if( defined('DEBUG') and DEBUG == 1 and defined('DEVELOPMENT') and DEVELOPMENT == 1)
        {
            self::fetchExceptionDevelopmentTemplate($code);
        }
    }

    /**
     * Fetches a Helper Template for this exception by it's exception code.
     *
     * You find the Exception Template in the folder: /themes/core/exceptions/
     * The filename has to be "exception-ID.html", where ID is the exception id.
     *
     * @example
     * <code>
     * throw new Clansuite_Exception('My Exception Message: ', 20);
     * </code>
     * The file "exception-20.html" will be retrieved.
     *
     * @param $code The exception code.
     */
    private static function fetchExceptionTemplate($code)
    {
        # construct filename with code
        $exception_template_file = ROOT . 'themes/core/exceptions/exception-'.$code.'.html';

        # ensure file is there, load it and set it to classvariable
        if(is_file($exception_template_file) === true)
        {
            self::$exception_template_content = file_get_contents($exception_template_file);
        }
    }

    /**
     * Fetches a Helper Template for this exception by it's exception code.
     * This is for rapid development purposes.
     *
     * You find the Exception Development Template in the folder: /themes/core/exceptions/
     * The filename has to be "exception-dev-ID.html", where ID is the exception id.
     *
     * @example
     * <code>
     * throw new Clansuite_Exception('My Exception Message: ', 20);
     * </code>
     * The file "exception-dev-20.html" will be retrieved.
     *
     * @param $code The exception code.
     */
    private static function fetchExceptionDevelopmentTemplate($code)
    {
        # construct filename with code
        $exception_template_file = ROOT . 'themes/core/exceptions/exception-dev-'.$code.'.html';

        if(is_file($exception_template_file) === true)
        {
            $content = file_get_contents($exception_template_file);
            self::setExceptionDevelopmentTemplate($content);
        }
    }

    /**
     * Setter Method for the Content of the ExceptionDevelopmentTemplate
     *
     * @param string $content HTML Content of the Exception Development Template
     */
    private static function setExceptionDevelopmentTemplate($content)
    {
        self::$exception_development_template_content = $content;
    }

    /**
     * Getter Method for the exception_development_template_content
     *
     * @return HTML Representation of $exception_development_template_content
     */
    private static function getExceptionDevelopmentTemplate($placeholders)
    {
        $original_file_content = self::$exception_development_template_content;
        $replaced_content = '';

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
     * Yellow Screen of Death (YSOD) is used to display a Clansuite Exception
     */
    public function yellowScreenOfDeath()
    {
        # Header
        $errormessage    = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        $errormessage   .= '<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">';
        $errormessage   .= '<head>';
        $errormessage   .= '<title>Clansuite Exception : [ '. $this->getMessage() .' | Exceptioncode: '. self::getCode() .' ] </title>';
        $errormessage   .= '<link rel="stylesheet" href="'. WWW_ROOT_THEMES_CORE .'css/error.css" type="text/css" />';
        $errormessage   .= '</head>';

        # Body
        $errormessage   .= '<body>';

        # Fieldset with colours (error_red, error_orange, error_beige)
        $errormessage   .= '<fieldset class="error_beige">';

        # Errorlogo
        $errormessage   .= '<div style="float: left; margin: 5px; margin-right: 25px; border:1px inset #bf0000; padding: 20px;">';
        $errormessage   .= '<img src="'. WWW_ROOT_THEMES_CORE .'images/Clansuite-Toolbar-Icon-64-exception.png" style="border: 2px groove #000000;" alt="Clansuite Exception Icon" /></div>';

        # Fieldset Legend
        $errormessage   .= '<legend>Clansuite Exception : [ '. self::getMessage() .' ]</legend>';

        # Error Messages from Object (table)
        # HEADING <Exception Object>
        $errormessage   .= '<table>';

        # @todo add link
        if ($this->code > 0)
        {
            $code = '(# '.$this->code.')';
        }
        else
        {
            $code = '';
        }

        $errormessage   .= '<tr><td colspan="2"><h3>Exception '.$code.'</h3><h4>'.$this->message.'</h4></td></tr>';
        $errormessage   .= '<tr><td><strong>Path: </strong></td><td>'.dirname($this->file).'</td></tr>';
        $errormessage   .= '<tr><td><strong>File: </strong></td><td>'.basename($this->file).'</td></tr>';
        $errormessage   .= '<tr><td><strong>Line: </strong></td><td>'.$this->line.'</td></tr>';

        # Split
        $errormessage   .= '<tr><td colspan="2">&nbsp;</td></tr>';

        # Debug Backtrace
        if ( defined('DEBUG') and DEBUG == 1 )
        {
            $errormessage   .= '<tr><td colspan="2"><h3>Backtrace</h3></td></tr>';
            $errormessage   .= '<tr><td><strong>Callstack: </strong></td><td colspan="2" width="80%">'. self::formatGetTraceString($this->string) . '</td></tr>';

            # Split
            $errormessage   .= '<tr><td colspan="2">&nbsp;</td></tr>';
        }

        # Environmental Informations at Errortime
        if ( defined('DEBUG') and DEBUG == 1 )
        {
            # HEADING <Server Environment>
            $errormessage  .= '<tr><td colspan="2"><h3>Server Environment</h3></td></tr>';
            $errormessage   .= '<tr><td><strong>Date: </strong></td><td>'.date('r').'</td></tr>';
            $errormessage   .= '<tr><td><strong>Remote: </strong></td><td>'.$_SERVER['REMOTE_ADDR'].'</td></tr>';
            $errormessage   .= '<tr><td><strong>Request: </strong></td><td>index.php?'.htmlentities($_SERVER['QUERY_STRING']).'</td></tr>';
            $errormessage   .= '<tr><td><strong>Server: </strong></td><td>'.$_SERVER['SERVER_SOFTWARE'].'</td></tr>';
            $errormessage   .= '<tr><td><strong>Agent: </strong></td><td>'.$_SERVER['HTTP_USER_AGENT'].'</td></tr>';
            $errormessage   .= '<tr><td><strong>Clansuite: </strong></td><td>'.CLANSUITE_VERSION.' '.CLANSUITE_VERSION_STATE.' ('.CLANSUITE_VERSION_NAME.') [Revision #'.CLANSUITE_REVISION.']</td></tr>';

            # Split
            $errormessage   .= '<tr><td colspan="2">&nbsp;</td></tr>';
        }

        # HEADING <Additional Information>
        if(empty(self::$exception_template_content) === false)
        {
            $errormessage  .= '<tr><td colspan="2"><h3>Additional Information & Solution Suggestion</h3></td></tr>';
            $errormessage  .= '<tr><td colspan="2">'.self::$exception_template_content.'</td></tr>';

            # Split
            $errormessage  .= '<tr><td colspan="2">&nbsp;</td></tr>';
        }

        # HEADING <Rapid Development>

        $placeholders = array();
        # assign placeholders for replacements in the html
        if(mb_strpos(self::getMessage(), 'action_'))
        {
            $placeholders['actionname'] = mb_substr($this->message, mb_strpos($this->message, 'action_'));
        }
        elseif(mb_strpos(self::getMessage(), 'module_'))
        {
            $placeholders['classname'] = mb_substr($this->message, mb_strpos($this->message, 'module_'));
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

        # close all html element table
        $errormessage   .= '</table>';

        # Footer with Support-Backlinks
        $errormessage  .= '<div style="float:right;">';
        $errormessage  .= '<strong><!-- Live Support JavaScript -->
                           <a href="http://support.clansuite.com/chat.php" target="_blank">Contact Support (Start Chat)</a>
                           <!-- Live Support JavaScript --></strong> | ';
        $errormessage  .= '<strong><a href="http://trac.clansuite.com/newticket/">Bug-Report</a></strong> |
                           <strong><a href="http://forum.clansuite.com/">Support-Forum</a></strong> |
                           <strong><a href="http://docs.clansuite.com/">Manuals</a></strong> |
                           <strong><a href="http://www.clansuite.com/">visit clansuite.com</a></strong>
                           </div>';

        # close all html elements: fieldset, body+page
        $errormessage   .= '</fieldset>';
        $errormessage   .= '</body></html>';

        # save session
        session_write_close();

        # Output the errormessage
        return $errormessage;
    }

    /**
     * Formats the debugtrace by applying linebreaks
     *
     * @param $string The debug-trace string to format.
     */
    public static function formatGetTraceString($string)
    {
        $search  = array('#', '):');
        $replace = array('<br/><br/>Call #',')<br/>');
        $string  = str_replace($search, $replace, $string);
        $string  = ltrim($string, '<br/>');
        unset($search, $replace);
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
    #public function __construct($message = null, $code = 0);
}
?>