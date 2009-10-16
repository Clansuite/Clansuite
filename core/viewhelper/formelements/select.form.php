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

    # string
    protected $description ='Select an item from this pull-down menu.';

    public function __construct()
    {
        $this->type = 'select';
    }
    
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

    public function render()
    {
        $html = '';
        $html .= '<select name='.$this->name.' class="'.$this->class.'">';

        foreach ($this->options as $key => $value)
        {
            if ($key == $this->default)
            {
                $html .= '<option value='.$key.' selected >'.$value.'</option>';
            }
            else
            {
                $html .= '<option value='.$key.'>'.$value.'</option>';
            }
        }

        $html .= '</select>';
        
        return $html;
    }
    
    public function __toString()
    {
        return $this->render();   
    }
}
?>