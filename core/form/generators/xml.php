<?php
   /**
    * Koch Framework
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Koch Framework".
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
    die('Koch Framework not loaded. Direct Access forbidden.');
}

namespace Koch\Form\Generator;

/**
 * Koch FrameworkForm Generator via XML
 *
 * Purpose:
 * 1) form generation (html representation) from an xml description file (xml->form(html))
 * 2) xml generation from an array description of the form (form(array)->xml).
 */
class XML extends Koch_Form implements FormGenerator
{
    /**
     * Facade/Shortcut
     */
    public function generate($array)
    {
        $this->generateFormByXML($array);
    }

    /**
     * Generates a formular from a XML description file.
     *
     * @param string $filename XML file with formular description.
     * @return \Koch_Array_Formgenerator
     */
    public function generateFormByXML($filename)
    {
        # XML -> toArray -> Koch_Array_Formgenerator->generate($array)
        $array = array();
        $array = new Koch_Config($filename);

        #Koch_Debug::firebug($filename);
        #Koch_Debug::firebug($array);
        $form = '';
        $form = new Koch_Array_Formgenerator($array);

        #Koch_Debug::firebug($form);

        return $form;
    }



    /**
     * Generates a XML Form Description File from an form describing array
     *
     * @param $array
     */
    public function generateXMLByArray($array)
    {
        /* $filename = ROOT_MODULES . $array['modulename'] . DS . 'forms/';
          $filename .= $array['actionname'] . 'form.xml.php';

          Koch_Config_XML::writeConfig($filename, $array);
         */
    }

}
?>