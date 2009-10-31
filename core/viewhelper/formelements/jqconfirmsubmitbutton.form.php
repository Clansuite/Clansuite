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
    * @author     Jens-Andr� Koch   <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-Andr� Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.');}

if (!class_exists('Clansuite_Formelement_Input')) { require 'input.form.php'; }

/**
 *  Clansuite_Formelement
 *  |
 *  \- Clansuite_Formelement_Input
 *     |
 *     \- Clansuite_Formelement_JQConfirmSubmitButton
 */
class Clansuite_Formelement_JQConfirmSubmitButton extends Clansuite_Formelement_Input implements Clansuite_Formelement_Interface
{    
    protected $message = 'Please Confirm';
    
    # takes the name of the form (to trigger the original sumbit)
    protected $formid;

    function __construct()
    {
        $this->type = "submit";
        $this->value = _("Confirm & Submit");
        $this->class = "ButtonGreen";
        
        #clansuite_xdebug::printR($this->formid);

        # Add the Form Submit Confirmation Javascript. This is a jQuery UI Modal Confirm Dialog.
        # to add the value of specific form.elements to the message use "+ form.elements['email'].value +"
        # watch out, that div dialog is present in the dom, before you assign function to it via $('#dialog')
        $this->description = "<div id=\"dialog\" title=\"Verify Form\">
                                  <p>If your is correct click Submit Form.</p>
                                  <p>To edit, click Cancel.<p>                                  
                              </div>
                              
                              <script type=\"text/javascript\">
                                      
                               // jQuery UI Dialog

                               $('#dialog').dialog({
                                    autoOpen: false,
                                    width: 400,
                                    modal: true,
                                    resizable: false,
                                    buttons: {
                                        \"Submit Form\": function() {
                                            document.".$this->formid.".submit();
                                        },
                                        \"Cancel\": function() {
                                            $(this).dialog(\"close\");
                                        }
                                    }
                                });
        
        
                              $('form#".$this->formid."').submit(function(){
                                $('#dialog').dialog('open');
                                 return false;
                               });
                              </script>                             
                             ";
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }
    
    public function setFormId($formid)
    {
        $this->formid = $formid;

        return $this;
    }
}
?>