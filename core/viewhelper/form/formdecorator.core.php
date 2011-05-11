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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005-onwards)
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
 * Clansuite_Form_Decorator
 *
 * The base class for all decorators. It has the same type as Clansuite_Form itself.
 * The decorator pattern suggests that the decorator implements all public methods of the component which it decorates.
 * Being "of the same type as Clansuite_Form" is achieved by implementing all methods described by Clansuite_Form_Interface,
 * NOT by extending Clansuite_Form. "Implementing all methods" is achieved by using the magical __call() method.
 *
 * @see __call
 *
 * So basically instead of:
 *
 * public function render()
 * {
 *    return $this->form->render()
 * }
 *
 * and implementing each method in this way, we simply use _call($method, $parameters).
 * Ok, it's a tradeoff between magic against implementation of the interface plus loosing the knowledege
 * in which decorator the method is called. If you still want to know, if a method exists on a decorator, use hasMethod().
 * Effect is that all children of this base class have all the methods of Clansuite_Form.
 *
 * @pattern Decorator, [GoF, 216/220]
 *
 * @author     Jens-Andr� Koch  <vain@clansuite.com>
 * @copyright  Jens-Andr� Koch (2005-onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Form
 */
abstract class Clansuite_Form_Decorator implements Clansuite_Form_Decorator_Interface
{
    # instance of form, which is to decorate
    protected $form;
    
    private $class, $id;

    /**
    * Set class=""
    *
    * @param string $classname
    */
    public function setClass($classname)
    {
        $this->class = $classname;

        return $this->form;
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
    * Set id=""
    *
    * @param string $id
    */
    public function setId($id)
    {
        $this->id = $id;

        return $this->form;
    }

    /**
    * Get id="" values
    *
    * @return string
    */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Constructor
     *
     * @param $form Accepts a Clansuite_Form Object implementing the Clansuite_Form_Interface.
     */
    /*public function __construct(Clansuite_Form_Interface $form)
    {
        $this->decorate($form);
    }*/

    /**
     * Setter method to set the object which is to decorate.
     *
     * @param $form object of type Clansuite_Form_Interface or Clansuite_Form_Decorator_Interface
     */
    public function decorateWith($form)
    {
        $this->form = $form;
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
        elseif($this->form instanceof Clansuite_Form_Decorator)
        {
            return $this->form->hasMethod($method);
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
        if(is_object($this->form) === true)
        {
            return call_user_func_array( array($this->form, $method), $parameters);
        }            
    }
}

/**
 * Clansuite_Formelement_Decorator
 *
 * @author     Jens-Andr� Koch  <vain@clansuite.com>
 * @copyright  Jens-Andr� Koch (2005-onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Form
 */

abstract class Clansuite_Formelement_Decorator implements Clansuite_Formelement_Decorator_Interface
{
    # instance of formelement, which is to decorate
    protected $formelement;

    private $class;

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
     * @param $form Accepts a Clansuite_Form Object implementing the Clansuite_Form_Interface.
     */
    /*public function __construct(Clansuite_Form_Interface $form)
    {
        $this->decorate($form);
    }*/

    /**
     * Setter method to set the object which is to decorate.
     *
     * @param $form object of type Clansuite_Form_Interface or Clansuite_Form_Decorator_Interface
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
        elseif($this->formelement instanceof Clansuite_Formelement_Decorator)
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

interface Clansuite_Formelement_Decorator_Interface
{
    public function decorateWith($formelement_decorator);
}

interface Clansuite_Form_Decorator_Interface
{
    public function decorateWith($form_decorator);
}
?>