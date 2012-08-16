<?php

/**
 * Koch Framework
 * Jens-André Koch © 2005 - onwards
 *
 * This file is part of "Koch Framework".
 *
 * License: GNU/GPL v2 or any later version, see LICENSE file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Koch\Form\Generators;

/**
 * Koch FrameworkForm Generator via XML
 *
 * Purpose:
 * 1) form generation (html representation) from an xml description file (xml->form(html))
 * 2) xml generation from an array description of the form (form(array) ->xml).
 */
class XML extends Koch_Form implements FormGeneratorInterface
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
     * @param  string                    $filename XML file with formular description.
     * @return \Koch_Array_Formgenerator
     */
    public function generateFormByXML($filename)
    {
        // XML -> toArray -> Koch_Array_Formgenerator->generate($array)
        $array = array();
        $array = new Koch\Config($filename);

        #\Koch\Debug\Debug::firebug($filename);
        #\Koch\Debug\Debug::firebug($array);
        $form = '';
        $form = new Koch_Array_Formgenerator($array);

        #\Koch\Debug\Debug::firebug($form);

        return $form;
    }

    /**
     * Generates a XML Form Description File from an form describing array
     *
     * @param $array
     */
    public function generateXMLByArray($array)
    {
        /* $filename = ROOT_MODULES . $array['modulename'] . DIRECTORY_SEPARATOR . 'forms/';
          $filename .= $array['actionname'] . 'form.xml.php';

          Koch\Config_XML::writeConfig($filename, $array);
         */
    }

}
