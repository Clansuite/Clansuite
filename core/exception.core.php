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

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite_Exception
 *
 * Developer Notice:
 * The "Fatal error: Exception thrown without a stack frame in Unknown on line 0"
 * is of PHP dying when an exception is thrown when running INSIDE an error or exception handler.
 * Avoid stacking Exceptions, e.g. try/catch Exception($e) and then throwing a Clansuite_Exception().
 */
class Clansuite_Exception extends Exception
{
    private static $exception_template_content = '';

    # redeclare exception, so that it is not optional
    public function __construct($message = null, $code = 0)
    {
        # assign to parent
        parent::__construct($message, $code);

        # fetch errorTemplate, but not for $code = 0
        if( $code > 0 )
        {
            self::fetchExceptionTemplate($code);
        }

        # debug display of exception object
        #clansuite_xdebug::printR($this);
    }

    /**
     * Fetches an ErrorTemplate from File and sets it to the object
     *
     * @param $code errorcode
     */
    private static function fetchExceptionTemplate($code)
    {
        $exception_template_file = ROOT . 'themes/core/exceptions/exception-'.$code.'.html';
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
     * Getter Method for the exception_template_conent
     *
     * @return Content of $exception_template_content
     */
    private static function getExceptionTemplate()
    {
        return self::$exception_template_content;
    }

    /**
     * Exception Handler Callback
     *
     * Type Hint: only Exception Object valid
     * @param $exception
     */
    public function clansuite_exception_handler( Exception $exception )
    {
       # display exceptions if errors are not suppressed
       #if ($this->config['suppress_errors'] == 0 )
       #{
            echo $exception;
       #}
    }

    /**
     * Overwriteable Method of Class Exception
     * This is the String representation of the exception.
     * It is a pass-through to our presentation format (ysod);
     */
    public function __toString()
    {
        return $this->yellowScreenOfDeath();
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
        $errormessage   .= '<title>Clansuite Exception : [ '. self::getMessage() .' | Errorcode: '. self::getCode() .' ] </title>';
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
        $errormessage   .= '<tr><td width=15%><strong>Code :</strong></td><td>'.self::getCode().'</td></tr>';
        $errormessage   .= '<tr><td><strong>Message :</strong></td><td>'.self::getMessage().'</td></tr>';
        $errormessage   .= '<tr><td><strong>Pfad :</strong></td><td>'.dirname(self::getFile()).'</td></tr>';
        $errormessage   .= '<tr><td><strong>Datei :</strong></td><td>'.basename(self::getFile()).'</td></tr>';
        $errormessage   .= '<tr><td><strong>Zeile :</strong></td><td>'.self::getLine().'</td></tr>';

        /*if ( defined('DEBUG') && DEBUG == 1 )
        {
            $errormessage   .= '<tr><td><strong>Trace :</strong></td><td colspan=2 width=80%>'. self::formatGetTraceString(self::getTraceAsString()) . '</td></tr>';
        }*/

        # Environmental Informations at Errortime
        if ( defined('DEBUG') && DEBUG == 1 )
        {
        # Split
        $errormessage   .= '<tr><td colspan="2">&nbsp;</td></tr>';

        # HEADING <Server Environment>
        $errormessage  .= '<tr><td colspan="2"><h3>Server Environment</h3></td></tr>';
        $errormessage   .= '<tr><td><strong>Date :</strong></td><td>'.date('r').'</td></tr>';
        $errormessage   .= '<tr><td><strong>Request :</strong></td><td>index.php?'.htmlentities($_SERVER['QUERY_STRING']).'</td></tr>';
        $errormessage   .= '<tr><td><strong>Remote :</strong></td><td>'.$_SERVER['REMOTE_ADDR'].'</td></tr>';
        $errormessage   .= '<tr><td><strong>Server :</strong></td><td>'.$_SERVER['SERVER_SOFTWARE'].'</td></tr>';
        $errormessage   .= '<tr><td><strong>Agent :</strong></td><td>'.$_SERVER['HTTP_USER_AGENT'].'</td></tr>';
        $errormessage   .= '<tr><td><strong>Clansuite :</strong></td><td>'.CLANSUITE_VERSION.' '.CLANSUITE_VERSION_STATE.' ('.CLANSUITE_VERSION_NAME.') [Revision #'.CLANSUITE_REVISION.']</td></tr>';
        }

        # Split
        $errormessage   .= '<tr><td colspan="2">&nbsp;</td></tr>';

        # HEADING <Additional Information>
        if(self::getExceptionTemplate() != '')
        {
            $errormessage  .= '<tr><td colspan="2"><h3>Additional Information & Solution Suggestion</h3></td></tr>';
            $errormessage   .= '<tr><td colspan="2">'.self::getExceptionTemplate().'</td></tr>';
        }

        # Split
        $errormessage   .= '<tr><td colspan="2">&nbsp;</td></tr></table>';

        # Footer with Support-Backlinks
        $errormessage  .= '<div style="float:right;">
                           <strong><a href="http://trac.clansuite.com/">Bug-Report</a></strong> |
                           <strong><a href="http://forum.clansuite.com/">Support-Forum</a></strong> |
                           <strong><a href="http://docs.clansuite.com/">Manuals</a></strong> |
                           <strong><a href="http://www.clansuite.com/">visit clansuite.com</a></strong>
                           </div>';

        # Split
        $errormessage   .= '<tr><td colspan="2">&nbsp;</td></tr>';

        # close all html elements: table, fieldset, body+page
        $errormessage   .= '</table>';
        $errormessage   .= '</fieldset>';
        $errormessage   .= '</body></html>';

        # Output the errormessage
        return $errormessage;
    }

    /**
     * formats the getTraceString by applying linebreaks
     */
    public static function formatGetTraceString($string)
    {
        $string = str_replace('#','<br/><br/>#', $string);
        $string = str_replace('):','):<br/><br/>', $string);
        return $string;
    }
}
?>