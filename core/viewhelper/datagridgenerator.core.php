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

if (!class_exists('Clansuite_Form')) { require 'form.core.php'; }

/**
 * Clansuite Datagrid Generator via Doctrine Records
 *
 * Purpose: automatic datagrid generation from doctrine records/tables.
 *
 * @see http://www.doctrine-project.org/Doctrine_Table/1_2
 * @param object Doctrine_Table
 */
class Clansuite_Doctrine_Datagridgenerator
{
    public $DataTable;
    
    /**
    * Constructor
    */
    public function __construct($DataTable)
    {
        if( !($DataTable instanceof Doctrine_Table) )
        {
            throw new Clansuite_Exception(_('Incoming data seems not to be a valid Doctrine_Table'));
        }
    }
    
    /**
     * Generate a datatable
     * 
     * @params 
     */
    private function _generateDataGrid()
    {
        clansuite_xdebug::printR($this->DataTable);
    }
    
    /**
     * Show of the whole grid
     * 
     * @return String HTMLCode Returns the HTML-Code for the datatable <table>...</table>
     */
    public function render()
    {
        #todo
        return $this->_generateDataGrid();
    }
}
?>
