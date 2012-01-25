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

class Clansuite_Formelement_Validator_Range extends Clansuite_Formelement_Validator
{
    /**
     * @var Clansuite_Formelement_Validator_Minlength
     */
    private $validator_minlength;

    /**
     * @var Clansuite_Formelement_Validator_Maxlength
     */
    private $validator_maxlength;

    public function __construct()
    {
        $this->validator_minlength = Clansuite_Formelement_Validator_Minlength();
        $this->validator_maxlength = Clansuite_Formelement_Validator_Maxlength();
    }

    /**
     * Setter for the range array.
     *
     * @param int $minimum_length The minimum length of the string.
     * @param int $maximum_length The maximum length of the string.
     */
    public function setRange($minimum_length, $maximum_length)
    {
        $this->validator_minlength->setMinlength($minimum_length);
        $this->validator_maxlength->setMaxlength($maximum_length);
    }

    public function getErrorMessage()
    {
        $min = $this->validator_minlength->getMinlength();
        $max = $this->validator_maxlength->setMaxlength();

        return _('The value is outside the range of ' . $min .' <> '. $max .' chars.');
    }

    protected function processValidationLogic($value)
    {
        if($this->validator_maxlength->validate($value) === true and $this->validator_minlength->validate($value) === true)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
?>