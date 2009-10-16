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
 *  Clansuite_Formelement_Input
 *  |
 *  \- Clansuite_Formelement
 */
class Clansuite_Formelement_Input extends Clansuite_Formelement implements Clansuite_Formelement_Interface
{
    /**
     * input type
     *
     * @var int
     */
    protected $type;

    /**
     * custom css class
     *
     * @var string
     */
    protected $class;

    /**
     * indicates whether checkbox is checked
     *
     * @var int
     */
    protected $checked;

    /**
     * indicates whether radio button is selected
     *
     * @var int
     */
    protected $selected;

    /**
     * length of field in letters
     *
     * @var int
     */
    protected $size;

    /**
     * allowed length of input in letters
     *
     * @var int
     */
    protected $maxlength;

    /**
     * URL of image
     *
     * @var string
     */
    protected $source;

    /**
     * width of image (px)
     *
     * @var int
     */
    protected $width;

    /**
     * height of image (px)
     *
     * @var int
     */
    protected $height;

    /**
     * generates html code of element
     *
     * @return void
     */
    public function render()
    {
        $html  = null;
        $html .= '<input type="'.$this->type.'" id="'.$this->id.'" name="'.$this->name.'" value="'.$this->value.'"';
        $html .= (bool)$this->size ? ' size= "'.$this->size.'"' : NULL;
        $html .= (bool)$this->maxlength ? ' maxlength= "'.$this->maxlength.'"' : NULL;
        $html .= ($this->type == 'text' || $this->type == 'password') ? ' class="' . $this->class .'"' : NULL;
        $html .= ($this->type == 'submit') ? ' class="submit ' . $this->class .'"' : NULL;
        $html .= ($this->type == 'checkbox') ? ' class="checkbox ' . $this->class .'"' : NULL;
        $html .= ($this->type == 'radio') ? ' class="radio ' . $this->class .'"' : NULL;
        $html .= ($this->type == 'image') ? ' source="'.$this->source.'"' : NULL;
        $html .= ($this->type == 'image' && (bool)$this->width && (bool)$this->height) ? '  style="width:'.$this->width.'px; height:'.$this->height.'px;"' : NULL;
        $html .= (bool)$this->checked ? ' checked' : NULL;
        $html .= ' />' . CR;

        return $html;
    }
}
?>