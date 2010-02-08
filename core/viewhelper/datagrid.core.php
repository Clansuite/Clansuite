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

if (!class_exists('Clansuite_Datagrid_Col', false)) { require dirname(__FILE__) . '/datagridcol.core.php'; }

/**
* Clansuite Datagrid Base
*
* Purpose:
* Supply methods for all datagrid-subclasses
*
* @author Florian Wolf <xsign.dll@clansuite.com>
*/
class Clansuite_Datagrid_Base
{
    // scoped vars
    private $_Alias;
    private $_Id;
    private $_Name;
    private $_Class;
    private $_Style;

    // Setters
    public function setAlias($_)    { $this->_Alias = $_; }
    public function setName($_)     { $this->_Name = $_; }
    public function setClass($_)    { $this->_Class = $_; }
    public function setId($_)       { $this->_Id = $_; }
    public function setStyle($_)    { $this->_Style = $_; }

    // Getters
    public function getAlias()      { return $this->_Alias; }
    public function getName()       { return $this->_Name; }
    public function getClass()      { return $this->_Class; }
    public function getId()         { return $this->_Id; }
    public function getStyle()      { return $this->_Style; }
}

/**
 * Clansuite Datagrid
 *
 * Purpose:
 * Automatic datagrid generation from doctrine records/tables.
 * Doctrine_Table => Doctrine_Query => Clansuite_Datagrid
 *
 * Clansuite_Datagrid:
 *
 * #--------------------------------------------------#
 * # Caption (<caption>Caption</caption>)             #
 * #--------------------------------------------------#
 * # Pagination (<tr><td>Pagination</td></tr>)        #
 * #--------------------------------------------------#
 * # Header (<tr><th>Col1</th><th>Col2</th></tr>)     #
 * #--------------------------------------------------#
 * # Rows (<tr><td>DataField</td></tr>)               #
 * #--------------------------------------------------#
 * # Pagination (<tr><td>Pagination</td></tr>)        #
 * #--------------------------------------------------#
 * # Footer (<tr><td>Footer</td></tr>)                #
 * #--------------------------------------------------#
 *
 *
 * @see http://www.doctrine-project.org/Doctrine_Table/1_2
 */
class Clansuite_Datagrid extends Clansuite_Datagrid_Base
{
    //--------------------
    // Class parameters
    //--------------------

    /**
    * The BaseURL for this datagrid
    * Every Link in the datagrid (sorting, pagination, etc.) has this URL as base
    *
    * @var string
    */
    private $_BaseURL   = 'index.php';

    /**
    * The Batchactions for this grid (edit, delete, move, ...)
    *
    * @var array
    */
    private $_BatchActions = array();

    /**
    * Object which called
    *
    * @var object
    */
    private $_Caller;

    /**
    * The caption (<caption>...</caption>) for this datagrid
    *
    * @var string
    */
    private $_Caption   = 'Caption';

    /**
    * Array of Clansuite_Datagrid_Cell objects
    *
    * @var array
    */
    private $_Cells     = array();

    /**
    * Amount of columns
    *
    * @var integer
    */
    private $_ColCount  = 0;

    /**
    * Array of Clansuite_Datagrid_Col objects
    *
    * @var array
    */
    private $_Cols      = array();

    /**
    * An array of sets (arrays) to configure the columns
    *
    * @var array
    */
    private $_ColumnSets = array();

    /**
    * The datagrid type
    *
    * @todo implement
    * @var string
    */
    private $_DatagridType  = 'Normal';

    /**
    * Array of datasets
    * Usually this will be a doctrine-dataset
    *
    * @var array
    */
    private $_Datasets = array();

    /**
    * Doctrine Datatable object
    *
    * @var Doctrine_Datatable
    */
    private $_Datatable;

    /**
    * The description for this datagrid
    *
    * @var string
    */
    private $_Description   = 'This is a clansuite datagrid';

    /**
    * Boolean datagrid values for configuration, wrapped into an array
    *
    * @var array
    */
    private $_Features = array(
        'BatchActions'  => true,
        'Caption'       => true,
        'Description'   => true,
        'Footer'        => true,
        'Header'        => true,
        'Label'         => true,
        'Pagination'    => true,
        'Search'        => true,
        'Sorting'       => true
    );

    /**
    * Maps internal variables to incoming parameters
    * Default URL: ?sk=Title&sv=DESC&p=2&rpp=10
    *
    * @var array
    */
    private $_InputMapping = array( 'SortKey'           => 'sk',
                                    'SortValue'         => 'sv',
                                    'Page'              => 'p',
                                    'ResultsPerPage'    => 'rpp',
                                    'SearchValue'       => 'searchvalue',
                                    'SearchKey'         => 'searchkey',
                                    'Reset'             => 'reset' );

    /**
    * The label for this datagrid
    *
    * @var string
    */
    private $_Label     = 'Label';

    /**
    * Doctrine Pager Layout object
    *
    * @link http://www.doctrine-project.org/documentation/manual/1_2/en/utilities#pagination:working-with-pager Doctrine_Pager_Layout
    * @var Doctrine_Pager_Layout
    */
    private $_PagerLayout;

    /**
    * Doctrine Query object
    *
    * @link http://www.doctrine-project.org/documentation/manual/1_2/en/dql-doctrine-query-language DQL (Doctrine Query Language)
    * @var Doctrine_Query
    */
    private $_Query;

    /**
    * Doctrine Named Query
    *
    * @example
    * Within the DataTable:
    * function construct()
    * {
    *   $this->addNamedQuery(...)
    * }
    *
    * @link http://www.doctrine-project.org/documentation/manual/1_2/en/dql-doctrine-query-language#named-queries Named Queries
    * @var string
    */
    private $_QueryName = 'fetchAll';

    /**
    * The renderer for the datagrid
    *
    * @var Clansuite_Datagrid_Renderer
    */
    private $_Renderer;

    /**
    * Methodname to call on the resultset
    *
    * @var string
    */
    private $_ResultSetHook;

    /**
    * Array of Clansuite_Datagrid_Row objects
    *
    * @var array
    */
    private $_Rows      = array();

    /**
    * Associated sortables
    *
    * @var array
    */
    private $_SortReverseDefinitions = array(
                      'ASC'     => 'DESC',
                      'DESC'    => 'ASC',
                      'NATASC'  => 'NATDESC',
                      'NATDESC' => 'NATASC'
    );


