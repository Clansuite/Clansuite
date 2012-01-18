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
 * Clansuite_Formelement
 *
 * @author     Jens-André Koch   <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005-onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Form
 */

class Clansuite_Formelement implements Clansuite_Formelement_Interface
{
    /**
     * @var string
     */
    public $name, $id, $type, $class, $size, $disabled, $maxlength, $style, $onclick;

    public $label, $value, $position, $required;

    public $additional_attributes;

    protected $formelementdecorators = array();

    /**
     * validators are stored into an array. making multiple validators for one formelement possible.
     *
     * @var array
     */
    protected $validators = array();

    /**
     * The error messages array stores the incomming errors fromelement validators.
     *
     * @var array
     */
    protected $errormessages = array();

    /**
     * Set id of this form.
     *
     * @param $id string ID of this form.
     * @return Clansuite_Formelement
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
     * @return Clansuite_Formelement
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
     * @return Clansuite_Formelement
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
     * Returns name of this formelement without brackets.
     *
     * @return string Name of this form.
     */
    public function getNameWithoutBrackets()
    {
        $name = mb_strrpos($this->name, '[');

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
     * @param string $class Class to set
     * @return Clansuite_Formelement
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
     * Sets value for this element
     *
     * @param string $value
     * @return Clansuite_Formelement
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Return the (escaped!) value of the formelement.
     *
     * @return string Escaped string
     */
    public function getValue()
    {
        if(is_array($this->value) === true)
        {
            foreach($this->value as $key => $value)
            {
                $this->value[$key] = htmlspecialchars($value);
            }

            return $this->value;
        }
        else
        {
            return htmlspecialchars($this->value);
        }
    }

    /**
     * Returns the (unescaped!) value
     *
     * @return string Unescaped string
     */
    public function getRawValue()
    {
        return $this->value;
    }

    /**
     * Disables this formelement.
     *
     * @return Clansuite_Formelement
     */
    public function disable()
    {
        $this->disabled = true;

        return $this;
    }

    /**
     * Enables this formelement
     *
     * @return Clansuite_Formelement
     */
    public function enable()
    {
        $this->disabled = false;

        return $this;
    }

    /**
     * Set label of this formelement.
     *
     * @param string $label Label of this formelement.
     * @return Clansuite_Formelement
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
     * @return boolean True if label exists, false if not.
     */
    public function hasLabel()
    {
        if(isset($this->label) === true)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * This method provides a shortcut for checking if an formelement is required.
     * You might use this in conditional checks.
     *
     * @return boolean True if formelement is required, false if not.
     */
    public function isRequired()
    {
        if(isset($this->required) === true)
        {
            return true;
        }
        else
        {
            return false;
        }

        return $this;
    }

    /**
     * Set required state for the formelement.
     * A formelement is required, when the user is expected to (must) enter data into the formelement.
     *
     * @param boolean $required Set required state. Defaults to true.
     * @return Clansuite_Formelement
     */
    public function setRequired($required = true)
    {
        $this->required = ($required === true) ? true : false;

        return $this;
    }

    /**
     * Set description of this formelement.
     *
     * @param string $description Description of this formelement.
     * @return Clansuite_Formelement
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Returns description of this formelement.
     *
     * @return string Description of this formeement.
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set onclick text of this formelement.
     *
     * @param string $onclick Onclick text of this formelement.
     * @return Clansuite_Formelement
     */
    public function setOnclick($onclick)
    {
        $this->onclick = $onclick;

        return $this;
    }

    /**
     * Returns onclick text of this formelement.
     *
     * @return string Onclick text of this formelement.
     */
    public function getOnclick()
    {
        return $this->onclick;
    }

    /**
     * ===================================================================================
     *      Formelement Attribute Handling
     * ===================================================================================
     */

    /**
     * Returns the requested attribute if existing else null.
     *
     * @param $parametername
     * @return mixed null or value of the attribute
     */
    public function getAttribute($attributename)
    {
        if(isset($this->{$attributename}) === true)
        {
            return $this->{$attributename};
        }
        else
        {
            return null;
        }
    }

    /**
     * Setter method for Attributes
     *
     * @param array $attributes Array with one or several attributename => value relationships.
     */
    public function setAttributes($attributes)
    {
        if(is_array($attributes) === true and empty($attributes) === false)
        {
            foreach($attributes as $attribute => $value)
            {
                /**
                 * In DEBUG mode the attributes are set via a setter method.
                 * So one might even set a wrong one by accident, like $attribute = 'maxxxlength'.
                 * To protect the developer a bit more, we are focing the usage of a setter method.
                 * If the setter method exists most likely the property will exist too, i guess.
                 */
                if(DEBUG == true)
                {
                    $method = 'set' . $attribute;
                    $this->{$method}($value);
                }
                else # while in production mode
                {
                    # set attribute directly
                    $this->{$attribute} = $value;
                }
            }
        }
    }

   /**
    * Setter method for Attribute
    *
    * @param type $attribute Attribute name
    * @param type $value Value
    */
    public function setAttribute($attribute, $value)
    {
        $this->{$attribute} = $value;
    }

    /**
     * Renders an array of key=>value pairs as an HTML attributes string.
     *
     * @param array $attributes key=>value pairs corresponding to HTML attributes name="value"
     * @return string Attributes as HTML
     */
    public function render_attributes(array $attributes=array())
    {
        if(empty($attributes) === true)
        {
            return '';
        }

        $html = ' ';
        foreach($attributes as $key => $val)
        {
            # html = 'key="value" '
            $html .= $key . '="' . $val . '" ';
        }
        return $html;
    }

    /**
     * ===================================================================================
     *      Formelement Validation
     * ===================================================================================
     */

    /**
     * expect / setRules
     *
     * set validation rules as string
     * "required, maxlength=20";
     * "required, email";
     */
    public function setRules($rule)
    {
        if(false === is_string($rule))
        {
            throw new Clansuite_Exception('Parameter $rule must be of type string.');
        }

        $rules = explode(',', $rule);

        foreach($rules as $rule)
        {
            $rule = trim($rule);

            if(false === strpos($rule, '='))
            {
                # if there is no "=", then there is no value to set
                # rule is then the name of a validator
                $this->addValidator($rule);
            }
            else # ok -> property name to value relationship
            {
                $array = explode('=', $rule);
                $this->addValidator($array[0], array($array[0] => $array[1]));
            }
        }
    }

    /**
     * addValidator
     *
     * Is a shortcut/proxy/convenience method for addValidator()
     *
     * @param object|string Formelement Validator
     * @param mixed A Validator Property Value.
     * WATCH OUT! THIS BREAKS THE CHAINING IN REGARD TO THE FORM
     * @return Clansuite_Formelement_Validator
     */
    public function addValidator($validator, $properties = null)
    {
        if(false === is_object($validator))
        {
            if($validator === 'required' and false === $this->isRequired())
            {
                $this->setRequired();
            }

            $validator = $this->getValidator($validator);
        }

        if(isset($properties))
        {
            $validator->setProperties($properties);
        }

        $this->setValidator($validator);

        return $validator;
    }

    /**
     * Setter method for a validator.
     * The Validator is stored into the validators array.
     * So a formelement might have multiple validators.
     *
     * @param Clansuite_Validator $validator Accepts a Clansuite_Validator Object that has to implement Clansuite_Validator_Interface.
     * @return Clansuite_Formelement
     */
    public function setValidator(/*Clansuite_Formelement_Validates_Interface*/ $validator)
    {
        $this->validators[] = $validator;

        return $this;
    }

    /**
     * Returns a form validator object.
     * Also a factory method, which instantiates and returns a new formvalidator object.
     *
     * @return Clansuite_Formvalidator
     */
    public function getValidator($validator)
    {
        # construct classname
        $class = 'Clansuite_Formelement_Validator_' . ucfirst($validator);

        # return early, if this object is already stored
        if(isset($this->validators[$class]))
        {
            return $this->validators[$class];
        }
        # autoloader
        elseif(true === class_exists($class))
        {

            return new $class;
        }
        # factory method part
        elseif(false == class_exists($class, false))
        {
            $file = ROOT_CORE . 'viewhelper/form/validators/' . $validator . '.php';

            if(is_file($file) === true)
            {
                include $file;
            }
            unset($file);

            return new $class();
        }
        # validator not found
        else
        {
            throw new Clansuite_Exception('Validator named ' . $validator . ' not available.');
        }
    }

    /**
     * Validates the value of a formelement.
     *
     * The validation method processes the value of the formelement
     * by passing it to all registered validators of the formelement.
     * The value of the formelement is valid, when it satisfies
     * each of the element's validation rules.
     *
     * @see $validators array
     * @return boolean
     */
    public function validate()
    {
        $value = $this->getValue();

        # return early, if value empty|null and not required
        if((('' === $value) or (null === $value)) and false === $this->isRequired())
        {
            return true;
        }

        # no rules / validators
        if(null === $this->validators)
        {
            return true;
        }

        # iterate over all validators
        foreach($this->validators as $validator)
        {
            # ensure element validates
            if($validator->validate($value) === true)
            {
                # everything is fine, proceed
                continue;
            }
            else
            {
                # raise the error flag on the formelement
                $this->setError(true);

                # and transfer error message from the validator to the formelement
                $this->addErrorMessage($validator->getErrorMessage());

                return false;
            }
        }

        # formelement value is valid
        return true;
    }

    /**
     * Method adds an validation error to the formelement_validation_error stack.
     *
     * @param $errormessage
     */
    public function addErrorMessage($errormessage)
    {
        $this->errormessages[] = $errormessage;
    }

    /**
     * Returns the validation_error stack.
     *
     * @param $validation_error
     */
    public function getErrorMessages()
    {
        return $this->errormessages;
    }

    /**
      * Sets the error state of the form (formHasError).
      *
      * @param boolean $boolean
      */
     public function setError($boolean = true)
     {
        $this->error = $boolean;
     }

     /**
      * Returns the error state of the form.
      *
      * @return boolean False, if form has an error. True, otherwise.
      */
     public function hasError()
     {
        return $this->error;
     }

    /**
     * ===================================================================================
     *      Formelement Rendering
     * ===================================================================================
     */

    /**
     * override
     */
    public function render()
    {
        # nothing, because each formelement renders itself
    }

    /**
     * __toString works in the scope of the subclass.
     * all formelements inherit the formelement base class,
     * so we place the magic method here, in the parent,
     * and catch the subclass via get_class($this).
     *
     * @return @return HTML Representation of the subclassed Formelement
     */
    public function __toString()
    {
        $subclass = get_class($this);

        if(method_exists($subclass, 'render') === true)
        {
            return $subclass->render();
        }
        /*else # nothing, because each formelement renders itself
        {
            return $this->render();
        }*/
    }

    /**
     * ===================================================================================
     *      Formelement Decoration
     * ===================================================================================
     */

    /**
     * setDecorator
     *
     * Is a shortcut/proxy/convenience method for addDecorator()
     * @see $this->addDecorator()
     *
     * WATCH OUT! THIS BREAKS THE CHAINING IN REGARD TO THE FORM
     * @return Clansuite_Formelement_Decorator
     */
    public function setDecorator($decorators)
    {
        return $this->addDecorator($decorators);
    }

    /**
     * Adds a decorator to the formelement
     *
     * Usage:
     * $form->addDecorator('fieldset')->setLegend('legendname');
     *
     * WATCH OUT! THIS BREAKS THE CHAINING IN REGARD TO THE FORM OBJECT
     * @return Clansuite_Formelement_Decorator
     */
    public function addDecorator($decorators)
    {
        # init vars
        $decoratorname = '';
        $decorator = '';

        # check if multiple decorators are incomming at once
        if(is_array($decorators) === true)
        {
            # address each one of those decorators
            foreach($decorators as $decorator)
            {
                # and check if it is an object implementing the right interface
                if ( ($decorator instanceof Clansuite_Formelement_Decorator_Interface) === true )
                {
                    # if so, fetch this decorator objects name
                    $decoratorname = $decorator->name;
                }
                else
                {
                    # turn it into an decorator object
                    $decorator = $this->decoratorFactory($decorator);
                    $decoratorname = $decorator->name;

                    # WATCH OUT! RECURSION!
                    $this->addDecorator($decorator);
                }
            }
        }
        elseif(is_object($decorators) === true) # one element is incomming via recursion
        {
            $decorator = $decorators;
            $decoratorname = $decorator->name;
        }

        # if we got a string (ignore the plural, it's a one element string, like 'fieldset')
        if (is_string($decorators) === true)
        {
            # turn it into an decorator object
            $decorator = $this->decoratorFactory($decorators);
            $decoratorname = $decorator->name;
        }

        # now check if this decorator is not already set (prevent decorator duplications)
        if(in_array($decoratorname, $this->formelementdecorators) === false)
        {
            # set this decorator object under its name into the array
            $this->formelementdecorators[$decoratorname] = $decorator;
        }

        # WATCH OUT! THIS BREAKS THE CHAINING IN REGARD TO THE FORM
        # We dont return $this here, because $this would be the formelement.
        # Insted the decorator is returned, to apply some properties.
        # @return decorator object
        #Clansuite_Debug::printR($this->formelementdecorators[$decoratorname]);
        #Clansuite_Debug::printR($this->name);

        #Clansuite_Debug::firebug($this);
        #Clansuite_Debug::firebug($this->formelementdecorators);

        return $this->formelementdecorators[$decoratorname];
    }

    /**
     * Getter Method for a decorators of this formelement by it's name..
     *
     * @param string $decoratorname The formelement decorator to look for in the stack of decorators.
     * @return array Returns the object Clansuite_Formelement_Decorator_$decoratorname if registered.
     */
    public function getDecoratorByName($decoratorname)
    {
        return $this->formelementdecorators[$decoratorname];
    }

    /**
     * Getter Method for the decorators of this formelement.
     *
     * @return array Returns the array of Clansuite_Formelement_Decorators registered to this formelement.
     */
    public function getDecorators()
    {
        return $this->formelementdecorators;
    }

    /**
     * Factory method. Instantiates and returns a new formdecorator object.
     *
     * @param string Formelement Decorator.
     * @return Clansuite_Formelement_Decorator
     */
    public function decoratorFactory($decorator)
    {
        # construct Clansuite_Formelement_Decorator_Name
        $class = 'Clansuite_Formelement_Decorator_' . ucfirst($decorator);

        # if not already loaded, require forelement file
        if(false == class_exists($class, false))
        {
            $file = ROOT_CORE . 'viewhelper/form/decorators/formelement/' . $decorator . '.php';

            if(is_file($file) === true)
            {
                include $file;
            }
        }

        # instantiate the new $formdecorator
        return new $class;
    }

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
 * Interface for a single Clansuite Form Element
 */
interface Clansuite_Formelement_Interface
{
    # add/remove attributes for a formelement
    public function setAttribute($attribute, $value);
    public function getAttribute($attribute);

    # initializes the attributes of the formelement
    #public function initialize();

    # renders the output of the formobject as html
    public function render();

    # sets a validation rule to a form element
    #public function addValidation();
}
?>