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
    * @link       http://gna.org/projects/
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') == false)
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
 *      |
 *      \- Clansuite_Formelement_Checkbox
 */
class Clansuite_Formelement_Checkbox extends Clansuite_Formelement_Input implements Clansuite_Formelement_Interface
{
    /**
     * label next to element
     *
     * @var string
     */
    protected $label;

    protected $default;

    protected $options;

    public $description;

    public function setDefaultOption($default)
    {
        $this->default = $default;

        return $this;
    }

    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * constructor
     */
    public function __construct()
    {
        $this->type = 'checkbox';
        $this->label = null;

        return $this;
    }

    /**
     * check or unchecks the checkbox
     *
     * @param bool checked
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;

        return $this;
    }

    /**
     * sets clickable label next to element
     *
     * @param string $text
     */
    public function setLabel($text)
    {
        $this->label = '<label for="'.$this->id.'">'.$text.'</label>';

        return $this;
    }

    /**
     * sets description
     *
     * @param string $text
     */
    public function setDescription($text)
    {
        $this->description = $text;

        return $this;
    }

    public function render()
    {
        return parent::render() . $this->getLabel();
    }

    public function __toString()
    {
        return $this->render();
    }
}
?>