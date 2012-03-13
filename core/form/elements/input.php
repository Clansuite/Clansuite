<?php
   /**
    * Koch Framework
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Koch Framework".
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

namespace Koch\Form\Formelement;

use Koch\Form\Formelement;
use Koch\Form\FormelementInterface;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Koch_Formelement_Input
 *
 * @link http://www.whatwg.org/specs/web-apps/current-work/multipage/the-input-element.html
 */
class Input extends Formelement implements FormelementInterface
{
    /**
     * The formelement input type, e.g.
     * text, password, checkbox, radio, submit, reset, file, hidden, image, button.
     *
     * @var string
     */
    public $type;

    /**
     * custom css class
     *
     * @var string
     */
    public $class;

    /**
     * indicates whether checkbox is checked
     *
     * @var int
     */
    public $checked;

    /**
     * indicates whether radio button is selected
     *
     * @var int
     */
    public $selected;

    /**
     * length of field in letters
     *
     * @var int
     */
    public $size;

    /**
     * allowed length of input in letters
     *
     * @var int
     */
    public $maxlength;

    /**
     * disabled
     *
     * @var boolean
     */
    public $disabled;

    /**
     * additional string to attach to the opening form tag
     * for instance 'onSubmit="xy"'
     *
     * @var $string;
     */
    public $additional_attr_text;

    /**
     * description
     *
     * @var int
     */
    public $description;

    /**
     * A regular expression pattern, e.g. [A-Za-z]+\d+
     *
     * @var string
     */
    public $pattern;

    /**
     * String value for the placeholder attribute
     * <input placeholder="some placeholder">
     *
     * @var string
     */
    public $placeholder;

    /**
     * The readonly attribute specifies that an input field should be read-only.
     *
     * @var string
     */
    public $readonly;

    /**
     * Sets the state of the input field to read-only.
     *
     * @param boolean $disabled True or False.
     */
    public function setReadonly($readonly)
    {
        $this->readonly = (bool) $readonly;
    }

    /**
     * Set placeholder attribute value
     *
     * @link http://dev.w3.org/html5/spec/Overview.html#the-placeholder-attribute
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Get Placeholder <input placeholder="some placeholder">
     *
     * @return type
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * Set the regular expression pattern for client-side validation
     * e.g. [A-Za-z]+\d+
     *
     * @var string
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * defines length of field in letters
     *
     * @param int $size
     */
    public function setSize($size)
    {
        $this->size = (int) $size;
    }

    /**
     * defines allowed length of input in letters
     *
     * @param int $length
     */
    public function setMaxLength($length)
    {
        $this->maxlength = (int) $length;
    }

    /**
     * defines allowed length of input in letters
     *
     * @param boolean $disabled True or False.
     */
    public function setDisabled($disabled)
    {
        $this->disabled = (bool) $disabled;
    }

    /**
     * Set Additional Attributes as Text to formelement.
     *
     * @example
     * Setting the onclick attribute.
     * $this->setAdditionalAttributeText(' onclick="window.location.href=\''.$this->cancelURL.'\'"');
     *
     * @param $additional_attr_text of this formelement.
     */
    public function setAdditionalAttributeAsText($additional_attr_text)
    {
        $this->additional_attr_text = $additional_attr_text;

        return $this;
    }

    /**
     * Renders the html code of the input element.
     *
     * @return string
     */
    public function render()
    {
        $html  = null;
        $html .= '<input type="'.$this->type.'" name="'.$this->name.'"';
        $html .= (bool) $this->id ? ' id="'.$this->id.'"' : null;
        $html .= (bool) $this->value ? ' value="'.$this->value.'"' : null;
        $html .= (bool) $this->placeholder ? ' placeholder="'.$this->placeholder.'"' : null;
        $html .= (bool) $this->size ? ' size="'.$this->size.'"' : null;
        $html .= (bool) $this->readonly ? ' readonly="readonly"' : null;
        $html .= (bool) $this->disabled ? ' disabled="disabled"' : null;
        $html .= (bool) $this->maxlength ? ' maxlength="'.$this->maxlength.'"' : null;
        $html .= (bool) $this->pattern ? ' pattern="'.$this->pattern.'"' : null;
        $html .= (bool) $this->class ? ' class="'.$this->class.'"' : null;
        $html .= ($this->type == 'image') ? ' source="'.$this->source.'"' : null;
        $html .= ($this->type == 'image' and (bool) $this->width and (bool) $this->height) ? '  style="width:'.$this->width.'px; height:'.$this->height.'px;"' : null;
        $html .= (bool) $this->checked ? ' checked="checked"' : null;
        $html .= (bool) $this->additional_attr_text ? $this->additional_attr_text : null;
        $html .= (bool) $this->additional_attributes ? $this->render_attributes($this->additional_attributes) : null;
        $html .= ' />' . CR;

        return $html;
    }
}
?>
