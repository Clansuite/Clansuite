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

    protected $target;

    /**
     * Construct
     */
    public function __construct($name, $method, $action)
    {
         # set the name of the form
         $this->setName($name);

         # set method get/post
         $method = strtolower($method);

         if($method == "post" or $method == "get")
         {
             $this->setMethod($method);
         }
         else
         {
             throw new Clansuite_Exception('When instantiating the form object the second parameter has to be GET or POST.');
         }

         # set action
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
     * Set class of this form.
     *
     * @param $class string Name of this form.
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
     * @param $description string Name of this form.
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
     * @param $heading string Name of this form.
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
     * @param $action string Encoding type of this form.
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
     * Set target of this form.
     *
     * @param $target string ID of this form.
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
     */
    function setButton($button)
    {
        if (is_array($button) == false)
        {
            $submit = array($button);
        }

        $this->buttons = array_merge($this->buttons, $button);

        return $this;
    }


    /**
     * Get the buttons stack.
     */
    function getButtons()
    {
        return $this->buttons;
    }

    /**
     * Renders the Form
     *
     * @return $html_form string HTML Code of the Form.
     */
    public function render()
    {
        # init html form
        $html_form = '<!-- Start of Form "'. $this->getName() .'" -->' . CR;

        # open form
        $html_form  .= '<form ';

        if( strlen($this->getID()) > 0 )
        {
            $html_form .= 'id="'.$this->getID().'" ';
        }

        if( strlen($this->getAction()) > 0 )
        {
            $html_form .= 'action="'.$this->getAction().'" ';
        }

        if( strlen($this->getMethod()) > 0 )
        {
            $html_form .= 'method="'.$this->getMethod().'" ';
        }

        if( strlen($this->getEncoding()) > 0 )
        {
            $html_form .= 'enctype="'.$this->getEncoding().'" ';
        }

        if( strlen($this->getTarget()) > 0 )
        {
            $html_form .= 'target="'.$this->getTarget().'" ';
        }

        if( strlen($this->getName()) > 0 )
        {
             $html_form .= 'name="'.$this->getName().'" ';
        }

        if( strlen($this->getClass()) > 0 )
        {
             $html_form .= 'class="'.$this->getClass().'"';
        }

        # close the form tag
        $html_form .= '>' . CR;


        # add heading
        if( strlen($this->getHeading()) > 0 )
        {
             $html_form .= '<br /> <h2>'.$this->getHeading().'</h2>' . CR;
        }

        # add description
        if( strlen($this->getDescription()) > 0 )
        {
             $html_form .= '<p>'.$this->getDescription().'</p>' .CR;
        }

        # sort formelements by index
        ksort($this->formelements);

        # loop over all registered formelements of this form and render them
        foreach( $this->formelements as $formelement )
        {
            if( $formelement->getType() == 'submit' or $formelement->getType() == 'reset' or $formelement->getType() == 'button')
            {
                $html_form .= '<div class="formbutton">';
            }
            else
            {
                $html_form .= '<div class="formline">';
            }

            # add label
            if ( $formelement->hasLabel() == true)
            {
                $html_form .= CR . '<label>' . $formelement->getLabel() . '</label>' . CR; # @todo if required form field add (*)
            }

            # add div inside
            $html_form .= '<div class="forminside">';

            # render the formelement
            $html_form .= CR . $formelement->render() . CR . '<br />';

            # add description
            if ( isset($formelement->description) == true)
            {
                $html_form .= CR . '<span class="formdescription">'.$formelement->getDescription() . '</span>' . CR;
            }

            # close div inside
            $html_form .= '</div>';

            # close div formbutton/formline
            $html_form .= '</div>';
        }

        # add buttons @todo
        #$html_form .= $this->buttons;

        # close form
        $html_form .= '</form>' . CR . '<!--- End of Form "'. $this->getName() .'" -->' . CR;

        return $html_form;
    }

    /**
     * Returns a XHTML string representation of the form
     *
     * @see Clansuite_Form::render()
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Adds a formelement to the form
     *
     * @param $formelement Clansuite_Formelement Object implementing the Clansuite_Form_Interface
     * @param $position integer The position number of this formelement (ordering).
     * @return $this Form Object
     */
    public function addElement($formelement, $position = null)
    {
        if( ($formelement instanceof Clansuite_Formelement_Interface) == false )
        {
            $formelement = $this->formfactory($formelement);
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
     * @return $formelement Clansuite_Formelement Object implementing the Clansuite_Form_Interface
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
     * @return $formelement Clansuite_Formelement Object
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
     * Factory method. Instantiates and returns a new form object.
     *
     * @return object
     */
    public static function formfactory($formelement, $type = '')
    {
        if (!class_exists('Clansuite_Formelement_'.$formelement))
        {
            if(is_file(ROOT_CORE . 'viewhelper/formelements/'.$formelement.'.form.php'))
            {
                require ROOT_CORE . 'viewhelper/formelements/'.$formelement.'.form.php';
            }
        }

        $formelement_classname = 'Clansuite_Formelement_'.ucfirst($formelement);
        $formelement = new $formelement_classname;

        return $formelement;
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
 * Interface for Clansuite_Form
 */
interface Clansuite_Form_Interface
{
    # output the whole form
    public function render();

    # set action and method
    public function setAction($action);
    public function setMethod($method);

    # add/remove a formelement
    public function addElement($formelement, $position = null);
    public function delElement($name);

    # load/save the XML description of the form
    #public function loadDescriptionXML($xmlfile);
    #public function saveDescriptionXML($xmlfile);

    # shortcut method / factory method for accessing the formelements
    public static function formfactory($formelement, $type = '');

    # callback for validation on the whole form (all formelements)
    #public function processForm();
}
?>