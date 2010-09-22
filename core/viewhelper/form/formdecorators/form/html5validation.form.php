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
    * @copyright  Jens-André Koch (2005-onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

class Clansuite_Form_Decorator_Html5validation extends Clansuite_Form_Decorator
{
    public $name = 'html5validation';

    /**
     * Adds HTML5 form validation support to the form
     *
     * HTML5 validates forms without additional JavaScript.
     * Currently (September 2010) only Safari & Google Chrome support this functionality
     * The jQuery Plugin html5form validates formelements with html5 syntax in Firefox, Opera & Internet Explorer
     */
    public function addValidationJavascript()
    {
        # init var
        $html_form = '';

        # add html5 validation support for FF,O,IE
        $html_form .= '<script type="text/javascript" src="' . WWW_ROOT_THEMES_CORE . 'javascript/jquery/jquery.html5form-min.js">';
        
        $ident_form = '[Error] Form has no id or name!';
        # to identify the form use the name or id
        if( mb_strlen($this->getName()) > 0 )
        {
             $ident_form .= $this->getName();
        }
        
        if( mb_strlen($this->getId()) > 0 )
        {
             $ident_form .= $this->getId();
        }

        # activate html5 syntax validation support on the form
        $html_form .= '<script>
                      $(document).ready(function(){
                          $(\'#' . $ident_form . '\').html5form();
                      });
                      </script>';

        return $html_form;
    }

    public function render($html_form_content)
    {
        if(true === is_file(ROOT_THEMES_CORE . 'javascript/jquery/jquery.html5form-min.js'))
        {
            # put all the pieces of html together
            return $this->addValidationJavascript() . $html_form_content;
        }
        else # fail by prepending a message :(
        {
            $message = '[ERROR] HTML5 Validation Support not available. File missing : <br/>'.
            ROOT_THEMES_CORE . 'javascript/jquery/jquery.html5form-min.js';
            
            return  $message . $html_form_content;
        }
    }
}
?>