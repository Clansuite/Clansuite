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
    /**
     * The variable form_array contains the formdescription.
     *
     * @var array
     */
    protected $form_array;

    public function __construct(array $form_array)
    {
        # init parent Clansuite_Form with name, method and action
        parent::__construct( $form_array['form']['name'],
                             $form_array['form']['method'],
                             $form_array['form']['action'] );
        
        # unset the key form inside form_array, because the "form" description is no longer needed, parent Clansuite_Form is already informed
        unset($form_array['form']);

        # now assign the form description array to work with it
        $this->form_array = $form_array;

        # first we ensure, that the formdescription meets certain requirements
        if($this->validateFormArrayStructure($this->form_array))
        {
            # now that the form description is valid, we generate the form
            $this->generateFormByArray();
        }
        else
        {
            die('Ensure that all obligatory formelements are present.');
        }

        return $this;
    }

    /**
     * Level 1 - The form
     *
     * $form_array_section is an array of the following structure:
     *
     * @todo $form_array_section description
     *
     * Level 2 - The formelements
     *
     * $form_array_element is an array of the following structure:
     *
     * Obligatory Elements to describe the Formelement
     * These array keys have to exist!
     *
     *   [id]            => resultsPerPage_show
     *   [name]          => resultsPerPage_show
     *   [label]         => Results per Page for Action Show
     *   [description]   => This defines the Number of Newsitems to show per Page in Newsmodule
     *   [formfieldtype] => text
     *
     * Optional Elements to describe the Formelement
     *
     *   [value] => 3
     *   [class] => cssClass
     *
     * @param $form_array the form array
     * @return boolean true/false
     */
    public function validateFormArrayStructure($form_array)
    {
        $obligatory_form_array_elements = array('id', 'name', 'label', 'description', 'formfieldtype', 'value');
        $optional_form_array_elements   = array('class', 'decorator');

        # loop over all elements of the form description array
        foreach($form_array as $form_array_section => $form_array_elements)
        {
            #clansuite_xdebug::printR($form_array_elements);
            #clansuite_xdebug::printR($form_array_section);

            foreach($form_array_elements as $form_array_element_number => $form_array_element)
            {
                #clansuite_xdebug::printR(array_keys($form_array_element));
                #clansuite_xdebug::printR($obligatory_form_array_elements);

                # this does the validation. it ensures that required keys are present
                $report_differences_or_true = Clansuite_Functions::array_compare($obligatory_form_array_elements, array_keys($form_array_element));

                # errorcheck for valid formfield elements
                if(is_array($report_differences_or_true) == false)
                {
                    # form description arrays are identical
                    return true;
                }
                else
                {
                    # form description arrays are not identical
                    # @todo throw exception with errorcode, create excpetion tpl with this text
                    die('Form Array Structure not valid. <br />
                         The first array shows the obligatory form array elements. <br />
                         The second array shows your form definition. <br />
                         Please add the missing array keys with values. <br />'
                         .var_dump($report_differences_or_true));
                }
            }
        }
    }   

    public function generateFormByArray()
    {
        # debug display incomming form description array
        #clansuite_xdebug::printR($this->array);

        # loop over all elements of the form description array
        foreach($this->form_array as $form_array_section => $form_array_elements)
        {
            #clansuite_xdebug::printR($form_array_elements);
            #clansuite_xdebug::printR($form_array_section);

            foreach($form_array_elements as $form_array_element_number => $form_array_element)
            {
               #clansuite_xdebug::printR($form_array_element);

               # @todo ensure these elements exist !!!

               # add a new element to this form, position it by it's number in the array
               $this->addElement( $form_array_element['formfieldtype'], $form_array_element_number );

               # fetch the new formelement object
               $formelement = $this->getElementByPosition($form_array_element_number);

               # and apply the settings (id, name, description, value) to it
               $formelement->setID($form_array_element['id']);

               # provide array access to the form data (in $_POST) by prefixing it with the formulars name
               # @todo if you group formelements, add the name of the group here
               $formelement->setName($this->getName().'['.$form_array_element['name'].']');
               $formelement->setDescription($form_array_element['description']);

               # @todo consider this as formdebug display
               #$formelement->setLabel($this->getName().'['.$form_array_element['name'].']');

               $formelement->setLabel($form_array_element['label']);

               # set the options['selected'] value as default value
               if(isset($form_array_element['options']['selected']))
               {
                   $formelement->setDefault($form_array_element['options']['selected']);
                   unset($form_array_element['options']['selected']);
               }

               /**
                * check if $form_array_element['value'] is of type array or single value
                * array indicates, that we have a request for
                * something like a multiselect formfield with several options
                */
               if(is_array($form_array_element['value']) == false)
               {
                   $formelement->setValue($form_array_element['value']);
               }
               else
               {
                   $formelement->setOptions($form_array_element['value']);
               }

               /**
                * OPTIONAL ELEMENTS
                */

               # if we have a class attribute defined, then add it (optional)
               if(isset($form_array_element['class']))
               {
                   $formelement->setClass($form_array_element['class']);
               }

               /**
                * set a decorator for the formelement
                * why is this optional, because: if you do not define a decorator, the default one will be active
                */
               if(isset($form_array_element['decorator']))
               {
                   $formelement->setDecorator($form_array_element['decorator']);
               }
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

/**
 * Clansuite Form Generator via XML
 *
 * Purpose: automatic form generation from an xml description file.
 */
class Clansuite_XML_Formgenerator extends Clansuite_Form
{
    /**
     * Facade/Shortcut
     */
    public function generate($array)
    {
        $this->generateFormByXML($array);
    }
}
?>
