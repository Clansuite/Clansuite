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
    * @since      File available since Release 2.0alpha
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.');}

if (!class_exists('Clansuite_Datagrid_Col', false)) { require ROOT_CORE.'viewhelper/datagridcol.core.php'; }

/**
* Clansuite Datagrid Col Renderer
*
* String
*
* Purpose:
* Render string cells
*
* @author Florian Wolf <xsign.dll@clansuite.com>
*/
class Clansuite_Datagrid_Col_Renderer_Link
extends Clansuite_Datagrid_Col_Renderer_Base
implements Clansuite_Datagrid_Col_Renderer_Interface
{
    public $link            = '';
    public $linkFormat      = '&id=%{id}';
    public $linkId          = '';
    public $linkTitle       = '';
    public $nameWrapLength  = 50;
    public $nameFormat      = '%{name}';


    /**
    * Render the value(s) of a cell
    *
    * @param Clansuite_Datagrid_Cell
    * @return string Return html-code
    */
    public function renderCell($oCell)
    {
        # assign values to internal var
        $_Values = $oCell->getValues();

        # get the datagrid
        $_Datagrid = $oCell->getCol()->getDatagrid();

        # set internal link
        $this->link = $_Datagrid->getBaseURL();

        # validate
        if( !isset($_Values['name']) )
        {
            throw new Clansuite_Exception(_('A link needs a name. Please define "name" in the ResultKeys'));
        }
        else
        {
            if( strlen($_Values['name']) > $this->nameWrapLength )
            {
                $_Values['name'] = substr($_Values['name'],0,$this->nameWrapLength-3) . '...';
            }
        }

        # render
        return $this->_replacePlaceholders( $_Values,
                                            Clansuite_HTML::renderElement(  'a',
                                                                            $this->nameFormat,
                                                                            array(  'href'  => $_Datagrid->addToUrl($this->linkFormat),
                                                                                    'id'    => $this->linkId,
                                                                                    'title' => $this->linkTitle )));
    }


}

?>
