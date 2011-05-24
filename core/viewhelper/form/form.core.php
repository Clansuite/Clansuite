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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005-onwards)
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
 *    The form element represents a collection of form-associated elements, some of which can represent
 *    editable values that can be submitted to a server for processing.
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
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Form
 */

class Clansuite_Form implements Clansuite_Form_Interface
{
    /**
     * Contains all formelements / formobjects registered for this form.
     *
     * @var array
     */
    protected $formelements = array();

    /**
     * Form attributes:
     *
     * accept-charset, action, autocomplete, enctype, method, name, novalidate, target
     *
     * @link http://dev.w3.org/html5/html-author/#forms
     */

    /**
     * Contains accept-charset of the form.
     *
     * @var string
     */
    protected $acceptcharset;

    /**
     * Contains action of the form.
     *
     * @var string
     */
    protected $action;

    /**
     * Contains autocomplete state of the form.
     *
     * @var boolean
     */
    protected $autocomplete;

    /**
     * Contains encoding of the form.
     *
     * @var string
     */
    protected $encoding;

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

    protected $novalidation;

    protected $target;


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
     * Flag variable to indicate, if form has an error.
     *
     * @var boolean
     */
    protected $formerror_flag = false;

    /**
     * Form Decorators Array, contains one or several formdecorator objects.
     *
     * @var array
     */
    private $formdecorators = array();

    /**
     * Toogle variable to control registering of default Formdecorators during rendering.
     *
     * @var boolean
     */
    private $useDefaultFormDecorators = true;

    /**
     * Form Groups Array, contains one or several formgroup objects.
     *
     * @var array
     */
    protected $formgroups = array();

    protected $errormessages_stack = array();

    /**
     * Construct
     *
     * @param mixed|array|string $name_or_attributes Set the name of the form OR and array with attributes.
     * @param string $method Set the method of the form. Valid are get/post.
     * @param string $action Set the action of the form.
     *
     */
    public function __construct($name_or_attributes = null, $method = null, $action = null)
    {
        if(null === $name_or_attributes)
        {
            throw new Exception('Missing argument 1 - has to be string (Name of Form) or array (Form Description Array).');
        }

        # case 1: $name is a string, the name of the form
        if(is_string($name_or_attributes))
        {
            $this->setName($name_or_attributes);
        }
        # case 2: $name is an array with several attribute => value relationships
        elseif(is_array($name_or_attributes))
        {
            $this->setAttributes($name_or_attributes);
        }

        if(isset($method) and isset($action))
        {
            $this->setMethod($method);
            $this->setAction($action);
        }
    }

