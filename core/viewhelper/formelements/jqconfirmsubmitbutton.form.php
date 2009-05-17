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
 *
 *  Clansuite_Form_Formelement
 *  |
 *  \- Clansuite_Form_Formelement_Button
 *     |
 *     \- Clansuite_Formelement_JQConfirmSubmitButton
 */
class Clansuite_Formelement_JQConfirmSubmitButton extends Clansuiute_Formelement_Button
{
    /**
     * @todo javascript to implement
     *
     *  jConfirm('Can you confirm this?', 'Confirmation Dialog', function(r) {
     *      jAlert('Confirmed: ' + r, 'Confirmation Results');
     *  });
     *
     **/

    function Clansuite_Formelement_JQConfirmSubmitButton($label, $value, $message = null, $width = null, $height = null)
    {
        $action = "if (confirm('".$message."')) {this.form.".$this->formaction.".value='".$value."'; submit();}";

        $this->Clansuite_Formelement_Button($label, $value, $action, $width, $height);
    }
}