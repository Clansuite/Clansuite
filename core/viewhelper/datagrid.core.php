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
    * @copyright  Jens-André Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.');}

if (!class_exists('Clansuite_Datagrid_Column', false)) { require dirname(__FILE__) . '/datagridcol.core.php'; }

/**
 * Clansuite Datagrid Base
 *
 * Purpose:
 * It is the abstract base class for a datagrid,
 * supplying methods for all datagrid-subclasses.
 *
 * @author Florian Wolf <xsign.dll@clansuite.com>
 */
class Clansuite_Datagrid_Base
{
    // scoped vars
    private $_alias;
    private $_id;
    private $_name;
    private $_class;
    private $_style;

    /**
     * Label for the datagrid
     *
     * @var string
     */
    private $_label = 'Label';

    /**
     * The caption (<caption>...</caption>) for the datagrid
     *
     * @var string
     */
    private $_caption = 'Caption';

    /**
     * The description for the datagrid
     *
     * @var string
     */
    private $_description = 'This is a Clansuite Datagrid.';

    /**
     * Doctrine Datatable object
     *
     * @var Doctrine_Datatable
     */
    private $_doctrineTable;

    /**
     * Base URL for the Datatable
     *
     * @var string
     */
    private static $_baseURL = null;

    /**
     *  Setter Methods for a Datagrid
     */

    public function setAlias($alias)
    {
        $this->_alias = $alias;
    }