    /**
     * Sets the method (POST, GET) to the form.
     *
     * @param string $method POST or GET
     * @return Clansuite_Form
     */
    public function setMethod($method)
    {
        $method = mb_strtolower($method);

        if($method == 'post' or $method == 'get')
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
     * Returns method (GET or POST) of this form.
     *
     * @return string Name of the method of this form.
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set action of this form (which is the target url).
     *
     * @param $action string Target URL of the action of this form.
     * @return Clansuite_Form
     */
    public function setAction($action)
    {
        $this->action = Clansuite_Router::buildURL($action);

        return $this;
    }

    /**
     * Returns action of this form (target url).
     *
     * @return string Target Url as the action of this form.
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Returns autocompletion state of this form.
     *
     * @return boolean Returns autocompletion state of this form.
     */
    public function getAutocomplete()
    {
        return ($this->autocomplete === true) ? 'on' : 'off';
    }

    /**
     * Set autocomplete of this form.
     * If "on" browsers can store the form's input values, to auto-fill the form if the user returns to the page.
     *
     * @param $bool boolean state to set for autocomplete.
     * @return Clansuite_Form
     */
    public function setAutocomplete($bool)
    {
        $this->autocomplete = (bool) $bool;

        return $this;
    }

    /**
     * Gets the target (_blank, _self, _parent, _top)
     *
     * @return type string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Set the target of the form.
     *
     * _blank 	 Open in a new window
     * _self 	  Open in the same frame as it was clicked
     * _parent 	Open in the parent frameset
     * _top
     *
     * @param string $target _blank, _self, _parent, _top
     * @return Clansuite_Form
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Returns novalidation state of this form.
     * If present the form should not be validated when submitted.
     *
     * @return boolean Returns novalidation state of this form.
     */
    public function getNoValidation()
    {
        return ($this->novalidation === true) ? 'novalidate' : '';
    }

    /**
     * Set novalidation state of this form.
     * If true the form should not be validated when submitted.
     *
     * @link http://dev.w3.org/html5/spec-author-view/association-of-controls-and-forms.html#attr-fs-novalidate
     * @param $bool boolean state to set for novalidation.
     * @return Clansuite_Form
     */
    public function setNoValidation($bool)
    {
        $this->novalidation = (bool) $bool;

        return $this;
    }

    /**
     * Returns the requested attribute if existing else null.
     *
     * @param $parametername
     * @return mixed null or value of the attribute
     */
    public function getAttribute($attributename)
    {
        if(isset($this->{$attributename}))
        {
            return $this->{$attributename};
        }
        else
        {
            return null;
        }
    }

    /**
     * Setter method for Attribute
     *
     * @param array $attributes attribute name
     * @param array $value value
     */
    public function setAttribute($attribute, $value)
    {
        $this->{$attribute} = $value;
    }

    /**
     * Setter method for Attributes
     *
     * @param array $attributes Array with one or several attributename => value relationships.
     */
    public function setAttributes($attributes)
    {
        if(is_array($attributes))
        {
            /**
             * Array is a form description array for the formgenerator
             */
            if(isset($attributes['form']))
            {
                # generate a form with the formgenerator by passing the attributes array in
                $form = new Clansuite_Array_Formgenerator($attributes);
                # and copy all properties of the inner form object to ($this) outer form object =)
                $this->copyObjectProperties($form, $this);
                # unset inner form
                unset($form);
            }
            else
            {
                /**
                 * Just normal <form attribute(s)=value></form>
                 */
                foreach($attributes as $attribute => $value)
                {
                    $this->setAttribute($attribute, $value);
                }
            }
        }
    }

    /**
     * Copy properties from object A to object B.
     * *BadAssMethodWarning*
     *
     * @param object $object_to_copy The Object to copy the properties from.
     * @param object $target The Object to copy the properties to. Defaults to $this.
     */
    public function copyObjectProperties($object_to_copy, $target = null)
    {
        $varArray = get_object_vars($object_to_copy);
        foreach($varArray as $key => $value)
        {
            # use this object, if no target object is specified
            if($target == null)
            {
                $this->$key = $value;
            }
            else
            {
                $target->$key = $value;
            }
        }
        unset($key, $value);
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
     * Set accept-charset of this form.
     * Like accept-charset="ISO-8859-1".
     *
     * @param string $charset Charset of this form (utf-8, iso-8859-1).
     * @return Clansuite_Form
     */
    public function setAcceptCharset($charset)
    {
        $this->acceptcharset = $charset;

        return $this;
    }

    /**
     * Returns accept-charset of this form.
     *
     * @return string Accept-charset of this form. Defaults to UTF-8.
     */
    public function getAcceptCharset()
    {
        if(empty($this->acceptcharset))
        {
            $this->setAcceptCharset('utf-8');
        }

        return $this->acceptcharset;
    }

    /**
     * Set class of this form.
     *
     * @param string $class Css Classname of this form.
     * @return Clansuite_Form
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Returns css classname of this form.
     *
     * @return string Css Classname of this form.
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set description of this form.
     *
     * @param string $description Description of this form.
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
     * @return string Description of this form.
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set heading of this form.
     *
     * @param string $heading Heading of this form.
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
     * @return string Heading of this form.
     */
    public function getHeading()
    {
        return $this->heading;
    }

    /**
     * Set encoding type of this form.
     *
     * - application/x-www-form-urlencoded
     *  All characters are encoded before sent (this is default)
     * - multipart/form-data
     * 	No characters are encoded.
     *  This value is required when you are using forms that have a file upload control
     * - text/plain
     *  Spaces are converted to "+" symbols, but no special characters are encoded
     *
     * @param string $encoding Encoding type of this form.
     * @return Clansuite_Form
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;

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
    public function registerDefaultFormelementDecorators($formelement)
    {
        $formelement->addDecorator('label');
        $formelement->addDecorator('description');
        $formelement->addDecorator('div')->setClass('formline');
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

        #Clansuite_Debug::printR($formelements);

        # sort formelements by index
        ksort($formelements);

        # loop over all registered formelements of this form and render them
        foreach( $formelements as $formelement )
        {
            # fetch all decorators of this formelement
            $formelementdecorators = $formelement->getDecorators();

            if(empty($formelementdecorators))
            {
                # apply default decorators to the formelement
                $this->registerDefaultFormelementDecorators($formelement);

                # fetch again all decorators of this formelement
                $formelementdecorators = $formelement->getDecorators();
            }

            # then render this formelement (pure)
            $html_formelement = $formelement->render();

            # for each decorator, decorate the formelement and render it
            foreach ($formelementdecorators as $formelementdecorator)
            {
                $formelementdecorator->decorateWith($formelement);
                $html_formelement = $formelementdecorator->render($html_formelement);
            }

            # append the form html with the decorated formelement html
            $html_form .= $html_formelement;
        }

        #Clansuite_Debug::printR($html_form);
        return $html_form;
    }

    /**
     * Render this form
     *
     * @return Clansuite_Formelement
     */
    public function render()
    {
        # the content of the form are the formelements
        $html_form = $this->renderAllFormelements();

        if(empty($formdecorators) === true)
        {
            if($this->useDefaultFormDecorators === true)
            {
                # set a common style to the form by registering one or more decorators
                $this->registerDefaultFormDecorators();
            }
        }

        # iterate over all decorators
        foreach ($this->getDecorators() as $decorator)
        {
            # thereby sticking this form in each decorator
            $decorator->decorateWith($this);

            # apply some settings or call some methods on the decorator
            # before rendering
            # $decorator->$value;
            # $decorator->$method($value);
            # combined $decorator->setAttributes();

            # then rendering it
            $html_form = $decorator->render($html_form);

            # remove the processed decorator from the decorators stack
            $this->removeDecorator($decorator);

            # unset the decorator var in the foreach context
            unset($decorator);
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
     * You don't know the formelements available? Then take a look at
     * a) the directory core\viewhelper\form\formelements\*
     * b) the manual
     * @link http://docs.clansuite.com/developer/manual/de/#_clansuite_form
     *
     * @param $formelement string|object Name of formelement or Object implementing the Clansuite_Form_Interface
     * @param $position integer The position number of this formelement (ordering) in the formelements stack.
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
        if( ($formelement instanceof Clansuite_Formelement_Interface) === false )
        {
            $formelement = $this->formelementFactory($formelement);
        }

        # little helper for easier use of the formelement "file"
        # this switches the "encytype" attribute of form tag automatically
        if(($formelement instanceof Clansuite_Formelement_File) === true)
        {
            $this->setEncoding('multipart/form-data');
        }

        # if we don't have a position to order the elements, we just add an element
        if($position == null)
        {
            $this->formelements[] = $formelement;
        }
        # else we position the element under it's number to keep things in an order
        elseif(is_int($position) === true)
        {
            $this->formelements[$position] = $formelement;
        }

        # return object -> fluent interface / method chaining
        return $formelement;
    }

    /**
     * Removes a formelement by name (not type!)
     *
     * @param string $name
     * @return bool
     */
    public function delElementByName($name)
    {
        $cnt_formelements = count($this->formelements);
        for($i = 0; $i < $cnt_formelements; $i++)
        {
            if($name === $this->formelements[$i]->getName())
            {
                unset($this->formelements[$i]);
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
        if(is_numeric($position) and isset($this->formelements[$position]))
        {
            return $this->formelements[$position];
        }
        return null;
    }

    /**
     * Fetches a formelement via it's name (not type!)
     *
     * @param $name string The name of the requested formelement.
     * @return Clansuite_Formelement $formelement Object
     */
    public function getElementByName($name)
    {
        foreach($this->formelements as $formelement)
        {
            if($name === $formelement->getName())
            {
                return $formelement;
            }
        }
        return null;
    }

    public function getElement($formelement_position = null)
    {
        # if no position is incomming we return the last formelement item
        # this is the normal call to this method, while chaining
        if($formelement_position === null)
        {
           # fetch last item of array = last_formelement
           $formelement_object = end($this->formelements);
        }
        elseif(is_numeric($formelement_position))
        {
            # uh, not the last element of the formelements array requested, but some position
            $formelement_object = $this->getElementByPosition($formelement_position);
        }
        else # is_string
        {
            $formelement_object = $this->getElementByName($formelement_position);
        }

        return $formelement_object;
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
        # construct Clansuite_Formelement_Name
        $formelement_classname = 'Clansuite_Formelement_'.ucfirst($formelement);

        # if not already loaded, require formelement file
        if (false == class_exists($formelement_classname, false))
        {
            $file = ROOT_CORE . 'viewhelper/form/formelements/'.$formelement.'.form.php';

            if(is_file($file) === true)
            {
                include $file;
            }
        }

        # instantiate the new formelement and return
        return new $formelement_classname;
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
        /*
        if ($this->validateForm() === false)
        {
            # if not, redisplay the form (decorate with errors + render)
            $form->errors();

        }
        else # form was properly filled, display a success web page
        {
            # success!!
            $form->success();
        }
        */
    }

    public function populate($data = null)
    {
        $this->setValues($data);
    }

    public function setValues($data = null)
    {
        # because $data might be an object, typecast $data to array
        if(is_object($data) === true)
        {
            $data = (array) $data;
        }

        # fetch data from POST or GET
        if(null === $data)
        {
            $method = $this->getMethod();

            if ($method === 'GET')
            {
                $data = Clansuite_HttpRequest::getGet();
            }

            if ($method === 'POST')
            {
                $data = Clansuite_HttpRequest::getPost();
            }
        }

        # now we got an $data array to populate all the formelements with
        foreach($data as $key => $value)
        {
            foreach($this->formelements as $formelement)
            {
                $type = $formelement->getType();

                # skip certain formelements (buttons, etc.) from setValue()
                # setting the value would change the visible "name" of these elements
                if($type == 'submit' or $type == 'button' or $type == 'cancelbutton' or $type == 'resetbutton')
                {
                    continue;
                }

                # data key and name of formelement have to match
                if($formelement->getName() == ucfirst($key))
                {
                    $formelement->setValue($value);
                }
            }
        }
    }

    public function getValues()
    {
        # return validates values, ready for model insert
    }

    /**
     * ===================================================================================
     *      Form Decoration
     * ===================================================================================
     */

    /**
     * Is a shortcut/proxy/convenience method for addDecorator()
     * <strong>WATCH IT! THIS BREAKS THE CHAINING IN REGARD TO THE FORM</strong>
     *
     * @see $this->addDecorator()
     *
     * @return Clansuite_Formdecorator object
     */
    public function setDecorator($decorators)
    {
        return $this->addDecorator($decorators);
    }

    /**
     * Add multiple decorators at once
     *
     * @param array $decorators Array of decorator objects or names.
     */
    public function addDecorators($decorators)
    {
        # address each one of those decorators
        foreach($decorators as $decorator)
        {
            $this->addDecorator($decorator);
        }
    }

    /**
     * Adds a decorator to the form
     * <strong>WATCH IT! THIS BREAKS THE CHAINING IN REGARD TO THE FORM</strong>
     *
     * @example
     * $form->addDecorator('fieldset')->setLegend('legendname');
     *
     * @return Clansuite_Formdecorator object
     */
    public function addDecorator($decorator)
    {
        # check if multiple decorator are incomming at once
        if(is_array($decorator))
        {
            $this->addDecorators($decorator);
        }

        # if we got a string
        if(is_string($decorator))
        {
            # turn it string into an decorator object
            $decorator = $this->decoratorFactory($decorator);
        }

        # and check if it is an object implementing the right interface
        if($decorator instanceof Clansuite_Form_Decorator_Interface)
        {
            # if so, fetch this decorator objects name
            $decoratorname = $decorator->name;
        }

        # now check if this decorator is not already set (prevent decorator duplications)
        if(false === in_array($decorator, $this->formdecorators))
        {
            # set this decorator object under its name into the array
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
     * Toggles the Usage of Default Form Decorators
     * If set to false, registerDefaultFormDecorators() is not called during render()
     *
     * @see render()
     * @see registerDefaultFormDecorators()
     *
     * @param type $boolean Form is decorated on true (default), not decorated on false.
     */
    public function useDefaultFormDecorators($boolean = true)
    {
        $this->useDefaultFormDecorators = $boolean;
    }

    /**
     * Set default form decorators (form)
     */
    public function registerDefaultFormDecorators()
    {
            $this->addDecorator('html5validation');
            $this->addDecorator('form');
            $this->addDecorator('fieldset');
            $this->addDecorator('div')->setId('forms');
    }

    /**
     * Removes a form decorator from the decorator stack by name or object.
     *
     * @param mixed|string|object $decorator Object or String identifying the Form Decorator.
     */
    public function removeDecorator($decorator)
    {
        # check if it is an object implementing the right interface
        if($decorator instanceof Clansuite_Form_Decorator_Interface)
        {
            # if so, fetch this decorator objects name
            # overwriting $decorator object with decorator name string
            $decorator = $decorator->name;
        }

        # $decorator need to be string
        if(isset($this->formdecorators[$decorator]))
        {
            unset($this->formdecorators[$decorator]);
        }
    }

    public function getDecorator($decorator)
    {
        if(isset($this->formdecorators[$decorator]))
        {
            return $this->formdecorators[$decorator];
        }
        else
        {
           throw new Clansuite_Exception('The Formdecorator "' . $decorator . '" was not found.');
        }
    }

    /**
     * Factory method. Instantiates and returns a new formdecorator object.
     *
     * @return Clansuite_Formdecorator
     */
    public function decoratorFactory($formdecorator)
    {
        # if not already loaded, require forelement file
        if(false == class_exists('Clansuite_Form_Decorator_' . $formdecorator, false))
        {
            $file = ROOT_CORE . 'viewhelper/form/formdecorators/form/' . $formdecorator . '.form.php';

            if(is_file($file) === true)
            {
                include $file;
            }
        }

        # construct Clansuite_Formdecorator_Name
        $formdecorator_classname = 'Clansuite_Form_Decorator_' . ucfirst($formdecorator);
        # instantiate the new $formdecorator and return
        return new $formdecorator_classname();
    }

    /**
     * ===================================================================================
     *      Formelement Decoration
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
     * Adds a decorator to a formelement.
     *
     * The first parameter accepts the formelement decorator.
     * You might specify a decorater
     * (a) by its name or
     * (b) multiple decorators as an array or
     * (c) a instantied decorator object might me handed to this method.
     * @see addDecorator()
     *
     * The second parameter specifies the formelement_position.
     * If no position is given, it defaults to the last formelement in the stack of formelements.
     *
     * <strong>WATCH IT! THIS BREAKS THE CHAINING IN REGARD TO THE FORM</strong>
     *
     * @example
     * $form->addFormelementDecorator('fieldset')->setLegend('legendname');
     * This would attach the decorator fieldset to the last formelement of $form.
     *
     * @param string|array|object $decorator The formelement decorator(s) to apply to the formelement.
     * @param int|string|object $formelement_position Position in the formelement stack or Name of formelement.
     * @return Clansuite_Formdecorator object
     */
    public function addFormelementDecorator($decorator, $formelement_pos_name_obj = null)
    {
        if(is_array($this->formelements) === false)
        {
            throw new Clansuite_Exception('No Formelements found. Add the formelement first, then decorate it!');
        }

        if(false === is_object($formelement_pos_name_obj))
        {
            $formelement_object = $this->getElement($formelement_pos_name_obj);
        }

        # add the decorator
        # WATCH IT! this is a forwarding call to formelement.core.php->addDecorator()
        return $formelement_object->addDecorator($decorator);
    }

    public function removeFormelementDecorator($decorator, $formelement_position = null)
    {
        $formelement_object = $this->getElement($formelement_position);

        if(isset($formelement_object->formelementdecorators[$decorator]))
        {
            return $formelement_object->formelementdecorators[$decorator];
        }
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
    /*
    public function addGroup($groupname)
    {
        # @todo groupname becomes ID of decorator (e.g. a fieldset)

        $this->formgroups[] = $groupname;

        return $this;
    }*/

    /**
     * ===================================================================================
     *      Form Validation
     * ===================================================================================
     */

    /**
     * Adds a validator to the formelement
     *
     * @return Clansuite_Formelement
     */
    public function addValidator($validator)
    {


        return $this;
    }

    /**
     * Validates the form.
     *
     * The method iterates (loops over) all formelement objects and calls the validation on each object.
     *
     * @return boolean Returns true if form validates, false if validation fails, because errors exist.
     */
    public function validateForm()
    {
        foreach($this->formelements as $formelement)
        {
            if($formelement->validate() === false)
            {
                # raise error flag on the form
                $this->setErrorState(true);

                # and transfer errormessage from formelement to form errormessages stack
                $this->addErrormessage($formelement->getError());
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
     *      Form Errormessages
     * ===================================================================================
     */

     public function setErrorState($boolean = true)
     {
        $this->form_has_error_flag = $boolean;
     }

     public function getErrorState()
     {
        return $this->form_has_error_flag;
     }

     public function hasErrors()
     {
        return $this->form_has_error_flag;
     }

     public function addErrormessage($errormessage)
     {
        $this->errormessages_stack[] = $errormessage;
     }

     public function addErrormessages(array $errormessages)
     {
        $this->errormessages_stack = $errormessages;
     }

     public function resetErrormessages()
     {
        $this->errormessages_stack = array();
     }

     public function getErrormessages()
     {
        return $this->errormessages_stack;
     }

    /**
     * ============================
     *    Magic Methods: get/set
     * ============================
     */

    /**
     * Magic Method: set
     *
     * @param $name Name of the attribute to set to the form.
     * @param $value The value of the attribute.
     */
    public function __set($name, $value)
    {
        $this->setAttributes(array($name => $value));
    }

    /**
     * Magic Method: get
     *
     * @param $name
     */
    public function __get($name)
    {
        return $this->getAttribute($name);
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
    public function delElementByName($name);

    # load/save the XML description of the form
    #public function loadDescriptionXML($xmlfile);
    #public function saveDescriptionXML($xmlfile);

    # shortcut method / factory method for accessing the formelements
    public static function formelementFactory($formelement);

    # callback for validation on the whole form (all formelements)
    #public function processForm();
}
?>