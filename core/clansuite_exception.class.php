<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005 - onwards
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
    * @copyright  Jens-Andre Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

class clansuite_exception extends exception
{
    // redeclare exception, so that it is not optional
    public function __construct($ErrorObject, $errormessage, $errorcode = 0)
    {
        // assign to parent
        parent::__construct($errormessage, $errorcode); 
        
        $this->ysod($ErrorObject, $errormessage, $errorcode);
        #clansuite_xdebug::printR($ErrorObject);
        exit;
    }

    /**
     * Yellow Screen of Death (YSOD) is used to display errors
     *
     * @param string $ErrorObject contains ErrorObject
     * @param string $error_head contains the Name of the Error
     * @param string $string contains errorstring
     * @param integer $error_level contains errorlvl
     */
    public function ysod( $ErrorObject, $error_head = 'Unknown Error', $string = '', $error_level = 3 )
    {
        # Header
        $errormessage    = '<html><head>';
        $errormessage   .= '<title>Clansuite Error : '. $error_head .' | Errorcode: '. $error_level .'</title>';
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
        $errormessage   .= '<legend>Clansuite Error : '. $error_head .'</legend>';
        # Error String (passed Error Description)
        #$errormessage   .= '<p><strong>'.$ErrorObject->message.'</strong>';
        # Error Messages from the ErrorObject
        $errormessage   .= '<hr style="width=80%">';
        $errormessage   .= '<table>';
        $errormessage  .= '<tr><td><h3>Error</h3></td></tr>';
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
        #if ( defined('DEBUG') && DEBUG == 1 )
        #{
        #    $errormessage   .= '<tr><td>' . $this->getDebugBacktrace() . '</td></tr>';
        #}
        # close all html elements: table, fieldset, body+page
        $errormessage   .= '</table>';
        $errormessage   .= '</fieldset>';
        $errormessage   .= '</body></html>';
        # Output the errormessage
        echo $errormessage;
    }
}
?>