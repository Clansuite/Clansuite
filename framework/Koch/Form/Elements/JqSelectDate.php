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

/**
 *Renders jquery Datepicker for date selection, you know?
 */
class JQSelectDate extends Formelement implements FormElementInterface
{
    /**
     * Flag Variable for the output of the datepicker as an icon (if true)
     */
    protected $asIcon = false;

    /**
     * contains the datepicker html string for output
     */
    protected $html = '<div type="text" id="datepicker"></div>';

    /**
     * Datepicker Attributes Array contains the options for the javascript object.
     *
     * @var array
     */
    private $attributes = array();

    /**
     * Contains a sprintf javascript function string
     * @var string
     */
    private $sprintf_datepicker_js = '<script type="text/javascript">
                                         $(document).ready(function(){
                                            $("#%s").datepicker({
                                            %s
                                         }); });</script>';

    /**
     * contains the javascripts (html)
     *
     * @todo js-queue, duplication check
     *
     * @var string
     */
     // <script type="text/javascript" src="http://jqueryui.com/latest/jquery-1.3.2.js"></script>
     // <script type="text/javascript" src="http://jqueryui.com/latest/ui/ui.core.js"></script>
    private $javascript_libraries =  '<link type="text/css" href="http://jqueryui.com/latest/themes/base/ui.all.css" rel="stylesheet" />
                                      <script type="text/javascript" src="http://jqueryui.com/latest/ui/ui.datepicker.js"></script>';

    public function __construct()
    {
        $this->type = 'date';
        $this->name = 'datepicker';
    }

    /**
     * Returns the generated
     */
    public function getJavascript()
    {
        return $this->javascript_libraries.sprintf($this->sprintf_datepicker_js, $this->getName(), $this->getAttributes());
    }

    /**
     * Gets the datepicker attributes array
     *
     * @see setAttributes
     */
    public function getAttributes()
    {
        #\Koch\Debug\Debug::printR($this->attributes);
        $attributes_html = '';
        foreach ($this->attributes as $attribute => $value) {

            $attributes_html .= $attribute.':"'.$value.'",'.CR;
        }

        return $attributes_html;
    }

    public function setAttributes(array $attributes)
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    /**
     * Sets a single attribute to the datepicker attributes array
     *
     * @see setAttributes
     */
    public function setAttribute($attribute, $value)
    {
        $new_attribute = array();
        $new_attribute[$attribute] = $value;
        $this->setAttributes($new_attribute);

        return $this;
    }

    /**
     * Adjusts datepicker options and html output to display the datepicker as an icon.
     */
    public function asIcon()
    {
        // define relevat attributes to display the datepicker as an icon
        $datepicker_attributes = array(
                                       'firstDay' => '1',
                                       'format' => 'yy-mm-dd',
                                       'showOn' => 'button',
                                       'buttonImage'    => 'themes/core/images/lullacons/calendar.png',
                                       'buttonImageOnly' => 'true',
                                       'constrainInput' => 'false',
                                      );

        // set the relevant attributes
        $this->setAttributes($datepicker_attributes);

        // datepicker icon trigger needs a input element, so we replace the original (div) string
        $this->html = '<input type="text" id="datepicker">';

        return $this;
    }

    /**
     * Adds the jQuery UI Date Select Dialog
     */
    public function render()
    {
        $html = '';
        $html .= $this->html;
        // Watch out, that the div dialog is present in the dom, before you assign js function to it via $('#datepicker')
        $html .= $this->getJavascript();

        return $html;
    }

}
