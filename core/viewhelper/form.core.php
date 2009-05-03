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

class Clansuite_Form implements ArrayObject, Clansuite_Form_Interface
{
    # contains all formelements / formobjects registered for this form
    protected $elements = array();

    # contains action of the form
    protected $action;

    # contains action of the method
    protected $method;

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
     * Construct a new array object
     * Implementation of SPL ArrayObject::__construct()
     */
    public function __construct()
    {
    }

    /**
     * Get the number of elements in the Iterator
     * Implementation of SPL ArrayObject
     * ArrayObject implements Countable::count()
     *
     * @return integer Returns the number of formelements/objects registered to this form object.
     */
    public function count()
    {
        return count($formelements);
    }

    /**
     * Create a new iterator from an ArrayObject instance
     * Implementation of SPL ArrayObject
     *
     * @return void
     */
    public function getIterator()
    {
    }

    /**
     * Returns whether the requested $index exists
     * Implementation of SPL ArrayObject
     * ArrayObject implements ArrayAccess::offsetExists()
     *
     * @return void
     */
    public function offsetExits($index)
    {
    }

    /**
     * Returns the value at the specfied $index
     * Implementation of SPL ArrayObject
     * ArrayObject implements ArrayAccess::offsetGet()
     *
     * @return void
     */
    public function offsetGet($index)
    {
    }

    /**
     * Sets the value at the specified $index
     * Implementation of SPL ArrayObject
     * ArrayObject implements ArrayAccess::offsetSet()
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
     * Implementation of SPL ArrayObject
     * ArrayObject implements ArrayAccess::offsetUnset()
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
 * Automatic form generation from doctrine records/tables.
 *
 * @todo
 * determine and set excluded columns (maybe in record?)
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
                               'text'       => 'textarea'
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
                $printableName = ucwords(str_replace('_','',$columnName);

                # determine the columnname type and add the formfield
                $form[] = new Clansuite_Form_Factory( $table->getTypeOf($columnName), $fieldName, $printableName);
            }
        }

        return $form;
    }
}

/**
 * Interface for the whole Clansuite_Form
 */
interface Clansuite_Form_Interface
{
    # output the whole form
    public function render();

    # set action and method
    public function setAction();
    public function setMethod();

    # add/remove a formelement
    public function addElement();
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

    # shortcut method / factory method for accessing the validations
    public static function validationfactory();

    # callback for validation on all formelements
    public function validate();
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
    public function setRules()
    public function getRules()

    # main method of this class
    public function validate()

    # set/get/is validation errors
    public function setError();
    public function getErrors();
    public function isError();

    # factory method for validation rules
    public function factory();
}

/**
 * Interface for Clansuite Form Factory
 */
interface Clansuite_Form_Factory_Interface
{
    # factory method for formelements
    public function factory();
}
?>