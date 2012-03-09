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

namespace Koch\Form;

interface Decorator
{
    public function decorateWith(Form $form);
    public function getName();
    public function render($html_form_content);
}

/**
 * Koch_Form_Decorator
 *
 * The base class for all decorators. It has the same type as Koch_Form itself.
 * The decorator pattern suggests that the decorator implements all public methods of the component which it decorates.
 * Being "of the same type as Koch_Form" is achieved by implementing all methods described by Koch_Form_Interface,
 * NOT by extending Koch_Form. "Implementing all methods" is achieved by using the magical __call() method.
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
 * Effect is that all children of this base class have all the methods of Koch_Form.
 *
 * @pattern Decorator, [GoF, 216/220]
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
    /**
     * Instance of the form, which is to decorate.
     *
     * @var Koch_Form Defaults to null.
     */
    protected $form = null;

    private $name, $class, $id;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
    * Set css class
    *
    * @param string $classname
    */
    public function setClass($classname)
    {
        $this->class = $classname;

        return $this->form;
    }

    /**
    * Get css class
    *
    * @return string
    */
    public function getClass()
    {
        return $this->class;
    }

    /**
    * Set html id attribute
    *
    * @param string $id
    */
    public function setId($id)
    {
        $this->id = $id;

        return $this->form;
    }

    /**
    * Get html id attribute value
    *
    * @return string
    */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Setter method to set the form object which is to decorate.
     *
     * @param $form object of type Koch_Form_Interface or Koch_Form_Decorator_Interface
     */
    public function decorateWith(Koch_Form_Interface $form)
    {
        if (null === $form)
        {
            throw InvalidArgumentException('Form is null!');
        }

        $this->form = $form;
    }

    /**
     * Form setter.
     *
     * @param Form $form
     */
    public function setForm(Koch_Form $form)
    {
        $this->decorateWith($form);
    }

    /**
     * Form Getter
     *
     * @return object Koch_Form
     */
    public function getForm()
    {
        return $this->form;
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
        elseif($this->form instanceof Koch_Form_Decorator)
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
?>