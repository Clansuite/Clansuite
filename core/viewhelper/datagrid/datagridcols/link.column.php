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
 * Clansuite Datagrid Column Renderer Link
 *
 * Purpose: Render cells with a link
 *
 * @author Florian Wolf <xsign.dll@clansuite.com>
 */
class Clansuite_Datagrid_Column_Renderer_Link extends Clansuite_Datagrid_Column_Renderer_Base
implements Clansuite_Datagrid_Column_Renderer_Interface
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
        $values = $oCell->getValues();

        # set internal link
        $this->link = parent::getColumn()->getBaseURL();

        # validate
        if( false == isset($values['name']) )
        {
            throw new Clansuite_Exception(_('A link needs a name. Please define "name" in the ResultKeys'));
        }
        else
        {
            if( mb_strlen($values['name']) > $this->nameWrapLength )
            {
                $values['name'] = mb_substr($values['name'], 0, $this->nameWrapLength-3) . '...';
            }
        }

        # render
        return $this->_replacePlaceholders( $values,
                                            Clansuite_HTML::renderElement(  'a',
                                                                            $this->nameFormat,
                                                                            array(  'href'  => Clansuite_Datagrid::appendUrl($this->linkFormat),
                                                                                    'id'    => $this->linkId,
                                                                                    'title' => $this->linkTitle )));
    }
}
?>