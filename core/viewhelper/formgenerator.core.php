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
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.');}

if (!class_exists('Clansuite_Form')) { require 'form.core.php'; }

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
            $fieldName = $table->getClassnameToReturn()."[$columnName]";

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

/**
 * Clansuite Form Generator via Array
 *
 * Purpose: automatic form generation from an array.
 */
class Clansuite_Array_Formgenerator extends Clansuite_Form
{
    protected $form_array;
    
    public function __construct(array $form_array)
    {
        parent::__construct( $form_array['form']['name'], $form_array['form']['method'], $form_array['form']['action'] );
    
        unset($form_array['form']);
        
        $this->form_array = $form_array;
        
        return $this;
    }
    
    public function generateFormByArray()
    {
        # new form
        $form = array();
        
        # debug display incomming form description array
        #clansuite_xdebug::printR($this->form_array);
        
        # loop over all elements of the form description array
        foreach($this->form_array as $form_array_sectionname => $form_array_elements)
        {
            #clansuite_xdebug::printR($form_array_elements);
            #clansuite_xdebug::printR($form_array_sectionname);
            
            foreach($form_array_elements as $form_array_element_number => $form_array_element)
            {
               /**
                * $form_array_element is an array of the following structure:
                *
                * Array (
                *     [id] => resultsPerPage_show
                *     [name] => resultsPerPage_show
                *     [description] => Newsitems to show in Newsmodule
                *     [formfieldtype] => text
                *     [value] => 3
                * )
                */               
               #clansuite_xdebug::printR($form_array_element);
               
               # add a new element to this form, position it by it's number in the array
               $this->addElement( $form_array_element['formfieldtype'], $form_array_element_number );
               
               # fetch the new formelement object
               $formelement = $this->getElementByPosition($form_array_element_number);
              
               # and apply the settings (id, name, description, value) to it
               $formelement->setID($form_array_element['id']);
               $formelement->setName($form_array_element['name']);
               $formelement->setDescription($form_array_element['description']);
               $formelement->setValue($form_array_element['value']);               
              
               # attach to form html
               #$form .= parent::render();
                                
            }                              
        }
        # unset the form description array, because we are done with it
        unset($this->form_array);
        
        return $this->render();    
    }
    
    /**
     * Facade/Shortcut 
     */
    public function generate($array)
    {
        $this->generateFormByArray($array);
    }    
} 
?>