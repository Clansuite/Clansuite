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
if (false == class_exists('Clansuite_Formelement',false))
{
    include dirname(__DIR__) . '/formelement.core.php';
}

/**
 *  Clansuite_Formelement
 *  |
 *  \- Clansuite_Formelement_Input
 *
 * @link http://www.whatwg.org/specs/web-apps/current-work/multipage/the-input-element.html
 */
class Clansuite_Formelement_Input extends Clansuite_Formelement implements Clansuite_Formelement_Interface
{
    /**
     * input type
     *
     * @var int
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
     * Set placeholder attribute value
     *
     * @link http://dev.w3.org/html5/spec/Overview.html#the-placeholder-attribute
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
    
        return $this;
    }

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
     * generates html code of element
     */
    public function render()
    {
        $html  = null;
        $html .= '<input type="'.$this->type.'" name="'.$this->name.'"';
        $html .= (bool) $this->id ? ' id="'.$this->id.'"' : null;
        $html .= (bool) $this->value ? ' value="'.$this->value.'"' : null;
        $html .= (bool) $this->placeholder ? ' placeholder="'.$this->placeholder.'"' : null;
        $html .= (bool) $this->size ? ' size="'.$this->size.'"' : null;
        $html .= (bool) $this->maxlength ? ' maxlength= "'.$this->maxlength.'"' : null;
        $html .= (bool) $this->pattern ? ' pattern= "'.$this->pattern.'"' : null;
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
