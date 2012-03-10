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

namespace Koch\Datagrid\Column;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Datagrid Column Renderer for Edit Button Cells
 *
 * Renders an edit button.
 *
 * @author Florian Wolf <xsign.dll@clansuite.com>
 */
class Editbutton extends ColumnRenderer implements ColumnRendererInterface
{
    /**
    * Render the value(s) of a cell
    *
    * @param Clansuite_Datagrid_Cell
    * @return string Return html-code
    */
    public function renderCell($oCell)
    {
        $oImagebutton = new Clansuite_Formelement_Imagebutton();
        $oImagebutton->setName('Editbutton');
        $oImagebutton->setID('Editbutton-' . $oCell->getValue());
        $oImagebutton->setClass('DatagridEditbutton-' . $oCell->getColumn()->getAlias());
        $oImagebutton->setValue(_('Edit'));

        return $oImagebutton->render();

        #return sprintf('<input type="button" value="EditButton" id="EditButton-%s" name="EditButton" />', $oCell->getValue());
    }
}

?>
