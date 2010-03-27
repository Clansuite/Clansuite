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
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') == false){ die('Clansuite not loaded. Direct Access forbidden.');}

/**
 * Clansuite Core Class for Formular Handling
 *
 * The Formular Object provides methods to deal with the following problem:
 *
 * Normally you would define your form on html side. When the form gets submitted
 * you would perform a server-side validation on the incomming formdata against
 * certain validation rules. If your system is one of the better ones, you would
 * add these validations also on client-side as an useability enhancement.
 *
 * Problem:
 * This means that you have to implement the validation rules and validation methods two times.
 * One time via javascript on client-side, one time via php on server-side.
 *
 * Clansuite's formhandling abstracts the form generation and solves the problem described above.
 *
 * The formular handling process can be described as the following:
 *
 * 1) Formcreation
 *    The formular is defined/described only one-time in xml (Data-Dictionary).
 *
 *    The form-definition/description contains:
 *    a) Elements
 *    b) Attributes
 *    c) Validation rules
 *
 * 2) Transformation / Generation
 *    The formular definition is then transformed into a valid html/xhtml/xml document segment
 *    with client-side validation rules and methods applied.
 *
 *    The form contains:
 *    a) Formular
 *    b) Client-side formular validation rules
 *    c) Client-side formular validation methods
 *
 * 3) The generated form is ready for getting embedded into the template/document providing the formular.
 *
 * Form Workflow
 *
 *    a) Embed formular
 *       -> Perform client-side validation while data is collected from user
 *       -> If validation is ok:
 *    b) Submit
 *       -> Perform server-side validation on incomming data
 *       -> If validation is ok:
 *          -> Save Data !
 *       -> Else
 *    c) Repopulate formfields on submission error
 *       -> goto a)
 *
 * @link http://www.whatwg.org/specs/web-apps/current-work/multipage/forms.html
 *
 * @author     Jens-André Koch   <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005-onwards)
 * @version    0.1
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Form
 */

class Clansuite_Form /*extends Clansuite_HTML*/ implements Clansuite_Form_Interface, ArrayAccess, Countable, Iterator
{
    /**
     * Contains all formelements / formobjects registered for this form.
     *
     * @var array
     */
    protected $formelements = array();

    /**
     * Contains action of the form.
     *
     * @var string
     */
    protected $action;

    /**
     * Contains action of the form.
     *
     * @var string
     */
    protected $method;

    /**
     * Contains action of the form.
     *
     * @var string
     */
    protected $name;

    /**
     * Contains id of the form.
     *
     * @var string
     */
    protected $id;

    /**
     * Contains class of the form.
     *
     * @var string
     */
    protected $class;

    /**
     * Contains encoding of the form.
     *
     * @var string
     */
    protected $encoding;

    /**
     * Contains description of the form.
     *
     * @var string
     */
    protected $description;

    /**
     * Contains heading of the form.
     *
     * @var string
     */
    protected $heading;

    /**
     * Contains alternative target of the form.
     *
     * @var string
     */
    protected $target;

    /**
     * Flag variable to indicate, if form has an error.
     *
     * @var boolean
     */
    protected $formerror_flag = false;

    /**
     * Form Decorators Array, contains one or several formdecorator objects
     *
     * @var array
     */
    private $formdecorators = array();

    /**
     * Form Groups Array, contains one or several formgroup objects
     *
     * @var array
     */
    protected $formgroups = array();

    /**
     * Construct
     *
     * @param string $name Set the name of the form.
     * @param string $name Set the method of the form. Valid are get/post.
     * @param string $name Set the action of the form.
     *
     */
    public function __construct($name, $method, $action)
    {
         $this->setName($name);
         $this->setMethod($method);
         $this->setAction($action);
    }

    /**
     * Sets the method to the form.
     *
     * @param string $method
     * @return Clansuite_Form
     */
    public function setMethod($method)
    {
        $method = strtolower($method);

        if($method == "post" or $method == "get")
        {
            $this->method = $method;
        }
        else
        {
            throw new Clansuite_Exception('The parameter "$method" of the form has to be GET or POST.');
        }

        return $this;
    }

