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

if (!class_exists('Clansuite_Datagrid_Base', false)) { require 'datagrid.core.php'; }

/**
* Clansuite Datagrid Col
*
* Defines one single column for the datagrid
*
* @author Florian Wolf <xsign.dll@clansuite.com>
*/
class Clansuite_Datagrid_Col extends Clansuite_Datagrid_Base
{
    //--------------------
    // Class parameters
    //--------------------

    /**
    * All cells of this col (array of references)
    *
    * @var array Clansuite_Datagrid_Cell
    */
    private $_Cells = array();

    /**
    * The sortmode of the column
    *
    * @var string
    */
    private $_sortMode = 'DESC';

    /**
    * The sortfield of the column
    *
    * @var string
    */
    private $_sortField = '';

    /**
    * The position of a column
    *
    * @var int
    */
    private $_Position = 0;


    /**
    * Renderer for the cell
    *
    * @var object Clansuite_Datagrid_Col_Renderer
    */
    private $_Renderer;


    /**
    * Boolean datagrid column values for configuration, wrapped into an array
    *
    * @var array
    */
    private $_Features = array(
        'Sorting'       => true
    );

    //--------------------
    // Setter
    //--------------------

    /**
    * Set all row-cells
    *
    * @param array Clansuite_Datagrid_Cell
    */
    public function setCells($_Cells)
    {
        $this->_Cells = $_Cells;
    }

    /**
    * Set the position
    *
    * @param int
    */
    public function setPosition($_Position)
    {
        $this->_Position = $_Position;
    }

    /**
    * Set the renderer for the column
    *
    * @param mixed string|object Renderer Name|Clansuite_Datagrid_Col_Renderer
    */
    public function setRenderer($_Renderer)
    {
        if( $_Renderer instanceof Clansuite_Datagrid_Col_Renderer_Base )
        {
            $this->_Renderer = $_Renderer;
        }
        else
        {
            $this->_Renderer = $this->_loadRenderer($_Renderer);
        }
    }

    /**
    * Set the database sortfield
    *
    * @param string
    */
    public function setSortField($_sortField)
    {
        $this->_sortField = $_sortField;
    }

    /**
    * Set the sort-mode (ASC, DESC, NATASC, NETDESC, ...)
    *
    * @param string
    */
    public function setSortMode($_sortMode)
    {
        $this->_sortMode = $_sortMode;
    }

    //--------------------
    // Getter
    //--------------------

    /**
    * Get the position
    *
    * @return int
    */
    public function getPosition()
    {
        return $this->_Position;
    }

    /**
    * Get the renderer for the column
    *
    * @return Clansuite_Datagrid_Col_Renderer
    */
    public function getRenderer()
    {
        return $this->_Renderer;
    }

    /**
    * Get the sort-field
    *
    * @return string
    */
    public function getSortField()
    {
        return $this->_sortField;
    }

    /**
    * Get the sort-mode
    *
    * @return string
    */
    public function getSortMode()
    {
        return $this->_sortMode;
    }

    //--------------------
    // Class methods
    //--------------------

    /**
    * Check for datagrid column features
    *
    * @see $this->_features
    * @param string $feature
    * @return boolean
    */
    public function isEnabled($feature)
    {
        if( !isset($this->_Features[$feature]) )
        {
            throw new Clansuite_Exception(_('There is no such feature in this datagrid column: ') . $feature);
        }
        else
        {
            return $this->_Features[$feature];
        }
    }

    /**
    * Enable datagrid column features and return true if it succeeded, false if not
    *
    * @see $this->_features
    * @param string $feature
    * @return boolean
    */
    public function enableFeature($feature)
    {
        if( !isset($this->_Features[$feature]) )
        {
            return 0;
        }
        else
        {
            $this->_Features[$feature] = true;
            return 1;
        }
    }

    /**
    * Disable datagrid column features
    * Return true if succeeded, false if not
    *
    * @see $this->_features
    * @param mixed $feature
    * @return boolean
    */
    public function disableFeature($feature)
    {
        if( !isset($this->_Features[$feature]) )
        {
            return 0;
        }
        else
        {
            $this->_Features[$feature] = false;
            return 1;
        }
    }


    /**
    * Add a cell reference to the col
    *
    * @param Clansuite_Datagrid_Cell
    */
    public function addCell($_Cell)
    {
        array_push($this->_Cells, $_Cell);
    }

    /**
    * Load the renderer depending on a string (lowercased)
    * The method looks into the folder "datagridcols" and loads [$name].column.php
    *
    * @param string The renderer name
    */
    private function _loadRenderer($_RendererName = 'string')
    {
        $_FileLocation = ROOT_CORE . 'viewhelper' . DS . 'datagridcols' . DS . strtolower($_RendererName) . '.column.php';
        $_ClassName = 'Clansuite_Datagrid_Col_Renderer_' . ucfirst(strtolower($_RendererName));

        if(!class_exists($_ClassName, false))
        {
            if( is_file($_FileLocation) )
            {
                require $_FileLocation;
                if(!class_exists($_ClassName, false))
                {
                    throw new Clansuite_Exception(_('The column renderer class does not exist: ') . $_ClassName);
                }
                else
                {
                    #Clansuite_Xdebug::firebug('RENDERER: ' . $_ClassName);
                    return new $_ClassName($this);
                }
            }
            else
            {
                throw new Clansuite_Exception(_('The column renderer file does not exist: ') . $_FileLocation);
            }
        }
        else
        {
            return new $_ClassName($this);
        }
    }

    /**
    * Renders the column cell depanding on the renderer that is assigned to the column object
    * Default renderer: String
    *
    * @return string Returns html-code
    * @param Clansuite_Datagrid_Cell
    */
    public function renderCell($oCell)
    {
        return $this->getRenderer()->renderCell($oCell);
    }

}

/**
 * Interface for a Clansuite Datagrid Column Renderer
 */
interface Clansuite_Datagrid_Col_Renderer_Interface
{
    /**
    * Render the given cell of the column
    */
    public function renderCell($_Value);
}

/**
 * Interface for a Clansuite Datagrid Column Renderer
 */
class Clansuite_Datagrid_Col_Renderer_Base
{
    /**
    * The column object
    *
    * @var object Clansuite_Datagrid_Col
    */
    private $_Col;

    /**
    * Set the col object
    *
    * @param Clansuite_Datagrid_Col
    */
    public function setCol($_Col)
    {
        $this->_Col = $_Col;
    }

    /**
    * Get the column object
    *
    * @return Clansuite_Datagrid_Col
    */
    public function getCol()
    {
        return $this->_Col;
    }

    /**
    * Instantiate the Column Base
    *
    * @param Clansuite_Datagrid_Col
    * @return Clansuite_Datagrid_Col_Renderer_Base
    */
    public function __construct($_Col)
    {
        $this->setCol($_Col);
    }
}
?>
