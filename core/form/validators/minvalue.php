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
namespace Koch\Formelement\Validator;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Validates the value of an integer with minvalue given.
 */
class Minvalue extends Validator
{
    public $minvalue;

    public function getMinValue()
    {
        return $this->minvalue;
    }

    /**
     * Setter for the minimum length of the string.
     *
     * @param int|float $minvalue
     */
    public function setMinValue($minvalue)
    {
        if(is_string($minvalue) === true)
        {
            $msg = _('Parameter Minvalue must be numeric (int|float) and not %s.');
            $msg = sprintf($msg, gettype($minvalue));

            throw new InvalidArgumentException($msg);
        }

        $this->minvalue = $minvalue;
    }

    public function getErrorMessage()
    {
        $msg = _('The value deceeds (is less than) the minimum value of %s.');

        return sprintf($msg, $this->getMinValue());
    }

    public function getValidationHint()
    {
        $msg = _('Please enter a value not deceeding (being less than) the minimum value of %s.');

        return sprintf($msg, $this->getMinValue());
    }

    protected function processValidationLogic($value)
    {
        if($value < $this->getMinValue())
        {
            return false;
        }

        return true;
    }

}

?>