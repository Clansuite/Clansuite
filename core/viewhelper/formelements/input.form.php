<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch   <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-Andr� Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') == false){ die('Clansuite not loaded. Direct Access forbidden.');}

# conditional include of the parent class
if (false == class_exists('Clansuite_Formelement', false))
{ 
    include ROOT_CORE.'viewhelper/formelement.core.php';
}

/**
 *  Clansuite_Formelement
 *  |
 *  \- Clansuite_Formelement_Input
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
     * URL of image
     *
     * @var string
     */
    public $source;

    /**
     * width of image (px)
     *
     * @var int
     */
    public $width;

    /**
     * height of image (px)
     *
     * @var int
     */
    public $height;

    /**
     * additional string to attach to the opening form tag
     * for instance 'onSubmit="xy"'
     *
     * @var $string;
     */
    public $additionals;

    /**
     * description
     *
     * @var int
     */
    public $description;

    /**
     * Set Additionals to t formelement.
     *
     * @param $additionals of this formelement.
     */
    public function setAdditionals($additionals)
    {
        $this->additionals = $additionals;
        return $this;
    }

    /**
     * generates html code of element
     */
    public function render()
    {
        $html  = null;
        $html .= '<input type="'.$this->type.'" name="'.$this->name.'"';
        $html .= (bool)$this->id ? ' id="'.$this->id.'"' : null;
        $html .= (bool)$this->value ? ' value="'.$this->value.'"' : null;
        $html .= (bool)$this->size ? ' size="'.$this->size.'"' : null;
        $html .= (bool)$this->maxlength ? ' maxlength= "'.$this->maxlength.'"' : null;
        $html .= (bool)$this->class ? ' class="'.$this->class.'"' : null;
        $html .= ($this->type == 'image') ? ' source="'.$this->source.'"' : null;
        $html .= ($this->type == 'image' && (bool)$this->width && (bool)$this->height) ? '  style="width:'.$this->width.'px; height:'.$this->height.'px;"' : null;
        $html .= (bool)$this->checked ? ' checked="checked"' : null;
        $html .= (bool)$this->additionals ? $this->additionals : null;
        $html .= ' />' . CR;

        return $html;
    }

    public function __toString()
    {
        return $this->render();
    }
}
?>
