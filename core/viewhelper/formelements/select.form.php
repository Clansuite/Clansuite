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
    * @version    SVN: $Id: layout.core.php 2870 2009-03-25 23:21:42Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.');}

if (!class_exists('Clansuite_Formelement')) { require ROOT_CORE.'viewhelper/formelement.core.php'; }

/**
 *
 *  Clansuite_Form
 *  |
 *  \- Clansuite_Formelement_Select
 */
class Clansuite_Formelement_Select extends Clansuite_Formelement implements Clansuite_Formelement_Interface
{
    /**
     * array with options for the dropdown
     *
     * @var array
     */
    protected $options;
    
    /**
     * default option of the select dropdown
     *
     * @var string
     */
    protected $default = '';
    
    protected $description;
    
    /**
     * number of displayed items
     * 0 = pure dropdown with 1 field
     * 3 = 3 elements shown, rest available via scrollbar
     */
    protected $size;

    # string
    #protected $label ='Select an item from this pull-down menu.';

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
    
   /* public function setLabel($label)
    {
        $this->label = $label;
        
        return $this;   
    }   */

    public function render()
    {
        # open the html select tag
        $html = '';
        $html .= '<select name="'.$this->name.'"';
        $html .= (bool)$this->class ? 'class="'.$this->class.'"' : null;
        $html .= (bool)$this->size ? 'size="'.$this->size.'"' : null;
        $html .= '>';
        
        /**
         * this handles the delaut value setting via the options array, parameter "selected"
         * it grabs the first element in the options array, which keyname should be 'selected'
         * and then removes it, setting its value to $this->default.
         *
         * note: if the options array is incomming via a formgenerator, the formgenerator has already performed this step
         * $this->setDefault(options['selected']);
         */
        if(isset($this->options['selected']))
        {
           $this->default = $this->options['selected'];
           unset($this->options['selected']);          
        }
                
        # loop over all selectfield options
        foreach ($this->options as $key => $value)
        {  
            /**
             * check if the value is the default one
             * in case it is, add html "selected" 
             */
            if ($value == $this->default)
            {  
                $html .= '<option value="'.$key.'" selected>'.$value.'</option>';
            }
            else # a normal select element is rendered
            {
                $html .= '<option value="'.$key.'">'.$value.'</option>';
            }
        }

        # close the html select tag
        $html .= '</select>';
        
        # add a description after the element
        $html .= (bool)$this->description ? $this->description : null;
        
        return $html;
    }
    
    public function __toString()
    {
        return $this->render();   
    }
}
?>