    /**
     * Returns method of this form.
     *
     * @return string Name of the method of this form.
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set action of this form.
     *
     * @param $action string Name of the action of this form.
     * @return Clansuite_Form
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Returns action of this form.
     *
     * @return string Name of the action of this form.
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set id of this form.
     *
     * @param string $id ID of this form.
     * @return Clansuite_Form
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
     * Set name of this form.
     *
     * @param string $name Name of this form.
     * @return Clansuite_Form
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
     * Set class of this form.
     *
     * @param string $class Name of this form.
     * @return Clansuite_Form
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
     * Set class of this form.
     *
     * @param string $description Name of this form.
     * @return Clansuite_Form
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Returns class of this form.
     *
     * @return string Name of this form.
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set heading of this form.
     *
     * @param string $heading Name of this form.
     * @return Clansuite_Form
     */
    public function setHeading($heading)
    {
        $this->heading = $heading;

        return $this;
    }

    /**
     * Returns heading of this form.
     *
     * @return string Name of this form.
     */
    public function getHeading()
    {
        return $this->heading;
    }

    /**
     * Set encoding type of this form.
     *
     * @param string $encoding Encoding type of this form.
     * @return Clansuite_Form
     */
    public function setEncoding($encoding)
    {
        if( empty($encoding) )
        {
            $this->encoding = $encoding;
        }

        return $this;
    }

    /**
     * Returns encoding type of this form.
     *
     * @return string Encoding type of this form.
     */
    public function getEncoding()
    {
        if( empty($this->encoding) )
        {
            $this->encoding = 'multipart/form-data';

            return $this->encoding;
        }
        else
        {
            return $this->encoding;
        }
    }

    /**
     * Getter for formelements array
     *
     * @return array Formelements
     */
    public function getFormelements()
    {
        return $this->formelements;
    }

    /**
     * Set formelements
     *
     * @param array $formelements
     */
    public function setFormelements(array $formelements)
    {
        $this->formelements = $formelements;
    }

    /**
     * Set target of this form.
     *
     * @param string $target ID of this form.
     * @return Clansuite_Form
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Returns target of this form.
     *
     * @return string target of this form.
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Set a button element to the buttons stack of the form.
     *
     * @param mixed Button (string) or Buttons (array)
     */
    public function setButton($button)
    {
        if (is_array($button) === false)
        {
            $submit = array($button);
        }

        $this->buttons = array_merge($this->buttons, $button);

        return $this;
    }

    /**
     * Get the buttons stack.
     *
     * @return array Buttons
     */
    public function getButtons()
    {
        return $this->buttons;
    }

    /**
     * ===================================================================================
     *      Formerrors
     * ===================================================================================
     */

    /**
     * Get the form error status.
     *
     * @return boolean
     */
    public function formHasErrors()
    {
        return $this->formerror_flag;
    }

    /**
    * Sets the default positioning
    *
    * @param int $formelement_position
    */
    public function applyDefaultFormelementDecorators($formelement_position)
    {
        $this->setFormelementDecorator('label', $formelement_position);
        $this->setFormelementDecorator('description', $formelement_position);
        $this->setFormelementDecorator('div', $formelement_position)->setClass('formline');
    }

    /**
    * Render all elements
    *
    * @return Clansuite_Formelement
    */
    public function renderAllFormelements()
    {
        # init var
        $html_form = '';
        $html_formelement = '';

        # fetch all formelements
        $formelements = $this->getFormelements();

        #clansuite_xdebug::printR($formelements);

        # sort formelements by index
        ksort($formelements);
        $formelement_position = 0;

        # loop over all registered formelements of this form and render them
        foreach( $formelements as $formelement )
        {
            # fetch all decorators of this formelement
            $formelementdecorators = $formelement->getDecorators();

            if(empty($formelementdecorators))
            {
                # set
                $this->applyDefaultFormelementDecorators($formelement_position);

                # fetch again all decorators of this formelement
                $formelementdecorators = $formelement->getDecorators();
            }

            /*if($formelement_position == 1)
            {
                clansuite_xdebug::printR($formelements);
            }*/

            #clansuite_xdebug::printR($formelement);

            # then render this formelement (pure)
            $html_formelement = $formelement->render();

            # for each decorator, decorate the formelement and render it
            foreach ($formelementdecorators as $formelementdecorator)
            {
                $formelementdecorator->decorateWith($formelement);
                $html_formelement = $formelementdecorator->render($html_formelement);
            }

            # increase formelement position
            $formelement_position++;

            # append the form html with the decorated formelement html
            $html_form .= $html_formelement;
        }

        #clansuite_xdebug::printR($html_form);
        return $html_form;
    }

    /**
    * Set default form decorators (form)
    *
    */
    public function applyDefaultFormDecorators()
    {
        $this->addDecorator('form');
    }

