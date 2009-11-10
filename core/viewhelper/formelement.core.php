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
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.');}


/**
 * Clansuite_Formelement
 *
 * @author     Jens-André Koch   <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005-onwards)
 * @version    0.1
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Form
 */

class Clansuite_Formelement implements Clansuite_Formelement_Interface
{
    protected $name, $id, $type, $class, $size, $disabled, $maxlength, $style;

    protected $label;

    protected $value;

    protected $position;

    /**
     * Set id of this form.
     *
     * @param $id string ID of this form.
     */
    public function setID($id)
    {
        $this->id = $id;

         return $this;
    }

    /**
     * Returns action of this form.
     *
     * @return string ID of this form.
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set type of this form.
     *
     * @param $id string Type of this form.
     */
    public function setType($type)
    {
        $this->type = $type;

         return $this;
    }

    /**
     * Returns type of this form.
     *
     * @return string TYPE of this form.
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set name of this form.
     *
     * @param $name string Name of this form.
     */
    public function setName($name)
    {
        $this->name = $name;

         return $this;
    }

    /**
     * Returns name of this form.
     *
     * @return string Name of this form.
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Returns name of this form without brackets.
     *
     * @return string Name of this form.
     */
    public function getNameWithoutBrackets()
    {
        $name = strrpos($this->name, "[");
        if ($name === false)
        { 
            return $this->name;
        }
        else # remove brackets
        {
           $name = $this->name;           
           # replace left
           $name = str_replace('[', '_', $name);           
           # replace right with nothing (strip right)
           $name = str_replace(']', '', $name); 
        }
                
        return $name;
    }

    /**
     * Set class of this form.
     *
     * @param $action string Name of this form.
     */
    public function setClass($class)
    {
        $this->class = $class;

         return $this;
    }

    /**
     * Returns class of this form.
     *
     * @return string Name of this form.
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * sets value for this element
     *
     * @return boid
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    public function getValue()
    {
        return htmlspecialchars($this->value);
    }

    /**
     * Disables this formelement.
     */
    public function disable()
    {
        $this->disabled = true;

        return $this;
    }

    /**
     * Enables this formelement.
     */
    public function enable()
    {
        $this->disabled = false;

        return $this;
    }

    /**
     * Set label of this formelement.
     *
     * @param $label of this formelement.
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Returns label of this formelement.
     *
     * @return string Label of this formelement.
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Returns boolean true if a label exists for this formelement.
     *
     * @return boolean True if label exists.
     */
    public function hasLabel()
    {
        if(isset($this->label))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Set description of this formelement.
     *
     * @param $description Description of this formelement.
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Returns description of this formelement.
     *
     * @return string Description of this formelement.
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * override
     */
    public function render()
    {

    }
}

/**
 * Interface for a single Clansuite Form Element
 */
interface Clansuite_Formelement_Interface
{
    # add/remove attributes for a formelement
    #public function setAttribute($attribute, $value);
    #public function getAttribute($attribute);

    # initializes the attributes of the formelement
    #public function initialize();

    # renders the output of the formobject as html
    public function render();

    # sets a validation rule to a form element
    #public function addValidation();
}
?>