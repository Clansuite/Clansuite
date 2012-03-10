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

namespace Koch\Datagrid;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

use DoctrineExtensions\Paginate\Paginate;

/**
 * Clansuite Datagrid
 *
 * Purpose:
 * Automatic datagrid generation from doctrine records/tables.
 * Doctrine_Table => Doctrine_Query => Clansuite_Datagrid
 *
 * Datagrid builds a table with the following structure:
 *
 * #--------------------------------------------------#
 * # Caption             (<caption>Caption</caption>) #
 * #--------------------------------------------------#
 * # Pagination        (<tr><td>Pagination</td></tr>) #
 * #--------------------------------------------------#
 * # Header     (<tr><th>Col1</th><th>Col2</th></tr>) #
 * #--------------------------------------------------#
 * # Rows            (<tr><td>DataField</td>...</tr>) #
 * #--------------------------------------------------#
 * # Pagination        (<tr><td>Pagination</td></tr>) #
 * #--------------------------------------------------#
 * # Footer                (<tr><td>Footer</td></tr>) #
 * #--------------------------------------------------#
 */
class Datagrid extends Base
{
    //--------------------
    // Class properties
    //--------------------

    /**
     * Associative Array for the configuration of columns.
     *
     * @var array
     */
    private $_columnSets = array();

    /**
     * Associative Array for the definition of BatchActions for this grid.
     * BatchActions are executed on several elements, lile edit, delete, move, etc.
     *
     * @var array
     */
    private $_batchActions = array();

    /**
     * Array with a class and method callback for altering the result set.
     *
     * @var array
     */
    private $dataModifyHook = array('class' => '', 'method' => '');

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
    private $columnObjects = array();

    private $sortColumn = null;
    private $sortOrder = null;

    /**
     * The datagrid type
     *
     * @todo implement
     * @var string
     */
    #private $_DatagridType  = 'Normal';

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
     * Lookpup/Mapping Array
     * For mapping internal variable names to their shorthand names.
     *
     * This determines the incoming and offgoing url parameter names
     * for Sorting, Pagingation, Search and Reset.
     * Structure: array( InternalName => ExternalShorthand )
     *
     * Example URL: ?sortC=Title&sortO=DESC&p=2&rpp=10
     *
     * @var array
     */
    private $_requestParameterAliasMap = array(
        'SortColumn'        => 'sortC',
        'SortOrder'         => 'sortO',
        'Page'              => 'p',
        'ResultsPerPage'    => 'rpp',
        'SearchForValue'    => 'searchfor',
        'SearchColumn'      => 'searchc',
        'Reset'             => 'reset'
    );

    /**
     * Doctrine Query object
     *
     * @var Doctrine_QueryBuilder
     */
    private $queryBuilder;

    /**
     * String representing the Entity to use
     * e.g. "\Entities\News"
     *
     * @var string
     */
    private $_doctrineEntityName;

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
     * Array of Clansuite_Datagrid_Row objects
     *
     * @var array
     */
    private $rowObjects = array();

    /**
     * Associated sortables
     *
     * @var array
     */
    private static $sortReverseMap = array(
        'ASC' => 'DESC',
        'DESC' => 'ASC',
        'NATASC' => 'NATDESC',
        'NATDESC' => 'NATASC'
    );

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
        $this->columnObjects = $_Cols;
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
     * Set the results per page
     *
     * @param int $resultsPerPage
     */
    public function setResultsPerPage($resultsPerPage)
    {
        $this->_resultsPerPage = $resultsPerPage;
    }

    public function setTotalResultsCount($count)
    {
        $this->totalResultsCount = $count;
    }

    /**
     * Set the hook for the resultset manipulation
     *
     * @param Object $class
     * @param Methodname $methodname
    */
    public function modifyResultSetViaHook($class, $methodname)
    {
        $this->dataModifyHook['class']  = $class;
        $this->dataModifyHook['method'] = $methodname;
    }

    /**
     * Sets the row objects of the grid (Clansuite_Datagrid_Row)
     *
     * @param array $_Rows
     */
    public function setRows($_Rows)
    {
        $this->rowObjects = $_Rows;
    }

    public function setDoctrineEntityName($entityname)
    {
        $this->_doctrineEntityName = $entityname;;
    }

    public function getDoctrineEntityName()
    {
        return $this->_doctrineEntityName;
    }

