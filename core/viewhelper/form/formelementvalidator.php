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

abstract class Clansuite_Formelement_Validator
{
    /**
     * Error state of the validator.
     *
     * @var boolean
     */
    public $error = false;

    /**
     * General prupose options array.
     * For instance, this options array is passed as third parameter to
     * filter_var($value, FILTER_CONSTANT, $options).
     *
     * @var array
     */
    public $options = array();

    /**
     * Getter for Options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Setter for Options.
     *
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * Setter for the error state of the validator.
     *
     * @param boolean $bool True if error, false if not.
     */
    public function setError($bool)
    {
        $this->error = (bool) $bool;
    }

    /**
     * Getter for the error state of the validator.
     *
     * @return boolean
     */
    public function hasError()
    {
        return $this->error;
    }

    /**
     * Each Formelement Validator must return an errormessage.
     * The errormessage must be wrapped in a gettext shorthand call,
     * like so:
     * return _('This value is not ok.');
     *
     * @param string The Errormessage, when the validation fails.
     */
    abstract public function getErrorMessage();

    /**
     * Each Formelement Validator must implement validation logic.
     * This is the pure validation logic, called by validate().
     * If you need more complex things for validation, then
     * add some static helper functions for usage inside this method.
     *
     * @param $value The value to validate.
     * @param boolean True if formelement validates, false if not.
     */
    abstract protected function processValidationLogic($value);

    /**
     * Accepts an array and assigns object property names (by key) and values (by value) accordingly.
     * The objects needs a setter method for the value.
     *
     * Example:
     * $properties = array('maxlength' = '100');
     * Result:
     * $this->setMaxlength(100); === $this->maxlength = 100;
     *
     * @param array $properties
     */
    public function setProperties(array $properties)
    {
        foreach($properties as $property_name => $value)
        {
            $setter_method = 'set' . $property_name;

            # Set the value via a Setter Method
            $this->{$setter_method}($value);
        }
    }

    /**
     * Main method for the validation of this formelement.
     *
	 * @param boolean True if formelement validates, false if not.
	 */
	public function validate($value)
	{
		$valid = false;

        $valid = $this->processValidationLogic($value);

		return ($valid === true) ? true : false;
	}
}
?>