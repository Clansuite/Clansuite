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
    *
    * @author     Jens-Andr� Koch   <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-Andr� Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 2.0alpha
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.');}

if (!class_exists('Clansuite_Datagrid_Column', false)) { require ROOT_CORE.'viewhelper/datagridcol.core.php'; }

/**
* Clansuite Datagrid Col Renderer
*
* Date
*
* Purpose:
* Render date cells
*
* @author Florian Wolf <xsign.dll@clansuite.com>
*/
class Clansuite_Datagrid_Column_Renderer_Date extends Clansuite_Datagrid_Column_Renderer_Base implements Clansuite_Datagrid_Column_Renderer_Interface
{
    /**
    * Date format
    * Default: d.m.Y => 13.03.2007
    *
    * @var string
    */
    public $dateFormat = 'd.m.Y H:i';

    /**
    * Render the value(s) of a cell
    *
    * @todo How to format date? user-language? user-country?
    *
    * @param Clansuite_Datagrid_Cell
    * @return string Return html-code
    */
    public function renderCell($oCell)
    {
        #date_default_timezone_set('Europe/Berlin');

        $sDate = '';
        $oDatetime = date_create($oCell->getValue());
        if( $oDatetime !== false )
        {
            $sDate = $oDatetime->format($this->dateFormat);
        }
        return $sDate;
    }
}

?>
