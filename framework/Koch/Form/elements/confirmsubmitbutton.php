<?php

/**
 * Koch Framework
 * Jens-André Koch © 2005 - onwards
 *
 * This file is part of "Koch Framework".
 *
 * License: GNU/GPL v2 or any later version, see LICENSE file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 */

namespace Koch\Formelement;

class ConfirmSubmitButton extends Input implements FormelementInterface
{
    protected $message = 'Please Confirm';

    public function __construct($message = null)
    {
        $this->type = 'submit';
        $this->value = _('Confirm & Submit');
        $this->class = 'ButtonGreen';

        /**
         * Add the Form Submit Confirmation Javascript.
         * This is a pure Javacript Return Confirm.
         * To add the value of specific "form.elements" to the message
         * use: "+ form.elements['email'].value +"
         */
        $this->setAdditionalAttributeAsText("onclick=\"if (confirm('Are you sure you want to submit this form?\\n\\nClick OK to submit or Cancel to abort.')) { submit(); } else { return false; } \" value=\"Submit\"");
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }
}
