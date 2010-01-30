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
    private $_Alias;
    private $_Id;
    private $_Name;
    private $_Class;
    private $_Style;

    // Setters
    public function setAlias($_)   { $this->_Alias = $_; }
    public function setName($_)    { $this->_Name = $_; }
    public function setClass($_)   { $this->_Class = $_; }
    public function setId($_)      { $this->_Id = $_; }
    public function setStyle($_)   { $this->_Style = $_; }

    // Getters
    public function getAlias()   { return $this->_Alias; }
    public function getName()    { return $this->_Name; }
    public function getClass()   { return $this->_Class; }
    public function getId()      { return $this->_Id; }
    public function getStyle()   { return $this->_Style; }
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
    private $_QueryName = 'fetchAll';

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
    private $_SortReverseDefinitions = array(
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
    private $_Cols          = array();

    /**
    * Array of Clansuite_Datagrid_Row objects
    *
    * @var array
    */
    private $_Rows          = array();

    /**
    * Array of Clansuite_Datagrid_Cell objects
    *
    * @var array
    */
    private $_Cells      = array();


    /**
    * The label for this datagrid (<div>...</div>)
    *
    * @var string
    */
    private $_Label         = 'Datagrid';

    /**
    * The caption (<caption>...</caption>) for this datagrid
    *
    * @var string
    */
    private $_Caption       = 'Datagrid';

    /**
    * The description for this datagrid
    *
    * @var string
    */
    private $_Description   = 'This is a clansuite datagrid';

    /**
    * The datagrid type
    *
    * @todo implement
    * @var string
    */
    private $_DatagridType  = 'Normal';

    /**
    * The basepath for this datagrid
    * Every Link in the datagrid (sorting, pagination, etc.) has this URI as base
    *
    * @var string
    */
    private $_BasePath       = 'index.php';

    /**
    * Boolean datagrid values for configuration, wrapped into an array
    *
    * @var array
    */
    private $_Features = array(
        'Label'         => true,
        'Caption'       => true,
        'Description'   => true,
        'Header'        => true,
        'Pagination'    => true,
        'Footer'        => true,
        'Sorting'       => true
    );

    /**
    * An array of sets to configure the columns
    *
    * @var array
    */
    private $_ColumnSets = array();

    /**
    * Check for datagrid features
    *
    * @see $this->_features
    * @param string $feature
    * @return boolean $this->features[$_feature]
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
    * @param mixed $feature
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
    public function disableFeature($toggleVar)
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

        # attach Datagrid to renderer
        $this->setRenderer(new Clansuite_Datagrid_Renderer($this));

        # set the internal ref for the datatable of doctrine
        $this->_Datatable = $_Datatable;

        # Set all columns
        $this->_setColumnSets($_ColumnSets);

        # set queryname
        $this->_QueryName = $_QueryName;

        # generate a doctrine query
        $this->_generateQuery();

        # generate default datasets that can be overwritten
        $this->_initDatagrid();
    }

    /**
    * Render the datagrid
    *
    * @return string $_html Returns html-code
    */
    public function render()
    {
        return $this->getRenderer()->render();
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

    /**
    * Set the renderer object
    *
    * @param Clansuite_Datagrid_Renderer $_Renderer
    */
    public function setRenderer($_Renderer) { $this->_Renderer = $_Renderer; }

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
        $_Result = $this->_PagedQuery->execute();

        $this->_generateCols($_Result);
        $this->_generateRows($_Result);

        # scalar values
        $this->_Alias         = $this->_Datatable->getClassnameToReturn();
        $this->_Id            = 'Datagrid' . $this->_Alias . 'Id';
        $this->_Name          = 'Datagrid' . $this->_Alias . 'Name';
        $this->_Class         = 'Datagrid' . $this->_Alias . 'Class';
        $this->_Label         = $this->_Alias;
        $this->_Caption       = $this->_Alias;
    }

    // Setter
    public function setLabel($_Label)               { $this->_Label = $_Label; }
    public function setCaption($_Caption)           { $this->_Caption = $_Caption; }
    public function setDescription($_Description)   { $this->_Description = $_Description; }
    public function setCols($_Cols)                 { $this->_Cols = $_Cols; }

    // Getter
    public function getLabel()          { return $this->_Label; }
    public function getCaption()        { return $this->_Caption; }
    public function getDescription()    { return $this->_Description; }

    /**
    * Returns the column objects
    *
    * @return array Clansuite_Datagrid_Col
    */
    public function getCols()           { return $this->_Cols; }

    /**
    * Get the pendant to DESC/ASC
    *
    * @return string
    */
    public function getSortReverseDefinition($_sortMode)
    {
        if( !isset($this->_SortReverseDefinitions[$_sortMode]) )
            throw new Clansuite_Exception(_('This sortMode is not in the list: ') . $_sortMode );

        return $this->_SortReverseDefinitions[$_sortMode];
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
    *                    'Sort'    => 'DESC'
    *                    ),
    *           1 => array(
    *                    'Alias'   => 'Status',
    *                    'DBCol'   => 'n.news_status',
    *                    'Name'    => _('Status'),
    *                    'Sort'    => 'ASC'
    *                    ),
    *    );
    */
    private function _setColumnSets($_ColumnSets = array())
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
        $this->_ColumnSets = $_ColumnSets;
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
    public function setColumnSets($_ColumnSets = array())
    {
        $this->_setColumnSets($_ColumnSets);
        $this->_updateDatagrid();
    }

    /**
    * Get the datagrid cols
    *
    * @return array Clansuite_Datagrid_Col
    */
    public function getColumnSets()
    {
        return $this->_ColumnSets;
    }

    /**
     * Generates all col objects
     */
    private function _generateCols($_Result)
    {
        $_Position = 10;
        foreach( $this->getColumnSets() as $colKey => $colSet )
        {
            if( isset($_Result[0][$colSet['DBCol']]) )
            {
                $oCol = new Clansuite_Datagrid_Col();
                $oCol->setAlias($colSet['Alias']);
                $oCol->setId($colSet['Alias']);
                $oCol->setName($colSet['Name']);
                $oCol->setSortMode($colSet['Sort']);
                $oCol->setPosition($_Position);
                $_Position += 10;
                array_push($this->_Cols, $oCol);
            }
        }
        #Clansuite_Xdebug::printR($this->getColumnSets());
        #Clansuite_Xdebug::printR($this->_Cols);
    }

    /**
     * Generates all row objects
     */
    private function _generateRows($_Result)
    {
        foreach( $_Result as $dbKey => $dbSet )
        {
            foreach( $this->_ColumnSets as $colKey => $colSet )
            {
                if( isset($dbSet[$colSet['DBCol']]) )
                {
                    #$this->rows[$dbKey][$colSet['Alias']] = $dbSet[$colSet['DBCol']];
                }
            }
        }
        #Clansuite_Xdebug::printR($this->_Rows);
    }



    /**
     * Generates a customized query for this table
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


    /**
    * The position of a column
    *
    * @var int
    */
    private $_Position = 0;

    /**
    * Set the sort-mode (ASC, DESC, NATASC, NETDESC, ...)
    *
    * @param string $_sortMode
    */
    public function setSortMode($_sortMode)
    {
        $this->_sortMode = $_sortMode;
    }

    /**
    * Get the sort-mode
    *
    * @return string $_sortMode
    */
    public function getSortMode()
    {
        return $this->_sortMode;
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
    * Set the position
    *
    * @return int
    */
    public function getPosition()
    {
        return $this->_Position;
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
* Clansuite Datagrid Cell
*
* Defines a cell within the datagrid
*
*/
class Clansuite_Datagrid_Cell extends Clansuite_Datagrid_Base
{
    /**
    * Value of the cell
    *
    * @var mixed
    */
    private $_Value;

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

    //----------------------------
    // Setter
    //----------------------------

    /**
    * Set the value of the cell
    *
    * @param mixed $_Value
    */
    public function setValue($_Value)    { $this->_Value = $_Value; }

    /**
    * Set the column object of this cell
    *
    * @param Clansuite_Datagrid_Col $_Col
    */
    public function setCol($_Col)      { $this->_Col = $_Col; }

    /**
    * Set the row object of this cell
    *
    * @param Clansuite_Datagrid_Row $_Row
    */
    public function setRow($_Row)      { $this->_Row = $_Row; }

    //----------------------------
    // Getter
    //----------------------------

    /**
    * Returns the value of this cell
    *
    * @return mixed $_Value
    */
    public function getValue()  { return $this->_Value; }

    /**
    * Returns the column object of this cell
    *
    * @return Clansuite_Datagrid_Col $_Col
    */
    public function getCol()    { return $this->_Col; }

    /**
    * Returns the row object of this cell
    *
    * @return Clansuite_Datagrid_Row $_Row
    */
    public function getRow()    { return $this->_Row; }

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


class Clansuite_Datagrid_Renderer
{
    /**
    * The datagrid
    *
    * @var Clansuite_Datagrid $_Datagrid
    */
    private $_Datagrid;

    /**
    * Set the datagrid object
    *
    * @param Clansuite_Datagrid $_Datagrid
    */
    public function setDatagrid($_Datagrid) { $this->_Datagrid = $_Datagrid; }

    /**
    * Get the Datagrid object
    *
    * @return Clansuite_Datagrid $_Datagrid
    */
    public function getDatagrid() { return $this->_Datagrid; }

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

    /**
    * Render the datagrid table
    *
    * @param String HTMLCode Returns the html-code for the table
    * @return String HTMLCode Returns the html-code of the datagrid
    */
    private function _renderTable($_innerTableData)
    {
        return '<table class="DatagridTable '. $this->getDatagrid()->getClass() .'" id="'. $this->getDatagrid()->getId() .'" name="'. $this->getDatagrid()->getName() .'">' . CR . $_innerTableData  . CR . '</table>';
    }



    /**
    * Render the label
    *
    * @return String HTMLCode Returns the html-code for the label if enabled
    */
    private function _renderLabel()
    {
        if( $this->getDatagrid()->isEnabled('Label') )
            return '<div class="DatagridTableLabel">' . CR . $this->getDatagrid()->getLabel() . CR . '</div>';
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
        if( $this->getDatagrid()->isEnabled('Description') )
            return '<div class="DatagridTableDescription">' . CR . $this->getDatagrid()->getDescription() . CR . '</div>';
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
        if( $this->getDatagrid()->isEnabled('Header') )
        {

            $htmlString = '';
            $htmlString .= '<thead>';
            $htmlString .= '<tr>';

            foreach( $this->getDatagrid()->getCols() as $oCol )
            {
                $htmlString .= '<th id="ColHeaderId-'. $oCol->getAlias() . '" class="ColHeader ColHeader-'. $oCol->getAlias() .'">';
                $htmlString .= $oCol->getName();
                $htmlString .= '&nbsp;<a href="' . $this->_getSortString($oCol->getPosition(), $this->getDatagrid()->getSortReverseDefinition($oCol->getSortMode())) . '">' . _($oCol->getSortMode()) . '</a>';
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
        if( $this->getDatagrid()->isEnabled("Pagination") )
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

        /*foreach( $this->getDatagrid()->cols as $colName => $colSet )
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


?>