    /**
     * Sets the BaseURL
     *
     * @param string $baseURL The baseURL for the datatable
     */
    public function setBaseURL($baseURL)
    {
        if(is_null(self::$_baseURL))
        {
            self::$_baseURL = Clansuite_HttpRequest::getRequestURI();
        }
        else
        {
            self::$_baseURL = $baseURL;
        }
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function setClass($class)
    {
        $this->_class = $class;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function setStyle($style)
    {
        $this->_style = $style;
    }

    public function setLabel($label)
    {
        $this->_label = $label;
    }

    public function setCaption($caption)
    {
        $this->_caption = $caption;
    }

    public function setDescription($description)
    {
        $this->_description = $description;
    }

    /**
     * Get Doctine Table Object
     *
     * @return Doctrine_Table
     */
    public function getDoctrineTable()
    {
        return $this->_doctrineTable;
    }

    /**
     * Getter Methods for Datagrid
     */

    public function getAlias()
    {
        return $this->_alias;
    }

    public static function getBaseURL()
    {
       return self::$_baseURL;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getClass()
    {
        return $this->_class;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getStyle()
    {
        return $this->_style;
    }

    public function getLabel()
    {
        return $this->_label;
    }

    public function getCaption()
    {
        return $this->_caption;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * Sets the Doctrine Table
     *
     * @param Doctrine_Datatable $doctrineTable
     */
    public function setDoctrineTable(Doctrine_Table $doctrineTable)
    {
        $this->_doctrineTable = $doctrineTable;
    }

    /**
     * Add an url-string to the baseurl
     *
     * @example
     *   $sUrl = $this->addQueryToUrl('dg_Sort=0:ASC');
     *
     * @param string $appendString String to append to the URL.
     */
    public static function addQueryToUrl($appendString)
    {
        $separator = '?';

        if( preg_match('#\?#', self::getBaseURL()) )
        {
            $separator = '&amp;';
        }

        $cleanAppendString = preg_replace('#^&amp;#', '', $appendString);
        $cleanAppendString = preg_replace('#^&#', '', $cleanAppendString);
        $cleanAppendString = preg_replace('#^\?#', '', $cleanAppendString);
        $cleanAppendString = preg_replace('#&(?!amp;)#i', '&amp;', $cleanAppendString);

        return self::getBaseURL() . $separator . $cleanAppendString;
    }
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
 * # Caption             (<caption>Caption</caption>) #
 * #--------------------------------------------------#
 * # Pagination        (<tr><td>Pagination</td></tr>) #
 * #--------------------------------------------------#
 * # Header     (<tr><th>Col1</th><th>Col2</th></tr>) #
 * #--------------------------------------------------#
 * # Rows               (<tr><td>DataField</td></tr>) #
 * #--------------------------------------------------#
 * # Pagination        (<tr><td>Pagination</td></tr>) #
 * #--------------------------------------------------#
 * # Footer                (<tr><td>Footer</td></tr>) #
 * #--------------------------------------------------#
 *
 *
 * @see http://www.doctrine-project.org/Doctrine_Table/1_2
 */
class Clansuite_Datagrid extends Clansuite_Datagrid_Base
{
    //--------------------
    // Class properties
    //--------------------

    /**
     * The Batchactions for this grid (edit, delete, move, ...)
     *
     * @var array
     */
    private $_batchActions = array();

    /**
     * Object which called
     *
     * @var object
     */
    private $_Caller;

    /**
     * Array of Clansuite_Datagrid_Cell objects
     *
     * @var array
     */
    private $_Cells = array();

    /**
     * Amount of columns
     *
     * @var integer
     */
    private $_ColCount = 0;

    /**
     * Array of Clansuite_Datagrid_Column objects
     *
     * @var array
     */
    private $_columns = array();

    /**
     * An array of sets (arrays) to configure the columns
     *
     * @var array
     */
    private $_columnSets = array();

    /**
     * The datagrid type
     *
     * @todo implement
     * @var string
     */
    #private $_DatagridType  = 'Normal';

    /**
     * Array of datasets
     * Represents a doctrine dataset
     *
     * @var array
     */
    private $datasets = array();

    /**
     * Feature configuration for the datagrid
     *
     * @var array
     */
    private $_features = array(
        'Caption'       => true,
        'Header'        => true,
        'Footer'        => true,
        'BatchActions'  => true,
        'Description'   => true,
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
    private $_inputMapping = array( 'SortKey'           => 'sk',
                                    'SortValue'         => 'sv',
                                    'Page'              => 'p',
                                    'ResultsPerPage'    => 'rpp',
                                    'SearchValue'       => 'searchvalue',
                                    'SearchKey'         => 'searchkey',
                                    'Reset'             => 'reset' );

    /**
     * Doctrine Pager Layout object
     *
     * @link http://www.doctrine-project.org/documentation/manual/1_2/en/utilities#pagination:working-with-pager Doctrine_Pager_Layout
     * @var Doctrine_Pager_Layout
     */
    private $_doctrinePagerLayout;

    /**
     * Doctrine Query object
     *
     * @link http://www.doctrine-project.org/documentation/manual/1_2/en/dql-doctrine-query-language DQL (Doctrine Query Language)
     * @var Doctrine_Query
     */
    private $_query;

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
    private $_queryName = 'fetchAll';

    /**
     * The renderer for the datagrid
     *
     * @var Clansuite_Datagrid_Renderer
     */
    private $_renderer;

    /**
     * Results per Page
     *
     * @var int
     */
    private $_resultsPerPage;

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
    private $_rows = array();

    /**
     * Associated sortables
     *
     * @var array
     */
    private $_sortReverseDefinitions = array(
                      'ASC'     => 'DESC',
                      'DESC'    => 'ASC',
                      'NATASC'  => 'NATDESC',
                      'NATDESC' => 'NATASC'
    );

    /**
     * Set the baseurl and modify string
     *
     * @param string $baseURL
     */
    private function _setBaseURL($baseURL)
    {
        #$this->_baseURL = preg_replace('#&(?!amp;)#i', '&amp;', $baseURL);
    }

    /**
     * Set the batchactions
     *
     * @param array $batchActions
     */
    public function setBatchActions($batchActions)
    {
        $this->_batchActions = $batchActions;
    }

    /**
     * Sets the column objects of the grid (Clansuite_Datagrid_Column)
     *
     * @param array $_Cols
     */
    public function setCols($_Cols)
    {
        $this->_columns = $_Cols;
    }

    /**
     * Set the doctrine pager layout object
     *
     * @param Doctrine_Pager_Layout $doctrinePagerLayout
     */
    public function setPagerLayout(Doctrine_Pager_Layout $doctrinePagerLayout)
    {
        $this->_doctrinePagerLayout = $doctrinePagerLayout;
    }

    /**
     * Set the renderer object
     *
     * @param Clansuite_Datagrid_Renderer $_Renderer
     */
    public function setRenderer(Clansuite_Datagrid_Renderer $_Renderer)
    {
        $this->_renderer = $_Renderer;
    }

    /**
     * Set the results per page
     *
     * @param int $resultsPerPage
     */
    public function setResultsPerPage($resultsPerPage)
    {
        $this->_resultsPerPage = $resultsPerPage;
    }

    /**
     * Set the hook for the resultset manipulation
     *
     * @param Object $caller
     * @param Methodname $methodname
    */
    public function setResultSetHook($caller, $methodname)
    {
        $this->_Caller          = $caller;
        $this->_ResultSetHook   = $methodname;
    }

    /**
     * Sets the row objects of the grid (Clansuite_Datagrid_Row)
     *
     * @param array $_Rows
     */
    public function setRows($_Rows)
    {
        $this->_rows = $_Rows;
    }

    /**
     * Sets the queryname and updates the datagrid
     *
     * @param string $queryName
     */
    public function setQueryName($queryName)
    {
        $this->_queryName = $queryName;

        # generate a doctrine query
        $this->_generateQuery();
    }

    //--------------------
    // Getter
    //--------------------

    public function getBatchActions()   { return $this->_batchActions; }

    /**
     * Get a column depending on its alias
     *
     * @param string $sColumnKey Name of the Column
     * @return Clansuite_Datagrid_Column
    */
    public function getColumn($sColumnKey)     { return $this->_columns[$sColumnKey]; }

    /**
     * Amount of columns in the grid
     *
     * @return integer Amount of columns
     */
    public function getColumnCount()       { return count($this->getColumns()); }

    /**
     * Returns the column objects (Clansuite_Datagrid_Col)
     *
     * @return array
     */
    public function getColumns()           { return $this->_columns; }

    /**
     * Get the input parameter depending on the mapping
     *
     * @param string $_internalKey
     * @return string $this->_InputMapping[$_InternalKey];
     */
    public function getInputParameterName($_internalKey)
    {
        if( !isset($this->_inputMapping[$_internalKey]) )
        {
            throw new Clansuite_Exception(_('This internal key is not know to private array $_InputMapping: ') . $_internalKey);
        }
        return $this->_inputMapping[$_internalKey];
    }

    /**
     * Get the pager layout object
     *
     * @return Doctrine_Pager_Layout
     */
    public function getPagerLayout()    { return $this->_doctrinePagerLayout; }

    /**
     * Get the renderer object
     *
     * @return Clansuite_Datagrid_Renderer
     */
    public function getRenderer()       { return $this->_renderer; }

    /**
     * Get the results per page
     *
     * @return int
     */
    public function getResultsPerPage() { return $this->_resultsPerPage; }

    /**
     * Returns the row objects (Clansuite_Datagrid_Row)
     *
     * @return array
     */
    public function getRows()           { return $this->_rows; }

    /**
     * Get the pendant to DESC/ASC and NATASC/NATDESC
     *
     * @return string $this->_sortReverseDefinitions[$_sortMode];
     */
    public function getSortReverseDefinition($_sortMode)
    {
        if( !isset($this->_sortReverseDefinitions[$_sortMode]) )
        {
            throw new Clansuite_Exception(_('This sortMode is not in the list of private var $_SortReverseDefinitions: ') . $_sortMode );
        }

        return $this->_sortReverseDefinitions[$_sortMode];
    }


    //--------------------
    // Class methods
    //--------------------

    /**
     * Constructor
     *
     * @param array Options (Datatable, NamedQuery, ColumnSets, BaseURL)
     */
    public function __construct($options)
    {
        if( !($options['Datatable'] instanceof Doctrine_Table) )
        {
            throw new Clansuite_Exception(_('Incoming data seems not to be a valid Doctrine_Table'));
        }

        # attach Datagrid to renderer
        $this->setRenderer(new Clansuite_Datagrid_Renderer($this)); # @todo why pass $this?

        # sets the doctrine table to the base class
        $this->setDoctrineTable($options['Datatable']);

        # Set all columns
        # @todo load from columnset definition file / remove array from module
        $this->_setColumnSets($options['ColumnSets']);

        # set queryname
        $this->_queryName = $options['NamedQuery'];

        # construct url by appending to the baseURL
        $this->setBaseUrl($options['ModuleActionURL']);

        # disable some features
        # @todo css for these elements
        $this->disableFeatures(array('Label', 'Caption', 'Description'));

        # generate default datasets that can be overwritten
        $this->_initDatagrid();
    }

    /**
     * Initialize the datagrid
     */
    private function _initDatagrid()
    {
        # set scalar values
        $this->setAlias($this->getDoctrineTable()->getClassnameToReturn());

        # reset session?
        if( isset($_REQUEST[$this->getInputParameterName('Reset')]) )
        {
               $_SESSION['Datagrid_' . $this->getAlias()] = '';
        }

        # set properties to datagrid
        $this->setId('DatagridId-' . $this->getAlias());
        $this->setName('DatagridName-' . $this->getAlias());
        $this->setClass('Datagrid-' . $this->getAlias());
        $this->setLabel($this->getAlias());
        $this->setCaption($this->getAlias());
        $this->setDescription(_('This is the datagrid of ') . $this->getAlias());

        # generate the columns
        $this->_generateCols();

        # update the query
        #$this->_generateQuery();
    }

    /**
     * Execute the datagrid (query, pager, cols, rows, everything!)
     */
    public function execute()
    {
        # generate the doctrine query
        $this->_generateQuery();

        # execute the doctrine-query
        $this->datasets = $this->getPagerLayout()->getPager()->execute();

        # Debug
        #Clansuite_Xdebug::firebug($this->_Datasets);

        # update the current page
        $this->getRenderer()->setCurrentPage($this->getPagerLayout()->getPager()->getPage());

        # generate the data-rows
        $this->_generateRows($this->datasets);
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
        if( isset($this->_features[$feature]) == false )
        {
            throw new Clansuite_Exception(_('There is no such feature in this datagrid: ') . $feature);
        }
        else
        {
            return $this->_features[$feature];
        }
    }

    /**
     * Enables a datagrid feature and returns true on success, false otherwise
     *
     * @see $this->_features
     * @param string $feature
     * @return boolean
     */
    public function enableFeature($feature)
    {
        if( isset($this->_features[$feature]) == false)
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
     * Enables several datagrid features
     * Return true on success, false otherwise
     *
     * @see $this->_features
     * @param array $features
     */
    public function enableFeatures(array $features)
    {
        foreach ($features as $feature)
        {
            $this->enableFeature($feature);
        }
    }

    /**
     * Disables a datagrid feature
     * Returns true on success, false otherwise
     *
     * @see $this->_features
     * @param mixed $feature
     * @return boolean
     */
    public function disableFeature($feature)
    {
        if( isset($this->_features[$feature]) == false)
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
     * Disable several datagrid features
     * Return true on success, false otherwise
     *
     * @see $this->_features
     * @param array $features
     */
    public function disableFeatures(array $features)
    {
        foreach ($features as $feature)
        {
            $this->disableFeature($feature);
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
     * Ensures a Column key exists or is not empty
     *
     * @param array  $columnSet Structured array of the column.
     * @param string $columnKey Name of the Column Key to check for.
     */
    private static function checkColumnKeyExist($columnSet, $columnKey)
    {
        #$columnKey = ucfirst($columnKey);

        # define exception message string for usage with sprintf + gettext
        $exception_sprintf = _('The datagrid columnset has an error. The array key "%s" is missing.)');

        # No alias given
        if( (isset($columnSet[$columnKey]) == false) or ($columnSet[$columnKey] == '') )
        {
            throw new Clansuite_Exception(sprintf($exception_sprintf, $columnKey));
        }
    }

    /**
     * Set Datagrid Cols (internally without auto-update)
     *
     * @params array Columns Array
     */
    private function _setColumnSets($_columnSets = array())
    {
        #Clansuite_Xdebug::firebug($_columnSets);

        foreach( $_columnSets as $key => $columnSet )
        {
            #Clansuite_Xdebug::firebug($key);
            # No alias given
            self::checkColumnKeyExist($columnSet, 'Alias');
             # No resultset given
            self::checkColumnKeyExist($columnSet, 'ResultSet');
            # No name given
            self::checkColumnKeyExist($columnSet, 'Name');

            # No SortCol although sorting is enabled
            if( isset($columnSet['Sort']) and !isset($columnSet['SortCol']) )
            {
                throw new Clansuite_Exception(sprintf(_('The datagrid columnset has an error at key %s (sorting is enabled but "SortCol" is missing)'), $key));
            }

            # Default type: String
            if( !isset($columnSet['Type']) || $columnSet['Type'] == '')
            {
                $columnSet['Type'] = 'String';
            }
        }

        # Everything validates
        $this->_columnSets = $_columnSets;
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

        # generate a doctrine query
        $this->_generateQuery();
    }

    /**
     * Get the datagrid column sets from initial configuration
     *
     * @return array
     */
    public function getColumnSets()
    {
        return $this->_columnSets;
    }

    /**
     * Generates all col objects
     */
    private function _generateCols()
    {
        $colSet = null;

        foreach( $this->_columnSets as $columnKey => &$colSet )
        {
            $oCol = new Clansuite_Datagrid_Column();
            $oCol->setAlias($colSet['Alias']);
            $oCol->setId($colSet['Alias']);
            $oCol->setName($colSet['Name']);

            if( false == isset($colSet['Sort']) )
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

            $oCol->setPosition($columnKey);
            $oCol->setRenderer($colSet['Type']);

            # set the column object under its alias into the array of all columns
            $this->_columns[$colSet['Alias']] = $oCol;
        }
    }

    /**
     * Generates all row objects
     *
     * @param array Results-array from doctrine named query
     */
    private function _generateRows($datasets)
    {
        foreach( $datasets as $dataKey => $dataSet )
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

            $ColumnKey = null;

            foreach( $this->_columnSets as $ColumnKey => $colSet )
            {
                $oCell = new Clansuite_Datagrid_Cell();
                $oRow->addCell($oCell);
                $oCol = $this->_columns[$colSet['Alias']];
                $oCol->addCell($oCell);

                $this->_rows[$dataKey] = $oRow;

                $oCell->setColumnObject($oCol);
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
        $aResultSet = null;

        if( !is_array($_ColumnSet) )
        {
            throw new Clansuite_Exception(_('You have not supplied any columnset to validate.'));
        }

        $values = array();

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
                if( !is_array($_TmpArrayHandler) or !isset($_TmpArrayHandler[$_LevelKey]) )
                {
                    #Clansuite_Xdebug::firebug('ResultSet not found in Dataset: ' . $ResultValue, 'warn');
                    $_TmpArrayHandler = '';
                    break;
                }

                $_TmpArrayHandler = $_TmpArrayHandler[$_LevelKey];
            }

            $values[$i] = $_TmpArrayHandler;
            $values[$ResultKey] = $_TmpArrayHandler;
            $i++;
        }

        return $values;
    }

    /**
     * Generates a customized query for this table
     */
    private function _generateQuery()
    {
        if( isset($this->_queryName) )
        {
            $this->_query = parent::getDoctrineTable()->createNamedQuery($this->_queryName)->setHydrationMode(Doctrine::HYDRATE_ARRAY);
        }
        else
        {
            $this->_query = parent::getDoctrineTable()
                                ->createQuery()
                                ->select('*')
                                ->setHydrationMode(Doctrine::HYDRATE_ARRAY);
        }

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
        if( isset($_SESSION['Datagrid_' . $this->getAlias()]['SortKey']) and isset($_SESSION['Datagrid_' . $this->getAlias()]['SortValue']) )
        {
            $SortKey    = $_SESSION['Datagrid_' . $this->getAlias()]['SortKey'];
            $SortValue  = $_SESSION['Datagrid_' . $this->getAlias()]['SortValue'];
        }

        # Prefer requests
        if( isset($_REQUEST[$this->_inputMapping['SortKey']]) and isset($_REQUEST[$this->_inputMapping['SortValue']]) )
        {
            $SortKey    = $_REQUEST[$this->_inputMapping['SortKey']];
            $SortValue  = $_REQUEST[$this->_inputMapping['SortValue']];
        }

        # Check for valid formats of key and value
        if( ($SortKey != '' and $SortValue != '') AND
            ( preg_match('#^([0-9a-z_]+)#i', $SortKey) and preg_match('#([a-z]+)$#i', $SortValue) ) )
        {
            $_SESSION['Datagrid_' . $this->getAlias()]['SortKey']   = $SortKey;
            $_SESSION['Datagrid_' . $this->getAlias()]['SortValue'] = $SortValue;
            $this->getRenderer()->setCurrentSortKey($SortKey);
            $this->getRenderer()->setCurrentSortValue($SortValue);

            $oCol = $this->getColumn($SortKey);
            $this->_query->orderBy($oCol->getSortField() . ' ' . $SortValue);
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
        if( isset($_SESSION['Datagrid_' . $this->getAlias()]['SearchKey']) and isset($_SESSION['Datagrid_' . $this->getAlias()]['SearchValue']) )
        {
            $SearchKey    = $_SESSION['Datagrid_' . $this->getAlias()]['SearchKey'];
            $SearchValue  = $_SESSION['Datagrid_' . $this->getAlias()]['SearchValue'];
        }

        # Prefer requests
        if( isset($_REQUEST[$this->_inputMapping['SearchKey']]) and isset($_REQUEST[$this->_inputMapping['SearchValue']]) )
        {
            $SearchKey    = $_REQUEST[$this->_inputMapping['SearchKey']];
            $SearchValue  = $_REQUEST[$this->_inputMapping['SearchValue']];
        }

        $_SESSION['Datagrid_' . $this->getAlias()]['SearchKey']     = $SearchKey;
        $_SESSION['Datagrid_' . $this->getAlias()]['SearchValue']   = $SearchValue;

        # Check for valid formats of key and value
        if( ($SearchKey != '' and $SearchValue != '') )
        {
            $this->_query->andWhere($this->getColumn($SearchKey)->getSortField() .' LIKE ?', array('%' . $SearchValue . '%') );
        }
    }

    /**
     * Generate the PagerLayout for a query
     */
    private function _generatePagerLayout()
    {
        $page = null;
        $resultsPerPage = null;

        # Read session
        if( isset($_SESSION['Datagrid_' . $this->getAlias()]['Page']) )
        {
            $page = $_SESSION['Datagrid_' . $this->getAlias()]['Page'];
        }
        else
        {
            $page = 1;
        }

        if( isset($_SESSION['Datagrid_' . $this->getAlias()]['ResultsPerPage']) )
        {
            $resultsPerPage = $_SESSION['Datagrid_' . $this->getAlias()]['ResultsPerPage'];
        }
        else
        {
            $resultsPerPage = $this->getResultsPerPage();
        }

        # Add to session
        if( isset($_REQUEST[$this->_inputMapping['Page']]) )
        {
            $page = (int) $_REQUEST[$this->_inputMapping['Page']];
            $_SESSION['Datagrid_' . $this->getAlias()]['Page'] = $page;
        }

        if( isset($_REQUEST[$this->_inputMapping['ResultsPerPage']]) )
        {
            #Clansuite_Xdebug::firebug('ResultsPerPage:' . $_ResultsPerPage);
            $resultsPerPage = (int) $_REQUEST[$this->_inputMapping['ResultsPerPage']];
            $_SESSION['Datagrid_' . $this->getAlias()]['ResultsPerPage'] = $resultsPerPage;
        }

        # Add to renderer
        $this->getRenderer()->setCurrentPage($page);
        $this->getRenderer()->setCurrentResultsPerPage($resultsPerPage);

        $this->setPagerLayout( new Doctrine_Pager_Layout(
                                    new Doctrine_Pager(
                                        $this->_query,
                                        $page,
                                        $resultsPerPage
                                    ),
                                    new Doctrine_Pager_Range_Sliding(array(
                                        'chunk' => 5
                                    )),
                                    $this->addQueryToUrl('?' . $this->_inputMapping['Page'] . '={%page}')
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
    // Class properties
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
        # array_push($this->_Cells, $_Cell);
        $this->_Cells[] = $_Cell;
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
    // Class properties
    //----------------------

    /**
     * Value(s) of the cell
     * $_Values[0] is the standard value returned by getValue()
     *
     * @var array Mixed values
    */
    private $_Values = array();

    /**
     * Column object (Clansuite_Datagrid_Column)
     *
     * @var object Clansuite_Datagrid_Column
     */
    private $_columnObject;

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
     * @param Clansuite_Datagrid_Column $_columnObject
     */
    public function setColumnObject($_columnObject)      { $this->_columnObject = $_columnObject; }

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
     * @return Clansuite_Datagrid_Column $_columnObject
     */
    public function getColumn()    { return $this->_columnObject; }

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
        return $this->getColumn()->renderCell($this);
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
    // Class properties
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
    private static $_CurrentResultsPerPage;

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
    private static $_Datagrid;

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

    /**
     * The items for results per page
     *
     * @var array
     */
    private static $_ResultsPerPageItems = array( 5, 10, 20, 50, 100 );

    //----------------------
    // Class methods
    //----------------------

    /**
     * Instantiate renderer and attach Datagrid to it
     *
     * @param Clansuite_Datagrid_Datagrid $_Datagrid
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
    public static function setCurrentResultsPerPage($_Value)           { self::$_CurrentResultsPerPage = $_Value; }

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
    public static function setDatagrid($_Datagrid)              { self::$_Datagrid = $_Datagrid; }

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


    /**
     * Set the items for the dropdownbox of results per page
     *
     * @param array $_Items
     */
    public static function setResultsPerPageItems(array $_Items)
    {
        self::$_ResultsPerPageItems = $_Items;
    }

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
    public static function getCurrentResultsPerPage()      { return self::$_CurrentResultsPerPage; }

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
    public static function getDatagrid()            { return self::$_Datagrid; }

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

    /**
     * Get the items for the dropdownbox of results per page
     *
     * @return string
     */
    public static function getResultsPerPageItems()        { return self::$_ResultsPerPageItems; }

    //----------------------
    // Render methods
    //----------------------

    /**
     * Render the datagrid table
     *
     * @param string The html-code for the table
     * @return string Returns the html-code of the datagridtable
     */
    private static function renderTable()
    {
        $table_sprintf  = '<table class="DatagridTable DatagridTable-%s" cellspacing="0" cellpadding="0" border="0" id="%s">'.CR;
        $table_sprintf .= CR . '%s'  . CR . '</table>'.CR;

        $_innerTableContent = '';
        $_innerTableContent .= Clansuite_Datagrid_Renderer::renderTableCaption();
        $_innerTableContent .= Clansuite_Datagrid_Renderer::renderTableBody('one');
        $_innerTableContent .= Clansuite_Datagrid_Renderer::renderTableHeader();      # this isn't a <thead> tag, but <tbody>
        $_innerTableContent .= Clansuite_Datagrid_Renderer::renderTableBody('two');
        $_innerTableContent .= Clansuite_Datagrid_Renderer::renderTableBody('three');
        $_innerTableContent .= Clansuite_Datagrid_Renderer::renderTableFooter();

        $htmlString = sprintf($table_sprintf, self::getDatagrid()->getAlias(),
                                              self::getDatagrid()->getId(),
                                              $_innerTableContent);

        return $htmlString;
    }

    /**
     * Render the label
     *
     * @return string Returns the html-code for the label if enabled
     */
    private static function renderLabel()
    {
        if( self::getDatagrid()->isEnabled('Label') )
        {
            return '<div class="DatagridLabel DatagridLabel-'. self::getDatagrid()->getAlias() .'">' . CR . self::getDatagrid()->getLabel() . CR . '</div>';
        }
        else
        {
            return;
        }
    }

    /**
     * Render the description
     *
     * @return string Returns the html-code for the description
     */
    private static function renderDescription()
    {
        if( self::getDatagrid()->isEnabled('Description') )
        {
            return '<div class="DatagridDescription DatagridDescription-'. self::getDatagrid()->getAlias() .'">' . CR . self::getDatagrid()->getDescription() . CR . '</div>';
        }
        else
        {
            return;
        }
    }

    /**
     * Render the caption
     *
     * @return string Returns the html-code for the caption
     */
    private static function renderTableCaption()
    {
        $htmlString = '';

        if( self::getDatagrid()->isEnabled('Caption') )
        {
            $htmlString .= '<caption>';
            #$htmlString .= self::getDatagrid()->getCaption();
            $htmlString .= Clansuite_Datagrid_Renderer::renderLabel();
            $htmlString .= Clansuite_Datagrid_Renderer::renderDescription();
            $htmlString .= '</caption>'.CR;

        }

        return $htmlString;
    }

    /**
     * Represents a sortstring for a-Tags
     *
     * @param string SortKey
     * @param string SortValue
     * @return string Returns a string such as index.php?mod=news&action=admin&sk=Title&sv=DESC
     */
    private static function _getSortString($_SortKey, $_SortValue)
    {
        $url_string = sprintf('?%s=%s&%s=%s',
                               self::getDatagrid()->getInputParameterName('SortKey'),   $_SortKey,
                               self::getDatagrid()->getInputParameterName('SortValue'), $_SortValue
                             );

        return self::getDatagrid()->addQueryToUrl($url_string);
    }

    /**
     * Render the header
     *
     * @return string Returns the html-code for the header
     */
    private static function renderTableHeader()
    {
        $htmlString = '';

        if( self::getDatagrid()->isEnabled('Header') )
        {
            $htmlString .= '<tbody>'.CR; # OH MY GODDON! <thead> is not working here
            $htmlString .= '<tr>'.CR;
            $htmlString .= self::renderTableRowsHeader();
            $htmlString .= '</tr>'.CR;
            $htmlString .= '</tbody>'.CR; # OMG^2
        }

        return $htmlString;
    }

    /**
     * Render the header of the rows
     *
     * @return string Returns the html-code for the rows-header
     */
    private static function renderTableRowsHeader()
    {
        $htmlString = '';

        foreach( self::getDatagrid()->getColumns() as $column )
        {
            $htmlString .= Clansuite_Datagrid_Renderer::renderTableColumn($column);
        }

        return $htmlString;
    }

    /**
     * Render the pagination for the datagrid
     *
     * @param boolean $_ShowResultsPerPage If true, the drop-down for maximal results per page is shown. Otherwise the total number of items.
     * @return string Returns the html-code for the pagination row
     */
    private static function renderTablePagination($_ShowResultsPerPage = true)
    {
        $htmlString = '';
        #Clansuite_Xdebug::firebug('Pagination: ' . self::getDatagrid()->getPagerLayout());
        if( self::getDatagrid()->isEnabled("Pagination") )
        {
            $htmlString .= '<tr><td class="DatagridPagination DatagridPagination-'. self::getDatagrid()->getAlias() .'" colspan="'. self::getDatagrid()->getColumnCount() .'">';
            $htmlString .= '<div class="Pages"><span class="PagerDescription">' . _('Pages: ') . '</span>' . self::getDatagrid()->getPagerLayout() . '</div>';

            if( $_ShowResultsPerPage )
            {
                $htmlString .= '<div class="ResultsPerPage">';
                $htmlString .= '<select name="' . self::getDatagrid()->getInputParameterName('ResultsPerPage') . '" onchange="this.form.submit();">';
                    $_ResultsPerPageItems = self::getResultsPerPageItems();
                    foreach( $_ResultsPerPageItems as $ItemCount )
                    {
                        $htmlString .= '<option value="'.$ItemCount.'" ' . ((self::getCurrentResultsPerPage()==$ItemCount) ? 'selected="selected"' : '') . '>'.$ItemCount.'</option>';
                    }
                $htmlString .= '</select>';
                $htmlString .= '</div>';
            }
            else
            {
                $htmlString .= '<div class="ResultsPerPage">';
                $htmlString .= self::getDatagrid()->getPagerLayout()->getPager()->getNumResults() . _(' items');
                $htmlString .= '</div>';
            }

            $htmlString .= '</td></tr>';
        }
        return $htmlString;
    }

    /**
     * Render the body
     *
     * @see renderTable()
     * @param $type Rendertype toggle (one, two)
     * @return string Returns the html-code for the table body
     */
    private static function renderTableBody($type = 'one')
    {
        $htmlString = '';

        if($type == 'one')
        {
            #$htmlString .= Clansuite_Datagrid_Renderer::renderTableActions();
            $htmlString .= Clansuite_Datagrid_Renderer::renderTableSearch();
            $htmlString .= Clansuite_Datagrid_Renderer::renderTablePagination();
        }

        if($type == 'two' or $type == 'three')
        {
            $htmlString .= '<tbody>';

            if($type == 'two')
            {
                #$htmlString .= Clansuite_Datagrid_Renderer::renderTableActions();
                $htmlString .= Clansuite_Datagrid_Renderer::renderTableRows();
            }

            if($type == 'three')
            {
                $htmlString .= Clansuite_Datagrid_Renderer::renderTableBatchActions();
                $htmlString .= Clansuite_Datagrid_Renderer::renderTablePagination(false);
            }

            $htmlString .= '</tbody>';
        }

        return $htmlString;
    }

    /**
     * Render the actions of the rows
     *
     * @return string Returns the html-code for the actions
     */
    private static function renderTableBatchActions()
    {
        $_BatchActions = self::getDatagrid()->getBatchActions();
        $htmlString = '';

        if( count($_BatchActions) > 0 && self::getDatagrid()->isEnabled('BatchActions') )
        {
            $config = null;
            $config = Clansuite_CMS::getInjector()->instantiate('Clansuite_Config')->toArray();

            $htmlString .= '<tr>';
            $htmlString .= '<td class="DatagridBatchActions"><input type="checkbox" class="DatagridSelectAll" /></td>';

            $htmlString .= '<td colspan=' . (self::getDatagrid()->getColumnCount()-1) . '>';
            $htmlString .= '<select name="action" id="BatchActionId">';
            $htmlString .= '<option value="'.$config['defaults']['action'].'">' . _('(Choose an action)') . '</option>';
            foreach( $_BatchActions as $BatchAction )
            {
                $htmlString .= '<option value="' . $BatchAction['Action'] . '">' . $BatchAction['Name'] . '</option>';
            }
            $htmlString .= '</select>';
            $htmlString .= '<input type="submit" value="' . _('Execute') . '" />';
            $htmlString .= '</td>';

            $htmlString .= '</tr>';
        }

        return $htmlString;
    }

    /**
     * Render all the rows
     *
     * @return string Returns the html-code for all rows
     */
    private static function renderTableRows()
    {
        $htmlString = '';
        $rowKey = null;

        $rows = self::getDatagrid()->getRows();

        $i = 0;
        foreach( $rows as $rowKey => $row )
        {
            $i++;
            # @todo consider removing the css alternating code, in favor of css3 tr:nth-child
            $htmlString .= Clansuite_Datagrid_Renderer::renderTableRow($row, !($i % 2));
        }

        # render a "no results" row
        if( $htmlString == '' )
        {
            $htmlString .= '<tr class="DatagridRow DatagridRow-NoResults">';
            $htmlString .= '<td class="DatagridCell DatagridCell-NoResults" colspan="'.self::getDatagrid()->getColumnCount().'">';
            $htmlString .= _('No Results');
            $htmlString .= '</td>';
            $htmlString .= '</tr>';
        }

        return $htmlString;
    }

    /**
     * Render a single row
     *
     * @param $row Clansuite_Datagrid_Row
     * @param $alternate row alternating toggle
     * @return string Returns the html-code for a single row
     */
    private static function renderTableRow($row, $alternate)
    {
        $_AlternateClass = '';

        if( $alternate === true )
        {
            $_AlternateClass = 'Alternate';
        }

        # @todo consider removing the css alternating code, in favor of css3 tr:nth-child
        $htmlString = null;
        $htmlString = '<tr class="DatagridRow DatagridRow-' . $row->getAlias() . ' ' . $_AlternateClass . '">';

        $_Cells = $row->getCells();
        foreach( $_Cells as $oCell )
        {
            $htmlString .= Clansuite_Datagrid_Renderer::renderTableCell($oCell);
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
    private static function renderTableCell($_oCell)
    {
        $htmlString = '';

        $htmlString .= '<td class="DatagridCell DatagridCell-Cell_' . $_oCell->getColumn()->getPosition() . '">' . $_oCell->render() . '</td>';

        return $htmlString;
    }

    /**
     * Render the column
     *
     * @param Clansuite_Datagrid_Column
     * @return string Returns the html-code for a single column
     */
    private static function renderTableColumn($columnObject)
    {
        $htmlString = '';
        $htmlString .= '<th id="ColHeaderId-'. $columnObject->getAlias() . '" class="ColHeader ColHeader-'. $columnObject->getAlias() .'">';
        $htmlString .= $columnObject->getName();

        if( $columnObject->isEnabled('Sorting') )
        {
            $htmlString .= '&nbsp;<a href="' . self::_getSortString($columnObject->getAlias(), self::getDatagrid()->getSortReverseDefinition($columnObject->getSortMode())) . '">';
            #$htmlString .= '<img src="' . WWW_ROOT_THEMES .'/'. $_SESSION['user']['theme'] .'/" />';
            $htmlString .= _($columnObject->getSortMode());
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
    private static function renderTableFooter()
    {
        if( self::getDatagrid()->isEnabled('Footer') )
        {
            $htmlString = '';
            $htmlString .= '<tfoot>'.CR;
            # @todo getter for footer html
            $htmlString .= '</tfoot>'.CR;
            return $htmlString;
        }
        else
        {
            return; #'<tfoot>&nsbp;</tfoot>';
        }
    }

    /**
     * Renders the search element of the table
     *
     * @return $string HTML Representation of the Table Search Element
     */
    private static function renderTableSearch()
    {
        $htmlString = '';
        if( self::getDatagrid()->isEnabled('Search') )
        {
            $htmlString .= '<tr><td colspan="'.self::getDatagrid()->getColumnCount().'">';
            $htmlString .= _('Search: ');
            $htmlString .= '<input type="text" value="'.htmlentities($_SESSION['Datagrid_' . self::getDatagrid()->getAlias()]['SearchValue']).'" name="'.self::getDatagrid()->getInputParameterName('SearchValue').'" />';
            $htmlString .= ' <select name="'.self::getDatagrid()->getInputParameterName('SearchKey').'">';
            $columnsArray = self::getDatagrid()->getColumns();
            foreach( $columnsArray as $columnObject )
            {
                if( $columnObject->isEnabled('Search') )
                {
                    $selected = '';
                    if($_SESSION['Datagrid_' . self::getDatagrid()->getAlias()]['SearchKey'] == $columnObject->getAlias())
                    {
                        $selected = ' selected="selected"';
                    }
                    $htmlString .= '<option value="'.$columnObject->getAlias().'"'.$selected.'>'.$columnObject->getName().'</option>';
                }
            }
            $htmlString .= '</select>';
            $htmlString .= ' <input type="submit" value="'._('Search').'" />';
            $htmlString .= '</td></tr>';
        }

        return $htmlString;
    }

    /**
     * Renders the datagrid table
     *
     * @return string Returns the HTML representation of the datagrid table
     */
    public function render()
    {
        # Execute the datagrid!
        self::getDatagrid()->execute();

        # Build htmlcode
        $htmlString = '';

        $htmlString .= '<link rel="stylesheet" type="text/css" href="'. WWW_ROOT_THEMES_CORE . '/css/datagrid.css" />'.CR;
        $htmlString .= '<script src="'. WWW_ROOT_THEMES_CORE . '/javascript/datagrid.js" type="text/javascript"></script>'.CR;

        $htmlString .= '<form action="' . self::getDatagrid()->getBaseURL() . '" method="post" name="Datagrid-' . self::getDatagrid()->getAlias() . '" id="Datagrid-' . self::getDatagrid()->getAlias() . '">'.CRT;

            #$_htmlCode .= '<input type="hidden" name="action" value="' . Clansuite_ActionController_Resolver::getDefaultActionName() . '" />';
            #$_htmlCode .= '<input type="hidden" name="action" id="ActionId" value="' . ((isset($_REQUEST['action'])&&preg_match('#^[0-9a-z_]$#i',$_REQUEST['action']))?$_REQUEST['action']:'show') . '" />';

            $input_field_sprintf = '<input type="hidden" name="%s" value="%s" />';
            $htmlString .= sprintf($input_field_sprintf, self::getDatagrid()->getInputParameterName('Page'), $this->getCurrentPage());
            $htmlString .= sprintf($input_field_sprintf, self::getDatagrid()->getInputParameterName('ResultsPerPage'), $this->getCurrentResultsPerPage());
            $htmlString .= sprintf($input_field_sprintf, self::getDatagrid()->getInputParameterName('SortKey'), $this->getCurrentSortKey());
            $htmlString .= sprintf($input_field_sprintf, self::getDatagrid()->getInputParameterName('SortValue'), $this->getCurrentSortValue());

            $htmlString .= '<div class="Datagrid ' . self::getDatagrid()->getClass() . '">'.CR;

                $htmlString .= Clansuite_Datagrid_Renderer::renderTable();

            $htmlString .= '</div>'.CR;

        $htmlString .= '</form>'.CR;

        return $htmlString;
    }
}
?>