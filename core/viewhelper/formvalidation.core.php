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
if (defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
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
    public function validates(Clansuite_Formelement_Interface $formelement);

    # set/get/is validation errors
    public function setError();
    public function getErrors();
    public function isError();
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
     * Validity Flag: Is this Formelement valid?
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
     * This is a shortcut/proxy/convenience method to isError()
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
     * Getter for Validation Errors Array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * setRules
     *
     * Example Usage (in Module Action):
     *
     * // set validation rules
     * $rules['firstname']  = "required";
     * $rules['email']      = "required, email";
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
     * Proxy method for isValid.
     *
     * @return boolean True if the data is valid, otherwise false
     */
    public function validates()
    {
        return $this->isValid();
    }

    /**
     * This method fetches the submitted form data and checks if valid.
     * Getter for "isValid" Flag-Variable.
     *
     * @todo abstract $_SERVER $_POST $_GET
     * @return boolean True if the data is valid, otherwise false
     */
    public function isValid()
    {
        if (!isset($this->isValid))
        {
            if ('POST' == $_SERVER['REQUEST_METHOD'])
            {
                list($this->isValid, $_POST) = $this->validate($_POST);
            }
            elseif('GET' == $_SERVER['REQUEST_METHOD'])
            {
                list($this->isValid, $_GET) = $this->validate($_GET);
            }
        }

        return $this->isValid;
    }

    /**
     * valdate is the main method of this class
     * the data for a formelement is validated against the validation rules.
     * in case the the data is not matching the rule, it's invalid and a validation error is set.
     * @param array $incomming_formdata Array with formdata via POST/GET
     */
    public function validate($incomming_formdata) #(Clansuite_Form_Interface $form)
    {
        # @todo
    } 
}
?>