    //--------------------
    // Setter
    //--------------------

    public function setLabel($_Label)               { $this->_Label = $_Label; }
    public function setCaption($_Caption)           { $this->_Caption = $_Caption; }
    public function setDescription($_Description)   { $this->_Description = $_Description; }

    /**
    * Set the baseurl and modify string
    *
    * @param string $_BaseURL
    */
    private function _setBaseURL($_BaseURL)
    {
        $this->_BaseURL = preg_replace('#&(?!amp;)#i', '&amp;', $_BaseURL);
    }

    /**
    * Set the batchactions
    *
    * @param array $_BatchActions
    */
    public function setBatchActions( $_BatchActions )
    {
        $this->_BatchActions = $_BatchActions;
    }

    /**
    * Sets the column objects of the grid (Clansuite_Datagrid_Col)
    *
    * @param array $_Cols
    */
    public function setCols($_Cols)
    {
        $this->_Cols = $_Cols;
    }

    /**
    * Set the Datatable of the generator
    *
    * @param Doctrine_Datatable $_Datatable
    */
    public function setDatatable(Doctrine_Table $_Datatable)
    {
        $this->_Datatable = $_Datatable;
        $this->_updateDatagrid();
    }

    /**
    * Set the doctrine pager layout object
    *
    * @param Doctrine_Pager_Layout $_PagerLayout
    */
    public function setPagerLayout(Doctrine_Pager_Layout $_PagerLayout)
    {
        $this->_PagerLayout = $_PagerLayout;
    }

    /**
    * Set the renderer object
    *
    * @param Clansuite_Datagrid_Renderer $_Renderer
    */
    public function setRenderer(Clansuite_Datagrid_Renderer $_Renderer)
    {
        $this->_Renderer = $_Renderer;
    }

    /**
    * Set the hook for the resultset manipulation
    *
    * @param Object $_Caller
    * @param Methodname $_Methodname
    */
    public function setResultSetHook($_Caller, $_Methodname)
    {
        $this->_Caller          = $_Caller;
        $this->_ResultSetHook   = $_Methodname;
    }

    /**
    * Sets the row objects of the grid (Clansuite_Datagrid_Row)
    *
    * @param array $_Rows
    */
    public function setRows($_Rows)
    {
        $this->_Rows = $_Rows;
    }

    /**
    * Sets the queryname and updates the datagrid
    *
    * @param string $_QueryName
    */
    public function setQueryName($_QueryName)
    {
        $this->_QueryName = $_QueryName;
        $this->_updateDatagrid();
    }

    //--------------------
    // Getter
    //--------------------

    public function getLabel()          { return $this->_Label; }
    public function getCaption()        { return $this->_Caption; }
    public function getDescription()    { return $this->_Description; }
    public function getBaseURL()        { return $this->_BaseURL; }
    public function getBatchActions()   { return $this->_BatchActions; }

    /**
    * Get a column depending on its alias
    *
    * @return Clansuite_Datagrid_Col
    * @param string $sColKey
    */
    public function getCol($sColKey)     { return $this->_Cols[$sColKey]; }

    /**
    * Amount of columns in the grid
    *
    * @return integer Amount of columns
    */
    public function getColCount()       { return count($this->getCols()); }

    /**
    * Returns the column objects (Clansuite_Datagrid_Col)
    *
    * @return array
    */
    public function getCols()           { return $this->_Cols; }

    /**
    * Get the Datatable of the generator
    *
    * @return Doctrine_Table
    */
    public function getDatatable()
    {
        return $this->_Datatable;
    }

    /**
    * Get the input parameter depending on the mapping
    *
    * @param string $_InternalKey
    */
    public function getInputParameterName($_InternalKey)
    {
        if( !isset($this->_InputMapping[$_InternalKey]) )
        {
            throw new Clansuite_Exception(_('This internal key is not know to private array $_InputMapping: ') . $_InternalKey);
        }
        return $this->_InputMapping[$_InternalKey];
    }

    /**
    * Get the pager layout object
    *
    * @return Doctrine_Pager_Layout
    */
    public function getPagerLayout()    { return $this->_PagerLayout; }

    /**
    * Get the renderer object
    *
    * @return Clansuite_Datagrid_Renderer
    */
    public function getRenderer()       { return $this->_Renderer; }

    /**
    * Returns the row objects (Clansuite_Datagrid_Row)
    *
    * @return array
    */
    public function getRows()           { return $this->_Rows; }

    /**
    * Get the pendant to DESC/ASC
    *
    * @return string
    */
    public function getSortReverseDefinition($_sortMode)
    {
        if( !isset($this->_SortReverseDefinitions[$_sortMode]) )
        {
            throw new Clansuite_Exception(_('This sortMode is not in the list of private var $_SortReverseDefinitions: ') . $_sortMode );
        }

        return $this->_SortReverseDefinitions[$_sortMode];
    }


    //--------------------
    // Class methods
    //--------------------

    /**
    * Constructor
    *
    * @param array Options (Datatable, NamedQuery, ColumnSets, BaseURL)
    */
    public function __construct($_Options)
    {
        if( !($_Options['Datatable'] instanceof Doctrine_Table) )
        {
            throw new Clansuite_Exception(_('Incoming data seems not to be a valid Doctrine_Table'));
        }

        # attach Datagrid to renderer
        $this->setRenderer(new Clansuite_Datagrid_Renderer($this));

        # set the internal ref for the datatable of doctrine
        $this->_Datatable = $_Options['Datatable'];

        # Set all columns
        $this->_setColumnSets($_Options['ColumnSets']);

        # set queryname
        $this->_QueryName = $_Options['NamedQuery'];

        # set baseurl
        $this->_setBaseURL($_Options['BaseURL']);

        # generate default datasets that can be overwritten
        $this->_initDatagrid();
    }

    /**
    * Initialize the datagrid
    */
    private function _initDatagrid()
    {
        # set scalar values
        $this->setAlias($this->_Datatable->getClassnameToReturn());

        # reset session?
        if( isset($_REQUEST[$this->getInputParameterName('Reset')]) )
        {
               $_SESSION['Datagrid_' . $this->getAlias()] = '';
        }

        $this->setId('DatagridId-' . $this->getAlias());
        $this->setName('DatagridName-' . $this->getAlias());
        $this->setClass('Datagrid-' . $this->getAlias());
        $this->setLabel($this->getAlias());
        $this->setCaption($this->getAlias());
        $this->setDescription(_('This is the datagrid of ') . $this->getAlias());

        # generate the columns
        $this->_generateCols();
    }