    /**
    * Render this form
    *
    * @return Clansuite_Formelement
    */
    public function render()
    {
        $this->applyDefaultFormDecorators();

        $html_form = $this->renderAllFormelements();

        foreach ($this->getDecorators() as $decorator)
        {
            $decorator->decorateWith($this);
            $html_form = $decorator->render($html_form);
        }
        return $html_form;
    }

    /**
     * Returns a XHTML string representation of the form
     *
     * @see Clansuite_Form::render()
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * ===================================================================================
     *      Formelement Handling (add, del, getByPos, getByName)
     * ===================================================================================
     */

    /**
     * Adds a formelement to the form
     *
     * @param $formelement Clansuite_Formelement Object implementing the Clansuite_Form_Interface
     * @param $position integer The position number of this formelement (ordering).
     * @return Clansuite_Form $this Form Object
     */
    public function addElement($formelement, $position = null)
    {
        /**
         * We procced, if parameter $formelement is an fromelement object implementing the Clansuite_Formelement_Interface.
         * Else it's a string with the name of the formelement, which we pass to the factory to deliver that formelement object.
         *
         * Note: Checking for the interface is nessescary here, because checking for string, like if($formelement == string),
         * would result in true as formelement objects provide the __toString method.
         */
        if( ($formelement instanceof Clansuite_Formelement_Interface) == false )
        {
            $formelement = $this->formelementFactory($formelement);
        }

        # if we don't have a position to order the elements, we just add an element
        if($position == null)
        {
            $this->formelements[] = $formelement;
        }
        # else we position the element under it's number to keep things in an order
        elseif(is_int($position))
        {
            $this->formelements[$position] = $formelement;
        }

        # return object -> fluent interface / method chaining
        return $formelement;
    }

    /**
     * Removes a formelement by name
     *
     * @param string $name
     * @return bool
     */
    public function delElement($name)
    {
        foreach($this->formelements as $formelement)
        {
            if($name == $formelement->getName())
            {
                unset($this->formelement[$formelement]);
                return true;
            }
        }
        return false;
    }

    /**
     * Fetches a formelement via it's position number
     *
     * @param $position integer The position number the requested formelement (ordering).
     * @return Clansuite_Formelement $formelement Object implementing the Clansuite_Form_Interface
     */
    public function getElementByPosition($position)
    {
        if(is_int($position) and isset($this->formelements[$position]))
        {
            return $this->formelements[$position];
        }
        else
        {
            throw new Clansuite_Exception('There is no Formelement registered under this position number');
        }
    }

    /**
     * Fetches a formelement via it's name
     *
     * @param $name string The name of the requested formelement.
     * @return Clansuite_Formelement $formelement Object
     */
    public function getElementByName($name)
    {
        foreach($this->formelements as $formelement)
        {
            if($name == $formelement->getName())
            {
                return $formelement;
            }
        }
        return null;
    }

    /**
     * ===================================================================================
     *      Formelement Factory
     * ===================================================================================
     */

    /**
     * Factory method. Instantiates and returns a new formelement object.
     * For a list of all available formelements visit the "/formelements" directory.
     *
     * @return Clansuite_Formelement object
     */
    public static function formelementFactory($formelement)
    {
        # if not already loaded, require forelement file
        if (!class_exists('Clansuite_Formelement_'.$formelement,false))
        {
            if(is_file(ROOT_CORE . 'viewhelper/formelements/'.$formelement.'.form.php'))
            {
                require ROOT_CORE . 'viewhelper/formelements/'.$formelement.'.form.php';
            }
        }

        # construct Clansuite_Formelement_Name
        $formelement_classname = 'Clansuite_Formelement_'.ucfirst($formelement);
        # instantiate the new formelement
        $formelement = new $formelement_classname;

        return $formelement;
    }

    /**
     * ===================================================================================
     *      Form Processing
     * ===================================================================================
     */

    /**
     * processForm
     *
     * This is the main formular processing loop.
     * If the form doesn't validate, redisplay it, else present "Success"-Message!
     */
    public function processForm()
    {
        # check if form has been submitted properly
        if ($this->validateForm() == false)
        {
            # if not, redisplay the form (decorate with errors + render)
            $form->errors();

        }
        else # form was properly filled, display a success web page
        {
            # success!!
            $form->success();
        }
    }

    /**
     * ===================================================================================
     *      Form Decoration
     * ===================================================================================
     */

