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

use Koch\Form\FormElement;
use Koch\Form\FormElementInterface;

class ButtonBar extends FormElement implements FormElementInterface
{
    /**
     * Definition Array for the Buttonbar
     * It defines the buttons to add as formelements to the buttonbar.
     * The default buttons are submit, reset and cancel.
     *
     * @var array $_buttons buttonname => button object
     */
    private $_buttons = array( 'submitbutton' => '',
                               'resetbutton'  => '',
                               'cancelbutton' => '');

    /**
     * Adds the objects to the buttonnames fo the initial buttons array
     *
     * @return Koch_Formelement_Buttonbar
     */
    public function __construct()
    {
        // apply CSS class attribute
        $this->setClass('buttonbar');

        return $this;
    }

    public function addButton($buttonname)
    {
        if (is_string($buttonname)) {
            // turn buttoname to formelement name (cancelbutton => CancelButton)
            $formelement = str_replace('button', 'Button', ucfirst($buttoname));
            // fetch the button-formelement
            $formelement = '\Koch\Form\Elements\\' . $formelement;

            $formelement = new $formelement;
        }

        // @todo use instanceof Koch_Formelement_Button
        if (is_object($buttonname) and (!$buttonname instanceof Koch_Formelement_Input)) {
            throw new Koch_Exception('The button must a be formelement object.');
        }

        // attach button object to buttons array
        $this->_buttons[$buttonname] = $formelement;

        return $this;
    }

    /**
     * Gets a button
     *
     * @param  string                     $_buttonname
     * @return Koch_Formelement_Buttonbar
     */
    public function getButton($buttonname)
    {
        if ($this->_buttons[$buttonname] === null) {
            throw new Koch_Exception(_('This button does not exist, so its not in this buttonbar: ') . $buttonname);
        }

        // return the button object
        if (is_object($this->_buttons[$buttonname])) {
            return $this->_buttons[$buttonname];
        }

        // instantiate the button object first and then return
        if ( false === is_object($this->_buttons[$buttonname])) {
            $this->addButton($buttonname);

            return $this->_buttons[$buttonname];
        }
    }

    /**
     * Remove a button from the stack
     *
     * @param  string                     $_buttonname
     * @return Koch_Formelement_Buttonbar
     */
    public function removeButton($_buttonname)
    {
        if ( isset($this->_buttons[$_buttonname]) ) {
            unset($this->_buttons[$_buttonname]);
        }

        return $this;
    }

    public function setCancelButtonURL($url)
    {
       $this->getButton('cancelbutton')->setCancelURL($url);

       return $this;
    }

    /**
     * Renders the buttonbar with all registered buttons
     *
     * @return $htmlString HTML Representation of Koch_Formelement_Buttonbar
     */
    public function render()
    {
        $htmlString = '<div class="'.$this->getClass().'">';

        foreach ($this->_buttons as $buttonname => $buttonobject) {
            if (is_object($buttonobject)) {
                $htmlString .= $buttonobject->render();
            } else {
                // does this ever happen???, see addButton!
                $formelement = '\Koch\Form\Elements\\' . ucfirst($buttonname);
                $formelement = new $formelement;
                $htmlString .= $formelement->render();
            }
        }

        $htmlString .= '</div>';

        return $htmlString;
    }
}
