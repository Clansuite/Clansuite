<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005-onwards)
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
     * @var filter var options
     */
    private $options = array();

    /**
     * Setter for the range array.
     *
     * @param int $minimum_length The minimum length of the string.
     * @param int $maximum_length The maximum length of the string.
     */
    public function setRange($minimum_length, $maximum_length)
    {
        $this->options['options']['min_range'] = $minimum_length;
        $this->options['options']['max_range'] = $maximum_length;
    }

    public function getValidationHint()
    {
        $min = $this->options['options']['min_range'];
        $max = $this->options['options']['max_range'];

        $msg = _('Please enter text within the in the range of %s to %s chars.');

        return sprintf($msg, $min, $max);
    }

    public function getErrorMessage()
    {
        $min = $this->options['options']['min_range'];
        $max = $this->options['options']['max_range'];

        $msg = _('The value is outside the range of %s <> %s chars.');

        return sprintf($msg, $min, $max)
    }

    protected function processValidationLogic($value)
    {
        if(false !== filter_var($value, FILTER_VALIDATE_INT, $this->options))
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