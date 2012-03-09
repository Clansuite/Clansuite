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
 * Validates the lenght of a string with maxlength given.
 */
class Clansuite_Formelement_Validator_Maxlength extends Clansuite_Formelement_Validator
{
    public $maxlength;

    public function getMaxlength()
    {
        return $this->maxlength;
    }

    /**
     * Setter for the maximum length of the string.
     *
     * @param integer $maxlength
     */
    public function setMaxlength($maxlength)
    {
        $this->maxlength = (int) $maxlength;
    }

    public function getErrorMessage()
    {
        $msg = _('The value exceeds the maxlength of %s chars');

        return sprintf($msg, $this->getMaxlength());
    }

    public function getValidationHint()
    {
        $msg = _('Please enter %s chars at maximum.');

        return sprintf($msg, $this->getMaxlength());
    }

    /**
     * Get length of passed string.
     * Takes multibyte characters into account, if functions available.
     *
     * @param string $string
     * @return integer $length
     */
    public static function getStringLength($string)
    {
        if(function_exists('iconv_strlen'))
        {
            return iconv_strlen($string, 'UTF-8');
        }
        else if(function_exists('mb_strlen'))
        {
            return mb_strlen($string, 'utf8');
        }
        else
        {
            return strlen(utf8_decode($string));
        }
    }

    protected function processValidationLogic($value)
    {
        if (self::getStringLength($value) > $this->getMaxlength())
        {
            return false;
        }

        return true;
    }
}
?>