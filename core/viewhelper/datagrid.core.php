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
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id: formgenerator.core.php 3926 2010-01-19 21:13:23Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.');}

/**
* Clansuite Datagrid Base
*
* Purpose:
* Supply methods for all datagrid-subclasses
*/
class Clansuite_Datagrid_Base
{
    // scoped vars
    private $_alias;
    private $_id;
    private $_name;
    private $_class;
    private $_style;

    // Setters
    public function setAlias($_)   { $this->_alias = $_; }
    public function setName($_)    { $this->_name = $_; }
    public function setClass($_)   { $this->_class = $_; }
    public function setId($_)      { $this->_id = $_; }
    public function setStyle($_)   { $this->_style = $_; }

    // Getters
    public function getAlias()   { return $this->_alias = $_; }
    public function getName()    { return $this->_name = $_; }
    public function getClass()   { return $this->_class = $_; }
    public function getId()      { return $this->_id = $_; }
    public function getStyle()   { return $this->_style = $_; }
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
    /**
    * Doctrine Datatable object
    *
    * @var Doctrine_Datatable
    */
    private $_Datatable;

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
    private $_QueryName;

    /**
    * Doctrine Pager object
    *
    * @link http://www.doctrine-project.org/documentation/manual/1_2/en/utilities#pagination:working-with-pager Doctrine_Pager
    * @var mixed
    */
    private $_PagedQuery;

    /**
    * The renderer for the datagrid
    *
    * @var Clansuite_Datagrid_Renderer
    */
    private $_Renderer;

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
    * Array of Clansuite_Datagrid_Col objects
    *
    * @var array
    */
    private $_cols          = array();

    /**
    * Array of Clansuite_Datagrid_Row objects
    *
    * @var array
    */
    private $_rows          = array();

    /**
    * Array of Clansuite_Datagrid_Element objects
    *
    * @var array
    */
    private $_elements      = array();


    /**
    * The label for this datagrid (<div>...</div>)
    *
    * @var string
    */
    private $_label         = 'Datagrid';

    /**
    * The caption (<caption>...</caption>) for this datagrid
    *
    * @var string
    */
    private $_caption       = 'Datagrid';

    /**
    * The description for this datagrid
    *
    * @var string
    */
    private $_description   = 'This is a clansuite datagrid';

    /**
    * The datagrid type
    *
    * @todo implement
    * @var string
    */
    private $_datagridtype  = 'Normal';

    /**
    * Boolean datagrid values for configuration, wrapped into an array
    *
    * @var array
    */
    private $_features = array(
        'label'         => true,
        'caption'       => true,
        'description'   => true,
        'header'        => true,
        'pagination'    => true,
        'footer'        => true,
        'sorting'       => true
    );

    /**
    * Check for datagrid features
    *
    * @see $this->_features
    * @param string $feature
    * @return boolean $this->features[$_feature]
    */
    public function isEnabled($feature)
    {
        if( !isset($this->_features[$feature]) )
        {
            throw new Clansuite_Exception(_('There is no such feature in this datagrid: ') . $feature);
        }
        else
        {
            return $this->_features[$feature];
        }
    }

