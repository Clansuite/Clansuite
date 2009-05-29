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
    * @version    SVN: $Id: trail.core.php 2870 2009-03-25 23:21:42Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.');}

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
 *    The formular is defined/described only one-time in php and xml.
 *
 *    The form-definition/description contains:
 *    a) Elements
 *    b) Attributes
 *    c) Validation rules
 *    d) Filters
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
 *
 * @author     Jens-André Koch   <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005-onwards)
 * @since      Class available since Release 0.2
 * @version    0.1
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Form
 */

class Clansuite_Form extends Clansuite_HTML implements Clansuite_Form_Interface, ArrayAccess, Countable, Iterator
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
     * Construct
     */
    public function __construct($name, $method, $action)
    {
         $this->setName($name);

         $method = strtolower($method);
         if($method == "post" or $method == "get")
         {
             $this->setMethod($method);
         }
         else
         {
            throw new Clansuite_Exception('When instantiating the form object the second parameter has to be GET or POST.');
         }

          $this->setAction($action);
    }

    /**
     * Sets the method to the form.
     *
     * @param $method string
     */
    public function setMethod($method)
    {
        $this->method = $method;
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
     */
    public function setAction($action)
    {
        $this->action = $action;
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
     * @param $id string ID of this form.
     */
    public function setID($id)
    {
        $this->id = $id;
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
     * @param $action string Name of this form.
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * processForm
     *
     * This is the main formular processing loop.
     * If the form doesn't validate, redisplay it, else present "Success"-Message!
     */
    public function processForm()
    {
        # not needed, because its all in one file for dev-purposes
        # clansuite_loader::loadCoreClass('clansuite_form_validation');

        # check if form has been submitted properly
        if ($this->validation->ok() == false)
        {
            # if not, redisplay the form

        }
        else # form was properly filled, display a success web page
        {

        }

        # check if there are any errors set
        if ($validate->isError())
        {
            $errors = Clansuite_Form_Validation::getErrors();
            foreach ($errors as $error)
            {
              // let Clansuite know about the error
              form_set_error($error['field'], t($error['msg']));
            }
        }
    }

    /**
     * Renders the Form
     *
     * @return $html_form string HTML Code of the Form.
     */
    public function render()
    {
        # init html form
        $html_form = '' . CR;

        # open form
        $html_form = '<form id="'.$this->getID().'" action="'.$this->getAction().'" method="'.$this->getMethod().'" name="'.$this->getName().'">' . CR;

        /**
         * sort formelements by index
         * loop over all registered formelements of this form
         * and render them
         */
        ksort($this->formelements);
        foreach( $this->formelements as $formelement )
        {
            $htmlform .= CR . $formelement->render() . CR;
        }

        # add buttons

        # close form
        $html_form = '</form>' . CR;

        return $html_form;
    }

    public function __toString()
    {
        return $this->render();
    }

    /**
     * Adds a formelement to the form
     *
     * @param $formelement Clansuite_Form_Element Object implementing the Clansuite_Form_Interface
     * @param $position integer The position number of this formelement (ordering).
     * @return $this Form Object
     */
    public function addElement(Clansuite_Form_Element_Interface $formelement, $position = null)
    {
        # if we don't have a position to order the elements, we just add an element
        if($position === null)
        {
          $this->formelements[] = $formelement;
        }
        # else we position the element under it's number to keep things in an order
        elseif(is_int($position))
        {
          $this->formelements[$position] = $formelement;
        }
        return $this;
    }

    /**
     * Fetches a formelement via it's position number
     *
     * @param $position integer The position number the requested formelement (ordering).
     * @return $formelement Clansuite_Form_Element Object implementing the Clansuite_Form_Interface
     */
    public function getElementByPosition($position)
    {
        if(is_int($position) and isset($this->elements[$position]))
        {
            return $this->elements[$position];
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
     * @return $formelement Clansuite_Form_Element Object
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
     * Removes a formelement from the form
     */
    public function delElement()
    {
    }

    public function getAttribute()
    {
    }

    public function setAttribute()
    {
    }

    public function addFilter()
    {
    }

    public function delFilter()
    {
    }

    /**
     * Load the XML form description file
     */
    public function loadDescriptionXML()
    {

    }

    /**
     * Save the form to a XML description file
     */
    public function saveDescriptionXML()
    {

    }

    public static function factory()
    {

    }

    /**
     * ===================================================================================
     *    SPL Implementation
     *    ArrayObject implements IteratorAggregate, Traversable, ArrayAccess, Countable
     * ===================================================================================
     */

    /**
     * Appends the value
     * Implementation of SPL ArrayObject::append()
     *
     * @param $value mixed
     * @return void
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
     *
     * @return void
     */
    public function current()
    {
    }

    /**
     * Create a new iterator from an ArrayObject instance
     * Implementation of SPL Iterator::key
     *
     * @return void
     */
    public function key()
    {
    }

    /**
     * Create a new iterator from an ArrayObject instance
     * Implementation of SPL Iterator::next
     *
     * @return void
     */
    public function next()
    {
    }

    /**
     * Create a new iterator from an ArrayObject instance
     * Implementation of SPL Iterator::rewind
     *
     * @return void
     */
    public function rewind()
    {
    }

    /**
     * Create a new iterator from an ArrayObject instance
     * Implementation of SPL Iterator::valid
     *
     * @return void
     */
    public function valid()
    {
    }

    /**
     * Returns whether the requested $index exists
     * Implementation of SPL ArrayAccess::offsetExists()
     *
     * @return void
     */
    public function offsetExists($index)
    {
    }

    /**
     * Returns the value at the specfied $index
     * Implementation of SPL ArrayAccess::offsetGet()
     *
     * @return void
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
     * @return void
     */
    public function offsetSet($index, $value)
    {
    }

    /**
     * Unsets the value at the specified $index
     * Implementation of SPL ArrayAccess::offsetUnset()
     *
     * @return void
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
 * Clansuite Form Factory
 */
class Clansuite_Form_Factory implements Clansuite_Form_Factory_Interface
{
    public function factory()
    {

    }
}

/**
 * Clansuite Form Validation
 */
class Clansuite_Form_Validation implements Clansuite_Form_Validation_Interface
{
    /**
     * Error Flag: Are there any errors occured while validating?
     *
     * @var boolean
     */
    public $isError = false;

    /**
     * Validity Flag: Is this Form Element valid?
     *
     * @var boolean
     */
    public $isValid = false;

    /**
     * Validation Rules Array
     *
     * @var array
     */
    public $rules = array();

    /**
     * Validation Errors Array
     */
    public $errors = array();

    /**
     * Setter for "isError" Flag-Variable
     */
    public function setError()
    {
        # set error flag
        $this->isError = true;

        # @todo set error array
    }

    /**
     * Getter for Validation Errors Array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Getter for "isError" Flag-Variable
     *
     * Example Usage;:
     * if(Clansuite_Form_Validation->isError() == false) { }
     *
     * @return boolean
     */
    public function isError()
    {
        if($this->isError == true)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * This is a shortcut to isError
     *
     * Example Usage;:
     * if(Clansuite_Form_Validation->ok() == false) { }
     *
     * @return boolean
     */
    public function ok()
    {
        return $this->isError();
    }

    /**
     * setRules
     *
     * Example Usage (in Module Action):
     *
     * // set validation rules
     * $rules['firstname']  = "required";
     * $rules['email']      = "required,email";
     *
     * $this->validation->setRules($rules);
     */
    public function setRules(array $rules_array)
    {
        $this->rules = $rules_array;
    }

    /**
     * Getter for Validation Rules
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Getter for "isValid" Flag-Variable.
     * Checks if the submitted form data is valid.
     *
     * @todo abstract $_SERVER $_POST $_GET
     * @return true if the data is valid, otherwise false
     */
    public function isValid()
    {
        if (!isset($this->isValid))
        {
            if ('POST' == $_SERVER['REQUEST_METHOD'])
            {
                list($this->isValid, $_POST) = $this->validateForm($_POST);
            }
            elseif('GET' == $_SERVER['REQUEST_METHOD'])
            {
                list($this->isValid, $_GET) = $this->validateForm($_GET);
            }
        }
        return $this->isValid;
    }

    /**
     * valdate is the main method of this class
     * the data for a formelement is validated against the validation rules.
     * in case the the data is not matching the rule, it's invalid and a validation error is set.
     */
    public function validateForm(Clansuite_Form__Interface $form)
    {

    }

    /**
     * valdate is the main method of this class
     * the data for a formelement is validated against the validation rules.
     * in case the the data is not matching the rule, it's invalid and a validation error is set.
     */
    public function validate(Clansuite_Form_Element_Interface $formelement)
    {

    }

    /**
     * @todo right location?
     */
    public function generateJavaScriptValidation(Clansuite_Form_Element_Interface $formelement)
    {

    }
}

/**
 * Clansuite Form Generator via Doctrine Records
 *
 * Purpose: automatic form generation from doctrine records/tables.
 *
 * @todo determine and set excluded columns (maybe in record?)
 */
class Clansuite_Doctrine_Formgenerator extends Clansuite_Form
{
    /**
     * The typeMap is an array of all doctrine column types.
     * It maps the database fieldtypes to their related html inputfield types.
     *
     * @var array
     */
    protected $typeMap = array(
                               'boolean'    => 'checkbox',
                               'integer'    => 'text',
                               'float'      => 'text',
                               'decimal'    => 'string',
                               'string'     => 'text',
                               'text'       => 'textarea',
                               'enum'       => 'select',
                               'array'      => null,
                               'object'     => null,
                               'blob'       => null,
                               'clob'       => null,
                               'time'       => 'text',
                               'timestamp'  => 'text',
                               'date'       => 'text',
                               'gzip'       => null
                               );

    /**
     * Database columns which should not appear in the form.
     *
     * @var array
     */
    protected $excludedColumns   = array();

    /**
     * Generate a Form from a Table
     *
     * @param string $DoctrineTableName Name of the Doctrine Tablename to build the form from.
     */
    public function generateFormByTable($DoctrineTableName)
    {
        # init form
        $form = array();

        # fetch doctrine table by record name
        $table = Doctrine::getTable($DoctrineTableName);

        # fetch all columns of that table
        $tableColumns = $table->getColumnNames();

        # loop over all columns
        foreach ( $tableColumns as $columnName) # => $columnType
        {
            # and check wheather the $columnName is to exclude
            if(in_array($columnName, $this->excludeColumns))
            {
                # stop the foreach-loop here and reenter it
                continue;
            }

            # combine classname and columnname as fieldname
            $fieldName = $table->getClassnameToReturn()."[$columnName]";

            # if columnname is identifier
            if( $table->isIdentifier($columnName) )
            {
                # add it as an hidden field
                $form[] = new Clansuite_Form_Factory( 'hidden', $fieldName);
            }
            else
            {
                # transform columnName to a printable name
                $printableName = ucwords(str_replace('_','',$columnName));

                # determine the columnname type and add the formfield
                $form[] = new Clansuite_Form_Factory( $table->getTypeOf($columnName), $fieldName, $printableName);
            }
        }

        return $form;
    }
}

/**
 * Clansuite_HTML
 */
class Clansuite_HTML extends DOMDocument
{

}

/**
 * Interface for the whole Clansuite_Form
 */
interface Clansuite_Form_Interface
{
    # output the whole form
    public function render();

    # set action and method
    public function setAction($action);
    public function setMethod($method);

    # add/remove a formelement
    public function addElement($formelement);
    public function delElement();

    # add/remove attributes for a formelement
    public function setAttribute();
    public function getAttribute();

    # add/remove filter
    public function addFilter();
    public function delFilter();

    # load/save the XML description of the form
    public function loadDescriptionXML();
    public function saveDescriptionXML();

    # shortcut method / factory method for accessing the formelements
    public static function formfactory();

    # callback for validation on the whole form (all formelements)
    public function processForm();
}

/**
 * Interface for a single Clansuite Form Element
 */
interface Clansuite_Form_Element_Interface
{
    # renders the output of the formobject as html
    public function render();

    # sets a validation rule to a form element
    public function addValidation();
}

/**
 * Interface for Clansuite Form Validation
 */
interface Clansuite_Form_Validation_Interface
{
    # set/get validation rules
    public function setRules(array $rules_array);
    public function getRules();

    # main method of this class
    public function validate(Clansuite_Form_Element_Interface $formelement);

    # set/get/is validation errors
    public function setError();
    public function getErrors();
    public function isError();
}

/**
 * Interface for Clansuite Form Elements (Factory)
 */
interface Clansuite_Form_Factory_Interface
{
    # factory method for formelements
    public function factory();
}
?>