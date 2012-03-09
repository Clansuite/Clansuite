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

namespace Koch\Formelement;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 *  Koch_Formelement
 *  |
 *  \- Koch_Formelement_Input
 *     |
 *     \- Koch_Formelement_SelectDate
 */
class SelectDate extends Input implements Formelement
{
    public function __construct()
    {
        # Note: HTML5 <input type="date"> is not a select formelement.
        $this->type = 'date';

        return $this;
    }

    /**
     * HTML 5 has several Types of input formfields for date and time selection
     *
     * date             - Selects date, month and year
     * month            - Selects month and year
     * week             - Selects week and year
     * time             - Selects time (hour and minute)
     * datetime         - Selects time, date, month and year (UTC time)
     * datetime-local   - Selects time, date, month and year (local time)
     */
    public function setType($type)
    {
        $types = array('date', 'month', 'week', 'time', 'datetime', 'datetime-local');

        if(in_array($type, $types) === true)
        {
            $this->type = $type;
        }
        else
        {
            throw new Koch_Exception('Invalid formfield type specified. Choose one of ' . explode(',', $types));
        }

        return $this;
    }
}
?>