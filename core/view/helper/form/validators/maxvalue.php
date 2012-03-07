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
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Validates the value of an integer|float with maxvalue given.
 */
class Clansuite_Formelement_Validator_Maxvalue extends Clansuite_Formelement_Validator
{
    public $maxvalue;

    public function getMaxValue()
    {
        return $this->maxvalue;
    }

    /**
     * Setter for the maximum length of the string.
     *
     * @param int|float $maxvalue
     */
    public function setMaxValue($maxvalue)
    {
        if(is_string($maxvalue) === true)
        {
            $msg = _('Parameter Maxvalue must be numeric (int|float) and not %s.');
            $msg = sprintf($msg, gettype($maxvalue));

            throw new InvalidArgumentException($msg);
        }

        $this->maxvalue = $maxvalue;
    }

    public function getErrorMessage()
    {
        $msg = _('The value exceeds the maximum value of %s.');

        return sprintf($msg, $this->getMaxValue());
    }

    public function getValidationHint()
    {
        $msg = _('The value must be smaller than %s.');

        return sprintf($msg, $this->getMaxValue());
    }

    protected function processValidationLogic($value)
    {
        if($value > $this->getMaxValue())
        {
            return false;
        }

        return true;
    }
}
?>