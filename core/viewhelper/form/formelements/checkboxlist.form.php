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

# conditional include of the parent class
if (false == class_exists('Clansuite_Formelement_Checkbox',false))
{
    include dirname(__FILE__) . '/checkbox.form.php';
}

/**
 *  Clansuite_Formelement_Input
 *  |
 *  \ - Clansuite_Formelement_Checkbox
 *      |
 *      \- Clansuite_Formelement_Checkboxlist
 */
class Clansuite_Formelement_Checkboxlist extends Clansuite_Formelement_Checkbox implements Clansuite_Formelement_Interface
{
    public function getOptions()
    {
        $options = array( '1' => 'eins', '2' => 'zwei', '3' => 'drei', '4' => 'Polizei' );
        return $options;
    }

    public function render()
    {
        $html = '';

        foreach ($this->getOptions() as $key => $value)
        {
            $checkbox_element = new Clansuite_Formelement_Checkbox();
            $checkbox_element->setLabel($value);
            $checkbox_element->setName($value);
            $checkbox_element->setDescription($value);
            $checkbox_element->setValue($key);
            $html .= $checkbox_element;
        }

        return $html;
    }
}
?>