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

# conditional include of the parent class
if (false == class_exists('Clansuite_Datagrid_Column',false))
{
    include dirname(__FILE__) . '/datagridcol.core.php';
}

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
        if(self::$_baseURL === null)
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
     * Add an url-string to the baseurl
     *
     * @example
     *   $sUrl = $this->appendUrl('dg_Sort=0:ASC');
     *
     * @param string $appendString String to append to the URL.
     */
    public static function appendUrl($appendString)
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
                                    'Reset'             => 'reset' );

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
    private static $sortReverseMap = array(
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
    public function modifyResultSetViaHook($caller, $methodname)
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

    public function setDoctrineEntityName($entityname)
    {
        $this->_doctrineEntityName = $entityname;;
    }

    //--------------------
    // Getter
    //--------------------

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
        return $this->_columns[$sColumnKey];
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
        return $this->_columns;
    }

    /**
     * Get the shorthand input parameter name depending on the
     * alias mapping: Internal "SortOrder" = External "sorto"
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
        return $this->_resultsPerPage;
    }

    /**
     * Returns the row objects (Clansuite_Datagrid_Row)
     *
     * @return array
     */
    public function getRows()
    {
        return $this->_rows;
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

    //--------------------
    // Class methods
    //--------------------

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

        # attach Datagrid to renderer
        $this->setRenderer(new Clansuite_Datagrid_Renderer($this)); # @todo why pass $this?

        # sets the doctrine entity to use for the datagrid
        # set manually        'Entity'        => 'Entities\News',
        # or automatically    getEntityNameFromClassname()
        if(isset($options['Entity']))
        {
            $this->setDoctrineEntityName($options['Entity']);
        }

        # Set all columns
        # @todo load from columnset definition file / remove array from module
        $this->_setColumnSets($options['ColumnSets']);

        # construct url by appending to the baseURL
        $this->setBaseUrl($options['Url']);

        # disable some features
        # @todo css for these elements
        $this->disableFeature(array('Label', 'Caption', 'Description'));

        # set scalar values
        $this->setAlias( get_called_class() );

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
        $this->assembleQuery();

        # execute the doctrine-query
        #$this->datasets = $this->getPagerLayout()->getPager()->execute();

        # Get Array/Object Result Set
        #$result = $this->queryBuilder->getQuery()->getResult();

        # Get Array Result Set
        $result = $this->queryBuilder->getQuery()->getArrayResult();

        # Debug
        # Clansuite_Debug::printR($result);
        #Clansuite_Debug::firebug($result);

        # update the current page
        #$this->getRenderer()->setCurrentPage($this->getPagerLayout()->getPager()->getPage());

        # generate the data-rows
        $this->_generateRows($result);
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
     * Enables one or several datagrid feature(s)
     * Return true on success, false otherwise
     *
     * @see $this->_features
     * @param mixed(string|array) $features
     */
    public function enableFeature($features)
    {
        # if $feature is only one feature; then it's just a string, so we typecast
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
        # if $feature is only one feature; then it's just a string, so we typecast
        $features = (array) $features;

        # disable several datagrid features
        foreach($features as $feature)
        {
            $this->_features[$feature] = false;
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
     * Set Datagrid Cols (internally without auto-update)
     *
     * @see $this->_setColumnSets();
     * @params array Columns Array
     */
    public function setColumnSets($_ColumnSets = array())
    {
        $this->_setColumnSets($_ColumnSets);

        # generate a doctrine query
        # @todo why assemlbe the query here?
        $this->assembleQuery();
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
                #Clansuite_Debug::firebug($dataSet);
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
     * Generates a customized query for this entity/repository
     *
     * 1) $this->queryBuilder will contain a full select
     * 2) sorting is added
     * 3) search is added
     * 4) pager is is  added
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

        #var_dump($this->queryBuilder->getDQL()); ' = string 'SELECT a FROM Entities\News a'

        $this->addSortingToQuery();
        $this->addSearchToQuery();
        $this->addPagerToQuery();
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
            $SortColumn    = $_SESSION['Datagrid_' . $this->getAlias()]['SortColumn'];
            $SortOrder  = $_SESSION['Datagrid_' . $this->getAlias()]['SortOrder'];
        }

        # Prefer requests
        if( isset($_REQUEST[$this->getParameterAlias('SortColumn')]) and isset($_REQUEST[$this->getParameterAlias('SortOrder')]) )
        {
            $SortColumn    = $_REQUEST[$this->getParameterAlias('SortColumn')];
            $SortOrder  = $_REQUEST[$this->getParameterAlias('SortOrder')];
        }

        # Check for valid formats of key and value
        if( ($SortColumn != '' and $SortOrder != '') AND
            ( preg_match('#^([0-9a-z_]+)#i', $SortColumn) and preg_match('#([a-z]+)$#i', $SortOrder) ) )
        {
            $_SESSION['Datagrid_' . $this->getAlias()]['SortColumn']   = $SortColumn;
            $_SESSION['Datagrid_' . $this->getAlias()]['SortOrder'] = $SortOrder;

            $this->getRenderer()->setCurrentSortColumn($SortColumn);
            $this->getRenderer()->setCurrentSortOrder($SortOrder);

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
                    $qb->expr()->like(
                            'a.' . $this->getColumn($SearchColumn)->getSortField(),
                            '%' . $SearchForValue . '%'
                            )
            );

            # DEBUG
            # var_dump( $this->queryBuilder->getDQL() ); exit;
        }
    }

    /**
     * Generate the PagerLayout for a query
     */
    private function addPagerToQuery()
    {
        $page = null;

        # Page is incomming via Session, URL GET or Set to 1 as default
        if( isset($_SESSION['Datagrid_' . $this->getAlias()]['Page']) )
        {
            $page = $_SESSION['Datagrid_' . $this->getAlias()]['Page'];
        }
        elseif( isset($_REQUEST[$this->getParameterAlias('Page')]) )
        {
            $page = (int) $_REQUEST[$this->getParameterAlias('Page')];

        }
        else # if page is not inside session or request, we are on the first page
        {
            $page = 1;
        }

        # Add to session
        $_SESSION['Datagrid_' . $this->getAlias()]['Page'] = $page;

        # Add to renderer
        $this->getRenderer()->setCurrentPage($page);
        $this->getRenderer()->setCurrentResultsPerPage($this->getResultsPerPage());

        /**
         * Add Pager Layout
         *
         * offset = current page
         * limitPerPage = resultsPerPage
         */

        #use DoctrineExtensions\Paginate\Paginate;
        /*
        $this->getDoctrineRepository()->
        $query = $em->createQuery($dql);

        $count         = Paginate::getTotalQueryResults($query);
        $paginateQuery = Paginate::getPaginateQuery($query, $offset, $limitPerPage);
        $result        = $paginateQuery->getResult();
        */
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
    public function setColumnObject($_columnObject)
    {
        $this->_columnObject = $_columnObject;
    }

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
    public function setRow($_Row)
    {
        $this->_Row = $_Row;
    }

    /**
     * Set the value of the cell
     *
     * @param mixed A single value ($_Value[0])
     */
    public function setValue($_Value)
    {
        $this->_Values[0] = $_Value;
    }

    /**
     * Set the values of the cell
     *
     * @param array Array of values
     */
    public function setValues($_Values)
    {
        $this->_Values = $_Values;
    }

    //----------------------
    // Getter
    //----------------------

    /**
     * Returns the column object of this cell
     *
     * @return Clansuite_Datagrid_Column $_columnObject
     */
    public function getColumn()
    {
        return $this->_columnObject;
    }

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
    public function getRow()
    {
        return $this->_Row;
    }


    /**
     * Returns the value of this cell
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->_Values[0];
    }

    /**
     * Returns the values of this cell
     *
     * @return array
     */
    public function getValues()
    {
        return $this->_Values;
    }

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
     * Holds the current sort column
     *
     * @var string
     */
    private $_CurrentSortColumn;

    /**
     * Holds the current sort order (asc, desc)
     *
     * @var string
     */
    private $_CurrentSortOrder;

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
    public function setCurrentPage($_Page)
    {
        $this->_CurrentPage = $_Page;
    }

    /**
     * Sets the current results per page
     *
     * @param int
     */
    public static function setCurrentResultsPerPage($_Value)
    {
        self::$_CurrentResultsPerPage = $_Value;
    }

    /**
     * Sets the current sortkey
     *
     * @param string
     */
    public function setCurrentSortColumn($_SortKey)
    {
        $this->_CurrentSortColumn = $_SortKey;
    }

    /**
     * Sets the current sortkey
     *
     * @param string
     */
    public function setCurrentSortOrder($_SortOrder)
    {
        $this->_CurrentSortOrder = $_SortOrder;
    }

    /**
     * Set the datagrid object
     *
     * @param Clansuite_Datagrid $_Datagrid
     */
    public static function setDatagrid($_Datagrid)
    {
        self::$_Datagrid = $_Datagrid;
    }

    /**
     * Set the pager layout
     *
     * @see $_PagerLayoutString
     * @param string
     * @example
     *   $datagrid->getRenderer()->setPagerLayout('[{%page}]');
     */
    public function setPagerLayoutString($_PagerLayout)
    {
        $this->_PagerLayout = $_PagerLayout;
    }

    /**
     * Set the pager link layout
     *
     * @see $_PagerLinkLayoutString
     * @param string
     * @example
     *   $datagrid->getRenderer()->setPagerLinkLayout('[<a href="{%url}">{%page}</a>]');
     */
    public function setPagerLinkLayoutString($_PagerLinkLayout)
    {
        $this->_PagerLinkLayout = $_PagerLinkLayout;
    }

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
    public function getCurrentPage()
    {
        return $this->_CurrentPage;
    }

    /**
     * Gets the current results per page
     *
     * @return int
     */
    public static function getCurrentResultsPerPage()
    {
        return self::$_CurrentResultsPerPage;
    }

    /**
     * Gets the current sortkey
     *
     * @return string
     */
    public function getCurrentSortColumn()
    {
        return $this->_CurrentSortColumn;
    }

    /**
     * Gets the current sortvalue
     *
     * @return string
     */
    public function getCurrentSortOrder()
    {
        return $this->_CurrentSortOrder;
    }

    /**
     * Get the Datagrid object
     *
     * @return Clansuite_Datagrid $_Datagrid
     */
    public static function getDatagrid()
    {
        return self::$_Datagrid;
    }

    /**
     * Get the pager layout
     *
     * @return string
     */
    public function getPagerLayoutString()
    {
        return $this->_PagerLayoutString;
    }

    /**
     * Get the pager link layout
     *
     * @return string
     */
    public function getPagerLinkLayoutString()
    {
        return $this->_PagerLinkLayoutString;
    }

    /**
     * Get the items for the dropdownbox of results per page
     *
     * @return string
     */
    public static function getResultsPerPageItems()
    {
        return self::$_ResultsPerPageItems;
    }

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
        $_innerTableContent .= self::renderTableCaption();
        $_innerTableContent .= self::renderTableBody('one');
        $_innerTableContent .= self::renderTableHeader();      # this isn't a <thead> tag, but <tbody>
        $_innerTableContent .= self::renderTableBody('two');
        $_innerTableContent .= self::renderTableBody('three');
        $_innerTableContent .= self::renderTableFooter();

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
            $htmlString .= self::renderLabel();
            $htmlString .= self::renderDescription();
            $htmlString .= '</caption>'.CR;

        }

        return $htmlString;
    }

    /**
     * Represents a sortstring for a-Tags
     *
     * @param string SortKey
     * @param string SortOrder
     * @return string Returns a string such as index.php?mod=news&action=admin&sortc=Title&sorto=DESC
     */
    private static function getURLStringWithSorting($_SortColumn, $_SortOrder)
    {
        $url_string = sprintf('?%s=%s&%s=%s',
                               self::getDatagrid()->getParameterAlias('SortColumn'),   $_SortColumn,
                               self::getDatagrid()->getParameterAlias('SortOrder'), $_SortOrder
                             );

        return self::getDatagrid()->appendUrl($url_string);
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
            $htmlString .= self::renderTableColumn($column);
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
        #Clansuite_Debug::firebug('Pagination: ' . self::getDatagrid()->getPagerLayout());
        if(self::getDatagrid()->isEnabled('Pagination'))
        {
            $htmlString .= '<tr><td class="DatagridPagination DatagridPagination-' . self::getDatagrid()->getAlias() . '" colspan="' . self::getDatagrid()->getColumnCount() . '">';
            #$htmlString .= '<div class="Pages"><span class="PagerDescription">' . _('Pages: ') . '</span>' . self::getDatagrid()->getPagerLayout() . '</div>';

            if($_ShowResultsPerPage)
            {
                $htmlString .= '<div class="ResultsPerPage">';
                $htmlString .= '<select name="' . self::getDatagrid()->getParameterAlias('ResultsPerPage') . '" onchange="this.form.submit();">';
                $_ResultsPerPageItems = self::getResultsPerPageItems();
                foreach($_ResultsPerPageItems as $ItemCount)
                {
                    $htmlString .= '<option value="' . $ItemCount . '" ' . ((self::getCurrentResultsPerPage() == $ItemCount) ? 'selected="selected"' : '') . '>' . $ItemCount . '</option>';
                }
                $htmlString .= '</select>';
                $htmlString .= '</div>';
            }
            else
            {
                $htmlString .= '<div class="ResultsPerPage">';
                #$htmlString .= self::getDatagrid()->getPagerLayout()->getPager()->getNumResults() . _(' items');
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
            #$htmlString .= self::renderTableActions();
            $htmlString .= self::renderTableSearch();
            $htmlString .= self::renderTablePagination();
        }

        if($type == 'two' or $type == 'three')
        {
            $htmlString .= '<tbody>';

            if($type == 'two')
            {
                #$htmlString .= self::renderTableActions();
                $htmlString .= self::renderTableRows();
            }

            if($type == 'three')
            {
                $htmlString .= self::renderTableBatchActions();
                $htmlString .= self::renderTablePagination(false);
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
            $htmlString .= self::renderTableRow($row, !($i % 2));
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
            $htmlString .= self::renderTableCell($oCell);
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
            $htmlString .= '&nbsp;<a href="' . self::getURLStringWithSorting($columnObject->getAlias(), self::getDatagrid()->getSortDirectionOpposite($columnObject->getSortOrder())) . '">';
            #$htmlString .= '<img src="' . WWW_ROOT_THEMES .'/'. $_SESSION['user']['frontend_theme'] .'/" />';
            $htmlString .= _($columnObject->getSortOrder());
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
            $htmlString .= '<input type="text" value="'.htmlentities($_SESSION['Datagrid_' . self::getDatagrid()->getAlias()]['SearchForValue']).'" name="'.self::getDatagrid()->getParameterAlias('SearchForValue').'" />';
            $htmlString .= ' <select name="'.self::getDatagrid()->getParameterAlias('SearchColumn').'">';
            $columnsArray = self::getDatagrid()->getColumns();
            foreach( $columnsArray as $columnObject )
            {
                if( $columnObject->isEnabled('Search') )
                {
                    $selected = '';
                    if($_SESSION['Datagrid_' . self::getDatagrid()->getAlias()]['SearchColumn'] == $columnObject->getAlias())
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

        $htmlString .= '<link rel="stylesheet" type="text/css" href="'. WWW_ROOT_THEMES_CORE . 'css/datagrid.css" />'.CR;
        $htmlString .= '<script src="'. WWW_ROOT_THEMES_CORE . 'javascript/datagrid.js" type="text/javascript"></script>'.CR;

        $htmlString .= '<form action="' . self::getDatagrid()->getBaseURL() . '" method="post" name="Datagrid-' . self::getDatagrid()->getAlias() . '" id="Datagrid-' . self::getDatagrid()->getAlias() . '">'.CRT;

            #$_htmlCode .= '<input type="hidden" name="action" value="' . Clansuite_Action_Controller_Resolver::getDefaultActionName() . '" />';
            #$_htmlCode .= '<input type="hidden" name="action" id="ActionId" value="' . ((isset($_REQUEST['action'])&&preg_match('#^[0-9a-z_]$#i',$_REQUEST['action']))?$_REQUEST['action']:'show') . '" />';

            $input_field_sprintf = '<input type="hidden" name="%s" value="%s" />';
            $htmlString .= sprintf($input_field_sprintf, self::getDatagrid()->getParameterAlias('Page'), $this->getCurrentPage());
            $htmlString .= sprintf($input_field_sprintf, self::getDatagrid()->getParameterAlias('ResultsPerPage'), $this->getCurrentResultsPerPage());
            $htmlString .= sprintf($input_field_sprintf, self::getDatagrid()->getParameterAlias('SortColumn'), $this->getCurrentSortColumn());
            $htmlString .= sprintf($input_field_sprintf, self::getDatagrid()->getParameterAlias('SortOrder'), $this->getCurrentSortOrder());

            $htmlString .= '<div class="Datagrid ' . self::getDatagrid()->getClass() . '">'.CR;

                $htmlString .= self::renderTable();

            $htmlString .= '</div>'.CR;

        $htmlString .= '</form>'.CR;

        return $htmlString;
    }
}
?>