    /**
    * Execute the datagrid (query, pager, cols, rows, everything!)
    */
    public function execute()
    {
        # generate the doctrine query
        $this->_generateQuery();

        # execute the doctrine-query
        $this->_Datasets = $this->getPagerLayout()->getPager()->execute();

        # Debug
        Clansuite_Xdebug::firebug($this->_Datasets);

        # update the current page
        $this->getRenderer()->setCurrentPage($this->getPagerLayout()->getPager()->getPage());

        # generate the data-rows
        $this->_generateRows($this->_Datasets);
    }

    /**
    * Check for datagrid features
    *
    * @see $this->_features
    * @param string $feature
    * @return boolean
    */
    public function isEnabled($feature)
    {
        if( !isset($this->_Features[$feature]) )
        {
            throw new Clansuite_Exception(_('There is no such feature in this datagrid: ') . $feature);
        }
        else
        {
            return $this->_Features[$feature];
        }
    }

    /**
    * Enable datagrid features and return true if it succeeded, false if not
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
    * Disable datagrid features
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
    * Render the datagrid
    * Sends the renderer the command to procede with rendering
    *
    * @return string $_html Returns html-code
    */
    public function render()
    {
        return $this->getRenderer()->render();
    }

    /**
    * Add an url-string to the baseurl
    *
    * @param string
    * @todo move complete method into router.core.php
    * @example
    *   $sUrl = $this->addToUrl('dg_Sort=0:ASC');
    */
    public function addToUrl($_AppendString)
    {
        $StartSeparator = '?';
        if( preg_match('#\?#', $this->getBaseURL()) )
        {
            $StartSeparator = '&amp;';
        }

        $_CleanAppendString = preg_replace('#^&amp;#', '', $_AppendString);
        $_CleanAppendString = preg_replace('#^&#', '', $_CleanAppendString);
        $_CleanAppendString = preg_replace('#^\?#', '', $_CleanAppendString);
        $_CleanAppendString = preg_replace('#&(?!amp;)#i', '&amp;', $_CleanAppendString);

        return $this->getBaseURL() . $StartSeparator . $_CleanAppendString;
    }

    /**
    * Set the Datatable of the generator
    *
    * @param Doctrine_Table $Datatable
    */
    private function _updateDatagrid()
    {
        # generate a doctrine query
        $this->_generateQuery();
    }

    /**
    * Set Datagrid Cols (internally without auto-update)
    *
    * @params array Columns Array
    */
    private function _setColumnSets($_ColumnSets = array())
    {
        foreach( $_ColumnSets as $key => $colSet )
        {
            # No alias given
            if( !isset($colSet['Alias']) || $colSet['Alias'] == '' )
            {
                throw new Clansuite_Exception(sprintf(_('The datagrid columnset has an error at key %s (arraykey "Alias" is missing)'), $key));
            }

            # No resultset given
            if( !isset($colSet['ResultSet']) || $colSet['ResultSet'] == '' )
            {
                throw new Clansuite_Exception(sprintf(_('The datagrid columnset has an error at key %s (arraykey "ResultSet" is missing)'), $key));
            }

            # No name given
            if( !isset($colSet['Name']) || $colSet['Name'] == '')
            {
                throw new Clansuite_Exception(sprintf(_('The datagrid columnset has an error at key %s (arraykey "Name" is missing)'), $key));
            }

            # No SortCol although sorting is enabled
            if( isset($colSet['Sort']) AND !isset($colSet['SortCol']) )
            {
                throw new Clansuite_Exception(sprintf(_('The datagrid columnset has an error at key %s (sorting is enabled but "SortCol" is missing)'), $key));
            }

            # Default type: String
            if( !isset($colSet['Type']) || $colSet['Type'] == '')
            {
                $colSet['Type'] = 'String';
            }
        }

        # Everything validates
        $this->_ColumnSets = $_ColumnSets;
    }

    /**
    * Set Datagrid Cols (internally without auto-update)
    *
    * @see $this->_setColumnSets();
    * @params array Columns Array
    */
    public function setColumnSets($_ColumnSets = array())
    {
        $this->_setColumnSets($_ColumnSets);
        $this->_updateDatagrid();
    }

    /**
    * Get the datagrid column sets from initial configuration
    *
    * @return array
    */
    public function getColumnSets()
    {
        return $this->_ColumnSets;
    }

    /**
     * Generates all col objects
     */
    private function _generateCols()
    {
        foreach( $this->_ColumnSets as $colKey => &$colSet )
        {
            $oCol = new Clansuite_Datagrid_Col($this);
            $oCol->setAlias($colSet['Alias']);
            $oCol->setId($colSet['Alias']);
            $oCol->setName($colSet['Name']);
            if( !isset($colSet['Sort']) )
            {
                $oCol->disableFeature('Sorting');
            }
            else
            {
                $oCol->setSortMode($colSet['Sort']);
                if( isset($colSet['SortCol']) )
                {
                    $oCol->setSortField($colSet['SortCol']);
                }
                else
                {
                    $oCol->setSortField($colSet['ResultSet']);
                }
            }
            $oCol->setPosition($colKey);
            $oCol->setRenderer($colSet['Type']);
            $this->_Cols[$colSet['Alias']] = $oCol;
        }
    }

    /**
     * Generates all row objects
     *
     * @param array Results-array from doctrine named query
     */
    private function _generateRows($_Datasets)
    {
        foreach( $_Datasets as $dataKey => $dataSet )
        {
            # Hook
            if( isset($this->_ResultSetHook) )
            {
                #Clansuite_Xdebug::firebug($dataSet);
                $this->_Caller->{$this->_ResultSetHook}($dataSet);
            }

            $oRow = new Clansuite_Datagrid_Row($this);
            $oRow->setAlias('Row_' . $dataKey);
            $oRow->setId('RowId_' . $dataKey);
            $oRow->setName('RowName_' . $dataKey);
            $oRow->setPosition($dataKey);

            foreach( $this->_ColumnSets as $colKey => $colSet )
            {
                $oCell = new Clansuite_Datagrid_Cell();
                $oRow->addCell($oCell);
                $oCol = $this->_Cols[$colSet['Alias']];
                $oCol->addCell($oCell);

                $this->_Rows[$dataKey] = $oRow;

                $oCell->setCol($oCol);
                $oCell->setRow($oRow);

                $_Values = $this->_getCellValues($colSet, $dataSet);

                if( $_Values !== false )
                {
                    $oCell->setValues($_Values);
                }
                else
                {
                    # Set empty values if not in dataset
                    $oCell->setValues(array());
                }
            }
        }
    }

