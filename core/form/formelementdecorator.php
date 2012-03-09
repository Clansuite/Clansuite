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

# Security Handler
if (defined('IN_CS') === false)
{
    die('Koch Framework not loaded. Direct Access forbidden.');
}

namespace Koch\Formelement;

interface Decorator
{
    public function decorateWith(Formelement $form_decorator);
    public function getName();
}

/**
 * Koch_Formelement_Decorator
 *
 * @author     Jens-André Koch  <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005-onwards)
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Form
 */

abstract class Decorator implements Decorator
{
    # instance of formelement, which is to decorate
    protected $formelement;

    private $name, $class;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
    * Set class=""
    *
    * @param string $classname
    */
    public function setClass($classname)
    {
        $this->class = $classname;

        return $this->formelement;
    }

    /**
    * Get class="" values
    *
    * @return string
    */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Constructor
     *
     * @param $form Accepts a Koch_Form Object implementing the Koch_Form_Interface.
     */
    /*public function __construct(Koch_Form_Interface $form)
    {
        $this->decorate($form);
    }*/

    /**
     * Setter method to set the object which is to decorate.
     *
     * @param $form object of type Koch_Form_Interface or Koch_Form_Decorator_Interface
     */
    public function decorateWith($formelement)
    {
        $this->formelement = $formelement;
    }

    /**
     * Purpose of this method is to check, if this object or a decorator implements a certain method.
     *
     * @param $method
     * @return boolean
     */
    public function hasMethod($method)
    {
        # check if method exists in this object
        if(method_exists($this, $method))
        {
            return true;
        }
        # check if method exists in the decorator of this object
        elseif($this->formelement instanceof Koch_Formelement_Decorator)
        {
            return $this->formelement->hasMethod($method);
        }
        else # nope, method does not exist
        {
            return false;
        }
    }

    /**
     * __call Magic Method
     *
     * In general this calls a certain method with parameters on the object which is to decorate ($form).
     *
     * @param $method
     * @param $parameters
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array(array($this->formelement, $method), $parameters);
    }
}
?>