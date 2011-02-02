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

# conditional include of the parent class
if (false == class_exists('Clansuite_Formelement_Input',false))
{
    include dirname(__FILE__) . '/input.form.php';
}

/**
 *  Clansuite_Formelement
 *  |
 *  \- Clansuite_Formelement_Input
 *     |
 *     \- Clansuite_Formelement_ConfirmSubmitButton
 */
class Clansuite_Formelement_ConfirmSubmitButton extends Clansuite_Formelement_Input implements Clansuite_Formelement_Interface
{
    protected $message = 'Please Confirm';

    function __construct($message = null)
    {
        $this->type = 'submit';
        $this->value = _('Confirm & Submit');
        $this->class = 'ButtonGreen';

        # Add the Form Submit Confirmation Javascript. This is a pure Javacript Return Confirm.
        # to add the value of specific form.elements to the message use "+ form.elements['email'].value +"
        $this->setAdditionalAttributeAsText("onclick=\"if (confirm('Are you sure you want to submit this form?\\n\\nClick OK to submit or Cancel to abort.')) { submit(); } else { return false; } \" value=\"Submit\"");
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }
}
?>