    public function getBatchActions()
    {
        return $this->_batchActions;
    }

    /**
     * Get a column depending on its alias
     *
     * @param string $sColumnKey Name of the Column
     * @return Clansuite_Datagrid_Column
     */
    public function getColumn($sColumnKey)
    {
        return $this->columnObjects[$sColumnKey];
    }

    /**
     * Amount of columns in the grid
     *
     * @return integer Amount of columns
     */
    public function getColumnCount()
    {
        return count($this->getColumns());
    }

    /**
     * Returns the column objects (Clansuite_Datagrid_Col)
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->columnObjects;
    }

    /**
     * Get the shorthand input parameter name depending on the
     * alias mapping: Internal "SortOrder" = External "sorto".
     * In other words: in a html representation the short "sorto" will be used,
     * internally its "SortOrder".
     *
     * @see $_requestParameterAliasMap
     *
     * @param string $_internalKey
     * @return string $this->_requestParameterAliasMap[$_InternalKey];
     */
    public function getParameterAlias($_internalKey)
    {
        return $this->_requestParameterAliasMap[$_internalKey];
    }

    /**
     * Get the renderer object
     *
     * @return Clansuite_Datagrid_Renderer
     */
    public function getRenderer()
    {
        return $this->_renderer;
    }

    /**
     * Get the results per page
     *
     * @return int
     */
    public function getResultsPerPage()
    {
        $resultsPerPage = null;

        # $resultsPerPage is incomming via Session, URL GET or Set to 1 as default
        if( isset($_SESSION['Datagrid_' . $this->getAlias()]['ResultsPerPage']) )
        {
            $resultsPerPage = $_SESSION['Datagrid_' . $this->getAlias()]['ResultsPerPage'];
        }
        elseif( isset($_REQUEST[$this->getParameterAlias('ResultsPerPage')]) )
        {
            $resultsPerPage = (int) $_REQUEST[$this->getParameterAlias('ResultsPerPage')];
        }
        else # if page is not inside session or request, we are on the first page
        {
            $resultsPerPage = $this->_resultsPerPage; # default via setResultsPerPage / config
        }

        # Add to session
        $_SESSION['Datagrid_' . $this->getAlias()]['ResultsPerPage'] = $resultsPerPage;

        return $resultsPerPage;
    }

    /**
     * Returns the number of results in total.
     *
     * @return int Number of results in total.
     */
    public function getTotalResultsCount()
    {
        return $this->totalResultsCount;
    }

    /**
     * Returns the row objects (Clansuite_Datagrid_Row)
     *
     * @return array
     */
    public function getRows()
    {
        return $this->rowObjects;
    }

    /**
     * Get the pendant (reverse definition) for a sort order
     *
     * @see $sortReverseMap
     * @return string ASC, DESC, NATASC, NATDESC
     */
    public function getSortDirectionOpposite($sortOrder)
    {
        return self::$sortReverseMap[$sortOrder];
    }

    public function getSortColumn()
    {
        return $this->sortColumn;
    }

    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    public function setSortColumn($sortColumn)
    {
        $this->sortColumn = $sortColumn;
    }

    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * Constructor
     *
     * @param array Options 'Entity', 'ColumnSets', 'Url'
     */
    public function __construct(array $options)
    {
        /**
         * Ensure Datagrid gets some valid options passed
         */
        $acceptedOptions = array( 'Entity', 'ColumnSets', 'Url');
        foreach($acceptedOptions as $value)
        {
            if(false === isset($options[$value]))
            {
                throw new Clansuite_Exception('Datagrid Option(s) missing. Valid options are: '. var_export($acceptedOptions, true));
            }
        }
        unset($value);

        # sets the doctrine entity to use for the datagrid
        # set manually        'Entity'        => 'Entities\News',
        # or automatically    getEntityNameFromClassname()
        if(isset($options['Entity']))
        {
            $this->setDoctrineEntityName($options['Entity']);
        }

        # Set all columns
        # @todo load from columnset definition file / remove array from module
        $this->setColumnSets($options['ColumnSets']);

        # construct url by appending to the baseURL
        $this->setBaseUrl($options['Url']);

        # disable some features
        # @todo css for these elements
        $this->disableFeature(array('Label', 'Caption', 'Description'));

        # set scalar values
        $this->setAlias( $this->getDoctrineEntityName() );

        # reset session?
        if( isset($_REQUEST[$this->getParameterAlias('Reset')]) )
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
        $this->generateColumns();

        # update the query
        #$this->_generateQuery();
    }