    /**
    * Get the value of the columnset via dbSets of the doctrine query
    *
    * @param array The ColumnSet for this column
    * @param array The dbSet for this column
    * @return string The records value
    */
    private function _getCellValues(&$_ColumnSet, &$_Dataset)
    {
        if( !is_array($_ColumnSet) )
        {
            throw new Clansuite_Exception(_('You have not supplied any columnset to validate.'));
        }

        $_Values = array();

        # Standard for ResultSets is an array
        if( !is_array($_ColumnSet['ResultSet']) )
        {
            $aResultSet = array($_ColumnSet['ResultSet']);
        }
        else
        {
            $aResultSet = $_ColumnSet['ResultSet'];
        }

        $i = 0;

        foreach($aResultSet as $ResultKey => $ResultValue)
        {
            $_ArrayStructure = explode('.', $ResultValue);

            $_TmpArrayHandler = $_Dataset;
            foreach( $_ArrayStructure as $_LevelKey )
            {
                if( !is_array($_TmpArrayHandler) OR !isset($_TmpArrayHandler[$_LevelKey]) )
                {
                    Clansuite_Xdebug::firebug('ResultSet not found in Dataset: ' . $ResultValue, 'warn');
                    $_TmpArrayHandler = '';
                    break;
                }
                $_TmpArrayHandler = $_TmpArrayHandler[$_LevelKey];
            }

            $_Values[$i] = $_TmpArrayHandler;
            $_Values[$ResultKey] = $_TmpArrayHandler;
            $i++;
        }
        return $_Values;
    }

    /**
     * Generates a customized query for this table
     */
    private function _generateQuery()
    {
        if( isset($this->_QueryName) )
        {
            $_Query = $this->getDatatable()->createNamedQuery($this->_QueryName);
        }
        else
        {
            $_Query = $this->getDatatable()->createQuery()
                                ->select('*')
                                ->setHydrationMode(Doctrine::HYDRATE_ARRAY);
        }

        $this->_Query = $_Query;

        $this->_generateQuerySorts();
        $this->_generateQuerySearch();
        $this->_generatePagerLayout();
    }

    /**
    * Generate the Sorts for a query
    */
    private function _generateQuerySorts()
    {
        $SortKey    = '';
        $SortValue  = '';

        # Set sortkey and sortvalue if in session
        if( isset($_SESSION['Datagrid_' . $this->getAlias()]['SortKey']) AND isset($_SESSION['Datagrid_' . $this->getAlias()]['SortValue']) )
        {
            $SortKey    = $_SESSION['Datagrid_' . $this->getAlias()]['SortKey'];
            $SortValue  = $_SESSION['Datagrid_' . $this->getAlias()]['SortValue'];
        }

        # Prefer requests
        if( isset($_REQUEST[$this->_InputMapping['SortKey']]) AND isset($_REQUEST[$this->_InputMapping['SortValue']]) )
        {
            $SortKey    = $_REQUEST[$this->_InputMapping['SortKey']];
            $SortValue  = $_REQUEST[$this->_InputMapping['SortValue']];
        }

        # Check for valid formats of key and value
        if( ($SortKey != '' AND $SortValue != '') AND
            ( preg_match('#^([0-9a-z_]+)#i', $SortKey) AND preg_match('#([a-z]+)$#i', $SortValue) ) )
        {
            $_SESSION['Datagrid_' . $this->getAlias()]['SortKey']   = $SortKey;
            $_SESSION['Datagrid_' . $this->getAlias()]['SortValue'] = $SortValue;
            $this->getRenderer()->setCurrentSortKey($SortKey);
            $this->getRenderer()->setCurrentSortValue($SortValue);

            $oCol = $this->getCol($SortKey);
            $this->_Query->orderBy($oCol->getSortField() . ' ' . $SortValue);
            $oCol->setSortMode($SortValue);
        }
        else
        {
            $_SESSION['Datagrid_' . $this->getAlias()]['SortKey']   = '';
            $_SESSION['Datagrid_' . $this->getAlias()]['SortValue'] = '';
        }
    }

    /**
    * Generate the Serach for the query
    */
    private function _generateQuerySearch()
    {
        $SearchKey    = '';
        $SearchValue  = '';

        # Set sortkey and sortvalue if in session
        if( isset($_SESSION['Datagrid_' . $this->getAlias()]['SearchKey']) AND isset($_SESSION['Datagrid_' . $this->getAlias()]['SearchValue']) )
        {
            $SearchKey    = $_SESSION['Datagrid_' . $this->getAlias()]['SearchKey'];
            $SearchValue  = $_SESSION['Datagrid_' . $this->getAlias()]['SearchValue'];
        }

        # Prefer requests
        if( isset($_REQUEST[$this->_InputMapping['SearchKey']]) AND isset($_REQUEST[$this->_InputMapping['SearchValue']]) )
        {
            $SearchKey    = $_REQUEST[$this->_InputMapping['SearchKey']];
            $SearchValue  = $_REQUEST[$this->_InputMapping['SearchValue']];
        }

        $_SESSION['Datagrid_' . $this->getAlias()]['SearchKey']     = $SearchKey;
        $_SESSION['Datagrid_' . $this->getAlias()]['SearchValue']   = $SearchValue;

        # Check for valid formats of key and value
        if( ($SearchKey != '' AND $SearchValue != '') )
        {
            $this->_Query->andWhere($this->getCol($SearchKey)->getSortField() .' LIKE ?', array('%' . $SearchValue . '%') );
        }
    }

