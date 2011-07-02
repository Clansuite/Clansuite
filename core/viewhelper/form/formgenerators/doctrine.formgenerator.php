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
if (false == class_exists('Clansuite_Form',false))
{
    include __DIR__ . '/form.core.php';
}

/**
 * Clansuite Form Generator via Doctrine Records
 *
 * Purpose: automatic form generation from doctrine records/tables.
 *
 * @todo determine and set excluded columns (maybe in record?)
 */
class Clansuite_Doctrine_Formgenerator extends Clansuite_Form
{
    /**
     * The typeMap is an array of all doctrine column types.
     * It maps the database fieldtypes to their related html inputfield types.
     *
     * @var array
     */
    protected $typeMap = array(
            'boolean'    => 'checkbox',
            'integer'    => 'text',
            'float'      => 'text',
            'decimal'    => 'string',
            'string'     => 'text',
            'text'       => 'textarea',
            'enum'       => 'select',
            'array'      => null,
            'object'     => null,
            'blob'       => null,
            'clob'       => null,
            'time'       => 'text',
            'timestamp'  => 'text',
            'date'       => 'text',
            'gzip'       => null
    );

    /**
     * Database columns which should not appear in the form.
     *
     * @var array
     */
    protected $excludedColumns   = array();

    /**
     * Generates a Form from a Table
     *
     * @param string $DoctrineTableName Name of the Doctrine Tablename to build the form from.
     */
    public function generateFormByTable($DoctrineTableName)
    {
        # init form
        $form = array();

        # fetch doctrine table by record name
        $table = Doctrine::getTable($DoctrineTableName);

        # fetch all columns of that table
        $tableColumns = $table->getColumnNames();

        # loop over all columns
        foreach ( $tableColumns as $columnName) # => $columnType

        {
            # and check wheather the $columnName is to exclude
            if(in_array($columnName, $this->excludeColumns))
            {
                # stop the foreach-loop here and reenter it
                continue;
            }

            # combine classname and columnname as fieldname
            $fieldName = $table->getClassnameToReturn() . '[$columnName]';

            # if columnname is identifier
            if( $table->isIdentifier($columnName) )
            {
                # add it as an hidden field
                #$form[] = new Clansuite_Form->formfactory( 'hidden', $fieldName);
            }
            else
            {
                # transform columnName to a printable name
                $printableName = ucwords(str_replace('_','',$columnName));

                # determine the columnname type and add the formfield
                #$form[] = new Clansuite_Form->formfactory( $table->getTypeOf($columnName), $fieldName, $printableName);
            }
        }

        return $form;
    }

    /**
     * Facade/Shortcut
     */
    public function generate($array)
    {
        $this->generateFormByTable($array);
    }
}

?>