    /**
     * Execute the datagrid (query, pager, cols, rows, everything!)
     */
    public function execute()
    {
        # generate the doctrine query
        $query = $this->assembleQuery();

        $result = $query->getArrayResult();

        # Debug
        #Clansuite_Debug::printR($result);

        /**
         * The data set is an array.
         * It is passed to the row generator.
         * The row generator will take the data set generate the rows of the table and the cells within
         */
        $this->_generateRows($result);
    }

    /**
     * Setter for the data array to render as a grid.
     *
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Getter for the data array to render as a grid.
     */
    public function getData()
    {
        $this->data = $data;
    }

    /**
     * Generates a customized query for this entity/repository
     *
     * 1) $this->queryBuilder will contain a full select
     * 2) sorting is added
     * 3) search is added
     * 4) pagination limiting is is added
     *
     * var_dump($this->queryBuilder->getDQL());
     *
     * @see $this->queryBuilder
     */
    private function assembleQuery()
    {
        # get the "finder" method to use on the repository
        #$methodname = $this->getRepositoryMethodName();

        # call repository method with variable methodname
        #$this->query = $this->getDoctrineRepository()->$methodname();

        /**
         * get QueryBuilder so that we can append (sorting, search etc) to the Query
         */
        $this->queryBuilder = Clansuite_CMS::getEntityManager()
                       ->createQueryBuilder()
                       ->select('a')
                       ->from($this->getDoctrineEntityName(), 'a');

        $this->addSortingToQuery();
        $this->addSearchToQuery();
        $query = $this->addPaginationLimitToQuery();

        return $query;
    }

    /**
     * Check, if a datagrid feature is enabled.
     *
     * @see $this->_features
     * @param string $feature Name of the feature.
     * @return boolean True if enabled, false if disabled.
     */
    public function isEnabled($feature)
    {
        if( isset($this->_features[$feature]) === false )
        {
            throw new Clansuite_Exception(_('There is no such feature in this datagrid: ') . $feature);
        }
        else
        {
            return $this->_features[$feature];
        }
    }

    /**
     * Enables one or several datagrid feature(s)
     * Return true on success, false otherwise
     *
     * @see $this->_features
     * @param mixed(string|array) $features
     */
    public function enableFeature($features)
    {
        # if $features is only one feature; then it's just a string, so we typecast
        $features = (array) $features;

        foreach($features as $feature)
        {
            $this->_features[$feature] = true;
        }
    }

    /**
     * Disables one or more datagrid feature(s)
     * Returns true on success, false otherwise
     *
     * @see $this->_features
     * @param mixed(string|array) $features
     * @return boolean
     */
    public function disableFeature($features)
    {
        # if $features is only one feature; then it's just a string, so we typecast
        $features = (array) $features;

        # disable several datagrid features
        foreach($features as $feature)
        {
            $this->_features[$feature] = false;
        }
    }

    /**
     * Render the datagrid
     *
     * @return string $_html Returns html-code
     */
    public function render()
    {
        # Execute the datagrid!
        $this->execute();

        # attach Datagrid to renderer
        $renderer = new Clansuite_Datagrid_Renderer($this);

        return $renderer->render();
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

        # No alias given
        if( (isset($columnSet[$columnKey]) == false) or ($columnSet[$columnKey] == '') )
        {
            # define exception message string for usage with sprintf + gettext
            $exception_sprintf = _('The datagrid columnset has an error. The array key "%s" is missing.)');

            throw new Clansuite_Exception(sprintf($exception_sprintf, $columnKey));
        }
    }