    /**
    * Generate the PagerLayout for a query
    */
    private function _generatePagerLayout()
    {
        # Read session
        if( isset($_SESSION['Datagrid_' . $this->getAlias()]['Page']) )
        {
            $_Page = $_SESSION['Datagrid_' . $this->getAlias()]['Page'];
        }
        else
        {
            $_Page = 1;
        }

        if( isset($_SESSION['Datagrid_' . $this->getAlias()]['ResultsPerPage']) )
        {
            $_ResultsPerPage = $_SESSION['Datagrid_' . $this->getAlias()]['ResultsPerPage'];
        }
        else
        {
            $_ResultsPerPage = 10;
        }

        # Add to session
        if( isset($_REQUEST[$this->_InputMapping['Page']]) )
        {
            $_Page = (int) $_REQUEST[$this->_InputMapping['Page']];
            $_SESSION['Datagrid_' . $this->getAlias()]['Page'] = $_Page;
        }

        if( isset($_REQUEST[$this->_InputMapping['ResultsPerPage']]) )
        {
            #Clansuite_Xdebug::firebug('ResultsPerPage:' . $_ResultsPerPage);
            $_ResultsPerPage = (int) $_REQUEST[$this->_InputMapping['ResultsPerPage']];
            $_SESSION['Datagrid_' . $this->getAlias()]['ResultsPerPage'] = $_ResultsPerPage;
        }

        # Add to renderer
        $this->getRenderer()->setCurrentPage($_Page);
        $this->getRenderer()->setCurrentResultsPerPage($_ResultsPerPage);

        $this->setPagerLayout( new Doctrine_Pager_Layout(
                                    new Doctrine_Pager(
                                        $this->_Query,
                                        $_Page,
                                        $_ResultsPerPage
                                    ),
                                    new Doctrine_Pager_Range_Sliding(array(
                                        'chunk' => 5
                                    )),
                                    $this->addToUrl('?' . $this->_InputMapping['Page'] . '={%page}')
                              ) );

        # Set the layout of the pager links
        # '[<a href="{%url}">{%page}</a>]'
        $this->getPagerLayout()->setTemplate($this->getRenderer()->getPagerLinkLayoutString());

        # Set the layout of the pager
        # '[{%page}]'
        $this->getPagerLayout()->setSelectedTemplate($this->getRenderer()->getPagerLayoutString());
    }
}

/**
* Clansuite Datagrid Row
*
* Purpose:
* Defines one single row for the datagrid
*
* @author Florian Wolf <xsign.dll@clansuite.com>
*/
class Clansuite_Datagrid_Row extends Clansuite_Datagrid_Base
{
    //--------------------
    // Class parameters
    //--------------------

    /**
    * All cells of this row
    *
    * @var array Clansuite_Datagrid_Cell
    */
    private $_Cells = array();

    /**
    * The datagrid
    *
    * @var Clansuite_Datagrid $_Datagrid
    */
    private $_Datagrid;

    /**
    * The position of a column
    *
    * @var int
    */
    private $_Position = 0;

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

    //--------------------
    // Getter
    //--------------------

    /**
    * Get the cells for this row
    *
    * @return array Clansuite_Datagrid_Cell
    */
    public function getCells()
    {
        return $this->_Cells;
    }

    /**
    * Set the position
    *
    * @return int
    */
    public function getPosition()
    {
        return $this->_Position;
    }

    //--------------------
    // Class methods
    //--------------------

    /**
    * Add a cell to the row
    *
    * @param Clansuite_Datagrid_Cell
    */
    public function addCell(&$_Cell)
    {
        array_push($this->_Cells, $_Cell);
    }
}

/**
* Clansuite Datagrid Cell
*
* Purpose:
* Defines a cell within the datagrid
*
* @author Florian Wolf <xsign.dll@clansuite.com>
*/
class Clansuite_Datagrid_Cell extends Clansuite_Datagrid_Base
{
    //----------------------
    // Class parameters
    //----------------------

    /**
    * Value(s) of the cell
    * $_Values[0] is the standard value returned by getValue()
    *
    * @var array Mixed values
    */
    private $_Values = array();

    /**
    * Column object (Clansuite_Datagrid_Col)
    *
    * @var object Clansuite_Datagrid_Col
    */
    private $_Col;

    /**
    * Row object (Clansuite_Datagrid_Row)
    *
    * @var object Clansuite_Datagrid_Row
    */
    private $_Row;

    //----------------------
    // Setter
    //----------------------

    /**
    * Set the column object of this cell
    *
    * @param Clansuite_Datagrid_Col $_Col
    */
    public function setCol($_Col)      { $this->_Col = $_Col; }

    /**
    * Set the datagrid object
    *
    * @param Clansuite_Datagrid $_Datagrid
    */
    public function setDatagrid($_Datagrid)
    {
        $this->_Datagrid = $_Datagrid;
    }

    /**
    * Set the row object of this cell
    *
    * @param Clansuite_Datagrid_Row $_Row
    */
    public function setRow($_Row)      { $this->_Row = $_Row; }

    /**
    * Set the value of the cell
    *
    * @param mixed A single value ($_Value[0])
    */
    public function setValue($_Value)    { $this->_Values[0] = $_Value; }

    /**
    * Set the values of the cell
    *
    * @param array Array of values
    */
    public function setValues($_Values)    { $this->_Values = $_Values; }

    //----------------------
    // Getter
    //----------------------

    /**
    * Returns the column object of this cell
    *
    * @return Clansuite_Datagrid_Col $_Col
    */
    public function getCol()    { return $this->_Col; }

    /**
    * Get the Datagrid object
    *
    * @return Clansuite_Datagrid $_Datagrid
    */
    public function getDatagrid()
    {
        return $this->_Datagrid;
    }

    /**
    * Returns the row object of this cell
    *
    * @return Clansuite_Datagrid_Row $_Row
    */
    public function getRow()    { return $this->_Row; }


    /**
    * Returns the value of this cell
    *
    * @return mixed
    */
    public function getValue()  { return $this->_Values[0]; }

    /**
    * Returns the values of this cell
    *
    * @return array
    */
    public function getValues()  { return $this->_Values; }

    //----------------------
    // Class methods
    //----------------------

    /**
    * Render the value
    *
    * @return string Returns the value (maybe manipulated by column cell renderer)
    */
    public function render()
    {
        return $this->getCol()->renderCell($this);
    }
}

/**
* Clansuite Datagrid Renderer
*
* Purpose:
* Generates html-code based upon the grid settings
*
* @author Florian Wolf <xsign.dll@clansuite.com>
*/
class Clansuite_Datagrid_Renderer
{
    //----------------------
    // Class parameters
    //----------------------

