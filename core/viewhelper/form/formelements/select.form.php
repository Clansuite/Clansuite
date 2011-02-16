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

/**
 *  Clansuite_Form
 *  |
 *  \- Clansuite_Formelement_Select
 */
class Clansuite_Formelement_Select extends Clansuite_Formelement implements Clansuite_Formelement_Interface
{
    /**
     * @var array array with options for the dropdown
     */
    public $options;

    /**
     * @var string name of the default option of the select dropdown (pre-selection)
     */
    public $default;

    /**
     * @var string description
     */
    public $description;

    /**
     * 0 = pure dropdown with 1 field
     * 3 = 3 elements shown, rest available via scrollbar
     * @var int number of displayed items
     */
    public $size;

    /**
     * @var string Label
     */
    #public $label ='Select an item from this pull-down menu.';

    public function __construct()
    {
        $this->type = 'select';
    }

    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * This sets the default value.
     * Value is used to mark that option as "selected"
     */
    public function setDefaultValue($default)
    {
        $this->default = $default;

        return $this;
    }

    public function render()
    {
        # open the html select tag
        $html = '';
        $html .= '<select ';
        $html .= (bool) $this->name ? 'name="'.$this->name.'"' : null;
        $html .= (bool) $this->id ? 'id="'.$this->id.'"' : null;
        $html .= (bool) $this->class ? 'class="'.$this->class.'"' : null;
        $html .= (bool) $this->size ? 'size="'.$this->size.'"' : null;
        $html .= '>';

        /**
         * This handles the default value setting via the options array key "selected".
         * It grabs the first element in the options array, which keyname should be 'selected'
         * and then removes it, setting its value to $this->default.
         *
         * The check for empty($this->default) is important, because the default value might already
         * be set via setDefaultValue(). Such a scenario is given, when the form is generated via array.
         * The array would contain options['selected'] with an default value, but the actual default
         * value is incomming via setDefaultValue().
         *
         * Note: If the options array is incomming via a formgenerator: the generator has already performed this step!
         * $this->setDefault(options['selected']);
         */
        if(isset($this->options['selected']) and empty($this->default))
        {
            $this->default = $this->options['selected'];
            unset($this->options['selected']);
        }

        if(empty($this->options) === false)
        {
            # loop over all selectfield options
            foreach ($this->options as $key => $value)
            {
                $html .= $this->renderOptionTag($key, $value);
            }
        }
        else
        {
            $html .= '<option value="0">No Options given.</option>';
        }

        # close the html select tag
        $html .= '</select>';

        return $html;
    }

    private function renderOptionTag($key, $value)
    {
        # check if the value is the default one and in case it is, add html "selected"
        if($key == $this->default)
        {
            return '<option value="' . $key . '" selected="selected">' . $value . '</option>';
        }
        else # a normal select element is rendered
        {
            return '<option value="' . $key . '">' . $value . '</option>';
        }
    }

}
?>