    /**
     * Set Datagrid Cols (internally without auto-update)
     *
     * @params array Columns Array
     */
    private function setColumnSets($_columnSets = array())
    {
        #Clansuite_Debug::firebug($_columnSets);

        foreach( $_columnSets as $key => $columnSet )
        {
            #Clansuite_Debug::firebug($key);
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
    private function generateColumns()
    {
        $colSet = null;

        foreach( $this->_columnSets as $colKey => &$colSet )
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
                $oCol->setSortOrder($colSet['Sort']);
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

            # set the column object under its alias into the array of all columns
            $this->columnObjects[$colSet['Alias']] = $oCol;
        }
    }

    /**
     * This triggers the result set modification method.
     *
     * The modifying method works directly on the resultset,
     * so we use the reference operator.
     *
     * @param array $resultset  Results Array from Doctrine Query
     */
    private function triggerDataModificationHook(&$resultset)
    {
        if(isset($this->dataModifyHook['method']))
        {
            $this->dataModifyHook['class']->{$this->dataModifyHook['method']}($resultset);
        }
    }

    /**
     * Generates all row objects
     *
     * @param array Results-array from doctrine named query
     */
    private function _generateRows($data)
    {
        foreach( $data as $rowKey => $rowValues )
        {
            /**
             *  Trigger the Hook and pass dataSet for modification
             */
            $this->triggerDataModificationHook($rowValues);

            $oRow = new Clansuite_Datagrid_Row($this);

            $oRow->setAlias('Row_' . $rowKey);
            $oRow->setId('RowId_' . $rowKey);
            $oRow->setName('RowName_' . $rowKey);
            $oRow->setPosition($rowKey);

            foreach( $this->_columnSets as $columnKey => $columnSet )
            {
                $oCell = new Clansuite_Datagrid_Cell();
                $oRow->addCell($oCell);
                $this->rowObjects[$rowKey] = $oRow;

                $oCol = $this->columnObjects[$columnSet['Alias']];
                $oCol->addCell($oCell);

                $oCell->setColumnObject($oCol);
                $oCell->setRow($oRow);

                $values = $this->_getCellValues($columnSet, $rowValues);

                # Set empty values if not in dataset
                $oCell->setValues($values);
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

        if(false === is_array($_ColumnSet))
        {
            throw new Clansuite_Exception(_('You have not supplied any columnset to validate.'));
        }

        # Standard for ResultSets is an array
        if( false === is_array($_ColumnSet['ResultSet']))
        {
            $aResultSet = array($_ColumnSet['ResultSet']);
        }
        else
        {
            $aResultSet = $_ColumnSet['ResultSet'];
        }

        # init vars used inside foreach
        $i = 0;
        $values = array();

        foreach($aResultSet as $ResultKey => $ResultValue)
        {
            $_ArrayStructure = explode('.', $ResultValue);

            $_TmpArrayHandler = $_Dataset;

            foreach( $_ArrayStructure as $_LevelKey )
            {
                if( false === is_array($_TmpArrayHandler) or false === isset($_TmpArrayHandler[$_LevelKey]) )
                {
                    #Clansuite_Debug::firebug('ResultSet not found in Dataset: ' . $ResultValue, 'warn');
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
     * Generate the Sorts for a query
     *
     * Appends the Query by "ORDERBY fieldname sortorder"
     */
    private function addSortingToQuery()
    {
        # columnname
        $SortColumn    = '';
        # asc/desc
        $SortOrder  = '';

        # Set SortColumn and sortorder if in session
        if( isset($_SESSION['Datagrid_' . $this->getAlias()]['SortColumn']) and isset($_SESSION['Datagrid_' . $this->getAlias()]['SortOrder']) )
        {
            $SortColumn = $_SESSION['Datagrid_' . $this->getAlias()]['SortColumn'];
            $SortOrder  = $_SESSION['Datagrid_' . $this->getAlias()]['SortOrder'];
        }

        # Prefer requests
        if( isset($_REQUEST[$this->getParameterAlias('SortColumn')]) and isset($_REQUEST[$this->getParameterAlias('SortOrder')]) )
        {
            $SortColumn = $_REQUEST[$this->getParameterAlias('SortColumn')];
            $SortOrder  = $_REQUEST[$this->getParameterAlias('SortOrder')];
        }

        # Check for valid formats of key and value
        if( ($SortColumn != '' and $SortOrder != '') AND
            ( preg_match('#^([0-9a-z_]+)#i', $SortColumn) and preg_match('#([a-z]+)$#i', $SortOrder) ) )
        {
            $_SESSION['Datagrid_' . $this->getAlias()]['SortColumn']   = $SortColumn;
            $_SESSION['Datagrid_' . $this->getAlias()]['SortOrder'] = $SortOrder;

            $this->setSortColumn($SortColumn);
            $this->setSortOrder($SortOrder);

            $oCol = $this->getColumn($SortColumn);

            /**
             * Add Field based ordering to the Query
             */
            $this->queryBuilder->add('orderBy', 'a.' . $oCol->getSortField() . ' ' . $SortOrder);

            $oCol->setSortOrder($SortOrder);
        }
        else
        {
            $_SESSION['Datagrid_' . $this->getAlias()]['SortColumn']   = '';
            $_SESSION['Datagrid_' . $this->getAlias()]['SortOrder'] = '';
        }
    }

    /**
     * Generate the Serach for the query
     *
     * Appends the Query by "ANDWHERE fieldname LIKE SearchForValue"
     */
    private function addSearchToQuery()
    {
        $SearchColumn    = '';
        $SearchForValue  = '';

        if( isset($_SESSION['Datagrid_' . $this->getAlias()]['SearchColumn']) and isset($_SESSION['Datagrid_' . $this->getAlias()]['SearchForValue']) )
        {
            $SearchColumn    = $_SESSION['Datagrid_' . $this->getAlias()]['SearchColumn'];
            $SearchForValue  = $_SESSION['Datagrid_' . $this->getAlias()]['SearchForValue'];
        }

        if( isset($_REQUEST[$this->getParameterAlias('SearchColumn')]) and isset($_REQUEST[$this->getParameterAlias('SearchForValue')]) )
        {
            $SearchColumn    = $_REQUEST[$this->getParameterAlias('SearchColumn')];
            $SearchForValue  = $_REQUEST[$this->getParameterAlias('SearchForValue')];
        }

        $_SESSION['Datagrid_' . $this->getAlias()]['SearchColumn']     = $SearchColumn;
        $_SESSION['Datagrid_' . $this->getAlias()]['SearchForValue']   = $SearchForValue;

        # DEBUG
        # var_dump( $this->queryBuilder->getDQL() ); exit;

        # Check for valid formats of key and value
        if( ($SearchColumn != '' and $SearchForValue != '') )
        {
            $this->queryBuilder->add('andWhere',
                    # string = ANDWHERE a.fieldname LIKE :SearchForValue
                    $this->queryBuilder->expr()->like(
                            'a.' . $this->getColumn($SearchColumn)->getSortField(),
                            '%' . $SearchForValue . '%'
                            )
            );

            # DEBUG
            # var_dump( $this->queryBuilder->getDQL() ); exit;
        }
    }

    public function getPage()
    {
        $page = null;

        # Page is incomming via Session, URL GET or Set to 1 as default
        if(isset($_SESSION['Datagrid_' . $this->getAlias()]['Page']))
        {
            $page = $_SESSION['Datagrid_' . $this->getAlias()]['Page'];
        }
        elseif(isset($_REQUEST[$this->getParameterAlias('Page')]))
        {
            $page = (int) $_REQUEST[$this->getParameterAlias('Page')];
        }
        else
        {
            # default value: first page
            $page = 1;
        }

        # add page to session
        $_SESSION['Datagrid_' . $this->getAlias()]['Page'] = $page;

        return $page;
    }

    /**
     * Add pagination limits to the query
     *
     * http://www.mysqlperformanceblog.com/2008/09/24/four-ways-to-optimize-paginated-displays/
     */
    private function addPaginationLimitToQuery()
    {
        # Page (URL alias => p)
        $page = $this->getPage();

        # Results Per Page (URL alias => rpp)
        $resultsPerPage = $this->getResultsPerPage();

        # calculate offset = current page
        $offset = ($page - 1) * $resultsPerPage;

        /**
         * DQL does not have LIMIT and OFFSET capability
         * so we can't use $this->queryBuilder->add('limit',
         * D2 does limiting on hydration level ?
         */
        $query = $this->queryBuilder->getQuery();
        #$query->setFirstResult( $offset )->setMaxResults( $resultsPerPage );
        #Clansuite_Debug::printR($query->getArrayResult());

        # Step 1 - count total results
        $count = Paginate::getTotalQueryResults($query);
        $this->setTotalResultsCount($count);

        # Step 2 - adding limit and offset to the query
        $paginateQuery = Paginate::getPaginateQuery($query, $offset, $resultsPerPage);

        # fetching the paginated results set
        #Clansuite_Debug::printR($paginateQuery->getArrayResult());

        return $paginateQuery;
    }
}
?>