    /**
    * Enable datagrid features and return true if it succeeded, false if not
    *
    * @see $this->_features
    * @param mixed $feature
    * @return boolean
    */
    public function enableFeature($feature)
    {
        if( !isset($this->_features[$feature]) )
        {
            return 0;
        }
        else
        {
            $this->_features[$feature] = true;
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
    public function disableFeature($toggleVar)
    {
        if( !isset($this->_features[$feature]) )
        {
            return 0;
        }
        else
        {
            $this->_features[$feature] = false;
            return 1;
        }
    }

    /**
    * Constructor
    *
    * @param Doctrine_Table Doctrine_Table
    * @param string Doctrine_Query_Name
    */
    public function __construct($_Datatable, $_QueryName, $_ColumnSets)
    {
        if( !($_Datatable instanceof Doctrine_Table) )
        {
            throw new Clansuite_Exception(_('Incoming data seems not to be a valid Doctrine_Table'));
        }

        # set scalar values
        $this->setAlias('DatagridAlias');
        $this->setId('DatagridId');
        $this->setName('DatagridName');
        $this->setClass('DatagridClass');

        # attach operator to renderer
        $this->setRenderer(new Clansuite_Datagrid_Renderer($this));

        # set the internal ref for the datatable of doctrine
        $this->_Datatable = $_Datatable;

        # generate anonymous object for better and smoother handling
        $this->_Datagrid = new Clansuite_Datagrid();

        # Set all columns
        $this->_setDatagridCols($_ColumnSets);

        # set queryname
        $this->_QueryName = $_QueryName;

        # generate a doctrine query
        $this->_generateQuery();

        # generate default datasets that can be overwritten
        $this->_initDatagrid();
    }

    /**
    * Set the renderer object
    *
    * @param Clansuite_Datagrid_Renderer $_Renderer
    */
    public function setRenderer($_Renderer) { $this->_Renderer = $Renderer; }

    /**
    * Get the renderer object
    *
    * @return Clansuite_Datagrid_Renderer
    */
    public function getRenderer() { return $this->_Renderer; }

    /**
    * Set the Datatable of the generator
    */
    private function _initDatagrid()
    {
        # backreferences
        $this->datatable    = $this->_Datatable;
        $this->query        = $this->_Query;
        $this->pagedquery   = $this->_PagedQuery;



        $_Result = $this->_PagedQuery->execute();

        $this->rows = $this->_generateRows($_Result);
        #Clansuite_Xdebug::printR($_Result);

        # scalar values
        $this->alias         = $this->_Datatable->getClassnameToReturn();
        $this->id            = 'Datagrid' . $this->alias . 'Id';
        $this->name          = 'Datagrid' . $this->alias . 'Name';
        $this->class         = 'Datagrid' . $this->alias . 'Class';
        $this->label         = $this->alias;
        $this->caption       = $this->alias;

        /*
        $this->cols          = array();
        $aColumns = $this->_Datatable->getColumnNames();
        $this->colcount = count($aColumns);

        for($i=0;$i<$this->colcount;$i++)
        {
            $this->cols[$i] = array(
                    'Alias'   => $aColumns[$i],
                    'Name'    => _($aColumns[$i]),
                    'Sort'    => 'ASC'
            );
        }
        */
        $this->description   = '';
        $this->datagridtype  = 'Normal';

        # boolean values
        $this->label_enabled         = true;
        $this->caption_enabled       = true;
        $this->header_enabled        = true;
        $this->pagination_enabled    = true;
        $this->description_enabled   = true;
        $this->footer_enabled        = true;
        $this->sorting_enabled       = true;

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
    * Set the Datatable of the generator
    *
    * @param Doctrine_Table $Datatable
    */
    public function setDatatable(Doctrine_Table $_Datatable)
    {
        $this->_Datatable = $_Datatable;
        $this->_updateDatagrid();
    }

    /**
    * Get the Datatable of the generator
    *
    * @return Doctrine_Table $Datatable
    */
    public function getDatatable()
    {
        return $this->_Datatable;
    }

    /**
    * Set Datagrid Cols (internally without auto-update)
    *
    * @params array Columns Array
    * @example
    *    $this->_setDatagridCols = array(
    *           0 => array(
    *                    'Alias'   => 'Title',
    *                    'DBCol'   => 'n.news_title',
    *                    'Name'    => _('Title'),
    *                    ),
    *           1 => array(
    *                    'Alias'   => 'Status',
    *                    'DBCol'   => 'n.news_status',
    *                    'Name'    => _('Status'),
    *                    ),
    *    );
    */
    private function _setDatagridCols($_ColumnSets = array())
    {
        foreach( $_ColumnSets as $key => $colSet )
        {
            if( !isset($colSet['Alias']) || $colSet['Alias'] == '' )
            { throw new Clansuite_Exception(sprintf(_('The datagrid columnset has an error at key %s (arraykey "Alias" is missing)'), $key)); }

            if( !isset($colSet['DBCol']) || $colSet['DBCol'] == '' )
            { throw new Clansuite_Exception(sprintf(_('The datagrid columnset has an error at key %s (arraykey "DBCol" is missing)'), $key)); }

            if( !isset($colSet['Name']) || $colSet['Name'] == '')
            { throw new Clansuite_Exception(sprintf(_('The datagrid columnset has an error at key %s (arraykey "Name" is missing)'), $key)); }

            if( !isset($colSet['Sort']) || $colSet['Sort'] == '')
            {
                $colSet['Sort'] = 'DESC';
            }
        }
        $this->cols = $_ColumnSets;
    }

    /**
    * Set Datagrid Cols (internally without auto-update)
    *
    * @params array Columns Array
    * @example
    *    $oDatagrid->setDatagridCols = array(
    *           0 => array(
    *                    'Alias'   => 'Title',
    *                    'DBCol'   => 'n.news_title',
    *                    'Name'    => _('Title'),
    *                    ),
    *           1 => array(
    *                    'Alias'   => 'Status',
    *                    'DBCol'   => 'n.news_status',
    *                    'Name'    => _('Status'),
    *                    ),
    *    );
    */
    public function setDatagridCols($_ColumnSets = array())
    {
        $this->_setDatagridCols($_ColumnSets);
        $this->_updateDatagrid();
    }

    /**
     * Generates all cleaned-up rows
     *
     * @return array $_Rows Return cleaned rows for the datagrid
     */
    private function _generateRows($_Result)
    {
        foreach( $_Result as $dbKey => $dbSet )
        {
            foreach( $this->cols as $colKey => $colSet )
            {
                if( isset($dbSet[$colSet['DBCol']]) )
                {
                    $this->rows[$dbKey][$colSet['Alias']] = $dbSet[$colSet['DBCol']];
                }
            }
        }
        Clansuite_Xdebug::printR($this->rows);
    }

    /**
     * Generates a customized query for this table
     *
     * @return NULL
     */
    private function _generateQuery()
    {
        $_Query = array();
        if( isset($this->_QueryName) )
        {
            $_Query = $this->_Datatable->createNamedQuery($this->_QueryName);
        }
        else
        {
            $_Query = $this->_Datatable->createQuery()
                                ->select('*')
                                ->setHydrationMode(Doctrine::HYDRATE_ARRAY);
        }

        $this->_Query = $_Query;

        $this->_generateQuerySorts();
        $this->_generatePager();

        return;
    }

    /**
    * Generate the Sorts for a query
    *
    * @return NULL
    */
    private function _generateQuerySorts()
    {

        $aSort = array();
        if( isset($_REQUEST['dg_Sort']) )
        {
            if( preg_match('#^([0-9]+):([a-z]+)$#i', $_REQUEST['dg_Sort'], $aSort) )
            {
                $SortKey = $aSort[1];
                $SortValue = $aSort[2];
            }
        }
        #Clansuite_Xdebug::printR($SortValue);
        if( isset($SortKey) && isset($this->_sortReverseDefinitions[$SortValue]))
        {
            $this->_Query->orderBy($this->cols[$SortKey]['DBCol'] . ' ' . $SortValue);
            $this->cols[$SortKey]['Sort'] = $SortValue;
        }

        #Clansuite_Xdebug::printR($this->_Query->getSqlQuery());
    }

    /**
    * Generate the Pager for a query
    *
    * @todo sanitize by vain
    * @return NULL
    */
    private function _generatePager()
    {
        $_Page = 1;
        $_ResultsPerPage = 25;

        if( isset($_REQUEST['dg_Page']) )
        {
            $_Page = (int) $_REQUEST['dg_Page'];
        }

        if( isset($_REQUEST['dg_ResultsPerPage']) )
        {
            $_ResultsPerPage = (int) $_REQUEST['dg_ResultsPerPage'];
        }


        $this->_PagedQuery = new Doctrine_Pager($this->_Query, $_Page, $_ResultsPerPage);

        return;
    }

}

class Clansuite_Datagrid_Renderer
{
    /**
    * The operator for this datagrid
    *
    * @var Clansuite_Datagrid_Operator
    */
    private $_Operator;

    /**
    * Set the operator object
    *
    * @param Clansuite_Datagrid_Operator $_Operator
    */
    public function setOperator($_Operator) { $this->_Operator = $_Operator; }

    /**
    * Get the operator object
    *
    * @return Clansuite_Datagrid_Operator $_Operator
    */
    public function getOperator() { return $this->_Operator; }

    /**
    * Instantiate renderer and attack operator to it
    *
    * @param Clansuite_Datagrid_Operator $_Operator
    * @return Clansuite_Datagrid_Renderer
    */
    public function __construct($_Operator)
    {
        $this->setOperator($_Operator);
    }

    /**
    * Render the datagrid table
    *
    * @param String HTMLCode Returns the html-code for the table
    * @return String HTMLCode Returns the html-code of the datagrid
    */
    private function _renderTable($_innerTableData)
    {
        return '<table class="DatagridTable '. $this->_Datagrid->class .'" id="'. $this->_Datagrid->id .'" name="'. $this->_Datagrid->name .'">' . CR . $_innerTableData  . CR . '</table>';
    }



    /**
    * Render the label
    *
    * @return String HTMLCode Returns the html-code for the label if enabled
    */
    private function _renderLabel()
    {
        if( $this->_Datagrid->label_enabled === true )
            return '<div class="DatagridTableLabel">' . CR . $this->_Datagrid->label . CR . '</div>';
        else
            return;
    }


    /**
    * Render the description
    *
    * @return String HTMLCode Returns the html-code for the description
    */
    private function _renderDescription()
    {
        if( $this->_Datagrid->description_enabled === true )
            return '<div class="DatagridTableDescription">' . CR . $this->_Datagrid->description . CR . '</div>';
        else
            return;
    }


    /**
    * Render the caption
    *
    * @return String HTMLCode Returns the html-code for the caption
    */
    private function _renderTablecaption()
    {
        if( $this->_Datagrid->caption_enabled === true )
        {
            $htmlString = '';
            $htmlString .= '<caption>';
            $htmlString .= $this->_Datagrid->caption;
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
    * @return string $sSort A string such as ?mod=news&amp;action=admin&amp;dg_Sort=0;ASC&amp;
    */
    private function _getSortString($_colKey, $_sortMode)
    {
        $sSort = '?';

        if( isset($_GET['dg_Sort']) )
        {
            foreach( $_GET as $key => $value )
            {
                if( preg_match('#^[a-z0-9:_]#i', $key) && preg_match('#^[a-z0-9:_]#i', $key) )
                {
                    if($key == 'dg_Sort')
                    {
                        $value = $_colKey . ':' . $_sortMode;
                    }
                    $sSort .= $key . '=' . $value . '&amp;';
                }
            }
            return substr($sSort, 0, strlen($sSort)-5);
        }
        else
        {
            foreach( $_GET as $key => $value )
            {
                if( preg_match('#^[a-z0-9:_]#i', $key) && preg_match('#^[a-z0-9:_]#i', $key) )
                {
                    $sSort .= $key . '=' . $value . '&amp;';
                }
            }
            return $sSort . 'dg_Sort=' . $_colKey . ':' . $_sortMode;
        }
    }

    /**
    * Render the header
    *
    * @return String HTMLCode Returns the html-code for the header
    */
    private function _renderTableHeader()
    {
        if( $this->_Datagrid->header_enabled === true )
        {

            $htmlString = '';
            $htmlString .= '<thead>';
            $htmlString .= '<tr>';

            foreach( $this->_Datagrid->cols as $colKey => $colSet )
            {
                $htmlString .= '<th id="ColHeaderId-'. $colSet['Alias'] . '" class="ColHeader ColHeader-'. $colSet['Alias'] .'">';
                $htmlString .= $colSet['Name'];
                $htmlString .= '&nbsp;<a href="' . $this->_getSortString($colKey, $this->_Datagrid->_sortReverseDefinitions[$colSet['Sort']]) . '">' . _($colSet['Sort']) . '</a>';
                $htmlString .= '</th>';
            }
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
    * @return String HTMLCode Returns the html-code for pagination
    */
    private function _renderTablePagination()
    {
        if( $this->_Datagrid->pagination_enabled === true )
        {
            return '';
        }
        else
        {
            return;
        }
    }

    /**
    * Render the body
    *
    * @return String HTMLCode Returns the html-code for the body
    */
    private function _renderTableBody()
    {
        $htmlString = '';
        $htmlString .= '<tbody>';
        $htmlString .= $this->_renderTableRows();
        $htmlString .= '</tbody>';
        return $htmlString;
    }


    /**
    * Render all the rows
    *
    * @return String HTMLCode Returns the html-code for all rows
    */
    private function _renderTableRows()
    {
        $htmlString = '';
        $htmlString .= '<tr>';

        /*foreach( $this->_Datagrid->cols as $colName => $colSet )
        {
            $a = 'b';
        } */

        #Clansuite_Xdebug::printR( Clansuite_HttpRequest::getQueryString() );

        $htmlString .= '</tr>';
        return $htmlString;
    }

    /**
    * Render a single row
    *
    * @return String HTMLCode Returns the html-code for a single row
    */
    private function _renderTableRow()
    {

    }

    /**
    * Render the column
    *
    * @return String HTMLCode Returns the html-code for a single column
    */
    private function _renderTableCol()
    {

    }

    /**
    * Render the footer
    *
    * @return String HTMLCode Returns the html-code for the footer
    */
    private function _renderTableFooter()
    {
        if( $this->_Datagrid->footer_enabled === true )
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

    /**
     * Show the whole grid
     *
     * @return String HTMLCode Returns the html-code for the datatable <table>...</table>
     */
    public function render()
    {
        $_htmlCode = '';

        $_htmlCode .= $this->_renderLabel();
        $_htmlCode .= $this->_renderDescription();

        $_innerTableData = '';

        $_innerTableData .= $this->_renderTableCaption();
        $_innerTableData .= $this->_renderTablePagination();
        $_innerTableData .= $this->_renderTableHeader();
        $_innerTableData .= $this->_renderTableBody();
        $_innerTableData .= $this->_renderTablePagination();
        $_innerTableData .= $this->_renderTableFooter();

        $_htmlCode .= $this->_renderTable($_innerTableData);

        return $_htmlCode;
    }
}

/**
* Clansuite Datagrid Col
*
* Defines one single column for the datagrid
*
*/
class Clansuite_Datagrid_Col extends Clansuite_Datagrid_Base
{
    /**
    * The sortmode of the column
    *
    * @var string
    */
    private $_sortMode = 'DESC';

    public function setSortMode($_sortMode)
    {

    }

    public function getSortMode($_sortMode)
    {

    }
}

/**
* Clansuite Datagrid Row
*
* Defines one single row for the datagrid
*
*/
class Clansuite_Datagrid_Row extends Clansuite_Datagrid_Base
{

}

/**
* Clansuite Datagrid Element
*
* Defines an element within the datagrid
*
*/
class Clansuite_Datagrid_Element extends Clansuite_Datagrid_Base
{
    /**
    * Value of the element
    *
    * @var mixed
    */
    private $_value;

    /**
    * Column object (Clansuite_Datagrid_Col)
    *
    * @var object Clansuite_Datagrid_Col
    */
    private $_col;

    /**
    * Row object (Clansuite_Datagrid_Row)
    *
    * @var object Clansuite_Datagrid_Row
    */
    private $_row;

    //----------------------------
    // Setter
    //----------------------------

    /**
    * Set the value of the element
    *
    * @param mixed $var
    */
    public function setValue($var)    { $this->_value = $var; }

    /**
    * Set the column object of this element
    *
    * @param Clansuite_Datagrid_Col $col
    */
    public function setCol($col)      { $this->_col = $col; }

    /**
    * Set the row object of this element
    *
    * @param Clansuite_Datagrid_Row $row
    */
    public function setRow($row)      { $this->_row = $row; }

    //----------------------------
    // Getter
    //----------------------------

    /**
    * Returns the value of this element
    *
    * @return mixed $_value
    */
    public function getValue()  { return $this->_value; }

    /**
    * Returns the column object of this element
    *
    * @return Clansuite_Datagrid_Col $_col
    */
    public function getCol()    { return $this->_col; }

    /**
    * Returns the row object of this element
    *
    * @return Clansuite_Datagrid_Row $_row
    */
    public function getRow()    { return $this->_row; }

    /**
    * Render the value
    *
    * @return mixed $_value Returns the value (maybe manipulated by this function)
    */
    public function render()
    {
        return $this->getValue();
    }
}

?>
