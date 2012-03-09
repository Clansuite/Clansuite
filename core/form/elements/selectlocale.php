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

namespace Koch\Formelement;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

class Selectlocale extends Select implements Formelement
{
    /**
     * A locale drop-down select list
     *
     * You will find the value of the drop down in $_POST['locale']!
     */
    public function __construct()
    {
        # include locale arrays
        include ROOT_CORE . 'gettext/locales.gettext.php';

        /**
         * prepare array structure for dropdown ( key => value )
         */
        $options = array();

        foreach($l10n_sys_locales as $locale => $locale_array)
        {
            /**
             * Key is the locale name.
             *
             * a locale name has the form ‘ll_CC’.
             * Where ‘ll’ is an ISO 639 two-letter language code, and ‘CC’ is an ISO 3166 two-letter country code.
             * Both codes are separated by a underscore.
             *
             * For example, for German in Germany, ll is de, and CC is DE. The locale is "de_DE".
             * For example, for German in Austria, ll is de, and CC is AT. The locale is "de_AT".
             */
            $key = $locale;

            /**
             * Value consists of a long form of the language name and the locale code with hyphen.
             * This string will be displayed in the dropdown.
             * For example, "Deutsch/Deutschland (de-DE)" or "Suomi (fi-FI)".
             *
             * "lang-www" contains a hyphen and not an underscore!
             */
            $value = $locale_array['lang-native'] . ' (' . $locale_array['lang-www'] . ')';

            $options[$key] = $value;
        }

        $this->setOptions($options);

        $this->setLabel( _('Select Locale') );

        # You will find the value of the drop down in $_POST['locale']!
        $this->setName('locale');
    }
}
?>