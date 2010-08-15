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
if (defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

if (false === class_exists('Clansuite_Datagrid_Column', false))
{ 
    include ROOT_CORE.'viewhelper/datagridcol.core.php';
}

if (false === class_exists('Clansuite_Formelement_Checkbox', false))
{ 
    include ROOT_CORE.'viewhelper/formelements/checkbox.form.php';
}

/**
* Clansuite Datagrid Column Renderer Checkbox
*
* Purpose: Renders a checkbox
*
* @author Florian Wolf <xsign.dll@clansuite.com>
*/
class Clansuite_Datagrid_Column_Renderer_Checkbox extends Clansuite_Datagrid_Column_Renderer_Base implements Clansuite_Datagrid_Column_Renderer_Interface
{
    /**
    * Render the value(s) of a cell
    *
    * @param Clansuite_Datagrid_Cell
    * @return string Return html-code
    */
    public function renderCell($oCell)
    {
        $oCheckbox = new Clansuite_Formelement_Checkbox();
        $oCheckbox->setName('Checkbox[]');
        $oCheckbox->setID('Checkbox-' . $oCell->getValue());
        $oCheckbox->setValue($oCell->getValue());
        $oCheckbox->setClass('DatagridCheckbox DatagridCheckbox-' . $oCell->getColumn()->getAlias());

        return $oCheckbox->render();

        #return sprintf('<input type="checkbox" value="%s" id="Checkbox-%s" name="Checkbox[]" />', $oCell->getValue(), $oCell->getValue());
    }
}
?>