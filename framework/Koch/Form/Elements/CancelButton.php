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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Koch\Form\Elements;

use Koch\Form\Elements\Input;
use Koch\Form\FormElementInterface;

class CancelButton extends Input implements FormElementInterface
{
    /**
     * Holds the url when canceling
     *
     * @var string
     */
    public $cancelURL = 'history.back(); return false;'; // depends on javascript

    public function __construct()
    {
        $this->type  = 'button';
        $this->value = _('Cancel');

        $this->class = 'CancelButton ButtonRed';
        $this->id    = 'CancelButton';
        $this->name  = 'CancelButton';
    }

    public function getCancelURL()
    {
        return $this->cancelURL;
    }

    /**
     * Sets the cancel URL (the url to redirect the user to, after clicking cancel)
     *
     * @example
     * $form->addElement('buttonbar')
     *        ->getButton('cancelbutton')->setCancelURL('index.php?mod=languages&sub=admin&action=show');
     *
     * @param string cancelURL The Cancel URL (By default wrapped by window.location.href="")
     * @param bool suppressWrapping Toggle for wrapping
     */
    public function setCancelURL($cancelURL, $suppressWrapping = false)
    {
        if ($suppressWrapping === false) {
            $this->cancelURL = 'window.location.href=\'' . $cancelURL . '\'';
        } else {
           $this->cancelURL = $cancelURL;
        }
    }

    public function render()
    {
        $this->setAdditionalAttributeAsText(' onclick="' . $this->getCancelURL() . '"');

        return parent::render();
    }
}
