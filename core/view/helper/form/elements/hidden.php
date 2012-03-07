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

class Clansuite_Formelement_Hidden extends Clansuite_Formelement_Input implements Clansuite_Formelement_Interface
{
    public function __construct()
    {
        $this->type = 'hidden';

        /**
         * Convention: Default decorators are disabled on hidden formelements!
         *
         * When useDefaultDecorators() is activated on the form,
         * the default decorators would be applied to any formlement before rendering.
         * Rendering of a decorators, like e.g. the "label" decorator, rendering the "label" tag,
         * is unappreciated, because this element should obviously be "hidden".
         *
         * If you want a "non-default" decorator on this element, then use addDecorator().
         */
        $this->disableDefaultDecorators();
    }

    /**
     * Adds a hidden field for charset detection.
     *
     * @return string
     */
    public function addHiddenFieldForCharsetDetection()
    {
        return '<input name="_charset_" type="hidden" />';
    }

    /**
     * Assigns an array to a hidden field by combining the array into a string.
     * The separation char is ",".
     *
     * When the string is incomming via POST you need to explode it,
     * to get the array back. Like so:
     * $data_array = explode(',', $_POST['imploded_array']);
     *
     * The method sets the Name implicitly.
     *
     * @param string|array $value The data you want to pass through POST.
     */
    public function setValue($value)
    {
        $data = '';

        if(is_array($value))
        {
            # transform the array to a string by imploding it with comma
            $data = implode(',', $value);

            /**
             * Add imploded_array to the name.
             *
             * By appending the state of the array to the name, it's marked
             * to be exploded again, when incomming as $_POST data.
             */
            $this->setName('_imploded_array');
        }
        elseif((is_string($value) === true) or (is_numeric($value) === true))
        {
            $data = $value;
        }
        else
        {
            $msg = _('%s() only accepts array, string or numeric as $value. Your input was (%s) %s.');
            $msg = sprintf($msg, __METHOD__, gettype($value), $value);
            throw new InvalidArgumentException($msg);
        }

        $this->value = htmlspecialchars($data);

        unset($data);
    }

    /**
     * Proxy / Convenience Method for setName() and setValue() (a two in one call)
     *
     * @param type $name
     * @param string|array $value The data you want to pass through POST.
     */
    public function setData($name, $value)
    {
        $this->setName($name);
        $this->setValue($value);
    }
}
?>