    /**
    * Holds the current page
    *
    * @var integer
    */
    private $_CurrentPage;

    /**
    * Holds the current results per page
    *
    * @var integer
    */
    private $_CurrentResultsPerPage;

    /**
    * Holds the current sort key
    *
    * @var string
    */
    private $_CurrentSortKey;

    /**
    * Holds the current sort value
    *
    * @var string
    */
    private $_CurrentSortValue;

    /**
    * The datagrid
    *
    * @var Clansuite_Datagrid $_Datagrid
    */
    private $_Datagrid;

    /**
    * The PagerLayout of the datagrid
    *
    * @link http://www.doctrine-project.org/documentation/manual/1_2/en/utilities#pagination:customizing-pager-layout Customizing Pager Layout
    * @var string
    */
    private $_PagerLayoutString = '<span class="PagerItem Active">{%page}</span>';

    /**
    * The look of the links of the pager
    *
    * @link http://www.doctrine-project.org/documentation/manual/1_2/en/utilities#pagination:customizing-pager-layout Customizing Pager Layout
    * @var string
    */
    private $_PagerLinkLayoutString = '<a href="{%url}"><span class="PagerItem Inactive">{%page}</span></a>';

    //----------------------
    // Class methods
    //----------------------

    /**
    * Instantiate renderer and attack Datagrid to it
    *
    * @param Clansuite_Datagrid_Datagrid $_Datagrid
    * @return Clansuite_Datagrid_Renderer
    */
    public function __construct($_Datagrid)
    {
        $this->setDatagrid($_Datagrid);
    }

    //----------------------
    // Setter
    //----------------------

    /**
    * Sets the current page
    *
    * @param int
    */
    public function setCurrentPage($_Page)                      { $this->_CurrentPage = $_Page; }

    /**
    * Sets the current results per page
    *
    * @param int
    */
    public function setCurrentResultsPerPage($_Value)           { $this->_CurrentResultsPerPage = $_Value; }

    /**
    * Sets the current sortkey
    *
    * @param string
    */
    public function setCurrentSortKey($_SortKey)                { $this->_CurrentSortKey = $_SortKey; }

    /**
    * Sets the current sortkey
    *
    * @param string
    */
    public function setCurrentSortValue($_SortValue)            { $this->_CurrentSortValue = $_SortValue; }

    /**
    * Set the datagrid object
    *
    * @param Clansuite_Datagrid $_Datagrid
    */
    public function setDatagrid($_Datagrid)                     { $this->_Datagrid = $_Datagrid; }

    /**
    * Set the pager layout
    *
    * @see $_PagerLayoutString
    * @param string
    * @example
    *   $oDatagrid->getRenderer()->setPagerLayout('[{%page}]');
    */
    public function setPagerLayoutString($_PagerLayout)         { $this->_PagerLayout = $_PagerLayout; }

    /**
    * Set the pager link layout
    *
    * @see $_PagerLinkLayoutString
    * @param string
    * @example
    *   $oDatagrid->getRenderer()->setPagerLinkLayout('[<a href="{%url}">{%page}</a>]');
    */
    public function setPagerLinkLayoutString($_PagerLinkLayout) { $this->_PagerLinkLayout = $_PagerLinkLayout; }

    //----------------------
    // Getter
    //----------------------

    /**
    * Returns the current page
    *
    * @return int
    */
    public function getCurrentPage()                { return $this->_CurrentPage; }

    /**
    * Gets the current results per page
    *
    * @return int
    */
    public function getCurrentResultsPerPage()      { return $this->_CurrentResultsPerPage; }

    /**
    * Gets the current sortkey
    *
    * @return string
    */
    public function getCurrentSortKey()             { return $this->_CurrentSortKey; }

    /**
    * Gets the current sortvalue
    *
    * @return string
    */
    public function getCurrentSortValue()           { return $this->_CurrentSortValue; }

    /**
    * Get the Datagrid object
    *
    * @return Clansuite_Datagrid $_Datagrid
    */
    public function getDatagrid()                   { return $this->_Datagrid; }

    /**
    * Get the pager layout
    *
    * @return string
    */
    public function getPagerLayoutString()          { return $this->_PagerLayoutString; }

    /**
    * Get the pager link layout
    *
    * @return string
    */
    public function getPagerLinkLayoutString()      { return $this->_PagerLinkLayoutString; }



    //----------------------
    // Render methods
    //----------------------

    /**
    * Render the datagrid table
    *
    * @param string The html-code for the table
    * @return string Returns the html-code of the datagridtable
    */
    private function _renderTable($_innerTableData)
    {
        $htmlString = '';
        $htmlString .= '<table  class="DatagridTable DatagridTable-'. $this->getDatagrid()->getAlias() .'"
                                id="'. $this->getDatagrid()->getId() .'"
                                name="'. $this->getDatagrid()->getName() .'">';
        $htmlString .= CR . $_innerTableData  . CR . '</table>';
        return $htmlString;
    }



    /**
    * Render the label
    *
    * @return string Returns the html-code for the label if enabled
    */
    private function _renderLabel()
    {
        if( $this->getDatagrid()->isEnabled('Label') )
            return '<div class="DatagridLabel DatagridLabel-'. $this->getDatagrid()->getAlias() .'">' . CR . $this->getDatagrid()->getLabel() . CR . '</div>';
        else
            return;
    }


    /**
    * Render the description
    *
    * @return string Returns the html-code for the description
    */
    private function _renderDescription()
    {
        if( $this->getDatagrid()->isEnabled('Description') )
            return '<div class="DatagridDescription DatagridDescription-'. $this->getDatagrid()->getAlias() .'">' . CR . $this->getDatagrid()->getDescription() . CR . '</div>';
        else
            return;
    }

    /**
    * Render the caption
    *
    * @return string Returns the html-code for the caption
    */
    private function _renderTableCaption()
    {
        if( $this->getDatagrid()->isEnabled('Caption') )
        {
            $htmlString = '';
            $htmlString .= '<caption>';
            $htmlString .= $this->getDatagrid()->getCaption();
            $htmlString .= '</caption>';
            return $htmlString;
        }
        else
        {
            return '<caption></caption>';
        }
    }

