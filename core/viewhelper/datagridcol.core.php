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
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

# conditional include of the parent class
if (false == class_exists('Clansuite_Datagrid_Base', false))
{ 
    include dirname(__FILE__) . '/datagrid.core.php';
}

# conditional include of the parent class
if (false ==  class_exists('Clansuite_HTML', false))
{ 
    include dirname(__FILE__) . '/html.core.php';
}

/**
 * Clansuite Datagrid Column
 *
 * Purpose: Defines a column of the datagrid
 *
 * @author Florian Wolf <xsign.dll@clansuite.com>
 */
class Clansuite_Datagrid_Column extends Clansuite_Datagrid_Base
{
    //--------------------
    // Class properties
    //--------------------

    /**
     * All cells of this column (array of references)
     *
     * @var array Clansuite_Datagrid_Cell
     */
    private $_cells = array();

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
    private $_position = 0;


    /**
     * Renderer for the cell
     *
     * @var object Clansuite_Datagrid_Column_Renderer
     */
    private $_renderer;


    /**
     * Boolean datagrid column values for configuration, wrapped into an array
     *
     * @var array
     */
    private $_features = array(
        'Sorting'       => true,
        'Search'        => true
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
        $this->_cells = $_Cells;
    }

    /**
     * Set the position
     *
     * @param int
     */
    public function setPosition($_Position)
    {
        $this->_position = $_Position;
    }

    /**
     * Set the renderer for the column
     *
     * @param mixed string|object Renderer Name|Clansuite_Datagrid_Column_Renderer
     */
    public function setRenderer($_Renderer)
    {
        if( $_Renderer instanceof Clansuite_Datagrid_Column_Renderer_Base )
        {
            $this->_renderer = $_Renderer;
        }
        else
        {
            $this->_renderer = $this->loadRenderer($_Renderer);
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
        return $this->_position;
    }

    /**
     * Get the renderer for the column
     *
     * @return Clansuite_Datagrid_Column_Renderer
     */
    public function getRenderer()
    {
        return $this->_renderer;
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
        if( !isset($this->_features[$feature]) )
        {
            throw new Clansuite_Exception(_('There is no such feature in this datagrid column: ') . $feature);
        }
        else
        {
            return $this->_features[$feature];
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
        if( false == isset($this->_features[$feature]) )
        {
            return false;
        }
        else
        {
            $this->_features[$feature] = true;
            return true;
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
        if( false == isset($this->_features[$feature]) )
        {
            return false;
        }
        else
        {
            $this->_features[$feature] = false;
            return true;
        }
    }


    /**
     * Add a cell reference to the col
     *
     * @param Clansuite_Datagrid_Cell
     */
    public function addCell($cell)
    {
        array_push($this->_cells, $cell);
    }

    /**
     * Load the renderer depending on a string (lowercased)
     * The method looks into the folder "datagridcols" and loads [$name].column.php
     *
     * @param string $rendererName The renderer name
     */
    private function loadRenderer($rendererName = 'string')
    {
        $rendererName = strtolower($rendererName);
        
        $className = 'Clansuite_Datagrid_Column_Renderer_' . ucfirst($rendererName);

        if(false == class_exists($className, false))
        {
            $file = ROOT_CORE . 'viewhelper/datagridcols/' . $rendererName . '.column.php';

            if( is_file($file) )
            {
                include $file;
                
                if(false == class_exists($className, false))
                {
                    throw new Clansuite_Exception(_('The column renderer class does not exist: ') . $className);
                }
            }
            else
            {
                throw new Clansuite_Exception(_('The column renderer file does not exist: ') . $file);
            }
        }

        #Clansuite_Debug::firebug('Loaded Column Renderer: ' . $className);
        return new $className($this);
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
interface Clansuite_Datagrid_Column_Renderer_Interface
{
    /**
     * Render the given cell of the column
     */
    public function renderCell($_Value);
}

/**
 * Base Class of Clansuite_Datagrid_Column_Renderers
 *
 * Purpose:
 * Provides standard methods for all Clansuite_Datagrid_Column_Renderers
 *
 * @author Florian Wolf <xsign.dll@clansuite.com>
 */
class Clansuite_Datagrid_Column_Renderer_Base extends Clansuite_Datagrid_Renderer
{
    /**
     * The column object
     *
     * @var object Clansuite_Datagrid_Column
     */
    private $_column;

    //---------------------
    // Setter
    //---------------------

    /**
     * Set the col object
     *
     * @param Clansuite_Datagrid_Column $column
     */
    public function setColumn($column)
    {
        $this->_column = $column;
    }

    //---------------------
    // Getter
    //---------------------

    /**
     * Get the column object
     *
     * @return Clansuite_Datagrid_Column
     */
    public function getColumn()
    {
        return $this->_column;
    }

    /**
     * Instantiate the Column Base
     *
     * @param Clansuite_Datagrid_Column
     */
    public function __construct($column)
    {
        $this->setColumn($column);
    }

    //---------------------
    // Class methods
    //---------------------

    /**
     * Replace placeholders with values
     *
     * @param array $values
     * @param string $format
     * @return string
     */
    public function _replacePlaceholders($values, $format)
    {
        $placeholders   = array();
        $replacements   = array();

        # search for placeholders %{...}
        preg_match_all('#%\{([^\}]+)\}#', $format, $placeholders, PREG_PATTERN_ORDER );

        # check if placeholders are used
        # @todo replace count() with check for first placeholder element: if(isset($_Placeholders[1][0]))
        #       and move count into the if
        $_PlacerholderCount = count($placeholders[1]);
        if( $_PlacerholderCount > 0 ) 
        {
            # loop over placeholders
            for($i=0;$i<$_PlacerholderCount;$i++)
            {
                if( isset($values[$placeholders[1][$i]]) )
                {
                    $replacements['%{' . $placeholders[1][$i] . '}'] = $values[$placeholders[1][$i]];
                }
            }
        }

        # return substituted string
        return strtr($format, $replacements);
    }
}
?>