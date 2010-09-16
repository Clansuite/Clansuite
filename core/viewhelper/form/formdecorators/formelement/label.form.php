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
 * Clansuite_Formelement_Decorator_Label
 *
 * Adds a <label> element containing the formelement label in-front of html_fromelement_content.
 *
 * @category Clansuite
 * @package Clansuite_Form
 * @subpackage Clansuite_Form_Decorator
 */
class Clansuite_Formelement_Decorator_Label extends Clansuite_Formelement_Decorator
{
    /**
     * Name of this decorator
     *
     * @var string
     */
    public $name = 'label';

    /**
     * renders label BEFORE formelement
     *
     * @todo if required form field add (*)
     */
    public function render($html_formelement)
    {
        # add label
        if ( $this->formelement->hasLabel() == true)
        {
            $html_formelement = CR . '<label>' . $this->formelement->getLabel() . '</label>'. CR . $html_formelement;
        }

        return $html_formelement;
    }
}
?>