    /**
    * Represents a sortstring for a-Tags
    *
    * @return string Returns a string such as index.php?mod=news&action=admin&sk=Title&sv=DESC
    */
    private function _getSortString($_SortKey, $_SortValue)
    {
        return $this->getDatagrid()->addToUrl('?' . $this->getDatagrid()->getInputParameterName('SortKey') . '=' . $_SortKey . '&' . $this->getDatagrid()->getInputParameterName('SortValue') . '=' . $_SortValue);
    }

    /**
    * Render the header
    *
    * @return string Returns the html-code for the header
    */
    private function _renderTableHeader()
    {
        if( $this->getDatagrid()->isEnabled('Header') )
        {

            $htmlString = '';
            $htmlString .= '<thead>';
            $htmlString .= '<tr>';
            $htmlString .= '';
            $htmlString .= '</tr>';

            $htmlString .= '</thead>';
            return $htmlString;
        }
        else
        {
            return '<thead></thead>';
        }
    }

    /**
    * Render the pagination for the datagrid
    *
    * @return string Returns the html-code for the pagination row
    */
    private function _renderTablePagination($_ShowResultsPerPage = true)
    {
        $htmlString = '';
        #Clansuite_Xdebug::firebug('Pagination: ' . $this->getDatagrid()->getPagerLayout());
        if( $this->getDatagrid()->isEnabled("Pagination") )
        {
            $htmlString .= '<tr><td class="DatagridPagination DatagridPagination-'. $this->getDatagrid()->getAlias() .'" colspan="'. $this->getDatagrid()->getColCount() .'">';
            $htmlString .= '<div class="Pages">' . $this->getDatagrid()->getPagerLayout() . '</div>';

            if( $_ShowResultsPerPage )
            {
                $htmlString .= '<div class="ResultsPerPage">';
                    $htmlString .= '<select name="' . $this->getDatagrid()->getInputParameterName('ResultsPerPage') . '" onChange="this.form.submit();">';
                        $htmlString .= '<option value="10" ' . (($this->getCurrentResultsPerPage()==10) ? 'selected="selected"' : '') . '>10</option>';
                        $htmlString .= '<option value="20" ' . (($this->getCurrentResultsPerPage()==20) ? 'selected="selected"' : '') . '>20</option>';
                        $htmlString .= '<option value="50" ' . (($this->getCurrentResultsPerPage()==50) ? 'selected="selected"' : '') . '>50</option>';
                        $htmlString .= '<option value="100" ' . (($this->getCurrentResultsPerPage()==100) ? 'selected="selected"' : '') . '>100</option>';
                    $htmlString .= '</select>';
                $htmlString .= '</div>';
            }
            else
            {
                $htmlString .= '<div class="ResultsPerPage">';
                    $htmlString .= $this->getDatagrid()->getPagerLayout()->getPager()->getNumResults() . _(' items');
                $htmlString .= '</div>';
            }

            $htmlString .= '</td></tr>';
        }
        return $htmlString;
    }

    /**
    * Render the body
    *
    * @return string Returns the html-code for the table body
    */
    private function _renderTableBody()
    {
        $htmlString = '';
        $htmlString .= '<tbody>';
        $htmlString .= $this->_renderTableRowsHeader();
        #$htmlString .= $this->_renderTableActions();
        $htmlString .= $this->_renderTableRows();
        if( $this->getDatagrid()->isEnabled('BatchActions') )
        {
            $htmlString .= $this->_renderTableBatchActions();
        }
        $htmlString .= '</tbody>';
        return $htmlString;
    }

    /**
    * Render the header of the rows
    *
    * @return string Returns the html-code for the rows-header
    */
    private function _renderTableRowsHeader()
    {
        $htmlString = '';
        $htmlString .= '<tr>';

        foreach( $this->getDatagrid()->getCols() as $oCol )
        {
            $htmlString .= $this->_renderTableCol($oCol);
        }
        $htmlString .= '</tr>';

        return $htmlString;
    }

    /**
    * Render the actions of the rows
    *
    * @return string Returns the html-code for the actions
    */
    private function _renderTableBatchActions()
    {
        $config = Clansuite_CMS::getInjector()->instantiate('Clansuite_Config')->toArray();

        $htmlString = '';
        $htmlString .= '<tr>';

        $htmlString .= '<td>';
        $htmlString .= '<input type="checkbox" class="DatagridSelectAll" />';
        $htmlString .= '</td>';

        $htmlString .= '<td colspan=' . ($this->getDatagrid()->getColCount()-1) . '>';

        $htmlString .= '<select name="action" id="BatchActionId">';
        $htmlString .= '<option value="'.$config['defaults']['action'].'">' . _('(Please select)') . '</option>';
        foreach( $this->getDatagrid()->getBatchActions() as $BatchActionSet )
        {
            $htmlString .= '<option value="' . $BatchActionSet['Action'] . '">' . $BatchActionSet['Name'] . '</option>';
        }
        $htmlString .= '</select>';
        $htmlString .= '<input type="submit" value="' . _('Execute') . '" />';
        $htmlString .= '</td>';

        $htmlString .= '</tr>';

        return $htmlString;
    }

    /**
    * Render all the rows
    *
    * @return string Returns the html-code for all rows
    */
    private function _renderTableRows()
    {
        $htmlString = '';

        $_Rows = $this->getDatagrid()->getRows();
        $i = 0;
        foreach( $_Rows as $rowKey => $oRow )
        {
            $i++;
            $htmlString .= $this->_renderTableRow($oRow, !($i % 2));
        }

        if( $htmlString == '' )
        {
            $htmlString .= '<tr class="DatagridRow DatagridRow-NoResults">';
                $htmlString .= '<td class="DatagridCell DatagridCell-NoResults" colspan="'.$this->getDatagrid()->getColCount().'">';
                $htmlString .= _('No Results');
                $htmlString .= '</td>';
            $htmlString .= '</tr>';
        }

        return $htmlString;
    }

    /**
    * Render a single row
    *
    * @param Clansuite_Datagrid_Row
    * @return string Returns the html-code for a single row
    */
    private function _renderTableRow($_oRow, $_Alternate)
    {
        if( $_Alternate === true )
        {
            $_AlternateClass = 'Alternate';
        }
        else
        {
            $_AlternateClass = '';
        }
        $htmlString = '<tr class="DatagridRow DatagridRow-' . $_oRow->getAlias() . ' ' . $_AlternateClass . '">';

        $_Cells = $_oRow->getCells();
        foreach( $_Cells as $oCell )
        {
            $htmlString .= $this->_renderTableCell($oCell);
        }

        $htmlString .= '</tr>';

        return $htmlString;
    }