    /**
     * setFormelementDecorator
     *
     * Is a shortcut/proxy/convenience method for addFormelementDecorator()
     * @see $this->addFormelementDecorator()
     *
     * WATCH IT! THIS BREAKS THE CHAINING IN REGARD TO THE FORM
     * @return Clansuite_Formdecorator object
     */
    public function setFormelementDecorator($decorator, $formelement_position = null)
    {
        return $this->addFormelementDecorator($decorator, $formelement_position);
    }

    /**
     * addFormelementDecorator
     *
     * Adds a decorator to the formelement
     *
     * Usage:
     * $form->addFormelementDecorator('fieldset')->setLegend('legendname');
     *
     * WATCH IT! THIS BREAKS THE CHAINING IN REGARD TO THE FORM
     * @return Clansuite_Formdecorator object
     */
    public function addFormelementDecorator($decorator, $formelement_position = null)
    {
        # if no position is incomming we return the last formelement item
        # this would be the normal call to this method, when manually chaining
        if(is_null($formelement_position))
        {
            # count formelements, -1 because starting with 0 not with 1
            $position = count($this->formelements)-1;
        }

        # get last formelement object in formelements array
        $last_formelement_object = $this->getElementByPosition($formelement_position);

        # and add the decorator
        # WATCH IT! this is a call to formelement.core.php addDecorator()
        return $last_formelement_object->addDecorator($decorator);
    }

    /**
     * setDecorator
     *
     * Is a shortcut/proxy/convenience method for addDecorator()
     * @see $this->addDecorator()
     *
     * WATCH IT! THIS BREAKS THE CHAINING IN REGARD TO THE FORM
     * @return Clansuite_Formdecorator object
     */
    public function setDecorator($decorators)
    {
        return $this->addDecorator($decorators);
    }

    /**
     * addDecorator
     *
     * Adds a decorator to the form
     *
     * Usage:
     * $form->addDecorator('fieldset')->setLegend('legendname');
     *
     * WATCH IT! THIS BREAKS THE CHAINING IN REGARD TO THE FORM
     * @return Clansuite_Formdecorator object
     */
    public function addDecorator($decorators)
    {
        # Debug of incomming decorators
        #clansuite_xdebug::printR($decorators);

        # check if multiple decorators are incomming at once
        if(is_array($decorators))
        {
            # address each one of those decorators
            foreach($decorators as $decorator)
            {
                # and check if it is an object implementing the right interface
                if ( $decorator instanceof Clansuite_Form_Decorator_Interface )
                {
                    # if so, fetch this decorator objects name
                    $decoratorname = $decorator->name;
                }
                else
                {
                    # turn it into an decorator object
                    $decorator = $this->decoratorFactory($decorator);
                    $decoratorname = $decorator->name;
                    #$this->addDecorator();
                }
            }
        }

        # if we got a string (ignore the plural, it's a one element string, like 'fieldset')
        if (is_string($decorators))
        {
            # turn it into an decorator object
            $decorator = $this->decoratorFactory($decorators);
            $decoratorname = $decorator->name;
            #clansuite_xdebug::printR($decorator);
        }

        # now check if this decorator is not already set (prevent decorator duplications)
        if(in_array($decorator, $this->formdecorators) == false)
        {
            # set this decorator object under its name into the array
            $this->formdecorators[$decoratorname] = $decorator;
        }
        else
        {
            $this->formdecorators[$decoratorname] = $decorator;
        }

        # WATCH IT! THIS BREAKS THE CHAINING IN REGARD TO THE FORM
        # We dont return $this here, because $this would be the FORM.
        # Insted the decorator is returned, to apply some properties.
        # @return decorator object
        return $this->formdecorators[$decoratorname];
    }

    /**
     * Getter Method for the formdecorators
     *
     * @return array with registered formdecorators
     */
    public function getDecorators()
    {
        return $this->formdecorators;
    }

    /**
     * Factory method. Instantiates and returns a new formdecorator object.
     *
     * @return Clansuite_Formdecorator
     */
    public function decoratorFactory($formdecorator)
    {
        # if not already loaded, require forelement file
        if (!class_exists('Clansuite_Form_Decorator_'.$formdecorator,false))
        {
            if(is_file(ROOT_CORE . 'viewhelper/formdecorators/form/'.$formdecorator.'.form.php'))
            {
                require ROOT_CORE . 'viewhelper/formdecorators/form/'.$formdecorator.'.form.php';
            }
        }

        # construct Clansuite_Formdecorator_Name
        $formdecorator_classname = 'Clansuite_Form_Decorator_'.ucfirst($formdecorator);
        # instantiate the new $formdecorator
        $formdecorator = new $formdecorator_classname();

        return $formdecorator;
    }

