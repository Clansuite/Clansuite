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
    * @author     Jens-André Koch   <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.');}

/**
 *  Clansuite_Formelement
 *  |
 *  \- Clansuite_Formelement_JQSelectDate
 */
class Clansuite_Formelement_JQSelectDate extends Clansuite_Formelement implements Clansuite_Formelement_Interface
{
    protected $asIcon = false;

    function __construct()
    {
        $this->type = "date";

        # add the javascripts to the queue of the page (@todo queue, duplication check)
        $this->addJS = '<link type="text/css" href="http://jqueryui.com/latest/themes/base/ui.all.css" rel="stylesheet" />
                        <script type="text/javascript" src="http://jqueryui.com/latest/jquery-1.3.2.js"></script>
                        <script type="text/javascript" src="http://jqueryui.com/latest/ui/ui.core.js"></script>
                        <script type="text/javascript" src="http://jqueryui.com/latest/ui/ui.datepicker.js"></script>
                       ';

        # Add the jQuery UI Date Select Dialog.
        # Watch out, that the div dialog is present in the dom, before you assign js function to it via $('#datepicker')
        $this->datepicker_default   = '<script type="text/javascript">
                                      $(document).ready(function(){
                                        $("#datepicker").datepicker();
                                      });
                                      </script>';

        $this->datepicker_icon   = '<script type="text/javascript">
                                      $(document).ready(function(){
                                        $("#datepicker").datepicker({showOn: \'button\', buttonImage: \'themes/core/images/lullacons/calendar.png\', buttonImageOnly: true});
                                      });
                                      </script>';
    }

    public function asIcon()
    {
        $this->asIcon = true;

        return $this;
    }

    public function render()
    {
        if($this->asIcon == true)
        {
            # datepicker icon trigger needs a input element
            return $this->datepicker_icon.' <input type="text" id="datepicker">';
        }
        else
        {
            # default needs a div-element
            return $this->datepicker_default.'<div type="text" id="datepicker" title="JQuery Datepicker"></div>';
        }

    }

    public function __toString()
    {
        return $this->render();
    }
}
?>