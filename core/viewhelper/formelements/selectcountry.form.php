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
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') == false){ die('Clansuite not loaded. Direct Access forbidden.');}

if (!class_exists('Clansuite_Formelement_Select',false)) { require dirname(__FILE__) . '/select.form.php'; }

/**
 *
 *  Clansuite_Form
 *  |
 *  \- Clansuite_Formelement_Select
 *     |
 *     \- Clansuite_Formelement_Selectcountry
 */
class Clansuite_Formelement_Selectcountry extends Clansuite_Formelement_Select implements Clansuite_Formelement_Interface
{
    /**
     * getCountries()
     *
     * @return array with country names
     */
    public function getCountries()
    {
        $countries = array (
            _("Afghanistan"),
            _("Albania"),
            _("Algeria"),
            _("American Samoa"),
            _("Andorra"),
            _("Angola"),
            _("Anguilla"),
            _("Antarctica"),
            _("Antigua and Barbuda"),
            _("Argentina"),
            _("Armenia"),
            _("Aruba"),
            _("Australia"),
            _("Austria"),
            _("Azerbaijan"),
            _("Bahamas"),
            _("Bahrain"),
            _("Bangladesh"),
            _("Barbados"),
            _("Belarus"),
            _("Belgium"),
            _("Belize"),
            _("Benin"),
            _("Bermuda"),
            _("Bhutan"),
            _("Bolivia"),
            _("Bosnia and Herzegovina"),
            _("Botswana"),
            _("Bouvet Island"),
            _("Brazil"),
            _("British Indian Ocean Territory"),
            _("Brunei Darussalam"),
            _("Bulgaria"),
            _("Burkina Faso"),
            _("Burundi"),
            _("Cambodia"),
            _("Cameroon"),
            _("Canada"),
            _("Cape Verde"),
            _("Cayman Islands"),
            _("Central African Republic"),
            _("Chad"),
            _("Chile"),
            _("China"),
            _("Christmas Island"),
            _("Cocos Islands"),
            _("Colombia"),
            _("Comoros"),
            _("Congo"),
            _("Congo, Democratic Republic of the"),
            _("Cook Islands"),
            _("Costa Rica"),
            _("Cote d'Ivoire"),
            _("Croatia"),
            _("Cuba"),
            _("Cyprus"),
            _("Czech Republic"),
            _("Denmark"),
            _("Djibouti"),
            _("Dominica"),
            _("Dominican Republic"),
            _("Ecuador"),
            _("Egypt"),
            _("El Salvador"),
            _("Equatorial Guinea"),
            _("Eritrea"),
            _("Estonia"),
            _("Ethiopia"),
            _("Falkland Islands"),
            _("Faroe Islands"),
            _("Fiji"),
            _("Finland"),
            _("France"),
            _("French Guiana"),
            _("French Polynesia"),
            _("Gabon"),
            _("Gambia"),
            _("Georgia"),
            _("Germany"),
            _("Ghana"),
            _("Gibraltar"),
            _("Greece"),
            _("Greenland"),
            _("Grenada"),
            _("Guadeloupe"),
            _("Guam"),
            _("Guatemala"),
            _("Guinea"),
            _("Guinea-Bissau"),
            _("Guyana"),
            _("Haiti"),
            _("Heard Island and McDonald Islands"),
            _("Honduras"),
            _("Hong Kong"),
            _("Hungary"),
            _("Iceland"),
            _("India"),
            _("Indonesia"),
            _("Iran"),
            _("Iraq"),
            _("Ireland"),
            _("Isle of Man"),
            _("Israel"),
            _("Italy"),
            _("Jamaica"),
            _("Japan"),
            _("Jordan"),
            _("Kazakhstan"),
            _("Kenya"),
            _("Kiribati"),
            _("Kuwait"),
            _("Kyrgyzstan"),
            _("Laos"),
            _("Latvia"),
            _("Lebanon"),
            _("Lesotho"),
            _("Liberia"),
            _("Libya"),
            _("Liechtenstein"),
            _("Lithuania"),
            _("Luxembourg"),
            _("Macao"),
            _("Madagascar"),
            _("Malawi"),
            _("Malaysia"),
            _("Maldives"),
            _("Mali"),
            _("Malta"),
            _("Marshall Islands"),
            _("Martinique"),
            _("Mauritania"),
            _("Mauritius"),
            _("Mayotte"),
            _("Mexico"),
            _("Micronesia"),
            _("Moldova"),
            _("Monaco"),
            _("Mongolia"),
            _("Montenegro"),
            _("Montserrat"),
            _("Morocco"),
            _("Mozambique"),
            _("Myanmar"),
            _("Namibia"),
            _("Nauru"),
            _("Nepal"),
            _("Netherlands"),
            _("Netherlands Antilles"),
            _("New Caledonia"),
            _("New Zealand"),
            _("Nicaragua"),
            _("Niger"),
            _("Nigeria"),
            _("Norfolk Island"),
            _("North Korea"),
            _("Norway"),
            _("Oman"),
            _("Pakistan"),
            _("Palau"),
            _("Palestinian Territory"),
            _("Panama"),
            _("Papua New Guinea"),
            _("Paraguay"),
            _("Peru"),
            _("Philippines"),
            _("Pitcairn"),
            _("Poland"),
            _("Portugal"),
            _("Puerto Rico"),
            _("Qatar"),
            _("Romania"),
            _("Russian Federation"),
            _("Rwanda"),
            _("Saint Helena"),
            _("Saint Kitts and Nevis"),
            _("Saint Lucia"),
            _("Saint Pierre and Miquelon"),
            _("Saint Vincent and the Grenadines"),
            _("Samoa"),
            _("San Marino"),
            _("Sao Tome and Principe"),
            _("Saudi Arabia"),
            _("Senegal"),
            _("Serbia"),
            _("Seychelles"),
            _("Sierra Leone"),
            _("Singapore"),
            _("Slovakia"),
            _("Slovenia"),
            _("Solomon Islands"),
            _("Somalia"),
            _("South Africa"),
            _("South Georgia"),
            _("South Korea"),
            _("Spain"),
            _("Sri Lanka"),
            _("Sudan"),
            _("Suriname"),
            _("Svalbard and Jan Mayen"),
            _("Swaziland"),
            _("Sweden"),
            _("Switzerland"),
            _("Syrian Arab Republic"),
            _("Taiwan"),
            _("Tajikistan"),
            _("Tanzania"),
            _("Thailand"),
            _("The Former Yugoslav Republic of Macedonia"),
            _("Timor-Leste"),
            _("Togo"),
            _("Tokelau"),
            _("Tonga"),
            _("Trinidad and Tobago"),
            _("Tunisia"),
            _("Turkey"),
            _("Turkmenistan"),
            _("Tuvalu"),
            _("Uganda"),
            _("Ukraine"),
            _("United Arab Emirates"),
            _("United Kingdom"),
            _("United States"),
            _("United States Minor Outlying Islands"),
            _("Uruguay"),
            _("Uzbekistan"),
            _("Vanuatu"),
            _("Vatican City"),
            _("Venezuela"),
            _("Vietnam"),
            _("Virgin Islands, British"),
            _("Virgin Islands, U.S."),
            _("Wallis and Futuna"),
            _("Western Sahara"),
            _("Yemen"),
            _("Zambia"),
            _("Zimbabwe")
         );

         return $countries;
    }

    public function render()
    {
        $html  = '';
        $select_element = new Clansuite_Formelement_Select();
        $select_element->setOptions($this->getCountries());
        $html .= $select_element;
        return $html;
    }

    public function __toString()
    {
        $this->render();
    }
}
?>