    /**
     * ===================================================================================
     *      Form Groups
     * ===================================================================================
     */

    /**
     * Adds a new group to the form, to group one or several formelements inside.
     *
     * @return Clansuite_Form
     */
    public function addGroup($groupname)
    {
        # @todo groupname becomes ID of decorator (e.g. a fieldset)

        $this->formgroups[] = $groupname;

        return $this;
    }

    /**
     * ===================================================================================
     *      Form Validation
     * ===================================================================================
     */

    /**
     * Adds a validator to the formelement
     *
     * @return Clansuite_Form
     */
    public function addValidator()
    {

        return $this;
    }

    /**
     * Server-side validation the form.
     *
     * The method iterates (loops over) all formelement objects and calls the validation on each object.
     *
     * @return boolean Returns true if form validates, false if validation fails, because errors exist.
     */
    public function validateForm()
    {
        foreach($this->formelements as $formelement)
        {
            if($formelement->validate($this) == false)
            {
                # raise error flag
                $this->formerror_flag = true;

                $formelement->getError();
            }
        }

        if($this->formHasErrors() == true)
        {
            # form has errors and does not validate
            return false;
        }
    }

    /**
     * ===================================================================================
     *      SPL Implementation
     *      ArrayObject implements ArrayAccess, Countable, Iterator
     * ===================================================================================
     */

    /**
     * Appends the value
     * Implementation of SPL ArrayObject::append()
     *
     * @param $value mixed
     */
    public function append($value)
    {
    }

    /**
     * Get the number of elements in the Iterator
     * Implementation of SPL Countable::count()
     *
     * @return integer Returns the number of formelements/objects registered to this form object.
     */
    public function count()
    {
        return count($formelements);
    }

    /**
     * Create a new iterator from an ArrayObject instance
     * Implementation of SPL Iterator::current
     */
    public function current()
    {
    }

    /**
     * Create a new iterator from an ArrayObject instance
     * Implementation of SPL Iterator::key
     */
    public function key()
    {
    }

    /**
     * Create a new iterator from an ArrayObject instance
     * Implementation of SPL Iterator::next
     */
    public function next()
    {
    }

    /**
     * Create a new iterator from an ArrayObject instance
     * Implementation of SPL Iterator::rewind
     */
    public function rewind()
    {
    }

    /**
     * Create a new iterator from an ArrayObject instance
     * Implementation of SPL Iterator::valid
     */
    public function valid()
    {
    }

    /**
     * Returns whether the requested $index exists
     * Implementation of SPL ArrayAccess::offsetExists()
     */
    public function offsetExists($index)
    {
    }

    /**
     * Returns the value at the specfied $index
     * Implementation of SPL ArrayAccess::offsetGet()
     */
    public function offsetGet($index)
    {
    }

    /**
     * Sets the value at the specified $index
     * Implementation of SPL ArrayAccess::offsetSet()
     *
     * @param $index
     * @param $value
     */
    public function offsetSet($index, $value)
    {
    }

    /**
     * Unsets the value at the specified $index
     * Implementation of SPL ArrayAccess::offsetUnset()
     */
    public function offsetUnset($index)
    {
    }

    /**
     * ============================
     *    Magic Methods: get/set
     * ============================
     */

    /**
     * Magic Method: set
     * $this via ArrayObject
     *
     * @param $name Name of the attribute to set to the form.
     * @param $value The value of the attribute.
     */
    public function __set($name, $value)
    {
        $this[$name] = $val;
    }

    /**
     * Magic Method: get
     * $this via ArrayObject
     *
     * @param $name
     */
    public function __get($name)
    {
        return $this[$name];
    }
}

/**
 * Interface for Clansuite_Form
 */
interface Clansuite_Form_Interface
{
    # output the html representation of the form
    public function render();

    # set action, method, name
    public function setAction($action);
    public function setMethod($method);
    public function setName($method);

    # add/remove a formelement
    public function addElement($formelement, $position = null);
    public function delElement($name);

    # load/save the XML description of the form
    #public function loadDescriptionXML($xmlfile);
    #public function saveDescriptionXML($xmlfile);

    # shortcut method / factory method for accessing the formelements
    public static function formelementFactory($formelement);

    # callback for validation on the whole form (all formelements)
    #public function processForm();
}
?>