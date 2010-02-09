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
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.');}

if (!class_exists('Clansuite_Formelement_Input',false)) { require dirname(__FILE__) . '/input.form.php'; }
if (!class_exists('Clansuite_Formelement_Submitbutton',false)) { require dirname(__FILE__) . '/submitbutton.form.php'; }
if (!class_exists('Clansuite_Formelement_Resetbutton',false)) { require dirname(__FILE__) . '/resetbutton.form.php'; }
if (!class_exists('Clansuite_Formelement_Cancelbutton',false)) { require dirname(__FILE__) . '/cancelbutton.form.php'; }

/**
 *  Clansuite_Formelement
 *  |
 *  \- Clansuite_Formelement_ButtonBar
 */
class Clansuite_Formelement_Buttonbar extends Clansuite_Formelement implements Clansuite_Formelement_Interface
{
    /**
    * Array of button objects
    *
    * @var array
    */
    private $_Buttons = array( "submitbutton"   => '',
                               "resetbutton"    => '',
                               "cancelbutton"   => '');


    /**
    * Set all buttons
    * Assoc array:
    * "String" => Clansuite_Formelement_*
    *
    * @example:
    *   $this->_Buttons['submitbutton'] = new Clansuite_Formelement_Submitbutton();
    * @param array $_Buttons
    */
    private function _setButtons($_Buttons)
    {
        $this->_Buttons = $_Buttons;
    }

    /**
    * Add a button to the bar
    *
    * @param string $_ButtonName
    * @param Clansuite_Formelement $_FormElement
    * @return Clansuite_Formelement
    */
    public function addButton($_ButtonName, $_FormElement = null)
    {
        if( $_FormElement === null )
        {
            if( class_exists('Clansuite_Formelement_' . $_ButtonName,false) )
            {
                $_ClassName = 'Clansuite_Formelement_' . $_ButtonName;
                $Obj = $this->_Buttons[$_ButtonName] = new $_ClassName();
            }
            elseif( is_file( dirname(__FILE__).DS.$_ButtonName.'form.php' ))
            {
                require(dirname(__FILE__).DS.$_ButtonName.'form.php');

                $_ClassName = 'Clansuite_Formelement_' . $_ButtonName;
                $Obj = $this->_Buttons[$_ButtonName] = new $_ClassName();
            }
        }
        else
        {
            if($_RelatedObject instanceof Clansuite_Formelement)
            {
                $Obj = $_FormElement;
            }
            else
            {
                throw new Clansuite_Exception(_('The button object you supplied seems not be an an instance of "Clansuite_Formelement": ') . $_ButtonName);
            }
        }

        return $Obj;
    }

    /**
    * Gets a button
    *
    * @param string $_ButtonName
    * @return Clansuite_Formelement
    */
    public function getButton($_ButtonName)
    {
        if( isset($this->_Buttons[$_ButtonName]) )
        {
            return $this->_Buttons[$_ButtonName];
        }
        else
        {
            throw new Clansuite_Exception(_('This button does not exist in this buttonbar: ') . $_ButtonName);
        }
    }

    /**
    * Remove a button from the stack
    *
    * @param string $_ButtonName
    * @return Clansuite_Formelement_Buttonbar
    */
    public function removeButton($_ButtonName)
    {
        if( isset($this->_Buttons[$_ButtonName]) )
        {
            unset($this->_Buttons[$_ButtonName]);
        }
        return $this;
    }

    /**
    * Adds the objects to the buttonnames fo the initial buttons array
    *
    * @return Clansuite_Formelement_Buttonbar
    */
    function __construct()
    {
        foreach($this->_Buttons as $_ButtonName => $_FormElement)
        {
            $this->addButton($_ButtonName);
        }
        $this->setClass('Buttonbar');
        return $this;
    }

    /**
    * Render all buttons and then itself
    */
    public function render()
    {
        $htmlString = '<div class="'.$this->getClass().'">';
        foreach($this->_Buttons as $_ButtonName => $_FormElement)
        {
            $htmlString .= $_FormElement->render();
        }
        $htmlString .= '</div>';
        return $htmlString;
    }

    public function __toString()
    {
        return $this->render();
    }
}
?>