    /**
    * Render a single cell
    *
    * @param Clansuite_Datagrid_Cell
    * @return string Return the html-code for the cell
    */
    public function _renderTableCell($_oCell)
    {
        $htmlString = '';

        $htmlString .= '<td class="DatagridCell DatagridCell-Cell_' . $_oCell->getCol()->getPosition() . '">' . $_oCell->render() . '</td>';

        return $htmlString;
    }

    /**
    * Render the column
    *
    * @param Clansuite_Datagrid_Col
    * @return string Returns the html-code for a single column
    */
    private function _renderTableCol($oCol)
    {
        $htmlString = '';
        $htmlString .= '<th id="ColHeaderId-'. $oCol->getAlias() . '" class="ColHeader ColHeader-'. $oCol->getAlias() .'">';
        $htmlString .= $oCol->getName();
        if( $oCol->isEnabled('Sorting') )
        {
            $htmlString .= '&nbsp;<a href="' . $this->_getSortString($oCol->getAlias(), $this->getDatagrid()->getSortReverseDefinition($oCol->getSortMode())) . '">';
            #$htmlString .= '<img src="' . WWW_ROOT_THEMES .'/'. $_SESSION['user']['theme'] .'/" />';
            $htmlString .= _($oCol->getSortMode());
            $htmlString .= '</a>';
        }
        $htmlString .= '</th>';

        return $htmlString;
    }

    /**
    * Render the footer
    *
    * @return string Returns the html-code for the footer
    */
    private function _renderTableFooter()
    {
        if( $this->getDatagrid()->isEnabled('Footer') )
        {
            $htmlString = '';
            $htmlString .= '<tfoot>';

            $htmlString .= '</tfoot>';
            return $htmlString;
        }
        else
        {
            return '<tfoot></tfoot>';
        }
    }

    public function _renderTableSearch()
    {
        $request = Clansuite_CMS::getInjector()->instantiate('Clansuite_HttpRequest');

        $htmlString = '';
        if( $this->getDatagrid()->isEnabled('Search') )
        {
            $htmlString .= '<tr><td colspan="'.$this->getDatagrid()->getColCount().'">';
            $htmlString .= _('Search: ');
            $htmlString .= '<input type="text" value="'.htmlentities($_SESSION['Datagrid_' . $this->getDatagrid()->getAlias()]['SearchValue']).'" name="'.$this->getDatagrid()->getInputParameterName('SearchValue').'" />';
            $htmlString .= ' <select name="'.$this->getDatagrid()->getInputParameterName('SearchKey').'">';
            $aCols = $this->getDatagrid()->getCols();
            foreach( $aCols as $oCol )
            {
                if( $oCol->isEnabled('Search') )
                {
                    $selected = '';
                    if($_SESSION['Datagrid_' . $this->getDatagrid()->getAlias()]['SearchKey'] == $oCol->getAlias())
                    {
                        $selected = ' selected="selected"';
                    }
                    $htmlString .= '<option value="'.$oCol->getAlias().'"'.$selected.'>'.$oCol->getName().'</option>';
                }
            }
            $htmlString .= '</select>';
            $htmlString .= ' <input type="submit" value="'._('Search').'" />';
            $htmlString .= '</td></tr>';
        }
        return $htmlString;
    }

    /**
     * Render the whole grid
     *
     * @todo vain: Clansuite_ActionController_Resolver::getDefaultActionName() + resolve ternary
     * @return string Returns the html-code for the whole datagrid and its dataset of the named query
     */
    public function render()
    {
        # Execute the datagrid!
        $this->getDatagrid()->execute();

        # Build htmlcode
        $_htmlCode = '';

        $_htmlCode .= '<link rel="stylesheet" type="text/css" href="'. WWW_ROOT_THEMES_CORE . '/css/datagrid.css" />';
        $_htmlCode .= '<script src="'. WWW_ROOT_THEMES_CORE . '/javascript/datagrid.js" type="text/javascript"></script>';
        $_htmlCode .= '<form action="' . $this->getDatagrid()->getBaseURL() . '" method="post" name="Datagrid-' . $this->getDatagrid()->getAlias() . '" id="Datagrid-' . $this->getDatagrid()->getAlias() . '">';

            #$_htmlCode .= '<input type="hidden" name="action" value="' . Clansuite_ActionController_Resolver::getDefaultActionName() . '" />';
            #$_htmlCode .= '<input type="hidden" name="action" id="ActionId" value="' . ((isset($_REQUEST['action'])&&preg_match('#^[0-9a-z_]$#i',$_REQUEST['action']))?$_REQUEST['action']:'show') . '" />';
            $_htmlCode .= '<input type="hidden" name="' . $this->getDatagrid()->getInputParameterName('Page') . '" value="' . $this->getCurrentPage() . '" />';
            $_htmlCode .= '<input type="hidden" name="' . $this->getDatagrid()->getInputParameterName('ResultsPerPage') . '" value="' . $this->getCurrentResultsPerPage() . '" />';
            $_htmlCode .= '<input type="hidden" name="' . $this->getDatagrid()->getInputParameterName('SortKey') . '" value="' . $this->getCurrentSortKey() . '" />';
            $_htmlCode .= '<input type="hidden" name="' . $this->getDatagrid()->getInputParameterName('SortValue') . '" value="' . $this->getCurrentSortValue() . '" />';

            $_htmlCode .= '<div class="Datagrid ' . $this->getDatagrid()->getClass() . '">';

                $_htmlCode .= $this->_renderLabel();
                $_htmlCode .= $this->_renderDescription();

                $_innerTableData = '';

                $_innerTableData .= $this->_renderTableCaption();
                $_innerTableData .= $this->_renderTableHeader();
                $_innerTableData .= $this->_renderTablePagination();
                $_innerTableData .= $this->_renderTableSearch();
                $_innerTableData .= $this->_renderTableBody();
                $_innerTableData .= $this->_renderTablePagination(false);
                $_innerTableData .= $this->_renderTableFooter();

                $_htmlCode .= $this->_renderTable($_innerTableData);

            $_htmlCode .= '</div>';
        $_htmlCode .= '</form>';

        return $_htmlCode;
    }
}


?>
